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
            <strong>Aprobación:</strong> Nota final ≥ 12pts (promedio de los 3 trimestres)
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
                                        <span class="badge <?= $t1 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2">
                                            <?= number_format($t1, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <td class="text-center">
                                    <?php if ($t2 !== null): ?>
                                        <span class="badge <?= $t2 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2">
                                            <?= number_format($t2, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <td class="text-center">
                                    <?php if ($t3 !== null): ?>
                                        <span class="badge <?= $t3 >= 12 ? 'bg-success' : 'bg-danger' ?> p-2">
                                            <?= number_format($t3, 1) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <td class="text-center">
                                    <?php if ($nota_final !== null): ?>
                                        <span class="badge <?= $nota_final >= 12 ? 'bg-success' : 'bg-danger' ?> p-2" style="font-size: 1rem;">
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
                                        <?php if ($estado !== 'aprobada'): ?>
                                            <button type="button" class="btn btn-success accion-individual" 
                                                    data-accion="aprobar"
                                                    data-usuario-id="<?= $estudiante['id_usuario'] ?>"
                                                    data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                                <i class="fas fa-check"></i> Aprobar
                                            </button>
                                            <button type="button" class="btn btn-danger accion-individual" 
                                                    data-accion="rechazar"
                                                    data-usuario-id="<?= $estudiante['id_usuario'] ?>"
                                                    data-estudiante-nombre="<?= htmlspecialchars($estudiante['nombre_estudiante']) ?>">
                                                <i class="fas fa-times"></i> Rechazar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Modales -->
        <div class="modal fade" id="modalMensajeRechazo" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Rechazar Notas</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info"><strong>Estudiantes:</strong> <span id="estudiantesRechazados"></span></div>
                        <textarea class="form-control" id="mensajeRechazo" rows="4" placeholder="Motivo del rechazo..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnRechazar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalMensajeAprobacion" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">Aprobar Notas</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info"><strong>Estudiantes:</strong> <span id="estudiantesAprobados"></span></div>
                        <textarea class="form-control" id="mensajeAprobacion" rows="4" placeholder="Mensaje de aprobación..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnAprobar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalResultado" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id="modalResultadoHeader">
                        <h5 class="modal-title">Resultado</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="modalResultadoBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function() {
            let accionPendiente = null;
            let notasIdsPendientes = [];
            let esAccionGrupal = false;
            let docenteIdGlobal = <?= $docente_id ?>;
            let materiaIdGlobal = <?= $materia_id ?>;
            let periodoIdGlobal = <?= $periodo_id ?>;
            
            function actualizarBotones() {
                const selected = $('.estudiante-checkbox:checked');
                if (selected.length === 0) {
                    $('#botonesGrupo').show();
                    $('#botonesSeleccion').hide();
                } else {
                    $('#botonesGrupo').hide();
                    $('#botonesSeleccion').show();
                    $('#contadorSeleccion').text(selected.length === 1 ? '1 estudiante' : selected.length + ' estudiantes');
                }
            }
            
            window.limpiarSeleccion = function() {
                $('.estudiante-checkbox').prop('checked', false);
                $('#selectAllEstudiantes').prop('checked', false);
                actualizarBotones();
            };
            
            window.aplicarAccion = function(accion) {
                const selected = $('.estudiante-checkbox:checked');
                if (selected.length === 0) {
                    alert('Seleccione al menos un estudiante');
                    return;
                }
                const usuarioIds = selected.map(function() { return $(this).val(); }).get();
                const nombres = selected.map(function() { return $(this).data('estudiante-nombre'); }).get();
                
                accionPendiente = accion;
                notasIdsPendientes = usuarioIds;
                esAccionGrupal = false;
                
                if (accion === 'rechazar') {
                    $('#estudiantesRechazados').text(nombres.join(", "));
                    $('#modalMensajeRechazo').modal('show');
                } else {
                    $('#estudiantesAprobados').text(nombres.join(", "));
                    $('#modalMensajeAprobacion').modal('show');
                }
            };
            
            window.accionGrupo = function(accion) {
                accionPendiente = accion;
                esAccionGrupal = true;
                if (accion === 'rechazar') {
                    $('#estudiantesRechazados').text('TODOS los estudiantes del grupo');
                    $('#modalMensajeRechazo').modal('show');
                } else {
                    $('#estudiantesAprobados').text('TODOS los estudiantes del grupo');
                    $('#modalMensajeAprobacion').modal('show');
                }
            };
            
            function procesarAccion() {
                const mensaje = accionPendiente === 'rechazar' ? $('#mensajeRechazo').val().trim() : $('#mensajeAprobacion').val().trim();
                if (!mensaje) {
                    alert('Por favor, ingrese un mensaje');
                    return;
                }
                
                let datos = {
                    accion: accionPendiente,
                    materia_id: materiaIdGlobal,
                    periodo_id: periodoIdGlobal,
                    mensaje: mensaje
                };
                
                if (esAccionGrupal) {
                    datos.accion_grupo = 'true';
                    datos.docente_id = docenteIdGlobal;
                } else {
                    datos.usuario_ids = notasIdsPendientes;
                }
                
                $('#modalMensajeRechazo').modal('hide');
                $('#modalMensajeAprobacion').modal('hide');
                
                $.ajax({
                    url: 'procesar_acciones_trimestres.php',
                    type: 'POST',
                    data: datos,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#modalResultadoHeader').removeClass('bg-danger').addClass('bg-success');
                            $('#modalResultadoBody').html('<div class="text-center"><i class="fas fa-check-circle fa-3x text-success mb-3"></i><p>' + response.message + '</p></div>');
                            $('#modalResultado').modal('show');
                            $('#modalResultado').on('hidden.bs.modal', function() { location.reload(); });
                        } else {
                            $('#modalResultadoHeader').removeClass('bg-success').addClass('bg-danger');
                            $('#modalResultadoBody').html('<div class="text-center"><i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><p>' + response.message + '</p></div>');
                            $('#modalResultado').modal('show');
                        }
                    },
                    error: function(xhr) {
                        $('#modalResultadoHeader').removeClass('bg-success').addClass('bg-danger');
                        $('#modalResultadoBody').html('<div class="text-center"><i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><p>Error al procesar: ' + xhr.status + '</p></div>');
                        $('#modalResultado').modal('show');
                    }
                });
            }
            
            $('#selectAllEstudiantes').change(function() {
                $('.estudiante-checkbox').prop('checked', this.checked);
                actualizarBotones();
            });
            
            $('.estudiante-checkbox').change(actualizarBotones);
            
            $('.accion-individual').click(function() {
                const usuarioId = $(this).data('usuario-id');
                const accion = $(this).data('accion');
                const nombre = $(this).data('estudiante-nombre');
                
                accionPendiente = accion;
                notasIdsPendientes = [usuarioId];
                esAccionGrupal = false;
                
                if (accion === 'rechazar') {
                    $('#estudiantesRechazados').text(nombre);
                    $('#modalMensajeRechazo').modal('show');
                } else {
                    $('#estudiantesAprobados').text(nombre);
                    $('#modalMensajeAprobacion').modal('show');
                }
            });
            
            $('#btnRechazar').click(procesarAccion);
            $('#btnAprobar').click(procesarAccion);
            
            actualizarBotones();
        })();
        </script>
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
                        <div class="row text-center">
                            <div class="col-4">
                                <h3 class="text-primary"><?= $estadisticas['total_estudiantes'] ?></h3>
                                <small>Total</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-success"><?= $estadisticas['aprobados'] ?></h3>
                                <small>Aprobados</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-danger"><?= $estadisticas['reprobados'] ?></h3>
                                <small>Reprobados</small>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar bg-success" style="width: <?= ($estadisticas['aprobados'] / max(1, $estadisticas['total_estudiantes'])) * 100 ?>%"><?= $estadisticas['aprobados'] ?></div>
                            <div class="progress-bar bg-danger" style="width: <?= ($estadisticas['reprobados'] / max(1, $estadisticas['total_estudiantes'])) * 100 ?>%"><?= $estadisticas['reprobados'] ?></div>
                            <div class="progress-bar bg-secondary" style="width: <?= ($estadisticas['pendientes'] / max(1, $estadisticas['total_estudiantes'])) * 100 ?>%"><?= $estadisticas['pendientes'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
}
?>