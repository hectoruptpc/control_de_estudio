<?php
// ACTIVAR ERRORES PARA DEBUG
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

// Verificar que es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// LOG PARA DEBUG
error_log("=== INICIO ACTUALIZAR ESTUDIANTE ===");
error_log("Datos POST: " . print_r($_POST, true));
error_log("Datos FILES: " . print_r($_FILES, true));

try {
    // Validar ID
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('ID de estudiante no válido');
    }

    $id = $_POST['id'];
    error_log("ID del estudiante: " . $id);

    // VERIFICAR EXPLÍCITAMENTE LA CÉDULA
    if (!isset($_POST['idusuario'])) {
        throw new Exception('No se recibió el campo cédula (idusuario)');
    }

    $cedula = trim($_POST['idusuario']);
    error_log("Cédula recibida: " . $cedula);

    // Validar que la cédula no esté vacía
    if (empty($cedula)) {
        throw new Exception('La cédula es obligatoria');
    }

    // Validar formato de cédula
    if (!preg_match('/^[VE]-\d+$/', $cedula)) {
        throw new Exception('Formato de cédula inválido. Debe ser V-12345678 o E-12345678');
    }

    // Verificar si la cédula ya existe en otro usuario
    global $db;
    $query_verificar = "SELECT id, nombre FROM users WHERE idusuario = ? AND id != ?";
    $stmt_verificar = $db->prepare($query_verificar);
    $stmt_verificar->bind_param("si", $cedula, $id);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    
    if ($result_verificar->num_rows > 0) {
        $usuario_existente = $result_verificar->fetch_assoc();
        throw new Exception('La cédula ya está registrada para el estudiante: ' . $usuario_existente['nombre']);
    }
    $stmt_verificar->close();

    // Procesar datos
    $datos = [
        'id' => $id,
        'idusuario' => $cedula,
        'nombre' => trim($_POST['nombre'] ?? ''),
        'username' => trim($_POST['username'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'tlf' => trim($_POST['tlf'] ?? ''),
        'cel' => trim($_POST['cel'] ?? ''),
        'num_telf_opc' => trim($_POST['num_telf_opc'] ?? ''),
        'carrera' => trim($_POST['carrera'] ?? ''),
        'genero' => trim($_POST['genero'] ?? ''),
        'edo_civil' => trim($_POST['edo_civil'] ?? ''),
        'fecha_nac' => trim($_POST['fecha_nac'] ?? ''),
        'fecha_ingreso' => trim($_POST['fecha_ingreso'] ?? ''),
        'status' => intval($_POST['status'] ?? 1),
        'etnia' => trim($_POST['etnia'] ?? ''),
        'direccion' => trim($_POST['direccion'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
        'municipio' => trim($_POST['municipio'] ?? ''),
        'parroquia' => trim($_POST['parroquia'] ?? ''),
        'ciudad' => trim($_POST['ciudad'] ?? ''),
        'casaapto' => trim($_POST['casaapto'] ?? ''),
        'punto_referencia' => trim($_POST['punto_referencia'] ?? ''),
        'grupo_familiar' => intval($_POST['grupo_familiar'] ?? 0),
        'acargo_usted' => intval($_POST['acargo_usted'] ?? 0),
        'fuente_ingresos' => trim($_POST['fuente_ingresos'] ?? ''),
        'tipo_vivienda' => trim($_POST['tipo_vivienda'] ?? ''),
        'tenencia_vivienda' => trim($_POST['tenencia_vivienda'] ?? ''),
        'enfermedad' => trim($_POST['enfermedad'] ?? ''),
        'discapacidad' => trim($_POST['discapacidad'] ?? ''),
        'titulos' => trim($_POST['titulos'] ?? ''),
        'institutos' => trim($_POST['institutos'] ?? '')
    ];

    error_log("Datos procesados para actualización: " . print_r($datos, true));

    // Manejar la foto de perfil si se subió
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        try {
            $nombreFoto = subirFotoPerfil($_FILES['foto_perfil']);
            $datos['foto_perfil'] = $nombreFoto;
            error_log("Foto subida: " . $nombreFoto);
        } catch (Exception $e) {
            throw new Exception('Error al subir foto: ' . $e->getMessage());
        }
    }

    // Usar tu función existente
    $resultado = actualizarEstudiante($datos);

    error_log("Resultado de actualización: " . print_r($resultado, true));

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($resultado);
    
} catch (Exception $e) {
    // Manejar cualquier error y devolver JSON
    error_log("ERROR en actualizar_estudiante: " . $e->getMessage());
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

error_log("=== FIN ACTUALIZAR ESTUDIANTE ===");
exit;