import { sqliteTable, text, integer, real } from 'drizzle-orm/sqlite-core';
import { sql } from 'drizzle-orm';

/**
 * Tabla: users
 * Modelo completo del usuario UPTPC
 */
export const users = sqliteTable('users', {
  id: integer('id').primaryKey({ autoIncrement: true }),
  idusuario: text('idusuario'),
  nombre: text('nombre').notNull(),
  username: text('username').notNull(),
  email: text('email').notNull(),
  tlf: text('tlf').notNull(),
  cel: text('cel').notNull(),
  direccion: text('direccion'),
  ciudad: text('ciudad'),
  estado: text('estado'),
  municipio: text('municipio'),
  parroquia: text('parroquia'),
  etnia: text('etnia').default('Ninguna'),
  casaapto: text('casaapto'),
  punto_referencia: text('punto_referencia'),
  status: integer('status').default(1),
  user_type: text('user_type').default('user'),
  password: text('password'), // Hash bcrypt ($2y$)
  api_key: text('api_key'),
  carrera: integer('carrera'),
  carrera_di: integer('carrera_di'),
  genero: text('genero'),
  foto_perfil: text('foto_perfil'),

  // Flags de permisos
  usuario: text('usuario'),
  estudiante: integer('estudiante').default(0),
  docente: integer('docente').default(0),
  admin: integer('admin').default(0),
  super_user: integer('super_user').default(0),
  editar_user: integer('editar_user').default(0),
  editar_nota: integer('editar_nota').default(0),
  editar_acceso: integer('editar_acceso').default(0),
  editar_valores: integer('editar_valores').default(0),
  editar_estudiante: integer('editar_estudiante').default(0),
  agregar_estudiante: integer('agregar_estudiante').default(0),
  agregar_docente: integer('agregar_docente').default(0),
  editar_docente: integer('editar_docente').default(0),
  secciones: integer('secciones').default(0),
  auditoria: integer('auditoria').default(0),
  grado: integer('grado').default(0),
  gestion_grado: integer('gestion_grado').default(0),
  vocero: integer('vocero').default(0),
  visita: integer('visita').default(0),
  constancias: integer('constancias').default(0),
  preinscripciones: integer('preinscripciones').default(0),

  fecha_ingreso: text('fecha_ingreso').default(sql`CURRENT_TIMESTAMP`),
  fecha_act: text('fecha_act'),
});

/**
 * Tabla: carreras
 */
export const carreras = sqliteTable('carreras', {
  id_carrera: integer('id_carrera').primaryKey({ autoIncrement: true }),
  nombre_carrera: text('nombre_carrera').notNull(),
  cod_carrera: text('cod_carrera').notNull(),
  activa: integer('activa').default(1),
  duracion_semestres: integer('duracion_semestres'),
  titulo_otorga: text('titulo_otorga'),
  otro_titulo: text('otro_titulo'),
  descripcion: text('descripcion'),
});

/**
 * Tabla: materias
 */
export const materias = sqliteTable('materias', {
  id_materia: integer('id_materia').primaryKey({ autoIncrement: true }),
  cod_materia: text('cod_materia').notNull(),
  pnf_ptf: text('pnf_ptf'),
  nombre_materia: text('nombre_materia').notNull(),
  creditos: integer('creditos').default(3),
  activa: integer('activa').default(1),
  horas_teoricas: integer('horas_teoricas'),
  horas_practicas: integer('horas_practicas'),
  trayecto: integer('trayecto').default(1),
});

/**
 * Tabla: periodos_academicos
 */
export const periodosAcademicos = sqliteTable('periodos_academicos', {
  id_periodo: integer('id_periodo').primaryKey({ autoIncrement: true }),
  nombre_periodo: text('nombre_periodo').notNull(),
  fecha_inicio: text('fecha_inicio').notNull(),
  fecha_fin: text('fecha_fin').notNull(),
  activo: integer('activo').default(0),
  created_at: text('created_at').default(sql`CURRENT_TIMESTAMP`),
});

