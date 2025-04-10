<?php

if (isset($_SESSION['user']['user_type'])) {
    $linklocal = '/'.$_SESSION['user']['user_type'];
    if ($linklocal == '/user') {
        $linklocal = '/usuario';
    }
}

//$pag_web = $pag_web .'/herrera';

$bootstrap_head ='
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <!-- FAVICON -->

      <link rel="apple-touch-icon" href="'.$pag_web.'/images/favicon/apple-touch-icon.png" sizes="180x180">
      <link rel="icon" href="'.$pag_web.'/images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
      <link rel="icon" href="'.$pag_web.'/images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">
      <link rel="icon" href="'.$pag_web.'/images/favicon/favicon.ico">

  <script src="'.$pag_web.'/funciones/node_modules/jquery/dist/jquery.slim.js" ></script>

  <!-- MOMENT -->
  <script src="'.$pag_web.'/funciones/ajax/libs/moment/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
  <script src="'.$pag_web.'/funciones/ajax/libs/moment/es-us.min.js" integrity="sha512-QfUPyAMVgJBoL2yYVx8xkXmPFL7IKoF+eP5hq5xF4O/Mz1eqvxdy/vBEWDiJNPwGw7K8FCcCllrppqLpSWK/ng==" crossorigin="anonymous"></script>

  <!-- JQUERY       -->
      <script src="'.$pag_web.'/funciones/jquery/jquery-3.7.1.js"></script>


  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/bootstrap-4.6.2-dist/css/bootstrap.min.css">

  <!-- Font Awesome Solid + Brands y CSS -->
  <link href="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/css/fontawesome.css" rel="stylesheet">
  <link href="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
  <link href="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/css/solid.css" rel="stylesheet">
  <script defer src="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/js/all.js"></script>
  <script defer src="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/js/brands.js"></script>
  <script defer src="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/js/solid.js"></script>
  <script defer src="'.$pag_web.'/funciones/fontawesome-free-5.15.4-web/js/fontawesome.js"></script>

  <!-- DATATABLES -->
  <link href="'.$pag_web.'/funciones/DataTables/datatables.min.css" rel="stylesheet">
  <script src="'.$pag_web.'/funciones/DataTables/datatables.min.js"></script>

  <!-- INTERNO -->
  <link href="'.$pag_web.$linklocal.'/css/new.css" rel="stylesheet">
  <link href="'.$pag_web.'/css/modelo.css" rel="stylesheet">


  <!-- PUSH -->
  <script src="'.$pag_web.'/funciones/js/push.js"></script>
  <script src="'.$pag_web.'/funciones/js/push.min.js"></script>

   <!-- STACKTABLE -->
  <script src="'.$pag_web.'/funciones/js/stacktable/stacktable.js"></script>
  <link href="'.$pag_web.'/funciones/js/stacktable/stacktable.css" rel="stylesheet">


  <!-- JavaScript -->
  <script src="'.$pag_web.'/funciones/alertifyjs/alertify.min.js"></script>
    <script src="'.$pag_web.'/funciones/js/chart.js"></script>


  <!-- CSS -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/alertifyjs/alertify.min.css"/>
  <!-- Default theme -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/alertifyjs/default.min.css"/>
  <!-- Semantic UI theme -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/alertifyjs/semantic.min.css"/>
  <!-- Bootstrap theme -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/alertifyjs/bootstrap.min.css"/>

  <!-- SUMMERNOTE -->
  <link href="'.$pag_web.'/funciones/summernote-0.8.18-dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="'.$pag_web.'/funciones/summernote-0.8.18-dist/summernote-bs4.min.js"></script>
  <script src="'.$pag_web.'/funciones/summernote-0.8.18-dist/lang/summernote-es-ES.js"></script>

  <!-- SWEETALERT2 -->
  <script src="'.$pag_web.'/funciones/node_modules/sweetalert2/dist/sweetalert2.js"></script>
  <link href="'.$pag_web.'/funciones/node_modules/sweetalert2/dist/sweetalert2.css" rel="stylesheet">


  ';



  // Función para verificar si la función checkAccessKey existe
