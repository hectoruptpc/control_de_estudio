<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Materias a Docentes";
include('../funciones/functions.php');

// Procesar asignación de materia
if(isset($_POST['asignar_materia'])) {
    $id_profesor = $_POST['id_profesor'] ?? null;
    $id_materia = $_POST['id_materia'] ?? null;
    
    if($id_profesor && $id_materia) {
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
    } else {
        $mensaje = "<div class='alert alert-danger'>Datos incompletos para la asignación.</div>";
    }
}

// Procesar eliminación de asignación
if(isset($_GET['eliminar'])) {
    $id_asignacion = $_GET['eliminar'] ?? null;
    
    if($id_asignacion) {
        $delete = "DELETE FROM docente_materia WHERE id = ?";
        $stmt = $db->prepare($delete);
        $stmt->bind_param("i", $id_asignacion);
        
        if($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Asignación eliminada correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al eliminar asignación.</div>";
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
    } else {
        $mensaje = "<div class='alert alert-danger'>Datos incompletos para la actualización.</div>";
    }
}

// Obtener todas las asignaciones actuales
$query_asignaciones = "SELECT dm.id, u.nombre as nombre_profesor, u.idusuario, m.nombre_materia, m.cod_materia, dm.fecha_asignacion, dm.id_materia 
                      FROM docente_materia dm
                      JOIN users u ON dm.id_usuario = u.id
                      JOIN materias m ON dm.id_materia = m.id_materia
                      ORDER BY u.nombre, m.nombre_materia";
$asignaciones = $db->query($query_asignaciones);

// Procesar solicitud AJAX para recomendaciones
if(isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'get_recomendaciones' && isset($_POST['id_materia'])) {
    $id_materia = intval($_POST['id_materia']);
    $recomendaciones_html = '';
    
    if($id_materia > 0) {
        // Obtener títulos relacionados con la materia ordenados por prioridad
        $query = "SELECT tm.id_titulo, t.nombre, tm.prioridad 
                  FROM titulo_materia tm
                  JOIN titulos t ON tm.id_titulo = t.id
                  WHERE tm.id_materia = ?
                  ORDER BY tm.prioridad DESC";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id_materia);
        $stmt->execute();
        $titulos_relacionados = $stmt->get_result();
        
        if($titulos_relacionados->num_rows > 0) {
            $recomendaciones_html .= "<h5>Profesores recomendados para esta materia:</h5>";
            $recomendaciones_html .= "<p>Basado en títulos relacionados:</p>";
            $recomendaciones_html .= "<ul>";
            
            while($titulo = $titulos_relacionados->fetch_assoc()) {
                $recomendaciones_html .= "<li><strong>".htmlspecialchars($titulo['nombre'])."</strong> (Prioridad: ".htmlspecialchars($titulo['prioridad']).")";
                
                // Buscar profesores con este título
                $query_profesores = "SELECT u.id, u.nombre, u.idusuario 
                                    FROM users u
                                    WHERE u.docente = 1 AND u.titulos LIKE ?";
                $stmt_prof = $db->prepare($query_profesores);
                $like_param = "%".$titulo['id_titulo']."%";
                $stmt_prof->bind_param("s", $like_param);
                $stmt_prof->execute();
                $result_profesores = $stmt_prof->get_result();
                
                if($result_profesores->num_rows > 0) {
                    $recomendaciones_html .= "<ul>";
                    while($profesor = $result_profesores->fetch_assoc()) {
                        $recomendaciones_html .= "<li>".htmlspecialchars($profesor['nombre'])." (".htmlspecialchars($profesor['idusuario']).") - 
                              <a href='#' class='btn btn-sm btn-success asignar-rapido' data-profesor='".htmlspecialchars($profesor['id'])."' data-materia='".htmlspecialchars($id_materia)."'>Asignar</a></li>";
                    }
                    $recomendaciones_html .= "</ul>";
                } else {
                    $recomendaciones_html .= "<p class='text-muted'>No hay profesores con este título.</p>";
                }
            }
            
            $recomendaciones_html .= "</ul>";
        } else {
            $recomendaciones_html .= "<div class='alert alert-info'>No hay títulos relacionados registrados para esta materia.</div>";
        }
    } else {
        $recomendaciones_html .= "<div class='alert alert-warning'>ID de materia no válido.</div>";
    }
    
    // Si es una solicitud AJAX, devolver solo las recomendaciones y terminar
    echo $recomendaciones_html;
    exit();
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4">Asignación de Materias a Docentes</h1>
            
            <?php if(isset($mensaje)) echo $mensaje; ?>
            
            <!-- Sección de Asignaciones Actuales -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-list"></i> Asignaciones Actuales
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Docente</th>
                                    <th>Materia</th>
                                    <th>Código</th>
                                    <th>Fecha Asignación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($asignaciones && $asignaciones->num_rows > 0): ?>
                                    <?php while($asignacion = $asignaciones->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($asignacion['nombre_profesor']); ?> (<?php echo htmlspecialchars($asignacion['idusuario']); ?>)</td>
                                        <td><?php echo htmlspecialchars($asignacion['nombre_materia']); ?></td>
                                        <td><?php echo htmlspecialchars($asignacion['cod_materia']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($asignacion['fecha_asignacion'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary editar-asignacion" 
                                                    data-toggle="modal" 
                                                    data-target="#modalEditar"
                                                    data-id="<?php echo htmlspecialchars($asignacion['id']); ?>"
                                                    data-profesor="<?php echo htmlspecialchars($asignacion['nombre_profesor']); ?>"
                                                    data-materia="<?php echo htmlspecialchars($asignacion['nombre_materia']); ?>"
                                                    data-id-materia="<?php echo htmlspecialchars($asignacion['id_materia']); ?>">
                                                <i class="fas fa-edit"></i> Cambiar Materia
                                            </button>
                                            
                                            <a href="?eliminar=<?php echo htmlspecialchars($asignacion['id']); ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Estás seguro de eliminar esta asignación?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay asignaciones registradas</td>
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
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarLabel">Editar Asignación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                        <option value="">-- Seleccione --</option>
                                        <?php
                                        $query = "SELECT id_materia, nombre_materia FROM materias WHERE activa = 1 ORDER BY nombre_materia";
                                        $result = $db->query($query);
                                        
                                        if($result && $result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='".htmlspecialchars($row['id_materia'])."'>".htmlspecialchars($row['nombre_materia'])."</option>";
                                            }
                                        }
                                        ?>
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
                <div class="card-header">
                    <i class="fas fa-chalkboard-teacher"></i> Asignar Nueva Materia
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
                                            echo "<option value='".htmlspecialchars($row['id'])."'>".htmlspecialchars($row['nombre'])." (".htmlspecialchars($row['idusuario']).")</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="id_materia">Seleccionar Materia:</label>
                                <select class="form-control" id="id_materia" name="id_materia" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php
                                    $query = "SELECT id_materia, cod_materia, nombre_materia FROM materias WHERE activa = 1 ORDER BY nombre_materia";
                                    $result = $db->query($query);
                                    
                                    if($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".htmlspecialchars($row['id_materia'])."'>".htmlspecialchars($row['nombre_materia'])." (".htmlspecialchars($row['cod_materia']).")</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" name="asignar_materia" class="btn btn-primary">Asignar Materia</button>
                    </form>
                </div>
            </div>
            
            <!-- Sección de Recomendaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-graduation-cap"></i> Recomendaciones por Títulos
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="materia_recomendacion">Seleccionar Materia para ver recomendaciones:</label>
                        <select class="form-control" id="materia_recomendacion" name="materia_recomendacion">
                            <option value="">-- Seleccione una materia --</option>
                            <?php
                            $query = "SELECT id_materia, nombre_materia FROM materias WHERE activa = 1 ORDER BY nombre_materia";
                            $result = $db->query($query);
                            
                            if($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($row['id_materia'])."'>".htmlspecialchars($row['nombre_materia'])."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div id="resultados-recomendaciones" class="mt-3">
                        <!-- Aquí se cargarán las recomendaciones via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Asegurarse de incluir jQuery y Bootstrap JS antes del script personalizado
if(!isset($no_footer_scripts)) {
    echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
    echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>';
}
?>

<script>
$(document).ready(function() {
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
                    $('#resultados-recomendaciones').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando recomendaciones...</div>');
                },
                success: function(response) {
                    $('#resultados-recomendaciones').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error en AJAX:", status, error);
                    $('#resultados-recomendaciones').html('<div class="alert alert-danger">Error al cargar recomendaciones. Por favor recarga la página.</div>');
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
            
            // Hacer scroll al formulario
            $('html, body').animate({
                scrollTop: $('.card-body form').offset().top - 20
            }, 500);
            
            // Mostrar mensaje
            alert('Profesor y materia seleccionados. Por favor confirma la asignación.');
        }
    });
    
    // Configurar modal de edición
    $(document).on('click', '.editar-asignacion', function() {
        var id = $(this).data('id');
        var profesor = $(this).data('profesor');
        var materia = $(this).data('materia');
        var id_materia = $(this).data('id-materia');
        
        if(id && profesor && materia && id_materia) {
            $('#id_asignacion').val(id);
            $('#nombre_profesor_modal').val(profesor);
            $('#nombre_materia_modal').val(materia);
            $('#nueva_materia').val(id_materia).trigger('change');
        } else {
            console.error("Datos incompletos para editar asignación");
            alert("Error al preparar la edición. Por favor recarga la página.");
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>