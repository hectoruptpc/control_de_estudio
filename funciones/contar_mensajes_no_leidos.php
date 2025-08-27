<?php
require_once('functions.php');

if (!isLoggedIn()) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

function contarMensajesNoLeidos($user_id) {
    global $db;
    
    $query = "SELECT COUNT(*) as total 
              FROM mensajeria 
              WHERE id_usuario_destinatario = ? 
              AND leido = FALSE 
              AND eliminado_destinatario = FALSE";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

header('Content-Type: application/json');
echo json_encode([
    'mensajes_no_leidos' => contarMensajesNoLeidos($_SESSION['user']['id'])
]);
?>