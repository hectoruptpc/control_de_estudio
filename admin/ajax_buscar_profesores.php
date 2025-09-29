<?php
require_once('../funciones/functions.php');

if (!isset($_GET['termino'])) {
    die(json_encode([]));
}

$termino = trim($_GET['termino']);

if (strlen($termino) < 2) {
    die(json_encode([]));
}

// Buscar profesores por nombre o cédula
function buscarProfesores($termino) {
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
        $profesores[] = [
            'id' => $profesor['id'],
            'idusuario' => htmlspecialchars($profesor['idusuario']),
            'nombre' => htmlspecialchars($profesor['nombre'])
        ];
    }
    
    return $profesores;
}

header('Content-Type: application/json');
echo json_encode(buscarProfesores($termino));
?>