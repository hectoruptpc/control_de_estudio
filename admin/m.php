<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Mensualidades por Aprobar";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>
<div class="container">
  <div class="collapse" id="collapseExample">
  <div class="card">
  <div class="card-body">
            <p>Esta accion enviará un correo. </p>
        <form autocomplete="off" class="was-validated" method="post" action= "mensajeria.php">

                   <div class="form-group">
          <label for="exampleFormControlSelect1">A Quien le enviará el Mensaje </label>
          <input value ="<?php $destino; ?>" type="text" class="form-control" id="destino" aria-describedby="titulo" placeholder="Ingrese el destinatario" name="destino" required>
          <div class="invalid-feedback">Debe indicar a quien se le enviara el correo.</div>
          </div>


           <div class="form-group">
          <label for="idusuario">Asunto</label>
          <input value ="<?php $asunto; ?>" type="text" class="form-control" id="asunto" aria-describedby="titulo" placeholder="Ingrese el asunto" name="asunto" required>
          <div class="invalid-feedback">Debe indicar el asunto.</div>
          </div>

          <div class="form-group">
          <label for="contenido">Contenido</label>
          <textarea value ="<?php $contenido; ?>" type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido" required>
          </textarea>

          <div class="invalid-feedback">Debe indicar el contenido del mensaje.</div>
          </div>


  </div>
        <div class="card-footer">

          <button name="enviar_mensaje_todos_btn" type="submit" class="btn btn-primary">Enviar Correo</button>
          </form>
        </div>
  </div>
  </div>

  <div class="container mt-4">
  	<table id="dt_tlp" class="table table-bordered table-hover">
  	<thead>
  	 <tr>
  	 	<th>id</th>
  		<th>Usuario</th>
  		<th>Nombre</th>
  		<th>Fecha</th>
  		<th>Nro Transf</th>
      <th></th>
  	 </tr>
  		</thead>

  		<tbody>
  		</tbody>

  		<tfoot>
  		 <tr>
  		 	<th>id</th>
  			<th>Usuario</th>
  			<th>Nombre</th>
  			<th>Fecha</th>
  			<th>Nro Transf</th>
        <th></th>
  		 </tr>
  	 </tfoot>

  	 </table>
   </div>



</div>

<script>

   $('#contenido').summernote({
      lang: 'es-ES',
      placeholder: 'Ingrese su contenido',
      tabsize: 2,
      height: 100,
      dialogsInBody: true
});

$(document).ready(function(){
  $(".btn-primary").click(function(){
    $(".collapse").collapse('toggle'); //MUESTRA Y OCULTA
  });
  $(".btn-success").click(function(){
    $(".collapse").collapse('show'); // SOLO MUESTRA PERO NO OCULTA
  });
  $(".btn-warning").click(function(){
    $(".collapse").collapse('hide'); // SOLO OCULTA PERO NO MUESTRA
  });
});



</script>



<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function() {
     listar();

 });



var listar = function(){

	var table = $('#dt_tlp').DataTable({

    //"bServerSide": true,
		//"sAjaxSource": "funciones/listar_tlp.php",
    //"sServerMethod": "POST",
		//"dom": '<"top"pl>rt<"bottom"ip><"clear">',
		//"pagingType": "full_numbers",
    "order": [[ 3, "desc" ]],
		"language": idioma_espanol,
		 "ajax": {
		 	"method":"POST",
		 	"url":"funciones/listar_tlp.php"
		 },
		"columns":[
			{"data":"id"},
      {"data":"usuario"},
      {"data":"nombre"},
      {"data":"fecha_pedido"},
      {"data":"nro_transf"},
      {"defaultContent":'<a class="btn btn-warning" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-envelope"></i></a>'}

		]

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
