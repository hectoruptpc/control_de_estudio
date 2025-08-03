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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agregarEstudianteModalLabel"><i class="fas fa-user-plus me-2"></i>Agregar Nuevo Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php 
                // Obtener los datos necesarios para los select
                $tiposCedula = obtenerTiposCedula($db);
                $estadosCiviles = obtenerEstadosCiviless($db);
                $tiposVivienda = obtenerTiposVivienda($db);
                $tenenciasVivienda = obtenerTenenciaViviendas($db);
                $opcionesStatus = obtenerOpcionesStatus($db);
                $carreras = obtenerCarreras();
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
                        <form id="formEstudianteModal" method="post" action="procesar_estudiante.php">
                            <!-- Sección 1: Identificación -->
                            <h5 class="mb-3"><i class="fas fa-id-card me-2"></i> Identificación</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_modal" class="form-label required">Nombre Completo</label>
                                        <input type="text" class="form-control" id="nombre_modal" name="nombre" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cedula_completa_modal" class="form-label required">Cédula</label>
                                        <div class="input-group">
                                            <select class="form-select" id="tipo_cedula_modal" name="tipo_cedula" style="max-width: 80px;">
                                                <?php foreach ($tiposCedula as $tipo): ?>
                                                    <option value="<?php echo htmlspecialchars($tipo['tipo']); ?>">
                                                        <?php echo htmlspecialchars($tipo['tipo']); ?>-
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="text" class="form-control" id="numero_cedula_modal" name="numero_cedula" placeholder="Ej: 12345678" required>
                                            <input type="hidden" id="idusuario_modal" name="idusuario">
                                        </div>
                                        <small class="text-muted">Formato: V-12345678 o E-12345678</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 2: Datos Personales -->
                            <h5 class="mb-3"><i class="fas fa-user-tag me-2"></i> Datos Personales</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_nac_modal" class="form-label required">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fecha_nac_modal" name="fecha_nac" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label required">Género</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_m_modal" value="Masculino" required>
                                                <label class="form-check-label" for="genero_m_modal">Masculino</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_f_modal" value="Femenino">
                                                <label class="form-check-label" for="genero_f_modal">Femenino</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="genero" id="genero_o_modal" value="Otro">
                                                <label class="form-check-label" for="genero_o_modal">Otro</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edo_civil_modal" class="form-label required">Estado Civil</label>
                                        <select class="custom-select d-block w-100" id="edo_civil_modal" name="edo_civil" required>
                                            <option value="" selected disabled>Seleccione una opción</option>
                                            <?php foreach ($estadosCiviles as $id => $estadoCivil): ?>
                                                <option value="<?php echo htmlspecialchars($estadoCivil); ?>"><?php echo htmlspecialchars($estadoCivil); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="etnia_modal" class="form-label">Etnia</label>
                                        <input type="text" class="form-control" id="etnia_modal" name="etnia">
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 3: Formación Académica -->
                            <h5 class="mb-3"><i class="fas fa-graduation-cap me-2"></i> Formación Académica</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="carrera_modal" class="form-label required">Programa</label>
                                        <select class="custom-select d-block w-100" id="carrera_modal" name="carrera" required>
                                            <option value="" selected disabled>Seleccione un Programa</option>
                                            <?php foreach ($carreras as $carrera): ?>
                                                <?php if (!empty($carrera)): ?>
                                                    <option value="<?php echo htmlspecialchars($carrera); ?>">
                                                        <?php echo htmlspecialchars($carrera); ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Títulos Obtenidos -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="titulos_modal" class="form-label">Títulos Obtenidos</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="titulos_modal" name="titulos_main" placeholder="Títulos obtenidos">
                                            <button type="button" class="btn btn-outline-primary" id="addTitulo_modal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="titulosContainer_modal">
                                        <!-- Aquí se agregarán dinámicamente los campos de títulos -->
                                    </div>
                                </div>
                                
                                <!-- Instituciones -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="institutos_modal" class="form-label">Instituciones</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="institutos_modal" name="institutos_main" placeholder="Instituciones donde obtuvo los títulos">
                                            <button type="button" class="btn btn-outline-primary" id="addInstituto_modal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="institutosContainer_modal">
                                        <!-- Aquí se agregarán dinámicamente los campos de instituciones -->
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 4: Ubicación y Vivienda -->
                            <h5 class="mb-3"><i class="fas fa-home me-2"></i> Vivienda</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estado_modal" class="form-label required">Estado</label>
                                        <input type="text" class="form-control" id="estado_modal" name="estado" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="municipio_modal" class="form-label required">Municipio</label>
                                        <input type="text" class="form-control" id="municipio_modal" name="municipio" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="parroquia_modal" class="form-label">Parroquia</label>
                                        <input type="text" class="form-control" id="parroquia_modal" name="parroquia">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="direccion_modal" class="form-label required">Dirección</label>
                                        <textarea class="form-control" id="direccion_modal" name="direccion" rows="2" required></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="casaapto_modal" class="form-label">Tipo de Vivienda</label>
                                        <select class="custom-select d-block w-100" id="casaapto_modal" name="casaapto">
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                                                <option value="<?php echo htmlspecialchars($vivienda); ?>"><?php echo htmlspecialchars($vivienda); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="punto_referencia_modal" class="form-label">Punto de Referencia</label>
                                        <input type="text" class="form-control" id="punto_referencia_modal" name="punto_referencia">
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 5: Situación Familiar -->
                            <h5 class="mb-3"><i class="fas fa-users me-2"></i> Situación Familiar</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grupo_familiar_modal" class="form-label">Grupo Familiar</label>
                                        <input type="number" class="form-control" id="grupo_familiar_modal" name="grupo_familiar" min="1" placeholder="Número de personas">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="acargo_usted_modal" class="form-label">Personas a su cargo</label>
                                        <input type="number" class="form-control" id="acargo_usted_modal" name="acargo_usted" min="0">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fuente_ingresos_modal" class="form-label">Fuente de Ingresos</label>
                                        <input type="text" class="form-control" id="fuente_ingresos_modal" name="fuente_ingresos">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tenencia_vivienda_modal" class="form-label">Tenencia de Vivienda</label>
                                        <select class="custom-select d-block w-100" id="tenencia_vivienda_modal" name="tenencia_vivienda">
                                            <option value="">Seleccione...</option>
                                            <?php foreach ($tenenciasVivienda as $id => $tenencia): ?>
                                                <option value="<?php echo htmlspecialchars($tenencia); ?>"><?php echo htmlspecialchars($tenencia); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 6: Salud -->
                            <h5 class="mb-3"><i class="fas fa-heartbeat me-2"></i> Salud</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="enfermedad_modal" class="form-label">Enfermedades</label>
                                        <input type="text" class="form-control" id="enfermedad_modal" name="enfermedad" placeholder="Enfermedades conocidas">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discapacida_modal" class="form-label">Discapacidad</label>
                                        <input type="text" class="form-control" id="discapacida_modal" name="discapacida" placeholder="Tipo de discapacidad si aplica">
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 7: Contacto -->
                            <h5 class="mb-3"><i class="fas fa-address-book me-2"></i> Contacto</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tlf_modal" class="form-label required">Teléfono Principal</label>
                                        <input type="tel" class="form-control" id="tlf_modal" name="tlf" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="cel_modal" class="form-label">Teléfono Celular</label>
                                        <input type="tel" class="form-control" id="cel_modal" name="cel">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email_modal" class="form-label required">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email_modal" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="num_telf_opc_modal" class="form-label">Teléfono Opcional</label>
                                        <input type="tel" class="form-control" id="num_telf_opc_modal" name="num_telf_opc">
                                    </div>
                                </div>
                            </div>

                            <!-- Sección 8: Datos del Sistema -->
                            <h5 class="mb-3"><i class="fas fa-university me-2"></i> Datos del Sistema</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_ingreso_modal" class="form-label required">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso_modal" name="fecha_ingreso" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_modal" class="form-label required">Status</label>
                                        <select class="custom-select d-block w-100" id="status_modal" name="status" required>
                                            <option value="" selected disabled>Seleccione un status</option>
                                            <?php foreach ($opcionesStatus as $valor => $texto): ?>
                                                <option value="<?php echo htmlspecialchars($valor); ?>"><?php echo htmlspecialchars($texto); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                
                                <div>
                                    <button type="reset" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-eraser me-1"></i> Limpiar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Manejo del campo de cédula en el modal
