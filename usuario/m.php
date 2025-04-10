<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Digitel";
$titulopag = "Mensualidades Digitel";
include('../funciones/functions.php');
include('../funciones/test.php');

?>
<?php include("includes/head.php"); ?>

<div class="container">
<?php

analizar_mensualidad2(); ?>


<!-- Button trigger modal -->
<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá efectuar el pago de la mensualidad para el uso de la platafoma <?php echo $operador; ?>">
<button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#pago_mensualidad">
<i class="fa fa-money-bill-alt"></i> Declarar Pago de Mensualidad
</button>
</span>



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
<h2 class ="mt-3">Pagos de Mensualidades <?php echo strtoupper ($operador); ?></h2>

<?php lista_pagos_operador(); ?>




<!-- Modal Generar Pedido -->
<div class="modal fade" id="pago_mensualidad" tabindex="-1" role="dialog" aria-labelledby="pago_mensualidadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generarPedidoLabel">Declarar Pago de Mensualidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">



<?php pago_mensualidad_operador(); ?>



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
