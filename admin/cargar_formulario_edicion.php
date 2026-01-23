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
                <select class="custom-select d-block w-100" id="genero" name="genero" required>
                    <option value="masculino" <?= isset($docente['genero']) && $docente['genero'] == 'masculino' ? 'selected' : '' ?>>Masculino</option>
                    <option value="femenino" <?= isset($docente['genero']) && $docente['genero'] == 'femenino' ? 'selected' : '' ?>>Femenino</option>
                    <option value="otro" <?= isset($docente['genero']) && $docente['genero'] == 'otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>

            <!-- Campos adicionales requeridos por actualizarDocente() -->
            <?= generarInput('Municipio', 'municipio', $docente['municipio'] ?? '', 'text', false) ?>
            <?= generarInput('Parroquia', 'parroquia', $docente['parroquia'] ?? '', 'text', false) ?>
            <?= generarInput('Carrera', 'carrera', $docente['carrera'] ?? '', 'text', false) ?>
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

            <!-- Más campos adicionales -->
            <div class="mb-3">
                <label for="edo_civil" class="form-label">Estado Civil</label>
                <select class="custom-select d-block w-100" id="edo_civil" name="edo_civil">
                    <option value="">Seleccione...</option>
                    <option value="soltero" <?= isset($docente['edo_civil']) && $docente['edo_civil'] == 'soltero' ? 'selected' : '' ?>>Soltero</option>
                    <option value="casado" <?= isset($docente['edo_civil']) && $docente['edo_civil'] == 'casado' ? 'selected' : '' ?>>Casado</option>
                    <option value="divorciado" <?= isset($docente['edo_civil']) && $docente['edo_civil'] == 'divorciado' ? 'selected' : '' ?>>Divorciado</option>
                    <option value="viudo" <?= isset($docente['edo_civil']) && $docente['edo_civil'] == 'viudo' ? 'selected' : '' ?>>Viudo</option>
                </select>
            </div>

            <?= generarInput('Teléfono Opcional', 'num_telf_opc', $docente['num_telf_opc'] ?? '', 'tel', false) ?>
            <?= generarInput('Títulos', 'titulos', $docente['titulos'] ?? '', 'text', false) ?>
            <?= generarInput('Institutos', 'institutos', $docente['institutos'] ?? '', 'text', false) ?>
            
            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                       value="<?= htmlspecialchars($docente['fecha_ingreso'] ?? '') ?>">
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

        // Validar campos que pueden estar vacíos
        if (!jsonData.fecha_nac) jsonData.fecha_nac = null;
        if (!jsonData.fecha_ingreso) jsonData.fecha_ingreso = null;
        if (!jsonData.municipio) jsonData.municipio = '';
        if (!jsonData.parroquia) jsonData.parroquia = '';
        if (!jsonData.carrera) jsonData.carrera = '';
        if (!jsonData.edo_civil) jsonData.edo_civil = '';
        if (!jsonData.num_telf_opc) jsonData.num_telf_opc = '';
        if (!jsonData.titulos) jsonData.titulos = '';
        if (!jsonData.institutos) jsonData.institutos = '';

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
                        $('#modalEditar').modal('hide');
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

<?php