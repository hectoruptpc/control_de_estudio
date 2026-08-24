<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Generación de Constancias";
include('../funciones/functions.php');

// CARGAR PERMISOS Y VERIFICAR
cargarPermisosUsuario();
verificarPermiso('admin');
visita();

$estudiante = null;
$carrera = null;
$error = "";

// PROCESAR BÚSQUEDA
if (isset($_POST['buscar']) && !empty($_POST['cedula'])) {
    $cedula = strtoupper(trim($_POST['cedula']));
    
    // USANDO TU FUNCIÓN ORIGINAL
    $estudiante = buscarEstudiantePorCedulaConsulta($cedula);

    if ($estudiante) {
        // USANDO TU FUNCIÓN PARA OBTENER CARRERA
        $carrera = obtenerCarreraEstudiante($estudiante['id']);
        
        // Determinar el trayecto usando la lógica estándar de inscripciones
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
        $error = "No se encontró ningún estudiante con la cédula: <strong>$cedula</strong>";
    }
}

include("includes/head.php");
?>

<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-file-contract mr-2"></i> Generador de Constancias</h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST" action="" class="mb-4">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <label class="small font-weight-bold">Formato: V-00000000 o E-00000000</label>
                                <div class="input-group">
                                    <input type="text" name="cedula" class="form-control form-control-lg" 
                                           placeholder="Ej: V-12345678" 
                                           value="<?php echo $_POST['cedula'] ?? ''; ?>" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary px-4" type="submit" name="buscar">
                                            <i class="fas fa-search"></i> Consultar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php if ($error): ?>
                        <div class="alert alert-warning shadow-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($estudiante): ?>
                        <div class="row mt-4">
                            <div class="col-md-5">
                                <div class="card bg-light shadow-sm">
                                    <div class="card-body">
                                        <h5 class="font-weight-bold"><?php echo $estudiante['nombre']; ?></h5>
                                        <hr>
                                        <p class="mb-1"><strong>Cédula:</strong> <?php echo $estudiante['idusuario']; ?></p>
                                        <p class="mb-1"><strong>Carrera:</strong> <?php echo $carrera['nombre_carrera'] ?? 'N/A'; ?></p>
                                        <p class="mb-1"><strong>Ubicación:</strong> <span class="badge badge-info"><?php echo $estudiante['trayecto_nombre']; ?></span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white font-weight-bold text-primary">OPCIONES DISPONIBLES</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php if ($estudiante['trayecto_n'] == 0): ?>
                                                <div class="col-sm-6 mb-2">
                                                    <a class="btn btn-outline-primary btn-block" href="constancias/pdf_inscripcion.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                        <i class="fas fa-file-invoice mr-1"></i> Constancia de Inscripción
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-sm-6 mb-2">
                                                    <a class="btn btn-outline-primary btn-block" href="constancias/pdf_estudios.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                        <i class="fas fa-user-graduate mr-1"></i> Constancia de Estudios
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_intensivo.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-file-contract mr-1"></i> Constancia de Intensivo
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_evaluacion_extraordinaria.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-redo mr-1"></i> Evaluación Extraordinaria
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_adicion_retiro.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-exchange-alt mr-1"></i> Adición/Retiro
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_inscripcion_practicas.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-briefcase mr-1"></i> Pasantías/Proyecto
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_cambio_carrera.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-random mr-1"></i> Cambio de Carrera
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_cambio_turno.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-clock mr-1"></i> Cambio Turno
                                                </a>
                                            </div>
                                            <!-- NUEVO BOTÓN AÑADIDO AQUÍ -->
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_cambio_seccion.php?id=<?php echo $estudiante['id']; ?>">
                                                    <i class="fas fa-sync-alt mr-1"></i> Cambiar Sección
                                                </a>
                                            </div>
                                            <!-- FIN DEL NUEVO BOTÓN -->
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_renuncia_cupo.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-user-times mr-1"></i> Renuncia de Cupo
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_retiro_semestre.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-calendar-times mr-1"></i> Retiro de Semestre
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_constancia_retiro.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-file-export mr-1"></i> Constancia de Retiro
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_constancia_traslado.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-truck-moving mr-1"></i> Constancia de Traslado
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_constancia_reincorporacion.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-user-plus mr-1"></i> Constancia de Reincorporación
                                                </a>
                                            </div>
                                            <div class="col-sm-6 mb-2">
                                                <a class="btn btn-outline-primary btn-block" href="constancias/pdf_retiro_documento.php?id=<?php echo $estudiante['id']; ?>" target="_blank">
                                                    <i class="fas fa-file-download mr-1"></i> Retiro de Documento
                                                </a>
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
    </div>
</div>

<?php include("includes/footer.php"); ?>