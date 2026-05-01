<?php
// Obtener los datos necesarios para los select (si no se pasaron como parámetros)
if (!isset($tiposCedula)) {
    $tiposCedula = obtenerTiposCedula($db);
}
if (!isset($estadosCiviles)) {
    $estadosCiviles = obtenerEstadosCiviless($db);
}
if (!isset($tiposVivienda)) {
    $tiposVivienda = obtenerTiposVivienda($db);
}
if (!isset($tenenciasVivienda)) {
    $tenenciasVivienda = obtenerTenenciaViviendas($db);
}
if (!isset($opcionesStatus)) {
    $opcionesStatus = obtenerOpcionesStatus($db);
}
if (!isset($carreras)) {
    $carreras = obtenerTodasLasCarreras();
}
if (!isset($ingresos)) {
    $ingresos = obtenerIngresos($db);
}
if (!isset($estados)) {
    $estados = obtenerEstados($db);
}

// Determinar si estamos en modo modal
$esModal = isset($esModal) ? $esModal : false;
$modo_preinscripcion = isset($modo_preinscripcion) ? $modo_preinscripcion : false;
$formId = $esModal ? 'formEstudianteModal' : 'formEstudiante';
$prefijo = $esModal ? '_modal' : '';
$actionUrl = $modo_preinscripcion ? 'preinscripcion.php' : ($esModal ? 'procesar_estudiante.php' : htmlspecialchars($_SERVER["PHP_SELF"]));
$fechaSolicitud = date('Y-m-d');
?>

