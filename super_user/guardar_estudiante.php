<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $cedula = trim($_POST['cedula'] ?? '');
    $num_telf = trim($_POST['num_telf'] ?? '');
    $num_telf_opc = trim($_POST['num_telf_opc'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $fecha_nac = trim($_POST['fecha_nac'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $carrera = trim($_POST['carrera'] ?? '');
    $fecha_ingreso = trim($_POST['fecha_ingreso'] ?? '');

    // Validar campos obligatorios
    $errores = [];
    if ($nombre === '') $errores[] = 'El nombre es obligatorio.';
    if ($cedula === '') $errores[] = 'La cédula es obligatoria.';
    if ($num_telf === '') $errores[] = 'El teléfono principal es obligatorio.';
    if ($correo === '') $errores[] = 'El correo es obligatorio.';
    if ($fecha_nac === '') $errores[] = 'La fecha de nacimiento es obligatoria.';
    if ($genero === '') $errores[] = 'El género es obligatorio.';
    if ($carrera === '') $errores[] = 'La carrera es obligatoria.';
    if ($fecha_ingreso === '') $errores[] = 'La fecha de ingreso es obligatoria.';

    if (count($errores) > 0) {
        // Redirigir con errores
        header('Location: agregar_estudiante.php?error=' . urlencode(implode(' ', $errores)));
        exit;
    }

    // Preparar datos para insertar
    $datos = [
        'nombre' => $nombre,
        'cedula' => $cedula,
        'num_telf' => $num_telf,
        'num_telf_opc' => $num_telf_opc,
        'correo' => $correo,
        'fecha_nac' => $fecha_nac,
        'genero' => $genero,
        'carrera' => $carrera,
        'fecha_ingreso' => $fecha_ingreso
    ];

    // Usar la función de insertar estudiante
    $resultado = insertarEstudiante($datos);
    if ($resultado['success']) {
        header('Location: estudiantes.php?success=1');
        exit;
    } else {
        header('Location: agregar_estudiante.php?error=' . urlencode($resultado['message']));
        exit;
    }
} else {
    header('Location: agregar_estudiante.php');
    exit;
}
