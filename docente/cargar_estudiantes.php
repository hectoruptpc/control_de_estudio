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

// Verificar estados de notas
$estados_notas = verificarEstadosNotas($estudiantes, $materia_id, $periodo_id, $docente_id, $trayecto_a_mostrar);

// Actualizar la lógica para mostrar campo de soporte - SIEMPRE OBLIGATORIO
$mostrar_campo_soporte = true;

// Verificar disponibilidad de los trimestres
$disponibilidad_trimestres = [];
for ($t = 1; $t <= 3; $t++) {
    $disponibilidad_trimestres[$t] = verificarDisponibilidadTrimestre($t);
}
$disponibilidad_json = json_encode($disponibilidad_trimestres);
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
            
            <!-- CAMPO DE SOPORTE OBLIGATORIO -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h6><i class="fas fa-paperclip"></i> Soporte del Grupo <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="soporte_grupo"><strong>Imagen/PDF de Soporte (OBLIGATORIO):</strong> <span class="text-danger">*</span></label>
                        <input type="file" 
                               name="soporte_grupo" 
                               id="soporte_grupo"
                               class="form-control-file soporte-grupo" 
                               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf"
                               required>
                        <small class="form-text text-muted">
                            Formatos permitidos: JPG, PNG, GIF, WEBP, PDF. Tamaño máximo: 5MB. <strong class="text-danger">Este campo es obligatorio.</strong>
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
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th class="text-center">
                                Trimestre 1
                                <?php if (!$disponibilidad_trimestres[1]['disponible']): ?>
                                    <i class="fas fa-lock text-danger" title="<?php echo htmlspecialchars($disponibilidad_trimestres[1]['mensaje']); ?>"></i>
                                <?php else: ?>
                                    <i class="fas fa-check-circle text-success" title="<?php echo htmlspecialchars($disponibilidad_trimestres[1]['mensaje']); ?>"></i>
                                <?php endif; ?>
                            </th>
                            <th class="text-center">
                                Trimestre 2
                                <?php if (!$disponibilidad_trimestres[2]['disponible']): ?>
                                    <i class="fas fa-lock text-danger" title="<?php echo htmlspecialchars($disponibilidad_trimestres[2]['mensaje']); ?>"></i>
                                <?php else: ?>
                                    <i class="fas fa-check-circle text-success" title="<?php echo htmlspecialchars($disponibilidad_trimestres[2]['mensaje']); ?>"></i>
                                <?php endif; ?>
                            </th>
                            <th class="text-center">
                                Trimestre 3
                                <?php if (!$disponibilidad_trimestres[3]['disponible']): ?>
                                    <i class="fas fa-lock text-danger" title="<?php echo htmlspecialchars($disponibilidad_trimestres[3]['mensaje']); ?>"></i>
                                <?php else: ?>
                                    <i class="fas fa-check-circle text-success" title="<?php echo htmlspecialchars($disponibilidad_trimestres[3]['mensaje']); ?>"></i>
                                <?php endif; ?>
                            </th>
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
                                    <input type="number" 
                                           name="notas[<?= $estudiante['id'] ?>][trimestre_1]" 
                                           class="form-control nota-input text-center trimestre-input" 
                                           data-trimestre="1"
                                           min="1" 
                                           max="20" 
                                           step="1"
                                           value="<?= $info['trimestre_1_nota'] ?? '' ?>"
                                           style="width: 80px; margin: 0 auto;"
                                           onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)"
                                           <?php echo !$disponibilidad_trimestres[1]['disponible'] ? 'disabled' : ''; ?>>
                                </div>
                                
                                <!-- Trimestre 2 -->
                                <td class="text-center">
                                    <input type="number" 
                                           name="notas[<?= $estudiante['id'] ?>][trimestre_2]" 
                                           class="form-control nota-input text-center trimestre-input" 
                                           data-trimestre="2"
                                           min="1" 
                                           max="20" 
                                           step="1"
                                           value="<?= $info['trimestre_2_nota'] ?? '' ?>"
                                           style="width: 80px; margin: 0 auto;"
                                           onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)"
                                           <?php echo !$disponibilidad_trimestres[2]['disponible'] ? 'disabled' : ''; ?>>
                                 </div>
                                
                                <!-- Trimestre 3 -->
                                <td class="text-center">
                                    <input type="number" 
                                           name="notas[<?= $estudiante['id'] ?>][trimestre_3]" 
                                           class="form-control nota-input text-center trimestre-input" 
                                           data-trimestre="3"
                                           min="1" 
                                           max="20" 
                                           step="1"
                                           value="<?= $info['trimestre_3_nota'] ?? '' ?>"
                                           style="width: 80px; margin: 0 auto;"
                                           onchange="calcularPromedio(this, <?= $estudiante['id'] ?>)"
                                           <?php echo !$disponibilidad_trimestres[3]['disponible'] ? 'disabled' : ''; ?>>
                                 </div>
                                
                                <!-- Promedio Final -->
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
                                 </div>
                                
                                <td class="text-center">
                                    <?php
                                    $estado_t1 = $info['trimestre_1_estado'] ?? 'pendiente';
                                    $estado_t2 = $info['trimestre_2_estado'] ?? 'pendiente';
                                    $estado_t3 = $info['trimestre_3_estado'] ?? 'pendiente';
                                    $estados_temp = [$estado_t1, $estado_t2, $estado_t3];
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
                                 </div>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-warning mt-3">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Importante:</strong> El archivo de soporte es <strong class="text-danger">OBLIGATORIO</strong>. 
                Formatos permitidos: imágenes (JPG, PNG, GIF, WEBP) o PDF. Tamaño máximo: 5MB.
            </div>
            
            <button type="submit" class="btn btn-success btn-lg" id="btnGuardarNotas">
                <i class="fas fa-save"></i> 
                Guardar Notas
            </button>
        </form>
    </div>
