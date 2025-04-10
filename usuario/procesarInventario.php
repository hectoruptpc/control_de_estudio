  <?php

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $operador = "Procesar Inventario";
  $titulopag = "Procesar Inventario";
  include('../funciones/functions.php');

  include("includes/head.php");


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los componentes enviados desde JavaScript
    $componentes = json_decode($_POST['componentes'], true); // Convertir JSON a array

       // Obtener el id_control más grande
  $consulta = "SELECT MAX(id_control) AS max_id FROM inventario_producto_terminado WHERE id_usuario = '$id_usua'";
  $resultado = $db->query($consulta);
  $fila = $resultado->fetch_assoc();
  $id_control = $fila['max_id'] + 1; // Incrementar en 1 para la próxima operación

    // Obtener id_producto
    $nombre_funcion = $componentes['nombre_funcion'];
    $consulta = "SELECT id FROM productos WHERE nombre = '$nombre_funcion'";
    $resultado = mysqli_query($db, $consulta);
    $fila = $resultado->fetch_assoc();
    $id_producto = $fila['id'];


    // Verifica si la clave "componentes" existe en el arreglo
    if (isset($componentes['valoresFormateados'])) {
        $componentesArray = $componentes['valoresFormateados'];



  // INGRESAR DATOS inventario_producto_terminado

  // Obtener la cantidad desde la variable UI
  $cantidad_UI = $componentes['UI']; // Asegúrate de que 'UI' es la clave correcta
  $cantidad = $cantidad_UI * 1000; // Multiplicar por 1000

  echo '================================================================<br>';
  echo 'PRODUCTO AFECTADO<br>';

  echo '================================================================<br>';

  echo 'ID Usuario: ' . $id_usua . '<br>';
  echo 'ID Control: ' . $id_control . '<br>';
  echo 'Producto: ' . $id_producto . '<br>';
  echo 'Nombre Producto: ' . $nombre_funcion . '<br>';
  echo 'Cantidad Producto: ' . $cantidad . '<br>';
  echo 'Fecha: ' . $fecha_actual_sistema . '<br>';
  echo '================================================================<br>';
  echo 'MATERIA PRIMA ACTUALIZADA<br>';
  echo '================================================================<br>';


  // Descripción
  // 2 VENTAS
  // 1 INGRESO
  // 0 SALIDA
  $descripcion = 1; // Aquí debes definir la lógica para obtener la descripción correcta

  // Insertar en la base de datos
          $insertar_producto = "INSERT INTO inventario_producto_terminado (id_control, id_usuario, id_producto, cantidad, descripcion, fecha) VALUES ('$id_control', '$id_usua', '$id_producto', '$cantidad', '$descripcion', '$fecha_actual_sistema')";
          $resultado = $db->query($insertar_producto);







         // INGRESAR DATOS inventario_componente
    foreach ($componentesArray as $codigo => $valor) {
      // Convertir la cantidad a la misma unidad de medida
      $cantidad = explode(' ', $valor)[0];
      if (strpos($valor, 'Litros') !== false || strpos($valor, 'Kilos') !== false) {
        $cantidad *= 1000;
      }

      echo 'ID Usuario: ' . $id_usua . '<br>';
      echo 'ID Control: ' . $id_control . '<br>';
      echo 'ID Producto: ' . $id_producto . '<br>';
      echo 'Nombre Producto: ' . $nombre_funcion . '<br>';
      echo 'ID Componente: ' . $codigo . '<br>';
      echo 'Cantidad Componente: ' . $cantidad . '<br>';
      echo '-----------------------------------------------<br>';


      // $descripcion
      // 1 INGRESO
      // 0 SALIDA
      $descripcion = 0;
// Insertar en la base de datos
              $insertar = "INSERT INTO inventario_componente (id_control, id_producto, id_usuario, id_componente, cantidad, descripcion, fecha) VALUES ('$id_control', '$id_producto', '$id_usua', '$codigo', '$cantidad', '$descripcion', '$fecha_actual_sistema')";
              $resultado = $db->query($insertar);
    }

    echo "Datos recibidos y guardados correctamente";

        // Resto del código...
    } else {
        echo "Error: La clave 'valoresFormateados' no está definida en el arreglo.";
        header('location: productosdelimpieza.php');
    }
  }


  include("includes/footer.php");
  ?>
