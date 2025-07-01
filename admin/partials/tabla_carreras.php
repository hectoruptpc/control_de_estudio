<?php $carreras = obtenerListaCompletaCarreras();
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
                        <button class="btn btn-sm btn-warning btn-editar" data-id="<?= $carrera['id_carrera'] ?>">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        
                        <?php if ($carrera['activa']): ?>
                            <button class="btn btn-sm btn-danger btn-cambiar-estado" 
                                    data-id="<?= $carrera['id_carrera'] ?>" 
                                    data-accion="desactivar">
                                <i class="fas fa-toggle-off"></i> Desactivar
                            </button>
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