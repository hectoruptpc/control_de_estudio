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
    // Obtener datos del formulario
    $docente_id = (int)$_POST['docente_id'];
    $materia_id = (int)$_POST['materia_id'];
    $periodo_id = (int)$_POST['periodo_id'];
    $trayecto_actual = (int)$_POST['trayecto_actual'];
    $campo_trayecto = 'trayecto_' . $trayecto_actual;
    $notas = $_POST['notas'];
    
    // Procesar soporte si se subió
    $soporte_nombre = null;
    $tipo_archivo = null;
    
    if (isset($_FILES['soporte_grupo']) && $_FILES['soporte_grupo']['error'] === UPLOAD_ERR_OK) {
        $soporte = $_FILES['soporte_grupo'];
        $extension = strtolower(pathinfo($soporte['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            $soporte_nombre = uniqid() . '_' . time() . '.' . $extension;
            $tipo_archivo = $extension;
            $ruta_destino = '../soportes/' . $soporte_nombre;
            
            if (!move_uploaded_file($soporte['tmp_name'], $ruta_destino)) {
                echo "<script>alert('Error al subir el archivo de soporte');</script>";
            }
        }
    }
    
    // Procesar cada nota
    $errores = [];
    $exitos = 0;
    
    foreach ($notas as $id_estudiante => $nota_data) {
        $id_estudiante = (int)$id_estudiante;
        $valor_nota = (int)$nota_data[$campo_trayecto];
        
        // Validar que la nota esté entre 1 y 20
        if ($valor_nota < 1 || $valor_nota > 20) {
            $errores[] = "Nota inválida para el estudiante ID $id_estudiante: $valor_nota";
            continue;
        }
        
        // Verificar si ya existe en notas_pendientes
        $check_query = "SELECT id FROM notas_pendientes 
                       WHERE id_usuario = ? 
                       AND id_materia = ? 
                       AND id_periodo = ? 
                       AND id_docente = ?";
        $stmt = $db->prepare($check_query);
        $stmt->bind_param("iiii", $id_estudiante, $materia_id, $periodo_id, $docente_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Actualizar registro existente
            $update_query = "UPDATE notas_pendientes 
                            SET $campo_trayecto = ?, 
                                soporte = ?, 
                                tipo_archivo = ?, 
                                fecha_subida = NOW(),
                                estado = 'en_revision' 
                            WHERE id_usuario = ? 
                            AND id_materia = ? 
                            AND id_periodo = ? 
                            AND id_docente = ?";
            
            $stmt = $db->prepare($update_query);
            $stmt->bind_param("issiiii", $valor_nota, $soporte_nombre, $tipo_archivo, 
                             $id_estudiante, $materia_id, $periodo_id, $docente_id);
        } else {
            // Insertar nuevo registro - el estado por defecto es 'en revision'
            $insert_query = "INSERT INTO notas_pendientes 
                            (id_usuario, id_materia, id_periodo, id_docente, 
                             $campo_trayecto, soporte, tipo_archivo, estado, fecha_envio, fecha_subida) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'en_revision', NOW(), NOW())";
            
            $stmt = $db->prepare($insert_query);
            $stmt->bind_param("iiiiiss", $id_estudiante, $materia_id, $periodo_id, $docente_id,
                             $valor_nota, $soporte_nombre, $tipo_archivo);
        }
        
        if ($stmt->execute()) {
            $exitos++;
        } else {
            $errores[] = "Error al guardar nota para estudiante ID $id_estudiante: " . $stmt->error;
        }
    }
    
    // Mostrar resultados
    if (empty($errores)) {
        $_SESSION['success'] = "✅ Todas las notas se guardaron correctamente ($exitos registros)";
    } else {
        $mensaje_error = "Error al guardar algunas notas:\\n";
        $mensaje_error .= "• " . implode("\\n• ", array_slice($errores, 0, 5));
        if (count($errores) > 5) {
            $mensaje_error .= "\\n• ... y " . (count($errores) - 5) . " errores más";
        }
        $_SESSION['error'] = $mensaje_error;
    }
    
    // Redirigir de vuelta al formulario
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
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
if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} elseif (isset($_SESSION['id'])) {
    $docente_id = (int)$_SESSION['id'];
} else {
    $docente_id = 0;
}

// Verificar estados de notas
$notas_aprobadas = false;
$notas_rechazadas = false;
$notas_en_revision = false;
$notas_pendientes = false;

$estudiantes_con_notas_aprobadas = [];
$estudiantes_con_notas_rechazadas = [];
$estudiantes_con_notas_en_revision = [];
$estudiantes_con_notas_pendientes = [];

