<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Inscripción de Materias por Trayecto";
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

// Variables para control de avance
$cumple_requisitos = false;
$detalles_requisitos = '';
$total_materias = 0;
$total_aprobadas = 0;
$minimo_requerido = 0;
$aprobacion_existente = false;
$info_aprobacion = null;
$historial_aprobaciones = [];

// Procesar búsqueda por cédula (POST para seguridad)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar_cedula'])) {
        // Validar CSRF token
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
                            // Un solo resultado
                            $estudiantes_encontrados = [$resultados];
                        } else {
                            // Múltiples resultados
                            $estudiantes_encontrados = $resultados;
                        }
                        
                        if (count($estudiantes_encontrados) == 1) {
                            // Seleccionar automáticamente si solo hay uno
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
        // Validar CSRF token
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
            }
        }
    }
    
    // Procesar inscripción de materias
    if (isset($_POST['inscribir_materias'])) {
        // Validar CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            $id_seccion = intval($_POST['id_seccion'] ?? 0);
            $materias_ids = isset($_POST['materias']) ? array_map('intval', $_POST['materias']) : [];
            
            if ($id_estudiante > 0 && $id_seccion > 0 && !empty($materias_ids)) {
                if (inscribirMateriasEstudiante($id_estudiante, $id_seccion, $materias_ids)) {
                    $mensaje = "✅ Materias inscritas correctamente para el estudiante.";
                    $tipo_mensaje = 'success';
                    
                    // Actualizar información del estudiante
                    $info_estudiante = obtenerInfoEstudiantePorId($id_estudiante);
                } else {
                    $mensaje = "❌ Error al inscribir las materias. Por favor intente nuevamente.";
                    $tipo_mensaje = 'danger';
                }
            } else {
                $mensaje = "⚠️ Debe seleccionar una sección y al menos una materia para inscribir.";
                $tipo_mensaje = 'warning';
            }
        }
    }
    
    // Procesar aprobación de avance de trayecto
    if (isset($_POST['aprobar_avance'])) {
        // Validar CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            $trayecto_actual_post = intval($_POST['trayecto_actual'] ?? 0);
            $motivo = trim($_POST['motivo_aprobacion'] ?? '');
            
            if ($id_estudiante > 0 && $trayecto_actual_post >= 0) {
                // Obtener información actualizada del estudiante
                $info_estudiante_temp = obtenerInfoEstudiantePorId($id_estudiante);
                $id_carrera = $info_estudiante_temp['carrera'] ?? $info_estudiante_temp['id_carrera'] ?? 0;
                
                if ($id_carrera > 0) {
                    $resultado = aprobarAvanceTrayecto($id_estudiante, $id_carrera, $trayecto_actual_post, $motivo);
                    
                    if ($resultado['success']) {
                        $mensaje = "✅ " . $resultado['message'];
                        $tipo_mensaje = 'success';
                        
                        // Actualizar información del estudiante
                        $info_estudiante = $info_estudiante_temp;
                    } else {
                        $mensaje = "❌ " . $resultado['message'];
                        $tipo_mensaje = 'danger';
                    }
                } else {
                    $mensaje = "❌ Error: El estudiante no tiene una carrera asignada.";
                    $tipo_mensaje = 'danger';
                }
            } else {
                $mensaje = "⚠️ Datos incompletos para aprobar avance.";
                $tipo_mensaje = 'warning';
            }
        }
    }
    
    // Procesar rechazo de avance de trayecto
    if (isset($_POST['rechazar_avance'])) {
        // Validar CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $mensaje = "Error de seguridad. Token inválido.";
            $tipo_mensaje = 'danger';
        } else {
            $id_estudiante = intval($_POST['id_estudiante'] ?? 0);
            $trayecto_actual_post = intval($_POST['trayecto_actual'] ?? 0);
            
            if ($id_estudiante > 0 && $trayecto_actual_post >= 0) {
                // Obtener información actualizada del estudiante
                $info_estudiante_temp = obtenerInfoEstudiantePorId($id_estudiante);
                $id_carrera = $info_estudiante_temp['carrera'] ?? $info_estudiante_temp['id_carrera'] ?? 0;
                
                if ($id_carrera > 0) {
                    if (rechazarAvanceTrayecto($id_estudiante, $id_carrera, $trayecto_actual_post)) {
                        $mensaje = "✅ Aprobación de avance eliminada correctamente.";
                        $tipo_mensaje = 'success';
                        
                        // Actualizar información del estudiante
                        $info_estudiante = $info_estudiante_temp;
                    } else {
                        $mensaje = "❌ Error al eliminar la aprobación de avance.";
                        $tipo_mensaje = 'danger';
                    }
                } else {
                    $mensaje = "❌ Error: No se pudo obtener información de carrera.";
                    $tipo_mensaje = 'danger';
                }
            } else {
                $mensaje = "⚠️ Datos incompletos para rechazar avance.";
                $tipo_mensaje = 'warning';
            }
        }
    }
}

