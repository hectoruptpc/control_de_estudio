-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 06, 2024 at 12:50 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `biblioteka`
-- 
CREATE DATABASE `biblioteka` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `biblioteka`;

-- --------------------------------------------------------

-- 
-- Table structure for table `alumno`
-- 

CREATE TABLE `alumno` (
  `id` int(255) NOT NULL auto_increment,
  `nom` varchar(30) NOT NULL,
  `carrera` varchar(12) NOT NULL,
  `cedula` int(255) NOT NULL,
  `apll` varchar(300) NOT NULL,
  `tomo` varchar(200) NOT NULL,
  `folio` varchar(300) NOT NULL,
  `fechaGrado` varchar(10) NOT NULL,
  `archivo` varchar(300) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  `tesis` varchar(300) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ci` (`cedula`),
  KEY `carrera` (`carrera`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `alumno`
-- 

INSERT INTO `alumno` VALUES (1, 'Dubrasca', '323', 32455677, 'Villegas', '2', '13', '2024-09-27', 'Notas.jpeg', '2024-09-25', '');
INSERT INTO `alumno` VALUES (8, 'Mauro', '344', 23455677, 'Reyes', '2', '2', '2024-10-30', 'folio.jpeg', '2024-10-05', '');
INSERT INTO `alumno` VALUES (3, 'Lucy', '344', 12344233, 'Villegas', '1', '12', '2024-09-14', 'Notas.jpeg', '2024-09-26', '');
INSERT INTO `alumno` VALUES (7, 'Maria', '544', 15234455, 'Vargas', '1', '1', '2024-09-26', 'folio.jpeg', '2024-09-28', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `carrera`
-- 

CREATE TABLE `carrera` (
  `id` int(11) NOT NULL auto_increment,
  `nom` varchar(30) NOT NULL,
  `code` varchar(12) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `carrera`
-- 

INSERT INTO `carrera` VALUES (1, 'InformÃ¡tica', '323', '2024-09-24');
INSERT INTO `carrera` VALUES (4, 'Turismo', '233', '2024-09-27');
INSERT INTO `carrera` VALUES (5, 'MecÃ¡nica', '344', '2024-09-27');
INSERT INTO `carrera` VALUES (6, 'Materiales Industriales', '544', '2024-09-27');

-- --------------------------------------------------------

-- 
-- Table structure for table `tesis`
-- 

CREATE TABLE `tesis` (
  `idt` int(255) NOT NULL auto_increment,
  `titulo` varchar(30) NOT NULL,
  `carrera` varchar(12) NOT NULL,
  `autor` int(255) NOT NULL,
  `pag` int(10) NOT NULL,
  `archivo` varchar(255) character set utf8 collate utf8_spanish_ci NOT NULL,
  `idu` int(255) NOT NULL,
  PRIMARY KEY  (`idt`),
  KEY `autor` (`autor`),
  KEY `carrera` (`carrera`),
  KEY `carrera_2` (`carrera`),
  KEY `idu` (`idu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tesis`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `user`
-- 

CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `pas` varchar(300) NOT NULL,
  `tipo` varchar(300) NOT NULL,
  `fecha` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `user`
-- 

INSERT INTO `user` VALUES (1, 'oswal13villegas@gmail.com', '4d186321c1a7f0f354b297e8914ab240', '1', '2024-09-21');
INSERT INTO `user` VALUES (2, 'roseudismorgado@gmail.com', 'b59c67bf196a4758191e42f76670ceba', '2', '2024-09-25');
INSERT INTO `user` VALUES (4, 'alexflessowo@gmail.com', '4d186321c1a7f0f354b297e8914ab240', '2', '2024-09-27');
