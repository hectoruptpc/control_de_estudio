<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Materias a Docentes";
include('../funciones/functions.php');

// Verificar si el usuario es director de carrera
// Verificar autenticación y rol
if (!isLoggedIn() || !isUser()) {
    $_SESSION['msg'] = "Debes iniciar sesión como director de carrera para acceder";
    header('location: ../login.php');
    exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$carrera_director = $_SESSION['user']['carrera_di'];

// Obtener información de la carrera del director
$query_carrera = "SELECT id_carrera, nombre_carrera FROM carreras WHERE id_carrera = ?";
$stmt = $db->prepare($query_carrera);
$stmt->bind_param("s", $carrera_director);
$stmt->execute();
$result_carrera = $stmt->get_result();

if ($result_carrera->num_rows === 0) {
    die("<div class='alert alert-danger'>No se encontró información para la carrera del director.</div>");
}

$carrera_info = $result_carrera->fetch_assoc();

// Manejar petición AJAX para obtener materias por carrera (solo la del director)
if(isset($_GET['ajax']) && $_GET['ajax'] == 'materias_carrera') {
    header('Content-Type: application/json');
    
    $query = "SELECT m.id_materia, m.nombre_materia, m.cod_materia 
              FROM carrera_materia cm
              JOIN materias m ON cm.id_materia = m.id_materia
              WHERE cm.id_carrera = ? AND m.activa = 1
              ORDER BY cm.semestre, m.nombre_materia";
    
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $carrera_director);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $materias = array();
    while($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    
    echo json_encode($materias);
    exit();
}

// Procesar solicitud AJAX para recomendaciones (solo materias de la carrera del director)
if(isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'get_recomendaciones' && isset($_POST['id_materia'])) {
    $id_materia = intval($_POST['id_materia']);
    $recomendaciones_html = '';
    
    if($id_materia > 0) {
        // Verificar que la materia pertenezca a la carrera del director
        $query_verificar = "SELECT cm.id_carrera FROM carrera_materia cm WHERE cm.id_materia = ? AND cm.id_carrera = ?";
        $stmt = $db->prepare($query_verificar);
        $stmt->bind_param("is", $id_materia, $carrera_director);
        $stmt->execute();
        $verificar_result = $stmt->get_result();
        
        if($verificar_result->num_rows === 0) {
            $recomendaciones_html .= "<div class='alert alert-warning'>No tiene permisos para acceder a esta materia.</div>";
            echo $recomendaciones_html;
            exit();
        }
        
        // Obtener información de la materia
        $query_materia = "SELECT nombre_materia FROM materias WHERE id_materia = ?";
        $stmt = $db->prepare($query_materia);
        $stmt->bind_param("i", $id_materia);
        $stmt->execute();
        $materia_result = $stmt->get_result();
        
        if($materia_result->num_rows > 0) {
            $materia = $materia_result->fetch_assoc();
            $nombre_materia = $materia['nombre_materia'];
            
            // Primero: Buscar por títulos relacionados en titulo_materia
            $query_titulos = "SELECT tm.id_titulo, t.nombre, tm.prioridad 
                             FROM titulo_materia tm
                             JOIN titulos t ON tm.id_titulo = t.id
                             WHERE tm.id_materia = ?
                             ORDER BY tm.prioridad DESC";
            $stmt = $db->prepare($query_titulos);
            $stmt->bind_param("i", $id_materia);
            $stmt->execute();
            $titulos_relacionados = $stmt->get_result();
            
            if($titulos_relacionados->num_rows > 0) {
                $recomendaciones_html .= "<div class='recommendation-section'>";
                $recomendaciones_html .= "<h5><i class='fas fa-graduation-cap'></i> Profesores recomendados para $nombre_materia</h5>";
                $recomendaciones_html .= "<p class='text-muted'>Basado en títulos académicos relacionados:</p>";
                
                while($titulo = $titulos_relacionados->fetch_assoc()) {
                    $recomendaciones_html .= "<div class='recommendation-group mb-3'>";
                    $recomendaciones_html .= "<h6 class='recommendation-title'><strong>".htmlspecialchars($titulo['nombre'])."</strong> <span class='badge badge-primary'>Prioridad: ".$titulo['prioridad']."</span></h6>";
                    
                    // Buscar profesores con este título en titulos_obtenidos
                    $query_profesores = "SELECT u.id, u.nombre, u.idusuario, tit.titulo_obtenido, tit.instituto
                                        FROM users u
                                        JOIN titulos_obtenidos tit ON u.id = tit.id_usuario
                                        WHERE u.docente = 1 AND tit.titulo_obtenido LIKE ?
                                        ORDER BY u.nombre";
                    $stmt_prof = $db->prepare($query_profesores);
                    $like_param = "%".$titulo['nombre']."%";
                    $stmt_prof->bind_param("s", $like_param);
                    $stmt_prof->execute();
                    $profesores = $stmt_prof->get_result();
                    
                    if($profesores->num_rows > 0) {
                        $recomendaciones_html .= "<ul class='professor-list'>";
                        while($profesor = $profesores->fetch_assoc()) {
                            $recomendaciones_html .= "<li class='professor-item'>";
                            $recomendaciones_html .= "<div class='professor-info'>";
                            $recomendaciones_html .= "<strong>".htmlspecialchars($profesor['nombre'])."</strong> (".htmlspecialchars($profesor['idusuario']).")";
                            $recomendaciones_html .= "<div class='degree-info'><small>Título: ".htmlspecialchars($profesor['titulo_obtenido'])."</small></div>";
                            $recomendaciones_html .= "<div class='institute-info'><small>Institución: ".htmlspecialchars($profesor['instituto'])."</small></div>";
                            $recomendaciones_html .= "</div>";
                            $recomendaciones_html .= "<a href='#' class='btn btn-sm btn-success asignar-rapido' data-profesor='".$profesor['id']."' data-materia='$id_materia'>";
                            $recomendaciones_html .= "<i class='fas fa-plus-circle'></i> Asignar";
                            $recomendaciones_html .= "</a>";
                            $recomendaciones_html .= "</li>";
                        }
                        $recomendaciones_html .= "</ul>";
                    } else {
                        $recomendaciones_html .= "<div class='alert alert-light'>No hay profesores con este título específico.</div>";
                    }
                    
                    $recomendaciones_html .= "</div>"; // .recommendation-group
                }
                $recomendaciones_html .= "</div>"; // .recommendation-section
            }
            
            // Segundo: Búsqueda por palabras clave en el nombre de la materia
            $palabras_clave = explode(" ", $nombre_materia);
            $condiciones = [];
            $params = [];
            
            foreach($palabras_clave as $palabra) {
                if(strlen(trim($palabra)) > 3) { // Ignorar palabras muy cortas
                    $condiciones[] = "tit.titulo_obtenido LIKE ?";
                    $params[] = "%".trim($palabra)."%";
                }
            }
            
            if(!empty($condiciones)) {
                $query_palabras = "SELECT DISTINCT u.id, u.nombre, u.idusuario, tit.titulo_obtenido, tit.instituto
                                 FROM users u
                                 JOIN titulos_obtenidos tit ON u.id = tit.id_usuario
                                 WHERE u.docente = 1 AND (".implode(" OR ", $condiciones).")
                                 ORDER BY u.nombre";
                
                $stmt_palabras = $db->prepare($query_palabras);
                if($stmt_palabras) {
                    $tipos = str_repeat("s", count($params));
                    $stmt_palabras->bind_param($tipos, ...$params);
                    $stmt_palabras->execute();
                    $profesores_palabras = $stmt_palabras->get_result();
                    
                    if($profesores_palabras->num_rows > 0) {
                        $recomendaciones_html .= "<div class='recommendation-section mt-4'>";
                        $recomendaciones_html .= "<h5><i class='fas fa-search'></i> Otras coincidencias por título</h5>";
                        $recomendaciones_html .= "<p class='text-muted'>Profesores con títulos que contienen palabras clave de la materia:</p>";
                        $recomendaciones_html .= "<ul class='professor-list'>";
                        
                        while($profesor = $profesores_palabras->fetch_assoc()) {
                            $recomendaciones_html .= "<li class='professor-item'>";
                            $recomendaciones_html .= "<div class='professor-info'>";
                            $recomendaciones_html .= "<strong>".htmlspecialchars($profesor['nombre'])."</strong> (".htmlspecialchars($profesor['idusuario']).")";
                            $recomendaciones_html .= "<div class='degree-info'><small>Título: ".htmlspecialchars($profesor['titulo_obtenido'])."</small></div>";
                            $recomendaciones_html .= "<div class='institute-info'><small>Institución: ".htmlspecialchars($profesor['instituto'])."</small></div>";
                            $recomendaciones_html .= "</div>";
                            $recomendaciones_html .= "<a href='#' class='btn btn-sm btn-success asignar-rapido' data-profesor='".$profesor['id']."' data-materia='$id_materia'>";
                            $recomendaciones_html .= "<i class='fas fa-plus-circle'></i> Asignar";
                            $recomendaciones_html .= "</a>";
                            $recomendaciones_html .= "</li>";
                        }
                        
                        $recomendaciones_html .= "</ul>";
                        $recomendaciones_html .= "</div>"; // .recommendation-section
                    }
                }
            }
            
            if(empty($recomendaciones_html)) {
                $recomendaciones_html .= "<div class='alert alert-info'>No hay recomendaciones específicas para esta materia. Seleccione un profesor manualmente.</div>";
            }
        } else {
            $recomendaciones_html .= "<div class='alert alert-warning'>Materia no encontrada.</div>";
        }
    } else {
        $recomendaciones_html .= "<div class='alert alert-warning'>ID de materia no válido.</div>";
    }
    
    echo $recomendaciones_html;
    exit();
}

// Procesar asignación de materia
if(isset($_POST['asignar_materia'])) {
    $id_profesor = $_POST['id_profesor'] ?? null;
    $id_materia = $_POST['id_materia'] ?? null;
    
    if($id_profesor && $id_materia) {
        // Verificar que la materia pertenezca a la carrera del director
        $query_verificar = "SELECT cm.id_carrera FROM carrera_materia cm WHERE cm.id_materia = ? AND cm.id_carrera = ?";
        $stmt = $db->prepare($query_verificar);
        $stmt->bind_param("is", $id_materia, $carrera_director);
        $stmt->execute();
        $verificar_result = $stmt->get_result();
        
        if($verificar_result->num_rows === 0) {
            $mensaje = "<div class='alert alert-danger'>No tiene permisos para asignar esta materia.</div>";
        } else {
            // Verificar si ya existe la asignación
            $query = "SELECT * FROM docente_materia WHERE id_usuario = ? AND id_materia = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ii", $id_profesor, $id_materia);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows == 0) {
                // Insertar nueva asignación
                $insert = "INSERT INTO docente_materia (id_usuario, id_materia, fecha_asignacion) VALUES (?, ?, NOW())";
                $stmt = $db->prepare($insert);
                $stmt->bind_param("ii", $id_profesor, $id_materia);
                
                if($stmt->execute()) {
                    $mensaje = "<div class='alert alert-success'>Materia asignada correctamente.</div>";
                } else {
                    $mensaje = "<div class='alert alert-danger'>Error al asignar materia.</div>";
                }
            } else {
                $mensaje = "<div class='alert alert-warning'>Este profesor ya tiene asignada esta materia.</div>";
            }
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Datos incompletos para la asignación.</div>";
    }
}

