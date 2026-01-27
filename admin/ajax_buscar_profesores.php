<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    die('Acceso no permitido');
}

if (!isset($_GET['termino'])) {
    die('Término de búsqueda no especificado');
}

$termino = $_GET['termino'];

// Buscar profesores por término
function buscarProfesoresAjax($termino) {
    global $db;
    $query = "SELECT id, idusuario, nombre 
              FROM users 
              WHERE docente = 1 
              AND (nombre LIKE ? OR idusuario LIKE ?)
              ORDER BY nombre
              LIMIT 10";
    $stmt = $db->prepare($query);
    $termino_like = "%$termino%";
    $stmt->bind_param("ss", $termino_like, $termino_like);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $profesores = [];
    while ($profesor = $result->fetch_assoc()) {
        $profesores[] = $profesor;
    }
    
    return $profesores;
}

$profesores = buscarProfesoresAjax($termino);

// Devolver resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($profesores);
?>