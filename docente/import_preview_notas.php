<?php
require_once('../funciones/functions.php');

// Buffer para capturar cualquier salida extra (warnings/html) y devolver JSON válido
ob_start();
header('Content-Type: application/json; charset=utf-8');

function respondJson($data, $httpCode = 200) {
    $extra = trim(ob_get_clean());
    if ($extra !== '') {
        // Incluir salida adicional para debugging mínimo
        $data['server_output'] = substr($extra, 0, 4096);
    }
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

if (!isLoggedIn() || !isDocente()) {
    respondJson(['error' => 'Acceso denegado'], 403);
}

$docente_id = obtenerIdUsuario();
$seccion_id = $_POST['seccion_id'] ?? null;
$materia_id = $_POST['materia_id'] ?? null;
$trayecto_actual = isset($_POST['trayecto_actual']) ? (int)$_POST['trayecto_actual'] : 0;

if (!$seccion_id || !$materia_id) {
    respondJson(['error' => 'Parámetros faltantes'], 400);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    respondJson(['error' => 'No se recibió el archivo o hubo un error en la subida'], 400);
}

// Límite razonable
$maxBytes = 5 * 1024 * 1024; // 5 MB
if ($_FILES['file']['size'] > $maxBytes) {
    respondJson(['error' => 'Archivo demasiado grande (máx 5MB)'], 413);
}

$tmp = $_FILES['file']['tmp_name'];
$handle = fopen($tmp, 'r');
if (!$handle) {
    respondJson(['error' => 'No se pudo procesar el archivo'], 500);
}

// Obtener lista de estudiantes de la sección para validación rápida
$seccionMap = []; // cedula_normalizada => ['id'=>..., 'nombre'=>...]
$idMap = []; // id => ['id'=>..., 'nombre'=>...]
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
            $idVal = $r['id'] ?? $r['id_usuario'] ?? $r['usuario_id'] ?? null;
            $nombreVal = trim(($r['nombres'] ?? $r['nombre'] ?? $r['nombres_completos'] ?? ''));
            $seccionMap[$key] = [
                'id' => $idVal,
                'nombre' => $nombreVal
            ];
            if ($idVal) $idMap[(string)$idVal] = ['id' => $idVal, 'nombre' => $nombreVal];
        }
    } elseif (is_array($res)) {
        foreach ($res as $r) {
            $ced = '';
            if (isset($r['cedula'])) $ced = $r['cedula'];
            elseif (isset($r['identificacion'])) $ced = $r['identificacion'];
            elseif (isset($r['numero_cedula'])) $ced = $r['numero_cedula'];
            $key = preg_replace('/\s+/', '', strtolower($ced));
            $idVal = $r['id'] ?? $r['id_usuario'] ?? $r['usuario_id'] ?? null;
            $nombreVal = trim(($r['nombres'] ?? $r['nombre'] ?? $r['nombres_completos'] ?? ''));
            $seccionMap[$key] = [
                'id' => $idVal,
                'nombre' => $nombreVal
            ];
            if ($idVal) $idMap[(string)$idVal] = ['id' => $idVal, 'nombre' => $nombreVal];
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
        // Normalizar y eliminar BOM de cada celda antes de detectar header
        foreach ($data as $k => $v) {
            $cell = (string)$v;
            // quitar BOM y trim
            $cell = preg_replace('/^\xEF\xBB\xBF/', '', $cell);
            $data[$k] = trim($cell);
        }

        // Si la primera fila parece encabezado (contiene 'estudiante', 'id', 'ident', 'cedul' o 'nota'), la usamos para localizar la columna de nota
        $joined = strtolower(implode(',', array_map('trim', $data)));
        if (strpos($joined, 'estudiante') !== false || strpos($joined, 'estudiante_id') !== false || strpos($joined, 'id') !== false || strpos($joined, 'ident') !== false || strpos($joined, 'cedul') !== false || strpos($joined, 'nota') !== false || strpos($joined, 'nombres') !== false) {
            // Determinar índice de la columna "nota"
            $headers = array_map('strtolower', array_map('trim', $data));
            $notaIndex = array_search('nota', $headers);
            if ($notaIndex === false) $notaIndex = count($data) - 1; // última columna por defecto
            $headerDetected = true;
            // saltar la fila header
            continue;
        }
        // si no detectó header, continuar con la fila como datos (se normalizó ya)
    } else {
        // Normalizar celdas para filas no-header también
        foreach ($data as $k => $v) {
            $cell = (string)$v;
            $cell = preg_replace('/^\xEF\xBB\xBF/', '', $cell);
            $data[$k] = trim($cell);
        }
    }

    if ($line > $maxRows) break;

    // Si la fila está completamente vacía (ej. saltos de línea), la ignoramos
    $allEmpty = true;
    foreach ($data as $cell) {
        if (trim((string)$cell) !== '') { $allEmpty = false; break; }
    }
    if ($allEmpty) continue;

    // Normalizar celdas (trim ya aplicado). Detectar y omitir filas que parezcan una segunda cabecera
    $headerKeywords = ['estudiante', 'estudiante_id', 'estudianteid', 'id', 'identificador', 'ident', 'cedula', 'cedul', 'nota', 'nombres', 'nombre'];
    $isHeaderLike = false;
    foreach ($data as $cell) {
        $low = strtolower(trim((string)$cell));
        if ($low === '') continue;
        foreach ($headerKeywords as $kw) {
            if ($low === $kw || strpos($low, $kw) !== false) { $isHeaderLike = true; break 2; }
        }
    }
    if ($isHeaderLike) {
        // Omitir fila que parece ser cabecera (segunda línea o repetida)
        continue;
    }

    // Identificador esperado en la primera columna (estudiante_id) y nota en la última columna
    $ident = trim($data[0] ?? '');
    // Eliminar BOM si existe
    $ident = preg_replace('/^\xEF\xBB\xBF/', '', $ident);
    $notaRaw = isset($data[count($data) - 1]) ? trim($data[count($data) - 1]) : '';

    $rowObj = [
        'line' => $line,
        'identificador' => $ident,
        'nota' => $notaRaw,
        'valido' => false,
        'mensaje' => '',
        'estudiante_id' => null,
        'nombre' => '',
        'campo' => 'trayecto_' . intval($trayecto_actual)
    ];

    if ($ident === '') {
        $rowObj['mensaje'] = 'Identificador vacío';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }

    // Resolver estudiante: preferir estudiante_id numérico, si no intentar por cédula/identificador
    $resolved = false;
    if ($ident !== '' && is_numeric($ident)) {
        $idKey = (string)(int)$ident;
        if (isset($idMap[$idKey])) {
            $rowObj['estudiante_id'] = $idMap[$idKey]['id'];
            $rowObj['nombre'] = $idMap[$idKey]['nombre'];
            $resolved = true;
        }
    }
    if (!$resolved) {
        $key = preg_replace('/\s+/', '', strtolower($ident));
        if ($key !== '' && isset($seccionMap[$key]) && $seccionMap[$key]['id']) {
            $rowObj['estudiante_id'] = $seccionMap[$key]['id'];
            $rowObj['nombre'] = $seccionMap[$key]['nombre'];
            $resolved = true;
        }
    }
    if (!$resolved) {
        $rowObj['mensaje'] = 'Estudiante no encontrado en la sección';
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
    // Indicar qué campo de la tabla `notas_pendientes` se actualizaría
    $rowObj['campo'] = 'trayecto_' . intval($trayecto_actual);
    $validCount++;
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
