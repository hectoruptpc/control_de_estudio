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
    $duracion_anios = (int)($_POST['duracion_anios'] ?? 0);
    $titulo_principal = trim($_POST['titulo_principal'] ?? '');
    $titulo_opcional = trim($_POST['titulo_opcional'] ?? '');
    
    // Validación básica
    $camposObligatorios = [
        'nombre_carrera' => $nombre,
        'cod_carrera' => $codigo,
        'tipo_formacion' => $tipo_formacion,
        'duracion_anios' => $duracion_anios,
        'titulo_principal' => $titulo_principal
    ];
    
    $camposVacios = array_filter($camposObligatorios, function($valor) {
        return empty($valor);
    });
    
    if (!empty($camposVacios)) {
        $mensaje = '<div class="alert alert-warning">Los siguientes campos son obligatorios: ' . 
                   implode(', ', array_keys($camposVacios)) . '</div>';
    } else {
        $resultado = registrarNuevaCarrera(
            $nombre, 
            $codigo, 
            $tipo_formacion, 
            $duracion_anios, 
            $titulo_principal, 
            $titulo_opcional
        );
        
        if ($resultado['success']) {
            $mensaje = '<div class="alert alert-success">' . $resultado['message'] . '</div>';
            // Limpiar campos después de éxito
            $_POST = [];
        } else {
            $mensaje = '<div class="alert alert-danger">' . $resultado['message'] . '</div>';
        }
    }
}

include("includes/head.php");
?>

<?php if (tienePermiso('agregar_carrera')): ?>

<div class="container mt-4">
    <h2>Agregar Nuevo Programa</h2>
    
    <?php echo $mensaje; ?>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre_carrera">Nombre del Programa:</label>
            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
                   value="<?= htmlspecialchars($_POST['nombre_carrera'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="cod_carrera">Código del Programa:</label>
            <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
                   value="<?= htmlspecialchars($_POST['cod_carrera'] ?? '') ?>" required>
            <small class="form-text text-muted">Código único que identifica el programa</small>
        </div>

        <div class="form-group">
            <label for="tipo_formacion">Tipo de Formación:</label>
            <?php 
            $selected = $_POST['tipo_formacion'] ?? '';
            echo selectTiposFormacion('tipo_formacion', $selected); 
            ?>
            <small class="form-text text-muted">Seleccione el tipo de formación del programa</small>
        </div>
        
        <div class="form-group">
            <label for="duracion_anios">Duración en Años:</label>
            <input type="number" class="form-control" id="duracion_anios" name="duracion_anios" 
                   min="1" max="6" value="<?= htmlspecialchars($_POST['duracion_anios'] ?? '4') ?>" required>
            <small class="form-text text-muted">Duración total del programa en años</small>
        </div>
        
        <div class="form-group">
            <label for="titulo_principal">Título Principal:</label>
            <input type="text" class="form-control" id="titulo_principal" name="titulo_principal" 
                   value="<?= htmlspecialchars($_POST['titulo_principal'] ?? '') ?>" required>
            <small class="form-text text-muted">Título obtenido al completar el programa</small>
        </div>
        
        <div class="form-group">
            <label for="titulo_opcional">Segundo Título (opcional):</label>
            <input type="text" class="form-control" id="titulo_opcional" name="titulo_opcional" 
                   value="<?= htmlspecialchars($_POST['titulo_opcional'] ?? '') ?>">
            <small class="form-text text-muted">Título adicional obtenido al completar extensiones del programa (si aplica)</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Programa</button>
        <a href="lista_carreras.php" class="btn btn-secondary">Cancelar</a>
    </form>

<?php endif; ?>

    
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

    function mostrarAlerta(tipo, mensaje) {
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
});
</script>

<?php include("includes/footer.php"); ?>