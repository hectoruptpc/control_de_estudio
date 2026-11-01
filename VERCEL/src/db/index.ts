import { createClient } from '@libsql/client';
import { drizzle } from 'drizzle-orm/libsql';
import * as schema from './schema';
import { env } from '../lib/env';

/**
 * Conexión cliente con Turso (libSQL)
 */
export const client = createClient({
  url: env.TURSO_DATABASE_URL,
  authToken: env.TURSO_AUTH_TOKEN,
});

/**
 * Instancia de Drizzle ORM tipada con el esquema de Control de Estudios UPTPC
 */
export const db = drizzle(client, { schema });
