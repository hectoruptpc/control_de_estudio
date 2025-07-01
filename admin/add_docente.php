<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Añadir nuevo docente";
require_once('../funciones/functions.php');
include("includes/head.php");

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar datos primero
    $validacion = validarDocente($_POST);
    
    if ($validacion === true) {
        $resultado = insertarDocente($_POST);
        
        if ($resultado['success']) {
            echo '<div class="alert alert-success">'.$resultado['message'].'</div>';
            // Limpiar POST para no rellenar el formulario
            $_POST = [];
        } else {
            echo '<div class="alert alert-danger">'.$resultado['message'].'</div>';
        }
    } else {
        echo '<div class="alert alert-danger">'.implode('<br>', $validacion).'</div>';
    }
}

// Obtener lista de docentes
$docentes = obtenerDocentes();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Agregar Nuevo Docente</h5>
                </div>
                <div class="card-body">
                    <form id="formDocente" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <!-- Sección 1: Identificación -->
                        <h5 class="mb-3"><i class="fas fa-id-card me-2"></i> Identificación</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label required">Nombre Completo</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="tipo_documento" class="form-label required">Tipo de Documento</label>
                                    <select class="custom-select d-block w-100" id="tipo_documento" name="tipo_documento" required>
                                        <option value="V" <?= ($_POST['tipo_documento'] ?? '') == 'V' ? 'selected' : '' ?>>V- (Venezolano)</option>
                                        <option value="E" <?= ($_POST['tipo_documento'] ?? '') == 'E' ? 'selected' : '' ?>>E- (Extranjero)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="documento" class="form-label required">Número de Documento</label>
                                    <input type="text" class="form-control" id="documento" name="documento" 
                                           value="<?= htmlspecialchars($_POST['documento'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Datos Personales -->
                        <h5 class="mb-3"><i class="fas fa-user-tag me-2"></i> Datos Personales</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label required">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                           value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label required">Género</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_m" 
                                                   value="Masculino" <?= ($_POST['genero'] ?? '') == 'Masculino' ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="genero_m">Masculino</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_f" 
                                                   value="Femenino" <?= ($_POST['genero'] ?? '') == 'Femenino' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="genero_f">Femenino</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="estado_civil" class="form-label required">Estado Civil</label>
                                    <select class="custom-select d-block w-100" id="estado_civil" name="estado_civil" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Soltero/a" <?= ($_POST['estado_civil'] ?? '') == 'Soltero/a' ? 'selected' : '' ?>>Soltero/a</option>
                                        <option value="Casado/a" <?= ($_POST['estado_civil'] ?? '') == 'Casado/a' ? 'selected' : '' ?>>Casado/a</option>
                                        <option value="Divorciado/a" <?= ($_POST['estado_civil'] ?? '') == 'Divorciado/a' ? 'selected' : '' ?>>Divorciado/a</option>
                                        <option value="Viudo/a" <?= ($_POST['estado_civil'] ?? '') == 'Viudo/a' ? 'selected' : '' ?>>Viudo/a</option>
                                        <option value="Unión Libre" <?= ($_POST['estado_civil'] ?? '') == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="etnia" class="form-label">Etnia</label>
                                    <input type="text" class="form-control" id="etnia" name="etnia" 
                                           value="<?= htmlspecialchars($_POST['etnia'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Datos Profesionales -->
