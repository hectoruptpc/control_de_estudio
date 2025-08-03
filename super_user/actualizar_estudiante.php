<?php
error_reporting(0);
ini_set('display_errors', 0);

require_once('../funciones/functions.php');

// Verificar conexión
if (!isset($GLOBALS['db'])) {
    header("Location: ".$_SERVER['HTTP_REFERER']); // Regresa a la página anterior
    exit;
}

// Validar ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}

$id = $_POST['id'];

// Procesar datos
$datos = [
    'nombre' => trim($_POST['nombre'] ?? ''),
    'username' => trim($_POST['cedula'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'tlf' => trim($_POST['num_telf'] ?? ''),
    'num_telf_opc' => trim($_POST['num_telf_opc'] ?? ''),
    'carrera' => trim($_POST['carrera'] ?? ''),
    'genero' => trim($_POST['genero'] ?? ''),
    'fecha_nac' => trim($_POST['fecha_nac'] ?? ''),
    'fecha_ingreso' => trim($_POST['fecha_ingreso'] ?? ''),
    'status' => intval($_POST['status'] ?? 1),
    'fecha_act' => date('Y-m-d H:i:s')
];

// Validar campos obligatorios
if (empty($datos['nombre']) || empty($datos['username']) || empty($datos['fecha_ingreso'])) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}

// Ejecutar actualización
try {
    $db = $GLOBALS['db'];
    
    $query = "UPDATE users SET 
              nombre = ?, 
              username = ?, 
              email = ?, 
              tlf = ?, 
              num_telf_opc = ?, 
              carrera = ?, 
              genero = ?, 
              fecha_nac = ?, 
              fecha_ingreso = ?, 
              status = ?, 
              fecha_act = ? 
              WHERE id = ?";
    
    $stmt = $db->prepare($query);
    if ($stmt) {
        $stmt->bind_param(
            "sssssssssssi",
            $datos['nombre'],
            $datos['username'],
            $datos['email'],
            $datos['tlf'],
            $datos['num_telf_opc'],
            $datos['carrera'],
            $datos['genero'],
            $datos['fecha_nac'],
            $datos['fecha_ingreso'],
            $datos['status'],
            $datos['fecha_act'],
            $id
        );
        $stmt->execute();
        $stmt->close();
    }
} catch (Exception $e) {
    // No hacer nada con los errores
}

// Redireccionar siempre al final
header("Location: ".$_SERVER['HTTP_REFERER']);
exit;