<?php
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/test';
include('funciones/functions.php');

function test_apto($estudiante_id) {
    global $db;
    $estudiante_id = intval($estudiante_id);
    if ($estudiante_id <= 0) return false;

    $query_user = "SELECT id, status, estudiante FROM users WHERE id = $estudiante_id LIMIT 1";
    $res_user = mysqli_query($db, $query_user);
    if (!$res_user || mysqli_num_rows($res_user) === 0) return false;
    $user = mysqli_fetch_assoc($res_user);
    if ($user['status'] != 1 || $user['estudiante'] != 1) return false;

    $query_notas_total = "SELECT 
                            (SELECT COUNT(*) FROM notas_definitivas WHERE id_usuario = $estudiante_id) as def_count,
                            (SELECT COUNT(*) FROM notas_trimestres WHERE id_usuario = $estudiante_id) as trim_count,
                            (SELECT COUNT(*) FROM estudiante_materias WHERE id_usuario = $estudiante_id) as mat_count";
    $res_total = mysqli_query($db, $query_notas_total);
    if (!$res_total) return false;
    $row_total = mysqli_fetch_assoc($res_total);
    
    $total_registros = intval($row_total['def_count']) + intval($row_total['trim_count']) + intval($row_total['mat_count']);
    if ($total_registros === 0) return false;

    $query_reprobadas = "SELECT 
        (SELECT COUNT(*) 
         FROM notas_definitivas nd 
         INNER JOIN materias m ON nd.id_materia = m.id_materia 
         WHERE nd.id_usuario = $estudiante_id 
         AND (
            (m.es_proyecto_socio = 1 AND (
                (nd.trayecto_0 IS NOT NULL AND nd.trayecto_0 < 16) OR
                (nd.trayecto_1 IS NOT NULL AND nd.trayecto_1 < 16) OR
                (nd.trayecto_2 IS NOT NULL AND nd.trayecto_2 < 16) OR
                (nd.trayecto_3 IS NOT NULL AND nd.trayecto_3 < 16) OR
                (nd.trayecto_4 IS NOT NULL AND nd.trayecto_4 < 16)
            ))
            OR
            (m.es_proyecto_socio = 0 AND (
                (nd.trayecto_0 IS NOT NULL AND nd.trayecto_0 < 12) OR
                (nd.trayecto_1 IS NOT NULL AND nd.trayecto_1 < 12) OR
                (nd.trayecto_2 IS NOT NULL AND nd.trayecto_2 < 12) OR
                (nd.trayecto_3 IS NOT NULL AND nd.trayecto_3 < 12) OR
                (nd.trayecto_4 IS NOT NULL AND nd.trayecto_4 < 12)
            ))
         )
        ) as reprobadas_def,
        (SELECT COUNT(*) FROM estudiante_materias WHERE id_usuario = $estudiante_id AND nota_final IS NOT NULL AND nota_final < 12) as reprobadas_mat,
        (SELECT COUNT(*) FROM notas_trimestres WHERE id_usuario = $estudiante_id AND nota IS NOT NULL AND nota < 12) as reprobadas_trim";
    
    $res_reprobadas = mysqli_query($db, $query_reprobadas);
    if (!$res_reprobadas) return false;
    $row_reprobadas = mysqli_fetch_assoc($res_reprobadas);

    $total_reprobadas = intval($row_reprobadas['reprobadas_def']) + intval($row_reprobadas['reprobadas_mat']) + intval($row_reprobadas['reprobadas_trim']);

    return ($total_reprobadas > 0);
}

$test_ids = [1, 2, 3, 5, 2379, 2450];
foreach ($test_ids as $id) {
    $apto = test_apto($id) ? "SÍ (APTO PARA INTENSIVO)" : "NO (NO APTO PARA INTENSIVO)";
    echo "Student ID $id -> $apto\n";
}
?>