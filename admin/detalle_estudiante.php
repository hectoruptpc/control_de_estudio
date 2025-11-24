<?php
require_once('../funciones/functions.php');

// Verificar ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('<div class="alert alert-danger">ID de estudiante no válido</div>');
}

// Obtener datos
$estudiante = obtenerEstudiantePorId($id);
if (isset($estudiante['error'])) {
    die('<div class="alert alert-danger">'.$estudiante['error'].'</div>');
}

// Obtener nombre de la carrera
$nombreCarrera = 'No especificada';
if(isset($estudiante['carrera']) && !empty($estudiante['carrera'])) {
    global $db;
    $id_carrera = $estudiante['carrera'];
    $query = $db->query("SELECT nombre_carrera FROM carreras WHERE id_carrera = $id_carrera");
    if ($query && $carrera = $query->fetch_assoc()) {
        $nombreCarrera = $carrera['nombre_carrera'];
    }
}

// Manejo robusto de la foto de perfil
$fotoPerfil = '';
$tieneFoto = false;

if (!empty($estudiante['foto_perfil'])) {
    $rutaFoto = '../foto_perfil/' . $estudiante['foto_perfil'];
    // Verificar si el archivo existe físicamente
    if (file_exists($rutaFoto) && is_file($rutaFoto)) {
        $fotoPerfil = $rutaFoto;
        $tieneFoto = true;
    }
}

// Si no hay foto válida, usar una predeterminada
if (!$tieneFoto) {
    // Puedes usar una de estas opciones:
    
   
    
    // Opción 3: Imagen SVG directa (recomendado)
    $fotoPerfil = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='40' r='20' fill='%236c757d'/%3E%3Ccircle cx='50' cy='100' r='40' fill='%236c757d'/%3E%3Ctext x='50' y='45' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EUSER%3C/text%3E%3C/svg%3E";
}
?>


