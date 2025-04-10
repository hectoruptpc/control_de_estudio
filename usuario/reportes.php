<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Reportes";
$titulopag = "Reportes";
include('../funciones/functions.php');

// Obtener el mes y año actual para los filtros por defecto
$mesActual = date('m');
$añoActual = date('Y'); 

// Procesar filtros de reporte si se envían
if (isset($_POST['generar_reporte'])) {
    $mesSeleccionado = $_POST['mes']; 
    $añoSeleccionado = $_POST['año'];
} else {
    $mesSeleccionado = $mesActual;
    $añoSeleccionado = $añoActual;
}




?>

<?php include("includes/head.php"); ?>

<div class="container-fluid">
    <h1 class="mt-4"><?php echo $titulopag; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Panel de Reportes</li>
    </ol>

    <div class="row">

        <!-- Filtro por Mes y Año -->
        <div class="col-md-12 mb-4">
            <div class="card shadow py-2">
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="mes">Seleccionar Mes:</label>
                                <select name="mes" id="mes" class="form-control">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo sprintf('%02d', $m); ?>" <?php echo ($m == $mesSeleccionado) ? 'selected' : ''; ?>>

                                        
                                            <?php 
echo $formatter->format(mktime(0, 0, 0, $m, 1, $añoSeleccionado));
                                            ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="año">Seleccionar Año:</label>
                                <select name="año" id="año" class="form-control">
                                    <?php for ($a = date('Y'); $a >= 2020; $a--): ?>
                                        <option value="<?php echo $a; ?>" <?php echo ($a == $añoSeleccionado) ? 'selected' : ''; ?>>
                                            <?php echo $a; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4 pt-4">
                                <button type="submit" name="generar_reporte" class="btn btn-primary"><i class="fas fa-filter"></i> Generar Reporte</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php 
             $mes_imp = $formatter->format(mktime(0, 0, 0, $mesSeleccionado, 1, $añoSeleccionado));

             $fecha = mktime(0, 0, 0, $mesSeleccionado, 1, $añoSeleccionado);
             $formatter->setPattern(" Y"); 
             $ano_imp = $formatter->format($fecha); 
        ?>

        <!-- Reporte de Materia Prima Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Materia Prima Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $totalMateriaPrima = totalMateriaPrima(); 
                                echo kilo($totalMateriaPrima); 
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte Ingreso de Materia Prima por Mes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ingreso Materia Prima (<?php 
                      
                           echo $mes_imp . $ano_imp;

                            ?>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $ingresoMateriaPrimaMes = ingresoMateriaPrimaPorMes($mesSeleccionado, $añoSeleccionado); 
                                echo kilo($ingresoMateriaPrimaMes); 
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Producto Terminado Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Producto Terminado Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $totalProductoTerminado = totalProductoTerminado(); 
                                echo kilo($totalProductoTerminado); 
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-industry fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reporte de Producto Terminado por Mes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Producto Terminado (<?php 

echo $mes_imp . $ano_imp;
 
                            ?>)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php 
                                $productoTerminadoMes = productoTerminadoPorMes($mesSeleccionado, $añoSeleccionado); 
                                echo kilo($productoTerminadoMes); 
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ... (Agrega más reportes aquí) ... -->

    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="reporteModal" tabindex="-1" role="dialog" aria-labelledby="reporteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reporteModalLabel">Título del Reporte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div id="areaImprimir">
      <div class="modal-body" id="contenidoModal">
        <!-- Aquí se cargará el contenido del reporte -->
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="botonImprimir">Imprimir</button>
      </div>
    </div>
  </div>
</div>

<hr>
<!-- Ejemplo de botón para el reporte de Producto Terminado -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reporteModal" data-reporte="productoTerminado">
  Ver Reporte Producto Terminado
</button>

<!-- Ejemplo de botón para el reporte de Ventas -->
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#reporteModal" data-reporte="ventasFecha">
  Ver Reporte Ventas a la Fecha
</button>

<!-- Ejemplo de botón para el reporte de Entrada de Materia Prima -->
<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#reporteModal" data-reporte="materiaPrimaEntrada">
  Ver Reporte Entrada de Materia Prima
</button>

<!-- Ejemplo de botón para el reporte de Salida de Materia Prima -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reporteModal" data-reporte="materiaPrimaUsado">
  Ver Reporte de Materia Prima Usado
</button>

<br><br><br><br><br><br><br><br><br><br>

<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function() {
  $('#reporteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); // Botón que activó el modal
    var reporte = button.data('reporte'); // Obtener el nombre del reporte

    $.ajax({
      url: 'funciones/generar_reportes.php',
      type: 'POST',
      data: { reporte: reporte }, 
      success: function(response) {
        $('#contenidoModal').html(response); // Cargar respuesta en el modal
      }
    });
  });
});



$(document).ready(function() {
  $('#reporteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget); 
    var reporte = button.data('reporte'); 

    // Obtener el mes y año seleccionados
    var mesSeleccionado = $('#mes').val();
    var añoSeleccionado = $('#año').val(); 

    $.ajax({
      url: 'funciones/generar_reportes.php',
      type: 'POST',
      data: { 
        reporte: reporte, 
        mes: mesSeleccionado,  // Añadir el mes a los datos
        año: añoSeleccionado   // Añadir el año a los datos
      }, 
      success: function(response) {
        $('#contenidoModal').html(response); 
      }
    });
  });
});

</script>