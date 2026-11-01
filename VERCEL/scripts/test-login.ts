import { client } from '../src/db/index';
import { verifyPassword } from '../src/lib/auth';

console.log('Probando verificación de credenciales para herrejose@gmail.com...');

const rs = await client.execute({
  sql: "SELECT id, username, email, password, user_type FROM `users` WHERE email = 'herrejose@gmail.com' OR username = 'V-12345678' LIMIT 1;",
  args: []
});

if (rs.rows.length === 0) {
  console.error('❌ Usuario no encontrado en Turso');
  process.exit(1);
}

const user = rs.rows[0];
console.log('Usuario obtenido:', {
  id: user.id,
  username: user.username,
  email: user.email,
  hash: String(user.password).slice(0, 15) + '...'
});

const isMatch = await verifyPassword('12345678', String(user.password));
console.log('¿Contraseña 12345678 correcta?:', isMatch ? '✅ SÍ (100% VÁLIDA)' : '❌ NO');

const isWrong = await verifyPassword('clave_incorrecta', String(user.password));
console.log('¿Contraseña errónea rechazada?:', !isWrong ? '✅ SÍ (CORRECTAMENTE RECHAZADA)' : '❌ FALLÓ');

if (isMatch && !isWrong) {
  console.log('\n🎉 ¡SISTEMA DE AUTENTICACIÓN COMPATIBLE 1:1 CON BCRYPT DE PHP!\n');
} else {
  process.exit(1);
}
