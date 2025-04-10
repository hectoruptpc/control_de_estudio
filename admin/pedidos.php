<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Pedidos";
	include('../funciones/functions.php');

		 //APROBAR PAGOS PEDIDO

		 function aprobar_pago_pedido() {
		 	 global $db, $logo, $fecha_act;

		 	$id = ($_REQUEST['id']);
		 	$usua = ($_REQUEST['user']);
		 	$operador = ($_REQUEST['operador']);
		 	//echo $idusuario;
		 	// if (isset($_GET['id']))
		 	// $idusuario=$_GET['id'];

		 	$query = "SELECT * FROM pedidos WHERE id = '$id' AND status_pedido != 'ENTREGADO' LIMIT 1";
		 	$result = mysqli_query($db, $query);
		 	$row =  mysqli_fetch_assoc($result);
		 	$status_pedido = $row['status_pedido'];
		 	$operador = $row['operador'];
		 	$monto = $row['monto'];
		 	$usuario = $row['usuario'];

		 if ($operador == 'Billetera') {
		 // code...
		 $valor_status = 'ENTREGADO';
		 } else {
		 // code...
		 $valor_status = 'APROBADO';
		 }

		 	if ($status_pedido == 'ESPERANDO') {

		 	$sql = "UPDATE pedidos SET
		  status_pedido = '$valor_status',
		  fecha_aprobacion = CURRENT_TIMESTAMP()
		  WHERE id = $id";

		 if (mysqli_query($db, $sql)) {
		 	$_SESSION['msn_pedidos']  = "Se ha Actualizado Pedidos Exitosamente..!!<br>";

		 	if ($operador == 'Billetera') {

		 		$sqla = "UPDATE users SET
		 	 monto_a_favor = monto_a_favor+'$monto',
		 	 act_monto = CURRENT_TIMESTAMP(),
		 	 disp_a_favor = 1
		 	 WHERE username = '$usuario'";
		 	 if (mysqli_query($db, $sqla)) {
		 			$_SESSION['msn_pedidos']  .= "Se ha Actualizado Users..!!<br>";
		 	}else {
		 	 $_SESSION['msn_pedidos']  .= "Ha ocurrido un Error Actualizando Users updating record: " . mysqli_error($db) . "<br>";
		 		//mysqli_close($db);
		  }

		  $sqlb = "UPDATE billetera SET
		 status = 1,
		 fecha = CURRENT_TIMESTAMP()
		 WHERE id_descripcion = $id";
		 if (mysqli_query($db, $sqlb)) {
		 	 $_SESSION['msn_pedidos']  .= "Se ha Actualizado Billetera..!!<br>";
// CONFIGURAR PARA ENVIAR CORREO AL ACTUALIZAR BILLETERA
		 //	$asunto = "Aprobado Dinero a Billetera";
		 //	$cuerpo = "Hola $nombre <br><br>Le informamos que su billetera ha sido actualizada de forma exitosa. ";

		 //  enviarEmail($email, $nombre, $asunto, $cuerpo);

		 }else {
		 $_SESSION['msn_pedidos']  .= "Ha ocurrido un Error Actualizando Billetera record: " . mysqli_error($db) . "<br>";
		  //mysqli_close($db);
		 }

		 }

		 }
		 else {
		 $_SESSION['msn_pedidos']  = "Ha ocurrido un Error Actualizando Pedidos updating record: " . mysqli_error($db) . "<br>";
		 //mysqli_close($db);
		 }
		 } // cierre if ($status_pedido == 'ESPERANDO')
		 }


