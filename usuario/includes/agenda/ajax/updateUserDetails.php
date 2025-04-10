<?php
// include Database connection file
include("db_connection.php");

// check request
if(isset($_POST))
{
    // get values
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    //$last_name = $_POST['last_name'];
    $numero = $_POST['numero'];

    // Updaste User details
    $query = "UPDATE $database SET first_name = '$first_name', numero = '$numero' WHERE id = '$id'";
    if (!$result = mysqli_query($db,$query)) {
        exit(mysqli_error($db));
    }
}
