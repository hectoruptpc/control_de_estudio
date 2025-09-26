<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Consultar Notas Definitivas por Profesor";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('consultar_notas_pasadas');

// Verificar autenticación
if (!isLoggedIn()) {
    $_SESSION['msg'] = "Debes iniciar sesión para acceder";
    header('location: ../login.php');
    exit();
}

// Obtener lista de docentes que tienen notas definitivas
function obtenerDocentesConNotas() {
    global $db;
    
    $query = "SELECT DISTINCT 
                     ud.id,
                     ud.nombre as nombre_docente,
                     ud.username,
                     COUNT(DISTINCT nd.id_materia) as total_materias,
                     COUNT(nd.id) as total_notas,
                     MAX(nd.fecha_registro) as ultima_fecha
              FROM notas_definitivas nd
              INNER JOIN users ud ON nd.id_docente = ud.id
              WHERE ud.docente = 1 AND ud.status = 1
              GROUP BY ud.id, ud.nombre, ud.username
              ORDER BY ud.nombre";
    
    $result = $db->query($query);
    return $result;
}

// Obtener clases del docente seleccionado
function obtenerClasesDelDocente($docente_id, $fecha_desde = null, $fecha_hasta = null) {
    global $db;
    
    $query = "SELECT 
                     nd.id_materia,
                     nd.id_periodo,
                     m.nombre_materia,
                     m.trayecto,
                     pa.nombre_periodo,
                     pa.fecha_inicio,
                     pa.fecha_fin,
                     s.codigo_seccion,
                     c.nombre_carrera,
                     COUNT(nd.id) as total_estudiantes,
                     MAX(nd.fecha_registro) as ultima_fecha
              FROM notas_definitivas nd
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              INNER JOIN users ue ON nd.id_usuario = ue.id
              LEFT JOIN docente_seccion ds ON nd.id_docente = ds.id_usuario 
                                           AND nd.id_materia = ds.id_materia
              LEFT JOIN secciones s ON ds.id_seccion = s.id_seccion
              LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
              WHERE nd.id_docente = ?";
    
    $params = [$docente_id];
    $param_types = "i";
    
    // Filtros de fecha
    if ($fecha_desde) {
        $query .= " AND nd.fecha_registro >= ?";
        $params[] = $fecha_desde;
        $param_types .= "s";
    }
    
    if ($fecha_hasta) {
        $query .= " AND nd.fecha_registro <= ?";
        $params[] = $fecha_hasta . ' 23:59:59';
        $param_types .= "s";
    }
    
    $query .= " GROUP BY nd.id_materia, nd.id_periodo, m.nombre_materia, m.trayecto, 
                         pa.nombre_periodo, pa.fecha_inicio, pa.fecha_fin, 
                         s.codigo_seccion, c.nombre_carrera
                ORDER BY pa.fecha_inicio DESC, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($param_types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $clases = [];
    while ($row = $result->fetch_assoc()) {
        $clases[] = $row;
    }
    $stmt->close();
    
    return $clases;
}

// Obtener estudiantes y notas de una clase específica
function obtenerDetallesClase($docente_id, $materia_id, $periodo_id) {
    global $db;
    
    $query = "SELECT nd.id, 
                     ue.nombre as nombre_estudiante,
                     ue.idusuario as cedula,
                     ue.carrera,
                     nd.trayecto_0,
                     nd.trayecto_1,
                     nd.trayecto_2,
                     nd.trayecto_3,
                     nd.trayecto_4,
                     nd.fecha_registro,
                     admin.nombre as admin_aprobador
              FROM notas_definitivas nd
              INNER JOIN users ue ON nd.id_usuario = ue.id
              LEFT JOIN users admin ON nd.id_admin_aprobador = admin.id
              WHERE nd.id_docente = ? 
                AND nd.id_materia = ? 
                AND nd.id_periodo = ?
              ORDER BY ue.nombre";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $detalles = [];
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }
    $stmt->close();
    
    return $detalles;
}

