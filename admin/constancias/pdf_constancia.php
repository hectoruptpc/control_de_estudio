<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// 1. OBTENCIÓN DE DATOS
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$cedula = $estudiante['idusuario'];
$nombre_estudiante = strtoupper($estudiante['nombre']);
$carrera_data = obtenerCarreraEstudiante($id_estudiante);
$nombre_carrera = strtoupper($carrera_data['nombre_carrera']);

// Variables de ejemplo (puedes conectarlas a tu lógica de promedios)
$puesto = "2"; 
$total_promocion = "11";
$indice_personal = "18,35";
$indice_promocion = "16,36";

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

/**
 * Función de fecha actualizada y dinámica
 */
function fechaConstancia() {
    $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
    
    $d = date('d');
    $m = (int)date('m') - 1;
    $y = date('Y');
    
    // Convertir año a letras de forma sencilla
    $anios_letras = [
        "2025" => "dos mil veinticinco",
        "2026" => "dos mil veintiséis",
        "2027" => "dos mil veintisiete"
    ];
    $anio_texto = isset($anios_letras[$y]) ? $anios_letras[$y] : "dos mil $y";
    
    $dia_texto = ($d == "01") ? "un (01)" : "$d ($d)";
    
    return "en la ciudad de Puerto Cabello, a los $dia_texto días del mes de " . $meses[$m] . " del año " . $anio_texto . " ($y).";
}

// 2. CLASE FPDF
class PDF_Constancia extends FPDF {
    function Header() {
        if(file_exists('../../images/uptpc.png')) {
            $this->Image('../../images/uptpc.png', 20, 10, 18);
        }
        $this->SetY(10);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3.5, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3.5, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3.5, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3.5, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, txt('CONSTANCIAS'), 0, 1, 'C');
        $this->Ln(10);
    }
}

// 3. GENERACIÓN
$pdf = new PDF_Constancia('P', 'mm', 'Letter');
$pdf->SetMargins(25, 20, 25);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->MultiCell(0, 7, txt("Quien suscribe, Dra. Blanca A. Crespo C., titular de la cédula de identidad V-10.959.330, Secretaria del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano(a):"), 0, 'J');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 10, txt($nombre_estudiante), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Write(7, txt("Titular de la Cédula de Identidad "));
$pdf->SetFont('Arial', 'B', 12); $pdf->Write(7, txt("V-$cedula"));
$pdf->SetFont('Arial', '', 12); $pdf->Write(7, txt(" cursó estudio obteniendo el Técnico Superior Universitario en el Programa Nacional de Formación en:"));

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, txt($nombre_carrera), 0, 1, 'C');
$pdf->Ln(10);

// Párrafo con datos subrayados
$pdf->SetFont('Arial', '', 12);
$pdf->Write(7, txt("Así mismo se Certifica que obtuvo el "));
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(15, 6, $puesto, 'B', 0, 'C');
$pdf->SetFont('Arial', '', 12); $pdf->Write(7, txt(" lugar entre los "));
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(15, 6, $total_promocion, 'B', 0, 'C');
$pdf->SetFont('Arial', '', 12); $pdf->Write(7, txt(" de su promoción, su índice de rendimiento académico fue de "));
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(20, 6, $indice_personal, 'B', 0, 'C');
$pdf->SetFont('Arial', '', 12); $pdf->Write(7, txt(" y el de su promoción fue de "));
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(20, 6, $indice_promocion, 'B', 0, 'C');
$pdf->Write(7, ".");

$pdf->Ln(20);
$pdf->MultiCell(0, 7, txt("Documento que se emite " . fechaConstancia()), 0, 'J');

// Firma
$pdf->Ln(30);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 4, txt("DRA. BLANCA A. CRESPO C."), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, txt("Secretaria del Consejo de Gestión Universitaria"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Resolución N° 34 de fecha 20/07/2022 Gaceta Oficial República"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Bolivariana de Venezuela N° 457.753 de fecha 22/07/2022"), 0, 1, 'C');

ob_end_clean();
$pdf->Output('I', "Constancia_$cedula.pdf");