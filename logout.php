<?php
// ARCHIVO: logout.php
session_start();

// Incluir funciones para poder usar el sistema de auditoría
require_once('funciones/functions.php');

// Verificar si ya se ha procesado el logout para evitar doble registro
if (!isset($_SESSION['logout_processed'])) {
    $_SESSION['logout_processed'] = true;
    
    // Registrar cierre de sesión en auditoría antes de destruir la sesión
    if (isset($_SESSION['user']['id'])) {
        $usuario_id = $_SESSION['user']['id'];
        $username = $_SESSION['user']['username'] ?? 'Desconocido';
        
        // Registrar en auditoría
        registrarAuditoria(
            "LOGOUT", 
            "users", 
            $usuario_id, 
            null, 
            ['username' => $username], 
            "Autenticación", 
            "Cierre de sesión del sistema"
        );
    }

    // Destruir completamente la sesión
    $_SESSION = array();

    // Si se desea destruir la cookie de sesión, también se borra
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destruir la sesión
    session_destroy();
}

// Redireccionar al login con mensaje de despedida
$_SESSION['msg'] = "Ha cerrado sesión correctamente. ¡Hasta pronto!";
header('Location: login.php');
exit();
?>