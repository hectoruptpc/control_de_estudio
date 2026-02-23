Plan: Importar/llenar notas vía CSV (preview sólo, no guardado automático)

Resumen rápido
- Objetivo: permitir que el docente descargue una plantilla CSV con sus estudiantes (por sección+materia), la complete con las notas y la suba para que el sistema sólo "rellene" los campos del formulario de notas (no guardar automáticamente). El sistema debe validar filas y devolver un reporte de errores por fila.
- Decisiones asumidas (puedo cambiar si indicas lo contrario):
  - Identificador por CSV: `cedula` (más amigable para docentes)
  - Estructura plantilla: `identificador,nota` — la nota se aplicará al trayecto actual
  - Comportamiento de errores: importar filas válidas en el preview; generar reporte con filas inválidas y mensajes (no se guarda nada hasta que el docente revise y envíe el formulario normal)

Pasos detallados
1) Diseño UI en `docente/notas.php`
   - Añadir botones: `Descargar plantilla CSV` y `Importar CSV (preview)` junto a cada sección/materia.
   - Añadir modal o panel de preview que muestre tabla con filas del CSV mapeadas a estudiantes, mostrando: `cedula`, `nombre`, `nota_propuesta`, `estado_validacion` y mensaje de error si aplica.
   - Permitir al docente confirmar: "Aplicar al formulario" — esto rellenará los inputs del `#form-notas` en el DOM (no guarda). El docente podrá revisar y luego enviar el formulario como hoy.

2) Endpoint `docente/descargar_planilla_csv.php`
   - Recibe `seccion_id` y `materia_id`, valida que el docente tenga acceso.
   - Genera CSV con encabezado: `identificador,nota` y filas: `cedula,` (nota vacía)
   - Fuerza headers para descarga: `text/csv`, `attachment`, con nombre `planilla_seccion_{codigo}_materia_{nombre}.csv`.

3) Endpoint `docente/import_preview_notas.php`
   - Recibe upload `file` (CSV), `seccion_id`, `materia_id`, y `trayecto_actual` (o lo deduce de la sesión/form).
   - Parseo seguro (separator `,`, soporta `"`), límite de filas (ej. 2000), máximo MB configurable.
   - Para cada fila:
     - Buscar estudiante por `cedula` y por `seccion_id` (si no existe, marcar error "Cédula no encontrada en la sección").
     - Validar nota: numérica entera o decimal según reglas (ej. 1-20). Si inválida, marcar error.
     - Preparar objeto fila con: `estudiante_id`, `cedula`, `nombre`, `nota`, `valido:true/false`, `mensaje_error`.
   - Responder JSON con `previewRows` (filas), `summary` (total, válidas, inválidas).

4) Funciones de validación en `funciones/functions.php`
   - Añadir helper `validarFilaNotaCsv($fila, $trayecto)` que encapsule validaciones y normalización de nota.
   - Añadir helper `buscarEstudiantePorCedulaEnSeccion($cedula, $seccion_id)` (si ya existe, reutilizarla).

5) Cliente JS en `docente/notas.php`
   - Implementar: descarga plantilla (link con `window.location` a `descargar_planilla_csv.php?...`).
   - Implementar: subida via `fetch` con `FormData` a `import_preview_notas.php`; mostrar spinner; al recibir JSON, renderizar tabla de preview.
   - Botón `Aplicar al formulario` que itera filas válidas y hace `document.querySelector` para encontrar `input[name="notas[<estudiante_id>][<campo_trayecto>]" ]` y setea su `.value` con la nota. Si un campo no existe, marcar advertencia.

6) Mensajes y validación UX
   - Si hay filas inválidas, mostrar contador y descargar CSV de errores (opcional) o mostrar modal con lista para copiar/pegar.
   - Si ocurre error server-side (parseo, formato), mostrar alert con mensaje claro.

7) Pruebas
   - Crear CSV de ejemplo con 10 estudiantes (correctos/incorrectos) y validar preview y aplicación a formulario.
   - Verificar que no se inserte nada en DB durante el preview. Solo al enviar `#form-notas` actual se hará el guardado (flujo existente en `guardar_notas.php`).

Consideraciones de seguridad y rendimiento
- Validar `Content-Type`, aceptar solo `text/csv` y `application/vnd.ms-excel` (algunos navegadores), y validar por extensión y lectura inicial.
- Límite de tamaño de archivo (ej. 5MB) y límite de filas para evitar DoS.
- Sanitizar datos antes de mostrarlos en HTML (escape).
- Requiere verificación de permisos: el docente debe tener acceso a la `seccion_id` y `materia_id`.

Entregables iniciales
- `docente/descargar_planilla_csv.php` (nuevo)
- `docente/import_preview_notas.php` (nuevo)
- Cambios mínimos en `funciones/functions.php` (helpers de validación)
- Cambios en `docente/notas.php` (botones, modal y JS)

Siguiente paso que voy a ejecutar (si me das OK)
- Implementar los archivos nuevos `descargar_planilla_csv.php` e `import_preview_notas.php` y añadir el JS mínimo en `docente/notas.php` para subir/mostrar preview y aplicar los valores al formulario (sin guardar).
