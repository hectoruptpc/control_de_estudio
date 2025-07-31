<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Secciones";
include('../funciones/functions.php');

// Obtener parámetros de acción
$action = $_POST['action'] ?? 'list';
$seccion_id = $_POST['id'] ?? 0;
$periodo_id = $_POST['periodo'] ?? 0;

// Procesar formularios antes de cualquier output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear_seccion'])) {
        // Procesar creación de sección
        $datos = [
            'codigo_seccion' => trim($_POST['codigo_seccion']),
            'id_carrera' => (int)$_POST['id_carrera'],
            'id_trayecto' => (int)$_POST['id_trayecto'],
            'id_periodo' => (int)$_POST['id_periodo'],
            'capacidad_maxima' => (int)$_POST['capacidad_maxima']
        ];
        
        $resultado = crearSeccion($db, $datos);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        header("Location: gestion_seccion.php");
        exit();
        
    } elseif (isset($_POST['editar_seccion'])) {
        // Procesar edición de sección
        $datos = [
            'id_seccion' => (int)$_POST['id'],
            'codigo_seccion' => trim($_POST['codigo_seccion']),
            'id_carrera' => (int)$_POST['id_carrera'],
            'id_trayecto' => (int)$_POST['id_trayecto'],
            'id_periodo' => (int)$_POST['id_periodo'],
            'capacidad_maxima' => (int)$_POST['capacidad_maxima']
        ];
        
        $resultado = editarSeccion($db, $datos);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        header("Location: gestion_seccion.php");
        exit();
        
    } elseif (isset($_POST['asignar_estudiantes'])) {
        // Procesar asignación de estudiantes
        $seccion_id = (int)$_POST['seccion_id'];
        $estudiantes = $_POST['estudiantes'] ?? [];
        
        $resultado = asignarEstudiantes($db, $seccion_id, $estudiantes);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
            if (isset($resultado['warning'])) {
                $_SESSION['warning'] = $resultado['warning'];
            }
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        header("Location: gestion_seccion.php?action=view&id=".$seccion_id);
        exit();
    } elseif ($action === 'retirar_estudiante') {
        // Procesar retiro de estudiante
        $usuario_id = (int)$_POST['id_usuario'];
        $seccion_id = (int)$_POST['id_seccion'];
        
        $resultado = retirarEstudiante($db, $seccion_id, $usuario_id);
        
        if ($resultado['success']) {
            $_SESSION['success'] = $resultado['message'];
        } else {
            $_SESSION['error'] = $resultado['message'];
        }
        header("Location: gestion_seccion.php?action=view&id=".$seccion_id);
        exit();
    }
}

