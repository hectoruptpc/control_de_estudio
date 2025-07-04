<?php
require_once('../funciones/functions.php');

// Verificar ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('<div class="alert alert-danger">ID de estudiante no válido</div>');
}

// Obtener datos
$estudiante = obtenerEstudiantePorId($id);
if (isset($estudiante['error'])) {
    die('<div class="alert alert-danger">'.$estudiante['error'].'</div>');
}
?>

<div class="modal-header">
    <h5 class="modal-title">Detalles del Estudiante</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <h5>Información Personal</h5>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item">
                    <strong>ID:</strong> <?= htmlspecialchars($estudiante['id'] ?? '') ?>
                </li>
                <li class="list-group-item">
                    <strong>Nombre:</strong> <?= htmlspecialchars($estudiante['nombre'] ?? '') ?>
                </li>
                <li class="list-group-item">
                    <strong>Usuario:</strong> <?= htmlspecialchars($estudiante['username'] ?? '') ?>
                </li>
                <li class="list-group-item">
                    <strong>Género:</strong> <?= htmlspecialchars($estudiante['genero'] ?? '') ?>
                </li>
                <li class="list-group-item">
                    <strong>Fecha Nacimiento:</strong> 
                    <?= !empty($estudiante['fecha_nac']) ? date('d/m/Y', strtotime($estudiante['fecha_nac'])) : 'No especificado' ?>
                </li>
            </ul>
        </div>
        
        <div class="col-md-6">
            <h5>Información Académica</h5>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item">
                    <strong>Carrera:</strong> <?= htmlspecialchars($estudiante['carrera'] ?? '') ?>
                </li>
                <li class="list-group-item">
                    <strong>Fecha Ingreso:</strong> 
                    <?= !empty($estudiante['fecha_ingreso']) ? date('d/m/Y', strtotime($estudiante['fecha_ingreso'])) : 'No especificado' ?>
                </li>
                <li class="list-group-item">
                    <strong>Estado:</strong> <?= $estudiante['status'] == 1 ? 'Activo' : 'Inactivo' ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h5>Contacto</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Email:</strong> <?= htmlspecialchars($estudiante['email'] ?? 'No especificado') ?>
                </li>
                <li class="list-group-item">
                    <strong>Teléfono:</strong> <?= htmlspecialchars($estudiante['tlf'] ?? 'No especificado') ?>
                </li>
                <li class="list-group-item">
                    <strong>Celular:</strong> <?= htmlspecialchars($estudiante['cel'] ?? 'No especificado') ?>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
</div>