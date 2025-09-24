<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Horarios Docentes";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('horarios');

global $db;

// Procesar solicitudes AJAX
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_action'])) {
    switch($_POST['ajax_action']) {
        case 'get_docentes':
            $query = "SELECT DISTINCT u.id, u.nombre 
                      FROM docente_seccion ds
                      JOIN users u ON ds.id_usuario = u.id
                      WHERE ds.estatus = 1
                      ORDER BY u.nombre";
            $result = $db->query($query);
            
            $options = '<option value="">Seleccionar docente...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id']}'>{$row['nombre']}</option>";
            }
            
            echo $options;
            exit();
            
        case 'get_horario':
            $id_docente = $_POST['id_docente'];
            
            if(!$id_docente) {
                echo json_encode(['error' => 'Se requiere seleccionar un docente']);
                exit();
            }
            
            // Obtener horario del docente con información completa
            $query = "SELECT h.id_horario, h.dia, TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio, 
                             TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin, h.aula,
                             ds.id_docente_seccion, ds.id_materia,
                             u.nombre as docente, m.nombre_materia as materia,
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo,
                             m.horas_teoricas + m.horas_practicas as horas_totales
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      JOIN users u ON ds.id_usuario = u.id
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ?
                      ORDER BY h.dia, h.hora_inicio";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
            
            // Obtener TODOS los horarios existentes para detectar conflictos
            $query_conflictos = "SELECT h.dia, h.hora_inicio, h.hora_fin, h.aula, 
                               u.nombre as docente, u.id as id_docente,
                               m.nombre_materia as materia, c.nombre_carrera, s.codigo_seccion
                        FROM horarios h
                        JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                        JOIN users u ON ds.id_usuario = u.id
                        JOIN materias m ON ds.id_materia = m.id_materia
                        JOIN secciones s ON ds.id_seccion = s.id_seccion
                        JOIN carreras c ON s.id_carrera = c.id_carrera";
            $result_conflictos = $db->query($query_conflictos);
            $todos_horarios = $result_conflictos->fetch_all(MYSQLI_ASSOC);
            
            // Filtrar solo los horarios de otros docentes
            $conflictos = array_filter($todos_horarios, function($horario) use ($id_docente) {
                return $horario['id_docente'] != $id_docente;
            });
            
            // Generar tabla de horario
            $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            $horas_disponibles = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'];
            
            $html = '<div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Hora</th>';
            
            foreach($dias_semana as $dia) {
                $html .= "<th>$dia</th>";
            }
            
            $html .= '</tr></thead><tbody>';
            
            $celdas_ocupadas = array_fill(0, count($dias_semana), array_fill(0, count($horas_disponibles), false));
            
            foreach($horas_disponibles as $hora_index => $hora) {
                $html .= "<tr><td>$hora</td>";
                
                foreach($dias_semana as $dia_index => $dia_nombre) {
                    if(isset($celdas_ocupadas[$dia_index][$hora_index]) && $celdas_ocupadas[$dia_index][$hora_index]) {
                        $html .= '';
                        continue;
                    }
                    
                    $celdaContent = '';
                    $clasesCelda = 'horario-cell';
                    $title = '';
                    $rowspan = 1;
                    $id_horario = '';
                    $tieneConflicto = false;
                    $infoConflicto = [];
                    
                    // Verificar conflictos con otros docentes (aula o docente mismo horario)
                    foreach($conflictos as $conflicto) {
                        if($conflicto['dia'] == $dia_index) {
                            $hora_inicio_conflicto = $conflicto['hora_inicio'];
                            $hora_fin_conflicto = $conflicto['hora_fin'];
                            
                            // Verificar si el horario actual está dentro del horario conflictivo
                            if(($hora >= $hora_inicio_conflicto && $hora < $hora_fin_conflicto)) {
                                $tieneConflicto = true;
                                $infoConflicto[] = [
                                    'docente' => $conflicto['docente'],
                                    'materia' => $conflicto['materia'],
                                    'carrera' => $conflicto['nombre_carrera'],
                                    'seccion' => $conflicto['codigo_seccion'],
                                    'aula' => $conflicto['aula'],
                                    'hora_inicio' => $hora_inicio_conflicto,
                                    'hora_fin' => $hora_fin_conflicto
                                ];
                            }
                        }
                    }
                    
                    // Buscar asignación del docente
                    foreach($asignaciones as $asignacion) {
                        if($asignacion['dia'] == $dia_index && $asignacion['hora_inicio'] == $hora) {
                            $title = "Materia: {$asignacion['materia']}\nCarrera: {$asignacion['nombre_carrera']}\n";
                            $title .= "Sección: {$asignacion['codigo_seccion']}\nPeríodo: {$asignacion['id_periodo']}\n";
                            $title .= "Aula: {$asignacion['aula']}\nHoras: {$asignacion['horas_totales']}";
                            
                            $celdaContent = '<strong>' . $asignacion['materia'] . '</strong><br>' . 
                                            '<small>' . $asignacion['nombre_carrera'] . ' (' . $asignacion['codigo_seccion'] . ')</small>';
                            
                            $hora_fin = $asignacion['hora_fin'];
                            $hora_fin_index = array_search($hora_fin, $horas_disponibles);
                            
                            if($hora_fin_index !== false) {
                                $rowspan = $hora_fin_index - $hora_index + 1;
                                for($i = $hora_index; $i < $hora_fin_index; $i++) {
                                    $celdas_ocupadas[$dia_index][$i] = true;
                                }
                            }
                            
                            $clasesCelda .= ' bg-asignada';
                            $id_horario = $asignacion['id_horario'];
                            break;
                        }
                    }
                    
                    if($tieneConflicto) {
                        $clasesCelda .= ' bg-conflicto';
                        $titleConflicto = "CONFLICTO DETECTADO:\n";
                        foreach($infoConflicto as $conf) {
                            $titleConflicto .= "Docente: {$conf['docente']}\n";
                            $titleConflicto .= "Materia: {$conf['materia']}\n";
                            $titleConflicto .= "Carrera: {$conf['carrera']} ({$conf['seccion']})\n";
                            $titleConflicto .= "Aula: {$conf['aula']}\n";
                            $titleConflicto .= "Horario: {$conf['hora_inicio']} - {$conf['hora_fin']}\n\n";
                        }
                        
                        if(!$id_horario) {
                            $celdaContent = '<i class="fas fa-exclamation-triangle"></i> Conflicto';
                            $title = $titleConflicto;
                        } else {
                            $title = $title . "\n\n" . $titleConflicto;
                        }
                    }
                    
                    $html .= '<td class="' . $clasesCelda . '" data-dia="' . $dia_index . '" data-hora="' . $hora . '" ';
                    $html .= 'title="' . htmlspecialchars($title) . '" data-id="' . $id_horario . '"';
                    if($rowspan > 1) $html .= ' rowspan="' . $rowspan . '"';
                    $html .= '>' . $celdaContent . '</td>';
                }
                
                $html .= "</tr>";
            }
            
            $html .= '</tbody></table></div>';
            
            // Resumen de asignaciones y conflictos
            $html .= '<div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-info-circle mr-1"></i>
                            Resumen de Asignaciones y Conflictos
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Carrera</th>
                                        <th>Sección</th>
                                        <th>Día</th>
                                        <th>Horario</th>
                                        <th>Aula</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>';
            
            foreach($asignaciones as $asignacion) {
                // Verificar si esta asignación tiene conflictos
                $tieneConflicto = false;
                $infoConflicto = '';
                
                foreach($conflictos as $conflicto) {
                    if($conflicto['dia'] == $asignacion['dia'] && 
                       $conflicto['aula'] == $asignacion['aula'] &&
                       (($asignacion['hora_inicio'] < $conflicto['hora_fin'] && $asignacion['hora_fin'] > $conflicto['hora_inicio']))) {
                        
                        $tieneConflicto = true;
                        $infoConflicto .= "Conflicto con: {$conflicto['docente']} ({$conflicto['materia']})";
                    }
                }
                
                $estado = $tieneConflicto ? 
                    '<span class="badge badge-danger">Conflicto</span>' : 
                    '<span class="badge badge-success">OK</span>';
                
                $html .= '<tr class="' . ($tieneConflicto ? 'table-warning' : '') . '">
                            <td>' . $asignacion['materia'] . '</td>
                            <td>' . $asignacion['nombre_carrera'] . '</td>
                            <td>' . $asignacion['codigo_seccion'] . '</td>
                            <td>' . $dias_semana[$asignacion['dia']] . '</td>
                            <td>' . $asignacion['hora_inicio'] . ' - ' . $asignacion['hora_fin'] . '</td>
                            <td>' . $asignacion['aula'] . '</td>
                            <td>' . $estado . '<br><small>' . $infoConflicto . '</small></td>
                            <td><button class="btn btn-sm btn-danger btn-eliminar" data-id="' . $asignacion['id_horario'] . '">Eliminar</button></td>
                          </tr>';
            }
            
            $html .= '</tbody></table></div></div>';
            
            echo $html;
            exit();
            
        case 'guardar_asignacion':
            $dia = $_POST['dia'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin = $_POST['hora_fin'];
            $id_docente_seccion = $_POST['id_docente_seccion'];
            $aula = $_POST['aula'];
            $id_docente = $_POST['id_docente'];
            
            // Validaciones básicas
            if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora_inicio) || 
                !preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora_fin)) {
                echo json_encode(['success' => false, 'message' => 'Formato de hora inválido']);
                exit();
            }
            
            if (strtotime($hora_fin) <= strtotime($hora_inicio)) {
                echo json_encode(['success' => false, 'message' => 'La hora de fin debe ser mayor que la hora de inicio']);
                exit();
            }
            
            if($dia < 0 || $dia > 5) {
                echo json_encode(['success' => false, 'message' => 'Solo se permiten días de Lunes a Sábado']);
                exit();
            }
            
            // Validar máximo 2 horas por semana por materia
            $query = "SELECT SUM(TIME_TO_SEC(TIMEDIFF(hora_fin, hora_inicio)))/3600 as horas_semana
                      FROM horarios 
                      WHERE id_docente_seccion = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            $horas_existentes = $result->fetch_assoc()['horas_semana'] ?? 0;
            
            $nuevas_horas = (strtotime($hora_fin) - strtotime($hora_inicio))/3600;
            
            if(($horas_existentes + $nuevas_horas) > 2) {
                echo json_encode(['success' => false, 'message' => 'No se puede exceder el máximo de 2 horas semanales por materia']);
                exit();
            }
            
            // Verificar conflicto para el docente (mismo horario)
            $query = "SELECT COUNT(*) as conflicto
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      WHERE ds.id_usuario = ? AND h.dia = ? AND 
                            ((h.hora_inicio < ? AND h.hora_fin > ?) OR 
                             (h.hora_inicio >= ? AND h.hora_inicio < ?))";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iissss", $id_docente, $dia, $hora_fin, $hora_inicio, $hora_inicio, $hora_fin);
            $stmt->execute();
            $result = $stmt->get_result();
            $conflicto = $result->fetch_assoc();
            
            if($conflicto['conflicto'] > 0) {
                echo json_encode(['success' => false, 'message' => 'El docente ya tiene una clase asignada en este horario']);
                exit();
            }
            
            // Verificar conflicto con otros docentes (misma aula y horario)
            $query = "SELECT COUNT(*) as conflicto, u.nombre as docente, m.nombre_materia as materia
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      JOIN users u ON ds.id_usuario = u.id
                      JOIN materias m ON ds.id_materia = m.id_materia
                      WHERE ds.id_usuario != ? AND h.dia = ? AND h.aula = ? AND 
                            ((h.hora_inicio < ? AND h.hora_fin > ?) OR 
                             (h.hora_inicio >= ? AND h.hora_inicio < ?))
                      GROUP BY u.nombre, m.nombre_materia";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iisssss", $id_docente, $dia, $aula, $hora_fin, $hora_inicio, $hora_inicio, $hora_fin);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                $conflictos = [];
                while($row = $result->fetch_assoc()) {
                    $conflictos[] = "{$row['docente']} ({$row['materia']})";
                }
                echo json_encode([
                    'success' => false, 
                    'message' => 'Conflicto en el aula con: ' . implode(', ', $conflictos),
                    'conflictos' => $conflictos
                ]);
                exit();
            }
            
            // Crear nueva asignación
            $query = "INSERT INTO horarios (id_docente_seccion, dia, hora_inicio, hora_fin, aula)
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("issss", $id_docente_seccion, $dia, $hora_inicio, $hora_fin, $aula);
            
            if($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $db->error]);
            }
            exit();
            
        case 'get_docente_materias':
            $id_docente = $_POST['id_docente'];
            
            $query = "SELECT ds.id_docente_seccion, m.nombre_materia, 
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo,
                             m.horas_teoricas + m.horas_practicas as horas_totales
                      FROM docente_seccion ds
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ? AND ds.estatus = 1
                      ORDER BY s.id_periodo DESC, c.nombre_carrera, m.nombre_materia";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $options = '<option value="">Seleccionar materia...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id_docente_seccion']}' data-horas='{$row['horas_totales']}'>
                                {$row['nombre_materia']} - {$row['nombre_carrera']} ({$row['codigo_seccion']}) - {$row['id_periodo']}
                            </option>";
            }
            
            echo $options;
            exit();
            
        case 'eliminar_asignacion':
            $id_horario = $_POST['id_horario'];
            
            $query = "DELETE FROM horarios WHERE id_horario = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_horario);
            
            if($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $db->error]);
            }
            exit();
            
        case 'asignacion_automatica':
            $id_docente = $_POST['id_docente'];
            $dias_opcion = $_POST['dias_opcion']; // 'semana' o 'sabado'
            $horas_min = isset($_POST['horas_min']) ? (int)$_POST['horas_min'] : 7;
            $horas_max = isset($_POST['horas_max']) ? (int)$_POST['horas_max'] : 16;
            $duracion_clase = isset($_POST['duracion_clase']) ? (int)$_POST['duracion_clase'] : 2;

            // Obtener materias del docente ordenadas por horas (mayor a menor)
            $query = "SELECT ds.id_docente_seccion, m.nombre_materia, 
                             m.horas_teoricas + m.horas_practicas as horas_totales,
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo
                      FROM docente_seccion ds
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ? AND ds.estatus = 1
                      ORDER BY horas_totales DESC";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();

            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                // Limitar a máximo 2 horas por materia
                $row['horas_totales'] = min($row['horas_totales'], 2);
                $asignaciones[] = $row;
            }

            // Eliminar horarios existentes del docente
            $db->query("DELETE FROM horarios WHERE id_docente_seccion IN (SELECT id_docente_seccion FROM docente_seccion WHERE id_usuario = $id_docente)");

            // Configurar días disponibles según la opción seleccionada
            $dias_disponibles = ($dias_opcion == 'sabado') ? [5] : range(0, 4); // 0-4 = Lunes-Viernes, 5 = Sábado

            // Configurar horas disponibles
            $horas_base = range($horas_min, $horas_max - 1);
            $horas_disponibles = array_map(function($h) { return sprintf('%02d:00', $h); }, $horas_base);

            // Obtener TODOS los horarios existentes para detectar conflictos
            $query_conflictos = "SELECT h.dia, h.hora_inicio, h.hora_fin, h.aula, 
                               u.id as id_docente, u.nombre as docente,
                               m.nombre_materia as materia
                        FROM horarios h
                        JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                        JOIN users u ON ds.id_usuario = u.id
                        JOIN materias m ON ds.id_materia = m.id_materia";
            $result_conflictos = $db->query($query_conflictos);
            $todos_horarios = $result_conflictos->fetch_all(MYSQLI_ASSOC);

            // Obtener aulas disponibles desde la base de datos
            $aulas_result = $db->query("SELECT CONCAT(nave, ' - ', aula) as nombre_aula FROM aulas ORDER BY nave, aula");
            $aulas = [];
            while($row = $aulas_result->fetch_assoc()) {
                $aulas[] = $row['nombre_aula'];
            }
            
            // Crear matriz de disponibilidad mejorada
            $disponibilidad = [];
            foreach($dias_disponibles as $dia) {
                foreach($horas_disponibles as $hora) {
                    $disponibilidad[$dia][$hora] = [
                        'docente_disponible' => true,
                        'aulas_disponibles' => $aulas // Todas las aulas disponibles inicialmente
                    ];
                }
            }

            // Marcar horarios ocupados en la matriz de disponibilidad
            foreach($todos_horarios as $horario) {
                $dia = $horario['dia'];
                $hora_inicio = $horario['hora_inicio'];
                $hora_fin = $horario['hora_fin'];
                $aula = $horario['aula'];

                // Solo procesar días que estamos considerando
                if(in_array($dia, $dias_disponibles)) {
                    $hora_actual = $hora_inicio;
                    while($hora_actual < $hora_fin) {
                        if(isset($disponibilidad[$dia][$hora_actual])) {
                            // Si es el mismo docente, marcar como no disponible
                            if($horario['id_docente'] == $id_docente) {
                                $disponibilidad[$dia][$hora_actual]['docente_disponible'] = false;
                            }
                            
                            // Eliminar aula específica de las disponibles
                            $key = array_search($aula, $disponibilidad[$dia][$hora_actual]['aulas_disponibles']);
                            if($key !== false) {
                                unset($disponibilidad[$dia][$hora_actual]['aulas_disponibles'][$key]);
                                // Reindexar array
                                $disponibilidad[$dia][$hora_actual]['aulas_disponibles'] = array_values($disponibilidad[$dia][$hora_actual]['aulas_disponibles']);
                            }
                        }
                        
                        // Avanzar hora actual en bloques de 1 hora
                        $hora_actual = date('H:i', strtotime($hora_actual) + 3600);
                    }
                }
            }

            $asignacionesRealizadas = 0;
            $errores = [];
            $conflictos_detectados = [];
            
            // Función mejorada para encontrar horario disponible
            function encontrarHorarioDisponible($disponibilidad, $dias_disponibles, $horas_disponibles, $duracion, $aulas, $id_docente, $todos_horarios) {
                // Intentar primero en días con más disponibilidad
                $dias_ordenados = [];
                foreach($dias_disponibles as $dia) {
                    $disponibles = 0;
                    foreach($horas_disponibles as $hora) {
                        if($disponibilidad[$dia][$hora]['docente_disponible'] && 
                           !empty($disponibilidad[$dia][$hora]['aulas_disponibles'])) {
                            $disponibles++;
                        }
                    }
                    $dias_ordenados[$dia] = $disponibles;
                }
                
                // Ordenar días de mayor a menor disponibilidad
                arsort($dias_ordenados);
                $dias_ordenados = array_keys($dias_ordenados);
                
                foreach($dias_ordenados as $dia) {
                    foreach($horas_disponibles as $hora) {
                        $hora_fin = sprintf('%02d:00', intval(substr($hora, 0, 2)) + $duracion);
                        
                        // Verificar límites de horario
                        if(intval(substr($hora_fin, 0, 2)) > intval(substr(end($horas_disponibles), 0, 2)) + 1) {
                            continue;
                        }
                        
                        // Verificar disponibilidad para todas las horas del bloque
                        $disponible = true;
                        $aulas_compatibles = $aulas;
                        $hora_actual = $hora;
                        
                        while($hora_actual < $hora_fin) {
                            if(!isset($disponibilidad[$dia][$hora_actual]) || 
                               !$disponibilidad[$dia][$hora_actual]['docente_disponible']) {
                                $disponible = false;
                                break;
                            }
                            
                            // Verificar aulas disponibles en todas las horas del bloque
                            $aulas_compatibles = array_intersect($aulas_compatibles, $disponibilidad[$dia][$hora_actual]['aulas_disponibles']);
                            if(empty($aulas_compatibles)) {
                                $disponible = false;
                                break;
                            }
                            
                            $hora_actual = date('H:i', strtotime($hora_actual) + 3600);
                        }
                        
                        if($disponible && !empty($aulas_compatibles)) {
                            // Verificar conflictos con otros docentes
                            $aula_seleccionada = $aulas_compatibles[array_rand($aulas_compatibles)];
                            $conflicto = false;
                            
                            foreach($todos_horarios as $horario) {
                                if($horario['dia'] == $dia && $horario['aula'] == $aula_seleccionada) {
                                    $hora_inicio_conflicto = $horario['hora_inicio'];
                                    $hora_fin_conflicto = $horario['hora_fin'];
                                    
                                    if(($hora >= $hora_inicio_conflicto && $hora < $hora_fin_conflicto) || 
                                       ($hora_fin > $hora_inicio_conflicto && $hora_fin <= $hora_fin_conflicto) ||
                                       ($hora <= $hora_inicio_conflicto && $hora_fin >= $hora_fin_conflicto)) {
                                        $conflicto = true;
                                        break;
                                    }
                                }
                            }
                            
                            if(!$conflicto) {
                                return [
                                    'dia' => $dia,
                                    'hora_inicio' => $hora,
                                    'hora_fin' => $hora_fin,
                                    'aula' => $aula_seleccionada
                                ];
                            }
                        }
                    }
                }
                
                return false;
            }

            // Asignar materias priorizando las que tienen más horas
            foreach($asignaciones as $asignacion) {
                $horasAsignadas = 0;
                $horasNecesarias = $asignacion['horas_totales'];
                $intentos = 0;
                $maxIntentos = 50; // Aumentado para mejor búsqueda

                while($horasAsignadas < $horasNecesarias && $intentos < $maxIntentos) {
                    $intentos++;
                    
                    // Calcular duración de este bloque (máximo 2 horas por materia)
                    $duracionBloque = min($duracion_clase, $horasNecesarias - $horasAsignadas, 2);
                    
                    // Buscar horario disponible con verificación de conflictos
                    $horario = encontrarHorarioDisponible($disponibilidad, $dias_disponibles, $horas_disponibles, $duracionBloque, $aulas, $id_docente, $todos_horarios);
                    
                    if($horario) {
                        // Verificar conflicto nuevamente (por si acaso)
                        $query = "SELECT COUNT(*) as conflicto
                                  FROM horarios h
                                  JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                                  WHERE h.dia = ? AND h.aula = ? AND 
                                        ((h.hora_inicio < ? AND h.hora_fin > ?) OR 
                                         (h.hora_inicio >= ? AND h.hora_inicio < ?))";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("isssss", $horario['dia'], $horario['aula'], 
                                         $horario['hora_fin'], $horario['hora_inicio'], 
                                         $horario['hora_inicio'], $horario['hora_fin']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $conflicto = $result->fetch_assoc();
                        
                        if($conflicto['conflicto'] > 0) {
                            $conflictos_detectados[] = "Conflicto detectado al asignar {$asignacion['nombre_materia']}";
                            continue;
                        }
                        
                        // Asignar horario
                        $query = "INSERT INTO horarios (id_docente_seccion, dia, hora_inicio, hora_fin, aula)
                                  VALUES (?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("issss", $asignacion['id_docente_seccion'], $horario['dia'], 
                                         $horario['hora_inicio'], $horario['hora_fin'], $horario['aula']);
                        
                        if($stmt->execute()) {
                            $horasAsignadas += $duracionBloque;
                            $asignacionesRealizadas++;
                            
                            // Actualizar matriz de disponibilidad
                            $hora_actual = $horario['hora_inicio'];
                            while($hora_actual < $horario['hora_fin']) {
                                $disponibilidad[$horario['dia']][$hora_actual]['docente_disponible'] = false;
                                
                                // Eliminar aula asignada de las disponibles en este horario
                                $key = array_search($horario['aula'], $disponibilidad[$horario['dia']][$hora_actual]['aulas_disponibles']);
                                if($key !== false) {
                                    unset($disponibilidad[$horario['dia']][$hora_actual]['aulas_disponibles'][$key]);
                                    $disponibilidad[$horario['dia']][$hora_actual]['aulas_disponibles'] = array_values(
                                        $disponibilidad[$horario['dia']][$hora_actual]['aulas_disponibles']
                                    );
                                }
                                
                                $hora_actual = date('H:i', strtotime($hora_actual) + 3600);
                            }
                            
                            // Agregar a todos_horarios para futuras verificaciones
                            $todos_horarios[] = [
                                'dia' => $horario['dia'],
                                'hora_inicio' => $horario['hora_inicio'],
                                'hora_fin' => $horario['hora_fin'],
                                'aula' => $horario['aula'],
                                'id_docente' => $id_docente,
                                'docente' => '', // No necesario para esta verificación
                                'materia' => $asignacion['nombre_materia']
                            ];
                        }
                    } else {
                        $errores[] = "No se encontró horario disponible para {$asignacion['nombre_materia']} (bloque de {$duracionBloque} hora(s))";
                        break;
                    }
                }
                
                if($horasAsignadas < $horasNecesarias && $intentos >= $maxIntentos) {
                    $errores[] = "No se pudo asignar todas las horas de {$asignacion['nombre_materia']}";
                }
            }

            $mensaje = "Se asignaron $asignacionesRealizadas bloques horarios.";
            if(!empty($conflictos_detectados)) {
                $mensaje .= " Conflictos evitados: " . count($conflictos_detectados);
            }
            if(!empty($errores)) {
                $mensaje .= " Errores: " . implode(", ", array_unique($errores));
            }

            echo json_encode([
                'success' => $asignacionesRealizadas > 0,
                'message' => $mensaje,
                'conflictos_evitados' => count($conflictos_detectados)
            ]);
            exit();
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Gestión de Horarios Docentes</h1>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Seleccionar Docente
                </div>
                <div class="card-body">
                    <form id="filtroHorarioDocente">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="docente">Docente</label>
                                <select class="form-control" id="docente" name="docente" required>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    $docentes = $db->query("SELECT DISTINCT u.id, u.nombre 
                                                           FROM docente_seccion ds
                                                           JOIN users u ON ds.id_usuario = u.id
                                                           WHERE ds.estatus = 1
                                                           ORDER BY u.nombre");
                                    while($d = $docentes->fetch_assoc()) {
                                        echo "<option value='{$d['id']}'>{$d['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Cargar Horario</button>
                                <button type="button" id="btnAutoAsignar" class="btn btn-success ml-2" disabled>Asignación Automática</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Horario Semanal - Docente
                </div>
                <div class="card-body">
                    <div id="horarioDocenteContainer">
                        <p class="text-muted">Seleccione un docente para visualizar su horario.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para asignar materia -->
<div class="modal fade" id="asignarMateriaModal" tabindex="-1" role="dialog" aria-labelledby="asignarMateriaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asignarMateriaModalLabel">Asignar Materia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAsignarMateria">
                    <input type="hidden" name="ajax_action" value="guardar_asignacion">
                    <input type="hidden" id="celdaDia" name="dia">
                    <input type="hidden" id="idDocenteActual" name="id_docente">
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="selectDocenteMateria">Materia/Sección</label>
                            <select class="form-control" id="selectDocenteMateria" name="id_docente_seccion" required>
                                <option value="">Cargando materias...</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="aulaAsignada">Aula</label>
                            <select class="form-control" id="aulaAsignada" name="aula" required>
                                <?php
                                $aulas = $db->query("SELECT CONCAT(nave, ' - ', aula) as nombre_aula FROM aulas ORDER BY nave, aula");
                                while($aula = $aulas->fetch_assoc()) {
                                    echo "<option value='{$aula['nombre_aula']}'>{$aula['nombre_aula']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="horaInicio">Hora de Inicio</label>
                            <select class="form-control" id="horaInicio" name="hora_inicio" required>
                                <option value="07:00">07:00</option>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="horaFin">Hora de Fin</label>
                            <select class="form-control" id="horaFin" name="hora_fin" required>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                                <option value="17:00">17:00</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="alert alert-info mt-3">
                            <strong>Duración:</strong> <span id="duracionClase">1 hora</span>
                            <div class="mt-2 text-danger font-weight-bold" id="horasRestantes"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarAsignacion">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para asignación automática -->
<div class="modal fade" id="confirmAutoAsignacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configurar Asignación Automática</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Configura los parámetros para la asignación automática:</p>
                
                <div class="form-group">
                    <label for="duracionClaseAuto">Duración de cada clase (horas):</label>
                    <select class="form-control" id="duracionClaseAuto">
                        <option value="1">1 hora</option>
                        <option value="2" selected>2 horas</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Días de asignación:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="diasOpcion" id="diasSemana" value="semana" checked>
                        <label class="form-check-label" for="diasSemana">
                            Lunes a Viernes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="diasOpcion" id="diasSabado" value="sabado">
                        <label class="form-check-label" for="diasSabado">
                            Sábados
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="preferenciaHoras">Horario preferido:</label>
                    <select class="form-control" id="preferenciaHoras">
                        <option value="7-12">Mañana (7:00 - 12:00)</option>
                        <option value="13-16">Tarde (13:00 - 16:00)</option>
                        <option value="7-16">Todo el día (7:00 - 16:00)</option>
                    </select>
                </div>
                
                <div class="alert alert-warning">
                    <strong>Nota:</strong> Esta acción eliminará todos los horarios actuales del docente y creará una nueva asignación.
                </div>
                
                <div class="alert alert-info">
                    <strong>Información:</strong> El sistema intentará distribuir las clases considerando la disponibilidad de aulas y otros docentes.
                    <br><strong>Máximo 2 horas por materia por semana.</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarAutoAsignacion">Generar Horario</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar asignación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de eliminar esta asignación?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensajes de resultado -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalTitle">Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultModalBody">
                <!-- El contenido se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .horario-cell {
        min-height: 80px;
        border: 1px solid #ddd;
        padding: 5px;
        cursor: pointer;
        position: relative;
        text-align: center;
        vertical-align: middle;
    }
    .horario-cell:hover {
        background-color: #f5f5f5;
    }
    .bg-asignada {
        background-color: #d4edda;
        font-weight: bold;
    }
    .bg-asignada:hover {
        background-color: #c3e6cb !important;
    }
    .bg-conflicto {
        background-color: #f8d7da !important;
    }
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(0,0,0,.1);
        border-radius: 50%;
        border-top-color: #007bff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 10px;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    small {
        font-size: 0.8em;
        color: #666;
    }
    .btn-eliminar {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    #duracionClase {
        font-weight: bold;
        color: #007bff;
    }
    #horasRestantes {
        font-size: 0.9em;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .table-warning {
        background-color: #fff3cd;
    }
</style>

<script>
$(document).ready(function() {
    // Habilitar botón de asignación automática cuando se selecciona un docente
    $('#docente').change(function() {
        $('#btnAutoAsignar').prop('disabled', $(this).val() === '');
    });
    
    // Cargar horario cuando se selecciona un docente
    $('#filtroHorarioDocente').submit(function(e) {
        e.preventDefault();
        var idDocente = $('#docente').val();
        
        if(!idDocente) {
            showResultModal('Error', 'Por favor seleccione un docente', 'danger');
            return;
        }
        
        $('#horarioDocenteContainer').html('<div class="text-center py-4"><span class="loading-spinner"></span><p>Cargando horario...</p></div>');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'get_horario',
                id_docente: idDocente 
            },
            success: function(response) {
                $('#horarioDocenteContainer').html(response);
                
                // Configurar eventos para eliminar asignaciones
                $('.btn-eliminar').click(function() {
                    var idHorario = $(this).data('id');
                    $('#confirmDeleteModal').data('idHorario', idHorario).modal('show');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar horario docente:", status, error);
                $('#horarioDocenteContainer').html('<div class="alert alert-danger">Error al cargar el horario. Intente nuevamente.</div>');
            }
        });
    });
    
    // Configurar modal para asignar materia
    $(document).on('click', '.horario-cell:not(.bg-asignada)', function() {
        var dia = $(this).data('dia');
        var hora = $(this).data('hora');
        var idDocente = $('#docente').val();
        
        $('#celdaDia').val(dia);
        $('#horaInicio').val(hora);
        $('#idDocenteActual').val(idDocente);
        
        // Calcular hora fin por defecto (1 hora después)
        var horaFin = new Date('1970-01-01T' + hora + ':00');
        horaFin.setHours(horaFin.getHours() + 1);
        $('#horaFin').val(('0' + horaFin.getHours()).slice(-2) + ':00');
        
        // Cargar materias del docente
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'get_docente_materias',
                id_docente: idDocente 
            },
            success: function(response) {
                $('#selectDocenteMateria').html(response);
                $('#aulaAsignada').val($('#aulaAsignada option:first').val());
                $('#asignarMateriaModal').modal('show');
                
                // Actualizar duración cuando cambia la materia seleccionada
                $('#selectDocenteMateria').change(function() {
                    var horas = $(this).find('option:selected').data('horas');
                    if(horas) {
                        // Mostrar horas restantes disponibles para esta materia (máximo 2)
                        var horasRestantes = 2 - horas;
                        if(horasRestantes > 0) {
                            $('#horasRestantes').text('Horas disponibles esta semana: ' + horasRestantes + ' de 2');
                        } else {
                            $('#horasRestantes').text('¡Atención! Esta materia ya tiene las 2 horas máximas asignadas esta semana');
                        }
                    }
                });
                
                // Actualizar duración cuando cambian las horas
                $('#horaInicio, #horaFin').change(actualizarDuracion);
                actualizarDuracion();
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar materias:", status, error);
                showResultModal('Error', 'Error al cargar materias disponibles', 'danger');
            }
        });
    });
    
    function actualizarDuracion() {
        var horaInicio = $('#horaInicio').val();
        var horaFin = $('#horaFin').val();
        
        if(horaInicio && horaFin) {
            var inicio = new Date('1970-01-01T' + horaInicio + ':00');
            var fin = new Date('1970-01-01T' + horaFin + ':00');
            var diff = (fin - inicio) / (1000 * 60 * 60); // Diferencia en horas
            
            if(diff <= 0) {
                $('#duracionClase').html('<span class="text-danger">Hora fin debe ser mayor que hora inicio</span>');
                $('#btnGuardarAsignacion').prop('disabled', true);
            } else if(diff > 2) {
                $('#duracionClase').html('<span class="text-danger">No puede exceder 2 horas continuas</span>');
                $('#btnGuardarAsignacion').prop('disabled', true);
            } else {
                $('#duracionClase').text(diff + ' hora(s)');
                $('#btnGuardarAsignacion').prop('disabled', false);
            }
        }
    }
    
    // Guardar asignación
    $('#btnGuardarAsignacion').click(function() {
        var formData = $('#formAsignarMateria').serialize();
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Guardando...');
        
        // Agregar id_docente al formData
        formData += '&id_docente=' + $('#idDocenteActual').val();
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                
                if(response.success) {
                    $('#asignarMateriaModal').modal('hide');
                    showResultModal('Éxito', 'Asignación guardada correctamente', 'success');
                    $('#filtroHorarioDocente').submit(); // Recargar horario
                } else {
                    var mensaje = 'Error: ' + (response.message || 'No se pudo guardar');
                    if(response.conflictos) {
                        mensaje += '\nConflictos con: ' + response.conflictos.join(', ');
                    }
                    showResultModal('Error', mensaje, 'danger');
                }
            },
            dataType: 'json',
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).html(originalText);
                console.error("Error al guardar asignación:", status, error);
                showResultModal('Error', 'Error de conexión al guardar', 'danger');
            }
        });
    });
    
    // Asignación automática
    $('#btnAutoAsignar').click(function() {
        $('#confirmAutoAsignacion').modal('show');
    });
    
    $('#confirmarAutoAsignacion').click(function() {
        var idDocente = $('#docente').val();
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Generando...');
        
        // Obtener preferencias
        var duracion = $('#duracionClaseAuto').val();
        var diasOpcion = $('input[name="diasOpcion"]:checked').val();
        var horasRange = $('#preferenciaHoras').val().split('-');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'asignacion_automatica',
                id_docente: idDocente,
                duracion_clase: duracion,
                dias_opcion: diasOpcion,
                horas_min: horasRange[0],
                horas_max: horasRange[1]
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                $('#confirmAutoAsignacion').modal('hide');
                
                if(response.success) {
                    var mensaje = response.message + (response.conflictos_evitados ? '\nConflictos evitados: ' + response.conflictos_evitados : '');
                    showResultModal('Asignación Automática', mensaje, 'success');
                    $('#filtroHorarioDocente').submit(); // Recargar horario
                } else {
                    showResultModal('Error', 'Error: ' + response.message, 'danger');
                }
            },
            dataType: 'json',
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).html(originalText);
                console.error("Error en asignación automática:", status, error);
                showResultModal('Error', 'Error en asignación automática', 'danger');
            }
        });
    });
    
    // Confirmar eliminación de asignación
    $('#confirmDeleteButton').click(function() {
        var idHorario = $('#confirmDeleteModal').data('idHorario');
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Eliminando...');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'eliminar_asignacion',
                id_horario: idHorario
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                $('#confirmDeleteModal').modal('hide');
                
                if(response.success) {
                    showResultModal('Éxito', 'Asignación eliminada correctamente', 'success');
                    $('#filtroHorarioDocente').submit();
                } else {
                    showResultModal('Error', 'Error al eliminar: ' + (response.message || ''), 'danger');
                }
            },
            dataType: 'json'
        });
    });
    
    // Función para mostrar modales de resultado
    function showResultModal(title, message, type) {
        $('#resultModalTitle').text(title);
        $('#resultModalBody').html('<div class="alert alert-' + type + '">' + message.replace(/\n/g, '<br>') + '</div>');
        $('#resultModal').modal('show');
    }
});
</script>

<?php include("includes/footer.php"); ?>