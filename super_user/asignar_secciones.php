<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Secciones a Docentes";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('asig_secciones');

// Manejar petición AJAX para obtener materias del docente
if(isset($_GET['ajax']) && $_GET['ajax'] == 'materias_docente' && isset($_GET['id_docente'])) {
    header('Content-Type: application/json');
    $id_docente = $db->real_escape_string($_GET['id_docente']);
    
    $query = "SELECT m.id_materia, m.nombre_materia, m.cod_materia 
              FROM docente_materia dm
              JOIN materias m ON dm.id_materia = m.id_materia
              WHERE dm.id_usuario = '$id_docente'
              ORDER BY m.nombre_materia";
    
    $result = $db->query($query);
    $materias = array();
    
    while($row = $result->fetch_assoc()) {
        $materias[] = $row;
    }
    
    echo json_encode($materias);
    exit();
}

// Procesar formulario
if(isset($_POST['asignar'])) {
    $id_usuario = $db->real_escape_string($_POST['id_usuario']);
    $id_seccion = $db->real_escape_string($_POST['id_seccion']);
    $id_materia = $db->real_escape_string($_POST['id_materia']);
    
    // Verificar si ya existe la asignación
    $query = "SELECT * FROM docente_seccion 
              WHERE id_usuario = '$id_usuario' 
              AND id_seccion = '$id_seccion'
              AND id_materia = '$id_materia'";
    $result = $db->query($query);
    
    if($result->num_rows > 0) {
        $mensaje = "<div class='alert alert-warning'>Este docente ya tiene asignada esta sección con esta materia.</div>";
    } else {
        // Insertar nueva asignación
        $query = "INSERT INTO docente_seccion (id_usuario, id_seccion, id_materia) 
                  VALUES ('$id_usuario', '$id_seccion', '$id_materia')";
        if($db->query($query)) {
            $mensaje = "<div class='alert alert-success'>Asignación realizada correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al asignar: ".$db->error."</div>";
        }
    }
}

// Eliminar asignación
if(isset($_GET['eliminar'])) {
    $id = $db->real_escape_string($_GET['eliminar']);
    $query = "DELETE FROM docente_seccion WHERE id_docente_seccion = '$id'";
    if($db->query($query)) {
        $mensaje = "<div class='alert alert-success'>Asignación eliminada correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar: ".$db->error."</div>";
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulopag; ?></h1>
            
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
                            <p>¿Está seguro de eliminar esta asignación?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <a id="confirmDeleteButton" href="#" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Asignar Nueva Sección a Docente
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="id_usuario">Docente:</label>
                                <select class="form-control" id="id_usuario" name="id_usuario" required onchange="cargarMateriasDocente()">
                                    <option value="">Seleccione un docente</option>
                                    <?php
                                    $query = "SELECT id, idusuario, nombre FROM users WHERE docente = 1 ORDER BY nombre";
                                    $result = $db->query($query);
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='".$row['id']."'>".$row['nombre']." (".$row['idusuario'].")</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="id_seccion">Sección:</label>
                                <select class="form-control" id="id_seccion" name="id_seccion" required>
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $query = "SELECT s.id_seccion, s.codigo_seccion, c.nombre_carrera 
                                              FROM secciones s
                                              LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
                                              WHERE s.estatus = 'activa' AND (c.activa = 1 OR c.activa IS NULL)
                                              ORDER BY c.nombre_carrera, s.codigo_seccion";
                                    $result = $db->query($query);
                                    
                                    if($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            $nombre_carrera = $row['nombre_carrera'] ?? 'Sin carrera asignada';
                                            echo "<option value='".$row['id_seccion']."'>".$row['codigo_seccion']." - ".$nombre_carrera."</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay secciones activas disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="id_materia">Materia:</label>
                                <select class="form-control" id="id_materia" name="id_materia" required disabled>
                                    <option value="">Primero seleccione un docente</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="asignar" class="btn btn-primary">Asignar Sección</button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Asignaciones Actuales
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Docente</th>
                                    <th>Sección</th>
                                    <th>Carrera</th>
                                    <th>Materia</th>
                                    <th>Fecha Asignación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT ds.id_docente_seccion, u.nombre AS docente, 
                                                 s.codigo_seccion, c.nombre_carrera, ds.fecha_asignacion,
                                                 m.nombre_materia, m.cod_materia
                                          FROM docente_seccion ds
                                          JOIN users u ON ds.id_usuario = u.id
                                          JOIN secciones s ON ds.id_seccion = s.id_seccion
                                          LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
                                          JOIN materias m ON ds.id_materia = m.id_materia
                                          WHERE s.estatus = 'activa' AND (c.activa = 1 OR c.activa IS NULL)
                                          ORDER BY ds.fecha_asignacion DESC";
                                $result = $db->query($query);
                                
                                if($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $nombre_carrera = $row['nombre_carrera'] ?? 'Sin carrera asignada';
                                        echo "<tr>
                                                <td>".$row['docente']."</td>
                                                <td>".$row['codigo_seccion']."</td>
                                                <td>".$nombre_carrera."</td>
                                                <td>".$row['nombre_materia']." (".$row['cod_materia'].")</td>
                                                <td>".$row['fecha_asignacion']."</td>
                                                <td>
                                                    <button class='btn btn-sm btn-danger eliminar-asignacion' 
                                                            data-toggle='modal' 
                                                            data-target='#confirmDeleteModal'
                                                            data-id='".$row['id_docente_seccion']."'>
                                                        Eliminar
                                                    </button>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No hay asignaciones registradas</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cargarMateriasDocente() {
    var idDocente = document.getElementById('id_usuario').value;
    var selectMaterias = document.getElementById('id_materia');
    
    if(idDocente === '') {
        selectMaterias.innerHTML = '<option value="">Primero seleccione un docente</option>';
        selectMaterias.disabled = true;
        return;
    }
    
    // Realizar petición AJAX para obtener las materias del docente
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '?ajax=materias_docente&id_docente=' + idDocente, true);
    
    xhr.onload = function() {
        if(this.status == 200) {
            try {
                var materias = JSON.parse(this.responseText);
                
                if(materias.length > 0) {
                    selectMaterias.innerHTML = '';
                    materias.forEach(function(materia) {
                        var option = document.createElement('option');
                        option.value = materia.id_materia;
                        option.textContent = materia.nombre_materia + ' (' + materia.cod_materia + ')';
                        selectMaterias.appendChild(option);
                    });
                    selectMaterias.disabled = false;
                } else {
                    selectMaterias.innerHTML = '<option value="">Este docente no tiene materias asignadas</option>';
                    selectMaterias.disabled = true;
                }
            } catch(e) {
                selectMaterias.innerHTML = '<option value="">Error al procesar materias</option>';
                selectMaterias.disabled = true;
            }
        } else {
            selectMaterias.innerHTML = '<option value="">Error al cargar materias</option>';
            selectMaterias.disabled = true;
        }
    };
    
    xhr.onerror = function() {
        selectMaterias.innerHTML = '<option value="">Error de conexión</option>';
        selectMaterias.disabled = true;
    };
    
    xhr.send();
}

// Configurar modal de eliminación
$(document).ready(function() {
    $(document).on('click', '.eliminar-asignacion', function() {
        var id = $(this).data('id');
        $('#confirmDeleteButton').attr('href', '?eliminar=' + id);
    });
});
</script>

<?php include("includes/footer.php"); ?>