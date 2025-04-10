<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../funciones/functions.php'); 

if(isset($_REQUEST['idVenta'])) {
    $idVenta = $_REQUEST['idVenta'];

    $query = "SELECT 
                c.nombre AS nombre_cliente,
                c.cedula AS cedula,
                c.direccion AS direccion,
                c.telefono1 AS telefono1,
                c.telefono2 AS telefono2,
                v.id AS id_venta,
                v.fecha,
                v.id_control,
                p.nombre AS nombre_producto,
                v.cantidad AS cantidad_producto,
                v.monto AS monto_producto,
                p.tipo AS tipo_producto,
                u.rif_comercio AS rif_comercio,
                u.nombre_comercio AS nombre_comercio,
                u.direccion_comercio AS direccion_comercio,
                u.logo_comercio AS logo_comercio,
                u.web_comercio AS web_comercio,
                u.tlf AS tlf,
                u.cel AS cel
              FROM ventas v
              JOIN clientes c ON v.id_cliente = c.id
              JOIN productos p ON v.id_producto = p.id
              JOIN users u ON v.id_usuario = u.id
              WHERE v.id_control = (SELECT id_control FROM ventas WHERE id = ?)
              ORDER BY v.id_control, p.nombre";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $idVenta);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener los datos de la venta y los productos
    $dataVenta = [];
    $totalVenta = 0;
    while ($data = $result->fetch_assoc()) {
        $totalVenta += $data['monto_producto'];
        $dataVenta[] = $data; 
    }


    $logo_comercio = $dataVenta[0]['logo_comercio'];

    $html = '<!DOCTYPE html>
    <html>
    <head>
      <title>Nota de Entrega</title>
        <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="'.$pag_web.'/funciones/bootstrap-4.6.2-dist/css/bootstrap.min.css">

    </head>
    <body>

<div class="container">
    <div class="row">
        <div class="col-sm">';

        // Condicional para mostrar el logo
if (!empty($logo_comercio)) {
    $html .= '<img src="' . $logo_comercio . '" alt="Logo del comercio" width="100">';
}

   $html .= '
        </div>
        <div class="col-sm">
        <h1>NOTA DE ENTREGA</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
         <span id="nombre_comercio">'. $dataVenta[0]['nombre_comercio'] .'</span>
        </div>
        <div class="col">
        </div>
    </div>
     <div class="row">
        <div class="col">
         <span id="direccion_comercio">'. $dataVenta[0]['direccion_comercio'] .'</span>
        </div>
        <div class="col">
        </div>
    </div>
</div>

<br>

<div class="container">

        <div class="info">';

    // Mostrar la información de la venta
    if (!empty($dataVenta)) {

        $html .= '<div class="row">';
        $html .= '<div class="col"><strong>Cedula:</strong> <span id="cedula">' . $dataVenta[0]['cedula'] . '</span></div>';
        $html .= '<div class="col"><strong>ID Venta:</strong> <span id="id_venta">' . $dataVenta[0]['id_venta'] . '</span></div>';
        $html .= '<div class="w-100"></div>';
        $html .= '<div class="col"><strong>Cliente:</strong> <span id="cliente">' . $dataVenta[0]['nombre_cliente'] . '</span></div>';
        $html .= '<div class="col"><strong>ID Control:</strong> <span id="id_control">' . $dataVenta[0]['id_control'] . '</span></div>';
        $html .= '<div class="w-100"></div>';
        $html .= '<div class="col"><strong>Direccion:</strong> <span id="direccion">' . $dataVenta[0]['direccion'] . '</span></div>';
        $html .= '<div class="col"><strong>Fecha Actual:</strong> <span id="fecha">' . $fecha_actual_sistema . '</span></div>';
        $html .= '<div class="w-100"></div>';
        $html .= '<div class="col"><strong>Telefono:</strong> <span id="fecha">' . $dataVenta[0]['telefono1'] . ' / ' . $dataVenta[0]['telefono2'] . '</span></div>';
        $html .= '<div class="col"><strong>Fecha de la Venta:</strong> <span id="fecha">' . $dataVenta[0]['fecha'] . '</span></div>';
        $html .= '</div>';
    }

    $html .= '</div>

    <br>
        <table class="table"  id="tabla_productos">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>';

    // Mostrar los productos
    foreach ($dataVenta as $data) {
        $cantidadFormateada = formatearCantidad($data['cantidad_producto'], $data['tipo_producto']);
        $html .= '<tr>';
        $html .= '<td>' . $data['nombre_producto'] . '</td>';
        $html .= '<td>' . $cantidadFormateada . '</td>';
        $html .= '<td class="text-right">' . $data['monto_producto'] . ' $</td>';
        $html .= '</tr>';
    }

    $html .= '<tr>
      <td colspan="2" class="text-right"><strong>Total:</strong></td>
      <td class="text-right"><span id="total"> <strong>' . $totalVenta . ' $ </strong></span></td>
    </tr>
    </tbody></table>
    </body>
    </html>'
    ;

    echo $html;
}
?>