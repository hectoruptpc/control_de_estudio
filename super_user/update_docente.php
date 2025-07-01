<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
include('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Validar y sanitizar los datos
    $datos = [
        'id' => intval($_POST['id']),
        'nombre' => trim($_POST['nombre']),
        'apellido' => trim($_POST['apellido']),
        'email' => filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL),
        'especialidad' => trim($_POST['especialidad']),
        'estado' => in_array($_POST['estado'], ['Activo', 'Inactivo', 'Licencia']) ? $_POST['estado'] : 'Activo'
    ];

    // Verificar que el email es válido
    if (!$datos['email']) {
        $_SESSION['error_message'] = "El email proporcionado no es válido";
        header("Location: list_docentes.php");
        exit();
    }

    // Actualizar el docente
    if (updateDocente($datos['id'], $datos)) {
        $_SESSION['success_message'] = "Docente actualizado correctamente";
    } else {
        $_SESSION['error_message'] = "Error al actualizar el docente";
    }
}

header("Location: list_docentes.php");
exit();
?>