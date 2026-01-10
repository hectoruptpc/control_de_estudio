<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../funciones/functions.php';

// Verificar si es una solicitud AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso no permitido']);
    exit;
}

// Verificar autenticación y rol
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['msg'] = "Debes iniciar sesión como administrador para acceder";
    header('location: ../login.php');
    exit();
}

// Obtener término de búsqueda
$cedula = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';

if (empty($cedula)) {
    echo json_encode(['success' => false, 'error' => 'Ingrese una cédula para buscar']);
    exit;
}

// Buscar estudiantes por cédula (idusuario)
$sql = "SELECT id, idusuario, nombre, tlf, cel, email, carrera 
        FROM users 
        WHERE idusuario LIKE ? AND estudiante = 1 
        ORDER BY nombre 
        LIMIT 10";

$stmt = mysqli_prepare($db, $sql);
$searchTerm = "%$cedula%";
mysqli_stmt_bind_param($stmt, "s", $searchTerm);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$estudiantes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $estudiantes[] = [
        'id' => $row['id'],
        'cedula' => $row['idusuario'],
        'nombre' => $row['nombre'],
        'contacto' => $row['tlf'] ?: $row['cel'],
        'email' => $row['email'],
        'carrera' => $row['carrera'] ?: 'No especificado'
    ];
}

echo json_encode([
    'success' => true,
    'count' => count($estudiantes),
    'data' => $estudiantes
]);
exit;