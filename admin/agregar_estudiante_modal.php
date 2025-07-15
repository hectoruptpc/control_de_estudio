<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../funciones/functions.php');

// Desactivar log de errores a archivo para evitar error de permisos
ini_set('log_errors', 0);
ini_set('error_log', '/dev/null');
// Si quieres ver errores, déjalos en pantalla (display_errors=1)

// Obtener lista de carreras
$carreras = [];
$carrerasQuery = $db->query("SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera");
if ($carrerasQuery) {
    $carreras = $carrerasQuery->fetch_all(MYSQLI_ASSOC);
}

// Procesar el formulario en el mismo archivo
$success_message = '';
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generar idusuario si viene por partes
    if (isset($_POST['tipo_cedula'], $_POST['numero_cedula'])) {
        $_POST['idusuario'] = $_POST['tipo_cedula'] . '-' . preg_replace('/[^0-9]/', '', $_POST['numero_cedula']);
    }
    $validacion = validarEstudiante($_POST);
    if ($validacion === true) {
        // Ejecutar el guardado SIN ningún log ni error personalizado
        try {
            $resultado = @insertarEstudiante($_POST); // El @ suprime cualquier warning/error
            if ($resultado && isset($resultado['success']) && $resultado['success']) {
                $success_message = $resultado['message'];
                $_POST = [];
            } else {
                $error_message = isset($resultado['message']) ? $resultado['message'] : 'No se pudo guardar el estudiante.';
            }
        } catch (Exception $e) {
            $error_message = 'No se pudo guardar el estudiante.';
        }
    } else {
        $error_message = implode('<br>', $validacion);
    }
}
?>

<div class="modal-body">
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form id="formAgregarEstudiante" method="post" action="">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre Completo*</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="cedula" class="form-label">Cédula*</label>
                    <div class="input-group">
    <select class="form-select" id="tipo_cedula" name="tipo_cedula" style="max-width: 80px;" required>
        <?php
        // Consulta preparada para obtener los tipos de cédula
        $query = "SELECT id, tipo FROM tipo_cedula";
        $stmt = $db->prepare($query);
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            
            $selectedValue = $_POST['tipo_cedula'] ?? '';
            
            while ($row = $result->fetch_assoc()) {
                $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');
                $tipo = htmlspecialchars($row['tipo'], ENT_QUOTES, 'UTF-8');
                $selected = ($selectedValue === $row['id']) ? 'selected' : '';
                echo "<option value='{$id}' {$selected}>{$tipo}</option>";
            }
            
            $stmt->close();
        } else {
            // Manejo de error (opcional)
            echo "<option value=''>Error al cargar tipos</option>";
        }
        ?>
    </select>
                    </div>
                    <small class="text-muted">Formato: V-12345678 o E-12345678</small>
                </div>
                <div class="mb-3">
                    <label for="num_telf" class="form-label">Teléfono Principal*</label>
                    <input type="tel" class="form-control" id="num_telf" name="tlf" required value="<?= htmlspecialchars($_POST['tlf'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="num_telf_opc" class="form-label">Teléfono Opcional</label>
                    <input type="tel" class="form-control" id="num_telf_opc" name="num_telf_opc" value="<?= htmlspecialchars($_POST['num_telf_opc'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico*</label>
                    <input type="email" class="form-control" id="correo" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento*</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required value="<?= htmlspecialchars($_POST['fecha_nac'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="genero" class="form-label">Género*</label>
                    <select class="form-select" id="genero" name="genero" required>
                        <option value="">Seleccionar</option>
                        <option value="Masculino" <?= (($_POST['genero'] ?? '') == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                        <option value="Femenino" <?= (($_POST['genero'] ?? '') == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                        <option value="Otro" <?= (($_POST['genero'] ?? '') == 'Otro') ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="carrera" class="form-label">Programa/Carrera*</label>
                    <select class="form-select" id="carrera" name="carrera" required>
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?php echo $carrera['id_carrera']; ?>" <?= (($_POST['carrera'] ?? '') == $carrera['id_carrera']) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($carrera['nombre_carrera']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso*</label>
                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required value="<?= htmlspecialchars($_POST['fecha_ingreso'] ?? '') ?>">
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
// Manejo del campo de cédula para generar idusuario
    document.addEventListener('DOMContentLoaded', function() {
        const tipoCedula = document.getElementById('tipo_cedula');
        const numeroCedula = document.getElementById('numero_cedula');
        const idUsuario = document.getElementById('idusuario');

        function actualizarCedulaCompleta() {
            const numeroLimpio = numeroCedula.value.replace(/[^0-9]/g, '');
            numeroCedula.value = numeroLimpio;
            idUsuario.value = tipoCedula.value + '-' + numeroLimpio;
        }

        tipoCedula.addEventListener('change', actualizarCedulaCompleta);
        numeroCedula.addEventListener('input', actualizarCedulaCompleta);
        actualizarCedulaCompleta();
    });
</script>