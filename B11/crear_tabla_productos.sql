-- Script para crear la tabla productos en la base de datos ProduccionDB
-- Ejecutar en MariaDB/MySQL

USE ProduccionDB;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(128) NOT NULL,
    codigo VARCHAR(32) NOT NULL UNIQUE,
    descripcion TEXT,
    costo DOUBLE NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    eliminado TINYINT(1) NOT NULL DEFAULT 0,
    INDEX idx_codigo (codigo),
    INDEX idx_status (status),
    INDEX idx_eliminado (eliminado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar que la tabla se creó correctamente
DESCRIBE productos;

-- Insertar algunos productos de prueba (opcional)
INSERT INTO productos (nombre, codigo, descripcion, costo, stock, status) VALUES
('Laptop HP 15-dy2021la', 'LAP-HP-001', 'Laptop HP Intel Core i5, 8GB RAM, 256GB SSD', 12500.00, 15, 1),
('Mouse Logitech M185', 'MOU-LOG-001', 'Mouse inalámbrico Logitech M185', 250.00, 50, 1),
('Teclado Mecánico Redragon', 'TEC-RED-001', 'Teclado mecánico RGB retroiluminado', 890.00, 30, 1),
('Monitor Samsung 24"', 'MON-SAM-001', 'Monitor LED 24 pulgadas Full HD', 2800.00, 20, 1),
('Audífonos Sony WH-1000XM4', 'AUD-SON-001', 'Audífonos inalámbricos con cancelación de ruido', 5500.00, 10, 1);

-- Consultar los productos insertados
SELECT * FROM productos WHERE eliminado = 0;
