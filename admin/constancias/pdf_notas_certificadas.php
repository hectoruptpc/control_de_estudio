<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

if (!isset($_GET['id'])) { die("ID no proporcionado."); }
$id_estudiante = intval($_GET['id']);
$tipo_reporte = isset($_GET['tipo']) ? strtolower($_GET['tipo']) : 'tsu';

// 1. DATOS
$query_user = "SELECT * FROM users WHERE id = ? LIMIT 1";
$stmt = $db->prepare($query_user);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) die('Estudiante no encontrado.');

$cedula_estudiante = $estudiante['idusuario']; 
$carrera = obtenerCarreraEstudiante($id_estudiante);
$materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
$notas_estudiante = obtenerNotasEstudianteConsulta($id_estudiante);

// Determinar etiqueta según tipo de formación: mostrar 'PNF' si la carrera es PNF, si no mostrar 'Carrera'
$tipo_form = isset($carrera['tipo_formacion']) ? strtoupper(trim($carrera['tipo_formacion'])) : '';
$etiqueta_tipo = (strpos($tipo_form, 'PNF') !== false) ? 'PNF' : 'Carrera';

// Determinar si se deben usar 'Semestre' en lugar de 'Trayecto' cuando la carrera es PTF
$periodo_label = (strpos($tipo_form, 'PTF') !== false) ? 'Semestre' : 'Trayecto';

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

function fechaEnLetras() {
    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    $dias = ["cero", "un", "dos", "tres", "cuatro", "cinco", "seis", "siete", "ocho", "nueve", "diez", "once", "doce", "trece", "catorce", "quince", "diez y seis", "diez y siete", "diez y ocho", "diez y nueve", "veinte", "veintiuno", "veintidós", "veintitrés", "veinticuatro", "veinticinco", "veintiséis", "veintisiete", "veintiocho", "veintinueve", "treinta", "treinta y uno"];
    $d = (int)date('d');
    $m = (int)date('m') - 1;
    $y = date('Y');
    $dia_texto = ($d < 32) ? $dias[$d] : $d;
    $anio_letras = ($y == "2026") ? "dos mil veintiséis" : (($y == "2025") ? "dos mil veinticinco" : "dos mil " . $y);
    return "En la ciudad de Puerto Cabello a los $dia_texto ($d) días del mes de " . $meses[$m] . " del año " . $anio_letras . " ($y)";
}

if ($tipo_reporte == 'ing') {
    $rango_trayectos = [3, 4];
    $titulo_grado = "INGENIERO"; 
} else {
    $rango_trayectos = [0, 1, 2];
    $titulo_grado = "TÉCNICO SUPERIOR UNIVERSITARIO";
}

class PDF_Certificacion extends FPDF {
    function Header() {
        $margin = 15;
        if(file_exists('../../images/uptpc.png')) {
            $this->Image('../../images/uptpc.png', $margin, 10, 20);
        }
        $this->SetY(12);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 4, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 4, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->SetY(12);
        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 0, date('d/m/Y'), 0, 0, 'R');
        $this->SetY(28);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Ln(8);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, txt('CERTIFICACIÓN'), 0, 1, 'C');
        $this->Ln(2);
    }
}

