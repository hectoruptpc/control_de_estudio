<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Ventas";
$titulopag = "Ventas";
include('../funciones/functions.php');

?>


<?php include("includes/head.php"); ?>


<!-- Button trigger modal --> 
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#crearModal">
<i class="fas fa-hand-holding-usd"></i> Registrar Venta
</button>

<h3 class="mb-4">Lista de Ventas</h3>

<?php
verificar_precios();
  ?>

<div id="alerta_datos" class="alert alert-danger mt-5" role="alert" >
<h3 class="text-uppercase">
<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
</h3>
</div>

<div id="contenedorTabla">

<table id="ventas" class="table-striped table-bordered table-hover" style="width: 100%;">
        <thead>
            <tr>
            <th>ID Venta</th>
            <th>Fecha</th>
            <th>Cedula Cliente</th>
            <th>Nombre Cliente</th>
            <th>Teléfono</th>
            <th>Total Productos</th>
            <th>Total Venta</th>
            <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
        <thead>
            <tr>
            <th>ID Venta</th>
            <th>Fecha</th>
            <th>Cedula Cliente</th>
            <th>Nombre Cliente</th>
            <th>Teléfono</th>
            <th>Total Productos</th>
            <th>Total Venta</th>
            <th>Acciones</th>
            </tr>
        </thead>
    </table>

    </div>







<!-- MODAL EDITAR -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Editar Cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="cedula">Cedula</label>
                <input onkeyup="mayus(this);" type="text" class="form-control" id="cedula" name="cedula">
            </div>
            <div class="form-group col-md-6">
                <label for="nombre">Nombre</label>
                <input onkeyup="mayus(this);" type="text" class="form-control" id="nombre" name="nombre">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="telefono1">Telefono 1</label>
                <input type="text" class="form-control" id="telefono1" name="telefono1">
            </div>
            <div class="form-group col-md-6">
                <label for="telefono2">Telefono 2</label>
                <input type="text" class="form-control" id="telefono2" name="telefono2">
            </div>
        </div>
        <div class="form-group">
            <label for="direccion">Direccion</label>
            <input onkeyup="mayus(this);" type="text" class="form-control" id="direccion" name="direccion" placeholder="Urbanizacion, Sector, Manzana, Casa, Edificio">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option selected>Seleccione...</option>
                </select>

            </div>

            <div class="form-group col-md-6">
                <label for="ciudad">Ciudad</label>
                <select class="form-control" id="ciudad" name="ciudad">
                    <option selected>Seleccione...</option>
                </select>
            </div>

            <input type="hidden" class="form-control" id="id" name="id">

        </div>
        <button type="submit" class="btn btn-primary" id="update_cliente" name="update_cliente"><i class="fas fa-save"></i> Guardar</button>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CREAR VENTA-->
<div id="crearModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">

      <!-- SE APLICA LOGICA DE EXISTENCIA DE CLIENTE, SI NO EXISTE SE CREA -->
        
        <h4 class="modal-title">Crear Venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="ventaForm">
      <div id="clienteSection">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="cedula">Cedula</label>
                <input onkeyup="mayus(this);" type="text" class="form-control" id="cedula" name="cedula">
            </div>
            <div class="form-group col-md-6">
                <label for="nombre">Nombre</label>
                <input onkeyup="mayus(this);" type="text" class="form-control" id="nombre" name="nombre" >
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="telefono1">Telefono 1</label>
                <input type="text" class="form-control" id="telefono1" name="telefono1" >
            </div>
            <div class="form-group col-md-6">
                <label for="telefono2">Telefono 2</label>
                <input type="text" class="form-control" id="telefono2" name="telefono2" >
            </div>
        </div>
        <div class="form-group">
            <label for="direccion">Direccion</label>
            <input onkeyup="mayus(this);" type="text" class="form-control" id="direccion" name="direccion" placeholder="Urbanizacion, Sector, Manzana, Casa, Edificio" >
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control" >
                    <option selected>Seleccione...</option>
                </select>

            </div>

            <div class="form-group col-md-6">
                <label for="ciudad">Ciudad</label>
                <select class="form-control" id="ciudad" name="ciudad" >
                    <option selected>Seleccione...</option>
                    <!-- Las opciones se cargarán aquí con JavaScript -->
                </select>
            </div>

        </div>
        <!-- SE DEBE GUARDAR CLIENTE, NOTIFICAR QUE SE HA CREADO EXITOSAMENTE, QUITAR EL BOTON DE GUARDAR CLIENTE Y PERMITIR SEGUIR CREANDO LA VENTA -->
        <button type="submit" class="btn btn-primary" id="guardar_cliente" name="guardar_cliente" ><i class="fas fa-save"></i> Guardar Cliente</button>

    </div>
