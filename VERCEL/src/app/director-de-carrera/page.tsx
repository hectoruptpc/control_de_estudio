'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import RoleNavbar from '@/components/RoleNavbar';

export default function DirectorCarreraDashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function checkAuth() {
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
    checkAuth();
  }, [router]);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="text-center">
          <div className="w-10 h-10 border-4 border-cyan-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Dirección de Carrera...</p>
        </div>
      </div>
    );
  }

  const directorTools = [
    {
      title: 'Asignación de Docentes a Cursos',
      desc: 'Designación de profesores titulares y suplentes por unidad curricular',
      icon: '👨‍🏫',
      badge: 'Carga Docente',
      color: 'border-b-4 border-cyan-500',
    },
    {
      title: 'Asignación y Apertura de Secciones',
      desc: 'Creación de nuevas secciones, control de capacidad y turnos de estudio',
      icon: '🏛️',
      badge: 'Planificación',
      color: 'border-b-4 border-blue-500',
    },
    {
      title: 'Asignación de Voceros Estudiantiles',
      desc: 'Registro oficial de los voceros por sección y trayecto formativo',
      icon: '📢',
      badge: 'Vocería',
      color: 'border-b-4 border-amber-500',
    },
    {
      title: 'Gestión de Horarios por Sección',
      desc: 'Distribución horaria por bloques y optimización de espacios áulicos',
      icon: '🕒',
      badge: 'Horarios',
      color: 'border-b-4 border-indigo-500',
    },
    {
      title: 'Seguimiento y Control de Notas',
      desc: 'Supervisión en tiempo real del porcentaje de notas cargadas por profesores',
      icon: '📈',
      badge: 'Supervisión',
      color: 'border-b-4 border-emerald-500',
    },
    {
      title: 'Mensajería con Coordinación General',
      desc: 'Canal oficial de solicitudes con Secretaría General y Administración',
      icon: '✉️',
      badge: 'Comunicaciones',
      color: 'border-b-4 border-purple-500',
    },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      <RoleNavbar
        user={user}
        roleTitle="Director de Carrera"
        roleBadgeColor="bg-cyan-100 text-cyan-900 border-cyan-300"
      />

      <section className="bg-gradient-to-r from-cyan-700 via-blue-800 to-slate-900 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-sm">
        <div className="max-w-7xl mx-auto">
          <div className="flex items-center gap-2 mb-2">
            <span className="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-white/20 border border-white/30">
              👔 Dirección de Programa Académico
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Dirección de Carrera • UPTPC
          </h1>
          <p className="text-sm text-cyan-100 mt-1">
            Director(a) <strong>{user?.nombre}</strong>. Gestión curricular, supervisión y asignación docente.
          </p>
        </div>
      </section>

      <main className="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1">
        <div className="mb-6">
          <h2 className="text-lg font-bold text-slate-900">Módulos de Coordinación de Carrera</h2>
          <p className="text-xs text-slate-500">Planifica la oferta académica y monitorea el cumplimiento de las metas curriculares</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          {directorTools.map((tool, idx) => (
            <div
              key={idx}
              className={`bg-white rounded-xl p-5 shadow-xs border border-slate-200/90 ${tool.color} flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-150`}
            >
              <div>
                <div className="flex justify-between items-start mb-3">
                  <span className="text-3xl p-2 rounded-lg bg-slate-50 border border-slate-100">
                    {tool.icon}
                  </span>
                  <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-cyan-50 text-cyan-800">
                    {tool.badge}
                  </span>
                </div>
                <h3 className="font-bold text-slate-900 text-base mb-1">{tool.title}</h3>
                <p className="text-xs text-slate-500 leading-relaxed mb-4">{tool.desc}</p>
              </div>

              <button
                onClick={() => alert(`Acceso al módulo "${tool.title}" de la Dirección de Carrera.`)}
                className="w-full py-2 px-3 text-xs font-bold text-cyan-800 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition text-center cursor-pointer"
              >
                Gestionar Módulo →
              </button>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
