<?php
// test_rps_manual.php - Prueba RPS manual desde el navegador
require_once 'funciones/functions.php';
require_once 'funciones/seguridad.php';

$seguridad = new Seguridad($db);

echo "<h2>🧪 PRUEBA RPS MANUAL</h2>";

// Limpiar tabla
mysqli_query($db, "TRUNCATE TABLE seguridad_rps");
echo "✅ Tabla limpiada<br><br>";

echo "<form method='POST'>";
echo "<button type='submit' name='probar' value='1'>▶️ Ejecutar 20 peticiones</button>";
echo "</form>";

if(isset($_POST['probar'])) {
    echo "<br><strong>Enviando 20 peticiones...</strong><br><br>";
    
    $exitosas = 0;
    $bloqueadas = 0;
    
    for($i = 1; $i <= 20; $i++) {
        // Simular una petición a recuperar_password.php
        $resultado = $seguridad->verificarRPS('recuperar_password');
        
        if($resultado['permitido']) {
            echo "✅ Petición $i: PERMITIDA<br>";
            $exitosas++;
        } else {
            echo "🔴 Petición $i: BLOQUEADA - {$resultado['mensaje']}<br>";
            $bloqueadas++;
        }
        usleep(80000);
    }
    
    echo "<br><strong>Resultado:</strong> $exitosas exitosas | $bloqueadas bloqueadas<br>";
    
    // Verificar registros
    $sql = "SELECT COUNT(*) as total FROM seguridad_rps";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "<br><strong>Total registros en seguridad_rps:</strong> {$row['total']}<br>";
    
    if($row['total'] > 0) {
        echo "<p style='color:green;font-weight:bold;'>✅ RPS FUNCIONA - Se guardaron {$row['total']} registros</p>";
    } else {
        echo "<p style='color:red;font-weight:bold;'>❌ RPS NO FUNCIONA - No se guardaron registros</p>";
    }
}
?>