<!-- SECCIÓN DE LA VENTA -->
<div id="ventaSection" style="display:none;"> 
<div id="alerta"></div>
<div id="mostrar_lista">
            <div id="productosContainer">
              <div class="producto-item">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="producto">Producto</label>
                    <select class="form-control productoSelect seleccionador" name="producto[]">
                      <option value="">Seleccione...</option>
                      <!-- Productos se cargarán aquí -->
                    </select>
                  </div>
                  <div class="form-group col-md-5">
                    <label for="cantidad">Cantidad en Gramos</label>
                    <input type="text" class="form-control cantidadInput seleccionador" name="cantidad[]" min="0.1" value="0" inputmode="numeric">
                    <span class="cantidad-disponible">Disponibles: 0</span>
                  </div>
                  
                  <div class="form-group col-md-1">
                    <label for="precio" style="font-size: 20px;">$ x Kg</label>
                    <span class="precio-producto seleccionador" style="font-size: 20px;">0 $</span> 
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="subtotal"  style="font-size: 24px;">Subtotal</label>
                    <span class="subtotal-producto"  style="font-size: 24px;">0 $</span> 
                  </div>
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-secondary" id="agregarProducto"><i class="fas fa-plus-circle"></i> Agregar Producto</button>
            <div class="form-group mt-3">
              <label for="totalVenta"  style="font-size: 24px;">Total Venta:</label>
              <span id="totalVenta"  style="font-size: 24px;">0 $</span> 
            </div>
            <input type="hidden" id="id" name="id">
            <button type="submit" class="btn btn-primary" id="guardarVenta" name="guardarVenta"><i class="fas fa-save"></i> Guardar Venta</button>
            <button type="button" class="btn btn-danger" id="limpiar_todo" name="limpiar_todo"> <i class="fas fa-trash-alt"></i> Borrar Todo</button>
</div>

</div>
    </form>

    <!-- SE APLICA LOGICA DE CREACION DE VENTA -->
    <!-- SE DEBE MOSTRAR LA SELECCION DE PRODUCTOS Y PRECIOS PARA LA VENTA SI NO EXISTEN LOS PRECIOS DE LOS PRODUCTOS CREADOS SE DEBE MANDAR AL CLIENTE A CREAR SU LISTA PERSONALIZADA DE PRECIOS A precios.php -->
    <!-- AL IR SELECCIONANDO LA LISTA DE PRECIOS SE DEBE MOSTRAR COMO SUB TOTAL EL RESULTADO DE LA MULTIPLICACION DEL VALOR DEL PRODUCTO QUE DEBE MOSTRARSE, POR LA CANTIDAD DE ESE PRODUCTO QUE SE VA A VENDER, QUE AL SELECCIONAR DEBE PERMITIR SELECCIONAR COMO MAXIMO SOLO LA CANTIDAD DISPONIBLE Y A SU VEZ MOSTRAR AL FINAL EL TOTAL DE LA SUMA DE TODO LOS SUBTOTALES DE ESA VENTA  -->

      </div>
      <div class="modal-footer">
      <button id="cerrar" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<div id="detalleModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl modal-dialog-scrollable"> 
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detalle de la Venta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div id="areaImprimir">
      <div class="modal-body" id="detalleVenta">
        <!-- Aquí se cargarán los detalles de la venta -->
      </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary" id="botonImprimir" value="Imprimir" /> 

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


      
    


<script>



    // -----------------------------------------------------
    // VARIABLES
    // -----------------------------------------------------

