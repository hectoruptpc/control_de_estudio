<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

$titulopag = "Ver Mensaje";
include("includes/head.php");

if (!isset($_GET['id']) || !isset($_GET['tipo'])) {
    header('location: mensajeria.php');
    exit();
}

$mensaje_id = (int)$_GET['id'];
$tipo = $_GET['tipo'];
$user_id = $_SESSION['user']['id'];

// Función para obtener el tipo de usuario
function obtenerTipoUsuario($usuario) {
    if ($usuario['estudiante'] == 1) return 'Estudiante';
    if ($usuario['docente'] == 1) return 'Docente';
    if ($usuario['admin'] == 1) return 'Administrador';
    if ($usuario['super_user'] == 1) return 'Super Usuario';
    return 'Usuario';
}

// Obtener mensaje
function obtenerMensaje($mensaje_id, $user_id, $tipo) {
    global $db;
    
    if ($tipo === 'recibidos') {
        $query = "SELECT m.*, u.nombre as remitente_nombre, u.usuario as remitente_usuario,
                         u.email as remitente_email, u.estudiante, u.docente, u.admin,
                         u.idusuario as remitente_cedula
                  FROM mensajeria m
                  INNER JOIN users u ON m.id_usuario_remitente = u.id
                  WHERE m.id = ? AND m.id_usuario_destinatario = ? 
                  AND m.eliminado_destinatario = FALSE";
    } else {
        $query = "SELECT m.*, u.nombre as destinatario_nombre, u.usuario as destinatario_usuario,
                         u.email as destinatario_email, u.estudiante, u.docente, u.admin,
                         u.idusuario as destinatario_cedula
                  FROM mensajeria m
                  INNER JOIN users u ON m.id_usuario_destinatario = u.id
                  WHERE m.id = ? AND m.id_usuario_remitente = ? 
                  AND m.eliminado_remitente = FALSE";
    }
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$mensaje = obtenerMensaje($mensaje_id, $user_id, $tipo);

if (!$mensaje) {
    header('location: mensajeria.php');
    exit();
}

// Marcar como leído si es un mensaje recibido
if ($tipo === 'recibidos' && !$mensaje['leido']) {
    $query = "UPDATE mensajeria SET leido = TRUE 
              WHERE id = ? AND id_usuario_destinatario = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $mensaje_id, $user_id);
    $stmt->execute();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5><?= htmlspecialchars($mensaje['titulo']) ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?php if ($tipo === 'recibidos'): ?>
                                <p><strong>De:</strong> <?= htmlspecialchars($mensaje['remitente_nombre']) ?></p>
                                <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje['remitente_usuario']) ?></p>
                                <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje) ?></p>
                                <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje['remitente_cedula']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($mensaje['remitente_email']) ?></p>
                            <?php else: ?>
                                <p><strong>Para:</strong> <?= htmlspecialchars($mensaje['destinatario_nombre']) ?></p>
                                <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje['destinatario_usuario']) ?></p>
                                <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje) ?></p>
                                <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje['destinatario_cedula']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($mensaje['destinatario_email']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></p>
                            <?php if ($tipo === 'recibidos'): ?>
                                <p><strong>Estado:</strong> 
                                    <span class="badge badge-<?= $mensaje['leido'] ? 'success' : 'warning' ?>">
                                        <?= $mensaje['leido'] ? 'Leído' : 'No leído' ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mensaje-contenido">
                        <?= nl2br(htmlspecialchars($mensaje['mensaje'])) ?>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="mensajeria.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver a Mensajería
                        </a>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>