<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['docente_id']) || !isset($_POST['materia_id']) || !isset($_POST['periodo_id']) || !isset($_POST['accion'])) {
    die('Parámetros incompletos');
}

$docente_id = (int)$_POST['docente_id'];
$materia_id = (int)$_POST['materia_id'];
$periodo_id = (int)$_POST['periodo_id'];
$accion = $_POST['accion'];

// Obtener información del grupo para notas definitivas
function obtenerInfoGrupoDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT ud.nombre as nombre_docente, m.nombre_materia, m.cod_materia,
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera, 
                     t.nombre_trayecto, t.id_trayecto, t.numero_trayecto
              FROM notas_definitivas nd
              INNER JOIN users ud ON nd.id_docente = ud.id
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON nd.id_docente = ds.id_usuario 
                                           AND nd.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto
              WHERE nd.id_docente = ? 
              AND nd.id_materia = ? 
              AND nd.id_periodo = ?
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Obtener estudiantes del grupo para notas definitivas
function obtenerEstudiantesGrupoDefinitivas($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT nd.*, u.nombre as nombre_estudiante, u.idusuario as cedula,
                     admin.nombre as admin_aprobador
              FROM notas_definitivas nd
              INNER JOIN users u ON nd.id_usuario = u.id
              LEFT JOIN users admin ON nd.id_admin_aprobador = admin.id
              WHERE nd.id_docente = ? 
              AND nd.id_materia = ? 
              AND nd.id_periodo = ?
              ORDER BY u.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Calcular promedio según el id_trayecto de la sección
function calcularPromedioPorTrayecto($nota, $id_trayecto) {
    // Determinar qué trayectos promediar según el id_trayecto de la sección
    switch ($id_trayecto) {
        case 1: return $nota['trayecto_0'] ?? 0;
        case 2: return $nota['trayecto_1'] ?? 0;
        case 3: return $nota['trayecto_2'] ?? 0;
        case 4: return $nota['trayecto_3'] ?? 0;
        case 5: return $nota['trayecto_4'] ?? 0;
        default: return 0;
    }
}

$info_grupo = obtenerInfoGrupoDefinitivas($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupoDefinitivas($docente_id, $materia_id, $periodo_id);

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

if ($accion === 'detalles') {
    // Mostrar detalles en el modal
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Trayecto considerado: <strong><?= $trayecto_considerado ?></strong> | 
                Aprobación: ≥12pts
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Cédula</th>
                    <th>Estudiante</th>
                    <th>Nota</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $contador = 1;
                while ($estudiante = $estudiantes->fetch_assoc()): 
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
                    <td><?= $contador ?></td>
                    <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
                    <td><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                    <td>
                        <?php if ($nota_trayecto !== null): ?>
                            <span class="badge badge-info">
                                <?= $nota_trayecto ?>
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
                    <td><?= date('d/m/Y H:i', strtotime($estudiante['fecha_registro'])) ?></td>
                </tr>
                <?php 
                $contador++;
                endwhile; 
                ?>
            </tbody>
        </table>
    </div>
    <?php
} elseif ($accion === 'pdf') {
    // Generar contenido para PDF
    // Recalcular estadísticas
    $estudiantes->data_seek(0);
    $total_estudiantes = 0;
    $aprobados = 0;
    $suma_notas = 0;
    
    while ($estudiante = $estudiantes->fetch_assoc()) {
        $total_estudiantes++;
        $promedio = calcularPromedioPorTrayecto($estudiante, $info_grupo['id_trayecto']);
        $suma_notas += $promedio;
        if ($promedio >= 12) {
            $aprobados++;
        }
    }
    
    $promedio_general = $total_estudiantes > 0 ? round($suma_notas / $total_estudiantes, 1) : 0;
    $reprobados = $total_estudiantes - $aprobados;
    $porcentaje_aprobados = $total_estudiantes > 0 ? round(($aprobados / $total_estudiantes) * 100, 1) : 0;
    ?>
    <div style="font-family: Arial, sans-serif; padding: 10px;">
        <h3 style="text-align: center; color: #2c3e50; margin-bottom: 15px;">REPORTE DE NOTAS DEFINITIVAS</h3>
        
        <!-- Información del grupo -->
        <div style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: #34495e; margin-bottom: 8px; font-size: 14px;">Información del Grupo</h4>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 80px; font-weight: bold;">Docente:</td>
                    <td><?= htmlspecialchars($info_grupo['nombre_docente']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Materia:</td>
                    <td><?= htmlspecialchars($info_grupo['nombre_materia']) ?> (<?= htmlspecialchars($info_grupo['cod_materia']) ?>)</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Periodo:</td>
                    <td><?= htmlspecialchars($info_grupo['nombre_periodo']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sección:</td>
                    <td><?= htmlspecialchars($info_grupo['codigo_seccion']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Carrera:</td>
                    <td><?= htmlspecialchars($info_grupo['nombre_carrera']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Trayecto:</td>
                    <td><?= htmlspecialchars($info_grupo['nombre_trayecto']) ?></td>
                </tr>
            </table>
        </div>

        <h4 style="color: #2c3e50; margin-bottom: 10px; font-size: 13px;">Lista de Estudiantes - Notas Definitivas</h4>
        
        <div style="background: #e8f4fd; padding: 8px; border-radius: 3px; margin-bottom: 10px; border-left: 3px solid #3498db; font-size: 10px;">
            <strong>Información:</strong> Trayecto considerado: <strong><?= $trayecto_considerado ?></strong> | Aprobación: ≥12pts
        </div>
        
        <table style="width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 15px;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 5%;">#</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 20%;">Cédula</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: left; width: 35%;">Estudiante</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 10%;">Nota</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 15%;">Estado</th>
                    <th style="border: 1px solid #ddd; padding: 6px; text-align: center; width: 15%;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $contador = 1;
                $estudiantes->data_seek(0);
                while ($estudiante = $estudiantes->fetch_assoc()): 
                    $promedio = calcularPromedioPorTrayecto($estudiante, $info_grupo['id_trayecto']);
                    $estado = $promedio >= 12 ? 'Aprobado' : 'Reprobado';
                    $color_estado = $promedio >= 12 ? '#27ae60' : '#e74c3c';
                    $fecha = date('d/m/Y H:i', strtotime($estudiante['fecha_registro']));
                    
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
                <tr style="background: <?= $contador % 2 === 0 ? '#f8f9fa' : '#ffffff' ?>;">
                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center;"><?= $contador ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px;"><?= htmlspecialchars($estudiante['cedula']) ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px;"><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center; font-weight: bold;">
                        <?= $nota_trayecto !== null ? $nota_trayecto : 'N/A' ?>
                    </td>
                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center; color: <?= $color_estado ?>; font-weight: bold;">
                        <?= $estado ?>
                    </td>
                    <td style="border: 1px solid #ddd; padding: 6px; text-align: center;"><?= $fecha ?></td>
                </tr>
                <?php 
                $contador++;
                endwhile; 
                ?>
            </tbody>
        </table>
        
        <!-- Estadísticas -->
        <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px; border: 1px solid #dee2e6; font-size: 10px;">
            <h4 style="color: #2c3e50; margin-bottom: 8px; font-size: 12px;">Estadísticas del Grupo</h4>
            <table style="width: 100%; font-size: 10px;">
                <tr>
                    <td style="width: 120px; font-weight: bold;">Total estudiantes:</td>
                    <td style="font-weight: bold; color: #3498db;"><?= $total_estudiantes ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Aprobados:</td>
                    <td style="font-weight: bold; color: #27ae60;"><?= $aprobados ?> (<?= $porcentaje_aprobados ?>%)</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Reprobados:</td>
                    <td style="font-weight: bold; color: #e74c3c;"><?= $reprobados ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Promedio general:</td>
                    <td style="font-weight: bold; color: #f39c12;"><?= $promedio_general ?></td>
                </tr>
            </table>
        </div>
        
        <div style="margin-top: 15px; text-align: center; color: #7f8c8d; font-size: 8px;">
            Generado el: <?= date('d/m/Y H:i:s') ?> | Sistema Académico - UPT Puerto Cabello
        </div>
    </div>
    <?php
}
?>