<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

include('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');
while (ob_get_level()) { ob_end_clean(); }

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// Datos del estudiante
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();
if (!$estudiante) die('Estudiante no encontrado.');

$carr = obtenerCarreraEstudiante($id_estudiante);

// Materias aprobadas
$aprobadas = obtenerMateriasAprobadas($id_estudiante);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(20, 12, 20);

// Membrete simple
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'NOTAS CERTIFICADAS'), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nombre: ' . $estudiante['nombre']), 0, 1);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Cédula: ' . $estudiante['idusuario']), 0, 1);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Carrera: ' . ($carr['nombre_carrera'] ?? '')), 0, 1);
$pdf->Ln(6);

// Tabla de materias aprobadas
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, 7, 'N°', 1, 0, 'C');
$pdf->Cell(120, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Materia'), 1, 0);
$pdf->Cell(25, 7, 'Trayecto', 1, 0, 'C');
$pdf->Cell(30, 7, 'Estado', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$i = 1;
if (empty($aprobadas)) {
    $pdf->Cell(190, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'No se encontraron materias aprobadas.'), 1, 1);
} else {
    foreach ($aprobadas as $mat) {
        $pdf->Cell(15, 7, $i, 1, 0, 'C');
        $pdf->Cell(120, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $mat['nombre_materia']), 1, 0);
        $pdf->Cell(25, 7, $mat['trayecto'], 1, 0, 'C');
        $pdf->Cell(30, 7, 'APROBADA', 1, 1, 'C');
        $i++;
    }
}

// Firma
$pdf->Ln(12);
$pdf->SetY(-60);
$pdf->Line(60, $pdf->GetY(), 150, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Jefe de Control de Estudios'), 0, 1, 'C');

$pdf->Output('I', 'Notas_Certificadas_' . $estudiante['idusuario'] . '.pdf');
exit();
