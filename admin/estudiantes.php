<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');

// Verificar permiso de edición de estudiantes
$puedeEditar = isset($_SESSION['user']['editar_estudiante']) && $_SESSION['user']['editar_estudiante'] == 1;

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener estudiantes (SOLO los que tienen estudiante = 1)
$query = "SELECT 
    id,
    idusuario,
    nombre,
    username,
    email,
    tlf,
    cel,
    direccion,
    ciudad,
    estado,
    municipio,
    parroquia,
    fecha_ingreso,
    fecha_nac,
    status,
    carrera,
    genero,
    embarazada,
    edo_civil,
    num_telf_opc,
    foto_perfil
FROM users 
WHERE estudiante = 1
ORDER BY nombre ASC";

$result = $db->query($query);
$estudiantes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estudiantes[] = $row;
    }
}

// Contar estudiantes por status y estadísticas adicionales
$totalEstudiantes = count($estudiantes);
$activos = 0;
$inactivos = 0;
$embarazadas = 0;
$menores = 0;
$estudiantesPorCarrera = [];

foreach ($estudiantes as $estudiante) {
    $status = $estudiante['status'] ?? 0;
    if ($status == 1) {
        $activos++;
    } else {
        $inactivos++;
    }

    // Contar mujeres embarazadas
    $esFemenino = isset($estudiante['genero']) && trim($estudiante['genero']) === 'Femenino';
    $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
    if ($esFemenino && $estaEmbarazada) {
        $embarazadas++;
    }

    // Contar menores de edad
    if (!empty($estudiante['fecha_nac'])) {
        try {
            $fechaNac = new DateTime($estudiante['fecha_nac']);
            $hoy = new DateTime();
            $edad = $fechaNac->diff($hoy)->y;
            if ($edad < 18) {
                $menores++;
            }
        } catch (Exception $e) {
            // Ignorar fechas inválidas
        }
    }

    // Contar por carrera
    $carrera = $estudiante['carrera'] ?? 'Sin Carrera';
    if (!isset($estudiantesPorCarrera[$carrera])) {
        $estudiantesPorCarrera[$carrera] = 0;
    }
    $estudiantesPorCarrera[$carrera]++;
}

