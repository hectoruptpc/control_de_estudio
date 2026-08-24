<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Horarios por Personal";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('horario_personal');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['asignar'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_tipo_horario = $_POST['id_tipo_horario'];
        
        $resultado = asignarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario);
        $mensaje = $resultado ? "Horario asignado correctamente." : "Error: Esta relación ya existe o hubo un problema.";
    } elseif (isset($_POST['editar'])) {
        $id_relacion = $_POST['id_relacion'];
        $id_tipo_horario = $_POST['id_tipo_horario'];
        
        $resultado = actualizarTipoHorarioUsuario($db, $id_relacion, $id_tipo_horario);
        $mensaje = $resultado ? "Horario actualizado correctamente." : "Error al actualizar el horario.";
    } elseif (isset($_POST['eliminar'])) {
        $id_relacion = $_POST['id_relacion'];
        
        $resultado = eliminarTipoHorarioUsuarioPorId($db, $id_relacion);
        $mensaje = $resultado ? "Relación eliminada correctamente." : "Error al eliminar la relación.";
    }
}

// Obtener solo el personal (docente, admin, super_user o usuario = 1) que NO tiene asignación de horario
$query_personal_sin_horario = "SELECT u.id, u.idusuario, u.nombre, u.username, u.docente, u.admin, u.super_user, u.usuario 
                               FROM users u
                               WHERE (u.docente = 1 OR u.admin = 1 OR u.super_user = 1 OR u.usuario = 1)
                               AND u.id NOT IN (SELECT id_usuario FROM tipo_horario_personal)
                               ORDER BY u.nombre";
$result_personal_sin_horario = $db->query($query_personal_sin_horario);
$personal_sin_horario = $result_personal_sin_horario ? $result_personal_sin_horario->fetch_all(MYSQLI_ASSOC) : [];

// Obtener tipos de horario
$tipos_horario = obtenerTiposHorario($db);

// Obtener todas las relaciones existentes
$relaciones = obtenerTodasRelacionesHorarioPersonal($db);

include("includes/head.php");

