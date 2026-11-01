import bcrypt from 'bcryptjs';
import crypto from 'node:crypto';
import { cookies } from 'next/headers';
import { env } from './env';

export interface AuthUser {
  id: number;
  idusuario: string | null;
  nombre: string;
  username: string;
  email: string;
  user_type: string;
  carrera: number | null;
  foto_perfil: string | null;
  status: number | null;
  roles: {
    isAdmin: boolean;
    isSuperUser: boolean;
    isDocente: boolean;
    isEstudiante: boolean;
    isDirector: boolean;
  };
  activeRole: string;
}

/**
 * Normaliza y verifica hashes bcrypt de PHP ($2y$) en Node.js ($2a$/$2b$)
 */
export async function verifyPassword(plainText: string, storedHash?: string | null): Promise<boolean> {
  if (!storedHash) return false;

  // Si es MD5 legacy de 32 caracteres hexadecimales
  if (/^[a-f0-9]{32}$/i.test(storedHash)) {
    const md5Hash = crypto.createHash('md5').update(plainText).digest('hex');
    if (md5Hash === storedHash.toLowerCase()) return true;
  }

  // Normalizar el prefijo $2y$ generado por password_hash() de PHP a $2a$ compatible con bcryptjs
  const normalizedHash = storedHash.replace(/^\$2y\$/, '$2a$');

  try {
    return await bcrypt.compare(plainText, normalizedHash);
  } catch (err) {
    console.error('Error al comparar hash bcrypt:', err);
    return false;
  }
}

/**
 * Genera un token de sesión firmado con HMAC SHA-256
 */
export function createSessionToken(user: AuthUser): string {
  const payload = JSON.stringify({
    ...user,
    exp: Date.now() + 1000 * 60 * 60 * 24 * 7, // 7 días de validez
  });

  const base64Payload = Buffer.from(payload).toString('base64url');
  const signature = crypto
    .createHmac('sha256', env.AUTH_SECRET)
    .update(base64Payload)
    .digest('base64url');

  return `${base64Payload}.${signature}`;
}

/**
 * Valida y extrae el usuario de un token de sesión
 */
export function verifySessionToken(token?: string | null): AuthUser | null {
  if (!token || !token.includes('.')) return null;

  const [base64Payload, signature] = token.split('.');
  if (!base64Payload || !signature) return null;

  const expectedSignature = crypto
    .createHmac('sha256', env.AUTH_SECRET)
    .update(base64Payload)
    .digest('base64url');

  if (signature !== expectedSignature) {
    return null;
  }

  try {
    const payloadStr = Buffer.from(base64Payload, 'base64url').toString('utf-8');
    const data = JSON.parse(payloadStr);

    if (data.exp && Date.now() > data.exp) {
      return null; // Sesión expirada
    }

    return data as AuthUser;
  } catch {
    return null;
  }
}

/**
 * Obtiene la sesión actual desde las cookies del navegador (Server Components / API)
 */
export async function getSession(): Promise<AuthUser | null> {
  const cookieStore = await cookies();
  const token = cookieStore.get('uptpc_session')?.value;
  return verifySessionToken(token);
}
