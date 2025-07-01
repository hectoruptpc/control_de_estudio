<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de usuarios";
include('../funciones/functions.php');

include("includes/head.php");

// Obtener todos los usuarios
$usuarios = obtenerListaCompletaUsuarios();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4"><?= $titulopag ?></h1>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id']) ?></td>
                                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                <td><?= htmlspecialchars($usuario['username']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td>
                                    <?php 
                                    $tipo = '';
                                    if ($usuario['super_user']) $tipo = 'Super Usuario';
                                    elseif ($usuario['admin']) $tipo = 'Administrador';
                                    elseif ($usuario['docente']) $tipo = 'Docente';
                                    elseif ($usuario['estudiante']) $tipo = 'Estudiante';
                                    else $tipo = 'Usuario';
                                    echo htmlspecialchars($tipo);
                                    ?>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $usuario['status'] ? 'success' : 'secondary' ?>">
                                        <?= $usuario['status'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['editar_user']): ?>
                                        <button class="btn btn-sm btn-warning btn-editar" data-id="<?= $usuario['id'] ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($usuario['status']): ?>
                                        <button class="btn btn-sm btn-danger btn-cambiar-estado" 
                                                data-id="<?= $usuario['id'] ?>" 
                                                data-accion="desactivar">
                                            <i class="fas fa-toggle-off"></i> Desactivar
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success btn-cambiar-estado" 
                                                data-id="<?= $usuario['id'] ?>" 
                                                data-accion="activar">
                                            <i class="fas fa-toggle-on"></i> Activar
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="btn btn-sm btn-info btn-ver-detalles" 
                                            data-id="<?= $usuario['id'] ?>">
                                        <i class="fas fa-info-circle"></i> Detalles
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar el cambio de estado
    $('.btn-cambiar-estado').click(function() {
        var id = $(this).data('id');
        var accion = $(this).data('accion');
        
        $.ajax({
            url: 'acciones/cambiar_estado_usuario.php',
            method: 'POST',
            data: { id: id, accion: accion },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error al procesar la solicitud');
            }
        });
    });
    
    // Manejar la edición
    $('.btn-editar').click(function() {
        var id = $(this).data('id');
        window.location.href = 'editar_usuario.php?id=' + id;
    });
    
    // Manejar ver detalles
    $('.btn-ver-detalles').click(function() {
        var id = $(this).data('id');
        window.location.href = 'detalles_usuario.php?id=' + id;
    });
});
</script>

<?php include("includes/footer.php"); ?>