<?php
// .dios/login.php - Login secreto del MODO DIOS
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel DIOS - Acceso Restringido";

// Incluir config DIOS y funciones del sistema
require_once 'config.php';
require_once '../funciones/functions.php';

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
    body {
        background: #f0f5fb;
    }
    .login-dios-card,
    .login-upc-card {
        max-width: 450px;
        margin: 80px auto;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.12);
    }
    .login-dios-card {
        background: rgba(10,14,39,0.95);
        border-top: 4px solid #ffd700;
    }
    .login-upc-card {
        background: #ffffff;
        border: 1px solid #cfe0f6;
    }
    .login-brand-header {
        background: #004a8f;
        color: #ffffff;
        padding: 30px 20px;
        text-align: center;
    }
    .login-brand-logo {
        max-width: 120px;
        margin-bottom: 15px;
    }
    .login-brand-title {
        margin-bottom: 8px;
        font-size: 20px;
        font-weight: 700;
    }
    .login-brand-subtitle {
        color: #d9e8ff;
        margin: 0;
        font-size: 14px;
    }
    .login-brand-body {
        padding: 30px;
    }
    .login-brand-footer {
        background: #f4f8ff;
        padding: 18px 20px;
        text-align: center;
        color: #3a5f8d;
        border-top: 1px solid #d8e5f8;
    }
    .btn-dios-login {
        background: linear-gradient(135deg, #004a8f, #2463c0);
        color: #fff;
        font-weight: bold;
        border: none;
        padding: 12px;
        font-size: 16px;
    }
    .btn-dios-login:hover {
        background: linear-gradient(135deg, #003366, #1f4f8c);
        color: #fff;
    }
    .login-upc-card .form-control-dios-login {
        background: #ffffff;
        border: 1px solid #a8c4f0;
        color: #0d264d;
    }
    .login-upc-card .form-control-dios-login:focus {
        border-color: #004a8f;
        box-shadow: 0 0 6px rgba(0,74,143,0.25);
        background: #ffffff;
        color: #0d264d;
    }
    .login-upc-card .btn-dios-login {
        background: #004a8f;
    }
    .login-upc-card .btn-dios-login:hover {
        background: #003366;
    }
    .login-upc-card .alert {
        background: #e1efff;
        color: #0f3173;
        border: 1px solid #9dc6ff;
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

<div class="login-upc-card">
    <div class="login-brand-header">
        <img src="../images/uptpc.png" alt="UPTPC Logo" class="login-brand-logo">
        <div class="login-brand-title">UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO</div>
        <p class="login-brand-subtitle">Sistema de Control de Estudios</p>
    </div>
    <div class="login-brand-body">
        <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Acceso denegado.</strong> Credenciales incorrectas.
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
    <div class="login-brand-footer">
        <small>© <?php echo date('Y'); ?> Universidad Politécnica Territorial de Puerto Cabello</small>
    </div>
</div>

<?php include("footer_dios.php"); ?>