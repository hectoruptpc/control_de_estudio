-- Crear tabla para códigos de secciones
CREATE TABLE IF NOT EXISTS `codigos_secciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_carrera` int NOT NULL,
  `codigo_inicio` int NOT NULL,
  `codigo_fin` int NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_carrera` (`id_carrera`),
  CONSTRAINT `codigos_secciones_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Insertar datos de ejemplo
INSERT INTO `codigos_secciones` (`id_carrera`, `codigo_inicio`, `codigo_fin`, `descripcion`) VALUES
(14, 10, 19, 'Mecánica'),
(15, 20, 29, 'Mecánica Automotriz');