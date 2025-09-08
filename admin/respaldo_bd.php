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

// Procesar la solicitud de eliminación de respaldo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_respaldo'])) {
    $id_respaldo = intval($_POST['id_respaldo']);
    eliminarRespaldo($id_respaldo);
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

function puedeEliminarRespaldo($fecha_descarga) {
    // Calcular si han pasado 90 días desde la fecha de descarga
    $fecha_descarga_obj = new DateTime($fecha_descarga);
    $fecha_actual = new DateTime();
    $diferencia = $fecha_actual->diff($fecha_descarga_obj);
    
    // Verificar si han pasado al menos 90 días
    return $diferencia->days >= 90;
}

function diasParaPoderEliminar($fecha_descarga) {
    // Calcular cuántos días faltan para poder eliminar el respaldo
    $fecha_descarga_obj = new DateTime($fecha_descarga);
    $fecha_actual = new DateTime();
    $diferencia = $fecha_actual->diff($fecha_descarga_obj);
    
    $dias_transcurridos = $diferencia->days;
    $dias_restantes = 90 - $dias_transcurridos;
    
    return max(0, $dias_restantes);
}

function eliminarRespaldo($id_respaldo) {
    global $db;
    
    // Primero verificar si el respaldo existe y si puede ser eliminado
    $stmt = $db->prepare("SELECT * FROM respaldos_descargas WHERE id = ?");
    $stmt->bind_param("i", $id_respaldo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "El respaldo no existe.";
        return false;
    }
    
    $respaldo = $result->fetch_assoc();
    
    // Verificar si han pasado 90 días desde la descarga
    if (!puedeEliminarRespaldo($respaldo['fecha_descarga'])) {
        $dias_restantes = diasParaPoderEliminar($respaldo['fecha_descarga']);
        $_SESSION['error'] = "No se puede eliminar el respaldo. Deben pasar 90 días desde su descarga. Faltan " . $dias_restantes . " días.";
        return false;
    }
    
    // Eliminar el registro de la base de datos
    $stmt = $db->prepare("DELETE FROM respaldos_descargas WHERE id = ?");
    $stmt->bind_param("i", $id_respaldo);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Respaldo eliminado correctamente.";
        return true;
    } else {
        $_SESSION['error'] = "Error al eliminar el respaldo: " . $db->error;
        return false;
    }
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
                    <?php
                    // Mostrar mensajes de éxito o error
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                        echo $_SESSION['success'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        unset($_SESSION['success']);
                    }
                    
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                        echo $_SESSION['error'];
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        echo '<span aria-hidden="true">&times;</span>';
                        echo '</button>';
                        echo '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    
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
                            <li>Los respaldos solo pueden eliminarse después de 90 días de su descarga</li>
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
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history"></i> Historial de Respaldos Recientes
                                    </h5>
                                    <small>Los respaldos solo pueden eliminarse después de 90 días</small>
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
                                        echo '<th>Acciones</th>';
                                        echo '</tr>';
                                        echo '</thead>';
                                        echo '<tbody>';
                                        
                                        foreach ($historial as $registro) {
                                            $puede_eliminar = puedeEliminarRespaldo($registro['fecha_descarga']);
                                            $dias_restantes = diasParaPoderEliminar($registro['fecha_descarga']);
                                            
                                            echo '<tr>';
                                            echo '<td>' . htmlspecialchars($registro['usuario']) . '</td>';
                                            echo '<td>' . htmlspecialchars($registro['nombre_archivo']) . '</td>';
                                            echo '<td>' . date('d/m/Y H:i:s', strtotime($registro['fecha_descarga'])) . '</td>';
                                            echo '<td>' . htmlspecialchars($registro['ip_address']) . '</td>';
                                            echo '<td>';
                                            
                                            if ($puede_eliminar) {
                                                echo '<form method="POST" class="d-inline">';
                                                echo '<input type="hidden" name="id_respaldo" value="' . $registro['id'] . '">';
                                                echo '<button type="submit" name="eliminar_respaldo" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Está seguro de que desea eliminar este registro de respaldo?\')">';
                                                echo '<i class="fas fa-trash"></i> Eliminar';
                                                echo '</button>';
                                                echo '</form>';
                                            } else {
                                                echo '<button type="button" class="btn btn-sm btn-secondary btn-eliminar" data-toggle="modal" data-target="#modalNoEliminar" data-dias="' . $dias_restantes . '">';
                                                echo '<i class="fas fa-trash"></i> Eliminar';
                                                echo '</button>';
                                            }
                                            
                                            echo '</td>';
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

<!-- Modal para respaldos que no se pueden eliminar -->
<div class="modal fade" id="modalNoEliminar" tabindex="-1" role="dialog" aria-labelledby="modalNoEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalNoEliminarLabel">
                    <i class="fas fa-exclamation-triangle"></i> No se puede eliminar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>No es posible eliminar este respaldo aún. Deben pasar 90 días desde su descarga para poder eliminarlo.</p>
                <p class="mb-0">Faltan <span id="dias-restantes" class="font-weight-bold"></span> días para que este respaldo pueda ser eliminado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

<script>
// Script para mostrar los días restantes en el modal
$(document).ready(function() {
    $('.btn-eliminar').on('click', function() {
        var diasRestantes = $(this).data('dias');
        $('#dias-restantes').text(diasRestantes);
    });
});
</script>

<?php include("includes/footer.php"); ?>