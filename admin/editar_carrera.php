<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Editar Carrera";
require_once '../funciones/functions.php';

// Verificar si se recibió ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: lista_carreras.php");
    exit();
}

$id = (int)$_GET['id'];
$carrera = obtenerCarreraPorId($id);

if (!$carrera) {
    header("Location: lista_carreras.php");
    exit();
}

// Procesar el formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_carrera'] ?? '');
    $codigo = trim($_POST['cod_carrera'] ?? '');
    $activa = isset($_POST['activa']) ? 1 : 0;
    
    if (!empty($nombre) && !empty($codigo)) {
        if (actualizarCarrera($id, $nombre, $codigo, $activa)) {
            $mensaje = '<div class="alert alert-success">Carrera actualizada correctamente</div>';
            // Actualizar los datos mostrados
            $carrera = obtenerCarreraPorId($id);
        } else {
            $mensaje = '<div class="alert alert-danger">Error al actualizar la carrera</div>';
        }
    } else {
        $mensaje = '<div class="alert alert-warning">Todos los campos son obligatorios</div>';
    }
}

include("includes/head.php");
?>

<div class="container mt-4">
    <h2>Editar Carrera</h2>
    
    <?php echo $mensaje; ?>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="nombre_carrera">Nombre de la Carrera:</label>
            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" 
                   value="<?= htmlspecialchars($carrera['nombre_carrera']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="cod_carrera">Código de la Carrera:</label>
            <input type="text" class="form-control" id="cod_carrera" name="cod_carrera" 
                   value="<?= htmlspecialchars($carrera['cod_carrera']) ?>" required>
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="activa" name="activa" 
                   <?= $carrera['activa'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="activa">Carrera activa</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="lista_carreras.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include("includes/footer.php"); ?>