<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Secciones a Docentes";
include('../funciones/functions.php');



// Procesar formulario
if(isset($_POST['asignar'])) {
    $id_usuario = $db->real_escape_string($_POST['id_usuario']);
    $id_seccion = $db->real_escape_string($_POST['id_seccion']);
    
    // Verificar si ya existe la asignación
    $query = "SELECT * FROM docente_seccion WHERE id_usuario = '$id_usuario' AND id_seccion = '$id_seccion'";
    $result = $db->query($query);
    
    if($result->num_rows > 0) {
        $mensaje = "<div class='alert alert-warning'>Este docente ya tiene asignada esta sección.</div>";
    } else {
        // Insertar nueva asignación
        $query = "INSERT INTO docente_seccion (id_usuario, id_seccion) VALUES ('$id_usuario', '$id_seccion')";
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
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Asignar Nueva Sección a Docente
                </div>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="id_usuario">Docente:</label>
                                <select class="form-control" id="id_usuario" name="id_usuario" required>
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
                            <div class="form-group col-md-6">
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
                                    <th>Fecha Asignación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT ds.id_docente_seccion, u.nombre AS docente, 
                                                 s.codigo_seccion, c.nombre_carrera, ds.fecha_asignacion
                                          FROM docente_seccion ds
                                          JOIN users u ON ds.id_usuario = u.id
                                          JOIN secciones s ON ds.id_seccion = s.id_seccion
                                          LEFT JOIN carreras c ON s.id_carrera = c.id_carrera
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
                                                <td>".$row['fecha_asignacion']."</td>
                                                <td>
                                                    <a href='?eliminar=".$row['id_docente_seccion']."' class='btn btn-sm btn-danger' onclick='return confirm(\"¿Está seguro de eliminar esta asignación?\")'>Eliminar</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No hay asignaciones registradas</td></tr>";
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

<?php include("includes/footer.php"); ?>