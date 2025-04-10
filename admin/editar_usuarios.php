<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestion de Usuarios";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>
<div class="container">


<h2>Editar Usuario</h2>

<!-- notification message -->
<?php if (isset($_SESSION['editar_usuarios'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['editar_usuarios'];
						unset($_SESSION['editar_usuarios']);
					?>
				</h3>
			</div>
<?php endif ?>

	<!-- Origen de Edicion -->
	<?php editar_usuario(); ?>
	<!-- Fin de Edicion -->


</div>
<?php include("includes/footer.php"); ?>
