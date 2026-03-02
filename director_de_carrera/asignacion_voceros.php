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

// Responder búsqueda en tiempo real (AJAX)
if (isset($_GET['ajax']) && $_GET['ajax'] === 'search') {
	header('Content-Type: application/json');
	$q_ajax = isset($_GET['q']) ? trim($_GET['q']) : '';

	$where_ajax = "WHERE u.estudiante = 1 AND u.carrera = ?";
	$params_ajax = [$carrera_director];

	if ($q_ajax !== '') {
		$where_ajax .= " AND (u.nombre LIKE ? OR u.idusuario LIKE ? )";
		$like_ajax = "%" . $q_ajax . "%";
		$params_ajax[] = $like_ajax;
		$params_ajax[] = $like_ajax;
	}

	$query_ajax = "SELECT u.id, u.nombre, u.idusuario, u.vocero, u.carrera FROM users u " . $where_ajax . " ORDER BY u.nombre LIMIT 200";
	$stmt_ajax = $db->prepare($query_ajax);
	if (count($params_ajax) === 1) {
		$stmt_ajax->bind_param('i', $params_ajax[0]);
	} elseif (count($params_ajax) === 3) {
		$stmt_ajax->bind_param('iss', $params_ajax[0], $params_ajax[1], $params_ajax[2]);
	}
	$stmt_ajax->execute();
	$res_ajax = $stmt_ajax->get_result();
	$rows = [];
	while ($r = $res_ajax->fetch_assoc()) {
		// seguridad: sólo devolver si pertenece a la misma carrera
		if (intval($r['carrera']) !== intval($carrera_director)) continue;
		$rows[] = $r;
	}
	echo json_encode(['success' => true, 'rows' => $rows]);
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
							<button class="btn btn-sm <?= $row['vocero'] ? 'btn-danger' : 'btn-success' ?> toggle-vocero" data-id="<?= $row['id'] ?>" data-value="<?= $row['vocero'] ? 0 : 1 ?>" data-name="<?= htmlspecialchars($row['nombre'], ENT_QUOTES) ?>">
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

	<!-- Modal de confirmación -->
	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="confirmModalLabel">Confirmar acción</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<p id="confirmText">¿Desea continuar?</p>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
			<button type="button" class="btn btn-primary" id="confirmBtn">Confirmar</button>
		  </div>
		</div>
	  </div>
	</div>

	<script>
	// Búsqueda en tiempo real con debounce
	(function(){
		var timer = null;
		var input = document.querySelector('input[name="q"]');
		var tbody = document.querySelector('table tbody');

		function renderRows(rows){
			var html = '';
			rows.forEach(function(r){
				var btnClass = r.vocero == 1 ? 'btn-danger' : 'btn-success';
				var btnText = r.vocero == 1 ? '<i class="fas fa-times"></i> Quitar' : '<i class="fas fa-check"></i> Marcar';
				html += '<tr data-user-id="'+r.id+'">';
				html += '<td>'+escapeHtml(r.nombre)+'</td>';
				html += '<td>'+escapeHtml(r.idusuario)+'</td>';
				html += '<td>'+(r.vocero==1?'<span class="badge badge-success">Sí</span>':'<span class="badge badge-secondary">No</span>')+'</td>';
				html += '<td><button class="btn btn-sm '+btnClass+' toggle-vocero" data-id="'+r.id+'" data-value="'+(r.vocero==1?0:1)+'" data-name="'+escapeHtmlAttr(r.nombre)+'">'+btnText+'</button></td>';
				html += '</tr>';
			});
			tbody.innerHTML = html;
		}

		function escapeHtml(str){ return (str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
		function escapeHtmlAttr(str){ return (str||'').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

		if (input) {
			input.addEventListener('input', function(){
				clearTimeout(timer);
				timer = setTimeout(function(){
					var q = input.value.trim();
					var url = 'asignacion_voceros.php?ajax=search&q='+encodeURIComponent(q);
					fetch(url).then(function(r){return r.json();}).then(function(json){
						if (json.success) renderRows(json.rows);
					});
				}, 300);
			});
		}

		// Delegación para botones generados dinámicamente
		document.addEventListener('click', function(e){
			var btn = e.target.closest('.toggle-vocero');
			if (!btn) return;
			e.preventDefault();
			var userId = btn.getAttribute('data-id');
			var value = btn.getAttribute('data-value');
			var name = btn.getAttribute('data-name') || '';

			// Mostrar modal
			var confirmText = document.getElementById('confirmText');
			confirmText.innerHTML = '¿Desea ' + (value==1 ? 'marcar' : 'quitar') + ' como vocero a <strong>'+ escapeHtml(name) +'</strong>?';
			$('#confirmModal').data('userid', userId).data('value', value).modal('show');
		});

		// Confirmar acción
		document.getElementById('confirmBtn').addEventListener('click', function(){
			var modal = $('#confirmModal');
			var userId = modal.data('userid');
			var value = modal.data('value');
			var btn = document.querySelector('button.toggle-vocero[data-id="'+userId+'"]');
			if (btn) { btn.disabled = true; }
			modal.modal('hide');

			var form = new FormData();
			form.append('action','toggle_vocero');
			form.append('user_id', userId);
			form.append('value', value);

			fetch('asignacion_voceros.php', { method: 'POST', body: form }).then(function(r){ return r.json(); }).then(function(json){
				if (json.success) {
					// refrescar búsqueda actual o recargar
					if (input && input.value.trim() !== '') {
						input.dispatchEvent(new Event('input'));
					} else {
						window.location.reload();
					}
				} else {
					alert(json.msg || 'Error al actualizar');
					if (btn) btn.disabled = false;
				}
			}).catch(function(){
				alert('Error en la comunicación');
				if (btn) btn.disabled = false;
			});
		});

	})();
	</script>
</body>
</html>

