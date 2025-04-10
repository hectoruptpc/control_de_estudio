<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// Obtiene los datos del formulario
$idControl = $_POST['idControl'];
$idComponente = $_POST['idComponente']; // Obtiene el id del componente del select
$cantidad = $_POST['cantidad'];
$userId = intval($_SESSION['user']['id']);
$descripcion = 1;

// Verificar la conexión
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Consulta SQL para insertar los datos
$sql = "INSERT INTO inventario_componente (id_control, id_usuario, id_componente, cantidad, descripcion)
VALUES (?, ?, ?, ?, ?)";

// Prepara la consulta
$stmt = $db->prepare($sql);
$stmt->bind_param("iisii", $idControl, $userId, $idComponente, $cantidad, $descripcion); 

// Ejecuta la consulta
if ($stmt->execute()) {
    echo "Materia prima guardada correctamente"; // Envía un mensaje de éxito
} else {
    echo "Error al guardar la materia prima: " . $db->error; // Envía un mensaje de error
}

// Cierra la conexión
$db->close();
?>