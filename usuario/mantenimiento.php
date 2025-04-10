<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Mantenimiento";
$titulopag = "Area en Mantenimiento";
include('../funciones/functions.php');

?>
<?php
 include("includes/head.php"); ?>

<?php
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion == 'respaldar') {
      respaldarDatosUsuario($id_usua);
    } elseif ($accion == 'optimizar') {
      optimizarTablasUsuario($id_usua);
    } 
  } 


  function obtenerArchivosRespaldo($carpetaRespaldos) {
    $archivos = []; 
    if (is_dir($carpetaRespaldos)) {
      $archivosTmp = scandir($carpetaRespaldos); 
      foreach ($archivosTmp as $archivo) {
        if (pathinfo($archivo, PATHINFO_EXTENSION) == 'sql') {
          $archivos[] = $archivo;
        }
      }
    }
    return $archivos;
  }
  
  if (isset($_POST['accion'])) {
      // ... (tus otros casos de acción)
      if ($_POST['accion'] == 'eliminar' && isset($_POST['archivo'])) {
          $archivo = $_POST['archivo']; 
          $rutaArchivo = 'respaldos/' . $id_usua . '/' . $archivo; 
          if (file_exists($rutaArchivo)) { 
            if (unlink($rutaArchivo)) {
                echo '<div class="alert alert-success">Archivo ' . $archivo . ' eliminado correctamente.</div>';
            } else {
                echo '<div class="alert alert-danger">Error al eliminar el archivo ' . $archivo . '.</div>';
            }
          } else { 
              echo '<div class="alert alert-warning">El archivo ' . $archivo . ' no existe.</div>';
          } 
      }
  } 
  
?>

  <div class="container mt-5">
    <h2>Mantenimiento de Datos</h2>

    <div class="row mt-4">

      <!-- Tarjeta para Respaldar Datos -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            Respaldar Datos
          </div>
          <div class="card-body">
            <p>Crea una copia de seguridad de tus datos.</p>
            <form method="post">
              <input type="hidden" name="accion" value="respaldar">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-download"></i> Generar Respaldo
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Tarjeta para Optimizar Tablas -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-warning text-white">
            Optimizar Tablas
          </div>
          <div class="card-body">
            <p>Optimiza el rendimiento de tus tablas.</p>
            <form method="post">
              <input type="hidden" name="accion" value="optimizar">
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-broom"></i> Optimizar
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>

  </div> 


  <div class="container mt-5">
  
  
   <div class="row mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-info text-white">
            Respaldos Disponibles
          </div>
          <div class="card-body">
            <ul class="list-group">
            <?php
              // Mostrar los respaldos
              $carpetaRespaldos = 'respaldos/' . $id_usua . '/';
              $archivos = obtenerArchivosRespaldo($carpetaRespaldos);
          
              if (count($archivos) > 0) {
                  foreach ($archivos as $archivo) {
                      echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                          <span>' . $archivo . '</span>
                          <div>
                              <a href="' . $carpetaRespaldos . $archivo . '" class="btn btn-sm btn-primary mr-2" download>
                                  <i class="fas fa-download"></i> Descargar
                              </a>
                              <form method="post" style="display: inline;">
                                  <input type="hidden" name="archivo" value="' . $archivo . '">
                                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminarModal" data-archivo="' . $archivo . '">
                          <i class="fas fa-trash"></i> Eliminar
                      </button>
                              </form>
                          </div>
                      </li>';
                  }
              } else {
                  echo '<div class="alert alert-warning" role="alert">No se encontraron respaldos.</div>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>


  </div> 
  <br><br><br><br><br><br><br><br><br><br>

  <!-- Modal para confirmar la eliminación -->
<div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar el archivo <span id="nombreArchivo"></span>? Esta acción no se puede deshacer.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <form method="post" id="eliminarForm">
          <input type="hidden" name="archivo" id="archivoAEliminar">
          <input type="hidden" name="accion" value="eliminar">
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function() {
  $('#eliminarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botón que activó el modal
    var nombreArchivo = button.data('archivo'); 
    var modal = $(this);
    modal.find('#nombreArchivo').text(nombreArchivo);
    modal.find('#archivoAEliminar').val(nombreArchivo);
  });
});
</script> 
