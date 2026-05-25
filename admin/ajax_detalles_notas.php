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

// Obtener información del grupo
$info_grupo = obtenerInfoGrupoNotasTrimestres($docente_id, $materia_id, $periodo_id);

switch ($seccion) {
    case 'lista-estudiantes':
        $estudiantes = obtenerEstudiantesConNotasTrimestres($docente_id, $materia_id, $periodo_id);
        ?>
        <h4>Lista de Estudiantes</h4>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Aprobación:</strong> Nota final ≥ 12pts (promedio de los 3 trimestres)<br>
            <strong>Estado:</strong> Pendiente = Sin revisar | Aprobado = Nota final ≥ 12 | Reprobado = Nota final &lt; 12
        </div>
        
        <!-- Botones Aprobar/Rechazar Todo -->
        <div id="botonesGrupo" class="mb-3">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Acciones Grupales:</strong> Estas acciones afectarán a TODOS los estudiantes del grupo.
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="accionGrupo('aprobar')">
                <i class="fas fa-check-circle"></i> Aprobar Todo el Grupo
            </button>
            <button type="button" class="btn btn-danger btn-sm" onclick="accionGrupo('rechazar')">
                <i class="fas fa-times-circle"></i> Rechazar Todo el Grupo
            </button>
        </div>
        
        <!-- Botones Aprobar/Rechazar Seleccionados -->
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
            <button type="button" class="btn btn-secondary btn-sm" onclick="limpiarSeleccion()">
                <i class="fas fa-times"></i> Limpiar Selección
            </button>
            <span id="contadorSeleccion" class="badge badge-primary ml-2"></span>
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
                            <th class="text-center" width="80">Trimestre 1</th>
                            <th class="text-center" width="80">Trimestre 2</th>
                            <th class="text-center" width="80">Trimestre 3</th>
                            <th class="text-center" width="90">Nota Final</th>
                            <th class="text-center" width="100">Estado</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): 
                            // Calcular nota final
                            $t1 = $estudiante['trimestre_1'];
                            $t2 = $estudiante['trimestre_2'];
                            $t3 = $estudiante['trimestre_3'];
                            
                            $suma = 0;
                            $count = 0;
                            if ($t1 !== null) { $suma += $t1; $count++; }
                            if ($t2 !== null) { $suma += $t2; $count++; }
                            if ($t3 !== null) { $suma += $t3; $count++; }
                            $nota_final = $count > 0 ? round($suma / $count, 1) : null;
                            
                            $estado = $estudiante['estado'];
                            $badge_class = 'secondary';
                            $badge_text = 'Pendiente';
                            
                            if ($estado === 'aprobada') {
                                $badge_class = 'success';
                                $badge_text = 'Aprobada';
                            } elseif ($estado === 'rechazada') {
                                $badge_class = 'danger';
                                $badge_text = 'Rechazada';
                            } elseif ($estado === 'en_revision') {
                                $badge_class = 'warning';
                                $badge_text = 'En Revisión';
                            }
                            
                            $puede_aprobar = ($estado !== 'aprobada');
                            $puede_rechazar = ($estado !== 'aprobada');
                        ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="notas_ids[]" 
                                           value="<?= $estudiante['id_usuario'] ?>" 
                                           class="estudiante-checkbox"
                                           data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                </div>
                                <td><?= htmlspecialchars($estudiante['cedula']) ?></div>
                                <td><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></div>
                                
                                <td class="text-center">
                                    <?php if ($t1 !== null): ?>
                                        <span class="badge <?= $t1 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2" style="font-size: 0.9rem;">
                                            <?= number_format($t1, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                
                                <td class="text-center">
                                    <?php if ($t2 !== null): ?>
                                        <span class="badge <?= $t2 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2" style="font-size: 0.9rem;">
                                            <?= number_format($t2, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                
                                <td class="text-center">
                                    <?php if ($t3 !== null): ?>
                                        <span class="badge <?= $t3 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2" style="font-size: 0.9rem;">
                                            <?= number_format($t3, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                
                                <td class="text-center">
                                    <?php if ($nota_final !== null): ?>
                                        <span class="badge <?= $nota_final >= 12 ? 'bg-success' : 'bg-danger' ?> p-2" style="font-size: 1rem; font-weight: bold;">
                                            <?= number_format($nota_final, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                
                                <td class="text-center">
                                    <span class="badge badge-<?= $badge_class ?> px-3 py-2"><?= $badge_text ?></span>
                                </div>
                                
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($puede_aprobar): ?>
                                            <button type="button" class="btn btn-success accion-individual" 
                                                    data-accion="aprobar"
                                                    data-usuario-id="<?= $estudiante['id_usuario'] ?>"
                                                    data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($puede_rechazar): ?>
                                            <button type="button" class="btn btn-danger accion-individual" 
                                                    data-accion="rechazar"
                                                    data-usuario-id="<?= $estudiante['id_usuario'] ?>"
                                                    data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                                <i class="fas fa-times"></i> Rechazar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <style>
        .bg-success {
            background-color: #28a745 !important;
            color: white !important;
        }
        .bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
        }
        </style>
        <?php
        break;
        
    case 'resumen':
        $estadisticas = obtenerEstadisticasGrupoTrimestres($docente_id, $materia_id, $periodo_id);
        ?>
        <h4>Resumen del Grupo</h4>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Información del Grupo</div>
                    <div class="card-body">
                        <p><strong>Docente:</strong> <?= htmlspecialchars($info_grupo['nombre_docente']) ?></p>
                        <p><strong>Materia:</strong> <?= htmlspecialchars($info_grupo['nombre_materia']) ?></p>
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
                                <div class="stat-label">Total Estudiantes</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 <?= $estadisticas['promedio_general'] >= 12 ? 'text-success' : 'text-warning' ?>">
                                    <?= $estadisticas['promedio_general'] ?>
                                </div>
                                <div class="stat-label">Promedio General</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-success"><?= $estadisticas['aprobados'] ?></div>
                                <div class="stat-label">Aprobados</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-danger"><?= $estadisticas['reprobados'] ?></div>
                                <div class="stat-label">Reprobados</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-warning"><?= $estadisticas['pendientes'] ?></div>
                                <div class="stat-label">Pendientes</div>
                            </div>
                        </div>
                        
                        <?php if ($estadisticas['total_estudiantes'] > 0): ?>
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= ($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <?= $estadisticas['aprobados'] ?>
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: <?= ($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <?= $estadisticas['reprobados'] ?>
                            </div>
                            <div class="progress-bar bg-secondary" 
                                 style="width: <?= ($estadisticas['pendientes'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <?= $estadisticas['pendientes'] ?>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <small>
                                Aprobados: <?= round(($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100, 1) ?>% | 
                                Reprobados: <?= round(($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100, 1) ?>% | 
                                Pendientes: <?= round(($estadisticas['pendientes'] / $estadisticas['total_estudiantes']) * 100, 1) ?>%
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
        }
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
        </style>
        <?php
        break;
}
?>

<!-- JavaScript -->
<script>
let accionPendiente = null;
let notasIdsPendientes = [];
let estudianteNombrePendiente = "";

function actualizarBotones() {
    const selected = $('.estudiante-checkbox:checked');
    const botonesGrupo = $('#botonesGrupo');
    const botonesSeleccion = $('#botonesSeleccion');
    
    if (selected.length === 0) {
        botonesGrupo.show();
        botonesSeleccion.hide();
    } else {
        botonesGrupo.hide();
        botonesSeleccion.show();
        const contador = selected.length === 1 ? '1 estudiante' : selected.length + ' estudiantes';
        $('#contadorSeleccion').text(contador);
    }
}

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
    
    const usuarioIds = selected.map(function() {
        return $(this).val();
    }).get();
    
    const nombresEstudiantes = [];
    selected.each(function() {
        const nombre = $(this).data('estudiante-nombre');
        nombresEstudiantes.push(nombre);
    });
    
    if (accion === 'rechazar') {
        accionPendiente = accion;
        notasIdsPendientes = usuarioIds;
        estudianteNombrePendiente = nombresEstudiantes.join(", ");
        $('#mensajeRechazoModal').modal('show');
        $('#estudiantesRechazados').text(estudianteNombrePendiente);
        $('#mensajeRechazoTexto').val("Las notas han sido rechazadas debido a: [ESPECIFIQUE EL MOTIVO]");
    } else if (accion === 'aprobar') {
        accionPendiente = accion;
        notasIdsPendientes = usuarioIds;
        estudianteNombrePendiente = nombresEstudiantes.join(", ");
        $('#mensajeAprobacionModal').modal('show');
        $('#estudiantesAprobados').text(estudianteNombrePendiente);
        $('#mensajeAprobacionTexto').val("Las notas han sido aprobadas exitosamente.");
    }
}

function accionGrupo(accion) {
    if (accion === 'rechazar') {
        $('#mensajeRechazoGrupoModal').modal('show');
        $('#mensajeRechazoGrupoTexto').val("Las notas de todo el grupo han sido rechazadas debido a: [ESPECIFIQUE EL MOTIVO]");
    } else if (accion === 'aprobar') {
        $('#mensajeAprobacionGrupoModal').modal('show');
        $('#mensajeAprobacionGrupoTexto').val("Las notas de todo el grupo han sido aprobadas exitosamente.");
    }
}

function confirmarRechazo() {
    const mensaje = $('#mensajeRechazoTexto').val().trim();
    if (!mensaje) { alert('Por favor, ingrese un mensaje'); return; }
    $('#mensajeRechazoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones_trimestres.php',
        type: 'POST',
        data: {
            accion: 'rechazar',
            usuario_ids: notasIdsPendientes,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            mensaje: mensaje
        },
        success: function(response) {
            if (response.success) location.reload();
            else alert('Error: ' + response.message);
        },
        error: function() { alert('Error al procesar'); }
    });
}

function confirmarAprobacion() {
    const mensaje = $('#mensajeAprobacionTexto').val().trim();
    if (!mensaje) { alert('Por favor, ingrese un mensaje'); return; }
    $('#mensajeAprobacionModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones_trimestres.php',
        type: 'POST',
        data: {
            accion: 'aprobar',
            usuario_ids: notasIdsPendientes,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            mensaje: mensaje
        },
        success: function(response) {
            if (response.success) location.reload();
            else alert('Error: ' + response.message);
        },
        error: function() { alert('Error al procesar'); }
    });
}

function confirmarRechazoGrupo() {
    const mensaje = $('#mensajeRechazoGrupoTexto').val().trim();
    if (!mensaje) { alert('Por favor, ingrese un mensaje'); return; }
    $('#mensajeRechazoGrupoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones_trimestres.php',
        type: 'POST',
        data: {
            accion: 'rechazar',
            docente_id: <?= $docente_id ?>,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            accion_grupo: true,
            mensaje: mensaje
        },
        success: function(response) {
            if (response.success) location.reload();
            else alert('Error: ' + response.message);
        },
        error: function() { alert('Error al procesar'); }
    });
}

function confirmarAprobacionGrupo() {
    const mensaje = $('#mensajeAprobacionGrupoTexto').val().trim();
    if (!mensaje) { alert('Por favor, ingrese un mensaje'); return; }
    $('#mensajeAprobacionGrupoModal').modal('hide');
    
    $.ajax({
        url: 'procesar_acciones_trimestres.php',
        type: 'POST',
        data: {
            accion: 'aprobar',
            docente_id: <?= $docente_id ?>,
            materia_id: <?= $materia_id ?>,
            periodo_id: <?= $periodo_id ?>,
            accion_grupo: true,
            mensaje: mensaje
        },
        success: function(response) {
            if (response.success) location.reload();
            else alert('Error: ' + response.message);
        },
        error: function() { alert('Error al procesar'); }
    });
}

$('#selectAllEstudiantes').change(function() {
    $('.estudiante-checkbox').prop('checked', this.checked);
    actualizarBotones();
});

$('.estudiante-checkbox').change(function() {
    const total = $('.estudiante-checkbox').length;
    const checked = $('.estudiante-checkbox:checked').length;
    $('#selectAllEstudiantes').prop('checked', total === checked);
    actualizarBotones();
});

$('.accion-individual').click(function() {
    const usuarioId = $(this).data('usuario-id');
    const accion = $(this).data('accion');
    const estudianteNombre = $(this).data('estudiante-nombre');
    
    if (accion === 'rechazar') {
        notasIdsPendientes = [usuarioId];
        estudianteNombrePendiente = estudianteNombre;
        $('#mensajeRechazoModal').modal('show');
        $('#estudiantesRechazados').text(estudianteNombre);
        $('#mensajeRechazoTexto').val("La nota ha sido rechazada debido a: [ESPECIFIQUE EL MOTIVO]");
    } else if (accion === 'aprobar') {
        notasIdsPendientes = [usuarioId];
        estudianteNombrePendiente = estudianteNombre;
        $('#mensajeAprobacionModal').modal('show');
        $('#estudiantesAprobados').text(estudianteNombre);
        $('#mensajeAprobacionTexto').val("La nota ha sido aprobada exitosamente.");
    }
});

$(document).ready(function() {
    actualizarBotones();
});
</script>

<!-- Modales -->
<div class="modal fade" id="mensajeRechazoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning"><h5 class="modal-title">Rechazar Notas</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <div class="alert alert-info"><strong>Estudiantes:</strong> <span id="estudiantesRechazados"></span></div>
                <textarea class="form-control" id="mensajeRechazoTexto" rows="4" placeholder="Motivo del rechazo..."></textarea>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" onclick="confirmarRechazo()">Enviar</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mensajeAprobacionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success"><h5 class="modal-title">Aprobar Notas</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <div class="alert alert-info"><strong>Estudiantes:</strong> <span id="estudiantesAprobados"></span></div>
                <textarea class="form-control" id="mensajeAprobacionTexto" rows="4" placeholder="Mensaje de aprobación..."></textarea>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" onclick="confirmarAprobacion()">Enviar</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mensajeRechazoGrupoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning"><h5 class="modal-title">Rechazar Todo el Grupo</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><textarea class="form-control" id="mensajeRechazoGrupoTexto" rows="4" placeholder="Motivo del rechazo grupal..."></textarea></div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" onclick="confirmarRechazoGrupo()">Enviar</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="mensajeAprobacionGrupoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success"><h5 class="modal-title">Aprobar Todo el Grupo</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><textarea class="form-control" id="mensajeAprobacionGrupoTexto" rows="4" placeholder="Mensaje de aprobación grupal..."></textarea></div>
            <div class="modal-footer"><button class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button class="btn btn-primary" onclick="confirmarAprobacionGrupo()">Enviar</button></div>
        </div>
    </div>
</div>