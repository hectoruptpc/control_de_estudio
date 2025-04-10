<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$operador = "Clientes";
$titulopag = "Clientes";
include('../funciones/functions.php');

?>

<?php include("includes/head.php"); ?>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#crearModal">
<i class="fas fa-user-check"></i> Crear Cliente
</button>

<div id="contenedorTabla">

<table id="clientes" class="table-striped table-bordered table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Telefono1</th>
                <th>Telefono2</th>
                <th>Direccion</th>
     
                <th>Fecha Ingreso</th>
                <th>Fecha Update</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cedula</th>
                <th>Nombre</th>
                <th>Telefono1</th>
                <th>Telefono2</th>
                <th>Direccion</th>
                
                <th>Fecha Ingreso</th>
                <th>Fecha Update</th>
                <th>Accion</th>
            </tr>
        </thead>
    </table>


<div class="container-fluid">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Grafico</h1>

        </div>
      </div>
      <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
</div>


<div id="alerta_datos" class="alert alert-danger mt-5" role="alert" >
<h3 class="text-uppercase">
<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
</h3>
</div>


<!-- MODAL EDITAR -->
<div id="editModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
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
        <button type="submit" class="btn btn-primary" id="update_cliente" name="update_cliente"><i class="fas fa-user-edit"></i> Guardar</button>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CREAR -->
<div id="crearModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Crear Cliente</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
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
        <button type="submit" class="btn btn-primary" id="guardar_cliente" name="guardar_cliente" ><i class="fas fa-save"></i> Guardar</button>
    </form>

      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>




      
<script src="../funciones/js/feather.min.js"></script>
<script src="../funciones/js/Chart.min.js"></script>
      
    


<script>
    $('#contenedorTabla').hide();

    // DEBO CORREGIR EL NOMBRE DEL DROPDOWN
    $('.mes').on('click', function(e) {
    e.preventDefault(); // Prevent default link behavior

    var period = $(this).data('value'); // Get the 'monthly' or 'annual' value
    updateChart(period);

    // **New code for dropdown functionality:**
    if (!$(this).parent().closest('.dropdown').hasClass('show')) {
        $(this).closest('.dropdown').find('.dropdown-toggle').first().click();
    }
});

function updateChart(period) {
    // Your code to update the chart based on the period
    console.log('Updating chart for ' + period + ' data...');
}


$(document).ready(function(){
    $('.btn-outline-secondary').click(function(){
        var action = $(this).text();
        switch(action) {
            case 'Compartir':
                // Aquí va tu código para compartir el gráfico.
                // Esto podría implicar copiar un enlace al gráfico en el portapapeles,
                // o abrir una ventana de diálogo para compartir en redes sociales.
                console.log('Sharing the chart...');
                break;
            case 'Exportar':
                // Aquí va tu código para exportar el gráfico.
                // Esto podría implicar generar una imagen del gráfico y descargarla.
                console.log('Exporting the chart...');
                break;
            default:
                console.log('Unknown action: ' + action);
        }
    });
});

$(document).ready(function(){
  $.ajax({
    url: 'funciones/listar_clientes_chart.php',
    type: 'GET',
    success: function(response) {
        let data = JSON.parse(response);
        let fechas = data.data.map(item => item.month);
        let clientes = data.data.map(item => item.count);

        let chartData = {
            labels: fechas,
            datasets: [{
                label: 'Registros',
                data: clientes,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 3
            }]
        };

        const ctx = document.getElementById('myChart');
        const stackedLine = new Chart(ctx, {
          type: 'line',
          data: chartData,
          options: {
            scales: {
              y: {
                stacked: true
              }
            }
          }
        });
    },
    error: function(error) {
        console.log(error);
    }
});
});

  

document.getElementById('guardar_cliente').style.display = 'none';
let timer;
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
              console.log(cliente);
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
  if (!cliente || (Object.keys(cliente).length === 0 && cliente.constructor === Object)) {
                        document.getElementById('guardar_cliente').style.display = 'block';
                    } else {
                        document.getElementById('guardar_cliente').style.display = 'none';
                    }
  });
    }, 900);
});


// Código para el modal de creación y edicion
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
console.log(estado);
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


// UPDATE

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


 
// GUARDAR
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
                // $(this).closest('.modal').modal('hide'); // Usar el padre del formulario
                
                // Actualiza la pantalla
                table.ajax.reload(); // Refresh the table after saving

                   // Elimina todos los fondos oscuros (overlays)
    $('.modal-backdrop').remove();

            }, 200);
        }
            });
   //         table.ajax.reload();
        
    },
error: function(jqXHR, textStatus, errorThrown) { // Handle errors
console.error('Error saving client:', textStatus, errorThrown);
bootbox.alert('Error al guardar el cliente. Por favor, intente de nuevo.');
}
});
});
});






// TABLA A MOSTRAR
var table;
$(document).ready(function() {
    // Inicializar el table
    table = $('#clientes').DataTable({
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
	 	"url":"funciones/listar_clientes.php"
		 },
		"columns":[
        {"data":"id"},
        {"data":"cedula"},
        {"data": "nombre"},
        {"data": "telefono1"},
        {"data": "telefono2"},
        {"data": "direccion"},
        // {"data": "estado"},
        // {"data": "ciudad"},
        {"data": "fecha_registro"},
        {"data": "fecha_update"},
        {"data": null, "defaultContent": `<button class='btn btn-primary' id='editBtn'><i class="fas fa-user-edit"></i> Editar</button>`}

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



$('#clientes').on('click', '#editBtn', function() {
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


    </script>

<?php include("includes/footer.php"); ?>