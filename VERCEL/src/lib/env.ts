/**
 * Acceso Tipado y Seguro a Variables de Entorno
 */

export const env = {
  TURSO_DATABASE_URL: process.env.TURSO_DATABASE_URL || '',
  TURSO_AUTH_TOKEN: process.env.TURSO_AUTH_TOKEN || '',

  R2_ACCOUNT_ID: process.env.R2_ACCOUNT_ID || '',
  R2_ACCESS_KEY_ID: process.env.R2_ACCESS_KEY_ID || '',
  R2_SECRET_ACCESS_KEY: process.env.R2_SECRET_ACCESS_KEY || '',
  R2_BUCKET_NAME: process.env.R2_BUCKET_NAME || 'control-estudios',
  R2_ENDPOINT: process.env.R2_ENDPOINT || '',
  R2_PUBLIC_URL: (process.env.R2_PUBLIC_URL || 'https://pub-78d31b91bd844251bac9de590ad73bc6.r2.dev').replace(/\/$/, ''),

  AUTH_SECRET: process.env.AUTH_SECRET || 'uptpc-secret-key-change-me',
  NEXT_PUBLIC_APP_URL: (process.env.NEXT_PUBLIC_APP_URL || 'http://localhost:3000').replace(/\/$/, ''),
};
