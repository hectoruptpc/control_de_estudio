<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$estudiante = ['nombre' => '', 'idusuario' => ''];

if ($id_estudiante > 0) {
    // Ajusta esta consulta a tu estructura de base de datos
    $query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query_user);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $estudiante = $stmt->get_result()->fetch_assoc();
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE FPDF CON MEMBRETE INSTITUCIONAL
class PDF_CambioTurno extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 15, 10, 15);
        }
        $this->SetY(10);
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('DEPARTAMENTO DE CONTROL DE ESTUDIOS'), 0, 1, 'C');
        
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, txt('SOLICITUD DE CAMBIO DE TURNO'), 0, 1, 'C');
        $this->Ln(4);
    }
}

$pdf = new PDF_CambioTurno('P', 'mm', 'Letter');
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();

// --- SECCIÓN: DATOS PERSONALES DEL SOLICITANTE ---
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 5, txt('DATOS PERSONALES DEL SOLICITANTE'), 1, 1, 'C');
$pdf->Cell(35, 5, txt('FECHA'), 1, 0, 'C');
$pdf->Cell(45, 5, txt('C. I.'), 1, 0, 'C');
$pdf->Cell(105, 5, txt('APELLIDOS Y NOMBRES'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(35, 8, date('d/m/Y'), 1, 0, 'C');
$pdf->Cell(45, 8, ''.$estudiante['idusuario'], 1, 0, 'C');
$pdf->Cell(105, 8, txt(strtoupper($estudiante['nombre'])), 1, 1, 'C');

$pdf->Ln(4);

// --- SECCIÓN: MOTIVOS DEL CAMBIO ---
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 5, txt('MOTIVOS DEL CAMBIO'), 1, 1, 'C');
$pdf->SetFont('Arial', '', 9);
// Líneas para escritura manual según el formato original
for($i=0; $i<6; $i++) {
    $pdf->Cell(0, 7, '', 'LRB', 1);
}

$pdf->Ln(8);

// --- CONFIGURACIÓN DE FILAS DE FIRMAS ---
$w3 = 185 / 3; // Ancho para 3 columnas iguales
$h_box = 22;   // Altura del área de firma/sello
$h_tit = 10;   // Altura fija para los títulos de las celdas

// --- FILA 1 DE FIRMAS ---
$x1 = $pdf->GetX();
$y1 = $pdf->GetY();

$pdf->SetFont('Arial', 'B', 6);
$pdf->MultiCell($w3, 5, txt("Firma del Director del\nPNF/Carrera que cursa"), 1, 'C');
$pdf->SetXY($x1 + $w3, $y1);
$pdf->MultiCell($w3, 5, txt("Sello del PNF/Carrera\nque cursa"), 1, 'C');
$pdf->SetXY($x1 + ($w3 * 2), $y1);
$pdf->MultiCell($w3, 10, txt("Sello de Control de Estudio"), 1, 'C');

// Espacios para firmas Fila 1
$pdf->SetY($y1 + $h_tit);
$pdf->Cell($w3, $h_box, '', 1, 0);
$pdf->Cell($w3, $h_box, '', 1, 0);
$pdf->Cell($w3, $h_box, '', 1, 1);

$pdf->Ln(8);

// --- FILA 2 DE FIRMAS (CORREGIDA PARA ALINEACIÓN) ---
$x2 = $pdf->GetX();
$y2 = $pdf->GetY();

$pdf->MultiCell($w3, 5, txt("Firma del Director de\nControl de Estudio"), 1, 'C');
$pdf->SetXY($x2 + $w3, $y2);
$pdf->MultiCell($w3, 5, txt("Firma del Jefe de\nAdmisión y control"), 1, 'C');
$pdf->SetXY($x2 + ($w3 * 2), $y2);
$pdf->MultiCell($w3, 10, txt("Observaciones"), 1, 'C');

// Espacios para firmas/observaciones Fila 2
$pdf->SetY($y2 + $h_tit);
$pdf->Cell($w3, $h_box, '', 1, 0);
$pdf->Cell($w3, $h_box, '', 1, 0);
$pdf->Cell($w3, $h_box, '', 1, 1);

ob_end_clean();
$pdf->Output('I', "Cambio_Turno_".$estudiante['idusuario'].".pdf");
exit();