import { NextResponse } from 'next/server';
import { client } from '@/db';
import { getSession } from '@/lib/auth';

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session || (!session.roles.isAdmin && !session.roles.isSuperUser)) {
      return NextResponse.json({ ok: false, error: 'No autorizado' }, { status: 401 });
    }

    const body = await request.json();
    const { action } = body;

    // 1. Agregar Estudiante
    if (action === 'agregar_estudiante') {
      const { cedula, nombre, email, tlf, cel, carrera, genero, direccion } = body;
      if (!cedula || !nombre) {
        return NextResponse.json({ ok: false, error: 'Cédula y Nombre son obligatorios' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`users\` 
          (idusuario, username, nombre, email, tlf, cel, carrera, genero, direccion, user_type, estudiante, status, fecha_ingreso)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'estudiante', 1, 1, datetime('now'));
        `,
        args: [
          cedula,
          cedula.startsWith('V-') ? cedula : `V-${cedula}`,
          nombre,
          email || `${cedula.toLowerCase()}@correo.uptpc.edu.ve`,
          tlf || '',
          cel || '',
          carrera ? Number(carrera) : 1,
          genero || 'Masculino',
          direccion || '',
        ],
      });

      return NextResponse.json({ ok: true, message: 'Estudiante registrado exitosamente.' });
    }

    // 2. Agregar Carrera
    if (action === 'agregar_carrera') {
      const { cod_carrera, nombre_carrera, duracion_anios, titulo_obtenido } = body;
      if (!nombre_carrera) {
        return NextResponse.json({ ok: false, error: 'Nombre de la carrera es obligatorio' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`carreras\` (cod_carrera, nombre_carrera, duracion_anios, titulo_obtenido)
          VALUES (?, ?, ?, ?);
        `,
        args: [cod_carrera || 'PNF-GEN', nombre_carrera, duracion_anios || 4, titulo_obtenido || 'Licenciatura / Ingeniería'],
      });

      return NextResponse.json({ ok: true, message: 'Carrera registrada exitosamente.' });
    }

    // 3. Agregar Materia
    if (action === 'agregar_materia') {
      const { cod_materia, nombre_materia, creditos, id_trayecto } = body;
      if (!nombre_materia) {
        return NextResponse.json({ ok: false, error: 'Nombre de la materia es obligatorio' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`materias\` (codigo_materia, nombre_materia, creditos, id_trayecto)
          VALUES (?, ?, ?, ?);
        `,
        args: [cod_materia || 'MAT01', nombre_materia, creditos || 3, id_trayecto || 1],
      });

      return NextResponse.json({ ok: true, message: 'Materia registrada exitosamente.' });
    }

    // 4. Agregar Docente
    if (action === 'agregar_docente') {
      const { cedula, nombre, email, tlf, cel } = body;
      if (!cedula || !nombre) {
        return NextResponse.json({ ok: false, error: 'Cédula y Nombre son obligatorios' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`users\` 
          (idusuario, username, nombre, email, tlf, cel, user_type, docente, status, fecha_ingreso)
          VALUES (?, ?, ?, ?, ?, ?, 'docente', 1, 1, datetime('now'));
        `,
        args: [
          cedula,
          cedula.startsWith('V-') ? cedula : `V-${cedula}`,
          nombre,
          email || `${cedula.toLowerCase()}@docente.uptpc.edu.ve`,
          tlf || '',
          cel || '',
        ],
      });

      return NextResponse.json({ ok: true, message: 'Docente registrado exitosamente.' });
    }

    // 5. Inscribir Materias
    if (action === 'inscribir_materia') {
      const { estudiante_id, materia_id, seccion_id } = body;
      if (!estudiante_id || !materia_id) {
        return NextResponse.json({ ok: false, error: 'Faltan parámetros de inscripción' }, { status: 400 });
      }

      await client.execute({
        sql: `
          INSERT INTO \`estudiante_materias\` (id_estudiante, id_materia, id_seccion, fecha_inscripcion)
          VALUES (?, ?, ?, datetime('now'));
        `,
        args: [estudiante_id, materia_id, seccion_id || null],
      });

      return NextResponse.json({ ok: true, message: 'Materia inscrita exitosamente al estudiante.' });
    }

    // 6. Asignar Secciones a Docentes
    if (action === 'asignar_docente_seccion') {
      const { id_docente, id_seccion, id_materia } = body;
      await client.execute({
        sql: `
          INSERT INTO \`docente_seccion\` (id_docente, id_seccion, id_materia)
          VALUES (?, ?, ?);
        `,
        args: [id_docente, id_seccion, id_materia],
      });
      return NextResponse.json({ ok: true, message: 'Sección asignada al docente.' });
    }

    // 7. Modificar accesos de usuario (editar_accesos.php)
    if (action === 'editar_accesos') {
      const { usuario_id, admin, super_user, docente, estudiante, director } = body;
      await client.execute({
        sql: `
          UPDATE \`users\` 
          SET admin = ?, super_user = ?, docente = ?, estudiante = ?, usuario = ?
          WHERE id = ?;
        `,
        args: [
          admin ? 1 : 0,
          super_user ? 1 : 0,
          docente ? 1 : 0,
          estudiante ? 1 : 0,
          director ? 1 : 0,
          usuario_id,
        ],
      });
      return NextResponse.json({ ok: true, message: 'Accesos actualizados exitosamente.' });
    }

    return NextResponse.json({ ok: false, error: 'Acción académica no reconocida' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
