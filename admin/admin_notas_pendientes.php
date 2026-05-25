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

// Obtener grupos de notas pendientes desde notas_trimestres
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
              WHERE nt.estado = 'pendiente'
              GROUP BY nt.id_docente, nt.id_materia, nt.id_periodo
              ORDER BY ultima_fecha DESC";
    
    $result = $db->query($query);
    return $result;
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.btn-detalles').click(function() {
        const docenteId = $(this).data('docente-id');
        const materiaId = $(this).data('materia-id');
        const periodoId = $(this).data('periodo-id');
        const docente = $(this).data('docente');
        const materia = $(this).data('materia');
        const periodo = $(this).data('periodo');
        const seccion = $(this).data('seccion');
        const carrera = $(this).data('carrera');
        
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
    });
});
</script>

<?php include("includes/footer.php"); ?>