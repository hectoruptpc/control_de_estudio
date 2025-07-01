<?php
session_start();
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'id' => $_POST['id'] ?? null,
        'cod_materia' => $_POST['cod_materia'],
        'nombre_materia' => $_POST['nombre_materia'],
        'creditos' => $_POST['creditos'],
        'horas_teoricas' => $_POST['horas_teoricas'],
        'horas_practicas' => $_POST['horas_practicas'],
        'activa' => $_POST['activa']
    ];
    
    // Validar datos (puedes agregar más validaciones)
    if (empty($datos['cod_materia']) || empty($datos['nombre_materia'])) {
        $_SESSION['error'] = "Código y nombre de materia son requeridos";
        header("Location: agregar_materia.php");
        exit();
    }
    
    if (guardarMateria($datos)) {
        $_SESSION['mensaje'] = empty($datos['id']) ? "Materia creada exitosamente" : "Materia actualizada exitosamente";
    } else {
        $_SESSION['error'] = "Error al guardar la materia";
    }
    
    header("Location: agregar_materia.php");
    exit();
}

header("Location: agregar_materia.php");
exit();
?>