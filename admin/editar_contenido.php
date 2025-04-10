<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Editar Contenidos";
include('../funciones/functions.php');


function editar_contenido(){
  global $db, $seccion,
  $contenido;

  $rowid = e($_REQUEST['id']);
  //$contenido = $_REQUEST['contenido'];

  $query = "SELECT * FROM contenido WHERE id = '$rowid'";
  $resultado = mysqli_query($db, $query);
  $rows = mysqli_num_rows($resultado);
  $row = mysqli_fetch_assoc($resultado);
  if ($rows < 1){
    $_SESSION['editar_contenido']  = "Lo sentimos, algo ha ocurrido.<br>";
  } else {

  $id = $row['id'];
  $seccion = $row['seccion'];
  $contenido = $row['contenido'];
  //echo $contenido;

  $editar_contenido = ' <form autocomplete="off" class="was-validated" method="post" action= "editar_contenido.php?id='.$id.'">';

  $editar_contenido .= '<div class="form-group">
<label for="contenido">Contenido</label>
<textarea type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido" >'.$contenido.'</textarea>
<div class="invalid-feedback">Debe indicar el contenido.</div>
</div>';
$editar_contenido .= '<button type="submit" class="btn btn-primary" name="editar_contenido_btn">Enviar</button>';
echo $editar_contenido;

  }

}


$rowid = $_REQUEST['id'];

?>
<?php include("includes/head.php"); ?>
<div class="container">

<h2>Editar Contenidos</h2>


<!-- notification message -->
<?php if (isset($_SESSION['editar_contenido'])) : ?>
			<div class="alert alert-danger" role="alert"" >
				<h3>
					<?php
						echo $_SESSION['editar_contenido'];
						unset($_SESSION['editar_contenido']);
					?>
				</h3>
			</div>
<?php endif ?>
<pre><code>
&lt;div class="alert alert-danger alert-dismissible fade show" role="alert"&gt;
  &lt;strong&gt;Holy guacamole!&lt;/strong&gt; You should check in on some of those fields below.
  &lt;button type="button" class="close" data-dismiss="alert" aria-label="Close"&gt;
    &lt;span aria-hidden="true">&times;&lt;/span&gt;
  &lt;/button&gt;
&lt;/div&gt;
</code></pre>

<?php editar_contenido(); ?>

<script>
    $('#contenido').summernote({
    lang: 'es-ES',
   placeholder: 'Ingrese su contenido',
   tabsize: 2,
   height: 300
 });
 </script>

<?php echo $rowid;?>
</div>

</div>






<?php include("includes/footer.php"); ?>
