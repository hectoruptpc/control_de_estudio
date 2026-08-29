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

    // Evaluación de aptitud para Intensivo, Evaluación Extraordinaria y Pasantías/Proyecto
    $es_apto_intensivo = esAptoParaIntensivo($estudiante['id']);
    $es_apto_extraordinario = esAptoParaExtraordinario($estudiante['id']);
    $es_apto_pasantias = esAptoParaPasantias($estudiante['id']);
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
                <h3 class="font-weight-bold text-uppercase"><i class="fas fa-file-alt mr-3"></i>CONSTANCIAS Y SOLICITUDES</h3>
                <p class="mb-0 text-uppercase">GENERA TUS CONSTANCIAS ACADÉMICAS Y GESTIONA TUS SOLICITUDES</p>
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
                                            <div class="card-header bg-warning-light bg-white py-2 d-flex justify-content-between align-items-center">
                                                <h6 class="font-weight-bold text-warning mb-0 text-uppercase">
                                                    <i class="fas fa-file-contract mr-1"></i> CONSTANCIA DE INTENSIVO
                                                </h6>
                                                <?php if ($es_apto_intensivo): ?>
                                                    <span class="badge badge-success">APTO</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">NO APTO</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2 text-uppercase">
                                                    PARA CURSAR MATERIAS EN PERÍODO INTENSIVO O VACACIONAL.
                                                </p>
                                                <?php if (!$es_apto_intensivo): ?>
                                                    <div class="alert alert-warning p-2 mb-0 text-uppercase font-weight-bold small">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> NO CUMPLE REQUISITOS PARA ESTE TRÁMITE.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <?php if ($es_apto_intensivo): ?>
                                                    <a class="btn btn-warning btn-block font-weight-bold text-uppercase" 
                                                       href="../admin/constancias/pdf_intensivo.php?id=<?php echo $estudiante['id']; ?>" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-warning btn-block font-weight-bold text-uppercase" 
                                                            data-toggle="modal" data-target="#modalNoAptoIntensivo">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Evaluación Extraordinaria -->
                                    <div class="col-lg-6 mb-3">
                                        <div class="card h-100 border-danger">
                                            <div class="card-header bg-danger-light bg-white py-2 d-flex justify-content-between align-items-center">
                                                <h6 class="font-weight-bold text-danger mb-0 text-uppercase">
                                                    <i class="fas fa-redo mr-1"></i> EVALUACIÓN EXTRAORDINARIA
                                                </h6>
                                                <?php if ($es_apto_extraordinario): ?>
                                                    <span class="badge badge-success">APTO</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">NO APTO</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2 text-uppercase">
                                                    SOLICITUD PARA PRESENTAR EVALUACIÓN EXTRAORDINARIA Y/O SUFICIENCIA.
                                                </p>
                                                <?php if (!$es_apto_extraordinario): ?>
                                                    <div class="alert alert-danger p-2 mb-0 text-uppercase font-weight-bold small">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> NO CUMPLE REQUISITOS PARA ESTE TRÁMITE.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <?php if ($es_apto_extraordinario): ?>
                                                    <a class="btn btn-danger btn-block font-weight-bold text-uppercase" 
                                                       href="../admin/constancias/pdf_evaluacion_extraordinaria.php?id=<?php echo $estudiante['id']; ?>" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-danger btn-block font-weight-bold text-uppercase" 
                                                            data-toggle="modal" data-target="#modalNoAptoExtraordinario">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </button>
                                                <?php endif; ?>
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
                                            <div class="card-header bg-success-light bg-white py-2 d-flex justify-content-between align-items-center">
                                                <h6 class="font-weight-bold text-success mb-0 text-uppercase">
                                                    <i class="fas fa-briefcase mr-1"></i> PASANTÍAS / PROYECTO
                                                </h6>
                                                <?php if ($es_apto_pasantias): ?>
                                                    <span class="badge badge-success">APTO</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">NO APTO</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body py-2">
                                                <p class="small text-muted mb-2 text-uppercase">
                                                    INSCRIPCIÓN EN PASANTÍAS O PROYECTO SOCIOINTEGRADOR.
                                                </p>
                                                <?php if (!$es_apto_pasantias): ?>
                                                    <div class="alert alert-warning p-2 mb-0 text-uppercase font-weight-bold small">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> RESERVADO PARA TRAYECTO I O SUPERIOR.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <?php if ($es_apto_pasantias): ?>
                                                    <a class="btn btn-success btn-block font-weight-bold text-uppercase" 
                                                       href="../admin/constancias/pdf_inscripcion_practicas.php?id=<?php echo $estudiante['id']; ?>" 
                                                       target="_blank">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-success btn-block font-weight-bold text-uppercase" 
                                                            data-toggle="modal" data-target="#modalNoAptoPasantias">
                                                        <i class="fas fa-file-pdf mr-1"></i> GENERAR PDF
                                                    </button>
                                                <?php endif; ?>
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

                

            <!-- Modal para Estudiante No Apto para Intensivo -->
            <div class="modal fade" id="modalNoAptoIntensivo" tabindex="-1" role="dialog" aria-labelledby="modalNoAptoIntensivoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-danger shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title font-weight-bold text-uppercase" id="modalNoAptoIntensivoLabel">
                                <i class="fas fa-exclamation-circle mr-2"></i> TRÁMITE NO PERMITIDO
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-user-slash text-danger" style="font-size: 3.5rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-danger text-uppercase mb-3">NO TE ENCUENTRAS APTO PARA INTENSIVO</h5>
                            <div class="alert alert-warning text-uppercase small font-weight-bold text-left mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                ESTIMADO(A) <strong><?php echo htmlspecialchars(mb_strtoupper($estudiante['nombre'], 'UTF-8')); ?></strong> (CÉDULA: <?php echo htmlspecialchars($estudiante['idusuario']); ?>), USTED NO CUMPLE CON LOS REQUISITOS ACADÉMICOS NECESARIOS O SU ESTATUS ACTUAL NO PERMITE PROCESAR UNA CONSTANCIA DE CURSO INTENSIVO EN ESTE PERÍODO.
                            </div>
                            <p class="text-muted text-uppercase small mb-0 font-weight-bold">
                                SI CONSIDERAS QUE ESTO ES UN ERROR O NECESITAS ORIENTACIÓN SOBRE TU EXPEDIENTE ACADÉMICO, POR FAVOR ACUDE A LA OFICINA DE <strong>CONTROL DE ESTUDIOS</strong>.
                            </p>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary font-weight-bold text-uppercase px-4" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> ENTENDIDO
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Estudiante No Apto para Evaluación Extraordinaria -->
            <div class="modal fade" id="modalNoAptoExtraordinario" tabindex="-1" role="dialog" aria-labelledby="modalNoAptoExtraordinarioLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-danger shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title font-weight-bold text-uppercase" id="modalNoAptoExtraordinarioLabel">
                                <i class="fas fa-exclamation-triangle mr-2"></i> TRÁMITE NO PERMITIDO
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-file-excel text-danger" style="font-size: 3.5rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-danger text-uppercase mb-3">NO APTO PARA EVALUACIÓN EXTRAORDINARIA</h5>
                            <div class="alert alert-danger text-uppercase small font-weight-bold text-left mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                ESTIMADO(A) <strong><?php echo htmlspecialchars(mb_strtoupper($estudiante['nombre'], 'UTF-8')); ?></strong> (CÉDULA: <?php echo htmlspecialchars($estudiante['idusuario']); ?>), USTED NO CUENTA CON MATERIAS REPROBADAS NI REGISTRO DE ASIGNATURAS APLAZADAS EN SU EXPEDIENTE QUE REQUIERAN O CALIFIQUEN PARA PRESENTAR UNA EVALUACIÓN EXTRAORDINARIA.
                            </div>
                            <p class="text-muted text-uppercase small mb-0 font-weight-bold">
                                SI CONSIDERAS QUE EXISTE ALGUNA INCONSISTENCIA EN TUS NOTAS, POR FAVOR ACUDE A LA OFICINA DE <strong>CONTROL DE ESTUDIOS</strong> PARA REVISAR TU HISTORIAL ACADÉMICO.
                            </p>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary font-weight-bold text-uppercase px-4" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> ENTENDIDO
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para Estudiante No Apto para Pasantías/Proyecto -->
            <div class="modal fade" id="modalNoAptoPasantias" tabindex="-1" role="dialog" aria-labelledby="modalNoAptoPasantiasLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-warning shadow">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title font-weight-bold text-uppercase" id="modalNoAptoPasantiasLabel">
                                <i class="fas fa-exclamation-circle mr-2"></i> TRÁMITE NO DISPONIBLE
                            </h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-briefcase text-warning" style="font-size: 3.5rem;"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark text-uppercase mb-3">RESERVADO PARA TRAYECTO I O SUPERIOR</h5>
                            <div class="alert alert-warning text-uppercase small font-weight-bold text-left mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                ESTIMADO(A) <strong><?php echo htmlspecialchars(mb_strtoupper($estudiante['nombre'], 'UTF-8')); ?></strong> (CÉDULA: <?php echo htmlspecialchars($estudiante['idusuario']); ?>), LA INSCRIPCIÓN EN PASANTÍAS Y PROYECTO SOCIOINTEGRADOR ESTÁ DESTINADA A ESTUDIANTES QUE SE ENCUENTRAN CURSANDO TRAYECTO I O SUPERIOR DEL PNF. SU UBICACIÓN ACTUAL ES <strong><?php echo htmlspecialchars(mb_strtoupper($estudiante['trayecto_nombre'], 'UTF-8')); ?></strong>.
                            </div>
                            <p class="text-muted text-uppercase small mb-0 font-weight-bold">
                                UNA VEZ CULMINADO Y APROBADO EL TRAYECTO INICIAL (TRAYECTO 0), PODRÁS SOLICITAR TU CONSTANCIA DE INSCRIPCIÓN EN PASANTÍAS / PROYECTO.
                            </p>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary font-weight-bold text-uppercase px-4" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> ENTENDIDO
                            </button>
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