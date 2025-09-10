<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$titulopag = "Pensum de la Carrera";
include('../funciones/functions.php');

// Verificar conexión MySQLi
if (!$db) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Validar parámetro
if (!isset($_GET['id_carrera']) || !is_numeric($_GET['id_carrera'])) {
    header("Location: lista_carreras.php");
    exit();
}

$id_carrera = (int)$_GET['id_carrera'];

// Obtener información básica de la carrera
$query_carrera = "SELECT nombre_carrera, tipo_formacion FROM carreras WHERE id_carrera = $id_carrera";
$result_carrera = mysqli_query($db, $query_carrera);

if (!$result_carrera || mysqli_num_rows($result_carrera) === 0) {
    header("Location: lista_carreras.php");
    exit();
}

$carrera = mysqli_fetch_assoc($result_carrera);
$es_pnf = ($carrera['tipo_formacion'] == 'PNF');
$texto_duracion = $es_pnf ? 'trimestres' : 'semestres';

// Obtener materias agrupadas por trayecto y ordenadas por duración
$query_materias = "SELECT 
                    m.*, 
                    cm.semestre,
                    m.trayecto
                  FROM materias m
                  JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                  WHERE cm.id_carrera = $id_carrera
                  ORDER BY m.trayecto, m.duracion_periodo, m.nombre_materia";
$result_materias = mysqli_query($db, $query_materias);

if (!$result_materias) {
    die("Error en consulta: " . mysqli_error($db));
}

// Agrupar materias solo por trayecto
$materias_agrupadas = [];
while ($materia = mysqli_fetch_assoc($result_materias)) {
    $trayecto = $materia['trayecto'];
    
    // Formatear el texto del trayecto
    $texto_trayecto = ($trayecto == 0) ? 'Trayecto Inicial' : 'Trayecto ' . $trayecto;
    
    if (!isset($materias_agrupadas[$texto_trayecto])) {
        $materias_agrupadas[$texto_trayecto] = [];
    }
    
    $materias_agrupadas[$texto_trayecto][] = $materia;
}

include("includes/head.php");
?>

<script>
// Función reutilizable para agregar membrete (desde functions.php)
<?php echo generarMembreteJS(); ?>

// Función ESPECÍFICA para generar el PDF del pensum desde admin
async function generarPDF() {
    try {
        // Configuración de jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const margin = 10;
        const pageWidth = doc.internal.pageSize.getWidth();
        
        // Agregar membrete reutilizable
        const yPos = await agregarMembretePDF(doc, pageWidth, margin);
        
        // Continuar con el proceso de generación del PDF
        const printableElement = document.getElementById('printable-area');
        
        const canvas = await html2canvas(printableElement, {
            scale: 2,
            useCORS: true,
            logging: false
        });
        
        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        const imgWidth = pageWidth - (margin * 2);
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        // Agregar contenido al PDF
        doc.addImage(imgData, 'JPEG', margin, yPos, imgWidth, imgHeight);
        
        // Guardar el PDF
        doc.save('Pensum_<?php echo addslashes(preg_replace('/[^a-zA-Z0-9]/', '_', $carrera['nombre_carrera'])); ?>_' + new Date().toISOString().slice(0, 10) + '.pdf');
        
    } catch (error) {
        console.error('Error al generar PDF:', error);
        alert('Error al generar el PDF. Por favor, intenta nuevamente.');
    }
}
</script>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pensum: <?php echo htmlspecialchars($carrera['nombre_carrera']); ?></h1>
        <div>
            <a href="lista_carreras.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm no-print">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver a Carreras
            </a>
            <button onclick="generarPDF()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm no-print ml-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Generar PDF
            </button>
        </div>
    </div>

    <div class="card shadow mb-4" id="printable-area">
        <div class="card-header py-3 bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold">Plan de Estudios</h5>
            <span class="no-print"><?php echo date('d/m/Y'); ?></span>
        </div>
        <div class="card-body">
            <?php if (empty($materias_agrupadas)): ?>
                <div class="alert alert-warning">No hay materias asignadas a esta carrera.</div>
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
                                                        <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                                        <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['creditos']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['horas_teoricas']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['horas_practicas']); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($materia['duracion_periodo']) . ' ' . $texto_duracion; ?></td>
                                                        <td class="text-center">
                                                            <span class="badge badge-<?php echo $materia['activa'] ? 'success' : 'secondary'; ?>">
                                                                <?php echo $materia['activa'] ? 'Activa' : 'Inactiva'; ?>
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