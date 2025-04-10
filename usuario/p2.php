<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

$titulopag = "Perfil";
	include('../funciones/functions.php');
	include('../funciones/test.php');

	function estados(){
  	global $db;
  	$query = "SELECT * FROM estados";
  	$results = mysqli_query($db, $query);
    $a = '<option value="">Seleccione</option>';
  	while ($fila = mysqli_fetch_assoc($results)) {
  			$a .= "<option value='".$fila['id_estado']."'>".$fila['estado']."</option>";
    }
    return $a;
  }

		function mostrar_perfil2(){
		    global $db, $usua;
				$estado_usuario = "";
		    $query = "SELECT * FROM users WHERE username = '$usua'";
		    $result = mysqli_query($db, $query);
		    $rows = mysqli_fetch_array($result);

		    $id = $rows['id'];
		    $control = $rows['control'];

		    echo '<h3>Los datos de su Usuario</h3>';

		    echo '<div class="card">';
		    echo '<ul class="list-group list-group-flush">';

		    echo '<li class="list-group-item">';
		    echo '<b>Usuario: </b>';
		    echo $rows['username'];
		    echo '<br><b>Nombre: </b>';
		    echo $rows['nombre'];
		    echo '<br><b>Email: </b>';
		    echo $rows['email'];
		    echo  '</li>';

		    echo '<li class="list-group-item">';
		    echo '<b>Telefono: </b>';
		    echo $rows['tlf'];
		    echo '<br><b>Celular: </b>';
		    echo $rows['cel'];
		    echo '<br><b>Direccion: </b>';
		    echo $rows['direccion'];
		    echo '<br><b>Estado: </b>';
				$estado_usuario = $rows['estado'];
				if ($estado_usuario > 0) {

					$queryeu = "SELECT * FROM estados WHERE id_estado = '$estado_usuario'";
					$resultseu = mysqli_query($db, $queryeu);
					while ($filaeu = mysqli_fetch_assoc($resultseu)) {
					$estado_usuario = strtoupper($filaeu['estado']);
					}



				}
else {
				$estado_usuario = $rows['estado'];
			}
		    echo $estado_usuario;
		    echo '<br><b>Ciudad: </b>';
		    echo $rows['ciudad'];
		    echo '<br><b>Municipio: </b>';
		    echo $rows['municipio'];
		    echo '<br><b>Parroquia: </b>';
		    echo $rows['parroquia'];
		    echo '</li>';
		    echo '</ul>';
		    echo '</div>';

		    echo '<div class="text-right">
		    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
		    <i class="fa fa-user-edit"></i> Editar</button>

		    <a  class="btn btn-danger" type="button" href="../crear_password.php?id='.$id.'&control='.$control.'"><i class="fa fa-key"></i> Cambiar Contraseña</a>
		    </div>';


		    echo '<!-- Modal -->
		    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		      <div class="modal-dialog" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		              <span aria-hidden="true">&times;</span>
		            </button>
		          </div>
		          <div class="modal-body">';
		          modal_editar_desde_usuario_A();
		          echo '
		          </div>
		          <div class="modal-footer">
		            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		            <button name="editar_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
		          </div>
		        </div>
		      </div>
		    </div>';




		    echo '<!-- Modal -->
		    <div class="modal fade" id="cambiarpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		      <div class="modal-dialog" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel">Cambiar Password o Contraseña de Acceso</h5>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		              <span aria-hidden="true">&times;</span>
		            </button>
		          </div>
		          <div class="modal-body">';
		          modal_editar_password_desde_usuario();
		          echo '
		          </div>
		          <div class="modal-footer">
		            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		            <button name="editar_password_desde_usuario_btn" type="submit" class="btn btn-primary">Guardar Cambios</button> </form>
		          </div>
		        </div>
		      </div>
		    </div>';

		}



				//PARA EL MODAL DE EDITAR USUARIO
				function modal_editar_desde_usuario_A(){
					global $db, $idusuario,
						$nombre_usuario, $email_usuario,  $telefono_usuario,
						$celular_usuario, $password_usuario, $user_type, $rowid;

						$usua = ($_SESSION['user']['username']);

						$query = "SELECT * FROM users WHERE username = '$usua'";
						$result = mysqli_query($db, $query);
						$row = mysqli_fetch_array($result);

						$rowid = $row['username'];
					 // $rowid = $row['id'];
									$idusuario = $row['idusuario'];
									$nombre_usuario = $row['nombre'];
									$email_usuario = $row['email'];
									$telefono_usuario = $row['tlf'];
									$celular_usuario = $row['cel'];
									$direccion_usuario = $row['direccion'];
									$ciudad_usuario = $row['ciudad'];
									$estado_usuario = $row['estado'];
									$municipio_usuario = $row['municipio'];
									$parroquia_usuario = $row['parroquia'];
									//$password_usuario = $row['password'];
									$status_usuario = $row['status'];

									if ($estado_usuario > 0) {

										$queryeu = "SELECT * FROM estados WHERE id_estado = '$estado_usuario'";
										$resultseu = mysqli_query($db, $queryeu);
										while ($filaeu = mysqli_fetch_assoc($resultseu)) {
										$estado = "<option value='".$filaeu['id_estado']."'>".$filaeu['estado']."</option>";
										}
$estado .= estados();
$estado_usuario = $filaeu['estado'];


									}
else {
									$estado = estados();
								}

				$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

				$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
				$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
				$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
				$modal_editar_usuario .= '<div class="dropdown-divider"></div>';

				$modal_editar_usuario .= '<div class="form-group">
				<label for="telefono_usuario">Numero de Telefono Local</label>
				<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="'.$telefono_usuario.'" required>
				<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
				</div>';

				$modal_editar_usuario .= '<div class="form-group">
				<label for="celular_usuario">Numero de Celular</label>
				<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="'.$celular_usuario.'" required>
				<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
				</div>';


				$modal_editar_usuario .= '<div class="form-group">
				<label for="estado_usuario">Estado donde Vive</label>
				    <select id="estados" name="estados" class="form-control" required >
				'.$estado.'
				    </select>
				    <div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
				  </div>';

					$modal_editar_usuario .= '
					<div class="form-group">
				  <label for="Ciudad_usuario">Ciudad donde Vive</label>
				      <select id="ciudades" name="ciudades" class="form-control" required >
				      </select>
				      <div class="invalid-feedback">Debe indicar la Ciudad donde vive.</div>
				    </div>';

					$modal_editar_usuario .= '
					<div class="form-group">
				  <label for="Municipio_usuario">Municipio donde Vive</label>
				      <select id="municipios" name="municipios" class="form-control" required >
				      </select>
				      <div class="invalid-feedback">Debe indicar el Municipio donde vive.</div>
				    </div>';

						$modal_editar_usuario .= '
						<div class="form-group">
					  <label for="Parroquia_usuario">Parroquia donde Vive</label>
					      <select id="parroquias" name="parroquias" class="form-control" required >
					      </select>
					      <div class="invalid-feedback">Debe indicar el Parroquia donde vive.</div>
					    </div>';



				echo $modal_editar_usuario;
				}


