<?php
	include('../../../../funciones/functions.php');
	if (empty($_POST['first_name'])){
		$errores[] = "Ingresa el nombre.";
	}
	elseif (!empty($_POST['first_name'])){
	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code

	$id_usua = mysqli_real_escape_string($con,(strip_tags($_POST["id_usua"],ENT_QUOTES)));
  $first_name = mysqli_real_escape_string($con,(strip_tags($_POST["first_name"],ENT_QUOTES)));
	$numero = mysqli_real_escape_string($con,(strip_tags($_POST["numero"],ENT_QUOTES)));

	// REGISTER data into database
    $sql = "INSERT INTO agenda(id, id_user, first_name, numero, fecha) VALUES (NULL,'$id_usua','$first_name','$numero',NOW())";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "Se ha guardado con éxito.";
    } else {
        $errores[] = "Lo sentimos, el registro falló. Por favor, vuelva a intentarlo.". mysqli_error($con);
    }

	} else
	{
		$errores[] = "Error desconocido.";
	}
if (isset($errores)){

			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong>
					<?php
						foreach ($errores as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){

				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}
?>
