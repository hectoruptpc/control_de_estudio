<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Tipos de Horario";
include('../funciones/functions.php');

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $horas_academicas = $_POST['horas_academicas'];
        $horas_atendiendo = $_POST['horas_atendiendo'];
        
        $resultado = agregarTipoHorario($db, $nombre, $horas_academicas, $horas_atendiendo);
        $mensaje = $resultado ? "Tipo de horario agregado correctamente." : "Error al agregar el tipo de horario.";
        
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $horas_academicas = $_POST['horas_academicas'];
        $horas_atendiendo = $_POST['horas_atendiendo'];
        
        $resultado = actualizarTipoHorario($db, $id, $nombre, $horas_academicas, $horas_atendiendo);
        $mensaje = $resultado ? "Tipo de horario actualizado correctamente." : "Error al actualizar el tipo de horario.";
    }
}

// Eliminar registro
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $resultado = eliminarTipoHorario($db, $id);
    $mensaje = $resultado ? "Tipo de horario eliminado correctamente." : "Error al eliminar el tipo de horario.";
}

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
            <h1 class="mt-4">Gestión de Tipos de Horario</h1>
            
            <!-- Formulario para agregar/editar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><?php echo isset($_GET['editar']) ? 'Editar' : 'Agregar'; ?> Tipo de Horario</h5>
                </div>
                <div class="card-body">
                    <?php
                    $editando = false;
                    $id = $nombre = $horas_academicas = $horas_atendiendo = '';
                    
                    if (isset($_GET['editar'])) {
                        $editando = true;
                        $id_editar = $_GET['editar'];
                        $tipo = obtenerTipoHorarioPorId($db, $id_editar);
                        if ($tipo) {
                            $id = $tipo['id'];
                            $nombre = $tipo['nombre'];
                            $horas_academicas = $tipo['horas_academicas'];
                            $horas_atendiendo = $tipo['horas_atendiendo'];
                        }
                    }
                    ?>
                    <form method="POST" action="">
                        <?php if ($editando): ?>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="nombre">Nombre del horario:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?php echo htmlspecialchars($nombre); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="horas_academicas">Horas académicas:</label>
                            <input type="number" class="form-control" id="horas_academicas" name="horas_academicas" 
                                   value="<?php echo $horas_academicas; ?>" required min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="horas_atendiendo">Horas atendiendo:</label>
                            <input type="number" class="form-control" id="horas_atendiendo" name="horas_atendiendo" 
                                   value="<?php echo $horas_atendiendo; ?>" required min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" name="<?php echo $editando ? 'editar' : 'agregar'; ?>">
                            <?php echo $editando ? 'Actualizar' : 'Agregar'; ?>
                        </button>
                        
                        <?php if ($editando): ?>
                            <a href="tipos_horario.php" class="btn btn-secondary">Cancelar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            
            <!-- Tabla de tipos de horario -->
            <div class="card">
                <div class="card-header">
                    <h5>Tipos de Horario Existentes</h5>
                </div>
                <div class="card-body">
                    <?php if (count($tipos_horario) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Horas Académicas</th>
                                        <th>Horas Atendiendo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tipos_horario as $tipo): ?>
                                        <tr>
                                            <td><?php echo $tipo['id']; ?></td>
                                            <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                            <td><?php echo $tipo['horas_academicas']; ?></td>
                                            <td><?php echo $tipo['horas_atendiendo']; ?></td>
                                            <td>
                                                <a href="?editar=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                                <a href="?eliminar=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('¿Estás seguro de que deseas eliminar este tipo de horario?');">Eliminar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No hay tipos de horario registrados aún.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>