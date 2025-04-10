<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '../../../funciones/conexion.php';

if(isset($_POST['updateid'])){
    $contenido_id = $_POST['updateid'];

    $sql = "SELECT * FROM contenido WHERE id=$contenido_id";
    $result = mysqli_query($db, $sql);
    $response = array();
    while($row = mysqli_fetch_assoc($result)){
        $response = $row;
    }
    echo json_encode($response);
} else if(isset($_POST['hiddendata'])){
    $uniqueid = $_POST['hiddendata'];
    $seccion = $_POST['updateseccion'];
    $contenido = $_POST['updatecontenido'];

    $sql = "UPDATE contenido SET seccion='$seccion', contenido='$contenido', fecha = now() WHERE id= $uniqueid";
    $result = mysqli_query($db, $sql);
}
?>