<?php
// ARCHIVO: exportar_auditoria.php
require_once('../funciones/functions.php');

if (!isLoggedIn() || !isAdmin()) {
    header('location: ../login.php');
    exit();
}

// Procesar filtros
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;
$usuario_id = $_GET['usuario_id'] ?? null;
$accion = $_GET['accion'] ?? null;
$modulo = $_GET['modulo'] ?? null;
$limite = $_GET['limite'] ?? 1000;

// Obtener registros de auditoría
$registros = obtenerRegistrosAuditoria($limite, $fecha_inicio, $fecha_fin, $usuario_id, $accion, $modulo);

// Configurar headers para descarga CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=auditoria_' . date('Y-m-d_H-i') . '.csv');

// Crear output
$output = fopen('php://output', 'w');

// Escribir headers CSV
fputcsv($output, [
    'Fecha y Hora',
    'Usuario',
    'Cédula',
    'Acción',
    'Módulo',
    'Tabla Afectada',
    'ID Registro',
    'Descripción',
    'IP Origen',
    'Valores Antiguos',
    'Valores Nuevos',
    'User Agent'
], ';');

// Escribir datos
foreach ($registros as $registro) {
    fputcsv($output, [
        $registro['fecha_hora'],
        $registro['usuario_nombre'],
        $registro['usuario_cedula'],
        $registro['accion'],
        $registro['modulo_sistema'],
        $registro['tabla_afectada'],
        $registro['registro_id'],
        $registro['descripcion'],
        $registro['ip_origen'],
        $registro['valores_antiguos'] ? json_encode($registro['valores_antiguos']) : '',
        $registro['valores_nuevos'] ? json_encode($registro['valores_nuevos']) : '',
        $registro['user_agent']
    ], ';');
}

fclose($output);
exit;