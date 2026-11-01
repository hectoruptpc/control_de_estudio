# 🚀 UPTPC — Control de Estudios (Versión Next.js + Vercel)

Plataforma moderna del **Sistema de Control de Estudios** de la **Universidad Politécnica Territorial de Puerto Cabello (UPTPC)**, diseñada para ejecutarse de forma nativa en **Vercel**, con base de datos **Turso (libSQL)** y almacenamiento de archivos/multimedia en **Cloudflare R2**.

---

## 🛠️ Stack Tecnológico
- **Framework Web**: [Next.js](https://nextjs.org/) (App Router, React 19, TypeScript)
- **Base de Datos**: [Turso](https://turso.tech/) (SQLite distribuido / libSQL con replicación global)
- **ORM**: [Drizzle ORM](https://orm.drizzle.team/) (Consultas tipadas en TypeScript de latencia mínima)
- **Almacenamiento Multimedia**: [Cloudflare R2](https://developers.cloudflare.com/r2/) (Compatible con AWS S3, sin costos de egreso de ancho de banda)
- **Autenticación**: Hashing compatible con `bcrypt` (garantiza compatibilidad total con contraseñas existentes del sistema PHP)

---

## 💻 Guía de Inicio Local (Manjaro Linux con KDE Plasma)

### 1. Prerrequisitos en Manjaro
Verifica que cuentas con Node.js (v20+) o Bun instalados:
```bash
node -v   # Recomendado v20+ o v22+
npm -v
# o bien:
bun -v
```

### 2. Instalación de Dependencias
Entra al directorio del proyecto:
```bash
cd /srv/http/control_de_estudio/VERCEL
npm install
# o con bun:
bun install
```

### 3. Configuración de Variables de Entorno
El archivo `.env.local` almacena las credenciales privadas y **está protegido por `.gitignore`** para que nunca se filtre a GitHub.
Si necesitas crear uno nuevo desde cero:
```bash
cp .env.example .env.local
```

### 4. Herramientas Locales de Diagnóstico
Hemos creado scripts utilitarios para verificar tu entorno antes de levantar el servidor:

- **Validar variables de entorno (sin exponer claves secretas):**
  ```bash
  npm run check-env
  ```
- **Probar conexión con la base de datos Turso:**
  ```bash
  npm run test-db
  ```
- **Simular o ejecutar migración de fotos/archivos a Cloudflare R2:**
  ```bash
  # Modo simulación (dry-run):
  node scripts/migrate-r2.mjs --dry-run

  # Modo carga activa a R2:
  npm run migrate-r2
  ```

### 5. Ejecución en Modo Desarrollo
Inicia el servidor local de Next.js:
```bash
npm run dev
# o con bun:
bun dev
```
Abre tu navegador en `http://localhost:3000`.

Para comprobar el estado de salud del sistema y la base de datos:
`http://localhost:3000/api/health`

---

## 📦 Despliegue en Vercel

### Despliegue desde la CLI de Vercel
1. Instala Vercel CLI si no la tienes:
   ```bash
   sudo npm install -g vercel
   ```
2. Despliega desde el directorio `VERCEL/`:
   ```bash
   cd /srv/http/control_de_estudio/VERCEL
   vercel
   ```
3. Para producción:
   ```bash
   vercel --prod
   ```

### Variables de Entorno en el Dashboard de Vercel
Configura en **Project Settings > Environment Variables** los mismos valores presentes en `.env.local`:
- `TURSO_DATABASE_URL`
- `TURSO_AUTH_TOKEN`
- `R2_ACCOUNT_ID`
- `R2_ACCESS_KEY_ID`
- `R2_SECRET_ACCESS_KEY`
- `R2_ENDPOINT`
- `R2_PUBLIC_URL`
- `AUTH_SECRET`
- `NEXT_PUBLIC_APP_URL` (`https://ce.uptpc.edu.ve`)

---

## 🔒 Reglas de Seguridad Críticas
1. **NUNCA modifiques el `.gitignore`** para incluir archivos `.env`. Las credenciales deben residir únicamente en `.env.local` en tu máquina y en el panel seguro de Vercel.
2. **Archivos fuera de `/VERCEL`**: El código del sistema legacy en PHP permanece intacto mientras se completa la migración modular.
