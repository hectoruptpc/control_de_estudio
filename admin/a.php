<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


  $titulopag = "Esperando Operador";
	include('../funciones/functions.php');
?>
<?php include("includes/head.php"); ?>
<div class="container">

  <hr>
  <div id="tablausuarios"></div>





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
    					<form id="frmnuevoU" accept-charset="utf-8">

                <!-- <div class="form-group">
                    <label>id</label>
                    <input placeholder="id" type="num" class="form-control input-sm" id="id" name="id" disabled>
                </div> -->
                <div class="form-group">
                  <div id="text">ID: </div>
                  <input type="hidden" id="id" name="id">
                </div>
                <div class="form-group">
                    <label class="text-uppercase">idusuario</label>
                    <input placeholder="idusuario" type="text" class="form-control input-sm" id="idusuario" name="idusuario" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">nombre</label>
                    <input placeholder="nombre" type="text" class="form-control input-sm" id="nombre" name="nombre" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">email</label>
                    <input placeholder="email" type="text" class="form-control input-sm" id="email" name="email" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">tlf</label>
                    <input placeholder="tlf" type="text" class="form-control input-sm" id="tlf" name="tlf" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">cel</label>
                    <input placeholder="cel" type="text" class="form-control input-sm" id="cel" name="cel" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">direccion</label>
                    <input placeholder="direccion" type="text" class="form-control input-sm" id="direccion" name="direccion" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">ciudad</label>
                    <input placeholder="ciudad" type="text" class="form-control input-sm" id="ciudad" name="ciudad" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">estado</label>
                    <input placeholder="estado" type="text" class="form-control input-sm" id="estado" name="estado" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">municipio</label>
                    <input placeholder="municipio" type="text" class="form-control input-sm" id="municipio" name="municipio" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">parroquia</label>
                    <input placeholder="parroquia" type="text" class="form-control input-sm" id="parroquia" name="parroquia" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">status</label>
                    <input placeholder="status" type="text" class="form-control input-sm" id="status" name="status" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">motivo_bloqueo</label>
                    <input placeholder="motivo_bloqueo" type="text" class="form-control input-sm" id="motivo_bloqueo" name="motivo_bloqueo" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">monto_a_favor</label>
                    <input placeholder="monto_a_favor" type="text" class="form-control input-sm" id="monto_a_favor" name="monto_a_favor" >
                </div>
                <div class="form-group">
                    <label class="text-uppercase">disp_a_favor</label>
                    <input placeholder="disp_a_favor" type="text" class="form-control input-sm" id="disp_a_favor" name="disp_a_favor" >
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
    $('#tablausuarios').load('includes/tablausuarios.php');
});



	$(document).ready(function(){

		$('#btnActualizar').click(function(){
			datos=$('#frmnuevoU').serialize();

			$.ajax({
				type:"POST",
				data:datos,
				url:"funciones/procesos/usuarios/actualizar.php",
				success:function(r){
					if(r==1){
            $('#tablausuarios').load('includes/tablausuarios.php');
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
    url:"funciones/procesos/usuarios/obtenerDatos.php",
    success:function(r){
      datos=jQuery.parseJSON(r);
      $('#text').append(datos['id']);
      $('#id').val(datos['id']);
      $('#idusuario').val(datos['idusuario']);
      $('#nombre').val(datos['nombre']);
      $('#email').val(datos['email']);
      $('#tlf').val(datos['tlf']);
      $('#cel').val(datos['cel']);
      $('#direccion').val(datos['direccion']);
      $('#ciudad').val(datos['ciudad']);
      $('#estado').val(datos['estado']);
      $('#municipio').val(datos['municipio']);
      $('#parroquia').val(datos['parroquia']);
      $('#status').val(datos['status']);
      $('#motivo_bloqueo').val(datos['motivo_bloqueo']);
      $('#monto_a_favor').val(datos['monto_a_favor']);
      $('#disp_a_favor').val(datos['disp_a_favor']);
    }
      });
    }
</script>
