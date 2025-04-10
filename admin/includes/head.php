<?php

if (!isLoggedIn()) {
    $_SESSION['here'] = $_SERVER['REQUEST_URI'];
    $_SESSION['msg'] = $msn_iniciar_sesion;
	header('location: ../login.php');
	die();
}

if (!isAdmin()) {
    header('location: ../usuario/home.php');
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestion">
<meta name="author" content="Jose Herrera">

<title><?php echo $titulopag; ?></title>

<?php echo $bootstrap_head; ?>
</head>

<body>

<div class="container">
<!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #c2d9fe;">
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

        <li class="nav-item">
          <a title="Ver Usuarios" class="nav-link" href="usuarios.php"><i class="fa fa-users fa-fw"></i> Ver Usuarios</a>
      </li>

       <li id="dropdown-mensualidades" class="nav-item dropdown">
        <a title="Ver Mensualidades" class="nav-link dropdown-toggle" href="mensualidades.php" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-money-bill-alt fa-fw"></i>  Mensualidades <span class="badge badge-light" id="mensualidades"><?php suma_mensualidad();
         echo $pendiente_mensualidad; ?></span>
        </a>
        <div id="dropdown-mens" class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a title="Mensualidades por Aprobar" class="dropdown-item" href="mensualidades.php"><i class="fa fa-money-check fa-fw"></i> Mensualidades por Aprobar</a>
          <a title="Ver Todas las Mensulidades" class="dropdown-item" href="todas_mensualidades.php"><i class="fa fa-list-alt fa-fw"></i> Ver todas las Mensualidades</a>
        </div>
      </li>


      <li id="dropdown-pedidos" class="nav-item dropdown">
        <a title="Ir a Pedidos" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-money-bill-alt fa-fw"></i>  Pedidos <span id="pedidosA"></span>
        </a>


        <div id="dropdown-pedi" class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a title="Ver Detalles de Pedidos" class="dropdown-item" href="pedidos.php"><i class="fa fa-money-check fa-fw"></i> Pedidos
          <span id="pedidosB"></span>
        </a>

        <a title="Ver Todos los Pedidos" class="dropdown-item" href="todos_los_pedidos.php">
        <i class="fa fa-list-alt fa-fw"></i> Todos los Pedidos
        </a>

        <a title="Esperando Operador" class="dropdown-item" href="esperando_operador.php">
        <i class="fa fa-clock fa-fw"></i> Esperando Operador <span id="pedidosC"></span>
        </a>
        </div>


      </li>
            <li id="dropdown-ajustes" class="nav-item dropdown">
        <a title="Ir a Ajustes" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-cogs fa-fw"></i>  Ajustes
        </a>
        <div id="dropdown-ajus" class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a title="Comentarios" class="dropdown-item" href="comentarios.php"><i class="fa fa-comments fa-fw"></i> Comentarios</a>
         <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fa fa-envelope fa-fw"></i> Mensajeria</a>
          <a title="Gestor de Contenido" class="dropdown-item" href="gestor_contenido.php"><i class="fa fa-file-contract fa-fw"></i> Contenido</a>
          <a title="Google Groups" class="dropdown-item" href="gg.php"><i class="fab fa-google-plus-g"></i> Google Groups</a>
          <a title="Creador 1" class="dropdown-item" href="test2.php"><i class="fa fa-wrench fa-fw"></i> Creador 2</a>
          <a title="Creador Mensajes" class="dropdown-item" href="cm.php"><i class="fa fa-wrench fa-fw"></i> Creador Mensajes</a>
          <a title="Reportes" class="dropdown-item" href="reportes.php"><i class="far fa-flag"></i> Reportes</a>
          <a title="Reportes" class="dropdown-item" href="mantenimiento.php"><i class="fas fa-wrench"></i> Mantenimiento</a>
          <div class="dropdown-divider"></div>
          <a title="Salir del Sistema" class="nav-link" href="../index.php?logout='1'"><i class="fas fa-sign-out-alt"></i>

 Salir</a>
        </div>
      </li>

          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
    <div class="row">
<div class="col-sm-6">
    <b class="mt-5"><?php echo 'Bienvenido ' .$_SESSION['user']['nombre']; ?></b>
    </div>

    <div class="col-sm-6">
<?php
  echo '<p class="text-right">';
  echo $fads;
  echo "<br>";
  echo $ip;
  echo "<br>";
  echo $nombrepag;
?>

</div>
</div>

</div>
</div>
