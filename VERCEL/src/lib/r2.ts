import { S3Client, PutObjectCommand, DeleteObjectCommand } from '@aws-sdk/client-s3';
import { env } from './env';

export type R2Folder = 'foto_perfil' | 'soportes' | 'uploads' | 'images';

/**
 * Cliente S3 para interactuar con Cloudflare R2
 */
export const r2Client = new S3Client({
  region: 'auto',
  endpoint: env.R2_ENDPOINT,
  credentials: {
    accessKeyId: env.R2_ACCESS_KEY_ID,
    secretAccessKey: env.R2_SECRET_ACCESS_KEY,
  },
});

/**
 * Genera la URL pública de un archivo alojado en Cloudflare R2
 */
export function getR2PublicUrl(folder: R2Folder, filename?: string | null): string {
  if (!filename) {
    if (folder === 'foto_perfil') {
      return `${env.R2_PUBLIC_URL}/images/user_profile.png`;
    }
    return '';
  }

  // Si ya es una URL completa, devolverla tal cual
  if (filename.startsWith('http://') || filename.startsWith('https://')) {
    return filename;
  }

  // Limpiar barras iniciales
  const cleanFilename = filename.replace(/^\/+/, '');
  return `${env.R2_PUBLIC_URL}/${folder}/${cleanFilename}`;
}

/**
 * Sube un buffer o archivo a Cloudflare R2
 */
export async function uploadFileToR2(params: {
  folder: R2Folder;
  filename: string;
  buffer: Buffer | Uint8Array;
  contentType: string;
}) {
  const objectKey = `${params.folder}/${params.filename}`;

  const command = new PutObjectCommand({
    Bucket: env.R2_BUCKET_NAME,
    Key: objectKey,
    Body: params.buffer,
    ContentType: params.contentType,
  });

  await r2Client.send(command);

  return {
    key: objectKey,
    filename: params.filename,
    url: `${env.R2_PUBLIC_URL}/${objectKey}`,
  };
}

/**
 * Elimina un archivo de Cloudflare R2
 */
export async function deleteFileFromR2(folder: R2Folder, filename: string) {
  const objectKey = `${folder}/${filename.replace(/^\/+/, '')}`;
  const command = new DeleteObjectCommand({
    Bucket: env.R2_BUCKET_NAME,
    Key: objectKey,
  });
  return r2Client.send(command);
}
