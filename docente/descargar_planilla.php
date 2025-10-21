<?php
// Iniciar sesión
session_start();
require_once('../funciones/functions.php');
require_once('../fpdf/fpdf.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    die("Acceso denegado");
}

// Obtener ID del docente
if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} elseif (isset($_SESSION['id'])) {
    $docente_id = (int)$_SESSION['id'];
} elseif (isset($_SESSION['user_id'])) {
    $docente_id = (int)$_SESSION['user_id'];
} else {
    die("Error: No se pudo identificar al usuario");
}

// Verificar parámetros
if (!isset($_GET['seccion_id']) || !isset($_GET['materia_id'])) {
    die("Error: Parámetros incompletos");
}

$seccion_id = (int)$_GET['seccion_id'];
$materia_id = (int)$_GET['materia_id'];

// Generar planilla
try {
    $resultado = generarPlanillaNotasPDF($seccion_id, $materia_id, $docente_id);
    
    if (!$resultado) {
        die("Error: No se pudo generar la planilla. Verifique que la sección tenga estudiantes asignados.");
    }
    
} catch (Exception $e) {
    die("Error al generar PDF: " . $e->getMessage());
}
?>