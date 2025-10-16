<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isset($_POST['accion'])) {
    die('Acción no especificada');
}

$accion = $_POST['accion'];
$admin_id = $_SESSION['user']['id'];

if ($accion === 'aprobar' || $accion === 'rechazar') {
    $nuevo_estado = $accion === 'aprobar' ? 'aprobada' : 'rechazada';
    
    // Verificar si es acción grupal
    if (isset($_POST['accion_grupo']) && $_POST['accion_grupo'] === 'true') {
        $docente_id = (int)$_POST['docente_id'];
        $materia_id = (int)$_POST['materia_id'];
        $periodo_id = (int)$_POST['periodo_id'];
        
        // Obtener todos los IDs de notas pendientes del grupo
        $query_ids = "SELECT id FROM notas_pendientes 
                     WHERE id_docente = ? 
                     AND id_materia = ? 
                     AND id_periodo = ? 
                     AND estado = 'pendiente'";
        $stmt = $db->prepare($query_ids);
        $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $notas_ids = [];
        while ($row = $result->fetch_assoc()) {
            $notas_ids[] = $row['id'];
        }
        
        if (empty($notas_ids)) {
            die('No hay notas pendientes en este grupo');
        }
        
        $ids_str = implode(',', $notas_ids);
    } else {
        // Acción individual o múltiple
        $notas_ids = $_POST['notas_ids'];
        if (empty($notas_ids)) {
            die('No se seleccionaron notas');
        }
        $ids_str = implode(',', array_map('intval', $notas_ids));
        
        // Obtener información del grupo para búsqueda de soporte
        $query_grupo = "SELECT DISTINCT id_docente, id_materia, id_periodo 
                       FROM notas_pendientes 
                       WHERE id IN ($ids_str) 
                       LIMIT 1";
        $result_grupo = $db->query($query_grupo);
        if ($result_grupo->num_rows > 0) {
            $grupo_info = $result_grupo->fetch_assoc();
            $docente_id = $grupo_info['id_docente'];
            $materia_id = $grupo_info['id_materia'];
            $periodo_id = $grupo_info['id_periodo'];
        }
    }
    
    // Obtener información de soporte para copiar a notas_definitivas - CORREGIDO
    $soporte_info = [];
    if ($accion === 'aprobar') {
        // Primero buscar en los registros seleccionados
        $query_soporte = "SELECT DISTINCT soporte, tipo_archivo 
                         FROM notas_pendientes 
                         WHERE id IN ($ids_str) 
                         AND soporte IS NOT NULL 
                         AND soporte != ''
                         LIMIT 1";
        $result_soporte = $db->query($query_soporte);
        if ($result_soporte->num_rows > 0) {
            $soporte_info = $result_soporte->fetch_assoc();
        } else {
            // Si no hay soporte en los registros seleccionados, buscar en todo el grupo
            if (isset($docente_id, $materia_id, $periodo_id)) {
                $query_soporte_grupo = "SELECT DISTINCT soporte, tipo_archivo 
                                      FROM notas_pendientes 
                                      WHERE id_docente = ? 
                                      AND id_materia = ? 
                                      AND id_periodo = ? 
                                      AND soporte IS NOT NULL 
                                      AND soporte != ''
                                      LIMIT 1";
                $stmt = $db->prepare($query_soporte_grupo);
                $stmt->bind_param("iii", $docente_id, $materia_id, $periodo_id);
                $stmt->execute();
                $result_soporte_grupo = $stmt->get_result();
                if ($result_soporte_grupo->num_rows > 0) {
                    $soporte_info = $result_soporte_grupo->fetch_assoc();
                }
            }
        }
        
        // Log para debugging
        error_log("Soporte encontrado: " . ($soporte_info ? $soporte_info['soporte'] : 'NULL'));
    }
    
    // Actualizar estado en notas_pendientes
    $update_query = "UPDATE notas_pendientes SET estado = '$nuevo_estado' 
                    WHERE id IN ($ids_str)";
    if (!$db->query($update_query)) {
        error_log("Error al actualizar notas_pendientes: " . $db->error);
        die('Error al actualizar el estado de las notas');
    }
    
    $affected_pendientes = $db->affected_rows;
    error_log("Notas pendientes actualizadas: $affected_pendientes registros");
    
    // Si se aprueban, copiar a notas_definitivas con soporte - CORREGIDO
    if ($accion === 'aprobar') {
        $soporte_valor = !empty($soporte_info['soporte']) ? "'" . $db->real_escape_string($soporte_info['soporte']) . "'" : "NULL";
        $tipo_archivo_valor = !empty($soporte_info['tipo_archivo']) ? "'" . $db->real_escape_string($soporte_info['tipo_archivo']) . "'" : "NULL";
        
        $insert_query = "INSERT INTO notas_definitivas 
                        (id_usuario, id_materia, id_periodo, id_docente, 
                         trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, 
                         soporte, tipo_archivo, fecha_registro, id_admin_aprobador)
                        SELECT id_usuario, id_materia, id_periodo, id_docente,
                               trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4,
                               $soporte_valor, $tipo_archivo_valor, NOW(), $admin_id
                        FROM notas_pendientes 
                        WHERE id IN ($ids_str)";
        
        if (!$db->query($insert_query)) {
            // Log del error para debugging
            error_log("Error al insertar en notas_definitivas: " . $db->error);
            error_log("Query: " . $insert_query);
            die('Error al guardar las notas definitivas: ' . $db->error);
        }
        
        // Verificar que se insertaron correctamente
        $affected_definitivas = $db->affected_rows;
        error_log("Notas definitivas insertadas: $affected_definitivas registros");
        error_log("Soporte usado: $soporte_valor, Tipo archivo: $tipo_archivo_valor");
        
        // Verificar en la base de datos que se insertó el soporte
        $check_query = "SELECT COUNT(*) as total, 
                               SUM(CASE WHEN soporte IS NOT NULL THEN 1 ELSE 0 END) as con_soporte
                        FROM notas_definitivas 
                        WHERE id_docente = $docente_id 
                        AND id_materia = $materia_id 
                        AND id_periodo = $periodo_id";
        $check_result = $db->query($check_query);
        if ($check_result) {
            $check_data = $check_result->fetch_assoc();
            error_log("Verificación BD - Total: {$check_data['total']}, Con soporte: {$check_data['con_soporte']}");
        }
    }
    
    // Aquí puedes agregar lógica para enviar mensajes al docente si es necesario
    if (isset($_POST['mensaje_rechazo']) || isset($_POST['mensaje_aprobacion'])) {
        // Lógica para enviar notificaciones al docente
        $mensaje = $_POST['mensaje_rechazo'] ?? $_POST['mensaje_aprobacion'] ?? '';
        // Aquí puedes guardar el mensaje en la base de datos o enviar notificación
        error_log("Mensaje para docente: " . substr($mensaje, 0, 100));
    }
    
    // Preparar mensaje de éxito
    $total_notas = count($notas_ids);
    $mensaje_exito = "$total_notas nota(s) $nuevo_estado correctamente";
    if ($accion === 'aprobar' && isset($soporte_info['soporte'])) {
        $mensaje_exito .= " (con soporte: {$soporte_info['soporte']})";
    }
    
    $_SESSION['msg'] = $mensaje_exito;
    
    echo 'success';
} else {
    die('Acción no válida');
}
?>