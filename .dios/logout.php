<?php
// .dios/logout.php - Cerrar sesión DIOS
require_once 'config.php';

// Destruir solo la sesión DIOS
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

// Redirigir al login
header('Location: login.php');
exit;
?>