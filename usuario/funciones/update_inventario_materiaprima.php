<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// Obtiene los datos del formulario
$id = $_POST['id']; // ID del registro a actualizar
$idControl = $_POST['idControl'];
$idComponente = $_POST['idComponente'];
$cantidad = $_POST['cantidad'];
$userId = intval($_SESSION['user']['id']);
$descripcion = 1; // Asumiendo que 'descripcion' es siempre 1 para INGRESO

// Verificar la conexión
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Consulta SQL para actualizar los datos
$sql = "UPDATE inventario_componente 
        SET id_control = ?, 
            id_componente = ?, 
            cantidad = ?
        WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("iiii", $idControl, $idComponente, $cantidad, $id);

// Ejecuta la consulta
if ($stmt->execute()) {
    echo "Materia prima actualizada correctamente";
} else {
    echo "Error al actualizar la materia prima: " . $db->error;
}

// Cierra la conexión
$db->close();

?>