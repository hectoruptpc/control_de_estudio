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
    <input type="hidden" name="status" value="<?= isset($docente['status']) ? htmlspecialchars($docente['status']) : 1 ?>">
    
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
                <select class="form-select" id="genero" name="genero" required>
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
            
         
        </div>
    </div>
    
    <div class="d-flex justify-content-end mt-4">
    <button type="button" class="btn btn-secondary me-2" id="btnCancelarDocente">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </div>
</form>

<script>

$(document).ready(function() {
    // Forzar cierre de modal al hacer clic en Cancelar
    $('#btnCancelarDocente').on('click', function() {
        // Buscar el modal padre y cerrarlo
        $(this).closest('.modal').modal('hide');
    });


    $('#formEditarDocente').on('submit', function(e) {
        e.preventDefault();
        
        // Mostrar loader
        Swal.fire({
            title: 'Procesando',
            html: 'Actualizando datos del docente...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var formData = $(this).serializeArray();
        var jsonData = {};
        
        // Convertir a objeto JSON
        $.each(formData, function(i, field) {
            jsonData[field.name] = field.value;
        });

        // Validar fecha_nac si está vacía
        if (!jsonData.fecha_nac) {
            jsonData.fecha_nac = null;
        }

        $.ajax({
            url: 'actualizar_docente.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(jsonData),
            dataType: 'json',
            success: function(response) {
                Swal.close();
                
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $('#modalEditarDocente').modal('hide');
                        // Refrescar la tabla o lista de docentes
                        if (typeof refreshDocentesTable === 'function') {
                            refreshDocentesTable();
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                let errorMsg = 'Ocurrió un error al enviar los datos';
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMsg = response.message;
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg,
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});
</script>