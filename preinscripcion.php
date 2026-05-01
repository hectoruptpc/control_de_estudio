<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulo = "Preinscripción";
include('funciones/functions.php');

$modo_preinscripcion = true;
$esModal = false;

$tiposCedula = obtenerTiposCedula($db);
$estadosCiviles = obtenerEstadosCiviless($db);
$tiposVivienda = obtenerTiposVivienda($db);
$tenenciasVivienda = obtenerTenenciaViviendas($db);
$opcionesStatus = obtenerOpcionesStatus($db);
$carreras = obtenerTodasLasCarreras();
$ingresos = obtenerIngresos($db);
$estados = obtenerEstados($db);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['tipo_cedula'] = $_POST['tipo_cedula'] ?? '';
    $_POST['numero_cedula'] = preg_replace('/[^0-9]/', '', $_POST['numero_cedula'] ?? '');
    $_POST['idusuario'] = trim($_POST['tipo_cedula'] . $_POST['numero_cedula']);
    $_POST['status'] = 'Pendiente';
    $_POST['fecha_ingreso'] = $_POST['fecha_ingreso'] ?? date('Y-m-d');

    if (empty($_POST['tipo_cedula']) || empty($_POST['numero_cedula'])) {
        $error_message = 'Debe indicar tipo y número de cédula para la preinscripción.';
    } else {
        $validacion = validarEstudiante($_POST);
        if ($validacion === true) {
            $resultado = insertarPreinscripcion($_POST);
            if ($resultado['success']) {
                $success_message = $resultado['message'];
                $_POST = [];
            } else {
                $error_message = $resultado['message'];
            }
        } else {
            $error_message = implode('<br>', $validacion);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preinscripción</title>
    <?php echo $bootstrap_head; ?>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-file-signature me-2"></i> Preinscripción</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <p class="mb-4">Complete el formulario de preinscripción. Sus datos serán revisados por el equipo administrativo antes de crear su cuenta de estudiante.</p>

                    <?php include 'admin/_formulario_estudiante.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
