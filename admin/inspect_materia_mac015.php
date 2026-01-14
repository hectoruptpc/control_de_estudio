<?php
require_once __DIR__ . '/../funciones/functions.php';
$code = 'MAC015';
$stmt = $db->prepare('SELECT id_materia,cod_materia,nombre_materia FROM materias WHERE cod_materia = ? LIMIT 1');
$stmt->bind_param('s', $code);
$stmt->execute();
$res = $stmt->get_result();
$r = $res ? $res->fetch_assoc() : null;
if (!$r) {
    echo "No encontrada\n";
    exit;
}
echo "ID: " . $r['id_materia'] . "\n";
echo "CODE: " . $r['cod_materia'] . "\n";
echo "NOMBRE: " . $r['nombre_materia'] . "\n";
echo "HEX: " . bin2hex($r['nombre_materia']) . "\n";
// mostrar bytes separados
$hex = bin2hex($r['nombre_materia']);
$parts = str_split($hex, 2);
echo "BYTES HEX: ";
foreach ($parts as $p) echo $p . ' ';
echo "\n";

// Mostrar con visibilidad de caracteres
function show_chars($s) {
    $out = '';
    $len = mb_strlen($s, 'UTF-8');
    for ($i = 0; $i < $len; $i++) {
        $ch = mb_substr($s, $i, 1, 'UTF-8');
        $out .= $i . ':' . $ch . ' '; 
    }
    return $out;
}

echo "CHARS: " . show_chars($r['nombre_materia']) . "\n";
?>