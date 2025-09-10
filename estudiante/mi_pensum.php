<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Mi Pensum Académico";
include('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

// 2. Obtener información del estudiante y su carrera
$user_id = (int)$_SESSION['user']['id'];
$query_estudiante = "SELECT carrera FROM users WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($db, $query_estudiante);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result_estudiante = mysqli_stmt_get_result($stmt);

if (!$result_estudiante || mysqli_num_rows($result_estudiante) === 0) {
    $_SESSION['error'] = "No se encontró información del estudiante";
    header('location: index.php');
    die();
}

$estudiante = mysqli_fetch_assoc($result_estudiante);
$id_carrera = (int)$estudiante['carrera'];

if ($id_carrera === 0) {
    $_SESSION['error'] = "No tienes una carrera asignada";
    header('location: index.php');
    die();
}

// 3. Obtener información detallada de la carrera
$query_carrera = "SELECT 
                    nombre_carrera, 
                    cod_carrera, 
                    tipo_formacion, 
                    duracion_semestres, 
                    titulo_otorga,
                    descripcion
                  FROM carreras 
                  WHERE id_carrera = ?";
$stmt = mysqli_prepare($db, $query_carrera);
mysqli_stmt_bind_param($stmt, 'i', $id_carrera);
mysqli_stmt_execute($stmt);
$result_carrera = mysqli_stmt_get_result($stmt);

if (!$result_carrera || mysqli_num_rows($result_carrera) === 0) {
    $_SESSION['error'] = "No se encontró información de tu carrera";
    header('location: index.php');
    die();
}

$carrera = mysqli_fetch_assoc($result_carrera);
$es_pnf = ($carrera['tipo_formacion'] == 'PNF');
$texto_duracion = $es_pnf ? 'trimestres' : 'semestres';

// 4. Obtener materias agrupadas por trayecto y ordenadas por duración
$query_materias = "SELECT 
                    m.id_materia,
                    m.cod_materia,
                    m.nombre_materia,
                    m.creditos,
                    m.horas_teoricas,
                    m.horas_practicas,
                    m.trayecto,
                    m.duracion_periodo,
                    m.activa,
                    cm.semestre
                  FROM materias m
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE cm.id_carrera = ?
                  ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
$stmt = mysqli_prepare($db, $query_materias);
mysqli_stmt_bind_param($stmt, 'i', $id_carrera);
mysqli_stmt_execute($stmt);
$result_materias = mysqli_stmt_get_result($stmt);

if (!$result_materias) {
    die("Error en consulta: " . mysqli_error($db));
}

// 5. Procesar y agrupar las materias solo por trayecto
$materias_agrupadas = [];
$total_creditos = 0;
$total_materias = 0;

while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = (int)$materia['trayecto'];
    
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : "Trayecto $trayecto";
    
    if (!isset($materias_agrupadas[$texto_trayecto])) {
        $materias_agrupadas[$texto_trayecto] = [];
    }
    
    $materias_agrupadas[$texto_trayecto][] = $materia;
    $total_creditos += (int)$materia['creditos'];
    $total_materias++;
}

include("includes/head.php");
?>

<!-- Incluir jsPDF y html2canvas para generar el PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-area, #printable-area * {
            visibility: visible;
        }
        #printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
        .card {
            border: none;
            box-shadow: none;
        }
        .table {
            font-size: 12px;
        }
        h4 {
            page-break-after: avoid;
        }
        .card-body {
            padding: 0;
        }
        .accordion .collapse {
            display: block !important;
            opacity: 1;
        }
    }
</style>

