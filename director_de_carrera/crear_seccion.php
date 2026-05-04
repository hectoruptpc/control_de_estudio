<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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

$carreras = obtenerTodasLasCarreras();
$carreraNombre = '';
foreach ($carreras as $c) {
    if ($c['id'] == $carreraId) {
        $carreraNombre = $c['nombre'];
        break;
    }
}

$cupos = obtenerCuposSecretaria();
$turnos = ['Diurno', 'Nocturno'];
$availableNumbersByTurno = [
    'Diurno' => obtenerNumerosSeccionDisponibles($carreraId, 'Diurno'),
    'Nocturno' => obtenerNumerosSeccionDisponibles($carreraId, 'Nocturno')
];
$availableNumbers = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['crear_seccion'])) {
    $turno = trim($_POST['turno'] ?? '');
    $numeroSeccion = (int)($_POST['numero_seccion'] ?? 0);
    $capacidad = (int)($_POST['capacidad'] ?? 30);
    $horario = trim($_POST['horario'] ?? '');

    $availableNumbers = $availableNumbersByTurno[$turno] ?? [];
    $config = $cupos[$carreraId][$turno] ?? null;
    $maxSecciones = $config['numero_secciones'] ?? 0;

    if (empty($turno) || !in_array($turno, $turnos)) {
        $error_message = 'Turno inválido.';
    } elseif (!$config || $maxSecciones <= 0) {
        $error_message = 'Secretaría no ha autorizado secciones para este turno.';
    } elseif ($numeroSeccion < 1) {
        $error_message = 'Número de sección inválido.';
    } elseif ($numeroSeccion > $maxSecciones) {
        $error_message = "Número de sección no puede ser mayor a $maxSecciones.";
    } elseif ($capacidad < 1) {
        $error_message = 'Capacidad debe ser mayor a 0.';
    } elseif (empty($horario)) {
        $error_message = 'El horario es obligatorio.';
    } else {
        // Verificar si ya existe sección con ese número
        global $db;
        $query = "SELECT id_seccion FROM secciones WHERE id_carrera = ? AND turno = ? AND numero_seccion = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('isi', $carreraId, $turno, $numeroSeccion);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error_message = 'Ya existe una sección con ese número para este turno.';
        } else {
            $stmt->close();
            if (crearSeccionPreinscripcion($carreraId, $turno, $numeroSeccion, $capacidad, $horario, $_SESSION['user']['id'])) {
                $success_message = 'Sección creada exitosamente y enviada para aprobación.';
            } else {
                $error_message = 'Error al crear la sección.';
            }
        }
    }
}

