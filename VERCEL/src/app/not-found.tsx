import Link from 'next/link';

export default function NotFound() {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-slate-50 p-4 text-center">
      <h2 className="text-3xl font-extrabold text-blue-950 mb-2">404 - Página no encontrada</h2>
      <p className="text-sm text-slate-500 mb-6">El recurso solicitado no existe en la plataforma de Control de Estudios UPTPC.</p>
      <Link
        href="/admin"
        className="px-4 py-2 bg-blue-700 text-white rounded-lg text-xs font-semibold hover:bg-blue-800 transition"
      >
        Regresar al Panel Principal
      </Link>
    </div>
  );
}
