<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Netflix";
$titulopag = "Netflix";
include('../funciones/functions.php');

selector_operador();

// LISTAR BANCO EMISOR
function cantidad_suscripciones(){
	global $db, $valor_divisa, $valor_cuenta_netflix;
	$query = "SELECT * FROM cantidad_suscripciones ORDER BY cantidad_suscripciones";
	$results = mysqli_query($db, $query);

	while ($valores = mysqli_fetch_array($results)) {

if ($valores['cantidad_suscripciones']==1) {
	$def = 'Cuenta Netflix';
}
else {
	$def = 'Cuentas Netflix';
}

$a_pagar = $valores['cantidad_suscripciones'] * $valor_divisa * $valor_cuenta_netflix;
$a_pagar_s = $a_pagar;
$a_pagar = number_format($a_pagar, 2, ',', '.');

		echo '<option value="'.$a_pagar_s.'">Pagando '. $a_pagar .' Bs. Solicita activar '.$valores['cantidad_suscripciones'].' '.$def.' </option>';
	}
}


function pedido_netflix() {
	global $db, $username, $usua, $ci_nro_cuenta, $monto, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $status_pedido, $fecha_pedido, $status_pago, $fecha_aprobacion,$mes_de_pago_actual, $debe_pagar, $operador, $modal_usuario_bloqueado, $monto_favor,
$mens_monto_favor, $nombrepag, $operador;

  echo '<h1>'.$operador.'</h1>';

	  if (isActive()){
			// echo 'ESTA ACTIVO';
			// echo $nombrepag;
			$query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND status_pedido IN('ESPERANDO','APROBADO') AND operador = '$operador' ";
		$result = mysqli_query($db, $query);
		$rows =  mysqli_num_rows($result);
if ($rows > 0){

	echo '<div class="alert alert-danger" role="alert"" >
				<h3>
		LO SENTIMOS, USTED POSEE UN PEDIDO EN ESPERA, DEBE ESPERAR SEA DESPACHADO SU PEDIDO PARA PODER EFECTUAR UN NUEVO PEDIDO.
				</h3>
			</div>';


}
else {

	    a_favor();
	    echo $mens_monto_favor;
	    $monto_favor = $GLOBALS['monto_a_favor'];

	        echo ' Si desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en <a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA">VER CUENTAS BANCARIAS PARA PAGOS EN VENEZUELA</a>';

	echo ' <form autocomplete="off" class="was-validated" method="post" action= "'.$nombrepag.'">';

	echo '<input type="hidden" name="operador" value="'.$operador.'">';

	echo '

	<div class="form-group">
	<label for="cantidad_suscripciones">Monto y Cantidad de Suscripciones.</label>
	<select class="custom-select" id="monto" name="monto" value="';
	echo $monto;
	echo '" required >
	<option value="">Seleccione:</option>';
	cantidad_suscripciones();

	$cantidad = $valores['cantidad_suscripciones'];
	echo '<input type="hidden" name="cantidad" value="'.$cantidad.'">';

	echo '</select> <label for="cantidad_suscripciones">Cada cuenta que solicite son 4 pantallas y 4K.</label><div class="invalid-feedback">Debe Seleccionar el monto y cantidad de suscripciones requeridas.</div>

	</div>



	<div class="form-group">
	<label for="banco_emisor">Desde Que banco Transfirio</label>
	<select class="custom-select" id="banco_emisor" name="banco_emisor" value="';
	echo $banco_emisor;
	echo '" required >
	<option value="">Seleccione:</option>';
	banco_emisor();
	echo '</select> <div class="invalid-feedback">Debe Seleccionar desde que banco efectuo su transferencia.</div>
	</div>

	<div class="form-group">
	<label for="banco_destino">A que Banco Transfirio</label>
	<select class="custom-select" id="banco_destino" name="banco_destino" value="';
	echo $banco_destino;
	echo '" required >
	<option value="">Seleccione:</option>';
	banco_destino();
	echo '</select>
	<div class="invalid-feedback">Debe Seleccionar a que banco usted efectuo su transferencia.</div>
	</div>

	<div class="form-group">
	<label for="nroTransf">Numero de Transferencia</label>
	<input  pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
	echo $nro_transf;
	echo '" required>
	<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
	</div>

	<div class="form-group">
	<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
	<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"  type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
	echo $ci_nro_cuenta;
	echo '" required>
	<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
	</div>

	<div class="form-group">
	<label for="fechaTransf">Fecha de su Transferencia</label>
	<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
	echo $fecha_transf;
	echo '" required>
	<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
	</div>

	<input type="hidden" name="user" value="'.$usua.'">

	<input type="hidden" name="sin_plan" value="0">

	<button type="submit" class="btn btn-primary" name="pedido_btn">Enviar</button>

	</form>';
}

} else {

	echo $modal_usuario_bloqueado;

}
}


