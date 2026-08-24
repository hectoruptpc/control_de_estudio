<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Duplicar Carrera (Crear Versión)";
require_once '../funciones/functions.php';

// Permisos
cargarPermisosUsuario();
if (!tienePermiso('gestionar_carrera')) {
    header('Location: lista_carreras.php');
    exit();
}

$mensaje = '';
$error = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: lista_carreras.php');
    exit();
}

$id_original = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = trim($_POST['fecha_vigencia'] ?? '');
    $copiar = isset($_POST['copiar_materias']) && $_POST['copiar_materias'] == '1';

    if (empty($fecha)) {
        $error = 'Debe indicar la fecha de vigencia para la nueva versión';
    } else {
        $resultado = duplicarCarrera($id_original, $fecha, $copiar);
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $error = $resultado['message'];
        }
    }
}

include('includes/head.php');
?>
<div class="container-fluid py-3">
    <h2>Duplicar Carrera para Nueva Fecha</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <a href="lista_carreras.php" class="btn btn-secondary">Volver a lista</a>
    <?php else: ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="duplicar_carrera.php?id=<?= $id_original ?>">
            <div class="form-group">
                <label>Fecha de Vigencia (completa):</label>
                <input type="date" name="fecha_vigencia" class="form-control" value="<?= date('Y-m-d') ?>" required>
                <small class="form-text text-muted">Esta será la fecha que mostrará el año de la versión.</small>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="copiar_materias" name="copiar_materias" value="1" checked>
                <label class="form-check-label" for="copiar_materias">Copiar materias asignadas a esta carrera</label>
            </div>

            <button class="btn btn-primary" type="submit">Crear Versión</button>
            <a href="lista_carreras.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
