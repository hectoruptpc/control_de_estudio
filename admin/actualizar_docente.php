<?php
require_once('../funciones/functions.php');

header('Content-Type: application/json');

$data = $_POST;
$resultado = actualizarDocente($data);

echo json_encode($resultado);