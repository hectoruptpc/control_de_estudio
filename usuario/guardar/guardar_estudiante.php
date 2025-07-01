<?php
// Configuración inicial
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Configuración de errores (activar solo en desarrollo)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/php_errors.log');

// Función para enviar respuestas JSON estandarizadas
function jsonResponse($success, $message, $data = [], $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Función para validar y sanitizar datos
function sanitizeInput($data, $type = 'string') {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

    switch ($type) {
        case 'int':
            return (int)$data;
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'date':
            return date('Y-m-d', strtotime($data));
        default:
            return $data;
    }
}

try {
    // 1. Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, 'Método no permitido', [], 405);
    }

    // 2. Obtener y validar datos JSON
    $jsonInput = file_get_contents('php://input');
    if (empty($jsonInput)) {
        jsonResponse(false, 'No se recibieron datos', [], 400);
    }

    $inputData = json_decode($jsonInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        jsonResponse(false, 'JSON inválido: '.json_last_error_msg(), [], 400);
    }

    // 3. Validar campos requeridos
    $requiredFields = [
        'tipoCedula' => 'string',
        'cedula' => 'string',
        'nombres' => 'string',
        'apellidos' => 'string',
        'genero' => 'string',
        'fechaNacimiento' => 'date',
        'edad' => 'int',
        'estadoCivil' => 'string',
        'estado' => 'string',
        'municipio' => 'string',
        'parroquia' => 'string',
        'direccion' => 'string',
        'carrera' => 'string',
        'telefono1' => 'string',
        'correo' => 'email'
    ];

    $errors = [];
    $sanitizedData = [];

    foreach ($requiredFields as $field => $type) {
        if (!isset($inputData[$field]) || $inputData[$field] === '') {
            $errors[] = "El campo $field es requerido";
            continue;
        }

        $sanitizedValue = sanitizeInput($inputData[$field], $type);
        
        // Validaciones específicas
        if ($field === 'correo' && !filter_var($sanitizedValue, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El correo electrónico no es válido";
        }

        if ($field === 'fechaNacimiento' && $sanitizedValue === '1970-01-01') {
            $errors[] = "La fecha de nacimiento no es válida";
        }

        $sanitizedData[$field] = $sanitizedValue;
    }

    if (!empty($errors)) {
        jsonResponse(false, implode(', ', $errors), [], 400);
    }

    // 4. Conexión a la base de datos
    require_once __DIR__.'/../../conexion.php';
    
    if (!class_exists('Database')) {
        jsonResponse(false, 'Clase Database no encontrada', [], 500);
    }

    $database = new Database();
    $db = $database->getConnection();

    if ($db === null) {
        jsonResponse(false, 'Error al conectar con la base de datos', [], 500);
    }

    // 5. Preparar datos para inserción
    $codEstudiante = $sanitizedData['tipoCedula'] . $sanitizedData['cedula'];
    $nombreCompleto = $sanitizedData['nombres'] . ' ' . $sanitizedData['apellidos'];
    $fechaIngreso = date('Y-m-d');
    $status = 1;

    // 6. Consulta SQL preparada
    $sql = "INSERT INTO estudiantes (
        id_usuario, cod_estudiante, nombre, carrera, genero, 
        edo_civil, estado, municipio, parroquia, direccion, 
        fecha_nac, edad, num_telf, num_telf_opc, correo, 
        fecha_ingreso, status
    ) VALUES (
        :id_usuario, :cod_estudiante, :nombre, :carrera, :genero, 
        :edo_civil, :estado, :municipio, :parroquia, :direccion, 
        :fecha_nac, :edad, :num_telf, :num_telf_opc, :correo, 
        :fecha_ingreso, :status
    )";

    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        throw new Exception("Error al preparar la consulta: " . implode(" ", $db->errorInfo()));
    }

    // 7. Ejecutar transacción
    $db->beginTransaction();

    $params = [
        ':id_usuario' => $codEstudiante,
        ':cod_estudiante' => $codEstudiante,
        ':nombre' => $nombreCompleto,
        ':carrera' => $sanitizedData['carrera'],
        ':genero' => $sanitizedData['genero'],
        ':edo_civil' => $sanitizedData['estadoCivil'],
        ':estado' => $sanitizedData['estado'],
        ':municipio' => $sanitizedData['municipio'],
        ':parroquia' => $sanitizedData['parroquia'],
        ':direccion' => $sanitizedData['direccion'],
        ':fecha_nac' => $sanitizedData['fechaNacimiento'],
        ':edad' => $sanitizedData['edad'],
        ':num_telf' => $sanitizedData['telefono1'],
        ':num_telf_opc' => $inputData['telefono2'] ?? null,
        ':correo' => $sanitizedData['correo'],
        ':fecha_ingreso' => $fechaIngreso,
        ':status' => $status
    ];

    $success = $stmt->execute($params);

    if (!$success) {
        $db->rollBack();
        throw new Exception("Error al ejecutar la consulta: " . implode(" ", $stmt->errorInfo()));
    }

    $db->commit();

    // 8. Respuesta exitosa
    jsonResponse(true, 'Estudiante registrado exitosamente', [
        'cod_estudiante' => $codEstudiante,
        'nombre_completo' => $nombreCompleto
    ], 201);

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    error_log("PDO Error: " . $e->getMessage());
    jsonResponse(false, 'Error en la base de datos', [
        'error' => $e->getMessage(),
        'code' => $e->getCode()
    ], 500);

} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    jsonResponse(false, $e->getMessage(), [
        'code' => $e->getCode()
    ], 500);
}
?>