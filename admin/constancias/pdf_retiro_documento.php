<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS (Simulados basándonos en la imagen)
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$estudiante = [
    'nombre' => 'NUNES ATHALIDO ANGEL ENRIQUE',
    'cedula' => '30.864.989',
    'carrera' => 'LOGISTICA DISTRIBUCION',
    'lapso' => '2025'
];

// 2. FUNCIÓN DE CONVERSIÓN DE TEXTO
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 3. CLASE FPDF CON PIE DE PÁGINA ESPECÍFICO
class PDF_Retiro extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 20, 15, 18);
        }
        $this->SetY(15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, txt('CONSTANCIA DE RETIRO DE DOCUMENTO'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-35);
        $this->SetFont('Arial', 'BI', 8);
        $this->Cell(0, 4, txt('TRANSFORMACIÓN UNIVERSITARIA CON CALIDAD Y PERTENENCIA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt('Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello'), 0, 1, 'C');
        $this->Cell(0, 3, txt('Número Telefónico: (0242) 3700494. Correo Electrónico:'), 0, 1, 'C');
        $this->SetTextColor(0, 0, 255);
        $this->Cell(0, 3, txt('uptpccontroldeestudios03@gmail.com'), 0, 1, 'C');
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 5, txt('Página 1'), 0, 0, 'R');
    }
}

// 4. GENERACIÓN DEL DOCUMENTO
$pdf = new PDF_Retiro('P', 'mm', 'Letter');
$pdf->SetMargins(25, 20, 25);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

$lh = 8; // Altura de línea

// Párrafo de apertura
$pdf->Write($lh, txt("Quien suscribe "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("Dra. Zorangel E. Aponte Q."));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(", titular de la cédula de identidad "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("V.- 7.153.528"));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" Jefa de Control de Estudios de nuestra Institución, hace constar que el (la) Ciudadano (a) que se menciona a continuación."));

$pdf->Ln(15);

// Datos del Estudiante (Centrados)
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, txt($estudiante['nombre']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, txt("Titular de la Cédula de Identidad V-" . $estudiante['cedula'] . ", inscrita en esta casa de"), 0, 1, 'C');
$pdf->Cell(0, 8, txt("estudios y es cursante del Programa Nacional Formación en:"), 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, txt($estudiante['carrera']), 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt("Para el lapso académico y su vigencia corresponde, "));
$pdf->SetFont('Arial', 'B', 11);
// Recuadro para el tipo de documento retirado
$pdf->Cell(95, 8, txt(" RETIRO DE DOCUMENTOS DE INSCRIPCIÓN COPIAS. EXPEDIENTE COMPLETO."), 1, 1, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt("Solicita traslado de la misma, documento que se emite en la ciudad de Puerto Cabello, a los "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("CATORCE (14)"));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" días del mes de "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("Mayo"));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" del año "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("dos mil veinticinco (2025)."));

$pdf->Ln(30);

// Firma de la Jefa de Control de Estudios
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, txt('Dra. Zorangel E. Aponte Q.'), 0, 1, 'C');
$pdf->Cell(0, 5, txt('Jefe de Control de Estudios'), 0, 1, 'C');
$pdf->Cell(0, 5, txt('Resolución N° 01 de fecha 17/10/2022 Consejo N° 7'), 0, 1, 'C');
$pdf->Cell(0, 5, txt('De fecha 17/10/2022'), 0, 1, 'C');

ob_end_clean();
$pdf->Output('I', "Retiro_Documento.pdf");
exit();