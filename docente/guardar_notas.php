<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Acceso no permitido']);
    exit;
}

$required = ['materia_id', 'seccion_id', 'periodo_id', 'notas'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => "Falta el campo: $field"]);
        exit;
    }
}

$materia_id = (int)$_POST['materia_id'];
$seccion_id = (int)$_POST['seccion_id'];
$periodo_id = (int)$_POST['periodo_id'];
$notas = $_POST['notas'];

if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Error: No se pudo identificar al docente']);
    exit;
}

// Procesar soporte del grupo
$soporte_grupo_nombre = null;
$tipo_archivo_grupo = null;

// El soporte es opcional pero se guarda si se proporciona
if (isset($_FILES['soporte_grupo']) && !empty($_FILES['soporte_grupo']['name'])) {
    $resultadoSoporte = subirSoporte($_FILES['soporte_grupo']);
    if ($resultadoSoporte['success']) {
        $soporte_grupo_nombre = $resultadoSoporte['ruta'];
        $tipo_archivo_grupo = $resultadoSoporte['tipo'];
    }
}

global $db;
$db->begin_transaction();

try {
    $notas_guardadas = 0;
    $notas_actualizadas = 0;
    
    foreach ($notas as $estudiante_id => $notas_trimestres) {
        $estudiante_id = (int)$estudiante_id;
        
        // Procesar cada trimestre (1, 2, 3)
        for ($trimestre = 1; $trimestre <= 3; $trimestre++) {
            $campo = "trimestre_$trimestre";
            if (isset($notas_trimestres[$campo]) && $notas_trimestres[$campo] !== '') {
                $nota_valor = (float)$notas_trimestres[$campo];
                $nota_valor = max(1, min(20, $nota_valor));
                
                // Verificar si ya existe una nota para este trimestre
                $check_query = "SELECT id, estado FROM notas_trimestres 
                               WHERE id_usuario = $estudiante_id 
                               AND id_materia = $materia_id 
                               AND id_periodo = $periodo_id 
                               AND trimestre_num = $trimestre";
                $result_check = $db->query($check_query);
                
                if ($result_check && $result_check->num_rows > 0) {
                    $row = $result_check->fetch_assoc();
                    $estado_actual = $row['estado'] ?? 'pendiente';
                    
                    // Solo actualizar si no está aprobada
                    if ($estado_actual !== 'aprobada') {
                        $update_query = "UPDATE notas_trimestres SET 
                                        nota = $nota_valor,
                                        id_docente = $docente_id,
                                        fecha_registro = NOW(),
                                        estado = 'pendiente'
                                        WHERE id_usuario = $estudiante_id 
                                        AND id_materia = $materia_id 
                                        AND id_periodo = $periodo_id 
                                        AND trimestre_num = $trimestre";
                        $db->query($update_query);
                        $notas_actualizadas++;
                    }
                } else {
                    // Insertar nueva nota
                    $insert_query = "INSERT INTO notas_trimestres 
                                    (id_usuario, id_materia, id_periodo, id_docente, trimestre_num, nota, fecha_registro, estado) 
                                    VALUES 
                                    ($estudiante_id, $materia_id, $periodo_id, $docente_id, $trimestre, $nota_valor, NOW(), 'pendiente')";
                    $db->query($insert_query);
                    $notas_guardadas++;
                }
            }
        }
        
        // Guardar nota final si existe
        if (isset($notas_trimestres['nota_final']) && $notas_trimestres['nota_final'] !== '') {
            $nota_final = (float)$notas_trimestres['nota_final'];
            
            // Guardar en notas_pendientes o notas_definitivas según corresponda
            // Por ahora, guardamos en notas_pendientes como referencia
            $check_pendientes = "SELECT id FROM notas_pendientes 
                                WHERE id_usuario = $estudiante_id 
                                AND id_materia = $materia_id 
                                AND id_periodo = $periodo_id";
            $result_pend = $db->query($check_pendientes);
            
            if ($result_pend && $result_pend->num_rows > 0) {
                // Actualizar trayecto correspondiente
                $trayecto_a_guardar = isset($_POST['trayecto_actual']) ? (int)$_POST['trayecto_actual'] : 0;
                $campo_trayecto = "trayecto_$trayecto_a_guardar";
                $update_pend = "UPDATE notas_pendientes SET 
                                $campo_trayecto = $nota_final,
                                fecha_envio = NOW()
                                WHERE id_usuario = $estudiante_id 
                                AND id_materia = $materia_id 
                                AND id_periodo = $periodo_id";
                $db->query($update_pend);
            } else {
                // Insertar en notas_pendientes
                $trayecto_a_guardar = isset($_POST['trayecto_actual']) ? (int)$_POST['trayecto_actual'] : 0;
                $campos_trayectos = '';
                $valores_trayectos = '';
                for ($i = 0; $i <= 4; $i++) {
                    $valor = ($i == $trayecto_a_guardar) ? $nota_final : 'NULL';
                    $campos_trayectos .= "trayecto_$i, ";
                    $valores_trayectos .= "$valor, ";
                }
                $campos_trayectos = rtrim($campos_trayectos, ', ');
                $valores_trayectos = rtrim($valores_trayectos, ', ');
                
                $soporte_sql = "";
                $soporte_values = "";
                if ($soporte_grupo_nombre) {
                    $soporte_sql = ", soporte, tipo_archivo, fecha_subida";
                    $soporte_values = ", '" . $db->real_escape_string($soporte_grupo_nombre) . "', 
                                       '" . $db->real_escape_string($tipo_archivo_grupo) . "', NOW()";
                }
                
                $insert_pend = "INSERT INTO notas_pendientes 
                                (id_usuario, id_materia, id_periodo, id_docente, $campos_trayectos, fecha_envio, estado $soporte_sql) 
                                VALUES 
                                ($estudiante_id, $materia_id, $periodo_id, $docente_id, $valores_trayectos, NOW(), 'pendiente' $soporte_values)";
                $db->query($insert_pend);
            }
        }
    }
    
    $db->commit();
    
    $mensaje = "✅ Procesamiento completado exitosamente.<br>";
    $mensaje .= "• Notas nuevas guardadas: $notas_guardadas<br>";
    $mensaje .= "• Notas actualizadas: $notas_actualizadas<br>";
    
    if ($soporte_grupo_nombre) {
        $mensaje .= "• Soporte del grupo subido: Sí<br>";
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'message' => $mensaje, 'soporte' => (bool)$soporte_grupo_nombre]);
    
} catch (Exception $e) {
    $db->rollback();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Error al guardar notas: ' . $e->getMessage()]);
    exit;
}
?>