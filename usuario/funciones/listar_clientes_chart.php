<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Clientes";
include('../../funciones/functions.php');

function listar_clientes_chart(){
global $db, $id_usua;

$arreglo = [];

$query = "SELECT *, DATE_FORMAT(fecha_registro, '%Y-%m') AS month, COUNT(*) AS count FROM clientes WHERE idusuario = ? GROUP BY month";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $id_usua);
$stmt->execute();
$result = $stmt->get_result();

while($data = $result->fetch_array(MYSQLI_ASSOC))
{
$arreglo ["data"][] = $data;
}

echo json_encode($arreglo);

$stmt->close();
}
listar_clientes_chart();
?>
