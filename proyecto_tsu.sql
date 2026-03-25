-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 25-03-2026 a las 16:26:22
-- Versión del servidor: 8.0.45-0ubuntu0.24.04.1
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
  `id_user` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `first_name` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `numero` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
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
  `motivo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` bigint NOT NULL,
  `usuario_id` int NOT NULL,
  `accion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `tabla_afectada` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `registro_id` int DEFAULT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valores_antiguos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci,
  `valores_nuevos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci,
  `ip_origen` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci,
  `modulo_sistema` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci
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
(592, 2, 'LOGIN', 'users', 2, '2026-03-25 12:26:04', NULL, '{\"username\":\"V-12345678\"}', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'Autenticación', 'Inicio de sesión exitoso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int NOT NULL,
  `nave` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `aula` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL
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
  `nombre_banco` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
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
  `status` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `admin` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `concepto` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int NOT NULL,
  `nombre_carrera` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `cod_carrera` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  `duracion_semestres` int DEFAULT NULL,
  `titulo_otorga` varchar(80) CHARACTER SET utf32 COLLATE utf32_spanish2_ci DEFAULT NULL,
  `otro_titulo` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish2_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf32 COLLATE utf32_spanish2_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_formacion` enum('PNF','PTF') CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL DEFAULT 'PNF'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`, `cod_carrera`, `activa`, `duracion_semestres`, `titulo_otorga`, `otro_titulo`, `descripcion`, `created_at`, `tipo_formacion`) VALUES
(0, 'No Especificado', 'NES', 1, 0, 'Ninguno', NULL, 'Carrera genérica para docentes sin asignación específica', '2025-08-01 22:39:06', ''),
(1, 'Informatica', '14232', 1, 8, 'TSU Informatica', 'Ing. Informatica', '0', '2025-06-02 14:08:44', 'PNF'),
(2, 'Turismo', '13569', 1, 8, 'TSU turismo', '', '0', '2025-06-16 18:07:13', 'PNF'),
(5, 'Logistica y Distribucion', '14231', 1, 4, 'Licenciado en Distribucion y Logistica', 'oooo', '0', '2025-08-10 22:26:32', 'PNF'),
(14, 'Mecanica', '13351', 1, 8, 'TSU Mecanica', 'Ing. Mecanica', '0', '2005-01-13 04:00:00', 'PTF'),
(15, 'Mecanica Automotriz', '12932', 1, 6, 'TSU Mecanica Automotriz', '', '0', '2026-01-28 04:00:00', 'PTF');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `seccion` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
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
  `motivo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
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
(18, 2, 35, '2026-02-23 09:25:52');

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
(12, 2, 10, 9, '2025-08-22 20:44:08', 1),
(14, 2, 11, 15, '2025-08-23 00:18:06', 1),
(17, 2585, 10, 10, '2025-10-23 18:05:46', 1),
(23, 4, 10, 11, '2026-01-26 16:29:51', 1),
(24, 2, 12, 35, '2026-02-23 13:50:33', 1);

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
  `estado_civil` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
-- Estructura de tabla para la tabla `estudiante_seccion`
--

CREATE TABLE `estudiante_seccion` (
  `id_usuario` int NOT NULL,
  `id_seccion` int NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `estatus` enum('activo','retirado','aprobado','reprobado') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiante_seccion`
--

INSERT INTO `estudiante_seccion` (`id_usuario`, `id_seccion`, `fecha_inscripcion`, `estatus`) VALUES
(5, 10, '2025-08-13', 'activo'),
(2379, 10, '2025-08-13', 'activo'),
(2450, 11, '2025-08-22', 'activo'),
(2451, 10, '2025-08-13', 'activo'),
(2454, 11, '2025-08-22', 'activo'),
(2455, 10, '2025-08-13', 'retirado'),
(2459, 10, '2025-08-13', 'activo'),
(2461, 10, '2025-08-13', 'activo'),
(2462, 11, '2025-08-22', 'activo'),
(2464, 11, '2025-08-22', 'activo'),
(2465, 10, '2025-08-13', 'activo'),
(2471, 10, '2025-08-13', 'activo'),
(2473, 10, '2025-08-13', 'activo'),
(2476, 11, '2025-08-22', 'activo'),
(2529, 10, '2025-08-13', 'activo'),
(2530, 11, '2025-08-22', 'activo'),
(2538, 11, '2025-08-22', 'activo'),
(2539, 10, '2025-08-13', 'activo'),
(2540, 11, '2025-08-22', 'activo'),
(2541, 10, '2025-08-13', 'activo'),
(2545, 10, '2025-08-13', 'activo'),
(2550, 11, '2025-08-22', 'activo'),
(2553, 10, '2025-08-13', 'activo'),
(2554, 11, '2025-08-22', 'activo'),
(2557, 10, '2025-08-13', 'activo'),
(2560, 11, '2025-08-13', 'retirado'),
(2562, 11, '2025-08-22', 'activo'),
(2564, 11, '2025-08-22', 'activo'),
(2565, 10, '2025-08-13', 'activo'),
(2566, 11, '2025-08-22', 'activo'),
(2567, 10, '2025-08-13', 'activo'),
(2568, 11, '2025-08-22', 'activo'),
(2570, 11, '2025-08-22', 'retirado'),
(2571, 10, '2025-08-13', 'activo'),
(2597, 12, '2026-02-23', 'activo'),
(2598, 12, '2026-02-23', 'activo'),
(2599, 12, '2026-02-23', 'activo'),
(2600, 12, '2026-02-23', 'activo'),
(2602, 12, '2026-02-23', 'activo'),
(2616, 12, '2026-02-23', 'activo'),
(2617, 12, '2026-02-23', 'activo'),
(2618, 12, '2026-02-23', 'activo'),
(2619, 12, '2026-02-23', 'activo'),
(2620, 12, '2026-02-23', 'activo');

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
  `genero` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL
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
  `acta_entrega` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci,
  `estado` enum('cumple_requisitos','graduado','titulo_entregado') CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT 'cumple_requisitos',
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
  `id_nota` int NOT NULL,
  `trayecto` int NOT NULL,
  `nota_anterior` decimal(4,2) DEFAULT NULL,
  `nota_nueva` decimal(4,2) NOT NULL,
  `justificacion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `id_admin` int NOT NULL,
  `fecha_cambio` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `historial_cambios_notas`
--

INSERT INTO `historial_cambios_notas` (`id`, `id_nota`, `trayecto`, `nota_anterior`, `nota_nueva`, `justificacion`, `id_admin`, `fecha_cambio`) VALUES
(1, 194, 0, 13.00, 15.00, 'lol', 2, '2025-11-10 10:33:06'),
(2, 194, 0, 15.00, 18.00, 'prueba', 2, '2025-11-10 12:23:45'),
(3, 194, 0, 18.00, 14.00, 'otra prueba', 2, '2025-11-10 12:57:49'),
(4, 194, 0, 14.00, 15.00, 'prueba para auditoria', 2, '2025-11-10 13:03:02');

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
  `aula` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL
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
(218, 7, 5, '08:00:00', '09:00:00', 'B - 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int NOT NULL,
  `ingreso` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `codigo_malla` varchar(100) NOT NULL,
  `anio` int NOT NULL,
  `descripcion` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `cod_materia` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `pnf_ptf` varchar(3) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `nombre_materia` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
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
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
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
(29, 5, 4, 'prueba de vocero', 'prueba', '2026-03-25 12:11:33', 1, 0, 0, 0, 0);

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
  `titulo` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `sub_titulo` varchar(500) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `contenido` text CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `ponente1` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `cedula1` varchar(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `ponente2` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `cedula2` varchar(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `lugar` varchar(25) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `horas` varchar(3) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
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
  `soporte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `tipo_archivo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
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
  `estado` enum('pendiente','aprobada','rechazada','en revision') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT 'en revision',
  `soporte` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'Ruta o nombre del archivo de imagen de soporte',
  `tipo_archivo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL COMMENT 'jpg, png, jpeg, etc',
  `fecha_subida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `notas_pendientes`
--

INSERT INTO `notas_pendientes` (`id`, `id_usuario`, `id_materia`, `id_periodo`, `id_docente`, `trayecto_0`, `trayecto_1`, `trayecto_2`, `trayecto_3`, `trayecto_4`, `fecha_envio`, `estado`, `soporte`, `tipo_archivo`, `fecha_subida`) VALUES
(567, 2459, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(568, 2545, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(569, 2451, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(570, 2529, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(571, 2471, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(572, 2565, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(573, 2541, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(574, 2465, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(575, 2553, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(576, 2567, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(577, 2473, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(578, 2379, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(579, 2539, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(580, 2461, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(581, 2571, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(582, 2557, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(583, 5, 5, 2, 4, 17, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(584, 2455, 5, 2, 4, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:18:41', 'aprobada', NULL, NULL, NULL),
(585, 2459, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(586, 2545, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(587, 2451, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(588, 2529, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(589, 2471, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(590, 2565, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(591, 2541, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(592, 2465, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(593, 2553, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(594, 2567, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(595, 2473, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(596, 2379, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(597, 2539, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(598, 2461, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(599, 2571, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(600, 2557, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(601, 5, 9, 2, 2, 19, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(602, 2455, 9, 2, 2, 1, NULL, NULL, NULL, NULL, '2025-12-04 12:20:06', 'aprobada', NULL, NULL, NULL),
(621, 2560, 15, 2, 2, 16, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(622, 2570, 15, 2, 2, 20, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(623, 2462, 15, 2, 2, 15, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(624, 2540, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(625, 2554, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(626, 2476, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(627, 2564, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(628, 2450, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(629, 2530, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(630, 2538, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(631, 2464, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(632, 2562, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(633, 2566, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(634, 2454, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(635, 2550, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05'),
(636, 2568, 15, 2, 2, 1, NULL, NULL, NULL, NULL, '2026-01-28 10:24:05', 'aprobada', 'soporte_697a1c05ad0270.30546288_1769610245.pdf', 'pdf', '2026-01-28 10:24:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int NOT NULL,
  `estudiante_id` int DEFAULT NULL,
  `tipo_pago` enum('inscripcion','reincorporacion_estudio_expediente','cambio_programa','cambio_sede','inscripcion_pasantias_practica_profesional','expedicion_constancia_certificada_notas','expedicion_constancia_simple_notas','expedicion_constancia_buena_conducta','expedicion_constancia_culminacion_academica','expedicion_constancia_estudios','expedicion_constancia_inscripcion','expedicion_constancia_servicio_comunitario','carnet_estudiantil','uniforme_franela_estudiantil','certificado_titulo','autenticacion_titulo','pensum_estudios_certificados','programas_analiticos_vigencia_programas','expedicion_constancia_modalidad_estudios','certificacion_acta_grado','grado_titulo_medalla_notas_certificadas_ubicacion_rango_buena_conducta_servicio_comunitario','derecho_grado','certificacion_saberes','examen_suficiencia','examen_extraordinario','cursos','talleres','diplomado','especializacion','maestria','otro') CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `otro_concepto` varchar(100) CHARACTER SET utf32 COLLATE utf32_spanish2_ci DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `banco_id` int DEFAULT NULL,
  `fecha_pago` datetime DEFAULT CURRENT_TIMESTAMP,
  `observaciones` text CHARACTER SET utf32 COLLATE utf32_spanish2_ci,
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
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id_periodo` int NOT NULL,
  `nombre_periodo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
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
(5, '2026-1', '2026-01-16', '2026-03-16', 1, '2026-01-16 17:01:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prelaciones`
--

