<?php
// _formulario_estudiante.php
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

// Determinar si estamos en modo modal
$esModal = isset($esModal) ? $esModal : false;
$formId = $esModal ? 'formEstudianteModal' : 'formEstudiante';
$prefijo = $esModal ? '_modal' : '';
$actionUrl = $esModal ? 'procesar_estudiante.php' : htmlspecialchars($_SERVER["PHP_SELF"]);
?>

<form id="<?php echo $formId; ?>" method="post" action="<?php echo $actionUrl; ?>"<?php echo $esModal ? ' enctype="multipart/form-data"' : ''; ?>>
    <!-- Sección 1: Identificación -->
    <h5 class="mb-3"><i class="fas fa-id-card mr-2"></i> Identificación</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre<?php echo $prefijo; ?>" class="form-label required">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre<?php echo $prefijo; ?>" name="nombre" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="cedula_completa<?php echo $prefijo; ?>" class="form-label required">Cédula</label>
                <div class="input-group">
                    <select class="custom-select" id="tipo_cedula<?php echo $prefijo; ?>" name="tipo_cedula" style="max-width: 80px;">
                        <?php foreach ($tiposCedula as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo['tipo']); ?>">
                                <?php echo htmlspecialchars($tipo['tipo']); ?>-
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control" id="numero_cedula<?php echo $prefijo; ?>" name="numero_cedula" placeholder="Ej: 12345678" required>
                    <input type="hidden" id="idusuario<?php echo $prefijo; ?>" name="idusuario">
                </div>
                <small class="text-muted">Formato: V-12345678 o E-12345678</small>
            </div>
        </div>
    </div>

    <!-- Sección 2: Datos Personales -->
    <h5 class="mb-3"><i class="fas fa-user-tag mr-2"></i> Datos Personales</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="fecha_nac<?php echo $prefijo; ?>" class="form-label required">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nac<?php echo $prefijo; ?>" name="fecha_nac" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label required">Género</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_m<?php echo $prefijo; ?>" value="Masculino" required>
                        <label class="form-check-label" for="genero_m<?php echo $prefijo; ?>">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_f<?php echo $prefijo; ?>" value="Femenino">
                        <label class="form-check-label" for="genero_f<?php echo $prefijo; ?>">Femenino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="genero" id="genero_o<?php echo $prefijo; ?>" value="Otro">
                        <label class="form-check-label" for="genero_o<?php echo $prefijo; ?>">Otro</label>
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
                        <option value="<?php echo htmlspecialchars($estadoCivil); ?>"><?php echo htmlspecialchars($estadoCivil); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="etnia<?php echo $prefijo; ?>" class="form-label">Etnia</label>
                <input type="text" class="form-control" id="etnia<?php echo $prefijo; ?>" name="etnia">
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
                        <option value="<?php echo htmlspecialchars($carrera['id']); ?>">
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
                <input type="text" class="form-control" id="estado<?php echo $prefijo; ?>" name="estado" required>
            </div>
            
            <div class="mb-3">
                <label for="municipio<?php echo $prefijo; ?>" class="form-label required">Municipio</label>
                <input type="text" class="form-control" id="municipio<?php echo $prefijo; ?>" name="municipio" required>
            </div>
            
            <div class="mb-3">
                <label for="parroquia<?php echo $prefijo; ?>" class="form-label">Parroquia</label>
                <input type="text" class="form-control" id="parroquia<?php echo $prefijo; ?>" name="parroquia">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="direccion<?php echo $prefijo; ?>" class="form-label required">Dirección</label>
                <textarea class="form-control" id="direccion<?php echo $prefijo; ?>" name="direccion" rows="2" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="casaapto<?php echo $prefijo; ?>" class="form-label">Tipo de Vivienda</label>
                <select class="form-control" id="casaapto<?php echo $prefijo; ?>" name="casaapto">
                    <option value="">Seleccione...</option>
                    <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                        <option value="<?php echo htmlspecialchars($vivienda); ?>"><?php echo htmlspecialchars($vivienda); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="punto_referencia<?php echo $prefijo; ?>" class="form-label">Punto de Referencia</label>
                <input type="text" class="form-control" id="punto_referencia<?php echo $prefijo; ?>" name="punto_referencia">
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

    <!-- Sección 6: Salud -->
    <h5 class="mb-3"><i class="fas fa-heartbeat mr-2"></i> Salud</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="enfermedad<?php echo $prefijo; ?>" class="form-label">Enfermedades</label>
                <input type="text" class="form-control" id="enfermedad<?php echo $prefijo; ?>" name="enfermedad" placeholder="Enfermedades conocidas">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="discapacida<?php echo $prefijo; ?>" class="form-label">Discapacidad</label>
                <input type="text" class="form-control" id="discapacida<?php echo $prefijo; ?>" name="discapacida" placeholder="Tipo de discapacidad si aplica">
            </div>
        </div>
    </div>

    <!-- Sección 7: Contacto -->
    <h5 class="mb-3"><i class="fas fa-address-book mr-2"></i> Contacto</h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tlf<?php echo $prefijo; ?>" class="form-label required">Teléfono Principal</label>
                <input type="tel" class="form-control" id="tlf<?php echo $prefijo; ?>" name="tlf" required>
            </div>
            
            <div class="mb-3">
                <label for="cel<?php echo $prefijo; ?>" class="form-label">Teléfono Celular</label>
                <input type="tel" class="form-control" id="cel<?php echo $prefijo; ?>" name="cel">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email<?php echo $prefijo; ?>" class="form-label required">Correo Electrónico</label>
                <input type="email" class="form-control" id="email<?php echo $prefijo; ?>" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="num_telf_opc<?php echo $prefijo; ?>" class="form-label">Teléfono Opcional</label>
                <input type="tel" class="form-control" id="num_telf_opc<?php echo $prefijo; ?>" name="num_telf_opc">
            </div>
        </div>
    </div>

    <!-- Sección 8: Datos del Sistema -->
    <h5 class="mb-3"><i class="fas fa-university mr-2"></i> Datos del Sistema</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="fecha_ingreso<?php echo $prefijo; ?>" class="form-label required">Fecha de Ingreso</label>
                <input type="date" class="form-control" id="fecha_ingreso<?php echo $prefijo; ?>" name="fecha_ingreso" required>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="status<?php echo $prefijo; ?>" class="form-label required">Status</label>
                <select class="custom-select" id="status<?php echo $prefijo; ?>" name="status" required>
                    <option value="" selected disabled>Seleccione un status</option>
                    <?php foreach ($opcionesStatus as $valor => $texto): ?>
                        <option value="<?php echo htmlspecialchars($valor); ?>"><?php echo htmlspecialchars($texto); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
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
</script>