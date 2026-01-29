<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php'); // Corregido a ../../ según tus scripts previos

// 1. OBTENCIÓN DE DATOS
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;
$estudiante = ['nombre' => '', 'idusuario' => ''];
$carrera_nombre = '';

if ($id_estudiante > 0) {
    $query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query_user);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $estudiante = $stmt->get_result()->fetch_assoc();
    $carrera_data = obtenerCarreraEstudiante($id_estudiante);
    $carrera_nombre = strtoupper($carrera_data['nombre_carrera']);
}

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 2. CLASE EXTENDIDA
class PDF_Adicion extends FPDF {
    // Función para líneas punteadas
    function SetDash($w=null, $s=null) {
        if($w!==null)
            $s=sprintf(' [%.3F %.3F] 0 d',$w*$this->k,$s*$this->k);
        else
            $s=' [] 0 d';
        $this->_out($s);
    }

    function DibujarFormato($y, $estudiante, $carrera) {
        $this->SetY($y);
        
        // --- LOGO ---
        // Se coloca en la esquina superior izquierda de cada bloque
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 12, $y, 16);
        }
        
        // --- MEMBRETE ---
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 2.5, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 2.5, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 2.5, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 2.5, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 4, txt('DIRECCIÓN DE MECÁNICA AUTOMOTRIZ Y/O MECÁNICA TÉRMICA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('SOLICITUD DE ADICIÓN Y RETIRO DE ASIGNATURA'), 0, 1, 'C');
        
        $this->Ln(2);
        // --- TABLA DE DATOS ---
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(30, 5, txt('FECHA'), 1, 0, 'C');
        $this->Cell(40, 5, txt('CÉDULA DE IDENTIDAD'), 1, 0, 'C');
        $this->Cell(120, 5, txt('APELLIDOS Y NOMBRES DEL ESTUDIANTE'), 1, 1, 'C');
        
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 7, date('d/m/Y'), 1, 0, 'C');
        $this->Cell(40, 7, 'V-'.$estudiante['idusuario'], 1, 0, 'C');
        $this->Cell(120, 7, txt(strtoupper($estudiante['nombre'])), 1, 1, 'C');
        
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(190, 4, txt('PNF / PTF'), 1, 1, 'C');
        
        $this->SetFont('Arial', '', 7);
        $chk1 = (strpos($carrera, 'AUTOMOTRIZ') !== false) ? 'X' : ' ';
        $chk2 = (strpos($carrera, 'TÉRMICA') !== false) ? 'X' : ' ';
        $this->Cell(95, 6, txt("[ $chk1 ] MECÁNICA AUTOMOTRIZ"), 1, 0, 'C');
        $this->Cell(95, 6, txt("[ $chk2 ] MECÁNICA TÉRMICA"), 1, 1, 'C');
        
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(90, 5, txt('UNIDADES CURRICULARES ADICIONALES'), 1, 0, 'C');
        $this->Cell(65, 5, txt('PERIODO Y/O LAPSO'), 1, 0, 'C');
        $this->Cell(35, 5, txt('SECCIÓN'), 1, 1, 'C');
        
        $this->SetFont('Arial', '', 8);
        for($i=0; $i<3; $i++) {
            $this->Cell(90, 6, '', 1, 0, 'L');
            $this->Cell(65, 6, '', 1, 0, 'C');
            $this->Cell(35, 6, '', 1, 1, 'C');
        }
        
        // Espacio para firmas
        $this->Cell(95, 12, '', 'LR', 0); $this->Cell(95, 12, '', 'R', 1);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(95, 4, txt('FIRMA DEL ESTUDIANTE'), 1, 0, 'C');
        $this->Cell(95, 4, txt('FIRMA DEL DIRECTOR'), 1, 1, 'C');
        
        $this->Cell(190, 6, txt('DEPARTAMENTO DE CONTROL DE ESTUDIOS'), 1, 1, 'C');
    }
}

// 3. GENERACIÓN
$pdf = new PDF_Adicion('P', 'mm', 'Letter');
$pdf->SetMargins(12, 10, 12);
$pdf->AddPage();

// Original (Superior)
$pdf->DibujarFormato(10, $estudiante, $carrera_nombre);

// Línea de corte
$pdf->SetDash(1, 1);
$pdf->Line(10, 138, 205, 138);
$pdf->SetDash(); 

// Copia (Inferior)
$pdf->DibujarFormato(148, $estudiante, $carrera_nombre);

ob_end_clean();
$pdf->Output('I', "Adicion_Retiro_".$estudiante['idusuario'].".pdf");
exit();