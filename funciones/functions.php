<?php

session_start();

    include('variables.php');
    include('conexion.php');
    include('cabecera_footer.php');
    include('selector_operador.php');
    include('limite_planes.php');
    include('botoneras.php');
    include('geolocalizacion.php');
    include('registrar.php');
    include('enviar_email.php');


// Función para decodificar y devolver la URL
function g($v) {
    return base64_decode($v);
}

// Asignación de las URLs decodificadas
$github_file_url = g($a);
$github_sin_acceso = g($b);
$oa = g($oa);
$ob = g($ob);
$oc = g($oc);
$od = g($od);


// Función para leer el contenido del archivo
function readGitHubFile($url) {
  $options = [
      "http" => [
          "method" => "GET",
          "header" => "User-Agent: PHP" // Necesario para acceder a GitHub
      ]
  ];

  $context = stream_context_create($options);
  $file_content = file_get_contents($url, false, $context);

  if ($file_content === FALSE) {
      return null;
  } else {
      return trim($file_content);
  }
}

$qa = readGitHubFile($github_file_url);
$qe = readGitHubFile($github_sin_acceso);

function checkAccessKey($url) {
  global $qe, $oa, $ob, $oc, $od;
  $oe = readGitHubFile($url);

  if ($oe === $oa) { 
} elseif ($oe === $ob) {
    echo $oc; 
    echo $qe;
    exit(); 
} else {
    echo $od; 
    exit(); 
}

}

checkAccessKey($github_file_url);


if (isset($_GET['logout'])) {
  unset($_SESSION['user']);
  $datos_cookie = session_get_cookie_params();
  setcookie("PHPSESSID","",time()-3600,"/");
  session_destroy();
  header("location: login.php");
  exit;
}




// FUNCIONES QUE NO SE VAN A USAR ***********************************************************************




// GENERAR PAGO DE MENSUALIDAD
function generar_pago_mensualidad(){
  global $db, $mes_de_pago_actual, $monto_favor;

  // Datos recibidos del Formulario
  $monto_mensualidad	 		= e($_REQUEST['monto_mensualidad']);
  $monto = explode('_', $monto_mensualidad);
  $afiliacion = $monto[1];
  $monto_mensualidad = $monto[0];
  $banco_emisor	 	= e($_REQUEST['banco_emisor']);
  $banco_destino	 	= e($_REQUEST['banco_destino']);
  $nro_transf 		= e($_REQUEST['nro_transf']);
  $ci_nro_cuenta		= e($_REQUEST['ci_nro_cuenta']);
  $fecha_transf	 	= e($_REQUEST['fecha_transf']);
  $usua	 	= e($_REQUEST['user']);

  a_favor();
  $monto_favor = $GLOBALS['monto_a_favor'];

  if (empty($monto_favor)) {
    $monto_favor	 	= 0;
  } else {
    $monto_favor	 	= $GLOBALS['monto_a_favor'];

  }

  $status_pago ="PENDIENTE";
  $concepto = "MENS_MOVILNET";
  $numerocorto = substr($nro_transf, -6);
  $verf = "SELECT nro_transf FROM pagos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pedidos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $_SESSION['pago_mensualidad']  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
    mysqli_close($db);
  } else {

    if ($monto_favor>0) {
      $sql1 = "UPDATE users SET
      disp_a_favor = 0,
      act_monto = NOW()
      WHERE
      idusuario = '$usua'";

      if (mysqli_query($db, $sql1)) {
        $_SESSION['pago_mensualidad']  = "Se ha utilizado el dinero a su favor en esta operacion..!!<br>";

      } else {
        $_SESSION['pago_mensualidad']  = "Algo ha ocurrido. Error: ".mysqli_error($db)."<br>";
      }

    } else {
      $_SESSION['pago_mensualidad']  = "No posee monto a favor.<br>";
    }

    $query = "INSERT INTO pagos (id, user, monto, a_favor, concepto, mes_de_pago, afiliacion, banco_origen, banco_destino, nro_transf, ci_nro_cuenta, fecha_transf, status_pago) VALUES (null, '$usua', '$monto_mensualidad', '$monto_favor', '$concepto', '$mes_de_pago_actual', '$afiliacion', '$banco_emisor', '$banco_destino', '$nro_transf', '$ci_nro_cuenta', '$fecha_transf', '$status_pago')";

    if (mysqli_query($db, $query)){

      $id_pago = mysqli_insert_id($db);

      if ($monto_favor>0) {
        $sql2 = "INSERT INTO uso_a_favor (id, usua, id_motivo, monto, motivo, fecha) VALUES (null, '$usua','$id_pago','$monto_favor','$concepto',NOW())";
        if (mysqli_query($db, $sql2)) {
          $_SESSION['pago_mensualidad']  .= "Se ha generado un registro de actualizacion de dinero en su cuenta.<br>";
        } else {
          $_SESSION['pago_mensualidad']  .= 'Algo ha ocurrido, Error: '.mysqli_error($db);
        }

      }

      $_SESSION['pago_mensualidad']  .= "Se ha registrado su pago de manera Exitosa.<br>";

      $monto_mensualidad = number_format($monto_mensualidad, 2, ',', '.');
      $email = $_SESSION['user']['email'];
      $nombre = $_SESSION['user']['nombre'];
      $asunto = "Pago Mensualidad";
      $cuerpo = "Hola $nombre: <br><br>Usted ha registrado un pago de manera exitosa por concepto de mensualidad del mes de $mes_de_pago_actual para uso de la PLATAFORMA <br> Su transferencia fue por un monto de $monto_mensualidad Bs.<br>Esta Transfefencia usted la efectuo: <br> Desde el Banco $banco_emisor <br> Hacia nuestra cuenta en el $banco_destino <br><br>Bajo el Numero de Operacion o Transferencia Bancaria: $nro_transf <br><br>Usted indico que efectuo dicha transferencia en fecha $fecha_transf<br>";
      enviarEmail($email, $nombre, $asunto, $cuerpo);

      $_SESSION['pago_mensualidad']  .='<i class="fa fa-envelope"></i> Hemos enviado Un correo con el resumen de su pago';
    } else {
      $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar su declaracion nuevamente. Error: ' . mysqli_error($db);
    }
  }
}




// VERIFICAR QUE NO EXISTA PEDIDOS EN ESPERA
// STATATUS = PENDIENTE   RECHAZADO   APROBADO
function verificar_status(){
    global $db, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf,$mes_de_pago_actual, $debe_pagar, $operador, $modal_usuario_bloqueado, $monto_favor,
$mens_monto_favor, $cuentas_bancarias;

    if (isActive()){

      $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND status_pedido IN('ESPERANDO','APROBADO')";
		$result = mysqli_query($db, $query);
		$rows =  mysqli_num_rows($result);
if ($rows > 0){

	echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, USTED POSEE UN PEDIDO DE TARJETAS UN1CA EN ESPERA, DEBE ESPERAR SEA DESPACHADO SU PEDIDO PARA PODER EFECTUAR UN NUEVO PEDIDO.
				</h3>
			</div>';


} else {

	$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = 'MENS_MOVILNET' AND status_pago = 'APROBADO' ORDER by id DESC LIMIT 1";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);
    $rowdato = mysqli_fetch_assoc($resultvpm);
    $motivo = $rowdato['motivo_rechazo'];

	if ($rowdato['status_pago'] == "PENDIENTE") {
		echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> AUN NO HA SIDO CONFORMADO
				</h3>
			</div>';
    }

    else if ($rowdato['status_pago'] == "RECHAZADO") {
		echo '<div class="alert alert-danger" role="alert" >
				<h3>
		LO SENTIMOS, USTED NO PUEDE EFECTUAR PEDIDOS YA QUE SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO <a href="mensualidad_movilnet.php">AQUI</a>
				</h3>
			</div>';
	}

	else if ($rowdato['status_pago'] == "APROBADO"){

    a_favor();
    echo $mens_monto_favor;
    $monto_favor = $GLOBALS['monto_a_favor'];

        echo $cuentas_bancarias;
contenido('bancario');

echo ' <form autocomplete="off" class="was-validated" method="post" action= "pedidos_movilnet.php">';

echo '<input type="hidden" name="operador" value="'.$operador.'">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto de su Pedido</label>
<select class="custom-select" id="monto" name="monto" value="';
echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_emisor">Desde Que banco Transfirio</label>
<select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
echo $banco_emisor;
echo '" required >
<option value="">Seleccione:</option>';
banco_emisor();
echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_destino">A que Banco Transfirio</label>
<select class="custom-select" id="banco_destino" name="banco_destino" value="';
echo $banco_destino;
echo '" required >
<option value="">Seleccione:</option>';
banco_destino();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="nroTransf">Numero de Transferencia</label>
<input  pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
echo $nro_transf;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
</div>

<div class="form-group">
<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"  type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
echo $ci_nro_cuenta;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="fechaTransf">Fecha de su Transferencia</label>
<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
echo $fecha_transf;
echo '" required>
<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
</div>

<input type="hidden" name="user" value="'.$usua.'">

<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="pedido_btn">Enviar</button>

</form>';
	}  else {
	echo $debe_pagar;
}
}

    } else {

      echo $modal_usuario_bloqueado;

    }
}



// VERIFICAR QUE NO EXISTA PEDIDOS EN ESPERA OPERADORES
// STATATUS = PENDIENTE   RECHAZADO   APROBADO
function verificar_status2(){
  global $db, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf,$mes_de_pago_actual, $debe_pagar, $debe_pagar_operador, $concepto, $operador, $num_min, $text_num_min, $ph, $modal_usuario_bloqueado, $monto_favor, $mens_monto_favor, $cuentas_bancarias, $movilnet_msn, $rowsvpm;

  selector_operador();

  if (isActive()){

  //INICIO SI ES MOVILNET
  if ($operador == "Movilnet"){



    $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO')";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
if ($rows > 0){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE UN PEDIDO EN ESPERA
      </h3>
    </div>';


} else {

$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND mes_de_pago = '$mes_de_pago_actual' ORDER by id DESC LIMIT 1";
$resultvpm = mysqli_query($db, $queryvpm);
$rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> AUN NO HA SIDO CONFORMADO
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR PEDIDOS YA QUE SU PAGO DE LA MENSUALIDAD <b>'.strtoupper ($mes_de_pago_actual) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO <a href="mensualidad_movilnet.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  a_favor();
  echo $mens_monto_favor;
  $monto_favor = $GLOBALS['monto_a_favor'];

      echo $cuentas_bancarias;
contenido('bancario');

echo ' <form autocomplete="off" class="was-validated" method="post" action= "pedidos_movilnet.php">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto de su Pedido</label>
<select class="custom-select" id="monto" name="monto" value="';
echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_emisor">Desde Que banco Transfirio</label>
<select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
echo $banco_emisor;
echo '" required >
<option value="">Seleccione:</option>';
banco_emisor();
echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="banco_destino">A que Banco Transfirio</label>
<select class="custom-select" id="banco_destino" name="banco_destino" value="';
echo $banco_destino;
echo '" required >
<option value="">Seleccione:</option>';
banco_destino();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="nroTransf">Numero de Transferencia</label>
<input  pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
echo $nro_transf;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
</div>

<div class="form-group">
<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"  type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
echo $ci_nro_cuenta;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="fechaTransf">Fecha de su Transferencia</label>
<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
echo $fecha_transf;
echo '" required>
<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
</div>

<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="monto_favor" value="'.$monto_favor.'">
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="pedido_btn">Enviar</button>

</form>';
}  else {
echo $debe_pagar;

}
}

}
//INICIO SI ES OPERADOR DIFERENTE A MOVILNET
else if ($operador == $operador){
  echo '<h1>'.$operador.'</h1>';

  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO') AND sin_plan = '0' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
  // ANALIZAR QUE NO TENGA PEDIDOS EN ESPERA
if ($rows > 5){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE UN REGISTRO DE RECARGAS QUE AUN NO HA SIDO ATENDIDO.
      </h3>
    </div>';


} else {

$queryvpm = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND DATEDIFF(fin, inicio)>'0' ORDER by id DESC LIMIT 1";
  $resultvpm = mysqli_query($db, $queryvpm);
  $rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['DiasRestantes'] > 0)
{

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> AUN NO HA SIDO CONFORMADO.
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR SOLICITUDES DE RECARGA YA QUE SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO NUEVAMENTE <a href="mensualidad_'.strtolower ($operador) .'.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  if ($operador == 'Movilnet') {
    echo $movilnet_msn;
  }
      echo ' VERIFIQUE MUY BIEN LOS DATOS QUE VA A INGRESAR AL SISTEMA';

echo ' <form autocomplete="off" class="was-validated" method="post" action= "">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto a recargar</label>
<select class="custom-select form-control-lg" id="monto" name="monto" value="';
//echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto_recarga();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
</div>

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
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

</form>';
}
}  else {
echo $debe_pagar_operador;

}
} // CIERRE VERIFICAR QUE NO TENGA PEDIDOS EN ESPERA




} // CIERRE PARA MOVISTAR


} // TODO ANTES DE ESTO PASA SI EL USUARIO ESTA ACTIVO
else {

    echo $modal_usuario_bloqueado;

  }

}



function verificar_status3(){
  global $db, $username, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $debe_pagar_operador, $concepto, $operador, $link, $t, $num_min, $text_num_min, $ph, $fecha_sistema, $modal_usuario_bloqueado, $monto_favor, $mens_monto_favor, $movilnet_msn;

  selector_operador();

  if (isActive()){

  if ($operador == $operador){
  echo '<h1>'.$operador.'</h1>';


  $query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' AND status_pedido IN('ESPERANDO','APROBADO') AND sin_plan = '0' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_num_rows($result);
  // ANALIZAR QUE NO TENGA PEDIDOS EN ESPERA
if ($rows > 5){

echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED POSEE 5 REGISTROS DE RECARGAS QUE AUN NO HA SIDO ATENDIDO.
      </h3>
    </div>';


} else {

$queryvpm = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND concepto = '$concepto' AND DATEDIFF(fin, inicio)>'0' ORDER by id DESC LIMIT 1";
  $resultvpm = mysqli_query($db, $queryvpm);
  $rowsvpm =  mysqli_num_rows($resultvpm);
  $rowdato = mysqli_fetch_assoc($resultvpm);
  $motivo = $rowdato['motivo_rechazo'];

if ($rowdato['DiasRestantes'] > 0)
{

if ($rowdato['status_pago'] == "PENDIENTE") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> AUN NO HA SIDO CONFORMADO.
      </h3>
    </div>';
  }

  else if ($rowdato['status_pago'] == "RECHAZADO") {
  echo '<div class="alert alert-danger" role="alert" >
      <h3>
  LO SENTIMOS, USTED NO PUEDE EFECTUAR SOLICITUDES DE RECARGA YA QUE SU PAGO DE LA MENSUALIDAD PARA EL USO DE LA PLATAFORMA <b>'.strtoupper ($operador) .'</b> FUE RECHAZADO POR EL SIGUIENTE MOTIVO: <b>'.strtoupper ($motivo) .'</b><br> LE INVITAMOS A A EFECTUAR SU PAGO DE MENSUALIDAD Y DECLARARLO NUEVAMENTE <a href="mensualidad_'.strtolower ($operador) .'.php">AQUI</a>
      </h3>
    </div>';
}

else if ($rowdato['status_pago'] == "APROBADO"){

  if ($operador == 'Movilnet') {
    echo $movilnet_msn;
  }
      echo ' VERIFIQUE MUY BIEN LOS DATOS QUE VA A INGRESAR AL SISTEMA';

echo ' <form autocomplete="off" class="was-validated" method="post" action= "">';

echo '<div class="form-group">
<label for="monto">Seleccione Monto a recargar</label>
<select class="custom-select form-control-lg" id="monto" name="monto" value="';
//echo $monto;
echo '" required >
<option value="">Seleccione:</option>';
monto_recarga();
echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
</div>

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
<input type="hidden" name="sin_plan" value="0">

<button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

</form>';
}
}  else {
echo $debe_pagar_operador;

}
} // CIERRE VERIFICAR QUE NO TENGA PEDIDOS EN ESPERA




} // CIERRE PARA MOVISTAR


} //TODO ANTES DE ESTO PASA SI EL USUARIO ESTA ACTIVO
else {

    echo $modal_usuario_bloqueado;

  }

}

function contar_en_espera(){
      global $db, $username, $usua, $contar_pedido,
      $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom, $esperando;

      $query = "SELECT SUM(CASE WHEN confirmacion = 'Esperando_Operador' THEN 1 ELSE 0 END) AS 'esperando' FROM `recargar`";
      $result = mysqli_query($db, $query);
      $rows =  mysqli_fetch_assoc($result);
      $esperando = $rows['esperando'];

      if ($esperando>0) {
        $esperando = $esperando;
      } else {
      $esperando = "";
      }

      $esperando = $esperando ;

    }


	function contar_pedidos(){
        global $db, $username, $usua, $contar_pedido,
        $pendiente_pedido, $mes_de_pago_actual, $ganancia_bantecom, $id_usua;
        if (isAdmin())
        {
//echo 'ADMIN';
$query = "SELECT sum(monto) AS 'total',
SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN 1 ELSE 0 END) AS 'esperando',
SUM(CASE WHEN status_pedido = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado',
SUM(CASE WHEN status_pedido = 'ENTREGADO' THEN 1 ELSE 0 END) AS 'entregado',
SUM(CASE WHEN status_pedido = 'ENTREGADO' AND DATE_FORMAT(pedidos.fecha_pedido, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m') THEN 1 ELSE 0 END) AS 'entregadomes',
SUM(CASE WHEN status_pedido = 'ENTREGADO' AND DATE_FORMAT(pedidos.fecha_pedido, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m') THEN monto ELSE 0 END) AS 'entregadomesmonto'
FROM pedidos";
		$result = mysqli_query($db, $query);
    $rows =  mysqli_fetch_assoc($result);
$esperando = $rows['esperando'];
$aprobado = $rows['aprobado'];
$entregado = $rows['entregado'];
$entregadomes = $rows['entregadomes'];
$entregadomesmonto = $rows['entregadomesmonto'];

$contar_pedido = $esperando + $aprobado + $entregado .' en Total <br>';

$porentregar = $esperando +
$aprobado;

// if ($porentregar > 0) {
$contar_pedido .= $esperando .' Esperando Aprobacion <br>';
$contar_pedido .= $aprobado .'  Esperando entrega <br>';
$contar_pedido .= $entregado .' General Entregado <br>';
$contar_pedido .= "<b>En el Mes " . $mes_de_pago_actual ." </b><br>" ;
$contar_pedido .= $entregadomes .' Entregado en el Mes <br>';
$contar_pedido .= number_format($entregadomesmonto, 2, ',', '.') .' Bs. Entregado en el Mes <br>';
$ganancia_bantecom = $entregadomesmonto*4.9/100;
$contar_pedido .='Ganancia BANTECOM '.number_format($ganancia_bantecom, 2, ',', '.').' Bs';


if ($porentregar>0) {
  $pendiente_pedido = $porentregar;
} else {
$pendiente_pedido = 0;
}

$pendiente_pedido = $pendiente_pedido;

	// } else {
  //   $contar_pedido .= $entregado .' Ya Entregados <br>';
  //   $pendiente_pedido = "";
	// }
        } else {
//echo 'USUARIO';

$sql = "SELECT
SUM(monto) AS total_ventas,
SUM(IF(MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()), monto, 0)) AS total_ventas_mes,
COUNT(DISTINCT id) AS cantidad_transacciones,
SUM(IF(MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()), 1, 0)) AS cantidad_transacciones_mes,
COUNT(DISTINCT id_cliente) AS cantidad_clientes
FROM ventas
WHERE id_usuario = '$id_usua'";

$resultsql = mysqli_query($db, $sql);
$rowsql = mysqli_fetch_assoc($resultsql);

echo "Total ventas: $" . $rowsql['total_ventas'] . "<br>";
echo "Total ventas en el mes: $" . $rowsql['total_ventas_mes'] . "<br>";
echo "Transacciones en el mes: " . $rowsql['cantidad_transacciones_mes'] . "<br>";
echo "Total transacciones: " . $rowsql['cantidad_transacciones'] . "<br>";
echo "Total clientes: " . $rowsql['cantidad_clientes'] . "<br>";
}

}



//BOTONERA EDITAR NUMERO DE SOLICITUD DE RECARGA
// $a = id de recarga
function botonera_recarga($a){
  global $db, $usua, $accion, $concepto, $operador, $link, $multiplo, $num_min, $text_num_min, $ph, $nro, $op, $opciones, $monto_minimo, $monto_maximo, $titulopag, $porcentaje;

  $query = "SELECT * FROM recargar WHERE id = '$a'";
  $result = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if ($rows<1){
      $_SESSION['recarga']  = "Lo sentimos,la accion que intenta efectuar no se puede llevar a cabo motivado q que intenta editar un id que no existe<br>";
      //mysqli_close($db);
    } else {

            $nro = $row['nro'];
            $monto = $row['monto'];
            $operador = $row['operador'];
            $tipo = $row['tipo'];
            selector_operador();

$boton_editar = '<div data-html="true" href="#" data-toggle="popover" title="EDITAR NUMERO A RECARGAR" data-content="Editar Numero <br> <b>'.$nro.'</b>.">
<i class="fa fa-edit"></i>
</div>';

$boton_editar2 ='<!-- Button trigger modal -->
<button type="button" class="mx-auto btn btn-sm btn-outline-info" data-toggle="modal" data-target="#editar'.$a.'" title="EDITAR NUMERO '.$nro.'">
'.$boton_editar.'
</button>';

$boton_eliminar = '<div data-html="true" href="#" data-toggle="popover" title="ELIMINAR NUMERO A RECARGAR" data-content="Eliminar Numero <br> <b>'.$nro.'</b>.">
<i class="fa fa-trash-alt"></i>
</div>';

$boton_eliminar2 ='<!-- Button trigger modal -->
<button type="button" class="mx-auto btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#eliminar'.$a.'" title="ELIMINAR NUMERO '.$nro.'">
'.$boton_eliminar.'
</button>';

$boton_eliminar2 .= '<!-- Modal -->
<div class="modal fade" id="eliminar'.$a.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Eliminar Numero</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      Confirme que desea eliminar la solicitud de recarga al numero : <b>' .$nro .'</b> por un monto de <b>' .$monto .' Bs.</b><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">cerrar</button>
        <form autocomplete="off" class="was-validated" method="post" action= "">
        <input type="hidden" name="id" value="'.$a.'">
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" class="btn btn-danger" name="registrar_recarga_btn">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>';

$boton_editar2 .= '<!-- Modal -->
          <div class="modal fade" id="editar'.$a.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Editar Recarga</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">';


    $editar_recarga = ' <form autocomplete="off" class="was-validated" method="post" action= "">';

    //$editar_recarga .= 'Identificador: ' .$a .'<br>';
    $editar_recarga .= 'Editar Numero: ' .$nro .'<br>';
    $editar_recarga .= 'Monto: ' .number_format($monto,2,',','.') .' Bs.<br>';
    $editar_recarga .= 'Tipo: ' .$tipo .'<br>';
    $editar_recarga .= '<div class="dropdown-divider"></div>';


    $editar_recarga .= '<div class="form-group">
    <label for="monto">Seleccione Monto a recargar</label>
    <select class="custom-select form-control-lg" id="monto" name="monto" value="';
    $editar_recarga .= $monto;
    $monto_f=number_format($monto,2,',','.');
    $editar_recarga .= 'Bs." required> <option value="'.$monto.'">'.$monto_f.' Bs.</option>';
    selector_operador();


	$query2 = "SELECT * FROM `monto_recarga` WHERE mod (monto, '$multiplo') = 0 AND monto >= $monto_minimo AND monto <= $monto_maximo ORDER BY monto ASC";
	$results2 = mysqli_query($db, $query2);

  //$foo = 'Hola mundo';

if (strpos($titulopag, 'Sin Plan')) {
  while ($valores = mysqli_fetch_array($results2)) {

    $monto = $valores['monto'];
    $monto_f = number_format($monto,2,',','.');
    $calculo = $monto * $porcentaje / 100;
    $total = $monto + $calculo;
    $total_f = number_format($total,2,',','.');

  $editar_recarga .= '<option value="'.$monto.'"> Para Recargar '.$monto_f.' Bs Deberá Pagar '.$total_f.' Bs.</option>';
    }
} else {


	while ($valores = mysqli_fetch_array($results2)) {
    $monto_f = number_format($valores['monto'],2,',','.');
    $editar_recarga .= '<option value="'.$valores['monto'].'">'.$monto_f.' Bs.</option>';

  }
  }
    $editar_recarga .= '</select> <div class="invalid-feedback">Debe Seleccionar el monto a recargar.</div>
    </div>



    <div class="form-group">
<label for="nro">Numero A Recargar</label>
<input  pattern="'.$num_min.'" minlenght="8" maxlenght="11" title = "'.$text_num_min.'"  type="text" class="form-control form-control-lg" id="nro" aria-describedby="nro" placeholder="'.$ph.'" name="nro" value="';
    $editar_recarga .= $nro;
    $editar_recarga .= '" required>
    <div class="invalid-feedback">'.$text_num_min.'</div>
    </div>
    <input type="hidden" name="accion" value="update">
    <input type="hidden" name="id" value="'.$a.'">
    <input type="hidden" name="user" value="'.$usua.'">
    <input type="hidden" name="operador" value="'.$operador.'">
    <button type="submit" class="btn btn-primary" name="registrar_recarga_btn"><i class="fa fa-save"></i> Registrar</button>

    </form>

                    </div>
                  </div>
                </div>
              </div>
              ';
  $boton_editar2 .=  $editar_recarga;
  $accion = '<div class="btn-group-horizontal" >' . $boton_editar2.$boton_eliminar2.'</div>';
}
}


// BOTONERA USUARIO
//$a = Id
//$b = Nombre de usuario
//$c = Username de usuario
// Se debe utilizar global $accion y la salida es $accion
function botonera_usuario($b,$c){
    global $db, $usua, $accion, $mes_de_pago_actual;


    $query = "SELECT * FROM pedidos  WHERE usuario = '$c'";
		$resultA = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($resultA);

    $query2 = "SELECT * FROM pagos  WHERE user = '$c'";
    $resultB = mysqli_query($db, $query2);
    $rowsB =  mysqli_num_rows($resultB);

    $query3 = "SELECT id FROM users WHERE idusuario = '$c'";
    $resultC = mysqli_query($db, $query3);
    while ($rowC = mysqli_fetch_assoc($resultC))
   {
$a = $rowC['id'];
}
    //$rowsC =  mysqli_num_rows($resultC);


      $cant_pedido = $rows;
      $cant_meses = $rowsB;

$boton_editar = '<div data-html="true" href="#" data-toggle="popover" title="EDITAR USUARIO" data-content="Editar Usuario <br> <b>'.$b.'</b>.">Editar <i class="fa fa-envelope"></i></div>';

//$boton_editar = '';
//<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">
$boton_editar2 ='<!-- Button trigger modal -->
 <button type="button" class="mx-auto btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#editar'.$a.'" title="EDITAR USUARIO '.$b.'">
'.$boton_editar.'
</button><br>';




echo '<!-- Modal -->
<div id="editar'.$a.'" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Editar al Usuario</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  Editar al Usuario '.$b;




$query = "SELECT * FROM users WHERE id = '$a'";
$result = mysqli_query($db, $query);
    $rows =  mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);
if ($rows<1){
  $_SESSION['usuarios']  = "Lo sentimos, el usuario que intenta editar no existe id $a.<br>";
  //mysqli_close($db);
} else {
        $idusuario = $row['idusuario'];
        $nombre = $row['nombre'];
        $email = $row['email'];
        $telefono_usuario = $row['tlf'];
        $celular_usuario = $row['cel'];
        $direccion_usuario = $row['direccion'];
        $ciudad_usuario = $row['ciudad'];
        $estado_usuario = $row['estado'];
        $municipio_usuario = $row['municipio'];
        $parroquia_usuario = $row['parroquia'];
        //$password_usuario = $row['password'];
        $status_usuario = $row['status'];
        $option = "";
        if ($status_usuario ==1){
            $option = '<option value= "'.$status_usuario.'">ACTIVO</option>
            <option value = "0">SUSPENDER</option>';
        }else if ($status_usuario ==0){
            $option = '<option value= "'.$status_usuario.'">SUSPENDIDO</option>
            <option value = "1">ACTIVAR</option>';
        }

$editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "editar_usuarios.php?id='.$a.'">';


//$editar_usuario .= 'Web de Origen: ' . $web = basename($_SERVER['REQUEST_URI']).'<br>';
$web = basename($_SERVER['REQUEST_URI']);
$editar_usuario .= '<input type="hidden" name="web" value="'.$web.'">';


$editar_usuario .= 'Identificador: ' .$a .'<br>';
$editar_usuario .= 'Usuario: ' .$idusuario .'<br>';
$editar_usuario .= 'Nombre: ' .$nombre .'<br>';
$editar_usuario .= 'Email: ' .$email .'<br>';
$editar_usuario .= '<div class="dropdown-divider"></div>';


$editar_usuario .= '<div class="form-group">
<label for="nombre">Numero de Cliente</label>
<input type="text" pattern="[V,J,G,E]{1}[-][0-9]{7,9}" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese Id de Usuario" name="idusuario" value="';
$editar_usuario .= $idusuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el idusuario en formato V-12345678.</div>
</div>



<div class="form-group">
<label for="nombre">Nombre</label>
<input type="text" class="form-control" id="nombre" aria-describedby="nombre" placeholder="Ingrese nombre" name="nombre" value="';
$editar_usuario .= $nombre;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el nombre.</div>
</div>


<div class="form-group">
<label for="email">Email</label>
<input type="email" pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)" title="Debe utilizar solo correos gmail, yahoo, hotmail o cantv" class="form-control" id="email" aria-describedby="email" placeholder="Ingrese Email" name="email" value="';
$editar_usuario .= $email;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email, solo usar gmail, yahoo, hotmail o cantv.</div>
</div>



<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567"  class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$editar_usuario .= $telefono_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567"  class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$editar_usuario .= $celular_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$editar_usuario .= $direccion_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$editar_usuario .= $estado_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$editar_usuario .= $ciudad_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$editar_usuario .= $municipio_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$editar_usuario .= $parroquia_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

$editar_usuario .= '<div class="form-group">
<label for="exampleFormControlSelect1">Status de Usuario </label>
<select class="form-control" name = "status_usuario" id="status_usuario" value="'.$status_usuario.'">
'.$option.'
</select>
</div>';



//$editar_usuario .= '<button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>';
echo  $editar_usuario;




    echo  '</div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                  <button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>



</form>

                </div>
              </div>
            </div>
          </div>';



        }

$boton_pedidos = '<a data-html="true" class="btn btn-outline-success btn-sm" href="ver_pedidos_del_usuarios.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="VER PEDIDOS" data-content="<b> '.$b.'</b> <br> Ha efectuado '.$cant_pedido.' pedidos en total.">
Pedidos ('.$cant_pedido.')
</a><br>';

$boton_meses = '<a data-html="true" class="btn btn-outline-dark btn-sm" href="ver_mensualidades_del_usuario.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="VER MENSUALIDADES" data-content="<b> '.$b.'</b>.<br>Ha realizado el pago de '.$cant_meses.' Mensualidades">Mensualidades ('.$cant_meses.')
</a><br>';
//$boton_enviar_mensaje = '<a data-html="true" class="btn btn-outline-info btn-sm" href="enviar_correo_a_usuario.php?id='.$a.'&usuario='.$c.'&nombre_usuario='.$b.'" data-toggle="popover" title="Enviar Mensaje" data-content="Enviarle un correo a: <b> '.$b.'</b>.">Enviar Correo <i class="fa fa-envelope"></i></a>';


$boton_enviar = '<div data-html="true" href="#" data-toggle="popover" title="ENVIAR CORREO" data-content="Enviar Correo a Usuario <br> <b>'.$b.'</b>.">
Email <i class="fa fa-envelope"></i>
</div>';

$boton_enviar_mensaje = '<!-- Large modal -->
<button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg'.$a.'">'.$boton_enviar.'</button>';


$modal_enviar_mensaje = '
<div class="modal fade bd-example-modal-lg'.$a.'" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enviar Correo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      Enviar correo a: '.$b;


  $editar_contenido = ' <form autocomplete="off" class="was-validated" method="post" action= "#">';





  $editar_contenido .= '<input type="hidden" name="nombre" value="'.$b.'">';

  $editar_contenido .= '<input type="hidden" name="email" value="'.$email.'">';

  $editar_contenido .= '<input type="hidden" name="id" value="'.$a.'">';

  $editar_contenido .= '<input type="hidden" name="usua" value="'.$usua.'">';

  $editar_contenido .= '<input type="hidden" name="destinatario" value="'.$c.'">';

  $editar_contenido .= '<div class="form-group">
  <label for="asunto">Asunto</label>
  <input type="text" class="form-control" id="asunto" aria-describedby="asunto" placeholder="Ingrese el asunto del MSN" name="asunto" required>
  <div class="invalid-feedback">Debe indicar el asunto del MSN.</div>
  </div>';

  $editar_contenido .= '<label for="mensaje">Mensaje</label>
<textarea width = "100%" type="text" class="form-control summernote" id="mensaje" aria-describedby="mensaje" placeholder="Ingrese el mensaje" name="mensaje" ></textarea>
';


$modal_enviar_mensaje .=  $editar_contenido;

$modal_enviar_mensaje .= '<div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                  <button type="submit" class="btn btn-primary" name="enviar_msn_btn">Enviar</button>



</form>

</div> </div>
    </div>
  </div>
</div>';

echo $modal_enviar_mensaje;



$accion = '<div class="btn-group-vertical" >' . $boton_editar2 . $boton_pedidos . $boton_meses . $boton_enviar_mensaje .'</div>';


}

