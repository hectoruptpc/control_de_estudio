<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// 1. OBTENCIÓN DE DATOS
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$carrera = obtenerCarreraEstudiante($id_estudiante);
$materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
$notas_estudiante = obtenerNotasEstudianteConsulta($id_estudiante);

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

class PDF_Certificacion extends FPDF {
    function Header() {
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        
        $this->Ln(6);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, txt('CERTIFICACIÓN'), 0, 1, 'C');
        $this->Ln(4);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(0, 10, txt('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF_Certificacion('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// Texto Introductorio
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("Quien suscribe, Dra. Blanca A. Crespo C., titular de la cédula de identidad V-10.959.330, Secretario del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano (a):"), 0, 'L');
$pdf->Ln(2);

// Cuadro de Datos del Estudiante
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 5, txt('APELLIDOS Y NOMBRES'), 1, 0, 'C');
$pdf->Cell(35, 5, txt('CÉDULA'), 1, 0, 'C');
$pdf->Cell(56, 5, txt('PNF'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 6, txt(strtoupper($estudiante['nombre'])), 1, 0, 'C');
$pdf->Cell(35, 6, txt($estudiante['idusuario']), 1, 0, 'C');
$pdf->Cell(56, 6, txt($carrera['nombre_carrera']), 1, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("quien cursó y aprobó todas las unidades curriculares del Plan de Estudios del Programa Nacional de Formación para obtener el Título de Técnico Superior Universitario, logrando las siguientes calificaciones:"), 0, 'L');
$pdf->Ln(3);

// Encabezados de Tabla
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 4, txt('UNIDADES CURRICULARES CURSADAS'), 0, 1, 'C');
$pdf->Cell(25, 5, txt('CÓDIGO'), 1, 0, 'C');
$pdf->Cell(90, 5, txt('UNIDAD CURRICULAR'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('UC'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('NOTA'), 1, 0, 'C');
$pdf->Cell(18, 5, txt('LAPSO'), 1, 0, 'C');
$pdf->Cell(0, 5, txt('OBSERVACIÓN'), 1, 1, 'C');

// --- PROCESAMIENTO ---
$suma_ponderada_total = 0;
$total_uc_general = 0;

// Agrupar materias por trayecto
$materias_por_trayecto = [];
while ($m = $materias_carrera->fetch_assoc()) {
    $t_num = (int)$m['trayecto'];
    $materias_por_trayecto[$t_num][] = $m;
}

foreach ($materias_por_trayecto as $t_num => $materias) {
    $buffer = [];
    $suma_ponderada_t = 0;
    $total_uc_t = 0;

    foreach ($materias as $m) {
        $id_m = $m['id_materia'];
        $nota_data = $notas_estudiante[$id_m] ?? null;
        
        // Buscar nota en la columna correspondiente al trayecto
        $campo_t = 'trayecto_' . $t_num;
        $nota_final = (isset($nota_data[$campo_t])) ? (float)$nota_data[$campo_t] : null;
        
        // Obtener UC de la columna 'creditos'
        $uc = (float)($m['creditos'] ?? 0);

        // Solo materias aprobadas (>= 12)
        if ($nota_final !== null && $nota_final >= 12) {
            $buffer[] = [
                'cod' => $m['cod_materia'],
                'nom' => $m['nombre_materia'],
                'uc'  => $uc,
                'nota'=> $nota_final,
                'lapso'=> $nota_data['nombre_periodo'] ?? ''
            ];
            $suma_ponderada_t += ($nota_final * $uc);
            $total_uc_t += $uc;
        }
    }

    if (!empty($buffer)) {
        // Cálculo de IRA del Trayecto (Ponderado)
        $ira_t = ($total_uc_t > 0) ? number_format($suma_ponderada_t / $total_uc_t, 3) : "0.000";

        // Fila de encabezado de Trayecto (Negrita solo etiquetas)
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(115, 5, txt("Trayecto: $t_num"), 'L', 0, 'L');
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(0, 5, txt("IRA $t_num del Trayecto: $ira_t   Total UC: $total_uc_t"), 'R', 1, 'R');

        // Materias del Trayecto
        $pdf->SetFont('Arial', '', 7);
        foreach ($buffer as $b) {
            $pdf->Cell(25, 5, txt($b['cod']), 1, 0, 'L');
            $pdf->Cell(90, 5, txt(substr($b['nom'], 0, 65)), 1, 0, 'L');
            $pdf->Cell(12, 5, $b['uc'], 1, 0, 'C');
            $pdf->Cell(12, 5, str_pad($b['nota'], 2, "0", STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(18, 5, txt($b['lapso']), 1, 0, 'C');
            $pdf->Cell(0, 5, '', 1, 1, 'C'); // Observación vacía
            
            $suma_ponderada_total += ($b['nota'] * $b['uc']);
            $total_uc_general += $b['uc'];
        }
    }
}

// --- TOTALES FINALES ---
$pdf->Ln(4);
$ira_final = ($total_uc_general > 0) ? number_format($suma_ponderada_total / $total_uc_general, 3) : "0.000";

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, txt("Unidades de Créditos Cursadas y Aprobadas: $total_uc_general"), 1, 1, 'C');
$pdf->Cell(0, 6, txt("Índice de Rendimiento Académico: $ira_final"), 1, 1, 'C');

// Limpiar salida y generar
ob_end_clean();
$pdf->Output('I', 'Certificacion_Notas_Final.pdf');
exit();