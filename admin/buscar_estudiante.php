<?php
require_once '../funciones/functions.php';

$cedula = $_GET['cedula'] ?? '';

// Usar la función modificada que busca en users
$estudiantes = buscarEstudiantePorCedula($cedula);

header('Content-Type: application/json');
echo json_encode($estudiantes);