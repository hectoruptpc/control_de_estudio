<?php

// Activar el reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', '1');


include '../../../funciones/conexion.php';

if(isset($_REQUEST['displaySend'])){
    $table = '<table class="table">
    <thead class="thead-dark">
      <tr>
        <th scope="col">#</th>
        <th scope="col">Sección</th>
        <th scope="col">Contenido</th>
        <th scope="col">Fecha</th>
        <th scope="col">Operación</th>
      </tr>
      </thead>';
    $sql = "SELECT * FROM contenido ORDER BY `contenido`.`fecha` DESC";
    $result = mysqli_query($db, $sql);
    $number = 1;
    while($row = mysqli_fetch_assoc($result)){
        $id = $row['id'];
        $seccion = $row['seccion'];
        $contenido = $row['contenido'];
        $ct = substr($contenido, 0, 600) . ".......";
        $ct = strip_tags($ct, "<img><h1><h2><h3><h4><h5><br><p><b>");
        $fecha = $row['fecha'];
        $table .= '<tr>
          <td scope="row">'.$number.'</td>
          <td>'.$seccion.'</td>
          <td>'.$ct.'</td>
          <td>'.$fecha.'</td>
          <td>
          <div class="btn-group" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-dark" onclick="GetDetails('.$id.')">Actualizar</button>
          <button type="button" class="btn btn-danger" onclick="DeleteUser('.$id.')">Borrar</button>
        </div>
          </td>
          </tr>';
        $number++;
    }
    $table .= '</table>';
    echo $table;
}
?>
