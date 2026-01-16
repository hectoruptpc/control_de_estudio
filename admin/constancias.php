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
        
        // Obtenemos las materias de su carrera para identificar el trayecto
        // Según tu función, esto devuelve un objeto mysqli_result
        $res_materias = obtenerMateriasCarrera($estudiante['carrera']);
        $materias_data = $res_materias->fetch_assoc();

        if (!$materias_data) {
            $error = "El estudiante no tiene materias asociadas a su carrera.";
            $estudiante = null;
        } else {
            // USANDO TU FUNCIÓN PARA INFO DE TRAYECTO
            // Tomamos el trayecto de la primera materia encontrada como referencia de su nivel actual
            $infoTrayecto = obtenerInfoTrayecto($materias_data['trayecto']);
            $estudiante['trayecto_n'] = $infoTrayecto['numero_trayecto'];
            $estudiante['trayecto_nombre'] = $infoTrayecto['nombre_trayecto'];
        }
    } else {
        $error = "No se encontró ningún estudiante con la cédula: <strong>$cedula</strong>";
    }
}

include("includes/head.php");
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
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
                                    <div class="card-body text-center">
                                        
                                        <?php if ($estudiante['trayecto_n'] == 0): ?>
                                            <i class="fas fa-file-invoice fa-3x text-info mb-3"></i>
                                            <h5>Constancia de Inscripción</h5>
                                            <p class="small text-muted">Disponible para Trayecto Inicial</p>
                                            <a href="reportes/pdf_inscripcion.php?id=<?php echo $estudiante['id']; ?>" target="_blank" class="btn btn-info btn-block">
                                                <i class="fas fa-print mr-2"></i>Generar Reporte
                                            </a>
                                        <?php else: ?>
                                            <i class="fas fa-user-graduate fa-3x text-success mb-3"></i>
                                            <h5>Constancia de Estudios</h5>
                                            <p class="small text-muted">Disponible para Trayectos Regulares</p>
                                            <a href="reportes/pdf_estudios.php?id=<?php echo $estudiante['id']; ?>" target="_blank" class="btn btn-success btn-block">
                                                <i class="fas fa-print mr-2"></i>Generar Reporte
                                            </a>
                                        <?php endif; ?>

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