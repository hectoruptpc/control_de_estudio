// IDIOMA ESPAÑOL

var idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}

$(document).on('keydown', function(e) {
  if (e.keyCode === 27) { //  27 es el código para la tecla Esc
    $('.modal.show').modal('hide'); // Cierra solo los modales visibles
  }
});


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


document.addEventListener('DOMContentLoaded', function() {
  // Obtén el botón de impresión
  var botonImprimir = document.getElementById('botonImprimir');

  // Agrega un EventListener al botón
  botonImprimir.addEventListener('click', function() {
    printDiv('areaImprimir');
    $('.modal').modal('hide');
  });
});

function printDiv(nombreDiv) {
  var contenido = document.getElementById(nombreDiv).innerHTML;
  var contenidoOriginal = document.body.innerHTML;

  document.body.innerHTML = contenido;
  window.print();
  document.body.innerHTML = contenidoOriginal;

    // Cierra el modal después de imprimir
    $('.modal').modal('hide');
    // Reinicia la página después de imprimir
    location.reload();
}