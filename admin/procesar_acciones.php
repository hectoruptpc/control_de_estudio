<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isAdmin()) {
    die('No autorizado');
}

// Función para enviar mensaje al docente
function enviarMensajeRechazo($docente_id, $contenido_mensaje) {
    global $db;
    
    $admin_id = $_SESSION['user']['id'];
    $titulo = "Notas Rechazadas";
    
    $query = "INSERT INTO mensajeria 
              (id_usuario_remitente, id_usuario_destinatario, titulo, mensaje, fecha_envio, leido) 
              VALUES (?, ?, ?, ?, NOW(), 0)";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iiss", $admin_id, $docente_id, $titulo, $contenido_mensaje);
    
    return $stmt->execute();
}

if (isset($_POST['accion']) && ($_POST['accion'] === 'aprobar' || $_POST['accion'] === 'rechazar')) {
    $accion = $_POST['accion'];
    $admin_id = $_SESSION['user']['id'];
    $nuevo_estado = $accion === 'aprobar' ? 'aprobada' : 'rechazada';
    
    // Obtener el contenido del mensaje si se está rechazando
    $contenido_mensaje = '';
    if ($accion === 'rechazar' && isset($_POST['mensaje_rechazo'])) {
        $contenido_mensaje = trim($_POST['mensaje_rechazo']);
    }
    
    $db->begin_transaction();
    
    try {
        if (isset($_POST['accion_grupo']) && $_POST['accion_grupo'] === 'true') {
            // Acción para todo el grupo
            $docente_id = (int)$_POST['docente_id'];
            $materia_id = (int)$_POST['materia_id'];
            $periodo_id = (int)$_POST['periodo_id'];
            
            // Obtener información del docente para el mensaje
            $docente_info_query = "SELECT nombre FROM users WHERE id = $docente_id";
            $docente_info = $db->query($docente_info_query)->fetch_assoc();
            $docente_nombre = $docente_info['nombre'];
            
            $update_query = "UPDATE notas_pendientes SET estado = '$nuevo_estado' 
                            WHERE id_docente = $docente_id 
                            AND id_materia = $materia_id 
                            AND id_periodo = $periodo_id
                            AND estado = 'pendiente'";
            $db->query($update_query);
            
            if ($accion === 'aprobar') {
                $insert_query = "INSERT INTO notas_definitivas 
                                (id_usuario, id_materia, id_periodo, id_docente, 
                                 trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, 
                                 fecha_registro, id_admin_aprobador)
                                SELECT id_usuario, id_materia, id_periodo, id_docente,
                                       trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4,
                                       NOW(), $admin_id
                                FROM notas_pendientes 
                                WHERE id_docente = $docente_id 
                                AND id_materia = $materia_id 
                                AND id_periodo = $periodo_id";
                $db->query($insert_query);
            } else {
                // Enviar mensaje de rechazo al docente
                if (!empty($contenido_mensaje)) {
                    enviarMensajeRechazo($docente_id, $contenido_mensaje);
                }
            }
            
        } else {
            // Acción individual
            $notas_ids = $_POST['notas_ids'];
            $ids_str = implode(',', array_map('intval', $notas_ids));
            
            // Obtener información de los docentes afectados
            $docentes_query = "SELECT DISTINCT id_docente FROM notas_pendientes WHERE id IN ($ids_str)";
            $docentes_result = $db->query($docentes_query);
            
            $update_query = "UPDATE notas_pendientes SET estado = '$nuevo_estado' 
                            WHERE id IN ($ids_str)";
            $db->query($update_query);
            
            if ($accion === 'aprobar') {
                $insert_query = "INSERT INTO notas_definitivas 
                                (id_usuario, id_materia, id_periodo, id_docente, 
                                 trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, 
                                 fecha_registro, id_admin_aprobador)
                                SELECT id_usuario, id_materia, id_periodo, id_docente,
                                       trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4,
                                       NOW(), $admin_id
                                FROM notas_pendientes 
                                WHERE id IN ($ids_str)";
                $db->query($insert_query);
            } else {
                // Enviar mensajes de rechazo a cada docente afectado
                if (!empty($contenido_mensaje)) {
                    while ($docente = $docentes_result->fetch_assoc()) {
                        enviarMensajeRechazo($docente['id_docente'], $contenido_mensaje);
                    }
                }
            }
        }
        
        $db->commit();
        echo 'success';
        
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo 'error: ' . $e->getMessage();
    }
}