<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. VALIDACIÓN DE DATOS (Este formulario suele ser para un grupo o vacío para llenar)
$fecha_hoy = date('d/m/Y');

/**
 * Función para convertir texto a codificación ISO para FPDF
 */
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE FPDF PERSONALIZADA
class PDF_Extraordinaria extends FPDF {
    function Header() {
        // Logo
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 10, 10, 18);
        }
        
        // Membrete
        $this->SetY(10);
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 4, txt('DIRECCIÓN DE PNF Y/O PTF'), 0, 1, 'C');
        $this->Cell(0, 4, txt('SOLICITUD DE EVALUACIÓN EXTRAORDINARIA Y/O EXAMEN DE SUFICIENCIA'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 6, txt('FECHA: ________/________/________'), 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        // Espacio para Control de Estudios al final
        $this->SetY(-20);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 10, txt('Control de Estudios Firma y Sello'), 0, 0, 'L');
    }
}

// 3. GENERACIÓN DEL DOCUMENTO
// Usamos orientación 'P' (Vertical) pero el formato de la imagen sugiere que es un cuadro ancho
$pdf = new PDF_Extraordinaria('P', 'mm', 'Letter');
$pdf->SetMargins(10, 15, 10);
$pdf->AddPage();

// Estilo de la tabla
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont('Arial', 'B', 7);

// Encabezados de la tabla
$h = 7; // Altura de celda
$pdf->Cell(8, $h, txt('N°'), 1, 0, 'C', true);
$pdf->Cell(25, $h, txt('Cédula'), 1, 0, 'C', true);
$pdf->Cell(40, $h, txt('Apellidos'), 1, 0, 'C', true);
$pdf->Cell(40, $h, txt('Nombres'), 1, 0, 'C', true);
$pdf->Cell(25, $h, txt('PNF/PTF'), 1, 0, 'C', true);
$pdf->Cell(58, $h, txt('Unidad Curricular'), 1, 1, 'C', true);

// Filas en blanco (puedes hacer un loop con datos de la DB si los tienes)
$pdf->SetFont('Arial', '', 7);
for ($i = 1; $i <= 15; $i++) {
    $pdf->Cell(8, 6, $i, 1, 0, 'C');
    $pdf->Cell(25, 6, '', 1, 0, 'C');
    $pdf->Cell(40, 6, '', 1, 0, 'C');
    $pdf->Cell(40, 6, '', 1, 0, 'C');
    $pdf->Cell(25, 6, '', 1, 0, 'C');
    $pdf->Cell(58, 6, '', 1, 1, 'C');
}

$pdf->Ln(10);

// Sección de firmas
$pdf->SetFont('Arial', 'B', 8);

// Definir coordenadas para las celdas de firmas
$y_firma = $pdf->GetY();

// Cuadro Firma Estudiante
$pdf->Rect(10, $y_firma, 60, 20);
$pdf->SetXY(10, $y_firma);
$pdf->Cell(60, 5, txt('Firma Estudiante'), 0, 0, 'C');

// Cuadro Firma Director
$pdf->Rect(75, $y_firma, 60, 20);
$pdf->SetXY(75, $y_firma);
$pdf->Cell(60, 5, txt('Firma Director'), 0, 0, 'C');

// Cuadro Observación
$pdf->Rect(140, $y_firma, 66, 20);
$pdf->SetXY(140, $y_firma);
$pdf->Cell(66, 5, txt('Observación'), 0, 0, 'C');

ob_end_clean();
$pdf->Output('I', "Solicitud_Evaluacion_Extraordinaria.pdf");
exit();