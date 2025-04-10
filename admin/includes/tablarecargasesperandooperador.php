<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

$sql="SELECT * FROM recargar  WHERE confirmacion = 'ESPERANDO_OPERADOR'
";
$result=mysqli_query($db,$sql);

?>

<div>
  <div class="table-responsive">
	<table class="table table-hover table-condensed table-bordered" id="iddatatable">
		<thead style="background-color: #dc3545;color: white; font-weight: bold;">
			<tr>
        <td>ID</td>
        <td>Operador</td>
        <td>Nro/Monto</td>
        <td>Confirmacion</td>
        <td>Accion</td>
			</tr>
		</thead>
		<tfoot style="background-color: #00bcd4;color: white; font-weight: bold;">
			<tr>
        <td>ID</td>
        <td>Operador</td>
        <td>Nro/Monto</td>
        <td>Confirmacion</td>
        <td>Accion</td>
			</tr>
		</tfoot>
		<tbody >
			<?php
			while ($mostrar=mysqli_fetch_array($result)) {
		   ?>
				<tr >
					<td><?php echo $mostrar['id']; ?></td>
					<td><?php echo $mostrar['operador']; ?></td>
					<td><?php echo substr($mostrar['nro'], 1, 10) .'<br>'. number_format($mostrar['monto'],2,',','.') .' Bs'; ?></td>
					<td><?php echo $mostrar['confirmacion']; ?></td>
          <td style="text-align: center;">
						<span class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditar" onclick="agregaFrmActualizar('<?php echo $mostrar['id']; ?>')">
							<span class="fa fa-edit"></span>
						</span>


            <!-- <span class="btn btn-danger btn-sm" onclick="eliminarDatos('<?php echo $mostrar['id']; ?>')">
							<span class="fa fa-trash"></span>
						</span> -->
					</td>

				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
</div>
</div>



<script type="text/javascript">
	$(document).ready(function() {
		$('#iddatatable').DataTable({
"order": [[ 0, "desc" ]],
"language": idioma_espanol

});
});

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
