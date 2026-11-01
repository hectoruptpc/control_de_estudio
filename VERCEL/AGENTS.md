# AGENTS.md — Módulo VERCEL (Next.js + Turso + Cloudflare R2)

> **Regla de Oro**: Este archivo orienta a cualquier Asistente o Agente de IA para trabajar en el proyecto moderno en `VERCEL/` sin gastar tokens re-analizando el entorno.

---

## 1. Contexto del Módulo
- **Ubicación**: `/srv/http/control_de_estudio/VERCEL`
- **Destino de Producción**: Vercel (`https://ce.uptpc.edu.ve`)
- **Desarrollo Local**: Manjaro Linux (KDE Plasma)
- **Stack**: Next.js 15+ (App Router), TypeScript, Drizzle ORM, Turso (libSQL), Cloudflare R2 (S3 API).

## 2. Variables de Entorno y Seguridad
- Las credenciales reales están en `.env.local` (excluido de Git y Vercel por `.gitignore` y `.vercelignore`).
- La plantilla de ejemplo está en `.env.example`.
- Si agregas una nueva variable, actualiza `.env.example` con un valor simulado y ejecuta:
  ```bash
  node scripts/check-env.mjs
  ```

## 3. Comandos Esenciales
| Comando | Función |
|---|---|
| `npm run check-env` | Valida variables de entorno locales |
| `npm run test-db` | Prueba la conectividad real con Turso |
| `bun scripts/test-login.ts` | Prueba la autenticación bcrypt contra Turso |
| `node scripts/migrate-uptpc-to-turso.mjs` | Migrador completo de `uptpc.sql` hacia Turso |
| `node scripts/migrate-r2.mjs --dry-run` | Simula sincronización de fotos a Cloudflare R2 |
| `npm run migrate-r2` | Sube fotos y soportes a Cloudflare R2 |
| `npm run dev` / `bun dev` | Inicia el servidor de desarrollo en `http://localhost:3000` |
| `npm run build` / `bun run build` | Compila la aplicación para producción Vercel |

## 4. Pautas para el Código
1. **No tocar `/sistema_viejo`**: Reservado para análisis posterior.
2. **No almacenar archivos en la BD**: Cualquier imagen o soporte debe subirse a Cloudflare R2 usando `src/lib/r2.ts`.
3. **Contraseñas**: Siempre usar `bcryptjs` para verificar contraseñas de la tabla `users` (compatibilidad con hashes `$2y$` existentes).
4. **Al terminar una tarea**: Actualizar siempre la bitácora en `AGENTS.md` (tanto en la raíz como en este archivo).

## 5. Estado de Implementación
- [x] Conexión a Turso configurada y operativa (72 tablas migradas).
- [x] Subida/migración de assets a Cloudflare R2 probada vía script.
- [x] Endpoints de autenticación y datos:
  - `POST /api/auth/login`: login con bcrypt compatible con `$2y$` y cálculo de perfiles múltiples.
  - `POST /api/auth/select-profile`: cambio de perfil activo en cookie de sesión.
  - `GET /api/auth/me`: sesión actual y rol activo.
  - `POST /api/auth/logout`: cierre de sesión.
  - `GET /api/estudiantes`: búsqueda y listado.
  - `GET /api/carreras`: listado de carreras.
  - `GET /api/secciones`: listado de secciones.
  - `GET /api/health`: chequeo de salud de DB y R2.
- [x] Selector de Perfil (`/profile-selector` sustituto moderno de `profile_selector.php`).
- [x] Portales por Rol con sus respectivas herramientas internas:
  - `/admin`: Pagos, Estudiantes, Pensum/Carreras, Secciones, Calificaciones, Docentes, Secretaría, Auditoría.
  - `/super-user`: Control maestro de accesos, auditoría forense, ajustes de sistema, respaldos.
  - `/docente`: Mis secciones, Carga de notas, Descargar CSV/Excel, Importar notas, Horario, Mensajería.
  - `/estudiante`: Mis notas/historial, Horario y aulas, Declarar pagos, Constancias, Pensum, Prosecución.
  - `/director-de-carrera`: Asignación de docentes, secciones, voceros estudiantiles, seguimiento de notas.
- [x] Componente `RoleNavbar` reutilizable con botón "Cambiar Perfil", identificación y logout.
- [x] Compilación de producción con `next build` 100% exitosa (19 rutas estáticas y dinámicas).

- [x] Operatividad completa de las 30 herramientas de Super User / Administración:
  - `/api/pagos`: Listado, aprobación, rechazo con motivo y registro manual de pagos.
  - `/api/notas`: Consulta de pendientes y definitivas, aprobación administrativa de notas cargadas por docentes, corrección manual.
  - `/api/mensajeria`: Bandeja de entrada y salida, lectura y envío de mensajes entre usuarios.
  - `/api/auditoria`: Consulta de logs de auditoría forense y registros de visitas por IP.
  - `/api/configuracion`: Tablas maestras de tipos de pago, tipos de horario, períodos académicos, materias, prelaciones, títulos y solicitudes de constancias.
  - `/api/academico`: Alta de estudiantes, alta de carreras, alta de materias, alta de docentes, inscripción de asignaturas y edición de permisos granulares.
- [x] Compilación de producción con `next build` 100% exitosa (25 rutas entre endpoints API y vistas).

- [x] CRUD Total de Super Administrador habilitado:
  - `POST /api/crud`: Motor de operaciones Create, Update y Delete para `estudiante`, `docente`, `carrera`, `materia`, `seccion`, `tipo_pago`, `tipo_horario`, `periodo`, `prelacion`, `titulo`.
  - Formularios y modales interactivos en `/admin` con validación y refresco inmediato en Turso Database.
  - Botones de acción directa `✏️ Editar` y `🗑️ Eliminar` por cada fila de las tablas maestras.
- [x] Compilación de producción con `next build` 100% exitosa (26 rutas).
