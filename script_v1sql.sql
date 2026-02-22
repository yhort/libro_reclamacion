CREATE DATABASE IF NOT EXISTS galeria
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE galeria;

SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- USUARIOS / SEGURIDAD
-- =====================================================
CREATE TABLE usuarios (
  usuario_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('ADMIN','TESORERIA','COCHERA','BANOS','ALQUILERES') NOT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

ALTER TABLE usuarios 
ADD COLUMN email VARCHAR(100) NOT NULL UNIQUE AFTER username,
ADD COLUMN reset_token VARCHAR(100) DEFAULT NULL AFTER activo,
ADD COLUMN reset_expires DATETIME DEFAULT NULL AFTER reset_token;
ALTER TABLE usuarios 
ADD COLUMN creado_por BIGINT UNSIGNED NULL AFTER creado_en,
ADD COLUMN editado_por BIGINT UNSIGNED NULL AFTER activo,
ADD COLUMN editado_en DATETIME NULL ON UPDATE CURRENT_TIMESTAMP AFTER editado_por;

-- Opcional: Agregar llaves foráneas para integridad
ALTER TABLE usuarios 
ADD CONSTRAINT fk_usuario_creador FOREIGN KEY (creado_por) REFERENCES usuarios(usuario_id),
ADD CONSTRAINT fk_usuario_editor FOREIGN KEY (editado_por) REFERENCES usuarios(usuario_id);
DESCRIBE usuarios;
-- =====================================================
-- PARÁMETROS DEL SISTEMA
-- =====================================================
CREATE TABLE parametros_sistema (
  parametro_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clave VARCHAR(80) NOT NULL UNIQUE,
  valor VARCHAR(120) NOT NULL,
  descripcion VARCHAR(255),
  actualizado_en DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO parametros_sistema (clave, valor, descripcion) VALUES
('TARIFA_COCHERA_AUTO_HORA','5.00','Auto por hora'),
('TARIFA_COCHERA_MOTO_HORA','4.00','Moto por hora'),
('TARIFA_COCHERA_PLANA','25.00','Tarifa plana cochera'),
('TOLERANCIA_COCHERA_MIN','10','Minutos gratis'),
('TARIFA_BANO','1.00','Baño por persona'),
('MORA_ALQUILER_FIJA_MES','100.00','Mora fija por mes vencido');

-- =====================================================
-- AUTORIZACIONES
-- =====================================================
CREATE TABLE autorizaciones (
  autorizacion_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('SALIDA_SIN_PAGO','REAPERTURA_CIERRE','AJUSTE_ESPECIAL','OTRO') NOT NULL,
  motivo TEXT NOT NULL,
  solicitado_por BIGINT UNSIGNED NOT NULL,
  autorizado_por BIGINT UNSIGNED NOT NULL,
  fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (solicitado_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (autorizado_por) REFERENCES usuarios(usuario_id)
) ENGINE=InnoDB;

-- =====================================================
-- CAJAS / TESORERÍA
-- =====================================================
CREATE TABLE cajas (
  caja_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  modulo ENUM('COCHERA','BANOS','ALQUILERES','TESORERIA') NOT NULL UNIQUE,
  activa TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

INSERT INTO cajas (modulo) VALUES
('COCHERA'),('BANOS'),('ALQUILERES'),('TESORERIA');

CREATE TABLE cierres_caja (
  cierre_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  caja_id BIGINT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  total_ingresos DECIMAL(12,2) DEFAULT 0,
  total_gastos DECIMAL(12,2) DEFAULT 0,
  total_ajustes DECIMAL(12,2) DEFAULT 0,
  estado ENUM('ABIERTO','CERRADO','REABIERTO') DEFAULT 'CERRADO',
  cerrado_por BIGINT UNSIGNED,
  cerrado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
  autorizacion_id BIGINT UNSIGNED,
  UNIQUE (caja_id, fecha),
  FOREIGN KEY (caja_id) REFERENCES cajas(caja_id),
  FOREIGN KEY (cerrado_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (autorizacion_id) REFERENCES autorizaciones(autorizacion_id)
) ENGINE=InnoDB;

CREATE TABLE movimientos_caja (
  movimiento_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  caja_id BIGINT UNSIGNED NOT NULL,
  fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  tipo ENUM('INGRESO','GASTO','AJUSTE') NOT NULL,
  concepto VARCHAR(150),
  monto DECIMAL(12,2) NOT NULL,
  referencia_tipo ENUM('COCHERA','BANOS','ALQUILER','OTRO'),
  referencia_id BIGINT UNSIGNED,
  registrado_por BIGINT UNSIGNED NOT NULL,
  cierre_id BIGINT UNSIGNED,
  autorizacion_id BIGINT UNSIGNED,
  FOREIGN KEY (caja_id) REFERENCES cajas(caja_id),
  FOREIGN KEY (registrado_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (cierre_id) REFERENCES cierres_caja(cierre_id),
  FOREIGN KEY (autorizacion_id) REFERENCES autorizaciones(autorizacion_id)
) ENGINE=InnoDB;

-- =====================================================
-- SOCIOS (EXONERADOS DE COCHERA)
-- =====================================================
CREATE TABLE socios (
  socio_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) UNIQUE,
  nombre VARCHAR(150),
  activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE socio_vehiculos (
  socio_vehiculo_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  socio_id BIGINT UNSIGNED NOT NULL,
  placa VARCHAR(15) UNIQUE,
  tipo_vehiculo ENUM('AUTO','MOTO'),
  activo TINYINT(1) DEFAULT 1,
  FOREIGN KEY (socio_id) REFERENCES socios(socio_id)
) ENGINE=InnoDB;

-- =====================================================
-- COCHERA
-- =====================================================
CREATE TABLE tickets_cochera (
  ticket_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) UNIQUE,
  placa VARCHAR(15) NOT NULL,
  tipo_vehiculo ENUM('AUTO','MOTO') NOT NULL,
  modalidad_cobro ENUM('HORA','PLANA','EXONERADO') DEFAULT 'HORA',
  socio_id BIGINT UNSIGNED,
  ingreso_fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  emitido_por BIGINT UNSIGNED NOT NULL,
  FOREIGN KEY (emitido_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (socio_id) REFERENCES socios(socio_id)
) ENGINE=InnoDB;

CREATE TABLE salidas_cochera (
  salida_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id BIGINT UNSIGNED UNIQUE,
  salida_fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  minutos_total INT,
  horas_cobradas INT,
  monto_calculado DECIMAL(12,2),
  monto_pagado DECIMAL(12,2),
  pagado TINYINT(1),
  autorizacion_id BIGINT UNSIGNED,
  registrado_por BIGINT UNSIGNED,
  movimiento_id BIGINT UNSIGNED,
  FOREIGN KEY (ticket_id) REFERENCES tickets_cochera(ticket_id),
  FOREIGN KEY (registrado_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (autorizacion_id) REFERENCES autorizaciones(autorizacion_id),
  FOREIGN KEY (movimiento_id) REFERENCES movimientos_caja(movimiento_id)
) ENGINE=InnoDB;

-- =====================================================
-- BAÑOS
-- =====================================================
CREATE TABLE tickets_banos (
  ticket_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) UNIQUE,
  fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
  cantidad_personas INT,
  monto_total DECIMAL(12,2),
  emitido_por BIGINT UNSIGNED,
  movimiento_id BIGINT UNSIGNED,
  FOREIGN KEY (emitido_por) REFERENCES usuarios(usuario_id),
  FOREIGN KEY (movimiento_id) REFERENCES movimientos_caja(movimiento_id)
) ENGINE=InnoDB;

-- =====================================================
-- ALQUILERES
-- =====================================================
CREATE TABLE stands (
  stand_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(30) UNIQUE,
  ubicacion VARCHAR(100),
  tamano VARCHAR(50),
  estado ENUM('LIBRE','OCUPADO','MANTENIMIENTO','OTRO') DEFAULT 'LIBRE'
) ENGINE=InnoDB;

CREATE TABLE arrendatarios (
  arrendatario_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre_razon_social VARCHAR(150),
  dni_ruc VARCHAR(20),
  telefono VARCHAR(30),
  activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE contratos_alquiler (
  contrato_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  stand_id BIGINT UNSIGNED,
  arrendatario_id BIGINT UNSIGNED,
  fecha_inicio DATE,
  fecha_fin DATE,
  monto_mensual DECIMAL(12,2),
  estado ENUM('ACTIVO','FINALIZADO','ANULADO') DEFAULT 'ACTIVO',
  creado_por BIGINT UNSIGNED,
  FOREIGN KEY (stand_id) REFERENCES stands(stand_id),
  FOREIGN KEY (arrendatario_id) REFERENCES arrendatarios(arrendatario_id),
  FOREIGN KEY (creado_por) REFERENCES usuarios(usuario_id)
) ENGINE=InnoDB;

CREATE TABLE pagos_alquiler (
  pago_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  contrato_id BIGINT UNSIGNED,
  fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
  meses_mora INT DEFAULT 0,
  monto_base DECIMAL(12,2),
  monto_mora DECIMAL(12,2),
  monto_total DECIMAL(12,2),
  movimiento_id BIGINT UNSIGNED,
  registrado_por BIGINT UNSIGNED,
  FOREIGN KEY (contrato_id) REFERENCES contratos_alquiler(contrato_id),
  FOREIGN KEY (movimiento_id) REFERENCES movimientos_caja(movimiento_id),
  FOREIGN KEY (registrado_por) REFERENCES usuarios(usuario_id)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE stands
ADD precio_mensual DECIMAL(12,2) NOT NULL DEFAULT 0;

ALTER TABLE pagos_alquiler 
ADD COLUMN numero_cuota INT NOT NULL AFTER contrato_id,
ADD COLUMN periodo_mes VARCHAR(50) AFTER numero_cuota;

-- INSERT INTO parametros_sistema (clave, valor) VALUES ('CAPACIDAD_MAX_COCHERA', '50');
INSERT INTO parametros_sistema (clave, valor) 
VALUES ('CAPACIDAD_MAX_COCHERA', '50')
ON DUPLICATE KEY UPDATE valor = '50';

select * from parametros_sistema

-- tb configuracion
-- 1. Crear la tabla de configuración
CREATE TABLE IF NOT EXISTS configuracion (
    id INT PRIMARY KEY CHECK (id = 1), -- Forzamos a que solo exista el ID 1
    nombre_galeria VARCHAR(100) NOT NULL,
    ruc VARCHAR(20) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    email_contacto VARCHAR(100),
    moneda VARCHAR(5) DEFAULT 'S/', -- Por defecto Soles (cambiar a $ si prefieres)
    logo_path VARCHAR(255),         -- Ruta de la imagen del logo
    mensaje_ticket TEXT,            -- Texto legal o agradecimiento en el ticket
    actualizado_en DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Insertar los datos iniciales (Solo se hace una vez)
INSERT INTO configuracion (id, nombre_galeria, ruc, direccion, telefono, email_contacto, moneda, mensaje_ticket)
VALUES (
    1, 
    'Galería Viacaba', 
    '20123456789', 
    'Av. Principal 123, Lima', 
    '987 654 321', 
    'contacto@galeriapro.com', 
    'S/', 
    'Gracias por su preferencia. No nos hacemos responsables por objetos de valor dejados en el vehículo.'
);

