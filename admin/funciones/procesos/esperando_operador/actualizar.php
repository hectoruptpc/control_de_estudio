<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
require_once "../../clases/conexion.php";
require_once "../../clases/crud_eo.php";

	$obj= new crud();

	$datos=array(
      $_POST['id'],
      $_POST['user'],
      $_POST['operador'],
      $_POST['tipo'],
      $_POST['nro'],
      $_POST['monto'],
      $_POST['fecha'],
      $_POST['status'],
      $_POST['relacion'],
      $_POST['confirmacion'],
      $_POST['sin_plan']

				);

	echo $obj->actualizar($datos);


 ?>
