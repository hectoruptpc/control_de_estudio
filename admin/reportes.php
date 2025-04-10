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

<div class="container">
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
                                <!--button type="submit" name="generar_reporte" class="btn btn-primary"><i class="fas fa-filter"></i> Generar Reporte</button !-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Botones para los reportes --> 
    </div>
</div>

<!-- Modal para mostrar reportes -->
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

<div class="container">
    <hr>

    <!-- Botones para los reportes (añadir los necesarios) -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reporteModal" data-reporte="reporteUsuarios">
        Ver Reporte de Usuarios
    </button>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#reporteModal" data-reporte="reporteBilletera">
        Ver Reporte de Billetera
    </button>

    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#reporteModal" data-reporte="reporteVisitas">
        Ver Reporte de Visitas
    </button>

    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#reporteModal" data-reporte="reportePagos">
        Ver Reporte de Pagos
    </button>
    <br><br><br><br><br><br><br><br><br><br>

</div>

<?php include("includes/footer.php"); ?>

<script>
    $(document).ready(function() {
        $('#reporteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); 
            var reporte = button.data('reporte'); 
        
            // Obtener el mes y año seleccionados
            var mesSeleccionado = $('#mes').val();
            var añoSeleccionado = $('#año').val();
        
            $.ajax({
                url: 'funciones/generar_reportes.php',  // <-- Cambiamos la ruta 
                type: 'POST',
                data: {
                    reporte: reporte,
                    mes: mesSeleccionado, 
                    año: añoSeleccionado 
                },
                success: function(response) {
                    $('#contenidoModal').html(response); 
                }
            });
        });
    });

</script>