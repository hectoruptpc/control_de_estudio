<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema";
	include('../funciones/functions.php');

?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>




    <!-- Page Content -->
<div class="container">
  

<div class="modal fade bd-example-modal-lg" id="overlay">
			<div class="modal-dialog modal-lg">
			<div class="modal-content">
			<?php
			 contenido($modal_inicio);
			?>
			</div>
			</div>
</div>

    <?php

		contenido('mensaje');

    $a = 'comentario';
    mostrar_alert($a);

// CONFIGURAR SI SE ACTIVA O NO UN MES EN PARTIULAR DE FORMA GRATUITA PUEDE USARSE $mes_de_pago_actual
//activar_automatica_mes('febrero/2022','MOVILNET',$_SESSION['user']['idusuario']);

		analizar_mensualidades();


 ?>


<div class="row">
    <div class="col-lg-10 col-sm-12">Su comentario es importante para nosotros, por ello le invitamos a dejarnos un comentario de caracter publico sobre su experiencia en el uso de esta plataforma, puede enviarnos su comentario publico aqui:</div>
    <div class="col-lg-2" col-sm-12><?php enviar_comentario(); ?></div>
</div>

<hr>


<a href="agregar_estudiante.php">
    <button>Generar Estudiante</button>
</a>



    <!-- Icon Cards-->
    <div class="row">
      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-primary o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fas fa-fw fa-comments"></i>
            </div>
            <div class="mr-5">
            <h4>Mensajeria</h4>
                <?php

            @contar_mensajes();
            echo $contador_msn;
            ?></div>
          </div>
          <a data-toggle="popover" data-content="Aca podrá ver los detalles de la mensajeria de su sistema."   class="card-footer text-white clearfix small z-1" href="mensajeria.php">
              <span class="float-left" > Ver Detalles</span>
            <span class="float-right">
                <i class="fa fa-arrow-circle-right"></i>
            </span>
          </a>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-info o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fas fa-fw fa-list"></i>
            </div>
            <div class="mr-5"><h4>Sus Pedidos</h4><?php contar_pedidos(); ?></div>
          </div>
          <a data-toggle="popover" data-content="Aca podrá ver los detalles de sus Pedidos o Ventas." class="card-footer text-white clearfix small z-1" href="ventas.php">
            <span class="float-left" >Ver Detalles</span>
            <span class="float-right">
            <i class="fa fa-arrow-circle-right"></i>
            </span>
          </a>
        </div>
      </div>


      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-success o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fas fa-fw fa-shopping-cart"></i>
            </div>
            <div class="mr-5"><h4>Mensualidad</h4><?php // suma_mensualidad(); ?></div>
          </div>
          <a data-toggle="popover" data-content="Aca podrá ver los detalles de sus Pagos de Mensualidades." class="card-footer text-white clearfix small z-1" href="mensualidades.php">
            <span class="float-left">Ver Detalles</span>
            <span class="float-right">
            <i class="fa fa-arrow-circle-right"></i>
            </span>
          </a>
        </div>
      </div>


      <div class="col-xl-3 col-sm-6 mb-3">
        <div class="card text-white bg-danger o-hidden h-100">
          <div class="card-body">
            <div class="card-body-icon">
              <i class="fas fa-fw fa-life-ring"></i>
            </div>
            <div class="mr-5">

            <?php
            contenido('soporte');
             ?>


</div>          </div>

        </div>
      </div>
    </div>


<hr>


<?php
 contenido($index);
?>


<hr>




<?php include("includes/footer.php"); ?>