document.getElementById('guardar_cliente').style.display = 'none';
let timer;
let idClienteGlobal = null; // Variable global para el ID del cliente

    // -----------------------------------------------------
    // BUSCAR CEDULA
    // -----------------------------------------------------

document.querySelector('#crearModal #cedula').addEventListener('input', function() {
    clearTimeout(timer);

    var cedula = this.value || '';

    timer = setTimeout(function() {
        var formData = new FormData();
        formData.append('cedula', cedula);

        fetch('funciones/clientes.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
              var cliente = data && data.data ? data.data[0] : null;
              // console.log("Cliente: ", cliente);
              // console.log("ID Cliente:", cliente.id);
              if (cliente) { 
              idClienteGlobal = cliente.id;  // Guarda el ID en la variable global 
            } else {
              idClienteGlobal = null; // Reinicia el ID si no se encuentra el cliente 
            }
             
              var campos = ['nombre', 'telefono1', 'telefono2', 'direccion', 'estado', 'ciudad'];
              
              for (var i = 0; i < campos.length; i++) {
  var campo = campos[i];
  if (cliente && cliente[campo]) {
  document.querySelector('#crearModal #' + campo).value = cliente[campo];
  if (campo === 'estado') {
  // Disparar el evento 'change' en el select de estado para que se carguen las ciudades
  document.querySelector('#crearModal #estado').dispatchEvent(new Event('change'));
  }

  } else {
  document.querySelector('#crearModal #' + campo).value = '';
  }
  }
// ----> AGREGAR ESTA SECCIÓN <----
if (!cliente || (Object.keys(cliente).length === 0 && cliente.constructor === Object)) {
        document.getElementById('guardar_cliente').style.display = 'block';
        $('#ventaSection').hide(); // Ocultar la sección de venta
       // $('#clienteSection').show(); // Mostrar la sección del cliente
      } else {
        document.getElementById('guardar_cliente').style.display = 'none';
        $('#ventaSection').show();  // Mostrar la sección de venta
        $('#clienteSection').show(); // Mostrar la sección del cliente
        $('.seleccionador.productoSelect').val(''); // Reiniciar el select de producto
        $('.seleccionador.cantidadInput').val('0');   // Reiniciar cantidad a 1 (o el valor que quieras)
        $('.seleccionador.precio-producto').text('0 $'); // Reiniciar el precio 
        $('#totalVenta').text('0.00'); // Reiniciar valor del precio
        $('.subtotal-producto').text('0.00'); // Reiniciar valor del subtotal
      }
  });
    }, 900);
});



    // -----------------------------------------------------
    // CREACION Y EDICION
    // -----------------------------------------------------

let modals = ['crearModal', 'editModal'];

modals.forEach(function(modal) {
fetch('funciones/estados.php')
.then(response => response.json())
.then(data => {
const selectEstado = document.querySelector('#' + modal + ' #estado');
data.forEach(estado => {
const option = document.createElement('option');
option.value = estado.id_estado; 
option.text = estado.estado;
selectEstado.add(option);
});
})
.catch(error => console.error(error));

document.querySelector('#' + modal + ' #estado').addEventListener('change', function() {
// Obtén el valor del elemento select
var estado = document.querySelector('#' + modal + ' #estado').value;
//console.log("Estado :",estado);
// Crea un objeto FormData
var formData = new FormData();
// Agrega el valor del elemento select al objeto FormData
formData.append('estado', estado);
// Realiza la solicitud fetch
fetch('funciones/ciudades.php', {
method: 'POST',
body: formData
})
.then(response => response.json())
.then(data => {
// Vacía la lista de opciones del elemento select
const selectCiudad = document.querySelector('#' + modal + ' #ciudad');
selectCiudad.innerHTML = '';

// Agrega las ciudades al elemento select
data.forEach(ciudad => {
const option = document.createElement('option');
option.value = ciudad.id_ciudad; // asumiendo que tus ciudades tienen un campo 'id'
option.text = ciudad.ciudad;
selectCiudad.add(option);
});

})
.catch(error => console.error(error));
});
});


    // -----------------------------------------------------
    // UPDATE
    // -----------------------------------------------------

