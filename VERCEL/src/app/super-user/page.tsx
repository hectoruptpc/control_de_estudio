'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import RoleNavbar from '@/components/RoleNavbar';

export default function SuperUserDashboardPage() {
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
          <div className="w-10 h-10 border-4 border-purple-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Panel de Super Usuario...</p>
        </div>
      </div>
    );
  }

  const superTools = [
    {
      title: 'Control Maestro de Accesos',
      desc: 'Edición de privilegios granulares, permisos especiales y roles',
      icon: '🔐',
      badge: 'Seguridad',
      color: 'border-b-4 border-purple-600',
    },
    {
      title: 'Auditoría Forense y Logs',
      desc: 'Trazabilidad de modificaciones de notas, IPs y actividad de usuarios',
      icon: '📜',
      badge: 'Auditoría',
      color: 'border-b-4 border-indigo-600',
    },
    {
      title: 'Ajustes de Sistema y Variables',
      desc: 'Configuración general de parámetros, tipos de pago y horarios',
      icon: '⚙️',
      badge: 'Parámetros',
      color: 'border-b-4 border-slate-600',
    },
    {
      title: 'Sincronización & Respaldos',
      desc: 'Copias de seguridad automáticas y dumps en la nube',
      icon: '💾',
      badge: 'Data Backup',
      color: 'border-b-4 border-blue-600',
    },
    {
      title: 'Gestión de Docentes & Directores',
      desc: 'Designación de directores de carrera y control de amonestaciones',
      icon: '👔',
      badge: 'Jerarquía',
      color: 'border-b-4 border-teal-600',
    },
    {
      title: 'Gestión de Grado y Egresados',
      desc: 'Cierre de expedientes de graduación, emisión de títulos y actas',
      icon: '🏆',
      badge: 'Grados',
      color: 'border-b-4 border-amber-600',
    },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      <RoleNavbar
        user={user}
        roleTitle="Super Usuario"
        roleBadgeColor="bg-purple-100 text-purple-900 border-purple-300"
      />

      <section className="bg-gradient-to-r from-purple-800 via-indigo-900 to-slate-900 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-sm">
        <div className="max-w-7xl mx-auto">
          <div className="flex items-center gap-2 mb-2">
            <span className="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider bg-white/20 border border-white/30">
              👑 Privilegios Absolutos
            </span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Panel de Super Usuario (Master Access)
          </h1>
          <p className="text-sm text-purple-100 mt-1">
            Bienvenido/a, <strong>{user?.nombre}</strong>. Nivel máximo de administración institucional UPTPC.
          </p>
        </div>
      </section>

      <main className="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 flex-1">
        <div className="mb-6">
          <h2 className="text-lg font-bold text-slate-900">Módulos de Seguridad y Supervisión</h2>
          <p className="text-xs text-slate-500">Herramientas exclusivas para el control integral de la plataforma</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          {superTools.map((tool, idx) => (
            <div
              key={idx}
              className={`bg-white rounded-xl p-5 shadow-xs border border-slate-200/90 ${tool.color} flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-150`}
            >
              <div>
                <div className="flex justify-between items-start mb-3">
                  <span className="text-3xl p-2 rounded-lg bg-slate-50 border border-slate-100">
                    {tool.icon}
                  </span>
                  <span className="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-purple-50 text-purple-700">
                    {tool.badge}
                  </span>
                </div>
                <h3 className="font-bold text-slate-900 text-base mb-1">{tool.title}</h3>
                <p className="text-xs text-slate-500 leading-relaxed mb-4">{tool.desc}</p>
              </div>

              <button
                onClick={() => router.push('/admin')}
                className="w-full py-2 px-3 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-center cursor-pointer"
              >
                Abrir Módulo →
              </button>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
