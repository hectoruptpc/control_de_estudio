-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-07-2026 a las 14:12:15
-- Versión del servidor: 8.0.46-0ubuntu0.24.04.3
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_tsu`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `id_user` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `first_name` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `numero` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `agenda`
--

INSERT INTO `agenda` (`id`, `id_user`, `first_name`, `numero`, `fecha`) VALUES
(77, '1780', 'Daniela', '04160524940', '2021-06-10 16:42:57'),
(78, '2', 'Jose', '04141448515', '2021-09-15 12:54:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprobaciones_avance`
--

CREATE TABLE `aprobaciones_avance` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_carrera` int NOT NULL,
  `trayecto_origen` int NOT NULL,
  `trayecto_destino` int NOT NULL,
  `aprobado_por` int DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL,
  `motivo` text COLLATE utf8mb3_spanish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` bigint NOT NULL,
  `usuario_id` int NOT NULL,
  `accion` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `tabla_afectada` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `registro_id` int DEFAULT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valores_antiguos` text COLLATE utf8mb4_spanish_ci,
  `valores_nuevos` text COLLATE utf8mb4_spanish_ci,
  `ip_origen` varchar(45) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_spanish_ci,
  `modulo_sistema` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(113, 2, 'UPDATE', 'users', 2, '2025-10-27 13:36:00', '{\"username\":\"V-12345678\",\"usuario\":1,\"estudiante\":1,\"docente\":1,\"admin\":1,\"super_user\":0,\"editar_user\":1,\"editar_nota\":1,\"editar_acceso\":1,\"editar_valores\":1,\"editar_estudiante\":1,\"agregar_estudiante\":1,\"agregar_docente\":1,\"editar_docente\":1,\"agregar_carrera\":1,\"agregar_materia\":1,\"editar_materia\":1,\"pagos\":1,\"auditoria\":1,\"secciones\":1,\"rela_materia_carrera\":1,\"periodos_academicos\":1,\"asig_secciones\":1,\"asig_cursos\":1,\"horarios\":1,\"gestion_director_carrera\":1,\"notas_cargadas\":1,\"consultar_notas\":1,\"consultar_notas_pasadas\":1,\"tipos_pago\":1,\"tipos_horario\":1,\"horario_personal\":1,\"respaldo_bd\":1,\"gestionar_carrera\":1,\"gestion_periodo_academico\":1,\"gestion_asig_cursos\":1,\"gestion_horario\":1,\"titulos_re_materia\":1,\"grado\":0,\"gestion_grado\":1}', '{\"usuario_afectado\":\"V-12345678\",\"usuario_afectado_id\":2,\"usuario_editor\":\"V-12345678\",\"usuario_editor_id\":\"2\",\"accesos_otorgados\":\"grado\",\"accesos_quitados\":\"\",\"total_otorgados\":1,\"total_quitados\":0,\"super_user_anterior\":0,\"super_user_nuevo\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Gestión de Permisos', 'Permisos actualizados para usuario: V-12345678 - Accesos OTORGADOS: grado'),
(114, 2, 'UPDATE', 'status', 0, '2025-10-27 14:04:02', '{\"id_anterior\":\"0\",\"valor_anterior\":\"Inactivo\"}', '{\"tabla\":\"status\",\"campo\":\"status\",\"id_nuevo\":\"0\",\"valor_nuevo\":\"Inactivos\",\"accion\":\"editar\",\"cambios\":\"status: Inactivo \\u2192 Inactivos\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en status: status: Inactivo → Inactivos'),
(115, 2, 'UPDATE', 'status', 0, '2025-10-27 14:04:49', '{\"id_anterior\":\"0\",\"valor_anterior\":\"Inactivos\"}', '{\"tabla\":\"status\",\"campo\":\"status\",\"id_nuevo\":\"0\",\"valor_nuevo\":\"Inactivo\",\"accion\":\"editar\",\"cambios\":\"status: Inactivos \\u2192 Inactivo\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en status: status: Inactivos → Inactivo'),
(116, 2, 'UPDATE', 'tipo_pago', 16, '2025-10-27 14:17:09', '{\"tipo_pago_anterior\":\"Autenticaci\\u00f3n de T\\u00edtulo\"}', '{\"tipo_pago_nuevo\":\"Autenticaci\\u00f3n de T\\u00edtuloss\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:17:09\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Pago', 'Tipo de pago actualizado: Autenticación de Título → Autenticación de Títuloss'),
(117, 2, 'UPDATE', 'tipo_pago', 16, '2025-10-27 14:17:16', '{\"tipo_pago_anterior\":\"Autenticaci\\u00f3n de T\\u00edtuloss\"}', '{\"tipo_pago_nuevo\":\"Autenticaci\\u00f3n de T\\u00edtulo\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:17:16\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Pago', 'Tipo de pago actualizado: Autenticación de Títuloss → Autenticación de Título'),
(118, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:39:13', '{\"nombre_anterior\":\"Convencional\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencionall\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:39:13\",\"cambios\":\"nombre: Convencional \\u2192 Convencionall\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencional → Convencionall'),
(119, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:39:38', '{\"nombre_anterior\":\"Convencionall\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencional\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:39:38\",\"cambios\":\"nombre: Convencionall \\u2192 Convencional\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencionall → Convencional'),
(120, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:42:45', '{\"nombre_anterior\":\"Convencional\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencionall\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:42:45\",\"cambios\":\"nombre: Convencional \\u2192 Convencionall\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencional → Convencionall'),
(121, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:42:53', '{\"nombre_anterior\":\"Convencionall\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencional\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:42:53\",\"cambios\":\"nombre: Convencionall \\u2192 Convencional\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencionall → Convencional'),
(122, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:44:38', '{\"nombre_anterior\":\"Convencional\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencionalll\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:44:38\",\"cambios\":\"nombre: Convencional \\u2192 Convencionalll\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencional → Convencionalll'),
(123, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:44:47', '{\"nombre_anterior\":\"Convencionalll\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convenciona\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:44:47\",\"cambios\":\"nombre: Convencionalll \\u2192 Convenciona\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencionalll → Convenciona'),
(124, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:44:53', '{\"nombre_anterior\":\"Convenciona\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencional\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:44:53\",\"cambios\":\"nombre: Convenciona \\u2192 Convencional\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convenciona → Convencional'),
(125, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:45:23', '{\"nombre_anterior\":\"Convencional\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencionall\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:45:23\",\"cambios\":\"nombre: Convencional \\u2192 Convencionall\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencional → Convencionall'),
(126, 2, 'UPDATE', 'tipos_horario', 4, '2025-10-27 14:45:30', '{\"nombre_anterior\":\"Convencionall\",\"horas_academicas_anterior\":\"7\",\"horas_atendiendo_anterior\":\"0\"}', '{\"nombre_nuevo\":\"Convencional\",\"horas_academicas_nuevo\":7,\"horas_atendiendo_nuevo\":0,\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:45:30\",\"cambios\":\"nombre: Convencionall \\u2192 Convencional\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Tipos de Horario', 'Tipo de horario actualizado: Convencionall → Convencional'),
(127, 2, 'UPDATE', 'tipo_horario_personal', 3, '2025-10-27 14:53:33', '{\"id_tipo_horario_anterior\":\"3\",\"horario_anterior_nombre\":\"Medio tiempo\"}', '{\"id_usuario\":\"2585\",\"usuario_nombre\":\"Alberto Lopez\",\"usuario_username\":\"alberto.lopez\",\"id_tipo_horario_nuevo\":\"4\",\"horario_nuevo_nombre\":\"Convencional\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\",\"fecha_actualizacion\":\"2025-10-27 14:53:33\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Horario Personal', 'Horario actualizado para usuario: Medio tiempo → Convencional (Alberto Lopez)'),
(128, 2, 'LOGIN', 'users', 2, '2025-10-28 09:30:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(129, 2, 'LOGIN', 'users', 2, '2025-10-28 11:27:10', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(130, 2, 'LOGIN', 'users', 2, '2025-10-28 12:22:22', NULL, '{\"username\":\"V-12345678\"}', '192.168.1.4', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(131, 2, 'LOGIN', 'users', 2, '2025-10-28 12:43:52', NULL, '{\"username\":\"V-12345678\"}', '192.168.1.4', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(132, 2, 'LOGIN', 'users', 2, '2025-10-28 13:18:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(133, 2, 'LOGIN', 'users', 2, '2025-10-28 13:21:10', NULL, '{\"username\":\"V-12345678\"}', '192.168.1.4', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(134, 2, 'CONSULTA', 'users', 5, '2025-10-28 15:09:15', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(135, 2, 'CONSULTA', 'users', 5, '2025-10-28 15:34:15', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(136, 2, 'LOGIN', 'users', 2, '2025-10-29 10:27:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(137, 2, 'LOGIN', 'users', 2, '2025-10-29 10:29:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(138, 2, 'LOGIN', 'users', 2, '2025-10-29 11:39:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(139, 2, 'LOGIN', 'users', 2, '2025-11-04 11:25:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(140, 2, 'LOGIN', 'users', 2, '2025-11-07 09:44:45', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(141, 2, 'LOGIN', 'users', 2, '2025-11-10 09:14:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(142, 4, 'LOGIN', 'users', 4, '2025-11-10 09:16:04', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(143, 5, 'LOGIN', 'users', 5, '2025-11-10 09:16:09', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(144, 5, 'LOGOUT', 'users', 5, '2025-11-10 09:16:59', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(145, 2, 'LOGIN', 'users', 2, '2025-11-10 09:17:01', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(146, 2, 'CONSULTA', 'users', 5, '2025-11-10 09:50:27', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(147, 2, 'CONSULTA', 'users', 5, '2025-11-10 10:33:31', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(148, 2, 'LOGIN', 'users', 2, '2025-11-10 11:35:02', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(149, 2, 'SELECT', 'carreras', 1, '2025-11-10 12:57:20', NULL, '{\"id_estudiante\":5,\"resultados\":1,\"carreras_encontradas\":[\"Informatica\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Carreras', 'Consulta de carreras del estudiante'),
(150, 2, 'SELECT', 'notas_definitivas', NULL, '2025-11-10 12:57:22', NULL, '{\"id_estudiante\":\"5\",\"id_carrera\":\"1\",\"resultados\":1,\"materias_con_notas\":[\"Introducci\\u00f3n a los Proyectos y al PNF\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de materias con notas del estudiante'),
(151, 2, 'SELECT', 'carreras', 1, '2025-11-10 12:57:22', NULL, '{\"id_estudiante\":\"5\",\"resultados\":1,\"carreras_encontradas\":[\"Informatica\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Carreras', 'Consulta de carreras del estudiante'),
(152, 2, 'SELECT', 'carreras', 1, '2025-11-10 12:57:24', NULL, '{\"id_estudiante\":\"5\",\"resultados\":1,\"carreras_encontradas\":[\"Informatica\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Carreras', 'Consulta de carreras del estudiante'),
(153, 2, 'SELECT', 'notas_definitivas', NULL, '2025-11-10 12:57:24', NULL, '{\"id_estudiante\":\"5\",\"id_carrera\":\"1\",\"resultados\":1,\"materias_con_notas\":[\"Introducci\\u00f3n a los Proyectos y al PNF\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de materias con notas del estudiante'),
(154, 2, 'SELECT', 'notas_definitivas', NULL, '2025-11-10 12:57:25', NULL, '{\"id_estudiante\":\"5\",\"id_materia\":\"9\",\"resultados\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de notas del estudiante por materia'),
(155, 2, 'SELECT', 'historial_cambios_notas', 194, '2025-11-10 12:57:25', NULL, '{\"registros_encontrados\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de historial de cambios de nota'),
(156, 2, 'UPDATE', 'notas_definitivas', 194, '2025-11-10 12:57:49', '{\"trayecto_0\":18}', '{\"trayecto_0\":\"14\",\"justificacion\":\"otra prueba\",\"id_admin\":\"2\",\"trayecto_afectado\":\"trayecto_0\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Edición exitosa de nota'),
(157, 2, 'SELECT', 'carreras', 1, '2025-11-10 12:57:49', NULL, '{\"id_estudiante\":\"5\",\"resultados\":1,\"carreras_encontradas\":[\"Informatica\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Carreras', 'Consulta de carreras del estudiante'),
(158, 2, 'SELECT', 'notas_definitivas', NULL, '2025-11-10 12:57:49', NULL, '{\"id_estudiante\":\"5\",\"id_carrera\":\"1\",\"resultados\":1,\"materias_con_notas\":[\"Introducci\\u00f3n a los Proyectos y al PNF\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de materias con notas del estudiante'),
(159, 2, 'SELECT', 'notas_definitivas', NULL, '2025-11-10 12:57:49', NULL, '{\"id_estudiante\":\"5\",\"id_materia\":\"9\",\"resultados\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de notas del estudiante por materia'),
(160, 2, 'SELECT', 'historial_cambios_notas', 194, '2025-11-10 12:57:49', NULL, '{\"registros_encontrados\":3}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Consulta de historial de cambios de nota'),
(161, 2, 'UPDATE', 'notas_definitivas', 194, '2025-11-10 13:03:02', '{\"trayecto_0\":14}', '{\"trayecto_0\":\"15\",\"justificacion\":\"prueba para auditoria\",\"id_admin\":\"2\",\"trayecto_afectado\":\"trayecto_0\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas', 'Edición exitosa de nota'),
(162, 2, 'LOGIN', 'users', 2, '2025-11-12 10:12:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(163, 2, 'LOGIN', 'users', 2, '2025-11-13 09:38:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(164, 2, 'LOGIN', 'users', 2, '2025-11-14 09:15:39', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(165, 2, 'LOGIN', 'users', 2, '2025-11-17 09:54:17', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(166, 2, 'LOGIN', 'users', 2, '2025-11-17 12:34:45', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(167, 2, 'LOGOUT', 'users', 2, '2025-11-17 12:38:51', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(168, 2, 'LOGIN', 'users', 2, '2025-11-17 12:38:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(169, 2, 'LOGOUT', 'users', 2, '2025-11-17 12:39:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(170, 2, 'LOGIN', 'users', 2, '2025-11-17 12:40:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(171, 2, 'SEARCH', 'users', 5, '2025-11-17 12:53:30', NULL, '{\"cedula\":\"V-30692052\",\"estudiante\":\"Hector\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Pagos', 'Búsqueda exitosa de estudiante por cédula'),
(172, 2, 'INSERT', 'pagos', 32, '2025-11-17 12:53:41', NULL, '{\"estudiante_id\":5,\"tipo_pago\":3,\"otro_concepto\":\"\",\"monto\":500,\"observaciones\":\"26458\",\"registrado_por\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Pagos', 'Registro de nuevo pago'),
(173, 2, 'SELECT', 'users', NULL, '2025-11-17 12:56:54', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":106}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(174, 2, 'CONSULTA', 'notas_definitivas', NULL, '2025-11-17 13:04:36', NULL, '{\"cantidad_grupos\":1,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(175, 2, 'LOGOUT', 'users', 2, '2025-11-17 13:09:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(176, 2, 'LOGIN', 'users', 2, '2025-11-17 13:12:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(177, 2, 'LOGOUT', 'users', 2, '2025-11-17 13:14:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(178, 2, 'LOGIN', 'users', 2, '2025-11-17 13:17:24', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(179, 2, 'LOGOUT', 'users', 2, '2025-11-17 13:20:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(180, 2, 'LOGIN', 'users', 2, '2025-11-17 13:21:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(181, 2, 'UPDATE', 'users', 2, '2025-11-17 13:53:56', '{\"username\":\"V-12345678\",\"usuario\":1,\"estudiante\":1,\"docente\":1,\"admin\":1,\"super_user\":0,\"editar_user\":1,\"editar_nota\":1,\"editar_acceso\":1,\"editar_valores\":1,\"editar_estudiante\":1,\"agregar_estudiante\":1,\"agregar_docente\":1,\"editar_docente\":1,\"agregar_carrera\":1,\"agregar_materia\":1,\"editar_materia\":1,\"pagos\":1,\"auditoria\":1,\"secciones\":1,\"rela_materia_carrera\":1,\"periodos_academicos\":1,\"asig_secciones\":1,\"asig_cursos\":1,\"horarios\":1,\"gestion_director_carrera\":1,\"notas_cargadas\":1,\"consultar_notas\":1,\"consultar_notas_pasadas\":1,\"tipos_pago\":1,\"tipos_horario\":1,\"horario_personal\":1,\"respaldo_bd\":1,\"gestionar_carrera\":1,\"gestion_periodo_academico\":1,\"gestion_asig_cursos\":1,\"gestion_horario\":1,\"titulos_re_materia\":1,\"grado\":1,\"gestion_grado\":1,\"visita\":null}', '{\"usuario_afectado\":\"V-12345678\",\"usuario_afectado_id\":2,\"usuario_editor\":\"V-12345678\",\"usuario_editor_id\":\"2\",\"accesos_otorgados\":\"visita\",\"accesos_quitados\":\"\",\"total_otorgados\":1,\"total_quitados\":0,\"super_user_anterior\":0,\"super_user_nuevo\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Gestión de Permisos', 'Permisos actualizados para usuario: V-12345678 - Accesos OTORGADOS: visita'),
(182, 2, 'UPDATE', 'users', 2, '2025-11-17 13:54:16', '{\"username\":\"V-12345678\",\"usuario\":1,\"estudiante\":1,\"docente\":1,\"admin\":1,\"super_user\":0,\"editar_user\":1,\"editar_nota\":1,\"editar_acceso\":1,\"editar_valores\":1,\"editar_estudiante\":1,\"agregar_estudiante\":1,\"agregar_docente\":1,\"editar_docente\":1,\"agregar_carrera\":1,\"agregar_materia\":1,\"editar_materia\":1,\"pagos\":1,\"auditoria\":1,\"secciones\":1,\"rela_materia_carrera\":1,\"periodos_academicos\":1,\"asig_secciones\":1,\"asig_cursos\":1,\"horarios\":1,\"gestion_director_carrera\":1,\"notas_cargadas\":1,\"consultar_notas\":1,\"consultar_notas_pasadas\":1,\"tipos_pago\":1,\"tipos_horario\":1,\"horario_personal\":1,\"respaldo_bd\":1,\"gestionar_carrera\":1,\"gestion_periodo_academico\":1,\"gestion_asig_cursos\":1,\"gestion_horario\":1,\"titulos_re_materia\":1,\"grado\":1,\"gestion_grado\":1,\"visita\":1}', '{\"usuario_afectado\":\"V-12345678\",\"usuario_afectado_id\":2,\"usuario_editor\":\"V-12345678\",\"usuario_editor_id\":\"2\",\"accesos_otorgados\":\"\",\"accesos_quitados\":\"visita\",\"total_otorgados\":0,\"total_quitados\":1,\"super_user_anterior\":0,\"super_user_nuevo\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Gestión de Permisos', 'Permisos actualizados para usuario: V-12345678Accesos QUITADOS: visita'),
(183, 2, 'UPDATE', 'users', 2, '2025-11-17 13:54:30', '{\"username\":\"V-12345678\",\"usuario\":1,\"estudiante\":1,\"docente\":1,\"admin\":1,\"super_user\":0,\"editar_user\":1,\"editar_nota\":1,\"editar_acceso\":1,\"editar_valores\":1,\"editar_estudiante\":1,\"agregar_estudiante\":1,\"agregar_docente\":1,\"editar_docente\":1,\"agregar_carrera\":1,\"agregar_materia\":1,\"editar_materia\":1,\"pagos\":1,\"auditoria\":1,\"secciones\":1,\"rela_materia_carrera\":1,\"periodos_academicos\":1,\"asig_secciones\":1,\"asig_cursos\":1,\"horarios\":1,\"gestion_director_carrera\":1,\"notas_cargadas\":1,\"consultar_notas\":1,\"consultar_notas_pasadas\":1,\"tipos_pago\":1,\"tipos_horario\":1,\"horario_personal\":1,\"respaldo_bd\":1,\"gestionar_carrera\":1,\"gestion_periodo_academico\":1,\"gestion_asig_cursos\":1,\"gestion_horario\":1,\"titulos_re_materia\":1,\"grado\":1,\"gestion_grado\":1,\"visita\":0}', '{\"usuario_afectado\":\"V-12345678\",\"usuario_afectado_id\":2,\"usuario_editor\":\"V-12345678\",\"usuario_editor_id\":\"2\",\"accesos_otorgados\":\"visita\",\"accesos_quitados\":\"\",\"total_otorgados\":1,\"total_quitados\":0,\"super_user_anterior\":0,\"super_user_nuevo\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Gestión de Permisos', 'Permisos actualizados para usuario: V-12345678 - Accesos OTORGADOS: visita'),
(184, 2, 'LOGOUT', 'users', 2, '2025-11-17 14:48:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(185, 5, 'LOGIN', 'users', 5, '2025-11-17 14:50:48', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(186, 5, 'LOGOUT', 'users', 5, '2025-11-17 14:51:31', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(187, 2, 'LOGIN', 'users', 2, '2025-11-17 14:51:34', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(188, 2, 'LOGIN', 'users', 2, '2025-11-24 11:45:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(189, 2, 'ERROR', 'users', NULL, '2025-11-24 11:48:07', NULL, '{\"nombre\":\"alberto guerra\",\"idusuario\":\"V--30123456\",\"error\":\"Error al subir el archivo.\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(190, 2, 'INSERT', 'users', 2610, '2025-11-24 11:53:40', NULL, '{\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerra\",\"email\":\"infos@guerra.com\",\"carrera\":\"1\",\"status\":\"1\",\"foto_perfil\":\"S\\u00ed\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante'),
(191, 2, 'UPDATE', 'users', 2610, '2025-11-24 13:46:53', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 11:53:40\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(192, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:02:11', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 13:46:53\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(193, 2, 'UPDATE', 'tipo_cedula', 1, '2025-11-24 14:06:01', '{\"id_anterior\":\"1\",\"valor_anterior\":\"V-\"}', '{\"tabla\":\"tipo_cedula\",\"campo\":\"tipo\",\"id_nuevo\":\"1\",\"valor_nuevo\":\"V\",\"accion\":\"editar\",\"cambios\":\"tipo: V- \\u2192 V\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en tipo_cedula: tipo: V- → V'),
(194, 2, 'UPDATE', 'tipo_cedula', 2, '2025-11-24 14:06:07', '{\"id_anterior\":\"2\",\"valor_anterior\":\"E-\"}', '{\"tabla\":\"tipo_cedula\",\"campo\":\"tipo\",\"id_nuevo\":\"2\",\"valor_nuevo\":\"E\",\"accion\":\"editar\",\"cambios\":\"tipo: E- \\u2192 E\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en tipo_cedula: tipo: E- → E'),
(195, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:06:41', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:02:11\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(196, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:06:52', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:06:40\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(197, 2, 'UPDATE', 'tipo_cedula', 1, '2025-11-24 14:08:16', '{\"id_anterior\":\"1\",\"valor_anterior\":\"V\"}', '{\"tabla\":\"tipo_cedula\",\"campo\":\"tipo\",\"id_nuevo\":\"1\",\"valor_nuevo\":\"V-\",\"accion\":\"editar\",\"cambios\":\"tipo: V \\u2192 V-\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en tipo_cedula: tipo: V → V-'),
(198, 2, 'UPDATE', 'tipo_cedula', 2, '2025-11-24 14:08:23', '{\"id_anterior\":\"2\",\"valor_anterior\":\"E\"}', '{\"tabla\":\"tipo_cedula\",\"campo\":\"tipo\",\"id_nuevo\":\"2\",\"valor_nuevo\":\"E-\",\"accion\":\"editar\",\"cambios\":\"tipo: E \\u2192 E-\",\"usuario\":\"V-12345678\",\"usuario_id\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Datos Predefinidos', 'Registro editado en tipo_cedula: tipo: E → E-'),
(199, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:13:52', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:06:52\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(200, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:27:08', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:13:52\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(201, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:33:04', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:27:07\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(202, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:33:32', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:33:04\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(203, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:34:31', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:33:32\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(204, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:34:48', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:34:31\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(205, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:42:27', '{\"id\":2610,\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:34:48\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V--30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(206, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:43:03', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:42:52\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(207, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:43:19', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:43:03\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(208, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:51:24', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:43:19\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(209, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:57:01', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:51:24\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Femenino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(210, 2, 'UPDATE', 'users', 2610, '2025-11-24 14:57:10', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:57:01\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Femenino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(211, 2, 'LOGIN', 'users', 2, '2025-11-25 09:43:01', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(212, 2, 'UPDATE', 'users', 2610, '2025-11-25 09:46:49', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-24 14:57:10\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(213, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:02:29', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 09:46:49\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(214, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:06:25', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:02:29\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(215, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:10:47', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:06:24\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(216, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:25:57', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:10:47\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123457\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(217, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:26:25', '{\"id\":2610,\"idusuario\":\"V-30123457\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:25:56\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(218, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:26:35', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerraa\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:26:25\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(219, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:27:20', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:26:35\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(220, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:34:50', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:27:20\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(221, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:35:25', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:34:50\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(222, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:48:30', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:35:25\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"jkhguykg\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(223, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:55:44', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"jkhguykg\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:48:30\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(224, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:55:58', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"prueba\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:55:44\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"5\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(225, 2, 'UPDATE', 'users', 2610, '2025-11-25 10:56:08', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"prueba\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:55:58\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":5,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(226, 2, 'UPDATE', 'users', 2610, '2025-11-25 11:05:14', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"prueba\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 10:56:08\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(227, 2, 'UPDATE', 'users', 2610, '2025-11-25 11:17:16', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"prueba\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 11:05:13\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"fecha_ingreso\":\"2025-11-24\",\"status\":1,\"etnia\":\"Ninguna\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":6,\"acargo_usted\":2,\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(228, 2, 'UPDATE', 'users', 2610, '2025-11-25 11:28:10', '{\"id\":2610,\"idusuario\":\"V-30123456\",\"nombre\":\"alberto guerra\",\"username\":\"alberto.guerra\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"direccion\":\"prueba\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a un campo\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-11-24 00:00:00\",\"fecha_act\":\"2025-11-25 11:17:15\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G\\/ryY0C8xK4T.\",\"api_key\":\"\",\"carrera\":1,\"carrera_di\":null,\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"69247f8403325_1763999620.png\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2610\",\"nombre\":\"alberto guerra\",\"idusuario\":\"V-30123456\",\"username\":\"alberto.guerra\",\"genero\":\"Masculino\",\"edo_civil\":\"Casado\",\"fecha_nac\":\"1975-06-12\",\"etnia\":\"Ninguna\",\"carrera\":\"1\",\"fecha_ingreso\":\"2025-11-24\",\"status\":\"1\",\"titulos\":\"\",\"institutos\":\"\",\"email\":\"infos@guerra.com\",\"tlf\":\"0416598362\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"direccion\":\"prueba\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Juan Jose Flores\",\"ciudad\":\"Puerto Cabello\",\"casaapto\":\"Apartamento\",\"tenencia_vivienda\":\"Alquilada\",\"grupo_familiar\":\"6\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"2\",\"punto_referencia\":\"frente a un campo\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(229, 4, 'LOGIN', 'users', 4, '2025-12-01 13:44:54', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(230, 4, 'LOGIN', 'users', NULL, '2025-12-01 13:45:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Intento de inicio de sesión fallido - Contraseña incorrecta'),
(231, 4, 'LOGIN', 'users', NULL, '2025-12-01 13:45:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Intento de inicio de sesión fallido - Contraseña incorrecta'),
(232, 4, 'UPDATE', 'users', 2, '2025-12-01 13:45:39', NULL, '{\"username\":\"V-12345678\",\"idusuario\":\"12345678\",\"accion\":\"Migraci\\u00f3n a password_hash\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Contraseña migrada a hash seguro - Provisional'),
(233, 2, 'LOGIN', 'users', 2, '2025-12-01 13:45:50', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(234, 2, 'LOGIN', 'users', 2, '2025-12-03 09:51:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(235, 2, 'ERROR', 'users', NULL, '2025-12-03 10:55:03', NULL, '{\"nombre\":\"\",\"idusuario\":\"\",\"error\":\"Column \'nombre\' cannot be null\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(236, 2, 'ERROR', 'users', NULL, '2025-12-03 11:14:48', NULL, '{\"nombre\":\"O\'Connor\",\"idusuario\":\"V-31123123\",\"error\":\"Unknown column \'fecha_registro\' in \'field list\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(237, 2, 'ERROR', 'users', NULL, '2025-12-03 11:17:51', NULL, '{\"nombre\":\"O\'Connor\",\"idusuario\":\"V-31123123\",\"error\":\"Unknown column \'fecha_ingreso\' in \'field list\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(238, 2, 'ERROR', 'users', NULL, '2025-12-03 11:17:54', NULL, '{\"nombre\":\"O\'Connor\",\"idusuario\":\"V-31123123\",\"error\":\"Unknown column \'fecha_ingreso\' in \'field list\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(239, 2, 'ERROR', 'users', NULL, '2025-12-03 11:48:55', NULL, '{\"nombre\":\"O\'Connor\",\"idusuario\":\"V-30123123\",\"error\":\"Unknown column \'fecha_registro\' in \'field list\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al registrar estudiante'),
(240, 2, 'INSERT', 'users', 2615, '2025-12-03 12:02:19', NULL, '{\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"carrera\":\"1\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante'),
(241, 2, 'ERROR', 'users', 2615, '2025-12-03 12:26:24', NULL, '{\"nombre\":\"O\'Connor2\",\"idusuario\":\"V-30123123\",\"error\":\"El nombre contiene caracteres no v\\u00e1lidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(242, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:26:31', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:02:19\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(243, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:26:45', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:26:31\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(244, 2, 'ERROR', 'users', 2615, '2025-12-03 12:28:10', NULL, '{\"nombre\":\"O\'Connor3\",\"idusuario\":\"V-30123123\",\"error\":\"El nombre contiene caracteres no v\\u00e1lidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(245, 2, 'ERROR', 'users', 2615, '2025-12-03 12:37:31', NULL, '{\"nombre\":\"O\'Connor4\",\"idusuario\":\"V-30123123\",\"error\":\"\\u274c ERRORES DE VALIDACI\\u00d3N:\\n\\n\\u2022 El nombre no puede contener n\\u00fameros. Solo letras, espacios y ap\\u00f3strofes (\')\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(246, 2, 'ERROR', 'users', 2615, '2025-12-03 12:42:22', NULL, '{\"nombre\":\"O\'Connor5\",\"idusuario\":\"V-30123123\",\"error\":\"\\u274c ERRORES DE VALIDACI\\u00d3N:\\n\\n\\u2022 El nombre no puede contener n\\u00fameros. Solo letras, espacios y ap\\u00f3strofes (\')\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(247, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:42:32', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:26:45\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(248, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:43:03', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:42:32\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(249, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:46:55', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:43:03\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123122\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(250, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:47:07', '{\"id\":2615,\"idusuario\":\"V-30123122\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:46:55\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(251, 2, 'ERROR', 'users', 2615, '2025-12-03 12:47:23', NULL, '{\"nombre\":\"O\'Connor5\",\"idusuario\":\"V-30123123\",\"error\":\"\\u274c ERRORES DE VALIDACI\\u00d3N:\\n\\n\\u2022 El nombre no puede contener n\\u00fameros. Solo letras, espacios y ap\\u00f3strofes (\')\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(252, 2, 'ERROR', 'users', 2615, '2025-12-03 12:49:00', NULL, '{\"nombre\":\"O\'Connor5\",\"idusuario\":\"V-30123123\",\"error\":\"\\u274c ERRORES DE VALIDACI\\u00d3N:\\n\\n\\u2022 El nombre no puede contener n\\u00fameros. Solo letras, espacios y ap\\u00f3strofes (\')\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(253, 2, 'ERROR', 'users', 2615, '2025-12-03 12:59:28', NULL, '{\"nombre\":\"O\'Connor5\",\"idusuario\":\"V-30123123\",\"error\":\"\\u274c ERRORES DE VALIDACI\\u00d3N:\\n\\n\\u2022 El nombre no puede contener n\\u00fameros. Solo letras, espacios y ap\\u00f3strofes (\')\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Error al actualizar estudiante'),
(254, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:59:33', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:47:07\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(255, 2, 'UPDATE', 'users', 2615, '2025-12-03 12:59:53', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:59:33\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(256, 2, 'UPDATE', 'users', 2615, '2025-12-03 13:03:03', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 12:59:53\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(257, 2, 'UPDATE', 'users', 2615, '2025-12-03 13:03:21', '{\"id\":2615,\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connorr\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 13:03:03\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123122\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(258, 2, 'UPDATE', 'users', 2615, '2025-12-03 13:03:32', '{\"id\":2615,\"idusuario\":\"V-30123122\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"direccion\":\"kvftfvgghjkf\",\"ciudad\":\"Puerto Cabello\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":\"3\",\"acargo_usted\":\"2\",\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"potencialidades\":\"\",\"fecha_ingreso\":\"2025-12-03 00:00:00\",\"fecha_act\":\"2025-12-03 13:03:21\",\"status\":1,\"user_type\":\"estudiante\",\"password\":\"$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.\",\"api_key\":\"854d0aa4bec27560ebb7550a3f9600a4\",\"carrera\":1,\"carrera_di\":1,\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"num_telf_opc\":\"04163333333\",\"foto_perfil\":\"foto_69305f0b6200d9.46312539.jpeg\",\"usuario\":0,\"estudiante\":1,\"docente\":0,\"admin\":0,\"super_user\":0,\"editar_user\":0,\"editar_nota\":0,\"editar_acceso\":0,\"editar_valores\":0,\"editar_estudiante\":0,\"agregar_estudiante\":0,\"agregar_docente\":0,\"editar_docente\":0,\"agregar_carrera\":0,\"agregar_materia\":0,\"editar_materia\":0,\"pagos\":0,\"auditoria\":0,\"secciones\":0,\"rela_materia_carrera\":0,\"periodos_academicos\":0,\"asig_secciones\":0,\"asig_cursos\":0,\"horarios\":0,\"gestion_director_carrera\":0,\"notas_cargadas\":0,\"consultar_notas\":0,\"consultar_notas_pasadas\":0,\"tipos_pago\":0,\"tipos_horario\":0,\"horario_personal\":0,\"respaldo_bd\":0,\"gestionar_carrera\":0,\"gestion_periodo_academico\":0,\"gestion_asig_cursos\":0,\"gestion_horario\":0,\"titulos_re_materia\":0,\"grado\":0,\"gestion_grado\":0,\"visita\":0}', '{\"id\":\"2615\",\"idusuario\":\"V-30123123\",\"nombre\":\"O\'Connor\",\"username\":\"o.connor\",\"email\":\"validacion@example.com\",\"tlf\":\"0412555777\",\"cel\":\"0416777777\",\"num_telf_opc\":\"04163333333\",\"direccion\":\"kvftfvgghjkf\",\"estado\":\"Carabobo\",\"municipio\":\"Puerto Cabello\",\"parroquia\":\"Bartolome Salom\",\"ciudad\":\"Puerto Cabello\",\"etnia\":\"Ninguna\",\"casaapto\":\"Apartamento\",\"punto_referencia\":\"frente a una farmacia\",\"grupo_familiar\":3,\"acargo_usted\":2,\"fuente_ingresos\":\"1\",\"tipo_vivienda\":\"\",\"tenencia_vivienda\":\"Alquilada\",\"enfermedad\":\"no\",\"discapacidad\":\"No\",\"titulos\":\"\",\"institutos\":\"\",\"carrera\":\"1\",\"genero\":\"Masculino\",\"edo_civil\":\"Divorciado\",\"fecha_nac\":\"2002-06-19\",\"fecha_ingreso\":\"2025-12-03\",\"status\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Estudiantes', 'Actualización de datos de estudiante'),
(259, 2, 'UPDATE', 'periodos_academicos', 3, '2025-12-03 14:31:42', '{\"activo\":0,\"estado_anterior\":\"Inactivo\"}', '{\"activo\":\"1\",\"estado_nuevo\":\"Activo\",\"nombre_periodo\":\"2027-1\",\"fecha_inicio\":\"2027-06-09\",\"fecha_fin\":\"2028-07-05\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Cambio de estado de período académico'),
(260, 2, 'UPDATE', 'periodos_academicos', 1, '2025-12-03 14:31:56', '{\"activo\":0,\"estado_anterior\":\"Inactivo\"}', '{\"activo\":\"1\",\"estado_nuevo\":\"Activo\",\"nombre_periodo\":\"2024-1\",\"fecha_inicio\":\"2024-01-15\",\"fecha_fin\":\"2024-04-15\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Cambio de estado de período académico'),
(261, 2, 'UPDATE', 'periodos_academicos', NULL, '2025-12-03 14:31:56', NULL, '{\"periodos_desactivados\":1,\"fecha_actual\":\"2025-12-03\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Desactivación automática de períodos académicos vencidos'),
(262, 2, 'UPDATE', 'periodos_academicos', 3, '2025-12-03 14:32:07', '{\"activo\":1,\"estado_anterior\":\"Activo\"}', '{\"activo\":\"0\",\"estado_nuevo\":\"Inactivo\",\"nombre_periodo\":\"2027-1\",\"fecha_inicio\":\"2027-06-09\",\"fecha_fin\":\"2028-07-05\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Cambio de estado de período académico'),
(263, 2, 'INSERT', 'periodos_academicos', 4, '2025-12-03 14:33:01', NULL, '{\"nombre_periodo\":\"2026-1\",\"fecha_inicio\":\"2025-12-13\",\"fecha_fin\":\"2026-02-10\",\"activo\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Nuevo período académico creado'),
(264, 2, 'UPDATE', 'periodos_academicos', 4, '2025-12-03 14:33:06', '{\"activo\":1,\"estado_anterior\":\"Activo\"}', '{\"activo\":\"0\",\"estado_nuevo\":\"Inactivo\",\"nombre_periodo\":\"2026-1\",\"fecha_inicio\":\"2025-12-13\",\"fecha_fin\":\"2026-02-10\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Periodos Académicos', 'Cambio de estado de período académico'),
(265, 2, 'CONSULTA', 'users', NULL, '2025-12-03 14:38:09', NULL, '{\"cedula_buscada\":\"30692052\",\"resultado_busqueda\":\"NO_ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"resultado\":\"Estudiante no encontrado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - NO_ENCONTRADO'),
(266, 2, 'CONSULTA', 'users', 5, '2025-12-03 14:38:16', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(267, 2, 'LOGOUT', 'users', 2, '2025-12-03 14:46:02', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(268, 2, 'LOGIN', 'users', 2, '2025-12-03 14:54:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(269, 2, 'CONSULTA', 'users', 5, '2025-12-03 15:51:37', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(270, 2, 'LOGOUT', 'users', 2, '2025-12-03 15:52:12', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(271, 4, 'LOGIN', 'users', 4, '2025-12-03 15:52:17', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(272, 4, 'LOGOUT', 'users', 4, '2025-12-03 15:53:06', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(273, 2, 'LOGIN', 'users', 2, '2025-12-03 15:53:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(274, 2, 'CONSULTA', 'users', 5, '2025-12-03 15:54:27', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(275, 2, 'CONSULTA', 'users', 5, '2025-12-03 15:55:03', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(276, 2, 'LOGIN', 'users', 2, '2025-12-04 10:18:42', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(277, 2, 'CONSULTA', 'users', 5, '2025-12-04 10:54:18', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(278, 2, 'LOGIN', 'users', 2, '2025-12-04 12:14:50', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(279, 4, 'LOGIN', 'users', 4, '2025-12-04 12:18:20', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(280, 4, 'LOGOUT', 'users', 4, '2025-12-04 12:19:11', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(281, 2, 'LOGIN', 'users', 2, '2025-12-04 12:19:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(282, 2, 'CONSULTA', 'users', 5, '2025-12-04 12:20:46', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(283, 2, 'LOGIN', 'users', 2, '2026-01-13 10:24:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(284, 2, 'CONSULTA', 'users', 5, '2026-01-13 10:25:54', NULL, '{\"cedula_buscada\":null,\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(285, 2, 'INSERT', 'carreras', 12, '2026-01-13 11:28:52', NULL, '{\"nombre_carrera\":\"Mecanica\",\"cod_carrera\":\"1122\",\"tipo_formacion\":\"2\",\"duracion_semestres\":8,\"duracion_anios\":4,\"titulo_principal\":\"TSU Mecanica\",\"titulo_opcional\":\"\",\"vigencia_fecha\":\"2005-01-13 00:00:00\",\"activa\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Nueva carrera registrada'),
(286, 2, 'LOGIN', 'users', 2, '2026-01-13 11:51:13', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(287, 2, 'INSERT', 'materias', 29, '2026-01-13 12:15:00', NULL, '{\"cod_materia\":\"MAT-154\",\"nombre_materia\":\"Matem\\u00e1tica I\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":4,\"activa\":1,\"horas_teoricas\":3,\"horas_practicas\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(288, 2, 'INSERT', 'carrera_materia', 33, '2026-01-13 12:15:43', NULL, '{\"id_carrera\":12,\"carrera_nombre\":\"Mecanica\",\"carrera_codigo\":\"1122\",\"id_materia\":29,\"materia_nombre\":\"Matem\\u00e1tica I\",\"materia_codigo\":\"MAT-154\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(289, 2, 'INSERT', 'carreras', 13, '2026-01-13 12:28:09', NULL, '{\"copiado_de\":12,\"created_at\":\"2000-01-13 00:00:00\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Duplicado/versionado de carrera'),
(290, 2, 'INSERT', 'carrera_versiones', 1, '2026-01-13 12:38:46', NULL, '{\"copiado_de\":12,\"fecha_vigencia\":\"1977-01-13 00:00:00\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Creación de versión de carrera'),
(291, 2, 'INSERT', 'materias', 30, '2026-01-13 12:41:36', NULL, '{\"cod_materia\":\"AE-1111\",\"nombre_materia\":\"Fisica\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":5,\"activa\":1,\"horas_teoricas\":2,\"horas_practicas\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(292, 2, 'INSERT', 'carreras', 14, '2026-01-13 12:46:03', NULL, '{\"nombre_carrera\":\"Mecanica\",\"cod_carrera\":\"1122\",\"tipo_formacion\":\"2\",\"duracion_semestres\":8,\"duracion_anios\":4,\"titulo_principal\":\"TSU Mecanica\",\"titulo_opcional\":\"Ing. Mecanica\",\"vigencia_fecha\":\"2005-01-13 00:00:00\",\"activa\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Nueva carrera registrada'),
(293, 2, 'INSERT', 'carrera_materia', 35, '2026-01-13 12:46:42', NULL, '{\"id_carrera\":14,\"carrera_nombre\":\"Mecanica\",\"carrera_codigo\":\"1122\",\"id_materia\":29,\"materia_nombre\":\"Matem\\u00e1tica I\",\"materia_codigo\":\"MAT-154\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(294, 2, 'INSERT', 'carrera_versiones', 2, '2026-01-13 12:48:01', NULL, '{\"copiado_de\":14,\"fecha_vigencia\":\"2000-01-13 00:00:00\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Creación de versión de carrera'),
(295, 2, 'INSERT', 'carrera_versiones', 3, '2026-01-13 13:36:25', NULL, '{\"copiado_de\":14,\"fecha_vigencia\":\"1977-01-01 00:00:00\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Creación de versión de carrera'),
(296, 2, 'INSERT', 'materias', 31, '2026-01-13 13:37:32', NULL, '{\"cod_materia\":\"AE-1241\",\"nombre_materia\":\"Deporte I\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":2,\"activa\":1,\"horas_teoricas\":2,\"horas_practicas\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(297, 2, 'SEARCH', 'users', 5, '2026-01-13 14:15:45', NULL, '{\"cedula\":\"V-30692052\",\"estudiante\":\"Hector\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Pagos', 'Búsqueda exitosa de estudiante por cédula'),
(298, 2, 'LOGIN', 'users', 2, '2026-01-14 09:51:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(299, 2, 'UPDATE', 'carreras', 1, '2026-01-14 09:58:56', '{\"nombre_carrera\":\"Informatica\",\"cod_carrera\":\"1234\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Actualización de datos de carrera'),
(300, 2, 'UPDATE', 'carreras', 5, '2026-01-14 09:59:41', '{\"nombre_carrera\":\"Logistica y Distribucion\",\"cod_carrera\":\"3\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Actualización de datos de carrera'),
(301, 2, 'UPDATE', 'carreras', 14, '2026-01-14 10:00:01', '{\"nombre_carrera\":\"Mecanica\",\"cod_carrera\":\"1122\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Actualización de datos de carrera'),
(302, 2, 'ERROR', 'carreras', 2, '2026-01-14 10:00:39', NULL, '{\"nombre_carrera\":\"Turismo\",\"cod_carrera\":\"13569\",\"error\":\"Data too long for column \'titulo_otorga\' at row 1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Error al actualizar carrera'),
(303, 2, 'UPDATE', 'carreras', 2, '2026-01-14 10:11:15', '{\"nombre_carrera\":\"Turismo\",\"cod_carrera\":\"2\",\"duracion_semestres\":null,\"titulo_otorga\":null}', '{\"nombre_carrera\":null,\"cod_carrera\":null,\"duracion_semestres\":8,\"titulo_otorga\":\"TSU turismo\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras', 'Actualización de datos de carrera'),
(304, 2, 'INSERT', 'materias', 32, '2026-01-14 12:11:55', NULL, '{\"cod_materia\":\"MAT-253\",\"nombre_materia\":\"Matem\\u00e1tica II\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":3,\"activa\":1,\"horas_teoricas\":2,\"horas_practicas\":3,\"horas_laboratorio\":0,\"horas_semanales\":4}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(305, 2, 'UPDATE', 'materias', 32, '2026-01-14 12:22:55', '{\"trayecto\":1}', '{\"trayecto\":\"2\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Materias', 'Actualización de datos de materia'),
(306, 2, 'INSERT', 'carrera_materia', 36, '2026-01-14 12:26:45', NULL, '{\"id_carrera\":14,\"carrera_nombre\":\"Mecanica\",\"carrera_codigo\":\"13351\",\"id_materia\":32,\"materia_nombre\":\"Matem\\u00e1tica II\",\"materia_codigo\":\"MAT-253\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(307, 2, 'LOGIN', 'users', 2, '2026-01-16 09:48:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(308, 2, 'LOGIN', 'users', 2, '2026-01-16 09:59:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(309, 2, 'LOGOUT', 'users', 2, '2026-01-16 10:00:03', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(310, 2, 'LOGIN', 'users', 2, '2026-01-16 10:00:11', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(311, 2, 'LOGOUT', 'users', 2, '2026-01-16 10:19:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(312, 5, 'LOGIN', 'users', 5, '2026-01-16 10:19:27', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(313, 5, 'LOGOUT', 'users', 5, '2026-01-16 10:20:34', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(314, 2, 'LOGIN', 'users', 2, '2026-01-16 10:20:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(315, 2, 'LOGOUT', 'users', 2, '2026-01-16 10:40:17', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(316, 5, 'LOGIN', 'users', 5, '2026-01-16 10:40:29', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(317, 5, 'LOGOUT', 'users', 5, '2026-01-16 11:02:34', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(318, 2, 'LOGIN', 'users', 2, '2026-01-16 11:02:37', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(319, 2, 'LOGOUT', 'users', 2, '2026-01-16 11:11:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(320, 5, 'LOGIN', 'users', 5, '2026-01-16 11:11:12', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(321, 5, 'LOGOUT', 'users', 5, '2026-01-16 11:27:50', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(322, 2, 'LOGIN', 'users', 2, '2026-01-16 11:27:53', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(323, 2, 'CONSULTA', 'users', NULL, '2026-01-16 12:07:36', NULL, '{\"cedula_buscada\":\"30692052\",\"resultado_busqueda\":\"NO_ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"resultado\":\"Estudiante no encontrado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - NO_ENCONTRADO'),
(324, 2, 'CONSULTA', 'users', NULL, '2026-01-16 12:08:18', NULL, '{\"cedula_buscada\":\"30692052\",\"resultado_busqueda\":\"NO_ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"resultado\":\"Estudiante no encontrado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - NO_ENCONTRADO'),
(325, 2, 'CONSULTA', 'users', NULL, '2026-01-16 12:10:24', NULL, '{\"cedula_buscada\":\"30692052\",\"resultado_busqueda\":\"NO_ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"resultado\":\"Estudiante no encontrado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - NO_ENCONTRADO'),
(326, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:10:36', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(327, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:14:42', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(328, 2, 'CONSULTA', 'users', 2607, '2026-01-16 12:15:10', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(329, 2, 'CONSULTA', 'users', 2607, '2026-01-16 12:15:17', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(330, 2, 'CONSULTA', 'users', 2607, '2026-01-16 12:15:21', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(331, 2, 'CONSULTA', 'users', NULL, '2026-01-16 12:15:22', NULL, '{\"cedula_buscada\":\"V-\",\"resultado_busqueda\":\"NO_ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"resultado\":\"Estudiante no encontrado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - NO_ENCONTRADO'),
(332, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:15:31', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(333, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:34:44', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(334, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:48:13', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(335, 2, 'CONSULTA', 'users', 5, '2026-01-16 12:59:36', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(336, 2, 'INSERT', 'periodos_academicos', 5, '2026-01-16 13:01:32', NULL, '{\"nombre_periodo\":\"2026-1\",\"fecha_inicio\":\"2026-01-16\",\"fecha_fin\":\"2026-03-16\",\"activo\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Periodos Académicos', 'Nuevo período académico creado'),
(337, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:13:39', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(338, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:14:52', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(339, 2, 'LOGIN', 'users', 2, '2026-01-16 13:17:07', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(340, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:17:19', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(341, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:17:43', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(342, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:19:02', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(343, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:20:30', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(344, 2, 'CONSULTA', 'users', 5, '2026-01-16 13:23:38', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(345, 2, 'LOGIN', 'users', 2, '2026-01-19 09:19:19', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(346, 2, 'LOGOUT', 'users', 2, '2026-01-19 09:20:24', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(347, 4, 'LOGIN', 'users', 4, '2026-01-19 09:20:48', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(348, 2, 'LOGIN', 'users', 2, '2026-01-20 10:43:41', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(349, 2, 'CONSULTA', 'users', 5, '2026-01-20 10:44:43', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(350, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 10:45:06', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(351, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 11:49:06', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(352, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:01:31', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(353, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:16:50', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(354, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:20:31', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(355, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:47:44', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(356, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:55:33', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(357, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:55:34', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(358, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 12:56:50', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(359, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:01:29', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(360, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:09:27', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(361, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:09:30', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(362, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:12:34', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(363, 2, 'CONSULTA', 'users', 5, '2026-01-20 13:12:42', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(364, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:15:43', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(365, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:18:40', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(366, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:19:05', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(367, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:19:43', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(368, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:22:11', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(369, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:24:34', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(370, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-20 13:28:03', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(371, 2, 'CONSULTA', 'users', 5, '2026-01-20 13:28:09', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(372, 2, 'LOGOUT', 'users', 2, '2026-01-20 13:45:43', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(373, 4, 'LOGIN', 'users', 4, '2026-01-20 13:46:25', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(374, 4, 'LOGOUT', 'users', 4, '2026-01-20 13:49:55', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(375, 2, 'LOGIN', 'users', 2, '2026-01-20 13:49:58', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(376, 4, 'LOGIN', 'users', 4, '2026-01-20 13:50:07', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(377, 2, 'LOGIN', 'users', 2, '2026-01-21 09:33:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(378, 2, 'LOGOUT', 'users', 2, '2026-01-21 09:53:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(379, 4, 'LOGIN', 'users', 4, '2026-01-21 09:53:24', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(380, 4, 'LOGOUT', 'users', 4, '2026-01-21 10:26:06', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(381, 2, 'LOGIN', 'users', 2, '2026-01-21 10:26:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(382, 2, 'LOGOUT', 'users', 2, '2026-01-21 11:20:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(383, 4, 'LOGIN', 'users', 4, '2026-01-21 11:20:17', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(384, 4, 'LOGOUT', 'users', 4, '2026-01-21 11:20:28', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(385, 2, 'LOGIN', 'users', 2, '2026-01-21 11:20:32', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(386, 2, 'LOGOUT', 'users', 2, '2026-01-21 11:21:46', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(387, 4, 'LOGIN', 'users', 4, '2026-01-21 11:21:50', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(388, 4, 'LOGOUT', 'users', 4, '2026-01-21 11:23:54', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(389, 2, 'LOGIN', 'users', 2, '2026-01-21 11:23:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(390, 2, 'LOGIN', 'users', 2, '2026-01-22 09:56:12', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(391, 2, 'LOGOUT', 'users', 2, '2026-01-22 09:57:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(392, 4, 'LOGIN', 'users', 4, '2026-01-22 09:57:07', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(393, 4, 'LOGOUT', 'users', 4, '2026-01-22 11:01:18', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(394, 4, 'LOGIN', 'users', 4, '2026-01-22 11:17:20', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(395, 4, 'LOGOUT', 'users', 4, '2026-01-22 11:17:25', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(396, 2, 'LOGIN', 'users', 2, '2026-01-23 09:33:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(397, 2, 'LOGOUT', 'users', 2, '2026-01-23 09:35:28', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(398, 4, 'LOGIN', 'users', 4, '2026-01-23 09:35:34', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(399, 4, 'LOGOUT', 'users', 4, '2026-01-23 09:35:52', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(400, 2, 'LOGIN', 'users', 2, '2026-01-23 09:35:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(401, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-23 09:36:05', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(402, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-23 09:37:47', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(403, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-23 09:55:05', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(404, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-23 10:20:14', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(405, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-23 11:43:05', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(406, 2, 'CONSULTA', 'users', 5, '2026-01-23 12:14:58', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(407, 2, 'CONSULTA', 'users', 5, '2026-01-23 12:49:03', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(408, 2, 'CONSULTA', 'users', 5, '2026-01-23 12:50:22', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(409, 2, 'LOGIN', 'users', 2, '2026-01-26 10:58:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(410, 2, 'CONSULTA', 'users', 5, '2026-01-26 10:59:14', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(411, 2, 'CONSULTA', 'users', 5, '2026-01-26 11:00:33', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(412, 2, 'UPDATE', 'secciones', 10, '2026-01-26 11:07:47', '{\"id_trayecto\":1,\"inicia\":\"2026-08-02 12:00:00\"}', '{\"id_trayecto\":2,\"inicia\":\"2026-08-02T12:00\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Secciones', 'Edición de datos de sección'),
(413, 2, 'ERROR', 'docente_seccion', NULL, '2026-01-26 11:09:43', NULL, '{\"id_usuario\":\"4\",\"id_seccion\":\"10\",\"id_materia\":\"11\",\"error\":\"Duplicate entry \'4-10\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(414, 2, 'ERROR', 'docente_seccion', NULL, '2026-01-26 12:04:24', NULL, '{\"id_usuario\":\"4\",\"id_seccion\":\"10\",\"id_materia\":\"11\",\"error\":\"Duplicate entry \'4-10\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(415, 2, 'ERROR', 'docente_seccion', NULL, '2026-01-26 12:05:45', NULL, '{\"id_usuario\":\"4\",\"id_seccion\":\"10\",\"id_materia\":\"11\",\"error\":\"Duplicate entry \'4-10\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(416, 2, 'ERROR', 'docente_seccion', NULL, '2026-01-26 12:05:48', NULL, '{\"id_usuario\":\"4\",\"id_seccion\":\"10\",\"id_materia\":\"11\",\"error\":\"Duplicate entry \'4-10\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(417, 2, 'ERROR', 'docente_seccion', NULL, '2026-01-26 12:28:46', NULL, '{\"id_usuario\":\"4\",\"id_seccion\":\"10\",\"id_materia\":\"11\",\"error\":\"Duplicate entry \'4-10\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(418, 2, 'INSERT', 'docente_seccion', 23, '2026-01-26 12:29:51', NULL, '{\"id_usuario\":\"4\",\"docente_nombre\":\"hector\",\"docente_cedula\":\"123456789\",\"id_seccion\":\"10\",\"seccion_codigo\":\"1-70\",\"carrera_seccion\":\"Informatica\",\"id_materia\":\"11\",\"materia_nombre\":\"Algor\\u00edtmica y Programaci\\u00f3n\",\"materia_codigo\":\"APT1312\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(419, 4, 'LOGIN', 'users', 4, '2026-01-26 12:36:38', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(420, 4, 'LOGOUT', 'users', 4, '2026-01-26 12:37:14', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(421, 2, 'LOGIN', 'users', 2, '2026-01-26 12:37:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(422, 2, 'LOGOUT', 'users', 2, '2026-01-26 12:38:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(423, 4, 'LOGIN', 'users', 4, '2026-01-26 12:38:28', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(424, 4, 'DENEGADO', 'users', 4, '2026-01-26 13:13:03', NULL, '{\"permiso_solicitado\":\"editar_nota\",\"usuario\":\"hero\",\"usuario_id\":\"4\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Control de Acceso', 'Acceso denegado a: editar_nota'),
(425, 2, 'LOGIN', 'users', 2, '2026-01-26 13:13:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(426, 2, 'CONSULTA', 'users', 5, '2026-01-26 13:13:21', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(427, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-26 13:13:56', NULL, '{\"cantidad_grupos\":1,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(428, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-26 13:14:04', NULL, '{\"cantidad_grupos\":1,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(429, 2, 'LOGOUT', 'users', 2, '2026-01-26 13:16:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(430, 4, 'LOGIN', 'users', 4, '2026-01-26 13:16:58', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(431, 4, 'LOGOUT', 'users', 4, '2026-01-26 14:24:34', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(432, 2, 'LOGIN', 'users', 2, '2026-01-26 14:24:37', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(433, 2, 'CONSULTA', 'users', 5, '2026-01-26 14:24:44', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(434, 2, 'CONSULTA', 'users', 5, '2026-01-26 14:33:55', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(435, 2, 'CONSULTA', 'users', 5, '2026-01-26 14:42:53', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(436, 2, 'CONSULTA', 'users', 5, '2026-01-26 14:44:04', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(437, 2, 'LOGIN', 'users', 2, '2026-01-28 09:35:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(438, 2, 'CONSULTA', 'users', 5, '2026-01-28 09:35:30', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(439, 2, 'SELECT', 'users', NULL, '2026-01-28 09:35:49', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(440, 2, 'SELECT', 'users', NULL, '2026-01-28 09:35:53', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(441, 2, 'SELECT', 'users', NULL, '2026-01-28 09:36:08', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(442, 2, 'CONSULTA', 'users', 5, '2026-01-28 09:57:34', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(443, 5, 'LOGIN', 'users', 5, '2026-01-28 09:57:55', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(444, 5, 'DENEGADO', 'users', 5, '2026-01-28 09:58:58', NULL, '{\"permiso_solicitado\":\"admin\",\"usuario\":\"heroestudiante\",\"usuario_id\":\"5\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Control de Acceso', 'Acceso denegado a: admin'),
(445, 2, 'LOGIN', 'users', 2, '2026-01-28 09:59:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(446, 2, 'SELECT', 'users', NULL, '2026-01-28 09:59:10', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(447, 2, 'CONSULTA', 'users', 2607, '2026-01-28 09:59:20', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(448, 2, 'CONSULTA', 'users', 5, '2026-01-28 09:59:30', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(449, 2, 'CONSULTA', 'users', 2607, '2026-01-28 09:59:40', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(450, 2, 'CONSULTA', 'users', 5, '2026-01-28 09:59:50', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(451, 2, 'SELECT', 'users', NULL, '2026-01-28 10:01:32', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(452, 2, 'SELECT', 'users', NULL, '2026-01-28 10:25:30', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(453, 2, 'SELECT', 'users', NULL, '2026-01-28 10:25:41', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":3}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(454, 2, 'CONSULTA', 'users', 2560, '2026-01-28 10:50:52', NULL, '{\"cedula_buscada\":\"E-22789012\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2560,\"nombre_estudiante\":\"Alberto M\\u00e1rquez\",\"cedula\":\"E-22789012\",\"id_carrera\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(455, 2, 'CONSULTA', 'notas_definitivas', 2560, '2026-01-28 10:50:52', NULL, '{\"id_estudiante\":2560,\"cedula_estudiante\":\"E-22789012\",\"nombre_estudiante\":\"Alberto M\\u00e1rquez\",\"id_carrera\":2,\"carrera_nombre\":\"Turismo\",\"apto_tsu\":true,\"apto_grado_completo\":true,\"porcentaje_tsu\":100,\"porcentaje_completo\":100,\"tipo_aptitud\":\"GRADO_COMPLETO\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Grado', 'Consulta de aptitud para grado - GRADO_COMPLETO'),
(456, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-28 11:03:02', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(457, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-01-28 11:03:31', NULL, '{\"cantidad_grupos\":2,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(458, 2, 'SELECT', 'users', NULL, '2026-01-28 11:05:55', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(459, 2, 'SELECT', 'users', NULL, '2026-01-28 11:05:57', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":3}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(460, 2, 'SELECT', 'users', NULL, '2026-01-28 11:40:42', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":3}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(461, 2, 'INSERT', 'graduados', 2, '2026-01-28 12:42:57', NULL, '{\"estado\":\"graduado\",\"id_usuario\":\"2560\",\"estudiante_nombre\":\"Alberto M\\u00e1rquez\",\"estudiante_cedula\":\"E-22789012\",\"carrera\":\"Turismo\",\"id_admin_graduacion\":1,\"observaciones\":\"prueba\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Estudiante marcado como graduado'),
(462, 2, 'SELECT', 'users', NULL, '2026-01-28 12:42:58', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(463, 2, 'SELECT', 'users', NULL, '2026-01-28 12:43:37', NULL, '{\"filtros_aplicados\":{\"pagina\":\"1\",\"buscar\":\"\",\"estado\":\"graduado\",\"carrera\":\"\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(464, 2, 'UPDATE', 'graduados', 2, '2026-01-28 12:43:40', '{\"titulo_entregado\":\"0\",\"estado\":\"graduado\"}', '{\"titulo_entregado\":1,\"estado\":\"titulo_entregado\",\"id_usuario\":\"2560\",\"estudiante_nombre\":\"Alberto M\\u00e1rquez\",\"estudiante_cedula\":\"E-22789012\",\"carrera\":\"Turismo\",\"id_admin_entrega_titulo\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Título marcado como entregado'),
(465, 2, 'SELECT', 'users', NULL, '2026-01-28 12:43:40', NULL, '{\"filtros_aplicados\":{\"pagina\":\"1\",\"buscar\":\"\",\"estado\":\"graduado\",\"carrera\":\"\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(466, 2, 'SELECT', 'users', NULL, '2026-01-28 12:43:45', NULL, '{\"filtros_aplicados\":{\"pagina\":\"1\",\"buscar\":\"\",\"estado\":\"titulo_entregado\",\"carrera\":\"\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(467, 2, 'SELECT', 'users', NULL, '2026-01-28 12:43:57', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(468, 2, 'INSERT', 'carreras', 15, '2026-01-28 12:50:21', NULL, '{\"nombre_carrera\":\"Mecanica Automotriz\",\"cod_carrera\":\"12932\",\"tipo_formacion\":\"2\",\"duracion_semestres\":6,\"duracion_anios\":3,\"titulo_principal\":\"TSU Mecanica Automotriz\",\"titulo_opcional\":\"\",\"vigencia_fecha\":\"2026-01-28 00:00:00\",\"activa\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Carreras', 'Nueva carrera registrada'),
(469, 2, 'SELECT', 'users', NULL, '2026-01-28 12:59:39', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(470, 2, 'SELECT', 'users', NULL, '2026-01-28 12:59:42', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(471, 2, 'SELECT', 'users', NULL, '2026-01-28 13:03:03', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(472, 2, 'SELECT', 'users', NULL, '2026-01-28 13:18:13', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(473, 2, 'SELECT', 'users', NULL, '2026-01-28 13:18:35', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(474, 2, 'UPDATE', 'users', 2, '2026-01-28 13:20:21', '{\"username\":\"V-12345678\",\"usuario\":1,\"estudiante\":1,\"docente\":1,\"admin\":1,\"super_user\":0,\"editar_user\":1,\"editar_nota\":1,\"editar_acceso\":1,\"editar_valores\":1,\"editar_estudiante\":1,\"agregar_estudiante\":1,\"agregar_docente\":1,\"editar_docente\":1,\"agregar_carrera\":1,\"agregar_materia\":1,\"editar_materia\":1,\"pagos\":1,\"auditoria\":1,\"secciones\":1,\"rela_materia_carrera\":1,\"periodos_academicos\":1,\"asig_secciones\":1,\"asig_cursos\":1,\"horarios\":1,\"gestion_director_carrera\":1,\"notas_cargadas\":1,\"consultar_notas\":1,\"consultar_notas_pasadas\":1,\"tipos_pago\":1,\"tipos_horario\":1,\"horario_personal\":1,\"respaldo_bd\":1,\"gestionar_carrera\":1,\"gestion_periodo_academico\":1,\"gestion_asig_cursos\":1,\"gestion_horario\":1,\"titulos_re_materia\":1,\"grado\":1,\"gestion_grado\":1,\"visita\":1}', '{\"usuario_afectado\":\"V-12345678\",\"usuario_afectado_id\":2,\"usuario_editor\":\"V-12345678\",\"usuario_editor_id\":\"2\",\"accesos_otorgados\":\"super_user\",\"accesos_quitados\":\"\",\"total_otorgados\":1,\"total_quitados\":0,\"super_user_anterior\":0,\"super_user_nuevo\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Gestión de Permisos', 'Permisos actualizados para usuario: V-12345678 - Accesos OTORGADOS: super_user'),
(475, 2, 'SELECT', 'users', NULL, '2026-01-28 13:20:42', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(476, 2, 'SELECT', 'users', NULL, '2026-01-28 13:20:45', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(477, 2, 'SELECT', 'users', NULL, '2026-01-28 13:31:53', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(478, 2, 'SELECT', 'users', NULL, '2026-01-28 13:33:49', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(479, 2, 'SELECT', 'users', NULL, '2026-01-28 13:33:56', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":108}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(480, 2, 'SELECT', 'users', NULL, '2026-01-28 13:34:06', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(481, 2, 'SELECT', 'users', NULL, '2026-01-28 13:39:43', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(482, 2, 'SELECT', 'users', NULL, '2026-01-28 13:45:19', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(483, 2, 'SELECT', 'users', NULL, '2026-01-28 13:45:39', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(484, 2, 'SELECT', 'users', NULL, '2026-01-28 13:47:02', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(485, 2, 'SELECT', 'users', NULL, '2026-01-28 13:49:47', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(486, 2, 'SELECT', 'users', NULL, '2026-01-28 13:56:47', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(487, 2, 'SELECT', 'users', NULL, '2026-01-28 14:21:41', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(488, 2, 'SELECT', 'users', NULL, '2026-01-28 14:22:03', NULL, '{\"filtros_aplicados\":{\"estado\":\"cumple_requisitos\",\"pagina\":\"1\"},\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(489, 2, 'LOGIN', 'users', 2, '2026-01-29 09:28:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(490, 2, 'CONSULTA', 'users', 5, '2026-01-29 09:29:34', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(491, 2, 'CONSULTA', 'users', 2607, '2026-01-29 09:29:56', NULL, '{\"cedula_buscada\":\"V-12345678\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2607,\"nombre_estudiante\":\"Nombre Ejemplo\",\"cedula\":\"V-12345678\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(492, 2, 'CONSULTA', 'users', 5, '2026-01-29 09:30:03', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(493, 2, 'CONSULTA', 'users', 5, '2026-01-29 10:20:32', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(494, 2, 'CONSULTA', 'users', 5, '2026-01-29 10:21:18', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(495, 2, 'CONSULTA', 'users', 5, '2026-01-29 11:11:44', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(496, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:09:14', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(497, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:27:38', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(498, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:27:48', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(499, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:29:38', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(500, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:32:02', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(501, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:50:04', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(502, 2, 'LOGIN', 'users', 2, '2026-01-29 12:50:58', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(503, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:51:06', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(504, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:51:22', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(505, 2, 'CONSULTA', 'users', 5, '2026-01-29 12:55:27', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(506, 2, 'CONSULTA', 'users', 5, '2026-01-29 13:17:40', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(507, 2, 'CONSULTA', 'users', 5, '2026-01-29 13:37:28', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(508, 2, 'LOGIN', 'users', 2, '2026-01-30 09:13:12', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(509, 2, 'INSERT', 'materias', 33, '2026-01-30 09:17:19', NULL, '{\"cod_materia\":\"MAT -154\",\"nombre_materia\":\"Matem\\u00e1tica I\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":4,\"activa\":1,\"horas_teoricas\":3,\"horas_practicas\":2,\"horas_laboratorio\":0,\"horas_semanales\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(510, 2, 'INSERT', 'carrera_materia', 37, '2026-01-30 09:18:13', NULL, '{\"id_carrera\":15,\"carrera_nombre\":\"Mecanica Automotriz\",\"carrera_codigo\":\"12932\",\"id_materia\":29,\"materia_nombre\":\"Matem\\u00e1tica I\",\"materia_codigo\":\"MAT-154\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(511, 2, 'INSERT', 'materias', 34, '2026-01-30 09:20:21', NULL, '{\"cod_materia\":\"GEA -142\",\"nombre_materia\":\"GEOMETR\\u00cdA ANAL\\u00cdTICA\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":2,\"activa\":1,\"horas_teoricas\":1,\"horas_practicas\":3,\"horas_laboratorio\":0,\"horas_semanales\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(512, 2, 'INSERT', 'carrera_materia', 38, '2026-01-30 09:20:38', NULL, '{\"id_carrera\":15,\"carrera_nombre\":\"Mecanica Automotriz\",\"carrera_codigo\":\"12932\",\"id_materia\":34,\"materia_nombre\":\"GEOMETR\\u00cdA ANAL\\u00cdTICA\",\"materia_codigo\":\"GEA -142\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(513, 2, 'UPDATE', 'carreras', 15, '2026-01-30 09:21:46', '{\"nombre_carrera\":\"Mecanica Automotriz\",\"cod_carrera\":\"12932\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Carreras', 'Actualización de datos de carrera'),
(514, 2, 'INSERT', 'materias', 35, '2026-01-30 10:17:14', NULL, '{\"cod_materia\":\"LDIEP006000\",\"nombre_materia\":\"Instituciones de Educaci\\u00f3n Universitaria y los  Programas Nacionales de Formaci\\u00f3n\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":0,\"activa\":1,\"horas_teoricas\":1,\"horas_practicas\":1,\"horas_laboratorio\":0,\"horas_semanales\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(515, 2, 'INSERT', 'carrera_materia', 39, '2026-01-30 10:18:25', NULL, '{\"id_carrera\":5,\"carrera_nombre\":\"Logistica y Distribucion\",\"carrera_codigo\":\"14231\",\"id_materia\":35,\"materia_nombre\":\"Instituciones de Educaci\\u00f3n Universitaria y los  Programas Nacionales de Formaci\\u00f3n\",\"materia_codigo\":\"LDIEP006000\",\"semestre\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Carreras-Materias', 'Asignación de materia a carrera'),
(516, 2, 'UPDATE', 'materias', 35, '2026-01-30 10:24:55', '{\"trayecto\":1}', '{\"trayecto\":\"0\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Actualización de datos de materia'),
(517, 2, 'INSERT', 'materias', 36, '2026-01-30 10:50:18', NULL, '{\"cod_materia\":\"LDEOE006000\",\"nombre_materia\":\"Expresi\\u00f3n Oral y Escrita\",\"pnf_ptf\":\"PTF\",\"duracion_periodo\":1,\"trayecto\":1,\"creditos\":0,\"activa\":1,\"horas_teoricas\":1,\"horas_practicas\":1,\"horas_laboratorio\":0,\"horas_semanales\":2}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Nueva materia creada'),
(518, 2, 'UPDATE', 'materias', 36, '2026-01-30 11:07:30', '{\"trayecto\":1}', '{\"trayecto\":\"0\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Materias', 'Actualización de datos de materia'),
(519, 2, 'LOGIN', 'users', 2, '2026-02-02 09:41:28', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(520, 2, 'LOGOUT', 'users', 2, '2026-02-02 10:08:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(521, 2, 'LOGIN', 'users', 2, '2026-02-02 10:08:45', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(522, 2, 'DELETE', 'respaldos_descargas', 2, '2026-02-02 10:24:06', '{\"id\":2,\"usuario\":\"PRUEBA\",\"nombre_archivo\":\"respaldo_PRUEBA_2025-09-08_12-43-25.sql\",\"fecha_descarga\":\"2025-09-08 12:43:25\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/135.0.0.0 Safari\\/537.36 OPR\\/120.0.0.0 (Edition std-2)\"}', '{\"usuario_eliminacion\":\"PRUEBA\",\"usuario_id_eliminacion\":\"2\",\"fecha_eliminacion\":\"2026-02-02 10:24:06\",\"dias_transcurridos\":146}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Eliminación de registro de respaldo: respaldo_PRUEBA_2025-09-08_12-43-25.sql'),
(523, 2, 'DELETE_SUCCESS', 'respaldos_descargas', 2, '2026-02-02 10:24:06', NULL, '{\"id_respaldo\":2,\"nombre_archivo\":\"respaldo_PRUEBA_2025-09-08_12-43-25.sql\",\"usuario_original\":\"PRUEBA\",\"fecha_descarga_original\":\"2025-09-08 12:43:25\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Respaldo eliminado exitosamente'),
(524, 2, 'DELETE', 'respaldos_descargas', 3, '2026-02-02 10:24:08', '{\"id\":3,\"usuario\":\"PRUEBA\",\"nombre_archivo\":\"respaldo_PRUEBA_2025-09-08_13-01-06.sql\",\"fecha_descarga\":\"2025-09-08 13:01:06\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/135.0.0.0 Safari\\/537.36 OPR\\/120.0.0.0 (Edition std-2)\"}', '{\"usuario_eliminacion\":\"PRUEBA\",\"usuario_id_eliminacion\":\"2\",\"fecha_eliminacion\":\"2026-02-02 10:24:08\",\"dias_transcurridos\":146}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Eliminación de registro de respaldo: respaldo_PRUEBA_2025-09-08_13-01-06.sql'),
(525, 2, 'DELETE_SUCCESS', 'respaldos_descargas', 3, '2026-02-02 10:24:09', NULL, '{\"id_respaldo\":3,\"nombre_archivo\":\"respaldo_PRUEBA_2025-09-08_13-01-06.sql\",\"usuario_original\":\"PRUEBA\",\"fecha_descarga_original\":\"2025-09-08 13:01:06\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Respaldo eliminado exitosamente'),
(526, 2, 'DELETE', 'respaldos_descargas', 4, '2026-02-02 10:24:11', '{\"id\":4,\"usuario\":\"PRUEBA\",\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-01_15-47-31.sql\",\"fecha_descarga\":\"2025-10-01 15:47:31\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/141.0.0.0 Safari\\/537.36\"}', '{\"usuario_eliminacion\":\"PRUEBA\",\"usuario_id_eliminacion\":\"2\",\"fecha_eliminacion\":\"2026-02-02 10:24:11\",\"dias_transcurridos\":123}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Eliminación de registro de respaldo: respaldo_PRUEBA_2025-10-01_15-47-31.sql'),
(527, 2, 'DELETE_SUCCESS', 'respaldos_descargas', 4, '2026-02-02 10:24:11', NULL, '{\"id_respaldo\":4,\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-01_15-47-31.sql\",\"usuario_original\":\"PRUEBA\",\"fecha_descarga_original\":\"2025-10-01 15:47:31\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Respaldo eliminado exitosamente'),
(528, 2, 'DELETE', 'respaldos_descargas', 5, '2026-02-02 10:24:14', '{\"id\":5,\"usuario\":\"PRUEBA\",\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-27_11-58-54.sql\",\"fecha_descarga\":\"2025-10-27 11:58:54\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/141.0.0.0 Safari\\/537.36\"}', '{\"usuario_eliminacion\":\"PRUEBA\",\"usuario_id_eliminacion\":\"2\",\"fecha_eliminacion\":\"2026-02-02 10:24:14\",\"dias_transcurridos\":97}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Eliminación de registro de respaldo: respaldo_PRUEBA_2025-10-27_11-58-54.sql'),
(529, 2, 'DELETE_SUCCESS', 'respaldos_descargas', 5, '2026-02-02 10:24:14', NULL, '{\"id_respaldo\":5,\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-27_11-58-54.sql\",\"usuario_original\":\"PRUEBA\",\"fecha_descarga_original\":\"2025-10-27 11:58:54\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Respaldo eliminado exitosamente'),
(530, 2, 'DELETE', 'respaldos_descargas', 6, '2026-02-02 10:24:35', '{\"id\":6,\"usuario\":\"PRUEBA\",\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-27_12-06-37.sql\",\"fecha_descarga\":\"2025-10-27 12:06:37\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/141.0.0.0 Safari\\/537.36\"}', '{\"usuario_eliminacion\":\"PRUEBA\",\"usuario_id_eliminacion\":\"2\",\"fecha_eliminacion\":\"2026-02-02 10:24:35\",\"dias_transcurridos\":97}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Eliminación de registro de respaldo: respaldo_PRUEBA_2025-10-27_12-06-37.sql'),
(531, 2, 'DELETE_SUCCESS', 'respaldos_descargas', 6, '2026-02-02 10:24:35', NULL, '{\"id_respaldo\":6,\"nombre_archivo\":\"respaldo_PRUEBA_2025-10-27_12-06-37.sql\",\"usuario_original\":\"PRUEBA\",\"fecha_descarga_original\":\"2025-10-27 12:06:37\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Sistema', 'Respaldo eliminado exitosamente'),
(532, 2, 'LOGIN', 'users', 2, '2026-02-05 10:57:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(533, 5, 'LOGIN', 'users', 5, '2026-02-10 14:48:44', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(534, 5, 'LOGOUT', 'users', 5, '2026-02-10 14:50:10', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(535, 2615, 'LOGIN', 'users', 2615, '2026-02-10 14:52:22', NULL, '{\"username\":\"o.connor\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(536, 2615, 'LOGOUT', 'users', 2615, '2026-02-10 14:52:39', NULL, '{\"username\":\"o.connor\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(537, 2, 'LOGIN', 'users', 2, '2026-02-10 14:52:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(538, 2, 'INSERT', 'users', 2616, '2026-02-10 14:55:17', NULL, '{\"idusuario\":\"E-30123458\",\"nombre\":\"Luis Miguel\",\"carrera\":\"5\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante'),
(539, 2, 'LOGOUT', 'users', 2, '2026-02-10 14:55:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(540, 2616, 'LOGIN', 'users', 2616, '2026-02-10 14:56:12', NULL, '{\"username\":\"luis.miguel\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(541, 2616, 'LOGOUT', 'users', 2616, '2026-02-10 15:06:30', NULL, '{\"username\":\"luis.miguel\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(542, 2, 'LOGIN', 'users', 2, '2026-02-12 09:50:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(543, 2, 'LOGOUT', 'users', 2, '2026-02-12 09:52:03', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(544, 4, 'LOGIN', 'users', 4, '2026-02-12 09:52:09', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(545, 4, 'LOGOUT', 'users', 4, '2026-02-12 09:52:25', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(546, 5, 'LOGIN', 'users', 5, '2026-02-12 09:52:28', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(547, 2, 'LOGIN', 'users', 2, '2026-02-12 09:58:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(548, 2, 'CONSULTA', 'users', 5, '2026-02-12 09:59:02', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(549, 2, 'LOGOUT', 'users', 2, '2026-02-12 10:29:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(550, 5, 'LOGIN', 'users', 5, '2026-02-12 10:29:51', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(551, 5, 'DENEGADO', 'users', 5, '2026-02-12 11:29:20', NULL, '{\"permiso_solicitado\":\"admin\",\"usuario\":\"heroestudiante\",\"usuario_id\":\"5\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/144.0.0.0 Safari\\/537.36\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Control de Acceso', 'Acceso denegado a: admin'),
(552, 2, 'LOGIN', 'users', 2, '2026-02-12 11:29:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(553, 2, 'CONSULTA', 'users', 5, '2026-02-12 11:29:32', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(554, 2, 'CONSULTA', 'users', 5, '2026-02-12 12:06:00', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(555, 2, 'LOGOUT', 'users', 2, '2026-02-12 12:55:45', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(556, 5, 'LOGIN', 'users', 5, '2026-02-12 12:55:50', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(557, 5, 'LOGOUT', 'users', 5, '2026-02-12 12:57:19', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(558, 2, 'LOGIN', 'users', 2, '2026-02-12 12:57:21', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(559, 2, 'LOGIN', 'users', 2, '2026-02-23 09:18:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(560, 2, 'INSERT', 'secciones', 12, '2026-02-23 09:27:41', NULL, '{\"codigo_seccion\":\"4-80\",\"id_carrera\":5,\"id_trayecto\":1,\"id_periodo\":5,\"capacidad_maxima\":30,\"inicia\":\"2026-02-23T07:00\",\"estatus\":\"inactiva\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Secciones', 'Creación de nueva sección'),
(561, 2, 'UPDATE', 'estudiante_seccion', 12, '2026-02-23 09:28:02', '{\"estudiantes_anteriores\":0,\"estudiantes_retirados\":0}', '{\"estudiantes_nuevos\":6,\"estudiantes_totales\":6,\"estudiantes_asignados\":[\"2600\",\"2602\",\"2598\",\"2599\",\"2597\",\"2616\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Secciones', 'Asignación de estudiantes a sección'),
(562, 2, 'INSERT', 'users', 2617, '2026-02-23 09:35:37', NULL, '{\"idusuario\":\"V-54123456\",\"nombre\":\"una pruba\",\"username\":\"V-54123456\",\"carrera\":\"5\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante. Username: V-54123456'),
(563, 2, 'INSERT', 'users', 2618, '2026-02-23 09:41:38', NULL, '{\"idusuario\":\"V-53123456\",\"nombre\":\"Otra Prueba\",\"username\":\"V-53123456\",\"carrera\":\"5\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante. Username: V-53123456'),
(564, 2, 'UPDATE', 'estudiante_seccion', 12, '2026-02-23 09:42:12', '{\"estudiantes_anteriores\":6,\"estudiantes_retirados\":0}', '{\"estudiantes_nuevos\":2,\"estudiantes_totales\":8,\"estudiantes_asignados\":[\"2600\",\"2602\",\"2598\",\"2599\",\"2597\",\"2616\",\"2618\",\"2617\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Secciones', 'Asignación de estudiantes a sección'),
(565, 2, 'INSERT', 'users', 2619, '2026-02-23 09:45:21', NULL, '{\"idusuario\":\"V-98123456\",\"nombre\":\"Diosito Otra Prueba\",\"username\":\"V-98123456\",\"carrera\":\"5\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante. Username: V-98123456'),
(566, 2, 'INSERT', 'users', 2620, '2026-02-23 09:47:58', NULL, '{\"idusuario\":\"V-45123456\",\"nombre\":\"Papadio Super Prueba\",\"username\":\"V-45123456\",\"carrera\":\"5\",\"status\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Estudiantes', 'Registro de nuevo estudiante. Username: V-45123456'),
(567, 2, 'UPDATE', 'secciones', 12, '2026-02-23 09:50:18', '{\"estatus\":\"inactiva\"}', '{\"estatus\":\"activa\",\"estudiantes_activos\":10,\"minimo_requerido\":10}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Secciones', 'Cambio de estado de sección'),
(568, 2, 'UPDATE', 'estudiante_seccion', 12, '2026-02-23 09:50:18', '{\"estudiantes_anteriores\":8,\"estudiantes_retirados\":0}', '{\"estudiantes_nuevos\":2,\"estudiantes_totales\":10,\"estudiantes_asignados\":[\"2600\",\"2602\",\"2619\",\"2598\",\"2599\",\"2597\",\"2616\",\"2618\",\"2620\",\"2617\"]}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Secciones', 'Asignación de estudiantes a sección'),
(569, 2, 'INSERT', 'docente_seccion', 24, '2026-02-23 09:50:33', NULL, '{\"id_usuario\":\"2\",\"docente_nombre\":\"PRUEBA\",\"docente_cedula\":\"12345678\",\"id_seccion\":\"12\",\"seccion_codigo\":\"4-80\",\"carrera_seccion\":\"Logistica y Distribucion\",\"id_materia\":\"35\",\"materia_nombre\":\"Instituciones de Educaci\\u00f3n Universitaria y los  Programas Nacionales de Formaci\\u00f3n\",\"materia_codigo\":\"LDIEP006000\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(570, 2, 'LOGIN', 'users', 2, '2026-02-24 09:44:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(571, 2, 'LOGIN', 'users', 2, '2026-03-02 09:12:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(572, 2, 'UPDATE', 'users', 2, '2026-03-02 10:34:26', '{\"vocero\":0}', '{\"vocero\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Voceros', 'Asignación/retirada de vocero para usuario: 12345678'),
(573, 2, 'UPDATE', 'users', 2, '2026-03-02 10:41:43', '{\"vocero\":1}', '{\"vocero\":0}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Voceros', 'Asignación/retirada de vocero para usuario: 12345678'),
(574, 2, 'UPDATE', 'users', 5, '2026-03-02 10:41:56', '{\"vocero\":0}', '{\"vocero\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Voceros', 'Asignación/retirada de vocero para usuario: V-30692052'),
(575, 5, 'LOGIN', 'users', 5, '2026-03-02 10:42:14', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(576, 5, 'LOGIN', 'users', 5, '2026-03-03 09:30:22', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(577, 5, 'LOGIN', 'users', 5, '2026-03-04 09:37:20', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(578, 5, 'LOGOUT', 'users', 5, '2026-03-04 10:31:40', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(579, 2, 'LOGIN', 'users', 2, '2026-03-12 09:20:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(580, 2, 'LOGOUT', 'users', 2, '2026-03-12 09:20:41', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(581, 5, 'LOGIN', 'users', 5, '2026-03-12 09:20:44', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(582, 5, 'LOGIN', 'users', 5, '2026-03-25 11:32:44', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(583, 5, 'SELECT', 'mensajeria', 20, '2026-03-25 11:55:47', NULL, '{\"usuario_id\":\"5\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(584, 5, 'INSERT', 'mensajeria', 29, '2026-03-25 12:11:33', NULL, '{\"id_usuario_remitente\":\"5\",\"id_usuario_destinatario\":4,\"titulo\":\"prueba de vocero\",\"mensaje_length\":6}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Nuevo mensaje enviado'),
(585, 5, 'SELECT', 'mensajeria', 29, '2026-03-25 12:11:38', NULL, '{\"usuario_id\":\"5\",\"tipo_mensaje\":\"enviados\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(586, 5, 'LOGOUT', 'users', 5, '2026-03-25 12:11:54', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(587, 4, 'LOGIN', 'users', 4, '2026-03-25 12:12:03', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(588, 4, 'SELECT', 'mensajeria', 29, '2026-03-25 12:12:22', NULL, '{\"usuario_id\":\"4\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(589, 4, 'UPDATE', 'mensajeria', 29, '2026-03-25 12:12:23', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"4\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(590, 4, 'SELECT', 'mensajeria', 29, '2026-03-25 12:24:02', NULL, '{\"usuario_id\":\"4\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(591, 4, 'SELECT', 'mensajeria', 28, '2026-03-25 12:24:19', NULL, '{\"usuario_id\":\"4\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(592, 2, 'LOGIN', 'users', 2, '2026-03-25 12:26:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(593, 2, 'LOGOUT', 'users', 2, '2026-05-03 15:29:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(594, 2, 'LOGIN', 'users', 2, '2026-05-03 15:31:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(595, 2, 'DELETE', 'secciones', 11, '2026-05-03 16:01:13', NULL, '{\"performed_by\":\"2\",\"seccion_id\":11}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Secciones', 'Eliminación de sección y limpieza de horarios/estudiantes'),
(596, 2, 'DELETE', 'secciones', 12, '2026-05-03 16:01:21', NULL, '{\"performed_by\":\"2\",\"seccion_id\":12}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Secciones', 'Eliminación de sección y limpieza de horarios/estudiantes'),
(597, 2, 'DELETE', 'secciones', 10, '2026-05-03 16:01:28', NULL, '{\"performed_by\":\"2\",\"seccion_id\":10}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Secciones', 'Eliminación de sección y limpieza de horarios/estudiantes');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(598, 2, 'DELETE', 'secciones', 9, '2026-05-03 16:01:31', NULL, '{\"performed_by\":\"2\",\"seccion_id\":9}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'Secciones', 'Eliminación de sección y limpieza de horarios/estudiantes'),
(599, 2, 'LOGIN', 'users', 2, '2026-05-10 15:17:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(600, 2, 'LOGOUT', 'users', 2, '2026-05-10 15:18:17', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(601, 2, 'LOGIN', 'users', 2, '2026-05-10 15:19:28', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(602, 2, 'UPDATE', 'carreras', 1, '2026-05-10 15:22:58', '{\"nombre_carrera\":\"Informatica\",\"cod_carrera\":\"14232\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Carreras', 'Actualización de datos de carrera'),
(603, 2, 'UPDATE', 'carreras', 14, '2026-05-10 15:23:11', '{\"nombre_carrera\":\"Mecanica\",\"cod_carrera\":\"13351\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Carreras', 'Actualización de datos de carrera'),
(604, 2, 'UPDATE', 'carreras', 5, '2026-05-10 15:23:28', '{\"nombre_carrera\":\"Logistica y Distribucion\",\"cod_carrera\":\"14231\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Carreras', 'Actualización de datos de carrera'),
(605, 2, 'UPDATE', 'carreras', 15, '2026-05-10 15:23:42', '{\"nombre_carrera\":\"Mecanica Automotriz\",\"cod_carrera\":\"12932\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Carreras', 'Actualización de datos de carrera'),
(606, 2, 'UPDATE', 'carreras', 2, '2026-05-10 15:23:57', '{\"nombre_carrera\":\"Turismo\",\"cod_carrera\":\"13569\"}', '{\"nombre_carrera\":null,\"cod_carrera\":null}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Carreras', 'Actualización de datos de carrera'),
(607, 2, 'LOGOUT', 'users', 2, '2026-05-10 17:50:19', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(608, 2, 'LOGIN', 'users', 2, '2026-05-10 17:53:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(609, 2, 'LOGOUT', 'users', 2, '2026-05-10 17:55:12', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(610, 2624, 'LOGIN', 'users', 2624, '2026-05-10 17:55:18', NULL, '{\"username\":\"E12345654\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(611, 2624, 'LOGOUT', 'users', 2624, '2026-05-10 18:00:33', NULL, '{\"username\":\"E12345654\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(612, 2, 'LOGIN', 'users', 2, '2026-05-10 18:06:21', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(613, 2, 'LOGOUT', 'users', 2, '2026-05-10 18:41:48', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(614, 2, 'LOGIN', 'users', 2, '2026-05-10 18:43:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(615, 2, 'LOGOUT', 'users', 2, '2026-05-10 18:44:34', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(616, 2, 'LOGIN', 'users', 2, '2026-05-10 18:48:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(617, 2, 'LOGOUT', 'users', 2, '2026-05-10 18:48:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(618, 2, 'LOGIN', 'users', 2, '2026-05-10 18:50:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Inicio de sesión exitoso'),
(619, 2, 'LOGOUT', 'users', 2, '2026-05-10 20:15:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'Autenticación', 'Cierre de sesión del sistema'),
(620, 2, 'LOGIN', 'users', 2, '2026-05-11 09:25:58', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(621, 2, 'LOGOUT', 'users', 2, '2026-05-11 09:30:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(622, 2, 'LOGIN', 'users', 2, '2026-05-11 09:32:31', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(623, 2, 'LOGOUT', 'users', 2, '2026-05-11 15:26:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(624, 2, 'LOGIN', 'users', 2, '2026-05-11 15:29:28', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(625, 2, 'LOGOUT', 'users', 2, '2026-05-11 15:29:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(626, 2, 'LOGIN', 'users', 2, '2026-05-13 09:10:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(627, 2, 'LOGOUT', 'users', 2, '2026-05-13 09:10:32', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(628, 2, 'LOGIN', 'users', 2, '2026-05-13 09:56:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(629, 2, 'UPDATE', 'secciones', 14, '2026-05-13 09:57:23', '{\"codigo_seccion\":\"71\",\"capacidad_maxima\":1,\"inicia\":\"2026-05-10 17:15:00\"}', '{\"codigo_seccion\":\"\",\"capacidad_maxima\":3,\"inicia\":\"2026-05-10T17:15\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Secciones', 'Edición de datos de sección'),
(630, 2, 'LOGOUT', 'users', 2, '2026-05-13 09:58:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(631, 2, 'LOGIN', 'users', 2, '2026-05-13 10:33:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(632, 2, 'LOGOUT', 'users', 2, '2026-05-13 10:44:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(633, 2, 'LOGIN', 'users', 2, '2026-05-13 11:49:00', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(634, 2, 'LOGIN', 'users', 2, '2026-05-14 09:42:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(635, 2, 'DELETE', 'secciones', 16, '2026-05-14 09:45:27', NULL, '{\"performed_by\":\"2\",\"seccion_id\":16}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Secciones', 'Eliminación de sección y limpieza de horarios/estudiantes'),
(636, 2, 'LOGOUT', 'users', 2, '2026-05-14 11:04:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(637, 2, 'LOGIN', 'users', 2, '2026-05-14 11:04:43', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(638, 2, 'LOGOUT', 'users', 2, '2026-05-14 11:24:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(639, 2, 'LOGIN', 'users', 2, '2026-05-14 11:26:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(640, 2, 'LOGOUT', 'users', 2, '2026-05-14 11:30:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(641, 2, 'LOGIN', 'users', 2, '2026-05-14 11:30:43', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(642, 2, 'LOGOUT', 'users', 2, '2026-05-14 11:44:58', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(643, 2, 'LOGIN', 'users', 2, '2026-05-14 11:49:13', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(644, 2, 'INSERT', 'docente_seccion', 25, '2026-05-14 11:51:25', NULL, '{\"id_usuario\":\"2585\",\"docente_nombre\":\"Alberto Lopez\",\"docente_cedula\":\"V-13123524\",\"id_seccion\":\"13\",\"seccion_codigo\":\"70\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"10\",\"materia_nombre\":\"Arquitectura del Computador\",\"materia_codigo\":\"ACT139\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(645, 2, 'LOGIN', 'users', 2, '2026-05-15 09:31:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(646, 2, 'LOGOUT', 'users', 2, '2026-05-15 09:38:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(647, 2, 'LOGIN', 'users', 2, '2026-05-15 09:38:46', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(648, 2, 'INSERT', 'docente_seccion', 26, '2026-05-15 10:35:49', NULL, '{\"id_usuario\":\"4\",\"docente_nombre\":\"hector\",\"docente_cedula\":\"123456789\",\"id_seccion\":\"14\",\"seccion_codigo\":\"71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"9\",\"materia_nombre\":\"Introducci\\u00f3n a los Proyectos y al PNF\",\"materia_codigo\":\"IPC012\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(649, 2, 'INSERT', 'docente_seccion', 27, '2026-05-15 10:40:04', NULL, '{\"id_usuario\":\"1\",\"docente_nombre\":\"J.E Suministros y Mas, C.A.\",\"docente_cedula\":\"J-294444890\",\"id_seccion\":\"14\",\"seccion_codigo\":\"71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"5\",\"materia_nombre\":\"Matematica\",\"materia_codigo\":\"MAC015\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(650, 2, 'LOGOUT', 'users', 2, '2026-05-15 10:41:39', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(651, 2629, 'LOGIN', 'users', 2629, '2026-05-15 10:41:53', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(652, 2629, 'LOGOUT', 'users', 2629, '2026-05-15 10:45:46', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(653, 2, 'LOGIN', 'users', 2, '2026-05-15 10:45:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(654, 2628, 'LOGIN', 'users', 2628, '2026-05-15 12:17:57', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(655, 2628, 'LOGOUT', 'users', 2628, '2026-05-15 12:36:39', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(656, 2, 'LOGIN', 'users', 2, '2026-05-15 12:36:42', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(657, 2, 'LOGIN', 'users', 2, '2026-05-15 12:39:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(658, 2, 'LOGOUT', 'users', 2, '2026-05-15 12:39:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(659, 2629, 'LOGIN', 'users', 2629, '2026-05-15 12:39:32', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(660, 2629, 'LOGOUT', 'users', 2629, '2026-05-15 12:40:06', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(661, 2, 'LOGIN', 'users', 2, '2026-05-15 12:40:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(662, 2, 'LOGOUT', 'users', 2, '2026-05-15 12:41:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(663, 2629, 'LOGIN', 'users', 2629, '2026-05-15 12:41:12', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(664, 2629, 'LOGOUT', 'users', 2629, '2026-05-15 13:27:21', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(665, 2, 'LOGIN', 'users', 2, '2026-05-15 13:27:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(666, 2, 'LOGOUT', 'users', 2, '2026-05-15 13:27:48', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(667, 2, 'LOGIN', 'users', 2, '2026-05-15 13:27:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(668, 2629, 'LOGIN', 'users', 2629, '2026-05-15 13:28:15', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(669, 2629, 'LOGOUT', 'users', 2629, '2026-05-15 13:28:36', NULL, '{\"username\":\"E98653265\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(670, 2, 'LOGIN', 'users', 2, '2026-05-15 13:28:53', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(671, 2, 'LOGIN', 'users', 2, '2026-05-18 10:33:34', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(672, 2, 'LOGOUT', 'users', 2, '2026-05-18 11:51:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(673, 2, 'LOGIN', 'users', 2, '2026-05-18 11:51:43', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(674, 2, 'LOGOUT', 'users', 2, '2026-05-18 11:52:30', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(675, 2628, 'LOGIN', 'users', 2628, '2026-05-18 11:52:38', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(676, 2628, 'LOGOUT', 'users', 2628, '2026-05-18 11:54:09', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(677, 2, 'LOGIN', 'users', 2, '2026-05-18 11:54:11', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(678, 2, 'LOGIN', 'users', 2, '2026-05-19 09:30:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(679, 2, 'LOGOUT', 'users', 2, '2026-05-19 11:31:10', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(680, 2, 'LOGIN', 'users', 2, '2026-05-19 11:31:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(681, 2, 'LOGOUT', 'users', 2, '2026-05-19 11:31:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(682, 2628, 'LOGIN', 'users', 2628, '2026-05-19 11:32:03', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(683, 2628, 'LOGOUT', 'users', 2628, '2026-05-19 12:09:30', NULL, '{\"username\":\"E46598763\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(684, 2, 'LOGIN', 'users', 2, '2026-05-19 12:09:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(685, 2, 'SELECT', 'users', NULL, '2026-05-19 12:11:58', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":115}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(686, 2, 'SELECT', 'users', NULL, '2026-05-19 12:29:59', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":115}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(687, 2, 'SELECT', 'users', NULL, '2026-05-19 12:30:03', NULL, '{\"filtros_aplicados\":[],\"pagina\":1,\"registros_por_pagina\":20,\"total_registros\":115}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Graduación', 'Consulta de estudiantes para graduación'),
(688, 2, 'CONSULTA', 'users', 5, '2026-05-19 13:43:58', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(689, 2, 'CONSULTA', 'users', 2628, '2026-05-19 13:44:55', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(690, 2, 'LOGIN', 'users', 2, '2026-05-20 09:32:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(691, 2, 'LOGOUT', 'users', 2, '2026-05-20 09:40:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(692, 2, 'LOGIN', 'users', 2, '2026-05-20 09:43:21', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(693, 2, 'LOGOUT', 'users', 2, '2026-05-20 11:44:37', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(694, 2, 'LOGIN', 'users', 2, '2026-05-20 11:46:01', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(695, 2, 'LOGOUT', 'users', 2, '2026-05-20 12:15:51', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(696, 2, 'LOGIN', 'users', 2, '2026-05-20 12:15:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(697, 2, 'LOGOUT', 'users', 2, '2026-05-20 13:51:58', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(698, 2, 'LOGIN', 'users', 2, '2026-05-20 13:52:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(699, 2, 'LOGIN', 'users', 2, '2026-05-25 08:59:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(700, 2, 'CONSULTA', 'users', 2459, '2026-05-25 09:12:06', NULL, '{\"cedula_buscada\":\"11\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2459,\"nombre_estudiante\":\"Adriana Castro\",\"cedula\":\"11\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(701, 2, 'CONSULTA', 'users', 5, '2026-05-25 09:12:43', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(702, 2, 'LOGOUT', 'users', 2, '2026-05-25 09:36:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(703, 4, 'LOGIN', 'users', 4, '2026-05-25 09:36:27', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(704, 4, 'LOGOUT', 'users', 4, '2026-05-25 11:35:27', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(705, 2, 'LOGIN', 'users', 2, '2026-05-25 11:35:31', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(706, 2, 'CONSULTA', 'users', 5, '2026-05-25 11:35:39', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(707, 2, 'CONSULTA', 'users', 5, '2026-05-25 12:29:05', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(708, 2, 'CONSULTA', 'users', 5, '2026-05-25 12:35:31', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(709, 2, 'LOGOUT', 'users', 2, '2026-05-25 12:36:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(710, 4, 'LOGIN', 'users', 4, '2026-05-25 12:36:25', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(711, 4, 'LOGOUT', 'users', 4, '2026-05-25 12:41:52', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(712, 2, 'LOGIN', 'users', 2, '2026-05-25 12:41:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(713, 2, 'CONSULTA', 'users', 5, '2026-05-25 12:42:01', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(714, 2, 'CONSULTA', 'users', 5, '2026-05-25 13:39:45', NULL, '{\"cedula_buscada\":\"V-30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":5,\"nombre_estudiante\":\"Hector\",\"cedula\":\"V-30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(715, 2, 'CONSULTA', 'users', 2628, '2026-05-25 13:40:19', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(716, 2, 'CONSULTA', 'users', 2628, '2026-05-25 13:48:47', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(717, 2, 'CONSULTA', 'users', 2628, '2026-05-25 13:58:12', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(718, 2, 'CONSULTA', 'users', 2628, '2026-05-25 14:26:00', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(719, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-25 14:27:05', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(720, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-25 14:27:10', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(721, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-25 14:27:14', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(722, 2, 'CONSULTA', 'users', 2628, '2026-05-25 14:27:24', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(723, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:28:18', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(724, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:40:56', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(725, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:40:59', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(726, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:43:09', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(727, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:43:13', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(728, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:49:37', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(729, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:49:41', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(730, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:49:48', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(731, 2, 'CONSULTA', 'users', 2630, '2026-05-25 14:50:15', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(732, 2, 'CONSULTA', 'users', 2630, '2026-05-25 15:05:15', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(733, 2, 'CONSULTA', 'users', 2630, '2026-05-25 15:07:59', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(734, 2, 'CONSULTA', 'users', 2630, '2026-05-25 15:26:37', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(735, 2, 'CONSULTA', 'users', 2630, '2026-05-25 15:28:38', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(736, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-25 15:54:12', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(737, 2, 'CONSULTA', 'users', 2630, '2026-05-25 15:54:20', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(738, 2, 'LOGIN', 'users', 2, '2026-05-26 09:04:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(739, 2, 'CONSULTA', 'users', 2630, '2026-05-26 09:04:34', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(740, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-26 09:04:47', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(741, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-26 09:06:26', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(742, 2, 'CONSULTA', 'users', 2630, '2026-05-26 09:40:25', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(743, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-26 11:25:44', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(744, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-26 11:25:51', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(745, 2, 'CONSULTA', 'notas_definitivas', NULL, '2026-05-26 11:25:55', NULL, '{\"cantidad_grupos\":0,\"filtros_aplicados\":\"ninguno\",\"filtro_profesor\":\"todos\",\"filtro_fecha_desde\":\"sin_filtro\",\"filtro_fecha_hasta\":\"sin_filtro\",\"tipo_consulta\":\"grupos_notas_definitivas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Notas Definitivas', 'Consulta de grupos de notas definitivas'),
(746, 2, 'LOGOUT', 'users', 2, '2026-05-26 12:43:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(747, 1, 'LOGIN', 'users', 1, '2026-05-26 12:46:31', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(748, 2, 'LOGIN', 'users', 2, '2026-05-27 08:13:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(749, 2, 'LOGOUT', 'users', 2, '2026-05-27 08:19:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(750, 1, 'LOGIN', 'users', 1, '2026-05-27 08:19:31', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(751, 2, 'LOGIN', 'users', 2, '2026-05-27 08:22:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(752, 2, 'CONSULTA', 'users', 2459, '2026-05-27 08:24:41', NULL, '{\"cedula_buscada\":\"11\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2459,\"nombre_estudiante\":\"Adriana Castro\",\"cedula\":\"11\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(753, 5, 'LOGIN', 'users', 5, '2026-05-27 11:12:21', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(754, 5, 'LOGOUT', 'users', 5, '2026-05-27 11:12:31', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(755, 2, 'LOGIN', 'users', 2, '2026-05-27 11:12:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(756, 2, 'LOGOUT', 'users', 2, '2026-05-27 11:13:31', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(757, 2630, 'LOGIN', 'users', 2630, '2026-05-27 11:13:55', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(758, 2630, 'LOGOUT', 'users', 2630, '2026-05-27 11:17:02', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(759, 2, 'LOGIN', 'users', 2, '2026-05-27 11:17:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(760, 2, 'UPDATE', 'users', 2630, '2026-05-27 11:17:16', '{\"vocero\":0}', '{\"vocero\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Voceros', 'Asignación/retirada de vocero para usuario: E14725836'),
(761, 2, 'LOGOUT', 'users', 2, '2026-05-27 11:17:24', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(762, 2630, 'LOGIN', 'users', 2630, '2026-05-27 11:17:31', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(763, 2630, 'CONSULTA', 'users', 2628, '2026-05-27 11:17:41', NULL, '{\"cedula_buscada\":\"E46598763\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2628,\"nombre_estudiante\":\"prueba de inscripcion notas\",\"cedula\":\"E46598763\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(764, 2630, 'CONSULTA', 'users', 2630, '2026-05-27 11:17:55', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(765, 2630, 'LOGOUT', 'users', 2630, '2026-05-27 11:18:10', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(766, 2, 'LOGIN', 'users', 2, '2026-05-27 11:20:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(767, 2, 'LOGOUT', 'users', 2, '2026-05-27 11:25:19', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(768, 2, 'LOGIN', 'users', 2, '2026-05-27 11:26:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(769, 2, 'CONSULTA', 'users', 2630, '2026-05-27 11:27:06', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(770, 2, 'LOGOUT', 'users', 2, '2026-05-27 11:27:32', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(771, 2, 'LOGIN', 'users', 2, '2026-05-27 11:29:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(772, 2, 'LOGOUT', 'users', 2, '2026-05-27 11:45:37', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(773, 2, 'LOGIN', 'users', 2, '2026-05-27 11:47:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(774, 2, 'LOGIN', 'users', 2, '2026-05-28 08:38:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(775, 2, 'CONSULTA', 'users', 2630, '2026-05-28 08:38:13', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(776, 2, 'LOGOUT', 'users', 2, '2026-05-28 08:39:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(777, 2630, 'LOGIN', 'users', 2630, '2026-05-28 08:39:12', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(778, 2630, 'LOGOUT', 'users', 2630, '2026-05-28 09:07:36', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(779, 2, 'LOGIN', 'users', 2, '2026-05-28 09:19:42', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(780, 2, 'LOGOUT', 'users', 2, '2026-05-28 09:21:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(781, 1, 'LOGIN', 'users', 1, '2026-05-28 09:21:22', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(782, 2, 'LOGIN', 'users', 2, '2026-05-29 08:53:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(783, 2, 'LOGOUT', 'users', 2, '2026-05-29 09:00:11', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(784, 1, 'LOGIN', 'users', 1, '2026-05-29 09:00:26', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(785, 1, 'LOGOUT', 'users', 1, '2026-05-29 09:27:34', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(786, 2, 'LOGIN', 'users', 2, '2026-05-29 09:27:37', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(787, 2, 'LOGOUT', 'users', 2, '2026-05-29 09:28:02', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(788, 1, 'LOGIN', 'users', 1, '2026-05-29 09:28:15', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(789, 1, 'LOGOUT', 'users', 1, '2026-05-29 09:29:16', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(790, 2, 'LOGIN', 'users', 2, '2026-05-29 09:29:17', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(791, 2, 'LOGOUT', 'users', 2, '2026-05-29 09:29:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(792, 1, 'LOGIN', 'users', 1, '2026-05-29 09:29:58', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(793, 1, 'LOGOUT', 'users', 1, '2026-05-29 13:04:01', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(794, 5, 'LOGIN', 'users', 5, '2026-05-29 13:04:07', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(795, 5, 'LOGOUT', 'users', 5, '2026-05-29 13:15:27', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(796, 1, 'LOGIN', 'users', 1, '2026-05-29 13:15:52', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(797, 2, 'LOGIN', 'users', 2, '2026-06-01 08:56:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(798, 2, 'LOGOUT', 'users', 2, '2026-06-01 11:17:41', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(799, 2633, 'LOGIN', 'users', 2633, '2026-06-01 11:17:50', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(800, 2633, 'LOGOUT', 'users', 2633, '2026-06-01 11:18:49', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(801, 1, 'LOGIN', 'users', 1, '2026-06-01 11:19:04', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(802, 1, 'LOGOUT', 'users', 1, '2026-06-01 11:19:26', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(803, 2, 'LOGIN', 'users', 2, '2026-06-01 11:19:30', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(804, 2, 'LOGIN', 'users', 2, '2026-06-02 09:17:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(805, 2, 'LOGOUT', 'users', 2, '2026-06-02 09:27:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(806, 1, 'LOGIN', 'users', 1, '2026-06-02 09:27:26', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(807, 1, 'LOGOUT', 'users', 1, '2026-06-02 09:28:36', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(808, 2, 'LOGIN', 'users', 2, '2026-06-02 09:28:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(809, 2, 'CONSULTA', 'users', 2630, '2026-06-02 09:28:58', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(810, 2, 'LOGIN', 'users', 2, '2026-06-03 09:02:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(811, 2, 'LOGOUT', 'users', 2, '2026-06-03 11:08:19', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(812, 1, 'LOGIN', 'users', 1, '2026-06-03 11:08:32', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(813, 1, 'LOGOUT', 'users', 1, '2026-06-03 11:09:08', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(814, 2633, 'LOGIN', 'users', 2633, '2026-06-03 11:09:16', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(815, 2633, 'LOGOUT', 'users', 2633, '2026-06-03 11:10:11', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(816, 2, 'LOGIN', 'users', 2, '2026-06-03 11:10:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(817, 1, 'LOGIN', 'users', 1, '2026-06-03 11:10:25', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(818, 2, 'LOGIN', 'users', 2, '2026-06-03 11:13:29', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(819, 2, 'LOGOUT', 'users', 2, '2026-06-03 11:14:53', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(820, 1, 'LOGIN', 'users', 1, '2026-06-03 11:15:06', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(821, 1, 'LOGOUT', 'users', 1, '2026-06-03 11:15:27', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(822, 2633, 'LOGIN', 'users', 2633, '2026-06-03 11:15:34', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(823, 2633, 'LOGOUT', 'users', 2633, '2026-06-03 11:17:27', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(824, 1, 'LOGIN', 'users', 1, '2026-06-03 11:17:35', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(825, 1, 'LOGOUT', 'users', 1, '2026-06-03 11:32:49', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(826, 2, 'LOGIN', 'users', 2, '2026-06-03 11:32:52', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(827, 2, 'LOGOUT', 'users', 2, '2026-06-03 11:32:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(828, 1, 'LOGIN', 'users', 1, '2026-06-03 11:33:07', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(829, 1, 'LOGOUT', 'users', 1, '2026-06-03 11:35:52', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(830, 2, 'LOGIN', 'users', 2, '2026-06-03 11:35:53', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(831, 2, 'ERROR', 'mensajeria', NULL, '2026-06-03 11:37:29', NULL, '{\"remitente_id\":\"2\",\"destinatario_id\":1,\"titulo\":\"\\u2705 Notas APROBADAS - Matematica\",\"error\":\"Conversion from collation utf8mb3_general_ci into utf8mb4_spanish_ci impossible for parameter\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Mensajería', 'Error al enviar mensaje'),
(832, 2, 'CONSULTA', 'users', 2633, '2026-06-03 11:37:59', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(833, 2, 'LOGOUT', 'users', 2, '2026-06-03 11:41:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(834, 1, 'LOGIN', 'users', 1, '2026-06-03 11:41:45', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(835, 1, 'LOGOUT', 'users', 1, '2026-06-03 11:42:24', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(836, 2, 'LOGIN', 'users', 2, '2026-06-03 11:42:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(837, 2, 'LOGOUT', 'users', 2, '2026-06-03 11:43:53', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(838, 2, 'LOGIN', 'users', 2, '2026-06-03 11:43:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(839, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:44:31', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(840, 2, 'DELETE', 'docente_seccion', 26, '2026-06-03 11:44:44', '{\"id_usuario\":4,\"docente_nombre\":\"hector\",\"docente_cedula\":\"123456789\",\"id_seccion\":14,\"seccion_codigo\":\"71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":9,\"materia_nombre\":\"Introducci\\u00f3n a los Proyectos y al PNF\",\"fecha_asignacion\":\"2026-05-15 10:35:49\"}', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Eliminación de asignación de sección a docente'),
(841, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:44:58', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(842, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:45:21', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(843, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:45:32', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(844, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:46:04', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(845, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:50:45', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(846, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:50:59', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(847, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:56:52', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(848, 2, 'ERROR', 'docente_seccion', NULL, '2026-06-03 11:58:15', NULL, '{\"id_usuario\":\"1\",\"id_seccion\":\"14\",\"id_materia\":\"9\",\"error\":\"Duplicate entry \'1-14\' for key \'docente_seccion.id_usuario\'\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Error al asignar sección a docente'),
(849, 2, 'INSERT', 'docente_seccion', 37, '2026-06-03 12:05:20', NULL, '{\"id_usuario\":\"1\",\"docente_nombre\":\"J.E Suministros y Mas, C.A.\",\"docente_cedula\":\"J-294444890\",\"id_seccion\":\"14\",\"seccion_codigo\":\"71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"9\",\"materia_nombre\":\"Introducci\\u00f3n a los Proyectos y al PNF\",\"materia_codigo\":\"IPC012\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(850, 2, 'LOGOUT', 'users', 2, '2026-06-03 12:27:02', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(851, 1, 'LOGIN', 'users', 1, '2026-06-03 12:27:14', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(852, 1, 'LOGOUT', 'users', 1, '2026-06-03 12:29:09', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(853, 2, 'LOGIN', 'users', 2, '2026-06-03 12:29:11', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(854, 2, 'LOGOUT', 'users', 2, '2026-06-03 12:49:10', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(855, 1, 'LOGIN', 'users', 1, '2026-06-03 12:49:16', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(856, 1, 'LOGOUT', 'users', 1, '2026-06-03 12:50:03', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(857, 2, 'LOGIN', 'users', 2, '2026-06-03 12:50:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(858, 2, 'ERROR', 'mensajeria', NULL, '2026-06-03 12:52:02', NULL, '{\"remitente_id\":\"2\",\"destinatario_id\":1,\"titulo\":\"\\u2705 Notas APROBADAS - Introducci\\u00f3n a los Proyectos y al PNF\",\"error\":\"Conversion from collation utf8mb3_general_ci into utf8mb4_spanish_ci impossible for parameter\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Mensajería', 'Error al enviar mensaje'),
(859, 2, 'LOGOUT', 'users', 2, '2026-06-03 12:52:22', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(860, 1, 'LOGIN', 'users', 1, '2026-06-03 12:52:31', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(861, 1, 'LOGOUT', 'users', 1, '2026-06-03 13:38:24', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(862, 2, 'LOGIN', 'users', 2, '2026-06-03 13:38:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(863, 2, 'LOGIN', 'users', 2, '2026-06-04 08:49:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(864, 2, 'ERROR', 'mensajeria', NULL, '2026-06-04 08:56:27', NULL, '{\"remitente_id\":\"2\",\"destinatario_id\":1,\"titulo\":\"\\u2705 Notas APROBADAS - Introducci\\u00f3n a los Proyectos y al PNF\",\"error\":\"Conversion from collation utf8mb3_general_ci into utf8mb4_spanish_ci impossible for parameter\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Error al enviar mensaje'),
(865, 2, 'LOGOUT', 'users', 2, '2026-06-04 09:31:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(866, 1, 'LOGIN', 'users', 1, '2026-06-04 09:32:06', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(867, 1, 'LOGOUT', 'users', 1, '2026-06-04 09:34:04', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(868, 2, 'LOGIN', 'users', 2, '2026-06-04 09:34:07', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(869, 2, 'ERROR', 'mensajeria', NULL, '2026-06-04 09:34:21', NULL, '{\"remitente_id\":\"2\",\"destinatario_id\":1,\"titulo\":\"\\u2705 Notas APROBADAS - Introducci\\u00f3n a los Proyectos y al PNF\",\"error\":\"Conversion from collation utf8mb3_general_ci into utf8mb4_spanish_ci impossible for parameter\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Error al enviar mensaje'),
(870, 2, 'CONSULTA', 'users', 2630, '2026-06-04 09:36:31', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(871, 5, 'LOGIN', 'users', 5, '2026-06-04 09:37:37', NULL, '{\"username\":\"heroestudiante\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(872, 5, 'DENEGADO', 'users', 5, '2026-06-04 09:37:56', NULL, '{\"permiso_solicitado\":\"asig_cursos\",\"usuario\":\"heroestudiante\",\"usuario_id\":\"5\",\"ip_address\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Acceso denegado a: asig_cursos'),
(873, 2, 'LOGIN', 'users', 2, '2026-06-04 09:38:02', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(874, 2, 'INSERT', 'docente_seccion', 40, '2026-06-04 09:38:48', NULL, '{\"id_usuario\":\"1\",\"docente_nombre\":\"J.E Suministros y Mas, C.A.\",\"docente_cedula\":\"J-294444890\",\"id_seccion\":\"14\",\"seccion_codigo\":\"71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"6\",\"materia_nombre\":\"Proyecto Nacional y Nueva Ciudadania\",\"materia_codigo\":\"PNS013\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(875, 2, 'LOGOUT', 'users', 2, '2026-06-04 09:39:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(876, 1, 'LOGIN', 'users', 1, '2026-06-04 09:40:05', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(877, 2, 'LOGIN', 'users', 2, '2026-06-04 09:41:20', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(878, 2, 'LOGOUT', 'users', 2, '2026-06-04 09:52:48', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(879, 1, 'LOGIN', 'users', 1, '2026-06-04 09:53:01', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(880, 1, 'LOGOUT', 'users', 1, '2026-06-04 09:53:48', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(881, 2, 'LOGIN', 'users', 2, '2026-06-04 09:53:52', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(882, 2, 'LOGOUT', 'users', 2, '2026-06-04 09:54:54', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(883, 1, 'LOGIN', 'users', 1, '2026-06-04 09:55:03', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(884, 1, 'LOGOUT', 'users', 1, '2026-06-04 10:25:18', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(885, 2, 'LOGIN', 'users', 2, '2026-06-04 10:25:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(886, 2, 'LOGOUT', 'users', 2, '2026-06-04 10:28:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(887, 1, 'LOGIN', 'users', 1, '2026-06-04 10:28:32', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(888, 1, 'SELECT', 'mensajeria', 30, '2026-06-04 10:35:07', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(889, 1, 'UPDATE', 'mensajeria', 30, '2026-06-04 10:35:07', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(890, 1, 'LOGOUT', 'users', 1, '2026-06-04 10:36:33', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(891, 2, 'LOGIN', 'users', 2, '2026-06-04 10:36:35', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(892, 2, 'LOGOUT', 'users', 2, '2026-06-04 10:39:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(893, 1, 'LOGIN', 'users', 1, '2026-06-04 10:39:32', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(894, 1, 'LOGOUT', 'users', 1, '2026-06-04 10:40:10', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(895, 2, 'LOGIN', 'users', 2, '2026-06-04 10:40:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(896, 2, 'LOGOUT', 'users', 2, '2026-06-04 10:40:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(897, 1, 'LOGIN', 'users', 1, '2026-06-04 10:40:54', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(898, 1, 'SELECT', 'mensajeria', 31, '2026-06-04 10:40:57', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(899, 1, 'UPDATE', 'mensajeria', 31, '2026-06-04 10:40:57', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(900, 1, 'LOGOUT', 'users', 1, '2026-06-04 10:44:56', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(901, 2, 'LOGIN', 'users', 2, '2026-06-04 10:45:01', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(902, 1, 'LOGIN', 'users', 1, '2026-06-04 11:06:05', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(903, 1, 'SELECT', 'mensajeria', 32, '2026-06-04 11:06:08', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(904, 1, 'UPDATE', 'mensajeria', 32, '2026-06-04 11:06:09', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(905, 1, 'SELECT', 'mensajeria', 32, '2026-06-04 11:06:21', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(906, 1, 'LOGOUT', 'users', 1, '2026-06-04 11:17:01', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(907, 1, 'LOGIN', 'users', 1, '2026-06-04 11:17:09', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(908, 1, 'LOGOUT', 'users', 1, '2026-06-04 11:17:32', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(909, 2, 'LOGIN', 'users', 2, '2026-06-04 11:17:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(910, 2, 'LOGOUT', 'users', 2, '2026-06-04 11:22:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(911, 1, 'LOGIN', 'users', 1, '2026-06-04 11:22:22', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(912, 1, 'SELECT', 'mensajeria', 33, '2026-06-04 11:22:39', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(913, 1, 'UPDATE', 'mensajeria', 33, '2026-06-04 11:22:39', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(914, 1, 'LOGOUT', 'users', 1, '2026-06-04 11:26:45', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(915, 2, 'LOGIN', 'users', 2, '2026-06-04 11:26:51', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(916, 2, 'LOGOUT', 'users', 2, '2026-06-04 11:39:50', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(917, 1, 'LOGIN', 'users', 1, '2026-06-04 11:39:57', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(918, 1, 'LOGOUT', 'users', 1, '2026-06-04 11:40:21', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(919, 2, 'LOGIN', 'users', 2, '2026-06-04 11:40:34', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(920, 2, 'LOGOUT', 'users', 2, '2026-06-04 11:45:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(921, 1, 'LOGIN', 'users', 1, '2026-06-04 11:46:04', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(922, 2, 'LOGIN', 'users', 2, '2026-06-05 10:02:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(923, 2, 'LOGOUT', 'users', 2, '2026-06-05 10:04:23', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(924, 1, 'LOGIN', 'users', 1, '2026-06-05 10:04:32', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(925, 1, 'LOGOUT', 'users', 1, '2026-06-05 10:06:04', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(926, 2, 'LOGIN', 'users', 2, '2026-06-05 10:06:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso');
INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `fecha_hora`, `valores_antiguos`, `valores_nuevos`, `ip_origen`, `user_agent`, `modulo_sistema`, `descripcion`) VALUES
(927, 2, 'LOGOUT', 'users', 2, '2026-06-05 10:06:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(928, 1, 'LOGIN', 'users', 1, '2026-06-05 10:06:46', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(929, 1, 'SELECT', 'mensajeria', 34, '2026-06-05 10:06:52', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(930, 1, 'UPDATE', 'mensajeria', 34, '2026-06-05 10:06:53', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(931, 1, 'LOGOUT', 'users', 1, '2026-06-05 10:07:12', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(932, 1, 'LOGIN', 'users', 1, '2026-06-05 10:07:20', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(933, 1, 'LOGOUT', 'users', 1, '2026-06-05 10:16:42', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(934, 2, 'LOGIN', 'users', 2, '2026-06-05 10:16:48', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(935, 2, 'LOGOUT', 'users', 2, '2026-06-05 10:17:40', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(936, 1, 'LOGIN', 'users', 1, '2026-06-05 10:17:50', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(937, 1, 'SELECT', 'mensajeria', 35, '2026-06-05 10:17:53', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(938, 1, 'UPDATE', 'mensajeria', 35, '2026-06-05 10:17:53', '{\"leido\":\"0\"}', '{\"leido\":\"1\",\"usuario_id\":\"1\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Mensaje marcado como leído'),
(939, 1, 'SELECT', 'mensajeria', 35, '2026-06-05 10:18:07', NULL, '{\"usuario_id\":\"1\",\"tipo_mensaje\":\"recibidos\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Mensajería', 'Consulta de mensaje específico'),
(940, 1, 'LOGOUT', 'users', 1, '2026-06-05 10:22:22', NULL, '{\"username\":\"jesuministrosymas\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(941, 2, 'LOGIN', 'users', 2, '2026-06-05 10:22:25', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(942, 2, 'CONSULTA', 'users', 2633, '2026-06-05 11:06:27', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(943, 2, 'LOGOUT', 'users', 2, '2026-06-05 11:06:33', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(944, 2633, 'LOGIN', 'users', 2633, '2026-06-05 11:06:37', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(945, 2633, 'LOGOUT', 'users', 2633, '2026-06-05 11:07:22', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(946, 2, 'LOGIN', 'users', 2, '2026-06-05 11:07:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(947, 2, 'INSERT', 'periodos_academicos', 6, '2026-06-05 11:09:23', NULL, '{\"nombre_periodo\":\"2026-2\",\"fecha_inicio\":\"2026-06-05\",\"fecha_fin\":\"2026-09-01\",\"activo\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Periodos Académicos', 'Nuevo período académico creado'),
(948, 2, 'CONSULTA', 'users', 2633, '2026-06-05 11:42:37', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(949, 2, 'LOGIN', 'users', 2, '2026-06-05 11:43:36', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(950, 2, 'CONSULTA', 'users', 2633, '2026-06-05 11:43:49', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(951, 2, 'LOGOUT', 'users', 2, '2026-06-05 12:37:46', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(952, 2, 'LOGIN', 'users', 2, '2026-06-05 12:38:39', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(953, 2, 'LOGOUT', 'users', 2, '2026-06-05 12:39:14', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(954, 2, 'LOGIN', 'users', 2, '2026-06-08 11:52:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(955, 2, 'LOGOUT', 'users', 2, '2026-06-08 12:13:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(956, 2, 'LOGIN', 'users', 2, '2026-06-10 09:08:09', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(957, 2, 'LOGIN', 'users', 2, '2026-06-11 09:24:08', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(958, 2, 'LOGIN', 'users', 2, '2026-06-12 09:30:28', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(959, 2633, 'LOGIN', 'users', 2633, '2026-06-12 10:04:48', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(960, 2633, 'LOGOUT', 'users', 2633, '2026-06-12 10:06:24', NULL, '{\"username\":\"V33058485\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(961, 2, 'LOGIN', 'users', 2, '2026-06-12 10:06:46', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(962, 2, 'LOGOUT', 'users', 2, '2026-06-12 11:27:32', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(963, 4, 'LOGIN', 'users', 4, '2026-06-12 14:39:41', NULL, '{\"username\":\"hectorlamaquina14@gmail.com\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(964, 4, 'LOGIN', 'users', 4, '2026-06-12 14:41:03', NULL, '{\"username\":\"hectorlamaquina14@gmail.com\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(965, 4, 'LOGOUT', 'users', 4, '2026-06-12 14:41:09', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(966, 2, 'LOGIN', 'users', 2, '2026-06-15 08:56:11', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(967, 2, 'LOGOUT', 'users', 2, '2026-06-15 08:57:06', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(968, 2, 'LOGIN', 'users', 2, '2026-06-15 11:23:03', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(969, 2, 'LOGOUT', 'users', 2, '2026-06-15 11:50:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(970, 2, 'LOGIN', 'users', 2, '2026-06-15 12:13:05', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(971, 2, 'LOGIN', 'users', 2, '2026-06-15 12:18:48', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(972, 2, 'LOGIN', 'users', 2, '2026-06-15 12:18:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(973, 2, 'LOGIN', 'users', 2, '2026-06-15 12:30:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(974, 2, 'LOGIN', 'users', 2, '2026-06-15 12:37:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(975, 2, 'LOGIN', 'users', 2, '2026-06-15 12:38:59', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(976, 2, 'LOGIN', 'users', 2, '2026-06-15 12:40:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(977, 2, 'LOGIN', 'users', 2, '2026-06-15 12:51:44', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(978, 2, 'LOGOUT', 'users', 2, '2026-06-15 12:51:49', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(979, 2, 'LOGIN', 'users', 2, '2026-06-15 13:21:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(980, 2, 'LOGIN', 'users', 2, '2026-06-15 13:21:45', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(981, 2, 'LOGIN', 'users', 2, '2026-06-16 12:57:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(982, 2, 'LOGOUT', 'users', 2, '2026-06-16 13:14:38', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(983, 2, 'LOGIN', 'users', 2, '2026-06-16 13:14:41', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(984, 2, 'UPDATE', 'users', 4, '2026-06-16 13:16:09', '{\"carrera_di\":2,\"carrera_nombre\":\"PNF EN TURISMO\",\"carrera_codigo\":\"13569\",\"estado_anterior\":\"Director asignado\"}', '{\"carrera_di\":null,\"usuario_nombre\":\"hector\",\"usuario_username\":\"hero\",\"estado_nuevo\":\"Sin carrera asignada\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Directores de Carrera', 'Eliminación de asignación de director de carrera'),
(985, 2, 'UPDATE', 'users', 4, '2026-06-16 13:16:19', '{\"carrera_di\":null,\"estado_anterior\":\"Sin carrera asignada\"}', '{\"carrera_di\":5,\"carrera_nombre\":\"PNF EN DISTRIBUCION Y LOGISTICA\",\"carrera_codigo\":\"14231\",\"usuario_nombre\":\"hector\",\"usuario_username\":\"hero\",\"estado_nuevo\":\"Director asignado\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Directores de Carrera', 'Asignación de director de carrera'),
(986, 2, 'LOGOUT', 'users', 2, '2026-06-16 13:16:26', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(987, 2, 'LOGIN', 'users', 2, '2026-06-16 13:16:51', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(988, 2, 'LOGOUT', 'users', 2, '2026-06-16 13:18:19', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(989, 4, 'LOGIN', 'users', 4, '2026-06-16 13:18:29', NULL, '{\"username\":\"hero\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(990, 2, 'LOGIN', 'users', 2, '2026-06-16 13:22:59', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(991, 2636, 'LOGIN', 'users', 2636, '2026-06-16 13:45:16', NULL, '{\"username\":\"V30692053\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(992, 2, 'LOGIN', 'users', 2, '2026-06-18 09:59:27', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(993, 2, 'CONSULTA', 'users', 2634, '2026-06-18 10:00:28', NULL, '{\"cedula_buscada\":\"V30692052\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2634,\"nombre_estudiante\":\"Falso Hector\",\"cedula\":\"V30692052\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(994, 2, 'CONSULTA', 'users', 2633, '2026-06-18 10:02:16', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(995, 2, 'CONSULTA', 'users', 2633, '2026-06-18 10:04:09', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(996, 2, 'CONSULTA', 'users', 2633, '2026-06-18 10:06:10', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(997, 2, 'LOGIN', 'users', 2, '2026-07-13 09:02:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(998, 2, 'CONSULTA', 'users', 2630, '2026-07-13 09:09:25', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(999, 2, 'CONSULTA', 'users', 2630, '2026-07-13 09:12:24', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(1000, 2, 'LOGOUT', 'users', 2, '2026-07-13 09:22:59', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(1001, 2, 'LOGIN', 'users', 2, '2026-07-13 09:23:18', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1002, 2, 'CONSULTA', 'users', 2630, '2026-07-13 09:23:39', NULL, '{\"cedula_buscada\":\"E14725836\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2630,\"nombre_estudiante\":\"prueba de planilla\",\"cedula\":\"E14725836\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(1003, 2, 'LOGIN', 'users', 2, '2026-07-13 09:24:57', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1004, 2, 'INSERT', 'docente_seccion', 43, '2026-07-13 11:57:11', NULL, '{\"id_usuario\":\"2588\",\"docente_nombre\":\"Sarsamora Vegano\",\"docente_cedula\":\"V-24765890\",\"id_seccion\":\"18\",\"seccion_codigo\":\"1-71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"13\",\"materia_nombre\":\"Proyecto Socio tecnol\\u00f3gico I\",\"materia_codigo\":\"PTP139\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(1005, 2, 'LOGOUT', 'users', 2, '2026-07-13 12:14:55', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(1006, 2630, 'LOGIN', 'users', 2630, '2026-07-13 12:15:02', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1007, 2630, 'CONSULTA', 'users', 2633, '2026-07-13 12:15:35', NULL, '{\"cedula_buscada\":\"V33058485\",\"resultado_busqueda\":\"ENCONTRADO\",\"tipo_consulta\":\"busqueda_estudiante\",\"id_estudiante\":2633,\"nombre_estudiante\":\"Gim\\u00e9nez Tovar Jos\\u00e9 David \",\"cedula\":\"V33058485\",\"id_carrera\":1}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Consulta de Estudiantes', 'Búsqueda de estudiante por cédula - ENCONTRADO'),
(1008, 2630, 'LOGOUT', 'users', 2630, '2026-07-13 12:15:59', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(1009, 2, 'LOGIN', 'users', 2, '2026-07-13 12:16:01', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1010, 2630, 'LOGIN', 'users', 2630, '2026-07-13 13:06:12', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1011, 2630, 'LOGOUT', 'users', 2630, '2026-07-13 13:07:12', NULL, '{\"username\":\"E14725836\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Cierre de sesión del sistema'),
(1012, 2, 'LOGIN', 'users', 2, '2026-07-13 13:07:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1013, 2, 'ERROR', 'users', 2, '2026-07-13 13:08:57', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1014, 2, 'ERROR', 'users', 2, '2026-07-13 13:11:00', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1015, 2, 'LOGIN', 'users', 2, '2026-07-13 13:11:17', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1016, 2, 'ERROR', 'users', 2, '2026-07-13 13:11:23', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1017, 2, 'LOGIN', 'users', 2, '2026-07-15 10:06:56', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1018, 2, 'ERROR', 'users', 2, '2026-07-15 10:07:00', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1019, 2, 'LOGIN', 'users', 2, '2026-07-15 10:07:03', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1020, 2, 'ERROR', 'users', 2, '2026-07-15 10:21:10', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1021, 2, 'LOGIN', 'users', 2, '2026-07-15 10:21:16', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1022, 2, 'ERROR', 'users', 2, '2026-07-15 10:22:11', NULL, '{\"permiso_solicitado\":\"director\",\"usuario\":\"V-12345678\",\"error\":\"Permiso no v\\u00e1lido\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Control de Acceso', 'Intento de acceso con permiso no válido'),
(1023, 2, 'LOGIN', 'users', 2, '2026-07-15 10:22:15', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1024, 2, 'LOGIN', 'users', 2, '2026-07-15 10:46:31', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso'),
(1025, 2, 'INSERT', 'docente_seccion', 44, '2026-07-15 10:53:28', NULL, '{\"id_usuario\":\"2609\",\"docente_nombre\":\"Perdomo Alba\\u00f1il\",\"docente_cedula\":\"V-12345555\",\"id_seccion\":\"18\",\"seccion_codigo\":\"1-71\",\"carrera_seccion\":\"PNF EN INFORMATICA\",\"id_materia\":\"11\",\"materia_nombre\":\"Algor\\u00edtmica y Programaci\\u00f3n\",\"materia_codigo\":\"APT1312\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'Asignaciones Docentes', 'Asignación de sección a docente'),
(1026, 2, 'LOGIN', 'users', 2, '2026-07-16 09:39:47', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int NOT NULL,
  `nave` varchar(1) COLLATE utf8mb4_spanish_ci NOT NULL,
  `aula` varchar(5) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id`, `nave`, `aula`) VALUES
(1, 'A', '1'),
(2, 'A', '2'),
(3, 'A', '3'),
(4, 'A', '4'),
(5, 'B', '1'),
(6, 'B', '2'),
(7, 'B', '3'),
(8, 'B', '4'),
(9, 'C', '1'),
(10, 'C', '2'),
(11, 'C', '3'),
(12, 'C', '4'),
(13, 'D', '1'),
(14, 'D', '2'),
(15, 'D', '3'),
(16, 'D', '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bancos`
--

CREATE TABLE `bancos` (
  `id` int NOT NULL,
  `nombre_banco` varchar(100) COLLATE utf8mb3_spanish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `bancos`
--

INSERT INTO `bancos` (`id`, `nombre_banco`, `created_at`) VALUES
(1, 'Banco de Venezuela', '2025-09-17 16:22:16'),
(2, 'Banesco', '2025-09-17 16:22:16'),
(3, 'Banco Mercantil', '2025-09-17 16:22:16'),
(4, 'Banco Provincial', '2025-09-17 16:22:16'),
(5, 'Banco Bicentenario', '2025-09-17 16:22:16'),
(6, 'Banco del Tesoro', '2025-09-17 16:22:16'),
(7, 'Banco Nacional de Crédito', '2025-09-17 16:22:16'),
(8, 'Banco Occidental de Descuento', '2025-09-17 16:22:16'),
(9, 'Banco Caroní', '2025-09-17 16:22:16'),
(10, 'Banco Plaza', '2025-09-17 16:22:16'),
(11, 'Banco Exterior', '2025-09-17 16:22:16'),
(12, 'Banco Sofitasa', '2025-09-17 16:22:16'),
(13, 'Banco Fondo Común', '2025-09-17 16:22:16'),
(14, 'Banco Activo', '2025-09-17 16:22:16'),
(15, 'Banco Venezolano de Crédito', '2025-09-17 16:22:16'),
(16, '100% Banco', '2025-09-17 16:22:16'),
(17, 'Banco del Sur', '2025-09-17 16:22:16'),
(18, 'Bancrecer', '2025-09-17 16:22:16'),
(19, 'Mi Banco', '2025-09-17 16:22:16'),
(20, 'Banco Agrícola de Venezuela', '2025-09-17 16:22:16'),
(21, 'Banco de la Fuerza Armada Nacional Bolivariana', '2025-09-17 16:22:16'),
(22, 'Banco de Desarrollo del Microempresario', '2025-09-17 16:22:16'),
(23, 'Banco Internacional de Desarrollo', '2025-09-17 16:22:16'),
(24, 'Banplus', '2025-09-17 16:22:16'),
(25, 'Bancaribe', '2025-09-17 16:22:16'),
(26, 'Banfanb', '2025-09-17 16:22:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int NOT NULL,
  `id_pedido` int NOT NULL,
  `status` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `admin` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `concepto` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int NOT NULL,
  `nombre_carrera` varchar(100) COLLATE utf32_spanish2_ci NOT NULL,
  `cod_carrera` varchar(100) COLLATE utf32_spanish2_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `duracion_semestres` int DEFAULT NULL,
  `titulo_otorga` varchar(80) COLLATE utf32_spanish2_ci DEFAULT NULL,
  `otro_titulo` varchar(20) COLLATE utf32_spanish2_ci DEFAULT NULL,
  `descripcion` text COLLATE utf32_spanish2_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_formacion` enum('PNF','PTF') COLLATE utf32_spanish2_ci NOT NULL DEFAULT 'PNF'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`, `cod_carrera`, `activa`, `duracion_semestres`, `titulo_otorga`, `otro_titulo`, `descripcion`, `created_at`, `tipo_formacion`) VALUES
(0, 'No Especificado', 'NES', 1, 0, 'Ninguno', NULL, 'Carrera genérica para docentes sin asignación específica', '2025-08-01 22:39:06', ''),
(1, 'PNF EN INFORMATICA', '14232', 1, 8, 'TSU Informatica', 'Ing. Informatica', '0', '2025-06-02 14:08:44', 'PNF'),
(2, 'PNF EN TURISMO', '13569', 1, 8, 'TSU turismo', '', '0', '2025-06-16 18:07:13', 'PNF'),
(5, 'PNF EN DISTRIBUCION Y LOGISTICA', '14231', 1, 4, 'Licenciado en Distribucion y Logistica', 'oooo', '0', '2025-08-10 22:26:32', 'PNF'),
(14, 'PTF EN MECANICA', '13351', 1, 8, 'TSU Mecanica', 'Ing. Mecanica', '0', '2005-01-13 04:00:00', 'PTF'),
(15, 'PTF EN MECANICA AUTOMOTRIZ', '12932', 1, 6, 'TSU Mecanica Automotriz', '', '0', '2026-01-28 04:00:00', 'PTF');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera_materia`
--

CREATE TABLE `carrera_materia` (
  `id_relacion` int NOT NULL,
  `id_carrera` int NOT NULL,
  `id_materia` int NOT NULL,
  `semestre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `carrera_materia`
--

INSERT INTO `carrera_materia` (`id_relacion`, `id_carrera`, `id_materia`, `semestre`) VALUES
(3, 1, 5, 1),
(5, 1, 7, 3),
(6, 1, 6, 1),
(9, 1, 9, 1),
(10, 1, 10, 3),
(11, 1, 11, 3),
(12, 1, 12, 3),
(13, 1, 13, 3),
(14, 1, 14, 3),
(16, 1, 16, 2),
(17, 1, 17, 2),
(18, 1, 18, 3),
(19, 1, 19, 1),
(22, 1, 21, 3),
(23, 1, 22, 3),
(25, 1, 20, 1),
(26, 1, 23, 2),
(27, 1, 24, 1),
(28, 1, 25, 1),
(29, 2, 15, 1),
(30, 1, 26, 1),
(31, 1, 27, 3),
(32, 1, 28, 3),
(35, 14, 29, 1),
(36, 14, 32, 1),
(37, 15, 29, 1),
(38, 15, 34, 1),
(39, 5, 35, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera_versiones`
--

CREATE TABLE `carrera_versiones` (
  `id_version` int NOT NULL,
  `id_carrera` int NOT NULL,
  `fecha_vigencia` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `carrera_versiones`
--

INSERT INTO `carrera_versiones` (`id_version`, `id_carrera`, `fecha_vigencia`, `created_at`) VALUES
(2, 14, '2000-01-13 00:00:00', '2026-01-13 16:48:01'),
(3, 14, '1977-01-01 00:00:00', '2026-01-13 17:36:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudades`
--

CREATE TABLE `ciudades` (
  `id_ciudad` int NOT NULL,
  `id_estado` int NOT NULL,
  `ciudad` varchar(200) NOT NULL,
  `capital` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `ciudades`
--

INSERT INTO `ciudades` (`id_ciudad`, `id_estado`, `ciudad`, `capital`) VALUES
(1, 1, 'Maroa', 0),
(2, 1, 'Puerto Ayacucho', 1),
(3, 1, 'San Fernando de Atabapo', 0),
(4, 2, 'Anaco', 0),
(5, 2, 'Aragua de Barcelona', 0),
(6, 2, 'Barcelona', 1),
(7, 2, 'Boca de Uchire', 0),
(8, 2, 'Cantaura', 0),
(9, 2, 'Clarines', 0),
(10, 2, 'El Chaparro', 0),
(11, 2, 'El Pao Anzoátegui', 0),
(12, 2, 'El Tigre', 0),
(13, 2, 'El Tigrito', 0),
(14, 2, 'Guanape', 0),
(15, 2, 'Guanta', 0),
(16, 2, 'Lechería', 0),
(17, 2, 'Onoto', 0),
(18, 2, 'Pariaguán', 0),
(19, 2, 'Píritu', 0),
(20, 2, 'Puerto La Cruz', 0),
(21, 2, 'Puerto Píritu', 0),
(22, 2, 'Sabana de Uchire', 0),
(23, 2, 'San Mateo Anzoátegui', 0),
(24, 2, 'San Pablo Anzoátegui', 0),
(25, 2, 'San Tomé', 0),
(26, 2, 'Santa Ana de Anzoátegui', 0),
(27, 2, 'Santa Fe Anzoátegui', 0),
(28, 2, 'Santa Rosa', 0),
(29, 2, 'Soledad', 0),
(30, 2, 'Urica', 0),
(31, 2, 'Valle de Guanape', 0),
(43, 3, 'Achaguas', 0),
(44, 3, 'Biruaca', 0),
(45, 3, 'Bruzual', 0),
(46, 3, 'El Amparo', 0),
(47, 3, 'El Nula', 0),
(48, 3, 'Elorza', 0),
(49, 3, 'Guasdualito', 0),
(50, 3, 'Mantecal', 0),
(51, 3, 'Puerto Páez', 0),
(52, 3, 'San Fernando de Apure', 1),
(53, 3, 'San Juan de Payara', 0),
(54, 4, 'Barbacoas', 0),
(55, 4, 'Cagua', 0),
(56, 4, 'Camatagua', 0),
(58, 4, 'Choroní', 0),
(59, 4, 'Colonia Tovar', 0),
(60, 4, 'El Consejo', 0),
(61, 4, 'La Victoria', 0),
(62, 4, 'Las Tejerías', 0),
(63, 4, 'Magdaleno', 0),
(64, 4, 'Maracay', 1),
(65, 4, 'Ocumare de La Costa', 0),
(66, 4, 'Palo Negro', 0),
(67, 4, 'San Casimiro', 0),
(68, 4, 'San Mateo', 0),
(69, 4, 'San Sebastián', 0),
(70, 4, 'Santa Cruz de Aragua', 0),
(71, 4, 'Tocorón', 0),
(72, 4, 'Turmero', 0),
(73, 4, 'Villa de Cura', 0),
(74, 4, 'Zuata', 0),
(75, 5, 'Barinas', 1),
(76, 5, 'Barinitas', 0),
(77, 5, 'Barrancas', 0),
(78, 5, 'Calderas', 0),
(79, 5, 'Capitanejo', 0),
(80, 5, 'Ciudad Bolivia', 0),
(81, 5, 'El Cantón', 0),
(82, 5, 'Las Veguitas', 0),
(83, 5, 'Libertad de Barinas', 0),
(84, 5, 'Sabaneta', 0),
(85, 5, 'Santa Bárbara de Barinas', 0),
(86, 5, 'Socopó', 0),
(87, 6, 'Caicara del Orinoco', 0),
(88, 6, 'Canaima', 0),
(89, 6, 'Ciudad Bolívar', 1),
(90, 6, 'Ciudad Piar', 0),
(91, 6, 'El Callao', 0),
(92, 6, 'El Dorado', 0),
(93, 6, 'El Manteco', 0),
(94, 6, 'El Palmar', 0),
(95, 6, 'El Pao', 0),
(96, 6, 'Guasipati', 0),
(97, 6, 'Guri', 0),
(98, 6, 'La Paragua', 0),
(99, 6, 'Matanzas', 0),
(100, 6, 'Puerto Ordaz', 0),
(101, 6, 'San Félix', 0),
(102, 6, 'Santa Elena de Uairén', 0),
(103, 6, 'Tumeremo', 0),
(104, 6, 'Unare', 0),
(105, 6, 'Upata', 0),
(106, 7, 'Bejuma', 0),
(107, 7, 'Belén', 0),
(108, 7, 'Campo de Carabobo', 0),
(109, 7, 'Canoabo', 0),
(110, 7, 'Central Tacarigua', 0),
(111, 7, 'Chirgua', 0),
(112, 7, 'Ciudad Alianza', 0),
(113, 7, 'El Palito', 0),
(114, 7, 'Guacara', 0),
(115, 7, 'Guigue', 0),
(116, 7, 'Las Trincheras', 0),
(117, 7, 'Los Guayos', 0),
(118, 7, 'Mariara', 0),
(119, 7, 'Miranda', 0),
(120, 7, 'Montalbán', 0),
(121, 7, 'Morón', 0),
(122, 7, 'Naguanagua', 0),
(123, 7, 'Puerto Cabello', 0),
(124, 7, 'San Joaquín', 0),
(125, 7, 'Tocuyito', 0),
(126, 7, 'Urama', 0),
(127, 7, 'Valencia', 1),
(128, 7, 'Vigirimita', 0),
(129, 8, 'Aguirre', 0),
(130, 8, 'Apartaderos Cojedes', 0),
(131, 8, 'Arismendi', 0),
(132, 8, 'Camuriquito', 0),
(133, 8, 'El Baúl', 0),
(134, 8, 'El Limón', 0),
(135, 8, 'El Pao Cojedes', 0),
(136, 8, 'El Socorro', 0),
(137, 8, 'La Aguadita', 0),
(138, 8, 'Las Vegas', 0),
(139, 8, 'Libertad de Cojedes', 0),
(140, 8, 'Mapuey', 0),
(141, 8, 'Piñedo', 0),
(142, 8, 'Samancito', 0),
(143, 8, 'San Carlos', 1),
(144, 8, 'Sucre', 0),
(145, 8, 'Tinaco', 0),
(146, 8, 'Tinaquillo', 0),
(147, 8, 'Vallecito', 0),
(148, 9, 'Tucupita', 1),
(149, 24, 'Caracas', 1),
(150, 24, 'El Junquito', 0),
(151, 10, 'Adícora', 0),
(152, 10, 'Boca de Aroa', 0),
(153, 10, 'Cabure', 0),
(154, 10, 'Capadare', 0),
(155, 10, 'Capatárida', 0),
(156, 10, 'Chichiriviche', 0),
(157, 10, 'Churuguara', 0),
(158, 10, 'Coro', 1),
(159, 10, 'Cumarebo', 0),
(160, 10, 'Dabajuro', 0),
(161, 10, 'Judibana', 0),
(162, 10, 'La Cruz de Taratara', 0),
(163, 10, 'La Vela de Coro', 0),
(164, 10, 'Los Taques', 0),
(165, 10, 'Maparari', 0),
(166, 10, 'Mene de Mauroa', 0),
(167, 10, 'Mirimire', 0),
(168, 10, 'Pedregal', 0),
(169, 10, 'Píritu Falcón', 0),
(170, 10, 'Pueblo Nuevo Falcón', 0),
(171, 10, 'Puerto Cumarebo', 0),
(172, 10, 'Punta Cardón', 0),
(173, 10, 'Punto Fijo', 0),
(174, 10, 'San Juan de Los Cayos', 0),
(175, 10, 'San Luis', 0),
(176, 10, 'Santa Ana Falcón', 0),
(177, 10, 'Santa Cruz De Bucaral', 0),
(178, 10, 'Tocopero', 0),
(179, 10, 'Tocuyo de La Costa', 0),
(180, 10, 'Tucacas', 0),
(181, 10, 'Yaracal', 0),
(182, 11, 'Altagracia de Orituco', 0),
(183, 11, 'Cabruta', 0),
(184, 11, 'Calabozo', 0),
(185, 11, 'Camaguán', 0),
(196, 11, 'Chaguaramas Guárico', 0),
(197, 11, 'El Socorro', 0),
(198, 11, 'El Sombrero', 0),
(199, 11, 'Las Mercedes de Los Llanos', 0),
(200, 11, 'Lezama', 0),
(201, 11, 'Onoto', 0),
(202, 11, 'Ortíz', 0),
(203, 11, 'San José de Guaribe', 0),
(204, 11, 'San Juan de Los Morros', 1),
(205, 11, 'San Rafael de Laya', 0),
(206, 11, 'Santa María de Ipire', 0),
(207, 11, 'Tucupido', 0),
(208, 11, 'Valle de La Pascua', 0),
(209, 11, 'Zaraza', 0),
(210, 12, 'Aguada Grande', 0),
(211, 12, 'Atarigua', 0),
(212, 12, 'Barquisimeto', 1),
(213, 12, 'Bobare', 0),
(214, 12, 'Cabudare', 0),
(215, 12, 'Carora', 0),
(216, 12, 'Cubiro', 0),
(217, 12, 'Cují', 0),
(218, 12, 'Duaca', 0),
(219, 12, 'El Manzano', 0),
(220, 12, 'El Tocuyo', 0),
(221, 12, 'Guaríco', 0),
(222, 12, 'Humocaro Alto', 0),
(223, 12, 'Humocaro Bajo', 0),
(224, 12, 'La Miel', 0),
(225, 12, 'Moroturo', 0),
(226, 12, 'Quíbor', 0),
(227, 12, 'Río Claro', 0),
(228, 12, 'Sanare', 0),
(229, 12, 'Santa Inés', 0),
(230, 12, 'Sarare', 0),
(231, 12, 'Siquisique', 0),
(232, 12, 'Tintorero', 0),
(233, 13, 'Apartaderos Mérida', 0),
(234, 13, 'Arapuey', 0),
(235, 13, 'Bailadores', 0),
(236, 13, 'Caja Seca', 0),
(237, 13, 'Canaguá', 0),
(238, 13, 'Chachopo', 0),
(239, 13, 'Chiguara', 0),
(240, 13, 'Ejido', 0),
(241, 13, 'El Vigía', 0),
(242, 13, 'La Azulita', 0),
(243, 13, 'La Playa', 0),
(244, 13, 'Lagunillas Mérida', 0),
(245, 13, 'Mérida', 1),
(246, 13, 'Mesa de Bolívar', 0),
(247, 13, 'Mucuchíes', 0),
(248, 13, 'Mucujepe', 0),
(249, 13, 'Mucuruba', 0),
(250, 13, 'Nueva Bolivia', 0),
(251, 13, 'Palmarito', 0),
(252, 13, 'Pueblo Llano', 0),
(253, 13, 'Santa Cruz de Mora', 0),
(254, 13, 'Santa Elena de Arenales', 0),
(255, 13, 'Santo Domingo', 0),
(256, 13, 'Tabáy', 0),
(257, 13, 'Timotes', 0),
(258, 13, 'Torondoy', 0),
(259, 13, 'Tovar', 0),
(260, 13, 'Tucani', 0),
(261, 13, 'Zea', 0),
(262, 14, 'Araguita', 0),
(263, 14, 'Carrizal', 0),
(264, 14, 'Caucagua', 0),
(265, 14, 'Chaguaramas Miranda', 0),
(266, 14, 'Charallave', 0),
(267, 14, 'Chirimena', 0),
(268, 14, 'Chuspa', 0),
(269, 14, 'Cúa', 0),
(270, 14, 'Cupira', 0),
(271, 14, 'Curiepe', 0),
(272, 14, 'El Guapo', 0),
(273, 14, 'El Jarillo', 0),
(274, 14, 'Filas de Mariche', 0),
(275, 14, 'Guarenas', 0),
(276, 14, 'Guatire', 0),
(277, 14, 'Higuerote', 0),
(278, 14, 'Los Anaucos', 0),
(279, 14, 'Los Teques', 1),
(280, 14, 'Ocumare del Tuy', 0),
(281, 14, 'Panaquire', 0),
(282, 14, 'Paracotos', 0),
(283, 14, 'Río Chico', 0),
(284, 14, 'San Antonio de Los Altos', 0),
(285, 14, 'San Diego de Los Altos', 0),
(286, 14, 'San Fernando del Guapo', 0),
(287, 14, 'San Francisco de Yare', 0),
(288, 14, 'San José de Los Altos', 0),
(289, 14, 'San José de Río Chico', 0),
(290, 14, 'San Pedro de Los Altos', 0),
(291, 14, 'Santa Lucía', 0),
(292, 14, 'Santa Teresa', 0),
(293, 14, 'Tacarigua de La Laguna', 0),
(294, 14, 'Tacarigua de Mamporal', 0),
(295, 14, 'Tácata', 0),
(296, 14, 'Turumo', 0),
(297, 15, 'Aguasay', 0),
(298, 15, 'Aragua de Maturín', 0),
(299, 15, 'Barrancas del Orinoco', 0),
(300, 15, 'Caicara de Maturín', 0),
(301, 15, 'Caripe', 0),
(302, 15, 'Caripito', 0),
(303, 15, 'Chaguaramal', 0),
(305, 15, 'Chaguaramas Monagas', 0),
(307, 15, 'El Furrial', 0),
(308, 15, 'El Tejero', 0),
(309, 15, 'Jusepín', 0),
(310, 15, 'La Toscana', 0),
(311, 15, 'Maturín', 1),
(312, 15, 'Miraflores', 0),
(313, 15, 'Punta de Mata', 0),
(314, 15, 'Quiriquire', 0),
(315, 15, 'San Antonio de Maturín', 0),
(316, 15, 'San Vicente Monagas', 0),
(317, 15, 'Santa Bárbara', 0),
(318, 15, 'Temblador', 0),
(319, 15, 'Teresen', 0),
(320, 15, 'Uracoa', 0),
(321, 16, 'Altagracia', 0),
(322, 16, 'Boca de Pozo', 0),
(323, 16, 'Boca de Río', 0),
(324, 16, 'El Espinal', 0),
(325, 16, 'El Valle del Espíritu Santo', 0),
(326, 16, 'El Yaque', 0),
(327, 16, 'Juangriego', 0),
(328, 16, 'La Asunción', 1),
(329, 16, 'La Guardia', 0),
(330, 16, 'Pampatar', 0),
(331, 16, 'Porlamar', 0),
(332, 16, 'Puerto Fermín', 0),
(333, 16, 'Punta de Piedras', 0),
(334, 16, 'San Francisco de Macanao', 0),
(335, 16, 'San Juan Bautista', 0),
(336, 16, 'San Pedro de Coche', 0),
(337, 16, 'Santa Ana de Nueva Esparta', 0),
(338, 16, 'Villa Rosa', 0),
(339, 17, 'Acarigua', 0),
(340, 17, 'Agua Blanca', 0),
(341, 17, 'Araure', 0),
(342, 17, 'Biscucuy', 0),
(343, 17, 'Boconoito', 0),
(344, 17, 'Campo Elías', 0),
(345, 17, 'Chabasquén', 0),
(346, 17, 'Guanare', 1),
(347, 17, 'Guanarito', 0),
(348, 17, 'La Aparición', 0),
(349, 17, 'La Misión', 0),
(350, 17, 'Mesa de Cavacas', 0),
(351, 17, 'Ospino', 0),
(352, 17, 'Papelón', 0),
(353, 17, 'Payara', 0),
(354, 17, 'Pimpinela', 0),
(355, 17, 'Píritu de Portuguesa', 0),
(356, 17, 'San Rafael de Onoto', 0),
(357, 17, 'Santa Rosalía', 0),
(358, 17, 'Turén', 0),
(359, 18, 'Altos de Sucre', 0),
(360, 18, 'Araya', 0),
(361, 18, 'Cariaco', 0),
(362, 18, 'Carúpano', 0),
(363, 18, 'Casanay', 0),
(364, 18, 'Cumaná', 1),
(365, 18, 'Cumanacoa', 0),
(366, 18, 'El Morro Puerto Santo', 0),
(367, 18, 'El Pilar', 0),
(368, 18, 'El Poblado', 0),
(369, 18, 'Guaca', 0),
(370, 18, 'Guiria', 0),
(371, 18, 'Irapa', 0),
(372, 18, 'Manicuare', 0),
(373, 18, 'Mariguitar', 0),
(374, 18, 'Río Caribe', 0),
(375, 18, 'San Antonio del Golfo', 0),
(376, 18, 'San José de Aerocuar', 0),
(377, 18, 'San Vicente de Sucre', 0),
(378, 18, 'Santa Fe de Sucre', 0),
(379, 18, 'Tunapuy', 0),
(380, 18, 'Yaguaraparo', 0),
(381, 18, 'Yoco', 0),
(382, 19, 'Abejales', 0),
(383, 19, 'Borota', 0),
(384, 19, 'Bramon', 0),
(385, 19, 'Capacho', 0),
(386, 19, 'Colón', 0),
(387, 19, 'Coloncito', 0),
(388, 19, 'Cordero', 0),
(389, 19, 'El Cobre', 0),
(390, 19, 'El Pinal', 0),
(391, 19, 'Independencia', 0),
(392, 19, 'La Fría', 0),
(393, 19, 'La Grita', 0),
(394, 19, 'La Pedrera', 0),
(395, 19, 'La Tendida', 0),
(396, 19, 'Las Delicias', 0),
(397, 19, 'Las Hernández', 0),
(398, 19, 'Lobatera', 0),
(399, 19, 'Michelena', 0),
(400, 19, 'Palmira', 0),
(401, 19, 'Pregonero', 0),
(402, 19, 'Queniquea', 0),
(403, 19, 'Rubio', 0),
(404, 19, 'San Antonio del Tachira', 0),
(405, 19, 'San Cristobal', 1),
(406, 19, 'San José de Bolívar', 0),
(407, 19, 'San Josecito', 0),
(408, 19, 'San Pedro del Río', 0),
(409, 19, 'Santa Ana Táchira', 0),
(410, 19, 'Seboruco', 0),
(411, 19, 'Táriba', 0),
(412, 19, 'Umuquena', 0),
(413, 19, 'Ureña', 0),
(414, 20, 'Batatal', 0),
(415, 20, 'Betijoque', 0),
(416, 20, 'Boconó', 0),
(417, 20, 'Carache', 0),
(418, 20, 'Chejende', 0),
(419, 20, 'Cuicas', 0),
(420, 20, 'El Dividive', 0),
(421, 20, 'El Jaguito', 0),
(422, 20, 'Escuque', 0),
(423, 20, 'Isnotú', 0),
(424, 20, 'Jajó', 0),
(425, 20, 'La Ceiba', 0),
(426, 20, 'La Concepción de Trujllo', 0),
(427, 20, 'La Mesa de Esnujaque', 0),
(428, 20, 'La Puerta', 0),
(429, 20, 'La Quebrada', 0),
(430, 20, 'Mendoza Fría', 0),
(431, 20, 'Meseta de Chimpire', 0),
(432, 20, 'Monay', 0),
(433, 20, 'Motatán', 0),
(434, 20, 'Pampán', 0),
(435, 20, 'Pampanito', 0),
(436, 20, 'Sabana de Mendoza', 0),
(437, 20, 'San Lázaro', 0),
(438, 20, 'Santa Ana de Trujillo', 0),
(439, 20, 'Tostós', 0),
(440, 20, 'Trujillo', 1),
(441, 20, 'Valera', 0),
(442, 21, 'Carayaca', 0),
(443, 21, 'Litoral', 0),
(444, 25, 'Archipiélago Los Roques', 0),
(445, 22, 'Aroa', 0),
(446, 22, 'Boraure', 0),
(447, 22, 'Campo Elías de Yaracuy', 0),
(448, 22, 'Chivacoa', 0),
(449, 22, 'Cocorote', 0),
(450, 22, 'Farriar', 0),
(451, 22, 'Guama', 0),
(452, 22, 'Marín', 0),
(453, 22, 'Nirgua', 0),
(454, 22, 'Sabana de Parra', 0),
(455, 22, 'Salom', 0),
(456, 22, 'San Felipe', 1),
(457, 22, 'San Pablo de Yaracuy', 0),
(458, 22, 'Urachiche', 0),
(459, 22, 'Yaritagua', 0),
(460, 22, 'Yumare', 0),
(461, 23, 'Bachaquero', 0),
(462, 23, 'Bobures', 0),
(463, 23, 'Cabimas', 0),
(464, 23, 'Campo Concepción', 0),
(465, 23, 'Campo Mara', 0),
(466, 23, 'Campo Rojo', 0),
(467, 23, 'Carrasquero', 0),
(468, 23, 'Casigua', 0),
(469, 23, 'Chiquinquirá', 0),
(470, 23, 'Ciudad Ojeda', 0),
(471, 23, 'El Batey', 0),
(472, 23, 'El Carmelo', 0),
(473, 23, 'El Chivo', 0),
(474, 23, 'El Guayabo', 0),
(475, 23, 'El Mene', 0),
(476, 23, 'El Venado', 0),
(477, 23, 'Encontrados', 0),
(478, 23, 'Gibraltar', 0),
(479, 23, 'Isla de Toas', 0),
(480, 23, 'La Concepción del Zulia', 0),
(481, 23, 'La Paz', 0),
(482, 23, 'La Sierrita', 0),
(483, 23, 'Lagunillas del Zulia', 0),
(484, 23, 'Las Piedras de Perijá', 0),
(485, 23, 'Los Cortijos', 0),
(486, 23, 'Machiques', 0),
(487, 23, 'Maracaibo', 1),
(488, 23, 'Mene Grande', 0),
(489, 23, 'Palmarejo', 0),
(490, 23, 'Paraguaipoa', 0),
(491, 23, 'Potrerito', 0),
(492, 23, 'Pueblo Nuevo del Zulia', 0),
(493, 23, 'Puertos de Altagracia', 0),
(494, 23, 'Punta Gorda', 0),
(495, 23, 'Sabaneta de Palma', 0),
(496, 23, 'San Francisco', 0),
(497, 23, 'San José de Perijá', 0),
(498, 23, 'San Rafael del Moján', 0),
(499, 23, 'San Timoteo', 0),
(500, 23, 'Santa Bárbara Del Zulia', 0),
(501, 23, 'Santa Cruz de Mara', 0),
(502, 23, 'Santa Cruz del Zulia', 0),
(503, 23, 'Santa Rita', 0),
(504, 23, 'Sinamaica', 0),
(505, 23, 'Tamare', 0),
(506, 23, 'Tía Juana', 0),
(507, 23, 'Villa del Rosario', 0),
(508, 21, 'La Guaira', 1),
(509, 21, 'Catia La Mar', 0),
(510, 21, 'Macuto', 0),
(511, 21, 'Naiguatá', 0),
(512, 25, 'Archipiélago Los Monjes', 0),
(513, 25, 'Isla La Tortuga y Cayos adyacentes', 0),
(514, 25, 'Isla La Sola', 0),
(515, 25, 'Islas Los Testigos', 0),
(516, 25, 'Islas Los Frailes', 0),
(517, 25, 'Isla La Orchila', 0),
(518, 25, 'Archipiélago Las Aves', 0),
(519, 25, 'Isla de Aves', 0),
(520, 25, 'Isla La Blanquilla', 0),
(521, 25, 'Isla de Patos', 0),
(522, 25, 'Islas Los Hermanos', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `id` int NOT NULL,
  `seccion` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `contenido` longblob NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_avance_trayecto`
--

CREATE TABLE `control_avance_trayecto` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_carrera` int NOT NULL,
  `trayecto_actual` int NOT NULL,
  `puede_avanzar` tinyint(1) DEFAULT '0',
  `aprobado_por` int DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL,
  `motivo` text COLLATE utf8mb3_spanish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `control_avance_trayecto`
--

INSERT INTO `control_avance_trayecto` (`id`, `id_usuario`, `id_carrera`, `trayecto_actual`, `puede_avanzar`, `aprobado_por`, `fecha_aprobacion`, `motivo`, `created_at`, `updated_at`) VALUES
(2, 5, 1, 0, 1, 2, '2026-01-26 11:04:31', '', '2026-01-26 15:04:31', '2026-01-26 15:04:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_materia`
--

CREATE TABLE `docente_materia` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_materia` int NOT NULL,
  `fecha_asignacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `docente_materia`
--

INSERT INTO `docente_materia` (`id`, `id_usuario`, `id_materia`, `fecha_asignacion`) VALUES
(7, 2585, 10, '2025-08-03 19:17:37'),
(9, 2586, 15, '2025-08-07 18:36:30'),
(10, 2585, 15, '2025-08-08 15:41:27'),
(11, 4, 11, '2025-08-19 17:51:30'),
(12, 4, 9, '2025-08-22 16:26:30'),
(13, 2, 9, '2025-08-22 16:26:50'),
(14, 4, 5, '2025-08-22 16:43:47'),
(15, 2, 15, '2025-08-22 20:17:24'),
(17, 4, 15, '2025-10-02 12:45:47'),
(18, 2, 35, '2026-02-23 09:25:52'),
(19, 1, 5, '2026-05-15 10:39:20'),
(20, 1, 9, '2026-06-03 11:43:37'),
(21, 1, 6, '2026-06-04 09:38:20'),
(22, 2588, 13, '2026-07-13 11:24:00'),
(23, 2609, 11, '2026-07-15 10:47:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_seccion`
--

CREATE TABLE `docente_seccion` (
  `id_docente_seccion` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_seccion` int NOT NULL,
  `id_materia` int NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `docente_seccion`
--

INSERT INTO `docente_seccion` (`id_docente_seccion`, `id_usuario`, `id_seccion`, `id_materia`, `fecha_asignacion`, `estatus`) VALUES
(25, 2585, 13, 10, '2026-05-14 15:51:25', 1),
(27, 1, 14, 5, '2026-05-15 14:40:04', 1),
(37, 1, 14, 9, '2026-06-03 16:05:20', 1),
(40, 1, 14, 6, '2026-06-04 13:38:48', 1),
(43, 2588, 18, 13, '2026-07-13 15:57:11', 1),
(44, 2609, 18, 11, '2026-07-15 14:53:28', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int NOT NULL,
  `estado` varchar(250) NOT NULL,
  `iso_3166-2` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`, `iso_3166-2`) VALUES
(1, 'Amazonas', 'VE-X'),
(2, 'Anzoátegui', 'VE-B'),
(3, 'Apure', 'VE-C'),
(4, 'Aragua', 'VE-D'),
(5, 'Barinas', 'VE-E'),
(6, 'Bolívar', 'VE-F'),
(7, 'Carabobo', 'VE-G'),
(8, 'Cojedes', 'VE-H'),
(9, 'Delta Amacuro', 'VE-Y'),
(10, 'Falcón', 'VE-I'),
(11, 'Guárico', 'VE-J'),
(12, 'Lara', 'VE-K'),
(13, 'Mérida', 'VE-L'),
(14, 'Miranda', 'VE-M'),
(15, 'Monagas', 'VE-N'),
(16, 'Nueva Esparta', 'VE-O'),
(17, 'Portuguesa', 'VE-P'),
(18, 'Sucre', 'VE-R'),
(19, 'Táchira', 'VE-S'),
(20, 'Trujillo', 'VE-T'),
(21, 'La Guaira', 'VE-W'),
(22, 'Yaracuy', 'VE-U'),
(23, 'Zulia', 'VE-V'),
(24, 'Distrito Capital', 'VE-A'),
(25, 'Dependencias Federales', 'VE-Z');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_civil`
--

CREATE TABLE `estado_civil` (
  `id` int NOT NULL,
  `estado_civil` varchar(20) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `estado_civil`
--

INSERT INTO `estado_civil` (`id`, `estado_civil`) VALUES
(1, 'Soltero'),
(2, 'Casado'),
(3, 'Divorciado'),
(4, 'Viudo'),
(5, 'Union libre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_materias`
--

CREATE TABLE `estudiante_materias` (
  `id_inscripcion` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_materia` int NOT NULL,
  `id_seccion` int NOT NULL,
  `id_periodo` int NOT NULL,
  `fecha_inscripcion` datetime DEFAULT NULL,
  `estatus` varchar(20) COLLATE utf8mb3_spanish_ci DEFAULT 'activo',
  `nota_final` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `estudiante_materias`
--

INSERT INTO `estudiante_materias` (`id_inscripcion`, `id_usuario`, `id_materia`, `id_seccion`, `id_periodo`, `fecha_inscripcion`, `estatus`, `nota_final`) VALUES
(1, 2628, 5, 14, 5, '2026-05-11 14:40:53', 'activo', NULL),
(2, 2628, 6, 14, 5, '2026-05-11 14:40:53', 'activo', NULL),
(3, 2628, 9, 14, 5, '2026-05-11 14:40:53', 'activo', NULL),
(4, 2629, 5, 14, 5, '2026-05-14 11:20:46', 'activo', NULL),
(5, 2629, 6, 14, 5, '2026-05-14 11:20:46', 'activo', NULL),
(6, 2629, 9, 14, 5, '2026-05-14 11:20:46', 'activo', NULL),
(7, 2630, 5, 14, 5, '2026-05-20 09:51:12', 'activo', NULL),
(8, 2630, 6, 14, 5, '2026-05-20 09:51:12', 'activo', NULL),
(9, 2630, 9, 14, 5, '2026-05-20 09:51:12', 'activo', NULL),
(10, 2631, 5, 17, 5, '2026-05-20 11:53:06', 'activo', NULL),
(11, 2631, 6, 17, 5, '2026-05-20 11:53:06', 'activo', NULL),
(12, 2631, 9, 17, 5, '2026-05-20 11:53:06', 'activo', NULL),
(13, 2632, 5, 14, 5, '2026-05-20 12:14:05', 'activo', NULL),
(14, 2632, 6, 14, 5, '2026-05-20 12:14:05', 'activo', NULL),
(15, 2632, 9, 14, 5, '2026-05-20 12:14:05', 'activo', NULL),
(16, 2633, 5, 14, 5, '2026-05-27 11:48:24', 'activo', NULL),
(17, 2633, 6, 14, 5, '2026-05-27 11:48:24', 'activo', NULL),
(18, 2633, 9, 14, 5, '2026-05-27 11:48:24', 'activo', NULL),
(19, 2633, 11, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(20, 2633, 10, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(21, 2633, 12, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(22, 2633, 14, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(23, 2633, 7, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(24, 2633, 13, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(25, 2630, 11, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(26, 2630, 10, 18, 5, '2026-06-05 11:05:44', 'activo', NULL),
(27, 2630, 12, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(28, 2630, 14, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(29, 2630, 7, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(30, 2630, 13, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(31, 2632, 11, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(32, 2632, 10, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(33, 2632, 12, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(34, 2632, 14, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(35, 2632, 7, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(36, 2632, 13, 18, 5, '2026-06-05 11:05:45', 'activo', NULL),
(37, 2634, 5, 17, 6, '2026-06-16 12:57:38', 'activo', NULL),
(38, 2634, 6, 17, 6, '2026-06-16 12:57:38', 'activo', NULL),
(39, 2634, 9, 17, 6, '2026-06-16 12:57:38', 'activo', NULL),
(40, 2636, 35, 19, 6, '2026-06-16 13:42:28', 'activo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_seccion`
--

CREATE TABLE `estudiante_seccion` (
  `id_usuario` int NOT NULL,
  `id_seccion` int NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `estatus` enum('activo','retirado','aprobado','reprobado') COLLATE utf8mb4_spanish_ci DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiante_seccion`
--

INSERT INTO `estudiante_seccion` (`id_usuario`, `id_seccion`, `fecha_inscripcion`, `estatus`) VALUES
(2623, 13, '2026-05-10', 'activo'),
(2624, 13, '2026-05-10', 'activo'),
(2625, 15, '2026-05-10', 'activo'),
(2628, 14, '2026-05-11', 'activo'),
(2629, 14, '2026-05-14', 'activo'),
(2630, 18, '2026-06-05', 'activo'),
(2631, 17, '2026-05-20', 'activo'),
(2632, 18, '2026-06-05', 'activo'),
(2633, 18, '2026-06-05', 'activo'),
(2634, 17, '2026-06-16', 'activo'),
(2636, 19, '2026-06-16', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluacion`
--

CREATE TABLE `evaluacion` (
  `id` int NOT NULL,
  `id_usua` int NOT NULL,
  `pregunta1` int NOT NULL,
  `pregunta2` int NOT NULL,
  `control` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int NOT NULL,
  `genero` varchar(9) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `genero`) VALUES
(1, 'Masculino'),
(2, 'Femenino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `graduados`
--

CREATE TABLE `graduados` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `fecha_graduacion` date DEFAULT NULL,
  `titulo_entregado` tinyint(1) DEFAULT '0',
  `fecha_entrega_titulo` date DEFAULT NULL,
  `acta_entrega` text COLLATE utf8mb3_spanish_ci,
  `observaciones` text COLLATE utf8mb3_spanish_ci,
  `estado` enum('cumple_requisitos','graduado','titulo_entregado') COLLATE utf8mb3_spanish_ci DEFAULT 'cumple_requisitos',
  `id_admin_graduacion` int DEFAULT NULL,
  `id_admin_entrega_titulo` int DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `graduados`
--

INSERT INTO `graduados` (`id`, `id_usuario`, `fecha_graduacion`, `titulo_entregado`, `fecha_entrega_titulo`, `acta_entrega`, `observaciones`, `estado`, `id_admin_graduacion`, `id_admin_entrega_titulo`, `fecha_registro`, `fecha_actualizacion`) VALUES
(2, 2560, '2026-01-28', 1, '2026-01-28', NULL, 'prueba', 'titulo_entregado', 1, 1, '2026-01-28 16:42:57', '2026-01-28 16:43:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambios_notas`
--

CREATE TABLE `historial_cambios_notas` (
  `id` int NOT NULL,
  `id_nota` int DEFAULT NULL,
  `trayecto` int DEFAULT NULL,
  `nota_anterior` decimal(4,2) DEFAULT NULL,
  `nota_nueva` decimal(4,2) DEFAULT NULL,
  `justificacion` text COLLATE utf8mb3_spanish_ci NOT NULL,
  `id_admin` int NOT NULL,
  `fecha_cambio` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_nota_trimestre` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_materia` int DEFAULT NULL,
  `id_periodo` int DEFAULT NULL,
  `trimestre_1_anterior` decimal(5,2) DEFAULT NULL,
  `trimestre_2_anterior` decimal(5,2) DEFAULT NULL,
  `trimestre_3_anterior` decimal(5,2) DEFAULT NULL,
  `trimestre_1_nuevo` decimal(5,2) DEFAULT NULL,
  `trimestre_2_nuevo` decimal(5,2) DEFAULT NULL,
  `trimestre_3_nuevo` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `historial_cambios_notas`
--

INSERT INTO `historial_cambios_notas` (`id`, `id_nota`, `trayecto`, `nota_anterior`, `nota_nueva`, `justificacion`, `id_admin`, `fecha_cambio`, `id_nota_trimestre`, `id_usuario`, `id_materia`, `id_periodo`, `trimestre_1_anterior`, `trimestre_2_anterior`, `trimestre_3_anterior`, `trimestre_1_nuevo`, `trimestre_2_nuevo`, `trimestre_3_nuevo`) VALUES
(1, 194, 0, 13.00, 15.00, 'lol', 2, '2025-11-10 10:33:06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 194, 0, 15.00, 18.00, 'prueba', 2, '2025-11-10 12:23:45', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 194, 0, 18.00, 14.00, 'otra prueba', 2, '2025-11-10 12:57:49', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 194, 0, 14.00, 15.00, 'prueba para auditoria', 2, '2025-11-10 13:03:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, NULL, 1, 20.00, 14.00, 'lol', 2, '2026-05-25 15:52:57', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, NULL, 1, 14.00, 6.00, 'siuuu', 2, '2026-05-26 11:14:14', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, NULL, 1, 6.00, 7.00, 'lol', 2, '2026-05-26 11:32:59', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, NULL, 1, 7.00, 5.00, 'lol', 2, '2026-05-26 11:58:05', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, NULL, 1, 5.00, 13.00, 'lol', 2, '2026-05-26 12:07:56', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, NULL, 1, 13.00, 10.00, 'jiji', 2, '2026-05-26 12:15:16', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, NULL, NULL, NULL, NULL, 'dqd', 2, '2026-05-26 12:28:37', NULL, 2630, 9, 5, 10.00, 16.00, 12.00, 10.00, 10.00, 12.00),
(14, NULL, NULL, NULL, NULL, 'prueba', 2, '2026-05-26 12:39:46', NULL, 2630, 9, 5, 10.00, 10.00, 12.00, 11.00, 10.00, 13.00),
(15, NULL, NULL, NULL, NULL, 'prueba 3', 2, '2026-05-26 12:40:06', NULL, 2630, 9, 5, 11.00, 10.00, 13.00, 10.00, 12.00, 10.00),
(16, NULL, NULL, NULL, NULL, 'prueba', 2, '2026-06-18 10:05:42', NULL, 2633, 9, 5, 18.00, 19.00, 10.00, 18.00, 18.00, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int NOT NULL,
  `id_docente_seccion` int NOT NULL,
  `dia` tinyint NOT NULL COMMENT '0=Lunes, 1=Martes, ..., 5=Sábado',
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `aula` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `id_docente_seccion`, `dia`, `hora_inicio`, `hora_fin`, `aula`) VALUES
(202, 12, 0, '08:00:00', '09:00:00', 'D - 1'),
(203, 12, 1, '07:00:00', '08:00:00', 'D - 1'),
(204, 14, 2, '07:00:00', '08:00:00', 'A - 3'),
(205, 14, 3, '07:00:00', '08:00:00', 'C - 2'),
(217, 7, 5, '07:00:00', '08:00:00', 'D - 1'),
(218, 7, 5, '08:00:00', '09:00:00', 'B - 2'),
(220, 27, 3, '10:00:00', '12:00:00', 'A - 2'),
(226, 37, 0, '08:00:00', '09:30:00', 'A - 1'),
(227, 40, 2, '08:00:00', '09:00:00', 'A - 1'),
(228, 43, 4, '10:30:00', '12:00:00', 'B - 4'),
(229, 44, 2, '09:00:00', '10:30:00', 'A - 1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int NOT NULL,
  `ingreso` varchar(100) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `ingreso`) VALUES
(1, 'Salario fijo'),
(2, 'Salario variable'),
(3, 'Sin ingresos'),
(4, 'No especifica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mallas`
--

CREATE TABLE `mallas` (
  `id_malla` int NOT NULL,
  `id_carrera` int NOT NULL,
  `codigo_malla` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `anio` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `mallas`
--

INSERT INTO `mallas` (`id_malla`, `id_carrera`, `codigo_malla`, `anio`, `descripcion`, `created_at`) VALUES
(1, 14, '133512000', 2000, 'Migrada desde carrera_versiones id_version=2', '2026-01-14 14:40:32'),
(2, 14, '133511977', 1977, 'Migrada desde carrera_versiones id_version=3', '2026-01-14 14:40:32'),
(3, 1, '142322025', 2025, 'Malla generada automáticamente', '2026-01-14 15:22:34'),
(4, 5, '142312025', 2025, 'Malla generada automáticamente', '2026-01-14 15:22:39'),
(5, 2, '135692025', 2025, 'Malla generada automáticamente', '2026-01-14 15:22:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `malla_materia`
--

CREATE TABLE `malla_materia` (
  `id` int NOT NULL,
  `id_malla` int NOT NULL,
  `id_materia` int NOT NULL,
  `semestre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `malla_materia`
--

INSERT INTO `malla_materia` (`id`, `id_malla`, `id_materia`, `semestre`) VALUES
(1, 1, 29, 1),
(2, 1, 30, 1),
(3, 2, 31, 1),
(4, 3, 5, 1),
(5, 3, 7, 3),
(6, 3, 6, 1),
(7, 3, 9, 1),
(8, 3, 10, 3),
(9, 3, 11, 3),
(10, 3, 12, 3),
(11, 3, 13, 3),
(12, 3, 14, 3),
(13, 3, 16, 2),
(14, 3, 17, 2),
(15, 3, 18, 3),
(16, 3, 19, 1),
(17, 3, 21, 3),
(18, 3, 22, 3),
(19, 3, 20, 1),
(20, 3, 23, 2),
(21, 3, 24, 1),
(22, 3, 25, 1),
(23, 3, 26, 1),
(24, 3, 27, 3),
(25, 3, 28, 3),
(26, 5, 15, 1),
(28, 1, 32, 3),
(29, 4, 35, 1),
(30, 4, 36, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materia` int NOT NULL,
  `cod_materia` varchar(20) COLLATE utf32_spanish2_ci NOT NULL,
  `pnf_ptf` varchar(3) COLLATE utf32_spanish2_ci NOT NULL,
  `nombre_materia` varchar(100) COLLATE utf32_spanish2_ci NOT NULL,
  `creditos` int DEFAULT '3',
  `activa` tinyint(1) DEFAULT '1',
  `horas_teoricas` int DEFAULT NULL,
  `horas_practicas` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `duracion_periodo` int NOT NULL,
  `trayecto` tinyint NOT NULL DEFAULT '1',
  `es_proyecto_socio` tinyint(1) DEFAULT '0',
  `horas_laboratorio` int DEFAULT NULL,
  `horas_semanales` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_materia`, `cod_materia`, `pnf_ptf`, `nombre_materia`, `creditos`, `activa`, `horas_teoricas`, `horas_practicas`, `created_at`, `duracion_periodo`, `trayecto`, `es_proyecto_socio`, `horas_laboratorio`, `horas_semanales`) VALUES
(5, 'MAC015', 'PNF', 'Matematica', 5, 1, 2, 2, '2025-06-04 18:21:13', 1, 0, 0, NULL, NULL),
(6, 'PNS013', 'PNF', 'Proyecto Nacional y Nueva Ciudadania', 3, 1, 2, 2, '2025-06-04 18:22:37', 1, 0, 0, NULL, NULL),
(7, 'MAC139', 'PNF', 'Matemática I', 9, 1, 2, 2, '2025-06-16 15:21:07', 3, 1, 0, NULL, NULL),
(9, 'IPC012', 'PNF', 'Introducción a los Proyectos y al PNF', 2, 1, 2, 2, '2025-08-01 19:51:18', 1, 0, 0, NULL, NULL),
(10, 'ACT139', 'PNF', 'Arquitectura del Computador', 9, 1, 2, 2, '2025-08-01 19:58:28', 3, 1, 0, NULL, NULL),
(11, 'APT1312', 'PNF', 'Algorítmica y Programación', 12, 1, 2, 2, '2025-08-01 20:03:19', 3, 1, 0, NULL, NULL),
(12, 'FCS133', 'PNF', 'Formación Crítica I', 3, 1, 2, 2, '2025-08-01 20:05:28', 3, 1, 0, NULL, NULL),
(13, 'PTP139', 'PNF', 'Proyecto Socio tecnológico I', 9, 1, 2, 2, '2025-08-01 20:07:21', 3, 1, 1, NULL, NULL),
(14, 'IDC133', 'PNF', 'Inglés', 3, 1, 2, 2, '2025-08-01 20:08:35', 3, 1, 0, NULL, NULL),
(15, 'TTIU40', 'PNF', 'Introducción a la Universidad y El Turismo', 1, 1, 2, 2, '2025-08-07 22:35:15', 1, 0, 0, NULL, NULL),
(16, 'MAC226', 'PNF', 'Matematica II', 6, 1, 2, 2, '2025-08-24 18:14:27', 2, 2, 0, NULL, NULL),
(17, 'RCT226', 'PNF', 'Redes de Computadoras', 6, 1, 2, 2, '2025-08-24 18:18:37', 2, 2, 0, NULL, NULL),
(18, 'POT2312', 'PNF', 'Programacion II', 12, 1, 2, 2, '2025-08-24 18:48:27', 3, 2, 0, NULL, NULL),
(19, 'ISC213', 'PNF', 'Ingenieria del Software I', 3, 1, 2, 2, '2025-08-24 18:50:56', 1, 2, 0, NULL, NULL),
(20, 'BDC213', 'PNF', 'Base de Datos', 3, 1, 2, 2, '2025-08-24 18:53:03', 1, 2, 0, NULL, NULL),
(21, 'FCS233', 'PNF', 'Formación Crítica II', 3, 1, 2, 2, '2025-08-24 18:57:07', 3, 2, 0, NULL, NULL),
(22, 'PTP239', 'PNF', 'Proyecto Socio tecnológico II', 9, 1, 2, 2, '2025-08-24 19:00:14', 3, 2, 0, NULL, NULL),
(23, 'MAC326', 'PNF', 'Matematica Aplicada', 6, 1, 2, 2, '2025-08-24 20:09:26', 2, 3, 0, NULL, NULL),
(24, 'IOC313', 'PNF', 'Investigación de Operaciones', 3, 1, 2, 2, '2025-08-24 20:12:20', 1, 3, 0, NULL, NULL),
(25, 'SOC313', 'PNF', 'Sistemas Operativos', 3, 1, 2, 2, '2025-08-24 20:14:44', 1, 3, 0, NULL, NULL),
(26, 'BDC313', 'PNF', 'Modelado de Bases de Datos', 3, 1, 2, 2, '2025-08-29 19:20:37', 1, 3, 0, NULL, NULL),
(27, 'FCS333', 'PNF', 'Formación Crítica III', 3, 1, 2, 2, '2025-08-29 19:33:31', 3, 3, 0, NULL, NULL),
(28, 'ISC339', 'PNF', 'Ingeniería del Software II', 9, 1, 2, 2, '2025-08-29 19:44:59', 3, 3, 0, NULL, NULL),
(29, 'MAT-154', 'PTF', 'Matemática I', 4, 1, 3, 2, '2026-01-13 16:15:00', 1, 1, 0, NULL, NULL),
(30, 'AE-1111', 'PTF', 'Fisica', 5, 1, 2, 2, '2026-01-13 16:41:36', 1, 1, 0, NULL, NULL),
(31, 'AE-1241', 'PTF', 'Deporte I', 2, 1, 2, 2, '2026-01-13 17:37:32', 1, 1, 0, NULL, NULL),
(32, 'MAT-253', 'PTF', 'Matemática II', 3, 1, 2, 3, '2026-01-14 16:11:55', 1, 2, 0, 0, 4),
(33, 'MAT -154', 'PTF', 'Matemática I', 4, 1, 3, 2, '2026-01-30 13:17:19', 1, 1, 0, 0, 0),
(34, 'GEA -142', 'PTF', 'GEOMETRÍA ANALÍTICA', 2, 1, 1, 3, '2026-01-30 13:20:21', 1, 1, 0, 0, 0),
(35, 'LDIEP006000', 'PTF', 'Instituciones de Educación Universitaria y los  Programas Nacionales de Formación', 0, 1, 1, 1, '2026-01-30 14:17:14', 1, 0, 0, 0, 2),
(36, 'LDEOE006000', 'PTF', 'Expresión Oral y Escrita', 0, 1, 1, 1, '2026-01-30 14:50:18', 1, 0, 0, 0, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajeria`
--

CREATE TABLE `mensajeria` (
  `id` int NOT NULL,
  `id_usuario_remitente` int NOT NULL,
  `id_usuario_destinatario` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `leido` tinyint(1) DEFAULT '0',
  `archivado_remitente` tinyint(1) DEFAULT '0',
  `archivado_destinatario` tinyint(1) DEFAULT '0',
  `eliminado_remitente` tinyint(1) DEFAULT '0',
  `eliminado_destinatario` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `mensajeria`
--

INSERT INTO `mensajeria` (`id`, `id_usuario_remitente`, `id_usuario_destinatario`, `titulo`, `mensaje`, `fecha_envio`, `leido`, `archivado_remitente`, `archivado_destinatario`, `eliminado_remitente`, `eliminado_destinatario`) VALUES
(6, 2, 4, 'Notas Rechazadas', 'porque si', '2025-09-12 12:27:37', 1, 0, 0, 0, 0),
(7, 2, 4, 'Notas Rechazadas', 'porque si tambien', '2025-09-12 12:28:07', 1, 0, 0, 0, 0),
(8, 2, 4, 'Notas Rechazadas', 'porque nunca fue', '2025-09-15 09:33:08', 1, 0, 0, 0, 0),
(9, 2, 4, 'Notas Rechazadas', 'estudiante prueba de rechazo de notas', '2025-09-16 09:41:27', 1, 0, 0, 0, 0),
(10, 4, 2, 'prueba', 'lol', '2025-09-17 09:44:36', 1, 0, 0, 0, 0),
(11, 4, 2, 'prueba', 'lol x2', '2025-09-17 10:40:09', 1, 0, 0, 0, 0),
(12, 4, 2, 'prueba', 'prueba', '2025-09-17 10:51:29', 1, 0, 0, 0, 0),
(13, 4, 2, 'prueba', 'pruebaaa', '2025-09-17 11:17:59', 1, 0, 0, 0, 0),
(14, 4, 2, 'prueba', 'llololol', '2025-09-17 11:39:31', 1, 0, 0, 0, 0),
(15, 4, 2, 'prueba', 'huygb7i6v', '2025-09-17 11:48:08', 1, 0, 0, 0, 0),
(16, 4, 2, 'prueba', 'kjhiuy', '2025-09-17 11:52:48', 1, 0, 0, 0, 0),
(17, 2, 4, 'Notas Rechazadas', 'porque si', '2025-09-17 11:58:34', 1, 0, 0, 0, 0),
(18, 2, 4, 'Notas Aprobadas', 'Las notas de los estudiantes Ana Rodríguez, Anaa Rodríguez han sido aprobadas exitosamente.', '2025-09-18 13:08:14', 1, 0, 0, 0, 0),
(19, 2, 5, 'lol', 'dkfjirhgjñer', '2025-09-22 09:32:54', 1, 0, 0, 0, 0),
(20, 2, 5, 'lol', 'dkfjirhgjñer', '2025-09-22 09:33:06', 1, 0, 0, 0, 0),
(21, 2, 4, 'Notas Aprobadas', 'Las notas de los estudiantes Adriana Castro, Adriana Ríos, Andrea Medina, Beatriz Rangel, Carolina Herrera, Carolina Silva, Daniela Mora, Diana Contreras, Elena Cordero, Eliud Miguel Mendoza Perez, Gabriela Mendoza han sido aprobadas exitosamente.', '2025-09-30 12:47:53', 1, 0, 0, 0, 0),
(22, 2, 2, 'Notas Rechazadas', 'Las notas de todos los estudiantes del grupo han sido rechazadas debido a: pruebas', '2025-09-30 12:50:25', 1, 0, 0, 0, 0),
(23, 2, 4, 'Notas Rechazadas', 'Las notas de todos los estudiantes del grupo han sido rechazadas debido a: pruebas', '2025-09-30 12:59:32', 1, 0, 0, 0, 0),
(24, 2, 4, 'Notas Aprobadas', 'Las notas de los estudiantes Gabriela Rojas, Gisela Ferrer, Gladys Suárez, Isabel Díaz han sido aprobadas exitosamente.', '2025-09-30 13:00:52', 1, 0, 0, 0, 0),
(25, 2, 2, 'Notas Rechazadas', 'La nota del estudiante Adriana Ríos ha sido rechazada debido a: prueba', '2025-10-09 11:27:31', 1, 0, 0, 0, 0),
(26, 2, 2, 'Notas Rechazadas', 'Las notas de los estudiantes Adriana Ríos, Ana Rodríguez, Anaa Rodríguez han sido rechazadas debido a:prueba', '2025-10-09 11:31:05', 1, 0, 0, 0, 0),
(27, 2, 2, 'Notas Aprobadas', 'Las notas de todos los estudiantes del grupo han sido aprobadas exitosamente.', '2025-10-16 11:12:55', 1, 0, 0, 0, 0),
(28, 2, 4, 'prueba de auditoria', 'prueba de auditoria', '2025-10-23 10:22:49', 1, 0, 0, 0, 0),
(29, 5, 4, 'prueba de vocero', 'prueba', '2026-03-25 12:11:33', 1, 0, 0, 0, 0),
(30, 2, 1, 'prueba', 'funciona?', '2026-06-04 10:28:22', 1, 0, 0, 0, 0),
(31, 2, 1, 'Prueba de mensaje', 'Este es un mensaje de prueba desde el administrador', '2026-06-04 10:40:27', 1, 0, 0, 0, 0),
(32, 2, 1, '✅ Notas APROBADAS - Proyecto Nacional y Nueva Ciudadania (2026-1)', 'lol', '2026-06-04 11:05:51', 1, 0, 0, 0, 0),
(33, 2, 1, '✅ Notas APROBADAS - Proyecto Nacional y Nueva Ciudadania (2026-1)', 'lol', '2026-06-04 11:19:13', 1, 0, 0, 0, 0),
(34, 2, 1, '❌ Notas RECHAZADAS - Proyecto Nacional y Nueva Ciudadania (2026-1)', 'lol', '2026-06-05 10:06:30', 1, 0, 0, 0, 0),
(35, 2, 1, '✅ Notas APROBADAS - Proyecto Nacional y Nueva Ciudadania (2026-1)', '========================================\n✅ APROBACIÓN DE NOTAS\n========================================\n\nEstimado(a) docente,\n\nLe informamos que las notas que usted registró para la materia Proyecto Nacional y Nueva Ciudadania han sido APROBADAS por el administrador.\n\n✅ Las notas ya están disponibles para que los estudiantes las consulten.\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\nSistema de Gestión de Notas - UPT Puerto Cabello', '2026-06-05 10:17:34', 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int NOT NULL,
  `id_estado` int NOT NULL,
  `municipio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `id_estado`, `municipio`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Atabapo'),
(3, 1, 'Atures'),
(4, 1, 'Autana'),
(5, 1, 'Manapiare'),
(6, 1, 'Maroa'),
(7, 1, 'Río Negro'),
(8, 2, 'Anaco'),
(9, 2, 'Aragua'),
(10, 2, 'Manuel Ezequiel Bruzual'),
(11, 2, 'Diego Bautista Urbaneja'),
(12, 2, 'Fernando Peñalver'),
(13, 2, 'Francisco Del Carmen Carvajal'),
(14, 2, 'General Sir Arthur McGregor'),
(15, 2, 'Guanta'),
(16, 2, 'Independencia'),
(17, 2, 'José Gregorio Monagas'),
(18, 2, 'Juan Antonio Sotillo'),
(19, 2, 'Juan Manuel Cajigal'),
(20, 2, 'Libertad'),
(21, 2, 'Francisco de Miranda'),
(22, 2, 'Pedro María Freites'),
(23, 2, 'Píritu'),
(24, 2, 'San José de Guanipa'),
(25, 2, 'San Juan de Capistrano'),
(26, 2, 'Santa Ana'),
(27, 2, 'Simón Bolívar'),
(28, 2, 'Simón Rodríguez'),
(29, 3, 'Achaguas'),
(30, 3, 'Biruaca'),
(31, 3, 'Muñóz'),
(32, 3, 'Páez'),
(33, 3, 'Pedro Camejo'),
(34, 3, 'Rómulo Gallegos'),
(35, 3, 'San Fernando'),
(36, 4, 'Atanasio Girardot'),
(37, 4, 'Bolívar'),
(38, 4, 'Camatagua'),
(39, 4, 'Francisco Linares Alcántara'),
(40, 4, 'José Ángel Lamas'),
(41, 4, 'José Félix Ribas'),
(42, 4, 'José Rafael Revenga'),
(43, 4, 'Libertador'),
(44, 4, 'Mario Briceño Iragorry'),
(45, 4, 'Ocumare de la Costa de Oro'),
(46, 4, 'San Casimiro'),
(47, 4, 'San Sebastián'),
(48, 4, 'Santiago Mariño'),
(49, 4, 'Santos Michelena'),
(50, 4, 'Sucre'),
(51, 4, 'Tovar'),
(52, 4, 'Urdaneta'),
(53, 4, 'Zamora'),
(54, 5, 'Alberto Arvelo Torrealba'),
(55, 5, 'Andrés Eloy Blanco'),
(56, 5, 'Antonio José de Sucre'),
(57, 5, 'Arismendi'),
(58, 5, 'Barinas'),
(59, 5, 'Bolívar'),
(60, 5, 'Cruz Paredes'),
(61, 5, 'Ezequiel Zamora'),
(62, 5, 'Obispos'),
(63, 5, 'Pedraza'),
(64, 5, 'Rojas'),
(65, 5, 'Sosa'),
(66, 6, 'Caroní'),
(67, 6, 'Cedeño'),
(68, 6, 'El Callao'),
(69, 6, 'Gran Sabana'),
(70, 6, 'Heres'),
(71, 6, 'Piar'),
(72, 6, 'Angostura (Raúl Leoni)'),
(73, 6, 'Roscio'),
(74, 6, 'Sifontes'),
(75, 6, 'Sucre'),
(76, 6, 'Padre Pedro Chien'),
(77, 7, 'Bejuma'),
(78, 7, 'Carlos Arvelo'),
(79, 7, 'Diego Ibarra'),
(80, 7, 'Guacara'),
(81, 7, 'Juan José Mora'),
(82, 7, 'Libertador'),
(83, 7, 'Los Guayos'),
(84, 7, 'Miranda'),
(85, 7, 'Montalbán'),
(86, 7, 'Naguanagua'),
(87, 7, 'Puerto Cabello'),
(88, 7, 'San Diego'),
(89, 7, 'San Joaquín'),
(90, 7, 'Valencia'),
(91, 8, 'Anzoátegui'),
(92, 8, 'Tinaquillo'),
(93, 8, 'Girardot'),
(94, 8, 'Lima Blanco'),
(95, 8, 'Pao de San Juan Bautista'),
(96, 8, 'Ricaurte'),
(97, 8, 'Rómulo Gallegos'),
(98, 8, 'San Carlos'),
(99, 8, 'Tinaco'),
(100, 9, 'Antonio Díaz'),
(101, 9, 'Casacoima'),
(102, 9, 'Pedernales'),
(103, 9, 'Tucupita'),
(104, 10, 'Acosta'),
(105, 10, 'Bolívar'),
(106, 10, 'Buchivacoa'),
(107, 10, 'Cacique Manaure'),
(108, 10, 'Carirubana'),
(109, 10, 'Colina'),
(110, 10, 'Dabajuro'),
(111, 10, 'Democracia'),
(112, 10, 'Falcón'),
(113, 10, 'Federación'),
(114, 10, 'Jacura'),
(115, 10, 'José Laurencio Silva'),
(116, 10, 'Los Taques'),
(117, 10, 'Mauroa'),
(118, 10, 'Miranda'),
(119, 10, 'Monseñor Iturriza'),
(120, 10, 'Palmasola'),
(121, 10, 'Petit'),
(122, 10, 'Píritu'),
(123, 10, 'San Francisco'),
(124, 10, 'Sucre'),
(125, 10, 'Tocópero'),
(126, 10, 'Unión'),
(127, 10, 'Urumaco'),
(128, 10, 'Zamora'),
(129, 11, 'Camaguán'),
(130, 11, 'Chaguaramas'),
(131, 11, 'El Socorro'),
(132, 11, 'José Félix Ribas'),
(133, 11, 'José Tadeo Monagas'),
(134, 11, 'Juan Germán Roscio'),
(135, 11, 'Julián Mellado'),
(136, 11, 'Las Mercedes'),
(137, 11, 'Leonardo Infante'),
(138, 11, 'Pedro Zaraza'),
(139, 11, 'Ortíz'),
(140, 11, 'San Gerónimo de Guayabal'),
(141, 11, 'San José de Guaribe'),
(142, 11, 'Santa María de Ipire'),
(143, 11, 'Sebastián Francisco de Miranda'),
(144, 12, 'Andrés Eloy Blanco'),
(145, 12, 'Crespo'),
(146, 12, 'Iribarren'),
(147, 12, 'Jiménez'),
(148, 12, 'Morán'),
(149, 12, 'Palavecino'),
(150, 12, 'Simón Planas'),
(151, 12, 'Torres'),
(152, 12, 'Urdaneta'),
(179, 13, 'Alberto Adriani'),
(180, 13, 'Andrés Bello'),
(181, 13, 'Antonio Pinto Salinas'),
(182, 13, 'Aricagua'),
(183, 13, 'Arzobispo Chacón'),
(184, 13, 'Campo Elías'),
(185, 13, 'Caracciolo Parra Olmedo'),
(186, 13, 'Cardenal Quintero'),
(187, 13, 'Guaraque'),
(188, 13, 'Julio César Salas'),
(189, 13, 'Justo Briceño'),
(190, 13, 'Libertador'),
(191, 13, 'Miranda'),
(192, 13, 'Obispo Ramos de Lora'),
(193, 13, 'Padre Noguera'),
(194, 13, 'Pueblo Llano'),
(195, 13, 'Rangel'),
(196, 13, 'Rivas Dávila'),
(197, 13, 'Santos Marquina'),
(198, 13, 'Sucre'),
(199, 13, 'Tovar'),
(200, 13, 'Tulio Febres Cordero'),
(201, 13, 'Zea'),
(223, 14, 'Acevedo'),
(224, 14, 'Andrés Bello'),
(225, 14, 'Baruta'),
(226, 14, 'Brión'),
(227, 14, 'Buroz'),
(228, 14, 'Carrizal'),
(229, 14, 'Chacao'),
(230, 14, 'Cristóbal Rojas'),
(231, 14, 'El Hatillo'),
(232, 14, 'Guaicaipuro'),
(233, 14, 'Independencia'),
(234, 14, 'Lander'),
(235, 14, 'Los Salias'),
(236, 14, 'Páez'),
(237, 14, 'Paz Castillo'),
(238, 14, 'Pedro Gual'),
(239, 14, 'Plaza'),
(240, 14, 'Simón Bolívar'),
(241, 14, 'Sucre'),
(242, 14, 'Urdaneta'),
(243, 14, 'Zamora'),
(258, 15, 'Acosta'),
(259, 15, 'Aguasay'),
(260, 15, 'Bolívar'),
(261, 15, 'Caripe'),
(262, 15, 'Cedeño'),
(263, 15, 'Ezequiel Zamora'),
(264, 15, 'Libertador'),
(265, 15, 'Maturín'),
(266, 15, 'Piar'),
(267, 15, 'Punceres'),
(268, 15, 'Santa Bárbara'),
(269, 15, 'Sotillo'),
(270, 15, 'Uracoa'),
(271, 16, 'Antolín del Campo'),
(272, 16, 'Arismendi'),
(273, 16, 'García'),
(274, 16, 'Gómez'),
(275, 16, 'Maneiro'),
(276, 16, 'Marcano'),
(277, 16, 'Mariño'),
(278, 16, 'Península de Macanao'),
(279, 16, 'Tubores'),
(280, 16, 'Villalba'),
(281, 16, 'Díaz'),
(282, 17, 'Agua Blanca'),
(283, 17, 'Araure'),
(284, 17, 'Esteller'),
(285, 17, 'Guanare'),
(286, 17, 'Guanarito'),
(287, 17, 'Monseñor José Vicente de Unda'),
(288, 17, 'Ospino'),
(289, 17, 'Páez'),
(290, 17, 'Papelón'),
(291, 17, 'San Genaro de Boconoíto'),
(292, 17, 'San Rafael de Onoto'),
(293, 17, 'Santa Rosalía'),
(294, 17, 'Sucre'),
(295, 17, 'Turén'),
(296, 18, 'Andrés Eloy Blanco'),
(297, 18, 'Andrés Mata'),
(298, 18, 'Arismendi'),
(299, 18, 'Benítez'),
(300, 18, 'Bermúdez'),
(301, 18, 'Bolívar'),
(302, 18, 'Cajigal'),
(303, 18, 'Cruz Salmerón Acosta'),
(304, 18, 'Libertador'),
(305, 18, 'Mariño'),
(306, 18, 'Mejía'),
(307, 18, 'Montes'),
(308, 18, 'Ribero'),
(309, 18, 'Sucre'),
(310, 18, 'Valdéz'),
(341, 19, 'Andrés Bello'),
(342, 19, 'Antonio Rómulo Costa'),
(343, 19, 'Ayacucho'),
(344, 19, 'Bolívar'),
(345, 19, 'Cárdenas'),
(346, 19, 'Córdoba'),
(347, 19, 'Fernández Feo'),
(348, 19, 'Francisco de Miranda'),
(349, 19, 'García de Hevia'),
(350, 19, 'Guásimos'),
(351, 19, 'Independencia'),
(352, 19, 'Jáuregui'),
(353, 19, 'José María Vargas'),
(354, 19, 'Junín'),
(355, 19, 'Libertad'),
(356, 19, 'Libertador'),
(357, 19, 'Lobatera'),
(358, 19, 'Michelena'),
(359, 19, 'Panamericano'),
(360, 19, 'Pedro María Ureña'),
(361, 19, 'Rafael Urdaneta'),
(362, 19, 'Samuel Darío Maldonado'),
(363, 19, 'San Cristóbal'),
(364, 19, 'Seboruco'),
(365, 19, 'Simón Rodríguez'),
(366, 19, 'Sucre'),
(367, 19, 'Torbes'),
(368, 19, 'Uribante'),
(369, 19, 'San Judas Tadeo'),
(370, 20, 'Andrés Bello'),
(371, 20, 'Boconó'),
(372, 20, 'Bolívar'),
(373, 20, 'Candelaria'),
(374, 20, 'Carache'),
(375, 20, 'Escuque'),
(376, 20, 'José Felipe Márquez Cañizalez'),
(377, 20, 'Juan Vicente Campos Elías'),
(378, 20, 'La Ceiba'),
(379, 20, 'Miranda'),
(380, 20, 'Monte Carmelo'),
(381, 20, 'Motatán'),
(382, 20, 'Pampán'),
(383, 20, 'Pampanito'),
(384, 20, 'Rafael Rangel'),
(385, 20, 'San Rafael de Carvajal'),
(386, 20, 'Sucre'),
(387, 20, 'Trujillo'),
(388, 20, 'Urdaneta'),
(389, 20, 'Valera'),
(390, 21, 'Vargas'),
(391, 22, 'Arístides Bastidas'),
(392, 22, 'Bolívar'),
(407, 22, 'Bruzual'),
(408, 22, 'Cocorote'),
(409, 22, 'Independencia'),
(410, 22, 'José Antonio Páez'),
(411, 22, 'La Trinidad'),
(412, 22, 'Manuel Monge'),
(413, 22, 'Nirgua'),
(414, 22, 'Peña'),
(415, 22, 'San Felipe'),
(416, 22, 'Sucre'),
(417, 22, 'Urachiche'),
(418, 22, 'José Joaquín Veroes'),
(441, 23, 'Almirante Padilla'),
(442, 23, 'Baralt'),
(443, 23, 'Cabimas'),
(444, 23, 'Catatumbo'),
(445, 23, 'Colón'),
(446, 23, 'Francisco Javier Pulgar'),
(447, 23, 'Páez'),
(448, 23, 'Jesús Enrique Losada'),
(449, 23, 'Jesús María Semprún'),
(450, 23, 'La Cañada de Urdaneta'),
(451, 23, 'Lagunillas'),
(452, 23, 'Machiques de Perijá'),
(453, 23, 'Mara'),
(454, 23, 'Maracaibo'),
(455, 23, 'Miranda'),
(456, 23, 'Rosario de Perijá'),
(457, 23, 'San Francisco'),
(458, 23, 'Santa Rita'),
(459, 23, 'Simón Bolívar'),
(460, 23, 'Sucre'),
(461, 23, 'Valmore Rodríguez'),
(462, 24, 'Libertador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nombre_curso`
--

CREATE TABLE `nombre_curso` (
  `id` int NOT NULL,
  `titulo` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `sub_titulo` varchar(500) COLLATE latin1_spanish_ci NOT NULL,
  `contenido` text COLLATE latin1_spanish_ci NOT NULL,
  `ponente1` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `cedula1` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `ponente2` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `cedula2` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `lugar` varchar(25) COLLATE latin1_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `horas` varchar(3) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_definitivas`
--

CREATE TABLE `notas_definitivas` (
  `id` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_materia` int DEFAULT NULL,
  `id_periodo` int DEFAULT NULL,
  `id_docente` int DEFAULT NULL,
  `trayecto_0` int DEFAULT NULL,
  `trayecto_1` int DEFAULT NULL,
  `trayecto_2` int DEFAULT NULL,
  `trayecto_3` int DEFAULT NULL,
  `trayecto_4` int DEFAULT NULL,
  `soporte` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `tipo_archivo` varchar(10) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `id_admin_aprobador` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `notas_definitivas`
--

INSERT INTO `notas_definitivas` (`id`, `id_usuario`, `id_materia`, `id_periodo`, `id_docente`, `trayecto_0`, `trayecto_1`, `trayecto_2`, `trayecto_3`, `trayecto_4`, `soporte`, `tipo_archivo`, `fecha_registro`, `id_admin_aprobador`) VALUES
(227, 2459, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(228, 2545, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(229, 2451, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(230, 2529, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(231, 2471, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(232, 2565, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(233, 2541, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(234, 2465, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(235, 2553, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(236, 2567, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(237, 2473, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(238, 2379, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(239, 2539, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(240, 2461, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(241, 2571, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(242, 2557, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(243, 5, 5, 2, 4, 17, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(244, 2455, 5, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:19:24', 2),
(258, 2459, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(259, 2545, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(260, 2451, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(261, 2529, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(262, 2471, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(263, 2565, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(264, 2541, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(265, 2465, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(266, 2553, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(267, 2567, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(268, 2473, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(269, 2379, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(270, 2539, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(271, 2461, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(272, 2571, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(273, 2557, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(274, 5, 9, 2, 2, 19, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(275, 2455, 9, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 12:20:18', 2),
(289, 5, 7, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-26 11:04:51', NULL),
(290, 5, 10, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-26 11:04:51', NULL),
(292, 5, 12, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-26 11:04:51', NULL),
(293, 5, 13, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-26 11:04:51', NULL),
(295, 2560, 15, 2, 2, 16, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(296, 2570, 15, 2, 2, 20, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(297, 2462, 15, 2, 2, 15, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(298, 2540, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(299, 2554, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(300, 2476, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(301, 2564, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(302, 2450, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(303, 2530, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(304, 2538, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(305, 2464, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(306, 2562, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(307, 2566, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(308, 2454, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(309, 2550, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2),
(310, 2568, 15, 2, 2, 1, NULL, NULL, NULL, NULL, 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:25:19', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_pendientes`
--

CREATE TABLE `notas_pendientes` (
  `id` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_materia` int DEFAULT NULL,
  `id_periodo` int DEFAULT NULL,
  `id_docente` int DEFAULT NULL,
  `trayecto_0` int DEFAULT NULL,
  `trayecto_1` int DEFAULT NULL,
  `trayecto_2` int DEFAULT NULL,
  `trayecto_3` int DEFAULT NULL,
  `trayecto_4` int DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada','en revision') COLLATE utf8mb4_spanish_ci DEFAULT 'en revision',
  `soporte` varchar(255) COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'Ruta o nombre del archivo de imagen de soporte',
  `tipo_archivo` varchar(10) COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'jpg, png, jpeg, etc',
  `fecha_subida` datetime DEFAULT NULL,
  `estado_aprobacion_trimestre_1` enum('pendiente','en_revision','aprobada','rechazada') COLLATE utf8mb4_spanish_ci DEFAULT 'pendiente',
  `estado_aprobacion_trimestre_2` enum('pendiente','en_revision','aprobada','rechazada') COLLATE utf8mb4_spanish_ci DEFAULT 'pendiente',
  `estado_aprobacion_trimestre_3` enum('pendiente','en_revision','aprobada','rechazada') COLLATE utf8mb4_spanish_ci DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `notas_pendientes`
--

INSERT INTO `notas_pendientes` (`id`, `id_usuario`, `id_materia`, `id_periodo`, `id_docente`, `trayecto_0`, `trayecto_1`, `trayecto_2`, `trayecto_3`, `trayecto_4`, `fecha_envio`, `estado`, `soporte`, `tipo_archivo`, `fecha_subida`, `estado_aprobacion_trimestre_1`, `estado_aprobacion_trimestre_2`, `estado_aprobacion_trimestre_3`) VALUES
(567, 2459, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(568, 2545, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(569, 2451, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(570, 2529, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(571, 2471, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(572, 2565, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(573, 2541, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(574, 2465, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(575, 2553, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(576, 2567, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(577, 2473, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(578, 2379, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(579, 2539, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(580, 2461, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(581, 2571, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(582, 2557, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(583, 5, 5, 2, 4, 17, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(584, 2455, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(585, 2459, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(586, 2545, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(587, 2451, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(588, 2529, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(589, 2471, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(590, 2565, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(591, 2541, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(592, 2465, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(593, 2553, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(594, 2567, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(595, 2473, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(596, 2379, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(597, 2539, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(598, 2461, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(599, 2571, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(600, 2557, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(601, 5, 9, 2, 2, 19, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(602, 2455, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL, 'pendiente', 'pendiente', 'pendiente'),
(621, 2560, 15, 2, 2, 16, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(622, 2570, 15, 2, 2, 20, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(623, 2462, 15, 2, 2, 15, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(624, 2540, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(625, 2554, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(626, 2476, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(627, 2564, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(628, 2450, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(629, 2530, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(630, 2538, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(631, 2464, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(632, 2562, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(633, 2566, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(634, 2454, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(635, 2550, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(636, 2568, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05', 'pendiente', 'pendiente', 'pendiente'),
(647, 2628, 9, 5, 4, 16, NULL, NULL, NULL, NULL, '2026-05-25 12:39:41', 'pendiente', 'soporte_6a217ec6bf7336.05242164_1780580038.pdf', 'pdf', '2026-06-04 09:33:58', 'pendiente', 'pendiente', 'pendiente'),
(648, 2630, 9, 5, 4, 10, NULL, NULL, NULL, NULL, '2026-05-25 12:39:41', 'pendiente', 'soporte_6a217ec6bf7336.05242164_1780580038.pdf', 'pdf', '2026-06-04 09:33:58', 'pendiente', 'pendiente', 'pendiente'),
(649, 2632, 9, 5, 4, 14, NULL, NULL, NULL, NULL, '2026-05-25 12:39:41', 'pendiente', 'soporte_6a217ec6bf7336.05242164_1780580038.pdf', 'pdf', '2026-06-04 09:33:58', 'pendiente', 'pendiente', 'pendiente'),
(650, 2629, 9, 5, 4, 14, NULL, NULL, NULL, NULL, '2026-05-25 12:39:41', 'pendiente', 'soporte_6a217ec6bf7336.05242164_1780580038.pdf', 'pdf', '2026-06-04 09:33:58', 'pendiente', 'pendiente', 'pendiente'),
(651, 2633, 5, 5, 1, 15, NULL, NULL, NULL, NULL, '2026-05-29 09:27:06', 'pendiente', 'soporte_6a2049bb6c66d1.74588697_1780500923.pdf', 'pdf', '2026-06-03 11:35:23', 'pendiente', 'pendiente', 'pendiente'),
(652, 2628, 5, 5, 1, 5, NULL, NULL, NULL, NULL, '2026-05-29 12:52:06', 'pendiente', 'soporte_6a2049bb6c66d1.74588697_1780500923.pdf', 'pdf', '2026-06-03 11:35:23', 'pendiente', 'pendiente', 'pendiente'),
(653, 2630, 5, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-05-29 13:38:07', 'en revision', 'soporte_6a2049bb6c66d1.74588697_1780500923.pdf', 'pdf', '2026-06-03 11:35:23', 'pendiente', 'pendiente', 'pendiente'),
(654, 2632, 5, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-05-29 13:38:07', 'en revision', 'soporte_6a2049bb6c66d1.74588697_1780500923.pdf', 'pdf', '2026-06-03 11:35:23', 'pendiente', 'pendiente', 'pendiente'),
(655, 2629, 5, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-05-29 13:38:07', 'en revision', 'soporte_6a2049bb6c66d1.74588697_1780500923.pdf', 'pdf', '2026-06-03 11:35:23', 'pendiente', 'pendiente', 'pendiente'),
(656, 2633, 9, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-03 12:29:03', 'en revision', 'soporte_6a217ec6bf7336.05242164_1780580038.pdf', 'pdf', '2026-06-04 09:33:58', 'pendiente', 'pendiente', 'pendiente'),
(657, 2633, 6, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-04 09:41:11', 'en revision', 'soporte_6a22d79e697c35.67772523_1780668318.pdf', 'pdf', '2026-06-05 10:05:18', 'pendiente', 'pendiente', 'pendiente'),
(658, 2628, 6, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-04 09:41:11', 'en revision', 'soporte_6a22d89113bd27.32669537_1780668561.pdf', 'pdf', '2026-06-05 10:09:21', 'pendiente', 'pendiente', 'pendiente'),
(659, 2630, 6, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-04 09:41:11', 'en revision', 'soporte_6a22d89113bd27.32669537_1780668561.pdf', 'pdf', '2026-06-05 10:09:21', 'pendiente', 'pendiente', 'pendiente'),
(660, 2632, 6, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-04 09:41:11', 'en revision', 'soporte_6a22d89113bd27.32669537_1780668561.pdf', 'pdf', '2026-06-05 10:09:21', 'pendiente', 'pendiente', 'pendiente'),
(661, 2629, 6, 5, 1, 1, NULL, NULL, NULL, NULL, '2026-06-04 09:41:11', 'en revision', 'soporte_6a22d89113bd27.32669537_1780668561.pdf', 'pdf', '2026-06-05 10:09:21', 'pendiente', 'pendiente', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_trimestres`
--

CREATE TABLE `notas_trimestres` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_materia` int NOT NULL,
  `id_periodo` int NOT NULL,
  `id_docente` int DEFAULT NULL,
  `trimestre_num` int NOT NULL COMMENT '1, 2 o 3',
  `nota` decimal(5,2) DEFAULT NULL,
  `estado` enum('pendiente','en_revision','aprobada','rechazada') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_admin_aprobador` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `notas_trimestres`
--

INSERT INTO `notas_trimestres` (`id`, `id_usuario`, `id_materia`, `id_periodo`, `id_docente`, `trimestre_num`, `nota`, `estado`, `fecha_registro`, `id_admin_aprobador`) VALUES
(1, 2628, 9, 5, 4, 1, 15.00, 'aprobada', '2026-05-25 12:39:41', 2),
(2, 2628, 9, 5, 4, 2, 12.00, 'aprobada', '2026-05-25 12:39:41', 2),
(3, 2628, 9, 5, 4, 3, 20.00, 'aprobada', '2026-05-25 12:39:41', 2),
(4, 2630, 9, 5, 4, 1, 10.00, 'aprobada', '2026-05-26 12:40:06', 2),
(5, 2630, 9, 5, 4, 2, 12.00, 'aprobada', '2026-05-26 12:40:06', 2),
(6, 2630, 9, 5, 4, 3, 10.00, 'aprobada', '2026-05-26 12:40:06', 2),
(7, 2632, 9, 5, 4, 1, 16.00, 'aprobada', '2026-05-25 12:39:41', 2),
(8, 2632, 9, 5, 4, 2, 7.00, 'aprobada', '2026-05-25 12:39:41', 2),
(9, 2632, 9, 5, 4, 3, 18.00, 'aprobada', '2026-05-25 12:39:41', 2),
(10, 2629, 9, 5, 4, 1, 20.00, 'aprobada', '2026-05-25 12:39:41', 2),
(11, 2629, 9, 5, 4, 2, 7.00, 'aprobada', '2026-05-25 12:39:41', 2),
(12, 2629, 9, 5, 4, 3, 15.00, 'aprobada', '2026-05-25 12:39:41', 2),
(13, 2633, 5, 5, 1, 1, 14.00, 'aprobada', '2026-05-29 13:38:07', 2),
(14, 2628, 5, 5, 1, 1, 4.00, 'aprobada', '2026-05-29 13:38:07', 2),
(15, 2630, 5, 5, 1, 1, 20.00, 'aprobada', '2026-06-02 09:28:22', 2),
(16, 2632, 5, 5, 1, 1, 17.00, 'aprobada', '2026-06-02 09:28:22', 2),
(17, 2629, 5, 5, 1, 1, 10.00, 'aprobada', '2026-06-02 09:28:22', 2),
(18, 2633, 5, 5, 1, 2, 10.00, 'aprobada', '2026-06-03 11:13:11', 2),
(19, 2628, 5, 5, 1, 2, 15.00, 'aprobada', '2026-06-03 11:13:11', 2),
(20, 2630, 5, 5, 1, 2, 19.00, 'aprobada', '2026-06-03 11:13:11', 2),
(21, 2632, 5, 5, 1, 2, 14.00, 'aprobada', '2026-06-03 11:13:11', 2),
(22, 2629, 5, 5, 1, 2, 12.00, 'aprobada', '2026-06-03 11:13:11', 2),
(23, 2633, 5, 5, 1, 3, 20.00, 'aprobada', '2026-06-03 11:35:23', 2),
(24, 2628, 5, 5, 1, 3, 15.00, 'aprobada', '2026-06-03 11:35:23', 2),
(25, 2630, 5, 5, 1, 3, 9.00, 'aprobada', '2026-06-03 11:35:23', 2),
(26, 2632, 5, 5, 1, 3, 14.00, 'aprobada', '2026-06-03 11:35:23', 2),
(27, 2629, 5, 5, 1, 3, 6.00, 'aprobada', '2026-06-03 11:35:23', 2),
(28, 2633, 9, 5, 1, 1, 18.00, 'aprobada', '2026-06-18 10:05:42', 2),
(29, 2633, 9, 5, 1, 2, 18.00, 'aprobada', '2026-06-18 10:05:42', 2),
(30, 2633, 9, 5, 1, 3, 10.00, 'aprobada', '2026-06-18 10:05:42', 2),
(31, 2633, 6, 5, 1, 1, 16.00, 'aprobada', '2026-06-04 09:41:11', 2),
(32, 2633, 6, 5, 1, 2, 15.00, 'aprobada', '2026-06-04 09:53:43', 2),
(33, 2633, 6, 5, 1, 3, 20.00, 'aprobada', '2026-06-04 10:36:28', 2),
(34, 2628, 6, 5, 1, 1, 13.00, 'aprobada', '2026-06-04 10:40:04', 2),
(35, 2628, 6, 5, 1, 2, 8.00, 'aprobada', '2026-06-04 10:44:52', 2),
(36, 2628, 6, 5, 1, 3, 6.00, 'aprobada', '2026-06-04 11:17:27', 2),
(37, 2630, 6, 5, 1, 1, 20.00, 'aprobada', '2026-06-04 11:22:55', 2),
(38, 2630, 6, 5, 1, 2, 14.00, 'aprobada', '2026-06-04 11:40:12', 2),
(39, 2630, 6, 5, 1, 3, 15.00, 'aprobada', '2026-06-05 10:09:21', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int NOT NULL,
  `estudiante_id` int DEFAULT NULL,
  `tipo_pago` enum('inscripcion','reincorporacion_estudio_expediente','cambio_programa','cambio_sede','inscripcion_pasantias_practica_profesional','expedicion_constancia_certificada_notas','expedicion_constancia_simple_notas','expedicion_constancia_buena_conducta','expedicion_constancia_culminacion_academica','expedicion_constancia_estudios','expedicion_constancia_inscripcion','expedicion_constancia_servicio_comunitario','carnet_estudiantil','uniforme_franela_estudiantil','certificado_titulo','autenticacion_titulo','pensum_estudios_certificados','programas_analiticos_vigencia_programas','expedicion_constancia_modalidad_estudios','certificacion_acta_grado','grado_titulo_medalla_notas_certificadas_ubicacion_rango_buena_conducta_servicio_comunitario','derecho_grado','certificacion_saberes','examen_suficiencia','examen_extraordinario','cursos','talleres','diplomado','especializacion','maestria','otro') COLLATE utf32_spanish2_ci NOT NULL,
  `otro_concepto` varchar(100) COLLATE utf32_spanish2_ci DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `banco_id` int DEFAULT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `observaciones` text COLLATE utf32_spanish2_ci,
  `registrado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `estudiante_id`, `tipo_pago`, `otro_concepto`, `monto`, `banco_id`, `fecha_pago`, `observaciones`, `registrado_por`) VALUES
(28, 5, 'cursos', '', 200.00, NULL, '2025-09-17 13:28:42', '00051215', 2),
(29, 5, 'inscripcion', '', 1000.00, NULL, '2025-09-22 09:35:33', '0000524186', 2),
(30, 5, 'inscripcion', '', 1000.00, NULL, '2025-09-23 12:16:41', '0004521', 2),
(31, 5, 'inscripcion', '', 1000.00, NULL, '2025-10-06 13:08:31', '0004123', 2),
(32, 5, 'cambio_programa', '', 500.00, NULL, '2025-11-17 12:53:41', '26458', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` int NOT NULL,
  `id_municipio` int NOT NULL,
  `parroquia` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `id_municipio`, `parroquia`) VALUES
(1, 1, 'Alto Orinoco'),
(2, 1, 'Huachamacare Acanaña'),
(3, 1, 'Marawaka Toky Shamanaña'),
(4, 1, 'Mavaka Mavaka'),
(5, 1, 'Sierra Parima Parimabé'),
(6, 2, 'Ucata Laja Lisa'),
(7, 2, 'Yapacana Macuruco'),
(8, 2, 'Caname Guarinuma'),
(9, 3, 'Fernando Girón Tovar'),
(10, 3, 'Luis Alberto Gómez'),
(11, 3, 'Pahueña Limón de Parhueña'),
(12, 3, 'Platanillal Platanillal'),
(13, 4, 'Samariapo'),
(14, 4, 'Sipapo'),
(15, 4, 'Munduapo'),
(16, 4, 'Guayapo'),
(17, 5, 'Alto Ventuari'),
(18, 5, 'Medio Ventuari'),
(19, 5, 'Bajo Ventuari'),
(20, 6, 'Victorino'),
(21, 6, 'Comunidad'),
(22, 7, 'Casiquiare'),
(23, 7, 'Cocuy'),
(24, 7, 'San Carlos de Río Negro'),
(25, 7, 'Solano'),
(26, 8, 'Anaco'),
(27, 8, 'San Joaquín'),
(28, 9, 'Cachipo'),
(29, 9, 'Aragua de Barcelona'),
(30, 11, 'Lechería'),
(31, 11, 'El Morro'),
(32, 12, 'Puerto Píritu'),
(33, 12, 'San Miguel'),
(34, 12, 'Sucre'),
(35, 13, 'Valle de Guanape'),
(36, 13, 'Santa Bárbara'),
(37, 14, 'El Chaparro'),
(38, 14, 'Tomás Alfaro'),
(39, 14, 'Calatrava'),
(40, 15, 'Guanta'),
(41, 15, 'Chorrerón'),
(42, 16, 'Mamo'),
(43, 16, 'Soledad'),
(44, 17, 'Mapire'),
(45, 17, 'Piar'),
(46, 17, 'Santa Clara'),
(47, 17, 'San Diego de Cabrutica'),
(48, 17, 'Uverito'),
(49, 17, 'Zuata'),
(50, 18, 'Puerto La Cruz'),
(51, 18, 'Pozuelos'),
(52, 19, 'Onoto'),
(53, 19, 'San Pablo'),
(54, 20, 'San Mateo'),
(55, 20, 'El Carito'),
(56, 20, 'Santa Inés'),
(57, 20, 'La Romereña'),
(58, 21, 'Atapirire'),
(59, 21, 'Boca del Pao'),
(60, 21, 'El Pao'),
(61, 21, 'Pariaguán'),
(62, 22, 'Cantaura'),
(63, 22, 'Libertador'),
(64, 22, 'Santa Rosa'),
(65, 22, 'Urica'),
(66, 23, 'Píritu'),
(67, 23, 'San Francisco'),
(68, 24, 'San José de Guanipa'),
(69, 25, 'Boca de Uchire'),
(70, 25, 'Boca de Chávez'),
(71, 26, 'Pueblo Nuevo'),
(72, 26, 'Santa Ana'),
(73, 27, 'Bergantín'),
(74, 27, 'Caigua'),
(75, 27, 'El Carmen'),
(76, 27, 'El Pilar'),
(77, 27, 'Naricual'),
(78, 27, 'San Crsitóbal'),
(79, 28, 'Edmundo Barrios'),
(80, 28, 'Miguel Otero Silva'),
(81, 29, 'Achaguas'),
(82, 29, 'Apurito'),
(83, 29, 'El Yagual'),
(84, 29, 'Guachara'),
(85, 29, 'Mucuritas'),
(86, 29, 'Queseras del medio'),
(87, 30, 'Biruaca'),
(88, 31, 'Bruzual'),
(89, 31, 'Mantecal'),
(90, 31, 'Quintero'),
(91, 31, 'Rincón Hondo'),
(92, 31, 'San Vicente'),
(93, 32, 'Guasdualito'),
(94, 32, 'Aramendi'),
(95, 32, 'El Amparo'),
(96, 32, 'San Camilo'),
(97, 32, 'Urdaneta'),
(98, 33, 'San Juan de Payara'),
(99, 33, 'Codazzi'),
(100, 33, 'Cunaviche'),
(101, 34, 'Elorza'),
(102, 34, 'La Trinidad'),
(103, 35, 'San Fernando'),
(104, 35, 'El Recreo'),
(105, 35, 'Peñalver'),
(106, 35, 'San Rafael de Atamaica'),
(107, 36, 'Pedro José Ovalles'),
(108, 36, 'Joaquín Crespo'),
(109, 36, 'José Casanova Godoy'),
(110, 36, 'Madre María de San José'),
(111, 36, 'Andrés Eloy Blanco'),
(112, 36, 'Los Tacarigua'),
(113, 36, 'Las Delicias'),
(114, 36, 'Choroní'),
(115, 37, 'Bolívar'),
(116, 38, 'Camatagua'),
(117, 38, 'Carmen de Cura'),
(118, 39, 'Santa Rita'),
(119, 39, 'Francisco de Miranda'),
(120, 39, 'Moseñor Feliciano González'),
(121, 40, 'Santa Cruz'),
(122, 41, 'José Félix Ribas'),
(123, 41, 'Castor Nieves Ríos'),
(124, 41, 'Las Guacamayas'),
(125, 41, 'Pao de Zárate'),
(126, 41, 'Zuata'),
(127, 42, 'José Rafael Revenga'),
(128, 43, 'Palo Negro'),
(129, 43, 'San Martín de Porres'),
(130, 44, 'El Limón'),
(131, 44, 'Caña de Azúcar'),
(132, 45, 'Ocumare de la Costa'),
(133, 46, 'San Casimiro'),
(134, 46, 'Güiripa'),
(135, 46, 'Ollas de Caramacate'),
(136, 46, 'Valle Morín'),
(137, 47, 'San Sebastían'),
(138, 48, 'Turmero'),
(139, 48, 'Arevalo Aponte'),
(140, 48, 'Chuao'),
(141, 48, 'Samán de Güere'),
(142, 48, 'Alfredo Pacheco Miranda'),
(143, 49, 'Santos Michelena'),
(144, 49, 'Tiara'),
(145, 50, 'Cagua'),
(146, 50, 'Bella Vista'),
(147, 51, 'Tovar'),
(148, 52, 'Urdaneta'),
(149, 52, 'Las Peñitas'),
(150, 52, 'San Francisco de Cara'),
(151, 52, 'Taguay'),
(152, 53, 'Zamora'),
(153, 53, 'Magdaleno'),
(154, 53, 'San Francisco de Asís'),
(155, 53, 'Valles de Tucutunemo'),
(156, 53, 'Augusto Mijares'),
(157, 54, 'Sabaneta'),
(158, 54, 'Juan Antonio Rodríguez Domínguez'),
(159, 55, 'El Cantón'),
(160, 55, 'Santa Cruz de Guacas'),
(161, 55, 'Puerto Vivas'),
(162, 56, 'Ticoporo'),
(163, 56, 'Nicolás Pulido'),
(164, 56, 'Andrés Bello'),
(165, 57, 'Arismendi'),
(166, 57, 'Guadarrama'),
(167, 57, 'La Unión'),
(168, 57, 'San Antonio'),
(169, 58, 'Barinas'),
(170, 58, 'Alberto Arvelo Larriva'),
(171, 58, 'San Silvestre'),
(172, 58, 'Santa Inés'),
(173, 58, 'Santa Lucía'),
(174, 58, 'Torumos'),
(175, 58, 'El Carmen'),
(176, 58, 'Rómulo Betancourt'),
(177, 58, 'Corazón de Jesús'),
(178, 58, 'Ramón Ignacio Méndez'),
(179, 58, 'Alto Barinas'),
(180, 58, 'Manuel Palacio Fajardo'),
(181, 58, 'Juan Antonio Rodríguez Domínguez'),
(182, 58, 'Dominga Ortiz de Páez'),
(183, 59, 'Barinitas'),
(184, 59, 'Altamira de Cáceres'),
(185, 59, 'Calderas'),
(186, 60, 'Barrancas'),
(187, 60, 'El Socorro'),
(188, 60, 'Mazparrito'),
(189, 61, 'Santa Bárbara'),
(190, 61, 'Pedro Briceño Méndez'),
(191, 61, 'Ramón Ignacio Méndez'),
(192, 61, 'José Ignacio del Pumar'),
(193, 62, 'Obispos'),
(194, 62, 'Guasimitos'),
(195, 62, 'El Real'),
(196, 62, 'La Luz'),
(197, 63, 'Ciudad Bolívia'),
(198, 63, 'José Ignacio Briceño'),
(199, 63, 'José Félix Ribas'),
(200, 63, 'Páez'),
(201, 64, 'Libertad'),
(202, 64, 'Dolores'),
(203, 64, 'Santa Rosa'),
(204, 64, 'Palacio Fajardo'),
(205, 65, 'Ciudad de Nutrias'),
(206, 65, 'El Regalo'),
(207, 65, 'Puerto Nutrias'),
(208, 65, 'Santa Catalina'),
(209, 66, 'Cachamay'),
(210, 66, 'Chirica'),
(211, 66, 'Dalla Costa'),
(212, 66, 'Once de Abril'),
(213, 66, 'Simón Bolívar'),
(214, 66, 'Unare'),
(215, 66, 'Universidad'),
(216, 66, 'Vista al Sol'),
(217, 66, 'Pozo Verde'),
(218, 66, 'Yocoima'),
(219, 66, '5 de Julio'),
(220, 67, 'Cedeño'),
(221, 67, 'Altagracia'),
(222, 67, 'Ascensión Farreras'),
(223, 67, 'Guaniamo'),
(224, 67, 'La Urbana'),
(225, 67, 'Pijiguaos'),
(226, 68, 'El Callao'),
(227, 69, 'Gran Sabana'),
(228, 69, 'Ikabarú'),
(229, 70, 'Catedral'),
(230, 70, 'Zea'),
(231, 70, 'Orinoco'),
(232, 70, 'José Antonio Páez'),
(233, 70, 'Marhuanta'),
(234, 70, 'Agua Salada'),
(235, 70, 'Vista Hermosa'),
(236, 70, 'La Sabanita'),
(237, 70, 'Panapana'),
(238, 71, 'Andrés Eloy Blanco'),
(239, 71, 'Pedro Cova'),
(240, 72, 'Raúl Leoni'),
(241, 72, 'Barceloneta'),
(242, 72, 'Santa Bárbara'),
(243, 72, 'San Francisco'),
(244, 73, 'Roscio'),
(245, 73, 'Salóm'),
(246, 74, 'Sifontes'),
(247, 74, 'Dalla Costa'),
(248, 74, 'San Isidro'),
(249, 75, 'Sucre'),
(250, 75, 'Aripao'),
(251, 75, 'Guarataro'),
(252, 75, 'Las Majadas'),
(253, 75, 'Moitaco'),
(254, 76, 'Padre Pedro Chien'),
(255, 76, 'Río Grande'),
(256, 77, 'Bejuma'),
(257, 77, 'Canoabo'),
(258, 77, 'Simón Bolívar'),
(259, 78, 'Güigüe'),
(260, 78, 'Carabobo'),
(261, 78, 'Tacarigua'),
(262, 79, 'Mariara'),
(263, 79, 'Aguas Calientes'),
(264, 80, 'Ciudad Alianza'),
(265, 80, 'Guacara'),
(266, 80, 'Yagua'),
(267, 81, 'Morón'),
(268, 81, 'Yagua'),
(269, 82, 'Tocuyito'),
(270, 82, 'Independencia'),
(271, 83, 'Los Guayos'),
(272, 84, 'Miranda'),
(273, 85, 'Montalbán'),
(274, 86, 'Naguanagua'),
(275, 87, 'Bartolomé Salóm'),
(276, 87, 'Democracia'),
(277, 87, 'Fraternidad'),
(278, 87, 'Goaigoaza'),
(279, 87, 'Juan José Flores'),
(280, 87, 'Unión'),
(281, 87, 'Borburata'),
(282, 87, 'Patanemo'),
(283, 88, 'San Diego'),
(284, 89, 'San Joaquín'),
(285, 90, 'Candelaria'),
(286, 90, 'Catedral'),
(287, 90, 'El Socorro'),
(288, 90, 'Miguel Peña'),
(289, 90, 'Rafael Urdaneta'),
(290, 90, 'San Blas'),
(291, 90, 'San José'),
(292, 90, 'Santa Rosa'),
(293, 90, 'Negro Primero'),
(294, 91, 'Cojedes'),
(295, 91, 'Juan de Mata Suárez'),
(296, 92, 'Tinaquillo'),
(297, 93, 'El Baúl'),
(298, 93, 'Sucre'),
(299, 94, 'La Aguadita'),
(300, 94, 'Macapo'),
(301, 95, 'El Pao'),
(302, 96, 'El Amparo'),
(303, 96, 'Libertad de Cojedes'),
(304, 97, 'Rómulo Gallegos'),
(305, 98, 'San Carlos de Austria'),
(306, 98, 'Juan Ángel Bravo'),
(307, 98, 'Manuel Manrique'),
(308, 99, 'General en Jefe José Laurencio Silva'),
(309, 100, 'Curiapo'),
(310, 100, 'Almirante Luis Brión'),
(311, 100, 'Francisco Aniceto Lugo'),
(312, 100, 'Manuel Renaud'),
(313, 100, 'Padre Barral'),
(314, 100, 'Santos de Abelgas'),
(315, 101, 'Imataca'),
(316, 101, 'Cinco de Julio'),
(317, 101, 'Juan Bautista Arismendi'),
(318, 101, 'Manuel Piar'),
(319, 101, 'Rómulo Gallegos'),
(320, 102, 'Pedernales'),
(321, 102, 'Luis Beltrán Prieto Figueroa'),
(322, 103, 'San José (Delta Amacuro)'),
(323, 103, 'José Vidal Marcano'),
(324, 103, 'Juan Millán'),
(325, 103, 'Leonardo Ruíz Pineda'),
(326, 103, 'Mariscal Antonio José de Sucre'),
(327, 103, 'Monseñor Argimiro García'),
(328, 103, 'San Rafael (Delta Amacuro)'),
(329, 103, 'Virgen del Valle'),
(330, 10, 'Clarines'),
(331, 10, 'Guanape'),
(332, 10, 'Sabana de Uchire'),
(333, 104, 'Capadare'),
(334, 104, 'La Pastora'),
(335, 104, 'Libertador'),
(336, 104, 'San Juan de los Cayos'),
(337, 105, 'Aracua'),
(338, 105, 'La Peña'),
(339, 105, 'San Luis'),
(340, 106, 'Bariro'),
(341, 106, 'Borojó'),
(342, 106, 'Capatárida'),
(343, 106, 'Guajiro'),
(344, 106, 'Seque'),
(345, 106, 'Zazárida'),
(346, 106, 'Valle de Eroa'),
(347, 107, 'Cacique Manaure'),
(348, 108, 'Norte'),
(349, 108, 'Carirubana'),
(350, 108, 'Santa Ana'),
(351, 108, 'Urbana Punta Cardón'),
(352, 109, 'La Vela de Coro'),
(353, 109, 'Acurigua'),
(354, 109, 'Guaibacoa'),
(355, 109, 'Las Calderas'),
(356, 109, 'Macoruca'),
(357, 110, 'Dabajuro'),
(358, 111, 'Agua Clara'),
(359, 111, 'Avaria'),
(360, 111, 'Pedregal'),
(361, 111, 'Piedra Grande'),
(362, 111, 'Purureche'),
(363, 112, 'Adaure'),
(364, 112, 'Adícora'),
(365, 112, 'Baraived'),
(366, 112, 'Buena Vista'),
(367, 112, 'Jadacaquiva'),
(368, 112, 'El Vínculo'),
(369, 112, 'El Hato'),
(370, 112, 'Moruy'),
(371, 112, 'Pueblo Nuevo'),
(372, 113, 'Agua Larga'),
(373, 113, 'El Paují'),
(374, 113, 'Independencia'),
(375, 113, 'Mapararí'),
(376, 114, 'Agua Linda'),
(377, 114, 'Araurima'),
(378, 114, 'Jacura'),
(379, 115, 'Tucacas'),
(380, 115, 'Boca de Aroa'),
(381, 116, 'Los Taques'),
(382, 116, 'Judibana'),
(383, 117, 'Mene de Mauroa'),
(384, 117, 'San Félix'),
(385, 117, 'Casigua'),
(386, 118, 'Guzmán Guillermo'),
(387, 118, 'Mitare'),
(388, 118, 'Río Seco'),
(389, 118, 'Sabaneta'),
(390, 118, 'San Antonio'),
(391, 118, 'San Gabriel'),
(392, 118, 'Santa Ana'),
(393, 119, 'Boca del Tocuyo'),
(394, 119, 'Chichiriviche'),
(395, 119, 'Tocuyo de la Costa'),
(396, 120, 'Palmasola'),
(397, 121, 'Cabure'),
(398, 121, 'Colina'),
(399, 121, 'Curimagua'),
(400, 122, 'San José de la Costa'),
(401, 122, 'Píritu'),
(402, 123, 'San Francisco'),
(403, 124, 'Sucre'),
(404, 124, 'Pecaya'),
(405, 125, 'Tocópero'),
(406, 126, 'El Charal'),
(407, 126, 'Las Vegas del Tuy'),
(408, 126, 'Santa Cruz de Bucaral'),
(409, 127, 'Bruzual'),
(410, 127, 'Urumaco'),
(411, 128, 'Puerto Cumarebo'),
(412, 128, 'La Ciénaga'),
(413, 128, 'La Soledad'),
(414, 128, 'Pueblo Cumarebo'),
(415, 128, 'Zazárida'),
(416, 113, 'Churuguara'),
(417, 129, 'Camaguán'),
(418, 129, 'Puerto Miranda'),
(419, 129, 'Uverito'),
(420, 130, 'Chaguaramas'),
(421, 131, 'El Socorro'),
(422, 132, 'Tucupido'),
(423, 132, 'San Rafael de Laya'),
(424, 133, 'Altagracia de Orituco'),
(425, 133, 'San Rafael de Orituco'),
(426, 133, 'San Francisco Javier de Lezama'),
(427, 133, 'Paso Real de Macaira'),
(428, 133, 'Carlos Soublette'),
(429, 133, 'San Francisco de Macaira'),
(430, 133, 'Libertad de Orituco'),
(431, 134, 'Cantaclaro'),
(432, 134, 'San Juan de los Morros'),
(433, 134, 'Parapara'),
(434, 135, 'El Sombrero'),
(435, 135, 'Sosa'),
(436, 136, 'Las Mercedes'),
(437, 136, 'Cabruta'),
(438, 136, 'Santa Rita de Manapire'),
(439, 137, 'Valle de la Pascua'),
(440, 137, 'Espino'),
(441, 138, 'San José de Unare'),
(442, 138, 'Zaraza'),
(443, 139, 'San José de Tiznados'),
(444, 139, 'San Francisco de Tiznados'),
(445, 139, 'San Lorenzo de Tiznados'),
(446, 139, 'Ortiz'),
(447, 140, 'Guayabal'),
(448, 140, 'Cazorla'),
(449, 141, 'San José de Guaribe'),
(450, 141, 'Uveral'),
(451, 142, 'Santa María de Ipire'),
(452, 142, 'Altamira'),
(453, 143, 'El Calvario'),
(454, 143, 'El Rastro'),
(455, 143, 'Guardatinajas'),
(456, 143, 'Capital Urbana Calabozo'),
(457, 144, 'Quebrada Honda de Guache'),
(458, 144, 'Pío Tamayo'),
(459, 144, 'Yacambú'),
(460, 145, 'Fréitez'),
(461, 145, 'José María Blanco'),
(462, 146, 'Catedral'),
(463, 146, 'Concepción'),
(464, 146, 'El Cují'),
(465, 146, 'Juan de Villegas'),
(466, 146, 'Santa Rosa'),
(467, 146, 'Tamaca'),
(468, 146, 'Unión'),
(469, 146, 'Aguedo Felipe Alvarado'),
(470, 146, 'Buena Vista'),
(471, 146, 'Juárez'),
(472, 147, 'Juan Bautista Rodríguez'),
(473, 147, 'Cuara'),
(474, 147, 'Diego de Lozada'),
(475, 147, 'Paraíso de San José'),
(476, 147, 'San Miguel'),
(477, 147, 'Tintorero'),
(478, 147, 'José Bernardo Dorante'),
(479, 147, 'Coronel Mariano Peraza '),
(480, 148, 'Bolívar'),
(481, 148, 'Anzoátegui'),
(482, 148, 'Guarico'),
(483, 148, 'Hilario Luna y Luna'),
(484, 148, 'Humocaro Alto'),
(485, 148, 'Humocaro Bajo'),
(486, 148, 'La Candelaria'),
(487, 148, 'Morán'),
(488, 149, 'Cabudare'),
(489, 149, 'José Gregorio Bastidas'),
(490, 149, 'Agua Viva'),
(491, 150, 'Sarare'),
(492, 150, 'Buría'),
(493, 150, 'Gustavo Vegas León'),
(494, 151, 'Trinidad Samuel'),
(495, 151, 'Antonio Díaz'),
(496, 151, 'Camacaro'),
(497, 151, 'Castañeda'),
(498, 151, 'Cecilio Zubillaga'),
(499, 151, 'Chiquinquirá'),
(500, 151, 'El Blanco'),
(501, 151, 'Espinoza de los Monteros'),
(502, 151, 'Lara'),
(503, 151, 'Las Mercedes'),
(504, 151, 'Manuel Morillo'),
(505, 151, 'Montaña Verde'),
(506, 151, 'Montes de Oca'),
(507, 151, 'Torres'),
(508, 151, 'Heriberto Arroyo'),
(509, 151, 'Reyes Vargas'),
(510, 151, 'Altagracia'),
(511, 152, 'Siquisique'),
(512, 152, 'Moroturo'),
(513, 152, 'San Miguel'),
(514, 152, 'Xaguas'),
(515, 179, 'Presidente Betancourt'),
(516, 179, 'Presidente Páez'),
(517, 179, 'Presidente Rómulo Gallegos'),
(518, 179, 'Gabriel Picón González'),
(519, 179, 'Héctor Amable Mora'),
(520, 179, 'José Nucete Sardi'),
(521, 179, 'Pulido Méndez'),
(522, 180, 'La Azulita'),
(523, 181, 'Santa Cruz de Mora'),
(524, 181, 'Mesa Bolívar'),
(525, 181, 'Mesa de Las Palmas'),
(526, 182, 'Aricagua'),
(527, 182, 'San Antonio'),
(528, 183, 'Canagua'),
(529, 183, 'Capurí'),
(530, 183, 'Chacantá'),
(531, 183, 'El Molino'),
(532, 183, 'Guaimaral'),
(533, 183, 'Mucutuy'),
(534, 183, 'Mucuchachí'),
(535, 184, 'Fernández Peña'),
(536, 184, 'Matriz'),
(537, 184, 'Montalbán'),
(538, 184, 'Acequias'),
(539, 184, 'Jají'),
(540, 184, 'La Mesa'),
(541, 184, 'San José del Sur'),
(542, 185, 'Tucaní'),
(543, 185, 'Florencio Ramírez'),
(544, 186, 'Santo Domingo'),
(545, 186, 'Las Piedras'),
(546, 187, 'Guaraque'),
(547, 187, 'Mesa de Quintero'),
(548, 187, 'Río Negro'),
(549, 188, 'Arapuey'),
(550, 188, 'Palmira'),
(551, 189, 'San Cristóbal de Torondoy'),
(552, 189, 'Torondoy'),
(553, 190, 'Antonio Spinetti Dini'),
(554, 190, 'Arias'),
(555, 190, 'Caracciolo Parra Pérez'),
(556, 190, 'Domingo Peña'),
(557, 190, 'El Llano'),
(558, 190, 'Gonzalo Picón Febres'),
(559, 190, 'Jacinto Plaza'),
(560, 190, 'Juan Rodríguez Suárez'),
(561, 190, 'Lasso de la Vega'),
(562, 190, 'Mariano Picón Salas'),
(563, 190, 'Milla'),
(564, 190, 'Osuna Rodríguez'),
(565, 190, 'Sagrario'),
(566, 190, 'El Morro'),
(567, 190, 'Los Nevados'),
(568, 191, 'Andrés Eloy Blanco'),
(569, 191, 'La Venta'),
(570, 191, 'Piñango'),
(571, 191, 'Timotes'),
(572, 192, 'Eloy Paredes'),
(573, 192, 'San Rafael de Alcázar'),
(574, 192, 'Santa Elena de Arenales'),
(575, 193, 'Santa María de Caparo'),
(576, 194, 'Pueblo Llano'),
(577, 195, 'Cacute'),
(578, 195, 'La Toma'),
(579, 195, 'Mucuchíes'),
(580, 195, 'Mucurubá'),
(581, 195, 'San Rafael'),
(582, 196, 'Gerónimo Maldonado'),
(583, 196, 'Bailadores'),
(584, 197, 'Tabay'),
(585, 198, 'Chiguará'),
(586, 198, 'Estánquez'),
(587, 198, 'Lagunillas'),
(588, 198, 'La Trampa'),
(589, 198, 'Pueblo Nuevo del Sur'),
(590, 198, 'San Juan'),
(591, 199, 'El Amparo'),
(592, 199, 'El Llano'),
(593, 199, 'San Francisco'),
(594, 199, 'Tovar'),
(595, 200, 'Independencia'),
(596, 200, 'María de la Concepción Palacios Blanco'),
(597, 200, 'Nueva Bolivia'),
(598, 200, 'Santa Apolonia'),
(599, 201, 'Caño El Tigre'),
(600, 201, 'Zea'),
(601, 223, 'Aragüita'),
(602, 223, 'Arévalo González'),
(603, 223, 'Capaya'),
(604, 223, 'Caucagua'),
(605, 223, 'Panaquire'),
(606, 223, 'Ribas'),
(607, 223, 'El Café'),
(608, 223, 'Marizapa'),
(609, 224, 'Cumbo'),
(610, 224, 'San José de Barlovento'),
(611, 225, 'El Cafetal'),
(612, 225, 'Las Minas'),
(613, 225, 'Nuestra Señora del Rosario'),
(614, 226, 'Higuerote'),
(615, 226, 'Curiepe'),
(616, 226, 'Tacarigua de Brión'),
(617, 227, 'Mamporal'),
(618, 228, 'Carrizal'),
(619, 229, 'Chacao'),
(620, 230, 'Charallave'),
(621, 230, 'Las Brisas'),
(622, 231, 'El Hatillo'),
(623, 232, 'Altagracia de la Montaña'),
(624, 232, 'Cecilio Acosta'),
(625, 232, 'Los Teques'),
(626, 232, 'El Jarillo'),
(627, 232, 'San Pedro'),
(628, 232, 'Tácata'),
(629, 232, 'Paracotos'),
(630, 233, 'Cartanal'),
(631, 233, 'Santa Teresa del Tuy'),
(632, 234, 'La Democracia'),
(633, 234, 'Ocumare del Tuy'),
(634, 234, 'Santa Bárbara'),
(635, 235, 'San Antonio de los Altos'),
(636, 236, 'Río Chico'),
(637, 236, 'El Guapo'),
(638, 236, 'Tacarigua de la Laguna'),
(639, 236, 'Paparo'),
(640, 236, 'San Fernando del Guapo'),
(641, 237, 'Santa Lucía del Tuy'),
(642, 238, 'Cúpira'),
(643, 238, 'Machurucuto'),
(644, 239, 'Guarenas'),
(645, 240, 'San Antonio de Yare'),
(646, 240, 'San Francisco de Yare'),
(647, 241, 'Leoncio Martínez'),
(648, 241, 'Petare'),
(649, 241, 'Caucagüita'),
(650, 241, 'Filas de Mariche'),
(651, 241, 'La Dolorita'),
(652, 242, 'Cúa'),
(653, 242, 'Nueva Cúa'),
(654, 243, 'Guatire'),
(655, 243, 'Bolívar'),
(656, 258, 'San Antonio de Maturín'),
(657, 258, 'San Francisco de Maturín'),
(658, 259, 'Aguasay'),
(659, 260, 'Caripito'),
(660, 261, 'El Guácharo'),
(661, 261, 'La Guanota'),
(662, 261, 'Sabana de Piedra'),
(663, 261, 'San Agustín'),
(664, 261, 'Teresen'),
(665, 261, 'Caripe'),
(666, 262, 'Areo'),
(667, 262, 'Capital Cedeño'),
(668, 262, 'San Félix de Cantalicio'),
(669, 262, 'Viento Fresco'),
(670, 263, 'El Tejero'),
(671, 263, 'Punta de Mata'),
(672, 264, 'Chaguaramas'),
(673, 264, 'Las Alhuacas'),
(674, 264, 'Tabasca'),
(675, 264, 'Temblador'),
(676, 265, 'Alto de los Godos'),
(677, 265, 'Boquerón'),
(678, 265, 'Las Cocuizas'),
(679, 265, 'La Cruz'),
(680, 265, 'San Simón'),
(681, 265, 'El Corozo'),
(682, 265, 'El Furrial'),
(683, 265, 'Jusepín'),
(684, 265, 'La Pica'),
(685, 265, 'San Vicente'),
(686, 266, 'Aparicio'),
(687, 266, 'Aragua de Maturín'),
(688, 266, 'Chaguamal'),
(689, 266, 'El Pinto'),
(690, 266, 'Guanaguana'),
(691, 266, 'La Toscana'),
(692, 266, 'Taguaya'),
(693, 267, 'Cachipo'),
(694, 267, 'Quiriquire'),
(695, 268, 'Santa Bárbara'),
(696, 269, 'Barrancas'),
(697, 269, 'Los Barrancos de Fajardo'),
(698, 270, 'Uracoa'),
(699, 271, 'Antolín del Campo'),
(700, 272, 'Arismendi'),
(701, 273, 'García'),
(702, 273, 'Francisco Fajardo'),
(703, 274, 'Bolívar'),
(704, 274, 'Guevara'),
(705, 274, 'Matasiete'),
(706, 274, 'Santa Ana'),
(707, 274, 'Sucre'),
(708, 275, 'Aguirre'),
(709, 275, 'Maneiro'),
(710, 276, 'Adrián'),
(711, 276, 'Juan Griego'),
(712, 276, 'Yaguaraparo'),
(713, 277, 'Porlamar'),
(714, 278, 'San Francisco de Macanao'),
(715, 278, 'Boca de Río'),
(716, 279, 'Tubores'),
(717, 279, 'Los Baleales'),
(718, 280, 'Vicente Fuentes'),
(719, 280, 'Villalba'),
(720, 281, 'San Juan Bautista'),
(721, 281, 'Zabala'),
(722, 283, 'Capital Araure'),
(723, 283, 'Río Acarigua'),
(724, 284, 'Capital Esteller'),
(725, 284, 'Uveral'),
(726, 285, 'Guanare'),
(727, 285, 'Córdoba'),
(728, 285, 'San José de la Montaña'),
(729, 285, 'San Juan de Guanaguanare'),
(730, 285, 'Virgen de la Coromoto'),
(731, 286, 'Guanarito'),
(732, 286, 'Trinidad de la Capilla'),
(733, 286, 'Divina Pastora'),
(734, 287, 'Monseñor José Vicente de Unda'),
(735, 287, 'Peña Blanca'),
(736, 288, 'Capital Ospino'),
(737, 288, 'Aparición'),
(738, 288, 'La Estación'),
(739, 289, 'Páez'),
(740, 289, 'Payara'),
(741, 289, 'Pimpinela'),
(742, 289, 'Ramón Peraza'),
(743, 290, 'Papelón'),
(744, 290, 'Caño Delgadito'),
(745, 291, 'San Genaro de Boconoito'),
(746, 291, 'Antolín Tovar'),
(747, 292, 'San Rafael de Onoto'),
(748, 292, 'Santa Fe'),
(749, 292, 'Thermo Morles'),
(750, 293, 'Santa Rosalía'),
(751, 293, 'Florida'),
(752, 294, 'Sucre'),
(753, 294, 'Concepción'),
(754, 294, 'San Rafael de Palo Alzado'),
(755, 294, 'Uvencio Antonio Velásquez'),
(756, 294, 'San José de Saguaz'),
(757, 294, 'Villa Rosa'),
(758, 295, 'Turén'),
(759, 295, 'Canelones'),
(760, 295, 'Santa Cruz'),
(761, 295, 'San Isidro Labrador'),
(762, 296, 'Mariño'),
(763, 296, 'Rómulo Gallegos'),
(764, 297, 'San José de Aerocuar'),
(765, 297, 'Tavera Acosta'),
(766, 298, 'Río Caribe'),
(767, 298, 'Antonio José de Sucre'),
(768, 298, 'El Morro de Puerto Santo'),
(769, 298, 'Puerto Santo'),
(770, 298, 'San Juan de las Galdonas'),
(771, 299, 'El Pilar'),
(772, 299, 'El Rincón'),
(773, 299, 'General Francisco Antonio Váquez'),
(774, 299, 'Guaraúnos'),
(775, 299, 'Tunapuicito'),
(776, 299, 'Unión'),
(777, 300, 'Santa Catalina'),
(778, 300, 'Santa Rosa'),
(779, 300, 'Santa Teresa'),
(780, 300, 'Bolívar'),
(781, 300, 'Maracapana'),
(782, 302, 'Libertad'),
(783, 302, 'El Paujil'),
(784, 302, 'Yaguaraparo'),
(785, 303, 'Cruz Salmerón Acosta'),
(786, 303, 'Chacopata'),
(787, 303, 'Manicuare'),
(788, 304, 'Tunapuy'),
(789, 304, 'Campo Elías'),
(790, 305, 'Irapa'),
(791, 305, 'Campo Claro'),
(792, 305, 'Maraval'),
(793, 305, 'San Antonio de Irapa'),
(794, 305, 'Soro'),
(795, 306, 'Mejía'),
(796, 307, 'Cumanacoa'),
(797, 307, 'Arenas'),
(798, 307, 'Aricagua'),
(799, 307, 'Cogollar'),
(800, 307, 'San Fernando'),
(801, 307, 'San Lorenzo'),
(802, 308, 'Villa Frontado (Muelle de Cariaco)'),
(803, 308, 'Catuaro'),
(804, 308, 'Rendón'),
(805, 308, 'San Cruz'),
(806, 308, 'Santa María'),
(807, 309, 'Altagracia'),
(808, 309, 'Santa Inés'),
(809, 309, 'Valentín Valiente'),
(810, 309, 'Ayacucho'),
(811, 309, 'San Juan'),
(812, 309, 'Raúl Leoni'),
(813, 309, 'Gran Mariscal'),
(814, 310, 'Cristóbal Colón'),
(815, 310, 'Bideau'),
(816, 310, 'Punta de Piedras'),
(817, 310, 'Güiria'),
(818, 341, 'Andrés Bello'),
(819, 342, 'Antonio Rómulo Costa'),
(820, 343, 'Ayacucho'),
(821, 343, 'Rivas Berti'),
(822, 343, 'San Pedro del Río'),
(823, 344, 'Bolívar'),
(824, 344, 'Palotal'),
(825, 344, 'General Juan Vicente Gómez'),
(826, 344, 'Isaías Medina Angarita'),
(827, 345, 'Cárdenas'),
(828, 345, 'Amenodoro Ángel Lamus'),
(829, 345, 'La Florida'),
(830, 346, 'Córdoba'),
(831, 347, 'Fernández Feo'),
(832, 347, 'Alberto Adriani'),
(833, 347, 'Santo Domingo'),
(834, 348, 'Francisco de Miranda'),
(835, 349, 'García de Hevia'),
(836, 349, 'Boca de Grita'),
(837, 349, 'José Antonio Páez'),
(838, 350, 'Guásimos'),
(839, 351, 'Independencia'),
(840, 351, 'Juan Germán Roscio'),
(841, 351, 'Román Cárdenas'),
(842, 352, 'Jáuregui'),
(843, 352, 'Emilio Constantino Guerrero'),
(844, 352, 'Monseñor Miguel Antonio Salas'),
(845, 353, 'José María Vargas'),
(846, 354, 'Junín'),
(847, 354, 'La Petrólea'),
(848, 354, 'Quinimarí'),
(849, 354, 'Bramón'),
(850, 355, 'Libertad'),
(851, 355, 'Cipriano Castro'),
(852, 355, 'Manuel Felipe Rugeles'),
(853, 356, 'Libertador'),
(854, 356, 'Doradas'),
(855, 356, 'Emeterio Ochoa'),
(856, 356, 'San Joaquín de Navay'),
(857, 357, 'Lobatera'),
(858, 357, 'Constitución'),
(859, 358, 'Michelena'),
(860, 359, 'Panamericano'),
(861, 359, 'La Palmita'),
(862, 360, 'Pedro María Ureña'),
(863, 360, 'Nueva Arcadia'),
(864, 361, 'Delicias'),
(865, 361, 'Pecaya'),
(866, 362, 'Samuel Darío Maldonado'),
(867, 362, 'Boconó'),
(868, 362, 'Hernández'),
(869, 363, 'La Concordia'),
(870, 363, 'San Juan Bautista'),
(871, 363, 'Pedro María Morantes'),
(872, 363, 'San Sebastián'),
(873, 363, 'Dr. Francisco Romero Lobo'),
(874, 364, 'Seboruco'),
(875, 365, 'Simón Rodríguez'),
(876, 366, 'Sucre'),
(877, 366, 'Eleazar López Contreras'),
(878, 366, 'San Pablo'),
(879, 367, 'Torbes'),
(880, 368, 'Uribante'),
(881, 368, 'Cárdenas'),
(882, 368, 'Juan Pablo Peñalosa'),
(883, 368, 'Potosí'),
(884, 369, 'San Judas Tadeo'),
(885, 370, 'Araguaney'),
(886, 370, 'El Jaguito'),
(887, 370, 'La Esperanza'),
(888, 370, 'Santa Isabel'),
(889, 371, 'Boconó'),
(890, 371, 'El Carmen'),
(891, 371, 'Mosquey'),
(892, 371, 'Ayacucho'),
(893, 371, 'Burbusay'),
(894, 371, 'General Ribas'),
(895, 371, 'Guaramacal'),
(896, 371, 'Vega de Guaramacal'),
(897, 371, 'Monseñor Jáuregui'),
(898, 371, 'Rafael Rangel'),
(899, 371, 'San Miguel'),
(900, 371, 'San José'),
(901, 372, 'Sabana Grande'),
(902, 372, 'Cheregüé'),
(903, 372, 'Granados'),
(904, 373, 'Arnoldo Gabaldón'),
(905, 373, 'Bolivia'),
(906, 373, 'Carrillo'),
(907, 373, 'Cegarra'),
(908, 373, 'Chejendé'),
(909, 373, 'Manuel Salvador Ulloa'),
(910, 373, 'San José'),
(911, 374, 'Carache'),
(912, 374, 'La Concepción'),
(913, 374, 'Cuicas'),
(914, 374, 'Panamericana'),
(915, 374, 'Santa Cruz'),
(916, 375, 'Escuque'),
(917, 375, 'La Unión'),
(918, 375, 'Santa Rita'),
(919, 375, 'Sabana Libre'),
(920, 376, 'El Socorro'),
(921, 376, 'Los Caprichos'),
(922, 376, 'Antonio José de Sucre'),
(923, 377, 'Campo Elías'),
(924, 377, 'Arnoldo Gabaldón'),
(925, 378, 'Santa Apolonia'),
(926, 378, 'El Progreso'),
(927, 378, 'La Ceiba'),
(928, 378, 'Tres de Febrero'),
(929, 379, 'El Dividive'),
(930, 379, 'Agua Santa'),
(931, 379, 'Agua Caliente'),
(932, 379, 'El Cenizo'),
(933, 379, 'Valerita'),
(934, 380, 'Monte Carmelo'),
(935, 380, 'Buena Vista'),
(936, 380, 'Santa María del Horcón'),
(937, 381, 'Motatán'),
(938, 381, 'El Baño'),
(939, 381, 'Jalisco'),
(940, 382, 'Pampán'),
(941, 382, 'Flor de Patria'),
(942, 382, 'La Paz'),
(943, 382, 'Santa Ana'),
(944, 383, 'Pampanito'),
(945, 383, 'La Concepción'),
(946, 383, 'Pampanito II'),
(947, 384, 'Betijoque'),
(948, 384, 'José Gregorio Hernández'),
(949, 384, 'La Pueblita'),
(950, 384, 'Los Cedros'),
(951, 385, 'Carvajal'),
(952, 385, 'Campo Alegre'),
(953, 385, 'Antonio Nicolás Briceño'),
(954, 385, 'José Leonardo Suárez'),
(955, 386, 'Sabana de Mendoza'),
(956, 386, 'Junín'),
(957, 386, 'Valmore Rodríguez'),
(958, 386, 'El Paraíso'),
(959, 387, 'Andrés Linares'),
(960, 387, 'Chiquinquirá'),
(961, 387, 'Cristóbal Mendoza'),
(962, 387, 'Cruz Carrillo'),
(963, 387, 'Matriz'),
(964, 387, 'Monseñor Carrillo'),
(965, 387, 'Tres Esquinas'),
(966, 388, 'Cabimbú'),
(967, 388, 'Jajó'),
(968, 388, 'La Mesa de Esnujaque'),
(969, 388, 'Santiago'),
(970, 388, 'Tuñame'),
(971, 388, 'La Quebrada'),
(972, 389, 'Juan Ignacio Montilla'),
(973, 389, 'La Beatriz'),
(974, 389, 'La Puerta'),
(975, 389, 'Mendoza del Valle de Momboy'),
(976, 389, 'Mercedes Díaz'),
(977, 389, 'San Luis'),
(978, 390, 'Caraballeda'),
(979, 390, 'Carayaca'),
(980, 390, 'Carlos Soublette'),
(981, 390, 'Caruao Chuspa'),
(982, 390, 'Catia La Mar'),
(983, 390, 'El Junko'),
(984, 390, 'La Guaira'),
(985, 390, 'Macuto'),
(986, 390, 'Maiquetía'),
(987, 390, 'Naiguatá'),
(988, 390, 'Urimare'),
(989, 391, 'Arístides Bastidas'),
(990, 392, 'Bolívar'),
(991, 407, 'Chivacoa'),
(992, 407, 'Campo Elías'),
(993, 408, 'Cocorote'),
(994, 409, 'Independencia'),
(995, 410, 'José Antonio Páez'),
(996, 411, 'La Trinidad'),
(997, 412, 'Manuel Monge'),
(998, 413, 'Salóm'),
(999, 413, 'Temerla'),
(1000, 413, 'Nirgua'),
(1001, 414, 'San Andrés'),
(1002, 414, 'Yaritagua'),
(1003, 415, 'San Javier'),
(1004, 415, 'Albarico'),
(1005, 415, 'San Felipe'),
(1006, 416, 'Sucre'),
(1007, 417, 'Urachiche'),
(1008, 418, 'El Guayabo'),
(1009, 418, 'Farriar'),
(1010, 441, 'Isla de Toas'),
(1011, 441, 'Monagas'),
(1012, 442, 'San Timoteo'),
(1013, 442, 'General Urdaneta'),
(1014, 442, 'Libertador'),
(1015, 442, 'Marcelino Briceño'),
(1016, 442, 'Pueblo Nuevo'),
(1017, 442, 'Manuel Guanipa Matos'),
(1018, 443, 'Ambrosio'),
(1019, 443, 'Carmen Herrera'),
(1020, 443, 'La Rosa'),
(1021, 443, 'Germán Ríos Linares'),
(1022, 443, 'San Benito'),
(1023, 443, 'Rómulo Betancourt'),
(1024, 443, 'Jorge Hernández'),
(1025, 443, 'Punta Gorda'),
(1026, 443, 'Arístides Calvani'),
(1027, 444, 'Encontrados'),
(1028, 444, 'Udón Pérez'),
(1029, 445, 'Moralito'),
(1030, 445, 'San Carlos del Zulia'),
(1031, 445, 'Santa Cruz del Zulia'),
(1032, 445, 'Santa Bárbara'),
(1033, 445, 'Urribarrí'),
(1034, 446, 'Carlos Quevedo'),
(1035, 446, 'Francisco Javier Pulgar'),
(1036, 446, 'Simón Rodríguez'),
(1037, 446, 'Guamo-Gavilanes'),
(1038, 448, 'La Concepción'),
(1039, 448, 'San José'),
(1040, 448, 'Mariano Parra León'),
(1041, 448, 'José Ramón Yépez'),
(1042, 449, 'Jesús María Semprún'),
(1043, 449, 'Barí'),
(1044, 450, 'Concepción'),
(1045, 450, 'Andrés Bello'),
(1046, 450, 'Chiquinquirá'),
(1047, 450, 'El Carmelo'),
(1048, 450, 'Potreritos'),
(1049, 451, 'Libertad'),
(1050, 451, 'Alonso de Ojeda'),
(1051, 451, 'Venezuela'),
(1052, 451, 'Eleazar López Contreras'),
(1053, 451, 'Campo Lara'),
(1054, 452, 'Bartolomé de las Casas'),
(1055, 452, 'Libertad'),
(1056, 452, 'Río Negro'),
(1057, 452, 'San José de Perijá'),
(1058, 453, 'San Rafael'),
(1059, 453, 'La Sierrita'),
(1060, 453, 'Las Parcelas'),
(1061, 453, 'Luis de Vicente'),
(1062, 453, 'Monseñor Marcos Sergio Godoy'),
(1063, 453, 'Ricaurte'),
(1064, 453, 'Tamare'),
(1065, 454, 'Antonio Borjas Romero'),
(1066, 454, 'Bolívar'),
(1067, 454, 'Cacique Mara'),
(1068, 454, 'Carracciolo Parra Pérez'),
(1069, 454, 'Cecilio Acosta'),
(1070, 454, 'Cristo de Aranza'),
(1071, 454, 'Coquivacoa'),
(1072, 454, 'Chiquinquirá'),
(1073, 454, 'Francisco Eugenio Bustamante'),
(1074, 454, 'Idelfonzo Vásquez'),
(1075, 454, 'Juana de Ávila'),
(1076, 454, 'Luis Hurtado Higuera'),
(1077, 454, 'Manuel Dagnino'),
(1078, 454, 'Olegario Villalobos'),
(1079, 454, 'Raúl Leoni'),
(1080, 454, 'Santa Lucía'),
(1081, 454, 'Venancio Pulgar'),
(1082, 454, 'San Isidro'),
(1083, 455, 'Altagracia'),
(1084, 455, 'Faría'),
(1085, 455, 'Ana María Campos'),
(1086, 455, 'San Antonio'),
(1087, 455, 'San José'),
(1088, 456, 'Donaldo García'),
(1089, 456, 'El Rosario'),
(1090, 456, 'Sixto Zambrano'),
(1091, 457, 'San Francisco'),
(1092, 457, 'El Bajo'),
(1093, 457, 'Domitila Flores'),
(1094, 457, 'Francisco Ochoa'),
(1095, 457, 'Los Cortijos'),
(1096, 457, 'Marcial Hernández'),
(1097, 458, 'Santa Rita'),
(1098, 458, 'El Mene'),
(1099, 458, 'Pedro Lucas Urribarrí'),
(1100, 458, 'José Cenobio Urribarrí'),
(1101, 459, 'Rafael Maria Baralt'),
(1102, 459, 'Manuel Manrique'),
(1103, 459, 'Rafael Urdaneta'),
(1104, 460, 'Bobures'),
(1105, 460, 'Gibraltar'),
(1106, 460, 'Heras'),
(1107, 460, 'Monseñor Arturo Álvarez'),
(1108, 460, 'Rómulo Gallegos'),
(1109, 460, 'El Batey'),
(1110, 461, 'Rafael Urdaneta'),
(1111, 461, 'La Victoria'),
(1112, 461, 'Raúl Cuenca'),
(1113, 447, 'Sinamaica'),
(1114, 447, 'Alta Guajira'),
(1115, 447, 'Elías Sánchez Rubio'),
(1116, 447, 'Guajira'),
(1117, 462, 'Altagracia'),
(1118, 462, 'Antímano'),
(1119, 462, 'Caricuao'),
(1120, 462, 'Catedral'),
(1121, 462, 'Coche'),
(1122, 462, 'El Junquito'),
(1123, 462, 'El Paraíso'),
(1124, 462, 'El Recreo'),
(1125, 462, 'El Valle'),
(1126, 462, 'La Candelaria'),
(1127, 462, 'La Pastora'),
(1128, 462, 'La Vega'),
(1129, 462, 'Macarao'),
(1130, 462, 'San Agustín'),
(1131, 462, 'San Bernardino'),
(1132, 462, 'San José'),
(1133, 462, 'San Juan'),
(1134, 462, 'San Pedro'),
(1135, 462, 'Santa Rosalía'),
(1136, 462, 'Santa Teresa'),
(1137, 462, 'Sucre (Catia)'),
(1138, 462, '23 de enero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expira` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT '0',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `email`, `token`, `expira`, `usado`, `creado_en`) VALUES
(1, 2, 'herrejose@gmail.com', '57e06fba78ddd75aa57e489edb75e8e0d7ed2c6ada6c43b429411bc64732cbf2', '2026-06-12 14:59:23', 1, '2026-06-12 17:59:23'),
(2, 4, 'hectorlamaquina14@gmail.com', 'bf4951621fd9b2a1b691217da80ad83d6bfea8bc9d0e87f3a6e635c7c6506453', '2026-06-12 15:01:11', 1, '2026-06-12 18:01:11'),
(3, 4, 'hectorlamaquina14@gmail.com', '7d8ae771fef933e4d04238f7af233bc50fa1bc589922a82514e65dba3fedb185', '2026-06-12 15:25:07', 1, '2026-06-12 18:25:07'),
(4, 4, 'hectorlamaquina14@gmail.com', '20ff379fe118ccb943c65ad0a41e571e68a20318b865fcfe3b087ff6fbf1cddf', '2026-06-12 15:28:36', 1, '2026-06-12 18:28:36'),
(5, 4, 'hectorlamaquina14@gmail.com', 'eea1a8d914d83e299f5bb286efc21a8358533714bf52806e9ce9416c53ab305d', '2026-06-12 15:37:54', 1, '2026-06-12 18:37:54'),
(6, 4, 'hectorlamaquina14@gmail.com', '9848f51a2fd66ad5cae07566bc8cd6716fbce9aca6d4f8e15ac48bb52893e9a3', '2026-06-16 11:01:05', 1, '2026-06-16 14:01:05'),
(7, 4, 'hectorlamaquina14@gmail.com', 'bf8fde1a051ee2901d7f4fcdad98d74216f8fc555503e9c0dda03f2813f61823', '2026-06-16 13:27:44', 0, '2026-06-16 16:27:44'),
(8, 4, 'hectorlamaquina14@gmail.com', '8dc0ab47bd26089b56554f463f0b5020aeaec8e575625bb454b6ea61a8571d2c', '2026-06-16 13:33:28', 0, '2026-06-16 16:33:28'),
(9, 4, 'hectorlamaquina14@gmail.com', '1073241b02881fbdab558e7af5612c080ac802629393d164e992b6c1f6035e82', '2026-06-16 13:34:18', 0, '2026-06-16 16:34:18'),
(10, 4, 'hectorlamaquina14@gmail.com', '43ccacf18f756e8371dd3ee774db2973b42855baa699f6e754b56f23cced5265', '2026-06-16 13:37:15', 0, '2026-06-16 16:37:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id_periodo` int NOT NULL,
  `nombre_periodo` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id_periodo`, `nombre_periodo`, `fecha_inicio`, `fecha_fin`, `activo`, `created_at`) VALUES
(1, '2024-1', '2024-01-15', '2024-04-15', 0, '2025-07-25 17:28:39'),
(2, '2025-2', '2025-07-01', '2025-12-10', 0, '2025-07-31 19:13:38'),
(3, '2027-1', '2027-06-09', '2028-07-05', 0, '2025-08-24 01:06:13'),
(4, '2026-1', '2025-12-13', '2026-02-10', 0, '2025-12-03 18:33:00'),
(5, '2026-1', '2026-01-16', '2026-03-16', 1, '2026-01-16 17:01:32'),
(6, '2026-2', '2026-06-05', '2026-09-01', 1, '2026-06-05 15:09:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preinscripcion`
--

CREATE TABLE `preinscripcion` (
  `id` int UNSIGNED NOT NULL,
  `idusuario` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tlf` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipio` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parroquia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comuna` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etnia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `casaapto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `punto_referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grupo_familiar` int DEFAULT '0',
  `acargo_usted` int DEFAULT '0',
  `fuente_ingresos` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_vivienda` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tenencia_vivienda` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enfermedad` text COLLATE utf8mb4_unicode_ci,
  `discapacidad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titulos` text COLLATE utf8mb4_unicode_ci,
  `institutos` text COLLATE utf8mb4_unicode_ci,
  `pais_titulo` text COLLATE utf8mb4_unicode_ci,
  `legalizado_titulo` text COLLATE utf8mb4_unicode_ci,
  `turno` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sede` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `potencialidades` text COLLATE utf8mb4_unicode_ci,
  `carrera` int DEFAULT NULL,
  `genero` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edo_civil` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `embarazada` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_telf_opc` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_act` datetime DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Pendiente',
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'preinscrito',
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aprobado_por` int DEFAULT NULL,
  `fecha_aprobado` datetime DEFAULT NULL,
  `rechazado_por` int DEFAULT NULL,
  `fecha_rechazo` datetime DEFAULT NULL,
  `motivo_rechazo` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `preinscripcion`
--

INSERT INTO `preinscripcion` (`id`, `idusuario`, `nombre`, `username`, `email`, `tlf`, `cel`, `direccion`, `ciudad`, `estado`, `municipio`, `parroquia`, `comuna`, `etnia`, `casaapto`, `punto_referencia`, `grupo_familiar`, `acargo_usted`, `fuente_ingresos`, `tipo_vivienda`, `tenencia_vivienda`, `enfermedad`, `discapacidad`, `titulos`, `institutos`, `pais_titulo`, `legalizado_titulo`, `turno`, `sede`, `potencialidades`, `carrera`, `genero`, `edo_civil`, `fecha_nac`, `embarazada`, `num_telf_opc`, `fecha_ingreso`, `fecha_act`, `status`, `user_type`, `foto_perfil`, `aprobado_por`, `fecha_aprobado`, `rechazado_por`, `fecha_rechazo`, `motivo_rechazo`, `created_at`, `updated_at`) VALUES
(1, 'E12345677', 'Preinscripcion Prueba de nuevo', 'E12345677', 'preinscripcionn@gmail.com', '04122222222', '04167777777', 'fdzbfdzv', '11', '2', '11', '31', '10 raices de la revolucion', '', 'No especificado', 'frente a un parque', 4, 0, '1', 'Apartamento', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'Otro', 'Sí', 'Diurno', 'Puerto Cabello', 'gwfwe', 1, 'Masculino', 'Casado', '1998-06-11', '0', '', '2026-05-10', '2026-05-10 15:19:09', 'Aprobada', 'preinscrito', 'foto_6a00da2dba0655.11250450.jpg', 2, '2026-05-10 16:10:45', NULL, NULL, NULL, '2026-05-10 15:19:09', '2026-05-10 16:10:45'),
(2, 'E12345654', 'Peru Es Clave', 'E12345654', 'preinscripc@gmail.com', '04122222222', '04167777777', 'iuohcous', '13', '2', '13', '36', '10 raices de la revolucion', '', 'No especificado', 'frente a un parque', 4, 0, '2', 'Otro', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'mbdcqiuhndxoueq', 1, 'Masculino', 'Casado', '1999-07-08', '0', '', '2026-05-10', '2026-05-10 17:51:49', 'Aprobada', 'preinscrito', 'foto_6a00fdf5dd3636.49124688.jpg', 2, '2026-05-10 17:54:20', NULL, NULL, NULL, '2026-05-10 17:51:49', '2026-05-10 17:54:20'),
(3, 'E87654567', 'Pedro Pepe Perozo Palomo', 'E87654567', 'preinscripdwfewc@gmail.com', '04122222222', '04167777777', '12345', '15', '2', '15', '41', '10 raices de la revolucion', '', 'No especificado', 'frente a un parque', 4, 0, '1', 'Casa', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'lol', 1, 'Masculino', 'Casado', '2000-07-13', '0', '', '2026-05-10', '2026-05-10 17:53:25', 'Aprobada', 'preinscrito', 'foto_6a00fe55ecd529.10523157.jpg', 2, '2026-05-10 17:54:40', NULL, NULL, NULL, '2026-05-10 17:53:25', '2026-05-10 17:54:40'),
(4, 'E46598763', 'prueba de inscripcion notas', 'E46598763', 'progral_estudios@uptpc.edu.ve', '0412555777', '0416777777', 'hjhjyj', '19', '2', '19', '52', '10 raices de la revolucion', '', 'No especificado', 'frente a una farmacia', 6, 2, '2', 'Casa', 'Familiar', '', '', 'TSU Informatica', 'U.E Freancis de Miranda', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'estresarse', 1, 'Masculino', 'Casado', '1991-07-26', '0', '', '2026-05-11', '2026-05-11 09:32:15', 'Aprobada', 'preinscrito', 'foto_6a01da5fcc9bd6.89927752.jpg', 2, '2026-05-11 14:40:53', NULL, NULL, NULL, '2026-05-11 09:32:15', '2026-05-11 14:40:53'),
(5, 'E56789456', 'La prueba de la prueba de la prueba', 'E56789456', 'proor_control_estudios@uptpc.edu.ve', '02423644304', '0416587954', 'lol', '20', '2', '20', '57', '10 raices de la revolucion', '', 'No especificado', 'yuk', 11, 5, '2', 'Casa', 'Propia', '', '', 'Bachiller', 'U.E Freancis de Miranda', 'Venezuela', '', 'Diurno', 'COEF', 'comer', 1, 'Masculino', 'Soltero', '1989-07-14', '0', '', '2026-05-11', '2026-05-11 15:29:09', 'Pendiente', 'preinscrito', 'foto_6a022e05ce1059.03872435.jpg', NULL, NULL, NULL, NULL, NULL, '2026-05-11 15:29:09', '2026-05-11 15:29:09'),
(6, 'E14725836', 'prueba de planilla', 'E14725836', 'progstudios@uptpc.edu.ve', '02423644304', '0416777777', 'ghfxfg', '84', '7', '84', '272', '10 raices de la revolucion', 'Añu', 'No especificado', 'frente a una farmacia', 5, 2, '2', 'Casa', 'Propia', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'Otro', 'Sí', 'Diurno', 'COEF', 'dormir', 1, 'Femenino', 'Casado', '1999-06-18', '1', '', '2026-05-13', '2026-05-13 10:01:35', 'Aprobada', 'preinscrito', 'foto_6a04843f0601e8.38144014.png', 2, '2026-05-20 09:51:12', NULL, NULL, NULL, '2026-05-13 10:01:35', '2026-05-20 09:51:12'),
(7, 'E14725834', 'prueba de planilla dos punto cero', 'E14725834', 'progss@uptpc.edu.ve', '02423644304', '0416777777', 'ghfxfg', '84', '7', '84', '272', '10 raices de la revolucion', 'Añu', 'No especificado', 'frente a una farmacia', 5, 1, '2', 'Casa', 'Familiar', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'Otro', 'Sí', 'Diurno', 'COEF', 'dormir', 1, 'Femenino', 'Casado', '1999-06-18', '1', '', '2026-05-13', '2026-05-13 10:04:40', 'Aprobada', 'preinscrito', 'foto_6a0484f8039b01.77431194.png', 2, '2026-05-20 12:14:05', NULL, NULL, NULL, '2026-05-13 10:04:40', '2026-05-20 12:14:05'),
(8, 'V14725822', 'otra prueba', 'V14725822', 'ol_estudios@uptpc.edu.ve', '02423644304', '0412555555', 'fdsfsdf', '462', '24', '462', '1131', '10 raices de la revolucion', 'Añu', 'No especificado', 'frente a un campo', 4, 1, '3', 'Apartamento', 'Familiar', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'sadsda', 1, 'Femenino', 'Divorciado', '2001-07-05', '1', '04145689456', '2026-05-13', '2026-05-13 10:13:40', 'Pendiente', 'preinscrito', 'foto_6a048714c9dcc7.45264687.png', NULL, NULL, NULL, NULL, NULL, '2026-05-13 10:13:40', '2026-05-13 10:13:40'),
(9, 'E87654333', 'Super Prueba', 'E87654333', 'progrl_estudios@uptpc.edu.ve', '02423644304', '0416777777', 'g7uyhg', '390', '21', '390', '979', '10 raices de la revolucion', '', 'No especificado', 'frente a un campo', 5, 3, '3', 'Apartamento', 'Alquilada', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'Otro', 'Sí', 'Diurno', 'COEF', 'jholkiuh', 1, 'Masculino', 'Soltero', '1993-11-11', '0', '', '2026-05-13', '2026-05-13 10:46:58', 'Pendiente', 'preinscrito', 'foto_6a048ee21cceb9.80875698.jpg', NULL, NULL, NULL, NULL, NULL, '2026-05-13 10:46:58', '2026-05-13 10:46:58'),
(10, 'E98653265', 'prueba porsiacaso', 'E98653265', 'os@uptpc.edu.ve', '04124122996', '0416777777', 'dsfdw', '390', '21', '390', '985', '10 raices de la revolucion', '', 'No especificado', 'frente a un campo', 5, 3, '1', '', 'Alquilada', 'Hipertension', 'Motora', 'Bachiller', 'U.E Freancis de Miranda', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'fgafrefgaer', 1, 'Masculino', 'Divorciado', '1995-03-09', '0', '', '2026-05-14', '2026-05-14 11:18:24', 'Aprobada', 'preinscrito', 'foto_6a05e7c08bf451.47831637.jpeg', 2, '2026-05-14 11:20:46', NULL, NULL, NULL, '2026-05-14 11:18:24', '2026-05-14 11:20:46'),
(11, 'V45678932', 'prueba titulo pais', 'V45678932', 'progrdrghos@uptpc.edu.ve', '02423644304', '0416777777', 'gfdghr', '18', '2', '18', '51', '10 raices de la revolucion', 'Wayuu', 'No especificado', 'yuk', 4, 2, '1', 'Apartamento', 'Propia', 'Hipertension', 'Motora', 'Bachiller|||TSU Informatica', 'U.E Freancis de Miranda|||sdsd', 'Otro|||Venezuela', 'Sí|||', 'Nocturno', 'COEF', 'comer', 1, 'Femenino', 'Casado', '2003-06-06', '1', '04163333333', '2026-05-20', '2026-05-20 11:49:56', 'Aprobada', 'preinscrito', 'foto_6a0dd8241e9392.46027094.png', 2, '2026-05-20 11:53:06', NULL, NULL, NULL, '2026-05-20 11:49:56', '2026-05-20 11:53:06'),
(12, 'V33058485', 'Giménez Tovar José David ', 'V33058485', 'josedavid@gmail.com', '04120352159', '04120352159', 'Mi casa', '87', '7', '87', '275', 'Pepe', '', 'No especificado', 'Un árbol al frente ', 4, 0, '3', 'Casa', 'Familiar', '', '', 'Bachiller', 'Fortín Solano', '', '', 'Diurno', 'Puerto Cabello', 'Se jugar béisbol ', 1, 'Masculino', 'Soltero', '2008-05-16', '0', '', '2026-05-27', '2026-05-27 11:46:57', 'Aprobada', 'preinscrito', '', 2, '2026-05-27 11:48:24', NULL, NULL, NULL, '2026-05-27 11:46:57', '2026-05-27 11:48:24'),
(13, 'V30692052', 'Falso Hector', 'V30692052', 'falsohector@prueba.com', '02423644304', '0416777777', 'gkyhugikyg', '11', '2', '11', '31', '10 raices de la revolucion', '', 'No especificado', 'frente a un campo', 9, 8, '1', 'Casa', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', 'Venezuela', '', 'Nocturno', 'Puerto Cabello', 'lol', 1, 'Masculino', 'Soltero', '2003-03-07', '0', '', '2026-06-16', '2026-06-16 12:55:58', 'Aprobada', 'preinscrito', 'foto_6a31801e56da26.56909752.jpeg', 2, '2026-06-16 12:57:38', NULL, NULL, NULL, '2026-06-16 12:55:58', '2026-06-16 12:57:38'),
(14, 'V30692053', 'Falso Hector lol', 'V30692053', 'hectorlamaquina14@gmail.com', '02423644304', '0416777777', 'gkyhugikyg', '30', '3', '30', '87', '10 raices de la revolucion', '', 'No especificado', 'frente a un campo', 4, 3, '2', 'Apartamento', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', 'Venezuela', '', 'Diurno', 'Puerto Cabello', 'lol', 5, 'Masculino', 'Soltero', '2003-03-08', '0', '', '2026-06-16', '2026-06-16 13:03:26', 'Aprobada', 'preinscrito', 'foto_6a3181de6eb3a5.11987768.jpeg', 2, '2026-06-16 13:42:28', NULL, NULL, NULL, '2026-06-16 13:03:26', '2026-06-16 13:42:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prelaciones`
--

CREATE TABLE `prelaciones` (
  `id` int NOT NULL,
  `id_carrera` int NOT NULL,
  `id_materia` int NOT NULL,
  `id_prerequisito` int NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `prelaciones`
--

INSERT INTO `prelaciones` (`id`, `id_carrera`, `id_materia`, `id_prerequisito`, `tipo`, `created_at`) VALUES
(2, 14, 32, 29, 'optativo', '2026-01-14 16:31:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion_cursos`
--

CREATE TABLE `relacion_cursos` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_curso` int NOT NULL,
  `codigo` varchar(10) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respaldos_descargas`
--

CREATE TABLE `respaldos_descargas` (
  `id` int NOT NULL,
  `usuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_archivo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_descarga` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revision_mensajes`
--

CREATE TABLE `revision_mensajes` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id_seccion` int NOT NULL,
  `codigo_seccion` varchar(20) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_carrera` int NOT NULL,
  `turno` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `numero_seccion` int DEFAULT NULL,
  `id_trayecto` int NOT NULL,
  `id_periodo` int NOT NULL,
  `capacidad_maxima` int NOT NULL,
  `capacidad_minima` int DEFAULT '10',
  `aula_asignada` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `horario` text COLLATE utf8mb4_spanish_ci,
  `estatus` enum('activa','inactiva','completa') COLLATE utf8mb4_spanish_ci DEFAULT 'activa',
  `status` enum('Pendiente','Aprobada','Rechazada') COLLATE utf8mb4_spanish_ci DEFAULT 'Pendiente',
  `created_by` int DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `inicia` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_seccion`, `codigo_seccion`, `id_carrera`, `turno`, `numero_seccion`, `id_trayecto`, `id_periodo`, `capacidad_maxima`, `capacidad_minima`, `aula_asignada`, `horario`, `estatus`, `status`, `created_by`, `approved_by`, `approved_at`, `inicia`, `created_at`) VALUES
(13, '70', 1, 'Diurno', 70, 2, 5, 2, 0, NULL, 'Lunes: 07:00 - 09:00', 'activa', 'Aprobada', 2, 2, '2026-05-10 19:22:18', '2026-05-10 15:20:00', '2026-05-10 19:20:41'),
(14, '71', 1, 'Diurno', 71, 1, 5, 6, 0, NULL, 'Lunes: 07:00 - 09:00', 'activa', 'Aprobada', 2, 2, '2026-05-10 21:17:03', '2026-05-10 17:15:00', '2026-05-10 21:15:53'),
(15, '72', 1, 'Diurno', 72, 1, 5, 1, 0, NULL, 'Lunes: 07:00 - 09:00', 'activa', 'Aprobada', 2, 2, '2026-05-10 21:44:01', '2026-05-10 17:40:00', '2026-05-10 21:40:40'),
(17, '74', 1, 'Nocturno', 74, 1, 5, 4, 10, NULL, '', 'activa', 'Aprobada', 2, 2, '2026-05-20 15:43:57', '2026-05-15 00:00:00', '2026-05-15 16:10:32'),
(18, '1-71', 1, 'Diurno', NULL, 2, 6, 6, 0, NULL, NULL, 'activa', 'Aprobada', 2, NULL, NULL, '2026-06-05 11:05:00', '2026-06-05 15:05:43'),
(19, '90', 5, 'Diurno', 90, 1, 6, 10, 10, NULL, '', 'activa', 'Aprobada', 4, 2, '2026-06-16 17:23:51', '2026-06-16 00:00:00', '2026-06-16 17:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretaria_config`
--

CREATE TABLE `secretaria_config` (
  `clave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secretaria_config`
--

INSERT INTO `secretaria_config` (`clave`, `valor`) VALUES
('mostrar_preinscripcion', '1'),
('mostrar_prosecucion', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretaria_configuracion_carga`
--

CREATE TABLE `secretaria_configuracion_carga` (
  `id` int NOT NULL,
  `trimestre_num` int NOT NULL COMMENT '1, 2 o 3',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `activo` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secretaria_configuracion_carga`
--

INSERT INTO `secretaria_configuracion_carga` (`id`, `trimestre_num`, `fecha_inicio`, `fecha_fin`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-05-28', '2026-06-18', 1, '2026-05-29 13:00:00', '2026-05-29 13:00:00'),
(2, 2, '2026-05-30', '2026-06-18', 1, '2026-05-29 13:00:01', '2026-05-29 13:00:01'),
(3, 3, '2026-05-31', '2026-06-18', 1, '2026-05-29 13:00:01', '2026-05-29 13:00:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretaria_cupos`
--

CREATE TABLE `secretaria_cupos` (
  `id` int UNSIGNED NOT NULL,
  `carrera_id` int NOT NULL,
  `turno` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cupos_totales` int NOT NULL DEFAULT '0',
  `numero_secciones` int NOT NULL DEFAULT '1',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secretaria_cupos`
--

INSERT INTO `secretaria_cupos` (`id`, `carrera_id`, `turno`, `cupos_totales`, `numero_secciones`, `updated_at`) VALUES
(191, 5, 'Diurno', 9, 3, '2026-06-05 12:37:34'),
(192, 5, 'Nocturno', 11, 3, '2026-06-05 12:37:34'),
(193, 1, 'Diurno', 21, 7, '2026-06-05 12:37:34'),
(194, 1, 'Nocturno', 25, 7, '2026-06-05 12:37:34'),
(195, 2, 'Diurno', 14, 5, '2026-06-05 12:39:09'),
(196, 2, 'Nocturno', 14, 5, '2026-06-05 12:39:09'),
(197, 14, 'Diurno', 0, 1, '2026-05-10 19:52:42'),
(198, 14, 'Nocturno', 0, 1, '2026-05-10 19:52:42'),
(199, 15, 'Diurno', 0, 1, '2026-05-10 19:52:42'),
(200, 15, 'Nocturno', 0, 1, '2026-05-10 19:52:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_bloqueos`
--

CREATE TABLE `seguridad_bloqueos` (
  `id` int NOT NULL,
  `ip` varchar(45) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motivo` enum('recuperar_fallido','cambiar_fallido','ip_sospechosa') NOT NULL,
  `desbloqueo_en` datetime NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `intentos` int DEFAULT '1',
  `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `seguridad_bloqueos`
--

INSERT INTO `seguridad_bloqueos` (`id`, `ip`, `email`, `motivo`, `desbloqueo_en`, `activo`, `intentos`, `fecha_creacion`) VALUES
(1, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-16 10:09:50', 0, 3, '2026-06-16 09:09:50'),
(2, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-16 10:09:51', 0, 4, '2026-06-16 09:09:51'),
(3, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-16 10:10:06', 0, 5, '2026-06-16 09:10:06'),
(4, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:10:07', 0, 6, '2026-06-16 09:10:07'),
(5, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:10:08', 0, 7, '2026-06-16 09:10:08'),
(6, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:10:09', 0, 8, '2026-06-16 09:10:09'),
(7, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:18:23', 0, 9, '2026-06-16 09:18:23'),
(8, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:18:27', 0, 10, '2026-06-16 09:18:27'),
(9, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:18:27', 0, 11, '2026-06-16 09:18:27'),
(10, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:18:28', 0, 12, '2026-06-16 09:18:28'),
(11, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:32:26', 0, 13, '2026-06-16 09:32:26'),
(12, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:32:28', 0, 14, '2026-06-16 09:32:28'),
(13, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:32:29', 0, 15, '2026-06-16 09:32:29'),
(14, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 09:32:30', 0, 16, '2026-06-16 09:32:30'),
(15, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 10:05:43', 0, 17, '2026-06-16 10:05:43'),
(16, '::1', 'test@prueba.com', 'recuperar_fallido', '2026-06-17 10:05:44', 0, 18, '2026-06-16 10:05:44'),
(17, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-16 12:35:36', 0, 3, '2026-06-16 11:35:36'),
(18, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-16 12:35:37', 0, 4, '2026-06-16 11:35:37'),
(19, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-16 12:35:39', 0, 5, '2026-06-16 11:35:39'),
(20, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:41', 0, 6, '2026-06-16 11:35:41'),
(21, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:42', 0, 7, '2026-06-16 11:35:43'),
(22, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:44', 0, 8, '2026-06-16 11:35:44'),
(23, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:49', 0, 9, '2026-06-16 11:35:50'),
(24, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:53', 0, 10, '2026-06-16 11:35:54'),
(25, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:55', 0, 11, '2026-06-16 11:35:55'),
(26, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:35:57', 0, 12, '2026-06-16 11:35:57'),
(27, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:36:01', 0, 13, '2026-06-16 11:36:01'),
(28, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:36:03', 0, 14, '2026-06-16 11:36:03'),
(29, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:36:04', 0, 15, '2026-06-16 11:36:04'),
(30, '::1', 'prueba_rps@test.com', 'recuperar_fallido', '2026-06-17 11:36:06', 0, 16, '2026-06-16 11:36:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_intentos`
--

CREATE TABLE `seguridad_intentos` (
  `id` int NOT NULL,
  `ip` varchar(45) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo` enum('recuperar','cambiar') NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `seguridad_intentos`
--

INSERT INTO `seguridad_intentos` (`id`, `ip`, `email`, `tipo`, `user_agent`, `fecha`) VALUES
(1, '::1', 'rps_1@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:34'),
(2, '::1', 'rps_2@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:34'),
(3, '::1', 'rps_3@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:35'),
(4, '::1', 'rps_4@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:35'),
(5, '::1', 'rps_5@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:35'),
(6, '::1', 'rps_6@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:35'),
(7, '::1', 'rps_7@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:35'),
(8, '::1', 'rps_8@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:36'),
(9, '::1', 'rps_9@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:36'),
(10, '::1', 'rps_10@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:36'),
(11, '::1', 'rps_11@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:36'),
(12, '::1', 'rps_12@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:37'),
(13, '::1', 'rps_13@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:37'),
(14, '::1', 'rps_14@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:37'),
(15, '::1', 'rps_15@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:37'),
(16, '::1', 'rps_16@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:38'),
(17, '::1', 'rps_17@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:38'),
(18, '::1', 'rps_18@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:38'),
(19, '::1', 'rps_19@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:38'),
(20, '::1', 'rps_20@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:39'),
(21, '::1', 'rps_21@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:39'),
(22, '::1', 'rps_22@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:39'),
(23, '::1', 'rps_23@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:39'),
(24, '::1', 'rps_24@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:40'),
(25, '::1', 'rps_25@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:40'),
(26, '::1', 'rps_26@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:40'),
(27, '::1', 'rps_27@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:40'),
(28, '::1', 'rps_28@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:41'),
(29, '::1', 'rps_29@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:41'),
(30, '::1', 'rps_30@test.com', 'recuperar', 'desconocido', '2026-06-16 11:31:41'),
(31, '::1', 'falso_1@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:41'),
(32, '::1', 'falso_2@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:42'),
(33, '::1', 'falso_3@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:42'),
(34, '::1', 'falso_4@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:42'),
(35, '::1', 'falso_5@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:43'),
(36, '::1', 'falso_6@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:43'),
(37, '::1', 'falso_7@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:43'),
(38, '::1', 'falso_8@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:44'),
(39, '::1', 'falso_9@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:44'),
(40, '::1', 'falso_10@inexistente.com', 'recuperar', 'desconocido', '2026-06-16 11:31:44'),
(41, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:30'),
(42, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:33'),
(43, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:35'),
(44, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:37'),
(45, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:39'),
(46, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:40'),
(47, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:42'),
(48, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:44'),
(49, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:49'),
(50, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:53'),
(51, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:55'),
(52, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:35:56'),
(53, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:36:01'),
(54, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:36:03'),
(55, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:36:04'),
(56, '::1', 'prueba_rps@test.com', 'recuperar', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-16 11:36:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_rps`
--

CREATE TABLE `seguridad_rps` (
  `id` int NOT NULL,
  `ip` varchar(45) NOT NULL,
  `endpoint` varchar(100) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `seguridad_rps`
--

INSERT INTO `seguridad_rps` (`id`, `ip`, `endpoint`, `fecha`) VALUES
(24, '::1', 'recuperar_password', '2026-06-16 12:37:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_sistema`
--

CREATE TABLE `seguridad_sistema` (
  `id` int NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `actualizado_en` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `seguridad_sistema`
--

INSERT INTO `seguridad_sistema` (`id`, `clave`, `valor`, `actualizado_en`) VALUES
(1, 'sistema_activo', '1', '2026-06-15 13:15:10'),
(2, 'modo_mantenimiento', '0', '2026-06-15 13:15:14'),
(3, 'limite_recuperar_por_hora', '3', '2026-06-15 09:38:36'),
(4, 'limite_bloqueo_horas', '1', '2026-06-15 09:38:36'),
(5, 'limite_bloqueo_incremento', '24', '2026-06-15 09:38:36'),
(6, 'limite_rps_10seg', '10', '2026-06-15 09:38:36'),
(7, 'limite_rps_global_porcentaje', '10', '2026-06-15 09:38:36'),
(8, 'ultimo_ataque_detectado', '', '2026-06-15 09:38:36'),
(9, 'total_usuarios', '130', '2026-06-16 13:42:28'),
(10, 'sistema_completo_activo', '1', '2026-06-15 12:53:21'),
(11, 'razon_cierre', '', '2026-06-15 12:53:21'),
(12, 'ultimo_cierre_por', 'Dios', '2026-06-15 11:22:45'),
(13, 'fecha_cierre', '2026-06-15 12:53:11', '2026-06-15 12:53:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguridad_tokens_invalidos`
--

CREATE TABLE `seguridad_tokens_invalidos` (
  `id` int NOT NULL,
  `token_recibido` varchar(255) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE `status` (
  `id` int NOT NULL,
  `status` varchar(10) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(0, 'Inactivo'),
(1, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tenencia_vivienda`
--

CREATE TABLE `tenencia_vivienda` (
  `id` int NOT NULL,
  `tenencia` varchar(20) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `tenencia_vivienda`
--

INSERT INTO `tenencia_vivienda` (`id`, `tenencia`) VALUES
(1, 'Propia'),
(2, 'Alquilada'),
(3, 'Familiar'),
(4, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_horario`
--

CREATE TABLE `tipos_horario` (
  `id` int NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `horas_academicas` int DEFAULT '0',
  `horas_atendiendo` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_horario`
--

INSERT INTO `tipos_horario` (`id`, `nombre`, `horas_academicas`, `horas_atendiendo`) VALUES
(1, 'Dedicacion Exclusiva', 36, 0),
(2, 'Tiempo Completo', 16, 14),
(3, 'Medio tiempo', 12, 6),
(4, 'Convencional', 7, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cedula`
--

CREATE TABLE `tipo_cedula` (
  `id` int NOT NULL,
  `tipo` varchar(2) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_cedula`
--

INSERT INTO `tipo_cedula` (`id`, `tipo`) VALUES
(1, 'V-'),
(2, 'E-');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_formacion`
--

CREATE TABLE `tipo_formacion` (
  `id` int NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_formacion`
--

INSERT INTO `tipo_formacion` (`id`, `tipo`) VALUES
(1, 'PNF'),
(2, 'PTF');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_horario_personal`
--

CREATE TABLE `tipo_horario_personal` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_tipo_horario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_horario_personal`
--

INSERT INTO `tipo_horario_personal` (`id`, `id_usuario`, `id_tipo_horario`) VALUES
(2, 2, 4),
(1, 4, 2),
(3, 2585, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id` int NOT NULL,
  `tipopago` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id`, `tipopago`) VALUES
(16, 'Autenticación de Título'),
(3, 'Cambio de Programa'),
(4, 'Cambio de Sede'),
(34, 'Cambio de Turno'),
(13, 'Carnet Estudiantil'),
(20, 'Certificación de Acta de Grado'),
(23, 'Certificación de Saberes'),
(15, 'Certificado de Título'),
(26, 'Cursos'),
(22, 'Derecho a Grado'),
(28, 'Diplomado'),
(29, 'Especialización'),
(24, 'Examen de Suficiencia'),
(25, 'Examen Extraordinario'),
(6, 'Expedición de Constancia Certificada de Notas'),
(8, 'Expedición de Constancia de Buena Conducta'),
(9, 'Expedición de Constancia de Culminación Académica'),
(10, 'Expedición de Constancia de Estudios'),
(11, 'Expedición de Constancia de Inscripción'),
(19, 'Expedición de Constancia de Modalidad de Estudios'),
(12, 'Expedición de Constancia de Servicio Comunitario'),
(7, 'Expedición de Constancia Simple de Notas'),
(21, 'Grado'),
(1, 'Inscripción'),
(5, 'Inscripción de Pasantías/Práctica Profesional'),
(30, 'Maestría'),
(31, 'Otro'),
(17, 'Pensum de Estudios Certificados'),
(18, 'Programas Analíticos/Vigencia de Programas'),
(2, 'Reincorporación/Estudio de Expediente'),
(33, 'Retiro de Trayecto'),
(27, 'Talleres'),
(14, 'Uniforme (Franela) Estudiantil');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_vivienda`
--

CREATE TABLE `tipo_vivienda` (
  `id` int NOT NULL,
  `vivienda` varchar(20) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `tipo_vivienda`
--

INSERT INTO `tipo_vivienda` (`id`, `vivienda`) VALUES
(1, 'Casa'),
(2, 'Apartamento'),
(3, 'Quinta'),
(4, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos`
--

CREATE TABLE `titulos` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `titulos`
--

INSERT INTO `titulos` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Bachiller en Ciencias', 'Título de educación secundaria con énfasis en ciencias exactas y naturales'),
(2, 'Bachiller en Humanidades', 'Título de educación secundaria con énfasis en ciencias sociales y humanidades'),
(3, 'Bachiller Técnico en Informática', 'Formación secundaria con especialización en fundamentos de informática'),
(4, 'Bachiller Técnico en Administración', 'Formación secundaria con especialización en gestión administrativa'),
(5, 'Bachiller Técnico en Contabilidad', 'Formación secundaria con especialización en principios contables'),
(6, 'Bachiller Técnico en Electrónica', 'Formación secundaria con especialización en circuitos y sistemas electrónicos'),
(7, 'Bachiller Técnico en Mecánica Automotriz', 'Formación secundaria con especialización en mantenimiento vehicular'),
(8, 'Bachiller Técnico en Salud', 'Formación secundaria con especialización en fundamentos de salud'),
(9, 'Técnico Superior en Desarrollo de Software', 'Formación superior para desarrollo de aplicaciones y sistemas software'),
(10, 'Técnico Superior en Redes y Telecomunicaciones', 'Formación superior en infraestructura de redes y sistemas de comunicación'),
(11, 'Técnico Superior en Mecatrónica', 'Formación superior en sistemas mecánicos-electrónicos integrados'),
(12, 'Técnico Superior en Automatización Industrial', 'Formación superior en control y automatización de procesos industriales'),
(13, 'Técnico Superior en Análisis de Sistemas', 'Formación superior en análisis y diseño de sistemas informáticos'),
(14, 'Técnico Superior en Administración de Empresas', 'Formación superior en gestión organizacional y administrativa'),
(15, 'Técnico Superior en Contabilidad', 'Formación superior en contabilidad financiera y tributaria'),
(16, 'Técnico Superior en Turismo y Hotelería', 'Formación superior en gestión de servicios turísticos y hoteleros'),
(17, 'Técnico Superior en Diseño Gráfico', 'Formación superior en diseño visual y comunicación gráfica'),
(18, 'Técnico Superior en Gastronomía', 'Formación superior en técnicas culinarias y gestión de alimentos'),
(19, 'Técnico Superior en Enfermería', 'Formación superior en cuidados de enfermería general'),
(20, 'Técnico Superior en Laboratorio Clínico', 'Formación superior en análisis clínicos y diagnóstico de laboratorio'),
(21, 'Técnico Superior en Educación Infantil', 'Formación superior en pedagogía para la primera infancia'),
(22, 'Técnico Superior en Seguridad Industrial', 'Formación superior en prevención de riesgos laborales'),
(23, 'Técnico Superior en Logística', 'Formación superior en gestión de cadena de suministro'),
(24, 'Licenciatura en Matemáticas', 'Formación universitaria en matemáticas puras y aplicadas'),
(25, 'Licenciatura en Física', 'Formación universitaria en física teórica y experimental'),
(26, 'Licenciatura en Química', 'Formación universitaria en principios químicos y sus aplicaciones'),
(27, 'Licenciatura en Biología', 'Formación universitaria en ciencias biológicas y organismos vivos'),
(28, 'Licenciatura en Estadística', 'Formación universitaria en análisis estadístico y probabilístico'),
(29, 'Licenciatura en Ciencias de la Computación', 'Formación universitaria en fundamentos teóricos de la computación'),
(30, 'Licenciatura en Ingeniería de Software', 'Formación universitaria en desarrollo sistemático de software'),
(31, 'Licenciatura en Inteligencia Artificial', 'Formación universitaria en algoritmos y sistemas inteligentes'),
(32, 'Licenciatura en Ciencias de Datos', 'Formación universitaria en análisis y procesamiento de big data'),
(33, 'Licenciatura en Filosofía', 'Formación universitaria en pensamiento crítico y teoría filosófica'),
(34, 'Licenciatura en Historia', 'Formación universitaria en investigación y análisis histórico'),
(35, 'Licenciatura en Literatura', 'Formación universitaria en teoría literaria y análisis de textos'),
(36, 'Licenciatura en Lingüística', 'Formación universitaria en estudio científico del lenguaje'),
(37, 'Licenciatura en Psicología', 'Formación universitaria en estudio de la mente y comportamiento humano'),
(38, 'Licenciatura en Sociología', 'Formación universitaria en análisis de estructuras y dinámicas sociales'),
(39, 'Licenciatura en Economía', 'Formación universitaria en teoría económica y análisis de mercados'),
(40, 'Licenciatura en Administración de Empresas', 'Formación universitaria en gestión organizacional y empresarial'),
(41, 'Licenciatura en Contabilidad', 'Formación universitaria en contabilidad financiera y gerencial'),
(42, 'Licenciatura en Marketing', 'Formación universitaria en gestión estratégica de mercados'),
(43, 'Licenciatura en Derecho', 'Formación universitaria en ciencias jurídicas y legislación'),
(44, 'Licenciatura en Medicina', 'Formación universitaria en diagnóstico y tratamiento médico'),
(45, 'Licenciatura en Enfermería', 'Formación universitaria en cuidados integrales de salud'),
(46, 'Licenciatura en Odontología', 'Formación universitaria en salud bucodental y tratamientos dentales'),
(47, 'Licenciatura en Farmacia', 'Formación universitaria en medicamentos y terapéutica farmacológica'),
(48, 'Licenciatura en Arquitectura', 'Formación universitaria en diseño y construcción de espacios habitables'),
(49, 'Licenciatura en Diseño Industrial', 'Formación universitaria en desarrollo de productos industriales'),
(50, 'Licenciatura en Bellas Artes', 'Formación universitaria en creación y teoría artística'),
(51, 'Licenciatura en Música', 'Formación universitaria en interpretación y teoría musical'),
(52, 'Licenciatura en Teatro', 'Formación universitaria en artes escénicas y dramaturgia'),
(53, 'Ingeniería Civil', 'Formación en diseño, construcción y mantenimiento de infraestructura'),
(54, 'Ingeniería Mecánica', 'Formación en diseño y análisis de sistemas mecánicos'),
(55, 'Ingeniería Eléctrica', 'Formación en generación, transmisión y distribución de energía eléctrica'),
(56, 'Ingeniería Electrónica', 'Formación en circuitos, sistemas de control y telecomunicaciones'),
(57, 'Ingeniería de Sistemas', 'Formación en desarrollo e implementación de sistemas informáticos'),
(58, 'Ingeniería Industrial', 'Formación en optimización de procesos productivos y logísticos'),
(59, 'Ingeniería Química', 'Formación en procesos químicos industriales y transformación de materiales'),
(60, 'Ingeniería Ambiental', 'Formación en gestión y protección de recursos naturales'),
(61, 'Ingeniería Biomédica', 'Formación en aplicación de ingeniería a problemas médicos y biológicos'),
(62, 'Ingeniería en Telecomunicaciones', 'Formación en sistemas de comunicación y transmisión de información'),
(63, 'Ingeniería en Energías Renovables', 'Formación en tecnologías de energía sostenible'),
(64, 'Ingeniería en Materiales', 'Formación en desarrollo y caracterización de nuevos materiales'),
(65, 'Ingeniería Aeroespacial', 'Formación en diseño de aeronaves y sistemas espaciales'),
(66, 'Ingeniería Naval', 'Formación en diseño y construcción de estructuras marinas'),
(67, 'Ingeniería en Petróleo', 'Formación en exploración, extracción y procesamiento de hidrocarburos'),
(68, 'Especialización en Matemáticas Avanzadas', 'Posgrado en áreas avanzadas de matemáticas puras y aplicadas'),
(69, 'Especialización en Física Teórica', 'Posgrado en física matemática y teorías fundamentales'),
(70, 'Especialización en Inteligencia Artificial', 'Posgrado en desarrollo de sistemas inteligentes y aprendizaje automático'),
(71, 'Especialización en Ciberseguridad', 'Posgrado en protección de sistemas y datos digitales'),
(72, 'Especialización en Finanzas Cuantitativas', 'Posgrado en modelado matemático para mercados financieros'),
(73, 'Especialización en Derecho Tributario', 'Posgrado en legislación y normativa fiscal'),
(74, 'Especialización en Cardiología', 'Posgrado médico en diagnóstico y tratamiento de enfermedades cardíacas'),
(75, 'Especialización en Pediatría', 'Posgrado médico en salud infantil y desarrollo pediátrico'),
(76, 'Especialización en Ingeniería Estructural', 'Posgrado en diseño y análisis de estructuras complejas'),
(77, 'Maestría en Matemáticas Puras', 'Posgrado avanzado en investigación matemática teórica'),
(78, 'Maestría en Física Cuántica', 'Posgrado avanzado en mecánica cuántica y sus aplicaciones'),
(79, 'Maestría en Ciencias de la Computación', 'Posgrado avanzado en teoría computacional y algoritmos'),
(80, 'Maestría en Inteligencia Artificial', 'Posgrado avanzado en sistemas cognitivos y machine learning'),
(81, 'Maestría en Economía Aplicada', 'Posgrado avanzado en análisis económico cuantitativo'),
(82, 'Maestría en Administración de Empresas (MBA)', 'Posgrado avanzado en gestión empresarial estratégica'),
(83, 'Maestría en Derecho Corporativo', 'Posgrado avanzado en legislación aplicada a negocios'),
(84, 'Maestría en Salud Pública', 'Posgrado avanzado en políticas y gestión de salud poblacional'),
(85, 'Maestría en Educación Superior', 'Posgrado avanzado en pedagogía universitaria y gestión académica'),
(86, 'Doctorado en Matemáticas', 'Máximo grado académico en investigación matemática'),
(87, 'Doctorado en Física', 'Máximo grado académico en investigación física teórica/experimental'),
(88, 'Doctorado en Ciencias de la Computación', 'Máximo grado académico en investigación computacional'),
(89, 'Doctorado en Ingeniería', 'Máximo grado académico en investigación en ingeniería avanzada'),
(90, 'Doctorado en Economía', 'Máximo grado académico en investigación económica'),
(91, 'Doctorado en Psicología Clínica', 'Máximo grado académico en investigación en psicología aplicada'),
(92, 'Doctorado en Ciencias de la Educación', 'Máximo grado académico en investigación educativa'),
(93, 'Profesorado en Matemáticas', 'Formación pedagógica especializada en enseñanza de matemáticas'),
(94, 'Profesorado en Física', 'Formación pedagógica especializada en enseñanza de física'),
(95, 'Profesorado en Química', 'Formación pedagógica especializada en enseñanza de química'),
(96, 'Profesorado en Biología', 'Formación pedagógica especializada en enseñanza de biología'),
(97, 'Profesorado en Informática', 'Formación pedagógica especializada en enseñanza de computación'),
(98, 'Profesorado en Lengua y Literatura', 'Formación pedagógica especializada en enseñanza lingüística'),
(99, 'Profesorado en Historia', 'Formación pedagógica especializada en enseñanza histórica'),
(100, 'Profesorado en Filosofía', 'Formación pedagógica especializada en enseñanza filosófica'),
(101, 'Profesorado en Educación Primaria', 'Formación pedagógica para enseñanza en nivel primario'),
(102, 'Profesorado en Educación Inicial', 'Formación pedagógica para enseñanza en primera infancia'),
(103, 'Profesorado en Inglés', 'Formación pedagógica especializada en enseñanza del idioma inglés'),
(104, 'Profesorado en Educación Física', 'Formación pedagógica especializada en actividad física y deportes'),
(105, 'Medicina General', 'Formación médica integral para atención primaria de salud'),
(106, 'Cirugía General', 'Especialidad médica en técnicas quirúrgicas básicas'),
(107, 'Pediatría', 'Especialidad médica en salud infantil y desarrollo'),
(108, 'Ginecología y Obstetricia', 'Especialidad médica en salud reproductiva femenina'),
(109, 'Psiquiatría', 'Especialidad médica en trastornos mentales y conductuales'),
(110, 'Anestesiología', 'Especialidad médica en manejo del dolor y anestesia'),
(111, 'Radiología', 'Especialidad médica en diagnóstico por imágenes'),
(112, 'Medicina Interna', 'Especialidad médica en diagnóstico y tratamiento de adultos'),
(113, 'Enfermería Universitaria', 'Formación avanzada en cuidados de enfermería integral'),
(114, 'Bioquímica Clínica', 'Especialidad en análisis bioquímicos para diagnóstico médico'),
(115, 'Nutrición y Dietética', 'Formación en alimentación, nutrición y dietoterapia'),
(116, 'Fisioterapia y Rehabilitación', 'Formación en terapias físicas para recuperación funcional'),
(117, 'Odontología General', 'Formación en diagnóstico y tratamiento de enfermedades bucales'),
(118, 'Optometría', 'Formación en salud visual y corrección de problemas oculares'),
(119, 'Licenciatura en Artes Visuales', 'Formación universitaria en creación y teoría de artes plásticas'),
(120, 'Licenciatura en Diseño Gráfico', 'Formación universitaria en comunicación visual y diseño'),
(121, 'Licenciatura en Diseño Industrial', 'Formación universitaria en desarrollo de productos funcionales'),
(122, 'Licenciatura en Diseño de Interiores', 'Formación universitaria en diseño espacial y ambientación'),
(123, 'Licenciatura en Artes Escénicas', 'Formación universitaria en teatro, danza y performance'),
(124, 'Licenciatura en Cinematografía', 'Formación universitaria en producción y dirección cinematográfica'),
(125, 'Licenciatura en Producción Musical', 'Formación universitaria en creación y gestión musical'),
(126, 'Licenciatura en Historia del Arte', 'Formación universitaria en análisis y crítica artística'),
(129, 'Licenciatura en Educación', 'Formación universitaria en teoría y práctica pedagógica'),
(131, 'TSU Informática', 'Tecnico Superior Universitario en Informatica'),
(132, 'Economista', 'Experto en ciencias sociales en asignación y manejo de recursos escasos para necesidades múltiples');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos_obtenidos`
--

CREATE TABLE `titulos_obtenidos` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nombre` varchar(255) COLLATE utf32_spanish2_ci NOT NULL,
  `titulo_obtenido` varchar(255) COLLATE utf32_spanish2_ci NOT NULL,
  `instituto` varchar(255) COLLATE utf32_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `titulos_obtenidos`
--

INSERT INTO `titulos_obtenidos` (`id`, `id_usuario`, `nombre`, `titulo_obtenido`, `instituto`) VALUES
(14, 2584, 'Manuel Turiso', 'ijfnewpj', 'dnfiuwn'),
(15, 2585, 'Alberto Lopez', 'TSU Informática', 'UPTPC'),
(16, 2586, 'Francisco Torrealba', 'Técnico Superior en Desarrollo de Software', 'Universidad de Carabobo'),
(17, 2588, 'Sarsamora Vegano', 'TSU Informática', 'Universidad de Carabobo'),
(18, 2589, 'Palmera Kazekage', 'Bachiller', 'U.E Manuel Gual'),
(19, 2590, 'Francisco Mendoza', 'Bachiller', 'U.E Manuel Gual'),
(20, 2591, 'Claudia Lopez', 'Bachiller', 'U.E Manuel Gual'),
(21, 2592, 'Jose Manuel', 'Bachiller', 'Tal lugar'),
(22, 2593, 'Jose Manuel Lopez', 'Bachiller', 'lol'),
(23, 2594, 'Maria Antonieta', 'Bachiller', 'porai'),
(24, 2595, 'Sofia Fernandez', 'Bachiller', 'porai'),
(25, 2596, 'Hector Gutierrez', 'Bachiller', 'lol'),
(26, 2597, 'Luis Aguilar', 'Bachiller', 'porai'),
(27, 2600, 'Anabelle Carroza', 'Bachiller', 'porai'),
(28, 2601, 'Manuel Turisooo', 'Bachiller', 'porai'),
(29, 2603, 'Manteca De Colesterol', 'Bachiller', 'porai'),
(30, 2604, 'Jose Manuel Lopezz', 'Bachiller', 'porai'),
(35, 2607, 'Nombre Ejemplo', 'Bachiller', 'Liceo XYZ'),
(36, 2607, 'Nombre Ejemplo', 'Licenciatura', 'Universidad ABC'),
(37, 2608, 'bhuftyfu', 'Bachiller', 'U.E Freancis de Miranda'),
(38, 2609, 'Perdomo Albañil', 'Ingeniería Mecánica', 'U.E Manuel Gual'),
(39, 2617, 'una pruba', 'Bachiller', 'U.E Freancis de Miranda'),
(40, 2618, 'Otra Prueba', 'Bachiller', 'U.E Freancis de Miranda'),
(41, 2619, 'Diosito Otra Prueba', 'Bachiller', 'U.E Freancis de Miranda'),
(42, 2620, 'Papadio Super Prueba', 'Bachiller', 'U.E Freancis de Miranda');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulo_materia`
--

CREATE TABLE `titulo_materia` (
  `id_relacion` int NOT NULL,
  `id_titulo` int NOT NULL,
  `id_materia` int NOT NULL,
  `prioridad` tinyint DEFAULT '1' COMMENT '1=Recomendado, 2=Obligatorio, 3=Alternativo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `titulo_materia`
--

INSERT INTO `titulo_materia` (`id_relacion`, `id_titulo`, `id_materia`, `prioridad`, `fecha_creacion`) VALUES
(29, 24, 5, 2, '2025-08-01 21:03:20'),
(30, 57, 5, 2, '2025-08-01 21:03:20'),
(31, 9, 5, 1, '2025-08-01 21:03:20'),
(32, 25, 5, 1, '2025-08-01 21:03:20'),
(33, 53, 5, 1, '2025-08-01 21:03:20'),
(34, 38, 6, 2, '2025-08-01 21:03:20'),
(35, 34, 6, 2, '2025-08-01 21:03:20'),
(36, 129, 6, 1, '2025-08-01 21:03:20'),
(37, 99, 6, 1, '2025-08-01 21:03:20'),
(38, 24, 7, 2, '2025-08-01 21:03:20'),
(39, 54, 7, 2, '2025-08-01 21:03:20'),
(40, 55, 7, 2, '2025-08-01 21:03:20'),
(41, 11, 7, 1, '2025-08-01 21:03:20'),
(42, 40, 9, 2, '2025-08-01 21:03:20'),
(43, 14, 9, 2, '2025-08-01 21:03:20'),
(44, 58, 9, 1, '2025-08-01 21:03:20'),
(45, 129, 9, 1, '2025-08-01 21:03:20'),
(46, 29, 10, 2, '2025-08-01 21:03:20'),
(47, 57, 10, 2, '2025-08-01 21:03:20'),
(48, 9, 10, 2, '2025-08-01 21:03:20'),
(49, 56, 10, 1, '2025-08-01 21:03:20'),
(50, 10, 10, 1, '2025-08-01 21:03:20'),
(51, 30, 11, 2, '2025-08-01 21:03:20'),
(52, 9, 11, 2, '2025-08-01 21:03:20'),
(53, 29, 11, 2, '2025-08-01 21:03:20'),
(54, 31, 11, 1, '2025-08-01 21:03:20'),
(55, 13, 11, 1, '2025-08-01 21:03:20'),
(56, 33, 12, 2, '2025-08-01 21:03:20'),
(57, 38, 12, 2, '2025-08-01 21:03:20'),
(58, 100, 12, 1, '2025-08-01 21:03:20'),
(59, 34, 12, 1, '2025-08-01 21:03:20'),
(60, 58, 13, 2, '2025-08-01 21:03:20'),
(61, 38, 13, 2, '2025-08-01 21:03:20'),
(62, 22, 13, 1, '2025-08-01 21:03:20'),
(63, 40, 13, 1, '2025-08-01 21:03:20'),
(64, 103, 14, 2, '2025-08-01 21:03:20'),
(65, 36, 14, 1, '2025-08-01 21:03:20'),
(66, 35, 14, 1, '2025-08-01 21:03:20'),
(67, 131, 11, 1, '2025-08-03 21:00:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trayectos`
--

CREATE TABLE `trayectos` (
  `id_trayecto` int NOT NULL,
  `numero_trayecto` int NOT NULL,
  `nombre_trayecto` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `trayectos`
--

INSERT INTO `trayectos` (`id_trayecto`, `numero_trayecto`, `nombre_trayecto`, `descripcion`) VALUES
(1, 0, 'Trayecto Inicial', NULL),
(2, 1, 'Trayecto 1', NULL),
(3, 2, 'Trayecto 2', NULL),
(4, 3, 'Trayecto 3', NULL),
(5, 4, 'Trayecto 4', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `idusuario` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `username` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `tlf` varchar(11) COLLATE latin1_spanish_ci NOT NULL,
  `cel` varchar(11) COLLATE latin1_spanish_ci NOT NULL,
  `direccion` varchar(300) COLLATE latin1_spanish_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `estado` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `municipio` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `parroquia` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `etnia` varchar(50) COLLATE latin1_spanish_ci DEFAULT 'Ninguna',
  `casaapto` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `punto_referencia` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `grupo_familiar` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `acargo_usted` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `fuente_ingresos` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `tipo_vivienda` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `tenencia_vivienda` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `enfermedad` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `discapacidad` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `titulos` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `institutos` varchar(255) COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `potencialidades` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_act` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '1',
  `user_type` varchar(200) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `carrera` int DEFAULT NULL,
  `carrera_di` int DEFAULT NULL,
  `genero` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `embarazada` tinyint(1) DEFAULT '0',
  `edo_civil` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `num_telf_opc` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `sede` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `pais_titulo` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `legalizado_titulo` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `usuario` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `estudiante` int DEFAULT '0',
  `docente` int DEFAULT '0',
  `admin` int DEFAULT '0',
  `super_user` int DEFAULT '0',
  `editar_user` int DEFAULT '0',
  `editar_nota` int DEFAULT '0',
  `editar_acceso` int DEFAULT '0',
  `editar_valores` int DEFAULT '0',
  `editar_estudiante` int DEFAULT '0',
  `agregar_estudiante` int DEFAULT '0',
  `agregar_docente` int DEFAULT '0',
  `editar_docente` int DEFAULT '0',
  `agregar_carrera` int DEFAULT '0',
  `agregar_materia` int DEFAULT '0',
  `editar_materia` int DEFAULT '0',
  `pagos` int DEFAULT '0',
  `auditoria` int DEFAULT '0',
  `secciones` int DEFAULT '0',
  `rela_materia_carrera` int DEFAULT '0',
  `periodos_academicos` int DEFAULT '0',
  `asig_secciones` int DEFAULT '0',
  `asig_cursos` int DEFAULT '0',
  `horarios` int DEFAULT '0',
  `gestion_director_carrera` int DEFAULT '0',
  `notas_cargadas` int DEFAULT '0',
  `consultar_notas` int DEFAULT '0',
  `consultar_notas_pasadas` int DEFAULT '0',
  `tipos_pago` int DEFAULT '0',
  `tipos_horario` int DEFAULT '0',
  `horario_personal` int DEFAULT '0',
  `respaldo_bd` int DEFAULT '0',
  `gestionar_carrera` int DEFAULT '0',
  `gestion_periodo_academico` int DEFAULT '0',
  `gestion_asig_cursos` int DEFAULT '0',
  `gestion_horario` int DEFAULT '0',
  `titulos_re_materia` int DEFAULT '0',
  `grado` int DEFAULT '0',
  `gestion_grado` int DEFAULT '0',
  `vocero` int DEFAULT NULL,
  `visita` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `idusuario`, `nombre`, `username`, `email`, `tlf`, `cel`, `direccion`, `ciudad`, `estado`, `municipio`, `parroquia`, `etnia`, `casaapto`, `punto_referencia`, `grupo_familiar`, `acargo_usted`, `fuente_ingresos`, `tipo_vivienda`, `tenencia_vivienda`, `enfermedad`, `discapacidad`, `titulos`, `institutos`, `potencialidades`, `fecha_ingreso`, `fecha_act`, `status`, `user_type`, `password`, `api_key`, `carrera`, `carrera_di`, `genero`, `embarazada`, `edo_civil`, `fecha_nac`, `num_telf_opc`, `sede`, `pais_titulo`, `legalizado_titulo`, `foto_perfil`, `usuario`, `estudiante`, `docente`, `admin`, `super_user`, `editar_user`, `editar_nota`, `editar_acceso`, `editar_valores`, `editar_estudiante`, `agregar_estudiante`, `agregar_docente`, `editar_docente`, `agregar_carrera`, `agregar_materia`, `editar_materia`, `pagos`, `auditoria`, `secciones`, `rela_materia_carrera`, `periodos_academicos`, `asig_secciones`, `asig_cursos`, `horarios`, `gestion_director_carrera`, `notas_cargadas`, `consultar_notas`, `consultar_notas_pasadas`, `tipos_pago`, `tipos_horario`, `horario_personal`, `respaldo_bd`, `gestionar_carrera`, `gestion_periodo_academico`, `gestion_asig_cursos`, `gestion_horario`, `titulos_re_materia`, `grado`, `gestion_grado`, `vocero`, `visita`) VALUES
(1, 'J-294444890', 'J.E Suministros y Mas, C.A.', 'jesuministrosymas', 'info@jesuministrosymas.com.ve', '02423644304', '0416777777', 'San Esteban Urb, avenida principal casa 23', 'Puerto Cabello', 'Carabobo', '', '', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-10-23 04:00:00', '2026-05-26 16:44:54', 1, 'admin', '$2y$10$l2Ss0UiDUN633hdjjyZ.DOhejW9JbHz5T6FUCy.VLpcmo.thha.ce', '', 1, 0, 'masculino', 0, '', NULL, '', NULL, NULL, NULL, NULL, '0', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2, '12345678', 'PRUEBA', 'V-12345678', 'herrejose@gmail.com', '02423644304', '04124372322', 'DEBE COMPLETAR', '123', '7', '87', '278', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2018-09-15 04:49:29', '2026-06-15 17:21:36', 1, 'admin', '$2y$10$wTPSdxjrtgbpr3CDNdxYuexu.5u01BhxfH8GlHo9/oP61nUni/rVW', 'API_LIMP_67cf30d4ae5de', 1, 1, 'masculino', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(3, '15949430', 'JOSE HERRERA', 'V-15949430', 'jose@jesuministrosymas.com.ve', '04141448515', '02436721452', 'Maracay', 'Maracay', 'Aragua', 'MBI', 'Caña de Azucar', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2018-09-27 03:10:20', '2025-10-01 18:20:39', 1, 'admin', '2ee3c27d9ea2416f9279ec18117311a1', '', 1, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(4, '123456789', 'hector', 'hero', 'hectorlamaquina13@gmail.com', '0412555555', '', '', '', '', NULL, NULL, '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-17 14:47:06', '2026-06-16 17:42:05', 1, 'docente', '$2y$10$hvsDEj.qU9xqSAqzTJ8mguMGVi.KccLDwaMpY0nG2FyKuIURR5dKS', '', 1, 5, 'masculino', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(5, 'V-30692052', 'Hector', 'heroestudiante', 'heroestudiante@gmail.com', '0412555555', '', 'lol', NULL, 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'wayoyo', '03', 'frente al parque', '4', '2', 'Salario', 'Urbana', 'familiar', 'Ninguna', 'Ninguna', 'Bachiller', 'U.E Manuel Gual', '', '2025-06-17 16:07:22', '2026-03-02 14:41:56', 1, 'estudiante', '$2y$10$q3Jrf5ys6uo9CrkYscOfw.L5iydeKL94foqwatyGE96LFJGiLbobG', '', 1, 0, 'Masculino', 0, 'Soltero/a', '2004-04-14', '04124122996', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL),
(2372, 'V-28596315', 'Manuel Aponte Diaz Romero', 'V-154545454545', 'manuel@gmail.com', '04125555557', '04167777777', 'porai siuuuu', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-05-13 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '6917fc789d762d53c70bec13497c6921d189e0930ff7d3d99fe7a23d9fbd6884', NULL, 1, 0, 'Masculino', 0, 'Casado/a', '2000-07-22', '04167777777', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2377, 'V-11111111', 'Juan Sambrano', '12345610', 'juansambrano@gmail.com', '0412555555', '0416777777', 'jguyhfyt', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-07-03 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', '1bbd886460827015e5d605ed44252251', NULL, 1, 0, 'Masculino', 0, 'Soltero/a', '2000-07-19', '4568426513', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2378, 'V-29565454', 'Sara Miller', 'sara.miller', 'saramiller@gmail.com', '0412555777', '0416777555', 'hgytdrrt', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-19 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '29b3b2d836fbea2589c7383ae8bba39f', NULL, 1, 0, 'Femenino', 0, 'Soltero/a', '2003-06-19', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2379, 'V-30762211', 'Eliud Miguel Mendoza Perez', 'eliud.miguel.mendoza.perez', 'eliud@gmail.com', '7525254542', '5542643534', 'hgfsvsfr', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-19 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '478727529f93cfe6013d31fcc9773633', NULL, 1, 0, 'Masculino', 0, 'Soltero/a', '2004-10-06', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2449, '1', 'María González', 'maría.gonzález', 'maria.gonzalez@example.com', '2125551234', '4125551234', 'Calle 1 #23', 'Caracas', 'Distrito Capital', 'Libertador', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-01-15 04:00:00', '2025-10-23 15:10:03', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 0, 'Soltera', '1995-05-20', '2125551235', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2450, '2', 'Carlos López', 'carlos.lópez', 'carlos.lopez@example.com', '2125552345', '4125552345', 'Avenida 2 #45', 'Caracas', 'Distrito Capital', 'Libertador', 'San Agustín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-02-10 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Casado', '1990-08-15', '2125552346', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2451, '3', 'Ana Rodríguez', 'ana.rodríguez', 'ana.rodriguez@example.com', '2125553456', '4125553456', 'Calle 3 #67', 'Valencia', 'Carabobo', 'Valencia', 'Naguanagua', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-03-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Divorciada', '1988-11-25', '2125553457', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2452, '4', 'Luis Pérez', 'luis.pérez', 'luis.perez@example.com', '2125554567', '4125554567', 'Avenida 4 #89', 'Maracaibo', 'Zulia', 'Maracaibo', 'Coquivacoa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-04-20 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1993-07-10', '2125554568', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2453, '5', 'Sofía Martínez', 'sofía.martínez', 'sofia.martinez@example.com', '2125555678', '4125555678', 'Calle 5 #12', 'Barcelona', 'Anzoátegui', 'Simón Bolívar', 'El Carmen', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-05-15 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Casada', '1992-02-28', '2125555679', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2454, '6', 'Jorge Hernández', 'jorge.hernández', 'jorge.hernandez@example.com', '2125556789', '4125556789', 'Avenida 6 #34', 'Barquisimeto', 'Lara', 'Iribarren', 'Concepción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-06-10 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1994-09-15', '2125556790', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2455, '7', 'Isabel Díaz', 'isabel.díaz', 'isabel.diaz@example.com', '2125557890', '4125557890', 'Calle 7 #56', 'Mérida', 'Mérida', 'Libertador', 'Milla', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-07-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Soltera', '1991-12-05', '2125557891', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2456, '8', 'Pablo Sánchez', 'pablo.sánchez', 'pablo.sanchez@example.com', '2125558901', '4125558901', 'Avenida 8 #78', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'San Juan Bautista', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-08-20 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Casado', '1989-04-20', '2125558902', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2457, '9', 'Valeria Ramírez', 'valeria.ramírez', 'valeria.ramirez@example.com', '2125559012', '4125559012', 'Calle 9 #90', 'Ciudad Guayana', 'Bolívar', 'Caroní', 'Unare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-09-15 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Soltera', '1996-01-30', '2125559013', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2458, '10', 'Daniel Torres', 'daniel.torres', 'daniel.torres@example.com', '2125550123', '4125550123', 'Avenida 10 #11', 'Puerto La Cruz', 'Anzoátegui', 'Sotillo', 'Guanta', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-10-10 04:00:00', '2025-10-23 15:10:12', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 0, 'Divorciado', '1990-06-25', '2125550124', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2459, '11', 'Adriana Castro', 'adriana.castro', 'adriana.castro@example.com', '2125551122', '4125551122', 'Calle 11 #22', 'Maracay', 'Aragua', 'Girardot', 'Choroní', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-11-05 04:00:00', '2025-10-23 15:10:20', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 0, 'Casada', '1987-03-15', '2125551123', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2460, '12', 'Roberto Núñez', 'roberto.núñez', 'roberto.nunez@example.com', '2125552233', '4125552233', 'Avenida 12 #33', 'Barinas', 'Barinas', 'Barinas', 'Alto Barinas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-12-20 04:00:00', '2025-10-23 15:10:32', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 0, 'Soltero', '1995-10-10', '2125552234', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2461, '13', 'Gabriela Rojas', 'gabriela.rojas', 'gabriela.rojas@example.com', '2125553344', '4125553344', 'Calle 13 #44', 'Los Teques', 'Miranda', 'Guaicaipuro', 'Los Teques', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-01-15 04:00:00', '2025-10-23 15:10:54', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 0, 'Soltera', '1994-07-20', '2125553345', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2462, '14', 'Andrés Mendoza', 'andrés.mendoza', 'andres.mendoza@example.com', '2125554455', '4125554455', 'Avenida 14 #55', 'Punto Fijo', 'Falcón', 'Carirubana', 'Punto Fijo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-02-10 04:00:00', '2025-10-23 15:10:59', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 0, 'Casado', '1991-04-05', '2125554456', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2463, '15', 'Natalia Guzmán', 'natalia.guzmán', 'natalia.guzman@example.com', '2125555566', '4125555566', 'Calle 15 #66', 'Coro', 'Falcón', 'Colina', 'Coro', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-03-05 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Divorciada', '1989-11-30', '2125555567', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2464, '16', 'Fernando Herrera', 'fernando.herrera', 'fernando.herrera@example.com', '2125556677', '4125556677', 'Avenida 16 #77', 'San Fernando', 'Apure', 'San Fernando', 'San Fernando', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-04-20 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1993-08-15', '2125556678', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2465, '17', 'Carolina Silva', 'carolina.silva', 'carolina.silva@example.com', '2125557788', '4125557788', 'Calle 17 #88', 'La Victoria', 'Aragua', 'José Félix Ribas', 'La Victoria', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-05-15 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Casada', '1992-05-20', '2125557789', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2466, '18', 'Ricardo Peña', 'ricardo.peña', 'ricardo.pena@example.com', '2125558899', '4125558899', 'Avenida 18 #99', 'El Tigre', 'Anzoátegui', 'Simón Rodríguez', 'El Tigre', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-06-10 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1996-02-25', '2125558900', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2467, '19', 'Patricia Flores', 'patricia.flores', 'patricia.flores@example.com', '2125559900', '4125559900', 'Calle 19 #00', 'Acarigua', 'Portuguesa', 'Páez', 'Acarigua', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-07-05 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Soltera', '1990-09-10', '2125559901', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2468, '20', 'José Ruiz', 'josé.ruiz', 'jose.ruiz@example.com', '2125550011', '4125550011', 'Avenida 20 #11', 'Valera', 'Trujillo', 'Valera', 'Valera', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-08-20 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Casado', '1988-12-05', '2125550012', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2469, '21', 'Luisa Vargas', 'luisa.vargas', 'luisa.vargas@example.com', '2125551123', '4125551123', 'Calle 21 #22', 'Cabimas', 'Zulia', 'Cabimas', 'Cabimas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-09-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Divorciada', '1994-06-15', '2125551124', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2470, '22', 'Manuel Ortega', 'manuel.ortega', 'manuel.ortega@example.com', '2125552234', '4125552234', 'Avenida 22 #33', 'Carúpano', 'Sucre', 'Bermúdez', 'Carúpano', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-10-10 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1995-03-20', '2125552235', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2471, '23', 'Andrea Medina', 'andrea.medina', 'andrea.medina@example.com', '2125553345', '4125553345', 'Calle 23 #44', 'Porlamar', 'Nueva Esparta', 'Mariño', 'Porlamar', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-11-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Casada', '1991-10-25', '2125553346', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2472, '24', 'Diego Rivas', 'diego.rivas', 'diego.rivas@example.com', '2125554456', '4125554456', 'Avenida 24 #55', 'San Carlos', 'Cojedes', 'San Carlos', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-12-20 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1993-07-30', '2125554457', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2473, '25', 'Elena Cordero', 'elena.cordero', 'elena.cordero@example.com', '2125555567', '4125555567', 'Calle 25 #66', 'Tucupita', 'Delta Amacuro', 'Tucupita', 'Tucupita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-01-15 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Soltera', '1996-04-05', '2125555568', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2474, '26', 'Oscar Romero', 'oscar.romero', 'oscar.romero@example.com', '2125556678', '4125556678', 'Avenida 26 #77', 'La Grita', 'Táchira', 'Jáuregui', 'La Grita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-02-10 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Casado', '1989-01-10', '2125556679', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2475, '27', 'Vanessa Gil', 'vanessa.gil', 'vanessa.gil@example.com', '2125557789', '4125557789', 'Calle 27 #88', 'San Felipe', 'Yaracuy', 'San Felipe', 'San Felipe', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-03-05 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Divorciada', '1992-08-15', '2125557790', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2476, '28', 'Arturo Mora', 'arturo.mora', 'arturo.mora@example.com', '2125558890', '4125558890', 'Avenida 28 #99', 'San Juan de los Morros', 'Guárico', 'Roscio', 'San Juan', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-04-20 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1995-05-20', '2125558891', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2477, '29', 'Mariana León', 'mariana.león', 'mariana.leon@example.com', '2125559901', '4125559901', 'Calle 29 #00', 'San Antonio de Los Altos', 'Miranda', 'Los Salias', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-05-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 0, 'Casada', '1990-12-25', '2125559902', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2478, '30', 'Julio Espinoza', 'julio.espinoza', 'julio.espinoza@example.com', '2125550012', '4125550012', 'Avenida 30 #11', 'El Vigía', 'Mérida', 'Alberto Adriani', 'El Vigía', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-06-10 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 0, 'Soltero', '1994-09-30', '2125550013', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2527, 'V-15678901', 'Maríaa Gonzálezz', 'maríaa.gonzálezz', 'mgonzalez@example.com', '2125550101', '4125550101', 'Calle 1 #101', 'Caracas', 'Distrito Capital', 'Libertador', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'db0789017e0d5a2484886c25c7bbffd1', '', 1, 0, 'F', 0, 'soltera', '2000-05-20', '2125550102', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2528, 'E-20345678', 'Juan Pérez', 'juan.pérez', 'jperez@example.com', '2125550202', '4125550202', 'Avenida 2 #202', 'Caracas', 'Distrito Capital', 'Libertador', 'San Agustín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-10 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '6a37eebd4f766baee264c59ee1bbca02', '', 2, 0, 'M', 0, 'casado', '1999-11-15', '2125550203', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2529, 'V-17432109', 'Anaa Rodríguez', 'anaa.rodríguez', 'arodriguez@example.com', '2125550303', '4125550303', 'Calle 3 #303', 'Valencia', 'Carabobo', 'Valencia', 'San Blas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-20 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '89451e2737f7a3a6c46d060107dc708b', '', 1, 0, 'F', 0, 'soltera', '2001-02-28', '2125550304', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2530, 'E-18765432', 'Carloss López', 'carloss.lópez', 'clopez@example.com', '2125550404', '4125550404', 'Avenida 4 #404', 'Maracaibo', 'Zulia', 'Maracaibo', 'Juana de Ávila', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-05 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'b145ec79b1151099b9570d4e3b29aeca', '', 2, 0, 'M', 0, 'soltero', '2000-07-10', '2125550405', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2531, 'V-23456789', 'Laura Martínez', 'laura.martínez', 'lmartinez@example.com', '2125550505', '4125550505', 'Calle 5 #505', 'Barquisimeto', 'Lara', 'Iribarren', 'Concepción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-12 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '4428c6c474502e61151877825bb41961', '', 1, 0, 'F', 0, 'casada', '1999-09-25', '2125550506', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2532, 'E-19876543', 'Pedro Gómez', 'pedro.gómez', 'pgomez@example.com', '2125550606', '4125550606', 'Avenida 6 #606', 'Maracay', 'Aragua', 'Girardot', 'Choroní', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-18 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'dd26143c452d55054355fdbd5c92e398', '', 2, 0, 'M', 0, 'soltero', '2001-04-05', '2125550607', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2533, 'V-21567890', 'Sofía Hernández', 'sofía.hernández', 'shernandez@example.com', '2125550707', '4125550707', 'Calle 7 #707', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'La Concordia', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-22 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'f5ece76723ac1b6ae3bb9e99bbf26f68', '', 1, 0, 'F', 0, 'soltera', '2000-12-12', '2125550708', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2534, 'E-17654321', 'José Ramírez', 'josé.ramírez', 'jramirez@example.com', '2125550808', '4125550808', 'Avenida 8 #808', 'Barcelona', 'Anzoátegui', 'Simón Bolívar', 'El Carmen', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-30 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'e068ff48f0966deade935517d6b4686a', '', 2, 0, 'M', 0, 'casado', '1999-08-17', '2125550809', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2535, 'V-22345678', 'Isabel Torres', 'isabel.torres', 'itorres@example.com', '2125550909', '4125550909', 'Calle 9 #909', 'Mérida', 'Mérida', 'Libertador', 'Milla', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', '08e0750210f66396eb83957973705aad', '', 1, 0, 'F', 0, 'soltera', '2001-01-30', '2125550910', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2536, 'E-19456789', 'Miguel Díaz', 'miguel.díaz', 'mdiaz@example.com', '2125551010', '4125551010', 'Avenida 10 #1010', 'Ciudad Guayana', 'Bolívar', 'Caroní', 'Unare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '7df8c11bddbef2f19bb65c22b1d6c7e6', '', 2, 0, 'M', 0, 'soltero', '2000-06-22', '2125551011', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2537, 'V-20654321', 'Valentina Rojas', 'valentina.rojas', 'vrojas@example.com', '2125551111', '4125551111', 'Calle 11 #1111', 'Barinas', 'Barinas', 'Barinas', 'Alto Barinas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-20 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '0295896c168f4a350adf4cdf464198d7', '', 1, 0, 'F', 0, 'soltera', '2001-03-14', '2125551112', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2538, 'E-18543210', 'Daniel Castro', 'daniel.castro', 'dcastro@example.com', '2125551212', '4125551212', 'Avenida 12 #1212', 'Coro', 'Falcón', 'Colina', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-07-25 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '8249bfa20206fc926e206d9fad918ca1', '', 2, 0, 'M', 0, 'casado', '1999-10-08', '2125551213', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2539, 'V-21789012', 'Gabriela Mendoza', 'gabriela.mendoza', 'gmendoza@example.com', '2125551313', '4125551313', 'Calle 13 #1313', 'Puerto La Cruz', 'Anzoátegui', 'Sotillo', 'Los Taques', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-10 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'bb1426e76d77f79cc3e5ae1de1e024d6', '', 1, 0, 'F', 0, 'soltera', '2000-09-19', '2125551314', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2540, 'E-19876540', 'Andrés Silva', 'andrés.silva', 'asilva@example.com', '2125551414', '4125551414', 'Avenida 14 #1414', 'San Fernando', 'Apure', 'San Fernando', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd70e78c0ed5accbec273cea8884902ff', '', 2, 0, 'M', 0, 'soltero', '2001-05-25', '2125551415', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2541, 'V-22456789', 'Carolina Herrera', 'carolina.herrera', 'cherrera@example.com', '2125551515', '4125551515', 'Calle 15 #1515', 'Los Teques', 'Miranda', 'Guaicaipuro', 'Paracotos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-12 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'a8567e2d80e3d52ac3c81825d3b211fb', '', 1, 0, 'F', 0, 'casada', '1999-12-30', '2125551516', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2542, 'E-20765432', 'Ricardo Núñez', 'ricardo.núñez', 'rnunez@example.com', '2125551616', '4125551616', 'Avenida 16 #1616', 'Punto Fijo', 'Falcón', 'Carirubana', 'Amuay', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-20 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '5bd83db2c82e0eae9f59a479fc1d1bd1', '', 2, 0, 'M', 0, 'soltero', '2000-08-15', '2125551617', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2543, 'V-19345678', 'Patricia Vargas', 'patricia.vargas', 'pvargas@example.com', '2125551717', '4125551717', 'Calle 17 #1717', 'El Tigre', 'Anzoátegui', 'Simón Rodríguez', 'San José de Guanipa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-18 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '49cfc1380a9ce7380c9cc29813e3b326', '', 1, 0, 'F', 0, 'soltera', '2001-02-10', '2125551718', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2544, 'E-21654321', 'Roberto Medina', 'roberto.medina', 'rmedina@example.com', '2125551818', '4125551818', 'Avenida 18 #1818', 'Cúa', 'Miranda', 'Urdaneta', 'Cúa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-22 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'a52104978231c9a62c4e8a097922ddd9', '', 2, 0, 'M', 0, 'casado', '1999-07-05', '2125551819', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2545, 'V-18456789', 'Adriana Ríos', 'adriana.ríos', 'arios@example.com', '2125551919', '4125551919', 'Calle 19 #1919', 'Ocumare del Tuy', 'Miranda', 'Lander', 'Ocumare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-28 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', 'fe07e07d7cbbff8b42f6544553763d8a', '', 1, 0, 'F', 0, 'soltera', '2000-11-20', '2125551920', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2546, 'E-22567890', 'Fernando Guzmán', 'fernando.guzmán', 'fguzman@example.com', '2125552020', '4125552020', 'Avenida 20 #2020', 'La Victoria', 'Aragua', 'Jose Felix Ribas', 'La Victoria', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-12 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '9d610e830da7d54e118c00518d7a9b64', '', 2, 0, 'M', 0, 'soltero', '2001-04-15', '2125552021', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2547, 'V-19765432', 'Natalia Blanco', 'natalia.blanco', 'nblanco@example.com', '2125552121', '4125552121', 'Calle 21 #2121', 'San Juan de los Morros', 'Guárico', 'Roscio', 'San Juan', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-30 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '24288ed4283a8c2cc350f035337e84a7', '', 1, 0, 'F', 0, 'soltera', '2000-10-05', '2125552122', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2548, 'E-21456789', 'Eduardo Salas', 'eduardo.salas', 'esalas@example.com', '2125552222', '4125552222', 'Avenida 22 #2222', 'Valera', 'Trujillo', 'Valera', 'La Puerta', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-08 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'e275c58706ae71adb5bf8942eca845ba', '', 2, 0, 'M', 0, 'casado', '1999-09-12', '2125552223', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2549, 'V-18654321', 'Mariana Cordero', 'mariana.cordero', 'mcordero@example.com', '2125552323', '4125552323', 'Calle 23 #2323', 'Porlamar', 'Nueva Esparta', 'Maneiro', 'Pampatar', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '11c54f59eb081bcbbcfc65c8bd4772b8', '', 1, 0, 'F', 0, 'soltera', '2001-01-25', '2125552324', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2550, 'E-23567890', 'Jorge Paredes', 'jorge.paredes', 'jparedes@example.com', '2125552424', '4125552424', 'Avenida 24 #2424', 'Carúpano', 'Sucre', 'Bermúdez', 'Carúpano', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-30 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '9929b8ec6b8b4edfe2ab26c25b1e4a58', '', 2, 0, 'M', 0, 'soltero', '2000-07-18', '2125552425', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2551, 'V-20456789', 'Luisa Fuentes', 'luisa.fuentes', 'lfuentes@example.com', '2125552525', '4125552525', 'Calle 25 #2525', 'La Asunción', 'Nueva Esparta', 'Arismendi', 'La Asunción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-05 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'b13e714608fcd3f0fb7f936e1dbd5310', '', 1, 0, 'F', 0, 'casada', '1999-12-08', '2125552526', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2552, 'E-17654320', 'Manuel Alvarado', 'manuel.alvarado', 'malvarado@example.com', '2125552626', '4125552626', 'Avenida 26 #2626', 'Tucupita', 'Delta Amacuro', 'Tucupita', 'Tucupita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '7e6c056718d8497121412444db238f51', '', 2, 0, 'M', 0, 'soltero', '2001-05-30', '2125552627', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2553, 'V-22678901', 'Daniela Mora', 'daniela.mora', 'dmora@example.com', '2125552727', '4125552727', 'Calle 27 #2727', 'Santa Teresa del Tuy', 'Miranda', 'Independencia', 'Santa Teresa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-25 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'a0c79045aa1f687714256873c0d9fbde', '', 1, 0, 'F', 0, 'soltera', '2000-09-15', '2125552728', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2554, 'E-19543210', 'Antonio Peña', 'antonio.peña', 'apena@example.com', '2125552828', '4125552828', 'Avenida 28 #2828', 'San Felipe', 'Yaracuy', 'San Felipe', 'San Felipe', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '552b566abe41b5f4b7a328382eac6290', '', 2, 0, 'M', 0, 'casado', '1999-08-22', '2125552829', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2555, 'V-23678901', 'Verónica León', 'verónica.león', 'vleon@example.com', '2125552929', '4125552929', 'Calle 29 #2929', 'San Carlos', 'Cojedes', 'San Carlos', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd8b9bf41fc29d33ff8a0d642caf11247', '', 1, 0, 'F', 0, 'soltera', '2001-02-20', '2125552930', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2556, 'E-18765430', 'Oscar Rivas', 'oscar.rivas', 'orivas@example.com', '2125553030', '4125553030', 'Avenida 30 #3030', 'Achaguas', 'Apure', 'Achaguas', 'Achaguas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-20 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '8c5e3e201833318627b5a3a3d1fb0801', '', 2, 0, 'M', 0, 'soltero', '2000-06-12', '2125553031', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2557, 'V-24567890', 'Gladys Suárez', 'gladys.suárez', 'gsuarez@example.com', '2125553131', '4125553131', 'Calle 31 #3131', 'San Antonio del Táchira', 'Táchira', 'Bolívar', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-08 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'a7b69682aeedae2e01d506d05cef0933', '', 1, 0, 'F', 0, 'casada', '1999-11-25', '2125553132', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2558, 'E-21654320', 'Raúl Espinoza', 'raúl.espinoza', 'respinoza@example.com', '2125553232', '4125553232', 'Avenida 32 #3232', 'San Carlos de Zulia', 'Zulia', 'Mara', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-15 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '66f633b7a86d055da5b9f8b4c5aa172c', '', 2, 0, 'M', 0, 'soltero', '2001-03-10', '2125553233', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `users` (`id`, `idusuario`, `nombre`, `username`, `email`, `tlf`, `cel`, `direccion`, `ciudad`, `estado`, `municipio`, `parroquia`, `etnia`, `casaapto`, `punto_referencia`, `grupo_familiar`, `acargo_usted`, `fuente_ingresos`, `tipo_vivienda`, `tenencia_vivienda`, `enfermedad`, `discapacidad`, `titulos`, `institutos`, `potencialidades`, `fecha_ingreso`, `fecha_act`, `status`, `user_type`, `password`, `api_key`, `carrera`, `carrera_di`, `genero`, `embarazada`, `edo_civil`, `fecha_nac`, `num_telf_opc`, `sede`, `pais_titulo`, `legalizado_titulo`, `foto_perfil`, `usuario`, `estudiante`, `docente`, `admin`, `super_user`, `editar_user`, `editar_nota`, `editar_acceso`, `editar_valores`, `editar_estudiante`, `agregar_estudiante`, `agregar_docente`, `editar_docente`, `agregar_carrera`, `agregar_materia`, `editar_materia`, `pagos`, `auditoria`, `secciones`, `rela_materia_carrera`, `periodos_academicos`, `asig_secciones`, `asig_cursos`, `horarios`, `gestion_director_carrera`, `notas_cargadas`, `consultar_notas`, `consultar_notas_pasadas`, `tipos_pago`, `tipos_horario`, `horario_personal`, `respaldo_bd`, `gestionar_carrera`, `gestion_periodo_academico`, `gestion_asig_cursos`, `gestion_horario`, `titulos_re_materia`, `grado`, `gestion_grado`, `vocero`, `visita`) VALUES
(2559, 'V-19567890', 'Teresa Acosta', 'teresa.acosta', 'tacosta@example.com', '2125553333', '4125553333', 'Calle 33 #3333', 'Upata', 'Bolívar', 'Piar', 'Upata', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd6d77546e16bffd6c9768db15103139f', '', 1, 0, 'F', 0, 'soltera', '2000-10-30', '2125553334', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2560, 'E-22789012', 'Alberto Márquez', 'alberto.márquez', 'amarquez@example.com', '2125553434', '4125553434', 'Avenida 34 #3434', 'Guasdualito', 'Apure', 'Páez', 'Guasdualito', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-22 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', 'd84de70c483dc10e4d05955c5e6c864c', '', 2, 0, 'M', 0, 'casado', '1999-07-15', '2125553435', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2561, 'V-20654320', 'Yolanda Cárdenas', 'yolanda.cárdenas', 'ycardenas@example.com', '2125553535', '4125553535', 'Calle 35 #3535', 'Carora', 'Lara', 'Torres', 'Carora', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-05 04:00:00', '2025-10-01 18:20:40', 1, 'estudiante', 'd6bde420f4c5b1e80215fb12fbb8a267', '', 1, 0, 'F', 0, 'soltera', '2001-01-10', '2125553536', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2562, 'E-17654329', 'Francisco Parra', 'francisco.parra', 'fparra@example.com', '2125553636', '4125553636', 'Avenida 36 #3636', 'La Grita', 'Táchira', 'Jáuregui', 'La Grita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-30 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'd9d1ca317c8468142d784f3569fff65c', '', 2, 0, 'M', 0, 'soltero', '2000-05-25', '2125553637', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2563, 'V-23789012', 'Leticia Romero', 'leticia.romero', 'lromero@example.com', '2125553737', '4125553737', 'Calle 37 #3737', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'San Juan Bautista', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-18 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '7d6afc7443c6f54340b730698e04688a', '', 1, 0, 'F', 0, 'casada', '1999-12-15', '2125553738', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2564, 'E-19876532', 'Arturoo Mora', 'arturoo.mora', 'amora@example.com', '2125553838', '4125553838', 'Avenida 38 #3838', 'San Joaquín', 'Carabobo', 'San Joaquín', 'San Joaquín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-28 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '9cb1f9517363737ed3a32082ec88fe93', '', 2, 0, 'M', 0, 'soltero', '2001-04-20', '2125553839', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2565, 'V-24678901', 'Beatriz Rangel', 'beatriz.rangel', 'brangel@example.com', '2125553939', '4125553939', 'Calle 39 #3939', 'San Mateo', 'Aragua', 'Bolívar', 'San Mateo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-10 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '2da3acf9de8c82b1fd4c40d0f85a59fd', '', 1, 0, 'F', 0, 'soltera', '2000-08-05', '2125553940', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2566, 'E-21567890', 'Héctor Zambrano', 'héctor.zambrano', 'hzambrano@example.com', '2125554040', '4125554040', 'Avenida 40 #4040', 'San José de Guanipa', 'Anzoátegui', 'Simón Rodríguez', 'San José de Guanipa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'f5ece76723ac1b6ae3bb9e99bbf26f68', '', 2, 0, 'M', 0, 'casado', '1999-09-30', '2125554041', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2567, 'V-18543219', 'Diana Contreras', 'diana.contreras', 'dcontreras@example.com', '2125554141', '4125554141', 'Calle 41 #4141', 'San Antonio de Los Altos', 'Miranda', 'Los Salias', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-22 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '66a65afa7f551b2197845c4ad1754889', '', 1, 0, 'F', 0, 'soltera', '2001-02-15', '2125554142', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2568, 'E-22678901', 'José Gregorio Peñalver', 'josé.gregorio.peñalver', 'jpenalver@example.com', '2125554242', '4125554242', 'Avenida 42 #4242', 'Sanare', 'Lara', 'Jiménez', 'Sanare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-28 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'a0c79045aa1f687714256873c0d9fbde', '', 2, 0, 'M', 0, 'soltero', '2000-07-08', '2125554243', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2569, 'V-19765430', 'Rosaura Velásquez', 'rosaura.velásquez', 'rvelasquez@example.com', '2125554343', '4125554343', 'Calle 43 #4343', 'Quíbor', 'Lara', 'Jiménez', 'Quíbor', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-15 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '0f05d09833979bbf49c467800c9f7631', '', 1, 0, 'F', 0, 'casada', '1999-11-20', '2125554344', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2570, 'E-24789012', 'Alfredo Delgado', 'alfredo.delgado', 'adelgado@example.com', '2125554444', '4125554444', 'Avenida 44 #4444', 'San Juan de Colón', 'Táchira', 'Ayacucho', 'San Juan de Colón', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-12 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', '0b4954ba6a5e405ad4ed717f14c72764', '', 2, 0, 'M', 0, 'soltero', '2001-03-25', '2125554445', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2571, 'V-21654329', 'Gisela Ferrer', 'gisela.ferrer', 'gferrer@example.com', '2125554545', '4125554545', 'Calle 45 #4545', 'San Luis', 'Falcón', 'Federación', 'San Luis', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-10 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'fcd54cbd301c33304cab2820a6e7a553', '', 1, 0, 'F', 0, 'soltera', '2000-09-30', '2125554546', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2572, 'E-18654320', 'René Márquez', 'rené.márquez', 'rmarquez@example.com', '2125554646', '4125554646', 'Avenida 46 #4646', 'San Francisco', 'Zulia', 'San Francisco', 'San Francisco', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-05 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '92f34bbe48e19810ec7e9f232e35e309', '', 2, 0, 'M', 0, 'casado', '1999-08-10', '2125554647', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2573, 'V-25678901', 'Marisol Rivas', 'marisol.rivas', 'mrivas@example.com', '2125554747', '4125554747', 'Calle 47 #4747', 'San Simón', 'Zulia', 'Francisco Javier Pulgar', 'San Simón', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-22 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '16ad8043ae4b9817b7409e6e7fb90dc3', '', 1, 0, 'F', 0, 'soltera', '2001-01-15', '2125554748', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2574, 'E-20765431', 'Wilmer Castillo', 'wilmer.castillo', 'wcastillo@example.com', '2125554848', '4125554848', 'Avenida 48 #4848', 'San Pablo', 'Zulia', 'Almirante Padilla', 'San Pablo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '15314e6f381ff9b044d7eb8595636fbe', '', 2, 0, 'M', 0, 'soltero', '2000-04-28', '2125554849', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2575, 'V-22789013', 'Yusmery Del Moral', 'yusmery.del.moral', 'ydelmoral@example.com', '2125554949', '4125554949', 'Calle 49 #4949', 'San Rafael del Moján', 'Zulia', 'Almirante Padilla', 'San Rafael', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-18 04:00:00', '2025-10-01 18:20:40', 1, 'estudiante', 'efe9efc9276e537d2f1450885df651b3', '', 1, 0, 'F', 0, 'casada', '1999-10-12', '2125554950', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2576, 'E-23678902', 'Richard Briceño', 'richard.briceño', 'rbriceno@example.com', '2125555050', '4125555050', 'Avenida 50 #5050', 'San Timote', 'Zulia', 'Baralt', 'San Timote', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-30 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'c146a97d5173f9f25c8fb142cf207ecd', '', 2, 0, 'M', 0, 'soltero', '2001-05-05', '2125555051', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2584, 'V-14123524', 'Manuel Turiso', 'manuel.turiso', 'kol@gmail.com', '0412777777', '0412777777', 'qedwq', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '1', '0', '1', 'Casa', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:35', 1, 'docente', '$2y$10$RxKomMmQumrSU9DFowD7mOriXhK6oOW/GYMLm6DvO7NSJQsPh/wiS', '7e140884c26c97f6f6bcce3a20b0a2c3', 0, 0, 'Masculino', 0, '2', '2000-03-09', '', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2585, 'V-13123524', 'Alberto Lopez', 'alberto.lopez', 'zol@gmail.com', '0412777777', '0412777777', 'mjdncja', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '2', '0', '1', 'Apartamento', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:27', 1, 'docente', '$2y$10$hXIRvrslTjCvVisOvsBMl.iNHitesSiFKTolJ5KObfnr6oCk3NwpC', 'af2c2755c1f3498a955651ad7dcc156a', 0, 0, 'Masculino', 0, '2', '1991-07-11', '', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2586, 'E-12569002', 'Francisco Torrealba', 'francisco.torrealba', 'pol@gmail.com', '0412777777', '0412777777', 'jdNJDSANJ', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '2', '0', '2', 'Casa', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:31', 1, 'docente', '$2y$10$JkE3FtgVlymcKJRtI4w6CeecP8Dk93HQO59D6CwgFGeKgBYsDuUKy', '0cc8939b4524580c7589a64aa3e59ae9', 0, 0, 'Masculino', 0, '2', '1991-03-13', '', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2588, 'V-24765890', 'Sarsamora Vegano', 'sarsamora.vegano', 'rol@gmail.com', '0412777777', '0412777777', 'siuuuuu', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '3', '0', '', 'Apartamento', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:38', 1, 'docente', '$2y$10$xgVIJqKbEPm/HJTfyUx5/.xF9YGPlLFioOENtL4gjqfDB13ybb8h2', '226af57221e592d91a033ecc16491a1d', 0, 0, 'Femenino', 0, '2', '1988-07-13', '', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2589, 'V--21456555', 'Palmera Kazekage', 'palmera.kazekage', 'kazekage@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-24 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'b71219d2ea11fb066d298edbadf67b19', '', 1, 0, 'Masculino', 0, 'Soltero', '2000-06-15', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2590, 'V--24648009', 'Francisco Mendoza', 'francisco.mendoza', 'rolllll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-31 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', '3f718eb49861ad69bd0ddaa7c94974c9', '', 1, 0, 'Masculino', 0, 'Casado', '1989-07-19', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2591, 'V-32567456', 'Claudia Lopez', 'claudia.lopez', 'kollllll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-31 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '377d6bc1b54ba0f6d729651c9195c205', '', 1, 0, 'Femenino', 0, 'Casado', '2006-07-13', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2592, 'V-54678943', 'Jose Manuel', 'jose.manuel', 'ggol@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '92fa6a601065ef1d62cf229a40642da1', '', 1, 0, 'Masculino', 0, 'Soltero', '2002-06-13', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2593, 'V--45324567', 'Jose Manuel Lopez', 'jose.manuel.lopez', 'rrollll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '3fe63d34589ba217e4824534c4582578', '', 2, 0, 'Masculino', 0, 'Casado', '1995-07-13', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2594, 'V--21456565', 'Maria Antonieta', 'maria.antonieta', 'mariaantonieta@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '78f0a70fb42e54b223544eec88e2d052', '', 1, 0, 'Femenino', 0, 'Soltero', '2001-07-19', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2595, 'V--34678324', 'Sofia Fernandez', 'sofia.fernandez', 'sofilol@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '97f1db41887d87caa54e276ad7b2c312', '', 1, 0, 'Masculino', 0, 'Casado', '1998-06-10', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2596, 'V--20456543', 'Hector Gutierrez', 'hector.gutierrez', 'hectorgu@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'd5d6bb5424a9d4f9dc9c1092477fdfc3', '', 1, 0, 'Masculino', 0, 'Casado', '2001-03-08', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2597, 'V--36789546', 'Luis Aguilar', 'luis.aguilar', 'luisaguila@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '3e6d29ef91fedd06772de7b754316a2a', '', 5, 0, 'Masculino', 0, 'Soltero', '2007-06-28', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2598, 'V--31789321', 'Laura Colores', 'laura.colores', 'lauracolores@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '8f467697120171c90181e9a3241fd529', '', 5, 0, 'Masculino', 0, 'Casado', '2003-10-17', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2599, 'V--31789324', 'Laura Coloress', 'laura.coloress', 'lauracolores2@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '6b2af18350070cc63a2cf6988b872f38', '', 5, 0, 'Masculino', 0, 'Casado', '2003-10-17', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2600, 'V--12345677', 'Anabelle Carroza', 'anabelle.carroza', 'carroza@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '02b89b15f7210b47c94e79f08f62704a', '', 5, 0, 'Masculino', 0, 'Casado', '2004-07-15', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2601, 'E--34511211', 'Manuel Turisooo', 'manuel.turisooo', 'turisoo@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '8059f1d1a0accf2c3aa27dd0c89dfb0f', '', 1, 0, 'Masculino', 0, 'Casado', '2001-12-13', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2602, 'E--34678900', 'Carlos Humberto Morales', 'carlos.humberto.morales', 'calos@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '777419bcad989fde187a64f51be7b4ea', '', 5, 0, 'Masculino', 0, 'Soltero', '2001-07-18', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2603, 'E--30567435', 'Manteca De Colesterol', 'manteca.de.colesterol', 'manteca@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '8f908f3eb2d5f7305f17fa9837f591f6', '', 1, 0, 'Masculino', 0, 'Casado', '2004-07-16', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2604, 'V--21456544', 'Jose Manuel Lopezz', 'jose.manuel.lopezz', 'pgol@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '37ce9255f0c8e6dfca1e811959ead689', '', 1, 0, 'Masculino', 0, 'Casado', '2000-07-12', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2607, 'V-12345678', 'Nombre Ejemplo', 'nombre.ejemplo', 'ejemplo@correo.com', '02121234567', '04141234567', 'Dirección Ejemplo', 'Caracas', 'Distrito Capital', 'Libertador', 'La Candelaria', '', 'Casa', 'Frente a la plaza', '4', '2', 'Trabajo formal', 'Casa', 'Propia', 'Ninguna', 'No especificado', 'Bachiller,Licenciatura', 'Liceo XYZ,Universidad ABC', '', '2023-01-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '25d55ad283aa400af464c76d713c07ad', '', 1, NULL, 'Masculino', 0, 'Soltero', '1990-01-01', '02121234568', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2608, 'E-8549625', 'bhuftyfu', 'bhuftyfu', 'frthft@gmail.com', '0412555777', '', 'guygyh', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-10-01 04:00:00', '2025-10-23 14:25:07', 1, 'estudiante', '92a0159e815657aeab28ac8a935cf1ca', '', 1, NULL, 'Masculino', 0, 'Soltero', '1979-07-19', '', NULL, NULL, NULL, NULL, '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2609, 'V-12345555', 'Perdomo Albañil', 'perdomo.albañil', 'perdomo@gmail.com', '0412555555', '0412555555', 'rdrhv', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'frente a una farmacia', '2', '0', '1', 'Apartamento', 'Familiar', '', 'no', '', '', 'esfdfs', '2025-10-02 04:00:00', '2025-10-23 17:26:26', 1, 'docente', '$2y$10$8lPuQS3UuMISfjSY7Cwuc.QToyk85yB/Nz3MfIXd13zi4705M6ivC', '97610812c565e1a74c3478ae6bc6c099', 0, NULL, 'Masculino', 0, '1', '1992-06-11', '', NULL, NULL, NULL, NULL, '0', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2610, 'V-30123456', 'alberto guerra', 'alberto.guerra', 'infos@guerra.com', '0416598362', '0416777777', 'prueba', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', 'Ninguna', 'Apartamento', 'frente a un campo', '6', '2', '2', '', 'Alquilada', 'no', 'No', '', '', '', '2025-11-24 04:00:00', '2025-11-25 15:28:10', 1, 'estudiante', '$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G/ryY0C8xK4T.', '', 1, NULL, 'Masculino', 0, 'Casado', '1975-06-12', '04163333333', NULL, NULL, NULL, '69247f8403325_1763999620.png', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2615, 'V-30123123', 'O\'Connor', 'o.connor', 'validacion@example.com', '0412555777', '0416777777', 'kvftfvgghjkf', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'Apartamento', 'frente a una farmacia', '3', '2', '1', '', 'Alquilada', 'no', 'No', '', '', '', '2025-12-03 04:00:00', '2025-12-03 17:03:32', 1, 'estudiante', '$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.', '854d0aa4bec27560ebb7550a3f9600a4', 1, 1, 'Masculino', 0, 'Divorciado', '2002-06-19', '04163333333', NULL, NULL, NULL, 'foto_69305f0b6200d9.46312539.jpeg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2616, 'E-30123458', 'Luis Miguel', 'luis.miguel', 'luismiguell@gmail.com', '02423644305', '0416777775', 'porai', '87', '7', '87', '275', '', 'Casa', 'frente a un campo', '2', '2', '1', '', 'Propia', 'no', 'No', '', '', '', '2026-02-10 04:00:00', '2026-02-10 18:55:17', 1, 'estudiante', '$2y$10$FWB2IFV68cufx1b50aw0c.DHQVyvJCAVG.E63gWydrFf6c9ku5JX2', 'dfeac3c02b604bcae12167a29e71a819', 5, 5, 'Masculino', 0, 'Soltero', '1995-10-13', '04163333335', NULL, NULL, NULL, '', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2617, 'V-54123456', 'una pruba', 'V-54123456', 'pruebasuper@gmail.com', '0412555777', '0416777777', 'porai', '87', '7', '87', '275', '', 'Casa', 'frente a un campo', '2', '1', '1', '', 'Familiar', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:35:36', 1, 'estudiante', '$2y$10$xF2bRQQe5nPh1YYH8hqVaeENeVVFDUmWxbWMqtH6YAbgwe4IVPpIO', '0037221a6e5888dd5951f6c2f64301a6', 5, 5, 'Masculino', 0, 'Casado', '2000-06-17', '', NULL, NULL, NULL, '', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2618, 'V-53123456', 'Otra Prueba', 'V-53123456', 'otraprueba@gmail.com', '0412555777', '0416777777', 'lol', '87', '7', '87', '275', '', 'Otro', 'frente a una farmacia', '1', '0', '1', '', 'Otro', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:41:38', 1, 'estudiante', '$2y$10$vkv7JDi8xArzlGWqfXGffOkQS/JbcnpkGpQ0L55Ll4Opr4rWXr93.', '41681b0b80142eed810d89f8c60e2f1b', 5, 5, 'Masculino', 0, 'Casado', '2003-02-07', '04163333333', NULL, NULL, NULL, 'foto_699c59127213f0.57745891.jpg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2619, 'V-98123456', 'Diosito Otra Prueba', 'V-98123456', 'ooomaga@gmail.com', '0412555777', '0416777777', 'lol', '87', '7', '87', '275', '', 'Apartamento', 'yuk', '2', '1', '3', '', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:45:21', 1, 'estudiante', '$2y$10$.nHV1hMcXMDJMK8i/YQ46OFQS5diOIRpVv1ou4MCpuYIUj1rsuRNy', 'b221bfdb70f4db19841cc369b2ff94d2', 5, 5, 'Masculino', 0, 'Soltero', '2000-03-17', '', NULL, NULL, NULL, 'foto_699c59f1d0d921.96633581.png', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2620, 'V-45123456', 'Papadio Super Prueba', 'V-45123456', 'diosito@gmail.com', '02423644304', '0416777777', 'porai', '87', '7', '87', '275', '', 'Apartamento', 'frente a un campo', '2', '0', '1', '', 'Familiar', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:47:58', 1, 'estudiante', '$2y$10$T1Me3Locj9qc7ZwNSavxheUEIaENCoyD0veQxYOM2y6BTuw4ffdY2', '6ad958f6ff0b729615f6da629362ccc9', 5, 5, 'Masculino', 0, 'Casado', '2004-11-12', '', NULL, NULL, NULL, 'foto_699c5a8e810181.58284137.jpg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2623, 'E12345677', 'Preinscripcion Prueba de nuevo', 'E12345677', 'preinscripcionn@gmail.com', '04122222222', '04167777777', 'fdzbfdzv', '11', '2', '11', '31', '', 'No especificado', 'frente a un parque', '4', '0', '1', 'Apartamento', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'gwfwe', '2026-05-10 04:00:00', '2026-05-10 20:10:45', 0, 'estudiante', '$2y$10$v0GoJDHVYwQfcLDbPeCsvuwPGXVjwFk3CwlDfstCCbf080dxFxAP6', '7c7ae1cc743a577f1939327542e7a9d7', 1, 1, 'Masculino', 0, 'Casado', '1998-06-11', '', NULL, NULL, NULL, 'foto_6a00da2dba0655.11250450.jpg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2624, 'E12345654', 'Peru Es Clave', 'E12345654', 'preinscripc@gmail.com', '04122222222', '04167777777', 'iuohcous', '13', '2', '13', '36', '', 'No especificado', 'frente a un parque', '4', '0', '2', 'Otro', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'mbdcqiuhndxoueq', '2026-05-10 04:00:00', '2026-05-10 21:54:20', 0, 'estudiante', '$2y$10$3YYp4ZKHDXLqsgw2q3CFgucaE2dGoienspB.ZcJVASSfpZEfmtqg2', '6bea4888daf05b4bb4e7aa61dd06c010', 1, 1, 'Masculino', 0, 'Casado', '1999-07-08', '', NULL, NULL, NULL, 'foto_6a00fdf5dd3636.49124688.jpg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2625, 'E87654567', 'Pedro Pepe Perozo Palomo', 'E87654567', 'preinscripdwfewc@gmail.com', '04122222222', '04167777777', '12345', '15', '2', '15', '41', '', 'No especificado', 'frente a un parque', '4', '0', '1', 'Casa', 'Familiar', '', '', 'Bachiller', 'U.E Manuel Gual', 'lol', '2026-05-10 04:00:00', '2026-05-10 21:54:40', 0, 'estudiante', '$2y$10$8IllfyEy2yYrivNebpnUCefSwCxIqO1KnO0SYCdsObZ32Si0t.iXm', 'a46fdae73e8bdccaf0a9e2122eff2136', 1, 1, 'Masculino', 0, 'Casado', '2000-07-13', '', NULL, NULL, NULL, 'foto_6a00fe55ecd529.10523157.jpg', '0', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2628, 'E46598763', 'prueba de inscripcion notas', 'E46598763', 'progral_estudios@uptpc.edu.ve', '0412555777', '0416777777', 'hjhjyj', '19', '2', '19', '52', '', 'No especificado', 'frente a una farmacia', '6', '2', '2', 'Casa', 'Familiar', '', '', 'TSU Informatica', 'U.E Freancis de Miranda', 'estresarse', '2026-05-11 04:00:00', '2026-05-11 18:40:53', 1, 'estudiante', '$2y$10$zJylnP6mGB3mey.XPKxEe.kaxAcrAZjpgZT37gTHwaAmKh8CtcJFW', '266263180f5ada3c39a0011cef046bfe', 1, 1, 'Masculino', 0, 'Casado', '1991-07-26', '', NULL, NULL, NULL, 'foto_6a01da5fcc9bd6.89927752.jpg', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2629, 'E98653265', 'prueba porsiacaso', 'E98653265', 'os@uptpc.edu.ve', '04124122996', '0416777777', 'dsfdw', '390', '21', '390', '985', '', 'No especificado', 'frente a un campo', '5', '3', '1', '', 'Alquilada', 'Hipertension', 'Motora', 'Bachiller', 'U.E Freancis de Miranda', 'fgafrefgaer', '2026-05-14 04:00:00', '2026-05-20 18:02:49', 1, 'estudiante', '$2y$10$m21KGFybLe/J5cZQcwPujOTyfa9.slLzdj/BivXLbvRnpxGpksu6K', '5603c0fe875007f0533fd31f74b7c50f', 1, 1, 'Masculino', 0, 'Divorciado', '1995-03-09', '', 'Puerto Cabello', NULL, NULL, 'foto_6a05e7c08bf451.47831637.jpeg', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2630, 'E14725836', 'prueba de planilla', 'E14725836', 'progstudios@uptpc.edu.ve', '02423644304', '0416777777', 'ghfxfg', '84', '7', '84', '272', 'Añu', 'No especificado', 'frente a una farmacia', '5', '2', '2', 'Casa', 'Propia', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'dormir', '2026-05-13 04:00:00', '2026-05-27 15:17:16', 1, 'estudiante', '$2y$10$qu93GOSpiyyORlJyTb6B.O6Hk77HlBCSGFs00uX0T5XmsCvl2LtoS', '51c4742afc50f8a54ed2d7b19a5e05f8', 1, 1, 'Femenino', 1, 'Casado', '1999-06-18', '', 'COEF', NULL, NULL, 'foto_6a04843f0601e8.38144014.png', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL),
(2631, 'V45678932', 'prueba titulo pais', 'V45678932', 'progrdrghos@uptpc.edu.ve', '02423644304', '0416777777', 'gfdghr', '18', '2', '18', '51', 'Wayuu', 'No especificado', 'yuk', '4', '2', '1', 'Apartamento', 'Propia', 'Hipertension', 'Motora', 'Bachiller|||TSU Informatica', 'U.E Freancis de Miranda|||sdsd', 'comer', '2026-05-20 04:00:00', '2026-05-20 15:53:06', 1, 'estudiante', '$2y$10$l/82cvD1O8wOKZpSh1mcBuSgi2g.nIEpURpNBmsMbZTF/sipIuaLO', 'e80d3afd2540517e9ab37b58ba3bba19', 1, 1, 'Femenino', 1, 'Casado', '2003-06-06', '04163333333', 'COEF', NULL, NULL, 'foto_6a0dd8241e9392.46027094.png', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2632, 'E14725834', 'prueba de planilla dos punto cero', 'E14725834', 'progss@uptpc.edu.ve', '02423644304', '0416777777', 'ghfxfg', '84', '7', '84', '272', 'Añu', 'No especificado', 'frente a una farmacia', '5', '1', '2', 'Casa', 'Familiar', 'Hipertension', 'Motora', 'TSU Informatica', 'U.E Freancis de Miranda', 'dormir', '2026-05-13 04:00:00', '2026-05-20 16:14:05', 1, 'estudiante', '$2y$10$1i37ypgo17/LBfXZf8O1V.novcg4WQtUlprcIZm2NHjPMTxsaftFW', '241b4cf9a6ea5bf2cf315940d3f441ef', 1, 1, 'Femenino', 1, 'Casado', '1999-06-18', '', 'COEF', 'Otro', 'Sí', 'foto_6a0484f8039b01.77431194.png', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2633, 'V33058485', 'Giménez Tovar José David ', 'V33058485', 'josedavid@gmail.com', '04120352159', '04120352159', 'Mi casa', '87', '7', '87', '275', '', 'No especificado', 'Un árbol al frente ', '4', '0', '3', 'Casa', 'Familiar', '', '', 'Bachiller', 'Fortín Solano', 'Se jugar béisbol ', '2026-05-27 04:00:00', '2026-05-27 15:48:24', 1, 'estudiante', '$2y$10$TaEho3IP/S.U81hINFZqdu.KrPfzl6sNl9XkXkb7hzBvsnuxl4NFK', '1d64a7f6d972d95b703a8a56d79bafde', 1, 1, 'Masculino', 0, 'Soltero', '2008-05-16', '', 'Puerto Cabello', '', '', '', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2634, 'V30692052', 'Falso Hector', 'V30692052', 'falsohector@prueba.com', '02423644304', '0416777777', 'gkyhugikyg', '11', '2', '11', '31', '', 'No especificado', 'frente a un campo', '9', '8', '1', 'Casa', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', 'lol', '2026-06-16 04:00:00', '2026-06-16 16:57:38', 1, 'estudiante', '$2y$10$6u8MZ7WySc5tHYnkIvqAqulSwbtp2DMhDU3aM1Th.S4eF/zk83/eO', '956f680922875f69a1b660f37a998046', 1, 1, 'Masculino', 0, 'Soltero', '2003-03-07', '', 'Puerto Cabello', 'Venezuela', '', 'foto_6a31801e56da26.56909752.jpeg', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2636, 'V30692053', 'Falso Hector lol', 'V30692053', 'hectorlamaquina14@gmail.com', '02423644304', '0416777777', 'gkyhugikyg', '30', '3', '30', '87', '', 'No especificado', 'frente a un campo', '4', '3', '2', 'Apartamento', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', 'lol', '2026-06-16 04:00:00', '2026-06-16 17:42:28', 1, 'estudiante', '$2y$10$8DFH43t3NHIVqO419AK9E.Aw7e0VeckrCckoAedh4eqtIvFk1xsKC', '1e1e73a5f11d1f099fabd4f4640c1579', 5, 5, 'Masculino', 0, 'Soltero', '2003-03-08', '', 'Puerto Cabello', 'Venezuela', '', 'foto_6a3181de6eb3a5.11987768.jpeg', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);

--
-- Disparadores `users`
--
DELIMITER $$
CREATE TRIGGER `actualizar_total_usuarios` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE total INT$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_types`
--

CREATE TABLE `user_types` (
  `id` int NOT NULL,
  `user_type` varchar(11) COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(50) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `user_types`
--

INSERT INTO `user_types` (`id`, `user_type`, `descripcion`) VALUES
(1, 'user', 'Usuario'),
(2, 'admin', 'Administrativo'),
(3, 'estudiante', 'estudiante'),
(4, 'docente', 'docente'),
(5, 'super_user', 'Super_usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_user_types`
--

CREATE TABLE `user_user_types` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_cursos`
--

CREATE TABLE `usuarios_cursos` (
  `id` int NOT NULL,
  `nro_identificacion` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `pais` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `whatsapp` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `telegram` varchar(30) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `version_materia`
--

CREATE TABLE `version_materia` (
  `id` int NOT NULL,
  `id_version` int NOT NULL,
  `id_materia` int NOT NULL,
  `semestre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `version_materia`
--

INSERT INTO `version_materia` (`id`, `id_version`, `id_materia`, `semestre`) VALUES
(2, 2, 29, 1),
(3, 2, 30, 1),
(4, 2, 30, 1),
(5, 3, 31, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `ip` varchar(15) COLLATE latin1_spanish_ci NOT NULL,
  `fecha_visita` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `web` varchar(100) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `visitas`
--

INSERT INTO `visitas` (`id`, `id_usuario`, `ip`, `fecha_visita`, `web`) VALUES
(1, 2, '::1', '2025-11-17 16:41:21', 'index.php'),
(2, 2, '::1', '2025-11-17 16:42:47', 'index.php'),
(3, 2, '::1', '2025-11-17 16:42:48', 'mensajeria.php'),
(4, 2, '::1', '2025-11-17 16:44:58', 'mensajeria.php'),
(5, 2, '::1', '2025-11-17 16:45:02', 'mensajeria.php'),
(6, 2, '::1', '2025-11-17 16:45:25', 'mensajeria.php'),
(7, 2, '::1', '2025-11-17 16:45:26', 'estudiantes.php'),
(8, 2, '::1', '2025-11-17 16:46:01', 'estudiantes.php'),
(9, 2, '::1', '2025-11-17 16:55:19', 'index.php'),
(10, 2, '::1', '2025-11-17 16:55:21', 'registro_pagos.php'),
(11, 2, '::1', '2025-11-17 16:55:56', 'agregar_estudiante.php'),
(12, 2, '::1', '2025-11-17 16:56:21', 'gestion_seccion.php'),
(13, 2, '::1', '2025-11-17 16:56:54', 'grado.php'),
(14, 2, '::1', '2025-11-17 16:57:24', 'agregar_carrera.php'),
(15, 2, '::1', '2025-11-17 16:59:21', 'materia.php'),
(16, 2, '::1', '2025-11-17 16:59:27', 'carrera_materias.php'),
(17, 2, '::1', '2025-11-17 16:59:37', 'periodos_academicos.php'),
(18, 2, '::1', '2025-11-17 17:00:17', 'add_docente.php'),
(19, 2, '::1', '2025-11-17 17:00:50', 'asignar_secciones.php'),
(20, 2, '::1', '2025-11-17 17:01:17', 'asignacion_cursos.php'),
(21, 2, '::1', '2025-11-17 17:02:18', 'horarios_docentes.php'),
(22, 2, '::1', '2025-11-17 17:02:48', 'directores_carrera.php'),
(23, 2, '::1', '2025-11-17 17:03:16', 'admin_notas_pendientes.php'),
(24, 2, '::1', '2025-11-17 17:04:08', 'consulta_notas.php'),
(25, 2, '::1', '2025-11-17 17:04:35', 'notas_pasadas.php'),
(26, 2, '::1', '2025-11-17 17:05:01', 'correccion_notas.php'),
(27, 2, '::1', '2025-11-17 17:05:49', 'auditoria.php'),
(28, 2, '::1', '2025-11-17 17:06:33', 'respaldo_bd.php'),
(29, 2, '::1', '2025-11-17 17:07:05', 'titulos_relaciones_materias.php'),
(30, 2, '::1', '2025-11-17 17:07:05', 'titulos_relaciones_materias.php'),
(31, 2, '::1', '2025-11-17 17:07:05', 'titulos_relaciones_materias.php'),
(32, 2, '::1', '2025-11-17 17:08:04', 'editar_accesos.php'),
(33, 2, '::1', '2025-11-17 17:08:27', 'valores_predefinidos.php'),
(34, 2, '::1', '2025-11-17 17:08:49', 'tipo_pago.php'),
(35, 2, '::1', '2025-11-17 17:09:14', 'tipos_horario.php'),
(36, 2, '::1', '2025-11-17 17:09:41', 'gestion_horario_personal.php'),
(37, 2, '::1', '2025-11-17 17:18:11', 'index.php'),
(38, 2, '::1', '2025-11-17 17:21:56', 'index.php'),
(39, 2, '::1', '2025-11-17 17:22:07', 'auditoria.php'),
(40, 2, '::1', '2025-11-17 17:36:48', 'index.php'),
(41, 2, '::1', '2025-11-17 17:36:54', 'index.php'),
(42, 2, '::1', '2025-11-17 17:37:21', 'index.php'),
(43, 2, '::1', '2025-11-17 17:37:29', 'index.php'),
(44, 2, '::1', '2025-11-17 17:38:18', 'index.php'),
(45, 2, '::1', '2025-11-17 17:39:00', 'index.php'),
(46, 2, '::1', '2025-11-17 17:39:38', 'editar_accesos.php'),
(47, 2, '::1', '2025-11-17 17:45:02', 'editar_accesos.php'),
(48, 2, '::1', '2025-11-17 17:45:03', 'editar_accesos.php'),
(49, 2, '::1', '2025-11-17 17:50:29', 'index.php'),
(50, 2, '::1', '2025-11-17 17:50:33', 'editar_accesos.php'),
(51, 2, '::1', '2025-11-17 17:53:41', 'editar_accesos.php'),
(52, 2, '::1', '2025-11-17 17:53:44', 'editar_accesos.php'),
(53, 2, '::1', '2025-11-17 17:53:55', 'editar_accesos.php'),
(54, 2, '::1', '2025-11-17 17:53:56', 'editar_accesos.php'),
(55, 2, '::1', '2025-11-17 17:54:15', 'editar_accesos.php'),
(56, 2, '::1', '2025-11-17 17:54:16', 'editar_accesos.php'),
(57, 2, '::1', '2025-11-17 17:54:30', 'editar_accesos.php'),
(58, 2, '::1', '2025-11-17 17:54:31', 'editar_accesos.php'),
(59, 2, '::1', '2025-11-17 18:09:56', 'visita.php'),
(60, 2, '::1', '2025-11-17 18:10:05', 'visita.php'),
(61, 2, '::1', '2025-11-17 18:10:10', 'visita.php'),
(62, 2, '::1', '2025-11-17 18:10:32', 'visita.php'),
(63, 2, '::1', '2025-11-17 18:10:40', 'visita.php'),
(64, 2, '::1', '2025-11-17 18:10:41', 'visita.php'),
(65, 2, '::1', '2025-11-17 18:12:18', 'visita.php'),
(66, 2, '::1', '2025-11-17 18:12:22', 'visita.php'),
(67, 2, '::1', '2025-11-17 18:13:24', 'visita.php'),
(68, 2, '::1', '2025-11-17 18:19:23', 'index.php'),
(69, 2, '::1', '2025-11-17 18:19:25', 'visita.php'),
(70, 2, '::1', '2025-11-17 18:19:36', 'visita.php'),
(71, 2, '::1', '2025-11-17 18:19:40', 'visita.php'),
(72, 2, '::1', '2025-11-17 18:19:52', 'visita.php'),
(73, 2, '::1', '2025-11-17 18:19:56', 'visita.php'),
(74, 2, '::1', '2025-11-17 18:20:55', 'visita.php'),
(75, 2, '::1', '2025-11-17 18:21:10', 'visita.php'),
(76, 2, '::1', '2025-11-17 18:26:10', 'visita.php'),
(77, 2, '::1', '2025-11-17 18:26:13', 'visita.php'),
(78, 2, '::1', '2025-11-17 18:26:17', 'index.php'),
(79, 2, '::1', '2025-11-17 18:26:19', 'visita.php'),
(80, 2, '::1', '2025-11-17 18:27:23', 'visita.php'),
(81, 2, '::1', '2025-11-17 18:32:45', 'index.php'),
(82, 2, '::1', '2025-11-17 18:32:47', 'visita.php'),
(83, 2, '::1', '2025-11-17 18:32:54', 'visita.php'),
(84, 2, '::1', '2025-11-17 18:32:54', 'visita.php'),
(85, 2, '::1', '2025-11-17 18:32:54', 'visita.php'),
(86, 2, '::1', '2025-11-17 18:32:55', 'visita.php'),
(87, 2, '::1', '2025-11-17 18:33:03', 'visita.php'),
(88, 2, '::1', '2025-11-17 18:33:14', 'visita.php'),
(89, 2, '::1', '2025-11-17 18:33:22', 'visita.php'),
(90, 2, '::1', '2025-11-17 18:33:32', 'visita.php'),
(91, 2, '::1', '2025-11-17 18:33:33', 'visita.php'),
(92, 2, '::1', '2025-11-17 18:33:39', 'visita.php'),
(93, 2, '::1', '2025-11-17 18:33:45', 'visita.php'),
(94, 2, '::1', '2025-11-17 18:33:46', 'visita.php'),
(95, 2, '::1', '2025-11-17 18:33:48', 'visita.php'),
(96, 2, '::1', '2025-11-17 18:38:47', 'index.php'),
(97, 2, '::1', '2025-11-17 18:38:48', 'visita.php'),
(98, 2, '::1', '2025-11-17 18:38:51', 'visita.php'),
(99, 2, '::1', '2025-11-17 18:38:53', 'visita.php'),
(100, 2, '::1', '2025-11-17 18:39:30', 'index.php'),
(101, 2, '::1', '2025-11-17 18:39:32', 'visita.php'),
(102, 2, '::1', '2025-11-17 18:39:35', 'visita.php'),
(103, 2, '::1', '2025-11-17 18:39:36', 'visita.php'),
(104, 2, '::1', '2025-11-17 18:39:49', 'visita.php'),
(105, 2, '::1', '2025-11-17 18:45:48', 'visita.php'),
(106, 2, '::1', '2025-11-17 18:45:52', 'index.php'),
(107, 2, '::1', '2025-11-17 18:45:54', 'visita.php'),
(108, 2, '::1', '2025-11-17 18:45:56', 'visita.php'),
(109, 2, '::1', '2025-11-17 18:45:58', 'visita.php'),
(110, 2, '::1', '2025-11-17 18:46:38', 'visita.php'),
(111, 2, '::1', '2025-11-17 18:47:19', 'visita.php'),
(112, 2, '::1', '2025-11-17 18:47:21', 'visita.php'),
(113, 2, '::1', '2025-11-17 18:47:28', 'index.php'),
(114, 2, '::1', '2025-11-17 18:47:30', 'visita.php'),
(115, 2, '::1', '2025-11-17 18:47:32', 'visita.php'),
(116, 2, '::1', '2025-11-17 18:47:34', 'visita.php'),
(117, 5, '::1', '2025-11-17 18:50:48', 'index.php'),
(118, 5, '::1', '2025-11-17 18:50:58', 'mi_horario.php'),
(119, 5, '::1', '2025-11-17 18:51:02', 'index.php'),
(120, 5, '::1', '2025-11-17 18:51:04', 'mis_secciones.php'),
(121, 5, '::1', '2025-11-17 18:51:16', 'mi_pensum.php'),
(122, 5, '::1', '2025-11-17 18:51:23', 'mi_historial.php'),
(123, 2, '::1', '2025-11-17 18:51:36', 'index.php'),
(124, 2, '::1', '2025-11-17 18:51:39', 'visita.php'),
(125, 2, '::1', '2025-11-17 18:51:44', 'visita.php'),
(126, 2, '::1', '2025-11-17 18:51:46', 'visita.php'),
(127, 2, '::1', '2025-11-17 18:52:44', 'index.php'),
(128, 2, '::1', '2025-11-17 18:53:45', 'visita.php'),
(129, 2, '::1', '2025-11-17 18:53:47', 'index.php'),
(130, 2, '::1', '2025-11-17 18:55:31', 'visita.php'),
(131, 2, '::1', '2025-11-17 18:55:36', 'visita.php'),
(132, 2, '::1', '2025-11-17 18:55:39', 'visita.php'),
(133, 2, '::1', '2025-11-17 18:55:45', 'visita.php'),
(134, 2, '::1', '2025-11-17 18:56:22', 'visita.php'),
(135, 2, '::1', '2025-11-17 18:56:30', 'visita.php'),
(136, 2, '::1', '2025-11-17 19:01:14', 'estudiantes.php'),
(137, 2, '::1', '2025-11-17 19:04:36', 'index.php'),
(138, 2, '::1', '2025-11-17 19:04:41', 'agregar_estudiante.php'),
(139, 2, '::1', '2025-11-17 19:23:53', 'agregar_estudiante.php'),
(140, 2, '::1', '2025-11-17 19:23:53', 'agregar_estudiante.php'),
(141, 2, '::1', '2025-11-17 19:47:07', 'agregar_estudiante.php'),
(142, 2, '::1', '2025-11-17 19:47:07', 'agregar_estudiante.php'),
(143, 2, '::1', '2025-11-24 15:45:19', 'index.php'),
(144, 2, '::1', '2025-11-24 15:45:36', 'agregar_estudiante.php'),
(145, 2, '::1', '2025-11-24 15:45:36', 'agregar_estudiante.php'),
(146, 2, '::1', '2025-11-24 15:48:07', 'agregar_estudiante.php'),
(147, 2, '::1', '2025-11-24 15:48:07', 'agregar_estudiante.php'),
(148, 2, '::1', '2025-11-24 15:53:39', 'agregar_estudiante.php'),
(149, 2, '::1', '2025-11-24 15:53:40', 'agregar_estudiante.php'),
(150, 2, '::1', '2025-11-24 15:54:51', 'estudiantes.php'),
(151, 2, '::1', '2025-11-24 15:54:51', 'estudiantes.php'),
(152, 2, '::1', '2025-11-24 16:07:11', 'estudiantes.php'),
(153, 2, '::1', '2025-11-24 16:07:11', 'estudiantes.php'),
(154, 2, '::1', '2025-11-24 16:07:58', 'estudiantes.php'),
(155, 2, '::1', '2025-11-24 16:07:58', 'estudiantes.php'),
(156, 2, '::1', '2025-11-24 16:10:14', 'estudiantes.php'),
(157, 2, '::1', '2025-11-24 16:10:15', 'estudiantes.php'),
(158, 2, '::1', '2025-11-24 16:15:02', 'estudiantes.php'),
(159, 2, '::1', '2025-11-24 16:15:02', 'estudiantes.php'),
(160, 2, '::1', '2025-11-24 17:02:31', 'estudiantes.php'),
(161, 2, '::1', '2025-11-24 17:02:31', 'estudiantes.php'),
(162, 2, '::1', '2025-11-24 17:03:47', 'estudiantes.php'),
(163, 2, '::1', '2025-11-24 17:05:27', 'estudiantes.php'),
(164, 2, '::1', '2025-11-24 17:05:28', 'estudiantes.php'),
(165, 2, '::1', '2025-11-24 17:11:06', 'estudiantes.php'),
(166, 2, '::1', '2025-11-24 17:11:06', 'estudiantes.php'),
(167, 2, '::1', '2025-11-24 17:17:29', 'estudiantes.php'),
(168, 2, '::1', '2025-11-24 17:17:30', 'estudiantes.php'),
(169, 2, '::1', '2025-11-24 17:17:59', 'estudiantes.php'),
(170, 2, '::1', '2025-11-24 17:17:59', 'estudiantes.php'),
(171, 2, '::1', '2025-11-24 17:23:33', 'estudiantes.php'),
(172, 2, '::1', '2025-11-24 17:23:33', 'estudiantes.php'),
(173, 2, '::1', '2025-11-24 17:29:49', 'estudiantes.php'),
(174, 2, '::1', '2025-11-24 17:29:49', 'estudiantes.php'),
(175, 2, '::1', '2025-11-24 17:32:19', 'estudiantes.php'),
(176, 2, '::1', '2025-11-24 17:32:20', 'estudiantes.php'),
(177, 2, '::1', '2025-11-24 17:40:54', 'estudiantes.php'),
(178, 2, '::1', '2025-11-24 17:40:55', 'estudiantes.php'),
(179, 2, '::1', '2025-11-24 17:46:42', 'estudiantes.php'),
(180, 2, '::1', '2025-11-24 17:46:43', 'estudiantes.php'),
(181, 2, '::1', '2025-11-24 17:46:54', 'estudiantes.php'),
(182, 2, '::1', '2025-11-24 17:46:55', 'estudiantes.php'),
(183, 2, '::1', '2025-11-24 18:01:58', 'estudiantes.php'),
(184, 2, '::1', '2025-11-24 18:01:59', 'estudiantes.php'),
(185, 2, '::1', '2025-11-24 18:02:13', 'estudiantes.php'),
(186, 2, '::1', '2025-11-24 18:02:13', 'estudiantes.php'),
(187, 2, '::1', '2025-11-24 18:04:35', 'estudiantes.php'),
(188, 2, '::1', '2025-11-24 18:04:35', 'estudiantes.php'),
(189, 2, '::1', '2025-11-24 18:05:55', 'valores_predefinidos.php'),
(190, 2, '::1', '2025-11-24 18:06:01', 'valores_predefinidos.php'),
(191, 2, '::1', '2025-11-24 18:06:01', 'valores_predefinidos.php'),
(192, 2, '::1', '2025-11-24 18:06:07', 'valores_predefinidos.php'),
(193, 2, '::1', '2025-11-24 18:06:07', 'valores_predefinidos.php'),
(194, 2, '::1', '2025-11-24 18:06:16', 'index.php'),
(195, 2, '::1', '2025-11-24 18:06:31', 'estudiantes.php'),
(196, 2, '::1', '2025-11-24 18:06:31', 'estudiantes.php'),
(197, 2, '::1', '2025-11-24 18:06:42', 'estudiantes.php'),
(198, 2, '::1', '2025-11-24 18:06:42', 'estudiantes.php'),
(199, 2, '::1', '2025-11-24 18:06:54', 'estudiantes.php'),
(200, 2, '::1', '2025-11-24 18:06:54', 'estudiantes.php'),
(201, 2, '::1', '2025-11-24 18:08:09', 'valores_predefinidos.php'),
(202, 2, '::1', '2025-11-24 18:08:15', 'valores_predefinidos.php'),
(203, 2, '::1', '2025-11-24 18:08:16', 'valores_predefinidos.php'),
(204, 2, '::1', '2025-11-24 18:08:22', 'valores_predefinidos.php'),
(205, 2, '::1', '2025-11-24 18:08:23', 'valores_predefinidos.php'),
(206, 2, '::1', '2025-11-24 18:08:29', 'estudiantes.php'),
(207, 2, '::1', '2025-11-24 18:08:29', 'estudiantes.php'),
(208, 2, '::1', '2025-11-24 18:13:41', 'estudiantes.php'),
(209, 2, '::1', '2025-11-24 18:13:41', 'estudiantes.php'),
(210, 2, '::1', '2025-11-24 18:13:53', 'estudiantes.php'),
(211, 2, '::1', '2025-11-24 18:13:54', 'estudiantes.php'),
(212, 2, '::1', '2025-11-24 18:26:58', 'estudiantes.php'),
(213, 2, '::1', '2025-11-24 18:26:58', 'estudiantes.php'),
(214, 2, '::1', '2025-11-24 18:27:09', 'estudiantes.php'),
(215, 2, '::1', '2025-11-24 18:27:09', 'estudiantes.php'),
(216, 2, '::1', '2025-11-24 18:32:37', 'estudiantes.php'),
(217, 2, '::1', '2025-11-24 18:32:38', 'estudiantes.php'),
(218, 2, '::1', '2025-11-24 18:33:06', 'estudiantes.php'),
(219, 2, '::1', '2025-11-24 18:33:06', 'estudiantes.php'),
(220, 2, '::1', '2025-11-24 18:33:24', 'estudiantes.php'),
(221, 2, '::1', '2025-11-24 18:33:24', 'estudiantes.php'),
(222, 2, '::1', '2025-11-24 18:33:25', 'estudiantes.php'),
(223, 2, '::1', '2025-11-24 18:33:25', 'estudiantes.php'),
(224, 2, '::1', '2025-11-24 18:33:34', 'estudiantes.php'),
(225, 2, '::1', '2025-11-24 18:33:34', 'estudiantes.php'),
(226, 2, '::1', '2025-11-24 18:34:33', 'estudiantes.php'),
(227, 2, '::1', '2025-11-24 18:34:33', 'estudiantes.php'),
(228, 2, '::1', '2025-11-24 18:34:49', 'estudiantes.php'),
(229, 2, '::1', '2025-11-24 18:34:49', 'estudiantes.php'),
(230, 2, '::1', '2025-11-24 18:42:18', 'estudiantes.php'),
(231, 2, '::1', '2025-11-24 18:42:18', 'estudiantes.php'),
(232, 2, '::1', '2025-11-24 18:42:29', 'estudiantes.php'),
(233, 2, '::1', '2025-11-24 18:42:29', 'estudiantes.php'),
(234, 2, '::1', '2025-11-24 18:42:57', 'estudiantes.php'),
(235, 2, '::1', '2025-11-24 18:42:58', 'estudiantes.php'),
(236, 2, '::1', '2025-11-24 18:43:05', 'estudiantes.php'),
(237, 2, '::1', '2025-11-24 18:43:05', 'estudiantes.php'),
(238, 2, '::1', '2025-11-24 18:43:21', 'estudiantes.php'),
(239, 2, '::1', '2025-11-24 18:43:21', 'estudiantes.php'),
(240, 2, '::1', '2025-11-24 18:51:14', 'estudiantes.php'),
(241, 2, '::1', '2025-11-24 18:51:14', 'estudiantes.php'),
(242, 2, '::1', '2025-11-24 18:51:25', 'estudiantes.php'),
(243, 2, '::1', '2025-11-24 18:51:25', 'estudiantes.php'),
(244, 2, '::1', '2025-11-24 18:57:02', 'estudiantes.php'),
(245, 2, '::1', '2025-11-24 18:57:02', 'estudiantes.php'),
(246, 2, '::1', '2025-11-24 18:57:11', 'estudiantes.php'),
(247, 2, '::1', '2025-11-24 18:57:11', 'estudiantes.php'),
(248, 2, '::1', '2025-11-24 19:00:32', 'estudiantes.php'),
(249, 2, '::1', '2025-11-24 19:00:32', 'estudiantes.php'),
(250, 2, '::1', '2025-11-25 13:44:39', 'index.php'),
(251, 2, '::1', '2025-11-25 13:46:05', 'estudiantes.php'),
(252, 2, '::1', '2025-11-25 13:46:05', 'estudiantes.php'),
(253, 2, '::1', '2025-11-25 13:46:51', 'estudiantes.php'),
(254, 2, '::1', '2025-11-25 13:46:51', 'estudiantes.php'),
(255, 2, '::1', '2025-11-25 14:02:02', 'estudiantes.php'),
(256, 2, '::1', '2025-11-25 14:02:02', 'estudiantes.php'),
(257, 2, '::1', '2025-11-25 14:02:04', 'estudiantes.php'),
(258, 2, '::1', '2025-11-25 14:02:04', 'estudiantes.php'),
(259, 2, '::1', '2025-11-25 14:02:04', 'estudiantes.php'),
(260, 2, '::1', '2025-11-25 14:02:05', 'estudiantes.php'),
(261, 2, '::1', '2025-11-25 14:02:30', 'estudiantes.php'),
(262, 2, '::1', '2025-11-25 14:02:30', 'estudiantes.php'),
(263, 2, '::1', '2025-11-25 14:03:07', 'estudiantes.php'),
(264, 2, '::1', '2025-11-25 14:03:07', 'estudiantes.php'),
(265, 2, '::1', '2025-11-25 14:03:08', 'estudiantes.php'),
(266, 2, '::1', '2025-11-25 14:03:08', 'estudiantes.php'),
(267, 2, '::1', '2025-11-25 14:03:39', 'estudiantes.php'),
(268, 2, '::1', '2025-11-25 14:03:39', 'estudiantes.php'),
(269, 2, '::1', '2025-11-25 14:06:12', 'estudiantes.php'),
(270, 2, '::1', '2025-11-25 14:06:12', 'estudiantes.php'),
(271, 2, '::1', '2025-11-25 14:06:13', 'estudiantes.php'),
(272, 2, '::1', '2025-11-25 14:06:16', 'estudiantes.php'),
(273, 2, '::1', '2025-11-25 14:06:26', 'estudiantes.php'),
(274, 2, '::1', '2025-11-25 14:06:26', 'estudiantes.php'),
(275, 2, '::1', '2025-11-25 14:10:34', 'estudiantes.php'),
(276, 2, '::1', '2025-11-25 14:10:34', 'estudiantes.php'),
(277, 2, '::1', '2025-11-25 14:10:36', 'estudiantes.php'),
(278, 2, '::1', '2025-11-25 14:10:36', 'estudiantes.php'),
(279, 2, '::1', '2025-11-25 14:10:49', 'estudiantes.php'),
(280, 2, '::1', '2025-11-25 14:10:49', 'estudiantes.php'),
(281, 2, '::1', '2025-11-25 14:24:08', 'estudiantes.php'),
(282, 2, '::1', '2025-11-25 14:24:08', 'estudiantes.php'),
(283, 2, '::1', '2025-11-25 14:24:10', 'estudiantes.php'),
(284, 2, '::1', '2025-11-25 14:24:10', 'estudiantes.php'),
(285, 2, '::1', '2025-11-25 14:24:12', 'estudiantes.php'),
(286, 2, '::1', '2025-11-25 14:24:12', 'estudiantes.php'),
(287, 2, '::1', '2025-11-25 14:24:13', 'estudiantes.php'),
(288, 2, '::1', '2025-11-25 14:24:14', 'estudiantes.php'),
(289, 2, '::1', '2025-11-25 14:25:47', 'estudiantes.php'),
(290, 2, '::1', '2025-11-25 14:25:47', 'estudiantes.php'),
(291, 2, '::1', '2025-11-25 14:25:58', 'estudiantes.php'),
(292, 2, '::1', '2025-11-25 14:25:58', 'estudiantes.php'),
(293, 2, '::1', '2025-11-25 14:26:27', 'estudiantes.php'),
(294, 2, '::1', '2025-11-25 14:26:27', 'estudiantes.php'),
(295, 2, '::1', '2025-11-25 14:26:36', 'estudiantes.php'),
(296, 2, '::1', '2025-11-25 14:26:36', 'estudiantes.php'),
(297, 2, '::1', '2025-11-25 14:27:22', 'estudiantes.php'),
(298, 2, '::1', '2025-11-25 14:27:22', 'estudiantes.php'),
(299, 2, '::1', '2025-11-25 14:34:22', 'estudiantes.php'),
(300, 2, '::1', '2025-11-25 14:34:22', 'estudiantes.php'),
(301, 2, '::1', '2025-11-25 14:34:23', 'estudiantes.php'),
(302, 2, '::1', '2025-11-25 14:34:26', 'estudiantes.php'),
(303, 2, '::1', '2025-11-25 14:34:26', 'estudiantes.php'),
(304, 2, '::1', '2025-11-25 14:34:31', 'estudiantes.php'),
(305, 2, '::1', '2025-11-25 14:34:52', 'estudiantes.php'),
(306, 2, '::1', '2025-11-25 14:34:52', 'estudiantes.php'),
(307, 2, '::1', '2025-11-25 14:34:56', 'estudiantes.php'),
(308, 2, '::1', '2025-11-25 14:35:00', 'estudiantes.php'),
(309, 2, '::1', '2025-11-25 14:35:27', 'estudiantes.php'),
(310, 2, '::1', '2025-11-25 14:35:27', 'estudiantes.php'),
(311, 2, '::1', '2025-11-25 14:45:07', 'estudiantes.php'),
(312, 2, '::1', '2025-11-25 14:45:07', 'estudiantes.php'),
(313, 2, '::1', '2025-11-25 14:45:09', 'estudiantes.php'),
(314, 2, '::1', '2025-11-25 14:45:10', 'estudiantes.php'),
(315, 2, '::1', '2025-11-25 14:48:32', 'estudiantes.php'),
(316, 2, '::1', '2025-11-25 14:48:32', 'estudiantes.php'),
(317, 2, '::1', '2025-11-25 14:55:45', 'estudiantes.php'),
(318, 2, '::1', '2025-11-25 14:55:45', 'estudiantes.php'),
(319, 2, '::1', '2025-11-25 14:55:59', 'estudiantes.php'),
(320, 2, '::1', '2025-11-25 14:56:00', 'estudiantes.php'),
(321, 2, '::1', '2025-11-25 14:56:10', 'estudiantes.php'),
(322, 2, '::1', '2025-11-25 14:56:10', 'estudiantes.php'),
(323, 2, '::1', '2025-11-25 15:04:55', 'estudiantes.php'),
(324, 2, '::1', '2025-11-25 15:04:55', 'estudiantes.php'),
(325, 2, '::1', '2025-11-25 15:04:57', 'estudiantes.php'),
(326, 2, '::1', '2025-11-25 15:04:57', 'estudiantes.php'),
(327, 2, '::1', '2025-11-25 15:05:15', 'estudiantes.php'),
(328, 2, '::1', '2025-11-25 15:05:15', 'estudiantes.php'),
(329, 2, '::1', '2025-11-25 15:05:39', 'estudiantes.php'),
(330, 2, '::1', '2025-11-25 15:05:39', 'estudiantes.php'),
(331, 2, '::1', '2025-11-25 15:16:59', 'estudiantes.php'),
(332, 2, '::1', '2025-11-25 15:17:00', 'estudiantes.php'),
(333, 2, '::1', '2025-11-25 15:17:01', 'estudiantes.php'),
(334, 2, '::1', '2025-11-25 15:17:01', 'estudiantes.php'),
(335, 2, '::1', '2025-11-25 15:18:22', 'estudiantes.php'),
(336, 2, '::1', '2025-11-25 15:18:22', 'estudiantes.php'),
(337, 2, '::1', '2025-11-25 15:27:50', 'estudiantes.php'),
(338, 2, '::1', '2025-11-25 15:27:50', 'estudiantes.php'),
(339, 2, '::1', '2025-11-25 15:27:52', 'estudiantes.php'),
(340, 2, '::1', '2025-11-25 15:27:52', 'estudiantes.php'),
(341, 2, '::1', '2025-11-25 15:27:54', 'estudiantes.php'),
(342, 2, '::1', '2025-11-25 15:27:54', 'estudiantes.php'),
(343, 2, '::1', '2025-11-25 15:28:11', 'estudiantes.php'),
(344, 2, '::1', '2025-11-25 15:28:11', 'estudiantes.php'),
(345, 2, '::1', '2025-12-01 17:45:52', 'index.php'),
(346, 2, '::1', '2025-12-03 13:51:24', 'index.php'),
(347, 2, '::1', '2025-12-03 13:58:39', 'agregar_estudiante.php'),
(348, 2, '::1', '2025-12-03 13:58:40', 'agregar_estudiante.php'),
(349, 2, '::1', '2025-12-03 14:39:49', 'agregar_estudiante.php'),
(350, 2, '::1', '2025-12-03 14:40:21', 'agregar_estudiante.php'),
(351, 2, '::1', '2025-12-03 14:40:38', 'agregar_estudiante.php'),
(352, 2, '::1', '2025-12-03 14:40:38', 'agregar_estudiante.php'),
(353, 2, '::1', '2025-12-03 14:40:50', 'agregar_estudiante.php'),
(354, 2, '::1', '2025-12-03 14:40:50', 'agregar_estudiante.php'),
(355, 2, '::1', '2025-12-03 14:42:07', 'agregar_estudiante.php'),
(356, 2, '::1', '2025-12-03 14:42:07', 'agregar_estudiante.php'),
(357, 2, '::1', '2025-12-03 14:52:30', 'agregar_estudiante.php'),
(358, 2, '::1', '2025-12-03 14:52:30', 'agregar_estudiante.php'),
(359, 2, '::1', '2025-12-03 14:52:32', 'agregar_estudiante.php'),
(360, 2, '::1', '2025-12-03 14:52:32', 'agregar_estudiante.php'),
(361, 2, '::1', '2025-12-03 14:52:32', 'agregar_estudiante.php'),
(362, 2, '::1', '2025-12-03 14:52:33', 'agregar_estudiante.php'),
(363, 2, '::1', '2025-12-03 14:54:24', 'agregar_estudiante.php'),
(364, 2, '::1', '2025-12-03 14:54:39', 'agregar_estudiante.php'),
(365, 2, '::1', '2025-12-03 14:55:03', 'agregar_estudiante.php'),
(366, 2, '::1', '2025-12-03 14:55:31', 'agregar_estudiante.php'),
(367, 2, '::1', '2025-12-03 14:55:31', 'agregar_estudiante.php'),
(368, 2, '::1', '2025-12-03 15:10:20', 'agregar_estudiante.php'),
(369, 2, '::1', '2025-12-03 15:10:21', 'agregar_estudiante.php'),
(370, 2, '::1', '2025-12-03 15:10:21', 'agregar_estudiante.php'),
(371, 2, '::1', '2025-12-03 15:10:21', 'agregar_estudiante.php'),
(372, 2, '::1', '2025-12-03 15:10:29', 'agregar_estudiante.php'),
(373, 2, '::1', '2025-12-03 15:10:29', 'agregar_estudiante.php'),
(374, 2, '::1', '2025-12-03 15:14:48', 'agregar_estudiante.php'),
(375, 2, '::1', '2025-12-03 15:14:49', 'agregar_estudiante.php'),
(376, 2, '::1', '2025-12-03 15:17:50', 'agregar_estudiante.php'),
(377, 2, '::1', '2025-12-03 15:17:51', 'agregar_estudiante.php'),
(378, 2, '::1', '2025-12-03 15:17:54', 'agregar_estudiante.php'),
(379, 2, '::1', '2025-12-03 15:17:55', 'agregar_estudiante.php'),
(380, 2, '::1', '2025-12-03 15:46:00', 'index.php'),
(381, 2, '::1', '2025-12-03 15:46:02', 'agregar_estudiante.php'),
(382, 2, '::1', '2025-12-03 15:46:02', 'agregar_estudiante.php'),
(383, 2, '::1', '2025-12-03 15:48:54', 'agregar_estudiante.php'),
(384, 2, '::1', '2025-12-03 15:48:55', 'agregar_estudiante.php'),
(385, 2, '::1', '2025-12-03 15:59:34', 'index.php'),
(386, 2, '::1', '2025-12-03 15:59:35', 'agregar_estudiante.php'),
(387, 2, '::1', '2025-12-03 15:59:35', 'agregar_estudiante.php'),
(388, 2, '::1', '2025-12-03 16:02:19', 'agregar_estudiante.php'),
(389, 2, '::1', '2025-12-03 16:02:19', 'agregar_estudiante.php'),
(390, 2, '::1', '2025-12-03 16:02:25', 'estudiantes.php'),
(391, 2, '::1', '2025-12-03 16:02:25', 'estudiantes.php'),
(392, 2, '::1', '2025-12-03 16:26:02', 'estudiantes.php'),
(393, 2, '::1', '2025-12-03 16:26:02', 'estudiantes.php'),
(394, 2, '::1', '2025-12-03 16:26:32', 'estudiantes.php'),
(395, 2, '::1', '2025-12-03 16:26:32', 'estudiantes.php'),
(396, 2, '::1', '2025-12-03 16:26:47', 'estudiantes.php'),
(397, 2, '::1', '2025-12-03 16:26:47', 'estudiantes.php'),
(398, 2, '::1', '2025-12-03 16:37:06', 'index.php'),
(399, 2, '::1', '2025-12-03 16:37:08', 'estudiantes.php'),
(400, 2, '::1', '2025-12-03 16:37:08', 'estudiantes.php'),
(401, 2, '::1', '2025-12-03 16:38:26', 'estudiantes.php'),
(402, 2, '::1', '2025-12-03 16:38:27', 'estudiantes.php'),
(403, 2, '::1', '2025-12-03 16:42:08', 'index.php'),
(404, 2, '::1', '2025-12-03 16:42:09', 'estudiantes.php'),
(405, 2, '::1', '2025-12-03 16:42:09', 'estudiantes.php'),
(406, 2, '::1', '2025-12-03 16:42:12', 'estudiantes.php'),
(407, 2, '::1', '2025-12-03 16:42:12', 'estudiantes.php'),
(408, 2, '::1', '2025-12-03 16:42:50', 'index.php'),
(409, 2, '::1', '2025-12-03 16:42:52', 'agregar_carrera.php'),
(410, 2, '::1', '2025-12-03 16:42:55', 'estudiantes.php'),
(411, 2, '::1', '2025-12-03 16:42:55', 'estudiantes.php'),
(412, 2, '::1', '2025-12-03 16:43:04', 'estudiantes.php'),
(413, 2, '::1', '2025-12-03 16:43:05', 'estudiantes.php'),
(414, 2, '::1', '2025-12-03 16:45:49', 'index.php'),
(415, 2, '::1', '2025-12-03 16:45:51', 'agregar_estudiante.php'),
(416, 2, '::1', '2025-12-03 16:45:51', 'agregar_estudiante.php'),
(417, 2, '::1', '2025-12-03 16:45:52', 'estudiantes.php'),
(418, 2, '::1', '2025-12-03 16:45:52', 'estudiantes.php'),
(419, 2, '::1', '2025-12-03 16:46:57', 'estudiantes.php'),
(420, 2, '::1', '2025-12-03 16:46:57', 'estudiantes.php'),
(421, 2, '::1', '2025-12-03 16:47:09', 'estudiantes.php'),
(422, 2, '::1', '2025-12-03 16:47:09', 'estudiantes.php'),
(423, 2, '::1', '2025-12-03 16:48:33', 'index.php'),
(424, 2, '::1', '2025-12-03 16:48:42', 'estudiantes.php'),
(425, 2, '::1', '2025-12-03 16:48:43', 'estudiantes.php'),
(426, 2, '::1', '2025-12-03 16:59:13', 'index.php'),
(427, 2, '::1', '2025-12-03 16:59:20', 'estudiantes.php'),
(428, 2, '::1', '2025-12-03 16:59:20', 'estudiantes.php'),
(429, 2, '::1', '2025-12-03 16:59:34', 'estudiantes.php'),
(430, 2, '::1', '2025-12-03 16:59:34', 'estudiantes.php'),
(431, 2, '::1', '2025-12-03 16:59:55', 'estudiantes.php'),
(432, 2, '::1', '2025-12-03 16:59:55', 'estudiantes.php'),
(433, 2, '::1', '2025-12-03 17:00:28', 'estudiantes.php'),
(434, 2, '::1', '2025-12-03 17:00:28', 'estudiantes.php'),
(435, 2, '::1', '2025-12-03 17:02:16', 'estudiantes.php'),
(436, 2, '::1', '2025-12-03 17:02:17', 'estudiantes.php'),
(437, 2, '::1', '2025-12-03 17:02:18', 'estudiantes.php'),
(438, 2, '::1', '2025-12-03 17:02:20', 'estudiantes.php'),
(439, 2, '::1', '2025-12-03 17:03:06', 'estudiantes.php'),
(440, 2, '::1', '2025-12-03 17:03:06', 'estudiantes.php'),
(441, 2, '::1', '2025-12-03 17:03:22', 'estudiantes.php'),
(442, 2, '::1', '2025-12-03 17:03:23', 'estudiantes.php'),
(443, 2, '::1', '2025-12-03 17:03:34', 'estudiantes.php'),
(444, 2, '::1', '2025-12-03 17:03:34', 'estudiantes.php'),
(445, 2, '::1', '2025-12-03 17:10:31', 'index.php'),
(446, 2, '::1', '2025-12-03 17:10:34', 'mi_pensum.php'),
(447, 2, '::1', '2025-12-03 17:11:19', 'index.php'),
(448, 2, '::1', '2025-12-03 18:23:33', 'index.php'),
(449, 2, '::1', '2025-12-03 18:24:14', 'index.php'),
(450, 2, '::1', '2025-12-03 18:24:15', 'index.php'),
(451, 2, '::1', '2025-12-03 18:24:19', 'inscripcion_materias.php'),
(452, 2, '::1', '2025-12-03 18:24:40', 'inscripcion_materias.php'),
(453, 2, '::1', '2025-12-03 18:24:55', 'inscripcion_materias.php'),
(454, 2, '::1', '2025-12-03 18:29:09', 'inscripcion_materias.php'),
(455, 2, '::1', '2025-12-03 18:31:16', 'periodos_academicos.php'),
(456, 2, '::1', '2025-12-03 18:31:20', 'periodos_academicos.php'),
(457, 2, '::1', '2025-12-03 18:31:42', 'periodos_academicos.php'),
(458, 2, '::1', '2025-12-03 18:31:43', 'periodos_academicos.php'),
(459, 2, '::1', '2025-12-03 18:31:49', 'periodos_academicos.php'),
(460, 2, '::1', '2025-12-03 18:31:52', 'periodos_academicos.php'),
(461, 2, '::1', '2025-12-03 18:31:56', 'periodos_academicos.php'),
(462, 2, '::1', '2025-12-03 18:31:56', 'periodos_academicos.php'),
(463, 2, '::1', '2025-12-03 18:32:07', 'periodos_academicos.php'),
(464, 2, '::1', '2025-12-03 18:32:07', 'periodos_academicos.php'),
(465, 2, '::1', '2025-12-03 18:33:00', 'periodos_academicos.php'),
(466, 2, '::1', '2025-12-03 18:33:01', 'periodos_academicos.php'),
(467, 2, '::1', '2025-12-03 18:33:06', 'periodos_academicos.php'),
(468, 2, '::1', '2025-12-03 18:33:06', 'periodos_academicos.php'),
(469, 2, '::1', '2025-12-03 18:34:03', 'periodos_academicos.php'),
(470, 2, '::1', '2025-12-03 18:34:05', 'inscripcion_materias.php'),
(471, 2, '::1', '2025-12-03 18:34:24', 'inscripcion_materias.php'),
(472, 2, '::1', '2025-12-03 18:37:17', 'inscripcion_materias.php'),
(473, 2, '::1', '2025-12-03 18:38:05', 'consulta_notas.php'),
(474, 2, '::1', '2025-12-03 18:38:09', 'consulta_notas.php'),
(475, 2, '::1', '2025-12-03 18:38:16', 'consulta_notas.php'),
(476, 2, '::1', '2025-12-03 18:38:36', 'inscripcion_materias.php'),
(477, 2, '::1', '2025-12-03 18:38:41', 'inscripcion_materias.php'),
(478, 2, '::1', '2025-12-03 18:38:45', 'index.php'),
(479, 2, '::1', '2025-12-03 18:38:47', 'inscripcion_materias.php'),
(480, 2, '::1', '2025-12-03 18:38:48', 'inscripcion_materias.php'),
(481, 2, '::1', '2025-12-03 18:38:53', 'inscripcion_materias.php'),
(482, 2, '::1', '2025-12-03 18:38:59', 'inscripcion_materias.php'),
(483, 2, '::1', '2025-12-03 18:39:01', 'index.php'),
(484, 2, '::1', '2025-12-03 18:39:02', 'index.php'),
(485, 2, '::1', '2025-12-03 18:39:05', 'inscripcion_materias.php'),
(486, 2, '::1', '2025-12-03 18:39:40', 'inscripcion_materias.php'),
(487, 2, '::1', '2025-12-03 18:40:39', 'inscripcion_materias.php'),
(488, 2, '::1', '2025-12-03 18:40:57', 'inscripcion_materias.php'),
(489, 2, '::1', '2025-12-03 18:41:21', 'inscripcion_materias.php'),
(490, 2, '::1', '2025-12-03 18:41:23', 'inscripcion_materias.php'),
(491, 2, '::1', '2025-12-03 18:41:46', 'inscripcion_materias.php'),
(492, 2, '::1', '2025-12-03 18:54:24', 'index.php'),
(493, 2, '::1', '2025-12-03 19:16:20', 'inscripcion_materias.php'),
(494, 2, '::1', '2025-12-03 19:16:25', 'inscripcion_materias.php'),
(495, 2, '::1', '2025-12-03 19:18:20', 'gestion_seccion.php'),
(496, 2, '::1', '2025-12-03 19:18:42', 'inscripcion_materias.php'),
(497, 2, '::1', '2025-12-03 19:19:32', 'inscripcion_materias.php'),
(498, 2, '::1', '2025-12-03 19:30:12', 'index.php'),
(499, 2, '::1', '2025-12-03 19:30:14', 'gestion_seccion.php'),
(500, 2, '::1', '2025-12-03 19:30:17', 'inscripcion_materias.php'),
(501, 2, '::1', '2025-12-03 19:38:32', 'inscripcion_materias.php'),
(502, 2, '::1', '2025-12-03 19:50:25', 'inscripcion_materias.php'),
(503, 2, '::1', '2025-12-03 19:50:36', 'inscripcion_materias.php'),
(504, 2, '::1', '2025-12-03 19:51:14', 'gestion_seccion.php'),
(505, 2, '::1', '2025-12-03 19:51:32', 'admin_notas_pendientes.php'),
(506, 2, '::1', '2025-12-03 19:51:34', 'consulta_notas.php'),
(507, 2, '::1', '2025-12-03 19:51:37', 'consulta_notas.php'),
(508, 2, '::1', '2025-12-03 19:51:54', 'index.php'),
(509, 2, '::1', '2025-12-03 19:51:56', 'notas.php'),
(510, 4, '::1', '2025-12-03 19:52:19', 'index.php'),
(511, 4, '::1', '2025-12-03 19:52:21', 'notas.php'),
(512, 2, '::1', '2025-12-03 19:53:14', 'index.php'),
(513, 2, '::1', '2025-12-03 19:53:41', 'inscripcion_materias.php'),
(514, 2, '::1', '2025-12-03 19:53:45', 'inscripcion_materias.php'),
(515, 2, '::1', '2025-12-03 19:53:52', 'inscripcion_materias.php'),
(516, 2, '::1', '2025-12-03 19:54:00', 'admin_notas_pendientes.php'),
(517, 2, '::1', '2025-12-03 19:54:22', 'admin_notas_pendientes.php'),
(518, 2, '::1', '2025-12-03 19:54:25', 'consulta_notas.php'),
(519, 2, '::1', '2025-12-03 19:54:27', 'consulta_notas.php'),
(520, 2, '::1', '2025-12-03 19:54:30', 'inscripcion_materias.php'),
(521, 2, '::1', '2025-12-03 19:54:34', 'inscripcion_materias.php'),
(522, 2, '::1', '2025-12-03 19:55:01', 'consulta_notas.php'),
(523, 2, '::1', '2025-12-03 19:55:03', 'consulta_notas.php'),
(524, 2, '::1', '2025-12-04 14:18:44', 'index.php'),
(525, 2, '::1', '2025-12-04 14:18:46', 'inscripcion_materias.php'),
(526, 2, '::1', '2025-12-04 14:18:50', 'inscripcion_materias.php'),
(527, 2, '::1', '2025-12-04 14:19:06', 'inscripcion_materias.php'),
(528, 2, '::1', '2025-12-04 14:19:10', 'inscripcion_materias.php'),
(529, 2, '::1', '2025-12-04 14:19:48', 'inscripcion_materias.php'),
(530, 2, '::1', '2025-12-04 14:19:56', 'inscripcion_materias.php'),
(531, 2, '::1', '2025-12-04 14:21:37', 'estudiantes.php'),
(532, 2, '::1', '2025-12-04 14:21:37', 'estudiantes.php'),
(533, 2, '::1', '2025-12-04 14:23:08', 'estudiantes.php'),
(534, 2, '::1', '2025-12-04 14:23:09', 'estudiantes.php'),
(535, 2, '::1', '2025-12-04 14:23:13', 'agregar_estudiante.php'),
(536, 2, '::1', '2025-12-04 14:23:13', 'agregar_estudiante.php'),
(537, 2, '::1', '2025-12-04 14:23:15', 'inscripcion_materias.php'),
(538, 2, '::1', '2025-12-04 14:53:55', 'inscripcion_materias.php'),
(539, 2, '::1', '2025-12-04 14:54:16', 'consulta_notas.php'),
(540, 2, '::1', '2025-12-04 14:54:18', 'consulta_notas.php'),
(541, 2, '::1', '2025-12-04 14:54:21', 'consulta_notas.php'),
(542, 2, '::1', '2025-12-04 14:54:40', 'consulta_notas.php'),
(543, 2, '::1', '2025-12-04 14:54:45', 'inscripcion_materias.php'),
(544, 2, '::1', '2025-12-04 16:14:51', 'index.php'),
(545, 2, '::1', '2025-12-04 16:14:54', 'inscripcion_materias.php'),
(546, 2, '::1', '2025-12-04 16:14:57', 'inscripcion_materias.php'),
(547, 2, '::1', '2025-12-04 16:15:05', 'admin_notas_pendientes.php'),
(548, 2, '::1', '2025-12-04 16:15:17', 'index.php'),
(549, 2, '::1', '2025-12-04 16:15:19', 'notas.php'),
(550, 2, '::1', '2025-12-04 16:17:01', 'notas.php'),
(551, 4, '::1', '2025-12-04 16:18:21', 'index.php'),
(552, 4, '::1', '2025-12-04 16:18:22', 'notas.php'),
(553, 2, '::1', '2025-12-04 16:19:15', 'index.php'),
(554, 2, '::1', '2025-12-04 16:19:18', 'admin_notas_pendientes.php'),
(555, 2, '::1', '2025-12-04 16:19:24', 'admin_notas_pendientes.php'),
(556, 2, '::1', '2025-12-04 16:19:26', 'inscripcion_materias.php'),
(557, 2, '::1', '2025-12-04 16:19:29', 'inscripcion_materias.php'),
(558, 2, '::1', '2025-12-04 16:19:47', 'index.php'),
(559, 2, '::1', '2025-12-04 16:19:53', 'notas.php'),
(560, 2, '::1', '2025-12-04 16:20:11', 'index.php'),
(561, 2, '::1', '2025-12-04 16:20:14', 'admin_notas_pendientes.php'),
(562, 2, '::1', '2025-12-04 16:20:18', 'admin_notas_pendientes.php'),
(563, 2, '::1', '2025-12-04 16:20:21', 'inscripcion_materias.php'),
(564, 2, '::1', '2025-12-04 16:20:41', 'admin_notas_pendientes.php'),
(565, 2, '::1', '2025-12-04 16:20:44', 'consulta_notas.php'),
(566, 2, '::1', '2025-12-04 16:20:46', 'consulta_notas.php'),
(567, 2, '::1', '2025-12-04 16:21:10', 'inscripcion_materias.php'),
(568, 2, '::1', '2025-12-04 16:22:32', 'inscripcion_materias.php'),
(569, 2, '::1', '2025-12-04 16:22:44', 'inscripcion_materias.php'),
(570, 2, '::1', '2025-12-04 16:44:35', 'index.php'),
(571, 2, '::1', '2025-12-04 16:44:38', 'inscripcion_materias.php'),
(572, 2, '::1', '2025-12-04 16:46:47', 'inscripcion_materias.php'),
(573, 2, '::1', '2025-12-04 17:12:54', 'index.php'),
(574, 2, '::1', '2025-12-04 17:12:56', 'inscripcion_materias.php'),
(575, 2, '::1', '2025-12-04 17:13:01', 'inscripcion_materias.php'),
(576, 2, '::1', '2025-12-04 17:13:20', 'inscripcion_materias.php'),
(577, 2, '::1', '2025-12-04 17:16:42', 'index.php'),
(578, 2, '::1', '2025-12-04 17:16:44', 'inscripcion_materias.php'),
(579, 2, '::1', '2025-12-04 17:16:47', 'inscripcion_materias.php'),
(580, 2, '::1', '2025-12-04 17:17:05', 'inscripcion_materias.php'),
(581, 2, '::1', '2025-12-04 17:23:45', 'index.php'),
(582, 2, '::1', '2025-12-04 17:23:47', 'inscripcion_materias.php'),
(583, 2, '::1', '2025-12-04 17:23:59', 'inscripcion_materias.php'),
(584, 2, '::1', '2025-12-04 17:30:16', 'inscripcion_materias.php'),
(585, 2, '::1', '2025-12-04 17:30:17', 'inscripcion_materias.php'),
(586, 2, '::1', '2025-12-04 17:30:20', 'inscripcion_materias.php'),
(587, 2, '::1', '2025-12-04 17:30:22', 'inscripcion_materias.php'),
(588, 2, '::1', '2025-12-04 17:30:32', 'inscripcion_materias.php'),
(589, 2, '::1', '2026-01-13 14:24:06', 'index.php'),
(590, 2, '::1', '2026-01-13 14:25:23', 'inscripcion_materias.php'),
(591, 2, '::1', '2026-01-13 14:25:26', 'inscripcion_materias.php'),
(592, 2, '::1', '2026-01-13 14:25:52', 'consulta_notas.php'),
(593, 2, '::1', '2026-01-13 14:25:54', 'consulta_notas.php'),
(594, 2, '::1', '2026-01-13 14:33:26', 'agregar_carrera.php'),
(595, 2, '::1', '2026-01-13 14:33:36', 'agregar_carrera.php'),
(596, 2, '::1', '2026-01-13 14:51:06', 'agregar_carrera.php'),
(597, 2, '::1', '2026-01-13 15:03:52', 'agregar_carrera.php'),
(598, 2, '::1', '2026-01-13 15:10:38', 'agregar_carrera.php'),
(599, 2, '::1', '2026-01-13 15:10:56', 'agregar_carrera.php'),
(600, 2, '::1', '2026-01-13 15:10:59', 'agregar_carrera.php'),
(601, 2, '::1', '2026-01-13 15:24:36', 'agregar_carrera.php'),
(602, 2, '::1', '2026-01-13 15:24:53', 'agregar_carrera.php'),
(603, 2, '::1', '2026-01-13 15:28:52', 'agregar_carrera.php'),
(604, 2, '::1', '2026-01-13 15:33:58', 'agregar_carrera.php'),
(605, 2, '::1', '2026-01-13 15:34:11', 'materia.php'),
(606, 2, '::1', '2026-01-13 15:44:12', 'materia.php'),
(607, 2, '::1', '2026-01-13 15:44:15', 'carrera_materias.php'),
(608, 2, '::1', '2026-01-13 15:44:20', 'materia.php'),
(609, 2, '::1', '2026-01-13 15:51:14', 'index.php'),
(610, 2, '::1', '2026-01-13 15:51:47', 'agregar_carrera.php'),
(611, 2, '::1', '2026-01-13 15:52:24', 'materia.php'),
(612, 2, '::1', '2026-01-13 16:15:00', 'materia.php'),
(613, 2, '::1', '2026-01-13 16:15:00', 'materia.php'),
(614, 2, '::1', '2026-01-13 16:15:11', 'carrera_materias.php'),
(615, 2, '::1', '2026-01-13 16:15:42', 'carrera_materias.php'),
(616, 2, '::1', '2026-01-13 16:15:47', 'agregar_carrera.php'),
(617, 2, '::1', '2026-01-13 16:15:54', 'agregar_carrera.php'),
(618, 2, '::1', '2026-01-13 16:17:22', 'agregar_carrera.php'),
(619, 2, '::1', '2026-01-13 16:26:30', 'agregar_carrera.php'),
(620, 2, '::1', '2026-01-13 16:28:22', 'agregar_carrera.php'),
(621, 2, '::1', '2026-01-13 16:34:44', 'agregar_carrera.php'),
(622, 2, '::1', '2026-01-13 16:37:39', 'agregar_carrera.php'),
(623, 2, '::1', '2026-01-13 16:38:15', 'agregar_carrera.php'),
(624, 2, '::1', '2026-01-13 16:40:09', 'agregar_carrera.php'),
(625, 2, '::1', '2026-01-13 16:40:24', 'agregar_carrera.php'),
(626, 2, '::1', '2026-01-13 16:40:30', 'materia.php'),
(627, 2, '::1', '2026-01-13 16:40:36', 'carrera_materias.php'),
(628, 2, '::1', '2026-01-13 16:40:41', 'materia.php'),
(629, 2, '::1', '2026-01-13 16:41:36', 'materia.php'),
(630, 2, '::1', '2026-01-13 16:41:36', 'materia.php'),
(631, 2, '::1', '2026-01-13 16:41:42', 'carrera_materias.php'),
(632, 2, '::1', '2026-01-13 16:42:57', 'agregar_carrera.php'),
(633, 2, '::1', '2026-01-13 16:43:16', 'agregar_carrera.php'),
(634, 2, '::1', '2026-01-13 16:43:20', 'agregar_carrera.php'),
(635, 2, '::1', '2026-01-13 16:44:22', 'agregar_carrera.php'),
(636, 2, '::1', '2026-01-13 16:44:31', 'carrera_materias.php'),
(637, 2, '::1', '2026-01-13 16:45:08', 'agregar_carrera.php'),
(638, 2, '::1', '2026-01-13 16:46:03', 'agregar_carrera.php'),
(639, 2, '::1', '2026-01-13 16:46:10', 'agregar_carrera.php'),
(640, 2, '::1', '2026-01-13 16:46:11', 'materia.php'),
(641, 2, '::1', '2026-01-13 16:46:25', 'carrera_materias.php'),
(642, 2, '::1', '2026-01-13 16:46:42', 'carrera_materias.php'),
(643, 2, '::1', '2026-01-13 16:46:47', 'agregar_carrera.php'),
(644, 2, '::1', '2026-01-13 16:46:54', 'agregar_carrera.php'),
(645, 2, '::1', '2026-01-13 16:48:10', 'materia.php'),
(646, 2, '::1', '2026-01-13 16:48:14', 'carrera_materias.php'),
(647, 2, '::1', '2026-01-13 16:48:35', 'agregar_estudiante.php'),
(648, 2, '::1', '2026-01-13 16:48:35', 'agregar_estudiante.php'),
(649, 2, '::1', '2026-01-13 16:48:38', 'carrera_materias.php'),
(650, 2, '::1', '2026-01-13 16:51:25', 'carrera_materias.php'),
(651, 2, '::1', '2026-01-13 16:51:26', 'carrera_materias.php'),
(652, 2, '::1', '2026-01-13 16:51:26', 'carrera_materias.php'),
(653, 2, '::1', '2026-01-13 16:53:23', 'carrera_materias.php'),
(654, 2, '::1', '2026-01-13 16:54:40', 'carrera_materias.php'),
(655, 2, '::1', '2026-01-13 16:54:42', 'carrera_materias.php'),
(656, 2, '::1', '2026-01-13 16:54:44', 'carrera_materias.php'),
(657, 2, '::1', '2026-01-13 16:54:45', 'carrera_materias.php'),
(658, 2, '::1', '2026-01-13 16:54:46', 'carrera_materias.php'),
(659, 2, '::1', '2026-01-13 16:55:12', 'carrera_materias.php'),
(660, 2, '::1', '2026-01-13 16:56:05', 'carrera_materias.php'),
(661, 2, '::1', '2026-01-13 16:56:06', 'carrera_materias.php'),
(662, 2, '::1', '2026-01-13 16:56:06', 'carrera_materias.php'),
(663, 2, '::1', '2026-01-13 16:56:07', 'carrera_materias.php'),
(664, 2, '::1', '2026-01-13 17:01:45', 'carrera_materias.php'),
(665, 2, '::1', '2026-01-13 17:03:53', 'carrera_materias.php'),
(666, 2, '::1', '2026-01-13 17:03:54', 'carrera_materias.php'),
(667, 2, '::1', '2026-01-13 17:03:55', 'carrera_materias.php'),
(668, 2, '::1', '2026-01-13 17:03:55', 'carrera_materias.php'),
(669, 2, '::1', '2026-01-13 17:11:42', 'carrera_materias.php'),
(670, 2, '::1', '2026-01-13 17:11:43', 'carrera_materias.php'),
(671, 2, '::1', '2026-01-13 17:11:43', 'carrera_materias.php'),
(672, 2, '::1', '2026-01-13 17:16:25', 'carrera_materias.php'),
(673, 2, '::1', '2026-01-13 17:16:26', 'carrera_materias.php'),
(674, 2, '::1', '2026-01-13 17:16:27', 'carrera_materias.php'),
(675, 2, '::1', '2026-01-13 17:18:24', 'carrera_materias.php'),
(676, 2, '::1', '2026-01-13 17:18:40', 'carrera_materias.php'),
(677, 2, '::1', '2026-01-13 17:19:44', 'carrera_materias.php'),
(678, 2, '::1', '2026-01-13 17:19:49', 'agregar_carrera.php'),
(679, 2, '::1', '2026-01-13 17:19:58', 'agregar_carrera.php'),
(680, 2, '::1', '2026-01-13 17:21:40', 'agregar_carrera.php'),
(681, 2, '::1', '2026-01-13 17:25:24', 'agregar_carrera.php'),
(682, 2, '::1', '2026-01-13 17:30:35', 'agregar_carrera.php'),
(683, 2, '::1', '2026-01-13 17:30:36', 'agregar_carrera.php'),
(684, 2, '::1', '2026-01-13 17:30:46', 'agregar_carrera.php'),
(685, 2, '::1', '2026-01-13 17:30:56', 'agregar_carrera.php'),
(686, 2, '::1', '2026-01-13 17:31:01', 'agregar_carrera.php'),
(687, 2, '::1', '2026-01-13 17:31:10', 'agregar_carrera.php'),
(688, 2, '::1', '2026-01-13 17:31:18', 'agregar_carrera.php'),
(689, 2, '::1', '2026-01-13 17:31:22', 'agregar_carrera.php'),
(690, 2, '::1', '2026-01-13 17:34:55', 'agregar_carrera.php'),
(691, 2, '::1', '2026-01-13 17:35:09', 'agregar_carrera.php'),
(692, 2, '::1', '2026-01-13 17:35:16', 'agregar_carrera.php'),
(693, 2, '::1', '2026-01-13 17:35:24', 'agregar_carrera.php'),
(694, 2, '::1', '2026-01-13 17:36:35', 'materia.php'),
(695, 2, '::1', '2026-01-13 17:37:31', 'materia.php'),
(696, 2, '::1', '2026-01-13 17:37:32', 'materia.php'),
(697, 2, '::1', '2026-01-13 17:37:38', 'carrera_materias.php'),
(698, 2, '::1', '2026-01-13 17:38:09', 'carrera_materias.php'),
(699, 2, '::1', '2026-01-13 17:38:15', 'agregar_carrera.php'),
(700, 2, '::1', '2026-01-13 17:38:21', 'agregar_carrera.php'),
(701, 2, '::1', '2026-01-13 17:38:26', 'agregar_carrera.php'),
(702, 2, '::1', '2026-01-13 17:38:32', 'agregar_carrera.php'),
(703, 2, '::1', '2026-01-13 17:42:08', 'agregar_carrera.php'),
(704, 2, '::1', '2026-01-13 17:42:13', 'carrera_materias.php'),
(705, 2, '::1', '2026-01-13 17:42:25', 'carrera_materias.php'),
(706, 2, '::1', '2026-01-13 17:42:26', 'carrera_materias.php'),
(707, 2, '::1', '2026-01-13 17:44:15', 'carrera_materias.php'),
(708, 2, '::1', '2026-01-13 17:44:16', 'carrera_materias.php'),
(709, 2, '::1', '2026-01-13 17:44:32', 'agregar_carrera.php'),
(710, 2, '::1', '2026-01-13 18:11:29', 'agregar_carrera.php'),
(711, 2, '::1', '2026-01-13 18:11:42', 'agregar_carrera.php'),
(712, 2, '::1', '2026-01-13 18:12:33', 'inscripcion_materias.php'),
(713, 2, '::1', '2026-01-13 18:15:42', 'registro_pagos.php'),
(714, 2, '::1', '2026-01-13 18:15:44', 'registro_pagos.php'),
(715, 2, '::1', '2026-01-13 18:15:49', 'mensajeria.php'),
(716, 2, '::1', '2026-01-13 18:18:29', 'mensajeria.php'),
(717, 2, '::1', '2026-01-13 18:18:34', 'mensajeria.php'),
(718, 2, '::1', '2026-01-13 18:24:11', 'mensajeria.php'),
(719, 2, '::1', '2026-01-13 18:28:38', 'agregar_carrera.php'),
(720, 2, '::1', '2026-01-13 18:29:10', 'agregar_carrera.php'),
(721, 2, '::1', '2026-01-13 18:40:56', 'agregar_carrera.php'),
(722, 2, '::1', '2026-01-14 13:51:21', 'index.php'),
(723, 2, '::1', '2026-01-14 13:57:09', 'agregar_carrera.php'),
(724, 2, '::1', '2026-01-14 14:10:27', 'agregar_carrera.php'),
(725, 2, '::1', '2026-01-14 14:15:29', 'agregar_carrera.php'),
(726, 2, '::1', '2026-01-14 14:21:33', 'agregar_carrera.php'),
(727, 2, '::1', '2026-01-14 14:21:33', 'agregar_carrera.php'),
(728, 2, '::1', '2026-01-14 14:22:07', 'agregar_carrera.php'),
(729, 2, '::1', '2026-01-14 14:42:42', 'agregar_carrera.php'),
(730, 2, '::1', '2026-01-14 14:42:43', 'agregar_carrera.php'),
(731, 2, '::1', '2026-01-14 14:42:57', 'index.php'),
(732, 2, '::1', '2026-01-14 14:43:00', 'agregar_carrera.php'),
(733, 2, '::1', '2026-01-14 14:56:38', 'agregar_carrera.php'),
(734, 2, '::1', '2026-01-14 15:06:04', 'agregar_carrera.php'),
(735, 2, '::1', '2026-01-14 15:06:10', 'agregar_carrera.php'),
(736, 2, '::1', '2026-01-14 15:06:26', 'agregar_carrera.php'),
(737, 2, '::1', '2026-01-14 15:09:01', 'agregar_carrera.php'),
(738, 2, '::1', '2026-01-14 15:17:45', 'agregar_carrera.php'),
(739, 2, '::1', '2026-01-14 15:18:03', 'agregar_carrera.php'),
(740, 2, '::1', '2026-01-14 15:18:12', 'agregar_carrera.php'),
(741, 2, '::1', '2026-01-14 15:18:30', 'agregar_carrera.php'),
(742, 2, '::1', '2026-01-14 15:31:03', 'agregar_carrera.php'),
(743, 2, '::1', '2026-01-14 15:31:12', 'agregar_carrera.php'),
(744, 2, '::1', '2026-01-14 15:32:00', 'agregar_carrera.php'),
(745, 2, '::1', '2026-01-14 15:32:05', 'agregar_carrera.php'),
(746, 2, '::1', '2026-01-14 15:32:19', 'agregar_carrera.php'),
(747, 2, '::1', '2026-01-14 15:58:59', 'materia.php'),
(748, 2, '::1', '2026-01-14 16:06:19', 'materia.php'),
(749, 2, '::1', '2026-01-14 16:07:07', 'materia.php'),
(750, 2, '::1', '2026-01-14 16:09:36', 'materia.php'),
(751, 2, '::1', '2026-01-14 16:09:39', 'agregar_carrera.php'),
(752, 2, '::1', '2026-01-14 16:09:52', 'agregar_carrera.php'),
(753, 2, '::1', '2026-01-14 16:09:58', 'materia.php'),
(754, 2, '::1', '2026-01-14 16:11:54', 'materia.php'),
(755, 2, '::1', '2026-01-14 16:11:55', 'materia.php'),
(756, 2, '::1', '2026-01-14 16:12:25', 'carrera_materias.php'),
(757, 2, '::1', '2026-01-14 16:13:15', 'carrera_materias.php'),
(758, 2, '::1', '2026-01-14 16:13:22', 'agregar_carrera.php'),
(759, 2, '::1', '2026-01-14 16:15:43', 'carrera_materias.php'),
(760, 2, '::1', '2026-01-14 16:16:19', 'carrera_materias.php'),
(761, 2, '::1', '2026-01-14 16:17:04', 'carrera_materias.php'),
(762, 2, '::1', '2026-01-14 16:17:09', 'agregar_carrera.php'),
(763, 2, '::1', '2026-01-14 16:18:22', 'materia.php'),
(764, 2, '::1', '2026-01-14 16:19:02', 'materia.php'),
(765, 2, '::1', '2026-01-14 16:22:46', 'materia.php'),
(766, 2, '::1', '2026-01-14 16:22:55', 'materia.php'),
(767, 2, '::1', '2026-01-14 16:22:55', 'materia.php'),
(768, 2, '::1', '2026-01-14 16:23:36', 'carrera_materias.php'),
(769, 2, '::1', '2026-01-14 16:23:42', 'agregar_carrera.php'),
(770, 2, '::1', '2026-01-14 16:25:38', 'carrera_materias.php'),
(771, 2, '::1', '2026-01-14 16:25:51', 'carrera_materias.php'),
(772, 2, '::1', '2026-01-14 16:26:21', 'carrera_materias.php'),
(773, 2, '::1', '2026-01-14 16:26:45', 'carrera_materias.php'),
(774, 2, '::1', '2026-01-14 16:26:54', 'agregar_carrera.php'),
(775, 2, '::1', '2026-01-14 16:30:57', 'index.php'),
(776, 2, '::1', '2026-01-14 16:32:15', 'agregar_carrera.php'),
(777, 2, '::1', '2026-01-16 13:48:35', 'index.php'),
(778, 2, '::1', '2026-01-16 13:48:43', 'inscripcion_materias.php'),
(779, 2, '::1', '2026-01-16 13:48:47', 'inscripcion_materias.php'),
(780, 2, '::1', '2026-01-16 13:59:58', 'index.php'),
(781, 2, '::1', '2026-01-16 14:00:13', 'index.php'),
(782, 2, '::1', '2026-01-16 14:00:33', 'agregar_carrera.php'),
(783, 2, '::1', '2026-01-16 14:00:45', 'agregar_carrera.php'),
(784, 2, '::1', '2026-01-16 14:00:57', 'agregar_carrera.php'),
(785, 2, '::1', '2026-01-16 14:01:45', 'agregar_carrera.php'),
(786, 2, '::1', '2026-01-16 14:04:05', 'agregar_carrera.php'),
(787, 2, '::1', '2026-01-16 14:04:27', 'index.php'),
(788, 2, '::1', '2026-01-16 14:04:34', 'mi_historial.php'),
(789, 2, '::1', '2026-01-16 14:04:42', 'mi_horario.php'),
(790, 2, '::1', '2026-01-16 14:04:44', 'mis_secciones.php'),
(791, 2, '::1', '2026-01-16 14:04:46', 'mi_pensum.php'),
(792, 2, '::1', '2026-01-16 14:15:07', 'mis_secciones.php'),
(793, 2, '::1', '2026-01-16 14:15:11', 'mi_pensum.php'),
(794, 2, '::1', '2026-01-16 14:15:16', 'mi_pensum.php'),
(795, 2, '::1', '2026-01-16 14:16:11', 'mi_pensum.php'),
(796, 2, '::1', '2026-01-16 14:19:02', 'mensajeria_estudiantes.php'),
(797, 2, '::1', '2026-01-16 14:19:12', 'mi_horario.php'),
(798, 2, '::1', '2026-01-16 14:19:15', 'mis_secciones.php'),
(799, 2, '::1', '2026-01-16 14:19:18', 'mi_pensum.php'),
(800, 5, '::1', '2026-01-16 14:19:28', 'index.php'),
(801, 5, '::1', '2026-01-16 14:19:34', 'mensajeria_estudiantes.php'),
(802, 5, '::1', '2026-01-16 14:19:37', 'mi_horario.php'),
(803, 5, '::1', '2026-01-16 14:20:02', 'mis_secciones.php'),
(804, 5, '::1', '2026-01-16 14:20:05', 'mi_pensum.php'),
(805, 5, '::1', '2026-01-16 14:20:11', 'mi_historial.php'),
(806, 5, '::1', '2026-01-16 14:20:25', 'index.php'),
(807, 2, '::1', '2026-01-16 14:20:37', 'index.php'),
(808, 2, '::1', '2026-01-16 14:20:59', 'materia.php'),
(809, 2, '::1', '2026-01-16 14:21:07', 'agregar_carrera.php'),
(810, 5, '::1', '2026-01-16 14:40:29', 'index.php'),
(811, 5, '::1', '2026-01-16 14:40:36', 'mi_pensum.php'),
(812, 5, '::1', '2026-01-16 14:41:56', 'mi_pensum.php'),
(813, 5, '::1', '2026-01-16 15:01:48', 'mi_pensum.php'),
(814, 5, '::1', '2026-01-16 15:02:02', 'mi_pensum.php'),
(815, 5, '::1', '2026-01-16 15:02:30', 'mi_pensum.php'),
(816, 2, '::1', '2026-01-16 15:02:38', 'index.php'),
(817, 2, '::1', '2026-01-16 15:02:41', 'agregar_carrera.php'),
(818, 5, '::1', '2026-01-16 15:11:12', 'index.php'),
(819, 5, '::1', '2026-01-16 15:11:16', 'mi_pensum.php'),
(820, 5, '::1', '2026-01-16 15:11:17', 'mi_pensum.php'),
(821, 5, '::1', '2026-01-16 15:11:31', 'mi_pensum.php'),
(822, 5, '::1', '2026-01-16 15:11:37', 'index.php'),
(823, 5, '::1', '2026-01-16 15:11:53', 'index.php'),
(824, 5, '::1', '2026-01-16 15:27:18', 'index.php'),
(825, 5, '::1', '2026-01-16 15:27:21', 'mi_pensum.php'),
(826, 5, '::1', '2026-01-16 15:27:36', 'mi_pensum.php'),
(827, 5, '::1', '2026-01-16 15:27:46', 'mi_pensum.php'),
(828, 2, '::1', '2026-01-16 15:27:54', 'index.php'),
(829, 2, '::1', '2026-01-16 15:28:00', 'correccion_notas.php'),
(830, 2, '::1', '2026-01-16 15:28:02', 'correccion_notas.php'),
(831, 2, '::1', '2026-01-16 15:28:05', 'correccion_notas.php'),
(832, 2, '::1', '2026-01-16 15:39:17', 'index.php'),
(833, 2, '::1', '2026-01-16 15:39:20', 'index.php'),
(834, 2, '::1', '2026-01-16 15:39:46', 'index.php'),
(835, 2, '::1', '2026-01-16 15:39:50', 'index.php'),
(836, 2, '::1', '2026-01-16 15:58:58', 'index.php'),
(837, 2, '::1', '2026-01-16 15:59:00', 'constancias.php'),
(838, 2, '::1', '2026-01-16 16:07:25', 'constancias.php'),
(839, 2, '::1', '2026-01-16 16:07:36', 'constancias.php'),
(840, 2, '::1', '2026-01-16 16:08:18', 'constancias.php'),
(841, 2, '::1', '2026-01-16 16:10:24', 'constancias.php'),
(842, 2, '::1', '2026-01-16 16:10:36', 'constancias.php'),
(843, 2, '::1', '2026-01-16 16:14:42', 'constancias.php'),
(844, 2, '::1', '2026-01-16 16:15:10', 'constancias.php'),
(845, 2, '::1', '2026-01-16 16:15:17', 'constancias.php'),
(846, 2, '::1', '2026-01-16 16:15:21', 'constancias.php'),
(847, 2, '::1', '2026-01-16 16:15:22', 'constancias.php'),
(848, 2, '::1', '2026-01-16 16:15:31', 'constancias.php'),
(849, 2, '::1', '2026-01-16 16:34:39', 'index.php'),
(850, 2, '::1', '2026-01-16 16:34:41', 'constancias.php'),
(851, 2, '::1', '2026-01-16 16:34:44', 'constancias.php'),
(852, 2, '::1', '2026-01-16 16:48:13', 'constancias.php'),
(853, 2, '::1', '2026-01-16 16:59:36', 'constancias.php'),
(854, 2, '::1', '2026-01-16 17:00:21', 'gestion_seccion.php'),
(855, 2, '::1', '2026-01-16 17:00:24', 'gestion_seccion.php');
INSERT INTO `visitas` (`id`, `id_usuario`, `ip`, `fecha_visita`, `web`) VALUES
(856, 2, '::1', '2026-01-16 17:00:28', 'gestion_seccion.php'),
(857, 2, '::1', '2026-01-16 17:00:29', 'gestion_seccion.php'),
(858, 2, '::1', '2026-01-16 17:00:48', 'periodos_academicos.php'),
(859, 2, '::1', '2026-01-16 17:01:32', 'periodos_academicos.php'),
(860, 2, '::1', '2026-01-16 17:01:32', 'periodos_academicos.php'),
(861, 2, '::1', '2026-01-16 17:01:52', 'gestion_seccion.php'),
(862, 2, '::1', '2026-01-16 17:02:01', 'gestion_seccion.php'),
(863, 2, '::1', '2026-01-16 17:04:05', 'gestion_seccion.php'),
(864, 2, '::1', '2026-01-16 17:04:09', 'gestion_seccion.php'),
(865, 2, '::1', '2026-01-16 17:04:22', 'gestion_seccion.php'),
(866, 2, '::1', '2026-01-16 17:05:31', 'gestion_seccion.php'),
(867, 2, '::1', '2026-01-16 17:05:34', 'gestion_seccion.php'),
(868, 2, '::1', '2026-01-16 17:05:41', 'tipos_horario.php'),
(869, 2, '::1', '2026-01-16 17:06:08', 'gestion_horario_personal.php'),
(870, 2, '::1', '2026-01-16 17:06:21', 'tipos_horario.php'),
(871, 2, '::1', '2026-01-16 17:07:18', 'gestion_seccion.php'),
(872, 2, '::1', '2026-01-16 17:07:20', 'gestion_seccion.php'),
(873, 2, '::1', '2026-01-16 17:13:37', 'constancias.php'),
(874, 2, '::1', '2026-01-16 17:13:39', 'constancias.php'),
(875, 2, '::1', '2026-01-16 17:14:52', 'constancias.php'),
(876, 2, '::1', '2026-01-16 17:17:08', 'index.php'),
(877, 2, '::1', '2026-01-16 17:17:17', 'constancias.php'),
(878, 2, '::1', '2026-01-16 17:17:19', 'constancias.php'),
(879, 2, '::1', '2026-01-16 17:17:43', 'constancias.php'),
(880, 2, '::1', '2026-01-16 17:19:02', 'constancias.php'),
(881, 2, '::1', '2026-01-16 17:20:30', 'constancias.php'),
(882, 2, '::1', '2026-01-16 17:23:38', 'constancias.php'),
(883, 2, '::1', '2026-01-19 13:19:21', 'index.php'),
(884, 2, '::1', '2026-01-19 13:20:06', 'admin_notas_pendientes.php'),
(885, 2, '::1', '2026-01-19 13:20:08', 'consulta_notas.php'),
(886, 4, '::1', '2026-01-19 13:20:53', 'index.php'),
(887, 4, '::1', '2026-01-19 13:20:55', 'notas.php'),
(888, 4, '::1', '2026-01-19 15:36:43', 'notas.php'),
(889, 2, '::1', '2026-01-20 14:43:44', 'index.php'),
(890, 2, '::1', '2026-01-20 14:44:40', 'consulta_notas.php'),
(891, 2, '::1', '2026-01-20 14:44:42', 'consulta_notas.php'),
(892, 2, '::1', '2026-01-20 14:45:02', 'admin_notas_pendientes.php'),
(893, 2, '::1', '2026-01-20 14:45:06', 'notas_pasadas.php'),
(894, 2, '::1', '2026-01-20 15:49:06', 'notas_pasadas.php'),
(895, 2, '::1', '2026-01-20 16:01:31', 'notas_pasadas.php'),
(896, 2, '::1', '2026-01-20 16:16:49', 'notas_pasadas.php'),
(897, 2, '::1', '2026-01-20 16:20:31', 'notas_pasadas.php'),
(898, 2, '::1', '2026-01-20 16:20:36', 'registro_pagos.php'),
(899, 2, '::1', '2026-01-20 16:47:44', 'notas_pasadas.php'),
(900, 2, '::1', '2026-01-20 16:55:33', 'notas_pasadas.php'),
(901, 2, '::1', '2026-01-20 16:55:34', 'notas_pasadas.php'),
(902, 2, '::1', '2026-01-20 16:56:49', 'notas_pasadas.php'),
(903, 2, '::1', '2026-01-20 16:57:03', 'agregar_carrera.php'),
(904, 2, '::1', '2026-01-20 17:01:27', 'correccion_notas.php'),
(905, 2, '::1', '2026-01-20 17:01:29', 'notas_pasadas.php'),
(906, 2, '::1', '2026-01-20 17:09:27', 'notas_pasadas.php'),
(907, 2, '::1', '2026-01-20 17:09:30', 'notas_pasadas.php'),
(908, 2, '::1', '2026-01-20 17:12:34', 'notas_pasadas.php'),
(909, 2, '::1', '2026-01-20 17:12:40', 'consulta_notas.php'),
(910, 2, '::1', '2026-01-20 17:12:42', 'consulta_notas.php'),
(911, 2, '::1', '2026-01-20 17:15:43', 'notas_pasadas.php'),
(912, 2, '::1', '2026-01-20 17:18:40', 'notas_pasadas.php'),
(913, 2, '::1', '2026-01-20 17:19:05', 'notas_pasadas.php'),
(914, 2, '::1', '2026-01-20 17:19:43', 'notas_pasadas.php'),
(915, 2, '::1', '2026-01-20 17:22:11', 'notas_pasadas.php'),
(916, 2, '::1', '2026-01-20 17:24:34', 'notas_pasadas.php'),
(917, 2, '::1', '2026-01-20 17:28:03', 'notas_pasadas.php'),
(918, 2, '::1', '2026-01-20 17:28:06', 'consulta_notas.php'),
(919, 2, '::1', '2026-01-20 17:28:09', 'consulta_notas.php'),
(920, 2, '::1', '2026-01-20 17:29:09', 'gestion_seccion.php'),
(921, 2, '::1', '2026-01-20 17:29:12', 'gestion_seccion.php'),
(922, 2, '::1', '2026-01-20 17:29:21', 'gestion_seccion.php'),
(923, 2, '::1', '2026-01-20 17:29:59', 'index.php'),
(924, 2, '::1', '2026-01-20 17:41:30', 'index.php'),
(925, 4, '::1', '2026-01-20 17:47:30', 'index.php'),
(926, 4, '::1', '2026-01-20 17:50:08', 'index.php'),
(927, 4, '::1', '2026-01-20 17:50:26', 'notas.php'),
(928, 2, '::1', '2026-01-21 13:33:20', 'index.php'),
(929, 2, '::1', '2026-01-21 13:47:08', 'visita.php'),
(930, 2, '::1', '2026-01-21 13:47:12', 'visita.php'),
(931, 2, '::1', '2026-01-21 13:47:12', 'visita.php'),
(932, 2, '::1', '2026-01-21 13:47:14', 'visita.php'),
(933, 2, '::1', '2026-01-21 13:52:49', 'index.php'),
(934, 4, '::1', '2026-01-21 13:53:25', 'index.php'),
(935, 4, '::1', '2026-01-21 13:53:27', 'notas.php'),
(936, 4, '::1', '2026-01-21 14:26:02', 'notas.php'),
(937, 2, '::1', '2026-01-21 14:26:09', 'index.php'),
(938, 2, '::1', '2026-01-21 14:26:13', 'actas_calificacion.php'),
(939, 2, '::1', '2026-01-21 14:27:07', 'actas_calificacion.php'),
(940, 2, '::1', '2026-01-21 14:28:43', 'carrera_materias.php'),
(941, 2, '::1', '2026-01-21 14:31:17', 'actas_calificacion.php'),
(942, 2, '::1', '2026-01-21 14:45:48', 'actas_calificacion.php'),
(943, 2, '::1', '2026-01-21 14:56:04', 'actas_calificacion.php'),
(944, 2, '::1', '2026-01-21 14:56:06', 'actas_calificacion.php'),
(945, 2, '::1', '2026-01-21 14:59:34', 'actas_calificacion.php'),
(946, 2, '::1', '2026-01-21 14:59:35', 'actas_calificacion.php'),
(947, 2, '::1', '2026-01-21 15:10:28', 'actas_calificacion.php'),
(948, 2, '::1', '2026-01-21 15:10:29', 'actas_calificacion.php'),
(949, 2, '::1', '2026-01-21 15:10:31', 'actas_calificacion.php'),
(950, 2, '::1', '2026-01-21 15:19:03', 'actas_calificacion.php'),
(951, 2, '::1', '2026-01-21 15:19:05', 'actas_calificacion.php'),
(952, 4, '::1', '2026-01-21 15:20:18', 'index.php'),
(953, 4, '::1', '2026-01-21 15:20:19', 'notas.php'),
(954, 2, '::1', '2026-01-21 15:20:33', 'index.php'),
(955, 2, '::1', '2026-01-21 15:20:37', 'actas_calificacion.php'),
(956, 4, '::1', '2026-01-21 15:21:51', 'index.php'),
(957, 4, '::1', '2026-01-21 15:21:52', 'notas.php'),
(958, 2, '::1', '2026-01-21 15:24:08', 'index.php'),
(959, 2, '::1', '2026-01-21 15:24:11', 'actas_calificacion.php'),
(960, 2, '::1', '2026-01-21 15:26:44', 'actas_calificacion.php'),
(961, 2, '::1', '2026-01-22 13:56:14', 'index.php'),
(962, 4, '::1', '2026-01-22 13:57:08', 'index.php'),
(963, 4, '::1', '2026-01-22 13:57:09', 'notas.php'),
(964, 4, '::1', '2026-01-22 15:17:21', 'index.php'),
(965, 2, '::1', '2026-01-23 13:33:34', 'index.php'),
(966, 4, '::1', '2026-01-23 13:35:35', 'index.php'),
(967, 4, '::1', '2026-01-23 13:35:36', 'notas.php'),
(968, 2, '::1', '2026-01-23 13:35:58', 'index.php'),
(969, 2, '::1', '2026-01-23 13:36:04', 'notas_pasadas.php'),
(970, 2, '::1', '2026-01-23 13:37:46', 'notas_pasadas.php'),
(971, 2, '::1', '2026-01-23 13:55:05', 'notas_pasadas.php'),
(972, 2, '::1', '2026-01-23 14:20:14', 'notas_pasadas.php'),
(973, 2, '::1', '2026-01-23 14:20:20', 'correccion_notas.php'),
(974, 2, '::1', '2026-01-23 14:20:26', 'correccion_notas.php'),
(975, 2, '::1', '2026-01-23 14:20:29', 'correccion_notas.php'),
(976, 2, '::1', '2026-01-23 14:21:12', 'correccion_notas.php'),
(977, 2, '::1', '2026-01-23 14:39:47', 'correccion_notas.php'),
(978, 2, '::1', '2026-01-23 14:55:55', 'correccion_notas.php'),
(979, 2, '::1', '2026-01-23 15:03:05', 'correccion_notas.php'),
(980, 2, '::1', '2026-01-23 15:03:11', 'index.php'),
(981, 2, '::1', '2026-01-23 15:03:17', 'correccion_notas.php'),
(982, 2, '::1', '2026-01-23 15:03:20', 'correccion_notas.php'),
(983, 2, '::1', '2026-01-23 15:03:23', 'correccion_notas.php'),
(984, 2, '::1', '2026-01-23 15:03:26', 'correccion_notas.php'),
(985, 2, '::1', '2026-01-23 15:04:26', 'correccion_notas.php'),
(986, 2, '::1', '2026-01-23 15:04:57', 'correccion_notas.php'),
(987, 2, '::1', '2026-01-23 15:09:54', 'correccion_notas.php'),
(988, 2, '::1', '2026-01-23 15:40:41', 'correccion_notas.php'),
(989, 2, '::1', '2026-01-23 15:40:58', 'correccion_notas.php'),
(990, 2, '::1', '2026-01-23 15:43:05', 'notas_pasadas.php'),
(991, 2, '::1', '2026-01-23 15:44:36', 'correccion_notas.php'),
(992, 2, '::1', '2026-01-23 15:44:38', 'correccion_notas.php'),
(993, 2, '::1', '2026-01-23 15:44:40', 'correccion_notas.php'),
(994, 2, '::1', '2026-01-23 15:44:43', 'correccion_notas.php'),
(995, 2, '::1', '2026-01-23 16:05:03', 'correccion_notas.php'),
(996, 2, '::1', '2026-01-23 16:14:56', 'consulta_notas.php'),
(997, 2, '::1', '2026-01-23 16:14:58', 'consulta_notas.php'),
(998, 2, '::1', '2026-01-23 16:49:03', 'consulta_notas.php'),
(999, 2, '::1', '2026-01-23 16:50:21', 'consulta_notas.php'),
(1000, 2, '::1', '2026-01-26 14:59:00', 'index.php'),
(1001, 2, '::1', '2026-01-26 14:59:12', 'consulta_notas.php'),
(1002, 2, '::1', '2026-01-26 14:59:14', 'consulta_notas.php'),
(1003, 2, '::1', '2026-01-26 15:00:33', 'consulta_notas.php'),
(1004, 2, '::1', '2026-01-26 15:02:45', 'inscripcion_materias.php'),
(1005, 2, '::1', '2026-01-26 15:02:48', 'inscripcion_materias.php'),
(1006, 2, '::1', '2026-01-26 15:04:31', 'inscripcion_materias.php'),
(1007, 2, '::1', '2026-01-26 15:04:51', 'inscripcion_materias.php'),
(1008, 2, '::1', '2026-01-26 15:07:33', 'gestion_seccion.php'),
(1009, 2, '::1', '2026-01-26 15:07:38', 'gestion_seccion.php'),
(1010, 2, '::1', '2026-01-26 15:07:42', 'gestion_seccion.php'),
(1011, 2, '::1', '2026-01-26 15:07:47', 'gestion_seccion.php'),
(1012, 2, '::1', '2026-01-26 15:07:47', 'gestion_seccion.php'),
(1013, 2, '::1', '2026-01-26 15:08:23', 'asignacion_cursos.php'),
(1014, 2, '::1', '2026-01-26 15:08:50', 'asignacion_cursos.php'),
(1015, 2, '::1', '2026-01-26 15:09:11', 'asignacion_cursos.php'),
(1016, 2, '::1', '2026-01-26 15:09:24', 'asignar_secciones.php'),
(1017, 2, '::1', '2026-01-26 15:09:30', 'asignar_secciones.php'),
(1018, 2, '::1', '2026-01-26 15:09:43', 'asignar_secciones.php'),
(1019, 2, '::1', '2026-01-26 16:02:45', 'index.php'),
(1020, 2, '::1', '2026-01-26 16:02:51', 'gestion_seccion.php'),
(1021, 2, '::1', '2026-01-26 16:02:57', 'asignar_secciones.php'),
(1022, 2, '::1', '2026-01-26 16:03:03', 'asignar_secciones.php'),
(1023, 2, '::1', '2026-01-26 16:03:05', 'asignar_secciones.php'),
(1024, 2, '::1', '2026-01-26 16:03:24', 'asignar_secciones.php'),
(1025, 2, '::1', '2026-01-26 16:03:26', 'asignar_secciones.php'),
(1026, 2, '::1', '2026-01-26 16:03:30', 'asignar_secciones.php'),
(1027, 2, '::1', '2026-01-26 16:03:41', 'asignar_secciones.php'),
(1028, 2, '::1', '2026-01-26 16:03:44', 'asignar_secciones.php'),
(1029, 2, '::1', '2026-01-26 16:04:04', 'index.php'),
(1030, 2, '::1', '2026-01-26 16:04:10', 'asignar_secciones.php'),
(1031, 2, '::1', '2026-01-26 16:04:22', 'asignar_secciones.php'),
(1032, 2, '::1', '2026-01-26 16:04:24', 'asignar_secciones.php'),
(1033, 2, '::1', '2026-01-26 16:05:45', 'asignar_secciones.php'),
(1034, 2, '::1', '2026-01-26 16:05:47', 'asignar_secciones.php'),
(1035, 2, '::1', '2026-01-26 16:28:45', 'asignar_secciones.php'),
(1036, 2, '::1', '2026-01-26 16:29:35', 'index.php'),
(1037, 2, '::1', '2026-01-26 16:29:37', 'asignar_secciones.php'),
(1038, 2, '::1', '2026-01-26 16:29:48', 'asignar_secciones.php'),
(1039, 2, '::1', '2026-01-26 16:29:51', 'asignar_secciones.php'),
(1040, 2, '::1', '2026-01-26 16:34:06', 'index.php'),
(1041, 2, '::1', '2026-01-26 16:34:08', 'asignar_secciones.php'),
(1042, 2, '::1', '2026-01-26 16:34:18', 'asignar_secciones.php'),
(1043, 2, '::1', '2026-01-26 16:34:24', 'asignar_secciones.php'),
(1044, 4, '::1', '2026-01-26 16:36:39', 'index.php'),
(1045, 4, '::1', '2026-01-26 16:36:42', 'notas.php'),
(1046, 2, '::1', '2026-01-26 16:37:22', 'index.php'),
(1047, 2, '::1', '2026-01-26 16:37:29', 'correccion_notas.php'),
(1048, 2, '::1', '2026-01-26 16:37:33', 'correccion_notas.php'),
(1049, 2, '::1', '2026-01-26 16:37:38', 'correccion_notas.php'),
(1050, 4, '::1', '2026-01-26 16:38:30', 'index.php'),
(1051, 4, '::1', '2026-01-26 16:38:31', 'notas.php'),
(1052, 4, '::1', '2026-01-26 16:39:37', 'notas.php'),
(1053, 4, '::1', '2026-01-26 16:40:35', 'notas.php'),
(1054, 4, '::1', '2026-01-26 16:43:21', 'index.php'),
(1055, 4, '::1', '2026-01-26 16:43:22', 'notas.php'),
(1056, 4, '::1', '2026-01-26 17:04:17', 'notas.php'),
(1057, 4, '::1', '2026-01-26 17:04:28', 'notas.php'),
(1058, 4, '::1', '2026-01-26 17:05:02', 'notas.php'),
(1059, 4, '::1', '2026-01-26 17:05:04', 'notas.php'),
(1060, 4, '::1', '2026-01-26 17:06:25', 'notas.php'),
(1061, 2, '::1', '2026-01-26 17:13:07', 'index.php'),
(1062, 2, '::1', '2026-01-26 17:13:12', 'admin_notas_pendientes.php'),
(1063, 2, '::1', '2026-01-26 17:13:18', 'consulta_notas.php'),
(1064, 2, '::1', '2026-01-26 17:13:20', 'consulta_notas.php'),
(1065, 2, '::1', '2026-01-26 17:13:56', 'notas_pasadas.php'),
(1066, 2, '::1', '2026-01-26 17:14:04', 'notas_pasadas.php'),
(1067, 4, '::1', '2026-01-26 17:16:59', 'index.php'),
(1068, 4, '::1', '2026-01-26 17:17:00', 'notas.php'),
(1069, 4, '::1', '2026-01-26 17:27:26', 'notas.php'),
(1070, 4, '::1', '2026-01-26 17:27:37', 'notas.php'),
(1071, 4, '::1', '2026-01-26 17:45:27', 'notas.php'),
(1072, 4, '::1', '2026-01-26 17:45:29', 'notas.php'),
(1073, 4, '::1', '2026-01-26 17:48:45', 'notas.php'),
(1074, 4, '::1', '2026-01-26 17:54:26', 'notas.php'),
(1075, 4, '::1', '2026-01-26 17:57:19', 'notas.php'),
(1076, 4, '::1', '2026-01-26 17:57:20', 'notas.php'),
(1077, 4, '::1', '2026-01-26 18:07:52', 'notas.php'),
(1078, 4, '::1', '2026-01-26 18:12:16', 'notas.php'),
(1079, 4, '::1', '2026-01-26 18:12:19', 'notas.php'),
(1080, 4, '::1', '2026-01-26 18:12:37', 'notas.php'),
(1081, 4, '::1', '2026-01-26 18:12:54', 'notas.php'),
(1082, 4, '::1', '2026-01-26 18:24:17', 'notas.php'),
(1083, 2, '::1', '2026-01-26 18:24:38', 'index.php'),
(1084, 2, '::1', '2026-01-26 18:24:42', 'consulta_notas.php'),
(1085, 2, '::1', '2026-01-26 18:24:44', 'consulta_notas.php'),
(1086, 2, '::1', '2026-01-26 18:33:55', 'consulta_notas.php'),
(1087, 2, '::1', '2026-01-26 18:42:53', 'consulta_notas.php'),
(1088, 2, '::1', '2026-01-26 18:44:04', 'consulta_notas.php'),
(1089, 2, '::1', '2026-01-28 13:35:21', 'index.php'),
(1090, 2, '::1', '2026-01-28 13:35:27', 'consulta_notas.php'),
(1091, 2, '::1', '2026-01-28 13:35:30', 'consulta_notas.php'),
(1092, 2, '::1', '2026-01-28 13:35:49', 'grado.php'),
(1093, 2, '::1', '2026-01-28 13:35:52', 'grado.php'),
(1094, 2, '::1', '2026-01-28 13:36:07', 'grado.php'),
(1095, 2, '::1', '2026-01-28 13:57:30', 'constancias.php'),
(1096, 2, '::1', '2026-01-28 13:57:33', 'constancias.php'),
(1097, 5, '::1', '2026-01-28 13:57:55', 'index.php'),
(1098, 5, '::1', '2026-01-28 13:58:06', 'mis_secciones.php'),
(1099, 2, '::1', '2026-01-28 13:59:08', 'index.php'),
(1100, 2, '::1', '2026-01-28 13:59:10', 'grado.php'),
(1101, 2, '::1', '2026-01-28 13:59:12', 'constancias.php'),
(1102, 2, '::1', '2026-01-28 13:59:20', 'constancias.php'),
(1103, 2, '::1', '2026-01-28 13:59:30', 'constancias.php'),
(1104, 2, '::1', '2026-01-28 13:59:40', 'constancias.php'),
(1105, 2, '::1', '2026-01-28 13:59:50', 'constancias.php'),
(1106, 2, '::1', '2026-01-28 14:01:31', 'grado.php'),
(1107, 2, '::1', '2026-01-28 14:01:41', 'index.php'),
(1108, 2, '::1', '2026-01-28 14:01:42', 'notas.php'),
(1109, 2, '::1', '2026-01-28 14:19:25', 'notas.php'),
(1110, 2, '::1', '2026-01-28 14:22:47', 'index.php'),
(1111, 2, '::1', '2026-01-28 14:22:48', 'notas.php'),
(1112, 2, '::1', '2026-01-28 14:23:52', 'notas.php'),
(1113, 2, '::1', '2026-01-28 14:25:03', 'index.php'),
(1114, 2, '::1', '2026-01-28 14:25:10', 'index.php'),
(1115, 2, '::1', '2026-01-28 14:25:15', 'admin_notas_pendientes.php'),
(1116, 2, '::1', '2026-01-28 14:25:20', 'admin_notas_pendientes.php'),
(1117, 2, '::1', '2026-01-28 14:25:29', 'grado.php'),
(1118, 2, '::1', '2026-01-28 14:25:40', 'grado.php'),
(1119, 2, '::1', '2026-01-28 14:50:49', 'consulta_notas.php'),
(1120, 2, '::1', '2026-01-28 14:50:51', 'consulta_notas.php'),
(1121, 2, '::1', '2026-01-28 15:02:59', 'admin_notas_pendientes.php'),
(1122, 2, '::1', '2026-01-28 15:03:02', 'notas_pasadas.php'),
(1123, 2, '::1', '2026-01-28 15:03:31', 'notas_pasadas.php'),
(1124, 2, '::1', '2026-01-28 15:03:38', 'materia.php'),
(1125, 2, '::1', '2026-01-28 15:03:48', 'agregar_carrera.php'),
(1126, 2, '::1', '2026-01-28 15:05:54', 'grado.php'),
(1127, 2, '::1', '2026-01-28 15:05:57', 'grado.php'),
(1128, 2, '::1', '2026-01-28 15:40:41', 'grado.php'),
(1129, 2, '::1', '2026-01-28 16:42:57', 'grado.php'),
(1130, 2, '::1', '2026-01-28 16:43:37', 'grado.php'),
(1131, 2, '::1', '2026-01-28 16:43:40', 'grado.php'),
(1132, 2, '::1', '2026-01-28 16:43:45', 'grado.php'),
(1133, 2, '::1', '2026-01-28 16:43:56', 'grado.php'),
(1134, 2, '::1', '2026-01-28 16:44:23', 'gestion_seccion.php'),
(1135, 2, '::1', '2026-01-28 16:44:31', 'agregar_carrera.php'),
(1136, 2, '::1', '2026-01-28 16:50:21', 'agregar_carrera.php'),
(1137, 2, '::1', '2026-01-28 16:52:38', 'materia.php'),
(1138, 2, '::1', '2026-01-28 16:52:43', 'materia.php'),
(1139, 2, '::1', '2026-01-28 16:52:47', 'index.php'),
(1140, 2, '::1', '2026-01-28 16:52:49', 'materia.php'),
(1141, 2, '::1', '2026-01-28 16:55:39', 'materia.php'),
(1142, 2, '::1', '2026-01-28 16:58:51', 'index.php'),
(1143, 2, '::1', '2026-01-28 16:59:39', 'grado.php'),
(1144, 2, '::1', '2026-01-28 16:59:42', 'grado.php'),
(1145, 2, '::1', '2026-01-28 17:03:03', 'grado.php'),
(1146, 2, '::1', '2026-01-28 17:18:13', 'grado.php'),
(1147, 2, '::1', '2026-01-28 17:18:34', 'grado.php'),
(1148, 2, '::1', '2026-01-28 17:20:03', 'index.php'),
(1149, 2, '::1', '2026-01-28 17:20:10', 'editar_accesos.php'),
(1150, 2, '::1', '2026-01-28 17:20:21', 'editar_accesos.php'),
(1151, 2, '::1', '2026-01-28 17:20:21', 'editar_accesos.php'),
(1152, 2, '::1', '2026-01-28 17:20:26', 'materia.php'),
(1153, 2, '::1', '2026-01-28 17:20:31', 'editar_accesos.php'),
(1154, 2, '::1', '2026-01-28 17:20:33', 'index.php'),
(1155, 2, '::1', '2026-01-28 17:20:40', 'gestion_seccion.php'),
(1156, 2, '::1', '2026-01-28 17:20:42', 'grado.php'),
(1157, 2, '::1', '2026-01-28 17:20:45', 'grado.php'),
(1158, 2, '::1', '2026-01-28 17:31:52', 'grado.php'),
(1159, 2, '::1', '2026-01-28 17:33:48', 'grado.php'),
(1160, 2, '::1', '2026-01-28 17:33:56', 'grado.php'),
(1161, 2, '::1', '2026-01-28 17:34:06', 'grado.php'),
(1162, 2, '::1', '2026-01-28 17:39:42', 'grado.php'),
(1163, 2, '::1', '2026-01-28 17:45:18', 'grado.php'),
(1164, 2, '::1', '2026-01-28 17:45:39', 'grado.php'),
(1165, 2, '::1', '2026-01-28 17:47:01', 'grado.php'),
(1166, 2, '::1', '2026-01-28 17:49:46', 'grado.php'),
(1167, 2, '::1', '2026-01-28 17:56:47', 'grado.php'),
(1168, 2, '::1', '2026-01-28 18:21:41', 'grado.php'),
(1169, 2, '::1', '2026-01-28 18:22:03', 'grado.php'),
(1170, 2, '::1', '2026-01-28 18:22:04', 'index.php'),
(1171, 2, '::1', '2026-01-28 18:37:46', 'index.php'),
(1172, 2, '::1', '2026-01-28 18:40:50', 'index.php'),
(1173, 2, '::1', '2026-01-28 18:41:15', 'index.php'),
(1174, 2, '::1', '2026-01-28 18:41:42', 'index.php'),
(1175, 2, '::1', '2026-01-28 18:43:20', 'index.php'),
(1176, 2, '::1', '2026-01-28 18:43:21', 'index.php'),
(1177, 2, '::1', '2026-01-28 18:43:23', 'mensajeria.php'),
(1178, 2, '::1', '2026-01-28 18:43:35', 'registro_pagos.php'),
(1179, 2, '::1', '2026-01-28 18:44:08', 'index.php'),
(1180, 2, '::1', '2026-01-29 13:28:47', 'index.php'),
(1181, 2, '::1', '2026-01-29 13:29:17', 'materia.php'),
(1182, 2, '::1', '2026-01-29 13:29:27', 'constancias.php'),
(1183, 2, '::1', '2026-01-29 13:29:34', 'constancias.php'),
(1184, 2, '::1', '2026-01-29 13:29:56', 'constancias.php'),
(1185, 2, '::1', '2026-01-29 13:30:03', 'constancias.php'),
(1186, 2, '::1', '2026-01-29 14:20:32', 'constancias.php'),
(1187, 2, '::1', '2026-01-29 14:21:18', 'constancias.php'),
(1188, 2, '::1', '2026-01-29 14:49:35', 'agregar_carrera.php'),
(1189, 2, '::1', '2026-01-29 15:11:42', 'constancias.php'),
(1190, 2, '::1', '2026-01-29 15:11:44', 'constancias.php'),
(1191, 2, '::1', '2026-01-29 16:09:14', 'constancias.php'),
(1192, 2, '::1', '2026-01-29 16:27:38', 'constancias.php'),
(1193, 2, '::1', '2026-01-29 16:27:48', 'constancias.php'),
(1194, 2, '::1', '2026-01-29 16:29:38', 'constancias.php'),
(1195, 2, '::1', '2026-01-29 16:32:02', 'constancias.php'),
(1196, 2, '::1', '2026-01-29 16:50:04', 'constancias.php'),
(1197, 2, '::1', '2026-01-29 16:50:59', 'index.php'),
(1198, 2, '::1', '2026-01-29 16:51:03', 'constancias.php'),
(1199, 2, '::1', '2026-01-29 16:51:06', 'constancias.php'),
(1200, 2, '::1', '2026-01-29 16:51:22', 'constancias.php'),
(1201, 2, '::1', '2026-01-29 16:55:27', 'constancias.php'),
(1202, 2, '::1', '2026-01-29 17:17:40', 'constancias.php'),
(1203, 2, '::1', '2026-01-29 17:37:28', 'constancias.php'),
(1204, 2, '::1', '2026-01-29 17:37:29', 'index.php'),
(1205, 2, '::1', '2026-01-29 17:38:11', 'index.php'),
(1206, 2, '::1', '2026-01-29 17:39:14', 'index.php'),
(1207, 2, '::1', '2026-01-30 13:13:19', 'index.php'),
(1208, 2, '::1', '2026-01-30 13:14:38', 'agregar_carrera.php'),
(1209, 2, '::1', '2026-01-30 13:14:47', 'agregar_carrera.php'),
(1210, 2, '::1', '2026-01-30 13:15:28', 'materia.php'),
(1211, 2, '::1', '2026-01-30 13:17:19', 'materia.php'),
(1212, 2, '::1', '2026-01-30 13:17:19', 'materia.php'),
(1213, 2, '::1', '2026-01-30 13:17:25', 'carrera_materias.php'),
(1214, 2, '::1', '2026-01-30 13:18:13', 'carrera_materias.php'),
(1215, 2, '::1', '2026-01-30 13:18:18', 'agregar_carrera.php'),
(1216, 2, '::1', '2026-01-30 13:19:30', 'carrera_materias.php'),
(1217, 2, '::1', '2026-01-30 13:19:36', 'materia.php'),
(1218, 2, '::1', '2026-01-30 13:20:20', 'materia.php'),
(1219, 2, '::1', '2026-01-30 13:20:21', 'materia.php'),
(1220, 2, '::1', '2026-01-30 13:20:23', 'carrera_materias.php'),
(1221, 2, '::1', '2026-01-30 13:20:37', 'carrera_materias.php'),
(1222, 2, '::1', '2026-01-30 13:20:45', 'agregar_carrera.php'),
(1223, 2, '::1', '2026-01-30 13:21:34', 'agregar_carrera.php'),
(1224, 2, '::1', '2026-01-30 13:21:51', 'agregar_carrera.php'),
(1225, 2, '::1', '2026-01-30 13:54:52', 'add_docente.php'),
(1226, 2, '::1', '2026-01-30 13:55:01', 'asignacion_cursos.php'),
(1227, 2, '::1', '2026-01-30 13:55:13', 'asignacion_cursos.php'),
(1228, 2, '::1', '2026-01-30 13:55:36', 'index.php'),
(1229, 2, '::1', '2026-01-30 13:56:02', 'agregar_carrera.php'),
(1230, 2, '::1', '2026-01-30 13:56:11', 'agregar_carrera.php'),
(1231, 2, '::1', '2026-01-30 13:56:23', 'agregar_carrera.php'),
(1232, 2, '::1', '2026-01-30 13:56:27', 'agregar_carrera.php'),
(1233, 2, '::1', '2026-01-30 14:09:48', 'materia.php'),
(1234, 2, '::1', '2026-01-30 14:16:24', 'materia.php'),
(1235, 2, '::1', '2026-01-30 14:16:28', 'materia.php'),
(1236, 2, '::1', '2026-01-30 14:16:29', 'materia.php'),
(1237, 2, '::1', '2026-01-30 14:17:14', 'materia.php'),
(1238, 2, '::1', '2026-01-30 14:17:14', 'materia.php'),
(1239, 2, '::1', '2026-01-30 14:17:46', 'carrera_materias.php'),
(1240, 2, '::1', '2026-01-30 14:18:25', 'carrera_materias.php'),
(1241, 2, '::1', '2026-01-30 14:18:35', 'agregar_carrera.php'),
(1242, 2, '::1', '2026-01-30 14:18:45', 'agregar_carrera.php'),
(1243, 2, '::1', '2026-01-30 14:18:50', 'agregar_carrera.php'),
(1244, 2, '::1', '2026-01-30 14:19:18', 'materia.php'),
(1245, 2, '::1', '2026-01-30 14:19:21', 'carrera_materias.php'),
(1246, 2, '::1', '2026-01-30 14:19:44', 'carrera_materias.php'),
(1247, 2, '::1', '2026-01-30 14:19:56', 'agregar_carrera.php'),
(1248, 2, '::1', '2026-01-30 14:23:17', 'materia.php'),
(1249, 2, '::1', '2026-01-30 14:24:34', 'materia.php'),
(1250, 2, '::1', '2026-01-30 14:24:39', 'materia.php'),
(1251, 2, '::1', '2026-01-30 14:24:54', 'materia.php'),
(1252, 2, '::1', '2026-01-30 14:24:55', 'materia.php'),
(1253, 2, '::1', '2026-01-30 14:24:58', 'agregar_carrera.php'),
(1254, 2, '::1', '2026-01-30 14:49:30', 'materia.php'),
(1255, 2, '::1', '2026-01-30 14:50:18', 'materia.php'),
(1256, 2, '::1', '2026-01-30 14:50:18', 'materia.php'),
(1257, 2, '::1', '2026-01-30 14:50:21', 'carrera_materias.php'),
(1258, 2, '::1', '2026-01-30 14:50:35', 'carrera_materias.php'),
(1259, 2, '::1', '2026-01-30 14:50:44', 'agregar_carrera.php'),
(1260, 2, '::1', '2026-01-30 15:07:18', 'materia.php'),
(1261, 2, '::1', '2026-01-30 15:07:30', 'materia.php'),
(1262, 2, '::1', '2026-01-30 15:07:30', 'materia.php'),
(1263, 2, '::1', '2026-01-30 15:07:33', 'agregar_carrera.php'),
(1264, 2, '::1', '2026-02-02 13:44:23', 'index.php'),
(1265, 2, '::1', '2026-02-02 13:44:26', 'index.php'),
(1266, 2, '::1', '2026-02-02 13:51:36', 'index.php'),
(1267, 2, '::1', '2026-02-02 13:51:53', 'index.php'),
(1268, 2, '::1', '2026-02-02 14:01:38', 'index.php'),
(1269, 2, '::1', '2026-02-02 14:07:09', 'index.php'),
(1270, 2, '::1', '2026-02-02 14:07:40', 'index.php'),
(1271, 2, '::1', '2026-02-02 14:07:42', 'index.php'),
(1272, 2, '::1', '2026-02-02 14:07:44', 'index.php'),
(1273, 2, '::1', '2026-02-02 14:07:46', 'index.php'),
(1274, 2, '::1', '2026-02-02 14:08:30', 'index.php'),
(1275, 2, '::1', '2026-02-02 14:08:46', 'index.php'),
(1276, 2, '::1', '2026-02-02 14:09:06', 'index.php'),
(1277, 2, '::1', '2026-02-02 14:14:25', 'index.php'),
(1278, 2, '::1', '2026-02-02 14:16:24', 'index.php'),
(1279, 2, '::1', '2026-02-02 14:17:12', 'index.php'),
(1280, 2, '::1', '2026-02-02 14:23:49', 'index.php'),
(1281, 2, '::1', '2026-02-02 14:23:54', 'respaldo_bd.php'),
(1282, 2, '::1', '2026-02-02 14:24:06', 'respaldo_bd.php'),
(1283, 2, '::1', '2026-02-02 14:24:08', 'respaldo_bd.php'),
(1284, 2, '::1', '2026-02-02 14:24:11', 'respaldo_bd.php'),
(1285, 2, '::1', '2026-02-02 14:24:14', 'respaldo_bd.php'),
(1286, 2, '::1', '2026-02-02 14:24:35', 'respaldo_bd.php'),
(1287, 2, '::1', '2026-02-02 14:25:28', 'index.php'),
(1288, 2, '::1', '2026-02-02 14:25:29', 'index.php'),
(1289, 2, '::1', '2026-02-02 14:26:08', 'index.php'),
(1290, 2, '::1', '2026-02-02 14:28:02', 'index.php'),
(1291, 2, '::1', '2026-02-05 14:57:43', 'index.php'),
(1292, 2, '::1', '2026-02-05 14:57:57', 'estudiantes.php'),
(1293, 2, '::1', '2026-02-05 14:57:58', 'estudiantes.php'),
(1294, 2, '::1', '2026-02-05 14:58:00', 'agregar_carrera.php'),
(1295, 2, '::1', '2026-02-05 14:58:08', 'estudiantes.php'),
(1296, 2, '::1', '2026-02-05 14:58:08', 'estudiantes.php'),
(1297, 2, '::1', '2026-02-05 14:58:18', 'index.php'),
(1298, 5, '::1', '2026-02-10 18:48:45', 'index.php'),
(1299, 2615, '::1', '2026-02-10 18:52:22', 'index.php'),
(1300, 2, '::1', '2026-02-10 18:52:51', 'index.php'),
(1301, 2, '::1', '2026-02-10 18:53:15', 'agregar_estudiante.php'),
(1302, 2, '::1', '2026-02-10 18:53:15', 'agregar_estudiante.php'),
(1303, 2, '::1', '2026-02-10 18:55:17', 'agregar_estudiante.php'),
(1304, 2, '::1', '2026-02-10 18:55:17', 'agregar_estudiante.php'),
(1305, 2, '::1', '2026-02-10 18:55:33', 'index.php'),
(1306, 2616, '::1', '2026-02-10 18:56:12', 'index.php'),
(1307, 2616, '::1', '2026-02-10 19:00:40', 'mi_historial.php'),
(1308, 2616, '::1', '2026-02-10 19:00:59', 'index.php'),
(1309, 2, '::1', '2026-02-12 13:50:46', 'index.php'),
(1310, 2, '::1', '2026-02-12 13:50:59', 'mi_pensum.php'),
(1311, 2, '::1', '2026-02-12 13:51:03', 'mi_pensum.php'),
(1312, 2, '::1', '2026-02-12 13:51:36', 'mi_pensum.php'),
(1313, 2, '::1', '2026-02-12 13:51:46', 'index.php'),
(1314, 2, '::1', '2026-02-12 13:51:55', 'mi_horario.php'),
(1315, 2, '::1', '2026-02-12 13:51:56', 'index.php'),
(1316, 4, '::1', '2026-02-12 13:52:17', 'index.php'),
(1317, 5, '::1', '2026-02-12 13:52:28', 'index.php'),
(1318, 5, '::1', '2026-02-12 13:52:31', 'mi_horario.php'),
(1319, 5, '::1', '2026-02-12 13:53:06', 'index.php'),
(1320, 5, '::1', '2026-02-12 13:53:09', 'mis_secciones.php'),
(1321, 5, '::1', '2026-02-12 13:53:12', 'index.php'),
(1322, 5, '::1', '2026-02-12 13:58:16', 'mi_historial.php'),
(1323, 2, '::1', '2026-02-12 13:58:38', 'index.php'),
(1324, 2, '::1', '2026-02-12 13:58:59', 'consulta_notas.php'),
(1325, 2, '::1', '2026-02-12 13:59:01', 'consulta_notas.php'),
(1326, 2, '::1', '2026-02-12 14:29:22', 'mi_historial.php'),
(1327, 2, '::1', '2026-02-12 14:29:28', 'index.php'),
(1328, 2, '::1', '2026-02-12 14:29:32', 'mi_historial.php'),
(1329, 2, '::1', '2026-02-12 14:29:44', 'mi_historial.php'),
(1330, 5, '::1', '2026-02-12 14:29:51', 'index.php'),
(1331, 5, '::1', '2026-02-12 14:29:54', 'mi_pensum.php'),
(1332, 5, '::1', '2026-02-12 14:29:56', 'index.php'),
(1333, 5, '::1', '2026-02-12 14:29:59', 'mi_historial.php'),
(1334, 5, '::1', '2026-02-12 15:06:53', 'mi_historial.php'),
(1335, 5, '::1', '2026-02-12 15:08:28', 'mi_historial.php'),
(1336, 5, '::1', '2026-02-12 15:08:58', 'mi_historial.php'),
(1337, 5, '::1', '2026-02-12 15:26:28', 'mi_historial.php'),
(1338, 5, '::1', '2026-02-12 15:28:09', 'index.php'),
(1339, 5, '::1', '2026-02-12 15:28:11', 'mi_pensum.php'),
(1340, 5, '::1', '2026-02-12 15:28:15', 'mi_pensum.php'),
(1341, 5, '::1', '2026-02-12 15:28:25', 'mi_pensum.php'),
(1342, 5, '::1', '2026-02-12 15:28:29', 'index.php'),
(1343, 5, '::1', '2026-02-12 15:28:33', 'mis_secciones.php'),
(1344, 5, '::1', '2026-02-12 15:28:38', 'index.php'),
(1345, 5, '::1', '2026-02-12 15:28:51', 'mi_historial.php'),
(1346, 5, '::1', '2026-02-12 15:29:00', 'index.php'),
(1347, 2, '::1', '2026-02-12 15:29:27', 'index.php'),
(1348, 2, '::1', '2026-02-12 15:29:30', 'constancias.php'),
(1349, 2, '::1', '2026-02-12 15:29:32', 'constancias.php'),
(1350, 2, '::1', '2026-02-12 16:06:00', 'constancias.php'),
(1351, 2, '::1', '2026-02-12 16:06:03', 'index.php'),
(1352, 2, '::1', '2026-02-12 16:07:05', 'index.php'),
(1353, 2, '::1', '2026-02-12 16:07:11', 'index.php'),
(1354, 2, '::1', '2026-02-12 16:55:20', 'index.php'),
(1355, 2, '::1', '2026-02-12 16:55:22', 'mis_constancias.php'),
(1356, 2, '::1', '2026-02-12 16:55:41', 'index.php'),
(1357, 5, '::1', '2026-02-12 16:55:50', 'index.php'),
(1358, 5, '::1', '2026-02-12 16:55:55', 'mis_constancias.php'),
(1359, 2, '::1', '2026-02-12 16:57:23', 'index.php'),
(1360, 2, '::1', '2026-02-12 16:57:26', 'mis_constancias.php'),
(1361, 2, '::1', '2026-02-12 17:03:11', 'mis_constancias.php'),
(1362, 2, '::1', '2026-02-12 17:06:05', 'mis_constancias.php'),
(1363, 2, '::1', '2026-02-12 17:12:50', 'mis_constancias.php'),
(1364, 2, '::1', '2026-02-12 17:12:51', 'mis_constancias.php'),
(1365, 2, '::1', '2026-02-12 17:13:33', 'index.php'),
(1366, 2, '::1', '2026-02-12 17:16:23', 'index.php'),
(1367, 2, '::1', '2026-02-12 17:18:46', 'index.php'),
(1368, 2, '::1', '2026-02-12 17:18:47', 'mis_constancias.php'),
(1369, 2, '::1', '2026-02-12 17:22:43', 'index.php'),
(1370, 2, '::1', '2026-02-12 17:23:36', 'index.php'),
(1371, 2, '::1', '2026-02-23 13:18:25', 'index.php'),
(1372, 2, '::1', '2026-02-23 13:18:28', 'mi_pensum.php'),
(1373, 2, '::1', '2026-02-23 13:18:32', 'index.php'),
(1374, 2, '::1', '2026-02-23 13:18:43', 'mis_constancias.php'),
(1375, 2, '::1', '2026-02-23 13:18:46', 'index.php'),
(1376, 2, '::1', '2026-02-23 13:22:48', 'mi_horario.php'),
(1377, 2, '::1', '2026-02-23 13:22:52', 'index.php'),
(1378, 2, '::1', '2026-02-23 13:22:54', 'mi_historial.php'),
(1379, 2, '::1', '2026-02-23 13:23:03', 'index.php'),
(1380, 2, '::1', '2026-02-23 13:23:54', 'index.php'),
(1381, 2, '::1', '2026-02-23 13:24:16', 'index.php'),
(1382, 2, '::1', '2026-02-23 13:24:21', 'notas.php'),
(1383, 2, '::1', '2026-02-23 13:25:09', 'index.php'),
(1384, 2, '::1', '2026-02-23 13:25:14', 'asignacion_cursos.php'),
(1385, 2, '::1', '2026-02-23 13:25:35', 'asignacion_cursos.php'),
(1386, 2, '::1', '2026-02-23 13:25:52', 'asignacion_cursos.php'),
(1387, 2, '::1', '2026-02-23 13:26:33', 'gestion_seccion.php'),
(1388, 2, '::1', '2026-02-23 13:26:38', 'gestion_seccion.php'),
(1389, 2, '::1', '2026-02-23 13:27:41', 'gestion_seccion.php'),
(1390, 2, '::1', '2026-02-23 13:27:41', 'gestion_seccion.php'),
(1391, 2, '::1', '2026-02-23 13:27:45', 'gestion_seccion.php'),
(1392, 2, '::1', '2026-02-23 13:27:47', 'gestion_seccion.php'),
(1393, 2, '::1', '2026-02-23 13:27:51', 'gestion_seccion.php'),
(1394, 2, '::1', '2026-02-23 13:28:02', 'gestion_seccion.php'),
(1395, 2, '::1', '2026-02-23 13:28:02', 'gestion_seccion.php'),
(1396, 2, '::1', '2026-02-23 13:28:12', 'gestion_seccion.php'),
(1397, 2, '::1', '2026-02-23 13:28:23', 'gestion_seccion.php'),
(1398, 2, '::1', '2026-02-23 13:28:27', 'gestion_seccion.php'),
(1399, 2, '::1', '2026-02-23 13:28:32', 'gestion_seccion.php'),
(1400, 2, '::1', '2026-02-23 13:28:37', 'gestion_seccion.php'),
(1401, 2, '::1', '2026-02-23 13:29:14', 'gestion_seccion.php'),
(1402, 2, '::1', '2026-02-23 13:29:26', 'gestion_seccion.php'),
(1403, 2, '::1', '2026-02-23 13:29:30', 'asignar_secciones.php'),
(1404, 2, '::1', '2026-02-23 13:30:01', 'asignar_secciones.php'),
(1405, 2, '::1', '2026-02-23 13:31:59', 'gestion_seccion.php'),
(1406, 2, '::1', '2026-02-23 13:32:04', 'agregar_estudiante.php'),
(1407, 2, '::1', '2026-02-23 13:32:04', 'agregar_estudiante.php'),
(1408, 2, '::1', '2026-02-23 13:35:36', 'agregar_estudiante.php'),
(1409, 2, '::1', '2026-02-23 13:35:37', 'agregar_estudiante.php'),
(1410, 2, '::1', '2026-02-23 13:35:56', 'estudiantes.php'),
(1411, 2, '::1', '2026-02-23 13:35:57', 'estudiantes.php'),
(1412, 2, '::1', '2026-02-23 13:39:39', 'agregar_estudiante.php'),
(1413, 2, '::1', '2026-02-23 13:39:39', 'agregar_estudiante.php'),
(1414, 2, '::1', '2026-02-23 13:41:38', 'agregar_estudiante.php'),
(1415, 2, '::1', '2026-02-23 13:41:38', 'agregar_estudiante.php'),
(1416, 2, '::1', '2026-02-23 13:41:45', 'estudiantes.php'),
(1417, 2, '::1', '2026-02-23 13:41:45', 'estudiantes.php'),
(1418, 2, '::1', '2026-02-23 13:42:02', 'gestion_seccion.php'),
(1419, 2, '::1', '2026-02-23 13:42:05', 'gestion_seccion.php'),
(1420, 2, '::1', '2026-02-23 13:42:12', 'gestion_seccion.php'),
(1421, 2, '::1', '2026-02-23 13:42:12', 'gestion_seccion.php'),
(1422, 2, '::1', '2026-02-23 13:43:17', 'agregar_estudiante.php'),
(1423, 2, '::1', '2026-02-23 13:43:17', 'agregar_estudiante.php'),
(1424, 2, '::1', '2026-02-23 13:45:21', 'agregar_estudiante.php'),
(1425, 2, '::1', '2026-02-23 13:45:22', 'agregar_estudiante.php'),
(1426, 2, '::1', '2026-02-23 13:47:58', 'agregar_estudiante.php'),
(1427, 2, '::1', '2026-02-23 13:47:58', 'agregar_estudiante.php'),
(1428, 2, '::1', '2026-02-23 13:48:03', 'estudiantes.php'),
(1429, 2, '::1', '2026-02-23 13:48:03', 'estudiantes.php'),
(1430, 2, '::1', '2026-02-23 13:49:59', 'asignar_secciones.php'),
(1431, 2, '::1', '2026-02-23 13:50:05', 'gestion_seccion.php'),
(1432, 2, '::1', '2026-02-23 13:50:11', 'gestion_seccion.php'),
(1433, 2, '::1', '2026-02-23 13:50:18', 'gestion_seccion.php'),
(1434, 2, '::1', '2026-02-23 13:50:18', 'gestion_seccion.php'),
(1435, 2, '::1', '2026-02-23 13:50:21', 'asignar_secciones.php'),
(1436, 2, '::1', '2026-02-23 13:50:26', 'asignar_secciones.php'),
(1437, 2, '::1', '2026-02-23 13:50:33', 'asignar_secciones.php'),
(1438, 2, '::1', '2026-02-23 13:51:22', 'index.php'),
(1439, 2, '::1', '2026-02-23 13:51:30', 'index.php'),
(1440, 2, '::1', '2026-02-23 13:52:17', 'notas.php'),
(1441, 2, '::1', '2026-02-23 14:42:33', 'notas.php'),
(1442, 2, '::1', '2026-02-23 14:43:38', 'notas.php'),
(1443, 2, '::1', '2026-02-23 14:52:23', 'notas.php'),
(1444, 2, '::1', '2026-02-23 15:09:58', 'notas.php'),
(1445, 2, '::1', '2026-02-23 15:09:59', 'notas.php'),
(1446, 2, '::1', '2026-02-23 15:45:09', 'notas.php'),
(1447, 2, '::1', '2026-02-23 15:46:18', 'notas.php'),
(1448, 2, '::1', '2026-02-23 15:49:17', 'notas.php'),
(1449, 2, '::1', '2026-02-23 15:52:07', 'notas.php'),
(1450, 2, '::1', '2026-02-24 13:44:59', 'index.php'),
(1451, 2, '::1', '2026-02-24 13:46:00', 'index.php'),
(1452, 2, '::1', '2026-02-24 13:46:02', 'notas.php'),
(1453, 2, '::1', '2026-02-24 13:48:44', 'notas.php'),
(1454, 2, '::1', '2026-02-24 13:54:34', 'notas.php'),
(1455, 2, '::1', '2026-02-24 13:56:03', 'notas.php'),
(1456, 2, '::1', '2026-02-24 13:56:43', 'notas.php'),
(1457, 2, '::1', '2026-02-24 15:00:40', 'notas.php'),
(1458, 2, '::1', '2026-02-24 15:03:58', 'notas.php'),
(1459, 2, '::1', '2026-02-24 15:03:59', 'notas.php'),
(1460, 2, '::1', '2026-02-24 15:04:00', 'notas.php'),
(1461, 2, '::1', '2026-02-24 15:04:11', 'notas.php'),
(1462, 2, '::1', '2026-02-24 15:04:12', 'notas.php'),
(1463, 2, '::1', '2026-02-24 15:04:18', 'notas.php'),
(1464, 2, '::1', '2026-02-24 18:04:37', 'notas.php'),
(1465, 2, '::1', '2026-03-02 13:12:23', 'index.php'),
(1466, 2, '::1', '2026-03-02 13:12:58', 'index.php'),
(1467, 2, '::1', '2026-03-02 13:13:01', 'notas.php'),
(1468, 2, '::1', '2026-03-02 13:16:42', 'notas.php'),
(1469, 2, '::1', '2026-03-02 13:17:08', 'notas.php'),
(1470, 2, '::1', '2026-03-02 13:17:31', 'notas.php'),
(1471, 2, '::1', '2026-03-02 13:17:52', 'notas.php'),
(1472, 2, '::1', '2026-03-02 13:17:57', 'notas.php'),
(1473, 2, '::1', '2026-03-02 13:18:03', 'notas.php'),
(1474, 2, '::1', '2026-03-02 13:19:27', 'notas.php'),
(1475, 2, '::1', '2026-03-02 13:20:17', 'notas.php'),
(1476, 2, '::1', '2026-03-02 13:22:50', 'notas.php'),
(1477, 2, '::1', '2026-03-02 13:25:15', 'notas.php'),
(1478, 2, '::1', '2026-03-02 13:27:59', 'index.php'),
(1479, 2, '::1', '2026-03-02 13:28:00', 'notas.php'),
(1480, 2, '::1', '2026-03-02 13:38:21', 'index.php'),
(1481, 2, '::1', '2026-03-02 13:38:22', 'notas.php'),
(1482, 2, '::1', '2026-03-02 13:38:23', 'notas.php'),
(1483, 2, '::1', '2026-03-02 13:43:11', 'notas.php'),
(1484, 2, '::1', '2026-03-02 13:43:11', 'notas.php'),
(1485, 2, '::1', '2026-03-02 13:44:12', 'index.php'),
(1486, 2, '::1', '2026-03-02 13:44:20', 'asignacion_cursos.php'),
(1487, 2, '::1', '2026-03-02 13:44:27', 'index.php'),
(1488, 2, '::1', '2026-03-02 14:09:47', 'index.php'),
(1489, 2, '::1', '2026-03-02 14:15:24', 'index.php'),
(1490, 2, '::1', '2026-03-02 14:27:04', 'index.php'),
(1491, 2, '::1', '2026-03-02 14:27:05', 'asignacion_voceros.php'),
(1492, 2, '::1', '2026-03-02 14:27:16', 'asignacion_voceros.php'),
(1493, 2, '::1', '2026-03-02 14:32:46', 'asignacion_voceros.php'),
(1494, 2, '::1', '2026-03-02 14:33:00', 'asignacion_voceros.php'),
(1495, 2, '::1', '2026-03-02 14:33:02', 'asignacion_voceros.php'),
(1496, 2, '::1', '2026-03-02 14:34:12', 'asignacion_voceros.php'),
(1497, 2, '::1', '2026-03-02 14:34:21', 'asignacion_voceros.php'),
(1498, 2, '::1', '2026-03-02 14:34:26', 'asignacion_voceros.php'),
(1499, 2, '::1', '2026-03-02 14:34:26', 'asignacion_voceros.php'),
(1500, 2, '::1', '2026-03-02 14:35:06', 'index.php'),
(1501, 2, '::1', '2026-03-02 14:35:09', 'mis_secciones.php'),
(1502, 2, '::1', '2026-03-02 14:35:11', 'index.php'),
(1503, 2, '::1', '2026-03-02 14:35:22', 'index.php'),
(1504, 2, '::1', '2026-03-02 14:35:26', 'asignacion_cursos.php'),
(1505, 2, '::1', '2026-03-02 14:35:29', 'index.php'),
(1506, 2, '::1', '2026-03-02 14:38:07', 'index.php'),
(1507, 2, '::1', '2026-03-02 14:38:09', 'asignacion_voceros.php'),
(1508, 2, '::1', '2026-03-02 14:38:12', 'index.php'),
(1509, 2, '::1', '2026-03-02 14:41:31', 'index.php'),
(1510, 2, '::1', '2026-03-02 14:41:32', 'asignacion_voceros.php'),
(1511, 2, '::1', '2026-03-02 14:41:36', 'asignacion_voceros.php'),
(1512, 2, '::1', '2026-03-02 14:41:37', 'asignacion_voceros.php'),
(1513, 2, '::1', '2026-03-02 14:41:39', 'asignacion_voceros.php'),
(1514, 2, '::1', '2026-03-02 14:41:43', 'asignacion_voceros.php'),
(1515, 2, '::1', '2026-03-02 14:41:44', 'asignacion_voceros.php'),
(1516, 2, '::1', '2026-03-02 14:41:48', 'asignacion_voceros.php'),
(1517, 2, '::1', '2026-03-02 14:41:50', 'asignacion_voceros.php'),
(1518, 2, '::1', '2026-03-02 14:41:56', 'asignacion_voceros.php'),
(1519, 2, '::1', '2026-03-02 14:41:56', 'asignacion_voceros.php'),
(1520, 5, '::1', '2026-03-02 14:42:14', 'index.php'),
(1521, 5, '::1', '2026-03-02 14:44:02', 'mis_secciones.php'),
(1522, 5, '::1', '2026-03-02 14:44:05', 'index.php'),
(1523, 5, '::1', '2026-03-02 14:51:46', 'index.php'),
(1524, 5, '::1', '2026-03-02 14:52:16', 'mis_secciones.php'),
(1525, 5, '::1', '2026-03-02 14:52:20', 'index.php'),
(1526, 5, '::1', '2026-03-02 14:52:25', 'mi_horario.php'),
(1527, 5, '::1', '2026-03-02 14:52:31', 'index.php'),
(1528, 5, '::1', '2026-03-02 14:52:33', 'mi_pensum.php'),
(1529, 5, '::1', '2026-03-02 14:52:37', 'index.php'),
(1530, 5, '::1', '2026-03-02 14:52:40', 'mi_historial.php'),
(1531, 5, '::1', '2026-03-02 14:53:20', 'index.php'),
(1532, 5, '::1', '2026-03-02 14:54:20', 'index.php'),
(1533, 5, '::1', '2026-03-03 13:30:25', 'index.php'),
(1534, 5, '::1', '2026-03-03 13:32:02', 'mi_horario.php'),
(1535, 5, '::1', '2026-03-03 13:32:17', 'index.php'),
(1536, 5, '::1', '2026-03-03 13:32:25', 'mis_constancias.php'),
(1537, 5, '::1', '2026-03-03 13:32:40', 'index.php'),
(1538, 5, '::1', '2026-03-03 13:55:13', 'index.php'),
(1539, 5, '::1', '2026-03-04 13:37:20', 'index.php'),
(1540, 5, '::1', '2026-03-04 14:11:24', 'mis_constancias.php'),
(1541, 5, '::1', '2026-03-04 14:11:49', 'index.php'),
(1542, 2, '::1', '2026-03-12 13:20:34', 'index.php'),
(1543, 5, '::1', '2026-03-12 13:20:45', 'index.php'),
(1544, 5, '::1', '2026-03-12 13:20:56', 'index.php'),
(1545, 5, '::1', '2026-03-12 15:00:46', 'index.php'),
(1546, 5, '::1', '2026-03-12 15:00:51', 'vocero.php'),
(1547, 5, '::1', '2026-03-12 15:01:23', 'mensajeria_estudiantes.php'),
(1548, 5, '::1', '2026-03-12 15:01:28', 'index.php'),
(1549, 5, '::1', '2026-03-12 15:01:30', 'vocero.php'),
(1550, 5, '::1', '2026-03-25 15:32:45', 'index.php'),
(1551, 5, '::1', '2026-03-25 15:33:05', 'vocero.php'),
(1552, 5, '::1', '2026-03-25 15:34:47', 'mensajeria_estudiantes.php'),
(1553, 5, '::1', '2026-03-25 15:43:30', 'mensajeria_estudiantes.php'),
(1554, 5, '::1', '2026-03-25 15:50:51', 'mensajeria_estudiantes.php'),
(1555, 5, '::1', '2026-03-25 15:55:20', 'mensajeria_estudiantes.php'),
(1556, 5, '::1', '2026-03-25 15:55:23', 'mensajeria_estudiantes.php'),
(1557, 5, '::1', '2026-03-25 15:55:39', 'mensajeria_estudiantes.php'),
(1558, 5, '::1', '2026-03-25 15:55:47', 'mensajeria_estudiantes.php'),
(1559, 5, '::1', '2026-03-25 15:59:26', 'mensajeria_estudiantes.php'),
(1560, 5, '::1', '2026-03-25 15:59:46', 'mensajeria_estudiantes.php'),
(1561, 5, '::1', '2026-03-25 16:10:40', 'mensajeria_estudiantes.php'),
(1562, 5, '::1', '2026-03-25 16:10:51', 'mensajeria_estudiantes.php'),
(1563, 5, '::1', '2026-03-25 16:10:57', 'mensajeria_estudiantes.php'),
(1564, 5, '::1', '2026-03-25 16:11:33', 'mensajeria_estudiantes.php'),
(1565, 5, '::1', '2026-03-25 16:11:38', 'mensajeria_estudiantes.php'),
(1566, 5, '::1', '2026-03-25 16:11:49', 'index.php'),
(1567, 4, '::1', '2026-03-25 16:12:15', 'index.php'),
(1568, 4, '::1', '2026-03-25 16:12:19', 'mensajeria.php'),
(1569, 4, '::1', '2026-03-25 16:12:22', 'mensajeria.php'),
(1570, 4, '::1', '2026-03-25 16:12:37', 'index.php'),
(1571, 4, '::1', '2026-03-25 16:23:51', 'mensajeria.php'),
(1572, 4, '::1', '2026-03-25 16:24:02', 'mensajeria.php'),
(1573, 4, '::1', '2026-03-25 16:24:19', 'mensajeria.php'),
(1574, 2, '::1', '2026-05-03 19:24:43', 'index.php'),
(1575, 2, '::1', '2026-05-03 19:24:47', 'secretaria.php'),
(1576, 2, '::1', '2026-05-03 19:27:44', 'secretaria.php'),
(1577, 2, '::1', '2026-05-03 19:29:26', 'index.php'),
(1578, 2, '::1', '2026-05-03 19:29:32', 'preinscripciones.php'),
(1579, 2, '::1', '2026-05-03 19:31:28', 'index.php'),
(1580, 2, '::1', '2026-05-03 19:31:41', 'secretaria.php'),
(1581, 2, '::1', '2026-05-03 19:32:51', 'secretaria.php'),
(1582, 2, '::1', '2026-05-03 19:33:07', 'index.php'),
(1583, 2, '::1', '2026-05-03 19:33:22', 'gestion_seccion.php'),
(1584, 2, '::1', '2026-05-03 19:33:35', 'index.php'),
(1585, 2, '::1', '2026-05-03 19:33:38', 'aprobar_secciones.php'),
(1586, 2, '::1', '2026-05-03 19:33:43', 'aprobar_secciones.php'),
(1587, 2, '::1', '2026-05-03 19:33:47', 'aprobar_secciones.php'),
(1588, 2, '::1', '2026-05-03 19:33:50', 'aprobar_secciones.php'),
(1589, 2, '::1', '2026-05-03 19:33:53', 'aprobar_secciones.php'),
(1590, 2, '::1', '2026-05-03 19:33:58', 'gestion_seccion.php'),
(1591, 2, '::1', '2026-05-03 19:34:02', 'index.php'),
(1592, 2, '::1', '2026-05-03 19:34:07', 'aprobar_secciones.php'),
(1593, 2, '::1', '2026-05-03 19:47:39', 'aprobar_secciones.php'),
(1594, 2, '::1', '2026-05-03 19:47:52', 'index.php'),
(1595, 2, '::1', '2026-05-03 19:47:55', 'secretaria.php'),
(1596, 2, '::1', '2026-05-03 19:48:03', 'index.php'),
(1597, 2, '::1', '2026-05-03 19:48:24', 'index.php'),
(1598, 2, '::1', '2026-05-03 19:48:32', 'index.php'),
(1599, 2, '::1', '2026-05-03 19:48:36', 'secretaria.php'),
(1600, 2, '::1', '2026-05-03 19:48:44', 'index.php'),
(1601, 2, '::1', '2026-05-03 19:48:47', 'aprobar_secciones.php'),
(1602, 2, '::1', '2026-05-03 19:48:52', 'index.php'),
(1603, 2, '::1', '2026-05-03 19:49:11', 'registro_pagos.php'),
(1604, 2, '::1', '2026-05-03 19:49:25', 'gestion_seccion.php'),
(1605, 2, '::1', '2026-05-03 19:49:29', 'aprobar_secciones.php'),
(1606, 2, '::1', '2026-05-03 19:49:34', 'index.php'),
(1607, 2, '::1', '2026-05-03 19:50:24', 'gestion_seccion.php'),
(1608, 2, '::1', '2026-05-03 19:50:28', 'gestion_seccion.php'),
(1609, 2, '::1', '2026-05-03 19:50:32', 'gestion_seccion.php'),
(1610, 2, '::1', '2026-05-03 19:50:44', 'gestion_seccion.php'),
(1611, 2, '::1', '2026-05-03 19:50:47', 'gestion_seccion.php'),
(1612, 2, '::1', '2026-05-03 19:51:45', 'gestion_seccion.php'),
(1613, 2, '::1', '2026-05-03 19:51:50', 'index.php'),
(1614, 2, '::1', '2026-05-03 19:51:53', 'aprobar_secciones.php'),
(1615, 2, '::1', '2026-05-03 19:56:27', 'aprobar_secciones.php'),
(1616, 2, '::1', '2026-05-03 19:56:32', 'aprobar_secciones.php'),
(1617, 2, '::1', '2026-05-03 19:56:42', 'aprobar_secciones.php'),
(1618, 2, '::1', '2026-05-03 20:01:13', 'aprobar_secciones.php'),
(1619, 2, '::1', '2026-05-03 20:01:21', 'aprobar_secciones.php'),
(1620, 2, '::1', '2026-05-03 20:01:28', 'aprobar_secciones.php'),
(1621, 2, '::1', '2026-05-03 20:01:31', 'aprobar_secciones.php'),
(1622, 2, '::1', '2026-05-03 20:01:34', 'gestion_seccion.php'),
(1623, 2, '::1', '2026-05-03 20:01:46', 'index.php'),
(1624, 2, '::1', '2026-05-03 20:02:11', 'index.php'),
(1625, 2, '::1', '2026-05-03 20:02:16', 'gestion_seccion.php'),
(1626, 2, '::1', '2026-05-03 20:02:20', 'gestion_seccion.php'),
(1627, 2, '::1', '2026-05-03 20:02:26', 'index.php'),
(1628, 2, '::1', '2026-05-03 20:02:31', 'gestion_seccion.php'),
(1629, 2, '::1', '2026-05-03 20:02:37', 'aprobar_secciones.php'),
(1630, 2, '::1', '2026-05-03 20:02:42', 'index.php'),
(1631, 2, '::1', '2026-05-10 19:17:52', 'index.php'),
(1632, 2, '::1', '2026-05-10 19:18:07', 'preinscripciones.php'),
(1633, 2, '::1', '2026-05-10 19:19:30', 'index.php'),
(1634, 2, '::1', '2026-05-10 19:19:33', 'preinscripciones.php'),
(1635, 2, '::1', '2026-05-10 19:19:35', 'preinscripcion_detalle.php'),
(1636, 2, '::1', '2026-05-10 19:19:41', 'index.php'),
(1637, 2, '::1', '2026-05-10 19:19:47', 'gestion_seccion.php'),
(1638, 2, '::1', '2026-05-10 19:19:56', 'index.php'),
(1639, 2, '::1', '2026-05-10 19:20:46', 'index.php'),
(1640, 2, '::1', '2026-05-10 19:20:50', 'secretaria.php'),
(1641, 2, '::1', '2026-05-10 19:21:10', 'index.php'),
(1642, 2, '::1', '2026-05-10 19:21:12', 'aprobar_secciones.php'),
(1643, 2, '::1', '2026-05-10 19:22:11', 'aprobar_secciones.php'),
(1644, 2, '::1', '2026-05-10 19:22:18', 'aprobar_secciones.php'),
(1645, 2, '::1', '2026-05-10 19:22:23', 'preinscripciones.php'),
(1646, 2, '::1', '2026-05-10 19:22:25', 'preinscripcion_detalle.php'),
(1647, 2, '::1', '2026-05-10 19:22:39', 'agregar_carrera.php'),
(1648, 2, '::1', '2026-05-10 19:24:02', 'preinscripciones.php'),
(1649, 2, '::1', '2026-05-10 19:24:05', 'preinscripcion_detalle.php'),
(1650, 2, '::1', '2026-05-10 19:24:42', 'preinscripcion_detalle.php'),
(1651, 2, '::1', '2026-05-10 19:24:46', 'gestion_seccion.php'),
(1652, 2, '::1', '2026-05-10 19:54:31', 'gestion_seccion.php'),
(1653, 2, '::1', '2026-05-10 19:54:36', 'preinscripciones.php'),
(1654, 2, '::1', '2026-05-10 19:54:38', 'preinscripcion_detalle.php'),
(1655, 2, '::1', '2026-05-10 19:55:27', 'preinscripcion_detalle.php'),
(1656, 2, '::1', '2026-05-10 19:55:35', 'preinscripcion_detalle.php'),
(1657, 2, '::1', '2026-05-10 19:55:51', 'preinscripcion_detalle.php'),
(1658, 2, '::1', '2026-05-10 19:55:55', 'preinscripcion_detalle.php'),
(1659, 2, '::1', '2026-05-10 20:01:01', 'preinscripcion_detalle.php'),
(1660, 2, '::1', '2026-05-10 20:01:54', 'preinscripcion_detalle.php'),
(1661, 2, '::1', '2026-05-10 20:02:36', 'preinscripcion_detalle.php'),
(1662, 2, '::1', '2026-05-10 20:10:45', 'preinscripcion_detalle.php'),
(1663, 2, '::1', '2026-05-10 20:10:49', 'gestion_seccion.php'),
(1664, 2, '::1', '2026-05-10 20:11:35', 'gestion_seccion.php'),
(1665, 2, '::1', '2026-05-10 20:13:35', 'index.php'),
(1666, 2, '::1', '2026-05-10 20:13:58', 'index.php'),
(1667, 2, '::1', '2026-05-10 20:55:16', 'index.php'),
(1668, 2, '::1', '2026-05-10 20:55:19', 'aprobar_secciones.php'),
(1669, 2, '::1', '2026-05-10 21:15:17', 'aprobar_secciones.php'),
(1670, 2, '::1', '2026-05-10 21:15:23', 'index.php'),
(1671, 2, '::1', '2026-05-10 21:16:16', 'index.php'),
(1672, 2, '::1', '2026-05-10 21:16:18', 'secretaria.php'),
(1673, 2, '::1', '2026-05-10 21:16:28', 'index.php'),
(1674, 2, '::1', '2026-05-10 21:16:31', 'aprobar_secciones.php'),
(1675, 2, '::1', '2026-05-10 21:17:03', 'aprobar_secciones.php'),
(1676, 2, '::1', '2026-05-10 21:17:28', 'gestion_seccion.php'),
(1677, 2, '::1', '2026-05-10 21:40:13', 'gestion_seccion.php'),
(1678, 2, '::1', '2026-05-10 21:40:19', 'index.php'),
(1679, 2, '::1', '2026-05-10 21:40:50', 'index.php'),
(1680, 2, '::1', '2026-05-10 21:40:54', 'aprobar_secciones.php'),
(1681, 2, '::1', '2026-05-10 21:43:47', 'aprobar_secciones.php'),
(1682, 2, '::1', '2026-05-10 21:44:01', 'aprobar_secciones.php'),
(1683, 2, '::1', '2026-05-10 21:50:06', 'gestion_seccion.php'),
(1684, 2, '::1', '2026-05-10 21:53:52', 'index.php'),
(1685, 2, '::1', '2026-05-10 21:54:08', 'preinscripciones.php'),
(1686, 2, '::1', '2026-05-10 21:54:12', 'preinscripcion_detalle.php'),
(1687, 2, '::1', '2026-05-10 21:54:20', 'preinscripcion_detalle.php'),
(1688, 2, '::1', '2026-05-10 21:54:28', 'gestion_seccion.php'),
(1689, 2, '::1', '2026-05-10 21:54:34', 'preinscripciones.php'),
(1690, 2, '::1', '2026-05-10 21:54:36', 'preinscripcion_detalle.php'),
(1691, 2, '::1', '2026-05-10 21:54:40', 'preinscripcion_detalle.php'),
(1692, 2, '::1', '2026-05-10 21:54:46', 'preinscripciones.php'),
(1693, 2, '::1', '2026-05-10 21:54:48', 'gestion_seccion.php'),
(1694, 2, '::1', '2026-05-10 21:55:02', 'gestion_seccion.php'),
(1695, 2624, '::1', '2026-05-10 21:55:26', 'index.php'),
(1696, 2624, '::1', '2026-05-10 21:55:30', 'mis_secciones.php'),
(1697, 2624, '::1', '2026-05-10 21:55:32', 'index.php'),
(1698, 2624, '::1', '2026-05-10 21:55:34', 'mi_horario.php'),
(1699, 2624, '::1', '2026-05-10 22:00:15', 'index.php'),
(1700, 2624, '::1', '2026-05-10 22:00:28', 'index.php'),
(1701, 2, '::1', '2026-05-10 22:06:29', 'index.php'),
(1702, 2, '::1', '2026-05-10 22:40:02', 'inscripcion_materias.php'),
(1703, 2, '::1', '2026-05-10 22:40:11', 'inscripcion_materias.php'),
(1704, 2, '::1', '2026-05-10 22:41:41', 'preinscripciones.php'),
(1705, 2, '::1', '2026-05-10 22:43:37', 'index.php'),
(1706, 2, '::1', '2026-05-10 22:43:43', 'preinscripciones.php'),
(1707, 2, '::1', '2026-05-10 22:43:46', 'index.php'),
(1708, 2, '::1', '2026-05-10 22:43:53', 'gestion_seccion.php'),
(1709, 2, '::1', '2026-05-10 22:48:20', 'index.php'),
(1710, 2, '::1', '2026-05-10 22:48:25', 'secretaria.php'),
(1711, 2, '::1', '2026-05-10 22:48:40', 'secretaria.php'),
(1712, 2, '::1', '2026-05-10 22:50:24', 'index.php'),
(1713, 2, '::1', '2026-05-10 22:50:26', 'secretaria.php'),
(1714, 2, '::1', '2026-05-10 22:50:40', 'secretaria.php'),
(1715, 2, '::1', '2026-05-10 22:50:47', 'index.php'),
(1716, 2, '::1', '2026-05-10 22:50:49', 'secretaria.php'),
(1717, 2, '::1', '2026-05-10 23:23:10', 'secretaria.php');
INSERT INTO `visitas` (`id`, `id_usuario`, `ip`, `fecha_visita`, `web`) VALUES
(1718, 2, '::1', '2026-05-10 23:23:23', 'secretaria.php'),
(1719, 2, '::1', '2026-05-10 23:23:42', 'secretaria.php'),
(1720, 2, '::1', '2026-05-10 23:24:06', 'secretaria.php'),
(1721, 2, '::1', '2026-05-10 23:44:13', 'secretaria.php'),
(1722, 2, '::1', '2026-05-10 23:44:25', 'secretaria.php'),
(1723, 2, '::1', '2026-05-10 23:44:32', 'secretaria.php'),
(1724, 2, '::1', '2026-05-10 23:44:53', 'secretaria.php'),
(1725, 2, '::1', '2026-05-10 23:44:58', 'secretaria.php'),
(1726, 2, '::1', '2026-05-10 23:45:07', 'secretaria.php'),
(1727, 2, '::1', '2026-05-10 23:45:14', 'secretaria.php'),
(1728, 2, '::1', '2026-05-10 23:45:27', 'secretaria.php'),
(1729, 2, '::1', '2026-05-10 23:45:32', 'secretaria.php'),
(1730, 2, '::1', '2026-05-10 23:47:27', 'secretaria.php'),
(1731, 2, '::1', '2026-05-10 23:52:13', 'secretaria.php'),
(1732, 2, '::1', '2026-05-10 23:52:26', 'secretaria.php'),
(1733, 2, '::1', '2026-05-10 23:52:34', 'secretaria.php'),
(1734, 2, '::1', '2026-05-10 23:52:42', 'secretaria.php'),
(1735, 2, '::1', '2026-05-10 23:57:43', 'index.php'),
(1736, 2, '::1', '2026-05-10 23:57:52', 'secretaria.php'),
(1737, 2, '::1', '2026-05-10 23:58:29', 'secretaria.php'),
(1738, 2, '::1', '2026-05-10 23:58:33', 'secretaria.php'),
(1739, 2, '::1', '2026-05-11 00:04:41', 'secretaria.php'),
(1740, 2, '::1', '2026-05-11 00:04:52', 'secretaria.php'),
(1741, 2, '::1', '2026-05-11 00:09:27', 'secretaria.php'),
(1742, 2, '::1', '2026-05-11 00:09:37', 'secretaria.php'),
(1743, 2, '::1', '2026-05-11 00:09:46', 'secretaria.php'),
(1744, 2, '::1', '2026-05-11 00:10:02', 'secretaria.php'),
(1745, 2, '::1', '2026-05-11 00:10:09', 'secretaria.php'),
(1746, 2, '::1', '2026-05-11 00:10:16', 'secretaria.php'),
(1747, 2, '::1', '2026-05-11 00:10:22', 'secretaria.php'),
(1748, 2, '::1', '2026-05-11 00:10:36', 'secretaria.php'),
(1749, 2, '::1', '2026-05-11 00:10:42', 'secretaria.php'),
(1750, 2, '::1', '2026-05-11 00:10:46', 'secretaria.php'),
(1751, 2, '::1', '2026-05-11 00:13:29', 'index.php'),
(1752, 2, '::1', '2026-05-11 00:13:31', 'gestion_seccion.php'),
(1753, 2, '::1', '2026-05-11 00:13:37', 'index.php'),
(1754, 2, '::1', '2026-05-11 00:13:40', 'secretaria.php'),
(1755, 2, '::1', '2026-05-11 00:13:55', 'secretaria.php'),
(1756, 2, '::1', '2026-05-11 00:14:06', 'secretaria.php'),
(1757, 2, '::1', '2026-05-11 00:14:10', 'secretaria.php'),
(1758, 2, '::1', '2026-05-11 00:14:22', 'index.php'),
(1759, 2, '::1', '2026-05-11 00:14:25', 'secretaria.php'),
(1760, 2, '::1', '2026-05-11 13:26:00', 'index.php'),
(1761, 2, '::1', '2026-05-11 13:26:07', 'agregar_carrera.php'),
(1762, 2, '::1', '2026-05-11 13:26:21', 'agregar_estudiante.php'),
(1763, 2, '::1', '2026-05-11 13:26:21', 'agregar_estudiante.php'),
(1764, 2, '::1', '2026-05-11 13:26:55', 'index.php'),
(1765, 2, '::1', '2026-05-11 13:26:57', 'secretaria.php'),
(1766, 2, '::1', '2026-05-11 13:27:08', 'secretaria.php'),
(1767, 2, '::1', '2026-05-11 13:27:12', 'secretaria.php'),
(1768, 2, '::1', '2026-05-11 13:30:18', 'preinscripciones.php'),
(1769, 2, '::1', '2026-05-11 13:30:19', 'index.php'),
(1770, 2, '::1', '2026-05-11 13:32:55', 'index.php'),
(1771, 2, '::1', '2026-05-11 13:33:01', 'preinscripciones.php'),
(1772, 2, '::1', '2026-05-11 13:33:04', 'index.php'),
(1773, 2, '::1', '2026-05-11 13:33:06', 'secretaria.php'),
(1774, 2, '::1', '2026-05-11 13:33:41', 'preinscripciones.php'),
(1775, 2, '::1', '2026-05-11 13:34:43', 'preinscripciones.php'),
(1776, 2, '::1', '2026-05-11 13:34:44', 'preinscripcion_detalle.php'),
(1777, 2, '::1', '2026-05-11 13:34:46', 'preinscripciones.php'),
(1778, 2, '::1', '2026-05-11 14:24:55', 'preinscripciones.php'),
(1779, 2, '::1', '2026-05-11 14:24:59', 'preinscripcion_detalle.php'),
(1780, 2, '::1', '2026-05-11 15:03:24', 'preinscripcion_detalle.php'),
(1781, 2, '::1', '2026-05-11 15:45:17', 'preinscripcion_detalle.php'),
(1782, 2, '::1', '2026-05-11 15:45:18', 'preinscripcion_detalle.php'),
(1783, 2, '::1', '2026-05-11 15:45:18', 'preinscripcion_detalle.php'),
(1784, 2, '::1', '2026-05-11 15:45:49', 'preinscripcion_detalle.php'),
(1785, 2, '::1', '2026-05-11 15:46:32', 'preinscripcion_detalle.php'),
(1786, 2, '::1', '2026-05-11 15:55:15', 'preinscripcion_detalle.php'),
(1787, 2, '::1', '2026-05-11 15:55:17', 'preinscripcion_detalle.php'),
(1788, 2, '::1', '2026-05-11 15:55:23', 'preinscripcion_detalle.php'),
(1789, 2, '::1', '2026-05-11 15:55:40', 'preinscripcion_detalle.php'),
(1790, 2, '::1', '2026-05-11 16:06:35', 'preinscripcion_detalle.php'),
(1791, 2, '::1', '2026-05-11 16:06:41', 'preinscripcion_detalle.php'),
(1792, 2, '::1', '2026-05-11 16:06:45', 'preinscripcion_detalle.php'),
(1793, 2, '::1', '2026-05-11 16:09:45', 'preinscripcion_detalle.php'),
(1794, 2, '::1', '2026-05-11 16:18:30', 'preinscripcion_detalle.php'),
(1795, 2, '::1', '2026-05-11 18:04:17', 'preinscripcion_detalle.php'),
(1796, 2, '::1', '2026-05-11 18:40:52', 'preinscripcion_detalle.php'),
(1797, 2, '::1', '2026-05-11 18:42:34', 'preinscripciones.php'),
(1798, 2, '::1', '2026-05-11 18:42:43', 'index.php'),
(1799, 2, '::1', '2026-05-11 18:42:45', 'secretaria.php'),
(1800, 2, '::1', '2026-05-11 19:26:28', 'secretaria.php'),
(1801, 2, '::1', '2026-05-11 19:26:41', 'gestion_seccion.php'),
(1802, 2, '::1', '2026-05-11 19:29:29', 'index.php'),
(1803, 2, '::1', '2026-05-11 19:29:33', 'preinscripciones.php'),
(1804, 2, '::1', '2026-05-11 19:29:35', 'preinscripcion_detalle.php'),
(1805, 2, '::1', '2026-05-13 13:10:16', 'index.php'),
(1806, 2, '::1', '2026-05-13 13:10:21', 'gestion_seccion.php'),
(1807, 2, '::1', '2026-05-13 13:56:57', 'index.php'),
(1808, 2, '::1', '2026-05-13 13:57:01', 'gestion_seccion.php'),
(1809, 2, '::1', '2026-05-13 13:57:12', 'gestion_seccion.php'),
(1810, 2, '::1', '2026-05-13 13:57:23', 'gestion_seccion.php'),
(1811, 2, '::1', '2026-05-13 13:57:23', 'gestion_seccion.php'),
(1812, 2, '::1', '2026-05-13 13:58:01', 'registro_pagos.php'),
(1813, 2, '::1', '2026-05-13 13:58:02', 'gestion_seccion.php'),
(1814, 2, '::1', '2026-05-13 14:33:07', 'index.php'),
(1815, 2, '::1', '2026-05-13 14:33:16', 'gestion_seccion.php'),
(1816, 2, '::1', '2026-05-13 14:33:21', 'preinscripciones.php'),
(1817, 2, '::1', '2026-05-13 14:33:22', 'preinscripcion_detalle.php'),
(1818, 2, '::1', '2026-05-13 15:49:02', 'index.php'),
(1819, 2, '::1', '2026-05-13 15:49:08', 'secretaria.php'),
(1820, 2, '::1', '2026-05-13 15:49:53', 'secretaria.php'),
(1821, 2, '::1', '2026-05-14 13:43:01', 'index.php'),
(1822, 2, '::1', '2026-05-14 13:44:11', 'secretaria.php'),
(1823, 2, '::1', '2026-05-14 13:44:27', 'index.php'),
(1824, 2, '::1', '2026-05-14 13:45:19', 'index.php'),
(1825, 2, '::1', '2026-05-14 13:45:22', 'aprobar_secciones.php'),
(1826, 2, '::1', '2026-05-14 13:45:27', 'aprobar_secciones.php'),
(1827, 2, '::1', '2026-05-14 13:45:31', 'gestion_seccion.php'),
(1828, 2, '::1', '2026-05-14 13:45:59', 'index.php'),
(1829, 2, '::1', '2026-05-14 13:46:00', 'secretaria.php'),
(1830, 2, '::1', '2026-05-14 14:14:25', 'index.php'),
(1831, 2, '::1', '2026-05-14 14:19:10', 'index.php'),
(1832, 2, '::1', '2026-05-14 14:19:23', 'index.php'),
(1833, 2, '::1', '2026-05-14 14:19:32', 'index.php'),
(1834, 2, '::1', '2026-05-14 14:19:51', 'index.php'),
(1835, 2, '::1', '2026-05-14 14:19:53', 'secretaria.php'),
(1836, 2, '::1', '2026-05-14 15:04:44', 'index.php'),
(1837, 2, '::1', '2026-05-14 15:04:46', 'secretaria.php'),
(1838, 2, '::1', '2026-05-14 15:05:07', 'secretaria.php'),
(1839, 2, '::1', '2026-05-14 15:19:17', 'index.php'),
(1840, 2, '::1', '2026-05-14 15:19:23', 'preinscripciones.php'),
(1841, 2, '::1', '2026-05-14 15:19:27', 'preinscripcion_detalle.php'),
(1842, 2, '::1', '2026-05-14 15:20:35', 'gestion_seccion.php'),
(1843, 2, '::1', '2026-05-14 15:20:46', 'preinscripcion_detalle.php'),
(1844, 2, '::1', '2026-05-14 15:20:50', 'gestion_seccion.php'),
(1845, 2, '::1', '2026-05-14 15:20:52', 'gestion_seccion.php'),
(1846, 2, '::1', '2026-05-14 15:21:21', 'estudiantes.php'),
(1847, 2, '::1', '2026-05-14 15:21:21', 'estudiantes.php'),
(1848, 2, '::1', '2026-05-14 15:22:46', 'index.php'),
(1849, 2, '::1', '2026-05-14 15:24:02', 'index.php'),
(1850, 2, '::1', '2026-05-14 15:26:48', 'index.php'),
(1851, 2, '::1', '2026-05-14 15:27:03', 'auditoria.php'),
(1852, 2, '::1', '2026-05-14 15:28:00', 'index.php'),
(1853, 2, '::1', '2026-05-14 15:28:05', 'mensajeria.php'),
(1854, 2, '::1', '2026-05-14 15:28:09', 'mensajeria.php'),
(1855, 2, '::1', '2026-05-14 15:28:11', 'mensajeria.php'),
(1856, 2, '::1', '2026-05-14 15:28:13', 'mensajeria.php'),
(1857, 2, '::1', '2026-05-14 15:28:21', 'mensajeria.php'),
(1858, 2, '::1', '2026-05-14 15:28:28', 'mensajeria.php'),
(1859, 2, '::1', '2026-05-14 15:28:34', 'index.php'),
(1860, 2, '::1', '2026-05-14 15:29:04', 'secretaria.php'),
(1861, 2, '::1', '2026-05-14 15:30:27', 'secretaria.php'),
(1862, 2, '::1', '2026-05-14 15:30:45', 'index.php'),
(1863, 2, '::1', '2026-05-14 15:38:05', 'preinscripciones.php'),
(1864, 2, '::1', '2026-05-14 15:38:14', 'estudiantes.php'),
(1865, 2, '::1', '2026-05-14 15:38:14', 'estudiantes.php'),
(1866, 2, '::1', '2026-05-14 15:38:35', 'index.php'),
(1867, 2, '::1', '2026-05-14 15:49:20', 'index.php'),
(1868, 2, '::1', '2026-05-14 15:49:45', 'horarios_docentes.php'),
(1869, 2, '::1', '2026-05-14 15:50:13', 'add_docente.php'),
(1870, 2, '::1', '2026-05-14 15:50:47', 'asignacion_cursos.php'),
(1871, 2, '::1', '2026-05-14 15:51:09', 'asignar_secciones.php'),
(1872, 2, '::1', '2026-05-14 15:51:17', 'asignar_secciones.php'),
(1873, 2, '::1', '2026-05-14 15:51:21', 'asignar_secciones.php'),
(1874, 2, '::1', '2026-05-14 15:51:25', 'asignar_secciones.php'),
(1875, 2, '::1', '2026-05-14 15:51:32', 'horarios_docentes.php'),
(1876, 2, '::1', '2026-05-14 15:51:36', 'horarios_docentes.php'),
(1877, 2, '::1', '2026-05-15 13:32:00', 'index.php'),
(1878, 2, '::1', '2026-05-15 13:36:03', 'secretaria.php'),
(1879, 2, '::1', '2026-05-15 13:38:54', 'index.php'),
(1880, 2, '::1', '2026-05-15 13:38:58', 'index.php'),
(1881, 2, '::1', '2026-05-15 14:04:37', 'index.php'),
(1882, 2, '::1', '2026-05-15 14:29:37', 'index.php'),
(1883, 2, '::1', '2026-05-15 14:29:41', 'asignacion_cursos.php'),
(1884, 2, '::1', '2026-05-15 14:30:17', 'asignacion_cursos.php'),
(1885, 2, '::1', '2026-05-15 14:30:28', 'index.php'),
(1886, 2, '::1', '2026-05-15 14:34:52', 'index.php'),
(1887, 2, '::1', '2026-05-15 14:34:54', 'index.php'),
(1888, 2, '::1', '2026-05-15 14:35:36', 'asignar_secciones.php'),
(1889, 2, '::1', '2026-05-15 14:35:43', 'asignar_secciones.php'),
(1890, 2, '::1', '2026-05-15 14:35:48', 'asignar_secciones.php'),
(1891, 2, '::1', '2026-05-15 14:36:03', 'asignar_secciones.php'),
(1892, 2, '::1', '2026-05-15 14:36:06', 'asignar_secciones.php'),
(1893, 2, '::1', '2026-05-15 14:36:08', 'asignar_secciones.php'),
(1894, 2, '::1', '2026-05-15 14:36:10', 'asignar_secciones.php'),
(1895, 2, '::1', '2026-05-15 14:36:11', 'asignar_secciones.php'),
(1896, 2, '::1', '2026-05-15 14:36:17', 'asignar_secciones.php'),
(1897, 2, '::1', '2026-05-15 14:36:19', 'asignar_secciones.php'),
(1898, 2, '::1', '2026-05-15 14:36:26', 'index.php'),
(1899, 2, '::1', '2026-05-15 14:37:35', 'index.php'),
(1900, 2, '::1', '2026-05-15 14:37:37', 'gestion_seccion.php'),
(1901, 2, '::1', '2026-05-15 14:37:43', 'gestion_seccion.php'),
(1902, 2, '::1', '2026-05-15 14:37:45', 'gestion_seccion.php'),
(1903, 2, '::1', '2026-05-15 14:38:00', 'index.php'),
(1904, 2, '::1', '2026-05-15 14:38:35', 'index.php'),
(1905, 2, '::1', '2026-05-15 14:38:37', 'asignar_secciones.php'),
(1906, 2, '::1', '2026-05-15 14:38:49', 'asignacion_cursos.php'),
(1907, 2, '::1', '2026-05-15 14:38:59', 'asignacion_cursos.php'),
(1908, 2, '::1', '2026-05-15 14:39:20', 'asignacion_cursos.php'),
(1909, 2, '::1', '2026-05-15 14:39:28', 'asignar_secciones.php'),
(1910, 2, '::1', '2026-05-15 14:39:39', 'asignar_secciones.php'),
(1911, 2, '::1', '2026-05-15 14:40:04', 'asignar_secciones.php'),
(1912, 2, '::1', '2026-05-15 14:40:13', 'index.php'),
(1913, 2, '::1', '2026-05-15 14:40:16', 'index.php'),
(1914, 2, '::1', '2026-05-15 14:41:17', 'index.php'),
(1915, 2, '::1', '2026-05-15 14:41:23', 'gestion_seccion.php'),
(1916, 2, '::1', '2026-05-15 14:41:28', 'gestion_seccion.php'),
(1917, 2629, '::1', '2026-05-15 14:41:53', 'index.php'),
(1918, 2629, '::1', '2026-05-15 14:41:57', 'mi_horario.php'),
(1919, 2629, '::1', '2026-05-15 14:45:34', 'index.php'),
(1920, 2629, '::1', '2026-05-15 14:45:38', 'index.php'),
(1921, 2629, '::1', '2026-05-15 14:45:41', 'mi_pensum.php'),
(1922, 2, '::1', '2026-05-15 14:45:58', 'index.php'),
(1923, 2, '::1', '2026-05-15 14:46:11', 'secretaria.php'),
(1924, 2, '::1', '2026-05-15 14:46:54', 'index.php'),
(1925, 2, '::1', '2026-05-15 16:03:07', 'index.php'),
(1926, 2, '::1', '2026-05-15 16:03:10', 'mi_horario.php'),
(1927, 2, '::1', '2026-05-15 16:03:15', 'index.php'),
(1928, 2, '::1', '2026-05-15 16:03:18', 'gestion_seccion.php'),
(1929, 2, '::1', '2026-05-15 16:03:22', 'gestion_seccion.php'),
(1930, 2, '::1', '2026-05-15 16:03:23', 'gestion_seccion.php'),
(1931, 2, '::1', '2026-05-15 16:03:35', 'index.php'),
(1932, 2, '::1', '2026-05-15 16:10:49', 'index.php'),
(1933, 2, '::1', '2026-05-15 16:17:15', 'index.php'),
(1934, 2, '::1', '2026-05-15 16:17:25', 'index.php'),
(1935, 2, '::1', '2026-05-15 16:17:27', 'mi_horario.php'),
(1936, 2, '::1', '2026-05-15 16:17:31', 'index.php'),
(1937, 2, '::1', '2026-05-15 16:17:34', 'gestion_seccion.php'),
(1938, 2, '::1', '2026-05-15 16:17:36', 'gestion_seccion.php'),
(1939, 2628, '::1', '2026-05-15 16:17:57', 'index.php'),
(1940, 2628, '::1', '2026-05-15 16:18:01', 'mi_horario.php'),
(1941, 2628, '::1', '2026-05-15 16:26:35', 'mi_horario.php'),
(1942, 2628, '::1', '2026-05-15 16:28:40', 'mi_horario.php'),
(1943, 2628, '::1', '2026-05-15 16:31:02', 'mi_horario.php'),
(1944, 2628, '::1', '2026-05-15 16:31:52', 'mi_horario.php'),
(1945, 2628, '::1', '2026-05-15 16:34:03', 'mi_horario.php'),
(1946, 2, '::1', '2026-05-15 16:36:47', 'index.php'),
(1947, 2, '::1', '2026-05-15 16:36:51', 'index.php'),
(1948, 2, '::1', '2026-05-15 16:39:09', 'index.php'),
(1949, 2, '::1', '2026-05-15 16:39:12', 'gestion_seccion.php'),
(1950, 2, '::1', '2026-05-15 16:39:14', 'gestion_seccion.php'),
(1951, 2629, '::1', '2026-05-15 16:39:32', 'index.php'),
(1952, 2629, '::1', '2026-05-15 16:39:37', 'mi_horario.php'),
(1953, 2, '::1', '2026-05-15 16:40:15', 'index.php'),
(1954, 2, '::1', '2026-05-15 16:40:18', 'index.php'),
(1955, 2629, '::1', '2026-05-15 16:41:12', 'index.php'),
(1956, 2629, '::1', '2026-05-15 16:41:17', 'mi_horario.php'),
(1957, 2629, '::1', '2026-05-15 16:46:46', 'mi_horario.php'),
(1958, 2629, '::1', '2026-05-15 16:46:49', 'mi_horario.php'),
(1959, 2629, '::1', '2026-05-15 16:52:13', 'mi_horario.php'),
(1960, 2629, '::1', '2026-05-15 16:57:49', 'mi_horario.php'),
(1961, 2629, '::1', '2026-05-15 17:07:27', 'mi_horario.php'),
(1962, 2629, '::1', '2026-05-15 17:08:05', 'mi_horario.php'),
(1963, 2629, '::1', '2026-05-15 17:10:37', 'mi_horario.php'),
(1964, 2629, '::1', '2026-05-15 17:14:47', 'mi_horario.php'),
(1965, 2629, '::1', '2026-05-15 17:26:20', 'mi_horario.php'),
(1966, 2, '::1', '2026-05-15 17:27:25', 'index.php'),
(1967, 2, '::1', '2026-05-15 17:27:56', 'index.php'),
(1968, 2, '::1', '2026-05-15 17:28:00', 'gestion_seccion.php'),
(1969, 2, '::1', '2026-05-15 17:28:02', 'gestion_seccion.php'),
(1970, 2629, '::1', '2026-05-15 17:28:15', 'index.php'),
(1971, 2629, '::1', '2026-05-15 17:28:17', 'mi_horario.php'),
(1972, 2, '::1', '2026-05-15 17:28:55', 'index.php'),
(1973, 2, '::1', '2026-05-15 17:30:06', 'index.php'),
(1974, 2, '::1', '2026-05-15 17:30:09', 'horarios_docentes.php'),
(1975, 2, '::1', '2026-05-15 17:30:27', 'gestion_seccion.php'),
(1976, 2, '::1', '2026-05-15 17:30:36', 'gestion_seccion.php'),
(1977, 2, '::1', '2026-05-15 17:30:43', 'gestion_seccion.php'),
(1978, 2, '::1', '2026-05-18 14:33:37', 'index.php'),
(1979, 2, '::1', '2026-05-18 14:33:41', 'gestion_seccion.php'),
(1980, 2, '::1', '2026-05-18 14:33:47', 'gestion_seccion.php'),
(1981, 2, '::1', '2026-05-18 14:33:52', 'gestion_seccion.php'),
(1982, 2, '::1', '2026-05-18 14:40:15', 'gestion_seccion.php'),
(1983, 2, '::1', '2026-05-18 14:40:43', 'gestion_seccion.php'),
(1984, 2, '::1', '2026-05-18 14:40:48', 'gestion_seccion.php'),
(1985, 2, '::1', '2026-05-18 14:40:50', 'gestion_seccion.php'),
(1986, 2, '::1', '2026-05-18 14:47:24', 'gestion_seccion.php'),
(1987, 2, '::1', '2026-05-18 14:57:22', 'gestion_seccion.php'),
(1988, 2, '::1', '2026-05-18 14:57:40', 'gestion_seccion.php'),
(1989, 2, '::1', '2026-05-18 15:05:28', 'gestion_seccion.php'),
(1990, 2, '::1', '2026-05-18 15:05:46', 'gestion_seccion.php'),
(1991, 2, '::1', '2026-05-18 15:05:50', 'gestion_seccion.php'),
(1992, 2, '::1', '2026-05-18 15:35:12', 'gestion_seccion.php'),
(1993, 2, '::1', '2026-05-18 15:43:55', 'gestion_seccion.php'),
(1994, 2, '::1', '2026-05-18 15:51:09', 'index.php'),
(1995, 2, '::1', '2026-05-18 15:51:19', 'index.php'),
(1996, 2, '::1', '2026-05-18 15:51:44', 'index.php'),
(1997, 2, '::1', '2026-05-18 15:51:48', 'preinscripciones.php'),
(1998, 2, '::1', '2026-05-18 15:51:51', 'gestion_seccion.php'),
(1999, 2, '::1', '2026-05-18 15:51:54', 'estudiantes.php'),
(2000, 2, '::1', '2026-05-18 15:51:55', 'estudiantes.php'),
(2001, 2, '::1', '2026-05-18 15:52:06', 'gestion_seccion.php'),
(2002, 2, '::1', '2026-05-18 15:52:10', 'gestion_seccion.php'),
(2003, 2628, '::1', '2026-05-18 15:52:38', 'index.php'),
(2004, 2628, '::1', '2026-05-18 15:52:42', 'mi_horario.php'),
(2005, 2628, '::1', '2026-05-18 15:52:49', 'index.php'),
(2006, 2628, '::1', '2026-05-18 15:52:52', 'mis_secciones.php'),
(2007, 2628, '::1', '2026-05-18 15:52:53', 'index.php'),
(2008, 2628, '::1', '2026-05-18 15:52:55', 'mi_pensum.php'),
(2009, 2628, '::1', '2026-05-18 15:53:21', 'index.php'),
(2010, 2628, '::1', '2026-05-18 15:53:24', 'mi_historial.php'),
(2011, 2628, '::1', '2026-05-18 15:53:35', 'index.php'),
(2012, 2628, '::1', '2026-05-18 15:53:36', 'mis_constancias.php'),
(2013, 2, '::1', '2026-05-18 15:54:12', 'index.php'),
(2014, 2, '::1', '2026-05-18 15:54:28', 'gestion_seccion.php'),
(2015, 2, '::1', '2026-05-18 15:54:35', 'gestion_seccion.php'),
(2016, 2, '::1', '2026-05-18 15:54:41', 'gestion_seccion.php'),
(2017, 2, '::1', '2026-05-18 15:54:50', 'gestion_seccion.php'),
(2018, 2, '::1', '2026-05-18 15:54:53', 'gestion_seccion.php'),
(2019, 2, '::1', '2026-05-18 15:55:07', 'gestion_seccion.php'),
(2020, 2, '::1', '2026-05-18 15:55:17', 'gestion_seccion.php'),
(2021, 2, '::1', '2026-05-18 15:56:20', 'gestion_seccion.php'),
(2022, 2, '::1', '2026-05-18 16:03:02', 'gestion_seccion.php'),
(2023, 2, '::1', '2026-05-18 16:27:37', 'gestion_seccion.php'),
(2024, 2, '::1', '2026-05-18 16:27:43', 'gestion_seccion.php'),
(2025, 2, '::1', '2026-05-18 16:27:53', 'gestion_seccion.php'),
(2026, 2, '::1', '2026-05-18 16:27:58', 'gestion_seccion.php'),
(2027, 2, '::1', '2026-05-18 16:28:44', 'gestion_seccion.php'),
(2028, 2, '::1', '2026-05-18 16:44:23', 'gestion_seccion.php'),
(2029, 2, '::1', '2026-05-18 16:44:24', 'index.php'),
(2030, 2, '::1', '2026-05-18 16:44:41', 'index.php'),
(2031, 2, '::1', '2026-05-18 16:44:42', 'index.php'),
(2032, 2, '::1', '2026-05-18 16:45:22', 'gestion_seccion.php'),
(2033, 2, '::1', '2026-05-18 16:45:38', 'gestion_seccion.php'),
(2034, 2, '::1', '2026-05-18 16:45:47', 'gestion_seccion.php'),
(2035, 2, '::1', '2026-05-18 16:46:08', 'gestion_seccion.php'),
(2036, 2, '::1', '2026-05-18 16:46:57', 'gestion_seccion.php'),
(2037, 2, '::1', '2026-05-18 16:47:07', 'gestion_seccion.php'),
(2038, 2, '::1', '2026-05-18 16:53:43', 'gestion_seccion.php'),
(2039, 2, '::1', '2026-05-18 16:53:46', 'index.php'),
(2040, 2, '::1', '2026-05-18 16:53:48', 'gestion_seccion.php'),
(2041, 2, '::1', '2026-05-18 17:27:10', 'crear_seccion.php'),
(2042, 2, '::1', '2026-05-18 17:31:13', 'gestion_seccion.php'),
(2043, 2, '::1', '2026-05-18 17:31:14', 'gestion_seccion.php'),
(2044, 2, '::1', '2026-05-18 17:31:28', 'gestion_seccion.php'),
(2045, 2, '::1', '2026-05-18 17:31:36', 'gestion_seccion.php'),
(2046, 2, '::1', '2026-05-18 17:32:04', 'gestion_seccion.php'),
(2047, 2, '::1', '2026-05-18 17:32:14', 'gestion_seccion.php'),
(2048, 2, '::1', '2026-05-18 17:34:59', 'gestion_seccion.php'),
(2049, 2, '::1', '2026-05-18 17:34:59', 'gestion_seccion.php'),
(2050, 2, '::1', '2026-05-18 17:35:00', 'gestion_seccion.php'),
(2051, 2, '::1', '2026-05-18 17:35:23', 'gestion_seccion.php'),
(2052, 2, '::1', '2026-05-18 17:35:30', 'gestion_seccion.php'),
(2053, 2, '::1', '2026-05-18 17:35:40', 'gestion_seccion.php'),
(2054, 2, '::1', '2026-05-18 17:39:12', 'gestion_seccion.php'),
(2055, 2, '::1', '2026-05-18 17:47:57', 'gestion_seccion.php'),
(2056, 2, '::1', '2026-05-18 17:48:47', 'gestion_seccion.php'),
(2057, 2, '::1', '2026-05-18 17:55:24', 'gestion_seccion.php'),
(2058, 2, '::1', '2026-05-18 17:55:24', 'gestion_seccion.php'),
(2059, 2, '::1', '2026-05-18 17:55:25', 'gestion_seccion.php'),
(2060, 2, '::1', '2026-05-18 17:55:26', 'gestion_seccion.php'),
(2061, 2, '::1', '2026-05-18 17:56:11', 'gestion_seccion.php'),
(2062, 2, '::1', '2026-05-18 17:56:59', 'gestion_seccion.php'),
(2063, 2, '::1', '2026-05-18 18:15:53', 'gestion_seccion.php'),
(2064, 2, '::1', '2026-05-18 18:17:23', 'ver_seccion.php'),
(2065, 2, '::1', '2026-05-18 18:17:40', 'gestion_seccion.php'),
(2066, 2, '::1', '2026-05-18 18:17:42', 'ver_seccion.php'),
(2067, 2, '::1', '2026-05-18 18:17:46', 'horario_seccion.php'),
(2068, 2, '::1', '2026-05-18 18:20:48', 'ver_seccion.php'),
(2069, 2, '::1', '2026-05-18 18:31:49', 'ver_seccion.php'),
(2070, 2, '::1', '2026-05-18 18:32:02', 'gestion_seccion.php'),
(2071, 2, '::1', '2026-05-18 18:32:06', 'aprobar_secciones.php'),
(2072, 2, '::1', '2026-05-18 18:32:12', 'index.php'),
(2073, 2, '::1', '2026-05-18 18:32:16', 'gestion_seccion.php'),
(2074, 2, '::1', '2026-05-18 18:32:36', 'ver_seccion.php'),
(2075, 2, '::1', '2026-05-18 18:32:38', 'editar_seccion.php'),
(2076, 2, '::1', '2026-05-18 18:32:41', 'gestion_seccion.php'),
(2077, 2, '::1', '2026-05-18 18:32:43', 'ver_seccion.php'),
(2078, 2, '::1', '2026-05-18 18:32:45', 'asignar_estudiantes.php'),
(2079, 2, '::1', '2026-05-18 18:33:12', 'ver_seccion.php'),
(2080, 2, '::1', '2026-05-18 18:33:14', 'horario_seccion.php'),
(2081, 2, '::1', '2026-05-19 13:30:23', 'index.php'),
(2082, 2, '::1', '2026-05-19 13:30:44', 'gestion_seccion.php'),
(2083, 2, '::1', '2026-05-19 13:38:07', 'ver_seccion.php'),
(2084, 2, '::1', '2026-05-19 13:38:13', 'horario_seccion.php'),
(2085, 2, '::1', '2026-05-19 13:38:16', 'ver_seccion.php'),
(2086, 2, '::1', '2026-05-19 13:38:17', 'gestion_seccion.php'),
(2087, 2, '::1', '2026-05-19 13:38:21', 'ver_seccion.php'),
(2088, 2, '::1', '2026-05-19 13:38:33', 'editar_seccion.php'),
(2089, 2, '::1', '2026-05-19 13:38:36', 'gestion_seccion.php'),
(2090, 2, '::1', '2026-05-19 13:40:06', 'ver_seccion.php'),
(2091, 2, '::1', '2026-05-19 13:41:34', 'gestion_seccion.php'),
(2092, 2, '::1', '2026-05-19 13:41:35', 'ver_seccion.php'),
(2093, 2, '::1', '2026-05-19 14:05:15', 'gestion_seccion.php'),
(2094, 2, '::1', '2026-05-19 14:05:20', 'gestion_seccion.php'),
(2095, 2, '::1', '2026-05-19 14:05:21', 'ver_seccion.php'),
(2096, 2, '::1', '2026-05-19 14:05:22', 'gestion_seccion.php'),
(2097, 2, '::1', '2026-05-19 14:05:23', 'editar_seccion.php'),
(2098, 2, '::1', '2026-05-19 14:05:23', 'ver_seccion.php'),
(2099, 2, '::1', '2026-05-19 14:05:24', 'gestion_seccion.php'),
(2100, 2, '::1', '2026-05-19 14:05:24', 'ver_seccion.php'),
(2101, 2, '::1', '2026-05-19 14:05:25', 'horario_seccion.php'),
(2102, 2, '::1', '2026-05-19 14:05:26', 'ver_seccion.php'),
(2103, 2, '::1', '2026-05-19 14:05:26', 'gestion_seccion.php'),
(2104, 2, '::1', '2026-05-19 14:05:27', 'index.php'),
(2105, 2, '::1', '2026-05-19 14:05:29', 'index.php'),
(2106, 2, '::1', '2026-05-19 14:05:31', 'gestion_seccion.php'),
(2107, 2, '::1', '2026-05-19 14:26:06', 'gestion_seccion.php'),
(2108, 2, '::1', '2026-05-19 14:26:09', 'gestion_seccion.php'),
(2109, 2, '::1', '2026-05-19 14:26:10', 'index.php'),
(2110, 2, '::1', '2026-05-19 14:26:18', 'index.php'),
(2111, 2, '::1', '2026-05-19 14:26:24', 'registro_pagos.php'),
(2112, 2, '::1', '2026-05-19 14:26:25', 'index.php'),
(2113, 2, '::1', '2026-05-19 14:29:31', 'index.php'),
(2114, 2, '::1', '2026-05-19 14:29:33', 'gestion_seccion.php'),
(2115, 2, '::1', '2026-05-19 14:29:39', 'index.php'),
(2116, 2, '::1', '2026-05-19 14:29:46', 'gestion_seccion.php'),
(2117, 2, '::1', '2026-05-19 14:32:18', 'gestion_seccion.php'),
(2118, 2, '::1', '2026-05-19 14:32:24', 'index.php'),
(2119, 2, '::1', '2026-05-19 14:38:03', 'gestion_seccion.php'),
(2120, 2, '::1', '2026-05-19 14:38:08', 'index.php'),
(2121, 2, '::1', '2026-05-19 14:38:33', 'gestion_seccion.php'),
(2122, 2, '::1', '2026-05-19 14:44:55', 'gestion_seccion.php'),
(2123, 2, '::1', '2026-05-19 14:44:57', 'index.php'),
(2124, 2, '::1', '2026-05-19 14:47:41', 'gestion_seccion.php'),
(2125, 2, '::1', '2026-05-19 14:47:43', 'index.php'),
(2126, 2, '::1', '2026-05-19 14:48:06', 'gestion_seccion.php'),
(2127, 2, '::1', '2026-05-19 14:48:08', 'gestion_seccion.php'),
(2128, 2, '::1', '2026-05-19 14:48:33', 'index.php'),
(2129, 2, '::1', '2026-05-19 14:49:29', 'gestion_seccion.php'),
(2130, 2, '::1', '2026-05-19 14:49:32', 'index.php'),
(2131, 2, '::1', '2026-05-19 14:53:48', 'gestion_seccion.php'),
(2132, 2, '::1', '2026-05-19 14:53:50', 'index.php'),
(2133, 2, '::1', '2026-05-19 14:55:35', 'gestion_seccion.php'),
(2134, 2, '::1', '2026-05-19 14:55:40', 'index.php'),
(2135, 2, '::1', '2026-05-19 14:56:41', 'gestion_seccion.php'),
(2136, 2, '::1', '2026-05-19 15:02:03', 'gestion_seccion.php'),
(2137, 2, '::1', '2026-05-19 15:02:05', 'mensajeria.php'),
(2138, 2, '::1', '2026-05-19 15:02:07', 'index.php'),
(2139, 2, '::1', '2026-05-19 15:02:09', 'estudiantes.php'),
(2140, 2, '::1', '2026-05-19 15:02:09', 'estudiantes.php'),
(2141, 2, '::1', '2026-05-19 15:02:10', 'index.php'),
(2142, 2, '::1', '2026-05-19 15:02:12', 'gestion_seccion.php'),
(2143, 2, '::1', '2026-05-19 15:02:14', 'mensajeria.php'),
(2144, 2, '::1', '2026-05-19 15:02:15', 'index.php'),
(2145, 2, '::1', '2026-05-19 15:04:07', 'index.php'),
(2146, 2, '::1', '2026-05-19 15:04:12', 'gestion_seccion.php'),
(2147, 2, '::1', '2026-05-19 15:04:14', 'ver_seccion.php'),
(2148, 2, '::1', '2026-05-19 15:04:16', 'horario_seccion.php'),
(2149, 2, '::1', '2026-05-19 15:08:04', 'horario_seccion.php'),
(2150, 2, '::1', '2026-05-19 15:12:19', 'horario_seccion.php'),
(2151, 2, '::1', '2026-05-19 15:30:14', 'horario_seccion.php'),
(2152, 2, '::1', '2026-05-19 15:30:41', 'ver_seccion.php'),
(2153, 2, '::1', '2026-05-19 15:31:00', 'index.php'),
(2154, 2, '::1', '2026-05-19 15:31:05', 'index.php'),
(2155, 2, '::1', '2026-05-19 15:31:07', 'mi_horario.php'),
(2156, 2, '::1', '2026-05-19 15:31:08', 'index.php'),
(2157, 2, '::1', '2026-05-19 15:31:17', 'index.php'),
(2158, 2, '::1', '2026-05-19 15:31:24', 'gestion_seccion.php'),
(2159, 2, '::1', '2026-05-19 15:31:26', 'ver_seccion.php'),
(2160, 2628, '::1', '2026-05-19 15:32:03', 'index.php'),
(2161, 2628, '::1', '2026-05-19 15:32:07', 'mi_horario.php'),
(2162, 2628, '::1', '2026-05-19 15:32:39', 'index.php'),
(2163, 2628, '::1', '2026-05-19 15:32:40', 'mi_pensum.php'),
(2164, 2628, '::1', '2026-05-19 15:32:44', 'index.php'),
(2165, 2628, '::1', '2026-05-19 15:32:50', 'mi_historial.php'),
(2166, 2628, '::1', '2026-05-19 16:09:25', 'index.php'),
(2167, 2, '::1', '2026-05-19 16:09:53', 'index.php'),
(2168, 2, '::1', '2026-05-19 16:09:55', 'asignacion_cursos.php'),
(2169, 2, '::1', '2026-05-19 16:09:57', 'horarios_docentes.php'),
(2170, 2, '::1', '2026-05-19 16:10:00', 'horarios_docentes.php'),
(2171, 2, '::1', '2026-05-19 16:10:15', 'horarios_docentes.php'),
(2172, 2, '::1', '2026-05-19 16:11:57', 'grado.php'),
(2173, 2, '::1', '2026-05-19 16:12:01', 'horarios_docentes.php'),
(2174, 2, '::1', '2026-05-19 16:12:15', 'horarios_docentes.php'),
(2175, 2, '::1', '2026-05-19 16:13:05', 'horarios_docentes.php'),
(2176, 2, '::1', '2026-05-19 16:23:39', 'horarios_docentes.php'),
(2177, 2, '::1', '2026-05-19 16:23:42', 'horarios_docentes.php'),
(2178, 2, '::1', '2026-05-19 16:23:47', 'horarios_docentes.php'),
(2179, 2, '::1', '2026-05-19 16:24:12', 'horarios_docentes.php'),
(2180, 2, '::1', '2026-05-19 16:24:17', 'horarios_docentes.php'),
(2181, 2, '::1', '2026-05-19 16:24:33', 'horarios_docentes.php'),
(2182, 2, '::1', '2026-05-19 16:28:40', 'horarios_docentes.php'),
(2183, 2, '::1', '2026-05-19 16:28:43', 'horarios_docentes.php'),
(2184, 2, '::1', '2026-05-19 16:29:01', 'horarios_docentes.php'),
(2185, 2, '::1', '2026-05-19 16:29:59', 'grado.php'),
(2186, 2, '::1', '2026-05-19 16:30:02', 'grado.php'),
(2187, 2, '::1', '2026-05-19 16:30:07', 'horarios_docentes.php'),
(2188, 2, '::1', '2026-05-19 16:30:09', 'horarios_docentes.php'),
(2189, 2, '::1', '2026-05-19 16:33:47', 'horarios_docentes.php'),
(2190, 2, '::1', '2026-05-19 16:33:50', 'horarios_docentes.php'),
(2191, 2, '::1', '2026-05-19 16:34:04', 'horarios_docentes.php'),
(2192, 2, '::1', '2026-05-19 16:38:52', 'horarios_docentes.php'),
(2193, 2, '::1', '2026-05-19 16:38:55', 'horarios_docentes.php'),
(2194, 2, '::1', '2026-05-19 16:39:11', 'horarios_docentes.php'),
(2195, 2, '::1', '2026-05-19 16:39:14', 'horarios_docentes.php'),
(2196, 2, '::1', '2026-05-19 16:47:33', 'horarios_docentes.php'),
(2197, 2, '::1', '2026-05-19 16:47:36', 'horarios_docentes.php'),
(2198, 2, '::1', '2026-05-19 16:48:05', 'horarios_docentes.php'),
(2199, 2, '::1', '2026-05-19 16:48:11', 'horarios_docentes.php'),
(2200, 2, '::1', '2026-05-19 16:52:25', 'horarios_docentes.php'),
(2201, 2, '::1', '2026-05-19 16:52:28', 'horarios_docentes.php'),
(2202, 2, '::1', '2026-05-19 16:53:12', 'horarios_docentes.php'),
(2203, 2, '::1', '2026-05-19 16:59:16', 'horarios_docentes.php'),
(2204, 2, '::1', '2026-05-19 16:59:19', 'horarios_docentes.php'),
(2205, 2, '::1', '2026-05-19 16:59:30', 'horarios_docentes.php'),
(2206, 2, '::1', '2026-05-19 16:59:32', 'horarios_docentes.php'),
(2207, 2, '::1', '2026-05-19 16:59:41', 'horarios_docentes.php'),
(2208, 2, '::1', '2026-05-19 17:00:44', 'horarios_docentes.php'),
(2209, 2, '::1', '2026-05-19 17:00:47', 'horarios_docentes.php'),
(2210, 2, '::1', '2026-05-19 17:00:51', 'horarios_docentes.php'),
(2211, 2, '::1', '2026-05-19 17:05:38', 'horarios_docentes.php'),
(2212, 2, '::1', '2026-05-19 17:05:41', 'horarios_docentes.php'),
(2213, 2, '::1', '2026-05-19 17:06:00', 'horarios_docentes.php'),
(2214, 2, '::1', '2026-05-19 17:06:04', 'horarios_docentes.php'),
(2215, 2, '::1', '2026-05-19 17:10:36', 'horarios_docentes.php'),
(2216, 2, '::1', '2026-05-19 17:10:39', 'horarios_docentes.php'),
(2217, 2, '::1', '2026-05-19 17:13:04', 'horarios_docentes.php'),
(2218, 2, '::1', '2026-05-19 17:13:07', 'horarios_docentes.php'),
(2219, 2, '::1', '2026-05-19 17:13:22', 'horarios_docentes.php'),
(2220, 2, '::1', '2026-05-19 17:13:28', 'horarios_docentes.php'),
(2221, 2, '::1', '2026-05-19 17:13:41', 'horarios_docentes.php'),
(2222, 2, '::1', '2026-05-19 17:13:44', 'horarios_docentes.php'),
(2223, 2, '::1', '2026-05-19 17:14:59', 'horarios_docentes.php'),
(2224, 2, '::1', '2026-05-19 17:15:02', 'horarios_docentes.php'),
(2225, 2, '::1', '2026-05-19 17:15:13', 'index.php'),
(2226, 2, '::1', '2026-05-19 17:17:09', 'gestion_seccion.php'),
(2227, 2, '::1', '2026-05-19 17:42:02', 'gestion_seccion.php'),
(2228, 2, '::1', '2026-05-19 17:42:06', 'gestion_seccion.php'),
(2229, 2, '::1', '2026-05-19 17:42:07', 'gestion_seccion.php'),
(2230, 2, '::1', '2026-05-19 17:42:09', 'gestion_seccion.php'),
(2231, 2, '::1', '2026-05-19 17:42:10', 'gestion_seccion.php'),
(2232, 2, '::1', '2026-05-19 17:42:15', 'gestion_seccion.php'),
(2233, 2, '::1', '2026-05-19 17:42:16', 'gestion_seccion.php'),
(2234, 2, '::1', '2026-05-19 17:42:52', 'ver_seccion.php'),
(2235, 2, '::1', '2026-05-19 17:43:01', 'asignar_estudiantes.php'),
(2236, 2, '::1', '2026-05-19 17:43:15', 'ver_seccion.php'),
(2237, 2, '::1', '2026-05-19 17:43:19', 'gestion_seccion.php'),
(2238, 2, '::1', '2026-05-19 17:43:47', 'admin_notas_pendientes.php'),
(2239, 2, '::1', '2026-05-19 17:43:55', 'consulta_notas.php'),
(2240, 2, '::1', '2026-05-19 17:43:58', 'consulta_notas.php'),
(2241, 2, '::1', '2026-05-19 17:44:22', 'gestion_seccion.php'),
(2242, 2, '::1', '2026-05-19 17:44:33', 'ver_seccion.php'),
(2243, 2, '::1', '2026-05-19 17:44:39', 'gestion_seccion.php'),
(2244, 2, '::1', '2026-05-19 17:44:41', 'ver_seccion.php'),
(2245, 2, '::1', '2026-05-19 17:44:52', 'consulta_notas.php'),
(2246, 2, '::1', '2026-05-19 17:44:55', 'consulta_notas.php'),
(2247, 2, '::1', '2026-05-20 13:32:20', 'index.php'),
(2248, 2, '::1', '2026-05-20 13:35:34', 'horarios_docentes.php'),
(2249, 2, '::1', '2026-05-20 13:35:37', 'horarios_docentes.php'),
(2250, 2, '::1', '2026-05-20 13:36:36', 'registro_pagos.php'),
(2251, 2, '::1', '2026-05-20 13:36:37', 'index.php'),
(2252, 2, '::1', '2026-05-20 13:43:22', 'index.php'),
(2253, 2, '::1', '2026-05-20 13:43:25', 'preinscripciones.php'),
(2254, 2, '::1', '2026-05-20 13:43:28', 'preinscripcion_detalle.php'),
(2255, 2, '::1', '2026-05-20 13:50:33', 'preinscripcion_detalle.php'),
(2256, 2, '::1', '2026-05-20 13:51:11', 'preinscripcion_detalle.php'),
(2257, 2, '::1', '2026-05-20 13:52:30', 'estudiantes.php'),
(2258, 2, '::1', '2026-05-20 13:52:32', 'estudiantes.php'),
(2259, 2, '::1', '2026-05-20 15:01:15', 'estudiantes.php'),
(2260, 2, '::1', '2026-05-20 15:01:16', 'estudiantes.php'),
(2261, 2, '::1', '2026-05-20 15:04:14', 'estudiantes.php'),
(2262, 2, '::1', '2026-05-20 15:04:14', 'estudiantes.php'),
(2263, 2, '::1', '2026-05-20 15:04:43', 'gestion_seccion.php'),
(2264, 2, '::1', '2026-05-20 15:04:47', 'ver_seccion.php'),
(2265, 2, '::1', '2026-05-20 15:05:09', 'gestion_seccion.php'),
(2266, 2, '::1', '2026-05-20 15:05:10', 'estudiantes.php'),
(2267, 2, '::1', '2026-05-20 15:05:11', 'estudiantes.php'),
(2268, 2, '::1', '2026-05-20 15:05:12', 'estudiantes.php'),
(2269, 2, '::1', '2026-05-20 15:05:13', 'estudiantes.php'),
(2270, 2, '::1', '2026-05-20 15:11:08', 'estudiantes.php'),
(2271, 2, '::1', '2026-05-20 15:11:09', 'estudiantes.php'),
(2272, 2, '::1', '2026-05-20 15:11:11', 'estudiantes.php'),
(2273, 2, '::1', '2026-05-20 15:11:11', 'estudiantes.php'),
(2274, 2, '::1', '2026-05-20 15:11:16', 'gestion_seccion.php'),
(2275, 2, '::1', '2026-05-20 15:11:17', 'ver_seccion.php'),
(2276, 2, '::1', '2026-05-20 15:11:24', 'estudiantes.php'),
(2277, 2, '::1', '2026-05-20 15:11:24', 'estudiantes.php'),
(2278, 2, '::1', '2026-05-20 15:22:08', 'index.php'),
(2279, 2, '::1', '2026-05-20 15:22:11', 'agregar_estudiante.php'),
(2280, 2, '::1', '2026-05-20 15:22:11', 'agregar_estudiante.php'),
(2281, 2, '::1', '2026-05-20 15:43:21', 'gestion_seccion.php'),
(2282, 2, '::1', '2026-05-20 15:43:24', 'estudiantes.php'),
(2283, 2, '::1', '2026-05-20 15:43:24', 'estudiantes.php'),
(2284, 2, '::1', '2026-05-20 15:43:36', 'gestion_seccion.php'),
(2285, 2, '::1', '2026-05-20 15:43:53', 'aprobar_secciones.php'),
(2286, 2, '::1', '2026-05-20 15:43:57', 'aprobar_secciones.php'),
(2287, 2, '::1', '2026-05-20 15:44:02', 'gestion_seccion.php'),
(2288, 2, '::1', '2026-05-20 15:44:06', 'ver_seccion.php'),
(2289, 2, '::1', '2026-05-20 15:44:10', 'gestion_seccion.php'),
(2290, 2, '::1', '2026-05-20 15:46:03', 'index.php'),
(2291, 2, '::1', '2026-05-20 15:46:08', 'secretaria.php'),
(2292, 2, '::1', '2026-05-20 15:46:25', 'secretaria.php'),
(2293, 2, '::1', '2026-05-20 15:46:41', 'secretaria.php'),
(2294, 2, '::1', '2026-05-20 15:52:19', 'index.php'),
(2295, 2, '::1', '2026-05-20 15:52:24', 'preinscripciones.php'),
(2296, 2, '::1', '2026-05-20 15:52:30', 'preinscripcion_detalle.php'),
(2297, 2, '::1', '2026-05-20 15:53:06', 'preinscripcion_detalle.php'),
(2298, 2, '::1', '2026-05-20 15:53:12', 'gestion_seccion.php'),
(2299, 2, '::1', '2026-05-20 15:53:16', 'ver_seccion.php'),
(2300, 2, '::1', '2026-05-20 15:53:25', 'estudiantes.php'),
(2301, 2, '::1', '2026-05-20 15:53:25', 'estudiantes.php'),
(2302, 2, '::1', '2026-05-20 16:01:47', 'estudiantes.php'),
(2303, 2, '::1', '2026-05-20 16:01:47', 'estudiantes.php'),
(2304, 2, '::1', '2026-05-20 16:01:56', 'gestion_seccion.php'),
(2305, 2, '::1', '2026-05-20 16:01:57', 'ver_seccion.php'),
(2306, 2, '::1', '2026-05-20 16:02:04', 'estudiantes.php'),
(2307, 2, '::1', '2026-05-20 16:02:04', 'estudiantes.php'),
(2308, 2, '::1', '2026-05-20 16:11:58', 'preinscripciones.php'),
(2309, 2, '::1', '2026-05-20 16:12:05', 'gestion_seccion.php'),
(2310, 2, '::1', '2026-05-20 16:12:25', 'editar_seccion.php'),
(2311, 2, '::1', '2026-05-20 16:12:54', 'gestion_seccion.php'),
(2312, 2, '::1', '2026-05-20 16:13:42', 'gestion_seccion.php'),
(2313, 2, '::1', '2026-05-20 16:13:50', 'preinscripciones.php'),
(2314, 2, '::1', '2026-05-20 16:13:53', 'preinscripcion_detalle.php'),
(2315, 2, '::1', '2026-05-20 16:14:05', 'preinscripcion_detalle.php'),
(2316, 2, '::1', '2026-05-20 16:14:12', 'estudiantes.php'),
(2317, 2, '::1', '2026-05-20 16:14:12', 'estudiantes.php'),
(2318, 2, '::1', '2026-05-20 16:15:58', 'index.php'),
(2319, 2, '::1', '2026-05-20 16:16:00', 'estudiantes.php'),
(2320, 2, '::1', '2026-05-20 16:16:00', 'estudiantes.php'),
(2321, 2, '::1', '2026-05-20 16:22:10', 'estudiantes.php'),
(2322, 2, '::1', '2026-05-20 16:22:11', 'estudiantes.php'),
(2323, 2, '::1', '2026-05-20 16:29:42', 'estudiantes.php'),
(2324, 2, '::1', '2026-05-20 16:29:42', 'estudiantes.php'),
(2325, 2, '::1', '2026-05-20 16:29:56', 'estudiantes.php'),
(2326, 2, '::1', '2026-05-20 16:29:57', 'estudiantes.php'),
(2327, 2, '::1', '2026-05-20 16:30:04', 'estudiantes.php'),
(2328, 2, '::1', '2026-05-20 16:30:04', 'estudiantes.php'),
(2329, 2, '::1', '2026-05-20 16:30:08', 'estudiantes.php'),
(2330, 2, '::1', '2026-05-20 16:30:08', 'estudiantes.php'),
(2331, 2, '::1', '2026-05-20 16:30:13', 'estudiantes.php'),
(2332, 2, '::1', '2026-05-20 16:30:13', 'estudiantes.php'),
(2333, 2, '::1', '2026-05-20 16:30:54', 'gestion_seccion.php'),
(2334, 2, '::1', '2026-05-20 16:30:56', 'ver_seccion.php'),
(2335, 2, '::1', '2026-05-20 16:31:26', 'gestion_seccion.php'),
(2336, 2, '::1', '2026-05-20 16:31:30', 'estudiantes.php'),
(2337, 2, '::1', '2026-05-20 16:31:30', 'estudiantes.php'),
(2338, 2, '::1', '2026-05-20 16:31:48', 'estudiantes.php'),
(2339, 2, '::1', '2026-05-20 16:31:48', 'estudiantes.php'),
(2340, 2, '::1', '2026-05-20 16:32:00', 'estudiantes.php'),
(2341, 2, '::1', '2026-05-20 16:32:00', 'estudiantes.php'),
(2342, 2, '::1', '2026-05-20 16:32:15', 'estudiantes.php'),
(2343, 2, '::1', '2026-05-20 16:32:15', 'estudiantes.php'),
(2344, 2, '::1', '2026-05-20 16:38:08', 'estudiantes.php'),
(2345, 2, '::1', '2026-05-20 16:38:08', 'estudiantes.php'),
(2346, 2, '::1', '2026-05-20 16:38:31', 'estudiantes.php'),
(2347, 2, '::1', '2026-05-20 16:38:31', 'estudiantes.php'),
(2348, 2, '::1', '2026-05-20 16:41:29', 'estudiantes.php'),
(2349, 2, '::1', '2026-05-20 16:41:29', 'estudiantes.php'),
(2350, 2, '::1', '2026-05-20 16:41:38', 'estudiantes.php'),
(2351, 2, '::1', '2026-05-20 16:41:38', 'estudiantes.php'),
(2352, 2, '::1', '2026-05-20 16:41:44', 'estudiantes.php'),
(2353, 2, '::1', '2026-05-20 16:41:44', 'estudiantes.php'),
(2354, 2, '::1', '2026-05-20 16:41:45', 'estudiantes.php'),
(2355, 2, '::1', '2026-05-20 16:41:45', 'estudiantes.php'),
(2356, 2, '::1', '2026-05-20 16:41:56', 'estudiantes.php'),
(2357, 2, '::1', '2026-05-20 16:41:56', 'estudiantes.php'),
(2358, 2, '::1', '2026-05-20 16:42:00', 'estudiantes.php'),
(2359, 2, '::1', '2026-05-20 16:42:00', 'estudiantes.php'),
(2360, 2, '::1', '2026-05-20 16:42:11', 'estudiantes.php'),
(2361, 2, '::1', '2026-05-20 16:42:11', 'estudiantes.php'),
(2362, 2, '::1', '2026-05-20 16:42:16', 'estudiantes.php'),
(2363, 2, '::1', '2026-05-20 16:42:16', 'estudiantes.php'),
(2364, 2, '::1', '2026-05-20 16:47:56', 'estudiantes.php'),
(2365, 2, '::1', '2026-05-20 16:47:56', 'estudiantes.php'),
(2366, 2, '::1', '2026-05-20 16:48:04', 'estudiantes.php'),
(2367, 2, '::1', '2026-05-20 16:48:04', 'estudiantes.php'),
(2368, 2, '::1', '2026-05-20 16:48:14', 'estudiantes.php'),
(2369, 2, '::1', '2026-05-20 16:48:14', 'estudiantes.php'),
(2370, 2, '::1', '2026-05-20 16:48:48', 'estudiantes.php'),
(2371, 2, '::1', '2026-05-20 16:48:48', 'estudiantes.php'),
(2372, 2, '::1', '2026-05-20 16:54:59', 'estudiantes.php'),
(2373, 2, '::1', '2026-05-20 16:54:59', 'estudiantes.php'),
(2374, 2, '::1', '2026-05-20 16:55:11', 'estudiantes.php'),
(2375, 2, '::1', '2026-05-20 16:55:11', 'estudiantes.php'),
(2376, 2, '::1', '2026-05-20 16:55:21', 'estudiantes.php'),
(2377, 2, '::1', '2026-05-20 16:55:21', 'estudiantes.php'),
(2378, 2, '::1', '2026-05-20 16:55:23', 'estudiantes.php'),
(2379, 2, '::1', '2026-05-20 16:55:23', 'estudiantes.php'),
(2380, 2, '::1', '2026-05-20 17:00:47', 'estudiantes.php'),
(2381, 2, '::1', '2026-05-20 17:00:47', 'estudiantes.php'),
(2382, 2, '::1', '2026-05-20 17:08:53', 'estudiantes.php'),
(2383, 2, '::1', '2026-05-20 17:08:54', 'estudiantes.php'),
(2384, 2, '::1', '2026-05-20 17:08:56', 'estudiantes.php'),
(2385, 2, '::1', '2026-05-20 17:08:56', 'estudiantes.php'),
(2386, 2, '::1', '2026-05-20 17:10:42', 'estudiantes.php'),
(2387, 2, '::1', '2026-05-20 17:10:42', 'estudiantes.php'),
(2388, 2, '::1', '2026-05-20 17:11:01', 'estudiantes.php'),
(2389, 2, '::1', '2026-05-20 17:11:01', 'estudiantes.php'),
(2390, 2, '::1', '2026-05-20 17:11:18', 'estudiantes.php'),
(2391, 2, '::1', '2026-05-20 17:11:18', 'estudiantes.php'),
(2392, 2, '::1', '2026-05-20 17:11:22', 'estudiantes.php'),
(2393, 2, '::1', '2026-05-20 17:11:22', 'estudiantes.php'),
(2394, 2, '::1', '2026-05-20 17:11:29', 'estudiantes.php'),
(2395, 2, '::1', '2026-05-20 17:11:29', 'estudiantes.php'),
(2396, 2, '::1', '2026-05-20 17:11:39', 'estudiantes.php'),
(2397, 2, '::1', '2026-05-20 17:11:39', 'estudiantes.php'),
(2398, 2, '::1', '2026-05-20 17:18:02', 'estudiantes.php'),
(2399, 2, '::1', '2026-05-20 17:18:02', 'estudiantes.php'),
(2400, 2, '::1', '2026-05-20 17:19:19', 'estudiantes.php'),
(2401, 2, '::1', '2026-05-20 17:19:19', 'estudiantes.php'),
(2402, 2, '::1', '2026-05-20 17:22:42', 'estudiantes.php'),
(2403, 2, '::1', '2026-05-20 17:22:42', 'estudiantes.php'),
(2404, 2, '::1', '2026-05-20 17:22:47', 'estudiantes.php'),
(2405, 2, '::1', '2026-05-20 17:22:47', 'estudiantes.php'),
(2406, 2, '::1', '2026-05-20 17:23:13', 'estudiantes.php'),
(2407, 2, '::1', '2026-05-20 17:23:14', 'estudiantes.php'),
(2408, 2, '::1', '2026-05-20 17:27:34', 'estudiantes.php'),
(2409, 2, '::1', '2026-05-20 17:27:34', 'estudiantes.php'),
(2410, 2, '::1', '2026-05-20 17:43:08', 'estudiantes.php'),
(2411, 2, '::1', '2026-05-20 17:43:08', 'estudiantes.php'),
(2412, 2, '::1', '2026-05-20 17:44:31', 'editar_accesos.php'),
(2413, 2, '::1', '2026-05-20 17:44:49', 'estudiantes.php'),
(2414, 2, '::1', '2026-05-20 17:44:49', 'estudiantes.php'),
(2415, 2, '::1', '2026-05-20 17:46:33', 'estudiantes.php'),
(2416, 2, '::1', '2026-05-20 17:46:34', 'estudiantes.php'),
(2417, 2, '::1', '2026-05-20 17:49:55', 'estudiantes.php'),
(2418, 2, '::1', '2026-05-20 17:49:56', 'estudiantes.php'),
(2419, 2, '::1', '2026-05-20 17:50:58', 'estudiantes.php'),
(2420, 2, '::1', '2026-05-20 17:50:59', 'estudiantes.php'),
(2421, 2, '::1', '2026-05-20 17:52:27', 'index.php'),
(2422, 2, '::1', '2026-05-20 17:52:29', 'estudiantes.php'),
(2423, 2, '::1', '2026-05-20 17:52:29', 'estudiantes.php'),
(2424, 2, '::1', '2026-05-20 18:00:28', 'estudiantes.php'),
(2425, 2, '::1', '2026-05-20 18:00:28', 'estudiantes.php'),
(2426, 2, '::1', '2026-05-20 18:02:55', 'estudiantes.php'),
(2427, 2, '::1', '2026-05-20 18:02:55', 'estudiantes.php'),
(2428, 2, '::1', '2026-05-20 18:09:49', 'consulta_notas.php'),
(2429, 2, '::1', '2026-05-20 18:10:40', 'consulta_notas.php'),
(2430, 2, '::1', '2026-05-25 12:59:57', 'index.php'),
(2431, 2, '::1', '2026-05-25 13:00:26', 'estudiantes.php'),
(2432, 2, '::1', '2026-05-25 13:00:27', 'estudiantes.php'),
(2433, 2, '::1', '2026-05-25 13:01:52', 'index.php'),
(2434, 2, '::1', '2026-05-25 13:05:16', 'estudiantes.php'),
(2435, 2, '::1', '2026-05-25 13:05:16', 'estudiantes.php'),
(2436, 2, '::1', '2026-05-25 13:10:11', 'estudiantes.php'),
(2437, 2, '::1', '2026-05-25 13:10:11', 'estudiantes.php'),
(2438, 2, '::1', '2026-05-25 13:10:18', 'consulta_notas.php'),
(2439, 2, '::1', '2026-05-25 13:11:18', 'estudiantes.php'),
(2440, 2, '::1', '2026-05-25 13:11:18', 'estudiantes.php'),
(2441, 2, '::1', '2026-05-25 13:12:06', 'consulta_notas.php'),
(2442, 2, '::1', '2026-05-25 13:12:43', 'consulta_notas.php'),
(2443, 2, '::1', '2026-05-25 13:36:10', 'index.php'),
(2444, 2, '::1', '2026-05-25 13:36:12', 'notas.php'),
(2445, 4, '::1', '2026-05-25 13:36:28', 'index.php'),
(2446, 4, '::1', '2026-05-25 13:36:29', 'notas.php'),
(2447, 2, '::1', '2026-05-25 15:35:32', 'index.php'),
(2448, 2, '::1', '2026-05-25 15:35:37', 'consulta_notas.php'),
(2449, 2, '::1', '2026-05-25 15:35:39', 'consulta_notas.php'),
(2450, 2, '::1', '2026-05-25 16:29:05', 'consulta_notas.php'),
(2451, 2, '::1', '2026-05-25 16:35:31', 'consulta_notas.php'),
(2452, 4, '::1', '2026-05-25 16:36:26', 'index.php'),
(2453, 4, '::1', '2026-05-25 16:36:27', 'notas.php'),
(2454, 2, '::1', '2026-05-25 16:41:56', 'index.php'),
(2455, 2, '::1', '2026-05-25 16:41:58', 'consulta_notas.php'),
(2456, 2, '::1', '2026-05-25 16:42:01', 'consulta_notas.php'),
(2457, 2, '::1', '2026-05-25 16:42:04', 'admin_notas_pendientes.php'),
(2458, 2, '::1', '2026-05-25 17:13:33', 'admin_notas_pendientes.php'),
(2459, 2, '::1', '2026-05-25 17:32:28', 'admin_notas_pendientes.php'),
(2460, 2, '::1', '2026-05-25 17:34:23', 'admin_notas_pendientes.php'),
(2461, 2, '::1', '2026-05-25 17:39:01', 'admin_notas_pendientes.php'),
(2462, 2, '::1', '2026-05-25 17:39:42', 'consulta_notas.php'),
(2463, 2, '::1', '2026-05-25 17:39:44', 'consulta_notas.php'),
(2464, 2, '::1', '2026-05-25 17:39:54', 'admin_notas_pendientes.php'),
(2465, 2, '::1', '2026-05-25 17:40:16', 'consulta_notas.php'),
(2466, 2, '::1', '2026-05-25 17:40:19', 'consulta_notas.php'),
(2467, 2, '::1', '2026-05-25 17:48:47', 'consulta_notas.php'),
(2468, 2, '::1', '2026-05-25 17:58:11', 'consulta_notas.php'),
(2469, 2, '::1', '2026-05-25 17:58:31', 'admin_notas_pendientes.php'),
(2470, 2, '::1', '2026-05-25 17:58:51', 'admin_notas_pendientes.php'),
(2471, 2, '::1', '2026-05-25 18:07:42', 'admin_notas_pendientes.php'),
(2472, 2, '::1', '2026-05-25 18:25:29', 'admin_notas_pendientes.php'),
(2473, 2, '::1', '2026-05-25 18:25:55', 'consulta_notas.php'),
(2474, 2, '::1', '2026-05-25 18:26:00', 'consulta_notas.php'),
(2475, 2, '::1', '2026-05-25 18:27:05', 'notas_pasadas.php'),
(2476, 2, '::1', '2026-05-25 18:27:10', 'notas_pasadas.php'),
(2477, 2, '::1', '2026-05-25 18:27:14', 'notas_pasadas.php'),
(2478, 2, '::1', '2026-05-25 18:27:15', 'admin_notas_pendientes.php'),
(2479, 2, '::1', '2026-05-25 18:27:16', 'consulta_notas.php'),
(2480, 2, '::1', '2026-05-25 18:27:23', 'consulta_notas.php'),
(2481, 2, '::1', '2026-05-25 18:27:55', 'gestion_seccion.php'),
(2482, 2, '::1', '2026-05-25 18:27:59', 'ver_seccion.php'),
(2483, 2, '::1', '2026-05-25 18:28:12', 'consulta_notas.php'),
(2484, 2, '::1', '2026-05-25 18:28:18', 'consulta_notas.php'),
(2485, 2, '::1', '2026-05-25 18:40:56', 'consulta_notas.php'),
(2486, 2, '::1', '2026-05-25 18:40:59', 'consulta_notas.php'),
(2487, 2, '::1', '2026-05-25 18:43:09', 'consulta_notas.php'),
(2488, 2, '::1', '2026-05-25 18:43:13', 'consulta_notas.php'),
(2489, 2, '::1', '2026-05-25 18:49:37', 'consulta_notas.php'),
(2490, 2, '::1', '2026-05-25 18:49:40', 'consulta_notas.php'),
(2491, 2, '::1', '2026-05-25 18:49:48', 'consulta_notas.php'),
(2492, 2, '::1', '2026-05-25 18:50:15', 'consulta_notas.php'),
(2493, 2, '::1', '2026-05-25 19:05:14', 'consulta_notas.php'),
(2494, 2, '::1', '2026-05-25 19:07:59', 'consulta_notas.php'),
(2495, 2, '::1', '2026-05-25 19:26:37', 'consulta_notas.php'),
(2496, 2, '::1', '2026-05-25 19:27:59', 'index.php'),
(2497, 2, '::1', '2026-05-25 19:28:01', 'consulta_notas.php'),
(2498, 2, '::1', '2026-05-25 19:28:09', 'admin_notas_pendientes.php'),
(2499, 2, '::1', '2026-05-25 19:28:12', 'preinscripciones.php'),
(2500, 2, '::1', '2026-05-25 19:28:16', 'agregar_estudiante.php'),
(2501, 2, '::1', '2026-05-25 19:28:16', 'agregar_estudiante.php'),
(2502, 2, '::1', '2026-05-25 19:28:17', 'gestion_seccion.php'),
(2503, 2, '::1', '2026-05-25 19:28:19', 'ver_seccion.php'),
(2504, 2, '::1', '2026-05-25 19:28:32', 'consulta_notas.php'),
(2505, 2, '::1', '2026-05-25 19:28:38', 'consulta_notas.php'),
(2506, 2, '::1', '2026-05-25 19:28:55', 'correccion_notas.php'),
(2507, 2, '::1', '2026-05-25 19:28:58', 'correccion_notas.php'),
(2508, 2, '::1', '2026-05-25 19:29:02', 'correccion_notas.php'),
(2509, 2, '::1', '2026-05-25 19:34:41', 'correccion_notas.php'),
(2510, 2, '::1', '2026-05-25 19:34:45', 'correccion_notas.php'),
(2511, 2, '::1', '2026-05-25 19:34:50', 'correccion_notas.php'),
(2512, 2, '::1', '2026-05-25 19:34:58', 'correccion_notas.php'),
(2513, 2, '::1', '2026-05-25 19:35:05', 'correccion_notas.php'),
(2514, 2, '::1', '2026-05-25 19:36:50', 'correccion_notas.php'),
(2515, 2, '::1', '2026-05-25 19:42:07', 'correccion_notas.php'),
(2516, 2, '::1', '2026-05-25 19:42:11', 'correccion_notas.php'),
(2517, 2, '::1', '2026-05-25 19:42:13', 'correccion_notas.php'),
(2518, 2, '::1', '2026-05-25 19:42:15', 'correccion_notas.php'),
(2519, 2, '::1', '2026-05-25 19:42:34', 'correccion_notas.php'),
(2520, 2, '::1', '2026-05-25 19:44:00', 'correccion_notas.php'),
(2521, 2, '::1', '2026-05-25 19:44:02', 'correccion_notas.php'),
(2522, 2, '::1', '2026-05-25 19:44:06', 'correccion_notas.php'),
(2523, 2, '::1', '2026-05-25 19:44:17', 'correccion_notas.php'),
(2524, 2, '::1', '2026-05-25 19:52:37', 'correccion_notas.php'),
(2525, 2, '::1', '2026-05-25 19:52:41', 'correccion_notas.php'),
(2526, 2, '::1', '2026-05-25 19:52:43', 'correccion_notas.php'),
(2527, 2, '::1', '2026-05-25 19:52:45', 'correccion_notas.php'),
(2528, 2, '::1', '2026-05-25 19:52:57', 'correccion_notas.php'),
(2529, 2, '::1', '2026-05-25 19:54:02', 'index.php'),
(2530, 2, '::1', '2026-05-25 19:54:12', 'notas_pasadas.php'),
(2531, 2, '::1', '2026-05-25 19:54:14', 'admin_notas_pendientes.php'),
(2532, 2, '::1', '2026-05-25 19:54:16', 'consulta_notas.php'),
(2533, 2, '::1', '2026-05-25 19:54:20', 'consulta_notas.php'),
(2534, 2, '::1', '2026-05-25 19:54:28', 'correccion_notas.php'),
(2535, 2, '::1', '2026-05-25 19:55:15', 'visita.php'),
(2536, 2, '::1', '2026-05-25 19:55:21', 'consulta_notas.php'),
(2537, 2, '::1', '2026-05-25 19:55:38', 'correccion_notas.php'),
(2538, 2, '::1', '2026-05-25 19:55:42', 'correccion_notas.php'),
(2539, 2, '::1', '2026-05-25 19:55:45', 'correccion_notas.php'),
(2540, 2, '::1', '2026-05-25 19:55:48', 'correccion_notas.php'),
(2541, 2, '::1', '2026-05-25 19:59:45', 'correccion_notas.php'),
(2542, 2, '::1', '2026-05-25 19:59:49', 'correccion_notas.php'),
(2543, 2, '::1', '2026-05-25 19:59:52', 'correccion_notas.php'),
(2544, 2, '::1', '2026-05-25 19:59:54', 'correccion_notas.php'),
(2545, 2, '::1', '2026-05-25 20:05:36', 'correccion_notas.php'),
(2546, 2, '::1', '2026-05-25 20:05:41', 'correccion_notas.php'),
(2547, 2, '::1', '2026-05-26 13:04:21', 'index.php'),
(2548, 2, '::1', '2026-05-26 13:04:25', 'admin_notas_pendientes.php'),
(2549, 2, '::1', '2026-05-26 13:04:29', 'consulta_notas.php'),
(2550, 2, '::1', '2026-05-26 13:04:33', 'consulta_notas.php'),
(2551, 2, '::1', '2026-05-26 13:04:47', 'notas_pasadas.php'),
(2552, 2, '::1', '2026-05-26 13:04:50', 'correccion_notas.php'),
(2553, 2, '::1', '2026-05-26 13:04:54', 'correccion_notas.php'),
(2554, 2, '::1', '2026-05-26 13:06:26', 'notas_pasadas.php');
INSERT INTO `visitas` (`id`, `id_usuario`, `ip`, `fecha_visita`, `web`) VALUES
(2555, 2, '::1', '2026-05-26 13:34:37', 'notas_pasadas.php'),
(2556, 2, '::1', '2026-05-26 13:35:22', 'notas_pasadas.php'),
(2557, 2, '::1', '2026-05-26 13:37:17', 'notas_pasadas.php'),
(2558, 2, '::1', '2026-05-26 13:37:49', 'notas_pasadas.php'),
(2559, 2, '::1', '2026-05-26 13:39:51', 'agregar_carrera.php'),
(2560, 2, '::1', '2026-05-26 13:40:00', 'estudiantes.php'),
(2561, 2, '::1', '2026-05-26 13:40:00', 'estudiantes.php'),
(2562, 2, '::1', '2026-05-26 13:40:25', 'consulta_notas.php'),
(2563, 2, '::1', '2026-05-26 13:40:41', 'notas_pasadas.php'),
(2564, 2, '::1', '2026-05-26 13:41:14', 'notas_pasadas.php'),
(2565, 2, '::1', '2026-05-26 13:41:16', 'notas_pasadas.php'),
(2566, 2, '::1', '2026-05-26 13:41:20', 'notas_pasadas.php'),
(2567, 2, '::1', '2026-05-26 13:41:23', 'notas_pasadas.php'),
(2568, 2, '::1', '2026-05-26 13:41:28', 'notas_pasadas.php'),
(2569, 2, '::1', '2026-05-26 13:41:42', 'notas_pasadas.php'),
(2570, 2, '::1', '2026-05-26 13:41:46', 'notas_pasadas.php'),
(2571, 2, '::1', '2026-05-26 13:53:16', 'notas_pasadas.php'),
(2572, 2, '::1', '2026-05-26 13:54:39', 'notas_pasadas.php'),
(2573, 2, '::1', '2026-05-26 13:56:32', 'notas_pasadas.php'),
(2574, 2, '::1', '2026-05-26 14:04:20', 'notas_pasadas.php'),
(2575, 2, '::1', '2026-05-26 14:04:23', 'notas_pasadas.php'),
(2576, 2, '::1', '2026-05-26 14:09:54', 'notas_pasadas.php'),
(2577, 2, '::1', '2026-05-26 14:09:55', 'notas_pasadas.php'),
(2578, 2, '::1', '2026-05-26 14:14:41', 'notas_pasadas.php'),
(2579, 2, '::1', '2026-05-26 14:14:42', 'notas_pasadas.php'),
(2580, 2, '::1', '2026-05-26 14:36:19', 'notas_pasadas.php'),
(2581, 2, '::1', '2026-05-26 14:36:21', 'notas_pasadas.php'),
(2582, 2, '::1', '2026-05-26 14:36:28', 'notas_pasadas.php'),
(2583, 2, '::1', '2026-05-26 14:36:31', 'notas_pasadas.php'),
(2584, 2, '::1', '2026-05-26 14:37:02', 'notas_pasadas.php'),
(2585, 2, '::1', '2026-05-26 14:37:18', 'notas_pasadas.php'),
(2586, 2, '::1', '2026-05-26 14:37:42', 'notas_pasadas.php'),
(2587, 2, '::1', '2026-05-26 14:37:47', 'notas_pasadas.php'),
(2588, 2, '::1', '2026-05-26 14:38:02', 'notas_pasadas.php'),
(2589, 2, '::1', '2026-05-26 14:42:14', 'notas_pasadas.php'),
(2590, 2, '::1', '2026-05-26 14:42:19', 'notas_pasadas.php'),
(2591, 2, '::1', '2026-05-26 14:42:21', 'index.php'),
(2592, 2, '::1', '2026-05-26 14:42:25', 'notas_pasadas.php'),
(2593, 2, '::1', '2026-05-26 14:42:31', 'notas_pasadas.php'),
(2594, 2, '::1', '2026-05-26 14:42:33', 'notas_pasadas.php'),
(2595, 2, '::1', '2026-05-26 14:42:40', 'notas_pasadas.php'),
(2596, 2, '::1', '2026-05-26 14:47:15', 'notas_pasadas.php'),
(2597, 2, '::1', '2026-05-26 14:47:17', 'notas_pasadas.php'),
(2598, 2, '::1', '2026-05-26 14:47:33', 'notas_pasadas.php'),
(2599, 2, '::1', '2026-05-26 14:47:43', 'notas_pasadas.php'),
(2600, 2, '::1', '2026-05-26 14:47:48', 'notas_pasadas.php'),
(2601, 2, '::1', '2026-05-26 14:48:02', 'notas_pasadas.php'),
(2602, 2, '::1', '2026-05-26 14:59:32', 'notas_pasadas.php'),
(2603, 2, '::1', '2026-05-26 15:01:26', 'notas_pasadas.php'),
(2604, 2, '::1', '2026-05-26 15:02:40', 'notas_pasadas.php'),
(2605, 2, '::1', '2026-05-26 15:13:32', 'notas_pasadas.php'),
(2606, 2, '::1', '2026-05-26 15:13:42', 'correccion_notas.php'),
(2607, 2, '::1', '2026-05-26 15:13:46', 'correccion_notas.php'),
(2608, 2, '::1', '2026-05-26 15:13:50', 'correccion_notas.php'),
(2609, 2, '::1', '2026-05-26 15:13:53', 'correccion_notas.php'),
(2610, 2, '::1', '2026-05-26 15:14:14', 'correccion_notas.php'),
(2611, 2, '::1', '2026-05-26 15:14:25', 'correccion_notas.php'),
(2612, 2, '::1', '2026-05-26 15:14:27', 'correccion_notas.php'),
(2613, 2, '::1', '2026-05-26 15:14:36', 'correccion_notas.php'),
(2614, 2, '::1', '2026-05-26 15:14:43', 'correccion_notas.php'),
(2615, 2, '::1', '2026-05-26 15:20:34', 'notas_pasadas.php'),
(2616, 2, '::1', '2026-05-26 15:20:38', 'notas_pasadas.php'),
(2617, 2, '::1', '2026-05-26 15:20:54', 'notas_pasadas.php'),
(2618, 2, '::1', '2026-05-26 15:21:31', 'notas_pasadas.php'),
(2619, 2, '::1', '2026-05-26 15:21:34', 'correccion_notas.php'),
(2620, 2, '::1', '2026-05-26 15:21:37', 'correccion_notas.php'),
(2621, 2, '::1', '2026-05-26 15:21:41', 'correccion_notas.php'),
(2622, 2, '::1', '2026-05-26 15:21:44', 'correccion_notas.php'),
(2623, 2, '::1', '2026-05-26 15:22:03', 'correccion_notas.php'),
(2624, 2, '::1', '2026-05-26 15:22:06', 'correccion_notas.php'),
(2625, 2, '::1', '2026-05-26 15:22:11', 'correccion_notas.php'),
(2626, 2, '::1', '2026-05-26 15:22:14', 'correccion_notas.php'),
(2627, 2, '::1', '2026-05-26 15:22:25', 'correccion_notas.php'),
(2628, 2, '::1', '2026-05-26 15:22:27', 'correccion_notas.php'),
(2629, 2, '::1', '2026-05-26 15:22:30', 'correccion_notas.php'),
(2630, 2, '::1', '2026-05-26 15:22:46', 'correccion_notas.php'),
(2631, 2, '::1', '2026-05-26 15:22:48', 'correccion_notas.php'),
(2632, 2, '::1', '2026-05-26 15:22:52', 'correccion_notas.php'),
(2633, 2, '::1', '2026-05-26 15:23:02', 'correccion_notas.php'),
(2634, 2, '::1', '2026-05-26 15:23:12', 'correccion_notas.php'),
(2635, 2, '::1', '2026-05-26 15:23:16', 'correccion_notas.php'),
(2636, 2, '::1', '2026-05-26 15:23:19', 'correccion_notas.php'),
(2637, 2, '::1', '2026-05-26 15:23:26', 'correccion_notas.php'),
(2638, 2, '::1', '2026-05-26 15:23:30', 'correccion_notas.php'),
(2639, 2, '::1', '2026-05-26 15:23:34', 'correccion_notas.php'),
(2640, 2, '::1', '2026-05-26 15:23:46', 'correccion_notas.php'),
(2641, 2, '::1', '2026-05-26 15:25:44', 'notas_pasadas.php'),
(2642, 2, '::1', '2026-05-26 15:25:51', 'notas_pasadas.php'),
(2643, 2, '::1', '2026-05-26 15:25:55', 'notas_pasadas.php'),
(2644, 2, '::1', '2026-05-26 15:26:09', 'notas_pasadas.php'),
(2645, 2, '::1', '2026-05-26 15:26:16', 'notas_pasadas.php'),
(2646, 2, '::1', '2026-05-26 15:26:43', 'notas_pasadas.php'),
(2647, 2, '::1', '2026-05-26 15:26:45', 'notas_pasadas.php'),
(2648, 2, '::1', '2026-05-26 15:26:47', 'notas_pasadas.php'),
(2649, 2, '::1', '2026-05-26 15:26:48', 'correccion_notas.php'),
(2650, 2, '::1', '2026-05-26 15:26:52', 'correccion_notas.php'),
(2651, 2, '::1', '2026-05-26 15:26:55', 'correccion_notas.php'),
(2652, 2, '::1', '2026-05-26 15:26:59', 'correccion_notas.php'),
(2653, 2, '::1', '2026-05-26 15:27:07', 'correccion_notas.php'),
(2654, 2, '::1', '2026-05-26 15:29:07', 'correccion_notas.php'),
(2655, 2, '::1', '2026-05-26 15:31:31', 'index.php'),
(2656, 2, '::1', '2026-05-26 15:31:34', 'correccion_notas.php'),
(2657, 2, '::1', '2026-05-26 15:31:37', 'correccion_notas.php'),
(2658, 2, '::1', '2026-05-26 15:31:40', 'correccion_notas.php'),
(2659, 2, '::1', '2026-05-26 15:31:45', 'correccion_notas.php'),
(2660, 2, '::1', '2026-05-26 15:31:54', 'correccion_notas.php'),
(2661, 2, '::1', '2026-05-26 15:32:40', 'correccion_notas.php'),
(2662, 2, '::1', '2026-05-26 15:32:42', 'correccion_notas.php'),
(2663, 2, '::1', '2026-05-26 15:32:44', 'correccion_notas.php'),
(2664, 2, '::1', '2026-05-26 15:32:47', 'correccion_notas.php'),
(2665, 2, '::1', '2026-05-26 15:32:59', 'correccion_notas.php'),
(2666, 2, '::1', '2026-05-26 15:42:24', 'correccion_notas.php'),
(2667, 2, '::1', '2026-05-26 15:42:26', 'correccion_notas.php'),
(2668, 2, '::1', '2026-05-26 15:42:29', 'correccion_notas.php'),
(2669, 2, '::1', '2026-05-26 15:42:33', 'correccion_notas.php'),
(2670, 2, '::1', '2026-05-26 15:42:50', 'correccion_notas.php'),
(2671, 2, '::1', '2026-05-26 15:42:59', 'correccion_notas.php'),
(2672, 2, '::1', '2026-05-26 15:43:08', 'correccion_notas.php'),
(2673, 2, '::1', '2026-05-26 15:43:11', 'correccion_notas.php'),
(2674, 2, '::1', '2026-05-26 15:43:51', 'correccion_notas.php'),
(2675, 2, '::1', '2026-05-26 15:48:38', 'correccion_notas.php'),
(2676, 2, '::1', '2026-05-26 15:48:40', 'correccion_notas.php'),
(2677, 2, '::1', '2026-05-26 15:48:42', 'correccion_notas.php'),
(2678, 2, '::1', '2026-05-26 15:48:44', 'correccion_notas.php'),
(2679, 2, '::1', '2026-05-26 15:48:47', 'correccion_notas.php'),
(2680, 2, '::1', '2026-05-26 15:49:00', 'correccion_notas.php'),
(2681, 2, '::1', '2026-05-26 15:53:23', 'correccion_notas.php'),
(2682, 2, '::1', '2026-05-26 15:53:25', 'correccion_notas.php'),
(2683, 2, '::1', '2026-05-26 15:53:28', 'correccion_notas.php'),
(2684, 2, '::1', '2026-05-26 15:57:37', 'correccion_notas.php'),
(2685, 2, '::1', '2026-05-26 15:57:45', 'correccion_notas.php'),
(2686, 2, '::1', '2026-05-26 15:57:47', 'correccion_notas.php'),
(2687, 2, '::1', '2026-05-26 15:57:50', 'correccion_notas.php'),
(2688, 2, '::1', '2026-05-26 15:58:05', 'correccion_notas.php'),
(2689, 2, '::1', '2026-05-26 15:58:19', 'correccion_notas.php'),
(2690, 2, '::1', '2026-05-26 16:07:21', 'correccion_notas.php'),
(2691, 2, '::1', '2026-05-26 16:07:24', 'correccion_notas.php'),
(2692, 2, '::1', '2026-05-26 16:07:27', 'correccion_notas.php'),
(2693, 2, '::1', '2026-05-26 16:07:29', 'correccion_notas.php'),
(2694, 2, '::1', '2026-05-26 16:07:56', 'correccion_notas.php'),
(2695, 2, '::1', '2026-05-26 16:14:32', 'correccion_notas.php'),
(2696, 2, '::1', '2026-05-26 16:14:34', 'correccion_notas.php'),
(2697, 2, '::1', '2026-05-26 16:14:37', 'correccion_notas.php'),
(2698, 2, '::1', '2026-05-26 16:14:39', 'correccion_notas.php'),
(2699, 2, '::1', '2026-05-26 16:14:50', 'correccion_notas.php'),
(2700, 2, '::1', '2026-05-26 16:14:52', 'correccion_notas.php'),
(2701, 2, '::1', '2026-05-26 16:14:55', 'correccion_notas.php'),
(2702, 2, '::1', '2026-05-26 16:14:57', 'correccion_notas.php'),
(2703, 2, '::1', '2026-05-26 16:15:16', 'correccion_notas.php'),
(2704, 2, '::1', '2026-05-26 16:23:54', 'correccion_notas.php'),
(2705, 2, '::1', '2026-05-26 16:23:57', 'correccion_notas.php'),
(2706, 2, '::1', '2026-05-26 16:23:58', 'correccion_notas.php'),
(2707, 2, '::1', '2026-05-26 16:24:01', 'correccion_notas.php'),
(2708, 2, '::1', '2026-05-26 16:24:14', 'correccion_notas.php'),
(2709, 2, '::1', '2026-05-26 16:26:22', 'correccion_notas.php'),
(2710, 2, '::1', '2026-05-26 16:26:35', 'correccion_notas.php'),
(2711, 2, '::1', '2026-05-26 16:26:36', 'correccion_notas.php'),
(2712, 2, '::1', '2026-05-26 16:26:40', 'correccion_notas.php'),
(2713, 2, '::1', '2026-05-26 16:26:42', 'correccion_notas.php'),
(2714, 2, '::1', '2026-05-26 16:26:45', 'correccion_notas.php'),
(2715, 2, '::1', '2026-05-26 16:27:08', 'correccion_notas.php'),
(2716, 2, '::1', '2026-05-26 16:27:42', 'correccion_notas.php'),
(2717, 2, '::1', '2026-05-26 16:27:44', 'correccion_notas.php'),
(2718, 2, '::1', '2026-05-26 16:27:53', 'correccion_notas.php'),
(2719, 2, '::1', '2026-05-26 16:28:10', 'correccion_notas.php'),
(2720, 2, '::1', '2026-05-26 16:28:16', 'correccion_notas.php'),
(2721, 2, '::1', '2026-05-26 16:28:18', 'correccion_notas.php'),
(2722, 2, '::1', '2026-05-26 16:28:21', 'correccion_notas.php'),
(2723, 2, '::1', '2026-05-26 16:28:23', 'correccion_notas.php'),
(2724, 2, '::1', '2026-05-26 16:28:37', 'correccion_notas.php'),
(2725, 2, '::1', '2026-05-26 16:39:03', 'correccion_notas.php'),
(2726, 2, '::1', '2026-05-26 16:39:06', 'correccion_notas.php'),
(2727, 2, '::1', '2026-05-26 16:39:13', 'correccion_notas.php'),
(2728, 2, '::1', '2026-05-26 16:39:19', 'correccion_notas.php'),
(2729, 2, '::1', '2026-05-26 16:39:46', 'correccion_notas.php'),
(2730, 2, '::1', '2026-05-26 16:40:06', 'correccion_notas.php'),
(2731, 2, '::1', '2026-05-26 16:40:34', 'index.php'),
(2732, 2, '::1', '2026-05-26 16:42:19', 'admin_notas_pendientes.php'),
(2733, 2, '::1', '2026-05-26 16:42:32', 'gestion_seccion.php'),
(2734, 2, '::1', '2026-05-26 16:42:34', 'ver_seccion.php'),
(2735, 2, '::1', '2026-05-26 16:42:37', 'horario_seccion.php'),
(2736, 1, '::1', '2026-05-26 16:46:34', 'index.php'),
(2737, 1, '::1', '2026-05-26 16:46:37', 'notas.php'),
(2738, 1, '::1', '2026-05-26 17:31:02', 'notas.php'),
(2739, 1, '::1', '2026-05-26 17:41:20', 'notas.php'),
(2740, 1, '::1', '2026-05-26 17:45:09', 'notas.php'),
(2741, 1, '::1', '2026-05-26 17:48:47', 'notas.php'),
(2742, 1, '::1', '2026-05-26 17:49:20', 'notas.php'),
(2743, 1, '::1', '2026-05-26 17:58:46', 'notas.php'),
(2744, 1, '::1', '2026-05-26 18:10:28', 'notas.php'),
(2745, 1, '::1', '2026-05-26 18:11:38', 'notas.php'),
(2746, 1, '::1', '2026-05-26 18:12:59', 'notas.php'),
(2747, 1, '::1', '2026-05-26 18:16:20', 'notas.php'),
(2748, 1, '::1', '2026-05-26 18:16:21', 'notas.php'),
(2749, 1, '::1', '2026-05-26 18:16:49', 'notas.php'),
(2750, 1, '::1', '2026-05-26 18:17:09', 'notas.php'),
(2751, 1, '::1', '2026-05-26 18:25:53', 'notas.php'),
(2752, 1, '::1', '2026-05-26 18:25:56', 'notas.php'),
(2753, 1, '::1', '2026-05-26 18:26:11', 'notas.php'),
(2754, 1, '::1', '2026-05-26 18:27:14', 'notas.php'),
(2755, 1, '::1', '2026-05-26 18:28:41', 'notas.php'),
(2756, 1, '::1', '2026-05-26 18:33:16', 'notas.php'),
(2757, 1, '::1', '2026-05-26 19:35:59', 'notas.php'),
(2758, 2, '::1', '2026-05-27 12:13:19', 'index.php'),
(2759, 2, '::1', '2026-05-27 12:13:50', 'correccion_notas.php'),
(2760, 2, '::1', '2026-05-27 12:13:57', 'correccion_notas.php'),
(2761, 2, '::1', '2026-05-27 12:17:37', 'notas_pasadas.php'),
(2762, 2, '::1', '2026-05-27 12:17:44', 'notas_pasadas.php'),
(2763, 1, '::1', '2026-05-27 12:19:37', 'index.php'),
(2764, 1, '::1', '2026-05-27 12:19:41', 'mi_horario.php'),
(2765, 1, '::1', '2026-05-27 12:20:44', 'index.php'),
(2766, 1, '::1', '2026-05-27 12:20:46', 'notas.php'),
(2767, 1, '::1', '2026-05-27 12:22:35', 'notas.php'),
(2768, 2, '::1', '2026-05-27 12:22:48', 'index.php'),
(2769, 2, '::1', '2026-05-27 12:23:05', 'estudiantes.php'),
(2770, 2, '::1', '2026-05-27 12:23:06', 'estudiantes.php'),
(2771, 2, '::1', '2026-05-27 12:24:41', 'consulta_notas.php'),
(2772, 2, '::1', '2026-05-27 12:25:02', 'estudiantes.php'),
(2773, 2, '::1', '2026-05-27 12:25:02', 'estudiantes.php'),
(2774, 2, '::1', '2026-05-27 12:25:39', 'index.php'),
(2775, 2, '::1', '2026-05-27 12:25:52', 'secretaria.php'),
(2776, 2, '::1', '2026-05-27 15:11:50', 'index.php'),
(2777, 2, '::1', '2026-05-27 15:11:57', 'index.php'),
(2778, 2, '::1', '2026-05-27 15:12:03', 'index.php'),
(2779, 2, '::1', '2026-05-27 15:12:05', 'notas.php'),
(2780, 2, '::1', '2026-05-27 15:12:10', 'index.php'),
(2781, 2, '::1', '2026-05-27 15:12:13', 'mi_horario.php'),
(2782, 2, '::1', '2026-05-27 15:12:14', 'index.php'),
(2783, 5, '::1', '2026-05-27 15:12:22', 'index.php'),
(2784, 5, '::1', '2026-05-27 15:12:24', 'mi_horario.php'),
(2785, 5, '::1', '2026-05-27 15:12:25', 'index.php'),
(2786, 2, '::1', '2026-05-27 15:12:48', 'index.php'),
(2787, 2, '::1', '2026-05-27 15:12:50', 'gestion_seccion.php'),
(2788, 2, '::1', '2026-05-27 15:12:59', 'ver_seccion.php'),
(2789, 2630, '::1', '2026-05-27 15:13:56', 'index.php'),
(2790, 2630, '::1', '2026-05-27 15:14:00', 'index.php'),
(2791, 2630, '::1', '2026-05-27 15:14:02', 'index.php'),
(2792, 2630, '::1', '2026-05-27 15:14:02', 'index.php'),
(2793, 2630, '::1', '2026-05-27 15:14:15', 'mi_horario.php'),
(2794, 2630, '::1', '2026-05-27 15:14:49', 'index.php'),
(2795, 2630, '::1', '2026-05-27 15:14:51', 'mis_secciones.php'),
(2796, 2630, '::1', '2026-05-27 15:14:53', 'mis_constancias.php'),
(2797, 2630, '::1', '2026-05-27 15:14:54', 'index.php'),
(2798, 2630, '::1', '2026-05-27 15:14:56', 'mi_pensum.php'),
(2799, 2630, '::1', '2026-05-27 15:15:02', 'index.php'),
(2800, 2630, '::1', '2026-05-27 15:15:07', 'mi_historial.php'),
(2801, 2630, '::1', '2026-05-27 15:15:34', 'index.php'),
(2802, 2630, '::1', '2026-05-27 15:15:44', 'mis_constancias.php'),
(2803, 2630, '::1', '2026-05-27 15:16:50', 'index.php'),
(2804, 2, '::1', '2026-05-27 15:17:08', 'index.php'),
(2805, 2, '::1', '2026-05-27 15:17:09', 'asignacion_voceros.php'),
(2806, 2, '::1', '2026-05-27 15:17:12', 'asignacion_voceros.php'),
(2807, 2, '::1', '2026-05-27 15:17:16', 'asignacion_voceros.php'),
(2808, 2, '::1', '2026-05-27 15:17:17', 'asignacion_voceros.php'),
(2809, 2630, '::1', '2026-05-27 15:17:32', 'index.php'),
(2810, 2630, '::1', '2026-05-27 15:17:37', 'vocero.php'),
(2811, 2630, '::1', '2026-05-27 15:17:41', 'vocero.php'),
(2812, 2630, '::1', '2026-05-27 15:17:45', 'vocero.php'),
(2813, 2630, '::1', '2026-05-27 15:17:55', 'vocero.php'),
(2814, 2630, '::1', '2026-05-27 15:18:03', 'vocero.php'),
(2815, 2, '::1', '2026-05-27 15:20:08', 'index.php'),
(2816, 2, '::1', '2026-05-27 15:26:20', 'index.php'),
(2817, 2, '::1', '2026-05-27 15:26:22', 'estudiantes.php'),
(2818, 2, '::1', '2026-05-27 15:26:22', 'estudiantes.php'),
(2819, 2, '::1', '2026-05-27 15:27:06', 'consulta_notas.php'),
(2820, 2, '::1', '2026-05-27 15:29:42', 'index.php'),
(2821, 2, '::1', '2026-05-27 15:29:43', 'preinscripciones.php'),
(2822, 2, '::1', '2026-05-27 15:30:05', 'preinscripcion_detalle.php'),
(2823, 2, '::1', '2026-05-27 15:36:01', 'preinscripcion_detalle.php'),
(2824, 2, '::1', '2026-05-27 15:39:32', 'preinscripciones.php'),
(2825, 2, '::1', '2026-05-27 15:39:47', 'preinscripciones.php'),
(2826, 2, '::1', '2026-05-27 15:41:01', 'estudiantes.php'),
(2827, 2, '::1', '2026-05-27 15:41:02', 'estudiantes.php'),
(2828, 2, '::1', '2026-05-27 15:45:33', 'index.php'),
(2829, 2, '::1', '2026-05-27 15:47:39', 'index.php'),
(2830, 2, '::1', '2026-05-27 15:47:43', 'preinscripciones.php'),
(2831, 2, '::1', '2026-05-27 15:47:47', 'preinscripcion_detalle.php'),
(2832, 2, '::1', '2026-05-27 15:48:24', 'preinscripcion_detalle.php'),
(2833, 2, '::1', '2026-05-27 15:48:33', 'gestion_seccion.php'),
(2834, 2, '::1', '2026-05-28 12:38:06', 'index.php'),
(2835, 2, '::1', '2026-05-28 12:38:08', 'consulta_notas.php'),
(2836, 2, '::1', '2026-05-28 12:38:13', 'consulta_notas.php'),
(2837, 2630, '::1', '2026-05-28 12:39:13', 'index.php'),
(2838, 2630, '::1', '2026-05-28 12:39:30', 'mi_historial.php'),
(2839, 2630, '::1', '2026-05-28 12:52:13', 'mi_historial.php'),
(2840, 2630, '::1', '2026-05-28 13:00:22', 'mi_historial.php'),
(2841, 2630, '::1', '2026-05-28 13:06:26', 'mi_historial.php'),
(2842, 2630, '::1', '2026-05-28 13:06:32', 'index.php'),
(2843, 2630, '::1', '2026-05-28 13:06:32', 'mis_constancias.php'),
(2844, 2630, '::1', '2026-05-28 13:06:33', 'index.php'),
(2845, 2630, '::1', '2026-05-28 13:06:49', 'mi_pensum.php'),
(2846, 2630, '::1', '2026-05-28 13:06:58', 'index.php'),
(2847, 2630, '::1', '2026-05-28 13:07:06', 'mi_historial.php'),
(2848, 2630, '::1', '2026-05-28 13:07:16', 'mensajeria_estudiantes.php'),
(2849, 2, '::1', '2026-05-28 13:19:44', 'index.php'),
(2850, 2, '::1', '2026-05-28 13:20:14', 'index.php'),
(2851, 2, '::1', '2026-05-28 13:20:24', 'gestion_seccion.php'),
(2852, 2, '::1', '2026-05-28 13:20:26', 'ver_seccion.php'),
(2853, 1, '::1', '2026-05-28 13:22:32', 'index.php'),
(2854, 1, '::1', '2026-05-28 13:22:35', 'notas.php'),
(2855, 2, '::1', '2026-05-29 12:53:36', 'index.php'),
(2856, 2, '::1', '2026-05-29 12:53:39', 'secretaria.php'),
(2857, 2, '::1', '2026-05-29 12:57:39', 'secretaria.php'),
(2858, 2, '::1', '2026-05-29 13:00:00', 'secretaria.php'),
(2859, 1, '::1', '2026-05-29 13:00:27', 'index.php'),
(2860, 1, '::1', '2026-05-29 13:00:33', 'notas.php'),
(2861, 1, '::1', '2026-05-29 13:23:23', 'notas.php'),
(2862, 1, '::1', '2026-05-29 13:26:13', 'notas.php'),
(2863, 1, '::1', '2026-05-29 13:26:38', 'notas.php'),
(2864, 2, '::1', '2026-05-29 13:27:39', 'index.php'),
(2865, 2, '::1', '2026-05-29 13:27:43', 'admin_notas_pendientes.php'),
(2866, 1, '::1', '2026-05-29 13:28:39', 'index.php'),
(2867, 1, '::1', '2026-05-29 13:28:41', 'notas.php'),
(2868, 2, '::1', '2026-05-29 13:29:19', 'index.php'),
(2869, 2, '::1', '2026-05-29 13:29:20', 'admin_notas_pendientes.php'),
(2870, 1, '::1', '2026-05-29 13:30:00', 'index.php'),
(2871, 1, '::1', '2026-05-29 13:30:02', 'notas.php'),
(2872, 1, '::1', '2026-05-29 14:09:53', 'notas.php'),
(2873, 1, '::1', '2026-05-29 14:12:13', 'notas.php'),
(2874, 1, '::1', '2026-05-29 16:21:16', 'notas.php'),
(2875, 1, '::1', '2026-05-29 16:24:07', 'notas.php'),
(2876, 1, '::1', '2026-05-29 16:24:18', 'notas.php'),
(2877, 1, '::1', '2026-05-29 16:33:18', 'notas.php'),
(2878, 1, '::1', '2026-05-29 16:33:31', 'notas.php'),
(2879, 1, '::1', '2026-05-29 16:41:29', 'notas.php'),
(2880, 1, '::1', '2026-05-29 16:50:13', 'notas.php'),
(2881, 1, '::1', '2026-05-29 16:51:26', 'notas.php'),
(2882, 1, '::1', '2026-05-29 17:00:08', 'notas.php'),
(2883, 1, '::1', '2026-05-29 17:03:27', 'notas.php'),
(2884, 1, '::1', '2026-05-29 17:03:56', 'notas.php'),
(2885, 5, '::1', '2026-05-29 17:04:08', 'index.php'),
(2886, 5, '::1', '2026-05-29 17:04:12', 'mis_constancias.php'),
(2887, 1, '::1', '2026-05-29 17:15:54', 'index.php'),
(2888, 1, '::1', '2026-05-29 17:15:57', 'notas.php'),
(2889, 1, '::1', '2026-05-29 17:28:55', 'notas.php'),
(2890, 1, '::1', '2026-05-29 17:37:55', 'notas.php'),
(2891, 2, '::1', '2026-06-01 12:59:27', 'index.php'),
(2892, 2, '::1', '2026-06-01 13:03:19', 'gestion_seccion.php'),
(2893, 2, '::1', '2026-06-01 13:18:30', 'inscripcion_materias.php'),
(2894, 2, '::1', '2026-06-01 13:18:33', 'inscripcion_materias.php'),
(2895, 2, '::1', '2026-06-01 13:20:09', 'gestion_seccion.php'),
(2896, 2, '::1', '2026-06-01 15:16:13', 'gestion_seccion.php'),
(2897, 2, '::1', '2026-06-01 15:16:20', 'ver_seccion.php'),
(2898, 2, '::1', '2026-06-01 15:16:22', 'avance_trayectos.php'),
(2899, 2633, '::1', '2026-06-01 15:17:51', 'index.php'),
(2900, 2633, '::1', '2026-06-01 15:18:13', 'mi_horario.php'),
(2901, 2633, '::1', '2026-06-01 15:18:18', 'index.php'),
(2902, 2633, '::1', '2026-06-01 15:18:19', 'mis_secciones.php'),
(2903, 2633, '::1', '2026-06-01 15:18:20', 'index.php'),
(2904, 2633, '::1', '2026-06-01 15:18:23', 'mi_pensum.php'),
(2905, 2633, '::1', '2026-06-01 15:18:25', 'index.php'),
(2906, 2633, '::1', '2026-06-01 15:18:26', 'mi_historial.php'),
(2907, 2633, '::1', '2026-06-01 15:18:44', 'mi_horario.php'),
(2908, 1, '::1', '2026-06-01 15:19:05', 'index.php'),
(2909, 1, '::1', '2026-06-01 15:19:11', 'notas.php'),
(2910, 2, '::1', '2026-06-01 15:19:38', 'index.php'),
(2911, 2, '::1', '2026-06-01 15:19:55', 'admin_notas_pendientes.php'),
(2912, 2, '::1', '2026-06-01 15:20:00', 'admin_notas_pendientes.php'),
(2913, 2, '::1', '2026-06-02 13:17:25', 'index.php'),
(2914, 2, '::1', '2026-06-02 13:17:58', 'admin_notas_pendientes.php'),
(2915, 2, '::1', '2026-06-02 13:25:02', 'admin_notas_pendientes.php'),
(2916, 1, '::1', '2026-06-02 13:27:27', 'index.php'),
(2917, 1, '::1', '2026-06-02 13:27:29', 'notas.php'),
(2918, 2, '::1', '2026-06-02 13:28:41', 'index.php'),
(2919, 2, '::1', '2026-06-02 13:28:53', 'consulta_notas.php'),
(2920, 2, '::1', '2026-06-02 13:28:57', 'consulta_notas.php'),
(2921, 2, '::1', '2026-06-02 13:29:11', 'admin_notas_pendientes.php'),
(2922, 2, '::1', '2026-06-02 13:29:57', 'admin_notas_pendientes.php'),
(2923, 2, '::1', '2026-06-03 13:04:55', 'index.php'),
(2924, 2, '::1', '2026-06-03 13:05:02', 'admin_notas_pendientes.php'),
(2925, 2, '::1', '2026-06-03 13:08:36', 'admin_notas_pendientes.php'),
(2926, 2, '::1', '2026-06-03 13:08:45', 'admin_notas_pendientes.php'),
(2927, 2, '::1', '2026-06-03 13:18:26', 'index.php'),
(2928, 2, '::1', '2026-06-03 13:18:30', 'admin_notas_pendientes.php'),
(2929, 2, '::1', '2026-06-03 13:19:01', 'admin_notas_pendientes.php'),
(2930, 2, '::1', '2026-06-03 13:19:09', 'admin_notas_pendientes.php'),
(2931, 2, '::1', '2026-06-03 13:52:14', 'admin_notas_pendientes.php'),
(2932, 2, '::1', '2026-06-03 13:52:53', 'admin_notas_pendientes.php'),
(2933, 2, '::1', '2026-06-03 13:53:30', 'admin_notas_pendientes.php'),
(2934, 2, '::1', '2026-06-03 13:58:57', 'admin_notas_pendientes.php'),
(2935, 2, '::1', '2026-06-03 13:58:57', 'admin_notas_pendientes.php'),
(2936, 2, '::1', '2026-06-03 13:59:12', 'admin_notas_pendientes.php'),
(2937, 2, '::1', '2026-06-03 13:59:14', 'admin_notas_pendientes.php'),
(2938, 2, '::1', '2026-06-03 15:05:14', 'admin_notas_pendientes.php'),
(2939, 2, '::1', '2026-06-03 15:05:16', 'admin_notas_pendientes.php'),
(2940, 2, '::1', '2026-06-03 15:05:18', 'admin_notas_pendientes.php'),
(2941, 2, '::1', '2026-06-03 15:05:24', 'admin_notas_pendientes.php'),
(2942, 2, '::1', '2026-06-03 15:05:26', 'admin_notas_pendientes.php'),
(2943, 2, '::1', '2026-06-03 15:05:37', 'admin_notas_pendientes.php'),
(2944, 2, '::1', '2026-06-03 15:05:38', 'admin_notas_pendientes.php'),
(2945, 2, '::1', '2026-06-03 15:05:39', 'admin_notas_pendientes.php'),
(2946, 2, '::1', '2026-06-03 15:05:39', 'admin_notas_pendientes.php'),
(2947, 2, '::1', '2026-06-03 15:05:41', 'admin_notas_pendientes.php'),
(2948, 2, '::1', '2026-06-03 15:05:47', 'index.php'),
(2949, 2, '::1', '2026-06-03 15:05:48', 'admin_notas_pendientes.php'),
(2950, 2, '::1', '2026-06-03 15:05:50', 'admin_notas_pendientes.php'),
(2951, 2, '::1', '2026-06-03 15:06:08', 'admin_notas_pendientes.php'),
(2952, 2, '::1', '2026-06-03 15:06:11', 'admin_notas_pendientes.php'),
(2953, 2, '::1', '2026-06-03 15:06:11', 'admin_notas_pendientes.php'),
(2954, 2, '::1', '2026-06-03 15:06:12', 'admin_notas_pendientes.php'),
(2955, 2, '::1', '2026-06-03 15:06:13', 'admin_notas_pendientes.php'),
(2956, 2, '::1', '2026-06-03 15:06:13', 'admin_notas_pendientes.php'),
(2957, 2, '::1', '2026-06-03 15:07:58', 'admin_notas_pendientes.php'),
(2958, 2, '::1', '2026-06-03 15:08:00', 'admin_notas_pendientes.php'),
(2959, 1, '::1', '2026-06-03 15:08:33', 'index.php'),
(2960, 1, '::1', '2026-06-03 15:08:34', 'notas.php'),
(2961, 2633, '::1', '2026-06-03 15:09:16', 'index.php'),
(2962, 2633, '::1', '2026-06-03 15:09:25', 'mi_historial.php'),
(2963, 1, '::1', '2026-06-03 15:10:26', 'index.php'),
(2964, 1, '::1', '2026-06-03 15:10:28', 'notas.php'),
(2965, 2, '::1', '2026-06-03 15:13:31', 'index.php'),
(2966, 2, '::1', '2026-06-03 15:13:32', 'admin_notas_pendientes.php'),
(2967, 2, '::1', '2026-06-03 15:14:42', 'admin_notas_pendientes.php'),
(2968, 1, '::1', '2026-06-03 15:15:07', 'index.php'),
(2969, 1, '::1', '2026-06-03 15:15:09', 'notas.php'),
(2970, 2633, '::1', '2026-06-03 15:15:34', 'index.php'),
(2971, 2633, '::1', '2026-06-03 15:15:45', 'mi_historial.php'),
(2972, 1, '::1', '2026-06-03 15:17:36', 'index.php'),
(2973, 1, '::1', '2026-06-03 15:17:38', 'mensajeria.php'),
(2974, 2, '::1', '2026-06-03 15:32:53', 'index.php'),
(2975, 1, '::1', '2026-06-03 15:33:08', 'index.php'),
(2976, 1, '::1', '2026-06-03 15:33:10', 'notas.php'),
(2977, 2, '::1', '2026-06-03 15:35:54', 'index.php'),
(2978, 2, '::1', '2026-06-03 15:35:56', 'admin_notas_pendientes.php'),
(2979, 2, '::1', '2026-06-03 15:37:32', 'index.php'),
(2980, 2, '::1', '2026-06-03 15:37:34', 'admin_notas_pendientes.php'),
(2981, 2, '::1', '2026-06-03 15:37:42', 'consulta_notas.php'),
(2982, 2, '::1', '2026-06-03 15:37:48', 'gestion_seccion.php'),
(2983, 2, '::1', '2026-06-03 15:37:50', 'ver_seccion.php'),
(2984, 2, '::1', '2026-06-03 15:37:56', 'consulta_notas.php'),
(2985, 2, '::1', '2026-06-03 15:37:59', 'consulta_notas.php'),
(2986, 2, '::1', '2026-06-03 15:38:29', 'notas_pasadas.php'),
(2987, 2, '::1', '2026-06-03 15:38:56', 'gestion_seccion.php'),
(2988, 2, '::1', '2026-06-03 15:38:58', 'ver_seccion.php'),
(2989, 2, '::1', '2026-06-03 15:39:30', 'avance_trayectos.php'),
(2990, 2, '::1', '2026-06-03 15:39:54', 'ver_seccion.php'),
(2991, 2, '::1', '2026-06-03 15:40:09', 'gestion_seccion.php'),
(2992, 1, '::1', '2026-06-03 15:41:46', 'index.php'),
(2993, 1, '::1', '2026-06-03 15:41:47', 'mensajeria.php'),
(2994, 1, '::1', '2026-06-03 15:41:49', 'index.php'),
(2995, 1, '::1', '2026-06-03 15:42:09', 'mi_horario.php'),
(2996, 2, '::1', '2026-06-03 15:42:30', 'index.php'),
(2997, 2, '::1', '2026-06-03 15:42:36', 'asignacion_cursos.php'),
(2998, 2, '::1', '2026-06-03 15:42:45', 'index.php'),
(2999, 2, '::1', '2026-06-03 15:42:47', 'asignacion_cursos.php'),
(3000, 2, '::1', '2026-06-03 15:42:51', 'index.php'),
(3001, 2, '::1', '2026-06-03 15:43:01', 'asignacion_cursos.php'),
(3002, 2, '::1', '2026-06-03 15:43:37', 'asignacion_cursos.php'),
(3003, 2, '::1', '2026-06-03 15:43:57', 'index.php'),
(3004, 2, '::1', '2026-06-03 15:44:00', 'asignacion_cursos.php'),
(3005, 2, '::1', '2026-06-03 15:44:15', 'asignar_secciones.php'),
(3006, 2, '::1', '2026-06-03 15:44:25', 'asignar_secciones.php'),
(3007, 2, '::1', '2026-06-03 15:44:31', 'asignar_secciones.php'),
(3008, 2, '::1', '2026-06-03 15:44:44', 'asignar_secciones.php'),
(3009, 2, '::1', '2026-06-03 15:44:53', 'asignar_secciones.php'),
(3010, 2, '::1', '2026-06-03 15:44:58', 'asignar_secciones.php'),
(3011, 2, '::1', '2026-06-03 15:45:13', 'asignar_secciones.php'),
(3012, 2, '::1', '2026-06-03 15:45:21', 'asignar_secciones.php'),
(3013, 2, '::1', '2026-06-03 15:45:32', 'asignar_secciones.php'),
(3014, 2, '::1', '2026-06-03 15:46:01', 'asignar_secciones.php'),
(3015, 2, '::1', '2026-06-03 15:46:04', 'asignar_secciones.php'),
(3016, 2, '::1', '2026-06-03 15:49:45', 'index.php'),
(3017, 2, '::1', '2026-06-03 15:50:33', 'index.php'),
(3018, 2, '::1', '2026-06-03 15:50:38', 'asignar_secciones.php'),
(3019, 2, '::1', '2026-06-03 15:50:43', 'asignar_secciones.php'),
(3020, 2, '::1', '2026-06-03 15:50:45', 'asignar_secciones.php'),
(3021, 2, '::1', '2026-06-03 15:50:55', 'asignar_secciones.php'),
(3022, 2, '::1', '2026-06-03 15:50:59', 'asignar_secciones.php'),
(3023, 2, '::1', '2026-06-03 15:56:50', 'asignar_secciones.php'),
(3024, 2, '::1', '2026-06-03 15:56:52', 'asignar_secciones.php'),
(3025, 2, '::1', '2026-06-03 15:58:15', 'asignar_secciones.php'),
(3026, 2, '::1', '2026-06-03 16:05:01', 'index.php'),
(3027, 2, '::1', '2026-06-03 16:05:04', 'admin_notas_pendientes.php'),
(3028, 2, '::1', '2026-06-03 16:05:06', 'consulta_notas.php'),
(3029, 2, '::1', '2026-06-03 16:05:07', 'notas_pasadas.php'),
(3030, 2, '::1', '2026-06-03 16:05:10', 'asignar_secciones.php'),
(3031, 2, '::1', '2026-06-03 16:05:17', 'asignar_secciones.php'),
(3032, 2, '::1', '2026-06-03 16:05:20', 'asignar_secciones.php'),
(3033, 2, '::1', '2026-06-03 16:08:15', 'index.php'),
(3034, 2, '::1', '2026-06-03 16:09:20', 'index.php'),
(3035, 2, '::1', '2026-06-03 16:10:14', 'index.php'),
(3036, 1, '::1', '2026-06-03 16:27:15', 'index.php'),
(3037, 1, '::1', '2026-06-03 16:27:17', 'notas.php'),
(3038, 1, '::1', '2026-06-03 16:28:46', 'notas.php'),
(3039, 2, '::1', '2026-06-03 16:29:13', 'index.php'),
(3040, 2, '::1', '2026-06-03 16:29:16', 'admin_notas_pendientes.php'),
(3041, 2, '::1', '2026-06-03 16:48:53', 'admin_notas_pendientes.php'),
(3042, 1, '::1', '2026-06-03 16:49:17', 'index.php'),
(3043, 1, '::1', '2026-06-03 16:49:24', 'notas.php'),
(3044, 2, '::1', '2026-06-03 16:50:05', 'index.php'),
(3045, 2, '::1', '2026-06-03 16:50:48', 'index.php'),
(3046, 2, '::1', '2026-06-03 16:50:52', 'admin_notas_pendientes.php'),
(3047, 2, '::1', '2026-06-03 16:52:12', 'index.php'),
(3048, 2, '::1', '2026-06-03 16:52:15', 'admin_notas_pendientes.php'),
(3049, 1, '::1', '2026-06-03 16:52:32', 'index.php'),
(3050, 1, '::1', '2026-06-03 16:52:33', 'notas.php'),
(3051, 1, '::1', '2026-06-03 17:38:00', 'notas.php'),
(3052, 2, '::1', '2026-06-03 17:38:50', 'index.php'),
(3053, 2, '::1', '2026-06-03 17:38:52', 'admin_notas_pendientes.php'),
(3054, 2, '::1', '2026-06-04 12:49:49', 'index.php'),
(3055, 2, '::1', '2026-06-04 12:52:47', 'admin_notas_pendientes.php'),
(3056, 2, '::1', '2026-06-04 12:53:16', 'admin_notas_pendientes.php'),
(3057, 2, '::1', '2026-06-04 13:31:48', 'index.php'),
(3058, 2, '::1', '2026-06-04 13:31:50', 'admin_notas_pendientes.php'),
(3059, 1, '::1', '2026-06-04 13:32:07', 'index.php'),
(3060, 1, '::1', '2026-06-04 13:32:10', 'notas.php'),
(3061, 2, '::1', '2026-06-04 13:34:08', 'index.php'),
(3062, 2, '::1', '2026-06-04 13:34:11', 'admin_notas_pendientes.php'),
(3063, 2, '::1', '2026-06-04 13:34:27', 'admin_notas_pendientes.php'),
(3064, 2, '::1', '2026-06-04 13:36:09', 'consulta_notas.php'),
(3065, 2, '::1', '2026-06-04 13:36:12', 'notas_pasadas.php'),
(3066, 2, '::1', '2026-06-04 13:36:21', 'notas_pasadas.php'),
(3067, 2, '::1', '2026-06-04 13:36:25', 'correccion_notas.php'),
(3068, 2, '::1', '2026-06-04 13:36:27', 'consulta_notas.php'),
(3069, 2, '::1', '2026-06-04 13:36:31', 'consulta_notas.php'),
(3070, 2, '::1', '2026-06-04 13:37:12', 'asignacion_cursos.php'),
(3071, 2, '::1', '2026-06-04 13:37:19', 'asignacion_cursos.php'),
(3072, 5, '::1', '2026-06-04 13:37:38', 'index.php'),
(3073, 5, '::1', '2026-06-04 13:37:41', 'mi_pensum.php'),
(3074, 2, '::1', '2026-06-04 13:38:05', 'index.php'),
(3075, 2, '::1', '2026-06-04 13:38:11', 'asignacion_cursos.php'),
(3076, 2, '::1', '2026-06-04 13:38:15', 'asignacion_cursos.php'),
(3077, 2, '::1', '2026-06-04 13:38:19', 'asignacion_cursos.php'),
(3078, 2, '::1', '2026-06-04 13:38:26', 'asignar_secciones.php'),
(3079, 2, '::1', '2026-06-04 13:38:34', 'asignar_secciones.php'),
(3080, 2, '::1', '2026-06-04 13:38:48', 'asignar_secciones.php'),
(3081, 2, '::1', '2026-06-04 13:38:59', 'index.php'),
(3082, 2, '::1', '2026-06-04 13:39:50', 'index.php'),
(3083, 1, '::1', '2026-06-04 13:40:06', 'index.php'),
(3084, 1, '::1', '2026-06-04 13:40:11', 'notas.php'),
(3085, 2, '::1', '2026-06-04 13:41:21', 'index.php'),
(3086, 2, '::1', '2026-06-04 13:41:29', 'admin_notas_pendientes.php'),
(3087, 2, '::1', '2026-06-04 13:43:32', 'admin_notas_pendientes.php'),
(3088, 2, '::1', '2026-06-04 13:52:42', 'admin_notas_pendientes.php'),
(3089, 1, '::1', '2026-06-04 13:53:07', 'index.php'),
(3090, 1, '::1', '2026-06-04 13:53:08', 'notas.php'),
(3091, 2, '::1', '2026-06-04 13:53:53', 'index.php'),
(3092, 2, '::1', '2026-06-04 13:54:14', 'admin_notas_pendientes.php'),
(3093, 2, '::1', '2026-06-04 13:54:37', 'admin_notas_pendientes.php'),
(3094, 1, '::1', '2026-06-04 13:55:04', 'index.php'),
(3095, 1, '::1', '2026-06-04 13:55:10', 'mensajeria.php'),
(3096, 2, '::1', '2026-06-04 14:25:25', 'index.php'),
(3097, 2, '::1', '2026-06-04 14:25:28', 'registro_pagos.php'),
(3098, 2, '::1', '2026-06-04 14:25:28', 'mensajeria.php'),
(3099, 2, '::1', '2026-06-04 14:25:33', 'mensajeria.php'),
(3100, 2, '::1', '2026-06-04 14:28:22', 'mensajeria.php'),
(3101, 1, '::1', '2026-06-04 14:28:33', 'index.php'),
(3102, 1, '::1', '2026-06-04 14:28:37', 'mensajeria.php'),
(3103, 1, '::1', '2026-06-04 14:35:07', 'mensajeria.php'),
(3104, 1, '::1', '2026-06-04 14:35:13', 'mensajeria.php'),
(3105, 1, '::1', '2026-06-04 14:35:17', 'index.php'),
(3106, 1, '::1', '2026-06-04 14:35:19', 'notas.php'),
(3107, 2, '::1', '2026-06-04 14:36:36', 'index.php'),
(3108, 2, '::1', '2026-06-04 14:36:39', 'admin_notas_pendientes.php'),
(3109, 2, '::1', '2026-06-04 14:37:14', 'index.php'),
(3110, 2, '::1', '2026-06-04 14:37:16', 'admin_notas_pendientes.php'),
(3111, 2, '::1', '2026-06-04 14:38:10', 'admin_notas_pendientes.php'),
(3112, 2, '::1', '2026-06-04 14:38:12', 'admin_notas_pendientes.php'),
(3113, 1, '::1', '2026-06-04 14:39:33', 'index.php'),
(3114, 1, '::1', '2026-06-04 14:39:34', 'notas.php'),
(3115, 2, '::1', '2026-06-04 14:40:16', 'index.php'),
(3116, 2, '::1', '2026-06-04 14:40:18', 'admin_notas_pendientes.php'),
(3117, 2, '::1', '2026-06-04 14:40:34', 'admin_notas_pendientes.php'),
(3118, 1, '::1', '2026-06-04 14:40:55', 'index.php'),
(3119, 1, '::1', '2026-06-04 14:40:56', 'mensajeria.php'),
(3120, 1, '::1', '2026-06-04 14:40:57', 'mensajeria.php'),
(3121, 1, '::1', '2026-06-04 14:44:27', 'notas.php'),
(3122, 2, '::1', '2026-06-04 14:45:03', 'index.php'),
(3123, 2, '::1', '2026-06-04 14:45:04', 'admin_notas_pendientes.php'),
(3124, 2, '::1', '2026-06-04 14:57:06', 'index.php'),
(3125, 2, '::1', '2026-06-04 14:57:12', 'admin_notas_pendientes.php'),
(3126, 2, '::1', '2026-06-04 14:57:42', 'index.php'),
(3127, 2, '::1', '2026-06-04 14:57:43', 'admin_notas_pendientes.php'),
(3128, 2, '::1', '2026-06-04 15:05:34', 'index.php'),
(3129, 2, '::1', '2026-06-04 15:05:35', 'admin_notas_pendientes.php'),
(3130, 2, '::1', '2026-06-04 15:05:53', 'admin_notas_pendientes.php'),
(3131, 1, '::1', '2026-06-04 15:06:06', 'index.php'),
(3132, 1, '::1', '2026-06-04 15:06:07', 'mensajeria.php'),
(3133, 1, '::1', '2026-06-04 15:06:08', 'mensajeria.php'),
(3134, 1, '::1', '2026-06-04 15:06:21', 'mensajeria.php'),
(3135, 1, '::1', '2026-06-04 15:06:36', 'mensajeria.php'),
(3136, 1, '::1', '2026-06-04 15:17:09', 'index.php'),
(3137, 1, '::1', '2026-06-04 15:17:11', 'notas.php'),
(3138, 2, '::1', '2026-06-04 15:17:39', 'index.php'),
(3139, 2, '::1', '2026-06-04 15:18:30', 'admin_notas_pendientes.php'),
(3140, 2, '::1', '2026-06-04 15:19:15', 'admin_notas_pendientes.php'),
(3141, 2, '::1', '2026-06-04 15:22:11', 'index.php'),
(3142, 1, '::1', '2026-06-04 15:22:23', 'index.php'),
(3143, 1, '::1', '2026-06-04 15:22:25', 'notas.php'),
(3144, 1, '::1', '2026-06-04 15:22:37', 'mensajeria.php'),
(3145, 1, '::1', '2026-06-04 15:22:39', 'mensajeria.php'),
(3146, 1, '::1', '2026-06-04 15:22:41', 'index.php'),
(3147, 1, '::1', '2026-06-04 15:22:42', 'notas.php'),
(3148, 2, '::1', '2026-06-04 15:26:52', 'index.php'),
(3149, 2, '::1', '2026-06-04 15:28:03', 'admin_notas_pendientes.php'),
(3150, 2, '::1', '2026-06-04 15:30:35', 'index.php'),
(3151, 2, '::1', '2026-06-04 15:30:36', 'admin_notas_pendientes.php'),
(3152, 2, '::1', '2026-06-04 15:32:36', 'index.php'),
(3153, 2, '::1', '2026-06-04 15:32:49', 'admin_notas_pendientes.php'),
(3154, 1, '::1', '2026-06-04 15:39:58', 'index.php'),
(3155, 1, '::1', '2026-06-04 15:39:59', 'notas.php'),
(3156, 1, '::1', '2026-06-04 15:40:15', 'mi_horario.php'),
(3157, 2, '::1', '2026-06-04 15:40:35', 'index.php'),
(3158, 2, '::1', '2026-06-04 15:40:37', 'admin_notas_pendientes.php'),
(3159, 2, '::1', '2026-06-04 15:45:51', 'index.php'),
(3160, 2, '::1', '2026-06-04 15:45:52', 'admin_notas_pendientes.php'),
(3161, 1, '::1', '2026-06-04 15:46:05', 'index.php'),
(3162, 1, '::1', '2026-06-04 15:46:06', 'notas.php'),
(3163, 2, '::1', '2026-06-05 14:02:16', 'index.php'),
(3164, 2, '::1', '2026-06-05 14:04:18', 'admin_notas_pendientes.php'),
(3165, 1, '::1', '2026-06-05 14:04:33', 'index.php'),
(3166, 1, '::1', '2026-06-05 14:04:35', 'notas.php'),
(3167, 2, '::1', '2026-06-05 14:06:09', 'index.php'),
(3168, 2, '::1', '2026-06-05 14:06:11', 'admin_notas_pendientes.php'),
(3169, 2, '::1', '2026-06-05 14:06:31', 'admin_notas_pendientes.php'),
(3170, 1, '::1', '2026-06-05 14:06:47', 'index.php'),
(3171, 1, '::1', '2026-06-05 14:06:49', 'mensajeria.php'),
(3172, 1, '::1', '2026-06-05 14:06:52', 'mensajeria.php'),
(3173, 1, '::1', '2026-06-05 14:07:05', 'mensajeria.php'),
(3174, 1, '::1', '2026-06-05 14:08:23', 'index.php'),
(3175, 1, '::1', '2026-06-05 14:08:27', 'notas.php'),
(3176, 1, '::1', '2026-06-05 14:16:32', 'notas.php'),
(3177, 2, '::1', '2026-06-05 14:16:49', 'index.php'),
(3178, 2, '::1', '2026-06-05 14:17:30', 'admin_notas_pendientes.php'),
(3179, 2, '::1', '2026-06-05 14:17:36', 'admin_notas_pendientes.php'),
(3180, 1, '::1', '2026-06-05 14:17:51', 'index.php'),
(3181, 1, '::1', '2026-06-05 14:17:52', 'mensajeria.php'),
(3182, 1, '::1', '2026-06-05 14:17:53', 'mensajeria.php'),
(3183, 1, '::1', '2026-06-05 14:18:07', 'mensajeria.php'),
(3184, 1, '::1', '2026-06-05 14:18:42', 'mensajeria.php'),
(3185, 1, '::1', '2026-06-05 14:18:43', 'index.php'),
(3186, 1, '::1', '2026-06-05 14:18:47', 'mi_horario.php'),
(3187, 1, '::1', '2026-06-05 14:18:51', 'index.php'),
(3188, 2, '::1', '2026-06-05 14:22:56', 'index.php'),
(3189, 2, '::1', '2026-06-05 14:23:08', 'admin_notas_pendientes.php'),
(3190, 2, '::1', '2026-06-05 14:23:10', 'notas_pasadas.php'),
(3191, 2, '::1', '2026-06-05 14:23:10', 'admin_notas_pendientes.php'),
(3192, 2, '::1', '2026-06-05 14:23:12', 'consulta_notas.php'),
(3193, 2, '::1', '2026-06-05 14:23:16', 'gestion_seccion.php'),
(3194, 2, '::1', '2026-06-05 14:23:18', 'ver_seccion.php'),
(3195, 2, '::1', '2026-06-05 14:23:30', 'avance_trayectos.php'),
(3196, 2, '::1', '2026-06-05 14:32:45', 'avance_trayectos.php'),
(3197, 2, '::1', '2026-06-05 14:32:49', 'avance_trayectos.php'),
(3198, 2, '::1', '2026-06-05 15:03:50', 'avance_trayectos.php'),
(3199, 2, '::1', '2026-06-05 15:04:33', 'avance_trayectos.php'),
(3200, 2, '::1', '2026-06-05 15:05:43', 'procesar_avance_seccion.php'),
(3201, 2, '::1', '2026-06-05 15:05:49', 'avance_trayectos.php'),
(3202, 2, '::1', '2026-06-05 15:05:55', 'ver_seccion.php'),
(3203, 2, '::1', '2026-06-05 15:05:58', 'gestion_seccion.php'),
(3204, 2, '::1', '2026-06-05 15:06:07', 'ver_seccion.php'),
(3205, 2, '::1', '2026-06-05 15:06:24', 'consulta_notas.php'),
(3206, 2, '::1', '2026-06-05 15:06:27', 'consulta_notas.php'),
(3207, 2633, '::1', '2026-06-05 15:06:38', 'index.php'),
(3208, 2633, '::1', '2026-06-05 15:06:45', 'mi_horario.php'),
(3209, 2633, '::1', '2026-06-05 15:06:50', 'index.php'),
(3210, 2633, '::1', '2026-06-05 15:06:52', 'mis_secciones.php'),
(3211, 2633, '::1', '2026-06-05 15:06:54', 'index.php'),
(3212, 2633, '::1', '2026-06-05 15:06:56', 'mi_pensum.php'),
(3213, 2633, '::1', '2026-06-05 15:07:05', 'index.php'),
(3214, 2633, '::1', '2026-06-05 15:07:07', 'mi_historial.php'),
(3215, 2633, '::1', '2026-06-05 15:07:11', 'index.php'),
(3216, 2, '::1', '2026-06-05 15:07:37', 'index.php'),
(3217, 2, '::1', '2026-06-05 15:07:41', 'gestion_seccion.php'),
(3218, 2, '::1', '2026-06-05 15:07:50', 'gestion_seccion.php'),
(3219, 2, '::1', '2026-06-05 15:08:14', 'valores_predefinidos.php'),
(3220, 2, '::1', '2026-06-05 15:08:31', 'periodos_academicos.php'),
(3221, 2, '::1', '2026-06-05 15:09:22', 'periodos_academicos.php'),
(3222, 2, '::1', '2026-06-05 15:09:23', 'periodos_academicos.php'),
(3223, 2, '::1', '2026-06-05 15:09:39', 'gestion_seccion.php'),
(3224, 2, '::1', '2026-06-05 15:09:42', 'gestion_seccion.php'),
(3225, 2, '::1', '2026-06-05 15:09:44', 'gestion_seccion.php'),
(3226, 2, '::1', '2026-06-05 15:09:46', 'gestion_seccion.php'),
(3227, 2, '::1', '2026-06-05 15:09:48', 'gestion_seccion.php'),
(3228, 2, '::1', '2026-06-05 15:09:52', 'gestion_seccion.php'),
(3229, 2, '::1', '2026-06-05 15:09:55', 'gestion_seccion.php'),
(3230, 2, '::1', '2026-06-05 15:09:57', 'gestion_seccion.php'),
(3231, 2, '::1', '2026-06-05 15:10:12', 'ver_seccion.php'),
(3232, 2, '::1', '2026-06-05 15:10:24', 'editar_seccion.php'),
(3233, 2, '::1', '2026-06-05 15:10:38', 'gestion_seccion.php'),
(3234, 2, '::1', '2026-06-05 15:10:42', 'editar_seccion.php'),
(3235, 2, '::1', '2026-06-05 15:15:30', 'gestion_seccion.php'),
(3236, 2, '::1', '2026-06-05 15:15:31', 'gestion_seccion.php'),
(3237, 2, '::1', '2026-06-05 15:15:33', 'ver_seccion.php'),
(3238, 2, '::1', '2026-06-05 15:15:36', 'editar_seccion.php'),
(3239, 2, '::1', '2026-06-05 15:16:13', 'gestion_seccion.php'),
(3240, 2, '::1', '2026-06-05 15:16:16', 'ver_seccion.php'),
(3241, 2, '::1', '2026-06-05 15:16:46', 'editar_seccion.php'),
(3242, 2, '::1', '2026-06-05 15:21:30', 'editar_seccion.php'),
(3243, 2, '::1', '2026-06-05 15:21:35', 'editar_seccion.php'),
(3244, 2, '::1', '2026-06-05 15:22:51', 'editar_seccion.php'),
(3245, 2, '::1', '2026-06-05 15:22:55', 'editar_seccion.php'),
(3246, 2, '::1', '2026-06-05 15:25:01', 'editar_seccion.php'),
(3247, 2, '::1', '2026-06-05 15:26:52', 'editar_seccion.php'),
(3248, 2, '::1', '2026-06-05 15:27:05', 'editar_seccion.php'),
(3249, 2, '::1', '2026-06-05 15:27:08', 'gestion_seccion.php'),
(3250, 2, '::1', '2026-06-05 15:27:16', 'editar_seccion.php'),
(3251, 2, '::1', '2026-06-05 15:32:28', 'editar_seccion.php'),
(3252, 2, '::1', '2026-06-05 15:33:18', 'editar_seccion.php'),
(3253, 2, '::1', '2026-06-05 15:33:21', 'gestion_seccion.php'),
(3254, 2, '::1', '2026-06-05 15:33:57', 'aprobar_secciones.php'),
(3255, 2, '::1', '2026-06-05 15:33:59', 'index.php'),
(3256, 2, '::1', '2026-06-05 15:34:06', 'index.php'),
(3257, 2, '::1', '2026-06-05 15:34:36', 'gestion_seccion.php'),
(3258, 2, '::1', '2026-06-05 15:38:58', 'gestion_seccion.php'),
(3259, 2, '::1', '2026-06-05 15:39:00', 'ver_seccion.php'),
(3260, 2, '::1', '2026-06-05 15:39:02', 'editar_seccion.php'),
(3261, 2, '::1', '2026-06-05 15:39:25', 'editar_seccion.php'),
(3262, 2, '::1', '2026-06-05 15:39:27', 'gestion_seccion.php'),
(3263, 2, '::1', '2026-06-05 15:39:46', 'ver_seccion.php'),
(3264, 2, '::1', '2026-06-05 15:39:49', 'editar_seccion.php'),
(3265, 2, '::1', '2026-06-05 15:39:52', 'gestion_seccion.php'),
(3266, 2, '::1', '2026-06-05 15:39:58', 'ver_seccion.php'),
(3267, 2, '::1', '2026-06-05 15:40:01', 'avance_trayectos.php'),
(3268, 2, '::1', '2026-06-05 15:41:39', 'ver_seccion.php'),
(3269, 2, '::1', '2026-06-05 15:41:44', 'gestion_seccion.php'),
(3270, 2, '::1', '2026-06-05 15:42:18', 'index.php'),
(3271, 2, '::1', '2026-06-05 15:42:28', 'consulta_notas.php'),
(3272, 2, '::1', '2026-06-05 15:42:37', 'consulta_notas.php'),
(3273, 2, '::1', '2026-06-05 15:43:37', 'index.php'),
(3274, 2, '::1', '2026-06-05 15:43:41', 'consulta_notas.php'),
(3275, 2, '::1', '2026-06-05 15:43:48', 'consulta_notas.php'),
(3276, 2, '::1', '2026-06-05 15:44:10', 'registro_pagos.php'),
(3277, 2, '::1', '2026-06-05 15:44:13', 'index.php'),
(3278, 2, '::1', '2026-06-05 15:44:14', 'estudiantes.php'),
(3279, 2, '::1', '2026-06-05 15:44:14', 'estudiantes.php'),
(3280, 2, '::1', '2026-06-05 15:44:19', 'agregar_estudiante.php'),
(3281, 2, '::1', '2026-06-05 15:44:19', 'agregar_estudiante.php'),
(3282, 2, '::1', '2026-06-05 15:44:24', 'preinscripciones.php'),
(3283, 2, '::1', '2026-06-05 15:44:25', 'preinscripcion_detalle.php'),
(3284, 2, '::1', '2026-06-05 15:49:05', 'preinscripcion_detalle.php'),
(3285, 2, '::1', '2026-06-05 15:50:41', 'index.php'),
(3286, 2, '::1', '2026-06-05 15:50:43', 'agregar_carrera.php'),
(3287, 2, '::1', '2026-06-05 15:50:46', 'materia.php'),
(3288, 2, '::1', '2026-06-05 15:50:54', 'inscripcion_materias.php'),
(3289, 2, '::1', '2026-06-05 15:51:04', 'inscripcion_materias.php'),
(3290, 2, '::1', '2026-06-05 15:57:35', 'index.php'),
(3291, 2, '::1', '2026-06-05 15:57:38', 'inscripcion_materias.php'),
(3292, 2, '::1', '2026-06-05 15:57:40', 'inscripcion_materias.php'),
(3293, 2, '::1', '2026-06-05 15:59:07', 'inscripcion_materias.php'),
(3294, 2, '::1', '2026-06-05 16:03:35', 'inscripcion_materias.php'),
(3295, 2, '::1', '2026-06-05 16:04:01', 'gestion_seccion.php'),
(3296, 2, '::1', '2026-06-05 16:04:03', 'gestion_seccion.php'),
(3297, 2, '::1', '2026-06-05 16:04:05', 'ver_seccion.php'),
(3298, 2, '::1', '2026-06-05 16:04:10', 'gestion_seccion.php'),
(3299, 2, '::1', '2026-06-05 16:04:15', 'gestion_seccion.php'),
(3300, 2, '::1', '2026-06-05 16:04:18', 'gestion_seccion.php'),
(3301, 2, '::1', '2026-06-05 16:04:38', 'ver_seccion.php'),
(3302, 2, '::1', '2026-06-05 16:04:44', 'gestion_seccion.php'),
(3303, 2, '::1', '2026-06-05 16:04:47', 'inscripcion_materias.php'),
(3304, 2, '::1', '2026-06-05 16:04:50', 'inscripcion_materias.php'),
(3305, 2, '::1', '2026-06-05 16:05:11', 'inscripcion_materias.php'),
(3306, 2, '::1', '2026-06-05 16:05:15', 'inscripcion_materias.php'),
(3307, 2, '::1', '2026-06-05 16:09:58', 'inscripcion_materias.php'),
(3308, 2, '::1', '2026-06-05 16:11:33', 'inscripcion_materias.php'),
(3309, 2, '::1', '2026-06-05 16:15:34', 'inscripcion_materias.php'),
(3310, 2, '::1', '2026-06-05 16:16:03', 'inscripcion_materias.php'),
(3311, 2, '::1', '2026-06-05 16:16:21', 'index.php'),
(3312, 2, '::1', '2026-06-05 16:17:05', 'admin_notas_pendientes.php'),
(3313, 2, '::1', '2026-06-05 16:17:06', 'consulta_notas.php'),
(3314, 2, '::1', '2026-06-05 16:17:11', 'notas_pasadas.php'),
(3315, 2, '::1', '2026-06-05 16:21:41', 'notas_pasadas.php'),
(3316, 2, '::1', '2026-06-05 16:23:16', 'index.php'),
(3317, 2, '::1', '2026-06-05 16:23:25', 'asignacion_cursos.php'),
(3318, 2, '::1', '2026-06-05 16:23:30', 'index.php'),
(3319, 2, '::1', '2026-06-05 16:27:41', 'index.php'),
(3320, 2, '::1', '2026-06-05 16:28:55', 'index.php'),
(3321, 2, '::1', '2026-06-05 16:28:57', 'index.php'),
(3322, 2, '::1', '2026-06-05 16:29:05', 'notas_pasadas.php'),
(3323, 2, '::1', '2026-06-05 16:34:48', 'notas_pasadas.php'),
(3324, 2, '::1', '2026-06-05 16:34:51', 'notas_pasadas.php'),
(3325, 2, '::1', '2026-06-05 16:35:53', 'notas_pasadas.php'),
(3326, 2, '::1', '2026-06-05 16:36:04', 'notas_pasadas.php'),
(3327, 2, '::1', '2026-06-05 16:36:07', 'index.php'),
(3328, 2, '::1', '2026-06-05 16:36:14', 'respaldo_bd.php'),
(3329, 2, '::1', '2026-06-05 16:36:16', 'titulos_relaciones_materias.php'),
(3330, 2, '::1', '2026-06-05 16:36:16', 'titulos_relaciones_materias.php'),
(3331, 2, '::1', '2026-06-05 16:36:17', 'titulos_relaciones_materias.php'),
(3332, 2, '::1', '2026-06-05 16:36:19', 'index.php'),
(3333, 2, '::1', '2026-06-05 16:36:28', 'index.php'),
(3334, 2, '::1', '2026-06-05 16:36:29', 'mensajeria.php'),
(3335, 2, '::1', '2026-06-05 16:36:37', 'index.php'),
(3336, 2, '::1', '2026-06-05 16:36:39', 'secretaria.php'),
(3337, 2, '::1', '2026-06-05 16:37:34', 'secretaria.php'),
(3338, 2, '::1', '2026-06-05 16:38:40', 'index.php'),
(3339, 2, '::1', '2026-06-05 16:38:42', 'secretaria.php'),
(3340, 2, '::1', '2026-06-05 16:39:09', 'secretaria.php'),
(3341, 2, '::1', '2026-06-08 15:52:18', 'index.php'),
(3342, 2, '::1', '2026-06-08 15:52:21', 'estudiantes.php'),
(3343, 2, '::1', '2026-06-08 15:52:22', 'estudiantes.php'),
(3344, 2, '::1', '2026-06-08 16:12:48', 'index.php'),
(3345, 2, '::1', '2026-06-08 16:12:51', 'index.php'),
(3346, 2, '::1', '2026-06-08 16:12:53', 'notas.php'),
(3347, 2, '::1', '2026-06-08 16:12:56', 'index.php'),
(3348, 2, '::1', '2026-06-10 13:08:13', 'index.php'),
(3349, 2, '::1', '2026-06-10 13:09:24', 'gestion_seccion.php'),
(3350, 2, '::1', '2026-06-10 13:09:28', 'ver_seccion.php'),
(3351, 2, '::1', '2026-06-10 13:09:30', 'avance_trayectos.php'),
(3352, 2, '::1', '2026-06-10 13:21:41', 'avance_trayectos.php'),
(3353, 2, '::1', '2026-06-10 13:21:58', 'ver_seccion.php'),
(3354, 2, '::1', '2026-06-10 13:22:00', 'gestion_seccion.php'),
(3355, 2, '::1', '2026-06-10 13:22:04', 'ver_seccion.php'),
(3356, 2, '::1', '2026-06-10 13:22:08', 'gestion_seccion.php'),
(3357, 2, '::1', '2026-06-10 13:22:09', 'ver_seccion.php'),
(3358, 2, '::1', '2026-06-10 13:22:12', 'gestion_seccion.php'),
(3359, 2, '::1', '2026-06-10 13:22:13', 'ver_seccion.php'),
(3360, 2, '::1', '2026-06-10 13:22:16', 'avance_trayectos.php'),
(3361, 2, '::1', '2026-06-11 13:24:10', 'index.php'),
(3362, 2, '::1', '2026-06-11 13:24:31', 'gestion_seccion.php'),
(3363, 2, '::1', '2026-06-11 13:25:13', 'gestion_seccion.php'),
(3364, 2, '::1', '2026-06-11 13:25:14', 'ver_seccion.php'),
(3365, 2, '::1', '2026-06-11 13:25:16', 'avance_trayectos.php'),
(3366, 2, '::1', '2026-06-11 13:29:20', 'avance_trayectos.php'),
(3367, 2, '::1', '2026-06-11 13:34:56', 'avance_trayectos.php'),
(3368, 2, '::1', '2026-06-11 13:35:04', 'avance_trayectos.php'),
(3369, 2, '::1', '2026-06-11 13:40:59', 'avance_trayectos.php'),
(3370, 2, '::1', '2026-06-11 13:41:13', 'ver_seccion.php'),
(3371, 2, '::1', '2026-06-11 13:41:28', 'avance_trayectos.php'),
(3372, 2, '::1', '2026-06-11 13:41:43', 'gestion_seccion.php'),
(3373, 2, '::1', '2026-06-12 13:30:30', 'index.php'),
(3374, 2, '::1', '2026-06-12 13:31:28', 'index.php'),
(3375, 2, '::1', '2026-06-12 13:31:29', 'index.php'),
(3376, 2, '::1', '2026-06-12 13:31:36', 'gestion_seccion.php'),
(3377, 2, '::1', '2026-06-12 13:31:40', 'ver_seccion.php'),
(3378, 2, '::1', '2026-06-12 13:31:41', 'avance_trayectos.php'),
(3379, 2, '::1', '2026-06-12 13:50:49', 'avance_trayectos.php'),
(3380, 2, '::1', '2026-06-12 13:51:00', 'ver_seccion.php'),
(3381, 2, '::1', '2026-06-12 13:51:02', 'gestion_seccion.php'),
(3382, 2, '::1', '2026-06-12 13:51:12', 'editar_seccion.php'),
(3383, 2, '::1', '2026-06-12 13:51:19', 'editar_seccion.php'),
(3384, 2, '::1', '2026-06-12 13:51:21', 'gestion_seccion.php'),
(3385, 2, '::1', '2026-06-12 13:51:32', 'ver_seccion.php'),
(3386, 2, '::1', '2026-06-12 13:51:33', 'avance_trayectos.php'),
(3387, 2, '::1', '2026-06-12 13:53:41', 'avance_trayectos.php'),
(3388, 2, '::1', '2026-06-12 13:53:43', 'avance_trayectos.php'),
(3389, 2, '::1', '2026-06-12 13:53:50', 'avance_trayectos.php'),
(3390, 2, '::1', '2026-06-12 13:54:27', 'avance_trayectos.php');
INSERT INTO `visitas` (`id`, `id_usuario`, `ip`, `fecha_visita`, `web`) VALUES
(3391, 2, '::1', '2026-06-12 14:00:35', 'avance_trayectos.php'),
(3392, 2, '::1', '2026-06-12 14:03:47', 'ver_seccion.php'),
(3393, 2, '::1', '2026-06-12 14:03:49', 'gestion_seccion.php'),
(3394, 2, '::1', '2026-06-12 14:03:52', 'gestion_seccion.php'),
(3395, 2, '::1', '2026-06-12 14:03:54', 'gestion_seccion.php'),
(3396, 2, '::1', '2026-06-12 14:03:55', 'gestion_seccion.php'),
(3397, 2, '::1', '2026-06-12 14:03:57', 'gestion_seccion.php'),
(3398, 2, '::1', '2026-06-12 14:03:58', 'gestion_seccion.php'),
(3399, 2, '::1', '2026-06-12 14:03:59', 'gestion_seccion.php'),
(3400, 2, '::1', '2026-06-12 14:04:02', 'gestion_seccion.php'),
(3401, 2, '::1', '2026-06-12 14:04:06', 'gestion_seccion.php'),
(3402, 2, '::1', '2026-06-12 14:04:08', 'gestion_seccion.php'),
(3403, 2, '::1', '2026-06-12 14:04:16', 'ver_seccion.php'),
(3404, 2, '::1', '2026-06-12 14:04:31', 'gestion_seccion.php'),
(3405, 2633, '::1', '2026-06-12 14:04:49', 'index.php'),
(3406, 2633, '::1', '2026-06-12 14:04:51', 'mi_historial.php'),
(3407, 2633, '::1', '2026-06-12 14:06:14', 'index.php'),
(3408, 2633, '::1', '2026-06-12 14:06:21', 'index.php'),
(3409, 2, '::1', '2026-06-12 14:06:48', 'index.php'),
(3410, 2, '::1', '2026-06-12 14:06:56', 'preinscripciones.php'),
(3411, 4, '::1', '2026-06-12 18:41:04', 'index.php'),
(3412, 2, '::1', '2026-06-15 12:56:24', 'index.php'),
(3413, 2, '::1', '2026-06-15 15:23:05', 'index.php'),
(3414, 2, '::1', '2026-06-15 15:50:50', 'index.php'),
(3415, 2, '::1', '2026-06-15 15:50:50', 'index.php'),
(3416, 2, '::1', '2026-06-15 16:18:57', 'index.php'),
(3417, 2, '::1', '2026-06-15 16:51:46', 'index.php'),
(3418, 2, '::1', '2026-06-16 16:57:05', 'index.php'),
(3419, 2, '::1', '2026-06-16 16:57:07', 'preinscripciones.php'),
(3420, 2, '::1', '2026-06-16 16:57:14', 'index.php'),
(3421, 2, '::1', '2026-06-16 16:57:15', 'estudiantes.php'),
(3422, 2, '::1', '2026-06-16 16:57:16', 'estudiantes.php'),
(3423, 2, '::1', '2026-06-16 16:57:17', 'preinscripciones.php'),
(3424, 2, '::1', '2026-06-16 16:57:19', 'preinscripcion_detalle.php'),
(3425, 2, '::1', '2026-06-16 16:57:38', 'preinscripcion_detalle.php'),
(3426, 2, '::1', '2026-06-16 16:57:41', 'estudiantes.php'),
(3427, 2, '::1', '2026-06-16 16:57:41', 'estudiantes.php'),
(3428, 2, '::1', '2026-06-16 17:12:20', 'preinscripciones.php'),
(3429, 2, '::1', '2026-06-16 17:12:22', 'preinscripcion_detalle.php'),
(3430, 2, '::1', '2026-06-16 17:12:53', 'gestion_seccion.php'),
(3431, 2, '::1', '2026-06-16 17:12:57', 'gestion_seccion.php'),
(3432, 2, '::1', '2026-06-16 17:13:47', 'gestion_seccion.php'),
(3433, 2, '::1', '2026-06-16 17:13:54', 'gestion_seccion.php'),
(3434, 2, '::1', '2026-06-16 17:14:03', 'index.php'),
(3435, 2, '::1', '2026-06-16 17:14:07', 'index.php'),
(3436, 2, '::1', '2026-06-16 17:14:43', 'index.php'),
(3437, 2, '::1', '2026-06-16 17:14:47', 'directores_carrera.php'),
(3438, 2, '::1', '2026-06-16 17:14:57', 'editar_accesos.php'),
(3439, 2, '::1', '2026-06-16 17:16:00', 'directores_carrera.php'),
(3440, 2, '::1', '2026-06-16 17:16:09', 'directores_carrera.php'),
(3441, 2, '::1', '2026-06-16 17:16:10', 'directores_carrera.php'),
(3442, 2, '::1', '2026-06-16 17:16:19', 'directores_carrera.php'),
(3443, 2, '::1', '2026-06-16 17:16:19', 'directores_carrera.php'),
(3444, 2, '::1', '2026-06-16 17:16:54', 'index.php'),
(3445, 2, '::1', '2026-06-16 17:16:56', 'add_docente.php'),
(3446, 2, '::1', '2026-06-16 17:17:32', 'directores_carrera.php'),
(3447, 2, '::1', '2026-06-16 17:17:52', 'add_docente.php'),
(3448, 4, '::1', '2026-06-16 17:18:32', 'index.php'),
(3449, 2, '::1', '2026-06-16 17:23:00', 'index.php'),
(3450, 2, '::1', '2026-06-16 17:23:04', 'secretaria.php'),
(3451, 2, '::1', '2026-06-16 17:23:32', 'gestion_seccion.php'),
(3452, 2, '::1', '2026-06-16 17:23:35', 'aprobar_secciones.php'),
(3453, 2, '::1', '2026-06-16 17:23:51', 'aprobar_secciones.php'),
(3454, 2, '::1', '2026-06-16 17:23:57', 'index.php'),
(3455, 2, '::1', '2026-06-16 17:24:01', 'preinscripciones.php'),
(3456, 2, '::1', '2026-06-16 17:24:03', 'preinscripcion_detalle.php'),
(3457, 2, '::1', '2026-06-16 17:26:20', 'preinscripcion_detalle.php'),
(3458, 2, '::1', '2026-06-16 17:40:57', 'preinscripcion_detalle.php'),
(3459, 2, '::1', '2026-06-16 17:41:04', 'preinscripcion_detalle.php'),
(3460, 2, '::1', '2026-06-16 17:42:13', 'index.php'),
(3461, 2, '::1', '2026-06-16 17:42:18', 'preinscripciones.php'),
(3462, 2, '::1', '2026-06-16 17:42:23', 'preinscripcion_detalle.php'),
(3463, 2, '::1', '2026-06-16 17:42:28', 'preinscripcion_detalle.php'),
(3464, 2636, '::1', '2026-06-16 17:45:16', 'index.php'),
(3465, 2, '::1', '2026-06-18 13:59:29', 'index.php'),
(3466, 2, '::1', '2026-06-18 13:59:48', 'estudiantes.php'),
(3467, 2, '::1', '2026-06-18 13:59:48', 'estudiantes.php'),
(3468, 2, '::1', '2026-06-18 14:00:28', 'consulta_notas.php'),
(3469, 2, '::1', '2026-06-18 14:00:51', 'consulta_notas.php'),
(3470, 2, '::1', '2026-06-18 14:01:27', 'gestion_seccion.php'),
(3471, 2, '::1', '2026-06-18 14:01:50', 'ver_seccion.php'),
(3472, 2, '::1', '2026-06-18 14:02:09', 'admin_notas_pendientes.php'),
(3473, 2, '::1', '2026-06-18 14:02:13', 'consulta_notas.php'),
(3474, 2, '::1', '2026-06-18 14:02:16', 'consulta_notas.php'),
(3475, 2, '::1', '2026-06-18 14:03:20', 'index.php'),
(3476, 2, '::1', '2026-06-18 14:03:25', 'auditoria.php'),
(3477, 2, '::1', '2026-06-18 14:03:29', 'index.php'),
(3478, 2, '::1', '2026-06-18 14:03:33', 'aprobar_secciones.php'),
(3479, 2, '::1', '2026-06-18 14:03:35', 'index.php'),
(3480, 2, '::1', '2026-06-18 14:04:09', 'consulta_notas.php'),
(3481, 2, '::1', '2026-06-18 14:04:50', 'correccion_notas.php'),
(3482, 2, '::1', '2026-06-18 14:04:53', 'correccion_notas.php'),
(3483, 2, '::1', '2026-06-18 14:04:57', 'correccion_notas.php'),
(3484, 2, '::1', '2026-06-18 14:05:06', 'correccion_notas.php'),
(3485, 2, '::1', '2026-06-18 14:05:42', 'correccion_notas.php'),
(3486, 2, '::1', '2026-06-18 14:06:07', 'consulta_notas.php'),
(3487, 2, '::1', '2026-06-18 14:06:10', 'consulta_notas.php'),
(3488, 2, '::1', '2026-07-13 13:02:25', 'index.php'),
(3489, 2, '::1', '2026-07-13 13:05:30', 'inscripcion_materias.php'),
(3490, 2, '::1', '2026-07-13 13:05:35', 'inscripcion_materias.php'),
(3491, 2, '::1', '2026-07-13 13:05:46', 'inscripcion_materias.php'),
(3492, 2, '::1', '2026-07-13 13:08:40', 'inscripcion_materias.php'),
(3493, 2, '::1', '2026-07-13 13:08:59', 'estudiantes.php'),
(3494, 2, '::1', '2026-07-13 13:09:00', 'estudiantes.php'),
(3495, 2, '::1', '2026-07-13 13:09:25', 'consulta_notas.php'),
(3496, 2, '::1', '2026-07-13 13:09:41', 'inscripcion_materias.php'),
(3497, 2, '::1', '2026-07-13 13:12:21', 'consulta_notas.php'),
(3498, 2, '::1', '2026-07-13 13:12:24', 'consulta_notas.php'),
(3499, 2, '::1', '2026-07-13 13:12:40', 'gestion_seccion.php'),
(3500, 2, '::1', '2026-07-13 13:12:51', 'ver_seccion.php'),
(3501, 2, '::1', '2026-07-13 13:12:57', 'horario_seccion.php'),
(3502, 2, '::1', '2026-07-13 13:12:59', 'ver_seccion.php'),
(3503, 2, '::1', '2026-07-13 13:13:02', 'gestion_seccion.php'),
(3504, 2, '::1', '2026-07-13 13:13:08', 'ver_seccion.php'),
(3505, 2, '::1', '2026-07-13 13:13:11', 'horario_seccion.php'),
(3506, 2, '::1', '2026-07-13 13:13:13', 'ver_seccion.php'),
(3507, 2, '::1', '2026-07-13 13:13:14', 'gestion_seccion.php'),
(3508, 2, '::1', '2026-07-13 13:13:17', 'ver_seccion.php'),
(3509, 2, '::1', '2026-07-13 13:13:19', 'horario_seccion.php'),
(3510, 2, '::1', '2026-07-13 13:13:20', 'ver_seccion.php'),
(3511, 2, '::1', '2026-07-13 13:13:21', 'gestion_seccion.php'),
(3512, 2, '::1', '2026-07-13 13:13:23', 'ver_seccion.php'),
(3513, 2, '::1', '2026-07-13 13:13:25', 'gestion_seccion.php'),
(3514, 2, '::1', '2026-07-13 13:13:26', 'ver_seccion.php'),
(3515, 2, '::1', '2026-07-13 13:13:27', 'gestion_seccion.php'),
(3516, 2, '::1', '2026-07-13 13:13:28', 'ver_seccion.php'),
(3517, 2, '::1', '2026-07-13 13:13:29', 'horario_seccion.php'),
(3518, 2, '::1', '2026-07-13 13:13:30', 'ver_seccion.php'),
(3519, 2, '::1', '2026-07-13 13:13:33', 'gestion_seccion.php'),
(3520, 2, '::1', '2026-07-13 13:13:35', 'ver_seccion.php'),
(3521, 2, '::1', '2026-07-13 13:13:38', 'horario_seccion.php'),
(3522, 2, '::1', '2026-07-13 13:13:40', 'ver_seccion.php'),
(3523, 2, '::1', '2026-07-13 13:13:43', 'horario_seccion.php'),
(3524, 2, '::1', '2026-07-13 13:13:58', 'ver_seccion.php'),
(3525, 2, '::1', '2026-07-13 13:14:05', 'gestion_seccion.php'),
(3526, 2, '::1', '2026-07-13 13:14:18', 'ver_seccion.php'),
(3527, 2, '::1', '2026-07-13 13:14:19', 'gestion_seccion.php'),
(3528, 2, '::1', '2026-07-13 13:14:26', 'ver_seccion.php'),
(3529, 2, '::1', '2026-07-13 13:14:28', 'horario_seccion.php'),
(3530, 2, '::1', '2026-07-13 13:14:29', 'ver_seccion.php'),
(3531, 2, '::1', '2026-07-13 13:14:35', 'gestion_seccion.php'),
(3532, 2, '::1', '2026-07-13 13:14:36', 'ver_seccion.php'),
(3533, 2, '::1', '2026-07-13 13:14:38', 'horario_seccion.php'),
(3534, 2, '::1', '2026-07-13 13:14:39', 'ver_seccion.php'),
(3535, 2, '::1', '2026-07-13 13:14:40', 'avance_trayectos.php'),
(3536, 2, '::1', '2026-07-13 13:23:20', 'index.php'),
(3537, 2, '::1', '2026-07-13 13:23:31', 'estudiantes.php'),
(3538, 2, '::1', '2026-07-13 13:23:31', 'estudiantes.php'),
(3539, 2, '::1', '2026-07-13 13:23:35', 'consulta_notas.php'),
(3540, 2, '::1', '2026-07-13 13:23:39', 'consulta_notas.php'),
(3541, 2, '::1', '2026-07-13 13:23:45', 'inscripcion_materias.php'),
(3542, 2, '::1', '2026-07-13 13:25:04', 'index.php'),
(3543, 2, '::1', '2026-07-13 13:25:12', 'inscripcion_materias.php'),
(3544, 2, '::1', '2026-07-13 13:25:18', 'inscripcion_materias.php'),
(3545, 2, '::1', '2026-07-13 13:55:22', 'inscripcion_materias.php'),
(3546, 2, '::1', '2026-07-13 13:55:27', 'inscripcion_materias.php'),
(3547, 2, '::1', '2026-07-13 14:02:48', 'inscripcion_materias.php'),
(3548, 2, '::1', '2026-07-13 14:12:16', 'inscripcion_materias.php'),
(3549, 2, '::1', '2026-07-13 14:12:28', 'inscripcion_materias.php'),
(3550, 2, '::1', '2026-07-13 14:12:33', 'inscripcion_materias.php'),
(3551, 2, '::1', '2026-07-13 15:06:52', 'index.php'),
(3552, 2, '::1', '2026-07-13 15:06:55', 'inscripcion_materias.php'),
(3553, 2, '::1', '2026-07-13 15:12:08', 'inscripcion_materias.php'),
(3554, 2, '::1', '2026-07-13 15:17:31', 'gestion_seccion.php'),
(3555, 2, '::1', '2026-07-13 15:17:39', 'estudiantes.php'),
(3556, 2, '::1', '2026-07-13 15:17:40', 'estudiantes.php'),
(3557, 2, '::1', '2026-07-13 15:19:26', 'inscripcion_materias.php'),
(3558, 2, '::1', '2026-07-13 15:19:58', 'gestion_seccion.php'),
(3559, 2, '::1', '2026-07-13 15:20:09', 'ver_seccion.php'),
(3560, 2, '::1', '2026-07-13 15:22:33', 'horario_seccion.php'),
(3561, 2, '::1', '2026-07-13 15:22:42', 'ver_seccion.php'),
(3562, 2, '::1', '2026-07-13 15:22:46', 'index.php'),
(3563, 2, '::1', '2026-07-13 15:23:19', 'asignacion_cursos.php'),
(3564, 2, '::1', '2026-07-13 15:24:00', 'asignacion_cursos.php'),
(3565, 2, '::1', '2026-07-13 15:24:04', 'index.php'),
(3566, 2, '::1', '2026-07-13 15:28:33', 'index.php'),
(3567, 2, '::1', '2026-07-13 15:28:36', 'asignacion_cursos.php'),
(3568, 2, '::1', '2026-07-13 15:55:47', 'index.php'),
(3569, 2, '::1', '2026-07-13 15:56:06', 'index.php'),
(3570, 2, '::1', '2026-07-13 15:56:09', 'asignacion_cursos.php'),
(3571, 2, '::1', '2026-07-13 15:56:21', 'asignacion_cursos.php'),
(3572, 2, '::1', '2026-07-13 15:56:32', 'asignacion_cursos.php'),
(3573, 2, '::1', '2026-07-13 15:56:46', 'asignacion_cursos.php'),
(3574, 2, '::1', '2026-07-13 15:56:53', 'asignar_secciones.php'),
(3575, 2, '::1', '2026-07-13 15:57:06', 'asignar_secciones.php'),
(3576, 2, '::1', '2026-07-13 15:57:11', 'asignar_secciones.php'),
(3577, 2, '::1', '2026-07-13 15:57:21', 'index.php'),
(3578, 2, '::1', '2026-07-13 16:04:03', 'index.php'),
(3579, 2, '::1', '2026-07-13 16:04:07', 'gestion_seccion.php'),
(3580, 2, '::1', '2026-07-13 16:04:11', 'ver_seccion.php'),
(3581, 2, '::1', '2026-07-13 16:04:13', 'horario_seccion.php'),
(3582, 2, '::1', '2026-07-13 16:04:22', 'ver_seccion.php'),
(3583, 2, '::1', '2026-07-13 16:04:30', 'horario_seccion.php'),
(3584, 2, '::1', '2026-07-13 16:04:43', 'ver_seccion.php'),
(3585, 2, '::1', '2026-07-13 16:04:47', 'index.php'),
(3586, 2, '::1', '2026-07-13 16:13:20', 'gestion_seccion.php'),
(3587, 2, '::1', '2026-07-13 16:13:22', 'ver_seccion.php'),
(3588, 2630, '::1', '2026-07-13 16:15:02', 'index.php'),
(3589, 2630, '::1', '2026-07-13 16:15:05', 'mi_historial.php'),
(3590, 2630, '::1', '2026-07-13 16:15:10', 'index.php'),
(3591, 2630, '::1', '2026-07-13 16:15:12', 'mi_horario.php'),
(3592, 2630, '::1', '2026-07-13 16:15:28', 'vocero.php'),
(3593, 2630, '::1', '2026-07-13 16:15:35', 'vocero.php'),
(3594, 2630, '::1', '2026-07-13 16:15:40', 'vocero.php'),
(3595, 2630, '::1', '2026-07-13 16:15:42', 'index.php'),
(3596, 2630, '::1', '2026-07-13 16:15:46', 'mi_pensum.php'),
(3597, 2630, '::1', '2026-07-13 16:15:50', 'index.php'),
(3598, 2630, '::1', '2026-07-13 16:15:51', 'mi_historial.php'),
(3599, 2, '::1', '2026-07-13 16:16:03', 'index.php'),
(3600, 2, '::1', '2026-07-13 16:16:13', 'secretaria.php'),
(3601, 2, '::1', '2026-07-13 16:20:17', 'asignar_secciones.php'),
(3602, 2630, '::1', '2026-07-13 17:06:13', 'index.php'),
(3603, 2, '::1', '2026-07-13 17:07:17', 'index.php'),
(3604, 2, '::1', '2026-07-13 17:07:20', 'index.php'),
(3605, 2, '::1', '2026-07-13 17:08:55', 'index.php'),
(3606, 2, '::1', '2026-07-13 17:09:01', 'index.php'),
(3607, 2, '::1', '2026-07-13 17:09:59', 'index.php'),
(3608, 2, '::1', '2026-07-13 17:10:54', 'index.php'),
(3609, 2, '::1', '2026-07-13 17:10:56', 'index.php'),
(3610, 2, '::1', '2026-07-13 17:11:21', 'index.php'),
(3611, 2, '::1', '2026-07-15 14:06:58', 'index.php'),
(3612, 2, '::1', '2026-07-15 14:07:04', 'index.php'),
(3613, 2, '::1', '2026-07-15 14:07:07', 'index.php'),
(3614, 2, '::1', '2026-07-15 14:21:09', 'index.php'),
(3615, 2, '::1', '2026-07-15 14:21:17', 'index.php'),
(3616, 2, '::1', '2026-07-15 14:22:17', 'index.php'),
(3617, 2, '::1', '2026-07-15 14:22:27', 'index.php'),
(3618, 2, '::1', '2026-07-15 14:23:57', 'index.php'),
(3619, 2, '::1', '2026-07-15 14:23:59', 'director_asignar_secciones.php'),
(3620, 2, '::1', '2026-07-15 14:30:17', 'director_asignar_secciones.php'),
(3621, 2, '::1', '2026-07-15 14:36:24', 'director_asignar_secciones.php'),
(3622, 2, '::1', '2026-07-15 14:45:42', 'director_asignar_secciones.php'),
(3623, 2, '::1', '2026-07-15 14:46:02', 'director_asignar_secciones.php'),
(3624, 2, '::1', '2026-07-15 14:46:13', 'director_asignar_secciones.php'),
(3625, 2, '::1', '2026-07-15 14:46:32', 'index.php'),
(3626, 2, '::1', '2026-07-15 14:46:39', 'gestion_seccion.php'),
(3627, 2, '::1', '2026-07-15 14:46:54', 'ver_seccion.php'),
(3628, 2, '::1', '2026-07-15 14:47:18', 'horario_seccion.php'),
(3629, 2, '::1', '2026-07-15 14:47:29', 'asignacion_cursos.php'),
(3630, 2, '::1', '2026-07-15 14:47:56', 'asignacion_cursos.php'),
(3631, 2, '::1', '2026-07-15 14:48:05', 'director_asignar_secciones.php'),
(3632, 2, '::1', '2026-07-15 14:48:11', 'director_asignar_secciones.php'),
(3633, 2, '::1', '2026-07-15 14:48:21', 'asignacion_cursos.php'),
(3634, 2, '::1', '2026-07-15 14:53:00', 'asignacion_cursos.php'),
(3635, 2, '::1', '2026-07-15 14:53:04', 'director_asignar_secciones.php'),
(3636, 2, '::1', '2026-07-15 14:53:12', 'director_asignar_secciones.php'),
(3637, 2, '::1', '2026-07-15 14:53:19', 'director_asignar_secciones.php'),
(3638, 2, '::1', '2026-07-15 14:53:28', 'director_asignar_secciones.php'),
(3639, 2, '::1', '2026-07-15 14:53:42', 'index.php'),
(3640, 2, '::1', '2026-07-15 14:53:43', 'director_asignar_secciones.php'),
(3641, 2, '::1', '2026-07-15 14:53:45', 'director_asignar_secciones.php'),
(3642, 2, '::1', '2026-07-15 15:02:13', 'director_asignar_secciones.php'),
(3643, 2, '::1', '2026-07-15 15:02:16', 'director_asignar_secciones.php'),
(3644, 2, '::1', '2026-07-15 15:02:22', 'director_asignar_secciones.php'),
(3645, 2, '::1', '2026-07-15 15:14:05', 'director_asignar_secciones.php'),
(3646, 2, '::1', '2026-07-15 15:23:19', 'mensajeria.php'),
(3647, 2, '::1', '2026-07-15 15:23:21', 'mensajeria.php'),
(3648, 2, '::1', '2026-07-15 15:23:21', 'index.php'),
(3649, 2, '::1', '2026-07-15 15:23:33', 'asignacion_cursos.php'),
(3650, 2, '::1', '2026-07-15 15:23:39', 'index.php'),
(3651, 2, '::1', '2026-07-15 15:24:15', 'index.php'),
(3652, 2, '::1', '2026-07-15 15:32:28', 'index.php'),
(3653, 2, '::1', '2026-07-15 15:32:34', 'secretaria.php'),
(3654, 2, '::1', '2026-07-15 15:34:25', 'horario_seccion.php'),
(3655, 2, '::1', '2026-07-15 15:34:33', 'index.php'),
(3656, 2, '::1', '2026-07-15 15:35:54', 'horario_seccion.php'),
(3657, 2, '::1', '2026-07-15 15:36:52', 'index.php'),
(3658, 2, '::1', '2026-07-15 15:43:14', 'index.php'),
(3659, 2, '::1', '2026-07-15 15:50:29', 'index.php'),
(3660, 2, '::1', '2026-07-15 15:50:34', 'aprobar_secciones.php'),
(3661, 2, '::1', '2026-07-15 15:50:37', 'index.php'),
(3662, 2, '::1', '2026-07-15 15:50:51', 'registro_pagos.php'),
(3663, 2, '::1', '2026-07-15 15:50:55', 'index.php'),
(3664, 2, '::1', '2026-07-15 15:51:33', 'index.php'),
(3665, 2, '::1', '2026-07-15 15:53:11', 'index.php'),
(3666, 2, '::1', '2026-07-15 15:53:14', 'index.php'),
(3667, 2, '::1', '2026-07-15 15:53:29', 'index.php'),
(3668, 2, '::1', '2026-07-15 15:53:31', 'index.php'),
(3669, 2, '::1', '2026-07-15 15:53:32', 'index.php'),
(3670, 2, '::1', '2026-07-15 15:53:33', 'index.php'),
(3671, 2, '::1', '2026-07-15 15:53:33', 'index.php'),
(3672, 2, '::1', '2026-07-15 15:53:37', 'index.php'),
(3673, 2, '::1', '2026-07-15 15:53:39', 'index.php'),
(3674, 2, '::1', '2026-07-15 16:03:25', 'index.php'),
(3675, 2, '::1', '2026-07-16 13:39:48', 'index.php'),
(3676, 2, '::1', '2026-07-16 13:40:00', 'index.php');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `aprobaciones_avance`
--
ALTER TABLE `aprobaciones_avance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_trayecto` (`id_usuario`,`trayecto_origen`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `aprobado_por` (`aprobado_por`);

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_auditoria_usuario` (`usuario_id`),
  ADD KEY `idx_auditoria_fecha` (`fecha_hora`),
  ADD KEY `idx_auditoria_accion` (`accion`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `carrera_materia`
--
ALTER TABLE `carrera_materia`
  ADD PRIMARY KEY (`id_relacion`),
  ADD UNIQUE KEY `idx_relacion_unica` (`id_carrera`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `carrera_versiones`
--
ALTER TABLE `carrera_versiones`
  ADD PRIMARY KEY (`id_version`),
  ADD KEY `id_carrera` (`id_carrera`);

--
-- Indices de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id_ciudad`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `contenido`
--
ALTER TABLE `contenido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `control_avance_trayecto`
--
ALTER TABLE `control_avance_trayecto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_trayecto` (`id_usuario`,`trayecto_actual`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `aprobado_por` (`aprobado_por`);

--
-- Indices de la tabla `docente_materia`
--
ALTER TABLE `docente_materia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `docente_seccion`
--
ALTER TABLE `docente_seccion`
  ADD PRIMARY KEY (`id_docente_seccion`),
  ADD UNIQUE KEY `idx_unique_seccion_materia` (`id_seccion`,`id_materia`),
  ADD UNIQUE KEY `unique_asignacion` (`id_usuario`,`id_seccion`,`id_materia`),
  ADD KEY `id_seccion` (`id_seccion`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_civil`
--
ALTER TABLE `estado_civil`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiante_materias`
--
ALTER TABLE `estudiante_materias`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `id_seccion` (`id_seccion`),
  ADD KEY `idx_usuario` (`id_usuario`),
  ADD KEY `idx_periodo` (`id_periodo`);

--
-- Indices de la tabla `estudiante_seccion`
--
ALTER TABLE `estudiante_seccion`
  ADD PRIMARY KEY (`id_usuario`,`id_seccion`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_usua_3` (`id_usua`),
  ADD KEY `id_usua` (`id_usua`),
  ADD KEY `id_usua_2` (`id_usua`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `graduados`
--
ALTER TABLE `graduados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_admin_graduacion` (`id_admin_graduacion`),
  ADD KEY `id_admin_entrega_titulo` (`id_admin_entrega_titulo`);

--
-- Indices de la tabla `historial_cambios_notas`
--
ALTER TABLE `historial_cambios_notas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nota` (`id_nota`),
  ADD KEY `id_nota_trimestre` (`id_nota_trimestre`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_docente_seccion` (`id_docente_seccion`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mallas`
--
ALTER TABLE `mallas`
  ADD PRIMARY KEY (`id_malla`),
  ADD UNIQUE KEY `codigo_malla` (`codigo_malla`),
  ADD KEY `id_carrera` (`id_carrera`);

--
-- Indices de la tabla `malla_materia`
--
ALTER TABLE `malla_materia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_malla` (`id_malla`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`),
  ADD UNIQUE KEY `cod_materia` (`cod_materia`);

--
-- Indices de la tabla `mensajeria`
--
ALTER TABLE `mensajeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_remitente` (`id_usuario_remitente`),
  ADD KEY `id_usuario_destinatario` (`id_usuario_destinatario`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `nombre_curso`
--
ALTER TABLE `nombre_curso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas_definitivas`
--
ALTER TABLE `notas_definitivas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `id_admin_aprobador` (`id_admin_aprobador`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `notas_pendientes`
--
ALTER TABLE `notas_pendientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indices de la tabla `notas_trimestres`
--
ALTER TABLE `notas_trimestres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario_materia_periodo` (`id_usuario`,`id_materia`,`id_periodo`),
  ADD KEY `idx_trimestre_num` (`trimestre_num`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudiante_id` (`estudiante_id`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `banco_id` (`banco_id`);

--
-- Indices de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `preinscripcion`
--
ALTER TABLE `preinscripcion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_idusuario` (`idusuario`),
  ADD UNIQUE KEY `idx_username` (`username`);

--
-- Indices de la tabla `prelaciones`
--
ALTER TABLE `prelaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `id_materia` (`id_materia`),
  ADD KEY `id_prerequisito` (`id_prerequisito`);

--
-- Indices de la tabla `relacion_cursos`
--
ALTER TABLE `relacion_cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `respaldos_descargas`
--
ALTER TABLE `respaldos_descargas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `revision_mensajes`
--
ALTER TABLE `revision_mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id_seccion`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `id_trayecto` (`id_trayecto`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `secretaria_config`
--
ALTER TABLE `secretaria_config`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `secretaria_configuracion_carga`
--
ALTER TABLE `secretaria_configuracion_carga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_trimestre` (`trimestre_num`);

--
-- Indices de la tabla `secretaria_cupos`
--
ALTER TABLE `secretaria_cupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_carrera_turno` (`carrera_id`,`turno`),
  ADD KEY `idx_carrera` (`carrera_id`);

--
-- Indices de la tabla `seguridad_bloqueos`
--
ALTER TABLE `seguridad_bloqueos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_activo` (`ip`,`activo`),
  ADD KEY `idx_email_activo` (`email`,`activo`);

--
-- Indices de la tabla `seguridad_intentos`
--
ALTER TABLE `seguridad_intentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip` (`ip`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_fecha` (`fecha`);

--
-- Indices de la tabla `seguridad_rps`
--
ALTER TABLE `seguridad_rps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_fecha` (`ip`,`fecha`);

--
-- Indices de la tabla `seguridad_sistema`
--
ALTER TABLE `seguridad_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `seguridad_tokens_invalidos`
--
ALTER TABLE `seguridad_tokens_invalidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip` (`ip`);

--
-- Indices de la tabla `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tenencia_vivienda`
--
ALTER TABLE `tenencia_vivienda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_horario`
--
ALTER TABLE `tipos_horario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_cedula`
--
ALTER TABLE `tipo_cedula`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_formacion`
--
ALTER TABLE `tipo_formacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_horario_personal`
--
ALTER TABLE `tipo_horario_personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_horario` (`id_usuario`,`id_tipo_horario`),
  ADD KEY `id_tipo_horario` (`id_tipo_horario`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipopago` (`tipopago`);

--
-- Indices de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `titulos_obtenidos`
--
ALTER TABLE `titulos_obtenidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `titulo_materia`
--
ALTER TABLE `titulo_materia`
  ADD PRIMARY KEY (`id_relacion`),
  ADD UNIQUE KEY `id_titulo` (`id_titulo`,`id_materia`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `trayectos`
--
ALTER TABLE `trayectos`
  ADD PRIMARY KEY (`id_trayecto`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `idusuario` (`idusuario`),
  ADD UNIQUE KEY `idx_users_idusuario` (`idusuario`),
  ADD KEY `id` (`id`),
  ADD KEY `carrera` (`carrera`),
  ADD KEY `carrera_di` (`carrera_di`);

--
-- Indices de la tabla `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`);

--
-- Indices de la tabla `user_user_types`
--
ALTER TABLE `user_user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`user_type_id`),
  ADD KEY `user_type_id` (`user_type_id`);

--
-- Indices de la tabla `usuarios_cursos`
--
ALTER TABLE `usuarios_cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `version_materia`
--
ALTER TABLE `version_materia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_version` (`id_version`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `aprobaciones_avance`
--
ALTER TABLE `aprobaciones_avance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1027;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `carrera_materia`
--
ALTER TABLE `carrera_materia`
  MODIFY `id_relacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `carrera_versiones`
--
ALTER TABLE `carrera_versiones`
  MODIFY `id_version` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `id_ciudad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=523;

--
-- AUTO_INCREMENT de la tabla `contenido`
--
ALTER TABLE `contenido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `control_avance_trayecto`
--
ALTER TABLE `control_avance_trayecto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `docente_materia`
--
ALTER TABLE `docente_materia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `docente_seccion`
--
ALTER TABLE `docente_seccion`
  MODIFY `id_docente_seccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `estado_civil`
--
ALTER TABLE `estado_civil`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estudiante_materias`
--
ALTER TABLE `estudiante_materias`
  MODIFY `id_inscripcion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `evaluacion`
--
ALTER TABLE `evaluacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `graduados`
--
ALTER TABLE `graduados`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `historial_cambios_notas`
--
ALTER TABLE `historial_cambios_notas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mallas`
--
ALTER TABLE `mallas`
  MODIFY `id_malla` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `malla_materia`
--
ALTER TABLE `malla_materia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `mensajeria`
--
ALTER TABLE `mensajeria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=463;

--
-- AUTO_INCREMENT de la tabla `nombre_curso`
--
ALTER TABLE `nombre_curso`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas_definitivas`
--
ALTER TABLE `notas_definitivas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT de la tabla `notas_pendientes`
--
ALTER TABLE `notas_pendientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=662;

--
-- AUTO_INCREMENT de la tabla `notas_trimestres`
--
ALTER TABLE `notas_trimestres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1139;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id_periodo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `preinscripcion`
--
ALTER TABLE `preinscripcion`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `prelaciones`
--
ALTER TABLE `prelaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `relacion_cursos`
--
ALTER TABLE `relacion_cursos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `respaldos_descargas`
--
ALTER TABLE `respaldos_descargas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `revision_mensajes`
--
ALTER TABLE `revision_mensajes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `secretaria_configuracion_carga`
--
ALTER TABLE `secretaria_configuracion_carga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `secretaria_cupos`
--
ALTER TABLE `secretaria_cupos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT de la tabla `seguridad_bloqueos`
--
ALTER TABLE `seguridad_bloqueos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `seguridad_intentos`
--
ALTER TABLE `seguridad_intentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `seguridad_rps`
--
ALTER TABLE `seguridad_rps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `seguridad_sistema`
--
ALTER TABLE `seguridad_sistema`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `seguridad_tokens_invalidos`
--
ALTER TABLE `seguridad_tokens_invalidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `status`
--
ALTER TABLE `status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tenencia_vivienda`
--
ALTER TABLE `tenencia_vivienda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_horario`
--
ALTER TABLE `tipos_horario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_cedula`
--
ALTER TABLE `tipo_cedula`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_formacion`
--
ALTER TABLE `tipo_formacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_horario_personal`
--
ALTER TABLE `tipo_horario_personal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `tipo_vivienda`
--
ALTER TABLE `tipo_vivienda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `titulos`
--
ALTER TABLE `titulos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT de la tabla `titulos_obtenidos`
--
ALTER TABLE `titulos_obtenidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `titulo_materia`
--
ALTER TABLE `titulo_materia`
  MODIFY `id_relacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT de la tabla `trayectos`
--
ALTER TABLE `trayectos`
  MODIFY `id_trayecto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2637;

--
-- AUTO_INCREMENT de la tabla `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `user_user_types`
--
ALTER TABLE `user_user_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_cursos`
--
ALTER TABLE `usuarios_cursos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `version_materia`
--
ALTER TABLE `version_materia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3677;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aprobaciones_avance`
--
ALTER TABLE `aprobaciones_avance`
  ADD CONSTRAINT `aprobaciones_avance_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `aprobaciones_avance_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  ADD CONSTRAINT `aprobaciones_avance_ibfk_3` FOREIGN KEY (`aprobado_por`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrera_materia`
--
ALTER TABLE `carrera_materia`
  ADD CONSTRAINT `carrera_materia_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`) ON DELETE CASCADE,
  ADD CONSTRAINT `carrera_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carrera_versiones`
--
ALTER TABLE `carrera_versiones`
  ADD CONSTRAINT `carrera_versiones_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ciudades`
--
ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `control_avance_trayecto`
--
ALTER TABLE `control_avance_trayecto`
  ADD CONSTRAINT `control_avance_trayecto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `control_avance_trayecto_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  ADD CONSTRAINT `control_avance_trayecto_ibfk_3` FOREIGN KEY (`aprobado_por`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `docente_materia`
--
ALTER TABLE `docente_materia`
  ADD CONSTRAINT `docente_materia_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `docente_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Filtros para la tabla `docente_seccion`
--
ALTER TABLE `docente_seccion`
  ADD CONSTRAINT `docente_seccion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `docente_seccion_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`),
  ADD CONSTRAINT `docente_seccion_ibfk_3` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Filtros para la tabla `estudiante_materias`
--
ALTER TABLE `estudiante_materias`
  ADD CONSTRAINT `estudiante_materias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `estudiante_materias_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  ADD CONSTRAINT `estudiante_materias_ibfk_3` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`),
  ADD CONSTRAINT `estudiante_materias_ibfk_4` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id_periodo`);

--
-- Filtros para la tabla `estudiante_seccion`
--
ALTER TABLE `estudiante_seccion`
  ADD CONSTRAINT `estudiante_seccion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `estudiante_seccion_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

--
-- Filtros para la tabla `graduados`
--
ALTER TABLE `graduados`
  ADD CONSTRAINT `graduados_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `graduados_ibfk_2` FOREIGN KEY (`id_admin_graduacion`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `graduados_ibfk_3` FOREIGN KEY (`id_admin_entrega_titulo`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `historial_cambios_notas`
--
ALTER TABLE `historial_cambios_notas`
  ADD CONSTRAINT `historial_cambios_notas_ibfk_1` FOREIGN KEY (`id_nota`) REFERENCES `notas_definitivas` (`id`),
  ADD CONSTRAINT `historial_cambios_notas_ibfk_2` FOREIGN KEY (`id_nota_trimestre`) REFERENCES `notas_trimestres` (`id`);

--
-- Filtros para la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_docente_seccion`) REFERENCES `docente_seccion` (`id_docente_seccion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mallas`
--
ALTER TABLE `mallas`
  ADD CONSTRAINT `mallas_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`) ON DELETE CASCADE;

--
-- Filtros para la tabla `malla_materia`
--
ALTER TABLE `malla_materia`
  ADD CONSTRAINT `malla_materia_ibfk_1` FOREIGN KEY (`id_malla`) REFERENCES `mallas` (`id_malla`) ON DELETE CASCADE,
  ADD CONSTRAINT `malla_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mensajeria`
--
ALTER TABLE `mensajeria`
  ADD CONSTRAINT `mensajeria_ibfk_1` FOREIGN KEY (`id_usuario_remitente`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mensajeria_ibfk_2` FOREIGN KEY (`id_usuario_destinatario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notas_definitivas`
--
ALTER TABLE `notas_definitivas`
  ADD CONSTRAINT `notas_definitivas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notas_definitivas_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  ADD CONSTRAINT `notas_definitivas_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id_periodo`),
  ADD CONSTRAINT `notas_definitivas_ibfk_4` FOREIGN KEY (`id_admin_aprobador`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notas_definitivas_ibfk_5` FOREIGN KEY (`id_docente`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `notas_pendientes`
--
ALTER TABLE `notas_pendientes`
  ADD CONSTRAINT `notas_pendientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notas_pendientes_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  ADD CONSTRAINT `notas_pendientes_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id_periodo`),
  ADD CONSTRAINT `notas_pendientes_ibfk_4` FOREIGN KEY (`id_docente`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`);

--
-- Filtros para la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD CONSTRAINT `parroquias_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `prelaciones`
--
ALTER TABLE `prelaciones`
  ADD CONSTRAINT `prelaciones_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`) ON DELETE CASCADE,
  ADD CONSTRAINT `prelaciones_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE,
  ADD CONSTRAINT `prelaciones_ibfk_3` FOREIGN KEY (`id_prerequisito`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE;

--
-- Filtros para la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD CONSTRAINT `secciones_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  ADD CONSTRAINT `secciones_ibfk_2` FOREIGN KEY (`id_trayecto`) REFERENCES `trayectos` (`id_trayecto`),
  ADD CONSTRAINT `secciones_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_academicos` (`id_periodo`);

--
-- Filtros para la tabla `tipo_horario_personal`
--
ALTER TABLE `tipo_horario_personal`
  ADD CONSTRAINT `tipo_horario_personal_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tipo_horario_personal_ibfk_2` FOREIGN KEY (`id_tipo_horario`) REFERENCES `tipos_horario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `titulos_obtenidos`
--
ALTER TABLE `titulos_obtenidos`
  ADD CONSTRAINT `titulos_obtenidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `titulo_materia`
--
ALTER TABLE `titulo_materia`
  ADD CONSTRAINT `titulo_materia_ibfk_1` FOREIGN KEY (`id_titulo`) REFERENCES `titulos` (`id`),
  ADD CONSTRAINT `titulo_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`carrera`) REFERENCES `carreras` (`id_carrera`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`carrera_di`) REFERENCES `carreras` (`id_carrera`);

--
-- Filtros para la tabla `user_user_types`
--
ALTER TABLE `user_user_types`
  ADD CONSTRAINT `user_user_types_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_user_types_ibfk_2` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`);

--
-- Filtros para la tabla `version_materia`
--
ALTER TABLE `version_materia`
  ADD CONSTRAINT `version_materia_ibfk_1` FOREIGN KEY (`id_version`) REFERENCES `carrera_versiones` (`id_version`) ON DELETE CASCADE,
  ADD CONSTRAINT `version_materia_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
