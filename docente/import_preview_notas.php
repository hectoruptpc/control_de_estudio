<?php
require_once('../funciones/functions.php');

ob_start();
header('Content-Type: application/json; charset=utf-8');

function respondJson($data, $httpCode = 200) {
    $extra = trim(ob_get_clean());
    if ($extra !== '') {
        $data['server_output'] = substr($extra, 0, 4096);
    }
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

if (!isLoggedIn() || !isDocente()) {
    respondJson(['error' => 'Acceso denegado'], 403);
}

$seccion_id = $_POST['seccion_id'] ?? null;
$materia_id = $_POST['materia_id'] ?? null;

if (!$seccion_id || !$materia_id) {
    respondJson(['error' => 'Parámetros faltantes'], 400);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    respondJson(['error' => 'No se recibió el archivo o hubo un error en la subida'], 400);
}

$maxBytes = 5 * 1024 * 1024;
if ($_FILES['file']['size'] > $maxBytes) {
    respondJson(['error' => 'Archivo demasiado grande (máx 5MB)'], 413);
}

global $db;
$estudiantes_seccion = [];
$query = "SELECT u.id, u.idusuario, u.nombre 
          FROM estudiante_seccion es
          INNER JOIN users u ON es.id_usuario = u.id
          WHERE es.id_seccion = " . intval($seccion_id) . "
          AND u.estudiante = 1
          ORDER BY u.nombre ASC";
$result = $db->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $estudiantes_seccion[$row['idusuario']] = $row;
    }
}

$tmp = $_FILES['file']['tmp_name'];
$handle = fopen($tmp, 'r');
if (!$handle) {
    respondJson(['error' => 'No se pudo procesar el archivo'], 500);
}

$rows = [];
$validCount = 0;
$invalidCount = 0;
$line = 0;
$isFirstLine = true;

while (($data = fgetcsv($handle, 10000, ',')) !== false) {
    $line++;
    
    foreach ($data as $k => $v) {
        $data[$k] = trim(preg_replace('/^\xEF\xBB\xBF/', '', $v));
    }
    
    $isEmpty = true;
    foreach ($data as $cell) {
        if ($cell !== '') { $isEmpty = false; break; }
    }
    if ($isEmpty) continue;
    
    if ($isFirstLine) {
        $isFirstLine = false;
        continue;
    }
    
    $cedula = isset($data[0]) ? strtoupper(trim($data[0])) : '';
    $estudiante = $estudiantes_seccion[$cedula] ?? null;
    
    $nota1 = isset($data[5]) ? trim($data[5]) : '';
    $nota2 = isset($data[6]) ? trim($data[6]) : '';
    $nota3 = isset($data[7]) ? trim($data[7]) : '';
    
    $rowObj = [
        'line' => $line,
        'identificador' => $cedula,
        'nombre' => $estudiante['nombre'] ?? '',
        'valido' => false,
        'mensaje' => '',
        'estudiante_id' => $estudiante['id'] ?? null,
        'notas' => [],
        'notas_texto' => ''
    ];
    
    if (!$estudiante) {
        $rowObj['mensaje'] = 'Estudiante no encontrado en la sección';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }
    
    $notas_trimestres = [];
    $notas_texto = [];
    
    if ($nota1 !== '') {
        $val = floatval(str_replace(',', '.', $nota1));
        if ($val >= 1 && $val <= 20) {
            $notas_trimestres['trimestre_1'] = $val;
            $notas_texto[] = "T1:$val";
        } else {
            $rowObj['mensaje'] .= 'T1 inválido (' . $nota1 . '); ';
        }
    }
    
    if ($nota2 !== '') {
        $val = floatval(str_replace(',', '.', $nota2));
        if ($val >= 1 && $val <= 20) {
            $notas_trimestres['trimestre_2'] = $val;
            $notas_texto[] = "T2:$val";
        } else {
            $rowObj['mensaje'] .= 'T2 inválido (' . $nota2 . '); ';
        }
    }
    
    if ($nota3 !== '') {
        $val = floatval(str_replace(',', '.', $nota3));
        if ($val >= 1 && $val <= 20) {
            $notas_trimestres['trimestre_3'] = $val;
            $notas_texto[] = "T3:$val";
        } else {
            $rowObj['mensaje'] .= 'T3 inválido (' . $nota3 . '); ';
        }
    }
    
    if (empty($notas_trimestres)) {
        $rowObj['mensaje'] = $rowObj['mensaje'] ?: 'No se encontraron notas válidas';
        $invalidCount++;
    } else {
        $rowObj['valido'] = true;
        $rowObj['notas'] = $notas_trimestres;
        $rowObj['notas_texto'] = implode(' | ', $notas_texto);
        $rowObj['mensaje'] = 'OK';
        $validCount++;
    }
    
    $rows[] = $rowObj;
}

fclose($handle);

respondJson([
    'previewRows' => $rows,
    'summary' => [
        'total' => count($rows),
        'validas' => $validCount,
        'invalidas' => $invalidCount
    ]
]);
?>