<?php
/**
 * =========================================================================================
 * SISTEMA DE CONTROL DE ESTUDIOS - PROTECCIÓN DE DIRECTORIO DE SOPORTES
 * =========================================================================================
 * Archivo: soportes/index.php
 * Propósito: Prevenir la exploración de directorios (Directory Browsing / Index of) y proteger
 *            los documentos confidenciales (PDFs, imágenes de comprobantes y actas).
 * 
 * Política de seguridad y redirección:
 * 1. Si el usuario NO ha iniciado sesión:
 *    -> Se le redirige inmediatamente al formulario de login (../login.php).
 * 
 * 2. Si el usuario está autenticado y tiene múltiples perfiles:
 *    -> Se le redirige a la selección de perfiles (../profile_selector.php).
 * 
 * 3. Si el usuario está autenticado y posee una sola cuenta/perfil:
 *    -> Se le redirige directamente al panel principal de su perfil correspondiente
 *       (por ejemplo, ../estudiante/index.php, ../docente/index.php, etc.).
 * =========================================================================================
 */

// Cargar librerías del sistema y arranque de sesión
require_once(__DIR__ . '/../funciones/functions.php');

// -----------------------------------------------------------------------------------------
// PASO 1: VALIDACIÓN DE SESIÓN ACTIVA
// -----------------------------------------------------------------------------------------
// Si el usuario no ha iniciado sesión, no tiene autorización para acceder
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

// -----------------------------------------------------------------------------------------
// PASO 2: DETERMINACIÓN DE PERFILES ASIGNADOS AL USUARIO
// -----------------------------------------------------------------------------------------
// Obtener la lista de perfiles habilitados para la cuenta en sesión
$availableProfiles = [];

if (function_exists('getAvailableProfiles')) {
    $availableProfiles = getAvailableProfiles();
} elseif (isset($_SESSION['user']['available_profiles']) && is_array($_SESSION['user']['available_profiles'])) {
    $availableProfiles = $_SESSION['user']['available_profiles'];
}

// En caso de que no esté inicializado en la sesión, calcularlo directamente con las funciones de rol
if (!is_array($availableProfiles) || empty($availableProfiles)) {
    $availableProfiles = [];
    if (isAdmin()) {
        $availableProfiles[] = 'admin';
    }
    if (function_exists('isSuperUser') && isSuperUser()) {
        $availableProfiles[] = 'super_user';
    }
    if (isDocente()) {
        $availableProfiles[] = 'docente';
    }
    if (isEstudiante()) {
        $availableProfiles[] = 'estudiante';
    }
    if (isUser()) {
        $availableProfiles[] = 'director_de_carrera';
    }
}

// -----------------------------------------------------------------------------------------
// PASO 3: MAPEO DE RUTAS POR PERFIL DE USUARIO
// -----------------------------------------------------------------------------------------
$profileRoutes = [
    'admin'               => '../admin/index.php',
    'super_user'          => '../super_user/index.php',
    'docente'             => '../docente/index.php',
    'estudiante'          => '../estudiante/index.php',
    'director_de_carrera' => '../director_de_carrera/index.php'
];

// -----------------------------------------------------------------------------------------
// PASO 4: EJECUCIÓN DE LA REDIRECCIÓN SEGÚN CANTIDAD DE PERFILES
// -----------------------------------------------------------------------------------------
$cantidadPerfiles = count($availableProfiles);

if ($cantidadPerfiles === 1) {
    // Caso A: El usuario tiene un único perfil asignado -> Enviar directo a su panel
    $perfilUnico = $availableProfiles[0];
    $_SESSION['current_profile'] = $perfilUnico;
    
    // Obtener la ruta del perfil o fallback a la raíz
    $rutaDestino = $profileRoutes[$perfilUnico] ?? '../index.php';
    header("Location: " . $rutaDestino);
    exit();
} elseif ($cantidadPerfiles > 1) {
    // Caso B: El usuario tiene múltiples perfiles -> Enviar al selector de perfiles
    header('Location: ../profile_selector.php');
    exit();
} else {
    // Caso de contingencia (sin perfiles reconocidos) -> Cerrar sesión o ir a login
    header('Location: ../login.php');
    exit();
}
