<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);
$tipo_reporte = isset($_GET['tipo']) ? strtolower($_GET['tipo']) : 'tsu';

// 1. OBTENCIÓN DE DATOS
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$cedula_estudiante = $estudiante['idusuario']; 
$carrera = obtenerCarreraEstudiante($id_estudiante);

// Lógica de distinción de títulos
if ($tipo_reporte == 'ing') {
    $titulo_obtener = "INGENIERO";
} else {
    $titulo_obtener = "TÉCNICO SUPERIOR UNIVERSITARIO";
}

// Función para obtener la fecha actual en formato del membrete
function obtenerFechaMembrete() {
    return date('d/m/Y');
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

function fechaCarta() {
    $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
    $d = (int)date('d');
    $m = (int)date('m') - 1;
    $y = date('Y');
    
    $dia_texto = ($d < 10) ? "0$d" : "$d";
    $anio_letras = ($y == "2026") ? "dos mil veintiséis" : "dos mil veinticinco";
    
    return "en la ciudad de Puerto Cabello, a los $d ($dia_texto) días del mes de " . $meses[$m] . " del año $anio_letras ($y)";
}

// 2. CLASE FPDF CON MEMBRETE INTEGRADO
class PDF_Culminacion extends FPDF {
    function Header() {
        // Obtener fecha actual
        $fecha = date('d/m/Y');
        
        // Logo en la izquierda
        if(file_exists('../../images/uptpc.png')) {
            $this->Image('../../images/uptpc.png', $this->lMargin, 10, 20);
        }
        
        // Textos del membrete (alineados al centro)
        $this->SetY(10);
        $this->SetFont('Arial', 'B', 10); // Reducido de 12 a 10 como en la función JS
        $this->Cell(0, 5, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 5, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 5, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        
        // Fecha alineada a la derecha
        $this->SetFont('Arial', '', 9); // Reducido para la fecha
        $this->SetXY($this->lMargin, 10);
        $this->Cell($this->GetPageWidth() - ($this->lMargin + $this->rMargin), 5, txt($fecha), 0, 0, 'R');
        
        // Línea separadora debajo del membrete
        $this->SetY(28);
        $this->SetDrawColor(0, 0, 0);
        $this->Line($this->lMargin, $this->GetY(), $this->GetPageWidth() - $this->rMargin, $this->GetY());
        
        // Título del documento con espacio después del membrete
        $this->Ln(15);
        $this->SetFont('Arial', 'BU', 11);
        $this->Cell(0, 5, txt('CARTA DE CULMINACIÓN'), 0, 1, 'C');
        $this->SetTextColor(0);
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-30);
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt("Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello Rif:G-20005608-8"), 0, 1, 'C');
        $this->Cell(0, 3, txt("Correo Electrónico: uptpccontroldeestudios03@gmail.com  uptpcsecretariacgu@gmail.com"), 0, 1, 'C');
        $this->Ln(2);
        $this->Cell(50, 4, txt("Universidad Politécnica Territorial de Puerto Cabello"), 0, 0, 'L');
        $this->Cell(0, 4, txt("Página (s): ") . $this->PageNo(), 0, 1, 'R');
    }
}

// 3. GENERACIÓN
$pdf = new PDF_Culminacion('P', 'mm', 'Letter');
$pdf->SetMargins(25, 20, 25);
$pdf->AddPage();

$pdf->SetFont('Arial', '', 11);

// Texto de Introducción
$pdf->Write(7, txt("Quien suscribe, "));
$pdf->SetFont('Arial', 'I', 11); 
$pdf->Write(7, txt("Dra. Blanca Crespo C."));
$pdf->SetFont('Arial', '', 11); 
$pdf->Write(7, txt(", titular de la cédula de identidad "));
$pdf->SetFont('Arial', 'B', 11); 
$pdf->Write(7, txt("V-10.959.330"));
$pdf->SetFont('Arial', '', 11); 
$pdf->Write(7, txt(", Secretario del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano(a):"));

$pdf->Ln(20);

// Nombre del Estudiante Centrado
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, txt(strtoupper($estudiante['nombre'])), 0, 1, 'C');

$pdf->Ln(10);

// Cuerpo de la carta
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 7, txt("Titular de la Cédula de Identidad V-") . $cedula_estudiante . txt(", Quien cursó y aprobó todas las asignaturas del Plan de estudios del Programa Nacional de Formación en ") . strtoupper($carrera['nombre_carrera']) . txt(" para obtener el Título de:"), 0, 'J');

$pdf->Ln(15);

// Título a obtener Centrado y Negrita
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, txt($titulo_obtener), 0, 1, 'C');

$pdf->Ln(15);

// Fecha de emisión
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 7, txt("Documento que se emite ") . fechaCarta(), 0, 'J');

// Firma con línea
$pdf->Ln(20); // Reducido de 35 a 20 para dejar espacio para la línea de firma

// Línea de firma (aproximadamente 80mm de ancho, centrada)
$lineWidth = 80;
$pageWidth = $pdf->GetPageWidth();
$lineX = ($pageWidth - $lineWidth) / 2;
$lineY = $pdf->GetY();

// Dibujar la línea de firma
$pdf->SetDrawColor(0, 0, 0); // Color negro
$pdf->SetLineWidth(0.5); // Grosor de la línea
$pdf->Line($lineX, $lineY, $lineX + $lineWidth, $lineY);

// Espacio después de la línea
$pdf->Ln(8);

// Información de la firmante
$pdf->SetFont('Arial', 'BI', 10);
$pdf->Cell(0, 4, txt("Dra. Blanca A. Crespo C."), 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, txt("Secretario del Consejo de Gestión Universitaria"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Resolución N° 34 de fecha 20/07/2022 Gaceta Oficial República"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Bolivariana de Venezuela N° 457.753 de fecha 22/07/2022"), 0, 1, 'C');

ob_end_clean();
$pdf->Output('I', "Carta_Culminacion_" . $cedula_estudiante . ".pdf");
?>