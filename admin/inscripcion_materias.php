<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Inscripción de Materias por Trayecto - Individual";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('admin');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Asegurar que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Asegurar user_id en sesión
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    if (isset($_SESSION['id']) && $_SESSION['id'] > 0) {
        $_SESSION['user_id'] = $_SESSION['id'];
    } elseif (isset($_SESSION['idusuario']) && $_SESSION['idusuario'] > 0) {
        $_SESSION['user_id'] = $_SESSION['idusuario'];
    }
}

include("includes/head.php");

// Obtener período activo
$periodo_activo = obtenerPeriodoActivo();

// Variables de estado
$mensaje = '';
$tipo_mensaje = '';
$info_estudiante = null;
$materias_disponibles = [];
$secciones_disponibles = [];
$materias_aprobadas = [];
$materias_inscritas = [];
$historial_secciones = [];
$trayecto_actual = 0;
$trayecto_inscripcion = 0;
$estudiantes_encontrados = [];
$es_estudiante_nuevo = false;
$info_seccion_actual = null;
$verificacion_avance = null;
$puede_avanzar = false;
$trayecto_siguiente = 0;

// Procesar búsqueda por cédula
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar_cedula'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $cedula = trim($_POST['cedula'] ?? '');
            
            if (empty($cedula)) {
                $mensaje = "Por favor ingrese una cédula";
                $tipo_mensaje = 'warning';
            } else {
                try {
                    $resultados = buscarEstudiantePorCedula($cedula);
                    
                    if (is_array($resultados) && !empty($resultados)) {
                        if (isset($resultados['id'])) {
                            $estudiantes_encontrados = [$resultados];
                        } else {
                            $estudiantes_encontrados = $resultados;
                        }
                        
                        if (count($estudiantes_encontrados) == 1) {
                            $info_estudiante = obtenerInfoEstudiantePorId($estudiantes_encontrados[0]['id']);
                            $_SESSION['estudiante_seleccionado'] = $info_estudiante['id'];
                        }
                    } else {
                        $mensaje = "No se encontraron estudiantes con la cédula: " . htmlspecialchars($cedula);
                        $tipo_mensaje = 'warning';
                    }
                } catch (Exception $e) {
                    $mensaje = "Error al buscar estudiante: " . $e->getMessage();
                    $tipo_mensaje = 'danger';
                }
            }
        }
    }
    
    // Procesar selección de estudiante
    if (isset($_POST['seleccionar_estudiante'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            
            if ($id_estudiante > 0) {
                $info_estudiante = obtenerInfoEstudiantePorId($id_estudiante);
                if ($info_estudiante) {
                    $_SESSION['estudiante_seleccionado'] = $info_estudiante['id'];
                } else {
                    $mensaje = "Estudiante no encontrado";
                    $tipo_mensaje = 'danger';
                }
            } else {
                unset($_SESSION['estudiante_seleccionado']);
                $info_estudiante = null;
                $mensaje = "Selección de estudiante limpiada.";
                $tipo_mensaje = 'info';
            }
        }
    }
    
    // Procesar inscripción de materias
    if (isset($_POST['inscribir_materias'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            $id_seccion = intval($_POST['id_seccion'] ?? 0);
            $materias_ids = isset($_POST['materias']) ? array_map('intval', $_POST['materias']) : [];
            
            if ($id_estudiante > 0 && !empty($materias_ids)) {
                $resultado = inscribirMateriasEstudiante($id_estudiante, $id_seccion, $materias_ids);
                
                if ($resultado) {
                    $mensaje = "✅ Materias inscritas correctamente para el estudiante.";
                    $tipo_mensaje = 'success';
                    $info_estudiante = obtenerInfoEstudiantePorId($id_estudiante);
                } else {
                    $mensaje = "❌ Error al inscribir las materias. Por favor intente nuevamente.";
                    $tipo_mensaje = 'danger';
                }
            } else {
                $mensaje = "⚠️ Debe seleccionar al menos una materia para inscribir.";
                $tipo_mensaje = 'warning';
            }
        }
    }
    
    // Procesar avance de estudiante individual
    if (isset($_POST['avanzar_estudiante_trayecto'])) {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            
            if ($id_estudiante > 0) {
                $id_admin = $_SESSION['user_id'] ?? 0;
                $resultado = avanzarEstudianteTrayecto($id_estudiante, $id_admin);
                
                if ($resultado['success']) {
                    $mensaje = "✅ " . $resultado['message'];
                    $tipo_mensaje = 'success';
                    $info_estudiante = obtenerInfoEstudiantePorId($id_estudiante);
                } else {
                    $mensaje = "❌ " . $resultado['message'];
                    $tipo_mensaje = 'danger';
                }
            }
        }
    }
}

