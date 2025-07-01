<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Añadir nuevo docente";
include('../funciones/functions.php');
include("includes/head.php");

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $datos = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'documento' => $_POST['documento'],
        'tipo_documento' => $_POST['tipo_documento'],
        'fecha_nacimiento' => $_POST['fecha_nacimiento'],
        'genero' => $_POST['genero'],
        'direccion' => $_POST['direccion'],
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email'],
        'especialidad' => $_POST['especialidad'],
        'titulo' => $_POST['titulo'],
        'fecha_contratacion' => $_POST['fecha_contratacion'],
        'estado' => $_POST['estado']
    ];
    
    if (addDocente($datos)) {
        echo '<div class="alert alert-success">Docente añadido correctamente!</div>';
    } else {
        echo '<div class="alert alert-danger">Error al añadir docente.</div>';
    }
}

// Obtener lista de docentes
$docentes = getDocentes();
?>

<div class="container">
    <h2>Añadir nuevo docente</h2>
    <form method="POST" action="">
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

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tipo de documento</label>
                    <select name="tipo_documento" class="form-control" required>
                        <option value="DNI">DNI</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="Cédula">Cédula</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Número de documento</label>
                    <input type="text" name="documento" class="form-control" required>
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

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Título académico</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
            </div>
        </div>

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

        <button type="submit" class="btn btn-primary">Guardar docente</button>
    </form>
</div>


<hr>
    
    <h2>Docentes Registrados</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
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
                    <td><?php echo $docente['id']; ?></td>
                    <td><?php echo $docente['nombre']; ?></td>
                    <td><?php echo $docente['apellido']; ?></td>
                    <td><?php echo $docente['tipo_documento'] . ': ' . $docente['documento']; ?></td>
                    <td><?php echo $docente['email']; ?></td>
                    <td><?php echo $docente['especialidad']; ?></td>
                    <td><?php echo $docente['estado']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editarModal<?php echo $docente['id']; ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#deshabilitarModal<?php echo $docente['id']; ?>">
                            <i class="fas fa-ban"></i> Deshabilitar
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="editarModal<?php echo $docente['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarModalLabel">Editar Docente: <?php echo $docente['nombre'] . ' ' . $docente['apellido']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="editar_docente.php">
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $docente['id']; ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" name="nombre" class="form-control" value="<?php echo $docente['nombre']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Apellido</label>
                                                <input type="text" name="apellido" class="form-control" value="<?php echo $docente['apellido']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Tipo de documento</label>
                                                <select name="tipo_documento" class="form-control" required>
                                                    <option value="DNI" <?php echo ($docente['tipo_documento'] == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                                                    <option value="Pasaporte" <?php echo ($docente['tipo_documento'] == 'Pasaporte') ? 'selected' : ''; ?>>Pasaporte</option>
                                                    <option value="Cédula" <?php echo ($docente['tipo_documento'] == 'Cédula') ? 'selected' : ''; ?>>Cédula</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Número de documento</label>
                                                <input type="text" name="documento" class="form-control" value="<?php echo $docente['documento']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo $docente['email']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Especialidad</label>
                                        <input type="text" name="especialidad" class="form-control" value="<?php echo $docente['especialidad']; ?>" required>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select name="estado" class="form-control" required>
                                                    <option value="Activo" <?php echo ($docente['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                                    <option value="Inactivo" <?php echo ($docente['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                                                    <option value="Licencia" <?php echo ($docente['estado'] == 'Licencia') ? 'selected' : ''; ?>>Licencia</option>
                                                </select>
                                            </div>
                                        </div>
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

                <!-- Modal Deshabilitar -->
                <div class="modal fade" id="deshabilitarModal<?php echo $docente['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deshabilitarModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deshabilitarModalLabel">Deshabilitar Docente</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" action="deshabilitar_docente.php">
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $docente['id']; ?>">
                                    <p>¿Estás seguro que deseas deshabilitar al docente <strong><?php echo $docente['nombre'] . ' ' . $docente['apellido']; ?></strong>?</p>
                                    <div class="form-group">
                                        <label>Razón de deshabilitación</label>
                                        <textarea name="razon" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-warning">Confirmar Deshabilitación</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("includes/footer.php"); ?>