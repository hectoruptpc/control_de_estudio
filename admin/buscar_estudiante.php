<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Verificar sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

include('../funciones/functions.php');

if (!isset($_GET['cedula'])) {
    echo json_encode([]);
    exit;
}

$cedula = trim($_GET['cedula']);
if (strlen($cedula) < 2) {
    echo json_encode([]);
    exit;
}

$estudiantes = buscarEstudiantePorCedula($cedula);
echo json_encode($estudiantes);