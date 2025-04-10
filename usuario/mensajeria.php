<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


$titulopag = "Mensajeria";
include('../funciones/functions.php');


@$consulta = e($_REQUEST['consulta']);
$id = $_SESSION['user']['username'];
$nombre = $_SESSION['user']['nombre'];
$email = $_SESSION['user']['email'];
$identificador = intval( "0" . rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) );
$asunto = 'Reporte_'.$id.'_'.$identificador;

if (empty($consulta)) {
} else {
  $query = "INSERT INTO mensajes (id, asunto, contenido, origen, destinatario) VALUES (null, '$asunto', '$consulta', '$id', 'JESUMINISTROSYMAS')";
  if (mysqli_query($db, $query)) {

    $_SESSION['mensajeria']  ="$nombre, hemos recibido su mensaje de manera correcta con el siguiente contenido:<br> $consulta";

    $asunto2 = "Se ha creado una consulta";
    $cuerpo = "Hola $nombre <br><br>
    Hemos recibido su consulta y en breve sera atendida.<br>El contenido de su consulta es el siguiente:<br>$consulta";

    enviarEmail($email, $nombre, $asunto2, $cuerpo);

  } else {
    $_SESSION['mensajeria']  .= '<i class="fa fa-exclamation-triangle"></i> Ups..! Algo ha ocurrido.<br>'. mysqli_error($db).'<br>Intente nuevamente y si el error continua puede comunicarse con el administrador del sitio en el area de soporte.';

  }
}
?>
<?php include("includes/head.php"); ?>
<div class="container">
******************************************

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-hands-helping"></i> Solicitar Ayuda o Soporte
</button>


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalScrollableTitle">Generar Reporte</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<p>Si posee alguna interrogante, duda o desea solucionar un problema referente al uso de la plataforma, puede contarnos aca de que se trata y a la brevedad nos pondremos en contacto con usted.</p>
<p>No debe usar etiquetas HTML <code>&lt;p&gt;&lt;/br&gt;&lt;hr&gt;&lt;/p&gt;
</code></p>

<form class="was-validated" autocomplete="on" method="post" id="form" name="form" action= "<?php echo 'mensajeria.php'; ?>">

<div class="form-group">
<label for="consulta">Su Mensaje o Consulta</label>
<textarea pattern="[A-Za-z0-9 ,.:*/?¿¡!_-\s]{100,20000}" rows="5" class="form-control" name="consulta" id="consulta" aria-describedby="consulta" placeholder="Escriba aqui su consulta" required></textarea>
<div class="invalid-feedback">Debe indicar su consulta, El contenido de su mensaje debe ser minimo de 100 letras, No estan permitidas las etiquetas HTML ni caracteres especiales.</div>
<div class="valid-feedback">Correcto, ya puede enviar su consulta.</div>
</div>


<script>
$( document ).ready( function() {
  var errorMessage = "El contenido de su mensaje debe ser minimo de 100 letras, No estan permitidas las etiquetas HTML ni caracteres especiales.";
  $( this ).find( "textarea" ).on( "input change propertychange", function() {
    var pattern = $( this ).attr( "pattern" );
    if(typeof pattern !== typeof undefined && pattern !== false)
    {
      var patternRegex = new RegExp( "^" + pattern.replace(/^\^|\$$/g, '') + "$", "g" );
      hasError = !$( this ).val().match( patternRegex );
      if ( typeof this.setCustomValidity === "function")
      {
        this.setCustomValidity( hasError ? errorMessage : "" );
      }
      else
      {
        $( this ).toggleClass( "error", !!hasError );
        $( this ).toggleClass( "ok", !hasError );
        if ( hasError )
        {
          $( this ).attr( "title", errorMessage );
        }
        else
        {
          $( this ).removeAttr( "title" );
        }
      }
    }
  });
});
</script>


</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
<button class="btn btn-success" type="submit"><i class="fa fa-envelope"></i> Enviar</button>

</form>
</div>
</div>
</div>
</div>
**************************************


<h2>Mensajes</h2>

<!-- notification message -->
<?php if (isset($_SESSION['mensajeria'])) : ?>
<div class="alert alert-danger" role="alert" >
<h3>
<?php
echo $_SESSION['mensajeria'];
unset($_SESSION['mensajeria']);
?>
</h3>
</div>
<?php endif ?>



<?php mostrar_mensajes(); ?>


<hr>



Si deseas ver todos los mensajes grupales que se han enviado utilizando la tecnologia de Google Groups puedes hacerlo ingresando aqui:<br>
<a href="https://groups.google.com/g/GestionDeRecargas" class="btn btn-success" target="_blank" >Ver Mensajes en Google Groups</a>
<hr>



</div>

<?php include("includes/footer.php"); ?>
