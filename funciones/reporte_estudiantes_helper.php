<?php
/**
 * ==============================================================================
 * GENERADOR DE REPORTE PDF DE ESTUDIANTES - UPTPC
 * Generación oficial de listado de estudiantes filtrados con resumen estadístico.
 * 
 * Formato institucional en orientación horizontal (Landscape) con:
 * - Soporte nativo para envíos masivos vía POST y fallback GET.
 * - Membrete oficial de la UPTPC y MPPEU con logos institucionales.
 * - Tabla estructurada con paginación automática y cabecera fija por página.
 * - Resumen estadístico (totales, géneros, estatus, edades).
 * ==============================================================================
 */

require_once __DIR__ . '/../fpdf/fpdf.php';
require_once __DIR__ . '/functions.php';

if (!function_exists('conv_pdf_estudiantes')) {
    function conv_pdf_estudiantes($texto) {
        if ($texto === null || $texto === '') return '';
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding((string)$texto, 'ISO-8859-1', 'UTF-8');
        } elseif (function_exists('iconv')) {
            return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string)$texto);
        } else {
            return @utf8_decode((string)$texto);
        }
    }
}

if (!function_exists('safe_pdf_substr')) {
    function safe_pdf_substr($texto, $longitudMax) {
        if ($texto === null || $texto === '') return '';
        $str = (string)$texto;
        if (function_exists('mb_substr')) {
            return mb_substr($str, 0, $longitudMax, 'UTF-8');
        }
        return substr($str, 0, $longitudMax);
    }
}

