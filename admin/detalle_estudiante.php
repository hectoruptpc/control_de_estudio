<?php
require_once '../config/database.php';

$id = $_GET['id'] ?? 0;

try {
    $query = "SELECT 
                u.*,
                c.nombre_carrera 
              FROM users u
              LEFT JOIN carreras c ON u.carrera = c.id_carrera
              WHERE u.id = :id AND u.user_type = 'estudiante'";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$estudiante) {
        echo '<div class="alert alert-danger">Estudiante no encontrado</div>';
        exit;
    }
?>
<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted">Información Personal</h6>
        <ul class="list-group list-group-flush mb-4">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Código:</span>
                <span><?= htmlspecialchars($estudiante['cod_estudiante']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Nombre:</span>
                <span><?= htmlspecialchars($estudiante['nombre']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Carrera:</span>
                <span><?= htmlspecialchars($estudiante['nombre_carrera'] ?? 'No especificado') ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Género:</span>
                <span><?= htmlspecialchars($estudiante['genero']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Fecha Nacimiento:</span>
                <span><?= !empty($estudiante['fecha_nac']) ? date('d/m/Y', strtotime($estudiante['fecha_nac'])) : 'No especificada' ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Edad:</span>
                <span><?= $estudiante['edad'] ?? 'No especificada' ?></span>
            </li>
        </ul>
    </div>
    <div class="col-md-6">
        <h6 class="text-muted">Información de Contacto</h6>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Teléfono:</span>
                <span><?= htmlspecialchars($estudiante['tlf']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Teléfono Opcional:</span>
                <span><?= htmlspecialchars($estudiante['num_telf_opc'] ?? 'No especificado') ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Correo:</span>
                <span><?= htmlspecialchars($estudiante['email']) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Dirección:</span>
                <span><?= htmlspecialchars($estudiante['direccion'] ?? 'No especificada') ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Estado:</span>
                <span><?= htmlspecialchars($estudiante['estado'] ?? 'No especificado') ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="fw-bold">Fecha Ingreso:</span>
                <span><?= date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) ?></span>
            </li>
        </ul>
    </div>
</div>
<?php
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Error al cargar detalles: ' . $e->getMessage() . '</div>';
}