?>
<?php include("includes/head.php"); ?>

<div class="container">

	<?php
contenido('mens_netfix');
	 ?>

	<!-- notification message -->
	<?php if (isset($_SESSION['netflix'])) : ?>
				<div class="alert alert-danger" role="alert" >
					<h3>
						<?php
							echo $_SESSION['netflix'];
							unset($_SESSION['netflix']);
						?>
					</h3>
				</div>
	<?php endif ?>

	<!-- Button DECLARAR PAGO -->
	<span class="d-inline-block" data-toggle="popover" data-content="Aca podrá efectuar sus pagos de pedidos de cuentas NETFLIX">
	<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#generarPedido"><span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
	<i class="fa fa-cart-arrow-down"></i>  Declarar Pago de Pedido <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
	</button>
</span>

	<?php

	$query = "SELECT * FROM pedidos  WHERE usuario = '$usua' AND operador = '$operador' ORDER BY `pedidos`.`fecha_pedido` DESC";

	$result = mysqli_query($db, $query);
	$row =  mysqli_num_rows($result);
	//echo $row; ?>

	<?php if ($row > 0) :	?>
	<div class="container mt-4">
	<table id="dt_pedidos" class="table table-bordered table-hover">
	<thead>
	 <tr>
		<th>ID Pedido</th>
		<th>Fechas <br>P = Pedido <br>A = Aprobacion <br>E = Entrega</th>
		<th>Monto</th>
			<th>Banco Emisor / Banco Destino / Nro. de Transf.</th>
		<!-- <th>Nro. de Transferencia</th> -->
		<th width ="150px">Status</th>
	 </tr>
		</thead>
		<tbody>
		</tbody>

		<tfoot>
		 <tr>
			<th>ID Pedido</th>
			<th>Fechas <br>P = Pedido <br>A = Aprobacion <br>E = Entrega</th>
			<th>Monto</th>
			<th>Banco Emisor / Banco Destino / Nro. de Transf.</th>
			<!-- <th>Nro. de Transferencia</th> -->
			<th>Status</th>
		 </tr>
	 </tfoot>

	 </table>
	</div>
	<?php else :  ?>
<hr>
		<?php planes_netflix(); ?>


	<div class="alert alert-danger mt-5" role="alert" >
	<h3 class="text-uppercase">
	<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
	</h3>
	</div>

		<?php  endif ?>
	</div>




	<!-- Modal Generar Pedido -->
	<div class="modal fade" id="generarPedido" tabindex="-1" role="dialog" aria-labelledby="generarPedidoLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="generarPedidoLabel">Generar Pedido</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body">


<?php pedido_netflix(); ?>


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





<?php include("includes/footer.php"); ?>



<script>
$(document).ready(function() {
     listar();
 });

var listar = function(){
	var table = $('#dt_pedidos').DataTable({

    //"bServerSide": true,
		//"sAjaxSource": "funciones/listar_netflix.php",
    //"sServerMethod": "POST",
		"dom": '<"top"pl>rt<"bottom"ip><"clear">',
		//"pagingType": "full_numbers",
    "order": [[ 1, "desc" ]],
		"language": idioma_espanol,
		 "ajax": {
		 	"method":"POST",
		 	"url":"funciones/listar_netflix.php"
		 },
		"columns":[
			{"data":"id"},

			{
            "data":"fecha_pedido",
            "render": function ( data, type, row ) {
                if(type === 'display'){
                    return "<span class='d-inline-block' data-toggle='popover' data-content='Fecha de Pedido'>P: " +row.fecha_pedido + "</span> <br><span class='d-inline-block' data-toggle='popover' data-content='Fecha de Aprobacion'>A: " + row.fecha_aprobacion + " </span><br><span class='d-inline-block' data-toggle='popover' data-content='Fecha de Entrega'> E: " + row.fecha_entrega+"</span>";
                }else{
                    return data;
                }
            }
        },
			{"data":"monto", render: function ( data, type, row ) {
	        return data +' Bs';
	    }},

			{
            "data": "banco_emisor",
            "render": function ( data, type, row ) {
                if(type === 'display'){
                    return row.banco_emisor + " <br>" + row.banco_destino + " <br>" + row.nro_transf;
                }else{
                    return data;
                }
            }
        },

			//{"data":"banco_emisor"},

		//	{"data":"nro_transf"},

			{"data":"status_pedido"}

		],

		drawCallback: function () {
				$('[data-toggle="popover"]').popover({
						"html": true,
						trigger: 'hover',
						placement: 'auto',

				})
		}

	});
	//console.log(table);
}


var idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}

</script>
