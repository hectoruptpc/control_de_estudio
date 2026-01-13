<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Pensum de la Carrera";
include('../funciones/functions.php');

// Verificar conexión MySQLi
if (!$db) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_carrera = null;
$carrera = null;
$version_year = null;

// Permitir búsqueda por id OR por código + año
if (isset($_GET['id_version'])) {
    $version_id = intval($_GET['id_version']);
    if ($version_id <= 0) { header("Location: lista_carreras.php"); exit(); }

    $stmt = $db->prepare("SELECT v.id_version, v.id_carrera, v.fecha_vigencia, c.nombre_carrera, c.tipo_formacion FROM carrera_versiones v JOIN carreras c ON v.id_carrera = c.id_carrera WHERE v.id_version = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $version_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $carrera = ['nombre_carrera' => $row['nombre_carrera'], 'tipo_formacion' => $row['tipo_formacion']];
            $id_carrera = (int)$row['id_carrera'];
            $version_year = !empty($row['fecha_vigencia']) ? date('Y', strtotime($row['fecha_vigencia'])) : null;
        }
        $stmt->close();
    }

    if (!$carrera) { header("Location: lista_carreras.php"); exit(); }

} elseif (isset($_GET['cod']) && isset($_GET['anio'])) {
    $cod = trim($_GET['cod']);
    $anio = (int)$_GET['anio'];
    if ($anio <= 0 || empty($cod)) { header("Location: lista_carreras.php"); exit(); }

    // Buscar la versión correspondiente por código y año en carrera_versiones
    $version_id = null;
    $stmt = $db->prepare("SELECT v.id_version, c.id_carrera, c.nombre_carrera, c.tipo_formacion FROM carrera_versiones v JOIN carreras c ON v.id_carrera = c.id_carrera WHERE c.cod_carrera = ? AND YEAR(v.fecha_vigencia) = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('si', $cod, $anio);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $carrera = ['nombre_carrera' => $row['nombre_carrera'], 'tipo_formacion' => $row['tipo_formacion']];
            $id_carrera = (int)$row['id_carrera'];
            $version_id = (int)$row['id_version'];
            $version_year = !empty($row['fecha_vigencia']) ? date('Y', strtotime($row['fecha_vigencia'])) : $anio;
        }
        $stmt->close();
    }

    if (!$carrera) { header("Location: lista_carreras.php"); exit(); }

} else {
    // Parámetro por id (comportamiento original)
    if (!isset($_GET['id_carrera']) || !is_numeric($_GET['id_carrera'])) {
        header("Location: lista_carreras.php");
        exit();
    }

    $id_carrera = (int)$_GET['id_carrera'];

    // Obtener información básica de la carrera
    $stmt = $db->prepare("SELECT nombre_carrera, tipo_formacion, created_at FROM carreras WHERE id_carrera = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $id_carrera);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $carrera = $res->fetch_assoc();
            $version_year = !empty($carrera['created_at']) ? date('Y', strtotime($carrera['created_at'])) : null;
        }
        $stmt->close();
    }

    if (!$carrera) {
        header("Location: lista_carreras.php");
        exit();
    }

}
$es_pnf = ($carrera['tipo_formacion'] == 'PNF');

// Obtener el tipo de período según la carrera (trimestre o semestre)
$tipo_periodo = obtenerTipoPeriodoPorCarrera($id_carrera);

// (removed debug log)

// Corregir la formación del plural
$texto_duracion = ($tipo_periodo == 'trimestre') ? 'trimestres' : 'semestres';

// Obtener materias agrupadas por trayecto y ordenadas por duración
if (!empty($version_id)) {
    $query_materias = "SELECT m.*, vm.semestre, m.trayecto FROM materias m JOIN version_materia vm ON m.id_materia = vm.id_materia WHERE vm.id_version = " . intval($version_id) . " ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
} else {
    $query_materias = "SELECT m.*, cm.semestre, m.trayecto FROM materias m JOIN carrera_materia cm ON m.id_materia = cm.id_materia WHERE cm.id_carrera = " . intval($id_carrera) . " ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
}

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
    $title = 'Pensum: ' . $carrera['nombre_carrera'];
    if (!empty($version_year)) $title .= ' (Año: ' . $version_year . ')';
    $pdf->Cell(0, 8, to_iso($title), 0, 1, 'C');
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

    $filename = 'pensum_' . $id_carrera . (!empty($version_year) ? '_' . $version_year : '');
    $pdf->Output('I', $filename . '.pdf');
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
            <?php $pdf_url = !empty($version_id) ? ('?id_version=' . intval($version_id) . '&pdf=1') : ('?id_carrera=' . intval($id_carrera) . '&pdf=1'); ?>
            <a href="<?= $pdf_url ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm no-print ml-2">
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