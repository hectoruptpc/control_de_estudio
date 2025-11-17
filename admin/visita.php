<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Seguimiento de Visitas de Usuarios";
include('../funciones/functions.php');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CARGAR PERMISOS - hacerlo ANTES de verificar
cargarPermisosUsuario();

// Verificar permiso para visita
verificarPermiso('visita');

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

// Verificar si es admin
if (!isAdmin()) {
    $_SESSION['error'] = "No tienes permisos de administrador para acceder a esta página.";
    header('location: ../login.php');
    exit();
}

// Configuración de paginación
$registros_por_pagina = 50; // Puedes ajustar este número
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

// Función para buscar usuario por cédula o nombre
function buscarUsuarioPorIdentificacion($busqueda) {
    global $db;
    
    try {
        $query = "SELECT 
                    id, 
                    idusuario AS cedula, 
                    nombre,
                    email,
                    tlf,
                    cel,
                    usuario,
                    estudiante,
                    docente,
                    admin
                  FROM users 
                  WHERE idusuario LIKE CONCAT('%', ?, '%')
                     OR nombre LIKE CONCAT('%', ?, '%')
                  ORDER BY nombre ASC
                  LIMIT 10";
        
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        $stmt->bind_param("ss", $busqueda, $busqueda);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $usuarios = [];

        while ($row = $result->fetch_assoc()) {
            // Determinar tipo de usuario
            $tipo_usuario = "Usuario";
            if ($row['estudiante']) $tipo_usuario = "Estudiante";
            if ($row['docente']) $tipo_usuario = "Docente";
            if ($row['admin']) $tipo_usuario = "Administrador";
            if ($row['usuario']) $tipo_usuario = "Director Carrera";
            
            $usuarios[] = [
                'id' => (int)$row['id'],
                'cedula' => $row['cedula'],
                'nombre' => $row['nombre'],
                'email' => $row['email'] ?? 'Sin email',
                'telefono' => $row['cel'] ?: ($row['tlf'] ?: 'Sin teléfono'),
                'tipo_usuario' => $tipo_usuario
            ];
        }

        $stmt->close();
        return $usuarios;
        
    } catch (Exception $e) {
        error_log("Error en buscarUsuarioPorIdentificacion: " . $e->getMessage());
        throw new Exception("Error al buscar usuario");
    }
}

