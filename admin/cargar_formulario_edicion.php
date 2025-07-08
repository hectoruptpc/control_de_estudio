<?php
require_once('../funciones/functions.php');

$docenteId = $_GET['id'] ?? 0;
$docente = obtenerDocentePorId($docenteId);

if (!$docente) {
    echo '<div class="alert alert-danger">Docente no encontrado.</div>';
    exit;
}

// Función para generar inputs de forma dinámica
function generarInput($label, $name, $value, $type = 'text', $required = true) {
    return '<div class="mb-3">
        <label for="'.$name.'" class="form-label">'.$label.'</label>
        <input type="'.$type.'" class="form-control" id="'.$name.'" name="'.$name.'" 
               value="'.htmlspecialchars($value ?? '').'" '.($required ? 'required' : '').'>
    </div>';
}
?>

<form id="formEditarDocente">
    <input type="hidden" name="id" value="<?= htmlspecialchars($docente['id'] ?? '') ?>">
    <input type="hidden" name="idusuario" value="<?= htmlspecialchars($docente['idusuario'] ?? '') ?>">
    
    <div class="row">
        <div class="col-md-6">
            <h5 class="mb-4">Información Básica</h5>
            <?= generarInput('Nombre Completo', 'nombre', $docente['nombre'] ?? '') ?>
            <?= generarInput('Usuario', 'username', $docente['username'] ?? '') ?>
            <?= generarInput('Correo Electrónico', 'email', $docente['email'] ?? '', 'email') ?>
            <?= generarInput('Teléfono', 'tlf', $docente['tlf'] ?? '', 'tel') ?>
            <?= generarInput('Celular', 'cel', $docente['cel'] ?? '', 'tel', false) ?>
            
            <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select class="custom-select d-block w-100" id="genero" name="genero" required>
                    <option value="masculino" <?= isset($docente['genero']) && $docente['genero'] == 'masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="femenino" <?= isset($docente['genero']) && $docente['genero'] == 'femenino' ? 'selected' : '' ?>>Femenino</option>
                    <option value="otro" <?= isset($docente['genero']) && $docente['genero'] == 'otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <h5 class="mb-4">Información Adicional</h5>
            <?= generarInput('Dirección', 'direccion', $docente['direccion'] ?? '', 'text', false) ?>
            <?= generarInput('Ciudad', 'ciudad', $docente['ciudad'] ?? '', 'text', false) ?>
            <?= generarInput('Estado', 'estado', $docente['estado'] ?? '', 'text', false) ?>
            
            <div class="mb-3">
                <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" 
                       value="<?= htmlspecialchars($docente['fecha_nac'] ?? '') ?>">
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select class="custom-select d-block w-100" id="status" name="status" required>
                    <option value="activo" <?= isset($docente['status']) && $docente['status'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= isset($docente['status']) && $docente['status'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
</form>

<script>
.then(response => {
    if (!response.ok) {
        return response.text().then(text => { throw new Error(text) });
    }
    return response.json();
})
.then(data => {
    if (data.success) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: data.message || 'Los datos del docente se actualizaron correctamente.',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            location.reload();
        });
    } else {
        throw new Error(data.message || 'Error desconocido');
    }
})
.catch(error => {
    console.error('Error completo:', error);
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: error.message || 'Ocurrió un error al actualizar los datos.',
        confirmButtonText: 'Aceptar'
    });
})
</script>
