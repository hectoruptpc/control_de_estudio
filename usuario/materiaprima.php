<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$operador = "Materia Prima";
$titulopag = "Materia Prima";
include('../funciones/functions.php');

?>

<?php include("includes/head.php"); ?>

<h1><?php echo $titulopag; ?></h1>


<?php
$query = "SELECT ic.id,
               c.nombre AS Componente,
               ic.cantidad AS cantidad_original,  -- Almacenamos la cantidad original
               CASE 
                   WHEN ic.cantidad >= 1000 THEN ic.cantidad / 1000 
                   ELSE ic.cantidad 
               END AS cantidad_final,  -- Cantidad final para mostrar
               CASE WHEN ic.descripcion = 0 THEN 'USADO' ELSE 'INGRESO' END AS Descripcion,
               ic.fecha AS Fecha,
               c.tipo AS tipo_componente
        FROM inventario_componente ic
        JOIN componentes c ON ic.id_componente = c.codigo
        WHERE id_usuario = '$id_usua';";
$result = mysqli_query($db, $query);
$row =  mysqli_num_rows($result);
if ($row > 0) :

    // id
    // id_componente
    // cantidad
    // descripcion
        // 1 INGRESO
        // 0 SALIDA
    // fecha
?>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#crearModal">
<i class="fas fa-vial"></i>
  Ingresar Materia Prima 
</button>

<!-- Button trigger modal -->
<button type="button" class="btn btn-info mb-3" data-toggle="modal" data-target="#crearLoteModal">
<i class="fas fa-vials"></i>
  Ingresar Lote
</button>



<h2>MOVIMIENTO DE INVENTARIO</h2>

<select id="filtroDescripcion">
    <option value="">Todos</option>
    <option value="USADO">USADO</option>
    <option value="INGRESO">INGRESO</option>
</select>

<!-- button id="applyFilterBtn">Aplicar Filtro</button !-->

<table id="inventario_componentes" class="table-striped table-bordered table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Componente</th>
                <th>Cantidad</th>
                <th>Descripcion</th>
                <th>Fecha</th>
                <th>Editar</th>
              
            </tr>
        </thead>
        <tbody>
           
        </tbody>
        <thead>
            <tr>
            <th>ID</th>
                <th>Componente</th>
                <th>Cantidad</th>
                <th>Descripcion</th>
                <th>Fecha</th>
                <th>Editar</th>
               
            </tr>
        </thead>
    </table>
    <?php else :  ?>


<div class="alert alert-danger mt-5" role="alert" >
<h3 class="text-uppercase">
<i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
</h3>
</div>

  <?php  endif ?>


<!-- MODAL CREAR -->
<div id="crearModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ingresar Materia Prima</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="funciones/guardar_materia_prima.php"> 
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="idControl">ID Control/Número de Factura</label>
              <input required  type="text" class="form-control" id="idControl" name="idControl">
            </div>
          </div>
          <div class="form-group">
            <label for="idComponente">Materia Prima</label>
            <select class="form-control" id="idComponente" name="idComponente">
              <option value="">Seleccione...</option>
              <!-- Las opciones se cargarán aquí con JavaScript -->
            </select>
          </div>
          <div class="form-group">
            <label for="cantidad">Cantidad en Gramos</label>
            <input required  type="number" class="form-control" id="cantidad" name="cantidad">
          </div>
          <button type="submit" class="btn btn-primary" id="guardarMateriaPrima" name="guardarMateriaPrima"> <i class="fas fa-upload"></i> Guardar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<!-- MODAL CREAR  LOTE-->
