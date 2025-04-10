<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Gestor de Contenidos";
include('../funciones/functions.php');



function mostrar_contenidos(){
  global $db, $limit_end;
$url = basename($_SERVER ["PHP_SELF"]);
$user_tipo = 'user';

if (isset($_GET['p']))
  $ini=$_GET['p'];
else
  $ini=1;

$init = ($ini-1) * $limit_end;

if (isset($_REQUEST['busqueda'])) {
  $busqueda = strtolower(e($_REQUEST['busqueda']));
} else {
  $busqueda = "";
}

if (empty($busqueda)) {
  $busqueda = "";
  $count="SELECT COUNT(*) FROM contenido";
  $query = "  SELECT * FROM contenido
           ORDER BY id  DESC
           LIMIT $init, $limit_end";
} else {
  $count="SELECT COUNT(*) FROM contenido WHERE (seccion LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%')";
  $query = "SELECT * FROM 'contenido' WHERE (seccion LIKE '%$busqueda%' OR contenido LIKE '%$busqueda%') ORDER BY id
  DESC LIMIT $init, $limit_end";
}
  $result = mysqli_query($db,$query);
  //$row = mysqli_fetch_assoc($result);
  $row =  mysqli_num_rows($result);
  if (!$row){

        $mensaje  = 'No hay datos que Mostrar';
      echo '<div class="alert alert-danger" role="alert" >';
      echo '<h3>';
      echo $mensaje;
      //unset($_SESSION['successmes']);
      echo '</h3>';
      echo '</div>';

      } else
      {

        $num = $db->query($count);
		$x = $num->fetch_array();
    $total = ceil($x[0]/$limit_end);

    echo '<h2 class ="mt-5">Editar Contenido</h2>';
    echo "<hr>";

    echo '<div class="d-none d-sm-none d-md-block">';
        pag($ini, $limit_end, $total);
    echo "</div>";
    echo '<div class="d-block d-sm-block d-md-none">';
    pag_test($ini, $limit_end, $total);
    echo "</div>";

    // INICIA EL WHILE
    echo '<div class="accordion" id="accordionExample">';

     $c = $db->query($query);
     $j = 1;

     $c = $db->query($query);
		 while($row = $c->fetch_array(MYSQLI_ASSOC))
		  {
      $date = date_create($row['fecha']);
      $fecha = date_format($date, 'd-m-Y');

      $rowid = $row['id'];
      $seccion = $row['seccion'];
      $contenido = $row['contenido'];

      //$boton_editar = '<a class="btn btn-outline-primary btn-sm" href="editar_usuarios.php?id=jjjj" data-toggle="popover" title="EDITAR CONTENIDO" data-content="Editar Contenido">Editar</a>';
      $boton_editar = '<a class="btn btn-outline-dark btn-sm" href="editar_contenido.php?id='.$rowid.'" data-toggle="popover" title=EDITAR CONTENIDO" data-content="Editar este contenido.">
      Editar
      </a>';

$accion = '<div class="btn-group" >'. $boton_editar. '</div>';

$a = '
     <div class="card">
       <div class="card-header" id="headingOne'.$rowid.'">
         <h5 class="row mb-0">
           <button class="btn btn-link col-12" type="button" data-toggle="collapse" data-target="#collapseOne'.$rowid.'" aria-expanded="true" aria-controls="collapseOne'.$rowid.'">

      <div class="row no-gutters">
           <div class="d-flex justify-content-start col-sm-8">'.$rowid.' Seccion: '. $seccion.' contenido('. $seccion.')</div>
           <div class="d-flex justify-content-end col-sm-4">'.$accion.'
         </div>
    </div>

           </button>
         </h5>
       </div>

       <div id="collapseOne'.$rowid.'" class="collapse" aria-labelledby="headingOne'.$rowid.'" data-parent="#accordionExample">
         <div class="card-body">';
         //$a .= $contenido;
         $ct = substr($contenido,0,600) .".......";
         $ct = strip_tags($ct, "<img><h1><h2><h3><h4><h5><br><p><b>");
         $a .= $ct;

    //  $a .= '<tr>';
    //  $a .= '<td class="align-middle">'.$rowid.'</td>
    //        <td class="align-middle">'.$seccion.'</td>
    //        <td width = "25%" class="align-middle">'.$contenido.'</td>
    //        <td class="align-middle">'.$accion.'</td>
    //        </tr>';
    $a .='       </div>
         </div>
       </div>
         ';
   echo $a;
          }
    //$a .= '</tbody></table>';

echo '</div>';
echo '<div class="d-none d-sm-none d-md-block">';
    pag($ini, $limit_end, $total);
echo "</div>";
echo '<div class="d-block d-sm-block d-md-none">';
pag_test($ini, $limit_end, $total);
echo "</div>";

    }



  }

?>
<?php include("includes/head.php"); ?>
<div class="container">

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#myModal2">Nuevo Contenido</button>



<h2>Contenidos</h2>
<br>
<b>Para incluir en un php: </b>
contenido('nombre_contenido')
<br>
<!-- notification message -->
<?php if (isset($_SESSION['gestor_contenido'])) : ?>
<div class="alert alert-danger" role="alert"" >
<h3>
<?php
echo $_SESSION['gestor_contenido'];
unset($_SESSION['gestor_contenido']);
?>
</h3>
</div>
<?php endif ?>

<?php mostrar_contenidos(); ?>



<!-- Origen de Tabla -->
<?php //lista_mensajes(); ?>
<!-- Fin de Tabla -->




<!-- Modal -->
<div id="myModal2" class="modal fade bd-example-modal-lg" role="dialog">
<div class="modal-dialog modal-lg">

<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="mensajeriaLabel">Gestion de Contenidos</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form autocomplete="off" class="was-validated" method="post" action= "gestor_contenido.php">


<div class="form-group">
<label for="seccion">Seccion</label>
<input value ="<?php $seccion; ?>" type="text" class="form-control" id="seccion" aria-describedby="seccion" placeholder="Crear Seccion" name="seccion" required>
<div class="invalid-feedback">Debe indicar la seccion.</div>
</div>

<pre><code>
&lt;div class="alert alert-danger alert-dismissible fade show" role="alert"&gt;
&lt;strong&gt;Holy guacamole!&lt;/strong&gt; You should check in on some of those fields below.
&lt;button type="button" class="close" data-dismiss="alert" aria-label="Close"&gt;
&lt;span aria-hidden="true">&times;&lt;/span&gt;
&lt;/button&gt;
&lt;/div&gt;
</code></pre>

<div class="form-group">
<label for="idusuario">Contenido</label>
<textarea value ="<?php $contenido; ?>" type="text" class="form-control" id="contenido" aria-describedby="contenido" placeholder="Ingrese el contenido" name="contenido" required></textarea>
<div class="invalid-feedback">Debe indicar el contenido del mensaje.</div>
</div>

<script>
    $('#contenido').summernote({
    lang: 'es-ES',
   placeholder: 'Ingrese su contenido',
   tabsize: 2,
   height: 300
 });
 </script>


</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
<button name="nuevo_contenido_btn" type="submit" class="btn btn-primary">Grabar</button>
</form>
</div>


</div>
</div>
</div>

<script>
$('#contenido').summernote({
  lang: 'es-ES',
  placeholder: 'Ingrese su contenido',
  tabsize: 2,
  height: 300
});
</script>






<?php include("includes/footer.php"); ?>
