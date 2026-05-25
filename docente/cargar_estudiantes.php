<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

// PROCESAR FORMULARIO SI SE ENVÍAN NOTAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notas'])) {
    procesarNotasEstudiantes();
}

// MOSTRAR FORMULARIO (código original)
if (!isset($_POST['seccion_id']) || !isset($_POST['materia_id'])) {
    die('Parámetros incompletos');
}

$seccion_id = (int)$_POST['seccion_id'];
$materia_id = (int)$_POST['materia_id'];

// Obtener datos usando las funciones reutilizables
$materia = obtenerInfoMateria($materia_id);
$estudiantes = obtenerEstudiantesPorSeccion($seccion_id);
$periodo_id = obtenerPeriodoSeccion($seccion_id);
$trayecto_seccion = obtenerTrayectoSeccion($seccion_id);

if (!$materia) {
    die('Error: Materia no encontrada');
}

if (!$estudiantes) {
    die('No hay estudiantes en esta sección');
}

// Obtener el trayecto específico de la sección
$trayecto_actual = $trayecto_seccion['numero_trayecto'];
$id_trayecto_seccion = $trayecto_seccion['id_trayecto'];

// Determinar qué trayecto mostrar
$trayecto_a_mostrar = determinarTrayectoAMostrar($id_trayecto_seccion);

// Obtener ID del docente
$docente_id = obtenerIdUsuario();

// Obtener notas existentes de los estudiantes (para los 3 trimestres)
global $db;
$notas_existentes = [];
$query_notas = "SELECT id_usuario, trimestre_num, nota, estado 
                FROM notas_trimestres 
                WHERE id_materia = $materia_id 
                AND id_periodo = $periodo_id";
$result_notas = $db->query($query_notas);
if ($result_notas && $result_notas->num_rows > 0) {
    while ($row = $result_notas->fetch_assoc()) {
        $notas_existentes[$row['id_usuario']][$row['trimestre_num']] = [
            'nota' => $row['nota'],
            'estado' => $row['estado'] ?? 'pendiente'
        ];
    }
}

// Verificar estados de notas (adaptado para trimestres)
$estados_notas = [
    'notas_aprobadas' => false,
    'notas_rechazadas' => false,
    'notas_en_revision' => false,
    'notas_pendientes' => false,
    'estudiantes_con_notas_aprobadas' => [],
    'estudiantes_con_notas_rechazadas' => [],
    'estudiantes_con_notas_en_revision' => [],
    'estudiantes_con_notas_pendientes' => [],
    'estudiantes_info' => []
];

foreach ($estudiantes as $estudiante) {
    $estudiante_id = $estudiante['id'];
    $info_estudiante = ['datos' => $estudiante];
    $tiene_notas_aprobadas = false;
    $tiene_notas_rechazadas = false;
    $tiene_notas_revision = false;
    $tiene_notas_pendientes = false;
    
    for ($trimestre = 1; $trimestre <= 3; $trimestre++) {
        $nota_info = $notas_existentes[$estudiante_id][$trimestre] ?? null;
        if ($nota_info) {
            $estado = $nota_info['estado'] ?? 'pendiente';
            if ($estado === 'aprobada') {
                $tiene_notas_aprobadas = true;
                $estados_notas['notas_aprobadas'] = true;
            } elseif ($estado === 'rechazada') {
                $tiene_notas_rechazadas = true;
                $estados_notas['notas_rechazadas'] = true;
            } elseif ($estado === 'en_revision') {
                $tiene_notas_revision = true;
                $estados_notas['notas_en_revision'] = true;
            } else {
                $tiene_notas_pendientes = true;
                $estados_notas['notas_pendientes'] = true;
            }
        } else {
            $tiene_notas_pendientes = true;
            $estados_notas['notas_pendientes'] = true;
        }
        
        $info_estudiante["trimestre_{$trimestre}_estado"] = $nota_info['estado'] ?? 'pendiente';
        $info_estudiante["trimestre_{$trimestre}_nota"] = $nota_info['nota'] ?? '';
    }
    
    if ($tiene_notas_aprobadas) {
        $estados_notas['estudiantes_con_notas_aprobadas'][] = $estudiante['nombre'];
    }
    if ($tiene_notas_rechazadas) {
        $estados_notas['estudiantes_con_notas_rechazadas'][] = $estudiante['nombre'];
    }
    if ($tiene_notas_revision) {
        $estados_notas['estudiantes_con_notas_en_revision'][] = $estudiante['nombre'];
    }
    if ($tiene_notas_pendientes) {
        $estados_notas['estudiantes_con_notas_pendientes'][] = $estudiante['nombre'];
    }
    
    $estados_notas['estudiantes_info'][] = $info_estudiante;
}

