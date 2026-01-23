<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../funciones/functions.php';

header('Content-Type: application/json');

try {
    // Validar datos de entrada
    $id = $_POST['id_carrera'] ?? 0;
    $nombre = trim($_POST['nombre_carrera'] ?? '');
    $codigo = trim($_POST['cod_carrera'] ?? '');
    $tipo_formacion = trim($_POST['tipo_formacion'] ?? '');
    $duracion_anios = (int)($_POST['duracion_anios'] ?? 0);
    $titulo_principal = trim($_POST['titulo_principal'] ?? '');
    $titulo_opcional = trim($_POST['titulo_opcional'] ?? '');
    $activa = (int)($_POST['activa'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($codigo) || empty($titulo_principal)) {
        throw new Exception("Todos los campos obligatorios deben completarse");
    }

    if ($duracion_anios < 1 || $duracion_anios > 6) {
        throw new Exception("La duración debe estar entre 1 y 6 años");
    }

    // Convertir años a semestres
    $duracion_semestres = $duracion_anios * 2;

    // Actualizar en la base de datos
    $resultado = actualizarCarrera(
        $id,
        $nombre,
        $codigo,
        $tipo_formacion,
        $duracion_semestres,
        $titulo_principal,
        $titulo_opcional,
        $descripcion,
        $activa
    );

    if ($resultado['success']) {
        echo json_encode(['success' => true, 'message' => 'Carrera actualizada correctamente']);
    } else {
        throw new Exception($resultado['message'] ?? "Error al actualizar la carrera en la base de datos");
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}