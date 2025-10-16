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

// Obtener lista de profesores para el filtro (docente = 1)
function obtenerProfesores() {
    global $db;
    $query = "SELECT id, idusuario, nombre 
              FROM users 
              WHERE docente = 1 
              ORDER BY nombre";
    $result = $db->query($query);
    return $result;
}

// Nueva función para buscar profesores por término
function buscarProfesores($termino) {
    global $db;
    $query = "SELECT id, idusuario, nombre 
              FROM users 
              WHERE docente = 1 
              AND (nombre LIKE ? OR idusuario LIKE ?)
              ORDER BY nombre
              LIMIT 10";
    $stmt = $db->prepare($query);
    $termino_like = "%$termino%";
    $stmt->bind_param("ss", $termino_like, $termino_like);
    $stmt->execute();
    return $stmt->get_result();
}

// Obtener información de un profesor específico
function obtenerProfesorPorId($id) {
    global $db;
    $query = "SELECT id, idusuario, nombre 
              FROM users 
              WHERE id = ? AND docente = 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Obtener grupos de notas definitivas agrupados por docente/materia/periodo con filtros
function obtenerGruposNotasDefinitivas($filtro_profesor = '', $filtro_fecha_desde = '', $filtro_fecha_hasta = '') {
    global $db;
    
    $query = "SELECT nd.id_docente, nd.id_materia, nd.id_periodo,
                     ud.nombre as nombre_docente, ud.idusuario as cedula_docente,
                     m.nombre_materia, 
                     pa.nombre_periodo, s.codigo_seccion, c.nombre_carrera,
                     COUNT(nd.id) as total_notas, MAX(nd.fecha_registro) as ultima_fecha
              FROM notas_definitivas nd
              INNER JOIN users ud ON nd.id_docente = ud.id
              INNER JOIN materias m ON nd.id_materia = m.id_materia
              INNER JOIN periodos_academicos pa ON nd.id_periodo = pa.id_periodo
              INNER JOIN docente_seccion ds ON nd.id_docente = ds.id_usuario 
                                           AND nd.id_materia = ds.id_materia
              INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
              INNER JOIN carreras c ON s.id_carrera = c.id_carrera
              WHERE 1=1";
    
    $params = array();
    $types = '';
    
    // Aplicar filtro por profesor
    if (!empty($filtro_profesor)) {
        $query .= " AND nd.id_docente = ?";
        $params[] = $filtro_profesor;
        $types .= "i";
    }
    
    // Aplicar filtro por fecha desde
    if (!empty($filtro_fecha_desde)) {
        $query .= " AND DATE(nd.fecha_registro) >= ?";
        $params[] = $filtro_fecha_desde;
        $types .= "s";
    }
    
    // Aplicar filtro por fecha hasta
    if (!empty($filtro_fecha_hasta)) {
        $query .= " AND DATE(nd.fecha_registro) <= ?";
        $params[] = $filtro_fecha_hasta;
        $types .= "s";
    }
    
    $query .= " GROUP BY nd.id_docente, nd.id_materia, nd.id_periodo, s.codigo_seccion, c.nombre_carrera
                ORDER BY ultima_fecha DESC";
    
    if (!empty($params)) {
        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    } else {
        $result = $db->query($query);
        return $result;
    }
}

// Obtener parámetros de filtro
$filtro_profesor = $_GET['profesor'] ?? '';
$filtro_fecha_desde = $_GET['fecha_desde'] ?? '';
$filtro_fecha_hasta = $_GET['fecha_hasta'] ?? '';

// Obtener información del profesor seleccionado para mostrar
$profesor_seleccionado = null;
if (!empty($filtro_profesor)) {
    $profesor_seleccionado = obtenerProfesorPorId($filtro_profesor);
}

$profesores = obtenerProfesores();
$grupos_notas = obtenerGruposNotasDefinitivas($filtro_profesor, $filtro_fecha_desde, $filtro_fecha_hasta);

include("includes/head.php");
?>

<div class="container-fluid">
    <h2 class="my-4">Consultar Notas Definitivas por Profesor</h2>
    
    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
    
    <!-- Card de Filtros -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="form-row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="buscar_profesor" class="form-label">Buscar Profesor:</label>
                        <input type="text" 
                               class="form-control" 
                               id="buscar_profesor" 
                               placeholder="Escriba nombre o cédula..."
                               autocomplete="off">
                        <input type="hidden" name="profesor" id="profesor_id" value="<?= htmlspecialchars($filtro_profesor) ?>">
                        <div id="sugerencias_profesores" class="list-group mt-1" style="display: none; max-height: 200px; overflow-y: auto; position: absolute; z-index: 1000; width: 100%;"></div>
                        
                        <!-- Mostrar profesor seleccionado -->
                        <?php if ($profesor_seleccionado): ?>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-check"></i> 
                                    Profesor seleccionado: <strong><?= htmlspecialchars($profesor_seleccionado['nombre']) ?> (<?= htmlspecialchars($profesor_seleccionado['idusuario']) ?>)</strong>
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="limpiarProfesor()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_desde" class="form-label">Fecha Desde:</label>
                        <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                               value="<?= htmlspecialchars($filtro_fecha_desde) ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="fecha_hasta" class="form-label">Fecha Hasta:</label>
                        <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                               value="<?= htmlspecialchars($filtro_fecha_hasta) ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Aplicar
                            </button>
                            <a href="notas_pasadas.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Mostrar filtros activos -->
            <?php if (!empty($filtro_profesor) || !empty($filtro_fecha_desde) || !empty($filtro_fecha_hasta)): ?>
                <div class="mt-3">
                    <small class="text-muted">
                        <strong>Filtros aplicados:</strong>
                        <?php 
                        $filtros_activos = array();
                        
                        if (!empty($filtro_profesor) && $profesor_seleccionado) {
                            $filtros_activos[] = "Profesor: " . htmlspecialchars($profesor_seleccionado['nombre']) . " (" . htmlspecialchars($profesor_seleccionado['idusuario']) . ")";
                        }
                        
                        if (!empty($filtro_fecha_desde)) {
                            $filtros_activos[] = "Desde: " . date('d/m/Y', strtotime($filtro_fecha_desde));
                        }
                        
                        if (!empty($filtro_fecha_hasta)) {
                            $filtros_activos[] = "Hasta: " . date('d/m/Y', strtotime($filtro_fecha_hasta));
                        }
                        
                        echo implode(' | ', $filtros_activos);
                        ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Notas Definitivas por Docente</h5>
            <span class="badge badge-light badge-pill">
                Total: <?= $grupos_notas->num_rows ?> grupo(s)
            </span>
        </div>
        <div class="card-body">
            <?php if ($grupos_notas->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Docente</th>
                                <th>Cédula</th>
                                <th>Materia</th>
                                <th>Periodo</th>
                                <th>Sección</th>
                                <th>Carrera</th>
                                <th># Notas</th>
                                <th>Última Actualización</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($grupo = $grupos_notas->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nombre_docente']) ?></td>
                                    <td><?= htmlspecialchars($grupo['cedula_docente']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_materia']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_periodo']) ?></td>
                                    <td><?= htmlspecialchars($grupo['codigo_seccion']) ?></td>
                                    <td><?= htmlspecialchars($grupo['nombre_carrera']) ?></td>
                                    <td>
                                        <span class="badge badge-info badge-pill">
                                            <?= $grupo['total_notas'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($grupo['ultima_fecha'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info btn-detalles" 
                                                data-toggle="modal" data-target="#modalDetalles"
                                                data-docente-id="<?= $grupo['id_docente'] ?>"
                                                data-materia-id="<?= $grupo['id_materia'] ?>"
                                                data-periodo-id="<?= $grupo['id_periodo'] ?>"
                                                data-docente="<?= htmlspecialchars($grupo['nombre_docente']) ?>"
                                                data-materia="<?= htmlspecialchars($grupo['nombre_materia']) ?>"
                                                data-periodo="<?= htmlspecialchars($grupo['nombre_periodo']) ?>"
                                                data-seccion="<?= htmlspecialchars($grupo['codigo_seccion']) ?>"
                                                data-carrera="<?= htmlspecialchars($grupo['nombre_carrera']) ?>">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </button>
                                        
                                        <!-- Botón para generar PDF -->
                                        <button type="button" class="btn btn-sm btn-danger btn-pdf" 
                                                data-docente-id="<?= $grupo['id_docente'] ?>"
                                                data-materia-id="<?= $grupo['id_materia'] ?>"
                                                data-periodo-id="<?= $grupo['id_periodo'] ?>"
                                                data-docente="<?= htmlspecialchars($grupo['nombre_docente']) ?>"
                                                data-materia="<?= htmlspecialchars($grupo['nombre_materia']) ?>"
                                                data-periodo="<?= htmlspecialchars($grupo['nombre_periodo']) ?>"
                                                data-seccion="<?= htmlspecialchars($grupo['codigo_seccion']) ?>"
                                                data-carrera="<?= htmlspecialchars($grupo['nombre_carrera']) ?>">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center py-4">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>
                        <?php if (!empty($filtro_profesor) || !empty($filtro_fecha_desde) || !empty($filtro_fecha_hasta)): ?>
                            No se encontraron resultados con los filtros aplicados.
                        <?php else: ?>
                            No hay notas definitivas registradas en el sistema.
                        <?php endif; ?>
                    </h5>
                    <?php if (!empty($filtro_profesor) || !empty($filtro_fecha_desde) || !empty($filtro_fecha_hasta)): ?>
                        <a href="notas_pasadas.php" class="btn btn-primary mt-2">
                            <i class="fas fa-times"></i> Limpiar Filtros
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para ver detalles de notas -->
<div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetallesTitle">
                    Notas Definitivas - <span id="tituloGrupo"></span>
                </h5>
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
                            <a href="#soporte" class="list-group-item list-group-item-action" data-toggle="tab">
                                <i class="fas fa-paperclip"></i> Soporte
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
                            <div class="tab-pane fade" id="soporte">
                                <div class="text-center">
                                    <div class="spinner-border text-primary"></div>
                                    <p>Cargando soporte...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Búsqueda de profesores con autocompletado
    $('#buscar_profesor').on('input', function() {
        const termino = $(this).val().trim();
        
        if (termino.length < 2) {
            $('#sugerencias_profesores').hide().empty();
            return;
        }
        
        $.ajax({
            url: 'ajax_buscar_profesores.php',
            type: 'GET',
            data: { termino: termino },
            success: function(data) {
                const sugerencias = $('#sugerencias_profesores');
                sugerencias.empty();
                
                if (data.length > 0) {
                    data.forEach(function(profesor) {
                        const item = $('<a href="#" class="list-group-item list-group-item-action"></a>');
                        item.html(`
                            <div class="d-flex justify-content-between">
                                <strong>${profesor.nombre}</strong>
                                <small class="text-muted">${profesor.idusuario}</small>
                            </div>
                        `);
                        item.click(function(e) {
                            e.preventDefault();
                            $('#buscar_profesor').val(profesor.nombre);
                            $('#profesor_id').val(profesor.id);
                            sugerencias.hide();
                            
                            // Mostrar información del profesor seleccionado
                            $('<div class="mt-2">' +
                                '<small class="text-success">' +
                                '<i class="fas fa-check"></i> ' +
                                'Profesor seleccionado: <strong>' + profesor.nombre + ' (' + profesor.idusuario + ')</strong>' +
                                '<button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="limpiarProfesor()">' +
                                '<i class="fas fa-times"></i>' +
                                '</button>' +
                                '</small>' +
                                '</div>').insertAfter('#buscar_profesor');
                        });
                        sugerencias.append(item);
                    });
                    sugerencias.show();
                } else {
                    sugerencias.hide();
                }
            },
            error: function() {
                $('#sugerencias_profesores').hide().empty();
            }
        });
    });
    
    // Ocultar sugerencias al hacer clic fuera
    $(document).click(function(e) {
        if (!$(e.target).closest('#buscar_profesor, #sugerencias_profesores').length) {
            $('#sugerencias_profesores').hide();
        }
    });
    
    // Limpiar campo de búsqueda si está vacío
    $('#buscar_profesor').on('blur', function() {
        if ($(this).val().trim() === '' && $('#profesor_id').val() === '') {
            $(this).val('');
        }
    });

    // Cargar detalles del grupo via AJAX
    $('.btn-detalles').click(function() {
        const docenteId = $(this).data('docente-id');
        const materiaId = $(this).data('materia-id');
        const periodoId = $(this).data('periodo-id');
        const docente = $(this).data('docente');
        const materia = $(this).data('materia');
        const periodo = $(this).data('periodo');
        const seccion = $(this).data('seccion');
        const carrera = $(this).data('carrera');
        
        // Actualizar título del modal
        $('#tituloGrupo').text(`${docente} - ${materia} - ${periodo}`);
        
        // Cargar lista de estudiantes
        $.ajax({
            url: 'ajax_detalles_notas_definitivas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'lista-estudiantes'
            },
            success: function(data) {
                $('#lista-estudiantes').html(data);
            }
        });
        
        // Cargar resumen
        $.ajax({
            url: 'ajax_detalles_notas_definitivas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'resumen'
            },
            success: function(data) {
                $('#resumen').html(data);
            }
        });
        
        // Cargar soporte
        $.ajax({
            url: 'ajax_detalles_notas_definitivas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                seccion: 'soporte'
            },
            success: function(data) {
                $('#soporte').html(data);
            }
        });
    });
    
    // Manejar clic en botón PDF
    $('.btn-pdf').click(function() {
        const docenteId = $(this).data('docente-id');
        const materiaId = $(this).data('materia-id');
        const periodoId = $(this).data('periodo-id');
        const docente = $(this).data('docente');
        const materia = $(this).data('materia');
        const periodo = $(this).data('periodo');
        const seccion = $(this).data('seccion');
        const carrera = $(this).data('carrera');
        
        // Mostrar loading
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Generando...');
        $btn.prop('disabled', true);
        
        // Cargar datos para el PDF
        $.ajax({
            url: 'ajax_detalles_notas_definitivas.php',
            type: 'POST',
            data: { 
                docente_id: docenteId, 
                materia_id: materiaId, 
                periodo_id: periodoId,
                accion: 'pdf'
            },
            success: function(data) {
                // Restaurar botón
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                
                // Generar PDF
                generarPDF(data, docente, materia, periodo, seccion, carrera);
            },
            error: function(xhr, status, error) {
                // Restaurar botón
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
                console.error('Error:', error);
                alert('Error al generar el PDF: ' + error);
            }
        });
    });

    // Validación de fechas: fecha_hasta no puede ser menor que fecha_desde
    $('#fecha_desde, #fecha_hasta').change(function() {
        const fechaDesde = $('#fecha_desde').val();
        const fechaHasta = $('#fecha_hasta').val();
        
        if (fechaDesde && fechaHasta && fechaHasta < fechaDesde) {
            alert('La fecha "Hasta" no puede ser menor que la fecha "Desde"');
            $('#fecha_hasta').val('');
        }
    });
});

