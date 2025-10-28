<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Corrección de Notas";
include('../funciones/functions.php');

// Verificar permisos y sesión
cargarPermisosUsuario();
verificarPermiso('editar_nota');

// Procesar formularios
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'buscar':
                // La búsqueda se procesa en el mismo archivo
                break;
            case 'editar':
                $resultado = procesarEdicionNota();
                if ($resultado['success']) {
                    $mensaje = $resultado['message'];
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = $resultado['message'];
                    $tipo_mensaje = 'error';
                }
                break;
        }
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Corrección de Notas</h1>
            
            <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <!-- Filtros de búsqueda -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtrar Estudiantes</h6>
                </div>
                <div class="card-body">
                    <form method="POST" id="formFiltro">
                        <input type="hidden" name="accion" value="buscar">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-4">
                                <label for="id_materia">Materia:</label>
                                <select name="id_materia" id="id_materia" class="form-control" required>
                                    <option value="">Seleccionar Materia</option>
                                    <?php echo generarOpcionesMaterias($_POST['id_materia'] ?? ''); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="id_periodo">Periodo:</label>
                                <select name="id_periodo" id="id_periodo" class="form-control" required>
                                    <option value="">Seleccionar Periodo</option>
                                    <?php echo generarOpcionesPeriodos($_POST['id_periodo'] ?? ''); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="trayecto">Trayecto:</label>
                                <select name="trayecto" id="trayecto" class="form-control" required>
                                    <option value="">Seleccionar Trayecto</option>
                                    <option value="0" <?php echo (isset($_POST['trayecto']) && $_POST['trayecto'] == '0') ? 'selected' : ''; ?>>Trayecto 0</option>
                                    <option value="1" <?php echo (isset($_POST['trayecto']) && $_POST['trayecto'] == '1') ? 'selected' : ''; ?>>Trayecto 1</option>
                                    <option value="2" <?php echo (isset($_POST['trayecto']) && $_POST['trayecto'] == '2') ? 'selected' : ''; ?>>Trayecto 2</option>
                                    <option value="3" <?php echo (isset($_POST['trayecto']) && $_POST['trayecto'] == '3') ? 'selected' : ''; ?>>Trayecto 3</option>
                                    <option value="4" <?php echo (isset($_POST['trayecto']) && $_POST['trayecto'] == '4') ? 'selected' : ''; ?>>Trayecto 4</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultados -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscar'): ?>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notas de Estudiantes</h6>
                </div>
                <div class="card-body">
                    <?php
                    $notas = buscarNotasPorFiltro(
                        $_POST['id_materia'] ?? '',
                        $_POST['id_periodo'] ?? '',
                        $_POST['trayecto'] ?? ''
                    );
                    
                    if ($notas === false): ?>
                        <div class="alert alert-danger">Error al cargar las notas</div>
                    <?php elseif (empty($notas)): ?>
                        <div class="alert alert-warning">No se encontraron notas para los filtros seleccionados</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablaNotas" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Cédula</th>
                                        <th>Materia</th>
                                        <th>Periodo</th>
                                        <th>Nota Actual</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($notas as $nota): 
                                        $trayecto_field = 'trayecto_' . ($_POST['trayecto'] ?? '0');
                                        $nota_actual = $nota[$trayecto_field] ?? 'Sin asignar';
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($nota['nombre_estudiante']); ?></td>
                                        <td><?php echo htmlspecialchars($nota['cedula']); ?></td>
                                        <td><?php echo htmlspecialchars($nota['nombre_materia']); ?></td>
                                        <td><?php echo htmlspecialchars($nota['nombre_periodo']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo ($nota_actual === 'Sin asignar' || $nota_actual === null) ? 'warning' : 'primary'; ?> p-2">
                                                <?php echo htmlspecialchars($nota_actual); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarNota<?php echo $nota['id']; ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal para editar nota individual -->
                                    <div class="modal fade" id="modalEditarNota<?php echo $nota['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditarNotaLabel<?php echo $nota['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <input type="hidden" name="accion" value="editar">
                                                    <input type="hidden" name="id_nota" value="<?php echo $nota['id']; ?>">
                                                    <input type="hidden" name="trayecto" value="<?php echo htmlspecialchars($_POST['trayecto'] ?? ''); ?>">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $nota['id_usuario']; ?>">
                                                    <input type="hidden" name="id_materia" value="<?php echo htmlspecialchars($_POST['id_materia'] ?? ''); ?>">
                                                    <input type="hidden" name="id_periodo" value="<?php echo htmlspecialchars($_POST['id_periodo'] ?? ''); ?>">
                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditarNotaLabel<?php echo $nota['id']; ?>">Editar Nota</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="estudiante<?php echo $nota['id']; ?>">Estudiante:</label>
                                                            <input type="text" class="form-control" id="estudiante<?php echo $nota['id']; ?>" 
                                                                   value="<?php echo htmlspecialchars($nota['nombre_estudiante']); ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="materia<?php echo $nota['id']; ?>">Materia:</label>
                                                            <input type="text" class="form-control" id="materia<?php echo $nota['id']; ?>" 
                                                                   value="<?php echo htmlspecialchars($nota['nombre_materia']); ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nota_actual<?php echo $nota['id']; ?>">Nota Actual:</label>
                                                            <input type="text" class="form-control" id="nota_actual<?php echo $nota['id']; ?>" 
                                                                   value="<?php echo htmlspecialchars($nota_actual); ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nueva_nota<?php echo $nota['id']; ?>">Nueva Nota:</label>
                                                            <input type="number" class="form-control" id="nueva_nota<?php echo $nota['id']; ?>" 
                                                                   name="nueva_nota" min="0" max="20" step="0.01" required
                                                                   placeholder="Ingrese la nueva nota">
                                                            <small class="form-text text-muted">Nota entre 0 y 20</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="justificacion<?php echo $nota['id']; ?>">Justificación:</label>
                                                            <textarea class="form-control" id="justificacion<?php echo $nota['id']; ?>" 
                                                                      name="justificacion" rows="3" required 
                                                                      placeholder="Explique la razón del cambio de nota"></textarea>
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
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>