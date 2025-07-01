<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');


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
                        <button class="btn btn-success btn-sm" onclick="abrirModalNuevoEstudiante()">
    <i class="fas fa-plus-circle me-1"></i> Nuevo Estudiante
</button>
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
        <th>Cédula</th>  <!-- Cambiado de "Código" a "Cédula" -->
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
            <td><?php echo htmlspecialchars($estudiante['cedula'] ?? ''); ?></td>  <!-- Cambiado a cedula -->
                <td><?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($estudiante['carrera'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($estudiante['genero'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($estudiante['num_telf'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($estudiante['correo'] ?? ''); ?></td>
                <td><?php echo !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : ''; ?></td>
                <td><?php echo mostrarEstadoEstudiante($estudiante['status'] ?? 0); ?></td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-info btn-details btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#detalleModal"
                            data-id="<?php echo $estudiante['id']; ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning btn-sm btn-edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editarEstudianteModal"
                            data-id="<?php echo $estudiante['id']; ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal para Editar Estudiante -->
<div class="modal fade" id="editarEstudianteModal" tabindex="-1" aria-labelledby="editarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editarEstudianteModalLabel"><i class="fas fa-user-edit me-2"></i>Editar Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agregarEstudianteModalLabel"><i class="fas fa-user-plus me-2"></i>Agregar Nuevo Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregarEstudiante" method="post" action="procesar_estudiante.php">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cod_estudiante" class="form-label">Código del Estudiante</label>
                                <input type="text" class="form-control" id="cod_estudiante" name="cod_estudiante" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="carrera" class="form-label">Programa</label>
                                <select  class="custom-select d-block w-100" id="carrera" name="carrera" required>
    <option value="">Seleccione un Programa</option>
    <?php foreach ($carreras as $carrera): ?>
        <option value="<?= $carrera['id_carrera'] ?>">
            <?= htmlspecialchars($carrera['nombre_carrera']) ?>
        </option>
    <?php endforeach; ?>
</select>
                            </div>
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <select  class="custom-select d-block w-100" id="genero" name="genero" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="num_telf" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="num_telf" name="num_telf">
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado</label>
                                <select  class="custom-select d-block w-100" id="status" name="status" required>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formAgregarEstudiante" class="btn btn-success">Guardar Estudiante</button>
            </div>
        </div>
    </div>
</div>







<!-- JavaScript para manejar el modal -->
<script>
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
        
        // Mostrar el modal (en caso de que no se abra automáticamente)
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
    
    // Limpiar modal al cerrar
    document.getElementById('detalleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('detalleEstudianteContent').innerHTML = '';
    });
});



// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});


 // Manejar el envío del formulario de agregar estudiante
 const formAgregarEstudiante = document.getElementById('formAgregarEstudiante');
    if (formAgregarEstudiante) {
        formAgregarEstudiante.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('agregarEstudianteModal'));
                    modal.hide();
                    // Recargar la tabla o agregar el nuevo estudiante dinámicamente
                    location.reload(); // Opción simple
                } else {
                    // Mostrar mensaje de error
                    alert(data.message || 'Error al agregar el estudiante');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        });
    }
;

// Función para cargar carreras via AJAX
async function cargarCarreras() {
    const selectCarrera = document.getElementById('carrera');
    
    try {
        // Mostrar carga
        selectCarrera.disabled = true;
        const loadingOption = new Option('Cargando carreras...', '');
        loadingOption.disabled = true;
        selectCarrera.add(loadingOption);
        
        // Hacer la petición
        const response = await fetch('api_carreras.php');
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Limpiar select
        selectCarrera.innerHTML = '';
        selectCarrera.add(new Option('Seleccione una carrera', ''));
        
        // Verificar respuesta
        console.log('Datos recibidos:', data); // Ver en consola del navegador
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (!Array.isArray(data)) {
            throw new Error('La respuesta no es un array');
        }
        
        // Llenar opciones
        data.forEach(carrera => {
            if (!carrera.id_carrera || !carrera.nombre_carrera) {
                console.warn('Carrera con formato incorrecto:', carrera);
                return;
            }
            
            selectCarrera.add(new Option(
                carrera.nombre_carrera,
                carrera.id_carrera
            ));
        });
        
    } catch (error) {
        console.error('Error al cargar carreras:', error);
        
        // Limpiar y mostrar error
        selectCarrera.innerHTML = '';
        const errorOption = new Option(`Error: ${error.message}`, '');
        errorOption.disabled = true;
        selectCarrera.add(errorOption);
        
    } finally {
        selectCarrera.disabled = false;
    }
}

