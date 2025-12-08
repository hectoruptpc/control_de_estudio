<?php
// admin/api/obtener_parroquias.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Incluir las funciones que contienen $db
// IMPORTANTE: Ajusta la ruta según tu estructura
require_once '../../funciones/functions.php';

$response = ['success' => false, 'parroquias' => [], 'error' => ''];

try {
    // Verificar que $db esté disponible
    if (!isset($db) || !$db) {
        throw new Exception('Base de datos no disponible');
    }
    
    // Obtener ID del municipio
    $municipio_id = null;
    
    // Primero intentar con POST
    if (isset($_POST['municipio_id']) && is_numeric($_POST['municipio_id'])) {
        $municipio_id = intval($_POST['municipio_id']);
    } 
    // Si no hay POST, intentar con GET
    elseif (isset($_GET['municipio_id']) && is_numeric($_GET['municipio_id'])) {
        $municipio_id = intval($_GET['municipio_id']);
    }
    
    if (!$municipio_id) {
        throw new Exception('ID de municipio no proporcionado o inválido');
    }
    
    // Usar la función que ya tienes en functions.php
    $parroquias_data = obtenerParroquiasPorMunicipio($municipio_id);
    
    // Formatear la respuesta
    $parroquias = [];
    foreach ($parroquias_data as $parroquia) {
        $parroquias[] = [
            'id' => $parroquia['id_parroquia'],
            'nombre' => $parroquia['parroquia']
        ];
    }
    
    $response['success'] = true;
    $response['parroquias'] = $parroquias;
    
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    error_log('Error en obtener_parroquias.php: ' . $e->getMessage());
}

echo json_encode($response);
?>