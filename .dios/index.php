<?php
// .dios/index.php - Panel MODO DIOS (COMPLETO)
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "👑 PANEL DIOS - Control Total UPTPC";

// Incluir configuración DIOS y funciones
require_once 'config.php';
require_once '../funciones/functions.php';
require_once '../funciones/seguridad.php';

// Verificar autenticación DIOS
if (!isset($_SESSION['dios_autenticado']) || $_SESSION['dios_autenticado'] !== true) {
    header('Location: login.php');
    exit;
}

$seguridad = new Seguridad($db);

// ==============================================
// VARIABLES DE PAGINACIÓN Y BÚSQUEDA
// ==============================================
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$por_pagina = 15;
$offset = ($pagina - 1) * $por_pagina;

// Construir query con búsqueda
$where = "";
$params = [];
$types = "";

if (!empty($buscar)) {
    $where = "WHERE nombre LIKE ? OR email LIKE ? OR username LIKE ? OR id LIKE ?";
    $buscar_param = "%$buscar%";
    $params = [$buscar_param, $buscar_param, $buscar_param, $buscar_param];
    $types = "ssss";
}

// Contar total de usuarios
$count_sql = "SELECT COUNT(*) as total FROM users $where";
$stmt = mysqli_prepare($db, $count_sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$count_result = mysqli_stmt_get_result($stmt);
$total_usuarios = mysqli_fetch_assoc($count_result)['total'];
$total_paginas = ceil($total_usuarios / $por_pagina);

// Obtener usuarios con paginación
$sql = "SELECT id, nombre, email, username, status FROM users $where ORDER BY id LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($db, $sql);
if (!empty($params)) {
    $params[] = $por_pagina;
    $params[] = $offset;
    $types .= "ii";
    mysqli_stmt_bind_param($stmt, $types, ...$params);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $por_pagina, $offset);
}
mysqli_stmt_execute($stmt);
$usuarios = mysqli_stmt_get_result($stmt);

// Otras consultas
$bloqueos = mysqli_query($db, "SELECT * FROM seguridad_bloqueos WHERE activo = 1 AND desbloqueo_en > NOW() ORDER BY desbloqueo_en DESC LIMIT 20");
$top_ips = mysqli_query($db, "SELECT ip, COUNT(*) as total FROM seguridad_intentos WHERE fecha > DATE_SUB(NOW(), INTERVAL 1 HOUR) GROUP BY ip ORDER BY total DESC LIMIT 10");
$logs_recientes = mysqli_query($db, "SELECT * FROM seguridad_intentos ORDER BY fecha DESC LIMIT 30");

