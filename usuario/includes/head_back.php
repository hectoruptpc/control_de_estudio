<?php
if (isset($limpio)) {

} else {

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['REQUEST_URI'];
    $_SESSION['msg'] = $msn_iniciar_sesion;
    header('location: ../login.php');
    die();
}

if (isAdmin()) {
    header('location: ../admin/home.php');
}

}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestion de Recargas Telefonicas Venezuela">
<meta name="author" content="J.E Suministros y Mas, C.A.">

<title><?php echo $titulopag; ?></title>

<?php echo $bootstrap_head; ?>

<!-- Custom styles for this template -->

<link href="css/style.css" rel="stylesheet">
<link href="https://virtual.jesuministrosymas.com.ve/u/usuario/css/new.css" rel="stylesheet">


</head>

<body>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-139158384-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-139158384-1');
</script>


  <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4VGGHS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php if (isLoggedIn()) :?>

<?php
$usua = ($_SESSION['user']['username']);
?>

<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #a0ecfc;">
      <div class="container">
        <a title="Cargar Inicio" class="navbar-brand" href="index.php">
          <?php echo $logo_web; ?>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

         <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a title="Cargar Inicio" class="nav-link" href="index.php"><i class="fas fa-home fa-fw"></i> Inicio
                <span class="sr-only">(current)</span>
              </a>
            </li>






<!-- MOVILNET -->
            <li class="nav-item dropdown">
             <a title="Sistema de Recarga MOVILNET" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
             <span class="icon-movilnet"></span>
           </span>
             <span class="badge badge-light"><?php  contar_nueva_recarga_movilnet();

             contar_nueva_recarga_movilnet_sp();
             @$contador_recarga_movilnet = $contador_recarga_movilnet_badge + $contador_recarga_movilnet_sp_badge;
if ($contador_recarga_movilnet==0) {

} else {
  echo $contador_recarga_movilnet;
}


             ; ?></span>
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>MOVILNET</b>
                  <div class="dropdown-divider"></div>
                <a title="Mensualidades Movilnet" class="nav-link" href="mensualidad_movilnet.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                <a title="Hacer recargas Movilnet" class="nav-link" href="recargas_movilnet.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas <span class="badge badge-info"><?php echo contar_nueva_recarga_movilnet().$contador_recarga_movilnet_badge; ?></span></a>
                <a title="Hacer recargas Movilnet sin pagar Mensualidades" class="nav-link" href="recargas_movilnet_sin_plan.php"><i class="fas fa-cogs fa-fw"></i> Recargas Sin Mensualidad <span class="badge badge-info"><?php echo contar_nueva_recarga_movilnet_sp().$contador_recarga_movilnet_sp_badge; ?></span></a>
                </div>
            </li>

<!-- MOVISTAR -->
            <li class="nav-item dropdown ">
             <a title="Sistema de Recarga MOVISTAR" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
             <span class="icon-movistar"></span>
           </span>

             <span class="badge badge-light"><?php  contar_nueva_recarga_movistar();

             contar_nueva_recarga_movistar_sp();
             @$contador_recarga_movistar = $contador_recarga_movistar_badge + $contador_recarga_movistar_sp_badge;
if ($contador_recarga_movistar==0) {

} else {
  echo $contador_recarga_movistar;
}
             ; ?></span>

             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>MOVISTAR</b>
                  <div class="dropdown-divider"></div>
                <a title="Mensualidades Movistar" class="nav-link" href="mensualidad_movistar.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                <a title="Recargas Movistar" class="nav-link" href="recargas_movistar.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas <span class="badge badge-info"><?php echo contar_nueva_recarga_movistar().$contador_recarga_movistar_badge; ?></span></a>
                <a title="Hacer y Ver sus solicitudes de Recargas MOVISTAR sin necesidad de pagar Mensualidades" class="nav-link" href="recargas_movistar_sin_plan.php"><i class="fas fa-cogs fa-fw"></i> Recargas Sin Mensualidad <span class="badge badge-info"><?php echo contar_nueva_recarga_movistar_sp().$contador_recarga_movistar_sp_badge; ?></span></a>
                </div>
            </li>