?>
<!DOCTYPE html>
<html lang="es">

  <?php include("includes/head.php"); ?>

<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.1.1.js"></script>

	<script language = "javascript">
	$(document).ready(function () {

		$("#estados").change(function (){

			$("#parroquias").find("option").remove().end().append('<option value="0"</option>').val("0");

			$("#estados option:selected").each(function () {
				id_estado = $(this).val();
				$.post("funciones/ciudades.php", {id_estado: id_estado}, function(data) {
					$("#ciudades").html(data);
				});
				$.post("funciones/municipios.php", {id_estado: id_estado}, function(datab) {
					$("#municipios").html(datab);
				});
			});
		})

	});



	$(document).ready(function () {
		$("#municipios").change(function (){
			$("#municipios option:selected").each(function () {
				id_municipio = $(this).val();
				$.post("funciones/parroquias.php", {id_municipio: id_municipio}, function(data) {
					$("#parroquias").html(data);
				});
			});
		})

	});
	</script>

    <!-- Page Content -->
    <div class="container">

<!-- notification message -->
<?php if (isset($_SESSION['msn_perfil'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['msn_perfil'];
						unset($_SESSION['msn_perfil']);
					?>
				</h3>
			</div>
<?php endif ?>

     <?php mostrar_perfil2(); ?>

    </div>
    <?php include("includes/footer.php"); ?>