// Modifica la función abrirModalNuevoEstudiante
function abrirModalNuevoEstudiante() {
    var modal = new bootstrap.Modal(document.getElementById('agregarEstudianteModal'));
    modal.show();
    
    // Resetear el formulario al abrir
    document.getElementById('formAgregarEstudiante').reset();
    
    // Cargar las carreras cuando se abre el modal
    cargarCarreras();
}

// Actualiza el evento DOMContentLoaded para incluir la inicialización del modal
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    $('#tablaEstudiantes').DataTable();
    
    // Configurar el evento para cuando se muestre el modal
    const agregarModal = document.getElementById('agregarEstudianteModal');
    if (agregarModal) {
        agregarModal.addEventListener('show.bs.modal', function() {
            // Opcional: puedes cargar las carreras aquí también
            // cargarCarreras();
        });
    }
    
    // Resto de tu código existente...
    // Delegación de eventos para botones dinámicos
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-details')) {
            const button = e.target.closest('.btn-details');
            const studentId = button.getAttribute('data-id');
            loadStudentDetails(studentId);
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
        
        // Mostrar el modal (en caso de que no se abra automáticamente)
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
    
    // Limpiar modal al cerrar
    document.getElementById('detalleModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('detalleEstudianteContent').innerHTML = '';
    });

    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejar el envío del formulario de agregar estudiante
    const formAgregarEstudiante = document.getElementById('formAgregarEstudiante');
    if (formAgregarEstudiante) {
        formAgregarEstudiante.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('agregarEstudianteModal'));
                    modal.hide();
                    // Recargar la tabla o agregar el nuevo estudiante dinámicamente
                    location.reload(); // Opción simple
                } else {
                    // Mostrar mensaje de error
                    alert(data.message || 'Error al agregar el estudiante');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        });
    }
});



// Agrega esto al evento DOMContentLoaded en el script de estudiantes.php

// Manejar clic en botón de editar
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const studentId = button.getAttribute('data-id');
        loadEditStudentForm(studentId);
    }
});

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
    
    // Mostrar el modal (en caso de que no se abra automáticamente)
    const modal = new bootstrap.Modal(document.getElementById('editarEstudianteModal'));
    modal.show();
    
    // Cargar contenido via AJAX
    fetch(`editar_estudiante_modal.php?id=${studentId}`)
        .then(response => response.text())
        .then(data => {
            modalContent.innerHTML = data;
            
            // Inicializar validaciones y eventos del formulario
            initEditForm();
        })
        .catch(error => {
            modalContent.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar el formulario: ${error.message}
                </div>
            `;
        });
}

// Función para inicializar el formulario de edición
function initEditForm() {
    // Calcular edad automáticamente cuando cambia la fecha de nacimiento
    $('#fecha_nac').change(function() {
        var fechaNac = new Date($(this).val());
        var hoy = new Date();
        var edad = hoy.getFullYear() - fechaNac.getFullYear();
        var m = hoy.getMonth() - fechaNac.getMonth();
        
        if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
            edad--;
        }
        
        $('#edad').val(edad);
    });
    
    // Validación del formulario
    $('#formEstudiante').submit(function(e) {
        let isValid = true;
        
        // Validar email
        const email = $('#correo').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Por favor ingrese un correo electrónico válido');
            isValid = false;
        }
        
        // Validar teléfono (al menos 10 dígitos)
        const telefono = $('#num_telf').val();
        if (telefono.length < 10) {
            alert('El teléfono debe tener al menos 10 dígitos');
            isValid = false;
        }
        
        // Validar que la fecha de ingreso no sea anterior a la de nacimiento
        const fechaNac = new Date($('#fecha_nac').val());
        const fechaIngreso = new Date($('#fecha_ingreso').val());
        if (fechaIngreso < fechaNac) {
            alert('La fecha de ingreso no puede ser anterior a la fecha de nacimiento');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        } else {
            // Enviar formulario via AJAX
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('actualizar_estudiante.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editarEstudianteModal'));
                    modal.hide();
                    // Recargar la tabla
                    location.reload();
                } else {
                    // Mostrar mensaje de error
                    alert(data.message || 'Error al actualizar el estudiante');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud');
            });
        }
    });
}

// Limpiar modal al cerrar
document.getElementById('editarEstudianteModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('editarEstudianteContent').innerHTML = '';
});






</script>

<?php include("includes/footer.php"); ?>