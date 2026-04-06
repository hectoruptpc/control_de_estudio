<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('../funciones/functions.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Verificar que el usuario es un estudiante
if (!isset($_SESSION['user']) || $_SESSION['user']['estudiante'] != 1) {
    header('location: ../index.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Mi Historial de Notas";
include("includes/head.php");

// Función para obtener la carrera del estudiante
if (!function_exists('obtenerCarreraEstudiante')) {
function obtenerCarreraEstudiante($estudiante_id) {
    global $db;
    
    $query = "SELECT c.id_carrera, c.nombre_carrera, c.cod_carrera 
              FROM users u
              INNER JOIN carreras c ON u.carrera = c.id_carrera
              WHERE u.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
}

// Función para obtener todas las materias de la carrera
if (!function_exists('obtenerMateriasCarrera')) {
function obtenerMateriasCarrera($carrera_id) {
    global $db;
    
    $query = "SELECT m.id_materia, m.nombre_materia, m.cod_materia, m.trayecto
              FROM carrera_materia cm
              INNER JOIN materias m ON cm.id_materia = m.id_materia
              WHERE cm.id_carrera = ?
              ORDER BY m.trayecto, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $carrera_id);
    $stmt->execute();
    return $stmt->get_result();
}
}

// Función para obtener información del trayecto desde la tabla trayectos
if (!function_exists('obtenerInfoTrayecto')) {
function obtenerInfoTrayecto($numero_trayecto) {
    global $db;
    
    $query = "SELECT id_trayecto, numero_trayecto, nombre_trayecto 
              FROM trayectos 
              WHERE numero_trayecto = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $numero_trayecto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Si no encuentra el trayecto, crear uno basado en el número
    $nombres_trayectos = [
        0 => 'Trayecto Inicial',
        1 => 'Trayecto 1',
        2 => 'Trayecto 2', 
        3 => 'Trayecto 3',
        4 => 'Trayecto 4'
    ];
    
    return [
        'id_trayecto' => $numero_trayecto + 1,
        'numero_trayecto' => $numero_trayecto,
        'nombre_trayecto' => isset($nombres_trayectos[$numero_trayecto]) ? $nombres_trayectos[$numero_trayecto] : 'Trayecto ' . $numero_trayecto
    ];
}
}

// Función para obtener las notas definitivas del estudiante
if (!function_exists('obtenerNotasEstudianteConsulta')) {
function obtenerNotasEstudianteConsulta($estudiante_id) {
    global $db;
    
    $query = "SELECT nd.*, 
                     m.id_materia, m.nombre_materia, m.cod_materia, m.trayecto,
                     pa.nombre_periodo,
                     ud.nombre as nombre_docente,
                     ua.nombre as nombre_admin
              FROM notas_definitivas nd
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              LEFT JOIN users ud ON nd.id_docente = ud.id
              LEFT JOIN users ua ON nd.id_admin_aprobador = ua.id
              WHERE nd.id_usuario = ?
              ORDER BY pa.nombre_periodo, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    
    // Convertir to array asociativo con id_materia como clave
    $result = $stmt->get_result();
    $notas = [];
    while ($row = $result->fetch_assoc()) {
        $notas[$row['id_materia']] = $row;
    }
    
    return $notas;
}
}

// Función para verificar si tiene notas en trayectos específicos
if (!function_exists('tieneNotasEnTrayectos')) {
function tieneNotasEnTrayectos($materias_carrera, $notas_estudiante, $trayectos) {
    $tiene_notas = false;
    $materias_carrera->data_seek(0); // Reiniciar puntero
    
    while ($materia = $materias_carrera->fetch_assoc()) {
        $trayecto = (int)$materia['trayecto'];
        
        // Verificar si la materia pertenece a los trayectos solicitados
        if (in_array($trayecto, $trayectos)) {
            $nota = isset($notas_estudiante[$materia['id_materia']]) ? $notas_estudiante[$materia['id_materia']] : null;
            
            if ($nota) {
                $campo_trayecto = 'trayecto_' . $trayecto;
                if (isset($nota[$campo_trayecto]) && $nota[$campo_trayecto] !== null) {
                    $nota_trayecto = (float)$nota[$campo_trayecto];
                    if ($nota_trayecto >= 12) { // Solo contar notas aprobadas
                        $tiene_notas = true;
                        break;
                    }
                }
            }
        }
    }
    
    $materias_carrera->data_seek(0); // Reiniciar puntero de nuevo
    return $tiene_notas;
}
}

// Obtener información del estudiante actual
$estudiante = $_SESSION['user'];
$carrera = null;
$materias_carrera = [];
$notas_estudiante = [];

if ($estudiante) {
    // Obtener información de la carrera
    $carrera = obtenerCarreraEstudiante($estudiante['id']);
    
    if ($carrera) {
        // Obtener todas las materias de la carrera
        $materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
        
        // Obtener notas del estudiante (si existen)
        $notas_estudiante = obtenerNotasEstudianteConsulta($estudiante['id']);
    }
}

// Verificar permisos para cada tipo de reporte
$puede_ver_tsu = false;
$puede_ver_ingenieria = false;
$puede_ver_completo = false;

if ($estudiante && $carrera && $materias_carrera) {
    // TSU: Debe tener notas aprobadas en trayectos 0,1,2
    $puede_ver_tsu = tieneNotasEnTrayectos($materias_carrera, $notas_estudiante, [0, 1, 2]);
    
    // Ingeniería: Debe tener notas aprobadas en trayectos 3,4
    $puede_ver_ingenieria = tieneNotasEnTrayectos($materias_carrera, $notas_estudiante, [3, 4]);
    
    // Completo: Debe tener al menos una nota aprobada en cualquier trayecto
    $puede_ver_completo = tieneNotasEnTrayectos($materias_carrera, $notas_estudiante, [0, 1, 2, 3, 4]);
}
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <h2 class="my-4 h2-sm">Mi Historial de Notas</h2>
    
    <?php if ($estudiante && $carrera): ?>
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <h5 class="mb-2 mb-sm-0">Información del Estudiante</h5>
            
            <!-- Botones de reportes condicionales - Responsive -->
            <?php if ($puede_ver_tsu || $puede_ver_ingenieria || $puede_ver_completo): ?>
            <div class="btn-group btn-group-sm flex-wrap justify-content-center" role="group">
                <?php if ($puede_ver_tsu): ?>
                <a href="../admin/historial_desglozado_tsu.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
                   class="btn btn-warning mb-1 mb-sm-0" target="_blank">
                    <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Historial TSU</span><span class="d-inline d-sm-none">TSU</span>
                </a>
                <?php endif; ?>
                
                <?php if ($puede_ver_ingenieria): ?>
                <a href="../admin/historial_desglozado_ingenieria.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
                   class="btn btn-info mb-1 mb-sm-0" target="_blank">
                    <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Historial Ingeniería</span><span class="d-inline d-sm-none">Ing.</span>
                </a>
                <?php endif; ?>
                
                <?php if ($puede_ver_completo): ?>
                <a href="../admin/generar_reporte_consulta.php?estudiante_id=<?= $estudiante['id'] ?>&cedula=<?= urlencode($estudiante['idusuario']) ?>&nombre=<?= urlencode($estudiante['nombre']) ?>&carrera=<?= urlencode($carrera['nombre_carrera']) ?>" 
                   class="btn btn-danger mb-1 mb-sm-0" target="_blank">
                    <i class="fas fa-file-pdf"></i> <span class="d-none d-sm-inline">Historial Completo</span><span class="d-inline d-sm-none">Completo</span>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body p-2 p-sm-3">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <p><strong><i class="fas fa-id-card"></i> Cédula:</strong> <?= htmlspecialchars($estudiante['idusuario']) ?></p>
                    <p><strong><i class="fas fa-user"></i> Nombre:</strong> <?= htmlspecialchars($estudiante['nombre']) ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <p><strong><i class="fas fa-graduation-cap"></i> Carrera:</strong> <?= htmlspecialchars($carrera['nombre_carrera']) ?> (<?= htmlspecialchars($carrera['cod_carrera']) ?>)</p>
                    <p><strong><i class="fas fa-book"></i> Total de Materias:</strong> <span class="badge badge-primary"><?= $materias_carrera->num_rows ?></span></p>
                </div>
            </div>
            
            <!-- Mensaje informativo sobre disponibilidad de reportes -->
            <?php if (!$puede_ver_tsu && !$puede_ver_ingenieria && !$puede_ver_completo): ?>
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle"></i> 
                Aún no tienes notas aprobadas para generar reportes. Los reportes estarán disponibles cuando tengas al menos una materia aprobada.
            </div>
            <?php else: ?>
            <div class="alert alert-light mt-3">
                <i class="fas fa-file-pdf"></i> 
                <strong>Reportes disponibles:</strong>
                <?php if ($puede_ver_tsu): ?> 
                    <span class="badge badge-warning ml-2">TSU</span>
                <?php endif; ?>
                <?php if ($puede_ver_ingenieria): ?> 
                    <span class="badge badge-info ml-2">Ingeniería</span>
                <?php endif; ?>
                <?php if ($puede_ver_completo): ?> 
                    <span class="badge badge-danger ml-2">Completo</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($materias_carrera->num_rows > 0): ?>
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Plan de Estudios y Notas</h5>
        </div>
        <div class="card-body p-2 p-sm-3">
            <!-- Vista para escritorio: tabla completa -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Trayecto</th>
                            <th>Materia</th>
                            <th>Código</th>
                            <th>Nota</th>
                            <th>Estado</th>
                            <th>Periodo</th>
                            <th>Fecha</th>
                            <th>Aprobado por</th>
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
                                        <span class="badge badge-<?= $nota_trayecto >= 12 ? 'success' : 'danger' ?>">
                                            <?= $nota_trayecto ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-<?= $badge_estado ?>">
                                        <?= $estado ?>
                                    </span>
                                </td>
                                <td><?= $nota ? htmlspecialchars($nota['nombre_periodo']) : '-' ?></td>
                                <td><?= $nota && $nota['fecha_registro'] ? date('d/m/Y', strtotime($nota['fecha_registro'])) : '-' ?></td>
                                <td><?= $nota && !empty($nota['nombre_admin']) ? htmlspecialchars($nota['nombre_admin']) : '-' ?></td>
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
                            
                            <div class="row mb-2">
                                <div class="col-5 text-muted">
                                    <i class="fas fa-user-check"></i> Aprobado por:
                                </div>
                                <div class="col-7">
                                    <?= $nota && !empty($nota['nombre_admin']) ? htmlspecialchars($nota['nombre_admin']) : '-' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Resumen estadístico - Responsive -->
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
                                    <small class="text-muted">Total Materias</small>
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
                                <p><strong>Promedio General:</strong> 
                                    <span class="badge badge-<?= $promedio_general >= 12 ? 'success' : ($promedio_general > 0 ? 'warning' : 'secondary') ?>">
                                        <?= $promedio_general > 0 ? $promedio_general : 'N/A' ?>
                                    </span>
                                </p>
                                <p><strong>Progreso:</strong> 
                                    <span class="badge badge-info"><?= $porcentaje_completado ?>% completado</span>
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
                            $porcentaje_meta_tsu = ($materias_tsu / $total_materias) * 100;
                            ?>
                            
                            <!-- Barra de progreso principal -->
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-success" style="width: <?= $porcentaje_completado ?>%">
                                    <?= $porcentaje_completado ?>%
                                </div>
                            </div>
                            
                            <!-- Barra de progreso por estados -->
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: <?= ($materias_aprobadas / $total_materias) * 100 ?>%">
                                    Aprob: <?= $materias_aprobadas ?>
                                </div>
                                <div class="progress-bar bg-danger" style="width: <?= ($materias_reprobadas / $total_materias) * 100 ?>%">
                                    Rep: <?= $materias_reprobadas ?>
                                </div>
                                <div class="progress-bar bg-secondary" style="width: <?= ($materias_sin_notas / $total_materias) * 100 ?>%">
                                    Pend: <?= $materias_sin_notas ?>
                                </div>
                            </div>
                            
                            <!-- Distribución por trayectos -->
                            <div class="mt-3">
                                <small class="text-muted">Distribución por Trayectos:</small>
                                <div class="row mt-1">
                                    <?php for ($i = 0; $i <= 4; $i++): 
                                        if ($materias_por_trayecto[$i] > 0): ?>
                                        <div class="col-3 col-md-2 mb-1">
                                            <small>
                                                <strong>T<?= $i ?>:</strong> <?= $materias_por_trayecto[$i] ?>
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
    <?php else: ?>
        <div class="alert alert-warning">
            No se encontraron materias para la carrera: <?= htmlspecialchars($carrera['nombre_carrera']) ?>
        </div>
    <?php endif; ?>
    
    <?php else: ?>
        <div class="alert alert-danger">
            No se pudo cargar la información del estudiante. Por favor, contacte con administración.
        </div>
    <?php endif; ?>
</div>

<style>
/* Estilos responsivos */
@media (max-width: 767.98px) {
    .h2-sm {
        font-size: 1.4rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin: 0;
    }
    
    /* Mejoras para tarjetas en móviles */
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
    
    /* Ajustes para el resumen */
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
.card {
    border-radius: 0.5rem;
    overflow: hidden;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

/* Animación suave para tarjetas móviles */
@media (max-width: 767.98px) {
    .d-block.d-md-none .card:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
}
</style>

<?php include("includes/footer.php"); ?>