<!-- DIGITEL -->
            <li class="nav-item dropdown ">
             <a title="Sistema de Recarga DIGITEL" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
             <span class="icon-digitel"></span>
</span>
             <span class="badge badge-light"><?php  contar_nueva_recarga_digitel();

             contar_nueva_recarga_digitel_sp();
             @$contador_recarga_digitel = $contador_recarga_digitel_badge + $contador_recarga_digitel_sp_badge;
if ($contador_recarga_digitel==0) {

} else {
  echo $contador_recarga_digitel;
}
             ; ?></span>

             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>DIGITEL</b>
                  <div class="dropdown-divider"></div>
                <a title="Mensualidades Digitel" class="nav-link" href="mensualidad_digitel.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                <a title="Recargas Digitel" class="nav-link" href="recargas_digitel.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas <span class="badge badge-info"><?php echo contar_nueva_recarga_digitel().$contador_recarga_digitel_badge; ?></span></a>
                <a title="Hacer y Ver sus solicitudes de Recargas DIGITEL sin necesidad de pagar Mensualidades" class="nav-link" href="recargas_digitel_sin_plan.php"><i class="fas fa-cogs fa-fw"></i> Recargas Sin Mensualidad <span class="badge badge-info"><?php echo contar_nueva_recarga_digitel_sp().$contador_recarga_digitel_sp_badge; ?></span></a>
                </div>
            </li>

<!-- DIRECTV -->
            <li class="nav-item dropdown ">
             <a title="Sistema de Recarga DIRECTV" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
             <span class="icon-directv"></span>
</span>
             <span class="badge badge-light"><?php  contar_nueva_recarga_directv();

             contar_nueva_recarga_directv_sp();
             @$contador_recarga_directv = $contador_recarga_directv_badge + $contador_recarga_directv_sp_badge;
if ($contador_recarga_directv==0) {

} else {
  echo $contador_recarga_directv;
}
             ; ?></span>

             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>DIRECTV SIMPLE TV</b>
                  <div class="dropdown-divider"></div>
                <a title="Mensualidades Directv" class="nav-link" href="mensualidad_directv.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                <a title="Recargas Directv" class="nav-link" href="recargas_directv.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas <span class="badge badge-info"><?php echo contar_nueva_recarga_directv().$contador_recarga_directv_badge; ?></span></a>
                <a title="Hacer y Ver sus solicitudes de Recargas DIRECTV SIMPLE TV sin necesidad de pagar Mensualidades" class="nav-link" href="recargas_directv_sin_plan.php"><i class="fas fa-cogs fa-fw"></i> Recargas Sin Mensualidad <span class="badge badge-info"><?php echo contar_nueva_recarga_directv_sp().$contador_recarga_directv_sp_badge; ?></span></a>
                </div>
            </li>


    <!-- INTER CABLE -->

        <!--
            <li class="nav-item dropdown">

             <a title="Sistema de Recarga Intercable" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
               <span class="icon-inter"></span>
             </span>

             <span class="badge badge-light">
<?php
//contar_nueva_recarga_inter();
//contar_nueva_recarga_inter_sp();
//@$contador_recarga_inter = $contador_recarga_inter_badge + //$contador_recarga_inter_sp_badge;
//if ($contador_recarga_inter==0) {
//} else {
//echo $contador_recarga_inter;
//}
//;
?>
</span>

             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>INTERCABLE</b>
                  <div class="dropdown-divider"></div>
                  <a title="Mensualidades Inter" class="nav-link" href="mensualidad_inter.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                  <a title="Recargas Directv" class="nav-link" href="recargas_inter.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas <span class="badge badge-info">
                  <?php
                  // echo
                  //  contar_nueva_recarga_inter().$contador_recarga_inter_badge;
                  ?>
                </span></a>
                  <a title="Hacer y Ver sus solicitudes de Recargas INTER sin necesidad de pagar Mensualidades" class="nav-link" href="recargas_inter_sin_plan.php"><i class="fas fa-cogs fa-fw"></i> Recargas Sin Mensualidad <span class="badge badge-info">
                  <?php
                  // echo
                  //  contar_nueva_recarga_inter_sp().$contador_recarga_inter_sp_badge;
                  ?>
                </span></a>
                </div>
            </li>
