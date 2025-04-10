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
<meta name="description" content="Plataforma">
<meta name="author" content="Jose Herrera">

<title><?php echo $titulopag; ?></title>

<?php echo $bootstrap_head; ?>

<!-- Custom styles for this template -->

<link href="css/style.css" rel="stylesheet">
<link href="css/new.css" rel="stylesheet">


</head>

<body>


<?php if (isLoggedIn()) :?>

<?php
$usua = ($_SESSION['user']['username']);
?>

<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #a0ecfc;">
      <div class="container">
        <a title="Cargar Inicio" class="navbar-brand" href="index.php">
          <?php echo $logopertenencia; ?>
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


             <!-- CLIENTES -->
             <li class="nav-item dropdown">
             <a title="CLIENTES" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-users"></i>  Clientes
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a title="Materia Prima" class="dropdown-item" href="clientes.php"><i class="fa fa-user"></i> Clientes</a>


                </div>
             </li>

              <!-- CLIENTES -->
              <li class="nav-item dropdown">
             <a title="VENTAS" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fa fa-store"></i>  Ventas
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a title="Materia Prima" class="dropdown-item" href="ventas.php"><i class="fa fa-cash-register"></i> Ventas</a>
                
                    <a title="Materia Prima" class="dropdown-item" href="precios.php"><i class="fas fa-dollar-sign"></i> Precios</a>

                


                </div>
             </li>



            <!-- CALCULOS -->
            <li class="nav-item dropdown">
             <a title="CALCULOS" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-calculator"></i>  Calculos
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a title="Materia Prima" class="dropdown-item" href="productosdelimpieza.php"><i class="fa fa-atom"></i> Calculo</a>


                </div>
             </li>







<!-- QUIMICOS -->
            <li class="nav-item dropdown">
             <a title="PRODUCTOS QUIMICOS" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fa fa-boxes"></i>  Inventarios
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <a title="Materia Prima" class="dropdown-item" href="materiaprima.php"><i class="fa fa-warehouse"></i> Materia Prima</a>

                    <a title="Materia Prima" class="dropdown-item" href="productoterminado.php"><i class="fa fa-pump-soap"></i> Producto Terminado</a>


                </div>
             </li>






<!-- REPORTES -->
<li class="nav-item dropdown">
             <a title="REPORTES" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-diagnoses"></i>  Extras
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                <a title="Ver su Perfil" class="dropdown-item" href="conectividad.php"><i class="fas fa-code-branch"></i> API</a>

                <a title="Reportes" class="dropdown-item" href="reportes.php"><i class="far fa-flag"></i> Reportes</a>

                    <a title="Reportes" class="dropdown-item" href="mantenimiento.php"><i class="fas fa-wrench"></i> Mantenimiento</a>

                    <a title="AcercaDe" class="dropdown-item" href="acercade.php"><i class="fas fa-grin-stars"></i> Acerca de</a>

                    <a title="AcercaDe" class="dropdown-item" href="como_funciona.php"><i class="fas fa-laptop"></i> Como Usar el Sitio</a>

                


                </div>
             </li>







<!-- AJUSTES -->
            <li class="nav-item dropdown">
             <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-cogs"></i>  Ajustes
             <span class="badge badge-light"><?php echo @contar_mensajes().$contador_msn_badge; ?></span>
             </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                

                    <a title="Ver su Perfil" class="dropdown-item" href="perfil.php"><i class="fa fa-address-card fa-fw"></i> Perfil</a>

                    <a title="Billetera" class="dropdown-item" href="billetera.php"><i class="fas fa-wallet"></i> Billetera</a>

                    <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fas fa-envelope fa-fw"></i> Mensajeria <span class="badge badge-info"><?php echo @contar_mensajes();
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
