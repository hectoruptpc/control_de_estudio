<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Pensum de la Carrera";
include('../funciones/functions.php');

// Verificar conexión MySQLi
if (!$db) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Validar parámetro
if (!isset($_GET['id_carrera']) || !is_numeric($_GET['id_carrera'])) {
    header("Location: lista_carreras.php");
    exit();
}

$id_carrera = (int)$_GET['id_carrera'];

// Obtener información básica de la carrera
$query_carrera = "SELECT nombre_carrera, tipo_formacion FROM carreras WHERE id_carrera = $id_carrera";
$result_carrera = mysqli_query($db, $query_carrera);

if (!$result_carrera || mysqli_num_rows($result_carrera) === 0) {
    header("Location: lista_carreras.php");
    exit();
}

$carrera = mysqli_fetch_assoc($result_carrera);
$es_pnf = ($carrera['tipo_formacion'] == 'PNF');
$texto_periodo = $es_pnf ? 'Trimestre' : 'Semestre';
$texto_duracion = $es_pnf ? 'trimestres' : 'semestres';

// Obtener materias agrupadas por trayecto (desde tabla materias) y semestre (desde carrera_materia)
$query_materias = "SELECT 
                    m.*, 
                    cm.semestre,
                    m.trayecto
                  FROM materias m
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE cm.id_carrera = $id_carrera
                  ORDER BY m.trayecto, cm.semestre, m.nombre_materia";
$result_materias = mysqli_query($db, $query_materias);

if (!$result_materias) {
    die("Error en consulta: " . mysqli_error($db));
}

// Agrupar materias por trayecto y semestre
$materias_agrupadas = [];
while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    $semestre = $materia['semestre'];
    
    // Formatear el texto del trayecto
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    
    if (!isset($materias_agrupadas[$texto_trayecto])) {
        $materias_agrupadas[$texto_trayecto] = [];
    }
    
    if (!isset($materias_agrupadas[$texto_trayecto][$semestre])) {
        $materias_agrupadas[$texto_trayecto][$semestre] = [];
    }
    
    $materias_agrupadas[$texto_trayecto][$semestre][] = $materia;
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pensum: <?php echo htmlspecialchars($carrera['nombre_carrera']); ?></h1>
        <a href="lista_carreras.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a Carreras
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Estructura del Pensum</h6>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning">No hay materias asignadas a esta carrera.</div>
            <?php else: ?>
                <?php foreach ($materias_agrupadas as $texto_trayecto => $periodos): ?>
                    <div class="mb-5">
                        <h4 class="font-weight-bold text-primary"><?php echo $texto_trayecto; ?></h4>
                        
                        <?php foreach ($periodos as $numero_periodo => $materias): ?>
                            <div class="mb-4 ml-3">
                                <h5 class="font-weight-bold"><?php echo $texto_periodo; ?> <?php echo $numero_periodo; ?></h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Código</th>
                                                <th>Nombre</th>
                                                <th>Créditos</th>
                                                <th>Horas Teóricas</th>
                                                <th>Horas Prácticas</th>
                                                <th>Duración</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($materias as $materia): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                                    <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                                    <td><?php echo htmlspecialchars($materia['creditos']); ?></td>
                                                    <td><?php echo htmlspecialchars($materia['horas_teoricas']); ?></td>
                                                    <td><?php echo htmlspecialchars($materia['horas_practicas']); ?></td>
                                                    <td><?php echo htmlspecialchars($materia['duracion_periodo']) . ' ' . $texto_duracion; ?></td>
                                                    <td>
                                                        <span class="badge badge-<?php echo $materia['activa'] ? 'success' : 'secondary'; ?>">
                                                            <?php echo $materia['activa'] ? 'Activa' : 'Inactiva'; ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>