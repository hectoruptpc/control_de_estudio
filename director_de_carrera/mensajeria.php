<?php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

$titulopag = "Sistema de Mensajería";
include("includes/head.php");

// Obtener lista de usuarios para enviar mensajes con filtros
function obtenerUsuarios($filtro_tipo = '', $busqueda_cedula = '') {
    global $db;
    $current_user_id = $_SESSION['user']['id'];
    
    $query = "SELECT id, nombre, usuario, estudiante, docente, admin, idusuario 
              FROM users 
              WHERE id != ? AND status = 1";
    
    $params = array($current_user_id);
    $types = "i";
    
    // Aplicar filtro por tipo de usuario
    if (!empty($filtro_tipo)) {
        if ($filtro_tipo === 'estudiante') {
            $query .= " AND estudiante = 1";
        } elseif ($filtro_tipo === 'docente') {
            $query .= " AND docente = 1";
        } elseif ($filtro_tipo === 'admin') {
            $query .= " AND admin = 1";
        }
    }
    
    // Aplicar búsqueda por cédula
    if (!empty($busqueda_cedula)) {
        $query .= " AND idusuario LIKE ?";
        $params[] = "%$busqueda_cedula%";
        $types .= "s";
    }
    
    $query .= " ORDER BY nombre";
    
    $stmt = $db->prepare($query);
    
    // Bind parameters dinámicamente
    if (count($params) > 1) {
        $stmt->bind_param($types, ...$params);
    } else {
        $stmt->bind_param($types, $params[0]);
    }
    
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

// Obtener mensajes enviados
function obtenerMensajesEnviados($user_id) {
    global $db;
    
    $query = "SELECT m.*, u.nombre as destinatario_nombre, u.usuario as destinatario_usuario,
                     u.estudiante, u.docente, u.admin, u.idusuario as destinatario_cedula
              FROM mensajeria m
              INNER JOIN users u ON m.id_usuario_destinatario = u.id
              WHERE m.id_usuario_remitente = ? 
              AND m.eliminado_remitente = FALSE
              ORDER BY m.fecha_envio DESC";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Procesar filtros y búsqueda
$filtro_tipo = isset($_GET['filtro_tipo']) ? $_GET['filtro_tipo'] : '';
$busqueda_cedula = isset($_GET['busqueda_cedula']) ? trim($_GET['busqueda_cedula']) : '';

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_mensaje'])) {
    $remitente_id = $_SESSION['user']['id'];
    $destinatario_id = (int)$_POST['destinatario'];
    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);
    
    if (!empty($titulo) && !empty($mensaje) && $destinatario_id > 0) {
        $query = "INSERT INTO mensajeria (id_usuario_remitente, id_usuario_destinatario, titulo, mensaje)
                  VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iiss", $remitente_id, $destinatario_id, $titulo, $mensaje);
        
        if ($stmt->execute()) {
            $mensaje_exito = "Mensaje enviado correctamente";
        } else {
            $mensaje_error = "Error al enviar el mensaje";
        }
    } else {
        $mensaje_error = "Por favor complete todos los campos";
    }
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

$usuarios = obtenerUsuarios($filtro_tipo, $busqueda_cedula);
$mensajes_recibidos = obtenerMensajesRecibidos($_SESSION['user']['id']);
$mensajes_enviados = obtenerMensajesEnviados($_SESSION['user']['id']);
?>

<div class="container-fluid">
    <h2 class="my-4">Sistema de Mensajería</h2>
    
    <?php if (isset($mensaje_exito)): ?>
        <div class="alert alert-success"><?= $mensaje_exito ?></div>
    <?php endif; ?>
    
    <?php if (isset($mensaje_error)): ?>
        <div class="alert alert-danger"><?= $mensaje_error ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Panel de escritura de mensajes -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Nuevo Mensaje</h5>
                </div>
                <div class="card-body">
                    <!-- Formulario de filtros y búsqueda -->
                    <form method="GET" class="mb-3">
                        <div class="form-group">
                            <label for="filtro_tipo">Filtrar por tipo:</label>
                            <select class="form-control" id="filtro_tipo" name="filtro_tipo" onchange="this.form.submit()">
                                <option value="">Todos los usuarios</option>
                                <option value="estudiante" <?= $filtro_tipo === 'estudiante' ? 'selected' : '' ?>>Estudiantes</option>
                                <option value="docente" <?= $filtro_tipo === 'docente' ? 'selected' : '' ?>>Docentes</option>
                                <option value="admin" <?= $filtro_tipo === 'admin' ? 'selected' : '' ?>>Administradores</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="busqueda_cedula">Buscar por cédula:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="busqueda_cedula" name="busqueda_cedula" 
                                       value="<?= htmlspecialchars($busqueda_cedula) ?>" placeholder="Ej: V12345678">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="destinatario">Destinatario:</label>
                            <select class="form-control" id="destinatario" name="destinatario" required>
                                <option value="">Seleccionar usuario...</option>
                                <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                    <option value="<?= $usuario['id'] ?>">
                                        <?= htmlspecialchars($usuario['nombre']) ?> 
                                        (<?= htmlspecialchars($usuario['usuario']) ?>)
                                        - <?= obtenerTipoUsuario($usuario) ?>
                                        - Cédula: <?= htmlspecialchars($usuario['idusuario']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="titulo">Asunto:</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensaje">Mensaje:</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" name="enviar_mensaje" class="btn btn-success">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                        
                        <?php if (!empty($filtro_tipo) || !empty($busqueda_cedula)): ?>
                            <a href="mensajeria.php" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Limpiar filtros
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Panel de mensajes -->
        <div class="col-md-8">
            <ul class="nav nav-tabs" id="mensajesTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="recibidos-tab" data-toggle="tab" href="#recibidos" role="tab">
                        Mensajes Recibidos
                        <span class="badge badge-primary">
                            <?= $mensajes_recibidos->num_rows ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="enviados-tab" data-toggle="tab" href="#enviados" role="tab">
                        Mensajes Enviados
                        <span class="badge badge-info">
                            <?= $mensajes_enviados->num_rows ?>
                        </span>
                    </a>
                </li>
            </ul>
            
            <div class="tab-content" id="mensajesTabContent">
                <!-- Mensajes Recibidos -->
                <div class="tab-pane fade show active" id="recibidos" role="tabpanel">
                    <div class="card mt-3">
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
                                                <a href="ver_mensaje.php?id=<?= $mensaje['id'] ?>&tipo=recibidos" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Leer
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
                
                <!-- Mensajes Enviados -->
                <div class="tab-pane fade" id="enviados" role="tabpanel">
                    <div class="card mt-3">
                        <div class="card-body">
                            <?php if ($mensajes_enviados->num_rows > 0): ?>
                                <div class="list-group">
                                    <?php while ($mensaje = $mensajes_enviados->fetch_assoc()): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-1"><?= htmlspecialchars($mensaje['titulo']) ?></h6>
                                                <small><?= date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) ?></small>
                                            </div>
                                            <p class="mb-1">
                                                <strong>Para:</strong> 
                                                <?= htmlspecialchars($mensaje['destinatario_nombre']) ?>
                                                (<?= htmlspecialchars($mensaje['destinatario_usuario']) ?>)
                                                - <?= obtenerTipoUsuario($mensaje) ?>
                                                - Cédula: <?= htmlspecialchars($mensaje['destinatario_cedula']) ?>
                                            </p>
                                            <p class="mb-2 text-muted">
                                                <?= nl2br(htmlspecialchars(substr($mensaje['mensaje'], 0, 100))) ?>
                                                <?= strlen($mensaje['mensaje']) > 100 ? '...' : '' ?>
                                            </p>
                                            <div>
                                                <a href="ver_mensaje.php?id=<?= $mensaje['id'] ?>&tipo=enviados" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No has enviado mensajes.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>