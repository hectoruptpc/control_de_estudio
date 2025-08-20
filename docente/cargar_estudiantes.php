<?php
require_once('../funciones/functions.php');

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
    $query = "SELECT m.*, t.numero_trayecto FROM materias m 
              INNER JOIN trayectos t ON m.trayecto = t.id_trayecto 
              WHERE m.id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $materia_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
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

$materia = obtenerInfoMateria($materia_id);
$estudiantes = obtenerEstudiantesPorSeccion($seccion_id);
$periodo_id = obtenerPeriodoSeccion($seccion_id);

if (!$materia) {
    die('Error: Materia no encontrada');
}

if (!$estudiantes) {
    die('No hay estudiantes en esta sección');
}

$trayecto_actual = $materia['numero_trayecto'];
$mostrar_trayectos = [];

if ($trayecto_actual >= 0 && $trayecto_actual <= 2) {
    $mostrar_trayectos = [0, 1, 2];
} elseif ($trayecto_actual >= 3 && $trayecto_actual <= 4) {
    $mostrar_trayectos = [3, 4];
}
?>

<div class="card">
    <div class="card-header bg-info text-white">
        <h5>Estudiantes - <?= htmlspecialchars($materia['nombre_materia']) ?></h5>
        <p class="mb-0">Trayecto <?= $trayecto_actual ?> | Periodo ID: <?= $periodo_id ?></p>
    </div>
    <div class="card-body">
        <form id="form-notas" method="POST">
            <input type="hidden" name="materia_id" value="<?= $materia_id ?>">
            <input type="hidden" name="seccion_id" value="<?= $seccion_id ?>">
            <input type="hidden" name="periodo_id" value="<?= $periodo_id ?>">
            <input type="hidden" name="trayecto_actual" value="<?= $trayecto_actual ?>">
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <?php foreach ($mostrar_trayectos as $trayecto): ?>
                                <th>Trayecto <?= $trayecto ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($estudiante = $estudiantes->fetch_assoc()): ?>
                            <?php $notas = obtenerNotasEstudiante($estudiante['id'], $materia_id); ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['idusuario']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                <?php foreach ($mostrar_trayectos as $trayecto): ?>
                                    <td>
                                        <?php
                                        $valor_nota = '';
                                        if ($notas && isset($notas['trayecto_' . $trayecto]) && $notas['trayecto_' . $trayecto] !== null) {
                                            $valor_nota = (int)$notas['trayecto_' . $trayecto];
                                        }
                                        ?>
                                        <input type="number" 
                                               name="notas[<?= $estudiante['id'] ?>][trayecto_<?= $trayecto ?>]" 
                                               class="form-control nota-input" 
                                               min="1" 
                                               max="20" 
                                               oninput="validarNota(this)"
                                               value="<?= $valor_nota ?: '01' ?>"
                                               required>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Guardar Notas Pendientes
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