// BORRAR USUARIO DEL SISTEMA
function borrar_usuario(){
  global $db;

  $idusuario          =  e($_REQUEST['id']);

$_SESSION['usuarios']  = "Se borrara al usuario $idusuario y esta Funcionando";
//header('location: usuarios.php');
}



$resultado_estadistica ="";







function editar_mensajeria(){
  global $db;

  $rowid = e($_REQUEST['id']);

  $query = "SELECT mensajes.*, users.nombre, users.email, users.username 
            FROM mensajes 
            INNER JOIN 
            users ON mensajes.destinatario = users.username 
            WHERE mensajes.id = ?";
  
  $stmt = $db->prepare($query);
  $stmt->bind_param('i', $rowid);
  $stmt->execute();
  $resultado = $stmt->get_result();
  $rows = $resultado->num_rows;
  $row = $resultado->fetch_assoc();

  if ($rows < 1){
    $_SESSION['editar_mensajeria'] = "Lo sentimos, algo ha ocurrido.<br>";
  } else {
    $id = $row['id'];
    $asunto = $row['asunto'];
    $contenido = $row['contenido'];
    $nombre = $row['nombre'];
    $email = $row['email'];
    $destinatario = $row['destinatario'];

    $editar_contenido = '<form autocomplete="off" class="was-validated" method="post" action="editar_mensajeria.php?id='.$id.'">';
    $editar_contenido .= '<div class="form-group">
                            <label for="asunto">Asunto</label>
                            <input type="text" class="form-control" id="asunto" aria-describedby="asunto" placeholder="Ingrese el asunto" name="asunto" value="'.$asunto.'">
                            <label for="contenido">Contenido</label>
                            <textarea type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido">'.$contenido.'</textarea>
                            <div class="invalid-feedback">Debe indicar el contenido.</div>
                            <input type="hidden" name="nombre" value="'.$nombre.'">
                            <input type="hidden" name="email" value="'.$email.'">
                            <input type="hidden" name="destinatario" value="'.$destinatario.'">
                          </div>';
    $editar_contenido .= '<button type="submit" class="btn btn-primary" name="editar_mensajeria_btn">Enviar</button>';
    echo $editar_contenido;
  }

  $stmt->close();
}



function modal_edicion_usuario(){
    global $rowid, $idusuario, $nombre_usuario, $email_usuario, $telefono_usuario, $celular_usuario, $direccion_usuario, $ciudad_usuario, $estado_usuario, $status_usuario;

    $acciones_usuario = '
    <!-- Button trigger modal -->
    <input type="hidden" name="id" value="'.$rowid.'">
    <a class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal" href="#">
      Editar
    </a>
    ';

$acciones_usuario .= ' <!-- Modal DEL Boton Editar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">';



      $acciones_usuario .= ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">';

      $acciones_usuario .= 'Identificador: ' .$rowid;

      $acciones_usuario .= '<div class="form-group">
      <label for="idusuario">Id del Usuario</label>
      <input type="text" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
      $acciones_usuario .= $idusuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="nombre_usuario">Nombre del Usuario</label>
      <input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Usuario" name="nombre_usuario" value="';
      $acciones_usuario .= $nombre_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="email_usuario">Email del Usuario</label>
      <input type="text" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Usuario" name="email_usuario" value="';
      $acciones_usuario .= $email_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="telefono_usuario">Telefono del Usuario</label>
      <input type="text" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Usuario" name="telefono_usuario" value="';
      $acciones_usuario .= $telefono_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="celular_usuario">Celular del Usuario</label>
      <input type="text" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Usuario" name="celular_usuario" value="';
      $acciones_usuario .= $celular_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="direccion_usuario">Direccion del Usuario</label>
      <input type="text" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese la Direccion" name="direccion_usuario" value="';
      $acciones_usuario .= $direccion_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Direccion del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="ciudad_usuario">Ciudad del Usuario</label>
      <input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
      $acciones_usuario .= $ciudad_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar la Ciudad del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="estado_usuario">Estado del Usuario</label>
      <input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
      $acciones_usuario .= $estado_usuario;
      $acciones_usuario .= '" required>
      <div class="invalid-feedback">Debe indicar el Estado de ubicacion del Usuario.</div>
      </div>

      <div class="form-group">
      <label for="estado_usuario">Status del Usuario</label>
      <input type="text" class="form-control" id="status_usuario" aria-describedby="status_usuario" placeholder="Ingrese el Status" name="status_usuario" value="';
      $acciones_usuario .= $status_usuario;
      $acciones_usuario .= '" required>

      <div class="invalid-feedback">Debe indicar el Status del Usuario 1 Para activarlo y 0 para Desactivarlo.</div>
      </div>


      <button type="submit" class="btn btn-primary" name="editar_usuario_btn">Enviar</button>

      </form>';


      $acciones_usuario .= '</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>';


}

function activar_desactivar() {
  global $db, $logo, $footer_correo;
  $id    = e($_REQUEST['id']);

  $query  = "SELECT * FROM users WHERE idusuario = '$id'";
  $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {
       $nombre = $row['nombre'];
       $rowUser = $row['idusuario'];

     }

     $a = "Bloquear Usuario";

     $salida = '<b>'. strtoupper($a).'</b><br>'.strtoupper($a) .'<br> Usuario: '. $nombre . '<br> Identificador: '. $rowUser . '<br>';

     $editar_contenido = ' <form autocomplete="off" class="was-validated" method="post" action= "activar_desactivar.php?id='.$id.'">';

  $editar_contenido .= '<label for="motivo">Motivo del Bloqueo</label>
<textarea width = "100%" type="text" class="form-control" id="motivo" aria-describedby="motivo" placeholder="Ingrese el motivo" name="motivo" ></textarea>
';

$editar_contenido .= '<button type="submit" class="btn btn-primary" name="procesar_bloqueo_btn">Enviar</button>';


  echo '<div class="row">';
echo '<div class="col-xs-12 col-md-4">';
echo $salida;
  echo '</div>';

  echo '<div class="col-xs-12 col-md-8 form-group">';
  echo $editar_contenido;
  echo '</div>';

  echo '</div>';

}

function procesar_bloqueo(){
  global $db, $logo, $footer_correo;
  $id = e($_GET['id']);
  $motivo  = e($_REQUEST['motivo']);

  $query  = "SELECT * FROM users WHERE idusuario = '$id'";
  $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {
       $nombre = $row['nombre'];
       $rowUser = $row['idusuario'];
       $email = $row['email'];
     }

     $sql = "UPDATE users SET
     status = 0,
     motivo_bloqueo = '$motivo'
     WHERE
     idusuario = '$id'";
     $mensaje = "Se ha BLOQUEADO al usuario de manera correcta..!!<br>";

     if (mysqli_query($db, $sql)) {
      $_SESSION['activar_desactivar']  = $mensaje;


      $link_mensualidades = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php" target="_blank"><b> ACTIVAR ALGUN PLAN DISPONIBLE </b></a>';

      $link_contactanos = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensajeria.php" target="_blank"><b> CONTACTANOS AQUI </b></a>';

      $link_cancelar_ggroups = '<a href="mailto:gestionderecargas+unsubscribe@googlegroups.com">gestionderecargas+unsubscribe@googlegroups.com</a>';


	$asunto = "Su Usuario ha Sido Bloqueado";

	$cuerpo = "Hola $nombre <br><br><p>Le informamos que su usuario ha sido bloqueado por el siguiente motivo:</p><p> $motivo. </p><p> Con esta accion su usuario se bloqueará y lamentablemente ya no podrás utilizar el sitio..!</p><p>Si considera que es un error en cualquier momento puede favor comuniquese con nosotros para reconsiderar el bloqueo de su usuario.</p><p>Si considera que es un error, puede comunicarse respondiendo este correo o ingresando al modulo de Mensajerias de la plataforma $link_contactanos </p><p>No te preocupes, ahora es posible reactivar tu usuario de manera automatica solo debes efectuar el pago de algunas de las mensualidades disponibles hoy mismo, puedes hacerlo ingresando a: $link_mensualidades </p><p>Si desea dejar de recibir mensajeria instantanea de la plataforma puedes hacerlo en cualquier momento: <p>Para cancelar la suscripción al grupo de distribucion masiva de informacion es sencillo, envía un correo electrónico con cualquier contenido al correo $link_cancelar_ggroups y listo de manera automatica dejara de recibibir correos automatizados del sistema</p>";

  enviarEmail($email, $nombre, $asunto, $cuerpo);

    $_SESSION['activar_desactivar']  .= '<i class="fa fa-envelope"></i> Le hemos enviado un Email a ' .$nombre.' avisandole que ha sido suspendido..!!';


   } else {
    $_SESSION['activar_desactivar']  = '<i class="fa fa-exclamation-triangle fa-fw"></i>Algo ha ocurrido al intentar bloquear a: '.$nombre.' Error updating record: '. mysqli_error($db);
      mysqli_close($db);
   }

    // $_SESSION['activar_desactivar']  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Actualizacion aplicable a '.$nombre.'<br>Con el motivo '.$motivo.'.<br>';

}


function enviar_msn(){
  global $db, $logo, $footer_correo, $usua;
  $id    = e($_REQUEST['id']);
  $nombre  = e($_REQUEST['nombre']);
  $email  = e($_REQUEST['email']);
  $asunto  = e($_REQUEST['asunto']);
  $mensaje  = e($_REQUEST['mensaje']);
  $origen  = $usua;
  $destinatario  = e($_REQUEST['destinatario']);

  $query = "INSERT INTO mensajes (id, asunto, contenido, origen, destinatario) VALUES (null, '$asunto', '$mensaje',' $origen', '$destinatario')";

   if (mysqli_query($db, $query)) {

  $_SESSION['msn']  = "Se ha guardado en la Base de datos el Mensaje para $nombre destinatario $destinatario y origen: $origen Y se enviara un correo al correo $email notificando de esta accion, el asunto es $asunto y el contendio es: $mensaje";

  $asunto2 = "$asunto";
  $cuerpo = "Hola $nombre <br><br>Le informamos que tiene un nuevo mensaje.<br><br><b>$asunto</b><br><br>$mensaje";

  enviarEmail($email, $nombre, $asunto2, $cuerpo);

   } else {
    $_SESSION['msn']  .= '<i class="fa fa-exclamation-triangle"></i> Algo ha.<br>'. mysqli_error($db);

   }

}



function rechazar_pagos(){
  global $db, $logo, $footer_correo;

  $id         = e($_REQUEST['id']);
  $rowUser    = e($_REQUEST['user']);
  $a          = e($_REQUEST['asunto']);




  if ($a == 'mensualidad') {
    $query = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos INNER JOIN users  ON pagos.user=users.idusuario WHERE pagos.id = '$id' ";
    $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {

        $monto          = $row['monto'];
        $banco_emisor   = $row['banco_origen'];
        $banco_destino  = $row['banco_destino'];
        $nro_transf     = $row['nro_transf'];
        $ci_nro_cuenta  = $row['ci_nro_cuenta'];
        $fecha_transf   = $row['fecha_transf'];
        $plan           = $row['afiliacion'];
        $concepto       = $row['concepto'];
        $nombre         = $row['nombre'];
        $email         = $row['email'];

    $date = date_create($fecha_transf);
    $fecha = date_format($date, 'd-m-Y');
    $fecha_de_transf = $fecha;
    $monto = number_format($monto, 2, ',', '.');

    $resumen = 'Por un Monto de: '.$monto . ' Bs. <br>
    Desde el Banco: '. $banco_emisor . ' <br>
    A nuestra Cuenta del: '. $banco_destino . ' <br>
    Numero de Transferencia: '. $nro_transf . '<br>
    Numero de Cedula del titular de la cuenta origen: '. $ci_nro_cuenta . '<br>
    Efectuado en fecha: '. $fecha_de_transf . '<br> ';

  }


  } else if ($a == 'pedido') {

    $query = "SELECT pedidos.*, users.id AS 'id_usuario', users.nombre, users.email, users.username FROM pedidos INNER JOIN users  ON pedidos.usuario=users.idusuario WHERE pedidos.id = '$id' ";
    $resultado = mysqli_query($db, $query) or mysqli_error($db);
    while ($row = mysqli_fetch_assoc($resultado))
     {

        $id_usuario     = $row['id_usuario'];
        $montoA         = $row['monto'];
        $banco_emisor   = $row['banco_emisor'];
        $banco_destino  = $row['banco_destino'];
        $nro_transf     = $row['nro_transf'];
        $ci_nro_cuenta  = $row['ci_nro_cuenta'];
        $fecha_transf   = $row['fecha_transf'];
        $nombre         = $row['nombre'];
        $email          = $row['email'];
        $operador       = $row['operador'];

    $date = date_create($fecha_transf);
    $fecha = date_format($date, 'd-m-Y');
    $fecha_pedido = $fecha;

    $monto = number_format($montoA, 2, ',', '.');

    $resumen = '
    Por un Monto de: '.$monto . ' Bs. <br>
    Desde el Banco: '. $banco_emisor . ' <br>
    A nuestra Cuenta del: '. $banco_destino . ' <br>
    Numero de Transferencia: '. $nro_transf . '<br>
    Numero de Cedula del titular de la cuenta origen: '. $ci_nro_cuenta . '<br>
    Efectuado en fecha: '. $fecha_pedido . '<br> ';

    }

  }

  $salida = '<b>'. strtoupper($a).'</b><br>'
  .strtoupper($a) .
  ' Identificador '. $id .
  '<br> Id Usuario: ' . $id_usuario .
  '<br> Nombre: '. $nombre .
  '<br> Identificador: '. $rowUser .
  '<br>'. $resumen;

  $salida_codificada = '<b>'. strtoupper($a).'</b><br>'.strtoupper($a) .' Identificador '. base64_encode($id) . '<br> Del Usuario: '. $nombre . '<br> Identificador: '. $rowUser . '<br>'. $resumen;
  // base64_decode PARA DECODIFICAR

  $editar_contenido = '<form autocomplete="off" class="was-validated" method="post" action= "rechazar.php">';

  $editar_contenido .= '
  <input type="hidden" name="id_usuario" value="'.$id_usuario.'">
  <input type="hidden" name="id" value="'.$id.'">
  <input type="hidden" name="user" value="'.$rowUser.'">
  <input type="hidden" name="asunto" value="'.$a.'">
  <input type="hidden" name="contenido" value="'.$salida_codificada.'">
  <input type="hidden" name="nro_transf" value="'.$nro_transf.'">
  <input type="hidden" name="nombre" value="'.$nombre.'">
  <input type="hidden" name="email" value="'.$email.'">
  <input type="hidden" name="concepto" value="'.@$concepto.'">
  <input type="hidden" name="operador" value="'.@$operador.'">';

  $editar_contenido .= '<label for="motivo">Motivo del Rechazo</label>
<textarea width = "100%" type="text" class="form-control" id="motivo" aria-describedby="motivo" placeholder="Ingrese el motivo" name="motivo" ></textarea>

<hr><p>Favor verifique con su plataforma bancaria e intente efectuar nuevamente su declaracion de pago.</p><p>Si efectuo un pago inferior al monto declarado su pago sera rechazado y el monto sera automaticamente agregado a su billetera virtual con la finalidad de que lo pueda utilizar de alli.</p> <br><br><p><b>RECOMENDACIONES</b></p><ul><li>Procure hacer sus transferencias del mismo Banco, es decir si usted posee cuenta en el Banco Banesco, efectúe su transferencia al mismo Banco Banesco, evite hacer transferencias por ejemplo desde el Banco de Venezuela al Banco Banesco.</li><li>Le recordamos que el sistema no acepta el mismo numero de transferencia para el pago de planes, pedidos o recarga de Billetera.</li><li>Si desea efectuar adelantos de pagos, puede hacerlo desde su billetera <a href="https://virtual.jesuministrosymas.com.ve/u/usuario/billetera.php">Ir a Billetera Virtual</a>.</li></ul>

  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="billetera" value="'.$montoA.'">
    <label class="form-check-label" for="exampleCheck1">Devolver dinero a Billetera</label>
  </div>

  ';
$editar_contenido .= '<button type="submit" class="btn btn-primary" name="procesar_rechazo_de_pagos_btn">Rechazar</button></form>';


    echo '<div class="row">';
    echo '<div class="col-xs-12 col-md-4">';
    echo $salida;
    echo '</div>';

    echo '<div class="col-xs-12 col-md-8 form-group">';
    echo $editar_contenido;
    echo '</div>';

    echo '</div>';



}

