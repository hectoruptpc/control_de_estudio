<?php
include '../../../funciones/conexion.php';

extract($_POST);

if(isset($_POST['seccionSend']) && isset($_POST['contenidoSend'])){
    $sql = "INSERT INTO contenido (seccion, contenido) VALUES ('$seccionSend', '$contenidoSend')";
    $result = mysqli_query($db, $sql);
}
?>
