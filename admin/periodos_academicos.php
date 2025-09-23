<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Periodos Académicos";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('periodos_academicos');


// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_periodo'])) {
        $creado = crearPeriodoAcademico($db, $_POST['nombre_periodo'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
        if ($creado) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Periodo creado correctamente'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al crear el periodo'];
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['editar_periodo'])) {
        $actualizado = actualizarPeriodoAcademico($db, $_POST['id_periodo'], $_POST['nombre_periodo'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
        if ($actualizado) {
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => 'Periodo actualizado correctamente'];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al actualizar el periodo'];
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['cambiar_estado'])) {
        $cambiado = cambiarEstadoPeriodo($db, $_POST['id_periodo'], $_POST['nuevo_estado']);
        
        if ($cambiado) {
            $mensaje = 'Estado del periodo cambiado correctamente';
            
            if ($_POST['nuevo_estado'] == 0) {
                // Desactivar período y sus secciones
                desactivarSeccionesDePeriodo($db, $_POST['id_periodo']);
                $mensaje .= '. Todas las secciones asociadas han sido desactivadas.';
            } else {
                // Activar período y actualizar estado de secciones
                actualizarEstadoSeccionesDePeriodo($db, $_POST['id_periodo']);
                $mensaje .= '. Las secciones asociadas se reactivarán si cumplen con los requisitos.';
            }
            
            $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => $mensaje];
        } else {
            $_SESSION['mensaje'] = ['tipo' => 'danger', 'texto' => 'Error al cambiar el estado del periodo'];
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestión de Periodos Académicos</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#crearPeriodoModal">
            <i class="fas fa-plus"></i> Crear Nuevo Periodo
        </button>
    </div>

    <?php if (isset($_SESSION['mensaje'])): ?>
    <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['mensaje']['texto'] ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['mensaje']); endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Listado de Periodos</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Periodo</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (obtenerPeriodosAcademicos($db) as $periodo): ?>
                        <tr>
                            <td><?= $periodo['id_periodo'] ?></td>
                            <td><?= htmlspecialchars($periodo['nombre_periodo']) ?></td>
                            <td><?= $periodo['fecha_inicio'] ?></td>
                            <td><?= $periodo['fecha_fin'] ?></td>
                            <td>
                                <span class="badge badge-<?= $periodo['activo'] ? 'success' : 'danger' ?>">
                                    <?= $periodo['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary editar-periodo" 
                                        data-id="<?= $periodo['id_periodo'] ?>"
                                        data-nombre="<?= htmlspecialchars($periodo['nombre_periodo']) ?>"
                                        data-inicio="<?= $periodo['fecha_inicio'] ?>"
                                        data-fin="<?= $periodo['fecha_fin'] ?>">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                
                                <?php if ($periodo['activo']): ?>
                                    <button class="btn btn-sm btn-danger desactivar-periodo" 
                                            data-id="<?= $periodo['id_periodo'] ?>">
                                        <i class="fas fa-times"></i> Desactivar
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success activar-periodo" 
                                            data-id="<?= $periodo['id_periodo'] ?>">
                                        <i class="fas fa-check"></i> Activar
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear nuevo periodo -->
<div class="modal fade" id="crearPeriodoModal" tabindex="-1" role="dialog" aria-labelledby="crearPeriodoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPeriodoModalLabel">Crear Nuevo Periodo Académico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_periodo">Nombre del Periodo</label>
                        <input type="text" class="form-control" id="nombre_periodo" name="nombre_periodo" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="crear_periodo" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar periodo -->
<div class="modal fade" id="editarPeriodoModal" tabindex="-1" role="dialog" aria-labelledby="editarPeriodoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPeriodoModalLabel">Editar Periodo Académico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <input type="hidden" id="id_periodo_edit" name="id_periodo">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre_periodo_edit">Nombre del Periodo</label>
                        <input type="text" class="form-control" id="nombre_periodo_edit" name="nombre_periodo" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio_edit">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio_edit" name="fecha_inicio" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin_edit">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin_edit" name="fecha_fin" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar_periodo" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmar cambio de estado -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" role="dialog" aria-labelledby="cambiarEstadoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarEstadoModalLabel">Cambiar Estado del Periodo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <input type="hidden" id="id_periodo_estado" name="id_periodo">
                <input type="hidden" id="nuevo_estado" name="nuevo_estado">
                <div class="modal-body">
                    <p id="mensaje_estado">¿Está seguro que desea cambiar el estado de este periodo académico?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="cambiar_estado" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
$(document).ready(function() {
    // Manejar clic en botón editar
    $('.editar-periodo').click(function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var inicio = $(this).data('inicio');
        var fin = $(this).data('fin');
        
        $('#id_periodo_edit').val(id);
        $('#nombre_periodo_edit').val(nombre);
        $('#fecha_inicio_edit').val(inicio);
        $('#fecha_fin_edit').val(fin);
        
        $('#editarPeriodoModal').modal('show');
    });
    
    // Manejar clic en botón desactivar
    $('.desactivar-periodo').click(function() {
        var id = $(this).data('id');
        $('#id_periodo_estado').val(id);
        $('#nuevo_estado').val(0);
        $('#mensaje_estado').html('¿Está seguro que desea DESACTIVAR este periodo académico?<br><br><strong>Todas las secciones asociadas también serán desactivadas.</strong>');
        $('#cambiarEstadoModal').modal('show');
    });
    
    // Manejar clic en botón activar
    $('.activar-periodo').click(function() {
        var id = $(this).data('id');
        $('#id_periodo_estado').val(id);
        $('#nuevo_estado').val(1);
        $('#mensaje_estado').html('¿Está seguro que desea ACTIVAR este periodo académico?<br><br><strong>Las secciones asociadas se reactivarán automáticamente si cumplen con los requisitos (mínimo de estudiantes).</strong>');
        $('#cambiarEstadoModal').modal('show');
    });
});
</script>