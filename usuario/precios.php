<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Precios"; // Ajusta el nombre del operador si es necesario
$titulopag = "Precios";
include('../funciones/functions.php');
?>

<?php include("includes/head.php"); ?>


<button id="crear_precio" type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#crearPrecioModal">
<i class="fas fa-tag"></i>  Crear Precio
</button>

  <h3 class="mb-4">Lista de Precios</h3>

  <?php
  verificar_precios();
  ?>


<div id="alerta_datos" class="alert alert-danger mt-5" role="alert" >
<h3 class="text-uppercase">
<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
</h3>
</div>

<div id="contenedorTabla">

  <table id="tablaPrecios" class="table-striped table-bordered table-hover" style="width: 100%;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Disponible</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <thead>
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Disponible</th>
        <th>Acciones</th>
      </tr>
        </thead>
  </table>
  </div>




<!-- Modal para crear precios -->
<div class="modal fade" id="crearPrecioModal" tabindex="-1" role="dialog" aria-labelledby="crearPrecioModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearPrecioModalLabel">Crear Precio</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="crearPrecioForm">
          <div class="form-group">
            <label for="crearPrecioProducto">Producto:</label>
            <select class="form-control" id="crearPrecioProducto" name="producto" required>
              <option value="">Seleccione...</option>
              <!-- Opciones de productos se cargarán aquí -->
            </select>
          </div>
          <div class="form-group">
            <label for="crearPrecioValor">Precio:</label>
            <input type="text" min="0.1" value="0" inputmode="numeric" class="form-control" id="crearPrecioValor" name="precio" required>
          </div>
          <input type="hidden" id="precioId" name="precioId">
          <button type="submit" class="btn btn-primary"><i class="fas fa-tag"></i> Crear Precio</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$('#contenedorTabla').hide();

$('#crearPrecioForm').on('input', '#crearPrecioValor', function() {
  this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});


// MOSTRAR TABLA
var tablaPrecios;
  $(document).ready(function() {
    // Cargar la tabla de precios con DataTables
    tablaPrecios = $('#tablaPrecios').DataTable({
    "orderCellsTop": true,
    "fixedHeader": true,
        "language": idioma_espanol,
        "dom": "<'top'Bfp>lrt<'bottom'ip><'clear'>",
        "caption": "Esta es una prueba de Caption",
        "buttons": [
        'copy', 'excel',   {
                    extend: 'print',
                    title: 'Reporte de Precios',
                    messageTop: 'Mensaje TOP.',
                    messageBottom: null
                    
                },
        {
                    extend: 'pdf',
                    title: 'Reporte de Precios',
                    messageTop: 'Mensaje TOP.',
                    messageBottom: null
                },
    ],
    "order": [[ 0, "desc" ]],
      "ajax": {
        "url": "funciones/obtener_precios.php", // Archivo PHP para obtener los precios
        "type": "POST" 
      },
      "columns": [
        { "data": "id" },
        { "data": "nombre" }, // Asumiendo que tu respuesta JSON tiene un campo "nombre" para el producto
        { "data": "precio" },
        { "data": "cantidad_disponible" }, // <-- Nueva columna
        { 
          "data": null,
          "render": function (data, type, row) {
            return `<button type="button" class="btn btn-primary btn-sm editar-precio" data-id="${row.id}" data-toggle="modal" data-target="#crearPrecioModal">Editar</button>`;
          }
        }
      ],
      "initComplete": function(settings, json) { // Evento que se ejecuta al terminar de cargar la tabla
        
      if (tablaPrecios.data().count() > 0) {
        $('#alerta_datos').hide();
        $('#contenedorTabla').show();
      } else {
        $('#alerta_datos').show(); 
      }
    }
    });


  });


  