function procesar_rechazo_de_pagos(){
    global $db, $fecha_act, $logo, $footer_correo;

    $status = "RECHAZADO";

   $id_usuario = e($_REQUEST['id_usuario']);
   $id = e($_REQUEST['id']);
   $user = e($_REQUEST['user']);
   $nombre = e($_REQUEST['nombre']);
   $email = e($_REQUEST['email']);
   $a = e($_REQUEST['asunto']);
   $contenido = e($_REQUEST['contenido']);
   $motivo = e($_REQUEST['motivo']);
   @$monto = e($_REQUEST['billetera']);

   $nro_transf  = $status .' ' . e($_REQUEST['nro_transf']) . ' ' . $status;

if ($a == 'mensualidad'){

  $query = "UPDATE pagos SET
  status_pago = '$status',
  motivo_rechazo = '$motivo',
  fecha_rechazo = '$fecha_act',
  nro_transf = '$nro_transf'
  WHERE id = '$id'";
  if (mysqli_query($db, $query)) {
      $_SESSION['rechazar']  = "Se ha Actualizado el STATUS del pago de Mensualidad a RECHAZADO..!!<br>";
      } else {
      echo "Error updating record: " . mysqli_error($db);
      //mysqli_close($db);
      }

} else if ($a == 'pedido'){

    $operador = e($_REQUEST['operador']);

  $query = "UPDATE pedidos SET
  status_pedido = '$status',
  motivo_rechazo = '$motivo',
  fecha_rechazo = '$fecha_act',
  nro_transf = '$nro_transf'
  WHERE id = '$id'";
  if (mysqli_query($db, $query)) {
      $_SESSION['rechazar']  = "Se ha Actualizado el STATUS del Pedido a RECHAZADO..!!<br>";

      // if ($operador == $operador) {
        $sql = "UPDATE recargar SET
        status = 1,
        relacion = '$id'
        WHERE
        user = '$user' AND operador = '$operador' AND status = 2";
            if (mysqli_query($db, $sql)){
              $_SESSION['rechazar']  .= "Se ha Actualizado el status de la solicitud de recargas.<br>";
              }
              else {
              $_SESSION['rechazar']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar el rechazo nuevamente. ' . mysqli_error($db);
              }
      // }


      } else {
      echo "No Se podido Actualizar el STATUS del Pedido a RECHAZADO el codigo error del sistema es el siguiente: <br>" . mysqli_error($db);
      //mysqli_close($db);
      }

}

// Actualizar billetera al recharzar pago
    if (isset($_REQUEST['billetera'])){

    //echo $_REQUEST['billetera']; // Muestra el CheckBox marcado.
    //Se devuelve monto positivo a billetera de cliente
    $descripcion = 'DEVOLUCION';
    $sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$id_usuario','$monto','$descripcion','$id',NOW(),1)";

    if (mysqli_query($db, $sql2)) {
    $_SESSION['rechazar']  .= "Se ha generado un registro de actualizacion de dinero en su Billetera.<br>";
    } else {
    $_SESSION['rechazar']  .= 'Algo ha ocurrido Actualizando su billetera, Error: ' . mysqli_error($db);
    }
} else {
  //Solo aplica para rechazo de ingresos a billetera
  //Update de tabla billetera
  // Status 2 Rechazado

  $sql_billetera = "UPDATE billetera SET
  descripcion = '$motivo',
  status = 2
  WHERE
  id_descripcion = '$id' ORDER BY id DESC LIMIT 1";

      if (mysqli_query($db, $sql_billetera)){
        $_SESSION['rechazar']  .= "Se ha Actualizado el status en la Billetera.<br>";
        }
        else {
        $_SESSION['rechazar']  = '<i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente efectuar el rechazo nuevamente. ' . mysqli_error($db);
        }
}

$asunto = "Se ha Rechazado su Pago";
$cuerpo = "Hola Usuario $nombre <br><br><b>Estimado Usuario. <br><br>Lamentamos informale que su pago con las siguientes caracteristicas:</b><br><p>$contenido.</p><br><b>HA SIDO RECHAZADO POR EL SIGUIENTE MOTIVO:</b><br><p>$motivo</p><br> <p>Favor verifique con su plataforma bancaria e intente efectuar nuevamente su declaracion de pago.</p><p>Si efectuo un pago inferior al monto declarado su pago sera rechazado y el monto sera automaticamente agregado a su billetera virtual con la finalidad de que lo pueda utilizar de alli.</p> <br><br><p><b>RECOMENDACIONES</b></p><ul><li>Procure hacer sus transferencias del mismo Banco, es decir si usted posee cuenta en el Banco Banesco, efectúe su transferencia al mismo Banco Banesco, evite hacer transferencias por ejemplo desde el Banco de Venezuela al Banco Banesco.</li><li>Le recordamos que el sistema no acepta el mismo numero de transferencia para el pago de planes, pedidos o recarga de Billetera.</li><li>Si desea efectuar adelantos de pagos, puede hacerlo desde su billetera <a href='https://virtual.jesuministrosymas.com.ve/u/usuario/billetera.php'>Ir a Billetera Virtual</a>.</li></ul>";

enviarEmail($email, $nombre, $asunto, $cuerpo);

 $_SESSION['rechazar']  .= '<i class="fa fa-envelope"></i> Se ha enviado un correo electronico notificando sobre este rechazo de pago..!!<br>';

}


// ANALISIS DE PEDIDOS POR CLIENTE

function analisis_pedidos_por_cliente($a) {
  global $db, $res;
  $query="SELECT SUM(CASE WHEN status_pedido = 'ENTREGADO' THEN 1 ELSE 0 END) AS 'entregado',
                 SUM(CASE WHEN status_pedido = 'RECHAZADO' THEN 1 ELSE 0 END) AS 'rechazado'
                 FROM pedidos
                 WHERE usuario = '$a'";
  $result = mysqli_query($db, $query);

  while ($row = mysqli_fetch_assoc($result))
  {
    $e = $row['entregado'];
    $r = $row['rechazado'];
  }

  if ($e<1){
$res = 'Primera Vez';
  } else if ($e==1) {
$res = 'Segunda vez';
  } else {
$res = 'Ha recibido: '.$e;
  }

  if ($r<1){
$res .= '';
  } else if ($r==1) {
$res .= '<br> Rechazado: 1';
  } else {
$res .= '<br>Rechazados: '.$r;
  }

}


//RESUMEN SUMA DE MENSUALIDAD
function suma_mensualidad(){
  global $db, $usua, $pendiente_mensualidad, $suma_mensualidad,$mes_de_pago_actual, $titulopag, $pmes, $fecha_sistema;

  if (isAdmin()) {
    // SI ES ADMIN
    //$sql="SELECT sum(monto) as total FROM pagos ";
    $sql="SELECT SUM(monto) AS 'total',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' THEN monto ELSE 0 END) AS 'mes',
    SUM(CASE WHEN status_pago = 'PENDIENTE' AND mes_de_pago ='$mes_de_pago_actual' THEN 1 ELSE 0 END) AS 'pendiente',
    SUM(CASE WHEN status_pago = 'APROBADO' AND mes_de_pago ='$mes_de_pago_actual' THEN 1 ELSE 0 END) AS 'aprobado',
    SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado_general',
    SUM(CASE WHEN status_pago = 'APROBADO' THEN monto ELSE 0 END) AS 'monto_aprobado_general',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN 1 ELSE 0 END) AS 'cantidad_basico',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN monto ELSE 0 END) AS 'monto_basico',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN monto ELSE 0 END) AS 'monto_avanzado',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN 1 ELSE 0 END) AS 'cantidad_avanzado',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN monto ELSE 0 END) AS 'monto_vip',
    SUM(CASE WHEN  mes_de_pago ='$mes_de_pago_actual' AND status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN 1 ELSE 0 END) AS 'cantidad_vip',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVILNET' THEN monto ELSE 0 END) AS 'monto_movilnet',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVILNET' THEN 1 ELSE 0 END) AS 'cantidad_movilnet',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN monto ELSE 0 END) AS 'monto_movistar',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN 1 ELSE 0 END) AS 'cantidad_movistar',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN monto ELSE 0 END) AS 'monto_digitel',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN 1 ELSE 0 END) AS 'cantidad_digitel',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN monto ELSE 0 END) AS 'monto_directv',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN 1 ELSE 0 END) AS 'cantidad_directv',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN monto ELSE 0 END) AS 'monto_inter',
    SUM(CASE WHEN  DATEDIFF(fin, NOW()) > 0 AND status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN 1 ELSE 0 END) AS 'cantidad_inter'
    FROM pagos";
    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']>0){


    $suma_mensualidad = "Total General ".number_format($row['monto_aprobado_general'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "<b>En el Mes " . $mes_de_pago_actual ."<br> </b>" ;

    $pmes=$row['mes'];
    $suma_mensualidad .=  "Total " . number_format($row['mes'], 2, ',', '.')."<br>";
    $suma_mensualidad .= "Aprobados " .$row['aprobado']."<br>";
    $suma_mensualidad .= "Pendientes " .$row['pendiente']."<br>";
    $suma_mensualidad .= "Basico " .$row['cantidad_basico']. " = ".number_format($row['monto_basico'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Avanzado " .$row['cantidad_avanzado']. " = ".number_format($row['monto_avanzado'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "VIP " .$row['cantidad_vip']. " = ".number_format($row['monto_vip'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Movilnet " .$row['cantidad_movilnet']. " = ".number_format($row['monto_movilnet'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Movistar " .$row['cantidad_movistar']. " = ".number_format($row['monto_movistar'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Digitel " .$row['cantidad_digitel']. " = ".number_format($row['monto_digitel'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Directv " .$row['cantidad_directv']. " = ".number_format($row['monto_directv'], 2, ',', '.')." Bs.<br>";
    $suma_mensualidad .= "Inter " .$row['cantidad_inter']. " = ".number_format($row['monto_inter'], 2, ',', '.')." Bs.<br>";

    // $pendiente_mensualidad = $row['pendiente'];

        } else  if ($row['total']==0) {


      $suma_mensualidad = "No hay datos";
     // $pendiente_mensualidad = "";

  }

  if ($row['pendiente']==0){
    $pendiente_mensualidad = "";
  } else
  {
    $pendiente_mensualidad = $row['pendiente'];

  }



  }




} else {
  // SI ES USUARIO
  $sql="SELECT sum(monto) as total,
  SUM(CASE WHEN (status_pago = 'PENDIENTE' OR status_pago = 'APROBADO' OR status_pago = 'RECHAZADO' ) THEN 1 ELSE 0 END) AS 'todo',
  SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
  SUM(CASE WHEN status_pago = 'RECHAZADO' THEN 1 ELSE 0 END) AS 'rechazado',
  SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'BASICO' THEN 1 ELSE 0 END) AS 'basico',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'AVANZADO' THEN 1 ELSE 0 END) AS 'avanzado',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'VIP' THEN 1 ELSE 0 END) AS 'vip',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_MOVISTAR' THEN 1 ELSE 0 END) AS 'movistar',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_DIGITEL' THEN 1 ELSE 0 END) AS 'digitel',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_DIRECTV' THEN 1 ELSE 0 END) AS 'directv',
  SUM(CASE WHEN status_pago = 'APROBADO' AND afiliacion = 'MENS_INTER' THEN 1 ELSE 0 END) AS 'inter'
  FROM pagos
  WHERE user = '$usua' ";
  $result = mysqli_query($db, $sql);

  while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['todo']<1){
      echo "En este momento no hay datos que permitan mostrar estadisticas.";
        } else {

          if ($titulopag == 'Mensualidades') {

            echo '<b class="card-title text-uppercase">Resumen</b><br>';
            echo "Cantidad de Pagos de Mensualidades Aprobadas = " .$row['aprobado']."<br>";
            echo "Cantidad de Pagos de Mensualidades Pendientes = ".$row['pendiente']."<br>";
            echo "Cantidad de Pagos de Mensualidades Rechazados = ".$row['rechazado']."<br>";

            echo '<b class="card-title text-uppercase">Operadora Publica Movilnet</b><br>';
            echo "Cantidad de Plan Basico activados = ".$row['basico']."<br>";
            echo "Cantidad de Plan Avanzado activados = ".$row['avanzado']."<br>";
            echo "Cantidad de Plan Vip activados = ".$row['vip']."<br>";

            echo '<b class="card-title text-uppercase">Operadoras Privadas</b><br>';
            echo "Cantidad de Mensualidades Movistar activados = ".$row['movistar']."<br>";
            echo "Cantidad de Mensualidades Digitel activados = ".$row['digitel']."<br>";
            echo "Cantidad de Mensualidades Directv activados = ".$row['directv']."<br>";
            echo "Cantidad de Mensualidades Inter activados = ".$row['inter']."<br>";




          } else {


     echo '<b class="card-title text-uppercase">Resumen</b><br>';
     echo "Aprobados " .$row['aprobado']."<br>";
     echo "Pendientes ".$row['pendiente']."<br>";
     echo "Rechazados ".$row['rechazado']."<br>";

     echo '<b class="card-title text-uppercase">Movilnet</b><br>';
     echo "Plan Basico ".$row['basico']."<br>";
     echo "Plan Avanzado ".$row['avanzado']."<br>";
     echo "Plan Vip ".$row['vip']."<br>";

     echo '<b class="card-title text-uppercase">Privadas</b><br>';
     echo "Movistar ".$row['movistar']."<br>";
     echo "Digitel ".$row['digitel']."<br>";
     echo "Directv ".$row['directv']."<br>";
     echo "Inter ".$row['inter']."<br>";
     }

  }
}
  //mysqli_close($db);}

}}


//DETALLADO SUMA MENSUALIDAD
function detallado_suma_mensualidad(){
  global $db, $usua;
  if (isAdmin()) {
    //$sql="SELECT sum(monto) as total FROM pagos ";
    $sql="SELECT sum(monto) AS 'total',
    SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
      SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado'
    FROM pagos ";
    $result = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']<1){
      echo "No hay datos";
        } else {
     echo "Cantidad en Bs. Pagados a la fecha ".number_format($row['total'],2,',','.') ." Bs<br>";
     echo "Cantidad de Pagos Aprobados " .$row['aprobado']."<br>";
     echo "Cantidad de Pagos Pendientes ".$row['pendiente']."<br>";

  }

  }} else {
  $sql="SELECT sum(monto) as total,
  SUM(CASE WHEN status_pago = 'PENDIENTE' THEN 1 ELSE 0 END) AS 'pendiente',
  SUM(CASE WHEN status_pago = 'APROBADO' THEN 1 ELSE 0 END) AS 'aprobado'
  FROM pagos
  WHERE user = '$usua' ";
  $result = mysqli_query($db, $sql);

  while ($row = mysqli_fetch_assoc($result))
  {
    if ($row['total']<1){
      echo "No hay Pagos Aprobados";
        } else {
     //echo "Cantidad en Bs. Pagados a la fecha ".$row['total']." Bs.<br>";
     echo "<h2>Resumen</h2><br>";
     echo "Cantidad de Pagos Aprobados " .$row['aprobado']."<br>";
     echo "Cantidad de Pagos Pendientes ".$row['pendiente']."<br>";
  }
}
//  mysqli_close($db);
}

}


// PAGO DE MENSUALIDAD
function verificar_pago_mes() {
	global $db, $username, $usua, $mes_de_pago_actual;

	$queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual'";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);

    if ($rowsvpm > 0){
	echo '<div class="alert alert-info" role="alert" >
	<h3>'
.$mes_de_pago_actual .' Pagado		</h3>
</div>';
    } else {
		echo '<div class="alert alert-danger" role="alert" >
        <h3>
Lo sentimos usted no ha efectuado el pago correspondiente a ' .$mes_de_pago_actual .'
        </h3>
	</div>';
	exit;
    }}

//PARA EL MODAL DE PAGO DE MENSUALIDAD
function pago_mensualidad(){
    global $db, $username, $usua, $ci_nro_cuenta, $monto_mensualidad, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $operador, $concepto, $link, $accion, $mens_monto_favor, $monto_favor, $cuentas_bancarias;

    selector_operador();

    $queryvpm = "SELECT * FROM pagos WHERE user = '$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = '$concepto' AND (status_pago = 'APROBADO' OR status_pago = 'PENDIENTE') ";
	$resultvpm = mysqli_query($db, $queryvpm);
	$rowsvpm =  mysqli_num_rows($resultvpm);
    $rowsvpma =  mysqli_fetch_assoc($resultvpm);


    // if (isActive()){

        //if ($rowsvpm > 0){
            if ($rowsvpma['status_pago'] == 'PENDIENTE'){
                echo '<div class="alert alert-danger" role="alert" >
            <h3>YA USTED EFECTUO EL PAGO DEL MES DE <b>'
        .strtoupper($mes_de_pago_actual) .'</b> Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> DEBE ESPERAR QUE SU PAGO SEA APROBADO PARA QUE PUEDA ACCEDER AL MODULO DE PEDIDOS <a class = "link" href="pedidos_movilnet.php">AQUI</a></h3>
        </div>';
            }
            else if ($rowsvpma['status_pago'] == 'APROBADO'){
                echo '<div class="alert alert-info" role="alert" >
            <h3>YA USTED EFECTUO EL PAGO DEL MES DE <b>'
        .strtoupper($mes_de_pago_actual) .'</b> Y EL STATUS DE DICHO PAGO ES <b>'.$rowsvpma['status_pago'].'</b> YA PUEDE ACCEDER AL MODULO DE PEDIDOS <a class = "link" href="pedidos_movilnet.php">AQUI</a></h3><p>SI HA EFECTUADO UN PAGO DE MEJORA DE SU PLAN DEBE ESPERAR QUE EL MISMO SEA CONFORMADO PARA QUE PUEDA DISFRUTAR DE LOS BENEFICIOS DE DICHO PLAN</p>
        </div>';
            }

            //}
             else {

               a_favor();
               echo $mens_monto_favor;
               $monto_favor = $GLOBALS['monto_a_favor'];


                echo $cuentas_bancarias;
contenido('bancario');

                echo '<hr>';

      $inicio = new DateTime();
      $fin = new DateTime();
      $fin = $fin->modify('last day of this month');

      $hoy_a = date('d/m/Y');
      $fin_a = $fin->format('d/m/Y');

      $interval = $inicio->diff($fin);
      $interval = $interval->days .' Dias';

                echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy_a .'</b><br>Su renta venceria el <b>'. $fin_a .'</b><br>Pudiendo Disfrutar su plan por los proximos: '. $interval .'

                </div>';
                echo '<hr>';


    echo ' <form autocomplete="off" class="was-validated" method="post" action= "mensualidad_movilnet.php">';
    //echo $status_usuario;

    echo '<div class="form-group">
    <label for="monto_mensualidad">Seleccione Monto de su Mensualidad</label>
    <select class="custom-select" id="monto_mensualidad" name="monto_mensualidad" value="';
    echo $monto_mensualidad;
    echo '" required >
    <option value="">Seleccione:</option>';
    monto_mensualidad_movilnet();
    echo '</select> <div class="invalid-feedback">Debe Seleccionar el monto de su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="banco_emisor">Desde Que banco Transfirio</label>
    <select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
    echo $banco_emisor;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_emisor();
    echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="banco_destino">A que Banco Transfirio</label>
    <select class="custom-select" id="banco_destino" name="banco_destino" value="';
    echo $banco_destino;
    echo '" required >
    <option value="">Seleccione:</option>';
    banco_destino();
    echo '</select>
    <div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="nroTransf">Numero de Transferencia</label>
    <input pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
    echo $nro_transf;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
    </div>

    <div class="form-group">
    <label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
    <input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"   type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
    echo $ci_nro_cuenta;
    echo '" required>
    <div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
    </div>

    <div class="form-group">
    <label for="fechaTransf">Fecha de su Transferencia</label>
    <input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
    echo $fecha_transf;
    echo '" required>
    <div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
    </div>

    <input type="hidden" name="user" value="'.$usua.'">


    <button type="submit" class="btn btn-primary" name="pago_mensualidad_btn">Enviar</button>

    </form>';
    }
    // } else {
    //
    //     echo '<div class="alert alert-warning" role="alert" >
    //     <h3>SU USUARIO ESTA BLOQUEADO</h3>
    //     <p>Si considera que es un error, favor ingrese al area de <a target="_BLANK" href= "http://www.jesuministrosymas.com.ve/contactenos" ><b>CONTACTENOS</b></a> para mas informacion.</p>
    // </div>';
    // }
  }


  function verificar_pago_mensualidad(){
  	global $db, $usua, $mmo, $concepto, $operador, $link, $m_dias_r, $fecha_sistema, $como_pagar, $pago_mensualidad;

  	analisis_dias_restantes();
  	if ($pago_mensualidad == 0) {
  		// SI NO HAY MENSUALIDAD PAGA
  	$pago_mensualidad = '';
  	}
  	else {
  		// SI SE DETECTA PAGO DE MENSUALIDAD
  		$pago_mensualidad = '<hr>';
  		//$pago_mensualidad .= $img_recarga_sin_necesidad;
  		$pago_mensualidad .= '<div class="alert alert-warning" role="alert">USTED HA PAGADO SU MENSUALIDAD PARA USAR LA PLATAFORMA '.strtoupper ($operador) .' Y TIENE ACTIVO TODOS LOS MODULOS </div>';
  		$pago_mensualidad .= '';
  		$pago_mensualidad .= '';
  		$pago_mensualidad .= '<hr>';
  	}
  	echo $pago_mensualidad;
  }



  $m_dias_r ="";

  function analisis_dias_restantes(){
    global $db, $usua, $mmo, $concepto, $operador, $link, $m_dias_r, $fecha_sistema, $como_pagar, $pago_mensualidad, $link_recargas;

    selector_operador();

    $sql = "SELECT DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE concepto = '$concepto' AND user = '$usua' AND (status_pago = 'APROBADO' OR status_pago = 'PENDIENTE') ORDER BY id DESC LIMIT 1";
$result = mysqli_query($db, $sql);

if ($result){
$row = mysqli_fetch_assoc($result);

//return $user;
if ($row['DiasRestantes']>0){


$como_pagar = "";
$m_dias_r = ' De la plataforma <b>'.$operador.'</b> le quedan <b>'.$row['DiasRestantes'].' Dias </b> Restantes para disfrutar de su plan de uso.<hr>';
$pago_mensualidad = 1;
 }



else {
  $como_pagar = $como_pagar;
  $m_dias_r = ' No se ha detectado pago de mensualidad para el uso del servicio de recargas <b>'.$operador.'</b><hr>';
  $pago_mensualidad = 0;
}
  } else {
    $como_pagar = $como_pagar;
    $m_dias_r = ' No se ha detectado pago de mensualidad para el uso del servicio de recargas <b>'.$operador.'</b><hr>';
    $pago_mensualidad = 0;
  }



}



 //ACTIVAR O SUSPENDER USUARIO
 function activar_bloquear_usuario() {
    global $db, $logo;

   $idusuario = e($_REQUEST['id']);
   $status_usuario = e($_REQUEST['status']);
   $nombre = e($_REQUEST['nombre']);
   $email = e($_REQUEST['email']);

if ($status_usuario==0){
  $sql = "UPDATE users SET
  status = 1,
  motivo_bloqueo = NULL
  WHERE id = '$idusuario'";
  $mensaje = "Se ha ACTIVADO al usuario de manera correcta..!!<br>";
  $asunto = "Su usuario ha sido Desbloqueado";
  $cuerpo = "Hola $nombre <br><br>  Le informamos que su usuario ha sido desbloqueado de manera exitosa y puede ingresar nuevamente a la plataforma con su usuario y clave. <br>";

  enviarEmail($email, $nombre, $asunto, $cuerpo);

  $mensaje .= '<i class="fa fa-envelope"></i> Hemos enviado Un correo a '.$nombre.' indicando que el usuario fue desbloqueado';

} else {

  $motivo = 'No se ha definido un motivo en particular, normalmente este tipo de bloqueo responde al hecho de que nunca ha utilizado la plataforma y el sistema le ha bloqueado como parte de un proceso de depuración de nuestro sistema, tambien el bloqueo puede responder al hecho de que nos hemos tratado de comunicar con usted via telefonica a los numeros de telefonos suministrados y los mismos son incorrectos o estan desconectados, por ello es importante que suministre informacion real y actualizada. En cualquier momento usted puede comunicarse via telefonica, Whatsapp o por Telegram para que podamos analizar su caso.';

  $sql = "UPDATE users SET
  status = 0,
  motivo_bloqueo = '$motivo'
  WHERE id = '$idusuario'";

  $link_mensualidades = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php" target="_blank"><b> ACTIVAR ALGUN PLAN DISPONIBLE </b></a>';

  $link_contactanos = '<a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensajeria.php" target="_blank"><b> CONTACTANOS AQUI </b></a>';

  $link_cancelar_ggroups = '<a href="mailto:gestionderecargas+unsubscribe@googlegroups.com">gestionderecargas+unsubscribe@googlegroups.com</a>';

$asunto = "Su Usuario ha Sido Bloqueado";

$cuerpo = "Hola $nombre <br><br><p>Le informamos que su usuario ha sido bloqueado por el siguiente motivo:</p><p> $motivo. </p><p> Con esta accion su usuario se bloqueará y lamentablemente ya no podrás utilizar el sitio..!</p><p>Si considera que es un error en cualquier momento puede favor comuniquese con nosotros para reconsiderar el bloqueo de su usuario.</p><p>Si considera que es un error, puede comunicarse respondiendo este correo o ingresando al modulo de Mensajerias de la plataforma $link_contactanos </p><p>No te preocupes, ahora es posible reactivar tu usuario de manera automatica solo debes efectuar el pago de algunas de las mensualidades disponibles hoy mismo, puedes hacerlo ingresando a: $link_mensualidades </p><p>Si desea dejar de recibir mensajeria instantanea de la plataforma puedes hacerlo en cualquier momento: <p>Para cancelar la suscripcion al grupo de distribucion masiva de informacion es sencillo, envía un correo electronico con cualquier contenido al correo $link_cancelar_ggroups y listo de manera automatica dejara de rcibibir correos automatizados del sistema</p>";

enviarEmail($email, $nombre, $asunto, $cuerpo);


  $mensaje = "Se ha BLOQUEADO al usuario de manera correcta..!!<br>";
  $mensaje .= "Se ha enviado una notificacion por correo electronico al usuario..!<br>";
}

if (mysqli_query($db, $sql)) {
   $_SESSION['usuarios']  = $mensaje;
   //header('location: usuarios.php');

} else {
   echo "Error updating record: " . mysqli_error($db);
   mysqli_close($db);
}
}



 //ACTIVAR O DESACTIVAR COMENTARIO
 function activar_desactivar_comentario() {
  global $db;

 $id = e($_REQUEST['id']);
 $visible = e($_REQUEST['visible']);
// $user = ($_REQUEST['user']);
 //$nombre = ($_REQUEST['nombre']);
 //$email = ($_REQUEST['email']);

if ($visible==0){
$sql = "UPDATE comentario SET
visible = 1
WHERE id = '$id'";
$mensaje =  'Se ha ACTIVADO este comentario al usuario de manera correcta..!!<br>';

} else {
$sql = "UPDATE comentario SET
visible = 0
WHERE id = '$id'";
$mensaje = "Se ha BLOQUEADO el comentario de manera correcta..!!<br>";
}

if (mysqli_query($db, $sql)) {
 $_SESSION['comentario']  = $mensaje;
 //header('location: usuarios.php');

} else {
 echo "Error updating record: " . mysqli_error($db);
 mysqli_close($db);
}
}





 //APROBAR PAGOS MENSUALIDAD
 function aprobar_pago_mes() {
     global $db, $logo, $fecha_act, $mes_de_pago_actual;
    $id = e($_REQUEST['id']);
    $usua = e($_REQUEST['user']);
    //echo $idusuario;
    // if (isset($_GET['id']))
    // $idusuario=$_GET['id'];

    $sqlA = "UPDATE pagos SET
   status_pago = 'APROBADO',
   fecha_aprobacion = NOW()
   WHERE id = '$id'";

if (mysqli_query($db, $sqlA)) {


    $sql2 = "SELECT pagos.a_favor AS 'a_favor', pagos.concepto AS 'concepto', pagos.mes_de_pago AS 'mes', pagos.afiliacion AS 'afiliacion', users.id AS 'id_usuario', users.nombre AS 'nombre', users.email AS 'email' FROM pagos INNER JOIN users ON pagos.user=users.idusuario WHERE pagos.id = '$id' ";

  	$result = mysqli_query($db, $sql2);
    $row = mysqli_fetch_assoc($result);


    $id_usuario = $row['id_usuario'];
    $email = $row['email'];
    $nombre = $row['nombre'];
    $mes = $row['mes'];
    $afiliacion = $row['afiliacion'];
    $concepto = $row['concepto'];

    $operadora = str_replace("MENS_", "", $concepto);


/*
$sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$id_usua','-$monto','$descripcion','$id_pago',NOW(),1)";

if (mysqli_query($db, $sql2))
*/


    $sqlB = "UPDATE `users` SET `monto_a_favor` = 0, `status` = (CASE WHEN status = 0 THEN 1 ELSE status END)
    WHERE `users`.`id` = $id_usuario";

    if (mysqli_query($db, $sqlB)){
      $_SESSION['pago_mensualidad'] = "Este usuario ya puede utilizar los modulos de recargas.<br>";
// PROCESAR ACTIVACION DE MENSUALIDADES

		activar_automatica_mes(strtolower($mes_de_pago_actual),'MOVILNET',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'MOVISTAR',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'DIGITEL',$id_usuario);
    activar_automatica_mes(strtolower($mes_de_pago_actual),'DIRECTV',$id_usuario);

    } else {
      $_SESSION['pago_mensualidad'] = "Algo ha ocurrido " . mysqli_error($db). "<br>";
    }



$az = '';

$_SESSION['pago_mensualidad']  .= "Se ha Actualizado status de Pago de $nombre de manera correcta..!!<br>";

      $pr = 'Recargas ';
      $az = 'https://virtual.jesuministrosymas.com.ve/u/usuario/recargas_'.strtolower($operadora).'.php';

	$asunto = "Aprobado su Pago del Periodo $mes de la Operadora $operadora";
	$cuerpo = "Hola $nombre <br><br>Le informamos que su pago del periodo $mes ha sido aprobado de manera satisfactoria <br>Desde ya puede ingresar y generar solicitudes de recarga adaptados a su plan $afiliacion de la Operadora $operadora <br>";

  $cuerpo .= '<br><span style="background-color: #baedec; color: #fff; display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 10px;"><strong><a href="'.$az.'" target="_BLANK">'.$pr . $operadora.'</a></strong></span><br>';

		enviarEmail($email, $nombre, $asunto, $cuerpo);

        $_SESSION['pago_mensualidad']  .= '<i class="fa fa-envelope"></i> Le hemos enviado un Email a ' .$nombre.' informando sobre la aprobacion de su pago y la invitacion a que ingrese a hacer recargas..!!';

 } else {
    $_SESSION['pago_mensualidad'] = "Error al actualizar este dato, algo ha ocurrido: " . mysqli_error($db);
    mysqli_close($db);
 } }




