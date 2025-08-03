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
</head>

<body>
    <!-- Contenido principal -->
    <div class="main-content" id="mainContent">
        <!-- Encabezado -->
        <div class="bg-primary text-white py-4 text-center mb-4">
            <h2>Panel del Estudiante</h2>
            <p class="lead mb-0">Bienvenido, <?php echo $_SESSION['user']['nombre_completo'] ?? $_SESSION['user']['username']; ?></p>
        </div>

        <!-- Área de contenido -->
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Información importante</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Este es tu panel principal como estudiante. Desde aquí puedes acceder a todas tus herramientas académicas.</p>
                    
                    <!-- Espacio para notificaciones importantes -->
                    <?php if (isset($_SESSION['success'])) : ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php 
                                echo $_SESSION['success']; 
                                unset($_SESSION['success']);
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif ?>
                    
                    <!-- Contenido adicional para estudiantes -->
                    <div class="alert alert-info mt-3">
                        <h5 class="alert-heading"><i class="fas fa-info-circle mr-2"></i>Próximas actividades</h5>
                        <hr>
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
                mainContent.classList.toggle('ml-0', !isOpen);
            } else {
                mainContent.classList.toggle('ml-5', isCollapsed);
                mainContent.classList.remove('ml-0');
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