<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Gestion de Usuarios";
include('../funciones/functions.php');

// LISTA DE USUARIOS
function lista_usuarios()
{
  global $db, $accion, $res;

  // Variables
  $limit_end = isset($_REQUEST['limite']) ? e($_REQUEST['limite']) : 10;
  $order = 'id DESC';
  $url = basename($_SERVER['PHP_SELF']);
  $user_tipo = 'user';
 

  // Paginación
  $ini = isset($_GET['p']) ? $_GET['p'] : 1;
  $init = ($ini - 1) * $limit_end;

  // Búsqueda
  $busqueda = isset($_REQUEST['busqueda']) ? e($_REQUEST['busqueda']) : '';
  $filtro = isset($_REQUEST['filtro']) ? e($_REQUEST['filtro']) : '';
  $query = '';
  

  // Filtros
  if ($busqueda !== '') {

    $count = "SELECT COUNT(*) FROM users
    WHERE (idusuario LIKE '%$busqueda%' OR
    nombre LIKE '%$busqueda%' OR
    email LIKE '%$busqueda%' OR
    tlf LIKE '%$busqueda%' OR
    cel LIKE '%$busqueda%')";

    $query = "SELECT * FROM users
    WHERE (users.idusuario LIKE '%$busqueda%' OR users.nombre LIKE '%$busqueda%' OR
    users.email LIKE '%$busqueda%' OR
    users.tlf LIKE '%$busqueda%' OR
    users.cel LIKE '%$busqueda%')
    AND user_type = '$user_tipo'
    ORDER BY id DESC
    LIMIT $init, $limit_end";
  } 
  else if ($filtro == "tlf_repedidos") { 
    $count = "SELECT COUNT(*) AS total FROM (
      SELECT *
      FROM users
      GROUP BY tlf
      HAVING COUNT(*) > 1
    ) AS tabla_agrupada";

    $query = "SELECT * FROM users
    GROUP BY tlf
    HAVING COUNT(*) > 1
    ORDER BY tlf ASC
    LIMIT $init, $limit_end";
  }
  else if ($filtro == "sin_password") { 
    $count = "SELECT COUNT(*) FROM users WHERE user_type = '$user_tipo' AND users.password IS NULL";
    $query = "SELECT * FROM users
    WHERE user_type = '$user_tipo'
    AND users.password IS NULL
    GROUP BY id
    ORDER BY id 
    DESC LIMIT $init, $limit_end";
  }
  else {
    $count = "SELECT COUNT(*) FROM users";

    $query = "SELECT * FROM users
    WHERE user_type = '$user_tipo'
    ORDER BY id DESC
    LIMIT $init, $limit_end";
  }

  // Ejecutar la consulta
  $result = mysqli_query($db, $query);

  // Comprobar si hay resultados
  if ($result) {
    $num_rows = mysqli_num_rows($result);

    // PARA USAR EN PAGINACION
    $num = $db->query($count);
    $x = $num->fetch_array();
    $total = ceil($x[0] / $limit_end);

    echo 'Se muestran ' . $total . ' Paginas.<br>';
    echo 'Se enlistan ' . $x[0] . ' Registros.<br>';

        // Paginación
        pag($ini, $limit_end, $total);

    // Mostrar los resultados
    echo '<div class="table-responsive">';
    echo '<table id="usuarios" class="table table-bordered table-hover">';
    echo '<thead>';
    echo '<tr><th>Id / Nombre / CI / Email / Fecha Ingreso</th><th>Condicion</th><th>Accion</th></tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
 
      $date = date_create($row['fecha_ingreso']);
      $fecha = date_format($date, 'd-m-Y');
      //$fecha_ingreso = $fecha;
      //$abab = $row['status'];
      $rowid = $row['id'];
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
      $password_usuario = $row['password'];
      $status_usuario = $row['status'];
      $fecha_ingreso = $row['fecha_ingreso'];
//      $monto_a_favor = $row['Monto_a_favor'];
      if (!$password_usuario) {
        $p = '<button class="mx-auto btn btn-sm btn-danger" role="alert" data-toggle="popover" title="Aun no ha creado su Password" data-content="Este usuario aun no ha ingresado al sistema.">
No  <i class="fa fa-clock"></i>
</button>';
        $password = '<div class="btn-group-vertical" >' . $p . '</div>';
      } else {
        $password = '<button class="mx-auto btn btn-sm btn-success" role="alert" data-toggle="popover" title="Ya creo su Password" data-content="Este usuario ya ha ingresado al sistema y creo su password.">
Yes   <i class="fa fa-thumbs-up"></i>
</button>';
      }
      $status_usuario = $row['status'];
      //$motivo_bloqueo = strip_tags($row['motivo_bloqueo']);
      $motivo_bloqueo = $row['motivo_bloqueo'];
      if ($motivo_bloqueo !== null) {
        $motivo_bloqueo_limpio = strip_tags($motivo_bloqueo);
      } else {
        $motivo_bloqueo_limpio = '';
        // o mostrar un mensaje de error, por ejemplo:
        // echo 'Error: el motivo de bloqueo es nulo.';
      }
      if ($status_usuario == 1) {
        $ba = '<button type="submit" class="btn btn-success btn-sm" name="activar_bloquear_btn" data-toggle="popover" title="ACTIVO" data-content="Usuario ACTIVO haga click para desactivarlo o suspenderlo.">ACT <i class="fa fa-thumbs.-up"></i></button><br>';

        $bb = '<a href= "activar_desactivar.php?id=' . $idusuario . '" type="submit" class="btn btn-success btn-sm"  data-toggle="popover" title="ACTIVO" data-content="Usuario ACTIVO haga click para desactivarlo o suspenderlo y notificarle por correo el motivo de la suspension.">ACT <i class="fa fa-envelope"></i></a><br>';
        $stdp = '<div class="btn-group-vertical" >' . $ba . $bb . $password . '</div>';
      } else {
        $motivo_bloqueo = strip_tags($motivo_bloqueo, "<img><h1><h2><h3><h4><h5><br><p><b>");
        $stdp = '<button type="submit" class="btn btn-danger btn-sm" name="activar_bloquear_btn" data-toggle="popover" title="BLOQUEADO" data-html="true"  data-content="Usuario BLOQUEADO o SUSPENDIDO, haga click para activarlo. <h3>Motivo de Bloqueo</h3> ' . $motivo_bloqueo . '">BLOQUEADO <i class="fa fa-thumbs.-down"></i></button><br><br>' . $motivo_bloqueo . '';
        //$stdp .='<div>'. $motivo_bloqueo.'</div>';
      }
      $link = '<form autocomplete="off" class="was-validated" method="post" action= "">
<input type="hidden" name="id" value="' . $rowid . '">
<input type="hidden" name="status" value="' . $status_usuario . '">
<input type="hidden" name="nombre" value="' . $nombre_usuario . '">
<input type="hidden" name="email" value="' . $email_usuario . '">
' . $stdp . ' </form>';
      botonera_usuario($nombre_usuario, $idusuario);
      analisis_pedidos_por_cliente($idusuario);
      //$email = <a href="mailto:$row['email']">.$row['email']</a>;
      $email_link = '<a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a>';
      $telefono_usuario = '<a href="tel:' . $telefono_usuario . '">' . $telefono_usuario . '</a>';
      $celular_usuario = '<a href="tel:' . $celular_usuario . '">' . $celular_usuario . '</a>';
      $ultimo_mens = "SELECT * FROM `mensajes` WHERE destinatario = '$idusuario' ORDER BY id DESC LIMIT 1";
      $result_mens = mysqli_query($db, $ultimo_mens);
      $row_mens = mysqli_fetch_array($result_mens);
      //$contenido = $row_mens['contenido'];

      if ($row_mens !== null && array_key_exists('contenido', $row_mens)) {
        $contenido = $row_mens['contenido'];
      } else {
        $contenido = '';
        // o mostrar un mensaje de error, por ejemplo:
        // echo 'Error: el contenido del mensaje es nulo o no existe.';
      }
      if (empty($contenido)) {
        $contenido = '';
      } else {
        $contenidoa = substr($contenido, 0, 600) . ".......";
        $contenido = 'Ultimo Mensaje:<div class="alert alert-dark" role="alert">' . e($contenidoa) .
          '</div>';
      }
      $blo = '#: ' . $rowid . '<br>' .
        $idusuario . '<br><b>' .
        $nombre_usuario . '</b><br>' .
        $email_link . '<br>' .
        $telefono_usuario . '<br>' .
        $celular_usuario . '<br>' .
        $res . '<br>' .
        'Fecha de Ingreso: ' . $fecha_ingreso . '<br>' .
     //   'Monto a Favor: ' . number_format($monto_a_favor, 2, ',', '.') . '<br>' .
        $contenido;
      echo '<tr>';

      echo '<td width="50%" class="align-middle">' . $blo . '</td>
<td class="align-middle">' . $link . '</td>
<td class="align-middle">' . $accion . '</td>';
    }

    echo '</tbody></table></div>';

    // Paginación
    pag($ini, $limit_end, $num_rows);
  } else {
    // Mostrar un mensaje de error
    echo '<div class="alert alert-danger" role="alert">Ocurrió un error al consultar la base de datos.</div>';
  }
}
?>
<?php include("includes/head.php"); ?>





