<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Panel de Vocero";
require_once('../funciones/functions.php');

// acceso básico
if (!isLoggedIn() || !isEstudiante()) {
    $_SESSION['msg'] = "Debes iniciar sesión como estudiante para acceder";
    header('location: ../login.php');
    exit();
}

$uid = intval($_SESSION['user']['id']);
// Verificar el marcador de vocero (consultar DB para asegurarse de estar al día)
$is_vocero = esVoceroUsuario($uid);

if (!$is_vocero) {
    $_SESSION['msg'] = "Acceso denegado: esta sección es solo para voceros";
    header('location: index.php');
    exit();
}

// registro de visita
visita();

// identificar sección del vocero
$seccion = obtenerSeccionEstudiante($db, $uid);
$estudiantes = [];
if ($seccion) {
    $estudiantes = obtenerEstudiantesConNotasSeccion($seccion['id_seccion']);
}

include("includes/head.php");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Panel del Vocero</h1>
        <a href="index.php" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>

    <?php if ($seccion): ?>
        <p>Sección: <strong><?= htmlspecialchars($seccion['codigo_seccion']) ?></strong></p>

        <!-- Panel de opciones para vocero -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-bar fa-2x mb-2 text-primary"></i>
                        <h5 class="card-title">Ver Notas</h5>
                        <p class="card-text">Revisa las calificaciones de tus compañeros de sección.</p>
                        <a href="#notas" class="btn btn-sm btn-primary">Ir a notas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2 text-success"></i>
                        <h5 class="card-title">Lista de Estudiantes</h5>
                        <p class="card-text">Consulta quiénes están inscritos en tu sección.</p>
                        <a href="#notas" class="btn btn-sm btn-success">Ver lista</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope fa-2x mb-2 text-warning"></i>
                        <h5 class="card-title">Mensajes</h5>
                        <p class="card-text">Enviar mensajes al docente o a la administración.</p>
                        <a href="mensajeria_estudiantes.php" class="btn btn-sm btn-warning">Ir a mensajería</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de notas -->
        <div id="notas" class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Notas de la Sección <?= htmlspecialchars($seccion['codigo_seccion']) ?></h5>
            </div>
            <div class="card-body table-responsive">
                <?php if (empty($estudiantes)): ?>
                    <div class="alert alert-info">No hay estudiantes inscritos en tu sección.</div>
                <?php else: ?>
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Estudiante</th>
                                <th>Cédula</th>
                                <th>Materia</th>
                                <th>Cód.</th>
                                <th>Trayecto 0</th>
                                <th>Trayecto 1</th>
                                <th>Trayecto 2</th>
                                <th>Trayecto 3</th>
                                <th>Trayecto 4</th>
                                <th>Período</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($estudiantes as $est):
                            $nombre = htmlspecialchars($est['nombre']);
                            $ced = htmlspecialchars($est['cedula']);
                            $notas = $est['notas'];
                            if (empty($notas)) {
                                echo "<tr><td>{$nombre}</td><td>{$ced}</td><td colspan=\"9\" class=\"text-center\">Sin notas registradas</td></tr>";
                            } else {
                                foreach ($notas as $nota) {
                                    $t0 = $nota['trayecto_0'] !== null ? htmlspecialchars($nota['trayecto_0']) : '';
                                    $t1 = $nota['trayecto_1'] !== null ? htmlspecialchars($nota['trayecto_1']) : '';
                                    $t2 = $nota['trayecto_2'] !== null ? htmlspecialchars($nota['trayecto_2']) : '';
                                    $t3 = $nota['trayecto_3'] !== null ? htmlspecialchars($nota['trayecto_3']) : '';
                                    $t4 = $nota['trayecto_4'] !== null ? htmlspecialchars($nota['trayecto_4']) : '';
                                    echo "<tr>
                                            <td>{$nombre}</td>
                                            <td>{$ced}</td>
                                            <td>".htmlspecialchars($nota['nombre_materia'])."</td>
                                            <td>".htmlspecialchars($nota['cod_materia'])."</td>
                                            <td>{$t0}</td>
                                            <td>{$t1}</td>
                                            <td>{$t2}</td>
                                            <td>{$t3}</td>
                                            <td>{$t4}</td>
                                            <td>".htmlspecialchars($nota['nombre_periodo'])."</td>
                                            <td>".ucfirst(htmlspecialchars($nota['estado'] ?? ''))."</td>
                                          </tr>";
                                }
                            }
                        endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning">No se pudo determinar tu sección. Contacta al administrador.</div>
    <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
