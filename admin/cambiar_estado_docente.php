<?php
require_once('../funciones/functions.php');

header('Content-Type: application/json');

$docenteId = $_POST['id'] ?? 0;
$nuevoEstado = $_POST['status'] ?? 0;

$resultado = cambiarEstadoDocente($docenteId, $nuevoEstado);

echo json_encode($resultado);