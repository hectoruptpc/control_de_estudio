<?php
require_once __DIR__ . '/funciones/functions.php';

// Ajusta estos IDs según una carrera/trayecto/periodo existentes en tu BD para probar
$id_carrera = isset($argv[1]) ? (int)$argv[1] : 1;
$id_trayecto = isset($argv[2]) ? (int)$argv[2] : 0;
$id_periodo = isset($argv[3]) ? (int)$argv[3] : 1;

$secciones = obtenerSeccionesDisponiblesParaRezagados($id_carrera, $id_trayecto, $id_periodo);

if (empty($secciones)) {
    echo "No se encontraron secciones\n";
} else {
    foreach ($secciones as $s) {
        $disponibles = $s['capacidad_maxima'] - $s['inscritos'];
        echo "ID: {$s['id_seccion']} | {$s['codigo_seccion']} ({$disponibles} cupos)\n";
    }
}
