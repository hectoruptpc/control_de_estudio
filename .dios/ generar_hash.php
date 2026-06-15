<?php
// generar_hash.php - Ejecutar una vez y luego borrar
echo "Hash para tu contraseña: " . password_hash('TU_CONTRASEÑA_SECRETA', PASSWORD_DEFAULT);
?>