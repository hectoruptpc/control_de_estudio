<?php
// Evitar que el archivo de funciones ejecute bloques de procesamiento
// que dependen de $_GET al incluirlo. Guardamos y limpiamos temporalmente.
$__saved_GET = $_GET;
unset($_GET['materia_id'], $_GET['seccion_id'], $_GET['docente_id'], $_GET['periodo_id']);

require_once('../funciones/functions.php');
// Restaurar GET original
$_GET = $__saved_GET;

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

// Comprobaciones detalladas antes de generar la planilla
try {
    // 1) Verificar acceso del docente a la sección/materia
    if (!verificarAccesoDocente($docente_id, $seccion_id, $materia_id)) {
        die("Acceso denegado: el docente no está asignado a esa sección y materia.");
    }

    // 2) Obtener información de sección y materia
    $info = obtenerInfoSeccionMateria($seccion_id, $materia_id);
    if (!$info) {
        die("Error: No se encontró información de la sección/materia.");
    }

    // 3) Obtener lista de estudiantes
    $estudiantes = obtenerEstudiantesSeccion($seccion_id);
    if (empty($estudiantes)) {
        die("Error: No hay estudiantes activos en la sección seleccionada.");
    }

    // 4) Generar planilla (la función interna hará Output y exit)
    $resultado = generarPlanillaNotasPDF($seccion_id, $materia_id, $docente_id);
    if (!$resultado) {
        die("Error: La generación de la planilla falló inesperadamente.");
    }

} catch (Exception $e) {
    die("Error al generar PDF: " . $e->getMessage());
}
?>