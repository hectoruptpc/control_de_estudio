<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Configuración inicial
header('Content-Type: application/json');

// 2. Incluir funciones y verificar sesión
include('../funciones/functions.php');

// Verificación de sesión adaptada a tu estructura
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

// 3. Procesamiento principal
try {
    global $db; // Usamos tu variable global $db

    if (isset($_GET['id_carrera'])) {
        $id_carrera = intval($_GET['id_carrera']);
        
        // Determinar qué tipo de materias se solicitan
        if (isset($_GET['tipo']) && $_GET['tipo'] === 'disponibles') {
            // Materias disponibles (no asignadas a la carrera)
            $query = "SELECT m.id_materia, m.cod_materia, m.nombre_materia 
                      FROM materias m
                      WHERE m.activa = 1 AND m.id_materia NOT IN (
                          SELECT cm.id_materia FROM carrera_materia cm 
                          WHERE cm.id_carrera = ?
                      )
                      ORDER BY m.nombre_materia";
        } else {
            // Materias asignadas a la carrera (por defecto)
            $query = "SELECT m.id_materia, m.cod_materia, m.nombre_materia, cm.semestre, cm.id_relacion
                      FROM carrera_materia cm
                      JOIN materias m ON cm.id_materia = m.id_materia
                      WHERE cm.id_carrera = ?
                      ORDER BY cm.semestre, m.nombre_materia";
        }

        // Prepared statement para seguridad
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id_carrera);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $materias = [];
        while ($row = $result->fetch_assoc()) {
            $materias[] = $row;
        }

        echo json_encode([
            'success' => true,
            'data' => $materias
        ]);
        
    } else {
        throw new Exception('Parámetro id_carrera no especificado');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// 4. No incluimos head.php ni footer.php porque es un endpoint API
// Se cierra explícitamente la conexión si es necesario
if (isset($db) && $db instanceof mysqli) {
    $db->close();
}
exit; // Importante para evitar salida adicional


