DROP TABLE IF EXISTS secciones;
CREATE TABLE secciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  carrera_id INT NOT NULL,
  turno VARCHAR(50) NOT NULL,
  numero_seccion INT NOT NULL,
  capacidad INT NOT NULL DEFAULT 30,
  horario TEXT,
  status ENUM('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  created_by INT,
  approved_by INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  approved_at TIMESTAMP NULL,
  UNIQUE KEY unique_seccion (carrera_id, turno, numero_seccion),
  KEY idx_secciones_carrera (carrera_id),
  KEY idx_secciones_created_by (created_by),
  KEY idx_secciones_approved_by (approved_by),
  CONSTRAINT fk_secciones_carrera FOREIGN KEY (carrera_id) REFERENCES carreras(id_carrera),
  CONSTRAINT fk_secciones_created_by FOREIGN KEY (created_by) REFERENCES users(id),
  CONSTRAINT fk_secciones_approved_by FOREIGN KEY (approved_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
