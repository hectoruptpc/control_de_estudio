<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Asignaturas";
include('../funciones/functions.php');

// Verificar permisos y autenticación si es necesario
// check_auth();

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['guardar_materia'])) {
        $data = [
            'cod_materia' => $_POST['cod_materia'],
            'nombre_materia' => $_POST['nombre_materia'],
            'pnf_ptf' => strtoupper($_POST['pnf_ptf']),
            'duracion_periodo' => $_POST['duracion_periodo'], // Ahora recibe directamente el número
            'creditos' => $_POST['creditos'],
            'activa' => isset($_POST['activa']) ? 1 : 0,
            'horas_teoricas' => $_POST['horas_teoricas'],
            'horas_practicas' => $_POST['horas_practicas']
        ];
        
        if (!empty($_POST['id_materia'])) {
            // Actualizar materia existente
            $id = $_POST['id_materia'];
            if (actualizarMateria($db, $id, $data)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Materia actualizada correctamente'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al actualizar la materia'];
            }
        } else {
            // Crear nueva materia
            if (crearMateria($db, $data)) {
                $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Materia creada correctamente'];
            } else {
                $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al crear la materia'];
            }
        }
        
        header("Location: materia.php");
        exit();
    }
    
    if (isset($_POST['deshabilitar_materia'])) {
        $id = $_POST['id_materia'];
        if (toggleMateria($db, $id)) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Estado de la materia cambiado correctamente'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al cambiar el estado de la materia'];
        }
        header("Location: materia.php");
        exit();
    }
}

// Obtener lista de materias
$materias = obtenerMaterias($db);

include("includes/head.php");
?>

<div class="container-fluid">
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?> alert-dismissible fade show mt-3">
            <?= $_SESSION['mensaje']['texto'] ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Gestión de Asignaturas</h1>
            
            <!-- Formulario de Materias -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Agregar Nueva Materia</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="materia.php">
                        <input type="hidden" name="id_materia" value="">
                        
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="cod_materia">Código de Materia</label>
                                <input type="text" class="form-control" id="cod_materia" name="cod_materia" required>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="nombre_materia">Nombre de Materia</label>
                                <input type="text" class="form-control" id="nombre_materia" name="nombre_materia" required>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label for="pnf_ptf">PNF/PTF</label>
                                <select class="form-control" id="pnf_ptf" name="pnf_ptf" required>
                                    <option value="">Seleccione...</option>
                                    <option value="PNF">PNF</option>
                                    <option value="PTF">PTF</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label for="creditos">Créditos</label>
                                <input type="number" class="form-control" id="creditos" name="creditos" min="1" value="1" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="activa">Estado</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input" id="activa" name="activa" checked>
                                    <label class="custom-control-label" for="activa">Activa</label>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="horas_teoricas">Horas Teóricas</label>
                                <input type="number" class="form-control" id="horas_teoricas" name="horas_teoricas" min="0" value="0" required>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="horas_practicas">Horas Prácticas</label>
                                <input type="number" class="form-control" id="horas_practicas" name="horas_practicas" min="0" value="0" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="duracion_periodo">Duración en Periodos</label>
                                <select class="form-control" id="duracion_periodo" name="duracion_periodo" required>
                                    <option value="1">1 periodo</option>
                                    <option value="2">2 periodos</option>
                                    <option value="3">3 periodos</option>
                                    <option value="4">4 periodos</option>
                                    <option value="5">5 periodos</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
        <label for="trayecto">Trayecto</label>
        <select class="form-control" id="trayecto" name="trayecto" required>
        <option value="0">Trayecto 0</option>
        <option value="1">Trayecto 1</option>
            <option value="2">Trayecto 2</option>
            <option value="3">Trayecto 3</option>
            <option value="4">Trayecto 4</option>
            <option value="5">Trayecto 5</option>
        </select>
    </div>






                        </div>
                        
                        <button type="submit" name="guardar_materia" class="btn btn-primary">
                            Guardar Materia
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Lista de Materias -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Listado de Asignaturas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo/Duración</th>
                                    <th>Créditos</th>
                                    <th>Horas T/P</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias as $materia): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($materia['cod_materia']) ?></td>
                                        <td><?= htmlspecialchars($materia['nombre_materia']) ?></td>
                                        <td>
                                            <?= isset($materia['pnf_ptf']) ? htmlspecialchars((string)$materia['pnf_ptf']) : 'No definido' ?>
                                            <br>
                                            <small class="text-muted">(<?= isset($materia['duracion_periodo']) ? 
                                                htmlspecialchars($materia['duracion_periodo']) . ' periodos' : 
                                                'No definido' ?>)</small>
                                        </td>
                                        <td><?= $materia['creditos'] ?></td>
                                        <td><?= $materia['horas_teoricas'] ?> / <?= $materia['horas_practicas'] ?></td>
                                        <td>
                                            <span class="badge badge-<?= $materia['activa'] ? 'success' : 'danger' ?>">
                                                <?= $materia['activa'] ? 'Activa' : 'Inactiva' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-toggle="modal" data-target="#modalEditar" 
                                                    data-id="<?= $materia['id_materia'] ?>" 
                                                    data-codigo="<?= htmlspecialchars($materia['cod_materia']) ?>" 
                                                    data-nombre="<?= htmlspecialchars($materia['nombre_materia']) ?>" 
                                                    data-pnf="<?= $materia['pnf_ptf'] ?>" 
                                                    data-creditos="<?= $materia['creditos'] ?>" 
                                                    data-activa="<?= $materia['activa'] ? '1' : '0' ?>" 
                                                    data-teoricas="<?= $materia['horas_teoricas'] ?>" 
                                                    data-practicas="<?= $materia['horas_practicas'] ?>" 
                                                    data-duracion="<?= isset($materia['duracion_periodo']) ? $materia['duracion_periodo'] : '1' ?>">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            
                                            <button type="button" class="btn btn-sm btn-<?= $materia['activa'] ? 'danger' : 'success' ?>" 
                                                    data-toggle="modal" data-target="#modalDeshabilitar" 
                                                    data-id="<?= $materia['id_materia'] ?>" 
                                                    data-nombre="<?= htmlspecialchars($materia['nombre_materia']) ?>" 
                                                    data-estado="<?= $materia['activa'] ? 'activa' : 'inactiva' ?>">
                                                <i class="fas fa-<?= $materia['activa'] ? 'times' : 'check' ?>"></i> 
                                                <?= $materia['activa'] ? 'Deshabilitar' : 'Habilitar' ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Materia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="materia.php">
                <div class="modal-body">
                    <input type="hidden" name="id_materia" id="edit-id-materia">
                    
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="edit-cod_materia">Código de Materia</label>
                            <input type="text" class="form-control" id="edit-cod_materia" name="cod_materia" required>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="edit-nombre_materia">Nombre de Materia</label>
                            <input type="text" class="form-control" id="edit-nombre_materia" name="nombre_materia" required>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label for="edit-pnf_ptf">PNF/PTF</label>
                            <select class="form-control" id="edit-pnf_ptf" name="pnf_ptf" required>
                                <option value="">Seleccione...</option>
                                <option value="PNF">PNF</option>
                                <option value="PTF">PTF</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label for="edit-creditos">Créditos</label>
                            <input type="number" class="form-control" id="edit-creditos" name="creditos" min="1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="edit-activa">Estado</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="edit-activa" name="activa">
                                <label class="custom-control-label" for="edit-activa">Activa</label>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="edit-horas_teoricas">Horas Teóricas</label>
                            <input type="number" class="form-control" id="edit-horas_teoricas" name="horas_teoricas" min="0" required>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="edit-horas_practicas">Horas Prácticas</label>
                            <input type="number" class="form-control" id="edit-horas_practicas" name="horas_practicas" min="0" required>
                        </div>

                        

                        <div class="form-group col-md-3">
                            <label for="edit-duracion_periodo">Duración en Periodos</label>
                            <select class="form-control" id="edit-duracion_periodo" name="duracion_periodo" required>
                                <option value="1">1 periodo</option>
                                <option value="2">2 periodos</option>
                                <option value="3">3 periodos</option>
                                <option value="4">4 periodos</option>
                                <option value="5">5 periodos</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
    <label for="edit-trayecto">Trayecto</label>
    <select class="form-control" id="edit-trayecto" name="trayecto" required>
    <option value="0">Trayecto 0</option>
    <option value="1">Trayecto 1</option>
        <option value="2">Trayecto 2</option>
        <option value="3">Trayecto 3</option>
        <option value="4">Trayecto 4</option>
        <option value="5">Trayecto 5</option>
    </select>
