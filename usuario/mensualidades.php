<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "General";
$titulopag = "Mensualidades";
include('../funciones/functions.php');
$orig = $titulopag;

  //PARA EL MODAL DE PAGO DE MENSUALIDAD GENERAL
  function pago_mensualidad_operadores(){
    global $db, $usua, $ci_nro_cuenta, $nro_transf, $banco_emisor, $banco_destino, $fecha_transf, $mes_de_pago_actual, $mmo, $concepto, $operador, $monto_favor, $cuentas_bancarias, $orig, $mt;

    a_favor();

    $mt = $mmo - $monto_favor;
    $mt = number_format($mt, 2, ',', '.');
    $mcf = number_format($mmo, 2, ',', '.');
    $mf = number_format($monto_favor, 2, ',', '.');

    // Validación de la consulta
    $queryvpm = "SELECT * FROM `pagos` WHERE user = '$usua' AND DATEDIFF(fin, NOW()) > 0 AND status_pago ='APROBADO' ORDER BY id DESC LIMIT 4 ";
    $resultvpm = mysqli_query($db, $queryvpm);

    if (!$resultvpm) {
        echo "Error en la consulta: " . mysqli_error($db);
        return;
    }

    // Verificar si hay al menos 4 pagos aprobados
    $pagos_aprobados = 0;
    while ($row = mysqli_fetch_assoc($resultvpm)) {
        $pagos_aprobados++;
        if ($row['status_pago'] == 'APROBADO') {
            // Mostrar mensaje de pago aprobado
            echo '<div class="alert alert-info" role="alert"" >
                    <h3>YA USTED EFECTUO EL PAGO DEL PERIODO <b>' . strtoupper($mes_de_pago_actual) . '</b> Y YA PUEDE ACCEDER A LOS MODULOS DEL SISTEMA</h3>
                  </div>';
            return; // Finalizar la función si ya se encontró un pago aprobado
        }
    }

      // Si no hay 4 pagos aprobados
      if ($pagos_aprobados < 4) {
        // Calcular el monto restante
        $monto_restante = $monto_favor - $mmo;
        if ($monto_restante >= 1) {
          // Si el monto restante es mayor o igual a 1
         
                    // code...
                  //echo "billetera 100 - 35 = 65";
                  $banco_emisor ='Interno';
                  $banco_destino ='Interno';
                  $nro_transf = 'ICM_'.generar_cadena(20);
                  $ci_nro_cuenta ='Interno';
                  $fecha_transf = date("Y-m-d H:i:s");


                     echo $cuentas_bancarias;
                     contenido('bancario');
                     echo ' <form autocomplete="off" class="was-validated" method="post" action= "'.strtolower ($orig) .'.php">';
                     //echo $status_usuario;
                     // Your date
                     $inicio = new DateTime(); // empty for now or pass any date string as param
                     //echo $inicio->format('Y-m-d');
                     //echo "<br>";
                     $hoy = date('d/m/Y');
                     // Adding
                     // or even easier
                     $fin = $inicio->modify('+1 month');
                     $fin = $fin->format('d/m/Y');
                     // Checking
                     //echo $fin->format('Y-m-d');
                     echo "<br>";
                     
                     echo '<div class="form-group">
                     <label for="monto_mensualidad"><h5>Monto a pagar</h5>El monto a pagar para poder activar el servicio de uso de la plataforma '.$operador.' es de: <b>'. $mcf .' Bs.</b><br>
                     En su Billetera tiene: '.$mf.' Bs<br>
                     El su Billetera Quedarian: '.number_format($monto_restante, 2, ',', '.').'<br>
                     </label>


                     </div>';
                     echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy .'</b><br>Su renta venceria el <b>'. $fin .'</b><br>Tome en cuenta que todas las operadoras estaran disponibles hasta esa fecha.
                     </div>';
                     echo '
                     <input type="hidden" name="monto" value="'.$mmo.'">
                     <input type="hidden" name="concepto" value="'.$concepto.'">
                     <input type="hidden" name="inicio" value="'.$hoy.'">
                     <input type="hidden" name="fin" value="'.$fin.'">
                     <input type="hidden" name="banco_emisor" value="'.$banco_emisor.'">
                     <input type="hidden" name="banco_destino" value="'.$banco_destino.'">
                     <input type="hidden" name="nro_transf" value="'.$nro_transf.'">
                     <input type="hidden" name="ci_nro_cuenta" value="'.$ci_nro_cuenta.'">
                     <input type="hidden" name="fecha_transf" value="'.$fecha_transf.'">
                     <input type="hidden" name="user" value="'.$usua.'">
                     <input type="hidden" name="titulopag" value="'.$operador.'">
                     <input type="hidden" name="modelopago" value="1">
                     <button type="submit" class="btn btn-primary" name="pago_mensualidad_operadoras_btn">Enviar</button>
                     </form>';
                     // MODELO DE PAGO modelopago 1
                     // ES QUE SE DESCONTO DE LA BILLETERA EL CONCEPTO DE PAGO
      } else {
          // Si el monto restante es menor a 1
         
                    // code...

                  // ESTRUCTURA DEL MODAL CUANDO FALTA POR PAGAR
                  // SE DESCONTARA TODO EL DINERO DE BILLETERA Y SE DEBE ABONAR MAS DINERO VIA TRANSFERENCIA
                  // echo $mens_monto_favor; ESTO DA EL VALOR A FAVOR
                  echo $cuentas_bancarias;
                  contenido('bancario');
                  echo ' <form autocomplete="off" class="was-validated" method="post" action= "'.strtolower ($orig) .'.php">';
                  //echo $status_usuario;
                  // Your date
                  $inicio = new DateTime(); // empty for now or pass any date string as param
                  //echo $inicio->format('Y-m-d');
                  //echo "<br>";
                  $hoy = date('d/m/Y');
                  // Adding
                  // or even easier
                  $fin = $inicio->modify('+1 month');
                  $fin = $fin->format('d/m/Y');
                  // Checking
                  //echo $fin->format('Y-m-d');
                  echo "<br>";
                  
                  echo '<div class="form-group">
                  <label for="monto_mensualidad"><h5>Monto a pagar</h5>El monto a pagar para poder activar el servicio '.$operador.' es de: <b>'. $mcf .' Bs.</b> de su billetera se descontaran <b>'.$mf.' Bs</b> su pago debe ser por la cantidad de <b>'.$mt.' Bs</b></label>
                  </div>';
                  echo '<div class="alert alert-warning" role="alert"><h5>Vigencia de su Plan '.$operador.'</h5>Por ejemplo:<br>Aprobandose su pago hoy: <b>'. $hoy .'</b><br>Su renta venceria el <b>'. $fin .'</b><br>
                  Podra utilizar toda la plataforma durante todo ese tiempo.
                  </div>';
                  echo '
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
                  <label for="fechaTransf">Fecha de su Transferencia</label>
                  <input pattern="(?: 30)) | (? :(? : 0 [13578] | 1 [02]) - 31)) / (? :(?: 0 [1-9] | 1 [0-2]) - (?: 0 [1-9] | 1 [0 -9] | 2 [0-9]) | (? :( ?! 02) (?: 0 [1-9] | 1 [0-2]) / (?: 19 | 20) [0-9] {2}" title = "Debe utilizar el formato DD/MM/YYYY" type="date" class="form-control" id="fecha_transf" aria-describedby="fecha_transf" placeholder="Numero de Operacion Bancaria" name ="fecha_transf" value="';
                  echo $fecha_transf;
                  echo '" required>
                  <div class="invalid-feedback">Debe Seleccione la fecha en que usted efectuo su transferencia.</div>
                  </div>
                  <input type="hidden" name="monto" value="'.$mt.'">
                   <input type="hidden" name="concepto" value="'.$concepto.'">
                   <input type="hidden" name="inicio" value="'.$hoy.'">
                  <input type="hidden" name="fin" value="'.$fin.'">
                  <input type="hidden" name="user" value="'.$usua.'">
                  <input type="hidden" name="titulopag" value="'.$operador.'">
                  <input type="hidden" name="modelopago" value="2">

                  <button type="submit" class="btn btn-primary" name="pago_mensualidad_operadoras_btn">Enviar</button>
                  </form>';
                  // MODELO DE PAGO modelopago 2
                  // ES QUE SE DESCONTO DE LA BILLETERA PARTE DEL PAGO Y HAY QUE APROBAR PAGO BANCARIO
      }
  }
}





