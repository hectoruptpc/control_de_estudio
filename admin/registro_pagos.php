<?php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

$titulopag = "Registro de Pagos";
include("includes/head.php");

// Función para buscar estudiante por cédula
function buscarEstudiantePorCedulaPagos($cedula) {
    global $db;
    
    $query = "SELECT u.id, u.nombre, u.idusuario, u.carrera 
              FROM users u 
              WHERE u.idusuario = ? AND u.estudiante = 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Función para obtener los tipos de pago
function obtenerTiposPago() {
    global $db;
    
    $query = "SELECT id, tipopago FROM tipo_pago ORDER BY tipopago";
    $result = $db->query($query);
    
    $tipos = [];
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row;
    }
    
    return $tipos;
}

// Función para registrar un pago
function registrarPago($estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por) {
    global $db;
    
    $query = "INSERT INTO pagos (estudiante_id, tipo_pago, otro_concepto, monto, fecha_pago, observaciones, registrado_por) 
              VALUES (?, ?, ?, ?, NOW(), ?, ?)";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iisdsi", $estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por);
    
    return $stmt->execute();
}

// Función para obtener pagos del día actual
function obtenerPagosDelDia() {
    global $db;
    
    $query = "SELECT p.*, u.nombre as nombre_estudiante, u.idusuario as cedula, 
                     tp.tipopago as nombre_tipo_pago,
                     ur.nombre as nombre_registrador
              FROM pagos p
              INNER JOIN users u ON p.estudiante_id = u.id
              INNER JOIN tipo_pago tp ON p.tipo_pago = tp.id
              INNER JOIN users ur ON p.registrado_por = ur.id
              WHERE DATE(p.fecha_pago) = CURDATE()
              ORDER BY p.fecha_pago DESC";
    
    $result = $db->query($query);
    
    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    
    return $pagos;
}

// Función para obtener total de pagos del día
function obtenerTotalPagosDelDia() {
    global $db;
    
    $query = "SELECT SUM(monto) as total FROM pagos WHERE DATE(fecha_pago) = CURDATE()";
    $result = $db->query($query);
    
    return $result->fetch_assoc()['total'] ?? 0;
}

// Procesar búsqueda de estudiante
$estudiante = null;
$mensaje_error = '';
$mensaje_exito = '';

// Procesar registro de pago
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['buscar_cedula'])) {
        // Búsqueda de estudiante
        $cedula = trim($_POST['cedula']);
        
        if (!empty($cedula)) {
            $estudiante = buscarEstudiantePorCedulaPagos($cedula);
            
            if (!$estudiante) {
                $mensaje_error = "No se encontró ningún estudiante con la cédula: " . htmlspecialchars($cedula);
            }
        } else {
            $mensaje_error = "Por favor, ingrese una cédula para buscar.";
        }
    }
    elseif (isset($_POST['registrar_pago'])) {
        // Registro de pago
        $estudiante_id = (int)$_POST['estudiante_id'];
        $tipo_pago = (int)$_POST['tipo_pago'];
        $otro_concepto = trim($_POST['otro_concepto']);
        $monto = (float)$_POST['monto'];
        $observaciones = trim($_POST['observaciones']);
        $registrado_por = $_SESSION['user']['id'];
        
        if ($monto > 0) {
            if (registrarPago($estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por)) {
                $mensaje_exito = "Pago registrado exitosamente.";
                $estudiante = null; // Limpiar formulario después de registro exitoso
            } else {
                $mensaje_error = "Error al registrar el pago. Por favor, intente nuevamente.";
            }
        } else {
            $mensaje_error = "El monto debe ser mayor a cero.";
        }
    }
}

// Obtener tipos de pago
$tipos_pago = obtenerTiposPago();

// Obtener pagos del día
$pagos_del_dia = obtenerPagosDelDia();
$total_pagos_dia = obtenerTotalPagosDelDia();
?>

