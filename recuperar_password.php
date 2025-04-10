<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$titulo ="Olvido Password";
include('funciones/functions.php');

$email =   $_REQUEST['email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : "";


if (empty($email)) {
    //echo "LO SENTIMOS";
   // array_push($errors, "Debe indicar un correo electronico registrado en el sistema.");
    $_SESSION['msg_recuperar']  = 'Si posees Usuario y Clave puede acceder e iniciar sesion <a href="login.php" class="link">AQUI</a><br>';
    //header("location: login.php");

}
else {
    global $db, $email;

$sql = "SELECT * FROM users
WHERE email = '$email' ";
   $result = mysqli_query($db, $sql);
   $rows = mysqli_num_rows($result);
   $row = mysqli_fetch_assoc($result);

   if (!$rows)   {

    array_push($errors, "El correo<b> $email </b>indicado por usted no existe en nuestra base de datos.");
    //$_SESSION['msg_recuperar']  = 'El correo indicado por usted no existe en nuestra base de datos<br>';

   } else {

    //array_push($errors, "El correo si existe.");

    $rowid = $row['id'];
    $rowControl = $row['control'];
    $nombre_usuario = $row['nombre'];
    $username = $row['username'];
    $email_usuario = $row['email'];
    $status = $row['status'];
    $motivo = $row['motivo_bloqueo'];

    if ($status == 0) {
      $_SESSION['msg']  ='<i class="fa fa-ban"></i> No puedes recuperar contraseña porque tu usuario esta bloqueado por el siguiente motivo: '.$motivo.'<br>';
    } else {

$email = $email_usuario;
$nombre = $nombre_usuario;
$asunto = "Configure su Contraseña de acceso al Sistema Gestion de Recargas Telefonicas";
$cuerpo = "Hola $nombre: <br/><br/>
Usted ha sido registrado en la Plataforma Sistema Gestion de Recargas Telefonicas donde podra recargar servicios prepagados de las operadoras Movilnet, Movistar y Digitel. De abrir nuevas relaciones comerciales con otros operadores sera notificado desde la plataforma.<br><br>Ahora debes crear tu contraseña<br>";
  $cuerpo .= '<span style="background-color: #FFFD01; color: #fff; display: inline-block; padding: 10px 20px; font-weight: bold; border-radius: 10px;"><strong><a href="https://virtual.jesuministrosymas.com.ve/u/crear_password.php?id='.$rowid.'&control='.$rowControl.'" target="_BLANK"><i class="fa fa-key"></i> CREAR CONTRASEÑA AQUI</a></strong></span><br><br>';

$cuerpo .= "Ya en breve podras acceder al sistema y empezar a disfrutar de este servicio.";
enviarEmail($email, $nombre, $asunto, $cuerpo);

//$_SESSION['msg_recuperar']  =' Hemos enviado un Correo con Instrucciones para que cree su contraseña.<br>puede acceder e iniciar sesion <a href="login.php" class="link">AQUI</a><br>';
$_SESSION['msg']  ='<i class="fa fa-envelope"></i> Hemos enviado un Email a '.$email.' con Instrucciones para que cree su contraseña.<br>';

}
  header('location: login.php');
}

}

?>
<!DOCTYPE html>
<html>
<head>
  <?php echo $bootstrap_head; ?>
  <!-- FAVICON EN LOGIN -->


  <link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png" sizes="180x180">
  <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">

  	<link rel="icon" href="images/favicon/favicon.ico">
</head>
<body>
<div class="container text-center">

        <?php echo $logopertenenciag; ?>
       
</div>
<hr>
<div class="container-fluid">
  <div class="row">
    <div class="d-none d-sm-block col-md-6">

      <?php
      echo $image_responsive;
      ?>
    </div>
    <div class="col-sm-12 col-md-6">
    <h3 class="text-center text-uppercase">Olvido su Contraseña</h3>

    <form class="was-validated" method="post" action="recuperar_password.php" autocomplete="off">
<!-- notification message -->
<?php if (isset($_SESSION['msg_recuperar'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['msg_recuperar'];
						unset($_SESSION['msg_recuperar']);
					?>
				</h3>
			</div>
<?php endif ?>

<?php echo display_error();
echo "<br>";
 ?>


  <div class="form-group">
    <label for="email">Su Email</label>
    <input pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.com|cantv.net)" title="Debe utilizar solo correos gmail, outlook, yahoo, hotmail o cantv" type="email" class="form-control" id="email" placeholder="Indique su Email" name="email" required>
    <div class="invalid-feedback">Indique su Email registrado en el sistema </div>
  </div>

  <button type="submit" class="btn btn-danger" ><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
  <i class="fa fa-key"></i> Recuperar Password <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span></button>

</form>

    </div>
  </div>


  <hr>



<?php include("usuario/includes/footer.php"); ?>
