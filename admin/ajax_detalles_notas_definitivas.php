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

$info_grupo = obtenerInfoGrupoDefinitivas($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupoDefinitivas($docente_id, $materia_id, $periodo_id);

if (!$info_grupo) {
    die('Información no encontrada');
}

if ($accion === 'detalles') {
    // Mostrar detalles en el modal
    ?>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Cédula</th>
                    <th>Estudiante</th>
                    <th>Nota</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $contador = 1;
                while ($estudiante = $estudiantes->fetch_assoc()): 
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
    // Generar contenido para PDF - ESTILO PROFESIONAL
    ?>
    <div style="font-family: 'Arial', sans-serif; padding: 15px; color: #333;">
        <!-- Encabezado profesional -->
        <div style="text-align: center; margin-bottom: 25px; border-bottom: 2px solid #2c3e50; padding-bottom: 15px;">
            <h2 style="color: #2c3e50; margin: 0; font-size: 20px; font-weight: bold;">
                ACTA DE NOTAS DEFINITIVAS
            </h2>
            <p style="color: #7f8c8d; margin: 5px 0 0 0; font-size: 12px;">
                Universidad Politécnica Territorial de Puerto Cabello
            </p>
        </div>

        <!-- Información del grupo en tabla profesional -->
        <table style="width: 100%; margin-bottom: 20px; font-size: 10px; border-collapse: collapse;">
            <tr>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1; width: 25%;"><strong>Docente:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['nombre_docente']) ?></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1; width: 20%;"><strong>Materia:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['nombre_materia']) ?></td>
            </tr>
            <tr>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><strong>Código:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['cod_materia']) ?></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><strong>Sección:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['codigo_seccion']) ?></td>
            </tr>
            <tr>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><strong>Periodo:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['nombre_periodo']) ?></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><strong>Carrera:</strong></td>
                <td style="padding: 6px; border-bottom: 1px solid #ecf0f1;"><?= htmlspecialchars($info_grupo['nombre_carrera']) ?></td>
            </tr>
            <tr>
                <td style="padding: 6px;"><strong>Trayecto:</strong></td>
                <td style="padding: 6px;" colspan="3"><?= htmlspecialchars($info_grupo['nombre_trayecto']) ?></td>
            </tr>
        </table>

        <!-- Tabla de estudiantes - Estilo profesional -->
        <table style="width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 20px; border: 1px solid #34495e;">
            <thead>
                <tr style="background: #34495e; color: white;">
                    <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center; width: 8%;">N°</th>
                    <th style="border: 1px solid #2c3e50; padding: 8px; text-align: left; width: 25%;">CÉDULA</th>
                    <th style="border: 1px solid #2c3e50; padding: 8px; text-align: left; width: 47%;">ESTUDIANTE</th>
                    <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center; width: 10%;">NOTA</th>
                    <th style="border: 1px solid #2c3e50; padding: 8px; text-align: center; width: 10%;">FECHA</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $contador = 1;
                $estudiantes->data_seek(0);
                while ($estudiante = $estudiantes->fetch_assoc()): 
                    // Obtener la nota específica del trayecto
                    $nota_trayecto = '';
                    switch ($info_grupo['id_trayecto']) {
                        case 1: $nota_trayecto = $estudiante['trayecto_0']; break;
                        case 2: $nota_trayecto = $estudiante['trayecto_1']; break;
                        case 3: $nota_trayecto = $estudiante['trayecto_2']; break;
                        case 4: $nota_trayecto = $estudiante['trayecto_3']; break;
                        case 5: $nota_trayecto = $estudiante['trayecto_4']; break;
                    }
                    $fecha = date('d/m/Y', strtotime($estudiante['fecha_registro']));
                ?>
                <tr style="background: <?= $contador % 2 === 0 ? '#f8f9fa' : '#ffffff' ?>;">
                    <td style="border: 1px solid #bdc3c7; padding: 7px; text-align: center; font-weight: bold;"><?= $contador ?></td>
                    <td style="border: 1px solid #bdc3c7; padding: 7px;"><?= htmlspecialchars($estudiante['cedula']) ?></td>
                    <td style="border: 1px solid #bdc3c7; padding: 7px;"><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                    <td style="border: 1px solid #bdc3c7; padding: 7px; text-align: center; font-weight: bold; font-size: 10px;">
                        <?= $nota_trayecto !== null ? $nota_trayecto : 'N/A' ?>
                    </td>
                    <td style="border: 1px solid #bdc3c7; padding: 7px; text-align: center;"><?= $fecha ?></td>
                </tr>
                <?php 
                $contador++;
                endwhile; 
                ?>
            </tbody>
        </table>

        <!-- Firma y sello -->
        <div style="margin-top: 40px; border-top: 1px solid #bdc3c7; padding-top: 20px;">
            <table style="width: 100%; font-size: 9px;">
                <tr>
                    <td style="width: 50%; text-align: center;">
                        <div style="border-bottom: 1px solid #34495e; padding-bottom: 5px; margin-bottom: 5px; width: 70%; margin-left: auto; margin-right: auto;">
                            <strong>FIRMA DEL DOCENTE</strong>
                        </div>
                        <div style="color: #7f8c8d;">
                            <?= htmlspecialchars($info_grupo['nombre_docente']) ?>
                        </div>
                    </td>
                    <td style="width: 50%; text-align: center;">
                        <div style="border-bottom: 1px solid #34495e; padding-bottom: 5px; margin-bottom: 5px; width: 70%; margin-left: auto; margin-right: auto;">
                            <strong>SELLO INSTITUCIONAL</strong>
                        </div>
                        <div style="color: #7f8c8d;">
                            Universidad Politécnica Territorial<br>de Puerto Cabello
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Pie de página profesional -->
        <div style="margin-top: 30px; text-align: center; color: #95a5a6; font-size: 7px; border-top: 1px solid #ecf0f1; padding-top: 10px;">
            Documento generado el <?= date('d/m/Y H:i:s') ?> | Sistema Académico UPT Puerto Cabello<br>
            Este documento tiene validez oficial y debe ser conservado según normativa institucional
        </div>
    </div>
    <?php
}
?>