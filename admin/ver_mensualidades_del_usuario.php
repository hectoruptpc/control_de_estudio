<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Mensualidades de Usuario";
include('../funciones/functions.php');


function ver_mensualidades_del_usuario(){
  global $db, $limit_end;
$rowid = $_REQUEST['id'];
$id_usuario =$_REQUEST['usuario'];
$nombre_usuario = $_REQUEST['nombre_usuario'];

echo '<h2>Mensualidades del Usuario '.$nombre_usuario.' </h2>';

$url = basename($_SERVER ["PHP_SELF"]);

if (isset($_GET['p']))
  $ini=$_GET['p'];
else
  $ini=1;

$init = ($ini-1) * $limit_end;


   $count="SELECT COUNT(*) FROM pagos WHERE user = '$id_usuario' ";
   $query = "  SELECT * FROM pagos WHERE user = '$id_usuario'
            ORDER BY id
            DESC LIMIT $init, $limit_end";


/* querys */
// $count="SELECT COUNT(*) FROM users";


//  $query = "  SELECT * FROM users
//              WHERE user_type = '$user_tipo'
//              ORDER BY id
//              DESC LIMIT $init, $limit_end";
	$result = mysqli_query($db, $query);
	$row =  mysqli_num_rows($result);
	if (!$row){

		$mensaje  = 'No hay datos que Mostrar';
	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	//unset($_SESSION['successmes']);
	echo '</h3>';
	echo '</div>';

	} else
	{
		//$mysql = new mysqli(HOST, USER, PASSWD, DB);
		$num = $db->query($count);
		$x = $num->fetch_array();
		$total = ceil($x[0]/$limit_end);

    pag($ini, $limit_end, $total);

		echo '<div class="table-responsive">';

		echo '<table id="tabla1" class="table table-bordered table-hover ">
		<thead>
		 <tr>
		  <th>ID</th>
		  <th>Fecha / Mes de Pago</th>
      <th>Tipo de Afiliacion</th>
      <th>Status de Pago</th>
      <th>Accion</th>
		 </tr>
		 </thead>
		 <tbody>';

		 $c = $db->query($query);
		 while($row = $c->fetch_array(MYSQLI_ASSOC))
		  {

		 $accion = "Se esta Configurando";





 echo '<tr>';
 echo '<td>'.$row['id'].'</td>
	   <td>'.$row['fecha_transf'].
     ' <br> '.$row['mes_de_pago'].
     ' <br> '.$row['monto']. ' Bs'.
     ' <br> '.$row['nro_transf'].
     '<br>Desde: '.$row['banco_origen'].
     ' <br>Hacia: '.$row['banco_destino'].
     '</td>
       <td>'.$row['afiliacion'] .'</td>
       <td>'.$row['status_pago'] .'</td>
       <td>'.$accion.'</td>';
		 }
echo '</tr></tbody></table>';



pag($ini, $limit_end, $total);
}

}

?>
<?php include("includes/head.php"); ?>
<div class="container">




<!-- notification message -->
<?php if (isset($_SESSION['ver_mensualidades_del_usuarios'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['ver_mensualidades_del_usuarios'];
						unset($_SESSION['ver_mensualidades_del_usuarios']);
					?>
				</h3>
			</div>
<?php endif ?>

	<!-- Origen de Edicion -->
	<?php ver_mensualidades_del_usuario(); ?>
	<!-- Fin de Edicion -->


</div>
<?php include("includes/footer.php"); ?>
