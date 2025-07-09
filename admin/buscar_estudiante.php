<?php
// buscar_estudiante.php
isAdmin();

include('../funciones/functions.php');

header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Sesión no válida']);
    exit;
}

// Solo aceptar método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Obtener y validar cédula
$cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';

if (strlen($cedula) < 2) {
    echo json_encode(['success' => false, 'error' => 'Mínimo 2 caracteres']);
    exit;
}

try {
    $resultados = buscarEstudiantePorCedula($cedula);
    echo json_encode([
        'success' => true,
        'data' => $resultados,
        'count' => count($resultados)
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}