CREATE DATABASE IF NOT EXISTS egresados_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE egresados_db;

CREATE TABLE IF NOT EXISTS egresados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    carrera VARCHAR(100) NOT NULL,
    nro_matricula VARCHAR(50) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    fecha_solicitud DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT IGNORE INTO administradores (usuario, password)
VALUES ('admin', '$2y$10$Ns/JrGTgaugCYr8.a8oYjuluZRkBQ4aB4aC37hXRGnZ.CdIbvTCNq');
