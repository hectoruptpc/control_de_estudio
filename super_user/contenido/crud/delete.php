<?php
include '../../../funciones/conexion.php';

if(isset($_POST['deletesend'])){
    $unique = $_POST['deletesend'];
    $sql = "DELETE FROM contenido WHERE id=$unique";
    $result = mysqli_query($db, $sql);
}
?>
