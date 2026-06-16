<?php
// test_intento.php - Probar registro de intentos
require_once 'funciones/functions.php';
require_once 'funciones/seguridad.php';

$seguridad = new Seguridad($db);

echo "<h2>Probando registro de intentos</h2>";

// Registrar un intento de prueba
$resultado = $seguridad->registrarIntentoFallido('test@prueba.com', 'recuperar');

if($resultado) {
    echo "✅ Intento registrado correctamente<br>";
} else {
    echo "❌ Error al registrar intento<br>";
}

// Verificar si se registró
$sql = "SELECT * FROM seguridad_intentos ORDER BY id DESC LIMIT 5";
$result = mysqli_query($db, $sql);

echo "<h3>Últimos intentos registrados:</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>IP</th><th>Email</th><th>Tipo</th><th>Fecha</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['ip']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['tipo']}</td>";
    echo "<td>{$row['fecha']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<br><a href='recuperar_password.php'>Ir a recuperar password</a>";
?>