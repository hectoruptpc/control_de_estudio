<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Guardar Clientes";
include('../../funciones/functions.php');

    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $telefono1 = $_POST['telefono1'];
    $telefono2 = $_POST['telefono2'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];

    function guardar_clientes(){
        global $db, $id_usua, $cedula, $nombre, $telefono1, $telefono2, $direccion, $estado, $ciudad, $pag_web;
  // Verificar la conexión
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
// Preparar la consulta SQL
$query = $db->prepare("INSERT INTO clientes (cedula, nombre, telefono1, telefono2, direccion, estado, ciudad, idusuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("ssssssss", $cedula, $nombre, $telefono1, $telefono2, $direccion, $estado, $ciudad, $id_usua);

// Ejecutar la consulta
if ($query->execute()) {
    echo 'Se ha guardado de manera exitosa al usuario <b>'.$nombre.'</b>';
} else {
    echo 'Ha ocurrido un error: '.$db->error;
}
// Cerrar la conexión
$db->close();
    }


    guardar_clientes();
    // Aquí puedes conectar con tu base de datos y guardar los datos
?>