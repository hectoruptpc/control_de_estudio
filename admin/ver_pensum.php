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
$codigo_malla = null;
$id_malla = null;

// --- LÓGICA DE BÚSQUEDA ---
if (isset($_GET['id_malla'])) {
    $malla_id = intval($_GET['id_malla']);
    $stmt = $db->prepare("SELECT m.id_malla, m.id_carrera, m.codigo_malla, m.anio, c.nombre_carrera, c.tipo_formacion FROM mallas m JOIN carreras c ON m.id_carrera = c.id_carrera WHERE m.id_malla = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $malla_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $carrera = ['nombre_carrera' => $row['nombre_carrera'], 'tipo_formacion' => $row['tipo_formacion']];
            $id_carrera = (int)$row['id_carrera'];
            $codigo_malla = $row['codigo_malla'];
            $id_malla = $malla_id;
        }
        $stmt->close();
    }
} elseif (isset($_GET['id_carrera'])) {
    $id_carrera = (int)$_GET['id_carrera'];
    $stmt = $db->prepare("SELECT nombre_carrera, tipo_formacion FROM carreras WHERE id_carrera = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('i', $id_carrera);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) { $carrera = $res->fetch_assoc(); }
        $stmt->close();
    }
}

if (!$carrera) { header("Location: lista_carreras.php"); exit(); }

if (empty($id_malla) && !empty($id_carrera)) {
    $mallas_disponibles = obtenerMallasPorCarrera($id_carrera);
    if (!empty($mallas_disponibles)) {
        $id_malla = intval($mallas_disponibles[0]['id_malla']);
        $codigo_malla = $mallas_disponibles[0]['codigo_malla'];
    }
}

$tipo_periodo = obtenerTipoPeriodoPorCarrera($id_carrera);
$texto_duracion = ($tipo_periodo == 'trimestre') ? 'trimestres' : 'semestres';

// --- CONSULTA CON LOS NUEVOS CAMPOS ---
if (!empty($id_malla)) {
    $query_materias = "SELECT m.*, mm.semestre, m.trayecto FROM materias m JOIN malla_materia mm ON m.id_materia = mm.id_materia WHERE mm.id_malla = " . intval($id_malla) . " ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
} else {
    $query_materias = "SELECT m.*, cm.semestre, m.trayecto FROM materias m JOIN carrera_materia cm ON m.id_materia = cm.id_materia WHERE cm.id_carrera = " . intval($id_carrera) . " ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
}

$result_materias = mysqli_query($db, $query_materias);
$materias_agrupadas = [];

// Determinar el texto a usar basado en el tipo de formación
$es_ptf = (isset($carrera['tipo_formacion']) && strtoupper($carrera['tipo_formacion']) == 'PTF');
$texto_trayecto = $es_ptf ? 'Semestre' : 'Trayecto';

while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    $nombre_grupo = '';
    
    if ($es_ptf) {
        // Para PTF: "Semestre 1", "Semestre 2", etc.
        $nombre_grupo = ($trayecto == 0) ? 'Semestre Inicial' : 'Semestre ' . $trayecto;
    } else {
        // Para otros tipos: "Trayecto 1", "Trayecto 2", etc.
        $nombre_grupo = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    }
    
    $materias_agrupadas[$nombre_grupo][] = $materia;
}

