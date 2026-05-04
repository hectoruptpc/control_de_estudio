<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

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

if (!$estudiante) die('Estudiante no encontrado.');

$cedula = $estudiante['idusuario'];
$carrera = obtenerCarreraEstudiante($id_estudiante);

// Datos del curso intensivo (Estos valores deberían venir de tu base de datos)
$lapso = "2025-1";
$materia_intensivo = "MATEMÁTICA II";
$fecha_inicio = "04-08-2025";
$fecha_fin = "29-08-2025";

/**
 * Función para convertir texto a codificación ISO para FPDF
 */
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

/**
 * Función para los datos de la fecha con negritas
 */
function obtenerDatosFecha() {
    $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
    $dias_letras = [
        1 => "Primero", 2 => "dos", 3 => "tres", 4 => "cuatro", 5 => "cinco",
        6 => "seis", 7 => "siete", 8 => "ocho", 9 => "nueve", 10 => "diez",
        11 => "once", 12 => "doce", 13 => "trece", 14 => "catorce", 15 => "quince",
        16 => "dieciséis", 17 => "diecisiete", 18 => "dieciocho", 19 => "diecinueve", 20 => "veinte",
        21 => "veintiuno", 22 => "veintidós", 23 => "veintitrés", 24 => "veinticuatro", 25 => "veinticinco",
        26 => "veintiséis", 27 => "veintisiete", 28 => "veintiocho", 29 => "veintinueve", 30 => "treinta",
        31 => "treinta y uno"
    ];

    $d = (int)date('d');
    $m = $meses[(int)date('m') - 1];
    $y = date('Y');
    $anio_letras = ($y == "2026") ? "dos mil veintiséis" : "dos mil " . $y;

    return [
        'dia_txt' => $dias_letras[$d] . " (" . str_pad($d, 2, "0", STR_PAD_LEFT) . ")",
        'mes' => $m,
        'anio_txt' => $anio_letras . " ($y)"
    ];
}

// 2. CLASE FPDF
class PDF_Intensivo extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 25, 12, 18);
        }
        $this->SetY(12);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 4, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 4, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 5, txt('CONSTANCIA DE ESTUDIOS DE INTENSIVO'), 0, 1, 'C');
        $this->Ln(12);
    }

    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(0, 4, txt("Este documento No es Valido sin la firma y sello del Departamento de Control de Estudios"), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt("Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello"), 0, 1, 'C');
        $this->Cell(0, 3, txt("Correo Electrónico: uptpccontroldeestudios03@gmail.com"), 0, 1, 'C');
    }
}

// 3. GENERACIÓN
$pdf = new PDF_Intensivo('P', 'mm', 'Letter');
$pdf->SetMargins(25, 25, 25);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Texto introductorio
$pdf->Write(6, txt("Quien suscribe "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt("Dra. Zorangel E. Aponte Q."));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", titular de la cédula de identidad "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt("V.-7.153.528"));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(". Jefe de Control de Estudio de nuestra Institución, hace constar que el (la) Ciudadano (a) que se menciona a continuación."));

$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, txt(strtoupper($estudiante['nombre'])), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 11);
$pdf->Write(6, txt("Titular de la Cédula de Identidad "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt("V-$cedula"));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(", se encuentra inscrito en esta casa de estudios y es cursante del Programa Nacional de Formación en:"));

$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, txt(strtoupper($carrera['nombre_carrera'])), 0, 1, 'C');
$pdf->Ln(10);

// Detalles del intensivo
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, txt("Para el lapso académico $lapso, para cursar el curso intensivo 2025 en la unidad curricular $materia_intensivo el cual inició el $fecha_inicio y finaliza el $fecha_fin."), 0, 'J');

// --- Fecha con Negritas ---
$datosFecha = obtenerDatosFecha();
$pdf->Ln(15);
$pdf->SetFont('Arial', '', 11);

$texto_fecha = "Documento que se emite en la ciudad de Puerto Cabello, a los " . $datosFecha['dia_txt'] . " día del mes de " . $datosFecha['mes'] . " del año " . $datosFecha['anio_txt'] . ".";
$w = $pdf->GetStringWidth(txt($texto_fecha));
$pdf->SetX(($pdf->GetPageWidth() - $w) / 2);

$pdf->Write(6, txt("Documento que se emite en la ciudad de Puerto Cabello, a los "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt($datosFecha['dia_txt']));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(" día del mes de "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt($datosFecha['mes']));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt(" del año "));
$pdf->SetFont('Arial', 'B', 11); $pdf->Write(6, txt($datosFecha['anio_txt']));
$pdf->SetFont('Arial', '', 11); $pdf->Write(6, txt("."));

// Firma
$pdf->Ln(35);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, txt("Dra. Zorangel E. Aponte Q."), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, txt("Jefe de Control de Estudios"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("Resolución N° 07-2022 de fecha 01/11/2022 Consejo N°07"), 0, 1, 'C');
$pdf->Cell(0, 4, txt("De fecha 01/11/2022"), 0, 1, 'C');

ob_end_clean();
$pdf->Output('I', "Constancia_Intensivo_$cedula.pdf");
exit();