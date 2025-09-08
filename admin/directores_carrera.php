<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignar Carreras a Directores";
include('../funciones/functions.php');

// Verificar permisos de administrador
if (!isAdmin()) {
    header('location: ../usuario/home.php');
    exit();
}

// Procesar la asignación de carrera
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar_carrera'])) {
    $id_usuario = intval($_POST['id_usuario']);
    $id_carrera = intval($_POST['carrera']);
    
    if (asignarCarreraDirector($id_usuario, $id_carrera)) {
        $_SESSION['success'] = "Carrera asignada correctamente al director.";
    } else {
        $_SESSION['error'] = "Error al asignar la carrera al director.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Procesar la eliminación de asignación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_asignacion'])) {
    $id_usuario = intval($_POST['id_usuario']);
    
    if (eliminarAsignacionCarrera($id_usuario)) {
        $_SESSION['success'] = "Asignación de carrera eliminada correctamente.";
    } else {
        $_SESSION['error'] = "Error al eliminar la asignación de carrera.";
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Función para asignar carrera a director
function asignarCarreraDirector($id_usuario, $id_carrera) {
    global $db;
    
    $stmt = $db->prepare("UPDATE users SET carrera_di = ? WHERE id = ? AND usuario = 1");
    $stmt->bind_param("ii", $id_carrera, $id_usuario);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Error al asignar carrera: " . $db->error);
        $stmt->close();
        return false;
    }
}

// Función para eliminar asignación de carrera
function eliminarAsignacionCarrera($id_usuario) {
    global $db;
    
    $stmt = $db->prepare("UPDATE users SET carrera_di = NULL WHERE id = ? AND usuario = 1");
    $stmt->bind_param("i", $id_usuario);
    
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        error_log("Error al eliminar asignación de carrera: " . $db->error);
        $stmt->close();
        return false;
    }
}

// Función para obtener directores de carrera (solo usuario = 1)
function obtenerDirectoresDeCarrera() {
    global $db;
    
    $directores = [];
    $query = "SELECT u.id, u.nombre, u.username, u.email, u.carrera_di, c.nombre_carrera 
              FROM users u 
              LEFT JOIN carreras c ON u.carrera_di = c.id_carrera 
              WHERE u.usuario = 1 
              ORDER BY u.nombre ASC";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $directores[] = $row;
        }
        
        $stmt->close();
        return $directores;
    } else {
        error_log("Error al obtener directores: " . $db->error);
        return [];
    }
}

