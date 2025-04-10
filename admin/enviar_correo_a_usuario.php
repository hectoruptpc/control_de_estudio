<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Enviar Mensaje a Usuario";
include('../funciones/functions.php');


if (isset($_REQUEST['id'])) {
    $pedidoid = ($_REQUEST['id']);
  } else {
    $pedidoid = "";
  }

?>
<?php include("includes/head.php"); ?>
<div class="container">




<h2>Enviar Mensaje</h2>



<!-- notification message -->
<?php if (isset($_SESSION['enviar_msn'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['enviar_msn'];
						unset($_SESSION['enviar_msn']);
					?>
				</h3>
			</div>
<?php endif ?>
<?php enviar_msn(); ?>

<script>
         $('#mensaje').summernote({
        placeholder: 'Ingrese su contenido',
        tabsize: 2,
        height: 200
      });
      </script>

</div>



<?php include("includes/footer.php"); ?>
