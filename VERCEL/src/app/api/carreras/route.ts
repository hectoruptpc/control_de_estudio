import { NextResponse } from 'next/server';
import { client } from '@/db';

export async function GET() {
  try {
    const rs = await client.execute({
      sql: `SELECT id_carrera, nombre_carrera, cod_carrera, activa, duracion_semestres, titulo_otorga FROM \`carreras\` ORDER BY id_carrera ASC;`,
      args: [],
    });

    const carreras = rs.rows.map((r: any) => ({
      id: r.id_carrera,
      nombre: r.nombre_carrera,
      codigo: r.cod_carrera,
      activa: Number(r.activa) === 1,
      duracion: r.duracion_semestres,
      titulo: r.titulo_otorga,
    }));

    return NextResponse.json({ ok: true, carreras });
  } catch (error: any) {
    console.error('Error al consultar carreras:', error);
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
