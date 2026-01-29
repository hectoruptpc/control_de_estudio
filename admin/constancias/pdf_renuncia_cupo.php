<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$datos = [
    'nombre' => 'NOMBRE DEL ESTUDIANTE',
    'cedula' => '00.000.000',
    'nacionalidad' => 'V',
    'anio_sni' => '2025',
    'carrera' => 'MECÁNICA',
    'telefono' => '',
    'direccion' => '',
    'correo' => ''
];

if ($id_estudiante > 0) {
    $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    $datos['nombre'] = strtoupper($res['nombre']);
    $datos['cedula'] = $res['idusuario'];
    $carrera_info = obtenerCarreraEstudiante($id_estudiante);
    $datos['carrera'] = strtoupper($carrera_info['nombre_carrera']);
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE FPDF OPTIMIZADA
class PDF_Renuncia extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 15, 10, 14);
        }
        $this->SetY(10);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 10, txt('RENUNCIA DE CUPO'), 0, 1, 'C');
    }
}

// 3. GENERACIÓN (Ajuste de márgenes y saltos)
$pdf = new PDF_Renuncia('P', 'mm', 'Letter');
$pdf->SetMargins(20, 10, 20); // Margen superior reducido a 10
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10); // Fuente ligeramente más pequeña

$lh = 7; // Altura de línea reducida de 8 a 7

// Cuerpo del documento
$pdf->Ln(5);
$pdf->Write($lh, txt("Yo, "));
$pdf->SetFont('Arial', 'B', 10);
$pdf->Write($lh, txt($datos['nombre'] . " "));
$pdf->SetFont('Arial', '', 10);
$pdf->Write($lh, txt(", titular de la Cédula de Identidad N° " . $datos['nacionalidad'] . " ( ) E ( ) " . $datos['cedula']));
$pdf->Write($lh, txt(", informo que participe en el Sistema Nacional de Ingreso (SNI) del año "));
$pdf->Write($lh, txt("__________")); 
$pdf->Write($lh, txt(", fui asignado a la Institución Universitaria ________________________ ( "));
$pdf->Write($lh, txt("__________"));
$pdf->Write($lh, txt(" ) en la carrera o Programa Nacional de Formación "));
$pdf->SetFont('Arial', 'B', 10);
$pdf->Write($lh, txt($datos['carrera']));
$pdf->SetFont('Arial', '', 10);
$pdf->Write($lh, txt(", hago constar por medio de la presente que renuncio al cupo en la carrera o PNF ____________________________________ en la institución antes mencionada."));

$pdf->Ln(12);
$pdf->Write($lh, txt("Motivo:"));
$pdf->Ln(6);
$pdf->Line(20, $pdf->GetY() + 4, 195, $pdf->GetY() + 4);
$pdf->Line(20, $pdf->GetY() + 12, 195, $pdf->GetY() + 12);
$pdf->Ln(18);

$pdf->Write($lh, txt("La presente se expide en la ciudad de Puerto Cabello, a los ________ días del mes de ____________________ de ___________."));

$pdf->Ln(12);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, txt('DATOS DEL BACHILLER'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 9);

// Campos de datos con interlineado compacto
$campos = [
    'Nombres y Apellidos' => $datos['nombre'],
    'Cédula de Identidad' => $datos['cedula'],
    'Teléfono' => $datos['telefono'],
    'Dirección' => $datos['direccion'],
    'Correo Electrónico' => $datos['correo']
];

foreach ($campos as $label => $valor) {
    $pdf->Cell(35, 6, txt($label . ":"), 0, 0);
    $pdf->Cell(0, 6, txt($valor), 'B', 1);
    $pdf->Ln(1);
}

$pdf->Ln(10); // Espacio antes de firmas
$pdf->SetFont('Arial', 'B', 9);

// Área de firmas en una sola línea para ahorrar espacio
$pdf->Cell(85, 6, txt('RECIBIDO POR: ___________________'), 0, 0, 'L');
$pdf->Cell(0, 6, txt('C.I. ____________________'), 0, 1, 'L');

$pdf->Ln(10);
$pdf->Cell(0, 6, txt('FIRMA y SELLO: _____________________________________'), 0, 1, 'L');

ob_end_clean();
$pdf->Output('I', "Renuncia_Cupo.pdf");
exit();