/**
 * Tabla: secciones
 */
export const secciones = sqliteTable('secciones', {
  id_seccion: integer('id_seccion').primaryKey({ autoIncrement: true }),
  codigo_seccion: text('codigo_seccion').notNull(),
  id_carrera: integer('id_carrera').notNull(),
  turno: text('turno'),
  numero_seccion: integer('numero_seccion'),
  id_trayecto: integer('id_trayecto').notNull(),
  id_periodo: integer('id_periodo').notNull(),
  capacidad_maxima: integer('capacidad_maxima').default(35),
});

/**
 * Tabla: estudiante_seccion
 */
export const estudianteSeccion = sqliteTable('estudiante_seccion', {
  id_usuario: integer('id_usuario').notNull(),
  id_seccion: integer('id_seccion').notNull(),
  fecha_inscripcion: text('fecha_inscripcion').notNull(),
  estatus: text('estatus').default('activo'),
});

/**
 * Tabla: docente_seccion
 */
export const docenteSeccion = sqliteTable('docente_seccion', {
  id_docente_seccion: integer('id_docente_seccion').primaryKey({ autoIncrement: true }),
  id_usuario: integer('id_usuario').notNull(),
  id_seccion: integer('id_seccion').notNull(),
  id_materia: integer('id_materia').notNull(),
  fecha_asignacion: text('fecha_asignacion').default(sql`CURRENT_TIMESTAMP`),
  estatus: integer('estatus').default(1),
});

/**
 * Tabla: notas_definitivas
 */
export const notasDefinitivas = sqliteTable('notas_definitivas', {
  id: integer('id').primaryKey({ autoIncrement: true }),
  id_usuario: integer('id_usuario'),
  id_materia: integer('id_materia'),
  id_periodo: integer('id_periodo'),
  id_docente: integer('id_docente'),
  trayecto_0: integer('trayecto_0'),
  trayecto_1: integer('trayecto_1'),
  trayecto_2: integer('trayecto_2'),
  trayecto_3: integer('trayecto_3'),
  trayecto_4: integer('trayecto_4'),
  fecha_carga: text('fecha_carga').default(sql`CURRENT_TIMESTAMP`),
});

/**
 * Tabla: pagos
 */
export const pagos = sqliteTable('pagos', {
  id: integer('id').primaryKey({ autoIncrement: true }),
  estudiante_id: integer('estudiante_id'),
  tipo_pago: text('tipo_pago').notNull(),
  otro_concepto: text('otro_concepto'),
  monto: real('monto').notNull(),
  fecha_transaccion: text('fecha_transaccion'),
  metodo_pago: text('metodo_pago').default('Transferencia'),
  banco_origen: text('banco_origen'),
  banco_destino_id: integer('banco_destino_id'),
  referencia: text('referencia'),
  comprobante: text('comprobante'),
  status_pago: text('status_pago').default('aprobado'),
  motivo_rechazo: text('motivo_rechazo'),
  fecha_pago: text('fecha_pago').default(sql`CURRENT_TIMESTAMP`),
  observaciones: text('observaciones'),
  registrado_por: integer('registrado_por'),
});

/**
 * Tabla: auditoria
 */
export const auditoria = sqliteTable('auditoria', {
  id: integer('id').primaryKey({ autoIncrement: true }),
  usuario_id: integer('usuario_id').notNull(),
  accion: text('accion').notNull(),
  tabla_afectada: text('tabla_afectada'),
  registro_id: integer('registro_id'),
  fecha_hora: text('fecha_hora').default(sql`CURRENT_TIMESTAMP`),
  valores_antiguos: text('valores_antiguos'),
  valores_nuevos: text('valores_nuevos'),
  ip_origen: text('ip_origen'),
  user_agent: text('user_agent'),
  modulo_sistema: text('modulo_sistema'),
  descripcion: text('descripcion'),
});
