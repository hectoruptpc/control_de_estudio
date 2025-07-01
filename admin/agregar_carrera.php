<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Agregar Nueva Carrera";
require_once '../funciones/functions.php';

// Procesar el formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_carrera'] ?? '');
    $codigo = trim($_POST['cod_carrera'] ?? '');
    $tipo_formacion = trim($_POST['tipo_formacion'] ?? '');
    
    if (!empty($nombre) && !empty($codigo) && !empty($tipo_formacion)) {
        $resultado = registrarNuevaCarrera($nombre, $codigo, $tipo_formacion);
        if ($resultado === true) {
            $mensaje = '<div class="alert alert-success">Carrera agregada correctamente</div>';
        } else {
            $mensaje = '<div class="alert alert-danger">'.$resultado.'</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Todos los campos son obligatorios</div>';
    }
}

include("includes/head.php");
?>

<div class="container mt-4">
    <h2>Agregar Nuevo Programa</h2>
    
    <?php echo $mensaje; ?>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre_carrera">Nombre del Programa:</label>
            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" required>
        </div>
        
        <div class="form-group">
            <label for="cod_carrera">Código del Programa:</label>
            <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" required>
            <small class="form-text text-muted">Código único que identifica el programa</small>
        </div>

        <div class="form-group">
            <label for="tipo_formacion">Tipo de Formación:</label>
            <select class="form-control" id="tipo_formacion" name="tipo_formacion" required>
                <option value="">Seleccione una opción</option>
                <option value="PNF">PNF (Programa Nacional de Formación)</option>
                <option value="PTF">PTF (Programa de Formación de Técnicos Superiores Universitarios)</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Programa</button>
    </form>
    
    <h3 class="mt-4">Programas Registrados en el Sistema</h3>
    <div id="tabla-carreras">
        <?php include('partials/tabla_carreras.php'); ?>
    </div>
</div>

<!-- Modal para Editar Carrera -->
<div class="modal fade" id="modalEditarCarrera" tabindex="-1" role="dialog" aria-labelledby="modalEditarCarreraLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarCarreraLabel">Editar Programa</h5>
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

    // Recargar la tabla después de agregar una carrera
    $('form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.post(window.location.href, form.serialize(), function(response) {
            // Reemplazar todo el contenido del container
            $('.container').html($(response).find('.container').html());
            // Volver a inicializar los event listeners
            $(document).ready();
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>