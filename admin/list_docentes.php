<?php
// Configuración inicial silenciosa
error_reporting(E_ALL);
ini_set('display_errors', 1); // Desactivamos la visualización de errores



// Procesamiento antes de cualquier salida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once('../funciones/functions.php'); // Incluimos solo lo necesario
    
    if ($_POST['action'] == 'update_status' && isset($_POST['user_id'])) {
        $new_status = ($_POST['current_status'] == 'Activo') ? 'Inactivo' : 'Activo';
        
        if (updateUserStatus($_POST['user_id'], $new_status)) {
            $_SESSION['success_message'] = "Estado del docente actualizado correctamente";
        } else {
            $_SESSION['error_message'] = "Error al actualizar el estado del docente";
        }
        
        // Redirección con JavaScript para evitar headers
        echo '<script>window.location.href="list_docentes.php";</script>';
        exit();
    }
}

// Configuración de página e includes
$titulopag = "Lista de docentes";
require_once('../funciones/functions.php');
require_once("includes/head.php");

// Obtener solo usuarios que son docentes (docente = 1)
$docentes = getUsersByType(1);
?>

<div class="container">
    <h2>Lista de docentes</h2>
    <!-- Botón para abrir modal de nuevo docente -->
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addDocenteModal">
        <i class="fas fa-plus"></i> Añadir nuevo docente
    </button>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Títulos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docentes as $docente): ?>
            <tr>
                <td><?= $docente['id'] ?></td>
                <td><?= $docente['nombre'] ?></td>
                <td><?= $docente['username'] ?></td>
                <td><?= $docente['email'] ?></td>
                <td><?= $docente['tlf'] ?: $docente['cel'] ?></td>
                <td><?= $docente['titulos'] ?></td>
                <td>
                    <span class="badge <?= $docente['status'] == 'Activo' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $docente['status'] ?>
                    </span>
                </td>
                <td>
                    <!-- Botón Editar - abre modal -->
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?= $docente['id'] ?>">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    
                    <!-- Botón Desactivar/Activar - abre modal -->
                    <button type="button" class="btn btn-sm <?= $docente['status'] == 'Activo' ? 'btn-danger' : 'btn-success' ?>" 
                            data-bs-toggle="modal" 
                            data-bs-target="#statusModal-<?= $docente['id'] ?>">
                        <i class="fas <?= $docente['status'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                        <?= $docente['status'] == 'Activo' ? 'Desactivar' : 'Activar' ?>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal para agregar nuevo docente -->
<div class="modal fade" id="addDocenteModal" tabindex="-1" aria-labelledby="addDocenteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addDocenteModalLabel">
                    <i class="fas fa-user-plus"></i> Añadir nuevo docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="add_user.php">
                <input type="hidden" name="docente" value="1">
                <div class="modal-body">
                    <!-- Información básica -->
                    <h5 class="mb-3">Información Personal</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Apellido</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Información de usuario -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de usuario</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="tlf" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" name="cel" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Información académica -->
                    <h5 class="mt-4 mb-3">Información Académica</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Títulos académicos</label>
                                <div id="titulos-container">
                                    <div class="input-group mb-2 titulo-group">
                                        <input type="text" name="titulos[]" class="form-control" placeholder="Ej: Licenciado en Educación" required>
                                        <button type="button" class="btn btn-success btn-add-more">
                                            <i class="fas fa-plus"></i> Añadir otro
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Puedes agregar todos los títulos que necesites</small>
                            </div>
                        </div>
                    </div>

                    <!-- Información de acceso -->
                    <h5 class="mt-4 mb-3">Información de Acceso</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="status" class="form-control" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar docente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modales - Fuera de la tabla pero dentro del container -->
<?php foreach ($docentes as $docente): ?>
<!-- Modal Editar -->
<div class="modal fade" id="editModal-<?= $docente['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel-<?= $docente['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editModalLabel-<?= $docente['id'] ?>">
                    <i class="fas fa-edit"></i> Editar Docente: <?= $docente['nombre'] ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_user.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $docente['id'] ?>">
                    <input type="hidden" name="docente" value="1">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($docente['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($docente['apellido'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre de usuario</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($docente['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($docente['email']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="tlf" class="form-control" value="<?= htmlspecialchars($docente['tlf']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Títulos</label>
                            <input type="text" name="titulos" class="form-control" value="<?= htmlspecialchars($docente['titulos']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-control" required>
                                <option value="Activo" <?= $docente['status'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= $docente['status'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cambio de Estado -->
<div class="modal fade" id="statusModal-<?= $docente['id'] ?>" tabindex="-1" aria-labelledby="statusModalLabel-<?= $docente['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header <?= $docente['status'] == 'Activo' ? 'bg-danger' : 'bg-success' ?> text-white">
                <h5 class="modal-title" id="statusModalLabel-<?= $docente['id'] ?>">
                    <i class="fas <?= $docente['status'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                    <?= $docente['status'] == 'Activo' ? 'Desactivar' : 'Activar' ?> Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="user_id" value="<?= $docente['id'] ?>">
                <input type="hidden" name="current_status" value="<?= $docente['status'] ?>">
                <div class="modal-body">
                    <p>¿Estás seguro que deseas <?= $docente['status'] == 'Activo' ? 'desactivar' : 'activar' ?> al docente:</p>
                    <h5 class="text-center"><?= $docente['nombre'] ?></h5>
                    <p class="text-muted text-center">Usuario: <?= $docente['username'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn <?= $docente['status'] == 'Activo' ? 'btn-danger' : 'btn-success' ?>">
                        <i class="fas <?= $docente['status'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                        <?= $docente['status'] == 'Activo' ? 'Desactivar' : 'Activar' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php endforeach; ?>

<script>
$(document).ready(function() {
    // Forzar el manejo de los modales
    $('[data-bs-toggle="modal"]').click(function() {
        var target = $(this).attr('data-bs-target');
        $(target).modal('show');
    });
    
    // Ocultar alertas después de 5 segundos
    $(".alert").delay(5000).fadeOut(300);
});
</script>

<script>
$(document).ready(function() {
    // Máximo teórico de campos (puedes aumentarlo o quitarlo)
    const MAX_FIELDS = 20;
    
    // Añadir nuevo campo de título
    $(document).on('click', '.btn-add-more', function() {
        const container = $('#titulos-container');
        const groupCount = container.find('.titulo-group').length;
        
        if(groupCount < MAX_FIELDS) {
            const newGroup = `
                <div class="input-group mb-2 titulo-group">
                    <input type="text" name="titulos[]" class="form-control" placeholder="Ej: Magister en Ciencias">
                    <button type="button" class="btn btn-danger btn-remove">
                        <i class="fas fa-times"></i> Eliminar
                    </button>
                </div>
            `;
            container.append(newGroup);
            
            // Scroll al nuevo campo si hay muchos
            if(groupCount > 3) {
                container.animate({
                    scrollTop: container.prop("scrollHeight")
                }, 300);
            }
        } else {
            alert(`Has alcanzado el máximo de ${MAX_FIELDS} títulos.`);
        }
    });
    
    // Eliminar campo de título
    $(document).on('click', '.btn-remove', function() {
        // No permitir eliminar si solo queda un campo
        if($('.titulo-group').length > 1) {
            $(this).closest('.titulo-group').remove();
        } else {
            alert("Debe haber al menos un título académico.");
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>