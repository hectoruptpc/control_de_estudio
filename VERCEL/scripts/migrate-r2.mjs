#!/usr/bin/env node

/**
 * Script de Migración y Sincronización de Archivos a Cloudflare R2
 * 
 * Sube automáticamente las imágenes y soportes locales a Cloudflare R2
 * estructurados en carpetas optimizadas:
 *   - /foto_perfil/
 *   - /soportes/
 *   - /uploads/
 *   - /images/
 * 
 * Uso:
 *   node scripts/migrate-r2.mjs [--dry-run] [--bucket=nombre-bucket]
 */

import fs from 'node:fs';
import path from 'node:path';
import crypto from 'node:crypto';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');
const legacyRoot = path.resolve(projectRoot, '..');

// 1. Cargar .env.local
const envPath = path.join(projectRoot, '.env.local');
if (!fs.existsSync(envPath)) {
  console.error('❌ Falta el archivo .env.local. Ejecuta: npm run check-env');
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

// Argumentos CLI
const args = process.argv.slice(2);
const isDryRun = args.includes('--dry-run');
const bucketArg = args.find((a) => a.startsWith('--bucket='));
const BUCKET_NAME = bucketArg ? bucketArg.split('=')[1] : (env.R2_BUCKET_NAME || 'control-estudios');

const ACCESS_KEY_ID = env.R2_ACCESS_KEY_ID;
const SECRET_ACCESS_KEY = env.R2_SECRET_ACCESS_KEY;
const ENDPOINT = env.R2_ENDPOINT;
const PUBLIC_URL = env.R2_PUBLIC_URL || 'https://pub-78d31b91bd844251bac9de590ad73bc6.r2.dev';

if (!ACCESS_KEY_ID || !SECRET_ACCESS_KEY || !ENDPOINT) {
  console.error('❌ Credenciales de R2 incompletas en .env.local');
  process.exit(1);
}

// Host y URL S3
const endpointUrl = new URL(ENDPOINT);
const host = endpointUrl.host;

// Extensiones mime
const MIME_TYPES = {
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.png': 'image/png',
  '.webp': 'image/webp',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.pdf': 'application/pdf',
  '.ico': 'image/x-icon',
  '.txt': 'text/plain'
};

function getMimeType(filePath) {
  const ext = path.extname(filePath).toLowerCase();
  return MIME_TYPES[ext] || 'application/octet-stream';
}

// AWS v4 Signer
function hmacSha256(key, string) {
  return crypto.createHmac('sha256', key).update(string).digest();
}

function sha256Hex(bufferOrString) {
  return crypto.createHash('sha256').update(bufferOrString).digest('hex');
}

function getSigningKey(secretKey, dateStamp, regionName, serviceName) {
  const kDate = hmacSha256('AWS4' + secretKey, dateStamp);
  const kRegion = hmacSha256(kDate, regionName);
  const kService = hmacSha256(kRegion, serviceName);
  return hmacSha256(kService, 'aws4_request');
}

async function uploadToR2(bucket, objectKey, fileBuffer, contentType) {
  const now = new Date();
  const amzDate = now.toISOString().replace(/[:-]|\.\d{3}/g, '');
  const dateStamp = amzDate.slice(0, 8);
  const region = 'auto';
  const service = 's3';

  const canonicalUri = `/${bucket}/${objectKey.split('/').map(encodeURIComponent).join('/')}`;
  const payloadHash = sha256Hex(fileBuffer);

  const canonicalHeaders = 
    `content-type:${contentType}\n` +
    `host:${host}\n` +
    `x-amz-content-sha256:${payloadHash}\n` +
    `x-amz-date:${amzDate}\n`;
  const signedHeaders = 'content-type;host;x-amz-content-sha256;x-amz-date';

  const canonicalRequest = 
    `PUT\n` +
    `${canonicalUri}\n` +
    `\n` +
    `${canonicalHeaders}\n` +
    `${signedHeaders}\n` +
    `${payloadHash}`;

  const algorithm = 'AWS4-HMAC-SHA256';
  const credentialScope = `${dateStamp}/${region}/${service}/aws4_request`;
  const stringToSign = 
    `${algorithm}\n` +
    `${amzDate}\n` +
    `${credentialScope}\n` +
    `${sha256Hex(canonicalRequest)}`;

  const signingKey = getSigningKey(SECRET_ACCESS_KEY, dateStamp, region, service);
  const signature = crypto.createHmac('sha256', signingKey).update(stringToSign).digest('hex');

  const authorizationHeader = 
    `${algorithm} ` +
    `Credential=${ACCESS_KEY_ID}/${credentialScope}, ` +
    `SignedHeaders=${signedHeaders}, ` +
    `Signature=${signature}`;

  const putUrl = `https://${host}${canonicalUri}`;

  const response = await fetch(putUrl, {
    method: 'PUT',
    headers: {
      'Host': host,
      'Content-Type': contentType,
      'x-amz-date': amzDate,
      'x-amz-content-sha256': payloadHash,
      'Authorization': authorizationHeader
    },
    body: fileBuffer
  });

  return {
    status: response.status,
    ok: response.ok,
    statusText: response.statusText,
    publicUrl: `${PUBLIC_URL}/${objectKey}`
  };
}

// 2. Definir directorios fuente
const SOURCE_FOLDERS = [
  { folder: 'foto_perfil', label: 'Fotos de Perfil' },
  { folder: 'soportes', label: 'Soportes y Comprobantes' },
  { folder: 'uploads', label: 'Cargas Administrativas' },
  { folder: 'images', label: 'Imágenes e Iconografía Institucional' }
];

console.log('\n============================================================');
console.log('🚀 MIGRACIÓN DE ARCHIVOS MULTIMEDIA A CLOUDFLARE R2');
console.log('============================================================');
console.log(`📦 Bucket Destino : ${BUCKET_NAME}`);
console.log(`🌐 Dominio Público: ${PUBLIC_URL}`);
console.log(`⚙️ Modo           : ${isDryRun ? 'DRY-RUN (Simulación sin transferir)' : 'PRODUCCIÓN (Subida activa)'}`);
console.log('============================================================\n');

let totalFiles = 0;
let totalBytes = 0;
const fileQueue = [];

for (const { folder, label } of SOURCE_FOLDERS) {
  const dirPath = path.join(legacyRoot, folder);
  if (!fs.existsSync(dirPath)) continue;

  const entries = fs.readdirSync(dirPath, { withFileTypes: true });
  for (const entry of entries) {
    if (entry.isDirectory()) continue;
    const filename = entry.name;
    // Ignorar archivos de sistema o de script PHP de protección
    if (filename.startsWith('.') || filename === 'index.php' || filename.endsWith('.sql')) continue;

    const fullPath = path.join(dirPath, filename);
    const stats = fs.statSync(fullPath);
    const objectKey = `${folder}/${filename}`;

    fileQueue.push({
      fullPath,
      folder,
      filename,
      objectKey,
      size: stats.size,
      mime: getMimeType(fullPath)
    });

    totalFiles++;
    totalBytes += stats.size;
  }
}

const totalMB = (totalBytes / (1024 * 1024)).toFixed(2);
console.log(`📁 Total archivos detectados: ${totalFiles} (${totalMB} MB)\n`);

let uploadedCount = 0;
let failedCount = 0;

for (let i = 0; i < fileQueue.length; i++) {
  const item = fileQueue[i];
  const progress = `[${i + 1}/${totalFiles}]`;
  const sizeKB = (item.size / 1024).toFixed(1);

  if (isDryRun) {
    console.log(`${progress} (DRY-RUN) -> ${item.objectKey} (${sizeKB} KB, ${item.mime})`);
    continue;
  }

  try {
    const fileBuffer = fs.readFileSync(item.fullPath);
    const result = await uploadToR2(BUCKET_NAME, item.objectKey, fileBuffer, item.mime);

    if (result.ok) {
      console.log(`✅ ${progress} ${item.objectKey} (${sizeKB} KB) -> ${result.publicUrl}`);
      uploadedCount++;
    } else {
      console.error(`❌ ${progress} ${item.objectKey} falló con status ${result.status}: ${result.statusText}`);
      failedCount++;
    }
  } catch (err) {
    console.error(`💥 ${progress} Error subiendo ${item.objectKey}:`, err.message);
    failedCount++;
  }
}

console.log('\n------------------------------------------------------------');
if (isDryRun) {
  console.log('🏁 Simulación completada sin subir archivos.');
} else {
  console.log(`🏁 Proceso completado: ${uploadedCount} exitosos, ${failedCount} fallidos.`);
  if (failedCount > 0) {
    console.log('💡 Sugerencia: Verifica que el BUCKET_NAME en .env.local coincida con el bucket de tu R2.');
  }
}
console.log('------------------------------------------------------------\n');
