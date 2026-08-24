<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Listado Completo de Carreras";
require_once '../funciones/functions.php';

include("includes/head.php");
?>

<div class="container-fluid py-3">
    <h2>Listado de Carreras Registradas</h2>
    
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
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenido-modal-estado">
                <!-- El contenido se carga aquí dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmar-cambio-estado">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let estadoActual = {
        id: null,
        accion: null,
        nombre: null,
        boton: null
    };

    // Manejar clic en botón Editar
    $(document).on('click', '.btn-editar', function() {
        var id = $(this).data('id');
        $('#contenido-modal-editar').load('partials/editar_carrera_modal.php?id=' + id, function() {
            $('#modalEditarCarrera').modal('show');
        });
    });

    // Manejar clic en botón Activar/Desactivar - ABRE EL MODAL
    $(document).on('click', '.btn-cambiar-estado', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        estadoActual.id = $btn.data('id');
        estadoActual.accion = $btn.data('accion');
        estadoActual.nombre = $btn.closest('tr').find('td:first').text() || 'esta carrera';
        estadoActual.boton = $btn;
        
        var textoAccion = estadoActual.accion === 'activar' ? 'activar' : 'desactivar';
        var titulo = estadoActual.accion === 'activar' ? 'Activar Carrera' : 'Desactivar Carrera';
        var btnColor = estadoActual.accion === 'activar' ? 'success' : 'danger';
        var icono = estadoActual.accion === 'activar' ? 'check-circle' : 'times-circle';
        
        // Configurar el modal
        $('#modalCambiarEstadoLabel').text(titulo);
        $('#contenido-modal-estado').html(`
            <div class="alert alert-${btnColor}">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>¿Estás seguro que deseas ${textoAccion} la carrera?</strong>
            </div>
            <p><strong>Carrera:</strong> ${estadoActual.nombre}</p>
            <p class="text-muted">
                ${estadoActual.accion === 'activar' ? 
                    'La carrera estará disponible para nuevos estudiantes.' : 
                    'La carrera no estará disponible para nuevos estudiantes.'}
            </p>
        `);
        
        $('#confirmar-cambio-estado')
            .removeClass('btn-success btn-danger')
            .addClass('btn-' + btnColor)
            .html(`<i class="fas fa-${icono} mr-2"></i> ${titulo}`);
        
        $('#modalCambiarEstado').modal('show');
    });

    // Confirmar cambio de estado
    $('#confirmar-cambio-estado').on('click', function() {
        var $btnConfirmar = $(this);
        var $btnOriginal = estadoActual.boton;
        
        // Deshabilitar botón y mostrar loading
        $btnConfirmar.prop('disabled', true).html(
            `<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...`
        );
        
        $.ajax({
            url: 'ajax/cambiar_estado_carrera.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id: estadoActual.id,
                accion: estadoActual.accion
            },
            success: function(response) {
                if (response.success) {
                    // Recargar la tabla completa
                    $('#tabla-carreras').load('partials/tabla_carreras.php');
                    
                    // Cerrar modal
                    $('#modalCambiarEstado').modal('hide');
                    
                    // Mostrar alerta de éxito
                    mostrarAlerta('success', 
                        `<i class="fas fa-check-circle mr-2"></i> 
                         ${response.message || 'Estado cambiado correctamente'}`);
                } else {
                    mostrarAlerta('danger', 
                        `<i class="fas fa-exclamation-circle mr-2"></i> 
                         ${response.message || 'Error al cambiar el estado'}`);
                }
            },
            error: function(xhr, status, error) {
                mostrarAlerta('danger', 
                    `<i class="fas fa-exclamation-circle mr-2"></i> 
                     Error de conexión: ${error}`);
                console.error("Error AJAX:", status, error);
            },
            complete: function() {
                // Rehabilitar botón del modal
                var textoAccion = estadoActual.accion === 'activar' ? 'Activar' : 'Desactivar';
                var icono = estadoActual.accion === 'activar' ? 'check' : 'times';
                $btnConfirmar.prop('disabled', false).html(
                    `<i class="fas fa-${icono} mr-2"></i> ${textoAccion}`
                );
            }
        });
    });

    // Resetear estado cuando se cierra el modal
    $('#modalCambiarEstado').on('hidden.bs.modal', function() {
        estadoActual = {
            id: null,
            accion: null,
            nombre: null,
            boton: null
        };
        $('#confirmar-cambio-estado').prop('disabled', false);
    });

    // Función para mostrar alertas
    function mostrarAlerta(tipo, mensaje) {
        // Remover alertas existentes del mismo tipo
        $(`.alert.alert-${tipo}`).alert('close');
        
        var alerta = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                        ${mensaje}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`;
        
        $('.container').prepend(alerta);
        
        // Auto-cerrar después de 5 segundos
        setTimeout(function() {
            $(`.alert.alert-${tipo}`).alert('close');
        }, 5000);
    }
});
</script>

<?php include("includes/footer.php"); ?>