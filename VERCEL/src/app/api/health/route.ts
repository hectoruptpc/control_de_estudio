import { NextResponse } from 'next/server';
import { client } from '@/db';
import { env } from '@/lib/env';

export async function GET() {
  const start = performance.now();
  let dbStatus = 'error';
  let dbLatency = 0;
  let errorMsg = null;

  try {
    const rs = await client.execute('SELECT 1 AS ok, datetime("now") AS server_time;');
    dbLatency = Math.round(performance.now() - start);
    if (rs.rows.length > 0) {
      dbStatus = 'connected';
    }
  } catch (err: any) {
    errorMsg = err.message;
  }

  return NextResponse.json({
    status: dbStatus === 'connected' ? 'healthy' : 'degraded',
    system: 'Control de Estudios UPTPC',
    environment: process.env.NODE_ENV || 'development',
    storage: {
      provider: 'Cloudflare R2',
      publicDomain: env.R2_PUBLIC_URL,
    },
    database: {
      provider: 'Turso (libSQL)',
      status: dbStatus,
      latencyMs: dbLatency,
      error: errorMsg,
    },
    timestamp: new Date().toISOString(),
  });
}
