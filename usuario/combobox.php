<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Perfil";
	include('../funciones/functions.php');

  function estados(){
  	global $db;
  	$query = "SELECT * FROM estados";
  	$results = mysqli_query($db, $query);
  $a = '<option value="">Seleccione</option>';
  	while ($fila = mysqli_fetch_assoc($results)) {
  			$a .= "<option value='".$fila['id_estado']."'>".$fila['estado']."</option>";
    }
    echo $a;
  }

?>



  <?php include("includes/head.php"); ?>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.1.1.js"></script>

  <script type="text/javascript">
  $(document).ready(function () {
    $("#parroquias").find("option").remove().end().append('<option value="whatever"</option>').val("whatever");
    $("#estados").change(function (){
      $("#estados option:selected").each(function () {
        id_estado = $(this).val();
        $.post("funciones/municipios.php", {id_estado: id_estado}, function(data) {
          $("#municipios").html(data);
        });
      });
    })

  });

  $(document).ready(function () {
    $("#municipios").change(function (){
      $("#municipios option:selected").each(function () {
        id_municipio = $(this).val();
        $.post("funciones/parroquias.php", {id_municipio: id_municipio}, function(data) {
          $("#parroquias").html(data);
        });
      });
    })

  });
  </script>

<div class="form-group">
<label for="estado_usuario">Estado donde Vive</label>
    <select id="estados" name="estados" class="form-control" required >
<?php estados(); ?>
    </select>
    <div class="invalid-feedback">Debe indicar el Estado donde vive.</div>
  </div>

  <div class="form-group">
  <label for="Municipio_usuario">Municipio donde Vive</label>
      <select id="municipios" name="municipios" class="form-control" required >
  <?php //municipios(); ?>
      </select>
      <div class="invalid-feedback">Debe indicar el Municipio donde vive.</div>
    </div>

    <div class="form-group">
    <label for="Parroquias_usuario">Parroquias donde Vive</label>
        <select id="parroquias" name="parroquias" class="form-control" required >
    <?php //municipios(); ?>
        </select>
        <div class="invalid-feedback">Debe indicar el Parroquias donde vive.</div>
      </div>
