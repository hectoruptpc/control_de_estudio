<?php
// .dios/ajax_usuarios.php - Buscador AJAX simple
header('Content-Type: application/json');

// Asegurar que esta petición use la sesión DIOS correcta
session_name('DIOS_SESSION');
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'path' => '/control_de_estudio/.dios/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Cargar configuración DIOS y entorno compartido
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../funciones/functions.php';

// Validar que el usuario DIOS esté autenticado y que el token AJAX pertenezca a la sesión
$token = $_GET['token'] ?? '';
if (!isset($_SESSION['dios_autenticado']) || $_SESSION['dios_autenticado'] !== true || empty($token) || !hash_equals($_SESSION['dios_ajax_token'] ?? '', $token)) {
    echo json_encode([
        'html' => '<div class="alert alert-danger">No autorizado</div>',
        'paginacion' => '',
        'total' => 0,
        'mostrando' => 0
    ]);
    exit;
}

$db = $GLOBALS['db'];

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 15;
$offset = ($pagina - 1) * $por_pagina;

// Obtener usuarios
if (!empty($buscar)) {
    $buscar_param = "%$buscar%";
    $query = "SELECT id, nombre, email, username, status FROM users 
              WHERE nombre LIKE ? OR email LIKE ? OR username LIKE ? OR id LIKE ? 
              ORDER BY id LIMIT $offset, $por_pagina";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $buscar_param, $buscar_param, $buscar_param, $buscar_param);
    mysqli_stmt_execute($stmt);
    $usuarios = mysqli_stmt_get_result($stmt);
    
    // Total
    $count_query = "SELECT COUNT(*) as total FROM users 
                    WHERE nombre LIKE ? OR email LIKE ? OR username LIKE ? OR id LIKE ?";
    $stmt_count = mysqli_prepare($db, $count_query);
    mysqli_stmt_bind_param($stmt_count, "ssss", $buscar_param, $buscar_param, $buscar_param, $buscar_param);
    mysqli_stmt_execute($stmt_count);
    $count_result = mysqli_stmt_get_result($stmt_count);
    $total = mysqli_fetch_assoc($count_result)['total'];
} else {
    $query = "SELECT id, nombre, email, username, status FROM users ORDER BY id LIMIT $offset, $por_pagina";
    $usuarios = mysqli_query($db, $query);
    
    $count_result = mysqli_query($db, "SELECT COUNT(*) as total FROM users");
    $total = mysqli_fetch_assoc($count_result)['total'];
}

$total_paginas = ceil($total / $por_pagina);

// Generar HTML
$html = '<div class="table-responsive">
    <table class="table table-bordered">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Username</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>';

if (mysqli_num_rows($usuarios) > 0) {
    while($u = mysqli_fetch_assoc($usuarios)) {
        $estado_class = ($u['status'] == 1) ? 'success' : 'danger';
        $estado_texto = ($u['status'] == 1) ? 'ACTIVO' : 'BLOQUEADO';
        
        $html .= '<tr>
            <td>' . $u['id'] . '</td>
            <td>' . htmlspecialchars($u['nombre']) . '</td>
            <td>' . htmlspecialchars($u['email']) . '</td>
            <td>' . htmlspecialchars($u['username']) . '</td>
            <td><span class="badge badge-' . $estado_class . '">' . $estado_texto . '</span></td>
            <td>
                <button type="button" class="btn btn-warning btn-sm" onclick="abrirModal(' . $u['id'] . ', \'' . addslashes($u['nombre']) . '\', \'' . addslashes($u['email']) . '\')">
                    <i class="fas fa-key"></i> Clave
                </button>';
        
        if($u['status'] == 1) {
            $html .= '<form method="POST" class="d-inline" onsubmit="return confirm(\'¿Bloquear?\')">
                        <input type="hidden" name="accion" value="bloquear_usuario">
                        <input type="hidden" name="user_id" value="' . $u['id'] . '">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-lock"></i> Bloq</button>
                      </form>';
        } else {
            $html .= '<form method="POST" class="d-inline" onsubmit="return confirm(\'¿Desbloquear?\')">
                        <input type="hidden" name="accion" value="desbloquear_usuario">
                        <input type="hidden" name="user_id" value="' . $u['id'] . '">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-unlock-alt"></i> Desbloq</button>
                      </form>';
        }
        
        $html .= '</td></tr>';
    }
} else {
    $html .= '<tr><td colspan="6" class="text-center">No se encontraron usuarios</td></tr>';
}

$html .= '</tbody></table></div>';

// Paginación
$paginacion = '';
if ($total_paginas > 1) {
    $paginacion = '<nav><ul class="pagination justify-content-center">';
    $paginacion .= '<li class="page-item ' . ($pagina <= 1 ? 'disabled' : '') . '"><a class="page-link" href="#" data-page="' . ($pagina - 1) . '">«</a></li>';
    
    for ($i = 1; $i <= $total_paginas; $i++) {
        $active = ($i == $pagina) ? 'active' : '';
        $paginacion .= '<li class="page-item ' . $active . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    
    $paginacion .= '<li class="page-item ' . ($pagina >= $total_paginas ? 'disabled' : '') . '"><a class="page-link" href="#" data-page="' . ($pagina + 1) . '">»</a></li>';
    $paginacion .= '</ul></nav>';
}

echo json_encode([
    'html' => $html,
    'paginacion' => $paginacion,
    'total' => $total,
    'mostrando' => mysqli_num_rows($usuarios)
]);
?>