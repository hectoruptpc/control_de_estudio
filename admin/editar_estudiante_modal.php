<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../funciones/functions.php');

// Variables iniciales
$estudiante = null;
$error_message = '';
$success_message = '';

// Cargar datos del estudiante
if (isset($_GET['id'])) {
    $estudiante = obtenerEstudiantePorId($_GET['id']);
    
    if (!$estudiante) {
        $error_message = "No se encontró el estudiante con ID ".$_GET['id'];
    }
} else {
    $error_message = "ID de estudiante no especificado";
}

if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php return; endif; ?>

<?php if ($estudiante): ?>
<form id="formEstudiante" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($estudiante['id']); ?>">
    
    <div class="row g-3">
        <!-- Primera columna (izquierda) -->
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
                <label for="carrera" class="form-label required">Carrera</label>
                <select class="custom-select d-block w-100" id="carrera" name="carrera" required>
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
                <select class="custom-select d-block w-100" id="edo_civil" name="edo_civil" required>
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
        
        <!-- Segunda columna (derecha) CON "Nombre Completo" al inicio -->
        <div class="col-md-6">
            <!-- Nombre Completo ahora está aquí (arriba a la derecha) -->
            <div class="mb-3">
                <label for="nombre" class="form-label required">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                       value="<?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?>" required>
            </div>
            
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
                <select class="custom-select d-block w-100" id="status" name="status" required>
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

    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
        <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Guardar Cambios
        </button>
    </div>
</form>
<?php endif; ?>