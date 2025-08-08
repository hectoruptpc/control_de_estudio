<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Horarios Docentes";
include('../funciones/functions.php');

// Conexión a la base de datos
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
            
            // Obtener horario asignado desde la base de datos
            $query = "SELECT h.id_horario, h.dia, TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio, 
                             TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin, h.aula,
                             ds.id_docente_seccion, ds.id_materia,
                             u.nombre as docente, m.nombre_materia as materia,
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo
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
            
            // Organizar por día y hora
            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
            
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
            
            // Matriz para controlar celdas ocupadas
            $celdas_ocupadas = array_fill(0, count($dias_semana), array_fill(0, count($horas_disponibles), false));
            
            foreach($horas_disponibles as $hora_index => $hora) {
                $html .= "<tr><td>$hora</td>";
                
                foreach($dias_semana as $dia_index => $dia_nombre) {
                    $celdaContent = '';
                    $clasesCelda = 'horario-cell';
                    $title = '';
                    $rowspan = 1;
                    
                    // Si la celda ya está ocupada, saltar
                    if(isset($celdas_ocupadas[$dia_index][$hora_index]) && $celdas_ocupadas[$dia_index][$hora_index]) {
                        $html .= '';
                        continue;
                    }
                    
                    // Buscar asignación para este día y hora
                    foreach($asignaciones as $asignacion) {
                        if($asignacion['dia'] == $dia_index && $asignacion['hora_inicio'] == $hora) {
                            $title = $asignacion['materia'] . ' - ' . $asignacion['nombre_carrera'] . 
                                     ' (' . $asignacion['codigo_seccion'] . ') - Período: ' . $asignacion['id_periodo'] . 
                                     ' - Aula: ' . $asignacion['aula'];
                            $celdaContent = '<strong>' . $asignacion['materia'] . '</strong><br>' . 
                                            '<small>' . $asignacion['nombre_carrera'] . ' (' . $asignacion['codigo_seccion'] . ')</small>';
                            
                            // Calcular cuántas celdas debe abarcar
                            $hora_fin = $asignacion['hora_fin'];
                            $hora_fin_index = array_search($hora_fin, $horas_disponibles);
                            
                            if($hora_fin_index !== false) {
                                $rowspan = $hora_fin_index - $hora_index + 1;
                                
                                // Marcar celdas como ocupadas
                                for($i = $hora_index; $i < $hora_fin_index; $i++) {
                                    $celdas_ocupadas[$dia_index][$i] = true;
                                }
                            }
                            
                            $clasesCelda .= ' bg-asignada';
                            break;
                        }
                    }
                    
                    $html .= '<td class="' . $clasesCelda . '" data-dia="' . $dia_index . '" data-hora="' . $hora . '" title="' . htmlspecialchars($title) . '"';
                    if($rowspan > 1) {
                        $html .= ' rowspan="' . $rowspan . '"';
                    }
                    $html .= '>' . $celdaContent . '</td>';
                }
                
                $html .= "</tr>";
            }
            
            $html .= '</tbody></table></div>';
            
            // Agregar resumen de asignaciones
            $html .= '<div class="card mt-4">
                        <div class="card-header">
                            <i class="fas fa-info-circle mr-1"></i>
                            Resumen de Asignaciones
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Materia</th>
                                        <th>Carrera</th>
                                        <th>Sección</th>
                                        <th>Período</th>
                                        <th>Día</th>
                                        <th>Horario</th>
                                        <th>Aula</th>
                                    </tr>
                                </thead>
                                <tbody>';
            
            foreach($asignaciones as $asignacion) {
                $html .= '<tr>
                            <td>' . $asignacion['materia'] . '</td>
                            <td>' . $asignacion['nombre_carrera'] . '</td>
                            <td>' . $asignacion['codigo_seccion'] . '</td>
                            <td>' . $asignacion['id_periodo'] . '</td>
                            <td>' . $dias_semana[$asignacion['dia']] . '</td>
                            <td>' . $asignacion['hora_inicio'] . ' - ' . $asignacion['hora_fin'] . '</td>
                            <td>' . $asignacion['aula'] . '</td>
                          </tr>';
            }
            
            $html .= '</tbody></table></div></div>';
            
            echo $html;
            exit();
            
        case 'verificar_conflicto':
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];
            $id_docente = $_POST['id_docente'];
            
            $query = "SELECT COUNT(*) as conflicto
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      WHERE ds.id_usuario = ? AND h.dia = ? AND h.hora_inicio = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iis", $id_docente, $dia, $hora);
            $stmt->execute();
            $result = $stmt->get_result();
            $conflicto = $result->fetch_assoc();
            
            echo json_encode(['conflicto' => $conflicto['conflicto'] > 0]);
            exit();
            
        case 'guardar_asignacion':
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];
            $id_docente_seccion = $_POST['id_docente_seccion'];
            $aula = $_POST['aula'];
            
            // Validar formato de hora
            if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora)) {
                echo json_encode(['success' => false, 'message' => 'Formato de hora inválido']);
                exit();
            }
            
            $hora_fin = date('H:i', strtotime($hora . ' +1 hour'));
            
            // Obtener id_docente para verificar conflictos
            $query = "SELECT id_usuario FROM docente_seccion WHERE id_docente_seccion = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            $docente = $result->fetch_assoc();
            
            if(!$docente) {
                echo json_encode(['success' => false, 'message' => 'Docente no encontrado']);
                exit();
            }
            
            // Verificar conflicto
            $query = "SELECT COUNT(*) as conflicto
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      WHERE ds.id_usuario = ? AND h.dia = ? AND h.hora_inicio = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iis", $docente['id_usuario'], $dia, $hora);
            $stmt->execute();
            $result = $stmt->get_result();
            $conflicto = $result->fetch_assoc();
            
            if($conflicto['conflicto'] > 0) {
                echo json_encode(['success' => false, 'message' => 'El docente ya tiene una clase asignada en este horario']);
                exit();
            }
            
            // Verificar si ya existe una asignación
            $query = "SELECT id_horario FROM horarios 
                      WHERE id_docente_seccion = ? AND dia = ? AND hora_inicio = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iis", $id_docente_seccion, $dia, $hora);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                // Actualizar asignación existente
                $row = $result->fetch_assoc();
                $query = "UPDATE horarios 
                          SET hora_fin = ?, aula = ?
                          WHERE id_horario = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("ssi", $hora_fin, $aula, $row['id_horario']);
            } else {
                // Crear nueva asignación
                $query = "INSERT INTO horarios (id_docente_seccion, dia, hora_inicio, hora_fin, aula)
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                $stmt->bind_param("issss", $id_docente_seccion, $dia, $hora, $hora_fin, $aula);
            }
            
            if($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $db->error]);
            }
            exit();
            
        case 'get_docente_materias':
            $id_docente = $_POST['id_docente'];
            
            $query = "SELECT ds.id_docente_seccion, m.nombre_materia, 
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo
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
                $options .= "<option value='{$row['id_docente_seccion']}'>
                                {$row['nombre_materia']} - {$row['nombre_carrera']} ({$row['codigo_seccion']}) - {$row['id_periodo']}
                            </option>";
            }
            
            echo $options;
            exit();
            
        case 'asignacion_automatica':
            $id_docente = $_POST['id_docente'];
            
            // Obtener todas las materias asignadas al docente
            $query = "SELECT ds.id_docente_seccion, m.nombre_materia, 
                             m.horas_teoricas + m.horas_practicas as horas_totales,
                             c.nombre_carrera, s.codigo_seccion, s.id_periodo
                      FROM docente_seccion ds
                      JOIN materias m ON ds.id_materia = m.id_materia
                      JOIN secciones s ON ds.id_seccion = s.id_seccion
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      WHERE ds.id_usuario = ? AND ds.estatus = 1
                      ORDER BY RAND()";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
            
            // Eliminar horarios existentes del docente
            $db->query("DELETE FROM horarios WHERE id_docente_seccion IN (SELECT id_docente_seccion FROM docente_seccion WHERE id_usuario = $id_docente)");
            
            // Configuración de horarios
            $dias = range(0, 4); // Lunes a Viernes
            $horas = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00'];
            $asignacionesRealizadas = 0;
            
            foreach($asignaciones as $asignacion) {
                $horasAsignadas = 0;
                $horasNecesarias = ceil($asignacion['horas_totales'] / 2);
                $intentos = 0;
                $maxIntentos = 100;
                
                while($horasAsignadas < $horasNecesarias && $intentos < $maxIntentos) {
                    $intentos++;
                    $dia = $dias[array_rand($dias)];
                    $hora = $horas[array_rand($horas)];
                    $hora_fin = date('H:i', strtotime($hora . ' +2 hours'));
                    
                    // Verificar disponibilidad del docente
                    $query = "SELECT COUNT(*) as disponible
                              FROM horarios h
                              JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                              WHERE ds.id_usuario = ? AND h.dia = ? AND h.hora_inicio = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("iis", $id_docente, $dia, $hora);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $disponibilidad = $result->fetch_assoc();
                    
                    if($disponibilidad['disponible'] == 0) {
                        // Asignar horario
                        $query = "INSERT INTO horarios (id_docente_seccion, dia, hora_inicio, hora_fin, aula)
                                  VALUES (?, ?, ?, ?, 'Aula por asignar')";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param("isss", $asignacion['id_docente_seccion'], $dia, $hora, $hora_fin);
                        
                        if($stmt->execute()) {
                            $horasAsignadas++;
                            $asignacionesRealizadas++;
                        }
                    }
                }
            }
            
            echo json_encode([
                'success' => $asignacionesRealizadas > 0,
                'message' => $asignacionesRealizadas > 0 
                    ? "Se asignaron $asignacionesRealizadas bloques horarios." 
                    : "No se pudo asignar automáticamente. Intente con menos materias."
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
    <div class="modal-dialog" role="document">
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
                    <input type="hidden" id="celdaHora" name="hora">
                    
                    <div class="form-group">
                        <label for="selectDocenteMateria">Materia/Sección</label>
                        <select class="form-control" id="selectDocenteMateria" name="id_docente_seccion" required>
                            <option value="">Cargando materias...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aulaAsignada">Aula</label>
                        <input type="text" class="form-control" id="aulaAsignada" name="aula" required>
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
                <h5 class="modal-title">Confirmar Asignación Automática</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de realizar una asignación automática? Esto eliminará todos los horarios actuales del docente y creará una nueva asignación.</p>
                <div class="form-group">
                    <label for="preferenciaDias">Preferencia de días:</label>
                    <select class="form-control" id="preferenciaDias">
                        <option value="0-4">Lunes a Viernes</option>
                        <option value="0-2">Lunes a Miércoles</option>
                        <option value="3-4">Jueves y Viernes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="preferenciaHoras">Preferencia de horario:</label>
                    <select class="form-control" id="preferenciaHoras">
                        <option value="7-12">Mañana (7:00 - 12:00)</option>
                        <option value="13-16">Tarde (13:00 - 16:00)</option>
                        <option value="7-16">Todo el día</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmarAutoAsignacion">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
    .hovered-cell {
        background-color: #e9f7ef !important;
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
            alert('Por favor seleccione un docente');
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
                
                // Configurar eventos para las celdas del horario
                $('.horario-cell').click(function() {
                    var dia = $(this).data('dia');
                    var hora = $(this).data('hora');
                    
                    $('#celdaDia').val(dia);
                    $('#celdaHora').val(hora);
                    
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
                            $('#aulaAsignada').val('');
                            $('#asignarMateriaModal').modal('show');
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al cargar materias:", status, error);
                            alert('Error al cargar materias disponibles');
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar horario docente:", status, error);
                $('#horarioDocenteContainer').html('<div class="alert alert-danger">Error al cargar el horario. Intente nuevamente.</div>');
            }
        });
    });
    
    // Guardar asignación
    $('#btnGuardarAsignacion').click(function() {
        var formData = $('#formAsignarMateria').serialize();
        var $btn = $(this);
        var originalText = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Guardando...');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: formData,
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                
                if(response.success) {
                    $('#asignarMateriaModal').modal('hide');
                    $('#filtroHorarioDocente').submit(); // Recargar horario
                } else {
                    alert('Error: ' + (response.message || 'No se pudo guardar'));
                }
            },
            dataType: 'json',
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).html(originalText);
                console.error("Error al guardar asignación:", status, error);
                alert('Error de conexión al guardar');
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
        
        $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Procesando...');
        
        // Obtener preferencias
        var diasRange = $('#preferenciaDias').val().split('-');
        var horasRange = $('#preferenciaHoras').val().split('-');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'asignacion_automatica',
                id_docente: idDocente,
                dias_min: diasRange[0],
                dias_max: diasRange[1],
                horas_min: horasRange[0],
                horas_max: horasRange[1]
            },
            success: function(response) {
                $btn.prop('disabled', false).html(originalText);
                $('#confirmAutoAsignacion').modal('hide');
                
                if(response.success) {
                    alert(response.message);
                    $('#filtroHorarioDocente').submit(); // Recargar horario
                } else {
                    alert('Error: ' + response.message);
                }
            },
            dataType: 'json',
            error: function(xhr, status, error) {
                $btn.prop('disabled', false).html(originalText);
                console.error("Error en asignación automática:", status, error);
                alert('Error en asignación automática');
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>