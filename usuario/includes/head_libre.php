<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="es-Es" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF8">
<meta http-equiv="Content-type" content="text/html; charset=UTF8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Gestion de Recargas Telefonicas Venezuela">
<meta name="author" content="J.E Suministros y Mas, C.A.">

<title><?php echo $titulopag; ?></title>


<?php
echo $bootstrap_head; ?>

<!-- Custom styles for this template -->

<link href="css/style.css" rel="stylesheet">



</head>



<body>


  <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #a0ecfc;">
        <div class="container">
          <a title="Cargar Inicio" class="navbar-brand" href="index.php">
            <?php echo $logo_web; ?>
          </a>

        <?php if (isLoggedIn()) :?>

          <?php
          $usua = ($_SESSION['user']['username']);
          ?>



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
                       <span class="icon-movilnet"></span> Movilnet
                       </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a title="Mensualidades Movilnet" class="nav-link" href="mensualidad_movilnet.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                          <a title="Hacer y Ver sus Pedidos de Tarjetas UN1CAS" class="nav-link" href="pedidos_movilnet.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Pedidos</a>
                          </div>
                      </li>

          <!-- MOVISTAR -->
                      <li class="nav-item dropdown ">
                       <a title="Sistema de Recarga MOVISTAR" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <span class="icon-movistar"></span> Movistar
                       </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a title="Mensualidades Movistar" class="nav-link" href="mensualidad_movistar.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                          <a title="Recargas Movistar" class="nav-link" href="recargas_movistar.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas</a>
                          </div>
                      </li>

          <!-- DIGITEL -->
                      <li class="nav-item dropdown ">
                       <a title="Sistema de Recarga DIGITEL" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <span class="icon-digitel"></span> Digitel
                       </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                          <a title="Mensualidades Digitel" class="nav-link" href="mensualidad_digitel.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                          <a title="Recargas Digitel" class="nav-link" href="recargas_digitel.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas</a>
                          </div>
                      </li>


          <!-- DIRECTV -->
                      <li class="nav-item dropdown ">
                       <a title="Sistema de Recarga DIRECTV" class="nav-link dropdown-toggle disabled" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <span class="icon-directv"></span> Directv
                       </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
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
                       <i class="fa fa-cogs fa-fw"></i>  Ajustes
                       <span class="badge badge-light"><?php echo contar_mensajes();
                        echo $contador_msn_badge; ?></span>
                       </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a title="Ver su Perfil" class="dropdown-item" href="perfil.php"><i class="fa fa-address-card fa-fw"></i> Perfil</a>
                              <a title="Mensajeria" class="dropdown-item" href="mensajeria.php"><i class="fas fa-envelope fa-fw"></i> Mensajeria <span class="badge badge-info"><?php echo contar_mensajes();
                        echo $contador_msn_badge; ?></span></a>
                              <a title="Ver Ayuda" class="dropdown-item" href="ayuda.php"><i class="fa fa-question-circle fa-fw"></i> Ayuda</a>
                              <!--a title="Revisar su Historial" class="dropdown-item" href="#"><i class="fa fa-history fa-fw"></i> Historial</a-->
                              <div class="dropdown-divider"></div>
                              <a title="Salir del Sistema" class="nav-link" href="../index.php?logout='1'"><i class="fas fa-sign-out-alt"></i>Salir</a>
                          </div>
                       </li>


        <?php else : ?>

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
               <span class="icon-movilnet"></span> Movilnet
               </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Mensualidades Movilnet" class="nav-link" href="mensualidad_movilnet.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                  <a title="Hacer y Ver sus Pedidos de Tarjetas UN1CAS" class="nav-link" href="pedidos_movilnet.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Pedidos</a>
                  </div>
              </li>

      <!-- MOVISTAR -->
              <li class="nav-item dropdown ">
               <a title="Sistema de Recarga MOVISTAR" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span class="icon-movistar"></span> Movistar
               </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Mensualidades Movistar" class="nav-link" href="mensualidad_movistar.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                  <a title="Recargas Movistar" class="nav-link" href="recargas_movistar.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas</a>
                  </div>
              </li>

      <!-- DIGITEL -->
              <li class="nav-item dropdown ">
               <a title="Sistema de Recarga DIGITEL" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span class="icon-digitel"></span> Digitel
               </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a title="Mensualidades Digitel" class="nav-link" href="mensualidad_digitel.php"><i class="fas fa-money-bill-alt fa-fw"></i> Mensualidades</a>
                  <a title="Recargas Digitel" class="nav-link" href="recargas_digitel.php"><i class="fas fa-cart-arrow-down fa-fw"></i> Recargas</a>
                  </div>
              </li>


      <!-- DIRECTV -->
              <li class="nav-item dropdown ">
               <a title="Sistema de Recarga DIRECTV" class="nav-link dropdown-toggle disabled" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <span class="icon-directv"></span> Directv
               </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  </div>
              </li>
              
      <?php endif ?>

    </ul>
    </div>
  </div>
  </nav>


    <div class="container">

    <div class="row">
    <div class="col-sm-6">



    </div>
    <div class="col-sm-6 text-right">
    <?php echo 'Hoy es: ' .$fads; ?>
    </div>
    </div>

<?php
 // echo '<p class="text-right">';
 // echo $ip;
 // echo " ";
 // echo $fads;
//  echo "<br>";
 // echo $nombrepag;
 // echo '</p>';

?>
