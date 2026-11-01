import { NextResponse } from 'next/server';
import { client } from '@/db';
import { getSession } from '@/lib/auth';

export async function GET(request: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ ok: false, error: 'No autorizado' }, { status: 401 });
    }

    const { searchParams } = new URL(request.url);
    const tipo = searchParams.get('tipo'); // 'inbox', 'enviados'

    if (tipo === 'enviados') {
      const rs = await client.execute({
        sql: `
          SELECT m.*, u.nombre AS destinatario_nombre, u.email AS destinatario_email
          FROM \`mensajeria\` m
          LEFT JOIN \`users\` u ON m.id_usuario_destinatario = u.id
          WHERE m.id_usuario_remitente = ? AND m.eliminado_remitente = 0
          ORDER BY m.fecha_envio DESC LIMIT 50;
        `,
        args: [session.id],
      });
      return NextResponse.json({ ok: true, mensajes: rs.rows });
    }

    // Por defecto bandeja de entrada
    const rs = await client.execute({
      sql: `
        SELECT m.*, u.nombre AS remitente_nombre, u.email AS remitente_email
        FROM \`mensajeria\` m
        LEFT JOIN \`users\` u ON m.id_usuario_remitente = u.id
        WHERE m.id_usuario_destinatario = ? AND m.eliminado_destinatario = 0
        ORDER BY m.fecha_envio DESC LIMIT 50;
      `,
      args: [session.id],
    });

    return NextResponse.json({ ok: true, mensajes: rs.rows });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ ok: false, error: 'No autorizado' }, { status: 401 });
    }

    const body = await request.json();
    const { action } = body;

    // Enviar mensaje
    if (action === 'enviar') {
      const { id_destinatario, titulo, mensaje } = body;
      if (!id_destinatario || !titulo || !mensaje) {
        return NextResponse.json({ ok: false, error: 'Campos incompletos' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`mensajeria\`
          (id_usuario_remitente, id_usuario_destinatario, titulo, mensaje, fecha_envio, leido)
          VALUES (?, ?, ?, ?, datetime('now'), 0);
        `,
        args: [session.id, id_destinatario, titulo, mensaje],
      });

      return NextResponse.json({ ok: true, message: 'Mensaje enviado correctamente.' });
    }

    // Marcar como leído
    if (action === 'marcar_leido') {
      const { id_mensaje } = body;
      await client.execute({
        sql: `UPDATE \`mensajeria\` SET leido = 1 WHERE id = ? AND id_usuario_destinatario = ?;`,
        args: [id_mensaje, session.id],
      });
      return NextResponse.json({ ok: true });
    }

    return NextResponse.json({ ok: false, error: 'Acción no válida' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
