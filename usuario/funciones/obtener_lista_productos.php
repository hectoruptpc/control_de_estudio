<?php
include('../../funciones/functions.php');

// Obtener los productos con sus precios (asumiendo que la tabla "precios" tiene la relación con el usuario)
$sql = "SELECT id, nombre FROM productos"; 
$result = $db->query($sql);

$productos = [];
$tienePrecios = false; // Indicador para saber si el usuario tiene precios

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $productos[] = $row;
    if (!is_null($row['precio'])) { // Verificar si el precio no es NULL
      $tienePrecios = true;
    }
  }
}
$db->close();

header('Content-Type: application/json');
echo json_encode(['productos' => $productos, 'tienePrecios' => $tienePrecios]); // Agregar el indicador en la respuesta
?>