<div id="crearLoteModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Ingresar Lote Materia Prima</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="funciones/guardar_lote_materia_prima.php" id="formularioLote"> 
        <div class="form-row">
            <div class="form-group col-md-6">
              <label for="idControl">ID Control/Número de Factura</label>
              <input required  type="text" class="form-control" id="idControl" name="idControl">
            </div>
          </div>
          
          <div id="productosContainer">
            <div class="producto-item">
              <div class="form-row"> 
                <div class="form-group col-md-6">
                  <label for="idComponenteLote">Materia Prima</label>
                  <select class="form-control" id="idComponenteLote" name="idComponenteLote[]">
                    <option value="">Seleccione...</option>
                    <!-- Las opciones se cargarán aquí con JavaScript -->
                  </select>
                </div>
                <div class="form-group col-md-5">
                  <label for="cantidadLote">Cantidad en Gramos</label>
                  <input required  type="number" class="form-control" id="cantidadLote" name="cantidadLote[]">
                </div>
                <div class="form-group col-md-1"> 
                  
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="btn btn-secondary" id="agregarProducto"> <i class="fas fa-plus-circle"></i> Agregar Otro Producto</button>
          <button type="submit" class="btn btn-success" id="guardarMateriaPrima" name="guardarMateriaPrima"> <i class="fas fa-upload"></i> Guardar Lote</button>
          <button type="button" class="btn btn-danger" id="limpiarLote" name="limpiarLote"> <i class="fas fa-trash-alt"></i> Borrar Lote</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- MODAL EDITAR -->
<div id="editarModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Editar Materia Prima</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="funciones/update_inventario_materiaprima.php"> 
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="idControl">ID Control/Número de Factura</label>
              <input required  type="text" class="form-control" id="editaridControl" name="editaridControl">
            </div>
          </div>
          <div class="form-group">
            <label for="idComponente">Materia Prima</label>
            <select class="form-control" id="editaridComponente" name="editaridComponente">
              <option value="">Seleccione...</option>
              <!-- Las opciones se cargarán aquí con JavaScript -->
            </select>
          </div>
          <div class="form-group">
            <label for="cantidad">Cantidad en Gramos</label>
            <input required  type="number" class="form-control" id="editarcantidad" name="editarcantidad">
          </div>
          <input required  type="hidden" id="id" name="id">
          <button type="submit" class="btn btn-primary" id="guardarEditarMateriaPrima" name="guardarEditarMateriaPrima"> <i class="fas fa-upload"></i> Editar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<?php
$query = "SELECT
    c.nombre AS Componente,
    c.tipo AS tipo_componente,
    SUM(CASE WHEN ic.descripcion = '1' THEN ic.cantidad ELSE 0 END) - 
    SUM(CASE WHEN ic.descripcion = '0' THEN ic.cantidad ELSE 0 END) AS CantidadTotal
FROM componentes c
LEFT JOIN inventario_componente ic ON c.codigo = ic.id_componente AND ic.id_usuario = '$id_usua'
GROUP BY c.codigo, c.nombre, c.tipo
HAVING CantidadTotal > 0;";

$result = mysqli_query($db, $query);

if ($result && mysqli_num_rows($result) > 0) : 
?>

<div class="alert alert-warning">
<h2>INVENTARIO REAL</h2>

<table id="inventario_real_componentes" class="table-striped table-bordered table-hover" style="width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Componente</th>
            <th>Cantidad</th>              
        </tr>
    </thead>
    <tbody>
        <?php 
        $id = 1;
            while ($row = mysqli_fetch_assoc($result)) {


    $cantidad_original = $row['CantidadTotal'];
    $tipo_componente = $row['tipo_componente'];
    $cantidad_final = $cantidad_original; 
    $unidad = ""; 

    if ($tipo_componente == 'liq') {
        if ($cantidad_original >= 1000) {
            $cantidad_final = $cantidad_original / 1000; 
            $unidad = ($cantidad_final > 1) ? "Litros" : "Litro"; 
        } else {
            $unidad =  "mililitro" . ($cantidad_original > 1 ? "s" : "");
        }
    } else if ($tipo_componente == 'sol') {
        if ($cantidad_original >= 1000) {
            $cantidad_final = $cantidad_original / 1000; 
            $unidad = ($cantidad_final > 1) ? "Kilos" : "Kilo";
        } else {
            $unidad =  "gramo" . ($cantidad_original > 1 ? "s" : ""); 
        }
    }

    $cantidad_final = round($cantidad_final, 2); 


                echo "<tr>";
                echo "<td>" . $id . "</td>";
                echo "<td>" . $row['Componente'] . "</td>";
                echo "<td>" . $cantidad_final . " " . $unidad  . "</td>";
                echo "</tr>";
                $id++;
            }
        ?>
    </tbody>