document.addEventListener('DOMContentLoaded', function() {
    const tipoCedula = document.getElementById('tipo_cedula_modal');
    const numeroCedula = document.getElementById('numero_cedula_modal');
    const idUsuario = document.getElementById('idusuario_modal');
    
    function actualizarCedulaCompleta() {
        const numeroLimpio = numeroCedula.value.replace(/[^0-9]/g, '');
        numeroCedula.value = numeroLimpio;
        idUsuario.value = tipoCedula.value + '-' + numeroLimpio;
    }
    
    tipoCedula.addEventListener('change', actualizarCedulaCompleta);
    numeroCedula.addEventListener('input', actualizarCedulaCompleta);
    actualizarCedulaCompleta();
});

// Script para manejar la adición de campos en el modal
document.addEventListener('DOMContentLoaded', function() {
    // Función para añadir nuevos campos
    function addField(containerId, inputId, namePrefix, placeholder, buttonId) {
        const container = document.getElementById(containerId);
        const mainInput = document.getElementById(inputId);
        const value = mainInput.value.trim();
        
        if(value === '') return; // No añadir si está vacío
        
        const newField = document.createElement('div');
        newField.className = 'mb-3';
        newField.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="${namePrefix}[]" 
                       value="${value}" placeholder="${placeholder}" readonly>
                <button type="button" class="btn btn-outline-danger remove-field">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;
        container.appendChild(newField);
        
        // Vaciar el campo principal
        mainInput.value = '';
        mainInput.focus();
        
        // Añadir evento para eliminar campo
        newField.querySelector('.remove-field').addEventListener('click', function() {
            container.removeChild(newField);
        });
    }
    
    // Eventos para los botones de añadir en el modal
    document.getElementById('addTitulo_modal').addEventListener('click', function() {
        addField('titulosContainer_modal', 'titulos_modal', 'titulos', 'Título obtenido', 'titulos_modal');
    });
    
    document.getElementById('addInstituto_modal').addEventListener('click', function() {
        addField('institutosContainer_modal', 'institutos_modal', 'institutos', 'Institución', 'institutos_modal');
    });
    
    // Manejar el evento Enter en los campos principales del modal
    document.getElementById('titulos_modal').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addTitulo_modal').click();
        }
    });
    
    document.getElementById('institutos_modal').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addInstituto_modal').click();
        }
    });
});

