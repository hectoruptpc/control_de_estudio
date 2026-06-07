<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS DEL ESTUDIANTE
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$estudiante = ['nombre' => '', 'idusuario' => '', 'f_nac' => '', 'lugar_nac' => '', 'correo' => '', 'direccion' => '', 'telefono' => ''];

if ($id_estudiante > 0) {
    // Aquí deberías ajustar la consulta según las columnas reales de tu tabla 'users'
    $query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query_user);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $estudiante = $stmt->get_result()->fetch_assoc();
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE FPDF PERSONALIZADA
class PDF_Inscripcion extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 15, 12, 18);
        }
        $this->SetY(12);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('DEPARTAMENTO DE CONTROL DE ESTUDIOS'), 0, 1, 'C');
        
        $this->Ln(8);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, txt('INSCRIPCIONES PASANTIAS Y PROYECTO'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(0, 4, txt("Este documento No es Valido sin la firma y sello del Departamento de Control de Estudios"), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt("Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello"), 0, 1, 'C');
        $this->Cell(0, 3, txt("Correo Electrónico: uptpccontroldeestudios03@gmail.com"), 0, 1, 'C');
        $this->Cell(0, 3, txt("Página (S): 1"), 0, 1, 'C');
    }
}

// 3. GENERACIÓN DEL DOCUMENTO
$pdf = new PDF_Inscripcion('P', 'mm', 'Letter');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// Fila 1: Fecha, C.I., Apellidos y Nombres
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(35, 6, txt('FECHA'), 1, 0, 'C', true);
$pdf->Cell(45, 6, txt('C. I.'), 1, 0, 'C', true);
$pdf->Cell(105, 6, txt('APELLIDOS Y NOMBRES'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(35, 8, date('d/m/Y'), 1, 0, 'C');
$pdf->Cell(45, 8, 'V-'.$estudiante['idusuario'], 1, 0, 'C');
$pdf->Cell(105, 8, txt(strtoupper($estudiante['nombre'])), 1, 1, 'C');

$pdf->Ln(5);

// Bloque de Datos Personales Detallados
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 6, txt('Fecha de Nacimiento:'), 'LTR', 0, 'L', true);
$pdf->Cell(65, 6, txt('Lugar de Nacimiento'), 'TR', 0, 'L', true);
$pdf->Cell(70, 6, txt('Correo Electrónico'), 'TR', 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 8, txt($estudiante['f_nac']), 'LRB', 0, 'L');
$pdf->Cell(65, 8, txt($estudiante['lugar_nac']), 'RB', 0, 'L');
$pdf->Cell(70, 8, txt($estudiante['correo']), 'RB', 1, 'L');

// Fila: Dirección y Teléfono
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(115, 6, txt('Dirección de Habitación'), 'LR', 0, 'L', true);
$pdf->Cell(70, 6, txt('Teléfono'), 'R', 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(115, 8, txt($estudiante['direccion']), 'LRB', 0, 'L');
$pdf->Cell(70, 8, txt($estudiante['telefono']), 'RB', 1, 'L');

// Fila: Datos de la Empresa
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(85, 6, txt('Nombre de la Empresa'), 'LR', 0, 'L', true);
$pdf->Cell(100, 6, txt('Dirección de la Empresa'), 'R', 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(85, 10, '', 'LRB', 0, 'L'); // Espacio para llenar
$pdf->Cell(100, 10, '', 'RB', 1, 'L');

// Fila Final: Firmas
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(85, 6, txt('Recibido y Procesado Por'), 'LR', 0, 'C', true);
$pdf->Cell(100, 6, txt('Sello y Firma control de Estudios'), 'R', 1, 'C', true);

$pdf->Cell(85, 20, '', 'LRB', 0, 'C');
$pdf->Cell(100, 20, '', 'RB', 1, 'C');

ob_end_clean();
$pdf->Output('I', "Inscripcion_Pasantias_".$estudiante['idusuario'].".pdf");
exit();