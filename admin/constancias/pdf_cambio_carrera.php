<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$estudiante = ['nombre' => '', 'idusuario' => ''];
$carrera_actual = ""; 
$carrera_aspira = ""; 

if ($id_estudiante > 0) {
    $query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query_user);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $estudiante = $stmt->get_result()->fetch_assoc();
    
    $carrera_data = obtenerCarreraEstudiante($id_estudiante);
    $carrera_actual = strtoupper($carrera_data['nombre_carrera']);
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE FPDF
class PDF_Cambio extends FPDF {
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
        $this->Cell(0, 5, txt('SOLICITUD DE CAMBIO DE CARRERA'), 0, 1, 'C');
        $this->Ln(4);
    }
}

$pdf = new PDF_Cambio('P', 'mm', 'Letter');
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();

// --- TABLA DE IDENTIFICACIÓN ---
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35, 6, txt('FECHA'), 1, 0, 'C');
$pdf->Cell(45, 6, txt('C. I.'), 1, 0, 'C');
$pdf->Cell(105, 6, txt('APELLIDOS Y NOMBRES'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(35, 8, date('d/m/Y'), 1, 0, 'C');
$pdf->Cell(45, 8, ''.$estudiante['idusuario'], 1, 0, 'C');
$pdf->Cell(105, 8, txt(strtoupper($estudiante['nombre'])), 1, 1, 'C');

$pdf->Ln(5);

// --- CUERPO DE CARRERAS ---
$carreras = [
    "CARRERA MECÁNICA AUTOMOTRIZ",
    "CARRERA MECÁNICA TÉRMICA",
    "PNF MECÁNICA",
    "PNF MANTENIMIENTO",
    "PNF MATERIALES INDUSTRIALES",
    "PNF INFORMÁTICA",
    "PNF DISTRIBUCIÓN Y LOGÍSTICA"
];

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(75, 5, txt('Indique PNF/Carrera que Cursa'), 1, 0, 'L');
$pdf->Cell(17, 5, txt('Marcar'), 1, 0, 'C');
$pdf->Cell(75, 5, txt('Indique PNF/Carrera que aspira cambio'), 1, 0, 'L');
$pdf->Cell(18, 5, txt('Marcar'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 7);
foreach ($carreras as $c) {
    $mark_actual = (trim($carrera_actual) == $c) ? "X" : "";
    $pdf->Cell(75, 5, txt($c), 1, 0, 'L');
    $pdf->Cell(17, 5, $mark_actual, 1, 0, 'C');
    
    $mark_aspira = (trim($carrera_aspira) == $c) ? "X" : "";
    $pdf->Cell(75, 5, txt($c), 1, 0, 'L');
    $pdf->Cell(18, 5, $mark_aspira, 1, 1, 'C');
}

$pdf->Ln(5);

// --- SECCIÓN DE FIRMAS Y SELLOS (AJUSTADA) ---
$pdf->SetFont('Arial', 'B', 6); 
$w_box = 46.25; 
$h_text = 4; // Altura de línea para MultiCell
$h_total_title = 8; // Altura total reservada para el título
$h_firma = 20; // Espacio para el sello/firma real

$x_start = $pdf->GetX();
$y_start = $pdf->GetY();

// Bloque 1
$pdf->MultiCell($w_box, $h_text, txt("Firma del Director del\nPNF/Carrera que cursa"), 1, 'C');
$pdf->SetXY($x_start + $w_box, $y_start);

// Bloque 2
$pdf->MultiCell($w_box, $h_text, txt("Sello del PNF/Carrera\nque cursa"), 1, 'C');
$pdf->SetXY($x_start + ($w_box * 2), $y_start);

// Bloque 3
$pdf->MultiCell($w_box, $h_text, txt("Firma del\nOrientador"), 1, 'C');
$pdf->SetXY($x_start + ($w_box * 3), $y_start);

// Bloque 4
$pdf->MultiCell($w_box, $h_text, txt("Sello de Control de\nEstudio"), 1, 'C');

// Dibujar cuadros de firma abajo
$pdf->SetY($y_start + $h_total_title);
$pdf->Cell($w_box, $h_firma, '', 1, 0);
$pdf->Cell($w_box, $h_firma, '', 1, 0);
$pdf->Cell($w_box, $h_firma, '', 1, 0);
$pdf->Cell($w_box, $h_firma, '', 1, 1);

$pdf->Ln(5);

// --- SECCIÓN FINAL ---
$x_final = $pdf->GetX();
$y_final = $pdf->GetY();

$pdf->MultiCell(60, 4, txt("Firma del Director de\nControl de Estudio"), 1, 'C');
$pdf->SetXY($x_final + 60, $y_final);
$pdf->Cell(125, 8, txt("Observaciones"), 1, 1, 'C');

$pdf->SetX($x_final);
$pdf->Cell(60, 25, '', 1, 0);
$pdf->Cell(125, 25, '', 1, 1);

ob_end_clean();
$pdf->Output('I', "Solicitud_Cambio_Carrera.pdf");
exit();