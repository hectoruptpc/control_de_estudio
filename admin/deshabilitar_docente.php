<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../funciones/functions.php');
include("includes/head.php");

// Verificar que la conexión existe
if (!isset($db)) {
    die("Error: No se pudo establecer conexión con la base de datos");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $razon = $_POST['razon'];

    if (deshabilitarDocente($id, $razon)) {
        $_SESSION['mensaje'] = 'Docente deshabilitado correctamente';
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = 'Error al deshabilitar el docente';
        $_SESSION['tipo_mensaje'] = 'danger';
    }
    
    header('Location: docentes.php');
    exit();
}

include("includes/footer.php");