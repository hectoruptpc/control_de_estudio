<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['docente_id']) || !isset($_POST['materia_id']) || !isset($_POST['periodo_id']) || !isset($_POST['seccion'])) {
    die('Parámetros incompletos');
}

$docente_id = (int)$_POST['docente_id'];
$materia_id = (int)$_POST['materia_id'];
$periodo_id = (int)$_POST['periodo_id'];
$seccion = $_POST['seccion'];

// Obtener información del grupo
function obtenerInfoGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT ud.nombre as nombre_docente, m.nombre_materia, m.cod_materia,
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.id_trayecto, t.numero_trayecto
              FROM notas_pendientes np
              INNER JOIN users ud ON np.id_docente = ud.id
              INNER JOIN materias m ON np.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON np.id_periodo = pa.id_periodo
              INNER JOIN secciones s ON np.id_periodo = s.id_periodo
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener estudiantes del grupo
function obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT np.*, u.nombre as nombre_estudiante, u.idusuario as cedula
              FROM notas_pendientes np
              INNER JOIN users u ON np.id_usuario = u.id
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Calcular promedio según el id_trayecto de la sección
function calcularPromedioPorTrayecto($nota, $id_trayecto) {
    $suma = 0;
    $count = 0;
    
    // Determinar qué trayectos promediar según el id_trayecto de la sección
    switch ($id_trayecto) {
        case 1: // Trayecto Inicial - Solo trayecto_0
            if ($nota['trayecto_0'] !== null) {
                $suma = $nota['trayecto_0'];
                $count = 1;
            }
            break;
            
        case 2: // Trayecto 1 - Solo trayecto_1
            if ($nota['trayecto_1'] !== null) {
                $suma = $nota['trayecto_1'];
                $count = 1;
            }
            break;
            
        case 3: // Trayecto 2 - Solo trayecto_2
            if ($nota['trayecto_2'] !== null) {
                $suma = $nota['trayecto_2'];
                $count = 1;
            }
            break;
            
        case 4: // Trayecto 3 - Solo trayecto_3
            if ($nota['trayecto_3'] !== null) {
                $suma = $nota['trayecto_3'];
                $count = 1;
            }
            break;
            
        case 5: // Trayecto 4 - Solo trayecto_4
            if ($nota['trayecto_4'] !== null) {
                $suma = $nota['trayecto_4'];
                $count = 1;
            }
            break;
            
        default:
            // Por defecto, calcular todos los trayectos (no debería pasar)
            for ($i = 0; $i <= 4; $i++) {
                if ($nota['trayecto_' . $i] !== null) {
                    $suma += $nota['trayecto_' . $i];
                    $count++;
                }
            }
    }
    
    return $count > 0 ? round($suma / $count, 1) : 0;
}

// Obtener estadísticas del grupo según el id_trayecto
function obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id, $id_trayecto) {
    global $db;
    
    $query = "SELECT np.trayecto_0, np.trayecto_1, np.trayecto_2, np.trayecto_3, np.trayecto_4
              FROM notas_pendientes np
              WHERE np.id_docente = ? 
              AND np.id_materia = ? 
              AND np.id_periodo = ?
              AND np.estado = 'pendiente'";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $total_estudiantes = 0;
    $suma_total = 0;
    $aprobados = 0;
    $reprobados = 0;
    
    while ($nota = $result->fetch_assoc()) {
        $total_estudiantes++;
        
        // Calcular promedio según el id_trayecto de la sección
        $promedio_estudiante = calcularPromedioPorTrayecto($nota, $id_trayecto);
        $suma_total += $promedio_estudiante;
        
        // Aprobados desde 12 puntos
        if ($promedio_estudiante >= 12) {
            $aprobados++;
        } else {
            $reprobados++;
        }
    }
    
    $promedio_general = $total_estudiantes > 0 ? round($suma_total / $total_estudiantes, 1) : 0;
    
    return [
        'total_estudiantes' => $total_estudiantes,
        'promedio_general' => $promedio_general,
        'aprobados' => $aprobados,
        'reprobados' => $reprobados,
        'id_trayecto' => $id_trayecto
    ];
}

