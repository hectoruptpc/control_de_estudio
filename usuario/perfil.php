<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Perfil";
include('../funciones/functions.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    // Redirigir a la página de inicio de sesión
    header('location: ../index.php'); 
    exit();
}

// Obtener los datos del usuario
$query = "SELECT * FROM users WHERE id = '$id_usua'";
$result = mysqli_query($db, $query);
$row = mysqli_fetch_array($result);

// Variables para los datos del usuario
$idusuario = $row['idusuario'];
$nombre_usuario = $row['nombre']?? null;
$email_usuario = $row['email']?? null;
$telefono_usuario = $row['tlf']?? null;
$celular_usuario = $row['cel']?? null;
$direccion_usuario = $row['direccion']?? null;
$estado_usuario = $row['estado']?? null;
$ciudad_usuario = $row['ciudad']?? null;
$municipio_usuario = $row['municipio']?? null;
$parroquia_usuario = $row['parroquia']?? null;
$rif_comercio = $row['rif_comercio']?? null;
$nombre_comercio = $row['nombre_comercio']?? null;
$direccion_comercio = $row['direccion_comercio']?? null;
$logo_comercio = $row['logo_comercio']?? null;
$web_comercio = $row['web_comercio']?? null;



// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $celular = $_POST['celular'];
    $direccion = $_POST['direccion'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $municipio = $_POST['municipio'];
    $parroquia = $_POST['parroquia'];
    $nombre_comercio = $_POST['nombre_comercio'];
    $rif_comercio = $_POST['rif_comercio'];
    $direccion_comercio = $_POST['direccion_comercio'];
    $web_comercio = $_POST['web_comercio'];

    // Actualizar la base de datos
    $query_update = "UPDATE users SET 
                        nombre = '$nombre',
                        email = '$email',
                        tlf = '$telefono',
                        cel = '$celular',
                        direccion = '$direccion',
                        estado = '$estado',
                        ciudad = '$ciudad',
                        municipio = '$municipio',
                        parroquia = '$parroquia',
                        rif_comercio = '$rif_comercio',
                        nombre_comercio = '$nombre_comercio',
                        direccion_comercio = '$direccion_comercio',
                        web_comercio = '$web_comercio'
                    WHERE id = '$id_usua'";

    if (mysqli_query($db, $query_update)) {
		 // Procesar la imagen del logo (si se ha subido)
		 if (isset($_FILES['logo_comercio']) && $_FILES['logo_comercio']['error'] === UPLOAD_ERR_OK) {

			// Obtener la ruta de la imagen anterior
			$query_logo_anterior = "SELECT logo_comercio FROM users WHERE id = '$id_usua'";
			$result_logo_anterior = mysqli_query($db, $query_logo_anterior);
			$row_logo_anterior = mysqli_fetch_assoc($result_logo_anterior);
			$ruta_imagen_anterior = $row_logo_anterior['logo_comercio'];
	
			// ... (Código para procesar y guardar la imagen del logo) ...
	
			// Borrar la imagen anterior (si existe)
			if (!empty($ruta_imagen_anterior) && file_exists($ruta_imagen_anterior)) {
				unlink($ruta_imagen_anterior);
			}


			$nombreArchivo = $_FILES['logo_comercio']['name'];
			$tipoArchivo = $_FILES['logo_comercio']['type'];
			$tamañoArchivo = $_FILES['logo_comercio']['size'];
			$rutaTemporal = $_FILES['logo_comercio']['tmp_name'];
	
			// Validar el tipo de archivo (opcional)
			$tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
			if (in_array($tipoArchivo, $tiposPermitidos)) {
				// Generar un nombre único para el archivo
				$nombreArchivoUnico = uniqid() . '_' . $nombreArchivo;
				$rutaDestino = 'uploads/' . $nombreArchivoUnico; // Ruta donde se guardará la imagen
	
				// Mover el archivo subido a la carpeta de destino
				if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
					// Actualizar la base de datos con la ruta de la imagen
					$query_logo = "UPDATE users SET logo_comercio = '$rutaDestino' WHERE id = '$id_usua'";
					mysqli_query($db, $query_logo); 
				} else {
					// Mostrar un mensaje de error
					echo '<div class="alert alert-danger" role="alert">Error al subir el logo.</div>';
				}
			} else {
				// Mostrar un mensaje de error
				echo '<div class="alert alert-danger" role="alert">Tipo de archivo no válido.</div>';
			}
		}
	
		// Mostrar un mensaje de éxito
		$_SESSION['perfil'] = 'Perfil actualizado correctamente.';
		 // Recargar la imagen del logo (usando JavaScript)// Esperar 5 segundos


			// Redirigir a la misma página
			header("Location: " . $_SERVER['PHP_SELF']);
			exit(); // Detener la ejecución del script

		// Redirigir a la misma página
		header("Location: " . $_SERVER['PHP_SELF']);
		exit(); // Detener la ejecución del script
	} else {
		// Mostrar un mensaje de error
		echo '<div class="alert alert-danger" role="alert">Error al actualizar el perfil: ' . mysqli_error($db) . '</div>';
	}
}




