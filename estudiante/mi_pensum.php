<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Mi Pensum Académico";
include('../funciones/functions.php');

// 1. Verificar autenticación y rol
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// 2. Obtener información del estudiante y su carrera
$user_id = (int)$_SESSION['user']['id'];
$query_estudiante = "SELECT carrera FROM users WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($db, $query_estudiante);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result_estudiante = mysqli_stmt_get_result($stmt);

if (!$result_estudiante || mysqli_num_rows($result_estudiante) === 0) {
    $_SESSION['error'] = "No se encontró información del estudiante";
    header('location: index.php');
    die();
}

$estudiante = mysqli_fetch_assoc($result_estudiante);
$id_carrera = (int)$estudiante['carrera'];

if ($id_carrera === 0) {
    $_SESSION['error'] = "No tienes una carrera asignada";
    header('location: index.php');
    die();
}

// 3. Obtener información detallada de la carrera
$query_carrera = "SELECT nombre_carrera, cod_carrera, tipo_formacion FROM carreras WHERE id_carrera = ?";
$stmt = mysqli_prepare($db, $query_carrera);
mysqli_stmt_bind_param($stmt, 'i', $id_carrera);
mysqli_stmt_execute($stmt);
$result_carrera = mysqli_stmt_get_result($stmt);
$carrera = mysqli_fetch_assoc($result_carrera);

$es_pnf = ($carrera['tipo_formacion'] == 'PNF');
$tipo_periodo = obtenerTipoPeriodoPorCarrera($id_carrera);
$texto_duracion = ($tipo_periodo == 'trimestre') ? 'trimestres' : 'semestres';

// 4. Intentar obtener el ID de la malla activa para este estudiante (opcional según tu lógica de BD)
$id_malla = null;
$codigo_malla = null;
$mallas_disponibles = obtenerMallasPorCarrera($id_carrera);
if (!empty($mallas_disponibles)) {
    $id_malla = intval($mallas_disponibles[0]['id_malla']);
    $codigo_malla = $mallas_disponibles[0]['codigo_malla'];
}

// 5. Obtener materias (Priorizando tabla malla_materia si existe el ID)
if (!empty($id_malla)) {
    $query_materias = "SELECT m.*, mm.semestre, m.trayecto FROM materias m JOIN malla_materia mm ON m.id_materia = mm.id_materia WHERE mm.id_malla = $id_malla ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
} else {
    $query_materias = "SELECT m.*, cm.semestre, m.trayecto FROM materias m JOIN carrera_materia cm ON m.id_materia = cm.id_materia WHERE cm.id_carrera = $id_carrera ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
}

$result_materias = mysqli_query($db, $query_materias);

// Agrupar materias por trayecto
$materias_agrupadas = [];
while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    if (!isset($materias_agrupadas[$texto_trayecto])) {
        $materias_agrupadas[$texto_trayecto] = [];
    }
    $materias_agrupadas[$texto_trayecto][] = $materia;
}

// ==========================================
// GENERACIÓN DE PDF (IGUAL QUE VER_PENSUM.PHP)
// ==========================================
if (isset($_GET['pdf']) && $_GET['pdf'] == '1') {
    ini_set('display_errors', '0');
    require_once __DIR__ . '/../fpdf/fpdf.php';

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 15);

    if (function_exists('agregarMembreteFPDF')) {
        agregarMembreteFPDF($pdf);
        $pdf->SetY(45);
    }

    $pdf->SetFont('Arial', 'B', 14);
    $title = 'Pensum Académico: ' . $carrera['nombre_carrera'];
    if (!empty($codigo_malla)) $title .= ' - ' . $codigo_malla;
    $pdf->Cell(0, 8, to_iso($title), 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, to_iso('Estudiante: ' . $_SESSION['user']['nombre'] . ' ' . $_SESSION['user']['apellido']), 0, 1, 'L');
    $pdf->Cell(0, 6, to_iso('Fecha: ' . date('d/m/Y')), 0, 1, 'R');
    $pdf->Ln(4);

    foreach ($materias_agrupadas as $texto_trayecto => $materias) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 7, to_iso($texto_trayecto), 0, 1);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(25, 7, to_iso('Codigo'), 1, 0, 'C', true);
        $pdf->Cell(75, 7, to_iso('Nombre'), 1, 0, 'L', true);
        $pdf->Cell(12, 7, to_iso('UC'), 1, 0, 'C', true);
        $pdf->Cell(18, 7, to_iso('Horas T'), 1, 0, 'C', true);
        $pdf->Cell(18, 7, to_iso('Horas P'), 1, 0, 'C', true);
        $pdf->Cell(22, 7, to_iso('Duración'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, to_iso('Estado'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        foreach ($materias as $materia) {
            $estado = $materia['activa'] ? 'Activa' : 'Inactiva';
            
            // Altura dinámica para el nombre
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->Cell(25, 6, to_iso($materia['cod_materia']), 1, 0, 'L');
            
            $nombre_tratado = insertarEspaciosEnPalabrasLargas($materia['nombre_materia'], 30);
            $pdf->MultiCell(75, 6, to_iso($nombre_tratado), 1);
            $newY = $pdf->GetY();
            $pdf->SetXY($x + 100, $y);

            $pdf->Cell(12, 6, to_iso($materia['creditos']), 1, 0, 'C');
            $pdf->Cell(18, 6, to_iso($materia['horas_teoricas']), 1, 0, 'C');
            $pdf->Cell(18, 6, to_iso($materia['horas_practicas']), 1, 0, 'C');
            $pdf->Cell(22, 6, to_iso($materia['duracion_periodo']), 1, 0, 'C');
            $pdf->Cell(20, 6, to_iso($estado), 1, 1, 'C');
            
            if($pdf->GetY() < $newY) $pdf->SetY($newY);
        }
        $pdf->Ln(4);
    }

    $pdf->Output('I', 'Mi_Pensum_' . $id_carrera . '.pdf');
    exit();
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Pensum Académico: <?php echo mb_strtoupper(htmlspecialchars($carrera['nombre_carrera']), 'UTF-8'); ?></h1>
        <div>
            <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm no-print">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Inicio
            </a>
            <a href="?pdf=1" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm no-print ml-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Generar PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Plan de Estudios - Duración en <?php echo $texto_duracion; ?></h5>
            <span class="no-print"><?php echo date('d/m/Y'); ?></span>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning">No hay materias asignadas a tu carrera.</div>
            <?php else: ?>
                <div class="accordion" id="pensumAccordion">
                    <?php foreach ($materias_agrupadas as $texto_trayecto => $materias): ?>
                        <div class="card mb-3">
                            <div class="card-header" id="heading<?= md5($texto_trayecto) ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" 
                                            data-target="#collapse<?= md5($texto_trayecto) ?>" 
                                            aria-expanded="true" aria-controls="collapse<?= md5($texto_trayecto) ?>">
                                        <?= $texto_trayecto ?>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse<?= md5($texto_trayecto) ?>" class="collapse show" 
                                 aria-labelledby="heading<?= md5($texto_trayecto) ?>" data-parent="#pensumAccordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="10%">Código</th>
                                                    <th width="35%">Nombre</th>
                                                    <th width="8%">Créditos</th>
                                                    <th width="12%">Horas T</th>
                                                    <th width="12%">Horas P</th>
                                                    <th width="13%">Duración (<?php echo $texto_duracion; ?>)</th>
                                                    <th width="10%">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($materias as $materia): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                                        <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['creditos']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['horas_teoricas']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['horas_practicas']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['duracion_periodo']); ?></td>
                                                        <td class="text-center">
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>