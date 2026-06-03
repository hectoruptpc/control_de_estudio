<?php
require_once('../funciones/functions.php');

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

$accion = $_POST['accion'] ?? '';
$materia_id = (int)($_POST['materia_id'] ?? 0);
$periodo_id = (int)($_POST['periodo_id'] ?? 0);
$mensaje_admin = $_POST['mensaje'] ?? '';
$admin_id = $_SESSION['user']['id'];
$admin_nombre = $_SESSION['user']['nombre'] ?? 'Administrador';

if (!$materia_id || !$periodo_id) {
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos: materia_id o periodo_id faltante']);
    exit;
}

if (empty($mensaje_admin)) {
    echo json_encode(['success' => false, 'message' => 'Debe ingresar un mensaje para el docente']);
    exit;
}

global $db;

// Obtener información de la materia y periodo para el mensaje
$info_query = "SELECT m.nombre_materia, pa.nombre_periodo 
               FROM materias m 
               CROSS JOIN periodos_academicos pa 
               WHERE m.id_materia = $materia_id AND pa.id_periodo = $periodo_id";
$info_result = $db->query($info_query);
$info = $info_result->fetch_assoc();
$nombre_materia = $info['nombre_materia'] ?? 'Materia';
$nombre_periodo = $info['nombre_periodo'] ?? 'Periodo';

// Acción grupal (todos los estudiantes del docente/materia/periodo)
if (isset($_POST['accion_grupo']) && $_POST['accion_grupo'] === 'true') {
    $docente_id = (int)($_POST['docente_id'] ?? 0);
    if (!$docente_id) {
        echo json_encode(['success' => false, 'message' => 'Docente no especificado']);
        exit;
    }
    
    $nuevo_estado = ($accion === 'aprobar') ? 'aprobada' : 'rechazada';
    $accion_texto = ($accion === 'aprobar') ? 'APROBADAS' : 'RECHAZADAS';
    $color = ($accion === 'aprobar') ? '✅' : '❌';
    
    // Obtener las notas a actualizar
    $query_select = "SELECT id, id_usuario FROM notas_trimestres 
                     WHERE id_docente = $docente_id 
                     AND id_materia = $materia_id 
                     AND id_periodo = $periodo_id
                     AND estado = 'en_revision'";
    $result_select = $db->query($query_select);
    
    if (!$result_select) {
        echo json_encode(['success' => false, 'message' => 'Error al consultar notas: ' . $db->error]);
        exit;
    }
    
    $notas_ids = [];
    $estudiantes_ids = [];
    while ($row = $result_select->fetch_assoc()) {
        $notas_ids[] = $row['id'];
        $estudiantes_ids[] = $row['id_usuario'];
    }
    
    if (empty($notas_ids)) {
        echo json_encode(['success' => false, 'message' => 'No hay notas en revisión para este grupo']);
        exit;
    }
    
    $ids_str = implode(',', $notas_ids);
    
    $update_query = "UPDATE notas_trimestres 
                     SET estado = '$nuevo_estado', 
                         id_admin_aprobador = $admin_id
                     WHERE id IN ($ids_str)";
    
    if ($db->query($update_query)) {
        $afectadas = $db->affected_rows;
        
        // ============================================
        // ENVIAR MENSAJE AL DOCENTE USANDO TU SISTEMA DE MENSAJERÍA
        // ============================================
        $titulo = "$color Notas $accion_texto - $nombre_materia";
        $contenido_mensaje = "Estimado docente,\n\n";
        $contenido_mensaje .= "Sus notas han sido $accion_texto por el administrador.\n\n";
        $contenido_mensaje .= "📚 Materia: $nombre_materia\n";
        $contenido_mensaje .= "📅 Periodo: $nombre_periodo\n";
        $contenido_mensaje .= "👨‍🏫 Docente: " . ($_SESSION['user']['nombre'] ?? 'Administrador') . "\n\n";
        $contenido_mensaje .= "📝 Total de notas afectadas: $afectadas\n\n";
        $contenido_mensaje .= "💬 Mensaje del administrador:\n";
        $contenido_mensaje .= "----------------------------------------\n";
        $contenido_mensaje .= $mensaje_admin . "\n";
        $contenido_mensaje .= "----------------------------------------\n\n";
        $contenido_mensaje .= "Por favor, revise el sistema para más detalles.\n\n";
        $contenido_mensaje .= "Atentamente,\n";
        $contenido_mensaje .= "$admin_nombre\n";
        $contenido_mensaje .= "Administración";
        
        $resultado_mensaje = enviarMensaje($admin_id, $docente_id, $titulo, $contenido_mensaje);
        
        registrarAccionAdmin($admin_id, $accion, $materia_id, $periodo_id, $notas_ids, $mensaje_admin, true);
        
        $mensaje_respuesta = "$afectadas nota(s) $nuevo_estado correctamente.";
        if ($resultado_mensaje && $resultado_mensaje['success']) {
            $mensaje_respuesta .= " Mensaje enviado al docente.";
        } else {
            $mensaje_respuesta .= " No se pudo enviar el mensaje al docente.";
        }
        
        echo json_encode(['success' => true, 'message' => $mensaje_respuesta]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al ejecutar acción grupal: ' . $db->error]);
    }
    exit;
}

// Acción individual o por selección
$notas_ids = $_POST['notas_ids'] ?? [];
if (empty($notas_ids)) {
    echo json_encode(['success' => false, 'message' => 'No se especificaron notas']);
    exit;
}

if (!is_array($notas_ids)) {
    $notas_ids = [$notas_ids];
}

// Obtener el docente_id de las notas (todas deberían tener el mismo)
$primera_nota = $notas_ids[0];
$query_docente = "SELECT id_docente, COUNT(*) as total FROM notas_trimestres WHERE id IN (" . implode(',', $notas_ids) . ") GROUP BY id_docente";
$result_docente = $db->query($query_docente);
if (!$result_docente) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener docente: ' . $db->error]);
    exit;
}
$docente_data = $result_docente->fetch_assoc();
$docente_id = $docente_data['id_docente'];
$total_notas = $docente_data['total'];

