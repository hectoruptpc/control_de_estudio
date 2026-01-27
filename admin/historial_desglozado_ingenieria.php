<?php
// Archivo: historial_desglozado_ingenieria.php
require_once __DIR__ . '/../fpdf/fpdf.php';
require_once('../funciones/functions.php');

// Función para manejar caracteres especiales
function t($texto) {
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    } else {
        return utf8_decode($texto);
    }
}

// Obtener parámetros
$estudiante_id = isset($_GET['estudiante_id']) ? intval($_GET['estudiante_id']) : 0;
$estudiante_nombre = isset($_GET['nombre']) ? urldecode($_GET['nombre']) : 'SIN NOMBRE';
$estudiante_cedula = isset($_GET['cedula']) ? urldecode($_GET['cedula']) : 'SIN CEDULA';

if ($estudiante_id == 0) {
    die("Error: No se proporcionó ID de estudiante");
}

// Variables
$carrera_formateada = "NO ASIGNADA";
$historial_notas = [];

// Cargar datos
try {
    $estudiante_info = obtenerEstudiantePorId($estudiante_id);
    if ($estudiante_info) {
        $estudiante_nombre = $estudiante_info['nombre'] ?? $estudiante_nombre;
        $estudiante_cedula = $estudiante_info['idusuario'] ?? $estudiante_cedula;
        
        $carrera = obtenerCarreraEstudiante($estudiante_id);
        
        if ($carrera) {
            $carrera_formateada = formatearNombreCarrera(
                $carrera['nombre_carrera'] ?? '', 
                $carrera['tipo_formacion'] ?? ''
            );
            
            // Obtener historial completo
            $historial_completo = obtenerHistorialNotasDesglozado($estudiante_id);
            
            // Filtrar solo trayectos 3-4 para Ingeniería
            $historial_notas = array_filter($historial_completo, function($nota) {
                return $nota['trayecto'] >= 3 && $nota['trayecto'] <= 4;
            });
        }
    }
} catch (Exception $e) {
    die("Error al cargar datos: " . $e->getMessage());
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 10);
        
        $logo_path = '../images/uptpc.png';
        if(file_exists($logo_path)) {
            $this->Image($logo_path, 10, 10, 20, 20);
        }
        
        $this->SetY(15);
        $this->Cell(0, 4, t('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 4, t('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 4, t('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        
        $this->SetFont('Arial', '', 9);
        $this->SetX(-40);
        $this->Cell(30, 4, date('d/m/Y'), 0, 1, 'R');

        $this->SetY(38);
    }

    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 10, t('Este Documento NO ES VALIDO sin la firma y Sello del Departamento de Control De Estudios'), 0, 0, 'C');
    }
}

