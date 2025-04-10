  <?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Publicidad";

include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>

<div class="container">
  <?php
  $a = 'comentario';
  mostrar_alert($a);
?>

<h1><?php echo $titulopag; ?></h1>
<?php contenido('publicidad'); ?>


<?php include("includes/footer.php"); ?>
