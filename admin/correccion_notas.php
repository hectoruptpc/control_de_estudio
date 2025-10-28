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
$estudiante = null;
$carreras = [];
$materias = [];
$notas = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'buscar_estudiante':
                $cedula = trim($_POST['cedula'] ?? '');
                if (!empty($cedula)) {
                    $estudiante = buscarEstudiantePorCedula($cedula);
                    
                    echo "<!-- DEBUG: Estudiante encontrado: " . print_r($estudiante, true) . " -->";

                    if ($estudiante) {
                        $carreras = obtenerCarrerasEstudiante($estudiante['id']);
                        echo "<!-- DEBUG: Carreras encontradas: " . print_r($carreras, true) . " -->";
                    } else {
                        $mensaje = 'No se encontró ningún estudiante con esa cédula';
                        $tipo_mensaje = 'warning';
                    }
                }
                break;
                
            case 'seleccionar_carrera':
                $estudiante_id = $_POST['id_usuario'] ?? '';
                $id_carrera = $_POST['id_carrera'] ?? '';
                if (!empty($estudiante_id) && !empty($id_carrera)) {
                    $estudiante = obtenerEstudiantePorId($estudiante_id);
                    $materias = obtenerMateriasPorCarrera($id_carrera);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    echo "<!-- DEBUG: Materias encontradas: " . print_r($materias, true) . " -->";
                }
                break;
                
            case 'seleccionar_materia':
                $estudiante_id = $_POST['id_usuario'] ?? '';
                $id_carrera = $_POST['id_carrera'] ?? '';
                $id_materia = $_POST['id_materia'] ?? '';
                if (!empty($estudiante_id) && !empty($id_materia)) {
                    $estudiante = obtenerEstudiantePorId($estudiante_id);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    $materias = obtenerMateriasPorCarrera($id_carrera);
                    $notas = obtenerNotasEstudianteMateria($estudiante_id, $id_materia);
                    echo "<!-- DEBUG: Notas encontradas: " . print_r($notas, true) . " -->";
                }
                break;
                
            case 'editar_nota':
                $resultado = procesarEdicionNota();
                if ($resultado['success']) {
                    $mensaje = $resultado['message'];
                    $tipo_mensaje = 'success';
                    // Recargar datos
                    $estudiante_id = $_POST['id_usuario'] ?? '';
                    $id_carrera = $_POST['id_carrera'] ?? '';
                    $id_materia = $_POST['id_materia'] ?? '';
                    if (!empty($estudiante_id)) {
                        $estudiante = obtenerEstudiantePorId($estudiante_id);
                        $carreras = obtenerCarrerasEstudiante($estudiante_id);
                        $materias = obtenerMateriasPorCarrera($id_carrera);
                        $notas = obtenerNotasEstudianteMateria($estudiante_id, $id_materia);
                    }
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

            <!-- Paso 1: Buscar estudiante por cédula -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paso 1: Buscar Estudiante</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="accion" value="buscar_estudiante">
                        <div class="form-group mr-3 mb-2">
                            <label for="cedula" class="mr-2">Cédula del Estudiante:</label>
                            <input type="text" name="cedula" id="cedula" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>" 
                                   placeholder="Ingrese la cédula" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-search"></i> Buscar Estudiante
                        </button>
                    </form>
                    
                    <?php if ($estudiante): ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6>Estudiante Encontrado:</h6>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($estudiante['nombre']); ?></p>
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($estudiante['idusuario']); ?></p>
                        <p><strong>Carrera:</strong> <?php echo htmlspecialchars($estudiante['carrera']); ?></p>
                        <p><strong>ID Estudiante:</strong> <?php echo $estudiante['id']; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paso 2: Seleccionar Carrera -->
            <?php if ($estudiante && !empty($carreras)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paso 2: Seleccionar Carrera</h6>
                    <small>Se encontraron <?php echo count($carreras); ?> carrera(s)</small>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="accion" value="seleccionar_carrera">
                        <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
                        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
                        
                        <div class="form-group">
                            <label for="id_carrera">Seleccione la Carrera:</label>
                            <select name="id_carrera" id="id_carrera" class="form-control" required onchange="this.form.submit()">
                                <option value="">Seleccionar Carrera</option>
                                <?php foreach ($carreras as $carrera): ?>
                                <option value="<?php echo $carrera['id_carrera']; ?>" 
                                    <?php echo (isset($_POST['id_carrera']) && $_POST['id_carrera'] == $carrera['id_carrera']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($carrera['nombre_carrera']); ?> 
                                    (ID: <?php echo $carrera['id_carrera']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <?php elseif ($estudiante && empty($carreras)): ?>
            <div class="alert alert-warning">
                No se encontraron carreras para este estudiante. La carrera registrada es: 
                <strong><?php echo htmlspecialchars($estudiante['carrera']); ?></strong>
            </div>
            <?php endif; ?>

            <!-- Paso 3: Seleccionar Materia -->
<?php if ($estudiante && !empty($materias)): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Paso 3: Seleccionar Materia</h6>
        <small>Se encontraron <?php echo count($materias); ?> materia(s)</small>
    </div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="accion" value="seleccionar_materia">
            <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
            <input type="hidden" name="id_carrera" value="<?php echo htmlspecialchars($_POST['id_carrera'] ?? ''); ?>">
            <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
            
            <div class="form-group">
                <label for="id_materia">Seleccione la Materia:</label>
                <select name="id_materia" id="id_materia" class="form-control" required onchange="this.form.submit()">
                    <option value="">Seleccionar Materia</option>
                    <?php foreach ($materias as $materia): ?>
                    <option value="<?php echo $materia['id_materia']; ?>" 
                        <?php echo (isset($_POST['id_materia']) && $_POST['id_materia'] == $materia['id_materia']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($materia['nombre_materia']); ?> 
                        - Trayecto <?php echo $materia['trayecto']; ?>
                        - Semestre <?php echo $materia['semestre']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>
<?php elseif ($estudiante && isset($_POST['id_carrera'])): ?>
<div class="alert alert-warning">
    No se encontraron materias para la carrera seleccionada.
    <br><small>Verifique que existan relaciones en la tabla carrera_materia.</small>
</div>
<?php endif; ?>

            <!-- Paso 4: Mostrar y Editar Notas -->
            <?php if ($estudiante && !empty($notas)): ?>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Paso 4: Notas del Estudiante - 
                        <?php 
                        $materia_seleccionada = null;
                        if (isset($_POST['id_materia']) && !empty($materias)) {
                            foreach ($materias as $materia) {
                                if ($materia['id_materia'] == $_POST['id_materia']) {
                                    $materia_seleccionada = $materia;
                                    break;
                                }
                            }
                        }
                        if ($materia_seleccionada) {
                            echo htmlspecialchars($materia_seleccionada['nombre_materia']);
                        }
                        ?>
                    </h6>
                    <small>Se encontraron <?php echo count($notas); ?> registro(s) de notas</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Periodo Académico</th>
                                    <th>Trayecto 0</th>
                                    <th>Trayecto 1</th>
                                    <th>Trayecto 2</th>
                                    <th>Trayecto 3</th>
                                    <th>Trayecto 4</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notas as $nota): ?>
                                <tr>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($nota['nombre_periodo']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo ($nota['trayecto_0'] === null || $nota['trayecto_0'] === '') ? 'secondary' : ($nota['trayecto_0'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                            <?php echo ($nota['trayecto_0'] !== null && $nota['trayecto_0'] !== '') ? number_format($nota['trayecto_0'], 2) : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo ($nota['trayecto_1'] === null || $nota['trayecto_1'] === '') ? 'secondary' : ($nota['trayecto_1'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                            <?php echo ($nota['trayecto_1'] !== null && $nota['trayecto_1'] !== '') ? number_format($nota['trayecto_1'], 2) : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo ($nota['trayecto_2'] === null || $nota['trayecto_2'] === '') ? 'secondary' : ($nota['trayecto_2'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                            <?php echo ($nota['trayecto_2'] !== null && $nota['trayecto_2'] !== '') ? number_format($nota['trayecto_2'], 2) : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo ($nota['trayecto_3'] === null || $nota['trayecto_3'] === '') ? 'secondary' : ($nota['trayecto_3'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                            <?php echo ($nota['trayecto_3'] !== null && $nota['trayecto_3'] !== '') ? number_format($nota['trayecto_3'], 2) : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo ($nota['trayecto_4'] === null || $nota['trayecto_4'] === '') ? 'secondary' : ($nota['trayecto_4'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                            <?php echo ($nota['trayecto_4'] !== null && $nota['trayecto_4'] !== '') ? number_format($nota['trayecto_4'], 2) : 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarNota<?php echo $nota['id']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php elseif ($estudiante && isset($_POST['id_materia'])): ?>
            <div class="alert alert-info">
                No se encontraron notas registradas para esta materia.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>