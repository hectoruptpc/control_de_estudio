<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Tipos de Horario";
include('../funciones/functions.php');

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $horas_academicas = $_POST['horas_academicas'];
        $horas_atendiendo = $_POST['horas_atendiendo'];
        
        $resultado = agregarTipoHorario($db, $nombre, $horas_academicas, $horas_atendiendo);
        $mensaje = $resultado ? "Tipo de horario agregado correctamente." : "Error al agregar el tipo de horario.";
        
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $horas_academicas = $_POST['horas_academicas'];
        $horas_atendiendo = $_POST['horas_atendiendo'];
        
        $resultado = actualizarTipoHorario($db, $id, $nombre, $horas_academicas, $horas_atendiendo);
        $mensaje = $resultado ? "Tipo de horario actualizado correctamente." : "Error al actualizar el tipo de horario.";
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $resultado = eliminarTipoHorario($db, $id);
        $mensaje = $resultado ? "Tipo de horario eliminado correctamente." : "Error al eliminar el tipo de horario.";
    }
}

// Obtener tipos de horario
$tipos_horario = obtenerTiposHorario($db);

include("includes/head.php");

// Mostrar mensaje si existe
if (isset($mensaje)) {
    echo '<div class="alert alert-' . (strpos($mensaje, 'Error') !== false ? 'danger' : 'success') . ' alert-dismissible fade show" role="alert">
            ' . $mensaje . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Gestión de Tipos de Horario</h1>
            
            <!-- Formulario para agregar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Agregar Tipo de Horario</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="nombre">Nombre del horario:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="horas_academicas">Horas académicas:</label>
                            <input type="number" class="form-control" id="horas_academicas" name="horas_academicas" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="horas_atendiendo">Horas atendiendo:</label>
                            <input type="number" class="form-control" id="horas_atendiendo" name="horas_atendiendo" required min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="agregar">
                            Agregar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Tabla de tipos de horario -->
            <div class="card">
                <div class="card-header">
                    <h5>Tipos de Horario Existentes</h5>
                </div>
                <div class="card-body">
                    <?php if (count($tipos_horario) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Horas Académicas</th>
                                        <th>Horas Atendiendo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tipos_horario as $tipo): ?>
                                        <tr>
                                            <td><?php echo $tipo['id']; ?></td>
                                            <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                            <td><?php echo $tipo['horas_academicas']; ?></td>
                                            <td><?php echo $tipo['horas_atendiendo']; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditar" 
                                                        data-id="<?php echo $tipo['id']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($tipo['nombre']); ?>"
                                                        data-horas-academicas="<?php echo $tipo['horas_academicas']; ?>"
                                                        data-horas-atendiendo="<?php echo $tipo['horas_atendiendo']; ?>">
                                                    Editar
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalEliminar" 
                                                        data-id="<?php echo $tipo['id']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($tipo['nombre']); ?>">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No hay tipos de horario registrados aún.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Tipo de Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre del horario:</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_horas_academicas">Horas académicas:</label>
                        <input type="number" class="form-control" id="edit_horas_academicas" name="horas_academicas" required min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_horas_atendiendo">Horas atendiendo:</label>
                        <input type="number" class="form-control" id="edit_horas_atendiendo" name="horas_atendiendo" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="editar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Tipo de Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="delete_id">
                    <p>¿Estás seguro de que deseas eliminar el tipo de horario: <strong id="delete_nombre"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para manejar los modales
document.addEventListener('DOMContentLoaded', function() {
    // Modal de edición
    $('#modalEditar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var horasAcademicas = button.data('horas-academicas');
        var horasAtendiendo = button.data('horas-atendiendo');
        
        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_nombre').val(nombre);
        modal.find('#edit_horas_academicas').val(horasAcademicas);
        modal.find('#edit_horas_atendiendo').val(horasAtendiendo);
    });
    
    // Modal de eliminación
    $('#modalEliminar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        
        var modal = $(this);
        modal.find('#delete_id').val(id);
        modal.find('#delete_nombre').text(nombre);
    });
});
</script>

<?php include("includes/footer.php"); ?>