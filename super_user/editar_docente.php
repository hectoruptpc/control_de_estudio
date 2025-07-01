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
    $datos = [
        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'tipo_documento' => $_POST['tipo_documento'],
        'documento' => $_POST['documento'],
        'email' => $_POST['email'],
        'especialidad' => $_POST['especialidad'],
        'estado' => $_POST['estado']
    ];

    if (editarDocente($datos)) {
        $_SESSION['mensaje'] = 'Docente actualizado correctamente';
        $_SESSION['tipo_mensaje'] = 'success';
    } else {
        $_SESSION['mensaje'] = 'Error al actualizar el docente';
        $_SESSION['tipo_mensaje'] = 'danger';
    }
    
    header('Location: docentes.php');
    exit();
}

include("includes/footer.php");