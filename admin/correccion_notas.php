<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Corrección de Notas";
include('../funciones/functions.php');

// Verificar permisos y sesión
cargarPermisosUsuario();
verificarPermiso('editar_nota');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Procesar formularios
$mensaje = '';
$tipo_mensaje = '';
$estudiante = null;
$carreras = [];
$materias = [];
$notas = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'buscar_estudiante':
                $cedula = trim($_POST['cedula'] ?? '');
                if (!empty($cedula)) {
                    $estudiante = buscarEstudiantePorCedula($cedula);
                    if ($estudiante) {
                        $carreras = obtenerCarrerasEstudiante($estudiante['id']);
                    } else {
                        $mensaje = 'No se encontró ningún estudiante con esa cédula';
                        $tipo_mensaje = 'warning';
                    }
                }
                break;
                
            case 'seleccionar_carrera':
                $estudiante_id = $_POST['id_usuario'] ?? '';
                $id_carrera = $_POST['id_carrera'] ?? '';
                if (!empty($estudiante_id) && !empty($id_carrera)) {
                    $estudiante = obtenerEstudiantePorId($estudiante_id);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    $materias = obtenerMateriasInscritasPorEstudiante($estudiante_id, $id_carrera);
                }
                break;
                
            case 'seleccionar_materia':
                $estudiante_id = $_POST['id_usuario'] ?? '';
                $id_carrera = $_POST['id_carrera'] ?? '';
                $id_materia = $_POST['id_materia'] ?? '';
                if (!empty($estudiante_id) && !empty($id_materia)) {
                    $estudiante = obtenerEstudiantePorId($estudiante_id);
                    $carreras = obtenerCarrerasEstudiante($estudiante_id);
                    $materias = obtenerMateriasInscritasPorEstudiante($estudiante_id, $id_carrera);
                    $notas = obtenerNotasTrimestresPorMateria($estudiante_id, $id_materia);
                }
                break;
                
            case 'editar_nota':
                $resultado = procesarEdicionNotaTrimestral();
                if ($resultado['success']) {
                    $mensaje = $resultado['message'];
                    $tipo_mensaje = 'success';
                    $estudiante_id = $_POST['id_usuario'] ?? '';
                    $id_carrera = $_POST['id_carrera'] ?? '';
                    $id_materia = $_POST['id_materia'] ?? '';
                    if (!empty($estudiante_id)) {
                        $estudiante = obtenerEstudiantePorId($estudiante_id);
                        $carreras = obtenerCarrerasEstudiante($estudiante_id);
                        $materias = obtenerMateriasInscritasPorEstudiante($estudiante_id, $id_carrera);
                        $notas = obtenerNotasTrimestresPorMateria($estudiante_id, $id_materia);
                    }
                } else {
                    $mensaje = $resultado['message'];
                    $tipo_mensaje = 'error';
                }
                break;
        }
    }
}

include("includes/head.php");
?>

<style>
.justificacion-texto {
    max-height: 80px;
    overflow-y: auto;
    font-size: 0.85rem;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4em 0.6em;
}

.historial-table th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    z-index: 10;
}