if (!class_exists('ReporteEstudiantesPDF')) {
    class ReporteEstudiantesPDF extends FPDF {
        public $admin_nombre = 'Administración';
        public $total_estudiantes = 0;
        public $mostrar_cabecera_tabla = true;

        function Header() {
            // Logos institucionales
            $logo_uptpc = __DIR__ . '/../images/uptpc.png';
            if (file_exists($logo_uptpc)) {
                $this->Image($logo_uptpc, 10, 8, 16, 16);
            }
            $logo_mppeu = __DIR__ . '/../images/mppeu_bb.png';
            if (file_exists($logo_mppeu)) {
                $this->Image($logo_mppeu, 270, 8, 17, 16);
            }

            $this->SetY(8);
            $this->SetTextColor(0, 0, 0);
            $this->SetDrawColor(0, 0, 0);

            // Membrete República
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(0, 3.5, conv_pdf_estudiantes('REPÚBLICA BOLIVARIANA DE VENEZUELA'), 0, 1, 'C');
            $this->Cell(0, 3.5, conv_pdf_estudiantes('MINISTERIO DEL PODER POPULAR PARA LA EDUCACIÓN UNIVERSITARIA'), 0, 1, 'C');
            $this->Cell(0, 3.5, conv_pdf_estudiantes('UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO'), 0, 1, 'C');
            $this->SetFont('Arial', '', 7.5);
            $this->Cell(0, 3.5, conv_pdf_estudiantes('DEPARTAMENTO DE CONTROL DE ESTUDIOS'), 0, 1, 'C');
            
            // Línea separadora
            $this->SetLineWidth(0.3);
            $this->Line(10, 24, 287, 24);
            $this->Ln(3);

            // Título Principal
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(0, 4.5, conv_pdf_estudiantes('REPORTE OFICIAL DE ESTUDIANTES'), 0, 1, 'C');

            // Subtítulo con fecha y detalles
            $this->SetFont('Arial', '', 7.5);
            $subt = "FECHA DE EMISIÓN: " . date('d/m/Y h:i A');
            $subt .= "   |   GENERADO POR: " . strtoupper($this->admin_nombre);
            $subt .= "   |   TOTAL REGISTROS: " . $this->total_estudiantes;
            $this->Cell(0, 3.5, conv_pdf_estudiantes($subt), 0, 1, 'C');
            $this->Ln(2);

            // Cabecera de la tabla en páginas subsecuentes
            if ($this->PageNo() > 1 && $this->mostrar_cabecera_tabla) {
                $this->imprimirCabeceraTabla();
            }
        }

        function Footer() {
            $this->SetY(-10);
            $this->SetFont('Arial', 'I', 7);
            $this->SetTextColor(80, 80, 80);
            $this->Cell(130, 4, conv_pdf_estudiantes('UPTPC - Sistema de Control de Estudios | Reporte Oficial de Estudiantes'), 0, 0, 'L');
            $this->Cell(0, 4, conv_pdf_estudiantes('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
        }

        function imprimirCabeceraTabla() {
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(0, 51, 102); // Azul institucional
            $this->SetDrawColor(0, 51, 102);
            $this->SetTextColor(255, 255, 255);

            // Anchos totales = 277 mm
            $this->Cell(8, 6, conv_pdf_estudiantes('N°'), 1, 0, 'C', true);
            $this->Cell(22, 6, conv_pdf_estudiantes('CÉDULA'), 1, 0, 'C', true);
            $this->Cell(48, 6, conv_pdf_estudiantes('NOMBRES Y APELLIDOS'), 1, 0, 'L', true);
            $this->Cell(44, 6, conv_pdf_estudiantes('CORREO ELECTRÓNICO'), 1, 0, 'L', true);
            $this->Cell(22, 6, conv_pdf_estudiantes('TELÉFONO'), 1, 0, 'C', true);
            $this->Cell(48, 6, conv_pdf_estudiantes('CARRERA / PNF'), 1, 0, 'L', true);
            $this->Cell(18, 6, conv_pdf_estudiantes('GÉNERO'), 1, 0, 'C', true);
            $this->Cell(12, 6, conv_pdf_estudiantes('EDAD'), 1, 0, 'C', true);
            $this->Cell(19, 6, conv_pdf_estudiantes('EDO. CIVIL'), 1, 0, 'C', true);
            $this->Cell(16, 6, conv_pdf_estudiantes('STATUS'), 1, 0, 'C', true);
            $this->Cell(20, 6, conv_pdf_estudiantes('F. INGRESO'), 1, 1, 'C', true);

            // Restablecer estilos para filas
            $this->SetTextColor(0, 0, 0);
            $this->SetDrawColor(200, 200, 200);
            $this->SetFont('Arial', '', 7);
        }
    }
}

if (!function_exists('generarPDFReporteEstudiantes')) {
    function generarPDFReporteEstudiantes($ids, $incluirEstadisticas = 'si', $admin_nombre = '') {
        global $db;

        // Limpiar cualquier búfer de salida para no corromper el binario PDF
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Sanitizar array de IDs
        $idsArray = [];
        if (is_array($ids)) {
            $idsArray = array_filter(array_map('intval', $ids));
        } elseif (!empty($ids)) {
            $idsArray = array_filter(array_map('intval', explode(',', (string)$ids)));
        }

        if (empty($idsArray)) {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Error</title><link rel="stylesheet" href="../funciones/bootstrap-4.6.2-dist/css/bootstrap.min.css"></head><body class="p-5 text-center"><div class="alert alert-warning d-inline-block">No se seleccionó ningún estudiante para generar el reporte.</div><br><button onclick="window.close()" class="btn btn-secondary mt-3">Cerrar pestaña</button></body></html>');
        }

        $idsString = implode(',', $idsArray);

        // Consultar estudiantes seleccionados
        $query = "SELECT 
            u.id,
            u.idusuario,
            u.nombre,
            u.email,
            u.tlf,
            u.cel,
            u.carrera,
            u.genero,
            u.fecha_nac,
            u.edo_civil,
            u.status,
            u.fecha_ingreso,
            c.nombre_carrera
        FROM users u
        LEFT JOIN carreras c ON u.carrera = c.id_carrera
        WHERE u.id IN ($idsString)
        ORDER BY u.nombre ASC";

        $result = $db->query($query);
        $estudiantes = [];

        if ($result && $result->num_rows > 0) {
            $hoy = new DateTime();
            while ($row = $result->fetch_assoc()) {
                // Calcular edad
                $edad = '';
                if (!empty($row['fecha_nac'])) {
                    try {
                        $fechaNac = new DateTime($row['fecha_nac']);
                        $edad = $fechaNac->diff($hoy)->y;
                    } catch (Exception $e) {
                        $edad = '';
                    }
                }
                $row['edad'] = $edad;
                $row['telefono'] = !empty($row['tlf']) ? $row['tlf'] : ($row['cel'] ?? '');
                $estudiantes[] = $row;
            }
        }

        if (empty($estudiantes)) {
            die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Error</title><link rel="stylesheet" href="../funciones/bootstrap-4.6.2-dist/css/bootstrap.min.css"></head><body class="p-5 text-center"><div class="alert alert-danger d-inline-block">No se encontraron registros de estudiantes con los IDs proporcionados.</div><br><button onclick="window.close()" class="btn btn-secondary mt-3">Cerrar pestaña</button></body></html>');
        }

        $pdf = new ReporteEstudiantesPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->admin_nombre = $admin_nombre ?: 'Control de Estudios';
        $pdf->total_estudiantes = count($estudiantes);
        $pdf->SetMargins(10, 8, 10);
        $pdf->SetAutoPageBreak(true, 12);
        $pdf->AddPage();

        // -------------------------------------------------------------
        // RESUMEN ESTADÍSTICO
        // -------------------------------------------------------------
        if (strtolower($incluirEstadisticas) === 'si') {
            $total = count($estudiantes);
            $activos = 0;
            $inactivos = 0;
            $masculinos = 0;
            $femeninos = 0;
            $menores = 0;
            $mayores = 0;

            foreach ($estudiantes as $e) {
                if ((int)$e['status'] === 1) {
                    $activos++;
                } else {
                    $inactivos++;
                }

                $gen = strtoupper(trim($e['genero'] ?? ''));
                if ($gen === 'MASCULINO' || $gen === 'M') {
                    $masculinos++;
                } elseif ($gen === 'FEMENINO' || $gen === 'F') {
                    $femeninos++;
                }

                if ($e['edad'] !== '') {
                    if ((int)$e['edad'] < 18) {
                        $menores++;
                    } else {
                        $mayores++;
                    }
                }
            }

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetFillColor(241, 245, 249);
            $pdf->SetDrawColor(0, 51, 102);
            $pdf->SetLineWidth(0.2);

            // Caja contenedor del resumen
            $xInicial = 10;
            $yInicial = $pdf->GetY();
            $pdf->Rect($xInicial, $yInicial, 277, 16, 'DF');

            $pdf->SetXY($xInicial + 2, $yInicial + 1.5);
            $pdf->SetTextColor(0, 51, 102);
            $pdf->Cell(0, 4, conv_pdf_estudiantes('RESUMEN ESTADÍSTICO DE ESTUDIANTES SELECCIONADOS'), 0, 1, 'L');

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 7.5);

            // Fila 1 de datos estadísticos
            $pdf->SetX($xInicial + 4);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Total Estudiantes: ") . $total, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Activos: ") . $activos, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Inactivos: ") . $inactivos, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Menores de 18 años: ") . $menores, 0, 1);

            // Fila 2 de datos estadísticos
            $pdf->SetX($xInicial + 4);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Masculinos: ") . $masculinos, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Femeninos: ") . $femeninos, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Mayores de 18 años: ") . $mayores, 0, 0);
            $pdf->Cell(45, 4.5, conv_pdf_estudiantes("Otros / No especificado: ") . ($total - $masculinos - $femeninos), 0, 1);

            $pdf->SetY($yInicial + 18);
        }

        // -------------------------------------------------------------
        // CABECERA Y CUERPO DE LA TABLA
        // -------------------------------------------------------------
        $pdf->imprimirCabeceraTabla();

        $numero = 1;
        foreach ($estudiantes as $e) {
            // Alternar color de fondo en filas
            $fill = ($numero % 2 === 0);
            if ($fill) {
                $pdf->SetFillColor(248, 249, 250);
            } else {
                $pdf->SetFillColor(255, 255, 255);
            }

            $statusText = ((int)$e['status'] === 1) ? 'Activo' : 'Inactivo';
            $fechaIngreso = !empty($e['fecha_ingreso']) ? date('d/m/Y', strtotime($e['fecha_ingreso'])) : '-';
            $carreraNombre = !empty($e['nombre_carrera']) ? $e['nombre_carrera'] : ($e['carrera'] ?: 'Sin Carrera');

            $pdf->Cell(8, 5.5, $numero, 1, 0, 'C', true);
            $pdf->Cell(22, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['idusuario'], 15)), 1, 0, 'C', true);
            $pdf->Cell(48, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['nombre'], 30)), 1, 0, 'L', true);
            $pdf->Cell(44, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['email'], 28)), 1, 0, 'L', true);
            $pdf->Cell(22, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['telefono'], 14)), 1, 0, 'C', true);
            $pdf->Cell(48, 5.5, conv_pdf_estudiantes(safe_pdf_substr($carreraNombre, 30)), 1, 0, 'L', true);
            $pdf->Cell(18, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['genero'], 10)), 1, 0, 'C', true);
            $pdf->Cell(12, 5.5, $e['edad'] !== '' ? $e['edad'] : '-', 1, 0, 'C', true);
            $pdf->Cell(19, 5.5, conv_pdf_estudiantes(safe_pdf_substr($e['edo_civil'], 12)), 1, 0, 'C', true);
            $pdf->Cell(16, 5.5, conv_pdf_estudiantes($statusText), 1, 0, 'C', true);
            $pdf->Cell(20, 5.5, conv_pdf_estudiantes($fechaIngreso), 1, 1, 'C', true);

            $numero++;
        }

        // Salida limpia a navegador
        $pdf->Output('I', 'reporte_estudiantes_' . date('Ymd_His') . '.pdf');
        exit();
    }
}
