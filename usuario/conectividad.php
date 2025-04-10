<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Sistema";
include('../funciones/functions.php');


// Verificar si el usuario ya tiene una API key
$sql = "SELECT api_key FROM users WHERE id = $id_usua"; 
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $apiKey = $row['api_key'];

    if (empty($apiKey)) {
        // Ejecutar algo si el valor de api_key está vacío
        // Tu código aquí
        // Generar una nueva API key
    $apiKey = uniqid('API_LIMP_');

    // Actualizar la tabla de usuarios con la nueva API key
    $sql = "UPDATE users SET api_key = '$apiKey' WHERE id = $id_usua";
    $db->query($sql);
    } else {
        // Ejecutar algo si el valor de api_key no está vacío
        // Tu código aquí
    }
} else {
    // Generar una nueva API key
    $apiKey = uniqid('API_LIMP_');

    // Actualizar la tabla de usuarios con la nueva API key
    $sql = "UPDATE users SET api_key = '$apiKey' WHERE id = $id_usua";
    $db->query($sql); 
}


// Lógica para renovar la API key si se ha enviado el formulario
if (isset($_POST['renovar_api_key'])) {
    // Generar una nueva API key
    $apiKey = uniqid('API_LIMP_');

    // Actualizar la tabla de usuarios con la nueva API key
    $sql = "UPDATE users SET api_key = '$apiKey' WHERE id = $id_usua";
    $db->query($sql);

    // Recargar la página para mostrar la nueva API key
    header("Location: conectividad.php");
    exit;
}
?>

<?php include("includes/head.php"); ?>

<!-- Page Content -->
<div class="container mt-5">
  <h1>Conectividad API</h1>

  <?php if ($apiKey): ?>
      <div class="alert alert-success" role="alert">
          Tu API key es:  <strong><?php echo $apiKey; ?></strong>
      </div>
       <!-- Formulario para renovar la API key -->
  <form method="post" action="conectividad.php">
  <div class="d-flex justify-content-center"> 
    <button type="submit" name="renovar_api_key" class="btn btn-warning"><i class="fas fa-key"></i> Renovar API Key</button>
  </div>
  </form>
  <?php else: ?>
      <p>Aún no has generado tu API key. <a href="conectividad.php" class="btn btn-primary">Generar API Key</a></p>
  <?php endif; ?>

  <!-- Manual de uso de la API -->
  <div class="card mt-4">
    <div class="card-header">
      <h2>Manual de Uso de la API</h2>
    </div>
    <div class="card-body">
        <!-- Contenido del manual -->
        <h3>Autenticación</h3>
        <p>Para acceder a los datos de la API, debes proporcionar tu API key en cada petición.</p>

        <h4>Encabezado HTTP</h4>
        <p>La forma más segura de enviar la API key es en un encabezado HTTP personalizado llamado `X-Api-Key`.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Lenguaje</th>
                        <th>Ejemplo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>cURL</td>
                        
                        <td><code>curl -H "X-Api-Key: <?php echo $apiKey; ?>" <?php echo $pag_web; ?>/usuario/api.php/clientes</code></td>
                    </tr>
                    <tr>
                        <td>JavaScript (Fetch API)</td>
                        <td><code>
                            fetch('<?php echo $pag_web; ?>/usuario/api.php/clientes', {<br>
                              headers: {<br>
                                'X-Api-Key': '<?php echo $apiKey; ?>'<br>
                              }<br>
                            })<br>
                            .then(response => response.json())<br>
                            .then(data => console.log(data));
                        </code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h4>Parámetro en la URL (menos seguro)</h4>
        <p>También puedes enviar la API key como un parámetro en la URL, aunque es menos seguro que usar el encabezado HTTP.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ejemplo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code><?php echo $pag_web; ?>/usuario/api.php/clientes/?api_key=<?php echo $apiKey; ?></code></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Rutas Disponibles</h3>

        <h4>Clientes</h4>
        <ul>
            <li>Obtener todos los clientes: <code>GET /api.php/clientes/?api_key=tu_api_key</code></li>
            <li>Obtener un cliente por ID: <code>GET /api.php/clientes/123?api_key=tu_api_key</code></li>
        </ul>

        <h4>Ventas</h4>
        <ul>
            <li>Obtener todas las ventas: <code>GET /api.php/ventas/?api_key=tu_api_key</code></li>
            <li>Obtener una venta por ID de control: <code>GET /api.php/ventas/123?api_key=tu_api_key</code></li>
            <li>Crear una nueva venta: <code>POST /api.php/ventas/?api_key=tu_api_key</code></li>
        </ul>

        <!-- Agrega las demás rutas aquí -->

        <h4>Ejemplo de JSON para crear una venta (POST /api.php/ventas)</h4>

        <pre><code>{
  "id_control": "123456",
  "id_cliente": "789",
  "id_producto": "456",
  "cantidad": "2",
  "monto": "20.00",
  "status": "pendiente",
  "nota": "Venta para cliente recurrente" 
}
        </code></pre>
    </div>
  </div>

</div>

<?php include("includes/footer.php"); ?>