// Procesar solicitud AJAX para detalles
if (isset($_POST['ajax']) && $_POST['ajax'] == 'detalles') {
    $docente_id = intval($_POST['docente_id']);
    $materia_id = intval($_POST['materia_id']);
    $periodo_id = intval($_POST['periodo_id']);
    $seccion = $_POST['seccion'];
    
    $detalles = obtenerDetallesClase($docente_id, $materia_id, $periodo_id);
    
    if ($seccion == 'lista-estudiantes') {
        echo '<h6>Lista de Estudiantes y Notas</h6>';
        
        if (empty($detalles)) {
            echo '<div class="alert alert-warning">No hay estudiantes registrados para esta clase.</div>';
        } else {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-sm">';
            echo '<thead class="thead-light">';
            echo '<tr>';
            echo '<th>Estudiante</th>';
            echo '<th>Cédula</th>';
            echo '<th>Carrera</th>';
            echo '<th>Nota T0</th>';
            echo '<th>Nota T1</th>';
            echo '<th>Nota T2</th>';
            echo '<th>Nota T3</th>';
            echo '<th>Nota T4</th>';
            echo '<th>Fecha Registro</th>';
            echo '<th>Aprobado por</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($detalles as $estudiante) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($estudiante['nombre_estudiante']) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['cedula'] ?? 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['carrera']) . '</td>';
                echo '<td>' . ($estudiante['trayecto_0'] !== null ? $estudiante['trayecto_0'] : '-') . '</td>';
                echo '<td>' . ($estudiante['trayecto_1'] !== null ? $estudiante['trayecto_1'] : '-') . '</td>';
                echo '<td>' . ($estudiante['trayecto_2'] !== null ? $estudiante['trayecto_2'] : '-') . '</td>';
                echo '<td>' . ($estudiante['trayecto_3'] !== null ? $estudiante['trayecto_3'] : '-') . '</td>';
                echo '<td>' . ($estudiante['trayecto_4'] !== null ? $estudiante['trayecto_4'] : '-') . '</td>';
                echo '<td>' . date('d/m/Y', strtotime($estudiante['fecha_registro'])) . '</td>';
                echo '<td>' . htmlspecialchars($estudiante['admin_aprobador'] ?? 'No asignado') . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            
            // Botón de exportación
            echo '<button type="button" class="btn btn-success btn-sm mt-3" onclick="exportarExcelClase(' . $docente_id . ', ' . $materia_id . ', ' . $periodo_id . ')">';
            echo '<i class="fas fa-file-excel"></i> Exportar a Excel';
            echo '</button>';
        }
    }
    elseif ($seccion == 'resumen') {
        echo '<h6>Resumen de la Clase</h6>';
        
        if (empty($detalles)) {
            echo '<div class="alert alert-warning">No hay datos para mostrar.</div>';
        } else {
            $total_estudiantes = count($detalles);
            $notas_promedio = [
                't0' => 0, 't1' => 0, 't2' => 0, 't3' => 0, 't4' => 0
            ];
            $contadores = [0, 0, 0, 0, 0];
            
            foreach ($detalles as $est) {
                for ($i = 0; $i <= 4; $i++) {
                    $campo = "trayecto_$i";
                    if (isset($est[$campo]) && is_numeric($est[$campo]) && $est[$campo] !== null) {
                        $notas_promedio["t$i"] += $est[$campo];
                        $contadores[$i]++;
                    }
                }
            }
            
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<h6>Estadísticas de la Clase</h6>';
            echo '<p>Total de estudiantes: <strong>' . $total_estudiantes . '</strong></p>';
            
            for ($i = 0; $i <= 4; $i++) {
                if ($contadores[$i] > 0) {
                    $promedio = $notas_promedio["t$i"] / $contadores[$i];
                    echo '<p>Promedio Trayecto ' . $i . ': <strong>' . number_format($promedio, 2) . '</strong></p>';
                } else {
                    echo '<p>Promedio Trayecto ' . $i . ': <strong>No hay datos</strong></p>';
                }
            }
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    exit();
}

// Procesar filtros
$docente_seleccionado = null;
$clases_docente = [];
$filtros = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['docente_id'])) {
        $docente_seleccionado = intval($_POST['docente_id']);
        $filtros['docente_id'] = $docente_seleccionado;
        
        $fecha_desde = !empty($_POST['fecha_desde']) ? $_POST['fecha_desde'] : null;
        $fecha_hasta = !empty($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : null;
        
        $filtros['fecha_desde'] = $fecha_desde;
        $filtros['fecha_hasta'] = $fecha_hasta;
        
        $clases_docente = obtenerClasesDelDocente($docente_seleccionado, $fecha_desde, $fecha_hasta);
    }
}

