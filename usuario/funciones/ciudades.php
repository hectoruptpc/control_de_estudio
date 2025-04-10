<?php
include('../../funciones/functions.php');

if (isset($_POST['estado'])) {
    $id_estado = $_POST['estado'];

    $query = "SELECT id_ciudad, ciudad FROM ciudades WHERE id_estado = '$id_estado' ORDER BY ciudad";
    $result = mysqli_query($db, $query);

    $ciudades = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $ciudades[] = $row;
    }

    echo json_encode($ciudades);
}
?>
