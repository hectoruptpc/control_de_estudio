<?php
include('../../funciones/functions.php');

$idProducto = $_POST['producto'];
$precio = $_POST['precio'];
$idUsuario = $id_usua;


// Verificar si ya existe un precio para el usuario y el producto
$sqlCheck = "SELECT id FROM precios WHERE id_usuario = ? AND id_producto = ?";
$stmtCheck = $db->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $idUsuario, $idProducto);
$stmtCheck->execute();
$stmtCheck->store_result(); 

if ($stmtCheck->num_rows > 0) {
  // Si existe, actualizar el precio
  $sql = "UPDATE precios SET precio = ? WHERE id_usuario = ? AND id_producto = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("dii", $precio, $idUsuario, $idProducto);

  if ($stmt->execute()) {
    echo "Precio actualizado correctamente";
  } else {
    echo "Error al actualizar el precio: " . $db->error;
  }
} else {
  // Si no existe, insertar el nuevo precio
  $sql = "INSERT INTO precios (id_usuario, id_producto, precio) VALUES (?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("iid", $idUsuario, $idProducto, $precio);

  if ($stmt->execute()) {
    echo "Precio creado correctamente";
  } else {
    echo "Error al crear el precio: " . $db->error;
  }
}

$db->close();
?>