<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema";
include('../funciones/functions.php');

// Asignar la API key si está presente en la cabecera HTTP, 
// de lo contrario, comprobar el parámetro en la URL
$apiKey = isset($_SERVER['HTTP_X_API_KEY']) 
             ? $_SERVER['HTTP_X_API_KEY'] 
             : (isset($_GET['api_key']) ? $_GET['api_key'] : null);

// Verificar si se ha enviado una API key
if (!$apiKey) {
  http_response_code(401); // Unauthorized
  echo json_encode(['error' => 'API key requerida']);
  exit; 
}

// Validar la API key
$sql = "SELECT id FROM users WHERE api_key = '$apiKey'";
$result = $db->query($sql); 

if ($result->num_rows > 0) { // Verificar si se encontró un usuario
    $row = $result->fetch_assoc(); // Obtener la fila como array asociativo
    $id_usua = $row['id']; // Acceder al ID
  } else {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'API key inválida']);
    exit;
  }


// Definir el tipo de contenido como JSON
header('Content-Type: application/json');

// Obtener la ruta de la solicitud
$ruta = $_SERVER['REQUEST_URI'];

// Separar la ruta en partes
$partesRuta = explode('/', $ruta);

// Eliminar la parte inicial de la ruta
if ($partesRuta[0] === '' && $partesRuta[1] === 'jh' && $partesRuta[2] === 'usuario' && $partesRuta[3] === 'api.php') {
    array_shift($partesRuta);
    array_shift($partesRuta);
    array_shift($partesRuta);
    array_shift($partesRuta);
}

// Obtener el método de la solicitud (GET, POST, etc.)
$metodo = $_SERVER['REQUEST_METHOD'];

// Manejar las diferentes rutas y métodos
switch ($partesRuta[0]) {
    case 'clientes':
        // Manejar las solicitudes para la tabla "clientes"
        switch ($metodo) {
            case 'GET':
                // Obtener todos los clientes o un cliente específico por ID
                if (isset($partesRuta[1]) && !empty($partesRuta[1]) && $partesRuta[1] !== '?api_key=' . $apiKey) {
                    // Obtener cliente por ID
                    $idCliente = $partesRuta[1];
                    $sql = "SELECT * FROM clientes WHERE id = $idCliente AND idusuario = $id_usua";
                } else {
                    // Obtener todos los clientes
                    $sql = "SELECT * FROM clientes WHERE idusuario = $id_usua";
                }

                $resultado = $db->query($sql);
                $clientes = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $clientes[] = $fila;
                }

                echo json_encode($clientes);
                break;

            // ... otros métodos (POST, PUT, DELETE) para clientes ...

            default:
                // Método no permitido
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }
        break;

    case 'ventas':
       // Manejar las solicitudes para la tabla "ventas"
       switch ($metodo) {
        case 'GET':
            // Obtener todos los ventas o un cliente específico por ID
            if (isset($partesRuta[1]) && !empty($partesRuta[1]) && $partesRuta[1] !== '?api_key=' . $apiKey) {
                // Obtener cliente por ID
                $idCliente = $partesRuta[1];
                $sql = "SELECT * FROM ventas WHERE id_control = $idCliente AND id_usuario = $id_usua";
            } else {
                // Obtener todos los ventas
                $sql = "SELECT * FROM ventas WHERE id_usuario = $id_usua";
            }

            $resultado = $db->query($sql);
            $ventas = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ventas[] = $fila;
            }

            echo json_encode($ventas);
            break;

        // PARA CREAR UNA NUEVA VENTA

        case 'POST':
            // Crear una nueva venta
            $datosVenta = json_decode(file_get_contents('php://input'), true); 

            // Validar los datos recibidos (¡muy importante!)
            if (!isset($datosVenta['id_control'], $datosVenta['id_cliente'], $datosVenta['id_producto'], 
                  $datosVenta['cantidad'], $datosVenta['monto'], $datosVenta['status'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['error' => 'Faltan datos para crear la venta.']);
                break;
            }

            // Escapar los datos para prevenir inyección SQL
            $idControl = mysqli_real_escape_string($db, $datosVenta['id_control']);
            $idCliente = mysqli_real_escape_string($db, $datosVenta['id_cliente']);
            $idProducto = mysqli_real_escape_string($db, $datosVenta['id_producto']);
            $cantidad = mysqli_real_escape_string($db, $datosVenta['cantidad']);
            $monto = mysqli_real_escape_string($db, $datosVenta['monto']);
            $status = mysqli_real_escape_string($db, $datosVenta['status']);
            $nota = isset($datosVenta['nota']) ? mysqli_real_escape_string($db, $datosVenta['nota']) : '';

            // Construir la consulta SQL
            $sql = "INSERT INTO ventas (id_control, id_usuario, id_cliente, id_producto, cantidad, monto, status, nota, fecha) 
                    VALUES ('$idControl', '$id_usua', '$idCliente', '$idProducto', '$cantidad', '$monto', '$status', '$nota', NOW())";

            // Ejecutar la consulta
            if ($db->query($sql) === TRUE) {
                $idVenta = $db->insert_id;
                echo json_encode(['mensaje' => 'Venta creada correctamente', 'id' => $idVenta]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Error al crear la venta: ' . $db->error]);
            }
            break;

        default:
            // Método no permitido
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
        break;

    // ... otras rutas (precios, inventario_producto_terminado, productos) ...

        // SOLUCION A
        default:
        // Ruta raíz o no encontrada
        if ($partesRuta[0] === '/' || empty($partesRuta[0])) {
            // Manejar la ruta raíz 
            echo json_encode(['mensaje' => 'Bienvenido a la API']);
            $ruta_cm = "index.php"; 
            header("Location: " . $ruta_cm);

        } else {
            // Ruta no encontrada
            http_response_code(404);
            echo json_encode(['error' => 'Ruta no encontrada']);
        }
        break;
}

// Cierra la conexión a la base de datos
$db->close();
?>
