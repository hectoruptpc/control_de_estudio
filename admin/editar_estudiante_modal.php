<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('<div class="alert alert-danger">ID de estudiante no válido</div>');
}

$id = $_GET['id'];

// Consulta modificada para formatear las fechas TIMESTAMP
$query = "SELECT *, 
          DATE(fecha_nac) as fecha_nac_format, 
          DATE(fecha_ingreso) as fecha_ingreso_format 
          FROM users WHERE id = ? AND estudiante = 1";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('<div class="alert alert-danger">Estudiante no encontrado</div>');
}

$estudiante = $result->fetch_assoc();

// Obtener listados necesarios
$carreras = obtenerTodasLasCarreras();
$generos = obtenerGeneros($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$ingresos = obtenerIngresos($db);
$tiposCedula = obtenerTiposCedula($db);

// Procesar cédula actual
$tipoActual = substr($estudiante['idusuario'] ?? '', 0, 1);
$numeroActual = substr($estudiante['idusuario'] ?? '', 2);

// Manejo de foto de perfil
$fotoPerfil = '';
if (!empty($estudiante['foto_perfil'])) {
    $rutaFoto = '../foto_perfil/' . $estudiante['foto_perfil'];
    if (file_exists($rutaFoto) && is_file($rutaFoto)) {
        $fotoPerfil = $rutaFoto;
    }
}

if (empty($fotoPerfil)) {
    $fotoPerfil = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='40' r='20' fill='%236c757d'/%3E%3Ccircle cx='50' cy='100' r='40' fill='%236c757d'/%3E%3Ctext x='50' y='45' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EUSER%3C/text%3E%3C/svg%3E";
}
?>



<div class="modal-body p-0">
    <!-- Header con foto -->
    <div class="bg-light py-3 px-4 border-bottom">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-container position-relative">
                    <img src="<?= $fotoPerfil ?>" 
                         alt="Foto de perfil" 
                         class="avatar-img rounded-circle border"
                         id="fotoPreview">
                    <div class="status-indicator <?= ($estudiante['status'] ?? 0) == 1 ? 'bg-success' : 'bg-secondary' ?>"></div>
                </div>
            </div>
            <div class="col">
                <h6 class="mb-1 text-dark"><?= htmlspecialchars($estudiante['nombre'] ?? '') ?></h6>
                <p class="text-muted mb-1 small">
                    <i class="fas fa-id-card mr-1"></i>
                    <?= htmlspecialchars($estudiante['idusuario'] ?? '') ?> 
                    | ID: <?= htmlspecialchars($estudiante['id'] ?? '') ?>
                </p>
                <div class="mb-2">
                    <input type="file" class="form-control form-control-sm" id="foto_perfil" name="foto_perfil" 
                           accept=".jpg,.jpeg,.png,.webp" style="max-width: 200px;">
                    <small class="text-muted">Formatos: JPG, JPEG, PNG, WEBP (Máx: 5MB)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-3">
        <form id="formEditarEstudiante" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $estudiante['id'] ?>">
            
            <!-- Pestañas para organizar la información - Bootstrap 4.5 -->
            <ul class="nav nav-tabs mb-4" id="editTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">
                        <i class="fas fa-user mr-1"></i>Personal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="academico-tab" data-toggle="tab" href="#academico" role="tab">
                        <i class="fas fa-graduation-cap mr-1"></i>Académico
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contacto-tab" data-toggle="tab" href="#contacto" role="tab">
                        <i class="fas fa-address-book mr-1"></i>Contacto
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vivienda-tab" data-toggle="tab" href="#vivienda" role="tab">
                        <i class="fas fa-home mr-1"></i>Vivienda
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="editTabsContent">
                <!-- Pestaña Información Personal -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="<?= htmlspecialchars($estudiante['nombre'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <!-- CAMPO CÉDULA -->
                        <div class="col-md-6">
    <div class="form-group">
        <label for="idusuario" class="form-label">Cédula</label>
        <div class="input-group">
            <select class="custom-select" id="tipo_cedula" name="tipo_cedula" style="max-width: 100px;">
                <?php 
                // Procesar cédula actual - EXTRAER SOLO LA LETRA
                $tipoActual = substr($estudiante['idusuario'] ?? '', 0, 1); // Solo la letra (V o E)
                $numeroActual = substr($estudiante['idusuario'] ?? '', 2); // El número después del guión
                ?>
                <?php foreach ($tiposCedula as $tipo): ?>
                    <?php 
                    // Extraer solo la letra del tipo (sin guión)
                    $tipoLetra = substr($tipo['tipo'], 0, 1);
                    ?>
                    <option value="<?= htmlspecialchars($tipoLetra) ?>"
                        <?= ($tipoActual == $tipoLetra) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipoLetra) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="text" class="form-control" id="numero_cedula" name="numero_cedula" 
                   value="<?= htmlspecialchars($numeroActual) ?>" 
                   placeholder="Ej: 12345678">
            <input type="hidden" id="idusuario" name="idusuario" value="<?= htmlspecialchars($estudiante['idusuario'] ?? '') ?>">
        </div>
        <small class="text-muted">Formato: V-12345678 o E-12345678</small>
    </div>
