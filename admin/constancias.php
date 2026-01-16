<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Generación de Constancias";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('admin');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

include("includes/head.php");
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-certificate mr-2"></i> Módulo de Constancias Académicas</h5>
                    <span class="badge badge-light">Administración</span>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-3 text-center">
                            <label for="cedula_buscar" class="font-weight-bold">Ingrese la Cédula del Estudiante:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="number" class="form-control form-control-lg" id="cedula_buscar" placeholder="Ej: 20123456">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">El sistema validará si el estudiante posee un lapso activo.</small>
                        </div>
                    </div>

                    <hr>

                    <div id="resultado_estudiante" class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> <strong>Importante:</strong> Las constancias solo se emiten para estudiantes con estado <strong>Activo</strong> en el lapso actual.
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-5">
                                <div class="card border-left-primary h-100 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="text-primary font-weight-bold uppercase">Información Académica</h6>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><strong>Nombre:</strong> [Nombre del Estudiante]</li>
                                            <li class="list-group-item"><strong>Cédula:</strong> [Cédula]</li>
                                            <li class="list-group-item"><strong>Carrera:</strong> [Nombre de la Carrera]</li>
                                            <li class="list-group-item"><strong>Trayecto/Semestre:</strong> [Trayecto]</li>
                                            <li class="list-group-item"><strong>Lapso Actual:</strong> <span class="badge badge-success">2024-II</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-light font-weight-bold text-dark">
                                        Reportes Disponibles
                                    </div>
                                    <div class="card-body text-center d-flex flex-column justify-content-center">
                                        
                                        <div class="mb-3">
                                            <button class="btn btn-outline-info btn-block py-3 shadow-sm">
                                                <i class="fas fa-clipboard-check fa-2x mb-2"></i> <br>
                                                <strong>Generar Constancia de Inscripción</strong>
                                                <p class="small mb-0 text-muted">Aplica para todos los trayectos (incluyendo Inicial)</p>
                                            </button>
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-outline-success btn-block py-3 shadow-sm">
                                                <i class="fas fa-user-graduate fa-2x mb-2"></i> <br>
                                                <strong>Generar Constancia de Estudios</strong>
                                                <p class="small mb-0 text-muted">Disponible solo para trayectos superiores al Inicial</p>
                                            </button>
                                            <div class="alert alert-warning mt-2 small text-left py-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> 
                                                No disponible para <strong>Trayecto Inicial</strong>.
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer text-muted small">
                    Sistema de Gestión de Constancias - Universidad v2.0
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>