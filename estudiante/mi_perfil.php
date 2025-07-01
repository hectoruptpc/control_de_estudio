<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();
require_once('../funciones/functions.php');

// Verificar autenticación y rol
if (!isLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

if (!isEstudiante()) {
    header('location: ../usuario/home.php');
    exit();
}

$titulopag = "Mi Perfil";

// Obtener ID de usuario de la sesión
$user_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;

if (!$user_id) {
    die("Sesión inválida: No se pudo obtener el ID de usuario");
}

// Consulta para obtener datos del usuario
$query = "SELECT * FROM users WHERE id = ? OR idusuario = ? LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $user_id, $user_id);

if (!$stmt->execute()) {
    die("Error al consultar los datos: " . $db->error);
}

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("No se encontraron datos para tu usuario. Por favor contacta al administrador.");
}

// Función auxiliar para mostrar datos
function mostrarDato($valor, $esBoolean = false) {
    if ($esBoolean) {
        return !empty($valor) ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>';
    }
    return !empty($valor) ? htmlspecialchars($valor) : '<span class="text-muted">No especificado</span>';
}

include("includes/head.php");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $titulopag; ?></h2>
        <a href="../usuario/home.php" class="btn btn-secondary">Volver</a>
    </div>

    <!-- Datos Básicos -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Datos Básicos</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Cédula:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['idusuario']); ?></dd>

                        <dt class="col-sm-4">Nombre Completo:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['nombre']); ?></dd>

                        <dt class="col-sm-4">Usuario:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['username']); ?></dd>

                        <dt class="col-sm-4">Correo Electrónico:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['email']); ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Teléfono Fijo:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['tlf']); ?></dd>

                        <dt class="col-sm-4">Celular:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['cel']); ?></dd>

                        <dt class="col-sm-4">Etnia:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['etnia']); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Datos de Vivienda -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Datos de Vivienda</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Dirección:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['direccion']); ?></dd>

                        <dt class="col-sm-4">Casa/Apartamento:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['casaapto']); ?></dd>

                        <dt class="col-sm-4">Punto de Referencia:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['punto_referencia']); ?></dd>

                        <dt class="col-sm-4">Tipo de Vivienda:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['tipo_vivienda']); ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Tenencia de Vivienda:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['tenencia_vivienda']); ?></dd>

                        <dt class="col-sm-4">Ciudad:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['ciudad']); ?></dd>

                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['estado']); ?></dd>

                        <dt class="col-sm-4">Municipio:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['municipio']); ?></dd>

                        <dt class="col-sm-4">Parroquia:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['parroquia']); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Situación Familiar -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Situación Familiar</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Grupo Familiar:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['grupo_familiar']); ?></dd>

                        <dt class="col-sm-4">Personas a su cargo:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['acargo_usted']); ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Fuente de Ingresos:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['fuente_ingresos']); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <?php
// Función auxiliar para mostrar listas
function mostrarLista($valor, $separador = ',') {
    if (empty($valor)) {
        return '<span class="text-muted">No especificado</span>';
    }
    
    $items = explode($separador, $valor);
    $html = '<ul class="mb-0">';
    foreach ($items as $item) {
        $html .= '<li>' . htmlspecialchars(trim($item)) . '</li>';
    }
    $html .= '</ul>';
    return $html;
}
?>

<!-- Formación Académica Mejorada -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Formación Académica</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Títulos Obtenidos:</h5>
                <?php echo mostrarLista($user['titulos']); ?>
            </div>
            <div class="col-md-6">
                <h5>Instituciones:</h5>
                <?php echo mostrarLista($user['institutos']); ?>
            </div>
        </div>
    </div>
</div>

    <!-- Salud -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Salud</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Enfermedad:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['enfermedad']); ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Discapacidad:</dt>
                        <dd class="col-sm-8"><?php echo mostrarDato($user['discapacidad']); ?></dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include("includes/footer.php"); 
?>