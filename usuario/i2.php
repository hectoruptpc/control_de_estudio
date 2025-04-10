<?php
$titulopag = "Sistema de Gestion";
	include('../funciones/functions.php');

?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">

    <?php
    $a = 'comentario';
    mostrar_alert($a);
 ?>

<div class="row">
    <div class="col-lg-10 col-sm-12">Su comentario es importante para nosotros, por ello le invitamos a dejarnos un comentario de caracter publico sobre su experiencia en el uso de esta plataforma, puede enviarnos su comentario publico aqui:</div>
    <div class="col-lg-2" col-sm-12><?php enviar_comentario(); ?></div>
</div>

<hr>



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

            contar_mensajes();
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
          <a data-toggle="popover" data-content="Aca podrá ver los detalles de sus Pedidos Movilnet." class="card-footer text-white clearfix small z-1" href="pedidos_movilnet.php">
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
            <div class="mr-5"><h4>Mensualidad</h4><?php suma_mensualidad(); ?></div>
          </div>
          <a class="collapsed card-footer text-white clearfix small z-1" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">
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
            <div class="mr-5"><h4>Soporte</h4>
Area de Soporte, si una tarjeta da mensaje de codigo usado o codigo invalido.!</div>          </div>
          <a data-toggle="popover" data-content="Aca podrá acceder al area de soporte de tarjetas UN1CAS de Movilnet." class="card-footer text-white clearfix small z-1" target = "_blank" href="http://www.movilnet.com.ve/sitio/minisitios/tarjetaunica/preguntas_frecuentes.html">
            <span class="float-left">Ir a Soporte</span>
            <span class="float-right">
            <i class="fa fa-arrow-circle-right"></i>
            </span>
          </a>
        </div>
      </div>
    </div>


    <p>
  <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Toggle first element</a>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Toggle second element</button>
  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Toggle both elements</button>
</p>



        <div class="collapse multi-collapse" id="multiCollapseExample1">
          <div class="card card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
          </div>
        </div>
        <div class="collapse multi-collapse" id="multiCollapseExample2">
          <div class="card card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
          </div>
        </div>



<hr>
<?php
planes_movilnet();
planes_operadoras();
?>

      <?php
       //comentarios();
 contenido($index);
?>


<hr>



      <div class="modal fade bd-example-modal-lg" id="overlay">
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <?php
 contenido($modal_inicio);

?>
    </div>
</div>



    </div>

    <script>
    $('.carousel').carousel({
  interval: 8000
})
    </script>
    <?php include("includes/footer.php"); ?>
