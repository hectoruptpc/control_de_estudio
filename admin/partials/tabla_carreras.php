<?php 
require_once(__DIR__.'/../../funciones/functions.php');

// Obtener lista de carreras con manejo de errores
try {
    $carreras = obtenerListaCompletaCarreras();
    if ($carreras === false) {
        throw new Exception("Error al obtener la lista de carreras");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    $carreras = []; // Array vacío para evitar errores en la vista
    echo '<div class="alert alert-danger">Error al cargar los datos. Por favor intente nuevamente.</div>';
}
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($carreras)): ?>
                <?php foreach ($carreras as $carrera): ?>
                <tr>
                    <td><?= htmlspecialchars($carrera['id_carrera']) ?></td>
                    <td><?= htmlspecialchars($carrera['nombre_carrera']) ?></td>
                    <td><?= htmlspecialchars($carrera['cod_carrera']) ?></td>
                    <td>
                        <span class="badge badge-<?= $carrera['activa'] ? 'success' : 'secondary' ?>">
                            <?= $carrera['activa'] ? 'Activa' : 'Inactiva' ?>
                        </span>
                    </td>
                    <td>

                    <?php if (tienePermiso('gestionar_carrera')): ?>
                        <button class="btn btn-sm btn-warning btn-editar" 
                                data-id="<?= intval($carrera['id_carrera']) ?>" 
                                onclick="cargarModalEditar(this)">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    <?php endif; ?>
                        
                        <?php if ($carrera['activa']): ?>
                            <?php if (tienePermiso('gestionar_carrera')): ?>
                            <button class="btn btn-sm btn-danger btn-cambiar-estado" 
                                    data-id="<?= $carrera['id_carrera'] ?>" 
                                    data-accion="desactivar">
                                <i class="fas fa-toggle-off"></i> Desactivar
                            </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button class="btn btn-sm btn-success btn-cambiar-estado" 
                                    data-id="<?= $carrera['id_carrera'] ?>" 
                                    data-accion="activar">
                                <i class="fas fa-toggle-on"></i> Activar
                            </button>
                        <?php endif; ?>
                        
                        <a href="ver_pensum.php?id_carrera=<?= $carrera['id_carrera'] ?>" 
                           class="btn btn-sm btn-info">
                            <i class="fas fa-book"></i> Ver Pensum
                        </a>

                        <?php // Mostrar selector de años si existen versiones por código ?>
                        <?php $anios = obtenerAniosPorCodigoCarrera($carrera['cod_carrera']); ?>
                        <?php if (!empty($anios)): ?>
                            <select class="form-control form-control-sm d-inline-block ml-2" style="width:auto; display:inline-block;" 
                                    onchange="if(this.value) window.location.href='ver_pensum.php?cod=<?= urlencode($carrera['cod_carrera']) ?>&anio='+this.value;">
                                <option value="">Año</option>
                                <?php foreach ($anios as $anio): ?>
                                    <option value="<?= intval($anio) ?>"><?= intval($anio) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay carreras registradas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function cargarModalEditar(button) {
    const idCarrera = $(button).data('id');
    console.log("ID obtenido del botón:", idCarrera);
    
    if (!idCarrera || isNaN(idCarrera) || idCarrera <= 0) {
        console.error("ID inválido:", idCarrera);
        mostrarAlerta('danger', "Error: ID de carrera inválido");
        return;
    }

    $.ajax({
        url: 'partials/editar_carrera_modal.php',
        type: 'GET',
        data: { id: idCarrera },
        dataType: 'html',
        beforeSend: function() {
            console.log("Enviando ID válido:", idCarrera);
            // Mostrar spinner de carga
            $('#modalEditarCarrera .modal-content').html(`
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando información de la carrera...</p>
                </div>
            `);
            $('#modalEditarCarrera').modal('show');
        },
        success: function(data) {
            $('#modalEditarCarrera .modal-content').html(data);
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud:", {
                status: status,
                error: error,
                response: xhr.responseText
            });
            $('#modalEditarCarrera .modal-content').html(`
                <div class="modal-body">
                    <div class="alert alert-danger">
                        Error al cargar los datos. Por favor intente nuevamente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            `);
        }
    });
}

function mostrarAlerta(tipo, mensaje) {
    // Cerrar alertas existentes
    $('.alert-dismissible').alert('close');
    
    // Crear nueva alerta
    var alerta = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Insertar la alerta en el contenedor principal
    $('.container').prepend(alerta);
    
    // Cerrar automáticamente después de 5 segundos
    setTimeout(function() {
        $('.alert-dismissible').alert('close');
    }, 5000);
}
</script>