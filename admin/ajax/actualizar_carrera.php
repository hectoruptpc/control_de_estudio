<?php
require_once '../../funciones/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Obtener y validar datos
$id = filter_input(INPUT_POST, 'id_carrera', FILTER_VALIDATE_INT);
$nombre = trim($_POST['nombre_carrera'] ?? '');
$codigo = trim($_POST['cod_carrera'] ?? '');
$tipo = trim($_POST['tipo_formacion'] ?? '');

if (!$id || empty($nombre) || empty($codigo) || empty($tipo)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Preparar datos para actualización
$datos = [
    'nombre_carrera' => $nombre,
    'cod_carrera' => $codigo,
    'tipo_formacion' => $tipo,
    'duracion_semestres' => filter_input(INPUT_POST, 'duracion_semestres', FILTER_VALIDATE_INT),
    'titulo_otorga' => trim($_POST['titulo_otorga'] ?? ''),
    'descripcion' => trim($_POST['descripcion'] ?? '')
];

try {
    $resultado = actualizarCarrera($id, $datos);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'message' => 'Carrera actualizada correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No se realizaron cambios o hubo un error'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}