$tabla_recargar = "";

	function tabla_recargar($a) {
		 global $db, $tabla_recargar;
			$query2 = "SELECT * FROM recargar WHERE relacion = '$a' ORDER BY id ASC";
		      $result2 = mysqli_query($db, $query2);
		     // $row2 =  mysqli_num_rows($result2);

		      $tabla_recargar = '
		<div class="table-responsive">
					<table id="tabla_recargar" class="table table-bordered table-hover ">
		<thead>
		<tr>
		<th>#</th>
		<th>id</th>
		<th>Tipo</th>
		<th>Numero</th>
		<th>Monto</th>
		<th>Status de Recarga</th>

		</tr>
		</thead>
		<tbody>';

		$i = 1;
		          while ($row2 = mysqli_fetch_assoc($result2))
		{
	  $id = $row2['id'] ;
		$tipo = $row2['tipo'] ;
		$numero = $row2['nro'] ;
		$monto = $row2['monto'] ;
		$status = $row2['status'] ;
		$confirmacion = $row2['confirmacion'] ;

		if ($status == 4){
		    $boton_status = '<div data-html="true" class= "mx-auto btn btn-sm btn-outline-danger" data-toggle="popover" title="Esperando ser Recargado" data-content="Algo ha ocurrido con el Numero <br> <b>'.$numero.'</b> aun no ha sido recargado, esperamos puedas solventar el inconveniente con esta recarga.">
		<i class="fa fa-clock"></i>
		</div>';

		  $status = $boton_status;
		}

		if ($status == 2){
		    $boton_status = '<div data-html="true" class= "mx-auto btn btn-sm btn-outline-info" data-toggle="popover" title="Esperando ser Recargado" data-content="El Numero <br> <b>'.$numero.'</b> aun no ha sido recargado.">
		<i class="fa fa-clock"></i>
		</div>';
		    //$status = "Esperado ser Recargado";
		    $status = $boton_status;
		}
		if ($status == 3){
		    $boton_status = '<div data-html="true" class= "mx-auto btn btn-sm btn-outline-success" data-toggle="popover" title="Recarga ya Efectuada" data-content="El Numero <br> <b>'.$numero.'</b> Ha sido recargado de manera exitosa.">
		<i class="fa fa-check-circle"></i>
		</div>';
		    $status = $boton_status. " Confirmacion N°: ".$confirmacion;
		}
		$tabla_recargar .= '<tr>';
		$tabla_recargar .= '<td>'.$i.'</td>';
		$tabla_recargar .= '<td>'.$id.'</td>';
		$tabla_recargar .= '<td>'.$tipo .'</td>';
		if ($tipo == "MOVISTAR TV" || $tipo == "DIRECTV") {
			$numero = $numero;
		} else {
			$numero = substr($numero, 1, 10);
		}
		$tabla_recargar .= '<td>'.$numero.'</td>';
		$tabla_recargar .= '<td>'. number_format($monto, 2, ',', '.') .' Bs.</td>';
		$tabla_recargar .= '<td>'.$status .'</td>';
		$tabla_recargar .= '</tr>';
		$i = $i +1;

		}

		$tabla_recargar .= '</tbody></table></div>';

		return $tabla_recargar;

	}

