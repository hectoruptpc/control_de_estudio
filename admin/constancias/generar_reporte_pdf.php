<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// Extender FPDF para soportar UTF-8
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, $this->convertText('SISTEMA DE GESTIÓN EDUCATIVA'), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $this->convertText('REPORTE DE ESTUDIANTES'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, $this->convertText('Fecha de generación: ' . date('d/m/Y H:i:s')), 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $this->convertText('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }
    
    // Cabecera de tabla
    function TablaHeader()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(0, 51, 102);
        $this->SetTextColor(255, 255, 255);
        
        $this->Cell(25, 8, $this->convertText('Cédula'), 1, 0, 'C', true);
        $this->Cell(45, 8, $this->convertText('Nombre'), 1, 0, 'C', true);
        $this->Cell(40, 8, $this->convertText('Email'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->convertText('Teléfono'), 1, 0, 'C', true);
        $this->Cell(35, 8, $this->convertText('Carrera'), 1, 0, 'C', true);
        $this->Cell(20, 8, $this->convertText('Género'), 1, 0, 'C', true);
        $this->Cell(15, 8, $this->convertText('Edad'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->convertText('Estado Civil'), 1, 0, 'C', true);
        $this->Cell(20, 8, $this->convertText('Status'), 1, 0, 'C', true);
        $this->Cell(25, 8, $this->convertText('Fecha Ingreso'), 1, 1, 'C', true);
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 7);
    }
    
    // Función para convertir texto a ISO-8859-1 manejando NULL
    function convertText($text)
    {
        if ($text === null || $text === '') {
            return '';
        }
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
    
    // Función segura para substr manejando NULL
    function safeSubstr($text, $start, $length)
    {
        if ($text === null || $text === '') {
            return '';
        }
        $textStr = (string)$text;
        return substr($textStr, $start, $length);
    }
}

// Obtener parámetros
$ids = $_GET['ids'] ?? '';
$incluirEstadisticas = $_GET['estadisticas'] ?? 'si';

if (empty($ids)) {
    die('No hay estudiantes seleccionados');
}

// Limpiar y validar IDs
$idsArray = explode(',', $ids);
$idsArray = array_map('intval', $idsArray);
$ids = implode(',', $idsArray);

// Obtener datos de los estudiantes
$query = "SELECT 
    id,
    idusuario,
    nombre,
    email,
    tlf,
    cel,
    carrera,
    genero,
    fecha_nac,
    edo_civil,
    status,
    fecha_ingreso
FROM users 
WHERE id IN ($ids) 
ORDER BY nombre ASC";

$result = $db->query($query);

$estudiantes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calcular edad
        $edad = '';
        if (!empty($row['fecha_nac'])) {
            try {
                $fechaNac = new DateTime($row['fecha_nac']);
                $hoy = new DateTime();
                $edad = $fechaNac->diff($hoy)->y;
            } catch (Exception $e) {
                $edad = '';
            }
        }
        $row['edad'] = $edad;
        $row['telefono'] = !empty($row['tlf']) ? $row['tlf'] : ($row['cel'] ?? '');
        $estudiantes[] = $row;
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('L', 'A4'); // Landscape
$pdf->SetFont('Arial', '', 8);

// Estadísticas
if ($incluirEstadisticas == 'si') {
    $total = count($estudiantes);
    $activos = 0;
    $inactivos = 0;
    $masculinos = 0;
    $femeninos = 0;
    $menores = 0;
    
    foreach ($estudiantes as $e) {
        if ($e['status'] == 1) $activos++;
        else $inactivos++;
        
        if ($e['genero'] == 'Masculino') $masculinos++;
        if ($e['genero'] == 'Femenino') $femeninos++;
        if ($e['edad'] < 18 && $e['edad'] != '') $menores++;
    }
    
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 10, $pdf->convertText('RESUMEN ESTADÍSTICO'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    
    $pdf->Cell(45, 8, $pdf->convertText('Total Estudiantes:'), 0, 0);
    $pdf->Cell(30, 8, $total, 0, 0);
    $pdf->Cell(45, 8, $pdf->convertText('Estudiantes Activos:'), 0, 0);
    $pdf->Cell(30, 8, $activos, 0, 0);
    $pdf->Cell(45, 8, $pdf->convertText('Estudiantes Inactivos:'), 0, 0);
    $pdf->Cell(30, 8, $inactivos, 0, 1);
    
    $pdf->Cell(45, 8, $pdf->convertText('Masculinos:'), 0, 0);
    $pdf->Cell(30, 8, $masculinos, 0, 0);
    $pdf->Cell(45, 8, $pdf->convertText('Femeninos:'), 0, 0);
    $pdf->Cell(30, 8, $femeninos, 0, 0);
    $pdf->Cell(45, 8, $pdf->convertText('Menores de 18 años:'), 0, 0);
    $pdf->Cell(30, 8, $menores, 0, 1);
    
    $pdf->Ln(5);
}

// Cabecera de la tabla
$pdf->TablaHeader();

// Datos de la tabla
$pdf->SetFont('Arial', '', 7);
foreach ($estudiantes as $e) {
    $status_text = ($e['status'] == 1) ? 'Activo' : 'Inactivo';
    $fecha_ingreso = !empty($e['fecha_ingreso']) ? date('d/m/Y', strtotime($e['fecha_ingreso'])) : '';
    
    // Usar safeSubstr para evitar errores con NULL
    $pdf->Cell(25, 7, $pdf->convertText($pdf->safeSubstr($e['idusuario'], 0, 20)), 1, 0, 'L');
    $pdf->Cell(45, 7, $pdf->convertText($pdf->safeSubstr($e['nombre'], 0, 25)), 1, 0, 'L');
    $pdf->Cell(40, 7, $pdf->convertText($pdf->safeSubstr($e['email'], 0, 25)), 1, 0, 'L');
    $pdf->Cell(25, 7, $pdf->convertText($pdf->safeSubstr($e['telefono'], 0, 15)), 1, 0, 'L');
    $pdf->Cell(35, 7, $pdf->convertText($pdf->safeSubstr($e['carrera'], 0, 20)), 1, 0, 'L');
    $pdf->Cell(20, 7, $pdf->convertText($pdf->safeSubstr($e['genero'], 0, 10)), 1, 0, 'L');
    $pdf->Cell(15, 7, $e['edad'], 1, 0, 'C');
    $pdf->Cell(25, 7, $pdf->convertText($pdf->safeSubstr($e['edo_civil'], 0, 15)), 1, 0, 'L');
    $pdf->Cell(20, 7, $status_text, 1, 0, 'C');
    $pdf->Cell(25, 7, $fecha_ingreso, 1, 1, 'C');
}

// Salida del PDF
$pdf->Output('I', 'reporte_estudiantes.pdf');
?>