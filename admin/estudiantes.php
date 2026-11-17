<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Lista de estudiantes";
include('../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('ver_estudiantes');

// Verificar permiso de edición de estudiantes
$puedeEditar = isset($_SESSION['user']['editar_estudiante']) && $_SESSION['user']['editar_estudiante'] == 1;

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Obtener carreras para mostrar nombres
$carreras = obtenerTodasLasCarreras();
$carreraMap = [];
if ($carreras && is_array($carreras)) {
    foreach ($carreras as $carrera) {
        $carreraMap[$carrera['id']] = $carrera['nombre'];
    }
}

// Mapeo de estados federales
$estadoMap = [];
$resEst = $db->query("SELECT id_estado, estado FROM estados");
if ($resEst) {
    while ($re = $resEst->fetch_assoc()) {
        $estadoMap[$re['id_estado']] = $re['estado'];
    }
}

// Obtener TODOS los estudiantes con JOIN a ciudades, graduados y nuevos campos
$query = "SELECT 
    u.id,
    u.idusuario,
    u.nombre,
    u.username,
    u.email,
    u.tlf,
    u.cel,
    u.direccion,
    u.ciudad as ciudad_id,
    u.estado,
    u.municipio,
    u.parroquia,
    u.fecha_ingreso,
    u.fecha_nac,
    u.status,
    u.carrera,
    u.genero,
    u.embarazada,
    u.edo_civil,
    u.num_telf_opc,
    u.foto_perfil,
    u.sede,
    u.turno,
    u.discapacidad,
    u.etnia,
    u.inscrito_por,
    u.asignado_opsu,
    u.fecha_graduacion as user_fecha_graduacion,
    c.ciudad as nombre_ciudad,
    g.id as id_graduado,
    g.estado as estado_graduado,
    g.fecha_graduacion as grad_fecha_graduacion
FROM users u
LEFT JOIN ciudades c ON u.ciudad = c.id_ciudad
LEFT JOIN graduados g ON u.id = g.id_usuario
WHERE u.estudiante = 1
ORDER BY u.nombre ASC";

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
        
        // Obtener nombre de la carrera
        $carreraId = $row['carrera'];
        $row['nombre_carrera'] = isset($carreraMap[$carreraId]) ? $carreraMap[$carreraId] : 'Sin Carrera';
        
        // Obtener nombre de la ciudad
        $row['ciudad'] = isset($row['nombre_ciudad']) && !empty($row['nombre_ciudad']) ? $row['nombre_ciudad'] : 'No especificada';
        
        // Sede por defecto
        $row['sede'] = isset($row['sede']) && !empty($row['sede']) ? $row['sede'] : 'No especificada';

        // Normalizar Estado Geográfico (Entidad Federal)
        $estadoRaw = trim((string)($row['estado'] ?? ''));
        if (is_numeric($estadoRaw) && isset($estadoMap[$estadoRaw])) {
            $row['nombre_estado'] = $estadoMap[$estadoRaw];
        } elseif (!empty($estadoRaw) && !is_numeric($estadoRaw)) {
            $row['nombre_estado'] = $estadoRaw;
        } else {
            $row['nombre_estado'] = 'No especificado';
        }

        // Normalizar Modalidad Inscrito por
        $inscrito = trim((string)($row['inscrito_por'] ?? ''));
        if (empty($inscrito)) {
            if (!empty($row['asignado_opsu']) && $row['asignado_opsu'] === 'Sí') {
                $inscrito = 'OPSU';
            } else {
                $inscrito = 'No especificado';
            }
        }
        $row['inscrito_por'] = $inscrito;

        // Normalizar Turno
        $row['turno'] = !empty($row['turno']) ? trim($row['turno']) : 'Diurno';

        // Normalizar Discapacidad y Etnia
        $disc = strtolower(trim((string)($row['discapacidad'] ?? '')));
        $row['tiene_discapacidad'] = (!empty($disc) && !in_array($disc, ['ninguna', 'no especificado', 'no', 'sin discapacidad', '0']));

        $etn = strtolower(trim((string)($row['etnia'] ?? '')));
        $row['tiene_etnia'] = (!empty($etn) && !in_array($etn, ['ninguna', 'no especificado', 'no', '0']));
        
        // Determinar condición: Estudiante Regular vs Egresado
        $fechaGrad = !empty($row['grad_fecha_graduacion']) ? $row['grad_fecha_graduacion'] : (!empty($row['user_fecha_graduacion']) ? $row['user_fecha_graduacion'] : '');
        $esEgresado = (!empty($row['id_graduado']) && in_array($row['estado_graduado'], ['graduado', 'titulo_entregado'])) || (!empty($fechaGrad) && $fechaGrad !== '0000-00-00');
        $row['es_egresado'] = $esEgresado;
        $row['tipo_alumno'] = $esEgresado ? 'egresado' : 'estudiante';
        $row['fecha_graduacion'] = $fechaGrad;

        $estudiantes[] = $row;
    }
}

// Obtener carreras únicas para el filtro
$carrerasUnicas = array_unique(array_column($estudiantes, 'nombre_carrera'));
sort($carrerasUnicas);

// Obtener ciudades únicas
$ciudades = array_unique(array_column($estudiantes, 'ciudad'));
$ciudades = array_filter($ciudades, function($ciudad) {
    return !empty($ciudad) && $ciudad !== 'No especificada';
});
sort($ciudades);

// Obtener sedes únicas para el filtro
$sedes = array_unique(array_column($estudiantes, 'sede'));
$sedes = array_filter($sedes, function($sede) {
    return !empty($sede) && $sede !== 'No especificada';
});
sort($sedes);

// Obtener estados geográficos únicos
$estadosUnicos = array_values(array_filter(array_unique(array_column($estudiantes, 'nombre_estado')), function($e) {
    return !empty($e) && $e !== 'No especificado';
}));
sort($estadosUnicos);

// Modalidades de ingreso disponibles
$modalidadesUnicas = [
    'OPSU',
    'Deporte',
    'Cultura',
    'Misión Sucre',
    'Ayuda Social',
    'Convenio',
    'Rendimiento Académico',
    'Autorizado por Secretaría',
    'Autorizado por Rector',
    'No especificado'
];

// Turnos disponibles
$turnosUnicos = array_values(array_filter(array_unique(array_column($estudiantes, 'turno'))));
sort($turnosUnicos);
if (empty($turnosUnicos)) $turnosUnicos = ['Diurno', 'Nocturno'];

// Contar estudiantes y calcular estadísticas completas
$totalEstudiantes = count($estudiantes);
$totalEgresados = 0;
$totalEstudiantesSolo = 0;
$activos = 0;
$inactivos = 0;
$nuevosIngresos = 0;
$embarazadas = 0;
$menores = 0;
$mayores = 0;
$masculinos = 0;
$femeninos = 0;
$solteros = 0;
$casados = 0;
$conDiscapacidad = 0;
$conEtnia = 0;
$asignadosOpsu = 0;
$sedePC = 0;
$sedeCOEF = 0;
$sumaEdades = 0;
$totalConEdad = 0;
$anioActual = date('Y');

foreach ($estudiantes as $estudiante) {
    if (!empty($estudiante['es_egresado'])) {
        $totalEgresados++;
    } else {
        $totalEstudiantesSolo++;
    }

    $status = $estudiante['status'] ?? 0;
    if ($status == 1) {
        $activos++;
    } else {
        $inactivos++;
    }

    $genero = strtolower(trim($estudiante['genero'] ?? ''));
    if ($genero === 'masculino' || $genero === 'm') $masculinos++;
    if ($genero === 'femenino' || $genero === 'f') $femeninos++;

    $esFemenino = ($genero === 'femenino' || $genero === 'f');
    $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
    if ($esFemenino && $estaEmbarazada) {
        $embarazadas++;
    }

    if (!empty($estudiante['es_menor'])) {
        $menores++;
    } elseif ($estudiante['edad'] >= 18 && $estudiante['edad'] !== '') {
        $mayores++;
    }

    if ($estudiante['edad'] !== '' && is_numeric($estudiante['edad'])) {
        $sumaEdades += (int)$estudiante['edad'];
        $totalConEdad++;
    }

    $edoCivil = strtolower(trim($estudiante['edo_civil'] ?? ''));
    if (strpos($edoCivil, 'soltero') !== false) $solteros++;
    if (strpos($edoCivil, 'casado') !== false) $casados++;

    if (!empty($estudiante['tiene_discapacidad'])) $conDiscapacidad++;
    if (!empty($estudiante['tiene_etnia'])) $conEtnia++;

    $inscrito = strtolower(trim($estudiante['inscrito_por'] ?? ''));
    if ($inscrito === 'opsu') $asignadosOpsu++;

    $sedeStr = strtolower(trim($estudiante['sede'] ?? ''));
    if (strpos($sedeStr, 'puerto cabello') !== false) $sedePC++;
    if (strpos($sedeStr, 'coef') !== false) $sedeCOEF++;

    $fechaIng = $estudiante['fecha_ingreso'] ?? '';
    if (!empty($fechaIng) && strpos($fechaIng, $anioActual) === 0) {
        $nuevosIngresos++;
    }
}

