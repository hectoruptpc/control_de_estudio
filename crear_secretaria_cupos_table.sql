CREATE TABLE IF NOT EXISTS `secretaria_config` (
  `clave` varchar(100) NOT NULL,
  `valor` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `secretaria_cupos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `carrera_id` int NOT NULL,
  `turno` varchar(50) NOT NULL,
  `cupos_totales` int NOT NULL DEFAULT 0,
  `numero_secciones` int NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_carrera_turno` (`carrera_id`, `turno`),
  KEY `idx_carrera` (`carrera_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
