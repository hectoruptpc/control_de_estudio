<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Mis Constancias y Solicitudes";
include('../funciones/functions.php');

// Verificar autenticación y rol de estudiante
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener información del estudiante desde la sesión
$estudiante_id = $_SESSION['user']['id'];
$estudiante = obtenerEstudiantePorId($estudiante_id);
$carrera = null;
$error = "";

if ($estudiante) {
    // Obtener información de la carrera
    $carrera = obtenerCarreraEstudiante($estudiante['id']);
    
    // Determinar el trayecto actual del estudiante
    $id_carrera = $carrera['id_carrera'] ?? 0;
    if ($id_carrera > 0) {
        $trayecto_actual = obtenerTrayectoActual($estudiante['id'], $id_carrera);
    } else {
        // Si no tiene carrera, intentar estimar por notas (fallback)
        $trayecto_actual = obtenerTrayectoActualEstudiante($estudiante['id']);
    }

    // Obtener información legible del trayecto
    $infoTrayecto = obtenerInfoTrayecto($trayecto_actual);
    $estudiante['trayecto_n'] = $infoTrayecto['numero_trayecto'];
    $estudiante['trayecto_nombre'] = $infoTrayecto['nombre_trayecto'];
} else {
    $error = "No se pudo cargar tu información. Por favor, contacta con administración.";
}

include("includes/head.php");
?>

