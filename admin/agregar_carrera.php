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
$(document).ready(function() {
    // Resto del código JavaScript permanece igual...
    $(document).on('click', '.btn-cambiar-estado', function() {
        var $btn = $(this);
        var id = $btn.data('id');
        var accion = $btn.data('accion');
        var textoAccion = accion === 'activar' ? 'activar' : 'desactivar';
        
        if (!confirm(`¿Estás seguro que deseas ${textoAccion} esta carrera?`)) {
            return;
        }
        
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
                    var $fila = $btn.closest('tr');
                    
                    $fila.find('.badge')
                        .removeClass('badge-secondary badge-success')
                        .addClass(response.nuevoEstado ? 'badge-success' : 'badge-secondary')
                        .text(response.nuevoEstado ? 'Activa' : 'Inactiva');
                    
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