// Función para obtener datos completos de notas_pendientes MEJORADA
function obtenerNotasPendientes($id_estudiante, $id_materia, $id_periodo, $id_docente) {
    global $db;
    
    $query = "SELECT *, estado as estado_pendiente FROM notas_pendientes 
              WHERE id_usuario = ? 
              AND id_materia = ? 
              AND id_periodo = ? 
              AND id_docente = ? 
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiii", $id_estudiante, $id_materia, $id_periodo, $id_docente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Obtener información de estados CORREGIDA
$estudiantes_info = [];
while ($estudiante = $estudiantes->fetch_assoc()) {
    $notas_definitivas = obtenerNotasDefinitivas($estudiante['id'], $materia_id);
    $notas_pendientes_data = obtenerNotasPendientes($estudiante['id'], $materia_id, $periodo_id, $docente_id);
    
    // JERARQUÍA CORRECTA DE ESTADOS:
    // 1. Aprobada (máxima prioridad) - tabla de notas_definitivas
    // 2. Rechazada - si está en notas_pendientes con estado 'rechazada'
    // 3. En Revisión - si está en tabla notas_pendientes con cualquier otro estado
    // 4. Pendiente - no está en ninguna tabla
    
    $estado = 'pendiente'; // Por defecto
    $valor_nota = 1; // Valor por defecto
    $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
    
    // PRIMERO: Verificar si existe en la tabla de notas_definitivas (APROBADA - MÁXIMA PRIORIDAD)
    if ($notas_definitivas) {
        $estado = 'aprobada';
        $notas_aprobadas = true;
        $estudiantes_con_notas_aprobadas[] = $estudiante['nombre'];
        // Obtener la nota de la tabla definitiva
        if (isset($notas_definitivas[$campo_trayecto]) && $notas_definitivas[$campo_trayecto] !== null) {
            $valor_nota = (int)$notas_definitivas[$campo_trayecto];
        }
    } 
    // SEGUNDO: Si no está aprobada, verificar si está en notas_pendientes y su estado
    elseif ($notas_pendientes_data) {
        // Verificar el estado en notas_pendientes
        $estado_pendiente = isset($notas_pendientes_data['estado_pendiente']) ? $notas_pendientes_data['estado_pendiente'] : 'en_revision';
        
        if ($estado_pendiente === 'rechazada') {
            $estado = 'rechazada';
            $notas_rechazadas = true;
            $estudiantes_con_notas_rechazadas[] = $estudiante['nombre'];
        } else {
            $estado = 'en_revision';
            $notas_en_revision = true;
            $estudiantes_con_notas_en_revision[] = $estudiante['nombre'];
        }
        
        // Obtener la nota de la tabla notas_pendientes
        if (isset($notas_pendientes_data[$campo_trayecto]) && $notas_pendientes_data[$campo_trayecto] !== null) {
            $valor_nota = (int)$notas_pendientes_data[$campo_trayecto];
        }
    } 
    // TERCERO: Si no está en ninguna tabla, es pendiente
    else {
        $estado = 'pendiente';
        $notas_pendientes = true;
        $estudiantes_con_notas_pendientes[] = $estudiante['nombre'];
        // Mantener el valor por defecto de 1
    }
    
    $estudiantes_info[] = [
        'datos' => $estudiante,
        'estado' => $estado,
        'valor_nota' => $valor_nota
    ];
}

// Actualizar la lógica para mostrar campo de soporte - incluir rechazadas
$mostrar_campo_soporte = false;
foreach ($estudiantes_info as $info) {
    // Se puede editar si está pendiente O rechazada
    if ($info['estado'] === 'pendiente' || $info['estado'] === 'rechazada') {
        $mostrar_campo_soporte = true;
        break;
    }
}
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
        <?php if ($notas_aprobadas): ?>
        <div class="alert alert-success">
            <strong>✅ Notas Aprobadas:</strong> Algunas notas ya fueron aprobadas y no pueden ser modificadas. 
            <?php if (!empty($estudiantes_con_notas_aprobadas)): ?>
                <br>
                <strong>Estudiantes con notas aprobadas:</strong>
                <?= implode(', ', $estudiantes_con_notas_aprobadas) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($notas_rechazadas): ?>
        <div class="alert alert-danger">
            <strong>❌ Notas Rechazadas:</strong> Algunas notas fueron rechazadas y necesitan corrección. 
            <?php if (!empty($estudiantes_con_notas_rechazadas)): ?>
                <br>
                <strong>Estudiantes con notas rechazadas:</strong>
                <?= implode(', ', $estudiantes_con_notas_rechazadas) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($notas_en_revision): ?>
        <div class="alert alert-warning">
            <strong>⏳ Notas en Revisión:</strong> Algunas notas están siendo revisadas por los administradores. 
            No pueden ser modificadas hasta que se complete la revisión.
            <?php if (!empty($estudiantes_con_notas_en_revision)): ?>
                <br>
                <strong>Estudiantes con notas en revisión:</strong>
                <?= implode(', ', $estudiantes_con_notas_en_revision) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($notas_pendientes): ?>
        <div class="alert alert-secondary">
            <strong>📝 Notas Pendientes:</strong> Algunas notas no han sido subidas aún. 
            Por favor, ingrese las notas faltantes.
            <?php if (!empty($estudiantes_con_notas_pendientes)): ?>
                <br>
                <strong>Estudiantes con notas pendientes:</strong>
                <?= implode(', ', $estudiantes_con_notas_pendientes) ?>
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
                            <th class="text-center">Nota Trayecto <?= $trayecto_actual ?></th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estudiantes_info as $info): 
                            $estudiante = $info['datos'];
                            $estado = $info['estado'];
                            $valor_nota = $info['valor_nota'];
                            
                            // Determinar si se puede editar
                            $puede_editar = ($estado === 'pendiente' || $estado === 'rechazada');
                            $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['idusuario']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                <td class="text-center">
                                    <?php if ($puede_editar): ?>
                                        <div class="d-inline-block">
                                            <input type="number" 
                                                   name="notas[<?= $estudiante['id'] ?>][<?= $campo_trayecto ?>]" 
                                                   class="form-control nota-input two-digit" 
                                                   min="1" 
                                                   max="20" 
                                                   step="1"
                                                   value="<?= $valor_nota ?>"
                                                   oninput="validarNota(this)"
                                                   onkeydown="limitarDigitos(event, this)"
                                                   maxlength="2"
                                                   required
                                                   style="width: 80px; text-align: center; margin: 0 auto;">
                                        </div>
                                    <?php else: ?>
                                        <div class="d-inline-block">
                                            <input type="text" 
                                                   class="form-control two-digit-display" 
                                                   value="<?= str_pad($valor_nota, 2, '0', STR_PAD_LEFT) ?>"
                                                   readonly
                                                   style="width: 80px; text-align: center; margin: 0 auto; background-color: #f8f9fa; cursor: not-allowed;">
                                            <input type="hidden" 
                                                   name="notas[<?= $estudiante['id'] ?>][<?= $campo_trayecto ?>]" 
                                                   value="<?= $valor_nota ?>">
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badge_class = 'secondary';
                                    $badge_text = 'Pendiente';
                                    $descripcion_estado = 'No se ha subido la nota';
                                    
                                    if ($estado === 'en_revision') {
                                        $badge_class = 'warning';
                                        $badge_text = 'En Revisión';
                                        $descripcion_estado = 'En revisión por administradores';
                                    } elseif ($estado === 'aprobada') {
                                        $badge_class = 'success';
                                        $badge_text = 'Aprobada';
                                        $descripcion_estado = 'No se puede modificar';
                                    } elseif ($estado === 'rechazada') {
                                        $badge_class = 'danger';
                                        $badge_text = 'Rechazada';
                                        $descripcion_estado = 'Puede corregir y reenviar';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $badge_class ?>">
                                        <?= $badge_text ?>
                                    </span>
                                    <br>
                                    <small class="text-muted"><?= $descripcion_estado ?></small>
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
                <?= ($notas_pendientes || $notas_rechazadas) ? 'Enviar Notas y Soporte' : 'Actualizar Notas' ?>
            </button>
            
            <?php if ($notas_en_revision): ?>
                <div class="mt-3 alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Nota:</strong> Las notas en revisión no pueden ser modificadas hasta que los administradores completen su evaluación.
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<style>
.nota-input.two-digit {
    font-variant-numeric: tabular-nums;
    font-weight: bold;
    letter-spacing: 2px;
}

.nota-input.two-digit::-webkit-outer-spin-button,
.nota-input.two-digit::-webkit-inner-spin-button {
    opacity: 1;
    height: 30px;
}

.two-digit-display {
    font-variant-numeric: tabular-nums;
    font-weight: bold;
    letter-spacing: 2px;
}

.img-preview img {
    max-width: 100%;
    height: auto;
}

#preview-grupo img {
    max-height: 150px;
    max-width: 100%;
}
</style>

