<?php
	include('../../../../funciones/functions.php');
	if (empty($_POST['delete_id'])){
		$errores[] = "Id vacío.";
	}
	else if (!empty($_POST['delete_id'])){

	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
    $id=intval($_POST['delete_id']);


	// DELETE FROM  database
    $sql = "DELETE FROM agenda WHERE id='$id'";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "El producto ha sido eliminado con éxito.";
    } else {
        $errores[] = "Lo sentimos, la eliminación falló. Por favor, regrese y vuelva a intentarlo.";
    }

	} else
	{
		$errores[] = "desconocido.";
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
