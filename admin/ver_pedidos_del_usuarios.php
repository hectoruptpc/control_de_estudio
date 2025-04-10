  <?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Pedidos de Usuario";
include('../funciones/functions.php');

function ver_pedidos_del_usuario($rowid, $id_usuario, $nombre_usuario){
  global $db, $limit_end, $img_ope;


  $rowid = $_SESSION['A'];
  $id_usuario = $_SESSION['B'];
  $nombre_usuario = $_SESSION['C'];


// $rowid = $_SESSION['A'];
// $id_usuario = $_SESSION['B'];
// $nombre_usuario = $_SESSION['C'];

//$url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


//$url = basename($_SERVER ["PHP_SELF"].$_SERVER['REQUEST_URI']);




//$url = basename($_SERVER ["PHP_SELF"]);
//echo $url;

if (isset($_REQUEST['busqueda'])) {
  $busqueda = strtolower($_REQUEST['busqueda']);
} else {
  $busqueda = "";
}



if (isset($_GET['p']))
  $ini=$_GET['p'];
else
  $ini=1;

$init = ($ini-1) * $limit_end;

if (empty($busqueda)) {
  $busqueda = "";

   $count="SELECT COUNT(*) FROM pedidos WHERE usuario = '$id_usuario' ";

   $query = "  SELECT * FROM pedidos WHERE usuario = '$id_usuario'
            ORDER BY id
            DESC LIMIT $init, $limit_end";



} else {


  $count="SELECT COUNT(*) FROM pedidos WHERE usuario = '$id_usuario' AND (nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%') ";

  $query = "  SELECT * FROM pedidos WHERE usuario = '$id_usuario' AND (nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%')
           ORDER BY id
           DESC LIMIT $init, $limit_end";

}

$result = mysqli_query($db, $query);
$row =  mysqli_num_rows($result);

	if (!$row){

		$mensaje  = 'No hay datos que Mostrar';
	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	echo '</h3>';
	echo '</div>';

	} else
	{
		$num = $db->query($count);
		$x = $num->fetch_array();
		$total = ceil($x[0]/$limit_end);

    pag($ini, $limit_end, $total);

		echo '<div class="table-responsive">';

		echo '<table id="tabla1" class="table table-bordered table-hover ">
		<thead>
		 <tr>
		  <th>ID</th>
      <th>Fecha / Monto</th>
      <th>Nº Transf / Banco / C.I. Titular</th>
		  <th>Status</th>
      <th>Accion</th>
		 </tr>
		 </thead>
		 <tbody>';

		 $c = $db->query($query);
		 while($row = $c->fetch_array(MYSQLI_ASSOC))
		  {
        $id = $row['id'];
        $operador = $row['operador'];
        $status_pedido = $row['status_pedido'];
        $query_detalle_pedidos = "SELECT SUM(monto) AS 'total_pedido', SUM(CASE WHEN id_pedido = '$id' THEN 1 ELSE 0 END) AS 'cant_tarjetas' FROM tarjetas WHERE id_pedido = '$id'";
        $resultado_query = mysqli_query($db,$query_detalle_pedidos);
        $rs = mysqli_num_rows($resultado_query);
        while ($r = mysqli_fetch_assoc($resultado_query)){
          $st = $r['total_pedido'];
          $ct = $r['cant_tarjetas'];
        }



if ($operador == 'Movilnet') {
        if (!$st) {

          $a = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="SIN ASIGNACION" data-content="Este pedido no posee asignacion de tarjetas.">
          SIN ASIGNACION  <i class="fa fa-clock"></i>
        </div>';

        } else {

        $res = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="RESUMEN" data-content="En este pedido fueron entregados '.$st.' Bs. en  '.$ct.' Unidades de Tarjetas.">
        RESUMEN  <i class="fa fa-clipboard-list"></i>
      </div>';

      $bot = '<a class="btn btn-warning btn-sm" href="preparar_entrega_pedido.php?id='.$id.'&user='.$id_usuario.'">ENVIAR MAS <i class="fa fa-paper-plane"></i></a>';

      $add = '<div class="btn-group-vertical" >'.$res. $bot . '</div>';

      $a = $add ;
      // preparar_entrega_pedido.php?id=113&user=V-18026050
    }

		 $accion = $a;

}
//if ($operador == 'Movistar') {}
//if ($operador == 'Digitel') {}
//if ($operador == 'Directv') {}
//if ($operador == 'Inter') {}

else {

  if ($status_pedido == 'ESPERANDO') {
      $sp = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="SIN ASIGNACION" data-content="Este pedido no posee asignacion de tarjetas.">
      ESPERA <i class="fa fa-clock"></i>
    </div>';
  }
  else if ($status_pedido == 'APROBADO') {
      $sp = '<form autocomplete="off" class="was-validated" method="post" action= "preparar_entrega_pedido.php?id='.$id.'&user='.$nombre_usuario.'"><button type="submit" class="btn btn-warning btn-sm" name="enviar_pedido_btn">Enviar Pedido  <i class="fa fa-paper-plane"></i></button> </form>';
  }
  else if ($status_pedido == 'RECHAZADO') {

    $motivo = strip_tags($row['motivo_rechazo']);

      $sp = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-html="true" data-toggle="popover" title="RECHAZADO" data-content="Motivo: '.$motivo.'"> RECHAZADO  <i class="fa fa-exclamation-triangle"></i> </div>';
  }
  else if ($status_pedido == 'ENTREGADO') {
      $res = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="ENTREGADO" data-content="Este pedido ya fue entregado">
      ENTREGADO  <i class="fa fa-clipboard-list"></i>
    </div>';
      //$add .= $accion;
    $sp =  $res ;
  }
  $status_pedido = $sp;
  $accion = $status_pedido;
}


  img_ope($operador);

 echo '<tr>';
 echo '<td>'.$row['id'].'</td>
     <td>Monto: '.$row['monto'].'<br>Bs.<br>'.
     'Declarado: '.$row['fecha_pedido'].'<br>'.
     'Transferencia: '.$row['fecha_transf'].'<br>'.
     'Aprobado: '.$row['fecha_aprobacion'].'<br>'.
     'Despachado: '.$row['fecha_entrega'].'<br>'.
     'Rechazo: '.$row['fecha_rechazo'].'<br>'.
      '</td>
     <td>'.'Desde: '.$row['banco_emisor'].' '.$row['ci_nro_cuenta'].'<br>'.'Hacia: '.$row['banco_destino'].'<br>'.$row['nro_transf'].' / '.$row['ci_nro_cuenta'].'</td>
       <td>'.$row['status_pedido'] .'<br> '.$img_ope.'</td>
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
<?php

$_SESSION['A'] = $_REQUEST['id'];
$_SESSION['B'] = $_REQUEST['usuario'];
$_SESSION['C'] = $_REQUEST['nombre_usuario'];

$rowid = $_SESSION['A'];
$id_usuario = $_SESSION['B'];
$nombre_usuario = $_SESSION['C'];


  if (isset($_REQUEST['busqueda'])) {
        $busqueda = strtolower(e($_REQUEST['busqueda']));
    } else {
        $busqueda = "";
    }

if (isset($_SESSION['ver_pedidos_del_usuarios'])) : ?>
			<div class="alert alert-danger" role="alert" >
				<h3>
					<?php
						echo $_SESSION['ver_pedidos_del_usuarios'];
						unset($_SESSION['ver_pedidos_del_usuarios']);
					?>
				</h3>
			</div>
<?php endif ?>


        <div class="row  mb-3">
            <div class="col-xs-12 col-sm-6">


<h2>Pedidos del Usuario <?php echo $nombre_usuario;?> </h2>


<?php
$url = "ver_pedidos_del_usuarios.php?id='.$rowid.'&usuario='.$id_usuario.'&nombre_usuario='.$nombre_usuario.'";
$_SESSION['URL'] = $url;

echo $url;
 ?>

</div>

<div class="col-xs-12 col-sm-6">
    <form action="<?php $url; ?>" method="post">
            <div class="input-group">
            <input name="busqueda" id="busqueda" type="text" class="form-control" placeholder="Realizar Busqueda" aria-label="buscar" aria-describedby="button-addon2" value="<?php echo $busqueda; ?>">
            <div class="input-group-append">
                <button  value="buscar" class="btn btn-outline-secondary" type="submit" id="buscar">Buscar</button>
            </div>
    </form>
</div>
</div>
</div>







	<!-- Origen de Edicion -->
	<?php ver_pedidos_del_usuario($rowid, $id_usuario, $nombre_usuario); ?>
	<!-- Fin de Edicion -->


</div>
<?php include("includes/footer.php"); ?>
