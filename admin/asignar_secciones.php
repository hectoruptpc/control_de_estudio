<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Secciones a Docentes";
include('../funciones/functions.php');

// CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('asig_secciones');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Manejar petición AJAX para obtener materias del docente POR CARRERA
if(isset($_GET['ajax']) && $_GET['ajax'] == 'materias_docente_carrera' && isset($_GET['id_docente']) && isset($_GET['id_carrera'])) {
    header('Content-Type: application/json');
    $id_docente = $db->real_escape_string($_GET['id_docente']);
    $id_carrera = $db->real_escape_string($_GET['id_carrera']);
    
    $materias = obtenerMateriasDocentePorCarrera($id_docente, $id_carrera);
    echo json_encode($materias);
    exit();
}

// Procesar formulario
if(isset($_POST['asignar'])) {
    $id_usuario = $db->real_escape_string($_POST['id_usuario']);
    $id_seccion = $db->real_escape_string($_POST['id_seccion']);
    $id_materia = $db->real_escape_string($_POST['id_materia']);
    
    $mensaje = procesarAsignacionSeccion($id_usuario, $id_seccion, $id_materia);
}

// Eliminar asignación
if(isset($_GET['eliminar'])) {
    $id = $db->real_escape_string($_GET['eliminar']);
    $mensaje = eliminarAsignacionSeccion($id);
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
                                <select class="form-control" id="id_usuario" name="id_usuario" required onchange="cargarMateriasPorCarrera()">
                                    <option value="">Seleccione un docente</option>
                                    <?php
                                    $docentes = obtenerDocentesActivos();
                                    foreach($docentes as $docente) {
                                        echo "<option value='".$docente['id']."'>".$docente['nombre']." (".$docente['idusuario'].")</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="id_seccion">Sección:</label>
                                <select class="form-control" id="id_seccion" name="id_seccion" required onchange="cargarMateriasPorCarrera()">
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $secciones = obtenerSeccionesActivas();
                                    if(count($secciones) > 0) {
                                        foreach($secciones as $seccion) {
                                            $id_carrera = $seccion['id_carrera'] ?? '0';
                                            $nombre_carrera = $seccion['nombre_carrera'] ?? 'Sin carrera asignada';
                                            echo "<option value='".$seccion['id_seccion']."' data-carrera='".$id_carrera."'>".$seccion['codigo_seccion']." - ".$nombre_carrera."</option>";
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
                                    <option value="">Primero seleccione docente y sección</option>
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
                                $asignaciones = obtenerAsignacionesSecciones();
                                if(count($asignaciones) > 0) {
                                    foreach($asignaciones as $row) {
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
function cargarMateriasPorCarrera() {
    var idDocente = document.getElementById('id_usuario').value;
    var selectSeccion = document.getElementById('id_seccion');
    var selectMaterias = document.getElementById('id_materia');
    
    // Obtener la carrera de la sección seleccionada
    var idCarrera = selectSeccion.options[selectSeccion.selectedIndex]?.getAttribute('data-carrera') || '0';
    
    if(idDocente === '' || selectSeccion.value === '') {
        selectMaterias.innerHTML = '<option value="">Primero seleccione docente y sección</option>';
        selectMaterias.disabled = true;
        return;
    }
    
    // Realizar petición AJAX para obtener las materias del docente por carrera
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '?ajax=materias_docente_carrera&id_docente=' + idDocente + '&id_carrera=' + idCarrera, true);
    
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
                    selectMaterias.innerHTML = '<option value="">Este docente no tiene materias para esta carrera</option>';
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