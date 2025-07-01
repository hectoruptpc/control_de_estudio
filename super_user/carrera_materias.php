<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');



$titulopag = "Gestión de Materias por Carrera";
include('../funciones/functions.php');
include("includes/head.php");

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar_materia'])) {
        $resultado = asignarMateriaACarrera(
            intval($_POST['id_carrera']),
            intval($_POST['id_materia']),
            intval($_POST['semestre'])
        );
        
        if ($resultado['success']) {
            $mensaje = htmlspecialchars($resultado['message']);
        } else {
            $error = htmlspecialchars($resultado['message']);
        }
    }
    
    if (isset($_POST['eliminar_asignacion'])) {
        $resultado = eliminarAsignacionMateria(intval($_POST['id_relacion']));
        
        if ($resultado['success']) {
            $mensaje = htmlspecialchars($resultado['message']);
        } else {
            $error = htmlspecialchars($resultado['message']);
        }
    }
}

// Obtener datos
$carreras = obtenerCarrerasActivas();
$carrera_seleccionada = isset($_POST['id_carrera']) ? intval($_POST['id_carrera']) : ($carreras[0]['id_carrera'] ?? 0);
$materias_disponibles = obtenerMateriasDisponibles($carrera_seleccionada);
$materias_asignadas = obtenerMateriasAsignadas($carrera_seleccionada);
?>

<div class="container-fluid">
    <h1 class="mt-4"><?= htmlspecialchars($titulopag) ?></h1>
    
    <?php if (isset($mensaje)): ?>
    <div class="alert alert-success">
        <?= is_array($mensaje) ? implode('<br>', $mensaje) : $mensaje ?>
    </div>
<?php endif; ?>


    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-circle mr-1"></i>
                    Asignar Materia
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Carrera:</label>
                            <select name="id_carrera" class="form-control" required>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?= intval($carrera['id_carrera']) ?>" 
                                        <?= ($carrera['id_carrera'] == $carrera_seleccionada) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Materia:</label>
                            <select name="id_materia" class="form-control" required>
                                <?php foreach ($materias_disponibles as $materia): ?>
                                    <option value="<?= intval($materia['id_materia']) ?>">
                                        <?= htmlspecialchars($materia['cod_materia']) ?> - <?= htmlspecialchars($materia['nombre_materia']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Trimestre/Semestre:</label>
                            <select name="semestre" class="form-control" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>">Periodo <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="agregar_materia" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-list mr-1"></i>
                    Materias Asignadas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Carrera</th>
                                    <th>Materia</th>
                                    <th>Semestre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carreras as $carrera): ?>
                                    <?php $materias = obtenerMateriasAsignadas($carrera['id_carrera']); ?>
                                    <?php foreach ($materias as $materia): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($carrera['cod_carrera']) ?></td>
                                            <td><?= htmlspecialchars($materia['cod_materia']) ?> - <?= htmlspecialchars($materia['nombre_materia']) ?></td>
                                            <td><?= intval($materia['semestre']) ?></td>
                                            <td>
                                                <form method="POST" style="display:inline;">
                                                    <input type="hidden" name="id_relacion" value="<?= intval($materia['id_relacion']) ?>">
                                                    <button type="submit" name="eliminar_asignacion" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('¿Eliminar esta asignación?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>