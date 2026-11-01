# 🏛️ Plan Integral de Migración y Optimización: UPTPC Control de Estudios (Next.js + Turso + Cloudflare R2)

**Universidad Territorial Politécnica de Puerto Cabello (UPTPC)**  
*Documento de Arquitectura, Estrategia de Migración y Hoja de Ruta Modular*

---

## 🎯 1. Visión y Objetivos

El objetivo de esta migración es transformar el sistema monolítico procedural en PHP 8 / MySQL hacia una **arquitectura moderna, escalable, sin servidor (serverless) y de costo óptimo**, desplegable de forma nativa en **Vercel** bajo el dominio institucional `https://ce.uptpc.edu.ve`, manteniendo:
1. **Cero pérdida de datos**: Migración limpia y fiel del esquema y registros de `proyecto_tsu.sql`.
2. **Cero fricción de usuarios**: Los hashes bcrypt de contraseñas se respetan 100%; ni estudiantes ni docentes tendrán que recuperar contraseñas.
3. **Cero código basura**: Solo código modular, fuertemente tipado en TypeScript, sin librerías obsoletas ni archivos temporales.
4. **Desacoplamiento total de archivos**: Todas las fotos, comprobantes y soportes migran a Cloudflare R2. La base de datos solo almacena metadatos y nombres de claves.

---

## 🏗️ 2. Arquitectura de la Solución

```
                                  [ Usuario / Navegador ]
                                             │
                                             ▼
                             [ Vercel Edge / https://ce.uptpc.edu.ve ]
                                             │
                 ┌───────────────────────────┴───────────────────────────┐
                 │                                                       │
                 ▼                                                       ▼
        [ Next.js App Router ]                                  [ Cloudflare R2 CDN ]
    (TypeScript + Drizzle ORM)                           (https://pub-78d31...r2.dev)
                 │                                                       │
                 ▼                                                       │
     [ Turso Database (libSQL) ]                                         │
   (Cluster distribuido AWS us-east-2)                                   │
                 ▲                                                       ▲
                 └─────────────── [ scripts/migrate-r2.mjs ] ────────────┘
```

### Componentes Principales:
| Componente | Tecnología | Responsabilidad |
|---|---|---|
| **Frontend & API** | Next.js 15+ (App Router) + Tailwind CSS | Vistas reactivas, SSR/SSG, Server Actions y API Routes seguras |
| **Capa de Datos (ORM)** | Drizzle ORM | Consultas SQL tipadas con TypeScript sin sobrecarga de runtime |
| **Base de Datos** | Turso (libSQL) | SQLite distribuido global con soporte de transacciones ACID y latencia < 50ms |
| **Almacenamiento de Archivos** | Cloudflare R2 | S3-compatible, costo $0 por egreso de datos, alta disponibilidad |
| **Autenticación** | `bcryptjs` + Cookies HttpOnly seguras | Validación directa de contraseñas existentes y sesiones multi-rol |
| **Email Transaccional** | Resend / SMTP | Notificaciones de preinscripción, notas y restablecimiento de claves |

---

## 📊 3. Mapeo de Archivos Multimedia a Cloudflare R2

Para garantizar un rendimiento óptimo y eliminar el uso de disco local:

| Carpeta PHP Actual | Ruta en Cloudflare R2 | Tipo de Contenido | Reglas de Acceso |
|---|---|---|---|
| `foto_perfil/` | `/foto_perfil/{filename}` | Fotos de estudiantes y profesores | Público vía CDN |
| `soportes/` | `/soportes/{filename}` | Comprobantes y documentos adjuntos | Público / URLs firmadas |
| `uploads/` | `/uploads/{filename}` | Cargas y plantillas administrativas | Público vía CDN |
| `images/` | `/images/{filename}` | Logotipos, escudos y sellos | Caché inmutable (1 año) |

El script [`scripts/migrate-r2.mjs`](file:///srv/http/control_de_estudio/VERCEL/scripts/migrate-r2.mjs) se encarga de transferir y sincronizar todos estos archivos con un solo comando.

---

## 🗺️ 4. Fases de Ejecución

### Fase 1: Infraestructura y Autenticación (Completada / Base Lista)
- [x] Configuración del proyecto Next.js en `VERCEL/`.
- [x] Sistema de variables de entorno seguras (`.env.local` / `.env.example`).
- [x] Script de diagnóstico de entorno (`scripts/check-env.mjs`).
- [x] Conexión y verificación de base de datos Turso (`scripts/test-db.mjs`).
- [x] Script de migración de archivos a Cloudflare R2 (`scripts/migrate-r2.mjs`).
- [x] Esquema inicial en Drizzle (`src/db/schema.ts`).
- [ ] Implementación de Server Action de Login con verificación bcrypt de la tabla `users`.

### Fase 2: Migración de Datos (MySQL → Turso)
1. Exportar datos de tablas maestras desde `proyecto_tsu.sql`:
   - `carreras`, `materias`, `mallas`, `periodos_academicos`, `aulas`, `estados`, `municipios`, `parroquias`.
2. Exportar usuarios (`users`), asegurando la normalización de campos booleanos (1/0 en lugar de NULL).
3. Exportar relaciones académicas:
   - `secciones`, `estudiante_seccion`, `docente_seccion`, `docente_materia`.
4. Exportar historial de calificaciones (`notas_definitivas`, `notas_trimestres`).
5. Exportar registros de `pagos` y `auditoria`.

### Fase 3: Módulo del Estudiante
- Dashboard del alumno con datos personales y carrera.
- Consulta interactiva de récord académico y notas definitivas.
- Visualización de horario asignado y sección.
- Módulo de carga de comprobantes de pago (subida directa a Cloudflare R2).

### Fase 4: Módulo del Docente
- Consulta de secciones y asignaturas asignadas.
- Carga de calificaciones parciales y definitivas por sección.
- Generación y descarga de planillas de calificaciones en CSV / PDF.

### Fase 5: Módulo del Director de Carrera
- Gestión y asignación de docentes por sección.
- Aprobación y revisión de notas de las carreras bajo su coordinación.
- Designación de voceros estudiantiles.

### Fase 6: Módulo de Administración y Super Usuario
- Gestión centralizada de estudiantes, docentes y personal administrativo.
- Configuración de períodos académicos y apertura/cierre de lapsos de notas.
- Centro de auditoría global de modificaciones y accesos.

### Fase 7: Generación de Constancias y Documentos Oficiales
- Reemplazo de FPDF por `@react-pdf/renderer` o `pdf-lib`.
- Generación en milisegundos en Vercel Serverless Functions.
- Constancias de estudio, notas certificadas, actas de calificación y carnets con código QR de verificación.

---

## 🛡️ 5. Principios de Seguridad y Ahorro de Recursos
1. **Protección de Secretos**: `.env*` completamente excluido de Git y de Vercel Source.
2. **Consultas Parametrizadas**: Drizzle ORM previene inyecciones SQL por diseño.
3. **Optimización de Imágenes**: `next/image` optimiza automáticamente las imágenes servidas desde Cloudflare R2.
4. **Sin Bloqueos de Procesos**: Operaciones pesadas (envío de correos o generación masiva de PDFs) se manejan asíncronamente mediante endpoints dedicados.
