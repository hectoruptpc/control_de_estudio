<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../funciones/functions.php'); 

    // Obtener el mes y año del POST
    $mes = isset($_POST['mes']) ? $_POST['mes'] : date('m'); // Si no se envió, usar mes actual
    $año = isset($_POST['año']) ? $_POST['año'] : date('Y'); // Si no se envió, usar año actual

// Consulta SQL 
$sql = "SELECT * FROM users WHERE id = ?";

// Preparar la consulta
$stmt = $db->prepare($sql);

// Vincular parámetros
$stmt->bind_param("i", $id_usua); // "i" indica que el parámetro es un entero

// Ejecutar la consulta
$stmt->execute();

// Obtener el resultado
$resultado = $stmt->get_result();

// Verificar si se encontró un usuario
if ($resultado->num_rows > 0) {
    // Obtener los datos como un arreglo asociativo
    $datosUsuario = $resultado->fetch_assoc();

    // Asignar los datos a variables PHP individuales
    $id = $datosUsuario['id'];
    $idusuario = $datosUsuario['idusuario']?? null;
    $nombre = $datosUsuario['nombre']?? null;
    $logo_comercio = $datosUsuario['logo_comercio']?? null;
    $nombre_comercio = $datosUsuario['nombre_comercio']?? null;
    $direccion_comercio = $datosUsuario['direccion_comercio']?? null;
    $email = $datosUsuario['email']?? null;
    $tlf = $datosUsuario['tlf']?? null;
    $cel = $datosUsuario['cel']?? null;
    $rif_comercio = $datosUsuario['rif_comercio']?? null;
    $web_comercio = $datosUsuario['web_comercio']?? null;





} else {
    // El usuario no se encontró, maneja el error como desees
    echo "No se encontró ningún usuario con ese ID";
}

      
         
               // Generar el HTML de la tabla
           $html = '
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
       <h1>REPORTE</h1>
       </div>
       </div>
       <div class="row">
       <div class="col">
       <span id="nombre_comercio">'. $nombre_comercio .'</span>
       </div>
       <div class="col">
       </div>
       </div>
       <div class="row">
       <div class="col">
        <span id="direccion_comercio">'. $direccion_comercio .'</span>
       </div>
       <div class="col">
       </div>
       </div>
       <div class="row">
       <div class="col">
        <strong>Telefono:</strong> <span id="telefono">' . $tlf . ' / ' . $cel . '</span>
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