<div class="modal-body p-0">
    <!-- Header con foto y datos principales -->
    <div class="bg-light py-4 px-4 border-bottom">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-container position-relative">
                    <img src="<?= $fotoPerfil ?>" 
                         alt="Foto de perfil del estudiante" 
                         class="avatar-img rounded-circle border"
                         id="fotoEstudiante">
                    <div class="status-indicator <?= ($estudiante['status'] ?? 0) == 1 ? 'bg-success' : 'bg-secondary' ?>"></div>
                </div>
            </div>
            <div class="col">
                <h4 class="mb-1 text-dark"><?= htmlspecialchars($estudiante['nombre'] ?? '') ?></h4>
                <p class="text-muted mb-1">
                    <i class="fas fa-id-card me-1"></i>
                    <?= htmlspecialchars($estudiante['idusuario'] ?? '') ?> 
                    | ID: <?= htmlspecialchars($estudiante['id'] ?? '') ?>
                </p>
                <p class="mb-1">
                    <span class="badge bg-primary">
                        <i class="fas fa-graduation-cap me-1"></i>
                        <?= htmlspecialchars($nombreCarrera) ?>
                    </span>
                    <span class="badge <?= ($estudiante['status'] ?? 0) == 1 ? 'bg-success' : 'bg-secondary' ?> ms-2">
                        <?= ($estudiante['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo' ?>
                    </span>
                    <?php if (!$tieneFoto): ?>
                        <span class="badge bg-warning ms-2">
                            <i class="fas fa-camera me-1"></i>Sin foto
                        </span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- El resto del contenido permanece igual -->
    <div class="container-fluid py-4">
        <!-- Primera fila: Información personal y académica -->
        <div class="row g-4 mb-4">
            <!-- Información Personal -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-blue text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>Información Personal
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Género</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['genero'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Estado Civil</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['edo_civil'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Fecha Nacimiento</label>
                                <p class="mb-0 fw-semibold"><?= !empty($estudiante['fecha_nac']) ? date('d/m/Y', strtotime($estudiante['fecha_nac'])) : 'No especificado' ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Etnia</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['etnia'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Académica -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-green text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>Información Académica
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Programa</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($nombreCarrera) ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Fecha Ingreso</label>
                                <p class="mb-0 fw-semibold"><?= !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : 'No especificado' ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Última Actualización</label>
                                <p class="mb-0 fw-semibold"><?= !empty($estudiante['fecha_act']) ? date('d/m/Y H:i', strtotime($estudiante['fecha_act'])) : 'No especificado' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Las demás secciones permanecen igual -->
        <!-- Segunda fila: Contacto y Salud -->
        <div class="row g-4 mb-4">
            <!-- Información de Contacto -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-purple text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-address-book me-2"></i>Información de Contacto
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Email</label>
                                <p class="mb-0 fw-semibold">
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    <?= htmlspecialchars($estudiante['email'] ?? 'No especificado') ?>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Teléfono Principal</label>
                                <p class="mb-0 fw-semibold">
                                    <i class="fas fa-phone me-1 text-success"></i>
                                    <?= htmlspecialchars($estudiante['tlf'] ?? 'No especificado') ?>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Celular</label>
                                <p class="mb-0 fw-semibold">
                                    <i class="fas fa-mobile-alt me-1 text-info"></i>
                                    <?= htmlspecialchars($estudiante['cel'] ?? 'No especificado') ?>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Teléfono Opcional</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['num_telf_opc'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos de Salud -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-orange text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-heartbeat me-2"></i>Datos de Salud
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Enfermedades</label>
                                <p class="mb-0 fw-semibold"><?= !empty($estudiante['enfermedad']) ? htmlspecialchars($estudiante['enfermedad']) : '<span class="text-muted">Ninguna registrada</span>' ?></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Discapacidad</label>
                                <p class="mb-0 fw-semibold"><?= !empty($estudiante['discapacidad']) ? htmlspecialchars($estudiante['discapacidad']) : '<span class="text-muted">Ninguna registrada</span>' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila: Dirección y Ubicación -->
        <div class="row g-4 mb-4">
            <!-- Dirección Residencial -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-pink text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-home me-2"></i>Dirección Residencial
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Dirección Completa</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['direccion'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Tipo de Vivienda</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['casaapto'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Punto de Referencia</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['punto_referencia'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubicación Geográfica -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-cyan text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Ubicación Geográfica
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Estado</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['estado'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Municipio</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['municipio'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Parroquia</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['parroquia'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Ciudad</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['ciudad'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cuarta fila: Situación Familiar y Vivienda -->
        <div class="row g-4">
            <!-- Situación Familiar -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-yellow text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-users me-2"></i>Situación Familiar
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Grupo Familiar</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['grupo_familiar'] ?? 'No especificado') ?> personas</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Personas a Cargo</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['acargo_usted'] ?? 'No especificado') ?> personas</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted mb-1">Fuente de Ingresos</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['fuente_ingresos'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Situación de Vivienda -->
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light-teal text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-building me-2"></i>Situación de Vivienda
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Tipo de Vivienda</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['tipo_vivienda'] ?? 'No especificado') ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small text-muted mb-1">Tenencia</label>
                                <p class="mb-0 fw-semibold"><?= htmlspecialchars($estudiante['tenencia_vivienda'] ?? 'No especificado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
// JavaScript adicional para manejar errores de imagen
document.addEventListener('DOMContentLoaded', function() {
    const fotoEstudiante = document.getElementById('fotoEstudiante');
    
    if (fotoEstudiante) {
        fotoEstudiante.addEventListener('error', function() {
            // Si falla la imagen, usar SVG por defecto
            this.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='40' r='20' fill='%236c757d'/%3E%3Ccircle cx='50' cy='100' r='40' fill='%236c757d'/%3E%3Ctext x='50' y='45' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EUSER%3C/text%3E%3C/svg%3E";
        });
    }
});
</script>

<style>
.avatar-container {
    position: relative;
    display: inline-block;
}

.avatar-img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    background-color: #f8f9fa; /* Fondo por si falla la imagen */
}

.status-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid #fff;
}

/* Colores de header para las tarjetas */
.bg-light-blue { background-color: #e3f2fd !important; }
.bg-light-green { background-color: #e8f5e9 !important; }
.bg-light-purple { background-color: #f3e5f5 !important; }
.bg-light-orange { background-color: #fff3e0 !important; }
.bg-light-pink { background-color: #fce4ec !important; }
.bg-light-cyan { background-color: #e0f2f1 !important; }
.bg-light-yellow { background-color: #fffde7 !important; }
.bg-light-teal { background-color: #e0f2f1 !important; }

.card {
    border: 1px solid #e0e0e0;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.form-label {
    font-size: 0.8rem;
    font-weight: 500;
}

.fw-semibold {
    font-weight: 600;
}
</style>