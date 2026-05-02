<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('admin');
visita();

$success_message = '';
$error_message = '';

$carreras = obtenerTodasLasCarreras();
$cuposActuales = obtenerCuposSecretaria();
$mostrarPreinscripcion = obtenerConfiguracionSecretaria('mostrar_preinscripcion', '1');
$mostrarProsecucion = obtenerConfiguracionSecretaria('mostrar_prosecucion', '1');
$turnos = ['Diurno', 'Nocturno'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action']) && $_POST['action'] === 'guardar') {
        foreach ($carreras as $carrera) {
            foreach ($turnos as $turno) {
                $valor = $_POST['cupos'][$carrera['id']][$turno] ?? '';
                $valor = (int) trim($valor);
                if ($valor < 0) {
                    $valor = 0;
                }
                guardarCupoSecretaria($carrera['id'], $turno, $valor);
            }
        }

        $mostrarPreinscripcion = isset($_POST['mostrar_preinscripcion']) ? '1' : '0';
        $mostrarProsecucion = isset($_POST['mostrar_prosecucion']) ? '1' : '0';
        guardarConfiguracionSecretaria('mostrar_preinscripcion', $mostrarPreinscripcion);
        guardarConfiguracionSecretaria('mostrar_prosecucion', $mostrarProsecucion);

        $cuposActuales = obtenerCuposSecretaria();
        $success_message = 'Los cupos y las opciones de visibilidad se actualizaron correctamente.';
    }
}

$titulopag = 'Secretaría - Gestión de cupos';
include('includes/head.php');
?>

<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-user-tie me-2"></i> Secretaría</h2>
            <p class="text-muted">Administre cupos por carrera y turno, y controle si los botones de preinscripción y prosecución quedan visibles.</p>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <form method="post" action="secretaria.php">
        <input type="hidden" name="action" value="guardar">

        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>Cupos por carrera y turno</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Programa</th>
                                <th>Turno</th>
                                <th>Cupos configurados</th>
                                <th>Ocupados</th>
                                <th>Disponibles</th>
                                <th>Actualizar cupos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carreras as $carrera): ?>
                                <?php foreach ($turnos as $turno): ?>
                                    <?php
                                        $total = $cuposActuales[$carrera['id']][$turno] ?? 0;
                                        $ocupados = contarPreinscripcionesPorCupo($carrera['id'], $turno);
                                        $libres = max(0, $total - $ocupados);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($carrera['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($turno); ?></td>
                                        <td><?php echo number_format($total); ?></td>
                                        <td><?php echo number_format($ocupados); ?></td>
                                        <td><?php echo number_format($libres); ?></td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="cupos[<?php echo (int)$carrera['id']; ?>][<?php echo htmlspecialchars($turno); ?>]" value="<?php echo htmlspecialchars($total); ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <strong>Visibilidad de botones públicos</strong>
            </div>
            <div class="card-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="mostrar_preinscripcion" name="mostrar_preinscripcion" <?php echo $mostrarPreinscripcion !== '0' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="mostrar_preinscripcion">
                        Mostrar botón de Preinscripción en la portada
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mostrar_prosecucion" name="mostrar_prosecucion" <?php echo $mostrarProsecucion !== '0' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="mostrar_prosecucion">
                        Mostrar botón de Prosecución en la portada
                    </label>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
