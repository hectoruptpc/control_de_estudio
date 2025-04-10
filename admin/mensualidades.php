<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Mensualidades por Aprobar";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>
<div class="container">

<?php  if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower($_REQUEST['busqueda']);
  } else {
    $busqueda = "";
  } ?>

<div class="row  mb-3">
<div class="col-xs-12 col-sm-6">

<h2>Mensualidades Por Aprobar</h2>
</div>
<div class="col-xs-12 col-sm-6">
<form action="mensualidades.php" method="get">
		<div class="input-group">
		<input name="busqueda" id="busqueda" type="text" class="form-control" placeholder="Realizar Busqueda" aria-label="buscar" aria-describedby="button-addon2" value="<?php echo $busqueda; ?>">
		<div class="input-group-append">
			<button  value="buscar" class="btn btn-outline-secondary" type="submit" id="buscar">Buscar</button>
		</div>
		</form>
		</div></div>
		<div class="container">

<!-- notification message -->
<?php if (isset($_SESSION['pago_mensualidad'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['pago_mensualidad'];
						unset($_SESSION['pago_mensualidad']);
					?>
				</h3>
			</div>
<?php endif ?>

<!-- notification message -->
<?php if (isset($_SESSION['msn'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['msn'];
						unset($_SESSION['msn']);
					?>
				</h3>
			</div>
<?php endif ?>

 <!-- Origen de Tabla -->
 <?php lista_pagos_mes(); ?>
	<!-- Fin de Tabla -->
    	<!-- Origen de Resumen Pagos Mes -->
        <h2>Resumen</h2>
	<?php detallado_suma_mensualidad(); ?>
	<!-- Fin de Resumen Pagos Mes -->



</div>

<script language='javascript'>
$('.summernote').summernote({
    placeholder: 'Ingrese su contenido',
    tabsize: 2,
    height: 200  });
</script>

<script>
  $('.table').stacktable();
</script>

<?php include("includes/footer.php"); ?>
