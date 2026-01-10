<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Agregar Estudiante";
require_once('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('agregar_estudiante');

// Obtener los datos necesarios usando las nuevas funciones
$tiposCedula = obtenerTiposCedula($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$opcionesStatus = obtenerOpcionesStatus($db);
$carreras = obtenerTodasLasCarreras();
$ingresos = obtenerIngresos($db);

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
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_message; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Pestañas -->
                    <ul class="nav nav-tabs mb-4" id="estudianteTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="individual-tab" data-toggle="tab" 
                               href="#individual" role="tab" aria-controls="individual" aria-selected="true">
                                <i class="fas fa-user mr-1"></i> Individual
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masivo-tab" data-toggle="tab" 
                               href="#masivo" role="tab" aria-controls="masivo" aria-selected="false">
                                <i class="fas fa-users mr-1"></i> Carga Masiva
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="estudianteTabsContent">
                        <!-- Formulario individual -->
                        <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                            <?php
                            // Incluir el formulario parcial
                            $esModal = false;
                            include('_formulario_estudiante.php');
                            ?>
                        </div>
                        
                        <!-- Formulario de carga masiva -->
                        <div class="tab-pane fade" id="masivo" role="tabpanel" aria-labelledby="masivo-tab">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle mr-2"></i>Instrucciones para carga masiva</h5>
                                <ol>
                                    <li>
                                        <button class="btn btn-sm btn-primary" onclick="descargarPlantilla()">
                                            <i class="fas fa-file-download mr-1"></i> Descargar Plantilla CSV Vacía
                                        </button>
                                    </li>
                                    <li>Complete los datos en la plantilla descargada</li>
                                    <li>Suba el archivo completado aquí:</li>
                                </ol>
                            </div>

                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="archivo_csv" class="form-label required">Archivo CSV completado</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="archivo_csv" name="archivo_csv" accept=".csv" required>
                                        <label class="custom-file-label" for="archivo_csv">Seleccionar archivo...</label>
                                    </div>
                                    <small class="form-text text-muted">El archivo debe contener todos los campos necesarios</small>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Regresar
                                    </button>
                                    
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload mr-1"></i> Subir y Procesar
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
    csvContent += 'V-12345678,Nombre Ejemplo,ejemplo@correo.com,02121234567,04141234567,"Dirección Ejemplo",Caracas,Distrito Capital,Libertador,La Candelaria,"",Casa,"Frente a la plaza",4,2,"Trabajo formal","Casa","Propia","Ninguna","Ninguna",2023-01-15,1,1,Masculino,Soltero,1990-01-01,33,02121234568,"Bachiller,Licenciatura","Liceo XYZ,Universidad ABC"\r\n';
    
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

// Mostrar nombre de archivo seleccionado
document.addEventListener('DOMContentLoaded', function() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});

// Activar pestañas
$(document).ready(function(){
    // Activar pestañas
    $('#estudianteTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Cambiar a pestaña masivo si hay parámetro en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'masivo') {
        $('#masivo-tab').tab('show');
    }
});
</script>

<?php include("includes/footer.php"); ?>