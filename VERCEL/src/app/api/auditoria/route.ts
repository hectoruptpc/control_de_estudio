import { NextResponse } from 'next/server';
import { client } from '@/db';
import { getSession } from '@/lib/auth';

export async function GET(request: Request) {
  try {
    const session = await getSession();
    if (!session || (!session.roles.isAdmin && !session.roles.isSuperUser)) {
      return NextResponse.json({ ok: false, error: 'No autorizado' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const tipo = searchParams.get('tipo'); // 'visitas' o 'auditoria'

    if (tipo === 'visitas') {
      const rs = await client.execute(`
        SELECT v.*, u.nombre AS usuario_nombre, u.username
        FROM \`visitas\` v
        LEFT JOIN \`users\` u ON v.id_usuario = u.id
        ORDER BY v.id DESC LIMIT 100;
      `);
      return NextResponse.json({ ok: true, registros: rs.rows });
    }

    // Auditoría
    const rs = await client.execute(`
      SELECT a.*, u.nombre AS usuario_nombre, u.username
      FROM \`auditoria\` a
      LEFT JOIN \`users\` u ON a.usuario_id = u.id
      ORDER BY a.id DESC LIMIT 100;
    `);

    return NextResponse.json({ ok: true, registros: rs.rows });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
