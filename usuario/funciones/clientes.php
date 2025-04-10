<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Consulta Clientes";
include('../../funciones/functions.php');

$cedula = $_POST['cedula'];

//$cedula =  'V-12345678';


function clientes(){
    global $db, $cedula, $nombre, $telefono1, $telefono2, $direccion, $ciudad, $estado, $id_usua;

    $arreglo = [];

    $query = "SELECT * FROM clientes WHERE cedula = '$cedula' AND 	idusuario = '$id_usua' ";
    mysqli_query($db, $query);
    $c = $db->query($query);
    while($data = $c->fetch_array(MYSQLI_ASSOC))
    {
        $data['id']=$data['id'];
        $cedula = $data['cedula'];
        $nombre = $data['nombre'];
        $telefono1 = $data['telefono1'];
        $telefono2 = $data['telefono2'];
        $direccion = $data['direccion'];
        $ciudad = $data['ciudad'];
        $estado = $data['estado'];

        $arreglo ["data"][] = $data;
    }

    echo json_encode($arreglo);
}

clientes();




?>