$titulopag = 'Crear Sección';
include('includes/head.php');
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Crear Nueva Sección</h2>
                <p class="text-muted mb-0">Crea una sección para <?php echo htmlspecialchars($carreraNombre); ?> con horario.</p>
            </div>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <strong>Detalles de la Sección</strong>
                </div>
                <div class="card-body">
                    <form method="post" action="crear_seccion.php">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="turno" class="form-label">Turno</label>
                                <select class="form-control" id="turno" name="turno" required>
                                    <option value="">Seleccionar turno</option>
                                    <?php foreach ($turnos as $t): ?>
                                        <option value="<?php echo htmlspecialchars($t); ?>" <?php echo isset($_POST['turno']) && $_POST['turno'] === $t ? 'selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero_seccion" class="form-label">Número de Sección</label>
                                <select class="form-control" id="numero_seccion" name="numero_seccion" required>
                                    <option value="">Seleccionar número</option>
                                </select>
                                <div id="section-help" class="form-text text-muted">Solo puedes elegir los números autorizados por Secretaría.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="capacidad" class="form-label">Capacidad</label>
                                <input type="number" class="form-control" id="capacidad" name="capacidad" min="1" value="<?php echo htmlspecialchars($_POST['capacidad'] ?? '30'); ?>" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Horario</label>
                                <div class="border rounded p-3 mb-3" id="schedule-builder">
                                    <div class="row g-2 align-items-end schedule-row-template d-none" id="schedule-row-template">
                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Día</label>
                                            <select class="form-control day-select">
                                                <option value="Lunes">Lunes</option>
                                                <option value="Martes">Martes</option>
                                                <option value="Miércoles">Miércoles</option>
                                                <option value="Jueves">Jueves</option>
                                                <option value="Viernes">Viernes</option>
                                                <option value="Sábado">Sábado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Desde</label>
                                            <input type="time" class="form-control time-from" value="07:00">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Hasta</label>
                                            <input type="time" class="form-control time-to" value="09:00">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-1">Aula</label>
                                            <input type="text" class="form-control room" placeholder="Aula">
                                        </div>
                                        <div class="col-md-1 text-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-schedule-row" title="Eliminar">×</button>
                                        </div>
                                    </div>
                                    <div id="schedule-rows"></div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="addScheduleRow">
                                        <i class="fas fa-plus"></i> Agregar horario
                                    </button>
                                </div>
                                <div id="schedule-summary" class="text-muted mb-3">Agrega al menos un horario para esta sección.</div>
                                <input type="hidden" id="horario" name="horario" value="<?php echo htmlspecialchars($_POST['horario'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" name="crear_seccion" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Sección
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <strong>Información</strong>
                </div>
                <div class="card-body">
                    <p><strong>Carrera:</strong> <?php echo htmlspecialchars($carreraNombre); ?></p>
                    <p>Las secciones creadas serán enviadas para aprobación por un administrador.</p>
                    <p>El número de sección debe estar dentro del límite configurado por Secretaría.</p>
                    <p>El horario se construye con entradas claras por día, hora y aula para un manejo profesional.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
    const cupos = <?php echo json_encode($cupos); ?>;
    const availableNumbersByTurno = <?php echo json_encode($availableNumbersByTurno); ?>;
    const previousSectionNumber = <?php echo json_encode($_POST['numero_seccion'] ?? ''); ?>;
    const carreraId = <?php echo (int)$carreraId; ?>;
    const turnoSelect = document.getElementById('turno');
    const numeroSeccionSelect = document.getElementById('numero_seccion');
    const sectionHelp = document.getElementById('section-help');
    const submitButton = document.querySelector('button[name="crear_seccion"]');
    const scheduleRowsContainer = document.getElementById('schedule-rows');
    const scheduleTemplate = document.getElementById('schedule-row-template');
    const scheduleSummary = document.getElementById('schedule-summary');
    const horarioInput = document.getElementById('horario');
    const addScheduleButton = document.getElementById('addScheduleRow');

    function buildSectionOptions(turno) {
        numeroSeccionSelect.innerHTML = '<option value="">Seleccionar número</option>';
        const config = cupos[carreraId] && cupos[carreraId][turno] ? cupos[carreraId][turno] : null;
        const max = config ? Math.max(0, parseInt(config.numero_secciones, 10)) : 0;

        if (!config || max <= 0) {
            const noOption = document.createElement('option');
            noOption.value = '';
            noOption.textContent = 'No autorizado por Secretaría';
            numeroSeccionSelect.appendChild(noOption);
            numeroSeccionSelect.disabled = true;
            if (sectionHelp) {
                sectionHelp.textContent = 'Secretaría no ha autorizado secciones para este turno.';
            }
            if (submitButton) {
                submitButton.disabled = true;
            }
            return;
        }

        numeroSeccionSelect.disabled = false;
        let anyAvailable = false;
        const availableNumbers = availableNumbersByTurno[turno] || [];
        for (let i = 1; i <= max; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            const used = availableNumbers.find(item => item.numero === i && !item.disponible) || null;
            if (used) {
                option.textContent = `${i} — ocupado`;
                option.disabled = true;
            } else {
                anyAvailable = true;
            }
            numeroSeccionSelect.appendChild(option);
        }

        if (sectionHelp) {
            sectionHelp.textContent = anyAvailable ? 'Puedes seleccionar un número autorizado por Secretaría.' : 'Todos los números autorizados ya están en uso o pendientes.';
        }
        if (submitButton) {
            submitButton.disabled = !anyAvailable;
        }
    }

    function updateScheduleSummary() {
        const rows = Array.from(scheduleRowsContainer.querySelectorAll('.schedule-row'));
        const lines = rows.map(row => {
            const day = row.querySelector('.day-select').value;
            const from = row.querySelector('.time-from').value;
            const to = row.querySelector('.time-to').value;
            const room = row.querySelector('.room').value.trim();
            if (!day || !from || !to) {
                return null;
            }
            return `${day}: ${from} - ${to}${room ? ' | Aula: ' + room : ''}`;
        }).filter(Boolean);

        if (lines.length === 0) {
            scheduleSummary.textContent = 'Agrega al menos un horario para esta sección.';
            horarioInput.value = '';
            return;
        }

        scheduleSummary.innerHTML = lines.map(line => `<div>${line}</div>`).join('');
        horarioInput.value = lines.join('\n');
    }

    function removeScheduleRow(button) {
        const row = button.closest('.schedule-row');
        if (row) {
            row.remove();
            updateScheduleSummary();
        }
    }

    function createScheduleRow(day = 'Lunes', from = '07:00', to = '09:00', room = '') {
        const clone = scheduleTemplate.cloneNode(true);
        clone.id = '';
        clone.classList.remove('d-none');
        clone.classList.add('schedule-row');
        const daySelect = clone.querySelector('.day-select');
        const fromInput = clone.querySelector('.time-from');
        const toInput = clone.querySelector('.time-to');
        const roomInput = clone.querySelector('.room');
        const removeButton = clone.querySelector('.remove-schedule-row');

        daySelect.value = day;
        fromInput.value = from;
        toInput.value = to;
        roomInput.value = room;

        [daySelect, fromInput, toInput, roomInput].forEach(input => {
            input.addEventListener('change', updateScheduleSummary);
        });

        removeButton.addEventListener('click', function () {
            removeScheduleRow(this);
        });

        scheduleRowsContainer.appendChild(clone);
        updateScheduleSummary();
    }

    addScheduleButton.addEventListener('click', function () {
        createScheduleRow();
    });

    turnoSelect.addEventListener('change', function () {
        buildSectionOptions(this.value);
    });

    document.addEventListener('DOMContentLoaded', function () {
        buildSectionOptions(turnoSelect.value || 'Diurno');
        if (previousSectionNumber) {
            numeroSeccionSelect.value = previousSectionNumber;
        }
        if (scheduleRowsContainer.children.length === 0) {
            createScheduleRow();
        }
        if (horarioInput.value) {
            scheduleSummary.textContent = horarioInput.value.replace(/\n/g, ' | ');
        }
    });
</script>

</body>
</html>