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
$mostrarPreinscripcion = obtenerConfiguracionSecretaria('mostrar_preinscripcion', '1');
$mostrarProsecucion = obtenerConfiguracionSecretaria('mostrar_prosecucion', '1');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['tipo_cedula'] = $_POST['tipo_cedula'] ?? '';
    $_POST['numero_cedula'] = preg_replace('/[^0-9]/', '', $_POST['numero_cedula'] ?? '');
    $_POST['idusuario'] = trim($_POST['tipo_cedula'] . $_POST['numero_cedula']);
    $_POST['turno'] = $_POST['turno'] ?? '';
    $_POST['status'] = 'Pendiente';
    $_POST['fecha_ingreso'] = $_POST['fecha_ingreso'] ?? date('Y-m-d');

    if ($mostrarPreinscripcion === '0') {
        $error_message = 'La preinscripción se encuentra temporalmente deshabilitada. Por favor contacte a Secretaría.';
    } elseif (empty($_POST['tipo_cedula']) || empty($_POST['numero_cedula'])) {
        $error_message = 'Debe indicar tipo y número de cédula para la preinscripción.';
    } else {
        $validacion = validarEstudiante($_POST);
        if ($validacion === true) {
            $resultado = insertarPreinscripcion($_POST);
            if ($resultado['success']) {
                $success_message = $resultado['message'];
                
                // Agregar botón para descargar planilla en el mensaje de éxito
                $success_message .= '<br><br>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-download"></i> 
                    <strong>¿No se descargó automáticamente?</strong>
                    <a href="admin/generar_planilla_pdf.php?id=' . $resultado['id'] . '" class="btn btn-sm btn-primary ms-3" target="_blank">
                        <i class="fas fa-file-pdf"></i> Descargar Planilla
                    </a>
                </div>';
                
                // JavaScript para descarga automática del PDF
                echo '<script>
                    setTimeout(function() {
                        window.open("admin/generar_planilla_pdf.php?id=' . $resultado['id'] . '", "_blank");
                    }, 500);
                </script>';
                
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

                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-file-pdf me-2"></i> 
                        <strong>IMPORTANTE:</strong> Al finalizar su preinscripción, se descargará automáticamente una planilla en formato PDF. 
                        Deberá presentar esta planilla impresa en <strong>Control de Estudios</strong> para formalizar su inscripción. 
                        <strong>La planilla es OBLIGATORIA</strong> y debe ser entregada dentro de los días hábiles siguientes a su preinscripción.
                    </div>

                    <p class="mb-4">Complete el formulario de preinscripción con sus datos personales, académicos y de contacto. Todos los campos marcados con <span class="text-danger">*</span> son obligatorios.</p>

                    <?php if ($mostrarPreinscripcion !== '0'): ?>
                        <?php include 'admin/_formulario_estudiante.php'; ?>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            La preinscripción está deshabilitada actualmente. Por favor contacte a Secretaría para más información.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>