<?php
// Iniciar buffer de salida para evitar errores de FPDF
ob_start();
require_once('../funciones/functions.php');

// Verificar autenticación y permisos
if (!isLoggedIn()) {
    ob_end_clean();
    exit("Acceso denegado");
}

// Verificar parámetros
if (!isset($_GET['docente_id']) || !isset($_GET['materia_id']) || !isset($_GET['periodo_id'])) {
    ob_end_clean();
    exit("Error: Parámetros incompletos");
}

$docente_id = (int)$_GET['docente_id'];
$materia_id = (int)$_GET['materia_id'];
$periodo_id = (int)$_GET['periodo_id'];

// Generar PDF
try {
    $resultado = generarPDFNotasDefinitivas($docente_id, $materia_id, $periodo_id);
    if (!$resultado) {
        ob_end_clean();
        exit("Error: No se pudo generar el reporte PDF.");
    }
} catch (Exception $e) {
    ob_end_clean();
    exit("Error al generar PDF: " . $e->getMessage());
}
ob_end_flush();