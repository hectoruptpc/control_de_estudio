<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Mi Horario";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

visita();

$estudiante_id = (int)$_SESSION['user']['id'];
$seccion_estudiante = obtenerSeccionEstudiante($db, $estudiante_id);

include("includes/head.php");
?>

<div class="container-fluid py-4">
    <?php if ($seccion_estudiante && !empty($seccion_estudiante)): ?>
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Mi Horario</h2>
                    <p class="text-muted mb-0">
                        Sección: <?= htmlspecialchars($seccion_estudiante['codigo_seccion'] ?? 'N/A') ?> - 
                        Turno: <?= htmlspecialchars($seccion_estudiante['turno'] ?? 'No especificado') ?>
                    </p>
                </div>
                <button class="btn btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong><i class="fas fa-table"></i> Horario de Clases</strong>
            </div>
            <div class="card-body">
                <?php
                $id_seccion = $seccion_estudiante['id_seccion'] ?? 0;
                $turno_seccion = $seccion_estudiante['turno'] ?? 'Diurno';
                $horarios = obtenerHorariosSeccion($db, $id_seccion);
                $horarios = is_array($horarios) ? $horarios : [];
                
                if (empty($horarios)):
                ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> No se han definido horarios para esta sección.
                    </div>
                <?php else: ?>
                    <?php
                    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    
                    // Todas las horas disponibles
                    $todas_las_horas = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30'];
                    
                    // Determinar el rango de horas a mostrar según el turno y las clases existentes
                    $hora_min = 24;
                    $hora_max = 0;
                    
                    // Convertir hora a número para comparar
                    function horaToNum($hora) {
                        return (int)substr($hora, 0, 2) + (int)substr($hora, 3, 2) / 60;
                    }
                    
                    foreach ($horarios as $horario) {
                        $hora_inicio_num = horaToNum($horario['hora_inicio']);
                        $hora_fin_num = horaToNum($horario['hora_fin']);
                        
                        if ($hora_inicio_num < $hora_min) $hora_min = $hora_inicio_num;
                        if ($hora_fin_num > $hora_max) $hora_max = $hora_fin_num;
                    }
                    
                    if ($turno_seccion == 'Diurno') {
                        // Rango normal diurno: 07:00 a 17:30
                        $rango_normal_inicio = 7;
                        $rango_normal_fin = 17.5;
                        
                        // Verificar si hay clases fuera del rango normal
                        $hay_clases_fuera = false;
                        foreach ($horarios as $horario) {
                            $hora_inicio_num = horaToNum($horario['hora_inicio']);
                            $hora_fin_num = horaToNum($horario['hora_fin']);
                            if ($hora_inicio_num < $rango_normal_inicio || $hora_fin_num > $rango_normal_fin) {
                                $hay_clases_fuera = true;
                                break;
                            }
                        }
                        
                        if ($hay_clases_fuera) {
                            // Mostrar desde la hora más temprana hasta la más tarde
                            $inicio = max(7, floor($hora_min));
                            $fin = min(20, ceil($hora_max));
                        } else {
                            // Solo mostrar rango normal
                            $inicio = 7;
                            $fin = 17;
                        }
                    } else { // Nocturno
                        // Rango normal nocturno: 17:30 a 20:30
                        $rango_normal_inicio = 17.5;
                        
                        // Verificar si hay clases fuera del rango normal (antes de las 17:30)
                        $hay_clases_fuera = false;
                        foreach ($horarios as $horario) {
                            $hora_inicio_num = horaToNum($horario['hora_inicio']);
                            if ($hora_inicio_num < $rango_normal_inicio) {
                                $hay_clases_fuera = true;
                                break;
                            }
                        }
                        
                        if ($hay_clases_fuera) {
                            // Mostrar desde la hora más temprana
                            $inicio = max(7, floor($hora_min));
                            $fin = min(20, ceil($hora_max));
                        } else {
                            // Solo mostrar rango normal
                            $inicio = 17;
                            $fin = 20;
                        }
                    }
                    
                    // Construir el array de horas a mostrar
                    $horas_tabla = [];
                    for ($h = $inicio; $h <= $fin; $h++) {
                        $horas_tabla[] = sprintf("%02d:00", $h);
                        if ($h < $fin) {
                            $horas_tabla[] = sprintf("%02d:30", $h);
                        }
                    }
                    
                    $horarios_por_dia = array_fill(0, 6, []);
                    foreach ($horarios as $h) {
                        $dia = (int)$h['dia'];
                        $horarios_por_dia[$dia][] = $h;
                    }
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="80">Hora</th>
                                    <?php foreach ($dias_semana as $dia): ?>
                                        <th><?= $dia ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $skip_cells = [];
                                foreach ($horas_tabla as $hora): 
                                ?>
                                    <tr>
                                        <th class="bg-light"><?= $hora ?></th>
                                        <?php for ($dia = 0; $dia <= 5; $dia++): ?>
                                            <?php
                                            $contenido = '';
                                            $clase_css = 'bg-white';
                                            $rowspan = 1;
                                            $saltar = false;
                                            
                                            if (isset($skip_cells[$dia][$hora]) && $skip_cells[$dia][$hora]) {
                                                $saltar = true;
                                            }
                                            
                                            if (!$saltar) {
                                                foreach ($horarios_por_dia[$dia] as $clase) {
                                                    if ($hora >= $clase['hora_inicio'] && $hora < $clase['hora_fin']) {
                                                        $contenido = '<strong>' . htmlspecialchars($clase['nombre_materia']) . '</strong><br>' .
                                                                    '<small>' . htmlspecialchars($clase['nombre_docente']) . '</small><br>' .
                                                                    '<small>Aula: ' . htmlspecialchars($clase['aula']) . '</small>';
                                                        $clase_css = 'bg-success text-white';
                                                        
                                                        $hora_actual = strtotime($hora);
                                                        $hora_fin_clase = strtotime($clase['hora_fin']);
                                                        $diff_minutos = ($hora_fin_clase - $hora_actual) / 60;
                                                        $rowspan = $diff_minutos / 30;
                                                        
                                                        $hora_temp = $hora;
                                                        for ($i = 1; $i < $rowspan; $i++) {
                                                            $proxima_hora = date('H:i', strtotime($hora_temp . ' +30 minutes'));
                                                            $skip_cells[$dia][$proxima_hora] = true;
                                                            $hora_temp = $proxima_hora;
                                                        }
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <?php if (!$saltar): ?>
                                                <td class="<?= $clase_css ?>" rowspan="<?= $rowspan ?>">
                                                    <?= $contenido ?>
                                                </td>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Detalle de Materias</h6>
                        <div class="row">
                            <?php foreach ($horarios as $item): ?>
                                <div class="col-12 col-md-6 col-lg-4 mb-2">
                                    <div class="border p-2 rounded">
                                        <strong><?= htmlspecialchars($item['nombre_materia'] ?? '') ?></strong><br>
                                        <small>
                                            <strong>Día:</strong> <?= $dias_semana[$item['dia']] ?? '' ?><br>
                                            <strong>Horario:</strong> <?= date('H:i', strtotime($item['hora_inicio'])) ?> - <?= date('H:i', strtotime($item['hora_fin'])) ?><br>
                                            <strong>Profesor:</strong> <?= htmlspecialchars($item['nombre_docente'] ?? '') ?><br>
                                            <strong>Aula:</strong> <?= htmlspecialchars($item['aula'] ?? '') ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
            @media print {
                .btn, .card-header .btn, .container-fluid .btn {
                    display: none !important;
                }
                .card {
                    border: none !important;
                }
                .table-hover tbody tr:hover {
                    background-color: transparent !important;
                }
            }
            .table td {
                vertical-align: middle;
                padding: 8px;
            }
            .bg-success {
                background-color: #28a745 !important;
            }
        </style>
        
    <?php else: ?>
        <div class="text-center py-5">
            <div class="card shadow">
                <div class="card-body py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                    <h3>No tienes una sección asignada</h3>
                    <p class="text-muted">Actualmente no estás asignado a ninguna sección.</p>
                    <p class="text-muted">Por favor, contacta con la administración.</p>
                    <a href="index.php" class="btn btn-primary mt-3">
                        <i class="fas fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>