CREATE TABLE `prelaciones` (
  `id` int NOT NULL,
  `id_carrera` int NOT NULL,
  `id_materia` int NOT NULL,
  `id_prerequisito` int NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `codigo` varchar(10) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respaldos_descargas`
--

CREATE TABLE `respaldos_descargas` (
  `id` int NOT NULL,
  `usuario` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_descarga` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revision_mensajes`
--

CREATE TABLE `revision_mensajes` (
  `id` int NOT NULL,
  `id_usuario` int NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(15) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id_seccion` int NOT NULL,
  `codigo_seccion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_carrera` int NOT NULL,
  `id_trayecto` int NOT NULL,
  `id_periodo` int NOT NULL,
  `capacidad_maxima` int NOT NULL,
  `capacidad_minima` int DEFAULT '10',
  `aula_asignada` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `horario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci,
  `estatus` enum('activa','inactiva','completa') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT 'activa',
  `inicia` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_seccion`, `codigo_seccion`, `id_carrera`, `id_trayecto`, `id_periodo`, `capacidad_maxima`, `capacidad_minima`, `aula_asignada`, `horario`, `estatus`, `inicia`, `created_at`) VALUES
(9, '1-70', 1, 1, 1, 30, 10, NULL, NULL, 'inactiva', NULL, '2025-07-25 21:05:43'),
(10, '1-70', 1, 2, 5, 40, 10, NULL, '{\"lunes\":[\"07:00\",\"11:30\"],\"martes\":null,\"miercoles\":null,\"jueves\":null,\"viernes\":null}', 'activa', '2026-08-02 12:00:00', '2025-07-31 22:15:49'),
(11, '1-80', 2, 1, 2, 30, 10, NULL, NULL, 'activa', '2025-08-14 12:00:00', '2025-08-05 17:14:39'),
(12, '4-80', 5, 1, 5, 30, 10, NULL, NULL, 'activa', '2026-02-23 07:00:00', '2026-02-23 13:27:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `status`
--

CREATE TABLE `status` (
  `id` int NOT NULL,
  `status` varchar(10) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `tenencia` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `nombre` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
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
  `tipo` varchar(2) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL
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
  `tipopago` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL
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
  `vivienda` varchar(20) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci
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
  `nombre` varchar(255) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `titulo_obtenido` varchar(255) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL,
  `instituto` varchar(255) CHARACTER SET utf32 COLLATE utf32_spanish2_ci NOT NULL
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
  `nombre_trayecto` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci
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
  `idusuario` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `tlf` varchar(11) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `cel` varchar(11) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `direccion` varchar(300) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `ciudad` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `estado` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `municipio` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `parroquia` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `etnia` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'Ninguna',
  `casaapto` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `punto_referencia` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `grupo_familiar` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `acargo_usted` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `fuente_ingresos` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `tipo_vivienda` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `tenencia_vivienda` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `enfermedad` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `discapacidad` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `titulos` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `institutos` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT 'No especificado',
  `potencialidades` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_act` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '1',
  `user_type` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `api_key` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `carrera` int DEFAULT NULL,
  `carrera_di` int DEFAULT NULL,
  `genero` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `edo_civil` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `num_telf_opc` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `foto_perfil` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `usuario` int NOT NULL,
  `estudiante` int NOT NULL,
  `docente` int NOT NULL,
  `admin` int NOT NULL,
  `super_user` int NOT NULL,
  `editar_user` int NOT NULL,
  `editar_nota` int NOT NULL,
  `editar_acceso` int NOT NULL,
  `editar_valores` int NOT NULL,
  `editar_estudiante` int NOT NULL,
  `agregar_estudiante` int NOT NULL,
  `agregar_docente` int NOT NULL,
  `editar_docente` int NOT NULL,
  `agregar_carrera` int NOT NULL,
  `agregar_materia` int NOT NULL,
  `editar_materia` int NOT NULL,
  `pagos` int DEFAULT NULL,
  `auditoria` int DEFAULT NULL,
  `secciones` int DEFAULT NULL,
  `rela_materia_carrera` int DEFAULT NULL,
  `periodos_academicos` int DEFAULT NULL,
  `asig_secciones` int DEFAULT NULL,
  `asig_cursos` int DEFAULT NULL,
  `horarios` int DEFAULT NULL,
  `gestion_director_carrera` int DEFAULT NULL,
  `notas_cargadas` int DEFAULT NULL,
  `consultar_notas` int DEFAULT NULL,
  `consultar_notas_pasadas` int DEFAULT NULL,
  `tipos_pago` int DEFAULT NULL,
  `tipos_horario` int DEFAULT NULL,
  `horario_personal` int DEFAULT NULL,
  `respaldo_bd` int DEFAULT NULL,
  `gestionar_carrera` int DEFAULT NULL,
  `gestion_periodo_academico` int DEFAULT NULL,
  `gestion_asig_cursos` int DEFAULT NULL,
  `gestion_horario` int DEFAULT NULL,
  `titulos_re_materia` int DEFAULT NULL,
  `grado` int DEFAULT NULL,
  `gestion_grado` int DEFAULT NULL,
  `vocero` tinyint(1) DEFAULT NULL,
  `visita` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `idusuario`, `nombre`, `username`, `email`, `tlf`, `cel`, `direccion`, `ciudad`, `estado`, `municipio`, `parroquia`, `etnia`, `casaapto`, `punto_referencia`, `grupo_familiar`, `acargo_usted`, `fuente_ingresos`, `tipo_vivienda`, `tenencia_vivienda`, `enfermedad`, `discapacidad`, `titulos`, `institutos`, `potencialidades`, `fecha_ingreso`, `fecha_act`, `status`, `user_type`, `password`, `api_key`, `carrera`, `carrera_di`, `genero`, `edo_civil`, `fecha_nac`, `num_telf_opc`, `foto_perfil`, `usuario`, `estudiante`, `docente`, `admin`, `super_user`, `editar_user`, `editar_nota`, `editar_acceso`, `editar_valores`, `editar_estudiante`, `agregar_estudiante`, `agregar_docente`, `editar_docente`, `agregar_carrera`, `agregar_materia`, `editar_materia`, `pagos`, `auditoria`, `secciones`, `rela_materia_carrera`, `periodos_academicos`, `asig_secciones`, `asig_cursos`, `horarios`, `gestion_director_carrera`, `notas_cargadas`, `consultar_notas`, `consultar_notas_pasadas`, `tipos_pago`, `tipos_horario`, `horario_personal`, `respaldo_bd`, `gestionar_carrera`, `gestion_periodo_academico`, `gestion_asig_cursos`, `gestion_horario`, `titulos_re_materia`, `grado`, `gestion_grado`, `vocero`, `visita`) VALUES
(1, 'J-294444890', 'J.E Suministros y Mas, C.A.', 'jesuministrosymas', 'info@jesuministrosymas.com.ve', '02423644304', '0416777777', 'San Esteban Urb, avenida principal casa 23', 'Puerto Cabello', 'Carabobo', '', '', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-10-23 04:00:00', '2025-10-23 17:52:50', 1, 'admin', 'f51ac20f477ebab234109d3865ff8ff0', '', 1, 0, 'masculino', '', NULL, '', NULL, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2, '12345678', 'PRUEBA', 'V-12345678', 'herrejose@gmail.com', '02423644304', '04124372322', 'DEBE COMPLETAR', '123', '7', '87', '278', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2018-09-15 04:49:29', '2026-03-02 14:41:43', 1, 'admin', '$2y$10$kM/1lGzaZGYo/T94hI12d.wEfFl.QVq0Mj61v8PuySCF1KhWxl/jy', 'API_LIMP_67cf30d4ae5de', 1, 1, 'masculino', NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(3, '15949430', 'JOSE HERRERA', 'V-15949430', 'jose@jesuministrosymas.com.ve', '04141448515', '02436721452', 'Maracay', 'Maracay', 'Aragua', 'MBI', 'Caña de Azucar', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2018-09-27 03:10:20', '2025-10-01 18:20:39', 1, 'admin', '2ee3c27d9ea2416f9279ec18117311a1', '', 1, 0, NULL, NULL, NULL, NULL, NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(4, '123456789', 'hector', 'hero', 'hectorlamaquina14@gmail.com', '0412555555', '', '', '', '', NULL, NULL, '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-17 14:47:06', '2025-10-22 15:38:24', 1, 'docente', '$2y$10$cpzUQk3toJ9QIrP30CHBreyr/AbJQP2oC5GBhSpO9fZL7fIUkN2nu', '', 1, 2, 'masculino', NULL, NULL, NULL, NULL, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(5, 'V-30692052', 'Hector', 'heroestudiante', 'heroestudiante@gmail.com', '0412555555', '', 'lol', NULL, 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'wayoyo', '03', 'frente al parque', '4', '2', 'Salario', 'Urbana', 'familiar', 'Ninguna', 'Ninguna', 'Bachiller', 'U.E Manuel Gual', '', '2025-06-17 16:07:22', '2026-03-02 14:41:56', 1, 'estudiante', '$2y$10$q3Jrf5ys6uo9CrkYscOfw.L5iydeKL94foqwatyGE96LFJGiLbobG', '', 1, 0, 'Masculino', 'Soltero/a', '2004-04-14', '04124122996', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, NULL),
(2372, 'V-28596315', 'Manuel Aponte Diaz Romero', 'V-154545454545', 'manuel@gmail.com', '04125555557', '04167777777', 'porai siuuuu', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-05-13 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '6917fc789d762d53c70bec13497c6921d189e0930ff7d3d99fe7a23d9fbd6884', NULL, 1, 0, 'Masculino', 'Casado/a', '2000-07-22', '04167777777', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2377, 'V-11111111', 'Juan Sambrano', '12345610', 'juansambrano@gmail.com', '0412555555', '0416777777', 'jguyhfyt', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-07-03 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', '1bbd886460827015e5d605ed44252251', NULL, 1, 0, 'Masculino', 'Soltero/a', '2000-07-19', '4568426513', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2378, 'V-29565454', 'Sara Miller', 'sara.miller', 'saramiller@gmail.com', '0412555777', '0416777555', 'hgytdrrt', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-19 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '29b3b2d836fbea2589c7383ae8bba39f', NULL, 1, 0, 'Femenino', 'Soltero/a', '2003-06-19', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2379, 'V-30762211', 'Eliud Miguel Mendoza Perez', 'eliud.miguel.mendoza.perez', 'eliud@gmail.com', '7525254542', '5542643534', 'hgfsvsfr', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', '', '', '0', '0', '', '', '0', '', '', '', '', '', '2025-06-19 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '478727529f93cfe6013d31fcc9773633', NULL, 1, 0, 'Masculino', 'Soltero/a', '2004-10-06', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2449, '1', 'María González', 'maría.gonzález', 'maria.gonzalez@example.com', '2125551234', '4125551234', 'Calle 1 #23', 'Caracas', 'Distrito Capital', 'Libertador', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-01-15 04:00:00', '2025-10-23 15:10:03', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 'Soltera', '1995-05-20', '2125551235', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2450, '2', 'Carlos López', 'carlos.lópez', 'carlos.lopez@example.com', '2125552345', '4125552345', 'Avenida 2 #45', 'Caracas', 'Distrito Capital', 'Libertador', 'San Agustín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-02-10 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Casado', '1990-08-15', '2125552346', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2451, '3', 'Ana Rodríguez', 'ana.rodríguez', 'ana.rodriguez@example.com', '2125553456', '4125553456', 'Calle 3 #67', 'Valencia', 'Carabobo', 'Valencia', 'Naguanagua', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-03-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Divorciada', '1988-11-25', '2125553457', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2452, '4', 'Luis Pérez', 'luis.pérez', 'luis.perez@example.com', '2125554567', '4125554567', 'Avenida 4 #89', 'Maracaibo', 'Zulia', 'Maracaibo', 'Coquivacoa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-04-20 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1993-07-10', '2125554568', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2453, '5', 'Sofía Martínez', 'sofía.martínez', 'sofia.martinez@example.com', '2125555678', '4125555678', 'Calle 5 #12', 'Barcelona', 'Anzoátegui', 'Simón Bolívar', 'El Carmen', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-05-15 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Casada', '1992-02-28', '2125555679', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2454, '6', 'Jorge Hernández', 'jorge.hernández', 'jorge.hernandez@example.com', '2125556789', '4125556789', 'Avenida 6 #34', 'Barquisimeto', 'Lara', 'Iribarren', 'Concepción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-06-10 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1994-09-15', '2125556790', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2455, '7', 'Isabel Díaz', 'isabel.díaz', 'isabel.diaz@example.com', '2125557890', '4125557890', 'Calle 7 #56', 'Mérida', 'Mérida', 'Libertador', 'Milla', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-07-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Soltera', '1991-12-05', '2125557891', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2456, '8', 'Pablo Sánchez', 'pablo.sánchez', 'pablo.sanchez@example.com', '2125558901', '4125558901', 'Avenida 8 #78', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'San Juan Bautista', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-08-20 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Casado', '1989-04-20', '2125558902', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2457, '9', 'Valeria Ramírez', 'valeria.ramírez', 'valeria.ramirez@example.com', '2125559012', '4125559012', 'Calle 9 #90', 'Ciudad Guayana', 'Bolívar', 'Caroní', 'Unare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-09-15 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Soltera', '1996-01-30', '2125559013', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2458, '10', 'Daniel Torres', 'daniel.torres', 'daniel.torres@example.com', '2125550123', '4125550123', 'Avenida 10 #11', 'Puerto La Cruz', 'Anzoátegui', 'Sotillo', 'Guanta', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-10-10 04:00:00', '2025-10-23 15:10:12', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 'Divorciado', '1990-06-25', '2125550124', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2459, '11', 'Adriana Castro', 'adriana.castro', 'adriana.castro@example.com', '2125551122', '4125551122', 'Calle 11 #22', 'Maracay', 'Aragua', 'Girardot', 'Choroní', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-11-05 04:00:00', '2025-10-23 15:10:20', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 'Casada', '1987-03-15', '2125551123', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2460, '12', 'Roberto Núñez', 'roberto.núñez', 'roberto.nunez@example.com', '2125552233', '4125552233', 'Avenida 12 #33', 'Barinas', 'Barinas', 'Barinas', 'Alto Barinas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2023-12-20 04:00:00', '2025-10-23 15:10:32', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 'Soltero', '1995-10-10', '2125552234', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2461, '13', 'Gabriela Rojas', 'gabriela.rojas', 'gabriela.rojas@example.com', '2125553344', '4125553344', 'Calle 13 #44', 'Los Teques', 'Miranda', 'Guaicaipuro', 'Los Teques', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-01-15 04:00:00', '2025-10-23 15:10:54', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'Femenino', 'Soltera', '1994-07-20', '2125553345', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2462, '14', 'Andrés Mendoza', 'andrés.mendoza', 'andres.mendoza@example.com', '2125554455', '4125554455', 'Avenida 14 #55', 'Punto Fijo', 'Falcón', 'Carirubana', 'Punto Fijo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-02-10 04:00:00', '2025-10-23 15:10:59', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'Masculino', 'Casado', '1991-04-05', '2125554456', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2463, '15', 'Natalia Guzmán', 'natalia.guzmán', 'natalia.guzman@example.com', '2125555566', '4125555566', 'Calle 15 #66', 'Coro', 'Falcón', 'Colina', 'Coro', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-03-05 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Divorciada', '1989-11-30', '2125555567', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2464, '16', 'Fernando Herrera', 'fernando.herrera', 'fernando.herrera@example.com', '2125556677', '4125556677', 'Avenida 16 #77', 'San Fernando', 'Apure', 'San Fernando', 'San Fernando', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-04-20 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1993-08-15', '2125556678', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2465, '17', 'Carolina Silva', 'carolina.silva', 'carolina.silva@example.com', '2125557788', '4125557788', 'Calle 17 #88', 'La Victoria', 'Aragua', 'José Félix Ribas', 'La Victoria', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-05-15 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Casada', '1992-05-20', '2125557789', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2466, '18', 'Ricardo Peña', 'ricardo.peña', 'ricardo.pena@example.com', '2125558899', '4125558899', 'Avenida 18 #99', 'El Tigre', 'Anzoátegui', 'Simón Rodríguez', 'El Tigre', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-06-10 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1996-02-25', '2125558900', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2467, '19', 'Patricia Flores', 'patricia.flores', 'patricia.flores@example.com', '2125559900', '4125559900', 'Calle 19 #00', 'Acarigua', 'Portuguesa', 'Páez', 'Acarigua', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-07-05 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Soltera', '1990-09-10', '2125559901', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2468, '20', 'José Ruiz', 'josé.ruiz', 'jose.ruiz@example.com', '2125550011', '4125550011', 'Avenida 20 #11', 'Valera', 'Trujillo', 'Valera', 'Valera', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-08-20 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Casado', '1988-12-05', '2125550012', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2469, '21', 'Luisa Vargas', 'luisa.vargas', 'luisa.vargas@example.com', '2125551123', '4125551123', 'Calle 21 #22', 'Cabimas', 'Zulia', 'Cabimas', 'Cabimas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-09-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Divorciada', '1994-06-15', '2125551124', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2470, '22', 'Manuel Ortega', 'manuel.ortega', 'manuel.ortega@example.com', '2125552234', '4125552234', 'Avenida 22 #33', 'Carúpano', 'Sucre', 'Bermúdez', 'Carúpano', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-10-10 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1995-03-20', '2125552235', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2471, '23', 'Andrea Medina', 'andrea.medina', 'andrea.medina@example.com', '2125553345', '4125553345', 'Calle 23 #44', 'Porlamar', 'Nueva Esparta', 'Mariño', 'Porlamar', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-11-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Casada', '1991-10-25', '2125553346', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2472, '24', 'Diego Rivas', 'diego.rivas', 'diego.rivas@example.com', '2125554456', '4125554456', 'Avenida 24 #55', 'San Carlos', 'Cojedes', 'San Carlos', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2024-12-20 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1993-07-30', '2125554457', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2473, '25', 'Elena Cordero', 'elena.cordero', 'elena.cordero@example.com', '2125555567', '4125555567', 'Calle 25 #66', 'Tucupita', 'Delta Amacuro', 'Tucupita', 'Tucupita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-01-15 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Soltera', '1996-04-05', '2125555568', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2474, '26', 'Oscar Romero', 'oscar.romero', 'oscar.romero@example.com', '2125556678', '4125556678', 'Avenida 26 #77', 'La Grita', 'Táchira', 'Jáuregui', 'La Grita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-02-10 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Casado', '1989-01-10', '2125556679', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2475, '27', 'Vanessa Gil', 'vanessa.gil', 'vanessa.gil@example.com', '2125557789', '4125557789', 'Calle 27 #88', 'San Felipe', 'Yaracuy', 'San Felipe', 'San Felipe', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-03-05 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Divorciada', '1992-08-15', '2125557790', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2476, '28', 'Arturo Mora', 'arturo.mora', 'arturo.mora@example.com', '2125558890', '4125558890', 'Avenida 28 #99', 'San Juan de los Morros', 'Guárico', 'Roscio', 'San Juan', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-04-20 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1995-05-20', '2125558891', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2477, '29', 'Mariana León', 'mariana.león', 'mariana.leon@example.com', '2125559901', '4125559901', 'Calle 29 #00', 'San Antonio de Los Altos', 'Miranda', 'Los Salias', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-05-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 1, 0, 'F', 'Casada', '1990-12-25', '2125559902', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2478, '30', 'Julio Espinoza', 'julio.espinoza', 'julio.espinoza@example.com', '2125550012', '4125550012', 'Avenida 30 #11', 'El Vigía', 'Mérida', 'Alberto Adriani', 'El Vigía', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2025-06-10 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'd41d8cd98f00b204e9800998ecf8427e', '', 2, 0, 'M', 'Soltero', '1994-09-30', '2125550013', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2527, 'V-15678901', 'Maríaa Gonzálezz', 'maríaa.gonzálezz', 'mgonzalez@example.com', '2125550101', '4125550101', 'Calle 1 #101', 'Caracas', 'Distrito Capital', 'Libertador', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', 'db0789017e0d5a2484886c25c7bbffd1', '', 1, 0, 'F', 'soltera', '2000-05-20', '2125550102', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2528, 'E-20345678', 'Juan Pérez', 'juan.pérez', 'jperez@example.com', '2125550202', '4125550202', 'Avenida 2 #202', 'Caracas', 'Distrito Capital', 'Libertador', 'San Agustín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-10 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '6a37eebd4f766baee264c59ee1bbca02', '', 2, 0, 'M', 'casado', '1999-11-15', '2125550203', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2529, 'V-17432109', 'Anaa Rodríguez', 'anaa.rodríguez', 'arodriguez@example.com', '2125550303', '4125550303', 'Calle 3 #303', 'Valencia', 'Carabobo', 'Valencia', 'San Blas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-20 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '89451e2737f7a3a6c46d060107dc708b', '', 1, 0, 'F', 'soltera', '2001-02-28', '2125550304', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2530, 'E-18765432', 'Carloss López', 'carloss.lópez', 'clopez@example.com', '2125550404', '4125550404', 'Avenida 4 #404', 'Maracaibo', 'Zulia', 'Maracaibo', 'Juana de Ávila', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-05 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'b145ec79b1151099b9570d4e3b29aeca', '', 2, 0, 'M', 'soltero', '2000-07-10', '2125550405', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2531, 'V-23456789', 'Laura Martínez', 'laura.martínez', 'lmartinez@example.com', '2125550505', '4125550505', 'Calle 5 #505', 'Barquisimeto', 'Lara', 'Iribarren', 'Concepción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-12 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '4428c6c474502e61151877825bb41961', '', 1, 0, 'F', 'casada', '1999-09-25', '2125550506', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2532, 'E-19876543', 'Pedro Gómez', 'pedro.gómez', 'pgomez@example.com', '2125550606', '4125550606', 'Avenida 6 #606', 'Maracay', 'Aragua', 'Girardot', 'Choroní', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-18 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'dd26143c452d55054355fdbd5c92e398', '', 2, 0, 'M', 'soltero', '2001-04-05', '2125550607', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2533, 'V-21567890', 'Sofía Hernández', 'sofía.hernández', 'shernandez@example.com', '2125550707', '4125550707', 'Calle 7 #707', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'La Concordia', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-22 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'f5ece76723ac1b6ae3bb9e99bbf26f68', '', 1, 0, 'F', 'soltera', '2000-12-12', '2125550708', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2534, 'E-17654321', 'José Ramírez', 'josé.ramírez', 'jramirez@example.com', '2125550808', '4125550808', 'Avenida 8 #808', 'Barcelona', 'Anzoátegui', 'Simón Bolívar', 'El Carmen', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-30 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'e068ff48f0966deade935517d6b4686a', '', 2, 0, 'M', 'casado', '1999-08-17', '2125550809', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2535, 'V-22345678', 'Isabel Torres', 'isabel.torres', 'itorres@example.com', '2125550909', '4125550909', 'Calle 9 #909', 'Mérida', 'Mérida', 'Libertador', 'Milla', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', '08e0750210f66396eb83957973705aad', '', 1, 0, 'F', 'soltera', '2001-01-30', '2125550910', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2536, 'E-19456789', 'Miguel Díaz', 'miguel.díaz', 'mdiaz@example.com', '2125551010', '4125551010', 'Avenida 10 #1010', 'Ciudad Guayana', 'Bolívar', 'Caroní', 'Unare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '7df8c11bddbef2f19bb65c22b1d6c7e6', '', 2, 0, 'M', 'soltero', '2000-06-22', '2125551011', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2537, 'V-20654321', 'Valentina Rojas', 'valentina.rojas', 'vrojas@example.com', '2125551111', '4125551111', 'Calle 11 #1111', 'Barinas', 'Barinas', 'Barinas', 'Alto Barinas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-20 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '0295896c168f4a350adf4cdf464198d7', '', 1, 0, 'F', 'soltera', '2001-03-14', '2125551112', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2538, 'E-18543210', 'Daniel Castro', 'daniel.castro', 'dcastro@example.com', '2125551212', '4125551212', 'Avenida 12 #1212', 'Coro', 'Falcón', 'Colina', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-07-25 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '8249bfa20206fc926e206d9fad918ca1', '', 2, 0, 'M', 'casado', '1999-10-08', '2125551213', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2539, 'V-21789012', 'Gabriela Mendoza', 'gabriela.mendoza', 'gmendoza@example.com', '2125551313', '4125551313', 'Calle 13 #1313', 'Puerto La Cruz', 'Anzoátegui', 'Sotillo', 'Los Taques', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-10 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'bb1426e76d77f79cc3e5ae1de1e024d6', '', 1, 0, 'F', 'soltera', '2000-09-19', '2125551314', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2540, 'E-19876540', 'Andrés Silva', 'andrés.silva', 'asilva@example.com', '2125551414', '4125551414', 'Avenida 14 #1414', 'San Fernando', 'Apure', 'San Fernando', 'El Recreo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', 'd70e78c0ed5accbec273cea8884902ff', '', 2, 0, 'M', 'soltero', '2001-05-25', '2125551415', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2541, 'V-22456789', 'Carolina Herrera', 'carolina.herrera', 'cherrera@example.com', '2125551515', '4125551515', 'Calle 15 #1515', 'Los Teques', 'Miranda', 'Guaicaipuro', 'Paracotos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-12 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', 'a8567e2d80e3d52ac3c81825d3b211fb', '', 1, 0, 'F', 'casada', '1999-12-30', '2125551516', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2542, 'E-20765432', 'Ricardo Núñez', 'ricardo.núñez', 'rnunez@example.com', '2125551616', '4125551616', 'Avenida 16 #1616', 'Punto Fijo', 'Falcón', 'Carirubana', 'Amuay', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-20 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '5bd83db2c82e0eae9f59a479fc1d1bd1', '', 2, 0, 'M', 'soltero', '2000-08-15', '2125551617', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2543, 'V-19345678', 'Patricia Vargas', 'patricia.vargas', 'pvargas@example.com', '2125551717', '4125551717', 'Calle 17 #1717', 'El Tigre', 'Anzoátegui', 'Simón Rodríguez', 'San José de Guanipa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-18 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '49cfc1380a9ce7380c9cc29813e3b326', '', 1, 0, 'F', 'soltera', '2001-02-10', '2125551718', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2544, 'E-21654321', 'Roberto Medina', 'roberto.medina', 'rmedina@example.com', '2125551818', '4125551818', 'Avenida 18 #1818', 'Cúa', 'Miranda', 'Urdaneta', 'Cúa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-22 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'a52104978231c9a62c4e8a097922ddd9', '', 2, 0, 'M', 'casado', '1999-07-05', '2125551819', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2545, 'V-18456789', 'Adriana Ríos', 'adriana.ríos', 'arios@example.com', '2125551919', '4125551919', 'Calle 19 #1919', 'Ocumare del Tuy', 'Miranda', 'Lander', 'Ocumare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-28 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', 'fe07e07d7cbbff8b42f6544553763d8a', '', 1, 0, 'F', 'soltera', '2000-11-20', '2125551920', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2546, 'E-22567890', 'Fernando Guzmán', 'fernando.guzmán', 'fguzman@example.com', '2125552020', '4125552020', 'Avenida 20 #2020', 'La Victoria', 'Aragua', 'Jose Felix Ribas', 'La Victoria', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-12 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '9d610e830da7d54e118c00518d7a9b64', '', 2, 0, 'M', 'soltero', '2001-04-15', '2125552021', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2547, 'V-19765432', 'Natalia Blanco', 'natalia.blanco', 'nblanco@example.com', '2125552121', '4125552121', 'Calle 21 #2121', 'San Juan de los Morros', 'Guárico', 'Roscio', 'San Juan', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-30 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '24288ed4283a8c2cc350f035337e84a7', '', 1, 0, 'F', 'soltera', '2000-10-05', '2125552122', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2548, 'E-21456789', 'Eduardo Salas', 'eduardo.salas', 'esalas@example.com', '2125552222', '4125552222', 'Avenida 22 #2222', 'Valera', 'Trujillo', 'Valera', 'La Puerta', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-08 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'e275c58706ae71adb5bf8942eca845ba', '', 2, 0, 'M', 'casado', '1999-09-12', '2125552223', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2549, 'V-18654321', 'Mariana Cordero', 'mariana.cordero', 'mcordero@example.com', '2125552323', '4125552323', 'Calle 23 #2323', 'Porlamar', 'Nueva Esparta', 'Maneiro', 'Pampatar', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '11c54f59eb081bcbbcfc65c8bd4772b8', '', 1, 0, 'F', 'soltera', '2001-01-25', '2125552324', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2550, 'E-23567890', 'Jorge Paredes', 'jorge.paredes', 'jparedes@example.com', '2125552424', '4125552424', 'Avenida 24 #2424', 'Carúpano', 'Sucre', 'Bermúdez', 'Carúpano', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-30 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '9929b8ec6b8b4edfe2ab26c25b1e4a58', '', 2, 0, 'M', 'soltero', '2000-07-18', '2125552425', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2551, 'V-20456789', 'Luisa Fuentes', 'luisa.fuentes', 'lfuentes@example.com', '2125552525', '4125552525', 'Calle 25 #2525', 'La Asunción', 'Nueva Esparta', 'Arismendi', 'La Asunción', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-05 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', 'b13e714608fcd3f0fb7f936e1dbd5310', '', 1, 0, 'F', 'casada', '1999-12-08', '2125552526', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2552, 'E-17654320', 'Manuel Alvarado', 'manuel.alvarado', 'malvarado@example.com', '2125552626', '4125552626', 'Avenida 26 #2626', 'Tucupita', 'Delta Amacuro', 'Tucupita', 'Tucupita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-15 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '7e6c056718d8497121412444db238f51', '', 2, 0, 'M', 'soltero', '2001-05-30', '2125552627', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2553, 'V-22678901', 'Daniela Mora', 'daniela.mora', 'dmora@example.com', '2125552727', '4125552727', 'Calle 27 #2727', 'Santa Teresa del Tuy', 'Miranda', 'Independencia', 'Santa Teresa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-25 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', 'a0c79045aa1f687714256873c0d9fbde', '', 1, 0, 'F', 'soltera', '2000-09-15', '2125552728', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2554, 'E-19543210', 'Antonio Peña', 'antonio.peña', 'apena@example.com', '2125552828', '4125552828', 'Avenida 28 #2828', 'San Felipe', 'Yaracuy', 'San Felipe', 'San Felipe', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-05 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '552b566abe41b5f4b7a328382eac6290', '', 2, 0, 'M', 'casado', '1999-08-22', '2125552829', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2555, 'V-23678901', 'Verónica León', 'verónica.león', 'vleon@example.com', '2125552929', '4125552929', 'Calle 29 #2929', 'San Carlos', 'Cojedes', 'San Carlos', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd8b9bf41fc29d33ff8a0d642caf11247', '', 1, 0, 'F', 'soltera', '2001-02-20', '2125552930', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2556, 'E-18765430', 'Oscar Rivas', 'oscar.rivas', 'orivas@example.com', '2125553030', '4125553030', 'Avenida 30 #3030', 'Achaguas', 'Apure', 'Achaguas', 'Achaguas', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-20 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '8c5e3e201833318627b5a3a3d1fb0801', '', 2, 0, 'M', 'soltero', '2000-06-12', '2125553031', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2557, 'V-24567890', 'Gladys Suárez', 'gladys.suárez', 'gsuarez@example.com', '2125553131', '4125553131', 'Calle 31 #3131', 'San Antonio del Táchira', 'Táchira', 'Bolívar', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-04-08 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'a7b69682aeedae2e01d506d05cef0933', '', 1, 0, 'F', 'casada', '1999-11-25', '2125553132', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2558, 'E-21654320', 'Raúl Espinoza', 'raúl.espinoza', 'respinoza@example.com', '2125553232', '4125553232', 'Avenida 32 #3232', 'San Carlos de Zulia', 'Zulia', 'Mara', 'San Carlos', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-15 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '66f633b7a86d055da5b9f8b4c5aa172c', '', 2, 0, 'M', 'soltero', '2001-03-10', '2125553233', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2559, 'V-19567890', 'Teresa Acosta', 'teresa.acosta', 'tacosta@example.com', '2125553333', '4125553333', 'Calle 33 #3333', 'Upata', 'Bolívar', 'Piar', 'Upata', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', 'd6d77546e16bffd6c9768db15103139f', '', 1, 0, 'F', 'soltera', '2000-10-30', '2125553334', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2560, 'E-22789012', 'Alberto Márquez', 'alberto.márquez', 'amarquez@example.com', '2125553434', '4125553434', 'Avenida 34 #3434', 'Guasdualito', 'Apure', 'Páez', 'Guasdualito', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-22 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', 'd84de70c483dc10e4d05955c5e6c864c', '', 2, 0, 'M', 'casado', '1999-07-15', '2125553435', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2561, 'V-20654320', 'Yolanda Cárdenas', 'yolanda.cárdenas', 'ycardenas@example.com', '2125553535', '4125553535', 'Calle 35 #3535', 'Carora', 'Lara', 'Torres', 'Carora', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-05 04:00:00', '2025-10-01 18:20:40', 1, 'estudiante', 'd6bde420f4c5b1e80215fb12fbb8a267', '', 1, 0, 'F', 'soltera', '2001-01-10', '2125553536', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `users` (`id`, `idusuario`, `nombre`, `username`, `email`, `tlf`, `cel`, `direccion`, `ciudad`, `estado`, `municipio`, `parroquia`, `etnia`, `casaapto`, `punto_referencia`, `grupo_familiar`, `acargo_usted`, `fuente_ingresos`, `tipo_vivienda`, `tenencia_vivienda`, `enfermedad`, `discapacidad`, `titulos`, `institutos`, `potencialidades`, `fecha_ingreso`, `fecha_act`, `status`, `user_type`, `password`, `api_key`, `carrera`, `carrera_di`, `genero`, `edo_civil`, `fecha_nac`, `num_telf_opc`, `foto_perfil`, `usuario`, `estudiante`, `docente`, `admin`, `super_user`, `editar_user`, `editar_nota`, `editar_acceso`, `editar_valores`, `editar_estudiante`, `agregar_estudiante`, `agregar_docente`, `editar_docente`, `agregar_carrera`, `agregar_materia`, `editar_materia`, `pagos`, `auditoria`, `secciones`, `rela_materia_carrera`, `periodos_academicos`, `asig_secciones`, `asig_cursos`, `horarios`, `gestion_director_carrera`, `notas_cargadas`, `consultar_notas`, `consultar_notas_pasadas`, `tipos_pago`, `tipos_horario`, `horario_personal`, `respaldo_bd`, `gestionar_carrera`, `gestion_periodo_academico`, `gestion_asig_cursos`, `gestion_horario`, `titulos_re_materia`, `grado`, `gestion_grado`, `vocero`, `visita`) VALUES
(2562, 'E-17654329', 'Francisco Parra', 'francisco.parra', 'fparra@example.com', '2125553636', '4125553636', 'Avenida 36 #3636', 'La Grita', 'Táchira', 'Jáuregui', 'La Grita', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-30 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'd9d1ca317c8468142d784f3569fff65c', '', 2, 0, 'M', 'soltero', '2000-05-25', '2125553637', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2563, 'V-23789012', 'Leticia Romero', 'leticia.romero', 'lromero@example.com', '2125553737', '4125553737', 'Calle 37 #3737', 'San Cristóbal', 'Táchira', 'San Cristóbal', 'San Juan Bautista', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-18 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '7d6afc7443c6f54340b730698e04688a', '', 1, 0, 'F', 'casada', '1999-12-15', '2125553738', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2564, 'E-19876532', 'Arturoo Mora', 'arturoo.mora', 'amora@example.com', '2125553838', '4125553838', 'Avenida 38 #3838', 'San Joaquín', 'Carabobo', 'San Joaquín', 'San Joaquín', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-28 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '9cb1f9517363737ed3a32082ec88fe93', '', 2, 0, 'M', 'soltero', '2001-04-20', '2125553839', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2565, 'V-24678901', 'Beatriz Rangel', 'beatriz.rangel', 'brangel@example.com', '2125553939', '4125553939', 'Calle 39 #3939', 'San Mateo', 'Aragua', 'Bolívar', 'San Mateo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-10 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '2da3acf9de8c82b1fd4c40d0f85a59fd', '', 1, 0, 'F', 'soltera', '2000-08-05', '2125553940', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2566, 'E-21567890', 'Héctor Zambrano', 'héctor.zambrano', 'hzambrano@example.com', '2125554040', '4125554040', 'Avenida 40 #4040', 'San José de Guanipa', 'Anzoátegui', 'Simón Rodríguez', 'San José de Guanipa', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-05 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'f5ece76723ac1b6ae3bb9e99bbf26f68', '', 2, 0, 'M', 'casado', '1999-09-30', '2125554041', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2567, 'V-18543219', 'Diana Contreras', 'diana.contreras', 'dcontreras@example.com', '2125554141', '4125554141', 'Calle 41 #4141', 'San Antonio de Los Altos', 'Miranda', 'Los Salias', 'San Antonio', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-05-22 04:00:00', '2025-10-01 18:20:30', 1, 'estudiante', '66a65afa7f551b2197845c4ad1754889', '', 1, 0, 'F', 'soltera', '2001-02-15', '2125554142', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2568, 'E-22678901', 'José Gregorio Peñalver', 'josé.gregorio.peñalver', 'jpenalver@example.com', '2125554242', '4125554242', 'Avenida 42 #4242', 'Sanare', 'Lara', 'Jiménez', 'Sanare', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-08-28 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', 'a0c79045aa1f687714256873c0d9fbde', '', 2, 0, 'M', 'soltero', '2000-07-08', '2125554243', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2569, 'V-19765430', 'Rosaura Velásquez', 'rosaura.velásquez', 'rvelasquez@example.com', '2125554343', '4125554343', 'Calle 43 #4343', 'Quíbor', 'Lara', 'Jiménez', 'Quíbor', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-02-15 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '0f05d09833979bbf49c467800c9f7631', '', 1, 0, 'F', 'casada', '1999-11-20', '2125554344', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2570, 'E-24789012', 'Alfredo Delgado', 'alfredo.delgado', 'adelgado@example.com', '2125554444', '4125554444', 'Avenida 44 #4444', 'San Juan de Colón', 'Táchira', 'Ayacucho', 'San Juan de Colón', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-10-12 04:00:00', '2025-10-01 18:20:27', 1, 'estudiante', '0b4954ba6a5e405ad4ed717f14c72764', '', 2, 0, 'M', 'soltero', '2001-03-25', '2125554445', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2571, 'V-21654329', 'Gisela Ferrer', 'gisela.ferrer', 'gferrer@example.com', '2125554545', '4125554545', 'Calle 45 #4545', 'San Luis', 'Falcón', 'Federación', 'San Luis', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-06-10 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', 'fcd54cbd301c33304cab2820a6e7a553', '', 1, 0, 'F', 'soltera', '2000-09-30', '2125554546', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2572, 'E-18654320', 'René Márquez', 'rené.márquez', 'rmarquez@example.com', '2125554646', '4125554646', 'Avenida 46 #4646', 'San Francisco', 'Zulia', 'San Francisco', 'San Francisco', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-09-05 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', '92f34bbe48e19810ec7e9f232e35e309', '', 2, 0, 'M', 'casado', '1999-08-10', '2125554647', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2573, 'V-25678901', 'Marisol Rivas', 'marisol.rivas', 'mrivas@example.com', '2125554747', '4125554747', 'Calle 47 #4747', 'San Simón', 'Zulia', 'Francisco Javier Pulgar', 'San Simón', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-03-22 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '16ad8043ae4b9817b7409e6e7fb90dc3', '', 1, 0, 'F', 'soltera', '2001-01-15', '2125554748', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2574, 'E-20765431', 'Wilmer Castillo', 'wilmer.castillo', 'wcastillo@example.com', '2125554848', '4125554848', 'Avenida 48 #4848', 'San Pablo', 'Zulia', 'Almirante Padilla', 'San Pablo', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-11-10 04:00:00', '2025-10-01 18:20:39', 1, 'estudiante', '15314e6f381ff9b044d7eb8595636fbe', '', 2, 0, 'M', 'soltero', '2000-04-28', '2125554849', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2575, 'V-22789013', 'Yusmery Del Moral', 'yusmery.del.moral', 'ydelmoral@example.com', '2125554949', '4125554949', 'Calle 49 #4949', 'San Rafael del Moján', 'Zulia', 'Almirante Padilla', 'San Rafael', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2022-01-18 04:00:00', '2025-10-01 18:20:40', 1, 'estudiante', 'efe9efc9276e537d2f1450885df651b3', '', 1, 0, 'F', 'casada', '1999-10-12', '2125554950', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2576, 'E-23678902', 'Richard Briceño', 'richard.briceño', 'rbriceno@example.com', '2125555050', '4125555050', 'Avenida 50 #5050', 'San Timote', 'Zulia', 'Baralt', 'San Timote', 'Ninguna', NULL, 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', 'No especificado', '', '2021-12-30 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', 'c146a97d5173f9f25c8fb142cf207ecd', '', 2, 0, 'M', 'soltero', '2001-05-05', '2125555051', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2584, 'V-14123524', 'Manuel Turiso', 'manuel.turiso', 'kol@gmail.com', '0412777777', '0412777777', 'qedwq', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '1', '0', '1', 'Casa', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:35', 1, 'docente', '$2y$10$RxKomMmQumrSU9DFowD7mOriXhK6oOW/GYMLm6DvO7NSJQsPh/wiS', '7e140884c26c97f6f6bcce3a20b0a2c3', 0, 0, 'Masculino', '2', '2000-03-09', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2585, 'V-13123524', 'Alberto Lopez', 'alberto.lopez', 'zol@gmail.com', '0412777777', '0412777777', 'mjdncja', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '2', '0', '1', 'Apartamento', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:27', 1, 'docente', '$2y$10$hXIRvrslTjCvVisOvsBMl.iNHitesSiFKTolJ5KObfnr6oCk3NwpC', 'af2c2755c1f3498a955651ad7dcc156a', 0, 0, 'Masculino', '2', '1991-07-11', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2586, 'E-12569002', 'Francisco Torrealba', 'francisco.torrealba', 'pol@gmail.com', '0412777777', '0412777777', 'jdNJDSANJ', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '2', '0', '2', 'Casa', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:31', 1, 'docente', '$2y$10$JkE3FtgVlymcKJRtI4w6CeecP8Dk93HQO59D6CwgFGeKgBYsDuUKy', '0cc8939b4524580c7589a64aa3e59ae9', 0, 0, 'Masculino', '2', '1991-03-13', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2588, 'V-24765890', 'Sarsamora Vegano', 'sarsamora.vegano', 'rol@gmail.com', '0412777777', '0412777777', 'siuuuuu', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'poraiiii', '3', '0', '', 'Apartamento', 'Alquilada', '', 'no', '', '', 'lol', '2025-08-03 04:00:00', '2025-10-01 18:20:38', 1, 'docente', '$2y$10$xgVIJqKbEPm/HJTfyUx5/.xF9YGPlLFioOENtL4gjqfDB13ybb8h2', '226af57221e592d91a033ecc16491a1d', 0, 0, 'Femenino', '2', '1988-07-13', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2589, 'V--21456555', 'Palmera Kazekage', 'palmera.kazekage', 'kazekage@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-24 04:00:00', '2025-10-01 18:20:37', 1, 'estudiante', 'b71219d2ea11fb066d298edbadf67b19', '', 1, 0, 'Masculino', 'Soltero', '2000-06-15', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2590, 'V--24648009', 'Francisco Mendoza', 'francisco.mendoza', 'rolllll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-31 04:00:00', '2025-10-01 18:20:31', 1, 'estudiante', '3f718eb49861ad69bd0ddaa7c94974c9', '', 1, 0, 'Masculino', 'Casado', '1989-07-19', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2591, 'V-32567456', 'Claudia Lopez', 'claudia.lopez', 'kollllll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-08-31 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '377d6bc1b54ba0f6d729651c9195c205', '', 1, 0, 'Femenino', 'Casado', '2006-07-13', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2592, 'V-54678943', 'Jose Manuel', 'jose.manuel', 'ggol@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '92fa6a601065ef1d62cf229a40642da1', '', 1, 0, 'Masculino', 'Soltero', '2002-06-13', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2593, 'V--45324567', 'Jose Manuel Lopez', 'jose.manuel.lopez', 'rrollll@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '3fe63d34589ba217e4824534c4582578', '', 2, 0, 'Masculino', 'Casado', '1995-07-13', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2594, 'V--21456565', 'Maria Antonieta', 'maria.antonieta', 'mariaantonieta@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '78f0a70fb42e54b223544eec88e2d052', '', 1, 0, 'Femenino', 'Soltero', '2001-07-19', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2595, 'V--34678324', 'Sofia Fernandez', 'sofia.fernandez', 'sofilol@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:38', 1, 'estudiante', '97f1db41887d87caa54e276ad7b2c312', '', 1, 0, 'Masculino', 'Casado', '1998-06-10', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2596, 'V--20456543', 'Hector Gutierrez', 'hector.gutierrez', 'hectorgu@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:32', 1, 'estudiante', 'd5d6bb5424a9d4f9dc9c1092477fdfc3', '', 1, 0, 'Masculino', 'Casado', '2001-03-08', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2597, 'V--36789546', 'Luis Aguilar', 'luis.aguilar', 'luisaguila@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '3e6d29ef91fedd06772de7b754316a2a', '', 5, 0, 'Masculino', 'Soltero', '2007-06-28', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2598, 'V--31789321', 'Laura Colores', 'laura.colores', 'lauracolores@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '8f467697120171c90181e9a3241fd529', '', 5, 0, 'Masculino', 'Casado', '2003-10-17', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2599, 'V--31789324', 'Laura Coloress', 'laura.coloress', 'lauracolores2@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:34', 1, 'estudiante', '6b2af18350070cc63a2cf6988b872f38', '', 5, 0, 'Masculino', 'Casado', '2003-10-17', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2600, 'V--12345677', 'Anabelle Carroza', 'anabelle.carroza', 'carroza@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-02 04:00:00', '2025-10-01 18:20:28', 1, 'estudiante', '02b89b15f7210b47c94e79f08f62704a', '', 5, 0, 'Masculino', 'Casado', '2004-07-15', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2601, 'E--34511211', 'Manuel Turisooo', 'manuel.turisooo', 'turisoo@gmail.com', '04125777777', '', 'porai', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '8059f1d1a0accf2c3aa27dd0c89dfb0f', '', 1, 0, 'Masculino', 'Casado', '2001-12-13', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2602, 'E--34678900', 'Carlos Humberto Morales', 'carlos.humberto.morales', 'calos@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:29', 1, 'estudiante', '777419bcad989fde187a64f51be7b4ea', '', 5, 0, 'Masculino', 'Soltero', '2001-07-18', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2603, 'E--30567435', 'Manteca De Colesterol', 'manteca.de.colesterol', 'manteca@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:35', 1, 'estudiante', '8f908f3eb2d5f7305f17fa9837f591f6', '', 1, 0, 'Masculino', 'Casado', '2004-07-16', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2604, 'V--21456544', 'Jose Manuel Lopezz', 'jose.manuel.lopezz', 'pgol@gmail.com', '04125777777', '', 'lol', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-09-03 04:00:00', '2025-10-01 18:20:33', 1, 'estudiante', '37ce9255f0c8e6dfca1e811959ead689', '', 1, 0, 'Masculino', 'Casado', '2000-07-12', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2607, 'V-12345678', 'Nombre Ejemplo', 'nombre.ejemplo', 'ejemplo@correo.com', '02121234567', '04141234567', 'Dirección Ejemplo', 'Caracas', 'Distrito Capital', 'Libertador', 'La Candelaria', '', 'Casa', 'Frente a la plaza', '4', '2', 'Trabajo formal', 'Casa', 'Propia', 'Ninguna', 'No especificado', 'Bachiller,Licenciatura', 'Liceo XYZ,Universidad ABC', '', '2023-01-15 04:00:00', '2025-10-01 18:20:36', 1, 'estudiante', '25d55ad283aa400af464c76d713c07ad', '', 1, NULL, 'Masculino', 'Soltero', '1990-01-01', '02121234568', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2608, 'E-8549625', 'bhuftyfu', 'bhuftyfu', 'frthft@gmail.com', '0412555777', '', 'guygyh', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', '', 'No especificado', '', '0', '0', '', '', '', '', '', '', '', '', '2025-10-01 04:00:00', '2025-10-23 14:25:07', 1, 'estudiante', '92a0159e815657aeab28ac8a935cf1ca', '', 1, NULL, 'Masculino', 'Soltero', '1979-07-19', '', NULL, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2609, 'V-12345555', 'Perdomo Albañil', 'perdomo.albañil', 'perdomo@gmail.com', '0412555555', '0412555555', 'rdrhv', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'No especificado', 'frente a una farmacia', '2', '0', '1', 'Apartamento', 'Familiar', '', 'no', '', '', 'esfdfs', '2025-10-02 04:00:00', '2025-10-23 17:26:26', 1, 'docente', '$2y$10$8lPuQS3UuMISfjSY7Cwuc.QToyk85yB/Nz3MfIXd13zi4705M6ivC', '97610812c565e1a74c3478ae6bc6c099', 0, NULL, 'Masculino', '1', '1992-06-11', '', NULL, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(2610, 'V-30123456', 'alberto guerra', 'alberto.guerra', 'infos@guerra.com', '0416598362', '0416777777', 'prueba', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Juan Jose Flores', 'Ninguna', 'Apartamento', 'frente a un campo', '6', '2', '2', '', 'Alquilada', 'no', 'No', '', '', '', '2025-11-24 04:00:00', '2025-11-25 15:28:10', 1, 'estudiante', '$2y$10$OMS2YHLfEYa3n1Y1RPoj5eDq200OinIjuH9sdPP0G/ryY0C8xK4T.', '', 1, NULL, 'Masculino', 'Casado', '1975-06-12', '04163333333', '69247f8403325_1763999620.png', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2615, 'V-30123123', 'O\'Connor', 'o.connor', 'validacion@example.com', '0412555777', '0416777777', 'kvftfvgghjkf', 'Puerto Cabello', 'Carabobo', 'Puerto Cabello', 'Bartolome Salom', 'Ninguna', 'Apartamento', 'frente a una farmacia', '3', '2', '1', '', 'Alquilada', 'no', 'No', '', '', '', '2025-12-03 04:00:00', '2025-12-03 17:03:32', 1, 'estudiante', '$2y$10$sNBUvk9vofry5VPN75ebbeJbLAuhSt61bziRN.ANpmDw3fFHm3Wj.', '854d0aa4bec27560ebb7550a3f9600a4', 1, 1, 'Masculino', 'Divorciado', '2002-06-19', '04163333333', 'foto_69305f0b6200d9.46312539.jpeg', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2616, 'E-30123458', 'Luis Miguel', 'luis.miguel', 'luismiguell@gmail.com', '02423644305', '0416777775', 'porai', '87', '7', '87', '275', '', 'Casa', 'frente a un campo', '2', '2', '1', '', 'Propia', 'no', 'No', '', '', '', '2026-02-10 04:00:00', '2026-02-10 18:55:17', 1, 'estudiante', '$2y$10$FWB2IFV68cufx1b50aw0c.DHQVyvJCAVG.E63gWydrFf6c9ku5JX2', 'dfeac3c02b604bcae12167a29e71a819', 5, 5, 'Masculino', 'Soltero', '1995-10-13', '04163333335', '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2617, 'V-54123456', 'una pruba', 'V-54123456', 'pruebasuper@gmail.com', '0412555777', '0416777777', 'porai', '87', '7', '87', '275', '', 'Casa', 'frente a un campo', '2', '1', '1', '', 'Familiar', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:35:36', 1, 'estudiante', '$2y$10$xF2bRQQe5nPh1YYH8hqVaeENeVVFDUmWxbWMqtH6YAbgwe4IVPpIO', '0037221a6e5888dd5951f6c2f64301a6', 5, 5, 'Masculino', 'Casado', '2000-06-17', '', '', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2618, 'V-53123456', 'Otra Prueba', 'V-53123456', 'otraprueba@gmail.com', '0412555777', '0416777777', 'lol', '87', '7', '87', '275', '', 'Otro', 'frente a una farmacia', '1', '0', '1', '', 'Otro', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:41:38', 1, 'estudiante', '$2y$10$vkv7JDi8xArzlGWqfXGffOkQS/JbcnpkGpQ0L55Ll4Opr4rWXr93.', '41681b0b80142eed810d89f8c60e2f1b', 5, 5, 'Masculino', 'Casado', '2003-02-07', '04163333333', 'foto_699c59127213f0.57745891.jpg', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2619, 'V-98123456', 'Diosito Otra Prueba', 'V-98123456', 'ooomaga@gmail.com', '0412555777', '0416777777', 'lol', '87', '7', '87', '275', '', 'Apartamento', 'yuk', '2', '1', '3', '', 'Alquilada', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:45:21', 1, 'estudiante', '$2y$10$.nHV1hMcXMDJMK8i/YQ46OFQS5diOIRpVv1ou4MCpuYIUj1rsuRNy', 'b221bfdb70f4db19841cc369b2ff94d2', 5, 5, 'Masculino', 'Soltero', '2000-03-17', '', 'foto_699c59f1d0d921.96633581.png', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0),
(2620, 'V-45123456', 'Papadio Super Prueba', 'V-45123456', 'diosito@gmail.com', '02423644304', '0416777777', 'porai', '87', '7', '87', '275', '', 'Apartamento', 'frente a un campo', '2', '0', '1', '', 'Familiar', '', '', 'Bachiller', 'U.E Freancis de Miranda', '', '2026-02-23 04:00:00', '2026-02-23 13:47:58', 1, 'estudiante', '$2y$10$T1Me3Locj9qc7ZwNSavxheUEIaENCoyD0veQxYOM2y6BTuw4ffdY2', '6ad958f6ff0b729615f6da629362ccc9', 5, 5, 'Masculino', 'Casado', '2004-11-12', '', 'foto_699c5a8e810181.58284137.jpg', 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_types`
--

CREATE TABLE `user_types` (
  `id` int NOT NULL,
  `user_type` varchar(11) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `descripcion` varchar(50) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
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
  `nro_identificacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `nombre` varchar(200) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `pais` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `whatsapp` varchar(30) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `telegram` varchar(30) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `ip` varchar(15) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `fecha_visita` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `web` varchar(100) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL
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
(1573, 4, '::1', '2026-03-25 16:24:19', 'mensajeria.php');

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
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_seccion`),
  ADD UNIQUE KEY `idx_unique_seccion_materia` (`id_seccion`,`id_materia`),
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
  ADD KEY `id_nota` (`id_nota`);

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
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id_periodo`);

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `docente_seccion`
--
ALTER TABLE `docente_seccion`
  MODIFY `id_docente_seccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=647;

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
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id_periodo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_seccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2621;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1574;

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
  ADD CONSTRAINT `historial_cambios_notas_ibfk_1` FOREIGN KEY (`id_nota`) REFERENCES `notas_definitivas` (`id`);

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
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`banco_id`) REFERENCES `bancos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
