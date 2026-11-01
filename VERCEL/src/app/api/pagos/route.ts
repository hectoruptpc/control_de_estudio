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
    const estado = searchParams.get('estado'); // 'aprobado', 'pendiente', 'rechazado'

    let sql = `
      SELECT 
        p.id,
        p.estudiante_id,
        u.nombre AS estudiante_nombre,
        u.idusuario AS estudiante_cedula,
        c.nombre_carrera AS carrera,
        p.tipo_pago,
        p.otro_concepto,
        p.monto,
        p.metodo_pago,
        p.banco_origen,
        p.referencia,
        p.comprobante,
        p.status_pago,
        p.motivo_rechazo,
        p.fecha_pago,
        p.fecha_transaccion,
        p.observaciones
      FROM \`pagos\` p
      LEFT JOIN \`users\` u ON p.estudiante_id = u.id
      LEFT JOIN \`carreras\` c ON u.carrera = c.id_carrera
    `;

    const conditions: string[] = [];
    const args: any[] = [];

    if (estado && estado !== 'todos') {
      conditions.push('p.status_pago = ?');
      args.push(estado);
    }

    if (conditions.length > 0) {
      sql += ' WHERE ' + conditions.join(' AND ');
    }

    sql += ' ORDER BY p.id DESC LIMIT 100;';

    const rs = await client.execute({ sql, args });

    return NextResponse.json({
      ok: true,
      pagos: rs.rows,
    });
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

    // 1. Cambiar estado (Aprobar o Rechazar)
    if (action === 'cambiar_estado') {
      const { pago_id, nuevo_estado, motivo_rechazo } = body;
      if (!pago_id || !nuevo_estado) {
        return NextResponse.json({ ok: false, error: 'Faltan parámetros' }, { status: 400 });
      }

      await client.execute({
        sql: `UPDATE \`pagos\` SET status_pago = ?, motivo_rechazo = ? WHERE id = ?;`,
        args: [nuevo_estado, motivo_rechazo || null, pago_id],
      });

      return NextResponse.json({ ok: true, message: `Pago #${pago_id} actualizado a ${nuevo_estado}.` });
    }

    // 2. Registrar pago administrativo manual
    if (action === 'registrar') {
      const { estudiante_id, tipo_pago, monto, metodo_pago, banco_origen, referencia, observaciones } = body;
      if (!estudiante_id || !monto) {
        return NextResponse.json({ ok: false, error: 'Estudiante y monto son obligatorios' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`pagos\` 
          (estudiante_id, tipo_pago, monto, metodo_pago, banco_origen, referencia, observaciones, status_pago, registrado_por, fecha_pago) 
          VALUES (?, ?, ?, ?, ?, ?, ?, 'aprobado', ?, datetime('now'));
        `,
        args: [
          Number(estudiante_id),
          tipo_pago || 'Arancel General',
          Number(monto),
          metodo_pago || 'Transferencia',
          banco_origen || '',
          referencia || '',
          observaciones || '',
          session.id,
        ],
      });

      return NextResponse.json({ ok: true, message: 'Pago registrado exitosamente.' });
    }

    // 3. Eliminar pago
    if (action === 'eliminar') {
      const { pago_id } = body;
      await client.execute({
        sql: `DELETE FROM \`pagos\` WHERE id = ?;`,
        args: [pago_id],
      });
      return NextResponse.json({ ok: true, message: 'Pago eliminado.' });
    }

    return NextResponse.json({ ok: false, error: 'Acción no reconocida' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
