<?php
// .dios/logout.php - Cerrar sesión DIOS
require_once 'config.php';

// Destruir solo la sesión DIOS
if (session_status() === PHP_SESSION_ACTIVE) {
    // Destruir la sesión DIOS
    session_destroy();
}

// Redirigir al login
header('Location: login.php');
exit;
?>