// Función para obtener visitas del usuario con paginación
function obtenerVisitasUsuario($user_id, $filtros = [], $pagina = 1, $registros_por_pagina = 50) {
    global $db;
    
    try {
        // Calcular offset para paginación
        $offset = ($pagina - 1) * $registros_por_pagina;
        
        $query = "SELECT 
                    v.id,
                    v.id_usuario,
                    v.ip,
                    v.fecha_visita,
                    v.web,
                    u.nombre as nombre_usuario,
                    u.idusuario as cedula_usuario
                  FROM visitas v
                  INNER JOIN users u ON v.id_usuario = u.id
                  WHERE v.id_usuario = ?";
        
        // Aplicar filtros de fecha si existen
        $params = ["i", $user_id];
        
        if (!empty($filtros['fecha_desde'])) {
            $query .= " AND DATE(v.fecha_visita) >= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_desde'];
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $query .= " AND DATE(v.fecha_visita) <= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_hasta'];
        }
        
        $query .= " ORDER BY v.fecha_visita DESC 
                   LIMIT ? OFFSET ?";
        
        $params[0] .= "ii";
        $params[] = $registros_por_pagina;
        $params[] = $offset;
        
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Bind dinámico de parámetros
        $stmt->bind_param(...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $visitas = [];

        while ($row = $result->fetch_assoc()) {
            $visitas[] = [
                'id' => (int)$row['id'],
                'id_usuario' => (int)$row['id_usuario'],
                'ip' => $row['ip'],
                'fecha_visita' => $row['fecha_visita'],
                'web' => $row['web'],
                'nombre_usuario' => $row['nombre_usuario'],
                'cedula_usuario' => $row['cedula_usuario'],
                'fecha_formateada' => date('Y-m-d', strtotime($row['fecha_visita'])),
                'hora_formateada' => date('H:i:s', strtotime($row['fecha_visita']))
            ];
        }

        $stmt->close();
        return $visitas;
        
    } catch (Exception $e) {
        error_log("Error en obtenerVisitasUsuario: " . $e->getMessage());
        throw new Exception("Error al obtener visitas del usuario");
    }
}

// Función para contar total de visitas (para paginación)
function contarTotalVisitasUsuario($user_id, $filtros = []) {
    global $db;
    
    try {
        $query = "SELECT COUNT(*) as total
                  FROM visitas 
                  WHERE id_usuario = ?";
        
        $params = ["i", $user_id];
        
        if (!empty($filtros['fecha_desde'])) {
            $query .= " AND DATE(fecha_visita) >= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_desde'];
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $query .= " AND DATE(fecha_visita) <= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_hasta'];
        }
        
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Bind dinámico de parámetros
        $stmt->bind_param(...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        
        $stmt->close();
        return $total;
        
    } catch (Exception $e) {
        error_log("Error en contarTotalVisitasUsuario: " . $e->getMessage());
        return 0;
    }
}

// Función para obtener estadísticas del usuario
function obtenerEstadisticasUsuario($user_id, $filtros = []) {
    global $db;
    
    try {
        $query = "SELECT 
                    COUNT(*) as total_visitas,
                    COUNT(DISTINCT DATE(fecha_visita)) as dias_activos,
                    COUNT(DISTINCT ip) as ips_diferentes,
                    MIN(fecha_visita) as primera_visita,
                    MAX(fecha_visita) as ultima_visita,
                    COUNT(DISTINCT web) as paginas_diferentes
                  FROM visitas 
                  WHERE id_usuario = ?";
        
        $params = ["i", $user_id];
        
        if (!empty($filtros['fecha_desde'])) {
            $query .= " AND DATE(fecha_visita) >= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_desde'];
        }
        
        if (!empty($filtros['fecha_hasta'])) {
            $query .= " AND DATE(fecha_visita) <= ?";
            $params[0] .= "s";
            $params[] = $filtros['fecha_hasta'];
        }
        
        $stmt = $db->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $db->error);
        }
        
        // Bind dinámico de parámetros
        $stmt->bind_param(...$params);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $estadisticas = $result->fetch_assoc();
        
        $stmt->close();
        return $estadisticas;
        
    } catch (Exception $e) {
        error_log("Error en obtenerEstadisticasUsuario: " . $e->getMessage());
        return [
            'total_visitas' => 0,
            'dias_activos' => 0,
            'ips_diferentes' => 0,
            'primera_visita' => null,
            'ultima_visita' => null,
            'paginas_diferentes' => 0
        ];
    }
}

// Función para agrupar visitas por día
function agruparVisitasPorDia($visitas) {
    $visitas_agrupadas = [];
    
    foreach ($visitas as $visita) {
        $fecha = $visita['fecha_formateada'];
        if (!isset($visitas_agrupadas[$fecha])) {
            $visitas_agrupadas[$fecha] = [
                'fecha' => $fecha,
                'fecha_formateada' => date('d/m/Y', strtotime($fecha)),
                'total_visitas' => 0,
                'visitas' => []
            ];
        }
        $visitas_agrupadas[$fecha]['visitas'][] = $visita;
        $visitas_agrupadas[$fecha]['total_visitas']++;
    }
    
    return $visitas_agrupadas;
}

// Procesar búsqueda
$resultados_busqueda = [];
$usuario_seleccionado = null;
$visitas_usuario = [];
$visitas_agrupadas = [];
$estadisticas_usuario = [];
$mostrar_resultados = false;
$total_visitas = 0;
$total_paginas = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
    $busqueda = trim($_POST['busqueda']);
    
    if (!empty($busqueda)) {
        $resultados_busqueda = buscarUsuarioPorIdentificacion($busqueda);
        $mostrar_resultados = true;
    }
}

