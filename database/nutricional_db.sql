-- Script SQL para crear la base de datos del sistema de nutrición estudiantil
-- Importa este archivo en phpMyAdmin o ejecuta con mysql -u root < nutricional_db.sql

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS nutricional_db;
USE nutricional_db;

-- Crear tabla de estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    estatura DECIMAL(3,2) NOT NULL,
    imc DECIMAL(5,2) NOT NULL,
    clasificacion VARCHAR(50) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha_registro),
    INDEX idx_clasificacion (clasificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo (comentados - descomenta si lo necesitas)
/*
INSERT INTO estudiantes (nombre, peso, estatura, imc, clasificacion) VALUES
('Juan Pérez', 65, 1.75, 21.22, 'Peso normal'),
('María García', 55, 1.60, 21.48, 'Peso normal'),
('Carlos López', 85, 1.80, 26.23, 'Sobrepeso'),
('Ana Martínez', 48, 1.65, 17.63, 'Bajo peso');
*/

-- Mensaje de confirmación
SELECT 'Base de datos creada exitosamente' AS mensaje;
