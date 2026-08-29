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

  <!-- UNIFORMIDAD GLOBAL A MAYÚSCULAS (Excluyendo Login y credenciales) -->
  <style>
    table.table th,
    table.table td,
    .card-header,
    .modal-title,
    .badge,
    form label,
    select.form-control,
    input.form-control:not(.no-uppercase):not([name="username"]):not([name="password"]):not([type="password"]):not([type="email"]),
    .dropdown-item {
        text-transform: uppercase !important;
    }

    body.page-login input.form-control,
    input.no-uppercase,
    input[name="username"],
    input[name="password"],
    input[type="password"],
    input[type="email"] {
        text-transform: none !important;
    }
  </style>


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
// function verifyFunctionExists($functionName) {
//   if (function_exists($functionName)) {
//       //echo "La función '$functionName' existe.";
//   } else {
//       echo "Error: La función '$functionName' no existe. Ha modificado el codigo y no podras utilizarlo";
//       exit(); // Detener la ejecución del script si la función no existe
//   }
// }
// // Verificar que la función checkAccessKey existe antes de llamarla
// verifyFunctionExists('checkAccessKey');

  
  $bootstrap_footer = ' 
    

    

       <!-- JAVASCRIPT -->
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
      <script src="'.$pag_web.'/funciones/js/complemento.js?v='.filemtime(__DIR__.'/js/complemento.js').'"></script>
      

';


$footer_correo = '
<div style="background-color: #f0f0f0; padding: 20px; text-align: center; border-top: 3px solid #003366; font-family: Arial, sans-serif; margin-top: 30px;">
    <p style="color: #003366; font-size: 16px; margin: 0; font-weight: bold;">🏛️ Universidad Politécnica Territorial de Puerto Cabello (UPTPC)</p>
    <p style="color: #666; font-size: 13px; margin: 5px 0 0;">Sistema de Control de Estudios</p>
    <p style="color: #999; font-size: 12px; margin: 10px 0 0;">Este es un mensaje automático, por favor no responder a este correo.</p>
    <p style="color: #999; font-size: 11px; margin: 5px 0 0;">© ' . date('Y') . ' - Todos los derechos reservados</p>
</div>';

 ?>
