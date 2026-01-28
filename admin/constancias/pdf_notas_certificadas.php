<?php
// Evitar cualquier salida de error que dañe el PDF
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

include('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// --- DATOS DEL ESTUDIANTE ---
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$carr = obtenerCarreraEstudiante($id_estudiante);
// Nota: Asegúrate de que esta función devuelva: codigo_materia, nombre_materia, uc, nota_final, trayecto, lapso
$aprobadas = obtenerMateriasAprobadas($id_estudiante); 

// Función para compatibilidad con PHP 8.2+ y tildes
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

class PDF_Certificacion extends FPDF {
    function Header() {
        // Logo institucional (Ajustar ruta)
        if(file_exists('../../img/logo_uptp.png')) {
            $this->Image('../../img/logo_uptp.png', 12, 10, 18);
        }

        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(6);
        // Título normal sin color, solo negrita
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, txt('CERTIFICACIÓN'), 0, 1, 'C');
        $this->Ln(4);
    }
}

$pdf = new PDF_Certificacion('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// Texto introductorio según la imagen
$pdf->SetFont('Arial', '', 9);
$intro = "Quien suscribe, Dra. Blanca A. Crespo C., titular de la cédula de identidad V-10.959.330, Secretario del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano (a):";
$pdf->MultiCell(0, 4, txt($intro), 0, 'L');
$pdf->Ln(3);

// --- TABLA DE DATOS DEL ALUMNO ---
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 5, txt('APELLIDOS Y NOMBRES'), 1, 0, 'C');
$pdf->Cell(35, 5, txt('CÉDULA'), 1, 0, 'C');
$pdf->Cell(56, 5, txt('PNF'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 6, txt(strtoupper($estudiante['nombre'])), 1, 0, 'C');
$pdf->Cell(35, 6, txt($estudiante['idusuario']), 1, 0, 'C');
$pdf->Cell(56, 6, txt($carr['nombre_carrera'] ?? 'N/A'), 1, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("quien cursó y aprobó todas las unidades curriculares del Plan de Estudios del Programa Nacional de Formación para obtener el Título de Técnico Superior Universitario, logrando las siguientes calificaciones:"), 0, 'L');
$pdf->Ln(3);

// --- TABLA DE CALIFICACIONES ---
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 4, txt('UNIDADES CURRICULARES CURSADAS'), 0, 1, 'C');

// Encabezados en Negrita
$pdf->Cell(28, 5, txt('CÓDIGO'), 1, 0, 'C');
$pdf->Cell(90, 5, txt('UNIDAD CURRICULAR'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('UC'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('NOTA'), 1, 0, 'C');
$pdf->Cell(15, 5, txt('LAPSO'), 1, 0, 'C');
$pdf->Cell(0, 5, txt('OBSERVACIÓN'), 1, 1, 'C');

// Agrupación por Trayecto
$trayectos = [];
foreach ($aprobadas as $m) {
    $trayectos[$m['trayecto']][] = $m;
}

$total_uc_general = 0;
$suma_ponderada_general = 0;

foreach ($trayectos as $t_num => $materias) {
    // Fila separadora de Trayecto
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(120, 5, txt("Trayecto: $t_num"), 'L', 0, 'L');
    
    // Cálculo de IRA del Trayecto
    $uc_t = 0; $suma_t = 0;
    foreach($materias as $mat) {
        $uc_t += $mat['uc'];
        $suma_t += ($mat['nota_final'] * $mat['uc']);
    }
    $ira_t = ($uc_t > 0) ? number_format($suma_t / $uc_t, 3) : "0.000";
    
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell(0, 5, txt("IRA $t_num del Trayecto: $ira_t  Total UC del Trayecto: $uc_t"), 'R', 1, 'R');
    
    // Lista de materias
    $pdf->SetFont('Arial', '', 7);
    foreach ($materias as $m) {
        $pdf->Cell(28, 5, txt($m['codigo_materia']), 1, 0, 'L');
        $pdf->Cell(90, 5, txt(substr($m['nombre_materia'], 0, 65)), 1, 0, 'L');
        $pdf->Cell(12, 5, $m['uc'], 1, 0, 'C');
        $pdf->Cell(12, 5, str_pad($m['nota_final'], 2, "0", STR_PAD_LEFT), 1, 0, 'C');
        $pdf->Cell(15, 5, $m['lapso'], 1, 0, 'C');
        $pdf->Cell(0, 5, txt('APROBADO'), 1, 1, 'C');
        
        $total_uc_general += $m['uc'];
        $suma_ponderada_general += ($m['nota_final'] * $m['uc']);
    }
}

// --- RESUMEN FINAL ---
$pdf->Ln(3);
$ira_final = ($total_uc_general > 0) ? number_format($suma_ponderada_general / $total_uc_general, 3) : "0.000";

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, txt("Unidades de Créditos Cursadas y Aprobadas: $total_uc_general"), 1, 1, 'C');
$pdf->Cell(0, 6, txt("Índice de Rendimiento Académico: $ira_final"), 1, 1, 'C');

// Finalización y salida limpia
ob_end_clean();
$pdf->Output('I', 'Certificacion_Notas_' . $estudiante['idusuario'] . '.pdf');
exit();