// Mostrar campo de soporte si hay notas pendientes o rechazadas
$mostrar_campo_soporte = $estados_notas['notas_pendientes'] || $estados_notas['notas_rechazadas'];
?>

<!-- Mostrar mensajes de éxito/error -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error'] ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5>Estudiantes - <?= htmlspecialchars($materia['nombre_materia']) ?></h5>
        <p class="mb-0">Trayecto <?= $trayecto_actual ?> | Periodo ID: <?= $periodo_id ?></p>
    </div>
    <div class="card-body">
        
        <!-- MOSTRAR SIEMPRE LOS MENSAJES DE ESTADO -->
        <?php if ($estados_notas['notas_aprobadas']): ?>
        <div class="alert alert-success">
            <strong>✅ Notas Aprobadas:</strong> Algunas notas ya fueron aprobadas y no pueden ser modificadas. 
            <?php if (!empty($estados_notas['estudiantes_con_notas_aprobadas'])): ?>
                <br>
                <strong>Estudiantes con notas aprobadas:</strong>
                <?= implode(', ', $estados_notas['estudiantes_con_notas_aprobadas']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($estados_notas['notas_rechazadas']): ?>
        <div class="alert alert-danger">
            <strong>❌ Notas Rechazadas:</strong> Algunas notas fueron rechazadas y necesitan corrección. 
            <?php if (!empty($estados_notas['estudiantes_con_notas_rechazadas'])): ?>
                <br>
                <strong>Estudiantes con notas rechazadas:</strong>
                <?= implode(', ', $estados_notas['estudiantes_con_notas_rechazadas']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($estados_notas['notas_en_revision']): ?>
        <div class="alert alert-warning">
            <strong>⏳ Notas en Revisión:</strong> Algunas notas están siendo revisadas por los administradores. 
            No pueden ser modificadas hasta que se complete la revisión.
            <?php if (!empty($estados_notas['estudiantes_con_notas_en_revision'])): ?>
                <br>
                <strong>Estudiantes con notas en revisión:</strong>
                <?= implode(', ', $estados_notas['estudiantes_con_notas_en_revision']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($estados_notas['notas_pendientes']): ?>
        <div class="alert alert-secondary">
            <strong>📝 Notas Pendientes:</strong> Algunas notas no han sido subidas aún. 
            Por favor, ingrese las notas faltantes.
            <?php if (!empty($estados_notas['estudiantes_con_notas_pendientes'])): ?>
                <br>
                <strong>Estudiantes con notas pendientes:</strong>
                <?= implode(', ', $estados_notas['estudiantes_con_notas_pendientes']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- MOSTRAR SIEMPRE EL PANEL DE INFORMACIÓN DE ESTADOS -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Estados:</strong><br>
            • <span class="badge badge-secondary">Pendiente</span> - No se ha subido la nota<br>
            • <span class="badge badge-warning">En Revisión</span> - En revisión por administradores<br>
            • <span class="badge badge-success">Aprobada</span> - No se puede modificar<br>
            • <span class="badge badge-danger">Rechazada</span> - Puede corregir y reenviar
        </div>
        
        <form id="form-notas" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="materia_id" value="<?= $materia_id ?>">
            <input type="hidden" name="seccion_id" value="<?= $seccion_id ?>">
            <input type="hidden" name="periodo_id" value="<?= $periodo_id ?>">
            <input type="hidden" name="trayecto_actual" value="<?= $trayecto_actual ?>">
            <input type="hidden" name="id_trayecto_seccion" value="<?= $id_trayecto_seccion ?>">
            <input type="hidden" name="docente_id" value="<?= $docente_id ?>">
            
            <!-- CAMPO DE SOPORTE PARA TODO EL GRUPO -->
            <?php if ($mostrar_campo_soporte): ?>
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6><i class="fas fa-paperclip"></i> Soporte del Grupo</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="soporte_grupo"><strong>Imagen/PDF de Soporte:</strong></label>
                        <input type="file" 
                               name="soporte_grupo" 
                               id="soporte_grupo"
                               class="form-control-file soporte-grupo" 
                               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
                        <small class="form-text text-muted">
                            Formatos permitidos: JPG, PNG, GIF, WEBP, PDF. Tamaño máximo: 5MB
                        </small>
                    </div>
                    <div class="form-group">
                        <label><strong>Vista Previa:</strong></label>
                        <div id="preview-grupo" class="mt-2">
                            <small class="text-muted">No se ha seleccionado ningún archivo</small>
                        </div>
                        <small id="nombre-archivo-grupo" class="form-text text-muted">
                            Ningún archivo seleccionado
                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th class="text-center">Trimestre 1</th>
                            <th class="text-center">Trimestre 2</th>
                            <th class="text-center">Trimestre 3</th>
                            <th class="text-center">Promedio Final</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estados_notas['estudiantes_info'] as $info): 
                            $estudiante = $info['datos'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['idusuario']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                
                                <!-- Trimestre 1 -->
                                <td class="text-center">
                                    <?php 
                                    $estado_t1 = $info['trimestre_1_estado'] ?? 'pendiente';
                                    $puede_editar_t1 = ($estado_t1 === 'pendiente' || $estado_t1 === 'rechazada');
                                    $valor_t1 = $info['trimestre_1_nota'] ?? '';
                                    ?>
                                    <?php if ($puede_editar_t1): ?>
                                        <input type="number" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_1]" 
                                               class="form-control nota-input text-center" 
                                               min="1" 
                                               max="20" 
                                               step="1"
                                               value="<?= $valor_t1 ?>"
                                               style="width: 80px; margin: 0 auto;"
                                               onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)">
                                    <?php else: ?>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               value="<?= $valor_t1 ?: '-' ?>"
                                               readonly
                                               style="width: 80px; margin: 0 auto; background-color: #f8f9fa;">
                                        <input type="hidden" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_1]" 
                                               value="<?= $valor_t1 ?>">
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Trimestre 2 -->
                                <td class="text-center">
                                    <?php 
                                    $estado_t2 = $info['trimestre_2_estado'] ?? 'pendiente';
                                    $puede_editar_t2 = ($estado_t2 === 'pendiente' || $estado_t2 === 'rechazada');
                                    $valor_t2 = $info['trimestre_2_nota'] ?? '';
                                    ?>
                                    <?php if ($puede_editar_t2): ?>
                                        <input type="number" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_2]" 
                                               class="form-control nota-input text-center" 
                                               min="1" 
                                               max="20" 
                                               step="1"
                                               value="<?= $valor_t2 ?>"
                                               style="width: 80px; margin: 0 auto;"
                                               onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)">
                                    <?php else: ?>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               value="<?= $valor_t2 ?: '-' ?>"
                                               readonly
                                               style="width: 80px; margin: 0 auto; background-color: #f8f9fa;">
                                        <input type="hidden" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_2]" 
                                               value="<?= $valor_t2 ?>">
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Trimestre 3 -->
                                <td class="text-center">
                                    <?php 
                                    $estado_t3 = $info['trimestre_3_estado'] ?? 'pendiente';
                                    $puede_editar_t3 = ($estado_t3 === 'pendiente' || $estado_t3 === 'rechazada');
                                    $valor_t3 = $info['trimestre_3_nota'] ?? '';
                                    ?>
                                    <?php if ($puede_editar_t3): ?>
                                        <input type="number" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_3]" 
                                               class="form-control nota-input text-center" 
                                               min="1" 
                                               max="20" 
                                               step="1"
                                               value="<?= $valor_t3 ?>"
                                               style="width: 80px; margin: 0 auto;"
                                               onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)">
                                    <?php else: ?>
                                        <input type="text" 
                                               class="form-control text-center" 
                                               value="<?= $valor_t3 ?: '-' ?>"
                                               readonly
                                               style="width: 80px; margin: 0 auto; background-color: #f8f9fa;">
                                        <input type="hidden" 
                                               name="notas[<?= $estudiante['id'] ?>][trimestre_3]" 
                                               value="<?= $valor_t3 ?>">
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Promedio Final (solo lectura, se calcula automáticamente) -->
                                <td class="text-center">
                                    <input type="text" 
                                           id="promedio_<?= $estudiante['id'] ?>"
                                           class="form-control text-center bg-light"
                                           readonly
                                           style="width: 80px; margin: 0 auto; font-weight: bold;">
                                    <input type="hidden" 
                                           name="notas[<?= $estudiante['id'] ?>][nota_final]"
                                           id="promedio_hidden_<?= $estudiante['id'] ?>"
                                           value="">
                                </td>
                                
                                <td>
                                    <?php
                                    // Determinar el estado general (el más crítico)
                                    $estados_temp = [$estado_t1 ?? 'pendiente', $estado_t2 ?? 'pendiente', $estado_t3 ?? 'pendiente'];
                                    if (in_array('aprobada', $estados_temp)) {
                                        $badge_class = 'success';
                                        $badge_text = 'Aprobada';
                                    } elseif (in_array('en_revision', $estados_temp)) {
                                        $badge_class = 'warning';
                                        $badge_text = 'En Revisión';
                                    } elseif (in_array('rechazada', $estados_temp)) {
                                        $badge_class = 'danger';
                                        $badge_text = 'Rechazada';
                                    } else {
                                        $badge_class = 'secondary';
                                        $badge_text = 'Pendiente';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $badge_class ?>"><?= $badge_text ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($mostrar_campo_soporte): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Importante:</strong> El archivo de soporte será aplicado a todas las notas del grupo. 
                Formatos permitidos: imágenes (JPG, PNG, GIF, WEBP) o PDF. Tamaño máximo: 5MB.
            </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> 
                Guardar Notas
            </button>
        </form>
    </div>
</div>

<script>
function calcularPromedio(input, estudianteId) {
    // Obtener valores de los 3 trimestres
    const t1 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_1]"]`)?.value) || 0;
    const t2 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_2]"]`)?.value) || 0;
    const t3 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_3]"]`)?.value) || 0;
    
    // Calcular promedio simple
    let promedio = 0;
    let notasValidas = 0;
    
    if (t1 > 0) { promedio += t1; notasValidas++; }
    if (t2 > 0) { promedio += t2; notasValidas++; }
    if (t3 > 0) { promedio += t3; notasValidas++; }
    
    if (notasValidas > 0) {
        promedio = (promedio / notasValidas).toFixed(1);
    } else {
        promedio = '';
    }
    
    // Mostrar en el campo de texto
    const promedioField = document.getElementById(`promedio_${estudianteId}`);
    const promedioHidden = document.getElementById(`promedio_hidden_${estudianteId}`);
    
    if (promedioField) {
        promedioField.value = promedio;
    }
    if (promedioHidden) {
        promedioHidden.value = promedio;
    }
}

