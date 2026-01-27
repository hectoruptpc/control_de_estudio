<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Sistema de Mensajería - Estudiante";
include("includes/head.php");

// Marcar como leído (ahora se hará vía AJAX o al cargar el modal)
if (isset($_GET['marcar_leido'])) {
    $mensaje_id = (int)$_GET['marcar_leido'];
    $user_id = $_SESSION['user']['id'];
    marcarMensajeLeido($mensaje_id, $user_id);
}

// Obtener mensaje para modal si se solicita
$mensaje_modal = null;
if (isset($_GET['ver_mensaje'])) {
    $mensaje_id = (int)$_GET['ver_mensaje'];
    $user_id = $_SESSION['user']['id'];
    
    $mensaje_modal = obtenerMensaje($mensaje_id, $user_id, 'recibidos');
    
    // Si es un mensaje no leído, marcarlo como leído
    if ($mensaje_modal && !$mensaje_modal['leido']) {
        marcarMensajeLeido($mensaje_id, $user_id);
        $mensaje_modal['leido'] = true;
    }
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
                                        <button type="button" class="btn btn-info btn-sm ver-mensaje-btn" 
                                                data-id="<?= $mensaje['id'] ?>">
                                            <i class="fas fa-eye"></i> Leer Mensaje
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
        
        // Cargar el mensaje en el modal
        $.ajax({
            url: 'mensajeria_estudiantes.php',
            type: 'GET',
            data: {
                ver_mensaje: mensajeId
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
                    <p><strong>De:</strong> <?= htmlspecialchars($mensaje_modal['remitente_nombre']) ?></p>
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['remitente_usuario']) ?></p>
                    <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                    <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['remitente_cedula']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['remitente_email']) ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje_modal['fecha_envio'])) ?></p>
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-<?= $mensaje_modal['leido'] ? 'success' : 'warning' ?>">
                            <?= $mensaje_modal['leido'] ? 'Leído' : 'No leído' ?>
                        </span>
                    </p>
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
                <p><strong>De:</strong> <?= htmlspecialchars($mensaje_modal['remitente_nombre']) ?></p>
                <p><strong>Usuario:</strong> <?= htmlspecialchars($mensaje_modal['remitente_usuario']) ?></p>
                <p><strong>Tipo:</strong> <?= obtenerTipoUsuario($mensaje_modal) ?></p>
                <p><strong>Cédula:</strong> <?= htmlspecialchars($mensaje_modal['remitente_cedula']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($mensaje_modal['remitente_email']) ?></p>
            </div>
            <div class="col-md-6 text-right">
                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje_modal['fecha_envio'])) ?></p>
                <p><strong>Estado:</strong> 
                    <span class="badge badge-<?= $mensaje_modal['leido'] ? 'success' : 'warning' ?>">
                        <?= $mensaje_modal['leido'] ? 'Leído' : 'No leído' ?>
                    </span>
                </p>
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