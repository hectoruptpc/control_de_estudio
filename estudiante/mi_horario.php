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

<style>
    :root {
        --color-clase: #e8f5e9;
        --color-texto-clase: #2e7d32;
        --border-clase: #388e3c;
    }

    /* Estilos de la Tabla */
    #tablaHorario {
        table-layout: fixed;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #6c757d;
        width: 100%;
    }

    #tablaHorario thead th {
        background-color: #2c3e50;
        color: white;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border: 1px solid #495057;
        padding: 6px 4px;
    }

    #tablaHorario tbody td {
        border: 1px solid #6c757d;
    }

    .hora-col {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #495057;
        width: 65px;
        text-align: center;
        padding: 6px 4px;
        font-size: 0.7rem;
        border: 1px solid #6c757d;
    }

    .materia-container {
        padding: 4px !important;
        vertical-align: middle !important;
        border: 1px solid #6c757d;
    }

    .bloque-clase {
        background-color: var(--color-clase);
        color: var(--color-texto-clase);
        border: 1px solid var(--border-clase);
        border-radius: 4px;
        padding: 4px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .materia-nombre {
        font-weight: 800;
        font-size: 0.65rem;
        line-height: 1.1;
        margin-bottom: 2px;
        text-transform: uppercase;
    }

    .docente-nombre {
        font-size: 0.6rem;
        opacity: 0.9;
    }

    .aula-tag {
        font-size: 0.55rem;
        font-weight: bold;
        margin-top: 2px;
        display: inline-block;
        background: rgba(255,255,255,0.5);
        padding: 1px 4px;
        border-radius: 3px;
    }

    /* Badges con colores claros */
    .badge-seccion {
        background-color: #e3f2fd;
        color: #1565c0;
        font-size: 0.65rem;
        padding: 4px 8px;
        margin-right: 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-carrera {
        background-color: #e8f5e9;
        color: #2e7d32;
        font-size: 0.65rem;
        padding: 4px 8px;
        margin-right: 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-turno {
        background-color: #fff3e0;
        color: #e65100;
        font-size: 0.65rem;
        padding: 4px 8px;
        margin-right: 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-trayecto {
        background-color: #f3e5f5;
        color: #6a1b9a;
        font-size: 0.65rem;
        padding: 4px 8px;
        margin-right: 5px;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-periodo {
        background-color: #e0f7fa;
        color: #006064;
        font-size: 0.65rem;
        padding: 4px 8px;
        margin-right: 5px;
        border-radius: 4px;
        display: inline-block;
    }

    /* CONFIGURACIÓN DE IMPRESIÓN - UNA SOLA HOJA */
    @media print {
        @page {
            size: landscape;
            margin: 0.3cm;
        }
        body * {
            visibility: hidden;
        }
        #seccionImprimir, #seccionImprimir * {
            visibility: visible;
        }
        #seccionImprimir {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .no-print {
            display: none !important;
        }
        .bloque-clase {
            border: 1px solid #2e7d32 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #6c757d !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card-body {
            padding: 0 !important;
        }
        #tablaHorario td, #tablaHorario th {
            border: 1px solid #6c757d !important;
            padding: 4px !important;
            font-size: 0.6rem !important;
        }
        #tablaHorario thead th {
            padding: 4px !important;
            font-size: 0.6rem !important;
            border: 1px solid #495057 !important;
        }
        .hora-col {
            padding: 4px !important;
            font-size: 0.6rem !important;
            width: 55px !important;
            border: 1px solid #6c757d !important;
        }
        .materia-container {
            border: 1px solid #6c757d !important;
        }
        .materia-nombre {
            font-size: 0.55rem !important;
        }
        .docente-nombre {
            font-size: 0.5rem !important;
        }
        .aula-tag {
            font-size: 0.45rem !important;
        }
        h2 {
            font-size: 1rem !important;
            margin: 0 !important;
        }
        .mt-2 {
            margin-top: 2px !important;
        }
        .mb-4 {
            margin-bottom: 5px !important;
        }
        .py-4 {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
        }
        .badge-seccion, .badge-carrera, .badge-turno, .badge-trayecto, .badge-periodo {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid py-4" id="seccionImprimir">
    <?php if ($seccion_estudiante && !empty($seccion_estudiante)): ?>
        <div class="row mb-3 align-items-center">
            <div class="col-md-8">
                <h2 class="mb-0 fw-bold" style="font-size: 1.2rem;"><i class="fas fa-calendar-check text-primary me-2"></i> HORARIO ACADÉMICO</h2>
                <div class="mt-1">
                    <span class="badge-seccion"><i class="fas fa-code-branch me-1"></i> SECCIÓN: <?= htmlspecialchars($seccion_estudiante['codigo_seccion'] ?? 'N/A') ?></span>
                    <span class="badge-carrera"><i class="fas fa-graduation-cap me-1"></i> CARRERA: <?= htmlspecialchars($seccion_estudiante['nombre_carrera'] ?? 'N/A') ?></span>
                    <span class="badge-turno"><i class="fas fa-clock me-1"></i> TURNO: <?= htmlspecialchars($seccion_estudiante['turno'] ?? 'N/A') ?></span>
                    <span class="badge-trayecto"><i class="fas fa-layer-group me-1"></i> TRAYECTO: <?= htmlspecialchars($seccion_estudiante['numero_trayecto'] ?? 'N/A') ?></span>
                    <span class="badge-periodo"><i class="fas fa-calendar-alt me-1"></i> PERÍODO: <?= htmlspecialchars($seccion_estudiante['nombre_periodo'] ?? 'N/A') ?></span>
                </div>
            </div>
            <div class="col-md-4 text-md-end no-print">
                <button onclick="window.print();" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-print me-2"></i> Imprimir / Guardar PDF
                </button>
            </div>
        </div>

        <div class="card border-0 shadow">
            <div class="card-body p-0">
                <?php
                $id_seccion = $seccion_estudiante['id_seccion'];
                $turno_seccion = $seccion_estudiante['turno'] ?? 'Diurno';
                $horarios = obtenerHorariosSeccion($db, $id_seccion);
                $horarios = is_array($horarios) ? $horarios : [];
                
                if (empty($horarios)):
                ?>
                    <div class="p-5 text-center">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <p class="mt-3 text-muted">No hay horarios cargados para esta sección.</p>
                    </div>
                <?php else: 
                    $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    
                    function horaToNumPrint($hora) {
                        return (int)substr($hora, 0, 2) + (int)substr($hora, 3, 2) / 60;
                    }
                    
                    $hora_min = 24;
                    $hora_max = 0;
                    foreach ($horarios as $horario) {
                        $hora_inicio_num = horaToNumPrint($horario['hora_inicio']);
                        $hora_fin_num = horaToNumPrint($horario['hora_fin']);
                        if ($hora_inicio_num < $hora_min) $hora_min = $hora_inicio_num;
                        if ($hora_fin_num > $hora_max) $hora_max = $hora_fin_num;
                    }
                    
                    if ($turno_seccion == 'Diurno') {
                        $hay_clases_fuera = false;
                        foreach ($horarios as $horario) {
                            $hora_inicio_num = horaToNumPrint($horario['hora_inicio']);
                            $hora_fin_num = horaToNumPrint($horario['hora_fin']);
                            if ($hora_inicio_num < 7 || $hora_fin_num > 17.5) {
                                $hay_clases_fuera = true;
                                break;
                            }
                        }
                        if ($hay_clases_fuera) {
                            $inicio = max(7, floor($hora_min));
                            $fin = min(20, ceil($hora_max));
                        } else {
                            $inicio = 7;
                            $fin = 17;
                        }
                    } else {
                        $hay_clases_fuera = false;
                        foreach ($horarios as $horario) {
                            $hora_inicio_num = horaToNumPrint($horario['hora_inicio']);
                            if ($hora_inicio_num < 17.5) {
                                $hay_clases_fuera = true;
                                break;
                            }
                        }
                        if ($hay_clases_fuera) {
                            $inicio = max(7, floor($hora_min));
                            $fin = min(20, ceil($hora_max));
                        } else {
                            $inicio = 17;
                            $fin = 20;
                        }
                    }
                    
                    $horas_tabla = [];
                    for ($h = $inicio; $h <= $fin; $h++) {
                        $horas_tabla[] = sprintf("%02d:00", $h);
                        if ($h < $fin) {
                            $horas_tabla[] = sprintf("%02d:30", $h);
                        }
                    }

                    $horarios_por_dia = array_fill(0, 6, []);
                    foreach ($horarios as $h) {
                        $horarios_por_dia[(int)$h['dia']][] = $h;
                    }
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered m-0 text-center" id="tablaHorario">
                            <thead>
                                <tr>
                                    <th class="hora-col">HORA</th>
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
                                        <td class="hora-col"><?= $hora ?></td>
                                        <?php for ($dia = 0; $dia <= 5; $dia++): ?>
                                            <?php
                                            if (isset($skip_cells[$dia][$hora])) continue;

                                            $clase_encontrada = null;
                                            foreach ($horarios_por_dia[$dia] as $clase) {
                                                if ($hora >= $clase['hora_inicio'] && $hora < $clase['hora_fin']) {
                                                    $clase_encontrada = $clase;
                                                    break;
                                                }
                                            }

                                            if ($clase_encontrada): 
                                                $h_ini = strtotime($hora);
                                                $h_fin_clase = strtotime($clase_encontrada['hora_fin']);
                                                $rowspan = ($h_fin_clase - $h_ini) / 1800;

                                                $temp_hora = $h_ini;
                                                for ($i = 1; $i < $rowspan; $i++) {
                                                    $temp_hora += 1800;
                                                    $skip_cells[$dia][date('H:i', $temp_hora)] = true;
                                                }
                                            ?>
                                                <td rowspan="<?= $rowspan ?>" class="materia-container">
                                                    <div class="bloque-clase">
                                                        <span class="materia-nombre"><?= htmlspecialchars($clase_encontrada['nombre_materia']) ?></span>
                                                        <span class="docente-nombre"><?= htmlspecialchars($clase_encontrada['nombre_docente']) ?></span>
                                                        <div><span class="aula-tag"><i class="fas fa-door-open me-1"></i> <?= htmlspecialchars($clase_encontrada['aula']) ?></span></div>
                                                    </div>
                                                 </td>
                                            <?php else: ?>
                                                <td class="bg-light"></td>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-2 no-print">
            <div class="alert alert-light border shadow-sm py-2" style="font-size: 0.8rem;">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong>Consejo:</strong> Para descargar este horario, haz clic en el botón azul y selecciona <strong>"Guardar como PDF"</strong>.
            </div>
        </div>

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