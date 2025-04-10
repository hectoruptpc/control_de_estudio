<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulo ="Ingreso al Sistema";
include('funciones/functions.php') ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ingreso al Sistema</title>
<?php echo $bootstrap_head; ?>

<!-- FAVICON EN LOGIN -->

<link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="icon" href="images/favicon/favicon.ico">

</head>
<body>

<style>
.carousel-control-prev-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}
</style>

<div class="container text-center">

        <?php echo $logopertenenciag; ?>
       
</div>


</div> <!-- CIERRE DE GRUPO DE BOTONES 2 -->
</nav>
<hr>

<nav class="nav nav-pills justify-content-end"> 
        <div class="btn-group-horizontal" >
            <span class="d-inline-block" data-toggle="popover" data-content="...">
                <a type="link" class="btn btn-outline-danger" href="recuperar_password.php">
                    <i class="fa fa-unlock-alt"></i> Recuperar Contraseña
                </a>
            </span>
            <span class="d-inline-block" data-toggle="popover" data-content="...">
                <a id="afiliarse" class="btn btn-outline-success" href="registro.php">
                    <i class="fas fa-key"></i> Afiliarse al Servicio
                </a>
            </span>
        </div>
    </nav>

<hr>

<div id="main" class="container-fluid">
<div class="row">
<div class="d-none d-sm-block col-md-6 mx-auto">

 <div class="d-flex justify-content-center align-items-center">
      <?php echo $image_responsive; ?>
    </div>

</div>
<div class="col-sm-12 col-md-6">
<h3 class="text-center text-uppercase"> ACCEDA AL SISTEMA</h3>

<form class="was-validated" method="post" action="login.php" autocomplete="on">
<!-- notification message -->
<?php if (isset($_SESSION['msg'])) : ?>
<div class="alert alert-danger" role="alert" >
<h3>
<?php
echo $_SESSION['msg'];
unset($_SESSION['msg']);
?>
</h3>
</div>
<hr>
<?php endif ?>

<?php echo display_error(); ?>

<div class="form-group">
<label for="exampleInputUser">Usuario</label>
<input type="User" class="form-control" id="exampleInputUser" aria-describedby="UserlHelp" placeholder="Usuario o Correo Electronico" name="username" required>
<div class="invalid-feedback">Ingrese su numero de Usuario o Correo Electronico.</div>
</div>
<div class="form-group">
<label for="exampleInputPassword1">Clave:</label>
<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Su Clave de Acceso" name="password" required>
</div>

<div class="btn-group-horizontal" >
<span class="d-inline-block" data-toggle="popover" data-content="Aca podra acceder al sistema las 24 horas del dia, los 365 dias del año.">
<button type="submit" class="btn btn-primary" name="login_btn"> <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <i class="fa fa-sign-in-alt"></i> Acceder <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> </button>
</span>
</div> <!-- CIERRE DE GRUPO DE BOTONES 1 -->
</form>
<br>

</div>
</div>

<hr>
<?php
conteo();
contenido('login');
?>

<script>
animacion = function(){

  document.getElementById('afiliarse').classList.toggle('fade');
}

setInterval(animacion, 200);
</script>


<?php include("usuario/includes/footer.php"); ?>
