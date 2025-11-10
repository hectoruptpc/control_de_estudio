<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Corrección de Notas";
include('../funciones/functions.php');

// Verificar permisos y sesión
cargarPermisosUsuario();
verificarPermiso('editar_nota');

// NUEVA FUNCIÓN: Obtener materias con notas del estudiante
function obtenerMateriasConNotas($id_estudiante, $id_carrera) {
    global $db;
    
    $sql = "SELECT DISTINCT m.*, cm.semestre
            FROM materias m
            INNER JOIN carrera_materia cm ON m.id_materia = cm.id_materia
            INNER JOIN notas_definitivas nd ON m.id_materia = nd.id_materia
            WHERE cm.id_carrera = ? 
            AND nd.id_usuario = ?
            AND (
                nd.trayecto_0 IS NOT NULL OR 
                nd.trayecto_1 IS NOT NULL OR 
                nd.trayecto_2 IS NOT NULL OR 
                nd.trayecto_3 IS NOT NULL OR 
                nd.trayecto_4 IS NOT NULL
            )
            ORDER BY m.trayecto, cm.semestre, m.nombre_materia";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_carrera, $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias = [];
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    
    $stmt->close();
    return $materias;
}

// FUNCIÓN: Obtener notas del estudiante para una materia específica
function obtenerNotasEstudianteMateria($id_estudiante, $id_materia) {
    global $db;
    
    $sql = "SELECT nd.*, pa.nombre_periodo 
            FROM notas_definitivas nd
            LEFT JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
            WHERE nd.id_usuario = ? AND nd.id_materia = ?
            ORDER BY pa.nombre_periodo DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id_estudiante, $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notas = [];
    while ($row = $result->fetch_assoc()) {
        $notas[] = $row;
    }
    
    $stmt->close();
    return $notas;
}

// FUNCIÓN: Obtener historial de cambios de una nota
function obtenerHistorialCambiosNota($id_nota) {
    global $db;
    
    $sql = "SELECT h.*, u.nombre as admin_nombre 
            FROM historial_cambios_notas h 
            LEFT JOIN users u ON h.id_admin = u.id 
            WHERE h.id_nota = ? 
            ORDER BY h.fecha_cambio DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_nota);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $historial = [];
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    $stmt->close();
    return $historial;
}

// FUNCIÓN: Procesar edición de nota
function procesarEdicionNota() {
    global $db;
    
    if (!isset($_POST['id_nota']) || !isset($_POST['trayecto']) || !isset($_POST['nueva_nota'])) {
        return ['success' => false, 'message' => 'Datos incompletos'];
    }
    
    $id_nota = intval($_POST['id_nota']);
    $trayecto = $_POST['trayecto'];
    $nueva_nota = $_POST['nueva_nota'];
    $justificacion = trim($_POST['justificacion'] ?? '');
    
    // Obtener el ID del administrador de la sesión - CORREGIDO según la estructura del index
    if (isset($_SESSION['user']['id'])) {
        $id_admin = $_SESSION['user']['id'];
    } elseif (isset($_SESSION['id'])) {
        $id_admin = $_SESSION['id'];
    } elseif (isset($_SESSION['user_id'])) {
        $id_admin = $_SESSION['user_id'];
    } else {
        // Debug para ver qué hay en la sesión
        error_log("Session data: " . print_r($_SESSION, true));
        return ['success' => false, 'message' => 'No se pudo identificar al administrador. Sesión: ' . print_r($_SESSION, true)];
    }
    
    // Validar que la justificación no esté vacía
    if (empty($justificacion)) {
        return ['success' => false, 'message' => 'La justificación es obligatoria'];
    }
    
    // Validar que la nota sea numérica y esté entre 0 y 20
    if (!is_numeric($nueva_nota) || $nueva_nota < 0 || $nueva_nota > 20) {
        return ['success' => false, 'message' => 'La nota debe ser un número entre 0 y 20'];
    }
    
    // Obtener la nota actual
    $sql_actual = "SELECT trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4 
                   FROM notas_definitivas WHERE id = ?";
    $stmt = $db->prepare($sql_actual);
    $stmt->bind_param("i", $id_nota);
    $stmt->execute();
    $result = $stmt->get_result();
    $nota_actual = $result->fetch_assoc();
    $stmt->close();
    
    if (!$nota_actual) {
        return ['success' => false, 'message' => 'No se encontró la nota a editar'];
    }
    
    // Determinar la nota anterior según el trayecto
    $nota_anterior = $nota_actual[$trayecto];
    
    // Iniciar transacción
    $db->begin_transaction();
    
    try {
        // 1. Actualizar la nota en notas_definitivas
        $sql_update = "UPDATE notas_definitivas SET {$trayecto} = ? WHERE id = ?";
        $stmt = $db->prepare($sql_update);
        $stmt->bind_param("di", $nueva_nota, $id_nota);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la nota");
        }
        $stmt->close();
        
        // 2. Registrar el cambio en el historial
        $sql_historial = "INSERT INTO historial_cambios_notas 
                         (id_nota, trayecto, nota_anterior, nota_nueva, justificacion, id_admin, fecha_cambio) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($sql_historial);
        $trayecto_numero = str_replace('trayecto_', '', $trayecto);
        $stmt->bind_param("isddsi", $id_nota, $trayecto_numero, $nota_anterior, $nueva_nota, $justificacion, $id_admin);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al registrar en el historial");
        }
        $stmt->close();
        
        // Confirmar transacción
        $db->commit();
        
        return ['success' => true, 'message' => 'Nota actualizada correctamente'];
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $db->rollback();
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

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
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalHistorial<?php echo $nota['id']; ?>">
                                                <i class="fas fa-history"></i> Historial
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

                                    <!-- Modal para Ver Historial - CORREGIDO -->
                                    <div class="modal fade" id="modalHistorial<?php echo $nota['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHistorialLabel<?php echo $nota['id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalHistorialLabel<?php echo $nota['id']; ?>">Historial de Cambios - <?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php 
                                                    // Cargar el historial directamente aquí
                                                    $historial = obtenerHistorialCambiosNota($nota['id']);
                                                    echo "<!-- DEBUG Historial para nota {$nota['id']}: " . print_r($historial, true) . " -->";
                                                    if (!empty($historial)): 
                                                    ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Administrador</th>
                                                                    <th>Trayecto</th>
                                                                    <th>Nota Anterior</th>
                                                                    <th>Nota Nueva</th>
                                                                    <th>Justificación</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($historial as $cambio): ?>
                                                                <tr>
                                                                    <td><?php echo date('d/m/Y H:i', strtotime($cambio['fecha_cambio'])); ?></td>
                                                                    <td><?php echo htmlspecialchars($cambio['admin_nombre'] ?? 'N/A'); ?></td>
                                                                    <td>Trayecto <?php echo htmlspecialchars($cambio['trayecto']); ?></td>
                                                                    <td>
                                                                        <span class="badge badge-<?php echo ($cambio['nota_anterior'] >= 10 ? 'success' : 'danger'); ?>">
                                                                            <?php echo number_format($cambio['nota_anterior'], 2); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-<?php echo ($cambio['nota_nueva'] >= 10 ? 'success' : 'danger'); ?>">
                                                                            <?php echo number_format($cambio['nota_nueva'], 2); ?>
                                                                        </span>
                                                                    </td>
                                                                    <td><?php echo htmlspecialchars($cambio['justificacion']); ?></td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <?php else: ?>
                                                    <div class="alert alert-info">
                                                        No hay historial de cambios para esta nota.
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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

<?php include("includes/footer.php"); ?>