<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

$titulopag = "Editar Sección";
require_once(__DIR__ . '/../../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('secciones');
visita();

$id_seccion = (int)$_GET['id'] ?? 0;
if (!$id_seccion) header("Location: gestion_seccion.php");

$carreras = obtenerTodasLasCarreras();
$datos_selects = obtenerDatosSelects($db);
$trayectos = $datos_selects['trayectos'];
$periodos = $datos_selects['periodos'];

$seccion = obtenerDatosSeccion($db, $id_seccion);
if (!$seccion) header("Location: gestion_seccion.php");

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'id_seccion' => $id_seccion,
        'codigo_seccion' => trim($_POST['codigo_seccion']),
        'id_carrera' => (int)$_POST['id_carrera'],
        'id_trayecto' => (int)$_POST['id_trayecto'],
        'id_periodo' => (int)$_POST['id_periodo'],
        'capacidad_maxima' => (int)$_POST['capacidad_maxima'],
        'turno' => trim($_POST['turno']),
        'inicia' => $_POST['inicia']
    ];
    $resultado = editarSeccion($db, $datos);
    if ($resultado['success']) { $_SESSION['success'] = $resultado['message']; header("Location: gestion_seccion.php"); exit();
    } else $error_message = $resultado['message'];
}

include(__DIR__ . '/../includes/head.php');
?>

<div class="container-fluid py-2">
    <div class="row mb-2"><div class="col-12 d-flex justify-content-between">
        <h2 class="h4 mb-0">Editar Sección</h2><a href="gestion_seccion.php" class="btn btn-secondary btn-sm">← Volver</a>
    </div></div>
    <?php if(!empty($error_message)) echo '<div class="alert alert-danger py-1">'.$error_message.'</div>'; ?>
    <div class="card shadow"><div class="card-header py-2"><h6 class="m-0">Datos de la Sección</h6></div>
    <div class="card-body py-2">
        <form method="POST">
            <div class="form-row">
                <div class="form-group col-md-2"><label class="small">Código</label><input type="text" class="form-control form-control-sm" name="codigo_seccion" value="<?= $seccion['codigo_seccion'] ?>" readonly></div>
                <div class="form-group col-md-3"><label class="small">Carrera</label>
                    <select class="form-control form-control-sm" name="id_carrera" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($carreras as $c): ?>
                            <option value="<?= $c['id_carrera'] ?>" <?= $seccion['id_carrera']==$c['id_carrera']?'selected':'' ?>><?= htmlspecialchars($c['nombre_carrera']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-2"><label class="small">Trayecto</label>
                    <select class="form-control form-control-sm" name="id_trayecto" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($trayectos as $t): ?>
                            <option value="<?= $t['id_trayecto'] ?>" <?= $seccion['id_trayecto']==$t['id_trayecto']?'selected':'' ?>><?= $t['numero_trayecto'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-2"><label class="small">Turno</label>
                    <select class="form-control form-control-sm" name="turno" required>
                        <option value="Diurno" <?= $seccion['turno']=='Diurno'?'selected':'' ?>>Diurno</option>
                        <option value="Nocturno" <?= $seccion['turno']=='Nocturno'?'selected':'' ?>>Nocturno</option>
                    </select>
                </div>
                <div class="form-group col-md-3"><label class="small">Período</label>
                    <select class="form-control form-control-sm" name="id_periodo" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($periodos as $p): ?>
                            <option value="<?= $p['id_periodo'] ?>" <?= $seccion['id_periodo']==$p['id_periodo']?'selected':'' ?>><?= htmlspecialchars($p['nombre_periodo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3"><label class="small">Capacidad</label><input type="number" class="form-control form-control-sm" name="capacidad_maxima" value="<?= $seccion['capacidad_maxima'] ?>" min="<?= MINIMO_ESTUDIANTES ?>" required></div>
                <div class="form-group col-md-3"><label class="small">Fecha Inicio</label><input type="datetime-local" class="form-control form-control-sm" name="inicia" value="<?= date('Y-m-d\TH:i', strtotime($seccion['inicia'])) ?>" required></div>
            </div>
            <button type="submit" name="editar_seccion" class="btn btn-primary btn-sm mt-2">Guardar</button>
            <a href="gestion_seccion.php" class="btn btn-secondary btn-sm mt-2">Cancelar</a>
        </form>
    </div></div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>