</table>

</div>

<?php else :  ?>

<div class="alert alert-danger mt-5" role="alert" >
    <h3 class="text-uppercase">
        <i class="fa fa-exclamation-triangle"></i> En este momento No hay datos que Mostrar
    </h3>
</div>

<?php endif; ?>


<?php include("includes/footer.php"); ?>

<script>



var table;
var table2;

//TABLA 1
$(document).ready(function() {
    // Inicializar el table
    table = $('#inventario_componentes').DataTable({
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
                        title: 'Reporte de Usos de Materia Prima',
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
                        title: 'Reporte de Usos de Materia Prima',
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
        "order": [[ 0, "desc" ]],
        "ajax": {
            "method":"POST",
            "url":"funciones/inventario_materia_prima.php"
        },
        "columns":[
            {"data":"id"},
            {"data":"nombre_componente"},
            {"data": "cantidad"},
            {"data": "descripcion"},
            {"data": "fecha"},
            {"data": null, "defaultContent": "<button class='btn btn-primary' id='editBtn'>Editar</button>"}

        ],
        drawCallback: function () {
            $('[data-toggle="popover"]').popover({
                "html": true,
                trigger: 'hover',
                placement: 'auto'

            })
        },
        layout: {
            top1: {
                searchBuilder: {
                    conditions: {
                        num: {
                            MultipleOf: {
                                conditionName: 'Múltiplo de', 
                                init: function (that, fn, preDefined = null) {
                                    var el = document.createElement('input');
                                    el.addEventListener('input', function () {
                                        fn(that, this);
                                    });

                                    if (preDefined !== null) {
                                        el.value = preDefined[0];
                                    }

                                    return el;
                                },
                                inputValue: function (el) {
                                    return el[0].value;
                                },
                                isInputValid: function (el, that) {
                                    return el[0].value.length !== 0;
                                },
                                search: function (value, comparison) {
                                    return value % comparison === 0;
                                }
                            }
                        }
                    },
                    
                    // Agregar un botón para aplicar el filtro
                    button: {
                        label: 'Aplicar Filtro',
                        action: function (e, dt, node, config) {
                            dt.draw();
                        }
                    }
                }
            }
        }
    });
    $('#applyFilterBtn').click(function() {
        // Obtener la instancia de SearchBuilder
        var searchBuilder = table.searchBuilder();

        // Aplicar los filtros de SearchBuilder
        searchBuilder.search();

        // Volver a dibujar la tabla
        table.draw();
    });
});


// TABLA 2
$(document).ready(function() {
   // Inicializar el table
   table2 = $('#inventario_real_componentes').DataTable({

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
                        title: 'Reporte de Inventario Real de Componentes',
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
                        title: 'Reporte de Inventario Real de Componentes',
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
    "order": [[ 0, "desc" ]],
    "ajax": {
        "method": "POST", // O "GET" según tu configuración
        "url": "funciones/obtener_inventario_real.php", // Ajusta la ruta si es necesario  
      },
    "columns": [
      {"data":"id_tabla_real"},
      {"data":"Componente_real"},
      {"data":"Cantidad_real"}
    ]
});  
});

// RECARGA TABLA DE INVENTARIO REAL
function recargarTablaInventarioReal() {
    table2.ajax.reload();
}

// SELECTOR DEL FILTRO
var selectFiltro = $('#filtroDescripcion');
// Maneja el cambio en el select de filtro
selectFiltro.on('change', function() {
    var valorFiltro = $(this).val();
    table.column(3) // Índice de la columna "descripcion"
        .search(valorFiltro ? '^' + valorFiltro + '$' : '', true, false)
        .draw();
});

