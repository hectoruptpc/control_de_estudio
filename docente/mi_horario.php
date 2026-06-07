<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Mi Horario - Docente";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener el horario del docente
$docente_id = (int)$_SESSION['user']['id'];
$horarios_docente = obtenerHorariosDocente($db, $docente_id);

include("includes/head.php");
?>

<!-- Estilos responsivos adicionales -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<style>
    /* Estilos responsivos generales */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        h1.h3 {
            font-size: 1.4rem;
            margin-bottom: 15px !important;
        }
        
        .btn-sm {
            padding: 8px 12px;
            font-size: 0.85rem;
        }
        
        /* --- MEJORA CRITICA: La tabla hace scroll, pero NO corta el texto --- */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        /* La tabla mantiene su tamaño natural, el contenedor hace scroll */
        .table {
            min-width: 800px; /* Fuerza el scroll horizontal en móviles para que quepa todo */
            width: max-content;
        }
        
        /* Las celdas mantienen el texto COMPLETO sin cortar */
        .table th, 
        .table td {
            padding: 10px 8px;
            font-size: 0.8rem;
            white-space: normal; /* Permite salto de linea si es muy largo, pero NO "..." */
            word-break: break-word; /* Rompe palabras largas si es necesario */
            vertical-align: middle;
        }
        
        /* Primera columna (Hora) más pegajosa */
        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            background-color: #f8f9fc;
            z-index: 1;
            font-weight: bold;
        }
        
        /* Estilo de las celdas de horario */
        .horario-block {
            background-color: #e3f2fd;
            font-weight: 600;
        }
        
        .continuacion-simbolo {
            font-size: 0.9rem;
            margin-right: 4px;
            font-weight: bold;
            color: #0056b3;
        }
        
        /* Tooltips */
        [data-toggle="tooltip"] {
            cursor: help;
        }
        
        /* Layout del encabezado */
        .d-flex.justify-content-between {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .d-flex.justify-content-between div:first-child {
            margin-bottom: 12px;
            width: 100%;
        }
        
        .d-flex.justify-content-between .btn {
            width: 100%;
        }
        
        /* Tarjetas de información */
        .row.mb-4 .row {
            flex-direction: column;
        }
        
        .col-md-4 {
            margin-bottom: 8px;
        }
        
        /* Detalle de clases */
        .col-md-6 {
            margin-bottom: 15px;
        }
        
        .d-flex.align-items-start {
            flex-direction: column;
        }
        
        .d-flex.align-items-start .mr-3 {
            margin-bottom: 8px;
        }
    }
    
    /* Para tablets (pantallas medianas) */
    @media (min-width: 769px) and (max-width: 1024px) {
        .table {
            min-width: 700px;
        }
        
        .d-flex.justify-content-between {
            flex-direction: row !important;
            align-items: center !important;
        }
        
        .d-flex.justify-content-between .btn {
            width: auto;
        }
    }
    
    /* Desktop */
    @media (min-width: 1025px) {
        .d-flex.justify-content-between {
            flex-direction: row !important;
            align-items: center !important;
        }
    }
    
    /* Estilos para el PDF (ocultos normalmente) */
    .pdf-only {
        display: none;
    }
    
    .celda-horario {
        transition: all 0.2s ease;
    }
    
    .table thead th {
        background-color: #343a40;
        color: white;
        position: sticky;
        top: 0;
        z-index: 2;
    }
