<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_FILES['archivo_csv'])) {
    echo json_encode(['success' => false, 'message' => 'No se subió ningún archivo']);
    exit;
}

$file = $_FILES['archivo_csv']['tmp_name'];
if (!file_exists($file)) {
    echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
    exit;
}

$lines = file($file);
if (empty($lines)) {
    echo json_encode(['success' => false, 'message' => 'El archivo está vacío']);
    exit;
}

// Verificar encabezados
$headers = str_getcsv(trim($lines[0]));
if (!in_array('idusuario', $headers)) {
    echo json_encode(['success' => false, 'message' => 'Falta la columna "idusuario"']);
    exit;
}

// Procesar registros (simulación)
$registros = [];
for ($i = 1; $i < count($lines); $i++) {
    $data = str_getcsv(trim($lines[$i]));
    if (count($data) !== count($headers)) continue; // Saltar líneas inválidas
    $registros[] = array_combine($headers, $data);
}

// Respuesta exitosa
echo json_encode([
    'success' => true,
    'total' => count($registros),
    'registros' => array_slice($registros, 0, 5) // Mostrar solo 5
]);
?>