<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

// Verificar que es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de estudiante no válido']);
    exit;
}

$id = $_POST['id'];

// Procesar datos
$datos = [
    'id' => $id,
    'nombre' => trim($_POST['nombre'] ?? ''),
    'username' => trim($_POST['cedula'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'tlf' => trim($_POST['num_telf'] ?? ''),
    'num_telf_opc' => trim($_POST['num_telf_opc'] ?? ''),
    'carrera' => trim($_POST['carrera'] ?? ''),
    'genero' => trim($_POST['genero'] ?? ''),
    'fecha_nac' => trim($_POST['fecha_nac'] ?? ''),
    'fecha_ingreso' => trim($_POST['fecha_ingreso'] ?? ''),
    'status' => intval($_POST['status'] ?? 1)
];

// Validar campos obligatorios
if (empty($datos['nombre']) || empty($datos['username']) || empty($datos['fecha_ingreso'])) {
    echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes']);
    exit;
}

// Usar tu función existente en lugar de duplicar lógica
$resultado = actualizarEstudiante($datos);

// Devolver respuesta JSON
header('Content-Type: application/json');
echo json_encode($resultado);
exit;