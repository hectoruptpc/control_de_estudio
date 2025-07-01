<?php
require_once '../config/database.php';

$id = $_POST['id'] ?? 0;

$datos = [
    'id' => $id,
    'cod_estudiante' => $_POST['cod_estudiante'] ?? '',
    'nombre' => $_POST['nombre'] ?? '',
    'carrera' => $_POST['carrera'] ?? '',
    'genero' => $_POST['genero'] ?? '',
    'tlf' => $_POST['num_telf'] ?? '',
    'email' => $_POST['correo'] ?? '',
    'fecha_ingreso' => $_POST['fecha_ingreso'] ?? '',
    'status' => $_POST['status'] ?? 1,
    'direccion' => $_POST['direccion'] ?? '',
    'fecha_nac' => $_POST['fecha_nac'] ?? null,
    'edad' => $_POST['edad'] ?? null
];

try {
    $query = "UPDATE users SET
                cod_estudiante = :cod_estudiante,
                nombre = :nombre,
                carrera = :carrera,
                genero = :genero,
                tlf = :tlf,
                email = :email,
                fecha_ingreso = :fecha_ingreso,
                status = :status,
                direccion = :direccion,
                fecha_nac = :fecha_nac,
                edad = :edad,
                fecha_act = NOW()
              WHERE id = :id";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($datos);
    
    echo json_encode([
        'success' => true,
        'message' => 'Estudiante actualizado correctamente'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al actualizar estudiante: ' . $e->getMessage()
    ]);
}