<?php
// Evitar que includes que impriman HTML rompan la respuesta JSON
ob_start();
require_once '../funciones/functions.php';
// Limpiar cualquier salida accidental proveniente de los includes
ob_end_clean();

header("Content-Type: application/json; charset=UTF-8");

// Permitir CORS solo en desarrollo
if ($_SERVER['HTTP_ORIGIN'] ?? false) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    if (!$data || !isset($data['id'])) {
        throw new Exception('Datos inválidos o ID no proporcionado');
    }

    // Usar la función correcta: actualizarDocente en lugar de editarDocente
    $resultado = actualizarDocente($data);

    if ($resultado['success']) {
        echo json_encode([
            'success' => true,
            'message' => $resultado['message'],
            'affected_rows' => $resultado['affected_rows'] ?? 0
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $resultado['message']
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>