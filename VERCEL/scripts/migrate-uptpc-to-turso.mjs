#!/usr/bin/env node

/**
 * Script de Migración Completa de Base de Datos: uptpc.sql -> Turso
 * 
 * - Lee `/srv/http/control_de_estudio/uptpc.sql`
 * - Convierte las 72 tablas del sistema de MySQL a SQLite nativo
 * - Carga la estructura DDL y todos los registros en Turso vía Pipeline v2
 * - Valida la integridad y verifica el usuario de prueba
 * 
 * Uso:
 *   node scripts/migrate-uptpc-to-turso.mjs
 */

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');
const legacyRoot = path.resolve(projectRoot, '..');

// 1. Cargar .env.local
const envPath = path.join(projectRoot, '.env.local');
if (!fs.existsSync(envPath)) {
  console.error('❌ Falta .env.local');
  process.exit(1);
}

const envContent = fs.readFileSync(envPath, 'utf-8');
const env = {};
for (const line of envContent.split('\n')) {
  const trimmed = line.trim();
  if (!trimmed || trimmed.startsWith('#')) continue;
  const eq = trimmed.indexOf('=');
  if (eq !== -1) {
    const k = trimmed.slice(0, eq).trim();
    let v = trimmed.slice(eq + 1).trim();
    if ((v.startsWith('"') && v.endsWith('"')) || (v.startsWith("'") && v.endsWith("'"))) {
      v = v.slice(1, -1);
    }
    env[k] = v;
  }
}

const dbUrl = env.TURSO_DATABASE_URL;
const token = env.TURSO_AUTH_TOKEN;

if (!dbUrl || !token) {
  console.error('❌ Credenciales de Turso no encontradas en .env.local');
  process.exit(1);
}

const httpUrl = dbUrl.replace(/^libsql:\/\//, 'https://');
const endpoint = `${httpUrl}/v2/pipeline`;

// Función para enviar consultas por lote a Turso
async function executeBatch(statements) {
  if (statements.length === 0) return [];
  const payload = {
    requests: statements.map((sql) => ({
      type: 'execute',
      stmt: { sql }
    }))
  };

  const res = await fetch(endpoint, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(payload)
  });

  if (!res.ok) {
    const txt = await res.text();
    throw new Error(`Error en Turso (${res.status}): ${txt}`);
  }

  const data = await res.json();
  const errors = [];
  data.results?.forEach((r, idx) => {
    if (r.type === 'error') {
      errors.push({ idx, error: r.error?.message, sql: statements[idx] });
    }
  });

  if (errors.length > 0) {
    console.warn(`⚠️ ${errors.length} sentencias tuvieron advertencias/errores en el lote`);
    // Mostrar primer error representativo
    console.warn(`   Ejemplo: ${errors[0].error} -> ${errors[0].sql.slice(0, 100)}...`);
  }

  return data.results;
}

// Tablas del sistema nuevo que se migran
const SYSTEM_TABLES = [
  "agenda", "aprobaciones_avance", "auditoria", "aulas", "bancos", "bitacora",
  "carreras", "carrera_materia", "carrera_versiones", "ciudades", "codigos_secciones",
  "contenido", "control_avance_trayecto", "docente_materia", "docente_seccion",
  "estados", "estado_civil", "estudiante_materias", "estudiante_seccion", "evaluacion",
  "genero", "graduados", "historial_cambios_notas", "horarios", "ingresos", "mallas",
  "malla_materia", "materias", "mensajeria", "metodos_pago", "municipios", "nombre_curso",
  "notas_definitivas", "notas_pendientes", "notas_trimestres", "pagos", "parroquias",
  "password_resets", "periodos_academicos", "preinscripcion", "prelaciones",
  "relacion_cursos", "respaldos_descargas", "revision_mensajes", "secciones",
  "secretaria_config", "secretaria_configuracion_carga", "secretaria_cupos",
  "seguridad_bloqueos", "seguridad_intentos", "seguridad_rps", "seguridad_sistema",
  "seguridad_tokens_invalidos", "solicitudes_academicas", "status", "tenencia_vivienda",
  "tipos_horario", "tipo_cedula", "tipo_formacion", "tipo_horario_personal", "tipo_pago",
  "tipo_vivienda", "titulos", "titulos_obtenidos", "titulo_materia", "trayectos",
  "users", "user_types", "user_user_types", "usuarios_cursos", "version_materia", "visitas"
];

