<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Listar Recargas Esperando Operador";
include('../../funciones/functions.php');

//$operador = "Movilnet";

function lreo(){
  global $db;

//$arreglo = 0;
if (!isLoggedIn()) {
echo   "Alerta puedes ser baneado por el sistema";
}
else {
  $query = "SELECT * FROM recargar  WHERE confirmacion = 'ESPERANDO_OPERADOR' ORDER BY `id` DESC";

  $result = mysqli_query($db, $query);
  $row =  mysqli_num_rows($result);

  if ($row == 0){
    die();
  } else {
    $c = $db->query($query);
    while($data = $c->fetch_array(MYSQLI_ASSOC))
     {
       $arreglo ["data"][] = $data;
     }

 echo json_encode($arreglo);
  }
  mysqli_free_result($result);
  mysqli_close($db);

    }

}

lreo();


?>
