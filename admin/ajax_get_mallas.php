<?php
header('Content-Type: application/json; charset=utf-8');
include('../funciones/functions.php');

$id = 0;
if (isset($_GET['id_carrera'])) $id = intval($_GET['id_carrera']);
elseif (isset($_POST['id_carrera'])) $id = intval($_POST['id_carrera']);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'id_carrera inválido', 'mallas' => []]);
    exit;
}

$mallas = obtenerMallasPorCarrera($id);
$out = [];
foreach ($mallas as $m) {
    $out[] = [
        'id_malla' => intval($m['id_malla']),
        'codigo_malla' => $m['codigo_malla'],
        'anio' => intval($m['anio']),
        'descripcion' => $m['descripcion'] ?? ''
    ];
}

echo json_encode(['success' => true, 'mallas' => $out]);
exit;

?>