</style>

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
    
    <?php if (!empty($horarios_docente)): ?>
        <!-- EL DOCENTE TIENE HORARIOS ASIGNADOS -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800">
                <i class="fas fa-calendar-alt"></i> Mi Horario - Docente
            </h1>
            <div>
                <button class="btn btn-sm btn-success" onclick="generarPDF()">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </div>
        </div>
        
        <!-- Información básica del docente -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col-12">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                    <i class="fas fa-user-graduate"></i> Información del Docente
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-2 mb-md-0">
                                        <strong>Nombre:</strong> 
                                        <span><?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'N/A') ?></span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <strong>Total de Clases:</strong> 
                                        <span class="badge badge-primary"><?= count($horarios_docente) ?></span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <?php
                                        $secciones_unicas = array_unique(array_column($horarios_docente, 'codigo_seccion'));
                                        ?>
                                        <strong>Secciones:</strong> 
                                        <span class="badge badge-info"><?= count($secciones_unicas) ?></span>
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
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock"></i> Horario de Clases Semanal
                </h6>
                <div>
                    <span class="badge badge-info"><?= count($horarios_docente) ?> bloques</span>
                </div>
            </div>
            <div class="card-body">
                <!-- Información para PDF (oculta en web) -->
                <div id="pdf-info" class="pdf-only text-center mb-3" style="display: none;">
                    <h4>Universidad Politécnica Territorial de Puerto Cabello</h4>
                    <h5>Horario de Clases - Docente</h5>
                    <p>
                        <strong>Docente:</strong> <?= htmlspecialchars($_SESSION['user']['nombre'] ?? 'N/A') ?> | 
                        <strong>Total de Clases:</strong> <?= count($horarios_docente) ?>
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
                
                <!-- Scroll horizontal SUAVE - Texto COMPLETO -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="min-width: 80px;">Hora</th>
                                <?php foreach ($dias_semana as $dia): ?>
                                    <th style="min-width: 130px;"><?= $dia ?></th>
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
                                                // Mostrar el nombre COMPLETO sin cortar
                                                $contenido_celda = htmlspecialchars($clase['materia']);
                                                $clase_css = 'horario-block';
                                                $tooltip_content = htmlspecialchars($clase['materia']) . 
                                                                  '\nSección: ' . htmlspecialchars($clase['seccion']) . 
                                                                  '\nCarrera: ' . htmlspecialchars($clase['carrera']) . 
                                                                  '\nAula: ' . htmlspecialchars($clase['aula']) . 
                                                                  '\nHora: ' . $clase['hora_inicio'] . ' - ' . $clase['hora_fin'];
                                                
                                                if ($hora != $clase['hora_inicio']) {
                                                    $clase_css .= ' continuacion';
                                                    $es_continuacion = true;
                                                }
                                                break;
                                            }
                                        }
                                        ?>
                                        <td class="<?= $clase_css ?>" data-toggle="tooltip" data-placement="top" title="<?= $tooltip_content ?>">
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
                            <i class="fas fa-info-circle"></i> Detalle de Clases Asignadas
                        </h5>
                        <div class="row">
                            <?php foreach ($horarios_docente as $index => $item): ?>
                                <div class="col-md-6 col-12 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="mr-3 mt-1 flex-shrink-0">
                                            <i class="fas fa-chalkboard-teacher text-primary fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="text-primary"><?= htmlspecialchars($item['nombre_materia']) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <span class="d-inline-block mr-2">
                                                    <i class="fas fa-calendar-day"></i> <?= $dias_semana[$item['dia']] ?>
                                                </span>
                                                <span class="d-inline-block">
                                                    <i class="fas fa-clock"></i> <?= date('H:i', strtotime($item['hora_inicio'])) ?> - <?= date('H:i', strtotime($item['hora_fin'])) ?>
                                                </span><br>
                                                <span class="d-inline-block mr-2">
                                                    <i class="fas fa-users"></i> Sección: <?= htmlspecialchars($item['codigo_seccion']) ?>
                                                </span>
                                                <span class="d-inline-block">
                                                    <i class="fas fa-building"></i> Aula: <?= htmlspecialchars($item['aula']) ?>
                                                </span><br>
                                                <span class="d-inline-block">
                                                    <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($item['nombre_carrera']) ?>
                                                </span>
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script>
        $(document).ready(function() {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // En móvil, mejorar experiencia con tooltips
            if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                $('[data-toggle="tooltip"]').on('click', function(e) {
                    e.preventDefault();
                    $(this).tooltip('toggle');
                    setTimeout(() => {
                        $(this).tooltip('hide');
                    }, 3000);
                });
            }
        });
        
        // Función para generar el PDF con membrete
        function generarPDF() {
            // Mostrar loading
            const btn = $('.btn-success');
            const originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> Generando...');
            btn.prop('disabled', true);
            
            document.getElementById('pdf-info').style.display = 'block';
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const margin = 10;
            const pageWidth = doc.internal.pageSize.getWidth();
            
            function agregarMembretePDF(doc, pageWidth, margin) {
                return new Promise((resolve) => {
                    const logoImg = new Image();
                    logoImg.crossOrigin = 'Anonymous';
                    logoImg.src = '../images/uptpc.png';
                    
                    logoImg.onload = function() {
                        doc.addImage(logoImg, 'PNG', margin, 10, 20, 20);
                        doc.setFontSize(11);
                        doc.setFont(undefined, 'bold');
                        doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                        doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                        doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
                        
                        const hoy = new Date();
                        const fecha = hoy.toLocaleDateString('es-ES');
                        doc.setFont(undefined, 'normal');
                        doc.setFontSize(9);
                        doc.text(fecha, pageWidth - margin, 15, { align: 'right' });
                        resolve(35);
                    };
                    
                    logoImg.onerror = function() {
                        doc.setFontSize(11);
                        doc.setFont(undefined, 'bold');
                        doc.text('República Bolivariana de Venezuela', pageWidth / 2, 15, { align: 'center' });
                        doc.text('Ministerio del Poder Popular para la Educación Universitaria', pageWidth / 2, 20, { align: 'center' });
                        doc.text('Universidad Politécnica Territorial de Puerto Cabello', pageWidth / 2, 25, { align: 'center' });
                        
                        const hoy = new Date();
                        const fecha = hoy.toLocaleDateString('es-ES');
                        doc.setFont(undefined, 'normal');
                        doc.text(fecha, pageWidth / 2, 32, { align: 'center' });
                        resolve(40);
                    };
                });
            }
            
            agregarMembretePDF(doc, pageWidth, margin).then(startY => {
                const element = document.getElementById('horario-clases');
                html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/jpeg', 1.0);
                    const imgWidth = pageWidth - (margin * 2);
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    
                    let yPosition = startY;
                    if (yPosition + imgHeight > doc.internal.pageSize.getHeight() - margin) {
                        doc.addPage();
                        yPosition = margin;
                    }
                    
                    doc.addImage(imgData, 'JPEG', margin, yPosition, imgWidth, imgHeight);
                    
                    const nombreDocente = '<?= preg_replace('/[^a-zA-Z0-9]/', '_', $_SESSION['user']['nombre'] ?? 'Docente') ?>';
                    doc.save(`Horario_${nombreDocente}.pdf`);
                    
                    btn.html(originalHtml);
                    btn.prop('disabled', false);
                    document.getElementById('pdf-info').style.display = 'none';
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error al generar el PDF.');
                    btn.html(originalHtml);
                    btn.prop('disabled', false);
                    document.getElementById('pdf-info').style.display = 'none';
                });
            });
        }
        </script>
        
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