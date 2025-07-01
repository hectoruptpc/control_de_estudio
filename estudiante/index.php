<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel del Estudiante";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/head.php"); ?>
    <style>
        .estudiante-header {
            background-color: #003366;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        /* Ajustes para el contenido principal con la barra lateral */
        .main-content {
    margin-left: 250px;
    transition: margin-left 0.3s ease;
    padding: 20px;
    min-height: calc(100vh - [altura-del-header]);
    z-index: 1; /* Menor que el sidebar (1000) y el botón (1002) */
}
        
.main-content.collapsed {
    margin-left: 70px;
}
        
        .main-content.full-width {
            margin-left: 0;
        }
        
        @media (max-width: 768px) {
    .main-content {
        margin-left: 0 !important;
    }
}
            
            .main-content.collapsed {
                margin-left: 0;
            }
        
    </style>
</head>

<body>
    <!-- Incluir la barra lateral -->
    <?php include("includes/sidebar.php"); ?>

    <!-- Contenido principal -->
    <div class="main-content" id="mainContent">
        <!-- Encabezado -->
        <div class="estudiante-header text-center">
            <h2>Panel del Estudiante</h2>
            <p>Bienvenido, <?php echo $_SESSION['user']['nombre_completo'] ?? $_SESSION['user']['username']; ?></p>
        </div>

        <!-- Área de contenido -->
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h4>Información importante</h4>
                </div>
                <div class="card-body">
                    <p>Este es tu panel principal como estudiante. Desde aquí puedes acceder a todas tus herramientas académicas.</p>
                    
                    <!-- Espacio para notificaciones importantes -->
                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success">
                            <?php 
                                echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif ?>
                    
                    <!-- Contenido adicional para estudiantes -->
                    <div class="alert alert-info mt-3">
                        <h5><i class="fas fa-info-circle"></i> Próximas actividades</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Tarea de Matemáticas - Fecha límite: 15/06</li>
                            <li class="list-group-item">Examen de Ciencias - 20/06</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <!-- Script para sincronizar el contenido principal con la barra lateral -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        
        function updateMainContent() {
            const isMobile = window.innerWidth <= 768;
            const isCollapsed = sidebar.classList.contains('collapsed');
            const isOpen = sidebar.classList.contains('open');
            
            if (isMobile) {
                mainContent.classList.remove('collapsed');
                mainContent.classList.toggle('full-width', !isOpen);
            } else {
                mainContent.classList.toggle('collapsed', isCollapsed);
                mainContent.classList.remove('full-width');
            }
        }
        
        // Observar cambios en la barra lateral
        const observer = new MutationObserver(updateMainContent);
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        
        // Actualizar al cambiar tamaño de ventana
        window.addEventListener('resize', updateMainContent);
        
        // Estado inicial
        updateMainContent();
        
        // Notificación de bienvenida
        Push.create('Bienvenido al sistema estudiantil', {
            body: 'Aquí encontrarás todo lo necesario para tu aprendizaje',
            icon: '../images/estudiante_icon.png',
            timeout: 5000
        });
    });
    </script>
</body>
</html>