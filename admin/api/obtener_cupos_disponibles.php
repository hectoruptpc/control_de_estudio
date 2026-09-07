<?php
header('Content-Type: application/json');
require_once('../../funciones/functions.php');

if (!isset($_GET['carrera_id']) || !isset($_GET['turno'])) {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros']);
    exit;
}

$carreraId = (int)$_GET['carrera_id'];
$turno = $_GET['turno'];

$cupos = obtenerCuposDisponiblesPorCarreraYTurno($carreraId, $turno);

echo json_encode([
    'success' => true,
    'total' => $cupos['total'],
    'ocupados' => $cupos['ocupados'],
    'disponibles' => $cupos['disponibles'],
    'tiene_cupo' => $cupos['tiene_cupo']
]);
?>