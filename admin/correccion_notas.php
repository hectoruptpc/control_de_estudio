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
                    // CAMBIO AQUÍ: Usar la nueva función que filtra por materias con notas
                    $materias = obtenerMateriasConNotas($estudiante_id, $id_carrera);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    echo "<!-- DEBUG: Materias con notas encontradas: " . print_r($materias, true) . " -->";
                }
                break;
                
            case 'seleccionar_materia':
                $estudiante_id = $_POST['id_usuario'] ?? '';
                $id_carrera = $_POST['id_carrera'] ?? '';
                $id_materia = $_POST['id_materia'] ?? '';
                if (!empty($estudiante_id) && !empty($id_materia)) {
                    $estudiante = obtenerEstudiantePorId($estudiante_id);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    // CAMBIO AQUÍ: Usar la nueva función que filtra por materias con notas
                    $materias = obtenerMateriasConNotas($estudiante_id, $id_carrera);
                    $notas = obtenerNotasEstudianteMateria($estudiante_id, $id_materia);
                    echo "<!-- DEBUG: Notas encontradas para materia $id_materia: " . print_r($notas, true) . " -->";
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
                        // CAMBIO AQUÍ: Usar la nueva función que filtra por materias con notas
                        $materias = obtenerMateriasConNotas($estudiante_id, $id_carrera);
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

<style>
.justificacion-texto {
    max-height: 80px;
    overflow-y: auto;
    font-size: 0.85rem;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.6em;
}

.historial-table th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    z-index: 10;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Corrección de Notas</h1>
            
            <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje === 'success' ? 'success' : ($tipo_mensaje === 'warning' ? 'warning' : 'danger'); ?> alert-dismissible fade show" role="alert">
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
                    <small>Se encontraron <?php echo count($materias); ?> materia(s) con notas</small>
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
            <?php elseif ($estudiante && isset($_POST['id_carrera']) && empty($materias)): ?>
            <div class="alert alert-warning">
                No se encontraron materias con notas para este estudiante en la carrera seleccionada.
            </div>
            <?php endif; ?>

            <!-- Paso 4: Mostrar y Editar Notas -->
            <?php if ($estudiante && isset($_POST['id_materia'])): ?>
                <?php if (!empty($notas)): ?>
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
                                        <td class="font-weight-bold"><?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?></td>
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

                                    <!-- Modal para Editar Nota -->
                                    <div class="modal fade" id="modalEditarNota<?php echo $nota['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditarNotaLabel<?php echo $nota['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditarNotaLabel<?php echo $nota['id']; ?>">Editar Nota - <?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="accion" value="editar_nota">
                                                        <input type="hidden" name="id_nota" value="<?php echo $nota['id']; ?>">
                                                        <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
                                                        <input type="hidden" name="id_carrera" value="<?php echo htmlspecialchars($_POST['id_carrera'] ?? ''); ?>">
                                                        <input type="hidden" name="id_materia" value="<?php echo htmlspecialchars($_POST['id_materia'] ?? ''); ?>">
                                                        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
                                                        
                                                        <div class="form-group">
                                                            <label for="trayecto_<?php echo $nota['id']; ?>">Seleccione el Trayecto a Editar:</label>
                                                            <select name="trayecto" id="trayecto_<?php echo $nota['id']; ?>" class="form-control" required>
                                                                <option value="">Seleccionar Trayecto</option>
                                                                <?php if ($nota['trayecto_0'] !== null): ?>
                                                                <option value="trayecto_0">Trayecto 0: <?php echo number_format($nota['trayecto_0'], 2); ?></option>
                                                                <?php endif; ?>
                                                                <?php if ($nota['trayecto_1'] !== null): ?>
                                                                <option value="trayecto_1">Trayecto 1: <?php echo number_format($nota['trayecto_1'], 2); ?></option>
                                                                <?php endif; ?>
                                                                <?php if ($nota['trayecto_2'] !== null): ?>
                                                                <option value="trayecto_2">Trayecto 2: <?php echo number_format($nota['trayecto_2'], 2); ?></option>
                                                                <?php endif; ?>
                                                                <?php if ($nota['trayecto_3'] !== null): ?>
                                                                <option value="trayecto_3">Trayecto 3: <?php echo number_format($nota['trayecto_3'], 2); ?></option>
                                                                <?php endif; ?>
                                                                <?php if ($nota['trayecto_4'] !== null): ?>
                                                                <option value="trayecto_4">Trayecto 4: <?php echo number_format($nota['trayecto_4'], 2); ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="nueva_nota_<?php echo $nota['id']; ?>">Nueva Nota:</label>
                                                            <input type="number" name="nueva_nota" id="nueva_nota_<?php echo $nota['id']; ?>" 
                                                                   class="form-control" step="0.01" min="0" max="20" required>
                                                            <small class="form-text text-muted">La nota debe estar entre 0 y 20</small>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label for="justificacion_<?php echo $nota['id']; ?>">Justificación del Cambio:</label>
                                                            <textarea name="justificacion" id="justificacion_<?php echo $nota['id']; ?>" 
                                                                      class="form-control" rows="3" required 
                                                                      placeholder="Explique detalladamente por qué se realiza este cambio..."></textarea>
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

                                    <!-- Modal para Ver Historial - MEJORADO -->
                                    <div class="modal fade" id="modalHistorial<?php echo $nota['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHistorialLabel<?php echo $nota['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title" id="modalHistorialLabel<?php echo $nota['id']; ?>">
                                                        <i class="fas fa-history"></i> Historial de Cambios - 
                                                        <?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?>
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php 
                                                    // Cargar el historial para esta nota específica
                                                    $historial = obtenerHistorialCambiosNota($nota['id']);
                                                    
                                                    if (!empty($historial)): 
                                                    ?>
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i> 
                                                        Se encontraron <strong><?php echo count($historial); ?></strong> cambio(s) en esta nota.
                                                    </div>
                                                    
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-sm historial-table">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>Fecha y Hora</th>
                                                                    <th>Administrador</th>
                                                                    <th>Trayecto</th>
                                                                    <th>Nota Anterior</th>
                                                                    <th>Nota Nueva</th>
                                                                    <th>Cambio</th>
                                                                    <th>Justificación</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($historial as $cambio): 
                                                                    $diferencia = $cambio['nota_nueva'] - $cambio['nota_anterior'];
                                                                    $clase_cambio = $diferencia > 0 ? 'text-success' : ($diferencia < 0 ? 'text-danger' : 'text-warning');
                                                                    $icono_cambio = $diferencia > 0 ? 'fa-arrow-up' : ($diferencia < 0 ? 'fa-arrow-down' : 'fa-equals');
                                                                ?>
                                                                <tr>
                                                                    <td class="font-weight-bold">
                                                                        <i class="fas fa-calendar-alt"></i> 
                                                                        <?php echo date('d/m/Y', strtotime($cambio['fecha_cambio'])); ?>
                                                                        <br>
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-clock"></i> 
                                                                            <?php echo date('H:i:s', strtotime($cambio['fecha_cambio'])); ?>
                                                                        </small>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-primary">
                                                                            <i class="fas fa-user"></i> 
                                                                            <?php echo htmlspecialchars($cambio['admin_nombre'] ?? 'Sistema'); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-secondary">
                                                                            Trayecto <?php echo htmlspecialchars($cambio['trayecto']); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-<?php echo ($cambio['nota_anterior'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                                                            <?php echo number_format($cambio['nota_anterior'], 2); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-<?php echo ($cambio['nota_nueva'] >= 10 ? 'success' : 'danger'); ?> p-2">
                                                                            <?php echo number_format($cambio['nota_nueva'], 2); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td class="<?php echo $clase_cambio; ?> font-weight-bold">
                                                                        <i class="fas <?php echo $icono_cambio; ?>"></i>
                                                                        <?php echo ($diferencia > 0 ? '+' : '') . number_format($diferencia, 2); ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="justificacion-texto">
                                                                            <?php echo nl2br(htmlspecialchars($cambio['justificacion'])); ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <!-- Resumen del Historial -->
                                                    <div class="row mt-3">
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center">
                                                                    <h6 class="card-title">Total de Cambios</h6>
                                                                    <h3 class="text-primary"><?php echo count($historial); ?></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center">
                                                                    <h6 class="card-title">Primer Cambio</h6>
                                                                    <small class="text-muted">
                                                                        <?php echo date('d/m/Y H:i', strtotime(end($historial)['fecha_cambio'])); ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light">
                                                                <div class="card-body text-center">
                                                                    <h6 class="card-title">Último Cambio</h6>
                                                                    <small class="text-muted">
                                                                        <?php echo date('d/m/Y H:i', strtotime($historial[0]['fecha_cambio'])); ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php else: ?>
                                                    <div class="alert alert-warning text-center">
                                                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                                        <h5>No hay historial de cambios</h5>
                                                        <p class="mb-0">Esta nota no ha sido modificada aún.</p>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    No se encontraron notas registradas para esta materia.
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Función para imprimir el historial
function imprimirHistorial(idNota) {
    const modalContent = document.querySelector('#modalHistorial' + idNota + ' .modal-content').cloneNode(true);
    
    // Remover botones del footer
    const footer = modalContent.querySelector('.modal-footer');
    if (footer) footer.remove();
    
    // Crear ventana de impresión
    const ventanaImpresion = window.open('', '_blank');
    ventanaImpresion.document.write(`
        <html>
            <head>
                <title>Historial de Cambios - Nota ${idNota}</title>
                <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    .table { font-size: 12px; }
                    .badge { font-size: 11px; }
                    .justificacion-texto { max-height: none; }
                </style>
            </head>
            <body>
                <h4 class="text-center">Historial de Cambios - Nota ${idNota}</h4>
                <p class="text-center text-muted">Generado el: ${new Date().toLocaleDateString()}</p>
                ${modalContent.innerHTML}
            </body>
        </html>
    `);
    ventanaImpresion.document.close();
    ventanaImpresion.print();
}

// Función para mejorar la experiencia del modal
$(document).ready(function() {
    $('.modal').on('shown.bs.modal', function() {
        $(this).find('.table-responsive').css('max-height', '400px');
    });
});
</script>

<?php include("includes/footer.php"); ?>