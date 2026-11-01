import { NextResponse } from 'next/server';
import { client } from '@/db';
import { verifyPassword, createSessionToken, AuthUser } from '@/lib/auth';

export async function POST(request: Request) {
  try {
    const { identifier, password } = await request.json();

    if (!identifier || !password) {
      return NextResponse.json(
        { ok: false, error: 'Por favor ingrese su usuario o correo y su contraseña.' },
        { status: 400 }
      );
    }

    const cleanId = String(identifier).trim();

    // Buscar por username (ej: V-12345678) o por email o idusuario
    const rs = await client.execute({
      sql: `SELECT * FROM \`users\` WHERE (username = ? OR email = ? OR idusuario = ?) LIMIT 1;`,
      args: [cleanId, cleanId, cleanId],
    });

    if (rs.rows.length === 0) {
      return NextResponse.json(
        { ok: false, error: 'Usuario o contraseña incorrectos.' },
        { status: 401 }
      );
    }

    const row = rs.rows[0];
    const passwordMatch = await verifyPassword(password, String(row.password || ''));

    if (!passwordMatch) {
      return NextResponse.json(
        { ok: false, error: 'Usuario o contraseña incorrectos.' },
        { status: 401 }
      );
    }

    // Calcular perfiles disponibles exactamente igual que getAvailableProfiles() en PHP
    const availableProfiles: string[] = [];
    if (Number(row.admin) === 1) availableProfiles.push('admin');
    if (Number(row.super_user) === 1) availableProfiles.push('super_user');
    if (Number(row.docente) === 1) availableProfiles.push('docente');
    if (Number(row.estudiante) === 1) availableProfiles.push('estudiante');
    if (Number(row.usuario) === 1 || Boolean(row.carrera_di && Number(row.carrera_di) > 0)) {
      availableProfiles.push('director_de_carrera');
    }

    // Por defecto si no tiene ningún flag, usar user_type
    if (availableProfiles.length === 0) {
      const type = String(row.user_type || 'estudiante');
      availableProfiles.push(type);
    }

    const isAdmin = availableProfiles.includes('admin') || availableProfiles.includes('super_user');
    const isSuperUser = availableProfiles.includes('super_user');
    const isDocente = availableProfiles.includes('docente');
    const isEstudiante = availableProfiles.includes('estudiante');
    const isDirector = availableProfiles.includes('director_de_carrera');

    const defaultRole = availableProfiles[0];

    const authUser: AuthUser = {
      id: Number(row.id),
      idusuario: row.idusuario ? String(row.idusuario) : null,
      nombre: String(row.nombre || ''),
      username: String(row.username || ''),
      email: String(row.email || ''),
      user_type: String(row.user_type || 'user'),
      carrera: row.carrera ? Number(row.carrera) : null,
      foto_perfil: row.foto_perfil ? String(row.foto_perfil) : null,
      status: Number(row.status || 1),
      roles: {
        isAdmin,
        isSuperUser,
        isDocente,
        isEstudiante,
        isDirector,
      },
      activeRole: defaultRole,
    };

    const token = createSessionToken(authUser);

    // Si tiene más de un perfil, la ruta objetivo es /profile-selector; si no, su portal directo
    let targetUrl = '/profile-selector';
    if (availableProfiles.length === 1) {
      const single = availableProfiles[0];
      if (single === 'director_de_carrera') targetUrl = '/director-de-carrera';
      else if (single === 'super_user') targetUrl = '/super-user';
      else targetUrl = `/${single}`;
    }

    const response = NextResponse.json({
      ok: true,
      message: `Bienvenido/a ${authUser.nombre}`,
      user: authUser,
      availableProfiles,
      targetUrl,
    });

    // Fijar cookie HttpOnly segura
    response.cookies.set('uptpc_session', token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      path: '/',
      maxAge: 60 * 60 * 24 * 7, // 7 días
    });

    return response;
  } catch (error: any) {
    console.error('Error en API Login:', error);
    return NextResponse.json(
      { ok: false, error: 'Error interno de autenticación: ' + error.message },
      { status: 500 }
    );
  }
}
