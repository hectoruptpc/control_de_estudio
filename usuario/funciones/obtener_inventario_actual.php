<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// ... (Tu código para obtener $id_usua) ... 

$query = "SELECT 
    p.id AS id_producto,
    p.nombre AS nombre_producto,
    p.tipo AS tipo_producto,  --  Agregamos el tipo de producto
      SUM(CASE 
        WHEN ipt.descripcion = 1 THEN ipt.cantidad 
        WHEN ipt.descripcion = 0 THEN -ipt.cantidad  
        ELSE 0 
    END) AS cantidad_disponible
FROM productos p
LEFT JOIN inventario_producto_terminado ipt ON p.id = ipt.id_producto AND ipt.id_usuario = '$id_usua' 
GROUP BY p.id, p.nombre, p.tipo  -- Agrupamos por tipo también
HAVING cantidad_disponible > 0  -- Solo mostramos productos con cantidad > 0";

$result = $db->query($query);
$arreglo = ['data' => []];

while ($row = $result->fetch_assoc()) {

  $cantidad_formateada = formatearCantidad($row['cantidad_disponible'], $row['tipo_producto']);

  $data = [
    'id_producto' => $row['id_producto'],
    'nombre_producto' => htmlspecialchars($row['nombre_producto']), // escapa caracteres HTML
   'cantidad_disponible' => $cantidad_formateada //  Usamos la cantidad formateada 
  ];
  $arreglo["data"][] = $data;
}

header('Content-Type: application/json');
echo json_encode($arreglo);
?>
