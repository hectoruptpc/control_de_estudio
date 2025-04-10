<?php
$titulopag = "Terminos y Condiciones";
	include('../funciones/functions.php');
$limpio ="A";
?>
<!DOCTYPE html>
<html lang="es">


<style>
   .my-5{
      padding-top: 60px;
   }
</style>
  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">
   <h2>Terminos y Condiciones</h2>
	 <font size=5>
<?php

 contenido($terminos_y_condiciones);
?>
</font>

    </div>
    <?php include("includes/footer.php"); ?>
