<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');

// Verificar permiso de edición de estudiantes
$puedeEditar = isset($_SESSION['user']['editar_estudiante']) && $_SESSION['user']['editar_estudiante'] == 1;

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener estudiantes (SOLO los que tienen estudiante = 1)
$query = "SELECT 
    id,
    idusuario,
    nombre,
    username,
    email,
    tlf,
    cel,
    direccion,
    ciudad,
    estado,
    municipio,
    parroquia,
    fecha_ingreso,
    fecha_nac,
    status,
    carrera,
    genero,
    embarazada,
    edo_civil,
    num_telf_opc,
    foto_perfil
FROM users 
WHERE estudiante = 1
ORDER BY nombre ASC";

$result = $db->query($query);
$estudiantes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calcular edad y menor de edad
        $edad = '';
        $esMenor = false;
        if (!empty($row['fecha_nac'])) {
            try {
                $fechaNac = new DateTime($row['fecha_nac']);
                $hoy = new DateTime();
                $edad = $fechaNac->diff($hoy)->y;
                $esMenor = ($edad < 18);
            } catch (Exception $e) {
                $edad = '';
                $esMenor = false;
            }
        }
        $row['edad'] = $edad;
        $row['es_menor'] = $esMenor;
        $estudiantes[] = $row;
    }
}

// Obtener carreras únicas para el filtro
$carrerasUnicas = array_unique(array_column($estudiantes, 'carrera'));
sort($carrerasUnicas);

// Obtener ciudades únicas
$ciudades = array_unique(array_column($estudiantes, 'ciudad'));
sort($ciudades);

// Contar estudiantes por status y estadísticas adicionales
$totalEstudiantes = count($estudiantes);
$activos = 0;
$inactivos = 0;
$embarazadas = 0;
$menores = 0;
$mayores = 0;
$estudiantesPorCarrera = [];
$masculinos = 0;
$femeninos = 0;
$solteros = 0;
$casados = 0;

foreach ($estudiantes as $estudiante) {
    $status = $estudiante['status'] ?? 0;
    if ($status == 1) {
        $activos++;
    } else {
        $inactivos++;
    }

    // Contar por género
    $genero = $estudiante['genero'] ?? '';
    if ($genero == 'Masculino') $masculinos++;
    if ($genero == 'Femenino') $femeninos++;

    // Contar mujeres embarazadas
    $esFemenino = isset($estudiante['genero']) && trim($estudiante['genero']) === 'Femenino';
    $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
    if ($esFemenino && $estaEmbarazada) {
        $embarazadas++;
    }

    // Contar menores y mayores de edad
    if ($estudiante['es_menor']) {
        $menores++;
    } elseif ($estudiante['edad'] >= 18 && $estudiante['edad'] !== '') {
        $mayores++;
    }

    // Contar estado civil
    $edoCivil = $estudiante['edo_civil'] ?? '';
    if ($edoCivil == 'Soltero/a') $solteros++;
    if ($edoCivil == 'Casado/a') $casados++;

    // Contar por carrera
    $carrera = $estudiante['carrera'] ?? 'Sin Carrera';
    if (!isset($estudiantesPorCarrera[$carrera])) {
        $estudiantesPorCarrera[$carrera] = 0;
    }
    $estudiantesPorCarrera[$carrera]++;
}

