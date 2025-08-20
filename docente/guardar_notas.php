<?php
require_once('../funciones/functions.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso no permitido');
}

$required = ['materia_id', 'seccion_id', 'periodo_id', 'trayecto_actual', 'notas'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        die("Falta el campo: $field");
    }
}

$materia_id = (int)$_POST['materia_id'];
$seccion_id = (int)$_POST['seccion_id'];
$periodo_id = (int)$_POST['periodo_id'];
$trayecto_actual = (int)$_POST['trayecto_actual'];
$notas = $_POST['notas'];

if (isset($_SESSION['user']['id'])) {
    $docente_id = (int)$_SESSION['user']['id'];
} else {
    die("Error: No se pudo identificar al docente");
}

$trayectos_a_procesar = $trayecto_actual >= 0 && $trayecto_actual <= 2 ? [0, 1, 2] : [3, 4];

$db->begin_transaction();

try {
    foreach ($notas as $estudiante_id => $notas_trayectos) {
        $estudiante_id = (int)$estudiante_id;
        
        // Primero eliminar cualquier registro existente
        $delete_query = "DELETE FROM notas_pendientes 
                        WHERE id_usuario = ? AND id_materia = ? AND id_periodo = ?";
        $stmt = $db->prepare($delete_query);
        $stmt->bind_param("iii", $estudiante_id, $materia_id, $periodo_id);
        $stmt->execute();
        
        // Preparar valores para TODOS los trayectos (0-4)
        // Usar 0 para trayectos no procesados (luego se cambiará a NULL en la consulta)
        $valores_trayectos = array_fill(0, 5, 0);
        
        // Llenar los trayectos que se procesan
        foreach ($trayectos_a_procesar as $trayecto) {
            $campo = "trayecto_$trayecto";
            if (isset($notas_trayectos[$campo]) && $notas_trayectos[$campo] !== '') {
                $valor = (int)$notas_trayectos[$campo];
                $valores_trayectos[$trayecto] = $valor >= 1 && $valor <= 20 ? $valor : 1;
            } else {
                $valores_trayectos[$trayecto] = 1;
            }
        }
        
        // Crear la consulta dinámica con NULL para trayectos no procesados
        $insert_query = "INSERT INTO notas_pendientes 
                        (id_usuario, id_materia, id_periodo, id_docente, 
                         trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, 
                         fecha_envio, estado) 
                        VALUES (?, ?, ?, ?, ";
        
        // Agregar valores para cada trayecto
        $params = [];
        $types = "iiii";
        $values = [$estudiante_id, $materia_id, $periodo_id, $docente_id];
        
        for ($i = 0; $i <= 4; $i++) {
            if (in_array($i, $trayectos_a_procesar)) {
                // Trayecto que se procesa - usar el valor
                $insert_query .= "?, ";
                $params[] = $valores_trayectos[$i];
                $types .= "i";
            } else {
                // Trayecto que NO se procesa - usar NULL
                $insert_query .= "NULL, ";
            }
        }
        
        $insert_query .= "NOW(), 'pendiente')";
        
        // Preparar y ejecutar la consulta
        $stmt = $db->prepare($insert_query);
        
        // Si hay parámetros para bind, hacerlo
        if (!empty($params)) {
            $stmt->bind_param($types, ...array_merge($values, $params));
        }
        
        $stmt->execute();
    }
    
    $db->commit();
    echo "✅ Notas guardadas exitosamente en pendientes. Esperando aprobación del administrador.";
    
} catch (Exception $e) {
    $db->rollback();
    die("❌ Error al guardar notas: " . $e->getMessage());
}