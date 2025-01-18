CREATE DATABASE IF NOT EXISTS bd_academico;

USE bd_academico;

CREATE TABLE IF NOT EXISTS TipoNotas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  cantidad_etapas INTEGER,
  estado BOOLEAN
);


CREATE TABLE IF NOT EXISTS ActivacionNotas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fecha_inicio DATE,
  fecha_fin DATE,
  tipo_nota_id BIGINT
);

DROP TABLE IF EXISTS TipoNotas;
