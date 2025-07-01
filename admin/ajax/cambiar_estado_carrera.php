<?php
require_once '../../funciones/functions.php';

header('Content-Type: application/json');

if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['accion'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

$id = (int)$_POST['id'];
$accion = $_POST['accion'];
$activa = ($accion === 'activar') ? 1 : 0;

// Primero obtenemos la carrera para verificar que existe
$carrera = obtenerCarreraPorId($id);

if (!$carrera) {
    echo json_encode(['success' => false, 'message' => 'Carrera no encontrada']);
    exit();
}

// Actualizamos el estado
if (actualizarCarrera($id, $carrera['nombre_carrera'], $carrera['cod_carrera'], $activa)) {
    echo json_encode(['success' => true, 'message' => 'Estado de la carrera actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
}