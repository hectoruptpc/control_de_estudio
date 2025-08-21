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
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, t.nombre_trayecto
              FROM notas_pendientes np
              INNER JOIN users ud ON np.id_docente = ud.id
              INNER JOIN materias m ON np.id_materia = m.id_materia
              INNER JOIN trayectos t ON m.trayecto = t.id_trayecto
              INNER JOIN periodos_academicos pa ON np.id_periodo = pa.id_periodo
              INNER JOIN secciones s ON np.id_periodo = s.id_periodo
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
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

// Obtener estadísticas del grupo
function obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT 
                COUNT(*) as total_estudiantes,
                AVG((trayecto_0 + trayecto_1 + trayecto_2 + trayecto_3 + trayecto_4)/5) as promedio_general,
                SUM(CASE WHEN (trayecto_0 + trayecto_1 + trayecto_2 + trayecto_3 + trayecto_4)/5 >= 10 THEN 1 ELSE 0 END) as aprobados,
                SUM(CASE WHEN (trayecto_0 + trayecto_1 + trayecto_2 + trayecto_3 + trayecto_4)/5 < 10 THEN 1 ELSE 0 END) as reprobados
              FROM notas_pendientes 
              WHERE id_docente = ? 
              AND id_materia = ? 
              AND id_periodo = ?
              AND estado = 'pendiente'";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$info_grupo = obtenerInfoGrupo($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupo($docente_id, $materia_id, $periodo_id);
$estadisticas = obtenerEstadisticasGrupo($docente_id, $materia_id, $periodo_id);

if (!$info_grupo) {
    die('Información no encontrada');
}

switch ($seccion) {
    case 'lista-estudiantes':
        ?>
        <h4>Lista de Estudiantes</h4>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Seleccione los estudiantes cuyas notas desea aprobar o rechazar
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
                            <th>Promedio</th>
                            <th>Notas</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                            <?php
                            $notas = [];
                            $suma = 0;
                            $count = 0;
                            for ($i = 0; $i <= 4; $i++) {
                                if ($estudiante['trayecto_' . $i] !== null) {
                                    $notas[] = "T$i: " . $estudiante['trayecto_' . $i];
                                    $suma += $estudiante['trayecto_' . $i];
                                    $count++;
                                }
                            }
                            $promedio = $count > 0 ? round($suma / $count, 1) : 0;
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
                                    <span class="badge badge-<?= $promedio >= 10 ? 'success' : 'danger' ?>">
                                        <?= $promedio ?>
                                    </span>
                                </td>
                                <td><?= implode(', ', $notas) ?></td>
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
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Información del Grupo</div>
                    <div class="card-body">
                        <p><strong>Docente:</strong> <?= htmlspecialchars($info_grupo['nombre_docente']) ?></p>
                        <p><strong>Materia:</strong> <?= htmlspecialchars($info_grupo['nombre_materia']) ?></p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($info_grupo['cod_materia']) ?></p>
                        <p><strong>Trayecto:</strong> <?= htmlspecialchars($info_grupo['nombre_trayecto']) ?></p>
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
                        <p><strong>Total Estudiantes:</strong> <?= $estadisticas['total_estudiantes'] ?></p>
                        <p><strong>Promedio General:</strong> 
                            <span class="badge badge-<?= $estadisticas['promedio_general'] >= 10 ? 'success' : 'danger' ?>">
                                <?= round($estadisticas['promedio_general'], 1) ?>
                            </span>
                        </p>
                        <p><strong>Aprobados:</strong> 
                            <span class="badge badge-success"><?= $estadisticas['aprobados'] ?></span>
                        </p>
                        <p><strong>Reprobados:</strong> 
                            <span class="badge badge-danger"><?= $estadisticas['reprobados'] ?></span>
                        </p>
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