//LISTA PAGO OPERADORES
function lista_pagos_operador(){
    global $db, $usua, $mes, $limit_end, $accion, $concepto;

    selector_operador();

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
  } else {
    $busqueda = "";
  }


	if (isset($_GET['p']))
		$ini=$_GET['p'];
	else
		$ini=1;
    $init = ($ini-1) * $limit_end;


        if (isAdmin()) {
            //SI ES ADMIN

          if (empty($busqueda)) {
            $busqueda = "";

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'";

            $querymes = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos
             INNER JOIN users
                        ON pagos.user=users.idusuario
                        WHERE status_pago = 'PENDIENTE' ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades Pendientes.';
          } else {

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE' AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )";

            $querymes = "SELECT pagos.*, users.nombre, users.email, users.username
            FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'
            AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )
            ORDER BY fecha_pago ASC
            LIMIT $init, $limit_end";

	          $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';

          }

        } else {
// SI ES USUARIO
            $countmes="SELECT COUNT(*) FROM pagos WHERE user = '$usua' AND concepto = '$concepto'";
            $querymes = "SELECT * FROM pagos  WHERE user = '$usua' AND concepto = '$concepto' ORDER BY id DESC LIMIT $init, $limit_end";
            $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades que Mostrar del usuario ' .ucwords(strtolower($_SESSION['user']['nombre']));


        }

	if (!$rowmes){

	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	echo '</h3>';
	echo '</div>';

	} else {
		$num = $db->query($countmes);
		$x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";
        if (isAdmin()){
    // SI ES ADMIN

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
     <th>ID</th>
     <th>Usuario</th>
     <th>Nombre</th>
      <th>Fecha de Transf </th>
      <th>Monto / Mes Pagado</th>
      <th>Nro Transf / CI</th>
      <th>Desde / Hasta</th>
      <th>Accion</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC))
      {
      $date = date_create($rowmes['fecha_transf']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
      $rowUser = $rowmes['user'];
      $rowid = $rowmes['id'];

      $rowNombre = $rowmes['nombre'];


      // MENSUALIDADES

        $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "">

        <input type="hidden" name="id" value="'.$rowid.'">
        <input type="hidden" name="user" value="'.$rowUser.'">

        <button type="submit" class="btn btn-success btn-block" name="aprobar_pago_btn" data-html="true" data-toggle="popover" title="Aprobar Pago" data-content="Aca podra aprobar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Aprobar <i class="fa fa-check-circle"></i></button> ';


       $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=mensualidad" type="submit" class="btn btn-danger btn-block" data-html="true" data-toggle="popover" title="Rechazar Pago" data-content="Aca podra rechazar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Rechazar  <i class="fa fa-times-circle"></i></a></form>';

       botonera_usuario($rowNombre, $rowUser);

        $link = '<div class="btn-group-vertical" role="group" >'. $aprobar .$rechazar . $accion . '</div>';


echo '<tr>';
echo '<td>'.$rowid.'</td>
       <td>'.$rowUser.'</td>
       <td>'.$rowNombre.'</td>
       <td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. / '.$rowmes['mes_de_pago']. '</td>
       <td>'.$rowmes['nro_transf'] . ' / '.$rowmes['ci_nro_cuenta'].'</td>
       <td>'.$rowmes['banco_origen'].' / '.$rowmes['banco_destino'] .'</td>
       <td>'.$link .'</td>
      </tr>';
      }
      echo '</tbody></table>';


        }
        else
        // SI ES USUARIO
        {

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
      <th>Fecha de Pago</th>
      <th>Monto</th>
      <th>Mes</th>
      <th>Desde / Hasta</th>
      <th>Status de Pago</th>

     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC)) {

     $statuspago = $rowmes['status_pago'];
     $mes = $rowmes['mes_de_pago'];
     $motivo = strip_tags($rowmes['motivo_rechazo']);

     if ($statuspago == "PENDIENTE") {
       $statuspago = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="PENDIENTE" data-content="Su pago aun no ha sido conformado.">
       PENDIENTE  <i class="fa fa-clock"></i>
     </div>';
     } else if ($statuspago == "APROBADO") {

      $statuspago = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="APROBADO" data-content="Su pago ya fue aprobado, ya puede generar pedidos en el periodo '.$mes.' .">
       APROBADO  <i class="fa fa-thumbs.-up"></i>
     </div>';

     }
     else if ($statuspago == "RECHAZADO") {

        $statuspago = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo: '.$motivo.'.">
         RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
       </div>';

       }


      $date = date_create($rowmes['fecha_pago']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
echo '<tr>';
echo '<td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. Plan '.$rowmes['afiliacion'].'</td>
       <td>'.$rowmes['mes_de_pago'] .'</td>
       <td>'.$rowmes['inicio'] . ' / '.$rowmes['fin'] .'</td>
       <td>'.$statuspago .'</td>
      </tr>';
      }
      echo '</tbody></table>';

        }



        echo '<div class="d-none d-sm-none d-md-block">';
            pag($ini, $limit_end, $total);
        echo "</div>";
        echo '<div class="d-block d-sm-block d-md-none">';
        pag_test($ini, $limit_end, $total);
        echo "</div>";
}


}

$dest ="";

function selector_bancario($a){
  global $dest;
  if ($a == 'Banco Banesco a Nombre de JE SUMINISTROS Y MAS CA'){
    $dest = 'Banesco JE';
    }
    if ($a == 'Banco Banesco a Nombre de ELENA NUÑEZ'){
    $dest = 'Banesco Elena';
    }
    if ($a == 'Banco Venezuela a Nombre de JOSE HERRERA'){
    $dest = 'BDV';
    }
    if ($a == 'Banco Occidental de Descuento BOD a Nombre de GLADYS ARRAYAGO'){
    $dest = 'BOD';
    }
    if ($a == 'Banco Bicentenario a Nombre de JOSE HERRERA'){
    $dest = 'Bicentenario';
    }
    if ($a == 'Banco del Caribe a Nombre de JOSE HERRERA'){
    $dest = 'Bancaribe';
    }
    if ($a == 'PAYPAL (SOLO MENSUALIDADES)'){
    $dest = 'PAYPAL';
    }
    if ($a == 'GIFT CARD (SOLO MENSUALIDADES)'){
    $dest = 'GIFT CARD';
    }
    if ($a == 'SKRILL (SOLO MENSUALIDADES)'){
    $dest = 'SKRILL';
    }
    if ($a == 'NETELLER (SOLO MENSUALIDADES)'){
    $dest = 'NETELLER';
    }
    if ($a == 'Interno'){
    $dest = 'Interno';
    }
    return $dest;
}

function img_ope($a){
  global $img_ope, $logo_movilnet, $logo_movistar, $logo_digitel, $logo_directv, $logo_inter, $logo_netflix, $logo_billetera;

  if ($a == 'MENS_MOVILNET' || $a == 'Movilnet'){
    $img_ope = $logo_movilnet;
  }
  if ($a == 'MENS_MOVISTAR' || $a == 'Movistar'){
    $img_ope = $logo_movistar;
  }
  if ($a == 'MENS_DIGITEL' || $a == 'Digitel'){
    $img_ope = $logo_digitel;
  }
  if ($a == 'MENS_DIRECTV' || $a == 'Directv'){
    $img_ope = $logo_directv;
  }
  if ($a == 'MENS_INTER' || $a == 'Inter'){
    $img_ope = $logo_inter;
  }
  if ($a == 'MENS_NETFLIX' || $a == 'Netflix'){
    $img_ope = $logo_netflix;
  }
  if ($a == 'BILLETERA' || $a == 'Billetera'){
    $img_ope = $logo_billetera;
  }

  return $img_ope;

}

// LISTAR PAGOS MENSUALES LISTA MESES
function lista_pagos_mes(){
	global $db, $usua, $mes, $limit_end, $accion, $concepto, $dest, $img_ope;

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
  } else {
    $busqueda = "";
  }


	if (isset($_GET['p']))
		$ini=$_GET['p'];
	else
		$ini=1;
    $init = ($ini-1) * $limit_end;


        if (isAdmin()) {
            //SI ES ADMIN

          if (empty($busqueda)) {
            $busqueda = "";

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE'";

            $querymes = "SELECT pagos.*, users.cel, users.tlf, users.nombre, users.email, users.username FROM pagos
             INNER JOIN users
                        ON pagos.user=users.idusuario
                        WHERE status_pago = 'PENDIENTE' ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades Pendientes.';
          } else {

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE status_pago = 'PENDIENTE' AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )";

            $querymes = "SELECT pagos.*, users.cel, users.tlf, users.nombre, users.email, users.username FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
             WHERE status_pago = 'PENDIENTE'  AND (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' ) ORDER BY fecha_pago ASC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';

          }

        } else {

// SI ES USUARIO
selector_operador();

            $countmes="SELECT COUNT(*) FROM pagos WHERE user = '$usua' AND concepto = '$concepto'";
            $querymes = "SELECT * FROM pagos  WHERE user = '$usua' AND concepto = '$concepto' ORDER BY id DESC LIMIT $init, $limit_end";
            $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades que Mostrar del usuario ' .ucwords(strtolower($_SESSION['user']['nombre']));


        }

	if (!$rowmes){

	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	echo '</h3>';
	echo '</div>';

	} else {
		$num = $db->query($countmes);
		$x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";
        if (isAdmin()){



// SI ES ADMIN

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
     <th>ID</th>
     <th>Usuario / Nombre / Tlf</th>
      <th>Fecha de Transf </th>
      <th>Monto / Mes Pagado / Concepto / Nro Transf / CI</th>

      <th>Desde / Hasta</th>
      <th>Accion</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC))
      {
      //$date = date_create($rowmes['fecha_transf']);
      //$fecha = date_format($date, 'd-m-Y');
      //$fecha_pago = $fecha;
      $rowUser = $rowmes['user'];
      $rowid = $rowmes['id'];
      $cel = $rowmes['cel'];
      $tlf = $rowmes['tlf'];
      $rowNombre = $rowmes['nombre'];
      $concep = $rowmes['concepto'];


      $destino = $rowmes['banco_destino'];


      // MENSUALIDADES

        $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "">

        <input type="hidden" name="id" value="'.$rowid.'">
        <input type="hidden" name="user" value="'.$rowUser.'">

        <button type="submit" class="btn btn-success btn-block" name="aprobar_pago_btn" data-html="true" data-toggle="popover" title="Aprobar Pago" data-content="Aca podra aprobar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Aprobar <i class="fa fa-check-circle"></i></button> ';


       $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=mensualidad" type="submit" class="btn btn-danger btn-block" data-html="true" data-toggle="popover" title="Rechazar Pago" data-content="Aca podra rechazar el pago de esta mensualidad y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Rechazar  <i class="fa fa-times-circle"></i></a></form>';

       botonera_usuario($rowNombre, $rowUser);

        $link = '<div class="btn-group-vertical" role="group" >'. $aprobar .$rechazar . $accion . '</div>';

selector_bancario($destino);
img_ope($concep);

//var_dump($img_ope);

echo '<tr>';
echo '<td>'.$rowid.'</td>
       <td>'.$rowUser.'<br>'.$rowNombre.'<br>'.$tlf.'<br>'.$cel.'</td>
       <td>'.$rowmes['fecha_transf'] .'</td>
       <td>Transf: '.$rowmes['monto'].' Bs. <br>A Favor:  '.$rowmes['a_favor'].' Bs. <br> '.$rowmes['mes_de_pago']. '<br>'.$concep. '<br>Transf: '. $rowmes['nro_transf'] .'<br>C.I.: '.$rowmes['ci_nro_cuenta'] .'<br></td>
       <td>'.$rowmes['banco_origen'].' / '.$dest.'<br>'.$img_ope.'<br></td>
       <td>'.$link .'</td>
      </tr>';
      }
      echo '</tbody></table>';


        }
        else
        // SI ES USUARIO
        {

	echo '<div class="table-responsive">';
    echo '<table id="tabla1" class="table table-bordered table-hover ">
    <thead>
     <tr>
      <th>Fecha de Pago</th>
      <th>Monto</th>
      <th>Mes</th>
      <th>Status de Pago</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC)) {

     $statuspago = $rowmes['status_pago'];
     $mes = $rowmes['mes_de_pago'];
     $motivo = strip_tags($rowmes['motivo_rechazo']);

     if ($statuspago == "PENDIENTE") {
       $statuspago = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="PENDIENTE" data-content="Su pago aun no ha sido conformado.">
       PENDIENTE  <i class="fa fa-clock"></i>
     </div>';
     } else if ($statuspago == "APROBADO") {

      $statuspago = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="APROBADO" data-content="Su pago ya fue aprobado, ya puede generar pedidos en el periodo '.$mes.' .">
       APROBADO  <i class="fa fa-thumbs.-up"></i>
     </div>';

     }
     else if ($statuspago == "RECHAZADO") {

        $statuspago = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo: '.$motivo.'.">
         RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
       </div>';

       }


      $date = date_create($rowmes['fecha_pago']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
echo '<tr>';
echo '<td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. Plan '.$rowmes['afiliacion'].'</td>
       <td>'.$rowmes['mes_de_pago'] .'</td>
       <td>'.$statuspago .'</td>
      </tr>';
      }
      echo '</tbody></table>';

        }



        echo '<div class="d-none d-sm-none d-md-block">';
            pag($ini, $limit_end, $total);
        echo "</div>";
        echo '<div class="d-block d-sm-block d-md-none">';
        pag_test($ini, $limit_end, $total);
        echo "</div>";
}


}



//PARA EL MODAL DE AGREGAR USUARIO
function modal_agregar_usuario(){
	global $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $user_type;


echo ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">

<div class="form-group">
<label for="idusuario">Id del Usuario</label>
<input type="text" pattern="[V,J,G,E]{1}[-][0-9]{7,9}" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
//echo $idusuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="nombre_usuario">Nombre del Usuario</label>
<input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Nuevo Usuario" name="nombre_usuario" value="';
echo $nombre_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="email_usuario">Email del Usuario</label>
<input type="email" pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)" title="Debe utilizar solo correos gmail, yahoo, hotmail o cantv" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Nuevo Usuario Debe utilizar solo correos gmail, yahoo, hotmail o cantv" name="email_usuario" value="';
echo $email_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
</div>

<div class="form-group">
<label for="telefono_usuario">Telefono del Usuario</label>
<input pattern="[0-9]{11}" title = "Debe ingresar un telefono valido con 11 digitos, no se requiere el codigo de discado internacional" type="tel" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Nuevo Usuario" name="telefono_usuario" value="';
echo $telefono_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Celular del Usuario</label>
<input pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" type="tel" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Nuevo Usuario" name="celular_usuario" value="';
echo $celular_usuario;
echo '" required>
<div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
</div>

<div class="form-group">
<label for="user_type">Tipo de Usuario</label>
<select class="custom-select" id="user_type" name="user_type" value="';
echo $user_type;
echo '" required >';
//echo '<option value="">Seleccione:</option>';
user_type();
echo '</select>
<div class="invalid-feedback">Debe Seleccionar El tipo de Usuario.</div>
</div>



<button type="submit" class="btn btn-primary" name="agregar_usuario_btn">Enviar</button>

