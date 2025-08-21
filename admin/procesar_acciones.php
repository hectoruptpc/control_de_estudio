<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

if (!isAdmin()) {
    die('No autorizado');
}

if (isset($_POST['accion']) && ($_POST['accion'] === 'aprobar' || $_POST['accion'] === 'rechazar')) {
    $accion = $_POST['accion'];
    $admin_id = $_SESSION['user']['id'];
    $nuevo_estado = $accion === 'aprobar' ? 'aprobada' : 'rechazada';
    
    $db->begin_transaction();
    
    try {
        if (isset($_POST['accion_grupo']) && $_POST['accion_grupo'] === 'true') {
            // Acción para todo el grupo
            $docente_id = (int)$_POST['docente_id'];
            $materia_id = (int)$_POST['materia_id'];
            $periodo_id = (int)$_POST['periodo_id'];
            
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
            }
            
        } else {
            // Acción individual
            $notas_ids = $_POST['notas_ids'];
            $ids_str = implode(',', array_map('intval', $notas_ids));
            
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
?>