<h5 class="mb-3"><i class="fas fa-briefcase me-2"></i> Datos Profesionales</h5>
<div class="row g-3 mb-4">
    <!-- Potencialidades -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="especialidad" class="form-label required">Potencialidades</label>
            <div class="input-group">
                <input type="text" class="form-control" id="especialidad" name="especialidad_main" 
                       value="<?= htmlspecialchars($_POST['especialidad_main'] ?? '') ?>" required>
                <button type="button" class="btn btn-outline-primary" id="addPotencialidad">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div id="potencialidadesContainer">
            <?php if(!empty($_POST['especialidad'])): ?>
                <?php foreach($_POST['especialidad'] as $value): ?>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="especialidad[]" 
                                   value="<?= htmlspecialchars($value) ?>">
                            <button type="button" class="btn btn-outline-danger remove-field">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Títulos Obtenidos -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="titulos" class="form-label">Títulos Obtenidos</label>
            <div class="input-group">
                <input type="text" class="form-control" id="titulos" name="titulos_main" 
                       value="<?= htmlspecialchars($_POST['titulos_main'] ?? '') ?>" placeholder="Titulos obtenidos">
                <button type="button" class="btn btn-outline-primary" id="addTitulo">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div id="titulosContainer">
            <?php if(!empty($_POST['titulos'])): ?>
                <?php foreach($_POST['titulos'] as $value): ?>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="titulos[]" 
                                   value="<?= htmlspecialchars($value) ?>">
                            <button type="button" class="btn btn-outline-danger remove-field">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Instituciones -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="institutos" class="form-label">Instituciones</label>
            <div class="input-group">
                <input type="text" class="form-control" id="institutos" name="institutos_main" 
                       value="<?= htmlspecialchars($_POST['institutos_main'] ?? '') ?>" placeholder="Instituciones donde obtuvo los títulos">
                <button type="button" class="btn btn-outline-primary" id="addInstituto">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div id="institutosContainer">
            <?php if(!empty($_POST['institutos'])): ?>
                <?php foreach($_POST['institutos'] as $value): ?>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="institutos[]" 
                                   value="<?= htmlspecialchars($value) ?>">
                            <button type="button" class="btn btn-outline-danger remove-field">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_contratacion" class="form-label required">Fecha de Contratación</label>
                                    <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" 
                                           value="<?= htmlspecialchars($_POST['fecha_contratacion'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado_laboral" class="form-label required">Estado Laboral</label>
                                    <select class="custom-select d-block w-100" id="estado_laboral" name="estado_laboral" required>
                                        <option value="Activo" <?= ($_POST['estado_laboral'] ?? '') == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="Inactivo" <?= ($_POST['estado_laboral'] ?? '') == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Ubicación y Vivienda -->
                        <h5 class="mb-3"><i class="fas fa-home me-2"></i> Vivienda</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estado_residencia" class="form-label required">Estado</label>
                                    <input type="text" class="form-control" id="estado_residencia" name="estado_residencia" 
                                           value="<?= htmlspecialchars($_POST['estado_residencia'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="municipio" class="form-label required">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio" 
                                           value="<?= htmlspecialchars($_POST['municipio'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="parroquia" class="form-label">Parroquia</label>
                                    <input type="text" class="form-control" id="parroquia" name="parroquia" 
                                           value="<?= htmlspecialchars($_POST['parroquia'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label required">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2" required><?= 
                                        htmlspecialchars($_POST['direccion'] ?? '') ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="punto_referencia" class="form-label">Punto de Referencia</label>
                                    <input type="text" class="form-control" id="punto_referencia" name="punto_referencia" 
                                           value="<?= htmlspecialchars($_POST['punto_referencia'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tipo_vivienda" class="form-label">Tipo de Vivienda</label>
                                    <select class="custom-select d-block w-100" id="tipo_vivienda" name="tipo_vivienda">
                                        <option value="">Seleccione...</option>
                                        <option value="Casa" <?= ($_POST['tipo_vivienda'] ?? '') == 'Casa' ? 'selected' : '' ?>>Casa</option>
                                        <option value="Apartamento" <?= ($_POST['tipo_vivienda'] ?? '') == 'Apartamento' ? 'selected' : '' ?>>Apartamento</option>
                                        <option value="Quinta" <?= ($_POST['tipo_vivienda'] ?? '') == 'Quinta' ? 'selected' : '' ?>>Quinta</option>
                                        <option value="Otro" <?= ($_POST['tipo_vivienda'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tenencia_vivienda" class="form-label">Tenencia de Vivienda</label>
                                    <select class="custom-select d-block w-100" id="tenencia_vivienda" name="tenencia_vivienda">
                                        <option value="">Seleccione...</option>
                                        <option value="Propia" <?= ($_POST['tenencia_vivienda'] ?? '') == 'Propia' ? 'selected' : '' ?>>Propia</option>
                                        <option value="Alquilada" <?= ($_POST['tenencia_vivienda'] ?? '') == 'Alquilada' ? 'selected' : '' ?>>Alquilada</option>
                                        <option value="Familiar" <?= ($_POST['tenencia_vivienda'] ?? '') == 'Familiar' ? 'selected' : '' ?>>Familiar</option>
                                        <option value="Otro" <?= ($_POST['tenencia_vivienda'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 5: Situación Familiar -->
                        <h5 class="mb-3"><i class="fas fa-users me-2"></i> Situación Familiar</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="grupo_familiar" class="form-label">Grupo Familiar</label>
                                    <input type="number" class="form-control" id="grupo_familiar" name="grupo_familiar" 
                                           value="<?= htmlspecialchars($_POST['grupo_familiar'] ?? '') ?>" min="1" placeholder="Número de personas">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="personas_a_cargo" class="form-label">Personas a su cargo</label>
                                    <input type="number" class="form-control" id="personas_a_cargo" name="personas_a_cargo" 
                                           value="<?= htmlspecialchars($_POST['personas_a_cargo'] ?? '') ?>" min="0">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fuente_ingresos" class="form-label">Fuente de Ingresos</label>
                                    <input type="text" class="form-control" id="fuente_ingresos" name="fuente_ingresos" 
                                           value="<?= htmlspecialchars($_POST['fuente_ingresos'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Sección 6: Salud -->
                        <h5 class="mb-3"><i class="fas fa-heartbeat me-2"></i> Salud</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="enfermedades" class="form-label">Enfermedades</label>
                                    <input type="text" class="form-control" id="enfermedades" name="enfermedades" 
                                           value="<?= htmlspecialchars($_POST['enfermedades'] ?? '') ?>" placeholder="Enfermedades conocidas">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discapacidad" class="form-label">Discapacidad</label>
                                    <input type="text" class="form-control" id="discapacidad" name="discapacidad" 
                                           value="<?= htmlspecialchars($_POST['discapacidad'] ?? '') ?>" placeholder="Tipo de discapacidad si aplica">
                                </div>
                            </div>
                        </div>

                        <!-- Sección 7: Contacto -->
                        <h5 class="mb-3"><i class="fas fa-address-book me-2"></i> Contacto</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label required">Teléfono Principal</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="celular" class="form-label">Teléfono Celular</label>
                                    <input type="tel" class="form-control" id="celular" name="celular" 
                                           value="<?= htmlspecialchars($_POST['celular'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label required">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="telefono_opcional" class="form-label">Teléfono Opcional</label>
                                    <input type="tel" class="form-control" id="telefono_opcional" name="telefono_opcional" 
                                           value="<?= htmlspecialchars($_POST['telefono_opcional'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Regresar
                            </button>
                            
                            <div>
                                <button type="reset" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-eraser me-1"></i> Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Docente
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de docentes registrados -->
<div class="container mt-5">
    <h2>Docentes Registrados</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tablaDocentes">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Contacto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($docentes as $docente): ?>
                <tr>
                    <td><?= $docente['id'] ?></td>
                    <td><?= $docente['idusuario'] ?></td>
                    <td><?= $docente['nombre'] ?></td>
                    <td><?= $docente['email'] ?></td>
                    <td><?= $docente['tlf'] ?></td>
                    <td>
                        <span class="badge <?= ($docente['status'] == 1) ? 'bg-success' : 'bg-warning' ?>">
                            <?= ($docente['status'] == 1) ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary btn-editar" 
                                data-id="<?= $docente['id'] ?>" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditar">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-sm <?= ($docente['status'] == 1) ? 'btn-warning' : 'btn-success' ?> btn-estado" 
                                data-id="<?= $docente['id'] ?>" 
                                data-status="<?= $docente['status'] ?>"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEstado">
                            <i class="fas <?= ($docente['status'] == 1) ? 'fa-ban' : 'fa-check' ?>"></i> 
                            <?= ($docente['status'] == 1) ? 'Deshabilitar' : 'Habilitar' ?>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Editar Docente -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarLabel">Editar Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoModalEditar">
                <!-- El contenido se cargará aquí via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p>Cargando información del docente...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarCambios">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEstadoLabel">Cambiar Estado del Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="textoConfirmacion">¿Está seguro que desea cambiar el estado de este docente?</p>
                <input type="hidden" id="docenteId">
                <input type="hidden" id="nuevoEstado">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" id="confirmarCambioEstado">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<!-- Script para manejar la adición de campos -->
<script>
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
    
    // Eventos para los botones de añadir
    document.getElementById('addPotencialidad').addEventListener('click', function() {
        addField('potencialidadesContainer', 'especialidad', 'especialidad', 'Potencialidad', 'potencialidades');
    });
    
    document.getElementById('addTitulo').addEventListener('click', function() {
        addField('titulosContainer', 'titulos', 'titulos', 'Título obtenido', 'titulos');
    });
    
    document.getElementById('addInstituto').addEventListener('click', function() {
        addField('institutosContainer', 'institutos', 'institutos', 'Institución', 'institutos');
    });
    
    // Manejar el evento Enter en los campos principales
    document.getElementById('especialidad').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addPotencialidad').click();
        }
    });
    
    document.getElementById('titulos').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addTitulo').click();
        }
    });
    
    document.getElementById('institutos').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addInstituto').click();
        }
    });
});




  // Script para los modales y acciones de la tabla
    
    // Configurar DataTable
    $('#tablaDocentes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true
    });
    
    // Manejar clic en botón Editar
    $(document).on('click', '.btn-editar', function() {
        var docenteId = $(this).data('id');
        $('#modalEditar').modal('show');
        
        // Cargar el formulario de edición via AJAX
        $.ajax({
            url: 'cargar_formulario_edicion.php',
            type: 'GET',
            data: {id: docenteId},
            success: function(response) {
                $('#contenidoModalEditar').html(response);
            },
            error: function() {
                $('#contenidoModalEditar').html(
                    '<div class="alert alert-danger">Error al cargar los datos del docente.</div>'
                );
            }
        });
    });
    
    // Guardar cambios en el modal de edición
    $('#guardarCambios').click(function() {
        var formData = $('#formEditarDocente').serialize();
        
        $.ajax({
            url: 'actualizar_docente.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if(result.success) {
                    $('#modalEditar').modal('hide');
                    location.reload(); // Recargar para ver cambios
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function() {
                alert('Error al enviar los datos.');
            }
        });
    });
    
    // Manejar clic en botón Cambiar Estado
    $(document).on('click', '.btn-estado', function() {
        var docenteId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var nuevoStatus = (currentStatus == 1) ? 0 : 1;
        var accion = (currentStatus == 1) ? 'deshabilitar' : 'habilitar';
        
        $('#docenteId').val(docenteId);
        $('#nuevoEstado').val(nuevoStatus);
        
        // Actualizar texto del modal según la acción
        $('#textoConfirmacion').text(
            `¿Está seguro que desea ${accion} este docente?`
        );
    });
    
    // Confirmar cambio de estado
    $('#confirmarCambioEstado').click(function() {
        var docenteId = $('#docenteId').val();
        var nuevoEstado = $('#nuevoEstado').val();
        
        $.ajax({
            url: 'cambiar_estado_docente.php',
            type: 'POST',
            data: {
                id: docenteId,
                status: nuevoEstado
            },
            success: function(response) {
                var result = JSON.parse(response);
                if(result.success) {
                    $('#modalEstado').modal('hide');
                    location.reload(); // Recargar para ver cambios
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function() {
                alert('Error al cambiar el estado.');
            }
        });
    });

</script>