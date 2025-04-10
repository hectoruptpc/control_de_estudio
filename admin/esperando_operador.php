<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


  $titulopag = "Esperando Operador";
	include('../funciones/functions.php');



?>
<?php include("includes/head.php"); ?>
<div class="container">





  <hr>
  <div id="tablarecargasesperandooperador"></div>






  	<!-- Modal PARA EDITAR-->
  	<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
  			<div class="modal-content">
  				<div class="modal-header">
  					<h5 class="modal-title" id="exampleModalLabel">Actualizar</h5>
  					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  						<span aria-hidden="true">&times;</span>
  					</button>
  				</div>
  				<div class="modal-body">
  					<form id="frmnuevoU">

              <!-- <div class="form-group">
                  <label>id</label>
                  <input placeholder="id" type="num" class="form-control input-sm" id="id" name="id" disabled>
              </div> -->
              <div class="form-group">
                <div id="text_id"></div>
                <input type="hidden" id="id" name="id">
              </div>

              <div class="form-group">
                <div id="text_user"></div> <br> Usar DEVOLUCION Para Devolver dinero
                <input type="hidden" id="user" name="user">
              </div>

<input type="hidden" id="operador" name="operador">
<input type="hidden" id="tipo" name="tipo">
              <div class="form-group">
                  <label>nro</label>
                  <input placeholder="nro" type="num" class="form-control input-sm" id="nro" name="nro" >
              </div>

              <div class="form-group">
                  <label>Monto</label>
      						<input placeholder="monto" type="text" class="form-control input-sm" id="monto" name="monto" required>

<input type="hidden" id="fecha" name="fecha">
<input type="hidden" id="status" name="status">
<input type="hidden" id="relacion" name="relacion">

              <div class="form-group">
                  <label>Confirmacion</label>
      						<input placeholder="confirmacion" type="text" class="form-control input-sm" id="confirmacion" name="confirmacion" required>
<input type="hidden" id="sin_plan" name="sin_plan">

              </div>

  					</form>
  				</div>
  				<div class="modal-footer">
            	<button data-dismiss="modal" type="button" class="btn btn-warning" id="btnActualizar">Actualizar</button>
  					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

  				</div>
  			</div>
  		</div>
  	</div>
  	</div>

  	<!-- Fin Modal PARA EDITAR-->



</div>



<?php include("includes/footer.php"); ?>

<script type="text/javascript">


$(document).ready(function(){
    $('#tablarecargasesperandooperador').load('includes/tablarecargasesperandooperador.php');


});



	$(document).ready(function(){

		$('#btnActualizar').click(function(){
			datos=$('#frmnuevoU').serialize();

			$.ajax({
				type:"POST",
				data:datos,
				url:"funciones/procesos/esperando_operador/actualizar.php",
				success:function(r){
					if(r==1){
              $('#tablarecargasesperandooperador').load('includes/tablarecargasesperandooperador.php');
              $('#pedidosA').load('includes/notificacionpedidos.php #pedidosA');
              $('#pedidosB').load('includes/notificacionpedidos.php #pedidosB');
              $('#pedidosC').load('includes/notificacionpedidos.php #pedidosC');
						  alertify.success("<i class='fa fa-check-circle'></i>Actualizado con exito :D");
					}else{
						alertify.error("<i class='fa fa-ban'></i>Fallo al actualizar :(");
					}
				}
			});
		});
	});

</script>



<script type="text/javascript">

function agregaFrmActualizar(id){
  $.ajax({
    type:"POST",
    data:"id=" + id,
    url:"funciones/procesos/esperando_operador/obtenerDatos.php",

    success:function(r){
      datos=jQuery.parseJSON(r);
      $('#text_id').html('ID: '+datos['id']);
      $('#text_user').html('USUARIO: '+datos['user']);
      $('#id').val(datos['id']);
      $('#user').val(datos['user']);
      $('#operador').val(datos['operador']);
      $('#tipo').val(datos['tipo']);
      $('#nro').val(datos['nro']);
      $('#monto').val(datos['monto']);
      $('#fecha').val(datos['fecha']);
      $('#status').val(datos['status']);
      $('#relacion').val(datos['relacion']);
      $('#confirmacion').val(datos['confirmacion']);
      $('#sin_plan').val(datos['sin_plan']);
    }
      });
    }

</script>