$info_grupo = obtenerInfoGrupo($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id);
$estadisticas = obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id, $info_grupo['id_trayecto']);

if (!$info_grupo) {
    die('Información no encontrada');
}

// Determinar qué trayecto se está considerando
$trayecto_considerado = '';
switch ($info_grupo['id_trayecto']) {
    case 1: $trayecto_considerado = 'Trayecto 0'; break;
    case 2: $trayecto_considerado = 'Trayecto 1'; break;
    case 3: $trayecto_considerado = 'Trayecto 2'; break;
    case 4: $trayecto_considerado = 'Trayecto 3'; break;
    case 5: $trayecto_considerado = 'Trayecto 4'; break;
    default: $trayecto_considerado = 'Todos los trayectos';
}

switch ($seccion) {
    case 'lista-estudiantes':
        ?>
        <h4>Lista de Estudiantes</h4>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            Trayecto considerado: <strong><?= $trayecto_considerado ?></strong><br>
            Aprobación: ≥12pts
        </div>
        
        <form id="formGestionIndividual">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="selectAllEstudiantes">
                            </th>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <th>Nota del Trayecto</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                            <?php
                            $promedio = calcularPromedioPorTrayecto($estudiante, $info_grupo['id_trayecto']);
                            $estado = $promedio >= 12 ? 'Aprobado' : 'Reprobado';
                            $color_estado = $promedio >= 12 ? 'success' : 'danger';
                            
                            // Obtener la nota específica del trayecto
                            $nota_trayecto = '';
                            switch ($info_grupo['id_trayecto']) {
                                case 1: $nota_trayecto = $estudiante['trayecto_0']; break;
                                case 2: $nota_trayecto = $estudiante['trayecto_1']; break;
                                case 3: $nota_trayecto = $estudiante['trayecto_2']; break;
                                case 4: $nota_trayecto = $estudiante['trayecto_3']; break;
                                case 5: $nota_trayecto = $estudiante['trayecto_4']; break;
                            }
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="notas_ids[]" 
                                           value="<?= $estudiante['id'] ?>" 
                                           class="estudiante-checkbox">
                                </td>
                                <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                                <td>
                                    <?php if ($nota_trayecto !== null): ?>
                                        <span class="badge badge-info">
                                            T<?= $info_grupo['id_trayecto'] - 1 ?>: <?= $nota_trayecto ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Sin nota</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $color_estado ?>">
                                        <?= $estado ?>
                                    </span>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm accion-individual" 
                                            data-nota-id="<?= $estudiante['id'] ?>">
                                        <option value="">-- Seleccionar --</option>
                                        <option value="aprobar">Aprobar</option>
                                        <option value="rechazar">Rechazar</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <button type="button" class="btn btn-success" onclick="aplicarAccion('aprobar')">
                    <i class="fas fa-check-circle"></i> Aprobar Seleccionados
                </button>
                <button type="button" class="btn btn-danger" onclick="aplicarAccion('rechazar')">
                    <i class="fas fa-times-circle"></i> Rechazar Seleccionados
                </button>
            </div>
        </form>

        <script>
        function aplicarAccion(accion) {
            const selected = $('.estudiante-checkbox:checked');
            if (selected.length === 0) {
                alert('Seleccione al menos un estudiante');
                return;
            }
            
            const notasIds = selected.map(function() {
                return $(this).val();
            }).get();
            
            if (confirm(`¿${accion === 'aprobar' ? 'Aprobar' : 'Rechazar'} ${selected.length} nota(s)?`)) {
                $.ajax({
                    url: 'procesar_acciones.php',
                    type: 'POST',
                    data: {
                        accion: accion,
                        notas_ids: notasIds
                    },
                    success: function() {
                        location.reload();
                    }
                });
            }
        }
        
        $('#selectAllEstudiantes').change(function() {
            $('.estudiante-checkbox').prop('checked', this.checked);
        });
        
        $('.accion-individual').change(function() {
            const notaId = $(this).data('nota-id');
            const accion = $(this).val();
            
            if (accion) {
                if (confirm(`${accion === 'aprobar' ? 'Aprobar' : 'Rechazar'} esta nota?`)) {
                    $.ajax({
                        url: 'procesar_acciones.php',
                        type: 'POST',
                        data: {
                            accion: accion,
                            notas_ids: [notaId]
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                } else {
                    $(this).val('');
                }
            }
        });
        </script>
        <?php
        break;
        
    case 'resumen':
        ?>
        <h4>Resumen del Grupo</h4>
        <div class="alert alert-info">
            <strong>Trayecto considerado:</strong> <?= $trayecto_considerado ?><br>
            <strong>Aprobación:</strong> ≥12pts
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Información del Grupo</div>
                    <div class="card-body">
                        <p><strong>Docente:</strong> <?= htmlspecialchars($info_grupo['nombre_docente']) ?></p>
                        <p><strong>Materia:</strong> <?= htmlspecialchars($info_grupo['nombre_materia']) ?></p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($info_grupo['cod_materia']) ?></p>
                        <p><strong>Trayecto:</strong> <?= htmlspecialchars($info_grupo['nombre_trayecto']) ?> (ID: <?= $info_grupo['id_trayecto'] ?>)</p>
                        <p><strong>Periodo:</strong> <?= htmlspecialchars($info_grupo['nombre_periodo']) ?></p>
                        <p><strong>Sección:</strong> <?= htmlspecialchars($info_grupo['codigo_seccion']) ?></p>
                        <p><strong>Carrera:</strong> <?= htmlspecialchars($info_grupo['nombre_carrera']) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Estadísticas</div>
                    <div class="card-body">
                        <p><strong>Total Estudiantes:</strong> 
                            <span class="badge badge-primary"><?= $estadisticas['total_estudiantes'] ?></span>
                        </p>
                        <p><strong>Promedio General:</strong> 
                            <span class="badge badge-<?= $estadisticas['promedio_general'] >= 12 ? 'success' : 'warning' ?>">
                                <?= $estadisticas['promedio_general'] ?>
                            </span>
                        </p>
                        <p><strong>Aprobados (≥12pts):</strong> 
                            <span class="badge badge-success"><?= $estadisticas['aprobados'] ?></span>
                            (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                        </p>
                        <p><strong>Reprobados (<12pts):</strong> 
                            <span class="badge badge-danger"><?= $estadisticas['reprobados'] ?></span>
                            (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                        </p>
                        
                        <!-- Gráfico simple de progreso -->
                        <?php if ($estadisticas['total_estudiantes'] > 0): ?>
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= ($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                Aprobados: <?= $estadisticas['aprobados'] ?>
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: <?= ($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                Reprobados: <?= $estadisticas['reprobados'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        break;
        
    case 'acciones-grupo':
        ?>
        <h4>Acciones Grupales</h4>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Precaución:</strong> Estas acciones afectarán a TODOS los estudiantes del grupo.
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-check-circle"></i> Aprobar Todo el Grupo
                    </div>
                    <div class="card-body">
                        <p>Aprobará las notas de todos los estudiantes en este grupo.</p>
                        <button class="btn btn-success btn-block" 
                                onclick="accionGrupo('aprobar')">
                            Aprobar Todo
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-times-circle"></i> Rechazar Todo el Grupo
                    </div>
                    <div class="card-body">
                        <p>Rechazará las notas de todos los estudiantes en este grupo.</p>
                        <button class="btn btn-danger btn-block" 
                                onclick="accionGrupo('rechazar')">
                            Rechazar Todo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function accionGrupo(accion) {
            if (confirm(`¿Está seguro de ${accion === 'aprobar' ? 'APROBAR' : 'RECHAZAR'} TODO el grupo?`)) {
                $.ajax({
                    url: 'procesar_acciones.php',
                    type: 'POST',
                    data: {
                        accion: accion,
                        docente_id: <?= $docente_id ?>,
                        materia_id: <?= $materia_id ?>,
                        periodo_id: <?= $periodo_id ?>,
                        accion_grupo: true
                    },
                    success: function() {
                        location.reload();
                    }
                });
            }
        }
        </script>
        <?php
        break;
}
?>