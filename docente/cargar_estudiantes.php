<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['seccion_id']) || !isset($_POST['materia_id'])) {
    die('Parámetros incompletos');
}

$seccion_id = (int)$_POST['seccion_id'];
$materia_id = (int)$_POST['materia_id'];

function obtenerInfoMateria($materia_id) {
    global $db;
    $query = "SELECT m.*, t.numero_trayecto 
              FROM materias m 
              LEFT JOIN trayectos t ON m.trayecto = t.id_trayecto 
              WHERE m.id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Si no encuentra el trayecto, intentar obtener solo la información de la materia
    $query = "SELECT * FROM materias WHERE id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $materia = $result->fetch_assoc();
        
        // Si el trayecto es 0, establecer manualmente el número de trayecto
        if ($materia['trayecto'] == 0) {
            $materia['numero_trayecto'] = 0;
        }
        
        return $materia;
    }
    
    return null;
}

function obtenerEstudiantesPorSeccion($seccion_id) {
    global $db;
    $query = "SELECT u.id, u.nombre, u.idusuario 
              FROM users u
              INNER JOIN estudiante_seccion es ON u.id = es.id_usuario
              WHERE es.id_seccion = ? AND u.estudiante = 1
              ORDER BY u.nombre";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerNotasEstudiante($estudiante_id, $materia_id) {
    global $db;
    $query = "SELECT * FROM notas_pendientes 
              WHERE id_usuario = ? AND id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $estudiante_id, $materia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

function obtenerPeriodoSeccion($seccion_id) {
    global $db;
    $query = "SELECT id_periodo FROM secciones WHERE id_seccion = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['id_periodo'];
}

function obtenerTrayectoSeccion($seccion_id) {
    global $db;
    $query = "SELECT t.id_trayecto, t.numero_trayecto 
              FROM secciones s 
              INNER JOIN trayectos t ON s.id_trayecto = t.id_trayecto 
              WHERE s.id_seccion = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $seccion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

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

// Determinar qué trayecto mostrar según el id_trayecto de la sección
$trayecto_a_mostrar = '';
switch ($id_trayecto_seccion) {
    case 1: $trayecto_a_mostrar = 0; break; // Trayecto Inicial
    case 2: $trayecto_a_mostrar = 1; break; // Trayecto 1
    case 3: $trayecto_a_mostrar = 2; break; // Trayecto 2
    case 4: $trayecto_a_mostrar = 3; break; // Trayecto 3
    case 5: $trayecto_a_mostrar = 4; break; // Trayecto 4
    default: $trayecto_a_mostrar = 0;
}

$notas_aprobadas = false;
$notas_rechazadas = false;
$estudiantes_con_notas_aprobadas = [];
$estudiantes_con_notas_rechazadas = [];

while ($estudiante = $estudiantes->fetch_assoc()) {
    $notas = obtenerNotasEstudiante($estudiante['id'], $materia_id);
    if ($notas) {
        if ($notas['estado'] === 'aprobada') {
            $notas_aprobadas = true;
            $estudiantes_con_notas_aprobadas[] = $estudiante['nombre'];
        } elseif ($notas['estado'] === 'rechazada') {
            $notas_rechazadas = true;
            $estudiantes_con_notas_rechazadas[] = $estudiante['nombre'];
        }
    }
}

$estudiantes->data_seek(0);
?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5>Estudiantes - <?= htmlspecialchars($materia['nombre_materia']) ?></h5>
        <p class="mb-0">Trayecto <?= $trayecto_actual ?> | Periodo ID: <?= $periodo_id ?></p>
    </div>
    <div class="card-body">
        
        <?php if ($notas_aprobadas): ?>
        <div class="alert alert-success">
            <strong>✅ Notas Aprobadas:</strong> Algunas notas ya fueron aprobadas y no pueden ser modificadas. 
            Si necesita hacer correcciones, debe dirigirse a la universidad.
            <br>
            <strong>Estudiantes con notas aprobadas:</strong>
            <?= implode(', ', $estudiantes_con_notas_aprobadas) ?>
        </div>
        <?php endif; ?>
        
        <?php if ($notas_rechazadas): ?>
        <div class="alert alert-danger">
            <strong>❌ Notas Rechazadas:</strong> Algunas notas fueron rechazadas por los administradores. 
            Por favor, corríjalas y envíelas nuevamente para revisión.
            <br>
            <strong>Estudiantes con notas rechazadas:</strong>
            <?= implode(', ', $estudiantes_con_notas_rechazadas) ?>
        </div>
        <?php endif; ?>
        
        <form id="form-notas" method="POST">
            <input type="hidden" name="materia_id" value="<?= $materia_id ?>">
            <input type="hidden" name="seccion_id" value="<?= $seccion_id ?>">
            <input type="hidden" name="periodo_id" value="<?= $periodo_id ?>">
            <input type="hidden" name="trayecto_actual" value="<?= $trayecto_actual ?>">
            <input type="hidden" name="id_trayecto_seccion" value="<?= $id_trayecto_seccion ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Nota Trayecto <?= $trayecto_actual ?></th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                            <?php 
                            $notas = obtenerNotasEstudiante($estudiante['id'], $materia_id);
                            $estado = $notas ? $notas['estado'] : 'pendiente';
                            $puede_editar = ($estado === 'pendiente' || $estado === 'rechazada');
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['idusuario']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                <td>
                                    <?php
                                    $valor_nota = '';
                                    $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
                                    
                                    if ($notas && isset($notas[$campo_trayecto]) && $notas[$campo_trayecto] !== null) {
                                        $valor_nota = (int)$notas[$campo_trayecto];
                                    }
                                    ?>
                                    <?php if ($puede_editar): ?>
                                        <input type="number" 
                                               name="notas[<?= $estudiante['id'] ?>][<?= $campo_trayecto ?>]" 
                                               class="form-control nota-input" 
                                               min="1" 
                                               max="20" 
                                               oninput="validarNota(this)"
                                               value="<?= $valor_nota ?: '01' ?>"
                                               required>
                                    <?php else: ?>
                                        <input type="number" 
                                               class="form-control" 
                                               value="<?= $valor_nota ?: '' ?>"
                                               readonly
                                               style="background-color: #f8f9fa; cursor: not-allowed;">
                                        <input type="hidden" 
                                               name="notas[<?= $estudiante['id'] ?>][<?= $campo_trayecto ?>]" 
                                               value="<?= $valor_nota ?>">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $badge_class = 'secondary';
                                    $badge_text = 'Sin estado';
                                    
                                    if ($estado === 'pendiente') {
                                        $badge_class = 'warning';
                                        $badge_text = 'Pendiente';
                                    } elseif ($estado === 'aprobada') {
                                        $badge_class = 'success';
                                        $badge_text = 'Aprobada';
                                    } elseif ($estado === 'rechazada') {
                                        $badge_class = 'danger';
                                        $badge_text = 'Rechazada';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $badge_class ?>">
                                        <?= $badge_text ?>
                                    </span>
                                    <?php if ($estado === 'rechazada'): ?>
                                        <br>
                                        <small class="text-danger">Puede corregir y reenviar</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($notas_aprobadas || $notas_rechazadas): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Estados:</strong><br>
                • <span class="badge badge-warning">Pendiente</span> - Puede editar<br>
                • <span class="badge badge-success">Aprobada</span> - No se puede modificar<br>
                • <span class="badge badge-danger">Rechazada</span> - Puede corregir y reenviar
            </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> 
                <?= $notas_rechazadas ? 'Reenviar Notas Rechazadas' : 'Guardar Notas' ?>
            </button>
        </form>
    </div>
</div>

<script>
function validarNota(input) {
    let valor = parseInt(input.value);
    
    if (input.value === '' || isNaN(valor)) {
        input.value = '01';
        return;
    }
    
    if (valor < 1) {
        input.value = '01';
    } else if (valor > 20) {
        input.value = '20';
    } else {
        input.value = valor.toString().padStart(2, '0');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nota-input').forEach(input => {
        input.addEventListener('blur', function() {
            validarNota(this);
        });
    });
});
</script>