// Procesar selección de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['seleccionar_usuario'])) {
    $user_id = (int)$_POST['user_id'];
    $pagina_actual = 1; // Resetear a primera página cuando se selecciona nuevo usuario
    
    // Obtener información del usuario
    $query = "SELECT id, idusuario, nombre, email, tlf, cel, usuario, estudiante, docente, admin 
              FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario_seleccionado = $result->fetch_assoc();
    
    // Determinar tipo de usuario
    if ($usuario_seleccionado) {
        $tipo_usuario = "Usuario";
        if ($usuario_seleccionado['estudiante']) $tipo_usuario = "Estudiante";
        if ($usuario_seleccionado['docente']) $tipo_usuario = "Docente";
        if ($usuario_seleccionado['admin']) $tipo_usuario = "Administrador";
        if ($usuario_seleccionado['usuario']) $tipo_usuario = "Director Carrera";
        
        $usuario_seleccionado['tipo_usuario'] = $tipo_usuario;
    }
    
    // Guardar user_id en sesión para la paginación
    $_SESSION['visita_user_id'] = $user_id;
    $_SESSION['visita_filtros'] = [
        'fecha_desde' => $_POST['fecha_desde'] ?? '',
        'fecha_hasta' => $_POST['fecha_hasta'] ?? ''
    ];
}

// Si hay un usuario seleccionado (de sesión o nuevo)
if (isset($_SESSION['visita_user_id'])) {
    $user_id = $_SESSION['visita_user_id'];
    $filtros = $_SESSION['visita_filtros'] ?? [];
    
    // Obtener información del usuario si no está cargada
    if (!$usuario_seleccionado) {
        $query = "SELECT id, idusuario, nombre, email, tlf, cel, usuario, estudiante, docente, admin 
                  FROM users WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario_seleccionado = $result->fetch_assoc();
        
        if ($usuario_seleccionado) {
            $tipo_usuario = "Usuario";
            if ($usuario_seleccionado['estudiante']) $tipo_usuario = "Estudiante";
            if ($usuario_seleccionado['docente']) $tipo_usuario = "Docente";
            if ($usuario_seleccionado['admin']) $tipo_usuario = "Administrador";
            if ($usuario_seleccionado['usuario']) $tipo_usuario = "Director Carrera";
            
            $usuario_seleccionado['tipo_usuario'] = $tipo_usuario;
        }
    }
    
    // Obtener visitas y estadísticas con paginación
    $total_visitas = contarTotalVisitasUsuario($user_id, $filtros);
    $total_paginas = ceil($total_visitas / $registros_por_pagina);
    
    // Ajustar página actual si es necesario
    if ($pagina_actual > $total_paginas && $total_paginas > 0) {
        $pagina_actual = $total_paginas;
    }
    
    $visitas_usuario = obtenerVisitasUsuario($user_id, $filtros, $pagina_actual, $registros_por_pagina);
    $visitas_agrupadas = agruparVisitasPorDia($visitas_usuario);
    $estadisticas_usuario = obtenerEstadisticasUsuario($user_id, $filtros);
    
    $mostrar_resultados = false;
}

