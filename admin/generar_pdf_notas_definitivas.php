<?php
// Iniciar sesión
session_start();
require_once('../funciones/functions.php');

// Verificar autenticación y permisos
if (!isLoggedIn()) {
    die("Acceso denegado");
}

// Verificar parámetros
if (!isset($_GET['docente_id']) || !isset($_GET['materia_id']) || !isset($_GET['periodo_id'])) {
    die("Error: Parámetros incompletos");
}

$docente_id = (int)$_GET['docente_id'];
$materia_id = (int)$_GET['materia_id'];
$periodo_id = (int)$_GET['periodo_id'];

// Generar PDF
try {
    $resultado = generarPDFNotasDefinitivas($docente_id, $materia_id, $periodo_id);
    
    if (!$resultado) {
        die("Error: No se pudo generar el reporte PDF.");
    }
    
} catch (Exception $e) {
    die("Error al generar PDF: " . $e->getMessage());
}
?>