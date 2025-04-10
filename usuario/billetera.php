<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Billetera";
$titulopag = "Billetera";
include('../funciones/functions.php');

?>

<?php include("includes/head.php"); ?>

<div class="container">

  <!-- notification message -->
  <?php if (isset($_SESSION['billetera_virtual'])) : ?>
  			<div class="alert alert-danger" role="alert" >
  				<h3>
  					<?php
  						echo $_SESSION['billetera_virtual'];
  						unset($_SESSION['billetera_virtual']);
  					?>
  				</h3>
  			</div>
  <?php endif ?>

<div class="row">

<div class="col-md-6">
<h1>Su Billetera Virtual</h1>
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


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ingrese dinero a su Billetera</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <?php
        echo ' Si desea conocer nuestras cuentas bancarias donde puede efectuar sus pagos puede ingresar en <a target="_blank" href="http://www.jesuministrosymas.com.ve/pagos#TOC-PAGOS-BANCARIOS-EN-VENEZUELA">VER CUENTAS BANCARIAS PARA PAGOS EN VENEZUELA</a>';
contenido('bancario');
echo ' <form autocomplete="off" class="was-validated" method="post" action= "'.strtolower ($operador) .'.php">';
//echo $status_usuario;



// Your date
$inicio = new DateTime(); // empty for now or pass any date string as param
//echo $inicio->format('Y-m-d');
//echo "<br>";
$hoy = date('d/m/Y');
// Adding

// Checking
//echo $fin->format('Y-m-d');
echo "<br>";

$user_id = $_SESSION['user']['id'];

echo '
<input type="hidden" name="concepto" id="concepto" value="'.$concepto.'">
<input type="hidden" name="user_id" id="user_id" value="'.$user_id.'">
';
echo '

<div class="form-group">
<label for="monto">Monto Pagado</label>
<input pattern="[0-9]{6,8}" min="100" max="999999999" title = "Indique el monto"  type="number" class="form-control" id="monto" name="monto" aria-describedby="monto" placeholder="Monto Transferido" name="monto" value="';
//echo $mmo;
echo '" required>
<div class="invalid-feedback">Debe indicar el monto transferido Minimo 100 Bs Maximo 999.999.999 Bs</div>
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
<label for="nro_transf">Numero de Transferencia</label>
<input pattern="[0-9]{8,15}" title = "Debe utilizar solo Numeros, Minimo 8 digitos y Maximo 15 digitos. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234"  type="text" class="form-control" id="nro_transf" aria-describedby="nro_transf" placeholder="Numero de Operacion Bancaria" name="nro_transf" value="';
echo $nro_transf;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de operacion bancaria indicada por su Banco. Si su banco solo le ha suministrado un numero de 4 digitos debe rellenar los espacios faltantes con el numero cero, ejemplo: 00001234</div>
</div>


<div class="form-group">
<label for="ci_nro_cuenta">Cedula del Titular de la Cuenta Origen</label>
<input  pattern="[0-9]{7,10}" title = "Debe utilizar solo Numeros, Minimo 7 digitos y Maximo 10 digitos"   type="text" class="form-control" id="ci_nro_cuenta" aria-describedby="ci_nro_cuenta" placeholder="Numero de Cedula Titular de la Cuenta Origen" name="ci_nro_cuenta" value="';
echo $ci_nro_cuenta;
echo '" required>
<div class="invalid-feedback">Debe indicar el numero de cedula del titular de la cuenta desde donde usted efectuo su transferencia.</div>
</div>

<div class="form-group">
<label for="fecha_transf">Fecha de su Transferencia</label>
<input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
echo $fecha_transf;
echo '" required>
<div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
</div>

<input type="hidden" name="user" value="'.$usua.'">
<input type="hidden" name="titulopag" value="'.$operador.'">

<button type="submit" class="btn btn-primary" id="pago_billetera_btn" name="pago_billetera_btn"> <i class="far fa-money-bill-alt"></i> Enviar</button>

';

         ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" name="pago_billetera_btn"> <i class="far fa-money-bill-alt"></i> Enviar</button></form>
        </div>
      </div>
    </div>
  </div>


  </div>
<h2>Tabla de Ingresos y Consumos</h2>

<?php
$query = "SELECT * FROM billetera  WHERE id_usuario = '$id_usua' AND monto != 0 ORDER BY `id` DESC";

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
   <th>Quedan</th>
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
    <th>Quedan</th>
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


<script type="text/javascript">

$(document).ready(function(){
     listar();
 });



var listar = function(){

	var table = $('#dt_billetera').DataTable({
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
rowCallback: function(row, data, index){
            if(data["descripcion"] == "INGRESO" || data["descripcion"] == "DEVOLUCION"){
                $('td', row).addClass('green');
            }
            else if(data["descripcion"] == "RECHAZADO"){
                $('td', row).addClass('orange');
            }
            else if(data["descripcion"] == "FACTOR DE RECONVERSION MONETARIA"){
                $('td', row).addClass('yellow');
            }
            else if(data["status"] == "ESPERANDO"){
                $('td', row).addClass('blue');
            }
            else {
                $('td', row).addClass('red');
            }
        },
    "orderCellsTop": true,
    "fixedHeader": true,
    //dom: 'Bfrtip',
    "language": idioma_espanol,
		"dom": '<"top"Bfp>lrt<"bottom"ip><"clear">',
    "buttons": [
        'copy', 'excel', 'pdf'
    ],
    "order": [[ 0, "desc" ]],
		"ajax": {
	 	"method":"POST",
	 	"url":"funciones/listar_b.php"
		 },
		"columns":[
			{"data":"id"},

			{

      "data":"fecha",
      "render": function ( data, type, row ) {
            return new Date(data).toLocaleDateString(
               'es-VE', // Ajusta tu idioma y país
               {
                   year: 'numeric',
                   month: 'long',
                   day: 'numeric',
                   hour: '2-digit',
                   minute:'2-digit'
               }
           );
      }
        },

			{
        "data":"monto",
        render: function(data, type) {
                      var number = $.fn.dataTable.render.number( ',', '.', 2, 'Bs '). display(data);

                      if (type === 'display') {
                          let color = 'green';
                          if (data < 0) {
                              color = 'red';
                          }
                          else if (data < -100) {
                              color = 'orange';
                          }

                          return '<span style="color:' + color + '">' + number + '</span>';
                      }

                      return number;

                  }
  },
			{
        "data":"historial",
        render: function(data, type) {
                      var number = $.fn.dataTable.render.number( ',', '.', 2, 'Bs '). display(data);

                      if (type === 'display') {
                          let color = 'green';
                          if (data < 0) {
                              color = 'red';
                          }
                          else if (data < -100) {
                              color = 'orange';
                          }

                          return '<span style="color:' + color + '">' + number + '</span>';
                      }

                      return number;

                  }
  },

			{"data": "descripcion"},
      {"data": "status"     }
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
