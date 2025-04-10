<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// Verificar la conexión
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Obtener datos del formulario
$idControl = $_POST['idControl'];
$idComponentes = $_POST['idComponenteLote']; // Array de IDs de componentes
$cantidades = $_POST['cantidadLote']; // Array de cantidades
$userId = intval($_SESSION['user']['id']);
$descripcion = 1; 

// Validar que las cantidades de arrays coincidan
if (count($idComponentes) !== count($cantidades)) {
    echo "Error: La cantidad de componentes y cantidades no coincide.";
    exit; 
}

// Iniciar transacción para asegurar la integridad de los datos
$db->begin_transaction();

try {
  // Recorrer los arrays e insertar cada producto
  for ($i = 0; $i < count($idComponentes); $i++) {
    $idComponente = $idComponentes[$i];
    $cantidad = $cantidades[$i];

    // Consulta SQL para insertar 
    $sql = "INSERT INTO inventario_componente (id_control, id_usuario, id_componente, cantidad, descripcion)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("iisii", $idControl, $userId, $idComponente, $cantidad, $descripcion);

    if (!$stmt->execute()) {
        throw new Exception("Error al guardar el producto $i: " . $db->error); 
    }
  }

  // Confirmar la transacción si todas las inserciones fueron exitosas
  $db->commit();
  echo "Lote de materia prima guardado correctamente";

} catch (Exception $e) {
  // Revertir la transacción si ocurre algún error
  $db->rollback();
  echo $e->getMessage(); 
}

// Cerrar conexión
$db->close();
?>