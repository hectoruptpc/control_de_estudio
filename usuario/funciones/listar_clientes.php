<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Clientes";
include('../../funciones/functions.php');

function listar_clientes(){
global $db, $id_usua, $cedula, $nombre, $telefono1, $telefono2, $direccion, $ciudad, $estado, $fecha_registro, $fecha_update;

$arreglo = [];

$query = "SELECT id, cedula, nombre, telefono1, telefono2, direccion, estado, ciudad, fecha_registro, fecha_update FROM clientes WHERE idusuario = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id_usua);
$stmt->execute();
$result = $stmt->get_result();

while($data = $result->fetch_array(MYSQLI_ASSOC))
{
$data['id']= $data['id'];
$cedula = $data['cedula'];
$nombre = $data['nombre'];
$telefono1 = $data['telefono1'];
$telefono2 = $data['telefono2'];
$direccion = $data['direccion'];
$estado = $data['estado'];
$ciudad = $data['ciudad'];
$fecha_registro = $data['fecha_registro'];
$fecha_update = $data['fecha_update'];

$arreglo ["data"][] = $data;
}

// Recorremos los datos y reemplazamos los null con una cadena vacía
foreach ($arreglo as $clave => $valor) {
foreach ($valor as $clave2 => $valor2) {
if ($valor2 === null) {
$arreglo[$clave][$clave2] = "";
}
}
}

echo json_encode($arreglo);

$stmt->close();
}
listar_clientes();
?>