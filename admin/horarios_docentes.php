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
        case 'get_secciones':
            $id_carrera = $_POST['id_carrera'];
            $id_periodo = $_POST['id_periodo'];
            
            $query = "SELECT id_seccion, codigo_seccion 
                      FROM secciones 
                      WHERE id_carrera = ? AND id_periodo = ? AND estatus = 1
                      ORDER BY codigo_seccion";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $id_carrera, $id_periodo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $options = '<option value="">Seleccionar sección...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id_seccion']}'>{$row['codigo_seccion']}</option>";
            }
            
            echo $options;
            exit();
            
        case 'get_docentes_materias':
            $id_seccion = $_POST['id_seccion'];
            
            $query = "SELECT ds.id_docente_seccion, u.nombre as docente, m.nombre_materia as materia
                       FROM docente_seccion ds
                       JOIN users u ON ds.id_usuario = u.id
                       JOIN materias m ON ds.id_materia = m.id_materia
                       WHERE ds.id_seccion = ? AND ds.estatus = 1
                       ORDER BY u.nombre, m.nombre_materia";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $options = '<option value="">Seleccionar docente/materia...</option>';
            while($row = $result->fetch_assoc()) {
                $options .= "<option value='{$row['id_docente_seccion']}'>{$row['docente']} - {$row['materia']}</option>";
            }
            
            echo $options;
            exit();
            
        case 'get_horario':
            $id_seccion = $_POST['id_seccion'];
            
            // Obtener horario asignado desde la base de datos
            $horario = [];
            $query = "SELECT h.id_horario, h.dia, TIME_FORMAT(h.hora_inicio, '%H:%i') as hora_inicio, 
                             TIME_FORMAT(h.hora_fin, '%H:%i') as hora_fin, h.aula,
                             ds.id_docente_seccion, ds.id_materia,
                             u.nombre as docente, m.nombre_materia as materia
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      JOIN users u ON ds.id_usuario = u.id
                      JOIN materias m ON ds.id_materia = m.id_materia
                      WHERE ds.id_seccion = ?
                      ORDER BY h.dia, h.hora_inicio";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Organizar por día y hora
            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
            
            // Obtener docentes/materias disponibles
            $query_dm = "SELECT ds.id_docente_seccion, u.nombre as docente, m.nombre_materia as materia
                         FROM docente_seccion ds
                         JOIN users u ON ds.id_usuario = u.id
                         JOIN materias m ON ds.id_materia = m.id_materia
                         WHERE ds.id_seccion = ? AND ds.estatus = 1
                         ORDER BY u.nombre, m.nombre_materia";
            $stmt_dm = $db->prepare($query_dm);
            $stmt_dm->bind_param("i", $id_seccion);
            $stmt_dm->execute();
            $result_dm = $stmt_dm->get_result();
            
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
                            $title = $asignacion['materia'] . ' - ' . $asignacion['docente'];
                            $celdaContent = $asignacion['materia'];
                            
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
            
            // Añadir sección de docentes/materias disponibles
            $html .= '<div id="listaDocentesMaterias" class="mt-4 p-3 border rounded">
                        <h5>Docentes/Materias Disponibles</h5>
                        <div class="d-flex flex-wrap">';
            
            while($dm = $result_dm->fetch_assoc()) {
                $html .= '<div class="materia-disponible mr-2 mb-2 p-2" data-id="'.$dm['id_docente_seccion'].'">
                            '.htmlspecialchars($dm['docente']).' - '.htmlspecialchars($dm['materia']).'
                         </div>';
            }
            
            $html .= '</div></div>';
            
            echo $html;
            exit();
            
        case 'verificar_conflicto':
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];
            $id_docente_seccion = $_POST['id_docente_seccion'];
            
            $query = "SELECT id_usuario FROM docente_seccion WHERE id_docente_seccion = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_docente_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            $docente = $result->fetch_assoc();
            
            if(!$docente) {
                echo json_encode(['conflicto' => false]);
                exit();
            }
            
            $query = "SELECT COUNT(*) as conflicto
                      FROM horarios h
                      JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                      WHERE ds.id_usuario = ? AND h.dia = ? AND h.hora_inicio = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iis", $docente['id_usuario'], $dia, $hora);
            $stmt->execute();
            $result = $stmt->get_result();
            $conflicto = $result->fetch_assoc();
            
            echo json_encode(['conflicto' => $conflicto['conflicto'] > 0]);
            exit();
            
        case 'guardar_asignacion':
            $dia = $_POST['dia'];
            $hora = $_POST['hora'];
            $id_docente_seccion = $_POST['id_docente_seccion'];
            $id_seccion = $_POST['id_seccion'];
            $aula = $_POST['aula'];
            
            // Validar formato de hora
            if (!preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $hora)) {
                echo json_encode(['success' => false, 'message' => 'Formato de hora inválido']);
                exit();
            }
            
            $hora_fin = date('H:i', strtotime($hora . ' +1 hour'));
            
            // Verificar si ya existe una asignación
            $query = "SELECT id_horario FROM horarios 
                      WHERE id_docente_seccion IN (SELECT id_docente_seccion FROM docente_seccion WHERE id_seccion = ?)
                      AND dia = ? AND hora_inicio = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("iis", $id_seccion, $dia, $hora);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                // Actualizar asignación existente
                $row = $result->fetch_assoc();
                $query = "UPDATE horarios 
                          SET id_docente_seccion = ?, hora_fin = ?, aula = ?
                          WHERE id_horario = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param("issi", $id_docente_seccion, $hora_fin, $aula, $row['id_horario']);
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
            
        case 'asignacion_automatica':
            $id_seccion = $_POST['id_seccion'];
            
            // Obtener todas las asignaciones docente-materia
            $query = "SELECT ds.id_docente_seccion, ds.id_usuario, m.nombre_materia,
                             m.horas_teoricas + m.horas_practicas as horas_totales
                      FROM docente_seccion ds
                      JOIN materias m ON ds.id_materia = m.id_materia
                      WHERE ds.id_seccion = ? AND ds.estatus = 1
                      ORDER BY RAND()";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $id_seccion);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $asignaciones = [];
            while($row = $result->fetch_assoc()) {
                $asignaciones[] = $row;
            }
            
            // Eliminar horarios existentes
            $db->query("DELETE FROM horarios WHERE id_docente_seccion IN (SELECT id_docente_seccion FROM docente_seccion WHERE id_seccion = $id_seccion)");
            
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
                    $stmt->bind_param("iis", $asignacion['id_usuario'], $dia, $hora);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $disponibilidadDocente = $result->fetch_assoc();
                    
                    // Verificar disponibilidad en la sección
                    $query = "SELECT COUNT(*) as disponible
                              FROM horarios h
                              JOIN docente_seccion ds ON h.id_docente_seccion = ds.id_docente_seccion
                              WHERE ds.id_seccion = ? AND h.dia = ? AND h.hora_inicio = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("iis", $id_seccion, $dia, $hora);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $disponibilidadSeccion = $result->fetch_assoc();
                    
                    if($disponibilidadDocente['disponible'] == 0 && $disponibilidadSeccion['disponible'] == 0) {
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
                    Seleccionar Período y Sección
                </div>
                <div class="card-body">
                    <form id="filtroHorario">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="periodo">Período Académico</label>
                                <select class="form-control" id="periodo" name="periodo" required>
                                    <?php
                                    $periodos = $db->query("SELECT DISTINCT id_periodo FROM secciones WHERE estatus = 1 ORDER BY id_periodo DESC");
                                    while($p = $periodos->fetch_assoc()) {
                                        echo "<option value='{$p['id_periodo']}'>{$p['id_periodo']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="carrera">Carrera</label>
                                <select class="form-control" id="carrera" name="carrera" required>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    $carreras = $db->query("SELECT id_carrera, nombre_carrera FROM carreras WHERE activa = 1");
                                    while($c = $carreras->fetch_assoc()) {
                                        echo "<option value='{$c['id_carrera']}'>{$c['nombre_carrera']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="seccion">Sección</label>
                                <select class="form-control" id="seccion" name="seccion" required disabled>
                                    <option value="">Primero seleccione carrera</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cargar Horario</button>
                        <button type="button" id="btnAutoAsignar" class="btn btn-success ml-2" disabled>Asignación Automática</button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Horario Semanal
                </div>
                <div class="card-body">
                    <div id="horarioContainer">
                        <p class="text-muted">Seleccione un período, carrera y sección para visualizar el horario.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <input type="hidden" id="idSeccionActual" name="id_seccion">
                    
                    <div class="form-group">
                        <label for="selectDocenteMateria">Docente/Materia</label>
                        <select class="form-control" id="selectDocenteMateria" name="id_docente_seccion" required>
                            <option value="">Seleccionar...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aulaAsignada">Aula</label>
                        <input type="text" class="form-control" id="aulaAsignada" name="aula">
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

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<style>
    .horario-cell {
        min-height: 60px;
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
    .materia-disponible {
        background-color: #cce5ff;
        border-radius: 4px;
        padding: 5px;
        margin: 5px;
        cursor: move;
    }
    #listaDocentesMaterias {
        min-height: 100px;
        border: 1px dashed #ccc;
        padding: 10px;
        margin-top: 20px;
    }
    #listaDocentesMaterias .materia-disponible {
        display: inline-block;
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
</style>

<script>
$(document).ready(function() {
    // Cargar secciones cuando se selecciona una carrera
    $('#carrera').change(function() {
        var idCarrera = $(this).val();
        var idPeriodo = $('#periodo').val();
        
        if(idCarrera && idPeriodo) {
            $('#seccion').prop('disabled', true).html('<option value="">Cargando secciones...</option>');
            
            $.ajax({
                url: '',
                type: 'POST',
                data: { 
                    ajax_action: 'get_secciones',
                    id_carrera: idCarrera,
                    id_periodo: idPeriodo
                },
                success: function(response) {
                    $('#seccion').html(response).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar secciones:", status, error);
                    $('#seccion').html('<option value="">Error al cargar secciones</option>');
                }
            });
        }
    });
    
    // Cargar horario cuando se selecciona una sección
    $('#filtroHorario').submit(function(e) {
        e.preventDefault();
        cargarHorario();
    });
    
    // Función para cargar el horario
    function cargarHorario() {
        var idSeccion = $('#seccion').val();
        
        if(!idSeccion) {
            alert('Por favor seleccione una sección');
            return;
        }
        
        $('#horarioContainer').html('<div class="text-center py-4"><span class="loading-spinner"></span><p>Cargando horario...</p></div>');
        
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'get_horario',
                id_seccion: idSeccion 
            },
            success: function(response) {
                $('#horarioContainer').html(response);
                $('#btnAutoAsignar').prop('disabled', false);
                
                // Configurar eventos para las celdas del horario
                configurarEventosHorario(idSeccion);
                
                // Configurar drag and drop
                configurarDragAndDrop(idSeccion);
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar horario:", status, error);
                $('#horarioContainer').html('<div class="alert alert-danger">Error al cargar el horario. Intente nuevamente.</div>');
            }
        });
    }
    
    // Configurar eventos para las celdas del horario
    function configurarEventosHorario(idSeccion) {
        // Evento click en celdas del horario
        $('.horario-cell').click(function() {
            var dia = $(this).data('dia');
            var hora = $(this).data('hora');
            
            $('#celdaDia').val(dia);
            $('#celdaHora').val(hora);
            $('#idSeccionActual').val(idSeccion);
            
            // Cargar docentes/materias disponibles
            $.ajax({
                url: '',
                type: 'POST',
                data: { 
                    ajax_action: 'get_docentes_materias',
                    id_seccion: idSeccion 
                },
                success: function(response) {
                    $('#selectDocenteMateria').html(response);
                    $('#aulaAsignada').val('');
                    $('#asignarMateriaModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar docentes/materias:", status, error);
                    alert('Error al cargar docentes/materias disponibles');
                }
            });
        });
    }
    
    // Configurar drag and drop
    function configurarDragAndDrop(idSeccion) {
        // Hacer elementos arrastrables
        $('.materia-disponible').draggable({
            revert: "invalid",
            cursor: "move",
            zIndex: 1000,
            helper: "clone"
        });
        
        // Hacer celdas receptivas
        $('.horario-cell').droppable({
            accept: '.materia-disponible',
            hoverClass: 'hovered-cell',
            drop: function(event, ui) {
                var dia = $(this).data('dia');
                var hora = $(this).data('hora');
                var idDocenteSeccion = ui.draggable.data('id');
                
                verificarConflicto(dia, hora, idDocenteSeccion, function(conflicto) {
                    if(conflicto) {
                        alert('¡Conflicto de horario! El docente ya tiene una clase asignada en ese horario.');
                        return;
                    }
                    
                    $('#celdaDia').val(dia);
                    $('#celdaHora').val(hora);
                    $('#idSeccionActual').val(idSeccion);
                    $('#selectDocenteMateria').val(idDocenteSeccion);
                    $('#aulaAsignada').val('');
                    
                    $('#asignarMateriaModal').modal('show');
                });
            }
        });
    }
    
    // Función para verificar conflictos
    function verificarConflicto(dia, hora, idDocenteSeccion, callback) {
        $.ajax({
            url: '',
            type: 'POST',
            data: { 
                ajax_action: 'verificar_conflicto',
                dia: dia,
                hora: hora,
                id_docente_seccion: idDocenteSeccion
            },
            success: function(response) {
                callback(response.conflicto);
            },
            dataType: 'json',
            error: function(xhr, status, error) {
                console.error("Error al verificar conflicto:", status, error);
                callback(false);
            }
        });
    }
    
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
                    cargarHorario(); // Recargar todo el horario
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
        if(confirm('¿Está seguro de realizar una asignación automática? Esto reemplazará asignaciones existentes.')) {
            var idSeccion = $('#seccion').val();
            var $btn = $(this);
            var originalText = $btn.html();
            
            $btn.prop('disabled', true).html('<span class="loading-spinner"></span> Asignando...');
            
            $.ajax({
                url: '',
                type: 'POST',
                data: { 
                    ajax_action: 'asignacion_automatica',
                    id_seccion: idSeccion 
                },
                success: function(response) {
                    $btn.prop('disabled', false).html(originalText);
                    alert(response.message);
                    if(response.success) {
                        cargarHorario(); // Recargar todo el horario
                    }
                },
                dataType: 'json',
                error: function(xhr, status, error) {
                    $btn.prop('disabled', false).html(originalText);
                    console.error("Error en asignación automática:", status, error);
                    alert('Error en asignación automática');
                }
            });
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>