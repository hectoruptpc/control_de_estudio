<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Respaldo de Base de Datos";
include('../funciones/functions.php');

// Verificar permisos de administrador
if (!isAdmin()) {
    header('location: ../usuario/home.php');
    exit();
}

// Procesar la solicitud de respaldo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['backup'])) {
    realizarRespaldo();
}

function realizarRespaldo() {
    global $db;
    
    // Obtener información del usuario
    $usuario = $_SESSION['user']['nombre'];
    $fecha = date('Y-m-d_H-i-s');
    
    // Nombre del archivo de respaldo con formato: respaldo_(usuario)_(fecha)
    $backup_file = 'respaldo_' . limpiarNombreArchivo($usuario) . '_' . $fecha . '.sql';
    
    // Registrar la descarga en la base de datos
    registrarDescargaRespaldo($usuario, $backup_file);
    
    // Cabecera para forzar descarga
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $backup_file . '"');
    
    // Obtener todas las tablas
    $tables = array();
    $result = $db->query('SHOW TABLES');
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    // Generar el SQL del respaldo
    $output = "-- Respaldo de Base de Datos\n";
    $output .= "-- Generado: " . date('Y-m-d H:i:s') . "\n";
    $output .= "-- Generado por: " . $usuario . "\n";
    $output .= "-- MySQL Server: " . $db->server_info . "\n\n";
    
    // Recorrer todas las tablas
    foreach ($tables as $table) {
        // Estructura de la tabla
        $output .= "--\n-- Estructura de tabla para la tabla `$table`\n--\n";
        $output .= "DROP TABLE IF EXISTS `$table`;\n";
        
        $create_table = $db->query("SHOW CREATE TABLE `$table`");
        $row = $create_table->fetch_row();
        $output .= $row[1] . ";\n\n";
        
        // Datos de la tabla
        $output .= "--\n-- Volcado de datos para la tabla `$table`\n--\n";
        
        $result = $db->query("SELECT * FROM `$table`");
        if ($result->num_rows > 0) {
            $output .= "INSERT IGNORE INTO `$table` VALUES\n";
            
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $values = array();
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . $db->real_escape_string($value) . "'";
                    }
                }
                $rows[] = "(" . implode(', ', $values) . ")";
            }
            
            $output .= implode(",\n", $rows) . ";\n\n";
        } else {
            $output .= "-- La tabla `$table` está vacía\n\n";
        }
    }
    
    // Escribir el output y finalizar
    echo $output;
    exit();
}

function limpiarNombreArchivo($nombre) {
    // Eliminar caracteres no permitidos en nombres de archivo
    $nombre = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombre);
    // Limitar la longitud
    $nombre = substr($nombre, 0, 50);
    return $nombre;
}

