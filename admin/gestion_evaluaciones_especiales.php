<?php
/**
 * ==============================================================================
 * GESTIÓN DE EVALUACIONES Y MODALIDADES ESPECIALES - UPTPC
 * Extraordinario (E), Intensivo (T), PER (P), Suficiencia (S)
 * ==============================================================================
 */

require_once __DIR__ . '/../funciones/functions.php';

cargarPermisosUsuario();
verificarPermiso('admin');

$titulopag = "Gestión de Evaluaciones Especiales (E, T, P, S)";
$mensaje_exito = '';
$mensaje_error = '';

// Procesar registro de nota especial
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registrar_nota_especial') {
    $estudiante_id = intval($_POST['estudiante_id'] ?? 0);
    $id_materia = intval($_POST['id_materia'] ?? 0);
    $id_periodo = intval($_POST['id_periodo'] ?? 0);
    $id_docente = intval($_POST['id_docente'] ?? 0);
    $nota = floatval($_POST['nota'] ?? 0);
    $tipo_examen = strtoupper(trim($_POST['tipo_examen'] ?? 'R'));
    $caso_especial = !empty($_POST['caso_especial_jefa']);

    if ($estudiante_id <= 0 || $id_materia <= 0 || $id_periodo <= 0) {
        $mensaje_error = "Por favor complete todos los campos obligatorios (Estudiante, Asignatura y Período).";
    } elseif ($nota < 0 || $nota > 20) {
        $mensaje_error = "La calificación debe estar comprendida entre 0 y 20 puntos.";
    } elseif (!in_array($tipo_examen, ['E', 'T', 'P', 'S', 'R'], true)) {
        $mensaje_error = "Modalidad de evaluación no válida.";
    } else {
        // Validación específica para Suficiencia: verificar límite de 2 por año
        if ($tipo_examen === 'S') {
            $val_suf = validarSuficiencia($estudiante_id, $id_materia, $caso_especial);
            if (!$val_suf['permitido']) {
                $mensaje_error = $val_suf['mensaje'];
            }
        }

        if (empty($mensaje_error)) {
            $admin_aprobador_id = $_SESSION['user']['id'] ?? 1;
            $res = registrarNotaModalidadEspecial($estudiante_id, $id_materia, $id_periodo, $id_docente, $nota, $tipo_examen, $admin_aprobador_id);
            if ($res['success']) {
                $mensaje_exito = $res['message'];
            } else {
                $mensaje_error = $res['message'];
            }
        }
    }
}

// Períodos académicos
$periodos = [];
$res_p = $db->query("SELECT id_periodo, nombre_periodo, activo FROM periodos_academicos ORDER BY id_periodo DESC");
if ($res_p) {
    while ($rp = $res_p->fetch_assoc()) {
        $periodos[] = $rp;
    }
}

// Docentes
$docentes = [];
$res_d = $db->query("SELECT id, nombre, idusuario FROM users WHERE user_type = 'docente' OR docente = 1 ORDER BY nombre ASC");
if ($res_d) {
    while ($rd = $res_d->fetch_assoc()) {
        $docentes[] = $rd;
    }
}

