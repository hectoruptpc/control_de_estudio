<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');

// Verificar permiso de edición de estudiantes
$puedeEditar = isset($_SESSION['user']['editar_estudiante']) && $_SESSION['user']['editar_estudiante'] == 1;

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener lista de estudiantes
$estudiantes = obtenerEstudiantes();

// Verificar si hubo error
if (isset($estudiantes['error'])) {
    $error_message = $estudiantes['error'];
    unset($estudiantes);
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
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error_message; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                            <table id="tablaEstudiantes" class="table table-striped table-hover table-bordered" style="width:100%; min-width: 800px;">
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
                                                <div class="d-flex flex-wrap gap-1">
                                                    <button class="btn btn-info btn-details btn-sm" 
                                                        data-toggle="modal" 
                                                        data-target="#detalleModal"
                                                        data-id="<?php echo $estudiante['id']; ?>"
                                                        title="Ver detalles">
                                                        <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Ver</span>
                                                    </button>
                                                    <?php if (tienePermiso('editar_estudiante')): ?>
                                                        <button class="btn btn-warning btn-sm btn-edit" 
                                                            data-toggle="modal" 
                                                            data-target="#editarEstudianteModal"
                                                            data-id="<?php echo $estudiante['id']; ?>"
                                                            title="Editar">
                                                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Editar</span>
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
                        <p class="mt-3">Cargando información del estudiante...</p>
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

/* Estilos responsivos */
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
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .table td, .table th {
        font-size: 0.8rem;
        padding: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
    }
}

/* Scroll suave para la tabla */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Mejoras para botones en móviles */
@media (max-width: 767.98px) {
    .d-flex.flex-wrap {
        gap: 0.25rem !important;
    }
    
    .btn i {
        font-size: 0.7rem;
    }
}

/* Estilos para modales en móviles */
@media (max-width: 767.98px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-xl {
        max-width: calc(100% - 1rem);
        margin: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    $('#tablaEstudiantes').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
    
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
    
    function mostrarMensaje(titulo, mensaje, esExito = true) {
        const header = document.getElementById('resultadoModalHeader');
        const body = document.getElementById('resultadoModalBody');
        const label = document.getElementById('resultadoModalLabel');
        
        if (esExito) {
            header.className = 'modal-header bg-success text-white';
            body.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4>${titulo}</h4>
                    <p>${mensaje}</p>
                </div>
            `;
        } else {
            header.className = 'modal-header bg-danger text-white';
            body.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <h4>${titulo}</h4>
                    <p>${mensaje}</p>
                </div>
            `;
        }
        
        label.textContent = titulo;
        $('#resultadoModal').modal('show');
        
        if (esExito) {
            $('#resultadoModal').on('hidden.bs.modal', function() {
                location.reload();
            });
        }
    }
    
    function loadStudentDetails(studentId) {
        const modalContent = document.getElementById('detalleEstudianteContent');
        
        modalContent.innerHTML = `
            <div class="text-center py-5 my-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando información...</p>
            </div>
        `;
        
        $('#detalleModal').modal('show');
        
        fetch(`detalle_estudiante.php?id=${studentId}`)
            .then(response => response.text())
            .then(data => {
                modalContent.innerHTML = data;
            })
            .catch(error => {
                modalContent.innerHTML = `<div class="alert alert-danger">Error al cargar los detalles: ${error.message}</div>`;
            });
    }
    
    function loadEditStudentForm(studentId) {
        const modalContent = document.getElementById('editarEstudianteContent');
        
        modalContent.innerHTML = `
            <div class="text-center py-5 my-5">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3">Cargando formulario de edición...</p>
            </div>
        `;
        
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
            .catch(error => {
                modalContent.innerHTML = `<div class="alert alert-danger">Error al cargar el formulario: ${error.message}</div>`;
            });
    }
    
    function submitEditForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        
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
                mostrarMensaje('¡Éxito!', data.message || 'Estudiante actualizado exitosamente', true);
            } else {
                mostrarMensaje('Error', data.message || 'Error al actualizar el estudiante', false);
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            mostrarMensaje('Error', 'Error de conexión: ' + error.message, false);
            submitButton.innerHTML = originalButtonText;
            submitButton.disabled = false;
        });
    }
    
    $('#detalleModal').on('hidden.bs.modal', function() {
        document.getElementById('detalleEstudianteContent').innerHTML = '';
    });
    
    $('#editarEstudianteModal').on('hidden.bs.modal', function() {
        document.getElementById('editarEstudianteContent').innerHTML = '';
    });
    
    $('#agregarEstudianteModal').on('hidden.bs.modal', function() {
        const form = document.getElementById('formEstudianteModal');
        if (form) form.reset();
    });
    
    const formEstudiante = document.getElementById('formEstudianteModal');
    if (formEstudiante) {
        formEstudiante.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
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
                    mostrarMensaje('¡Éxito!', data.message || 'Estudiante registrado exitosamente', true);
                } else {
                    mostrarMensaje('Error', data.message || 'Error al guardar el estudiante', false);
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                mostrarMensaje('Error', 'Error de conexión: ' + error.message, false);
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });
        });
    }
});

function abrirModalNuevoEstudiante() {
    $('#agregarEstudianteModal').modal('show');
}
</script>

<?php include("includes/footer.php"); ?>