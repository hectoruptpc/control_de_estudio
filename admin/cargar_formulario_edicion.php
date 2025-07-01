<?php
require_once('../funciones/functions.php');

$docenteId = $_GET['id'] ?? 0;
$docente = obtenerDocentePorId($docenteId);

if (!$docente) {
    echo '<div class="alert alert-danger">Docente no encontrado.</div>';
    exit;
}
?>

<form id="formEditarDocente">
    <input type="hidden" name="id" value="<?= $docente['id'] ?>">
    
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre Completo</label>
        <input type="text" class="form-control" id="nombre" name="nombre" 
               value="<?= htmlspecialchars($docente['nombre']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" 
               value="<?= htmlspecialchars($docente['email']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="tel" class="form-control" id="telefono" name="telefono" 
               value="<?= htmlspecialchars($docente['tlf']) ?>" required>
    </div>
    
    <!-- Agrega más campos según sea necesario -->
</form>