<?php
require_once('../funciones/functions.php');
header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isDocente()) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

$docente_id = obtenerIdUsuario();
$seccion_id = $_POST['seccion_id'] ?? null;
$materia_id = $_POST['materia_id'] ?? null;
$trayecto_actual = isset($_POST['trayecto_actual']) ? (int)$_POST['trayecto_actual'] : 0;

if (!$seccion_id || !$materia_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros faltantes']);
    exit();
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No se recibió el archivo o hubo un error en la subida']);
    exit();
}

// Límite razonable
$maxBytes = 5 * 1024 * 1024; // 5 MB
if ($_FILES['file']['size'] > $maxBytes) {
    http_response_code(413);
    echo json_encode(['error' => 'Archivo demasiado grande (máx 5MB)']);
    exit();
}

$tmp = $_FILES['file']['tmp_name'];
$handle = fopen($tmp, 'r');
if (!$handle) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo procesar el archivo']);
    exit();
}

// Obtener lista de estudiantes de la sección para validación rápida
$seccionMap = []; // cedula_normalizada => ['id'=>..., 'nombre'=>...]
if (function_exists('obtenerEstudiantesDeSeccion')) {
    global $db;
    $res = obtenerEstudiantesDeSeccion($db, $seccion_id);
    if (is_object($res)) {
        while ($r = $res->fetch_assoc()) {
            $ced = '';
            if (isset($r['cedula'])) $ced = $r['cedula'];
            elseif (isset($r['identificacion'])) $ced = $r['identificacion'];
            elseif (isset($r['numero_cedula'])) $ced = $r['numero_cedula'];
            $key = preg_replace('/\s+/', '', strtolower($ced));
            $seccionMap[$key] = [
                'id' => $r['id'] ?? $r['id_usuario'] ?? $r['usuario_id'] ?? null,
                'nombre' => trim(($r['nombres'] ?? '') . ' ' . ($r['apellidos'] ?? $r['apellido'] ?? ''))
            ];
        }
    } elseif (is_array($res)) {
        foreach ($res as $r) {
            $ced = '';
            if (isset($r['cedula'])) $ced = $r['cedula'];
            elseif (isset($r['identificacion'])) $ced = $r['identificacion'];
            elseif (isset($r['numero_cedula'])) $ced = $r['numero_cedula'];
            $key = preg_replace('/\s+/', '', strtolower($ced));
            $seccionMap[$key] = [
                'id' => $r['id'] ?? $r['id_usuario'] ?? $r['usuario_id'] ?? null,
                'nombre' => trim(($r['nombres'] ?? '') . ' ' . ($r['apellidos'] ?? $r['apellido'] ?? ''))
            ];
        }
    }
}

$rows = [];
$line = 0;
$validCount = 0;
$invalidCount = 0;
$maxRows = 2000;

while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    $line++;
    if ($line == 1) {
        // Si la primera fila parece encabezado con 'identificador' o 'cedula', lo aceptamos y lo saltamos
        $first = strtolower(trim($data[0] ?? ''));
        if (strpos($first, 'ident') !== false || strpos($first, 'cedul') !== false) {
            continue;
        }
    }

    if ($line > $maxRows) break;

    $ident = trim($data[0] ?? '');
    $notaRaw = isset($data[1]) ? trim($data[1]) : '';

    $rowObj = [
        'line' => $line,
        'identificador' => $ident,
        'nota' => $notaRaw,
        'valido' => false,
        'mensaje' => '',
        'estudiante_id' => null,
        'nombre' => ''
    ];

    if ($ident === '') {
        $rowObj['mensaje'] = 'Identificador vacío';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    $key = preg_replace('/\s+/', '', strtolower($ident));
    if (isset($seccionMap[$key]) && $seccionMap[$key]['id']) {
        $rowObj['estudiante_id'] = $seccionMap[$key]['id'];
        $rowObj['nombre'] = $seccionMap[$key]['nombre'];
    } else {
        $rowObj['mensaje'] = 'Cédula/identificador no encontrado en la sección';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    // Validar nota (permitir entero o decimal) — rango 1..20
    if ($notaRaw === '') {
        $rowObj['mensaje'] = 'Nota vacía';
        $rowObj['valido'] = false;
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    // Reemplazar comas por punto para decimales
    $notaNormalized = str_replace(',', '.', $notaRaw);
    if (!is_numeric($notaNormalized)) {
        $rowObj['mensaje'] = 'Nota no numérica';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    $notaVal = (float)$notaNormalized;
    if ($notaVal < 1 || $notaVal > 20) {
        $rowObj['mensaje'] = 'Nota fuera de rango (1-20)';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    // Todo OK
    $rowObj['valido'] = true;
    $rowObj['mensaje'] = 'OK';
    $rowObj['nota'] = $notaVal;
    $validCount++;
    $rows[] = $rowObj;
}

fclose($handle);

echo json_encode([
    'previewRows' => $rows,
    'summary' => [
        'total' => count($rows),
        'validas' => $validCount,
        'invalidas' => $invalidCount
    ]
]);
exit();
