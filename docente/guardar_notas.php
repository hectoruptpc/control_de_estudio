<?php
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn() || !isDocente()) {
    $_SESSION['msg'] = "Debes iniciar sesión como docente para acceder";
    header('location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

$required = ['materia_id', 'seccion_id', 'periodo_id', 'trayecto_actual', 'id_trayecto_seccion', 'notas'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        die("Falta el campo: $field");
    }
}

$materia_id = (int)$_POST['materia_id'];
$seccion_id = (int)$_POST['seccion_id'];
$periodo_id = (int)$_POST['periodo_id'];
$trayecto_actual = (int)$_POST['trayecto_actual'];
$id_trayecto_seccion = (int)$_POST['id_trayecto_seccion'];
$notas = $_POST['notas'];

if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} else {
    die("Error: No se pudo identificar al docente");
}

// Determinar qué trayecto procesar según el id_trayecto de la sección
$trayecto_a_procesar = '';
switch ($id_trayecto_seccion) {
    case 1: $trayecto_a_procesar = 0; break; // Trayecto Inicial
    case 2: $trayecto_a_procesar = 1; break; // Trayecto 1
    case 3: $trayecto_a_procesar = 2; break; // Trayecto 2
    case 4: $trayecto_a_procesar = 3; break; // Trayecto 3
    case 5: $trayecto_a_procesar = 4; break; // Trayecto 4
    default: $trayecto_a_procesar = 0;
}

$campo_trayecto = 'trayecto_' . $trayecto_a_procesar;

$db->begin_transaction();

try {
    $notas_actualizadas = 0;
    $notas_ignoradas = 0;
    $notas_reenviadas = 0;
    
    foreach ($notas as $estudiante_id => $notas_trayectos) {
        $estudiante_id = (int)$estudiante_id;
        
        $check_estado_query = "SELECT estado FROM notas_pendientes 
                              WHERE id_usuario = $estudiante_id 
                              AND id_materia = $materia_id 
                              AND id_periodo = $periodo_id";
        $result_estado = $db->query($check_estado_query);
        
        $estado_actual = 'pendiente';
        $existe_registro = false;
        
        if ($result_estado->num_rows > 0) {
            $existe_registro = true;
            $estado_actual = $result_estado->fetch_assoc()['estado'];
            
            if ($estado_actual === 'aprobada') {
                $notas_ignoradas++;
                continue;
            }
        }
        
        // Obtener valores actuales si existe el registro
        $valores_trayectos = array_fill(0, 5, 'NULL');
        
        if ($existe_registro) {
            $query_valores = "SELECT trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4 
                             FROM notas_pendientes 
                             WHERE id_usuario = $estudiante_id 
                             AND id_materia = $materia_id 
                             AND id_periodo = $periodo_id";
            $result_valores = $db->query($query_valores);
            
            if ($result_valores->num_rows > 0) {
                $valores_actuales = $result_valores->fetch_assoc();
                for ($i = 0; $i <= 4; $i++) {
                    $campo = 'trayecto_' . $i;
                    $valores_trayectos[$i] = $valores_actuales[$campo] !== null ? (int)$valores_actuales[$campo] : 'NULL';
                }
            }
        }
        
        // Actualizar solo el trayecto correspondiente
        if (isset($notas_trayectos[$campo_trayecto]) && $notas_trayectos[$campo_trayecto] !== '') {
            $valor = (int)$notas_trayectos[$campo_trayecto];
            $valores_trayectos[$trayecto_a_procesar] = $valor >= 1 && $valor <= 20 ? $valor : 1;
        } else {
            $valores_trayectos[$trayecto_a_procesar] = 1;
        }
        
        $nuevo_estado = 'pendiente';
        
        if ($existe_registro) {
            if ($estado_actual === 'rechazada') {
                $notas_reenviadas++;
            }
            
            $update_query = "UPDATE notas_pendientes SET 
                            id_docente = $docente_id, 
                            trayecto_0 = {$valores_trayectos[0]}, 
                            trayecto_1 = {$valores_trayectos[1]}, 
                            trayecto_2 = {$valores_trayectos[2]}, 
                            trayecto_3 = {$valores_trayectos[3]}, 
                            trayecto_4 = {$valores_trayectos[4]},
                            fecha_envio = NOW(), 
                            estado = '$nuevo_estado'
                            WHERE id_usuario = $estudiante_id 
                            AND id_materia = $materia_id 
                            AND id_periodo = $periodo_id
                            AND estado IN ('pendiente', 'rechazada')";
            
            $db->query($update_query);
            $notas_actualizadas++;
            
        } else {
            $insert_query = "INSERT INTO notas_pendientes 
                            (id_usuario, id_materia, id_periodo, id_docente, 
                             trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, 
                             fecha_envio, estado) 
                            VALUES (
                                $estudiante_id, 
                                $materia_id, 
                                $periodo_id, 
                                $docente_id,
                                {$valores_trayectos[0]}, 
                                {$valores_trayectos[1]}, 
                                {$valores_trayectos[2]},
                                {$valores_trayectos[3]}, 
                                {$valores_trayectos[4]},
                                NOW(), 
                                'pendiente'
                            )";
            
            $db->query($insert_query);
            $notas_actualizadas++;
        }
    }
    
    $db->commit();
    
    $mensaje = "✅ Notas procesadas exitosamente. ";
    $mensaje .= "Actualizadas: $notas_actualizadas, ";
    $mensaje .= "Reenviadas (rechazadas): $notas_reenviadas, ";
    $mensaje .= "Ignoradas (aprobadas): $notas_ignoradas.";
    
    if ($notas_ignoradas > 0) {
        $mensaje .= " Las notas aprobadas no pueden ser modificadas.";
    }
    
    if ($notas_reenviadas > 0) {
        $mensaje .= " Las notas rechazadas fueron cambiadas a pendiente.";
    }
    
    echo $mensaje;
    
} catch (Exception $e) {
    $db->rollback();
    die("❌ Error al guardar notas: " . $e->getMessage());
}