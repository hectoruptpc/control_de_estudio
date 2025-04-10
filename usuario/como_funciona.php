<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$titulopag = "Como Funciona el Sitio";
    include('../funciones/functions.php');
    $limpio ="A";
?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">

 <?php  contenido($como_usar); ?>

 

    </div>
    <?php include("includes/footer.php"); ?>
