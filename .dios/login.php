<?php
// .dios/login.php - Login secreto del MODO DIOS
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel DIOS - Acceso Restringido";

// Incluir funciones y configuraciones del sistema
require_once '../funciones/functions.php';

// Incluir config DIOS
require_once 'config.php';

// Si ya está logueado, ir al panel
if (isset($_SESSION['dios_autenticado']) && $_SESSION['dios_autenticado'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Verificar token secreto (acceso rápido)
    if (isset($_GET['token']) && $_GET['token'] === DIOS_TOKEN) {
        $_SESSION['dios_autenticado'] = true;
        $_SESSION['dios_usuario'] = 'token_master';
        $_SESSION['dios_acceso'] = date('Y-m-d H:i:s');
        $_SESSION['dios_ip'] = $_SERVER['REMOTE_ADDR'];
        header('Location: index.php');
        exit;
    }
    
    // Verificar usuario y contraseña
    if ($username === DIOS_USER && password_verify($password, DIOS_PASS_HASH)) {
        $_SESSION['dios_autenticado'] = true;
        $_SESSION['dios_usuario'] = $username;
        $_SESSION['dios_acceso'] = date('Y-m-d H:i:s');
        $_SESSION['dios_ip'] = $_SERVER['REMOTE_ADDR'];
        header('Location: index.php');
        exit;
    } else {
        $error = '⛔ Acceso denegado. Credenciales incorrectas.';
        // Registrar intento fallido
        file_put_contents(__DIR__ . '/.dios_log.txt', date('Y-m-d H:i:s') . ' - Intento fallido desde ' . $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);
    }
}

// Incluir el head DIOS
include("head_dios.php");
?>

<!-- Estilos adicionales para el login DIOS -->
<style>
    .login-dios-card {
        max-width: 450px;
        margin: 80px auto;
        border-top: 4px solid #ffd700;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        background: rgba(10,14,39,0.95);
        border-radius: 12px;
        overflow: hidden;
    }
    .dios-crown-icon {
        font-size: 60px;
        text-align: center;
        color: #ffd700;
        margin-bottom: 10px;
    }
    .bg-dios-dark {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        padding: 25px;
    }
    .btn-dios-login {
        background: linear-gradient(135deg, #ffd700, #ff8c00);
        color: #000;
        font-weight: bold;
        border: none;
        padding: 12px;
        font-size: 16px;
    }
    .btn-dios-login:hover {
        background: linear-gradient(135deg, #ff8c00, #ff6600);
        color: #fff;
    }
    .form-control-dios-login {
        background: #1a1f3a;
        border: 1px solid #2a2f4a;
        color: #fff;
        border-radius: 8px;
        padding: 10px 15px;
    }
    .form-control-dios-login:focus {
        border-color: #ffd700;
        box-shadow: 0 0 5px rgba(255,215,0,0.5);
        background: #1a1f3a;
        color: #fff;
    }
</style>

<div class="login-dios-card">
    <div class="bg-dios-dark text-center">
        <div class="dios-crown-icon">👑</div>
        <h3 style="color: #ffd700; margin-bottom: 5px;">PANEL DE CONTROL DIOS</h3>
        <small style="color: #888;">Sistema de Control de Estudios - UPTPC</small>
    </div>
    <div class="card-body" style="padding: 30px;">
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-user-shield"></i> Usuario DIOS</label>
                <input type="text" name="username" class="form-control-dios-login form-control" placeholder="Ingrese usuario" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-key"></i> Contraseña</label>
                <input type="password" name="password" class="form-control-dios-login form-control" placeholder="Ingrese contraseña" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-dios-login btn-block">
                <i class="fas fa-sign-in-alt"></i> Acceder al Panel DIOS
            </button>
        </form>
    </div>
    <div class="card-footer text-center" style="background: #1a1a2e; border-top: 1px solid #2a2f4a;">
        <small style="color: #666;"><i class="fas fa-shield-alt"></i> Acceso autorizado exclusivo para el Administrador del Sistema</small>
    </div>
</div>

<?php include("footer_dios.php"); ?>