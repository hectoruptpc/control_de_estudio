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
            background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
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
        .pagos-card {
            border-bottom: 4px solid #28a745;
        }
        .pagos-card .card-icon {
            color: #28a745;
        }
        .soporte-card {
            border-bottom: 4px solid #ffc107;
        }
        .soporte-card .card-icon {
            color: #ffc107;
        }
        .mensajeria-card {
            border-bottom: 4px solid #17a2b8;
        }
        .mensajeria-card .card-icon {
            color: #17a2b8;
        }
        .auditoria-card {
            border-bottom: 4px solid #6f42c1;
        }
        .auditoria-card .card-icon {
            color: #6f42c1;
        }
        .btn-access {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-pagos {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-pagos:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-soporte {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }
        .btn-soporte:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-mensajeria {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-mensajeria:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .btn-auditoria {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-auditoria:hover {
            background-color: #5a359c;
            border-color: #523091;
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
            <h1 class="display-4 font-weight-bold"><i class="fas fa-tachometer-alt mr-3"></i>Panel de Administración</h1>
            <p class="lead mb-0">Bienvenido, <?php echo $_SESSION['user']['nombre'] ?? 'Administrador'; ?></p>
        </div>

        <!-- Tarjetas de acceso -->
        <div class="row mb-5">
            <!-- Tarjeta de Pagos -->
            <div class="col-md-3 mb-4">
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

            <!-- Tarjeta de Soporte -->
            <div class="col-md-3 mb-4">
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
            <div class="col-md-3 mb-4">
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

            <!-- Tarjeta de Auditoría (NUEVA) -->
            <div class="col-md-3 mb-4">
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