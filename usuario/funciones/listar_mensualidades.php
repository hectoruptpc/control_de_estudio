<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Listar Mensualidades";
include('../../funciones/functions.php');

//$operador = "Movilnet";



function lmensualidades(){
  global $db, $usua, $id_usua, $operador;

//$arreglo = 0;
if (!isLoggedIn()) {
echo   "Ha sido baneado por el sistema";
}
else {
  $query = "SELECT * FROM pagos  WHERE user = '$usua' ORDER BY `id` DESC";

  $result = mysqli_query($db, $query);
  $row =  mysqli_num_rows($result);

  if ($row == 0){
    die();
  }
  else {
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



lmensualidades();


?>
