<?php
require_once 'functions.php';

// Habilitar CORS (para desarrollo)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Obtener datos JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validación básica
if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

try {
    $resultado = editarDocente($data);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Actualización exitosa'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error en la base de datos'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}