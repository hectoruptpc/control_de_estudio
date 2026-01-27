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

// Variables para mensajes
$tipo_mensaje = ''; // 'success' o 'error'
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
    
    $resultado = procesarAsignacionSeccion($id_usuario, $id_seccion, $id_materia);
    
    if($resultado['success']) {
        $tipo_mensaje = 'success';
        $texto_mensaje = $resultado['message'];
    } else {
        $tipo_mensaje = 'error';
        $texto_mensaje = $resultado['message'];
    }
}

// Eliminar asignación
if(isset($_GET['eliminar'])) {
    $id = $db->real_escape_string($_GET['eliminar']);
    $resultado = eliminarAsignacionSeccion($id);
    
    if($resultado['success']) {
        $tipo_mensaje = 'success';
        $texto_mensaje = $resultado['message'];
    } else {
        $tipo_mensaje = 'error';
        $texto_mensaje = $resultado['message'];
    }
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-4"><?php echo $titulopag; ?></h1>
            
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
                                $asignaciones = obtenerAsignacionesSecciones();
                                if(count($asignaciones) > 0) {
                                    foreach($asignaciones as $row) {
                                        $nombre_carrera = $row['nombre_carrera'] ?? 'Sin carrera asignada';
                                        echo "<tr>
                                                <td>".$row['docente']."</td>
                                                <td><span class='badge badge-primary'>".$row['codigo_seccion']."</span></td>
                                                <td>".$nombre_carrera."</td>
                                                <td>
                                                    <strong>".$row['nombre_materia']."</strong><br>
                                                    <small class='text-muted'>Código: ".$row['cod_materia']."</small>
                                                </td>
                                                <td>".$row['fecha_asignacion']."</td>
                                                <td>
                                                    <button class='btn btn-sm btn-danger eliminar-asignacion' 
                                                            data-toggle='modal' 
                                                            data-target='#confirmDeleteModal'
                                                            data-id='".$row['id_docente_seccion']."'
                                                            data-docente='".htmlspecialchars($row['docente'])."'
                                                            data-seccion='".$row['codigo_seccion']."'>
                                                        <i class='fas fa-trash-alt mr-1'></i> Eliminar
                                                    </button>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr>
                                            <td colspan='6' class='text-center'>
                                                <div class='alert alert-info'>
                                                    <i class='fas fa-info-circle mr-2'></i>
                                                    No hay asignaciones registradas
                                                </div>
                                            </td>
                                          </tr>";
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
    
    // Mostrar indicador de carga
    selectMaterias.innerHTML = '<option value="">Cargando materias...</option>';
    selectMaterias.disabled = true;
    
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
                    
                    // Mostrar toast de éxito si hay materias
                    if(materias.length === 1) {
                        showToast('success', 'Se encontró 1 materia disponible');
                    } else {
                        showToast('success', 'Se encontraron ' + materias.length + ' materias disponibles');
                    }
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

// Función para mostrar toast
function showToast(type, message) {
    // Puedes implementar un toast más elegante aquí si lo prefieres
    // Por ahora usaremos alertas de Bootstrap
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
    
    // Auto-eliminar después de 5 segundos
    setTimeout(function() {
        $(toast).alert('close');
    }, 5000);
}

// Configurar modales
$(document).ready(function() {
    // Configurar modal de eliminación
    $(document).on('click', '.eliminar-asignacion', function() {
        var id = $(this).data('id');
        var docente = $(this).data('docente');
        var seccion = $(this).data('seccion');
        
        $('#confirmDeleteButton').attr('href', '?eliminar=' + id);
        
        // Actualizar mensaje del modal con información específica
        $('#confirmDeleteModal .modal-body p:first').html(
            '¿Está seguro de eliminar la asignación de <strong>' + docente + '</strong> a la sección <strong>' + seccion + '</strong>?'
        );
    });
    
    // Mostrar modal de éxito/error si hay mensaje
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
        
        // Limpiar el elemento después de mostrar
        setTimeout(function() {
            toastMessage.remove();
        }, 100);
    }
    
    // Validación del formulario
    $('#asignarForm').on('submit', function(e) {
        var idMateria = $('#id_materia');
        if(idMateria.is(':disabled') || idMateria.val() === '') {
            e.preventDefault();
            showToast('error', 'Por favor seleccione una materia válida');
            return false;
        }
        return true;
    });
    
    // Resetear formulario
    $('#asignarForm button[type="reset"]').on('click', function() {
        $('#id_materia').html('<option value="">Primero seleccione docente y sección</option>').prop('disabled', true);
        showToast('info', 'Formulario limpiado');
    });
});
</script>

<?php include("includes/footer.php"); ?>