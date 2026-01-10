<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['docente_id']) || !isset($_POST['materia_id']) || !isset($_POST['periodo_id'])) {
    die('Parámetros incompletos');
}

$docente_id = (int)$_POST['docente_id'];
$materia_id = (int)$_POST['materia_id'];
$periodo_id = (int)$_POST['periodo_id'];
$seccion = $_POST['seccion'] ?? 'lista-estudiantes';
$accion = $_POST['accion'] ?? '';

// LLAMADAS A LAS FUNCIONES QUE ESTÁN AHORA EN FUNCTIONS.PHP
$info_grupo = obtenerInfoGrupoDefinitivas($docente_id, $materia_id, $periodo_id);
$estudiantes = obtenerEstudiantesGrupoDefinitivas($docente_id, $materia_id, $periodo_id);
$soporte_info = obtenerSoporteGrupoDefinitivas($docente_id, $materia_id, $periodo_id);

if (!$info_grupo) {
    die('Información no encontrada');
}

$estadisticas = obtenerEstadisticasGrupoDefinitivas($docente_id, $materia_id, $periodo_id, $info_grupo['id_trayecto']);

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

// Manejar acción PDF
if ($accion === 'pdf') {
    // Generar contenido para PDF
    ob_start();
    include('pdf_notas_definitivas.php');
    $contenido = ob_get_clean();
    echo $contenido;
    exit;
}

