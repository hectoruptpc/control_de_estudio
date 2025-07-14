<?php
include('funciones/functions.php');

if (!isLoggedIn()) {
    header('location: login.php');
    exit;
}

$available_profiles = $_SESSION['user']['available_profiles'] ?? [];

// Si por algún motivo no hay perfiles (no debería pasar)
if (empty($available_profiles)) {
    session_destroy();
    header('location: login.php');
    exit;
}

// Si solo tiene un perfil, redirigir directamente
if (count($available_profiles) == 1) {
    $_SESSION['current_profile'] = $available_profiles[0];
    $where = $_SESSION['here'] ?? $available_profiles[0] . '/home.php';
    header("Location: $where");
    exit;
}

// Procesar selección de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['profile'])) {
    if (in_array($_POST['profile'], $available_profiles)) {
        $_SESSION['current_profile'] = $_POST['profile'];
        $where = $_SESSION['here'] ?? $_POST['profile'] . '/home.php';
        header("Location: $where");
        exit;
    }
}

// Obtener descripciones de perfiles desde tu tabla user_types
$profile_descriptions = [];
$query = "SELECT user_type, descripcion FROM user_types WHERE user_type IN ('" . implode("','", $available_profiles) . "')";
$result = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $profile_descriptions[$row['user_type']] = $row['descripcion'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seleccionar Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .profile-card {
            transition: all 0.3s;
            cursor: pointer;
        }
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .admin-card { border-left: 4px solid #4CAF50; }
        .docente-card { border-left: 4px solid #2196F3; }
        .estudiante-card { border-left: 4px solid #003366; }
        .user-card { border-left: 4px solid #607D8B; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center mb-4">
                    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h2>
                    <p class="lead">Selecciona el perfil con el que deseas ingresar</p>
                </div>
                
                <form method="post" class="row">
                    <?php foreach ($available_profiles as $profile): ?>
                    <div class="col-md-6 mb-4">
                        <button type="submit" name="profile" value="<?php echo $profile; ?>" 
                                class="btn btn-light p-4 w-100 text-left profile-card <?php echo $profile; ?>-card">
                            <h4><?php echo ucfirst($profile); ?></h4>
                            <p class="text-muted mb-0">
                                <?php echo $profile_descriptions[$profile] ?? 'Perfil del sistema'; ?>
                            </p>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>