<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado del panel -->
            <div class="dashboard-header p-4 mb-4 text-center">
                <h3 class="font-weight-bold"><i class="fas fa-file-alt mr-3"></i>Constancias y Solicitudes</h3>
                <p class="mb-0">Genera tus constancias académicas y gestiona tus solicitudes</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger shadow-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($estudiante): ?>
                <div class="row">
                    <!-- Columna izquierda: Información del estudiante -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i> Mi Información</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="fas fa-user-circle" style="font-size: 4rem; color: #4e73df;"></i>
                                </div>
                                <h5 class="font-weight-bold text-center"><?php echo htmlspecialchars($estudiante['nombre']); ?></h5>
                                <hr>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong><i class="fas fa-id-card mr-1"></i> Cédula:</strong></td>
                                        <td><?php echo htmlspecialchars($estudiante['idusuario']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fas fa-university mr-1"></i> Carrera:</strong></td>
                                        <td><?php echo htmlspecialchars($carrera['nombre_carrera'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fas fa-code mr-1"></i> Código:</strong></td>
                                        <td><?php echo htmlspecialchars($carrera['cod_carrera'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fas fa-layer-group mr-1"></i> Ubicación:</strong></td>
                                        <td>
                                            <span class="badge badge-info p-2">
                                                <?php echo htmlspecialchars($estudiante['trayecto_nombre']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong><i class="fas fa-calendar mr-1"></i> Fecha:</strong></td>
                                        <td><?php echo date('d/m/Y'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> Los documentos generados no tienen validez académica sin firma
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha: Opciones de constancias -->
                    <div class="col-md-8 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-file-contract mr-2"></i> TIPOS DE CONSTANCIAS DISPONIBLES</h5>
                                <span class="badge badge-light"><?php echo date('Y'); ?></span>
                            </div>
                            <div class="card-body">
                                <!-- Constancia según ubicación académica -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i> 
                                    <strong>Ubicación actual:</strong> <?php echo $estudiante['trayecto_nombre']; ?> - 
                                    Constancia disponible: 
                                    <?php if ($estudiante['trayecto_n'] == 0): ?>
                                        <span class="badge badge-primary">Constancia de Inscripción</span>
                                    <?php else: ?>
                                        <span class="badge badge-primary">Constancia de Estudios</span>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- Constancia según ubicación -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-primary">
                                            <div class="card-header bg-primary-light bg-white py-2">
                                                <h6 class="font-weight-bold text-primary mb-0">
                                                    <i class="fas fa-graduation-cap mr-1"></i> 
                                                    <?php if ($estudiante['trayecto_n'] == 0): ?>
                                                        Constancia de Inscripción
                                                    <?php else: ?>
                                                        Constancia de Estudios
                                                    <?php endif; ?>
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    <?php if ($estudiante['trayecto_n'] == 0): ?>
                                                        Acredita tu inscripción en el período académico actual.
                                                    <?php else: ?>
                                                        Certifica tu condición de estudiante regular en la institución.
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-primary btn-block" 
                                                   href="../admin/constancias/<?php echo $estudiante['trayecto_n'] == 0 ? 'pdf_inscripcion.php' : 'pdf_estudios.php'; ?>?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Constancia de Intensivo -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-warning">
                                            <div class="card-header bg-warning-light bg-white py-2">
                                                <h6 class="font-weight-bold text-warning mb-0">
                                                    <i class="fas fa-file-contract mr-1"></i> Constancia de Intensivo
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Para cursar materias en período intensivo o vacacional.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-warning btn-block" 
                                                   href="../admin/constancias/pdf_intensivo.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Evaluación Extraordinaria -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-danger">
                                            <div class="card-header bg-danger-light bg-white py-2">
                                                <h6 class="font-weight-bold text-danger mb-0">
                                                    <i class="fas fa-redo mr-1"></i> Evaluación Extraordinaria
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Solicitud para presentar evaluación extraordinaria.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-danger btn-block" 
                                                   href="../admin/constancias/pdf_evaluacion_extraordinaria.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Adición/Retiro de Materias -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-info">
                                            <div class="card-header bg-info-light bg-white py-2">
                                                <h6 class="font-weight-bold text-info mb-0">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Adición/Retiro
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Solicitud para adicionar o retirar materias del período.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-info btn-block" 
                                                   href="../admin/constancias/pdf_adicion_retiro.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pasantías/Proyecto -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-success">
                                            <div class="card-header bg-success-light bg-white py-2">
                                                <h6 class="font-weight-bold text-success mb-0">
                                                    <i class="fas fa-briefcase mr-1"></i> Pasantías/Proyecto
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Inscripción en pasantías o proyecto sociointegrador.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-success btn-block" 
                                                   href="../admin/constancias/pdf_inscripcion_practicas.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cambio de Sección -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-secondary">
                                            <div class="card-header bg-secondary-light bg-white py-2">
                                                <h6 class="font-weight-bold text-secondary mb-0">
                                                    <i class="fas fa-sync-alt mr-1"></i> Cambio de Sección
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Solicitud para cambiar de sección en una materia.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-secondary btn-block" 
                                                   href="../admin/constancias/pdf_cambio_seccion.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Retiro de Semestre -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-dark">
                                            <div class="card-header bg-dark-light bg-white py-2">
                                                <h6 class="font-weight-bold text-dark mb-0">
                                                    <i class="fas fa-calendar-times mr-1"></i> Retiro de Semestre
                                                </h6>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2">
                                                    Solicitud de retiro total del semestre académico.
                                                </p>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a class="btn btn-dark btn-block" 
                                                   href="../admin/constancias/pdf_retiro_semestre.php?id=<?php echo $estudiante['id']; ?>" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Más opciones en acordeón -->
                                <div class="mt-4">
                                    <div class="card">
                                        <div class="card-header bg-light py-2" id="headingMasOpciones">
                                            <h6 class="mb-0">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseMasOpciones" aria-expanded="false" aria-controls="collapseMasOpciones">
                                                    <i class="fas fa-chevron-circle-down mr-1"></i> Otras solicitudes disponibles
                                                </button>
                                            </h6>
                                        </div>
                                        <div id="collapseMasOpciones" class="collapse" aria-labelledby="headingMasOpciones">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_cambio_carrera.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-random mr-1"></i> Cambio de Carrera
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_cambio_turno.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-clock mr-1"></i> Cambio de Turno
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_renuncia_cupo.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-user-times mr-1"></i> Renuncia de Cupo
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_constancia_retiro.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-file-export mr-1"></i> Constancia de Retiro
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_constancia_traslado.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-truck-moving mr-1"></i> Constancia de Traslado
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_constancia_reincorporacion.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-user-plus mr-1"></i> Reincorporación
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-6 mb-2">
                                                        <a class="btn btn-outline-primary btn-block" href="../admin/constancias/pdf_retiro_documento.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                            <i class="fas fa-file-download mr-1"></i> Retiro de Documento
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .dashboard-header {
        background: linear-gradient(120deg, #4e73df 0%, #224abe 100%);
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
    }
    .btn-block {
        border-radius: 50px;
    }
    .bg-primary-light { background-color: #eef2ff; }
    .bg-success-light { background-color: #e6fff0; }
    .bg-warning-light { background-color: #fff4e6; }
    .bg-danger-light { background-color: #ffe6e6; }
    .bg-info-light { background-color: #e6f3ff; }
    .bg-secondary-light { background-color: #f2f2f2; }
    .bg-dark-light { background-color: #e9ecef; }
    
    @media (max-width: 768px) {
        .col-md-4, .col-md-8 {
            padding: 0 15px;
        }
    }
</style>

<?php include("includes/footer.php"); ?>