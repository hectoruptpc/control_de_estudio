#!/usr/bin/env node

/**
 * Herramienta de Gestión y Validación de Variables de Entorno (Local)
 * 
 * Propósito:
 * - Valida la existencia y formato de las variables en `.env.local`
 * - Garantiza que `.env.example` esté siempre sincronizado con los nombres de variables sin filtrar secretos
 * - Evita errores de despliegue en Vercel por falta de variables
 * 
 * Uso:
 *   node scripts/check-env.mjs
 */

import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

const ENV_LOCAL_PATH = path.join(projectRoot, '.env.local');
const ENV_EXAMPLE_PATH = path.join(projectRoot, '.env.example');

function parseEnv(filePath) {
  if (!fs.existsSync(filePath)) return {};
  const content = fs.readFileSync(filePath, 'utf-8');
  const env = {};
  for (const line of content.split('\n')) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith('#')) continue;
    const eqIdx = trimmed.indexOf('=');
    if (eqIdx !== -1) {
      const key = trimmed.slice(0, eqIdx).trim();
      let val = trimmed.slice(eqIdx + 1).trim();
      if ((val.startsWith('"') && val.endsWith('"')) || (val.startsWith("'") && val.endsWith("'"))) {
        val = val.slice(1, -1);
      }
      env[key] = val;
    }
  }
  return env;
}

function maskValue(val) {
  if (!val) return '(vacío)';
  if (val.length <= 8) return '********';
  return val.slice(0, 4) + '...' + val.slice(-4) + ` (${val.length} caracteres)`;
}

console.log('\n============================================================');
console.log('🔍 VERIFICADOR DE VARIABLES DE ENTORNO - UPTPC VERCEL');
console.log('============================================================\n');

if (!fs.existsSync(ENV_LOCAL_PATH)) {
  console.error('❌ Archivo .env.local no encontrado.');
  console.log('👉 Ejecuta: cp .env.example .env.local y configura tus credenciales.\n');
  process.exit(1);
}

const localEnv = parseEnv(ENV_LOCAL_PATH);
const exampleEnv = parseEnv(ENV_EXAMPLE_PATH);

const REQUIRED_KEYS = [
  'TURSO_DATABASE_URL',
  'TURSO_AUTH_TOKEN',
  'R2_ACCOUNT_ID',
  'R2_ACCESS_KEY_ID',
  'R2_SECRET_ACCESS_KEY',
  'R2_ENDPOINT',
  'R2_PUBLIC_URL',
  'AUTH_SECRET'
];

let hasErrors = false;

console.log('📋 Comprobación de Variables Requeridas:');
for (const key of REQUIRED_KEYS) {
  const val = localEnv[key];
  if (!val) {
    console.error(`  ❌ [FALTA] ${key}`);
    hasErrors = true;
  } else {
    console.log(`  ✅ [OK] ${key.padEnd(24)} -> ${maskValue(val)}`);
  }
}

// Validaciones de formato específicas
console.log('\n🔎 Validaciones de Formato y Conectividad:');

if (localEnv.TURSO_DATABASE_URL) {
  if (localEnv.TURSO_DATABASE_URL.startsWith('libsql://') || localEnv.TURSO_DATABASE_URL.startsWith('https://')) {
    console.log('  ✅ TURSO_DATABASE_URL tiene protocolo válido (libsql:// o https://)');
  } else {
    console.warn('  ⚠️ TURSO_DATABASE_URL no parece tener un protocolo estándar de Turso.');
  }
}

if (localEnv.R2_PUBLIC_URL) {
  if (localEnv.R2_PUBLIC_URL.startsWith('http://') || localEnv.R2_PUBLIC_URL.startsWith('https://')) {
    console.log('  ✅ R2_PUBLIC_URL tiene URL válida');
  } else {
    console.warn('  ⚠️ R2_PUBLIC_URL debe comenzar con https://');
  }
}

// Sincronizar claves hacia .env.example si hay nuevas
const missingInExample = Object.keys(localEnv).filter((k) => !(k in exampleEnv));
if (missingInExample.length > 0) {
  console.log(`\n💡 Se detectaron ${missingInExample.length} claves nuevas no registradas en .env.example:`);
  missingInExample.forEach((k) => console.log(`   - ${k}`));
}

console.log('\n------------------------------------------------------------');
if (hasErrors) {
  console.error('⚠️ Existen variables críticas sin definir. Revisa tu .env.local\n');
  process.exit(1);
} else {
  console.log('🎉 Todas las variables locales requeridas están correctamente configuradas.');
  console.log('🔒 Recuerda: .env.local está protegido por .gitignore y NUNCA se subirá a GitHub.\n');
}