include("includes/head.php");
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-user-secret mr-2"></i>Seguimiento de Visitas de Usuarios
            </h1>
            
            <!-- Formulario de Búsqueda -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Buscar Usuario</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="busqueda">Buscar por Cédula o Nombre:</label>
                                <input type="text" class="form-control" id="busqueda" name="busqueda" 
                                       placeholder="Ingrese cédula o nombre del usuario..." 
                                       value="<?= isset($_POST['busqueda']) ? htmlspecialchars($_POST['busqueda']) : '' ?>" required>
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-end">
                                <button type="submit" name="buscar" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-2"></i>Buscar Usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Resultados de Búsqueda -->
            <?php if ($mostrar_resultados && !empty($resultados_busqueda)): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Resultados de la Búsqueda</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Cédula</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Tipo de Usuario</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultados_busqueda as $usuario): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usuario['cedula']) ?></td>
                                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                                    <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                                    <td><?= htmlspecialchars($usuario['tipo_usuario']) ?></td>
                                    <td>
                                        <form method="POST" action="" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                                            <input type="hidden" name="fecha_desde" value="<?= $_POST['fecha_desde'] ?? '' ?>">
                                            <input type="hidden" name="fecha_hasta" value="<?= $_POST['fecha_hasta'] ?? '' ?>">
                                            <button type="submit" name="seleccionar_usuario" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye mr-1"></i>Ver Visitas
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php elseif ($mostrar_resultados && empty($resultados_busqueda)): ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle mr-2"></i>No se encontraron usuarios con los criterios de búsqueda.
            </div>
            <?php endif; ?>
            
            <!-- Información del Usuario Seleccionado -->
            <?php if ($usuario_seleccionado): ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Usuario</h6>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="busqueda" value="<?= htmlspecialchars($usuario_seleccionado['idusuario']) ?>">
                        <button type="submit" name="buscar" class="btn btn-secondary btn-sm">
                            <i class="fas fa-redo mr-1"></i>Nueva Búsqueda
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Cédula:</strong> <?= htmlspecialchars($usuario_seleccionado['idusuario']) ?></p>
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario_seleccionado['nombre']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($usuario_seleccionado['email']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario_seleccionado['tlf'] ?: $usuario_seleccionado['cel'] ?: 'No registrado') ?></p>
                            <p><strong>Tipo de Usuario:</strong> <?= htmlspecialchars($usuario_seleccionado['tipo_usuario']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Visitas</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($estadisticas_usuario['total_visitas']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Días Activos</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($estadisticas_usuario['dias_activos']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        IPs Diferentes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($estadisticas_usuario['ips_diferentes']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-network-wired fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Páginas Diferentes</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= number_format($estadisticas_usuario['paginas_diferentes']) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información de Paginación -->
            <?php if ($total_visitas > 0): ?>
            <div class="card shadow mb-3">
                <div class="card-body py-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted">
                                Mostrando <?= number_format(($pagina_actual - 1) * $registros_por_pagina + 1) ?> 
                                a <?= number_format(min($pagina_actual * $registros_por_pagina, $total_visitas)) ?> 
                                de <?= number_format($total_visitas) ?> visitas
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="text-muted">Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Historial de Visitas Agrupado por Días -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Historial de Visitas (Agrupado por Días)</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($visitas_agrupadas)): ?>
                        <?php foreach ($visitas_agrupadas as $grupo): ?>
                        <div class="card mb-4 border-left-primary">
                            <div class="card-header bg-light py-2">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <?= $grupo['fecha_formateada'] ?>
                                    <span class="badge badge-primary ml-2"><?= $grupo['total_visitas'] ?> visitas</span>
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="20%">Hora</th>
                                                <th width="60%">Página Visitada</th>
                                                <th width="20%">Dirección IP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($grupo['visitas'] as $visita): ?>
                                            <tr>
                                                <td class="text-nowrap">
                                                    <i class="fas fa-clock text-muted mr-1"></i>
                                                    <?= htmlspecialchars($visita['hora_formateada']) ?>
                                                </td>
                                                <td>
                                                    <i class="fas fa-file-alt text-muted mr-1"></i>
                                                    <?= htmlspecialchars($visita['web']) ?>
                                                </td>
                                                <td class="text-nowrap">
                                                    <i class="fas fa-network-wired text-muted mr-1"></i>
                                                    <?= htmlspecialchars($visita['ip']) ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- Paginación -->
                        <?php if ($total_paginas > 1): ?>
                        <nav aria-label="Paginación de visitas">
                            <ul class="pagination justify-content-center">
                                <!-- Botón Anterior -->
                                <li class="page-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" 
                                       href="?pagina=<?= $pagina_actual - 1 ?>" 
                                       aria-label="Anterior">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                </li>
                                
                                <!-- Números de página -->
                                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                    <?php if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - 2 && $i <= $pagina_actual + 2)): ?>
                                        <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php elseif ($i == $pagina_actual - 3 || $i == $pagina_actual + 3): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                
                                <!-- Botón Siguiente -->
                                <li class="page-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                                    <a class="page-link" 
                                       href="?pagina=<?= $pagina_actual + 1 ?>" 
                                       aria-label="Siguiente">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Siguiente</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <?php endif; ?>
                        
                    <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>No se encontraron visitas registradas para este usuario.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>