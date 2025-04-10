<?php
include('../../funciones/functions.php');

// Obtener los productos del usuario que NO tienen precio definido
$sql = "SELECT pr.id, p.nombre, pr.precio 
        FROM precios pr
        INNER JOIN productos p ON pr.id_producto = p.id
        WHERE pr.id_usuario = '$id_usua'"; 
$result = $db->query($sql);

$productos = [];
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}
$db->close();

header('Content-Type: application/json');
echo json_encode($productos);
?>