$(document).ready(function(){
    $('#update_cliente').click(function(e){
        e.preventDefault();
        var id = $('#id').val();
        var cedula = $('#cedula').val();
        var nombre = $('#nombre').val();
        var telefono1 = $('#telefono1').val();
        var telefono2 = $('#telefono2').val();
        var direccion = $('#direccion').val();
        var estado = $('#estado').val();
        var ciudad = $('#ciudad').val();

        $.ajax({
            url: 'funciones/update_cliente.php',
            type: 'post',
            data: {
                id: id,
                cedula: cedula,
                nombre: nombre,
                telefono1: telefono1,
                telefono2: telefono2,
                direccion: direccion,
                estado: estado,
                ciudad: ciudad
            },
            success: function(response){
                bootbox.alert({
                message: response,
                callback: function(){
                        $('.modal').modal('hide'); // Esto cierra todos los modales
                    }
            });
            table.ajax.reload();      
    }

        });
    });
});


    // -----------------------------------------------------
    // GUARDAR
    // ----------------------------------------------------- 

$(document).ready(function(){
  $('.modal form').on('submit', function(e){
e.preventDefault();

// Obtiene el ID del modal actual
var modalId = $(this).closest('.modal').attr('id');

var cedula = $('#' + modalId + ' #cedula').val();
console.log("cedula: ",cedula);
var nombre = $('#' + modalId + ' #nombre').val();
var telefono1 = $('#' + modalId + ' #telefono1').val();
var telefono2 = $('#' + modalId + ' #telefono2').val();
var direccion = $('#' + modalId + ' #direccion').val();
var estado = $('#' + modalId + ' #estado').val(); // Assuming the ID is correct
var ciudad = $('#' + modalId + ' #ciudad').val();

if (estado === "Seleccione...") {
alert("Seleccione un estado válido.");
return; // Prevent form submission
}

console.log("Estado seleccionado:", estado); // Log the value for debugging

$.ajax({
url: 'funciones/guardar_cliente.php',
type: 'post',
data: {
cedula: cedula,
nombre: nombre,
telefono1: telefono1,
telefono2: telefono2,
direccion: direccion,
estado: estado,
ciudad: ciudad
},
success: function(response){
    bootbox.alert({
        message: response,
    callback: function(){
        setTimeout(function(){
        // Cierra solo el modal actual
        $('#' + modalId).modal('hide'); // Usar el ID del modal
        // Actualiza la pantalla
        table.ajax.reload(); // Refresh the table after saving
        // Elimina todos los fondos oscuros (overlays)
        $('.modal-backdrop').remove();
    }, 200);
}
}); 

// Mostrar la sección de la venta y ocultar la sección del cliente
$('#ventaSection').show();
//$('#clienteSection').hide();

},
error: function(response){ bootbox.alert('Error al guardar el cliente. Por favor, intente de nuevo.');
}
});
});
});


    // -----------------------------------------------------
    // TABLA A MOSTRAR MOSTRAR CONTENIDO DE TABLA APLICAR LOGICA DE OCULTARLA SI NO HAY DATOS
    // -----------------------------------------------------

$('#contenedorTabla').hide();
var table;
$(document).ready(function() {
  
    // Inicializar el table
    table = $('#ventas').DataTable({
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

    "buttons": [
        'copy', 'excel',   {
                    extend: 'print',
                    title: 'Reporte de Clientes',
                    messageTop: 'Mensaje TOP.',
                    messageBottom: null
                    
                },
        {
                    extend: 'pdf',
                    title: 'Reporte de Clientes',
                    messageTop: 'Mensaje TOP.',
                    messageBottom: null
                },
    ],
    "order": [[ 0, "desc" ]],
		"ajax": {
	 	"method":"POST",
	 	"url":"funciones/listar_ventas.php"
		 },
		 "columns":[
            {"data":"id_control"}, // ID de la venta
            {"data":"fecha"}, 
            {"data":"cedula"},
            {"data": "nombre_cliente"}, 
            {"data": "telefono1"},
            {"data": "total_productos"},
            {"data": "total_venta"}, 
            {"data": null, "defaultContent": "<button class='btn btn-info btn-sm ver-detalle' data-toggle='modal' data-target='#detalleModal'>Ver Detalle</button>"} 
        ],
    "initComplete": function(settings, json) { // Evento que se ejecuta al terminar de cargar la tabla
        
        if (table.data().count() > 0) {
          $('#alerta_datos').hide();
          $('#contenedorTabla').show();
        } else {
          $('#alerta_datos').show(); 
        }
      },

		drawCallback: function () {
				$('[data-toggle="popover"]').popover({
						"html": true,
						trigger: 'hover',
						placement: 'auto'

				})
		}
    });
});


    // -----------------------------------------------------
    // CONTROL BOTON EDITAR
    // -----------------------------------------------------

