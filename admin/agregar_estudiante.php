<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Agregar Estudiante";
require_once('../funciones/functions.php');

// Obtener los datos necesarios usando las nuevas funciones
$tiposCedula = obtenerTiposCedula($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$opcionesStatus = obtenerOpcionesStatus($db);

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si es una carga masiva de CSV
    if (isset($_FILES['archivo_csv']) && $_FILES['archivo_csv']['error'] == UPLOAD_ERR_OK) {
        // Procesar el archivo CSV
        $resultado = procesarCSVEstudiantes(
            $_FILES['archivo_csv']['tmp_name'],
            $_FILES['archivo_csv']['name']
        );
        
        if ($resultado['success']) {
            $success_message = $resultado['message'];
        } else {
            $error_message = $resultado['message'];
        }
    } else {
        // Procesamiento individual
        $validacion = validarEstudiante($_POST);
        
        if ($validacion === true) {
            $resultado = insertarEstudiante($_POST);
            
            if ($resultado['success']) {
                $success_message = $resultado['message'];
            } else {
                $error_message = $resultado['message'];
            }
        } else {
            $error_message = implode("<br>", $validacion);
        }
    }
}

include("includes/head.php");
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Agregar Nuevo Estudiante</h5>
                </div>
                <div class="card-body">
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
                    
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs mb-4" id="estudianteTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="individual-tab" data-toggle="tab" 
                               href="#individual" role="tab" aria-controls="individual" aria-selected="true">
                                <i class="fas fa-user me-1"></i> Individual
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masivo-tab" data-toggle="tab" 
                               href="#masivo" role="tab" aria-controls="masivo" aria-selected="false">
                                <i class="fas fa-users me-1"></i> Carga Masiva
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="myTabContent">
                        <!-- Formulario individual -->
                        <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                            <form id="formEstudiante" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <!-- Sección 1: Identificación -->
<h5 class="mb-3"><i class="fas fa-id-card me-2"></i> Identificación</h5>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nombre" class="form-label required">Nombre Completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="mb-3">
            <label for="cedula_completa" class="form-label required">Cédula</label>
            <div class="input-group">
                <select class="form-select" id="tipo_cedula" name="tipo_cedula" style="max-width: 80px;">
                    <?php foreach ($tiposCedula as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo['tipo']); ?>">
                            <?php echo htmlspecialchars($tipo['tipo']); ?>-
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" class="form-control" id="numero_cedula" name="numero_cedula" placeholder="Ej: 12345678" required>
                <input type="hidden" id="idusuario" name="idusuario">
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
                                            <label for="fecha_nac" class="form-label required">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label required">Género</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="genero" id="genero_m" value="Masculino" required>
                                                    <label class="form-check-label" for="genero_m">Masculino</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="genero" id="genero_f" value="Femenino">
                                                    <label class="form-check-label" for="genero_f">Femenino</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="genero" id="genero_o" value="Otro">
                                                    <label class="form-check-label" for="genero_o">Otro</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edo_civil" class="form-label required">Estado Civil</label>
                                            <select class="custom-select d-block w-100" id="edo_civil" name="edo_civil" required>
                                                <option value="" selected disabled>Seleccione una opción</option>
                                                <?php foreach ($estadosCiviles as $id => $estadoCivil): ?>
                                                    <option value="<?php echo htmlspecialchars($estadoCivil); ?>"><?php echo htmlspecialchars($estadoCivil); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="etnia" class="form-label">Etnia</label>
                                            <input type="text" class="form-control" id="etnia" name="etnia">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 3: Formación Académica -->
                                <h5 class="mb-3"><i class="fas fa-graduation-cap me-2"></i> Formación Académica</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="carrera" class="form-label required">Programa</label>
                                            <select class="custom-select d-block w-100" id="carrera" name="carrera" required>
                                                <option value="" selected disabled>Seleccione un Programa</option>
                                                <?php 
                                                $carreras = obtenerCarreras();
                                                foreach ($carreras as $carrera): 
                                                    if (!empty($carrera)): ?>
                                                        <option value="<?php echo htmlspecialchars($carrera); ?>">
                                                            <?php echo htmlspecialchars($carrera); ?>
                                                        </option>
                                                    <?php endif;
                                                endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titulos" class="form-label">Títulos Obtenidos</label>
                                            <input type="text" class="form-control" id="titulos" name="titulos" placeholder="Separados por comas si son varios">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="institutos" class="form-label">Instituciones</label>
                                            <input type="text" class="form-control" id="institutos" name="institutos" placeholder="Instituciones donde obtuvo los títulos">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 4: Ubicación y Vivienda -->
                                <h5 class="mb-3"><i class="fas fa-home me-2"></i> Vivienda</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="estado" class="form-label required">Estado</label>
                                            <input type="text" class="form-control" id="estado" name="estado" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="municipio" class="form-label required">Municipio</label>
                                            <input type="text" class="form-control" id="municipio" name="municipio" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="parroquia" class="form-label">Parroquia</label>
                                            <input type="text" class="form-control" id="parroquia" name="parroquia">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="direccion" class="form-label required">Dirección</label>
                                            <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="casaapto" class="form-label">Tipo de Vivienda</label>
                                            <select class="form-control" id="casaapto" name="casaapto">
                                                <option value="">Seleccione...</option>
                                                <?php foreach ($tiposVivienda as $id => $vivienda): ?>
                                                    <option value="<?php echo htmlspecialchars($vivienda); ?>"><?php echo htmlspecialchars($vivienda); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="punto_referencia" class="form-label">Punto de Referencia</label>
                                            <input type="text" class="form-control" id="punto_referencia" name="punto_referencia">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 5: Situación Familiar -->
                                <h5 class="mb-3"><i class="fas fa-users me-2"></i> Situación Familiar</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="grupo_familiar" class="form-label">Grupo Familiar</label>
                                            <input type="number" class="form-control" id="grupo_familiar" name="grupo_familiar" min="1" placeholder="Número de personas">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="acargo_usted" class="form-label">Personas a su cargo</label>
                                            <input type="number" class="form-control" id="acargo_usted" name="acargo_usted" min="0">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fuente_ingresos" class="form-label">Fuente de Ingresos</label>
                                            <input type="text" class="form-control" id="fuente_ingresos" name="fuente_ingresos">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tenencia_vivienda" class="form-label">Tenencia de Vivienda</label>
                                            <select class="form-control" id="tenencia_vivienda" name="tenencia_vivienda">
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
                                            <label for="enfermedad" class="form-label">Enfermedades</label>
                                            <input type="text" class="form-control" id="enfermedad" name="enfermedad" placeholder="Enfermedades conocidas">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discapacida" class="form-label">Discapacidad</label>
                                            <input type="text" class="form-control" id="discapacida" name="discapacida" placeholder="Tipo de discapacidad si aplica">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 7: Contacto -->
                                <h5 class="mb-3"><i class="fas fa-address-book me-2"></i> Contacto</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tlf" class="form-label required">Teléfono Principal</label>
                                            <input type="tel" class="form-control" id="tlf" name="tlf" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="cel" class="form-label">Teléfono Celular</label>
                                            <input type="tel" class="form-control" id="cel" name="cel">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label required">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                                            <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc">
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección 8: Datos del Sistema -->
                                <h5 class="mb-3"><i class="fas fa-university me-2"></i> Datos del Sistema</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_ingreso" class="form-label required">Fecha de Ingreso</label>
                                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label required">Status</label>
                                            <select class="custom-select d-block w-100" id="status" name="status" required>
                                                <option value="" selected disabled>Seleccione un status</option>
                                                <?php foreach ($opcionesStatus as $valor => $texto): ?>
                                                    <option value="<?php echo htmlspecialchars($valor); ?>"><?php echo htmlspecialchars($texto); ?></option>
                                                <?php endforeach; ?>
                                            </select>
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
                                            <i class="fas fa-save me-1"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Formulario de carga masiva -->
                        <div class="tab-pane fade" id="masivo" role="tabpanel" aria-labelledby="masivo-tab">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle me-2"></i>Instrucciones para carga masiva</h5>
                                <ol>
                                    <li>
                                        <button class="btn btn-sm btn-primary" onclick="descargarPlantilla()">
                                            <i class="fas fa-file-download me-1"></i> Descargar Plantilla CSV Vacía
                                        </button>
                                    </li>
                                    <li>Complete los datos en la plantilla descargada</li>
                                    <li>Suba el archivo completado aquí:</li>
                                </ol>
                            </div>

                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="archivo_csv" class="form-label required">Archivo CSV completado</label>
                                    <input class="form-control" type="file" id="archivo_csv" name="archivo_csv" accept=".csv" required>
                                    <div class="form-text">El archivo debe contener todos los campos necesarios</div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Regresar
                                    </button>
                                    
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-1"></i> Subir y Procesar
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
</div>

