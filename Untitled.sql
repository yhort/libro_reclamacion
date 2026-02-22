
INSERT INTO usuarios (username, password_hash, rol, activo)
VALUES ('admin', '$2y$10$SWjtHCFwvS8f9XpAJ35q8eanILy0YbBLvZuvZxl4MPbq0SIaBhya.', 'ADMIN', 1);


SELECT * FROM tickets_cochera ORDER BY ticket_id DESC LIMIT 1;
select * from stands

-- PARA PROBAR LOS PAGOS DE SALIDA
SELECT * FROM salidas_cochera ORDER BY salida_id DESC LIMIT 1;
SELECT * FROM movimientos_caja ORDER BY movimiento_id DESC LIMIT 1;
--
USE galeria;

SHOW TABLES LIKE 'parametros_sistema';
SELECT * FROM parametros_sistema WHERE clave IN (
  'TOLERANCIA_COCHERA_MIN',
  'TARIFA_COCHERA_AUTO_HORA',
  'TARIFA_COCHERA_MOTO_HORA',
  'TARIFA_COCHERA_PLANA'
);

-- ERROR AL BUSCAR SALIDA POR CODIGO O PLACA
'C-20260103-000001'


SELECT NOW();           -- muestra la hora actual de MySQL
SELECT @@global.time_zone, @@session.time_zone;