?>


<?php include("includes/head.php"); ?>

<div class="container mt-5">
  <h1>Perfil de Usuario</h1>

    <!-- notification message -->
	<?php if (isset($_SESSION['perfil'])) : ?>
  			<div class="alert alert-success" role="alert" >
  				<h3>
  					<?php
  						echo $_SESSION['perfil'];
  						unset($_SESSION['perfil']);
  					?>
  				</h3>
  			</div>
  <?php endif ?>

  <div class="row mt-3 justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><h2>Información del Usuario</h2></div>
        <div class="card-body">
          <form id="form-perfil" method="post" enctype="multipart/form-data">
            <input type="hidden" id="idusuario" name="idusuario" value="<?php echo $idusuario; ?>">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre_usuario; ?>" required>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo $email_usuario; ?>" required>
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono:</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono_usuario; ?>">
            </div>
            <div class="form-group">
              <label for="celular">Celular:</label>
              <input type="tel" class="form-control" id="celular" name="celular" value="<?php echo $celular_usuario; ?>">
            </div>
            <div class="form-group">
              <label for="direccion">Dirección:</label>
              <textarea class="form-control" id="direccion" name="direccion" rows="3"><?php echo $direccion_usuario; ?></textarea>
            </div>
            <div class="form-group">
              <label for="estado">Estado:</label>
              <select class="form-control" id="estado" name="estado" required>
                <option value="">Seleccione un estado</option>
              </select>
            </div>
            <div class="form-group">
              <label for="ciudad">Ciudad:</label>
              <select class="form-control" id="ciudad" name="ciudad" required>
                <option value="">Seleccione una ciudad</option>
              </select>
            </div>
            <div class="form-group">
              <label for="municipio">Municipio:</label>
              <select class="form-control" id="municipio" name="municipio" required>
                <option value="">Seleccione un municipio</option>
              </select>
            </div>
            <div class="form-group">
              <label for="parroquia">Parroquia:</label>
              <select class="form-control" id="parroquia" name="parroquia" required>
                <option value="">Seleccione una parroquia</option>
              </select>
            </div>
            <div class="form-group">
              <label for="rif_comercio">Rif de Comercio:</label>
              <input type="text" class="form-control" id="rif_comercio" name="rif_comercio" value="<?php echo $rif_comercio; ?>" >
            </div>
            <div class="form-group">
              <label for="nombre_comercio">Nombre de Comercio:</label>
              <input type="text" class="form-control" id="nombre_comercio" name="nombre_comercio" value="<?php echo $nombre_comercio; ?>" >
            </div>
            <div class="form-group">
              <label for="direccion_comercio">Dirección Comercio:</label>
              <textarea class="form-control" id="direccion_comercio" name="direccion_comercio" rows="3"><?php echo $direccion_comercio; ?></textarea>
            </div>
            <div class="form-group">
              <label for="web_comercio">Web de Comercio:</label>
              <input type="text" class="form-control" id="web_comercio" name="web_comercio" value="<?php echo $web_comercio; ?>" >
            </div>
            <div class="form-group">
              <label for="logo_comercio">Logo:</label>
              <input type="file" class="form-control-file" id="logo_comercio" name="logo_comercio" accept="image/*">
              <?php if (!empty($logo_comercio)): ?>
                <img id="imagen-logo" src="<?php echo $logo_comercio; ?>" alt="Logo del comercio" width="100">
              <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Cargar estados al iniciar la página
    cargarEstados();

    // Manejar cambio de estado
    $("#estado").change(function(){
        var id_estado = $(this).val();
        cargarCiudades(id_estado);
        cargarMunicipios(id_estado);
    });

    // Manejar cambio de ciudad
    $("#ciudad").change(function(){
        var id_estado = $("#estado").val();
        cargarMunicipios(id_estado);
    });

    // Manejar cambio de municipio
    $("#municipio").change(function(){
        var id_municipio = $(this).val();
        cargarParroquias(id_municipio);
    });

    // Función para cargar estados
    function cargarEstados() {
        fetch('funciones/estados.php')
            .then(response => response.json())
            .then(data => {
                const selectEstado = document.getElementById('estado');
                data.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.id_estado;
                    option.text = estado.estado;
                    selectEstado.add(option);
                });

                // Selecciona el estado del usuario después de cargar los estados
                selectEstado.value = '<?php echo $estado_usuario; ?>';
                // Carga las ciudades del estado seleccionado
				cargarCiudades(selectEstado.value);
				cargarMunicipios(selectEstado.value);
            })
            .catch(error => console.error(error));
    }

    // Función para cargar ciudades
    function cargarCiudades(id_estado) {
        var formData = new FormData();
        formData.append('estado', id_estado);

        fetch('funciones/ciudades.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const selectCiudad = document.getElementById('ciudad');
                selectCiudad.innerHTML = '<option value="">Seleccione una ciudad</option>';
                data.forEach(ciudad => {
                    const option = document.createElement('option');
                    option.value = ciudad.id_ciudad;
                    option.text = ciudad.ciudad;
                    selectCiudad.add(option);
                });

                // Selecciona la ciudad del usuario después de cargar las ciudades
                selectCiudad.value = '<?php echo $ciudad_usuario; ?>';
                // Carga los municipios de la ciudad seleccionada
                cargarMunicipios(id_estado);
            })
            .catch(error => console.error(error));
    }

    // Función para cargar municipios
    function cargarMunicipios(id_estado) {
        var formData = new FormData();
        formData.append('estado', id_estado);

        fetch('funciones/municipios.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const selectMunicipio = document.getElementById('municipio');
                selectMunicipio.innerHTML = '<option value="">Seleccione un municipio</option>';
                data.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio.id_municipio;
                    option.text = municipio.municipio;
                    selectMunicipio.add(option);
                });

                // Selecciona el municipio del usuario después de cargar los municipios
                selectMunicipio.value = '<?php echo $municipio_usuario; ?>';
                // Carga las parroquias del municipio seleccionado
                cargarParroquias(selectMunicipio.value);
            })
            .catch(error => console.error(error));
    }

    // Función para cargar parroquias
    function cargarParroquias(id_municipio) {
        var formData = new FormData();
        formData.append('municipio', id_municipio);

        fetch('funciones/parroquias.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const selectParroquia = document.getElementById('parroquia');
                selectParroquia.innerHTML = '<option value="">Seleccione una parroquia</option>';
                data.forEach(parroquia => {
                    const option = document.createElement('option');
                    option.value = parroquia.id_parroquia;
                    option.text = parroquia.parroquia;
                    selectParroquia.add(option);
                });

                // Selecciona la parroquia del usuario después de cargar las parroquias
                selectParroquia.value = '<?php echo $parroquia_usuario; ?>';
            })
            .catch(error => console.error(error));
    }
});
</script>


<?php include("includes/footer.php"); ?>
