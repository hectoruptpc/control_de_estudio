


<?php
// Configuración inicial silenciosa
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivamos la visualización de errores

// Manejo de sesión sin errores
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Procesamiento antes de cualquier salida
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    require_once('../funciones/functions.php'); // Incluimos solo lo necesario
    
    if ($_POST['action'] == 'update_status' && isset($_POST['docente_id'])) {
        $new_status = ($_POST['current_status'] == 'Activo') ? 'Inactivo' : 'Activo';
        
        if (updateDocenteEstado($_POST['docente_id'], $new_status)) {
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

$docentes = getDocentes();
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
                <th>Apellido</th>
                <th>Documento</th>
                <th>Email</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docentes as $docente): ?>
            <tr>
                <td><?= $docente['id'] ?></td>
                <td><?= $docente['nombre'] ?></td>
                <td><?= $docente['apellido'] ?></td>
                <td><?= $docente['tipo_documento'] ?>: <?= $docente['documento'] ?></td>
                <td><?= $docente['email'] ?></td>
                <td><?= $docente['especialidad'] ?></td>
                <td>
                    <span class="badge <?= $docente['estado'] == 'Activo' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= $docente['estado'] ?>
                    </span>
                </td>
                <td>
                    <!-- Botón Editar - abre modal -->
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?= $docente['id'] ?>">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    
                    <!-- Botón Desactivar/Activar - abre modal -->
                    <button type="button" class="btn btn-sm <?= $docente['estado'] == 'Activo' ? 'btn-danger' : 'btn-success' ?>" 
                            data-bs-toggle="modal" 
                            data-bs-target="#statusModal-<?= $docente['id'] ?>">
                        <i class="fas <?= $docente['estado'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                        <?= $docente['estado'] == 'Activo' ? 'Desactivar' : 'Activar' ?>
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
            <form method="POST" action="add_docente.php">
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

                    <!-- Documentación -->
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de documento</label>
                                <select name="tipo_documento" class="form-control" required>
                                    <option value="V-">V- (Venezolano)</option>
                                    <option value="E-">E- (Extranjero)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número de documento</label>
                                <input type="text" name="documento" class="form-control" 
                                       pattern="\d{1,8}" title="Máximo 8 dígitos numéricos" 
                                       maxlength="8" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Género</label>
                                <select name="genero" class="form-control" required>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Direcciones -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dirección actual</label>
                                <input type="text" name="direccion_actual" class="form-control" placeholder="Ej: Av. Principal, Casa #123">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dirección de nacimiento</label>
                                <input type="text" name="direccion_nacimiento" class="form-control" placeholder="Ej: Calle 5 con Av. 3, Edif. Las Flores">
                            </div>
                        </div>
                    </div>

                    <!-- Contacto -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Información académica -->
                    <h5 class="mt-4 mb-3">Información Académica</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Especialidad</label>
                                <input type="text" name="especialidad" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
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

                    <!-- Información laboral -->
                    <h5 class="mt-4 mb-3">Información Laboral</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de contratación</label>
                                <input type="date" name="fecha_contratacion" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="estado" class="form-control" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                    <option value="Licencia">Licencia</option>
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
                    <i class="fas fa-edit"></i> Editar Docente: <?= $docente['nombre'] ?> <?= $docente['apellido'] ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="update_docente.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $docente['id'] ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($docente['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($docente['apellido']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($docente['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" class="form-control" value="<?= htmlspecialchars($docente['especialidad']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="estado"  class="custom-select d-block w-100" required>
                                <option value="Activo" <?= $docente['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= $docente['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                <option value="Licencia" <?= $docente['estado'] == 'Licencia' ? 'selected' : '' ?>>Licencia</option>
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
            <div class="modal-header <?= $docente['estado'] == 'Activo' ? 'bg-danger' : 'bg-success' ?> text-white">
                <h5 class="modal-title" id="statusModalLabel-<?= $docente['id'] ?>">
                    <i class="fas <?= $docente['estado'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                    <?= $docente['estado'] == 'Activo' ? 'Desactivar' : 'Activar' ?> Docente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="docente_id" value="<?= $docente['id'] ?>">
                <input type="hidden" name="current_status" value="<?= $docente['estado'] ?>">
                <div class="modal-body">
                    <p>¿Estás seguro que deseas <?= $docente['estado'] == 'Activo' ? 'desactivar' : 'activar' ?> al docente:</p>
                    <h5 class="text-center"><?= $docente['nombre'] ?> <?= $docente['apellido'] ?>?</h5>
                    <p class="text-muted text-center">Documento: <?= $docente['tipo_documento'] ?> <?= $docente['documento'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn <?= $docente['estado'] == 'Activo' ? 'btn-danger' : 'btn-success' ?>">
                        <i class="fas <?= $docente['estado'] == 'Activo' ? 'fa-user-slash' : 'fa-user-check' ?>"></i> 
                        <?= $docente['estado'] == 'Activo' ? 'Desactivar' : 'Activar' ?>
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