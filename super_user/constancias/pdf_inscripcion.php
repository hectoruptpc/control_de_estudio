<?php
ob_start(); 
error_reporting(0);
ini_set('display_errors', 0);

include('../../funciones/functions.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// 1. Datos del estudiante y carrera
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();
$carrera = obtenerCarreraEstudiante($id_estudiante);

// 2. Datos de Periodos
$query_periodo = "SELECT fecha_inicio, fecha_fin FROM periodos_academicos ORDER BY id_periodo DESC LIMIT 1";
$res_p = mysqli_query($db, $query_periodo);

$meses = ["ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
$texto_vigencia = "LAPSO NO DISPONIBLE";

if ($res_p && mysqli_num_rows($res_p) > 0) {
    $periodo_data = mysqli_fetch_assoc($res_p);
    $f_inicio = strtotime($periodo_data['fecha_inicio']);
    $f_fin = strtotime($periodo_data['fecha_fin']);
    $texto_vigencia = $meses[date('n', $f_inicio)-1] . " " . date('Y', $f_inicio) . " - " . $meses[date('n', $f_fin)-1] . " " . date('Y', $f_fin);
}

require_once('../fpdf/fpdf.php'); 
while (ob_get_level()) { ob_end_clean(); }

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(25, 10, 25);
$pdf->SetAutoPageBreak(false); 

// --- MEMBRETE ---
$pdf->Image('../images/uptpc.png', 20, 10, 18); 
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->SetY(10);
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "REPÚBLICA BOLIVARIANA DE VENEZUELA"), 0, 1, 'C');
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA"), 0, 1, 'C');
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO"), 0, 1, 'C');
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA"), 0, 1, 'C');

// --- TÍTULO ---
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "CONSTANCIA DE INSCRIPCIÓN"), 0, 1, 'C');

// --- CUERPO ---
$pdf->Ln(6);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Quien suscribe Jefe del Departamento de Control de Estudio de nuestra Institución, Hace Constar que el (la) Ciudadano (a) que se menciona a continuación"), 0, 'J');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', mb_strtoupper($estudiante['nombre'])), 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Titular de la Cédula de Identidad " . $estudiante['idusuario'] . ", se encuentra inscrito en esta casa de estudio para cursar el TRAYECTO INICIAL del Programa Nacional de Formación en:"), 0, 'J');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', mb_strtoupper($carrera['nombre_carrera'])), 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 11);
$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Cuyo lapso académico " . ($fads ?? '') . " y su vigencia corresponde desde"), 0, 'J');

$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto_vigencia), 0, 1, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', '', 11);
$dia_text = date('d');
$anio_text = date('Y');
$mes_text = strtolower($meses[date('n')-1]);
$pdf->MultiCell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Constancia que se expide en la ciudad de Puerto Cabello a los $dia_text días del mes de $mes_text del año $anio_text."), 0, 'J');

// --- BLOQUE DE FIRMA (POSICIONADO ABAJO) ---
$pdf->SetY(-65); // Establecemos la posición Y contando desde el final de la hoja hacia arriba
$pdf->Line(70, $pdf->GetY(), 140, $pdf->GetY()); // Línea para firmar: Line(x1, y1, x2, y2)
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Dra. Zorangel E. Aponte Q."), 0, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Jefa de Control de Estudios"), 0, 1, 'C');
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Resolución N° 07-2022 de fecha 01/11/2022"), 0, 1, 'C');
$pdf->Cell(0, 3.5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Consejo N° 07 de fecha 01/11/2022"), 0, 1, 'C');

// --- PIE DE PÁGINA (ESTÁTICO AL FINAL) ---
$pdf->SetY(-30); 
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 6, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Este Documento NO ES VALIDO sin la firma y Sello del Departamento de Control De Estudios"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(0, 3, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', "Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello\nNúmero Telefónico: (0242) 3700494. Correo Electrónico: control_de_estudios@uptpc.edu.ve"), 0, 'C');

$pdf->Output('I', 'Constancia_Inscripcion.pdf');
exit();