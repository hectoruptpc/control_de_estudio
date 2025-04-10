<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$titulo ="Registro en el Sistema";
include('funciones/functions.php');




 ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $titulo; ?></title>
    <?php
echo $bootstrap_head; ?>

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
<hr>

<div class="container text-center">

        <?php echo $logopertenenciag; ?>
       
</div>

<hr>
<div class="container">
	<div class="row">
	<div class="col-6">
	</div>

	<div class="col-6">



<nav class="nav nav-pills nav-fill ">
<div class="btn-group-horizontal" >
  <span class="d-inline-block" data-toggle="popover" data-content="Si desea recuperar su contraseña de acceso, o nunca a ingresado al sistema puede hacerlo usando este boton para crear su contraseña de acceso.">
  <a type="link" class="btn btn-outline-danger" href="recuperar_password.php">
  <i class="fa fa-unlock-alt"></i> Recuperar Contraseña</a>
</span>

  <span class="d-inline-block" data-toggle="popover" data-content="Acceda a la Plataforma.">
  <a class="btn btn-success" href="login.php">
  <i class="fa fa-key"></i> Acceder a la Plataforma</a>
  </span>
		</div>
	</nav>

</div>
</div>


</div> <!-- CIERRE DE GRUPO DE BOTONES 2 -->
</nav>
<hr>


<div id="main" class="container-fluid">
  <div class="row">
    <div class="d-none d-sm-block col-md-6">

   <div class="d-flex justify-content-center align-items-center">
      <?php echo $image_responsive; ?>
    </div>


    </div>
    <div class="col-sm-12 col-md-6">
    <h3 class="text-center text-uppercase"> REGISTRESE EN EL SISTEMA</h3>

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

<font size=4>
  <?php
  echo ' <form autocomplete="on" class="was-validated" method="post" id="form" name="form" action= "registro.php">

  <div class="form-group">
  <label for="idusuario">CEDULA O RIF</label>
  <input onkeyup="mayus(this);" type="text" pattern="[V,J,G,E]{1}[-][0-9]{7,9}" placeholder="V-12345678 J-12345678 E-12345678"
  title = "Use el formato V-12345678 V-12345678 J-12345678 E-12345678"
  class="form-control" id="idusuario" aria-describedby="idusuario" placeholder="Ingrese el idusuario" name="idusuario" value="';
  //echo $idusuario;
  echo '" required>
  <div class="invalid-feedback">Debe indicar el numero de Cedula o Rif en el formato V-12345678.</div>
  </div>

  <div class="form-group">
  <label for="nombre_usuario">NOMBRE COMPLETO</label>
  <input onkeyup="mayus(this);" type="text" class="form-control" id="nombre_usuario" aria-describedby="nombre_usuario" placeholder="Ingrese su Nombre Completo" pattern="[A-Z ]{7,50}" title = "Use solo letras Mayusculas, escriba su nombre completo." name="nombre_usuario" value="';
  //echo $nombre_usuario;
  echo '" required>
  <div class="invalid-feedback">Debe indicar Nombre completo el letra MAYUSCULA, si su nombre no esta en capacidad de contratar, es decir no posee RIF su usuario sera dado de baja.</div>
  </div>

  <div class="form-group">
  <label for="email_usuario">EMAIL</label>
  <input onkeyup="minusc(this);" type="email" pattern="[a-zA-Z0-9]{0,}([.]?[_.a-zA-Z0-9]{1,})[@](gmail.com|hotmail.com|yahoo.com|yahoo.es|outlook.es|outlook.com|hotmail.es|cantv.net|cantv.com)" title="Debe utilizar solo correos gmail, yahoo, hotmail o cantv" class="form-control" id="email_usuario" aria-describedby="email_usuario" placeholder="Ingrese su Email" name="email_usuario" value="';
  //echo $email_usuario;
  echo '" required>
  <div class="invalid-feedback">Debe indicar el Email del Usuario, solo se admiten correos gmail, yahoo, hotmail o cantv. <br><b>RECOMENDAMOS AMPLIAMENTE USAR CORREOS <a href="http://www.gmail.com" target="_BLANK">GMAIL</a></b></div>
  </div>

  <div class="form-group">
  <label for="telefono_usuario">TELEFONO LOCAL</label>
  <input pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" type="tel" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese el Telefono del Nuevo Usuario" name="telefono_usuario" value="';
  //echo $telefono_usuario;
  echo '" required>
  <div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
  </div>

  <div class="form-group">
  <label for="celular_usuario">CELULAR</label>
  <input pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" type="tel" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese el Celular del Nuevo Usuario" name="celular_usuario" value="';
  //echo $celular_usuario;
  echo '" required>
  <div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
  </div>



    <div class="col-auto my-1">

       <div class="custom-control custom-checkbox mr-sm-2">
         <h3>FAVOR LEA ANTES DE ACEPTAR</h3>
         <input type="checkbox" class="custom-control-input" id="customControlAutosizing1" title="Debe marcar la casilla para poder continuar." required>

         <label class="custom-control-label" for="customControlAutosizing1">Marque esta casilla indicando que usted esta de acuerdo que para usar la plataforma debe efectuar un pago Mensual para uso de la Misma y luego de aprobado su pago de Mensualidad usted debe efectuar los respectivos pagos de sus Pedidos o solicitudes de Recargas.</label>
       </div>
     </div>


     <div class="col-auto my-1">
        <div class="custom-control custom-checkbox mr-sm-2">
          <input type="checkbox" class="custom-control-input" id="customControlAutosizing2" title="Debe marcar la casilla para poder continuar." required>
          <label class="custom-control-label" for="customControlAutosizing2">Marque esta casilla en señal de comprender que usted ha suministrado datos reales y que los mismos son correctos.</label>
        </div>
      </div>


    <div class="col-auto my-1">
       <div class="custom-control custom-checkbox mr-sm-2">
         <input name="customControlAutosizing3"
       type="checkbox" class="custom-control-input" id="customControlAutosizing3" required>
         <label class="custom-control-label" for="customControlAutosizing3"> Declara que ha leido y Acepta Los   <!-- Extra large modal -->
           <a href="#" class="link" data-toggle="modal" data-target=".bd-example-modal-xl">Terminos & Condiciones del Sitio</a></label>
       </div>
     </div>



<hr>

  <button type="submit" class="enviar btn btn-primary" id="agregar_usuario_btn" name="agregar_usuario_btn" disabled="disabled">Enviar</button>

  </form>';
   ?>
</font>
  </div>
  </div>
<hr>
<?php conteo(); ?>


  <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Terminos y Condiciones del Sitio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <font size=4>
        <?php
         contenido($terminos_y_condiciones);
        ?>
      </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>



  <script>
  function minusc(e) {
    e.value = e.value.toLowerCase();
}
  function mayus(e) {
    e.value = e.value.toUpperCase();
}
  $(document).ready(function(){
      $('#save').prop('disabled', true);

      $('#customControlAutosizing3').click(function(){
          if($(this).is(':checked'))
          {
              $('.enviar').prop('disabled', false);
          }
          else
          {
              $('.enviar').prop('disabled', true);
          }
      });
  });


</script>


<?php include("usuario/includes/footer.php"); ?>