-->

            <!-- NETFLIX -->
            <!--
            <span class="icon-netflix"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span></span>
            -->

            <li class="nav-item dropdown">

             <a title="Activacion de Cuentas NETFLIX Venezuela" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span id="testDrive" class="icon-" style="font-family: icomoon; font-size: 20px;">
             <span class="icon-netflix"></span>
</span>
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <b>NETFLIX</b>
                  <div class="dropdown-divider"></div>
                  <a title="Activar Cuentas Netflix" class="nav-link" href="netflix.php"><i class="fas fa-money-bill-alt fa-fw"></i> Activar Cuentas Netflix</a>

                </div>
            </li>



<!-- MENSAJERIA
            <li class="nav-item">
              <a title="Mensajeria" class="nav-link" href="mensajeria.php"><i class="fas fa-envelope fa-fw"></i> Mensajeria <span class="badge badge-light"><?php
              //echo //contar_mensajes();
              //echo $contador_msn_badge; ?></span></a>
            </li>
-->
<!-- AJUSTES -->
            <li class="nav-item dropdown">
             <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fa fa-wrench fa-fw"></i>  Ajustes
             <span class="badge badge-light"><?php echo contar_mensajes().$contador_msn_badge; ?></span>
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a title="Ver su Perfil" class="dropdown-item" href="perfil.php"><i class="fa fa-address-card fa-fw"></i> Perfil</a>

                    <a title="Billetera" class="dropdown-item" href="billetera.php"><i class="fas fa-wallet"></i> Billetera</a>

                    <a title="Directorio" class="dropdown-item" href="https://virtual.jesuministrosymas.com.ve/u/usuario/includes/ag/index.php" target="popup" onClick="window.open(this.href, this.target, 'width=650,height=500'); return false;"><i class="fas fa-address-book"></i> Directorio</a>

                    <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fas fa-envelope fa-fw"></i> Mensajeria <span class="badge badge-info"><?php echo contar_mensajes();
                    echo $contador_msn_badge; ?></span></a>

                    <a title="Comentarios" class="dropdown-item" href="comentarios.php"><i class="fas fa-comments fa-fw"></i> Comentarios </a>

                    <a title="Ayuda Publicitaria" class="dropdown-item" href="publicidad.php"><i class="fa fa-users-cog fa-fw"></i> Publicidades</a>

                    <a title="Ver Ayuda" class="dropdown-item" href="ayuda.php"><i class="fa fa-question-circle fa-fw"></i> Ayuda</a>
                    <!--a title="Revisar su Historial" class="dropdown-item" href="#"><i class="fa fa-history fa-fw"></i> Historial</a-->
                    <div class="dropdown-divider"></div>
                    <a title="Salir del Sistema" class="nav-link" href="../index.php?logout='1'"><i class="fas fa-sign-out-alt"></i>Salir</a>
                </div>
             </li>
        </ul>
        </div>
      </div>
    </nav>

    <div class="container">

    <div class="row">
    <div class="col-sm-6">

    <?php
echo '<p class="font-weight-bold">' . strtoupper(strtolower($_SESSION['user']['nombre'])) . '</p>';

//echo breadcrumbs();
?>

    </div>
    <div class="col-sm-6 text-right">
    <?php echo 'Hoy es: ' .$fads; ?>
    </div>
    </div>

<hr>

<?php status_usuario(); ?>

<?php else : ?>

<!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #a0ecfc;">
    <div class="container">
      <a title="Cargar Inicio" class="navbar-brand" href="../index">
        <?php echo $logo_web; ?>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

       <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a title="Cargar Inicio" class="nav-link" href="../index.php"><i class="fas fa-home fa-fw"></i> Iniciar Sesion
              <span class="sr-only">(current)</span>
            </a>
          </li>
      </ul>
      </div>


    </div>
  </nav>

  <div class="container">

  <div class="row">
  <div class="col-sm-6">

  <?php
echo '<p class="font-weight-bold"> Bienvenido/a </p>';

?>

  </div>
  <div class="col-sm-6 text-right">
  <?php echo 'Hoy es: ' .$fads; ?>
  </div>
  </div>



<?php endif; ?>