// Estadísticas
$stats = $seguridad->obtenerEstadisticas();
$sistema_activo = $seguridad->obtenerConfiguracion('sistema_activo', '1');
$modo_mantenimiento = $seguridad->obtenerConfiguracion('modo_mantenimiento', '0');
$sistema_completo = $seguridad->obtenerConfiguracion('sistema_completo_activo', '1') == '1';
$razon_cierre = $seguridad->obtenerConfiguracion('razon_cierre', '');
$ultimo_cierre_por = $seguridad->obtenerConfiguracion('ultimo_cierre_por', '');
$fecha_cierre = $seguridad->obtenerConfiguracion('fecha_cierre', '');
$limite_recuperar = $seguridad->obtenerConfiguracion('limite_recuperar_por_hora', 3);
$bloqueo_horas = $seguridad->obtenerConfiguracion('limite_bloqueo_horas', 1);
$bloqueo_incremento = $seguridad->obtenerConfiguracion('limite_bloqueo_incremento', 24);
$rps_limite = $seguridad->obtenerConfiguracion('limite_rps_10seg', 10);
$rps_global = $seguridad->obtenerConfiguracion('limite_rps_global_porcentaje', 10);
$total_usuarios_db = $seguridad->obtenerConfiguracion('total_usuarios', 100);
$limite_global_calculado = ceil($total_usuarios_db * ($rps_global / 100));

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        // Cierre completo del sistema (con motivo automático)
        case 'toggle_sistema_completo':
            if ($_POST['sistema_completo'] == '1') {
                // Motivos aleatorios o fijos
                $motivos = [
                    '🔧 Mantenimiento programado del sistema',
                    '⚙️ Actualización de la plataforma',
                    '🛠️ Mejoras en el sistema de control de estudios',
                    '🔒 Seguridad del sistema - Revisión programada',
                    '📊 Optimización de base de datos',
                    '🔄 Respaldo completo del sistema',
                    '🚨 Sistema en mantenimiento por seguridad',
                    '📈 Actualización de módulos académicos'
                ];
                $razon = $motivos[array_rand($motivos)];
                $seguridad->actualizarConfiguracion('sistema_completo_activo', '0');
                $seguridad->actualizarConfiguracion('razon_cierre', $razon);
                $seguridad->actualizarConfiguracion('ultimo_cierre_por', $_SESSION['dios_usuario']);
                $seguridad->actualizarConfiguracion('fecha_cierre', date('Y-m-d H:i:s'));
                $_SESSION['msg_dios'] = "🔒 SISTEMA COMPLETO CERRADO. Motivo: $razon";
            } else {
                $seguridad->actualizarConfiguracion('sistema_completo_activo', '1');
                $seguridad->actualizarConfiguracion('razon_cierre', '');
                $_SESSION['msg_dios'] = "🔓 SISTEMA COMPLETO ABIERTO - Todo funciona normalmente";
            }
            break;
        case 'toggle_sistema':
            $nuevo_estado = $_POST['sistema_activo'] == '1' ? '0' : '1';
            $seguridad->actualizarConfiguracion('sistema_activo', $nuevo_estado);
            $_SESSION['msg_dios'] = "👑 Recuperación de contraseña " . ($nuevo_estado == '1' ? 'ACTIVADA' : 'DESACTIVADA');
            break;
        case 'toggle_mantenimiento':
            $nuevo_estado = $_POST['modo_mantenimiento'] == '1' ? '0' : '1';
            $seguridad->actualizarConfiguracion('modo_mantenimiento', $nuevo_estado);
            $_SESSION['msg_dios'] = "👑 Modo mantenimiento " . ($nuevo_estado == '1' ? 'ACTIVADO' : 'DESACTIVADO');
            break;
        case 'actualizar_limites':
            $limite_recuperar = max(1, min(100, intval($_POST['limite_recuperar'] ?? 3)));
            $bloqueo_horas = max(1, min(24, intval($_POST['bloqueo_horas'] ?? 1)));
            $bloqueo_incremento = max(1, min(168, intval($_POST['bloqueo_incremento'] ?? 24)));
            $rps_limite = max(1, min(1000, intval($_POST['rps_limite'] ?? 10)));
            $rps_global_porcentaje = max(1, min(100, intval($_POST['rps_global_porcentaje'] ?? 10)));
            $seguridad->actualizarConfiguracion('limite_recuperar_por_hora', (string)$limite_recuperar);
            $seguridad->actualizarConfiguracion('limite_bloqueo_horas', (string)$bloqueo_horas);
            $seguridad->actualizarConfiguracion('limite_bloqueo_incremento', (string)$bloqueo_incremento);
            $seguridad->actualizarConfiguracion('limite_rps_10seg', (string)$rps_limite);
            $seguridad->actualizarConfiguracion('limite_rps_global_porcentaje', (string)$rps_global_porcentaje);
            $_SESSION['msg_dios'] = "👑 Límites actualizados";
            break;
        case 'desbloquear_ip':
            $sql = "UPDATE seguridad_bloqueos SET activo = 0 WHERE ip = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_POST['ip']);
            mysqli_stmt_execute($stmt);
            $_SESSION['msg_dios'] = "👑 IP " . $_POST['ip'] . " desbloqueada";
            break;
        case 'desbloquear_email':
            $sql = "UPDATE seguridad_bloqueos SET activo = 0 WHERE email = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $_POST['email']);
            mysqli_stmt_execute($stmt);
            $_SESSION['msg_dios'] = "👑 Email desbloqueado";
            break;
        case 'desbloquear_todo':
            mysqli_query($db, "UPDATE seguridad_bloqueos SET activo = 0 WHERE activo = 1");
            $_SESSION['msg_dios'] = "👑 TODOS los bloqueos eliminados";
            break;
        case 'resetear_tokens':
            mysqli_query($db, "UPDATE password_resets SET usado = 1 WHERE usado = 0");
            $_SESSION['msg_dios'] = "👑 Todos los tokens invalidados";
            break;
        case 'limpiar_logs':
            $periodo = max(1, min(365, intval($_POST['periodo'] ?? 7)));
            mysqli_query($db, "DELETE FROM seguridad_intentos WHERE fecha < DATE_SUB(NOW(), INTERVAL $periodo DAY)");
            mysqli_query($db, "DELETE FROM seguridad_rps WHERE fecha < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
            mysqli_query($db, "DELETE FROM seguridad_tokens_invalidos WHERE fecha < DATE_SUB(NOW(), INTERVAL $periodo DAY)");
            $_SESSION['msg_dios'] = "👑 Logs eliminados ($periodo días)";
            break;
        case 'limpiar_todo':
            mysqli_query($db, "TRUNCATE TABLE seguridad_intentos");
            mysqli_query($db, "TRUNCATE TABLE seguridad_rps");
            mysqli_query($db, "TRUNCATE TABLE seguridad_tokens_invalidos");
            $_SESSION['msg_dios'] = "👑 TODOS los logs eliminados";
            break;
        case 'cambiar_password':
            $nueva_password = trim($_POST['nueva_password'] ?? '');
            if ($nueva_password === '') {
                $_SESSION['msg_dios'] = "⚠️ La contraseña no puede estar vacía.";
            } else {
                $nueva_pass = password_hash($nueva_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = mysqli_prepare($db, $sql);
                mysqli_stmt_bind_param($stmt, "si", $nueva_pass, $_POST['user_id']);
                mysqli_stmt_execute($stmt);
                $_SESSION['msg_dios'] = "👑 Contraseña cambiada";
            }
            break;
        case 'bloquear_usuario':
            $sql = "UPDATE users SET status = 0, motivo_bloqueo = 'Bloqueado por administrador DIOS' WHERE id = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "i", $_POST['user_id']);
            mysqli_stmt_execute($stmt);
            $_SESSION['msg_dios'] = "👑 Usuario BLOQUEADO";
            break;
        case 'desbloquear_usuario':
            $sql = "UPDATE users SET status = 1, motivo_bloqueo = NULL WHERE id = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "i", $_POST['user_id']);
            mysqli_stmt_execute($stmt);
            $_SESSION['msg_dios'] = "👑 Usuario DESBLOQUEADO";
            break;
    }
    header('Location: index.php' . (!empty($buscar) ? "?buscar=" . urlencode($buscar) : ""));
    exit;
}

