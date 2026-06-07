<?php
// MOSTRAR ERRORES (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('pagos');

// Verificar autenticación y rol
if (!isLoggedIn() || !isAdmin()) {
    $_SESSION['msg'] = "Debes iniciar sesión como administrador para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Registro de Pagos";
include("includes/head.php");

// Función para buscar estudiante por cédula
function buscarEstudiantePorCedulaPagos($cedula) {
    global $db;
    
    $query = "SELECT u.id, u.nombre, u.idusuario, u.carrera, c.nombre_carrera 
              FROM users u 
              LEFT JOIN carreras c ON u.carrera = c.id_carrera 
              WHERE u.idusuario = ? AND u.estudiante = 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $estudiante = $result->fetch_assoc();
        return $estudiante;
    }
    
    return null;
}

// Función para registrar un pago
function registrarPago($estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por) {
    global $db;
    
    $query = "INSERT INTO pagos (estudiante_id, tipo_pago, otro_concepto, monto, fecha_pago, observaciones, registrado_por) 
              VALUES (?, ?, ?, ?, NOW(), ?, ?)";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("iisdsi", $estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por);
    
    if ($stmt->execute()) {
        $pago_id = $stmt->insert_id;
        
        $valores_nuevos = [
            'estudiante_id' => $estudiante_id,
            'tipo_pago' => $tipo_pago,
            'otro_concepto' => $otro_concepto,
            'monto' => $monto,
            'observaciones' => $observaciones,
            'registrado_por' => $registrado_por
        ];
        
        registrarAuditoria(
            "INSERT", 
            "pagos", 
            $pago_id, 
            null, 
            $valores_nuevos, 
            "Pagos", 
            "Registro de nuevo pago"
        );
        
        return true;
    }
    
    return false;
}

// Función para obtener todos los pagos
function obtenerTodosLosPagos() {
    global $db;
    
    $query = "SELECT p.*, u.nombre as nombre_estudiante, u.idusuario as cedula, 
                     tp.tipopago as nombre_tipo_pago,
                     ur.nombre as nombre_registrador
              FROM pagos p
              INNER JOIN users u ON p.estudiante_id = u.id
              INNER JOIN tipo_pago tp ON p.tipo_pago = tp.id
              INNER JOIN users ur ON p.registrado_por = ur.id
              ORDER BY p.fecha_pago DESC";
    
    $result = $db->query($query);
    
    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    
    return $pagos;
}

// Función para obtener el total de pagos del día actual
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
        $cedula = trim($_POST['cedula']);
        
        if (!empty($cedula)) {
            $estudiante = buscarEstudiantePorCedulaPagos($cedula);
            
            if (!$estudiante) {
                $mensaje_error = "No se encontró ningún estudiante con la cédula: " . htmlspecialchars($cedula);
                
                registrarAuditoria(
                    "SEARCH", 
                    "users", 
                    null, 
                    null, 
                    ['cedula' => $cedula], 
                    "Pagos", 
                    "Búsqueda fallida de estudiante por cédula"
                );
            } else {
                registrarAuditoria(
                    "SEARCH", 
                    "users", 
                    $estudiante['id'], 
                    null, 
                    ['cedula' => $cedula, 'estudiante' => $estudiante['nombre']], 
                    "Pagos", 
                    "Búsqueda exitosa de estudiante por cédula"
                );
            }
        } else {
            $mensaje_error = "Por favor, ingrese una cédula para buscar.";
        }
    }
    elseif (isset($_POST['registrar_pago'])) {
        $estudiante_id = (int)$_POST['estudiante_id'];
        $tipo_pago = (int)$_POST['tipo_pago'];
        $otro_concepto = trim($_POST['otro_concepto']);
        $monto = (float)$_POST['monto'];
        $observaciones = trim($_POST['observaciones']);
        $registrado_por = $_SESSION['user']['id'];
        
        if ($monto > 0) {
            if (registrarPago($estudiante_id, $tipo_pago, $otro_concepto, $monto, $observaciones, $registrado_por)) {
                $mensaje_exito = "Pago registrado exitosamente.";
                $estudiante = null;
            } else {
                $mensaje_error = "Error al registrar el pago. Por favor, intente nuevamente.";
                
                registrarAuditoria(
                    "ERROR", 
                    "pagos", 
                    null, 
                    null, 
                    [
                        'estudiante_id' => $estudiante_id,
                        'tipo_pago' => $tipo_pago,
                        'monto' => $monto
                    ], 
                    "Pagos", 
                    "Error al intentar registrar pago"
                );
            }
        } else {
            $mensaje_error = "El monto debe ser mayor a cero.";
        }
    }
    elseif (isset($_POST['editar_pago'])) {
        $pago_id = (int)$_POST['pago_id'];
        $tipo_pago = (int)$_POST['tipo_pago_edit'];
        $otro_concepto = trim($_POST['otro_concepto_edit']);
        $monto = (float)$_POST['monto_edit'];
        $observaciones = trim($_POST['observaciones_edit']);
        
        if ($monto > 0) {
            if (actualizarPago($pago_id, $tipo_pago, $otro_concepto, $monto, $observaciones)) {
                $mensaje_exito = "Pago actualizado exitosamente.";
            } else {
                $mensaje_error = "Error al actualizar el pago. Por favor, intente nuevamente.";
            }
        } else {
            $mensaje_error = "El monto debe ser mayor a cero.";
        }
    }
}

// Procesar eliminación de pago
if (isset($_GET['eliminar_pago'])) {
    $pago_id = (int)$_GET['eliminar_pago'];
    
    if (eliminarPago($pago_id)) {
        $mensaje_exito = "Pago eliminado exitosamente.";
    } else {
        $mensaje_error = "Error al eliminar el pago. Por favor, intente nuevamente.";
    }
}

