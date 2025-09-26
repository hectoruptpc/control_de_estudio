<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Notas Definitivas por Profesor";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('consultar_notas_pasadas');

// Obtener datos para los filtros
$docentes = [];
$materias = [];
$periodos = [];
$carreras = [];

// Obtener docentes
$sql_docentes = "SELECT id, nombre, username FROM users WHERE docente = 1 AND status = 1 ORDER BY nombre";
$result_docentes = $db->query($sql_docentes);
if ($result_docentes) {
    while ($row = $result_docentes->fetch_assoc()) {
        $docentes[] = $row;
    }
    $result_docentes->free();
}

// Obtener materias
$sql_materias = "SELECT id_materia, cod_materia, nombre_materia FROM materias WHERE activa = 1 ORDER BY nombre_materia";
$result_materias = $db->query($sql_materias);
if ($result_materias) {
    while ($row = $result_materias->fetch_assoc()) {
        $materias[] = $row;
    }
    $result_materias->free();
}

// Obtener periodos académicos
$sql_periodos = "SELECT id_periodo, nombre_periodo FROM periodos_academicos WHERE activo = 1 ORDER BY fecha_inicio DESC";
$result_periodos = $db->query($sql_periodos);
if ($result_periodos) {
    while ($row = $result_periodos->fetch_assoc()) {
        $periodos[] = $row;
    }
    $result_periodos->free();
}

// Obtener carreras - CORREGIDO: asegurar que obtenemos strings, no arrays
$sql_carreras = "SELECT DISTINCT carrera FROM users WHERE carrera IS NOT NULL AND carrera != '' ORDER BY carrera";
$result_carreras = $db->query($sql_carreras);
if ($result_carreras) {
    while ($row = $result_carreras->fetch_assoc()) {
        // Asegurarnos de que carrera es un string
        if (is_string($row['carrera']) && !empty($row['carrera'])) {
            $carreras[] = $row['carrera'];
        }
    }
    $result_carreras->free();
}

// Procesar filtros
$filtros = [];
$where_conditions = [];
$params = [];
$param_types = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['id_docente'])) {
        $filtros['id_docente'] = intval($_POST['id_docente']);
        $where_conditions[] = "nd.id_docente = ?";
        $params[] = $filtros['id_docente'];
        $param_types .= "i";
    }
    
    if (!empty($_POST['id_materia'])) {
        $filtros['id_materia'] = intval($_POST['id_materia']);
        $where_conditions[] = "nd.id_materia = ?";
        $params[] = $filtros['id_materia'];
        $param_types .= "i";
    }
    
    if (!empty($_POST['id_periodo'])) {
        $filtros['id_periodo'] = intval($_POST['id_periodo']);
        $where_conditions[] = "nd.id_periodo = ?";
        $params[] = $filtros['id_periodo'];
        $param_types .= "i";
    }
    
    if (!empty($_POST['carrera'])) {
        $filtros['carrera'] = $db->real_escape_string($_POST['carrera']);
        $where_conditions[] = "u.carrera = ?";
        $params[] = $filtros['carrera'];
        $param_types .= "s";
    }
    
    if (!empty($_POST['trayecto'])) {
        $filtros['trayecto'] = intval($_POST['trayecto']);
        $where_conditions[] = "m.trayecto = ?";
        $params[] = $filtros['trayecto'];
        $param_types .= "i";
    }
}