<div class="container">

<?php
if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower($_REQUEST['busqueda']);
  } else {
    $busqueda = "";
  }
    ?>

<div class="row  mb-3">

  <div class="col-sm-12">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agregar_usuario">
  Agregar nuevo Usuario
</button>
  </div>

  <div class="col-sm-12">

  <form action="usuarios.php" method="get">
<!--
<input type="checkbox" id="filtro1" name="filtro" value="afavor">
<label for="filtro1"> Saldo a Favor</label><br> -->
<input type="checkbox" id="filtro2" name="filtro" value="tlf_repedidos">
<label for="filtro2"> Telefonos Repetidos</label><br>
<input type="checkbox" id="filtro3" name="filtro" value="sin_password">
<label for="filtro3"> No han Creado Contraseña</label><br>

    <button  value="filtrar" class="btn btn-outline-secondary" type="submit" id="filtrar">Filtrar</button>

  </form>

</div>

<div class="col-sm-12">

	<form action="usuarios.php" method="get">
  <div class="input-group">
  <input name="busqueda" id="busqueda" type="text" class="form-control" placeholder="Realizar Busqueda" aria-label="buscar" aria-describedby="button-addon2" value="<?php echo $busqueda; ?>">
  <div class="input-group-append">
    <button  value="buscar" class="btn btn-outline-secondary" type="submit" id="buscar">Buscar</button>
  </div>
  </form>

	</div>

