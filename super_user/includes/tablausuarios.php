<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../../funciones/functions.php');

$sql="SELECT * FROM users";
$result=mysqli_query($db,$sql);

?>

<head>
  <meta charset="utf-8" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>

<div>
  <div class="table-responsive">
	<table class="table table-hover table-condensed table-bordered" id="iddatatable">
		<thead style="background-color: #dc3545;color: white; font-weight: bold;">
			<tr>
        <td>Id</td>
        <td>Nombre / CI / Email / Fecha Ingreso</td>
        <td>Condicion</td>
        <td>Accion</td>
			</tr>
		</thead>
		<tfoot style="background-color: #00bcd4;color: white; font-weight: bold;">
			<tr>
        <td>Id</td>
        <td>Nombre / CI / Email / Fecha Ingreso</td>
        <td>Condicion</td>
        <td>Accion</td>
			</tr>
		</tfoot>
		<tbody >
			<?php
			while ($mostrar=mysqli_fetch_array($result)) {
		   ?>
				<tr >
					<td><?php echo $mostrar['id']; ?></td>
          <td>

<div class="row">
<div class="col">

              <?php echo $mostrar['idusuario'] . '<br>' .
                           $mostrar['nombre']. '<br>' .
                           '<a href = "mailto:'.$mostrar['email'].'" target="_blank">'.$mostrar['email']. '</a><br>' .
                           '<a href = "tel:'.$mostrar['tlf'].'" target="_blank">'.$mostrar['tlf']. '</a><br>' .
                           '<a href = "tel:'.$mostrar['cel'].'" target="_blank">'.$mostrar['cel']. '</a><br>' .
                           'Fecha Ingreso: ' .$mostrar['fecha_ingreso']; ?>
</div>
<div class="col">
<?php echo $mostrar['motivo_bloqueo']; ?>
</div>
</div>
          </td>
					<td>
            <?php
            $status = '';
            $clase = '';
if ($mostrar['status'] == 1) {
  // Si el usuario esta ACTIVO
  $claseS = "btn btn-info btn-sm";
  $mensajeS = '<span class="fa fa-thumbs-up"></span> Usuario Activo';
  $clickS = '';
  $btn = '<button type="submit" class="btn btn-success btn-sm" name="activar_bloquear_btn" data-toggle="popover" title="ACTIVO" data-content="Usuario ACTIVO haga click para desactivarlo o suspenderlo.">ACT <i class="fa fa-thumbs.-up"></i></button>';

  } else {
    // Si No esta Activo
    $claseS = "btn btn-danger btn-sm";
    $mensajeS = '<span class="fa fa-thumbs-down"></span> Usuario Bloqueado';
    $clickS = '';
    $btn = '<button type="submit" class="btn btn-success btn-sm" name="activar_bloquear_btn" data-toggle="popover" title="ACTIVO" data-content="Usuario ACTIVO haga click para desactivarlo o suspenderlo.">ACT <i class="fa fa-thumbs.-up"></i></button>';
  }

if (!$mostrar['password']) {
  // Si no ha creado password
$claseP = "btn btn-danger btn-sm";
$mensajeP = '<span class="fa fa-thumbs-down"></span> Sin Password';
} else {
  // Si ya creo su password
$claseP = "btn btn-success btn-sm";
$mensajeP = '<span class="fa fa-thumbs-up"></span> Con Password';
}
           ?>
<div class="btn-group-vertical" >
           <span title="Status" class="<?php echo $claseS; ?>" <?php echo $clickS; ?> >
           <?php echo $mensajeS; ?>
           </span>
           <?php echo $btn; ?>
           <span title="Password" class="<?php echo $claseP; ?>">
           <?php echo $mensajeP; ?>
           </span>

</div>
         </td>
          <td>
            <span title="Editar Usuario" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalEditar" onclick="agregaFrmActualizar('<?php echo $mostrar['id']; ?>')">
            <span class="fa fa-edit"></span> Editar
          </span>
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
"order": [[ 0, "asc" ]],
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
