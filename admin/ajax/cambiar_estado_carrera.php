<?php
require_once '../../funciones/functions.php';

header('Content-Type: application/json');

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar datos
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$accion = filter_input(INPUT_POST, 'accion', FILTER_SANITIZE_STRING);

if (!$id || !$accion || !in_array($accion, ['activar', 'desactivar'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// Determinar el nuevo estado
$nuevoEstado = ($accion === 'activar') ? 1 : 0;

// Ejecutar la actualización
try {
    $resultado = cambiarEstadoCarrera($id, $nuevoEstado);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'nuevoEstado' => $nuevoEstado
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios en la base de datos'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}