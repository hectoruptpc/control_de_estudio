<?php
include('../../funciones/functions.php');

if (isset($_POST['municipio'])) {
    $id_municipio = $_POST['municipio'];

    $query = "SELECT id_parroquia, parroquia FROM parroquias WHERE id_municipio = '$id_municipio' ORDER BY parroquia";
    $result = mysqli_query($db, $query);

    $parroquias = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $parroquias[] = $row;
    }

    echo json_encode($parroquias);
}
?>