// Consulta para obtener las notas definitivas
$notas = [];
if (!empty($where_conditions) || isset($_GET['mostrar_todo'])) {
    $sql = "SELECT 
                nd.id,
                d.nombre as nombre_docente,
                u.nombre as nombre_estudiante,
                u.carrera,
                m.nombre_materia,
                m.trayecto,
                p.nombre_periodo,
                nd.trayecto_0,
                nd.trayecto_1,
                nd.trayecto_2,
                nd.trayecto_3,
                nd.trayecto_4,
                nd.fecha_registro,
                a.nombre as admin_aprobador
            FROM notas_definitivas nd
            INNER JOIN users d ON nd.id_docente = d.id
            INNER JOIN users u ON nd.id_usuario = u.id
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            INNER JOIN periodos_academicos p ON nd.id_periodo = p.id_periodo
            LEFT JOIN users a ON nd.id_admin_aprobador = a.id";
    
    if (!empty($where_conditions)) {
        $sql .= " WHERE " . implode(" AND ", $where_conditions);
    }
    
    $sql .= " ORDER BY d.nombre, p.fecha_inicio DESC, m.nombre_materia, u.nombre";
    
    $stmt = $db->prepare($sql);
    
    if ($stmt) {
        // Bind parameters if they exist
        if (!empty($params)) {
            $stmt->bind_param($param_types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notas[] = $row;
            }
            $result->free();
        }
        $stmt->close();
    } else {
        $error = "Error en la consulta: " . $db->error;
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Notas Definitivas por Profesor</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Formulario de Filtros -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_docente">Profesor:</label>
                                    <select class="form-control" id="id_docente" name="id_docente">
                                        <option value="">Seleccionar Profesor</option>
                                        <?php foreach ($docentes as $docente): ?>
                                            <option value="<?php echo $docente['id']; ?>" 
                                                <?php echo (isset($filtros['id_docente']) && $filtros['id_docente'] == $docente['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($docente['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="id_materia">Materia:</label>
                                    <select class="form-control" id="id_materia" name="id_materia">
                                        <option value="">Seleccionar Materia</option>
                                        <?php foreach ($materias as $materia): ?>
                                            <option value="<?php echo $materia['id_materia']; ?>" 
                                                <?php echo (isset($filtros['id_materia']) && $filtros['id_materia'] == $materia['id_materia']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($materia['nombre_materia']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_periodo">Periodo:</label>
                                    <select class="form-control" id="id_periodo" name="id_periodo">
                                        <option value="">Seleccionar Periodo</option>
                                        <?php foreach ($periodos as $periodo): ?>
                                            <option value="<?php echo $periodo['id_periodo']; ?>" 
                                                <?php echo (isset($filtros['id_periodo']) && $filtros['id_periodo'] == $periodo['id_periodo']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($periodo['nombre_periodo']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="carrera">Carrera:</label>
                                    <select class="form-control" id="carrera" name="carrera">
                                        <option value="">Seleccionar Carrera</option>
                                        <?php foreach ($carreras as $carrera): ?>
                                            <!-- CORREGIDO: $carrera ahora es un string, no un array -->
                                            <option value="<?php echo htmlspecialchars($carrera); ?>" 
                                                <?php echo (isset($filtros['carrera']) && $filtros['carrera'] == $carrera) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($carrera); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="trayecto">Trayecto:</label>
                                    <select class="form-control" id="trayecto" name="trayecto">
                                        <option value="">Todos</option>
                                        <option value="0" <?php echo (isset($filtros['trayecto']) && $filtros['trayecto'] == '0') ? 'selected' : ''; ?>>Trayecto 0</option>
                                        <option value="1" <?php echo (isset($filtros['trayecto']) && $filtros['trayecto'] == '1') ? 'selected' : ''; ?>>Trayecto 1</option>
                                        <option value="2" <?php echo (isset($filtros['trayecto']) && $filtros['trayecto'] == '2') ? 'selected' : ''; ?>>Trayecto 2</option>
                                        <option value="3" <?php echo (isset($filtros['trayecto']) && $filtros['trayecto'] == '3') ? 'selected' : ''; ?>>Trayecto 3</option>
                                        <option value="4" <?php echo (isset($filtros['trayecto']) && $filtros['trayecto'] == '4') ? 'selected' : ''; ?>>Trayecto 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="?mostrar_todo=1" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> Mostrar Todo
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Resultados -->
            <?php if (!empty($notas)): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Resultados (<?php echo count($notas); ?> registros encontrados)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablaNotas" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Profesor</th>
                                        <th>Estudiante</th>
                                        <th>Carrera</th>
                                        <th>Materia</th>
                                        <th>Trayecto</th>
                                        <th>Periodo</th>
                                        <th>Nota T0</th>
                                        <th>Nota T1</th>
                                        <th>Nota T2</th>
                                        <th>Nota T3</th>
                                        <th>Nota T4</th>
                                        <th>Fecha Registro</th>
                                        <th>Aprobado por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notas as $nota): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($nota['nombre_docente']); ?></td>
                                            <td><?php echo htmlspecialchars($nota['nombre_estudiante']); ?></td>
                                            <td><?php echo htmlspecialchars($nota['carrera']); ?></td>
                                            <td><?php echo htmlspecialchars($nota['nombre_materia']); ?></td>
                                            <td><?php echo htmlspecialchars($nota['trayecto']); ?></td>
                                            <td><?php echo htmlspecialchars($nota['nombre_periodo']); ?></td>
                                            <td><?php echo isset($nota['trayecto_0']) && $nota['trayecto_0'] !== null ? $nota['trayecto_0'] : '-'; ?></td>
                                            <td><?php echo isset($nota['trayecto_1']) && $nota['trayecto_1'] !== null ? $nota['trayecto_1'] : '-'; ?></td>
                                            <td><?php echo isset($nota['trayecto_2']) && $nota['trayecto_2'] !== null ? $nota['trayecto_2'] : '-'; ?></td>
                                            <td><?php echo isset($nota['trayecto_3']) && $nota['trayecto_3'] !== null ? $nota['trayecto_3'] : '-'; ?></td>
                                            <td><?php echo isset($nota['trayecto_4']) && $nota['trayecto_4'] !== null ? $nota['trayecto_4'] : '-'; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($nota['fecha_registro'])); ?></td>
                                            <td><?php echo htmlspecialchars($nota['admin_aprobador'] ?? 'No asignado'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['mostrar_todo'])): ?>
                <div class="alert alert-warning">
                    No se encontraron registros con los filtros seleccionados.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function exportarExcel() {
    // Crear una tabla temporal para la exportación
    let tabla = document.getElementById('tablaNotas');
    let html = tabla.outerHTML;
    
    // Crear un blob y descargar
    let blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'notas_definitivas_' + new Date().toISOString().split('T')[0] + '.xls';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Inicializar DataTables
$(document).ready(function() {
    $('#tablaNotas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "pageLength": 25,
        "order": [[0, 'asc'], [4, 'asc'], [3, 'asc']]
    });
});
</script>

<?php include("includes/footer.php"); ?>