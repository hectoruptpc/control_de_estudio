<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Log de inicio
file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Inicio del script\n", FILE_APPEND);

// Verificar si el script fue accedido directamente
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die("Acceso no permitido");
}

// Ruta absoluta para includes
require_once __DIR__ . '/../funciones/functions.php';
file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Funciones incluidas\n", FILE_APPEND);

// Forzar JSON
header('Content-Type: application/json');
ob_start(); // Capturar cualquier salida no deseada

try {
    file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Inicio del try\n", FILE_APPEND);
    
    // Verificar método
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Verificar archivo
    if (!isset($_FILES['archivo_csv'])) {
        throw new Exception('No se recibió el archivo', 400);
    }

    file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Archivo recibido: " . $_FILES['archivo_csv']['name'] . "\n", FILE_APPEND);

    // Procesar archivo
    $resultado = previewCSVEstudiantes($_FILES['archivo_csv']['tmp_name']);
    file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Archivo procesado\n", FILE_APPEND);
    
    // Limpiar buffer y enviar respuesta
    ob_end_clean();
    echo json_encode($resultado);
    file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Respuesta enviada\n", FILE_APPEND);
    exit;
    
} catch (Exception $e) {
    // Limpiar buffer si hubo error
    ob_end_clean();
    
    file_put_contents('csv_preview.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'errors' => [$e->getMessage()],
        'trace' => $e->getTrace()
    ]);
    exit;
}