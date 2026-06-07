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

// Obtener información de la materia, sección y carrera
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

$filename = "planilla_trimestres_seccion_{$seccion_id}.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$out = fopen('php://output', 'w');

// Encabezado
$encabezado = [
    'CEDULA', 
    'NOMBRES', 
    'SECCION', 
    'CARRERA', 
    'MATERIA', 
    'TRIMESTRE_1',
    'TRIMESTRE_2',
    'TRIMESTRE_3'
];
fputcsv($out, $encabezado);

// Escribir filas de estudiantes
foreach ($students as $s) {
    $cedula = $s['idusuario'] ?? $s['cedula'] ?? $s['identificacion'] ?? $s['numero_cedula'] ?? '';
    $nombres = $s['nombre'] ?? $s['nombres'] ?? $s['nombres_completos'] ?? '';
    
    $fila = [
        $cedula,
        $nombres,
        $codigo_seccion,
        $carrera_nombre,
        $materia_nombre,
        '',  // Trimestre 1
        '',  // Trimestre 2
        ''   // Trimestre 3
    ];
    fputcsv($out, $fila);
}

fclose($out);
exit();