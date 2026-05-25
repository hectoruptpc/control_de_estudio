<?php
require_once('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('consultar_notas');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Consulta de Notas por Cédula";
include("includes/head.php");

// Procesar búsqueda
$estudiante = null;
$carrera = null;
$materias_carrera = [];
$notas_estudiante = [];
$mensaje_error = '';
$info_apto = null;

// Aceptar búsqueda tanto por POST como por GET
$cedula = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'])) {
    $cedula = trim($_POST['cedula']);
} elseif (isset($_GET['cedula']) && !empty($_GET['cedula'])) {
    $cedula = trim($_GET['cedula']);
}

if (!empty($cedula)) {
    $estudiante = buscarEstudiantePorCedulaConsulta($cedula);
    
    if ($estudiante) {
        // Obtener información de la carrera
        $carrera = obtenerCarreraEstudiante($estudiante['id']);
        
        if ($carrera) {
            // Obtener todas las materias de la carrera
            $materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
            
            // Obtener notas del estudiante (incluyendo trimestres)
            $notas_estudiante = obtenerNotasEstudianteConTrimestres($estudiante['id']);
            
            // Determinar si es apto para grado
            $info_apto = esAptoParaGradoConsulta($estudiante['id'], $carrera['id_carrera']);
        }
    } else {
        $mensaje_error = "No se encontró ningún estudiante con la cédula: " . htmlspecialchars($cedula);
    }
} else if (isset($_GET['cedula']) && empty($_GET['cedula'])) {
    $mensaje_error = "Por favor, ingrese una cédula válida para buscar.";
}
?>

<div class="container-fluid">
    <h2 class="my-4">Consulta de Notas por Cédula</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Buscar Estudiante</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <label for="cedula" class="mr-2">Cédula del Estudiante:</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" 
                           placeholder="Ej: V12345678" value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : (isset($_GET['cedula']) ? htmlspecialchars($_GET['cedula']) : '') ?>" required>
                </div>
                <button type="submit" class="btn btn-success mb-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
            
            <?php if (!empty($mensaje_error)): ?>
                <div class="alert alert-danger mt-3"><?= $mensaje_error ?></div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($estudiante && $carrera): ?>
<div class="card mb-4">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Información del Estudiante</h5>
        <div class="btn-group" role="group">
            <a href="historial_desglozado_tsu.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
               class="btn btn-warning btn-sm mr-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Historial TSU
            </a>
            <a href="historial_desglozado_ingenieria.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
               class="btn btn-info btn-sm mr-2" target="_blank">
                <i class="fas fa-file-pdf"></i> Historial Ingeniería
            </a>
            <a href="generar_reporte_consulta.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
               class="btn btn-danger btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> Historial Completo
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Cédula:</strong> <?= htmlspecialchars($estudiante['idusuario']) ?></p>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($estudiante['nombre']) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Carrera:</strong> <?= htmlspecialchars($carrera['nombre_carrera']) ?> (<?= htmlspecialchars($carrera['cod_carrera']) ?>)</p>
                <p><strong>Total de Materias:</strong> <span class="badge badge-primary"><?= $materias_carrera->num_rows ?></span></p>
                <?php if ($info_apto): ?>
                <p><strong>Estado para Grado:</strong> 
                    <?= obtenerBadgeEstadoConsulta($info_apto) ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($info_apto): ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert <?= ($info_apto['apto_grado_completo'] || $info_apto['apto_tsu']) ? 'alert-success' : 'alert-warning' ?>">
                    <h6><i class="fas fa-graduation-cap"></i> Evaluación para Grado:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>TSU (Trayectos 0-2):</strong><br>
                            <?= $info_apto['materias_aprobadas_tsu'] ?>/<?= $info_apto['total_materias_tsu'] ?> materias aprobadas<br>
                            <span class="badge badge-<?= $info_apto['porcentaje_tsu'] >= 90 ? 'success' : 'warning' ?>">
                                <?= $info_apto['porcentaje_tsu'] ?>% completado
                            </span>
                            <?php if ($info_apto['apto_tsu']): ?>
                                <span class="badge badge-success ml-2">APTO TSU</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Grado Completo:</strong><br>
                            <?= $info_apto['materias_aprobadas_completo'] ?>/<?= $info_apto['total_materias_carrera'] ?> materias aprobadas<br>
                            <span class="badge badge-<?= $info_apto['porcentaje_completo'] >= 100 ? 'success' : 'info' ?>">
                                <?= $info_apto['porcentaje_completo'] ?>% completado
                            </span>
                            <?php if ($info_apto['apto_grado_completo']): ?>
                                <span class="badge badge-success ml-2">APTO GRADO COMPLETO</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($materias_carrera && $materias_carrera->num_rows > 0): ?>