// Calcular promedios al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($estudiantes as $estudiante): ?>
        calcularPromedio(null, <?= $estudiante['id'] ?>);
    <?php endforeach; ?>
    
    // Validar notas
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('input', function() {
            let val = parseInt(this.value);
            if (isNaN(val)) val = 1;
            if (val < 1) this.value = 1;
            if (val > 20) this.value = 20;
        });
    });
});

// Preview para el soporte del grupo
document.addEventListener('DOMContentLoaded', function() {
    const soporteGrupo = document.getElementById('soporte_grupo');
    const previewGrupo = document.getElementById('preview-grupo');
    const nombreArchivoGrupo = document.getElementById('nombre-archivo-grupo');
    
    if (soporteGrupo) {
        soporteGrupo.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        previewGrupo.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height:150px;">`;
                    } else {
                        previewGrupo.innerHTML = `
                            <div class="alert alert-info text-center">
                                <i class="fas fa-file-pdf fa-3x"></i><br>
                                <strong>Archivo PDF</strong>
                            </div>
                        `;
                    }
                    nombreArchivoGrupo.textContent = file.name;
                }
                reader.readAsDataURL(file);
            } else {
                previewGrupo.innerHTML = '<small class="text-muted">No se ha seleccionado ningún archivo</small>';
                nombreArchivoGrupo.textContent = 'Ningún archivo seleccionado';
            }
        });
    }
});
</script>

<style>
.nota-input {
    font-weight: bold;
    text-align: center;
}
</style>