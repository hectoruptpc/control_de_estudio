<?php
require_once('../funciones/functions.php');

if (!isLoggedIn() || !isDocente()) {
    http_response_code(403);
    echo "Acceso denegado";
    exit();
}

$docente_id = obtenerIdUsuario();
$seccion_id = $_GET['seccion_id'] ?? null;
$materia_id = $_GET['materia_id'] ?? null;

if (!$seccion_id || !$materia_id) {
    http_response_code(400);
    echo "Parámetros faltantes";
    exit();
}

// Obtener estudiantes de la sección
$students = [];
if (function_exists('obtenerEstudiantesDeSeccion')) {
    global $db;
    $res = obtenerEstudiantesDeSeccion($db, $seccion_id);
    if (is_object($res)) {
        while ($row = $res->fetch_assoc()) {
            $students[] = $row;
        }
    } elseif (is_array($res)) {
        $students = $res;
    }
}

// Obtener nombres descriptivos de sección, carrera y materia
$materia_nombre = '';
$codigo_seccion = '';
$carrera_nombre = '';
if (isset($db) && $db) {
    $mres = $db->query("SELECT nombre_materia FROM materias WHERE id_materia = " . intval($materia_id) . " LIMIT 1");
    if ($mres && $mrow = $mres->fetch_assoc()) $materia_nombre = $mrow['nombre_materia'];

    $sres = $db->query("SELECT codigo_seccion, id_carrera FROM secciones WHERE id_seccion = " . intval($seccion_id) . " LIMIT 1");
    if ($sres && $srow = $sres->fetch_assoc()) {
        $codigo_seccion = $srow['codigo_seccion'] ?? '';
        $id_carrera = $srow['id_carrera'] ?? null;
        if ($id_carrera) {
            $cres = $db->query("SELECT nombre_carrera FROM carreras WHERE id_carrera = " . intval($id_carrera) . " LIMIT 1");
            if ($cres && $crow = $cres->fetch_assoc()) $carrera_nombre = $crow['nombre_carrera'];
        }
    }
}

$filename = "planilla_seccion_{$seccion_id}_materia_{$materia_id}.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$out = fopen('php://output', 'w');

// Encabezado: incluir `estudiante_id` y `nombres`, dejar `nota` vacía
fputcsv($out, ['estudiante_id','nombres','codigo_seccion','carrera','materia','nota']);

foreach ($students as $s) {
    $id_usuario = $s['id'] ?? $s['id_usuario'] ?? $s['usuario_id'] ?? '';
    $nombres = $s['nombres'] ?? $s['nombre'] ?? $s['nombres_completos'] ?? '';

    fputcsv($out, [$id_usuario, $nombres, $codigo_seccion, $carrera_nombre, $materia_nombre, '']);
}

fclose($out);
exit();
