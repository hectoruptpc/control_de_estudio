<?php
include('../../funciones/functions.php');

// Obtiene los datos de la venta
$idCliente = $_POST['id_cliente'];
$productos = json_decode($_POST['productos']);
$cantidades = json_decode($_POST['cantidades']);
$montos = json_decode($_POST['montos']);
$idUsuario = $id_usua; 

// Obtiene el último id_control de la tabla "ventas"
$sqlUltimoIdControl = "SELECT MAX(id_control) AS ultimo_id_control FROM ventas";
$resultadoUltimoIdControl = $db->query($sqlUltimoIdControl);

if ($resultadoUltimoIdControl->num_rows > 0) { 
  $fila = $resultadoUltimoIdControl->fetch_assoc();
  $ultimoIdControl = $fila['ultimo_id_control'];
  $idControl = $ultimoIdControl + 1;
} else {
  // Si la tabla está vacía, empieza desde 1
  $idControl = 1; 
}

try { 
    $db->begin_transaction();  

    for ($i = 0; $i < count($productos); $i++) {
        $idProducto = $productos[$i];
        $cantidad = $cantidades[$i]; 
        $monto = $montos[$i];  

        // Valida la cantidad 
        if ($cantidad <= 0) {
            throw new Exception("La cantidad debe ser mayor a 0 para el producto ID: $idProducto");
        } 
         

        // Inserta en la tabla "ventas" 
        $sqlVenta = "INSERT INTO ventas (id_control, id_usuario, id_cliente, id_producto, cantidad, monto, status, nota)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtVenta = $db->prepare($sqlVenta);
        $status = "pendiente"; 
        $nota = ""; 

        $stmtVenta->bind_param("iiiiddss", $idControl, $idUsuario, $idCliente, $idProducto, $cantidad, $monto, $status, $nota); 

        if (!$stmtVenta->execute()) { 
            throw new Exception("Error al insertar en 'ventas': " . $stmtVenta->error); 
        } 
        
        // Inserta en la tabla "inventario_producto_terminado"  
        $sqlInventario = "INSERT INTO inventario_producto_terminado (id_control, id_usuario, id_producto, cantidad, descripcion) 
                        VALUES (?, ?, ?, ?, ?)"; 
        $stmtInventario = $db->prepare($sqlInventario);
        $descripcion = 0; //  0 indica venta 

        $stmtInventario->bind_param("iiidi", $idControl, $idUsuario, $idProducto, $cantidad, $descripcion);

        if (!$stmtInventario->execute()) {
            throw new Exception("Error al insertar en 'inventario_producto_terminado': " . $stmtInventario->error); 
        }  
    } 

    $db->commit();
    echo "Venta guardada correctamente";
} catch (Exception $e) {
    $db->rollback(); 
    echo "Error: " . $e->getMessage();
} 

$db->close();
?>