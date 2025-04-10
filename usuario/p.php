<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');


$titulopag = "Perfil";
	include('../funciones/functions.php');
	include('../funciones/test.php');
		include('funciones/estados.php');

	?>

	<script type="text/javascript">
		// function showselect(str){
		// 	var xmlhttp;
		// 	if (str=="")
		// 		{
		// 		document.getElementById("txtHint").innerHTML="";
		// 		return;
		// 		}
		// 	if (window.XMLHttpRequest)
		// 		{// code for IE7+, Firefox, Chrome, Opera, Safari
		// 		xmlhttp=new XMLHttpRequest();
		// 		}
		// 	else
		// 		{// code for IE6, IE5
		// 		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		// 		}
		// 	xmlhttp.onreadystatechange=function()
		// 		{
		// 		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		// 		 {
		// 		 document.getElementById("municipios").innerHTML=xmlhttp.responseText;
		// 		 }
		// 		}
		// 		xmlhttp.open("GET","funciones/municipios.php?e="+str,true);
		// 	xmlhttp.send();
		// }

		function showselect(strA){
			var xmlhttpA;
			if (strA=="")
				{
				document.getElementById("txtHint").innerHTML="";
				return;
				}
			if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttpA=new XMLHttpRequest();
				}
			else
				{// code for IE6, IE5
				xmlhttpA=new ActiveXObject("Microsoft.XMLHTTP");
				}
			xmlhttpA.onreadystatechange=function()
				{
				if (xmlhttpA.readyState==4 && xmlhttpA.status==200)
				 {
				 document.getElementById("municipios").innerHTML=xmlhttpA.responseText;
				 }
				}
				xmlhttpA.open("GET","funciones/municipios.php?e="+strA,true);
			xmlhttpA.send();
			console.log(xmlhttpA);
			console.log(strA);

		}


function showselectP(str){
			console.log("Cargando...");
			var xmlhttp;

			if (str=="")
				{
				document.getElementById("txtHint").innerHTML="";
				return;
				}
			if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
				}
			else
				{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			xmlhttp.onreadystatechange=function()
				{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				 {
				 document.getElementById("parroquias").innerHTML=xmlhttp.responseText;
				 }
				}
				xmlhttp.open("GET","funciones/parroquias.php?m="+str,true);
			xmlhttp.send();

		}


	</script>



	<?php

    if (isAdmin()) {
        header('location: ../admin/home.php');
    }

		function mostrar_perfil2(){
		    global $db, $usua;
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
		    echo $rows['estado'];
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

				$modal_editar_usuario = ' <form autocomplete="off" class="was-validated" method="post" action= "perfil.php">';

				$modal_editar_usuario .= 'Identificador: ' .$rowid .'<br>';
				$modal_editar_usuario .= 'Nombre: ' .$nombre_usuario .'<br>';
				$modal_editar_usuario .= 'Email: ' .$email_usuario .'<br>';
				$modal_editar_usuario .= '<div class="dropdown-divider"></div>';






				$modal_editar_usuario .= '<div class="form-group">
				<label for="telefono_usuario">Numero de Telefono Local</label>
				<input type="tel" pattern="[0]{1}[2]{1}[1-9]{1}[0-9]{8}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567" class="form-control" id="telefono_usuario" aria-describedby="telefono_usuario" placeholder="Ingrese su numero de Telefono local" name="telefono_usuario" value="';
				$modal_editar_usuario .= $telefono_usuario;
				$modal_editar_usuario .= '" required>
				<div class="invalid-feedback">Debe indicar el numero de Telefono local, Debe usar minimo 11 digitos debe incluir el codigo de area, Ejemplo: 02431234567.</div>
				</div>

				<div class="form-group">
				<label for="celular_usuario">Numero de Celular</label>
				<input type="tel" pattern="[0]{1}[4]{1}[1,2]{1}[2,4,6]{1}[0-9]{7}" title = "Debe utilizar solo Numeros, Minimo 11 digitos debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567" class="form-control" class="form-control" id="celular_usuario" aria-describedby="celular_usuario" placeholder="Ingrese su numero de Celular" name="celular_usuario" value="';
				$modal_editar_usuario .= $celular_usuario;
				$modal_editar_usuario .= '" required>
				<div class="invalid-feedback">Debe indicar su numero de telefono Celular, debe incluir el codigo de la operadora, Ejemplo: 04161234567, 04141234567 o 04121234567.</div>
				</div>

				<div class="form-group">
				<label for="direccion_usuario">Su Direccion Completa</label>
				<input type="textarea" class="form-control" id="direccion_usuario" aria-describedby="direccion_usuario" placeholder="Ingrese su Direccion" name="direccion_usuario" value="';
				$modal_editar_usuario .= $direccion_usuario;
				$modal_editar_usuario .= '" required>
				<div class="invalid-feedback">Debe indicar su Direccion completa.</div>
				</div>';

				$modal_editar_usuario .= '
				<div class="form-group">
				<label for="estado_usuario">Estado donde Vive</label>
						<select name="estados" class="form-control" onchange="showselect(this.value)" required >
							'.
estados().'
						</select>
						<div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
					</div>';

				$modal_editar_usuario .= '
				<div class="form-group">
				<label for="municipio_usuario">Municipio donde Vive</label>
						<div id="municipios">
							<select id="municipio" name="municipios" class="form-control" onchange="showselectP(this.value)" required>
								<option value="">Seleccione</option>
							</select>
							<div class="invalid-feedback">Debe indicar el Municipio donde vive.</div>
						</div>
					</div><p id="demo">Aqui</p>';

					$modal_editar_usuario .= '
					<div class="form-group">
					<label for="parroquia_usuario">Parroquia donde Vive</label>
							<div id="parroquias">
								<select name="parroquias" class="form-control" required>
									<option value="">Seleccione</option>
								</select>
								<div class="invalid-feedback">Debe indicar La Parroquia donde vive.</div>
							</div>
						</div>';



				echo $modal_editar_usuario;
				}


?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>

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