?>
<?php include("includes/head.php"); ?>

<div class="container">


<?php
//contenido('mens_'.strtolower ($operador));
//analizar_mensualidad_total();
// Button trigger modal
 echo   $registrar_mensualidad; ?>
<!-- FIN GENERAR PAGO -->



<!-- notification message -->
<?php if (isset($_SESSION['pago_mensualidad'])) : ?>
			<div class="alert alert-danger" role="alert">
				<h3>
					<?php
						echo $_SESSION['pago_mensualidad'];
						unset($_SESSION['pago_mensualidad']);
?>
				</h3>
			</div>
<?php endif ?>



<!-- Modal Generar Pedido -->
<div class="modal fade" id="pago_mensualidad" tabindex="-1" role="dialog" aria-labelledby="pago_mensualidadLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="generarPedidoLabel">Declarar Pago de Mensualidad</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">



<?php pago_mensualidad_operadores(); ?>



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


--------

<h1>SISTEMA CENTRALIZADO DE MENSUALIDADES</h1>

<h2>Tabla general de Pago de Mensualidades</h2>



<?php
$query = "SELECT * FROM pagos  WHERE user = '$usua' ORDER BY `id` DESC";

$result = mysqli_query($db, $query);
$row =  mysqli_num_rows($result);
//echo $row; ?>


<?php if ($row > 0) :	?>
<div class="table-responsive">
<div class="container mt-4">
<table id="dt_mensualidades" class="table table-bordered table-hover">
<thead>
 <tr>
   <th>ID</th>
   <th>Fecha</th>
   <th>Monto</th>
   <th>Mes</th>
   <th>Desde / Hasta</th>
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
     <th>Mes</th>
     <th>Desde / Hasta</th>
     <th>Status</th>
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


<?php include("includes/footer.php"); ?>


<script type="text/javascript">

$(document).ready(function(){
     listar();
 });


 var listar = function(){

 	var table = $('#dt_mensualidades').DataTable({
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
             if(data["status_pago"] == "APROBADO"){
                 $('td', row).addClass('green');
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
 	 	 "url":"funciones/listar_mensualidades.php"
 	  	 },
 		 "columns":[
 			{"data":"id"},
      {"data":"concepto"},
     	{"data":"monto",
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
    	{"data":"mes_de_pago"},

      {"data":"fin",
           "render": function ( data, type, row ) {
                 return new Date(row.inicio).toLocaleDateString(
                    'es-VE', // Ajusta tu idioma y país
                    {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute:'2-digit'
                    }
                ) + '<br>' + new Date(row.fin).toLocaleDateString(
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
        "data": "status_pago"
       }

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