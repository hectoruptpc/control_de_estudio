<?php
/**
 * ==============================================================================
 * SISTEMA DE CONTROL DE ESTUDIO - UPTPC
 * ARCHIVO DE CONFIGURACIÓN Y DECLARACIÓN DE VARIABLES GLOBALES
 * ==============================================================================
 */

// 1. ZONA HORARIA Y CONFIGURACIÓN REGIONAL
// ------------------------------------------------------------------------------
date_default_timezone_set('America/Caracas');
@setlocale(LC_ALL, 'es_VE.UTF-8', 'es_ES.UTF-8', 'spanish');

$start                = time();
$fecha_act            = date("Y-m-d H:i:s", $start);
$fecha_act_lectura    = date("d-m-Y H:i:s", $start);
$fecha_actual_sistema = date("Y-m-d");
$fecha_sistema        = date("Y/m/d");
$dia                  = date("d");
$mes                  = date("F");
$mes_de_pago_actual   = date("F/Y");
$mes_fecha_sistema    = date("m/Y");
$ano_sistema          = date("Y");
$nombrepag            = basename($_SERVER['PHP_SELF'] ?? '');

try {
    $fecha_dt = new DateTime('now', new DateTimeZone('America/Caracas'));
    if (class_exists('IntlDateFormatter')) {
        $formato_dt = new IntlDateFormatter('es_VE', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'America/Caracas');
        $fads = $formato_dt->format($fecha_dt);
    } else {
        $fads = date("d/m/Y", $start);
    }
} catch (Exception $e) {
    $fads = date("d/m/Y", $start);
}

// 2. DATOS INSTITUCIONALES OFICIALES
// ------------------------------------------------------------------------------
$nombre_empresa      = 'UNIVERSIDAD POLITÉCNICA TERRITORIAL DE PUERTO CABELLO';
$siglas_institucion  = 'UPTPC';
$rif_empresa         = 'G-20005608-8';
$direccion_empresa   = 'Urb. La Elvira Zona Industrial Santa Rosa Galpón N° 8. Puerto Cabello, Estado Carabobo';
$instituto           = 'UPTPC';

// 3. RUTAS, PROTOCOLOS Y DOMINIOS WEB
// ------------------------------------------------------------------------------
$carpeta   = '/control_de_estudio';
$protocolo = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https://" : "http://";
$dominio   = $_SERVER["HTTP_HOST"] ?? 'localhost';
$domain    = $dominio;
$resto_url = $_SERVER["REQUEST_URI"] ?? '';
$linklocal = '';
$pag_web   = $protocolo . $dominio . $carpeta;
$web_basea = array($protocolo, $dominio, $resto_url);

// 4. RECURSOS GRÁFICOS Y LOGOS INSTITUCIONALES
// ------------------------------------------------------------------------------
$logo_web         = '<img class="img-fluid" src="' . $pag_web . '/images/logo.png" width="150" height="25" alt="Logo">';
$logo_empresa     = '<img class="img-fluid" src="' . $pag_web . '/images/logoempresa.png" width="100" height="100" alt="Logo">';
$logo_empresag    = '<img class="img-fluid" src="' . $pag_web . '/images/logoempresa.png" width="500" height="500" alt="Logo">';
$logo_web_login   = '<img class="img-fluid" src="' . $pag_web . '/images/logo.png" width="450" height="80" alt="Logo Login">';
$logo_uptpc       = '<img class="img-fluid" src="' . $pag_web . '/images/uptpc.png" width="150" height="150" alt="UPTPC">';
$logo_uptpcp      = '<img class="img-fluid" src="' . $pag_web . '/images/uptpc.png" width="25" height="25" alt="UPTPC">';
$logopertenencia  = '<img class="img-fluid logo-header" src="' . $pag_web . '/images/logo.png" style="height: 46px; max-height: 50px; width: auto; display: inline-block; vertical-align: middle;" alt="Logo">';
$logo_footer      = '<img class="img-fluid" src="' . $pag_web . '/images/educacion_universitaria.jpg" style="max-height: 35px; width: auto;" alt="Logo MPPEU">';
$logopertenenciag = '<img class="img-fluid" src="' . $pag_web . '/images/logopertenenciag.png" width="700" height="100" alt="Logo">';
$image_responsive = '<img class="img-fluid" src="' . $pag_web . '/images/responsive.png" style="max-width: 80%; height: auto;" alt="Responsive">';

// 5. VARIABLES DE SESIÓN DE USUARIO GLOBAL
// ------------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_session         = $_SESSION['user'] ?? [];
$current_user_id      = intval($user_session['id'] ?? ($_SESSION['user_id'] ?? ($_SESSION['id'] ?? 0)));
$id_usua              = $current_user_id;
$current_username     = $user_session['username'] ?? ($user_session['usuario'] ?? ($_SESSION['username'] ?? ''));
$usua                 = $current_username;
$current_user_nombre  = $user_session['nombre'] ?? '';
$current_user_cedula  = $user_session['idusuario'] ?? '';
$current_user_email   = $user_session['email'] ?? '';
$current_user_type    = $user_session['user_type'] ?? '';

// Mapeo unificado de variables comunes
$cedula               = $current_user_cedula; // Alias intuitivo de idusuario
$idusuario            = $current_user_cedula;
$nombre               = $current_user_nombre;
$email                = $current_user_email;

// Roles de usuario activos
$is_admin             = !empty($user_session['admin']);
$is_super_user        = !empty($user_session['super_user']);
$is_docente           = !empty($user_session['docente']);
$is_estudiante        = !empty($user_session['estudiante']);
$is_director_carrera  = (!empty($user_session['gestion_director_carrera']) || !empty($user_session['carrera_di']));
$is_vocero            = !empty($user_session['vocero']);

// 6. TOKEN DE SEGURIDAD CSRF
// ------------------------------------------------------------------------------
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// 7. VARIABLES GLOBALES DE ESTADO Y FORMULARIOS
// ------------------------------------------------------------------------------
$errors        = [];
$error         = [];
$mensaje       = '';
$tipo_mensaje  = '';
$mensaje_exito = '';
$mensaje_error = '';

$init          = 0;
$limit_end     = 10;
$order         = '';
$url           = '';
$msn_iniciar_sesion = '<i class="fa fa-exclamation-triangle"></i> Debe iniciar sesión para continuar.';
$boton_volver       = '<a class="btn btn-info" href="javascript:window.history.go(-1);"><i class="fa fa-undo"></i> Volver</a>';

?>
