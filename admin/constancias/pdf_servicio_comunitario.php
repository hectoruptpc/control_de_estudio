<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');

// 1. VALIDACIÓN Y OBTENCIÓN DE DATOS
if (!isset($_GET['id'])) { 
    die("ID de estudiante no proporcionado."); 
}

$id_estudiante = intval($_GET['id']);

// Consulta de datos del estudiante
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) {
    die('Estudiante no encontrado en la base de datos.');
}

$cedula_estudiante = $estudiante['idusuario']; 
// Asumimos que esta función existe en tu functions.php para traer el nombre de la carrera
$carrera = obtenerCarreraEstudiante($id_estudiante); 

/**
 * Función para convertir texto a codificación ISO para FPDF
 */
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

/**
 * Genera la fecha en el formato específico del Acta
 */
function fechaComunitaria() {
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    $d = (int)date('d');
    $m = (int)date('m') - 1;
    $y = date('Y');
    
    // Formato: Primero (01) o el número normal
    $dia_str = ($d == 1) ? "Primero (01)" : "$d (" . str_pad($d, 2, "0", STR_PAD_LEFT) . ")";
    
    // Conversión de año a letras (ajustar según necesidad)
    if ($y == "2025") $anio_letras = "dos mil veinticinco";
    elseif ($y == "2026") $anio_letras = "dos mil veintiséis";
    else $anio_letras = "dos mil " . $y;
    
    return "En Puerto Cabello, a los $dia_str días del mes de " . $meses[$m] . " del año $anio_letras ($y).";
}

// 2. EXTENSIÓN DE CLASE FPDF PARA CABECERA Y PIE
class PDF_Comunitario extends FPDF {
    function Header() {
        // Logo de la UPT PC
        if(file_exists('../../images/uptpc.png')) {
            $this->Image('../../images/uptpc.png', 25, 12, 18);
        }
        
        $this->SetY(12);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 4, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 5, txt('ACTA DE CULMINACIÓN DE SERVICIO COMUNITARIO'), 0, 1, 'C');
        $this->Ln(15);
    }

    function Footer() {
        // Pie de página institucional
        $this->SetY(-35);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(80, 80, 80);
        $this->MultiCell(0, 3.5, txt("Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello Rif:G-20005608-8\nNúmero Telefónico: (0242) 3700494. Correo Electrónico: uptpccontroldeestudios03@gmail.com\nuptpcsecretariacgu@gmail.com uptpcpuertocabello@gmail.com"), 0, 'C');
    }
}

// 3. CONFIGURACIÓN Y GENERACIÓN DEL DOCUMENTO
$pdf = new PDF_Comunitario('P', 'mm', 'Letter');
$pdf->SetMargins(25, 25, 25); // Márgenes anchos según la imagen
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// --- Párrafo Principal ---
$pdf->SetFont('Arial', '', 11);
$pdf->Write(6, txt("Quienes suscriben, "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt("Dra. Crespo C. Blanca A."));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", cédula de identidad N° "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt("V-10.959.330"));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", Secretaria del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello, hacen constar que el(la) Bachiller: "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt(strtoupper($estudiante['nombre'])));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", cédula de identidad N° "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt($cedula_estudiante));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", estudiante regular del Programa Nacional de Formación en "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt(strtoupper($carrera['nombre_carrera'])));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", cumplió con la ejecución de ciento veinte (120) horas de actividades de Servicio Comunitario según el plan de trabajo y metas establecidas en el Proyecto."));

$pdf->Ln(15);

// --- Segundo Párrafo ---
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, txt("Igualmente, se certifica un desempeño satisfactorio conforme a lo establecido en los artículos 8 y 18, de la Ley de Servicio Comunitario del Estudiante de Educación Superior (Gaceta Oficial 38.272 de fecha 14-09-05)"), 0, 'J');

$pdf->Ln(20);

// --- Fecha ---
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 10, txt(fechaComunitaria()), 0, 1, 'C');

$pdf->Ln(25);

// --- Firma Central ---
$pdf->SetFont('Arial', 'B', 10);

// Dibujar línea de firma centrada
$ancho_linea = 85;
$x_inicio = ($pdf->GetPageWidth() - $ancho_linea) / 2;
$y_actual = $pdf->GetY();
$pdf->Line($x_inicio, $y_actual, $x_inicio + $ancho_linea, $y_actual);

$pdf->Ln(2);
$pdf->Cell(0, 5, txt("Dra. Blanca A. Crespo C."), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, txt("Secretario del Consejo de Gestión Universitaria"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Resolución N° 34 de fecha 20/07/2022 Gaceta Oficial República"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Bolivariana de Venezuela N° 457.753 de fecha 22/07/2022"), 0, 1, 'C');

// Finalización
ob_end_clean();
$pdf->Output('I', "Servicio_Comunitario_" . $cedula_estudiante . ".pdf");
exit();