// Obtener tipos de pago
$tipos_pago = obtenerTiposPago();

// Obtener todos los pagos
$todos_los_pagos = obtenerTodosLosPagos();
$total_pagos_hoy = obtenerTotalPagosDelDia();

// Fechas por defecto
$fecha_inicio = date('Y-m-d');
$fecha_fin = date('Y-m-d');
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <h2 class="my-4" style="font-size: 1.4rem;">Registro de Pagos</h2>
    
    <?php if (!empty($mensaje_exito)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $mensaje_exito ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($mensaje_error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $mensaje_error ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Formulario de búsqueda y registro -->
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-search"></i> Buscar Estudiante y Registrar Pago</h5>
                </div>
                <div class="card-body p-2 p-sm-3">
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
                    
                    <?php if ($estudiante): ?>
                    <form method="POST">
                        <input type="hidden" name="estudiante_id" value="<?= $estudiante['id'] ?>">
                        
                        <div class="form-group">
                            <label>Estudiante Encontrado:</label>
                            <div class="alert alert-info p-2">
                                <strong><i class="fas fa-user"></i> <?= htmlspecialchars($estudiante['nombre']) ?></strong><br>
                                <i class="fas fa-id-card"></i> Cédula: <?= htmlspecialchars($estudiante['idusuario']) ?><br>
                                <i class="fas fa-graduation-cap"></i> Carrera: <?= htmlspecialchars($estudiante['nombre_carrera']) ?>
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
                                    <span class="input-group-text">Bs</span>
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
                        
                        <button type="submit" name="registrar_pago" class="btn btn-success btn-block">
                            <i class="fas fa-money-bill-wave"></i> Registrar Pago
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Lista de todos los pagos -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Todos los Pagos Registrados</h5>
                </div>
                <div class="card-body p-2 p-sm-3">
                    <div class="alert alert-primary text-center">
                        <strong><i class="fas fa-chart-line"></i> Total del día de hoy:</strong> Bs<?= number_format($total_pagos_hoy, 2, ',', '.') ?>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Estudiante</th>
                                    <th>Cédula</th>
                                    <th>Concepto</th>
                                    <th>Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($todos_los_pagos)): ?>
                                    <?php foreach ($todos_los_pagos as $pago): ?>
                                        <tr>
                                            <td class="text-nowrap">
                                                <?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?><br>
                                                <small><?= date('H:i', strtotime($pago['fecha_pago'])) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($pago['nombre_estudiante']) ?></td>
                                            <td class="text-nowrap"><?= htmlspecialchars($pago['cedula']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($pago['nombre_tipo_pago']) ?>
                                                <?php if (!empty($pago['otro_concepto'])): ?>
                                                    <br><small class="text-muted">(<?= htmlspecialchars($pago['otro_concepto']) ?>)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-right">Bs<?= number_format($pago['monto'], 2, ',', '.') ?></td>
                                            <td class="text-center text-nowrap">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarPagoModal<?= $pago['id'] ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarPagoModal<?= $pago['id'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal Editar -->
                                        <div class="modal fade" id="editarPagoModal<?= $pago['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Pago</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="pago_id" value="<?= $pago['id'] ?>">
                                                            <div class="form-group">
                                                                <label>Estudiante:</label>
                                                                <div class="alert alert-info p-2"><?= htmlspecialchars($pago['nombre_estudiante']) ?> (<?= htmlspecialchars($pago['cedula']) ?>)</div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Tipo de Pago:</label>
                                                                <select class="form-control" name="tipo_pago_edit" required>
                                                                    <?php foreach ($tipos_pago as $tipo): ?>
                                                                        <option value="<?= $tipo['id'] ?>" <?= ($tipo['id'] == $pago['tipo_pago']) ? 'selected' : '' ?>><?= htmlspecialchars($tipo['tipopago']) ?></option>
                                                                    <?php endforeach; ?>
                                                                    <option value="0" <?= ($pago['tipo_pago'] == 0) ? 'selected' : '' ?>>Otro concepto</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Otro Concepto:</label>
                                                                <input type="text" class="form-control" name="otro_concepto_edit" value="<?= htmlspecialchars($pago['otro_concepto']) ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Monto:</label>
                                                                <input type="number" class="form-control" name="monto_edit" step="0.01" min="0.01" required value="<?= $pago['monto'] ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Referencia:</label>
                                                                <textarea class="form-control" name="observaciones_edit" rows="3"><?= htmlspecialchars($pago['observaciones']) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" name="editar_pago" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal Eliminar -->
                                        <div class="modal fade" id="eliminarPagoModal<?= $pago['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Eliminar Pago</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>¿Está seguro de eliminar este pago?</p>
                                                        <div class="alert alert-warning">
                                                            <strong><?= htmlspecialchars($pago['nombre_estudiante']) ?></strong><br>
                                                            Monto: Bs<?= number_format($pago['monto'], 2, ',', '.') ?>
                                                        </div>
                                                        <p class="text-danger">Esta acción no se puede deshacer.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <a href="?eliminar_pago=<?= $pago['id'] ?>" class="btn btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No se han registrado pagos.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('tipo_pago')?.addEventListener('change', function() {
    const otroGroup = document.getElementById('otro_concepto_group');
    if (this.value === '0') {
        otroGroup.style.display = 'block';
    } else {
        otroGroup.style.display = 'none';
    }
});
</script>

<style>
@media (max-width: 767.98px) {
    .table td, .table th {
        font-size: 0.75rem;
        padding: 0.4rem;
    }
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
    .text-nowrap {
        white-space: normal !important;
    }
}
</style>

<?php include("includes/footer.php"); ?>