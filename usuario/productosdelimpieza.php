<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Productos de Limpieza";
$titulopag = "Productos de Limpieza";
include('../funciones/functions.php');

?>

<?php include("includes/head.php"); ?>

<div id="contenido"></div>


<?php include("includes/footer.php"); ?>



<script>
  document.addEventListener('DOMContentLoaded', function() {
  //  updateP();
  //  updateIndicator();
    formulas();
    primerselect();

    // Aquí puedes agregar más funciones que quieras ejecutar al cargar la página
});

</script>

<script type="text/javascript" src="../funciones/js/kernel_web.js"></script>