// ==========================================
// GENERACIÓN DE PDF (VERTICAL OPTIMIZADO)
// ==========================================
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
    $pdf->Cell(0, 7, to_iso('PENSUM DE ESTUDIOS: ' . mb_strtoupper($carrera['nombre_carrera'])), 0, 1, 'C');
    
    // Solo el código, sin la frase "Código de malla"
    if(!empty($codigo_malla)) {
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, to_iso($codigo_malla), 0, 1, 'C');
    }
    $pdf->Ln(5);

    foreach ($materias_agrupadas as $grupo_nombre => $materias) {
        // Verificar espacio restante antes de imprimir el encabezado del grupo
        if ($pdf->GetY() > 250) $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 7, to_iso($grupo_nombre), 1, 1, 'L', true);

        // Anchos de columna redistribuidos (Total 190mm)
        $w = [22, 70, 14, 16, 16, 16, 16, 20]; // Total: 190mm
        $pdf->SetFont('Arial', 'B', 8);
        $headers = ['CÓDIGO', 'ASIGNATURA', 'UC', 'H.T.', 'H.P.', 'H.L.', 'H.S.', 'ESTADO'];
        foreach($headers as $i => $h_text) $pdf->Cell($w[$i], 7, to_iso($h_text), 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);
        foreach ($materias as $m) {
            // Calcular altura de la fila basándose en el nombre de la materia
            $nb_lines = $pdf->GetStringWidth(to_iso($m['nombre_materia'])) > $w[1] ? 2 : 1;
            $h_fila = 6 * $nb_lines;

            // Salto de página preventivo si la fila no cabe
            if ($pdf->GetY() + $h_fila > 270) $pdf->AddPage();

            $x = $pdf->GetX();
            $y = $pdf->GetY();

            $pdf->Cell($w[0], $h_fila, to_iso($m['cod_materia']), 1, 0, 'C');
            
            // Celda multi-línea para el nombre
            $pdf->MultiCell($w[1], ($h_fila/$nb_lines), to_iso($m['nombre_materia']), 1, 'L');
            
            $pdf->SetXY($x + $w[0] + $w[1], $y);
            $pdf->Cell($w[2], $h_fila, $m['creditos'], 1, 0, 'C');
            $pdf->Cell($w[3], $h_fila, $m['horas_teoricas'], 1, 0, 'C');
            $pdf->Cell($w[4], $h_fila, $m['horas_practicas'], 1, 0, 'C');
            $pdf->Cell($w[5], $h_fila, $m['horas_laboratorio'], 1, 0, 'C');
            $pdf->Cell($w[6], $h_fila, $m['horas_semanales'], 1, 0, 'C');
            $pdf->Cell($w[7], $h_fila, ($m['activa'] ? 'Activa' : 'Inactiva'), 1, 1, 'C');
        }
        $pdf->Ln(4);
    }
    $pdf->Output('I', 'Pensum_Academico.pdf');
    exit();
}

include("includes/head.php");
?>

