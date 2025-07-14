<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../funciones/functions.php');

// Obtener el mes y año del POST (si se envían)
$mes = isset($_POST['mes']) ? $_POST['mes'] : date('m');
$año = isset($_POST['año']) ? $_POST['año'] : date('Y');


             // Generar el HTML de la tabla
             $html = '
             <div class="container">
                 <div class="row">
                     <div class="col-sm">';
                     // Condicional para mostrar el logo
             if (!empty($logo_web)) {
                 $html .= $logo_empresa . $logo_web;
             }
             $html .= '
             </div>
             <div class="col-sm">
             <h1>REPORTE</h1>
             </div>
             </div>
             <div class="row">
             <div class="col">
             <span id="nombre_empresa">'. $nombre_empresa .'</span>
             </div>
             <div class="col">
             </div>
             </div>
             <div class="row">
             <div class="col">
              <span id="direccion_empresa">'. $direccion_empresa .'</span>
             </div>
             <div class="col">
             </div>
             </div>
             <div class="row">
             <div class="col">
              <strong>Rif:</strong> <span id="rif">' . $rif_empresa . '</span>
             </div>
             <div class="col">
             </div>
             </div>
             </div>
             <br>
             <div class="container">
                     <div class="info">';
             $html .= '<div class="row">';
             $html .= '<div class="col"></div>';
             $html .= '<div class="col"><strong>Fecha de Impresion:</strong> <span id="fecha">' . $fecha_actual_sistema . '</span></div>';
             $html .= '</div>';
             $html .= '<div class="row">';
             $html .= '<div class="col"></div>';
             $html .= '<div class="col"><strong>Fecha Solicitada:</strong> <span id="fecha2">' . $mes . '/' . $año . '</span></div>';
             $html .= '</div>';
                 $html .= '</div>';
                 $html .= '<br>';

if (isset($_POST['reporte'])) {
    $reporte = $_POST['reporte'];

    switch ($reporte) {
        // ... [Tus otros casos para reportes anteriores] ...

        case 'reportePagos':
            $sql = "SELECT 
                        id, 
                        user, 
                        monto, 
                        banco_origen, 
                        banco_destino, 
                        nro_transf, 
                        fecha_transf, 
                        fecha_pago 
                    FROM pagos 
                    WHERE MONTH(fecha_pago) = $mes AND YEAR(fecha_pago) = $año";
        
            $resultado = $db->query($sql);
        
            $html .= '<h2>Reporte de Pagos</h2>';
            if ($resultado->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Banco Origen</th>
                            <th>Banco Destino</th>
                            <th>Nro. Transf.</th>
                            <th>Fecha Transf.</th>
                            <th>Fecha Pago</th> 
                        </tr></thead>';
                $html .= '<tbody>';
        
                while ($fila = $resultado->fetch_assoc()) {
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['user'] . '</td>';
                    $html .= '<td>' . $fila['monto'] . '</td>'; 
                    $html .= '<td>' . $fila['banco_origen'] . '</td>'; 
                    $html .= '<td>' . $fila['banco_destino'] . '</td>';
                    $html .= '<td>' . $fila['nro_transf'] . '</td>';
                    $html .= '<td>' . $fila['fecha_transf'] . '</td>'; 
                    $html .= '<td>' . $fila['fecha_pago'] . '</td>'; 
                    $html .= '</tr>';
                }
        
                $html .= '</tbody></table>';
            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay pagos registrados para mostrar.</div>';
            }
            echo $html;
            break;

        case 'reporteUsuarios':
            $sql = "SELECT id, idusuario, nombre, email, tlf, fecha_ingreso 
FROM users 
WHERE MONTH(fecha_ingreso) = $mes AND YEAR(fecha_ingreso) = $año";
            $resultado = $db->query($sql);

            $html .= '<h2>Reporte de Usuarios</h2>';
            if ($resultado->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>ID Usuario</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha de Ingreso</th>
                        </tr></thead>';
                $html .= '<tbody>';

                while ($fila = $resultado->fetch_assoc()) {
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['idusuario'] . '</td>';
                    $html .= '<td>' . $fila['nombre'] . '</td>';
                    $html .= '<td>' . $fila['email'] . '</td>';
                    $html .= '<td>' . $fila['tlf'] . '</td>';
                    $html .= '<td>' . $fila['fecha_ingreso'] . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';
            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay usuarios para mostrar.</div>';
            }
            echo $html;
            break;

        case 'reporteBilletera':
            $sql = "SELECT b.id, u.nombre as nombre_usuario, b.monto, b.fecha 
                    FROM billetera b
                    JOIN users u ON b.id_usuario = u.id
                    WHERE MONTH(b.fecha) = $mes AND YEAR(b.fecha) = $año";
            $resultado = $db->query($sql);

            $html .= '<h2>Reporte de Billetera</h2>';
            $totalPositivo = 0;
            $totalNegativo = 0;
            
            if ($resultado->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr></thead>';
                $html .= '<tbody>';

                while ($fila = $resultado->fetch_assoc()) {
                    $monto = $fila['monto'];
                    
                    if ($monto > 0) {
                        $totalPositivo += $monto;
                    } else {
                        $totalNegativo += $monto;
                    }
                
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['nombre_usuario'] . '</td>';
                    $html .= '<td class="text-right">' . $monto . '</td>';
                    $html .= '<td>' . $fila['fecha'] . '</td>';
                    $html .= '</tr>';
                }

                // Calcular total disponible
                $totalDisponible = $totalPositivo + $totalNegativo;
                $html .= '<tr class="font-weight-bold">';
                $html .= '<td colspan="2" class="text-right">Total Disponible:</td>';
                $html .= '<td class="text-right">' . number_format($totalDisponible, 2, '.', ',') . '</td>';
                $html .= '<td></td>'; 
                $html .= '</tr>';

                $html .= '</tbody></table>';
            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay movimientos en la billetera para mostrar.</div>';
            }
            echo $html;
            break;

        case 'reporteVisitas':
            $sql = "SELECT v.id, u.nombre AS nombre_usuario, v.fecha_visita, v.web 
                    FROM visitas v 
                    JOIN users u ON v.id_usuario = u.id
                    WHERE MONTH(v.fecha_visita) = $mes AND YEAR(v.fecha_visita) = $año";
            $resultado = $db->query($sql);

            $html .= '<h2>Reporte de Visitas</h2>';
            if ($resultado->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Página Web</th>
                        </tr></thead>';
                $html .= '<tbody>';
                while ($fila = $resultado->fetch_assoc()) {
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['nombre_usuario'] . '</td>';
                    $html .= '<td>' . $fila['fecha_visita'] . '</td>';
                    $html .= '<td>' . $fila['web'] . '</td>';
                    $html .= '</tr>';
                }

                $html .= '</tbody></table>';


            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay visitas para mostrar.</div>';
            }
            echo $html;
            break;

        default:
            echo 'Reporte no válido';
    }
}
?>