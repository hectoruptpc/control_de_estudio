<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Agregar nueva materia";
require_once('../funciones/functions.php');
include("includes/head.php");

// Procesar acciones (editar/desactivar)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;
    
    if ($action == 'edit' && $id) {
        $materiaEditar = getMateriaById($id);
        if (!$materiaEditar) {
            $_SESSION['mensaje'] = "Materia no encontrada";
            header("Location: agregar_materia.php");
            exit();
        }
    } elseif ($action == 'toggle' && $id) {
        if (toggleMateriaStatus($id)) {
            $_SESSION['mensaje'] = "Estado de la materia actualizado";
        } else {
            $_SESSION['error'] = "Error al cambiar el estado";
        }
        header("Location: agregar_materia.php");
        exit();
    }
}

// Obtener todas las materias
$listaMaterias = getAllMaterias();
?>

<div class="container mt-4">
    <!-- Mensajes de sesión -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <h2 class="mb-4"><?= isset($materiaEditar) ? 'Editar Materia' : 'Agregar Nueva Materia' ?></h2>
    
    <form action="procesar_materia.php" method="post" id="materiaForm">
        <input type="hidden" name="id" id="materiaId" value="<?= $materiaEditar['id'] ?? '' ?>">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cod_materia">Código de Materia:</label>
                    <input type="text" class="form-control" id="cod_materia" name="cod_materia" 
                           value="<?= htmlspecialchars($materiaEditar['cod_materia'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre_materia">Nombre de Materia:</label>
                    <input type="text" class="form-control" id="nombre_materia" name="nombre_materia" 
                           value="<?= htmlspecialchars($materiaEditar['nombre_materia'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="creditos">Créditos:</label>
                    <input type="number" class="form-control" id="creditos" name="creditos" 
                           value="<?= $materiaEditar['creditos'] ?? '' ?>" min="1" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="horas_teoricas">Horas Teóricas:</label>
                    <input type="number" class="form-control" id="horas_teoricas" name="horas_teoricas" 
                           value="<?= $materiaEditar['horas_teoricas'] ?? '' ?>" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="horas_practicas">Horas Prácticas:</label>
                    <input type="number" class="form-control" id="horas_practicas" name="horas_practicas" 
                           value="<?= $materiaEditar['horas_practicas'] ?? '' ?>" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="activa">Estado:</label>
                    <select class="form-control" id="activa" name="activa" required>
                        <option value="1" <?= isset($materiaEditar['activa']) && $materiaEditar['activa'] ? 'selected' : '' ?>>Activa</option>
                        <option value="0" <?= isset($materiaEditar['activa']) && !$materiaEditar['activa'] ? 'selected' : '' ?>>Inactiva</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary">
                <?= isset($materiaEditar) ? 'Actualizar Materia' : 'Guardar Materia' ?>
            </button>
            <button type="reset" class="btn btn-secondary">Limpiar Formulario</button>
            <?php if (isset($materiaEditar)): ?>
                <a href="agregar_materia.php" class="btn btn-outline-secondary">Cancelar Edición</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Lista de materias existentes -->
    <div class="mt-5">
        <h3>Materias Registradas</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Créditos</th>
                        <th>Horas (T/P)</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($listaMaterias)): ?>
                        <?php foreach ($listaMaterias as $materiaActual): ?>
                            <tr>
                                <td><?= htmlspecialchars($materiaActual['cod_materia']) ?></td>
                                <td><?= htmlspecialchars($materiaActual['nombre_materia']) ?></td>
                                <td><?= $materiaActual['creditos'] ?></td>
                                <td><?= $materiaActual['horas_teoricas'] ?> / <?= $materiaActual['horas_practicas'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $materiaActual['activa'] ? 'success' : 'danger' ?>">
                                        <?= $materiaActual['activa'] ? 'Activa' : 'Inactiva' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?action=edit&id=<?= $materiaActual['id'] ?>" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="?action=toggle&id=<?= $materiaActual['id'] ?>" 
                                       class="btn btn-sm btn-<?= $materiaActual['activa'] ? 'danger' : 'success' ?>">
                                        <i class="fas fa-power-off"></i> <?= $materiaActual['activa'] ? 'Desactivar' : 'Activar' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay materias registradas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>