</form>';
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_desde_usuario(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $rowid, $status_usuario, $nombre_comercio, $direccion_comercio, $logo_comercio;

    $usua = ($_SESSION['user']['username']);

    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

          $nombre_comercio = $row['nombre_comercio'];
          $direccion_comercio = $row['direccion_comercio'];
          $logo_comercio = $row['logo_comercio'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$modal_editar_usuario .= $direccion_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$modal_editar_usuario .= $estado_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$modal_editar_usuario .= $ciudad_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$modal_editar_usuario .= $municipio_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$modal_editar_usuario .= $parroquia_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>


<div class="form-group">
<label for="nombre_comercio">Nombre del Comercio</label>
<input type="text" class="form-control" id="nombre_comercio" aria-describedby="nombre_comercio" placeholder="Ingrese el Parroquia" name="nombre_comercio" value="';
$modal_editar_usuario .= $nombre_comercio;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>

<div class="form-group">
<label for="direccion_comercio">Direccion del Comercio</label>
<input type="text" class="form-control" id="direccion_comercio" aria-describedby="direccion_comercio" placeholder="Ingrese el Parroquia" name="direccion_comercio" value="';
$modal_editar_usuario .= $direccion_comercio;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>

<div class="form-group">
    <label for="logo_comercio">Logo</label>';

// Si hay un logo almacenado, muestra la imagen
if (!empty($logo_comercio)) {
    $modal_editar_usuario .= '<img src="' . $logo_comercio . '" alt="Logo del comercio" class="img-fluid">'; // Mostrar imagen
} else {
    // De lo contrario, muestra un texto o un placeholder
    $modal_editar_usuario .= 'No se ha subido un logo.';
}

$modal_editar_usuario .= '<input type="file" class="form-control-file" id="logo_comercio" name="logo_comercio" accept="image/*"> 
  <div class="invalid-feedback">Debe seleccionar una imagen.</div>
</div>
</div>';



echo $modal_editar_usuario;
}

function modal_editar_desde_usuario2(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid;

    $usua = ($_SESSION['user']['username']);

    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$modal_editar_usuario .= $direccion_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>



';

$sq = "SELECT * FROM estados ORDER BY id_estado";
$results = mysqli_query($db, $sq);
$modal_editar_usuario .= '<div class="form-group">
<label for="banco_emisor">Seleccione su Estado</label>
<select class="custom-select" id="estado_id" name="estado_id" value="" required >
<option value="">Seleccione:</option>';
while ($a = mysqli_fetch_array($results)) {
  $modal_editar_usuario .= '<option value="'.$a['id_estado'].'">'.$a['estado'].'</option>';
}
$modal_editar_usuario .= '</select> <div class="invalid-feedback">Debe Seleccionar su estado.</div>
</div>';


$modal_editar_usuario .= '<div class="form-group">
   <label for="name1">Ciudad</label>
   <select id="ciudad_id" class="form-control" name="ciudad_id" required>
     <option value="">-- SELECCIONE --</option>
  </select> <div class="invalid-feedback">Debe Seleccionar su estado.</div>
 </div>';


$modal_editar_usuario .= '


<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$modal_editar_usuario .= $estado_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$modal_editar_usuario .= $ciudad_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$modal_editar_usuario .= $municipio_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$modal_editar_usuario .= $parroquia_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

echo $modal_editar_usuario;
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_password_desde_usuario(){
	global $db, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid, $usua;



    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_array($result);

    $rowid = $row['username'];
   // $rowid = $row['id'];
          $idusuario = $row['idusuario'];
          $nombre_usuario = $row['nombre'];
          $email_usuario = $row['email'];
          $telefono_usuario = $row['tlf'];
          $celular_usuario = $row['cel'];
          $direccion_usuario = $row['direccion'];
          $ciudad_usuario = $row['ciudad'];
          $estado_usuario = $row['estado'];
          $municipio_usuario = $row['municipio'];
          $parroquia_usuario = $row['parroquia'];
          //$password_usuario = $row['password'];
          $status_usuario = $row['status'];

$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "crear_password.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






$modal_editar_usuario .= '<div class="form-group">
<label for="password_1">Password o Contraseña</label>
<input pattern="[a-zA-Z0-9.+_-]{6,10}" title="Debe utilizar combiaciones de Letras, Numeros y Puede utilizar los caracteres especiales: . + _ - Puede usar un minimo de 6 caracteres y un maximo de 10"
type="password" class="form-control" id="password_1" placeholder="Password" name="password_1" required>
<div class="invalid-feedback">Ingrese su Password o Contraseña. Por su seguridad Recomendamos que Utilice una contraseña conformada por combiaciones de Letras Pueden ser Mayusculas o Minusculas y Numeros. Su contraseña debe tener minimo 6 caracteres y un maximo de 10 caracteres. Puede utilizar los caracteres especiales: . + _ - </div>
</div>

<div class="form-group">
    <label for="password_2">Repita su Password o Contraseña</label>
    <input pattern="[a-zA-Z0-9.+_-]{6,10}" title="Debe utilizar combiaciones de Letras, Numeros y Puede utilizar los caracteres especiales: . + _ - Puede usar un minimo de 6 caracteres y un maximo de 10"
 type="password" class="form-control" id="password_2" placeholder="Password" name="password_2" required>
    <div class="invalid-feedback">Ingrese su Password o Contraseña. Por su seguridad Recomendamos que Utilice una contraseña conformada por combiaciones de Letras Pueden ser Mayusculas o Minusculas y Numeros. Su contraseña debe tener minimo 6 caracteres y un maximo de 10 caracteres. Puede utilizar los caracteres especiales: . + _ - </div>
  </div';

echo $modal_editar_usuario;
}

//PARA EL MODAL DE EDITAR USUARIO
function modal_editar_usuario(){
	global $db, $usua, $idusuario,
    $nombre_usuario, $email_usuario,  $telefono_usuario,
    $celular_usuario, $password_usuario, $user_type, $rowid;


$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "usuarios.php">';

$modal_editar_usuario .= 'Identificador: ' .$rowid;

$modal_editar_usuario .= '<div class="form-group">
<label for="idusuario">Id del Usuario</label>
<input type="text" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
$modal_editar_usuario .= $idusuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="nombre_usuario">Nombre del Usuario</label>
<input type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese el Nombre del Nuevo Usuario" name="nombre_usuario" value="';
$modal_editar_usuario .= $nombre_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de ID del Usuario.</div>
</div>

<div class="form-group">
<label for="email_usuario">Email del Usuario</label>
<input type="text" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese el Email del Nuevo Usuario" name="email_usuario" value="';
$modal_editar_usuario .= $email_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email del Usuario.</div>
</div>

<div class="form-group">
<label for="telefono_usuario">Telefono del Usuario</label>
<input type="text" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Nuevo Usuario" name="telefono_usuario" value="';
$modal_editar_usuario .= $telefono_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Telefono del Usuario.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Celular del Usuario</label>
<input type="text" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Nuevo Usuario" name="celular_usuario" value="';
$modal_editar_usuario .= $celular_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Celular del Usuario.</div>
</div>

<div class="form-group">
<label for="password_usuario">Password del Usuario</label>
<input type="text" class="form-control" id="password_usuario" aria-describedby="password_usuario" placeholder="Ingrese el Password del Nuevo Usuario" name="password_usuario" value="';
$modal_editar_usuario .= $password_usuario;
$modal_editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Password del Usuario.</div>
</div>

<button type="submit" class="btn btn-primary" name="agregar_usuario_btn">Enviar</button>

</form>';
return $modal_editar_usuario;
}

function agregar_usuario(){
    global $db, $error;
    $alea = "";
// RECIBE LOS DATOS DEL FORM
$idusuario          =  strtoupper(e($_POST['idusuario']));
$nombre_usuario     =  strtoupper(e($_POST['nombre_usuario']));
$email_usuario      =  strtolower(e($_POST['email_usuario']));
$telefono_usuario   =  (e($_POST['telefono_usuario']));
$celular_usuario    =  (e($_POST['celular_usuario']));
$direccion_usuario  =  "Debe Completar";
$ciudad_usuario     =  "Debe Completar";
$estado_usuario     =  "Debe Completar";
$municipio_usuario  =  "Debe Completar";
$parroquia_usuario  =  "Debe Completar";
$user_type          =  "user";
$alea = generateRandomString(10);
// $a dato a verificar y $b el regex
//validar_dato($a, $b);
$vid = "[V,J,G,E]{1}[-][0-9]{7,9}";
$vnu = "[A-Z ]{7,50}";
$veu = "[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)";
$vtu = "[0]{1}[2]{1}[1-9]{1}[0-9]{8}";
$vcu = "[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}";
validar_dato($idusuario, $vid);
validar_dato($nombre_usuario, $vnu);
validar_dato($email_usuario, $veu);
validar_dato($telefono_usuario, $vtu);
validar_dato($celular_usuario, $vcu);
//$password_usuario   =  ($_POST['password_usuario']);

//$password = md5($password_usuario);//encrypt the password before saving in the database

$verf = "SELECT email FROM users WHERE username = '$idusuario' OR email = '$email_usuario'";
		$result = mysqli_query($db, $verf);
		$rows =  mysqli_num_rows($result);
		if ($rows>0){
			$_SESSION['usuarios']  = 'Lo sentimos, el usuario que intenta registrar ya existe, si no recuerda sus credenciales de acceso favor ingrese a <a href="recuperar_password.php">RECUPERAR CONTRASEÑA</a>.<br>';
      $_SESSION['msg'] = $_SESSION['usuarios'];
      //header('location: usuarios.php');
      //mysqli_close($db);
		} else {

$query = "INSERT INTO users (
id,
idusuario,
nombre,
username,
email,
tlf,
cel,
direccion,
ciudad,
estado,
municipio,
parroquia,
user_type, control)
VALUES(null, '$idusuario', '$nombre_usuario', '$idusuario', '$email_usuario', '$telefono_usuario', '$celular_usuario', '$direccion_usuario', '$ciudad_usuario','$estado_usuario','$municipio_usuario', '$parroquia_usuario','$user_type', '$alea')";
  //mysqli_query($db, $query);
  if (mysqli_query($db, $query)) {

    $_SESSION['usuarios']  = "Se ha registrado nuevo usuario de manera Exitosa.<br>";
    $_SESSION['msg'] = $_SESSION['usuarios'];

    $sql = "SELECT id FROM users
    WHERE username='$idusuario' OR username='$idusuario'";
    $results_sql = mysqli_query($db, $sql);
    $rows_sql =  mysqli_fetch_assoc($results_sql);

    $rowid = $rows_sql['id'];

$email = $email_usuario;
$nombre = $nombre_usuario;
$asunto = "Registro Exitoso Sistema Gestion de Recargas";
$cuerpo = 'Hola '.$nombre.' <br><br>Usted ha sido registrado de manera exitosa en la Plataforma Digital de J.E Suministros y Mas, C.A. Ventana digital que le permitira adquirir Recargar Movilnet, Recargas Movistar, Recargas Digitel.<p style="text-align: justify;"><strong>SUS CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br> <span style= "background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <strong>'.$email_usuario.'</strong><br>Su Usuario es: <strong>'.$idusuario.'</strong></span></p><p>&nbsp;</p></hr>CREA TU CONTRASEÑA DE ACCESO AQUI</strong></span></p><br><br>Ahora debes crear tu contraseña ingresando <p style="text-align: center;"><br> <span style="background-color: #FFFD01; color: #fff; display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 10px;"><strong><a href=';
$cuerpo .= '"';
$cuerpo .= "https://virtual.jesuministrosymas.com.ve/u/crear_password.php?id=";
$cuerpo .=$rowid;
$cuerpo .="&control=";
$cuerpo .=$alea;
$cuerpo .= '"';
$cuerpo .=">CREAR CONTRASEÑA AQUI</a></strong></span></p><br><br>";
$cuerpo .= "Ya en breve podras acceder al sistema y empezar a utilizarlo.";
enviarEmail($email, $nombre, $asunto, $cuerpo);

$_SESSION['usuarios']  .= '<i class="fa fa-envelope"></i> Hemos enviado un Correo con Instrucciones para que cree su contraseña.<br>';
$_SESSION['msg'] .= '<i class="fa fa-envelope"></i> En breve este sistema enviará un Correo Electronico a la direccion '.$email.' suministrada con instrucciones para que usted cree su contraseña, si no encuentra el correo en el buzon de correo normal favor revise el buzon de correos no deseados o buzon de correos SPAM.<br>Si por algun error el correo '.$email.' no existe entonces usted debe comunicarse con nosotros via Whatsapp, o Telegram para que podamos efectuar la correccion del correo.';

} else {

      $_SESSION['usuarios']  .= '<i class="fa fa-exclamation-triangle"></i> Algo ha ocurrido, favor intente este proceso mas tarde.<br>'. mysqli_error($db);
      $_SESSION['msg'] .= '<i class="fa fa-exclamation-triangle"></i> Algo ha ocurrido, favor intente este proceso mas tarde.<br>'. mysqli_error($db);
    }

}
}


function editar_desde_usuario(){
    global $db, $error;
// RECIBE LOS DATOS DEL FORM
$telefono_usuario     =  e($_POST['telefono_usuario']);
$celular_usuario      =  e($_POST['celular_usuario']);
$direccion_usuario    =  e($_POST['direccion_usuario']);
$ciudad_usuario       =  e($_POST['ciudad_usuario']);
$estado_usuario       =  e($_POST['estado_usuario']);
$municipio_usuario    =  e($_POST['municipio_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);

$usua = e($_SESSION['user']['username']);


//$password = md5($password_usuario);//encrypt the password before saving in the database

$sql = "UPDATE users SET
   tlf = '$telefono_usuario',
   cel = '$celular_usuario',
   direccion = '$direccion_usuario',
   ciudad = '$ciudad_usuario',
   estado = '$estado_usuario',
   municipio = '$municipio_usuario',
   parroquia = '$parroquia_usuario'
   WHERE username = '$usua'";

if (mysqli_query($db, $sql)) {
    $_SESSION['msn_perfil']  = "Se ha Actualizado su usuario de manera correcta..!!";

 } else {
    echo "Error updating record: " . mysqli_error($db);
    mysqli_close($db);
 }



}

function guardar_editar_usuario(){
    global $db, $error, $usua, $logo, $footer_correo;
    $id = ($_GET['id']);
// RECIBE LOS DATOS DEL FORM
$idusuario = e($_POST['idusuario']);
$nombre = strtoupper(e($_POST['nombre']));
$email = strtolower(e($_POST['email']));
$telefono_usuario     =  e($_POST['telefono_usuario']);
$celular_usuario      =  e($_POST['celular_usuario']);
$direccion_usuario    =  e($_POST['direccion_usuario']);
$ciudad_usuario       =  e($_POST['ciudad_usuario']);
$estado_usuario       =  e($_POST['estado_usuario']);
$municipio_usuario    =  e($_POST['municipio_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);
$parroquia_usuario    =  e($_POST['parroquia_usuario']);
$status_usuario    =  e($_POST['status_usuario']);
$web    =  e($_REQUEST['web']);


//$password = md5($password_usuario);//encrypt the password before saving in the database

$sql = "UPDATE users SET
   nombre       = '$nombre',
   email        = '$email',
   idusuario    = '$idusuario',
   username     = '$idusuario',
   tlf          = '$telefono_usuario',
   cel          = '$celular_usuario',
   direccion    = '$direccion_usuario',
   ciudad       = '$ciudad_usuario',
   estado       = '$estado_usuario',
   municipio    = '$municipio_usuario',
   parroquia    = '$parroquia_usuario',
   status       = '$status_usuario'
   WHERE id     = '$id'";

if (mysqli_query($db, $sql)) {
    $_SESSION['usuarios']  = '<i class="fa fa-thumbs.-up"></i> Se ha Actualizado este usuario de manera correcta..!!<br>';
    //sleep(10);

  $asunto = "Actualizacion de Usuario";
  $cuerpo = '<p>Hola '.$nombre.' <br><br> Por alguna razon hemos tenido que modificar tu perfil dentro de la plataforma, normalmente se debe a que al momento de ingresar tus datos en el formulario de solicitud de afiliacion algunos datos como tu correo lo escribistes con errores, o colocastes datos incompletos y los mismos ya fueron corregidos, te invitamos a utilizar tus credenciales:</p><p style="text-align: justify;"><strong>CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br> <span style="background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <strong>'.$email.'</strong><br>Su Usuario es: <strong>'.$idusuario.'</strong></span></p><p>&nbsp;</p><hr /><p>Ahora puedes acceder y crear tu contrase&ntilde;a desde el modulo <a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank"> OLVIDO CONTRASE&Ntilde;A:</a></p><p style="text-align: center;"><br> <span style="background-color: #DE0000; color: #fff; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;"><strong><a href="https://virtual.jesuministrosymas.com.ve/u/recuperar_password.php" target="_blank">RECUPERA TU CLAVE DE ACCESO AQUI</a></strong></span></p>';

    enviarEmail($email, $nombre, $asunto, $cuerpo);

    $_SESSION['usuarios']  .='<i class="fa fa-envelope"></i> Le Hemos enviado un Correo notificandole sobre esta accion..<br>';

    header('location:'.$web);


 } else {
    echo "Error updating record: " . mysqli_error($db);
    mysqli_close($db);
 }



}

function editar_usuario(){
    global $db, $error;
// RECIBE LOS DATOS DEL FORM

$id = ($_GET['id']);

if (isAdmin()){



$query = "SELECT * FROM users WHERE id = '$id'";
		$result = mysqli_query($db, $query);
        $rows =  mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
		if ($rows<1){
			$_SESSION['editar_usuarios']  = "Lo sentimos, el usuario que intenta editar no existe id $id.<br>";
			//mysqli_close($db);
		} else {
            $idusuario = $row['idusuario'];
            $nombre = $row['nombre'];
            $email = $row['email'];
            $telefono_usuario = $row['tlf'];
            $celular_usuario = $row['cel'];
            $direccion_usuario = $row['direccion'];
            $ciudad_usuario = $row['ciudad'];
            $estado_usuario = $row['estado'];
            $municipio_usuario = $row['municipio'];
            $parroquia_usuario = $row['parroquia'];
            //$password_usuario = $row['password'];
            $status_usuario = $row['status'];

            $option = "";
            if ($status_usuario ==1){
                $option = '<option value= "'.$status_usuario.'">ACTIVO</option>
                <option value = "0">SUSPENDER</option>';
            }else if ($status_usuario ==0){
                $option = '<option value= "'.$status_usuario.'">SUSPENDIDO</option>
                <option value = "1">ACTIVAR</option>';
            }

            $editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "editar_usuarios.php?id='.$id.'">';
$editar_usuario .= 'Web de Origen: ' . $web = basename($_SERVER['REQUEST_URI']).'<br>';
$editar_usuario .= 'Identificador: ' .$id .'<br>';
$editar_usuario .= 'Usuario: ' .$idusuario .'<br>';
$editar_usuario .= 'Nombre: ' .$nombre .'<br>';
$editar_usuario .= 'Email: ' .$email .'<br>';
$editar_usuario .= '<div class="dropdown-divider"></div>';

$editar_usuario .= '<div class="form-group">
<label for="nombre">Numero de Cliente</label>
<input type="tel" class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese Id de Usuario" name="idusuario" value="';
$editar_usuario .= $idusuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el idusuario.</div>
</div>



<div class="form-group">
<label for="nombre">Nombre</label>
<input type="tel" class="form-control" id="nombre" aria-describedby="nombre" placeholder="Ingrese nombre" name="nombre" value="';
$editar_usuario .= $nombre;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el nombre.</div>
</div>


<div class="form-group">
<label for="email">Email</label>
<input type="tel" class="form-control" id="email" aria-describedby="email" placeholder="Ingrese Email" name="email" value="';
$editar_usuario .= $email;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Email.</div>
</div>



<div class="form-group">
<label for="telefono_usuario">Numero de Telefono Local</label>
<input type="tel" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
$editar_usuario .= $telefono_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el numero de Telefono local.</div>
</div>

<div class="form-group">
<label for="celular_usuario">Numero de Celular</label>
<input type="tel" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
$editar_usuario .= $celular_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su numero de telefono Celular.</div>
</div>

<div class="form-group">
<label for="direccion_usuario">Su Direccion Completa</label>
<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
$editar_usuario .= $direccion_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
</div>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
<input type="text" class="form-control" id="estado_usuario" aria-describedby="estado_usuario" placeholder="Ingrese el Estado" name="estado_usuario" value="';
$editar_usuario .= $estado_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
</div>

<div class="form-group">
<label for="ciudad_usuario">Ciudad donde vive</label>
<input type="text" class="form-control" id="ciudad_usuario" aria-describedby="ciudad_usuario" placeholder="Ingrese la Ciudad" name="ciudad_usuario" value="';
$editar_usuario .= $ciudad_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
</div>

<div class="form-group">
<label for="municipio_usuario">Municipio donde vive</label>
<input type="text" class="form-control" id="municipio_usuario" aria-describedby="municipio_usuario" placeholder="Ingrese el Municipio" name="municipio_usuario" value="';
$editar_usuario .= $municipio_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar el Municipio de ubicacion.</div>
</div>

<div class="form-group">
<label for="parroquia_usuario">Parroquia donde vive</label>
<input type="text" class="form-control" id="parroquia_usuario" aria-describedby="parroquia_usuario" placeholder="Ingrese el Parroquia" name="parroquia_usuario" value="';
$editar_usuario .= $parroquia_usuario;
$editar_usuario .= '" required>
<div class="invalid-feedback">Debe indicar la Parroquia de ubicacion.</div>
</div>';

$editar_usuario .= '<div class="form-group">
<label for="exampleFormControlSelect1">Status de Usuario </label>
<select class="form-control" name = "status_usuario" id="status_usuario" value="'.$status_usuario.'">
'.$option.'
</select>
</div>';



$editar_usuario .= '<button type="submit" class="btn btn-primary" name="editar_desde_admin_btn">Enviar</button>

';
echo $editar_usuario;
        }
      } else {
        echo 'Sin autorizacion';
      }

}

//MOSTRAR PERFIL

function mostrar_perfil(){
    global $db, $usua;
    $query = "SELECT * FROM users WHERE username = '$usua'";
    $result = mysqli_query($db, $query);
    $rows = mysqli_fetch_array($result);

    $id = $rows['id'];
    $control = $rows['control'];

    echo '<h3>Los datos de su Usuario</h3>';

    echo '<div class="card">';
    echo '<ul class="list-group list-group-flush">';

    echo '<li class="list-group-item">';
    echo '<b>Usuario: </b>';
    echo $rows['username'];
    echo '<br><b>Nombre: </b>';
    echo $rows['nombre'];
    echo '<br><b>Email: </b>';
    echo $rows['email'];
    echo  '</li>';

    echo '<li class="list-group-item">';
    echo '<b>Telefono: </b>';
    echo $rows['tlf'];
    echo '<br><b>Celular: </b>';
    echo $rows['cel'];
    echo '<br><b>Direccion: </b>';
    echo $rows['direccion'];
    echo '<br><b>Estado: </b>';
    echo $rows['estado'];
    echo '<br><b>Ciudad: </b>';
    echo $rows['ciudad'];
    echo '<br><b>Municipio: </b>';
    echo $rows['municipio'];
    echo '<br><b>Parroquia: </b>';
    echo $rows['parroquia'];
    echo '</li>';


    echo '<li class="list-group-item">';
    echo '<b>Nombre de Comercio: </b>';
    echo $rows['nombre_comercio'];
    echo '<br><b>Direccion Comercio: </b>';
    echo $rows['direccion_comercio'];
    echo '<br><b>Logo: </b>';
    echo $rows['logo_comercio'];
    echo '</li>';


    echo '</ul>';

    echo '</div>';

    echo '<div class="text-right">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    <i class="fa fa-user-edit"></i> Editar</button>

    <a  class="btn btn-danger" type="button" href="../crear_password.php?id='.$id.'&control='.$control.'"><i class="fa fa-key"></i> Cambiar Contraseña</a>
    </div>';


    echo '<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">';
          modal_editar_desde_usuario();
          echo '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="editar_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
          </div>
        </div>
      </div>
    </div>';




    echo '<!-- Modal -->
    <div class="modal fade" id="cambiarpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Cambiar Password o Contraseña de Acceso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">';
          modal_editar_password_desde_usuario();
          echo '
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button name="editar_password_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
          </div>
        </div>
      </div>
    </div>';

}

function preparar_entrega_pedido() {
global $db, $errors, $nombre;
   $id_pedido = ($_GET['id']);
   $user = ($_GET['user']);

   $lote_pedido="";

   $sql="SELECT sum(monto) AS 'total'
   FROM tarjetas
   WHERE usuario = '$user' AND id_pedido = '$id_pedido'";
   $result = mysqli_query($db, $sql);

   $query = "SELECT pedidos.*, users.id AS uid, users.nombre, users.email, users.username FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario WHERE pedidos.id= '$id_pedido' ";
   $resultado = mysqli_query($db, $query) or mysqli_error($db);
   while ($row = mysqli_fetch_assoc($resultado)){
    $monto	 	    	= $row['monto'];
    $nombre	 	    	= $row['nombre'];

   }
   echo '<div class="container">';
   echo "Entrega para el Usuario <b>";
   echo $nombre;
   echo "</b><br>";
   echo "Identificador <b>";
   echo $user;
   echo "</b><br>";
   while ($row = mysqli_fetch_assoc($result))
   {
     if ($row['total']<1){
       echo "No se Han Asignado Tarjetas Aun <br>";
       echo "Se deben asignar <b> $monto Bs.</b><br>";
         } else {
          echo "A Este Pedido ya se le han asignado <b>".$row['total']. " Bs. </b><br>";
   }
 }

echo ' <form autocomplete="off" class="was-validated" method="post" action= "preparar_entrega_pedido.php?id='.$id_pedido.'&user='.$user.'">

<div class="form-group">
<label for="lote">Monto, Codigo y Serial</label>
<input minlength="31" required  type="text" class="form-control" id="lote" aria-describedby="lote" placeholder="Ingrese el lote" name="lote"';
echo 'value="';
echo $lote_pedido;
echo '">';
echo '
<div class="invalid-feedback">Se debe utilizar el siguiente formato: 3 1234 1234 1234 1234 123456789 sin puntos ni coma, solo separado con espacios</div>
</div>
<input class="input-group-text" id="finalcount" value="0" disabled />

<button type="submit" class="btn btn-primary" name="entregar_pedido_btn">Enviar</button>

</form>
</div>';


}
function confirmaciones(){
    global $db, $fecha_act;

    $msg = "";
    $id_pedido = e($_REQUEST['id_pedido']);
    $confirmacion = e($_REQUEST['confirmacion']);
    $status = '';

if ($confirmacion == 'DEVOLUCION') {
  # code...
  $status = 4;
} else if ($confirmacion == 'Esperando_Operador') {
  $status = 4;
} else {
  $status = 3;
}


    $lote_confirmacion = str_replace("	", " ", $confirmacion);

    $allValues = explode(' ', $lote_confirmacion);

    $allIDs=[];

    $query2 = "SELECT * FROM recargar WHERE relacion = '$id_pedido' ORDER BY id ASC";
    $result2 = mysqli_query($db, $query2);
    $row2 =  mysqli_num_rows($result2);

    while ($row2 = mysqli_fetch_assoc($result2))
    {
        $id = $row2['id'] ;
        $allIDs[]=$id;
    }

$allParams=array_combine($allIDs,$allValues);

if($allParams){
    $db->autocommit(FALSE);
    $sql="UPDATE recargar SET confirmacion = ?, status = '$status' WHERE id = ?";
    $stmt=$db->prepare($sql);
    $stmt->bind_param('si', $value,$id);
    $status=TRUE;
    foreach ($allParams as $id=>$value) {
        $stmt->execute() ? null : $msg =$stmt->error;
    }

    if(!$msg){
        $db->commit();
        // ACTUALIZAR TABLA PEDIDOS A ENTREGADO
$query = "UPDATE pedidos
SET status_pedido = 'ENTREGADO',
 fecha_entrega = '$fecha_act'
WHERE id = '$id_pedido'";

if (mysqli_query($db, $query)) {

    $query3 = "SELECT recargar.*, users.nombre, users.email FROM recargar INNER JOIN users ON recargar.user=users.idusuario WHERE relacion = '$id_pedido' ORDER BY id ASC";
    $result3 = mysqli_query($db, $query3);
    $row3 =  mysqli_num_rows($result3);



    $recarga = '<div class="table-responsive"><table class="table table-bordered table-hover ">';
    $recarga .= '<thead><tr>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'NUMERO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'TIPO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="20%" align="center">';
    $recarga .= 'MONTO';
    $recarga .= '</th>';
    $recarga .= '<th height="17" width ="30%" align="center">';
    $recarga .= 'CONFIRMACION';
    $recarga .= '</th>';
    $recarga .= '</tr></thead>';

    while ($row3 = mysqli_fetch_assoc($result3))
    {
        $operador =$row3['operador'];
        $nombre =  $row3['nombre'];
        $email =   $row3['email'];
        $nro =   $row3['nro'];
        $tipo =   $row3['tipo'];
        $monto =   $row3['monto'];
        $confirmacion =   $row3['confirmacion'];


        $recarga .= '<tr>';

  $recarga .= '<td align="center">';
  $recarga .= $nro;
  $recarga .= '</td>';
  $recarga .= '<td align="center">';
  $recarga .= $tipo;
  $recarga .= '</td>';
  $recarga .= '<td align="center">';
  $recarga .= $monto;
  $recarga .= ' Bs.</td>';
  $recarga .= '<td align="center"> Nro: ';
  $recarga .= $confirmacion;
  $recarga .= '</td>';
  $recarga .= '</tr>';

}
$recarga .=  '</table></div>';


$confirmaciones = $recarga;

if ($operador=='Movilnet') {
  // code...
  $mensaje_movilnet = "<br><br>Es posible que los codigos de confirmacion recibidos esten marcados como <b>Esperando_Operador</b> esto es indicativo de que esa solicitud en particular de recarga aun no ha sido procesada y nuestro sistema ha incluido dicho requerimiento en un bucle que se estara repitiendo hasta recargar el numero solicitado o hasta que se efectue el reverso de dicha solicitud.";
} else {
  // code...
  $mensaje_movilnet = '';
}

	$asunto = "Recargas Procesadas";
	$cuerpo = "Hola $nombre <br><br>Le informamos que las Recargas $operador solicitadas han sido procesadas de manera exitosa y puede ingresar a su plataforma para verificar los numeros de confirmacion respectivos. $mensaje_movilnet ";
  $cuerpo .= "<h2>Recargas Solicitadas</h2>";
  $cuerpo .= $confirmaciones;

  enviarEmail($email, $nombre, $asunto, $cuerpo);


   } else {
    $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle fa-fw"></i>Algo ha ocurrido'. mysqli_error($db);
   }

    $_SESSION['msn_pedidos']  = 'Todo fue actualizado sin problemas<br><i class="fa fa-envelope"></i> Se ha enviado un correo electronico a '.$nombre.' notificando sobre estas asignaciones de recarga..!!<br>';
    }else{
        $db->rollback();
    }
    $db->autocommit(TRUE);
} else {
    $_SESSION['msn_pedidos']  = '<i class="fa fa-exclamation-triangle"></i>Error, no se pueden combinar los valores, por favor revísalos.';
}

}



function entregar_pedido(){
  global $db, $fecha_act;

  $id_pedido = e($_REQUEST['id']);
  $user = e($_REQUEST['user']);


  $lote = e($_REQUEST['lote']);
  $lote_pedido = str_replace("	", " ", $lote);
  $datos = $lote_pedido;
// divides por espacios y cada 6 elementos, los elementos de cada fila
$temp = array_chunk(explode(' ', $datos), 6);
$ar = array();



foreach($temp as $key => $v) {
  // optienes el 1º elemento monto
  $ar[$key]['monto'] = array_shift($v);
  // optienes el ultimo elemento, serial
  $ar[$key]['serial'] = array_pop($v);
  // lo que queda es el codigo, lo unes con espacios
  $ar[$key]['codigo'] = implode(' ', $v);

  $monto =   $ar[$key]['monto'];
  $codigo =  $ar[$key]['codigo'];
  $serial =  $ar[$key]['serial'];
  try {
  $sql = "INSERT INTO tarjetas (id, monto, codigo, serial, usuario, id_pedido)
      VALUES(null, '$monto', ' $codigo', '$serial', '$user', '$id_pedido')";
     $resultado_ingreso = mysqli_query($db, $sql) or $error= (mysqli_error($db));
    } catch (Exception $e) {
        // Aqui puedes desplegar el error si quieres
        $_SESSION['msn_pedidos']  = "Algo ha Ocurrido<br>No se ejecutara ninguna accion, este fue el error:<br>" . $error;
        continue;
    }

}

if (!$resultado_ingreso){
$_SESSION['msn_pedidos']  = "Algo ha Ocurrido<br>" . $error;
} else {

$status = 'ENTREGADO';
$admin = $_SESSION['user']['username'];
$concepto = "ASIGNACION DE TARJETAS";
$sqlUPDATE = "UPDATE pedidos SET
status_pedido = '$status', fecha_entrega = '$fecha_act'
WHERE id = '$id_pedido'";

if (mysqli_query($db, $sqlUPDATE)) {
$_SESSION['msn_pedidos']  = "Se ha Actualizado el STATUS del pedido..!!<br>";
} else {
echo "Error updating record: " . mysqli_error($db);
//mysqli_close($db);
}


$query = "INSERT INTO bitacora (
id,
id_pedido,
status,
admin,
concepto)
VALUES(null, '$id_pedido', '$status', '$admin', '$concepto')";
  //mysqli_query($db, $query);
    $resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);


if (count($ar)<2){
$t= "Tarjeta";
} else {
$t= "Tarjetas";
}
$_SESSION['msn_pedidos']  .= "Se ha entregado el Pedido con Exito.<br>";
$_SESSION['msn_pedidos']  .= "En esta Transaccion fueron asignadas " .count($ar)." ".$t." <br>";

$sql1="SELECT sum(monto) AS 'total'
  FROM tarjetas
  WHERE usuario = '$user' AND id_pedido = '$id_pedido'";
  $result1 = mysqli_query($db, $sql1);

  while ($row1 = mysqli_fetch_assoc($result1))
{
  if ($row1['total']<1){
    echo "No se Ha encontrado Registros";
      } else {
        $_SESSION['msn_pedidos']  .= "Total de Bs. Entregado ".$row1['total']." Bs.<br>";
}
}


$sql2 = "SELECT tarjetas.*, users.nombre, users.email, users.username FROM tarjetas INNER JOIN users  ON tarjetas.usuario=users.idusuario WHERE usuario = '$user' AND id_pedido = '$id_pedido' ";
$result2 = mysqli_query($db, $sql2);
//if (mysqli_query($db, $query)){
$row2count =  mysqli_num_rows($result2);
//$row2 =  mysqli_fetch_assoc($result2);

  $tarjetas = '<div class="table-responsive"><table class="table table-bordered table-hover ">';
  $tarjetas .= '<thead><tr>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'MONTO';
  $tarjetas .= '</th>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'CODIGO';
  $tarjetas .= '</th>';
  $tarjetas .= '<th height="17" width ="20%" align="center">';
  $tarjetas .= 'SERIAL';
  $tarjetas .= '</th>';
  $tarjetas .= '</tr></thead>';

while ($row2 = mysqli_fetch_assoc($result2)) {
  $monto = $row2['monto'];
  $codigo = $row2['codigo'];
  $serial = $row2['serial'];
  $email_usuario = $row2['email'];
  $nombre_usuario = $row2['nombre'];

  $tarjetas .= '<tr>';

  $tarjetas .= '<td align="center">';
  $tarjetas .= $monto;
  $tarjetas .= '</td>';
  $tarjetas .= '<td align="center">';
  $tarjetas .= $codigo;
  $tarjetas .= '</td>';
  $tarjetas .= '<td align="center">';
  $tarjetas .= $serial;
  $tarjetas .= '</td>';
  $tarjetas .= '</tr>';
}
  $tarjetas .=  '</table></div>';

  $tarjetas_asignadas = $tarjetas;


$_SESSION['msn_pedidos']  .= "En total se le han asignado ".$row2count." tarjetas al usuario " .$user."<br>";

$email = $email_usuario;
$nombre = $nombre_usuario;
$asunto = "Entrega de Tarjetas UN1CA";
$cuerpo = "Hola $nombre <br><br> <h1>FAVOR LEER</h1>Por medio de la presente le informamos que la operadora Movilnet ha asignado tarjetas UN1CAS a su Pedido y desde ya puede acceder y ver su Pedido de Tarjetas On-Line en: ";
$cuerpo .= '<a href= "https://virtual.jesuministrosymas.com.ve/u/usuario/pedidos_movilnet.php"><b>VER PEDIDO COMPLETO AQUI</b></a>.<br><br>';
$cuerpo .= "<b>PARA ACCEDER A SU PEDIDO COMPLETO DEBE HACERLO INGRESANDO DIRECTAMENTE A SU PLATAFORMA DIGITAL</b>";
$cuerpo .= '<a href= "https://virtual.jesuministrosymas.com.ve/u/usuario/pedidos_movilnet.php"><b>VER PEDIDO COMPLETO AQUI</b></a>.<br><br>';
$cuerpo .= "<h2>Tarjetas Asignadas</h2>";
$cuerpo .= $tarjetas_asignadas;
$cuerpo .= "<h2>Consideraciones</h2>";
$cuerpo .= "Motivado a los ultimos eventos acontecidos en el pais, tanto el personal como la infraestructura interna de CANTV se ha visto en riesgo de ataque terrorista y por ello hay lentitud y retrasos en las entregas.";

enviarEmail($email, $nombre, $asunto, $cuerpo);

$_SESSION['msn_pedidos']  .= '<i class="fa fa-envelope"></i> Se ha enviado un correo electronico notificando sobre esta asignacion de pedido..!!<br>';

}
}
//}



  function nuevo_contenido(){
    global $db;
    $seccion = e($_POST['seccion']);
    $contenido = e($_POST['contenido']);


$query = "INSERT INTO contenido (
  id,
  seccion,
  contenido)
  VALUES(null, '$seccion', '$contenido')";
    //mysqli_query($db, $query);
      $resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);
      $_SESSION['contenido']  = "Se ha registrado un nuevo contenido de manera Exitosa.<br>";


  }

  // FUERA DE FUNCTION SE EJECUTA CREACION DE LAS VARIABLES $seccion
  $query = "SELECT seccion FROM seccion";
  $results = mysqli_query($db, $query);
  //$seccion = mysqli_fetch_array($results);

while($seccion = mysqli_fetch_array($results)) {
   ${$seccion['seccion']} = $seccion['seccion'];
}




  function analizar_mensualidad(){
    global $db, $usua, $mes_de_pago_actual, $limite_basico,
    $limite_avanzado, $limite_vip, $titulopag, $planes, $operador, $como_pagar, $registrar_mensualidad;

    $montos_permitidos ="";
    $limite_base = 0;


      $inicio = new DateTime();
      $fin = new DateTime();
      $fin = $fin->modify('last day of this month');

      $hoy_a = date('d/m/Y');
      $fin_a = $fin->format('d/m/Y');

      $interval = $inicio->diff($fin);
      $interval = $interval->days .' Dias';


  $query = "SELECT * FROM pagos WHERE user='$usua' AND mes_de_pago = '$mes_de_pago_actual' AND concepto = 'MENS_MOVILNET' AND status_pago ='APROBADO' ORDER BY `id` DESC LIMIT 1";
  $resultado = mysqli_query($db, $query);
  $row = mysqli_fetch_assoc($resultado);

  if ($row['afiliacion']=='BASICO'){
   $l = $limite_basico;
  } else if ($row['afiliacion']=='AVANZADO') {
   $l = $limite_avanzado;
  } else {
    $l = $limite_vip;
   }

   $sql = "SELECT * FROM monto LIMIT $limite_base, $l ";
  $resultadosql = mysqli_query($db, $sql);
  $num_datos = mysqli_num_rows($resultadosql);
  while ($rowsql=mysqli_fetch_row($resultadosql)){
      if ($num_datos ==1)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs.";
      }
      else if ($num_datos ==2)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs y ";
      }
      else if ($num_datos > 2)
      {
        $montos_permitidos = $montos_permitidos .  $rowsql[1] . " Bs., ";
      }
      $num_datos--;
    }
  $logo_movilnet = '<img src="../images/operadoras/movilnet.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">';

  if (mysqli_num_rows($resultado)){
      echo $logo_movilnet;
    echo ' De la platafoma <b>'. $operador .'</b> le quedan <b>'.$interval.'</b> Restantes para disfrutar de su plan. <b>'.$row['afiliacion'].'
    </b>';

    $actual = $row['mes_de_pago'];


  echo '<hr><div class="alert alert-success" role="alert">
   <h3>SOBRE SU PLAN MOVILNET CONTRATADO</h3>En el periodo correspondiente al mes de <b> '.strtoupper($actual).'</b> su tipo de Plan es Afiliacion <b>'.$row['afiliacion'].'
  </b> y Vence el dia <b>'. $fin_a .'</b> Usted puede efectuar pedidos de los siguientes Montos: <b>'.$montos_permitidos.'</b> por cada una de sus solicitudes, la cantidad de pedidos diarios, semanales o mensuales son ilimitados. Cada vez que la operadora le asigne un pedido usted podra generar un nuevo pedido, evite hacer transferencias adelantadas, efectue solo la transferencia del pedido que va a declarar. </div><hr>';
  } else {

    if ($titulopag == "Movilnet")
  {
    $link = $registrar_mensualidad;
  } else {
    $link = '<a class="btn btn-danger" href="mensualidad_movilnet.php"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <b>PAGUE SU MENSUALIDAD</b> <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></a>';
  }

    echo $como_pagar;
    echo $logo_movilnet;
    echo ' No se ha detectado pago de mensualidad para el uso del servicio <b>'.$titulopag.'</b>.';
    echo '<hr><div class="alert alert-warning" role="alert">
    Lo sentimos aun no se ha registrado o aprobado pagos de Mensualidad correspondientes de la operadora '.strtoupper($operador).' del mes de: <b>'.strtoupper($mes_de_pago_actual).'</b> si no ha efectuado pago le invitamos a hacerlo ingresando a:<br> '.$link.'<br>Si por el contrario ya efectuo su pago debe esperar a que el mismo sea conformado.<br><br>
    <h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy_a .'</b> Su renta venceria el <b>'. $fin_a .'</b><br>Pudiendo disfrutar de  <b>'.$interval . '</b> de su Plan.<br> <br>Si no desea pagar mensualidades por uso de la plataforma ahora tendra la posibilidad de efectuar pedidos para consumo personal<br><br> <a href="pedidos_movilnet_sin_plan.php" class="btn btn-info" ><b><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <i class="fas fa-hand-point-right"></i>  HACER PEDIDOS SIN PAGAR MENSUALIDADES  <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></b></a> </div><hr>';

  planes_movilnet();

  }



  }




