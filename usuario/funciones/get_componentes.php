<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$titulopag = "Consulta Componentes";
include('../../funciones/functions.php');

function get_componentes(){
global $db, $id_usua, $id_componente, $descripcion, $fecha;

// Verifica la conexión
if ($db->connect_error) {
    die("Conexión fallida: " . $db->connect_error);
}

// Consulta SQL para obtener los componentes
$sql = "SELECT codigo, nombre FROM componentes ORDER BY `componentes`.`nombre` ASC ";
$result = $db->query($sql);

// Crea un array para almacenar los componentes
$componentes = array();

// Recorre los resultados y agrega los componentes al array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $componentes[] = $row;
    }
}

// Cierra la conexión
$db->close();

// Imprime los componentes en formato JSON
header('Content-type: application/json');
echo json_encode($componentes);

}
get_componentes();
?>
