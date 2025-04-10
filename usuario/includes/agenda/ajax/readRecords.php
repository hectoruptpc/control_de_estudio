<?php
	// include Database connection file
	include("db_connection.php");
//echo 'La BD es ' . $database;

	// Design initial table header
$data = '<table class="table table-bordered table-striped">
	<tr>
		<th>No.</th>
		<th>Nombre</th>
		<th>Numero</th>
		<th>Actualizar</th>
		<th>Borrar</th>
	</tr>';

	$query = "SELECT * FROM $database WHERE id_user = $id_usua";

	if (!$result = mysqli_query($db,$query)) {
        exit(mysqli_error($db));
				//echo mysqli_error($db);
    }

    // if query results contains rows then featch those rows
    if(mysqli_num_rows($result) > 0)
    {
    	$number = 1;
    	while($row = mysqli_fetch_assoc($result))
    	{
    		$data .= '<tr>
				<td>'.$number.'</td>
				<td>'.$row['first_name'].'</td>
				<td>'.$row['numero'].'</td>

				<td>
					<button onclick="GetUserDetails('.$row['id'].')" class="btn btn-warning">Cambiar</button>
				</td>
				<td>
					<button onclick="DeleteUser('.$row['id'].')" class="btn btn-danger">Borrar</button>
				</td>
    		</tr>';
    		$number++;
    	}
    }
    else
    {
    	// records now found
    	$data .= '<tr><td colspan="6">No hay Datos</td></tr>';
    }

    $data .= '</table>';

		//var_dump($data);


			echo $data;



?>
