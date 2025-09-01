<?php
// ARCHIVO: buscar_pagos_fechas.php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    echo '<div class="alert alert-danger">Sesión expirada. Por favor, recargue la página.</div>';
    exit();
}

// Obtener tipos de pago
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

$tipos_pago = obtenerTiposPago();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
    $fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
    
    // Validar fechas
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        echo '<div class="alert alert-danger">Fechas inválidas.</div>';
        exit();
    }
    
    // Obtener pagos por rango de fechas
    $pagos = obtenerPagosPorRangoFechas($fecha_inicio, $fecha_fin);
    $total = obtenerTotalPagosPorRangoFechas($fecha_inicio, $fecha_fin);
    
    if (!empty($pagos)): 
    ?>
    <div class="alert alert-info">
        <strong>Total del período (<?= date('d/m/Y', strtotime($fecha_inicio)) ?> - <?= date('d/m/Y', strtotime($fecha_fin)) ?>): 
        $<?= number_format($total, 2, ',', '.') ?></strong>
    </div>
    
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estudiante</th>
                    <th>Cédula</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Referencia</th>
                    <th>Registrado por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $current_date = null;
                $daily_total = 0;
                $first_row = true;
                
                foreach ($pagos as $pago): 
                    $pago_date = date('Y-m-d', strtotime($pago['fecha_pago']));
                    
                    // Mostrar total del día anterior si cambió la fecha
                    if ($current_date !== null && $current_date !== $pago_date): ?>
                        <tr class="table-success">
                            <td colspan="5" class="text-right"><strong>Total del día <?= date('d/m/Y', strtotime($current_date)) ?>:</strong></td>
                            <td class="text-right"><strong>$<?= number_format($daily_total, 2, ',', '.') ?></strong></td>
                            <td colspan="3"></td>
                        </tr>
                        <?php 
                        $daily_total = 0;
                    endif;
                    
                    // Iniciar nuevo día
                    if ($current_date !== $pago_date): 
                        $current_date = $pago_date;
                        if (!$first_row): ?>
                            <tr>
                                <td colspan="9" class="bg-light"></td>
                            </tr>
                        <?php endif;
                        $first_row = false;
                    endif;
                    
                    $daily_total += $pago['monto'];
                ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
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
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editarPagoModal<?= $pago['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarPagoModal<?= $pago['id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <!-- Modal para editar pago -->
                        <div class="modal fade" id="editarPagoModal<?= $pago['id'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Pago</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="registro_pagos.php">
                                        <div class="modal-body">
                                            <input type="hidden" name="pago_id" value="<?= $pago['id'] ?>">
                                            
                                            <div class="form-group">
                                                <label>Estudiante:</label>
                                                <div class="alert alert-info p-2">
                                                    <strong><?= htmlspecialchars($pago['nombre_estudiante']) ?></strong><br>
                                                    Cédula: <?= htmlspecialchars($pago['cedula']) ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="tipo_pago_edit<?= $pago['id'] ?>">Tipo de Pago:</label>
                                                <select class="form-control" id="tipo_pago_edit<?= $pago['id'] ?>" name="tipo_pago_edit" required>
                                                    <option value="">-- Seleccionar Tipo de Pago --</option>
                                                    <?php foreach ($tipos_pago as $tipo): ?>
                                                        <option value="<?= $tipo['id'] ?>" <?= ($tipo['id'] == $pago['tipo_pago']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($tipo['tipopago']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                    <option value="0" <?= ($pago['tipo_pago'] == 0) ? 'selected' : '' ?>>Otro concepto</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group" id="otro_concepto_group_edit<?= $pago['id'] ?>" style="<?= ($pago['tipo_pago'] == 0) ? 'display: block;' : 'display: none;' ?>">
                                                <label for="otro_concepto_edit<?= $pago['id'] ?>">Especificar Otro Concepto:</label>
                                                <input type="text" class="form-control" id="otro_concepto_edit<?= $pago['id'] ?>" name="otro_concepto_edit" 
                                                       value="<?= htmlspecialchars($pago['otro_concepto']) ?>" placeholder="Especifique el concepto de pago">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="monto_edit<?= $pago['id'] ?>">Monto:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="monto_edit<?= $pago['id'] ?>" name="monto_edit" 
                                                           step="0.01" min="0.01" required value="<?= $pago['monto'] ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="observaciones_edit<?= $pago['id'] ?>">Referencia:</label>
                                                <textarea class="form-control" id="observaciones_edit<?= $pago['id'] ?>" name="observaciones_edit" 
                                                          rows="3"><?= htmlspecialchars($pago['observaciones']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="editar_pago" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal para eliminar pago -->
                        <div class="modal fade" id="eliminarPagoModal<?= $pago['id'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Eliminar Pago</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro de que desea eliminar este pago?</p>
                                        <div class="alert alert-warning">
                                            <strong>Estudiante:</strong> <?= htmlspecialchars($pago['nombre_estudiante']) ?><br>
                                            <strong>Monto:</strong> $<?= number_format($pago['monto'], 2, ',', '.') ?><br>
                                            <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?>
                                        </div>
                                        <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <a href="registro_pagos.php?eliminar_pago=<?= $pago['id'] ?>" class="btn btn-danger">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Mostrar total del último día -->
                <?php if ($current_date !== null): ?>
                <tr class="table-success">
                    <td colspan="5" class="text-right"><strong>Total del día <?= date('d/m/Y', strtotime($current_date)) ?>:</strong></td>
                    <td class="text-right"><strong>$<?= number_format($daily_total, 2, ',', '.') ?></strong></td>
                    <td colspan="3"></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-warning">
            No se encontraron pagos en el rango de fechas seleccionado.
        </div>
    <?php endif;
} else {
    echo '<div class="alert alert-danger">Método no permitido.</div>';
}
?>