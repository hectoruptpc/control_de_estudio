<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema de Gestion";
	include('../funciones/functions.php');
	requireAdmin();


	//ESTADISTICA DE USUARIOS
	function estadistica_usuarios(){
	  global $db, $resultado_estadistica, $limit_end, $mes_de_pago_actual;

$datos_apa = '';



	echo "<h4>ESTADISTICA DE USUARIOS</h4>";

	  $sql="SELECT
	  SUM(IF( password !='', 1,0)) AS 'ingreso',
	  SUM(status = 0) AS 'bloqueados',
	  COUNT(*) AS 'total'
	  FROM users";
	  $result = mysqli_query($db, $sql) or mysqli_error($db);
	  while ($row = mysqli_fetch_assoc($result))
	  {
	  if( $row['ingreso'] <1){
	echo "NO HAY RESULTADOS";
	  } else {
	    $sin_password = $row['total'] - $row['ingreso'];
	    $resta2 = $row['bloqueados'] - $sin_password ;
	    $resultado_estadistica = 'Total de Usuarios Ingresados: ' . $row['total'].'<br>';
	    $resultado_estadistica .= 'Total de Usuarios Bloqueados: ' . $row['bloqueados'].'<br>';
	    $resultado_estadistica .= 'Total que ya han creado Password: ' . $row['ingreso'].'<br>';
	    $resultado_estadistica .= 'Total que faltan por crear Password Incluido los Bloqueados: ' . $sin_password .'<br>';
	    $resultado_estadistica .= 'Total que faltan por crear Password No Bloqueados: ' . $resta2 .'<br>';
	    }
	}


	//$analisis_banesco = "SELECT usuario, users.nombre AS 'nombre', users.email AS 'email', users.tlf AS 'tlf', users.cel AS 'cel', users.fecha_ingreso AS 'fecha_ingreso', SUM(monto) AS 'monto_total', COUNT( usuario ) AS 'num'	FROM pedidos INNER JOIN users ON (pedidos.usuario=users.idusuario) WHERE status_pedido = 'ENTREGADO'	GROUP BY `usuario`,'monto_total', nombre, email, tlf, cel, fecha_ingreso	ORDER BY monto_total DESC	LIMIT 0 , $limit_end";


	$analisis_general = "SELECT usuario, users.nombre AS 'nombre', users.email AS 'email', users.tlf AS 'tlf', users.cel AS 'cel', users.fecha_ingreso AS 'fecha_ingreso', SUM(monto) AS 'monto_total', COUNT( usuario ) AS 'num'
	FROM pedidos INNER JOIN users ON (pedidos.usuario=users.idusuario) WHERE status_pedido = 'ENTREGADO'
	GROUP BY `usuario`,'monto_total', nombre, email, tlf, cel, fecha_ingreso
	ORDER BY monto_total DESC
	LIMIT 0 , $limit_end";

	$analisis_mes = "SELECT usuario, users.nombre AS 'nombre', users.email AS 'email', SUM(monto) AS 'monto_total', COUNT( usuario ) AS 'num'
	FROM pedidos INNER JOIN users ON (pedidos.usuario=users.idusuario) WHERE status_pedido = 'ENTREGADO'
	AND DATE_FORMAT(pedidos.fecha_pedido, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m')
	GROUP BY `usuario`, 'monto_total', nombre, email
	ORDER BY monto_total DESC
	LIMIT 0 , $limit_end";

	$resultado_analisis_g = mysqli_query($db,$analisis_general);
	$resultado_analisis_m = mysqli_query($db,$analisis_mes);

	$sqlq = "SET lc_time_names = 'es_VE'";
	mysqli_query($db,$sqlq);
	 //_epe

	$sql_epe = "SELECT DATE_FORMAT(fecha_pedido,'%M %Y') AS dateGroup, SUM(monto) AS 'monto_total' FROM pedidos WHERE status_pedido = 'ENTREGADO' GROUP BY dateGroup ORDER by DATE_FORMAT(dateGroup, '%m/%d') DESC LIMIT 0 , $limit_end";
	//$sql_epe = "SELECT DATE_FORMAT(fecha_pedido,'%M %Y') AS dateGroup, SUM(monto) AS 'monto_total' FROM pedidos WHERE status_pedido = 'ENTREGADO' GROUP BY dateGroup ORDER by DATE_FORMAT(dateGroup, '%m') DESC LIMIT 0 , $limit_end";
	$result_epe = mysqli_query($db,$sql_epe);

//MOFIFICAR A QUE MUESTRE SOLO DEL AÑO EN CURSO
	$sql_apa="SELECT DATE_FORMAT(fecha_pago,'%M %Y') AS dateGroup2, SUM(monto) AS 'monto_total2' FROM pagos WHERE status_pago = 'APROBADO' GROUP BY dateGroup2 ORDER BY DATE_FORMAT(dateGroup2, '%M/%d') DESC LIMIT 0 , $limit_end";

	//$sql_apa="SELECT DATE_FORMAT(fecha_aprobacion,'%M %Y') AS dateGroup2, SUM(monto) AS 'monto_total2' FROM pagos WHERE status_pago = 'APROBADO' GROUP BY dateGroup2 ORDER BY DATE_FORMAT(dateGroup2,'%m') DESC LIMIT 0 , $limit_end";

	    $result_apa = mysqli_query($db, $sql_apa);

	    $datos_apa = array();

	    // ahora guardamos los datos de la consulta apa en nuestro array
	    while ($j = mysqli_fetch_assoc($result_apa)) {
	      array_push($datos_apa, $j['monto_total2']);
	      }
//var_dump($datos_apa);
	      $i = 0;



	  }

	function calculo_sin_plan(){
			global $db, $monto_sin_plan_calculo, $mes_de_pago_actual;

				$m = date("n");
				$a = date("Y");
			$query = "SELECT * FROM pedidos WHERE sin_plan = '1' AND MONTH(fecha_transf) = '$m' AND YEAR(fecha_transf) = '$a'";

			echo '<b>EXTRA</b><br>';
			$results = mysqli_query($db, $query);
			$rows =  mysqli_num_rows($results);
			echo 'Sin Plan '. $rows.' = ';
			$monto_sin_plan_calculo  = 0;
			while ($r = mysqli_fetch_assoc($results)){
				$monto_sin_plan_calculo += $r['monto'];

		}echo number_format($monto_sin_plan_calculo *30/100, 2, ',', '.') . ' Bs.';
	}

?>
<!DOCTYPE html>
<html lang="es">


  <?php include("includes/head.php"); ?>



	<?php
echo @$inicio;
echo @$finaliza;
	 ?>

    <!-- Page Content -->
    <div class="container">
    <style>
.carousel-control-prev-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23f00' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}
</style>


    
    <hr>







<!-- Icon Cards-->
<div class="row">
<div class="col-xl-3 col-sm-6 mb-3">
<div class="card text-white bg-primary o-hidden h-100">
<div class="card-body">
<div class="card-body-icon">
<i class="fas fa-fw fa-comments"></i>
</div>
<div class="mr-5">
<h4>Mensajeria</h4>
Mensajeria</div>
</div>
<a  title="Mensajeria" class="card-footer text-white clearfix small z-1" href="mensajeria.php">
<span class="float-left"> Ver Detalles</span>
<span class="float-right">
<i class="fa fa-arrow-circle-right"></i>
</span>
</a>
</div>
</div>



<div class="col-xl-3 col-sm-6 mb-3">
<div class="card text-white bg-info o-hidden h-100">
<div class="card-body">
<div class="card-body-icon">
<i class="fas fa-fw fa-list"></i>
</div>
<div class="mr-5"><h4>Estadistica</h4>estadistica</div>
</div>
<a class="card-footer text-white clearfix small z-1" title="Ir a Pedidos" href="pedidos.php">
<span class="float-left">Ver Detalles</span>
<span class="float-right">
<i class="fa fa-arrow-circle-right"></i>
</span>
</a>
</div>
</div>





<div class="col-xl-3 col-sm-6 mb-3">
<div class="card text-white bg-success o-hidden h-100">
<div class="card-body">
<div class="card-body-icon">
<i class="fas fa-fw fa-shopping-cart"></i>
</div>
<div class="mr-5"><h4>Pagos</h4>aqui iran los pagos</div>
</div>
<a class="card-footer text-white clearfix small z-1" title="Ir a Mensualidades" href="registro_pagos.php">
<span class="float-left">Ver Detalles</span>
<span class="float-right">
<i class="fa fa-arrow-circle-right"></i>
</span>
</a>
</div>
</div>




<div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-danger o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fas fa-fw fa-life-ring"></i>
        </div>
        <div class="mr-5"><h4>Soporte</h4>
Soporte</div>          </div>
      <a class="card-footer text-white clearfix small z-1" title="Soporte" href="soporte.php">
        <span class="float-left">Ir a Soporte</span>
        <span class="float-right">
        <i class="fa fa-arrow-circle-right"></i>
        </span>
      </a>
    </div>
  </div>
</div>

<h2>

</h2>
</div>

<div class="table-responsive">
<hr>


<hr>
</div>
           

     <hr>
  
    <hr>
    </div>
		<?php
		if ($pendiente_pedido>0) {
			echo "<script>
 	 Push.create('Hay Pedidos Pendientes!', {
 			 body: 'Hola hay pedidos pendientes por ser atentidos',
 			 icon: '../images/logo_mini.png',
 			 timeout: 8000,
 			 onClick: function () {
 					 window.focus();
 					 this.close();
 			 }
 	 });
 	 </script>";
	 	}

	 if ($pendiente_mensualidad>0) {
		  echo "<script>
     Push.create('Hay Mensualidades Pendientes!', {
         body: 'Hola hay Mensualidades pendientes.',
         icon: '../images/logo_mini.png',
         timeout: 8000,
         onClick: function () {
             window.focus();
             this.close();
         }
     });
     </script>";
	 }


		 ?>


    <?php include("includes/footer.php"); ?>
 <script>
Push.create('Hola Bienvenido/a!', {
    body: 'Me estan configurando, pronto estare disponible para avisar cuando alguian efectue un pago de mensualidad o pedidos',
    icon: '../images/logo_mini.png',
    timeout: 8000,
    onClick: function () {
        window.focus();
        this.close();
    }
});
</script>
