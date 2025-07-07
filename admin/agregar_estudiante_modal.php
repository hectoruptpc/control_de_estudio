<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

// Obtener lista de carreras
$carreras = [];
$carrerasQuery = $db->query("SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera");
if ($carrerasQuery) {
    $carreras = $carrerasQuery->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="modal-body">
    <form id="formAgregarEstudiante" method="post" action="guardar_estudiante.php">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula*</label>
                    <input type="text" class="form-control" id="cedula" name="cedula" required>
                </div>
                
                <div class="mb-3">
                    <label for="num_telf" class="form-label">Teléfono Principal*</label>
                    <input type="tel" class="form-control" id="num_telf" name="num_telf" required>
                </div>
                
                <div class="mb-3">
                    <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                    <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico*</label>
                    <input type="email" class="form-control" id="correo" name="correo" required>
                </div>
                
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento*</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                </div>
                
                <div class="mb-3">
                    <label for="genero" class="form-label">Género*</label>
                    <select class="form-select" id="genero" name="genero" required>
                        <option value="">Seleccionar</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="carrera" class="form-label">Programa/Carrera*</label>
                    <select class="form-select" id="carrera" name="carrera" required>
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id_carrera']; ?>">
                                <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso*</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Estudiante</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>

</script>