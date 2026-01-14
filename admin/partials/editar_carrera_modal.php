<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__.'/../../funciones/functions.php');

$id = $_GET['id'] ?? 0;
$carrera = obtenerCarreraPorId($id);

if (!$carrera) {
    die('<div class="alert alert-danger">No se encontró la carrera solicitada</div>');
}

// Convertir semestres a años
$duracion_anios = $carrera['duracion_semestres'] / 2;

// Obtener títulos
// Asegurar strings para evitar warnings/deprecations en funciones como strpos
$titulo_principal = isset($carrera['titulo_otorga']) ? (string)$carrera['titulo_otorga'] : '';
$titulo_opcional = isset($carrera['otro_titulo']) ? (string)$carrera['otro_titulo'] : '';

// Si el título principal contiene "/", separarlo (para compatibilidad con versiones anteriores)
if ($titulo_principal !== '' && strpos($titulo_principal, ' / ') !== false) {
    $titulos = explode(' / ', $titulo_principal);
    $titulo_principal = $titulos[0] ?? '';
    $titulo_opcional = $titulos[1] ?? $titulo_opcional;
}
?>

<div class="modal-body">
    <form id="formEditarCarrera" method="post" action="actualizar_carrera.php">
        <input type="hidden" name="id_carrera" value="<?= htmlspecialchars($carrera['id_carrera']) ?>">
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
                           value="<?= htmlspecialchars($carrera['nombre_carrera']) ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cod_carrera">Código de Carrera <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
                           value="<?= htmlspecialchars($carrera['cod_carrera']) ?>" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tipo_formacion">Tipo de Formación <span class="text-danger">*</span></label>
                    <select class="form-control" id="tipo_formacion" name="tipo_formacion" required>
                        <option value="PNF" <?= $carrera['tipo_formacion'] == 'PNF' ? 'selected' : '' ?>>PNF</option>
                        <option value="PTF" <?= $carrera['tipo_formacion'] == 'PTF' ? 'selected' : '' ?>>PTF</option>
                        <option value="TRAD" <?= $carrera['tipo_formacion'] == 'TRAD' ? 'selected' : '' ?>>Tradicional</option>
                        <option value="TSU" <?= $carrera['tipo_formacion'] == 'TSU' ? 'selected' : '' ?>>TSU</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duracion_anios">Duración (Años) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="duracion_anios" name="duracion_anios" 
                           min="1" max="6" value="<?= $duracion_anios ?>" required>
                    <small class="form-text text-muted">Se convertirá automáticamente a semestres</small>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titulo_principal">Título Principal <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="titulo_principal" name="titulo_principal" 
                           value="<?= htmlspecialchars($titulo_principal) ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titulo_opcional">Título Opcional</label>
                    <input type="text" class="form-control" id="titulo_opcional" name="titulo_opcional" 
                           value="<?= htmlspecialchars($titulo_opcional) ?>">
                    <small class="form-text text-muted">Segundo título que se puede obtener</small>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="activa">Estado <span class="text-danger">*</span></label>
                    <select class="form-control" id="activa" name="activa" required>
                        <option value="1" <?= $carrera['activa'] == 1 ? 'selected' : '' ?>>Activa</option>
                        <option value="0" <?= $carrera['activa'] == 0 ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($carrera['descripcion']) ?></textarea>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#formEditarCarrera').on('submit', function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        let isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            mostrarAlerta('danger', 'Por favor complete todos los campos obligatorios marcados con <span class="text-danger">*</span>');
            return false;
        }
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('.modal-footer button').prop('disabled', true);
                $('.modal-footer button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    $('#modalEditarCarrera').modal('hide');
                    // Recargar la tabla de carreras
                    $('#tabla-carreras').load('partials/tabla_carreras.php');
                    mostrarAlerta('success', response.message);
                } else {
                    mostrarAlerta('danger', response.message || 'Error al guardar los cambios');
                }
                $('.modal-footer button').prop('disabled', false);
                $('.modal-footer button[type="submit"]').html('Guardar Cambios');
            },
            error: function(xhr, status, error) {
                mostrarAlerta('danger', 'Error de conexión: ' + error);
                $('.modal-footer button').prop('disabled', false);
                $('.modal-footer button[type="submit"]').html('Guardar Cambios');
                console.error("Error AJAX:", status, error);
            }
        });
    });

    // Marcar campos inválidos al perder el foco
    $('#formEditarCarrera [required]').on('blur', function() {
        if (!$(this).val().trim()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    function mostrarAlerta(tipo, mensaje) {
        // Cerrar alertas existentes
        $('.alert-dismissible').alert('close');
        
        // Crear nueva alerta
        var alerta = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensaje}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        // Insertar la alerta en el contenedor principal
        $('.container').prepend(alerta);
        
        // Cerrar automáticamente después de 5 segundos
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
    }
});
</script>