include("includes/head.php");
?>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <div class="py-3 py-sm-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <h5 class="mb-2 mb-sm-0"><i class="fas fa-users me-2"></i>Listado de Estudiantes</h5>
                        <div>
                            <button id="toggleEstadisticas" class="btn btn-outline-light btn-sm mb-1 mb-sm-0 me-2" onclick="toggleEstadisticas()">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </button>
                            <button id="btnGenerarReporte" class="btn btn-danger btn-sm mb-1 mb-sm-0 me-2">
                                <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                            </button>
                            <?php if (tienePermiso('agregar_estudiante')): ?>
                                <button class="btn btn-success btn-sm mb-1 mb-sm-0" onclick="abrirModalNuevoEstudiante()">
                                    <i class="fas fa-plus-circle"></i> Nuevo Estudiante
                                </button>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-2 p-sm-3">
                        <!-- Conteos de estudiantes -->
                        <div id="estadisticas-row" class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-primary text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $totalEstudiantes; ?></h4>
                                        <p class="card-text">Total de Estudiantes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-success text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-check fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $activos; ?></h4>
                                        <p class="card-text">Estudiantes Activos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-secondary text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-times fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $inactivos; ?></h4>
                                        <p class="card-text">Estudiantes Inactivos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-info text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-baby-carriage fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $embarazadas; ?></h4>
                                        <p class="card-text">Mujeres Embarazadas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-warning text-dark h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-child fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $menores; ?></h4>
                                        <p class="card-text">Menores de Edad</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-dark text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $mayores; ?></h4>
                                        <p class="card-text">Mayores de Edad</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-danger text-white h-100 shadow-sm">
                                    <div class="card-body text-center">
                                        <i class="fas fa-mars fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $masculinos; ?></h4>
                                        <p class="card-text">Masculinos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-purple text-white h-100 shadow-sm" style="background-color: #6f42c1;">
                                    <div class="card-body text-center">
                                        <i class="fas fa-venus fa-2x mb-2"></i>
                                        <h4 class="card-title"><?php echo $femeninos; ?></h4>
                                        <p class="card-text">Femeninos</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros Avanzados -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-filter me-2"></i>Filtros Avanzados
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Buscador por Cédula -->
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-primary text-white">
                                                        <i class="fas fa-id-card"></i>
                                                    </span>
                                                    <input type="text" 
                                                           id="buscadorCedula" 
                                                           class="form-control" 
                                                           placeholder="Buscar por cédula (Ej: V12345678)..."
                                                           autocomplete="off">
                                                    <button type="button" id="limpiarBusqueda" class="btn btn-secondary">
                                                        <i class="fas fa-times"></i> Limpiar todos los filtros
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Filtro por Carrera -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-graduation-cap"></i> Carrera</strong>
                                                    </div>
                                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-carrera" type="checkbox" value="todas" id="filtroTodasCarreras" checked>
                                                            <label class="form-check-label" for="filtroTodasCarreras">
                                                                <strong>Todas las carreras</strong>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                        <?php foreach ($carrerasUnicas as $carrera): ?>
                                                            <?php if (!empty($carrera)): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-carrera" type="checkbox" value="<?php echo htmlspecialchars($carrera); ?>" id="filtroCarrera_<?php echo md5($carrera); ?>">
                                                                <label class="form-check-label" for="filtroCarrera_<?php echo md5($carrera); ?>">
                                                                    <?php echo htmlspecialchars($carrera); ?>
                                                                </label>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Género -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-venus-mars"></i> Género</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="masculino" id="filtroMasculino">
                                                            <label class="form-check-label" for="filtroMasculino">
                                                                Masculino
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="femenino" id="filtroFemenino">
                                                            <label class="form-check-label" for="filtroFemenino">
                                                                Femenino
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Estado Civil -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-heart"></i> Estado Civil</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="soltero" id="filtroSoltero">
                                                            <label class="form-check-label" for="filtroSoltero">
                                                                Soltero/a
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="casado" id="filtroCasado">
                                                            <label class="form-check-label" for="filtroCasado">
                                                                Casado/a
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Estado (Activo/Inactivo) -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-user-check"></i> Estado</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="activo" id="filtroActivo">
                                                            <label class="form-check-label" for="filtroActivo">
                                                                Activo
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="inactivo" id="filtroInactivo">
                                                            <label class="form-check-label" for="filtroInactivo">
                                                                Inactivo
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <!-- Filtro por Embarazo -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-baby-carriage"></i> Embarazo</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="embarazada" id="filtroEmbarazada">
                                                            <label class="form-check-label" for="filtroEmbarazada">
                                                                Solo Embarazadas
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Edad -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-child"></i> Edad</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="menor" id="filtroMenor">
                                                            <label class="form-check-label" for="filtroMenor">
                                                                Menores de 18 años
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro" type="checkbox" value="mayor" id="filtroMayor">
                                                            <label class="form-check-label" for="filtroMayor">
                                                                Mayores de 18 años
                                                            </label>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-6">
                                                                <input type="number" class="form-control form-control-sm" id="edadMin" placeholder="Edad mín">
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="number" class="form-control form-control-sm" id="edadMax" placeholder="Edad máx">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Rango de Fechas -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-calendar-alt"></i> Fecha Ingreso</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label>Desde</label>
                                                            <input type="date" class="form-control form-control-sm" id="fechaIngresoDesde">
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <label>Hasta</label>
                                                            <input type="date" class="form-control form-control-sm" id="fechaIngresoHasta">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Filtro por Ciudad -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card">
                                                    <div class="card-header bg-light">
                                                        <strong><i class="fas fa-city"></i> Ciudad</strong>
                                                    </div>
                                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                                        <?php foreach ($ciudades as $ciudad): ?>
                                                            <?php if (!empty($ciudad)): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-ciudad" type="checkbox" value="<?php echo htmlspecialchars($ciudad); ?>" id="filtroCiudad_<?php echo md5($ciudad); ?>">
                                                                <label class="form-check-label" for="filtroCiudad_<?php echo md5($ciudad); ?>">
                                                                    <?php echo htmlspecialchars($ciudad); ?>
                                                                </label>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tablaEstudiantes">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Cédula</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Carrera</th>
                                        <th>Género</th>
                                        <th>Edad</th>
                                        <th>Estado Civil</th>
                                        <th>Ciudad</th>
                                        <th>Status</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    <?php if (count($estudiantes) > 0): ?>
                                        <?php foreach ($estudiantes as $estudiante): 
                                            $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
                                            $esFemenino = isset($estudiante['genero']) && trim($estudiante['genero']) === 'Femenino';
                                            $cedula = $estudiante['idusuario'] ?? '';
                                            $status = $estudiante['status'] ?? 0;
                                            $genero = strtolower($estudiante['genero'] ?? '');
                                            $edad = $estudiante['edad'] ?? '';
                                            $edoCivil = strtolower($estudiante['edo_civil'] ?? '');
                                            $fechaIngreso = $estudiante['fecha_ingreso'] ?? '';
                                            $ciudad = $estudiante['ciudad'] ?? '';
                                            $carrera = $estudiante['carrera'] ?? '';
                                        ?>
                                            <tr data-cedula="<?php echo htmlspecialchars(strtoupper($cedula)); ?>"
                                                data-genero="<?php echo $genero; ?>"
                                                data-status="<?php echo $status; ?>"
                                                data-embarazada="<?php echo $estaEmbarazada ? '1' : '0'; ?>"
                                                data-edad="<?php echo $edad; ?>"
                                                data-menor="<?php echo $estudiante['es_menor'] ? '1' : '0'; ?>"
                                                data-mayor="<?php echo ($edad >= 18 && $edad != '') ? '1' : '0'; ?>"
                                                data-edo-civil="<?php echo $edoCivil; ?>"
                                                data-fecha-ingreso="<?php echo $fechaIngreso; ?>"
                                                data-ciudad="<?php echo strtolower($ciudad); ?>"
                                                data-carrera="<?php echo strtolower($carrera); ?>">
                                                <td><?php echo htmlspecialchars($cedula); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['username'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['tlf'] ?? $estudiante['cel'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($carrera); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($estudiante['genero'] ?? ''); ?>
                                                    <?php if ($esFemenino && $estaEmbarazada): ?>
                                                        <span class="badge bg-info ms-1" title="Embarazada">🤰</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $edad; ?></td>
                                                <td><?php echo htmlspecialchars($estudiante['edo_civil'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($ciudad); ?></td>
                                                <td>
                                                    <?php echo ($status == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?>
                                                 </td>
                                                <td><?php echo !empty($fechaIngreso) ? date('d/m/Y', strtotime($fechaIngreso)) : ''; ?></td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button class="btn btn-info btn-details btn-sm" data-id="<?php echo $estudiante['id']; ?>" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <?php if ($puedeEditar): ?>
                                                            <button class="btn btn-warning btn-sm btn-edit" data-id="<?php echo $estudiante['id']; ?>" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                  </td>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="13" class="text-center">No hay estudiantes registrados</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Reporte -->
<div class="modal fade" id="reporteModal" tabindex="-1" aria-labelledby="reporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="reporteModalLabel">
                    <i class="fas fa-file-pdf me-2"></i> Generar Reporte de Estudiantes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Se generará un reporte en PDF con los estudiantes actualmente filtrados.
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chart-bar"></i> Incluir estadísticas</label>
                    <select id="incluirEstadisticas" class="form-control">
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnGenerarPDF" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modales (detalle, editar, agregar, resultado) -->
<div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detalleModalLabel">Detalles del Estudiante</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleEstudianteContent">
                <div class="text-center my-5 py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando información del estudiante...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarEstudianteModal" tabindex="-1" aria-labelledby="editarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editarEstudianteModalLabel"><i class="fas fa-user-edit me-2"></i> Editar Estudiante</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editarEstudianteContent">
                <div class="text-center my-5 py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3">Cargando formulario de edición...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="agregarEstudianteModal" tabindex="-1" aria-labelledby="agregarEstudianteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="agregarEstudianteModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Agregar Nuevo Estudiante
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                $tiposCedula = obtenerTiposCedula($db);
                $estadosCiviles = obtenerEstadosCiviless($db);
                $tiposVivienda = obtenerTiposVivienda($db);
                $tenenciasVivienda = obtenerTenenciaViviendas($db);
                $opcionesStatus = obtenerOpcionesStatus($db);
                $carreras = obtenerTodasLasCarreras();
                $ingresos = obtenerIngresos($db);
                $esModal = true;
                ?>
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                        <form id="formEstudianteModal" method="post" enctype="multipart/form-data">
                            <?php 
                            $esModal = true;
                            include('_formulario_estudiante.php'); 
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="resultadoModal" tabindex="-1" aria-labelledby="resultadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="resultadoModalHeader">
                <h5 class="modal-title" id="resultadoModalLabel">Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="resultadoModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<style>
.close {
    font-size: 1.5rem;
    font-weight: bold;
    opacity: 0.8;
    padding: 0.5rem;
    line-height: 1;
    background: transparent;
    border: none;
    cursor: pointer;
}

.close:hover {
    opacity: 1;
}

.modal-header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-content {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.bg-purple {
    background-color: #6f42c1 !important;
}

@media (max-width: 767.98px) {
    .card-header h5 {
        font-size: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
    }
    
    .table td, .table th {
        font-size: 0.75rem;
        padding: 0.4rem;
        vertical-align: middle;
    }
    
    .modal-body {
        padding: 0.75rem;
    }
    
    .d-flex.flex-wrap {
        gap: 0.25rem !important;
    }
}
</style>

<script>
// Función para obtener los IDs de los estudiantes filtrados actualmente
function getEstudiantesFiltrados() {
    let filas = document.querySelectorAll('#tablaBody tr');
    let estudiantesFiltrados = [];
    
    filas.forEach(fila => {
        if (fila.style.display !== 'none') {
            let estudianteId = fila.querySelector('.btn-details')?.getAttribute('data-id');
            if (estudianteId) {
                estudiantesFiltrados.push(estudianteId);
            }
        }
    });
    
    return estudiantesFiltrados;
}

// Función para generar reporte PDF
function generarReportePDF() {
    let estudiantesIds = getEstudiantesFiltrados();
    let incluirEstadisticas = document.getElementById('incluirEstadisticas').value;
    
    if (estudiantesIds.length === 0) {
        mostrarMensaje('Sin resultados', 'No hay estudiantes con los filtros seleccionados para generar el reporte', false);
        return;
    }
    
    // Abrir el PDF en una nueva ventana
    let url = `constancias/generar_reporte_pdf.php?ids=${estudiantesIds.join(',')}&estadisticas=${incluirEstadisticas}`;
    window.open(url, '_blank');
    
    $('#reporteModal').modal('hide');
    mostrarMensaje('Éxito', 'Reporte PDF generado exitosamente', true);
}

// Función para aplicar todos los filtros
function aplicarFiltros() {
    let termino = document.getElementById('buscadorCedula').value.toUpperCase();
    
    // Obtener valores de los checkboxes
    let filtroMasculino = document.getElementById('filtroMasculino').checked;
    let filtroFemenino = document.getElementById('filtroFemenino').checked;
    let filtroActivo = document.getElementById('filtroActivo').checked;
    let filtroInactivo = document.getElementById('filtroInactivo').checked;
    let filtroEmbarazada = document.getElementById('filtroEmbarazada').checked;
    let filtroMenor = document.getElementById('filtroMenor').checked;
    let filtroMayor = document.getElementById('filtroMayor').checked;
    let filtroSoltero = document.getElementById('filtroSoltero').checked;
    let filtroCasado = document.getElementById('filtroCasado').checked;
    
    let edadMin = document.getElementById('edadMin').value;
    let edadMax = document.getElementById('edadMax').value;
    let fechaDesde = document.getElementById('fechaIngresoDesde').value;
    let fechaHasta = document.getElementById('fechaIngresoHasta').value;
    
    // Obtener carreras seleccionadas
    let carrerasSeleccionadas = [];
    let todasCarreras = document.getElementById('filtroTodasCarreras').checked;
    if (!todasCarreras) {
        document.querySelectorAll('.filtro-carrera:checked').forEach(cb => {
            if (cb.value !== 'todas') carrerasSeleccionadas.push(cb.value.toLowerCase());
        });
    }
    
    // Obtener ciudades seleccionadas
    let ciudadesSeleccionadas = [];
    document.querySelectorAll('.filtro-ciudad:checked').forEach(cb => {
        ciudadesSeleccionadas.push(cb.value.toLowerCase());
    });
    
    let filas = document.querySelectorAll('#tablaBody tr');
    let encontrados = 0;
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por cédula
        let cedula = fila.getAttribute('data-cedula') || '';
        if (termino !== '' && !cedula.includes(termino)) {
            mostrar = false;
        }
        
        // Filtro por carrera
        if (mostrar && !todasCarreras && carrerasSeleccionadas.length > 0) {
            let carrera = fila.getAttribute('data-carrera') || '';
            if (!carrerasSeleccionadas.includes(carrera)) {
                mostrar = false;
            }
        }
        
        // Filtro por ciudad
        if (mostrar && ciudadesSeleccionadas.length > 0) {
            let ciudad = fila.getAttribute('data-ciudad') || '';
            if (!ciudadesSeleccionadas.includes(ciudad)) {
                mostrar = false;
            }
        }
        
        // Filtro por género
        if (mostrar && (filtroMasculino || filtroFemenino)) {
            let genero = fila.getAttribute('data-genero') || '';
            if (filtroMasculino && filtroFemenino) {
                // Ambos seleccionados, mostrar todos
            } else if (filtroMasculino && genero !== 'masculino') {
                mostrar = false;
            } else if (filtroFemenino && genero !== 'femenino') {
                mostrar = false;
            }
        }
        
        // Filtro por estado
        if (mostrar && (filtroActivo || filtroInactivo)) {
            let status = fila.getAttribute('data-status') || '';
            if (filtroActivo && filtroInactivo) {
                // Ambos seleccionados, mostrar todos
            } else if (filtroActivo && status !== '1') {
                mostrar = false;
            } else if (filtroInactivo && status !== '0') {
                mostrar = false;
            }
        }
        
        // Filtro por embarazada
        if (mostrar && filtroEmbarazada) {
            let embarazada = fila.getAttribute('data-embarazada') || '0';
            let genero = fila.getAttribute('data-genero') || '';
            if (embarazada !== '1' || genero !== 'femenino') {
                mostrar = false;
            }
        }
        
        // Filtro por edad (menor/mayor)
        if (mostrar && (filtroMenor || filtroMayor)) {
            let menor = fila.getAttribute('data-menor') || '0';
            let mayor = fila.getAttribute('data-mayor') || '0';
            if (filtroMenor && filtroMayor) {
                // Ambos seleccionados, mostrar todos
            } else if (filtroMenor && menor !== '1') {
                mostrar = false;
            } else if (filtroMayor && mayor !== '1') {
                mostrar = false;
            }
        }
        
        // Filtro por rango de edad
        if (mostrar && (edadMin !== '' || edadMax !== '')) {
            let edad = parseInt(fila.getAttribute('data-edad')) || 0;
            if (edadMin !== '' && edad < parseInt(edadMin)) mostrar = false;
            if (edadMax !== '' && edad > parseInt(edadMax)) mostrar = false;
        }
        
        // Filtro por estado civil
        if (mostrar && (filtroSoltero || filtroCasado)) {
            let edoCivil = fila.getAttribute('data-edo-civil') || '';
            if (filtroSoltero && edoCivil !== 'soltero/a') mostrar = false;
            if (filtroCasado && edoCivil !== 'casado/a') mostrar = false;
        }
        
        // Filtro por fecha de ingreso
        if (mostrar && (fechaDesde !== '' || fechaHasta !== '')) {
            let fechaIngreso = fila.getAttribute('data-fecha-ingreso') || '';
            if (fechaDesde !== '' && fechaIngreso < fechaDesde) mostrar = false;
            if (fechaHasta !== '' && fechaIngreso > fechaHasta) mostrar = false;
        }
        
        // Aplicar visibilidad
        if (mostrar) {
            fila.style.display = '';
            encontrados++;
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Actualizar mensaje
    let mensajeDiv = document.getElementById('mensajeBusqueda');
    if(!mensajeDiv) {
        mensajeDiv = document.createElement('div');
        mensajeDiv.id = 'mensajeBusqueda';
        mensajeDiv.className = 'alert alert-info mt-3';
        document.querySelector('.table-responsive').appendChild(mensajeDiv);
    }
    
    let totalFilas = filas.length;
    if(encontrados === 0) {
        mensajeDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> No se encontraron estudiantes con los filtros seleccionados`;
        mensajeDiv.className = 'alert alert-warning mt-3';
    } else {
        mensajeDiv.innerHTML = `<i class="fas fa-check-circle"></i> Se encontraron ${encontrados} de ${totalFilas} estudiantes`;
        mensajeDiv.className = 'alert alert-success mt-3';
    }
}

// Manejar el checkbox "Todas las carreras"
document.getElementById('filtroTodasCarreras').addEventListener('change', function() {
    let checkboxesCarrera = document.querySelectorAll('.filtro-carrera');
    if (this.checked) {
        checkboxesCarrera.forEach(cb => {
            if (cb.value !== 'todas') cb.checked = false;
        });
    }
    aplicarFiltros();
});

// Cuando se selecciona una carrera específica, desmarcar "Todas"
document.querySelectorAll('.filtro-carrera').forEach(cb => {
    if (cb.value !== 'todas') {
        cb.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('filtroTodasCarreras').checked = false;
            }
            aplicarFiltros();
        });
    }
});

// Event listeners para filtros de ciudad
document.querySelectorAll('.filtro-ciudad').forEach(cb => {
    cb.addEventListener('change', aplicarFiltros);
});

// Event listeners para todos los filtros
const filtros = document.querySelectorAll('.filtro');
filtros.forEach(checkbox => {
    checkbox.addEventListener('change', aplicarFiltros);
});

// Event listeners para inputs de rango
document.getElementById('edadMin').addEventListener('input', aplicarFiltros);
document.getElementById('edadMax').addEventListener('input', aplicarFiltros);
document.getElementById('fechaIngresoDesde').addEventListener('change', aplicarFiltros);
document.getElementById('fechaIngresoHasta').addEventListener('change', aplicarFiltros);
document.getElementById('buscadorCedula').addEventListener('keyup', aplicarFiltros);

// Botón limpiar todos los filtros
document.getElementById('limpiarBusqueda').addEventListener('click', function() {
    // Limpiar búsqueda
    document.getElementById('buscadorCedula').value = '';
    
    // Limpiar todos los checkboxes de filtros
    document.querySelectorAll('.filtro').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Limpiar checkboxes de ciudad
    document.querySelectorAll('.filtro-ciudad').forEach(cb => {
        cb.checked = false;
    });
    
    // Limpiar checkboxes de carrera
    document.querySelectorAll('.filtro-carrera').forEach(cb => {
        if (cb.value !== 'todas') cb.checked = false;
    });
    document.getElementById('filtroTodasCarreras').checked = true;
    
    // Limpiar inputs de rango
    document.getElementById('edadMin').value = '';
    document.getElementById('edadMax').value = '';
    document.getElementById('fechaIngresoDesde').value = '';
    document.getElementById('fechaIngresoHasta').value = '';
    
    aplicarFiltros();
    document.getElementById('buscadorCedula').focus();
});

// Botón para generar reporte
document.getElementById('btnGenerarReporte').addEventListener('click', function() {
    let estudiantesFiltrados = getEstudiantesFiltrados();
    if (estudiantesFiltrados.length === 0) {
        mostrarMensaje('Sin resultados', 'No hay estudiantes con los filtros seleccionados para generar el reporte', false);
        return;
    }
    $('#reporteModal').modal('show');
});

document.getElementById('btnGenerarPDF').addEventListener('click', generarReportePDF);

// Función para toggle estadísticas
window.toggleEstadisticas = function() {
    const row = document.getElementById('estadisticas-row');
    const button = document.getElementById('toggleEstadisticas');
    
    if (row.style.display === 'none') {
        row.style.display = 'flex';
        button.innerHTML = '<i class="fas fa-chart-bar"></i> Ocultar Estadísticas';
    } else {
        row.style.display = 'none';
        button.innerHTML = '<i class="fas fa-chart-bar"></i> Mostrar Estadísticas';
    }
};

// Modal handlers
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-details')) {
        const button = e.target.closest('.btn-details');
        const studentId = button.getAttribute('data-id');
        loadStudentDetails(studentId);
    }
    
    if (e.target.closest('.btn-edit')) {
        const button = e.target.closest('.btn-edit');
        const studentId = button.getAttribute('data-id');
        loadEditStudentForm(studentId);
    }
});

function loadStudentDetails(studentId) {
    const modalContent = document.getElementById('detalleEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#detalleModal').modal('show');
    
    fetch(`detalle_estudiante.php?id=${studentId}`)
        .then(response => response.text())
        .then(data => modalContent.innerHTML = data)
        .catch(error => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`);
}

function loadEditStudentForm(studentId) {
    const modalContent = document.getElementById('editarEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#editarEstudianteModal').modal('show');
    
    fetch(`editar_estudiante_modal.php?id=${studentId}`)
        .then(response => response.text())
        .then(data => {
            modalContent.innerHTML = data;
            const editForm = document.getElementById('formEditarEstudiante');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitEditForm(this);
                });
            }
        })
        .catch(error => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`);
}

function submitEditForm(form) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitButton.disabled = true;
    
    fetch('actualizar_estudiante.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editarEstudianteModal').modal('hide');
            mostrarMensaje('Éxito', data.message || 'Estudiante actualizado', true, true);
        } else {
            mostrarMensaje('Error', data.message || 'Error al actualizar', false);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    })
    .catch(error => {
        mostrarMensaje('Error', 'Error de conexión', false);
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
}

function mostrarMensaje(titulo, mensaje, esExito = true, recargar = false) {
    const header = document.getElementById('resultadoModalHeader');
    const body = document.getElementById('resultadoModalBody');
    const label = document.getElementById('resultadoModalLabel');
    
    if (header && body && label) {
        header.className = `modal-header bg-${esExito ? 'success' : 'danger'} text-white`;
        body.innerHTML = `<div class="text-center"><i class="fas fa-${esExito ? 'check-circle' : 'exclamation-circle'} fa-3x mb-3"></i><h4>${titulo}</h4><p>${mensaje}</p></div>`;
        label.textContent = titulo;
        $('#resultadoModal').modal('show');
        
        if (recargar) {
            $('#resultadoModal').on('hidden.bs.modal', () => location.reload());
        }
    } else {
        alert(mensaje);
    }
}

// Limpiar modales al cerrar
$('#detalleModal, #editarEstudianteModal, #agregarEstudianteModal').on('hidden.bs.modal', function() {
    const contentId = this.id === 'detalleModal' ? 'detalleEstudianteContent' : 
                     (this.id === 'editarEstudianteModal' ? 'editarEstudianteContent' : null);
    if (contentId && document.getElementById(contentId)) {
        if (this.id !== 'agregarEstudianteModal') document.getElementById(contentId).innerHTML = '';
    }
});

// Formulario nuevo estudiante
const formEstudiante = document.getElementById('formEstudianteModal');
if (formEstudiante) {
    formEstudiante.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        submitButton.disabled = true;
        
        fetch('procesar_estudiante.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#agregarEstudianteModal').modal('hide');
                mostrarMensaje('Éxito', data.message || 'Estudiante registrado', true, true);
            } else {
                mostrarMensaje('Error', data.message || 'Error al guardar', false);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        })
        .catch(error => {
            mostrarMensaje('Error', 'Error de conexión', false);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
}

function abrirModalNuevoEstudiante() {
    $('#agregarEstudianteModal').modal('show');
}

// Aplicar filtros al cargar
document.addEventListener('DOMContentLoaded', function() {
    aplicarFiltros();
});
</script>

<?php include("includes/footer.php"); ?>