$('#ventas').on('click', '#editBtn', function() {
    var data = table.row($(this).parents('tr')).data();
    // Rellena el formulario del modal con los datos de la fila
    $('#id').val(data.id);
    $('#cedula').val(data.cedula);
    $('#nombre').val(data.nombre);
    $('#telefono1').val(data.telefono1);
    $('#telefono2').val(data.telefono2);
    $('#direccion').val(data.direccion);
    $('#estado').val(data.estado);

    // Disparar el evento 'change' en el select de estado para que se carguen las ciudades
    document.getElementById('estado').dispatchEvent(new Event('change'));

    // Espera a que se carguen las ciudades antes de seleccionar la ciudad correcta
    setTimeout(function() {
        $('#ciudad').val(data.ciudad);
    }, 500);

    $('#editModal').modal('show');
});



$(document).ready(function() {
    let cantidadesDisponibles = {};
    let cantidadesSeleccionadas = {};

    // -----------------------------------------------------
    // FUNCIONES
    // -----------------------------------------------------

    // Función para poblar el select de productos
    function poblarProductos(selectElement) {
        $.ajax({
            url: 'funciones/obtener_productos.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                selectElement.empty();
                selectElement.append('<option value="">Seleccione...</option>');

                if (data.tienePrecios) {
                    data.productos.forEach(function(producto) {
                        selectElement.append(
                            `<option value="${producto.id}"
                            data-precio="${producto.precio}"
                            data-cantidad-disponible="${producto.cantidad_disponible}">
                            ${producto.nombre} 
                            </option>`
                        );

         // cantidadesDisponibles[producto.id] = parseFloat(producto.cantidad_disponible.replace(/[^0-9.]/g, ''));
         // cantidadesSeleccionadas[producto.id] = 0;

// Solo inicializar la cantidadSeleccionada si no se ha establecido previamente
if (!cantidadesSeleccionadas.hasOwnProperty(producto.id)) { 
  cantidadesSeleccionadas[producto.id] = 0;
}  

cantidadesDisponibles[producto.id] = parseFloat(producto.cantidad_disponible.replace(/[^0-9.]/g, ''));



                    });
                } else {
                    $('#alerta').before(`
                        <div class="alert alert-danger" role="alert">
                            Debe crear su lista de precios personalizada <a href="precios.php">AQUI</a>
                        </div>
                    `);
                    $('#agregarProducto').prop('disabled', true);
                    $('#mostrar_lista').hide();
                }
              //  actualizarOpcionesSelect(); // Actualizar todos los selects después de poblar
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los productos: " + error);
            }
        });
    }

    // Función para actualizar las opciones del select
    function actualizarOpcionesSelect() {
    let productosSeleccionados = []; 
    $('.productoSelect').each(function() {
        let productoId = $(this).val();
        if (productoId) { 
            productosSeleccionados.push(productoId);
        } 
    }); 
 

    $('.productoSelect').each(function() {
        let selectElement = $(this);
        let selectedProductoId = selectElement.val();  

        // Obtener cantidad seleccionada para este producto
        let cantidadSeleccionada = cantidadesSeleccionadas[selectedProductoId] || 0; 
      //  let cantidadInicial = cantidadesDisponibles[selectedProductoId] || 0; 
        let cantidadDisponible = cantidadesDisponibles[selectedProductoId] - cantidadSeleccionada;

        // Actualizar solo el select actual 
        selectElement.find('option').each(function(index) { 
            let option = $(this); 
            let productoId = option.val();

            if (cantidadDisponible <= 0) {
                selectElement.find('option[value="' + selectedProductoId + '"]').prop('disabled', true); 
            } else {
                selectElement.find('option[value="' + selectedProductoId + '"]').prop('disabled', false); 
                // Actualizar la cantidad disponible
                selectElement.find('option[value="' + selectedProductoId + '"]').data('cantidad-disponible', cantidadDisponible); 
                selectElement.find('option[value="' + selectedProductoId + '"]').attr('data-cantidad-disponible', cantidadDisponible); 
            } 

           // Deshabilitar si el producto ya está seleccionado en OTRO select  
           if (productosSeleccionados.includes(productoId) && productoId !== selectedProductoId) { 
                option.prop('disabled', true);
            } else { 
                option.prop('disabled', false); 
            } 
        }); 
        selectElement.closest('.producto-item').find('.cantidad-disponible').text('Disponibles: ' + cantidadDisponible + ' Gramos');
    });
}

    // -----------------------------------------------------
    // Inicialización
    // -----------------------------------------------------

    // Poblar el select del primer producto al cargar la página
    poblarProductos($('#productosContainer .productoSelect:first'));

    // -----------------------------------------------------
    // Eventos
    // -----------------------------------------------------

    // Agregar nuevo producto al formulario
    $('#agregarProducto').click(function() {
        let nuevoProducto = `
          <div class="producto-item">
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="producto">Producto</label>
                <select class="form-control productoSelect" name="producto[]">
                  <option value="">Seleccione...</option> 
                </select>
              </div> 
              <div class="form-group col-md-5"> 
                <label for="cantidad">Cantidad en Gramos</label>
                <input type="text" class="form-control cantidadInput" name="cantidad[]" min="0.1" value="0" inputmode="numeric"> 
                <span class="cantidad-disponible">Disponibles: 0</span>
                <div class="invalid-feedback">La cantidad supera el stock disponible.</div>
              </div>  
              <div class="form-group col-md-1">
                <label for="precio" style="font-size: 20px;">$ x Kg</label>
                <span class="precio-producto" style="font-size: 20px;">0 $</span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                <label for="subtotal" style="font-size: 24px;">Subtotal</label>
                <span class="subtotal-producto" style="font-size: 24px;">0 $</span> 
              </div>
            </div>
            <div class="form-group col-md-12"> 
              <button type="button" class="btn btn-danger btn-sm eliminar-producto"><i class="far fa-trash-alt"></i></button> 
            </div> 
          </div>`;

        $('#productosContainer').append(nuevoProducto);

        let nuevoSelect = $('#productosContainer .productoSelect:last');
        poblarProductos(nuevoSelect); 
        nuevoSelect.trigger('change'); 
    });  

    // Eliminar producto
    $('#productosContainer').on('click', '.eliminar-producto', function() { 
        let productoEliminadoId = $(this).closest('.producto-item').find('.productoSelect').val();
        // Restaurar la cantidad seleccionada a 0 
        cantidadesSeleccionadas[productoEliminadoId] = 0;

        $(this).closest('.producto-item').remove(); 
        actualizarTotalVenta();
        actualizarOpcionesSelect();
    });

    // Manejar cambios en el select de productos
    $('#productosContainer').on('change', '.productoSelect', function() {
        let productoItem = $(this).closest('.producto-item');
        let selectElement = $(this); 
        let precio = parseFloat(selectElement.find('option:selected').data('precio'));
        let cantidadInput = productoItem.find('.cantidadInput'); 
        let cantidad = parseFloat(cantidadInput.val()) || 0; // Obtener la cantidad, si está vacía usar 0 
        let cantidadDisponible = parseFloat(selectElement.find('option:selected').data('cantidad-disponible')) || 0;

        productoItem.find('.precio-producto').text(precio.toFixed(2) + ' $');

        // Validar cantidad
        if (cantidad > cantidadDisponible) {
            cantidadInput.addClass('is-invalid'); 
        } else { 
            cantidadInput.removeClass('is-invalid'); 
            let subtotal = precio * cantidad;
            productoItem.find('.subtotal-producto').text(subtotal.toFixed(2) + ' $'); 
            actualizarTotalVenta(); 
        }

        // Actualizar el stock disponible 
        cantidadesSeleccionadas[selectElement.val()] = cantidad; 

        actualizarOpcionesSelect(); // Actualizar todos los selects 
    });

    // Manejar cambios en el input de cantidad
    //  $('#productosContainer').on('change input', '.productoSelect, .cantidadInput', function() {
        $('#productosContainer').on('change input', '.cantidadInput', function() {
          let productoItem = $(this).closest('.producto-item');
          let selectElement = productoItem.find('.productoSelect');
          let precio = parseFloat(selectElement.find('option:selected').data('precio')); 
          let cantidad = parseFloat($(this).val()); 
          let productoId = selectElement.val(); 

          // Obtener la cantidad disponible ACTUALIZADA 
          let cantidadDisponible = cantidadesDisponibles[productoId] - (cantidadesSeleccionadas[productoId] || 0); 

          //  Actualizar la cantidad seleccionada
          cantidadesSeleccionadas[productoId] = cantidad; 

        // Validar que la cantidad no sea mayor que la cantidad disponible
            if (cantidad > cantidadDisponible) {
            $(this).addClass('is-invalid'); 
            } else {
            $(this).removeClass('is-invalid');
            }


        
        // Verificar si el precio es un número válido
        if (!isNaN(precio)) { 
          let subtotal = precio * cantidad;
          subtotal = subtotal/1000;
          productoItem.find('.subtotal-producto').text(subtotal.toFixed(2) + ' $');
          //  Mostrar cantidad disponible
          productoItem.find('.cantidad-disponible').text('Disponibles: ' + cantidadDisponible + ' Gramos');
          actualizarTotalVenta();
          actualizarOpcionesSelect(); 

        } else {
            // Si no hay precio, establecer precio y subtotal a cero
            productoItem.find('.precio-producto').text("0.00 $");
            productoItem.find('.subtotal-producto').text("0.00 $");
            actualizarTotalVenta(); 
        }


    });  
 
  // Actualizar el total de la venta
  function actualizarTotalVenta() { 
    let total = 0;
    $('.subtotal-producto').each(function() { 
      let subtotalText = $(this).text().replace(' $', ''); // Eliminar "$" para el cálculo 
      total += parseFloat(subtotalText) || 0;  // Manejar valores vacíos
    });
    $('#totalVenta').text(total.toFixed(2) + ' $');
  } 



    // -----------------------------------------------------
    // GUARDAR VENTA
    // -----------------------------------------------------
    
    // Guardar la venta con AJAX
  $('#guardarVenta').click(function(e) {
  e.preventDefault(); 

  //console.log(idClienteGlobal);
  console.log('idClienteGlobal:', idClienteGlobal); // Depurar el valor de idClienteGlobal

  // Obtener productos, cantidades y precios 
  let productos = [];
  let cantidades = [];
  let montos = [];

  //AGARRAR DATOS QUE SE VAN A ENVIAR DE LA VENTA
// Crear un objeto FormData para enviar la información de la venta 
  let formData = new FormData(); 


  $('#productosContainer .producto-item').each(function() {
    let productoId = $(this).find('.productoSelect').val();
    let cantidad = parseFloat($(this).find('.cantidadInput').val());
    let precio = parseFloat($(this).find('.precio-producto').text().replace(' $', '')); 
    let monto = (cantidad * precio)/1000;

    productos.push(productoId);
    cantidades.push(cantidad);
   // precios.push(precio); 
    montos.push(monto);
  }); 

  formData.append('productos', JSON.stringify(productos));
  formData.append('cantidades', JSON.stringify(cantidades));
  //formData.append('precios', JSON.stringify(precios));
  formData.append('montos', JSON.stringify(montos));
  formData.append('id_cliente', idClienteGlobal); // <--- Usa idClienteGlobal 


  // Agrega esto para inspeccionar el contenido de formData
  for (var pair of formData.entries()) {
    console.log(pair[0]+ ', ' + pair[1]); 
  }

  // AJAX request 
  $.ajax({
    url: 'funciones/guardar_venta.php',
    type: 'POST',
    data: formData,
    processData: false, // Importante para FormData
    contentType: false, // Importante para FormData
    success: function(response, textStatus, xhr) {
      console.log('Respuesta del servidor:', response); // Verifica la respuesta
      console.log('Estado HTTP:', xhr.status); // Verifica el estado HTTP


      // Manejar la respuesta del servidor
      if (response === "Venta guardada correctamente") {
        // Mostrar mensaje de éxito
        bootbox.alert({
          message: 'Venta registrada con exito', 
          callback: function() { 
              // Actualiza la tabla y cierra los modales DENTRO del callback
              table.ajax.reload(); 
              $('.modal').modal('hide'); 
              // Reinicia el formulario  
              $('#ventaForm')[0].reset();  
              $('#ventaSection').hide();
              // Elimina todas las líneas de productos adicionales
              $('#productosContainer .producto-item:not(:first)').remove();
              // Reinicia los objetos de cantidades 
              cantidadesDisponibles = {}; 
              cantidadesSeleccionadas = {};
              // Recarga el primer select de producto
              poblarProductos($('#productosContainer .productoSelect:first'));
          }
        }); 
      } else {
        bootbox.alert(response); 
      } 
    },
     error: function(xhr, status, error) {
      console.error('Error al enviar la petición al servidor:', error); 
      bootbox.alert('Error al enviar la petición al servidor: ' + error); 
    }
  });
});


  // Limpiar lista de productos al hacer clic en "Borrar Lote"
  $('#limpiar_todo').click(function() {
    // Eliminar todos los productos adicionales, dejando solo el primero
    $('#productosContainer .producto-item:not(:first)').remove();

    // Reiniciar el formulario 
    $('#ventaForm')[0].reset();
    $('#ventaSection').hide(); // Ocultar la sección de venta
  });

  // Manejar clic en el botón "Ver Detalle"
$('#ventas tbody').on('click', '.ver-detalle', function () {
    var data = table.row($(this).parents('tr')).data();
    var idVenta = data.id;

    $.ajax({
        url: 'funciones/obtener_detalle_venta.php',
        type: 'POST',
        data: { idVenta: idVenta },
        success: function(response) {
            $('#detalleVenta').html(response); 
        },
        error: function() {
            alert('Error al obtener los detalles de la venta.');
        }
    });
}); 

}); 


