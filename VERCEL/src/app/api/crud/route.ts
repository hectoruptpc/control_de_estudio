import { NextResponse } from 'next/server';
import { client } from '@/db';
import { getSession } from '@/lib/auth';

/**
 * Endpoint centralizado para todas las mutaciones CRUD del Super Administrador:
 * - Crear (Insert)
 * - Leer/Detallar (Read)
 * - Actualizar (Update)
 * - Eliminar (Delete)
 */
export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session || (!session.roles.isAdmin && !session.roles.isSuperUser)) {
      return NextResponse.json({ ok: false, error: 'Acceso no autorizado' }, { status: 401 });
    }

    const body = await request.json();
    const { entity, action, data } = body;

    if (!entity || !action) {
      return NextResponse.json({ ok: false, error: 'Entidad y Acción requeridas' }, { status: 400 });
    }

    // ==========================================
    // 1. ESTUDIANTES (users WHERE estudiante = 1)
    // ==========================================
    if (entity === 'estudiante') {
      if (action === 'create') {
        const { idusuario, nombre, email, tlf, cel, carrera, genero, direccion } = data;
        const username = idusuario.startsWith('V-') || idusuario.startsWith('E-') ? idusuario : `V-${idusuario}`;
        await client.execute({
          sql: `
            INSERT INTO \`users\` (idusuario, username, nombre, email, tlf, cel, carrera, genero, direccion, user_type, estudiante, status, fecha_ingreso)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'estudiante', 1, 1, datetime('now'));
          `,
          args: [idusuario, username, nombre, email || `${idusuario}@uptpc.edu.ve`, tlf || '', cel || '', carrera ? Number(carrera) : 1, genero || 'M', direccion || ''],
        });
        return NextResponse.json({ ok: true, message: 'Estudiante creado exitosamente.' });
      }

      if (action === 'update') {
        const { id, idusuario, nombre, email, tlf, cel, carrera, status, direccion } = data;
        await client.execute({
          sql: `
            UPDATE \`users\` 
            SET idusuario = ?, nombre = ?, email = ?, tlf = ?, cel = ?, carrera = ?, status = ?, direccion = ?
            WHERE id = ?;
          `,
          args: [idusuario, nombre, email, tlf || '', cel || '', carrera ? Number(carrera) : null, Number(status), direccion || '', id],
        });
        return NextResponse.json({ ok: true, message: 'Estudiante actualizado exitosamente.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`users\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Estudiante eliminado exitosamente.' });
      }
    }

    // ==========================================
    // 2. DOCENTES (users WHERE docente = 1)
    // ==========================================
    if (entity === 'docente') {
      if (action === 'create') {
        const { idusuario, nombre, email, tlf, cel } = data;
        const username = idusuario.startsWith('V-') ? idusuario : `V-${idusuario}`;
        await client.execute({
          sql: `
            INSERT INTO \`users\` (idusuario, username, nombre, email, tlf, cel, user_type, docente, status, fecha_ingreso)
            VALUES (?, ?, ?, ?, ?, ?, 'docente', 1, 1, datetime('now'));
          `,
          args: [idusuario, username, nombre, email || `${idusuario}@docente.uptpc.edu.ve`, tlf || '', cel || ''],
        });
        return NextResponse.json({ ok: true, message: 'Docente registrado exitosamente.' });
      }

      if (action === 'update') {
        const { id, idusuario, nombre, email, tlf, cel, status } = data;
        await client.execute({
          sql: `
            UPDATE \`users\` 
            SET idusuario = ?, nombre = ?, email = ?, tlf = ?, cel = ?, status = ?
            WHERE id = ?;
          `,
          args: [idusuario, nombre, email, tlf || '', cel || '', Number(status), id],
        });
        return NextResponse.json({ ok: true, message: 'Docente actualizado exitosamente.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`users\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Docente eliminado.' });
      }
    }

    // ==========================================
    // 3. CARRERAS (carreras)
    // ==========================================
    if (entity === 'carrera') {
      if (action === 'create') {
        const { cod_carrera, nombre_carrera, duracion_anios, titulo_obtenido } = data;
        await client.execute({
          sql: `
            INSERT INTO \`carreras\` (cod_carrera, nombre_carrera, duracion_anios, titulo_obtenido, activa)
            VALUES (?, ?, ?, ?, 1);
          `,
          args: [cod_carrera, nombre_carrera, duracion_anios || 4, titulo_obtenido || 'Título Universitario'],
        });
        return NextResponse.json({ ok: true, message: 'Carrera creada exitosamente.' });
      }

      if (action === 'update') {
        const { id_carrera, cod_carrera, nombre_carrera, duracion_anios, titulo_obtenido, activa } = data;
        await client.execute({
          sql: `
            UPDATE \`carreras\` 
            SET cod_carrera = ?, nombre_carrera = ?, duracion_anios = ?, titulo_obtenido = ?, activa = ?
            WHERE id_carrera = ?;
          `,
          args: [cod_carrera, nombre_carrera, duracion_anios || 4, titulo_obtenido, activa ? 1 : 0, id_carrera],
        });
        return NextResponse.json({ ok: true, message: 'Carrera actualizada exitosamente.' });
      }

      if (action === 'delete') {
        const { id_carrera } = data;
        await client.execute({ sql: `DELETE FROM \`carreras\` WHERE id_carrera = ?;`, args: [id_carrera] });
        return NextResponse.json({ ok: true, message: 'Carrera eliminada.' });
      }
    }

    // ==========================================
    // 4. MATERIAS (materias)
    // ==========================================
    if (entity === 'materia') {
      if (action === 'create') {
        const { codigo_materia, nombre_materia, creditos, id_trayecto } = data;
        await client.execute({
          sql: `
            INSERT INTO \`materias\` (codigo_materia, nombre_materia, creditos, id_trayecto)
            VALUES (?, ?, ?, ?);
          `,
          args: [codigo_materia, nombre_materia, creditos || 3, id_trayecto || 1],
        });
        return NextResponse.json({ ok: true, message: 'Materia registrada exitosamente.' });
      }

      if (action === 'update') {
        const { id_materia, codigo_materia, nombre_materia, creditos, id_trayecto } = data;
        await client.execute({
          sql: `
            UPDATE \`materias\` 
            SET codigo_materia = ?, nombre_materia = ?, creditos = ?, id_trayecto = ?
            WHERE id_materia = ?;
          `,
          args: [codigo_materia, nombre_materia, creditos, id_trayecto, id_materia],
        });
        return NextResponse.json({ ok: true, message: 'Materia actualizada exitosamente.' });
      }

      if (action === 'delete') {
        const { id_materia } = data;
        await client.execute({ sql: `DELETE FROM \`materias\` WHERE id_materia = ?;`, args: [id_materia] });
        return NextResponse.json({ ok: true, message: 'Materia eliminada.' });
      }
    }

    // ==========================================
    // 5. SECCIONES (secciones)
    // ==========================================
    if (entity === 'seccion') {
      if (action === 'create') {
        const { codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno } = data;
        await client.execute({
          sql: `
            INSERT INTO \`secciones\` (codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno)
            VALUES (?, ?, ?, ?, ?, ?);
          `,
          args: [codigo_seccion, id_carrera, id_trayecto || 1, id_periodo || 1, capacidad_maxima || 35, turno || 'Diurno'],
        });
        return NextResponse.json({ ok: true, message: 'Sección creada exitosamente.' });
      }

      if (action === 'update') {
        const { id_seccion, codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno } = data;
        await client.execute({
          sql: `
            UPDATE \`secciones\`
            SET codigo_seccion = ?, id_carrera = ?, id_trayecto = ?, id_periodo = ?, capacidad_maxima = ?, turno = ?
            WHERE id_seccion = ?;
          `,
          args: [codigo_seccion, id_carrera, id_trayecto, id_periodo, capacidad_maxima, turno, id_seccion],
        });
        return NextResponse.json({ ok: true, message: 'Sección actualizada exitosamente.' });
      }

      if (action === 'delete') {
        const { id_seccion } = data;
        await client.execute({ sql: `DELETE FROM \`secciones\` WHERE id_seccion = ?;`, args: [id_seccion] });
        return NextResponse.json({ ok: true, message: 'Sección eliminada.' });
      }
    }

    // ==========================================
    // 6. TIPOS DE PAGO (tipo_pago)
    // ==========================================
    if (entity === 'tipo_pago') {
      if (action === 'create') {
        const { tipopago, precio } = data;
        await client.execute({
          sql: `INSERT INTO \`tipo_pago\` (tipopago, precio, status) VALUES (?, ?, 1);`,
          args: [tipopago, precio || 0],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de pago creado.' });
      }

      if (action === 'update') {
        const { id, tipopago, precio, status } = data;
        await client.execute({
          sql: `UPDATE \`tipo_pago\` SET tipopago = ?, precio = ?, status = ? WHERE id = ?;`,
          args: [tipopago, precio, status ? 1 : 0, id],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de pago modificado.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`tipo_pago\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Tipo de pago eliminado.' });
      }
    }

    // ==========================================
    // 7. TIPOS DE HORARIO (tipos_horario)
    // ==========================================
    if (entity === 'tipo_horario') {
      if (action === 'create') {
        const { nombre, horas_academicas, horas_atendiendo } = data;
        await client.execute({
          sql: `INSERT INTO \`tipos_horario\` (nombre, horas_academicas, horas_atendiendo) VALUES (?, ?, ?);`,
          args: [nombre, horas_academicas || 0, horas_atendiendo || 0],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de horario creado.' });
      }

      if (action === 'update') {
        const { id, nombre, horas_academicas, horas_atendiendo } = data;
        await client.execute({
          sql: `UPDATE \`tipos_horario\` SET nombre = ?, horas_academicas = ?, horas_atendiendo = ? WHERE id = ?;`,
          args: [nombre, horas_academicas, horas_atendiendo, id],
        });
        return NextResponse.json({ ok: true, message: 'Tipo de horario actualizado.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`tipos_horario\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Tipo de horario eliminado.' });
      }
    }

    // ==========================================
    // 8. PERIODOS ACADÉMICOS (periodos_academicos)
    // ==========================================
    if (entity === 'periodo') {
      if (action === 'create') {
        const { nombre_periodo, fecha_inicio, fecha_fin, activo } = data;
        await client.execute({
          sql: `INSERT INTO \`periodos_academicos\` (nombre_periodo, fecha_inicio, fecha_fin, activo) VALUES (?, ?, ?, ?);`,
          args: [nombre_periodo, fecha_inicio, fecha_fin, activo ? 1 : 0],
        });
        return NextResponse.json({ ok: true, message: 'Período académico creado.' });
      }

      if (action === 'update') {
        const { id_periodo, nombre_periodo, fecha_inicio, fecha_fin, activo } = data;
        await client.execute({
          sql: `UPDATE \`periodos_academicos\` SET nombre_periodo = ?, fecha_inicio = ?, fecha_fin = ?, activo = ? WHERE id_periodo = ?;`,
          args: [nombre_periodo, fecha_inicio, fecha_fin, activo ? 1 : 0, id_periodo],
        });
        return NextResponse.json({ ok: true, message: 'Período académico modificado.' });
      }

      if (action === 'delete') {
        const { id_periodo } = data;
        await client.execute({ sql: `DELETE FROM \`periodos_academicos\` WHERE id_periodo = ?;`, args: [id_periodo] });
        return NextResponse.json({ ok: true, message: 'Período académico eliminado.' });
      }
    }

    // ==========================================
    // 9. PRELACIONES (prelaciones)
    // ==========================================
    if (entity === 'prelacion') {
      if (action === 'create') {
        const { id_carrera, id_materia, id_prelacion } = data;
        await client.execute({
          sql: `INSERT INTO \`prelaciones\` (id_carrera, id_materia, id_prelacion) VALUES (?, ?, ?);`,
          args: [id_carrera, id_materia, id_prelacion],
        });
        return NextResponse.json({ ok: true, message: 'Prelación registrada.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`prelaciones\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Prelación eliminada.' });
      }
    }

    // ==========================================
    // 10. TÍTULOS Y GRADOS (titulos)
    // ==========================================
    if (entity === 'titulo') {
      if (action === 'create') {
        const { nombre, descripcion } = data;
        await client.execute({
          sql: `INSERT INTO \`titulos\` (nombre, descripcion) VALUES (?, ?);`,
          args: [nombre, descripcion || ''],
        });
        return NextResponse.json({ ok: true, message: 'Título registrado.' });
      }

      if (action === 'update') {
        const { id, nombre, descripcion } = data;
        await client.execute({
          sql: `UPDATE \`titulos\` SET nombre = ?, descripcion = ? WHERE id = ?;`,
          args: [nombre, descripcion, id],
        });
        return NextResponse.json({ ok: true, message: 'Título modificado.' });
      }

      if (action === 'delete') {
        const { id } = data;
        await client.execute({ sql: `DELETE FROM \`titulos\` WHERE id = ?;`, args: [id] });
        return NextResponse.json({ ok: true, message: 'Título eliminado.' });
      }
    }

    return NextResponse.json({ ok: false, error: 'Operación CRUD no soportada' }, { status: 400 });
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
