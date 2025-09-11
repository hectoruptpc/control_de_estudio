<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Mi Horario";
include('../funciones/functions.php');

// Verificar que el usuario es un estudiante - CORREGIDO según tu estructura
if (empty($_SESSION['user']['estudiante']) || $_SESSION['user']['estudiante'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Obtener la sección del estudiante - CORREGIDO: usar $_SESSION['user']['id']
$estudiante_id = (int)$_SESSION['user']['id'];
$seccion_estudiante = obtenerSeccionEstudiante($db, $estudiante_id);

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
    
    <?php if ($seccion_estudiante): ?>
        <!-- EL ESTUDIANTE TIENE UNA SECCIÓN ASIGNADA -->
        <h1 class="h3 mb-4 text-gray-800">Mi Horario - <?= htmlspecialchars($seccion_estudiante['codigo_seccion']) ?></h1>
        
        <!-- Información básica de la sección -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Información de mi Sección
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Carrera:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_carrera']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Trayecto:</strong> <?= $seccion_estudiante['numero_trayecto'] ?>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Período:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_periodo']) ?>
                                    </div>
                                    <div class="col-md-2">
                                        <strong>Estado:</strong> 
                                        <span class="badge badge-<?= $seccion_estudiante['estatus'] == 'activa' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($seccion_estudiante['estatus']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- HORARIO SEMANAL -->
        <?php
        $horarios = obtenerHorariosSeccion($db, $seccion_estudiante['id_seccion']);
        $horarios = is_array($horarios) ? $horarios : [];
        ?>
        
        <div class="card shadow mb-4" id="horario-clases">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Horario de Clases Semanal</h6>
                <div>
                    <span class="badge badge-info"><?= count($horarios) ?> bloques horarios</span>
                    <button class="btn btn-sm btn-success ml-2" onclick="imprimirHorario()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($horarios)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No se han definido horarios para esta sección.
                    </div>
                <?php else: ?>
                    <?php
                    // Definir las horas de la tabla (de 7:00 a 16:00)
                    $horas_tabla = [];
                    for ($h = 7; $h <= 16; $h++) {
                        $horas_tabla[] = sprintf("%02d:00", $h);
                    }
                    
                    // Organizar los horarios por día
                    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    $horarios_por_dia = array_fill(0, 6, []);
                    
                    foreach ($horarios as $horario) {
                        $dia = (int)$horario['dia'];
                        $hora_inicio = date('H:i', strtotime($horario['hora_inicio']));
                        $hora_fin = date('H:i', strtotime($horario['hora_fin']));
                        
                        $horarios_por_dia[$dia][] = [
                            'materia' => $horario['nombre_materia'],
                            'docente' => $horario['nombre_docente'],
                            'aula' => $horario['aula'],
                            'hora_inicio' => $hora_inicio,
                            'hora_fin' => $hora_fin,
                            'cod_materia' => $horario['cod_materia'] ?? ''
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
                                                                      '\\nProf: ' . htmlspecialchars($clase['docente']) . 
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
                                <i class="fas fa-info-circle"></i> Detalle de Materias
                            </h5>
                            <div class="row">
                                <?php foreach ($horarios as $item): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                            <div>
                                                <strong class="text-primary"><?= htmlspecialchars($item['nombre_materia']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <strong>Día:</strong> <?= $dias_semana[$item['dia']] ?><br>
                                                    <strong>Horario:</strong> <?= date('H:i', strtotime($item['hora_inicio'])) ?> - <?= date('H:i', strtotime($item['hora_fin'])) ?><br>
                                                    <strong>Profesor:</strong> <?= htmlspecialchars($item['nombre_docente']) ?><br>
                                                    <strong>Aula:</strong> <?= htmlspecialchars($item['aula']) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .horario-block {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            position: relative;
            cursor: help;
        }
        
        .horario-block.continuacion {
            background-color: #bbdefb;
            border-left: 4px solid #64b5f6;
            font-weight: normal;
        }
        
        .continuacion-simbolo {
            color: #1976d2;
            margin-right: 5px;
        }
        
        .celda-horario {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .table {
            table-layout: fixed;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 12px;
            height: 60px;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        
        /* Estilos para impresión */
        @media print {
            body * {
                visibility: hidden;
            }
            #horario-clases, #horario-clases * {
                visibility: visible;
            }
            #horario-clases {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .btn, .mb-4 {
                display: none !important;
            }
        }
        </style>
        
        <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Ajustar el contenido de las celdas para mejor visualización
            $('.horario-block').each(function() {
                var texto = $(this).text();
                if (texto.length > 20) {
                    $(this).text(texto.substring(0, 17) + '...');
                }
            });
        });
        
        function imprimirHorario() {
            window.print();
        }
        </script>
        
    <?php else: ?>
        <!-- EL ESTUDIANTE NO TIENE SECCIÓN ASIGNADA -->
        <div class="text-center py-5">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-times fa-4x text-gray-300 mb-4"></i>
                    <h3 class="text-gray-800">No tienes una sección asignada</h3>
                    <p class="text-muted">Actualmente no estás asignado a ninguna sección.</p>
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