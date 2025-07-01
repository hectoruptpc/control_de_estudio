<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Editar Estudiante";
require_once('../funciones/functions.php');

// Variables iniciales
$estudiante = null;
$error_message = '';
$success_message = '';

// 1. Procesar actualización de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $resultado = actualizarEstudiante($_POST);
    
    if ($resultado['success']) {
        $success_message = $resultado['message'];
        // Recargar datos actualizados
        $estudiante = obtenerEstudiantePorId($_POST['id']);
    } else {
        $error_message = $resultado['message'];
        $estudiante = $_POST; // Mantener los datos editados
    }
}

// 2. Cargar datos del estudiante
if (isset($_GET['id']) && !isset($_POST['id'])) {
    $estudiante = obtenerEstudiantePorId($_GET['id']);
    
    if (!$estudiante) {
        $error_message = "No se encontró el estudiante con ID ".$_GET['id'];
    }
} elseif (!isset($_GET['id']) && !isset($_POST['id'])) {
    $error_message = "ID de estudiante no especificado";
}

include("includes/head.php");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        <?php echo isset($estudiante['nombre']) ? 'Editar: '.htmlspecialchars($estudiante['nombre']) : 'Editar Estudiante'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($estudiante): ?>
                    <form id="formEstudiante" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante['id']); ?>">
                        
                        <div class="row g-3">
                            <!-- Primera columna -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_usuario" class="form-label">ID Usuario</label>
                                    <input type="text" class="form-control" id="id_usuario" name="id_usuario" 
                                           value="<?php echo htmlspecialchars($estudiante['id_usuario'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="cod_estudiante" class="form-label required">Código del Estudiante</label>
                                    <input type="text" class="form-control" id="cod_estudiante" name="cod_estudiante" 
                                           value="<?php echo htmlspecialchars($estudiante['cod_estudiante'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label required">Nombre Completo</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="carrera" class="form-label required">Carrera</label>
                                    <select class="form-select" id="carrera" name="carrera" required>
                                        <option value="" disabled>Seleccione una carrera</option>
                                        <?php 
                                        $carreras = obtenerCarreras();
                                        foreach ($carreras as $carrera): 
                                            if (!empty($carrera)): ?>
                                                <option value="<?php echo htmlspecialchars($carrera); ?>" 
                                                    <?php echo (isset($estudiante['carrera']) && $estudiante['carrera'] == $carrera) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($carrera); ?>
                                                </option>
                                            <?php endif;
                                        endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label required">Género</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_m" 
                                                   value="Masculino" <?php echo (isset($estudiante['genero']) && $estudiante['genero'] == 'Masculino') ? 'checked' : ''; ?> required>
                                            <label class="form-check-label" for="genero_m">Masculino</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_f" 
                                                   value="Femenino" <?php echo (isset($estudiante['genero']) && $estudiante['genero'] == 'Femenino') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="genero_f">Femenino</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="genero" id="genero_o" 
                                                   value="Otro" <?php echo (isset($estudiante['genero']) && $estudiante['genero'] == 'Otro') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="genero_o">Otro</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="edo_civil" class="form-label required">Estado Civil</label>
                                    <select class="form-select" id="edo_civil" name="edo_civil" required>
                                        <option value="" disabled>Seleccione una opción</option>
                                        <?php foreach (obtenerEstadosCiviles() as $estadoCivil): ?>
                                            <option value="<?php echo htmlspecialchars($estadoCivil); ?>" 
                                                <?php echo (isset($estudiante['edo_civil']) && $estudiante['edo_civil'] == $estadoCivil) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($estadoCivil); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estado" class="form-label required">Estado</label>
                                    <input type="text" class="form-control" id="estado" name="estado" 
                                           value="<?php echo htmlspecialchars($estudiante['estado'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="municipio" class="form-label required">Municipio</label>
                                    <input type="text" class="form-control" id="municipio" name="municipio" 
                                           value="<?php echo htmlspecialchars($estudiante['municipio'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <!-- Segunda columna -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parroquia" class="form-label">Parroquia</label>
                                    <input type="text" class="form-control" id="parroquia" name="parroquia" 
                                           value="<?php echo htmlspecialchars($estudiante['parroquia'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="direccion" class="form-label required">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2" required><?php 
                                        echo htmlspecialchars($estudiante['direccion'] ?? ''); 
                                    ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="fecha_nac" class="form-label required">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" 
                                           value="<?php echo htmlspecialchars($estudiante['fecha_nac'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="edad" class="form-label required">Edad</label>
                                    <input type="number" class="form-control" id="edad" name="edad" min="15" max="100" 
                                           value="<?php echo htmlspecialchars($estudiante['edad'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="num_telf" class="form-label required">Teléfono Principal</label>
                                    <input type="tel" class="form-control" id="num_telf" name="num_telf" 
                                           value="<?php echo htmlspecialchars($estudiante['num_telf'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                                    <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc" 
                                           value="<?php echo htmlspecialchars($estudiante['num_telf_opc'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="correo" class="form-label required">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo" 
                                           value="<?php echo htmlspecialchars($estudiante['correo'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="fecha_ingreso" class="form-label required">Fecha de Ingreso</label>
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                                           value="<?php echo htmlspecialchars($estudiante['fecha_ingreso'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label required">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="" disabled>Seleccione un status</option>
                                        <?php foreach (obtenerEstadosEstudiante() as $status): ?>
                                            <option value="<?php echo htmlspecialchars($status); ?>" 
                                                <?php echo (isset($estudiante['status']) && $estudiante['status'] == $status) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($status); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                            <a href="estudiantes.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver al listado
                            </a>
                            <div>
                                <button type="reset" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-eraser me-1"></i> Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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
            }
        });
    });
</script>

<?php include("includes/footer.php"); ?>