$nuevo_estado = ($accion === 'aprobar') ? 'aprobada' : 'rechazada';
$accion_texto = ($accion === 'aprobar') ? 'APROBADAS' : 'RECHAZADAS';
$color = ($accion === 'aprobar') ? '✅' : '❌';

$ids_str = implode(',', array_map('intval', $notas_ids));

$update_query = "UPDATE notas_trimestres 
                 SET estado = '$nuevo_estado', 
                     id_admin_aprobador = $admin_id
                 WHERE id IN ($ids_str) 
                 AND estado = 'en_revision'";

if ($db->query($update_query)) {
    $afectadas = $db->affected_rows;
    
    // ============================================
    // ENVIAR MENSAJE AL DOCENTE
    // ============================================
    $titulo = "$color Notas $accion_texto - $nombre_materia";
    $contenido_mensaje = "Estimado docente,\n\n";
    $contenido_mensaje .= "Se han $accion_texto algunas de sus notas.\n\n";
    $contenido_mensaje .= "📚 Materia: $nombre_materia\n";
    $contenido_mensaje .= "📅 Periodo: $nombre_periodo\n\n";
    $contenido_mensaje .= "📝 Notas afectadas: $afectadas de $total_notas\n\n";
    $contenido_mensaje .= "💬 Mensaje del administrador:\n";
    $contenido_mensaje .= "----------------------------------------\n";
    $contenido_mensaje .= $mensaje_admin . "\n";
    $contenido_mensaje .= "----------------------------------------\n\n";
    $contenido_mensaje .= "Por favor, revise el sistema para más detalles.\n\n";
    $contenido_mensaje .= "Atentamente,\n";
    $contenido_mensaje .= "$admin_nombre\n";
    $contenido_mensaje .= "Administración";
    
    $resultado_mensaje = enviarMensaje($admin_id, $docente_id, $titulo, $contenido_mensaje);
    
    registrarAccionAdmin($admin_id, $accion, $materia_id, $periodo_id, $notas_ids, $mensaje_admin);
    
    $mensaje_respuesta = "$afectadas nota(s) $nuevo_estado correctamente.";
    if ($resultado_mensaje && $resultado_mensaje['success']) {
        $mensaje_respuesta .= " Mensaje enviado al docente.";
    } else {
        $mensaje_respuesta .= " No se pudo enviar el mensaje al docente: " . ($resultado_mensaje['message'] ?? 'Error desconocido');
    }
    
    echo json_encode(['success' => true, 'message' => $mensaje_respuesta]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al procesar: ' . $db->error]);
}

function registrarAccionAdmin($admin_id, $accion, $materia_id, $periodo_id, $notas_ids, $mensaje, $es_grupal = false) {
    global $db;
    $tipo = $es_grupal ? 'GRUPAL' : 'INDIVIDUAL';
    $notas_str = is_array($notas_ids) ? implode(',', $notas_ids) : $notas_ids;
    
    $log_query = "INSERT INTO logs_administrativos 
                  (id_admin, accion, materia_id, periodo_id, notas_afectadas, mensaje, tipo_accion, fecha) 
                  VALUES 
                  ($admin_id, '$accion', $materia_id, $periodo_id, '$notas_str', 
                   '" . $db->real_escape_string($mensaje) . "', '$tipo', NOW())";
    $db->query($log_query);
}
?>