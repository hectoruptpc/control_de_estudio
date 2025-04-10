<?php
	if(isset($_POST['first_name']) && isset($_POST['numero']))
	{
		// include Database connection file
		include("db_connection.php");

		// get values
		$first_name = $_POST['first_name'];
		//$last_name = $_POST['last_name'];
		$numero = $_POST['numero'];

		$query = "INSERT INTO $database (id_user, first_name, numero) VALUES('$id_usua','$first_name', '$numero')";
		if (!$result = mysqli_query($db,$query)) {
	        exit(mysqli_error($db));
	    }
	    echo "1 Numero agregado a su Agenda!";
	}
?>
