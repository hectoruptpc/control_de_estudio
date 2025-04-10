<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Lista Total de Pedidos";
	include('../funciones/functions.php');


    if (isset($_REQUEST['busqueda'])) {
        $busqueda = strtolower($_REQUEST['busqueda']);
    } else {
        $busqueda = "";
    }


		// Lista Total de Pedidos Generados
		function lista_total_pedidos(){
		    global $db, $error;
		    $limit_end = 10;
		    $url = basename($_SERVER ["PHP_SELF"]);


		if (isset($_GET['p']))
		$ini=$_GET['p'];
		else
		$ini=1;

		$init = ($ini-1) * $limit_end;

		if (isset($_REQUEST['busqueda'])) {
		  $busqueda = strtolower($_REQUEST['busqueda']);

		} else {
		  $busqueda = "";
		}

		if (isAdmin()) {
		  //SI ES ADMINISTRADOOR
		  //echo 'ADMINISTRADOR';

		  if (empty($busqueda)) {
		    $busqueda = "";
		  $count="SELECT COUNT(*) FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario";

		  $query = "SELECT  pedidos.*, users.id AS uid, users.nombre, users.email, users.username FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario ORDER BY id DESC LIMIT $init, $limit_end";

		  } else {

		    $count = "SELECT COUNT(*) FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario WHERE (email LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR monto LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%')";

		    $query = "SELECT pedidos.*,users.id AS uid, users.nombre, users.email, users.username FROM pedidos  INNER JOIN users ON pedidos.usuario=users.idusuario WHERE (email LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR monto LIKE '%$busqueda%' OR usuario LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%') ORDER BY id DESC LIMIT $init, $limit_end";

		  }
		  $result = mysqli_query($db, $query);
		  $row =  mysqli_num_rows($result);

		  if (!$row){

		        $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda.';
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
		               <th>id</th>
		               <th>Usuario</th>
		               <th>Nombre</th>
		                <th>Fecha / Monto</th>
		                <th>Nro Transf / Cedula</th>
		                <th>Emisor / Receptor</th>
		                <th>Status</th>
		               </tr>
		               </thead>
		               <tbody>';


		               $c = $db->query($query);
		               while($row = $c->fetch_array(MYSQLI_ASSOC))
		                {

		                $date = date_create($row['fecha_transf']);
		                $fecha = date_format($date, 'd-m-Y');
		                $fecha_pedido = $fecha;

		              $rowUser = $row['usuario'];
		              $rowid = $row['id'];
		              $rowiduser = $row['uid'];


		              $status_pedido = $row['status_pedido'];
		              $rowNombre = $row['nombre'];
		              $motivo = strip_tags((string) $row['motivo_rechazo']);

		              //botonera_usuario($rowNombre,$rowUser);

		        $query_detalle_pedidos = "SELECT SUM(monto) AS 'total_pedido', SUM(CASE WHEN id_pedido = '$rowid' THEN 1 ELSE 0 END) AS 'cant_tarjetas' FROM tarjetas WHERE id_pedido = '$rowid'";
		        $resultado_query = mysqli_query($db,$query_detalle_pedidos);
		        $rs = mysqli_num_rows($resultado_query);
		        while ($r = mysqli_fetch_assoc($resultado_query)){
		          $st = $r['total_pedido'];
		          $ct = $r['cant_tarjetas'];
		        }

		                if ($status_pedido == 'ESPERANDO') {
		                    $sp = '<div class="text-center w-70 mx-auto alert alert-warning" role="alert" data-toggle="popover" title="SIN ASIGNACION" data-content="Este pedido no posee asignacion de tarjetas.">
		                    ESPERA <i class="fa fa-clock"></i>
		                  </div>';
		                }
		                else if ($status_pedido == 'APROBADO') {
		                    $sp = '<form autocomplete="off" class="was-validated" method="post" action= "preparar_entrega_pedido.php?id='.$rowid.'&user='.$rowUser.'"><button type="submit" class="btn btn-warning btn-sm" name="enviar_pedido_btn">Enviar Pedido  <i class="fa fa-paper-plane"></i></button> </form>';
		                }
		                else if ($status_pedido == 'RECHAZADO') {
		                    $sp = '<div class="text-center w-70 mx-auto alert alert-danger" role="alert" data-html="true" data-toggle="popover" title="RECHAZADO" data-content="Su pago fue rechazado, por el siguiente motivo:<br> '.$motivo.'.">
		                    RECHAZADO  <i class="fa fa-exclamation-triangle"></i>
		                  </div>';
		                }
		                else if ($status_pedido == 'ENTREGADO') {
		                    $res = '<div class="text-center w-70 mx-auto alert alert-success" role="alert" data-toggle="popover" title="RESUMEN" data-content="En este pedido fueron entregados '.$st.' Bs. en  '.$ct.' Unidades de Tarjetas.">
		                    RESUMEN  <i class="fa fa-clipboard-list"></i>
		                  </div>';



		                  $bot = '<a class="btn btn-warning btn-sm" href="preparar_entrega_pedido.php?id='.$rowid.'&user='.$rowUser.'">ENVIAR MAS <i class="fa fa-paper-plane"></i></a>';



		                  $add = '<div class="btn-group-vertical" >'.$res. $bot . '</div>';
		                  //$add .= "<hr>";
		                    //$add .= $accion;
		                  $sp =  $add ;
		                }
		                $status_pedido = $sp;
		       echo '<tr>';
		       echo '<td>'.$row['id'] .'</td>
		       <td>'.$rowUser .'</td>
		       <td>'. $rowNombre .'</td>
		       <td>'. $fecha_pedido . ' / ' .$row['monto'].' Bs.</td>
		       <td>'.$row['nro_transf'] .' / '.$row['ci_nro_cuenta'] .'</td>
		       <td>'.$row['banco_emisor'] .' / '.$row['banco_destino'] .'</td>
		       <td>'.$status_pedido .'</td>

		       </tr>';
		               }
		                echo '</tbody></table>';

		 pag($ini, $limit_end, $total);
		}
		} else { echo "Error";}
		}
?>
<?php include("includes/head.php"); ?>
<div class="container">


<div class="row  mb-3">
<div class="col-xs-12 col-sm-6">


<h2>Lista Total de Pedidos Generados</h2>

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
<?php if (isset($_SESSION['lista_total_pedido'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['lista_total_pedido'];
						unset($_SESSION['lista_total_pedido']);
					?>
				</h3>
			</div>
<?php endif ?>
    <!-- Origen de Tabla -->

    <?php lista_total_pedidos(); ?>
<!-- Fin de Tabla -->

<script language='javascript'> $('.summernote').summernote({  placeholder: 'Ingrese su contenido',  tabsize: 2,  height: 200  });
</script>


<script>
     $('table').stacktable();
</script>

</div>
<?php include("includes/footer.php"); ?>
