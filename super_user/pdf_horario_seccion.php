<?php
// PDF: Horario de Sección (FPDF)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../fpdf/fpdf.php';
require_once('../funciones/functions.php');

function t($texto) {
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    } else {
        return utf8_decode($texto);
    }
}

$seccion_id = isset($_GET['seccion_id']) ? intval($_GET['seccion_id']) : 0;
if ($seccion_id <= 0) die('Sección no especificada');

$seccion = obtenerDetalleSeccion($db, $seccion_id);
if (!$seccion) die('Sección no encontrada');

$horarios_raw = obtenerHorariosSeccion($db, $seccion_id);
$horarios = is_array($horarios_raw) ? $horarios_raw : [];

// Preparar horarios por día
$dias_semana = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$horarios_por_dia = array_fill(0, 6, []);
foreach ($horarios as $h) {
    $dia = (int)$h['dia'];
    $hora_inicio = date('H:i', strtotime($h['hora_inicio']));
    $hora_fin = date('H:i', strtotime($h['hora_fin']));
    $horarios_por_dia[$dia][] = [
        'materia' => $h['nombre_materia'],
        'docente' => $h['nombre_docente'],
        'aula' => $h['aula'],
        'hora_inicio' => $hora_inicio,
        'hora_fin' => $hora_fin
    ];
}

// Horas a mostrar
$horas = [];
for ($h = 7; $h <= 16; $h++) $horas[] = sprintf('%02d:00', $h);

class PDF extends FPDF {
    function Header() {
        global $seccion;
        $this->SetFont('Arial','B',10);
        $logo = __DIR__ . '/../images/uptpc.png';
        if (file_exists($logo)) $this->Image($logo,10,8,20,20);
        $this->SetY(12);
        $this->Cell(0,5,t('REPÚBLICA BOLIVARIANA DE VENEZUELA'),0,1,'C');
        $this->Cell(0,5,t('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'),0,1,'C');
        $this->Ln(3);
        $this->SetFont('Arial','B',12);
        $this->Cell(0,6,t('Horario de Clases - Sección: ' . ($seccion['codigo_seccion'] ?? '')),0,1,'C');
        $this->Ln(2);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,t('Departamento de Control de Estudios - UPTPC'),0,0,'C');
    }

    // Devuelve el ancho utilizable de la página (excluye márgenes)
    public function getInnerWidth() {
        return $this->GetPageWidth() - $this->lMargin - $this->rMargin;
    }
}

try {
    $pdf = new PDF('P','mm','A4');
    $pdf->SetMargins(8,8,8);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',9);

    // Información de sección
    $pdf->Cell(0,6,t('Carrera: ' . ($seccion['nombre_carrera'] ?? '')),0,1,'L');
    $pdf->Cell(0,6,t('Trayecto: ' . ($seccion['numero_trayecto'] ?? '')),0,1,'L');
    $pdf->Cell(0,6,t('Período: ' . ($seccion['nombre_periodo'] ?? '')),0,1,'L');
    $pdf->Ln(4);

    // Tabla: columnas
    $col_hora = 20;
    $page_width = $pdf->getInnerWidth();
    $col_day = ($page_width - $col_hora) / 6;

    // Encabezado
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell($col_hora,8,t('Hora'),1,0,'C');
    foreach ($dias_semana as $d) $pdf->Cell($col_day,8,t($d),1,0,'C');
    $pdf->Ln();

    $pdf->SetFont('Arial','',8);
    // Preparar mapa de ocupación para saltos
    $ocupado = array_fill(0,6, array_fill(0, count($horas), false));

    // Construir bloques por día con índices de inicio y duración en horas
    $blocks_by_day = array_fill(0, 6, []);
    foreach ($horarios as $h) {
        $d = (int)$h['dia'];
        $start = date('H:i', strtotime($h['hora_inicio']));
        $end = date('H:i', strtotime($h['hora_fin']));
        // Buscar índice de inicio; aproximar al piso si no coincide exactamente
        $start_index = array_search($start, $horas);
        if ($start_index === false) {
            $start_ts = strtotime($start);
            $start_index = 0;
            foreach ($horas as $idx => $h_label) {
                if ($start_ts >= strtotime($h_label)) $start_index = $idx;
            }
        }
        $dur_h = max(1, (int) ceil((strtotime($end) - strtotime($start)) / 3600));
        $blocks_by_day[$d][$start_index] = [
            'dur' => $dur_h,
            'materia' => $h['nombre_materia'],
            'docente' => $h['nombre_docente'],
            'aula' => $h['aula'],
            'hora_inicio' => $start,
            'hora_fin' => $end
        ];
        // Marcar ocupadas
        for ($k = $start_index; $k < min(count($horas), $start_index + $dur_h); $k++) {
            $ocupado[$d][$k] = true;
        }
    }

    // Estilo de celdas similar a la vista HTML: mostrar bloque en la fila de inicio y "↳" en las continuaciones
    $rowHeight = 10; // mm por hora

    foreach ($horas as $hi => $hora) {
        $pdf->Cell($col_hora, $rowHeight, $hora, 1, 0, 'C');

        for ($d = 0; $d <= 5; $d++) {
            // Si empieza un bloque aquí
            if (isset($blocks_by_day[$d][$hi])) {
                $b = $blocks_by_day[$d][$hi];
                $cellH = $rowHeight * $b['dur'];

                // Fondo suave
                $pdf->SetFillColor(235, 243, 255);
                $x = $pdf->GetX();
                $y = $pdf->GetY();
                $pdf->Rect($x, $y, $col_day, $cellH, 'F');
                $pdf->Rect($x, $y, $col_day, $cellH);

                // Contenido: nombre en negrita y datos debajo
                $pdf->SetXY($x + 2, $y + 2);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->MultiCell($col_day - 4, 4, t($b['materia']), 0, 'L');
                $pdf->SetFont('Arial', '', 7);
                $pdf->MultiCell($col_day - 4, 3, t($b['docente'] . ' | ' . $b['aula'] . ' | ' . $b['hora_inicio'] . ' - ' . $b['hora_fin']), 0, 'L');

                // Volver a la posición a la derecha de esta celda
                $pdf->SetXY($x + $col_day, $y);

            } elseif (!empty($ocupado[$d][$hi])) {
                // Continuación de un bloque: símbolo de continuación
                $pdf->Cell($col_day, $rowHeight, '↳', 1, 0, 'C');
            } else {
                // Celda vacía
                $pdf->Cell($col_day, $rowHeight, '', 1, 0, 'C');
            }
        }
        $pdf->Ln();
    }

    // Leyenda
    $pdf->Ln(4);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(0,6,t('Detalle de materias y docentes'),0,1,'L');
    $pdf->SetFont('Arial','',8);
    foreach ($horarios as $h) {
        $line = $h['nombre_materia'] . ' | ' . $h['nombre_docente'] . ' | ' . $h['aula'] . ' | ' . date('H:i', strtotime($h['hora_inicio'])) . '-' . date('H:i', strtotime($h['hora_fin']));
        $pdf->Cell(0,5,t($line),0,1,'L');
    }

    $filename = 'Horario_Seccion_' . preg_replace('/[^A-Za-z0-9]/','_', $seccion['codigo_seccion']) . '_' . date('Ymd_His') . '.pdf';
    $pdf->Output('I', $filename);
    exit();
} catch (Exception $e) {
    die('Error al generar PDF: ' . $e->getMessage());
}
