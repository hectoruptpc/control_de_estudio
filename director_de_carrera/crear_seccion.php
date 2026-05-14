<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isUser()) {
    $_SESSION['msg'] = "Debes iniciar sesión como director de carrera para acceder";
    header('location: ../login.php');
    exit();
}

$success_message = '';
$error_message = '';

$carreraId = $_SESSION['user']['carrera_di'] ?? 0;
if (!$carreraId) {
    $error_message = 'No se pudo determinar la carrera asignada.';
}

// Obtener datos necesarios
$carreras = obtenerTodasLasCarreras();
$carreraNombre = '';
foreach ($carreras as $c) {
    if ($c['id'] == $carreraId) {
        $carreraNombre = $c['nombre'];
        break;
    }
}

$turnos = ['Diurno', 'Nocturno'];

// Obtener trayectos y periodos
$datosSelects = obtenerDatosSelects($db);
$trayectos = $datosSelects['trayectos'] ?? [];
$periodos = $datosSelects['periodos'] ?? [];

// Variables para límites
$limitesTurnos = [];
$periodoSeleccionado = 0;

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_seccion'])) {
    // Recibir y limpiar datos
    $turno = trim($_POST['turno'] ?? '');
    $codigoSeccion = trim($_POST['codigo_seccion'] ?? '');
    $numeroSeccion = (int)$codigoSeccion;
    $capacidad = (int)($_POST['capacidad'] ?? 30);
    $horario = trim($_POST['horario'] ?? '');
    $idTrayecto = (int)($_POST['id_trayecto'] ?? 0);
    $idPeriodo = (int)($_POST['id_periodo'] ?? 0);
    $inicia = trim($_POST['inicia'] ?? '');
    
    // Verificar límite de secciones ANTES de crear
    $limite = obtenerLimiteSeccionesDirector($carreraId, $turno, $idPeriodo);
    
    if (!$limite['tiene_cupo']) {
        $error_message = "No puedes crear más secciones para el turno $turno en este periodo. " .
                         "Límite autorizado: {$limite['autorizadas']}, " .
                         "Secciones ya creadas: {$limite['creadas']}.";
    } else {
        // Validaciones
        $errores = [];
        
        if (empty($turno)) {
            $errores[] = 'Debe seleccionar un turno';
        }
        
        if (empty($codigoSeccion)) {
            $errores[] = 'El código de sección es obligatorio';
        }
        
        if ($capacidad < 1) {
            $errores[] = 'La capacidad debe ser mayor a 0';
        }
        
        if ($idTrayecto <= 0) {
            $errores[] = 'Debe seleccionar un trayecto válido';
        }
        
        if ($idPeriodo <= 0) {
            $errores[] = 'Debe seleccionar un periodo válido';
        }
        
        if (empty($inicia)) {
            $errores[] = 'La fecha y hora de inicio es obligatoria';
        }
        
        if (empty($horario)) {
            $errores[] = 'Debe definir al menos un horario para la sección';
        }
        
        // Formatear fecha para MySQL
        $fechaFormateada = date('Y-m-d H:i:s', strtotime($inicia));
        
        // Verificar si ya existe la sección
        if (empty($errores)) {
            $check_query = "SELECT id_seccion FROM secciones WHERE id_carrera = ? AND turno = ? AND codigo_seccion = ? AND id_periodo = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bind_param('issi', $carreraId, $turno, $codigoSeccion, $idPeriodo);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $errores[] = "Ya existe una sección con el código '$codigoSeccion' en el turno '$turno' para este periodo";
            }
            $check_stmt->close();
        }
        
        // Si no hay errores, proceder a guardar
        if (empty($errores)) {
            $resultado = crearSeccionDirector(
                $carreraId, 
                $turno, 
                $numeroSeccion, 
                $capacidad, 
                $horario, 
                $_SESSION['user']['id'], 
                $codigoSeccion, 
                $idTrayecto, 
                $idPeriodo, 
                $fechaFormateada
            );
            
            if ($resultado) {
                $success_message = 'Sección creada exitosamente y enviada para aprobación.';
                $_POST = array();
            } else {
                $error_message = 'Error al crear la sección. Por favor, intenta nuevamente.';
            }
        } else {
            $error_message = implode('<br>', $errores);
        }
    }
}

// Obtener límites para cada turno (con el periodo seleccionado o activo)
$periodoActivo = obtenerPeriodoActivo();
$periodoPorDefecto = $_POST['id_periodo'] ?? ($periodoActivo['id_periodo'] ?? 0);

foreach ($turnos as $t) {
    $limitesTurnos[$t] = obtenerLimiteSeccionesDirector($carreraId, $t, $periodoPorDefecto);
}

