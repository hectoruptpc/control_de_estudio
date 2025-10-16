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
        
        <form id="formGestionIndividual">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllEstudiantes">
                            </th>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <th>Nota del Trayecto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
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
                                <td>
                                    <?php if ($nota_trayecto !== null): ?>
                                        <span class="badge badge-info">
                                            T<?= $info_grupo['id_trayecto'] - 1 ?>: <?= $nota_trayecto ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Sin nota</span>
                                    <?php endif; ?>
                                </td>
                                <td>
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
            
            <div class="mt-3">
                <button type="button" class="btn btn-success" onclick="aplicarAccion('aprobar')">
                    <i class="fas fa-check-circle"></i> Aprobar Seleccionados
                </button>
                <button type="button" class="btn btn-danger" onclick="aplicarAccion('rechazar')">
                    <i class="fas fa-times-circle"></i> Rechazar Seleccionados
                </button>
            </div>
        </form>
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
                        <p><strong>Total Estudiantes:</strong> 
                            <span class="badge badge-primary"><?= $estadisticas['total_estudiantes'] ?></span>
                        </p>
                        <p><strong>Promedio General:</strong> 
                            <span class="badge badge-<?= $estadisticas['promedio_general'] >= 12 ? 'success' : 'warning' ?>">
                                <?= $estadisticas['promedio_general'] ?>
                            </span>
                        </p>
                        <p><strong>Aprobados (≥12pts):</strong> 
                            <span class="badge badge-success"><?= $estadisticas['aprobados'] ?></span>
                            (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                        </p>
                        <p><strong>Reprobados (<12pts):</strong> 
                            <span class="badge badge-danger"><?= $estadisticas['reprobados'] ?></span>
                            (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                        </p>
                        
                        <!-- Gráfico simple de progreso -->
                        <?php if ($estadisticas['total_estudiantes'] > 0): ?>
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= ($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                Aprobados: <?= $estadisticas['aprobados'] ?>
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: <?= ($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                Reprobados: <?= $estadisticas['reprobados'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
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
                            <p><strong>Nombre del archivo:</strong> <?= htmlspecialchars($soporte_info['soporte']) ?></p>
                            <p><strong>Tipo de archivo:</strong> 
                                <span class="badge badge-info"><?= strtoupper($soporte_info['tipo_archivo']) ?></span>
                            </p>
                            <p><strong>Fecha de subida:</strong> 
                                <?= date('d/m/Y H:i', strtotime($soporte_info['fecha_subida'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <?php if (in_array($soporte_info['tipo_archivo'], ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                    <div class="img-preview mb-3">
                                        <img src="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                             alt="Vista previa del soporte" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 300px;">
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-file-pdf fa-3x mb-3"></i>
                                        <br>
                                        <strong>Archivo PDF</strong>
                                        <br>
                                        <small class="text-muted">Haga clic en el botón para descargar</small>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="btn-group">
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
            
            <div class="alert alert-warning mt-3">
                <i class="fas fa-info-circle"></i>
                <strong>Nota:</strong> Este archivo de soporte será copiado a las notas definitivas cuando se aprueben las notas.
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>No hay archivo de soporte disponible</strong>
                <p class="mb-0">El docente no ha subido ningún archivo de soporte para este grupo.</p>
            </div>
            
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                    <h5>Sin Soporte</h5>
                    <p class="text-muted">No se encontró ningún archivo de soporte asociado a este grupo de notas.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php
        break;
        
    case 'acciones-grupo':
        ?>
        <h4>Acciones Grupales</h4>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Precaución:</strong> Estas acciones afectarán a TODOS los estudiantes del grupo.
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-check-circle"></i> Aprobar Todo el Grupo
                    </div>
                    <div class="card-body">
                        <p>Aprobará las notas de todos los estudiantes en este grupo.</p>
                        <?php if ($soporte_info): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-paperclip"></i> 
                                Se incluirá el archivo de soporte en las notas definitivas.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                No hay archivo de soporte disponible.
                            </div>
                        <?php endif; ?>
                        <button class="btn btn-success btn-block" 
                                onclick="accionGrupo('aprobar')">
                            Aprobar Todo
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-times-circle"></i> Rechazar Todo el Grupo
                    </div>
                    <div class="card-body">
                        <p>Rechazará las notas de todos los estudiantes en este grupo.</p>
                        <button class="btn btn-danger btn-block" 
                                onclick="accionGrupo('rechazar')">
                            Rechazar Todo
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modales para acciones grupales (APROBAR TODO / RECHAZAR TODO) -->
        <div class="modal fade" id="mensajeRechazoGrupoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeRechazoGrupoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="tituloRechazoGrupoModal">Mensaje de Rechazo Grupal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Estudiante(s) a rechazar:</strong>
                            <span id="estudiantesRechazadosGrupo">TODO EL GRUPO</span>
                        </div>
                        <p>Por favor, ingrese el motivo del rechazo de las notas. Este mensaje será enviado al docente.</p>
                        <div class="form-group">
                            <label for="mensajeRechazoGrupoTexto">Mensaje:</label>
                            <textarea class="form-control" id="mensajeRechazoGrupoTexto" rows="5" placeholder="Explique los motivos del rechazo..."></textarea>
                        </div>
                        <div class="alert alert-secondary">
                            <small><i class="fas fa-info-circle"></i> Puede editar el mensaje predeterminado según sea necesario.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarRechazoGrupoConMensaje()">Enviar Rechazo</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mensajeAprobacionGrupoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeAprobacionGrupoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="tituloAprobacionGrupoModal">Mensaje de Aprobación Grupal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Estudiante(s) a aprobar:</strong>
                            <span id="estudiantesAprobadosGrupo">TODO EL GRUPO</span>
                        </div>
                        <p>Puede enviar un mensaje de confirmación al docente. Este mensaje será enviado al docente.</p>
                        <div class="form-group">
                            <label for="mensajeAprobacionGrupoTexto">Mensaje:</label>
                            <textarea class="form-control" id="mensajeAprobacionGrupoTexto" rows="5" placeholder="Mensaje de confirmación de aprobación..."></textarea>
                        </div>
                        <div class="alert alert-secondary">
                            <small><i class="fas fa-info-circle"></i> Puede editar el mensaje predeterminado según sea necesario.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarAprobacionGrupoConMensaje()">Enviar Aprobación</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
}
?>

<!-- El resto del JavaScript permanece igual -->
<script>
// Variables globales para almacenar la acción pendiente
let accionPendiente = null;
let notasIdsPendientes = [];
let estudianteNombrePendiente = "";

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
        $('#tituloRechazoModal').html('<i class="fas fa-exclamation-triangle"></i> Rechazar Notas de: ' + (nombresEstudiantes.length > 3 ? nombresEstudiantes.length + ' Estudiantes' : estudianteNombrePendiente));
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
        $('#tituloAprobacionModal').html('<i class="fas fa-check-circle"></i> Aprobar Notas de: ' + (nombresEstudiantes.length > 3 ? nombresEstudiantes.length + ' Estudiantes' : estudianteNombrePendiente));
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
        $('#tituloRechazoModal').html('<i class="fas fa-exclamation-triangle"></i> Rechazar Nota de: ' + estudianteNombre);
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
        $('#tituloAprobacionModal').html('<i class="fas fa-check-circle"></i> Aprobar Nota de: ' + estudianteNombre);
        $('#estudiantesAprobados').text(estudianteNombrePendiente);
        
        // Establecer mensaje predeterminado
        const mensajePredeterminado = "La nota del estudiante " + estudianteNombre + " ha sido aprobada exitosamente.";
        $('#mensajeAprobacionTexto').val(mensajePredeterminado);
    }
});

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
</script>

<!-- Modal para mensaje de rechazo (INDIVIDUAL) -->
<div class="modal fade" id="mensajeRechazoModal" tabindex="-1" role="dialog" aria-labelledby="mensajeRechazoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="tituloRechazoModal">Mensaje de Rechazo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Estudiante(s) a rechazar:</strong>
                    <span id="estudiantesRechazados"></span>
                </div>
                <p>Por favor, ingrese el motivo del rechazo de las notas. Este mensaje será enviado al docente.</p>
                <div class="form-group">
                    <label for="mensajeRechazoTexto">Mensaje:</label>
                    <textarea class="form-control" id="mensajeRechazoTexto" rows="5" placeholder="Explique los motivos del rechazo..."></textarea>
                </div>
                <div class="alert alert-secondary">
                    <small><i class="fas fa-info-circle"></i> Puede editar el mensaje predeterminado según sea necesario.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarRechazoConMensaje()">Enviar Rechazo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mensaje de aprobación (INDIVIDUAL) -->
<div class="modal fade" id="mensajeAprobacionModal" tabindex="-1" role="dialog" aria-labelledby="mensajeAprobacionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="tituloAprobacionModal">Mensaje de Aprobación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Estudiante(s) a aprobar:</strong>
                    <span id="estudiantesAprobados"></span>
                </div>
                <p>Puede enviar un mensaje de confirmación al docente. Este mensaje será enviado al docente.</p>
                <div class="form-group">
                    <label for="mensajeAprobacionTexto">Mensaje:</label>
                    <textarea class="form-control" id="mensajeAprobacionTexto" rows="5" placeholder="Mensaje de confirmación de aprobación..."></textarea>
                </div>
                <div class="alert alert-secondary">
                    <small><i class="fas fa-info-circle"></i> Puede editar el mensaje predeterminado según sea necesario.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarAprobacionConMensaje()">Enviar Aprobación</button>
            </div>
        </div>
    </div>
</div>