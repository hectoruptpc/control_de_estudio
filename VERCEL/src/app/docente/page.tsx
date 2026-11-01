'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import RoleNavbar from '@/components/RoleNavbar';

export default function DocenteDashboardPage() {
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
          <div className="w-10 h-10 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Portal Docente...</p>
        </div>
      </div>
    );
  }

  const docenteTools = [
    {
      title: 'Mis Secciones Asignadas',
      desc: 'Consulta de aulas, listas de estudiantes inscritos y turnos',
      icon: '👥',
      badge: 'Académico',
      color: 'border-b-4 border-emerald-500',
    },
    {
      title: 'Carga de Calificaciones',
      desc: 'Registro de notas por corte, evaluaciones acumuladas y definitivas',
      icon: '📝',
      badge: 'Evaluación',
      color: 'border-b-4 border-teal-500',
    },
    {
      title: 'Descargar Planilla CSV / Excel',
      desc: 'Generación de plantillas de notas para llenado fuera de línea',
      icon: '📥',
      badge: 'Formatos',
      color: 'border-b-4 border-blue-500',
    },
    {
      title: 'Importar Notas Masivas (CSV)',
      desc: 'Carga automatizada desde archivo CSV con previsualización',
      icon: '📤',
      badge: 'Carga Masiva',
      color: 'border-b-4 border-indigo-500',
    },
    {
      title: 'Mi Horario Semanal',
      desc: 'Visualización de carga horaria, días de clase y distribución de aulas',
      icon: '📅',
      badge: 'Planificación',
      color: 'border-b-4 border-amber-500',
    },
    {
      title: 'Mensajería & Notificaciones',
      desc: 'Comunicación oficial con directores de carrera y control de estudios',
      icon: '✉️',
      badge: 'Comunicaciones',
      color: 'border-b-4 border-rose-500',
    },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      <RoleNavbar
        user={user}
        roleTitle="Docente"
        roleBadgeColor="bg-emerald-100 text-emerald-900 border-emerald-300"
      />

      <section className="bg-gradient-to-r from-emerald-700 via-teal-800 to-slate-900 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-sm">
        <div className="max-w-7xl mx-auto">
          <div className="flex items-center gap-2 mb-2">
            <span className="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-white/20 border border-white/30">
              👨‍🏫 Cuerpo Docente
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Portal del Docente • UPTPC
          </h1>
          <p className="text-sm text-emerald-100 mt-1">
            Profesor(a) <strong>{user?.nombre}</strong>. Gestión de asignaturas, actas y notas académicas.
          </p>
        </div>
      </section>

      <main className="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1">
        <div className="mb-6">
          <h2 className="text-lg font-bold text-slate-900">Herramientas Académicas del Profesor</h2>
          <p className="text-xs text-slate-500">Administra tus cursos asignados durante el período académico activo</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          {docenteTools.map((tool, idx) => (
            <div
              key={idx}
              className={`bg-white rounded-xl p-5 shadow-xs border border-slate-200/90 ${tool.color} flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-150`}
            >
              <div>
                <div className="flex justify-between items-start mb-3">
                  <span className="text-3xl p-2 rounded-lg bg-slate-50 border border-slate-100">
                    {tool.icon}
                  </span>
                  <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-50 text-emerald-700">
                    {tool.badge}
                  </span>
                </div>
                <h3 className="font-bold text-slate-900 text-base mb-1">{tool.title}</h3>
                <p className="text-xs text-slate-500 leading-relaxed mb-4">{tool.desc}</p>
              </div>

              <button
                onClick={() => alert(`Módulo "${tool.title}" listo para vincular con las secciones asignadas del docente.`)}
                className="w-full py-2 px-3 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition text-center cursor-pointer"
              >
                Acceder a {tool.title} →
              </button>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
