<?php
include('../../funciones/functions.php');

$query = "SELECT id_estado, estado FROM estados ORDER BY estado";
$result = mysqli_query($db, $query);

$estados = array();
while ($row = mysqli_fetch_assoc($result)) {
    $estados[] = $row;
}

echo json_encode($estados);
?>
