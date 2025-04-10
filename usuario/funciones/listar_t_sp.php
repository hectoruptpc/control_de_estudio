<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Listar Tarjetas";
include('../../funciones/functions.php');

$operador = "Movilnet";



function lpusp(){
  global $db, $usua, $operador;

//$arreglo = 0;
if (!isLoggedIn()) {
echo   "Ha sido baneado por el sistema";
}
else {
  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND sin_plan = '1' AND formato = '0' ORDER BY `pedidos`.`fecha_pedido` DESC";

  $result = mysqli_query($db, $query);
  $row =  mysqli_num_rows($result);

  if ($row == 0){
    die();
  } else {
    $c = $db->query($query);
    while($data = $c->fetch_array(MYSQLI_ASSOC))
     {
       $data['id']=base64_encode($data['id']);

       if ($data['status_pedido'] == "ESPERANDO"){
         $data['status_pedido'] = '<div class="w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="EN ESPERA" data-content="Su pago aun no ha sido conformado.">
   EN ESPERA  <i class="fa fa-clock"></i>
 </div>';
}

if ($data['status_pedido'] == "COMPLEMENTO"){
  $data['status_pedido'] = '<div class="w-70 mx-auto alert alert-dark" role="alert" data-toggle="popover" title="COMPLEMENTO" data-content="Esta operacion es un Complemento de pago.">
COMPLEMENTO  <i class="fa fa-thumbs-up"></i>
</div>';
}

       $motivo = $data['motivo_rechazo'];

       $data['nro_transf'] = "Nro Transf: ".str_replace("RECHAZADO", "", $data['nro_transf']);

if ($data['status_pedido']=="RECHAZADO") {
  $data['status_pedido']='<span class="d-inline-block" data-toggle="popover" data-html="true" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo:<br> '.$motivo.'."><div class="text-center w-70 mx-auto alert alert-danger" role="alert">
   <i class="fa fa-exclamation-triangle"></i> RECHAZADO</div></span>';
}
 $id = $data['id'];
 if ($data['status_pedido']=="ENTREGADO") {
  $data['status_pedido']='<form autocomplete="off" class="was-validated" method="get" action= "ver_pedido.php?id_pedido='.$id.'">
  <input type="hidden" name="id_pedido" value="'.$id.'">
  <button data-html="true" data-toggle="popover" title="SECCION DE DESCARGAS" data-content="Aca podra acceder a sus Tarjetas UN1CAS." type="submit" class="btn btn-success" >DESCARGAR  <i class="fa fa-file-download"></i></button> </form>';
}

 if ($data['status_pedido'] == "APROBADO"){
$data['status_pedido'] = '<div class="w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="CONFORMADO" data-content="Este mensaje es indicativo de que su pago ya fue aprobado, queda a la espera de la entrega de su pedido.">
CONFORMADO <i class="fa fa-clock"></i>
</div>'; }

if ($data['status_pedido'] == "ESPERANDO"){
  $data['status_pedido'] = '<div class="w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="EN ESPERA" data-content="Su pago aun no ha sido conformado.">
EN ESPERA  <i class="fa fa-clock"></i>
</div>';
}


   $arreglo ["data"][] = $data;
 }

 echo json_encode($arreglo);
  }
  mysqli_free_result($result);
  mysqli_close($db);

}
}



lpusp();


?>
