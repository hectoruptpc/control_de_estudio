<?php
require_once __DIR__ . '/../funciones/functions.php';

// Script para asegurar que cada carrera tenga al menos una malla.
header('Content-Type: text/plain; charset=utf-8');

$carreras = obtenerListaCompletaCarreras(false);
$summary = ['checked' => 0, 'created_mallas' => 0, 'created_assignments' => 0, 'errors' => []];

foreach ($carreras as $c) {
    $summary['checked']++;
    $id_c = intval($c['id_carrera'] ?? $c['id'] ?? $c['id_carrera']);
    if (!$id_c) continue;

    $mallas = obtenerMallasPorCarrera($id_c);
    if (!empty($mallas)) {
        echo "Carrera {$id_c} ya tiene mallas, saltando.\n";
        continue;
    }

    // obtener año desde created_at de la carrera
    $stmt = $db->prepare("SELECT created_at, cod_carrera FROM carreras WHERE id_carrera = ? LIMIT 1");
    $stmt->bind_param('i', $id_c);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    $anio = null;
    if (!empty($row['created_at'])) $anio = intval(date('Y', strtotime($row['created_at'])));
    if (empty($anio)) $anio = intval(date('Y'));

    // generar y crear malla
    $codigo_sugerido = generarCodigoMallaNumerico($row['cod_carrera'] ?? '', $anio, $id_c);
    $resCrear = crearMalla($id_c, $anio, $codigo_sugerido, 'Malla generada automáticamente');
    if (empty($resCrear) || empty($resCrear['success'])) {
        $summary['errors'][] = "Error creando malla para carrera {$id_c}: " . ($resCrear['message'] ?? 'desconocido');
        echo "ERROR crear malla para {$id_c}: " . ($resCrear['message'] ?? 'unknown') . "\n";
        continue;
    }

    $id_malla = intval($resCrear['id_malla']);
    $summary['created_mallas']++;
    echo "Malla creada id {$id_malla} para carrera {$id_c} (codigo: {$codigo_sugerido})\n";

    // copiar asignaciones base carrera_materia -> malla_materia
    $q = $db->prepare("SELECT id_materia, semestre FROM carrera_materia WHERE id_carrera = ?");
    $q->bind_param('i', $id_c);
    if ($q->execute()) {
        $res2 = $q->get_result();
        while ($r = $res2->fetch_assoc()) {
            $as = asignarMateriaAMalla($id_malla, intval($r['id_materia']), intval($r['semestre']));
            if (!empty($as['success'])) {
                $summary['created_assignments']++;
            }
        }
        $res2->free();
    }
    $q->close();
}

echo "\nResumen:\n";
echo "Carreras comprobadas: " . $summary['checked'] . "\n";
echo "Mallas creadas: " . $summary['created_mallas'] . "\n";
echo "Asignaciones copiadas: " . $summary['created_assignments'] . "\n";
if (!empty($summary['errors'])) {
    echo "Errores:\n" . implode("\n", $summary['errors']) . "\n";
}

?>