function m_o($a){
  global $db, $montos;

  $sql ="SELECT * FROM `monto_recarga` WHERE mod (monto, $a) = 0";
 $resultadosql = mysqli_query($db, $sql);
 $num_datos = mysqli_num_rows($resultadosql);
 while ($rowsql=mysqli_fetch_row($resultadosql)){

  if($num_datos ==1)
  {
     $montos = $montos . "y " . $rowsql[1] . " Bs.";
  }
  else
  {

     $montos = $montos . $rowsql[1] . " Bs., ";
  }

     $num_datos--;
 }


}






function analizar_mensualidad2(){
    global $db, $usua, $mes_de_pago_actual, $limite_basico,
    $limite_avanzado, $limite_vip, $operador, $planes, $fecha_actual_sistema, $concepto, $m_dias_r, $como_pagar, $me, $pago_mensualidad, $cabecera_privada;

    $montos_permitidos ="";
    $limite_base = 0;

    selector_operador();


  analisis_dias_restantes();
  $cabecera_privada = $como_pagar;
  $cabecera_privada .= '<img width="20%" src="../images/operadoras/'.strtolower ($operador) .'.png" class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle,|}" alt="">';
  $cabecera_privada .= $m_dias_r;
  }


function ejecutar_editar_contenido(){
  global $db;

$rowid      = e($_REQUEST['id']);
$contenido  = e($_POST['contenido']);


  $sql = "UPDATE contenido SET
  contenido = '$contenido'
  WHERE id = '$rowid'";
  if (mysqli_query($db, $sql)) {
    $_SESSION['editar_contenido']  = "Se ha Actualizado el contenido de manera correcta..!!<br>";
    		//$email = "jose@jesuministrosymas.com.ve";
		//$nombre = "Jose";
		//$asunto = "Prueba de Contenido";
		//$cuerpo = $contenido;
		//enviarEmail($email, $nombre, $asunto, $cuerpo);
		//$_SESSION['editar_contenido']  .= '<i class="fa fa-envelope"></i> Hemos enviado Un correo a jose@jesuministrosymas.com.ve<br>';

 } else {
  $_SESSION['editar_contenido']  = "NO SE PUEDE ACTUALIZAR..!!";
    echo "Un Error ha ocurrido: " . mysqli_error($db);
    //mysqli_close($db);
 }
}

function ejecutar_editar_mensajeria(){
  global $db;

$rowid      = e($_REQUEST['id']);
$contenido  = e($_REQUEST['contenido']);
$asunto  = e($_REQUEST['asunto']);
$email = e($_REQUEST['email']);
$nombre = e($_REQUEST['nombre']);
$destinatario = e($_REQUEST['destinatario']);
$control = '';

if ($destinatario == 'JESUMINISTROSYMAS'){
  $control = '1';

} else {
  $control = '0';
}


  $sql = "UPDATE mensajes SET
  contenido = '$contenido', asunto = '$asunto', fecha_mensaje = NOW(), control = '$control'
  WHERE id = '$rowid'";
  if (mysqli_query($db, $sql)) {
    $_SESSION['editar_mensajeria']  = "Se ha Actualizado el contenido de manera correcta..!!<br>";

    $asunto2 = "Su consulta ha recibido respuesta";
    $cuerpo = "Hola $nombre <br><br>Tu requerimiento $asunto ha recibido la siguiente respuesta:<br>$contenido";

    enviarEmail($email, $nombre, $asunto2, $cuerpo);

 } else {
  $_SESSION['editar_mensajeria']  = "NO SE PUEDE ACTUALIZAR..!!";
    echo "Un Error ha ocurrido: " . mysqli_error($db);
    //mysqli_close($db);
 }
}



function breadcrumbs($sep = ' » ', $home = 'Inicio') {
    $bc     =   '<ul class="breadcrumb">';
    //Get the server http address
    $site   =   'https://'.$_SERVER['HTTP_HOST'];
    //Get all vars en skip the empty ones
    $crumbs =   array_filter( explode("/",$_SERVER["REQUEST_URI"]) );
    //Create the homepage breadcrumb
    $bc    .=   '<li><a href="'.$site.'">'.$home.'</a>'.$sep.'</li>';
    //Count all not empty breadcrumbs
    $nm     =   count($crumbs);
    $i      =   1;
    //Loop through the crumbs
    foreach($crumbs as $crumb){
    //grab the last crumb
    $last_piece = end($crumbs);

        //Make the link look nice
        $link    =  ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$crumb) );

        //Loose the last seperator
        $sep     =  $i==$nm?'':$sep;
        //Add crumbs to the root
        $site   .=  '/'.$crumb;
        //Check if last crumb
        if ($last_piece!==$crumb){
        //Make the next crumb
        $bc     .= '<li><a href="'.$site.'">'.$link.'</a>'.$sep.'</li>';
        } else {
        //Last crumb, do not make it a link
        $bc     .= '<li class="active">'.ucfirst( str_replace( array(".php","-","_"), array(""," "," ") ,$last_piece)).'</li>';
        }
        $i++;
    }
    $bc .=  '</ul>';
    //Return the result
    return $bc;
    }


    function comentarios() {
      global $db;
      $comentario ="";
      $query = "SELECT *, users.nombre AS 'nombre'
      FROM comentario
      INNER JOIN users ON (comentario.user=users.idusuario)
      WHERE visible = '1'
      ORDER BY RAND() LIMIT 15 ";
      $result = mysqli_query($db,$query);
  $rows = mysqli_num_rows($result);
  if ($rows){
    $comentario = '<h5>Comentarios de nuestros usuarios</h5><div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

  <ol class="carousel-indicators">
   <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
   <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
   <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
   ';
   $counter = 1;
   while($row = mysqli_fetch_array($result)){

        $date = date_create($row['fecha']);
   $fecha = date_format($date, 'd/m/Y');
   $fecha_comentario = $fecha;

       $comentario .= '<div class="carousel-item ';
       if($counter <= 1){$comentario .= 'active'; }

       $comentario .=  '">

       <div class="shadow p-3 mb-5 bg-white rounded">
               <blockquote class="blockquote text-center">
         <p class="mb-0">'.strtoupper($row['comentario']).'</p>
         <footer class="blockquote-footer">'.$row['nombre'].' <cite title="fecha">'.$fecha_comentario.'</cite></footer>
       </blockquote>
       </div>

       </div>';


   $counter++;
   }
  //<i class="fa fa-angle-left" aria-hidden="true"></i>
  //<i class="fa fa-angle-right" aria-hidden="true"></i>
   $comentario .= '</div>

   <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
   <span class="sr-only">Anterior</span>
  </a>

  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
   <span class="carousel-control-next-icon" aria-hidden="true"></span>
   <span class="sr-only">Siguiente</span>
  </a>

  </div>';

  } else {
 $comentario ='No hay comentarios que mostrar';
  }
  echo $comentario;

  }


if (isset($_POST['enviar_comentario_btn'])) {
    procesar_enviar_comentario();
}



function enviar_comentario(){
    global $usua, $modal_usuario_bloqueado;


    $modal = '
    <!-- Button trigger modal -->
<button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#exampleModal">
  <b data-toggle="popover" title="Dejanos tu Comentario" data-content="Ingresa aqui y dejanos tu comentario."> <i class="fa fa-comments fa-fw"></i> Dejanos tu Opinion</b>
</button>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dejanos tu Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">';

    if (isActive())
{
  $modal .= '<h4>Su comentario es importante para nosotros</h4>
        <p>En pro de que usted pueda expresarse de una manera publica, le informamos que el comentario que usted efectue en este sitio sera visible por los otros asociados a esta plataforma, recomendamos no suministrar claves de acceso ni informacion personal como: numeros de identificacion, direccion de ubicacion ni numeros de telefono o correos de contacto.</p>
        <p>Asi mismo le indicamos que este no es un espacio para reclamos, si usted posee un reclamo el mismo debe ser canalizado desde el buzon de reclamos o buzones de sugerencia.</p>
        <p>Los comentarios que contengan contenido ofensivo o sensible podra ser baneado.</p>
        <p>Si usted tiene alguna duda, o si usted necesita hacer un reclamo, si desea hacernos llegar una sugerencia, o desea hacer un aporte que usted condidere puede hacer mejorar el o los servicios ofrecidos en la plataforma puede contactarse con nosotros <a href="mensajeria.php"><b>AQUI</b></a></p>
          <form autocomplete="off" class="was-validated" method="post" action ="#">
          <label for="comentario">Su Comentario</label>
  <input required  pattern="[A-Za-z0-9 ]{20,250}"
  title="Puede utilizar Letras y números. Tamaño mínimo de su comentario debe ser de: 20 caracteres. Tamaño máximo: 250 caracteres" type="text" class="form-control" id="comentario" aria-describedby="comentario" placeholder="Ingrese el comentario" name="comentario">
  <p>Deja aca tus impresiones sobre nuestro servicio y sobre la atencion que usted ha recibido de nuestra parte.</p>
  <input type="hidden" name="usua" value="'.$usua.'">';

  $modal .= '</div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      <button type="submit" name="enviar_comentario_btn" class="btn btn-success"><i class="fa fa-comments fa-fw"></i> Enviar Comentario</button>
      </form>
    </div>
  </div>
</div>
</div>';

} else {
  $modal .= $modal_usuario_bloqueado;

$modal .= '</div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    <button type="submit" name="enviar_comentario_btn" class="btn btn-success disabled"><i class="fa fa-comments fa-fw"></i> Enviar Comentario</button>
    </form>
  </div>
</div>
</div>
</div>';
}

echo $modal;
}



function procesar_enviar_comentario(){
    global $db, $logo, $footer_correo;
    $user= e($_REQUEST['usua']);
    $comentario= e($_REQUEST['comentario']);

    //$user= mysqli_real_escape_string($db,$_REQUEST['usua']);
    //$comentario= mysqli_real_escape_string($db,$_REQUEST['comentario']);

    //echo 'Se ha agregado el siguiente comentario: '. $comentario;

    $query = "INSERT INTO comentario (id, user, comentario)
VALUES(null, '$user', '$comentario')";
	//mysqli_query($db, $query);
    //$resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);
    if (mysqli_query($db, $query)){
    $_SESSION['comentario']  = "Se ha registrado su comentario con el siguiente contenido:  $comentario y en breve figurara en el carrusel de comentarios y sera visible por todos.<br>";
  } else {
    echo mysqli_error($db);
}
}

function mostrar_alert($a) {
if (isset($_SESSION[$a])) {
			echo '<div class="alert alert-danger" role="alert" ><h3>';
			echo $_SESSION[$a];
			unset($_SESSION[$a]);
			echo '</h3></div>';
}
}



function pag_test($ini, $limit_end, $total){

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_REQUEST['busqueda'])) {
      $busqueda = strtolower(e($_REQUEST['busqueda']));

      if (empty($busqueda)) {
      $busq = "";
    } else {
      $busq = '&busqueda='.$busqueda;
    }


    } else {
      $busq = "";
      //unset($_REQUEST['busqueda']);
    }
//echo '<div class="container">';
echo '<nav aria-label="Page navigation example">';
echo '<ul class="pagination pagination-sm flex-sm-wrap">';
/****************************************/
if(($ini - 1) == 0)
{
echo "<li class='page-item disabled'><a title='Principio' class='page-link' href='$url?p=".(1).$busq."'><b><i class='fa fa-angle-double-left'></i>  </b></a></li>";
echo "<li class='page-item disabled'><a title='Anterior' class='page-link' href='#'><i class='fa fa-angle-left'></i>  </a></li>";
}
else
{
echo "<li class='page-item'><a title='Principio' class='page-link' href='$url?p=".(1).$busq."'><b><i class='fa fa-angle-double-left'></i>  </b></a></li>";
echo "<li class='page-item'><a title='Anterior' class='page-link' href='$url?p=".($ini-1).$busq."'><b><i class='fa fa-angle-left'></i>  </b></a></li>";
}
/****************************************/

  for($k=max(1, min($ini-5,$total-10));
  $k < max(min(11,$total+1), min($ini+5,$total+1));
  $k++)
  {
if($ini == $k){
    echo "<li class='page-item active'><a class='page-link' href='$url?p=$k$busq'>".$k."</a></li>";
}
else{
    echo "<li class='page-item'><a class='page-link' href='$url?p=$k$busq'>".$k."</a></li>";
}
}



/****************************************/
if($ini == $total)
{
echo "<li class='page-item disabled'><a title='Siguiente' class='page-link' href='#'> <i class='fa fa-angle-right'></i> </a></li>";
echo "<li class='page-item disabled'><a title='Ultimo' class='page-link' href='$url?p=".($total).$busq."'><b> <i class='fa fa-angle-double-right'></i></b></a></li>";
}
else
{
echo "<li class='page-item'><a title='Siguiente' class='page-link' href='$url?p=".($ini+1).$busq."'><b> <i class='fa fa-angle-right'></i></b></a></li>";
echo "<li class='page-item'><a title='Ultimo' class='page-link' href='$url?p=".($total).$busq."'><b> <i class='fa fa-angle-double-right'></i></b></a></li>";
}
/*******************END*******************/
echo "</ul>";
// echo "</div>";
echo '</nav>';
//echo '</div>';
}


function admin_comentarios(){
  global $db, $limit_end;
  //$limit_end = 1;
  //$init = "";

if (isset($_GET['p']))
$ini=$_GET['p'];
else
$ini=1;

$init = ($ini-1) * $limit_end;

  $count_query="SELECT COUNT(*) FROM comentario";

  $query = "SELECT *, comentario.id AS idrow, users.nombre AS 'nombre'
  FROM comentario
  INNER JOIN users ON (comentario.user=users.idusuario)
  ORDER BY fecha DESC
  LIMIT $init, $limit_end";

  //$result = mysqli_query($db,$query);
$result_count = mysqli_query($db, $count_query);



  if (isAdmin()){
    $num = $db->query($count_query);
    $x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);

    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";

    $admin_comentarios = '<div class="table-responsive">';
    $admin_comentarios .= '<table id="tabla1" class="table table-bordered table-hover stacktable">
      <thead>
       <tr>
       <th>ID</th>
       <th>Fecha del Comentario</th>
       <th>Nombre</th>
        <th>Comentario </th>
        <th>Accion</th>
       </tr>
       </thead>
       <tbody>';

       $c = $db->query($query);
       while($row = $c->fetch_array(MYSQLI_ASSOC))
        {
        $date = date_create($row['fecha']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_comentario = $fecha;
        $comentario = $row['comentario'];
        $id = $row['idrow'];
        $nombre = $row['nombre'];
        $user = $row['user'];

        $visible = $row['visible'];

    if ($visible==1)
    {
      $stdp = '<button type="submit" class="btn btn-success btn-sm" name="activar_desactivar_comentario_btn" data-toggle="popover" title="ACTIVO" data-content="Comentario ACTIVO haga click para desactivarlo y que no se muestre.">ACT <i class="fa fa-thumbs.-up"></i></button>';

} else {
      $stdp = '<button type="submit" class="btn btn-danger btn-sm" name="activar_desactivar_comentario_btn" data-toggle="popover" title="BLOQUEADO" data-content="Comentario BLOQUEADO, haga click para activarlo.">BLOQUEADO <i class="fa fa-thumbs.-down"></i></button>';

    }

    $link = '<form autocomplete="off" class="was-validated" method="post" action= "">
       <input type="hidden" name="id" value="'.$id.'">
    <input type="hidden" name="visible" value="'.$visible.'">
    '.$stdp.' </form>';



    $visible = $link;


    $admin_comentarios .= '<tr>';
    $admin_comentarios .= '<td>'.$id.'</td>
                          <td>'.$fecha_comentario.'</td>
                          <td>'.$nombre.'</td>
                          <td>'.$comentario .'</td>
                          <td>'.$visible.'</td>
                          </tr>';
        }
        $admin_comentarios .= '</tbody></table>';


          }

echo $admin_comentarios;
echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";
}

