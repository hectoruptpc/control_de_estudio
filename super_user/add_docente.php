<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Añadir nuevo docente";
require_once('../funciones/functions.php');
include("includes/head.php");

// Verificar permisos
$permiso_agregar = isset($_SESSION['user']['agregar_docente']) && $_SESSION['user']['agregar_docente'] == 1;
$permiso_editar = isset($_SESSION['user']['editar_docente']) && $_SESSION['user']['editar_docente'] == 1;

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que el usuario tenga permiso para agregar
    if (!$permiso_agregar) {
        echo '<div class="alert alert-danger">No tiene permisos para agregar docentes</div>';
    } else {
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
}

// Obtener lista de docentes
$docentes = obtenerDocentes();

// Obtener lista de títulos desde la base de datos
$titulos_db = [];
$query = "SELECT id, nombre FROM titulos ORDER BY nombre";
if ($stmt = $db->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $titulos_db[] = $row;
    }
    $stmt->close();
}
?>

<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
             <?php if (tienePermiso('agregar_docente')): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Agregar Nuevo Docente</h5>
                </div>
                <div class="card-body">
                    <form id="formDocente" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <!-- Sección 1: Identificación -->
                        <h5 class="mb-3"><i class="fas fa-id-card me-2"></i> Identificación</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label required">Nombre Completo</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                           <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="tipo_documento" class="form-label required">Tipo de Documento</label>
                                    <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $query = "SELECT id, tipo FROM tipo_cedula ORDER BY tipo";
                                        if ($stmt = $db->prepare($query)) {
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = (isset($_POST['tipo_documento']) && $_POST['tipo_documento'] == $row['id']) ? 'selected' : '';
                                                echo '<option value="' . $row['id'] . '" ' . $selected . '>' 
                                                     . htmlspecialchars($row['tipo']) . '</option>';
                                            }
                                            
                                            $stmt->close();
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
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
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label required">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                           value="<?= htmlspecialchars($_POST['fecha_nacimiento'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label required">Género</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_m" 
                                                   value="Masculino" <?= ($_POST['genero'] ?? '') == 'Masculino' ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="genero_m">Masculino</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_f" 
                                                   value="Femenino" <?= ($_POST['genero'] ?? '') == 'Femenino' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="genero_f">Femenino</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="mb-3">
                                    <label for="estado_civil" class="form-label required">Estado Civil</label>
                                    <select class="form-select" id="estado_civil" name="estado_civil" required>
                                        <option value="">Seleccione...</option>
                                        <?php
                                        // Consulta para obtener los estados civiles desde la base de datos
                                        $query = "SELECT id, estado_civil FROM estado_civil ORDER BY estado_civil";
                                        $result = $db->query($query);
                                        
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = (isset($_POST['estado_civil']) && $_POST['estado_civil'] == $row['id']) ? 'selected' : '';
                                                echo '<option value="' . $row['id'] . '" ' . $selected . '>' 
                                                     . htmlspecialchars($row['estado_civil']) . '</option>';
                                            }
                                        }
                                        
                                        // Cerrar el resultado si es necesario (depende de tu configuración)
                                        if ($result) {
                                            $result->free();
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-3">
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
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="especialidad" class="form-label required">Especialidad / Potencialidades</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="especialidad" name="potencialidades[]" 
                                               value="<?= htmlspecialchars($_POST['potencialidades'][0] ?? '') ?>" required>
                                        <button type="button" class="btn btn-outline-primary" id="addPotencialidad">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="potencialidadesContainer">
                                    <?php if(!empty($_POST['potencialidades']) && count($_POST['potencialidades']) > 1): ?>
                                        <?php for($i = 1; $i < count($_POST['potencialidades']); $i++): ?>
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="potencialidades[]" 
                                                           value="<?= htmlspecialchars($_POST['potencialidades'][$i] ?? '') ?>">
                                                    <button type="button" class="btn btn-outline-danger remove-field">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                           <!-- Títulos Obtenidos e Instituciones -->
<div class="col-12">
    <div class="mb-3">
        <label class="form-label">Títulos Obtenidos e Instituciones</label>
        <div class="row g-2 mb-3">
            <div class="col-12 col-md-5">
                <select class="form-select" id="titulos" name="titulos_main">
                    <option value="">Seleccione un título...</option>
                    <?php foreach ($titulos_db as $titulo): ?>
                        <option value="<?= htmlspecialchars($titulo['nombre']) ?>" <?= isset($_POST['titulos_main']) && $_POST['titulos_main'] == $titulo['nombre'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($titulo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-5">
                <input type="text" class="form-control" id="institutos" name="institutos_main" 
                       value="<?= htmlspecialchars($_POST['institutos_main'] ?? '') ?>" placeholder="Institución donde obtuvo el título">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-primary w-100" id="addTituloInstituto">
                    <i class="fas fa-plus me-1"></i> Agregar
                </button>
            </div>
        </div>
        <div id="titulosInstitutosContainer">
            <?php if(!empty($_POST['titulos_institutos'])): ?>
                <?php foreach($_POST['titulos_institutos'] as $index => $pair): ?>
                    <div class="row g-2 mb-2">
                        <div class="col-12 col-md-5">
                            <input type="text" class="form-control" name="titulos[]" 
                                   value="<?= htmlspecialchars($pair['titulo']) ?>">
                        </div>
                        <div class="col-12 col-md-5">
                            <input type="text" class="form-control" name="institutos[]" 
                                   value="<?= htmlspecialchars($pair['instituto']) ?>">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="button" class="btn btn-outline-danger w-100 remove-field">
                                <i class="fas fa-minus"></i> Eliminar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
                            
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_contratacion" class="form-label required">Fecha de Contratación</label>
                                    <input type="date" class="form-control" id="fecha_contratacion" name="fecha_contratacion" 
                                           value="<?= htmlspecialchars($_POST['fecha_contratacion'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="estado_laboral" class="form-label required">Estado Laboral</label>
                                    <select class="form-select" id="estado_laboral" name="estado_laboral" required>
                                        <option value="Activo" <?= ($_POST['estado_laboral'] ?? '') == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                        <option value="Inactivo" <?= ($_POST['estado_laboral'] ?? '') == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 4: Ubicación y Vivienda -->
                        <h5 class="mb-3"><i class="fas fa-home me-2"></i> Vivienda</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="estado_residencia" class="form-label required">Estado</label>
                                    <input type="text" class="form-control" id="estado_residencia" name="estado_residencia" 
                                           value="<?= htmlspecialchars($_POST['estado_residencia'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="municipio" class="form-label required">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio" 
                                           value="<?= htmlspecialchars($_POST['municipio'] ?? '') ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="parroquia" class="form-label">Parroquia</label>
                                    <input type="text" class="form-control" id="parroquia" name="parroquia" 
                                           value="<?= htmlspecialchars($_POST['parroquia'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label required">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2" required><?= 
                                        htmlspecialchars($_POST['direccion'] ?? '') ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-6">
                                <div class="mb-3">
                                    <label for="punto_referencia" class="form-label">Punto de Referencia</label>
                                    <input type="text" class="form-control" id="punto_referencia" name="punto_referencia" 
                                           value="<?= htmlspecialchars($_POST['punto_referencia'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="tipo_vivienda" class="form-label">Tipo de Vivienda</label>
                                    <select class="form-select" id="tipo_vivienda" name="tipo_vivienda">
                                        <option value="">Seleccione...</option>
                                        <?php
                                        $query = "SELECT id, vivienda FROM tipo_vivienda ORDER BY vivienda";
                                        if ($stmt = $db->prepare($query)) {
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = (isset($_POST['tipo_vivienda']) && $_POST['tipo_vivienda'] == $row['vivienda']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($row['vivienda']) . '" ' . $selected . '>' 
                                                     . htmlspecialchars($row['vivienda']) . '</option>';
                                            }
                                            
                                            $stmt->close();
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="tenencia_vivienda" class="form-label">Tenencia de Vivienda</label>
                                    <select class="form-select" id="tenencia_vivienda" name="tenencia_vivienda">
                                        <option value="">Seleccione...</option>
                                        <?php
                                        try {
                                            $query = "SELECT id, tenencia FROM tenencia_vivienda ORDER BY tenencia";
                                            if ($stmt = $db->prepare($query)) {
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = (isset($_POST['tenencia_vivienda']) && $_POST['tenencia_vivienda'] == $row['tenencia']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($row['tenencia']) . '" ' . $selected . '>' 
                                                         . htmlspecialchars($row['tenencia']) . '</option>';
                                                }
                                                
                                                $stmt->close();
                                            } else {
                                                echo '<option value="">Error al preparar la consulta</option>';
                                            }
                                        } catch (Exception $e) {
                                            echo '<option value="">Error al cargar opciones</option>';
                                            error_log("Error en tenencia_vivienda: " . $e->getMessage());
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 5: Situación Familiar -->
                        <h5 class="mb-3"><i class="fas fa-users me-2"></i> Situación Familiar</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="grupo_familiar" class="form-label">Grupo Familiar</label>
                                    <input type="number" class="form-control" id="grupo_familiar" name="grupo_familiar" 
                                           value="<?= htmlspecialchars($_POST['grupo_familiar'] ?? '') ?>" min="1" placeholder="Número de personas">
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="personas_a_cargo" class="form-label">Personas a su cargo</label>
                                    <input type="number" class="form-control" id="personas_a_cargo" name="personas_a_cargo" 
                                           value="<?= htmlspecialchars($_POST['personas_a_cargo'] ?? '') ?>" min="0">
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="fuente_ingresos" class="form-label">Fuente de Ingresos</label>
                                    <select class="form-select" id="fuente_ingresos" name="fuente_ingresos">
                                        <option value="">Seleccione una opción</option>
                                        <?php 
                                        $ingresos = obtenerIngresos($db);
                                        foreach ($ingresos as $id => $ingreso): ?>
                                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($ingreso); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 6: Salud -->
                        <h5 class="mb-3"><i class="fas fa-heartbeat me-2"></i> Salud</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="enfermedades" class="form-label">Enfermedades</label>
                                    <input type="text" class="form-control" id="enfermedades" name="enfermedades" 
                                           value="<?= htmlspecialchars($_POST['enfermedades'] ?? '') ?>" placeholder="Enfermedades conocidas">
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-6">
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
                            <div class="col-12 col-md-6">
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
                            
                            <div class="col-12 col-md-6">
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

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">
                            <button type="button" onclick="history.back()" class="btn btn-outline-secondary order-2 order-md-1 w-100 w-md-auto">
                                <i class="fas fa-arrow-left me-1"></i> Regresar
                            </button>
                            
                            <div class="d-flex flex-column flex-md-row gap-2 order-1 order-md-2 w-100 w-md-auto">
                                <button type="reset" class="btn btn-secondary">
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
            <?php endif; ?>
            
            <!-- Tabla de docentes registrados -->
            <div class="card mt-4 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Docentes Registrados</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="tablaDocentes">
                            <thead class="table-dark">
                                <tr>
                                    <th width="70">ID</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Contacto</th>
                                    <th width="100">Estado</th>
                                    <?php if ($permiso_editar): ?>
                                    <th width="150" class="text-center">Acciones</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($docentes as $docente): ?>
                                <tr>
                                    <td class="fw-bold"><?= $docente['id'] ?></td>
                                    <td><?= $docente['idusuario'] ?></td>
                                    <td><?= $docente['nombre'] ?></td>
                                    <td><?= $docente['email'] ?></td>
                                    <td><?= $docente['tlf'] ?></td>
                                    <td>
                                        <span class="badge <?= ($docente['status'] == 1) ? 'bg-success' : 'bg-warning' ?>">
                                            <?= ($docente['status'] == 1) ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <?php if ($permiso_editar): ?>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-editar" 
                                                    data-id="<?= $docente['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-<?= ($docente['status'] == 1) ? 'warning' : 'success' ?> btn-estado" 
                                                    data-id="<?= $docente['id'] ?>" 
                                                    data-status="<?= $docente['status'] ?>"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEstado">
                                                <i class="fas <?= ($docente['status'] == 1) ? 'fa-ban' : 'fa-check' ?>"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 <?php if (tienePermiso('editar_docente')): ?>
<!-- Modal para Editar Docente -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarLabel">Editar Docente</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="modalEstadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEstadoLabel">Cambiar Estado del Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<?php endif; ?>

<?php include("includes/footer.php"); ?>

<style>
/* ESTILOS RESPONSIVE */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .form-check {
        margin-right: 1rem;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        border-radius: 0.375rem !important;
        margin-bottom: 0.5rem;
    }
    
    .input-group .btn {
        border-radius: 0.375rem;
        width: 100%;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .d-flex.flex-md-row {
        flex-direction: column !important;
    }
    
    .gap-3 {
        gap: 1rem !important;
    }
}

/* Mejoras generales */
.form-select, .form-control {
    border-radius: 0.375rem;
}

.card {
    border-radius: 0.5rem;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para añadir títulos e instituciones juntos
    document.getElementById('addTituloInstituto').addEventListener('click', function() {
        const tituloSelect = document.getElementById('titulos');
        const titulo = tituloSelect.value;
        const instituto = document.getElementById('institutos').value;
        
        if(titulo.trim() === '' || instituto.trim() === '') {
            alert('Por favor ingrese tanto el título como la institución');
            return;
        }
        
        const container = document.getElementById('titulosInstitutosContainer');
        
        const newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2';
        newRow.innerHTML = `
            <div class="col-12 col-md-5">
                <input type="text" class="form-control" name="titulos[]" value="${titulo.replace(/"/g, '&quot;')}">
            </div>
            <div class="col-12 col-md-5">
                <input type="text" class="form-control" name="institutos[]" value="${instituto.replace(/"/g, '&quot;')}">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 remove-field">
                    <i class="fas fa-minus"></i> Eliminar
                </button>
            </div>
        `;
        
        container.appendChild(newRow);
        
        // Limpiar los campos principales
        tituloSelect.value = '';
        document.getElementById('institutos').value = '';
    });

    // Delegación de eventos para los botones de eliminar
    document.getElementById('titulosInstitutosContainer').addEventListener('click', function(e) {
        if(e.target.classList.contains('remove-field') || e.target.closest('.remove-field')) {
            const button = e.target.classList.contains('remove-field') ? e.target : e.target.closest('.remove-field');
            button.closest('.row').remove();
        }
    });

    // Función para añadir nuevos campos individuales
    function addField(containerId, inputId, namePrefix, placeholder, buttonId) {
        const container = document.getElementById(containerId);
        const mainInput = document.getElementById(inputId);
        const value = mainInput.value.trim();
        
        if(value === '') return;
        
        const newField = document.createElement('div');
        newField.className = 'mb-3';
        newField.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="${namePrefix}[]" 
                       value="${value}" placeholder="${placeholder}">
                <button type="button" class="btn btn-outline-danger remove-field">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;
        container.appendChild(newField);
        
        mainInput.value = '';
        mainInput.focus();
    }
    
    // Evento para añadir potencialidades
    document.getElementById('addPotencialidad').addEventListener('click', function() {
        addField('potencialidadesContainer', 'especialidad', 'potencialidades', 'Especialidad/Potencialidad', 'addPotencialidad');
    });
    
    // Manejar el evento Enter en los campos
    document.getElementById('especialidad').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addPotencialidad').click();
        }
    });
    
    document.getElementById('titulos').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addTituloInstituto').click();
        }
    });
    
    document.getElementById('institutos').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('addTituloInstituto').click();
        }
    });

    // Configurar DataTable
    $('#tablaDocentes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
    });
    
    // Manejar clic en botón Editar
    $(document).on('click', '.btn-editar', function() {
        var docenteId = $(this).data('id');
        $('#modalEditar').modal('show');
        
        $.ajax({
            url: 'cargar_formulario_edicion.php',
            type: 'GET',
            data: {id: docenteId},
            success: function(response) {
                $('#contenidoModalEditar').html(response);
                setupDynamicFieldsInModal();
            },
            error: function() {
                $('#contenidoModalEditar').html(
                    '<div class="alert alert-danger">Error al cargar los datos del docente.</div>'
                );
            }
        });
    });
    
    // Función para configurar campos dinámicos en el modal de edición
    function setupDynamicFieldsInModal() {
        // Configurar el botón para agregar títulos e institutos juntos en el modal
        $('#addTituloInstitutoEdit').on('click', function() {
            const titulo = $('#titulosEdit').val();
            const instituto = $('#institutosEdit').val();
            
            if(titulo.trim() === '' || instituto.trim() === '') {
                alert('Por favor ingrese tanto el título como la institución');
                return;
            }
            
            const container = $('#titulosInstitutosContainerEdit');
            
            const newRow = $(`
                <div class="row g-2 mb-2">
                    <div class="col-12 col-md-5">
                        <input type="text" class="form-control" name="titulos[]" value="${titulo.replace(/"/g, '&quot;')}">
                    </div>
                    <div class="col-12 col-md-5">
                        <input type="text" class="form-control" name="institutos[]" value="${instituto.replace(/"/g, '&quot;')}">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100 remove-field">
                            <i class="fas fa-minus"></i> Eliminar
                        </button>
                    </div>
                </div>
            `);
            
            container.append(newRow);
            
            $('#titulosEdit').val('');
            $('#institutosEdit').val('');
        });
        
        // Eliminar campos en el modal
        $(document).on('click', '.remove-field', function() {
            $(this).closest('.row, .mb-3').remove();
        });
    }
    
    // Guardar cambios en el modal de edición: bind al submit del formulario cargado por AJAX
    $(document).on('submit', '#formEditarDocente', function(e) {
        e.preventDefault();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Procesando',
                html: 'Actualizando datos del docente...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        var formArray = $(this).serializeArray();
        var jsonData = {};
        $.each(formArray, function(i, field) { jsonData[field.name] = field.value; });

        if (!jsonData.fecha_nac) jsonData.fecha_nac = null;
        if (!jsonData.fecha_ingreso) jsonData.fecha_ingreso = null;

        $.ajax({
            url: 'actualizar_docente.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(jsonData),
            dataType: 'json',
            success: function(response) {
                if (typeof Swal !== 'undefined') Swal.close();
                if (response.success) {
                    $('#modalEditar').modal('hide');
                    if (typeof refreshDocentesTable === 'function') {
                        refreshDocentesTable();
                    } else {
                        location.reload();
                    }
                } else {
                    alert('Error: ' + (response.message || 'No se pudo guardar'));
                }
            },
            error: function(xhr) {
                if (typeof Swal !== 'undefined') Swal.close();
                var msg = 'Error al enviar los datos.';
                try { var r = xhr.responseJSON || JSON.parse(xhr.responseText); if (r && r.message) msg = r.message; } catch (e) {}
                alert(msg);
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
        $('#textoConfirmacion').text(`¿Está seguro que desea ${accion} este docente?`);
        $('#modalEstado').modal('show');
    });
    
    // Confirmar cambio de estado
    $('#confirmarCambioEstado').click(function() {
        var docenteId = $('#docenteId').val();
        var nuevoEstado = $('#nuevoEstado').val();
        
        $.ajax({
            url: 'cambiar_estado_docente.php',
            type: 'POST',
            dataType: 'json',
            data: {id: docenteId, status: nuevoEstado},
            success: function(response) {
                if(response.success) {
                    $('#modalEstado').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'No se pudo cambiar el estado.'));
                }
            },
            error: function() {
                alert('Error al cambiar el estado.');
            }
        });
    });
});
</script>