<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// 1. OBTENCIÓN DE DATOS
$id_estudiante = isset($_GET['id']) ? intval($_GET['id']) : 0;

$nombre_est = "NOMBRE DEL ESTUDIANTE";
$cedula_est = "00.000.000";
$carrera_est = "PROGRAMA NACIONAL DE FORMACIÓN";

if ($id_estudiante > 0) {
    $query_user = "SELECT nombre, idusuario FROM users WHERE id = ? LIMIT 1";
    $stmt = $db->prepare($query_user);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    if ($res) {
        $nombre_est = strtoupper($res['nombre']);
        $cedula_est = $res['idusuario'];
    }

    $carrera_info = obtenerCarreraEstudiante($id_estudiante);
    if ($carrera_info) {
        $carrera_est = strtoupper($carrera_info['nombre_carrera']);
    }
}

// Función mejorada para fecha legal completa en letras
function getFechaLegalCompleta() {
    $meses = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    $dias_letras = ["", "UN (01)", "DOS (02)", "TRES (03)", "CUATRO (04)", "CINCO (05)", "SEIS (06)", "SIETE (07)", "OCHO (08)", "NUEVE (09)", "DIEZ (10)", 
                    "ONCE (11)", "DOCE (12)", "TRECE (13)", "CATORCE (14)", "QUINCE (15)", "DIECISÉIS (16)", "DIECISIETE (17)", "DIECIOCHO (18)", 
                    "DIECINUEVE (19)", "VEINTE (20)", "VEINTIÚN (21)", "VEINTIDÓS (22)", "VEINTITRÉS (23)", "VEINTICUATRO (24)", "VEINTICINCO (25)", 
                    "VEINTISÉIS (26)", "VEINTISIETE (27)", "VEINTIOCHO (28)", "VEINTINUEVE (29)", "TREINTA (30)", "TREINTA Y UN (31)"];
    
    // Diccionario simple para años (puedes ampliarlo si es necesario)
    $anios_letras = [
        2024 => "DOS MIL VEINTICUATRO",
        2025 => "DOS MIL VEINTICINCO",
        2026 => "DOS MIL VEINTISÉIS",
        2027 => "DOS MIL VEINTISIETE"
    ];

    $anio_actual = intval(date('Y'));
    $anio_texto = isset($anios_letras[$anio_actual]) ? $anios_letras[$anio_actual] : $anio_actual;

    return [
        'dia' => $dias_letras[intval(date('d'))],
        'mes' => $meses[intval(date('m'))],
        'anio_letras' => $anio_texto,
        'anio_num' => $anio_actual
    ];
}

$fecha = getFechaLegalCompleta();

function txt($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

class PDF_Retiro extends FPDF {
    function Header() {
        if(file_exists('../images/uptpc.png')) {
            $this->Image('../images/uptpc.png', 20, 15, 18);
        }
        $this->SetY(15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 3, txt('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 3, txt('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        $this->Cell(0, 3, txt('SECRETARÍA DEL CONSEJO DE GESTIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, txt('CONSTANCIA DE RETIRO DE DOCUMENTO'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-35);
        $this->SetFont('Arial', 'BI', 8);
        $this->Cell(0, 4, txt('TRANSFORMACIÓN UNIVERSITARIA CON CALIDAD Y PERTENENCIA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 3, txt('Urbanización la Elvira, Zona Industrial Santa Rosa, Galpón N° 8, Puerto Cabello'), 0, 1, 'C');
        $this->Cell(0, 3, txt('Número Telefónico: (0242) 3700494. Correo Electrónico: uptpccontroldeestudios03@gmail.com'), 0, 1, 'C');
        $this->Cell(0, 5, txt('Página 1'), 0, 0, 'R');
    }
}

$pdf = new PDF_Retiro('P', 'mm', 'Letter');
$pdf->SetMargins(25, 20, 25);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);
$lh = 8;

// Párrafo de apertura con datos de la Jefa
$pdf->Write($lh, txt("Quien suscribe "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("Dra. Zorangel E. Aponte Q."));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(", titular de la cédula de identidad "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("V.- 7.153.528"));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" Jefa de Control de Estudios de nuestra Institución, hace constar que el (la) Ciudadano (a) que se menciona a continuación."));

$pdf->Ln(15);

// Identificación del Estudiante
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, txt($nombre_est), 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 8, txt("Titular de la Cédula de Identidad " . $cedula_est . ", inscrita en esta casa de"), 0, 1, 'C');
$pdf->Cell(0, 8, txt("estudios y es cursante del Programa Nacional Formación en:"), 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, txt($carrera_est), 0, 1, 'C');

$pdf->Ln(10);

// Detalle del retiro
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt("Para el lapso académico y su vigencia corresponde, "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt("RETIRO DE DOCUMENTOS DE INSCRIPCIÓN COPIAS. EXPEDIENTE COMPLETO."));

$pdf->Ln(15);

// Fecha con año en letras
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt("Solicita traslado de la misma, documento que se emite en la ciudad de Puerto Cabello, a los "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt($fecha['dia']));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" días del mes de "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt($fecha['mes']));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt(" del año "));
$pdf->SetFont('Arial', 'B', 11);
$pdf->Write($lh, txt($fecha['anio_letras'] . " (" . $fecha['anio_num'] . ")"));
$pdf->SetFont('Arial', '', 11);
$pdf->Write($lh, txt("."));

$pdf->Ln(30);

// Bloque de Firma Final
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 5, txt('Dra. Zorangel E. Aponte Q.'), 0, 1, 'C');
$pdf->Cell(0, 5, txt('Jefe de Control de Estudios'), 0, 1, 'C');
$pdf->Cell(0, 4, txt('Resolución N° 01 de fecha 17/10/2022 Consejo N° 7'), 0, 1, 'C');
$pdf->Cell(0, 4, txt('De fecha 17/10/2022'), 0, 1, 'C');

ob_end_clean();
$pdf->Output('I', "Constancia_Retiro_".$cedula_est.".pdf");
exit();