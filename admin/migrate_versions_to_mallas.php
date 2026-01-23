<?php
require_once __DIR__ . '/../funciones/functions.php';

session_start();
// Se puede añadir control de permisos aquí

header('Content-Type: text/html; charset=utf-8');
echo '<h2>Iniciando migración versiones → mallas</h2>';
echo '<pre>';
$res = migrarVersionesAMallas();
print_r($res);
echo '</pre>';
echo '<p>Listo. Revisa los mensajes y luego actualiza la UI para usar mallas.</p>';

?>