// Dentro del script donde se define la tabla
$('#crearModal').on('hidden.bs.modal', function () {
    table.ajax.reload();
     $('#ventaForm')[0].reset();  
     $('#ventaSection').hide();
     // Elimina todas las líneas de productos adicionales
     $('#productosContainer .producto-item:not(:first)').remove();
     // Reinicia los objetos de cantidades 
     cantidadesDisponibles = {}; 
     cantidadesSeleccionadas = {};
     // Recarga el primer select de producto
    // poblarProductos($('#productosContainer .productoSelect:first'));

    if (typeof poblarProductos === 'function') {
  // Recarga el primer select de producto
  poblarProductos($('#productosContainer .productoSelect:first')); 
} else {
  // Manejar el caso en que la función no está definida
  // (puedes dejarlo en blanco o realizar otra acción)
  console.warn("La función poblarProductos no está definida."); 
}


});

$('#cerrar').click(function(e){
    table.ajax.reload();
     $('#ventaForm')[0].reset();  
     $('#ventaSection').hide();
     // Elimina todas las líneas de productos adicionales
     $('#productosContainer .producto-item:not(:first)').remove();
     // Reinicia los objetos de cantidades 
     cantidadesDisponibles = {}; 
     cantidadesSeleccionadas = {};
     // Recarga el primer select de producto
   //  poblarProductos($('#productosContainer .productoSelect:first'));

   if (typeof poblarProductos === 'function') {
  // Recarga el primer select de producto
  poblarProductos($('#productosContainer .productoSelect:first')); 
} else {
  // Manejar el caso en que la función no está definida
  // (puedes dejarlo en blanco o realizar otra acción)
  console.warn("La función poblarProductos no está definida."); 
}


});



</script>

<?php include("includes/footer.php"); ?>