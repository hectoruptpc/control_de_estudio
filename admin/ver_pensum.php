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

// Obtener el tipo de período según la carrera (trimestre o semestre)
$tipo_periodo = obtenerTipoPeriodoPorCarrera($id_carrera);

// Debug: ver qué está retornando la función
error_log("Tipo de periodo para carrera ID $id_carrera: " . $tipo_periodo);

// Corregir la formación del plural
$texto_duracion = ($tipo_periodo == 'trimestre') ? 'trimestres' : 'semestres';

// Obtener materias agrupadas por trayecto y ordenadas por duración
$query_materias = "SELECT 
                    m.*, 
                    cm.semestre,
                    m.trayecto
                  FROM materias m
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE cm.id_carrera = $id_carrera
                  ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
$result_materias = mysqli_query($db, $query_materias);

if (!$result_materias) {
    die("Error en consulta: " . mysqli_error($db));
}

// Agrupar materias solo por trayecto
$materias_agrupadas = [];
while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    
    // Formatear el texto del trayecto
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    
    if (!isset($materias_agrupadas[$texto_trayecto])) {
        $materias_agrupadas[$texto_trayecto] = [];
    }
    
    $materias_agrupadas[$texto_trayecto][] = $materia;
}

// Generar PDF si se solicita
if (isset($_GET['pdf']) && $_GET['pdf'] == '1') {
    // Evitar que mensajes deprecados o advertencias envíen salida antes del PDF
    ini_set('display_errors', '0');
    error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);

    require_once __DIR__ . '/../fpdf/fpdf.php';

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 15);

    // Agregar membrete usando la función en funciones.php
    if (function_exists('agregarMembreteFPDF')) {
        agregarMembreteFPDF($pdf);
        $pdf->SetY(45); // posición después del membrete
    }

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, to_iso('Pensum: ' . $carrera['nombre_carrera']), 0, 1, 'C');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, to_iso('Fecha: ' . date('d/m/Y')), 0, 1, 'R');
    $pdf->Ln(4);

    foreach ($materias_agrupadas as $texto_trayecto => $materias) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 7, to_iso($texto_trayecto), 0, 1);

        // Cabecera de tabla
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(25, 7, to_iso('Codigo'), 1, 0, 'C', true);
        $pdf->Cell(70, 7, to_iso('Nombre'), 1, 0, 'L', true);
        $pdf->Cell(15, 7, to_iso('Cred'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, to_iso('Horas T'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, to_iso('Horas P'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, to_iso('Duración'), 1, 0, 'C', true);
        $pdf->Cell(20, 7, to_iso('Estado'), 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        foreach ($materias as $materia) {
            $estado = $materia['activa'] ? 'Activa' : 'Inactiva';
            $pdf->Cell(25, 6, to_iso($materia['cod_materia']), 1, 0, 'L');

            // Nombre: usar MultiCell alternativa para evitar cortar texto
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell(70, 6, to_iso($materia['nombre_materia']), 1);
            $pdf->SetXY($x + 70, $y);

            $pdf->Cell(15, 6, to_iso($materia['creditos']), 1, 0, 'C');
            $pdf->Cell(20, 6, to_iso($materia['horas_teoricas']), 1, 0, 'C');
            $pdf->Cell(20, 6, to_iso($materia['horas_practicas']), 1, 0, 'C');
            $pdf->Cell(20, 6, to_iso($materia['duracion_periodo']), 1, 0, 'C');
            $pdf->Cell(20, 6, to_iso($estado), 1, 1, 'C');
        }

        $pdf->Ln(4);
    }

    $pdf->Output('I', 'pensum_' . $id_carrera . '.pdf');
    exit();
}

include("includes/head.php");
?>

<!-- Resto del código HTML/PHP permanece igual -->
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pensum: <?php echo htmlspecialchars($carrera['nombre_carrera']); ?> 
            <small class="text-muted">(<?php echo strtoupper($tipo_periodo); ?>s)</small>
        </h1>
        <div>
            <a href="agregar_carrera.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm no-print">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a Carreras
            </a>
            <a href="?id_carrera=<?php echo $id_carrera; ?>&pdf=1" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm no-print ml-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Generar PDF
            </a>
        </div>
    </div>

    <div class="card shadow mb-4" id="printable-area">
        <div class="card-header py-3 bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Plan de Estudios - Duración en <?php echo $texto_duracion; ?></h5>
            <span class="no-print"><?php echo date('d/m/Y'); ?></span>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning">No hay materias asignadas a esta carrera.</div>
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