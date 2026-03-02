<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema de Gestión - Panel de Director de Carrera";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isUser()) {
    $_SESSION['msg'] = "Debes iniciar sesión como director de carrera para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/head.php"); ?>
    <!-- Bootstrap 4.6 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(120deg, #fd7e14 0%, #e86100 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .feature-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .card-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .asignacion-card {
            border-bottom: 4px solid #fd7e14;
        }
        .voceros-card {
            border-bottom: 4px solid #28a745;
        }
        .asignacion-card .card-icon {
            color: #fd7e14;
        }
        .voceros-card .card-icon {
            color: #28a745;
        }
        .btn-access {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-asignacion {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }
        .btn-asignacion:hover {
            background-color: #e86100;
            border-color: #dc5600;
        }
        .btn-voceros {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-voceros:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .welcome-message {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <!-- Encabezado -->
        <div class="dashboard-header p-4 mb-5 text-center">
            <h1 class="display-4 font-weight-bold"><i class="fas fa-chalkboard-teacher mr-3"></i>Panel de Director de Carrera</h1>
            <p class="lead mb-0">Bienvenido, <?php echo $_SESSION['user']['nombre'] ?? 'Director'; ?></p>
        </div>

        <!-- Tarjetas de acceso -->
        <div class="row mb-5 justify-content-center">
            <!-- Tarjeta de Asignación de Docentes -->
            <div class="col-md-5 mb-4">
                <div class="card feature-card asignacion-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Asignar Docente al Programa</h3>
                        <p class="card-text text-muted">Gestione la asignación de profesores a programas académicos</p>
                        <a href="asignacion_cursos.php" class="btn btn-access btn-asignacion mt-3">Acceder</a>
                    </div>
                </div>
            </div>
            
            <!-- Tarjeta de Asignación de Voceros -->
            <div class="col-md-5 mb-4">
                <div class="card feature-card voceros-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Asignación de Voceros</h3>
                        <p class="card-text text-muted">Gestione la asignación de voceros estudiantiles por programa</p>
                        <a href="asignacion_voceros.php" class="btn btn-access btn-voceros mt-3">Acceder</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje de bienvenida -->
        <div class="welcome-message p-4 text-center">
            <h4 class="font-weight-bold">Bienvenido al Sistema de Gestión</h4>
            <p class="text-muted mb-0">Utilice las opciones disponibles para gestionar las asignaciones docentes y de voceros</p>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    
</body>
</html>