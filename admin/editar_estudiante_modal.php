<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('<div class="alert alert-danger">ID de estudiante no válido</div>');
}

$id = $_GET['id'];

// Consulta modificada para formatear las fechas TIMESTAMP
$query = "SELECT *, 
          DATE(fecha_nac) as fecha_nac_format, 
          DATE(fecha_ingreso) as fecha_ingreso_format 
          FROM users WHERE id = ? AND estudiante = 1";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('<div class="alert alert-danger">Estudiante no encontrado</div>');
}

$estudiante = $result->fetch_assoc();

// Obtener lista de carreras
$carreras = [];
$carrerasQuery = $db->query("SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera");
if ($carrerasQuery) {
    $carreras = $carrerasQuery->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="modal-header">
    <h5 class="modal-title">Editar Estudiante</h5>
    
</div>

<div class="modal-body">
    <form id="formEditarEstudiante" method="post" action="actualizar_estudiante.php">
        <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" 
                           value="<?php echo htmlspecialchars($estudiante['username'] ?? ''); ?>" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="num_telf" class="form-label">Teléfono Principal</label>
                    <input type="tel" class="form-control" id="num_telf" name="num_telf" 
                           value="<?php echo htmlspecialchars($estudiante['tlf'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                    <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc" 
                           value="<?php echo htmlspecialchars($estudiante['num_telf_opc'] ?? ''); ?>">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($estudiante['email'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" 
                           value="<?php echo htmlspecialchars($estudiante['fecha_nac_format'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label for="genero" class="form-label">Género</label>
                    <select class="custom-select" id="genero" name="genero" required>
                        <option value="">Seleccionar</option>
                        <option value="Masculino" <?php echo ($estudiante['genero'] ?? '') == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($estudiante['genero'] ?? '') == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        <option value="Otro" <?php echo ($estudiante['genero'] ?? '') == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="carrera" class="form-label">Programa/Carrera</label>
                    <select class="custom-select" id="carrera" name="carrera" required>
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id_carrera']; ?>" 
                                <?php echo ($estudiante['carrera'] ?? '') == $carrera['id_carrera'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" 
                           value="<?php echo htmlspecialchars($estudiante['fecha_ingreso_format'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select class="custom-select" id="status" name="status" required>
                        <option value="1" <?php echo ($estudiante['status'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($estudiante['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#formEditarEstudiante').on('submit', function(e) {
        e.preventDefault();
        
        // Validaciones básicas
        const email = $('#email').val();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Por favor ingrese un correo electrónico válido');
            return;
        }
        
        const telefono = $('#num_telf').val();
        if (telefono && telefono.length < 10) {
            alert('El teléfono debe tener al menos 10 dígitos');
            return;
        }
        
        // Enviar formulario via AJAX
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success) {
                    alert(data.message);
                    $('#editarEstudianteModal').modal('hide');
                    location.reload();
                } else {
                    alert(data.message || 'Error al actualizar el estudiante');
                }
            },
            error: function() {
                alert('Ocurrió un error al procesar la solicitud');
            }
        });
    });
});
</script>