// Función para poblar un selector con los componentes
function poblarSelectorComponente(selectorId) {
  var selector = $(selectorId);
  selector.empty(); // Limpiar opciones existentes
  // Agregar opción "Seleccionar" por defecto
  selector.append('<option value="">Seleccionar</option>');

  $.ajax({
    url: 'funciones/get_componentes.php', 
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      data.forEach(function(componente) {
        if (componente.nombre.trim() !== "") { 
          selector.append('<option value="' + componente.codigo + '">' + componente.nombre + '</option>');
        }
      });
    },
    error: function(xhr, status, error) {
      console.error("Error al obtener los componentes:", error);
    }
  });
}

// Poblar los selectores al cargar la página
$(document).ready(function() {
  poblarSelectorComponente('#idComponente');
  poblarSelectorComponente('#idComponenteLote');
  poblarSelectorComponente('#editaridComponente'); 
});

// SUMIT DE GUARDAR

$(document).ready(function() {
    // Agrega un evento para enviar el formulario al hacer clic en el botón "Guardar"
  $('#guardarMateriaPrima').parent('form').on('submit', function(event) {
    event.preventDefault(); // Evita que se recargue la página

    // Validación adicional (opcional)
    if ($('#idControl').val() === "" || $('#idComponente').val() === "" || $('#cantidad').val() === "") {
      alert("Por favor, completa todos los campos.");
      return;
    }

    // Obtiene los valores del formulario
    var idControl = $('#idControl').val();
    var idComponente = $('#idComponente').val(); // Corregido el nombre del campo
    var cantidad = $('#cantidad').val();
    console.log("idComponente:", idComponente); // Agrega esto para depurar

    // Envía la solicitud AJAX
    $.ajax({
      url: 'funciones/guardar_materia_prima.php', // Ruta al archivo PHP
      type: 'POST', // Usa POST para enviar datos
      data: {
        idControl: idControl,
        idComponente: idComponente,
        cantidad: cantidad
      },
      success: function(response) {
        // Maneja la respuesta del servidor
        alert(response); // Muestra un mensaje de éxito o error
        $('#crearModal').modal('hide'); // Cierra el modal
        // Actualiza la tabla DataTable (si la tienes)
        table.ajax.reload(); // Recarga la tabla DataTable
        recargarTablaInventarioReal(); // Recarga la segunda tabla
      },
      error: function(xhr, status, error) {
        console.error("Error al guardar la materia prima:", error);
      }
    });
  });
});

// EDITAR MATERIA PRIMA
$('#inventario_componentes').on('click', '#editBtn', function () {
  var data = table.row($(this).parents('tr')).data(); 
  $('#editaridControl').val(data.ID_Control); 
  $('#editaridComponente').val(data.ID_Componente);
  $('#editarcantidad').val(data.cantidad_original);
  $('#id').val(data.id); // Agrega el ID del registro al campo oculto
  $('#editarModal').modal('show');
  console.log(table.row($(this).parents('tr')).data());


});

// Maneja el evento submit del formulario
$('#guardarEditarMateriaPrima').parent('form').on('submit', function(event) {
  event.preventDefault(); // Evita que se recargue la página

 // Validación adicional (opcional)
 if ($('#editaridControl').val() === "" || $('#editaridComponente').val() === "" || $('#editarcantidad').val() === "") {
    alert("Por favor, completa todos los campos.");
    return;
  }

  // Obtiene los valores del formulario
  var idControl = $('#editaridControl').val();
  var idComponente = $('#editaridComponente').val(); 
  var cantidad = $('#editarcantidad').val();
  var id = $('#id').val(); // Obtiene el ID del registro

 // Envía la solicitud AJAX
 $.ajax({
    url: 'funciones/update_inventario_materiaprima.php', // Ruta al archivo PHP
    type: 'POST', // Usa POST para enviar datos
    data: {
      idControl: idControl,
      idComponente: idComponente,
      cantidad: cantidad,
      id: id  // Incluye el ID del registro
    },
    success: function(response) {
      // Maneja la respuesta del servidor
      alert(response); // Muestra un mensaje de éxito o error
      $('#editarModal').modal('hide'); // Cierra el modal
      // Actualiza la tabla DataTable (si la tienes)
      table.ajax.reload(); // Recarga la tabla DataTable
      recargarTablaInventarioReal(); // Recarga la segunda tabla
    },
    error: function(xhr, status, error) {
      console.error("Error al actualizar la materia prima:", error);
    }
  });
});

