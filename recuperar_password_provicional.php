<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulo = "Recuperar Contraseña Provisional - Migración a Hash";
include('funciones/functions.php');

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    
    $username = e($_POST['username']);
    $nueva_password = e($_POST['nueva_password']);
    $confirmar_password = e($_POST['confirmar_password']);
    
    $errors = [];
    
    // Validaciones
    if (empty($username)) {
        $errors[] = "El nombre de usuario o cédula es requerido";
    }
    
    if (empty($nueva_password)) {
        $errors[] = "La nueva contraseña es requerida";
    }
    
    if ($nueva_password !== $confirmar_password) {
        $errors[] = "Las contraseñas no coinciden";
    }
    
    if (strlen($nueva_password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if (empty($errors)) {
        try {
            // Buscar usuario por username, email o idusuario
            $query = "SELECT id, username, idusuario, email FROM users 
                     WHERE username = ? OR email = ? OR idusuario = ? LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sss", $username, $username, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                // Generar hash seguro de la nueva contraseña
                $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
                
                // Actualizar la contraseña en la base de datos
                $update_query = "UPDATE users SET password = ?, fecha_act = NOW() WHERE id = ?";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->bind_param("si", $password_hash, $usuario['id']);
                
                if ($update_stmt->execute()) {
                    $success_message = "¡Contraseña actualizada exitosamente!<br>
                                      Usuario: <strong>{$usuario['username']}</strong><br>
                                      Cédula: <strong>{$usuario['idusuario']}</strong><br>
                                      Nueva contraseña ha sido guardada con hash seguro.";
                    
                    // Registrar en auditoría
                    registrarAuditoria(
                        "UPDATE", 
                        "users", 
                        $usuario['id'], 
                        null, 
                        [
                            'username' => $usuario['username'],
                            'idusuario' => $usuario['idusuario'],
                            'accion' => 'Migración a password_hash'
                        ], 
                        "Autenticación", 
                        "Contraseña migrada a hash seguro - Provisional"
                    );
                    
                } else {
                    $errors[] = "Error al actualizar la contraseña: " . $db->error;
                }
                
                $update_stmt->close();
            } else {
                $errors[] = "Usuario no encontrado. Verifique el nombre de usuario, cédula o email.";
            }
            
            $stmt->close();
            
        } catch (Exception $e) {
            $errors[] = "Error en el proceso: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Migración a Contraseñas Seguras</title>
<?php echo $bootstrap_head; ?>

<!-- FAVICON -->
<link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="images/favicon/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="icon" href="images/favicon/favicon.ico">

<style>
.carousel-control-prev-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
  background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}

.security-info {
    background-color: #e8f5e8;
    border-left: 4px solid #28a745;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-custom {
    border-left: 4px solid #ffc107;
    background-color: #fff3cd;
}
</style>
</head>
<body>

<div class="container text-center">
    <?php echo $logopertenenciag; ?>
</div>

<nav class="nav nav-pills justify-content-end"> 
    <div class="btn-group-horizontal">
        <span class="d-inline-block" data-toggle="popover" data-content="...">
            <a type="link" class="btn btn-outline-danger" href="recuperar_password.php">
                <i class="fa fa-unlock-alt"></i> Recuperar Contraseña
            </a>
            <a type="link" class="btn btn-outline-danger" href="recuperar_password_provicional.php">
                <i class="fa fa-unlock-alt"></i> Recuperar Contraseña Provisional
            </a>
        </span>
        <span class="d-inline-block" data-toggle="popover" data-content="...">
            <a class="btn btn-outline-success" href="login.php">
                <i class="fa fa-sign-in-alt"></i> Volver al Login
            </a>
        </span>
    </div>
</nav>

<hr>

<div id="main" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="security-info">
                <h4><i class="fas fa-shield-alt"></i> Migración a Contraseñas Seguras</h4>
                <p class="mb-0">
                    Esta herramienta permite actualizar las contraseñas al nuevo sistema de hash seguro. 
                    Las nuevas contraseñas se almacenarán de forma encriptada en la base de datos.
                </p>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center mb-0">
                        <i class="fas fa-key"></i> Establecer Nueva Contraseña
                    </h3>
                </div>
                <div class="card-body">
                    
                    <!-- Mensajes de éxito -->
                    <?php if (isset($success_message)) : ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h4><i class="fas fa-check-circle"></i> ¡Éxito!</h4>
                            <?php echo $success_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="text-center">
                            <a href="login.php" class="btn btn-success">
                                <i class="fa fa-sign-in-alt"></i> Ir al Login
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mensajes de error -->
                    <?php if (!empty($errors)) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h4><i class="fas fa-exclamation-triangle"></i> Errores encontrados:</h4>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulario (solo mostrar si no hay éxito) -->
                    <?php if (!isset($success_message)) : ?>
                    <form method="post" action="recuperar_password_provicional.php" autocomplete="off">
                        
                        <div class="form-group">
                            <label for="username">
                                <i class="fas fa-user"></i> Usuario, Cédula o Email:
                            </label>
                            <input type="text" class="form-control" id="username" 
                                   name="username" placeholder="Ingrese usuario, cédula o email" 
                                   value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                            <small class="form-text text-muted">
                                Puede usar: nombre de usuario, cédula (V-12345678) o email
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="nueva_password">
                                <i class="fas fa-lock"></i> Nueva Contraseña:
                            </label>
                            <input type="password" class="form-control" id="nueva_password" 
                                   name="nueva_password" placeholder="Mínimo 6 caracteres" 
                                   minlength="6" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_password">
                                <i class="fas fa-lock"></i> Confirmar Contraseña:
                            </label>
                            <input type="password" class="form-control" id="confirmar_password" 
                                   name="confirmar_password" placeholder="Repita la contraseña" 
                                   minlength="6" required>
                        </div>
                        
                        <div class="alert alert-custom">
                            <h5><i class="fas fa-info-circle"></i> Información Importante:</h5>
                            <ul class="mb-0">
                                <li>La nueva contraseña se almacenará con <strong>encriptación segura</strong></li>
                                <li>El usuario podrá iniciar sesión inmediatamente después</li>
                                <li>Esta acción queda registrada en el sistema de auditoría</li>
                            </ul>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sync-alt"></i> Actualizar a Contraseña Segura
                            </button>
                        </div>
                        
                    </form>
                    <?php endif; ?>
                    
                </div>
            </div>
            
            <div class="mt-3 text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt"></i> Sistema de Autenticación Segura
                </small>
            </div>
            
        </div>
    </div>
</div>

<hr>

<?php
// Incluir funciones de conteo y contenido si existen
if (function_exists('conteo')) {
    conteo();
}
if (function_exists('contenido')) {
    contenido('password_recovery');
}
?>

<script>
// Validación de coincidencia de contraseñas
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('nueva_password');
    const confirmPassword = document.getElementById('confirmar_password');
    
    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
});
</script>

</body>
</html>