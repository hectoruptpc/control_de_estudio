<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$titulopag = "Asignación de Voceros";
include('../funciones/functions.php');

// Verificar autenticación básica
if (!isLoggedIn() || !isUser()) {
	$_SESSION['msg'] = "Debes iniciar sesión como director de carrera para acceder";
	header('location: ../login.php');
	exit();
}

// LLAMAR A LA FUNCIÓN DE VISITA
visita();

$carrera_director = $_SESSION['user']['carrera_di'];

// Procesar petición AJAX para alternar vocero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_vocero') {
	header('Content-Type: application/json');
	$response = ['success' => false];

	$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
	$new_value = isset($_POST['value']) ? intval($_POST['value']) : 0;

	if ($user_id <= 0) {
		echo json_encode(['success' => false, 'msg' => 'Usuario inválido']);
		exit();
	}

	// Verificar que el usuario sea estudiante y pertenezca a la carrera del director
	$query_check = "SELECT id, nombre, idusuario, vocero, carrera FROM users WHERE id = ? AND estudiante = 1 LIMIT 1";
	$stmt = $db->prepare($query_check);
	$stmt->bind_param('i', $user_id);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows === 0) {
		echo json_encode(['success' => false, 'msg' => 'Estudiante no encontrado']);
		exit();
	}

	$user = $result->fetch_assoc();

	if ($user['carrera'] != $carrera_director) {
		echo json_encode(['success' => false, 'msg' => 'No tiene permisos para modificar este estudiante']);
		exit();
	}

	$old_values = ['vocero' => intval($user['vocero'])];
	$new_values = ['vocero' => $new_value];

	// Actualizar campo vocero
	$query_update = "UPDATE users SET vocero = ? WHERE id = ?";
	$stmt2 = $db->prepare($query_update);
	$stmt2->bind_param('ii', $new_value, $user_id);
	$ok = $stmt2->execute();

	if ($ok && $stmt2->affected_rows >= 0) {
		// Registrar auditoría
		if (function_exists('registrarAuditoria')) {
			registrarAuditoria(
				'UPDATE',
				'users',
				$user_id,
				$old_values,
				$new_values,
				'Voceros',
				'Asignación/retirada de vocero para usuario: ' . ($user['idusuario'] ?? $user['nombre'])
			);
		}

		echo json_encode(['success' => true, 'vocero' => $new_value]);
		exit();
	}

	echo json_encode(['success' => false, 'msg' => 'Error al actualizar']);
	exit();
}

// Consulta de listado con buscador
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$params = [];
$where = "WHERE u.estudiante = 1 AND u.carrera = ?";
$params[] = $carrera_director;

if ($q !== '') {
	$where .= " AND (u.nombre LIKE ? OR u.idusuario LIKE ? )";
	$like = "%" . $q . "%";
	$params[] = $like;
	$params[] = $like;
}

$query = "SELECT u.id, u.nombre, u.idusuario, u.vocero, u.carrera FROM users u " . $where . " ORDER BY u.nombre LIMIT 500";

$stmt = $db->prepare($query);

// Bind dinámico
if (count($params) === 1) {
	$stmt->bind_param('i', $params[0]);
} elseif (count($params) === 3) {
	$stmt->bind_param('iss', $params[0], $params[1], $params[2]);
}

$stmt->execute();
$result = $stmt->get_result();

?>
<!doctype html>
<html lang="es">
<head>
	<?php include("includes/head.php"); ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
	<title><?= htmlspecialchars($titulopag) ?></title>
</head>
<body>
	<div class="container py-4">
		<div class="d-flex align-items-center justify-content-between mb-4">
			<h3 class="mb-0"><?= htmlspecialchars($titulopag) ?></h3>
			<div>Director: <strong><?= htmlspecialchars($_SESSION['user']['nombre'] ?? '') ?></strong></div>
		</div>

		<form method="get" class="form-inline mb-3">
			<div class="form-group mr-2 w-75">
				<input type="text" name="q" value="<?= htmlspecialchars($q) ?>" class="form-control w-100" placeholder="Buscar por nombre o cédula">
			</div>
			<button class="btn btn-primary mr-2">Buscar</button>
			<a href="asignacion_voceros.php" class="btn btn-outline-secondary">Limpiar</a>
		</form>

		<div class="table-responsive">
		<table class="table table-sm table-hover">
			<thead class="thead-light">
				<tr>
					<th>Nombre</th>
					<th>Cédula</th>
					<th>Vocero</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($row = $result->fetch_assoc()): ?>
					<?php if (intval($row['carrera']) !== intval($carrera_director)) continue; ?>
					<tr data-user-id="<?= $row['id'] ?>">
						<td><?= htmlspecialchars($row['nombre']) ?></td>
						<td><?= htmlspecialchars($row['idusuario']) ?></td>
						<td><?= $row['vocero'] ? '<span class="badge badge-success">Sí</span>' : '<span class="badge badge-secondary">No</span>' ?></td>
						<td>
							<button class="btn btn-sm <?= $row['vocero'] ? 'btn-danger' : 'btn-success' ?> toggle-vocero" data-id="<?= $row['id'] ?>" data-value="<?= $row['vocero'] ? 0 : 1 ?>">
								<?= $row['vocero'] ? '<i class="fas fa-times"></i> Quitar' : '<i class="fas fa-check"></i> Marcar' ?>
							</button>
						</td>
					</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
		</div>

	</div>

	<?php include("includes/footer.php"); ?>

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

	<script>
	document.addEventListener('click', function(e){
		if (e.target && e.target.closest('.toggle-vocero')) {
			e.preventDefault();
			var btn = e.target.closest('.toggle-vocero');
			var userId = btn.getAttribute('data-id');
			var value = btn.getAttribute('data-value');

			btn.disabled = true;
			var originalHtml = btn.innerHTML;
			btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

			var form = new FormData();
			form.append('action','toggle_vocero');
			form.append('user_id', userId);
			form.append('value', value);

			fetch('asignacion_voceros.php', {
				method: 'POST',
				body: form
			}).then(function(resp){
				return resp.json();
			}).then(function(json){
				if (json.success) {
					window.location.reload();
				} else {
					alert(json.msg || 'Error al actualizar');
					btn.disabled = false;
					btn.innerHTML = originalHtml;
				}
			}).catch(function(){
				alert('Error en la comunicación');
				btn.disabled = false;
				btn.innerHTML = originalHtml;
			});
		}
	});
	</script>
</body>
</html>

