<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Mi Horario - Docente";
include('../funciones/functions.php');

// Verificar que el usuario es un docente - según tu estructura
if (empty($_SESSION['user']['docente']) || $_SESSION['user']['docente'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Obtener el horario del docente
$docente_id = (int)$_SESSION['user']['id'];
$horarios_docente = obtenerHorariosDocente($db, $docente_id);

// DEPURACIÓN: Mostrar información de lo que se obtiene
error_log("Docente ID: " . $docente_id);
error_log("Número de horarios obtenidos: " . count($horarios_docente));
if (!empty($horarios_docente)) {
    error_log("Primer horario: " . print_r($horarios_docente[0], true));
}

include("includes/head.php");
?>

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
    ?>
    
    <!-- DEPURACIÓN VISUAL (solo para desarrollo) -->
    <?php if (false): // Cambiar a true para ver depuración ?>
    <div class="alert alert-info">
        <strong>Depuración:</strong><br>
        Docente ID: <?= $docente_id ?><br>
        Horarios obtenidos: <?= count($horarios_docente) ?><br>
        <?php if (!empty($horarios_docente)): ?>
            <pre><?= print_r($horarios_docente, true) ?></pre>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($horarios_docente)): ?>
        <!-- EL DOCENTE TIENE HORARIOS ASIGNADOS -->
        <h1 class="h3 mb-4 text-gray-800">Mi Horario - Docente</h1>
        
        <!-- Información básica del docente -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Información del Docente
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'N/A') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total de Clases:</strong> <?= count($horarios_docente) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Secciones:</strong> 
                                        <?php
                                        $secciones_unicas = array_unique(array_column($horarios_docente, 'codigo_seccion'));
                                        echo count($secciones_unicas);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- HORARIO SEMANAL -->
        <div class="card shadow mb-4" id="horario-clases">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Horario de Clases Semanal</h6>
                <div>
                    <span class="badge badge-info"><?= count($horarios_docente) ?> bloques horarios</span>
                    <button class="btn btn-sm btn-success ml-2" onclick="imprimirHorario()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php
                // Definir las horas de la tabla (de 7:00 a 16:00)
                $horas_tabla = [];
                for ($h = 7; $h <= 16; $h++) {
                    $horas_tabla[] = sprintf("%02d:00", $h);
                }
                
                // Organizar los horarios por día
                $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $horarios_por_dia = array_fill(0, 6, []);
                
                foreach ($horarios_docente as $horario) {
                    $dia = (int)$horario['dia'];
                    $hora_inicio = date('H:i', strtotime($horario['hora_inicio']));
                    $hora_fin = date('H:i', strtotime($horario['hora_fin']));
                    
                    $horarios_por_dia[$dia][] = [
                        'materia' => $horario['nombre_materia'],
                        'seccion' => $horario['codigo_seccion'],
                        'carrera' => $horario['nombre_carrera'],
                        'aula' => $horario['aula'],
                        'hora_inicio' => $hora_inicio,
                        'hora_fin' => $hora_fin
                    ];
                }
                ?>
                
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="100">Hora</th>
                                <?php foreach ($dias_semana as $dia): ?>
                                    <th><?= $dia ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($horas_tabla as $index => $hora): ?>
                                <tr>
                                    <th class="bg-light"><?= $hora ?></th>
                                    <?php for ($dia = 0; $dia <= 5; $dia++): ?>
                                        <?php
                                        $contenido_celda = '';
                                        $clase_css = 'celda-horario';
                                        $es_continuacion = false;
                                        $tooltip_content = '';
                                        
                                        // Buscar si hay una clase en esta hora y día
                                        foreach ($horarios_por_dia[$dia] as $clase) {
                                            if ($hora >= $clase['hora_inicio'] && $hora < $clase['hora_fin']) {
                                                $contenido_celda = htmlspecialchars($clase['materia']);
                                                $clase_css = 'horario-block';
                                                $tooltip_content = htmlspecialchars($clase['materia']) . 
                                                                  '\\nSección: ' . htmlspecialchars($clase['seccion']) . 
                                                                  '\\nCarrera: ' . htmlspecialchars($clase['carrera']) . 
                                                                  '\\nAula: ' . htmlspecialchars($clase['aula']) . 
                                                                  '\\nHora: ' . $clase['hora_inicio'] . ' - ' . $clase['hora_fin'];
                                                
                                                // Verificar si es continuación
                                                if ($hora != $clase['hora_inicio']) {
                                                    $clase_css .= ' continuacion';
                                                    $es_continuacion = true;
                                                }
                                                break;
                                            }
                                        }
                                        ?>
                                        <td class="<?= $clase_css ?>" data-toggle="tooltip" title="<?= $tooltip_content ?>">
                                            <?php if ($es_continuacion): ?>
                                                <span class="continuacion-simbolo">↳</span>
                                            <?php endif; ?>
                                            <?= $contenido_celda ?>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Leyenda de materias -->
                <div class="card border-left-primary shadow py-2">
                    <div class="card-body">
                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-info-circle"></i> Detalle de Clases Asignadas
                        </h5>
                        <div class="row">
                            <?php foreach ($horarios_docente as $item): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="mr-3 mt-1">
                                            <i class="fas fa-chalkboard-teacher text-primary"></i>
                                        </div>
                                        <div>
                                            <strong class="text-primary"><?= htmlspecialchars($item['nombre_materia']) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <strong>Día:</strong> <?= $dias_semana[$item['dia']] ?><br>
                                                <strong>Horario:</strong> <?= date('H:i', strtotime($item['hora_inicio'])) ?> - <?= date('H:i', strtotime($item['hora_fin'])) ?><br>
                                                <strong>Sección:</strong> <?= htmlspecialchars($item['codigo_seccion']) ?><br>
                                                <strong>Carrera:</strong> <?= htmlspecialchars($item['nombre_carrera']) ?><br>
                                                <strong>Aula:</strong> <?= htmlspecialchars($item['aula']) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- EL DOCENTE NO TIENE HORARIOS ASIGNADOS -->
        <div class="text-center py-5">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-times fa-4x text-gray-300 mb-4"></i>
                    <h3 class="text-gray-800">No tienes horarios asignados</h3>
                    <p class="text-muted">Actualmente no tienes clases asignadas en tu horario.</p>
                    <p class="text-muted">Por favor, contacta con la administración para resolver esta situación.</p>
                    <a href="../index.php" class="btn btn-primary mt-3">
                        <i class="fas fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>