// Manejar el envío del formulario del modal via AJAX
document.getElementById('formEstudianteModal').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Mostrar loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Procesando...';
    submitBtn.disabled = true;

    try {
        // Validación del cliente
        const requiredFields = form.querySelectorAll('[required]');
        let missingFields = [];
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                missingFields.push(field.name || field.id);
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (missingFields.length > 0) {
            throw new Error(`Complete los campos requeridos: ${missingFields.join(', ')}`);
        }

        // Validar cédula
        const cedula = document.getElementById('numero_cedula_modal').value;
        if (cedula.length < 6 || cedula.length > 8 || !/^\d+$/.test(cedula)) {
            throw new Error('La cédula debe tener entre 6 y 8 dígitos numéricos');
        }

        // Validar email
        const email = document.getElementById('email_modal').value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            throw new Error('Ingrese un correo electrónico válido');
        }

        // Validar teléfono
        const tlf = document.getElementById('tlf_modal').value;
        if (tlf.length < 10 || !/^\d+$/.test(tlf)) {
            throw new Error('El teléfono debe tener al menos 10 dígitos');
        }

        // Validar fechas
        const fechaNac = new Date(document.getElementById('fecha_nac_modal').value);
        const fechaIngreso = new Date(document.getElementById('fecha_ingreso_modal').value);
        if (fechaIngreso < fechaNac) {
            throw new Error('La fecha de ingreso no puede ser anterior a la fecha de nacimiento');
        }

        // Recolectar todos los datos del formulario
        const formData = new FormData(form);
        
        // Agregar campos dinámicos
        const titulos = Array.from(document.querySelectorAll('#titulosContainer_modal input[name="titulos[]"]')).map(input => input.value.trim()).filter(Boolean);
        const institutos = Array.from(document.querySelectorAll('#institutosContainer_modal input[name="institutos[]"]')).map(input => input.value.trim()).filter(Boolean);
        
        // Convertir FormData a objeto para poder modificarlo
        const data = Object.fromEntries(formData.entries());
        data.titulos = titulos;
        data.institutos = institutos;

        // Enviar como JSON
        const response = await fetch('procesar_estudiante.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });

        // Verificar respuesta JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            const text = await response.text();
            console.error('Respuesta no JSON:', text);
            throw new Error('La respuesta del servidor no es válida');
        }

        const result = await response.json();
        
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Error en el servidor');
        }

        // Éxito
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: result.message,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('agregarEstudianteModal'));
            modal.hide();
            location.reload();
        });

    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: error.message,
            confirmButtonText: 'Entendido'
        });
    } finally {
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
});
</script>







</script>







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






// Calcular edad automáticamente cuando cambia la fecha de nacimiento
$(document).on('change', '#fecha_nac', function() {
    var fechaNac = new Date($(this).val());
    var hoy = new Date();
    var edad = hoy.getFullYear() - fechaNac.getFullYear();
    var m = hoy.getMonth() - fechaNac.getMonth();
    
    if (m < 0 || (m === 0 && hoy.getDate() < fechaNac.getDate())) {
        edad--;
    }
    
    // Puedes mostrar la edad en algún campo si lo necesitas
    console.log('Edad calculada:', edad);
});

// Validación de email
$(document).on('blur', '#email', function() {
    const email = $(this).val();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        $(this).addClass('is-invalid');
        // Puedes mostrar un mensaje de error aquí
    } else {
        $(this).removeClass('is-invalid');
    }
});


</script>

<?php include("includes/footer.php"); ?>