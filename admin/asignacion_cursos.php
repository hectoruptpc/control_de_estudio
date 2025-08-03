<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Materias a Docentes";
include('../funciones/functions.php');



// Procesar asignación de materia
if(isset($_POST['asignar_materia'])) {
    $id_profesor = $_POST['id_profesor'];
    $id_materia = $_POST['id_materia'];
    
    // Verificar si ya existe la asignación
    $query = "SELECT * FROM profesor_materia WHERE id_usuario = ? AND id_materia = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $id_profesor, $id_materia);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 0) {
        // Insertar nueva asignación
        $insert = "INSERT INTO profesor_materia (id_usuario, id_materia, fecha_asignacion) VALUES (?, ?, NOW())";
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

// Procesar solicitud AJAX para recomendaciones (simulada en el mismo archivo)
$recomendaciones_html = '';
if(isset($_POST['ajax_request']) && $_POST['ajax_request'] == 'get_recomendaciones' && isset($_POST['id_materia'])) {
    $id_materia = $_POST['id_materia'];
    
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
            $recomendaciones_html .= "<li><strong>".$titulo['nombre']."</strong> (Prioridad: ".$titulo['prioridad'].")";
            
            // Buscar profesores con este título
            $query_profesores = "SELECT u.id, u.nombre, u.idusuario 
                                FROM users u
                                WHERE u.docente = 1 AND u.titulos LIKE '%".$titulo['id_titulo']."%'";
            $result_profesores = $db->query($query_profesores);
            
            if($result_profesores->num_rows > 0) {
                $recomendaciones_html .= "<ul>";
                while($profesor = $result_profesores->fetch_assoc()) {
                    $recomendaciones_html .= "<li>".$profesor['nombre']." (".$profesor['idusuario'].") - 
                          <a href='#' class='btn btn-sm btn-success asignar-rapido' data-profesor='".$profesor['id']."' data-materia='".$id_materia."'>Asignar</a></li>";
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
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chalkboard-teacher"></i> Asignar Materia
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
                                    
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row['id']."'>".$row['nombre']." (".$row['idusuario'].")</option>";
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
                                    
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row['id_materia']."'>".$row['nombre_materia']." (".$row['cod_materia'].")</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" name="asignar_materia" class="btn btn-primary">Asignar Materia</button>
                    </form>
                </div>
            </div>
            
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
                            
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='".$row['id_materia']."'>".$row['nombre_materia']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div id="resultados-recomendaciones" class="mt-3">
                        <?php 
                        // Mostrar recomendaciones si ya se seleccionó una materia (por ejemplo, al recargar la página)
                        if(isset($_GET['id_materia'])) {
                            $id_materia = $_GET['id_materia'];
                            // Simular el mismo código que en la parte AJAX
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
                                echo "<h5>Profesores recomendados para esta materia:</h5>";
                                echo "<p>Basado en títulos relacionados:</p>";
                                echo "<ul>";
                                
                                while($titulo = $titulos_relacionados->fetch_assoc()) {
                                    echo "<li><strong>".$titulo['nombre']."</strong> (Prioridad: ".$titulo['prioridad'].")";
                                    
                                    $query_profesores = "SELECT u.id, u.nombre, u.idusuario 
                                                        FROM users u
                                                        WHERE u.docente = 1 AND u.titulos LIKE '%".$titulo['id_titulo']."%'";
                                    $result_profesores = $db->query($query_profesores);
                                    
                                    if($result_profesores->num_rows > 0) {
                                        echo "<ul>";
                                        while($profesor = $result_profesores->fetch_assoc()) {
                                            echo "<li>".$profesor['nombre']." (".$profesor['idusuario'].") - 
                                                  <a href='#' class='btn btn-sm btn-success asignar-rapido' data-profesor='".$profesor['id']."' data-materia='".$id_materia."'>Asignar</a></li>";
                                        }
                                        echo "</ul>";
                                    } else {
                                        echo "<p class='text-muted'>No hay profesores con este título.</p>";
                                    }
                                }
                                
                                echo "</ul>";
                            } else {
                                echo "<div class='alert alert-info'>No hay títulos relacionados registrados para esta materia.</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                success: function(response) {
                    $('#resultados-recomendaciones').html(response);
                },
                error: function() {
                    $('#resultados-recomendaciones').html('<div class="alert alert-danger">Error al cargar recomendaciones.</div>');
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
        
        // Seleccionar los valores en los dropdowns
        $('#id_profesor').val(id_profesor);
        $('#id_materia').val(id_materia);
        
        // Hacer scroll al formulario
        $('html, body').animate({
            scrollTop: $('.card-body form').offset().top
        }, 500);
    });
});
</script>

<?php include("includes/footer.php"); ?>