// RELLENAR MODAL DE EDITAR
    $(document).ready(function() {
  // Manejar el clic en el botón "Editar"
  $('#tablaPrecios tbody').on('click', '.editar-precio', function () {
    let fila = $(this).closest('tr'); 
    let id = fila.find('td:eq(0)').text(); 
    let producto = fila.find('td:eq(1)').text(); 
    let precio = fila.find('td:eq(2)').text(); 
    // Rellenar el formulario del modal
    $('#precioId').val(id);
    $('#crearPrecioValor').val(precio);
    // Cambiar el título del modal a "Editar Precio"
    $('#crearPrecioModalLabel').text('Editar Precio');
    $.ajax({
      url: 'funciones/obtener_lista_productos.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
          $('#crearPrecioProducto').empty();
          $('#crearPrecioProducto').append('<option value="">Seleccione...</option>');
           // Verificar si el usuario tiene precios
          data.productos.forEach(function(producto) { // Acceder a los productos desde data.productos
          $('#crearPrecioProducto').append('<option value="' + producto.id + '" data-precio="' + producto.precio + '">' + producto.nombre + '</option>');
            });
        // --> CÓDIGO CORREGIDO PARA SELECCIONAR EL PRODUCTO <--
    // Encontrar la opción que coincida con el texto del producto
    let opcionProducto = $('#crearPrecioProducto option').filter(function() {
      return $(this).text() === producto; 
    });
     // Agregar un pequeño retraso para asegurar que el select se cargue
  setTimeout(function() {
    let opcionProducto = $('#crearPrecioProducto option').filter(function() {
      return $(this).text() === producto;
    });
    // Seleccionar la opción encontrada
    opcionProducto.prop('selected', true);
     // Verificar si la opción se seleccionó correctamente
     console.log("Valor del select después del retraso:", $('#crearPrecioProducto').val());
  }, 100); // Retrasar 100 milisegundos (ajusta el valor si es necesario)
       // Abrir el modal
    $('#crearPrecioModal').modal('show');
      },
      error: function(xhr, status, error) {
        console.error("Error al obtener los productos: " + error);
      }
    });
     });
});



// GUARDAR PRECIO
$(document).ready(function() {
  $('#crearPrecioForm').submit(function(e) {
    e.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

    var precioFormateado = parseFloat($('#crearPrecioValor').val()).toFixed(2);
    // Obtener los datos del formulario
    //var formData = $(this).serialize();
    var formData = {
        producto: $('#crearPrecioProducto').val(),
        precio: precioFormateado // Incluir precioFormateado
    };

    $.ajax({
      url: 'funciones/guardar_precios.php',
      type: 'POST',
      data: formData,
      success: function(response) {
        // Cerrar el modal, mostrar un mensaje de éxito y recargar la tabla
        $('#alerta_datos').hide();
        $('#contenedorTabla').show();
        $('#crearPrecioModal').modal('hide');
        alert("Precio creado correctamente.");
        tablaPrecios.ajax.reload();
      },
      error: function(xhr, status, error) {
        console.error("Error al crear el precio: " + error);
        alert("Error al crear el precio. Por favor, inténtalo de nuevo.");
      }
    });
  });
});

// CREAR PRECIO
// OBTENER LISTA DE PRODUCTOS AL HACER CLIC EN "CREAR PRECIO"
$(document).ready(function() {
  // Función para poblar el select de productos
  function poblarSelectProductosCrear() {
    $.ajax({
      url: 'funciones/obtener_lista_productos.php', 
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        $('#crearPrecioProducto').empty();
        $('#crearPrecioProducto').append('<option value="">Seleccione...</option>');
        data.productos.forEach(function(producto) {
          $('#crearPrecioProducto').append('<option value="' + producto.id + '" data-precio="' + producto.precio + '">' + producto.nombre + '</option>');
        });
        $('#crearPrecioForm')[0].reset(); 
        $('#crearPrecioModalLabel').text('Crear Precio');

      },
      error: function(xhr, status, error) {
        console.error("Error al obtener los productos: " + error);
      }
    });
  }

  // Cargar los productos al hacer clic en el botón "Crear Precio"
  $('#crear_precio').click(function() {
    poblarSelectProductosCrear(); 
  });
});


</script>

<?php include("includes/footer.php"); ?>