// Cargar estudiante seleccionado de sesión
if (isset($_SESSION['estudiante_seleccionado']) && empty($info_estudiante)) {
    $info_estudiante = obtenerInfoEstudiantePorId($_SESSION['estudiante_seleccionado']);
}

// Si hay información del estudiante, cargar datos académicos
if ($info_estudiante) {
    $id_carrera = $info_estudiante['carrera'] ?? $info_estudiante['id_carrera'] ?? 0;
    
    // Verificar si es estudiante nuevo (sin notas ni inscripciones)
    $es_estudiante_nuevo = esEstudianteNuevo($info_estudiante['id']);
    
    // Obtener trayecto actual (siempre empieza en 0, luego según aprobaciones)
    $trayecto_actual = obtenerTrayectoActual($info_estudiante['id'], $id_carrera);
    
    // Verificar requisitos para avanzar
    if ($id_carrera > 0) {
        $resultado_requisitos = verificarRequisitosTrayecto($info_estudiante['id'], $trayecto_actual, $id_carrera);
        $cumple_requisitos = $resultado_requisitos['cumple_requisitos'];
        $detalles_requisitos = $resultado_requisitos['detalles'];
        $total_materias = $resultado_requisitos['total_materias'];
        $total_aprobadas = $resultado_requisitos['total_aprobadas'];
        $minimo_requerido = $resultado_requisitos['minimo_requerido'] ?? 0;
        
        // Verificar si ya existe aprobación para este trayecto
        $aprobacion_existente = verificarAprobacionExistente($info_estudiante['id'], $id_carrera, $trayecto_actual);
        $info_aprobacion = $aprobacion_existente ? $aprobacion_existente : null;
        
        // Obtener historial de aprobaciones
        $historial_aprobaciones = obtenerHistorialAprobaciones($info_estudiante['id'], $id_carrera);
    }
    
    // Obtener materias aprobadas en el trayecto actual
    $materias_aprobadas = obtenerMateriasAprobadas($info_estudiante['id'], $trayecto_actual);
    
    // Determinar trayecto para inscripción
    $trayecto_inscripcion = $trayecto_actual;
    
    // Obtener secciones disponibles
    if ($periodo_activo && $id_carrera > 0) {
        $secciones_disponibles = obtenerSeccionesTrayecto($id_carrera, $trayecto_inscripcion, $periodo_activo['id_periodo']);
    }
    
    // Obtener materias para inscripción (siempre del trayecto actual, solo reprobadas)
    if ($id_carrera > 0) {
        $materias_disponibles = obtenerMateriasReprobadas($info_estudiante['id'], $trayecto_actual, $id_carrera);
        
        // Si no tiene reprobadas, mostrar todas las materias del trayecto
        if (empty($materias_disponibles) && $trayecto_actual == 0 && $es_estudiante_nuevo) {
            $materias_disponibles = obtenerMateriasTrayecto($id_carrera, $trayecto_actual);
        }
    }
    
    // Obtener materias inscritas actualmente
    $materias_inscritas = obtenerMateriasInscritas($info_estudiante['id']);
    
    // Obtener historial de secciones
    $historial_secciones = obtenerHistorialSecciones($info_estudiante['id']);
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
                                <?php if ($es_estudiante_nuevo): ?>
                                    <span class="badge badge-success">NUEVO</span>
                                <?php endif; ?>
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
                    
                    <!-- Historial de aprobaciones -->
                    <?php if (!empty($historial_aprobaciones)): ?>
                    <div class="mt-3">
                        <h6><i class="fas fa-history mr-1"></i> Historial de Aprobaciones de Trayecto:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Trayecto Aprobado</th>
                                        <th>Aprobado por</th>
                                        <th>Fecha Aprobación</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial_aprobaciones as $aprobacion): ?>
                                    <tr>
                                        <td>Trayecto <?php echo $aprobacion['trayecto_actual']; ?></td>
                                        <td><?php echo htmlspecialchars($aprobacion['nombre_aprobador'] ?? 'Administrador'); ?></td>
                                        <td><?php echo isset($aprobacion['fecha_aprobacion']) ? date('d/m/Y H:i', strtotime($aprobacion['fecha_aprobacion'])) : 'N/A'; ?></td>
                                        <td><?php echo !empty($aprobacion['motivo']) ? htmlspecialchars($aprobacion['motivo']) : 'Sin motivo especificado'; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Historial de secciones -->
                    <?php if (!empty($historial_secciones)): ?>
                    <div class="mt-3">
                        <h6><i class="fas fa-book mr-1"></i> Historial de Inscripciones en Secciones:</h6>
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
                                        <td><?php echo $historial['id_trayecto'] ?? '0'; ?></td>
                                        <td><?php echo isset($historial['fecha_inscripcion']) ? date('d/m/Y', strtotime($historial['fecha_inscripcion'])) : 'Sin fecha'; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo ($historial['estatus'] ?? '') == 'Activo' ? 'success' : 'secondary'; ?>">
                                                <?php echo $historial['estatus'] ?? 'Desconocido'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Materias inscritas actualmente -->
    <?php if (!empty($materias_inscritas) && $periodo_activo): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-warning">
                    <i class="fas fa-clipboard-check mr-1"></i>
                    Materias Actualmente Inscritas (<?php echo htmlspecialchars($periodo_activo['nombre_periodo']); ?>)
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
                                    <th>Sección</th>
                                    <th>Nota Mínima</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias_inscritas as $materia): 
                                    $nota_minima = obtenerNotaMinimaMateria($materia['id_materia']);
                                    $es_proyecto = esProyectoSocio($materia['id_materia']);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($materia['cod_materia']); ?></td>
                                    <td><?php echo htmlspecialchars($materia['nombre_materia']); ?></td>
                                    <td><?php echo $materia['trayecto']; ?></td>
                                    <td><?php echo $materia['creditos']; ?></td>
                                    <td><?php echo htmlspecialchars($materia['codigo_seccion'] ?? 'No asignada'); ?></td>
                                    <td><?php echo $nota_minima; ?></td>
                                    <td>
                                        <?php if ($es_proyecto): ?>
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Estas materias ya están inscritas en el período actual. No se pueden volver a inscribir.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Paneles de estado e inscripción -->
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-line mr-1"></i>
                    Estado Académico - Trayecto <?php echo $trayecto_actual; ?>
                    <?php if ($es_estudiante_nuevo): ?>
                        <span class="badge badge-light float-right">ESTUDIANTE NUEVO</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($es_estudiante_nuevo): ?>
                        <div class="alert alert-primary">
                            <h5 class="alert-heading"><i class="fas fa-user-plus"></i> Estudiante Nuevo</h5>
                            <p>Este estudiante no tiene historial académico. Se inscribirá en el <strong>Trayecto 0</strong> por primera vez.</p>
                            <p class="mb-0"><strong>Condición para avanzar al Trayecto 1:</strong> Aprobar al menos el 50% de las materias del trayecto 0.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert <?php echo $cumple_requisitos ? 'alert-success' : 'alert-warning'; ?>">
                            <h5 class="alert-heading">
                                <i class="fas fa-<?php echo $cumple_requisitos ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                                <?php 
                                if ($aprobacion_existente) {
                                    echo "¡Avance APROBADO al Trayecto " . ($trayecto_actual + 1) . "!";
                                } elseif ($cumple_requisitos) {
                                    echo "¡Cumple requisitos para avanzar al Trayecto " . ($trayecto_actual + 1) . "!";
                                } else {
                                    echo "No cumple requisitos para avanzar al siguiente trayecto";
                                }
                                ?>
                            </h5>
                            <p class="mb-0">
                                <strong>Condición para avanzar:</strong><br>
                                <?php echo $detalles_requisitos; ?>
                            </p>
                            
                            <?php if ($aprobacion_existente): ?>
                                <hr>
                                <div class="alert alert-info">
                                    <i class="fas fa-user-check"></i> 
                                    <strong>Avance ya aprobado:</strong>
                                    <ul class="mb-0 pl-3">
                                        <li>Aprobado por: <?php echo htmlspecialchars($info_aprobacion['nombre_aprobador']); ?></li>
                                        <li>Fecha: <?php echo date('d/m/Y H:i', strtotime($info_aprobacion['fecha_aprobacion'])); ?></li>
                                        <?php if (!empty($info_aprobacion['motivo'])): ?>
                                            <li>Motivo: <?php echo htmlspecialchars($info_aprobacion['motivo']); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                    
                                    <!-- Botón para revocar aprobación -->
                                    <?php if ($trayecto_actual < 4): ?>
                                    <form method="POST" action="" class="mt-2">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="id_estudiante" value="<?php echo $info_estudiante['id']; ?>">
                                        <input type="hidden" name="trayecto_actual" value="<?php echo $trayecto_actual; ?>">
                                        
                                        <button type="submit" name="rechazar_avance" class="btn btn-warning btn-sm" 
                                                onclick="return confirm('¿Está seguro de revocar la aprobación de avance al Trayecto ' + (<?php echo $trayecto_actual; ?> + 1) + '?')">
                                            <i class="fas fa-times-circle mr-1"></i> REVOCAR APROBACIÓN
                                        </button>
                                        <small class="d-block text-muted mt-1">
                                            <i class="fas fa-exclamation-triangle"></i> Revocar esta aprobación devolverá al estudiante al Trayecto <?php echo $trayecto_actual; ?>.
                                        </small>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($cumple_requisitos && $trayecto_actual < 4): ?>
                                <hr>
                                <!-- Formulario para aprobar avance -->
                                <form method="POST" action="" class="mt-3">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id_estudiante" value="<?php echo $info_estudiante['id']; ?>">
                                    <input type="hidden" name="trayecto_actual" value="<?php echo $trayecto_actual; ?>">
                                    
                                    <div class="form-group">
                                        <label for="motivo_aprobacion"><small>Motivo de aprobación (opcional):</small></label>
                                        <textarea class="form-control form-control-sm" 
                                                  id="motivo_aprobacion" 
                                                  name="motivo_aprobacion" 
                                                  rows="2" 
                                                  placeholder="Ej: Cumple todos los requisitos académicos"></textarea>
                                    </div>
                                    
                                    <button type="submit" name="aprobar_avance" class="btn btn-success btn-lg">
                                        <i class="fas fa-check-circle mr-1"></i> APROBAR AVANCE AL TRAYECTO <?php echo ($trayecto_actual + 1); ?>
                                    </button>
                                    
                                    <small class="d-block text-muted mt-1">
                                        <i class="fas fa-info-circle"></i> Esta acción permitirá al estudiante inscribirse en el Trayecto <?php echo ($trayecto_actual + 1); ?>.
                                    </small>
                                </form>
                            <?php elseif ($trayecto_actual == 4): ?>
                                <hr>
                                <div class="alert alert-success">
                                    <i class="fas fa-graduation-cap"></i> 
                                    <strong>¡Felicidades!</strong> El estudiante ha completado todos los trayectos de la carrera.
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Materias aprobadas en el trayecto actual -->
                        <h6><i class="fas fa-check-circle text-success mr-1"></i> Materias Aprobadas en Trayecto <?php echo $trayecto_actual; ?>:</h6>
                        <?php if (!empty($materias_aprobadas)): ?>
                            <ul class="list-group mb-3">
                                <?php foreach ($materias_aprobadas as $materia): ?>
                                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($materia['nombre_materia']); ?>
                                    <?php if (esProyectoSocio($materia['id_materia'])): ?>
                                        <span class="badge badge-warning badge-pill">PROYECTO</span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="alert alert-secondary">
                                <i class="fas fa-info-circle"></i> No tiene materias aprobadas en este trayecto.
                            </div>
                        <?php endif; ?>
                        
                        <!-- Progreso del trayecto -->
                        <?php if ($trayecto_actual == 0 && $total_materias > 0): ?>
                        <div class="mt-3">
                            <strong>Progreso del Trayecto 0:</strong>
                            <?php 
                            $porcentaje = ($total_aprobadas / $total_materias) * 100;
                            ?>
                            <div class="progress mt-2" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: <?php echo $porcentaje; ?>%" 
                                     aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo number_format($porcentaje, 1); ?>%
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    <?php echo $total_aprobadas; ?> de <?php echo $total_materias; ?> materias aprobadas
                                </small>
                                <small class="text-muted">
                                    Mínimo requerido: <?php echo $minimo_requerido; ?> materias (50%)
                                </small>
                            </div>
                            <?php if ($total_aprobadas >= $minimo_requerido && !$aprobacion_existente): ?>
                                <div class="alert alert-success mt-2">
                                    <i class="fas fa-check-circle"></i> 
                                    <strong>¡Cumple con el mínimo requerido!</strong> 
                                    Puede aprobar el avance al Trayecto 1.
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Inscripción de Materias - Trayecto <?php echo $trayecto_inscripcion; ?>
                    <?php if ($trayecto_inscripcion == 0 && $es_estudiante_nuevo): ?>
                        <span class="badge badge-warning float-right">INICIO</span>
                    <?php elseif ($aprobacion_existente): ?>
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
                    <?php elseif (empty($secciones_disponibles)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> No hay secciones disponibles para el Trayecto <?php echo $trayecto_inscripcion; ?> en este período.
                        </div>
                    <?php elseif ($info_estudiante['carrera'] <= 0): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> El estudiante no tiene una carrera asignada.
                        </div>
                    <?php else: ?>
                        <?php if ($aprobacion_existente): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <strong>¡El estudiante puede inscribirse en el Trayecto <?php echo ($trayecto_actual + 1); ?>!</strong>
                                <p class="mb-0">Ya fue aprobado para avanzar. Ahora puede inscribir materias del siguiente trayecto.</p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="id_estudiante" value="<?php echo $info_estudiante['id']; ?>">
                            
                            <div class="form-group">
                                <label for="id_seccion">Seleccionar Sección (Trayecto <?php echo $trayecto_inscripcion; ?>)</label>
                                <select class="form-control" id="id_seccion" name="id_seccion" required>
                                    <option value="">Seleccione una sección...</option>
                                    <?php foreach ($secciones_disponibles as $seccion): ?>
                                    <option value="<?php echo $seccion['id_seccion']; ?>">
                                        <?php echo htmlspecialchars($seccion['codigo_seccion']); ?> - 
                                        Horario: <?php echo htmlspecialchars($seccion['horario']); ?> - 
                                        Aula: <?php echo htmlspecialchars($seccion['aula_asignada']); ?>
                                        <?php if ($seccion['capacidad_maxima']): ?>
                                            (Cupo: <?php echo $seccion['capacidad_maxima']; ?>)
                                        <?php endif; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    Secciones disponibles para el Trayecto <?php echo $trayecto_inscripcion; ?>.
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label>Materias para Inscripción (Trayecto <?php echo $trayecto_inscripcion; ?>)</label>
                                <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                    <?php if (empty($materias_disponibles)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <?php 
                                            if ($trayecto_inscripcion == $trayecto_actual && !$es_estudiante_nuevo) {
                                                echo "¡Felicidades! Ya aprobó todas las materias de este trayecto.";
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
                                            $es_proyecto = esProyectoSocio($materia['id_materia']);
                                            
                                            // Verificar si ya está inscrita
                                            $ya_inscrita = false;
                                            foreach ($materias_inscritas as $inscrita) {
                                                if ($inscrita['id_materia'] == $materia['id_materia']) {
                                                    $ya_inscrita = true;
                                                    break;
                                                }
                                            }
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
                                                <?php endif; ?>
                                                <small class="text-muted d-block">
                                                    Créditos: <?php echo $materia['creditos']; ?> | 
                                                    Nota mínima: <?php echo $nota_minima; ?> | 
                                                    Trayecto: <?php echo $materia['trayecto']; ?>
                                                </small>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <button type="submit" name="inscribir_materias" class="btn btn-success btn-lg" 
                                    <?php echo (empty($materias_disponibles) || empty($secciones_disponibles)) ? 'disabled' : ''; ?>>
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
                                <li class="mb-2"><strong>Todos empiezan:</strong> En Trayecto 0</li>
                                <li class="mb-2"><strong>Avance manual:</strong> Requiere aprobación explícita del administrador</li>
                                <li class="mb-2"><strong>Nota mínima aprobatoria:</strong> 12 puntos</li>
                                <li class="mb-2"><strong>Nota mínima para proyectos:</strong> 16 puntos</li>
                                <li class="mb-2"><strong>Reinscripción:</strong> Solo se inscriben materias reprobadas</li>
                                <li class="mb-2"><strong>Ya inscritas:</strong> No se pueden volver a inscribir en mismo período</li>
                                <li class="mb-2"><strong>Nuevos estudiantes:</strong> Pueden inscribir todas las materias del Trayecto 0</li>
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
            const idSeccion = document.getElementById('id_seccion');
            
            if (inscribirBtn) {
                const algunaSeleccionada = Array.from(materiaCheckboxes).some(cb => cb.checked);
                const seccionSeleccionada = idSeccion && idSeccion.value !== '';
                
                inscribirBtn.disabled = !(algunaSeleccionada && seccionSeleccionada);
            }
        }
        
        const idSeccion = document.getElementById('id_seccion');
        if (idSeccion) {
            idSeccion.addEventListener('change', actualizarEstadoBoton);
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
</script>

<?php include("includes/footer.php"); ?>