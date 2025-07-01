<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel del Docente";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/head.php"); ?>
    <style>
        .docente-header {
            background-color: #006633;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .menu-docente {
            list-style: none;
            padding: 0;
        }
        .menu-docente li {
            margin-bottom: 10px;
        }
        .menu-docente a {
            display: block;
            padding: 10px 15px;
            background: #f8f9fa;
            border-left: 4px solid #006633;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        .menu-docente a:hover {
            background: #e9ecef;
            border-left: 4px solid #004d26;
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <div class="docente-header text-center">
        <h2>Panel del Docente</h2>
        <p>Bienvenido, <?php echo $_SESSION['user']['nombre_completo'] ?? $_SESSION['user']['username']; ?></p>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <div class="row">
            <?php include("./includes/sidebar_docente.php"); ?>

            <!-- Área de contenido -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4>Información importante</h4>
                    </div>
                    <div class="card-body">
                        <p>Este es tu panel principal como docente. Desde aquí puedes acceder a todas las herramientas.</p>
                        
                        <!-- Espacio para notificaciones importantes -->
                        <?php if (isset($_SESSION['success'])) : ?>
                            <div class="alert alert-success">
                                <?php 
                                    echo $_SESSION['success']; 
                                    unset($_SESSION['success']);
                                ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <!-- Script para notificación de bienvenida -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Push.create('Bienvenido al sistema docente', {
            body: 'Tienes acceso a todas las herramientas para gestionar tus cursos',
            icon: '../images/docente_icon.png',
            timeout: 5000
        });
    });
    </script>
</body>
</html>