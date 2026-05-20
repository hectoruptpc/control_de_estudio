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

function clean_output() {
    $level = ob_get_level();
    for ($i = 0; $i < $level; $i++) {
        ob_end_clean();
    }
    ob_start();
}

clean_output();
header('Content-Type: application/json; charset=utf-8');

function shutdown_handler() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        clean_output();
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error crítico del sistema. Contacte al administrador.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}
register_shutdown_function('shutdown_handler');

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("PHP Error [$errno]: $errstr in $errfile:$errline");
    return true;
}, E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Se requiere POST.', 405);
    }

    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('ID de estudiante no proporcionado', 400);
    }

    $id = trim($_POST['id']);
    if (!is_numeric($id)) {
        throw new Exception('ID de estudiante no válido', 400);
    }

    require_once('../funciones/functions.php');
    
    if (!function_exists('actualizarEstudiante')) {
        throw new Exception('Función actualizarEstudiante no disponible', 500);
    }

    $datos = [
        'id' => $id,
        'idusuario' => isset($_POST['idusuario']) ? trim($_POST['idusuario']) : '',
        'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
        'username' => isset($_POST['username']) ? trim($_POST['username']) : '',
        'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
        'tlf' => isset($_POST['tlf']) ? trim($_POST['tlf']) : '',
        'cel' => isset($_POST['cel']) ? trim($_POST['cel']) : '',
        'num_telf_opc' => isset($_POST['num_telf_opc']) ? trim($_POST['num_telf_opc']) : '',
        'direccion' => isset($_POST['direccion']) ? trim($_POST['direccion']) : '',
        'estado' => isset($_POST['estado']) ? trim($_POST['estado']) : '',
        'municipio' => isset($_POST['municipio']) ? trim($_POST['municipio']) : '',
        'parroquia' => isset($_POST['parroquia']) ? trim($_POST['parroquia']) : '',
        'ciudad' => isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '',
        'etnia' => isset($_POST['etnia']) ? trim($_POST['etnia']) : '',
        'casaapto' => isset($_POST['casaapto']) ? trim($_POST['casaapto']) : '',
        'punto_referencia' => isset($_POST['punto_referencia']) ? trim($_POST['punto_referencia']) : '',
        'grupo_familiar' => isset($_POST['grupo_familiar']) ? (int)$_POST['grupo_familiar'] : 0,
        'acargo_usted' => isset($_POST['acargo_usted']) ? (int)$_POST['acargo_usted'] : 0,
        'fuente_ingresos' => isset($_POST['fuente_ingresos']) ? trim($_POST['fuente_ingresos']) : '',
        'tipo_vivienda' => isset($_POST['tipo_vivienda']) ? trim($_POST['tipo_vivienda']) : '',
        'tenencia_vivienda' => isset($_POST['tenencia_vivienda']) ? trim($_POST['tenencia_vivienda']) : '',
        'enfermedad' => isset($_POST['enfermedad']) ? trim($_POST['enfermedad']) : '',
        'discapacidad' => isset($_POST['discapacidad']) ? trim($_POST['discapacidad']) : '',
        'titulos' => isset($_POST['titulos']) ? trim($_POST['titulos']) : '',
        'institutos' => isset($_POST['institutos']) ? trim($_POST['institutos']) : '',
        'pais_titulo' => isset($_POST['pais_titulo']) ? trim($_POST['pais_titulo']) : '',
        'legalizado_titulo' => isset($_POST['legalizado_titulo']) ? trim($_POST['legalizado_titulo']) : '',
        'potencialidades' => isset($_POST['potencialidades']) ? trim($_POST['potencialidades']) : '',
        'carrera' => isset($_POST['carrera']) ? trim($_POST['carrera']) : '',
        'sede' => isset($_POST['sede']) ? trim($_POST['sede']) : '',
        'genero' => isset($_POST['genero']) ? trim($_POST['genero']) : '',
        'embarazada' => isset($_POST['embarazada']) ? (int)$_POST['embarazada'] : 0,
        'edo_civil' => isset($_POST['edo_civil']) ? trim($_POST['edo_civil']) : '',
        'fecha_nac' => isset($_POST['fecha_nac']) ? trim($_POST['fecha_nac']) : '',
        'fecha_ingreso' => isset($_POST['fecha_ingreso']) ? trim($_POST['fecha_ingreso']) : '',
        'status' => isset($_POST['status']) ? (int)$_POST['status'] : 1
    ];

    if (empty($datos['idusuario'])) {
        throw new Exception('La cédula es obligatoria', 400);
    }

    if (!preg_match('/^[VE]-\d{6,9}$/', $datos['idusuario'])) {
        throw new Exception('Formato de cédula inválido. Debe ser V-12345678 o E-12345678', 400);
    }

    $resultado = actualizarEstudiante($datos);

    clean_output();
    
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    
    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    clean_output();
    
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    
    $statusCode = ($e->getCode() >= 100 && $e->getCode() < 600) ? $e->getCode() : 500;
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

$output = ob_get_contents();
ob_end_clean();

if ($output) {
    json_decode($output);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $errorResponse = [
            'success' => false,
            'message' => 'Error en la respuesta del servidor',
            'debug' => 'Salida no JSON detectada'
        ];
        echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE);
    } else {
        echo $output;
    }
}

exit;