include("head_dios.php");
?>

<style>
    .search-box {
        background: #fff;
        border-radius: 50px;
        padding: 5px 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }
    .search-box input {
        border: none;
        padding: 10px;
        width: 250px;
        outline: none;
    }
    .search-box button {
        background: none;
        border: none;
        color: #ffc107;
        cursor: pointer;
    }
    .pagination .page-item.active .page-link {
        background: #ffc107;
        border-color: #ffc107;
        color: #2c3e50;
    }
    .pagination .page-link {
        color: #2c3e50;
    }
    .badge-sistema-cerrado {
        background: #dc3545;
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-sistema-abierto {
        background: #28a745;
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: bold;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stat-card .icon {
        font-size: 35px;
        color: #ffc107;
        margin-bottom: 10px;
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
    }
    .stat-card .label {
        font-size: 14px;
        color: #6c757d;
    }
    .btn-dios {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #2c3e50;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        padding: 8px 20px;
        border-radius: 8px;
    }
    .btn-dios:hover {
        background: linear-gradient(135deg, #ffb300, #ffa000);
        color: #fff;
        transform: translateY(-2px);
    }
    .btn-dios-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
        border: none;
    }
    .btn-dios-danger:hover {
        background: linear-gradient(135deg, #c82333, #a71d2a);
        transform: translateY(-2px);
    }
    .btn-dios-success {
        background: linear-gradient(135deg, #28a745, #218838);
        color: #fff;
        border: none;
    }
    .btn-dios-success:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
    }
    .badge-dios-active {
        background: #d4edda;
        color: #155724;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-dios-blocked {
        background: #f8d7da;
        color: #721c24;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-dios-warning {
        background: #fff3cd;
        color: #856404;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .table-dios {
        background: #ffffff;
        color: #2c3e50;
    }
    .table-dios thead th {
        background: #f8f9fa;
        color: #2c3e50;
        border-bottom: 2px solid #ffc107;
        font-weight: 600;
    }
    .table-dios tbody tr:hover {
        background: #fff8e1;
    }
    .card-dios {
        background: #ffffff;
        border-radius: 12px;
        border: none;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .card-header-dios {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border-bottom: 3px solid #ffc107;
        padding: 15px 20px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 16px;
    }
    .dios-header {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        text-align: center;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .dios-header h1 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .crown-icon {
        font-size: 45px;
        color: #ffc107;
        margin-bottom: 10px;
    }
    .alert-dios {
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        color: #856404;
        border-radius: 8px;
    }
</style>

<div class="dios-container">
    <!-- CABECERA -->
    <div class="dios-header">
        <div class="crown-icon">👑</div>
        <h1>PANEL DE CONTROL DIOS</h1>
        <p>
            <i class="fas fa-user-secret"></i> <strong><?php echo $_SESSION['dios_usuario']; ?></strong> | 
            <i class="fas fa-network-wired"></i> IP: <?php echo $_SESSION['dios_ip']; ?>
        </p>
        
        <!-- Indicador de estado del sistema completo -->
        <div class="mt-2">
            <?php if($sistema_completo): ?>
                <span class="badge-sistema-abierto"><i class="fas fa-check-circle"></i> SISTEMA COMPLETO: ABIERTO</span>
            <?php else: ?>
                <span class="badge-sistema-cerrado"><i class="fas fa-ban"></i> SISTEMA COMPLETO: CERRADO</span>
            <?php endif; ?>
        </div>
        
        <a href="logout.php" class="btn btn-danger btn-sm mt-3">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión DIOS
        </a>
    </div>

    <!-- Mensajes -->
    <?php if(isset($_SESSION['msg_dios'])): ?>
        <div class="alert alert-dios alert-dismissible fade show">
            <i class="fas fa-crown"></i> <?php echo $_SESSION['msg_dios']; unset($_SESSION['msg_dios']); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <!-- ============================================== -->
    <!-- SECCIÓN: CONTROL DE SISTEMA COMPLETO -->
    <!-- ============================================== -->
    <div class="card card-dios mb-4">
        <div class="card-header-dios" style="border-left: 4px solid #dc3545;">
            <i class="fas fa-globe-americas"></i> CONTROL DE SISTEMA COMPLETO
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <?php if($sistema_completo): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> El sistema está <strong>ABIERTO</strong> y funcionando normalmente.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-ban"></i> El sistema está <strong>CERRADO</strong>.
                            <?php if($razon_cierre): ?>
                                <br><strong>📌 Motivo:</strong> <?php echo htmlspecialchars($razon_cierre); ?>
                            <?php endif; ?>
                            <?php if($ultimo_cierre_por): ?>
                                <br><strong>👤 Cerrado por:</strong> <?php echo htmlspecialchars($ultimo_cierre_por); ?>
                            <?php endif; ?>
                            <?php if($fecha_cierre): ?>
                                <br><strong>📅 Fecha:</strong> <?php echo htmlspecialchars($fecha_cierre); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-5">
                    <form method="POST">
                        <input type="hidden" name="accion" value="toggle_sistema_completo">
                        <input type="hidden" name="sistema_completo" value="<?php echo $sistema_completo ? '1' : '0'; ?>">
                        <button type="submit" class="btn <?php echo $sistema_completo ? 'btn-dios-danger' : 'btn-dios-success'; ?> btn-lg btn-block" 
                                style="padding: 12px; font-size: 16px;"
                                onclick="return confirm('<?php echo $sistema_completo ? '¿Estás seguro de CERRAR el sistema completo? Nadie podrá acceder.' : '¿Estás seguro de REABRIR el sistema completo?'; ?>')">
                            <?php if($sistema_completo): ?>
                                <i class="fas fa-lock"></i> CERRAR SISTEMA COMPLETO
                            <?php else: ?>
                                <i class="fas fa-unlock-alt"></i> ABRIR SISTEMA COMPLETO
                            <?php endif; ?>
                        </button>
                    </form>
                    <small class="text-muted">⚠️ Al cerrar el sistema, NADIE podrá acceder. Solo tú desde este panel podrás reabrirlo.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: CONTROLES RÁPIDOS -->
    <!-- ============================================== -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-dios">
                <div class="card-header-dios">
                    <i class="fas fa-power-off"></i> Recuperación de Contraseña
                </div>
                <div class="card-body">
                    <p>Estado: <strong class="<?php echo $sistema_activo == '1' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $sistema_activo == '1' ? '🟢 ACTIVO' : '🔴 CERRADO'; ?>
                    </strong></p>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="accion" value="toggle_sistema">
                        <input type="hidden" name="sistema_activo" value="<?php echo $sistema_activo; ?>">
                        <button type="submit" class="btn btn-sm <?php echo $sistema_activo == '1' ? 'btn-dios-danger' : 'btn-dios-success'; ?>">
                            <?php echo $sistema_activo == '1' ? '🔒 Cerrar Recuperación' : '🔓 Abrir Recuperación'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-dios">
                <div class="card-header-dios">
                    <i class="fas fa-tools"></i> Modo Mantenimiento
                </div>
                <div class="card-body">
                    <p>Estado: <strong class="<?php echo $modo_mantenimiento == '1' ? 'text-warning' : 'text-success'; ?>">
                        <?php echo $modo_mantenimiento == '1' ? '🛠️ ACTIVO' : '✅ NORMAL'; ?>
                    </strong></p>
                    <form method="POST">
                        <input type="hidden" name="accion" value="toggle_mantenimiento">
                        <input type="hidden" name="modo_mantenimiento" value="<?php echo $modo_mantenimiento; ?>">
                        <button type="submit" class="btn btn-sm btn-warning">
                            <?php echo $modo_mantenimiento == '1' ? 'Desactivar' : 'Activar Mantenimiento'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: ESTADÍSTICAS -->
    <!-- ============================================== -->
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-hand-paper"></i></div>
                <div class="value"><?php echo $stats['intentos_hoy']; ?></div>
                <div class="label">Intentos Hoy</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-ban"></i></div>
                <div class="value"><?php echo $stats['bloqueos_activos']; ?></div>
                <div class="label">Bloqueos Activos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-key"></i></div>
                <div class="value"><?php echo $stats['tokens_invalidos_hoy']; ?></div>
                <div class="label">Tokens Inválidos</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                <div class="value"><?php echo $stats['rps_actual']; ?></div>
                <div class="label">RPS Actual</div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: CONFIGURACIÓN DE LÍMITES -->
    <!-- ============================================== -->
    <div class="card card-dios mt-3">
        <div class="card-header-dios">
            <i class="fas fa-sliders-h"></i> Configuración de Límites
        </div>
        <div class="card-body">
            <form method="POST" class="row">
                <input type="hidden" name="accion" value="actualizar_limites">
                <div class="col-md-2">
                    <label>Intentos por hora</label>
                    <input type="number" name="limite_recuperar" class="form-control" value="<?php echo $limite_recuperar; ?>">
                </div>
                <div class="col-md-2">
                    <label>Bloqueo inicial (h)</label>
                    <input type="number" name="bloqueo_horas" class="form-control" value="<?php echo $bloqueo_horas; ?>">
                </div>
                <div class="col-md-2">
                    <label>Bloqueo avanzado (h)</label>
                    <input type="number" name="bloqueo_incremento" class="form-control" value="<?php echo $bloqueo_incremento; ?>">
                </div>
                <div class="col-md-2">
                    <label>RPS por IP (/10s)</label>
                    <input type="number" name="rps_limite" class="form-control" value="<?php echo $rps_limite; ?>">
                </div>
                <div class="col-md-2">
                    <label>RPS Global (%)</label>
                    <input type="number" name="rps_global_porcentaje" class="form-control" value="<?php echo $rps_global; ?>" min="1" max="50">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dios w-100 mt-4"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: BUSCADOR Y LISTA DE USUARIOS -->
    <!-- ============================================== -->
    <div class="card card-dios mt-3">
        <div class="card-header-dios">
            <i class="fas fa-users"></i> Gestión de Usuarios
        </div>
        <div class="card-body">
            <!-- Buscador -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <form method="GET" action="" class="search-box d-flex">
                        <input type="text" name="buscar" placeholder="🔍 Buscar por nombre, email, username o ID..." 
                               value="<?php echo htmlspecialchars($buscar); ?>" class="flex-grow-1">
                        <button type="submit"><i class="fas fa-search"></i></button>
                        <?php if(!empty($buscar)): ?>
                            <a href="index.php" class="btn btn-sm btn-secondary ml-2">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <span class="text-muted">Total: <?php echo $total_usuarios; ?> usuarios</span>
                </div>
            </div>
            
            <!-- Tabla de usuarios -->
            <div class="table-responsive">
                <table class="table table-dios table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Estado</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($usuarios) > 0): ?>
                            <?php while($u = mysqli_fetch_assoc($usuarios)): ?>
                            <tr>
                                <td><?php echo $u['id']; ?></td>
                                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['username']); ?></td>
                                <td>
                                    <?php if($u['status'] == 1): ?>
                                        <span class="badge-dios-active"><i class="fas fa-check-circle"></i> ACTIVO</span>
                                    <?php else: ?>
                                        <span class="badge-dios-blocked"><i class="fas fa-ban"></i> BLOQUEADO</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-dios" data-toggle="modal" data-target="#modalPassword<?php echo $u['id']; ?>">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    
                                    <?php if($u['status'] == 1): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="accion" value="bloquear_usuario">
                                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-dios-danger" onclick="return confirm('¿Bloquear a <?php echo addslashes($u['nombre']); ?>?')">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="accion" value="desbloquear_usuario">
                                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-dios-success" onclick="return confirm('¿Desbloquear a <?php echo addslashes($u['nombre']); ?>?')">
                                                <i class="fas fa-unlock-alt"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="modalPassword<?php echo $u['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <input type="hidden" name="accion" value="cambiar_password">
                                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cambiar contraseña</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Usuario: <strong><?php echo htmlspecialchars($u['nombre']); ?></strong></p>
                                                        <p>Email: <?php echo htmlspecialchars($u['email']); ?></p>
                                                        <div class="form-group">
                                                            <label>Nueva contraseña:</label>
                                                            <input type="text" name="nueva_password" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-dios">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No se encontraron usuarios</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <?php if($total_paginas > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina-1; ?>&buscar=<?php echo urlencode($buscar); ?>">« Anterior</a>
                    </li>
                    <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $i == $pagina ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($buscar); ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $pagina >= $total_paginas ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina+1; ?>&buscar=<?php echo urlencode($buscar); ?>">Siguiente »</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: IPs BLOQUEADAS -->
    <!-- ============================================== -->
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card card-dios">
                <div class="card-header-dios" style="border-left: 4px solid #dc3545;">
                    <i class="fas fa-ban"></i> IPs Bloqueadas
                </div>
                <div class="card-body">
                    <?php if(mysqli_num_rows($bloqueos) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-dios table-sm">
                                <thead>
                                    <tr><th>IP</th><th>Email</th><th>Desbloqueo</th><th>Acción</th></tr>
                                </thead>
                                <tbody>
                                <?php while($row = mysqli_fetch_assoc($bloqueos)): ?>
                                    <tr>
                                        <td><?php echo $row['ip']; ?></td>
                                        <td><?php echo $row['email'] ?: 'N/A'; ?></td>
                                        <td><?php echo $row['desbloqueo_en']; ?></td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="accion" value="desbloquear_ip">
                                                <input type="hidden" name="ip" value="<?php echo $row['ip']; ?>">
                                                <button type="submit" class="btn btn-sm btn-dios-success"><i class="fas fa-unlock-alt"></i></button>
                                            </form>
                                    </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <form method="POST" class="mt-2">
                            <input type="hidden" name="accion" value="desbloquear_todo">
                            <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Desbloquear TODOS los bloqueos?')">
                                <i class="fas fa-unlock-alt"></i> Desbloquear Todos
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="text-success"><i class="fas fa-check-circle"></i> No hay bloqueos activos</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-dios">
                <div class="card-header-dios" style="border-left: 4px solid #ffc107;">
                    <i class="fas fa-exclamation-triangle"></i> Top IPs Sospechosas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dios table-sm">
                            <thead>
                                <tr><th>IP</th><th>Intentos</th></tr>
                            </thead>
                            <tbody>
                            <?php while($row = mysqli_fetch_assoc($top_ips)): ?>
                                <tr>
                                    <td><code><?php echo $row['ip']; ?></code></td>
                                    <td class="text-danger"><?php echo $row['total']; ?> intentos</td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- SECCIÓN: LOG DE INTENTOS -->
    <!-- ============================================== -->
    <div class="card card-dios mt-3 mb-4">
        <div class="card-header-dios">
            <i class="fas fa-history"></i> Log de Intentos Recientes
        </div>
        <div class="card-body table-responsive">
            <table class="table table-dios table-sm">
                <thead>
                    <tr>
                        <th>Fecha</th><th>IP</th><th>Email</th><th>Tipo</th><th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($logs_recientes)): ?>
                        <tr>
                            <td><small><?php echo $row['fecha']; ?></small></td>
                            <td><?php echo $row['ip']; ?></td>
                            <td><?php echo $row['email'] ?: 'N/A'; ?></td>
                            <td>
                                <?php if($row['tipo'] == 'recuperar'): ?>
                                    <span class="badge-dios-warning">Recuperar</span>
                                <?php else: ?>
                                    <span class="badge-dios-warning">Cambiar</span>
                                <?php endif; ?>
                            </td>
                            <td><small><?php echo substr($row['user_agent'], 0, 40); ?>...</small></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("footer_dios.php"); ?>