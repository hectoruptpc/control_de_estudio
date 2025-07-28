<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Secciones";
include('../funciones/functions.php');

// Obtener parámetros de acción
$action = $_POST['action'] ?? 'list';
$seccion_id = $_POST['id'] ?? 0;
$periodo_id = $_POST['periodo'] ?? 0;

// Constante para el mínimo de estudiantes requeridos
define('MINIMO_ESTUDIANTES', 15);

// Procesar formularios antes de cualquier output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_seccion'])) {
        // Procesar creación de sección
        $codigo = trim($_POST['codigo_seccion']);
        $carrera = (int)$_POST['id_carrera'];
        $trayecto = (int)$_POST['id_trayecto'];
        $periodo = (int)$_POST['id_periodo'];
        $maximo = (int)$_POST['capacidad_maxima'];
        
        try {
            // Crear la sección como inactiva por defecto
            $stmt = $db->prepare("INSERT INTO secciones (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, estatus) 
                                VALUES (?, ?, ?, ?, ?, 'inactiva')");
            $stmt->bind_param("siiii", $codigo, $carrera, $trayecto, $periodo, $maximo);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['success'] = "Sección creada exitosamente! La sección estará inactiva hasta tener al menos ".MINIMO_ESTUDIANTES." estudiantes.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al crear sección: " . $e->getMessage();
        }
        header("Location: gestion_seccion.php");
        exit();
        
    } elseif (isset($_POST['asignar_estudiantes'])) {
        // Procesar asignación de estudiantes
        $seccion_id = (int)$_POST['seccion_id'];
        $estudiantes = $_POST['estudiantes'] ?? [];
        
        try {
            $db->begin_transaction();
            
            // Eliminar asignaciones no seleccionadas
            if (!empty($estudiantes)) {
                $placeholders = implode(',', array_fill(0, count($estudiantes), '?'));
                $types = str_repeat('i', count($estudiantes));
                
                $stmt = $db->prepare("UPDATE estudiante_seccion 
                                    SET estatus = 'retirado'
                                    WHERE id_seccion = ? 
                                    AND id_usuario NOT IN ($placeholders)");
                
                $params = array_merge([$seccion_id], $estudiantes);
                $stmt->bind_param(str_repeat('i', count($params)), ...$params);
                $stmt->execute();
                $stmt->close();
            } else {
                // Si no hay estudiantes seleccionados, marcar todos como retirados
                $stmt = $db->prepare("UPDATE estudiante_seccion 
                                    SET estatus = 'retirado'
                                    WHERE id_seccion = ?");
                $stmt->bind_param("i", $seccion_id);
                $stmt->execute();
                $stmt->close();
            }
            
            // Insertar nuevas asignaciones
            foreach ($estudiantes as $est_id) {
                $est_id = (int)$est_id;
                $stmt = $db->prepare("INSERT INTO estudiante_seccion (id_usuario, id_seccion, fecha_inscripcion, estatus)
                                    VALUES (?, ?, CURDATE(), 'activo')
                                    ON DUPLICATE KEY UPDATE estatus = 'activo'");
                $stmt->bind_param("ii", $est_id, $seccion_id);
                $stmt->execute();
                $stmt->close();
            }
            
            // Verificar si se alcanza el mínimo de estudiantes para activar la sección
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM estudiante_seccion 
                                WHERE id_seccion = ? AND estatus = 'activo'");
            $stmt->bind_param("i", $seccion_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_assoc()['total'];
            $stmt->close();
            
            // Actualizar estado de la sección según el número de estudiantes
            $nuevo_estatus = ($count >= MINIMO_ESTUDIANTES) ? 'activa' : 'inactiva';
            $stmt = $db->prepare("UPDATE secciones SET estatus = ? WHERE id_seccion = ?");
            $stmt->bind_param("si", $nuevo_estatus, $seccion_id);
            $stmt->execute();
            $stmt->close();
            
            $db->commit();
            
            if ($count >= MINIMO_ESTUDIANTES) {
                $_SESSION['success'] = "Asignación de estudiantes actualizada! La sección ha sido activada al alcanzar el mínimo requerido.";
            } else {
                $_SESSION['warning'] = "Asignación de estudiantes actualizada! La sección permanecerá inactiva hasta tener al menos ".MINIMO_ESTUDIANTES." estudiantes (actualmente tiene $count).";
            }
        } catch (Exception $e) {
            $db->rollback();
            $_SESSION['error'] = "Error al asignar estudiantes: " . $e->getMessage();
        }
        // Redirigir con POST
        header("Location: gestion_seccion.php?action=view&id=".$seccion_id);
        exit();
    }
}

// Ahora incluimos el head después de cualquier posible redirección
include("includes/head.php");
?>

<div class="container-fluid">
    <?php 
    // Mostrar mensajes después de incluir el head
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['warning'])) {
        echo '<div class="alert alert-warning">' . $_SESSION['warning'] . '</div>';
        unset($_SESSION['warning']);
    }
    
    if ($action === 'list'): ?>
        <!-- LISTADO DE SECCIONES -->
        <h1 class="h3 mb-4 text-gray-800">Gestión de Secciones</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Secciones</h6>
                <form method="post" style="display:inline">
                    <input type="hidden" name="action" value="new">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle"></i> Nueva Sección
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Nota:</strong> Las secciones requieren al menos <?= MINIMO_ESTUDIANTES ?> estudiantes para activarse.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Carrera</th>
                                <th>Trayecto</th>
                                <th>Período</th>
                                <th>Estudiantes</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT s.id_seccion, s.codigo_seccion, c.nombre_carrera, t.numero_trayecto, 
                                     p.nombre_periodo, s.capacidad_maxima, s.estatus,
                                     COUNT(es.id_usuario) as inscritos
                              FROM secciones s
                              JOIN carreras c ON s.id_carrera = c.id_carrera
                              JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                              JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                              LEFT JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion AND es.estatus = 'activo'
                              GROUP BY s.id_seccion
                              ORDER BY p.nombre_periodo DESC, s.codigo_seccion";
                            $stmt = $db->prepare($query);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $secciones = $result->fetch_all(MYSQLI_ASSOC);
                            $stmt->close();
                            
                            foreach ($secciones as $seccion) {
                                $porcentaje = $seccion['capacidad_maxima'] > 0 ? 
                                    round(($seccion['inscritos'] / $seccion['capacidad_maxima']) * 100) : 0;
                                $estado_clase = ($seccion['estatus'] == 'activa') ? 'success' : 
                                               (($seccion['estatus'] == 'completa') ? 'warning' : 'danger');
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($seccion['codigo_seccion']) ?></td>
                                <td><?= htmlspecialchars($seccion['nombre_carrera']) ?></td>
                                <td>Trayecto <?= $seccion['numero_trayecto'] ?></td>
                                <td><?= htmlspecialchars($seccion['nombre_periodo']) ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar <?= $porcentaje >= 80 ? 'bg-success' : 'bg-info' ?>" 
                                             role="progressbar" style="width: <?= $porcentaje ?>%">
                                            <?= $seccion['inscritos'] ?>/<?= $seccion['capacidad_maxima'] ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $seccion['capacidad_maxima'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $estado_clase ?>">
                                        <?= ucfirst($seccion['estatus']) ?>
                                        <?php if ($seccion['estatus'] == 'inactiva' && $seccion['inscritos'] > 0): ?>
                                            <br><small>(Faltan <?= MINIMO_ESTUDIANTES - $seccion['inscritos'] ?> para activar)</small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="action" value="view">
                                        <input type="hidden" name="id" value="<?= $seccion['id_seccion'] ?>">
                                        <button type="submit" class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="id" value="<?= $seccion['id_seccion'] ?>">
                                        <button type="submit" class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                    <form method="post" style="display:inline">
                                        <input type="hidden" name="action" value="assign">
                                        <input type="hidden" name="id" value="<?= $seccion['id_seccion'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning" title="Asignar Estudiantes">
                                            <i class="fas fa-users"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($action === 'new' || $action === 'edit'): ?>
        <!-- FORMULARIO DE EDICIÓN/CREACIÓN -->
        <?php
        // Obtener datos para selects
        $stmt = $db->prepare("SELECT id_carrera, nombre_carrera FROM carreras WHERE activa = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $carreras = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        $stmt = $db->prepare("SELECT id_trayecto, numero_trayecto FROM trayectos ORDER BY numero_trayecto");
        $stmt->execute();
        $result = $stmt->get_result();
        $trayectos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        $stmt = $db->prepare("SELECT id_periodo, nombre_periodo FROM periodos_academicos WHERE activo = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $periodos = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Datos de la sección si es edición
        $seccion = [];
        if ($action === 'edit' && $seccion_id > 0) {
            $stmt = $db->prepare("SELECT * FROM secciones WHERE id_seccion = ?");
            $stmt->bind_param("i", $seccion_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $seccion = $result->fetch_assoc();
            $stmt->close();
        }
        ?>
        
        <h1 class="h3 mb-4 text-gray-800"><?= $action === 'new' ? 'Nueva' : 'Editar' ?> Sección</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Datos de la Sección</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Importante:</strong> La sección se activará automáticamente cuando tenga al menos <?= MINIMO_ESTUDIANTES ?> estudiantes asignados.
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="<?= $action === 'new' ? 'crear_seccion' : 'editar_seccion' ?>">
                    <?php if ($action === 'edit'): ?>
                        <input type="hidden" name="id" value="<?= $seccion_id ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="codigo_seccion">Código de Sección *</label>
                            <input type="text" class="form-control" id="codigo_seccion" name="codigo_seccion" 
                                   value="<?= $seccion['codigo_seccion'] ?? '' ?>" required>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label for="id_carrera">Carrera *</label>
                            <select class="form-control" id="id_carrera" name="id_carrera" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($carreras as $carrera): ?>
                                    <option value="<?= $carrera['id_carrera'] ?>" 
                                        <?= isset($seccion['id_carrera']) && $seccion['id_carrera'] == $carrera['id_carrera'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($carrera['nombre_carrera']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label for="id_trayecto">Trayecto *</label>
                            <select class="form-control" id="id_trayecto" name="id_trayecto" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($trayectos as $trayecto): ?>
                                    <option value="<?= $trayecto['id_trayecto'] ?>" 
                                        <?= isset($seccion['id_trayecto']) && $seccion['id_trayecto'] == $trayecto['id_trayecto'] ? 'selected' : '' ?>>
                                        <?= $trayecto['numero_trayecto'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="id_periodo">Período *</label>
                            <select class="form-control" id="id_periodo" name="id_periodo" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= $periodo['id_periodo'] ?>" 
                                        <?= isset($seccion['id_periodo']) && $seccion['id_periodo'] == $periodo['id_periodo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($periodo['nombre_periodo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="capacidad_maxima">Capacidad Máxima *</label>
                            <input type="number" class="form-control" id="capacidad_maxima" name="capacidad_maxima" 
                                   value="<?= $seccion['capacidad_maxima'] ?? 30 ?>" min="<?= MINIMO_ESTUDIANTES ?>" required>
                            <small class="form-text text-muted">Mínimo <?= MINIMO_ESTUDIANTES ?> estudiantes</small>
                        </div>
                    </div>
                    
                    <button type="submit" name="<?= $action === 'new' ? 'crear_seccion' : 'editar_seccion' ?>" 
                            class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="list">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </form>
                </form>
            </div>
        </div>

    <?php elseif ($action === 'assign' && $seccion_id > 0): ?>
        <!-- ASIGNACIÓN DE ESTUDIANTES -->
        <?php
        $stmt = $db->prepare("SELECT s.*, c.nombre_carrera, COUNT(es.id_usuario) as inscritos
                      FROM secciones s
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      LEFT JOIN estudiante_seccion es ON s.id_seccion = es.id_seccion AND es.estatus = 'activo'
                      WHERE s.id_seccion = ?");
        $stmt->bind_param("i", $seccion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $seccion = $result->fetch_assoc();
        $stmt->close();
        
        $stmt = $db->prepare("SELECT u.id, u.nombre, u.username, 
                             (SELECT COUNT(*) FROM estudiante_seccion 
                              WHERE id_usuario = u.id AND id_seccion = ? AND estatus = 'activo') as asignado
                      FROM users u
                      WHERE u.estudiante = 1 AND u.status = 1 AND u.carrera = ?
                      ORDER BY u.nombre");
        $stmt->bind_param("ii", $seccion_id, $seccion['id_carrera']);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        ?>
        
        <h1 class="h3 mb-4 text-gray-800">Asignar Estudiantes</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Sección: <?= htmlspecialchars($seccion['codigo_seccion']) ?> - <?= htmlspecialchars($seccion['nombre_carrera']) ?>
                </h6>
                <div>
                    <span class="badge badge-<?= $seccion['estatus'] == 'activa' ? 'success' : 'warning' ?>">
                        <?= ucfirst($seccion['estatus']) ?>
                    </span>
                    <span class="badge badge-info ml-2">
                        Cupos: <?= $seccion['inscritos'] ?>/<?= $seccion['capacidad_maxima'] ?>
                    </span>
                    <?php if ($seccion['estatus'] == 'inactiva'): ?>
                        <span class="badge badge-danger ml-2">
                            Faltan <?= MINIMO_ESTUDIANTES - $seccion['inscritos'] ?> para activar
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="asignar_estudiantes">
                    <input type="hidden" name="seccion_id" value="<?= $seccion_id ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaEstudiantes" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" id="seleccionarTodos">
                                    </th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Asignado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes as $est): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="estudiantes[]" value="<?= $est['id'] ?>"
                                                <?= $est['asignado'] > 0 ? 'checked' : '' ?>>
                                        </td>
                                        <td><?= htmlspecialchars($est['nombre']) ?></td>
                                        <td><?= htmlspecialchars($est['username']) ?></td>
                                        <td>
                                            <?php if ($est['asignado'] > 0): ?>
                                                <span class="badge badge-success">Sí</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="submit" name="asignar_estudiantes" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Asignaciones
                    </button>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="action" value="view">
                        <input type="hidden" name="id" value="<?= $seccion_id ?>">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </form>
                </form>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#tablaEstudiantes').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                }
            });
            
            // Seleccionar/deseleccionar todos
            $('#seleccionarTodos').change(function() {
                $('input[name="estudiantes[]"]').prop('checked', this.checked);
            });
            
            // Si se deselecciona un estudiante, desmarcar "seleccionar todos"
            $('input[name="estudiantes[]"]').change(function() {
                if (!this.checked) {
                    $('#seleccionarTodos').prop('checked', false);
                }
            });
        });
        </script>

    <?php elseif ($action === 'view' && $seccion_id > 0): ?>
        <!-- VISTA DETALLADA DE SECCIÓN -->
        <?php
        $stmt = $db->prepare("SELECT s.*, c.nombre_carrera, t.numero_trayecto, p.nombre_periodo
                      FROM secciones s
                      JOIN carreras c ON s.id_carrera = c.id_carrera
                      JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                      JOIN periodos_academicos p ON s.id_periodo = p.id_periodo
                      WHERE s.id_seccion = ?");
        $stmt->bind_param("i", $seccion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $seccion = $result->fetch_assoc();
        $stmt->close();
        
        $stmt = $db->prepare("SELECT u.id, u.nombre, u.username, es.fecha_inscripcion
                      FROM users u
                      JOIN estudiante_seccion es ON u.id = es.id_usuario
                      WHERE es.id_seccion = ? AND es.estatus = 'activo'
                      ORDER BY u.nombre");
        $stmt->bind_param("i", $seccion_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        $estudiantes_inscritos = count($estudiantes);
        $faltan_para_activar = max(0, MINIMO_ESTUDIANTES - $estudiantes_inscritos);
        ?>
        
        <h1 class="h3 mb-4 text-gray-800">Detalles de Sección</h1>
        
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Información General</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Código:</strong> <?= htmlspecialchars($seccion['codigo_seccion']) ?></p>
                        <p><strong>Carrera:</strong> <?= htmlspecialchars($seccion['nombre_carrera']) ?></p>
                        <p><strong>Trayecto:</strong> <?= $seccion['numero_trayecto'] ?></p>
                        <p><strong>Período:</strong> <?= htmlspecialchars($seccion['nombre_periodo']) ?></p>
                        <p><strong>Capacidad:</strong> <?= $estudiantes_inscritos ?>/<?= $seccion['capacidad_maxima'] ?></p>
                        <p><strong>Estado:</strong> 
                            <span class="badge badge-<?= $seccion['estatus'] == 'activa' ? 'success' : 'warning' ?>">
                                <?= ucfirst($seccion['estatus']) ?>
                                <?php if ($seccion['estatus'] == 'inactiva'): ?>
                                    <br><small>(Faltan <?= $faltan_para_activar ?> estudiantes para activar)</small>
                                <?php endif; ?>
                            </span>
                        </p>
                        
                        <div class="mt-4">
                            <form method="post" style="display:block; margin-bottom:10px;">
                                <input type="hidden" name="action" value="assign">
                                <input type="hidden" name="id" value="<?= $seccion_id ?>">
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-users"></i> Asignar Estudiantes
                                </button>
                            </form>
                            <form method="post" style="display:block; margin-bottom:10px;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $seccion_id ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-edit"></i> Editar Sección
                                </button>
                            </form>
                            <form method="post" style="display:block;">
                                <input type="hidden" name="action" value="list">
                                <button type="submit" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> Volver al listado
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Estudiantes Asignados</h6>
                        <span class="badge badge-primary"><?= $estudiantes_inscritos ?> estudiantes</span>
                        <?php if ($seccion['estatus'] == 'inactiva'): ?>
                            <span class="badge badge-danger">
                                Se requiere <?= MINIMO_ESTUDIANTES ?> para activar (faltan <?= $faltan_para_activar ?>)
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Fecha Inscripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $est): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($est['nombre']) ?></td>
                                            <td><?= htmlspecialchars($est['username']) ?></td>
                                            <td><?= $est['fecha_inscripcion'] ?></td>
                                            <td>
                                                <form method="post" style="display:inline">
                                                    <input type="hidden" name="action" value="retirar_estudiante">
                                                    <input type="hidden" name="id_usuario" value="<?= $est['id'] ?>">
                                                    <input type="hidden" name="id_seccion" value="<?= $seccion_id ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Retirar">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
</div>
</body>
</html>