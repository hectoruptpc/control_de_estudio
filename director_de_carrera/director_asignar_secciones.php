<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Secciones a Docentes - Director";
include('../funciones/functions.php');

// CARGAR PERMISOS (IGUAL QUE EL ADMIN)
cargarPermisosUsuario();
verificarPermiso('asig_secciones');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener ID del usuario director
$id_usuario_director = $_SESSION['user']['id'] ?? 0;

// Obtener la carrera del director desde la tabla users (campo carrera_di)
$query_carrera = "SELECT carrera_di as id_carrera, 
                         (SELECT nombre_carrera FROM carreras WHERE id_carrera = users.carrera_di) as nombre_carrera
                  FROM users 
                  WHERE id = $id_usuario_director AND usuario = 1";
$result_carrera = $db->query($query_carrera);
$carrera_director = $result_carrera->fetch_assoc();

if (!$carrera_director || !$carrera_director['id_carrera']) {
    $_SESSION['msg'] = "No tiene una carrera asignada como director";
    header('Location: index.php');
    exit();
}

$id_carrera_director = $carrera_director['id_carrera'];

// Variables para mensajes
$tipo_mensaje = '';
$texto_mensaje = '';

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
    
    // Verificar que la sección pertenezca a la carrera del director
    $query_seccion = "SELECT id_carrera FROM secciones WHERE id_seccion = $id_seccion";
    $result_seccion = $db->query($query_seccion);
    $seccion = $result_seccion->fetch_assoc();
    
    if ($seccion['id_carrera'] != $id_carrera_director) {
        $tipo_mensaje = 'error';
        $texto_mensaje = 'No tiene permisos para asignar secciones de esta carrera';
    } else {
        $resultado = procesarAsignacionSeccion($id_usuario, $id_seccion, $id_materia);
        
        if($resultado['success']) {
            $tipo_mensaje = 'success';
            $texto_mensaje = $resultado['message'];
        } else {
            $tipo_mensaje = 'error';
            $texto_mensaje = $resultado['message'];
        }
    }
}

