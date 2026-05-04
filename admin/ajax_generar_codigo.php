<?php
header('Content-Type: application/json');

include('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_carrera = (int)$_POST['id_carrera'];
    
    $codigo = generarCodigoSeccion($id_carrera);
    
    if ($codigo) {
        echo json_encode(['success' => true, 'codigo' => $codigo]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No hay códigos disponibles para esta carrera']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>