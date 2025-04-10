<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Mantenimiento";
$titulopag = "Area en Mantenimiento";
include('../funciones/functions.php');
$limpio ="A";

?>
<?php include("includes/head.php"); ?>
<div class="container">
<!-- Sección Acerca de -->
<section id="acerca-de" class="py-5 bg-light">  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 order-2 order-lg-1"> 
        <?php echo $logo_empresag;?>
      </div> 
      <div class="col-lg-6  order-1 order-lg-2">
        <h2 class="display-4 mb-3">Acerca de Sistema de Gestion de Productos de Limpieza</h2> 
        <p class="lead"> Presentamos una solucion practica y con el uso de tecnologia de punta que permite gestionar de manera efectiva la produccion de Productos de Limpieza.</p>
        <p> Sabemos lo importante que es usar un sistema que sea de manera practico y de utilidad, por ello desde Emprendimiento Jose Herrera 32 ofrecemos nuestros desarrollos bajo tecnologias GNU, mas detalles sobre licencias de distribucion ver al final. </p>
        <ul class="list-unstyled">
          <li> <i class="fas fa-check-circle text-primary mr-2"></i>  Practico </li>
          <li> <i class="fas fa-check-circle text-primary mr-2"></i> Responsive  </li>
          <li> <i class="fas fa-check-circle text-primary mr-2"></i>  Intuitivo  </li>
        </ul>
        <a href="https://emprendimientojh.blogspot.com/" target="_blank" class="btn btn-primary btn-md mt-4">Aprende Más sobre Nosotros </a> 
      </div> 
    </div>
  </div>
</section>
</div>

<?php include("includes/footer.php"); ?>