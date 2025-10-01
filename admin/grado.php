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
            
            <!-- ============================================= -->
            <!-- 🐛 INSTRUCCIONES DEBUG - COMO ACTIVAR EL MODO DEBUG -->
            <!-- ============================================= -->
            <!-- 
            PARA ACTIVAR EL MODO DEBUG USA ESTAS URLS:
            
            1. DEBUG COMPLETO (todos los estudiantes):
               http://tudominio.com/admin/grado.php?debug=1
            
            2. DEBUG SOLO ESTUDIANTES APTOS:
               http://tudominio.com/admin/grado.php?debug=1&estado=cumple_requisitos
            
            3. DEBUG CON FILTROS ESPECÍFICOS:
               http://tudominio.com/admin/grado.php?debug=1&carrera=Ingeniería&buscar=nombre
            
            El modo DEBUG te muestra información detallada de por qué cada estudiante 
            es considerado APTO o NO APTO para graduarse.
            -->
            
            <!-- DEBUG: Información de depuración -->
            <?php if (isset($_GET['debug'])): ?>
            <div class="alert alert-info">
                <h5>🔍 MODO DEBUG ACTIVADO</h5>
                <p>Esta información te ayuda a ver por qué los estudiantes aparecen como aptos o no aptos.</p>
                <small>
                    <a href="grado.php" class="text-primary">[Ocultar DEBUG]</a> | 
                    <a href="grado.php?debug=1&estado=cumple_requisitos" class="text-primary">[DEBUG Solo Aptos]</a> |
                    <a href="grado.php?debug=1" class="text-primary">[DEBUG Todos]</a>
                </small>
            </div>
            <?php endif; ?>
            
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
                                        $selected = (isset($_GET['carrera']) && $_GET['carrera'] == $carrera['nombre_carrera']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($carrera['nombre_carrera']) . "' $selected>" . htmlspecialchars($carrera['nombre_carrera']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
                        <a href="grado.php" class="btn btn-secondary mb-2 ml-2">Limpiar</a>
                        
                        <!-- Enlace de depuración -->
                        <div class="ml-2">
                            <small>
                                <?php if (!isset($_GET['debug'])): ?>
                                    <a href="grado.php?debug=1<?php echo isset($_GET['estado']) ? '&estado=' . $_GET['estado'] : ''; ?><?php echo isset($_GET['carrera']) ? '&carrera=' . $_GET['carrera'] : ''; ?><?php echo isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : ''; ?>" class="text-muted">[DEBUG] Ver evaluación</a>
                                <?php else: ?>
                                    <a href="grado.php<?php echo isset($_GET['estado']) ? '?estado=' . $_GET['estado'] : ''; ?><?php echo isset($_GET['carrera']) ? '&carrera=' . $_GET['carrera'] : ''; ?><?php echo isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : ''; ?>" class="text-muted">[DEBUG] Ocultar</a>
                                <?php endif; ?>
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DEBUG: Información detallada de evaluación -->
            <?php if (isset($_GET['debug'])): ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">🔍 Información de Depuración</h6>
                </div>
                <div class="card-body">
                    <?php
                    // Obtener estudiantes para debug
                    $estudiantes_debug = obtener_estudiantes_graduacion($_GET);
                    $total_aptos = 0;
                    $total_no_aptos = 0;
                    
                    if ((is_array($estudiantes_debug) && count($estudiantes_debug) > 0)) {
                        
                        echo "<div class='table-responsive'>";
                        echo "<table class='table table-sm table-bordered'>";
                        echo "<thead><tr class='bg-light'>
                                <th>Estudiante</th>
                                <th>Cédula</th>
                                <th>Carrera</th>
                                <th>TSU (Aprobadas/Total)</th>
                                <th>% TSU</th>
                                <th>Completo (Aprobadas/Total)</th>
                                <th>% Completo</th>
                                <th>Apto TSU</th>
                                <th>Apto Completo</th>
                                <th>Estado</th>
                              </tr></thead><tbody>";
                        
                        // Manejar arrays de estudiantes
                        foreach ($estudiantes_debug as $est) {
                            $info = es_apto_para_grado($est['id']);
                            $es_apto = $info['apto_tsu'] || $info['apto_grado_completo'];
                            
                            if ($es_apto) $total_aptos++; else $total_no_aptos++;
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($est['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($est['idusuario']) . "</td>";
                            echo "<td>" . htmlspecialchars($est['nombre_carrera'] ?: 'Carrera ' . $est['carrera']) . "</td>";
                            echo "<td>{$info['materias_aprobadas_tsu']}/{$info['total_materias_tsu']}</td>";
                            echo "<td><span class='badge badge-" . ($info['porcentaje_tsu'] >= 90 ? 'success' : 'warning') . "'>{$info['porcentaje_tsu']}%</span></td>";
                            echo "<td>{$info['materias_aprobadas_completo']}/{$info['total_materias_carrera']}</td>";
                            echo "<td><span class='badge badge-" . ($info['porcentaje_completo'] >= 100 ? 'success' : 'info') . "'>{$info['porcentaje_completo']}%</span></td>";
                            echo "<td><span class='badge badge-" . ($info['apto_tsu'] ? 'success' : 'secondary') . "'>" . ($info['apto_tsu'] ? 'SÍ' : 'NO') . "</span></td>";
                            echo "<td><span class='badge badge-" . ($info['apto_grado_completo'] ? 'success' : 'secondary') . "'>" . ($info['apto_grado_completo'] ? 'SÍ' : 'NO') . "</span></td>";
                            echo "<td>" . obtener_badge_estado($est['estado']) . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</tbody></table>";
                        echo "</div>";
                        
                        echo "<div class='mt-3 p-3 bg-light rounded'>";
                        echo "<h6>Resumen de Evaluación:</h6>";
                        echo "<p><strong>Total estudiantes evaluados:</strong> " . ($total_aptos + $total_no_aptos) . "</p>";
                        echo "<p><strong>Estudiantes aptos para graduación:</strong> <span class='badge badge-success'>$total_aptos</span></p>";
                        echo "<p><strong>Estudiantes NO aptos:</strong> <span class='badge badge-secondary'>$total_no_aptos</span></p>";
                        echo "<p><small class='text-muted'>Criterios: TSU ≥90% aprobado | Grado Completo = 100% aprobado</small></p>";
                        echo "</div>";
                    } else {
                        echo "<p class='text-muted'>No hay estudiantes para mostrar con los filtros actuales.</p>";
                    }
                    ?>
                </div>
            </div>
            <?php endif; ?>

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
                                if (is_array($estudiantes) && count($estudiantes) > 0) {
                                    // Si es un array (resultado filtrado)
                                    foreach ($estudiantes as $estudiante) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($estudiante['idusuario']) . "</td>";
                                        echo "<td>" . htmlspecialchars($estudiante['nombre']) . "</td>";
                                        echo "<td>" . htmlspecialchars($estudiante['nombre_carrera'] ?: 'Carrera ' . $estudiante['carrera']) . "</td>";
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

// =============================================
// 🐛 INSTRUCCIONES DEBUG EN CONSOLA
// =============================================
console.log("🔍 MODO DEBUG DISPONIBLE:");
console.log("1. grado.php?debug=1 - Ver evaluación completa");
console.log("2. grado.php?debug=1&estado=cumple_requisitos - Ver solo aptos");
console.log("3. grado.php?debug=1&carrera=X&buscar=Y - Ver con filtros específicos");
console.log("");
console.log("📊 ESTADOS DE GRADUACIÓN:");
console.log("- Cumple Requisitos: Estudiantes que pueden graduarse");
console.log("- Pendiente: Estudiantes que NO cumplen requisitos");
console.log("- Graduado: Estudiantes marcados como graduados");
console.log("- Título Entregado: Estudiantes que recibieron su título");
</script>