</div>


  </div>


<!-- Button trigger modal -->

<h2>Usuarios</h2>

<div class="btn-group" role="group" aria-label="Basic example">
  <a title="Ir al CNE" href="http://www.cne.gob.ve/" target="_blank" type="button" class="btn btn-warning">CNE</a>
  <a title="Consultar RIF" href="http://contribuyente.seniat.gob.ve/BuscaRif/BuscaRif.jsp" target="_blank" type="button" class="btn btn-info">SENIAT</a>
  <a title="Google Groups" href="gg.php" type="button" class="btn btn-danger ">Google Groups</a>
</div>
<p>
<?php
$web = basename($_SERVER['REQUEST_URI']);
echo 'Esta en la web: '.$web;
 ?>
 </p>
<!-- notification message -->
<?php if (isset($_SESSION['usuarios'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['usuarios'];
						unset($_SESSION['usuarios']);
					?>
				</h3>
			</div>
<?php endif ?>

<!-- notification message -->
<?php if (isset($_SESSION['msn'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['msn'];
						unset($_SESSION['msn']);
					?>
				</h3>
			</div>
<?php endif ?>

	<!-- Origen de Tabla -->
	<?php lista_usuarios(); ?>
	<!-- Fin de Tabla -->

<!-- Modal Agregar Usuario -->
<div id="agregar_usuario" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usuariosLabel">Agregar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

<?php modal_agregar_usuario();
 ?>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
 <!-- Fin de Modal Generar Pedido -->

</div>


<script language='javascript'>
$('.summernote').summernote({
  placeholder: 'Ingrese su contenido',
  tabsize: 2,
  height: 200
});
 </script>

<script>
  $('#usuarios').stacktable();
</script>


<?php include("includes/footer.php"); ?>
