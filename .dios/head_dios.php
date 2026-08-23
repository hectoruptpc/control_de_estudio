<?php
// .dios/head_dios.php - Head específico para el Panel DIOS
// Este head NO verifica permisos de admin porque es un sistema aparte

// Base URL del sistema
$base_url = '/control_de_estudio';
$dios_url = $base_url . '/.dios';
?>
<!DOCTYPE html>
<html lang="es-Es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Panel de Control DIOS - UPTPC">
<meta name="author" content="Administrador del Sistema">
<meta name="robots" content="noindex, nofollow">
<title><?php echo $titulopag; ?></title>

<!-- Bootstrap 4.6 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* ESTILOS GENERALES */
    body {
        padding-top: 20px;
        background: linear-gradient(135deg, #0a0e27 0%, #1a1a2e 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }
    
    /* CONTENEDOR PRINCIPAL */
    .dios-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* TARJETAS DIOS */
    .card-dios {
        background: rgba(10,14,39,0.95);
        border-radius: 12px;
        border: 1px solid rgba(255,215,0,0.3);
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }
    
    .card-dios:hover {
        border-color: #ffd700;
        box-shadow: 0 0 15px rgba(255,215,0,0.2);
    }
    
    .card-header-dios {
        background: linear-gradient(135deg, #1a1a2e, #16213e);
        border-bottom: 1px solid #ffd700;
        padding: 15px 20px;
        font-weight: bold;
        color: #ffd700;
    }
    
    /* BOTONES DIOS */
    .btn-dios {
        background: linear-gradient(135deg, #ffd700, #ff8c00);
        color: #000;
        font-weight: bold;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-dios:hover {
        background: linear-gradient(135deg, #ff8c00, #ff6600);
        color: #fff;
        transform: translateY(-2px);
    }
    
    .btn-dios-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
        border: none;
    }
    
    .btn-dios-danger:hover {
        background: linear-gradient(135deg, #c82333, #a71d2a);
        transform: translateY(-2px);
    }
    
    .btn-dios-success {
        background: linear-gradient(135deg, #28a745, #218838);
        color: #fff;
        border: none;
    }
    
    .btn-dios-success:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
    }
    
    /* TABLAS */
    .table-dios {
        background: rgba(10,14,39,0.9);
        color: #fff;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table-dios thead th {
        background: #1a1a2e;
        color: #ffd700;
        border-bottom: 2px solid #ffd700;
        font-weight: bold;
    }
    
    .table-dios tbody tr:hover {
        background: rgba(255,215,0,0.1);
    }
    
    /* BADGES */
    .badge-dios-active {
        background: #28a745;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-dios-blocked {
        background: #dc3545;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-dios-warning {
        background: #ffc107;
        color: #000;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    /* NÚMEROS ESTADÍSTICOS */
    .stat-number {
        font-size: 32px;
        font-weight: bold;
        background: linear-gradient(135deg, #ffd700, #ff8c00);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: inline-block;
    }
    
    /* FOOTER */
    .dios-footer {
        background: rgba(10,14,39,0.95);
        border-top: 1px solid #ffd700;
        padding: 20px;
        text-align: center;
        margin-top: 40px;
        color: #888;
        font-size: 12px;
    }
    
    /* SCROLLBAR PERSONALIZADA */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #1a1a2e;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #ffd700;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #ff8c00;
    }
    
    /* ALERTAS */
    .alert-dios {
        background: rgba(255,215,0,0.1);
        border: 1px solid #ffd700;
        color: #ffd700;
        border-radius: 8px;
    }
    
    /* INPUTS */
    .form-control-dios {
        background: #1a1f3a;
        border: 1px solid #2a2f4a;
        color: #fff;
        border-radius: 6px;
        padding: 8px 12px;
    }
    
    .form-control-dios:focus {
        border-color: #ffd700;
        box-shadow: 0 0 5px rgba(255,215,0,0.5);
        background: #1a1f3a;
        color: #fff;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 24px;
        }
        .card-header-dios {
            font-size: 14px;
        }
    }
</style>
</head>
<body>
<div class="dios-container"><?php
// .dios/head_dios.php - Head específico para el Panel DIOS (VERSIÓN CLARA)

// Base URL del sistema
$base_url = '/control_de_estudio';
$dios_url = $base_url . '/.dios';
?>
<!DOCTYPE html>
<html lang="es-Es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Panel de Control DIOS - UPTPC">
<meta name="author" content="Administrador del Sistema">
<meta name="robots" content="noindex, nofollow">
<title><?php echo $titulopag; ?></title>

<!-- Bootstrap 4.6 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    /* ESTILOS GENERALES - VERSIÓN CLARA */
    body {
        background: #f0f2f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding-bottom: 20px;
    }
    
    /* CONTENEDOR PRINCIPAL */
    .dios-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    /* TARJETAS DIOS - ESTILO CLARO */
    .card-dios {
        background: #ffffff;
        border-radius: 12px;
        border: none;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .card-dios:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .card-header-dios {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border-bottom: 3px solid #ffc107;
        padding: 15px 20px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 16px;
    }
    
    /* BOTONES DIOS */
    .btn-dios {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #2c3e50;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        padding: 8px 20px;
        border-radius: 8px;
    }
    
    .btn-dios:hover {
        background: linear-gradient(135deg, #ffb300, #ffa000);
        color: #fff;
        transform: translateY(-2px);
    }
    
    .btn-dios-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: #fff;
        border: none;
    }
    
    .btn-dios-danger:hover {
        background: linear-gradient(135deg, #c82333, #a71d2a);
        transform: translateY(-2px);
    }
    
    .btn-dios-success {
        background: linear-gradient(135deg, #28a745, #218838);
        color: #fff;
        border: none;
    }
    
    .btn-dios-success:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        transform: translateY(-2px);
    }
    
    .btn-dios-warning {
        background: linear-gradient(135deg, #ffc107, #ffb300);
        color: #2c3e50;
        border: none;
    }
    
    .btn-dios-warning:hover {
        background: linear-gradient(135deg, #ffb300, #ffa000);
        color: #fff;
    }
    
    /* TABLAS - ESTILO CLARO */
    .table-dios {
        background: #ffffff;
        color: #2c3e50;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .table-dios thead th {
        background: #f8f9fa;
        color: #2c3e50;
        border-bottom: 2px solid #ffc107;
        font-weight: 600;
    }
    
    .table-dios tbody tr:hover {
        background: #fff8e1;
    }
    
    /* BADGES */
    .badge-dios-active {
        background: #d4edda;
        color: #155724;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-dios-blocked {
        background: #f8d7da;
        color: #721c24;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-dios-warning {
        background: #fff3cd;
        color: #856404;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* NÚMEROS ESTADÍSTICOS */
    .stat-number {
        font-size: 32px;
        font-weight: bold;
        color: #ffc107;
        display: inline-block;
    }
    
    .stat-label {
        font-size: 14px;
        color: #6c757d;
        margin-top: 5px;
    }
    
    /* CABECERA DIOS */
    .dios-header {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        text-align: center;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .dios-header h1 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .dios-header .crown-icon {
        font-size: 45px;
        color: #ffc107;
        margin-bottom: 10px;
    }
    
    /* FOOTER */
    .dios-footer {
        background: #ffffff;
        border-top: 1px solid #e9ecef;
        padding: 20px;
        text-align: center;
        margin-top: 40px;
        color: #6c757d;
        font-size: 12px;
        border-radius: 10px;
    }
    
    /* ALERTAS */
    .alert-dios {
        background: #fff8e1;
        border-left: 4px solid #ffc107;
        color: #856404;
        border-radius: 8px;
    }
    
    /* INPUTS */
    .form-control-dios {
        background: #ffffff;
        border: 1px solid #dee2e6;
        color: #2c3e50;
        border-radius: 8px;
        padding: 8px 12px;
    }
    
    .form-control-dios:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255,193,7,0.25);
    }
    
    /* STAT CARDS */
    .stat-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card .icon {
        font-size: 35px;
        color: #ffc107;
        margin-bottom: 10px;
    }
    
    .stat-card .value {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .stat-card .label {
        font-size: 14px;
        color: #6c757d;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .stat-number {
            font-size: 24px;
        }
        .card-header-dios {
            font-size: 14px;
        }
        .dios-container {
            padding: 10px;
        }
    }
</style>
</head>
<body>
<div class="dios-container">