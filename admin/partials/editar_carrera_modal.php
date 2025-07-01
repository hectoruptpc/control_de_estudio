<?php
require_once '../../funciones/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de carrera no válido");
}

$id = (int)$_GET['id'];
$carrera = obtenerCarreraPorId($id);

if (!$carrera) {
    die("Carrera no encontrada");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_carrera'] ?? '');
    $codigo = trim($_POST['cod_carrera'] ?? '');
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    if (!empty($nombre) && !empty($codigo)) {
        if (actualizarCarrera($id, $nombre, $codigo, $activa)) {
            echo '<script>
                    parent.$("#modalEditarCarrera").modal("hide");
                    parent.$("#tabla-carreras").load("partials/tabla_carreras.php");
                    parent.mostrarAlerta("success", "Carrera actualizada correctamente");
                  </script>';
            exit();
        } else {
            echo '<div class="alert alert-danger">Error al actualizar la carrera</div>';
        }
    } else {
        echo '<div class="alert alert-warning">Todos los campos son obligatorios</div>';
    }
}
?>

<form method="post" id="form-editar-carrera">
    <div class="form-group">
        <label for="nombre_carrera">Nombre de la Carrera:</label>
        <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
               value="<?= htmlspecialchars($carrera['nombre_carrera']) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="cod_carrera">Código de la Carrera:</label>
        <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
               value="<?= htmlspecialchars($carrera['cod_carrera']) ?>" required>
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
        
        $.post(window.location.href, $(this).serialize(), function(response) {
            $('#contenido-modal-editar').html(response);
        });
    });
});
</script>