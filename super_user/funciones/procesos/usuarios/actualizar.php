<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
require_once "../../clases/conexion.php";
require_once "../../clases/crud_eo.php";

	$obj= new crud();

	$datos=array(
      $_POST['id'],
      $_POST['idusuario'],
      $_POST['nombre'],
      $_POST['email'],
      $_POST['tlf'],
      $_POST['cel'],
      $_POST['direccion'],
      $_POST['ciudad'],
      $_POST['estado'],
      $_POST['municipio'],
      $_POST['parroquia'],
      $_POST['status'],
      $_POST['motivo_bloqueo'],
      $_POST['monto_a_favor'],
      $_POST['disp_a_favor']
				);

	echo $obj->actualizarUsuario($datos);


 ?>
