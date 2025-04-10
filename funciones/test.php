<?php

  $registrar_recarga = '<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá solicitar se efectue una recarga a cualquier numero Prepago - '.@$operador.'">

  <button type="button" class="btn btn-danger mt-1" data-toggle="modal" data-target="#modal-a"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
       <i class="fa fa-phone-square"></i>  Registrar Recarga '.@$operador.' <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></button>
       </span>';

function monto_recarga_sp_test(){
 global $db, $operador, $multiplo, $porcentaje, $monto_minimo, $monto_maximo;

selector_operador();

$query = "SELECT * FROM `monto_recarga` WHERE mod (monto, '$multiplo') = 0 AND monto >= $monto_minimo AND monto <= $monto_maximo ORDER BY monto ASC";
$results = mysqli_query($db, $query);
while ($valores = mysqli_fetch_array($results)) {

 $monto = e($valores['monto']);
 $monto_f = number_format($monto,2,',','.');
 $calculo = $monto * $porcentaje / 100;
 $total = $monto + $calculo;
 $total_f = number_format($total,2,',','.');

echo '<option value="'.$monto.'"> Para Recargar '.$monto_f.' Bs Deberá Pagar '.$total_f.' Bs.</option>';
 }

}

function generar_recarga_test(){
  global $db, $username, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $debe_pagar_operador, $concepto, $operador, $link, $t, $num_min, $text_num_min, $ph, $fecha_sistema, $monto_favor, $mens_monto_favor;

  a_favor();


  selector_operador();

  if (isActive()){

if ($operador == $operador){
  echo '<h1>'.$operador.'</h1>';


  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO') AND sin_plan = '1' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
  // ANALIZAR QUE NO TENGA PEDIDOS EN ESPERA
if ($rows > 0){

echo '<div class="alert alert-danger" role="alert"" >
      <h3>
  LO SENTIMOS, USTED POSEE UN REGISTRO DE RECARGAS QUE AUN NO HA SIDO ATENDIDO.
      </h3>
    </div>';


} else {

echo ' VERIFIQUE MUY BIEN LOS DATOS QUE VA A INGRESAR AL SISTEMA';

echo ' <form autocomplete="off" class="was-validated" method="post" action= "">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto a recargar</label>
<select class="custom-select form-control-lg" id="monto" name="monto" value="';
//echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto_recarga_sp_test();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
</div>

<!-- Boton Agregar Usuario -->

  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-b"> <i class="fa fa-user-plus"></i>  Seleccionar de Agenda </button>

<div class="form-group">
<label for="nro">Numero A Recargar</label>
<input title = "'.$text_num_min.'"  type="num"  pattern="'.$num_min.'" minlenght="8" maxlenght="11" class="form-control form-control-lg" id="nro" aria-describedby="nro" placeholder="'.$ph.'" name="nro" value="';
//echo $nro;
echo '" required>
<div class="invalid-feedback">'.$text_num_min.'</div>
</div>
<input type="hidden" name="accion" value="insert">
<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="operador" value="'.$operador.'">
<input type="hidden" name="sin_plan" value="1">

<button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

</form>';
} // CIERRE VERIFICAR QUE NO TENGA PEDIDOS EN ESPERA




} // CIERRE PARA OPERADORAS


} //_TODO ANTES DE ESTO PASA SI EL USUARIO ESTA ACTIVO
else {

    echo '<div class="alert alert-warning" role="alert"" >
      <h3>SU USUARIO ESTA BLOQUEADO</h3>
      <p>Si considera que es un error, favor ingrese al area de <a target="_BLANK" href= "http://www.jesuministrosymas.com.ve/contactenos" ><b>CONTACTENOS</b></a> para mas informacion.</p>
  </div>';

  }

}


 ?>
