import { NextResponse } from 'next/server';
import { client } from '@/db';
import { getR2PublicUrl } from '@/lib/r2';

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const query = searchParams.get('q')?.trim() || '';

    let sql = `
      SELECT u.id, u.idusuario, u.nombre, u.username, u.email, u.tlf, u.cel, 
             u.carrera, c.nombre_carrera, u.status, u.foto_perfil, u.fecha_ingreso
      FROM \`users\` u
      LEFT JOIN \`carreras\` c ON u.carrera = c.id_carrera
      WHERE (u.user_type = 'estudiante' OR u.estudiante = 1)
    `;

    const args: any[] = [];
    if (query) {
      sql += ` AND (u.nombre LIKE ? OR u.username LIKE ? OR u.idusuario LIKE ? OR u.email LIKE ?)`;
      const likeQuery = `%${query}%`;
      args.push(likeQuery, likeQuery, likeQuery, likeQuery);
    }

    sql += ` ORDER BY u.id DESC LIMIT 100;`;

    const rs = await client.execute({ sql, args });

    const estudiantes = rs.rows.map((r: any) => ({
      id: r.id,
      cedula: r.idusuario || r.username,
      nombre: r.nombre,
      username: r.username,
      email: r.email,
      telefono: r.tlf || r.cel || 'No registrado',
      carreraId: r.carrera,
      carreraNombre: r.nombre_carrera || 'No asignada',
      status: Number(r.status) === 1 ? 'Activo' : 'Inactivo',
      fotoUrl: getR2PublicUrl('foto_perfil', r.foto_perfil as string),
      fechaIngreso: r.fecha_ingreso,
    }));

    return NextResponse.json({
      ok: true,
      total: estudiantes.length,
      estudiantes,
    });
  } catch (error: any) {
    console.error('Error obteniendo estudiantes:', error);
    return NextResponse.json(
      { ok: false, error: 'Error al consultar estudiantes: ' + error.message },
      { status: 500 }
    );
  }
}