// ENVIO POR LOTE
$(document).ready(function() {
  // ... (tu código para poblar el selector idComponenteLote) ...

    // Agregar nuevo producto al formulario
    let productoCount = 1;
  $('#agregarProducto').click(function() {
    productoCount++;

      // Crear el HTML del nuevo producto SIN el botón "X"
      let nuevoProducto = `
      <div class="producto-item">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="idComponenteLote">Materia Prima</label>
            <select class="form-control" id="idComponenteLote_${productoCount}" name="idComponenteLote[]">
              <option value="">Seleccione...</option>
              <!-- Las opciones se cargarán aquí con JavaScript -->
            </select>
          </div>
          <div class="form-group col-md-5">
            <label for="cantidadLote">Cantidad en Gramos</label>
            <input required  type="number" class="form-control" id="cantidadLote_${productoCount}" name="cantidadLote[]">
          </div>
          ${productoCount > 1 ? // Agregar botón "X" solo si no es el primer producto
            `<div class="form-group col-md-1">
              <button type="button" class="btn btn-danger btn-sm eliminar-producto" style="margin-top: 32px;"><i class="far fa-trash-alt"></i></button>
            </div>`
          : ''} 
        </div>
      </div>
    `; 

     // Agregar el nuevo producto al contenedor
    $('#productosContainer').append(nuevoProducto);
    
    // Poblar el nuevo selector con las opciones de materia prima
    poblarSelectorComponente(`#idComponenteLote_${productoCount}`);
  });

   // Eliminar producto al hacer clic en el botón "X"
   $('#productosContainer').on('click', '.eliminar-producto', function() {
    $(this).closest('.producto-item').remove(); 
  });


  // Manejar el envío del formulario con AJAX
  $('#formularioLote').submit(function(event) {
    event.preventDefault(); // Evitar el envío tradicional del formulario

    // Obtener datos del formulario
    var formData = $(this).serialize(); 

    let isValid = true; // Asumir que el formulario es válido

// Validar cada producto individualmente
$('.producto-item').each(function() {
  let componente = $(this).find('select[name="idComponenteLote[]"]').val();
  let cantidad = $(this).find('input[name="cantidadLote[]"]').val();

  if (componente === "" || cantidad === "" || cantidad <= 0) {
    isValid = false;
    alert("Por favor, completa todos los campos y asegúrate de que las cantidades sean mayores que cero.");
    return false; // Salir del bucle each si hay un error
  }
});

if (isValid) { 

    $.ajax({
      url: 'funciones/guardar_lote_materia_prima.php', 
      type: 'POST',
      data: formData, 
      success: function(response) {
        // Manejar la respuesta del servidor
        console.log(response); 
        alert(response); // Mostrar la respuesta en un alert
        $('#crearLoteModal').modal('hide'); // Cerrar el modal

        // Reiniciar el formulario
        $('#formularioLote')[0].reset();

        // Eliminar productos adicionales, dejando solo el primero
        $('#productosContainer .producto-item:not(:first)').remove();
        
        // Puedes recargar la tabla principal o realizar otras acciones 
        // después de guardar el lote.
        table.ajax.reload(); // Recarga la tabla DataTable
        recargarTablaInventarioReal(); // Recarga la segunda tabla
      },
      error: function(xhr, status, error) {
        // Manejar errores de la solicitud AJAX
        console.error("Error al enviar el formulario: " + error);
        alert("Error al guardar el lote. Por favor, inténtalo de nuevo."); 
      }
      
    });
  } 
  });

  // Limpiar lista de productos al hacer clic en "Borrar Lote"
  $('#limpiarLote').click(function() {
    // Eliminar todos los productos adicionales, dejando solo el primero
    $('#productosContainer .producto-item:not(:first)').remove();

    // Reiniciar el formulario (esto también limpia los valores del primer producto)
    $('#formularioLote')[0].reset(); 
  });


});



</script>