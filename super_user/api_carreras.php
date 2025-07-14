<?php
// api_carreras.php
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar visualización de errores en output
header('Content-Type: application/json');

try {
    require_once('../funciones/functions.php'); // Ajusta la ruta según tu estructura
    
    $carreras = obtenerCarreras();
    
    if (isset($carreras['error'])) {
        throw new Exception($carreras['error']);
    }
    
    // Validar estructura de datos
    if (!is_array($carreras)) {
        throw new Exception('Los datos de carreras no son un array');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $carreras
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}