<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Listar Todos los Pedidos";
include('../../funciones/functions.php');

function ltp(){
  global $db;

//$arreglo = 0;
if (!isLoggedIn()) {
echo   "Tenga cuidado pueder ser baneado por el sistema";
}
else {
  $query = "SELECT  pedidos.*, users.id AS uid, users.nombre, users.email, users.username FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario ORDER BY id DESC";

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



ltp();


?>