$modal_recarga = "";


	// LISTA PEDIDOS
	function lista_pedidos_admin(){
	  global $db, $username, $usua, $mes, $res, $accion, $img_ope, $operador, $dest, $tabla_recargar, $modal_recarga, $valor_divisa, $valor_cuenta_netflix;

	  $limit_end = 20;

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

	    //SENTENCIAS SQL SI ES ADMINISTRADOOR

	      if (empty($busqueda)) {
	          // SI LA VARIABLE BUSQUEDA ESTA VACIA
	      $busqueda = "";

	      $count="SELECT COUNT(*) FROM pedidos INNER JOIN users ON pedidos.usuario=users.idusuario WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO')";

	      $query = "SELECT pedidos.*, users.id AS uid, users.nombre, users.email, users.username, users.tlf, users.cel, SUM(CASE WHEN billetera.status = 1 THEN billetera.monto ELSE 0 END) AS Monto_a_favor
				FROM pedidos
				INNER JOIN users  ON pedidos.usuario=users.idusuario
				LEFT JOIN billetera ON billetera.id_usuario=users.id
				WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO' AND billetera.status = 1)
				GROUP BY id
				ORDER BY id DESC
				LIMIT $init, $limit_end";

	    $result = mysqli_query($db, $query);
	    $row =  mysqli_num_rows($result);
	    $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No hay Pedidos Pendientes.';

	    } else {
	        // SI EXISTE LA VARIABLE BUSQUEDA

	      $count="SELECT COUNT(*) FROM pedidos
	      INNER JOIN users ON pedidos.usuario=users.idusuario
	      WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') AND (usuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR status_pedido LIKE '%$busqueda%')";

	      $query = "SELECT pedidos.*, users.id AS uid, users.nombre, users.email, users.username, users.tlf, users.cel, SUM(CASE WHEN billetera.status = 1 THEN billetera.monto ELSE 0 END) AS Monto_a_favor
				FROM pedidos
				INNER JOIN users  ON pedidos.usuario=users.idusuario
				LEFT JOIN billetera ON billetera.id_usuario=users.id
				WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') AND billetera.status = 1 AND (usuario LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR email LIKE '%$busqueda%' OR nro_transf LIKE '%$busqueda%' OR ci_nro_cuenta LIKE '%$busqueda%' OR banco_emisor LIKE '%$busqueda%' OR banco_destino LIKE '%$busqueda%' OR status_pedido LIKE '%$busqueda%')
				GROUP BY id
				ORDER BY id DESC
				LIMIT $init, $limit_end";

	        $result = mysqli_query($db, $query);
	        $row =  mysqli_num_rows($result);
	        $mensaje  = '<i class="fa fa-exclamation-triangle"></i> No resultados con su criterio de busqueda';
	    }


	    if (!$row){

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

							echo '<div class="d-none d-sm-none d-md-block">';
							    pag($ini, $limit_end, $total);
							echo "</div>";
							echo '<div class="d-block d-sm-block d-md-none">';
							pag_test($ini, $limit_end, $total);
							echo "</div>";

	// ES ADMIN
	// SENTENCIA SQL PARA SUMAR Y DETERMINAR MONTOS Y CANTIDADES DE PEDIDOS EN ESPERA POR APROBACION Y EN ESPERA

	$suma="SELECT sum(monto) AS 'total',
	SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN 1 ELSE 0 END) AS 'esperando_aprobar',
	SUM(CASE WHEN status_pedido = 'ESPERANDO' THEN monto ELSE 0 END) AS 'esperando_aprobar_monto',
	SUM(CASE WHEN status_pedido = 'APROBADO' THEN 1 ELSE 0 END) AS 'esperando_entregar',
	SUM(CASE WHEN status_pedido = 'APROBADO' THEN monto ELSE 0 END) AS 'esperando_entregar_monto'
	FROM pedidos WHERE (status_pedido = 'ESPERANDO' OR status_pedido = 'APROBADO') ";
	$result_suma = mysqli_query($db, $suma);

	echo '<div class="row">';
	echo '<div class="col-6">';
	while ($row_suma = mysqli_fetch_assoc($result_suma))
	{
	$total_esp = $row_suma['esperando_aprobar'] + $row_suma['esperando_entregar'];

	echo 'Cantidad: '. $total_esp . ' Unid. Total: '.$row_suma['total'] . ' Bs.<br>';
	echo 'Esperando Aprobacion: '.$row_suma['esperando_aprobar'] . ' Unid. Total: '.$row_suma['esperando_aprobar_monto'] . ' Bs.<br>';
	echo 'Esperando Entrega: '.$row_suma['esperando_entregar'] . ' Unid. Total: '.$row_suma['esperando_entregar_monto'] . ' Bs.<br>';
	}
	echo '</div>';

	$cantidad_pedidos ="SELECT pedidos.monto AS 'monto_pedido',COUNT( pedidos.monto ) AS 'num'
	FROM pedidos INNER JOIN monto ON (pedidos.monto=monto.monto) WHERE status_pedido = 'APROBADO'
	GROUP BY monto.monto
	ORDER BY monto.monto ASC";
	$rcp = mysqli_query($db, $cantidad_pedidos);
	echo '<div class="col-6">';
	while ($rcdp = mysqli_fetch_assoc($rcp))
	{
	$mp =  $rcdp['monto_pedido'];
	$cp = $rcdp['num'];

	echo 'Se requieren: '.$cp . ' Unid. de: ' . $mp .' Bs.<br>';
	}
	echo '</div>';
	echo '</div>';

	// TABLA LISTA DE PEDIDOS ADMIN

	//echo '<div class="table-responsive">';

	echo '<div class="table-responsive">
	<table id="pedidos" class="table table-bordered table-hover">
	<thead>
	<tr>
	<th>id Pedido</th>
	<th>Id de Usuario / Nombre / Email / Datos</th>
	<th>Fecha de Transf</th>
	<th>Monto / Nro Transf / Cedula</th>
	<th>Emisor / Receptor / Operador</th>
	<th>Accion</th>
	</tr>
	</thead>
	<tbody>';

	$c = $db->query($query);
	while($row = $c->fetch_array(MYSQLI_ASSOC))
	{

	$date = date_create($row['fecha_transf']);
	$fecha = date_format($date, 'd-m-Y h:i:s A');
	$fecha_pedido = $fecha;

	$rowUser = $row['usuario'];
	$rowUserid = $row['uid'];
	$rowid = $row['id'];
	$rowNombre = $row['nombre'];
	$rowEmail = $row['email'];
	$cel = $row['cel'];
	$tlf = $row['tlf'];
	$status_pedido = $row['status_pedido'];
	$destino = $row['banco_destino'];
	$operador = $row['operador'];

	$monto = $row['monto'];
	$a_favor = $row['Monto_a_favor'];

	//if ($a_favor >0) {
	//	$a_favor = $row['a_favor'];
	//	$monto_asignar = $row['monto']+$a_favor;
	//} else {
	//	$a_favor = 0;
		$monto_asignar = $row['monto'];
	//}

	//$monto = number_format($monto, 2, ',', '.');
	$sin_plan = $row['sin_plan'];

	if ($sin_plan == 1)
	//&& ($operador !== 'Movilnet'))
	 {
	  $sin_plan = 'Sin Plan';
	  //$monto_A = $monto + ($monto*30/100);
	  $monto_A = $monto;
		$monto_asignar = $monto_asignar-$a_favor;
	}
	else if ($sin_plan == 0) {
	  $sin_plan = 'Plan Activo';
	  $monto_A = $monto;
	}
	else if ($sin_plan == 2) {
	  $sin_plan = 'Billetera';
	  $monto_A = $monto;
	}

	img_ope($operador);
	selector_bancario($destino);

	analisis_pedidos_por_cliente($rowUser);
	$resumen = $res;

	$query_entregas = "SELECT sum(monto) AS 'total' FROM tarjetas WHERE id_pedido = $rowid";
	$q_e = $db->query($query_entregas);
	while($row2 = $q_e->fetch_array(MYSQLI_ASSOC))
	{
	$total_asig = $row2['total'];

	if ($total_asig== 0) {
	$resumen .='<br>No tiene Asignacion';
	} else {



	$resumen .='<br> Asignado <b>'.number_format($total_asig, 2, ',', '.') .' Bs.</b>';
	//$resumen .= 'PRUEBA '.$total_asig .' '.$monto;
	//ACTUALIZAR STATUS DE TARJETA
	if ($total_asig == $monto ) {
		$sqlact = "UPDATE pedidos SET
	 status_pedido = 'ENTREGADO',
	 fecha_aprobacion = CURRENT_TIMESTAMP()
	 WHERE id = $rowid";

 if (mysqli_query($db, $sqlact)) {
		$_SESSION['msn_pedidos']  = "Se ha Actualizado el usuario de manera correcta..!!<br>";
		header("location: pedidos.php");
		//exit();

 }
	}

	}
	}
	$rowiduser = $row['uid'];

	$rechazar = '<a href= "rechazar.php?id='.$rowid.'&user='.$rowUser.'&asunto=pedido" type="submit" class="btn btn-danger btn-sm btn-block" name="rechazar_pago_pedido_btn" data-html="true" data-toggle="popover" title="Rechazar Pago" data-content="Aca podra rechazar el pago de este pedido y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Rechazar  <i class="fa fa-times-circle"></i></a></form>';

	if ($status_pedido == "ESPERANDO") {

	// PEDIDOS FUNCIONAL

	$aprobar = '<form autocomplete="off" class="was-validated" method="post" action= "">

	<input type="hidden" name="id" value="'.$rowid.'">
	<input type="hidden" name="user" value="'.$rowUser.'">
	<input type="hidden" name="operador" value="'.$operador.'">

	<button type="submit" class="btn btn-success btn-sm btn-block" name="aprobar_pago_pedido_btn" data-html="true" data-toggle="popover" title="Aprobar Pago" data-content="Aca podra aprobar el pago de este pedido y notificar a <b>'.$rowNombre.'</b> con un correo electronico.">Aprobar <i class="fa fa-check-circle"></i></button> ';




	$link = '<div class="btn-group-vertical" role="group" >'. $aprobar . $rechazar . $resumen.'</div>';



	//$link .= $accion;

	}
	else if ($status_pedido == "APROBADO") {
	// REALIZAR RECARGAS OTRAS OPERADORAS

	$link = '<!-- Extra large modal -->
	<div class="btn-group-vertical" role="group" ><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#recarga'.$rowid.'">Realizar Recarga</button><br>'.$rechazar.'</div>
';

$modal_recarga= '
	<!-- Modal -->
	<div class="modal fade bd-example-modal-xl" id="recarga'.$rowid.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Efectuar Recargas</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body"><h6>'.$fecha_pedido.
	      $rowUser.'-'.
	      $rowid.'</h6><br>'.$operador.'<br>';

				if ($operador == 'Digitel') {
				$modal_recarga .= '<p><b>ID DIGITEL:</b> 2812516090319330</p>';
				}
				if ($operador == 'Movistar') {
				$modal_recarga .= '<p><b>ID MOVISTAR:</b> UTILICE SOLO LOS ULTIMOS 4 DIGITOS DEL NUMERO DE CONFIRMACION</p>';
				$modal_recarga .= '<p>En Caso de Error Usar: Esperando_Operador</p>';
				}
				if ($operador == 'Movilnet') {
				$modal_recarga .= '<p>Esperando_Operador</p>';
				}

				// PONER CONDICIONAL PARA ID POR OPERADOR

				tabla_recargar($rowid);

	$modal_recarga .= $tabla_recargar;

	$modal_recarga .= '<form autocomplete="off" class="was-validated" method="post" action= "">


	<div class="row row-cols-1">

	<input id="confirmacion" type="text" class="form-control col" aria-describedby="confirmacion" placeholder="Nro de Confirmacion" name="confirmacion" value="" required>
	<div class="input-group-append col">
	  <input class="input-group-text" id="finalcount" value="0" disabled />
	</div>
	  <div class="invalid-feedback">Debe indicar el o los numeros de confirmacion de las recargas</div>
	</div>

	<input type="hidden" name="id_pedido" value="'.$rowid.'">

	<button type="submit" class="btn btn-success" name="confirmaciones_btn">Enviar</button>


	';

	     $modal_recarga .= '</div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="btn btn-success" name="confirmaciones_btn">Enviar</button>
	      </form></div>
	    </div>
	  </div>
	</div>';


	$link .= $modal_recarga;
	}


	echo '<tr>';
	echo '
	<td>'.$row['id'] .'</td>
	<td>'.$rowUserid.'<br><a href="./usuarios.php?busqueda='.$rowUser .'" target="_blank">'.$rowUser .'</a><br>'. $rowNombre .'<br>'.$rowEmail.'<br>'.$tlf.'<br>'.$cel.'</td>
	<td>'.$fecha_pedido .'</td>
	<td><span data-toggle="popover" data-content="Monto que se debe confirmar con el banco."><b>Transf: '.number_format($monto_A, 2, ',', '.').' Bs</b></span><br><span data-toggle="popover" data-content="Monto que tiene el usuario a favor.">A Favor: '.number_format($a_favor, 2, ',', '.').' Bs<br></span>'.$row['nro_transf'] .'<br>'.$row['ci_nro_cuenta'] .'</td>
	<td>'.$row['banco_emisor'] .' / '.$dest .'<br>'.$img_ope.'<br>'.$sin_plan.'</td>
	<td>'. $link .'</td>
	</tr>';


	}
	echo '</tbody></table></div>';



	echo '<div class="d-none d-sm-none d-md-block">';
  pag($ini, $limit_end, $total);
	echo "</div>";
	echo '<div class="d-block d-sm-block d-md-none">';
	pag_test($ini, $limit_end, $total);
	echo "</div>";
	}


	}