// Crear PDF
try {
    $pdf = new PDF('P', 'mm', 'A4');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 25);
    $pdf->AddPage();

    // Título ESPECÍFICO
    $pdf->SetFont('Arial', 'BI', 11);
    $pdf->Cell(0, 7, 'HISTORIAL DESGLOZADO DE NOTAS - INGENIERÍA (TRAYECTOS 3-4)', 0, 1, 'C');
    $pdf->Ln(2);

    // Información del Estudiante
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(65, 5, 'CEDULA: ' . $estudiante_cedula, 0, 0);
    $pdf->Cell(90, 5, 'NOMBRE: ' . t(strtoupper($estudiante_nombre)), 0, 0);
    $pdf->Cell(0, 5, 'ACT.: 1', 0, 1, 'R');

    // Mostrar PNF o PTF
    if (stripos($carrera_formateada, 'PNF ') === 0) {
        $pdf->Cell(155, 5, 'PNF: ' . substr($carrera_formateada, 4), 0, 0);
    } elseif (stripos($carrera_formateada, 'PTF ') === 0) {
        $pdf->Cell(155, 5, 'PTF: ' . substr($carrera_formateada, 4), 0, 0);
    } else {
        $pdf->Cell(155, 5, 'CARRERA: ' . $carrera_formateada, 0, 0);
    }
    $pdf->Cell(0, 5, 'PLAN: C', 0, 1, 'R');
    $pdf->Ln(2);

    // Encabezado de la Tabla DESGLOZADA
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('Arial', 'B', 6);
    
    // Encabezado principal
    $pdf->Cell(15, 5, 'CODIGO', 1, 0, 'C', true);
    $pdf->Cell(55, 5, 'MATERIA', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'TR', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'UC', 1, 0, 'C', true);
    
    // Columnas para Ingeniería (T3, T4)
    $pdf->Cell(15, 5, 'TRAYECTO 3', 1, 0, 'C', true);
    $pdf->Cell(15, 5, 'TRAYECTO 4', 1, 0, 'C', true);
    $pdf->Cell(20, 5, '', 1, 0, 'C', true); // Espacio vacío
    
    $pdf->Cell(20, 5, 'PERIODO', 1, 0, 'C', true);
    $pdf->Cell(15, 5, 'FECHA', 1, 0, 'C', true);
    $pdf->Cell(25, 5, 'APROBADO POR', 1, 1, 'C', true);

    // Agrupar materias por código
    $materias_agrupadas = [];
    foreach ($historial_notas as $nota) {
        $codigo = $nota['cod_materia'];
        if (!isset($materias_agrupadas[$codigo])) {
            $materias_agrupadas[$codigo] = [
                'nombre' => $nota['nombre_materia'],
                'trayecto' => $nota['trayecto'],
                'creditos' => $nota['creditos'],
                'inscripciones' => []
            ];
        }
        $materias_agrupadas[$codigo]['inscripciones'][] = $nota;
    }

    // Mostrar historial desglozado
    $total_materias = count($materias_agrupadas);
    $total_inscripciones = count($historial_notas);
    
    if ($total_inscripciones > 0) {
        $pdf->SetFont('Arial', '', 6);
        
        foreach ($materias_agrupadas as $codigo => $materia) {
            // Mostrar cada inscripción de esta materia
            foreach ($materia['inscripciones'] as $index => $inscripcion) {
                // Solo mostrar nombre de materia en la primera fila
                if ($index == 0) {
                    $pdf->Cell(15, 5, $codigo, 1, 0, 'C');
                    $pdf->Cell(55, 5, t(substr($materia['nombre'], 0, 35)), 1, 0, 'L');
                    $pdf->Cell(10, 5, $materia['trayecto'], 1, 0, 'C');
                    $pdf->Cell(10, 5, $materia['creditos'] ?? '0', 1, 0, 'C');
                } else {
                    // Filas siguientes, dejar en blanco los datos de la materia
                    $pdf->Cell(15, 5, '', 1, 0, 'C');
                    $pdf->Cell(55, 5, '', 1, 0, 'L');
                    $pdf->Cell(10, 5, '', 1, 0, 'C');
                    $pdf->Cell(10, 5, '', 1, 0, 'C');
                }
                
                // Mostrar notas de Ingeniería (T3, T4)
                $pdf->Cell(15, 5, $inscripcion['trayecto_3'] !== null ? $inscripcion['trayecto_3'] : '-', 1, 0, 'C');
                $pdf->Cell(15, 5, $inscripcion['trayecto_4'] !== null ? $inscripcion['trayecto_4'] : '-', 1, 0, 'C');
                $pdf->Cell(20, 5, '', 1, 0, 'C'); // Espacio vacío
                
                // Información de la inscripción
                $pdf->Cell(20, 5, substr($inscripcion['nombre_periodo'] ?? '', 0, 10), 1, 0, 'C');
                
                // Fecha
                $fecha = $inscripcion['fecha_registro'] ? date('d/m/y', strtotime($inscripcion['fecha_registro'])) : '-';
                $pdf->Cell(15, 5, $fecha, 1, 0, 'C');
                
                // Aprobado por
                $pdf->Cell(25, 5, substr($inscripcion['nombre_admin'] ?? '', 0, 12), 1, 1, 'C');
            }
            
            // Línea separadora entre materias
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Cell(190, 0, '', 'T', 1);
            $pdf->SetDrawColor(0, 0, 0);
        }
    } else {
        $pdf->Cell(190, 10, t('NO HAY HISTORIAL DE NOTAS INGENIERÍA (TRAYECTOS 3-4)'), 1, 1, 'C');
    }

    // Estadísticas
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(100, 5, 'ESTADÍSTICAS INGENIERÍA DESGLOZADAS:', 0, 0);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(90, 5, 'Emitido por: YANOSKI CALATRAVA', 0, 1, 'R');

    $pdf->Cell(60, 5, 'Total Materias Ing.: ' . $total_materias, 0, 0);
    $pdf->Cell(60, 5, 'Total Inscripciones: ' . $total_inscripciones, 0, 1);

    $pdf->Cell(60, 5, 'Promedio Inscripciones: ' . ($total_materias > 0 ? round($total_inscripciones / $total_materias, 1) : '0'), 0, 0);
    
    // Calcular estadísticas de aprobación
    $aprobadas_t3 = 0; $aprobadas_t4 = 0;
    foreach ($historial_notas as $nota) {
        if ($nota['trayecto_3'] !== null && $nota['trayecto_3'] >= 12) $aprobadas_t3++;
        if ($nota['trayecto_4'] !== null && $nota['trayecto_4'] >= 12) $aprobadas_t4++;
    }
    
    $pdf->Cell(60, 5, 'Aprob. T3: ' . $aprobadas_t3 . ' / T4: ' . $aprobadas_t4, 0, 1);

    $pdf->Ln(2);
    $pdf->SetFont('Arial', '', 6.5);
    $pdf->MultiCell(0, 3, t('Institución autorizada para gestionar el Programa Nacional de Formación según Gaceta oficial de la República Bolivariana de Venezuela N° 39721 de fecha 26 de Julio del 2011'), 0, 'L');

    $nombre_archivo = 'Historial_Desglozado_Ingenieria_' . preg_replace('/[^a-zA-Z0-9]/', '_', $estudiante_cedula) . '_' . date('Ymd_His') . '.pdf';
    $pdf->Output('I', $nombre_archivo);
    
} catch (Exception $e) {
    die("Error al generar PDF: " . $e->getMessage());
}