include("includes/head.php");
?>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmarRetiroModal" tabindex="-1" role="dialog" aria-labelledby="confirmarRetiroModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarRetiroModalLabel">Confirmar Retiro de Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalConfirmacionBody">
                ¿Está seguro que desea retirar a este estudiante de la sección?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="formRetiroEstudiante" method="post" style="display:inline">
                    <input type="hidden" name="action" value="retirar_estudiante">
                    <input type="hidden" name="id_usuario" id="modalIdUsuario" value="">
                    <input type="hidden" name="id_seccion" id="modalIdSeccion" value="">
                    <button type="submit" class="btn btn-danger">Confirmar Retiro</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php 
    if (isset($_SESSION['error'])) {
        mostrarError($_SESSION['error']);
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        mostrarExito($_SESSION['success']);
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['warning'])) {
        mostrarAdvertencia($_SESSION['warning']);
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
                            $secciones = obtenerListadoSecciones($db);
                            
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
        $datos_selects = obtenerDatosSelects($db);
        $carreras = $datos_selects['carreras'];
        $trayectos = $datos_selects['trayectos'];
        $periodos = $datos_selects['periodos'];
        
        // Datos de la sección si es edición
        $seccion = [];
        if ($action === 'edit' && $seccion_id > 0) {
            $seccion = obtenerDatosSeccion($db, $seccion_id);
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
        $seccion = obtenerDetalleSeccion($db, $seccion_id);
        $estudiantes = obtenerEstudiantesDisponibles($db, $seccion_id, $seccion['id_carrera']);
        $asignados = obtenerEstudiantesAsignados($db, $seccion_id);
        
        // Verificar si la sección está llena
        $seccion_llena = ($seccion['inscritos'] >= $seccion['capacidad_maxima']);
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
                    <?php if ($seccion_llena): ?>
                        <span class="badge badge-warning ml-2">
                            Sección llena
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <?php if ($seccion_llena): ?>
                    <div class="alert alert-warning">
                        <strong>Atención:</strong> La sección ha alcanzado su capacidad máxima. Puede desasignar estudiantes marcándolos para retirarlos.
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="formAsignarEstudiantes">
                    <input type="hidden" name="action" value="asignar_estudiantes">
                    <input type="hidden" name="seccion_id" value="<?= $seccion_id ?>">
                    <input type="hidden" id="capacidadMaxima" value="<?= $seccion['capacidad_maxima'] ?>">
                    <input type="hidden" id="inscritosActuales" value="<?= $seccion['inscritos'] ?>">
                    
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
                                                <?= in_array($est['id'], $asignados) ? 'checked' : '' ?>
                                                class="checkbox-estudiante">
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
                    
                    <div class="alert alert-info mt-3">
                        Estudiantes seleccionados: <span id="contador-seleccionados">0</span>/<?= $seccion['capacidad_maxima'] ?>
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
            // Obtener valores iniciales
            const capacidadMaxima = parseInt($('#capacidadMaxima').val());
            const inscritosActuales = parseInt($('#inscritosActuales').val());
            
            // Contar checkboxes marcados inicialmente
            let seleccionados = $('#formAsignarEstudiantes input[name="estudiantes[]"]:checked').length;
            
            // Actualizar contador inicial
            $('#contador-seleccionados').text(seleccionados);
            
            // Inicializar DataTable
            var table = $('#tablaEstudiantes').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                }
            });
            
            // Función para actualizar el contador y deshabilitar checkboxes si es necesario
            function actualizarContador() {
                // Usar la API de DataTables para obtener todos los registros (incluyendo paginación)
                let totalSeleccionados = 0;
                table.rows().every(function() {
                    const row = this.node();
                    const checkbox = $(row).find('.checkbox-estudiante');
                    if (checkbox.is(':checked')) {
                        totalSeleccionados++;
                    }
                });
                
                seleccionados = totalSeleccionados;
                $('#contador-seleccionados').text(seleccionados);
                
                // Actualizar estado de "Seleccionar todos"
                const totalEstudiantes = $('#formAsignarEstudiantes input[name="estudiantes[]"]').length;
                $('#seleccionarTodos').prop('checked', seleccionados === totalEstudiantes);
                
                // Deshabilitar checkboxes no seleccionados si se alcanzó la capacidad máxima
                if (seleccionados >= capacidadMaxima) {
                    $('.checkbox-estudiante:not(:checked)').prop('disabled', true);
                } else {
                    $('.checkbox-estudiante').prop('disabled', false);
                }
            }
            
            // Manejar cambios en los checkboxes
            $(document).on('change', '.checkbox-estudiante', function() {
                // Verificar si estamos intentando marcar un checkbox cuando ya se alcanzó la capacidad máxima
                if ($(this).is(':checked') && seleccionados >= capacidadMaxima) {
                    $(this).prop('checked', false);
                    alert('No puedes seleccionar más estudiantes que la capacidad máxima de la sección.');
                    return;
                }
                
                actualizarContador();
            });
            
            // Seleccionar/deseleccionar todos
            $('#seleccionarTodos').change(function() {
                const seleccionarTodos = $(this).is(':checked');
                
                if (seleccionarTodos) {
                    // Verificar si podemos seleccionar todos
                    const totalEstudiantes = $('#formAsignarEstudiantes input[name="estudiantes[]"]').length;
                    if (totalEstudiantes > capacidadMaxima) {
                        $(this).prop('checked', false);
                        alert('No puedes seleccionar todos los estudiantes porque superaría la capacidad máxima.');
                        return;
                    }
                }
                
                // Marcar/desmarcar todos los checkboxes usando la API de DataTables
                table.rows().every(function() {
                    const row = this.node();
                    const checkbox = $(row).find('.checkbox-estudiante');
                    checkbox.prop('checked', seleccionarTodos);
                });
                
                actualizarContador();
            });
            
            // Manejar el envío del formulario
            $('#formAsignarEstudiantes').on('submit', function() {
                // Forzar a DataTables a mostrar todos los registros temporalmente
                table.page.len(-1).draw();
                // Esperar un momento para que se complete el redibujado
                setTimeout(() => {
                    // Continuar con el envío del formulario
                    return true;
                }, 500);
            });
            
            // Actualizar contador y estado inicial
            actualizarContador();
        });
        </script>

    <?php elseif ($action === 'view' && $seccion_id > 0): ?>
        <!-- VISTA DETALLADA DE SECCIÓN -->
        <?php
        $seccion = obtenerDetalleSeccion($db, $seccion_id);
        $estudiantes = obtenerEstudiantesDeSeccion($db, $seccion_id);
        
        $estudiantes_inscritos = count($estudiantes);
        $faltan_para_activar = max(0, MINIMO_ESTUDIANTES - $estudiantes_inscritos);
        $seccion_llena = ($estudiantes_inscritos >= $seccion['capacidad_maxima']);
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
                                <?php if ($seccion_llena): ?>
                                    <br><small>(Sección llena)</small>
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
                        <?php if ($seccion_llena): ?>
                            <span class="badge badge-warning">
                                Sección llena
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
                                                <button type="button" class="btn btn-sm btn-danger btn-retirar" 
                                                        data-id="<?= $est['id'] ?>" 
                                                        data-seccion="<?= $seccion_id ?>"
                                                        data-nombre="<?= htmlspecialchars($est['nombre']) ?>">
                                                    <i class="fas fa-user-minus"></i> Retirar
                                                </button>
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

        <script>
        $(document).ready(function() {
            // Manejar clic en botón de retirar
            $('.btn-retirar').click(function() {
                var idUsuario = $(this).data('id');
                var idSeccion = $(this).data('seccion');
                var nombreEstudiante = $(this).data('nombre');
                
                // Actualizar el modal con los datos del estudiante
                $('#modalConfirmacionBody').html(
                    '¿Está seguro que desea retirar al estudiante <strong>' + nombreEstudiante + '</strong> de la sección?'
                );
                
                // Setear los valores en el formulario del modal
                $('#modalIdUsuario').val(idUsuario);
                $('#modalIdSeccion').val(idSeccion);
                
                // Mostrar el modal
                $('#confirmarRetiroModal').modal('show');
            });
        });
        </script>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
</div>
</body>
</html>