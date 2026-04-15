<?php
// ARCHIVO: auditoria.php
require_once('../funciones/functions.php');

if (!isLoggedIn()) {
    header('location: ../login.php');
    exit();
}

// Verificar permisos de administrador
if (!isAdmin()) {
    header('location: index.php');
    exit();
}

// Cargar permisos del usuario
cargarPermisosUsuario();
verificarPermiso('auditoria');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$titulopag = "Sistema de Auditoría";
include("includes/head.php");

// Procesar filtros
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;
$usuario_id = $_GET['usuario_id'] ?? null;
$accion = $_GET['accion'] ?? null;
$modulo = $_GET['modulo'] ?? null;
$limite = $_GET['limite'] ?? 100;

// Obtener registros de auditoría
$registros = obtenerRegistrosAuditoria($limite, $fecha_inicio, $fecha_fin, $usuario_id, $accion, $modulo);

// Obtener lista de usuarios para el filtro
$usuarios = obtenerUsuariosParaFiltro();

// Obtener lista de acciones únicas para el filtro
$acciones_unicas = obtenerAccionesUnicas();

// Obtener lista de módulos únicos para el filtro
$modulos_unicos = obtenerModulosUnicos();
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <h2 class="my-4" style="font-size: 1.4rem;">Sistema de Auditoría</h2>
    
    <!-- Filtros Responsive -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filtros de Búsqueda</h5>
        </div>
        <div class="card-body p-2 p-sm-3">
            <form method="GET">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="fecha_inicio">Fecha Inicio:</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                               value="<?= htmlspecialchars($fecha_inicio ?? '') ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="fecha_fin">Fecha Fin:</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                               value="<?= htmlspecialchars($fecha_fin ?? '') ?>">
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="usuario_id">Usuario:</label>
                        <select class="form-control" id="usuario_id" name="usuario_id">
                            <option value="">Todos los usuarios</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>" 
                                    <?= ($usuario_id == $usuario['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="accion">Acción:</label>
                        <select class="form-control" id="accion" name="accion">
                            <option value="">Todas las acciones</option>
                            <?php foreach ($acciones_unicas as $acc): ?>
                                <option value="<?= $acc ?>" 
                                    <?= ($accion == $acc) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($acc) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="modulo">Módulo:</label>
                        <select class="form-control" id="modulo" name="modulo">
                            <option value="">Todos los módulos</option>
                            <?php foreach ($modulos_unicos as $mod): ?>
                                <option value="<?= $mod ?>" 
                                    <?= ($modulo == $mod) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mod) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3 mb-2">
                        <label for="limite">Registros:</label>
                        <select class="form-control" id="limite" name="limite">
                            <option value="50" <?= ($limite == 50) ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= ($limite == 100) ? 'selected' : '' ?>>100</option>
                            <option value="200" <?= ($limite == 200) ? 'selected' : '' ?>>200</option>
                            <option value="500" <?= ($limite == 500) ? 'selected' : '' ?>>500</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary mr-2 mb-2 mb-sm-0">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="auditoria.php" class="btn btn-secondary mr-2 mb-2 mb-sm-0">
                            <i class="fas fa-sync"></i> Limpiar
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportarAuditoria()">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Estadísticas rápidas Responsive -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-2">
            <div class="card bg-primary text-white text-center">
                <div class="card-body p-2 p-sm-3">
                    <h6 class="mb-1">Total Registros</h6>
                    <h3 class="mb-0"><?= count($registros) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card bg-info text-white text-center">
                <div class="card-body p-2 p-sm-3">
                    <h6 class="mb-1">Registros Hoy</h6>
                    <h3 class="mb-0"><?= contarRegistrosHoy() ?></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card bg-success text-white text-center">
                <div class="card-body p-2 p-sm-3">
                    <h6 class="mb-1">Acciones INSERT</h6>
                    <h3 class="mb-0"><?= contarAccionesPorTipo('INSERT') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card bg-warning text-dark text-center">
                <div class="card-body p-2 p-sm-3">
                    <h6 class="mb-1">Acciones UPDATE</h6>
                    <h3 class="mb-0"><?= contarAccionesPorTipo('UPDATE') ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resultados - Solo tabla con scroll horizontal -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
            <h5 class="mb-2 mb-sm-0"><i class="fas fa-list"></i> Registros de Auditoría</h5>
            <span class="badge badge-light"><?= count($registros) ?> registros</span>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($registros)): ?>
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table table-bordered table-striped table-sm mb-0" style="min-width: 1200px;">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 140px;">Fecha/Hora</th>
                                <th style="width: 180px;">Usuario</th>
                                <th style="width: 100px;">Acción</th>
                                <th style="width: 120px;">Módulo</th>
                                <th style="width: 120px;">Tabla Afectada</th>
                                <th style="width: 80px;">ID Registro</th>
                                <th style="width: 250px;">Descripción</th>
                                <th style="width: 120px;">IP Origen</th>
                                <th style="width: 100px;">Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td>
                                    <?= date('d/m/Y', strtotime($registro['fecha_hora'])) ?><br>
                                    <small><?= date('H:i:s', strtotime($registro['fecha_hora'])) ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($registro['usuario_nombre'] ?? '') ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($registro['usuario_cedula'] ?? '') ?></small>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?= $registro['accion'] == 'INSERT' ? 'badge-success' : '' ?>
                                        <?= $registro['accion'] == 'UPDATE' ? 'badge-warning' : '' ?>
                                        <?= $registro['accion'] == 'DELETE' ? 'badge-danger' : '' ?>
                                        <?= $registro['accion'] == 'LOGIN' ? 'badge-info' : '' ?>
                                        <?= $registro['accion'] == 'LOGOUT' ? 'badge-secondary' : '' ?>
                                        <?= $registro['accion'] == 'ERROR' ? 'badge-dark' : '' ?>
                                        <?= $registro['accion'] == 'SEARCH' ? 'badge-primary' : '' ?>">
                                        <?= htmlspecialchars($registro['accion'] ?? '') ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($registro['modulo_sistema'] ?? '') ?></td>
                                <td><?= htmlspecialchars($registro['tabla_afectada'] ?? '') ?></td>
                                <td><?= htmlspecialchars($registro['registro_id'] ?? '') ?></td>
                                <td><?= htmlspecialchars(substr($registro['descripcion'] ?? '', 0, 60)) ?><?= strlen($registro['descripcion'] ?? '') > 60 ? '...' : '' ?></td>
                                <td><?= htmlspecialchars($registro['ip_origen'] ?? '') ?></td>
                                <td>
                                    <?php if ($registro['valores_antiguos'] || $registro['valores_nuevos'] || $registro['user_agent']): ?>
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                            data-target="#detallesModal<?= $registro['id'] ?>">
                                        <i class="fas fa-eye"></i> Ver
                                    </button>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                 </td>
                             </tr>
                             
                             <!-- Modal para detalles -->
                             <div class="modal fade" id="detallesModal<?= $registro['id'] ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalles de Auditoría - <?= date('d/m/Y H:i:s', strtotime($registro['fecha_hora'])) ?></h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-12 col-md-6">
                                                    <strong>Usuario:</strong> <?= htmlspecialchars($registro['usuario_nombre'] ?? '') ?> (<?= htmlspecialchars($registro['usuario_cedula'] ?? '') ?>)
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <strong>Acción:</strong> <span class="badge badge-primary"><?= htmlspecialchars($registro['accion'] ?? '') ?></span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12 col-md-6">
                                                    <strong>Módulo:</strong> <?= htmlspecialchars($registro['modulo_sistema'] ?? '') ?>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <strong>Tabla:</strong> <?= htmlspecialchars($registro['tabla_afectada'] ?? '') ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <strong>Descripción:</strong> <?= htmlspecialchars($registro['descripcion'] ?? '') ?>
                                                </div>
                                            </div>
                                            
                                            <?php if ($registro['valores_antiguos'] || $registro['valores_nuevos']): ?>
                                            <div class="row mb-3">
                                                <?php if ($registro['valores_antiguos']): ?>
                                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                                    <h6>Valores Antiguos:</h6>
                                                    <div class="border p-2 bg-light" style="max-height: 200px; overflow-y: auto;">
                                                        <pre class="mb-0" style="font-size: 0.7rem;"><?= json_encode($registro['valores_antiguos'], JSON_PRETTY_PRINT) ?></pre>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if ($registro['valores_nuevos']): ?>
                                                <div class="col-12 col-md-6">
                                                    <h6>Valores Nuevos:</h6>
                                                    <div class="border p-2 bg-light" style="max-height: 200px; overflow-y: auto;">
                                                        <pre class="mb-0" style="font-size: 0.7rem;"><?= json_encode($registro['valores_nuevos'], JSON_PRETTY_PRINT) ?></pre>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($registro['user_agent']): ?>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6>User Agent:</h6>
                                                    <div class="border p-2 bg-light">
                                                        <small><?= htmlspecialchars($registro['user_agent'] ?? '') ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center py-4 m-3">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>No se encontraron registros de auditoría</h4>
                    <p>Intente ajustar los filtros de búsqueda</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function exportarAuditoria() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = 'exportar_auditoria.php?' + params.toString();
}
</script>

<style>
/* Estilos responsivos */
@media (max-width: 767.98px) {
    .card-header h5 {
        font-size: 1rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
    
    .table td, .table th {
        font-size: 0.75rem;
        padding: 0.4rem;
        vertical-align: middle;
    }
}

/* Estilos para modales en móviles */
@media (max-width: 767.98px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-body {
        padding: 0.75rem;
    }
    
    pre {
        font-size: 0.6rem;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
}

/* Scroll suave para la tabla */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.card {
    border-radius: 0.5rem;
    overflow: hidden;
}
</style>

<?php include("includes/footer.php"); ?>