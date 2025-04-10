<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Billetera";
$titulopag = "Billetera";
include('../funciones/functions.php');
//include('../funciones/billetera.php');

?>

<?php include("includes/head.php"); ?>

<div class="container">


<h1>Su Billetera Virtual</h1>

  <?php echo $disp; ?>

<h2>Tabla de Ingresos y Consumos</h2>

<?php
$query = "SELECT * FROM billetera  WHERE id_usuario = '$id_usua' ORDER BY `id` DESC";

$result = mysqli_query($db, $query);
$row =  mysqli_num_rows($result);
//echo $row; ?>


<?php if ($row > 0) :	?>
<div class="table-responsive">
<div class="container mt-4">
<table id="dt_billetera" class="table table-bordered table-hover">
<thead>
 <tr>
   <th>ID</th>
   <th>Fecha</th>
   <th>Monto</th>
   <th>Descripcion</th>
   <th>Status</th>
 </tr>
  </thead>
  <tbody>
  </tbody>

  <tfoot>
   <tr>
    <th>ID</th>
    <th>Fecha</th>
    <th>Monto</th>
    <th>Descripcion</th>
    <th>Status</th>
    <!-- <th>Nro. de Transferencia</th> -->
   </tr>
 </tfoot>

 </table>
</div>
</div>
<?php else :  ?>


<div class="alert alert-danger mt-5" role="alert" >
<h3 class="text-uppercase">
<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
</h3>
</div>

  <?php  endif ?>
</div>




</div>
<?php include("includes/footer.php"); ?>



<script>
$(document).ready(function() {
     listar();
 });

var listar = function(){
	var table = $('#dt_billetera').DataTable({

    //"bServerSide": true,
		//"sAjaxSource": "funciones/listar_t.php",
    //"sServerMethod": "POST",
		"dom": '<"top"pl>rt<"bottom"ip><"clear">',
		//"pagingType": "full_numbers",
    "order": [[ 1, "desc" ]],
		"language": idioma_espanol,
		 "ajax": {
		 	"method":"POST",
		 	"url":"funciones/listar_b.php"
		 },
		"columns":[
			{"data":"id"},

			{
            "data":"fecha",
            "render": function ( data, type, row ) {

                    return data;
                }
        },
			{"data":"monto", render: function ( data, type, row ) {
	        return data +' Bs';
	    }},

			{"data": "descripcion"},
      {"data": "status"}

			//{"data":"banco_emisor"},

		//	{"data":"nro_transf"},

	//		{"data":"id_descripcion"}

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
