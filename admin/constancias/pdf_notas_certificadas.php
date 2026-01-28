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
}

$pdf = new PDF_Certificacion('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// Texto Introductorio
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 4, txt("Quien suscribe, Dra. Blanca A. Crespo C., titular de la cédula de identidad V-10.959.330, Secretario del Consejo de Gestión Universitaria de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano (a):"), 0, 'L');
$pdf->Ln(2);

// Datos Alumno
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 5, txt('APELLIDOS Y NOMBRES'), 1, 0, 'C');
$pdf->Cell(35, 5, txt('CÉDULA'), 1, 0, 'C');
$pdf->Cell(56, 5, txt('PNF'), 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 6, txt(strtoupper($estudiante['nombre'])), 1, 0, 'C');
$pdf->Cell(35, 6, txt($estudiante['idusuario']), 1, 0, 'C');
$pdf->Cell(56, 6, txt($carrera['nombre_carrera']), 1, 1, 'C');

$pdf->Ln(6);

// Encabezados Tabla
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(25, 5, txt('CÓDIGO'), 1, 0, 'C');
$pdf->Cell(90, 5, txt('UNIDAD CURRICULAR'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('UC'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('NOTA'), 1, 0, 'C');
$pdf->Cell(18, 5, txt('LAPSO'), 1, 0, 'C');
$pdf->Cell(0, 5, txt('OBSERVACIÓN'), 1, 1, 'C');

// --- PROCESAMIENTO ---
$suma_ponderada_total = 0;
$total_uc_general = 0;

// Agrupamos materias por trayecto primero
$materias_por_trayecto = [];
while ($m = $materias_carrera->fetch_assoc()) {
    $t_idx = (int)$m['trayecto'];
    $materias_por_trayecto[$t_idx][] = $m;
}

// Recorremos cada trayecto
foreach ($materias_por_trayecto as $t_num => $materias) {
    $buffer_html = [];
    $uc_t = 0;
    $suma_t = 0;

    foreach ($materias as $m) {
        // IMPORTANTE: Verificar el ID correcto. Tu código usa 'id_materia'
        $id_m = $m['id_materia'];
        $nota_data = $notas_estudiante[$id_m] ?? null;
        
        // El campo de la nota se llama trayecto_X
        $campo_nota = 'trayecto_' . $t_num;
        $nota_final = (isset($nota_data[$campo_nota])) ? (float)$nota_data[$campo_nota] : null;

        // Intentar obtener UC de varios nombres posibles (ajusta según tu DB)
        $uc_valor = (float)($m['uc_materia'] ?? $m['unidades_credito'] ?? $m['uc'] ?? 0);

        // Solo procesar si está aprobada (Nota >= 12)
        if ($nota_final !== null && $nota_final >= 12) {
            $buffer_html[] = [
                'cod' => $m['cod_materia'],
                'nom' => $m['nombre_materia'],
                'uc'  => $uc_valor,
                'nota'=> $nota_final,
                'periodo' => $nota_data['nombre_periodo'] ?? ''
            ];
            $uc_t += $uc_valor;
            $suma_t += ($nota_final * $uc_valor);
        }
    }

    // Si el trayecto tiene materias aprobadas, lo imprimimos
    if (!empty($buffer_html)) {
        $ira_t = ($uc_t > 0) ? number_format($suma_t / $uc_t, 3) : "0.000";

        // Encabezado del Trayecto
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(115, 5, txt("Trayecto: $t_num"), 'L', 0, 'L');
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(0, 5, txt("IRA $t_num del Trayecto: $ira_t   Total UC: $uc_t"), 'R', 1, 'R');

        // Filas de materias
        $pdf->SetFont('Arial', '', 7);
        foreach ($buffer_html as $row) {
            $pdf->Cell(25, 5, txt($row['cod']), 1, 0, 'L');
            $pdf->Cell(90, 5, txt(substr($row['nom'], 0, 65)), 1, 0, 'L');
            $pdf->Cell(12, 5, $row['uc'], 1, 0, 'C');
            $pdf->Cell(12, 5, str_pad($row['nota'], 2, "0", STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(18, 5, txt($row['periodo']), 1, 0, 'C');
            $pdf->Cell(0, 5, '', 1, 1, 'C');

            $suma_ponderada_total += ($row['nota'] * $row['uc']);
            $total_uc_general += $row['uc'];
        }
    }
}

// 4. TOTALES FINALES
$pdf->Ln(5);
$ira_final = ($total_uc_general > 0) ? number_format($suma_ponderada_total / $total_uc_general, 3) : "0.000";

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, txt("Unidades de Créditos Cursadas y Aprobadas: $total_uc_general"), 1, 1, 'C');
$pdf->Cell(0, 6, txt("Índice de Rendimiento Académico: $ira_final"), 1, 1, 'C');

ob_end_clean();
$pdf->Output('I', 'Certificacion_Notas.pdf');
exit();