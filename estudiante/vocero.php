<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel de Vocero";
require_once('../funciones/functions.php');

// acceso básico
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

$uid = intval($_SESSION['user']['id']);
// Verificar el marcador de vocero (consultar DB para asegurarse de estar al día)
$is_vocero = esVoceroUsuario($uid);

if (!$is_vocero) {
    $_SESSION['msg'] = "Acceso denegado: esta sección es solo para voceros";
    header('location: index.php');
    exit();
}

// registro de visita
visita();

// identificar sección del vocero
$seccion = obtenerSeccionEstudiante($db, $uid);
$estudiantes = [];
if ($seccion) {
    $estudiantes = obtenerEstudiantesConNotasSeccion($seccion['id_seccion']);
}

// Obtener estudiante seleccionado para ver detalles
$estudiante_seleccionado = null;
$carrera = null;
$materias_carrera = [];
$notas_estudiante = [];
$info_apto = null;

if (isset($_GET['estudiante_id']) && is_numeric($_GET['estudiante_id'])) {
    $estudiante_id = (int)$_GET['estudiante_id'];
    
    // Verificar que el estudiante pertenece a la misma sección del vocero
    $pertenece_seccion = false;
    $estudiante_data = null;
    
    foreach ($estudiantes as $est) {
        if ($est['id'] == $estudiante_id) {
            $pertenece_seccion = true;
            $estudiante_data = $est;
            break;
        }
    }
    
    if ($pertenece_seccion && $estudiante_data) {
        // Usar la función buscarEstudiantePorCedulaConsulta con la cédula del estudiante
        $estudiante_seleccionado = buscarEstudiantePorCedulaConsulta($estudiante_data['cedula']);
        
        if ($estudiante_seleccionado) {
            // Obtener información de la carrera
            $carrera = obtenerCarreraEstudiante($estudiante_seleccionado['id']);
            
            if ($carrera) {
                // Obtener todas las materias de la carrera
                $materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
                
                // Obtener notas del estudiante (si existen)
                $notas_estudiante = obtenerNotasEstudianteConsulta($estudiante_seleccionado['id']);
                
                // Determinar si es apto para grado
                $info_apto = esAptoParaGradoConsulta($estudiante_seleccionado['id'], $carrera['id_carrera']);
            }
        }
    }
}

