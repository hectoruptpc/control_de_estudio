<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Google Groups";
    include('../funciones/functions.php');
    include('../funciones/creador.php');

?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

    <!-- Page Content -->
    <div class="container">

      <div class="alert alert-info">
Agregar nuevos miembros <a target="_blank" href="https://groups.google.com/forum/#!managemembers/gestionderecargas/add"><b>AQUI</b></a>
      </div>

      <?php

      $init= 0;
      $limit_end= 10;

      if (isset($_GET['p']))
      $ini=$_GET['p'];
      else
      $ini=1;
      $init = ($ini-1) * $limit_end;

       $count_query="SELECT COUNT(*) FROM `users`";
$query = "SELECT email FROM `users` ORDER BY `id` ASC LIMIT  $init, $limit_end";
$results = mysqli_query($db, $query);
$result_count = mysqli_query($db, $count_query);

$num = $db->query($count_query);
$x = $num->fetch_array();
$total = ceil($x[0]/$limit_end);

echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";
echo '<p class="alert alert-dark" id="p1">';
while ($valores = mysqli_fetch_array($results)) {
echo $valores['email'].', ';
}
?>
</p>
<button id="2" class="btn btn-primary" onclick="copiarAlPortapapeles('p1')">Copiar Contactos 2</button>
<hr>


<div class="alert alert-warning" role="alert">
    <?php contenido('googlegroups') ?>
</div>
<div>Para editar este contenido ir a <a class="btn btn-info" href="gestor_contenido.php">GESTOR DE CONTENIDO</a> y editar la seccion <b>googlegroups</b></div>

    <script type="text/javascript">

function copiarAlPortapapeles(id_elemento) {
  var aux = document.createElement("input");
  aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
}

function ejecutar(idelemento){
  var aux = document.createElement("div");
  aux.setAttribute("contentEditable", true);
  aux.innerHTML = document.getElementById(idelemento).innerHTML;
  aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
  document.body.appendChild(aux);
  aux.focus();
  document.execCommand("copy");
  document.body.removeChild(aux);
}

    </script>

    </div>
    <?php include("includes/footer.php"); ?>
