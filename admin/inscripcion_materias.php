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

include("includes/head.php");

// Obtener período activo
$periodo_activo = obtenerPeriodoActivo();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['inscribir'])) {
        $id_usuario = intval($_POST['id_usuario']);
        $id_seccion = intval($_POST['id_seccion']);
        $materias_ids = isset($_POST['materias']) ? array_map('intval', $_POST['materias']) : [];
        
        if (!empty($materias_ids)) {
            if (inscribirMateriasEstudiante($id_usuario, $id_seccion, $materias_ids)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Éxito!</strong> Materias inscritas correctamente.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> No se pudieron inscribir las materias.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
            }
        }
    }
    
    // Ejecutar script para marcar proyectos socio (solo si se solicita)
    if (isset($_POST['marcar_proyectos'])) {
        $total = marcarProyectosSocio();
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Información!</strong> Se marcaron ' . $total . ' materias como Proyecto Socio Integrador.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>';
    }
}

// Obtener parámetros
$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;
$trayecto_actual = 0;
$info_estudiante = null;
$materias_disponibles = [];
$secciones_disponibles = [];
$materias_aprobadas = [];
$puede_avanzar = false;
$trayecto_inscripcion = 0;

if ($id_usuario > 0) {
    // Obtener información del estudiante
    $info_estudiante = obtenerInfoEstudiante($id_usuario);
    
    if ($info_estudiante) {
        $trayecto_actual = obtenerTrayectoActualEstudiante($id_usuario);
        $id_carrera = $info_estudiante['carrera'];
        
        // Obtener materias aprobadas
        $materias_aprobadas = obtenerMateriasAprobadas($id_usuario, $trayecto_actual);
        
        // Verificar si puede avanzar
        $puede_avanzar = puedeAvanzarTrayecto($id_usuario, $trayecto_actual, $id_carrera);
        
        // Si puede avanzar, ofrecer siguiente trayecto
        $trayecto_inscripcion = $puede_avanzar ? $trayecto_actual + 1 : $trayecto_actual;
        
        // Obtener secciones disponibles
        if ($periodo_activo) {
            $secciones_disponibles = obtenerSeccionesTrayecto($id_carrera, $trayecto_inscripcion, $periodo_activo['id_periodo']);
        }
        
        // Obtener materias para inscripción
        if ($trayecto_inscripcion == $trayecto_actual) {
            // Inscribir solo reprobadas
            $materias_disponibles = obtenerMateriasReprobadas($id_usuario, $trayecto_actual);
        } else {
            // Inscribir todas del nuevo trayecto
            $materias_disponibles = obtenerMateriasTrayecto($id_carrera, $trayecto_inscripcion);
        }
    }
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

    <!-- Botón para marcar proyectos socio (solo admin) -->
    <?php if (verificarPermiso('admin', false)): ?>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-warning">
                <form method="POST" action="" class="d-inline">
                    <strong>Configuración inicial:</strong> Si acabas de agregar el campo "es_proyecto_socio" a la tabla materias, ejecuta este script para marcar automáticamente las materias de proyecto.
                    <button type="submit" name="marcar_proyectos" class="btn btn-sm btn-warning ml-2">
                        <i class="fas fa-magic"></i> Marcar Proyectos Socio Automáticamente
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-search mr-1"></i>
                    Buscar Estudiante
                </div>
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="id_usuario">Seleccionar Estudiante</label>
                                    <select class="form-control" id="id_usuario" name="id_usuario" required>
                                        <option value="">Seleccione un estudiante...</option>
                                        <?php
                                        $sql_estudiantes = "SELECT idusuario, nombre, username, carrera FROM users WHERE estudiante = 1 ORDER BY nombre";
                                        $result_estudiantes = $db->query($sql_estudiantes);
                                        
                                        while ($est = $result_estudiantes->fetch_assoc()) {
                                            $selected = ($est['idusuario'] == $id_usuario) ? 'selected' : '';
                                            echo "<option value='{$est['idusuario']}' {$selected}>{$est['nombre']} ({$est['username']})</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Consultar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if ($info_estudiante): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Información del Estudiante
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($info_estudiante['nombre']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($info_estudiante['username']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Carrera:</strong> <?php echo htmlspecialchars($info_estudiante['nombre_carrera']); ?></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Trayecto Actual:</strong> <?php echo $trayecto_actual; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Período Académico Activo:</strong> 
                                <?php echo $periodo_activo ? htmlspecialchars($periodo_activo['nombre_periodo']) : 'No hay período activo'; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header <?php echo $puede_avanzar ? 'bg-success text-white' : 'bg-warning'; ?>">
                    <i class="fas fa-chart-line mr-1"></i>
                    Estado Académico - Trayecto <?php echo $trayecto_actual; ?>
                </div>
                <div class="card-body">
                    <div class="alert <?php echo $puede_avanzar ? 'alert-success' : 'alert-warning'; ?>">
                        <h5 class="alert-heading">
                            <?php 
                            if ($puede_avanzar) {
                                echo "¡Puede avanzar al Trayecto " . ($trayecto_actual + 1) . "!";
                            } else {
                                echo "No puede avanzar al siguiente trayecto todavía";
                            }
                            ?>
                        </h5>
                        <p class="mb-0">
                            <strong>Condición para avanzar:</strong><br>
                            <?php 
                            if ($trayecto_actual == 0) {
                                echo "Aprobar al menos el 50% de las materias del trayecto 0";
                            } elseif ($trayecto_actual == 1 || $trayecto_actual == 3) {
                                echo "Haber aprobado el Proyecto Socio Integrador (nota mínima 16)";
                            } elseif ($trayecto_actual == 2) {
                                echo "Aprobar todas las materias y obtener el primer título de la carrera";
                            } else {
                                echo "No aplica";
                            }
                            ?>
                        </p>
                    </div>
                    
                    <h6>Materias Aprobadas en Trayecto <?php echo $trayecto_actual; ?>:</h6>
                    <ul class="list-group">
                        <?php if (!empty($materias_aprobadas)): ?>
                            <?php foreach ($materias_aprobadas as $materia): ?>
                            <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($materia['nombre_materia']); ?>
                                <?php if (esProyectoSocio($materia['id_materia'])): ?>
                                    <span class="badge badge-warning badge-pill">PROYECTO</span>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">No tiene materias aprobadas en este trayecto</li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="mt-3">
                        <strong>Estadísticas:</strong>
                        <div class="progress mt-2">
                            <?php
                            $materias_trayecto_total = obtenerMateriasTrayecto($info_estudiante['carrera'], $trayecto_actual);
                            $total_materias = count($materias_trayecto_total);
                            $total_aprobadas = count($materias_aprobadas);
                            $porcentaje = $total_materias > 0 ? ($total_aprobadas / $total_materias) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $porcentaje; ?>%" 
                                 aria-valuenow="<?php echo $porcentaje; ?>" aria-valuemin="0" aria-valuemax="100">
                                <?php echo number_format($porcentaje, 1); ?>%
                            </div>
                        </div>
                        <small class="text-muted"><?php echo $total_aprobadas; ?> de <?php echo $total_materias; ?> materias aprobadas</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Inscripción de Materias - Trayecto <?php echo $trayecto_inscripcion; ?>
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
                    <?php else: ?>
                        <form method="POST" action="">
                            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                            
                            <div class="form-group">
                                <label for="id_seccion">Seleccionar Sección</label>
                                <select class="form-control" id="id_seccion" name="id_seccion" required>
                                    <option value="">Seleccione una sección...</option>
                                    <?php foreach ($secciones_disponibles as $seccion): ?>
                                    <option value="<?php echo $seccion['id_seccion']; ?>">
                                        <?php echo htmlspecialchars($seccion['codigo_seccion']); ?> - 
                                        Horario: <?php echo htmlspecialchars($seccion['horario']); ?> - 
                                        Aula: <?php echo htmlspecialchars($seccion['aula_asignada']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Materias para Inscripción (Trayecto <?php echo $trayecto_inscripcion; ?>)</label>
                                <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                    <?php if (empty($materias_disponibles)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <?php 
                                            if ($trayecto_inscripcion == $trayecto_actual) {
                                                echo "¡Felicidades! Ya aprobó todas las materias de este trayecto.";
                                            } else {
                                                echo "No hay materias disponibles para este trayecto.";
                                            }
                                            ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="select_all">
                                            <label class="form-check-label" for="select_all">
                                                <strong>Seleccionar todas</strong>
                                            </label>
                                        </div>
                                        <hr>
                                        <?php foreach ($materias_disponibles as $materia): 
                                            $nota_minima = obtenerNotaMinimaMateria($materia['id_materia']);
                                            $es_proyecto = esProyectoSocio($materia['id_materia']);
                                        ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input materia-checkbox" type="checkbox" 
                                                   name="materias[]" 
                                                   value="<?php echo $materia['id_materia']; ?>" 
                                                   id="materia_<?php echo $materia['id_materia']; ?>">
                                            <label class="form-check-label" for="materia_<?php echo $materia['id_materia']; ?>">
                                                <?php echo htmlspecialchars($materia['cod_materia'] . ' - ' . $materia['nombre_materia']); ?>
                                                <?php if ($es_proyecto): ?>
                                                    <span class="badge badge-warning">PROYECTO</span>
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
                            
                            <button type="submit" name="inscribir" class="btn btn-success" 
                                    <?php echo (empty($materias_disponibles) || empty($secciones_disponibles)) ? 'disabled' : ''; ?>>
                                <i class="fas fa-save mr-1"></i> Inscribir Materias Seleccionadas
                            </button>
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
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-clipboard-check text-success"></i> Reglas de Inscripción:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Nota mínima aprobatoria:</strong> 12 puntos</li>
                                <li class="mb-2"><strong>Nota mínima para proyectos:</strong> 16 puntos</li>
                                <li class="mb-2"><strong>Reinscripción:</strong> Solo se inscriben materias reprobadas</li>
                                <li class="mb-2"><strong>Aprobadas:</strong> No se reinscriben automáticamente</li>
                                <li class="mb-2"><strong>Selección:</strong> Puede seleccionar todas las materias con un clic</li>
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
// Script para seleccionar/deseleccionar todas las materias
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select_all');
    const materiaCheckboxes = document.querySelectorAll('.materia-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            materiaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Verificar si todas están seleccionadas
        materiaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(materiaCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && Array.from(materiaCheckboxes).some(cb => cb.checked);
            });
        });
    }
    
    // Habilitar/deshabilitar botón de inscripción según selección
    const inscribirBtn = document.querySelector('button[name="inscribir"]');
    if (inscribirBtn) {
        materiaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const algunaSeleccionada = Array.from(materiaCheckboxes).some(cb => cb.checked);
                inscribirBtn.disabled = !algunaSeleccionada;
            });
        });
    }
});
</script>

<?php include("includes/footer.php"); ?>