<!-- Estilos CSS Responsivos -->
<style>
    /* Estilos base responsivos */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            align-items: stretch !important;
            gap: 10px;
        }
        
        .header-buttons a {
            margin: 0 !important;
            text-align: center;
        }
        
        h1.h3 {
            font-size: 1.5rem;
            margin-bottom: 15px !important;
        }
        
        /* Tabla responsiva con scroll horizontal en móviles */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            min-width: 700px;
        }
        
        /* Ajuste de títulos de grupos */
        h5.font-weight-bold {
            font-size: 1.1rem;
            padding: 8px;
            background-color: #f8f9fc;
            border-left: 4px solid #4e73df;
        }
        
        /* Badges más pequeños en móvil */
        .badge {
            font-size: 0.7rem;
            padding: 4px 8px;
        }
    }
    
    @media (max-width: 480px) {
        .container-fluid {
            padding: 0 10px;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        h1.h3 {
            font-size: 1.2rem;
        }
        
        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Optimización para pantallas muy pequeñas */
        .table {
            font-size: 0.75rem;
            min-width: 650px;
        }
        
        .table th, 
        .table td {
            padding: 0.5rem 0.3rem;
        }
        
        h5.font-weight-bold {
            font-size: 0.95rem;
        }
    }
    
    /* Estilos para tablet */
    @media (min-width: 769px) and (max-width: 1024px) {
        .table {
            font-size: 0.85rem;
        }
        
        .table th, 
        .table td {
            padding: 0.6rem;
        }
        
        h1.h3 {
            font-size: 1.6rem;
        }
    }
    
    /* Estilos para desktop */
    @media (min-width: 1025px) {
        .table {
            font-size: 0.9rem;
        }
        
        .table-responsive {
            overflow: visible;
        }
    }
    
    /* Estilos generales mejorados */
    .card-header {
        border-bottom: none;
    }
    
    .table thead th {
        vertical-align: middle;
        background-color: #f8f9fc;
        font-weight: 600;
    }
    
    .table tbody tr:hover {
        background-color: #f5f5f5;
        transition: background-color 0.3s ease;
    }
    
    /* Tooltips para mejor experiencia en móvil */
    @media (hover: none) and (pointer: coarse) {
        .btn:active {
            opacity: 0.7;
            transform: scale(0.98);
        }
    }
    
    /* Mejora de contraste y legibilidad */
    .text-primary {
        color: #4e73df !important;
    }
    
    .badge-success {
        background-color: #1cc88a;
    }
    
    .badge-secondary {
        background-color: #858796;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 header-buttons" style="display: flex; flex-wrap: wrap;">
        <h1 class="h3 mb-0 text-gray-800" style="flex: 1;">Pensum: <?php echo htmlspecialchars($carrera['nombre_carrera']); ?></h1>
        <div class="d-flex gap-2" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="lista_carreras.php" class="btn btn-sm btn-primary shadow-sm no-print">
                <i class="fas fa-arrow-left"></i> <span>Volver</span>
            </a>
            <a href="?<?= $_SERVER['QUERY_STRING'] ?>&pdf=1" class="btn btn-sm btn-success shadow-sm no-print">
                <i class="fas fa-print"></i> <span>Generar PDF</span>
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-secondary text-white">
            <h6 class="m-0 font-weight-bold">Plan de Estudios Completo</h6>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle"></i> No hay materias registradas para este pensum.
                </div>
            <?php else: ?>
                <?php foreach ($materias_agrupadas as $grupo => $materias): ?>
                    <h5 class="font-weight-bold text-primary mt-4 mb-3">
                        <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($grupo) ?>
                        <span class="badge badge-secondary ml-2"><?= count($materias) ?> materias</span>
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th style="width: 12%">Código</th>
                                    <th style="width: 38%">Nombre de la Asignatura</th>
                                    <th style="width: 8%">UC</th>
                                    <th style="width: 8%">H.T.</th>
                                    <th style="width: 8%">H.P.</th>
                                    <th style="width: 8%">H.L.</th>
                                    <th style="width: 8%">H.S.</th>
                                    <th style="width: 10%">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias as $m): ?>
                                    <tr>
                                        <td class="text-center font-weight-medium"><?= htmlspecialchars($m['cod_materia']) ?></td>
                                        <td><?= htmlspecialchars($m['nombre_materia']) ?></td>
                                        <td class="text-center"><?= $m['creditos'] ?></td>
                                        <td class="text-center"><?= $m['horas_teoricas'] ?></td>
                                        <td class="text-center"><?= $m['horas_practicas'] ?></td>
                                        <td class="text-center"><?= $m['horas_laboratorio'] ?></td>
                                        <td class="text-center"><?= $m['horas_semanales'] ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-<?= $m['activa'] ? 'success' : 'secondary' ?>">
                                                <?= $m['activa'] ? 'Activa' : 'Inactiva' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                
                <!-- Leyenda informativa para móviles -->
                <div class="alert alert-info mt-4 d-block d-md-none">
                    <small>
                        <i class="fas fa-info-circle"></i> 
                        <strong>Leyenda:</strong> UC = Unidades Crédito | H.T. = Horas Teóricas | 
                        H.P. = Horas Prácticas | H.L. = Horas Laboratorio | H.S. = Horas Semanales
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script adicional para mejorar la experiencia en móviles -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detectar si es dispositivo móvil para optimizar la visualización
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        // Añadir tooltips a los encabezados de las tablas en móvil
        const tableHeaders = document.querySelectorAll('.table thead th');
        const headerTitles = {
            'UC': 'Unidades Crédito',
            'H.T.': 'Horas Teóricas',
            'H.P.': 'Horas Prácticas',
            'H.L.': 'Horas Laboratorio',
            'H.S.': 'Horas Semanales'
        };
        
        tableHeaders.forEach(header => {
            const text = header.innerText.trim();
            if (headerTitles[text]) {
                header.setAttribute('title', headerTitles[text]);
                header.style.cursor = 'help';
            }
        });
    }
    
    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>