// Manejar secciones del modal
switch ($seccion) {
    case 'lista-estudiantes':
        ?>
        <h4>Lista de Estudiantes</h4>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            Trayecto considerado: <strong><?= $trayecto_considerado ?></strong><br>
            Aprobación: ≥12pts
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>Cédula</th>
                        <th>Estudiante</th>
                        <th width="90">Nota</th>
                        <th width="80">Estado</th>
                        <th width="120">Fecha</th>
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
                            <td><?= htmlspecialchars($estudiante['cedula']) ?></td>
                            <td><?= htmlspecialchars($estudiante['nombre_estudiante']) ?></td>
                            <td class="text-center">
                                <?php if ($nota_trayecto !== null): ?>
                                    <div class="nota-display">
                                        <span class="nota-valor <?= $nota_trayecto >= 12 ? 'nota-aprobada' : 'nota-reprobada' ?>">
                                            <?= $nota_trayecto ?>
                                        </span>
                                        <small class="text-muted">T<?= $info_grupo['id_trayecto'] - 1 ?></small>
                                    </div>
                                <?php else: ?>
                                    <span class="badge badge-secondary badge-sm">Sin nota</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-<?= $color_estado ?>">
                                    <?= $estado ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($estudiante['fecha_registro'])) ?>
                                </small>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <style>
        .nota-display {
            text-align: center;
            padding: 2px;
        }
        
        .nota-valor {
            display: inline-block;
            font-size: 1rem;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 6px;
            min-width: 40px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            color: #000000 !important; /* Texto negro */
            font-weight: 900; /* Texto más grueso */
        }
        
        .nota-aprobada {
            background: #90EE90; /* Verde claro */
            border: 1px solid #28a745;
        }
        
        .nota-reprobada {
            background: #FFB6C1; /* Rojo claro */
            border: 1px solid #dc3545;
        }
        
        .badge-sm {
            font-size: 0.75rem;
            padding: 3px 6px;
        }
        </style>
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
                        <p><strong>Cédula:</strong> <?= htmlspecialchars($info_grupo['cedula_docente']) ?></p>
                        <p><strong>Materia:</strong> <?= htmlspecialchars($info_grupo['nombre_materia']) ?></p>
                        <p><strong>Código:</strong> <?= htmlspecialchars($info_grupo['cod_materia']) ?></p>
                        <p><strong>Trayecto:</strong> <?= htmlspecialchars($info_grupo['nombre_trayecto']) ?> (ID: <?= $info_grupo['id_trayecto'] ?>)</p>
                        <p><strong>Periodo:</strong> <?= htmlspecialchars($info_grupo['nombre_periodo']) ?></p>
                        <p><strong>Sección:</strong> <?= htmlspecialchars($info_grupo['codigo_seccion']) ?></p>
                        <p><strong>Carrera:</strong> <?= htmlspecialchars($info_grupo['nombre_carrera']) ?></p>
                        <?php if (!empty($info_grupo['nombre_admin'])): ?>
                            <p><strong>Aprobado por:</strong> <?= htmlspecialchars($info_grupo['nombre_admin']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header bg-light">Estadísticas</div>
                    <div class="card-body">
                        <div class="stats-container">
                            <div class="stat-item">
                                <div class="stat-value h4 text-primary"><?= $estadisticas['total_estudiantes'] ?></div>
                                <div class="stat-label">Total</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 <?= $estadisticas['promedio_general'] >= 12 ? 'text-success' : 'text-warning' ?>">
                                    <?= $estadisticas['promedio_general'] ?>
                                </div>
                                <div class="stat-label">Promedio</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-success"><?= $estadisticas['aprobados'] ?></div>
                                <div class="stat-label">Aprobados</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-value h4 text-danger"><?= $estadisticas['reprobados'] ?></div>
                                <div class="stat-label">Reprobados</div>
                            </div>
                        </div>
                        
                        <!-- Gráfico simple de progreso -->
                        <?php if ($estadisticas['total_estudiantes'] > 0): ?>
                        <div class="progress mt-2" style="height: 18px;">
                            <div class="progress-bar bg-success" 
                                 style="width: <?= ($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <span class="progress-text" style="color: #000; font-weight: bold;"><?= $estadisticas['aprobados'] ?></span>
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: <?= ($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100 ?>%">
                                <span class="progress-text" style="color: #000; font-weight: bold;"><?= $estadisticas['reprobados'] ?></span>
                            </div>
                        </div>
                        <div class="text-center mt-1">
                            <small class="text-muted">
                                Aprobados: <?= $estadisticas['aprobados'] ?> 
                                (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['aprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%) | 
                                Reprobados: <?= $estadisticas['reprobados'] ?> 
                                (<?= $estadisticas['total_estudiantes'] > 0 ? round(($estadisticas['reprobados'] / $estadisticas['total_estudiantes']) * 100, 1) : 0 ?>%)
                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .stats-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .stat-item {
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .stat-value {
            font-weight: bold;
            margin-bottom: 2px;
            color: #000 !important; /* Texto negro en estadísticas */
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .progress-text {
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
        }
        </style>
        <?php
        break;
        
    case 'soporte':
        ?>
        <h4>Soporte del Grupo</h4>
        
        <?php if ($soporte_info): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> 
                <strong>Archivo de soporte disponible</strong>
            </div>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Información del Archivo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($soporte_info['soporte']) ?></p>
                            <p><strong>Tipo:</strong> 
                                <span class="badge badge-info"><?= strtoupper($soporte_info['tipo_archivo']) ?></span>
                            </p>
                            <p><strong>Registro:</strong> 
                                <?= date('d/m/Y H:i', strtotime($soporte_info['fecha_registro'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <?php if (in_array($soporte_info['tipo_archivo'], ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                    <div class="img-preview mb-2">
                                        <img src="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                             alt="Vista previa" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 150px;">
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-file-pdf fa-2x mb-1"></i>
                                        <br>
                                        <strong>PDF</strong>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="btn-group btn-group-sm">
                                    <a href="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                       class="btn btn-primary" 
                                       target="_blank" 
                                       download="<?= htmlspecialchars($soporte_info['soporte']) ?>">
                                        <i class="fas fa-download"></i> Descargar
                                    </a>
                                    <a href="../soportes/<?= htmlspecialchars($soporte_info['soporte']) ?>" 
                                       class="btn btn-info" 
                                       target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-2">
                <i class="fas fa-info-circle"></i>
                Este archivo fue utilizado durante la aprobación de las notas.
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>No hay archivo de soporte</strong>
            </div>
            
            <div class="card">
                <div class="card-body text-center py-3">
                    <i class="fas fa-paperclip fa-2x text-muted mb-2"></i>
                    <h5>Sin Soporte</h5>
                    <p class="text-muted mb-0">No se encontró archivo de soporte.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php
        break;
}
?>