include("includes/head.php");
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800 mb-2 mb-sm-0">Panel del Vocero</h1>
        <div class="d-flex">
            <?php if ($estudiante_seleccionado): ?>
                <a href="vocero.php" class="btn btn-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Volver a la lista</span><span class="d-inline d-sm-none">Volver</span>
                </a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-home"></i> <span class="d-none d-sm-inline">Inicio</span>
            </a>
        </div>
    </div>

    <?php if ($seccion): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-users"></i> Sección: <strong><?= htmlspecialchars($seccion['codigo_seccion']) ?></strong>
            <br class="d-block d-sm-none">
            <small class="d-block d-sm-inline mt-1 mt-sm-0">Como vocero, puedes consultar las notas de tus compañeros de sección haciendo clic en cada estudiante.</small>
        </div>

        <?php if ($estudiante_seleccionado): ?>
            <!-- Detalle del estudiante seleccionado -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <h5 class="mb-2 mb-sm-0">
                        <i class="fas fa-user-graduate"></i> 
                        <?= htmlspecialchars($estudiante_seleccionado['nombre']) ?>
                    </h5>
                    <a href="vocero.php" class="btn btn-light btn-sm">
                        <i class="fas fa-times"></i> Cerrar
                    </a>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2 mb-md-0">
                            <p><strong><i class="fas fa-id-card"></i> Cédula:</strong> <?= htmlspecialchars($estudiante_seleccionado['idusuario']) ?></p>
                            <p><strong><i class="fas fa-user"></i> Nombre:</strong> <?= htmlspecialchars($estudiante_seleccionado['nombre']) ?></p>
                        </div>
                        <div class="col-12 col-md-6">
                            <p><strong><i class="fas fa-graduation-cap"></i> Carrera:</strong> <?= htmlspecialchars($carrera['nombre_carrera']) ?> (<?= htmlspecialchars($carrera['cod_carrera']) ?>)</p>
                            <p><strong><i class="fas fa-book"></i> Total Materias:</strong> <span class="badge badge-primary"><?= $materias_carrera->num_rows ?></span></p>
                        </div>
                    </div>
                    
                    <?php if ($info_apto): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert <?= ($info_apto['apto_grado_completo'] || $info_apto['apto_tsu']) ? 'alert-success' : 'alert-warning' ?>">
                                <h6><i class="fas fa-graduation-cap"></i> Evaluación para Grado:</h6>
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                                        <strong>TSU (Trayectos 0-2):</strong><br>
                                        <?= $info_apto['materias_aprobadas_tsu'] ?>/<?= $info_apto['total_materias_tsu'] ?> materias aprobadas<br>
                                        <span class="badge badge-<?= $info_apto['porcentaje_tsu'] >= 90 ? 'success' : 'warning' ?>">
                                            <?= $info_apto['porcentaje_tsu'] ?>% completado
                                        </span>
                                        <?php if ($info_apto['apto_tsu']): ?>
                                            <span class="badge badge-success ml-2">APTO TSU</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12 col-md-6">
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
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Plan de Estudios y Notas</h5>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <!-- Vista para escritorio: tabla -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="100">Trayecto</th>
                                    <th>Materia</th>
                                    <th width="100">Código</th>
                                    <th width="80">Nota</th>
                                    <th width="90">Estado</th>
                                    <th width="120">Periodo</th>
                                    <th width="100">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $materias_aprobadas = 0;
                                $materias_reprobadas = 0;
                                $materias_sin_notas = 0;
                                $suma_promedios = 0;
                                $materias_con_notas = 0;
                                $todas_materias = [];
                                
                                $materias_carrera->data_seek(0);
                                
                                while ($materia = $materias_carrera->fetch_assoc()): 
                                    $todas_materias[] = $materia;
                                    $nota = isset($notas_estudiante[$materia['id_materia']]) ? $notas_estudiante[$materia['id_materia']] : null;
                                    
                                    $numero_trayecto_materia = (int)$materia['trayecto'];
                                    $info_trayecto = obtenerInfoTrayecto($numero_trayecto_materia);
                                    $nombre_trayecto = $info_trayecto['nombre_trayecto'];
                                    
                                    $nota_trayecto = null;
                                    $tiene_nota = false;
                                    
                                    if ($nota) {
                                        $campo_trayecto = 'trayecto_' . $numero_trayecto_materia;
                                        if (isset($nota[$campo_trayecto]) && $nota[$campo_trayecto] !== null) {
                                            $nota_trayecto = (float)$nota[$campo_trayecto];
                                            $tiene_nota = true;
                                        }
                                    }
                                    
                                    $estado = 'Sin notas';
                                    $badge_estado = 'secondary';
                                    
                                    if ($tiene_nota) {
                                        if ($nota_trayecto >= 12) {
                                            $estado = 'Aprobado';
                                            $badge_estado = 'success';
                                            $materias_aprobadas++;
                                        } else {
                                            $estado = 'Reprobado';
                                            $badge_estado = 'danger';
                                            $materias_reprobadas++;
                                        }
                                        $suma_promedios += $nota_trayecto;
                                        $materias_con_notas++;
                                    } else {
                                        $materias_sin_notas++;
                                    }
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($nombre_trayecto) ?></td>
                                        <td><?= htmlspecialchars($materia['nombre_materia']) ?></td>
                                        <td><?= htmlspecialchars($materia['cod_materia']) ?></td>
                                        <td class="text-center">
                                            <?php if ($tiene_nota): ?>
                                                <div class="nota-display">
                                                    <span class="nota-valor <?= $nota_trayecto >= 12 ? 'nota-aprobada' : 'nota-reprobada' ?>">
                                                        <?= $nota_trayecto ?>
                                                    </span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-<?= $badge_estado ?>">
                                                <?= $estado ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($nota): ?>
                                                <?= htmlspecialchars($nota['nombre_periodo']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($nota && $nota['fecha_registro']): ?>
                                                <?= date('d/m/Y', strtotime($nota['fecha_registro'])) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Vista para móviles: tarjetas -->
                    <div class="d-block d-md-none">
                        <?php 
                        $materias_carrera->data_seek(0);
                        while ($materia = $materias_carrera->fetch_assoc()): 
                            $nota = isset($notas_estudiante[$materia['id_materia']]) ? $notas_estudiante[$materia['id_materia']] : null;
                            $numero_trayecto_materia = (int)$materia['trayecto'];
                            $info_trayecto = obtenerInfoTrayecto($numero_trayecto_materia);
                            $nombre_trayecto = $info_trayecto['nombre_trayecto'];
                            
                            $nota_trayecto = null;
                            $tiene_nota = false;
                            
                            if ($nota) {
                                $campo_trayecto = 'trayecto_' . $numero_trayecto_materia;
                                if (isset($nota[$campo_trayecto]) && $nota[$campo_trayecto] !== null) {
                                    $nota_trayecto = (float)$nota[$campo_trayecto];
                                    $tiene_nota = true;
                                }
                            }
                            
                            $estado = 'Sin notas';
                            $badge_estado = 'secondary';
                            
                            if ($tiene_nota) {
                                if ($nota_trayecto >= 12) {
                                    $estado = 'Aprobado';
                                    $badge_estado = 'success';
                                } else {
                                    $estado = 'Reprobado';
                                    $badge_estado = 'danger';
                                }
                            }
                        ?>
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong class="text-primary"><?= htmlspecialchars($materia['cod_materia']) ?></strong>
                                        <span class="badge badge-<?= $badge_estado ?>"><?= $estado ?></span>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3"><?= htmlspecialchars($materia['nombre_materia']) ?></h6>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5 text-muted">
                                            <i class="fas fa-layer-group"></i> Trayecto:
                                        </div>
                                        <div class="col-7">
                                            <?= htmlspecialchars($nombre_trayecto) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5 text-muted">
                                            <i class="fas fa-star"></i> Nota:
                                        </div>
                                        <div class="col-7">
                                            <?php if ($tiene_nota): ?>
                                                <span class="badge badge-<?= $nota_trayecto >= 12 ? 'success' : 'danger' ?>">
                                                    <?= $nota_trayecto ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Sin nota</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5 text-muted">
                                            <i class="fas fa-calendar-alt"></i> Periodo:
                                        </div>
                                        <div class="col-7">
                                            <?= $nota ? htmlspecialchars($nota['nombre_periodo']) : '-' ?>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5 text-muted">
                                            <i class="fas fa-calendar-check"></i> Fecha:
                                        </div>
                                        <div class="col-7">
                                            <?= $nota && $nota['fecha_registro'] ? date('d/m/Y', strtotime($nota['fecha_registro'])) : '-' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Resumen estadístico -->
                    <div class="row mt-4">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Resumen Académico</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $total_materias = $materias_carrera->num_rows;
                                    $promedio_general = $materias_con_notas > 0 ? round($suma_promedios / $materias_con_notas, 1) : 0;
                                    $porcentaje_aprobadas = $materias_con_notas > 0 ? round(($materias_aprobadas / $materias_con_notas) * 100, 1) : 0;
                                    $porcentaje_completado = $total_materias > 0 ? round(($materias_con_notas / $total_materias) * 100, 1) : 0;
                                    ?>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="h4 text-primary"><?= $total_materias ?></div>
                                            <small class="text-muted">Total</small>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="h4 text-success"><?= $materias_aprobadas ?></div>
                                            <small class="text-muted">Aprobadas</small>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="h4 text-danger"><?= $materias_reprobadas ?></div>
                                            <small class="text-muted">Reprobadas</small>
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <div class="h4 text-warning"><?= $materias_sin_notas ?></div>
                                            <small class="text-muted">Pendientes</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <p><strong>Promedio:</strong> 
                                            <span class="badge badge-<?= $promedio_general >= 12 ? 'success' : ($promedio_general > 0 ? 'warning' : 'secondary') ?>">
                                                <?= $promedio_general > 0 ? $promedio_general : 'N/A' ?>
                                            </span>
                                        </p>
                                        <p><strong>Progreso:</strong> 
                                            <span class="badge badge-info"><?= $porcentaje_completado ?>% completado</span>
                                        </p>
                                        <p><strong>Efectividad:</strong> 
                                            <span class="badge badge-<?= $porcentaje_aprobadas >= 80 ? 'success' : ($porcentaje_aprobadas >= 50 ? 'warning' : 'danger') ?>">
                                                <?= $porcentaje_aprobadas ?>%
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Progreso de la Carrera</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($total_materias > 0): 
                                    $materias_por_trayecto = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0];
                                    foreach ($todas_materias as $materia) {
                                        $trayecto = (int)$materia['trayecto'];
                                        if (isset($materias_por_trayecto[$trayecto])) {
                                            $materias_por_trayecto[$trayecto]++;
                                        }
                                    }
                                    
                                    $materias_tsu = $materias_por_trayecto[0] + $materias_por_trayecto[1] + $materias_por_trayecto[2];
                                    ?>
                                    
                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar bg-success" style="width: <?= $porcentaje_completado ?>%">
                                            <?= $porcentaje_completado ?>%
                                        </div>
                                    </div>
                                    
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: <?= ($materias_aprobadas / $total_materias) * 100 ?>%">
                                            A:<?= $materias_aprobadas ?>
                                        </div>
                                        <div class="progress-bar bg-danger" style="width: <?= ($materias_reprobadas / $total_materias) * 100 ?>%">
                                            R:<?= $materias_reprobadas ?>
                                        </div>
                                        <div class="progress-bar bg-secondary" style="width: <?= ($materias_sin_notas / $total_materias) * 100 ?>%">
                                            P:<?= $materias_sin_notas ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">Distribución por Trayectos:</small>
                                        <div class="row mt-1">
                                            <?php for ($i = 0; $i <= 4; $i++): 
                                                if ($materias_por_trayecto[$i] > 0): ?>
                                                <div class="col-4 col-sm-3 col-md-2 mb-1">
                                                    <small><strong>T<?= $i ?>:</strong> <?= $materias_por_trayecto[$i] ?></small>
                                                </div>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Lista de estudiantes de la sección -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Estudiantes de tu Sección</h5>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <?php if (empty($estudiantes)): ?>
                        <div class="alert alert-info">No hay estudiantes inscritos en tu sección.</div>
                    <?php else: ?>
                        <!-- Vista para escritorio: tabla -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Estudiante</th>
                                        <th>Cédula</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $contador = 1;
                                    $estudiantes_vistos = [];
                                    foreach ($estudiantes as $est):
                                        if (in_array($est['id'], $estudiantes_vistos)) continue;
                                        $estudiantes_vistos[] = $est['id'];
                                    ?>
                                        <tr>
                                            <td><?= $contador++ ?></td>
                                            <td><?= htmlspecialchars($est['nombre']) ?></td>
                                            <td><?= htmlspecialchars($est['cedula']) ?></td>
                                            <td>
                                                <a href="vocero.php?estudiante_id=<?= $est['id'] ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver Notas
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Vista para móviles: tarjetas -->
                        <div class="d-block d-md-none">
                            <?php 
                            $contador = 1;
                            $estudiantes_vistos = [];
                            foreach ($estudiantes as $est):
                                if (in_array($est['id'], $estudiantes_vistos)) continue;
                                $estudiantes_vistos[] = $est['id'];
                            ?>
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-primary"><?= $contador++ ?>. <?= htmlspecialchars($est['nombre']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card"></i> <?= htmlspecialchars($est['cedula']) ?>
                                                </small>
                                            </div>
                                            <a href="vocero.php?estudiante_id=<?= $est['id'] ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="alert alert-secondary mt-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Instrucción:</strong> Haz clic en "Ver Notas" para consultar el detalle completo de las calificaciones de cada estudiante.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-warning">No se pudo determinar tu sección. Contacta al administrador.</div>
    <?php endif; ?>