function verifyFunctionExists($functionName) {
  if (function_exists($functionName)) {
      //echo "La función '$functionName' existe.";
  } else {
      echo "Error: La función '$functionName' no existe. Ha modificado el codigo y no podras utilizarlo";
      exit(); // Detener la ejecución del script si la función no existe
  }
}
// Verificar que la función checkAccessKey existe antes de llamarla
verifyFunctionExists('checkAccessKey');

  
  $bootstrap_footer = ' 
    <!-- WHATSAPP CHAT -->
      <a target="_blank" href="https://wa.me/584141448515/?text=%C2%A1Saludos!%20Me%20gustar%C3%ADa%20contactar%20con%20ustedes%20..." class="float float-bottom-left float-floating" style="background-color: #31D92B">
      <i class="whatsapp-icon-3x" style="background-image:url('.$pag_web.'/images/whatsapp3x.png)"></i>
      </a>

    <!-- TELEGRAM CHAT -->
      <a target="_blank" href="https://t.me/joher60" class="float float-bottom-right float-floating" style="
      left: 100px">
      <i class="telegram-icon-3x" style="background-image:url('.$pag_web.'/images/telegram.png)"></i>
      </a>

       <!-- JAVASCRIPT -->
       <script src="'.$pag_web.'/funciones/node_modules/jquery/dist/jquery.slim.js" ></script>

       
        <!-- JQUERY       -->
        <script src="'.$pag_web.'/funciones/jquery/jquery-3.7.1.js"></script>
     

       <!-- DATATABLES -->
      <link href="'.$pag_web.'/funciones/DataTables/datatables.min.css" rel="stylesheet">
      <script src="'.$pag_web.'/funciones/DataTables/datatables.min.js"></script>

      <!-- PDFMAKE -->
      <script type="text/javascript" src="'.$pag_web.'/funciones/ajax/libs/pdfmake/pdfmake.min.js"></script>
      <script type="text/javascript" src="'.$pag_web.'/funciones/ajax/libs/pdfmake/vfs_fonts.js"></script>




      <script src="'.$pag_web.'/funciones/popper/package/dist/umd/popper.js"></script>
      

      <!-- BOOTSTRAP -->

  
      <script src="'.$pag_web.'/funciones/bootstrap-4.6.2-dist/js/bootstrap.min.js"></script>
      
      <script src="'.$pag_web.'/funciones/js/kernel.js"></script>


      <!-- BOOTBOX -->
      <script src="'.$pag_web.'/funciones/js/bootbox.all.min.js"></script>
      
      <!-- COMPLEMENTO -->
      <script src="'.$pag_web.'/funciones/js/complemento.js"></script>
      

';


$footer_correo = '<p>Siempre podra acceder aqui a su <a href="'.$pag_web.'/usuario/billetera.php" target="_blank">Billetera Virtual</a> <br><br>--<br><strong>Este es un producto ofrecido con el respaldo de J.E Suministros y Mas, C.A.</strong><br><em>Nuestra Web Principal: <a href="http://www.jesuministrosymas.com.ve" target="_blank" rel="noopener">http://www.jesuministrosymas.com.ve</a><br>La Plataforma de Gestion de Recargas: <a href="https://virtual.jesuministrosymas.com.ve" target="_blank" rel="noopener">https://virtual.jesuministrosymas.com.ve</a><br>Puedes solicitar atencion personalizada via <a href="https://wa.me/584141448515?text=Me%20gustar&iacute;a%20contactar%20con%20ustedes%20exponiendo%20el%20caso%20a%20continuacion:%20" target="_blank" rel="noopener">WHATSAPP</a><br>Unete a nuestro canal de Telegram <a href="https://t.me/jesuministrosymas" target="_blank" rel="noopener">Telegram JESUMINISTROSYMAS</a><br><br>Para dejar de recibir este tipo de correos favor, env&iacute;a un correo electr&oacute;nico a: <a href="mailto:gestionderecargas+unsubscribe@googlegroups.com" target="_blank" rel="noopener"> gestionderecargas+unsubscribe@googlegroups.com</a> y de manera automatica dejaras de recibir correos automatizados de nuestro sistema.</em><br><strong>J.E Suministros y Mas, C.A.<br>Rif: J-29444489-0</strong></p>';


 ?>
