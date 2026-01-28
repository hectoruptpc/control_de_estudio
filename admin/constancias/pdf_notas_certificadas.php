<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

// Importar tus funciones y la librería FPDF
require_once('../../funciones/functions.php');
require_once('../../fpdf/fpdf.php');

// 1. VALIDACIÓN DE ENTRADA
if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);

// 2. OBTENCIÓN DE DATOS (Basado en tu lógica de consulta)
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$carrera = obtenerCarreraEstudiante($id_estudiante);
if (!$carrera) die('El estudiante no tiene una carrera asignada.');

// Obtener todas las materias y las notas del estudiante
$materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
$notas_estudiante = obtenerNotasEstudianteConsulta($id_estudiante);

// Función para compatibilidad con PHP 8.2+ (tildes y eñes)
function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

// 3. CLASE PARA EL FORMATO DE SECRETARÍA (UPTP)
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

// 4. GENERACIÓN DEL CONTENIDO
$pdf = new PDF_Certificacion('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// Introducción oficial
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("Quien suscribe, Dra. Blanca A. Crespo C., titular de la cédula de identidad V-10.959.330, Secretario del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano (a):"), 0, 'L');
$pdf->Ln(2);

// Cuadro de Datos (Negritas donde corresponde)
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 5, txt('APELLIDOS Y NOMBRES'), 1, 0, 'C');
$pdf->Cell(35, 5, txt('CÉDULA'), 1, 0, 'C');
$pdf->Cell(56, 5, txt('PNF'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 6, txt(strtoupper($estudiante['nombre'])), 1, 0, 'C');
$pdf->Cell(35, 6, txt($estudiante['idusuario']), 1, 0, 'C');
$pdf->Cell(56, 6, txt($carrera['nombre_carrera']), 1, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("quien cursó y aprobó todas las unidades curriculares del Plan de Estudios del Programa Nacional de Formación para obtener el Título de Técnico Superior Universitario, logrando las siguientes calificaciones:"), 0, 'L');
$pdf->Ln(2);

// Encabezados de Tabla
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 4, txt('UNIDADES CURRICULARES CURSADAS'), 0, 1, 'C');
$pdf->Cell(25, 5, txt('CÓDIGO'), 1, 0, 'C');
$pdf->Cell(90, 5, txt('UNIDAD CURRICULAR'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('UC'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('NOTA'), 1, 0, 'C');
$pdf->Cell(18, 5, txt('LAPSO'), 1, 0, 'C');
$pdf->Cell(0, 5, txt('OBSERVACIÓN'), 1, 1, 'C');

// 5. PROCESAMIENTO DE NOTAS POR TRAYECTO
$materias_por_trayecto = [];
while ($m = $materias_carrera->fetch_assoc()) {
    $materias_por_trayecto[$m['trayecto']][] = $m;
}

$suma_ponderada_total = 0;
$total_uc_aprobadas = 0;

foreach ($materias_por_trayecto as $t_num => $materias) {
    // Cálculo de IRA del Trayecto
    $uc_t = 0;
    $suma_t = 0;
    $filas_materias = ""; // Buffer para imprimir después del encabezado del trayecto

    $pdf->SetFont('Arial', 'B', 7);
    
    // Primero procesamos las materias para tener el IRA del trayecto antes de imprimir la cabecera
    $buffer_materias = [];
    foreach ($materias as $m) {
        $nota_data = $notas_estudiante[$m['id_materia']] ?? null;
        $campo_t = 'trayecto_' . $t_num;
        $nota_final = (isset($nota_data[$campo_t])) ? (float)$nota_data[$campo_t] : null;

        // Solo incluimos en la certificación las materias aprobadas (Nota >= 12 según tu sistema)
        if ($nota_final !== null && $nota_final >= 12) {
            $buffer_materias[] = [
                'cod' => $m['cod_materia'],
                'nom' => $m['nombre_materia'],
                'uc' => $m['uc_materia'] ?? 0,
                'nota' => $nota_final,
                'periodo' => $nota_data['nombre_periodo'] ?? ''
            ];
            $uc_t += $m['uc_materia'];
            $suma_t += ($nota_final * $m['uc_materia']);
        }
    }

    if (!empty($buffer_materias)) {
        $ira_t = ($uc_t > 0) ? number_format($suma_t / $uc_t, 3) : "0.000";
        
        // Fila de Encabezado de Trayecto (con IRA a la derecha)
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(115, 5, txt("Trayecto: $t_num"), 'L', 0, 'L');
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(0, 5, txt("IRA $t_num del Trayecto: $ira_t   Total UC del Trayecto: $uc_t"), 'R', 1, 'R');
        
        // Imprimir las materias del buffer
        $pdf->SetFont('Arial', '', 7);
        foreach ($buffer_materias as $bm) {
            $pdf->Cell(25, 5, txt($bm['cod']), 1, 0, 'L');
            $pdf->Cell(90, 5, txt(substr($bm['nom'], 0, 65)), 1, 0, 'L');
            $pdf->Cell(12, 5, $bm['uc'], 1, 0, 'C');
            $pdf->Cell(12, 5, str_pad($bm['nota'], 2, "0", STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(18, 5, txt($bm['periodo']), 1, 0, 'C');
            $pdf->Cell(0, 5, txt('APROBADO'), 1, 1, 'C');
            
            $suma_ponderada_total += ($bm['nota'] * $bm['uc']);
            $total_uc_aprobadas += $bm['uc'];
        }
    }
}

// 6. RESUMEN FINAL
$pdf->Ln(3);
$ira_general = ($total_uc_aprobadas > 0) ? number_format($suma_ponderada_total / $total_uc_aprobadas, 3) : "0.000";

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, txt("Unidades de Créditos Cursadas y Aprobadas: $total_uc_aprobadas"), 1, 1, 'C');
$pdf->Cell(0, 6, txt("Índice de Rendimiento Académico: $ira_general"), 1, 1, 'C');

// Limpieza de buffer y salida
ob_end_clean();
$pdf->Output('I', 'Certificacion_Notas_' . $estudiante['idusuario'] . '.pdf');
exit();