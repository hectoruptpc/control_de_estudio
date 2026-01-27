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

// Determinar si aprobó proyecto socio (buscar en todos los trayectos)
$aprobado = false;
for ($t=0;$t<=4;$t++) {
    if (haAprobadoProyectoSocio($id_estudiante, $t)) { $aprobado = true; break; }
}

// Obtener soporte del proyecto si existe
$soporte = null;
if ($aprobado) {
    $sql = "SELECT nd.soporte, nd.tipo_archivo, nd.fecha_registro, m.nombre_materia
            FROM notas_definitivas nd
            INNER JOIN materias m ON nd.id_materia = m.id_materia
            WHERE nd.id_usuario = ? AND m.es_proyecto_socio = 1
            ORDER BY nd.fecha_registro DESC LIMIT 1";
    $st = $db->prepare($sql);
    $st->bind_param('i', $id_estudiante);
    $st->execute();
    $r = $st->get_result();
    if ($row = $r->fetch_assoc()) {
        $soporte = $row;
    }
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(20, 12, 20);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CONSTANCIA DE CULMINACIÓN DE SERVICIO COMUNITARIO'), 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Se hace constar que: "), 0, 'L');
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', mb_strtoupper($estudiante['nombre'])), 0, 1);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, 'Cédula: ' . $estudiante['idusuario'], 0, 1);
$pdf->Cell(0, 6, 'Carrera: ' . (obtenerCarreraEstudiante($id_estudiante)['nombre_carrera'] ?? ''), 0, 1);
$pdf->Ln(6);

if (!$aprobado) {
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "No se ha detectado aprobación del Proyecto/Servicio Comunitario en el expediente del estudiante. Por tanto, no es posible expedir esta constancia."), 0, 'J');
    $pdf->Output('I', 'Constancia_Servicio_Comunitario_' . $estudiante['idusuario'] . '.pdf');
    exit();
}

$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Por medio de la presente se certifica que el/la estudiante ha cumplido y aprobado la actividades relacionadas con el Proyecto/Servicio Comunitario requeridas por la carrera."), 0, 'J');
$pdf->Ln(6);

if ($soporte && !empty($soporte['soporte'])) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, 'Soporte registrado en sistema:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, 'Archivo: ' . $soporte['soporte'] . '  -  Fecha: ' . $soporte['fecha_registro'], 0, 'L');
    $pdf->Ln(6);
} else {
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, 'No se encontró archivo de culminación asociado en el expediente. Si considera que esto es un error, contacte a la oficina de Control de Estudios.', 0, 'J');
    $pdf->Ln(6);
}

// Firma
$pdf->SetY(-60);
$pdf->Line(60, $pdf->GetY(), 150, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Jefe de Control de Estudios'), 0, 1, 'C');

$pdf->Output('I', 'Constancia_Servicio_Comunitario_' . $estudiante['idusuario'] . '.pdf');
exit();
