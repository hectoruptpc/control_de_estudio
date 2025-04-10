<?php 
include('../../funciones/functions.php'); 

$arreglo = [];

$query = "SELECT 
    pre.id,
    p.nombre AS nombre,
    p.tipo AS tipo,
    pre.precio,
    SUM(CASE 
        WHEN ipt.descripcion = 1 THEN ipt.cantidad 
        WHEN ipt.descripcion = 0 THEN -ipt.cantidad  
        ELSE 0 
    END) AS cantidad_disponible
FROM precios pre
JOIN productos p ON pre.id_producto = p.id
LEFT JOIN inventario_producto_terminado ipt ON p.id = ipt.id_producto AND ipt.id_usuario = '$id_usua'
WHERE pre.id_usuario = '$id_usua'
GROUP BY pre.id, p.nombre, p.tipo, pre.precio"; // Asegura que tipo esté en GROUP BY

$result = $db->query($query);

while($data = $result->fetch_assoc()){
    $data['precio'] = number_format($data['precio'], 2, ',', '.');

    // Obtener el tipo del producto para formatear la cantidad
    $tipoProducto = $data['tipo'];  //  Accede al tipo aquí

    // Llama a formatearCantidad dentro del while
    $data['cantidad_disponible'] = formatearCantidad($data['cantidad_disponible'], $tipoProducto);

    $arreglo ["data"][] = $data; 
}

echo json_encode($arreglo);

$db->close();
?>