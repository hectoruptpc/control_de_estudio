<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');

// Verificar permiso de edición de estudiantes
$puedeEditar = isset($_SESSION['user']['editar_estudiante']) && $_SESSION['user']['editar_estudiante'] == 1;

// Obtener lista de estudiantes
$estudiantes = obtenerEstudiantes();

// Verificar si hubo error
if (isset($estudiantes['error'])) {
    $error_message = $estudiantes['error'];
    unset($estudiantes);
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Listado de Estudiantes</h5>
                        <div>
                            <?php if ($puedeEditar): ?>
                                <button class="btn btn-success btn-sm" onclick="abrirModalNuevoEstudiante()">
                                    <i class="fas fa-plus-circle me-1"></i> Nuevo Estudiante
                                </button>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-outline-light btn-sm ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error_message; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table id="tablaEstudiantes" class="table table-striped table-hover table-bordered" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Programa</th>
                                        <th>Género</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Ingreso</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($estudiantes) && is_array($estudiantes)): ?>
                                        <?php foreach ($estudiantes as $estudiante): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($estudiante['cedula'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['carrera'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['genero'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['num_telf'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['correo'] ?? ''); ?></td>
                                            <td><?php echo !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : ''; ?></td>
                                            <td>
                                                <?php
                                                    $status = $estudiante['status'] ?? 0;
                                                    echo ($status == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-info btn-details btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#detalleModal"
                                                        data-id="<?php echo $estudiante['id']; ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($puedeEditar): ?>
                                                        <button class="btn btn-warning btn-sm btn-edit" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editarEstudianteModal"
                                                            data-id="<?php echo $estudiante['id']; ?>">
                                                            <i class="fas fa-edit"></i> Editar
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del estudiante -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detalleModalLabel">Detalles del Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detalleEstudianteContent">
                    <!-- Contenido cargado dinámicamente -->
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editarEstudianteModalLabel"><i class="fas fa-user-edit me-2"></i> Editar Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="editarEstudianteContent">
                    <!-- Contenido cargado dinámicamente -->
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

    <!-- Modal para Agregar Estudiante -->
    <div class="modal fade" id="agregarEstudianteModal" tabindex="-1" aria-labelledby="agregarEstudianteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="agregarEstudianteModalLabel">
                        <i class="fas fa-user-plus me-2"></i> Agregar Nuevo Estudiante
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php 
                    // Obtener los datos necesarios para los select
                    $tiposCedula = obtenerTiposCedula($db);
                    $estadosCiviles = obtenerEstadosCiviless($db);
                    $tiposVivienda = obtenerTiposVivienda($db);
                    $tenenciasVivienda = obtenerTenenciaViviendas($db);
                    $opcionesStatus = obtenerOpcionesStatus($db);
                    $carreras = obtenerTodasLasCarreras();
                    $ingresos = obtenerIngresos($db);
                    $esModal = true;
                    ?>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <div class="tab-content" id="myTabContent">
                        <!-- Formulario individual -->
                        <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                            <?php include('_formulario_estudiante.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript para manejar el modal y DataTable
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    $('#tablaEstudiantes').DataTable();
    
    // Delegación de eventos para botones dinámicos
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
    
    // Función para cargar detalles del estudiante
    function loadStudentDetails(studentId) {
        const modalContent = document.getElementById('detalleEstudianteContent');
        
        // Mostrar spinner
        modalContent.innerHTML = `
            <div class="text-center py-5 my-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando información...</p>
            </div>
        `;
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('detalleModal'));
        modal.show();
        
        // Cargar contenido via AJAX
        fetch(`detalle_estudiante.php?id=${studentId}`)
            .then(response => response.text())
            .then(data => {
                modalContent.innerHTML = data;
            })
            .catch(error => {
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar los detalles: ${error.message}
                    </div>
                `;
            });
    }
    
    // Función para cargar el formulario de edición
    function loadEditStudentForm(studentId) {
        const modalContent = document.getElementById('editarEstudianteContent');
        
        // Mostrar spinner
        modalContent.innerHTML = `
            <div class="text-center py-5 my-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando formulario de edición...</p>
            </div>
        `;
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('editarEstudianteModal'));
        modal.show();
        
        // Cargar contenido via AJAX
        fetch(`editar_estudiante_modal.php?id=${studentId}`)
            .then(response => response.text())
            .then(data => {
                modalContent.innerHTML = data;
            })
            .catch(error => {
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar el formulario: ${error.message}
                    </div>
                `;
            });
    }
    
    // Limpiar modales al cerrar
    document.getElementById('detalleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('detalleEstudianteContent').innerHTML = '';
    });
    
    document.getElementById('editarEstudianteModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('editarEstudianteContent').innerHTML = '';
    });

    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Función para abrir el modal de nuevo estudiante
function abrirModalNuevoEstudiante() {
    var modal = new bootstrap.Modal(document.getElementById('agregarEstudianteModal'));
    modal.show();
}
</script>

<?php include("includes/footer.php"); ?>