'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import RoleNavbar from '@/components/RoleNavbar';

export default function AdminDashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  // Módulo activo
  const [currentModule, setCurrentModule] = useState<string>('resumen');

  // Listas de datos
  const [pagos, setPagos] = useState<any[]>([]);
  const [estudiantes, setEstudiantes] = useState<any[]>([]);
  const [carreras, setCarreras] = useState<any[]>([]);
  const [secciones, setSecciones] = useState<any[]>([]);
  const [notasPendientes, setNotasPendientes] = useState<any[]>([]);
  const [notasDefinitivas, setNotasDefinitivas] = useState<any[]>([]);
  const [auditorias, setAuditorias] = useState<any[]>([]);
  const [visitas, setVisitas] = useState<any[]>([]);
  const [mensajes, setMensajes] = useState<any[]>([]);
  const [docentes, setDocentes] = useState<any[]>([]);
  const [directores, setDirectores] = useState<any[]>([]);
  const [tiposPago, setTiposPago] = useState<any[]>([]);
  const [tiposHorario, setTiposHorario] = useState<any[]>([]);
  const [periodos, setPeriodos] = useState<any[]>([]);
  const [materias, setMaterias] = useState<any[]>([]);
  const [prelaciones, setPrelaciones] = useState<any[]>([]);
  const [titulos, setTitulos] = useState<any[]>([]);
  const [solicitudes, setSolicitudes] = useState<any[]>([]);

  // Notificaciones y filtros
  const [filtroTexto, setFiltroTexto] = useState('');
  const [msgExito, setMsgExito] = useState<string | null>(null);
  const [msgError, setMsgError] = useState<string | null>(null);

  // Control de Modales CRUD
  const [modalAbierto, setModalAbierto] = useState<string | null>(null);
  const [modoModal, setModoModal] = useState<'create' | 'update'>('create');
  const [itemEditar, setItemEditar] = useState<any>(null);

  // Estados de formularios para altas y ediciones
  const [formEstudiante, setFormEstudiante] = useState({ id: 0, idusuario: '', nombre: '', email: '', tlf: '', cel: '', carrera: '1', status: 1, direccion: '' });
  const [formDocente, setFormDocente] = useState({ id: 0, idusuario: '', nombre: '', email: '', tlf: '', cel: '', status: 1 });
  const [formCarrera, setFormCarrera] = useState({ id_carrera: 0, cod_carrera: '', nombre_carrera: '', duracion_anios: 4, titulo_obtenido: '', activa: 1 });
  const [formMateria, setFormMateria] = useState({ id_materia: 0, codigo_materia: '', nombre_materia: '', creditos: 3, id_trayecto: 1 });
  const [formSeccion, setFormSeccion] = useState({ id_seccion: 0, codigo_seccion: '', id_carrera: 1, id_trayecto: 1, id_periodo: 5, capacidad_maxima: 35, turno: 'Diurno' });
  const [formTipoPago, setFormTipoPago] = useState({ id: 0, tipopago: '', precio: 0, status: 1 });
  const [formTipoHorario, setFormTipoHorario] = useState({ id: 0, nombre: '', horas_academicas: 16, horas_atendiendo: 14 });
  const [formPeriodo, setFormPeriodo] = useState({ id_periodo: 0, nombre_periodo: '', fecha_inicio: '', fecha_fin: '', activo: 1 });
  const [formTitulo, setFormTitulo] = useState({ id: 0, nombre: '', descripcion: '' });
  const [formPago, setFormPago] = useState({ estudiante_id: '', monto: '', tipo_pago: 'Inscripción', metodo_pago: 'Transferencia', referencia: '', banco_origen: '', observaciones: '' });
  const [formInscripcion, setFormInscripcion] = useState({ estudiante_id: '', materia_id: '', seccion_id: '' });
  const [formPrelacion, setFormPrelacion] = useState({ id_carrera: 1, id_materia: 1, id_prelacion: 1 });

  // 1. Cargar sesión
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

  // 2. Fetchers de colecciones
  const cargarPagos = async () => {
    const res = await fetch('/api/pagos');
    const d = await res.json();
    if (d.ok) setPagos(d.pagos);
  };

  const cargarEstudiantes = async (q = '') => {
    const res = await fetch(`/api/estudiantes?q=${encodeURIComponent(q)}`);
    const d = await res.json();
    if (d.ok) setEstudiantes(d.estudiantes);
  };

  const cargarCarreras = async () => {
    const res = await fetch('/api/carreras');
    const d = await res.json();
    if (d.ok) setCarreras(d.carreras);
  };

  const cargarSecciones = async () => {
    const res = await fetch('/api/secciones');
    const d = await res.json();
    if (d.ok) setSecciones(d.secciones);
  };

  const cargarNotas = async () => {
    const r1 = await fetch('/api/notas?tipo=pendientes');
    const d1 = await r1.json();
    if (d1.ok) setNotasPendientes(d1.notas);

    const r2 = await fetch('/api/notas?tipo=definitivas');
    const d2 = await r2.json();
    if (d2.ok) setNotasDefinitivas(d2.notas);
  };

  const cargarAuditoriaYVisitas = async () => {
    const r1 = await fetch('/api/auditoria?tipo=auditoria');
    const d1 = await r1.json();
    if (d1.ok) setAuditorias(d1.registros);

    const r2 = await fetch('/api/auditoria?tipo=visitas');
    const d2 = await r2.json();
    if (d2.ok) setVisitas(d2.registros);
  };

  const cargarMensajeria = async () => {
    const res = await fetch('/api/mensajeria');
    const d = await res.json();
    if (d.ok) setMensajes(d.mensajes);
  };

  const cargarConfiguraciones = async () => {
    const [r1, r2, r3, r4, r5, r6, r7, r8, r9] = await Promise.all([
      fetch('/api/configuracion?tipo=tipos_pago'),
      fetch('/api/configuracion?tipo=tipos_horario'),
      fetch('/api/configuracion?tipo=periodos'),
      fetch('/api/configuracion?tipo=materias'),
      fetch('/api/configuracion?tipo=prelaciones'),
      fetch('/api/configuracion?tipo=docentes'),
      fetch('/api/configuracion?tipo=directores'),
      fetch('/api/configuracion?tipo=titulos'),
      fetch('/api/configuracion?tipo=solicitudes'),
    ]);

    const [d1, d2, d3, d4, d5, d6, d7, d8, d9] = await Promise.all([
      r1.json(), r2.json(), r3.json(), r4.json(), r5.json(), r6.json(), r7.json(), r8.json(), r9.json()
    ]);

    if (d1.ok) setTiposPago(d1.datos);
    if (d2.ok) setTiposHorario(d2.datos);
    if (d3.ok) setPeriodos(d3.datos);
    if (d4.ok) setMaterias(d4.datos);
    if (d5.ok) setPrelaciones(d5.datos);
    if (d6.ok) setDocentes(d6.datos);
    if (d7.ok) setDirectores(d7.datos);
    if (d8.ok) setTitulos(d8.datos);
    if (d9.ok) setSolicitudes(d9.datos);
  };

  const refrescarTodo = () => {
    cargarPagos();
    cargarEstudiantes();
    cargarCarreras();
    cargarSecciones();
    cargarNotas();
    cargarAuditoriaYVisitas();
    cargarMensajeria();
    cargarConfiguraciones();
  };

  useEffect(() => {
    if (!loading && user) {
      refrescarTodo();
    }
  }, [loading, user]);

  // ==========================================
  // DISPATCHER CRUD UNIVERSAL (CREATE, UPDATE, DELETE)
  // ==========================================
  const ejecutarCrud = async (entity: string, action: 'create' | 'update' | 'delete', data: any) => {
    try {
      const res = await fetch('/api/crud', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ entity, action, data }),
      });
      const resp = await res.json();
      if (resp.ok) {
        setMsgExito(resp.message || 'Operación completada exitosamente.');
        setModalAbierto(null);
        refrescarTodo();
      } else {
        setMsgError(resp.error || 'Error al procesar la operación');
      }
    } catch (err: any) {
      setMsgError('Error de red: ' + err.message);
    }
  };

  // Abrir Modal de Edición Estudiante
  const abrirEditarEstudiante = (est: any) => {
    setModoModal('update');
    setFormEstudiante({
      id: est.id,
      idusuario: est.idusuario || est.cedula,
      nombre: est.nombre,
      email: est.email || '',
      tlf: est.tlf || '',
      cel: est.cel || '',
      carrera: String(est.carrera || '1'),
      status: Number(est.status ?? 1),
      direccion: est.direccion || '',
    });
    setModalAbierto('modal_estudiante');
  };

  // Abrir Modal de Edición Docente
  const abrirEditarDocente = (doc: any) => {
    setModoModal('update');
    setFormDocente({
      id: doc.id,
      idusuario: doc.idusuario || doc.username,
      nombre: doc.nombre,
      email: doc.email || '',
      tlf: doc.tlf || '',
      cel: doc.cel || '',
      status: Number(doc.status ?? 1),
    });
    setModalAbierto('modal_docente');
  };

  // Abrir Modal de Edición Carrera
  const abrirEditarCarrera = (car: any) => {
    setModoModal('update');
    setFormCarrera({
      id_carrera: car.id || car.id_carrera,
      cod_carrera: car.codigo || car.cod_carrera,
      nombre_carrera: car.nombre || car.nombre_carrera,
      duracion_anios: car.duracion || car.duracion_anios || 4,
      titulo_obtenido: car.titulo || car.titulo_obtenido || '',
      activa: car.activa ? 1 : 0,
    });
    setModalAbierto('modal_carrera');
  };

  // Abrir Modal de Edición Materia
  const abrirEditarMateria = (mat: any) => {
    setModoModal('update');
    setFormMateria({
      id_materia: mat.id_materia,
      codigo_materia: mat.codigo_materia,
      nombre_materia: mat.nombre_materia,
      creditos: mat.creditos || 3,
      id_trayecto: mat.id_trayecto || 1,
    });
    setModalAbierto('modal_materia');
  };

  // Abrir Modal de Edición Tipo de Pago
  const abrirEditarTipoPago = (tp: any) => {
    setModoModal('update');
    setFormTipoPago({
      id: tp.id,
      tipopago: tp.tipopago,
      precio: tp.precio,
      status: tp.status ? 1 : 0,
    });
    setModalAbierto('modal_tipo_pago');
  };

  // Abrir Modal de Edición Tipo de Horario
  const abrirEditarTipoHorario = (th: any) => {
    setModoModal('update');
    setFormTipoHorario({
      id: th.id,
      nombre: th.nombre,
      horas_academicas: th.horas_academicas || 0,
      horas_atendiendo: th.horas_atendiendo || 0,
    });
    setModalAbierto('modal_tipo_horario');
  };

  // Abrir Modal de Edición Período Académico
  const abrirEditarPeriodo = (p: any) => {
    setModoModal('update');
    setFormPeriodo({
      id_periodo: p.id_periodo,
      nombre_periodo: p.nombre_periodo,
      fecha_inicio: p.fecha_inicio || '',
      fecha_fin: p.fecha_fin || '',
      activo: p.activo ? 1 : 0,
    });
    setModalAbierto('modal_periodo');
  };

  // Confirmar eliminación
  const confirmarEliminar = (entity: string, id: number, label: string) => {
    if (confirm(`¿Está seguro de eliminar ${label}? Esta acción no se puede deshacer.`)) {
      if (entity === 'carrera') ejecutarCrud('carrera', 'delete', { id_carrera: id });
      else if (entity === 'materia') ejecutarCrud('materia', 'delete', { id_materia: id });
      else if (entity === 'seccion') ejecutarCrud('seccion', 'delete', { id_seccion: id });
      else if (entity === 'periodo') ejecutarCrud('periodo', 'delete', { id_periodo: id });
      else ejecutarCrud(entity, 'delete', { id });
    }
  };

  // 30 Módulos solicitados
  const all30Modules = [
    { id: 'registro_pagos', name: 'Registro de Pagos', cat: 'Finanzas', icon: '💵', count: pagos.length },
    { id: 'estudiantes', name: 'Estudiantes', cat: 'Académico', icon: '🎓', count: estudiantes.length },
    { id: 'agregar_estudiante', name: 'Agregar Estudiante', cat: 'Académico', icon: '👤➕' },
    { id: 'inscripcion_materias', name: 'Inscripción de Materias', cat: 'Académico', icon: '📝' },
    { id: 'gestion_seccion', name: 'Gestión de Secciones', cat: 'Académico', icon: '🏛️', count: secciones.length },
    { id: 'grado', name: 'Grado y Egresados', cat: 'Grados', icon: '🏆', count: titulos.length },
    { id: 'constancias', name: 'Constancias y Solicitudes', cat: 'Documentos', icon: '📄', count: solicitudes.length },
    { id: 'agregar_carrera', name: 'Gestión de Carreras', cat: 'Pensum', icon: '📚', count: carreras.length },
    { id: 'materia', name: 'Asignaturas', cat: 'Pensum', icon: '📖', count: materias.length },
    { id: 'carrera_materias', name: 'Relación Carreras-Materias', cat: 'Pensum', icon: '🔗' },
    { id: 'periodos_academicos', name: 'Periodos Académicos', cat: 'Pensum', icon: '📅', count: periodos.length },
    { id: 'prelaciones', name: 'Prelaciones', cat: 'Pensum', icon: '📋', count: prelaciones.length },
    { id: 'add_docente', name: 'Gestión Docente', cat: 'Personal', icon: '👨‍🏫', count: docentes.length },
    { id: 'asignar_secciones', name: 'Asignación de Secciones', cat: 'Personal', icon: '🗂️' },
    { id: 'asignacion_cursos', name: 'Asignación de Cursos', cat: 'Personal', icon: '🎯' },
    { id: 'horarios_docentes', name: 'Horarios Docentes', cat: 'Personal', icon: '⏰' },
    { id: 'directores_carrera', name: 'Directores de Carrera', cat: 'Personal', icon: '👔', count: directores.length },
    { id: 'admin_notas_pendientes', name: 'Notas Pendientes', cat: 'Notas', icon: '⏳', count: notasPendientes.length },
    { id: 'consulta_notas', name: 'Consulta de Notas', cat: 'Notas', icon: '🔍' },
    { id: 'notas_pasadas', name: 'Notas Pasadas / Histórico', cat: 'Notas', icon: '📜', count: notasDefinitivas.length },
    { id: 'correccion_notas', name: 'Corrección de Notas', cat: 'Notas', icon: '✏️' },
    { id: 'mensajeria', name: 'Mensajería', cat: 'Comunicaciones', icon: '✉️', count: mensajes.length },
    { id: 'auditoria', name: 'Auditoría del Sistema', cat: 'Seguridad', icon: '🛡️', count: auditorias.length },
    { id: 'visita', name: 'Movimientos de Visitas', cat: 'Seguridad', icon: '👁️', count: visitas.length },
    { id: 'titulos_relaciones_materias', name: 'Títulos y Mallas', cat: 'Grados', icon: '🎓' },
    { id: 'editar_accesos', name: 'Niveles de Acceso', cat: 'Ajustes', icon: '🔐' },
    { id: 'valores_predefinidos', name: 'Valores Predefinidos', cat: 'Ajustes', icon: '⚙️' },
    { id: 'tipo_pago', name: 'Tipos de Pago', cat: 'Ajustes', icon: '🏷️', count: tiposPago.length },
    { id: 'tipos_horario', name: 'Tipos de Horario', cat: 'Ajustes', icon: '⏱️', count: tiposHorario.length },
    { id: 'gestion_horario_personal', name: 'Horario Personal', cat: 'Ajustes', icon: '🗓️' },
  ];

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="text-center">
          <div className="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
          <p className="text-sm font-medium text-slate-600">Cargando Panel de Administración Super Usuario...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col">
      <RoleNavbar
        user={user}
        roleTitle="Super Admin Master"
        roleBadgeColor="bg-purple-100 text-purple-900 border-purple-300"
      />

      {/* Alertas */}
      {msgExito && (
        <div className="bg-emerald-600 text-white px-4 py-2.5 text-xs font-semibold flex justify-between items-center shadow-sm">
          <span>✅ {msgExito}</span>
          <button onClick={() => setMsgExito(null)} className="font-bold cursor-pointer">✕</button>
        </div>
      )}
      {msgError && (
        <div className="bg-red-600 text-white px-4 py-2.5 text-xs font-semibold flex justify-between items-center shadow-sm">
          <span>⚠️ {msgError}</span>
          <button onClick={() => setMsgError(null)} className="font-bold cursor-pointer">✕</button>
        </div>
      )}

      {/* Sub-Header Super Admin con CRUD bar */}
      <div className="bg-slate-900 text-white border-b border-slate-800">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <div className="flex items-center gap-3">
            <span className="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
            <div>
              <span className="text-[10px] font-bold uppercase tracking-wider text-purple-400 block">
                Privilegios Totales: Super Administrador (CRUD Habilitado)
              </span>
              <span className="text-sm font-extrabold text-white">
                {all30Modules.find((m) => m.id === currentModule)?.name || 'Todos los Módulos'}
              </span>
            </div>
          </div>

          <div className="flex flex-wrap items-center gap-2">
            <button
              onClick={() => setCurrentModule('resumen')}
              className={`px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer ${
                currentModule === 'resumen' ? 'bg-purple-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'
              }`}
            >
              30 Módulos
            </button>
            <button
              onClick={() => {
                setModoModal('create');
                setFormEstudiante({ id: 0, idusuario: '', nombre: '', email: '', tlf: '', cel: '', carrera: '1', status: 1, direccion: '' });
                setModalAbierto('modal_estudiante');
              }}
              className="px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition cursor-pointer"
            >
              + Nuevo Estudiante
            </button>
            <button
              onClick={() => {
                setModoModal('create');
                setFormCarrera({ id_carrera: 0, cod_carrera: '', nombre_carrera: '', duracion_anios: 4, titulo_obtenido: '', activa: 1 });
                setModalAbierto('modal_carrera');
              }}
              className="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-500 text-white transition cursor-pointer"
            >
              + Nueva Carrera
            </button>
            <button
              onClick={() => {
                setModoModal('create');
                setFormMateria({ id_materia: 0, codigo_materia: '', nombre_materia: '', creditos: 3, id_trayecto: 1 });
                setModalAbierto('modal_materia');
              }}
              className="px-3 py-1.5 rounded-lg text-xs font-bold bg-teal-600 hover:bg-teal-500 text-white transition cursor-pointer"
            >
              + Nueva Materia
            </button>
          </div>
        </div>
      </div>

      {/* Contenido Principal */}
      <main className="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 flex-1">
        {/* RESUMEN DE 30 MÓDULOS */}
        {currentModule === 'resumen' && (
          <div>
            <div className="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <div>
                <h1 className="text-xl font-extrabold text-slate-900 tracking-tight">
                  Panel de Control Maestro (30 Módulos de Super Usuario)
                </h1>
                <p className="text-xs text-slate-500">
                  Operatividad completa: Crear, Ver, Editar y Eliminar registros en tiempo real
                </p>
              </div>

              <input
                type="text"
                placeholder="Buscar módulo por nombre o categoría..."
                value={filtroTexto}
                onChange={(e) => setFiltroTexto(e.target.value)}
                className="px-3 py-1.5 text-xs rounded-lg border border-slate-300 bg-white w-72 focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
              {all30Modules
                .filter((m) => m.name.toLowerCase().includes(filtroTexto.toLowerCase()) || m.cat.toLowerCase().includes(filtroTexto.toLowerCase()))
                .map((m) => (
                  <button
                    key={m.id}
                    onClick={() => setCurrentModule(m.id)}
                    className="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-purple-500 transition text-left flex flex-col justify-between group cursor-pointer"
                  >
                    <div>
                      <div className="flex justify-between items-start mb-2">
                        <span className="text-2xl p-1.5 rounded-lg bg-slate-50 group-hover:scale-110 transition">
                          {m.icon}
                        </span>
                        {m.count !== undefined && (
                          <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700">
                            {m.count}
                          </span>
                        )}
                      </div>
                      <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-0.5">
                        {m.cat}
                      </span>
                      <h3 className="font-bold text-slate-800 text-xs group-hover:text-purple-700 leading-snug">
                        {m.name}
                      </h3>
                    </div>

                    <div className="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-purple-600">
                      <span>Gestionar CRUD</span>
                      <span className="group-hover:translate-x-1 transition">→</span>
                    </div>
                  </button>
                ))}
            </div>
          </div>
        )}

        {/* 1. MÓDULO: ESTUDIANTES (CRUD COMPLETO) */}
        {(currentModule === 'estudiantes' || currentModule === 'agregar_estudiante') && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">🎓 Gestión de Estudiantes (CRUD)</h2>
                <p className="text-xs text-slate-500">Crear, actualizar datos, cambiar estado y eliminar estudiantes</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormEstudiante({ id: 0, idusuario: '', nombre: '', email: '', tlf: '', cel: '', carrera: '1', status: 1, direccion: '' });
                    setModalAbierto('modal_estudiante');
                  }}
                  className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 cursor-pointer"
                >
                  + Agregar Estudiante
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="p-4 border-b border-slate-100 flex gap-2">
              <input
                type="text"
                placeholder="Buscar cédula, nombre o correo..."
                value={filtroTexto}
                onChange={(e) => setFiltroTexto(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && cargarEstudiantes(filtroTexto)}
                className="px-3 py-1.5 text-xs rounded-lg border border-slate-300 w-72"
              />
              <button
                onClick={() => cargarEstudiantes(filtroTexto)}
                className="px-3 py-1.5 bg-slate-800 text-white text-xs font-semibold rounded-lg cursor-pointer"
              >
                Buscar
              </button>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Cédula</th>
                    <th className="py-3 px-4">Nombre Completo</th>
                    <th className="py-3 px-4">Correo</th>
                    <th className="py-3 px-4">Programa</th>
                    <th className="py-3 px-4">Teléfono</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {estudiantes.map((e) => (
                    <tr key={e.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold text-blue-900">{e.idusuario || e.username}</td>
                      <td className="py-3 px-4 font-semibold text-slate-900">{e.nombre}</td>
                      <td className="py-3 px-4 text-slate-500">{e.email}</td>
                      <td className="py-3 px-4">{e.carrera_nombre || 'No asignada'}</td>
                      <td className="py-3 px-4 font-mono text-[11px]">{e.cel || e.tlf || '-'}</td>
                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${
                          e.status === 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'
                        }`}>
                          {e.status === 1 ? 'Activo' : 'Inactivo'}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarEstudiante(e)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('estudiante', e.id, `al estudiante ${e.nombre}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 2. MÓDULO: CARRERAS & PENSUM (CRUD COMPLETO) */}
        {(currentModule === 'agregar_carrera' || currentModule === 'carrera_materias') && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">📚 Gestión de Carreras PNF (CRUD)</h2>
                <p className="text-xs text-slate-500">Crear nuevas carreras, modificar mallas y retirar programas</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormCarrera({ id_carrera: 0, cod_carrera: '', nombre_carrera: '', duracion_anios: 4, titulo_obtenido: '', activa: 1 });
                    setModalAbierto('modal_carrera');
                  }}
                  className="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 cursor-pointer"
                >
                  + Agregar Carrera
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Código</th>
                    <th className="py-3 px-4">Nombre de Carrera</th>
                    <th className="py-3 px-4">Título Otorgado</th>
                    <th className="py-3 px-4">Duración</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {carreras.map((c) => (
                    <tr key={c.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold text-blue-900">{c.codigo}</td>
                      <td className="py-3 px-4 font-bold text-slate-900">{c.nombre}</td>
                      <td className="py-3 px-4 text-slate-600">{c.titulo || 'Licenciatura/Ingeniería'}</td>
                      <td className="py-3 px-4">{c.duracion || 4} años ({Number(c.duracion || 4) * 2} semestres)</td>
                      <td className="py-3 px-4 text-center">
                        <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                          Activa
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarCarrera(c)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('carrera', c.id, `la carrera ${c.nombre}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 3. MÓDULO: ASIGNATURAS / MATERIAS (CRUD COMPLETO) */}
        {currentModule === 'materia' && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">📖 Catálogo de Asignaturas (CRUD)</h2>
                <p className="text-xs text-slate-500">Unidades curriculares, códigos y carga de créditos</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormMateria({ id_materia: 0, codigo_materia: '', nombre_materia: '', creditos: 3, id_trayecto: 1 });
                    setModalAbierto('modal_materia');
                  }}
                  className="px-3 py-1.5 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 cursor-pointer"
                >
                  + Agregar Asignatura
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Código</th>
                    <th className="py-3 px-4">Nombre de Asignatura</th>
                    <th className="py-3 px-4">Trayecto</th>
                    <th className="py-3 px-4">Unidades de Crédito</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {materias.map((m) => (
                    <tr key={m.id_materia} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold text-teal-800">{m.codigo_materia}</td>
                      <td className="py-3 px-4 font-semibold text-slate-900">{m.nombre_materia}</td>
                      <td className="py-3 px-4 font-medium">Trayecto {m.id_trayecto || 1}</td>
                      <td className="py-3 px-4 font-bold">{m.creditos || 3} UC</td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarMateria(m)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('materia', m.id_materia, `la materia ${m.nombre_materia}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 4. MÓDULO: DOCENTES (CRUD COMPLETO) */}
        {(currentModule === 'add_docente' || currentModule === 'asignar_secciones' || currentModule === 'asignacion_cursos' || currentModule === 'horarios_docentes') && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">👨‍🏫 Personal Docente (CRUD)</h2>
                <p className="text-xs text-slate-500">Gestión de profesores, asignación horaria y datos de contacto</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormDocente({ id: 0, idusuario: '', nombre: '', email: '', tlf: '', cel: '', status: 1 });
                    setModalAbierto('modal_docente');
                  }}
                  className="px-3 py-1.5 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 cursor-pointer"
                >
                  + Agregar Docente
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Cédula / Usuario</th>
                    <th className="py-3 px-4">Nombre Completo</th>
                    <th className="py-3 px-4">Correo Institucional</th>
                    <th className="py-3 px-4">Teléfono</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {docentes.map((d) => (
                    <tr key={d.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold text-blue-900">{d.idusuario || d.username}</td>
                      <td className="py-3 px-4 font-semibold text-slate-900">{d.nombre}</td>
                      <td className="py-3 px-4 text-slate-500">{d.email}</td>
                      <td className="py-3 px-4 font-mono text-[11px]">{d.cel || d.tlf || '-'}</td>
                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${
                          d.status === 1 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'
                        }`}>
                          {d.status === 1 ? 'Activo' : 'Inactivo'}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarDocente(d)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('docente', d.id, `al docente ${d.nombre}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 5. MÓDULO: PERIODOS ACADÉMICOS (CRUD COMPLETO) */}
        {currentModule === 'periodos_academicos' && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">📅 Periodos Académicos (CRUD)</h2>
                <p className="text-xs text-slate-500">Crear lapsos, fechas de inicio y cierre, y activar periodo actual</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormPeriodo({ id_periodo: 0, nombre_periodo: '', fecha_inicio: '', fecha_fin: '', activo: 1 });
                    setModalAbierto('modal_periodo');
                  }}
                  className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 cursor-pointer"
                >
                  + Agregar Período
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Nombre Período</th>
                    <th className="py-3 px-4">Fecha Inicio</th>
                    <th className="py-3 px-4">Fecha Fin</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {periodos.map((p) => (
                    <tr key={p.id_periodo} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold">#{p.id_periodo}</td>
                      <td className="py-3 px-4 font-bold text-slate-900 text-sm">{p.nombre_periodo}</td>
                      <td className="py-3 px-4 font-mono">{p.fecha_inicio}</td>
                      <td className="py-3 px-4 font-mono">{p.fecha_fin}</td>
                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold ${
                          p.activo ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'
                        }`}>
                          {p.activo ? '● ACTIVO' : 'Histórico'}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarPeriodo(p)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('periodo', p.id_periodo, `el período ${p.nombre_periodo}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 6. MÓDULO: TIPOS DE PAGO (CRUD COMPLETO) */}
        {currentModule === 'tipo_pago' && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">🏷️ Tipos de Pago y Aranceles (CRUD)</h2>
                <p className="text-xs text-slate-500">Crear aranceles, modificar montos y deshabilitar conceptos</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormTipoPago({ id: 0, tipopago: '', precio: 0, status: 1 });
                    setModalAbierto('modal_tipo_pago');
                  }}
                  className="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 cursor-pointer"
                >
                  + Agregar Arancel
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Concepto de Pago</th>
                    <th className="py-3 px-4">Monto / Precio ($)</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {tiposPago.map((tp) => (
                    <tr key={tp.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold">#{tp.id}</td>
                      <td className="py-3 px-4 font-semibold text-slate-900">{tp.tipopago}</td>
                      <td className="py-3 px-4 font-mono font-bold text-emerald-700 text-sm">${Number(tp.precio).toFixed(2)}</td>
                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${
                          tp.status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'
                        }`}>
                          {tp.status ? 'Activo' : 'Desactivado'}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarTipoPago(tp)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('tipo_pago', tp.id, `el tipo de pago ${tp.tipopago}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 7. MÓDULO: TIPOS DE HORARIO (CRUD COMPLETO) */}
        {(currentModule === 'tipos_horario' || currentModule === 'gestion_horario_personal') && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">⏱️ Tipos de Dedicación y Horarios (CRUD)</h2>
                <p className="text-xs text-slate-500">Configuración de dedicación exclusiva, tiempo completo y convencional</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormTipoHorario({ id: 0, nombre: '', horas_academicas: 16, horas_atendiendo: 14 });
                    setModalAbierto('modal_tipo_horario');
                  }}
                  className="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-semibold hover:bg-indigo-700 cursor-pointer"
                >
                  + Agregar Dedicación
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Régimen de Dedicación</th>
                    <th className="py-3 px-4">Horas Académicas</th>
                    <th className="py-3 px-4">Horas Atención</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {tiposHorario.map((th) => (
                    <tr key={th.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold">#{th.id}</td>
                      <td className="py-3 px-4 font-bold text-slate-900">{th.nombre}</td>
                      <td className="py-3 px-4 font-mono">{th.horas_academicas} horas</td>
                      <td className="py-3 px-4 font-mono">{th.horas_atendiendo} horas</td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => abrirEditarTipoHorario(th)}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('tipo_horario', th.id, `el tipo de horario ${th.nombre}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 8. MÓDULO: GESTIÓN DE SECCIONES (CRUD COMPLETO) */}
        {currentModule === 'gestion_seccion' && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">🏛️ Gestión de Secciones (CRUD)</h2>
                <p className="text-xs text-slate-500">Apertura de secciones, turnos, capacidades máximas y eliminación</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => {
                    setModoModal('create');
                    setFormSeccion({ id_seccion: 0, codigo_seccion: '', id_carrera: 1, id_trayecto: 1, id_periodo: 5, capacidad_maxima: 35, turno: 'Diurno' });
                    setModalAbierto('modal_seccion');
                  }}
                  className="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 cursor-pointer"
                >
                  + Nueva Sección
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Código</th>
                    <th className="py-3 px-4">Carrera</th>
                    <th className="py-3 px-4">Trayecto</th>
                    <th className="py-3 px-4">Turno</th>
                    <th className="py-3 px-4">Capacidad</th>
                    <th className="py-3 px-4">Período</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {secciones.map((s) => (
                    <tr key={s.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold text-blue-900">{s.codigo}</td>
                      <td className="py-3 px-4">{s.carrera}</td>
                      <td className="py-3 px-4 font-semibold">Trayecto {s.trayecto}</td>
                      <td className="py-3 px-4">{s.turno}</td>
                      <td className="py-3 px-4">{s.capacidad} estudiantes</td>
                      <td className="py-3 px-4 font-medium text-slate-500">{s.periodo}</td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          <button
                            onClick={() => {
                              setModoModal('update');
                              setFormSeccion({
                                id_seccion: s.id,
                                codigo_seccion: s.codigo,
                                id_carrera: 1,
                                id_trayecto: s.trayecto || 1,
                                id_periodo: 5,
                                capacidad_maxima: s.capacidad || 35,
                                turno: s.turno || 'Diurno',
                              });
                              setModalAbierto('modal_seccion');
                            }}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Editar
                          </button>
                          <button
                            onClick={() => confirmarEliminar('seccion', s.id, `la sección ${s.codigo}`)}
                            className="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️ Eliminar
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 9. MÓDULO: REGISTRO DE PAGOS (CRUD COMPLETO) */}
        {currentModule === 'registro_pagos' && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">💵 Registro y Control de Pagos (CRUD)</h2>
                <p className="text-xs text-slate-500">Aprobar, rechazar, registrar manual y eliminar pagos</p>
              </div>
              <div className="flex gap-2">
                <button
                  onClick={() => setModalAbierto('modal_pago')}
                  className="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 cursor-pointer"
                >
                  + Registrar Pago Manual
                </button>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">ID</th>
                    <th className="py-3 px-4">Estudiante / C.I</th>
                    <th className="py-3 px-4">Concepto</th>
                    <th className="py-3 px-4">Monto</th>
                    <th className="py-3 px-4">Método / Ref</th>
                    <th className="py-3 px-4 text-center">Estado</th>
                    <th className="py-3 px-4 text-center">Acciones CRUD</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {pagos.map((p) => (
                    <tr key={p.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-mono font-bold">#{p.id}</td>
                      <td className="py-3 px-4">
                        <span className="font-semibold text-slate-900 block">{p.estudiante_nombre}</span>
                        <span className="text-[10px] text-slate-400">{p.estudiante_cedula}</span>
                      </td>
                      <td className="py-3 px-4">{p.tipo_pago}</td>
                      <td className="py-3 px-4 font-mono font-bold text-slate-900">${Number(p.monto).toFixed(2)}</td>
                      <td className="py-3 px-4">
                        <span className="block font-medium">{p.metodo_pago}</span>
                        <span className="text-[10px] text-slate-400">{p.referencia || 'Sin referencia'}</span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                          p.status_pago === 'aprobado' ? 'bg-emerald-100 text-emerald-800' : p.status_pago === 'rechazado' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800'
                        }`}>
                          {p.status_pago}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <div className="flex justify-center gap-1.5">
                          {p.status_pago !== 'aprobado' && (
                            <button
                              onClick={() => {
                                fetch('/api/pagos', {
                                  method: 'POST',
                                  headers: { 'Content-Type': 'application/json' },
                                  body: JSON.stringify({ action: 'cambiar_estado', pago_id: p.id, nuevo_estado: 'aprobado' }),
                                }).then(() => { setMsgExito(`Pago #${p.id} aprobado.`); cargarPagos(); });
                              }}
                              className="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded text-[11px] font-bold cursor-pointer"
                            >
                              Aprobar
                            </button>
                          )}
                          <button
                            onClick={() => {
                              if (confirm(`¿Eliminar pago #${p.id}?`)) {
                                fetch('/api/pagos', {
                                  method: 'POST',
                                  headers: { 'Content-Type': 'application/json' },
                                  body: JSON.stringify({ action: 'eliminar', pago_id: p.id }),
                                }).then(() => { setMsgExito(`Pago #${p.id} eliminado.`); cargarPagos(); });
                              }
                            }}
                            className="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded text-[11px] font-bold cursor-pointer"
                          >
                            🗑️
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}

        {/* 10. MÓDULO: NOTAS PENDIENTES & CORRECCIÓN */}
        {(currentModule === 'admin_notas_pendientes' || currentModule === 'consulta_notas' || currentModule === 'notas_pasadas' || currentModule === 'correccion_notas') && (
          <div className="space-y-6">
            <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <div>
                  <h2 className="text-base font-bold text-slate-900">⏳ Calificaciones Pendientes y Corrección Directa</h2>
                  <p className="text-xs text-slate-500">Supervisar cortes, validar actas definitivas o modificar notas</p>
                </div>
                <button
                  onClick={() => setCurrentModule('resumen')}
                  className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
                >
                  Volver a Módulos
                </button>
              </div>

              <div className="overflow-x-auto">
                <table className="w-full text-left text-xs text-slate-600">
                  <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                    <tr>
                      <th className="py-3 px-4">Estudiante</th>
                      <th className="py-3 px-4">Asignatura</th>
                      <th className="py-3 px-4">Docente</th>
                      <th className="py-3 px-4 text-center">T1</th>
                      <th className="py-3 px-4 text-center">T2</th>
                      <th className="py-3 px-4 text-center">T3</th>
                      <th className="py-3 px-4 text-center">Acciones CRUD</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {notasPendientes.map((n) => (
                      <tr key={n.id} className="hover:bg-slate-50">
                        <td className="py-3 px-4 font-semibold text-slate-900">{n.estudiante_nombre}</td>
                        <td className="py-3 px-4">{n.nombre_materia}</td>
                        <td className="py-3 px-4">{n.docente_nombre}</td>
                        <td className="py-3 px-4 text-center font-bold">{n.trayecto_1 ?? '-'}</td>
                        <td className="py-3 px-4 text-center font-bold">{n.trayecto_2 ?? '-'}</td>
                        <td className="py-3 px-4 text-center font-bold">{n.trayecto_3 ?? '-'}</td>
                        <td className="py-3 px-4 text-center">
                          <button
                            onClick={() => {
                              fetch('/api/notas', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ action: 'aprobar_pendiente', nota_id: n.id }),
                              }).then(() => { setMsgExito('Nota aprobada y registrada en definitivas.'); cargarNotas(); });
                            }}
                            className="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold cursor-pointer"
                          >
                            Aprobar
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Histórico y Corrección de Definitivas */}
            <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
              <div className="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 className="text-base font-bold text-slate-900">📜 Historial Definitivo & Corrección de Notas</h3>
                <span className="text-xs text-slate-400">Total: {notasDefinitivas.length} registros</span>
              </div>
              <div className="overflow-x-auto">
                <table className="w-full text-left text-xs text-slate-600">
                  <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                    <tr>
                      <th className="py-3 px-4">Estudiante</th>
                      <th className="py-3 px-4">Asignatura</th>
                      <th className="py-3 px-4">Docente</th>
                      <th className="py-3 px-4 text-center">T1</th>
                      <th className="py-3 px-4 text-center">T2</th>
                      <th className="py-3 px-4 text-center">T3</th>
                      <th className="py-3 px-4 text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-100">
                    {notasDefinitivas.map((d) => (
                      <tr key={d.id} className="hover:bg-slate-50">
                        <td className="py-3 px-4 font-semibold text-slate-900">{d.estudiante_nombre}</td>
                        <td className="py-3 px-4">{d.nombre_materia}</td>
                        <td className="py-3 px-4">{d.docente_nombre}</td>
                        <td className="py-3 px-4 text-center font-bold">{d.trayecto_1 ?? '-'}</td>
                        <td className="py-3 px-4 text-center font-bold">{d.trayecto_2 ?? '-'}</td>
                        <td className="py-3 px-4 text-center font-bold">{d.trayecto_3 ?? '-'}</td>
                        <td className="py-3 px-4 text-center">
                          <button
                            onClick={() => {
                              const t1 = prompt('Nuevo valor Trimestre 1:', d.trayecto_1 ?? '0');
                              if (t1 === null) return;
                              const t2 = prompt('Nuevo valor Trimestre 2:', d.trayecto_2 ?? '0');
                              if (t2 === null) return;
                              const t3 = prompt('Nuevo valor Trimestre 3:', d.trayecto_3 ?? '0');
                              if (t3 === null) return;

                              fetch('/api/notas', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                  action: 'corregir_definitiva',
                                  id_nota: d.id,
                                  trayecto_0: d.trayecto_0,
                                  trayecto_1: Number(t1),
                                  trayecto_2: Number(t2),
                                  trayecto_3: Number(t3),
                                  trayecto_4: d.trayecto_4,
                                }),
                              }).then(() => { setMsgExito('Nota corregida exitosamente.'); cargarNotas(); });
                            }}
                            className="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 rounded text-[11px] font-bold cursor-pointer"
                          >
                            ✏️ Corregir
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* 11. MÓDULO: NIVELES DE ACCESO & VALORES PREDEFINIDOS */}
        {(currentModule === 'editar_accesos' || currentModule === 'valores_predefinidos') && (
          <div className="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
            <div className="p-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
              <div>
                <h2 className="text-base font-bold text-slate-900">🔐 Modificación de Accesos y Privilegios</h2>
                <p className="text-xs text-slate-500">Concesión y revocación de permisos granulares por usuario</p>
              </div>
              <button
                onClick={() => setCurrentModule('resumen')}
                className="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold"
              >
                Volver a Módulos
              </button>
            </div>

            <div className="overflow-x-auto">
              <table className="w-full text-left text-xs text-slate-600">
                <thead className="bg-slate-100 text-[11px] font-bold uppercase text-slate-600 border-b border-slate-200">
                  <tr>
                    <th className="py-3 px-4">Usuario</th>
                    <th className="py-3 px-4">C.I / Identificador</th>
                    <th className="py-3 px-4 text-center">Admin</th>
                    <th className="py-3 px-4 text-center">Super User</th>
                    <th className="py-3 px-4 text-center">Docente</th>
                    <th className="py-3 px-4 text-center">Estudiante</th>
                    <th className="py-3 px-4 text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {docentes.slice(0, 15).map((u) => (
                    <tr key={u.id} className="hover:bg-slate-50">
                      <td className="py-3 px-4 font-semibold text-slate-900">{u.nombre}</td>
                      <td className="py-3 px-4 font-mono">{u.username}</td>
                      <td className="py-3 px-4 text-center">
                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">Configurado</span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800">Super User</span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Docente</span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Estudiante</span>
                      </td>
                      <td className="py-3 px-4 text-center">
                        <button
                          onClick={() => {
                            const conf = confirm(`¿Alternar privilegios de administrador para ${u.nombre}?`);
                            if (conf) {
                              fetch('/api/academico', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                  action: 'editar_accesos',
                                  usuario_id: u.id,
                                  admin: true,
                                  super_user: true,
                                  docente: true,
                                  estudiante: true,
                                  director: true,
                                }),
                              }).then(() => { setMsgExito('Accesos actualizados exitosamente.'); refrescarTodo(); });
                            }
                          }}
                          className="px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded text-[11px] font-bold cursor-pointer"
                        >
                          Modificar Accesos
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        )}
      </main>

      {/* =========================================================
          MODALES CRUD (CREATE / UPDATE) PARA TODAS LAS ENTIDADES
         ========================================================= */}

      {/* MODAL 1: ESTUDIANTE (CREATE & UPDATE) */}
      {modalAbierto === 'modal_estudiante' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Nuevo Estudiante' : 'Editar Estudiante'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('estudiante', modoModal, formEstudiante);
              }}
              className="space-y-3"
            >
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Cédula</label>
                  <input
                    type="text"
                    required
                    placeholder="V-12345678"
                    value={formEstudiante.idusuario}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, idusuario: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre Completo</label>
                  <input
                    type="text"
                    required
                    placeholder="Nombre y Apellido"
                    value={formEstudiante.nombre}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, nombre: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Correo Electrónico</label>
                  <input
                    type="email"
                    value={formEstudiante.email}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, email: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Teléfono</label>
                  <input
                    type="text"
                    value={formEstudiante.tlf}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, tlf: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Carrera / Programa</label>
                  <select
                    value={formEstudiante.carrera}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, carrera: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    {carreras.map((c) => (
                      <option key={c.id} value={c.id}>{c.nombre}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Estado</label>
                  <select
                    value={formEstudiante.status}
                    onChange={(e) => setFormEstudiante({ ...formEstudiante, status: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    <option value={1}>Activo</option>
                    <option value={0}>Inactivo / Retirado</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Dirección de Residencia</label>
                <input
                  type="text"
                  value={formEstudiante.direccion}
                  onChange={(e) => setFormEstudiante({ ...formEstudiante, direccion: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                />
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Estudiante' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 2: DOCENTE (CREATE & UPDATE) */}
      {modalAbierto === 'modal_docente' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Nuevo Docente' : 'Editar Datos del Docente'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('docente', modoModal, formDocente);
              }}
              className="space-y-3"
            >
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Cédula</label>
                  <input
                    type="text"
                    required
                    placeholder="V-12345678"
                    value={formDocente.idusuario}
                    onChange={(e) => setFormDocente({ ...formDocente, idusuario: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre Completo</label>
                  <input
                    type="text"
                    required
                    placeholder="Profesor(a)..."
                    value={formDocente.nombre}
                    onChange={(e) => setFormDocente({ ...formDocente, nombre: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Correo Electrónico</label>
                  <input
                    type="email"
                    value={formDocente.email}
                    onChange={(e) => setFormDocente({ ...formDocente, email: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Teléfono</label>
                  <input
                    type="text"
                    value={formDocente.tlf}
                    onChange={(e) => setFormDocente({ ...formDocente, tlf: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Estado</label>
                <select
                  value={formDocente.status}
                  onChange={(e) => setFormDocente({ ...formDocente, status: Number(e.target.value) })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                >
                  <option value={1}>Activo</option>
                  <option value={0}>Inactivo / Reposo</option>
                </select>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-teal-600 hover:bg-teal-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Registrar Docente' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 3: CARRERA (CREATE & UPDATE) */}
      {modalAbierto === 'modal_carrera' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Nueva Carrera PNF' : 'Editar Programa de Formación'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('carrera', modoModal, formCarrera);
              }}
              className="space-y-3"
            >
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Código de Carrera</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej: PNF-INF"
                    value={formCarrera.cod_carrera}
                    onChange={(e) => setFormCarrera({ ...formCarrera, cod_carrera: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre de la Carrera</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej: PNF en Informática"
                    value={formCarrera.nombre_carrera}
                    onChange={(e) => setFormCarrera({ ...formCarrera, nombre_carrera: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Título Otorgado</label>
                  <input
                    type="text"
                    required
                    placeholder="Ingeniero en Informática"
                    value={formCarrera.titulo_obtenido}
                    onChange={(e) => setFormCarrera({ ...formCarrera, titulo_obtenido: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Duración (Años)</label>
                  <input
                    type="number"
                    min={1}
                    max={6}
                    value={formCarrera.duracion_anios}
                    onChange={(e) => setFormCarrera({ ...formCarrera, duracion_anios: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-indigo-600 hover:bg-indigo-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Carrera' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 4: MATERIA (CREATE & UPDATE) */}
      {modalAbierto === 'modal_materia' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Nueva Asignatura' : 'Editar Asignatura'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('materia', modoModal, formMateria);
              }}
              className="space-y-3"
            >
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Código Asignatura</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej: MAT-101"
                    value={formMateria.codigo_materia}
                    onChange={(e) => setFormMateria({ ...formMateria, codigo_materia: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre Asignatura</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej: Algoritmos y Programación"
                    value={formMateria.nombre_materia}
                    onChange={(e) => setFormMateria({ ...formMateria, nombre_materia: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Unidades de Crédito</label>
                  <input
                    type="number"
                    min={1}
                    max={12}
                    value={formMateria.creditos}
                    onChange={(e) => setFormMateria({ ...formMateria, creditos: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Trayecto Académico</label>
                  <select
                    value={formMateria.id_trayecto}
                    onChange={(e) => setFormMateria({ ...formMateria, id_trayecto: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    <option value={1}>Trayecto 1</option>
                    <option value={2}>Trayecto 2</option>
                    <option value={3}>Trayecto 3</option>
                    <option value={4}>Trayecto 4</option>
                  </select>
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-teal-600 hover:bg-teal-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Asignatura' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 5: SECCIÓN (CREATE & UPDATE) */}
      {modalAbierto === 'modal_seccion' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Apertura de Sección' : 'Editar Sección'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('seccion', modoModal, formSeccion);
              }}
              className="space-y-3"
            >
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Código de Sección</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej: INF-101-D"
                    value={formSeccion.codigo_seccion}
                    onChange={(e) => setFormSeccion({ ...formSeccion, codigo_seccion: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Carrera</label>
                  <select
                    value={formSeccion.id_carrera}
                    onChange={(e) => setFormSeccion({ ...formSeccion, id_carrera: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    {carreras.map((c) => (
                      <option key={c.id} value={c.id}>{c.nombre}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Trayecto</label>
                  <input
                    type="number"
                    min={1}
                    max={5}
                    value={formSeccion.id_trayecto}
                    onChange={(e) => setFormSeccion({ ...formSeccion, id_trayecto: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Capacidad Máxima</label>
                  <input
                    type="number"
                    min={5}
                    max={60}
                    value={formSeccion.capacidad_maxima}
                    onChange={(e) => setFormSeccion({ ...formSeccion, capacidad_maxima: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Turno</label>
                  <select
                    value={formSeccion.turno}
                    onChange={(e) => setFormSeccion({ ...formSeccion, turno: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    <option value="Diurno">Diurno</option>
                    <option value="Nocturno">Nocturno</option>
                    <option value="Fin de Semana">Fin de Semana</option>
                  </select>
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Sección' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 6: TIPO DE PAGO (CREATE & UPDATE) */}
      {modalAbierto === 'modal_tipo_pago' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Tipo de Pago / Arancel' : 'Editar Arancel'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('tipo_pago', modoModal, formTipoPago);
              }}
              className="space-y-3"
            >
              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Concepto de Pago</label>
                <input
                  type="text"
                  required
                  placeholder="Ej: Constancia Certificada de Notas"
                  value={formTipoPago.tipopago}
                  onChange={(e) => setFormTipoPago({ ...formTipoPago, tipopago: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Precio ($)</label>
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={formTipoPago.precio}
                    onChange={(e) => setFormTipoPago({ ...formTipoPago, precio: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Estado</label>
                  <select
                    value={formTipoPago.status}
                    onChange={(e) => setFormTipoPago({ ...formTipoPago, status: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    <option value={1}>Habilitado</option>
                    <option value={0}>Deshabilitado</option>
                  </select>
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-emerald-600 hover:bg-emerald-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Arancel' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 7: TIPO DE HORARIO (CREATE & UPDATE) */}
      {modalAbierto === 'modal_tipo_horario' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Tipo de Horario' : 'Editar Dedicación Horaria'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('tipo_horario', modoModal, formTipoHorario);
              }}
              className="space-y-3"
            >
              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre Dedicación</label>
                <input
                  type="text"
                  required
                  placeholder="Ej: Dedicación Exclusiva"
                  value={formTipoHorario.nombre}
                  onChange={(e) => setFormTipoHorario({ ...formTipoHorario, nombre: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Horas Académicas</label>
                  <input
                    type="number"
                    min={0}
                    value={formTipoHorario.horas_academicas}
                    onChange={(e) => setFormTipoHorario({ ...formTipoHorario, horas_academicas: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Horas de Atención</label>
                  <input
                    type="number"
                    min={0}
                    value={formTipoHorario.horas_atendiendo}
                    onChange={(e) => setFormTipoHorario({ ...formTipoHorario, horas_atendiendo: Number(e.target.value) })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-indigo-600 hover:bg-indigo-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Horario' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 8: PERIODO ACADÉMICO (CREATE & UPDATE) */}
      {modalAbierto === 'modal_periodo' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">
              {modoModal === 'create' ? 'Agregar Periodo Académico' : 'Editar Periodo Académico'}
            </h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                ejecutarCrud('periodo', modoModal, formPeriodo);
              }}
              className="space-y-3"
            >
              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Nombre Periodo</label>
                <input
                  type="text"
                  required
                  placeholder="Ej: 2026-2"
                  value={formPeriodo.nombre_periodo}
                  onChange={(e) => setFormPeriodo({ ...formPeriodo, nombre_periodo: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Fecha Inicio</label>
                  <input
                    type="date"
                    required
                    value={formPeriodo.fecha_inicio}
                    onChange={(e) => setFormPeriodo({ ...formPeriodo, fecha_inicio: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Fecha Fin</label>
                  <input
                    type="date"
                    required
                    value={formPeriodo.fecha_fin}
                    onChange={(e) => setFormPeriodo({ ...formPeriodo, fecha_fin: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
              </div>

              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Estado</label>
                <select
                  value={formPeriodo.activo}
                  onChange={(e) => setFormPeriodo({ ...formPeriodo, activo: Number(e.target.value) })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                >
                  <option value={1}>Activo (Período en curso)</option>
                  <option value={0}>Inactivo / Pasado</option>
                </select>
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded font-semibold cursor-pointer"
                >
                  {modoModal === 'create' ? 'Crear Período' : 'Guardar Cambios'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL 9: PAGO MANUAL */}
      {modalAbierto === 'modal_pago' && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <h3 className="text-base font-bold text-slate-900 mb-4">Registrar Pago Manual</h3>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                fetch('/api/pagos', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ action: 'registrar', ...formPago }),
                }).then(async (r) => {
                  const d = await r.json();
                  if (d.ok) { setMsgExito('Pago registrado y aprobado.'); setModalAbierto(null); cargarPagos(); }
                  else setMsgError(d.error);
                });
              }}
              className="space-y-3"
            >
              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Estudiante</label>
                <select
                  required
                  value={formPago.estudiante_id}
                  onChange={(e) => setFormPago({ ...formPago, estudiante_id: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                >
                  <option value="">Seleccione estudiante...</option>
                  {estudiantes.map((est) => (
                    <option key={est.id} value={est.id}>{est.nombre} ({est.idusuario})</option>
                  ))}
                </select>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Monto ($)</label>
                  <input
                    type="number"
                    step="0.01"
                    required
                    value={formPago.monto}
                    onChange={(e) => setFormPago({ ...formPago, monto: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  />
                </div>
                <div>
                  <label className="text-xs font-semibold text-slate-700 block mb-1">Concepto</label>
                  <select
                    value={formPago.tipo_pago}
                    onChange={(e) => setFormPago({ ...formPago, tipo_pago: e.target.value })}
                    className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                  >
                    {tiposPago.map((tp) => (
                      <option key={tp.id} value={tp.tipopago}>{tp.tipopago}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="text-xs font-semibold text-slate-700 block mb-1">Referencia</label>
                <input
                  type="text"
                  placeholder="00012345"
                  value={formPago.referencia}
                  onChange={(e) => setFormPago({ ...formPago, referencia: e.target.value })}
                  className="w-full px-3 py-2 text-xs rounded border border-slate-300"
                />
              </div>

              <div className="flex justify-end gap-2 pt-3 border-t border-slate-100">
                <button
                  type="button"
                  onClick={() => setModalAbierto(null)}
                  className="px-3 py-1.5 text-xs text-slate-600 bg-slate-100 rounded cursor-pointer"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-1.5 text-xs text-white bg-emerald-600 hover:bg-emerald-700 rounded font-semibold cursor-pointer"
                >
                  Aprobar y Registrar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
