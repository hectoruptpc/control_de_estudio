<?php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    exit();
}

// Función para obtener los pagos por rango de fechas
function obtenerPagosPorRangoFechas($fecha_inicio, $fecha_fin) {
    global $db;
    
    $query = "SELECT p.*, u.nombre as nombre_estudiante, u.idusuario as cedula, 
                     tp.tipopago as nombre_tipo_pago,
                     ur.nombre as nombre_registrador
              FROM pagos p
              INNER JOIN users u ON p.estudiante_id = u.id
              INNER JOIN tipo_pago tp ON p.tipo_pago = tp.id
              INNER JOIN users ur ON p.registrado_por = ur.id
              WHERE DATE(p.fecha_pago) BETWEEN ? AND ?
              ORDER BY p.fecha_pago DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pagos = [];
    while ($row = $result->fetch_assoc()) {
        $pagos[] = $row;
    }
    
    return $pagos;
}

// Función para obtener el total de pagos por rango de fechas
function obtenerTotalPagosPorRangoFechas($fecha_inicio, $fecha_fin) {
    global $db;
    
    $query = "SELECT SUM(monto) as total FROM pagos WHERE DATE(fecha_pago) BETWEEN ? AND ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc()['total'] ?? 0;
}

if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    
    $pagos = obtenerPagosPorRangoFechas($fecha_inicio, $fecha_fin);
    $total_rango = obtenerTotalPagosPorRangoFechas($fecha_inicio, $fecha_fin);
    
    echo '<div class="alert alert-info">';
    echo '<strong>Pagos del ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)) . '</strong>';
    echo '<br>Total del período: <strong>$' . number_format($total_rango, 2, ',', '.') . '</strong>';
    echo '</div>';
    
    if (!empty($pagos)) {
        echo '<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">';
        echo '<table class="table table-bordered table-striped table-sm">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>Fecha</th>';
        echo '<th>Hora</th>';
        echo '<th>Estudiante</th>';
        echo '<th>Cédula</th>';
        echo '<th>Concepto</th>';
        echo '<th>Monto</th>';
        echo '<th>Referencia</th>';
        echo '<th>Registrado por</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $current_date = null;
        $daily_total = 0;
        $first_row = true;
        
        foreach ($pagos as $pago) {
            $pago_date = date('Y-m-d', strtotime($pago['fecha_pago']));
            
            // Mostrar total del día anterior si cambió la fecha
            if ($current_date !== null && $current_date !== $pago_date) {
                echo '<tr class="table-success">';
                echo '<td colspan="5" class="text-right"><strong>Total del día ' . date('d/m/Y', strtotime($current_date)) . ':</strong></td>';
                echo '<td class="text-right"><strong>$' . number_format($daily_total, 2, ',', '.') . '</strong></td>';
                echo '<td colspan="2"></td>';
                echo '</tr>';
                
                $daily_total = 0;
            }
            
            // Iniciar nuevo día
            if ($current_date !== $pago_date) {
                $current_date = $pago_date;
                if (!$first_row) {
                    echo '<tr>';
                    echo '<td colspan="8" class="bg-light"></td>';
                    echo '</tr>';
                }
                $first_row = false;
            }
            
            $daily_total += $pago['monto'];
            
            echo '<tr>';
            echo '<td>' . date('d/m/Y', strtotime($pago['fecha_pago'])) . '</td>';
            echo '<td>' . date('H:i', strtotime($pago['fecha_pago'])) . '</td>';
            echo '<td>' . htmlspecialchars($pago['nombre_estudiante']) . '</td>';
            echo '<td>' . htmlspecialchars($pago['cedula']) . '</td>';
            echo '<td>';
            echo htmlspecialchars($pago['nombre_tipo_pago']);
            if (!empty($pago['otro_concepto'])) {
                echo '<br><small>(' . htmlspecialchars($pago['otro_concepto']) . ')</small>';
            }
            echo '</td>';
            echo '<td class="text-right">$' . number_format($pago['monto'], 2, ',', '.') . '</td>';
            echo '<td>' . (!empty($pago['observaciones']) ? htmlspecialchars($pago['observaciones']) : 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($pago['nombre_registrador']) . '</td>';
            echo '</tr>';
        }
        
        // Mostrar total del último día
        if ($current_date !== null) {
            echo '<tr class="table-success">';
            echo '<td colspan="5" class="text-right"><strong>Total del día ' . date('d/m/Y', strtotime($current_date)) . ':</strong></td>';
            echo '<td class="text-right"><strong>$' . number_format($daily_total, 2, ',', '.') . '</strong></td>';
            echo '<td colspan="2"></td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-warning">No se encontraron pagos en el rango de fechas seleccionado.</div>';
    }
}
?>