// Cargar estudiante seleccionado de sesión
if (isset($_SESSION['estudiante_seleccionado']) && empty($info_estudiante)) {
    $info_estudiante = obtenerInfoEstudiantePorId($_SESSION['estudiante_seleccionado']);
}

// Función para cargar todos los datos del estudiante
function cargarDatosEstudiante($info_estudiante) {
    global $db, $periodo_activo, $materias_disponibles, $secciones_disponibles, 
           $materias_aprobadas, $materias_inscritas, $historial_secciones, 
           $trayecto_actual, $trayecto_inscripcion, $es_estudiante_nuevo, 
           $info_seccion_actual, $verificacion_avance, $puede_avanzar, $trayecto_siguiente;
    
    if (!$info_estudiante) return;
    
    $id_carrera = $info_estudiante['carrera'] ?? $info_estudiante['id_carrera'] ?? 0;
    $id_usuario = $info_estudiante['id'];
    
    // OBTENER SECCIÓN ACTUAL
    $info_seccion_actual = obtenerSeccionActualEstudiante($id_usuario);
    
    // OBTENER TRAYECTO DESDE LA SECCIÓN
    $info_trayecto = obtenerTrayectoDesdeSeccion($id_usuario);
    $trayecto_actual = $info_trayecto['trayecto'];
    
    // Si no tiene sección, usar sistema de aprobaciones
    if ($trayecto_actual == 0 && !$info_seccion_actual) {
        $trayecto_actual = obtenerTrayectoActual($id_usuario, $id_carrera);
    }
    
    // Verificar si es estudiante nuevo
    $es_estudiante_nuevo = esEstudianteNuevo($id_usuario);
    
    // VERIFICAR SI PUEDE AVANZAR (IGUAL QUE EN SECCIONES)
    $verificacion_avance = verificarAvancePorSeccion($id_usuario);
    $puede_avanzar = $verificacion_avance['puede_avanzar'];
    $trayecto_siguiente = $trayecto_actual + 1;
    
    // DETERMINAR TRAYECTO PARA INSCRIPCIÓN
    if ($puede_avanzar && $trayecto_actual < 4) {
        $trayecto_inscripcion = $trayecto_actual + 1;
    } else {
        $trayecto_inscripcion = $trayecto_actual;
    }
    
    // OBTENER MATERIAS APROBADAS
    $materias_aprobadas = obtenerMateriasAprobadasPorTrayecto($id_usuario, $trayecto_actual);
    
    // OBTENER SECCIONES DISPONIBLES
    if ($periodo_activo && $id_carrera > 0) {
        $secciones_disponibles = obtenerSeccionesTrayecto($id_carrera, $trayecto_inscripcion, $periodo_activo['id_periodo']);
    }
    
    // OBTENER MATERIAS PARA INSCRIPCIÓN
    if ($id_carrera > 0) {
        $materias_disponibles = obtenerMateriasDisponiblesIndividual($id_usuario, $trayecto_inscripcion, $id_carrera);
    }
    
    // OBTENER MATERIAS INSCRITAS
    $materias_inscritas = obtenerMateriasInscritasActuales($id_usuario);
    
    // OBTENER HISTORIAL
    $historial_secciones = obtenerHistorialSecciones($id_usuario);
}

// Cargar datos del estudiante si existe
if ($info_estudiante) {
    cargarDatosEstudiante($info_estudiante);
}