</div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= htmlspecialchars($estudiante['username'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="genero" class="form-label">Género</label>
                                <select class="custom-select" id="genero" name="genero">
                                    <option value="">Seleccionar género</option>
                                    <?php foreach ($generos as $nombre_genero): ?>
                                        <option value="<?= htmlspecialchars($nombre_genero) ?>"
                                            <?= ($estudiante['genero'] == $nombre_genero) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($nombre_genero) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edo_civil" class="form-label">Estado Civil</label>
                                <select class="custom-select" id="edo_civil" name="edo_civil">
                                    <option value="">Seleccionar estado civil</option>
                                    <?php foreach ($estadosCiviles as $id => $estadoCivil): ?>
                                        <option value="<?= htmlspecialchars($estadoCivil) ?>"
                                            <?= ($estudiante['edo_civil'] == $estadoCivil) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($estadoCivil) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" 
                                       value="<?= htmlspecialchars($estudiante['fecha_nac_format'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="etnia" class="form-label">Etnia</label>
                                <input type="text" class="form-control" id="etnia" name="etnia" 
                                       value="<?= htmlspecialchars($estudiante['etnia'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña Información Académica -->
                <div class="tab-pane fade" id="academico" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="carrera" class="form-label">Programa</label>
                                <select name="carrera" id="carrera" class="custom-select">
                                    <option value="">-- Seleccione una carrera --</option>
                                    <?php foreach ($carreras as $carrera): ?>
                                        <option value="<?= htmlspecialchars($carrera['id']) ?>" 
                                            <?= ($estudiante['carrera'] ?? '') == $carrera['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($carrera['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                                       value="<?= htmlspecialchars($estudiante['fecha_ingreso_format'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label">Estado</label>
                                <select class="custom-select" id="status" name="status">
                                    <option value="1" <?= ($estudiante['status'] ?? 1) == 1 ? 'selected' : '' ?>>Activo</option>
                                    <option value="0" <?= ($estudiante['status'] ?? 1) == 0 ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Títulos Obtenidos</label>
                                <input type="text" class="form-control" id="titulos" name="titulos" 
                                       value="<?= htmlspecialchars($estudiante['titulos'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Instituciones Anteriores</label>
                                <input type="text" class="form-control" id="institutos" name="institutos" 
                                       value="<?= htmlspecialchars($estudiante['institutos'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña Información de Contacto -->
                <div class="tab-pane fade" id="contacto" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= htmlspecialchars($estudiante['email'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tlf" class="form-label">Teléfono Principal</label>
                                <input type="tel" class="form-control" id="tlf" name="tlf" 
                                       value="<?= htmlspecialchars($estudiante['tlf'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cel" class="form-label">Teléfono Celular</label>
                                <input type="tel" class="form-control" id="cel" name="cel" 
                                       value="<?= htmlspecialchars($estudiante['cel'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                                <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc" 
                                       value="<?= htmlspecialchars($estudiante['num_telf_opc'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="enfermedad" class="form-label">Enfermedades</label>
                                <input type="text" class="form-control" id="enfermedad" name="enfermedad" 
                                       value="<?= htmlspecialchars($estudiante['enfermedad'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="discapacidad" class="form-label">Discapacidad</label>
                                <input type="text" class="form-control" id="discapacidad" name="discapacidad" 
                                       value="<?= htmlspecialchars($estudiante['discapacidad'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña Información de Vivienda -->
                <div class="tab-pane fade" id="vivienda" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="direccion" class="form-label">Dirección Completa</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="2"><?= htmlspecialchars($estudiante['direccion'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estado" name="estado" 
                                       value="<?= htmlspecialchars($estudiante['estado'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="municipio" class="form-label">Municipio</label>
                                <input type="text" class="form-control" id="municipio" name="municipio" 
                                       value="<?= htmlspecialchars($estudiante['municipio'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parroquia" class="form-label">Parroquia</label>
                                <input type="text" class="form-control" id="parroquia" name="parroquia" 
                                       value="<?= htmlspecialchars($estudiante['parroquia'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                       value="<?= htmlspecialchars($estudiante['ciudad'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="casaapto" class="form-label">Tipo de Vivienda</label>
                                <select class="custom-select" id="casaapto" name="casaapto">
                                    <option value="">Seleccionar tipo</option>
                                    <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                                        <option value="<?= htmlspecialchars($vivienda) ?>"
                                            <?= ($estudiante['casaapto'] == $vivienda) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($vivienda) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenencia_vivienda" class="form-label">Tenencia de Vivienda</label>
                                <select class="custom-select" id="tenencia_vivienda" name="tenencia_vivienda">
                                    <option value="">Seleccionar tenencia</option>
                                    <?php foreach ($tenenciasVivienda as $id => $tenencia): ?>
                                        <option value="<?= htmlspecialchars($tenencia) ?>"
                                            <?= ($estudiante['tenencia_vivienda'] == $tenencia) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tenencia) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="grupo_familiar" class="form-label">Grupo Familiar</label>
                                <input type="number" class="form-control" id="grupo_familiar" name="grupo_familiar" 
                                       value="<?= htmlspecialchars($estudiante['grupo_familiar'] ?? '') ?>" min="0">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="acargo_usted" class="form-label">Personas a Cargo</label>
                                <input type="number" class="form-control" id="acargo_usted" name="acargo_usted" 
                                       value="<?= htmlspecialchars($estudiante['acargo_usted'] ?? '') ?>" min="0">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="fuente_ingresos" class="form-label">Fuente de Ingresos</label>
                                <select class="custom-select" id="fuente_ingresos" name="fuente_ingresos">
                                    <option value="">Seleccionar fuente</option>
                                    <?php foreach ($ingresos as $id => $ingreso): ?>
                                        <option value="<?= $id ?>"
                                            <?= ($estudiante['fuente_ingresos'] == $id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ingreso) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="punto_referencia" class="form-label">Punto de Referencia</label>
                                <input type="text" class="form-control" id="punto_referencia" name="punto_referencia" 
                                       value="<?= htmlspecialchars($estudiante['punto_referencia'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones dentro del formulario -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="fas fa-save mr-1"></i>Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar pestañas de Bootstrap 4
    $('#editTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

// Script para manejar el campo de cédula - CORREGIDO
function actualizarCedulaCompleta() {
    const tipoCedula = document.getElementById('tipo_cedula').value; // Solo la letra (V o E)
    const numeroCedula = document.getElementById('numero_cedula').value.replace(/[^0-9]/g, '');
    const idusuario = document.getElementById('idusuario');
    
    // Actualizar el campo número solo con números
    document.getElementById('numero_cedula').value = numeroCedula;
    
    // Actualizar cédula completa - AGREGAR GUION MANUALMENTE
    idusuario.value = tipoCedula + '-' + numeroCedula;
}

// Event listeners para el campo cédula
document.getElementById('tipo_cedula').addEventListener('change', actualizarCedulaCompleta);
document.getElementById('numero_cedula').addEventListener('input', actualizarCedulaCompleta);

// Inicializar cédula al cargar
actualizarCedulaCompleta();

    // Vista previa de foto de perfil
    $('#foto_perfil').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#fotoPreview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Validación del formulario - ELIMINADA LA VALIDACIÓN DE CAMPOS REQUERIDOS
    $('#formEditarEstudiante').on('submit', function(e) {
        e.preventDefault();
        
        // Solo validaciones básicas de formato (no de campos requeridos)
        const email = $('#email').val();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Por favor ingrese un correo electrónico válido');
            return;
        }
        
        // Validar archivo de imagen
        const fotoInput = $('#foto_perfil')[0];
        if (fotoInput.files.length > 0) {
            const file = fotoInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                alert('Solo se permiten archivos JPG, JPEG, PNG y WEBP');
                return;
            }
            
            if (file.size > maxSize) {
                alert('El archivo no debe superar los 5MB');
                return;
            }
        }
        
        // Mostrar loading
        const submitBtn = $('#btnGuardar');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Guardando...');
        
        // Enviar formulario via AJAX
        const formData = new FormData(this);
        
        $.ajax({
            url: 'actualizar_estudiante.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (data.success) {
                        alert(data.message);
                        $('#editarEstudianteModal').modal('hide');
                        location.reload();
                    } else {
                        alert(data.message || 'Error al actualizar el estudiante');
                    }
                } catch (e) {
                    alert('Error al procesar la respuesta del servidor');
                }
            },
            error: function(xhr, status, error) {
                alert('Ocurrió un error al procesar la solicitud: ' + error);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i>Guardar Cambios');
            }
        });
    });
});
</script>

<style>
.avatar-container {
    position: relative;
    display: inline-block;
}

.avatar-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.status-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.nav-tabs .nav-link {
    font-weight: 500;
    border: none;
    padding: 0.75rem 1rem;
}

.nav-tabs .nav-link.active {
    background-color: #f8f9fa;
    border-bottom: 3px solid #007bff;
}

.form-label.required::after {
    content: " *";
    color: #dc3545;
}

.tab-content {
    min-height: 400px;
}

/* Asegurar que las pestañas se vean bien en Bootstrap 4 */
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
}

.nav-tabs .nav-item {
    margin-bottom: -1px;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}

.nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

/* Mejorar espaciado de los botones */
.gap-2 > * {
    margin-left: 0.5rem;
}
.gap-2 > *:first-child {
    margin-left: 0;
}
</style>