<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Graduación";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('admin');

// Configuración de paginación
$registros_por_pagina = obtener_registros_por_pagina();
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

// Procesar acciones
if (isset($_POST['marcar_graduado'])) {
    marcar_como_graduado($_POST['id_usuario']);
}

if (isset($_POST['marcar_titulo_entregado'])) {
    marcar_titulo_entregado($_POST['id_graduado']);
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Gestión de Graduación</h1>
            
            <!-- Filtros -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filtros de Búsqueda</h6>
                </div>
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <input type="hidden" name="pagina" value="1">
                        <div class="form-group mr-2 mb-2">
                            <input type="text" class="form-control" name="buscar" placeholder="Buscar por nombre o cédula" 
                                   value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select class="form-control" name="estado">
                                <option value="">Todos los estados</option>
                                <option value="cumple_requisitos" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cumple_requisitos') ? 'selected' : ''; ?>>Cumple Requisitos</option>
                                <option value="graduado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'graduado') ? 'selected' : ''; ?>>Graduados</option>
                                <option value="titulo_entregado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'titulo_entregado') ? 'selected' : ''; ?>>Título Entregado</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select class="form-control" name="carrera">
                                <option value="">Todas las carreras</option>
                                <?php
                                $carreras = obtener_carreras();
                                if ($carreras) {
                                    while ($carrera = mysqli_fetch_assoc($carreras)) {
                                        $selected = (isset($_GET['carrera']) && $_GET['carrera'] == $carrera['carrera']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($carrera['carrera']) . "' $selected>" . htmlspecialchars($carrera['carrera']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
                        <a href="grado.php" class="btn btn-secondary mb-2 ml-2">Limpiar</a>
                    </form>
                </div>
            </div>

            <!-- Lista de Estudiantes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Lista de Estudiantes</h6>
                    <div>
                        <button class="btn btn-success btn-sm" onclick="verEstudiantesCumplenRequisitos()">
                            <i class="fas fa-graduation-cap"></i> Ver Cumplen Requisitos
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    // Obtener datos con paginación
                    $datos_paginacion = obtener_estudiantes_graduacion_paginados($_GET, $pagina_actual, $registros_por_pagina);
                    $estudiantes = $datos_paginacion['resultados'];
                    $total_registros = $datos_paginacion['total_registros'];
                    $total_paginas = $datos_paginacion['total_paginas'];
                    ?>
                    
                    <!-- Información de paginación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando <?php echo (($pagina_actual - 1) * $registros_por_pagina) + 1; ?> - 
                                <?php echo min($pagina_actual * $registros_por_pagina, $total_registros); ?> 
                                de <?php echo $total_registros; ?> estudiantes
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <select class="form-control form-control-sm d-inline-block w-auto" onchange="cambiarRegistrosPorPagina(this.value)">
                                <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10 por página</option>
                                <option value="20" <?php echo $registros_por_pagina == 20 ? 'selected' : ''; ?>>20 por página</option>
                                <option value="50" <?php echo $registros_por_pagina == 50 ? 'selected' : ''; ?>>50 por página</option>
                                <option value="100" <?php echo $registros_por_pagina == 100 ? 'selected' : ''; ?>>100 por página</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre Completo</th>
                                    <th>Carrera</th>
                                    <th>Estado</th>
                                    <th>Fecha Graduación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($estudiantes && mysqli_num_rows($estudiantes) > 0) {
                                    while ($estudiante = mysqli_fetch_assoc($estudiantes)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($estudiante['idusuario']) . "</td>";
                                        echo "<td>" . htmlspecialchars($estudiante['nombre']) . "</td>";
                                        echo "<td>" . htmlspecialchars($estudiante['carrera']) . "</td>";
                                        echo "<td>" . obtener_badge_estado($estudiante['estado']) . "</td>";
                                        echo "<td>" . ($estudiante['fecha_graduacion'] ? date('d/m/Y', strtotime($estudiante['fecha_graduacion'])) : '-') . "</td>";
                                        echo "<td>" . generar_botones_accion($estudiante) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No se encontraron estudiantes</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <?php if ($total_paginas > 1): ?>
                    <nav aria-label="Paginación">
                        <ul class="pagination justify-content-center">
                            <!-- Botón Anterior -->
                            <li class="page-item <?php echo $pagina_actual <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo generar_url_paginacion($pagina_actual - 1); ?>" aria-label="Anterior">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Páginas -->
                            <?php
                            $inicio = max(1, $pagina_actual - 2);
                            $fin = min($total_paginas, $pagina_actual + 2);
                            
                            for ($i = $inicio; $i <= $fin; $i++): 
                            ?>
                                <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo generar_url_paginacion($i); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Botón Siguiente -->
                            <li class="page-item <?php echo $pagina_actual >= $total_paginas ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo generar_url_paginacion($pagina_actual + 1); ?>" aria-label="Siguiente">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar graduación -->
<div class="modal fade" id="modalGraduacion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Graduación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de marcar a este estudiante como GRADUADO?</p>
                    <input type="hidden" name="id_usuario" id="id_usuario_modal">
                    <div class="form-group">
                        <label>Observaciones:</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones opcionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="marcar_graduado" class="btn btn-success">Confirmar Graduación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
function confirmarGraduacion(idUsuario) {
    $('#id_usuario_modal').val(idUsuario);
    $('#modalGraduacion').modal('show');
}

function verEstudiantesCumplenRequisitos() {
    window.location.href = 'grado.php?estado=cumple_requisitos&pagina=1';
}

function cambiarRegistrosPorPagina(cantidad) {
    const url = new URL(window.location.href);
    url.searchParams.set('registros_por_pagina', cantidad);
    url.searchParams.set('pagina', 1);
    window.location.href = url.toString();
}
</script>