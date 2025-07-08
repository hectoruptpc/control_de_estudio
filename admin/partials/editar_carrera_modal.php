<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__.'/../../funciones/functions.php');

$id = $_GET['id'] ?? 0;
$carrera = obtenerCarreraPorId($id);
?>



<div class="modal-body">
    <form id="formEditarCarrera" method="post" action="actualizar_carrera.php">
        <input type="hidden" name="id_carrera" value="<?= $carrera['id_carrera'] ?>">
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre_carrera">Nombre de la Carrera</label>
                    <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
                           value="<?= $carrera['nombre_carrera'] ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cod_carrera">Código de Carrera</label>
                    <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
                           value="<?= $carrera['cod_carrera'] ?>">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tipo_formacion">Tipo de Formación</label>
                    <select class="form-control" id="tipo_formacion" name="tipo_formacion">
                        <option value="PNF" <?= $carrera['tipo_formacion'] == 'PNF' ? 'selected' : '' ?>>PNF</option>
                        <option value="PTF" <?= $carrera['tipo_formacion'] == 'PTF' ? 'selected' : '' ?>>PTF</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duracion_semestres">Duración (Semestres)</label>
                    <input type="number" class="form-control" id="duracion_semestres" name="duracion_semestres" 
                           value="<?= $carrera['duracion_semestres'] ?>">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titulo_otorga">Título que Otorga</label>
                    <input type="text" class="form-control" id="titulo_otorga" name="titulo_otorga" 
                           value="<?= $carrera['titulo_otorga'] ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="activa">Estado</label>
                    <select class="form-control" id="activa" name="activa">
                        <option value="1" <?= $carrera['activa'] == 1 ? 'selected' : '' ?>>Activa</option>
                        <option value="0" <?= $carrera['activa'] == 0 ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= $carrera['descripcion'] ?></textarea>
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
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editarCarreraModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Ocurrió un error');
            }
        });
    });
});
</script>