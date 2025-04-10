<?php
$titulo ="Ingreso al Sistema";
include('funciones/functions.php') ?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-139158384-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-139158384-1');
</script>


	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4VGGHS');</script>
<!-- End Google Tag Manager -->
	<title>Ingreso al Sistema</title>
    <?php
echo $bootstrap_head; ?>

<!-- FAVICON EN LOGIN -->


<link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">

	<link rel="icon" href="images/favicon/favicon.ico">


</head>
<body>

	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4VGGHS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


  <style>
.carousel-control-prev-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}
</style>
<hr>
<div class="container">
	<div class="row">
	<div class="col-6">
	</div>

	<div class="col-6">



<nav class="nav nav-pills nav-fill ">
<div class="btn-group-horizontal" >
  <span class="d-inline-block" data-toggle="popover" data-content="Si desea recuperar su contraseña de acceso, o nunca a ingresado al sistema puede hacerlo usando este boton para crear su contraseña de acceso.">
  <a type="link" class="btn btn-outline-danger" href="recuperar_password.php">
  <i class="fa fa-unlock-alt"></i> Recuperar Contraseña</a>
</span>

  <span class="d-inline-block" data-toggle="popover" data-content="Si aun no posee credenciales de acceso puede solicitarlas aqui.">
  <a id="afiliarse" class="btn btn-outline-success" href="http://bit.ly/registro_je">
   <i class="fas fa-key"></i> Afiliarse al Servicio</a>
  </span>

  <script>
  animacion = function(){

  document.getElementById('afiliarse').classList.toggle('fade');
}

setInterval(animacion, 200);
  </script>
		</div>
	</nav>

</div>
</div>


</div> <!-- CIERRE DE GRUPO DE BOTONES 2 -->
</nav>
<hr>


<div id="main" class="container-fluid">
  <div class="row">
    <div class="d-none d-sm-block col-md-6">

    <?php
    echo $image_responsive;
    echo $logo_operadoras_center;
    ?>


    </div>
    <div class="col-sm-12 col-md-6">
    <h3 class="text-center text-uppercase"> WEB EN MANTENIMIENTO</h3>
<img src="" alt="">

<br>

  </div>
  </div>


  <script>

    $('.carousel').carousel({
  interval: 8000
})
</script>


<?php include("usuario/includes/footer.php"); ?>
