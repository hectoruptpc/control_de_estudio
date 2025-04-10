<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Producto Terminado";
$titulopag = "Producto Terminado";
include('../funciones/functions.php');

?>
<?php include("includes/head.php"); ?>

<h1><?php echo $titulopag; ?></h1>


<table id="inventarioActual" class="table-striped table-bordered table-hover" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
      <!-- Aquí DataTables cargará los datos vía AJAX --> 
    </tbody>
  </table>

  <div class="alert alert-warning">

  <h2>Historial de Ingresos</h2>
  <table id="historialIngresos" class="table-striped table-bordered table-hover" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Control/Factura</th> 
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
      <!-- Aquí DataTables cargará los datos vía AJAX -->
    </tbody>
  </table>
  </div>

  <script>
    $(document).ready(function() {
      //  Configuración de DataTables para  inventarioActual 
      $('#inventarioActual').DataTable({
         initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "orderCellsTop": true,
        "fixedHeader": true,
        "language": idioma_espanol,
        "dom": "<'top'Bfp>lrt<'bottom'ip><'clear'>",
        "caption": "Esta es una prueba de Caption",
        "ajax": {
          "url": "funciones/obtener_inventario_actual.php", 
          "method": "POST",
          "dataSrc": "data"
        },
        "columns": [
          {"data": "id_producto"}, 
          {"data": "nombre_producto"},
          {"data": "cantidad_disponible"}
        ],
    "buttons": [
            'copy', 'excel',   {
                        extend: 'print',
                        title: 'Reporte de Inventario Actual',
                        messageTop: function () {
                var fechaActual = new Date();
                var dia = fechaActual.getDate();
                var mes = fechaActual.getMonth() + 1; // Meses empiezan en 0
                var anio = fechaActual.getFullYear();
                var hora = fechaActual.getHours();
                var minuto = fechaActual.getMinutes();
                var segundo = fechaActual.getSeconds();

                return 'Reporte generado el ' + dia + '/' + mes + '/' + anio + ' a las ' + hora + ':' + minuto + ':' + segundo;
            },
                        messageBottom: null
                        
                    },
            {
                        extend: 'pdf',
                        title: 'Reporte de Inventario Actual',
                        messageTop: function () {
                var fechaActual = new Date();
                var dia = fechaActual.getDate();
                var mes = fechaActual.getMonth() + 1; // Meses empiezan en 0
                var anio = fechaActual.getFullYear();
                var hora = fechaActual.getHours();
                var minuto = fechaActual.getMinutes();
                var segundo = fechaActual.getSeconds();

                return 'Reporte generado el ' + dia + '/' + mes + '/' + anio + ' a las ' + hora + ':' + minuto + ':' + segundo;
            },
                        messageBottom: null
                    },
        ],
      }); 

      // Configuración de DataTables para historialIngresos
      $('#historialIngresos').DataTable({
         initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },
        "orderCellsTop": true,
        "fixedHeader": true,
        "language": idioma_espanol,
        "dom": "<'top'Bfp>lrt<'bottom'ip><'clear'>",
        "caption": "Esta es una prueba de Caption",
        "ajax": {
          "url": "funciones/obtener_historial_ingresos.php", 
          "method": "POST",
          "dataSrc": "data"
        },
        "columns": [
          {"data": "id"},
          {"data": "id_control"},
          {"data": "nombre_producto"},
          {"data": "cantidad"},
          {"data": "fecha"}
        ],
    "buttons": [
            'copy', 'excel',   {
                        extend: 'print',
                        title: 'Reporte de Historial de Ingresos',
                        messageTop: function () {
                var fechaActual = new Date();
                var dia = fechaActual.getDate();
                var mes = fechaActual.getMonth() + 1; // Meses empiezan en 0
                var anio = fechaActual.getFullYear();
                var hora = fechaActual.getHours();
                var minuto = fechaActual.getMinutes();
                var segundo = fechaActual.getSeconds();

                return 'Reporte generado el ' + dia + '/' + mes + '/' + anio + ' a las ' + hora + ':' + minuto + ':' + segundo;
            },
                        messageBottom: null
                        
                    },
            {
                        extend: 'pdf',
                        title: 'Reporte de Historial de Ingresos',
                        messageTop: function () {
                var fechaActual = new Date();
                var dia = fechaActual.getDate();
                var mes = fechaActual.getMonth() + 1; // Meses empiezan en 0
                var anio = fechaActual.getFullYear();
                var hora = fechaActual.getHours();
                var minuto = fechaActual.getMinutes();
                var segundo = fechaActual.getSeconds();

                return 'Reporte generado el ' + dia + '/' + mes + '/' + anio + ' a las ' + hora + ':' + minuto + ':' + segundo;
            },
                        messageBottom: null
                    },
        ],
      }); 

    }); 

  </script>

<?php include("includes/footer.php"); ?>
