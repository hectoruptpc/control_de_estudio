<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Administrar Comentarios";
    include('../funciones/functions.php');
    
?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">


      <?php
admin_comentarios();
?>


    </div>
    <?php include("includes/footer.php"); ?>
