<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');
//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('admin');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();





include("includes/head.php");
?>



<?php include("includes/footer.php"); ?>

//prueba