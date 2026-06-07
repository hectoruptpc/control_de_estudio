<?php
header('Content-Type: application/json; charset=utf-8');
// Incluir funciones
include('../funciones/functions.php');

$id = 0;
if (isset($_GET['id_carrera'])) $id = intval($_GET['id_carrera']);
elseif (isset($_POST['id_carrera'])) $id = intval($_POST['id_carrera']);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'id_carrera inválido', 'versions' => []]);
    exit;
}

$versions = obtenerVersionesPorIdCarrera($id);
// Asegurar que cada versión tenga campos esperados (id_version, fecha_vigencia, anio)
foreach ($versions as &$v) {
    if (!isset($v['id_version'])) $v['id_version'] = intval($v['id'] ?? 0);
    if (!isset($v['anio'])) $v['anio'] = !empty($v['fecha_vigencia']) ? date('Y', strtotime($v['fecha_vigencia'])) : null;
}

echo json_encode(['success' => true, 'versions' => $versions]);
exit;
