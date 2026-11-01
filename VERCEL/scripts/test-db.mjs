#!/usr/bin/env node

/**
 * Script de Verificación de Conectividad con Turso DB
 * Uso:
 *   node scripts/test-db.mjs
 */

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

const envPath = path.join(projectRoot, '.env.local');
if (!fs.existsSync(envPath)) {
  console.error('❌ Falta .env.local');
  process.exit(1);
}

// Parse simple .env.local
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
  console.error('❌ Credenciales de TURSO no encontradas en .env.local');
  process.exit(1);
}

// Transform libsql:// to https://
const httpUrl = dbUrl.replace(/^libsql:\/\//, 'https://');
const endpoint = `${httpUrl}/v2/pipeline`;

console.log(`🌐 Probando conexión a Turso (${httpUrl})...`);

const start = performance.now();

try {
  const res = await fetch(endpoint, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      requests: [
        {
          type: 'execute',
          stmt: { sql: "SELECT sqlite_version() AS version, 1 + 1 AS calculo, datetime('now') AS hora_utc;" }
        },
        {
          type: 'execute',
          stmt: { sql: "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;" }
        }
      ]
    })
  });

  const duration = (performance.now() - start).toFixed(2);

  if (!res.ok) {
    const text = await res.text();
    console.error(`❌ Error en respuesta de Turso (Status ${res.status}):`, text);
    process.exit(1);
  }

  const data = await res.json();
  const testQuery = data.results[0]?.response?.result;
  const tablesQuery = data.results[1]?.response?.result;

  console.log(`✅ Conexión exitosa a Turso en ${duration} ms!`);
  if (testQuery && testQuery.rows.length > 0) {
    const [row] = testQuery.rows;
    console.log(`   - SQLite Versión : ${row[0]?.value}`);
    console.log(`   - Test Aritmético: ${row[1]?.value}`);
    console.log(`   - Hora UTC       : ${row[2]?.value}`);
  }

  const tableList = tablesQuery?.rows?.map((r) => r[0]?.value) || [];
  console.log(`\n📊 Tablas encontradas en la BD (${tableList.length}):`);
  if (tableList.length === 0) {
    console.log('   (Base de datos limpia, lista para migración inicial)');
  } else {
    tableList.forEach((t) => console.log(`   - ${t}`));
  }
  console.log('\n🚀 Turso está listo para ser utilizado por Drizzle ORM en Next.js.\n');
} catch (err) {
  console.error('❌ Excepción al conectar con Turso:', err.message);
  process.exit(1);
}
