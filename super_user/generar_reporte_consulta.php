<?php
// Archivo: generar_reporte_consulta.php
// Activar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Usar la ruta original que funcionaba
require_once __DIR__ . '/../fpdf/fpdf.php';
require_once('../funciones/functions.php');

// Función para manejar caracteres especiales
function t($texto) {
    if (function_exists('mb_convert_encoding')) {
        return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    } else {
        // Fallback si no existe mb_convert_encoding
        return utf8_decode($texto);
    }
}

// Obtener parámetros
$estudiante_id = isset($_GET['estudiante_id']) ? intval($_GET['estudiante_id']) : 0;
$estudiante_nombre = isset($_GET['nombre']) ? urldecode($_GET['nombre']) : 'SIN NOMBRE';
$estudiante_cedula = isset($_GET['cedula']) ? urldecode($_GET['cedula']) : 'SIN CEDULA';

// Verificar que tenemos los datos mínimos
if ($estudiante_id == 0) {
    die("Error: No se proporcionó ID de estudiante");
}

// Variables
$carrera_formateada = "NO ASIGNADA";
$materias_carrera = null;
$notas_estudiante = [];

// Cargar datos desde la base de datos
try {
    // Usar obtenerEstudiantePorId que ya existe en functions.php
    $estudiante_info = obtenerEstudiantePorId($estudiante_id);
    if ($estudiante_info) {
        $estudiante_nombre = $estudiante_info['nombre'] ?? $estudiante_nombre;
        $estudiante_cedula = $estudiante_info['idusuario'] ?? $estudiante_cedula;
        
        // Usar obtenerCarreraEstudiante que ya existe (modificada con tipo_formacion)
        $carrera = obtenerCarreraEstudiante($estudiante_id);
        
        if ($carrera) {
            // Formatear el nombre de la carrera usando la función nueva
            $carrera_formateada = formatearNombreCarrera(
                $carrera['nombre_carrera'] ?? '', 
                $carrera['tipo_formacion'] ?? ''
            );
            
            // Usar obtenerMateriasCarrera que ya existe (modificada con creditos)
            $materias_carrera = obtenerMateriasCarrera($carrera['id_carrera']);
            $notas_estudiante = obtenerNotasEstudianteConsulta($estudiante_id);
        }
    }
} catch (Exception $e) {
    die("Error al cargar datos: " . $e->getMessage());
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 10);
        
        // Logo si existe
        $logo_path = '../images/uptpc.png';
        if(file_exists($logo_path)) {
            $this->Image($logo_path, 10, 10, 20, 20);
        }
        
        $this->SetY(15);
        $this->Cell(0, 4, t('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
        $this->Cell(0, 4, t('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
        $this->Cell(0, 4, t('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
        
        // Fecha
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

    // Título
    $pdf->SetFont('Arial', 'BI', 11);
    $pdf->Cell(0, 7, 'HISTORIAL ACADEMICO', 0, 1, 'C');
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

    // Encabezado de la Tabla
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(20, 5, 'CODIGO', 1, 0, 'C', true);
    $pdf->Cell(85, 5, 'NOMBRE DE LA ASIGNATURA', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'TRIM', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'UC', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'VC', 1, 0, 'C', true);
    $pdf->Cell(15, 5, 'NOTAS', 1, 0, 'C', true);
    $pdf->Cell(15, 5, 'LAPSO', 1, 0, 'C', true);
    $pdf->Cell(15, 5, 'TIPO', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'CUR.', 1, 1, 'C', true);

    // Datos de la Tabla agrupados por Trayecto
    if ($materias_carrera && $materias_carrera->num_rows > 0) {
        // Agrupar materias por trayecto
        $materias_agrupadas = [];
        $materias_carrera->data_seek(0); // Reiniciar puntero
        
        while ($m = $materias_carrera->fetch_assoc()) {
            $trayecto = $m['trayecto'];
            if (!isset($materias_agrupadas[$trayecto])) {
                $materias_agrupadas[$trayecto] = [];
            }
            $materias_agrupadas[$trayecto][] = $m;
        }

        // Ordenar por trayecto
        ksort($materias_agrupadas);
        
        foreach ($materias_agrupadas as $trayecto => $lista) {
            // Encabezado del trayecto
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(190, 5, t('Trayecto: ' . $trayecto), 1, 1, 'L', true);
            
            // Materias del trayecto
            $pdf->SetFont('Arial', '', 7);
            foreach ($lista as $materia) {
                $id_mat = $materia['id_materia'];
                
                // Nota y lapso
                $nota_v = '';
                $laps_v = '';
                if (isset($notas_estudiante[$id_mat])) {
                    $nota_key = 'trayecto_' . $trayecto;
                    if (isset($notas_estudiante[$id_mat][$nota_key]) && $notas_estudiante[$id_mat][$nota_key] !== null) {
                        $nota_v = $notas_estudiante[$id_mat][$nota_key];
                    }
                    if (isset($notas_estudiante[$id_mat]['nombre_periodo'])) {
                        $laps_v = $notas_estudiante[$id_mat]['nombre_periodo'];
                    }
                }
                
                // Unidades de crédito - ESTA ES LA LÍNEA IMPORTANTE
                $uc = isset($materia['creditos']) && $materia['creditos'] !== null ? $materia['creditos'] : '0';
                
                $pdf->Cell(20, 5, $materia['cod_materia'] ?? '', 1, 0, 'C');
                $pdf->Cell(85, 5, t(substr($materia['nombre_materia'] ?? '', 0, 55)), 1, 0, 'L');
                $pdf->Cell(10, 5, '0', 1, 0, 'C');
                $pdf->Cell(10, 5, $uc, 1, 0, 'C'); // Mostrar unidades de crédito
                $pdf->Cell(10, 5, '1', 1, 0, 'C');
                $pdf->Cell(15, 5, $nota_v, 1, 0, 'C');
                $pdf->Cell(15, 5, $laps_v, 1, 0, 'C');
                $pdf->Cell(15, 5, '', 1, 0, 'C');
                $pdf->Cell(10, 5, '', 1, 1, 'C');
            }
        }
        
        // Calcular estadísticas de créditos
        $total_creditos = 0;
        $creditos_aprobados = 0;
        $materias_carrera->data_seek(0); // Reiniciar puntero
        
        while ($materia = $materias_carrera->fetch_assoc()) {
            $id_mat = $materia['id_materia'];
            $trayecto = $materia['trayecto'];
            $creditos = intval($materia['creditos'] ?? 0);
            
            $total_creditos += $creditos;
            
            // Verificar si la materia está aprobada
            if (isset($notas_estudiante[$id_mat])) {
                $nota_key = 'trayecto_' . $trayecto;
                if (isset($notas_estudiante[$id_mat][$nota_key]) && 
                    $notas_estudiante[$id_mat][$nota_key] !== null &&
                    $notas_estudiante[$id_mat][$nota_key] >= 12) {
                    $creditos_aprobados += $creditos;
                }
            }
        }
    } else {
        $pdf->Cell(190, 10, t('NO HAY MATERIAS REGISTRADAS'), 1, 1, 'C');
        $total_creditos = 0;
        $creditos_aprobados = 0;
    }

    // Bloque de Resumen Final
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(100, 5, 'RESUMEN:', 0, 0);
    $pdf->SetFont('Arial', '', 8);
    $emitido_por = strtoupper($_SESSION['user']['nombre'] ?? $_SESSION['user']['username'] ?? 'DESCONOCIDO');
    $pdf->Cell(90, 5, 'Emitido por: ' . t($emitido_por), 0, 1, 'R');

    $pdf->Cell(60, 5, 'Total Creditos: ' . $total_creditos, 0, 0);
    $pdf->Cell(60, 5, 'Creditos Aprobados: ' . $creditos_aprobados, 0, 1);

    $pdf->Cell(60, 5, t('Índice de Rendimiento Académico: 16.779'), 0, 0);
    $pdf->SetX(110);
    $pdf->Cell(30, 5, 'GENERAL:', 1, 0);
    $pdf->Cell(25, 5, 'APROBADOS:', 1, 0);
    $pdf->Cell(25, 5, 'FALTANTES:', 1, 1);

    $pdf->SetX(110);
    $pdf->Cell(30, 5, 'EQUIVALENTES:', 1, 0);
    $pdf->Cell(50, 5, 'MAX. A CURSAR:', 1, 1);

    $pdf->Ln(4);
    $pdf->SetFont('Arial', '', 6.5);
    $pdf->MultiCell(0, 3, t('Institución autorizada para gestionar el Programa Nacional de Formación según Gaceta oficial de la República Bolivariana de Venezuela N° 39721 de fecha 26 de Julio del 2011'), 0, 'L');

    // Nombre del archivo
    $nombre_archivo = 'Historial_' . preg_replace('/[^a-zA-Z0-9]/', '_', $estudiante_cedula) . '_' . date('Ymd_His') . '.pdf';
    
    $pdf->Output('I', $nombre_archivo);
    
} catch (Exception $e) {
    die("Error al generar PDF: " . $e->getMessage());
}