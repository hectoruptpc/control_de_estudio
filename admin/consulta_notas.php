<?php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

$titulopag = "Consulta de Notas por Cédula";
include("includes/head.php");

// Función para buscar estudiante por cédula (nombre cambiado)
function buscarEstudiantePorCedulaConsulta($cedula) {
    global $db;
    
    $query = "SELECT u.id, u.nombre, u.idusuario, u.carrera 
              FROM users u 
              WHERE u.idusuario = ? AND u.estudiante = 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Función para obtener la carrera del estudiante
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

// Función para obtener todas las materias de la carrera
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

// Función para obtener información del trayecto desde la tabla trayectos
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

// Función para obtener las notas definitivas del estudiante
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

// Procesar búsqueda
$estudiante = null;
$carrera = null;
$materias_carrera = [];
$notas_estudiante = [];
$mensaje_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'])) {
    $cedula = trim($_POST['cedula']);
    
    if (!empty($cedula)) {
        $estudiante = buscarEstudiantePorCedulaConsulta($cedula);
        
        if ($estudiante) {
            // Obtener información de la carrera
            $carrera = obtenerCarreraEstudiante($estudiante['id']);
            
            if ($carrera) {
                // Obtener todas las materias de la carrera
                $materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
                
                // Obtener notas del estudiante (si existen)
                $notas_estudiante = obtenerNotasEstudianteConsulta($estudiante['id']);
            }
        } else {
            $mensaje_error = "No se encontró ningún estudiante con la cédula: " . htmlspecialchars($cedula);
        }
    } else {
        $mensaje_error = "Por favor, ingrese una cédula para buscar.";
    }
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
                           placeholder="Ej: V12345678" value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : '' ?>" required>
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
        <div class="card-header bg-info text-white">
            <h5>Información del Estudiante</h5>
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
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($materias_carrera->num_rows > 0): ?>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5>Plan de Estudios y Notas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Trayecto</th>
                            <th>Materia</th>
                            <th>Código</th>
                            <th>Nota</th>
                            <th>Estado</th>
                            <th>Periodo</th>
                            <th>Fecha Registro</th>
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
                        
                        while ($materia = $materias_carrera->fetch_assoc()): 
                            $nota = isset($notas_estudiante[$materia['id_materia']]) ? $notas_estudiante[$materia['id_materia']] : null;
                            
                            // Obtener información del trayecto de la materia
                            $numero_trayecto_materia = (int)$materia['trayecto'];
                            $info_trayecto = obtenerInfoTrayecto($numero_trayecto_materia);
                            $nombre_trayecto = $info_trayecto['nombre_trayecto'];
                            
                            // Obtener la nota específica del trayecto correspondiente
                            $nota_trayecto = null;
                            $tiene_nota = false;
                            
                            if ($nota) {
                                $campo_trayecto = 'trayecto_' . $numero_trayecto_materia;
                                if (isset($nota[$campo_trayecto]) && $nota[$campo_trayecto] !== null) {
                                    $nota_trayecto = (float)$nota[$campo_trayecto];
                                    $tiene_nota = true;
                                }
                            }
                            
                            // Determinar estado
                            $estado = 'Sin notas';
                            $color_estado = 'secondary';
                            $badge_estado = 'secondary';
                            
                            if ($tiene_nota) {
                                if ($nota_trayecto >= 12) {
                                    $estado = 'Aprobado';
                                    $color_estado = 'success';
                                    $badge_estado = 'success';
                                    $materias_aprobadas++;
                                } else {
                                    $estado = 'Reprobado';
                                    $color_estado = 'danger';
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
                                
                                <td>
                                    <?php if ($nota && !empty($nota['nombre_admin'])): ?>
                                        <?= htmlspecialchars($nota['nombre_admin']) ?>
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
                            
                            <p><strong>Promedio General:</strong> 
                                <span class="badge badge-<?= $promedio_general >= 12 ? 'success' : ($promedio_general > 0 ? 'warning' : 'secondary') ?>">
                                    <?= $promedio_general > 0 ? $promedio_general : 'N/A' ?>
                                </span>
                            </p>
                            <p><strong>Materias Aprobadas:</strong> 
                                <span class="badge badge-success"><?= $materias_aprobadas ?></span>
                                <?= $materias_con_notas > 0 ? "($porcentaje_aprobadas%)" : '' ?>
                            </p>
                            <p><strong>Materias Reprobadas:</strong> 
                                <span class="badge badge-danger"><?= $materias_reprobadas ?></span>
                                <?= $materias_con_notas > 0 ? "(" . (100 - $porcentaje_aprobadas) . "%)" : '' ?>
                            </p>
                            <p><strong>Materias Sin Notas:</strong> 
                                <span class="badge badge-secondary"><?= $materias_sin_notas ?></span>
                                (<?= $porcentaje_completado ?>% completado)
                            </p>
                            <p><strong>Total Materias:</strong> 
                                <span class="badge badge-primary"><?= $total_materias ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6>Progreso de la Carrera</h6>
                        </div>
                        <div class="card-body">
                            <?php if ($total_materias > 0): ?>
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= $porcentaje_completado ?>%"
                                     title="<?= $porcentaje_completado ?>% completado">
                                    <?= $porcentaje_completado ?>% Completado
                                </div>
                            </div>
                            
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" 
                                     style="width: <?= ($materias_aprobadas / $total_materias) * 100 ?>%">
                                    Aprobadas: <?= $materias_aprobadas ?>
                                </div>
                                <div class="progress-bar bg-danger" 
                                     style="width: <?= ($materias_reprobadas / $total_materias) * 100 ?>%">
                                    Reprobadas: <?= $materias_reprobadas ?>
                                </div>
                                <div class="progress-bar bg-secondary" 
                                     style="width: <?= ($materias_sin_notas / $total_materias) * 100 ?>%">
                                    Pendientes: <?= $materias_sin_notas ?>
                                </div>
                            </div>
                            <?php else: ?>
                                <p class="text-muted">No hay materias en esta carrera</p>
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
    
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>