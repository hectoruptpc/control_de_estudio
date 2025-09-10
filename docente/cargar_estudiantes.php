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

// Verificar estados de notas
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