$titulopag = 'Crear Sección';
include('includes/head.php');
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Crear Nueva Sección</h2>
                <p class="text-muted mb-0">Crea una sección para <?php echo htmlspecialchars($carreraNombre); ?></p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Mostrar resumen de límites por turno -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong><i class="fas fa-chart-line"></i> Límites de Secciones por Turno</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Turno</th>
                                    <th>Autorizadas</th>
                                    <th>Creadas</th>
                                    <th>Pendientes</th>
                                    <th>Aprobadas</th>
                                    <th>Disponibles</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($turnos as $t): ?>
                                    <?php $lim = $limitesTurnos[$t]; ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($t); ?></strong></td>
                                        <td class="text-center"><?php echo $lim['autorizadas']; ?></td>
                                        <td class="text-center"><?php echo $lim['creadas']; ?></td>
                                        <td class="text-center">
                                            <?php if ($lim['pendientes'] > 0): ?>
                                                <span class="badge badge-warning"><?php echo $lim['pendientes']; ?></span>
                                            <?php else: ?>
                                                <?php echo $lim['pendientes']; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo $lim['aprobadas']; ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $lim['disponibles'] > 0 ? 'badge-success' : 'badge-danger'; ?>">
                                                <?php echo $lim['disponibles']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($lim['tiene_cupo']): ?>
                                                <span class="badge badge-success">Disponible</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Límite alcanzado</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Las secciones <strong>pendientes</strong> están en espera de aprobación por Secretaría.
                        Las secciones <strong>aprobadas</strong> ya están activas.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong><i class="fas fa-info-circle"></i> Detalles de la Sección</strong>
                </div>
                <div class="card-body">
                    <form method="post" action="" id="formCrearSeccion">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="turno" class="form-label">Turno *</label>
                                <select class="form-control" id="turno" name="turno" required>
                                    <option value="">Seleccionar turno</option>
                                    <?php foreach ($turnos as $t): ?>
                                        <?php $lim = $limitesTurnos[$t]; ?>
                                        <option value="<?php echo htmlspecialchars($t); ?>" 
                                            <?php echo (isset($_POST['turno']) && $_POST['turno'] === $t) ? 'selected' : ''; ?>
                                            <?php echo !$lim['tiene_cupo'] ? 'disabled' : ''; ?>>
                                            <?php echo htmlspecialchars($t); ?>
                                            <?php echo !$lim['tiene_cupo'] ? ' (Límite alcanzado)' : ' (' . $lim['disponibles'] . ' disponibles)'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (!empty($_POST['turno']) && !$limitesTurnos[$_POST['turno']]['tiene_cupo']): ?>
                                    <div class="text-danger mt-1">
                                        <small>No puedes crear más secciones para este turno. Límite alcanzado.</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="codigo_seccion" class="form-label">Código de Sección *</label>
                                <input type="text" class="form-control" id="codigo_seccion" name="codigo_seccion" 
                                       value="<?php echo htmlspecialchars($_POST['codigo_seccion'] ?? ''); ?>" 
                                       placeholder="Ej: 001" required>
                                <div class="form-text">Código único para esta sección</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="id_trayecto" class="form-label">Trayecto *</label>
                                <select class="form-control" id="id_trayecto" name="id_trayecto" required>
                                    <option value="">Seleccionar trayecto</option>
                                    <?php foreach ($trayectos as $trayecto): ?>
                                        <option value="<?php echo $trayecto['id_trayecto']; ?>" 
                                            <?php echo (isset($_POST['id_trayecto']) && (int)$_POST['id_trayecto'] === (int)$trayecto['id_trayecto']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($trayecto['numero_trayecto']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="id_periodo" class="form-label">Periodo Académico *</label>
                                <select class="form-control" id="id_periodo" name="id_periodo" required>
                                    <option value="">Seleccionar periodo</option>
                                    <?php foreach ($periodos as $periodo): ?>
                                        <option value="<?php echo $periodo['id_periodo']; ?>"
                                            <?php echo (isset($_POST['id_periodo']) && (int)$_POST['id_periodo'] === (int)$periodo['id_periodo']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($periodo['nombre_periodo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="capacidad" class="form-label">Capacidad *</label>
                                <input type="number" class="form-control" id="capacidad" name="capacidad" 
                                       min="1" max="50" value="<?php echo htmlspecialchars($_POST['capacidad'] ?? '30'); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="inicia" class="form-label">Fecha y Hora de Inicio *</label>
                                <input type="datetime-local" class="form-control" id="inicia" name="inicia" 
                                       value="<?php echo htmlspecialchars($_POST['inicia'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label">Horario *</label>
                                <div id="horarios-container">
                                    <div class="horario-row mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <select class="form-control dia" name="horario_dia[]" required>
                                                    <option value="Lunes">Lunes</option>
                                                    <option value="Martes">Martes</option>
                                                    <option value="Miércoles">Miércoles</option>
                                                    <option value="Jueves">Jueves</option>
                                                    <option value="Viernes">Viernes</option>
                                                    <option value="Sábado">Sábado</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="time" class="form-control hora_desde" name="horario_desde[]" value="07:00" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="time" class="form-control hora_hasta" name="horario_hasta[]" value="09:00" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control aula" name="aula[]" placeholder="Aula">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm eliminar-horario" style="display: none;">×</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="agregarHorario">
                                    <i class="fas fa-plus"></i> Agregar otro horario
                                </button>
                                <input type="hidden" name="horario" id="horario_json">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" name="crear_seccion" class="btn btn-primary btn-lg" id="btnCrearSeccion">
                                <i class="fas fa-save"></i> Crear Sección
                            </button>
                            <button type="reset" class="btn btn-secondary btn-lg">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong><i class="fas fa-info-circle"></i> Información</strong>
                </div>
                <div class="card-body">
                    <p><strong>Carrera:</strong> <?php echo htmlspecialchars($carreraNombre); ?></p>
                    <hr>
                    <h6>Instrucciones:</h6>
                    <ul>
                        <li>Las secciones creadas serán enviadas para aprobación</li>
                        <li>El código de sección debe ser único por turno y periodo</li>
                        <li>Puede agregar múltiples horarios por sección</li>
                        <li>La capacidad máxima recomendada es de 30-40 estudiantes</li>
                    </ul>
                    <hr>
                    <h6>Límites:</h6>
                    <ul>
                        <?php foreach ($turnos as $t): ?>
                            <?php $lim = $limitesTurnos[$t]; ?>
                            <li>
                                <strong><?php echo $t; ?>:</strong> 
                                <?php echo $lim['creadas']; ?>/<?php echo $lim['autorizadas']; ?> secciones
                                <?php if ($lim['pendientes'] > 0): ?>
                                    <span class="text-warning">(<?php echo $lim['pendientes']; ?> pendientes)</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('horarios-container');
    const agregarBtn = document.getElementById('agregarHorario');
    
    function actualizarHorarioJSON() {
        const rows = document.querySelectorAll('.horario-row');
        let horarios = [];
        
        rows.forEach(row => {
            const dia = row.querySelector('.dia').value;
            const desde = row.querySelector('.hora_desde').value;
            const hasta = row.querySelector('.hora_hasta').value;
            const aula = row.querySelector('.aula').value;
            
            let horarioStr = `${dia}: ${desde} - ${hasta}`;
            if (aula) {
                horarioStr += ` (Aula: ${aula})`;
            }
            horarios.push(horarioStr);
        });
        
        document.getElementById('horario_json').value = horarios.join(' | ');
    }
    
    agregarBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'horario-row mb-2';
        newRow.innerHTML = `
            <div class="row g-2">
                <div class="col-md-3">
                    <select class="form-control dia" required>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control hora_desde" value="07:00" required>
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control hora_hasta" value="09:00" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control aula" placeholder="Aula">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm eliminar-horario">×</button>
                </div>
            </div>
        `;
        
        container.appendChild(newRow);
        
        const eliminarBtns = document.querySelectorAll('.eliminar-horario');
        eliminarBtns.forEach(btn => btn.style.display = 'inline-block');
        
        agregarEventListeners(newRow);
    });
    
    function agregarEventListeners(row) {
        const inputs = row.querySelectorAll('select, input');
        inputs.forEach(input => {
            input.addEventListener('change', actualizarHorarioJSON);
            input.addEventListener('input', actualizarHorarioJSON);
        });
        
        const eliminarBtn = row.querySelector('.eliminar-horario');
        if (eliminarBtn) {
            eliminarBtn.addEventListener('click', function() {
                row.remove();
                actualizarHorarioJSON();
                
                const remainingRows = document.querySelectorAll('.horario-row');
                const allEliminarBtns = document.querySelectorAll('.eliminar-horario');
                if (remainingRows.length === 1) {
                    allEliminarBtns.forEach(btn => btn.style.display = 'none');
                }
            });
        }
    }
    
    const primeraFila = document.querySelector('.horario-row');
    agregarEventListeners(primeraFila);
    
    const primerEliminar = primeraFila.querySelector('.eliminar-horario');
    if (primerEliminar) {
        primerEliminar.style.display = 'none';
    }
    
    const form = document.getElementById('formCrearSeccion');
    form.addEventListener('submit', function() {
        actualizarHorarioJSON();
    });
    
    actualizarHorarioJSON();
    
    // Validar límite al cambiar turno
    const turnoSelect = document.getElementById('turno');
    const btnCrear = document.getElementById('btnCrearSeccion');
    
    turnoSelect.addEventListener('change', function() {
        const selectedOption = turnoSelect.options[turnoSelect.selectedIndex];
        const tieneCupo = !selectedOption.hasAttribute('disabled');
        
        if (!tieneCupo && selectedOption.value !== '') {
            btnCrear.disabled = true;
            alert('No puedes crear más secciones para este turno. Límite alcanzado.');
        } else {
            btnCrear.disabled = false;
        }
    });
});
</script>

<style>
.horario-row {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.eliminar-horario {
    margin-top: 8px;
}
</style>

<?php include('includes/footer.php'); ?>