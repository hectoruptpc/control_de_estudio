<?php
// 1. Configuración inicial
error_reporting(E_ALL);
ini_set('display_errors', 1);


$titulopag = "Mis Secciones Inscritas";
include('../funciones/functions.php');
include("includes/head.php");

// 2. Verificación directa (si no es estudiante, va al login)
if (empty($_SESSION['user']['estudiante']) || $_SESSION['user']['estudiante'] != 1) {
    header('Location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();


// 4. Obtener ID de usuario seguro
$user_id = (int)$_SESSION['user']['id'];
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
                    Tus secciones activas
                </div>
                <div class="card-body">
                    <?php
                    global $db;
                    
                    // Consulta optimizada con el campo inicia
                    $query = "SELECT 
                                s.codigo_seccion,
                                c.nombre_carrera,
                                t.nombre_trayecto,
                                t.numero_trayecto,
                                pa.nombre_periodo,
                                es.fecha_inscripcion,
                                s.inicia
                              FROM estudiante_seccion es
                              JOIN secciones s ON es.id_seccion = s.id_seccion
                              JOIN carreras c ON s.id_carrera = c.id_carrera
                              JOIN trayectos t ON s.id_trayecto = t.id_trayecto
                              JOIN periodos_academicos pa ON s.id_periodo = pa.id_periodo
                              WHERE es.id_usuario = ?
                              AND es.estatus = 'activo'
                              ORDER BY pa.fecha_inicio DESC";

                    $stmt = $db->prepare($query);
                    
                    if ($stmt) {
                        $stmt->bind_param("i", $user_id);
                        
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                echo '<div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Sección</th>
                                                    <th>Carrera</th>
                                                    <th>Trayecto</th>
                                                    <th>Nivel</th>
                                                    <th>Periodo</th>
                                                    <th>Fecha de Inicio</th>
                                                    <th>Inscrito el</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                            <td>'.htmlspecialchars($row['codigo_seccion']).'</td>
                                            <td>'.htmlspecialchars($row['nombre_carrera']).'</td>
                                            <td>'.htmlspecialchars($row['nombre_trayecto']).'</td>
                                            <td>'.htmlspecialchars($row['numero_trayecto']).'</td>
                                            <td>'.htmlspecialchars($row['nombre_periodo']).'</td>
                                            <td>'.date('d/m/Y H:i', strtotime($row['inicia'])).'</td>
                                            <td>'.date('d/m/Y', strtotime($row['fecha_inscripcion'])).'</td>
                                        </tr>';
                                }
                                
                                echo '</tbody></table></div>';
                            } else {
                                echo '<div class="alert alert-info">No estás inscrito en ninguna sección activa.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">Error al cargar secciones: '.htmlspecialchars($stmt->error).'</div>';
                        }
                        $stmt->close();
                    } else {
                        echo '<div class="alert alert-danger">Error en la consulta: '.htmlspecialchars($db->error).'</div>';
                    }
                    ?>
                </div>
                <div class="card-footer text-muted">
                    Usuario: <?= htmlspecialchars($_SESSION['user']['id'] ?? 'N/D') ?> | 
                    Actualizado: <?= date('d/m/Y H:i:s') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include("includes/footer.php");
?>