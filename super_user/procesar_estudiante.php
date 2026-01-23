<?php
// Iniciar buffer de salida para evitar cualquier salida accidental antes del JSON
if (ob_get_level() == 0) ob_start();

// Manejador global de errores para capturar cualquier warning/fatal error y devolver JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) return;
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => "Error PHP: $errstr en $errfile:$errline",
        'data' => [],
        'timestamp' => time()
    ], JSON_UNESCAPED_UNICODE);
    exit;
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error fatal: ' . $error['message'] . ' en ' . $error['file'] . ':' . $error['line'],
            'data' => [],
            'timestamp' => time()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
});
// Configuración inicial
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para registrar errores (desactivada para evitar errores de permisos)
function logError($message) {
    // No hacer nada, ni intentar escribir a disco
}

// Función para enviar respuesta JSON consistente
function sendJsonResponse($success, $message, $data = [], $statusCode = 200) {
    http_response_code($statusCode);
    $response = [
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => time()
    ];
    // Limpiar cualquier salida previa antes de enviar JSON
    if (ob_get_length()) {
        ob_clean();
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido", 405);
    }

    // Incluir funciones
    $functionsPath = __DIR__.'/../funciones/functions.php';
    if (!file_exists($functionsPath)) {
        throw new Exception("Archivo de funciones no encontrado", 500);
    }
    require_once $functionsPath;


    // Permitir tanto JSON como FormData
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    $input = [];
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);
        if (!is_array($input)) {
            $input = [];
        }
    } else {
        $input = $_POST;
    }

    if (empty($input)) {
        throw new Exception("No se recibieron datos", 400);
    }


    // Procesar campos dinámicos
    $titulos = isset($input['titulos']) && is_array($input['titulos']) ? array_filter($input['titulos']) : [];
    $institutos = isset($input['institutos']) && is_array($input['institutos']) ? array_filter($input['institutos']) : [];

    // Preparar datos para inserción
    $datos = $input;
    $datos['titulos'] = !empty($titulos) ? implode('; ', $titulos) : null;
    $datos['institutos'] = !empty($institutos) ? implode('; ', $institutos) : null;
    // Asegurar que potencialidades tenga valor por defecto si no viene
    if (!isset($datos['potencialidades']) || $datos['potencialidades'] === null) {
        $datos['potencialidades'] = '';
    }

    // Validar datos
    $validacion = function_exists('validarEstudiante') ? validarEstudiante($datos) : true;
    if ($validacion !== true) {
        throw new Exception(is_array($validacion) ? implode("\n", $validacion) : $validacion, 400);
    }

    // Insertar estudiante
    if (!function_exists('insertarEstudiante')) {
        throw new Exception("Función insertarEstudiante no disponible", 500);
    }
    $resultado = insertarEstudiante($datos);
    if (!$resultado || !isset($resultado['success'])) {
        throw new Exception("Error al procesar la inserción", 500);
    }
    if (!$resultado['success']) {
        throw new Exception($resultado['message'] ?? "Error al insertar estudiante", 500);
    }

    sendJsonResponse(true, $resultado['message'] ?? 'Estudiante registrado exitosamente', [
        'id' => $resultado['id'] ?? null
    ]);

} catch (Exception $e) {
    logError("Error en procesar_estudiante.php: ".$e->getMessage()." en ".$e->getFile().":".$e->getLine());
    if (ob_get_length()) {
        ob_clean();
    }
    sendJsonResponse(false, $e->getMessage(), [], ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500);
}