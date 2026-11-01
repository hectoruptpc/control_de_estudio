import Image from 'next/image';
import Link from 'next/link';

interface NavbarProps {
  user: {
    nombre: string;
    username: string;
    email: string;
    activeRole: string;
    roles?: {
      isAdmin?: boolean;
      isSuperUser?: boolean;
      isDocente?: boolean;
      isEstudiante?: boolean;
      isDirector?: boolean;
    };
  };
  activeModule?: string;
  roleBadgeColor?: string;
  roleTitle?: string;
}

export default function RoleNavbar({
  user,
  activeModule,
  roleBadgeColor = 'bg-blue-100 text-blue-800 border-blue-200',
  roleTitle,
}: NavbarProps) {
  const displayRole = roleTitle || user.activeRole.replace(/_/g, ' ').toUpperCase();

  return (
    <header className="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <div className="flex items-center gap-3">
          <Link href="/profile-selector" className="flex items-center gap-3 group">
            <Image
              src="/img/control_estudios.png"
              alt="UPTPC Logo"
              width={40}
              height={40}
              className="h-auto group-hover:scale-105 transition"
            />
            <div>
              <span className="font-extrabold text-blue-950 text-sm sm:text-base leading-tight block">
                UPTPC • Control de Estudios
              </span>
              <span className="text-[10px] text-slate-500 font-medium">
                Sede Central Puerto Cabello
              </span>
            </div>
          </Link>
        </div>

        <div className="flex items-center gap-3 sm:gap-4">
          {/* Badge del Perfil Activo */}
          <div className="flex items-center gap-2">
            <span
              className={`hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border uppercase tracking-wider ${roleBadgeColor}`}
            >
              <span className="w-1.5 h-1.5 rounded-full bg-current"></span>
              {displayRole}
            </span>

            {/* Botón para cambiar de perfil si tiene múltiples */}
            <Link
              href="/profile-selector"
              title="Cambiar entre perfiles disponibles"
              className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition"
            >
              <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
              <span>Cambiar Perfil</span>
            </Link>
          </div>

          {/* Información del Usuario */}
          <div className="text-right hidden md:block">
            <span className="font-semibold text-xs text-slate-900 block leading-tight">
              {user.nombre}
            </span>
            <span className="text-[10px] text-slate-500">{user.username}</span>
          </div>

          {/* Form / Botón Logout */}
          <form action="/api/auth/logout" method="POST">
            <button
              type="submit"
              className="px-3 py-1.5 text-xs font-semibold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition flex items-center gap-1"
            >
              <svg className="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span className="hidden sm:inline">Salir</span>
            </button>
          </form>
        </div>
      </div>
    </header>
  );
}
