<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Pago Mensualidad";
include('../funciones/functions.php');

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['PHP_SELF'];
    $_SESSION['msg'] = "Debes iniciar sesión primero";
    header('location: ../login.php');
    die();
	
}



?>
<?php include("includes/head.php"); ?>
<div class="container">
<div class= "row">
<div class="col-xs-12 col-sm-4 card bg-light">
  <div class="card-header"><h3>PLAN BASICO</h3></div>
  <div class="card-body">
    <h5 class="card-title">Caracteristicas:</h5>
    <p class="card-text">
    <ul>
      <li>Los montos por cada solicitud se limitan al minimo permitido.</li>
      <li>Tiempo de espera entre 3 y 4 dias.</li>
    </ul>
    </p>
  </div>
</div>
<div class="col-xs-12 col-sm-4"></div>
<div class="col-xs-12 col-sm-4 card text-white bg-secondary">
  <div class="card-header"><h3>PLAN AVANZADO</h3></div>
  <div class="card-body">
    <h5 class="card-title">Caracteristicas:</h5>
    <p class="card-text">
    <ul>
      <li>Los montos por cada solicitud poseen un limite superior al minimo permitido.</li>
      <li>Tiempo de espera entre 1 y 2 dias.</li>
    </ul>
    </p>
  </div>
</div>
</div>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#pago_mensualidad">
  Generar Pago
</button>

<h2>Mensualidades</h2>

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

	<!-- Origen de Tabla -->
	<?php lista_pagos_mes(); ?>
	<!-- Fin de Tabla -->

<!-- Modal Generar Pedido -->
<div class="modal fade" id="pago_mensualidad" tabindex="-1" role="dialog" aria-labelledby="pago_mensualidadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generarPedidoLabel">Generar Pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">

<?php pago_mensualidad(); ?>
        
     

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
 <!-- Fin de Modal Generar Pedido -->

</div>
<?php include("includes/footer.php"); ?>
