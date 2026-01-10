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
            <h1 class="h3 text-gray-800">Mi Horario - Docente</h1>
            <div>
                <button class="btn btn-sm btn-success" onclick="generarPDF()">
                    <i class="fas fa-file-pdf"></i> Descargar PDF
                </button>
            </div>
        </div>
        
        <!-- Información básica del docente (solo para web) -->
        <div class="row mb-4 web-only">
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
                                        <?php
                                        $secciones_unicas = array_unique(array_column($horarios_docente, 'codigo_seccion'));
                                        ?>
                                        <strong>Secciones:</strong> <?= count($secciones_unicas) ?>
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
            <div class="card-header py-3 d-flex justify-content-between align-items-center web-only">
                <h6 class="m-0 font-weight-bold text-primary">Horario de Clases Semanal</h6>
                <div>
                    <span class="badge badge-info"><?= count($horarios_docente) ?> bloques horarios</span>
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
                
                <!-- Leyenda de materias (solo para web) -->
                <div class="card border-left-primary shadow py-2 web-only">
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
                    doc.save('Horario_Docente_<?= $_SESSION['user']['nombre'] ?? '' ?>.pdf');
                    
                    // Ocultar información para PDF después de generarlo
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