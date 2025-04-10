<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Ventas";
include('../../funciones/functions.php');

function listar_ventas(){
    global $db, $id_usua;

    $arreglo = [];
    
    // Obtener la lista de ventas con la información del cliente
    $query = "SELECT 
    v.id, 
    v.id_control,
    v.fecha, 
    c.cedula, 
    c.nombre AS nombre_cliente,
    c.telefono1,
    v.cantidad AS total_productos,
    v.monto AS total_venta,
    p.tipo  -- Obtener el tipo del producto
FROM ventas v
JOIN clientes c ON v.id_cliente = c.id
JOIN precios pre ON v.id_producto = pre.id_producto AND v.id_usuario = pre.id_usuario
JOIN productos p ON v.id_producto = p.id -- Unión con la tabla productos
WHERE v.id_usuario = ?
ORDER BY v.fecha DESC;"; 

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_usua);
    $stmt->execute();
    $result = $stmt->get_result();

    while($data = $result->fetch_array(MYSQLI_ASSOC)) {
        // Formatear la cantidad
        $data['total_productos'] = formatearCantidad($data['total_productos'], $data['tipo']); 
        $arreglo ["data"][] = $data;
    }

    // Reemplazar valores nulos por cadenas vacías
    foreach ($arreglo as $clave => $valor) {
        foreach ($valor as $clave2 => $valor2) {
            if ($valor2 === null) {
                $arreglo[$clave][$clave2] = "";
            }
        }
    }

    echo json_encode($arreglo);
    $stmt->close();
}

listar_ventas();
?>