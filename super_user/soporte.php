<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Soporte Técnico";
include('../funciones/functions.php');
include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <h1 class="page-title">
                    <i class="fas fa-headset text-primary"></i> <?php echo $titulopag; ?>
                </h1>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            
            <!-- Contenido principal -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <!-- Programador -->
                        <div class="col-md-5 mb-4">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-code"></i> Soporte Técnico
                                    </h3>
                                </div>
                                <div class="card-body text-center py-4">
                                    <div class="avatar mb-3">
                                        <i class="fas fa-user-tie fa-4x text-primary"></i>
                                    </div>
                                    <h4>Hector Marulanda</h4>
                                    <p class="text-muted">Desarrollador del Sistema</p>
                                    
                                    <hr>
                                    
                                    <div class="contact-info">
                                        <!-- REEMPLAZA CON EL NÚMERO REAL -->
                                        <p><i class="fas fa-phone text-primary"></i> 0412-412-2996</p>
                                        
                                        <!-- REEMPLAZA CON EL CORREO REAL -->
                                        <p><i class="fas fa-envelope text-primary"></i> programador_control_estudios@uptpc.edu.ve</p>
                                        
                                        <p><i class="fas fa-clock text-primary"></i> Disponibilidad: L-V, 8am - 3pm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jefa de Control de Estudios -->
                        <div class="col-md-5 mb-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-user-graduate"></i> Control de Estudios
                                    </h3>
                                </div>
                                <div class="card-body text-center py-4">
                                    <div class="avatar mb-3">
                                        <i class="fas fa-user-graduate fa-4x text-success"></i>
                                    </div>
                                    <h4>Blanca Crespo</h4>
                                    <p class="text-muted">Secretaria general del consejo de gestion</p>
                                    
                                    <hr>
                                    
                                    <div class="contact-info">
                                        <!-- REEMPLAZA CON EL NÚMERO REAL -->
                                        <p><i class="fas fa-phone text-success"></i> 0412-838-8957</p>
                                        
                                        <!-- REEMPLAZA CON EL CORREO REAL -->
                                        <p><i class="fas fa-envelope text-success"></i> blancacrespo@uptpc.edu.ve</p>
                                        
                                        <p><i class="fas fa-clock text-success"></i> Disponibilidad: L-V, 8am - 4pm</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mensaje adicional -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> Información importante</h5>
                                <p class="mb-0">
                                    Para reportar fallas técnicas, contactar al desarrollador. Para consultas académicas o 
                                    administrativas, comunicarse con la Jefa de Control de Estudios.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>