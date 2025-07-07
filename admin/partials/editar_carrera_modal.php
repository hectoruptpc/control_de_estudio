<?php
require_once '../../funciones/functions.php';

$id_carrera = $_GET['id'] ?? 0;
$carrera = obtenerCarreraPorId($id_carrera);

if (!$carrera) {
    echo '<div class="alert alert-danger">Carrera no encontrada</div>';
    exit;
}
?>

<form id="form-editar-carrera">
    <input type="hidden" name="id_carrera" value="<?= $carrera['id_carrera'] ?>">
    
    <div class="form-group">
        <label for="nombre_carrera">Nombre del Programa:</label>
        <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
               value="<?= htmlspecialchars($carrera['nombre_carrera']) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="cod_carrera">Código del Programa:</label>
        <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
               value="<?= htmlspecialchars($carrera['cod_carrera']) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="tipo_formacion">Tipo de Formación:</label>
        <select class="form-control" id="tipo_formacion" name="tipo_formacion" required>
            <option value="PNF" <?= $carrera['tipo_formacion'] === 'PNF' ? 'selected' : '' ?>>PNF</option>
            <option value="PTF" <?= $carrera['tipo_formacion'] === 'PTF' ? 'selected' : '' ?>>PTF</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="duracion_semestres">Duración (semestres):</label>
        <input type="number" class="form-control" id="duracion_semestres" name="duracion_semestres" 
               value="<?= $carrera['duracion_semestres'] ?>" min="1" max="20">
    </div>
    
    <div class="form-group">
        <label for="titulo_otorga">Título que otorga:</label>
        <input type="text" class="form-control" id="titulo_otorga" name="titulo_otorga" 
               value="<?= htmlspecialchars($carrera['titulo_otorga']) ?>">
    </div>
    
    <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= 
            htmlspecialchars($carrera['descripcion']) ?></textarea>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#form-editar-carrera').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        // Deshabilitar botón y mostrar spinner
        submitBtn.prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin"></i> Guardando...'
        );
        
        $.ajax({
            url: 'ajax/actualizar_carrera.php',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Cerrar modal y actualizar tabla
                    $('#modalEditarCarrera').modal('hide');
                    $('#tabla-carreras').load('partials/tabla_carreras.php', function() {
                        mostrarAlerta('success', response.message);
                    });
                } else {
                    mostrarAlerta('danger', response.message);
                    submitBtn.prop('disabled', false).html('Guardar Cambios');
                }
            },
            error: function(xhr, status, error) {
                mostrarAlerta('danger', 'Error de conexión: ' + error);
                submitBtn.prop('disabled', false).html('Guardar Cambios');
                console.error("Error AJAX:", status, error);
            }
        });
    });
});
</script>