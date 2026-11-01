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
    const tipo = searchParams.get('tipo'); // 'pendientes' o 'definitivas'
    const docente_id = searchParams.get('docente_id');

    if (tipo === 'pendientes') {
      let sql = `
        SELECT 
          np.id,
          np.id_usuario,
          u.nombre AS estudiante_nombre,
          u.idusuario AS estudiante_cedula,
          m.nombre_materia,
          m.codigo_materia,
          pa.nombre_periodo,
          d.nombre AS docente_nombre,
          np.trayecto_0, np.trayecto_1, np.trayecto_2, np.trayecto_3, np.trayecto_4,
          np.estado,
          np.soporte,
          np.fecha_envio
        FROM \`notas_pendientes\` np
        LEFT JOIN \`users\` u ON np.id_usuario = u.id
        LEFT JOIN \`materias\` m ON np.id_materia = m.id_materia
        LEFT JOIN \`periodos_academicos\` pa ON np.id_periodo = pa.id_periodo
        LEFT JOIN \`users\` d ON np.id_docente = d.id
      `;
      const args: any[] = [];
      if (docente_id) {
        sql += ' WHERE np.id_docente = ?';
        args.push(docente_id);
      }
      sql += ' ORDER BY np.id DESC LIMIT 100;';
      const rs = await client.execute({ sql, args });
      return NextResponse.json({ ok: true, notas: rs.rows });
    }

    // Por defecto definitivas
    let sql = `
      SELECT 
        nd.id,
        nd.id_usuario,
        u.nombre AS estudiante_nombre,
        u.idusuario AS estudiante_cedula,
        m.nombre_materia,
        m.codigo_materia,
        pa.nombre_periodo,
        d.nombre AS docente_nombre,
        nd.trayecto_0, nd.trayecto_1, nd.trayecto_2, nd.trayecto_3, nd.trayecto_4,
        nd.fecha_registro
      FROM \`notas_definitivas\` nd
      LEFT JOIN \`users\` u ON nd.id_usuario = u.id
      LEFT JOIN \`materias\` m ON nd.id_materia = m.id_materia
      LEFT JOIN \`periodos_academicos\` pa ON nd.id_periodo = pa.id_periodo
      LEFT JOIN \`users\` d ON nd.id_docente = d.id
      ORDER BY nd.id DESC LIMIT 100;
    `;
    const rs = await client.execute(sql);
    return NextResponse.json({ ok: true, notas: rs.rows });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session || (!session.roles.isAdmin && !session.roles.isSuperUser)) {
      return NextResponse.json({ ok: false, error: 'No autorizado' }, { status: 401 });
    }

    const body = await request.json();
    const { action } = body;

    // Aprobar nota pendiente y pasarla a notas_definitivas
    if (action === 'aprobar_pendiente') {
      const { nota_id } = body;
      const r = await client.execute({
        sql: `SELECT * FROM \`notas_pendientes\` WHERE id = ?;`,
        args: [nota_id],
      });
      if (r.rows.length === 0) {
        return NextResponse.json({ ok: false, error: 'Nota no encontrada' }, { status: 404 });
      }
      const n = r.rows[0];

      // Insertar en definitivas
      await client.execute({
        sql: `
          INSERT INTO \`notas_definitivas\`
          (id_usuario, id_materia, id_periodo, id_docente, trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, soporte, fecha_registro, id_admin_aprobador)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), ?);
        `,
        args: [
          n.id_usuario,
          n.id_materia,
          n.id_periodo,
          n.id_docente,
          n.trayecto_0,
          n.trayecto_1,
          n.trayecto_2,
          n.trayecto_3,
          n.trayecto_4,
          n.soporte,
          session.id,
        ],
      });

      // Actualizar estado a aprobada
      await client.execute({
        sql: `UPDATE \`notas_pendientes\` SET estado = 'aprobada' WHERE id = ?;`,
        args: [nota_id],
      });

      return NextResponse.json({ ok: true, message: 'Nota aprobada y registrada en definitivas.' });
    }

    // Corrección manual de nota definitiva
    if (action === 'corregir_definitiva') {
      const { id_nota, trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4 } = body;
      await client.execute({
        sql: `
          UPDATE \`notas_definitivas\` 
          SET trayecto_0 = ?, trayecto_1 = ?, trayecto_2 = ?, trayecto_3 = ?, trayecto_4 = ?
          WHERE id = ?;
        `,
        args: [trayecto_0, trayecto_1, trayecto_2, trayecto_3, trayecto_4, id_nota],
      });
      return NextResponse.json({ ok: true, message: 'Nota corregida exitosamente.' });
    }

    return NextResponse.json({ ok: false, error: 'Acción no reconocida' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
