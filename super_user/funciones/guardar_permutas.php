<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();

$titulopag = "Listar Recargas Esperando Operador";
include('../../funciones/functions.php');

var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $c1 = $_POST['c1'];
    $c2 = $_POST['c2'];
    $c3 = $_POST['c3'];
    $c4 = $_POST['c4'];

    // Iterar sobre las filas y actualizar la tabla "creador"
    for ($i = 0; $i < count($c1); $i++) {
        $id = $i + 1; // Asumiendo que el ID es secuencial
        $sql = "UPDATE creador SET 
                c1 = '" . $c1[$i] . "', 
                c2 = '" . $c2[$i] . "', 
                c3 = '" . $c3[$i] . "', 
                c4 = '" . $c4[$i] . "'
                WHERE id = " . $id;

                echo "ID: " . $id . ", SQL: " . $sql . "<br>"; // Muestra la consulta SQL

        if ($db->query($sql) !== TRUE) {
            echo "Error al actualizar la fila " . $id . ": " . $db->error;
        }
    }

    echo "Datos actualizados correctamente.";

    $db->close();

    $_SESSION['editar_permutas'] = "Se ha actualizado de manera correcta";

      // Redirige a la página anterior
      $ruta_cm = $_SERVER['HTTP_REFERER']; 
      echo $ruta_cm;
    header("Location: " . $ruta_cm);
      exit; // Detiene la ejecución del script después de la redirección

      ob_end_flush();


} else {
    echo "Acceso no permitido.";
}

?>