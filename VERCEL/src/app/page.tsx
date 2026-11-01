'use client';

import { useState } from 'react';
import Image from 'next/image';
import { useRouter } from 'next/navigation';

export default function LoginPage() {
  const router = useRouter();
  const [identifier, setIdentifier] = useState('herrejose@gmail.com');
  const [password, setPassword] = useState('12345678');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    try {
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ identifier, password }),
      });

      const data = await res.json();

      if (!res.ok || !data.ok) {
        setError(data.error || 'Credenciales incorrectas');
        setLoading(false);
        return;
      }

      // Redirigir según el perfil asignado o al profile-selector si tiene múltiples
      if (data.targetUrl) {
        router.push(data.targetUrl);
      } else {
        router.push('/profile-selector');
      }
    } catch (err: any) {
      setError('Error de conexión con el servidor: ' + err.message);
      setLoading(false);
    }
  };

  return (
    <main className="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 via-slate-50 to-indigo-50">
      <div className="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-blue-900/5 border border-blue-100 overflow-hidden">
        {/* Header Institucional */}
        <div className="bg-gradient-to-b from-white to-blue-50/60 p-6 text-center border-b border-blue-100/60">
          <div className="flex justify-center mb-3">
            <Image
              src="/img/control_estudios.png"
              alt="Control de Estudios UPTPC"
              width={85}
              height={85}
              priority
              className="drop-shadow-sm h-auto"
            />
          </div>
          <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-blue-100/70 text-blue-900 border border-blue-200/60">
            <span className="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            Plataforma 2.0
          </span>
          <h1 className="mt-2 text-xl font-extrabold text-blue-950 tracking-tight">
            Control de Estudios
          </h1>
          <p className="text-xs text-slate-500 font-medium">
            Universidad Politécnica Territorial de Puerto Cabello
          </p>
        </div>

        {/* Formulario de Acceso Unificado */}
        <div className="p-6">
          {error && (
            <div className="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs text-red-700 flex items-center gap-2">
              <span>⚠️</span>
              <span>{error}</span>
            </div>
          )}

          <form className="space-y-4" onSubmit={handleSubmit}>
            <div>
              <label className="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                Usuario, Cédula o Correo
              </label>
              <input
                type="text"
                value={identifier}
                onChange={(e) => setIdentifier(e.target.value)}
                placeholder="Ej: V-12345678 o herrejose@gmail.com"
                className="w-full px-3.5 py-2.5 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                required
              />
            </div>

            <div>
              <label className="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
                Contraseña
              </label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full px-3.5 py-2.5 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                required
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 disabled:bg-blue-400 text-white font-semibold text-sm rounded-lg shadow-md shadow-blue-700/20 transition duration-150 ease-in-out cursor-pointer text-center"
            >
              {loading ? 'Iniciando sesión...' : 'Iniciar Sesión'}
            </button>
          </form>

          {/* Estado de Arquitectura Moderna */}
          <div className="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-[11px] text-slate-500 text-center">
            <div className="bg-slate-50 p-2 rounded-lg border border-slate-200/60">
              <span className="font-semibold text-slate-700 block">Turso DB</span>
              <span>Base de Datos Conectada</span>
            </div>
            <div className="bg-slate-50 p-2 rounded-lg border border-slate-200/60">
              <span className="font-semibold text-slate-700 block">Cloudflare R2</span>
              <span>Almacenamiento S3</span>
            </div>
          </div>
        </div>

        {/* Footer */}
        <div className="bg-slate-50/80 px-6 py-3.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
          <div className="flex items-center gap-2">
            <Image
              src="/img/cyt.png"
              alt="Ciencia y Tecnología UPTPC"
              width={22}
              height={22}
              className="h-auto"
            />
            <span>Unidad de Ciencia y Tecnología</span>
          </div>
          <a
            href="/api/health"
            target="_blank"
            className="text-blue-600 hover:underline font-medium"
          >
            Estado API
          </a>
        </div>
      </div>
    </main>
  );
}
