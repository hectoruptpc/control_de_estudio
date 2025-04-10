<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Rechazar un Pago";
include('../funciones/functions.php');

if (isset($_REQUEST['id'])) {
    $id = ($_REQUEST['id']);
  } else {
    $id = "";
  }

?>
<?php include("includes/head.php"); ?>
<div class="container">




<h2>Rechazar un Pago</h2>



<!-- notification message -->
<?php if (isset($_SESSION['rechazar'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['rechazar'];
						unset($_SESSION['rechazar']);
					?>
				</h3>
			</div>
<?php endif ?>
<?php rechazar_pagos(); ?>

<script>
         $('#motivo').summernote({
        placeholder: 'Ingrese su contenido',
        tabsize: 2,
        height: 200
      });
      </script>

</div>



<?php include("includes/footer.php"); ?>