// Eliminar asignación
if(isset($_GET['eliminar'])) {
    $id = $db->real_escape_string($_GET['eliminar']);
    
    // Verificar que la asignación pertenezca a la carrera del director
    $query_check = "SELECT ds.id_docente_seccion, s.id_carrera 
                    FROM docente_seccion ds
                    INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
                    WHERE ds.id_docente_seccion = $id";
    $result_check = $db->query($query_check);
    $asignacion = $result_check->fetch_assoc();
    
    if ($asignacion['id_carrera'] != $id_carrera_director) {
        $tipo_mensaje = 'error';
        $texto_mensaje = 'No tiene permisos para eliminar esta asignación';
    } else {
        $resultado = eliminarAsignacionSeccion($id);
        
        if($resultado['success']) {
            $tipo_mensaje = 'success';
            $texto_mensaje = $resultado['message'];
        } else {
            $tipo_mensaje = 'error';
            $texto_mensaje = $resultado['message'];
        }
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulopag; ?></h1>
            
            <!-- Información de la carrera del director -->
            <div class="alert alert-info mb-4">
                <i class="fas fa-building mr-2"></i>
                <strong>Carrera asignada:</strong> <?php echo htmlspecialchars($carrera_director['nombre_carrera']); ?>
                <span class="badge badge-primary ml-2">DIRECTOR</span>
            </div>
            
            <!-- Modal de Éxito -->
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="successModalLabel">¡Operación Exitosa!</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-check-circle fa-3x text-success"></i>
                            </div>
                            <p id="successMessage" class="lead"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de Error -->
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="errorModalLabel">Error en la Operación</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-exclamation-circle fa-3x text-danger"></i>
                            </div>
                            <p id="errorMessage" class="lead"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

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
                            <div class="text-center mb-3">
                                <i class="fas fa-trash-alt fa-2x text-danger"></i>
                            </div>
                            <p class="text-center">¿Está seguro de eliminar esta asignación?</p>
                            <p class="text-center text-muted"><small>Esta acción no se puede deshacer.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <a id="confirmDeleteButton" href="#" class="btn btn-danger">
                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mostrar mensaje en toast -->
            <?php if($tipo_mensaje): ?>
            <div id="toastMessage" data-type="<?php echo $tipo_mensaje; ?>" data-message="<?php echo htmlspecialchars($texto_mensaje); ?>"></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-plus mr-1"></i>
                    Asignar Nueva Sección a Docente
                </div>
                <div class="card-body">
                    <form method="post" action="" id="asignarForm">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="id_usuario">Docente:</label>
                                <select class="form-control" id="id_usuario" name="id_usuario" required onchange="cargarMateriasPorCarrera()">
                                    <option value="">Seleccione un docente</option>
                                    <?php
                                    $query_docentes = "SELECT id, nombre, idusuario, carrera 
                                                       FROM users 
                                                       WHERE docente = 1 AND status = 1
                                                       ORDER BY nombre";
                                    $result_docentes = $db->query($query_docentes);
                                    if($result_docentes->num_rows > 0) {
                                        while($docente = $result_docentes->fetch_assoc()) {
                                            $nombre_carrera = '';
                                            if($docente['carrera']) {
                                                $query_carrera_doc = "SELECT nombre_carrera FROM carreras WHERE id_carrera = " . $docente['carrera'];
                                                $result_carrera_doc = $db->query($query_carrera_doc);
                                                if($result_carrera_doc->num_rows > 0) {
                                                    $carrera_doc = $result_carrera_doc->fetch_assoc();
                                                    $nombre_carrera = ' - ' . $carrera_doc['nombre_carrera'];
                                                }
                                            }
                                            echo "<option value='".$docente['id']."'>".$docente['nombre']." (".$docente['idusuario'].")" . $nombre_carrera . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay docentes disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="id_seccion">Sección:</label>
                                <select class="form-control" id="id_seccion" name="id_seccion" required onchange="cargarMateriasPorCarrera()">
                                    <option value="">Seleccione una sección</option>
                                    <?php
                                    $query_secciones = "SELECT s.id_seccion, s.codigo_seccion, s.id_carrera, c.nombre_carrera
                                                        FROM secciones s
                                                        INNER JOIN carreras c ON s.id_carrera = c.id_carrera
                                                        WHERE s.id_carrera = $id_carrera_director AND s.estatus = 'Activa'
                                                        ORDER BY s.codigo_seccion";
                                    $result_secciones = $db->query($query_secciones);
                                    if($result_secciones->num_rows > 0) {
                                        while($seccion = $result_secciones->fetch_assoc()) {
                                            echo "<option value='".$seccion['id_seccion']."' data-carrera='".$seccion['id_carrera']."'>".$seccion['codigo_seccion']." - ".$seccion['nombre_carrera']."</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay secciones activas disponibles para su carrera</option>";
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
                        <button type="submit" name="asignar" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Asignar Sección
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo mr-1"></i> Limpiar
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    Asignaciones Actuales
                </div>
                <div class="card-body">
                    <!-- DIAGNÓSTICO VISIBLE -->
                    <?php
                    // Contar total de registros en docente_seccion (CORREGIDO: usar 1 en lugar de 'activo')
                    $query_total = "SELECT COUNT(*) as total FROM docente_seccion WHERE estatus = 1";
                    $result_total = $db->query($query_total);
                    $total_registros = $result_total->fetch_assoc();
                    
                    // Contar registros de la carrera del director (CORREGIDO: usar 1 en lugar de 'activo')
                    $query_total_carrera = "SELECT COUNT(*) as total 
                                            FROM docente_seccion ds
                                            INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
                                            WHERE ds.estatus = 1 AND s.id_carrera = $id_carrera_director";
                    $result_total_carrera = $db->query($query_total_carrera);
                    $total_carrera = $result_total_carrera->fetch_assoc();
                    ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Diagnóstico:</strong> 
                        Total asignaciones en sistema: <strong><?php echo $total_registros['total']; ?></strong> | 
                        Asignaciones de su carrera: <strong><?php echo $total_carrera['total']; ?></strong>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
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
                                // ============================================================
                                // CORREGIDO: Usar estatus = 1 (numérico) en lugar de 'activo'
                                // ============================================================
                                $query_asignaciones = "SELECT 
                                    ds.id_docente_seccion,
                                    ds.id_usuario,
                                    ds.id_seccion,
                                    ds.id_materia,
                                    ds.fecha_asignacion,
                                    ds.estatus,
                                    u.nombre as docente,
                                    u.idusuario as cedula_docente,
                                    s.codigo_seccion,
                                    s.id_carrera,
                                    c.nombre_carrera,
                                    m.nombre_materia,
                                    m.cod_materia
                                FROM docente_seccion ds
                                INNER JOIN users u ON ds.id_usuario = u.id
                                INNER JOIN secciones s ON ds.id_seccion = s.id_seccion
                                INNER JOIN carreras c ON s.id_carrera = c.id_carrera
                                INNER JOIN materias m ON ds.id_materia = m.id_materia
                                WHERE ds.estatus = 1 
                                AND s.id_carrera = $id_carrera_director
                                ORDER BY c.nombre_carrera, s.codigo_seccion, u.nombre";

                                $result_asignaciones = $db->query($query_asignaciones);
                                
                                if($result_asignaciones->num_rows > 0) {
                                    while($row = $result_asignaciones->fetch_assoc()) {
                                        echo "<tr>
                                                <td>
                                                    <strong>".htmlspecialchars($row['docente'])."</strong><br>
                                                    <small class='text-muted'>Cédula: ".htmlspecialchars($row['cedula_docente'])."</small>
                                                </td>
                                                <td><span class='badge badge-primary'>".htmlspecialchars($row['codigo_seccion'])."</span></td>
                                                <td>".htmlspecialchars($row['nombre_carrera'])."</td>
                                                <td>
                                                    <strong>".htmlspecialchars($row['nombre_materia'])."</strong><br>
                                                    <small class='text-muted'>Código: ".htmlspecialchars($row['cod_materia'])."</small>
                                                </td>
                                                <td>".date('d/m/Y H:i', strtotime($row['fecha_asignacion']))."</td>
                                                <td>
                                                    <button class='btn btn-sm btn-danger eliminar-asignacion' 
                                                            data-toggle='modal' 
                                                            data-target='#confirmDeleteModal'
                                                            data-id='".$row['id_docente_seccion']."'
                                                            data-docente='".htmlspecialchars($row['docente'])."'
                                                            data-seccion='".htmlspecialchars($row['codigo_seccion'])."'>
                                                        <i class='fas fa-trash-alt mr-1'></i> Eliminar
                                                    </button>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo '<tr>
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                    <strong>No hay asignaciones registradas para su carrera</strong><br>
                                                    <small class="text-muted">
                                                        Total en sistema: '.htmlspecialchars($total_registros['total']).' | 
                                                        De su carrera: '.htmlspecialchars($total_carrera['total']).'
                                                    </small>
                                                </div>
                                            </td>
                                          </tr>';
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
    
    var idCarrera = selectSeccion.options[selectSeccion.selectedIndex]?.getAttribute('data-carrera') || '0';
    
    if(idDocente === '' || selectSeccion.value === '') {
        selectMaterias.innerHTML = '<option value="">Primero seleccione docente y sección</option>';
        selectMaterias.disabled = true;
        return;
    }
    
    selectMaterias.innerHTML = '<option value="">Cargando materias...</option>';
    selectMaterias.disabled = true;
    
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
                    showToast('warning', 'El docente no tiene materias asignadas para esta carrera');
                }
            } catch(e) {
                selectMaterias.innerHTML = '<option value="">Error al procesar materias</option>';
                selectMaterias.disabled = true;
                showToast('error', 'Error al cargar las materias');
            }
        } else {
            selectMaterias.innerHTML = '<option value="">Error al cargar materias</option>';
            selectMaterias.disabled = true;
            showToast('error', 'Error de conexión al servidor');
        }
    };
    
    xhr.onerror = function() {
        selectMaterias.innerHTML = '<option value="">Error de conexión</option>';
        selectMaterias.disabled = true;
        showToast('error', 'Error de conexión con el servidor');
    };
    
    xhr.send();
}

function showToast(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 
                     type === 'error' ? 'alert-danger' : 
                     type === 'warning' ? 'alert-warning' : 'alert-info';
    
    var toast = document.createElement('div');
    toast.className = 'alert ' + alertClass + ' alert-dismissible fade show';
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.innerHTML = `
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>${type === 'success' ? 'Éxito!' : type === 'error' ? 'Error!' : 'Advertencia!'}</strong> ${message}
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(function() {
        $(toast).alert('close');
    }, 5000);
}

$(document).ready(function() {
    $(document).on('click', '.eliminar-asignacion', function() {
        var id = $(this).data('id');
        var docente = $(this).data('docente');
        var seccion = $(this).data('seccion');
        
        $('#confirmDeleteButton').attr('href', '?eliminar=' + id);
        
        $('#confirmDeleteModal .modal-body p:first').html(
            '¿Está seguro de eliminar la asignación de <strong>' + docente + '</strong> a la sección <strong>' + seccion + '</strong>?'
        );
    });
    
    var toastMessage = $('#toastMessage');
    if(toastMessage.length) {
        var type = toastMessage.data('type');
        var message = toastMessage.data('message');
        
        if(type === 'success') {
            $('#successMessage').text(message);
            $('#successModal').modal('show');
        } else if(type === 'error') {
            $('#errorMessage').text(message);
            $('#errorModal').modal('show');
        }
        
        setTimeout(function() {
            toastMessage.remove();
        }, 100);
    }
    
    $('#asignarForm').on('submit', function(e) {
        var idMateria = $('#id_materia');
        if(idMateria.is(':disabled') || idMateria.val() === '') {
            e.preventDefault();
            showToast('error', 'Por favor seleccione una materia válida');
            return false;
        }
        return true;
    });
    
    $('#asignarForm button[type="reset"]').on('click', function() {
        $('#id_materia').html('<option value="">Primero seleccione docente y sección</option>').prop('disabled', true);
        showToast('info', 'Formulario limpiado');
    });
});
</script>

<?php include("includes/footer.php"); ?>