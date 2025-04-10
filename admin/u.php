<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Gestion de Usuarios";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>

<div class="container">

<!-- notification message -->
<?php if (isset($_SESSION['usuario'])) : ?>
    <div class="alert alert-danger" role="alert" >
        <h3>
            <?php
                echo $_SESSION['usuario'];
                unset($_SESSION['usuario']);
            ?>
        </h3>
    </div>
<?php endif ?>

<div class="row">
<div class="col-md-6">
<h1>USUARIOS</h1>
<?php echo $disp; ?>
</div>

<div class="col-md-6 d-flex justify-content-center align-items-center">
  <div class="d-flex justify-content-center">
    <!-- Button trigger modal -->
  <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#exampleModal">
    <i class="fas fa-wallet"></i> Agregar Dinero a Billetera
  </button>
</div>
</div>
</div>


<h2>Tabla de Usuarios</h2>

<?php
$query = "SELECT * FROM users ";

$result = mysqli_query($db, $query);
$row =  mysqli_num_rows($result);
 ?>


<?php if ($row > 0) :	?>
<div class="table-responsive">
<div class="container mt-4">
<table id="dt_usuarios" class="table table-bordered table-hover">
<thead>
 <tr>
   <th>ID</th>
   <th>IdUsuario</th>
   <th>Nombre</th>
   <th>Email</th>
   <th>Telefono</th>
   <th>Status</th>
   <th>Accion</th>
 </tr>
  </thead>
  <tbody>
  </tbody>

  <tfoot>
   <tr>
   <th>ID</th>
   <th>IdUsuario</th>
   <th>Nombre</th>
   <th>Email</th>
   <th>Telefono</th>
   <th>Status</th>
      <th>Accion</th>
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



<script type="text/javascript">

$(document).ready(function(){
     listar();
 });



var listar = function(){

	var table = $('#dt_usuarios').DataTable({
    initComplete: function () {
    this.api().columns().every( function () {
        var column = this;
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(column.footer()).empty() )
            .on( 'change', function () {
                var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                );

                column
                    .search( val ? '^'+val+'$' : '', true, false )
                    .draw();
            } );

        column.data().unique().sort().each( function ( d, j ) {
            select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
    } );
},
    "orderCellsTop": true,
    "fixedHeader": false,
    //dom: 'Bfrtip',
    "language": idioma_espanol,
    "dom": '<"top"Bfp>lrt<"bottom"ip><"clear">',
    "buttons": [
        'copy', 'excel', 'pdf'
    ],
    "order": [[ 0, "desc" ]],
		"ajax": {
	 	"method":"POST",
	 	"url":"funciones/listar_u.php"
		 },
		"columns":[
			{"data":"id"},
			{"data": "idusuario"},
            {"data": "nombre"},
            {"data": "email"},
            {"data": "tlf"},
            {"data": "status"},
            {"data": "fecha_ingreso"}
		],

		drawCallback: function () {
				$('[data-toggle="popover"]').popover({
						"html": true,
						trigger: 'hover',
						placement: 'auto'

				})
		}

	});

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
