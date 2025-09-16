<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Horarios por Personal";
include('../funciones/functions.php');

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['asignar'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_tipo_horario = $_POST['id_tipo_horario'];
        
        $resultado = asignarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario);
        $mensaje = $resultado ? "Horario asignado correctamente." : "Error: Esta relación ya existe o hubo un problema.";
    } elseif (isset($_POST['eliminar'])) {
        $id_usuario = $_POST['id_usuario'];
        $id_tipo_horario = $_POST['id_tipo_horario'];
        
        $resultado = eliminarTipoHorarioUsuario($db, $id_usuario, $id_tipo_horario);
        $mensaje = $resultado ? "Relación eliminada correctamente." : "Error al eliminar la relación.";
    }
}

// Obtener usuarios
$query_usuarios = "SELECT id, nombre, username FROM users ORDER BY nombre";
$result_usuarios = $db->query($query_usuarios);
$usuarios = $result_usuarios ? $result_usuarios->fetch_all(MYSQLI_ASSOC) : [];

// Obtener tipos de horario
$tipos_horario = obtenerTiposHorario($db);

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
                    <h5>Asignar Horario a Usuario</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="id_usuario">Usuario:</label>
                            <select class="form-control" id="id_usuario" name="id_usuario" required>
                                <option value="">Seleccionar usuario</option>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <option value="<?php echo $usuario['id']; ?>">
                                        <?php echo htmlspecialchars($usuario['nombre'] . ' (' . $usuario['username'] . ')'); ?>
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
                                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="asignar">
                            Asignar Horario
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Lista de relaciones existentes -->
            <div class="card">
                <div class="card-header">
                    <h5>Relaciones Existentes</h5>
                </div>
                <div class="card-body">
                    <?php 
                    // Obtener todas las relaciones
                    $query_relaciones = "SELECT thp.*, u.nombre as usuario_nombre, u.username, th.nombre as horario_nombre
                                        FROM tipo_horario_personal thp
                                        JOIN users u ON thp.id_usuario = u.id
                                        JOIN tipos_horario th ON thp.id_tipo_horario = th.id
                                        ORDER BY u.nombre, th.nombre";
                    $result_relaciones = $db->query($query_relaciones);
                    $relaciones = $result_relaciones ? $result_relaciones->fetch_all(MYSQLI_ASSOC) : [];
                    ?>
                    
                    <?php if (count($relaciones) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Horario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($relaciones as $relacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($relacion['usuario_nombre'] . ' (' . $relacion['username'] . ')'); ?></td>
                                            <td><?php echo htmlspecialchars($relacion['horario_nombre']); ?></td>
                                            <td>
                                                <form method="POST" action="" style="display:inline;">
                                                    <input type="hidden" name="id_usuario" value="<?php echo $relacion['id_usuario']; ?>">
                                                    <input type="hidden" name="id_tipo_horario" value="<?php echo $relacion['id_tipo_horario']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger" name="eliminar" 
                                                            onclick="return confirm('¿Estás seguro de eliminar esta relación?');">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No hay relaciones entre usuarios y horarios registradas aún.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>