// Estudiantes activos para selección
$estudiantes = [];
$res_est = $db->query("SELECT e.id, e.idusuario, e.nombre, c.nombre_carrera 
                       FROM estudiantes e 
                       LEFT JOIN carreras c ON e.carrera = c.id_carrera 
                       ORDER BY e.nombre ASC LIMIT 100");
if ($res_est) {
    while ($re = $res_est->fetch_assoc()) {
        $estudiantes[] = $re;
    }
}

// Estudiante preseleccionado si viene por GET o POST
$estudiante_seleccionado_id = intval($_GET['estudiante_id'] ?? ($_POST['estudiante_id'] ?? 0));
$estudiante_sel = null;
$materias_estudiante_sel = [];
$aptos_per_sel = [];
$info_extraordinario_sel = null;

if ($estudiante_seleccionado_id > 0) {
    $estudiante_sel = obtenerEstudiantePorId($estudiante_seleccionado_id);
    if ($estudiante_sel) {
        $carrera_sel = obtenerCarreraEstudiante($estudiante_seleccionado_id);
        if ($carrera_sel) {
            $res_mc = obtenerMateriasCarrera($carrera_sel['id_carrera']);
            if ($res_mc) {
                while ($mc = $res_mc->fetch_assoc()) {
                    $materias_estudiante_sel[] = $mc;
                }
            }
        }
        $aptos_per_sel = obtenerMateriasAptasPER($estudiante_seleccionado_id);
        $info_extraordinario_sel = esAptoExtraordinario($estudiante_seleccionado_id);
    }
}

// Obtener registros recientes de evaluaciones especiales (E, T, P, S)
$evaluaciones_recientes = [];
$q_rec = "SELECT nd.*, e.nombre as nombre_estudiante, e.idusuario as cedula_estudiante,
                 m.nombre_materia, m.cod_materia, pa.nombre_periodo, ud.nombre as nombre_docente,
                 ua.nombre as nombre_admin
          FROM notas_definitivas nd
          JOIN estudiantes e ON nd.id_usuario = e.id
          JOIN materias m ON nd.id_materia = m.id_materia
          LEFT JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
          LEFT JOIN users ud ON nd.id_docente = ud.id
          LEFT JOIN users ua ON nd.id_admin_aprobador = ua.id
          WHERE nd.tipo_examen IN ('E', 'T', 'P', 'S')
          ORDER BY nd.id DESC LIMIT 50";
$res_rec = $db->query($q_rec);
if ($res_rec) {
    while ($rr = $res_rec->fetch_assoc()) {
        $evaluaciones_recientes[] = $rr;
    }
}

include(__DIR__ . "/includes/head.php");
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="text-dark font-weight-bold mb-0">
                        <i class="fas fa-award text-primary mr-2"></i>Gestión de Evaluaciones Especiales
                    </h2>
                    <p class="text-muted mb-0">Administración y control de modalidades: Extraordinario (E), Intensivo (T), PER (P) y Suficiencia (S)</p>
                </div>
                <div>
                    <a href="consulta_notas.php" class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="fas fa-search mr-1"></i> Consulta de Notas
                    </a>
                </div>
            </div>

            <?php if (!empty($mensaje_exito)): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-1"></i> <?= htmlspecialchars($mensaje_exito) ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <?php if (!empty($mensaje_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle mr-1"></i> <?= htmlspecialchars($mensaje_error) ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Nav Tabs -->
            <ul class="nav nav-tabs shadow-sm bg-white rounded-top" id="evalTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="registro-tab" data-toggle="tab" href="#registro" role="tab">
                        <i class="fas fa-edit text-primary mr-1"></i> Cargar Calificación Especial
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="candidatos-tab" data-toggle="tab" href="#candidatos" role="tab">
                        <i class="fas fa-users-cog text-warning mr-1"></i> Diagnóstico por Estudiante
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="reglas-tab" data-toggle="tab" href="#reglas" role="tab">
                        <i class="fas fa-book-reader text-info mr-1"></i> Reglamento y Condiciones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="historial-tab" data-toggle="tab" href="#historial" role="tab">
                        <i class="fas fa-history text-secondary mr-1"></i> Historial Reciente (E, T, P, S)
                    </a>
                </li>
            </ul>

            <div class="tab-content bg-white p-4 border border-top-0 rounded-bottom shadow-sm mb-4" id="evalTabsContent">
                
                <!-- TAB 1: FORMULARIO DE REGISTRO -->
                <div class="tab-pane fade show active" id="registro" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-7">
                            <form method="POST" action="gestion_evaluaciones_especiales.php" id="formRegistroEspecial">
                                <input type="hidden" name="accion" value="registrar_nota_especial">
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">1. Seleccionar Estudiante: <span class="text-danger">*</span></label>
                                    <select name="estudiante_id" id="selectEstudiante" class="form-control" required onchange="cargarEstudiante(this.value)">
                                        <option value="">-- Seleccione un estudiante --</option>
                                        <?php foreach ($estudiantes as $e): ?>
                                            <option value="<?= $e['id'] ?>" <?= ($estudiante_seleccionado_id == $e['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($e['idusuario']) ?> - <?= htmlspecialchars($e['nombre']) ?> (<?= htmlspecialchars($e['nombre_carrera'] ?? 'Sin Carrera') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">2. Modalidad de Evaluación / Tipo de Examen: <span class="text-danger">*</span></label>
                                    <select name="tipo_examen" id="tipoExamen" class="form-control" required onchange="actualizarAyudaModalidad(this.value)">
                                        <option value="P" selected>[P] - Plan Especial de Recuperación (PER)</option>
                                        <option value="E">[E] - Examen Extraordinario</option>
                                        <option value="T">[T] - Curso Intensivo</option>
                                        <option value="S">[S] - Examen de Suficiencia</option>
                                        <option value="R">[R] - Regular</option>
                                    </select>
                                    <small id="ayudaModalidad" class="form-text text-muted"></small>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">3. Asignatura a Evaluar: <span class="text-danger">*</span></label>
                                    <select name="id_materia" id="selectMateria" class="form-control" required>
                                        <option value="">-- Seleccione la asignatura --</option>
                                        <?php if (!empty($materias_estudiante_sel)): ?>
                                            <?php foreach ($materias_estudiante_sel as $mat): ?>
                                                <option value="<?= $mat['id_materia'] ?>">
                                                    [T-<?= $mat['trayecto'] ?>] <?= htmlspecialchars($mat['cod_materia']) ?> - <?= htmlspecialchars($mat['nombre_materia']) ?> (<?= $mat['creditos'] ?> UC)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if ($estudiante_seleccionado_id > 0 && empty($materias_estudiante_sel)): ?>
                                        <small class="text-danger">El estudiante seleccionado no posee asignaturas en su pensum.</small>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">4. Calificación Obtenida (0 a 20): <span class="text-danger">*</span></label>
                                        <input type="number" step="0.5" min="0" max="20" name="nota" id="inputNota" class="form-control" placeholder="Ej: 12.0" required>
                                        <small id="notaFeedback" class="form-text text-muted">Para PER la nota máxima admisible es 12.0.</small>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-bold">5. Período Académico: <span class="text-danger">*</span></label>
                                        <select name="id_periodo" class="form-control" required>
                                            <?php foreach ($periodos as $p): ?>
                                                <option value="<?= $p['id_periodo'] ?>" <?= (!empty($p['activo'])) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($p['nombre_periodo']) ?> <?= (!empty($p['activo'])) ? '(Activo)' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">6. Docente Evaluador / Jurado (Opcional):</label>
                                    <select name="id_docente" class="form-control">
                                        <option value="0">-- Asignar después / Sin docente específico --</option>
                                        <?php foreach ($docentes as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nombre']) ?> (<?= htmlspecialchars($d['idusuario']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Caso Especial Suficiencia -->
                                <div class="form-group p-3 border rounded bg-light" id="boxCasoEspecial" style="display: none;">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="caso_especial_jefa" name="caso_especial_jefa" value="1">
                                        <label class="custom-control-label font-weight-bold text-dark" for="caso_especial_jefa">
                                            <i class="fas fa-unlock text-warning mr-1"></i> Desbloqueo Especial (Autorizado por Jefa de Control de Estudios)
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Marque esta casilla si el estudiante posee certificación de conocimientos por cursos externos o caso especial que justifique cursar más de 2 suficiencias al año.
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Validar y Registrar Calificación
                                </button>
                            </form>
                        </div>

                        <!-- PANEL DERECHO DE CONDICIÓN DEL ESTUDIANTE -->
                        <div class="col-lg-5 mt-4 mt-lg-0">
                            <div class="card bg-light border-0 shadow-sm h-100">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0 font-weight-bold">
                                        <i class="fas fa-user-graduate mr-1"></i> Resumen del Estudiante Seleccionado
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if ($estudiante_sel): ?>
                                        <h5 class="text-primary font-weight-bold"><?= htmlspecialchars($estudiante_sel['nombre']) ?></h5>
                                        <p class="mb-1"><strong>Cédula:</strong> <?= htmlspecialchars($estudiante_sel['idusuario']) ?></p>
                                        <p class="mb-2"><strong>Carrera:</strong> <?= htmlspecialchars($carrera_sel['nombre_carrera'] ?? 'No asignada') ?></p>

                                        <hr>

                                        <!-- Diagnóstico PER -->
                                        <h6 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-sync-alt text-warning mr-1"></i> Aptitud para PER (P):
                                        </h6>
                                        <?php if (!empty($aptos_per_sel)): ?>
                                            <span class="badge badge-success mb-2">Tiene <?= count($aptos_per_sel) ?> materia(s) apta(s)</span>
                                            <ul class="small pl-3 mb-3">
                                                <?php foreach ($aptos_per_sel as $ap): ?>
                                                    <li>
                                                        <strong><?= htmlspecialchars($ap['nombre_materia']) ?></strong>: Reprobada con <?= number_format($ap['nota_reprobada'], 1) ?> pts en <?= htmlspecialchars($ap['periodo_reprobado']) ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="small text-muted mb-3">No posee materias reprobadas con notas entre 6 y 11 pts para PER.</p>
                                        <?php endif; ?>

                                        <!-- Diagnóstico Extraordinario -->
                                        <h6 class="font-weight-bold text-dark mb-1">
                                            <i class="fas fa-star text-primary mr-1"></i> Aptitud para Extraordinario (E):
                                        </h6>
                                        <?php if ($info_extraordinario_sel && $info_extraordinario_sel['apto']): ?>
                                            <div class="alert alert-success p-2 small mb-3">
                                                <i class="fas fa-check-circle mr-1"></i> <strong>APTO:</strong> Solo le falta la materia <strong><?= htmlspecialchars($info_extraordinario_sel['materia_faltante']['nombre_materia'] ?? '') ?></strong> y ya la cursó previamente.
                                            </div>
                                        <?php else: ?>
                                            <p class="small text-muted mb-3">
                                                <?= htmlspecialchars($info_extraordinario_sel['mensaje'] ?? 'No evaluado.') ?>
                                            </p>
                                        <?php endif; ?>

                                        <!-- Botones de Historiales -->
                                        <div class="mt-3 pt-3 border-top">
                                            <label class="small font-weight-bold d-block">Generar Reportes Oficiales:</label>
                                            <form method="POST" action="historial_desglosado.php" target="_blank" class="d-inline mr-1">
                                                <input type="hidden" name="estudiante_id" value="<?= $estudiante_sel['id'] ?>">
                                                <button type="submit" class="btn btn-dark btn-sm font-weight-bold mb-1">
                                                    <i class="fas fa-file-pdf mr-1"></i> Desglosado
                                                </button>
                                            </form>
                                            <form method="POST" action="historial_graduando.php" target="_blank" class="d-inline mr-1">
                                                <input type="hidden" name="estudiante_id" value="<?= $estudiante_sel['id'] ?>">
                                                <button type="submit" class="btn btn-success btn-sm font-weight-bold mb-1">
                                                    <i class="fas fa-user-graduate mr-1"></i> Graduando
                                                </button>
                                            </form>
                                            <form method="POST" action="historial_desglozado_tsu.php" target="_blank" class="d-inline">
                                                <input type="hidden" name="estudiante_id" value="<?= $estudiante_sel['id'] ?>">
                                                <button type="submit" class="btn btn-warning btn-sm font-weight-bold mb-1">
                                                    <i class="fas fa-file-pdf mr-1"></i> TSU
                                                </button>
                                            </form>
                                        </div>

                                    <?php else: ?>
                                        <div class="text-center py-5 text-muted">
                                            <i class="fas fa-user-clock fa-3x mb-3 text-secondary"></i>
                                            <p>Seleccione un estudiante a la izquierda para cargar sus materias y verificar sus condiciones en tiempo real.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: DIAGNÓSTICO RÁPIDO POR CÉDULA -->
                <div class="tab-pane fade" id="candidatos" role="tabpanel">
                    <h5 class="font-weight-bold mb-3">Buscar Estudiante por Cédula:</h5>
                    <form method="GET" action="gestion_evaluaciones_especiales.php" class="form-inline mb-4">
                        <div class="input-group">
                            <input type="text" name="buscar_cedula" class="form-control" placeholder="Ingrese número de cédula..." value="<?= htmlspecialchars($_GET['buscar_cedula'] ?? '') ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    <i class="fas fa-search mr-1"></i> Consultar Diagnóstico
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (!empty($_GET['buscar_cedula'])) {
                        $ced_busq = trim($_GET['buscar_cedula']);
                        $stmt_b = $db->prepare("SELECT id FROM estudiantes WHERE idusuario = ? LIMIT 1");
                        $stmt_b->bind_param("s", $ced_busq);
                        $stmt_b->execute();
                        $res_b = $stmt_b->get_result();
                        if ($res_b && $rb = $res_b->fetch_assoc()) {
                            $bid = $rb['id'];
                            $b_est = obtenerEstudiantePorId($bid);
                            $b_car = obtenerCarreraEstudiante($bid);
                            $b_per = obtenerMateriasAptasPER($bid);
                            $b_ext = esAptoExtraordinario($bid);
                            $b_ira = calcularIRAEstudiante($bid, $b_car['id_carrera'] ?? null);
                            ?>
                            <div class="card border-info shadow-sm mb-4">
                                <div class="card-header bg-info text-white font-weight-bold">
                                    Resultado para: <?= htmlspecialchars($b_est['nombre']) ?> (C.I: <?= htmlspecialchars($b_est['idusuario']) ?>)
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Carrera:</strong> <?= htmlspecialchars($b_car['nombre_carrera'] ?? '-') ?></p>
                                            <p><strong>Promedio General:</strong> <?= number_format($b_ira['promedio_simple'], 2) ?> pts</p>
                                            <p><strong>Índice Rendimiento (IRA):</strong> <span class="badge badge-primary px-2 py-1"><?= number_format($b_ira['ira'], 3) ?> pts</span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold text-warning"><i class="fas fa-sync-alt mr-1"></i> Materias Aptas para PER (P):</h6>
                                            <?php if (!empty($b_per)): ?>
                                                <ul class="small pl-3">
                                                    <?php foreach ($b_per as $bp): ?>
                                                        <li><?= htmlspecialchars($bp['nombre_materia']) ?> (Nota: <?= $bp['nota_reprobada'] ?>)</li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="small text-muted">Ninguna materia apta para PER.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="font-weight-bold text-success"><i class="fas fa-star mr-1"></i> Examen Extraordinario (E):</h6>
                                            <p class="small"><?= htmlspecialchars($b_ext['mensaje']) ?></p>
                                            <a href="gestion_evaluaciones_especiales.php?estudiante_id=<?= $bid ?>" class="btn btn-outline-primary btn-sm font-weight-bold">
                                                <i class="fas fa-edit mr-1"></i> Ir a Cargar Calificación
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            echo '<div class="alert alert-warning">No se encontró ningún estudiante con la cédula indicada.</div>';
                        }
                    }
                    ?>
                </div>

                <!-- TAB 3: REGLAMENTO Y CONDICIONES -->
                <div class="tab-pane fade" id="reglas" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary h-100 shadow-sm">
                                <div class="card-header bg-primary text-white font-weight-bold">
                                    <i class="fas fa-star mr-1"></i> Examen Extraordinario (E)
                                </div>
                                <div class="card-body small">
                                    <ul class="mb-0 pl-3">
                                        <li class="mb-2"><strong>Condición de Culminación:</strong> Al estudiante le debe faltar <strong>únicamente una (1) asignatura</strong> para culminar su plan de estudios (TSU o Ingeniería/Licenciatura).</li>
                                        <li class="mb-2"><strong>Pre-cursado Obligatorio:</strong> El estudiante ya debió haber visto (cursado previamente) dicha materia en un período ordinario anterior.</li>
                                        <li class="mb-2"><strong>Solicitud Previa:</strong> El estudiante solicita la aplicación y Control de Estudios evalúa el cumplimiento estricto de la regla.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-warning h-100 shadow-sm">
                                <div class="card-header bg-warning text-dark font-weight-bold">
                                    <i class="fas fa-sync-alt mr-1"></i> Plan Especial de Recuperación - PER (P)
                                </div>
                                <div class="card-body small">
                                    <ul class="mb-0 pl-3">
                                        <li class="mb-2"><strong>Exclusión de Trayecto Inicial:</strong> El Trayecto 0 nunca hace PER.</li>
                                        <li class="mb-2"><strong>Exclusión de Proyectos:</strong> Los Proyectos Sociotecnológicos / Sociointegradores están expresamente excluidos de PER.</li>
                                        <li class="mb-2"><strong>Rango de Calificación:</strong> Solo aplica para asignaturas reprobadas con nota final entre <strong>6 y 11 puntos</strong>.</li>
                                        <li class="mb-2"><strong>Tope de Materias y Calificación:</strong> Máximo tres (3) materias simultáneas, y la nota máxima admisible para aprobar es de <strong>12 puntos</strong>.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-info h-100 shadow-sm">
                                <div class="card-header bg-info text-white font-weight-bold">
                                    <i class="fas fa-check-double mr-1"></i> Examen de Suficiencia (S)
                                </div>
                                <div class="card-body small">
                                    <ul class="mb-0 pl-3">
                                        <li class="mb-2"><strong>Límite Anual:</strong> Se admiten máximo dos (2) exámenes de suficiencia por año escolar al inicio del trayecto.</li>
                                        <li class="mb-2"><strong>Desbloqueo Caso Especial:</strong> Si el estudiante demuestra conocimientos por cursos o certificaciones, la Jefa de Control de Estudios puede autorizar materias adicionales mediante desbloqueo en sistema.</li>
                                        <li class="mb-2"><strong>Auto-inscripción Obligatoria:</strong> Si el estudiante no aprueba el examen de suficiencia (&lt; 12 puntos), el sistema <strong>inscribe automáticamente</strong> la asignatura en el período regular correspondiente.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-success h-100 shadow-sm">
                                <div class="card-header bg-success text-white font-weight-bold">
                                    <i class="fas fa-sun mr-1"></i> Curso Intensivo (T)
                                </div>
                                <div class="card-body small">
                                    <ul class="mb-0 pl-3">
                                        <li class="mb-2"><strong>Pre-cursado Obligatorio:</strong> El estudiante debió haber visto la asignatura con anterioridad.</li>
                                        <li class="mb-2"><strong>Reemplazo Activo sin Borrado:</strong> La nota aprobatoria de intensivo reemplaza la nota anterior como calificación activa vigente, pero <strong>sin borrar el intento previo</strong>, el cual permanece registrado para el historial desglosado y el cómputo del IRA.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: HISTORIAL RECIENTE -->
                <div class="tab-pane fade" id="historial" role="tabpanel">
                    <h5 class="font-weight-bold mb-3">Últimas Calificaciones Especiales Registradas:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th># ID</th>
                                    <th>Cédula</th>
                                    <th class="text-left">Estudiante</th>
                                    <th class="text-left">Asignatura</th>
                                    <th>Tipo</th>
                                    <th>Calificación</th>
                                    <th>Período</th>
                                    <th>Docente</th>
                                    <th>Registrado Por</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($evaluaciones_recientes)): ?>
                                    <?php foreach ($evaluaciones_recientes as $er): 
                                        $t_badge = 'badge-secondary';
                                        if ($er['tipo_examen'] === 'E') $t_badge = 'badge-primary';
                                        elseif ($er['tipo_examen'] === 'P') $t_badge = 'badge-warning text-dark';
                                        elseif ($er['tipo_examen'] === 'T') $t_badge = 'badge-success';
                                        elseif ($er['tipo_examen'] === 'S') $t_badge = 'badge-info';
                                    ?>
                                        <tr>
                                            <td><?= $er['id'] ?></td>
                                            <td class="font-weight-bold"><?= htmlspecialchars($er['cedula_estudiante']) ?></td>
                                            <td class="text-left"><?= htmlspecialchars($er['nombre_estudiante']) ?></td>
                                            <td class="text-left"><?= htmlspecialchars($er['cod_materia']) ?> - <?= htmlspecialchars($er['nombre_materia']) ?></td>
                                            <td><span class="badge <?= $t_badge ?> font-weight-bold px-2 py-1"><?= $er['tipo_examen'] ?> (<?= strtoupper($er['tipo_evaluacion']) ?>)</span></td>
                                            <td class="font-weight-bold <?= $er['nota_final'] >= 12 ? 'text-success' : 'text-danger' ?>">
                                                <?= number_format($er['nota_final'], 1) ?>
                                            </td>
                                            <td><?= htmlspecialchars($er['nombre_periodo'] ?? '-') ?></td>
                                            <td class="text-left small"><?= htmlspecialchars($er['nombre_docente'] ?? 'No asignado') ?></td>
                                            <td class="small"><?= htmlspecialchars($er['nombre_admin'] ?? 'Sistema') ?></td>
                                            <td class="small"><?= !empty($er['fecha_registro']) ? date('d/m/Y H:i', strtotime($er['fecha_registro'])) : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-muted py-3">No hay registros recientes de evaluaciones bajo modalidad especial.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function cargarEstudiante(estId) {
    if (estId) {
        window.location.href = 'gestion_evaluaciones_especiales.php?estudiante_id=' + estId;
    }
}

function actualizarAyudaModalidad(tipo) {
    var ayuda = document.getElementById('ayudaModalidad');
    var boxCaso = document.getElementById('boxCasoEspecial');
    var notaInput = document.getElementById('inputNota');
    var notaFeedback = document.getElementById('notaFeedback');

    if (tipo === 'P') {
        ayuda.innerHTML = '<span class="text-warning font-weight-bold">PER:</span> Solo aplica para materias reprobadas con nota 6 a 11 (excluye Trayecto 0 y Proyectos). Máximo 3 materias. La nota solo llega hasta 12.0.';
        boxCaso.style.display = 'none';
        notaInput.max = 12;
        notaFeedback.innerText = 'Nota máxima permitida para PER: 12.0 pts.';
    } else if (tipo === 'E') {
        ayuda.innerHTML = '<span class="text-primary font-weight-bold">Extraordinario:</span> Solo aplica si al estudiante le falta exactamente 1 materia para culminar y ya la cursó previamente.';
        boxCaso.style.display = 'none';
        notaInput.max = 20;
        notaFeedback.innerText = 'Calificación ordinaria de 0 a 20 pts.';
    } else if (tipo === 'S') {
        ayuda.innerHTML = '<span class="text-info font-weight-bold">Suficiencia:</span> Máximo 2 por año. Si reprueba (&lt;12), el sistema lo inscribe automáticamente en el período regular.';
        boxCaso.style.display = 'block';
        notaInput.max = 20;
        notaFeedback.innerText = 'Calificación de 0 a 20 pts (Aprobatoria &ge; 12).';
    } else if (tipo === 'T') {
        ayuda.innerHTML = '<span class="text-success font-weight-bold">Intensivo:</span> El estudiante debió haber visto la materia antes. Reemplaza la nota activa sin borrar el intento previo.';
        boxCaso.style.display = 'none';
        notaInput.max = 20;
        notaFeedback.innerText = 'Calificación ordinaria de 0 a 20 pts.';
    } else {
        ayuda.innerHTML = '';
        boxCaso.style.display = 'none';
        notaInput.max = 20;
        notaFeedback.innerText = 'Calificación de 0 a 20 pts.';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var selTipo = document.getElementById('tipoExamen');
    if (selTipo) {
        actualizarAyudaModalidad(selTipo.value);
    }
});
</script>

<?php include(__DIR__ . "/includes/footer.php"); ?>
