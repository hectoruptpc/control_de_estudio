<?php
require_once __DIR__ . '/../funciones/functions.php';
header('Content-Type: text/plain; charset=utf-8');

echo "Buscando materias con caracteres o tokens sospechosos...\n\n";

$q = $db->query("SELECT id_materia, cod_materia, nombre_materia FROM materias ORDER BY id_materia");
if (!$q) { echo "Error en consulta: " . $db->error . "\n"; exit; }

$found = 0;
while ($r = $q->fetch_assoc()) {
    $name = $r['nombre_materia'];
    $id = $r['id_materia'];
    $cod = $r['cod_materia'];

    $issues = [];
    // control chars
    if (preg_match('/[\x00-\x1F\x7F]/u', $name)) $issues[] = 'control-chars';
    // HTML-like
    if (preg_match('/<[^>]+>/u', $name)) $issues[] = 'html-tags';
    // long token without spaces (>=40)
    if (preg_match('/\S{40,}/u', $name)) $issues[] = 'long-token';
    // non-breaking space or &nbsp;
    if (strpos($name, "\xC2\xA0") !== false || stripos($name, '&nbsp;') !== false) $issues[] = 'nbsp';
    // suspicious invisible unicode (zero-width)
    if (preg_match('/[\x{200B}-\x{200F}\x{FEFF}]/u', $name)) $issues[] = 'zero-width';

    if (!empty($issues)) {
        $found++;
        echo "id={$id} cod={$cod}\n";
        echo "nombre: " . $name . "\n";
        echo "HEX: " . bin2hex($name) . "\n";
        echo "issues: " . implode(', ', $issues) . "\n";
        echo "----\n";
    }
}

if ($found === 0) echo "No se encontraron materias sospechosas.\n";
else echo "Encontradas: {$found}\n";

?>
