-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 01-12-2024 a las 16:47:20
-- Versión del servidor: 5.7.17-log
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteka`
--
CREATE DATABASE IF NOT EXISTS `biblioteka` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `biblioteka`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `id` int(255) NOT NULL,
  `nom` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `carrera` varchar(12) NOT NULL,
  `cedula` int(255) NOT NULL,
  `apll` varchar(300) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `tomo` varchar(200) NOT NULL,
  `folio` varchar(300) NOT NULL,
  `fechaGrado` varchar(10) NOT NULL,
  `archivo` varchar(300) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  `tesis` varchar(300) NOT NULL,
  `acto` int(200) NOT NULL,
  `titulo` int(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`id`, `nom`, `carrera`, `cedula`, `apll`, `tomo`, `folio`, `fechaGrado`, `archivo`, `fecha`, `tesis`, `acto`, `titulo`) VALUES
(17, 'Mario', '323', 25677899, 'lopez', '1', '4', '2024-11-18', 'folio.pdf', '2024-11-30', '', 1, 1),
(18, 'jesus', '544', 28455666, 'barrenas', '1', '5', '2024-11-18', 'folio.pdf', '2024-11-30', '', 2, 2),
(16, 'javi', '233', 25455677, 'flores', '1', '3', '2024-11-11', 'folio.pdf', '2024-11-30', '', 1, 2),
(15, 'Exkarlet', '323', 28534566, 'Gerrero', '1', '2', '2024-11-03', 'folio.pdf', '2024-11-29', '', 2, 2),
(14, 'Josevi', '233', 23455677, 'Gerrero', '1', '1', '2024-11-11', 'folio.pdf', '2024-11-29', '', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `code` varchar(12) NOT NULL,
  `titulo` int(200) NOT NULL,
  `fecha` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id`, `nom`, `code`, `titulo`, `fecha`) VALUES
(1, 'Informática', '323', 1, '2024-09-24'),
(4, 'Turismo', '233', 2, '2024-09-27'),
(5, 'Mecánica', '344', 1, '2024-09-27'),
(6, 'Materiales Industriales', '544', 1, '2024-09-27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tesis`
--

CREATE TABLE `tesis` (
  `idt` int(255) NOT NULL,
  `titulo` varchar(30) NOT NULL,
  `carrera` varchar(12) NOT NULL,
  `autor` int(255) NOT NULL,
  `pag` int(10) NOT NULL,
  `archivo` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `idu` int(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `pas` varchar(300) NOT NULL,
  `tipo` varchar(300) NOT NULL,
  `fecha` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `nom`, `pas`, `tipo`, `fecha`) VALUES
(1, 'blancacrespo@gmail.com', '4d186321c1a7f0f354b297e8914ab240', '1', '2024-09-21'),
(2, 'roseudismorgado@gmail.com', 'b59c67bf196a4758191e42f76670ceba', '2', '2024-09-25'),
(4, 'alexflessowo@gmail.com', '4d186321c1a7f0f354b297e8914ab240', '2', '2024-09-27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ci` (`cedula`),
  ADD KEY `carrera` (`carrera`);

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Indices de la tabla `tesis`
--
ALTER TABLE `tesis`
  ADD PRIMARY KEY (`idt`),
  ADD KEY `autor` (`autor`),
  ADD KEY `carrera` (`carrera`),
  ADD KEY `carrera_2` (`carrera`),
  ADD KEY `idu` (`idu`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `tesis`
--
ALTER TABLE `tesis`
  MODIFY `idt` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
