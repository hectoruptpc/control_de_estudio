<?php
// include Database connection file
include("db_connection.php");

// check request
if(isset($_POST['id']) && isset($_POST['id']) != "")
{
    // get User ID
    $user_id = $_POST['id'];

    // Get User Details
    $query = "SELECT * FROM $database WHERE id = '$user_id'";
    if (!$result = mysqli_query($db,$query)) {
        exit(mysqli_error($db));
    }
    $response = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }
    }
    else
    {
        $response['status'] = 200;
        $response['message'] = "Dato no Existe!";
    }
    // display JSON data
    echo json_encode($response);
}
else
{
    $response['status'] = 200;
    $response['message'] = "Invalido!";
}