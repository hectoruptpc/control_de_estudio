<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['docente_id']) || !isset($_POST['materia_id']) || !isset($_POST['periodo_id']) || !isset($_POST['seccion'])) {
    die('Parámetros incompletos');
}

$docente_id = (int)$_POST['docente_id'];
$materia_id = (int)$_POST['materia_id'];
$periodo_id = (int)$_POST['periodo_id'];
$seccion = $_POST['seccion'];

// Obtener información del grupo (FUNCIÓN ESPECÍFICA - se queda aquí)
function obtenerInfoGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT ud.nombre as nombre_docente, m.nombre_materia, m.cod_materia,
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.id_trayecto, t.numero_trayecto
              FROM notas_pendientes np
              INNER JOIN users ud ON np.id_docente = ud.id
              INNER JOIN materias m ON np.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON np.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON np.id_docente = ds.id_usuario 
                                           AND np.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener estudiantes del grupo (FUNCIÓN ESPECÍFICA - MODIFICADA para excluir rechazados)
function obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT np.*, u.nombre as nombre_estudiante, u.idusuario as cedula
              FROM notas_pendientes np
              INNER JOIN users u ON np.id_usuario = u.id
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'  -- SOLO notas pendientes, no rechazadas
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Obtener información de soporte del grupo
function obtenerSoporteGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT DISTINCT soporte, tipo_archivo, fecha_subida
              FROM notas_pendientes 
              WHERE id_docente = ? 
              AND id_materia = ? 
              AND id_periodo = ?
              AND soporte IS NOT NULL
              AND estado = 'pendiente'
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Calcular promedio según el id_trayecto de la sección (FUNCIÓN ESPECÍFICA - se queda aquí)
function calcularPromedioPorTrayecto($nota, $id_trayecto) {
    $suma = 0;
    $count = 0;
    
    // Determinar qué trayectos promediar según el id_trayecto de la sección
    switch ($id_trayecto) {
        case 1: // Trayecto Inicial - Solo trayecto_0
            if ($nota['trayecto_0'] !== null) {
                $suma = $nota['trayecto_0'];
                $count = 1;
            }
            break;
            
        case 2: // Trayecto 1 - Solo trayecto_1
            if ($nota['trayecto_1'] !== null) {
                $suma = $nota['trayecto_1'];
                $count = 1;
            }
            break;
            
        case 3: // Trayecto 2 - Solo trayecto_2
            if ($nota['trayecto_2'] !== null) {
                $suma = $nota['trayecto_2'];
                $count = 1;
            }
            break;
            
        case 4: // Trayecto 3 - Solo trayecto_3
            if ($nota['trayecto_3'] !== null) {
                $suma = $nota['trayecto_3'];
                $count = 1;
            }
            break;
            
        case 5: // Trayecto 4 - Solo trayecto_4
            if ($nota['trayecto_4'] !== null) {
                $suma = $nota['trayecto_4'];
                $count = 1;
            }
            break;
            
        default:
            // Por defecto, calcular todos los trayectos (no debería pasar)
            for ($i = 0; $i <= 4; $i++) {
                if ($nota['trayecto_' . $i] !== null) {
                    $suma += $nota['trayecto_' . $i];
                    $count++;
                }
            }
    }
    
    return $count > 0 ? round($suma / $count, 1) : 0;
}

// Obtener estadísticas del grupo según el id_trayecto (FUNCIÓN ESPECÍFICA - MODIFICADA para excluir rechazados)
function obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id, $id_trayecto) {
    global $db;
    
    $query = "SELECT np.trayecto_0, np.trayecto_1, np.trayecto_2, np.trayecto_3, np.trayecto_4
              FROM notas_pendientes np
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'";  // SOLO notas pendientes, no rechazadas
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_estudiantes = 0;
    $suma_total = 0;
    $aprobados = 0;
    $reprobados = 0;
    
    while ($nota = $result->fetch_assoc()) {
        $total_estudiantes++;
        
        // Calcular promedio según el id_trayecto de la sección
        $promedio_estudiante = calcularPromedioPorTrayecto($nota, $id_trayecto);
        $suma_total += $promedio_estudiante;
        
        // Aprobados desde 12 puntos
        if ($promedio_estudiante >= 12) {
            $aprobados++;
        } else {
            $reprobados++;
        }
    }
    
    $promedio_general = $total_estudiantes > 0 ? round($suma_total / $total_estudiantes, 1) : 0;
    
    return [
        'total_estudiantes' => $total_estudiantes,
        'promedio_general' => $promedio_general,
        'aprobados' => $aprobados,
        'reprobados' => $reprobados,
        'id_trayecto' => $id_trayecto
    ];
}