</div>


                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar_materia" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Deshabilitar/Habilitar -->
<div class="modal fade" id="modalDeshabilitar" tabindex="-1" role="dialog" aria-labelledby="modalDeshabilitarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeshabilitarLabel">Confirmar Cambio de Estado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modal-mensaje">¿Está seguro que desea cambiar el estado de esta materia?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="materia.php" id="formDeshabilitar">
                    <input type="hidden" name="id_materia" id="modal-id-materia">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="deshabilitar_materia" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Script para el modal de edición
    $('#modalEditar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var codigo = button.data('codigo');
        var nombre = button.data('nombre');
        var pnf = button.data('pnf');
        var creditos = button.data('creditos');
        var activa = button.data('activa');
        var teoricas = button.data('teoricas');
        var practicas = button.data('practicas');
        var duracion = button.data('duracion');
        var trayecto = button.data('trayecto');
        
        var modal = $(this);
        modal.find('#edit-id-materia').val(id);
        modal.find('#edit-cod_materia').val(codigo);
        modal.find('#edit-nombre_materia').val(nombre);
        modal.find('#edit-pnf_ptf').val(pnf);
        modal.find('#edit-creditos').val(creditos);
        modal.find('#edit-horas_teoricas').val(teoricas);
        modal.find('#edit-horas_practicas').val(practicas);
        modal.find('#edit-duracion_periodo').val(duracion);
        modal.find('#edit-trayecto').val(trayecto || '1');
        
        if (activa == '1') {
            modal.find('#edit-activa').prop('checked', true);
        } else {
            modal.find('#edit-activa').prop('checked', false);
        }
    });

    // Script para el modal de deshabilitar
    $('#modalDeshabilitar').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var estado = button.data('estado');
        
        var modal = $(this);
        modal.find('#modal-mensaje').text('¿Está seguro que desea ' + (estado === 'activa' ? 'deshabilitar' : 'habilitar') + 
                                   ' la materia "' + nombre + '"?');
        modal.find('#modal-id-materia').val(id);
    });
});
</script>

<?php include("includes/footer.php"); ?>