// Procesar eliminación de asignación
if(isset($_GET['eliminar'])) {
    $id_asignacion = $_GET['eliminar'] ?? null;
    
    if($id_asignacion) {
        // Verificar que la asignación pertenezca a la carrera del director
        $query_verificar = "SELECT cm.id_carrera 
                           FROM docente_materia dm
                           JOIN carrera_materia cm ON dm.id_materia = cm.id_materia
                           WHERE dm.id = ? AND cm.id_carrera = ?";
        $stmt = $db->prepare($query_verificar);
        $stmt->bind_param("is", $id_asignacion, $carrera_director);
        $stmt->execute();
        $verificar_result = $stmt->get_result();
        
        if($verificar_result->num_rows === 0) {
            $mensaje = "<div class='alert alert-danger'>No tiene permisos para eliminar esta asignación.</div>";
        } else {
            $delete = "DELETE FROM docente_materia WHERE id = ?";
            $stmt = $db->prepare($delete);
            $stmt->bind_param("i", $id_asignacion);
            
            if($stmt->execute()) {
                $mensaje = "<div class='alert alert-success'>Asignación eliminada correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Error al eliminar asignación.</div>";
            }
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>ID de asignación no válido.</div>";
    }
}

// Procesar actualización de asignación
if(isset($_POST['actualizar_asignacion'])) {
    $id_asignacion = $_POST['id_asignacion'] ?? null;
    $id_materia = $_POST['id_materia'] ?? null;
    
    if($id_asignacion && $id_materia) {
        // Verificar que la nueva materia pertenezca a la carrera del director
        $query_verificar = "SELECT cm.id_carrera FROM carrera_materia cm WHERE cm.id_materia = ? AND cm.id_carrera = ?";
        $stmt = $db->prepare($query_verificar);
        $stmt->bind_param("is", $id_materia, $carrera_director);
        $stmt->execute();
        $verificar_result = $stmt->get_result();
        
        if($verificar_result->num_rows === 0) {
            $mensaje = "<div class='alert alert-danger'>No tiene permisos para asignar esta materia.</div>";
        } else {
            // Verificar si la nueva asignación ya existe
            $query_check = "SELECT id_usuario FROM docente_materia WHERE id = ?";
            $stmt = $db->prepare($query_check);
            $stmt->bind_param("i", $id_asignacion);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                $asignacion = $result->fetch_assoc();
                $id_usuario = $asignacion['id_usuario'];
                
                $query_exists = "SELECT id FROM docente_materia WHERE id_usuario = ? AND id_materia = ? AND id != ?";
                $stmt = $db->prepare($query_exists);
                $stmt->bind_param("iii", $id_usuario, $id_materia, $id_asignacion);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($result->num_rows == 0) {
                    $update = "UPDATE docente_materia SET id_materia = ? WHERE id = ?";
                    $stmt = $db->prepare($update);
                    $stmt->bind_param("ii", $id_materia, $id_asignacion);
                    
                    if($stmt->execute()) {
                        $mensaje = "<div class='alert alert-success'>Asignación actualizada correctamente.</div>";
                    } else {
                        $mensaje = "<div class='alert alert-danger'>Error al actualizar asignación.</div>";
                    }
                } else {
                    $mensaje = "<div class='alert alert-warning'>Este profesor ya tiene asignada esta materia.</div>";
                }
            } else {
                $mensaje = "<div class='alert alert-danger'>Asignación no encontrada.</div>";
            }
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Datos incompletos para la actualización.</div>";
    }
}

// Obtener TODAS las asignaciones actuales (no solo las de la carrera del director)
$query_asignaciones = "SELECT dm.id, u.nombre as nombre_profesor, u.idusuario, m.nombre_materia, m.cod_materia, 
                      c.nombre_carrera, dm.fecha_asignacion, dm.id_materia, cm.id_carrera
                      FROM docente_materia dm
                      JOIN users u ON dm.id_usuario = u.id
                      JOIN materias m ON dm.id_materia = m.id_materia
                      JOIN carrera_materia cm ON m.id_materia = cm.id_materia
                      JOIN carreras c ON cm.id_carrera = c.id_carrera
                      ORDER BY u.nombre, m.nombre_materia";
$asignaciones = $db->query($query_asignaciones);

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulopag; ?></h1>
            <div class="alert alert-info">
                <strong>Director de Carrera:</strong> <?php echo htmlspecialchars($_SESSION['user']['nombre']); ?><br>
                <strong>Carrera:</strong> <?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?>
            </div>
            
            <?php if(isset($mensaje)) echo $mensaje; ?>
            
            <!-- Modal de Confirmación para Eliminar -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>¿Estás seguro de eliminar esta asignación?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <a id="confirmDeleteButton" href="#" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Asignaciones Actuales -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-list"></i> Asignaciones Actuales - Todas las Carreras
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> Solo puede editar o eliminar asignaciones de su propia carrera: <strong><?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?></strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Docente</th>
                                    <th>Materia</th>
                                    <th>Código</th>
                                    <th>Carrera</th>
                                    <th>Fecha Asignación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($asignaciones && $asignaciones->num_rows > 0): ?>
                                    <?php while($asignacion = $asignaciones->fetch_assoc()): 
                                        $es_mi_carrera = ($asignacion['id_carrera'] == $carrera_director);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($asignacion['nombre_profesor']); ?> (<?php echo htmlspecialchars($asignacion['idusuario']); ?>)</td>
                                        <td><?php echo htmlspecialchars($asignacion['nombre_materia']); ?></td>
                                        <td><?php echo htmlspecialchars($asignacion['cod_materia']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($asignacion['nombre_carrera']); ?>
                                            <?php if($es_mi_carrera): ?>
                                                <span class="badge badge-success ml-1">Mi carrera</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($asignacion['fecha_asignacion'])); ?></td>
                                        <td class="action-buttons">
                                            <?php if($es_mi_carrera): ?>
                                                <button class="btn btn-sm btn-primary editar-asignacion" 
                                                        data-toggle="modal" 
                                                        data-target="#modalEditar"
                                                        data-id="<?php echo $asignacion['id']; ?>"
                                                        data-profesor="<?php echo htmlspecialchars($asignacion['nombre_profesor']); ?>"
                                                        data-materia="<?php echo htmlspecialchars($asignacion['nombre_materia']); ?>"
                                                        data-id-materia="<?php echo $asignacion['id_materia']; ?>">
                                                    <i class="fas fa-edit"></i> Cambiar
                                                </button>
                                                
                                                <button class="btn btn-sm btn-danger eliminar-asignacion" 
                                                        data-toggle="modal" 
                                                        data-target="#confirmDeleteModal"
                                                        data-id="<?php echo $asignacion['id']; ?>">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">No permitido</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay asignaciones registradas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal para editar asignación -->
            <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalEditarLabel">Editar Asignación</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post" action="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Docente:</label>
                                    <input type="text" class="form-control" id="nombre_profesor_modal" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Materia Actual:</label>
                                    <input type="text" class="form-control" id="nombre_materia_modal" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="nueva_materia">Nueva Materia:</label>
                                    <select class="form-control" id="nueva_materia" name="id_materia" required>
                                        <option value="">-- Seleccione una materia --</option>
                                        <?php
                                        // Obtener materias de la carrera del director
                                        $query_materias = "SELECT m.id_materia, m.nombre_materia, m.cod_materia 
                                                          FROM carrera_materia cm
                                                          JOIN materias m ON cm.id_materia = m.id_materia
                                                          WHERE cm.id_carrera = ? AND m.activa = 1
                                                          ORDER BY cm.semestre, m.nombre_materia";
                                        $stmt = $db->prepare($query_materias);
                                        $stmt->bind_param("s", $carrera_director);
                                        $stmt->execute();
                                        $materias_result = $stmt->get_result();
                                        
                                        while($materia = $materias_result->fetch_assoc()): 
                                        ?>
                                            <option value="<?php echo $materia['id_materia']; ?>">
                                                <?php echo htmlspecialchars($materia['nombre_materia'] . ' (' . $materia['cod_materia'] . ')'); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <input type="hidden" id="id_asignacion" name="id_asignacion">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" name="actualizar_asignacion" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sección para Asignar Nueva Materia -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chalkboard-teacher"></i> Asignar Nueva Materia - <?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?>
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="id_profesor">Seleccionar Profesor:</label>
                                <select class="form-control" id="id_profesor" name="id_profesor" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php
                                    $query = "SELECT id, idusuario, nombre FROM users WHERE docente = 1 ORDER BY nombre";
                                    $result = $db->query($query);
                                    
                                    if($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row['id']."'>".htmlspecialchars($row['nombre'])." (".htmlspecialchars($row['idusuario']).")</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="carrera">Carrera:</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?>" readonly>
                                <input type="hidden" name="carrera" value="<?php echo $carrera_director; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="id_materia">Materia:</label>
                                <select class="form-control" id="id_materia" name="id_materia" required>
                                    <option value="">-- Seleccione una materia --</option>
                                    <?php
                                    // Obtener materias de la carrera del director
                                    $query_materias = "SELECT m.id_materia, m.nombre_materia, m.cod_materia 
                                                      FROM carrera_materia cm
                                                      JOIN materias m ON cm.id_materia = m.id_materia
                                                      WHERE cm.id_carrera = ? AND m.activa = 1
                                                      ORDER BY cm.semestre, m.nombre_materia";
                                    $stmt = $db->prepare($query_materias);
                                    $stmt->bind_param("s", $carrera_director);
                                    $stmt->execute();
                                    $materias_result = $stmt->get_result();
                                    
                                    while($materia = $materias_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $materia['id_materia']; ?>">
                                            <?php echo htmlspecialchars($materia['nombre_materia'] . ' (' . $materia['cod_materia'] . ')'); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" name="asignar_materia" class="btn btn-success">
                            <i class='fas fa-save'></i> Asignar Materia
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Sección de Recomendaciones -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-graduation-cap"></i> Recomendaciones por Títulos Académicos - <?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="carrera_recomendacion">Carrera:</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($carrera_info['nombre_carrera']); ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="materia_recomendacion">Materia:</label>
                            <select class="form-control" id="materia_recomendacion" name="materia_recomendacion">
                                <option value="">-- Seleccione una materia --</option>
                                <?php
                                // Obtener materias de la carrera del director
                                $query_materias = "SELECT m.id_materia, m.nombre_materia, m.cod_materia 
                                                  FROM carrera_materia cm
                                                  JOIN materias m ON cm.id_materia = m.id_materia
                                                  WHERE cm.id_carrera = ? AND m.activa = 1
                                                  ORDER BY cm.semestre, m.nombre_materia";
                                $stmt = $db->prepare($query_materias);
                                $stmt->bind_param("s", $carrera_director);
                                $stmt->execute();
                                $materias_result = $stmt->get_result();
                                
                                while($materia = $materias_result->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $materia['id_materia']; ?>">
                                        <?php echo htmlspecialchars($materia['nombre_materia'] . ' (' . $materia['cod_materia'] . ')'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div id="resultados-recomendaciones" class="mt-3">
                        <!-- Aquí se cargarán las recomendaciones via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.recommendation-section {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 20px;
    border-left: 4px solid #007bff;
}

.recommendation-group {
    margin-bottom: 15px;
}

.recommendation-title {
    color: #0056b3;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #dee2e6;
}

.professor-list {
    list-style: none;
    padding: 0;
}

.professor-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.3s;
}

.professor-item:hover {
    background-color: #f1f1f1;
}

.professor-info {
    flex-grow: 1;
    margin-right: 15px;
}

.degree-info, .institute-info {
    font-size: 0.85em;
    color: #6c757d;
}

.action-buttons {
    white-space: nowrap;
}

.action-buttons .btn {
    margin: 0 2px;
}

.badge-primary {
    background-color: #007bff;
}

#resultados-recomendaciones {
    min-height: 100px;
}
</style>

<script>
// Configurar modal de edición
$(document).on('click', '.editar-asignacion', function() {
    var id = $(this).data('id');
    var profesor = $(this).data('profesor');
    var materia = $(this).data('materia');
    var id_materia = $(this).data('id-materia');
    
    $('#id_asignacion').val(id);
    $('#nombre_profesor_modal').val(profesor);
    $('#nombre_materia_modal').val(materia);
    $('#nueva_materia').val(id_materia);
});

// Configurar modal de eliminación
$(document).on('click', '.eliminar-asignacion', function() {
    var id = $(this).data('id');
    $('#confirmDeleteButton').attr('href', '?eliminar=' + id);
});

// Cargar recomendaciones al cambiar la materia
$('#materia_recomendacion').change(function() {
    var id_materia = $(this).val();
    
    if(id_materia) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                ajax_request: 'get_recomendaciones',
                id_materia: id_materia
            },
            beforeSend: function() {
                $('#resultados-recomendaciones').html(
                    '<div class="text-center py-4">'+
                    '<div class="spinner-border text-primary" role="status">'+
                    '<span class="sr-only">Cargando...</span>'+
                    '</div>'+
                    '<p class="mt-2">Buscando profesores recomendados...</p>'+
                    '</div>'
                );
            },
            success: function(response) {
                $('#resultados-recomendaciones').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error en AJAX:", status, error);
                $('#resultados-recomendaciones').html(
                    '<div class="alert alert-danger">'+
                    'Error al cargar recomendaciones. Por favor intente nuevamente.'+
                    '</div>'
                );
            }
        });
    } else {
        $('#resultados-recomendaciones').html('');
    }
});

