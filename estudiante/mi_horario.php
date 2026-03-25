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

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

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
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800 mb-2 mb-sm-0">Mi Horario - <?= htmlspecialchars($seccion_estudiante['codigo_seccion']) ?></h1>
            <div>
                <button class="btn btn-sm btn-success" onclick="generarPDF()">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </div>
        </div>
        
        <!-- Información básica de la sección (responsive) -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                    Información de mi Sección
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3 mb-2">
                                <strong>Carrera:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_carrera']) ?>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <strong>Trayecto:</strong> <?= $seccion_estudiante['numero_trayecto'] ?>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <strong>Período:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_periodo']) ?>
                            </div>
                            <div class="col-12 col-md-3 mb-2">
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
        
        <!-- HORARIO SEMANAL -->
        <?php
        $horarios = obtenerHorariosSeccion($db, $seccion_estudiante['id_seccion']);
        $horarios = is_array($horarios) ? $horarios : [];
        ?>
        
        <div class="card shadow mb-4" id="horario-clases">
            <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary mb-2 mb-sm-0">Horario de Clases Semanal</h6>
                <div>
                    <span class="badge badge-info"><?= count($horarios) ?> bloques horarios</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($horarios)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No se han definido horarios para esta sección.
                    </div>
                <?php else: ?>
                    <!-- Información para PDF (oculta en web) -->
                    <div id="pdf-info" class="pdf-only text-center mb-3" style="display: none;">
                        <h4>Universidad Politécnica Territorial de Puerto Cabello</h4>
                        <h5>Horario de Clases - <?= htmlspecialchars($seccion_estudiante['codigo_seccion']) ?></h5>
                        <p>
                            <strong>Carrera:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_carrera']) ?> | 
                            <strong>Trayecto:</strong> <?= $seccion_estudiante['numero_trayecto'] ?> | 
                            <strong>Período:</strong> <?= htmlspecialchars($seccion_estudiante['nombre_periodo']) ?>
                        </p>
                    </div>
                    
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
                    
                    <!-- Vista para móviles: Tarjetas por día -->
                    <div class="d-block d-md-none">
                        <?php for ($dia = 0; $dia <= 5; $dia++): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <strong><?= $dias_semana[$dia] ?></strong>
                                </div>
                                <div class="card-body p-0">
                                    <?php 
                                    $clases_dia = $horarios_por_dia[$dia];
                                    if (empty($clases_dia)):
                                    ?>
                                        <div class="text-center text-muted p-3">
                                            <i class="fas fa-calendar-day"></i> Sin clases
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($clases_dia as $clase): ?>
                                            <div class="border-bottom p-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <strong class="text-primary"><?= htmlspecialchars($clase['materia']) ?></strong>
                                                        <div class="small text-muted mt-1">
                                                            <i class="fas fa-clock"></i> <?= $clase['hora_inicio'] ?> - <?= $clase['hora_fin'] ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($clase['docente']) ?>
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-door-open"></i> Aula: <?= htmlspecialchars($clase['aula']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Vista para escritorio: Tabla -->
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="80">Hora</th>
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
                                                                      '\nProf: ' . htmlspecialchars($clase['docente']) . 
                                                                      '\nAula: ' . htmlspecialchars($clase['aula']) . 
                                                                      '\nHora: ' . $clase['hora_inicio'] . ' - ' . $clase['hora_fin'];
                                                    
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
                    
                    <!-- Leyenda de materias (responsive) -->
                    <div class="card border-left-primary shadow py-2 mt-4">
                        <div class="card-body">
                            <h5 class="font-weight-bold text-primary mb-3">
                                <i class="fas fa-info-circle"></i> Detalle de Materias
                            </h5>
                            <div class="row">
                                <?php foreach ($horarios as $item): ?>
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <div class="d-flex align-items-start">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="text-primary"><?= htmlspecialchars($item['nombre_materia']) ?></strong>
                                                <br>
                                                <small class="text-muted d-block">
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
        /* Estilos generales */
        .horario-block {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            position: relative;
            cursor: help;
            font-size: 0.85rem;
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
            padding: 8px 6px;
            height: auto;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            font-size: 0.8rem;
        }
        
        /* Ajustes para pantallas medianas */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .table th, .table td {
                padding: 6px 4px;
                font-size: 0.7rem;
            }
            .horario-block {
                font-size: 0.7rem;
            }
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
            .btn, .web-only {
                display: none !important;
            }
            .pdf-only {
                display: block !important;
            }
            .d-block.d-md-none {
                display: none !important;
            }
            .d-none.d-md-block {
                display: block !important;
            }
        }
        
        /* Estilos para PDF */
        .pdf-only {
            display: none;
        }
        
        /* Mejoras para móviles */
        @media (max-width: 767.98px) {
            .card-header {
                flex-direction: column;
                text-align: center;
            }
            .card-body {
                padding: 0.75rem;
            }
            .badge {
                font-size: 0.7rem;
            }
        }
        </style>
        
        <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
        
        // Función para generar el PDF con membrete
        function generarPDF() {
            // Mostrar información para PDF
            document.getElementById('pdf-info').style.display = 'block';
            
            // Configuración de jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const margin = 10;
            const pageWidth = doc.internal.pageSize.getWidth();
            
            // Función para agregar membrete al PDF
            function agregarMembretePDF(doc, pageWidth, margin) {
                // Cargar imagen del logo
                const logoImg = new Image();
                logoImg.crossOrigin = 'Anonymous';
                logoImg.src = '../images/uptpc.png';
                
                return new Promise((resolve) => {
                    logoImg.onload = function() {
                        // Agregar logo (arriba a la izquierda)
                        doc.addImage(logoImg, 'PNG', margin, 10, 20, 20);
                        
                        // Agregar texto del membrete
                        doc.setFontSize(12);
                        doc.setFont(undefined, 'bold');
                        doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                        doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                        doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
                        
                        // Agregar fecha
                        const hoy = new Date();
                        const fecha = hoy.toLocaleDateString('es-ES');
                        doc.setFont(undefined, 'normal');
                        doc.text(fecha, pageWidth - margin, 15, { align: 'right' });
                        
                        resolve(35); // Retornar posición Y después del membrete
                    };
                    
                    logoImg.onerror = function() {
                        // Fallback sin imagen
                        doc.setFontSize(12);
                        doc.setFont(undefined, 'bold');
                        doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                        doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                        doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
                        
                        // Agregar fecha
                        const hoy = new Date();
                        const fecha = hoy.toLocaleDateString('es-ES');
                        doc.setFont(undefined, 'normal');
                        doc.text(fecha, pageWidth / 2, 32, { align: 'center' });
                        
                        resolve(40); // Retornar posición Y después del membrete
                    };
                });
            }
            
            // Llamar a la función para agregar el membrete
            agregarMembretePDF(doc, pageWidth, margin).then(startY => {
                // Capturar el contenido HTML y agregarlo al PDF
                html2canvas(document.getElementById('horario-clases'), {
                    scale: 2,
                    useCORS: true,
                    logging: false
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const imgWidth = pageWidth - (margin * 2);
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    // Agregar contenido al PDF
                    doc.addImage(imgData, 'JPEG', margin, startY, imgWidth, imgHeight);
                    
                    // Guardar el PDF
                    doc.save('Horario_<?= $seccion_estudiante['codigo_seccion'] ?>.pdf');
                    
                    // Ocultar información para PDF después de generarlo
                    document.getElementById('pdf-info').style.display = 'none';
                });
            });
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
                    <a href="index.php" class="btn btn-primary mt-3">
                        <i class="fas fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>