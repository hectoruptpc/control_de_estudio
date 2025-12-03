<?php
// Iniciar buffer de salida para evitar cualquier salida accidental antes del JSON
if (ob_get_level() == 0) ob_start();

// Configuración inicial
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manejador global de errores para capturar cualquier warning/fatal error y devolver JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) return;
    if (ob_get_length()) ob_clean();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Error del sistema: $errstr",
        'data' => [],
        'timestamp' => time()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}, E_ALL);

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_length()) ob_clean();
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error crítico del sistema. Por favor contacte al administrador.',
            'data' => [],
            'timestamp' => time()
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
});

// Función para enviar respuesta JSON consistente
function sendJsonResponse($success, $message, $data = [], $statusCode = 200) {
    http_response_code($statusCode);
    $response = [
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'errors' => isset($data['errors']) ? $data['errors'] : [],
        'timestamp' => time()
    ];
    
    // Limpiar cualquier salida previa antes de enviar JSON
    if (ob_get_length()) {
        ob_clean();
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

try {
    // Verificar método de solicitud
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido. Use POST.", 405);
    }

    // Verificar que sea una solicitud AJAX/JSON (opcional pero recomendado)
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    // Incluir funciones
    $functionsPath = __DIR__.'/../funciones/functions.php';
    if (!file_exists($functionsPath)) {
        throw new Exception("Archivo de funciones no encontrado", 500);
    }
    
    require_once $functionsPath;

    // Verificar que el archivo incluya nuestras funciones necesarias
    if (!function_exists('validarDatosEstudiante')) {
        throw new Exception("Función de validación no disponible", 500);
    }
    
    if (!function_exists('insertarEstudiante')) {
        throw new Exception("Función para insertar estudiante no disponible", 500);
    }

    // Permitir tanto JSON como FormData
    $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    $inputData = [];
    
    if (stripos($contentType, 'application/json') !== false) {
        $rawInput = file_get_contents('php://input');
        if ($rawInput === false) {
            throw new Exception("Error al leer los datos de entrada", 400);
        }
        
        $inputData = json_decode($rawInput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error en el formato JSON: " . json_last_error_msg(), 400);
        }
        
        if (!is_array($inputData)) {
            $inputData = [];
        }
    } else {
        // Para FormData (formulario normal)
        $inputData = $_POST;
        
        // También manejar archivos si existen
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $inputData['_foto_perfil'] = $_FILES['foto_perfil'];
        }
    }

    if (empty($inputData)) {
        throw new Exception("No se recibieron datos del formulario", 400);
    }

    // Validar campos requeridos básicos
    $camposRequeridos = [
        'idusuario', 'nombre', 'fecha_nac', 'genero', 'edo_civil',
        'carrera', 'estado', 'municipio', 'direccion',
        'tlf', 'email', 'fecha_ingreso', 'status'
    ];
    
    $camposFaltantes = [];
    foreach ($camposRequeridos as $campo) {
        if (empty($inputData[$campo])) {
            $camposFaltantes[] = $campo;
        }
    }
    
    if (!empty($camposFaltantes)) {
        $camposTexto = implode(', ', array_map(function($campo) {
            return str_replace('_', ' ', $campo);
        }, $camposFaltantes));
        
        throw new Exception("Campos requeridos faltantes: $camposTexto", 400);
    }

    // Procesar campos dinámicos de títulos e institutos
    $titulos = [];
    $institutos = [];
    
    if (isset($inputData['titulos']) && is_array($inputData['titulos'])) {
        $titulos = array_values(array_filter(array_map('trim', $inputData['titulos'])));
    }
    
    if (isset($inputData['institutos']) && is_array($inputData['institutos'])) {
        $institutos = array_values(array_filter(array_map('trim', $inputData['institutos'])));
    }

    // Preparar datos para validación e inserción
    $datos = $inputData;
    $datos['titulos'] = $titulos;
    $datos['institutos'] = $institutos;
    
    // Asegurar que potencialidades tenga valor por defecto si no viene
    if (!isset($datos['potencialidades']) || $datos['potencialidades'] === null) {
        $datos['potencialidades'] = '';
    }
    
    // Asegurar que los campos numéricos tengan valor por defecto
    $camposNumericos = ['grupo_familiar', 'acargo_usted'];
    foreach ($camposNumericos as $campo) {
        if (!isset($datos[$campo]) || $datos[$campo] === '') {
            $datos[$campo] = 0;
        }
    }

    // 1. VALIDAR DATOS CON LA NUEVA FUNCIÓN MEJORADA
    $validacion = validarDatosEstudiante($datos);
    
    // Verificar si hay errores de validación
    if (!empty($validacion['errors'])) {
        // Preparar respuesta de error con detalles
        $errorMessages = [];
        foreach ($validacion['errors'] as $campo => $mensaje) {
            $campoTexto = str_replace('_', ' ', $campo);
            $errorMessages[$campo] = $mensaje;
        }
        
        sendJsonResponse(false, "Por favor corrija los errores en el formulario", [
            'errors' => $errorMessages,
            'validated_data' => $validacion['data']
        ], 422); // 422 Unprocessable Entity
    }

    // 2. PROCESAR ARCHIVOS (si existen)
    if (isset($datos['_foto_perfil']) && isset($_FILES['foto_perfil'])) {
        // La función insertarEstudiante manejará la subida del archivo
        // Solo asegurarnos de que el archivo se pase correctamente
    }

    // 3. INSERTAR ESTUDIANTE
    $resultado = insertarEstudiante(array_merge($validacion['data'], $datos));
    
    if (!$resultado) {
        throw new Exception("Error al procesar la inserción: resultado vacío", 500);
    }
    
    if (!$resultado['success']) {
        // Verificar si es un error de duplicado de cédula
        $mensajeError = $resultado['message'] ?? 'Error desconocido';
        
        if (strpos($mensajeError, 'Duplicate') !== false || 
            strpos($mensajeError, 'duplicad') !== false ||
            strpos($mensajeError, 'ya existe') !== false) {
            
            sendJsonResponse(false, "La cédula ingresada ya está registrada en el sistema.", [
                'errors' => ['idusuario' => 'Esta cédula ya está registrada']
            ], 409); // 409 Conflict
        }
        
        throw new Exception($mensajeError, 500);
    }

    // 4. RESPUESTA EXITOSA
    sendJsonResponse(true, $resultado['message'] ?? 'Estudiante registrado exitosamente', [
        'id' => $resultado['id'] ?? null,
        'foto_perfil' => $resultado['foto_perfil'] ?? null,
        'redirect_url' => isset($datos['esModal']) && $datos['esModal'] ? null : 'lista_estudiantes.php'
    ]);

} catch (Exception $e) {
    // Error logging (sin escribir a disco para evitar errores de permisos)
    error_log("Error en procesar_estudiante.php: " . $e->getMessage() . 
              " en " . $e->getFile() . ":" . $e->getLine());
    
    // Determinar código de estado
    $statusCode = ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500;
    
    // Mensaje amigable para el usuario
    $userMessage = $e->getMessage();
    
    // Si es un error 500 interno, mostrar mensaje genérico
    if ($statusCode >= 500) {
        $userMessage = "Error interno del sistema. Por favor intente nuevamente o contacte al administrador.";
    }
    
    sendJsonResponse(false, $userMessage, [
        'debug' => ($statusCode < 500) ? $e->getMessage() : null,
        'file' => ($statusCode < 500) ? basename($e->getFile()) : null,
        'line' => ($statusCode < 500) ? $e->getLine() : null
    ], $statusCode);
}

// Limpiar buffer final
if (ob_get_length()) {
    ob_end_flush();
}