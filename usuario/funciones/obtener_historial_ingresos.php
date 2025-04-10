<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// ... (Tu código para obtener $id_usua) ... 

$query = "SELECT
            ipt.id,
            ipt.id_control,
            p.nombre AS nombre_producto,
            ipt.cantidad,
            p.tipo AS tipo_producto,  -- Agregamos el tipo de producto 
            ipt.fecha 
        FROM inventario_producto_terminado ipt
        JOIN productos p ON ipt.id_producto = p.id
        WHERE ipt.id_usuario = '$id_usua' 
        ORDER BY ipt.fecha DESC"; 

$result = $db->query($query);
$arreglo = ['data' => []];

while ($row = $result->fetch_assoc()) {

  //  Usamos la función formatearCantidad:
  $cantidad_formateada = formatearCantidad($row['cantidad'], $row['tipo_producto']); 

  $data = [
    'id' => $row['id'],
    'id_control' => $row['id_control'],
    'nombre_producto' => htmlspecialchars($row['nombre_producto']), 
    'cantidad' => $cantidad_formateada,  //  Usamos la cantidad formateada
    'fecha' => $row['fecha']
  ];
  $arreglo["data"][] = $data;
}

header('Content-Type: application/json');
echo json_encode($arreglo);
?>