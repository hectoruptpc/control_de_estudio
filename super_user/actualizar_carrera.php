<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

header('Content-Type: application/json');

$datos = [
    'id_carrera' => $_POST['id_carrera'],
    'nombre_carrera' => $_POST['nombre_carrera'],
    'cod_carrera' => $_POST['cod_carrera'],
    'tipo_formacion' => $_POST['tipo_formacion'],
    'duracion_semestres' => $_POST['duracion_semestres'],
    'titulo_otorga' => $_POST['titulo_otorga'],
    'descripcion' => $_POST['descripcion'],
    'activa' => $_POST['activa']
];

try {
    $db = $GLOBALS['db'];
    
    $query = "UPDATE carreras SET 
              nombre_carrera = ?, 
              cod_carrera = ?, 
              tipo_formacion = ?, 
              duracion_semestres = ?, 
              titulo_otorga = ?, 
              descripcion = ?, 
              activa = ? 
              WHERE id_carrera = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param(
        "sssissii",
        $datos['nombre_carrera'],
        $datos['cod_carrera'],
        $datos['tipo_formacion'],
        $datos['duracion_semestres'],
        $datos['titulo_otorga'],
        $datos['descripcion'],
        $datos['activa'],
        $datos['id_carrera']
    );
    
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>