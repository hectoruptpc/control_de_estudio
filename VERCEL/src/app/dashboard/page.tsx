'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import { useRouter } from 'next/navigation';

export default function DashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState<'estudiantes' | 'carreras' | 'secciones' | 'sistema'>('estudiantes');

  // Datos
  const [estudiantes, setEstudiantes] = useState<any[]>([]);
  const [carreras, setCarreras] = useState<any[]>([]);
  const [secciones, setSecciones] = useState<any[]>([]);
  const [searchQuery, setSearchQuery] = useState('');
  const [loadingData, setLoadingData] = useState(false);

  // 1. Cargar usuario actual
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

  // 2. Cargar estudiantes
  const fetchEstudiantes = async (q = '') => {
    setLoadingData(true);
    try {
      const res = await fetch(`/api/estudiantes?q=${encodeURIComponent(q)}`);
      const data = await res.json();
      if (data.ok) {
        setEstudiantes(data.estudiantes);
      }
    } finally {
      setLoadingData(false);
    }
  };

  // 3. Cargar carreras
  const fetchCarreras = async () => {
    try {
      const res = await fetch('/api/carreras');
      const data = await res.json();
      if (data.ok) setCarreras(data.carreras);
    } catch (e) {
      console.error(e);
    }
  };

  // 4. Cargar secciones
  const fetchSecciones = async () => {
    try {
      const res = await fetch('/api/secciones');
      const data = await res.json();
      if (data.ok) setSecciones(data.secciones);
    } catch (e) {
      console.error(e);
    }
  };

  useEffect(() => {
    if (!loading && user) {
      fetchEstudiantes();
      fetchCarreras();
      fetchSecciones();
    }
  }, [loading, user]);

  const handleLogout = async () => {
    await fetch('/api/auth/logout', { method: 'POST' });
    router.push('/');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="text-center">
          <div className="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Sistema de Control de Estudios...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      {/* Barra Superior / Navbar */}
      <header className="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
          <div className="flex items-center gap-3">
            <Image
              src="/img/control_estudios.png"
              alt="UPTPC Logo"
              width={42}
              height={42}
              className="h-auto"
            />
            <div>
              <span className="font-bold text-blue-950 text-base leading-none block">
                Control de Estudios UPTPC
              </span>
              <span className="text-[11px] text-slate-500 font-medium">
                Sede Central & COEF • Puerto Cabello
              </span>
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="text-right hidden sm:block">
              <span className="font-semibold text-xs text-slate-900 block leading-tight">
                {user.nombre}
              </span>
              <span className="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
                {user.activeRole}
              </span>
            </div>

            <button
              onClick={handleLogout}
              className="px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition"
            >
              Cerrar Sesión
            </button>
          </div>
        </div>
      </header>

      {/* Navegación por Módulos */}
      <div className="bg-white border-b border-slate-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-2 overflow-x-auto py-2">
          <button
            onClick={() => setActiveTab('estudiantes')}
            className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 ${
              activeTab === 'estudiantes'
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            👥 Directorio Estudiantes ({estudiantes.length})
          </button>

          <button
            onClick={() => setActiveTab('carreras')}
            className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 ${
              activeTab === 'carreras'
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            🎓 Carreras / PNF ({carreras.length})
          </button>

          <button
            onClick={() => setActiveTab('secciones')}
            className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 ${
              activeTab === 'secciones'
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            🏛️ Secciones ({secciones.length})
          </button>

          <button
            onClick={() => setActiveTab('sistema')}
            className={`px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 ${
              activeTab === 'sistema'
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100'
            }`}
          >
            ⚙️ Arquitectura & Estado
          </button>
        </div>
      </div>

      {/* Contenido Principal */}
      <main className="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8">
        {/* TAB 1: ESTUDIANTES */}
        {activeTab === 'estudiantes' && (
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
              <div>
                <h2 className="text-base font-bold text-slate-900">Listado de Estudiantes</h2>
                <p className="text-xs text-slate-500">
                  Registros activos sincronizados desde Turso DB con fotos alojadas en Cloudflare R2
                </p>
              </div>

              <div className="flex w-full sm:w-80 gap-2">
                <input
                  type="text"
                  placeholder="Buscar por cédula, nombre o correo..."
                  value={searchQuery}
                  onChange={(e) => {
                    setSearchQuery(e.target.value);
                    fetchEstudiantes(e.target.value);
                  }}
                  className="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-600"
                />
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Estudiante</th>
                    <th className="py-3 px-4">Cédula / Usuario</th>
                    <th className="py-3 px-4">Carrera</th>
                    <th className="py-3 px-4">Contacto</th>
                    <th className="py-3 px-4">Estado</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loadingData ? (
                    <tr>
                      <td colSpan={5} className="py-8 text-center text-slate-400">
                        Cargando estudiantes...
                      </td>
                    </tr>
                  ) : estudiantes.length === 0 ? (
                    <tr>
                      <td colSpan={5} className="py-8 text-center text-slate-400">
                        No se encontraron estudiantes para los criterios seleccionados.
                      </td>
                    </tr>
                  ) : (
                    estudiantes.map((est) => (
                      <tr key={est.id} className="hover:bg-blue-50/40 transition">
                        <td className="py-3 px-4">
                          <div className="flex items-center gap-3">
                            <img
                              src={est.fotoUrl}
                              alt={est.nombre}
                              className="w-9 h-9 rounded-full object-cover border border-slate-200 bg-slate-100"
                              onError={(e: any) => {
                                e.target.src = '/img/control_estudios.png';
                              }}
                            />
                            <div>
                              <span className="font-semibold text-slate-900 block">
                                {est.nombre}
                              </span>
                              <span className="text-[11px] text-slate-400">
                                ID #{est.id}
                              </span>
                            </div>
                          </div>
                        </td>
                        <td className="py-3 px-4 font-mono font-medium text-slate-700">
                          {est.cedula}
                        </td>
                        <td className="py-3 px-4">
                          <span className="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                            {est.carreraNombre}
                          </span>
                        </td>
                        <td className="py-3 px-4">
                          <span className="block">{est.email}</span>
                          <span className="text-[11px] text-slate-400">{est.telefono}</span>
                        </td>
                        <td className="py-3 px-4">
                          <span
                            className={`px-2 py-0.5 rounded-full text-[10px] font-bold ${
                              est.status === 'Activo'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700'
                            }`}
                          >
                            {est.status}
                          </span>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* TAB 2: CARRERAS */}
        {activeTab === 'carreras' && (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {carreras.map((car) => (
              <div
                key={car.id}
                className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between"
              >
                <div>
                  <div className="flex justify-between items-start mb-2">
                    <span className="px-2.5 py-1 rounded text-[10px] font-bold bg-blue-100 text-blue-800 uppercase">
                      Código: {car.codigo}
                    </span>
                    <span className="text-xs text-green-600 font-semibold">● Activa</span>
                  </div>
                  <h3 className="font-bold text-slate-900 text-sm mb-1">{car.nombre}</h3>
                  <p className="text-xs text-slate-500 mb-3">{car.titulo || 'Título Universitario UPTPC'}</p>
                </div>
                <div className="pt-3 border-t border-slate-100 text-xs text-slate-400 flex justify-between">
                  <span>Duración: {car.duracion || 6} trayectos/semestres</span>
                  <span className="text-blue-600 font-medium">Ver Malla</span>
                </div>
              </div>
            ))}
          </div>
        )}

        {/* TAB 3: SECCIONES */}
        {activeTab === 'secciones' && (
          <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="p-4 sm:p-6 border-b border-slate-200">
              <h2 className="text-base font-bold text-slate-900">Secciones Académicas</h2>
              <p className="text-xs text-slate-500">Asignación de cupos, turnos y trayectos</p>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Código Sección</th>
                    <th className="py-3 px-4">Carrera</th>
                    <th className="py-3 px-4">Trayecto</th>
                    <th className="py-3 px-4">Turno</th>
                    <th className="py-3 px-4">Capacidad</th>
                    <th className="py-3 px-4">Período</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {secciones.map((sec) => (
                    <tr key={sec.id} className="hover:bg-slate-50 transition">
                      <td className="py-3 px-4 font-mono font-bold text-blue-900">{sec.codigo}</td>
                      <td className="py-3 px-4">{sec.carrera}</td>
                      <td className="py-3 px-4 font-semibold">Trayecto {sec.trayecto}</td>
                      <td className="py-3 px-4">{sec.turno}</td>
                      <td className="py-3 px-4">{sec.capacidad} estudiantes</td>
                      <td className="py-3 px-4 font-medium text-slate-500">{sec.periodo}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* TAB 4: ARQUITECTURA & ESTADO */}
        {activeTab === 'sistema' && (
          <div className="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
            <div>
              <h2 className="text-base font-bold text-slate-900 mb-1">Estado de la Arquitectura Vercel</h2>
              <p className="text-xs text-slate-500">
                Infraestructura nativa sin servidor con base de datos distribuida y CDN multimedia
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span className="text-xs font-semibold text-slate-500 block mb-1">Base de Datos</span>
                <span className="text-sm font-bold text-slate-900 block">Turso (libSQL)</span>
                <span className="text-xs text-emerald-600 font-medium">● Conectada en AWS us-east-2</span>
              </div>

              <div className="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span className="text-xs font-semibold text-slate-500 block mb-1">Almacenamiento S3</span>
                <span className="text-sm font-bold text-slate-900 block">Cloudflare R2</span>
                <span className="text-xs text-blue-600 font-medium">● CDN Activo (pub-78d31...r2.dev)</span>
              </div>

              <div className="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span className="text-xs font-semibold text-slate-500 block mb-1">Entorno de Ejecución</span>
                <span className="text-sm font-bold text-slate-900 block">Next.js 15 (Node.js/Bun)</span>
                <span className="text-xs text-indigo-600 font-medium">● Modo Local / Vercel Ready</span>
              </div>
            </div>
          </div>
        )}
      </main>
    </div>
  );
}