// Mostrar mensaje si existe
if (isset($mensaje)) {
    echo '<div class="alert alert-' . (strpos($mensaje, 'Error') !== false ? 'danger' : 'success') . ' alert-dismissible fade show" role="alert">
            ' . $mensaje . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Gestión de Horarios por Personal</h1>
            
            <!-- Formulario para asignar horario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Asignar Horario a Personal</h5>
                </div>
                <div class="card-body">
                    <?php if (count($personal_sin_horario) > 0): ?>
                        <!-- Buscador en tiempo real por Cédula o Nombre -->
                        <div class="form-group">
                            <label for="buscar_personal"><i class="fas fa-search text-primary mr-1"></i> Buscar Personal (Cédula o Nombre):</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                </div>
                                <input type="text" id="buscar_personal" class="form-control" placeholder="Escriba la cédula o nombre para filtrar en tiempo real..." autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="btn_limpiar_busqueda">
                                        <i class="fas fa-times"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted" id="personal_coincidencias_info">Mostrando <?php echo count($personal_sin_horario); ?> personas sin horario asignado.</small>
                        </div>

                        <div class="form-group">
                            <label for="id_usuario">Personal Seleccionado:</label>
                            <select class="form-control" id="id_usuario" name="id_usuario" required>
                                <option value="">Seleccionar personal</option>
                                <?php foreach ($personal_sin_horario as $usuario): 
                                    $tipo_usuario = obtenerTipoUsuarioTexto($usuario);
                                ?>
                                    <option value="<?php echo $usuario['id']; ?>" data-search="<?php echo htmlspecialchars(mb_strtolower($usuario['nombre'] . ' ' . $usuario['idusuario'] . ' ' . $tipo_usuario, 'UTF-8')); ?>">
                                        <?php echo htmlspecialchars($usuario['nombre'] . ' (C.I: ' . $usuario['idusuario'] . ') - ' . $tipo_usuario); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_tipo_horario">Tipo de Horario:</label>
                            <select class="form-control" id="id_tipo_horario" name="id_tipo_horario" required>
                                <option value="">Seleccionar tipo de horario</option>
                                <?php foreach ($tipos_horario as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>">
                                        <?php echo htmlspecialchars($tipo['nombre'] . ' (Acad: ' . $tipo['horas_academicas'] . 'h, At: ' . $tipo['horas_atendiendo'] . 'h)'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="asignar">
                            Asignar Horario
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="alert alert-info">
                        Todo el personal ya tiene asignación de horario.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Tabla de personal sin horario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Personal Sin Asignación de Horario</h5>
                </div>
                <div class="card-body">
                    <?php if (count($personal_sin_horario) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Personal</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($personal_sin_horario as $usuario): 
                                        $tipo_usuario = obtenerTipoUsuarioTexto($usuario);
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($usuario['idusuario']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($tipo_usuario); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">Todo el personal tiene asignación de horario.</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Lista de relaciones existentes -->
            <div class="card">
                <div class="card-header">
                    <h5>Relaciones Existentes</h5>
                </div>
                <div class="card-body">
                    <?php if (count($relaciones) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Tipo de Personal</th>
                                        <th>Horario</th>
                                        <th>Horas Académicas</th>
                                        <th>Horas Atendiendo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($relaciones as $relacion): 
                                        $tipo_usuario = obtenerTipoUsuarioTexto($relacion);
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($relacion['idusuario']); ?></td>
                                            <td><?php echo htmlspecialchars($relacion['usuario_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($tipo_usuario); ?></td>
                                            <td><?php echo htmlspecialchars($relacion['horario_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($relacion['horas_academicas']); ?>h</td>
                                            <td><?php echo htmlspecialchars($relacion['horas_atendiendo']); ?>h</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditar" 
                                                        data-id="<?php echo $relacion['id']; ?>"
                                                        data-id-usuario="<?php echo $relacion['id_usuario']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($relacion['usuario_nombre']); ?>"
                                                        data-id-tipo-horario="<?php echo $relacion['id_tipo_horario']; ?>"
                                                        data-horario-actual="<?php echo htmlspecialchars($relacion['horario_nombre']); ?>">
                                                    Editar
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalEliminar" 
                                                        data-id="<?php echo $relacion['id']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($relacion['usuario_nombre']); ?>"
                                                        data-horario="<?php echo htmlspecialchars($relacion['horario_nombre']); ?>">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No hay relaciones entre personal y horarios registradas aún.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Horario de Personal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_relacion" id="edit_id_relacion">
                    <input type="hidden" name="id_usuario" id="edit_id_usuario">
                    
                    <div class="form-group">
                        <label>Personal:</label>
                        <p class="form-control-static" id="edit_nombre_usuario"></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Horario Actual:</label>
                        <p class="form-control-static" id="edit_horario_actual"></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_id_tipo_horario">Nuevo Tipo de Horario:</label>
                        <select class="form-control" id="edit_id_tipo_horario" name="id_tipo_horario" required>
                            <option value="">Seleccionar tipo de horario</option>
                            <?php foreach ($tipos_horario as $tipo): ?>
                                <option value="<?php echo $tipo['id']; ?>">
                                    <?php echo htmlspecialchars($tipo['nombre'] . ' (Acad: ' . $tipo['horas_academicas'] . 'h, At: ' . $tipo['horas_atendiendo'] . 'h)'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" name="editar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Asignación de Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_relacion" id="delete_id_relacion">
                    <p>¿Estás seguro de que deseas eliminar la asignación de horario para <strong id="delete_nombre"></strong>?</p>
                    <p>Horario: <strong id="delete_horario"></strong></p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para manejar los modales
document.addEventListener('DOMContentLoaded', function() {
    // Modal de edición
    $('#modalEditar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var idUsuario = button.data('id-usuario');
        var nombre = button.data('nombre');
        var idTipoHorario = button.data('id-tipo-horario');
        var horarioActual = button.data('horario-actual');
        
        var modal = $(this);
        modal.find('#edit_id_relacion').val(id);
        modal.find('#edit_id_usuario').val(idUsuario);
        modal.find('#edit_nombre_usuario').text(nombre);
        modal.find('#edit_horario_actual').text(horarioActual);
        modal.find('#edit_id_tipo_horario').val(idTipoHorario);
    });
    
    // Modal de eliminación
    $('#modalEliminar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var horario = button.data('horario');
        
        var modal = $(this);
        modal.find('#delete_id_relacion').val(id);
        modal.find('#delete_nombre').text(nombre);
        modal.find('#delete_horario').text(horario);
    });

    // Filtro en tiempo real para el select de personal sin horario
    const inputBuscar = document.getElementById('buscar_personal');
    const selectPersonal = document.getElementById('id_usuario');
    const infoCoincidencias = document.getElementById('personal_coincidencias_info');
    const btnLimpiar = document.getElementById('btn_limpiar_busqueda');

    if (inputBuscar && selectPersonal) {
        const opcionesOriginales = Array.from(selectPersonal.options);

        function filtrarOpciones() {
            const query = inputBuscar.value.toLowerCase().trim();
            selectPersonal.innerHTML = '';
            
            let contador = 0;
            let primeraCoincidencia = null;

            opcionesOriginales.forEach(function(opt) {
                if (opt.value === '') {
                    selectPersonal.appendChild(opt.cloneNode(true));
                    return;
                }

                const searchMeta = opt.getAttribute('data-search') || opt.text.toLowerCase();
                if (query === '' || searchMeta.includes(query)) {
                    const optClon = opt.cloneNode(true);
                    selectPersonal.appendChild(optClon);
                    contador++;
                    if (!primeraCoincidencia) {
                        primeraCoincidencia = optClon.value;
                    }
                }
            });

            if (query !== '') {
                if (contador === 1 && primeraCoincidencia) {
                    selectPersonal.value = primeraCoincidencia;
                } else if (contador === 0) {
                    const optVacia = document.createElement('option');
                    optVacia.value = '';
                    optVacia.textContent = '❌ No se encontró personal que coincida con la búsqueda';
                    optVacia.disabled = true;
                    optVacia.selected = true;
                    selectPersonal.appendChild(optVacia);
                }
            }

            if (infoCoincidencias) {
                if (query === '') {
                    infoCoincidencias.textContent = 'Mostrando ' + (opcionesOriginales.length - 1) + ' personas disponibles.';
                    infoCoincidencias.className = 'form-text text-muted';
                } else {
                    infoCoincidencias.textContent = 'Se encontraron ' + contador + ' de ' + (opcionesOriginales.length - 1) + ' personas.';
                    infoCoincidencias.className = contador > 0 ? 'form-text text-success font-weight-bold' : 'form-text text-danger font-weight-bold';
                }
            }
        }

        inputBuscar.addEventListener('input', filtrarOpciones);

        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function() {
                inputBuscar.value = '';
                filtrarOpciones();
                inputBuscar.focus();
            });
        }
    }
});
</script>

<?php include("includes/footer.php"); ?>