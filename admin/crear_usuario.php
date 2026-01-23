<?php
// Iniciar sesión solo si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión a la base de datos
require_once('../funciones/functions.php');

// Verificar si la conexión a la base de datos está disponible
if (!isset($db)) {
    die("Error: No se pudo establecer conexión con la base de datos. Verifica tu archivo de conexión.");
}

// Función para verificar si usuario existe
if (!function_exists('usuarioExiste')) {
    function usuarioExiste($username) {
        global $db;
        try {
            $sql = "SELECT id FROM users WHERE username = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en usuarioExiste: " . $e->getMessage());
            return false;
        }
    }
}

// Función para verificar si email existe
if (!function_exists('emailExiste')) {
    function emailExiste($email) {
        global $db;
        try {
            $sql = "SELECT id FROM users WHERE email = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en emailExiste: " . $e->getMessage());
            return false;
        }
    }
}

// Función para insertar usuario
if (!function_exists('insertarUsuario')) {
    function insertarUsuario($datos) {
        global $db;
        
        try {
            // Preparar campos y valores
            $campos = implode(", ", array_keys($datos));
            $placeholders = str_repeat('?,', count($datos) - 1) . '?';
            
            $sql = "INSERT INTO users ($campos) VALUES ($placeholders)";
            
            $stmt = $db->prepare($sql);
            
            // Ejecutar con los valores directamente
            if ($stmt->execute(array_values($datos))) {
                return ['exito' => true, 'mensaje' => 'Usuario creado correctamente'];
            } else {
                $errorInfo = $stmt->errorInfo();
                return ['exito' => false, 'mensaje' => 'Error al ejecutar la consulta: ' . $errorInfo[2]];
            }
        } catch (PDOException $e) {
            error_log("Error en insertarUsuario: " . $e->getMessage());
            return ['exito' => false, 'mensaje' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }
}

// Inicializar variables
$mensaje = '';
$error = false;
$valores = [
    'nombre' => '',
    'username' => '',
    'email' => '',
    'tlf' => '',
    'cel' => '',
    'direccion' => '',
    'ciudad' => '',
    'estado' => '',
    'municipio' => '',
    'parroquia' => '',
    'user_type' => 'user'
];

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recoger y sanitizar los datos
        $valores = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'tlf' => trim($_POST['tlf'] ?? ''),
            'cel' => trim($_POST['cel'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'ciudad' => trim($_POST['ciudad'] ?? ''),
            'estado' => trim($_POST['estado'] ?? ''),
            'municipio' => trim($_POST['municipio'] ?? ''),
            'parroquia' => trim($_POST['parroquia'] ?? ''),
            'user_type' => $_POST['user_type'] ?? 'user'
        ];
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validaciones básicas
        $campos_requeridos = ['nombre', 'username', 'email', 'password', 'confirm_password'];
        $faltantes = [];
        
        foreach ($campos_requeridos as $campo) {
            if (empty($valores[$campo] ?? '') && ($campo !== 'password' && $campo !== 'confirm_password')) {
                $faltantes[] = $campo;
            } elseif (($campo === 'password' || $campo === 'confirm_password') && empty($$campo)) {
                $faltantes[] = $campo;
            }
        }
        
        if (!empty($faltantes)) {
            throw new Exception('Los siguientes campos son obligatorios: ' . implode(', ', $faltantes));
        }
        
        if ($password !== $confirm_password) {
            throw new Exception('Las contraseñas no coinciden.');
        }
        
        if (strlen($password) < 6) {
            throw new Exception('La contraseña debe tener al menos 6 caracteres.');
        }
        
        if (!filter_var($valores['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('El correo electrónico no tiene un formato válido.');
        }
        
        // Verificar si el usuario o email ya existen
        if (usuarioExiste($valores['username'])) {
            throw new Exception('El nombre de usuario ya está en uso.');
        }
        
        if (emailExiste($valores['email'])) {
            throw new Exception('El correo electrónico ya está registrado.');
        }
        
        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Preparar datos para inserción
        $datos_usuario = $valores;
        $datos_usuario['password'] = $hashed_password;
        $datos_usuario['fecha_ingreso'] = date('Y-m-d H:i:s');
        $datos_usuario['fecha_act'] = date('Y-m-d H:i:s');
        $datos_usuario['status'] = 1; // Asumiendo que 1 es activo
        
        // Insertar en la base de datos
        $resultado = insertarUsuario($datos_usuario);
        
        if ($resultado['exito']) {
            $_SESSION['mensaje_exito'] = 'Usuario creado exitosamente!';
            
            // Limpiar el formulario
            $_SESSION['valores_formulario'] = [
                'nombre' => '',
                'username' => '',
                'email' => '',
                'tlf' => '',
                'cel' => '',
                'direccion' => '',
                'ciudad' => '',
                'estado' => '',
                'municipio' => '',
                'parroquia' => '',
                'user_type' => 'user'
            ];
        } else {
            throw new Exception($resultado['mensaje']);
        }
        
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $error = true;
        $_SESSION['valores_formulario'] = $valores;
        $_SESSION['mensaje_error'] = $mensaje;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Recuperar mensajes y valores de la sesión
if (isset($_SESSION['mensaje_error'])) {
    $mensaje = $_SESSION['mensaje_error'];
    $error = true;
    unset($_SESSION['mensaje_error']);
}

if (isset($_SESSION['mensaje_exito'])) {
    $mensaje = $_SESSION['mensaje_exito'];
    $error = false;
    unset($_SESSION['mensaje_exito']);
}

if (isset($_SESSION['valores_formulario'])) {
    $valores = array_merge($valores, $_SESSION['valores_formulario']);
    unset($_SESSION['valores_formulario']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
        }
        
        .radio-option input {
            margin-right: 5px;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .mensaje {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
        
        .error {
            background-color: #ffdddd;
            color: #d8000c;
        }
        
        .exito {
            background-color: #ddffdd;
            color: #4F8A10;
        }
        
        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Usuario</h1>
        
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?php echo $error ? 'error' : 'exito'; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>
        
        <form id="formularioUsuario" method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nombre">Nombre completo:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($valores['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($valores['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($valores['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="tlf">Teléfono fijo:</label>
                    <input type="tel" id="tlf" name="tlf" value="<?php echo htmlspecialchars($valores['tlf']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="cel">Teléfono celular:</label>
                    <input type="tel" id="cel" name="cel" value="<?php echo htmlspecialchars($valores['cel']); ?>">
                </div>
                
                <div class="form-group full-width">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($valores['direccion']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($valores['ciudad']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($valores['estado']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="municipio">Municipio:</label>
                    <input type="text" id="municipio" name="municipio" value="<?php echo htmlspecialchars($valores['municipio']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="parroquia">Parroquia:</label>
                    <input type="text" id="parroquia" name="parroquia" value="<?php echo htmlspecialchars($valores['parroquia']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Rol del usuario:</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="user_type_user" name="user_type" value="user" <?php echo $valores['user_type'] === 'user' ? 'checked' : ''; ?>>
                            <label for="user_type_user">Usuario Normal</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="user_type_admin" name="user_type" value="admin" <?php echo $valores['user_type'] === 'admin' ? 'checked' : ''; ?>>
                            <label for="user_type_admin">Administrador</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <button type="submit">Crear Usuario</button>
        </form>
        
        <?php if ($error && isset($_POST)): ?>
            <div class="debug-info">
                <h3>Información de depuración:</h3>
                <p><strong>Error:</strong> <?php echo htmlspecialchars($mensaje); ?></p>
                <p><strong>Datos enviados:</strong></p>
                <pre><?php print_r($_POST); ?></pre>
                <?php if (isset($resultado)): ?>
                    <p><strong>Resultado de inserción:</strong></p>
                    <pre><?php print_r($resultado); ?></pre>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('formularioUsuario').addEventListener('submit', function(event) {
            // Validación del lado del cliente
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            
            if (password.length < 6) {
                alert('La contraseña debe tener al menos 6 caracteres');
                event.preventDefault();
                return false;
            }
            
            if (password !== confirm_password) {
                alert('Las contraseñas no coinciden');
                event.preventDefault();
                return false;
            }
            
            // Validar email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor ingrese un correo electrónico válido');
                event.preventDefault();
                return false;
            }
            
            return true;
        });

        <?php if ($error): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const errorDiv = document.querySelector('.mensaje.error');
                if (errorDiv) {
                    // Desplazarse al mensaje de error
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Resaltar campos con error
                    const errorText = errorDiv.textContent.toLowerCase();
                    if (errorText.includes('nombre de usuario')) {
                        document.getElementById('username').focus();
                    } else if (errorText.includes('correo electrónico')) {
                        document.getElementById('email').focus();
                    } else if (errorText.includes('contraseña')) {
                        document.getElementById('password').focus();
                    }
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>