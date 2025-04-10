<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Clientes";
include('../../funciones/functions.php');

function listar_materia_prima(){
global $db, $id_usua, $id_componente, $descripcion, $fecha;

$arreglo = [];

$query = "SELECT ic.id, ic.id_componente, 
ic.cantidad AS cantidad_original,  -- Almacenamos la cantidad original
CASE 
    WHEN ic.cantidad >= 1000 THEN ic.cantidad / 1000  -- Dividimos si es mayor o igual a 1000
    ELSE ic.cantidad 
END AS cantidad_final, -- Cantidad final para mostrar
CASE 
    WHEN ic.descripcion = 0 THEN 'USADO' 
    ELSE 'INGRESO'
END AS descripcion, 
ic.fecha, c.nombre AS nombre_componente,
c.tipo AS tipo_componente  -- Agregamos el tipo del componente
FROM inventario_componente ic 
JOIN componentes c ON ic.id_componente = c.codigo 
WHERE ic.id_usuario = ?";


$stmt = $db->prepare($query);
$stmt->bind_param("s", $id_usua);
$stmt->execute();
$result = $stmt->get_result();

while($data = $result->fetch_array(MYSQLI_ASSOC))
{
$data['id']= $data['id'];
$cantidad_final = $data['cantidad_final'];
$id_componente = $data['nombre_componente'];
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
$data['cantidad_final'] = $cantidad_final . " " . $unidad;  

$descripcion = $data['descripcion'];
$fecha = $data['fecha'];

$arreglo ["data"][] = $data;
}

// Recorremos los datos y reemplazamos los null con una cadena vacía
foreach ($arreglo as $clave => $valor) {
foreach ($valor as $clave2 => $valor2) {
if ($valor2 === null) {
$arreglo[$clave][$clave2] = "";
}
}
}

echo json_encode($arreglo);

$stmt->close();
}
listar_materia_prima();
?>