</div>

<style>
/* Estilos responsivos */
@media (max-width: 767.98px) {
    .h3 {
        font-size: 1.4rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .alert-info small {
        font-size: 0.8rem;
    }
    
    /* Estilos para tarjetas de materias */
    .d-block.d-md-none .card {
        border-radius: 8px;
    }
    
    .d-block.d-md-none .card-header {
        background-color: #f8f9fc;
    }
    
    .d-block.d-md-none .card-title {
        font-size: 0.9rem;
        line-height: 1.3;
    }
    
    .d-block.d-md-none .row {
        margin-bottom: 0.5rem;
    }
    
    .d-block.d-md-none .col-5, 
    .d-block.d-md-none .col-7 {
        font-size: 0.85rem;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }
    
    /* Estilos para lista de estudiantes móvil */
    .d-block.d-md-none .card-body {
        padding: 0.75rem;
    }
    
    /* Ajustes de progreso */
    .progress-text {
        font-size: 0.7rem;
    }
    
    .h4 {
        font-size: 1.2rem;
    }
}

/* Ajustes para tablets */
@media (min-width: 768px) and (max-width: 991.98px) {
    .table th, .table td {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
}

/* Estilos generales */
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
    color: #000000 !important;
    font-weight: 900;
}

.nota-aprobada {
    background: #90EE90;
    border: 1px solid #28a745;
}

.nota-reprobada {
    background: #FFB6C1;
    border: 1px solid #dc3545;
}

.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

/* Animación para tarjetas móviles */
@media (max-width: 767.98px) {
    .d-block.d-md-none .card:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}
</style>

<?php include("includes/footer.php"); ?>