if(isset($_POST['reporte'])) {
    $reporte = $_POST['reporte'];
  
    switch($reporte) {
case 'productoTerminado':

/*
SELECT 
 SUM(CASE WHEN descripcion = 0 THEN cantidad END) AS total 
 FROM inventario_producto_terminado 
 WHERE MONTH(fecha) = '$mes' AND YEAR(fecha) = '$año' AND id_usuario = '$id_usua'
 */

                $query = "SELECT
                p.id AS id_producto,
                p.nombre AS nombre_producto,
                p.tipo AS tipo_producto, 
                SUM(CASE
                WHEN ipt.descripcion = 1 THEN ipt.cantidad
                WHEN ipt.descripcion = 0 THEN -ipt.cantidad
                ELSE 0
                END) AS cantidad_disponible
                FROM productos p
                LEFT JOIN inventario_producto_terminado ipt ON p.id = ipt.id_producto 
                AND ipt.id_usuario = '$id_usua'
                AND MONTH(ipt.fecha) = $mes  -- Condición de mes dentro del JOIN
                AND YEAR(ipt.fecha) = $año  -- Condición de año dentro del JOIN 
                GROUP BY p.id, p.nombre, p.tipo 
                HAVING cantidad_disponible > 0";
                
                $resultado = $db->query($query);

                // Generar la tabla HTML 
                $html .= '<h2>Lista de Producto Terminado</h2>';
                if ($resultado->num_rows > 0) {
                    $html .= '<table class="table table-striped">';
                    $html .= '<thead>
                                <tr>
                                    <th>ID Producto</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Cantidad Disponible</th>
                                </tr>
                            </thead>';
                    $html .= '<tbody>';

                    while ($fila = $resultado->fetch_assoc()) {
                        $html .= '<tr>';
                        $html .= '<td>' . $fila['id_producto'] . '</td>';
                        $html .= '<td>' . $fila['nombre_producto'] . '</td>';
                        $html .= '<td>' . $fila['tipo_producto'] . '</td>';
                        $html .= '<td>' . $fila['cantidad_disponible'] . '</td>';
                        $html .= '</tr>';
                    }
                    
                    $html .= '</tbody>';
                    $html .= '</table>';
                } else {
                    $html .= '<div class="alert alert-warning" role="alert">
                                No hay productos terminados para mostrar.
                            </div>';
                }
                    echo $html;
                    break;
  
case 'ventasFecha':

                    // ----> INICIALIZAR $arreglo["data"] COMO ARRAY VACÍO <----
                    $arreglo = ['data' => []];  // <-- Importante
                
                    $query="SELECT
                    v.id,
                    v.id_control,
                    v.fecha,
                    c.cedula,
                    c.nombre AS nombre_cliente,
                    c.telefono1,
                    v.cantidad AS total_productos,
                    v.monto AS total_venta,
                    p.tipo,
                    p.nombre
                    FROM ventas v
                    JOIN clientes c ON v.id_cliente = c.id
                    JOIN precios pre ON v.id_producto = pre.id_producto AND v.id_usuario = pre.id_usuario
                    JOIN productos p ON v.id_producto = p.id -- Unión con la tabla productos
                    WHERE v.id_usuario = ?
                    AND MONTH(v.fecha) = ?
                    AND YEAR(v.fecha) = ?
                    ORDER BY v.fecha DESC;";
                    
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("iii", $id_usua, $mes, $año);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    while($data = $result->fetch_array(MYSQLI_ASSOC)) {
                        // Formatear la cantidad
                        $data['total_productos'] = formatearCantidad($data['total_productos'], $data['tipo']); 
                        $arreglo ["data"][] = $data;
                    }
                    
                    // Reemplazar valores nulos por cadenas vacías
                    foreach ($arreglo as $clave => $valor) {
                        foreach ($valor as $clave2 => $valor2) {
                            if ($valor2 === null) {
                                $arreglo[$clave][$clave2] = "";
                            }
                        }
                    }
                    $html .= '<h2>Ventas</h2>';
                        // ----> GENERAR HTML DE LA TABLA <----
                if (count($arreglo["data"]) > 0) { 
                    $totalVentas = 0;
                    $html .= '<table class="table table-striped">';
                    $html .= '<thead><tr>
                                    <th>ID Venta</th>
                                    <th>Control</th>
                                    <th>Fecha</th>
                                    <th>Cédula</th>
                                    <th>Cliente</th>
                                    
                                    <th>Total Productos</th>
                                    <th>Total Venta</th>
                                    <th>Producto</th>
                                </tr></thead>';
                    $html .= '<tbody>';

                    foreach ($arreglo["data"] as $row) {
                        $totalVentas += $row['total_venta'];
                        $html .= '<tr>';
                        $html .= '<td>' . $row['id'] . '</td>';
                        $html .= '<td>' . $row['id_control'] . '</td>';
                        $html .= '<td>' . $row['fecha'] . '</td>';
                        $html .= '<td>' . $row['cedula'] . '</td>';
                        $html .= '<td>' . $row['nombre_cliente'] . '</td>';
                       
                        $html .= '<td class="text-center">' . $row['total_productos'] . '</td>';
                        $html .= '<td class="text-right">' . $row['total_venta'] . ' $</td>';
                        $html .= '<td>' . $row['nombre'] . '</td>'; // Agregamos el tipo de producto
                        $html .= '</tr>';
                    }
                    $html .= '<tr class="font-weight-bold">';
                    $html .= '<td colspan="6" class="text-right">Total:</td>'; 
                    $html .= '<td class="text-right">' . number_format($totalVentas, 2, '.', ',') . ' $</td>'; 
                    $html .= '<td></td>'; // Celda vacía para el tipo de producto
                    $html .= '</tr>';
                    $html .= '</tbody></table>';

                } else {
                    // Si no hay datos, mostrar alerta
                    $html .= '<div class="alert alert-warning" role="alert">
                                No hay datos que mostrar.
                            </div>';
                }
                        echo $html;
        break;
  
        case 'materiaPrimaEntrada':

            $sqlEntrada = "SELECT ic.id, ic.id_componente, 
                                ic.cantidad AS cantidad_original,
                                ic.fecha, c.nombre AS nombre_componente,
                                c.tipo AS tipo_componente,
                                ic.id_control AS idcontrol
                            FROM inventario_componente ic 
                            JOIN componentes c ON ic.id_componente = c.codigo 
                            WHERE ic.id_usuario = ?
                              AND ic.descripcion = 1  -- Filtramos por INGRESO
                              AND MONTH(ic.fecha) = ?  
                              AND YEAR(ic.fecha) = ?"; 
        
            $stmtEntrada = $db->prepare($sqlEntrada);
            $stmtEntrada->bind_param("iii", $id_usua, $mes, $año);
            $stmtEntrada->execute();
            $resultadoEntrada = $stmtEntrada->get_result();
        
            // Generar tabla HTML para 'materiaPrimaEntrada'
            $html .= '<h2>Lista Entrada de Materia Prima</h2>';
            if ($resultadoEntrada->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>Componente</th> 
                            <th>Cantidad Original</th>
                            <th>Fecha</th>
                            <th>Nombre Componente</th>
                            <th>Control</th> 
                        </tr></thead>';
                $html .= '<tbody>';
                while ($fila = $resultadoEntrada->fetch_assoc()) {
                    $cantidadFormateada = formatearCantidad($fila['cantidad_original'], $fila['tipo_componente']);
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['id_componente'] . '</td>';
                    $html .= '<td>' .  $cantidadFormateada . '</td>';
                    $html .= '<td>' . $fila['fecha'] . '</td>';
                    $html .= '<td>' . $fila['nombre_componente'] . '</td>';
                    $html .= '<td>' . $fila['idcontrol'] . '</td>';
                    $html .= '</tr>';
                }
                $html .= '</tbody></table>';
            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay entradas de materia prima para mostrar.</div>';
            }
            echo $html;
        
            break; 
        
            
        
case 'materiaPrimaUsado': 
            
            $sqlUsado = "SELECT 
                            ic.id, 
                            ic.id_componente, 
                            ic.cantidad AS cantidad_original, 
                            ic.fecha, 
                            c.nombre AS nombre_componente,
                            c.tipo AS tipo_componente,
                            p.nombre AS nombre_producto_terminado 
                        FROM inventario_componente ic 
                        JOIN componentes c ON ic.id_componente = c.codigo 
                        LEFT JOIN inventario_producto_terminado ipt ON ic.id_control = ipt.id_control  
                        LEFT JOIN productos p ON ipt.id_producto = p.id  
                        WHERE ic.id_usuario = ?
                            AND ic.descripcion = 0  
                            AND MONTH(ic.fecha) = ?  
                            AND YEAR(ic.fecha) = ?;";
        
            $stmtUsado = $db->prepare($sqlUsado);
            $stmtUsado->bind_param("iii", $id_usua, $mes, $año);
            $stmtUsado->execute();
            $resultadoUsado = $stmtUsado->get_result();
           
            // Generar la tabla HTML para 'materiaPrimaUsado'
            $html .= '<h2>Lista de Materia Prima Usada</h2>';
            if ($resultadoUsado->num_rows > 0) {
                $html .= '<table class="table table-striped">';
                $html .= '<thead><tr>
                            <th>ID</th>
                            <th>Componente</th> 
                            <th>Cantidad</th>
                           
                            <th>Fecha</th>
                            <th>Nombre Componente</th>
                            <th>Producto</th>
                        </tr></thead>';
                $html .= '<tbody>';
                while ($fila = $resultadoUsado->fetch_assoc()) {

                    $cantidadFormateada = formatearCantidad($fila['cantidad_original'], $fila['tipo_componente']);
                    $html .= '<tr>';
                    $html .= '<td>' . $fila['id'] . '</td>';
                    $html .= '<td>' . $fila['id_componente'] . '</td>';
                    $html .= '<td>' .    $cantidadFormateada . '</td>';
                    //$html .= '<td>' . $fila['cantidad_final'] . '</td>';
                    $html .= '<td>' . $fila['fecha'] . '</td>';
                    $html .= '<td>' . $fila['nombre_componente'] . '</td>';
                    $html .= '<td>' . $fila['nombre_producto_terminado'] . '</td>'; 
                    $html .= '</tr>';
                }
                $html .= '</tbody></table>';
            } else {
                $html .= '<div class="alert alert-warning" role="alert">No hay materia prima usada para mostrar.</div>';
            }
            echo $html;
          
            break;
        






  
      default:
        echo 'Reporte no válido';
    }
  }
?>