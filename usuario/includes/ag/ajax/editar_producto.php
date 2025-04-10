<?php
	include('../../../../funciones/functions.php');
	if (empty($_POST['edit_id'])){
		$errores[] = "ID está vacío.";
	}
	elseif (!empty($_POST['edit_id'])){

	require_once ("../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
  $first_name = mysqli_real_escape_string($con,(strip_tags($_POST["edit_first_name"],ENT_QUOTES)));
	$numero = mysqli_real_escape_string($con,(strip_tags($_POST["edit_numero"],ENT_QUOTES)));
	$id=intval($_POST['edit_id']);
	// UPDATE data into database
    $sql = "UPDATE agenda SET first_name='".$first_name."', numero='".$numero."', fecha=NOW() WHERE id='".$id."' ";
    $query = mysqli_query($con,$sql);
    // if product has been added successfully
    if ($query) {
        $messages[] = "El producto ha sido actualizado con éxito.";
    } else {
        $errores[] = "Lo sentimos, la actualización falló. Por favor, regrese y vuelva a intentarlo.";
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