.btn-reporte {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.btn-reporte:hover {
    background-color: #138496;
    border-color: #117a8b;
    color: white;
}

.btn-group-actions {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Corrección de Notas</h1>
            
            <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje === 'success' ? 'success' : ($tipo_mensaje === 'warning' ? 'warning' : 'danger'); ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($mensaje); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <!-- Paso 1: Buscar estudiante por cédula -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paso 1: Buscar Estudiante</h6>
                </div>
                <div class="card-body">
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="accion" value="buscar_estudiante">
                        <div class="form-group mr-3 mb-2">
                            <label for="cedula" class="mr-2">Cédula del Estudiante:</label>
                            <input type="text" name="cedula" id="cedula" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>" 
                                   placeholder="Ingrese la cédula" required>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-search"></i> Buscar Estudiante
                        </button>
                    </form>
                    
                    <?php if ($estudiante): ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6>Estudiante Encontrado:</h6>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($estudiante['nombre']); ?></p>
                        <p><strong>Cédula:</strong> <?php echo htmlspecialchars($estudiante['idusuario']); ?></p>
                        <p><strong>ID Estudiante:</strong> <?php echo $estudiante['id']; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Paso 2: Seleccionar Carrera -->
            <?php if ($estudiante && !empty($carreras)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paso 2: Seleccionar Carrera</h6>
                    <small>Se encontraron <?php echo count($carreras); ?> carrera(s)</small>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="accion" value="seleccionar_carrera">
                        <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
                        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
                        
                        <div class="form-group">
                            <label for="id_carrera">Seleccione la Carrera:</label>
                            <select name="id_carrera" id="id_carrera" class="form-control" required onchange="this.form.submit()">
                                <option value="">Seleccionar Carrera</option>
                                <?php foreach ($carreras as $carrera): ?>
                                <option value="<?php echo $carrera['id_carrera']; ?>" 
                                    <?php echo (isset($_POST['id_carrera']) && $_POST['id_carrera'] == $carrera['id_carrera']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <?php elseif ($estudiante && empty($carreras)): ?>
            <div class="alert alert-warning">
                No se encontraron carreras para este estudiante.
            </div>
            <?php endif; ?>

            <!-- Paso 3: Seleccionar Materia -->
            <?php if ($estudiante && !empty($materias) && $materias->num_rows > 0): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paso 3: Seleccionar Materia</h6>
                    <small>Se encontraron <?php echo $materias->num_rows; ?> materia(s) inscritas</small>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="accion" value="seleccionar_materia">
                        <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
                        <input type="hidden" name="id_carrera" value="<?php echo htmlspecialchars($_POST['id_carrera'] ?? ''); ?>">
                        <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
                        
                        <div class="form-group">
                            <label for="id_materia">Seleccione la Materia:</label>
                            <select name="id_materia" id="id_materia" class="form-control" required onchange="this.form.submit()">
                                <option value="">Seleccionar Materia</option>
                                <?php 
                                $materias->data_seek(0);
                                while ($materia = $materias->fetch_assoc()): 
                                ?>
                                <option value="<?php echo $materia['id_materia']; ?>" 
                                    <?php echo (isset($_POST['id_materia']) && $_POST['id_materia'] == $materia['id_materia']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($materia['nombre_materia']); ?> 
                                    - Trayecto <?php echo $materia['trayecto']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <?php elseif ($estudiante && isset($_POST['id_carrera']) && ($materias->num_rows == 0)): ?>
            <div class="alert alert-warning">
                No se encontraron materias inscritas para este estudiante en la carrera seleccionada.
            </div>
            <?php endif; ?>

            <!-- Paso 4: Mostrar y Editar Notas (Trimestres) -->
            <?php if ($estudiante && isset($_POST['id_materia']) && !empty($notas)): ?>
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Paso 4: Notas del Estudiante - Trimestres
                    </h6>
                    <small>Se encontraron <?php echo count($notas); ?> registro(s) de notas</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Periodo Académico</th>
                                    <th class="text-center">Trimestre 1</th>
                                    <th class="text-center">Trimestre 2</th>
                                    <th class="text-center">Trimestre 3</th>
                                    <th class="text-center">Nota Final</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Fecha Registro</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notas as $nota): 
                                    // Calcular nota final
                                    $t1 = $nota['trimestre_1'];
                                    $t2 = $nota['trimestre_2'];
                                    $t3 = $nota['trimestre_3'];
                                    
                                    $suma = 0;
                                    $count = 0;
                                    if ($t1 !== null) { $suma += $t1; $count++; }
                                    if ($t2 !== null) { $suma += $t2; $count++; }
                                    if ($t3 !== null) { $suma += $t3; $count++; }
                                    $nota_final = $count > 0 ? round($suma / $count, 1) : null;
                                    
                                    $estado = $nota['estado'];
                                    $badge_class = 'secondary';
                                    $badge_text = 'Pendiente';
                                    
                                    if ($estado === 'aprobada') {
                                        $badge_class = 'success';
                                        $badge_text = 'Aprobada';
                                    } elseif ($estado === 'rechazada') {
                                        $badge_class = 'danger';
                                        $badge_text = 'Rechazada';
                                    } elseif ($estado === 'en_revision') {
                                        $badge_class = 'warning';
                                        $badge_text = 'En Revisión';
                                    }
                                ?>
                                <tr>
                                    <td class="font-weight-bold"><?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge <?php echo ($t1 === null) ? 'bg-secondary' : ($t1 >= 12 ? 'bg-success' : 'bg-danger'); ?> p-2" style="font-size: 0.9rem;">
                                            <?php echo ($t1 !== null) ? number_format($t1, 1) : 'N/A'; ?>
                                        </span>
                                    </div>
                                    
                                    <td class="text-center">
                                        <span class="badge <?php echo ($t2 === null) ? 'bg-secondary' : ($t2 >= 12 ? 'bg-success' : 'bg-danger'); ?> p-2" style="font-size: 0.9rem;">
                                            <?php echo ($t2 !== null) ? number_format($t2, 1) : 'N/A'; ?>
                                        </span>
                                    </div>
                                    
                                    <td class="text-center">
                                        <span class="badge <?php echo ($t3 === null) ? 'bg-secondary' : ($t3 >= 12 ? 'bg-success' : 'bg-danger'); ?> p-2" style="font-size: 0.9rem;">
                                            <?php echo ($t3 !== null) ? number_format($t3, 1) : 'N/A'; ?>
                                        </span>
                                    </div>
                                    
                                    <td class="text-center">
                                        <span class="badge <?php echo ($nota_final === null) ? 'bg-secondary' : ($nota_final >= 12 ? 'bg-success' : 'bg-danger'); ?> p-2" style="font-size: 1rem; font-weight: bold;">
                                            <?php echo ($nota_final !== null) ? number_format($nota_final, 1) : 'N/A'; ?>
                                        </span>
                                    </div>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $badge_class; ?> px-3 py-2"><?php echo $badge_text; ?></span>
                                    </div>
                                    
                                    <td class="text-center"><?php echo !empty($nota['fecha_registro']) ? date('d/m/Y H:i', strtotime($nota['fecha_registro'])) : '-'; ?></div>
                                    
                                    <td class="text-center">
                                        <div class="btn-group-actions">
                                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEditarNota<?php echo $nota['id']; ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <a href="generar_reporte_solicitud.php?estudiante_id=<?php echo $estudiante['id']; ?>&estudiante_nombre=<?php echo urlencode($estudiante['nombre']); ?>&estudiante_cedula=<?php echo urlencode($estudiante['idusuario']); ?>&nota_id=<?php echo $nota['id']; ?>&periodo_academico=<?php echo urlencode($nota['nombre_periodo'] ?? 'Sin periodo'); ?>" 
                                               class="btn btn-reporte btn-sm" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Reporte
                                            </a>
                                        </div>
                                    </div>
                                </tr>

                                <!-- Modal para Editar Nota Trimestral -->
                                <div class="modal fade" id="modalEditarNota<?php echo $nota['id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title">Editar Nota - <?php echo htmlspecialchars($nota['nombre_periodo'] ?? 'Sin periodo'); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="accion" value="editar_nota">
                                                    <input type="hidden" name="id_nota" value="<?php echo $nota['id']; ?>">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $estudiante['id']; ?>">
                                                    <input type="hidden" name="id_carrera" value="<?php echo htmlspecialchars($_POST['id_carrera'] ?? ''); ?>">
                                                    <input type="hidden" name="id_materia" value="<?php echo htmlspecialchars($_POST['id_materia'] ?? ''); ?>">
                                                    <input type="hidden" name="cedula" value="<?php echo htmlspecialchars($_POST['cedula'] ?? ''); ?>">
                                                    
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i> 
                                                        <strong>Información:</strong> Puede editar cualquier trimestre. La nota final se recalculará automáticamente.
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="trimestre_1_<?php echo $nota['id']; ?>">Trimestre 1:</label>
                                                                <input type="number" name="trimestre_1" id="trimestre_1_<?php echo $nota['id']; ?>" 
                                                                       class="form-control" step="1" min="1" max="20" 
                                                                       value="<?php echo $t1; ?>">
                                                                <small class="form-text text-muted">Valor actual: <?php echo $t1 !== null ? number_format($t1, 1) : 'No registrado'; ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="trimestre_2_<?php echo $nota['id']; ?>">Trimestre 2:</label>
                                                                <input type="number" name="trimestre_2" id="trimestre_2_<?php echo $nota['id']; ?>" 
                                                                       class="form-control" step="1" min="1" max="20" 
                                                                       value="<?php echo $t2; ?>">
                                                                <small class="form-text text-muted">Valor actual: <?php echo $t2 !== null ? number_format($t2, 1) : 'No registrado'; ?></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="trimestre_3_<?php echo $nota['id']; ?>">Trimestre 3:</label>
                                                                <input type="number" name="trimestre_3" id="trimestre_3_<?php echo $nota['id']; ?>" 
                                                                       class="form-control" step="1" min="1" max="20" 
                                                                       value="<?php echo $t3; ?>">
                                                                <small class="form-text text-muted">Valor actual: <?php echo $t3 !== null ? number_format($t3, 1) : 'No registrado'; ?></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group mt-3">
                                                        <label for="estado_<?php echo $nota['id']; ?>">Estado de la Nota:</label>
                                                        <select name="estado" id="estado_<?php echo $nota['id']; ?>" class="form-control">
                                                            <option value="pendiente" <?php echo $estado === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                            <option value="aprobada" <?php echo $estado === 'aprobada' ? 'selected' : ''; ?>>Aprobada</option>
                                                            <option value="rechazada" <?php echo $estado === 'rechazada' ? 'selected' : ''; ?>>Rechazada</option>
                                                            <option value="en_revision" <?php echo $estado === 'en_revision' ? 'selected' : ''; ?>>En Revisión</option>
                                                        </select>
                                                        <small class="form-text text-muted">Si cambia el estado, se actualizará automáticamente.</small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label for="justificacion_<?php echo $nota['id']; ?>">Justificación del Cambio:</label>
                                                        <textarea name="justificacion" id="justificacion_<?php echo $nota['id']; ?>" 
                                                                  class="form-control" rows="3" required 
                                                                  placeholder="Explique detalladamente por qué se realiza este cambio..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php elseif ($estudiante && isset($_POST['id_materia']) && empty($notas)): ?>
            <div class="alert alert-info">
                No se encontraron notas registradas para esta materia.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>