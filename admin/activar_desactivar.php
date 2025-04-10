<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Activar - Desactivar Usuario";
include('../funciones/functions.php');

if (isset($_REQUEST['id'])) {
    $userid = ($_REQUEST['id']);
  } else {
    $userid = "";
  }

?>
<?php include("includes/head.php"); ?>
<div class="container">




<h2>Activar - Desactivar un Usuario</h2>



<!-- notification message -->
<?php if (isset($_SESSION['activar_desactivar'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['activar_desactivar'];
						unset($_SESSION['activar_desactivar']);
					?>
				</h3>
			</div>
<?php endif ?>
<?php activar_desactivar(); ?>

<script>
         $('#motivo').summernote({
        placeholder: 'Ingrese su contenido',
        tabsize: 2,
        height: 200
      });
      </script>

</div>



<?php include("includes/footer.php"); ?>