<div class="container-fluid">
    <h2 class="my-4">Registro de Pagos</h2>
    
    <?php if (!empty($mensaje_exito)): ?>
        <div class="alert alert-success"><?= $mensaje_exito ?></div>
    <?php endif; ?>
    
    <?php if (!empty($mensaje_error)): ?>
        <div class="alert alert-danger"><?= $mensaje_error ?></div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Formulario de búsqueda y registro -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Buscar Estudiante y Registrar Pago</h5>
                </div>
                <div class="card-body">
                    <!-- Formulario de búsqueda -->
                    <form method="POST" class="mb-4">
                        <div class="form-group">
                            <label for="cedula">Cédula del Estudiante:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="cedula" name="cedula" 
                                       placeholder="Ej: V12345678" value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : '' ?>" required>
                                <div class="input-group-append">
                                    <button type="submit" name="buscar_cedula" class="btn btn-success">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Formulario de registro de pago (solo visible si se encontró estudiante) -->
                    <?php if ($estudiante): ?>
                    <form method="POST">
                        <input type="hidden" name="estudiante_id" value="<?= $estudiante['id'] ?>">
                        
                        <div class="form-group">
                            <label>Estudiante Encontrado:</label>
                            <div class="alert alert-info">
                                <strong><?= htmlspecialchars($estudiante['nombre']) ?></strong><br>
                                Cédula: <?= htmlspecialchars($estudiante['idusuario']) ?><br>
                                Carrera: <?= htmlspecialchars($estudiante['carrera']) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_pago">Tipo de Pago:</label>
                            <select class="form-control" id="tipo_pago" name="tipo_pago" required>
                                <option value="">-- Seleccionar Tipo de Pago --</option>
                                <?php foreach ($tipos_pago as $tipo): ?>
                                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['tipopago']) ?></option>
                                <?php endforeach; ?>
                                <option value="0">Otro concepto</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="otro_concepto_group" style="display: none;">
                            <label for="otro_concepto">Especificar Otro Concepto:</label>
                            <input type="text" class="form-control" id="otro_concepto" name="otro_concepto" 
                                   placeholder="Especifique el concepto de pago">
                        </div>
                        
                        <div class="form-group">
                            <label for="monto">Monto:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number" class="form-control" id="monto" name="monto" 
                                       step="0.01" min="0.01" required placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="observaciones">Referencia:</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" 
                                      rows="3" placeholder="Referencia del pago"></textarea>
                        </div>
                        
                        <button type="submit" name="registrar_pago" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-money-bill-wave"></i> Registrar Pago
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Lista de pagos del día -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5>Pagos Registrados Hoy - <?= date('d/m/Y') ?></h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary">
                        <strong>Total del día: $<?= number_format($total_pagos_dia, 2, ',', '.') ?></strong>
                    </div>
                    
                    <?php if (!empty($pagos_del_dia)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Hora</th>
                                    <th>Estudiante</th>
                                    <th>Cédula</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Referencia</th>
                                    <th>Registrado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos_del_dia as $pago): ?>
                                <tr>
                                    <td><?= date('H:i', strtotime($pago['fecha_pago'])) ?></td>
                                    <td><?= htmlspecialchars($pago['nombre_estudiante']) ?></td>
                                    <td><?= htmlspecialchars($pago['cedula']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($pago['nombre_tipo_pago']) ?>
                                        <?php if (!empty($pago['otro_concepto'])): ?>
                                            <br><small>(<?= htmlspecialchars($pago['otro_concepto']) ?>)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">$<?= number_format($pago['monto'], 2, ',', '.') ?></td>
                                    <td><?= !empty($pago['observaciones']) ? htmlspecialchars($pago['observaciones']) : 'N/A' ?></td>
                                    <td><?= htmlspecialchars($pago['nombre_registrador']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No se han registrado pagos hoy.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campo "Otro concepto" según selección
document.getElementById('tipo_pago').addEventListener('change', function() {
    const otroConceptoGroup = document.getElementById('otro_concepto_group');
    const otroConceptoInput = document.getElementById('otro_concepto');
    
    if (this.value === '0') {
        otroConceptoGroup.style.display = 'block';
        otroConceptoInput.setAttribute('required', 'required');
    } else {
        otroConceptoGroup.style.display = 'none';
        otroConceptoInput.removeAttribute('required');
        otroConceptoInput.value = '';
    }
});

// Formatear monto automáticamente
document.getElementById('monto').addEventListener('blur', function() {
    if (this.value) {
        this.value = parseFloat(this.value).toFixed(2);
    }
});
</script>

<?php include("includes/footer.php"); ?>