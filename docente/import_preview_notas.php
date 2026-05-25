<?php
require_once('../funciones/functions.php');

// Buffer para capturar cualquier salida extra
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

$docente_id = obtenerIdUsuario();
$seccion_id = $_POST['seccion_id'] ?? null;
$materia_id = $_POST['materia_id'] ?? null;

if (!$seccion_id || !$materia_id) {
    respondJson(['error' => 'Parámetros faltantes'], 400);
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    respondJson(['error' => 'No se recibió el archivo o hubo un error en la subida'], 400);
}

// Límite 5 MB
$maxBytes = 5 * 1024 * 1024;
if ($_FILES['file']['size'] > $maxBytes) {
    respondJson(['error' => 'Archivo demasiado grande (máx 5MB)'], 413);
}

$tmp = $_FILES['file']['tmp_name'];
$handle = fopen($tmp, 'r');
if (!$handle) {
    respondJson(['error' => 'No se pudo procesar el archivo'], 500);
}

// Obtener lista de estudiantes de la sección
$seccionMap = [];
$idMap = [];
if (function_exists('obtenerEstudiantesDeSeccion')) {
    global $db;
    $res = obtenerEstudiantesDeSeccion($db, $seccion_id);
    if (is_object($res)) {
        while ($r = $res->fetch_assoc()) {
            $ced = $r['idusuario'] ?? $r['cedula'] ?? $r['identificacion'] ?? $r['numero_cedula'] ?? '';
            $key = preg_replace('/\s+/', '', strtolower((string)$ced));
            $idVal = $r['id'] ?? $r['id_usuario'] ?? $r['usuario_id'] ?? null;
            $nombreVal = trim($r['nombre'] ?? $r['nombres'] ?? $r['nombres_completos'] ?? '');
            $seccionMap[$key] = ['id' => $idVal, 'nombre' => $nombreVal, 'idusuario' => $ced];
            if ($idVal) $idMap[(string)$idVal] = ['id' => $idVal, 'nombre' => $nombreVal, 'idusuario' => $ced];
        }
    }
}

$rows = [];
$line = 0;
$validCount = 0;
$invalidCount = 0;
$maxRows = 2000;

// Detectar columna de trimestre (puede ser T1, T2, T3 o similar)
$trimestre_map = ['trimestre_1' => null, 'trimestre_2' => null, 'trimestre_3' => null];

while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    $line++;
    
    // Normalizar celdas (eliminar BOM)
    foreach ($data as $k => $v) {
        $cell = (string)$v;
        $cell = preg_replace('/^\xEF\xBB\xBF/', '', $cell);
        $data[$k] = trim($cell);
    }
    
    // Detectar encabezados en la primera línea
    if ($line == 1) {
        $headers = array_map('strtolower', array_map('trim', $data));
        
        // Buscar columnas de trimestres
        foreach ($headers as $idx => $header) {
            if (strpos($header, 't1') !== false || strpos($header, 'trim1') !== false || strpos($header, 'trimestre1') !== false || $header === 'trimestre_1') {
                $trimestre_map['trimestre_1'] = $idx;
            } elseif (strpos($header, 't2') !== false || strpos($header, 'trim2') !== false || strpos($header, 'trimestre2') !== false || $header === 'trimestre_2') {
                $trimestre_map['trimestre_2'] = $idx;
            } elseif (strpos($header, 't3') !== false || strpos($header, 'trim3') !== false || strpos($header, 'trimestre3') !== false || $header === 'trimestre_3') {
                $trimestre_map['trimestre_3'] = $idx;
            }
        }
        
        // Buscar columna de identificación (cédula)
        $ident_index = null;
        foreach ($headers as $idx => $header) {
            if (strpos($header, 'cedula') !== false || strpos($header, 'ced') !== false || 
                strpos($header, 'ident') !== false || strpos($header, 'id') === 0) {
                $ident_index = $idx;
                break;
            }
        }
        
        if ($ident_index === null) $ident_index = 0;
        
        $headerDetected = true;
        continue;
    }
    
    // Saltar filas vacías
    $allEmpty = true;
    foreach ($data as $cell) {
        if (trim((string)$cell) !== '') { $allEmpty = false; break; }
    }
    if ($allEmpty) continue;
    
    // Obtener identificador del estudiante (primera columna o columna de identificación)
    $ident = trim($data[$ident_index] ?? $data[0] ?? '');
    
    // Crear objeto de fila
    $rowObj = [
        'line' => $line,
        'identificador' => $ident,
        'valido' => false,
        'mensaje' => '',
        'estudiante_id' => null,
        'nombre' => '',
        'notas' => [] // Array de notas por trimestre
    ];
    
    if ($ident === '') {
        $rowObj['mensaje'] = 'Identificador vacío';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }
    
    // Resolver estudiante
    $resolved = false;
    $key = preg_replace('/\s+/', '', strtolower($ident));
    if ($key !== '' && isset($seccionMap[$key]) && $seccionMap[$key]['id']) {
        $rowObj['estudiante_id'] = $seccionMap[$key]['id'];
        $rowObj['nombre'] = $seccionMap[$key]['nombre'];
        $resolved = true;
    } elseif (is_numeric($ident) && isset($idMap[(string)(int)$ident])) {
        $rowObj['estudiante_id'] = $idMap[(string)(int)$ident]['id'];
        $rowObj['nombre'] = $idMap[(string)(int)$ident]['nombre'];
        $resolved = true;
    }
    
    if (!$resolved) {
        $rowObj['mensaje'] = 'Estudiante no encontrado en la sección';
        $invalidCount++;
        $rows[] = $rowObj;
        continue;
    }
    
    // Procesar notas de trimestres
    $valid_trimestres = 0;
    $trimestres_notas = [];
    
    foreach ($trimestre_map as $trimestre => $col_idx) {
        if ($col_idx !== null && isset($data[$col_idx])) {
            $notaRaw = trim($data[$col_idx]);
            if ($notaRaw !== '') {
                $notaNormalized = str_replace(',', '.', $notaRaw);
                if (is_numeric($notaNormalized)) {
                    $notaVal = (float)$notaNormalized;
                    if ($notaVal >= 1 && $notaVal <= 20) {
                        $trimestres_notas[$trimestre] = $notaVal;
                        $valid_trimestres++;
                    } else {
                        $rowObj['mensaje'] .= "Nota $trimestre fuera de rango (1-20). ";
                    }
                } else {
                    $rowObj['mensaje'] .= "Nota $trimestre no numérica. ";
                }
            }
        }
    }
    
    if ($valid_trimestres > 0) {
        $rowObj['valido'] = true;
        $rowObj['mensaje'] = 'OK - ' . $valid_trimestres . ' trimestre(s) válido(s)';
        $rowObj['notas'] = $trimestres_notas;
        $validCount++;
    } else {
        $rowObj['mensaje'] = $rowObj['mensaje'] ?: 'No se encontraron notas válidas';
        $invalidCount++;
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