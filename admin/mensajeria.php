<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Mensajeria";
include('../funciones/functions.php');


if (isset($_POST['enviar_mensaje_todos_btn'])) {
    enviar_mensaje_todos();
}

function enviar_mensaje_todos(){
    global $db, $errors, $usua, $footer_correo, $logo, $mes_de_pago_actual;

    $asunto = $_REQUEST['asunto'];
    $contenido = $_REQUEST['contenido'];
    $origen = $usua;
    $l = $_REQUEST['lista'];

    // MENSAJE GENERAL
        if ($l == 1){

        echo $asunto .    $contenido .
        $origen .
        $l;

      $query = "INSERT INTO mensajes (id, asunto, contenido, origen)
    VALUES(null, '$asunto', '$contenido', '$origen')";
      $resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);
      $_SESSION['mensajeria']  = "Se ha creado un nuevo mensaje.<br>";
      exit;

      }

// SIN PASSWORD
    if ($l == 2){

    // Query para solo quienes no han creado password
   $query = "SELECT email, nombre FROM users WHERE password is null AND status = 1"; }
// NO HAN USADO PLATAFORMA
   if ($l == 3) {
   // Query solo para quienes nunca han usado la plataforma pero si tienen clave y han accedido al sistema
   $query = "SELECT email, nombre
   FROM users t1
   WHERE NOT EXISTS (SELECT user
                      FROM pagos t2
                     WHERE t2.user = t1.username) AND t1.password IS NOT null AND t1.status = '1'";

}

// ESPERANDO DESPACHO DE PEDIDO

if ($l == 4) {


    // Query solo para quienes estan esperando despacho de pedido
    $query = "SELECT email, nombre
    FROM users t1
   WHERE EXISTS (SELECT usuario
                       FROM pedidos t2
                      WHERE t2.usuario = t1.username AND ( t2.status_pedido = 'ESPERANDO' OR t2.status_pedido = 'APROBADO') AND t1.status = '1')";

 }

 // ACTIVOS EN EL MES EN CURSO

 if ($l == 5) {


     // Query solo para quienes estan activos en el mes en curso
     $query = "SELECT email, nombre, status FROM users t1 WHERE EXISTS (SELECT user FROM pagos t2 WHERE t2.user = t1.username AND ( t2.mes_de_pago = '$mes_de_pago_actual') AND ( t1.status ='1'))";

  }

  // MEDIANTE GRUPO GOOGLE
      if ($l == 6){

      echo $asunto .    $contenido .
      $origen .
      $l;

    $q = "INSERT INTO mensajes (id, asunto, contenido, origen)
  VALUES(null, '$asunto', '$contenido', '$origen')";
    $resultado_ingreso = mysqli_query($db, $q) or mysqli_error($db);
    $_SESSION['mensajeria']  = "Se ha creado un nuevo mensaje y se ha guardado en la base de datos, lo podra acceder cada usuario desde su plataforma.<br>";

    $email = 'gestionderecargas@googlegroups.com';
    $nombre = 'Estimados Usuarios';
    $cuerpo = "Saludos cordiales $nombre<br>";
    $cuerpo .= $contenido;
    enviarEmail($email, $nombre, $asunto, $cuerpo);

    $_SESSION['mensajeria']  .= "Se ha enviado un mensaje de forma correcta al grupo $email .<br>";
    $where = $_SESSION['here'];
    if (!empty($where)) {
      header ("location: $where");
      exit;
} else {
      header("location: mensajeria.php");
      exit;
}

    }

   $result = mysqli_query($db, $query);
   $row = mysqli_num_rows($result);

   $rows =  mysqli_fetch_assoc($result);

    while ($rows =  mysqli_fetch_assoc($result))
  {
$email = $rows['email'];
$nombre = $rows['nombre'];

//echo $email;
$cuerpo = "Saludos cordiales $nombre<br>";
$cuerpo .= $contenido;
enviarEmail($email, $nombre, $asunto, $cuerpo);

  } //fin de ciclo


$_SESSION['mensajeria']  = "Se ha enviado un mensaje a todos los usuarios con el asunto: <br>$asunto  y el contenido: <br>$contenido<br>Solo le llegará a la cantidad de: $row Usuarios";

//    $query = "INSERT INTO mensajes (id, asunto, contenido, origen) VALUES(null, '$asunto', '$contenido', '$origen')";
//mysqli_query($db, $query);
//  $resultado_ingreso = mysqli_query($db, $query) or mysqli_error($db);
 // $_SESSION['mensajeria']  = "Se enviara un mensaje con el asunto: <br>$asunto  y el contenido: <br>$contenido.<br> a $email";

}

?>
<?php include("includes/head.php");
 ?>



<div class="container">

  <p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
      Redactar Mensaje
    </a>

  </p>
  <div class="collapse" id="collapseExample">
<div class="card">
  <div class="card-body">
            <p>Esta accion enviará correo a todos los usuarios sin almacenar nada en la base de datos, esta accion puede demorar depende de la cantidad de usuarios. </p>
        <form autocomplete="off" class="was-validated" method="post" action= "mensajeria.php">

                   <div class="form-group">
          <label for="exampleFormControlSelect1">A Quien le enviará el Mensaje </label>
          <select class="form-control" name = "lista" id="lista" value="" required>
          <option value="">Seleccione:</option>
          <option title="Mensaje General a Todos los Usuarios" value= "1">Mensaje General</option>
          <option title="Solo para quienes no han creado password" value= "2">Sin Password</option>
          <option title= "Solo para quienes nunca han usado la plataforma pero si tienen clave y han accedido al sistema" value= "3">No han Usado Plataforma</option>
          <option title= "Solo para quienes estan esperando despacho de pedido" value= "4">Esperando Despacho de Pedido</option>
          <option title= "Solo para quienes estan Activos en el Mes <?php  echo strtoupper($mes_de_pago_actual) ?>" value= "5">Activos en el Mes <?php  echo strtoupper($mes_de_pago_actual) ?> </option>
          <option title= "A todos los activos en el Grupo Anti Spam" value= "6">A todos mediante Grupo Google </option>
          </select>
          <div class="invalid-feedback">Debe Seleccionar a que lista enviara el correo.</div>
          </div>


           <div class="form-group">
          <label for="idusuario">Asunto</label>
          <input value ="<?php $asunto; ?>" type="text" class="form-control" id="asunto" aria-describedby="titulo" placeholder="Ingrese el asunto" name="asunto" required>
          <div class="invalid-feedback">Debe indicar el asunto.</div>
          </div>

          <div class="form-group">
          <label for="contenido">Contenido</label>
          <textarea value ="<?php $contenido; ?>" type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido" required>
          </textarea>

          <div class="invalid-feedback">Debe indicar el contenido del mensaje.</div>
          </div>
<?php
echo $footer_correo;
 ?>

  </div>
        <div class="card-footer">

          <button name="enviar_mensaje_todos_btn" type="submit" class="btn btn-primary">Enviar Correo</button>
          </form>
        </div>
</div>
</div>

<h2>Mensajes Enviados</h2>

<!-- notification message -->
<?php if (isset($_SESSION['mensajeria'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['mensajeria'];
						unset($_SESSION['mensajeria']);
					?>
				</h3>
			</div>
<?php endif ?>

<?php mostrar_mensajes(); ?>



	<!-- Origen de Tabla -->
	<?php //lista_mensajes(); ?>
	<!-- Fin de Tabla -->



<script>

   $('#contenido').summernote({
      lang: 'es-ES',
      placeholder: 'Ingrese su contenido',
      tabsize: 2,
      height: 100
});




</script>

<?php  include("includes/footer.php"); ?>