<script>
// Función para descargar plantilla CSV con todos los campos
function descargarPlantilla() {
    const encabezados = [
        'idusuario', 'nombre', 'email', 'tlf', 'cel', 'direccion', 'ciudad', 
        'estado', 'municipio', 'parroquia', 'etnia', 'casaapto', 'punto_referencia',
        'grupo_familiar', 'acargo_usted', 'fuente_ingresos', 'tipo_vivienda', 
        'tenencia_vivienda', 'enfermedad', 'discapacida', 'fecha_ingreso', 'status',
        'carrera', 'genero', 'edo_civil', 'fecha_nac', 'edad', 'num_telf_opc',
        'titulos', 'institutos'
    ];
    
    let csvContent = encabezados.join(',') + '\r\n';
    csvContent += 'V-12345678,Nombre Ejemplo,ejemplo@correo.com,02121234567,04141234567,"Dirección Ejemplo",Caracas,Distrito Capital,Libertador,La Candelaria,"",Casa,"Frente a la plaza",4,2,"Trabajo formal","Casa","Propia","Ninguna","Ninguna",2023-01-15,1,Ingeniería,Masculino,Soltero,1990-01-01,33,02121234568,"Bachiller,Licenciatura","Liceo XYZ,Universidad ABC"\r\n';
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = 'plantilla_estudiantes_completa.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
}

// Manejo del campo de cédula
document.addEventListener('DOMContentLoaded', function() {
    const tipoCedula = document.getElementById('tipo_cedula');
    const numeroCedula = document.getElementById('numero_cedula');
    const idUsuario = document.getElementById('idusuario');
    
    function actualizarCedulaCompleta() {
        const numeroLimpio = numeroCedula.value.replace(/[^0-9]/g, '');
        numeroCedula.value = numeroLimpio;
        idUsuario.value = tipoCedula.value + '-' + numeroLimpio;
    }
    
    tipoCedula.addEventListener('change', actualizarCedulaCompleta);
    numeroCedula.addEventListener('input', actualizarCedulaCompleta);
    actualizarCedulaCompleta();
});

// Manejo de pestañas
$(document).ready(function() {
    $('#estudianteTabs a').on('click', function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'masivo') {
        $('#masivo-tab').tab('show');
    }
});
</script>

<?php include("includes/footer.php"); ?>