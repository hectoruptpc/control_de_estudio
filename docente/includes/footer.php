<footer>

<p class="navbar-text pull-left"><br><br><br><br><br><br><br> </p>

<nav class="navbar fixed-bottom navbar-light bg-light d-none d-sm-block col-sm-12">


<div class="row">
    <div class="col-sm-6 col-xs-12">
    <p class="navbar-text pull-left">&copy <?php echo date('Y');?> - JH
</p>
    </div>
    <div class="col-sm-6 col-xs-12">

<ul class="nav justify-content-end">
  <li class="nav-item">
    <a class="nav-link active" href="<?php echo $pag_web; ?>/usuario/terminos_y_condiciones.php">Terminos y Condiciones</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $pag_web; ?>/usuario/como_funciona.php">Como utilizar el sitio</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="http://www.jesuministrosymas.com.ve/contactenos">Contactenos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo $pag_web; ?>">Soporte</a>
  </li>

</ul>
    </div>
</div>

</nav>
</footer>



<?php echo $bootstrap_footer; ?>

<script type="text/javascript">
$(document).ready(function(){
    $('#pedidosA').load('includes/notificacionpedidos.php #pedidosA');
    $('#pedidosB').load('includes/notificacionpedidos.php #pedidosB');
    $('#pedidosC').load('includes/notificacionpedidos.php #pedidosC');
});
</script>




<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
    trigger: 'hover',
    placement: 'auto'

        });
});

//MENSUALIDADES
$("#dropdown-mensualidades").on("click", function(e){
  e.stopPropagation();
});

$('#dropdown-mensualidades').hover(function() {
  $('#dropdown-mens').delay(200).show();
}, function() {
  $('#dropdown-mens').delay(100).hide(100);
});


// PEDIDOS
$("#dropdown-pedidos").on("click", function(e){
  e.stopPropagation();
});

$('#dropdown-pedidos').hover(function() {
  $('#dropdown-pedi').delay(200).show();
}, function() {
  $('#dropdown-pedi').delay(100).hide(100);
});



//Ajustes
$("#dropdown-ajustes").on("click", function(e){
  e.stopPropagation();
});

$('#dropdown-ajustes').hover(function() {
  $('#dropdown-ajus').delay(200).show();
}, function() {
  $('#dropdown-ajus').delay(100).hide(100);
});

</script>

</body>
</html>