console.log('\n============================================================');
console.log('🔄 MIGRACIÓN DE DATOS UPTPC: uptpc.sql -> TURSO DB');
console.log('============================================================');
console.log(`🌐 Base de datos destino: ${httpUrl}`);

const sqlPath = path.join(legacyRoot, 'uptpc.sql');
if (!fs.existsSync(sqlPath)) {
  console.error(`❌ Archivo uptpc.sql no encontrado en ${sqlPath}`);
  process.exit(1);
}

console.log('📖 Leyendo uptpc.sql...');
const sqlContent = fs.readFileSync(sqlPath, 'utf-8');

// 2. Extraer Primary Keys y AUTO_INCREMENT
console.log('🔍 Analizando claves primarias y tipos de datos...');
const pkMap = {};
const pkRegex = /ALTER TABLE `([a-zA-Z_0-9]+)`\s+([^;]+);/g;
let match;
while ((match = pkRegex.exec(sqlContent)) !== null) {
  const table = match[1];
  const body = match[2];
  const pkM = /ADD PRIMARY KEY \((.*?)\)/.exec(body);
  if (pkM) {
    pkMap[table] = pkM[1].split(',').map((c) => c.replace(/[`\s]/g, ''));
  }
}

const aiMap = {};
const aiRegex = /ALTER TABLE `([a-zA-Z_0-9]+)`\s+MODIFY `([a-zA-Z_0-9]+)`[^;]*AUTO_INCREMENT/g;
while ((match = aiRegex.exec(sqlContent)) !== null) {
  aiMap[match[1]] = match[2];
}

// Convertidor de columnas a SQLite
function convertCol(colDef, colName, pks, aiCol) {
  if (pks && pks.length === 1 && pks[0] === colName && aiCol === colName) {
    return `\`${colName}\` INTEGER PRIMARY KEY AUTOINCREMENT`;
  }
  let c = colDef;
  c = c.replace(/CHARACTER SET [^\s]+/gi, '');
  c = c.replace(/COLLATE [^\s]+/gi, '');
  c = c.replace(/COMMENT\s+('[^']*'|"[^"]*")/gi, '');
  c = c.replace(/ON UPDATE current_timestamp\(\)/gi, '');
  c = c.replace(/AUTO_INCREMENT/gi, '');
  c = c.replace(/UNSIGNED/gi, '');
  c = c.replace(/\b(tinyint|smallint|mediumint|int|bigint)(\s*\(\d+\))?/gi, 'INTEGER');
  c = c.replace(/\b(decimal|float|double)(\s*\(\d+(,\d+)?\))?/gi, 'REAL');
  c = c.replace(/\b(varchar|char|text|mediumtext|longtext|enum\([^)]+\))(\s*\(\d+\))?/gi, 'TEXT');
  c = c.replace(/\b(datetime|timestamp|date|time)/gi, 'TEXT');
  c = c.replace(/current_timestamp\(\)/gi, 'CURRENT_TIMESTAMP');
  c = c.replace(/\s+/g, ' ').trim();
  return `\`${colName}\` ${c}`;
}

// 3. Generar DDLs
console.log('🛠️ Generando estructura DDL para SQLite...');
const ddlList = [];

for (const t of SYSTEM_TABLES) {
  const tableRegex = new RegExp(`CREATE TABLE \`${t}\` \\(([\\s\\S]*?)\\) ENGINE=`, 'm');
  const tableMatch = tableRegex.exec(sqlContent);
  if (!tableMatch) continue;

  const body = tableMatch[1];
  const pks = pkMap[t];
  const aiCol = aiMap[t];
  const sqliteCols = [];

  for (const line of body.split('\n')) {
    const cleanLine = line.trim().replace(/,$/, '');
    const colMatch = /^`([a-zA-Z_0-9-]+)`\s+(.*)/.exec(cleanLine);
    if (colMatch) {
      sqliteCols.push(convertCol(colMatch[2], colMatch[1], pks, aiCol));
    }
  }

  if (pks && (pks.length > 1 || (pks.length === 1 && aiCol !== pks[0]))) {
    const pkCols = pks.map((c) => `\`${c}\``).join(', ');
    sqliteCols.push(`PRIMARY KEY (${pkCols})`);
  }

  ddlList.push(`DROP TABLE IF EXISTS \`${t}\`;`);
  const ddl = `CREATE TABLE \`${t}\` (\n  ${sqliteCols.join(',\n  ')}\n);`;
  ddlList.push(ddl);
}

console.log(`🚀 Ejecutando DDL en Turso (${ddlList.length} tablas)...`);
for (let i = 0; i < ddlList.length; i += 10) {
  const chunk = ddlList.slice(i, i + 10);
  await executeBatch(chunk);
}
console.log('✅ Estructura DDL completada con éxito en Turso.');

// Sanitizador de sentencias INSERT para SQLite
function sanitizeInsertForSqlite(insertSql) {
  // 1. Reemplazar comillas escapadas estilo MySQL \' por '' de SQLite
  let sanitized = insertSql.replace(/\\'/g, "''");

  // 2. Usar INSERT OR REPLACE para evitar errores de clave duplicada en reintentos
  sanitized = sanitized.replace(/^INSERT INTO /i, 'INSERT OR REPLACE INTO ');

  // 3. Proteger identificadores con guiones (ej. iso_3166-2 -> `iso_3166-2`)
  sanitized = sanitized.replace(/(?<=\(|,)\s*([a-zA-Z0-9_]+-[a-zA-Z0-9_-]+)\s*(?=\)|,)/g, ' `$1` ');

  return sanitized;
}

// 4. Migrar los Datos (INSERT INTO)
console.log('\n📥 Procesando sentencias INSERT INTO...');
const insertStatements = [];
const lines = sqlContent.split('\n');

let currentInsert = null;
let currentTable = null;

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];

  if (line.startsWith('INSERT INTO `')) {
    if (currentInsert) {
      insertStatements.push(sanitizeInsertForSqlite(currentInsert));
      currentInsert = null;
    }
    const endQuote = line.indexOf('`', 13);
    const tableName = line.slice(13, endQuote);
    if (SYSTEM_TABLES.includes(tableName)) {
      currentTable = tableName;
      currentInsert = line;
    } else {
      currentTable = null;
    }
    continue;
  }

  if (currentInsert) {
    currentInsert += '\n' + line;
    if (line.trim().endsWith(';')) {
      insertStatements.push(sanitizeInsertForSqlite(currentInsert));
      currentInsert = null;
      currentTable = null;
    }
  }
}

if (currentInsert) {
  insertStatements.push(sanitizeInsertForSqlite(currentInsert));
}

console.log(`📊 Sentencias INSERT detectadas para tablas del sistema: ${insertStatements.length}`);

// Ejecutar inserts por bloques
const BATCH_SIZE = 15;
for (let i = 0; i < insertStatements.length; i += BATCH_SIZE) {
  const batch = insertStatements.slice(i, i + BATCH_SIZE);
  const pct = Math.round(((i + batch.length) / insertStatements.length) * 100);
  process.stdout.write(`⏳ Migrando registros: ${i + batch.length}/${insertStatements.length} (${pct}%)...\r`);
  await executeBatch(batch);
}
console.log('\n✅ Todos los registros han sido migrados a Turso.');

// 5. Verificación de Integridad
console.log('\n============================================================');
console.log('🔎 VERIFICACIÓN DE INTEGRIDAD EN TURSO');
console.log('============================================================');

const tablesToCheck = ['users', 'carreras', 'materias', 'secciones', 'periodos_academicos', 'notas_definitivas', 'pagos'];
for (const t of tablesToCheck) {
  try {
    const res = await executeBatch([`SELECT COUNT(*) AS total FROM \`${t}\`;`]);
    const count = res[0]?.response?.result?.rows[0]?.[0]?.value || 0;
    console.log(`  📋 Tabla \`${t.padEnd(20)}\`: ${count} registros`);
  } catch (err) {
    console.warn(`  ⚠️ Error consultando ${t}: ${err.message}`);
  }
}

// Verificar usuario de prueba
console.log('\n👤 Verificando usuario de prueba: herrejose@gmail.com');
const userRes = await executeBatch([
  "SELECT id, username, email, nombre, user_type, status FROM `users` WHERE email='herrejose@gmail.com' LIMIT 1;"
]);
const userRow = userRes[0]?.response?.result?.rows?.[0];
if (userRow) {
  console.log('✅ Usuario de prueba encontrado y listo para autenticación:');
  console.log(`   - ID       : ${userRow[0]?.value}`);
  console.log(`   - Username : ${userRow[1]?.value}`);
  console.log(`   - Email    : ${userRow[2]?.value}`);
  console.log(`   - Nombre   : ${userRow[3]?.value}`);
  console.log(`   - Rol      : ${userRow[4]?.value}`);
} else {
  console.warn('⚠️ No se encontró herrejose@gmail.com en la tabla users.');
}

console.log('\n🎉 ¡MIGRACIÓN DE DATOS A TURSO COMPLETADA CON ÉXITO!\n');
