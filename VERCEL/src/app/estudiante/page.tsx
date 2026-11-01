'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import RoleNavbar from '@/components/RoleNavbar';

export default function EstudianteDashboardPage() {
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
          <div className="w-10 h-10 border-4 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Portal Estudiantil...</p>
        </div>
      </div>
    );
  }

  const estudianteTools = [
    {
      title: 'Mis Notas e Historial Académico',
      desc: 'Consulta de calificaciones por trimestre, créditos aprobados e índice',
      icon: '📊',
      badge: 'Calificaciones',
      color: 'border-b-4 border-amber-500',
    },
    {
      title: 'Mi Horario y Aulas de Clase',
      desc: 'Horario semanal por trayecto, turnos y ubicación física de aulas',
      icon: '🕒',
      badge: 'Horarios',
      color: 'border-b-4 border-orange-500',
    },
    {
      title: 'Declarar Pago de Aranceles',
      desc: 'Registro de comprobantes, transferencias, pagos móviles y constancias',
      icon: '💳',
      badge: 'Finanzas',
      color: 'border-b-4 border-emerald-500',
    },
    {
      title: 'Mis Constancias de Estudio',
      desc: 'Solicitud y descarga de constancia de estudio, notas certificadas y buena conducta',
      icon: '📄',
      badge: 'Documentos',
      color: 'border-b-4 border-blue-500',
    },
    {
      title: 'Mi Pensum y Plan de Estudios',
      desc: 'Malla curricular de la carrera, materias cursadas y prelaciones pendientes',
      icon: '🗺️',
      badge: 'Curricular',
      color: 'border-b-4 border-indigo-500',
    },
    {
      title: 'Prosecución y Reinscripción',
      desc: 'Planilla de prosecución académica para el próximo trayecto/semestre',
      icon: '🚀',
      badge: 'Inscripciones',
      color: 'border-b-4 border-teal-500',
    },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      <RoleNavbar
        user={user}
        roleTitle="Estudiante"
        roleBadgeColor="bg-amber-100 text-amber-900 border-amber-300"
      />

      <section className="bg-gradient-to-r from-amber-600 via-orange-700 to-slate-900 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-sm">
        <div className="max-w-7xl mx-auto">
          <div className="flex items-center gap-2 mb-2">
            <span className="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-white/20 border border-white/30">
              🎓 Comunidad Estudiantil
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Portal del Estudiante • UPTPC
          </h1>
          <p className="text-sm text-amber-100 mt-1">
            Estudiante <strong>{user?.nombre}</strong> (C.I: {user?.idusuario || user?.username}). Consulta y trámites académicos.
          </p>
        </div>
      </section>

      <main className="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1">
        <div className="mb-6">
          <h2 className="text-lg font-bold text-slate-900">Servicios Académicos Disponibles</h2>
          <p className="text-xs text-slate-500">Accede a tus trámites, constancias y seguimiento de tu avance curricular</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          {estudianteTools.map((tool, idx) => (
            <div
              key={idx}
              className={`bg-white rounded-xl p-5 shadow-xs border border-slate-200/90 ${tool.color} flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-150`}
            >
              <div>
                <div className="flex justify-between items-start mb-3">
                  <span className="text-3xl p-2 rounded-lg bg-slate-50 border border-slate-100">
                    {tool.icon}
                  </span>
                  <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-50 text-amber-800">
                    {tool.badge}
                  </span>
                </div>
                <h3 className="font-bold text-slate-900 text-base mb-1">{tool.title}</h3>
                <p className="text-xs text-slate-500 leading-relaxed mb-4">{tool.desc}</p>
              </div>

              <button
                onClick={() => alert(`Acceso a "${tool.title}" del estudiante.`)}
                className="w-full py-2 px-3 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-center cursor-pointer"
              >
                Abrir Trámite →
              </button>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
