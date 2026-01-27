<?php
require_once '../funciones/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar los datos recibidos
    if (!isset($_POST['id_carrera']) || !isset($_POST['nuevo_estado'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit();
    }

    $id_carrera = intval($_POST['id_carrera']);
    $nuevo_estado = intval($_POST['nuevo_estado']);

    try {
        // Preparar la consulta SQL
        $sql = "UPDATE carreras SET activa = ? WHERE id_carrera = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nuevo_estado, $id_carrera]);

        echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente']);
        exit();
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}