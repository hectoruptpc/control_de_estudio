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

$filename = "planilla_seccion_{$seccion_id}_materia_{$materia_id}.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$out = fopen('php://output', 'w');

// Encabezado
fputcsv($out, ['identificador', 'nota']);

foreach ($students as $s) {
    // Intentar distintos nombres de campo para cédula
    $cedula = '';
    if (isset($s['cedula'])) $cedula = $s['cedula'];
    elseif (isset($s['identificacion'])) $cedula = $s['identificacion'];
    elseif (isset($s['numero_cedula'])) $cedula = $s['numero_cedula'];

    // Escribir fila con cédula y nota vacía
    fputcsv($out, [$cedula, '']);
}

fclose($out);
exit();
