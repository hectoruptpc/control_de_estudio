import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: 'Control de Estudios | UPTPC',
  description: 'Sistema Integral de Control de Estudios - Universidad Politécnica Territorial de Puerto Cabello',
  icons: {
    icon: '/img/favicon.svg',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="es">
      <body className="antialiased bg-[#f4f7fc] text-slate-800">
        {children}
      </body>
    </html>
  );
}
