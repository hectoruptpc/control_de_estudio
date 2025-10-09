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
                $_SESSION['error'] = "Error al subir el archivo de soporte";
            }
        }
    }
    
    // Procesar cada nota
    $errores = [];
    $exitos = 0;
    $total_procesados = 0;
    
    foreach ($notas as $id_estudiante => $nota_data) {
        $id_estudiante = (int)$id_estudiante;
        
        // Verificar si existe el campo del trayecto
        if (!isset($nota_data[$campo_trayecto])) {
            $errores[] = "Campo de trayecto no encontrado para estudiante ID $id_estudiante";
            continue;
        }
        
        $valor_nota = (int)$nota_data[$campo_trayecto];
        $total_procesados++;
        
        // Validar que la nota esté entre 1 y 20
        if ($valor_nota < 1 || $valor_nota > 20) {
            $errores[] = "Nota inválida para el estudiante ID $id_estudiante: $valor_nota";
            continue;
        }
        
        // VERIFICAR ESTADO ACTUAL para determinar si puede editar
        $estado_actual = obtenerEstadoCorregido($id_estudiante, $materia_id, $periodo_id, $docente_id);
        
        // Si está aprobada o en revisión, no permitir cambios
        if ($estado_actual === 'aprobada' || $estado_actual === 'en_revision') {
            // Solo contabilizar pero no modificar
            $exitos++;
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
                                fecha_subida = NOW() 
                            WHERE id_usuario = ? 
                            AND id_materia = ? 
                            AND id_periodo = ? 
                            AND id_docente = ?";
            
            $stmt = $db->prepare($update_query);
            if ($soporte_nombre) {
                $stmt->bind_param("issiiii", $valor_nota, $soporte_nombre, $tipo_archivo, 
                                 $id_estudiante, $materia_id, $periodo_id, $docente_id);
            } else {
                // Si no hay nuevo soporte, mantener el existente
                $update_query = "UPDATE notas_pendientes 
                                SET $campo_trayecto = ?, 
                                    fecha_subida = NOW() 
                                WHERE id_usuario = ? 
                                AND id_materia = ? 
                                AND id_periodo = ? 
                                AND id_docente = ?";
                $stmt = $db->prepare($update_query);
                $stmt->bind_param("iiiii", $valor_nota, $id_estudiante, $materia_id, $periodo_id, $docente_id);
            }
        } else {
            // Insertar nuevo registro
            $insert_query = "INSERT INTO notas_pendientes 
                            (id_usuario, id_materia, id_periodo, id_docente, 
                             $campo_trayecto, soporte, tipo_archivo, fecha_envio, fecha_subida) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
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
        if ($exitos > 0) {
            $_SESSION['success'] = "✅ Las notas se procesaron correctamente ($exitos de $total_procesados registros)";
        } else {
            $_SESSION['info'] = "ℹ️ No se realizaron cambios - las notas ya están en revisión o aprobadas";
        }
    } else {
        $mensaje_error = "Se procesaron $exitos de $total_procesados notas, pero hubo errores:\\n";
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

// MOSTRAR FORMULARIO
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

// Obtener información de estados MEJORADA
$estudiantes_info = [];
if ($estudiantes) {
    while ($estudiante = $estudiantes->fetch_assoc()) {
        $notas_definitivas = obtenerNotasDefinitivas($estudiante['id'], $materia_id);
        $notas_pendientes_data = obtenerNotasPendientesEstudiante($estudiante['id'], $materia_id, $periodo_id, $docente_id);
        
        // USAR LA FUNCIÓN MEJORADA para determinar estado
        $estado = obtenerEstadoCorregido($estudiante['id'], $materia_id, $periodo_id, $docente_id);
        
        // Determinar valor de nota y si puede editar
        $valor_nota = 1; // Valor por defecto
        $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
        $puede_editar = ($estado === 'pendiente' || $estado === 'rechazada');
        
        // Obtener valor de nota según el estado
        if ($estado === 'aprobada' && $notas_definitivas) {
            // Buscar en notas_definitivas
            if (isset($notas_definitivas[$campo_trayecto]) && $notas_definitivas[$campo_trayecto] !== null) {
                $valor_nota = (int)$notas_definitivas[$campo_trayecto];
            }
        } elseif (($estado === 'en_revision' || $estado === 'rechazada') && $notas_pendientes_data) {
            // Buscar en notas_pendientes - llenar automáticamente con la nota guardada
            if (isset($notas_pendientes_data[$campo_trayecto]) && $notas_pendientes_data[$campo_trayecto] !== null) {
                $valor_nota = (int)$notas_pendientes_data[$campo_trayecto];
            }
        }
        
        // Actualizar flags para mostrar alertas
        if ($estado === 'aprobada') {
            $notas_aprobadas = true;
            $estudiantes_con_notas_aprobadas[] = $estudiante['nombre'];
        } elseif ($estado === 'rechazada') {
            $notas_rechazadas = true;
            $estudiantes_con_notas_rechazadas[] = $estudiante['nombre'];
        } elseif ($estado === 'en_revision') {
            $notas_en_revision = true;
            $estudiantes_con_notas_en_revision[] = $estudiante['nombre'];
        } else { // pendiente
            $notas_pendientes = true;
            $estudiantes_con_notas_pendientes[] = $estudiante['nombre'];
        }
        
        $estudiantes_info[] = [
            'datos' => $estudiante,
            'estado' => $estado,
            'valor_nota' => $valor_nota,
            'puede_editar' => $puede_editar
        ];
    }
}

// Verificar si hay algún estudiante que pueda editar (para mostrar el campo de soporte)
$mostrar_campo_soporte = false;
foreach ($estudiantes_info as $info) {
    if ($info['puede_editar']) {
        $mostrar_campo_soporte = true;
        break;
    }
}
?>

<!-- EL HTML PERMANECE IGUAL DESDE AQUÍ -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Notas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
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

        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $_SESSION['info'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5><i class="fas fa-users"></i> Estudiantes - <?= htmlspecialchars($materia['nombre_materia']) ?></h5>
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
                    <strong>❌ Notas Rechazadas:</strong> Algunas notas fueron rechazadas por los administradores. 
                    Por favor, corríjalas y envíelas nuevamente para revisión.
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
                
                <!-- PANEL DE INFORMACIÓN DE ESTADOS -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Información de Estados:</strong><br>
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
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h6><i class="fas fa-paperclip"></i> Soporte del Grupo</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="soporte_grupo"><strong>Imagen/PDF de Soporte:</strong></label>
                                        <input type="file" 
                                               name="soporte_grupo" 
                                               id="soporte_grupo"
                                               class="form-control soporte-grupo" 
                                               accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
                                        <small class="form-text text-muted">
                                            Formatos permitidos: JPG, PNG, GIF, WEBP, PDF. Tamaño máximo: 5MB
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                    $puede_editar = $info['puede_editar'];
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
                    
                    <div class="d-flex">
                        <button type="submit" class="btn btn-success btn-lg mr-2">
                            <i class="fas fa-save"></i> 
                            <?= ($notas_rechazadas || $notas_pendientes) ? 'Enviar Notas' : 'Actualizar Notas' ?>
                        </button>
                        <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                    
                    <?php if ($notas_en_revision): ?>
                        <div class="mt-3 alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Nota:</strong> Las notas en revisión no pueden ser modificadas hasta que los administradores completen su evaluación.
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function validarNota(input) {
        let valor = parseInt(input.value);
        
        if (isNaN(valor) || valor < 1) {
            input.value = 1;
        } else if (valor > 20) {
            input.value = 20;
        }
        
        // Formatear a 2 dígitos
        if (input.value.length === 1) {
            input.value = '0' + input.value;
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
                            previewGrupo.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">`;
                        } else {
                            previewGrupo.innerHTML = `
                                <div class="alert alert-info text-center p-2">
                                    <i class="fas fa-file-pdf fa-2x"></i><br>
                                    <strong>Archivo PDF</strong>
                                </div>
                            `;
                        }
                        nombreArchivoGrupo.textContent = 'Archivo: ' + file.name;
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
            // Asegurar que el valor esté entre 1-20
            validarNota(input);
            
            input.addEventListener('blur', function() {
                validarNota(this);
            });
            
            input.addEventListener('change', function() {
                validarNota(this);
            });
            
            input.addEventListener('input', function() {
                // Permitir solo números
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // Validar formulario antes de enviar
        document.getElementById('form-notas').addEventListener('submit', function(e) {
            let notasValidas = true;
            document.querySelectorAll('.nota-input').forEach(input => {
                const valor = parseInt(input.value);
                if (isNaN(valor) || valor < 1 || valor > 20) {
                    notasValidas = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!notasValidas) {
                e.preventDefault();
                alert('Por favor, ingrese notas válidas entre 1 y 20 para todos los estudiantes.');
            }
        });
    });
    </script>

    <style>
    .nota-input.two-digit {
        font-variant-numeric: tabular-nums;
        font-weight: bold;
        letter-spacing: 1px;
        text-align: center;
    }

    .two-digit-display {
        font-variant-numeric: tabular-nums;
        font-weight: bold;
        letter-spacing: 1px;
        text-align: center;
    }

    .nota-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }
    </style>
</body>
</html>