// Función para generar PDF - USANDO LA FUNCIÓN DEL MEMBRETE DE functions.php
function generarPDF(contenido, docente, materia, periodo, seccion, carrera) {
    // Crear elemento temporal para el contenido del PDF
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = contenido;
    tempDiv.style.padding = '20px';
    tempDiv.style.fontFamily = 'Arial, sans-serif';
    tempDiv.style.width = '800px';
    tempDiv.style.margin = '0 auto';
    document.body.appendChild(tempDiv);
    
    // Generar nombre del archivo
    const filename = `notas_definitivas_${docente.replace(/[^a-zA-Z0-9]/g, '_')}_${materia.replace(/[^a-zA-Z0-9]/g, '_')}.pdf`;
    
    // Configuración de jsPDF
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const margin = 10;
    const pageWidth = doc.internal.pageSize.getWidth();
    
    // Usar la función del membrete desde PHP
    <?php echo generarMembreteJS(); ?>
    
    // Llamar a la función para agregar el membrete
    agregarMembretePDF(doc, pageWidth, margin).then(startY => {
        // Capturar el contenido HTML y agregarlo al PDF
        html2canvas(tempDiv, {
            scale: 2,
            useCORS: true,
            logging: false,
            width: tempDiv.scrollWidth,
            height: tempDiv.scrollHeight,
            windowWidth: tempDiv.scrollWidth,
            windowHeight: tempDiv.scrollHeight
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpeg', 1.0);
            const imgWidth = pageWidth - (margin * 2);
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            // Agregar contenido al PDF (empezando después del membrete)
            doc.addImage(imgData, 'JPEG', margin, startY, imgWidth, imgHeight);
            
            // Guardar el PDF
            doc.save(filename);
            
            // Limpiar elemento temporal
            document.body.removeChild(tempDiv);
            
        }).catch(error => {
            console.error('Error al generar PDF:', error);
            alert('Error al generar el PDF: ' + error.message);
            document.body.removeChild(tempDiv);
        });
    }).catch(error => {
        console.error('Error al cargar el membrete:', error);
        alert('Error al generar el membrete del PDF');
        document.body.removeChild(tempDiv);
    });
}

// Función para limpiar la selección del profesor
function limpiarProfesor() {
    $('#buscar_profesor').val('');
    $('#profesor_id').val('');
    $('.mt-2').filter(function() {
        return $(this).find('.text-success').length > 0;
    }).remove();
}
</script>

<?php include("includes/footer.php"); ?>