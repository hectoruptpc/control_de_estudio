<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Listado Completo de Carreras";
require_once '../funciones/functions.php';

include("includes/head.php");
?>

<div class="container mt-4">
    <h2>Listado de Carreras Registradas</h2>
    
    <div class="mb-4">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCarrera">
            <i class="fas fa-plus"></i> Agregar Nueva Carrera
        </button>
    </div>
    
    <div id="tabla-carreras">
        <?php include('partials/tabla_carreras.php'); ?>
    </div>
</div>

<!-- Modal para Editar Carrera -->
<div class="modal fade" id="modalEditarCarrera" tabindex="-1" role="dialog" aria-labelledby="modalEditarCarreraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarCarreraLabel">Editar Carrera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-modal-editar">
                <!-- El contenido se carga aquí via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Cambio de Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cambio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-modal-estado">
                <!-- El contenido se carga aquí via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmar-cambio">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar clic en botón Editar
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        $('#contenido-modal-editar').load('partials/editar_carrera_modal.php?id=' + id);
        $('#modalEditarCarrera').modal('show');
    });

    // Manejar clic en botón Activar/Desactivar
    $(document).on('click', '.btn-cambiar-estado', function() {
        var id = $(this).data('id');
        var accion = $(this).data('accion');
        var texto = accion === 'activar' ? 'activar' : 'desactivar';
        
        $('#contenido-modal-estado').html(`¿Estás seguro que deseas ${texto} esta carrera?`);
        
        $('#confirmar-cambio').off('click').on('click', function() {
            $.post('ajax/cambiar_estado_carrera.php', {
                id: id,
                accion: accion
            }, function(response) {
                if (response.success) {
                    $('#tabla-carreras').load('partials/tabla_carreras.php');
                    $('#modalCambiarEstado').modal('hide');
                    mostrarAlerta('success', response.message);
                } else {
                    mostrarAlerta('danger', response.message);
                }
            }, 'json');
        });
        
        $('#modalCambiarEstado').modal('show');
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        var alerta = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                        ${mensaje}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
        
        $('.container').prepend(alerta);
        
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }
});
</script>

<?php include("includes/footer.php"); ?>