// Función para obtener usuarios que pueden ser directores (solo usuario = 1 sin carrera asignada)
function obtenerUsuariosParaDirectores() {
    global $db;
    
    $usuarios = [];
    $query = "SELECT id, nombre, username, email 
              FROM users 
              WHERE usuario = 1 
              AND (carrera_di IS NULL OR carrera_di = '' OR carrera_di = 0)
              ORDER BY nombre ASC";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        
        $stmt->close();
        return $usuarios;
    } else {
        error_log("Error al obtener usuarios para directores: " . $db->error);
        return [];
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
                        <i class="fas fa-chalkboard-teacher"></i> Asignar Carreras a Directores
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
                            En este módulo puede asignar carreras a los Directores de Carrera. 
                            Los directores solo podrán ver información relacionada con su carrera asignada en su módulo correspondiente.
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-plus-circle"></i> Asignar Nueva Carrera
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-group">
                                            <label for="usuario">Seleccionar Director de Carrera:</label>
                                            <select class="form-control" id="usuario" name="id_usuario" required>
                                                <option value="">-- Seleccionar Director --</option>
                                                <?php
                                                $usuarios = obtenerUsuariosParaDirectores();
                                                if (empty($usuarios)) {
                                                    echo '<option value="" disabled>No hay directores disponibles sin carrera asignada</option>';
                                                } else {
                                                    foreach ($usuarios as $usuario) {
                                                        echo '<option value="' . $usuario['id'] . '">' . 
                                                             htmlspecialchars($usuario['nombre']) . ' (' . 
                                                             htmlspecialchars($usuario['username']) . ')' . 
                                                             '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php if (empty($usuarios)): ?>
                                            <small class="form-text text-muted">
                                                Todos los directores de carrera ya tienen una carrera asignada.
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="carrera">Seleccionar Carrera:</label>
                                            <select class="form-control" id="carrera" name="carrera" required>
                                                <option value="">-- Seleccionar Carrera --</option>
                                                <?php
                                                $carreras = obtenerTodasLasCarreras();
                                                foreach ($carreras as $carrera) {
                                                    echo '<option value="' . $carrera['id'] . '">' . 
                                                         htmlspecialchars($carrera['nombre']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" name="asignar_carrera" class="btn btn-success btn-block" <?php echo empty($usuarios) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-save"></i> Asignar Carrera
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list"></i> Directores con Carrera Asignada
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p>Lista de directores de carrera (usuario = 1) con sus carreras asignadas:</p>
                                    
                                    <?php
                                    $directores = obtenerDirectoresDeCarrera();
                                    
                                    if (empty($directores)) {
                                        echo '<div class="alert alert-secondary">';
                                        echo '<p class="mb-0"><i class="fas fa-info-circle"></i> ';
                                        echo 'No hay directores de carrera registrados en el sistema.</p>';
                                        echo '</div>';
                                    } else {
                                        $directoresConAsignacion = array_filter($directores, function($director) {
                                            return !empty($director['carrera_di']) && $director['carrera_di'] != 0;
                                        });
                                        
                                        $directoresSinAsignacion = array_filter($directores, function($director) {
                                            return empty($director['carrera_di']) || $director['carrera_di'] == 0;
                                        });
                                        
                                        if (empty($directoresConAsignacion)) {
                                            echo '<div class="alert alert-warning">';
                                            echo '<p class="mb-0"><i class="fas fa-exclamation-triangle"></i> ';
                                            echo 'No hay directores con carreras asignadas.</p>';
                                            echo '</div>';
                                        } else {
                                            echo '<div class="table-responsive">';
                                            echo '<table class="table table-striped table-bordered">';
                                            echo '<thead class="thead-dark">';
                                            echo '<tr>';
                                            echo '<th>Nombre</th>';
                                            echo '<th>Usuario</th>';
                                            echo '<th>Carrera Asignada</th>';
                                            echo '<th>Acciones</th>';
                                            echo '</tr>';
                                            echo '</thead>';
                                            echo '<tbody>';
                                            
                                            foreach ($directoresConAsignacion as $director) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($director['nombre']) . '</td>';
                                                echo '<td>' . htmlspecialchars($director['username']) . '</td>';
                                                echo '<td>';
                                                if (!empty($director['nombre_carrera'])) {
                                                    echo htmlspecialchars($director['nombre_carrera']);
                                                } else {
                                                    echo '<span class="text-danger">Carrera no encontrada</span>';
                                                }
                                                echo '</td>';
                                                echo '<td>';
                                                echo '<form method="POST" class="d-inline">';
                                                echo '<input type="hidden" name="id_usuario" value="' . $director['id'] . '">';
                                                echo '<button type="submit" name="eliminar_asignacion" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Está seguro de que desea quitar esta asignación?\')">';
                                                echo '<i class="fas fa-times"></i> Quitar';
                                                echo '</button>';
                                                echo '</form>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                            
                                            echo '</tbody>';
                                            echo '</table>';
                                            echo '</div>';
                                        }
                                        
                                        // Mostrar directores sin asignación
                                        if (!empty($directoresSinAsignacion)) {
                                            echo '<div class="mt-4">';
                                            echo '<h6>Directores sin carrera asignada:</h6>';
                                            echo '<ul class="list-group">';
                                            foreach ($directoresSinAsignacion as $director) {
                                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                                echo htmlspecialchars($director['nombre']) . ' (' . htmlspecialchars($director['username']) . ')';
                                                echo '<span class="badge badge-warning badge-pill">Sin asignar</span>';
                                                echo '</li>';
                                            }
                                            echo '</ul>';
                                            echo '</div>';
                                        }
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