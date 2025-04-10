<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Guardar Clientes";
include('../../funciones/functions.php');

    $id = $_POST['id'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $telefono1 = $_POST['telefono1'];
    $telefono2 = $_POST['telefono2'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];

    function update_clientes(){
        global $db, $id_usua, $id, $cedula, $nombre, $telefono1, $telefono2, $direccion, $estado, $ciudad, $pag_web;
        // Verificar la conexión
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        // Preparar la consulta SQL
        $query = $db->prepare("UPDATE clientes SET cedula = ?, nombre = ?, telefono1 = ?, telefono2 = ?, direccion = ?, estado = ?, ciudad = ?, idusuario = ?, fecha_update = NOW() WHERE id = ?");
        $query->bind_param("ssssssssi", $cedula, $nombre, $telefono1, $telefono2, $direccion, $estado, $ciudad, $id_usua, $id);
    
        // Ejecutar la consulta
        if ($query->execute()) {
            echo 'Se ha actualizado de manera exitosa al usuario <b>'.$nombre.'</b>';
        } else {
            echo 'Ha ocurrido un error: '.$db->error;
        }
        // Cerrar la conexión
        $db->close();
    }
    
    update_clientes();
?>