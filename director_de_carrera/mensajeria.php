<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isUser()) {
    $_SESSION['msg'] = "Debes iniciar sesión como Director de Carrera para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Sistema de Mensajería";
include("includes/head.php");

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
        if (enviarMensaje($remitente_id, $destinatario_id, $titulo, $mensaje)) {
            $mensaje_exito = "Mensaje enviado correctamente";
        } else {
            $mensaje_error = "Error al enviar el mensaje";
        }
    } else {
        $mensaje_error = "Por favor complete todos los campos";
    }
}

// Marcar como leído (ahora se hará vía AJAX)
if (isset($_GET['marcar_leido'])) {
    $mensaje_id = (int)$_GET['marcar_leido'];
    $user_id = $_SESSION['user']['id'];
    
    marcarMensajeLeido($mensaje_id, $user_id);
}

// Obtener mensaje para modal si se solicita
$mensaje_modal = null;
if (isset($_GET['ver_mensaje'])) {
    $mensaje_id = (int)$_GET['ver_mensaje'];
    $tipo = $_GET['tipo'];
    $user_id = $_SESSION['user']['id'];
    
    $mensaje_modal = obtenerMensaje($mensaje_id, $user_id, $tipo);
    
    // Si es un mensaje recibido y no leído, marcarlo como leído
    if ($tipo === 'recibidos' && $mensaje_modal && !$mensaje_modal['leido']) {
        marcarMensajeLeido($mensaje_id, $user_id);
        $mensaje_modal['leido'] = true;
    }
}

// Obtener datos usando las funciones existentes
$usuarios = obtenerUsuariosMensajeria($filtro_tipo, $busqueda_cedula);
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
                                                <button type="button" class="btn btn-info btn-sm ver-mensaje-btn" 
                                                        data-id="<?= $mensaje['id'] ?>" 
                                                        data-tipo="recibidos">
                                                    <i class="fas fa-eye"></i> Leer
                                                </button>
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
                                                <button type="button" class="btn btn-info btn-sm ver-mensaje-btn" 
                                                        data-id="<?= $mensaje['id'] ?>" 
                                                        data-tipo="enviados">
                                                    <i class="fas fa-eye"></i> Ver
                                                </button>
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

<!-- Modal para ver mensaje -->
<div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="modalMensajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalMensajeLabel">Cargando mensaje...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalMensajeBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar clic en botones de ver mensaje
    $('.ver-mensaje-btn').click(function() {
        var mensajeId = $(this).data('id');
        var tipo = $(this).data('tipo');
        
        // Cargar el mensaje en el modal
        $.ajax({
            url: 'mensajeria.php',
            type: 'GET',
            data: {
                ver_mensaje: mensajeId,
                tipo: tipo
            },
            success: function(response) {
                // Extraer solo el contenido del modal desde la respuesta
                var tempDiv = $('<div>').html(response);
                var mensajeData = tempDiv.find('#mensajeModalContent').html();
                
                if (mensajeData) {
                    $('#modalMensajeBody').html(mensajeData);
                    $('#modalMensajeLabel').text(tempDiv.find('#modalTitulo').text());
                } else {
                    $('#modalMensajeBody').html('<p class="text-danger">Error al cargar el mensaje.</p>');
                }
                
                $('#modalMensaje').modal('show');
            },
            error: function() {
                $('#modalMensajeBody').html('<p class="text-danger">Error al cargar el mensaje.</p>');
                $('#modalMensaje').modal('show');
            }
        });
    });
    
    <?php if ($mensaje_modal): ?>
    // Mostrar modal automáticamente si se cargó un mensaje
    $(window).on('load', function() {
        // Preparar contenido del modal
        $('#modalMensajeLabel').text("<?= htmlspecialchars($mensaje_modal['titulo']) ?>");
        
        var contenidoModal = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <?php if ($tipo === 'recibidos'): ?>
                        <p><strong>De:</strong> <?= htmlspecialchars($mensaje_modal['remitente_nombre']) ?></p>
                        <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['remitente_usuario']) ?></p>
                        <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                        <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['remitente_cedula']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['remitente_email']) ?></p>
                    <?php else: ?>
                        <p><strong>Para:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_nombre']) ?></p>
                        <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_usuario']) ?></p>
                        <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                        <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_cedula']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_email']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje_modal['fecha_envio'])) ?></p>
                    <?php if ($tipo === 'recibidos'): ?>
                        <p><strong>Estado:</strong> 
                            <span class="badge badge-<?= $mensaje_modal['leido'] ? 'success' : 'warning' ?>">
                                <?= $mensaje_modal['leido'] ? 'Leído' : 'No leído' ?>
                            </span>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <hr>
            
            <div class="mensaje-contenido">
                <?= nl2br(htmlspecialchars($mensaje_modal['mensaje'])) ?>
            </div>
        `;
        
        $('#modalMensajeBody').html(contenidoModal);
        $('#modalMensaje').modal('show');
    });
    <?php endif; ?>
});
</script>

<?php 
// Si se está cargando un mensaje para el modal, mostrar el contenido oculto para AJAX
if ($mensaje_modal): ?>
    <div id="mensajeModalContent" style="display: none;">
        <div class="row mb-3">
            <div class="col-md-6">
                <?php if ($tipo === 'recibidos'): ?>
                    <p><strong>De:</strong> <?= htmlspecialchars($mensaje_modal['remitente_nombre']) ?></p>
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['remitente_usuario']) ?></p>
                    <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                    <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['remitente_cedula']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['remitente_email']) ?></p>
                <?php else: ?>
                    <p><strong>Para:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_nombre']) ?></p>
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_usuario']) ?></p>
                    <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                    <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_cedula']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['destinatario_email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-right">
                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje_modal['fecha_envio'])) ?></p>
                <?php if ($tipo === 'recibidos'): ?>
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-<?= $mensaje_modal['leido'] ? 'success' : 'warning' ?>">
                            <?= $mensaje_modal['leido'] ? 'Leído' : 'No leído' ?>
                        </span>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <hr>
        
        <div class="mensaje-contenido">
            <?= nl2br(htmlspecialchars($mensaje_modal['mensaje'])) ?>
        </div>
    </div>
    <div id="modalTitulo" style="display: none;"><?= htmlspecialchars($mensaje_modal['titulo']) ?></div>
<?php endif; ?>

<?php include("includes/footer.php"); ?>