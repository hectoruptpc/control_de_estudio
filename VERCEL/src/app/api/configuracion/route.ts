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
    const tipo = searchParams.get('tipo');

    // 1. Tipos de Pago
    if (tipo === 'tipos_pago') {
      const rs = await client.execute(`SELECT * FROM \`tipo_pago\` ORDER BY id ASC;`);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 2. Tipos de Horario
    if (tipo === 'tipos_horario') {
      const rs = await client.execute(`SELECT * FROM \`tipos_horario\` ORDER BY id ASC;`);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 3. Períodos Académicos
    if (tipo === 'periodos') {
      const rs = await client.execute(`SELECT * FROM \`periodos_academicos\` ORDER BY id_periodo DESC;`);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 4. Materias
    if (tipo === 'materias') {
      const rs = await client.execute(`SELECT * FROM \`materias\` ORDER BY nombre_materia ASC;`);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 5. Prelaciones
    if (tipo === 'prelaciones') {
      const rs = await client.execute(`
        SELECT p.*, m1.nombre_materia AS materia_nombre, m2.nombre_materia AS prelacion_nombre
        FROM \`prelaciones\` p
        LEFT JOIN \`materias\` m1 ON p.id_materia = m1.id_materia
        LEFT JOIN \`materias\` m2 ON p.id_prelacion = m2.id_materia
        LIMIT 100;
      `);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 6. Docentes
    if (tipo === 'docentes') {
      const rs = await client.execute(`
        SELECT id, nombre, username, email, tlf, cel, status
        FROM \`users\`
        WHERE docente = 1 OR user_type = 'docente'
        ORDER BY nombre ASC;
      `);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 7. Directores de Carrera
    if (tipo === 'directores') {
      const rs = await client.execute(`
        SELECT u.id, u.nombre, u.email, u.idusuario, c.nombre_carrera, u.carrera_di
        FROM \`users\` u
        LEFT JOIN \`carreras\` c ON u.carrera_di = c.id_carrera
        WHERE u.usuario = 1 OR (u.carrera_di IS NOT NULL AND u.carrera_di > 0)
        ORDER BY u.nombre ASC;
      `);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 8. Títulos y Grados
    if (tipo === 'titulos') {
      const rs = await client.execute(`SELECT * FROM \`titulos\` ORDER BY id ASC;`);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    // 9. Solicitudes y Constancias
    if (tipo === 'solicitudes') {
      const rs = await client.execute(`
        SELECT s.*, u.nombre AS estudiante_nombre, u.idusuario AS estudiante_cedula, c.nombre_carrera
        FROM \`solicitudes_academicas\` s
        LEFT JOIN \`users\` u ON s.estudiante_id = u.id
        LEFT JOIN \`carreras\` c ON s.id_carrera = c.id_carrera
        ORDER BY s.id DESC LIMIT 100;
      `);
      return NextResponse.json({ ok: true, datos: rs.rows });
    }

    return NextResponse.json({ ok: false, error: 'Tipo de configuración no especificado' }, { status: 400 });
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
    const { tipo, action } = body;

    // Crear o modificar Tipo de Pago
    if (tipo === 'tipo_pago') {
      if (action === 'crear') {
        const { tipopago, precio } = body;
        await client.execute({
          sql: `INSERT INTO \`tipo_pago\` (tipopago, precio, status) VALUES (?, ?, 1);`,
          args: [tipopago, precio || 0],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de pago creado.' });
      }
    }

    // Crear o modificar Tipo de Horario
    if (tipo === 'tipo_horario') {
      if (action === 'crear') {
        const { nombre, horas_academicas, horas_atendiendo } = body;
        await client.execute({
          sql: `INSERT INTO \`tipos_horario\` (nombre, horas_academicas, horas_atendiendo) VALUES (?, ?, ?);`,
          args: [nombre, horas_academicas || 0, horas_atendiendo || 0],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de horario creado.' });
      }
    }

    // Crear Período Académico
    if (tipo === 'periodo') {
      if (action === 'crear') {
        const { nombre_periodo, fecha_inicio, fecha_fin, activo } = body;
        await client.execute({
          sql: `INSERT INTO \`periodos_academicos\` (nombre_periodo, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?);`,
          args: [nombre_periodo, fecha_inicio, fecha_fin, activo ? 1 : 0],
        });
        return NextResponse.json({ ok: true, message: 'Período académico creado.' });
      }
    }

    return NextResponse.json({ ok: false, error: 'Operación no válida' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
