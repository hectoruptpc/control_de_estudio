<?php
require_once(__DIR__ . '/../../funciones/functions.php');

cargarPermisosUsuario();
verificarPermiso('secciones');

$id_carrera = (int)$_POST['id_carrera'];
$id_trayecto = (int)$_POST['id_trayecto'];
$id_periodo = (int)$_POST['id_periodo'];

$secciones = obtenerSeccionesDisponiblesParaRezagados($id_carrera, $id_trayecto, $id_periodo);

if (empty($secciones)) {
    echo '<option value="">No hay secciones disponibles con cupo</option>';
} else {
    echo '<option value="">Seleccione una sección...</option>';
    foreach ($secciones as $s) {
        $disponibles = $s['capacidad_maxima'] - $s['inscritos'];
        echo "<option value='{$s['id_seccion']}'>{$s['codigo_seccion']} ({$disponibles} cupos disponibles)</option>";
    }
}
?>