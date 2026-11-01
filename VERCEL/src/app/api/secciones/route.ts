import { NextResponse } from 'next/server';
import { client } from '@/db';

export async function GET() {
  try {
    const rs = await client.execute({
      sql: `
        SELECT s.id_seccion, s.codigo_seccion, s.turno, s.id_trayecto, s.capacidad_maxima,
               c.nombre_carrera, p.nombre_periodo
        FROM \`secciones\` s
        LEFT JOIN \`carreras\` c ON s.id_carrera = c.id_carrera
        LEFT JOIN \`periodos_academicos\` p ON s.id_periodo = p.id_periodo
        ORDER BY s.id_seccion DESC;
      `,
      args: [],
    });

    const secciones = rs.rows.map((r: any) => ({
      id: r.id_seccion,
      codigo: r.codigo_seccion,
      turno: r.turno || 'Diurno',
      trayecto: r.id_trayecto,
      capacidad: r.capacidad_maxima,
      carrera: r.nombre_carrera || 'General',
      periodo: r.nombre_periodo || 'Activo',
    }));

    return NextResponse.json({ ok: true, secciones });
  } catch (error: any) {
    console.error('Error al consultar secciones:', error);
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