$info_grupo = obtenerInfoGrupo($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id);
$soporte_info = obtenerSoporteGrupo($docente_id, $materia_id, $periodo_id);

if (!$info_grupo) {
    die('Información no encontrada');
}

$estadisticas = obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id, $info_grupo['id_trayecto']);

// Determinar qué trayecto se está considerando (REUTILIZANDO función existente)
$trayecto_considerado = '';
switch ($info_grupo['id_trayecto']) {
    case 1: $trayecto_considerado = 'Trayecto 0'; break;
    case 2: $trayecto_considerado = 'Trayecto 1'; break;
    case 3: $trayecto_considerado = 'Trayecto 2'; break;
    case 4: $trayecto_considerado = 'Trayecto 3'; break;
    case 5: $trayecto_considerado = 'Trayecto 4'; break;
    default: $trayecto_considerado = 'Todos los trayectos';
}

switch ($seccion) {
    case 'lista-estudiantes':
        ?>
        <h4>Lista de Estudiantes</h4>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            Trayecto considerado: <strong><?= $trayecto_considerado ?></strong><br>
            Aprobación: ≥12pts
        </div>
        
        <!-- Botones Aprobar/Rechazar Todo - Se muestran inicialmente -->
        <div id="botonesGrupo" class="mb-3">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Acciones Grupales:</strong> Estas acciones afectarán a TODOS los estudiantes del grupo.
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="accionGrupo('aprobar')">
                <i class="fas fa-check-circle"></i> Aprobar Todo
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="accionGrupo('rechazar')">
                <i class="fas fa-times-circle"></i> Rechazar Todo
            </button>
        </div>
        
        <!-- Botones Aprobar/Rechazar Seleccionados - Ocultos inicialmente -->
        <div id="botonesSeleccion" class="mb-3" style="display: none;">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Acciones sobre selección:</strong> Estas acciones afectarán solo a los estudiantes seleccionados.
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="aplicarAccion('aprobar')">
                <i class="fas fa-check-circle"></i> Aprobar Seleccionados
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="aplicarAccion('rechazar')">
                <i class="fas fa-times-circle"></i> Rechazar Seleccionados
            </button>
            
        </div>
        
        <form id="formGestionIndividual">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAllEstudiantes">
                            </th>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <th width="90">Nota</th>
                            <th width="80">Estado</th>
                            <th width="180">Acciones Individuales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                            <?php
                            $promedio = calcularPromedioPorTrayecto($estudiante, $info_grupo['id_trayecto']);
                            $estado = $promedio >= 12 ? 'Aprobado' : 'Reprobado';
                            $color_estado = $promedio >= 12 ? 'success' : 'danger';
                            
                            // Obtener la nota específica del trayecto
                            $nota_trayecto = '';
                            switch ($info_grupo['id_trayecto']) {
                                case 1: $nota_trayecto = $estudiante['trayecto_0']; break;
                                case 2: $nota_trayecto = $estudiante['trayecto_1']; break;
                                case 3: $nota_trayecto = $estudiante['trayecto_2']; break;
                                case 4: $nota_trayecto = $estudiante['trayecto_3']; break;
                                case 5: $nota_trayecto = $estudiante['trayecto_4']; break;
                            }
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="notas_ids[]" 
                                           value="<?= $estudiante['id'] ?>" 
                                           class="estudiante-checkbox">
                                </td>
                                <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                                <td class="text-center">
                                    <?php if ($nota_trayecto !== null): ?>
                                        <div class="nota-display">
                                            <span class="nota-valor <?= $nota_trayecto >= 12 ? 'nota-aprobada' : 'nota-reprobada' ?>">
                                                <?= $nota_trayecto ?>
                                            </span>
                                            <small class="text-muted">T<?= $info_grupo['id_trayecto'] - 1 ?></small>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge badge-secondary badge-sm">Sin nota</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $color_estado ?>">
                                        <?= $estado ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-success accion-individual" 
                                                data-accion="aprobar"
                                                data-nota-id="<?= $estudiante['id'] ?>"
                                                data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn btn-danger accion-individual" 
                                                data-accion="rechazar"
                                                data-nota-id="<?= $estudiante['id'] ?>"
                                                data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <style>
        .nota-display {
            text-align: center;
            padding: 2px;
        }
        
        .nota-valor {
            display: inline-block;
            font-size: 1rem;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
            min-width: 40px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            color: #000000 !important; /* Texto negro */
            font-weight: 900; /* Texto más grueso */
        }
        
        .nota-aprobada {
            background: #90EE90; /* Verde claro */
            border: 1px solid #28a745;
        }
        
        .nota-reprobada {
            background: #FFB6C1; /* Rojo claro */
            border: 1px solid #dc3545;
        }
        
        .badge-sm {
            font-size: 0.75rem;
            padding: 3px 6px;
        }
        </style>
        <?php
        break;
        
    case 'resumen':
        ?>
        <h4>Resumen del Grupo</h4>
        <div class="alert alert-info">
            <strong>Trayecto considerado:</strong> <?= $trayecto_considerado ?><br>
            <strong>Aprobación:</strong> ≥12pts
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Información del Grupo</div>
                    <div class="card-body">
                        <p><strong>Docente:</strong> <?= htmlspecialchars($info_grupo['nombre_docente']) ?></p>
                        <p><strong>Materia:</strong> <?= htmlspecialchars($info_grupo['nombre_materia']) ?></p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($info_grupo['cod_materia']) ?></p>
                        <p><strong>Trayecto:</strong> <?= htmlspecialchars($info_grupo['nombre_trayecto']) ?> (ID: <?= $info_grupo['id_trayecto'] ?>)</p>
                        <p><strong>Periodo:</strong> <?= htmlspecialchars($info_grupo['nombre_periodo']) ?></p>
                        <p><strong>Sección:</strong> <?= htmlspecialchars($info_grupo['codigo_seccion']) ?></p>
                        <p><strong>Carrera:</strong> <?= htmlspecialchars($info_grupo['nombre_carrera']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Estadísticas</div>
                    <div class="card-body">
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-value h4 text-primary"><?= $estadisticas['total_estudiantes'] ?></div>
                                <div class="stat-label">Total</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 <?= $estadisticas['promedio_general'] >= 12 ? 'text-success' : 'text-warning' ?>">
                                    <?= $estadisticas['promedio_general'] ?>
                                </div>
                                <div class="stat-label">Promedio</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-success"><?= $estadisticas['aprobados'] ?></div>
                                <div class="stat-label">Aprobados</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-danger"><?= $estadisticas['reprobados'] ?></div>
                                <div class="stat-label">Reprobados</div>
                            </div>
                        </div>
                        
                        <!-- Gráfico simple de progreso -->
                        <?php if ($estadisticas['total_estudiantes'] > 0): ?>
                        <div class="progress mt-2" style="height: 18px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= ($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <span class="progress-text" style="color: #000; font-weight: bold;"><?= $estadisticas['aprobados'] ?></span>
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: <?= ($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <span class="progress-text" style="color: #000; font-weight: bold;"><?= $estadisticas['reprobados'] ?></span>
                            </div>
                        </div>
                        <div class="text-center mt-1">
                            <small class="text-muted">
                                Aprobados: <?= $estadisticas['aprobados'] ?> 
                                (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%) | 
                                Reprobados: <?= $estadisticas['reprobados'] ?> 
                                (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .stats-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .stat-item {
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .stat-value {
            font-weight: bold;
            margin-bottom: 2px;
            color: #000 !important; /* Texto negro en estadísticas */
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .progress-text {
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
        }
        </style>
        <?php
        break;
        
    case 'soporte':
        ?>
        <h4>Soporte del Grupo</h4>
        
        <?php if ($soporte_info): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <strong>Archivo de soporte disponible</strong>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Archivo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($soporte_info['soporte']) ?></p>
                            <p><strong>Tipo:</strong> 
                                <span class="badge badge-info"><?= strtoupper($soporte_info['tipo_archivo']) ?></span>
                            </p>
                            <p><strong>Subido:</strong> 
                                <?= date('d/m/Y H:i', strtotime($soporte_info['fecha_subida'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <?php if (in_array($soporte_info['tipo_archivo'], ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                    <div class="img-preview mb-2">
                                        <img src="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                             alt="Vista previa" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 150px;">
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-file-pdf fa-2x mb-1"></i>
                                        <br>
                                        <strong>PDF</strong>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="btn-group btn-group-sm">
                                    <a href="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                       class="btn btn-primary" 
                                       target="_blank" 
                                       download="<?= htmlspecialchars($soporte_info['soporte']) ?>">
                                        <i class="fas fa-download"></i> Descargar
                                    </a>
                                    <a href="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                       class="btn btn-info" 
                                       target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning mt-2">
                <i class="fas fa-info-circle"></i>
                Este archivo se copiará a las notas definitivas cuando se aprueben.
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>No hay archivo de soporte</strong>
            </div>
            
            <div class="card">
                <div class="card-body text-center py-3">
                    <i class="fas fa-paperclip fa-2x text-muted mb-2"></i>
                    <h5>Sin Soporte</h5>
                    <p class="text-muted mb-0">No se encontró archivo de soporte.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php
        break;
}
?>

<!-- JavaScript -->
<script>
// Variables globales para almacenar la acción pendiente
let accionPendiente = null;
let notasIdsPendientes = [];
let estudianteNombrePendiente = "";

// Función para mostrar/ocultar botones según selección
function actualizarBotones() {
    const selected = $('.estudiante-checkbox:checked');
    const botonesGrupo = $('#botonesGrupo');
    const botonesSeleccion = $('#botonesSeleccion');
    
    if (selected.length === 0) {
        // No hay estudiantes seleccionados - mostrar botones grupales
        botonesGrupo.show();
        botonesSeleccion.hide();
    } else {
        // Hay estudiantes seleccionados - mostrar botones de selección
        botonesGrupo.hide();
        botonesSeleccion.show();
        
        // Actualizar contador en el botón de selección
        const contador = selected.length === 1 ? '1 estudiante' : selected.length + ' estudiantes';
        $('#contadorSeleccion').text(contador);
    }
}

// Función para limpiar selección
function limpiarSeleccion() {
    $('.estudiante-checkbox').prop('checked', false);
    $('#selectAllEstudiantes').prop('checked', false);
    actualizarBotones();
}

function aplicarAccion(accion) {
    const selected = $('.estudiante-checkbox:checked');
    if (selected.length === 0) {
        alert('Seleccione al menos un estudiante');
        return;
    }
    
    const notasIds = selected.map(function() {
        return $(this).val();
    }).get();
    
    // Obtener nombres de estudiantes seleccionados
    const nombresEstudiantes = [];
    selected.each(function() {
        const nombre = $(this).closest('tr').find('td:eq(2)').text();
        nombresEstudiantes.push(nombre);
    });
    
    if (accion === 'rechazar') {
        // Mostrar modal para ingresar mensaje de rechazo
        accionPendiente = accion;
        notasIdsPendientes = notasIds;
        estudianteNombrePendiente = nombresEstudiantes.join(", ");
        $('#mensajeRechazoModal').modal('show');
        
        // Actualizar el título y mensaje en el modal
        $('#tituloRechazoModal').html('<i class="fas fa-exclamation-triangle"></i> Rechazar: ' + (nombresEstudiantes.length > 3 ? nombresEstudiantes.length + ' Estudiantes' : estudianteNombrePendiente));
        $('#estudiantesRechazados').text(estudianteNombrePendiente);
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "Las notas de los estudiantes " + estudianteNombrePendiente + " han sido rechazadas debido a: [ESPECIFIQUE EL MOTIVO]";
        $('#mensajeRechazoTexto').val(mensajePredeterminado);
    } else if (accion === 'aprobar') {
        // Mostrar modal para ingresar mensaje de aprobación
        accionPendiente = accion;
        notasIdsPendientes = notasIds;
        estudianteNombrePendiente = nombresEstudiantes.join(", ");
        $('#mensajeAprobacionModal').modal('show');
        
        // Actualizar el título y mensaje en el modal
        $('#tituloAprobacionModal').html('<i class="fas fa-check-circle"></i> Aprobar: ' + (nombresEstudiantes.length > 3 ? nombresEstudiantes.length + ' Estudiantes' : estudianteNombrePendiente));
        $('#estudiantesAprobados').text(estudianteNombrePendiente);
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "Las notas de los estudiantes " + estudianteNombrePendiente + " han sido aprobadas exitosamente.";
        $('#mensajeAprobacionTexto').val(mensajePredeterminado);
    }
}

function confirmarRechazoConMensaje() {
    const mensaje = $('#mensajeRechazoTexto').val().trim();
    
    if (!mensaje) {
        alert('Por favor, ingrese un mensaje de rechazo');
        return;
    }
    
    $('#mensajeRechazoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones.php',
        type: 'POST',
        data: {
            accion: 'rechazar',
            notas_ids: notasIdsPendientes,
            mensaje_rechazo: mensaje
        },
        success: function() {
            location.reload();
        }
    });
}

function confirmarAprobacionConMensaje() {
    const mensaje = $('#mensajeAprobacionTexto').val().trim();
    
    if (!mensaje) {
        alert('Por favor, ingrese un mensaje de aprobación');
        return;
    }
    
    $('#mensajeAprobacionModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones.php',
        type: 'POST',
        data: {
            accion: 'aprobar',
            notas_ids: notasIdsPendientes,
            mensaje_aprobacion: mensaje
        },
        success: function() {
            location.reload();
        }
    });
}

$('#selectAllEstudiantes').change(function() {
    $('.estudiante-checkbox').prop('checked', this.checked);
    actualizarBotones();
});

$('.estudiante-checkbox').change(function() {
    // Actualizar el checkbox "Seleccionar Todos"
    const totalCheckboxes = $('.estudiante-checkbox').length;
    const checkedCheckboxes = $('.estudiante-checkbox:checked').length;
    $('#selectAllEstudiantes').prop('checked', totalCheckboxes === checkedCheckboxes);
    
    actualizarBotones();
});

$('.accion-individual').click(function() {
    const notaId = $(this).data('nota-id');
    const accion = $(this).data('accion');
    const estudianteNombre = $(this).data('estudiante-nombre');
    
    if (accion === 'rechazar') {
        // Para rechazo individual, mostrar modal
        accionPendiente = accion;
        notasIdsPendientes = [notaId];
        estudianteNombrePendiente = estudianteNombre;
        $('#mensajeRechazoModal').modal('show');
        
        // Actualizar el título y mensaje en el modal
        $('#tituloRechazoModal').html('<i class="fas fa-exclamation-triangle"></i> Rechazar: ' + estudianteNombre);
        $('#estudiantesRechazados').text(estudianteNombrePendiente);
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "La nota del estudiante " + estudianteNombre + " ha sido rechazada debido a: [ESPECIFIQUE EL MOTIVO]";
        $('#mensajeRechazoTexto').val(mensajePredeterminado);
    } else if (accion === 'aprobar') {
        // Para aprobación individual, mostrar modal
        accionPendiente = accion;
        notasIdsPendientes = [notaId];
        estudianteNombrePendiente = estudianteNombre;
        $('#mensajeAprobacionModal').modal('show');
        
        // Actualizar el título y mensaje en el modal
        $('#tituloAprobacionModal').html('<i class="fas fa-check-circle"></i> Aprobar: ' + estudianteNombre);
        $('#estudiantesAprobados').text(estudianteNombrePendiente);
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "La nota del estudiante " + estudianteNombre + " ha sido aprobada exitosamente.";
        $('#mensajeAprobacionTexto').val(mensajePredeterminado);
    }
});

function accionGrupo(accion) {
    if (accion === 'rechazar') {
        // Mostrar modal para ingresar mensaje de rechazo
        $('#mensajeRechazoGrupoModal').modal('show');
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "Las notas de todos los estudiantes del grupo han sido rechazadas debido a: [ESPECIFIQUE EL MOTIVO]";
        $('#mensajeRechazoGrupoTexto').val(mensajePredeterminado);
    } else if (accion === 'aprobar') {
        // Mostrar modal para ingresar mensaje de aprobación
        $('#mensajeAprobacionGrupoModal').modal('show');
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "Las notas de todos los estudiantes del grupo han sido aprobadas exitosamente.";
        $('#mensajeAprobacionGrupoTexto').val(mensajePredeterminado);
    }
}

function confirmarRechazoGrupoConMensaje() {
    const mensaje = $('#mensajeRechazoGrupoTexto').val().trim();
    
    if (!mensaje) {
        alert('Por favor, ingrese un mensaje de rechazo');
        return;
    }
    
    $('#mensajeRechazoGrupoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones.php',
        type: 'POST',
        data: {
            accion: 'rechazar',
            docente_id: <?= $docente_id ?>,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            accion_grupo: true,
            mensaje_rechazo: mensaje
        },
        success: function() {
            location.reload();
        }
    });
}

function confirmarAprobacionGrupoConMensaje() {
    const mensaje = $('#mensajeAprobacionGrupoTexto').val().trim();
    
    if (!mensaje) {
        alert('Por favor, ingrese un mensaje de aprobación');
        return;
    }
    
    $('#mensajeAprobacionGrupoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones.php',
        type: 'POST',
        data: {
            accion: 'aprobar',
            docente_id: <?= $docente_id ?>,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            accion_grupo: true,
            mensaje_aprobacion: mensaje
        },
        success: function() {
            location.reload();
        }
    });
}

// Configurar el modal para que se pueda abrir siempre
$(document).ready(function() {
    // Asegurar que los modales se cierren correctamente sin afectar otros modales
    $('#mensajeRechazoModal, #mensajeAprobacionModal, #mensajeRechazoGrupoModal, #mensajeAprobacionGrupoModal').on('show.bs.modal', function() {
        // Limpiar el mensaje anterior al abrir el modal
        $(this).find('textarea').val('');
    });
    
    // Configurar el botón de cancelar para que solo cierre este modal
    $('.modal .btn-secondary, .modal .close').click(function() {
        $(this).closest('.modal').modal('hide');
        return false; // Prevenir que se cierren otros modales
    });
    
    // Inicializar estado de los botones
    actualizarBotones();
});
</script>

<!-- Modal para mensaje de rechazo (INDIVIDUAL) -->
<div class="modal fade" id="mensajeRechazoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeRechazoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning py-2">
                <h5 class="modal-title" id="tituloRechazoModal">Mensaje de Rechazo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-1">
                    <strong>Estudiantes:</strong>
                    <span id="estudiantesRechazados"></span>
                </div>
                <p>Ingrese el motivo del rechazo:</p>
                <div class="form-group mb-2">
                    <textarea class="form-control form-control-sm" id="mensajeRechazoTexto" rows="4" placeholder="Explique los motivos del rechazo..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarRechazoConMensaje()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensaje de aprobación (INDIVIDUAL) -->
<div class="modal fade" id="mensajeAprobacionModal" tabindex="-1" role="dialog" aria-labelledby="mensajeAprobacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h5 class="modal-title" id="tituloAprobacionModal">Mensaje de Aprobación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-1">
                    <strong>Estudiantes:</strong>
                    <span id="estudiantesAprobados"></span>
                </div>
                <p>Mensaje de confirmación:</p>
                <div class="form-group mb-2">
                    <textarea class="form-control form-control-sm" id="mensajeAprobacionTexto" rows="4" placeholder="Mensaje de confirmación..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarAprobacionConMensaje()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensaje de rechazo (GRUPAL) -->
<div class="modal fade" id="mensajeRechazoGrupoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeRechazoGrupoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning py-2">
                <h5 class="modal-title" id="tituloRechazoGrupoModal">Mensaje de Rechazo Grupal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-1">
                    <strong>Estudiantes:</strong> TODO EL GRUPO
                </div>
                <p>Ingrese el motivo del rechazo:</p>
                <div class="form-group mb-2">
                    <textarea class="form-control form-control-sm" id="mensajeRechazoGrupoTexto" rows="4" placeholder="Explique los motivos del rechazo..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarRechazoGrupoConMensaje()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensaje de aprobación (GRUPAL) -->
<div class="modal fade" id="mensajeAprobacionGrupoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeAprobacionGrupoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success py-2">
                <h5 class="modal-title" id="tituloAprobacionGrupoModal">Mensaje de Aprobación Grupal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-1">
                    <strong>Estudiantes:</strong> TODO EL GRUPO
                </div>
                <p>Mensaje de confirmación:</p>
                <div class="form-group mb-2">
                    <textarea class="form-control form-control-sm" id="mensajeAprobacionGrupoTexto" rows="4" placeholder="Mensaje de confirmación..."></textarea>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmarAprobacionGrupoConMensaje()">Enviar</button>
            </div>
        </div>
    </div>
</div>