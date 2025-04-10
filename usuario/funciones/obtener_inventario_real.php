<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

// Supongo que  $id_usua se está pasando correctamente a este archivo

$query = "SELECT
    c.nombre AS Componente,
    c.tipo AS tipo_componente,
    SUM(CASE WHEN ic.descripcion = '1' THEN ic.cantidad ELSE 0 END) - 
    SUM(CASE WHEN ic.descripcion = '0' THEN ic.cantidad ELSE 0 END) AS CantidadTotal
    FROM componentes c
    LEFT JOIN inventario_componente ic ON c.codigo = ic.id_componente AND ic.id_usuario = '$id_usua'
    GROUP BY c.codigo, c.nombre, c.tipo
    HAVING CantidadTotal > 0;";

$result = $db->query($query);
$arreglo = ['data' => []];
$id_tabla = 1;

while ($row = $result->fetch_assoc()) {

    $cantidad_original = $row['CantidadTotal'];
    $tipo_componente = $row['tipo_componente'];
    $cantidad_final = $cantidad_original; 
    $unidad = ""; 

    if ($tipo_componente == 'liq') {
        if ($cantidad_original >= 1000) {
            $cantidad_final = $cantidad_original / 1000; 
            $unidad = ($cantidad_final > 1) ? "Litros" : "Litro"; 
        } else {
            $unidad =  "mililitro" . ($cantidad_original > 1 ? "s" : "");
        }
    } else if ($tipo_componente == 'sol') {
        if ($cantidad_original >= 1000) {
            $cantidad_final = $cantidad_original / 1000; 
            $unidad = ($cantidad_final > 1) ? "Kilos" : "Kilo";
        } else {
            $unidad =  "gramo" . ($cantidad_original > 1 ? "s" : ""); 
        }
    }

    $cantidad_final = round($cantidad_final, 2); 

    // Crea el array $data solo con las claves necesarias:
    $data = [
        "id_tabla_real" => $id_tabla,
        "Componente_real" => $row['Componente'],
        "Cantidad_real" => $cantidad_final . " " . $unidad 
    ];

    // Agrega $data al array "data" dentro de $arreglo
    $arreglo["data"][] = $data;

    $id_tabla++; 
}

// Envía la respuesta como JSON 
header('Content-Type: application/json');
echo json_encode($arreglo);

?>