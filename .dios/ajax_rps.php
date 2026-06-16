<?php
// .dios/ajax_rps.php - Actualizar RPS en tiempo real (CORREGIDO)

// ==============================================
// 1. INICIAR SESIÓN CON EL MISMO NOMBRE QUE EL PANEL DIOS
// ==============================================
session_name('DIOS_SESSION');  // ← Este es el nombre correcto
session_start();
header('Content-Type: application/json');

// ==============================================
// 2. VERIFICAR AUTENTICACIÓN
// ==============================================
if (!isset($_SESSION['dios_autenticado']) || $_SESSION['dios_autenticado'] !== true) {
    echo json_encode(['error' => 'No autorizado - Sesión no válida']);
    exit;
}

// ==============================================
// 3. CONECTAR A LA BASE DE DATOS
// ==============================================
require_once '../funciones/functions.php';
require_once '../funciones/seguridad.php';

// Instanciar seguridad
$seguridad = new Seguridad($db);

// ==============================================
// 4. OBTENER ESTADÍSTICAS
// ==============================================
// RPS actual (últimos 10 segundos)
$sql = "SELECT COUNT(*) as total FROM seguridad_rps WHERE fecha > DATE_SUB(NOW(), INTERVAL 10 SECOND)";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_assoc($result);
$rps_actual = $row['total'] ?? 0;

// Intentos hoy
$sql = "SELECT COUNT(*) as total FROM seguridad_intentos WHERE DATE(fecha) = CURDATE()";
$result = mysqli_query($db, $sql);
$intentos_hoy = mysqli_fetch_assoc($result)['total'] ?? 0;

// Bloqueos activos
$sql = "SELECT COUNT(*) as total FROM seguridad_bloqueos WHERE activo = 1 AND desbloqueo_en > NOW()";
$result = mysqli_query($db, $sql);
$bloqueos_activos = mysqli_fetch_assoc($result)['total'] ?? 0;

// Tokens inválidos hoy
$sql = "SELECT COUNT(*) as total FROM seguridad_tokens_invalidos WHERE DATE(fecha) = CURDATE()";
$result = mysqli_query($db, $sql);
$tokens_invalidos = mysqli_fetch_assoc($result)['total'] ?? 0;

// Top IPs sospechosas
$sql = "SELECT ip, COUNT(*) as total FROM seguridad_intentos WHERE fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR) GROUP BY ip ORDER BY total DESC LIMIT 5";
$result = mysqli_query($db, $sql);
$top_ips = [];
while($row = mysqli_fetch_assoc($result)) {
    $top_ips[] = $row;
}

// ==============================================
// 5. DEVOLVER RESPUESTA JSON
// ==============================================
echo json_encode([
    'success' => true,
    'rps_actual' => (int)$rps_actual,
    'intentos_hoy' => (int)$intentos_hoy,
    'bloqueos_activos' => (int)$bloqueos_activos,
    'tokens_invalidos' => (int)$tokens_invalidos,
    'top_ips' => $top_ips
]);
?>