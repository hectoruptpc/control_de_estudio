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

// Obtener grupos de notas pendientes agrupados por docente/materia/periodo
function obtenerGruposNotasPendientes() {
    global $db;
    
    $query = "SELECT np.id_docente, np.id_materia, np.id_periodo,
                     ud.nombre as nombre_docente, m.nombre_materia, 
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera,
                     COUNT(np.id) as total_notas, MAX(np.fecha_envio) as ultima_fecha
              FROM notas_pendientes np
              INNER JOIN users ud ON np.id_docente = ud.id
              INNER JOIN materias m ON np.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON np.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON np.id_docente = ds.id_usuario 
                                           AND np.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              WHERE np.estado = 'pendiente'
              GROUP BY np.id_docente, np.id_materia, np.id_periodo, s.codigo_seccion, c.nombre_carrera
              ORDER BY ultima_fecha DESC";
    
    $result = $db->query($query);
    return $result;
}

// Procesar aprobación/rechazo - versión simplificada que redirige a procesar_acciones.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && isset($_POST['notas_ids'])) {
        // Esta lógica ahora se maneja en procesar_acciones.php
        // Solo redirigimos si es una acción directa del formulario principal
        $_SESSION['msg'] = "Procesando acción...";
        // La lógica detallada está en procesar_acciones.php
    }
}

$grupos_notas = obtenerGruposNotasPendientes();
?>

<div class="container-fluid">
    <h2 class="my-4">Administrar Notas Pendientes</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success"><?= $_SESSION['msg'] ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Cargas de Notas Pendientes por Docente</h5>
        </div>
        <div class="card-body">
            <?php if ($grupos_notas->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Docente</th>
                                <th>Materia</th>
                                <th>Periodo</th>
                                <th>Sección</th>
                                <th>Carrera</th>
                                <th># Notas</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($grupo = $grupos_notas->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nombre_docente']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_materia']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_periodo']) ?></td>
                                    <td><?= htmlspecialchars($grupo['codigo_seccion']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_carrera']) ?></td>
                                    <td><span class="badge badge-info"><?= $grupo['total_notas'] ?></span></td>
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
                                    </td>
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
                    <!-- Sidebar de navegación -->
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
                    
                    <!-- Contenido de las pestañas -->
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
                            <div class="tab-pane fade" id="acciones-grupo">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando acciones grupales...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Cargar detalles del grupo via AJAX
    $('.btn-detalles').click(function() {
        const docenteId = $(this).data('docente-id');
        const materiaId = $(this).data('materia-id');
        const periodoId = $(this).data('periodo-id');
        const docente = $(this).data('docente');
        const materia = $(this).data('materia');
        const periodo = $(this).data('periodo');
        const seccion = $(this).data('seccion');
        const carrera = $(this).data('carrera');
        
        // Actualizar título del modal
        $('#tituloGrupo').text(`${docente} - ${materia} - ${periodo}`);
        
        // Cargar lista de estudiantes
        $.ajax({
            url: 'ajax_detalles_notas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'lista-estudiantes'
            },
            success: function(data) {
                $('#lista-estudiantes').html(data);
            }
        });
        
        // Cargar resumen
        $.ajax({
            url: 'ajax_detalles_notas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'resumen'
            },
            success: function(data) {
                $('#resumen').html(data);
            }
        });
        
        // Cargar soporte
        $.ajax({
            url: 'ajax_detalles_notas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'soporte'
            },
            success: function(data) {
                $('#soporte').html(data);
            }
        });
        
        // Cargar acciones grupales
        $.ajax({
            url: 'ajax_detalles_notas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'acciones-grupo'
            },
            success: function(data) {
                $('#acciones-grupo').html(data);
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>