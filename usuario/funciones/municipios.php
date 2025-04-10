<?php
include('../../funciones/functions.php');

if (isset($_POST['estado'])) {
    $id_estado = $_POST['estado'];

    $query = "SELECT id_municipio, municipio FROM municipios WHERE id_estado = '$id_estado' ORDER BY municipio";
    $result = mysqli_query($db, $query);

    $municipios = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $municipios[] = $row;
    }

    echo json_encode($municipios);
}
?>