// Asignación rápida desde las recomendaciones
$(document).on('click', '.asignar-rapido', function(e) {
    e.preventDefault();
    var id_profesor = $(this).data('profesor');
    var id_materia = $(this).data('materia');
    
    if(id_profesor && id_materia) {
        // Seleccionar los valores en los dropdowns
        $('#id_profesor').val(id_profesor).trigger('change');
        $('#id_materia').val(id_materia).trigger('change');
        
        // Resaltar el formulario
        $('.card-header.bg-success').css('background-color', '#ffc107');
        setTimeout(function() {
            $('.card-header.bg-success').css('background-color', '#28a745');
        }, 1000);
        
        // Hacer scroll al formulario
        $('html, body').animate({
            scrollTop: $('.card-body form').offset().top - 20
        }, 800);
        
        // Mostrar notificación
        var alertDiv = $(
            '<div class="alert alert-info alert-dismissible fade show" role="alert">'+
            'Profesor y materia seleccionados. <strong>Por favor confirme la asignación.</strong>'+
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
            '<span aria-hidden="true">&times;</span></button></div>'
        );
        
        $('.card-body form').before(alertDiv);
        
        setTimeout(function() {
            alertDiv.alert('close');
        }, 5000);
    }
});
</script>

<?php include("includes/footer.php"); ?>