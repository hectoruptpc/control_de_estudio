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



<script>
$(document).on('click', '.btn-cambiar-estado', function() {
    var $btn = $(this);
    var id = $btn.data('id');
    var accion = $btn.data('accion');
    var textoAccion = accion === 'activar' ? 'activar' : 'desactivar';
    
    if (!confirm(`¿Estás seguro que deseas ${textoAccion} esta carrera?`)) {
        return;
    }
    
    // Mostrar feedback visual
    $btn.prop('disabled', true).html(
        `<i class="fas fa-spinner fa-spin"></i> ${textoAccion}...`
    );
    
    $.ajax({
        url: 'ajax/cambiar_estado_carrera.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            accion: accion
        },
        success: function(response) {
            if (response.success) {
                // Actualizar la interfaz sin recargar toda la tabla
                var $fila = $btn.closest('tr');
                
                // Cambiar el badge de estado
                $fila.find('.badge')
                    .removeClass('badge-secondary badge-success')
                    .addClass(response.nuevoEstado ? 'badge-success' : 'badge-secondary')
                    .text(response.nuevoEstado ? 'Activa' : 'Inactiva');
                
                // Cambiar el botón
                var nuevoBoton = response.nuevoEstado ? 
                    `<button class="btn btn-sm btn-danger btn-cambiar-estado" 
                             data-id="${id}" data-accion="desactivar">
                        <i class="fas fa-toggle-off"></i> Desactivar
                    </button>` :
                    `<button class="btn btn-sm btn-success btn-cambiar-estado" 
                             data-id="${id}" data-accion="activar">
                        <i class="fas fa-toggle-on"></i> Activar
                    </button>`;
                
                $btn.replaceWith(nuevoBoton);
                
                mostrarAlerta('success', response.message);
            } else {
                mostrarAlerta('danger', response.message || 'Error al cambiar el estado');
                $btn.prop('disabled', false).html(
                    accion === 'activar' ? 
                    '<i class="fas fa-toggle-on"></i> Activar' : 
                    '<i class="fas fa-toggle-off"></i> Desactivar'
                );
            }
        },
        error: function(xhr, status, error) {
            mostrarAlerta('danger', 'Error de conexión: ' + error);
            $btn.prop('disabled', false).html(
                accion === 'activar' ? 
                '<i class="fas fa-toggle-on"></i> Activar' : 
                '<i class="fas fa-toggle-off"></i> Desactivar'
            );
            console.error("Error AJAX:", status, error);
        }
    });
});

// Manejar clic en botón Editar
$(document).on('click', '.btn-editar', function() {
    var id = $(this).data('id');
    $('#contenido-modal-editar').load('partials/editar_carrera_modal.php?id=' + id, function() {
        $('#modalEditarCarrera').modal('show');
    });
});

// Función para mostrar alertas (debe estar definida)
function mostrarAlerta(tipo, mensaje) {
    // Eliminar alertas anteriores del mismo tipo
    $(`.alert.alert-${tipo}`).alert('close');
    
    var alerta = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                    ${mensaje}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
    
    $('.container').prepend(alerta);
    
    setTimeout(function() {
        $(`.alert.alert-${tipo}`).alert('close');
    }, 5000);
}



</script>

<?php include("includes/footer.php"); ?>