<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Gestión de Graduación";
include('../funciones/functions.php');

//CARGAR PERMISOS
cargarPermisosUsuario();
verificarPermiso('grado');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Configuración de paginación
$registros_por_pagina = obtener_registros_por_pagina();
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

// Funciones auxiliares para la renderización de paginación e información dinámica
function generar_html_paginacion_grado($total_paginas, $pagina_actual) {
    if ($total_paginas <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Paginación"><ul class="pagination justify-content-center mb-0">';
    
    // Botón Anterior
    $disabled_prev = ($pagina_actual <= 1) ? 'disabled' : '';
    $prev_page = max(1, $pagina_actual - 1);
    $html .= '<li class="page-item ' . $disabled_prev . '">';
    $html .= '<a class="page-link" href="#" onclick="realizarBusquedaGraduacionPost(' . $prev_page . '); return false;" aria-label="Anterior">&laquo;</a>';
    $html .= '</li>';
    
    // Páginas
    $inicio = max(1, $pagina_actual - 2);
    $fin = min($total_paginas, $pagina_actual + 2);
    
    for ($i = $inicio; $i <= $fin; $i++) {
        $active = ($i == $pagina_actual) ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '">';
        $html .= '<a class="page-link" href="#" onclick="realizarBusquedaGraduacionPost(' . $i . '); return false;">' . $i . '</a>';
        $html .= '</li>';
    }
    
    // Botón Siguiente
    $disabled_next = ($pagina_actual >= $total_paginas) ? 'disabled' : '';
    $next_page = min($total_paginas, $pagina_actual + 1);
    $html .= '<li class="page-item ' . $disabled_next . '">';
    $html .= '<a class="page-link" href="#" onclick="realizarBusquedaGraduacionPost(' . $next_page . '); return false;" aria-label="Siguiente">&raquo;</a>';
    $html .= '</li>';
    
    $html .= '</ul></nav>';
    return $html;
}

function generar_html_info_grado($total_registros, $pagina_actual, $registros_por_pagina) {
    if ($total_registros == 0) {
        return '<p class="text-muted mb-0 font-weight-bold">NO SE ENCONTRARON REGISTROS</p>';
    }
    $inicio = (($pagina_actual - 1) * $registros_por_pagina) + 1;
    $fin = min($pagina_actual * $registros_por_pagina, $total_registros);
    return '<p class="text-muted mb-0 font-weight-bold">MOSTRANDO ' . $inicio . ' - ' . $fin . ' DE ' . $total_registros . ' ESTUDIANTES</p>';
}

// Manejar búsqueda AJAX por POST en tiempo de ejecución (sin recargar ni usar GET)
if (isset($_POST['ajax_filtrar'])) {
    header('Content-Type: application/json');
    $filtros = [
        'buscar' => trim($_POST['buscar'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
        'carrera' => trim($_POST['carrera'] ?? '')
    ];
    $pagina_actual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
    $registros_por_pagina = isset($_POST['registros_por_pagina']) ? (int)$_POST['registros_por_pagina'] : 20;
    
    $datos_paginacion = obtener_estudiantes_graduacion_paginados($filtros, $pagina_actual, $registros_por_pagina);
    $estudiantes = $datos_paginacion['resultados'];
    $total_registros = $datos_paginacion['total_registros'];
    $total_paginas = $datos_paginacion['total_paginas'];
    
    ob_start();
    if (is_array($estudiantes) && count($estudiantes) > 0) {
        foreach ($estudiantes as $estudiante) {
            $nombre_carrera = $estudiante['nombre_carrera'] ?: 'Carrera ' . $estudiante['carrera'];
            echo "<tr class='text-uppercase'>";
            echo "<td>" . htmlspecialchars($estudiante['idusuario']) . "</td>";
            echo "<td><strong>" . htmlspecialchars(mb_strtoupper($estudiante['nombre'], 'UTF-8')) . "</strong></td>";
            echo "<td>" . htmlspecialchars(mb_strtoupper($nombre_carrera, 'UTF-8')) . "</td>";
            echo "<td>" . obtener_badge_estado($estudiante['estado']) . "</td>";
            echo "<td>" . ($estudiante['fecha_graduacion'] ? date('d/m/Y', strtotime($estudiante['fecha_graduacion'])) : '-') . "</td>";
            if (tienePermiso('gestion_grado')) {
                echo "<td class='text-nowrap'>" . generar_botones_accion($estudiante) . "</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center font-weight-bold text-muted p-4'><i class='fas fa-exclamation-circle text-warning mr-2'></i> NO SE ENCONTRARON ESTUDIANTES PARA GRADUACIÓN CON LOS FILTROS SELECCIONADOS</td></tr>";
    }
    $html_tabla = ob_get_clean();
    
    $html_info = generar_html_info_grado($total_registros, $pagina_actual, $registros_por_pagina);
    $html_paginacion = generar_html_paginacion_grado($total_paginas, $pagina_actual);
    
    echo json_encode([
        'status' => 'success',
        'html' => $html_tabla,
        'html_info' => $html_info,
        'html_paginacion' => $html_paginacion,
        'total_registros' => $total_registros,
        'total_paginas' => $total_paginas,
        'pagina_actual' => $pagina_actual
    ]);
    exit();
}

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
            <h1 class="h3 mb-4 text-gray-800 font-weight-bold">GESTIÓN DE GRADUACIÓN</h1>
            
            <!-- Filtros -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white font-weight-bold">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-search mr-1"></i> FILTROS DE BÚSQUEDA EN TIEMPO REAL</h6>
                </div>
                <div class="card-body">
                    <form id="filtroGradoForm" onsubmit="return false;" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <input type="text" class="form-control text-uppercase" id="buscar_input" name="buscar" placeholder="🔍 BUSCAR POR NOMBRE O CÉDULA..." 
                                   value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>" autocomplete="off">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select class="form-control text-uppercase" id="estado_select" name="estado">
                                <option value="">TODOS LOS ESTADOS</option>
                                <option value="cumple_requisitos" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'cumple_requisitos') ? 'selected' : ''; ?>>CUMPLE REQUISITOS</option>
                                <option value="graduado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'graduado') ? 'selected' : ''; ?>>GRADUADOS</option>
                                <option value="titulo_entregado" <?php echo (isset($_GET['estado']) && $_GET['estado'] == 'titulo_entregado') ? 'selected' : ''; ?>>TÍTULO ENTREGADO</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select class="form-control text-uppercase" id="carrera_select" name="carrera">
                                <option value="">TODAS LAS CARRERAS</option>
                                <?php
                                $carreras = obtener_carreras();
                                if ($carreras) {
                                    while ($carrera = mysqli_fetch_assoc($carreras)) {
                                        $selected = (isset($_GET['carrera']) && $_GET['carrera'] == $carrera['nombre_carrera']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($carrera['nombre_carrera']) . "' $selected>" . htmlspecialchars(mb_strtoupper($carrera['nombre_carrera'], 'UTF-8')) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-secondary mb-2 ml-2 font-weight-bold" id="btnLimpiarFiltros">
                            <i class="fas fa-times mr-1"></i> LIMPIAR
                        </button>
                    </form>
                </div>
            </div>
                        
                        <!-- Enlace de depuración -->
                        <div class="ml-2 mb-3">
                             <?php if (tienePermiso('gestion_grado')): ?>
                            <small>
                                <?php if (!isset($_GET['debug'])): ?>
                                    <a href="grado.php?debug=1<?php echo isset($_GET['estado']) ? '&estado=' . $_GET['estado'] : ''; ?><?php echo isset($_GET['carrera']) ? '&carrera=' . $_GET['carrera'] : ''; ?><?php echo isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : ''; ?>" class="text-muted">[DEBUG] Ver evaluación</a>
                                <?php else: ?>
                                    <a href="grado.php<?php echo isset($_GET['estado']) ? '?estado=' . $_GET['estado'] : ''; ?><?php echo isset($_GET['carrera']) ? '&carrera=' . $_GET['carrera'] : ''; ?><?php echo isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : ''; ?>" class="text-muted">[DEBUG] Ocultar</a>
                                <?php endif; ?>
                            </small>
                            <?php endif; ?>
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
                    <h6 class="m-0 font-weight-bold text-primary">LISTA DE ESTUDIANTES</h6>
                    <div>
                        <button class="btn btn-success btn-sm font-weight-bold" onclick="verEstudiantesCumplenRequisitos()">
                            <i class="fas fa-graduation-cap mr-1"></i> VER CUMPLE REQUISITOS
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    // Obtener datos iniciales con paginación
                    $datos_paginacion = obtener_estudiantes_graduacion_paginados($_GET, $pagina_actual, $registros_por_pagina);
                    $estudiantes = $datos_paginacion['resultados'];
                    $total_registros = $datos_paginacion['total_registros'];
                    $total_paginas = $datos_paginacion['total_paginas'];
                    ?>
                    
                    <!-- Información de paginación -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6" id="infoPaginacionContainer">
                            <?php echo generar_html_info_grado($total_registros, $pagina_actual, $registros_por_pagina); ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <label class="mr-2 font-weight-bold text-uppercase text-muted"><small>MOSTRAR:</small></label>
                            <select class="form-control form-control-sm d-inline-block w-auto text-uppercase font-weight-bold" id="registros_por_pagina" onchange="cambiarRegistrosPorPagina(this.value)">
                                <option value="10" <?php echo $registros_por_pagina == 10 ? 'selected' : ''; ?>>10 POR PÁGINA</option>
                                <option value="20" <?php echo $registros_por_pagina == 20 ? 'selected' : ''; ?>>20 POR PÁGINA</option>
                                <option value="50" <?php echo $registros_por_pagina == 50 ? 'selected' : ''; ?>>50 POR PÁGINA</option>
                                <option value="100" <?php echo $registros_por_pagina == 100 ? 'selected' : ''; ?>>100 POR PÁGINA</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-uppercase" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>CÉDULA</th>
                                    <th>NOMBRE COMPLETO</th>
                                    <th>CARRERA</th>
                                    <th>ESTADO</th>
                                    <th>FECHA GRADUACIÓN</th>
                                     <?php if (tienePermiso('gestion_grado')): ?>
                                    <th>ACCIONES</th>
                                        <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody id="tableBodyGraduacion">
                                <?php
                                if (is_array($estudiantes) && count($estudiantes) > 0) {
                                    foreach ($estudiantes as $estudiante) {
                                        $nombre_carrera = $estudiante['nombre_carrera'] ?: 'Carrera ' . $estudiante['carrera'];
                                        echo "<tr class='text-uppercase'>";
                                        echo "<td>" . htmlspecialchars($estudiante['idusuario']) . "</td>";
                                        echo "<td><strong>" . htmlspecialchars(mb_strtoupper($estudiante['nombre'], 'UTF-8')) . "</strong></td>";
                                        echo "<td>" . htmlspecialchars(mb_strtoupper($nombre_carrera, 'UTF-8')) . "</td>";
                                        echo "<td>" . obtener_badge_estado($estudiante['estado']) . "</td>";
                                        echo "<td>" . ($estudiante['fecha_graduacion'] ? date('d/m/Y', strtotime($estudiante['fecha_graduacion'])) : '-') . "</td>";
                                        if (tienePermiso('gestion_grado')) {
                                            echo "<td class='text-nowrap'>" . generar_botones_accion($estudiante) . "</td>";
                                        }
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center font-weight-bold text-muted p-4'><i class='fas fa-exclamation-circle text-warning mr-2'></i> NO SE ENCONTRARON ESTUDIANTES</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Contenedor dinámico de paginación -->
                    <div id="navPaginacionContainer" class="mt-3">
                        <?php echo generar_html_paginacion_grado($total_paginas, $pagina_actual); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmar graduación -->
<div class="modal fade" id="modalGraduacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-success text-white font-weight-bold">
                    <h5 class="modal-title"><i class="fas fa-graduation-cap mr-1"></i> CONFIRMAR GRADUACIÓN</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold">¿ESTÁ SEGURO DE MARCAR A ESTE ESTUDIANTE COMO GRADUADO?</p>
                    <input type="hidden" name="id_usuario" id="id_usuario_modal">
                    <div class="form-group">
                        <label class="font-weight-bold">OBSERVACIONES:</label>
                        <textarea class="form-control text-uppercase" name="observaciones" rows="3" placeholder="OBSERVACIONES OPCIONALES..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" name="marcar_graduado" class="btn btn-success font-weight-bold">CONFIRMAR GRADUACIÓN</button>
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
    $('#estado_select').val('cumple_requisitos').trigger('change');
}

function cambiarRegistrosPorPagina(cantidad) {
    realizarBusquedaGraduacionPost(1, cantidad);
}

function realizarBusquedaGraduacionPost(pagina, cantidadRegistros) {
    pagina = pagina || 1;
    var buscar = $('#buscar_input').val();
    var estado = $('#estado_select').val();
    var carrera = $('#carrera_select').val();
    var registros = cantidadRegistros || $('#registros_por_pagina').val() || 20;

    $.ajax({
        url: 'grado.php',
        type: 'POST',
        data: {
            ajax_filtrar: 1,
            buscar: buscar,
            estado: estado,
            carrera: carrera,
            pagina: pagina,
            registros_por_pagina: registros
        },
        dataType: 'json',
        success: function(response) {
            if (response && response.status === 'success') {
                $('#tableBodyGraduacion').html(response.html);
                $('#infoPaginacionContainer').html(response.html_info);
                $('#navPaginacionContainer').html(response.html_paginacion);
            }
        }
    });
}

$(document).ready(function() {
    var searchTimeout = null;

    // Búsqueda en tiempo real por POST en tiempo de ejecución (actualiza tabla, contador y paginación)
    $('#buscar_input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            realizarBusquedaGraduacionPost(1);
        }, 250);
    });

    // Filtro inmediato al cambiar Selects
    $('#estado_select, #carrera_select').on('change', function() {
        realizarBusquedaGraduacionPost(1);
    });

    // Botón Limpiar sin recargar ni usar GET en URL
    $('#btnLimpiarFiltros').on('click', function() {
        $('#buscar_input').val('');
        $('#estado_select').val('');
        $('#carrera_select').val('');
        realizarBusquedaGraduacionPost(1);
    });
});
</script>