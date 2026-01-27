<?php
// admin/api/obtener_municipios.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir las funciones que contienen $db
// IMPORTANTE: Ajusta la ruta según tu estructura
require_once '../../funciones/functions.php';

$response = ['success' => false, 'municipios' => [], 'error' => ''];

try {
    // Verificar que $db esté disponible
    if (!isset($db) || !$db) {
        throw new Exception('Base de datos no disponible');
    }
    
    // Obtener ID del estado
    $estado_id = null;
    
    // Primero intentar con POST
    if (isset($_POST['estado_id']) && is_numeric($_POST['estado_id'])) {
        $estado_id = intval($_POST['estado_id']);
    } 
    // Si no hay POST, intentar con GET
    elseif (isset($_GET['estado_id']) && is_numeric($_GET['estado_id'])) {
        $estado_id = intval($_GET['estado_id']);
    }
    
    if (!$estado_id) {
        throw new Exception('ID de estado no proporcionado o inválido');
    }
    
    // Usar la función que ya tienes en functions.php
    $municipios_data = obtenerMunicipiosPorEstado($estado_id);
    
    // Formatear la respuesta
    $municipios = [];
    foreach ($municipios_data as $municipio) {
        $municipios[] = [
            'id' => $municipio['id_municipio'],
            'nombre' => $municipio['municipio']
        ];
    }
    
    $response['success'] = true;
    $response['municipios'] = $municipios;
    
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log('Error en obtener_municipios.php: ' . $e->getMessage());
}

echo json_encode($response);
?>