// Mostrar mensajes
if (!empty($mensaje)) {
    echo '<div class="alert alert-' . $tipo_mensaje . ' alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($mensaje) . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulopag; ?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"><?php echo $titulopag; ?></li>
            </ol>
        </div>
    </div>

    <!-- Búsqueda por cédula -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-search mr-1"></i>
                    Buscar Estudiante por Cédula
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="cedula">Número de Cédula</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        </div>
                                        <input type="text" 
                                               class="form-control" 
                                               id="cedula" 
                                               name="cedula" 
                                               placeholder="Ingrese la cédula del estudiante" 
                                               value="<?php echo isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : ''; ?>"
                                               required
                                               pattern="[0-9]+"
                                               title="Solo números permitidos">
                                    </div>
                                    <small class="form-text text-muted">Ingrese el número de cédula (solo números).</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" name="buscar_cedula" class="btn btn-primary btn-block">
                                        <i class="fas fa-search mr-1"></i> Buscar Estudiante
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mostrar resultados de búsqueda -->
    <?php if (!empty($estudiantes_encontrados) && count($estudiantes_encontrados) > 1): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-users mr-1"></i>
                    Resultados de Búsqueda
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Se encontraron <?php echo count($estudiantes_encontrados); ?> estudiantes. Seleccione uno:
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre Completo</th>
                                    <th>Carrera</th>
                                    <th>Contacto</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($estudiantes_encontrados as $est): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($est['cedula']); ?></td>
                                    <td><?php echo htmlspecialchars($est['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($est['carrera']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($est['contacto']); ?><br>
                                        <small><?php echo htmlspecialchars($est['email']); ?></small>
                                    </td>
                                    <td>
                                        <form method="POST" action="" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="id_estudiante" value="<?php echo $est['id']; ?>">
                                            <button type="submit" name="seleccionar_estudiante" class="btn btn-sm btn-success">
                                                <i class="fas fa-check mr-1"></i> Seleccionar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($info_estudiante): ?>
    <!-- Información del estudiante seleccionado -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Estudiante Seleccionado
                    <?php if ($es_estudiante_nuevo): ?>
                        <span class="badge badge-warning badge-pill float-right">ESTUDIANTE NUEVO</span>
                    <?php endif; ?>
                    <form method="POST" action="" class="float-right mr-2" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id_estudiante" value="0">
                        <button type="submit" name="seleccionar_estudiante" class="btn btn-sm btn-light">
                            <i class="fas fa-sync-alt mr-1"></i> Cambiar Estudiante
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($info_estudiante['idusuario']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($info_estudiante['nombre']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Carrera:</strong> <?php echo htmlspecialchars($info_estudiante['nombre_carrera'] ?? 'No asignada'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Trayecto Actual:</strong> 
                                <span class="badge badge-info"><?php echo $trayecto_actual; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Estado:</strong> 
                                <?php if ($es_estudiante_nuevo): ?>
                                    <span class="badge badge-primary">PRIMERA VEZ</span>
                                <?php else: ?>
                                    <span class="badge badge-info">EN PROCESO</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Período Activo:</strong> 
                                <?php echo $periodo_activo ? htmlspecialchars($periodo_activo['nombre_periodo']) : 'No hay período activo'; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Trayecto Inscripción:</strong> 
                                <span class="badge badge-primary"><?php echo $trayecto_inscripcion; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de Sección Actual -->
    <?php if ($info_seccion_actual): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-users mr-1"></i>
                    Sección Actual del Estudiante
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Código Sección:</strong> <?php echo htmlspecialchars($info_seccion_actual['codigo_seccion']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Trayecto:</strong> <span class="badge badge-info"><?php echo $info_seccion_actual['numero_trayecto']; ?></span></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Período:</strong> <?php echo htmlspecialchars($info_seccion_actual['nombre_periodo'] ?? 'No definido'); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Estado:</strong> <span class="badge badge-success">ACTIVA</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ESTADO ACADÉMICO (IGUAL QUE EN SECCIONES) -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-line mr-1"></i>
                    Estado Académico - Trayecto <?php echo $trayecto_actual; ?>
                    <?php if ($es_estudiante_nuevo): ?>
                        <span class="badge badge-light float-right">ESTUDIANTE NUEVO</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($trayecto_actual == 0 && $es_estudiante_nuevo): ?>
                        <div class="alert alert-primary">
                            <h5 class="alert-heading"><i class="fas fa-user-plus"></i> Estudiante Nuevo</h5>
                            <p>Este estudiante no tiene historial académico. Se inscribirá en el <strong>Trayecto 0</strong> por primera vez.</p>
                            <p class="mb-0"><strong>Condición para avanzar al Trayecto 1:</strong> Aprobar al menos el 50% de las materias del trayecto 0.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert <?php echo ($puede_avanzar && $trayecto_actual < 4) ? 'alert-success' : 'alert-warning'; ?>">
                            <h5 class="alert-heading">
                                <i class="fas fa-<?php echo ($puede_avanzar && $trayecto_actual < 4) ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                                <?php if ($puede_avanzar && $trayecto_actual < 4): ?>
                                    ¡Cumple requisitos para avanzar al Trayecto <?php echo $trayecto_siguiente; ?>!
                                <?php elseif ($trayecto_actual >= 4): ?>
                                    ¡El estudiante ha completado todos los trayectos!
                                <?php else: ?>
                                    No cumple requisitos para avanzar al siguiente trayecto
                                <?php endif; ?>
                            </h5>
                            <p class="mb-0">
                                <strong>Condición para avanzar:</strong><br>
                                <?php echo $verificacion_avance['detalles'] ?? 'Verificando requisitos...'; ?>
                            </p>
                            
                            <!-- BOTÓN DE AVANZAR (IGUAL QUE EN SECCIONES) -->
                            <?php if ($puede_avanzar && $trayecto_actual < 4): ?>
                                <hr>
                                <form method="POST" action="" class="mt-3">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id_estudiante" value="<?php echo $info_estudiante['id']; ?>">
                                    
                                    <div class="form-group">
                                        <label for="motivo_aprobacion"><small>Motivo de aprobación (opcional):</small></label>
                                        <textarea class="form-control form-control-sm" 
                                                  id="motivo_aprobacion" 
                                                  name="motivo_aprobacion" 
                                                  rows="2" 
                                                  placeholder="Ej: Cumple todos los requisitos académicos"></textarea>
                                    </div>
                                    
                                    <button type="submit" name="avanzar_estudiante_trayecto" class="btn btn-success btn-lg">
                                        <i class="fas fa-arrow-right mr-1"></i> AVANZAR AL TRAYECTO <?php echo $trayecto_siguiente; ?>
                                    </button>
                                    
                                    <small class="d-block text-muted mt-1">
                                        <i class="fas fa-info-circle"></i> Esta acción creará una nueva sección e inscribirá automáticamente las materias del Trayecto <?php echo $trayecto_siguiente; ?>.
                                    </small>
                                </form>
                            <?php elseif ($trayecto_actual >= 4): ?>
                                <hr>
                                <div class="alert alert-success">
                                    <i class="fas fa-graduation-cap"></i> 
                                    <strong>¡Felicidades!</strong> El estudiante ha completado todos los trayectos de la carrera.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mostrar progreso -->
                    <?php if (isset($verificacion_avance['total_materias']) && $verificacion_avance['total_materias'] > 0): ?>
                    <div class="mt-3">
                        <strong>Progreso del Trayecto <?php echo $trayecto_actual; ?>:</strong>
                        <?php 
                        $total = $verificacion_avance['total_materias'];
                        $aprobadas = $verificacion_avance['total_aprobadas'];
                        $porcentaje = ($total > 0) ? ($aprobadas / $total) * 100 : 0;
                        ?>
                        <div class="progress mt-2" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?php echo $porcentaje; ?>%" 
                                 aria-valuenow="<?php echo $porcentaje; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?php echo number_format($porcentaje, 1); ?>%
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">
                                <?php echo $aprobadas; ?> de <?php echo $total; ?> materias aprobadas
                            </small>
                            <?php if (isset($verificacion_avance['minimo_requerido']) && $verificacion_avance['minimo_requerido'] > 0): ?>
                            <small class="text-muted">
                                Mínimo requerido: <?php echo $verificacion_avance['minimo_requerido']; ?> materias
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Materias aprobadas -->
    <?php if (!empty($materias_aprobadas)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-check-circle mr-1"></i>
                    Materias Aprobadas - Trayecto <?php echo $trayecto_actual; ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Materia</th>
                                    <th>Créditos</th>
                                    <th>Período</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias_aprobadas as $materia): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                    <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                    <td><?php echo $materia['creditos']; ?></td>
                                    <td><?php echo htmlspecialchars($materia['nombre_periodo'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if ($materia['es_proyecto']): ?>
                                            <span class="badge badge-warning">PROYECTO</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">NORMAL</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Materias inscritas actualmente -->
    <?php if (!empty($materias_inscritas) && $periodo_activo): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <i class="fas fa-clipboard-check mr-1"></i>
                    Materias Inscritas en el Período Actual (<?php echo htmlspecialchars($periodo_activo['nombre_periodo']); ?>)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Trayecto</th>
                                    <th>Créditos</th>
                                    <th>Nota Mínima</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias_inscritas as $materia): 
                                    $nota_minima = obtenerNotaMinimaMateria($materia['id_materia']);
                                    $es_proyecto = $materia['es_proyecto'] ?? false;
                                    $nota_actual = obtenerNotaMateriaActualPeriodo($info_estudiante['id'], $materia['id_materia']);
                                    $estado_nota = ($nota_actual === null) ? 'Sin calificar' : 
                                        ((($es_proyecto && $nota_actual >= 16) || (!$es_proyecto && $nota_actual >= 12)) 
                                            ? 'Aprobada' 
                                            : 'Reprobada');
                                    $badge_color = ($estado_nota == 'Aprobada') ? 'success' : 
                                                 (($estado_nota == 'Reprobada') ? 'danger' : 'secondary');
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                    <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                    <td><?php echo $materia['trayecto']; ?></td>
                                    <td><?php echo $materia['creditos']; ?></td>
                                    <td><?php echo $nota_minima; ?></td>
                                    <td>
                                        <?php if ($es_proyecto): ?>
                                            <span class="badge badge-warning">PROYECTO</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">NORMAL</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $badge_color; ?>">
                                            <?php echo $estado_nota; ?>
                                            <?php if ($nota_actual !== null): ?>
                                                (<?php echo $nota_actual; ?>)
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Estas materias ya están inscritas en el período actual. 
                        Aparecerán aquí hasta que se cierre el período o se aprueben.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Historial de secciones -->
    <?php if (!empty($historial_secciones)): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-history mr-1"></i>
                    Historial de Secciones del Estudiante
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <th>Sección</th>
                                    <th>Trayecto</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historial_secciones as $historial): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($historial['nombre_periodo'] ?? 'Desconocido'); ?></td>
                                    <td><?php echo htmlspecialchars($historial['codigo_seccion'] ?? 'Sin sección'); ?></td>
                                    <td><?php echo $historial['numero_trayecto'] ?? '0'; ?></td>
                                    <td><?php echo isset($historial['fecha_inscripcion']) ? date('d/m/Y', strtotime($historial['fecha_inscripcion'])) : 'Sin fecha'; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo ($historial['estatus'] ?? '') == 'activo' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($historial['estatus'] ?? 'Desconocido'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Formulario de Inscripción -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Inscripción de Materias - Trayecto <?php echo $trayecto_inscripcion; ?>
                    <?php if ($trayecto_inscripcion == 0 && $es_estudiante_nuevo): ?>
                        <span class="badge badge-warning float-right">INICIO</span>
                    <?php elseif ($puede_avanzar && $trayecto_actual < 4): ?>
                        <span class="badge badge-success float-right">AVANCE APROBADO</span>
                    <?php else: ?>
                        <span class="badge badge-info float-right">REINSCRIPCIÓN</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if (!$periodo_activo): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> No hay un período académico activo. Contacte al administrador.
                        </div>
                    <?php elseif ($info_estudiante['carrera'] <= 0): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> El estudiante no tiene una carrera asignada.
                        </div>
                    <?php else: ?>
                        <?php if ($puede_avanzar && $trayecto_actual < 4): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <strong>¡El estudiante puede inscribirse en el Trayecto <?php echo $trayecto_siguiente; ?>!</strong>
                                <p class="mb-0">Cumple con los requisitos para avanzar. Ahora puede inscribir materias del siguiente trayecto.</p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="id_estudiante" value="<?php echo $info_estudiante['id']; ?>">
                            
                            <div class="form-group">
                                <label for="id_seccion">Seleccionar Sección (Trayecto <?php echo $trayecto_inscripcion; ?>) <small class="text-muted">(Opcional)</small></label>
                                <select class="form-control" id="id_seccion" name="id_seccion">
                                    <option value="0">Sin sección (inscripción general)</option>
                                    <?php foreach ($secciones_disponibles as $seccion): ?>
                                    <option value="<?php echo $seccion['id_seccion']; ?>">
                                        <?php echo htmlspecialchars($seccion['codigo_seccion']); ?> - 
                                        Trayecto <?php echo $seccion['numero_trayecto']; ?>
                                        (Cupo: <?php echo $seccion['inscritos']; ?>/<?php echo $seccion['capacidad_maxima']; ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    Secciones disponibles para el Trayecto <?php echo $trayecto_inscripcion; ?> (opcional).
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label>Materias para Inscripción (Trayecto <?php echo $trayecto_inscripcion; ?>)</label>
                                <div class="border p-3" style="max-height: 400px; overflow-y: auto;">
                                    <?php if (empty($materias_disponibles)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <?php 
                                            if ($trayecto_inscripcion == $trayecto_actual && !$es_estudiante_nuevo) {
                                                echo "¡Felicidades! Ya está inscrito en todas las materias de este trayecto.";
                                            } elseif ($es_estudiante_nuevo) {
                                                echo "Se mostrarán todas las materias del Trayecto 0 para inscripción inicial.";
                                            } else {
                                                echo "No hay materias disponibles para este trayecto.";
                                            }
                                            ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="select_all">
                                            <label class="form-check-label" for="select_all">
                                                <strong><i class="fas fa-check-double"></i> Seleccionar todas</strong>
                                            </label>
                                        </div>
                                        <hr>
                                        <?php foreach ($materias_disponibles as $materia): 
                                            $nota_minima = obtenerNotaMinimaMateria($materia['id_materia']);
                                            $es_proyecto = $materia['es_proyecto'] ?? false;
                                            $ya_inscrita = materiaYaInscritaPeriodo($info_estudiante['id'], $materia['id_materia']);
                                            $nota_actual = obtenerNotaMateriaActualPeriodo($info_estudiante['id'], $materia['id_materia']);
                                            $reprobada = ($nota_actual !== null && 
                                                         (($es_proyecto && $nota_actual < 16) || 
                                                          (!$es_proyecto && $nota_actual < 12)));
                                        ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input materia-checkbox" type="checkbox" 
                                                   name="materias[]" 
                                                   value="<?php echo $materia['id_materia']; ?>" 
                                                   id="materia_<?php echo $materia['id_materia']; ?>"
                                                   <?php echo $ya_inscrita ? 'disabled' : ''; ?>>
                                            <label class="form-check-label <?php echo $ya_inscrita ? 'text-muted' : ''; ?>" 
                                                   for="materia_<?php echo $materia['id_materia']; ?>">
                                                <?php echo htmlspecialchars($materia['cod_materia'] . ' - ' . $materia['nombre_materia']); ?>
                                                <?php if ($es_proyecto): ?>
                                                    <span class="badge badge-warning">PROYECTO</span>
                                                <?php endif; ?>
                                                <?php if ($ya_inscrita): ?>
                                                    <span class="badge badge-secondary">YA INSCRITA</span>
                                                <?php elseif ($reprobada): ?>
                                                    <span class="badge badge-danger">REPROBADA</span>
                                                <?php else: ?>
                                                    <span class="badge badge-primary">NUEVA</span>
                                                <?php endif; ?>
                                                <small class="text-muted d-block">
                                                    Créditos: <?php echo $materia['creditos']; ?> | 
                                                    Nota mínima: <?php echo $nota_minima; ?> | 
                                                    Trayecto: <?php echo $materia['trayecto']; ?>
                                                    <?php if ($reprobada && $nota_actual !== null): ?>
                                                        | Nota anterior: <?php echo $nota_actual; ?>
                                                    <?php endif; ?>
                                                </small>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <button type="submit" name="inscribir_materias" class="btn btn-success btn-lg" 
                                    <?php echo (empty($materias_disponibles)) ? 'disabled' : ''; ?>>
                                <i class="fas fa-save mr-1"></i> Inscribir Materias Seleccionadas
                            </button>
                            
                            <?php if (!empty($materias_inscritas)): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Nota:</strong> Las materias marcadas como "YA INSCRITA" no se pueden volver a inscribir en el mismo período.
                            </div>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Reglas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-info-circle mr-1"></i>
                    Resumen de Reglas de Inscripción
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-arrow-right text-primary"></i> Condiciones para Avanzar:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Trayecto 0 → Trayecto 1:</strong> Aprobar el 50% de las materias del trayecto 0</li>
                                <li class="mb-2"><strong>Trayecto 1 → Trayecto 2:</strong> Aprobar Proyecto Socio Integrador (nota ≥ 16)</li>
                                <li class="mb-2"><strong>Trayecto 2 → Trayecto 3:</strong> Aprobar todas las materias y obtener primer título</li>
                                <li class="mb-2"><strong>Trayecto 3 → Trayecto 4:</strong> Aprobar Proyecto Socio Integrador (nota ≥ 16)</li>
                                <li class="mb-2"><strong>Trayecto 4:</strong> Último trayecto, no puede avanzar más</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-clipboard-check text-success"></i> Reglas de Inscripción:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Nota mínima aprobatoria:</strong> 12 puntos</li>
                                <li class="mb-2"><strong>Nota mínima para proyectos:</strong> 16 puntos</li>
                                <li class="mb-2"><strong>Reinscripción:</strong> Solo se inscriben materias NO inscritas actualmente</li>
                                <li class="mb-2"><strong>Ya inscritas:</strong> No se pueden volver a inscribir en mismo período</li>
                                <li class="mb-2"><strong>Nuevos estudiantes:</strong> Pueden inscribir todas las materias del Trayecto 0</li>
                                <li class="mb-2"><strong>Sección:</strong> El trayecto se determina por la sección activa del estudiante</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select_all');
    const materiaCheckboxes = document.querySelectorAll('.materia-checkbox:not(:disabled)');
    
    if (selectAllCheckbox && materiaCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            materiaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            actualizarEstadoBoton();
        });
        
        materiaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(materiaCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && Array.from(materiaCheckboxes).some(cb => cb.checked);
                actualizarEstadoBoton();
            });
        });
        
        function actualizarEstadoBoton() {
            const inscribirBtn = document.querySelector('button[name="inscribir_materias"]');
            if (inscribirBtn) {
                const algunaSeleccionada = Array.from(materiaCheckboxes).some(cb => cb.checked);
                inscribirBtn.disabled = !algunaSeleccionada;
            }
        }
        actualizarEstadoBoton();
    }
    
    const cedulaInput = document.getElementById('cedula');
    if (cedulaInput) {
        cedulaInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
});

// Función para confirmar acciones
function showConfirm(options) {
    const title = options.title || 'Confirmar acción';
    const message = options.message || '¿Está seguro?';
    const confirmText = options.confirmText || 'Confirmar';
    
    const modalHtml = `
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">${message}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmBtn">${confirmText}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const existingModal = document.getElementById('confirmModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    const modal = document.getElementById('confirmModal');
    const confirmBtn = document.getElementById('confirmBtn');
    
    $(modal).modal('show');
    
    return new Promise((resolve) => {
        confirmBtn.onclick = function() {
            $(modal).modal('hide');
            resolve(true);
        };
        
        $(modal).on('hidden.bs.modal', function() {
            setTimeout(() => {
                modal.remove();
                resolve(false);
            }, 300);
        });
    });
}

// Manejar botón de avanzar
document.querySelectorAll('button[name="avanzar_estudiante_trayecto"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const trayectoActual = <?php echo $trayecto_actual; ?>;
        const siguiente = trayectoActual + 1;
        
        showConfirm({
            title: 'Confirmar Avance',
            message: `¿Está seguro de avanzar al estudiante al Trayecto ${siguiente}?<br><br>Esta acción creará una nueva sección para el estudiante en el Trayecto ${siguiente} e inscribirá automáticamente sus materias.`,
            confirmText: 'Avanzar'
        }).then(confirmed => {
            if (confirmed) {
                form.submit();
            }
        });
    });
});

// Manejar botón de inscripción
document.querySelectorAll('button[name="inscribir_materias"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');
        const checkboxes = document.querySelectorAll('input[name="materias[]"]:checked:not(:disabled)');
        
        if (checkboxes.length === 0) {
            showConfirm({
                title: 'Atención',
                message: '⚠️ Por favor seleccione al menos una materia.',
                confirmText: 'OK'
            });
            return;
        }
        
        showConfirm({
            title: 'Confirmar Inscripción',
            message: `¿Está seguro de inscribir ${checkboxes.length} materia(s)?`,
            confirmText: 'Inscribir'
        }).then(confirmed => {
            if (confirmed) {
                form.submit();
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>