function registrarDescargaRespaldo($usuario, $nombre_archivo) {
    global $db;
    
    // Crear tabla de respaldos si no existe
    $crear_tabla = "
    CREATE TABLE IF NOT EXISTS respaldos_descargas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(100) NOT NULL,
        nombre_archivo VARCHAR(255) NOT NULL,
        fecha_descarga DATETIME DEFAULT CURRENT_TIMESTAMP,
        ip_address VARCHAR(45),
        user_agent TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    
    $db->query($crear_tabla);
    
    // Insertar registro de la descarga
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $stmt = $db->prepare("INSERT INTO respaldos_descargas (usuario, nombre_archivo, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $usuario, $nombre_archivo, $ip, $user_agent);
    $stmt->execute();
    $stmt->close();
}

function obtenerHistorialRespaldos() {
    global $db;
    
    // Verificar si la tabla existe
    $result = $db->query("SHOW TABLES LIKE 'respaldos_descargas'");
    if ($result->num_rows == 0) {
        return array();
    }
    
    // Obtener el historial de descargas
    $historial = array();
    $result = $db->query("SELECT * FROM respaldos_descargas ORDER BY fecha_descarga DESC LIMIT 10");
    
    while ($row = $result->fetch_assoc()) {
        $historial[] = $row;
    }
    
    return $historial;
}

include("includes/head.php");
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-database"></i> Respaldo de Base de Datos
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle"></i> Información importante
                        </h5>
                        <p class="mb-0">
                            Esta herramienta genera un respaldo completo de la base de datos. El archivo se descargará con el nombre: 
                            <code>respaldo_(usuario)_(fecha).sql</code>
                        </p>
                        <ul class="mb-0 mt-2">
                            <li>Estructura de tablas con <code>CREATE TABLE IF NOT EXISTS</code></li>
                            <li>Datos insertados con <code>INSERT IGNORE</code> para evitar duplicados</li>
                            <li>Se registra automáticamente quién y cuándo se descargó el respaldo</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-download"></i> Generar Respaldo
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p>Haga clic en el botón para generar y descargar un respaldo completo de la base de datos.</p>
                                    
                                    <form method="POST">
                                        <button type="submit" name="backup" class="btn btn-success btn-lg btn-block">
                                            <i class="fas fa-download"></i> Generar Respaldo Completo
                                        </button>
                                    </form>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> Usuario actual: <strong><?php echo $_SESSION['user']['nombre']; ?></strong>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-exclamation-triangle"></i> Consideraciones
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">Antes de generar el respaldo:</h6>
                                        <ul class="mb-0">
                                            <li>Asegúrese de tener suficiente espacio en disco</li>
                                            <li>Verifique que la base de datos esté funcionando correctamente</li>
                                            <li>El proceso puede tomar varios minutos dependiendo del tamaño</li>
                                            <li>Guarde el archivo en un lugar seguro y protegido</li>
                                        </ul>
                                    </div>

                                    <div class="mt-3">
                                        <h6>Estadísticas de la base de datos:</h6>
                                        <?php
                                        // Mostrar estadísticas de la base de datos
                                        $result = $db->query("SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema = DATABASE()");
                                        $table_count = $result->fetch_assoc()['table_count'];
                                        
                                        $result = $db->query("SELECT SUM(data_length + index_length) as size FROM information_schema.tables WHERE table_schema = DATABASE()");
                                        $db_size = $result->fetch_assoc()['size'];
                                        $db_size_mb = round($db_size / (1024 * 1024), 2);
                                        ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="card bg-light mb-2">
                                                    <div class="card-body text-center py-2">
                                                        <h6 class="mb-0">Tablas</h6>
                                                        <h4 class="mb-0 text-primary"><?php echo $table_count; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="card bg-light mb-2">
                                                    <div class="card-body text-center py-2">
                                                        <h6 class="mb-0">Tamaño</h6>
                                                        <h4 class="mb-0 text-primary"><?php echo $db_size_mb; ?> MB</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history"></i> Historial de Respaldos Recientes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $historial = obtenerHistorialRespaldos();
                                    
                                    if (empty($historial)) {
                                        echo '<div class="alert alert-secondary">';
                                        echo '<p class="mb-0"><i class="fas fa-info-circle"></i> ';
                                        echo 'No hay registros de respaldos descargados aún.</p>';
                                        echo '</div>';
                                    } else {
                                        echo '<div class="table-responsive">';
                                        echo '<table class="table table-striped table-bordered">';
                                        echo '<thead class="thead-dark">';
                                        echo '<tr>';
                                        echo '<th>Usuario</th>';
                                        echo '<th>Archivo</th>';
                                        echo '<th>Fecha de Descarga</th>';
                                        echo '<th>IP</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        
                                        foreach ($historial as $registro) {
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($registro['usuario']) . '</td>';
                                            echo '<td>' . htmlspecialchars($registro['nombre_archivo']) . '</td>';
                                            echo '<td>' . date('d/m/Y H:i:s', strtotime($registro['fecha_descarga'])) . '</td>';
                                            echo '<td>' . htmlspecialchars($registro['ip_address']) . '</td>';
                                            echo '</tr>';
                                        }
                                        
                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>