<form id="<?php echo $formId; ?>" method="post" action="<?php echo $actionUrl; ?>" enctype="multipart/form-data">
    <!-- Sección 1: Identificación -->
    <h5 class="mb-3"><i class="fas fa-id-card mr-2"></i> Identificación</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cedula_completa<?php echo $prefijo; ?>" class="form-label required">Cédula</label>
                <div class="input-group">
                    <select class="custom-select" id="tipo_cedula<?php echo $prefijo; ?>" name="tipo_cedula" style="max-width: 80px;">
                        <?php foreach ($tiposCedula as $tipo): ?>
                            <?php 
                            $tipoLetra = substr($tipo['tipo'], 0, 1);
                            ?>
                            <option value="<?php echo htmlspecialchars($tipoLetra); ?>" <?php echo (isset($_POST['tipo_cedula']) && $_POST['tipo_cedula'] === $tipoLetra) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipoLetra); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control" id="numero_cedula<?php echo $prefijo; ?>" name="numero_cedula" placeholder="Ej: 12345678" required value="<?php echo htmlspecialchars($_POST['numero_cedula'] ?? ''); ?>">
                    <input type="hidden" id="idusuario<?php echo $prefijo; ?>" name="idusuario" value="<?php echo htmlspecialchars($_POST['idusuario'] ?? ''); ?>">
                </div>
                <small class="text-muted">Formato: V-12345678 o E-12345678</small>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre<?php echo $prefijo; ?>" class="form-label required">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre<?php echo $prefijo; ?>" name="nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- Sección 2: Datos Personales (MODIFICADA - Etnia condicional) -->
    <h5 class="mb-3"><i class="fas fa-user-tag mr-2"></i> Datos Personales</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <!-- Foto de Perfil -->
            <div class="mb-3">
                <label for="foto_perfil<?php echo $prefijo; ?>" class="form-label">Foto de Perfil</label>
                <input type="file" class="form-control" id="foto_perfil<?php echo $prefijo; ?>" name="foto_perfil" 
                       accept=".jpg,.jpeg,.png,.pdf,.webp" 
                       onchange="previewImage(this, 'preview<?php echo $prefijo; ?>')">
                <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG, WEBP, PDF (Máx: 5MB)</small>
                <div id="preview<?php echo $prefijo; ?>" class="mt-2" style="display:none;">
                    <img id="previewImage<?php echo $prefijo; ?>" src="#" alt="Vista previa" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="fecha_nac<?php echo $prefijo; ?>" class="form-label required">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nac<?php echo $prefijo; ?>" name="fecha_nac" required value="<?php echo htmlspecialchars($_POST['fecha_nac'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label required">Género</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_m<?php echo $prefijo; ?>" value="Masculino" required <?php echo (isset($_POST['genero']) && $_POST['genero'] === 'Masculino') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="genero_m<?php echo $prefijo; ?>">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_f<?php echo $prefijo; ?>" value="Femenino" <?php echo (isset($_POST['genero']) && $_POST['genero'] === 'Femenino') ? 'checked' : ''; ?> >
                        <label class="form-check-label" for="genero_f<?php echo $prefijo; ?>">Femenino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_o<?php echo $prefijo; ?>" value="Otro" <?php echo (isset($_POST['genero']) && $_POST['genero'] === 'Otro') ? 'checked' : ''; ?> >
                        <label class="form-check-label" for="genero_o<?php echo $prefijo; ?>">Otro</label>
                    </div>
                </div>
            </div>

            <div class="mb-3" id="embarazoContainer<?php echo $prefijo; ?>" style="display: none;">
                <label class="form-label">¿Está embarazada?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="embarazada" id="embarazada_si<?php echo $prefijo; ?>" value="1" <?php echo (isset($_POST['embarazada']) && $_POST['embarazada'] == '1') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="embarazada_si<?php echo $prefijo; ?>">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="embarazada" id="embarazada_no<?php echo $prefijo; ?>" value="0" <?php echo (!isset($_POST['embarazada']) || $_POST['embarazada'] === '0') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="embarazada_no<?php echo $prefijo; ?>">No</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="edo_civil<?php echo $prefijo; ?>" class="form-label required">Estado Civil</label>
                <select class="custom-select" id="edo_civil<?php echo $prefijo; ?>" name="edo_civil" required>
                    <option value="" selected disabled>Seleccione una opción</option>
                    <?php foreach ($estadosCiviles as $id => $estadoCivil): ?>
                        <option value="<?php echo htmlspecialchars($estadoCivil); ?>" <?php echo (isset($_POST['edo_civil']) && $_POST['edo_civil'] === $estadoCivil) ? 'selected' : ''; ?>><?php echo htmlspecialchars($estadoCivil); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Etnia (Condicional) -->
            <div class="mb-3">
                <label class="form-label">¿Pertenece a alguna etnia?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_etnia" id="etnia_no<?php echo $prefijo; ?>" value="no" <?php echo (!isset($_POST['posee_etnia']) || $_POST['posee_etnia'] === 'no') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="etnia_no<?php echo $prefijo; ?>">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_etnia" id="etnia_si<?php echo $prefijo; ?>" value="si" <?php echo (isset($_POST['posee_etnia']) && $_POST['posee_etnia'] === 'si') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="etnia_si<?php echo $prefijo; ?>">Sí</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="etniaContainer<?php echo $prefijo; ?>" style="display: none;">
                <label for="etnia<?php echo $prefijo; ?>" class="form-label">Especifique la etnia</label>
                <input type="text" class="form-control" id="etnia<?php echo $prefijo; ?>" name="etnia" placeholder="Ej: Wayúu, Añú, etc." value="<?php echo htmlspecialchars($_POST['etnia'] ?? ''); ?>">
                <small class="text-muted">Indique el nombre de la etnia a la que pertenece</small>
            </div>
        </div>
    </div>

    <!-- Sección 3: Formación Académica -->
    <h5 class="mb-3"><i class="fas fa-graduation-cap mr-2"></i> Formación Académica</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="carrera<?php echo $prefijo; ?>" class="form-label required">Programa</label>
                <select name="carrera" id="carrera<?php echo $prefijo; ?>" class="form-control" required>
                    <option value="">-- Seleccione una carrera --</option>
                    <?php foreach ($carreras as $carrera): ?>
                        <option value="<?php echo htmlspecialchars($carrera['id']); ?>" <?php echo (isset($_POST['carrera']) && $_POST['carrera'] == $carrera['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($carrera['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Títulos Obtenidos e Instituciones -->
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Títulos Obtenidos e Instituciones</label>
                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="titulos<?php echo $prefijo; ?>" 
                               placeholder="Título obtenido">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="institutos<?php echo $prefijo; ?>" 
                               placeholder="Institución donde obtuvo el título">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-primary w-100" id="addTituloInstituto<?php echo $prefijo; ?>">
                            <i class="fas fa-plus mr-1"></i> Agregar
                        </button>
                    </div>
                </div>
                <div id="titulosInstitutosContainer<?php echo $prefijo; ?>">
                    <!-- Aquí se agregarán los pares de títulos e instituciones -->
                </div>
            </div>
        </div>
    </div>

    <!-- Sección 4: Ubicación y Vivienda -->
    <h5 class="mb-3"><i class="fas fa-home mr-2"></i> Vivienda</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="estado<?php echo $prefijo; ?>" class="form-label required">Estado</label>
                <select class="form-control" id="estado<?php echo $prefijo; ?>" name="estado" required>
                    <option value="">Seleccione un estado</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?php echo htmlspecialchars($estado['id_estado']); ?>" <?php echo (isset($_POST['estado']) && $_POST['estado'] == $estado['id_estado']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($estado['estado']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="municipio<?php echo $prefijo; ?>" class="form-label required">Municipio</label>
                <select class="form-control" id="municipio<?php echo $prefijo; ?>" name="municipio" required disabled>
                    <option value="">Primero seleccione un estado</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="parroquia<?php echo $prefijo; ?>" class="form-label">Parroquia</label>
                <select class="form-control" id="parroquia<?php echo $prefijo; ?>" name="parroquia" disabled>
                    <option value="">Primero seleccione un municipio</option>
                </select>
            </div>
            
            <!-- Campos ocultos para los nombres -->
            <input type="hidden" id="nombre_estado<?php echo $prefijo; ?>" name="nombre_estado">
            <input type="hidden" id="nombre_municipio<?php echo $prefijo; ?>" name="nombre_municipio">
            <input type="hidden" id="nombre_parroquia<?php echo $prefijo; ?>" name="nombre_parroquia">
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="direccion<?php echo $prefijo; ?>" class="form-label required">Dirección</label>
                <textarea class="form-control" id="direccion<?php echo $prefijo; ?>" name="direccion" rows="2" required><?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="casaapto<?php echo $prefijo; ?>" class="form-label">Tipo de Vivienda</label>
                <select class="form-control" id="casaapto<?php echo $prefijo; ?>" name="casaapto">
                    <option value="">Seleccione...</option>
                    <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                        <option value="<?php echo htmlspecialchars($vivienda); ?>" <?php echo (isset($_POST['casaapto']) && $_POST['casaapto'] === $vivienda) ? 'selected' : ''; ?>><?php echo htmlspecialchars($vivienda); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="punto_referencia<?php echo $prefijo; ?>" class="form-label">Punto de Referencia</label>
                <input type="text" class="form-control" id="punto_referencia<?php echo $prefijo; ?>" name="punto_referencia" value="<?php echo htmlspecialchars($_POST['punto_referencia'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- Sección 5: Situación Familiar -->
    <h5 class="mb-3"><i class="fas fa-users mr-2"></i> Situación Familiar</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="grupo_familiar<?php echo $prefijo; ?>" class="form-label">Grupo Familiar</label>
                <input type="number" class="form-control" id="grupo_familiar<?php echo $prefijo; ?>" name="grupo_familiar" min="1" placeholder="Número de personas">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="acargo_usted<?php echo $prefijo; ?>" class="form-label">Personas a su cargo</label>
                <input type="number" class="form-control" id="acargo_usted<?php echo $prefijo; ?>" name="acargo_usted" min="0">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="fuente_ingresos<?php echo $prefijo; ?>" class="form-label">Fuente de Ingresos</label>
                <select class="custom-select d-block w-100" id="fuente_ingresos<?php echo $prefijo; ?>" name="fuente_ingresos">
                    <option value="">Seleccione una opción</option>
                    <?php foreach ($ingresos as $id => $ingreso): ?>
                        <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($ingreso); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tenencia_vivienda<?php echo $prefijo; ?>" class="form-label">Tenencia de Vivienda</label>
                <select class="form-control" id="tenencia_vivienda<?php echo $prefijo; ?>" name="tenencia_vivienda">
                    <option value="">Seleccione...</option>
                    <?php foreach ($tenenciasVivienda as $id => $tenencia): ?>
                        <option value="<?php echo htmlspecialchars($tenencia); ?>"><?php echo htmlspecialchars($tenencia); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Sección 6: Salud (REORGANIZADA COMPLETAMENTE) -->
    <h5 class="mb-3"><i class="fas fa-heartbeat mr-2"></i> Salud</h5>
    <div class="row g-3 mb-4">
        <!-- Discapacidad (Condicional) -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">¿Posee alguna discapacidad?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_discapacidad" id="discapacidad_no<?php echo $prefijo; ?>" value="no" <?php echo (!isset($_POST['posee_discapacidad']) || $_POST['posee_discapacidad'] === 'no') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="discapacidad_no<?php echo $prefijo; ?>">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_discapacidad" id="discapacidad_si<?php echo $prefijo; ?>" value="si" <?php echo (isset($_POST['posee_discapacidad']) && $_POST['posee_discapacidad'] === 'si') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="discapacidad_si<?php echo $prefijo; ?>">Sí</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="discapacidadContainer<?php echo $prefijo; ?>" style="display: none;">
                <label for="discapacidad<?php echo $prefijo; ?>" class="form-label">Especifique el tipo de discapacidad</label>
                <input type="text" class="form-control" id="discapacidad<?php echo $prefijo; ?>" name="discapacidad" placeholder="Ej: Visual, Auditiva, Motora, etc.">
                <small class="text-muted">Indique el tipo de discapacidad que posee</small>
            </div>
        </div>
        
        <!-- Enfermedad (Condicional) -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">¿Posee alguna enfermedad?</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_enfermedad" id="enfermedad_no<?php echo $prefijo; ?>" value="no" <?php echo (!isset($_POST['posee_enfermedad']) || $_POST['posee_enfermedad'] === 'no') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="enfermedad_no<?php echo $prefijo; ?>">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="posee_enfermedad" id="enfermedad_si<?php echo $prefijo; ?>" value="si" <?php echo (isset($_POST['posee_enfermedad']) && $_POST['posee_enfermedad'] === 'si') ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="enfermedad_si<?php echo $prefijo; ?>">Sí</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="enfermedadContainer<?php echo $prefijo; ?>" style="display: none;">
                <label for="enfermedad<?php echo $prefijo; ?>" class="form-label">Especifique la(s) enfermedad(es)</label>
                <input type="text" class="form-control" id="enfermedad<?php echo $prefijo; ?>" name="enfermedad" placeholder="Ej: Diabetes, Hipertensión, etc.">
                <small class="text-muted">Puede indicar múltiples enfermedades separadas por coma</small>
            </div>
        </div>
    </div>

    <!-- Sección 7: Contacto -->
    <h5 class="mb-3"><i class="fas fa-address-book mr-2"></i> Contacto</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tlf<?php echo $prefijo; ?>" class="form-label required">Teléfono Principal</label>
                <input type="tel" class="form-control" id="tlf<?php echo $prefijo; ?>" name="tlf" required value="<?php echo htmlspecialchars($_POST['tlf'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="cel<?php echo $prefijo; ?>" class="form-label">Teléfono Celular</label>
                <input type="tel" class="form-control" id="cel<?php echo $prefijo; ?>" name="cel" value="<?php echo htmlspecialchars($_POST['cel'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email<?php echo $prefijo; ?>" class="form-label required">Correo Electrónico</label>
                <input type="email" class="form-control" id="email<?php echo $prefijo; ?>" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="num_telf_opc<?php echo $prefijo; ?>" class="form-label">Teléfono Opcional</label>
                <input type="tel" class="form-control" id="num_telf_opc<?php echo $prefijo; ?>" name="num_telf_opc" value="<?php echo htmlspecialchars($_POST['num_telf_opc'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- Sección 8: Datos del Sistema -->
    <h5 class="mb-3"><i class="fas fa-university mr-2"></i> Datos del Sistema</h5>
    <div class="row g-3">
        <?php if (!$modo_preinscripcion): ?>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="fecha_ingreso<?php echo $prefijo; ?>" class="form-label required">Fecha de Ingreso</label>
                    <input type="date" class="form-control" id="fecha_ingreso<?php echo $prefijo; ?>" name="fecha_ingreso" value="<?php echo htmlspecialchars($_POST['fecha_ingreso'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="status<?php echo $prefijo; ?>" class="form-label required">Status</label>
                    <select class="custom-select" id="status<?php echo $prefijo; ?>" name="status" required>
                        <option value="" selected disabled>Seleccione un status</option>
                        <?php foreach ($opcionesStatus as $valor => $texto): ?>
                            <option value="<?php echo htmlspecialchars($valor); ?>" <?php echo (isset($_POST['status']) && $_POST['status'] == $valor) ? 'selected' : ''; ?>><?php echo htmlspecialchars($texto); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php else: ?>
            <input type="hidden" name="fecha_ingreso" value="<?php echo htmlspecialchars($_POST['fecha_ingreso'] ?? $fechaSolicitud); ?>">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($_POST['status'] ?? 'Pendiente'); ?>">
            <div class="col-md-12">
                <div class="alert alert-info mb-0">
                    Su preinscripción se registrará con fecha de solicitud <strong><?php echo htmlspecialchars($_POST['fecha_ingreso'] ?? $fechaSolicitud); ?></strong> y quedará como <strong>Pendiente</strong> hasta revisión administrativa.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <?php if ($esModal): ?>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
        <?php else: ?>
            <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Regresar
            </button>
        <?php endif; ?>
        
        <div>
            <button type="reset" class="btn btn-secondary mr-2">
                <i class="fas fa-eraser mr-1"></i> Limpiar
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar
            </button>
        </div>
    </div>
</form>

<script>
// Script para manejar la adición de campos de título e institución juntos
document.addEventListener('DOMContentLoaded', function() {
    function addTitleInstitutionPair(prefix) {
        const titulo = document.getElementById('titulos' + prefix).value.trim();
        const instituto = document.getElementById('institutos' + prefix).value.trim();
        
        if(titulo === '' || instituto === '') {
            alert('Por favor complete ambos campos: título e institución');
            return;
        }
        
        const container = document.getElementById('titulosInstitutosContainer' + prefix);
        
        const newPair = document.createElement('div');
        newPair.className = 'row g-3 mb-3';
        newPair.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="titulos[]" 
                       value="${titulo}" placeholder="Título obtenido" readonly>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="institutos[]" 
                       value="${instituto}" placeholder="Institución" readonly>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger remove-field w-100">
                    <i class="fas fa-minus"></i> Eliminar
                </button>
            </div>
        `;
        container.appendChild(newPair);
        
        // Vaciar los campos principales
        document.getElementById('titulos' + prefix).value = '';
        document.getElementById('institutos' + prefix).value = '';
        document.getElementById('titulos' + prefix).focus();
        
        // Añadir evento para eliminar el par
        newPair.querySelector('.remove-field').addEventListener('click', function() {
            container.removeChild(newPair);
        });
    }
    
    // Evento para el botón de añadir
    const addButton = document.getElementById('addTituloInstituto' + '<?php echo $prefijo; ?>');
    if (addButton) {
        addButton.addEventListener('click', function() {
            addTitleInstitutionPair('<?php echo $prefijo; ?>');
        });
    }
    
    // Manejar el evento Enter en los campos principales
    const titulosField = document.getElementById('titulos' + '<?php echo $prefijo; ?>');
    const institutosField = document.getElementById('institutos' + '<?php echo $prefijo; ?>');
    
    if (titulosField) {
        titulosField.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                addTitleInstitutionPair('<?php echo $prefijo; ?>');
            }
        });
    }
    
    if (institutosField) {
        institutosField.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                e.preventDefault();
                addTitleInstitutionPair('<?php echo $prefijo; ?>');
            }
        });
    }
});

// Manejo del campo de cédula
document.addEventListener('DOMContentLoaded', function() {
    const tipoCedula = document.getElementById('tipo_cedula<?php echo $prefijo; ?>');
    const numeroCedula = document.getElementById('numero_cedula<?php echo $prefijo; ?>');
    const idUsuario = document.getElementById('idusuario<?php echo $prefijo; ?>');
    
    if (tipoCedula && numeroCedula && idUsuario) {
        function actualizarCedulaCompleta() {
            const numeroLimpio = numeroCedula.value.replace(/[^0-9]/g, '');
            numeroCedula.value = numeroLimpio;
            idUsuario.value = tipoCedula.value + '-' + numeroLimpio;
        }
        
        tipoCedula.addEventListener('change', actualizarCedulaCompleta);
        numeroCedula.addEventListener('input', actualizarCedulaCompleta);
        actualizarCedulaCompleta();
    }
});

// Función para vista previa de imagen
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImage = document.getElementById('previewImage' + '<?php echo $prefijo; ?>');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type;
        
        // Validar tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'application/pdf'];
        if (!allowedTypes.includes(fileType)) {
            alert('Error: Solo se permiten archivos JPG, JPEG, PNG, WEBP y PDF.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validar tamaño (5MB máximo)
        if (file.size > 5 * 1024 * 1024) {
            alert('Error: El archivo no debe superar los 5MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Mostrar vista previa solo para imágenes
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            // Para PDF, mostrar mensaje
            preview.innerHTML = '<div class="alert alert-info p-2">Archivo PDF seleccionado</div>';
            preview.style.display = 'block';
        }
    } else {
        preview.style.display = 'none';
    }
}

// =============================================
// SCRIPT PARA SELECTS AUTOMÁTICOS DE UBICACIÓN
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    const prefijo = '<?php echo $prefijo; ?>';
    const estadoSelect = document.getElementById('estado' + prefijo);
    const municipioSelect = document.getElementById('municipio' + prefijo);
    const parroquiaSelect = document.getElementById('parroquia' + prefijo);
    const apiBase = '<?php echo isset($modo_preinscripcion) && $modo_preinscripcion ? 'admin/' : ''; ?>';
    
    // Campos ocultos para nombres
    const nombreEstadoInput = document.getElementById('nombre_estado' + prefijo);
    const nombreMunicipioInput = document.getElementById('nombre_municipio' + prefijo);
    const nombreParroquiaInput = document.getElementById('nombre_parroquia' + prefijo);
    
    if (!estadoSelect || !municipioSelect || !parroquiaSelect) {
        console.error('No se encontraron los selects de ubicación');
        return;
    }
    
    // ========== EVENTO: Cambio de Estado ==========
    estadoSelect.addEventListener('change', function() {
        const estadoId = this.value;
        const estadoTexto = this.options[this.selectedIndex].text;
        
        // Guardar nombre en campo oculto
        if (nombreEstadoInput) {
            nombreEstadoInput.value = estadoTexto;
        }
        
        // Resetear municipio y parroquia
        resetSelect(municipioSelect, 'Cargando municipios...', true);
        resetSelect(parroquiaSelect, 'Primero seleccione un municipio', true);
        
        if (!estadoId) {
            resetSelect(municipioSelect, 'Primero seleccione un estado', true);
            return;
        }
        
        // Cargar municipios del estado seleccionado
        cargarMunicipios(estadoId);
    });
    
    // ========== EVENTO: Cambio de Municipio ==========
    municipioSelect.addEventListener('change', function() {
        const municipioId = this.value;
        const municipioTexto = this.options[this.selectedIndex].text;
        
        // Guardar nombre en campo oculto
        if (nombreMunicipioInput) {
            nombreMunicipioInput.value = municipioTexto;
        }
        
        // Resetear parroquia
        resetSelect(parroquiaSelect, 'Cargando parroquias...', true);
        
        if (!municipioId) {
            resetSelect(parroquiaSelect, 'Primero seleccione un municipio', true);
            return;
        }
        
        // Cargar parroquias del municipio seleccionado
        cargarParroquias(municipioId);
    });
    
    // ========== EVENTO: Cambio de Parroquia ==========
    parroquiaSelect.addEventListener('change', function() {
        const parroquiaTexto = this.options[this.selectedIndex].text;
        
        // Guardar nombre en campo oculto
        if (nombreParroquiaInput) {
            nombreParroquiaInput.value = parroquiaTexto;
        }
    });
    
    // ========== FUNCIÓN: Cargar Municipios ==========
    function cargarMunicipios(estadoId, selectedMunicipioId = '', selectedParroquiaId = '') {
        const formData = new FormData();
        formData.append('estado_id', estadoId);
        
        fetch(apiBase + 'api/obtener_municipios.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.municipios) {
                updateSelect(municipioSelect, data.municipios, 'Seleccione un municipio', false);
                municipioSelect.disabled = false;
                if (selectedMunicipioId) {
                    municipioSelect.value = selectedMunicipioId;
                    if (municipioSelect.value === selectedMunicipioId && nombreMunicipioInput) {
                        nombreMunicipioInput.value = municipioSelect.options[municipioSelect.selectedIndex]?.text || '';
                    }
                    if (selectedMunicipioId) {
                        cargarParroquias(selectedMunicipioId, selectedParroquiaId);
                    }
                }
            } else {
                resetSelect(municipioSelect, 'No hay municipios disponibles', false);
                municipioSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error al cargar municipios:', error);
            resetSelect(municipioSelect, 'Error al cargar municipios', true);
            
            cargarMunicipiosAlternativo(estadoId, selectedMunicipioId, selectedParroquiaId);
        });
    }
    
    // Método alternativo GET
    function cargarMunicipiosAlternativo(estadoId, selectedMunicipioId = '', selectedParroquiaId = '') {
        fetch(apiBase + 'api/obtener_municipios.php?estado_id=' + estadoId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.municipios) {
                updateSelect(municipioSelect, data.municipios, 'Seleccione un municipio', false);
                municipioSelect.disabled = false;
                if (selectedMunicipioId) {
                    municipioSelect.value = selectedMunicipioId;
                    if (municipioSelect.value === selectedMunicipioId && nombreMunicipioInput) {
                        nombreMunicipioInput.value = municipioSelect.options[municipioSelect.selectedIndex]?.text || '';
                    }
                    if (selectedMunicipioId) {
                        cargarParroquias(selectedMunicipioId, selectedParroquiaId);
                    }
                }
            } else {
                resetSelect(municipioSelect, 'No hay municipios disponibles', false);
                municipioSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error alternativo:', error);
            resetSelect(municipioSelect, 'Error de conexión', true);
        });
    }
    
    // ========== FUNCIÓN: Cargar Parroquias ==========
    function cargarParroquias(municipioId, selectedParroquiaId = '') {
        const formData = new FormData();
        formData.append('municipio_id', municipioId);
        
        fetch(apiBase + 'api/obtener_parroquias.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.parroquias && data.parroquias.length > 0) {
                updateSelect(parroquiaSelect, data.parroquias, 'Seleccione una parroquia (opcional)', false);
                parroquiaSelect.disabled = false;
                if (selectedParroquiaId) {
                    parroquiaSelect.value = selectedParroquiaId;
                    if (parroquiaSelect.value === selectedParroquiaId && nombreParroquiaInput) {
                        nombreParroquiaInput.value = parroquiaSelect.options[parroquiaSelect.selectedIndex]?.text || '';
                    }
                }
            } else {
                resetSelect(parroquiaSelect, 'No hay parroquias disponibles', false);
                parroquiaSelect.disabled = false;
                parroquiaSelect.value = '';
            }
        })
        .catch(error => {
            console.error('Error al cargar parroquias:', error);
            resetSelect(parroquiaSelect, 'Error de conexión', true);
            
            fetch(apiBase + 'api/obtener_parroquias.php?municipio_id=' + municipioId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.parroquias) {
                    updateSelect(parroquiaSelect, data.parroquias, 'Seleccione una parroquia (opcional)', false);
                    parroquiaSelect.disabled = false;
                    if (selectedParroquiaId) {
                        parroquiaSelect.value = selectedParroquiaId;
                        if (parroquiaSelect.value === selectedParroquiaId && nombreParroquiaInput) {
                            nombreParroquiaInput.value = parroquiaSelect.options[parroquiaSelect.selectedIndex]?.text || '';
                        }
                    }
                } else {
                    resetSelect(parroquiaSelect, 'No hay parroquias disponibles', false);
                    parroquiaSelect.disabled = false;
                }
            })
            .catch(error2 => {
                console.error('Error alternativo:', error2);
                resetSelect(parroquiaSelect, 'Error de conexión', true);
            });
        });
    }
    
    // ========== FUNCIÓN: Resetear Select ==========
    function resetSelect(selectElement, placeholder, disabled) {
        if (!selectElement) return;
        
        selectElement.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        selectElement.appendChild(option);
        
        selectElement.disabled = disabled;
        selectElement.value = '';
    }
    
    // ========== FUNCIÓN: Actualizar Opciones del Select ==========
    function updateSelect(selectElement, options, placeholder, disabled) {
        if (!selectElement) return;
        
        // Limpiar opciones actuales
        selectElement.innerHTML = '';
        
        // Agregar placeholder
        const optionPlaceholder = document.createElement('option');
        optionPlaceholder.value = '';
        optionPlaceholder.textContent = placeholder;
        selectElement.appendChild(optionPlaceholder);
        
        // Agregar opciones
        options.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option.id;
            opt.textContent = option.nombre;
            selectElement.appendChild(opt);
        });
        
        selectElement.disabled = disabled;
    }
    
    // ========== FUNCIÓN: Cargar Datos si Estamos Editando O Reenviando ==========
    function cargarDatosUbicacionSiExisten() {
        const selectedEstadoId = <?php echo json_encode($_POST['estado'] ?? ''); ?>;
        const selectedMunicipioId = <?php echo json_encode($_POST['municipio'] ?? ''); ?>;
        const selectedParroquiaId = <?php echo json_encode($_POST['parroquia'] ?? ''); ?>;

        if (!selectedEstadoId) {
            return;
        }

        estadoSelect.value = selectedEstadoId;
        if (nombreEstadoInput) {
            nombreEstadoInput.value = estadoSelect.options[estadoSelect.selectedIndex]?.text || '';
        }

        cargarMunicipios(selectedEstadoId, selectedMunicipioId, selectedParroquiaId);
    }
    
    cargarDatosUbicacionSiExisten();
});

// =============================================
// SCRIPT PARA MANEJO CONDICIONAL DE ETNIA, DISCAPACIDAD Y ENFERMEDAD
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    const prefijo = '<?php echo $prefijo; ?>';
    
    // ===== Manejo condicional de Etnia =====
    const radioEtniaSi = document.getElementById('etnia_si' + prefijo);
    const radioEtniaNo = document.getElementById('etnia_no' + prefijo);
    const etniaContainer = document.getElementById('etniaContainer' + prefijo);
    const campoEtnia = document.getElementById('etnia' + prefijo);
    
    if (radioEtniaSi && radioEtniaNo && etniaContainer && campoEtnia) {
        function toggleEtniaField() {
            if (radioEtniaSi.checked) {
                etniaContainer.style.display = 'block';
                campoEtnia.setAttribute('required', 'required');
            } else {
                etniaContainer.style.display = 'none';
                campoEtnia.removeAttribute('required');
                campoEtnia.value = '';
            }
        }
        
        toggleEtniaField();
        radioEtniaSi.addEventListener('change', toggleEtniaField);
        radioEtniaNo.addEventListener('change', toggleEtniaField);
    }
    
    // ===== Manejo condicional de Discapacidad =====
    const radioDiscSi = document.getElementById('discapacidad_si' + prefijo);
    const radioDiscNo = document.getElementById('discapacidad_no' + prefijo);
    const discContainer = document.getElementById('discapacidadContainer' + prefijo);
    const campoDisc = document.getElementById('discapacidad' + prefijo);
    
    if (radioDiscSi && radioDiscNo && discContainer && campoDisc) {
        function toggleDiscapacidadField() {
            if (radioDiscSi.checked) {
                discContainer.style.display = 'block';
                campoDisc.setAttribute('required', 'required');
            } else {
                discContainer.style.display = 'none';
                campoDisc.removeAttribute('required');
                campoDisc.value = '';
            }
        }
        
        toggleDiscapacidadField();
        radioDiscSi.addEventListener('change', toggleDiscapacidadField);
        radioDiscNo.addEventListener('change', toggleDiscapacidadField);
    }
    
    // ===== Manejo condicional de Enfermedad =====
    const radioEnfSi = document.getElementById('enfermedad_si' + prefijo);
    const radioEnfNo = document.getElementById('enfermedad_no' + prefijo);
    const enfContainer = document.getElementById('enfermedadContainer' + prefijo);
    const campoEnf = document.getElementById('enfermedad' + prefijo);
    
    if (radioEnfSi && radioEnfNo && enfContainer && campoEnf) {
        function toggleEnfermedadField() {
            if (radioEnfSi.checked) {
                enfContainer.style.display = 'block';
                campoEnf.setAttribute('required', 'required');
            } else {
                enfContainer.style.display = 'none';
                campoEnf.removeAttribute('required');
                campoEnf.value = '';
            }
        }
        
        toggleEnfermedadField();
        radioEnfSi.addEventListener('change', toggleEnfermedadField);
        radioEnfNo.addEventListener('change', toggleEnfermedadField);
    }

    // ===== Manejo condicional de Embarazo =====
    const radioGeneroMasculino = document.getElementById('genero_m' + prefijo);
    const radioGeneroFemenino = document.getElementById('genero_f' + prefijo);
    const radioGeneroOtro = document.getElementById('genero_o' + prefijo);
    const embarazoContainer = document.getElementById('embarazoContainer' + prefijo);
    const embarazoSi = document.getElementById('embarazada_si' + prefijo);
    const embarazoNo = document.getElementById('embarazada_no' + prefijo);

    function toggleEmbarazoField() {
        if (radioGeneroFemenino && radioGeneroFemenino.checked) {
            if (embarazoContainer) {
                embarazoContainer.style.display = 'block';
            }
            if (embarazoSi) embarazoSi.setAttribute('required', 'required');
            if (embarazoNo) embarazoNo.setAttribute('required', 'required');
        } else {
            if (embarazoContainer) {
                embarazoContainer.style.display = 'none';
            }
            if (embarazoSi) {
                embarazoSi.removeAttribute('required');
                embarazoSi.checked = false;
            }
            if (embarazoNo) {
                embarazoNo.removeAttribute('required');
                embarazoNo.checked = true;
            }
        }
    }

    if (radioGeneroMasculino) radioGeneroMasculino.addEventListener('change', toggleEmbarazoField);
    if (radioGeneroFemenino) radioGeneroFemenino.addEventListener('change', toggleEmbarazoField);
    if (radioGeneroOtro) radioGeneroOtro.addEventListener('change', toggleEmbarazoField);
    toggleEmbarazoField();
});

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('<?php echo $formId; ?>');
    
    if (!form) return;
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        if (validarFormulario()) {
            // Si la validación pasa, enviar el formulario
            form.submit();
        }
    });
    
    function validarFormulario() {
        let isValid = true;
        let mensajesError = [];
        
        // Obtener el prefijo
        const prefijo = '<?php echo $prefijo; ?>';
        
        // Validar cédula
        const numeroCedula = document.getElementById('numero_cedula' + prefijo).value;
        if (!/^\d{6,9}$/.test(numeroCedula)) {
            mensajesError.push('La cédula debe contener entre 6 y 9 dígitos numéricos');
            isValid = false;
        }
        
        // Validar nombre
        const nombre = document.getElementById('nombre' + prefijo).value;
        if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s']+$/.test(nombre.trim())) {
            mensajesError.push('El nombre solo puede contener letras, espacios y apóstrofes');
            isValid = false;
        }
        
        // Validar estado
        const estado = document.getElementById('estado' + prefijo).value;
        if (!estado) {
            mensajesError.push('Debe seleccionar un estado');
            isValid = false;
        }
        
        // Validar municipio
        const municipio = document.getElementById('municipio' + prefijo).value;
        if (!municipio) {
            mensajesError.push('Debe seleccionar un municipio');
            isValid = false;
        }
        
        // Validar teléfono
        const telefono = document.getElementById('tlf' + prefijo).value;
        if (!/^[0-9]{10,11}$/.test(telefono.replace(/\D/g, ''))) {
            mensajesError.push('El teléfono debe contener 10 u 11 dígitos');
            isValid = false;
        }
        
        // Validar email
        const email = document.getElementById('email' + prefijo).value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            mensajesError.push('Ingrese un correo electrónico válido');
            isValid = false;
        }
        
        // Validar que la fecha de nacimiento sea válida
        const fechaNac = document.getElementById('fecha_nac' + prefijo).value;
        if (fechaNac) {
            const fechaNacDate = new Date(fechaNac);
            const hoy = new Date();
            if (fechaNacDate > hoy) {
                mensajesError.push('La fecha de nacimiento no puede ser futura');
                isValid = false;
            }
            
            // Calcular edad mínima (15 años)
            const edadMinima = new Date();
            edadMinima.setFullYear(edadMinima.getFullYear() - 15);
            if (fechaNacDate > edadMinima) {
                mensajesError.push('El estudiante debe tener al menos 15 años');
                isValid = false;
            }
        }
        
        // Validar fecha de ingreso
        const fechaIngreso = document.getElementById('fecha_ingreso' + prefijo).value;
        if (fechaNac && fechaIngreso) {
            const fechaNacDate = new Date(fechaNac);
            const fechaIngresoDate = new Date(fechaIngreso);
            if (fechaIngresoDate < fechaNacDate) {
                mensajesError.push('La fecha de ingreso no puede ser anterior a la fecha de nacimiento');
                isValid = false;
            }
        }
        
        // Validar campo de etnia si se seleccionó "Sí"
        const radioEtniaSi = document.getElementById('etnia_si' + prefijo);
        const campoEtnia = document.getElementById('etnia' + prefijo);
        
        if (radioEtniaSi && radioEtniaSi.checked && campoEtnia) {
            if (!campoEtnia.value.trim()) {
                mensajesError.push('Debe especificar la etnia si seleccionó "Sí"');
                isValid = false;
            }
        }
        
        // Validar campo de discapacidad si se seleccionó "Sí"
        const radioDiscSi = document.getElementById('discapacidad_si' + prefijo);
        const campoDisc = document.getElementById('discapacidad' + prefijo);
        
        if (radioDiscSi && radioDiscSi.checked && campoDisc) {
            if (!campoDisc.value.trim()) {
                mensajesError.push('Debe especificar el tipo de discapacidad si seleccionó "Sí"');
                isValid = false;
            }
        }
        
        // Validar campo de enfermedad si se seleccionó "Sí"
        const radioEnfSi = document.getElementById('enfermedad_si' + prefijo);
        const campoEnf = document.getElementById('enfermedad' + prefijo);
        
        if (radioEnfSi && radioEnfSi.checked && campoEnf) {
            if (!campoEnf.value.trim()) {
                mensajesError.push('Debe especificar la(s) enfermedad(es) si seleccionó "Sí"');
                isValid = false;
            }
        }

        // Validar campo de embarazo si el género es Femenino
        const radioGeneroFemenino = document.getElementById('genero_f' + prefijo);
        const embarazoSi = document.getElementById('embarazada_si' + prefijo);
        const embarazoNo = document.getElementById('embarazada_no' + prefijo);

        if (radioGeneroFemenino && radioGeneroFemenino.checked) {
            if (embarazoSi && embarazoNo && !embarazoSi.checked && !embarazoNo.checked) {
                mensajesError.push('Debe indicar si la estudiante está embarazada o no');
                isValid = false;
            }
        }
        
        // Mostrar errores si existen
        if (mensajesError.length > 0) {
            mostrarErrores(mensajesError);
        }
        
        return isValid;
    }
    
    function mostrarErrores(errores) {
        // Crear o actualizar contenedor de errores
        let errorContainer = document.getElementById('errorContainer');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'errorContainer';
            errorContainer.className = 'alert alert-danger';
            form.prepend(errorContainer);
        }
        
        // Limpiar contenido anterior
        errorContainer.innerHTML = '';
        
        // Agregar título
        const titulo = document.createElement('strong');
        titulo.textContent = 'Por favor corrija los siguientes errores:';
        errorContainer.appendChild(titulo);
        
        // Agregar lista de errores
        const lista = document.createElement('ul');
        lista.className = 'mb-0 mt-2';
        
        errores.forEach(error => {
            const item = document.createElement('li');
            item.textContent = error;
            lista.appendChild(item);
        });
        
        errorContainer.appendChild(lista);
        
        // Desplazar al inicio del formulario
        errorContainer.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Limpiar errores al cambiar campos
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            const errorContainer = document.getElementById('errorContainer');
            if (errorContainer) {
                errorContainer.remove();
            }
        });
    });
});
</script>