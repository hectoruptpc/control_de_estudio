<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Ver todas las Mensualidades";
include('../funciones/functions.php');

// LISTAR PAGOS MENSUALES TOTAL
function lista_pagos_mes_total(){
    global $db, $usua, $mes;
    $limit_end = 100;

    $url = basename($_SERVER ["PHP_SELF"]);

    if (isset($_REQUEST['busqueda'])) {
        $busqueda = strtolower(e($_REQUEST['busqueda']));
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

      $countmes="SELECT COUNT(*) FROM pagos INNER JOIN users
      ON pagos.user=users.idusuario";
      $querymes = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos INNER JOIN users
      ON pagos.user=users.idusuario ORDER BY id DESC LIMIT $init, $limit_end";
      $resultmes = mysqli_query($db, $querymes);
      $rowmes =  mysqli_num_rows($resultmes);

      $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Mensualidades que mostrar.';

        } else {

            $countmes="SELECT COUNT(*) FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
            WHERE (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' )";

            $querymes = "SELECT pagos.*, users.nombre, users.email, users.username FROM pagos
            INNER JOIN users
            ON pagos.user=users.idusuario
             WHERE (user LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR status_pago LIKE '%$busqueda%' OR mes_de_pago LIKE '%$busqueda%' OR afiliacion LIKE '%$busqueda%'  OR banco_origen LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%'  OR ci_nro_cuenta LIKE '%$busqueda%' ) ORDER BY pagos.id DESC LIMIT $init, $limit_end";

	        $resultmes = mysqli_query($db, $querymes);
            $rowmes =  mysqli_num_rows($resultmes);

            $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';

        }

	if (!$rowmes){

	echo '<div class="alert alert-danger" role="alert" >';
	echo '<h3>';
	echo $mensaje;
	//unset($_SESSION['successmes']);
	echo '</h3>';
	echo '</div>';

	} else {
		$num = $db->query($countmes);
		$x = $num->fetch_array();
        $total = ceil($x[0]/$limit_end);


        echo '<div class="d-none d-sm-none d-md-block">';
            pag($ini, $limit_end, $total);
        echo "</div>";
        echo '<div class="d-block d-sm-block d-md-none">';
        pag_test($ini, $limit_end, $total);
        echo "</div>";

    $link_aprobar_mes = '<a href="#">Aprobar</a>';

	echo '<div class="container">';
    echo '<table id="tabla1" class="table table-bordered table-hover" style="width: 50%;">
    <thead>
     <tr>
      <th>Usuario</th>
      <th>Nombre</th>
      <th>Fecha de Transf </th>
      <th>Monto / Mes Pagado</th>
      <th>Nro Transf / CI</th>
      <th>Desde / Hasta</th>
      <th>Status</th>
     </tr>
     </thead>
     <tbody>';

     $c = $db->query($querymes);
     while($rowmes = $c->fetch_array(MYSQLI_ASSOC))
      {

        $rowUser = $rowmes['user'];
        $rowid = $rowmes['id'];


      $rowNombre = $rowmes['nombre'];

      $date = date_create($rowmes['fecha_transf']);
      $fecha = date_format($date, 'd-m-Y');
      $fecha_pago = $fecha;
$status_pago = $rowmes['status_pago'];
$motivo = strip_tags((string) $rowmes['motivo_rechazo']);

if ($status_pago == 'APROBADO'){
$status_pago = '<div class="text-center w-70 mx-auto alert alert-success alert-sm" role="alert" data-toggle="popover" title="APROBADO" data-content="Este pago ya fue aprobado">
APROBADO  <i class="fa fa-thumbs.-up"></i>
</div>';
}
else if ($status_pago == 'PENDIENTE'){

  $aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "mensualidades.php?id='.$rowid.'&user='.$rowUser.'"><button type="submit" class="btn btn-success " name="aprobar_pago_btn">Aprobar</button> </form>';

  $rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=mensualidad" type="submit" class="btn btn-danger btn-sm" name="rechazar_pago_mensualidad_btn">Rechazar</a>';

        $link_aprobar_mes = '<div class="btn-group-vertical" >'. $aprobar .$rechazar . '</div>';

$status_pago =  $link_aprobar_mes;

}
else if ($status_pago== 'RECHAZADO'){
  $status_pago = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-toggle="popover" title="RECHAZADO" data-content="Este pago fue rechazado, por el siguiente motivo: '.$motivo.'.">
  RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
</div>';
}

echo '<tr>';
echo '<td>'. $rowUser .'</td>
      <td>'. $rowNombre .'</td>
       <td>'.$fecha_pago .'</td>
       <td>'.$rowmes['monto'].' Bs. / '.$rowmes['mes_de_pago']. '</td>
       <td>'.$rowmes['nro_transf'] . ' / '.$rowmes['ci_nro_cuenta'].'</td>
       <td>'.$rowmes['banco_origen'].' / '.$rowmes['banco_destino'] .'</td>
       <td>'.$status_pago.'</td>
      </tr>';
      }
      echo '</tbody></table>';

      echo '<div class="d-none d-sm-none d-md-block">';
          pag($ini, $limit_end, $total);
      echo "</div>";
      echo '<div class="d-block d-sm-block d-md-none">';
      pag_test($ini, $limit_end, $total);
      echo "</div>";

}

}



if (isset($_REQUEST['busqueda'])) {
    $busqueda = strtolower($_REQUEST['busqueda']);
} else {
    $busqueda = "";
}

?>
<?php include("includes/head.php"); ?>
<div class="container">



<div class="row  mb-3">
<div class="col-xs-12 col-sm-6">


<h2>Todas las Mensualidades</h2>

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

<!-- notification message -->
<?php if (isset($_SESSION['todas_mensualidades'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['todas_mensualidades'];
						unset($_SESSION['todas_mensualidades']);
					?>
				</h3>
			</div>
<?php endif ?>
 <!-- Origen de Tabla -->
 <?php lista_pagos_mes_total(); ?>
	<!-- Fin de Tabla -->



</div>
<?php include("includes/footer.php"); ?>