<script>
// Función para generar el PDF con el membrete
function generarPDF() {
    // Configuración de jsPDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const margin = 10;
    const pageWidth = doc.internal.pageSize.getWidth();
    
    // Obtener la fecha actual en formato corto
    const hoy = new Date();
    const fecha = hoy.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    
    // Cargar imagen desde URL (ruta actualizada)
    const logoImg = new Image();
    logoImg.crossOrigin = "Anonymous"; // Permite el acceso a recursos externos
    logoImg.src = '../uptpc.png'; // Ruta actualizada a tu imagen
    
    // Cuando la imagen se carga, agregarla al PDF
    logoImg.onload = function() {
        // Agregar la imagen al PDF (arriba a la izquierda)
        doc.addImage(logoImg, 'PNG', 15, 10, 20, 20);
        
        // Agregar el membrete centrado
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.text("República Bolivariana de Venezuela", pageWidth / 2, 15, { align: 'center' });
        doc.text("Ministerio del Poder Popular para la Educación Universitaria", pageWidth / 2, 20, { align: 'center' });
        doc.text("Universidad Politécnica Territorial de Puerto Cabello", pageWidth / 2, 25, { align: 'center' });
        doc.setFont(undefined, 'normal');
        doc.text(fecha, pageWidth - margin, 15, { align: 'right' });
        
        // Continuar con el proceso de generación del PDF
        const printableElement = document.getElementById('printable-area');
        
        html2canvas(printableElement, {
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);
            const imgWidth = pageWidth - (margin * 2);
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar contenido al PDF (empezando más abajo para dejar espacio al membrete)
            doc.addImage(imgData, 'JPEG', margin, 35, imgWidth, imgHeight);
            
            // Guardar el PDF
            doc.save('Pensum_Academico_' + new Date().toISOString().slice(0, 10) + '.pdf');
        });
    };
    
    // En caso de error al cargar la imagen
    logoImg.onerror = function() {
        console.error('Error al cargar la imagen del logo');
        
        // Continuar sin la imagen pero con el membrete centrado
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.text("República Bolivariana de Venezuela", pageWidth / 2, 15, { align: 'center' });
        doc.text("Ministerio del Poder Popular para la Educación Universitaria", pageWidth / 2, 20, { align: 'center' });
        doc.text("Universidad Politécnica Territorial de Puerto Cabello", pageWidth / 2, 25, { align: 'center' });
        doc.setFont(undefined, 'normal');
        doc.text(fecha, pageWidth / 2, 32, { align: 'center' });
        
        const printableElement = document.getElementById('printable-area');
        
        html2canvas(printableElement, {
            scale: 2,
            useCORS: true,
            logging: false
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);
            const imgWidth = pageWidth - (margin * 2);
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar contenido al PDF
            doc.addImage(imgData, 'JPEG', margin, 40, imgWidth, imgHeight);
            
            // Guardar el PDF
            doc.save('Pensum_Academico_' + new Date().toISOString().slice(0, 10) + '.pdf');
        });
    };
}
</script>

<div class="container-fluid">
    <!-- Encabezado principal -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Pensum Académico</h1>
        <div>
            <a href="index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm no-print">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver al Inicio
            </a>
            <button onclick="generarPDF()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm no-print ml-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Imprimir Pensum
            </button>
        </div>
    </div>

    <!-- Tarjeta con información de la carrera -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="m-0 font-weight-bold"><?= htmlspecialchars($carrera['nombre_carrera']) ?></h4>
                    <p class="mb-0">Código: <?= htmlspecialchars($carrera['cod_carrera']) ?></p>
                </div>
                <span class="badge badge-light">
                    <?= $es_pnf ? 'PNF' : 'Carrera Tradicional' ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Título que otorga:</strong> <?= htmlspecialchars($carrera['titulo_otorga']) ?></p>
                    <p><strong>Duración:</strong> <?= htmlspecialchars($carrera['duracion_semestres']) ?> semestres</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total de materias:</strong> <?= $total_materias ?></p>
                    <p><strong>Total de créditos:</strong> <?= $total_creditos ?></p>
                </div>
            </div>
            <?php if (!empty($carrera['descripcion'])): ?>
                <div class="mt-3">
                    <h5>Descripción del Programa</h5>
                    <p><?= nl2br(htmlspecialchars($carrera['descripcion'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tarjeta con el plan de estudios -->
    <div class="card shadow mb-4" id="printable-area">
        <div class="card-header py-3 bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Plan de Estudios</h5>
            <span class="no-print"><?php echo date('d/m/Y'); ?></span>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning">No hay materias asignadas a tu carrera.</div>
            <?php else: ?>
                <div class="accordion" id="pensumAccordion">
                    <?php foreach ($materias_agrupadas as $texto_trayecto => $materias): ?>
                        <div class="card mb-3">
                            <div class="card-header" id="heading<?= md5($texto_trayecto) ?>">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" 
                                            data-target="#collapse<?= md5($texto_trayecto) ?>" 
                                            aria-expanded="true" aria-controls="collapse<?= md5($texto_trayecto) ?>">
                                        <?= $texto_trayecto ?>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapse<?= md5($texto_trayecto) ?>" class="collapse show" 
                                 aria-labelledby="heading<?= md5($texto_trayecto) ?>" data-parent="#pensumAccordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="10%">Código</th>
                                                    <th width="35%">Nombre</th>
                                                    <th width="8%">Créditos</th>
                                                    <th width="12%">Horas T</th>
                                                    <th width="12%">Horas P</th>
                                                    <th width="13%">Duración</th>
                                                    <th width="10%">Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($materias as $materia): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($materia['cod_materia']) ?></td>
                                                        <td><?= htmlspecialchars($materia['nombre_materia']) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($materia['creditos']) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($materia['horas_teoricas']) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($materia['horas_practicas']) ?></td>
                                                        <td class="text-center"><?= htmlspecialchars($materia['duracion_periodo']) ?> <?= $texto_duracion ?></td>
                                                        <td class="text-center">
                                                            <span class="badge badge-<?= $materia['activa'] ? 'success' : 'secondary' ?>">
                                                                <?= $materia['activa'] ? 'Activa' : 'Inactiva' ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>