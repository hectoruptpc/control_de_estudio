<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema de Gestión - Panel de Administración";
include('../funciones/functions.php');
//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('admin');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include("includes/head.php"); ?>
    
</head>

<body>
    <div class="container py-5">
        <!-- Encabezado -->
        <div class="dashboard-header p-4 mb-5 text-center">
            <h1 class="display-4 font-weight-bold"><i class="fas fa-tachometer-alt mr-3"></i>Panel de Administración</h1>
            <p class="lead mb-0">Bienvenido, <?php echo $_SESSION['user']['nombre'] ?? 'Administrador'; ?></p>
        </div>

        <!-- Tarjetas de acceso -->
        <div class="cards-container mb-5">
            <?php if (tienePermiso('pagos')): ?>
            <!-- Tarjeta de Pagos -->
            <div class="card-wrapper">
                <div class="card feature-card pagos-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Pagos</h3>
                        <p class="card-text text-muted">Gestionar sistema de pagos y transacciones</p>
                        <a href="registro_pagos.php" class="btn btn-access btn-pagos mt-3">Acceder</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tarjeta de Soporte -->
            <div class="card-wrapper">
                <div class="card feature-card soporte-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Soporte</h3>
                        <p class="card-text text-muted">Información de ayuda y soporte técnico</p>
                        <a href="soporte.php" class="btn btn-access btn-soporte mt-3">Acceder</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Mensajería -->
            <div class="card-wrapper">
                <div class="card feature-card mensajeria-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Mensajería</h3>
                        <p class="card-text text-muted">Sistema de mensajes y notificaciones</p>
                        <a href="mensajeria.php" class="btn btn-access btn-mensajeria mt-3">Acceder</a>
                    </div>
                </div>
            </div>

            <?php if (tienePermiso('auditoria')): ?>
            <!-- Tarjeta de Auditoría -->
            <div class="card-wrapper">
                <div class="card feature-card auditoria-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="card-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 class="card-title h4 font-weight-bold">Auditoría</h3>
                        <p class="card-text text-muted">Registro de actividades del sistema</p>
                        <a href="auditoria.php" class="btn btn-access btn-auditoria mt-3">Acceder</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Mensaje de bienvenida -->
        <div class="welcome-message p-4 text-center">
            <h4 class="font-weight-bold">Bienvenido al Sistema de Gestión</h4>
            <p class="text-muted mb-0">Selecciona una de las opciones anteriores para comenzar</p>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script>
    // Notificación de bienvenida
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar si Push está disponible antes de usarlo
        if (typeof Push !== 'undefined') {
            Push.create('Panel de Administración', {
                body: 'Bienvenido al sistema de gestión',
                icon: '../images/logo_mini.png',
                timeout: 4000
            });
        }
    });
    </script>
</body>
</html>