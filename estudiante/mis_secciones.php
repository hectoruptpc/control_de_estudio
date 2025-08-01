<?php
// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

// Título y includes
$titulopag = "Mis Secciones Inscritas";
include('../funciones/functions.php');

// SOLUCIÓN TEMPORAL - INICIO (eliminar después de pruebas)
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0) {
    $_SESSION['user_id'] = 2; // ID del usuario PRUEBA
    error_log("SOLUCIÓN TEMPORAL: Se ha forzado user_id = 2");
}
// SOLUCIÓN TEMPORAL - FIN

// Verificación de sesión MEJORADA
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Debug MEJORADO
error_log("===== DEBUG DE SESIÓN =====");
error_log("user_id: " . $_SESSION['user_id']);
error_log("current_profile: " . ($_SESSION['current_profile'] ?? 'NO DEFINIDO'));

// Verificación de perfil
if (!isset($_SESSION['current_profile']) || $_SESSION['current_profile'] !== 'estudiante') {
    header('Location: ../profile_selector.php');
    exit();
}

// Head HTML
include("includes/head.php");

// Obtener ID de usuario
$user_id = (int)$_SESSION['user_id'];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-4"><?= htmlspecialchars($titulopag) ?></h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($titulopag) ?></li>
            </ol>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-table mr-1"></i>
                    Secciones activas
                </div>
                <div class="card-body">
                    <?php
                    global $db;
                    
                    // Consulta SQL
                    $query = "SELECT 
                                s.codigo_seccion,
                                c.nombre_carrera,
                                t.nombre_trayecto,
                                t.numero_trayecto,
                                pa.nombre_periodo,
                                es.fecha_inscripcion
                              FROM estudiante_seccion es
                              JOIN secciones s ON es.id_seccion = s.id_seccion
                              JOIN carreras c ON s.id_carrera = c.id_carrera
                              JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                              JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
                              WHERE es.id_usuario = ?
                              AND es.estatus = 'activo'
                              ORDER BY es.fecha_inscripcion DESC";

                    $stmt = $db->prepare($query);
                    
                    if ($stmt === false) {
                        echo '<div class="alert alert-danger">Error en preparación: ' . htmlspecialchars($db->error) . '</div>';
                    } else {
                        $stmt->bind_param("i", $user_id);
                        
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                echo '<div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Código</th>
                                                    <th>Carrera</th>
                                                    <th>Trayecto</th>
                                                    <th>Nivel</th>
                                                    <th>Periodo</th>
                                                    <th>Inscrito el</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>' . htmlspecialchars($row['codigo_seccion']) . '</td>
                                            <td>' . htmlspecialchars($row['nombre_carrera']) . '</td>
                                            <td>' . htmlspecialchars($row['nombre_trayecto']) . '</td>
                                            <td>' . htmlspecialchars($row['numero_trayecto']) . '</td>
                                            <td>' . htmlspecialchars($row['nombre_periodo']) . '</td>
                                            <td>' . date('d/m/Y', strtotime($row['fecha_inscripcion'])) . '</td>
                                        </tr>';
                                }
                                
                                echo '</tbody></table></div>';
                            } else {
                                echo '<div class="alert alert-warning">
                                        No tienes secciones activas actualmente.
                                        <hr>
                                        <small class="text-muted">
                                            Debug: Usuario ID '.$user_id.'<br>
                                            <a href="javascript:location.reload()">Recargar página</a> | 
                                            <a href="../logout.php">Cerrar sesión</a>
                                        </small>
                                      </div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">Error al ejecutar: ' . htmlspecialchars($stmt->error) . '</div>';
                        }
                        $stmt->close();
                    }
                    ?>
                </div>
                <div class="card-footer text-muted">
                    Actualizado: <?= date('d/m/Y H:i:s') ?>
                    <?php if ($user_id == 2): ?>
                        <span class="badge badge-warning float-right">MODO PRUEBA: ID forzado a 2</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("includes/footer.php");
?>