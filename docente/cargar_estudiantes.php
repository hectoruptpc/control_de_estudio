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

// Obtener información de estados ANTES de mostrar el formulario
$estudiantes_info = [];
while ($estudiante = $estudiantes->fetch_assoc()) {
    $notas = obtenerNotasEstudiante($estudiante['id'], $materia_id);
    $estudiantes_info[] = [
        'datos' => $estudiante,
        'notas' => $notas
    ];
    
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
?>



<div class="card">
    <div class="card-header bg-info text-white">
        <h5>Estudiantes - <?= htmlspecialchars($materia['nombre_materia']) ?></h5>
        <p class="mb-0">Trayecto <?= $trayecto_actual ?> | Periodo ID: <?= $periodo_id ?></p>
    </div>
    <div class="card-body">
        
        <!-- MOSTRAR SIEMPRE LOS MENSAJES DE ESTADO (fuera del condicional) -->
        <div class="alert alert-success <?= $notas_aprobadas ? '' : 'd-none' ?>" id="alert-aprobadas">
            <strong>✅ Notas Aprobadas:</strong> Algunas notas ya fueron aprobadas y no pueden ser modificadas. 
            Si necesita hacer correcciones, debe dirigirse a la universidad.
            <br>
            <strong>Estudiantes con notas aprobadas:</strong>
            <?= implode(', ', $estudiantes_con_notas_aprobadas) ?>
        </div>
        
        <div class="alert alert-danger <?= $notas_rechazadas ? '' : 'd-none' ?>" id="alert-rechazadas">
            <strong>❌ Notas Rechazadas:</strong> Algunas notas fueron rechazadas por los administradores. 
            Por favor, corríjalas y envíelas nuevamente para revisión.
            <br>
            <strong>Estudiantes con notas rechazadas:</strong>
            <?= implode(', ', $estudiantes_con_notas_rechazadas) ?>
        </div>
        
        <!-- MOSTRAR SIEMPRE EL PANEL DE INFORMACIÓN DE ESTADOS -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Estados:</strong><br>
            • <span class="badge badge-warning">Pendiente</span> - Puede editar<br>
            • <span class="badge badge-success">Aprobada</span> - No se puede modificar<br>
            • <span class="badge badge-danger">Rechazada</span> - Puede corregir y reenviar
        </div>
        
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
                        <?php foreach ($estudiantes_info as $info): 
                            $estudiante = $info['datos'];
                            $notas = $info['notas'];
                            $estado = $notas ? $notas['estado'] : 'pendiente';
                            $puede_editar = ($estado === 'pendiente' || $estado === 'rechazada');
                            
                            // Obtener valor de la nota en formato de 2 dígitos
                            $valor_nota = '';
                            $campo_trayecto = 'trayecto_' . $trayecto_a_mostrar;
                            
                            if ($notas && isset($notas[$campo_trayecto]) && $notas[$campo_trayecto] !== null) {
                                $valor_nota = str_pad($notas[$campo_trayecto], 2, '0', STR_PAD_LEFT);
                            } else {
                                $valor_nota = '01'; // Valor por defecto en 2 dígitos
                            }
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($estudiante['idusuario']) ?></td>
                                <td><?= htmlspecialchars($estudiante['nombre']) ?></td>
                                <td>
                                    <?php if ($puede_editar): ?>
                                        <input type="text" 
                                               name="notas[<?= $estudiante['id'] ?>][<?= $campo_trayecto ?>]" 
                                               class="form-control nota-input" 
                                               min="1" 
                                               max="20" 
                                               oninput="validarNota(this)"
                                               value="<?= $valor_nota ?>"
                                               pattern="[0-9]{2}"
                                               maxlength="2"
                                               required>
                                    <?php else: ?>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?= $valor_nota ?>"
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
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> 
                <?= $notas_rechazadas ? 'Reenviar Notas Rechazadas' : 'Guardar Notas' ?>
            </button>
        </form>
    </div>
</div>

<script>
function validarNota(input) {
    // Eliminar cualquier carácter no numérico
    input.value = input.value.replace(/[^0-9]/g, '');
    
    // Si está vacío, establecer como 01
    if (input.value === '') {
        input.value = '01';
        return;
    }
    
    // Convertir a número
    let valor = parseInt(input.value);
    
    // Validar rango
    if (valor < 1) {
        input.value = '01';
    } else if (valor > 20) {
        input.value = '20';
    } else {
        // Asegurar que siempre tenga 2 dígitos
        input.value = valor.toString().padStart(2, '0');
    }
}

// Validar todas las notas al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nota-input').forEach(input => {
        validarNota(input);
        
        input.addEventListener('blur', function() {
            validarNota(this);
        });
        
        input.addEventListener('focus', function() {
            this.select();
        });
    });
    
    // Mostrar alertas según corresponda
    <?php if ($notas_aprobadas): ?>
    document.getElementById('alert-aprobadas').classList.remove('d-none');
    <?php endif; ?>
    
    <?php if ($notas_rechazadas): ?>
    document.getElementById('alert-rechazadas').classList.remove('d-none');
    <?php endif; ?>
    
    // Agregar funcionalidad al botón volver
    document.getElementById('btn-volver').addEventListener('click', function() {
        // Esta función será manejada por el JavaScript principal en notas.php
        // Solo está aquí por si se accede directamente a cargar_estudiantes.php
        if (window.parent && window.parent !== window) {
            // Si estamos en un iframe o contexto similar
            window.parent.postMessage('volver-a-secciones', '*');
        } else {
            // Si estamos en la página directamente
            window.history.back();
        }
    });
});
</script>