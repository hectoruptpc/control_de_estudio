<?php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

if (!isEstudiante()) {
    header('location: ../usuario/home.php');
    exit();
}

$titulopag = "Sistema de Mensajería - Estudiante";
include("includes/head.php");

// Obtener mensajes recibidos
function obtenerMensajesRecibidos($user_id) {
    global $db;
    
    $query = "SELECT m.*, u.nombre as remitente_nombre, u.usuario as remitente_usuario,
                     u.estudiante, u.docente, u.admin, u.idusuario as remitente_cedula
              FROM mensajeria m
              INNER JOIN users u ON m.id_usuario_remitente = u.id
              WHERE m.id_usuario_destinatario = ? 
              AND m.eliminado_destinatario = FALSE
              ORDER BY m.fecha_envio DESC";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Función para obtener el tipo de usuario basado en los campos booleanos
function obtenerTipoUsuario($usuario) {
    if ($usuario['estudiante'] == 1) return 'Estudiante';
    if ($usuario['docente'] == 1) return 'Docente';
    if ($usuario['admin'] == 1) return 'Administrador';
    if ($usuario['super_user'] == 1) return 'Super Usuario';
    return 'Usuario';
}

// Marcar como leído
if (isset($_GET['marcar_leido'])) {
    $mensaje_id = (int)$_GET['marcar_leido'];
    $user_id = $_SESSION['user']['id'];
    
    $query = "UPDATE mensajeria SET leido = TRUE 
              WHERE id = ? AND id_usuario_destinatario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    $stmt->execute();
}

$mensajes_recibidos = obtenerMensajesRecibidos($_SESSION['user']['id']);
?>

<div class="container-fluid">
    <h2 class="my-4">Sistema de Mensajería - Estudiante</h2>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        <strong>Modo de solo lectura:</strong> Como estudiante, solo puedes leer los mensajes que recibes.
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Mensajes Recibidos</h5>
                </div>
                <div class="card-body">
                    <?php if ($mensajes_recibidos->num_rows > 0): ?>
                        <div class="list-group">
                            <?php while ($mensaje = $mensajes_recibidos->fetch_assoc()): ?>
                                <div class="list-group-item <?= !$mensaje['leido'] ? 'list-group-item-warning' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1">
                                            <?php if (!$mensaje['leido']): ?>
                                                <span class="badge badge-danger">Nuevo</span>
                                            <?php endif; ?>
                                            <?= htmlspecialchars($mensaje['titulo']) ?>
                                        </h6>
                                        <small><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></small>
                                    </div>
                                    <p class="mb-1">
                                        <strong>De:</strong> 
                                        <?= htmlspecialchars($mensaje['remitente_nombre']) ?>
                                        (<?= htmlspecialchars($mensaje['remitente_usuario']) ?>)
                                        - <?= obtenerTipoUsuario($mensaje) ?>
                                        - Cédula: <?= htmlspecialchars($mensaje['remitente_cedula']) ?>
                                    </p>
                                    <p class="mb-2 text-muted">
                                        <?= nl2br(htmlspecialchars(substr($mensaje['mensaje'], 0, 100))) ?>
                                        <?= strlen($mensaje['mensaje']) > 100 ? '...' : '' ?>
                                    </p>
                                    <div>
                                        <a href="ver_mensaje_estudiante.php?id=<?= $mensaje['id'] ?>" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Leer Mensaje
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No tienes mensajes recibidos.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>