?>

<?php include("includes/head.php"); ?>

<script>
$(function() {
	var wordCounts = {};
	$("input[type='text']:not(:disabled)").each(function() {
				 var input = '#' + this.id;
				 word_count(input);

				 $(this).keyup(function() {
						 word_count(input);
				 })

		 });

		 function word_count(field) {
					 var number = 0;
					 var matches = $(field).val().match(/\b/g);
					 if (matches) {
							 number = matches.length / 2;
					 }
					 wordCounts[field] = number;
					 var finalCount = 0;
					 $.each(wordCounts, function(k, v) {
							 finalCount += v;
					 });
					 $('#finalcount').val(finalCount)
			 }
	 });
</script>

<div class="container">

<?php
echo "<hr>";
contenido('bancario');
contenido('mens_movilnet');
contenido('mens_movistar');
contenido('mens_digitel');
contenido('mens_inter');
contenido('mens_directv');
contenido('mens_netflix');
echo "<hr>";

 ?>

    <?php  if (isset($_REQUEST['busqueda'])) {
        $busqueda = strtolower($_REQUEST['busqueda']);
    } else {
        $busqueda = "";
    } ?>
        <div class="row  mb-3">
            <div class="col-xs-12 col-sm-6">
            <h2>Pedidos Generados</h2>
            </div>

            <div class="col-xs-12 col-sm-6">
                <form action="pedidos.php" method="get">
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
        <?php if (isset($_SESSION['msn_pedidos'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <h3>
                            <?php
                                echo $_SESSION['msn_pedidos'];
                                unset($_SESSION['msn_pedidos']);
                            ?>
                        </h3>
                    </div>
        <?php endif ?>
            <!-- Origen de Tabla -->
            <?php lista_pedidos_admin(); ?>


<!-- Fin de Tabla -->
<script language='javascript'>
	$('.summernote').summernote({
	placeholder: 'Ingrese su contenido',
	tabsize: 2,
	height: 200
});
</script>

<!-- <script>
  $('.table').stacktable();
</script> -->







</div>

<?php include("includes/footer.php"); ?>
