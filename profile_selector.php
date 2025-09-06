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

// Inicializa la variable de error
$error = null;

// Configuración de rutas para cada perfil
$profileRoutes = [
    'admin' => 'admin/index.php',
    'super_user' => 'super_user/index.php',
    'docente' => 'docente/index.php',
    'estudiante' => 'estudiante/index.php',
    'usuario' => 'director_de_carrera/index.php'
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
    $selectedProfile = is_array($_POST['profile']) ? $_POST['profile'][0] : $_POST['profile'];
    if (in_array($selectedProfile, $availableProfiles)) {
        $_SESSION['current_profile'] = $selectedProfile;
        $route = $profileRoutes[$selectedProfile] ?? 'index.php';
        header("Location: $route");
        exit();
    } else {
        $error = "Perfil no válido";
    }
}

// Obtener nombre de usuario de forma segura
$username = 'Usuario'; // Valor por defecto

if (isset($_SESSION['user']['username'])) {
    if (is_array($_SESSION['user']['username'])) {
        // Si es un array, convertimos a string (tomamos el primer elemento)
        $username = (string)reset($_SESSION['user']['username']);
    } else {
        $username = (string)$_SESSION['user']['username'];
    }
}

// Función auxiliar para sanitizar valores
function sanitizeValue($value) {
    if (is_array($value)) {
        return htmlspecialchars(implode(', ', $value));
    }
    return htmlspecialchars((string)$value);
}
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
            padding-top: 20px;
            padding-bottom: 50px;
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
        .btn-back-container {
            margin-bottom: 10px;
            margin-left: 15px;
        }
        .card-header h4 {
            margin: 0;
        }
        @media (max-width: 768px) {
            .btn-back-container {
                margin-left: 0;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Contenedor fluid para que ocupe todo el ancho disponible -->
    <div class="container-fluid px-4">
        <!-- Botón de volver atrás dentro del contenedor principal y más cerca del contenido -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="btn-back-container">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver Atrás
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center py-4">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0 text-center">Selecciona tu perfil</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-4">Hola, <strong><?php echo sanitizeValue($username); ?></strong>. Tienes acceso a los siguientes perfiles:</p>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo sanitizeValue($error); ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="profile_selector.php">
                            <div class="row">
                                <?php 
                                if (!empty($availableProfiles)):
                                    foreach ($availableProfiles as $profile): 
                                        // Aseguramos que $profile sea string y saneamos
                                        $profileStr = is_array($profile) ? (string)reset($profile) : (string)$profile;
                                        $profileSanitized = sanitizeValue($profileStr);
                                ?>
                                    <div class="col-md-6 mb-3">
                                        <button type="submit" name="profile" value="<?php echo $profileSanitized; ?>" class="btn btn-outline-primary w-100 py-4">
                                            <div class="profile-icon">
                                                <?php 
                                                    switch($profileStr) {
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
                                                        case 'usuario':
                                                            echo '<i class="fas fa-user-tie"></i>';
                                                            break;
                                                        default:
                                                            echo '<i class="fas fa-user"></i>';
                                                    }
                                                ?>
                                            </div>
                                            <h5 class="mb-1">
                                                <?php 
                                                    switch($profileStr) {
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
                                                        case 'usuario':
                                                            echo 'Director de Carrera';
                                                            break;
                                                        default:
                                                            echo ucfirst($profileStr);
                                                    }
                                                ?>
                                            </h5>
                                            <small class="text-muted">Haz clic para entrar como <?php echo $profileSanitized; ?></small>
                                        </button>
                                    </div>
                                <?php 
                                    endforeach;
                                else:
                                ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning text-center">
                                            No tienes perfiles disponibles. Contacta al administrador.
                                        </div>
                                    </div>
                                <?php endif; ?>
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