include("includes/head.php");
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <div class="py-3 py-sm-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <h5 class="mb-2 mb-sm-0"><i class="fas fa-users me-2"></i>Listado de Estudiantes</h5>
                        <div>
                            <button id="toggleEstadisticas" class="btn btn-outline-light btn-sm mb-1 mb-sm-0 me-2" onclick="toggleEstadisticas()">
                                <i class="fas fa-chart-bar"></i> <span class="d-none d-sm-inline">Ocultar Estadísticas</span><span class="d-inline d-sm-none">Ocultar</span>
                            </button>
                            <?php if (tienePermiso('agregar_estudiante')): ?>
                                <button class="btn btn-success btn-sm mb-1 mb-sm-0" onclick="abrirModalNuevoEstudiante()">
                                    <i class="fas fa-plus-circle"></i> <span class="d-none d-sm-inline">Nuevo Estudiante</span><span class="d-inline d-sm-none">Nuevo</span>
                                </button>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Regresar</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-2 p-sm-3">
                        <!-- Conteos de estudiantes -->
                        <div id="estadisticas-row" class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $totalEstudiantes; ?></h4>
                                        <p class="card-text">Total de Estudiantes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-check fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $activos; ?></h4>
                                        <p class="card-text">Estudiantes Activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-secondary text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-times fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $inactivos; ?></h4>
                                        <p class="card-text">Estudiantes Inactivos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-info text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-baby-carriage fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $embarazadas; ?></h4>
                                        <p class="card-text">Mujeres Embarazadas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-warning text-dark h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-child fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $menores; ?></h4>
                                        <p class="card-text">Menores de Edad</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas por Carrera -->
                        <div id="estadisticas-carrera-row" class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-center mb-3">
                                    <i class="fas fa-graduation-cap me-2"></i>Estudiantes por Carrera
                                </h5>
                                <div class="row">
                                    <?php
                                    $colores = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                                    $colorIndex = 0;
                                    foreach ($estudiantesPorCarrera as $carrera => $cantidad):
                                        $color = $colores[$colorIndex % count($colores)];
                                        $colorIndex++;
                                    ?>
                                    <div class="col-md-4 col-lg-3 mb-3">
                                        <div class="card <?php echo $color; ?> text-white h-100 shadow-sm">
                                            <div class="card-body text-center">
                                                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                                <h5 class="card-title"><?php echo $cantidad; ?></h5>
                                                <p class="card-text small"><?php echo htmlspecialchars($carrera); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Buscador por Cédula -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-search me-2"></i>Buscador por Cédula
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mx-auto">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-primary text-white">
                                                        <i class="fas fa-id-card"></i>
                                                    </span>
                                                    <input type="text" 
                                                           id="buscadorCedula" 
                                                           class="form-control form-control-lg" 
                                                           placeholder="Escriba la cédula: V12345678 o E12345678"
                                                           autocomplete="off">
                                                    <button type="button" id="limpiarBusqueda" class="btn btn-secondary">
                                                        <i class="fas fa-times"></i> Limpiar
                                                    </button>
                                                </div>
                                                <small class="text-muted mt-2 d-block text-center">
                                                    <i class="fas fa-info-circle"></i> La búsqueda se actualiza automáticamente mientras escribe
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tablaEstudiantes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Carrera</th>
                                        <th>Género</th>
                                        <th>Edad</th>
                                        <th>Status</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    <?php if (count($estudiantes) > 0): ?>
                                        <?php foreach ($estudiantes as $estudiante): ?>
                                            <?php
                                            // Calcular edad
                                            $edad = '';
                                            if (!empty($estudiante['fecha_nac'])) {
                                                try {
                                                    $fechaNac = new DateTime($estudiante['fecha_nac']);
                                                    $hoy = new DateTime();
                                                    $edad = $fechaNac->diff($hoy)->y;
                                                } catch (Exception $e) {
                                                    $edad = '';
                                                }
                                            }
                                            
                                            $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
                                            $esFemenino = isset($estudiante['genero']) && trim($estudiante['genero']) === 'Femenino';
                                            $cedula = $estudiante['idusuario'] ?? '';
                                            ?>
                                            <tr data-cedula="<?php echo htmlspecialchars(strtoupper($cedula)); ?>">
                                                <td><?php echo htmlspecialchars($cedula); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['username'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['tlf'] ?? $estudiante['cel'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['carrera'] ?? ''); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($estudiante['genero'] ?? ''); ?>
                                                    <?php if ($esFemenino && $estaEmbarazada): ?>
                                                        <span class="badge bg-info ms-1" title="Embarazada">🤰</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $edad; ?></td>
                                                <td>
                                                    <?php
                                                        $status = $estudiante['status'] ?? 0;
                                                        echo ($status == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
                                                    ?>
                                                 </td>
                                                <td><?php echo !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : ''; ?></td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button class="btn btn-info btn-details btn-sm" 
                                                            data-toggle="modal" 
                                                            data-target="#detalleModal"
                                                            data-id="<?php echo $estudiante['id']; ?>"
                                                            title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($puedeEditar): ?>
                                                            <button class="btn btn-warning btn-sm btn-edit" 
                                                                data-toggle="modal" 
                                                                data-target="#editarEstudianteModal"
                                                                data-id="<?php echo $estudiante['id']; ?>"
                                                                title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                  </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="text-center">No hay estudiantes registrados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles del estudiante -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detalleModalLabel">Detalles del Estudiante</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleEstudianteContent">
                <div class="text-center my-5 py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando información del estudiante...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Estudiante -->
<div class="modal fade" id="editarEstudianteModal" tabindex="-1" aria-labelledby="editarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editarEstudianteModalLabel"><i class="fas fa-user-edit me-2"></i> Editar Estudiante</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editarEstudianteContent">
                <div class="text-center my-5 py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando formulario de edición...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar Estudiante -->
<div class="modal fade" id="agregarEstudianteModal" tabindex="-1" aria-labelledby="agregarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agregarEstudianteModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Agregar Nuevo Estudiante
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                $tiposCedula = obtenerTiposCedula($db);
                $estadosCiviles = obtenerEstadosCiviless($db);
                $tiposVivienda = obtenerTiposVivienda($db);
                $tenenciasVivienda = obtenerTenenciaViviendas($db);
                $opcionesStatus = obtenerOpcionesStatus($db);
                $carreras = obtenerTodasLasCarreras();
                $ingresos = obtenerIngresos($db);
                $esModal = true;
                ?>
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                        <form id="formEstudianteModal" method="post" enctype="multipart/form-data">
                            <?php 
                            $esModal = true;
                            include('_formulario_estudiante.php'); 
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Mensajes de Resultado -->
<div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="resultadoModalHeader">
                <h5 class="modal-title" id="resultadoModalLabel">Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultadoModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<style>
.close {
    font-size: 1.5rem;
    font-weight: bold;
    opacity: 0.8;
    padding: 0.5rem;
    line-height: 1;
    background: transparent;
    border: none;
    cursor: pointer;
}

.close:hover {
    opacity: 1;
}

.modal-header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-content {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

@media (max-width: 767.98px) {
    .card-header h5 {
        font-size: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .table td, .table th {
        font-size: 0.75rem;
        padding: 0.4rem;
        vertical-align: middle;
    }
    
    .modal-body {
        padding: 0.75rem;
    }
    
    .d-flex.flex-wrap {
        gap: 0.25rem !important;
    }
}
</style>

<script>
// Función para el buscador en tiempo real
document.getElementById('buscadorCedula').addEventListener('keyup', function() {
    let filtro = this.value.toUpperCase();
    let filas = document.querySelectorAll('#tablaBody tr');
    let encontrados = 0;
    
    filas.forEach(fila => {
        let cedula = fila.getAttribute('data-cedula') || '';
        if(cedula.includes(filtro)) {
            fila.style.display = '';
            encontrados++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Mostrar mensaje si no hay resultados
    let mensajeDiv = document.getElementById('mensajeBusqueda');
    if(!mensajeDiv) {
        mensajeDiv = document.createElement('div');
        mensajeDiv.id = 'mensajeBusqueda';
        mensajeDiv.className = 'alert alert-info mt-3';
        document.querySelector('.table-responsive').appendChild(mensajeDiv);
    }
    
    if(filtro === '') {
        mensajeDiv.innerHTML = `<i class="fas fa-users"></i> Mostrando ${filas.length} estudiantes en total`;
        mensajeDiv.className = 'alert alert-info mt-3';
    } else if(encontrados === 0) {
        mensajeDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> No se encontraron estudiantes con cédula que contenga "<strong>${filtro}</strong>"`;
        mensajeDiv.className = 'alert alert-warning mt-3';
    } else {
        mensajeDiv.innerHTML = `<i class="fas fa-check-circle"></i> Se encontraron ${encontrados} estudiante(s) con cédula que contiene "<strong>${filtro}</strong>"`;
        mensajeDiv.className = 'alert alert-success mt-3';
    }
});

// Botón limpiar búsqueda
document.getElementById('limpiarBusqueda').addEventListener('click', function() {
    document.getElementById('buscadorCedula').value = '';
    let filas = document.querySelectorAll('#tablaBody tr');
    filas.forEach(fila => fila.style.display = '');
    
    let mensajeDiv = document.getElementById('mensajeBusqueda');
    if(mensajeDiv) {
        mensajeDiv.innerHTML = `<i class="fas fa-users"></i> Mostrando ${filas.length} estudiantes en total`;
        mensajeDiv.className = 'alert alert-info mt-3';
    }
    document.getElementById('buscadorCedula').focus();
});

// Función para toggle estadísticas
window.toggleEstadisticas = function() {
    const row = document.getElementById('estadisticas-row');
    const carreraRow = document.getElementById('estadisticas-carrera-row');
    const button = document.getElementById('toggleEstadisticas');
    const icon = button.querySelector('i');
    const span = button.querySelector('span.d-none.d-sm-inline');
    const spanMobile = button.querySelector('span.d-inline.d-sm-none');
    
    if (row.style.display === 'none') {
        row.style.display = 'flex';
        carreraRow.style.display = 'block';
        span.textContent = 'Ocultar Estadísticas';
        spanMobile.textContent = 'Ocultar';
        icon.className = 'fas fa-chart-bar';
    } else {
        row.style.display = 'none';
        carreraRow.style.display = 'none';
        span.textContent = 'Mostrar Estadísticas';
        spanMobile.textContent = 'Mostrar';
        icon.className = 'fas fa-eye';
    }
};

// Modal handlers
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-details')) {
        const button = e.target.closest('.btn-details');
        const studentId = button.getAttribute('data-id');
        loadStudentDetails(studentId);
    }
    
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const studentId = button.getAttribute('data-id');
        loadEditStudentForm(studentId);
    }
});

function loadStudentDetails(studentId) {
    const modalContent = document.getElementById('detalleEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#detalleModal').modal('show');
    
    fetch(`detalle_estudiante.php?id=${studentId}`)
        .then(response => response.text())
        .then(data => modalContent.innerHTML = data)
        .catch(error => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`);
}

function loadEditStudentForm(studentId) {
    const modalContent = document.getElementById('editarEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#editarEstudianteModal').modal('show');
    
    fetch(`editar_estudiante_modal.php?id=${studentId}`)
        .then(response => response.text())
        .then(data => {
            modalContent.innerHTML = data;
            const editForm = document.getElementById('formEditarEstudiante');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitEditForm(this);
                });
            }
        })
        .catch(error => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`);
}

function submitEditForm(form) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitButton.disabled = true;
    
    fetch('actualizar_estudiante.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editarEstudianteModal').modal('hide');
            mostrarMensaje('Éxito', data.message || 'Estudiante actualizado', true, true);
        } else {
            mostrarMensaje('Error', data.message || 'Error al actualizar', false);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        mostrarMensaje('Error', 'Error de conexión', false);
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

function mostrarMensaje(titulo, mensaje, esExito = true, recargar = false) {
    const header = document.getElementById('resultadoModalHeader');
    const body = document.getElementById('resultadoModalBody');
    const label = document.getElementById('resultadoModalLabel');
    
    header.className = `modal-header bg-${esExito ? 'success' : 'danger'} text-white`;
    body.innerHTML = `<div class="text-center"><i class="fas fa-${esExito ? 'check-circle' : 'exclamation-circle'} fa-3x mb-3"></i><h4>${titulo}</h4><p>${mensaje}</p></div>`;
    label.textContent = titulo;
    $('#resultadoModal').modal('show');
    
    if (recargar) {
        $('#resultadoModal').on('hidden.bs.modal', () => location.reload());
    }
}

// Limpiar modales al cerrar
$('#detalleModal, #editarEstudianteModal, #agregarEstudianteModal').on('hidden.bs.modal', function() {
    const contentId = this.id === 'detalleModal' ? 'detalleEstudianteContent' : 
                     (this.id === 'editarEstudianteModal' ? 'editarEstudianteContent' : null);
    if (contentId && document.getElementById(contentId)) {
        if (this.id !== 'agregarEstudianteModal') document.getElementById(contentId).innerHTML = '';
    }
});

// Formulario nuevo estudiante
const formEstudiante = document.getElementById('formEstudianteModal');
if (formEstudiante) {
    formEstudiante.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        submitButton.disabled = true;
        
        fetch('procesar_estudiante.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#agregarEstudianteModal').modal('hide');
                mostrarMensaje('Éxito', data.message || 'Estudiante registrado', true, true);
            } else {
                mostrarMensaje('Error', data.message || 'Error al guardar', false);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            mostrarMensaje('Error', 'Error de conexión', false);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
}

function abrirModalNuevoEstudiante() {
    $('#agregarEstudianteModal').modal('show');
}
</script>

<?php include("includes/footer.php"); ?>