$pdf = new PDF_Certificacion('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 10, 15);

// INTRODUCCIÓN
$pdf->SetFont('Arial', '', 9);
$pdf->Write(4, txt("Quien suscribe, "));
$pdf->SetFont('Arial', 'B', 9); $pdf->Write(4, txt("Dra. Blanca A. Crespo C."));
$pdf->SetFont('Arial', '', 9); $pdf->Write(4, txt(", titular de la cédula de identidad "));
$pdf->SetFont('Arial', 'B', 9); $pdf->Write(4, txt("V-10.959.330"));
$pdf->SetFont('Arial', '', 9); $pdf->Write(4, txt(", "));
$pdf->SetFont('Arial', 'B', 9); $pdf->Write(4, txt("Secretario del Consejo de Gestión Universitaria"));
$pdf->SetFont('Arial', '', 9); $pdf->Write(4, txt(" de la Universidad Politécnica Territorial de Puerto Cabello certifica al Ciudadano (a):"));
$pdf->Ln(7);

// DATOS ALUMNO
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(95, 5, txt('APELLIDOS Y NOMBRES'), 1, 0, 'C');
$pdf->Cell(35, 5, txt('CÉDULA'), 1, 0, 'C');
$pdf->Cell(56, 5, txt($etiqueta_tipo), 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(95, 6, txt(strtoupper($estudiante['nombre'])), 1, 0, 'C');
$pdf->Cell(35, 6, txt($cedula_estudiante), 1, 0, 'C');
$pdf->Cell(56, 6, txt($carrera['nombre_carrera']), 1, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 9);
$pdf->Write(4, txt("quien cursó y aprobó todas las unidades curriculares del Plan de Estudios del Programa Nacional de Formación para obtener el Título de "));
$pdf->SetFont('Arial', 'B', 9); $pdf->Write(4, txt($titulo_grado));
$pdf->SetFont('Arial', '', 9); $pdf->Write(4, txt(", logrando las siguientes calificaciones:"));
$pdf->Ln(6);

// TABLA
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(25, 5, txt('CÓDIGO'), 1, 0, 'C');
$pdf->Cell(90, 5, txt('UNIDAD CURRICULAR'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('UC'), 1, 0, 'C');
$pdf->Cell(12, 5, txt('NOTA'), 1, 0, 'C');
$pdf->Cell(18, 5, txt('LAPSO'), 1, 0, 'C');
$pdf->Cell(0, 5, txt('OBSERVACIÓN'), 1, 1, 'C');

$suma_ponderada_total = 0; $total_uc_general = 0;
$materias_por_trayecto = [];
while ($m = $materias_carrera->fetch_assoc()) {
    if (in_array((int)$m['trayecto'], $rango_trayectos)) $materias_por_trayecto[(int)$m['trayecto']][] = $m;
}

foreach ($materias_por_trayecto as $t_num => $materias) {
    $uc_t = 0; $suma_t = 0; $rows = [];
    foreach ($materias as $m) {
        $nota = (float)($notas_estudiante[$m['id_materia']]['trayecto_'.$t_num] ?? 0);
        $uc = (float)($m['creditos'] ?? 0);
        if ($nota >= 12) {
            $rows[] = ['c'=>$m['cod_materia'], 'n'=>$m['nombre_materia'], 'u'=>$uc, 'nt'=>$nota, 'l'=>$notas_estudiante[$m['id_materia']]['nombre_periodo']];
            $uc_t += $uc; $suma_t += ($nota * $uc);
        }
    }
    if (!empty($rows)) {
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(115, 5, txt($periodo_label . ": " . $t_num), 'L', 0, 'L');
        $pdf->SetFont('Arial', 'I', 6);
        $pdf->Cell(0, 5, txt("IRA $t_num: ".number_format($suma_t/$uc_t, 3)."  Total UC: $uc_t"), 'R', 1, 'R');
        $pdf->SetFont('Arial', '', 7);
        foreach ($rows as $r) {
            $pdf->Cell(25, 5, txt($r['c']), 1, 0, 'L');
            $pdf->Cell(90, 5, txt(substr($r['n'], 0, 65)), 1, 0, 'L');
            $pdf->Cell(12, 5, $r['u'], 1, 0, 'C');
            $pdf->Cell(12, 5, str_pad($r['nt'], 2, "0", STR_PAD_LEFT), 1, 0, 'C');
            $pdf->Cell(18, 5, txt($r['l']), 1, 0, 'C');
            $pdf->Cell(0, 5, '', 1, 1, 'C');
            $suma_ponderada_total += ($r['nt']*$r['u']); $total_uc_general += $r['u'];
        }
    }
}

// TOTALES E IRA
$pdf->Ln(4);
$ira_f = ($total_uc_general > 0) ? number_format($suma_ponderada_total/$total_uc_general, 3) : "0.000";
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 6, txt("Unidades de Créditos Cursadas y Aprobadas: $total_uc_general"), 1, 1, 'C');
$pdf->Cell(0, 6, txt("Índice de Rendimiento Académico: $ira_f"), 1, 1, 'C');

// PIE LEGAL
$pdf->Ln(3);
$pdf->SetFont('Arial', '', 6.5);
$pdf->MultiCell(0, 2.5, txt("Lineamiento de Evaluación del desempeño estudiantil en los PNF:\n1) Según Resolución N° 549, Gaceta Oficial N° 39.483, de fecha 09/08/2010, art. 31: La Escala de Evaluación era de 01 al 05, siendo la Nota Mínima Aprobatoria 03 pts.\nLineamiento de Evaluación del desempeño estudiantil en los PNF en el Marco de la Misión Sucre y Misión Alma Mater:\n1) Según Resolución N° 2593, Gaceta Oficial N° 39.839, de fecha 10/01/2012, art. 18: La Escala de Evaluación es de 01 al 20, siendo la Nota Mínima Aprobatoria 12 pts.\n2) Según Resolución N° 2593, Gaceta Oficial N° 39.839, de fecha 10/01/2012, art. 19: Para la UC Proyecto, la Nota Mínima Aprobatoria es de 16 pts."), 0, 'L');

// FECHA Y FIRMAS
$pdf->Ln(4);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 5, txt(fechaEnLetras()), 0, 1, 'C');

$pdf->Ln(15); 
$y_f = $pdf->GetY();
$pdf->Line(25, $y_f, 85, $y_f); 
$pdf->Line(130, $y_f, 190, $y_f);
$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 8);
$pdf_y = $pdf->GetY();
$pdf->SetXY(15, $pdf_y);
$pdf->MultiCell(80, 3.5, txt("Dra. Zorangel E. Aponte Q.\nJefa de Control de Estudios\nResolución N° 07-2022 de fecha 01/11/2022\nConsejo N° 07 de fecha 01/11/2022"), 0, 'C');
$pdf->SetXY(120, $pdf_y);
$pdf->MultiCell(80, 3.5, txt("Dra. Blanca A. Crespo C.\nSecretario del Consejo de Gestión Universitaria\nResolución N° 34 de fecha 20/07/2022\nGaceta Oficial Bolivariana de Venezuela\nN° 457.753 de fecha 22/07/2022"), 0, 'C');

ob_end_clean();
$pdf->Output('I', "Certificacion_Final.pdf");
exit();