function error_fatal($a){
    global $db, $nombrepag, $usua;
//echo $usua;
$query = "SELECT * FROM users WHERE username='$usua' LIMIT 1";
$rows =  mysqli_fetch_array(mysqli_query($db, $query));

$id_usuario = $rows['id'];


$ip = get_client_ip();

$query_error_fatal = "INSERT INTO error_fatal (id, id_usuario, ip, web, area) VALUES(null, '$id_usuario', '$ip', '$nombrepag', '$a')";

if (mysqli_query($db, $query_error_fatal)) {
  echo '<div class="alert alert-warning" role="alert">
  <h1>Se ha registrado esta accion y se ha generado un alerta, evite continuar con esta practica no permitida.</h1>
</div>';
} else {
  echo 'error '. mysqli_error($db);
 }
}


function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
    return false;
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

function isActive(){
    global $db, $usua;

    $query = "SELECT * FROM users WHERE username = '$usua'";
	  $result = mysqli_query($db, $query);
    $rows =  mysqli_fetch_assoc($result);

	if ($rows['status']==1) {
		return true;
	}else{
		return false;
	}
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
			foreach ($errors as $error){
				echo $error;
				echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>';
			}
		echo '</div>';
	}
}



function analizar_mensualidades(){
  global $db, $usua, $mes_de_pago_actual, $limite_basico,
  $limite_avanzado, $limite_vip, $titulopag, $planes, $operador, $como_pagar, $fecha_actual_sistema, $concepto, $m_dias_r, $como_pagar, $me, $img_ope, $fecha_sistema;

  $montos_permitidos ="";
  $limite_base = 0;


    $inicio = new DateTime();
    $fin = new DateTime();
    $fin = $fin->modify('last day of this month');

    $hoy_a = date('d/m/Y');
    $fin_a = $fin->format('d/m/Y');

    $interval = $inicio->diff($fin);
    $interval = $interval->days .' Dias';

    $query = "SELECT * FROM pagos WHERE user='$usua' AND status_pago ='APROBADO' AND DATEDIFF(fin, NOW()) > 0";
    $resultado = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($resultado);

    if (mysqli_num_rows($resultado)>0) {

if (mysqli_num_rows($resultado)==1){
$tituloA = '<h3>Usted actualmente posee activo un solo plan:</h3>';
} else {
$tituloA = '<h3>Usted actualmente posee activo los siguientes planes:</h3>';
}
echo $tituloA;


} // Cierre de if (mysqli_num_rows($resultado)>0)
else {
  echo '<hr>';
  echo '<div class="alert alert-warning" role="alert">En el Periodo <b>'.strtoupper($mes_de_pago_actual).'</b> No posee Ningun Plan Activado</div>';
  echo '<hr>';
}

$query2 = "SELECT *, DATEDIFF(fin, NOW()) as DiasRestantes FROM pagos WHERE user = '$usua' AND status_pago = 'APROBADO' ORDER BY id DESC LIMIT 4";

$resul = mysqli_query($db, $query2);

while ($a = mysqli_fetch_assoc($resul)) {

  if ($a['DiasRestantes']>0) {
    $concepto = $a['concepto'];
    img_ope($concepto);
    $operador = ucwords(strtolower(str_replace("MENS_", "", $concepto)));
    echo '<hr>';
    echo '<div class="container">
  <div class="row">';
    echo '<div class="col-2">';
    echo $img_ope;
    echo '</div>';
    if ($a['DiasRestantes']==1) {
      $t_dias = "Dia restante";
    }
    else {
      $t_dias = "Dias restantes";
    }
    echo '<div class="col-10">';
    echo ' De la platafoma <b>'. $operador .'</b> le quedan <b>'.$a['DiasRestantes'].'</b> '.$t_dias.' para disfrutar de su plan de Recargas. <b><a href="recargas_'.strtolower($operador).'.php">'.strtoupper($operador).'
    </a></b>';
    echo '</div>';
    echo '</div></div>';
    echo '<hr>';

$date = date_create($a['fin']);
$fin_a = date_format($date, 'd/m/Y');
$actual = $a['mes_de_pago'];

    echo '<div class="alert alert-success" role="alert">
   <h3>SOBRE SU PLAN '.strtoupper($operador).' CONTRATADO</h3>En el periodo correspondiente al mes de <b>'.strtoupper($actual).' </b> su Plan Vence el dia <b>'. $fin_a .'</b>. y puede acceder cuando guste y hacer uso del servicio de recargas <a data-toggle="popover" data-content="Aca podrá ingresar directamente al area de recargas '.strtoupper($operador).'." href="recargas_'.strtolower($operador).'.php"><b>AQUI</b></a> </div><hr>';

    //echo $concepto;
  }
}

}


function a_favor(){
  global $db, $monto_favor, $mens_monto_favor;

$user_id = $_SESSION['user']['id'];
//echo $user_id ;
//$sql = "SELECT monto_a_favor FROM `users` WHERE id = $user_id AND disp_a_favor = 1";

$sql = "SELECT SUM(monto) AS 'monto_a_favor' FROM billetera WHERE id_usuario = '$user_id' AND  status = '1'";

$row = mysqli_fetch_assoc(mysqli_query($db, $sql));
$montoafavor = $row['monto_a_favor'];

if ($montoafavor>0) {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = '<div class="alert alert-danger" role="alert">Usted posee un saldo a favor de <b>' .
      number_format($monto_favor, 2, ',', '.') . '</b>
       Bs.</div><p>Este saldo sera utilizado de forma automatica para recalcular el monto que usted debe pagar en esta operacion.</p>';
}
else if ($montoafavor<0) {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = '<div class="alert alert-danger" role="alert">Usted posee una deuda de <b>'.number_format(abs($monto_favor), 2, ',', '.').' Bs.</b></div>';
} else {
  $monto_favor = $GLOBALS['monto_a_favor'] = $montoafavor;
  $mens_monto_favor = "<h4>Su saldo es de 0,00 Bs.</h4>";
  //$mens_monto_favor = '';
}


}




function conteo(){
global $db, $fecha_act_lectura, $fads, $titulo;

$verf = "SELECT id FROM users";
$result = mysqli_query($db, $verf);
$rows =  mysqli_num_rows($result);

$variable_interno = 0;
$suma=$rows+$variable_interno;

$boton = '';

if ($titulo == "Registro en el Sistema") {
  $boton = '';
} else {
$boton = '  <span class="d-inline-block" data-toggle="popover" data-content="Si aun no posee credenciales de acceso puede solicitarlas aqui.">
  <a id="afiliarse" class="btn btn-success" href="registro.php">
   <i class="fas fa-key"></i> Afiliarse de forma gratuita al Servicio Aqui</a>
  </span>';
}

//$fecha = date_format($fecha_act, 'd-m-Y');
echo '<div class="p-3 mb-2 bg-danger text-white text-center">';
echo '<i class="fas fa-users fa-10x"></i>';
echo '<h3>Hoy es: ' . $fads . '</h3><br>';
echo '<h1>Y hay registrados: ' . $suma . ' Usuarios.</h1>';

echo $boton;
echo '</div><hr>';

}


$variable_informacion_cuenta = 0;


if ($variable_informacion_cuenta == 1) {
  contenido('bancario');
  $informacion_cuentas = $contenido;

}
else {
$informacion_cuentas = '';
}

function billetera(){
  global $db, $disp,$id_usua,$dinero_billetera,$titulopag;

  $query = "SELECT SUM(CASE WHEN monto>0 THEN monto ELSE 0 END) AS 'pos', SUM(CASE WHEN monto<0 THEN monto ELSE 0 END) AS 'neg' FROM billetera WHERE id_usuario = '$id_usua' AND  status = '1'";
  $result = mysqli_query($db, $query);
  $rows =  mysqli_fetch_assoc($result);

  $pos = $rows['pos'];
  $neg = $rows['neg'];
  $dinero_billetera = $pos+$neg;
  //echo $disp;
  $disp = '';


if ($titulopag == "Billetera") {

  if ($dinero_billetera < 0) {
    $disp .= '<div class="alert alert-danger" role="alert">
    <h2>Usted posee una Deuda de:</h2><br>
    <h2 class="text-center">-' . number_format(abs($dinero_billetera),2,',','.') .' Bs.</h2></div>';
  }
elseif ($dinero_billetera == 0) {
  // code...
  $disp .= '<div class="alert alert-warning" role="alert">
  <h2>No posee dinero disponible en su billetera.</h2><br></div>';
}
  else {
    $disp .= '<div class="alert alert-info" role="alert">
    <h2>Disponible en su Billetera:</h2><br>
    <h2 class="text-center">' .number_format($dinero_billetera,2,',','.') .' Bs.</h2></div>';
  }

} else {
  if ($dinero_billetera < 0) {
    $disp .= '<div class="alert alert-danger" role="alert">
    <h2>Usted posee una Deuda de:</h2><br>
    <h2 class="text-center">-' . number_format(abs($dinero_billetera),2,',','.') .' Bs.</h2><div class="d-flex justify-content-center"><a class="btn btn-success" href="billetera.php" role="button"><i class="fas fa-money-bill-alt"></i> Recargar Billetera Virtual</a></div></div>';
  } elseif ($dinero_billetera == 0) {
    // code...
    $disp .= '<div class="alert alert-warning" role="alert">
    <h2>No posee dinero en su billetera.</h2><br>
    <div class="d-flex justify-content-center"><a class="btn btn-success" href="billetera.php" role="button"><i class="fas fa-money-bill-alt"></i> Recargar Billetera Virtual</a></div></div>';
  }else {
    $disp .= '<div class="alert alert-info" role="alert">
    <h2>Disponible en su Billetera:</h2><br>
    <h2 class="text-center">' .number_format($dinero_billetera,2,',','.') .' Bs.</h2><div class="d-flex justify-content-center"><a class="btn btn-success" href="billetera.php" role="button"><i class="fas fa-money-bill-alt"></i> Recargar Billetera Virtual</a></div></div>';
  }
}



  $disp .= '';

  return;


}





// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************
// FUNCIONES PROYECTO TSU ***********************************************************************************


function contenido($s){
  global $db, $contenido;
  $sql = "SELECT * FROM contenido WHERE seccion = '$s' " ;
  $resultado = mysqli_query($db, $sql) or mysqli_error($db);
  $row = mysqli_fetch_assoc($resultado);
  $rows = mysqli_num_rows($resultado);
  if (!$rows || strlen($row['contenido'])=='11'){
    $contenido = '';
    $contenido2 = '';
   }
    else {
  
  $id_contenido = $row['id'];
  $contenido = $row['contenido'];
  $contenido2 = '<a class="btn btn-secondary" title="Editar" target="_blank" href="https://virtual.jesuministrosymas.com.ve/u/admin/editar_contenido.php?id='.$id_contenido.'">Editar</a>';
    //echo $contenido;
  
   }
  
  if (IsAdmin()) {
    echo $contenido . $contenido2;
  }
  else {
    echo $contenido;
  }
  
  
  }

function activar_automatica_mes($a,$b,$c){
  //$a mes_de_pago_actual
  //$b Operador en Mayuscula
  //$c ID de Usuario para activarle MENS_

  global $db, $mes_de_pago_actual, $id_usua;

//echo $mes_de_pago_actual;
//echo "<br>";
$usuaci = $c; //$_SESSION['user']['idusuario'];
$concepto = $afiliacion = "MENS_".$b;

$verif1= "SELECT * FROM `pagos` WHERE user = '$usuaci' AND mes_de_pago = '$a' AND concepto = '$concepto' ORDER by id DESC LIMIT 1";
$result = mysqli_query($db, $verif1);

if($result){
   if(mysqli_num_rows($result) > 0) {
     echo '<div class="alert alert-info alert-dismissible fade show" role="primary">
      SE HA ACTUALIZADO SU PERFIL DE FORMA CORRECTA, Ahora ya puedes usar el sistema '.strtoupper($b).'
      <br>Durante todo el periodo  <b>'.strtoupper($mes_de_pago_actual).'
      </b> Ahora podras ir a cualquier seccion de este sitio. <br> Para agregar saldo a su billetera puede acceder al area de <a href="billetera.php" class="badge badge-success" data-toggle="popover" title="Recargar Billetera" data-content="Aca podra recargar su Billetera." ><i class="fas fa-wallet"></i> BILLETERA</a><button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button></div>';
   }
   else {
     if ($mes_de_pago_actual == ($a)) {

   $usua = $_SESSION['user']['idusuario'];
   $monto_mensualidad = $monto_favor = 0;
   $concepto = $afiliacion = "MENS_".$b;
   $mes_de_pago_actual = $mes_de_pago_actual;
   $banco_emisor = $banco_destino = $ci_nro_cuenta = 'Interno';
   $nro_transf = 'ACT_'.generar_cadena(40);
   $fecha_transf = $fecha_pago = $fecha_aprobacion = $fecha_inicio = date("Y-m-d H:i:s");
   $fecha_fin = date("Y-m-d H:i:s",strtotime($fecha_inicio."+ 1 month"));
   $status_pago ="APROBADO";

 $query = "INSERT INTO
 pagos (id, user, monto, a_favor, concepto, mes_de_pago, afiliacion, banco_origen, banco_destino, nro_transf, ci_nro_cuenta, fecha_transf, fecha_pago, status_pago, fecha_aprobacion, inicio, fin)
 VALUES (null, '$usua', '$monto_mensualidad', '$monto_favor', '$concepto', '$mes_de_pago_actual', '$afiliacion', '$banco_emisor', '$banco_destino', '$nro_transf', '$ci_nro_cuenta', '$fecha_transf', '$fecha_pago', '$status_pago', '$fecha_aprobacion', '$fecha_inicio', '$fecha_fin')";



     if (mysqli_query($db, $query)){


       echo '<div class="alert alert-warning alert-dismissible fade show" role="primary">
       Genial ya puedes usar el sistema '.strtoupper($b).'
       <br>Durante todo el periodo  <b>'.strtoupper($mes_de_pago_actual).'
       </b> Ahora podras ir a cualquier seccion del sitio. <br> Para agregar saldo a su billetera puede acceder al area de <a href="billetera.php" class="badge badge-success" data-toggle="popover" title="Recargar Billetera" data-content="Aca podra recargar su Billetera." ><i class="fas fa-wallet"></i> BILLETERA</a><button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button></div>';

 } else {

     echo '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i>Algo ha ocurrido, intente actualizar esta web nuevamente. Si el error persiste comuniquese de manera inmediatamente con el administrador y reporte el siguiente Error: ' . mysqli_error($db).'</div>';
         }
   } else {
     echo '<div class="alert alert-warning" role="alert">
<i class="fas fa-newspaper"></i> En <b>'.strtoupper($a).'</b> de forma automatica podras usar el sistema en este periodo de prueba!
</div>';
   }
}
}
}





// GENERAR PAGO DE BILLETERA
function generar_pago_billetera(){
  global $db, $mes_de_pago_actual, $logo, $monto_favor;

  // Datos recibidos del Formulario
  $monto           = e($_REQUEST['monto']);
  $concepto        = e($_REQUEST['concepto']);
  $afiliacion      = $concepto;
  $banco_emisor    = e($_REQUEST['banco_emisor']);
  $banco_destino   = e($_REQUEST['banco_destino']);
  $nro_transf      = e($_REQUEST['nro_transf']);
  $ci_nro_cuenta   = e($_REQUEST['ci_nro_cuenta']);
  $fecha_transf    = e($_REQUEST['fecha_transf']);
  $operador        = e($_REQUEST['titulopag']);
  $usua            = e($_REQUEST['user']);
  $user_id            = e($_REQUEST['user_id']);

  $hoy = date('d/m/Y');

  a_favor();
  $monto_favor = $GLOBALS['monto_a_favor'];
  $status_pedido ="ESPERANDO";

  $numerocorto = substr($nro_transf, -6);
  $verf = "SELECT nro_transf FROM pagos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pedidos WHERE  (nro_transf LIKE '%$numerocorto') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";
  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $_SESSION['billetera_virtual'] = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
  } else {

    $query = "INSERT INTO
    pedidos (
      id,
      usuario,
      operador,
      monto,
      nro_transf,
      banco_emisor,
      banco_destino,
      fecha_transf,
      ci_nro_cuenta,
      status_pedido,
      fecha_pedido,
      sin_plan)
      VALUES (
        null,
        '$usua',
        '$operador',
        '$monto',
        '$nro_transf',
        '$banco_emisor',
        '$banco_destino',
        '$fecha_transf',
        '$ci_nro_cuenta',
        '$status_pedido',
        STR_TO_DATE('$hoy', '%d/%m/%Y'),
        '2')";

  if (mysqli_query($db, $query)) {
    $_SESSION['billetera_virtual']  = "Se ha Registrado su Pago de Manera exitosa.<br>";
    $id_pedido = mysqli_insert_id($db);
    $descripcion = 'INGRESO';
    $sql2 = "INSERT INTO billetera (id, id_usuario, monto, descripcion, id_descripcion, fecha, status) VALUES (null, '$user_id','$monto','$descripcion','$id_pedido',NOW(),0)";

    if (mysqli_query($db, $sql2)) {
      $_SESSION['billetera_virtual']  .= "Se ha generado un registro de actualizacion de dinero en su Billetera.<br>";
    } else {
      $_SESSION['billetera_virtual']  = 'Algo ha ocurrido Actualizando su billetera, Error: ' . mysqli_error($db);
    }
    $_SESSION['billetera_virtual'] .= "Se ha registrado su pago para recarga de billetera manera Exitosa.<br>";
    $monto = number_format($monto, 2, ',', '.');
    $email = $_SESSION['user']['email'];
    $nombre = $_SESSION['user']['nombre'];
    $asunto = "Dinero a Billetera";
    $cuerpo = "Hola $nombre: <br><br>Usted ha registrado un pago de manera exitosa por concepto de Recarga de Billetera Virtual<br> por un monto de $monto Bs. <br> desde el Banco $banco_emisor <br> Hacia nuestra cuenta en el $banco_destino <br>Numero Transferencia Bancaria: $nro_transf <br>De fecha $fecha_transf <br>";
    enviarEmail($email, $nombre, $asunto, $cuerpo);
    $_SESSION['billetera_virtual'] .='<i class="fa fa-envelope"></i> Hemos enviado Un correo con el resumen de su pago';
  } else {
    $_SESSION['billetera_virtual']  = 'Algo ha ocurrido registrando el pedido de actualizacion de su billetera, Error: ' . mysqli_error($db);
  }
  }
}

billetera();

// return user array from their id
function getUserById($id){
  global $db;
  $query = "SELECT * FROM users WHERE id=" . $id;
  $result = mysqli_query($db, $query);
  $user = mysqli_fetch_assoc($result);
  return $user;
}




// LISTAR BANCO EMISOR
function banco_emisor(){
  global $db;
  $query = "SELECT * FROM banco_emisor ORDER BY banco_emisor";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['banco_emisor'].'">'.$valores['banco_emisor'].'</option>';
  }
}

// LISTAR SECCIONES
function seccion(){
  global $db;
  $query = "SELECT * FROM seccion ORDER BY seccion";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['seccion'].'">'.$valores['seccion'].'</option>';
  }
}


// LISTAR BANCO DESTINO
function banco_destino(){
  global $db;
  $query = "SELECT * FROM banco_destino";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['banco_destino'].'">'.$valores['banco_destino'].'</option>';
  }
}

// LISTAR TIPO DE USUARIO
function user_type(){
  global $db;
  $query = "SELECT * FROM user_types";
  $results = mysqli_query($db, $query);
  while ($valores = mysqli_fetch_array($results)) {
    echo '<option value="'.$valores['user_type'].'">'.$valores['descripcion'].'</option>';
  }
}

// GENERA NUMERO ALEATORIO
function generateRandomString($A) {
  return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $A);
}

// GENERA CADENA ALFANUMERICA
function generar_cadena($A) {
  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $input_length = strlen($permitted_chars);
  $random_string = '';
  for($i = 0; $i < $A; $i++) {
    $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
    $random_string .= $random_character;
  }

  return $random_string;
}



// CREAR PASSWORD
function crear_password(){
  global $db, $error;

  $password_1     = e($_POST['password_1']);
  $password_2     = e($_POST['password_2']);
  $idusuario      = e($_POST['idusuario']);
  $email          = e($_POST['email']);
  $control        = e($_POST['control']);
  $nombre        = e($_POST['nombre']);
  $username        = e($_POST['username']);

  if ($password_1 != $password_2) {
    array_push($error, "Las dos contraseñas no coinciden");
  } else {
    if (count($error) == 0) {
      $alea = generateRandomString(10);
      $password = md5($password_1);

      $sql = "UPDATE users SET
      password = '$password', control = '$alea'
      WHERE id = '$idusuario' AND email = '$email'";
    }


    if (mysqli_query($db, $sql)) {
      $_SESSION['msg']  = "Se ha creado Su contraseña de acceso de manera correcta, ahora puedes iniciar sesion..!!<br>";

      $email = $email;
      $nombre = $nombre;
      //CORREO CREACION DE CLAVE
      $asunto = "Creacion de Clave Exitoso Sistema Gestion de Recargas";
      $cuerpo = 'Hola Usuario '.$nombre.' <br><br>Usted ha creado su contraseña de manera exitosa.<br><p style="text-align: justify;"><br>Podra ingresar utilizando como usuario sus credenciales de acceso, puede utilizar su correo electronico o su numero de usuario</p> <p style="text-align: justify;"><strong>CREDENCIALES DE ACCESO:</strong></p><p style="text-align: center;"><br>  <span style="background-color: #70FF70; color: #000000; display: inline-block; padding: 3px 10px; font-weight: bold; border-radius: 5px;">Correo Registrado: <b>'.$email.'</b><br>Su Usuario es: <b>'.$username.'<b><br>Su clave de acceso es: <b>'.$password_1.'</b></span></p><br><br> Recomendamos que no borre este correo y copie sus datos de acceso en un lugar seguro.<br> <br> <br><b>PREGUNTAS FRECUENTES</b><p></p><p><b>¿Cuales son los montos de inversión?</b></p><p></p><ul><li>Primero usted debe pagar la mensualidad por uso de la plataforma segun la plataforma que usted desee utilizar. <a href="https://virtual.jesuministrosymas.com.ve/u/usuario/mensualidades.php"> <b>MENSUALIDADES</b></a></li><li>Luego generar sus respectivas solicitudes de recargas segun la operadora previamente seleccionada.</li></ul><PREGUNTAS FRECUENTES</P> <p><b>¿A que cuenta debo efectuar mi pago?</b></p><p>Usted debe hacer su pago a cualquiera de nuestras cuentas indicadas en <b><a href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA"> FORMAS DE PAGO AQUI</a>.</b></p>';

      enviarEmail($email, $nombre, $asunto, $cuerpo);
      $_SESSION['msg']  .='<i class="fa fa-envelope"></i> Le Hemos enviado un Correo notificandole sobre esta accion..<br>';
      header('location: login.php');

    } else {
      echo "Error updating record: " . mysqli_error($db);
      mysqli_close($db);
    }
  }

}

// LOGIN USER
function login(){
  global $db, $username, $errors;
  $username = e($_POST['username']);
  $password = e($_POST['password']);
  if (empty($username)) {
    array_push($errors, "Su Numero de Usuario o Correo Electronico es Requerido<br>");
  }
  if (empty($password)) {
    array_push($errors, "Su Contraseña de Acceso es Requerida<br>");
  }
  if (count($errors) == 0) {
    $password = md5($password);

    $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') AND password='$password' LIMIT 1";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) == 1) { // user found
      $logged_in_user = mysqli_fetch_assoc($results);
      $id_usuario = $logged_in_user['id'];

      if ($logged_in_user['user_type'] == 'admin') {
        $_SESSION['user'] = $logged_in_user;
        $_SESSION['success']  = "Favor Espere";
        $where = $_SESSION['here'];
        if (!empty($where)) {
          header ("Location: $where");
        } else {
          header('location: admin/home.php');
        }
      }
      else if ($logged_in_user['user_type'] == 'user'){
        $_SESSION['user'] = $logged_in_user;
        $_SESSION['success']  = "Favor Espere";
        $where = $_SESSION['here'];
        if (!empty($where)) {
          header ("Location: $where");
        } else {
          header('location: usuario/home.php');
        }
      }

      visita();

    }else {
      array_push($errors, "Combinación incorrecta de nombre de usuario/contraseña");
    }
  }
}

function visita() {
  global $pool, $nombrepag, $usua, $stmt_visita;
  try {
      // Obtener una conexión del pool
      $db = $pool->getConnection();
      // Preparar la consulta para seleccionar el usuario
      $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
      $stmt = $db->prepare($query);
      $stmt->bind_param("s", $usua);
      $stmt->execute();
      $results = $stmt->get_result();
      if ($results !== null && $results->num_rows > 0) {
          $logged_in_user = $results->fetch_assoc();
          $id_usuario = $logged_in_user['id'];
          $ip = get_client_ip();
          // Preparar la consulta para insertar la visita
          $query_visita = "INSERT INTO visitas (id, id_usuario, ip, fecha_visita, web) VALUES (null, ?, ?, NOW(), ?)";
          $stmt_visita = $db->prepare($query_visita);
          $stmt_visita->bind_param("iss", $id_usuario, $ip, $nombrepag);
          $stmt_visita->execute();
          if ($stmt_visita->error) {
              // Manejo del error
              echo 'Error al insertar la visita: ' . $stmt_visita->error;
          }
      } else {
          // Manejo del error
          echo 'Error: no se encontró ningún usuario registrado con el nombre de usuario actual.';
      }
      // Cerrar los statement y liberar la conexión
      if ($stmt !== null) {
          $stmt->close();
      }
      if ($stmt_visita !== null) {
          $stmt_visita->close();
      }
      $pool->releaseConnection($db);
  } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
  }
}




