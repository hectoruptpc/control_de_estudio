<?php
// Desactivar errores para producción, pero mantener logging
error_reporting(0);
ini_set('display_errors', 0);

require_once('../funciones/functions.php');

// Verificar que es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Validar ID
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception('ID de estudiante no válido');
    }

    $id = $_POST['id'];

    // Procesar datos - USANDO LOS NOMBRES CORRECTOS DEL FORMULARIO
    $datos = [
        'id' => $id,
        'idusuario' => trim($_POST['idusuario'] ?? ''), // NUEVO CAMPO CÉDULA
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

    // Validar que la cédula no esté vacía
    if (empty($datos['idusuario'])) {
        throw new Exception('La cédula es obligatoria');
    }

    // Verificar si la cédula ya existe en otro usuario
    global $db;
    $query_verificar = "SELECT id FROM users WHERE idusuario = ? AND id != ?";
    $stmt_verificar = $db->prepare($query_verificar);
    $stmt_verificar->bind_param("si", $datos['idusuario'], $id);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    
    if ($result_verificar->num_rows > 0) {
        throw new Exception('La cédula ya está registrada para otro estudiante');
    }
    $stmt_verificar->close();

    // Manejar la foto de perfil si se subió
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        try {
            $nombreFoto = subirFotoPerfil($_FILES['foto_perfil']);
            $datos['foto_perfil'] = $nombreFoto;
        } catch (Exception $e) {
            throw new Exception('Error al subir foto: ' . $e->getMessage());
        }
    }

    // Usar tu función existente
    $resultado = actualizarEstudiante($datos);

    // Devolver respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($resultado);
    
} catch (Exception $e) {
    // Manejar cualquier error y devolver JSON
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
exit;