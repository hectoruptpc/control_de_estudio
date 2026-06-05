<?php
require_once('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('notas_cargadas');

// Verificar autenticación y rol
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['msg'] = "Debes iniciar sesión como administrador para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Administrar Notas Pendientes";
include("includes/head.php");

// Obtener grupos de notas pendientes desde notas_trimestres (solo estado 'en_revision')
function obtenerGruposNotasPendientes() {
    global $db;
    
    $query = "SELECT 
                nt.id_docente, 
                nt.id_materia, 
                nt.id_periodo,
                MAX(ud.nombre) as nombre_docente, 
                MAX(m.nombre_materia) as nombre_materia, 
                MAX(pa.nombre_periodo) as nombre_periodo, 
                MAX(s.codigo_seccion) as codigo_seccion, 
                MAX(c.nombre_carrera) as nombre_carrera,
                COUNT(DISTINCT nt.id_usuario) as total_estudiantes,
                MAX(nt.fecha_registro) as ultima_fecha
              FROM notas_trimestres nt
              INNER JOIN users ud ON nt.id_docente = ud.id
              INNER JOIN materias m ON nt.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nt.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON nt.id_docente = ds.id_usuario 
                                           AND nt.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              WHERE nt.estado = 'en_revision'
              GROUP BY nt.id_docente, nt.id_materia, nt.id_periodo
              ORDER BY ultima_fecha DESC";
    
    $result = $db->query($query);
    return $result;
}

$grupos_notas = obtenerGruposNotasPendientes();
?>

<div class="container-fluid">
    <h2 class="my-4">Administrar Notas Pendientes (En Revisión)</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg'] ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Cargas de Notas Pendientes por Docente</h5>
        </div>
        <div class="card-body">
            <?php if ($grupos_notas && $grupos_notas->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Docente</th>
                                <th>Materia</th>
                                <th>Periodo</th>
                                <th>Sección</th>
                                <th>Carrera</th>
                                <th># Estudiantes</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            <tr>
                        </thead>
                        <tbody>
                            <?php while ($grupo = $grupos_notas->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nombre_docente']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_materia']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_periodo']) ?></td>
                                    <td><?= htmlspecialchars($grupo['codigo_seccion']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_carrera']) ?></td>
                                    <td><span class="badge badge-info"><?= $grupo['total_estudiantes'] ?></span></td>
                                    <td><?= date('d/m/Y H:i', strtotime($grupo['ultima_fecha'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info btn-detalles" 
                                                data-toggle="modal" data-target="#modalDetalles"
                                                data-docente-id="<?= $grupo['id_docente'] ?>"
                                                data-materia-id="<?= $grupo['id_materia'] ?>"
                                                data-periodo-id="<?= $grupo['id_periodo'] ?>"
                                                data-docente="<?= htmlspecialchars($grupo['nombre_docente']) ?>"
                                                data-materia="<?= htmlspecialchars($grupo['nombre_materia']) ?>"
                                                data-periodo="<?= htmlspecialchars($grupo['nombre_periodo']) ?>"
                                                data-seccion="<?= htmlspecialchars($grupo['codigo_seccion']) ?>"
                                                data-carrera="<?= htmlspecialchars($grupo['nombre_carrera']) ?>">
                                            Gestionar Notas
                                        </button>
                                    </div>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    No hay notas pendientes de aprobación en este momento.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para gestionar notas -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Gestionar Notas - <span id="tituloGrupo"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="list-group" id="sidebarDetalles">
                            <a href="#lista-estudiantes" class="list-group-item list-group-item-action active" data-toggle="tab">
                                <i class="fas fa-users"></i> Lista de Estudiantes
                            </a>
                            <a href="#resumen" class="list-group-item list-group-item-action" data-toggle="tab">
                                <i class="fas fa-chart-bar"></i> Resumen
                            </a>
                            <a href="#soporte" class="list-group-item list-group-item-action" data-toggle="tab">
                                <i class="fas fa-paperclip"></i> Soporte
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="tab-content" id="contenidoDetalles">
                            <div class="tab-pane fade show active" id="lista-estudiantes">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando estudiantes...</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="resumen">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando resumen...</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="soporte">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando soporte...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar resultado -->
<div class="modal fade" id="modalResultado" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalResultadoHeader">
                <h5 class="modal-title" id="modalResultadoTitle">Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalResultadoBody">
                <div class="text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p>Operación realizada correctamente.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Todo el código JavaScript
(function($) {
    $(document).ready(function() {
        let currentDocenteId = null;
        let currentMateriaId = null;
        let currentPeriodoId = null;
        let currentDocente = '';
        let currentMateria = '';
        let currentPeriodo = '';
        let currentSeccion = '';
        let currentCarrera = '';
        
        let accionPendiente = null;
        let notasIdsPendientes = [];
        let esAccionGrupal = false;
        
        // Función para mostrar modal de resultado
        function mostrarModalResultado(titulo, mensaje, tipo, recargar = false) {
            const header = $('#modalResultadoHeader');
            const title = $('#modalResultadoTitle');
            const body = $('#modalResultadoBody');
            
            header.removeClass('bg-success bg-danger bg-warning');
            if (tipo === 'success') {
                header.addClass('bg-success text-white');
            } else if (tipo === 'danger') {
                header.addClass('bg-danger text-white');
            } else {
                header.addClass('bg-warning');
            }
            
            title.text(titulo);
            body.html('<div class="text-center"><i class="fas fa-' + (tipo === 'success' ? 'check-circle' : 'exclamation-circle') + ' fa-3x mb-3"></i><p>' + mensaje + '</p></div>');
            
            $('#modalResultado').modal('show');
            
            if (recargar) {
                $('#modalResultado').one('hidden.bs.modal', function() {
                    location.reload();
                });
            }
        }
        
        // Función para obtener el mensaje predefinido
        function obtenerMensajePredefinido(accion) {
            if (accion === 'aprobar') {
                return `========================================
✅ APROBACIÓN DE NOTAS
========================================

Estimado(a) docente,

Le informamos que las notas que usted registró para la materia ${currentMateria} han sido APROBADAS por el administrador.

✅ Las notas ya están disponibles para que los estudiantes las consulten.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Sistema de Gestión de Notas - UPT Puerto Cabello`;
            } else {
                return `========================================
❌ RECHAZO DE NOTAS
========================================

Estimado(a) docente,

Lamentamos informarle que las notas que usted registró para la materia ${currentMateria} han sido RECHAZADAS por el administrador.

⚠️ Por favor, revise y corrija las observaciones, luego vuelva a enviar las notas.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Sistema de Gestión de Notas - UPT Puerto Cabello`;
            }
        }
        
        // Función para procesar la acción (sin modal de mensaje)
        function procesarAccion(accion, notasIds, esGrupo) {
            // Mostrar loading
            $('#modalResultadoBody').html('<div class="text-center"><div class="spinner-border text-primary"></div><p>Procesando...</p></div>');
            $('#modalResultado').modal('show');
            
            let datos = {
                accion: accion,
                materia_id: currentMateriaId,
                periodo_id: currentPeriodoId,
                mensaje: obtenerMensajePredefinido(accion)
            };
            
            if (esGrupo) {
                datos.accion_grupo = true;
                datos.docente_id = currentDocenteId;
            } else {
                datos.notas_ids = notasIds;
            }
            
            $.ajax({
                url: 'procesar_acciones_notas_admin.php',
                type: 'POST',
                data: datos,
                dataType: 'json',
                success: function(response) {
                    $('#modalResultado').modal('hide');
                    if (response.success) {
                        mostrarModalResultado('Éxito', response.message, 'success', true);
                    } else {
                        mostrarModalResultado('Error', response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    $('#modalResultado').modal('hide');
                    mostrarModalResultado('Error', 'Error al procesar la solicitud. Intente nuevamente.', 'danger');
                }
            });
        }
        
        // Exponer funciones globalmente
        window.aplicarAccion = function(accion) {
            const selected = $('.estudiante-checkbox:checked');
            if (selected.length === 0) {
                alert('Seleccione al menos un estudiante');
                return;
            }
            
            const usuarioIds = selected.map(function() { return $(this).val(); }).get();
            procesarAccion(accion, usuarioIds, false);
        };
        
        window.accionGrupo = function(accion) {
            procesarAccion(accion, [], true);
        };
        
        window.limpiarSeleccion = function() {
            $('.estudiante-checkbox').prop('checked', false);
            $('#selectAllEstudiantes').prop('checked', false);
            actualizarBotones();
        };
        
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
        
        // Cargar detalles
        $('.btn-detalles').off('click').on('click', function() {
            currentDocenteId = $(this).data('docente-id');
            currentMateriaId = $(this).data('materia-id');
            currentPeriodoId = $(this).data('periodo-id');
            currentDocente = $(this).data('docente') || '';
            currentMateria = $(this).data('materia') || '';
            currentPeriodo = $(this).data('periodo') || '';
            currentSeccion = $(this).data('seccion') || '';
            currentCarrera = $(this).data('carrera') || '';
            
            $('#tituloGrupo').text(`${currentDocente} - ${currentMateria} - ${currentPeriodo}`);
            
            // Cargar lista de estudiantes
            $.ajax({
                url: 'ajax_detalles_notas_admin.php',
                type: 'POST',
                data: { 
                    docente_id: currentDocenteId, 
                    materia_id: currentMateriaId, 
                    periodo_id: currentPeriodoId,
                    seccion: 'lista-estudiantes'
                },
                success: function(data) {
                    $('#lista-estudiantes').html(data);
                    // Reasignar eventos
                    $('.accion-individual').off('click').on('click', function() {
                        const idNota = $(this).data('id-nota');
                        const accion = $(this).data('accion');
                        procesarAccion(accion, [idNota], false);
                    });
                    
                    $('#selectAllEstudiantes').off('change').on('change', function() {
                        $('.estudiante-checkbox').prop('checked', this.checked);
                        actualizarBotones();
                    });
                    
                    $('.estudiante-checkbox').off('change').on('change', actualizarBotones);
                    actualizarBotones();
                },
                error: function() {
                    $('#lista-estudiantes').html('<div class="alert alert-danger">Error al cargar estudiantes</div>');
                }
            });
            
            // Cargar resumen y soporte
            $.ajax({
                url: 'ajax_detalles_notas_admin.php',
                type: 'POST',
                data: { docente_id: currentDocenteId, materia_id: currentMateriaId, periodo_id: currentPeriodoId, seccion: 'resumen' },
                success: function(data) { $('#resumen').html(data); }
            });
            
            $.ajax({
                url: 'ajax_detalles_notas_admin.php',
                type: 'POST',
                data: { docente_id: currentDocenteId, materia_id: currentMateriaId, periodo_id: currentPeriodoId, seccion: 'soporte' },
                success: function(data) { $('#soporte').html(data); }
            });
        });
    });
})(jQuery);
</script>

<?php include("includes/footer.php"); ?>