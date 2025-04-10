<?php
// Activar el reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Incluir funciones comunes
$titulopag = "Gestor de Contenidos";
include('../funciones/functions.php');

// Incluir el encabezado HTML
include("includes/head.php");
?>

<div class="container">

    <h2>Contenidos</h2>
    <br>
    <b>Para incluir en un php: </b>
    contenido('nombre_contenido')
    <br>

    <!-- Mostrar mensajes de notificación -->
    <?php if (isset($_SESSION['gestor_contenido'])) : ?>
        <div class="alert alert-danger" role="alert">
            <h3>
                <?php
                echo $_SESSION['gestor_contenido'];
                unset($_SESSION['gestor_contenido']);
                ?>
            </h3>
        </div>
    <?php endif ?>

    <!-- Modal para crear un nuevo registro -->
    <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo Contenido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="completeseccion">Sección</label>
                        <input type="text" class="form-control" id="completeseccion" placeholder="Ingresa la sección">
                    </div>
                    <div class="form-group">
                        <label for="completcontenido">Contenido</label>
                        <textarea type="contenido" class="form-control" id="completcontenido" placeholder="Ingresa el contenido"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" onclick="addcontenido()">Enviar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar un registro -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualizar Contenido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="updateseccion">Sección</label>
                        <input type="text" class="form-control" id="updateseccion" placeholder="Ingresa la sección">
                    </div>
                    <div class="form-group">
                        <label for="updatecontenido">Contenido</label>
                        <textarea class="form-control" id="updatecontenido" placeholder="Ingresa el contenido"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" onclick="updateDetails()">Actualizar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <input type="hidden" id="hiddendata">
                </div>
            </div>
        </div>
    </div>

    <div class="container my-3">
        <h1 class="text-center">Contenidos</h1>
        <button type="button" class="btn btn-info my-3" data-toggle="modal" data-target="#completeModal">
            Agregar nuevo Contenido
        </button>
        <div id="displayDataTable"></div>
    </div>

</div>

<!-- Incluir el pie de página HTML -->
<?php include("includes/footer.php"); ?>

<script>

 

$.getScript('../funciones/summernote-0.8.18-dist/summernote-bs4.min.js', function () 
{
$('#updatecontenido').summernote();
$('#completcontenido').summernote({
      lang: 'es-ES',
      placeholder: 'Ingrese su contenido',
      tabsize: 2,
      height: 300
    });
}); 


    $(document).ready(function() {
        displayData();
    });

    // Función para mostrar los datos en la tabla
    function displayData() {
        $.ajax({
            url: "contenido/crud/display.php",
            type: 'post',
            data: { displaySend: 'true' }, // Agrega la variable aquí
            success: function(data, status) {
                //console.log('Lo que se Imprime es: ',data); // Imprime la respuesta AJAX en la consola
                $('#displayDataTable').html(data);
            }
        });
    }

    // Función para agregar un nuevo contenido
    function addcontenido() {
        var seccionAdd = $('#completeseccion').val();
        var contenidoAdd = $('#completcontenido').val();

        $.ajax({
            url: "contenido/crud/insert.php",
            type: 'post',
            data: {
                seccionSend: seccionAdd,
                contenidoSend: contenidoAdd
            },
            success: function(data, status) {
                $('#completeModal').modal('hide');
                displayData();
            }
        });
    }

    // Función para eliminar un contenido
    function DeleteUser(deleteid) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: '¿Estás seguro de que deseas eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "contenido/crud/delete.php",
                    type: 'post',
                    data: {
                        deletesend: deleteid
                    },
                    success: function(data, status) {
                        displayData();
                    }
                });
            }
        });
    }

// Función para obtener los detalles de un contenido para editarlo
function GetDetails(updateid) {
  $('#hiddendata').val(updateid);

  $.post("contenido/crud/update.php", { updateid: updateid }, function(data, status) {
    var userid = JSON.parse(data);
    $('#updateseccion').val(userid.seccion);

    // Eliminar instancia previa de Summernote (si existe)
    $('#updatecontenido').summernote('destroy');

    // Establecer el contenido ANTES de inicializar Summernote
    $('#updatecontenido').val(userid.contenido);

    // Inicializar Summernote DESPUÉS de cargar el contenido
    $('#updatecontenido').summernote({
      lang: 'es-ES',
      placeholder: 'Ingrese su contenido',
      tabsize: 2,
      height: 300
    });

    $('#updateModal').modal("show");
  });
}

    // Función para actualizar un contenido
    function updateDetails() {
        var uniqueid = $('#hiddendata').val();
        var seccion = $('#updateseccion').val();
        var contenido = $('#updatecontenido').val();

        $.ajax({
            url: "contenido/crud/update.php",
            type: 'post',
            data: {
                hiddendata: uniqueid,
                updateseccion: seccion,
                updatecontenido: contenido
            },
            success: function(data, status) {
                $('#updateModal').modal('hide');
                displayData();
            }
        });
    }
</script>