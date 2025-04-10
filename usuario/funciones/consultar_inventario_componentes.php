<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// Obtener los componentes del pedido
if (isset($_POST['componentes'])) {
    $componentes = json_decode($_POST['componentes'], true); // Convertir JSON a array

  // Inicializar el array para almacenar el inventario
  $inventario = [];

  // Consulta a la base de datos 
  $sql = "SELECT ic.id_componente, 
  SUM(CASE WHEN ic.descripcion = 1 THEN ic.cantidad ELSE 0 END) AS cantidad_suma,
  SUM(CASE WHEN ic.descripcion = 0 THEN ic.cantidad ELSE 0 END) AS cantidad_resta
  FROM inventario_componente ic
  WHERE ic.id_usuario = '$id_usua'
  GROUP BY ic.id_componente";

  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    // Recorre los resultados y crea el array de inventario
    while ($row = $result->fetch_assoc()) {
      $inventario[$row['id_componente']] = $row['cantidad_suma'] - $row['cantidad_resta'];
    }
  }

  // Retorna el inventario en formato JSON
  header('Content-Type: application/json');
  echo json_encode($inventario);

} else {
  // Manejar el error 
  header('Content-Type: application/json');
  echo json_encode(['error' => 'No se recibieron los componentes']);
  exit;
}

?>