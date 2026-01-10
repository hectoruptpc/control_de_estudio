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

// Procesar soporte del grupo (una sola imagen para todos)
$soporte_grupo_nombre = null;
$tipo_archivo_grupo = null;

if (isset($_FILES['soporte_grupo']) && !empty($_FILES['soporte_grupo']['name'])) {
    $resultadoSoporte = subirSoporte($_FILES['soporte_grupo']);
    
    if ($resultadoSoporte['success']) {
        $soporte_grupo_nombre = $resultadoSoporte['ruta'];
        $tipo_archivo_grupo = $resultadoSoporte['tipo'];
    } else {
        // Continuar sin soporte pero informar al usuario
        $error_soporte = $resultadoSoporte['error'];
    }
}

$db->begin_transaction();

try {
    $notas_actualizadas = 0;
    $notas_ignoradas = 0;
    $notas_reenviadas = 0;
    
    foreach ($notas as $estudiante_id => $notas_trayectos) {
        $estudiante_id = (int)$estudiante_id;
        
        $check_estado_query = "SELECT estado, soporte FROM notas_pendientes 
                              WHERE id_usuario = $estudiante_id 
                              AND id_materia = $materia_id 
                              AND id_periodo = $periodo_id";
        $result_estado = $db->query($check_estado_query);
        
        $estado_actual = 'pendiente';
        $existe_registro = false;
        $soporte_anterior = null;
        
        if ($result_estado->num_rows > 0) {
            $existe_registro = true;
            $row = $result_estado->fetch_assoc();
            $estado_actual = $row['estado'];
            $soporte_anterior = $row['soporte'];
            
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
            $valor = max(1, min(20, $valor));
            $valores_trayectos[$trayecto_a_procesar] = $valor;
        } else {
            $valores_trayectos[$trayecto_a_procesar] = 1;
        }
        
        // Usar el mismo soporte para todos los estudiantes del grupo
        $soporte_nombre = $soporte_grupo_nombre;
        $tipo_archivo = $tipo_archivo_grupo;
        
        // Si existe registro y no se subió nuevo soporte, mantener el anterior
        if ($existe_registro && $soporte_nombre === null) {
            $soporte_nombre = $soporte_anterior;
            // Obtener tipo_archivo de la base de datos
            if ($soporte_anterior) {
                $tipo_query = "SELECT tipo_archivo FROM notas_pendientes 
                              WHERE id_usuario = $estudiante_id 
                              AND id_materia = $materia_id 
                              AND id_periodo = $periodo_id";
                $result_tipo = $db->query($tipo_query);
                if ($result_tipo->num_rows > 0) {
                    $tipo_archivo = $result_tipo->fetch_assoc()['tipo_archivo'];
                }
            }
        }
        
        $nuevo_estado = 'pendiente';
        
        // Preparar campos de soporte para la consulta
        $campos_soporte = "";
        $valores_soporte = "";
        
        if ($soporte_nombre !== null) {
            $campos_soporte = ", soporte, tipo_archivo, fecha_subida";
            $valores_soporte = ", '" . $db->real_escape_string($soporte_nombre) . "', 
                               '" . $db->real_escape_string($tipo_archivo) . "', NOW()";
        }
        
        if ($existe_registro) {
            if ($estado_actual === 'rechazada') {
                $notas_reenviadas++;
            }
            
            $update_campos_soporte = "";
            if ($soporte_nombre !== null) {
                $update_campos_soporte = ", soporte = '" . $db->real_escape_string($soporte_nombre) . "', 
                                         tipo_archivo = '" . $db->real_escape_string($tipo_archivo) . "', 
                                         fecha_subida = NOW()";
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
                            $update_campos_soporte
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
                             fecha_envio, estado
                             $campos_soporte) 
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
                                $valores_soporte
                            )";
            
            $db->query($insert_query);
            $notas_actualizadas++;
        }
    }
    
    $db->commit();
    
    $mensaje = "✅ Procesamiento completado exitosamente.<br>";
    $mensaje .= "• Notas procesadas: $notas_actualizadas<br>";
    
    if ($soporte_grupo_nombre) {
        $mensaje .= "• Soporte del grupo subido: Sí<br>";
    } else {
        $mensaje .= "• Soporte del grupo subido: No<br>";
        if (isset($error_soporte)) {
            $mensaje .= "• <span class='text-warning'>Advertencia: $error_soporte</span><br>";
        }
    }
    
    if ($notas_reenviadas > 0) {
        $mensaje .= "• Notas reenviadas (rechazadas): $notas_reenviadas<br>";
    }
    
    if ($notas_ignoradas > 0) {
        $mensaje .= "• Notas ignoradas (aprobadas): $notas_ignoradas<br>";
        $mensaje .= "<small class='text-muted'>Las notas aprobadas no pueden ser modificadas.</small>";
    }
    
    echo $mensaje;
    
} catch (Exception $e) {
    $db->rollback();
    // Eliminar el soporte subido si hubo error
    if ($soporte_grupo_nombre) {
        eliminarSoporteAnterior($soporte_grupo_nombre);
    }
    die("❌ Error al guardar notas: " . $e->getMessage());
}
?>