<?php
include('../funciones/functions.php');

header('Content-Type: application/json');

if (isset($_GET['cedula'])) {
    $cedula = trim($_GET['cedula']);
    $estudiantes = buscarEstudiantePorCedula($cedula);
    echo json_encode($estudiantes);
} else {
    echo json_encode([]);
}
?>