'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import { useRouter } from 'next/navigation';

interface ProfileOption {
  key: string;
  name: string;
  desc: string;
  icon: string;
  color: string;
  borderColor: string;
  targetUrl: string;
}

const ALL_PROFILES: Record<string, ProfileOption> = {
  admin: {
    key: 'admin',
    name: 'Administrador',
    desc: 'Gestión integral, estudiantes, pagos, notas, pensum y secretaría',
    icon: '🛡️',
    color: 'from-blue-600 to-indigo-700',
    borderColor: 'border-blue-500 hover:border-blue-600',
    targetUrl: '/admin',
  },
  super_user: {
    key: 'super_user',
    name: 'Super Usuario',
    desc: 'Acceso total de seguridad, auditorías, ajustes maestros y control',
    icon: '👑',
    color: 'from-purple-600 to-indigo-800',
    borderColor: 'border-purple-500 hover:border-purple-600',
    targetUrl: '/super-user',
  },
  docente: {
    key: 'docente',
    name: 'Docente',
    desc: 'Carga de notas, actas de calificación, horarios y secciones asignadas',
    icon: '👨‍🏫',
    color: 'from-emerald-600 to-teal-700',
    borderColor: 'border-emerald-500 hover:border-emerald-600',
    targetUrl: '/docente',
  },
  estudiante: {
    key: 'estudiante',
    name: 'Estudiante',
    desc: 'Consulta de notas, expediente, horarios, constancias y prosecución',
    icon: '🎓',
    color: 'from-amber-600 to-orange-700',
    borderColor: 'border-amber-500 hover:border-amber-600',
    targetUrl: '/estudiante',
  },
  director_de_carrera: {
    key: 'director_de_carrera',
    name: 'Director de Carrera',
    desc: 'Asignación de profesores, seguimiento de notas, cursos y secciones',
    icon: '👔',
    color: 'from-cyan-600 to-blue-700',
    borderColor: 'border-cyan-500 hover:border-cyan-600',
    targetUrl: '/director-de-carrera',
  },
};

export default function ProfileSelectorPage() {
  const router = useRouter();
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [selecting, setSelecting] = useState<string | null>(null);

  useEffect(() => {
    async function loadUser() {
      try {
        const res = await fetch('/api/auth/me');
        const data = await res.json();
        if (!res.ok || !data.ok) {
          router.push('/');
          return;
        }
        setUser(data.user);
        setLoading(false);
      } catch {
        router.push('/');
      }
    }
    loadUser();
  }, [router]);

  const handleSelect = async (profileKey: string, targetUrl: string) => {
    setSelecting(profileKey);
    try {
      const res = await fetch('/api/auth/select-profile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ profile: profileKey }),
      });

      const data = await res.json();
      if (data.ok) {
        router.push(data.targetUrl || targetUrl);
      } else {
        router.push(targetUrl);
      }
    } catch {
      router.push(targetUrl);
    }
  };

  const handleLogout = async () => {
    await fetch('/api/auth/logout', { method: 'POST' });
    router.push('/');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="text-center">
          <div className="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando perfiles disponibles...</p>
        </div>
      </div>
    );
  }

  // Filtrar perfiles según los roles reales del usuario
  const availableKeys: string[] = [];
  if (user?.roles?.isAdmin) availableKeys.push('admin');
  if (user?.roles?.isSuperUser) availableKeys.push('super_user');
  if (user?.roles?.isDocente) availableKeys.push('docente');
  if (user?.roles?.isEstudiante) availableKeys.push('estudiante');
  if (user?.roles?.isDirector) availableKeys.push('director_de_carrera');

  // Si por alguna razón está vacío, mostrar el activo
  if (availableKeys.length === 0 && user?.activeRole) {
    availableKeys.push(user.activeRole);
  }

  return (
    <main className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/40 to-slate-100 flex flex-col justify-between p-4 sm:p-6 lg:p-8">
      {/* Barra superior de navegación */}
      <div className="max-w-5xl mx-auto w-full flex items-center justify-between py-3 border-b border-slate-200/80">
        <div className="flex items-center gap-3">
          <Image
            src="/img/control_estudios.png"
            alt="Control de Estudios UPTPC"
            width={45}
            height={45}
            priority
            className="h-auto"
          />
          <div>
            <h2 className="font-extrabold text-blue-950 text-base leading-tight">
              Control de Estudios UPTPC
            </h2>
            <p className="text-xs text-slate-500 font-medium">
              Selector de Perfil de Acceso
            </p>
          </div>
        </div>

        <button
          onClick={handleLogout}
          className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition"
        >
          <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Salir del Sistema</span>
        </button>
      </div>

      {/* Contenido Central */}
      <div className="max-w-4xl mx-auto w-full my-auto py-8">
        <div className="text-center mb-8">
          <span className="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 uppercase tracking-wider mb-2">
            Sesión Activa: {user?.nombre || user?.username}
          </span>
          <h1 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Selecciona tu Perfil de Trabajo
          </h1>
          <p className="text-sm text-slate-600 mt-2 max-w-xl mx-auto">
            Tu cuenta posee múltiples roles asignados en la universidad. Escoge el área a la que deseas acceder en esta sesión:
          </p>
        </div>

        {/* Grid de Perfiles */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          {availableKeys.map((key) => {
            const profile = ALL_PROFILES[key] || {
              key,
              name: key.toUpperCase(),
              desc: 'Acceso a la plataforma institucional',
              icon: '👤',
              color: 'from-slate-600 to-slate-800',
              borderColor: 'border-slate-300',
              targetUrl: `/${key}`,
            };

            const isCurrent = user?.activeRole === key;
            const isBusy = selecting === key;

            return (
              <button
                key={key}
                onClick={() => handleSelect(key, profile.targetUrl)}
                disabled={selecting !== null}
                className={`relative group bg-white rounded-2xl p-6 text-left border-2 transition-all duration-200 shadow-sm hover:shadow-xl hover:-translate-y-1 cursor-pointer flex flex-col justify-between ${
                  isCurrent ? 'border-blue-600 ring-2 ring-blue-100 shadow-md' : 'border-slate-200/90 hover:border-blue-500'
                }`}
              >
                {isCurrent && (
                  <span className="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800 border border-blue-200">
                    Actual
                  </span>
                )}

                <div>
                  <div className="text-4xl mb-4 p-3 w-14 h-14 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:scale-110 transition">
                    {profile.icon}
                  </div>
                  <h3 className="text-lg font-bold text-slate-900 group-hover:text-blue-700 transition">
                    {profile.name}
                  </h3>
                  <p className="text-xs text-slate-500 mt-1 leading-relaxed">
                    {profile.desc}
                  </p>
                </div>

                <div className="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-blue-600 group-hover:text-blue-700">
                  <span>{isBusy ? 'Ingresando...' : 'Entrar al Panel'}</span>
                  <span className="group-hover:translate-x-1 transition">→</span>
                </div>
              </button>
            );
          })}
        </div>
      </div>

      {/* Footer */}
      <div className="max-w-5xl mx-auto w-full text-center py-4 border-t border-slate-200/80 text-xs text-slate-400">
        Control de Estudios UPTPC • Arquitectura Cloud Next.js + Turso DB + Cloudflare R2
      </div>
    </main>
  );
}