<script>
function limitarDigitos(event, input) {
    if (event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab' || 
        event.key === 'ArrowLeft' || event.key === 'ArrowRight' || event.key === 'Home' || event.key === 'End') {
        return;
    }
    
    if (input.value.length >= 2 && !event.ctrlKey && !event.metaKey) {
        event.preventDefault();
        
        if (event.key >= '0' && event.key <= '9') {
            input.value = event.key;
            validarNota(input);
        }
    }
}

function validarNota(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
    
    if (input.value === '') {
        input.value = '1';
        return;
    }
    
    let valor = parseInt(input.value);
    
    if (valor < 1) {
        input.value = '1';
    } else if (valor > 20) {
        input.value = '20';
    }
    
    if (input.value.length > 2) {
        input.value = input.value.slice(0, 2);
    }
}

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
                        previewGrupo.innerHTML = `<img src="${e.target.result}" class="img-thumbnail">`;
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
    
    // Validar todas las notas al cargar la página
    document.querySelectorAll('.nota-input').forEach(input => {
        let valor = parseInt(input.value);
        input.value = valor;
        
        input.addEventListener('blur', function() {
            validarNota(this);
        });
        
        input.addEventListener('focus', function() {
            this.select();
        });
        
        input.addEventListener('change', function() {
            validarNota(this);
        });
        
        input.addEventListener('input', function(e) {
            if (this.value === '') {
                setTimeout(() => {
                    this.value = '1';
                }, 10);
            }
        });
        
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            let pastedData = e.clipboardData.getData('text');
            let numero = parseInt(pastedData.replace(/[^0-9]/g, ''));
            if (!isNaN(numero) && numero >= 1 && numero <= 20) {
                this.value = numero;
                validarNota(this);
            }
        });
    });
});
</script>