$edadPromedio = $totalConEdad > 0 ? round($sumaEdades / $totalConEdad, 1) : 0;

include("includes/head.php");
?>

<style>
.pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 20px;
    gap: 10px;
}
.records-selector {
    display: flex;
    align-items: center;
    gap: 8px;
}
.records-selector select {
    width: auto;
    padding: 4px 8px;
}
.pagination {
    margin: 0;
    flex-wrap: wrap;
}
.page-link {
    cursor: pointer;
}
@media (max-width: 768px) {
    .pagination-bar {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <div class="py-3 py-sm-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <h5 class="mb-2 mb-sm-0"><i class="fas fa-users me-2"></i>Listado de Estudiantes</h5>
                        <div>
                            <button type="button" id="toggleEstadisticas" class="btn btn-outline-light btn-sm mb-1 mb-sm-0 me-2" onclick="toggleEstadisticas()">
                                <i class="fas fa-chart-bar"></i> Estadísticas
                            </button>
                            <button type="button" id="toggleFiltros" class="btn btn-outline-light btn-sm mb-1 mb-sm-0 me-2">
                                <i class="fas fa-filter"></i> <span id="toggleFiltrosText">Ocultar Filtros</span>
                            </button>
                            <button type="button" id="btnReporteEstudiantes" class="btn btn-danger btn-sm mb-1 mb-sm-0 me-2" title="Generar Reporte Oficial de Estudiantes">
                                <i class="fas fa-file-pdf me-1"></i> Lista de Estudiantes
                            </button>
                            <button type="button" id="btnReporteEgresados" class="btn btn-dark btn-sm mb-1 mb-sm-0 me-2" style="background-color: #3b093d; border-color: #2b062d;" title="Generar Reporte Oficial de Egresados y Graduados">
                                <i class="fas fa-graduation-cap me-1"></i> Lista de Egresados
                            </button>
                            <?php if (tienePermiso('agregar_estudiante')): ?>
                                <button type="button" class="btn btn-success btn-sm mb-1 mb-sm-0" onclick="abrirModalNuevoEstudiante()">
                                    <i class="fas fa-plus-circle"></i> Nuevo Estudiante
                                </button>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Regresar
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-2 p-sm-3">
                        <div id="estadisticas-row" class="row mb-4">
                            <!-- Total Estudiantes -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-primary text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-users fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statTotalEstudiantes"><?php echo $totalEstudiantes; ?></h4>
                                        <small class="card-text text-white-50">Total Estudiantes</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Activos -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-success text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-user-check fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statActivos"><?php echo $activos; ?></h4>
                                        <small class="card-text text-white-50">Activos</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Inactivos -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-secondary text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-user-times fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statInactivos"><?php echo $inactivos; ?></h4>
                                        <small class="card-text text-white-50">Inactivos</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Nuevos Ingresos -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-info text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-user-plus fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statNuevosIngresos"><?php echo $nuevosIngresos; ?></h4>
                                        <small class="card-text text-white-50">Ingreso <?php echo $anioActual; ?></small>
                                    </div>
                                </div>
                            </div>
                            <!-- Masculinos -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-danger text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-mars fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statMasculinos"><?php echo $masculinos; ?></h4>
                                        <small class="card-text text-white-50">Masculinos</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Femeninos -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #6f42c1;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-venus fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statFemeninos"><?php echo $femeninos; ?></h4>
                                        <small class="card-text text-white-50">Femeninos</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Menores de Edad -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-warning text-dark h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-child fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statMenores"><?php echo $menores; ?></h4>
                                        <small class="card-text text-muted">Menores (&lt;18)</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Mayores de Edad -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card bg-dark text-white h-100 shadow-sm border-0">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-user-graduate fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statMayores"><?php echo $mayores; ?></h4>
                                        <small class="card-text text-white-50">Mayores (&ge;18)</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Edad Promedio -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #fd7e14;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-birthday-cake fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statEdadPromedio"><?php echo $edadPromedio > 0 ? $edadPromedio . ' años' : '-'; ?></h4>
                                        <small class="card-text text-white-50">Edad Promedio</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Mujeres Embarazadas -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #e83e8c;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-baby-carriage fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statEmbarazadas"><?php echo $embarazadas; ?></h4>
                                        <small class="card-text text-white-50">Embarazadas</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Con Discapacidad -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #17a2b8;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-wheelchair fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statDiscapacidad"><?php echo $conDiscapacidad; ?></h4>
                                        <small class="card-text text-white-50">Discapacidad</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Pueblos Indígenas / Etnia -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #20c997;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-feather-alt fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statEtnia"><?php echo $conEtnia; ?></h4>
                                        <small class="card-text text-white-50">Etnia Indígena</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Asignados OPSU -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #0056b3;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-university fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statOpsu"><?php echo $asignadosOpsu; ?></h4>
                                        <small class="card-text text-white-50">Vía OPSU</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Sede Puerto Cabello -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #495057;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-anchor fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statSedePC"><?php echo $sedePC; ?></h4>
                                        <small class="card-text text-white-50">Sede Pto. Cabello</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Sede COEF -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #343a40;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-building fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statSedeCOEF"><?php echo $sedeCOEF; ?></h4>
                                        <small class="card-text text-white-50">Sede COEF</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Egresados / Graduados -->
                            <div class="col-6 col-sm-4 col-md-3 col-xl-2 mb-3">
                                <div class="card text-white h-100 shadow-sm border-0" style="background-color: #3b093d;">
                                    <div class="card-body text-center p-2">
                                        <i class="fas fa-graduation-cap fa-lg mb-1"></i>
                                        <h4 class="card-title mb-0" id="statEgresados"><?php echo $totalEgresados; ?></h4>
                                        <small class="card-text text-white-50">Egresados</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="filtrosContainer" class="row mb-4">
                            <div class="col-12">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtros Avanzados</h6>
                                        <button type="button" id="btnOcultarFiltros" class="btn btn-sm btn-outline-light">
                                            <i class="fas fa-chevron-up"></i> Ocultar
                                        </button>
                                    </div>
                                    <div class="card-body" id="filtrosBody">
                                        <div class="row">
                                            <!-- Carrera -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-graduation-cap text-primary me-1"></i> Carrera</strong></div>
                                                    <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-carrera" type="checkbox" value="todas" id="filtroTodasCarreras" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasCarreras"><strong>Seleccionar Todas</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <?php foreach ($carrerasUnicas as $carrera): ?>
                                                            <?php if (!empty($carrera)): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-carrera" type="checkbox" value="<?php echo htmlspecialchars($carrera); ?>" id="filtroCarrera_<?php echo md5($carrera); ?>">
                                                                <label class="form-check-label" for="filtroCarrera_<?php echo md5($carrera); ?>"><?php echo htmlspecialchars($carrera); ?></label>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modalidad / Inscrito por -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-clipboard-check text-primary me-1"></i> Inscrito por (Modalidad)</strong></div>
                                                    <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-modalidad-master" type="checkbox" value="todas" id="filtroTodasModalidades" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasModalidades"><strong>Seleccionar Todas</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <?php foreach ($modalidadesUnicas as $mod): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-modalidad" type="checkbox" value="<?php echo htmlspecialchars($mod); ?>" id="filtroMod_<?php echo md5($mod); ?>">
                                                                <label class="form-check-label" for="filtroMod_<?php echo md5($mod); ?>"><?php echo htmlspecialchars($mod); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Estado / Status y Condición -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-user-check text-primary me-1"></i> Estatus y Condición</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-status-master" type="checkbox" value="todos" id="filtroTodosStatus" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodosStatus"><strong>Todos los Estatus</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="form-check"><input class="form-check-input filtro-status" type="checkbox" value="activo" id="filtroActivo"><label class="form-check-label" for="filtroActivo">Activo</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-status" type="checkbox" value="inactivo" id="filtroInactivo"><label class="form-check-label" for="filtroInactivo">Inactivo</label></div>
                                                        <hr class="my-2">
                                                        <label class="small text-muted font-weight-bold d-block">Condición:</label>
                                                        <div class="form-check"><input class="form-check-input filtro-tipo-alumno" type="checkbox" value="estudiante" id="filtroTipoEstudiante" checked><label class="form-check-label" for="filtroTipoEstudiante"><i class="fas fa-user-graduate text-primary me-1"></i> Estudiantes</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-tipo-alumno" type="checkbox" value="egresado" id="filtroTipoEgresado" checked><label class="form-check-label" for="filtroTipoEgresado"><i class="fas fa-graduation-cap text-success me-1"></i> Egresados</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Género -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-venus-mars text-primary me-1"></i> Género</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-genero-master" type="checkbox" value="todos" id="filtroTodosGeneros" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodosGeneros"><strong>Seleccionar Todos</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="form-check"><input class="form-check-input filtro-genero" type="checkbox" value="masculino" id="filtroMasculino"><label class="form-check-label" for="filtroMasculino">Masculino</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-genero" type="checkbox" value="femenino" id="filtroFemenino"><label class="form-check-label" for="filtroFemenino">Femenino</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <!-- Sede y Turno -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-building text-primary me-1"></i> Sede y Turno</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-sede-master" type="checkbox" value="todas" id="filtroTodasSedes" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasSedes"><strong>Todas las Sedes</strong></label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-sede" type="checkbox" value="puerto cabello" id="filtroSedePuertoCabello">
                                                            <label class="form-check-label" for="filtroSedePuertoCabello">Puerto Cabello</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-sede" type="checkbox" value="coef" id="filtroSedeCOEF">
                                                            <label class="form-check-label" for="filtroSedeCOEF">COEF</label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-turno-master" type="checkbox" value="todos" id="filtroTodosTurnos" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodosTurnos"><strong>Todos los Turnos</strong></label>
                                                        </div>
                                                        <?php foreach ($turnosUnicos as $tur): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-turno" type="checkbox" value="<?php echo htmlspecialchars(strtolower($tur)); ?>" id="filtroTurno_<?php echo md5($tur); ?>">
                                                                <label class="form-check-label" for="filtroTurno_<?php echo md5($tur); ?>"><?php echo htmlspecialchars($tur); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Estado Geográfico -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-map-marked-alt text-primary me-1"></i> Estado Geográfico</strong></div>
                                                    <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-estado-geo-master" type="checkbox" value="todos" id="filtroTodosEstadosGeo" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodosEstadosGeo"><strong>Seleccionar Todos</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <?php foreach ($estadosUnicos as $estGeo): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-estado-geo" type="checkbox" value="<?php echo htmlspecialchars($estGeo); ?>" id="filtroEstGeo_<?php echo md5($estGeo); ?>">
                                                                <label class="form-check-label" for="filtroEstGeo_<?php echo md5($estGeo); ?>"><?php echo htmlspecialchars($estGeo); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Ciudad -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-city text-primary me-1"></i> Ciudad</strong></div>
                                                    <div class="card-body" style="max-height: 220px; overflow-y: auto;">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-ciudad-master" type="checkbox" value="todas" id="filtroTodasCiudades" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasCiudades"><strong>Seleccionar Todas</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <?php foreach ($ciudades as $ciudad): ?>
                                                            <?php if (!empty($ciudad)): ?>
                                                            <div class="form-check">
                                                                <input class="form-check-input filtro-ciudad" type="checkbox" value="<?php echo htmlspecialchars($ciudad); ?>" id="filtroCiudad_<?php echo md5($ciudad); ?>">
                                                                <label class="form-check-label" for="filtroCiudad_<?php echo md5($ciudad); ?>"><?php echo htmlspecialchars($ciudad); ?></label>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Inclusión y Condición Especial -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-universal-access text-primary me-1"></i> Inclusión & Salud</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-condicion-master" type="checkbox" value="todas" id="filtroTodasCondiciones" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasCondiciones"><strong>Sin Filtro Especial</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <label class="small text-muted font-weight-bold d-block">Discapacidad:</label>
                                                        <div class="form-check"><input class="form-check-input filtro-condicion" type="checkbox" value="con_discapacidad" id="filtroConDiscapacidad"><label class="form-check-label" for="filtroConDiscapacidad">Con Discapacidad</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-condicion" type="checkbox" value="sin_discapacidad" id="filtroSinDiscapacidad"><label class="form-check-label" for="filtroSinDiscapacidad">Sin Discapacidad</label></div>
                                                        <hr class="my-2">
                                                        <label class="small text-muted font-weight-bold d-block">Pueblos Indígenas / Etnia:</label>
                                                        <div class="form-check"><input class="form-check-input filtro-condicion" type="checkbox" value="con_etnia" id="filtroConEtnia"><label class="form-check-label" for="filtroConEtnia">Con Etnia Indígena</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-condicion" type="checkbox" value="sin_etnia" id="filtroSinEtnia"><label class="form-check-label" for="filtroSinEtnia">Sin Etnia</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <!-- Estado Civil -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-heart text-primary me-1"></i> Estado Civil</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-edocivil-master" type="checkbox" value="todos" id="filtroTodosEdoCivil" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodosEdoCivil"><strong>Seleccionar Todos</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="form-check"><input class="form-check-input filtro-edocivil" type="checkbox" value="soltero" id="filtroSoltero"><label class="form-check-label" for="filtroSoltero">Soltero/a</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-edocivil" type="checkbox" value="casado" id="filtroCasado"><label class="form-check-label" for="filtroCasado">Casado/a</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Embarazo -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-baby-carriage text-primary me-1"></i> Embarazo</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check"><input class="form-check-input filtro" type="checkbox" value="embarazada" id="filtroEmbarazada"><label class="form-check-label" for="filtroEmbarazada">Solo Mujeres Embarazadas</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Edad -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-child text-primary me-1"></i> Edad</strong></div>
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input filtro-edad-master" type="checkbox" value="todas" id="filtroTodasEdades" checked>
                                                            <label class="form-check-label text-primary" for="filtroTodasEdades"><strong>Seleccionar Todas</strong></label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <div class="form-check"><input class="form-check-input filtro-edad" type="checkbox" value="menor" id="filtroMenor" checked><label class="form-check-label" for="filtroMenor">Menores de 18 años</label></div>
                                                        <div class="form-check"><input class="form-check-input filtro-edad" type="checkbox" value="mayor" id="filtroMayor" checked><label class="form-check-label" for="filtroMayor">Mayores de 18 años</label></div>
                                                        <div class="row mt-2"><div class="col-6"><input type="number" class="form-control form-control-sm" id="edadMin" placeholder="Edad mín"></div><div class="col-6"><input type="number" class="form-control form-control-sm" id="edadMax" placeholder="Edad máx"></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Fecha Ingreso -->
                                            <div class="col-md-3 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header bg-light"><strong><i class="fas fa-calendar-alt text-primary me-1"></i> Fecha Ingreso</strong></div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-6"><label class="small">Desde</label><input type="date" class="form-control form-control-sm" id="fechaIngresoDesde"></div>
                                                            <div class="col-6"><label class="small">Hasta</label><input type="date" class="form-control form-control-sm" id="fechaIngresoHasta"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARRA DE BÚSQUEDA GENERAL (Ubicada DEBAJO de los Filtros y ENCIMA de la Tabla) -->
                        <div id="buscadorContainer" class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                                    <input type="text" id="buscadorCedula" class="form-control" 
                                           placeholder="Escriba aquí para buscar por Nombre, Apellidos, Cédula, Correo o Usuario..." autocomplete="off">
                                    <button type="button" id="limpiarBusqueda" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Limpiar filtros
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SELECTOR / FILTRO RÁPIDO: ESTUDIANTES VS EGRESADOS -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-between align-items-center p-2 bg-light rounded border shadow-sm">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="text-muted small font-weight-bold"><i class="fas fa-filter text-primary me-1"></i> Filtrar por condición:</span>
                                        <div class="btn-group btn-group-sm" role="group" id="btnGroupTipoAlumno">
                                            <input type="radio" class="btn-check" name="quickTipoAlumno" id="quickTipoTodos" value="todos" autocomplete="off" checked>
                                            <label class="btn btn-outline-primary" for="quickTipoTodos">
                                                <i class="fas fa-users me-1"></i> Todos (<span id="countTodos"><?php echo count($estudiantes); ?></span>)
                                            </label>

                                            <input type="radio" class="btn-check" name="quickTipoAlumno" id="quickTipoEstudiantes" value="estudiante" autocomplete="off">
                                            <label class="btn btn-outline-primary" for="quickTipoEstudiantes">
                                                <i class="fas fa-user-graduate me-1"></i> Lista de Estudiantes (<span id="countEstudiantes"><?php echo $totalEstudiantesSolo; ?></span>)
                                            </label>

                                            <input type="radio" class="btn-check" name="quickTipoAlumno" id="quickTipoEgresados" value="egresado" autocomplete="off">
                                            <label class="btn btn-outline-success" for="quickTipoEgresados">
                                                <i class="fas fa-graduation-cap me-1"></i> Lista de Egresados (<span id="countEgresados"><?php echo $totalEgresados; ?></span>)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-2 mt-md-0">
                                        <span class="badge bg-primary me-1"><i class="fas fa-user-graduate"></i> Estudiante regular</span>
                                        <span class="badge bg-success"><i class="fas fa-graduation-cap"></i> Egresado / Graduado</span>
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
                                        <th>Condición</th>
                                        <th>Modalidad</th>
                                        <th>Turno</th>
                                        <th>Género</th>
                                        <th>Edad</th>
                                        <th>Estado Civil</th>
                                        <th>Estado</th>
                                        <th>Ciudad</th>
                                        <th>Sede</th>
                                        <th>Status</th>
                                        <th>Fecha Ingreso</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaBody">
                                    <?php foreach ($estudiantes as $estudiante): 
                                        $estaEmbarazada = isset($estudiante['embarazada']) && trim((string)$estudiante['embarazada']) === '1';
                                        $esFemenino = isset($estudiante['genero']) && trim($estudiante['genero']) === 'Femenino';
                                        $cedula = $estudiante['idusuario'] ?? '';
                                        $status = $estudiante['status'] ?? 0;
                                        $edad = $estudiante['edad'] ?? '';
                                        $fechaIngreso = $estudiante['fecha_ingreso'] ?? '';
                                        $ciudad = $estudiante['ciudad'] ?? '';
                                        $carrera = $estudiante['nombre_carrera'] ?? '';
                                        $sede = $estudiante['sede'] ?? '';
                                        $modalidad = $estudiante['inscrito_por'] ?? 'No especificado';
                                        $turno = $estudiante['turno'] ?? 'No especificado';
                                        $estadoGeo = $estudiante['nombre_estado'] ?? 'No especificado';
                                        $tieneDiscapacidad = !empty($estudiante['tiene_discapacidad']) ? '1' : '0';
                                        $tieneEtnia = !empty($estudiante['tiene_etnia']) ? '1' : '0';
                                        $esOpsu = ($estudiante['asignado_opsu'] === 'Sí' || strtolower($modalidad) === 'opsu') ? '1' : '0';
                                    ?>
                                        <tr data-cedula="<?php echo htmlspecialchars(strtolower($cedula)); ?>"
                                            data-nombre="<?php echo htmlspecialchars(strtolower($estudiante['nombre'] ?? '')); ?>"
                                            data-username="<?php echo htmlspecialchars(strtolower($estudiante['username'] ?? '')); ?>"
                                            data-email="<?php echo htmlspecialchars(strtolower($estudiante['email'] ?? '')); ?>"
                                            data-genero="<?php echo strtolower($estudiante['genero'] ?? ''); ?>"
                                            data-status="<?php echo $status; ?>"
                                            data-tipo-alumno="<?php echo $estudiante['tipo_alumno']; ?>"
                                            data-es-egresado="<?php echo $estudiante['es_egresado'] ? '1' : '0'; ?>"
                                            data-embarazada="<?php echo $estaEmbarazada ? '1' : '0'; ?>"
                                            data-edad="<?php echo $edad; ?>"
                                            data-menor="<?php echo $estudiante['es_menor'] ? '1' : '0'; ?>"
                                            data-mayor="<?php echo ($edad >= 18 && $edad != '') ? '1' : '0'; ?>"
                                            data-edo-civil="<?php echo strtolower($estudiante['edo_civil'] ?? ''); ?>"
                                            data-fecha-ingreso="<?php echo $fechaIngreso; ?>"
                                            data-ciudad="<?php echo strtolower($ciudad); ?>"
                                            data-carrera="<?php echo strtolower($carrera); ?>"
                                            data-sede="<?php echo strtolower($sede); ?>"
                                            data-inscrito-por="<?php echo htmlspecialchars(strtolower($modalidad)); ?>"
                                            data-turno="<?php echo htmlspecialchars(strtolower($turno)); ?>"
                                            data-estado-geo="<?php echo htmlspecialchars(strtolower($estadoGeo)); ?>"
                                            data-discapacidad="<?php echo htmlspecialchars(strtolower($estudiante['discapacidad'] ?? '')); ?>"
                                            data-tiene-discapacidad="<?php echo $tieneDiscapacidad; ?>"
                                            data-etnia="<?php echo htmlspecialchars(strtolower($estudiante['etnia'] ?? '')); ?>"
                                            data-tiene-etnia="<?php echo $tieneEtnia; ?>"
                                            data-opsu="<?php echo $esOpsu; ?>">
                                            <td><?php echo htmlspecialchars($cedula); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['nombre'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['username'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['email'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['tlf'] ?? $estudiante['cel'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($carrera); ?></td>
                                            <td>
                                                <?php if ($estudiante['es_egresado']): ?>
                                                    <span class="badge bg-success" title="Graduado / Egresado<?php echo !empty($estudiante['fecha_graduacion']) ? ' (' . date('d/m/Y', strtotime($estudiante['fecha_graduacion'])) . ')' : ''; ?>">
                                                        <i class="fas fa-graduation-cap me-1"></i> Egresado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary" title="Estudiante Regular">
                                                        <i class="fas fa-user-graduate me-1"></i> Estudiante
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($modalidad); ?></span></td>
                                            <td><?php echo htmlspecialchars($turno); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($estudiante['genero'] ?? ''); ?>
                                                <?php if ($esFemenino && $estaEmbarazada): ?><span class="badge bg-info ms-1" title="Embarazada">🤰</span><?php endif; ?>
                                            </td>
                                            <td><?php echo $edad; ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['edo_civil'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($estadoGeo); ?></td>
                                            <td><?php echo htmlspecialchars($ciudad); ?></td>
                                            <td><?php echo htmlspecialchars($sede); ?></td>
                                            <td><?php echo ($status == 1) ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>'; ?></td>
                                            <td><?php echo !empty($fechaIngreso) ? date('d/m/Y', strtotime($fechaIngreso)) : ''; ?></td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <button type="button" class="btn btn-info btn-details btn-sm" data-id="<?php echo $estudiante['id']; ?>"><i class="fas fa-eye"></i></button>
                                                    <?php if ($puedeEditar): ?>
                                                        <button type="button" class="btn btn-warning btn-sm btn-edit" data-id="<?php echo $estudiante['id']; ?>"><i class="fas fa-edit"></i></button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination-bar">
                            <div class="records-selector">
                                <label class="small mb-0">Mostrar:</label>
                                <select id="registrosPorPagina" class="form-control form-control-sm">
                                    <option value="10">10</option>
                                    <option value="20" selected>20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="999999">Todos</option>
                                </select>
                                <span class="small text-muted">registros</span>
                            </div>
                            <div class="small text-muted">
                                Mostrando <span id="mostrandoDesde">0</span> - <span id="mostrandoHasta">0</span> de <span id="totalRegistros"><?php echo $totalEstudiantes; ?></span> estudiantes
                            </div>
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="paginationControls"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
<div class="modal fade" id="reporteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <input type="hidden" id="reporteTipoSeleccionado" value="estudiantes">
            <div class="modal-header bg-danger text-white" id="reporteModalHeader">
                <h5 class="modal-title" id="reporteModalTitle"><i class="fas fa-file-pdf me-2"></i> Generar Reporte</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" id="reporteModalAlert">
                    <i class="fas fa-info-circle"></i> Se generará un reporte oficial en PDF con los registros actualmente filtrados.
                </div>
                <div class="form-group">
                    <label><i class="fas fa-chart-bar"></i> Incluir estadísticas y resumen</label>
                    <select id="incluirEstadisticas" class="form-control">
                        <option value="si">Sí (Recomendado)</option>
                        <option value="no">No (Solo listado tabular)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnGenerarPDF" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Descargar Reporte PDF
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id="detalleEstudianteContent">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-id-card mr-2"></i> Detalles del Estudiante</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="text-center my-5"><div class="spinner-border text-primary"></div><p class="mt-2">Cargando datos...</p></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editarEstudianteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" id="editarEstudianteContent">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-user-edit mr-2"></i> Editar Estudiante</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="text-center my-5"><div class="spinner-border text-warning"></div><p class="mt-2">Cargando formulario...</p></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="agregarEstudianteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white"><h5 class="modal-title"><i class="fas fa-user-plus me-2"></i> Agregar Nuevo Estudiante</h5><button type="button" class="close text-white" data-dismiss="modal">&times;</button></div>
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
                <div class="tab-content"><div class="tab-pane fade show active" id="individual"><form id="formEstudianteModal" method="post" enctype="multipart/form-data"><?php $esModal = true; include('_formulario_estudiante.php'); ?></form></div></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="resultadoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="resultadoModalHeader"><h5 class="modal-title">Resultado</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body" id="resultadoModalBody"></div>
            <div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button></div>
        </div>
    </div>
</div>

<style>
.close { font-size: 1.5rem; font-weight: bold; opacity: 0.8; padding: 0.5rem; line-height: 1; background: transparent; border: none; cursor: pointer; }
.close:hover { opacity: 1; }
.modal-header { position: relative; display: flex; justify-content: space-between; align-items: center; }
.modal-content { border-radius: 0.5rem; border: none; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15); }
.bg-purple { background-color: #6f42c1 !important; }
.filtrosHidden .card-body { display: none !important; }
@media (max-width: 767.98px) {
    .card-header h5 { font-size: 1rem; }
    .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.7rem; }
    .badge { font-size: 0.65rem; padding: 0.2rem 0.4rem; }
    .table td, .table th { font-size: 0.75rem; padding: 0.4rem; vertical-align: middle; }
    .modal-body { padding: 0.75rem; }
    .d-flex.flex-wrap { gap: 0.25rem !important; }
}
</style>

<script>
let filtrosOcultos = false;
let todasLasFilas = [];
let filasFiltradas = [];
let paginaActual = 1;
let registrosPorPagina = 20;

document.addEventListener('DOMContentLoaded', function() {
    cargarEstadoGuardado();
    todasLasFilas = Array.from(document.querySelectorAll('#tablaBody tr'));
    configurarFiltros();
    
    const selectorRegistros = document.getElementById('registrosPorPagina');
    if (selectorRegistros) {
        selectorRegistros.addEventListener('change', function() {
            registrosPorPagina = parseInt(this.value);
            paginaActual = 1;
            aplicarFiltrosYActualizar();
        });
    }
    aplicarFiltrosYActualizar();
});

function setupMasterToggle(masterId, childSelector) {
    const master = document.getElementById(masterId);
    if (!master) return;
    master.addEventListener('change', function() {
        const checkAll = this.checked;
        document.querySelectorAll(childSelector).forEach(cb => {
            if (cb !== master) {
                cb.checked = checkAll;
                cb.disabled = checkAll;
            }
        });
        aplicarFiltrosYActualizar();
    });
    if (master.checked) {
        document.querySelectorAll(childSelector).forEach(cb => {
            if (cb !== master) {
                cb.checked = true;
                cb.disabled = true;
            }
        });
    }
}

function configurarFiltros() {
    setupMasterToggle('filtroTodasCarreras', '.filtro-carrera');
    setupMasterToggle('filtroTodasModalidades', '.filtro-modalidad');
    setupMasterToggle('filtroTodosGeneros', '.filtro-genero');
    setupMasterToggle('filtroTodosEdoCivil', '.filtro-edocivil');
    setupMasterToggle('filtroTodosStatus', '.filtro-status');
    setupMasterToggle('filtroTodasSedes', '.filtro-sede');
    setupMasterToggle('filtroTodosTurnos', '.filtro-turno');
    setupMasterToggle('filtroTodosEstadosGeo', '.filtro-estado-geo');
    setupMasterToggle('filtroTodasCiudades', '.filtro-ciudad');
    setupMasterToggle('filtroTodasCondiciones', '.filtro-condicion');
    setupMasterToggle('filtroTodasEdades', '.filtro-edad');

    document.querySelectorAll('.filtro-carrera, .filtro-modalidad, .filtro-ciudad, .filtro-sede, .filtro-turno, .filtro-estado-geo, .filtro-condicion, .filtro-genero, .filtro-edocivil, .filtro-status, .filtro-edad, .filtro').forEach(cb => {
        cb.addEventListener('change', aplicarFiltrosYActualizar);
    });

    // Filtros de Condición: Estudiante vs Egresado
    document.querySelectorAll('.filtro-tipo-alumno').forEach(cb => {
        cb.addEventListener('change', function() {
            const estCheck = document.getElementById('filtroTipoEstudiante')?.checked;
            const egrCheck = document.getElementById('filtroTipoEgresado')?.checked;
            const rTodos = document.getElementById('quickTipoTodos');
            const rEst = document.getElementById('quickTipoEstudiantes');
            const rEgr = document.getElementById('quickTipoEgresados');
            if (estCheck && egrCheck) {
                if (rTodos) rTodos.checked = true;
            } else if (estCheck && !egrCheck) {
                if (rEst) rEst.checked = true;
            } else if (!estCheck && egrCheck) {
                if (rEgr) rEgr.checked = true;
            } else {
                if (rTodos) rTodos.checked = false;
                if (rEst) rEst.checked = false;
                if (rEgr) rEgr.checked = false;
            }
            aplicarFiltrosYActualizar();
        });
    });

    // Selector rápido superior: Todos / Lista de Estudiantes / Lista de Egresados
    document.querySelectorAll('input[name="quickTipoAlumno"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const cbEst = document.getElementById('filtroTipoEstudiante');
            const cbEgr = document.getElementById('filtroTipoEgresado');
            if (this.value === 'todos') {
                if (cbEst) cbEst.checked = true;
                if (cbEgr) cbEgr.checked = true;
            } else if (this.value === 'estudiante') {
                if (cbEst) cbEst.checked = true;
                if (cbEgr) cbEgr.checked = false;
            } else if (this.value === 'egresado') {
                if (cbEst) cbEst.checked = false;
                if (cbEgr) cbEgr.checked = true;
            }
            aplicarFiltrosYActualizar();
        });
    });

    document.getElementById('edadMin').addEventListener('input', aplicarFiltrosYActualizar);
    document.getElementById('edadMax').addEventListener('input', aplicarFiltrosYActualizar);
    document.getElementById('fechaIngresoDesde').addEventListener('change', aplicarFiltrosYActualizar);
    document.getElementById('fechaIngresoHasta').addEventListener('change', aplicarFiltrosYActualizar);
    document.getElementById('buscadorCedula').addEventListener('keyup', aplicarFiltrosYActualizar);
}

function aplicarFiltrosYActualizar() {
    aplicarFiltros();
    actualizarEstadisticasDinamicas(filasFiltradas);
    actualizarPaginacion();
}

function actualizarEstadisticasDinamicas(filas) {
    const total = filas.length;
    let activos = 0, inactivos = 0, nuevosIngresos = 0;
    let masculinos = 0, femeninos = 0;
    let menores = 0, mayores = 0;
    let sumaEdad = 0, cantEdad = 0;
    let embarazadas = 0, conDiscapacidad = 0, conEtnia = 0, opsu = 0;
    let sedePC = 0, sedeCOEF = 0;
    let egresados = 0, estudiantesSolo = 0;
    const anioActualStr = '<?php echo $anioActual; ?>';

    filas.forEach(f => {
        const tipoAlumno = f.getAttribute('data-tipo-alumno') || 'estudiante';
        if (tipoAlumno === 'egresado') egresados++;
        else estudiantesSolo++;

        const status = f.getAttribute('data-status');
        if (status === '1') activos++;
        else if (status === '0') inactivos++;

        const fechaIngreso = f.getAttribute('data-fecha-ingreso') || '';
        if (fechaIngreso.startsWith(anioActualStr)) nuevosIngresos++;

        const genero = (f.getAttribute('data-genero') || '').toLowerCase();
        if (genero === 'masculino') masculinos++;
        else if (genero === 'femenino') femeninos++;

        const menor = f.getAttribute('data-menor');
        const mayor = f.getAttribute('data-mayor');
        if (menor === '1') menores++;
        if (mayor === '1') mayores++;

        const edad = parseInt(f.getAttribute('data-edad'));
        if (!isNaN(edad) && edad > 0) {
            sumaEdad += edad;
            cantEdad++;
        }

        if (f.getAttribute('data-embarazada') === '1') embarazadas++;
        if (f.getAttribute('data-tiene-discapacidad') === '1') conDiscapacidad++;
        if (f.getAttribute('data-tiene-etnia') === '1') conEtnia++;
        if (f.getAttribute('data-opsu') === '1') opsu++;

        const sede = (f.getAttribute('data-sede') || '').toLowerCase();
        if (sede.includes('puerto cabello')) sedePC++;
        if (sede.includes('coef')) sedeCOEF++;
    });

    const elTotal = document.getElementById('statTotalEstudiantes');
    if (elTotal) elTotal.textContent = total;
    const elActivos = document.getElementById('statActivos');
    if (elActivos) elActivos.textContent = activos;
    const elInactivos = document.getElementById('statInactivos');
    if (elInactivos) elInactivos.textContent = inactivos;
    const elNuevos = document.getElementById('statNuevosIngresos');
    if (elNuevos) elNuevos.textContent = nuevosIngresos;
    const elMasc = document.getElementById('statMasculinos');
    if (elMasc) elMasc.textContent = masculinos;
    const elFem = document.getElementById('statFemeninos');
    if (elFem) elFem.textContent = femeninos;
    const elMenores = document.getElementById('statMenores');
    if (elMenores) elMenores.textContent = menores;
    const elMayores = document.getElementById('statMayores');
    if (elMayores) elMayores.textContent = mayores;
    const elEdadProm = document.getElementById('statEdadPromedio');
    if (elEdadProm) elEdadProm.textContent = cantEdad > 0 ? (sumaEdad / cantEdad).toFixed(1) + ' años' : '-';
    const elEmb = document.getElementById('statEmbarazadas');
    if (elEmb) elEmb.textContent = embarazadas;
    const elDisc = document.getElementById('statDiscapacidad');
    if (elDisc) elDisc.textContent = conDiscapacidad;
    const elEtnia = document.getElementById('statEtnia');
    if (elEtnia) elEtnia.textContent = conEtnia;
    const elOpsu = document.getElementById('statOpsu');
    if (elOpsu) elOpsu.textContent = opsu;
    const elSedePC = document.getElementById('statSedePC');
    if (elSedePC) elSedePC.textContent = sedePC;
    const elSedeCOEF = document.getElementById('statSedeCOEF');
    if (elSedeCOEF) elSedeCOEF.textContent = sedeCOEF;
    const elEgresados = document.getElementById('statEgresados');
    if (elEgresados) elEgresados.textContent = egresados;

    const elCountTodos = document.getElementById('countTodos');
    if (elCountTodos) elCountTodos.textContent = total;
    const elCountEst = document.getElementById('countEstudiantes');
    if (elCountEst) elCountEst.textContent = estudiantesSolo;
    const elCountEgr = document.getElementById('countEgresados');
    if (elCountEgr) elCountEgr.textContent = egresados;
}

function aplicarFiltros() {
    let termino = document.getElementById('buscadorCedula').value.trim().toLowerCase();
    
    let todosGeneros = document.getElementById('filtroTodosGeneros')?.checked;
    let filtroMasculino = document.getElementById('filtroMasculino')?.checked;
    let filtroFemenino = document.getElementById('filtroFemenino')?.checked;
    
    let todosStatus = document.getElementById('filtroTodosStatus')?.checked;
    let filtroActivo = document.getElementById('filtroActivo')?.checked;
    let filtroInactivo = document.getElementById('filtroInactivo')?.checked;
    
    let filtroTipoEstudiante = document.getElementById('filtroTipoEstudiante')?.checked;
    let filtroTipoEgresado = document.getElementById('filtroTipoEgresado')?.checked;

    let filtroEmbarazada = document.getElementById('filtroEmbarazada')?.checked;
    let todasEdades = document.getElementById('filtroTodasEdades')?.checked;
    let filtroMenor = document.getElementById('filtroMenor')?.checked;
    let filtroMayor = document.getElementById('filtroMayor')?.checked;
    
    let todosEdoCivil = document.getElementById('filtroTodosEdoCivil')?.checked;
    let filtroSoltero = document.getElementById('filtroSoltero')?.checked;
    let filtroCasado = document.getElementById('filtroCasado')?.checked;
    
    let edadMin = document.getElementById('edadMin')?.value;
    let edadMax = document.getElementById('edadMax')?.value;
    let fechaDesde = document.getElementById('fechaIngresoDesde')?.value;
    let fechaHasta = document.getElementById('fechaIngresoHasta')?.value;
    
    let carrerasSeleccionadas = [];
    let todasCarreras = document.getElementById('filtroTodasCarreras')?.checked;
    if (!todasCarreras) {
        document.querySelectorAll('.filtro-carrera:checked').forEach(cb => {
            if (cb.value !== 'todas') carrerasSeleccionadas.push(cb.value.toLowerCase());
        });
    }

    let modalidadesSeleccionadas = [];
    let todasModalidades = document.getElementById('filtroTodasModalidades')?.checked;
    if (!todasModalidades) {
        document.querySelectorAll('.filtro-modalidad:checked').forEach(cb => {
            if (cb.value !== 'todas') modalidadesSeleccionadas.push(cb.value.toLowerCase());
        });
    }
    
    let ciudadesSeleccionadas = [];
    let todasCiudades = document.getElementById('filtroTodasCiudades')?.checked;
    if (!todasCiudades) {
        document.querySelectorAll('.filtro-ciudad:checked').forEach(cb => {
            if (cb.value !== 'todas') ciudadesSeleccionadas.push(cb.value.toLowerCase());
        });
    }
    
    let sedesSeleccionadas = [];
    let todasSedes = document.getElementById('filtroTodasSedes')?.checked;
    if (!todasSedes) {
        document.querySelectorAll('.filtro-sede:checked').forEach(cb => {
            if (cb.value !== 'todas') sedesSeleccionadas.push(cb.value.toLowerCase());
        });
    }

    let turnosSeleccionados = [];
    let todosTurnos = document.getElementById('filtroTodosTurnos')?.checked;
    if (!todosTurnos) {
        document.querySelectorAll('.filtro-turno:checked').forEach(cb => {
            if (cb.value !== 'todos') turnosSeleccionados.push(cb.value.toLowerCase());
        });
    }

    let estadosGeoSeleccionados = [];
    let todosEstadosGeo = document.getElementById('filtroTodosEstadosGeo')?.checked;
    if (!todosEstadosGeo) {
        document.querySelectorAll('.filtro-estado-geo:checked').forEach(cb => {
            if (cb.value !== 'todos') estadosGeoSeleccionados.push(cb.value.toLowerCase());
        });
    }

    let todasCondiciones = document.getElementById('filtroTodasCondiciones')?.checked;
    let filtroConDiscapacidad = document.getElementById('filtroConDiscapacidad')?.checked;
    let filtroSinDiscapacidad = document.getElementById('filtroSinDiscapacidad')?.checked;
    let filtroConEtnia = document.getElementById('filtroConEtnia')?.checked;
    let filtroSinEtnia = document.getElementById('filtroSinEtnia')?.checked;
    
    filasFiltradas = todasLasFilas.filter(fila => {
        let textoFila = (fila.textContent || fila.innerText || '').toLowerCase();

        if (termino !== '' && !textoFila.includes(termino)) return false;

        // Filtro por condición (Estudiante regular vs Egresado)
        if (!filtroTipoEstudiante && !filtroTipoEgresado) return false;
        let tipoAlumno = fila.getAttribute('data-tipo-alumno') || 'estudiante';
        if (filtroTipoEstudiante && !filtroTipoEgresado && tipoAlumno !== 'estudiante') return false;
        if (!filtroTipoEstudiante && filtroTipoEgresado && tipoAlumno !== 'egresado') return false;
        
        if (!todasCarreras && carrerasSeleccionadas.length > 0) {
            let carrera = (fila.getAttribute('data-carrera') || '').toLowerCase();
            if (!carrerasSeleccionadas.includes(carrera)) return false;
        }

        if (!todasModalidades && modalidadesSeleccionadas.length > 0) {
            let modalidad = (fila.getAttribute('data-inscrito-por') || '').toLowerCase();
            if (!modalidadesSeleccionadas.includes(modalidad)) return false;
        }

        if (!todasCiudades && ciudadesSeleccionadas.length > 0) {
            let ciudad = (fila.getAttribute('data-ciudad') || '').toLowerCase();
            if (!ciudadesSeleccionadas.includes(ciudad)) return false;
        }

        if (!todasSedes && sedesSeleccionadas.length > 0) {
            let sede = (fila.getAttribute('data-sede') || '').toLowerCase();
            if (!sedesSeleccionadas.includes(sede)) return false;
        }

        if (!todosTurnos && turnosSeleccionados.length > 0) {
            let turno = (fila.getAttribute('data-turno') || '').toLowerCase();
            if (!turnosSeleccionados.includes(turno)) return false;
        }

        if (!todosEstadosGeo && estadosGeoSeleccionados.length > 0) {
            let estadoGeo = (fila.getAttribute('data-estado-geo') || '').toLowerCase();
            if (!estadosGeoSeleccionados.includes(estadoGeo)) return false;
        }

        if (!todasCondiciones) {
            if (filtroConDiscapacidad || filtroSinDiscapacidad) {
                let tieneDisc = fila.getAttribute('data-tiene-discapacidad') || '0';
                if (filtroConDiscapacidad && !filtroSinDiscapacidad && tieneDisc !== '1') return false;
                if (filtroSinDiscapacidad && !filtroConDiscapacidad && tieneDisc !== '0') return false;
            }
            if (filtroConEtnia || filtroSinEtnia) {
                let tieneEtn = fila.getAttribute('data-tiene-etnia') || '0';
                if (filtroConEtnia && !filtroSinEtnia && tieneEtn !== '1') return false;
                if (filtroSinEtnia && !filtroConEtnia && tieneEtn !== '0') return false;
            }
        }

        if (!todosGeneros && (filtroMasculino || filtroFemenino)) {
            let genero = (fila.getAttribute('data-genero') || '').toLowerCase();
            if (filtroMasculino && filtroFemenino) {}
            else if (filtroMasculino && genero !== 'masculino') return false;
            else if (filtroFemenino && genero !== 'femenino') return false;
        }
        if (!todosStatus && (filtroActivo || filtroInactivo)) {
            let status = fila.getAttribute('data-status') || '';
            if (filtroActivo && filtroInactivo) {}
            else if (filtroActivo && status !== '1') return false;
            else if (filtroInactivo && status !== '0') return false;
        }
        if (filtroEmbarazada) {
            let embarazada = fila.getAttribute('data-embarazada') || '0';
            let genero = (fila.getAttribute('data-genero') || '').toLowerCase();
            if (embarazada !== '1' || genero !== 'femenino') return false;
        }
        if (!todasEdades && (filtroMenor || filtroMayor)) {
            let menor = fila.getAttribute('data-menor') || '0';
            let mayor = fila.getAttribute('data-mayor') || '0';
            if (filtroMenor && filtroMayor) {}
            else if (filtroMenor && menor !== '1') return false;
            else if (filtroMayor && mayor !== '1') return false;
        }
        if (edadMin !== '' || edadMax !== '') {
            let edad = parseInt(fila.getAttribute('data-edad')) || 0;
            if (edadMin !== '' && edad < parseInt(edadMin)) return false;
            if (edadMax !== '' && edad > parseInt(edadMax)) return false;
        }
        if (!todosEdoCivil && (filtroSoltero || filtroCasado)) {
            let edoCivil = (fila.getAttribute('data-edo-civil') || '').toLowerCase();
            if (filtroSoltero && !edoCivil.includes('soltero')) return false;
            if (filtroCasado && !edoCivil.includes('casado')) return false;
        }
        if (fechaDesde !== '' || fechaHasta !== '') {
            let fechaIngreso = fila.getAttribute('data-fecha-ingreso') || '';
            if (fechaDesde !== '' && fechaIngreso < fechaDesde) return false;
            if (fechaHasta !== '' && fechaIngreso > fechaHasta) return false;
        }
        return true;
    });
    
    let mensajeDiv = document.getElementById('mensajeBusqueda');
    if(!mensajeDiv) {
        mensajeDiv = document.createElement('div');
        mensajeDiv.id = 'mensajeBusqueda';
        mensajeDiv.className = 'alert alert-info mt-3';
        document.querySelector('.table-responsive').appendChild(mensajeDiv);
    }
    if(filasFiltradas.length === 0) {
        mensajeDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> No se encontraron estudiantes con los filtros seleccionados`;
        mensajeDiv.className = 'alert alert-warning mt-3';
    } else {
        mensajeDiv.innerHTML = `<i class="fas fa-check-circle"></i> Se encontraron ${filasFiltradas.length} de ${todasLasFilas.length} estudiantes`;
        mensajeDiv.className = 'alert alert-success mt-3';
    }
}

function actualizarPaginacion() {
    const totalFilas = filasFiltradas.length;
    const totalPaginas = registrosPorPagina === 999999 ? 1 : Math.ceil(totalFilas / registrosPorPagina);
    if (paginaActual > totalPaginas) paginaActual = totalPaginas;
    if (paginaActual < 1) paginaActual = 1;
    const inicio = (paginaActual - 1) * registrosPorPagina;
    const fin = registrosPorPagina === 999999 ? totalFilas : Math.min(inicio + registrosPorPagina, totalFilas);
    todasLasFilas.forEach(fila => fila.style.display = 'none');
    for (let i = inicio; i < fin; i++) {
        if (filasFiltradas[i]) filasFiltradas[i].style.display = '';
    }
    document.getElementById('mostrandoDesde').textContent = totalFilas === 0 ? 0 : inicio + 1;
    document.getElementById('mostrandoHasta').textContent = fin;
    document.getElementById('totalRegistros').textContent = totalFilas;
    
    const paginationContainer = document.getElementById('paginationControls');
    paginationContainer.innerHTML = '';
    if (registrosPorPagina !== 999999 && totalPaginas > 1) {
        const liPrev = document.createElement('li');
        liPrev.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
        liPrev.innerHTML = `<a class="page-link" data-pagina="${paginaActual - 1}"><i class="fas fa-chevron-left"></i> Anterior</a>`;
        paginationContainer.appendChild(liPrev);
        
        let inicioPag = Math.max(1, paginaActual - 2);
        let finPag = Math.min(totalPaginas, paginaActual + 2);
        if (inicioPag > 1) {
            paginationContainer.appendChild(crearItemPagina(1));
            if (inicioPag > 2) paginationContainer.appendChild(crearItemPuntos());
        }
        for (let i = inicioPag; i <= finPag; i++) {
            paginationContainer.appendChild(crearItemPagina(i));
        }
        if (finPag < totalPaginas) {
            if (finPag < totalPaginas - 1) paginationContainer.appendChild(crearItemPuntos());
            paginationContainer.appendChild(crearItemPagina(totalPaginas));
        }
        const liNext = document.createElement('li');
        liNext.className = `page-item ${paginaActual === totalPaginas ? 'disabled' : ''}`;
        liNext.innerHTML = `<a class="page-link" data-pagina="${paginaActual + 1}">Siguiente <i class="fas fa-chevron-right"></i></a>`;
        paginationContainer.appendChild(liNext);
        
        document.querySelectorAll('#paginationControls .page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const nuevaPagina = parseInt(this.getAttribute('data-pagina'));
                if (!isNaN(nuevaPagina) && nuevaPagina !== paginaActual && nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
                    paginaActual = nuevaPagina;
                    actualizarPaginacion();
                }
            });
        });
    }
}

function crearItemPagina(numero) {
    const li = document.createElement('li');
    li.className = `page-item ${numero === paginaActual ? 'active' : ''}`;
    li.innerHTML = `<a class="page-link" data-pagina="${numero}">${numero}</a>`;
    return li;
}

function crearItemPuntos() {
    const li = document.createElement('li');
    li.className = 'page-item disabled';
    li.innerHTML = '<span class="page-link">...</span>';
    return li;
}

function toggleFiltros() {
    const filtrosBody = document.getElementById('filtrosBody');
    const toggleText = document.getElementById('toggleFiltrosText');
    const btnOcultarFiltros = document.getElementById('btnOcultarFiltros');
    if (filtrosOcultos) {
        filtrosBody.style.display = 'block';
        filtrosOcultos = false;
        if (toggleText) toggleText.innerHTML = 'Ocultar Filtros';
        if (btnOcultarFiltros) btnOcultarFiltros.innerHTML = '<i class="fas fa-chevron-up"></i> Ocultar';
        localStorage.setItem('filtrosOcultos', 'false');
    } else {
        filtrosBody.style.display = 'none';
        filtrosOcultos = true;
        if (toggleText) toggleText.innerHTML = 'Mostrar Filtros';
        if (btnOcultarFiltros) btnOcultarFiltros.innerHTML = '<i class="fas fa-chevron-down"></i> Mostrar';
        localStorage.setItem('filtrosOcultos', 'true');
    }
}

function cargarEstadoGuardado() {
    const estadisticasOcultas = localStorage.getItem('estadisticasOcultas');
    if (estadisticasOcultas === 'true') {
        document.getElementById('estadisticas-row').style.display = 'none';
        document.getElementById('toggleEstadisticas').innerHTML = '<i class="fas fa-chart-bar"></i> Mostrar Estadísticas';
    }
    const filtrosGuardados = localStorage.getItem('filtrosOcultos');
    if (filtrosGuardados === 'true') {
        document.getElementById('filtrosBody').style.display = 'none';
        filtrosOcultos = true;
        document.getElementById('toggleFiltrosText').innerHTML = 'Mostrar Filtros';
        const btnOcultar = document.getElementById('btnOcultarFiltros');
        if (btnOcultar) btnOcultar.innerHTML = '<i class="fas fa-chevron-down"></i> Mostrar';
    }
}

window.toggleEstadisticas = function() {
    const row = document.getElementById('estadisticas-row');
    const button = document.getElementById('toggleEstadisticas');
    if (row.style.display === 'none') {
        row.style.display = 'flex';
        button.innerHTML = '<i class="fas fa-chart-bar"></i> Ocultar Estadísticas';
        localStorage.setItem('estadisticasOcultas', 'false');
    } else {
        row.style.display = 'none';
        button.innerHTML = '<i class="fas fa-chart-bar"></i> Mostrar Estadísticas';
        localStorage.setItem('estadisticasOcultas', 'true');
    }
};

function getEstudiantesFiltrados() {
    return filasFiltradas.map(fila => fila.querySelector('.btn-details')?.getAttribute('data-id')).filter(id => id);
}

function getEstudiantesParaReporte(tipo) {
    return filasFiltradas.filter(fila => {
        const tipoAlumno = fila.getAttribute('data-tipo-alumno') || 'estudiante';
        return (tipo === 'egresados') ? tipoAlumno === 'egresado' : tipoAlumno !== 'egresado';
    }).map(fila => fila.querySelector('.btn-details')?.getAttribute('data-id')).filter(Boolean);
}

function abrirModalReporte(tipo) {
    const inputTipo = document.getElementById('reporteTipoSeleccionado');
    if (inputTipo) inputTipo.value = tipo;

    const ids = getEstudiantesParaReporte(tipo);
    const modalHeader = document.getElementById('reporteModalHeader');
    const modalTitle = document.getElementById('reporteModalTitle');
    const modalAlert = document.getElementById('reporteModalAlert');
    const btnPDF = document.getElementById('btnGenerarPDF');

    if (tipo === 'egresados') {
        if (ids.length === 0) {
            mostrarMensaje('Sin Egresados', 'No se encontraron egresados con los filtros aplicados. Sugerencia: active la opción de egresados o verifique los criterios.', false);
            return;
        }
        if (modalHeader) {
            modalHeader.className = 'modal-header text-white';
            modalHeader.style.backgroundColor = '#3b093d';
        }
        if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-graduation-cap me-2"></i> Reporte Oficial de Egresados y Graduados';
        if (btnPDF) {
            btnPDF.className = 'btn text-white';
            btnPDF.style.backgroundColor = '#3b093d';
            btnPDF.style.borderColor = '#2b062d';
            btnPDF.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Generar Reporte de Egresados PDF';
        }
        if (modalAlert) {
            modalAlert.className = 'alert alert-info';
            modalAlert.innerHTML = `<i class="fas fa-info-circle me-1"></i> Se generará el reporte oficial en PDF con los <strong>${ids.length}</strong> egresados y graduados seleccionados según los filtros activos.`;
        }
    } else {
        if (ids.length === 0) {
            mostrarMensaje('Sin Estudiantes', 'No se encontraron estudiantes con los filtros aplicados.', false);
            return;
        }
        if (modalHeader) {
            modalHeader.className = 'modal-header bg-danger text-white';
            modalHeader.style.backgroundColor = '';
        }
        if (modalTitle) modalTitle.innerHTML = '<i class="fas fa-file-pdf me-2"></i> Reporte Oficial de Estudiantes';
        if (btnPDF) {
            btnPDF.className = 'btn btn-danger';
            btnPDF.style.backgroundColor = '';
            btnPDF.style.borderColor = '';
            btnPDF.innerHTML = '<i class="fas fa-file-pdf me-1"></i> Generar Reporte de Estudiantes PDF';
        }
        if (modalAlert) {
            modalAlert.className = 'alert alert-info';
            modalAlert.innerHTML = `<i class="fas fa-info-circle me-1"></i> Se generará el reporte oficial en PDF con los <strong>${ids.length}</strong> estudiantes seleccionados según los filtros activos.`;
        }
    }

    $('#reporteModal').modal('show');
}

function generarReportePDF() {
    const tipoReporte = document.getElementById('reporteTipoSeleccionado')?.value || 'estudiantes';
    const estudiantesIds = getEstudiantesParaReporte(tipoReporte);
    const incluirEstadisticas = document.getElementById('incluirEstadisticas').value;

    if (estudiantesIds.length === 0) {
        mostrarMensaje('Sin resultados', 'No hay registros para generar el reporte con los filtros seleccionados', false);
        return;
    }

    // Enviar mediante POST para soportar cientos/miles de IDs sin límites de URL GET
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'reporte_estudiantes_pdf.php';
    form.target = '_blank';
    form.style.display = 'none';

    const inputIds = document.createElement('input');
    inputIds.type = 'hidden';
    inputIds.name = 'ids';
    inputIds.value = estudiantesIds.join(',');
    form.appendChild(inputIds);

    const inputEstadisticas = document.createElement('input');
    inputEstadisticas.type = 'hidden';
    inputEstadisticas.name = 'estadisticas';
    inputEstadisticas.value = incluirEstadisticas;
    form.appendChild(inputEstadisticas);

    const inputTipo = document.createElement('input');
    inputTipo.type = 'hidden';
    inputTipo.name = 'tipo_reporte';
    inputTipo.value = tipoReporte;
    form.appendChild(inputTipo);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);

    $('#reporteModal').modal('hide');
}

document.getElementById('limpiarBusqueda').addEventListener('click', function() {
    document.getElementById('buscadorCedula').value = '';
    [
        'filtroTodasCarreras', 'filtroTodasModalidades', 'filtroTodosGeneros', 
        'filtroTodosEdoCivil', 'filtroTodosStatus', 'filtroTodasSedes', 
        'filtroTodosTurnos', 'filtroTodosEstadosGeo', 'filtroTodasCiudades', 
        'filtroTodasCondiciones', 'filtroTodasEdades'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.checked = true;
            el.dispatchEvent(new Event('change'));
        }
    });
    document.querySelectorAll('.filtro').forEach(cb => cb.checked = false);

    // Restaurar filtros de condición
    const cbEst = document.getElementById('filtroTipoEstudiante');
    const cbEgr = document.getElementById('filtroTipoEgresado');
    const rTodos = document.getElementById('quickTipoTodos');
    if (cbEst) cbEst.checked = true;
    if (cbEgr) cbEgr.checked = true;
    if (rTodos) rTodos.checked = true;

    document.getElementById('edadMin').value = '';
    document.getElementById('edadMax').value = '';
    document.getElementById('fechaIngresoDesde').value = '';
    document.getElementById('fechaIngresoHasta').value = '';
    aplicarFiltrosYActualizar();
});

const btnReporteEst = document.getElementById('btnReporteEstudiantes');
if (btnReporteEst) {
    btnReporteEst.addEventListener('click', function() {
        abrirModalReporte('estudiantes');
    });
}

const btnReporteEgr = document.getElementById('btnReporteEgresados');
if (btnReporteEgr) {
    btnReporteEgr.addEventListener('click', function() {
        abrirModalReporte('egresados');
    });
}

const btnGenReporteLegacy = document.getElementById('btnGenerarReporte');
if (btnGenReporteLegacy) {
    btnGenReporteLegacy.addEventListener('click', function() {
        abrirModalReporte('estudiantes');
    });
}

document.getElementById('btnGenerarPDF').addEventListener('click', generarReportePDF);
document.getElementById('toggleFiltros').addEventListener('click', toggleFiltros);
document.getElementById('btnOcultarFiltros').addEventListener('click', toggleFiltros);

$(document).on('click', '.btn-details', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var id = $(this).attr('data-id') || $(this).data('id');
    loadStudentDetails(id);
});

$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();
    e.stopPropagation();
    var id = $(this).attr('data-id') || $(this).data('id');
    loadEditStudentForm(id);
});

function loadStudentDetails(studentId) {
    const modalContent = document.getElementById('detalleEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#detalleModal').modal('show');
    fetch(`detalle_estudiante.php?id=${studentId}`).then(r => r.text()).then(d => modalContent.innerHTML = d).catch(e => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${e.message}</div>`);
}

function loadEditStudentForm(studentId) {
    const modalContent = document.getElementById('editarEstudianteContent');
    modalContent.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div><p>Cargando...</p></div>`;
    $('#editarEstudianteModal').modal('show');
    fetch(`editar_estudiante_modal.php?id=${studentId}`).then(r => r.text()).then(d => {
        modalContent.innerHTML = d;
        const editForm = document.getElementById('formEditarEstudiante');
        if (editForm) editForm.addEventListener('submit', function(e) { e.preventDefault(); submitEditForm(this); });
    }).catch(e => modalContent.innerHTML = `<div class="alert alert-danger">Error: ${e.message}</div>`);
}

function submitEditForm(form) {
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    submitButton.disabled = true;
    fetch('actualizar_estudiante.php', { method: 'POST', body: formData })
        .then(r => r.json()).then(d => {
            if (d.success) {
                $('#editarEstudianteModal').modal('hide');
                mostrarMensaje('Éxito', d.message || 'Estudiante actualizado', true, true);
            } else {
                mostrarMensaje('Error', d.message || 'Error al actualizar', false);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        }).catch(e => {
            mostrarMensaje('Error', 'Error de conexión', false);
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
}

function mostrarMensaje(titulo, mensaje, esExito = true, recargar = false) {
    const header = document.getElementById('resultadoModalHeader');
    const body = document.getElementById('resultadoModalBody');
    const label = document.querySelector('#resultadoModal .modal-title');
    if (header && body) {
        header.className = `modal-header bg-${esExito ? 'success' : 'danger'} text-white`;
        body.innerHTML = `<div class="text-center py-3"><i class="fas fa-${esExito ? 'check-circle' : 'exclamation-circle'} fa-3x mb-3 text-${esExito ? 'success' : 'danger'}"></i><h4>${titulo}</h4><p class="mb-0">${mensaje}</p></div>`;
        if (label) label.textContent = titulo;
        $('#resultadoModal').modal('show');
        if (recargar) $('#resultadoModal').off('hidden.bs.modal').on('hidden.bs.modal', () => location.reload());
    }
}

$('#detalleModal, #editarEstudianteModal, #agregarEstudianteModal').on('hidden.bs.modal', function() {
    const contentId = this.id === 'detalleModal' ? 'detalleEstudianteContent' : (this.id === 'editarEstudianteModal' ? 'editarEstudianteContent' : null);
    if (contentId && document.getElementById(contentId)) { if (this.id !== 'agregarEstudianteModal') document.getElementById(contentId).innerHTML = ''; }
});

const formEstudiante = document.getElementById('formEstudianteModal');
if (formEstudiante) {
    formEstudiante.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        submitButton.disabled = true;
        fetch('procesar_estudiante.php', { method: 'POST', body: formData })
            .then(r => r.json()).then(d => {
                if (d.success) {
                    $('#agregarEstudianteModal').modal('hide');
                    mostrarMensaje('Éxito', d.message || 'Estudiante registrado', true, true);
                } else {
                    mostrarMensaje('Error', d.message || 'Error al guardar', false);
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            }).catch(e => {
                mostrarMensaje('Error', 'Error de conexión', false);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
    });
}

function abrirModalNuevoEstudiante() { $('#agregarEstudianteModal').modal('show'); }
</script>

<?php include("includes/footer.php"); ?>