<?php
require_once __DIR__ . '/../fpdf/fpdf.php';

$estudiante_nombre = isset($_GET['estudiante_nombre']) ? utf8_decode(urldecode($_GET['estudiante_nombre'])) : '';
$estudiante_cedula = isset($_GET['estudiante_cedula']) ? urldecode($_GET['estudiante_cedula']) : '';

class PDF extends FPDF {
    function GenerarFormulario($x_base, $nombre, $cedula) {
        // Logo y Membrete
        if(file_exists('../images/uptpc.png')){
            $this->Image('../images/uptpc.png', $x_base, 10, 15);
        }
        
        $this->SetFont('Arial', '', 6);
        $this->SetXY($x_base + 18, 10);
        $this->Cell(100, 3, utf8_decode('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'L');
        $this->SetX($x_base + 18);
        $this->Cell(100, 3, utf8_decode('SECRETARÍA DE GESTIÓN UNIVERSITARIA'), 0, 1, 'L');
        $this->SetX($x_base + 18);
        $this->Cell(100, 3, utf8_decode('CONTROL DE ESTUDIOS'), 0, 1, 'L');

        $this->SetFont('Arial', 'B', 8);
        $this->SetXY($x_base, 22);
        $this->Cell(130, 5, 'SOLICITUD ADMINISTRATIVA', 0, 1, 'C');

        // Fecha de Solicitud
        $this->SetFont('Arial', '', 7);
        $this->SetX($x_base);
        $this->Cell(130, 5, 'FECHA DE SOLICITUD: ' . date('d/m/Y'), 1, 1, 'L');

        // Nombre y Cédula
        $this->SetFont('Arial', '', 7);
        $this->SetX($x_base);
        $this->Cell(95, 5, 'NOMBRE Y APELLIDO', 'LTR', 0, 'C');
        $this->Cell(35, 5, utf8_decode('CÉDULA DE IDENTIDAD'), 'LTR', 1, 'C');
        
        $this->SetX($x_base);
        $this->SetFont('Arial', '', 8);
        $this->Cell(95, 8, $nombre, 1, 0, 'L');
        $this->Cell(35, 8, $cedula, 1, 1, 'C');

        // Selección de Sede
        $this->SetFont('Arial', '', 7);
        $this->SetX($x_base);
        $this->Cell(95, 5, 'SELECCION LA SEDE QUE ESTUDIO', 1, 0, 'C');
        $this->Cell(35, 5, '', 'LR', 1, 'C');

        $y_sede = $this->GetY();
        $this->SetFont('Arial', '', 6);
        $this->SetXY($x_base, $y_sede);
        $this->Cell(95, 4, 'UPTPC SEDE PTO CABELLO', 1, 0, 'L'); $this->SetX($x_base + 80); $this->Cell(15, 4, '', 1, 1);
        $this->SetX($x_base);
        $this->Cell(95, 4, 'UPTPC - COEF', 1, 0, 'L'); $this->SetX($x_base + 80); $this->Cell(15, 4, '', 1, 1);
        $this->SetX($x_base);
        $this->Cell(95, 4, 'SUCRE', 1, 0, 'L'); $this->SetX($x_base + 80); $this->Cell(15, 4, '', 1, 1);

        // Especialidad (Caja lateral)
        $this->SetXY($x_base + 95, $y_sede - 5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(35, 17, 'ESPECIALIDAD', 1, 1, 'C');

        // Cuerpo central
        $this->SetX($x_base);
        $this->SetFont('Arial', '', 7);
        $this->Cell(95, 5, 'SOLICITUD ADMINISTRATIVA', 1, 0, 'C');
        $this->Cell(35, 5, '', 1, 1, 'C');

        $opciones = ['CARTA DE CULMINACION', 'NOTAS CERTIFICADAS TSU', 'NOTAS CERTIFICADAS ING / LCDO'];
        foreach ($opciones as $op) {
            $this->SetX($x_base);
            $this->Cell(95, 5, utf8_decode($op), 1, 0, 'L');
            $this->Cell(35, 5, '', 1, 1);
        }

        // Observaciones
        $this->SetX($x_base);
        $this->Cell(130, 4, 'OBSERVACIONES', 'LTR', 1, 'C');
        $this->SetX($x_base);
        $this->Cell(130, 8, '', 'LBR', 1, 'L');

        $this->Ln(2);

        // Bloque de Firmas
        $this->SetFont('Arial', '', 6);
        $this->SetX($x_base);
        $this->Cell(95, 8, utf8_decode('NOMBRE Y APELLIDO QUIEN RECIBIÓ LA SOLICITUD'), 1, 0, 'C');
        $this->Cell(35, 8, 'FIRMA Y FECHA', 1, 1, 'C');
        
        $this->SetX($x_base);
        $this->Cell(95, 8, utf8_decode('NOMBRE Y APELLIDO DEL ÁREA DE JEFE PERMANENCIA Y SEGUIMIENTO'), 1, 0, 'C');
        $this->Cell(35, 8, 'FIRMA Y FECHA', 1, 1, 'C');
    }
}

// Configuración: Paisaje (Landscape), milímetros, A4
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

// Formulario Izquierdo
$pdf->GenerarFormulario(10, $estudiante_nombre, $estudiante_cedula);

// Formulario Derecho (Empezamos en la mitad de la hoja horizontal)
$pdf->GenerarFormulario(155, $estudiante_nombre, $estudiante_cedula);

$pdf->Output('I', 'Reporte_Doble_Identico.pdf');