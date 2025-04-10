<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Clientes";
include('../../funciones/functions.php');

function inventario_materia_prima() {
    global $db, $id_usua;

    $arreglo = [];

    $query = "SELECT ic.id,
ic.id_control AS ID_Control,
ic.id_componente AS ID_Componente ,
               c.nombre AS Componente,
               ic.cantidad AS cantidad_original,  -- Almacenamos la cantidad original
               CASE 
                   WHEN ic.cantidad >= 1000 THEN ic.cantidad / 1000 
                   ELSE ic.cantidad 
               END AS cantidad_final,  -- Cantidad final para mostrar
               CASE WHEN ic.descripcion = 0 THEN 'USADO' ELSE 'INGRESO' END AS Descripcion,
               ic.fecha AS Fecha,
               c.tipo AS tipo_componente
        FROM inventario_componente ic
        JOIN componentes c ON ic.id_componente = c.codigo
        WHERE id_usuario = '$id_usua';";

    $result = $db->query($query);

    while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
        // Asignamos los datos al arreglo
        $data['id'] = $data['id'];
        $cantidad_final = $data['cantidad_final'];
        $tipo_componente = $data['tipo_componente'];
        $cantidad_original = $data['cantidad_original'];

        // Aplicamos la condición para el tipo
        if ($tipo_componente == 'liq') {
            if ($cantidad_original >= 1000) {
                if ($cantidad_final > 1) { // Para plural
                    $unidad = "Litros"; 
                } else { // Para singular
                    $unidad = "Litro";
                }
            } else {
                if ($cantidad_final < 1000) { // Evalúa la cantidad final
                    $unidad = "mililitro" . ($cantidad_original > 1 ? "s" : ""); // Asigna mililitro o mililitros
                }
            }
        } else if ($tipo_componente == 'sol') {
            if ($cantidad_original >= 1000) {
                if ($cantidad_final > 1) { // Para plural
                    $unidad = "Kilos";
                } else { // Para singular
                    $unidad = "Kilo";
                }
            } else {
                if ($cantidad_final < 1000) { // Evalúa la cantidad final
                    $unidad = "gramo" . ($cantidad_original > 1 ? "s" : ""); // Asigna gramo o gramos
                }
            }
        }

        $cantidad_final = round($cantidad_final, 2); // Redondea a dos decimales

        // Asignamos la cantidad y la unidad al arreglo
        $data['cantidad'] = $cantidad_final . " " . $unidad;  

        $data['descripcion'] = $data['Descripcion'];
        $data['fecha'] = $data['Fecha'];
        $data['nombre_componente'] = $data['Componente'];

        $arreglo["data"][] = $data;
    }

    // Recorremos los datos y reemplazamos los null con una cadena vacía
    foreach ($arreglo as $clave => $valor) {
        foreach ($valor as $clave2 => $valor2) {
            if ($valor2 === null) {
                $arreglo[$clave][$clave2] = "";
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($arreglo);
}

inventario_materia_prima();