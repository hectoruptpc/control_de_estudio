<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Tipos de Pago";
include('../funciones/functions.php');


//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('tipos_pago');




// Variables
$error = $success = '';
$id = $tipopago = '';
$accion = 'crear';

// Procesar formulario de creación/edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $tipopago = trim($_POST['tipopago'] ?? '');
    
    if (empty($tipopago)) {
        $error = "El tipo de pago es requerido";
    } else {
        if (empty($id)) {
            // Crear nuevo registro
            $stmt = $db->prepare("INSERT INTO tipo_pago (tipopago) VALUES (?)");
            $stmt->bind_param("s", $tipopago);
            if ($stmt->execute()) {
                $success = "Tipo de pago creado exitosamente";
            } else {
                $error = "Error al crear: " . $db->error;
            }
            $stmt->close();
        } else {
            // Actualizar registro existente
            $stmt = $db->prepare("UPDATE tipo_pago SET tipopago = ? WHERE id = ?");
            $stmt->bind_param("si", $tipopago, $id);
            if ($stmt->execute()) {
                $success = "Tipo de pago actualizado exitosamente";
            } else {
                $error = "Error al actualizar: " . $db->error;
            }
            $stmt->close();
        }
    }
}

// Procesar eliminación
if (isset($_POST['eliminar_id'])) {
    $id_eliminar = intval($_POST['eliminar_id']);
    $stmt = $db->prepare("DELETE FROM tipo_pago WHERE id = ?");
    $stmt->bind_param("i", $id_eliminar);
    if ($stmt->execute()) {
        $success = "Tipo de pago eliminado exitosamente";
    } else {
        $error = "Error al eliminar: " . $db->error;
    }
    $stmt->close();
}

// Obtener todos los registros
$query = "SELECT id, tipopago FROM tipo_pago ORDER BY tipopago";
$result = $db->query($query);
$tipos_pago = [];
if ($result) {
    $tipos_pago = $result->fetch_all(MYSQLI_ASSOC);
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Gestión de Tipos de Pago</h1>
            
            <!-- Mensajes de alerta -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Crear Nuevo Tipo de Pago</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="id" id="form_id" value="">
                        
                        <div class="form-group">
                            <label for="tipopago">Tipo de Pago:</label>
                            <input type="text" class="form-control" id="tipopago" name="tipopago" 
                                   value="<?php echo htmlspecialchars($tipopago); ?>" required 
                                   placeholder="Ingrese el tipo de pago">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="form_submit_btn">Crear</button>
                        <button type="button" class="btn btn-secondary" id="cancel_edit_btn" style="display:none;">Cancelar</button>
                    </form>
                </div>
            </div>
            
            <!-- Tabla de registros -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Lista de Tipos de Pago</h5>
                    <span class="badge badge-primary"><?php echo count($tipos_pago); ?> registros</span>
                </div>
                <div class="card-body">
                    <?php if (empty($tipos_pago)): ?>
                        <div class="alert alert-info">No hay tipos de pago registrados.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Tipo de Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tipos_pago as $tipo): ?>
                                        <tr id="row-<?php echo $tipo['id']; ?>">
                                            <td><?php echo htmlspecialchars($tipo['id']); ?></td>
                                            <td><?php echo htmlspecialchars($tipo['tipopago']); ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-btn" 
                                                        data-id="<?php echo $tipo['id']; ?>" 
                                                        data-tipopago="<?php echo htmlspecialchars($tipo['tipopago']); ?>">
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-btn" 
                                                        data-id="<?php echo $tipo['id']; ?>" 
                                                        data-tipopago="<?php echo htmlspecialchars($tipo['tipopago']); ?>">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Tipo de Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_tipopago">Tipo de Pago:</label>
                        <input type="text" class="form-control" id="edit_tipopago" name="tipopago" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="eliminar_id" id="delete_id">
                    <p>¿Está seguro de que desea eliminar el tipo de pago: <strong id="delete_tipopago"></strong>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .card-header {
        background-color: #f8f9fa;
    }
</style>

<script>
    $(document).ready(function() {
        // Manejar clic en botón Editar
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var tipopago = $(this).data('tipopago');
            
            $('#edit_id').val(id);
            $('#edit_tipopago').val(tipopago);
            
            $('#editModal').modal('show');
        });
        
        // Manejar clic en botón Eliminar
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            var tipopago = $(this).data('tipopago');
            
            $('#delete_id').val(id);
            $('#delete_tipopago').text(tipopago);
            
            $('#deleteModal').modal('show');
        });
        
        // Limpiar formulario principal al cancelar edición
        $('#cancel_edit_btn').click(function() {
            $('#form_id').val('');
            $('#tipopago').val('');
            $('#form_submit_btn').text('Crear');
            $('#cancel_edit_btn').hide();
            $('.card-header h5').text('Crear Nuevo Tipo de Pago');
        });
        
        // Cerrar modales al enviar formularios
        $('#editForm').submit(function() {
            $('#editModal').modal('hide');
        });
    });
</script>

<?php include("includes/footer.php"); ?>