<div class="card">
    <div class="card-header bg-success text-white">
        <h5>Plan de Estudios y Notas por Trimestre</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Trayecto</th>
                        <th>Materia</th>
                        <th>Código</th>
                        <th class="text-center">Trimestre 1</th>
                        <th class="text-center">Trimestre 2</th>
                        <th class="text-center">Trimestre 3</th>
                        <th class="text-center">Nota Final</th>
                        <th class="text-center">Estado</th>
                        <th>Periodo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $materias_aprobadas = 0;
                    $materias_reprobadas = 0;
                    $materias_sin_notas = 0;
                    $suma_promedios = 0;
                    $materias_con_notas = 0;
                    
                    if ($materias_carrera) {
                        $materias_carrera->data_seek(0);
                    }
                    
                    while ($materia = $materias_carrera->fetch_assoc()): 
                        $nota = isset($notas_estudiante[$materia['id_materia']]) ? $notas_estudiante[$materia['id_materia']] : null;
                        
                        $numero_trayecto_materia = (int)$materia['trayecto'];
                        $info_trayecto = obtenerInfoTrayecto($numero_trayecto_materia);
                        $nombre_trayecto = $info_trayecto['nombre_trayecto'];
                        
                        $t1 = $nota['trimestre_1'] ?? null;
                        $t2 = $nota['trimestre_2'] ?? null;
                        $t3 = $nota['trimestre_3'] ?? null;
                        $nota_final = $nota['nota_final'] ?? null;
                        
                        if ($nota_final !== null) {
                            if ($nota_final >= 12) {
                                $estado = 'Aprobado';
                                $badge_estado = 'success';
                                $materias_aprobadas++;
                            } else {
                                $estado = 'Reprobado';
                                $badge_estado = 'danger';
                                $materias_reprobadas++;
                            }
                            $suma_promedios += $nota_final;
                            $materias_con_notas++;
                        } else {
                            $estado = 'Sin notas';
                            $badge_estado = 'secondary';
                            $materias_sin_notas++;
                        }
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($nombre_trayecto) ?></td>
                            <td><strong><?= htmlspecialchars($materia['nombre_materia']) ?></strong></td>
                            <td><?= htmlspecialchars($materia['cod_materia']) ?></td>
                            
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
                                <span class="badge badge-<?= $badge_estado ?>"><?= $estado ?></span>
                             </div>
                            
                            <td><?= htmlspecialchars($nota['nombre_periodo'] ?? '-') ?></td>
                            <td class="text-center"><?= !empty($nota['fecha_registro']) ? date('d/m/Y', strtotime($nota['fecha_registro'])) : '-' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6>Resumen Académico</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        $total_materias = $materias_carrera ? $materias_carrera->num_rows : 0;
                        $promedio_general = $materias_con_notas > 0 ? round($suma_promedios / $materias_con_notas, 1) : 0;
                        $porcentaje_aprobadas = $materias_con_notas > 0 ? round(($materias_aprobadas / $materias_con_notas) * 100, 1) : 0;
                        $porcentaje_completado = $total_materias > 0 ? round(($materias_con_notas / $total_materias) * 100, 1) : 0;
                        ?>
                        
                        <div class="row">
                            <div class="col-6 text-center mb-3">
                                <h3 class="text-primary"><?= $total_materias ?></h3>
                                <small>Total Materias</small>
                            </div>
                            <div class="col-6 text-center mb-3">
                                <h3 class="<?= $promedio_general >= 12 ? 'text-success' : ($promedio_general > 0 ? 'text-warning' : 'text-secondary') ?>">
                                    <?= $promedio_general > 0 ? $promedio_general : 'N/A' ?>
                                </h3>
                                <small>Promedio General</small>
                            </div>
                            <div class="col-6 text-center">
                                <h3 class="text-success"><?= $materias_aprobadas ?></h3>
                                <small>Aprobadas</small>
                            </div>
                            <div class="col-6 text-center">
                                <h3 class="text-danger"><?= $materias_reprobadas ?></h3>
                                <small>Reprobadas</small>
                            </div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 25px;">
                            <div class="progress-bar bg-success" style="width: <?= $porcentaje_completado ?>%">
                                <?= $porcentaje_completado ?>%
                            </div>
                        </div>
                        <p class="mt-2 text-center"><small>Progreso de la carrera</small></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6>Leyenda</h6>
                    </div>
                    <div class="card-body">
                        <p><span class="badge bg-success p-2">≥ 12</span> = Aprobado</p>
                        <p><span class="badge bg-danger p-2">&lt; 12</span> = Reprobado</p>
                        <p><span class="badge bg-secondary p-2">-</span> = Sin nota registrada</p>
                        <hr>
                        <p class="mb-0"><i class="fas fa-chart-line"></i> <strong>Nota Final:</strong> Promedio de los 3 trimestres</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
    <div class="alert alert-warning">
        No se encontraron materias para la carrera: <?= htmlspecialchars($carrera['nombre_carrera']) ?>
    </div>
<?php endif; ?>

<?php endif; ?>
</div>

<style>
.bg-success {
    background-color: #28a745 !important;
    color: white !important;
}
.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}
.bg-secondary {
    background-color: #6c757d !important;
    color: white !important;
}
.table td, .table th {
    vertical-align: middle;
}
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.75rem;
    }
    .badge {
        font-size: 0.7rem;
    }
}
</style>

<?php include("includes/footer.php"); ?>