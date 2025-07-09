<?php
require_once('../funciones/functions.php');
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Error desconocido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$status = isset($_POST['status']) ? intval($_POST['status']) : null;

	if ($id > 0 && ($status === 0 || $status === 1)) {
		$result = updateUserStatus($id, $status);
		if (isset($result['success']) && $result['success']) {
			$response['success'] = true;
			$response['message'] = $result['message'] ?? 'Estado actualizado correctamente';
		} else {
			$response['message'] = $result['message'] ?? 'No se pudo actualizar el estado.';
		}
	} else {
		$response['message'] = 'Datos inválidos.';
	}
} else {
	$response['message'] = 'Método no permitido.';
}

echo json_encode($response);