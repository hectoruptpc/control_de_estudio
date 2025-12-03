<?php
// ============================
// LIMPIAR TODO BUFFER ANTES DE EMPEZAR
// ============================
while (ob_get_level() > 0) {
    ob_end_clean();
}
ob_start();

// Configuración
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Solo definir el header después de limpiar buffer
header('Content-Type: application/json; charset=utf-8');

// Manejador de errores para capturar cualquier salida
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $output = ob_get_contents();
        ob_end_clean();
        
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error crítico del sistema.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile:$errline");
    return true;
}, E_ALL);

try {
    // ============================
    // 1. VERIFICAR MÉTODO
    // ============================
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Se requiere POST.', 405);
    }

    // ============================
    // 2. INCLUIR FUNCIONES
    // ============================
    $functionsPath = __DIR__.'/../funciones/functions.php';
    if (!file_exists($functionsPath)) {
        throw new Exception("Archivo de funciones no encontrado", 500);
    }
    
    require_once $functionsPath;

    // ============================
    // 3. OBTENER DATOS
    // ============================
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    $input = [];
    
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true) ?: [];
    } else {
        $input = $_POST;
    }

    if (empty($input)) {
        throw new Exception("No se recibieron datos", 400);
    }

    // ============================
    // 4. VALIDAR CAMPOS REQUERIDOS
    // ============================
    $camposRequeridos = [
        'idusuario', 'nombre', 'fecha_nac', 'genero', 'edo_civil',
        'carrera', 'estado', 'municipio', 'direccion',
        'tlf', 'email', 'fecha_ingreso', 'status'
    ];
    
    $camposFaltantes = [];
    foreach ($camposRequeridos as $campo) {
        if (empty($input[$campo])) {
            $camposFaltantes[] = $campo;
        }
    }
    
    if (!empty($camposFaltantes)) {
        $camposTexto = implode(', ', array_map(function($campo) {
            return str_replace('_', ' ', $campo);
        }, $camposFaltantes));
        
        throw new Exception("Campos requeridos faltantes: $camposTexto", 400);
    }

    // ============================
    // 5. PROCESAR CAMPOS DINÁMICOS
    // ============================
    $titulos = [];
    $institutos = [];
    
    if (isset($input['titulos']) && is_array($input['titulos'])) {
        $titulos = array_values(array_filter(array_map('trim', $input['titulos'])));
    }
    
    if (isset($input['institutos']) && is_array($input['institutos'])) {
        $institutos = array_values(array_filter(array_map('trim', $input['institutos'])));
    }

    // ============================
    // 6. PREPARAR DATOS
    // ============================
    $datos = $input;
    $datos['titulos'] = $titulos;
    $datos['institutos'] = $institutos;
    
    if (!isset($datos['potencialidades']) || $datos['potencialidades'] === null) {
        $datos['potencialidades'] = '';
    }
    
    $camposNumericos = ['grupo_familiar', 'acargo_usted'];
    foreach ($camposNumericos as $campo) {
        if (!isset($datos[$campo]) || $datos[$campo] === '') {
            $datos[$campo] = 0;
        }
    }

    // ============================
    // 7. VALIDAR DATOS
    // ============================
    if (!function_exists('validarDatosEstudiante')) {
        throw new Exception("Función de validación no disponible", 500);
    }
    
    $validacion = validarDatosEstudiante($datos);
    
    if (!empty($validacion['errors'])) {
        $errorMessages = [];
        foreach ($validacion['errors'] as $campo => $mensaje) {
            $campoTexto = str_replace('_', ' ', $campo);
            $errorMessages[$campo] = $mensaje;
        }
        
        throw new Exception("Errores de validación:\n" . implode("\n", $errorMessages), 422);
    }

    // ============================
    // 8. INSERTAR ESTUDIANTE
    // ============================
    if (!function_exists('insertarEstudiante')) {
        throw new Exception("Función para insertar estudiante no disponible", 500);
    }
    
    $resultado = insertarEstudiante(array_merge($validacion['data'], $datos));
    
    if (!$resultado) {
        throw new Exception("Error al procesar la inserción", 500);
    }
    
    if (!$resultado['success']) {
        $mensajeError = $resultado['message'] ?? 'Error desconocido';
        
        if (strpos($mensajeError, 'Duplicate') !== false || 
            strpos($mensajeError, 'duplicad') !== false ||
            strpos($mensajeError, 'ya existe') !== false) {
            
            throw new Exception("La cédula ingresada ya está registrada en el sistema.", 409);
        }
        
        throw new Exception($mensajeError, 500);
    }

    // ============================
    // 9. ENVIAR RESPUESTA EXITOSA
    // ============================
    $response = [
        'success' => true,
        'message' => $resultado['message'] ?? '✅ Estudiante registrado exitosamente',
        'id' => $resultado['id'] ?? null,
        'foto_perfil' => $resultado['foto_perfil'] ?? null
    ];

} catch (Exception $e) {
    // ============================
    // 10. MANEJAR ERRORES
    // ============================
    $statusCode = ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500;
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    
    if ($statusCode >= 500) {
        $response['message'] = "❌ Error interno del sistema. Por favor intente nuevamente.";
    }
}

// ============================
// 11. LIMPIAR Y ENVIAR RESPUESTA
// ============================
$output = ob_get_contents();
ob_end_clean();

// Verificar que solo haya JSON
if (!empty($output) && json_decode($output) === null) {
    // Hay salida no JSON, forzar JSON de error
    $response = [
        'success' => false,
        'message' => 'Error en la respuesta del servidor',
        'debug' => 'Salida no JSON detectada'
    ];
}

// Asegurar headers
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
?>