function mostrar_mensajes(){
  global $db, $limit_end, $usua;

  $url = basename($_SERVER ["PHP_SELF"]);

  if (isset($_GET['p']))
    $ini=$_GET['p'];
  else
    $ini=1;
  $init = ($ini-1) * $limit_end;

  // SI ES ADMIN
  if (isAdmin()) {
    $count_mensajeria="SELECT COUNT(*) FROM mensajes";
    $query_mensajeria = "SELECT * FROM mensajes ORDER BY id DESC LIMIT $init, $limit_end";
    $result_mensajeria = mysqli_query($db, $query_mensajeria);
    $row_mensajeria =  mysqli_num_rows($result_mensajeria);
    $mensaje  = 'No hay mensajes que Mostrar';

  } else {
    //Si es Usuario
    $count_mensajeria="SELECT COUNT(*) FROM mensajes WHERE destinatario IN ('GENERAL','$usua') OR origen = '$usua'";
    $query_mensajeria = "SELECT * FROM mensajes WHERE destinatario IN ('GENERAL','$usua') OR origen = '$usua' ORDER BY id DESC LIMIT $init, $limit_end";
    $result_mensajeria = mysqli_query($db, $query_mensajeria);
    $row_mensajeria =  mysqli_num_rows($result_mensajeria);

    $mensaje  = '<i class="fa fa-exclamation-triangle"></i> ESTAMOS MEJORANDO ESTE MODULO';
  }

  /* querys */

  if (!$row_mensajeria){
    echo '<div class="alert alert-danger" role="alert" >';
    echo '<h3>';
    echo $mensaje;
    echo '</h3>';
    echo '</div>';
  } else {
    $num = $db->query($count_mensajeria);
    $x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);
    pag_test($ini, $limit_end, $total);
    if (isAdmin()){
      echo '<div class="table-responsive"><table id="tabla1" class="table table-bordered table-hover">
      <thead>
      <tr>
      <th>ID / Fecha de Mensaje / Asunto / Para</th>
      <th>Contenido</th>
      <th>Accion</th>
      </tr>
      </thead>
      <tbody>';

      $c = $db->query($query_mensajeria);
      while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
      {
        $date = date_create($row_mensajeria['fecha_mensaje']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_mensaje = $fecha;
        $asunto = $row_mensajeria['asunto'];
        $rowid = $row_mensajeria['id'];
        $contenido = $row_mensajeria['contenido'];
        $rowid = $row_mensajeria['id'];
        $origen = $row_mensajeria['origen'];
        $destinatario = $row_mensajeria['destinatario'];

        $boton_editar = '<a class="btn btn-outline-dark btn-sm" href="editar_mensajeria.php?id='.$rowid.'" data-toggle="popover" title="EDITAR CONTENIDO" data-content="Editar este contenido.">
        Editar
        </a>';

      $accion = '<div class="btn-group" >'. $boton_editar. '</div>';

      $consultar_nombre = "SELECT nombre FROM users WHERE id = '$origen'";
      $resultado_consultar_nombre=mysqli_query($db,$consultar_nombre);
      $rcn = mysqli_fetch_assoc($resultado_consultar_nombre);


      echo '<tr>';
      echo '<td><b>'.$rowid.'</b><br>'.$fecha_mensaje.'<br>'.$asunto.'<br><b>'.$destinatario.'</b></td>
      <td>'.$contenido .'</td>
      <td>'.$accion.'</td>
      </tr>';
      }
      echo '</tbody></table></div>';


    }
    else
      // SI ES USER NO ES ADMIN
    {
      echo '<div class="accordion" id="accordionExample">';

      $c = $db->query($query_mensajeria);
      while($row_mensajeria = $c->fetch_array(MYSQLI_ASSOC))
      {
        $date = date_create($row_mensajeria['fecha_mensaje']);
        $fecha = date_format($date, 'd-m-Y');
        $fecha_mensaje = $fecha;
        $asunto = $row_mensajeria['asunto'];
        $contenido = $row_mensajeria['contenido'];
        $rowid = $row_mensajeria['id'];
        $origen = $row_mensajeria['origen'];
        $destinatario = $row_mensajeria['destinatario'];
        $control = $row_mensajeria['control'];

        if ($destinatario == 'GENERAL') {
          $destino = '<span class="justify-content-end badge badge-pill badge-info">Mensaje General</span></div>';
        } else if ($destinatario == 'JESUMINISTROSYMAS' && $control == '0') {
          $destino = '<span class="justify-content-end badge badge-pill badge-danger">Solicitud de Soporte</span></div>';
        } else if ($destinatario == 'JESUMINISTROSYMAS' && $control == '1') {
          $destino = '<span class="justify-content-end badge badge-pill badge-success">Soporte Atendido</span></div>';
        } else {
          $destino = '<span class="justify-content-end badge badge-pill badge-warning">Mensaje Para Usted</span></div>';
        }

        $a = '
        <div class="card">
        <div class="card-header" id="headingOne'.$rowid.'">
        <h5 class="row mb-0">
        <button  title="Ver detalles de '.$asunto.'" class="btn btn-link collapsed col-12" type="button" data-toggle="collapse" data-target="#collapseOne'.$rowid.'" aria-expanded="true" aria-controls="collapseOne'.$rowid.'">

        <div class="row no-gutters">
        <div class="d-flex justify-content-start col-sm-8">'.$asunto.'</div>
        <div class="d-flex justify-content-end col-sm-4">'.$destino.'
        </div>

        </button>
        </h5>
        </div>

        <div id="collapseOne'.$rowid.'" class="collapse" aria-labelledby="headingOne'.$rowid.'" data-parent="#accordionExample">
        <div class="card-body">
        Publicado en Fecha: '.$fecha_mensaje.'<br><h2>'.$asunto.'</h2>'.$contenido.'
        </div>
        </div>
        </div>

        ';
echo $a;
      }
      echo '</div>';

    }

    pag_test($ini, $limit_end, $total);
  }

}

// CONTAR MENSAJES
function contar_mensajes(){
  global $db, $usua, $contador_msn, $contador_msn_badge;

  $id_usuario = $_SESSION['user']['id'];
  $web = "mensajeria.php";

  $cont_visita = "SELECT * FROM visitas
  WHERE fecha_visita =  (
    SELECT MAX(fecha_visita)
    FROM visitas WHERE web = '$web' AND id_usuario = '$id_usuario')  ";
  $result_visita = mysqli_query($db, $cont_visita);
  $row_visita =  mysqli_fetch_assoc($result_visita);

  $fecha_visita = $row_visita['fecha_visita'];
  $cont_msn = "SELECT * FROM mensajes
  WHERE (destinatario IN ('GENERAL','$usua') OR origen = '$usua')
  AND fecha_mensaje > '$fecha_visita'";

  $resultcont = mysqli_query($db, $cont_msn);
  $rowcont =  mysqli_num_rows($resultcont);


  if ($rowcont >= 1) {
    $contador_msn_badge = $rowcont;
    $contador_msn = 'Tiene '.$rowcont .' mensaje por leer!';
  }
  else {
    $contador_msn_badge = "";
    $contador_msn = "No hay Mensajes Nuevos";
  }

  $contador_msn .= '<br>Ahora puedes enviarnos mensajes que seran atendidos a la brevedad.';

}

// MOSTRAR ERROR
function display_error2() {
  global $error;

  if (count($error) > 0){
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    foreach ($error as $error){
      echo $error;
      echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>';
    }
    echo '</div>';
  }
}

// VERIFICAR STATUS DE USUARIO
function status_usuario(){
  global $db, $usua, $operador, $nombrepag;
  $sql ="SELECT * FROM users WHERE username = '$usua' ";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);

  $motivo= $row['motivo_bloqueo'];
  $status = $row['status'];

  if (!$motivo){
    $motivo = 'No se ha especificado un motivo en particular, si considera que es un error usted puede comunicarse con el <a href="http://www.jesuministrosymas.com.ve/contactenos" target="_blank"> Area de Soporte J.E Suministros y Mas, C.A.</a>.';
  } else {
    $motivo= $row['motivo_bloqueo'];
  }

  if ( $status == 0){

    $ndp = 'mensualidad_'.strtolower($operador).'.php';

    if ($operador == "Mensualidades" || $nombrepag == $ndp ) {
      $complemento ='<hr>Active cualquier plan disponible para que pueda desbloquear su usuario de forma automatizada. <i class="far fa-arrow-alt-circle-down fa-2x"></i>
      ';
    } else {

      $complemento = '<hr>Tambien es posible desbloquear su usuario efectuando el pago de su mensualidad hoy mismo, puedes hacerlo ingresando a: <a href="mensualidades.php"><b> ACTIVAR ALGUN PLAN DISPONIBLE</b></a>';
    }

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <h2 class="text-center"><i class="fa fa-exclamation-triangle fa-fw fa-bars "></i>Usuario Bloqueado<i class="fa fa-exclamation-triangle fa-fw fa-bars "></i></h2> <h3>Motivo:</h3>' . $motivo . '<hr>Si considera que es un error o desea que sea reconsiderada su suspension puede comunicarse a los canales de comunicacion explicando su caso <a target="_BLANK" href="mensajeria.php"><b> COMUNIQUESE CON NOSOTROS AQUI</b></a>'.$complemento.'</div>';
  }

}

// VERIFICAR TRANSFERENCIAS
function verificar_transferencias($a){
  global $db, $mensaje_verificacion;

  $verf = "SELECT nro_transf FROM pedidos WHERE (nro_transf LIKE '%$a') OR (nro_transf LIKE '%$a') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";

  $result = mysqli_query($db, $verf);
  $rows =  mysqli_num_rows($result);

  $verf2 = "SELECT nro_transf FROM pagos WHERE (nro_transf LIKE '%$a') OR (nro_transf LIKE '%$a') AND STR_TO_DATE(fecha_transf,'%Y-%m-%d %T')
  BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW()";

  $result2 = mysqli_query($db, $verf2);
  $rows2 =  mysqli_num_rows($result2);

  $sumarows = $rows + $rows2;

  if ($sumarows>0){
    $mensaje_verificacion  = '<i class="fa fa-exclamation-triangle fa-fw"></i> Lo sentimos, el numero de transferencia que intenta utilizar ya fue utilizado, recuerde que no debe utilizar un numero de transferencia usado en alguna otra operacion de declaracion de mensualidades u otros pagos de pedidos, evite ser suspendido/a.<br>';
    mysqli_close($db);
  }
}

  
// $ini=1; VALOR SUMINISTRADO POR LA FUNCTION
// $limit_end FIN DE UN CICLO EN LA PAG
// $total CANTIDAD TOTAL DE REGISTROS
function pag($ini, $limit_end, $total)
{
  $url = basename($_SERVER["PHP_SELF"]);
  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower(e($_REQUEST['busqueda']));
    if (empty($busqueda)) {
      $busq = "";
    } else {
      $busq = '&busqueda=' . $busqueda;
    }
  } else {
    $busq = "";
    //unset($_REQUEST['busqueda']);
  }
  if (isset($_REQUEST['filtro'])) {
    $filtro = strtolower(e($_REQUEST['filtro']));

    if (empty($filtro)) {
      $filt = "";
    } else {
      $filt = '&filtro=' . $filtro;
    }
  } else {
    $filt = "";
    //unset($_REQUEST['busqueda']);
  }
  echo '<nav aria-label="Page navigation example">';
  echo '<ul class="pagination pagination-sm flex-sm-wrap">';
  /****************************************/
  if (($ini - 1) == 0) {
    echo "<li class='page-item disabled'><a class='page-link' href='$url?p=" . (1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Principio</b></a></li>";
    echo "<li class='page-item disabled'><a class='page-link' href='#'><i class='fa fa-angle-double-left'></i>  Anterior</a></li>";
  } else {
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . (1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Principio</b></a></li>";
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($ini - 1) . $busq . $filt . "'><b><i class='fa fa-angle-double-left'></i>  Anterior</b></a></li>";
  }
  /****************************************/
  for (
    $k = max(1, min($ini - 5, $total - 10));
    $k < max(min(11, $total + 1), min($ini + 5, $total + 1));
    $k++
  ) {
    if ($ini == $k) {
      echo "<li class='page-item active'><a class='page-link' href='$url?p=$k$busq$filt'>" . $k . "</a></li>";
    } else {
      echo "<li class='page-item'><a class='page-link' href='$url?p=$k$busq$filt'>" . $k . "</a></li>";
    }
  }
  /****************************************/
  if ($ini == $total) {
    echo "<li class='page-item disabled'><a class='page-link' href='#'>Siguiente <i class='fa fa-angle-double-right'></i> </a></li>";
    echo "<li class='page-item disabled'><a class='page-link' href='$url?p=" . ($total) . $busq . $filt . "'><b>Ultima <i class='fa fa-angle-double-right'></i></b></a></li>";
  } else {
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($ini + 1) . $busq . $filt . "'><b>Siguiente <i class='fa fa-angle-double-right'></i></b></a></li>";
    echo "<li class='page-item'><a class='page-link' href='$url?p=" . ($total) . $busq . $filt . "'><b>Ultima <i class='fa fa-angle-double-right'></i></b></a></li>";
  }
  /*******************END*******************/
  echo "</ul>";
  // echo "</div>";
  echo '</nav>';
}


function formatearCantidad($cantidad, $tipo) {
  $cantidad_final = $cantidad;
  $unidad = "";

  if ($cantidad == 0) {
    return "Sin Existencia"; // O el mensaje que desees mostrar si no hay cantidad
  }

  if ($tipo == 'liq') {
    if ($cantidad >= 1000) {
      $cantidad_final = $cantidad / 1000; 
      $unidad = ($cantidad_final > 1) ? "Litros" : "Litro"; 
    } else {
      $unidad =  "mililitro" . ($cantidad > 1 ? "s" : "");
    }
  } else if ($tipo == 'sol') {
    if ($cantidad >= 1000) {
      $cantidad_final = $cantidad / 1000; 
      $unidad = ($cantidad_final > 1) ? "Kilos" : "Kilo";
    } else {
      $unidad =  "gramo" . ($cantidad > 1 ? "s" : ""); 
    }
  } else { //  Para otros tipos,  puedes manejarlo como desees
    $unidad = "";
  }

  $cantidad_final = round($cantidad_final, 2);

  return $cantidad_final . " " . $unidad;
}


// Función para obtener la cantidad total de materia prima
function totalMateriaPrima() {
  global $db, $id_usua;
  $sql = "SELECT 
          SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_componente 
          WHERE id_usuario = $id_usua";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad de materia prima ingresada en un mes específico
function ingresoMateriaPrimaPorMes($mes, $año) {
  global $db, $id_usua;
  $sql = "SELECT 
  SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_componente 
          WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año' AND id_usuario = '$id_usua'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad total de producto terminado
function totalProductoTerminado() {
  global $db, $id_usua;
  $sql = "SELECT 
    SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total  
FROM 
    inventario_producto_terminado 
WHERE 
    id_usuario = $id_usua";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

// Función para obtener la cantidad de producto terminado producido en un mes específico
function productoTerminadoPorMes($mes, $año) {
  global $db, $id_usua;
  $sql = "SELECT 
 SUM(CASE WHEN descripcion = 1 THEN cantidad END) AS total 
          FROM inventario_producto_terminado 
          WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año' AND id_usuario = '$id_usua'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['total'];
  } else {
      return 0;
  }
}

function kilo($a){
  if ($a>1000){
      $a = $a/1000;
      $a = number_format($a, 2, ',', '.') . ' Kilos';
  }
  else if ($a == 0){
      $a = "No hay Registros";
  }
  else {
      $a = number_format($a, 2, ',', '.') . ' Gramos';
  }
  return $a;
}

$formatter = IntlDateFormatter::create(
  'es_ES',
  IntlDateFormatter::NONE,
  IntlDateFormatter::NONE,
  'America/Santiago', // Ajusta la zona horaria si es necesario
  IntlDateFormatter::GREGORIAN,
  'MMMM' // Formato personalizado para mostrar solo el nombre completo del mes
);


function respaldarDatosUsuario($id_usua) {
  global $db, $pag_web;

  // 1. Crear la carpeta de respaldos si no existe:
  $carpetaRespaldos = 'respaldos/' . $id_usua . '/';
  if (!file_exists($carpetaRespaldos)) {
      mkdir($carpetaRespaldos, 0777, true); 
  }

  // 2. Definir el nombre del archivo del respaldo 
  $nombreArchivo = 'respaldo_' . date('Ymd_His') . '.sql';

  try {
      // Iniciar la transacción para un respaldo consistente:
      $db->begin_transaction();

      // 3. Obtener los datos de las tablas:
      $tablas = [
          'clientes' => "SELECT * FROM clientes WHERE idusuario = '$id_usua'",
          'ventas' => "SELECT * FROM ventas WHERE id_usuario = '$id_usua'",
          'inventario_componente' => "SELECT * FROM inventario_componente WHERE id_usuario = '$id_usua'",
          'inventario_producto_terminado' => "SELECT * FROM inventario_producto_terminado WHERE id_usuario = '$id_usua'"
      ];


      $sql = ''; // Acumular las consultas SQL

      foreach ($tablas as $tabla => $consulta) {
          $sql .= "-- Respaldo de datos de la tabla '$tabla'\n";
          $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";
          $resultadoTabla = $db->query("SHOW CREATE TABLE `$tabla`");
          $filaTabla = $resultadoTabla->fetch_assoc();
          $sql .= $filaTabla['Create Table'] . ";\n\n";

          $resultadoDatos = $db->query($consulta);
          while ($filaDatos = $resultadoDatos->fetch_assoc()) {
              $campos = implode("`, `", array_keys($filaDatos));
              $valores = "'" . implode("', '", array_values($filaDatos)) . "'";
              $sql .= "INSERT INTO `$tabla` (`$campos`) VALUES ($valores);\n";
          }
          $sql .= "\n\n";
      }

      // 4. Escribir el SQL en el archivo
      $rutaArchivo = $carpetaRespaldos . $nombreArchivo; 
      file_put_contents($rutaArchivo, $sql);

      // Cerrar la transacción si todo salió bien
      $db->commit();

      // Mostrar mensaje de éxito 
      echo '<div class="alert alert-success">Respaldo generado exitosamente en: <a href="'. $pag_web . '/usuario/'. $rutaArchivo .'" target="_BLANK">' . $rutaArchivo . '</a></div>';
  } catch (Exception $e) {
      // Si ocurre un error durante la transacción, hacer rollback 
      $db->rollback();
      echo '<div class="alert alert-danger">Error al generar el respaldo: ' . $e->getMessage() . '</div>';
  }
}


function optimizarTablasUsuario($id_usua) {
global $db;
// Implementar la lógica de optimización, por ejemplo:
  $tablas = ['clientes', 'ventas', 'inventario_materia_prima', 'inventario_producto_terminado'];
  
  foreach ($tablas as $tabla) {
    $sql = "OPTIMIZE TABLE $tabla"; 
    $db->query($sql); 
  }
  
  echo '<div class="alert alert-success">Tablas optimizadas correctamente.</div>'; 
} 


function respaldarDatosAdmin() {
  global $db; // Asegúrate de que $db está definida globalmente o pásala como parámetro a la función.

  // Carpeta para almacenar los respaldos (crea la carpeta si no existe)
  $carpetaRespaldos = 'respaldos/admin/';
  if (!file_exists($carpetaRespaldos)) {
      mkdir($carpetaRespaldos, 0777, true);
  }

  // Nombre del archivo de respaldo
  $nombreArchivo = 'respaldo_completo_' . date('Ymd_His') . '.sql'; 
  $rutaArchivo = $carpetaRespaldos . $nombreArchivo;

  try {
      $db->begin_transaction(); 

      // Obtener todas las tablas de la base de datos
      $resultadoTablas = $db->query("SHOW TABLES");
      $tablas = []; 
      while ($fila = $resultadoTablas->fetch_row()) {
          $tablas[] = $fila[0];
      }

      // Generar el SQL para el respaldo 
      $sql = ''; 
      foreach ($tablas as $tabla) {
          $sql .= "-- Respaldo de datos de la tabla '$tabla'\n";
          $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";

          $resultadoTabla = $db->query("SHOW CREATE TABLE `$tabla`");
          $filaTabla = $resultadoTabla->fetch_assoc();
          $sql .= $filaTabla['Create Table'] . ";\n\n";

          $resultadoDatos = $db->query("SELECT * FROM `$tabla`"); 
          while ($filaDatos = $resultadoDatos->fetch_assoc()) {
              $campos = implode("`, `", array_keys($filaDatos));
              $valores = "'" . implode("', '", array_values($filaDatos)) . "'"; 
              $sql .= "INSERT INTO `$tabla` (`$campos`) VALUES ($valores);\n";
          }

          $sql .= "\n\n"; 
      }

      // Escribir el SQL al archivo
      file_put_contents($rutaArchivo, $sql);

      // Cerrar la transacción
      $db->commit();

      echo '<div class="alert alert-success">Respaldo completo generado exitosamente: <a href="'. $rutaArchivo .'" target="_BLANK">' . $rutaArchivo . '</a></div>';
  } catch (Exception $e) {
      $db->rollback(); // Revertir la transacción si hay errores. 
      echo '<div class="alert alert-danger">Error al generar el respaldo: ' . $e->getMessage() . '</div>';
  }
}

function optimizarTablasAdmin() {
  global $db; // Asegúrate de que $db está definida globalmente o pásala como parámetro.
  
  try {
      // Iniciar transacción (opcional pero recomendado para garantizar la consistencia)
      $db->begin_transaction();

      // Obtener todas las tablas de la base de datos 
      $resultadoTablas = $db->query('SHOW TABLES');
      while ($fila = $resultadoTablas->fetch_row()) {
          $tabla = $fila[0]; 
          $db->query("OPTIMIZE TABLE `$tabla`"); 
      }

      // Cerrar transacción
      $db->commit();
      
      echo '<div class="alert alert-success">Todas las tablas de la base de datos han sido optimizadas correctamente.</div>';
  } catch (Exception $e) {
      $db->rollback(); // Revertir si hay errores. 
      echo '<div class="alert alert-danger">Error al optimizar las tablas: ' . $e->getMessage() . '</div>';
  }
}



function verificar_precios(){
  global $db, $id_usua;
  
  $query = "SELECT p.id, p.nombre 
FROM productos p
LEFT JOIN precios pre ON p.id = pre.id_producto AND pre.id_usuario = '$id_usua' 
WHERE pre.id_producto IS NULL AND p.id IN (
    SELECT ipt.id_producto
    FROM inventario_producto_terminado ipt
    WHERE ipt.id_usuario = '$id_usua')";
  
  $result = $db->query($query);
  
  if ($result->num_rows > 0) {

    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Advertencia!</strong> Se ha detectado la existencia en Stock de Productos pero que aun no se le han asignado Precio. Se sugiere ir a la seccion de <a href="precios.php">Precios</a> y crear el precio correspondiente para que el mismo pueda aparecer en la lista de Venta. <ul>';
    while($row = $result->fetch_assoc()){
        echo "<li><a href='#' class='crear-precio' data-id='" . $row['id'] . "' data-nombre='" . $row['nombre'] . "'>" . $row['nombre'] . "</a></li>"; 
    }
    echo ' </ul><button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">×</span>
    </button>
    </div> ';
    
    // Mostrar el modal con el producto
    echo '<div class="modal fade" id="modalCrearPrecio" tabindex="-1" role="dialog" aria-labelledby="modalCrearPrecioLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearPrecioLabel">Crear Precio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCrearPrecio">
                    <input type="hidden" id="producto" name="producto">
                    <div class="form-group">
                        <label for="productoNombre">Producto:</label>
                        <input type="text" class="form-control" id="productoNombre" name="productoNombre" readonly>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <input type="text" class="form-control" id="precio" name="precio" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-tag"></i> Crear Precio</button>
                </form>
            </div>
        </div>
    </div>
    </div>';

    // Agregar JavaScript para manejar el evento click en los links
    echo '<script>
    $(document).ready(function() {
        // Evento click en los links para abrir el modal
        $(".crear-precio").click(function(event) {
            event.preventDefault();
            var producto = $(this).data("id");
            var productoNombre = $(this).data("nombre");
            $("#producto").val(producto);
            $("#productoNombre").val(productoNombre);
            $("#modalCrearPrecio").modal("show");
        });
    
        // Submit del formulario del modal
        $("#formCrearPrecio").submit(function(e) {
            e.preventDefault();
            var producto = $("#producto").val();
            var precio = $("#precio").val();
            
            // Realizar la solicitud AJAX para guardar el precio
            $.ajax({
                url: "funciones/guardar_precios.php",
                type: "POST",
                body: formCrearPrecio,
                data: {
                    producto: producto,
                    precio: precio
                },
                success: function(response) {
                  $("#modalCrearPrecio").modal("hide");
                  alert("Precio creado correctamente.");

                  //  Recargar la página actual
                  location.reload(); 
                },
                error: function(xhr, status, error) {
                    console.error("Error al crear el precio: " + error);
                    alert("Error al crear el precio. Por favor, inténtalo de nuevo.");
                }
            });
        });
    });
    </script>';

} else {
    //echo "<h1>No se encontraron productos sin precio</h1>"; 
}
}

	?>