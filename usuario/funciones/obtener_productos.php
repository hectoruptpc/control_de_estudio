<?php
include('../../funciones/functions.php');

$sql = "SELECT
    p.id, 
    p.nombre, 
    p.tipo, 
    pr.precio,
    SUM(CASE 
        WHEN ipt.descripcion = 1 THEN ipt.cantidad 
        WHEN ipt.descripcion = 0 THEN -ipt.cantidad  
        ELSE 0 
    END) AS cantidad_disponible
FROM productos p 
INNER JOIN precios pr ON p.id = pr.id_producto AND pr.id_usuario = '$id_usua'
LEFT JOIN inventario_producto_terminado ipt ON p.id = ipt.id_producto AND ipt.id_usuario = '$id_usua'
GROUP BY p.id, p.nombre, p.tipo, pr.precio
HAVING cantidad_disponible > 0;";


$result = $db->query($sql);

$productos = [];
$tienePrecios = false;

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      //$cantidadFormateada = formatearCantidad($row['cantidad_disponible'], $row['tipo']); 
      $cantidadFormateada = $row['cantidad_disponible']; 
      $productos[] = [
          'id' => $row['id'],
          'nombre' => $row['nombre'],
          'precio' => $row['precio'],
          'cantidad_disponible' => $cantidadFormateada, //  Incluir cantidad en la respuesta
      ];
    if (!is_null($row['precio'])) {
      $tienePrecios = true;
    }
  }
}

$db->close();

header('Content-Type: application/json');
echo json_encode(['productos' => $productos, 'tienePrecios' => $tienePrecios]);
?>