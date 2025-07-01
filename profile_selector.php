<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'funciones/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$availableProfiles = getAvailableProfiles();

// Verifica que $availableProfiles es un array
if (!is_array($availableProfiles)) {
    $availableProfiles = [];
}

// Configuración de rutas para cada perfil
$profileRoutes = [
    'admin' => 'admin/index.php',
    'super_user' => 'super_user/index.php',
    'docente' => 'docente/index.php',
    'estudiante' => 'estudiante/index.php',
    'usuario' => 'perfil/mi_cuenta.php'
];

// Redirección automática si solo tiene un perfil
if (count($availableProfiles) == 1) {
    $_SESSION['current_profile'] = $availableProfiles[0];
    $route = $profileRoutes[$availableProfiles[0]] ?? 'index.php';
    header("Location: $route");
    exit();
}

// Procesar selección de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile'])) {
    if (in_array($_POST['profile'], $availableProfiles)) {
        $_SESSION['current_profile'] = $_POST['profile'];
        $route = $profileRoutes[$_POST['profile']] ?? 'index.php';
        header("Location: $route");
        exit();
    } else {
        $error = "Perfil no válido";
    }
}

// Obtener nombre de usuario
$username = isset($_SESSION['user']['username']) ? (string)$_SESSION['user']['username'] : 'Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .profile-card:hover {
            transform: scale(1.05);
        }
        .profile-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Selecciona tu perfil</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-4">Hola, <strong><?php echo htmlspecialchars($username); ?></strong>. Tienes acceso a los siguientes perfiles:</p>
                        
                       
                        
                        <form method="POST" action="profile_selector.php">
                            <div class="row">
                                <?php foreach ($availableProfiles as $profile): ?>
                                    <?php $profile = (string)$profile; ?>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" name="profile" value="<?php echo htmlspecialchars($profile); ?>" class="btn btn-outline-primary w-100 py-4">
                                            <div class="profile-icon">
                                                <?php 
                                                    switch($profile) {
                                                        case 'admin':
                                                            echo '<i class="fas fa-user-shield"></i>';
                                                            break;
                                                        case 'super_user':
                                                            echo '<i class="fas fa-crown"></i>';
                                                            break;
                                                        case 'docente':
                                                            echo '<i class="fas fa-chalkboard-teacher"></i>';
                                                            break;
                                                        case 'estudiante':
                                                            echo '<i class="fas fa-user-graduate"></i>';
                                                            break;
                                                        default:
                                                            echo '<i class="fas fa-user"></i>';
                                                    }
                                                ?>
                                            </div>
                                            <h5 class="mb-1">
                                                <?php 
                                                    switch($profile) {
                                                        case 'admin':
                                                            echo 'Administrador';
                                                            break;
                                                        case 'super_user':
                                                            echo 'Super Usuario';
                                                            break;
                                                        case 'docente':
                                                            echo 'Docente';
                                                            break;
                                                        case 'estudiante':
                                                            echo 'Estudiante';
                                                            break;
                                                        default:
                                                            echo 'Usuario';
                                                    }
                                                ?>
                                            </h5>
                                            <small class="text-muted">Haz clic para entrar como <?php echo htmlspecialchars($profile); ?></small>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>