<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Ver Pedido";
	include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>
<div class="container">
<h2>Ver Pedido</h2>


<!-- notification message -->
<?php if (isset($_SESSION['msn_pedidos'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['msn_pedidos'];
						unset($_SESSION['msn_pedidos']);
					?>
				</h3>
			</div>
<?php endif ?>
    <!-- Origen de Tabla -->

    <?php ver_pedido(); ?>
<!-- Fin de Tabla -->





</div>
<?php include("includes/footer.php"); ?>