</div>

<script>
// Disponibilidad de trimestres desde PHP
const disponibilidadTrimestres = <?php echo $disponibilidad_json; ?>;

function calcularPromedio(input, estudianteId) {
    const t1 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_1]"]`)?.value) || 0;
    const t2 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_2]"]`)?.value) || 0;
    const t3 = parseFloat(document.querySelector(`input[name="notas[${estudianteId}][trimestre_3]"]`)?.value) || 0;
    
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
    
    const promedioField = document.getElementById(`promedio_${estudianteId}`);
    const promedioHidden = document.getElementById(`promedio_hidden_${estudianteId}`);
    
    if (promedioField) promedioField.value = promedio;
    if (promedioHidden) promedioHidden.value = promedio;
}

// Validar trimestres antes de guardar
document.getElementById('btnGuardarNotas')?.addEventListener('click', function(e) {
    // Verificar si algún trimestre no disponible tiene contenido
    for (let trimestre = 1; trimestre <= 3; trimestre++) {
        if (!disponibilidadTrimestres[trimestre].disponible) {
            const inputs = document.querySelectorAll(`input[name*="trimestre_${trimestre}"]`);
            let tieneNota = false;
            inputs.forEach(input => {
                if (input.value && input.value !== '') {
                    tieneNota = true;
                }
            });
            if (tieneNota) {
                e.preventDefault();
                alert('❌ ' + disponibilidadTrimestres[trimestre].mensaje);
                return false;
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($estudiantes as $estudiante): ?>
        calcularPromedio(null, <?= $estudiante['id'] ?>);
    <?php endforeach; ?>
    
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('input', function() {
            let val = parseInt(this.value);
            if (isNaN(val)) val = 1;
            if (val < 1) this.value = 1;
            if (val > 20) this.value = 20;
        });
        
        // Mostrar alerta si el trimestre no está disponible y el usuario intenta enfocar
        input.addEventListener('focus', function() {
            const trimestre = this.getAttribute('data-trimestre');
            if (trimestre && !disponibilidadTrimestres[trimestre].disponible) {
                alert('❌ ' + disponibilidadTrimestres[trimestre].mensaje);
                this.blur();
            }
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
.nota-input:disabled {
    background-color: #e9ecef;
    cursor: not-allowed;
}
</style>