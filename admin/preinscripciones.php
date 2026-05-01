<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('admin');
visita();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['aceptar_id'])) {
        $resultado = aceptarPreinscripcion((int)$_POST['aceptar_id'], $_SESSION['user']['id']);
        if ($resultado['success']) {
            $success_message = $resultado['message'];
        } else {
            $error_message = $resultado['message'];
        }
    }
    if (!empty($_POST['rechazar_id'])) {
        $motivo = trim($_POST['motivo'] ?? '');
        $resultado = rechazarPreinscripcion((int)$_POST['rechazar_id'], $_SESSION['user']['id'], $motivo);
        if ($resultado['success']) {
            $success_message = $resultado['message'];
        } else {
            $error_message = $resultado['message'];
        }
    }
}

$preinscripciones = obtenerPreinscripcionesPendientes();
$carreras = obtenerTodasLasCarreras();
$carreraMap = [];
foreach ($carreras as $carrera) {
    $carreraMap[$carrera['id']] = $carrera['nombre'];
}

include('includes/head.php');
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-file-signature me-2"></i> Preinscripciones Pendientes</h2>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (empty($preinscripciones)): ?>
        <div class="alert alert-info">No hay preinscripciones pendientes por revisar.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha Solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preinscripciones as $pre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pre['id']); ?></td>
                            <td><?php echo htmlspecialchars($pre['idusuario']); ?></td>
                            <td><?php echo htmlspecialchars($pre['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($carreraMap[$pre['carrera']] ?? 'No especificada'); ?></td>
                            <td><?php echo htmlspecialchars($pre['email']); ?></td>
                            <td><?php echo htmlspecialchars($pre['tlf']); ?></td>
                            <td><?php echo htmlspecialchars($pre['fecha_ingreso']); ?></td>
                            <td>
                                <form method="post" class="d-inline-block mb-1">
                                    <input type="hidden" name="aceptar_id" value="<?php echo (int)$pre['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aceptar esta preinscripción y crear el usuario?');">
                                        <i class="fas fa-check"></i> Aceptar
                                    </button>
                                </form>
                                <form method="post" class="d-inline-block">
                                    <input type="hidden" name="rechazar_id" value="<?php echo (int)$pre['id']; ?>">
                                    <input type="hidden" name="motivo" value="Preinscripción rechazada por el administrador.">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Rechazar esta preinscripción?');">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
