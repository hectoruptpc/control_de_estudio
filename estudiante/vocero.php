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

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Panel del Vocero</h1>
        <div>
            <?php if ($estudiante_seleccionado): ?>
                <a href="vocero.php" class="btn btn-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-home"></i> Inicio
            </a>
        </div>
    </div>

    <?php if ($seccion): ?>
        <div class="alert alert-info mb-4">
            <i class="fas fa-users"></i> Sección: <strong><?= htmlspecialchars($seccion['codigo_seccion']) ?></strong>
            <br>
            <small>Como vocero, puedes consultar las notas de tus compañeros de sección haciendo clic en cada estudiante.</small>
        </div>

        <?php if ($estudiante_seleccionado): ?>
            <!-- Detalle del estudiante seleccionado -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate"></i> 
                        <?= htmlspecialchars($estudiante_seleccionado['nombre']) ?>
                    </h5>
                    <a href="vocero.php" class="btn btn-light btn-sm">
                        <i class="fas fa-times"></i> Cerrar
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Cédula:</strong> <?= htmlspecialchars($estudiante_seleccionado['idusuario']) ?></p>
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($estudiante_seleccionado['nombre']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Carrera:</strong> <?= htmlspecialchars($carrera['nombre_carrera']) ?> (<?= htmlspecialchars($carrera['cod_carrera']) ?>)</p>
                            <p><strong>Total de Materias:</strong> <span class="badge badge-primary"><?= $materias_carrera->num_rows ?></span></p>
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
                    <h5><i class="fas fa-book"></i> Plan de Estudios y Notas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th width="100">Trayecto</th>
                                    <th>Materia</th>
                                    <th width="100">Cod Materia</th>
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
                    
                    <!-- Resumen estadístico -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6>Resumen Académico</h6>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $total_materias = $materias_carrera->num_rows;
                                    $promedio_general = $materias_con_notas > 0 ? round($suma_promedios / $materias_con_notas, 1) : 0;
                                    $porcentaje_aprobadas = $materias_con_notas > 0 ? round(($materias_aprobadas / $materias_con_notas) * 100, 1) : 0;
                                    $porcentaje_completado = $total_materias > 0 ? round(($materias_con_notas / $total_materias) * 100, 1) : 0;
                                    ?>
                                    
                                    <div class="stats-container">
                                        <div class="stat-item">
                                            <div class="stat-value h4 text-primary"><?= $total_materias ?></div>
                                            <div class="stat-label">Total Materias</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value h4 <?= $promedio_general >= 12 ? 'text-success' : ($promedio_general > 0 ? 'text-warning' : 'text-secondary') ?>">
                                                <?= $promedio_general > 0 ? $promedio_general : 'N/A' ?>
                                            </div>
                                            <div class="stat-label">Promedio</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value h4 text-success"><?= $materias_aprobadas ?></div>
                                            <div class="stat-label">Aprobadas</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value h4 text-danger"><?= $materias_reprobadas ?></div>
                                            <div class="stat-label">Reprobadas</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <p><strong>Progreso:</strong> 
                                            <span class="badge badge-<?= $porcentaje_completado >= 100 ? 'success' : ($porcentaje_completado >= 50 ? 'info' : 'warning') ?>">
                                                <?= $porcentaje_completado ?>% completado
                                            </span>
                                        </p>
                                        <p><strong>Efectividad:</strong> 
                                            <span class="badge badge-<?= $porcentaje_aprobadas >= 80 ? 'success' : ($porcentaje_aprobadas >= 50 ? 'warning' : 'danger') ?>">
                                                <?= $porcentaje_aprobadas ?>% de aprobación
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6>Progreso de la Carrera</h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($total_materias > 0): 
                                    $materias_por_trayecto = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0];
                                    $materias_carrera->data_seek(0);
                                    while ($materia = $materias_carrera->fetch_assoc()) {
                                        $trayecto = (int)$materia['trayecto'];
                                        if (isset($materias_por_trayecto[$trayecto])) {
                                            $materias_por_trayecto[$trayecto]++;
                                        }
                                    }
                                    
                                    $materias_tsu = $materias_por_trayecto[0] + $materias_por_trayecto[1] + $materias_por_trayecto[2];
                                    $porcentaje_meta_tsu = ($materias_tsu / $total_materias) * 100;
                                    ?>
                                    
                                    <div class="progress mb-3" style="height: 25px; position: relative; background-color: #f8f9fa;">
                                        <div class="progress-bar bg-success" 
                                             style="width: <?= $porcentaje_completado ?>%">
                                            <span class="progress-text"><?= $porcentaje_completado ?>%</span>
                                        </div>
                                        
                                        <?php if ($porcentaje_meta_tsu > 0 && $porcentaje_meta_tsu < 100): ?>
                                        <div style="position: absolute; left: <?= $porcentaje_meta_tsu ?>%; top: -5px; bottom: -5px; width: 3px; background-color: #ff6b00; z-index: 10;"></div>
                                        <div style="position: absolute; left: <?= $porcentaje_meta_tsu ?>%; top: -20px; transform: translateX(-50%);">
                                            <i class="fas fa-flag" style="color: #ff6b00;"></i>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div style="position: absolute; right: 0; top: -20px;">
                                            <i class="fas fa-graduation-cap" style="color: #28a745;"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar bg-success" 
                                             style="width: <?= ($materias_aprobadas / $total_materias) * 100 ?>%">
                                            <span class="progress-text"><?= $materias_aprobadas ?></span>
                                        </div>
                                        <div class="progress-bar bg-danger" 
                                             style="width: <?= ($materias_reprobadas / $total_materias) * 100 ?>%">
                                            <span class="progress-text"><?= $materias_reprobadas ?></span>
                                        </div>
                                        <div class="progress-bar bg-secondary" 
                                             style="width: <?= ($materias_sin_notas / $total_materias) * 100 ?>%">
                                            <span class="progress-text"><?= $materias_sin_notas ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <h6>Distribución por Trayectos:</h6>
                                        <div class="row">
                                            <?php for ($i = 0; $i <= 4; $i++): 
                                                if ($materias_por_trayecto[$i] > 0): ?>
                                                <div class="col-md-2 col-sm-4 col-4 mb-2">
                                                    <small>
                                                        <strong>T<?= $i ?>:</strong> <?= $materias_por_trayecto[$i] ?> mat.
                                                    </small>
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
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Estudiantes de tu Sección</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div class="alert alert-info">No hay estudiantes inscritos en tu sección.</div>
                    <?php else: ?>
                        <div class="table-responsive">
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
                                        // Evitar duplicados (por si un estudiante tiene varias materias)
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
    color: #000 !important;
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

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}
</style>

<?php include("includes/footer.php"); ?>