<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Mi Pensum Académico";
include('../funciones/functions.php');

if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

visita();

$user_id = (int)$_SESSION['user']['id'];
// Buscamos la carrera y también intentamos identificar la malla activa para esa carrera
$query_estudiante = "SELECT carrera FROM users WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($db, $query_estudiante);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result_estudiante = mysqli_stmt_get_result($stmt);

if (!$result_estudiante || mysqli_num_rows($result_estudiante) === 0) {
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

// Obtener nombre de carrera y buscar el código de malla asociado
$query_info = "SELECT c.nombre_carrera, m.codigo_malla 
               FROM carreras c 
               LEFT JOIN mallas m ON c.id_carrera = m.id_carrera 
               WHERE c.id_carrera = ? 
               ORDER BY m.id_malla DESC LIMIT 1";
$stmt = mysqli_prepare($db, $query_info);
mysqli_stmt_bind_param($stmt, 'i', $id_carrera);
mysqli_stmt_execute($stmt);
$res_info = mysqli_stmt_get_result($stmt);
$info = mysqli_fetch_assoc($res_info);
$nombre_carrera = $info['nombre_carrera'];
$codigo_malla = $info['codigo_malla'] ?? '';

$tipo_periodo = obtenerTipoPeriodoPorCarrera($id_carrera);

// Obtener materias
$query_materias = "SELECT m.*, cm.semestre, m.trayecto 
                  FROM materias m 
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia 
                  WHERE cm.id_carrera = ? 
                  ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
$stmt = mysqli_prepare($db, $query_materias);
mysqli_stmt_bind_param($stmt, 'i', $id_carrera);
mysqli_stmt_execute($stmt);
$result_materias = mysqli_stmt_get_result($stmt);

$materias_agrupadas = [];
while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    $materias_agrupadas[$texto_trayecto][] = $materia;
}

// PDF
if (isset($_GET['pdf']) && $_GET['pdf'] == '1') {
    ini_set('display_errors', '0');
    require_once __DIR__ . '/../fpdf/fpdf.php';
    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();

    if (function_exists('agregarMembreteFPDF')) {
        agregarMembreteFPDF($pdf);
        $pdf->SetY(45);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, to_iso('PENSUM ACADÉMICO: ' . mb_strtoupper($nombre_carrera)), 0, 1, 'C');
    if(!empty($codigo_malla)) {
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, to_iso($codigo_malla), 0, 1, 'C');
    }
    $pdf->Ln(5);

    foreach ($materias_agrupadas as $trayecto_nombre => $materias) {
        if ($pdf->GetY() > 250) $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 7, to_iso($trayecto_nombre), 1, 1, 'L', true);

        $w = [22, 58, 10, 14, 14, 14, 14, 24, 20];
        $pdf->SetFont('Arial', 'B', 8);
        $headers = ['CÓDIGO', 'ASIGNATURA', 'UC', 'H.T.', 'H.P.', 'H.L.', 'H.S.', 'DURACIÓN', 'ESTADO'];
        foreach($headers as $i => $h_text) $pdf->Cell($w[$i], 7, to_iso($h_text), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        foreach ($materias as $m) {
            $nb_lines = $pdf->GetStringWidth(to_iso($m['nombre_materia'])) > $w[1] ? 2 : 1;
            $h_fila = 6 * $nb_lines;
            if ($pdf->GetY() + $h_fila > 270) $pdf->AddPage();
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->Cell($w[0], $h_fila, to_iso($m['cod_materia'] ?? ''), 1, 0, 'C');
            $pdf->MultiCell($w[1], ($h_fila/$nb_lines), to_iso($m['nombre_materia'] ?? ''), 1, 'L');
            $pdf->SetXY($x + $w[0] + $w[1], $y);
            $pdf->Cell($w[2], $h_fila, $m['creditos'] ?? '0', 1, 0, 'C');
            $pdf->Cell($w[3], $h_fila, $m['horas_teoricas'] ?? '0', 1, 0, 'C');
            $pdf->Cell($w[4], $h_fila, $m['horas_practicas'] ?? '0', 1, 0, 'C');
            $pdf->Cell($w[5], $h_fila, $m['horas_laboratorio'] ?? '0', 1, 0, 'C');
            $pdf->Cell($w[6], $h_fila, $m['horas_semanales'] ?? '0', 1, 0, 'C');
            $pdf->Cell($w[7], $h_fila, to_iso($m['duracion_periodo'] ?? ''), 1, 0, 'C');
            $pdf->Cell($w[8], $h_fila, ($m['activa'] ? 'Activa' : 'Inactiva'), 1, 1, 'C');
        }
        $pdf->Ln(4);
    }
    $pdf->Output('I', 'Mi_Pensum.pdf');
    exit();
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Mi Pensum Académico</h1>
            <?php if(!empty($codigo_malla)): ?>
                <span class="badge badge-info"><?= htmlspecialchars($codigo_malla) ?></span>
            <?php endif; ?>
        </div>
        <div>
            <a href="index.php" class="btn btn-sm btn-primary shadow-sm no-print"><i class="fas fa-arrow-left"></i> Volver</a>
            <a href="?pdf=1" class="btn btn-sm btn-success shadow-sm no-print ml-2"><i class="fas fa-print"></i> Imprimir</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h5 class="m-0 font-weight-bold"><?= htmlspecialchars($nombre_carrera) ?></h5>
        </div>
        <div class="card-body">
            <?php foreach ($materias_agrupadas as $texto_trayecto => $materias): ?>
                <h5 class="font-weight-bold text-primary mt-4 mb-3"><?= $texto_trayecto ?></h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th class="text-left">Nombre de la Asignatura</th>
                                <th>Unidades Crédito</th>
                                <th>Horas Teóricas</th>
                                <th>Horas Prácticas</th>
                                <th>Horas Laboratorio</th>
                                <th>Horas Semanales</th>
                                <th>Duración Periodo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($materias as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['cod_materia'] ?? '') ?></td>
                                    <td class="text-left"><?= htmlspecialchars($m['nombre_materia'] ?? '') ?></td>
                                    <td><?= (int)($m['creditos'] ?? 0) ?></td>
                                    <td><?= (int)($m['horas_teoricas'] ?? 0) ?></td>
                                    <td><?= (int)($m['horas_practicas'] ?? 0) ?></td>
                                    <td><?= (int)($m['horas_laboratorio'] ?? 0) ?></td>
                                    <td><?= (int)($m['horas_semanales'] ?? 0) ?></td>
                                    <td><?= htmlspecialchars($m['duracion_periodo'] ?? '') ?></td>
                                    <td><span class="badge badge-<?= $m['activa'] ? 'success' : 'secondary' ?>"><?= $m['activa'] ? 'Activa' : 'Inactiva' ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>