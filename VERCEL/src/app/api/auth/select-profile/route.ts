import { NextResponse } from 'next/server';
import { getSession, createSessionToken } from '@/lib/auth';

export async function POST(request: Request) {
  try {
    const session = await getSession();
    if (!session) {
      return NextResponse.json({ ok: false, error: 'No autenticado' }, { status: 401 });
    }

    const { profile } = await request.json();
    if (!profile) {
      return NextResponse.json({ ok: false, error: 'Perfil no especificado' }, { status: 400 });
    }

    const validProfiles = ['admin', 'super_user', 'docente', 'estudiante', 'director_de_carrera'];
    if (!validProfiles.includes(profile)) {
      return NextResponse.json({ ok: false, error: 'Perfil inválido' }, { status: 400 });
    }

    // Actualizar activeRole en la sesión
    const updatedUser = {
      ...session,
      activeRole: profile,
    };

    const newToken = createSessionToken(updatedUser);

    let targetUrl = `/${profile}`;
    if (profile === 'director_de_carrera') targetUrl = '/director-de-carrera';
    if (profile === 'super_user') targetUrl = '/super-user';

    const response = NextResponse.json({
      ok: true,
      message: `Perfil cambiado a ${profile}`,
      user: updatedUser,
      targetUrl,
    });

    response.cookies.set('uptpc_session', newToken, {
      httpOnly: true,
      secure: process.env.NODE_ENV === 'production',
      sameSite: 'lax',
      path: '/',
      maxAge: 60 * 60 * 24 * 7,
    });

    return response;
  } catch (error: any) {
    return NextResponse.json({ ok: false, error: error.message }, { status: 500 });
  }
}
