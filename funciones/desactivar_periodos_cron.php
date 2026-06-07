<?php
// Configuración básica
require_once('../funciones/functions.php'); // Incluye tus funciones



// Verificar conexión
if ($db->connect_error) {
    file_put_contents('cron_errors.log', date('Y-m-d H:i:s') . " - Error de conexión: " . $db->connect_error . "\n", FILE_APPEND);
    exit;
}

// Función para desactivar periodos vencidos (la misma que teníamos)
function desactivarPeriodosVencidos($db) {
    $query = "UPDATE periodos_academicos SET activo = 0 
              WHERE fecha_fin < CURDATE() AND activo = 1";
    $stmt = $db->prepare($query);
    
    if (!$stmt) {
        file_put_contents('cron_errors.log', date('Y-m-d H:i:s') . " - Error al preparar la consulta: " . $db->error . "\n", FILE_APPEND);
        return false;
    }
    
    if (!$stmt->execute()) {
        file_put_contents('cron_errors.log', date('Y-m-d H:i:s') . " - Error al ejecutar la consulta: " . $stmt->error . "\n", FILE_APPEND);
        return false;
    }
    
    $affected = $db->affected_rows;
    file_put_contents('cron_log.log', date('Y-m-d H:i:s') . " - Periodos desactivados: " . $affected . "\n", FILE_APPEND);
    
    return $affected;
}

// Ejecutar la función
$resultado = desactivarPeriodosVencidos($db);

// Cerrar conexión
$db->close();

// Mensaje de éxito (opcional para testing)
if ($resultado !== false) {
    echo "Proceso completado. Periodos desactivados: " . $resultado;
} else {
    echo "Ocurrió un error durante el proceso. Ver logs para más detalles.";
}
?>