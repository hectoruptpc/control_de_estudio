<?php
include('../funciones/functions.php');

header('Content-Type: application/json');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $materia = getMateriaById($id);
    
    if ($materia && isset($materia['id'])) {
        echo json_encode($materia);
        exit();
    }
}

echo json_encode(['error' => 'Materia no encontrada']);
?>