$docentes = obtenerDocentesConNotas();

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4 text-gray-800">Consultar Notas Definitivas por Profesor</h1>
            
            <!-- Filtros principales -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="docente_id">Seleccionar Profesor:</label>
                                    <select class="form-control select2" id="docente_id" name="docente_id" required>
                                        <option value="">-- Buscar Profesor --</option>
                                        <?php 
                                        if ($docentes && $docentes->num_rows > 0) {
                                            $docentes->data_seek(0);
                                            while ($docente = $docentes->fetch_assoc()): 
                                        ?>
                                            <option value="<?php echo $docente['id']; ?>" 
                                                <?php echo (isset($filtros['docente_id']) && $filtros['docente_id'] == $docente['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($docente['nombre_docente']); ?> 
                                                (<?php echo $docente['total_materias']; ?> materias)
                                            </option>
                                        <?php 
                                            endwhile;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_desde">Fecha Desde:</label>
                                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                                           value="<?php echo $filtros['fecha_desde'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha_hasta">Fecha Hasta:</label>
                                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                                           value="<?php echo $filtros['fecha_hasta'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de clases del docente seleccionado -->
            <?php if ($docente_seleccionado): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-success text-white">
                        <h6 class="m-0 font-weight-bold">
                            Clases del Profesor: 
                            <?php 
                                $docente_nombre = '';
                                if ($docentes && $docentes->num_rows > 0) {
                                    $docentes->data_seek(0);
                                    while ($doc = $docentes->fetch_assoc()) {
                                        if ($doc['id'] == $docente_seleccionado) {
                                            $docente_nombre = $doc['nombre_docente'];
                                            break;
                                        }
                                    }
                                }
                                echo htmlspecialchars($docente_nombre);
                            ?>
                            (<?php echo count($clases_docente); ?> clases encontradas)
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($clases_docente)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tablaClases" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Materia</th>
                                            <th>Trayecto</th>
                                            <th>Periodo</th>
                                            <th>Sección</th>
                                            <th>Carrera</th>
                                            <th># Estudiantes</th>
                                            <th>Última Actualización</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clases_docente as $clase): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($clase['nombre_materia']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['trayecto']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['nombre_periodo']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['codigo_seccion'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($clase['nombre_carrera'] ?? 'N/A'); ?></td>
                                                <td><span class="badge badge-info"><?php echo $clase['total_estudiantes']; ?></span></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($clase['ultima_fecha'])); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info btn-detalles" 
                                                            data-toggle="modal" data-target="#modalDetalles"
                                                            data-docente-id="<?php echo $docente_seleccionado; ?>"
                                                            data-materia-id="<?php echo $clase['id_materia']; ?>"
                                                            data-periodo-id="<?php echo $clase['id_periodo']; ?>"
                                                            data-docente="<?php echo htmlspecialchars($docente_nombre); ?>"
                                                            data-materia="<?php echo htmlspecialchars($clase['nombre_materia']); ?>"
                                                            data-periodo="<?php echo htmlspecialchars($clase['nombre_periodo']); ?>">
                                                        <i class="fas fa-eye"></i> Ver Notas
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                No se encontraron clases con notas para el profesor seleccionado en el rango de fechas especificado.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de la clase -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalles de Notas - <span id="tituloClase"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Sidebar de navegación -->
                    <div class="col-md-3">
                        <div class="list-group" id="sidebarDetalles">
                            <a href="#lista-estudiantes" class="list-group-item list-group-item-action active" data-toggle="tab">
                                <i class="fas fa-users"></i> Lista de Estudiantes
                            </a>
                            <a href="#resumen" class="list-group-item list-group-item-action" data-toggle="tab">
                                <i class="fas fa-chart-bar"></i> Resumen
                            </a>
                        </div>
                    </div>
                    
                    <!-- Contenido de las pestañas -->
                    <div class="col-md-9">
                        <div class="tab-content" id="contenidoDetalles">
                            <div class="tab-pane fade show active" id="lista-estudiantes">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando estudiantes...</p>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="resumen">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando resumen...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar Select2 para búsqueda de profesores
    $('.select2').select2({
        placeholder: "Buscar profesor...",
        allowClear: true
    });

    // Inicializar DataTable para la tabla de clases
    if ($.fn.DataTable.isDataTable('#tablaClases')) {
        $('#tablaClases').DataTable().destroy();
    }
    $('#tablaClases').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "pageLength": 25,
        "order": [[6, 'desc']]
    });

    // Cargar detalles de la clase via AJAX
    $(document).on('click', '.btn-detalles', function() {
        const docenteId = $(this).data('docente-id');
        const materiaId = $(this).data('materia-id');
        const periodoId = $(this).data('periodo-id');
        const docente = $(this).data('docente');
        const materia = $(this).data('materia');
        const periodo = $(this).data('periodo');
        
        // Actualizar título del modal
        $('#tituloClase').text(`${docente} - ${materia} - ${periodo}`);
        
        // Mostrar loading en ambas pestañas
        $('#lista-estudiantes').html('<div class="text-center"><div class="spinner-border text-primary"></div><p>Cargando estudiantes...</p></div>');
        $('#resumen').html('<div class="text-center"><div class="spinner-border text-primary"></div><p>Cargando resumen...</p></div>');
        
        // Cargar lista de estudiantes inmediatamente
        cargarSeccion('lista-estudiantes', docenteId, materiaId, periodoId);
        
        // Guardar IDs para uso posterior
        $('#modalDetalles').data('current-docente', docenteId);
        $('#modalDetalles').data('current-materia', materiaId);
        $('#modalDetalles').data('current-periodo', periodoId);
    });
    
    // Cambiar entre pestañas - cargar contenido cuando se active la pestaña
    $('#modalDetalles').on('show.bs.modal', function() {
        // Ya cargamos lista-estudiantes al abrir el modal
    });
    
    // Cuando se hace clic en una pestaña, cargar su contenido si no está cargado
    $('#sidebarDetalles a').on('click', function(e) {
        e.preventDefault();
        
        // Remover active de todas las pestañas
        $('#sidebarDetalles a').removeClass('active');
        // Agregar active a la pestaña clickeada
        $(this).addClass('active');
        
        const target = $(this).attr('href').substring(1);
        const docenteId = $('#modalDetalles').data('current-docente');
        const materiaId = $('#modalDetalles').data('current-materia');
        const periodoId = $('#modalDetalles').data('current-periodo');
        
        // Mostrar loading
        $('#' + target).html('<div class="text-center"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>');
        
        // Cargar contenido de la pestaña
        cargarSeccion(target, docenteId, materiaId, periodoId);
    });
});

function cargarSeccion(seccion, docenteId, materiaId, periodoId) {
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: { 
            ajax: 'detalles',
            docente_id: docenteId, 
            materia_id: materiaId, 
            periodo_id: periodoId,
            seccion: seccion
        },
        success: function(data) {
            $('#' + seccion).html(data);
        },
        error: function(xhr, status, error) {
            $('#' + seccion).html('<div class="alert alert-danger">Error al cargar los datos: ' + error + '</div>');
        }
    });
}

function exportarExcelClase(docenteId, materiaId, periodoId) {
    // Redirigir a página de exportación
    window.open('exportar_notas.php?docente=' + docenteId + '&materia=' + materiaId + '&periodo=' + periodoId, '_blank');
}
</script>

<!-- Incluir Select2 CSS y JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.js"></script>

<?php include("includes/footer.php"); ?>