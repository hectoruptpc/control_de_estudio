<?php
// .dios/config.php - Configuración SECRETA del acceso DIOS
// ⚠️ SOLO TÚ DEBES SABER ESTAS CREDENCIALES ⚠️

// Usuario y contraseña del MODO DIOS (cámbialos por los tuyos)
define('DIOS_USER', 'Dios');

// Contraseña: "dios123" (hasheada)
define('DIOS_PASS_HASH', '$2y$10$CxayOoSjMJLomCdQIA4r7eTJPjMdM/FyG5lNe/iWEZTH6eeH8o01.');

// Token secreto para acceder directamente (opcional)
define('DIOS_TOKEN', 'uptpc_dios_master_2024_');

// Carpeta donde está el panel (para redirecciones)
define('DIOS_FOLDER', '/control_de_estudio/.dios/');

// Iniciar sesión DIOS separada solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_name('DIOS_SESSION');  // Nombre diferente a la sesión normal
    session_start();
}
?>