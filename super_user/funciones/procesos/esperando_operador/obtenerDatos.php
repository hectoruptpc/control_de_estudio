<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

// $origen = $_SERVER['REQUEST_URI'];
//   if($origen!= "u/admin/esperando_operador.php"){
//       header("location:../error/");
//       die();
//   }

require_once "../../clases/conexion.php";
require_once "../../clases/crud_eo.php";

$obj= new crud();

 echo json_encode($obj->obtenDatos($_POST['id']));

 ?>
