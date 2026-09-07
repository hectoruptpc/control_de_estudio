<?php
// .dios/config.php - Configuración SECRETA del acceso DIOS
// ⚠️ SOLO TÚ DEBES SABER ESTAS CREDENCIALES ⚠️

// Usuario y contraseña del MODO DIOS (cámbialos por los tuyos)
define('DIOS_USER', 'Dios');

// Contraseña: "dios123" (hasheada)
define('DIOS_PASS_HASH', '$2y$10$CxayOoSjMJLomCdQIA4r7eTJPjMdM/FyG5lNe/iWEZTH6eeH8o01.');

// Token secreto para acceder directamente (opcional)
define('DIOS_TOKEN', 'uptpc_dios_master_');

// Carpeta donde está el panel (para redirecciones)
define('DIOS_FOLDER', '/control_de_estudio/.dios/');

// Iniciar sesión DIOS separada solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_name('DIOS_SESSION');  // Nombre diferente a la sesión normal
    $secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

    session_set_cookie_params([
        'path' => '/control_de_estudio/.dios/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => $secureCookie
    ]);
    session_start();
}

// Cabeceras de seguridad para el panel DIOS
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: interest-cohort=()');
}

if (session_status() === PHP_SESSION_ACTIVE) {
    if (empty($_SESSION['dios_ajax_token'])) {
        $_SESSION['dios_ajax_token'] = bin2hex(random_bytes(16));
    }
    if (empty($_SESSION['dios_csrf_token'])) {
        $_SESSION['dios_csrf_token'] = bin2hex(random_bytes(32));
    }
}
?>