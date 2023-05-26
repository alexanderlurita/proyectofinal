DROP DATABASE IF EXISTS restauranteDB;

CREATE DATABASE restauranteDB;

USE restauranteDB;

CREATE TABLE personas
(
	idpersona		INT AUTO_INCREMENT PRIMARY KEY,
	apellidos		VARCHAR(50)	NOT NULL,
	nombres 		VARCHAR(50)	NOT NULL,
	telefono		CHAR(9)		NULL,
	correo			VARCHAR(50)	NULL,
	direccion		VARCHAR(150)	NULL
) ENGINE = INNODB;

CREATE TABLE turnos
(
	idturno			TINYINT AUTO_INCREMENT PRIMARY KEY,
	turno			VARCHAR(30)	NOT NULL,
	horainicio		TIME 		NOT NULL,
	horafin			TIME 		NOT NULL,
	CONSTRAINT uk_turno_tur UNIQUE (turno)
) ENGINE = INNODB;

CREATE TABLE empleados
(
	idempleado 		INT AUTO_INCREMENT PRIMARY KEY,
	idpersona		INT 		NOT NULL, -- FK
	cargo			VARCHAR(30) 	NOT NULL, -- Chef, Cajero, Mesero, Etc
	fechacontrato		DATETIME	NOT NULL DEFAULT NOW(),
	fechadespido		DATETIME 	NULL,
	idturno			TINYINT		NULL,
	estado			CHAR(1) 	NOT NULL DEFAULT '1',
	CONSTRAINT fk_idpersona_emp FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
 	CONSTRAINT uk_idpersona_emp UNIQUE(idpersona),
 	CONSTRAINT fk_idturno_emp FOREIGN KEY (idturno) REFERENCES turnos(idturno)
) ENGINE = INNODB;

CREATE TABLE usuarios
(
	idusuario		INT AUTO_INCREMENT PRIMARY KEY,
	idempleado		INT		NOT NULL, -- FK
	nombreusuario		VARCHAR(50)	NOT NULL,
	claveacceso		VARCHAR(200)	NOT NULL,
	nivelacceso		CHAR(1)		NOT NULL DEFAULT 'E', -- A(Administrador), -- E(Estándar), -- S(Supervisor)
	create_at		DATETIME	NOT NULL DEFAULT NOW(),
	update_at		DATETIME	NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idempleado_usu FOREIGN KEY (idempleado) REFERENCES empleados(idempleado),
	CONSTRAINT uk_idempleado_usu UNIQUE (idempleado),
	CONSTRAINT uk_nombreusuario_usu UNIQUE (nombreusuario),
	CONSTRAINT ck_nivelacceso_usu CHECK (nivelacceso IN ('A', 'E', 'S'))
) ENGINE = INNODB;

CREATE TABLE mesas
(
	idmesa			TINYINT AUTO_INCREMENT PRIMARY KEY,
	nombremesa		VARCHAR(40)	NOT NULL,
	capacidad		TINYINT		NOT NULL,
	estado			CHAR(1)		NOT NULL DEFAULT 'D', -- D(Disponible), O(Ocupada), R(Reservada), M(Mantenimiento)
	CONSTRAINT uk_nombremesa_mes UNIQUE (nombremesa),
	CONSTRAINT ck_capacidad_mes CHECK (capacidad > 0),
	CONSTRAINT ck_estado_mes CHECK (estado IN ('D', 'O', 'R', 'M'))
) ENGINE = INNODB;

CREATE TABLE productos
(
	idproducto 		INT AUTO_INCREMENT PRIMARY KEY,
	tipoproducto		VARCHAR(40)	NOT NULL,
	nombreproducto		VARCHAR(50)	NOT NULL,
	descripcion		VARCHAR(150)	NULL,
	precio			DECIMAL(7,2)	NOT NULL,
	stock			TINYINT 	NULL,
	CONSTRAINT uk_producto_pla UNIQUE (tipoproducto, nombreproducto),
	CONSTRAINT ck_precio_pla CHECK (precio > 0),
	CONSTRAINT ck_stock_pla CHECK (stock >= 0)
) ENGINE = INNODB;

CREATE TABLE ventas
(
	idventa			INT AUTO_INCREMENT PRIMARY KEY,
	idmesa			TINYINT 	NOT NULL, -- FK
	idcliente		INT 		NOT NULL, -- FK
	idempleado		INT 		NOT NULL, -- FK
	fechahoraventa		DATETIME	NOT NULL DEFAULT NOW(),
	tipocomprobante		CHAR(1)		NULL, -- B(Boleta), F(Factura)
	numcomprobante		CHAR(10)	NULL, -- Se generará automáticamente
	estado			CHAR(2)		NOT NULL DEFAULT 'PE', -- PA(Pagado), PE(Pendiente), CA(Cancelado)
	CONSTRAINT fk_idmesa_ped FOREIGN KEY (idmesa) REFERENCES mesas(idmesa),
	CONSTRAINT fk_idcliente_ped FOREIGN KEY (idcliente) REFERENCES personas(idpersona),
	CONSTRAINT fk_idempleado_ped FOREIGN KEY (idempleado) REFERENCES empleados(idempleado),
	CONSTRAINT ck_tipocomprobante_ped CHECK (tipocomprobante IN ('B', 'F')), 
	CONSTRAINT ck_estado_ped CHECK (estado IN ('PA', 'PE', 'CA'))
) ENGINE = INNODB;

CREATE TABLE detalle_venta
(
	iddetalleventa		INT AUTO_INCREMENT PRIMARY KEY,
	idventa			INT 		NOT NULL, -- FK
	idproducto		INT 		NOT NULL, -- FK
	cantidad		TINYINT		NOT NULL,
	precioproducto		DECIMAL(7,2)	NOT NULL,
	CONSTRAINT fk_idventa_det FOREIGN KEY (idventa) REFERENCES ventas(idventa),
	CONSTRAINT fk_idproducto_det FOREIGN KEY (idproducto) REFERENCES productos(idproducto),
	CONSTRAINT ck_cantidad_det CHECK (cantidad > 0),
	CONSTRAINT ck_precioproducto_det CHECK (precioproducto >= 0)
) ENGINE = INNODB;


CREATE TABLE pagos
(
	idpago			INT AUTO_INCREMENT PRIMARY KEY,
	idventa			INT 		NOT NULL, -- FK
	metodopago		CHAR(1)		NOT NULL, -- E(Efectivo), T(Tarjeta), Y(Yape), P(Plin)
	montopagado		DECIMAL(7,2)	NOT NULL,
	fechahorapago		DATETIME	NOT NULL DEFAULT NOW(),
	CONSTRAINT fk_idventa_pag FOREIGN KEY (idventa) REFERENCES ventas(idventa),
	CONSTRAINT ck_metodopago_pag CHECK (metodopago IN ('E', 'T', 'Y', 'P'))
) ENGINE = INNODB;

-- Inserciones a las tablas
INSERT INTO personas (apellidos, nombres, telefono, correo, direccion) VALUES
	('Paredes Rovira', 'Xavier', NULL, NULL, NULL),
	('Apolaya Gomez', 'Paul', '956012030', NULL, NULL),
	('Pino Miranda', 'Maria', NULL, NULL, NULL),
	('Campos Perez', 'Cintia', NULL, NULL, 'Cl. Abigail Pulido # 760'),
	('Cartagena Magallanes', 'Nicole', NULL, NULL, NULL),
	('Lurita Chávez', 'Alexander', '977522216', 'alexanderlu244@gmail.com', 'Tambo Cañete La Garita km 213'),
	('Mendoza Quispe', 'Carlos', '987102030', NULL, NULL),
	('Ramirez', 'Mireya', NULL, NULL, NULL);
	
INSERT INTO turnos (turno, horainicio, horafin) VALUES
	('Mañana', '08:00:00', '12:00:00'),
	('Tarde', '12:00:00', '18:00:00'),
	('Noche', '18:00:00', '23:59:59');
	
INSERT INTO empleados (idpersona, cargo, idturno) VALUES
	(4, 'Chef', 2),
	(2, 'Mesero', 1),
	(6, 'Administrador', NULL);
	
INSERT INTO usuarios (idempleado, nombreusuario, claveacceso) VALUES
	(1, 'Cintia', '$2y$10$EESBB7SbmSCq/P9w0m5iO.IHrMJofhI/Suk4SqrSsB4bbMqAVkY2K'),
	(2, 'Paul', '$2y$10$.H7VAses0eK0kwb7ogG9OuATyST4naJHR3X2XK5dWm0DIuwJaRh8G');
	
INSERT INTO mesas (nombremesa, capacidad) VALUES
	('Mesa 1', 3),
	('Mesa 2', 4),
	('Mesa 3', 3),
	('Mesa 4', 6),
	('Mesa 5', 2);
	
INSERT INTO productos (tipoproducto, nombreproducto, descripcion, precio, stock) VALUES
	('Entrada', 'Tequeños de Lomo Saltado', '8 unidades de tequeños rellenos de lomo saltado criollo con guacamole', 20, NULL),
	('Entrada', 'Club Sandwich', 'Pollo, jamón, queso, palta, lechuga y tomate acompañado de papas fritas', 25, NULL),
	('Plato de fondo', 'Rocoto Relleno', 'Tradicional rocoto relleno arequipeño, acompañado de pastel de papas', 45, NULL),
	('Plato de fondo', 'Lomo Saltado', 'Clásico lomo fino saltado de res acompañado de papas fritas y arroz blanco', 64, NULL),
	('Postre', 'Charlotte de maracuya', 'Charlotte de maracuyá, naranja y muña', 32, 20),
	('Bebida', 'Inca Kola 600ml', 'Vaso de Inca Kola + hielos', 5, 16),
	('Entrada', 'Anticucho de corazón especial', 'Dos palitos de trozos tiernos de corazón de res, acompañados con choclo José Antonio, papa dorada y salsa criolla', 25, NULL),
	('Plato de fondo', 'Cau Cau', 'Receta en base a trozos de mondongo y papa, acompañada con arroz blanco', 40, NULL),
	('Bebida', 'Chicha morada', 'Vaso grande de chicha morada + hielos', 5, NULL);

INSERT INTO ventas(idmesa, idcliente, idempleado, tipocomprobante, numcomprobante, estado) VALUES
	(2, 7, 2, 'B', 'BOL-000001', 'PA'),
	(3, 8, 2, 'B', 'BOL-000002', 'PA');
	
INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES
	(1, 1, 2, 20),
	(1, 8, 2, 40),
	(1, 9, 2, 5),
	(2, 5, 2, 20),
	(2, 6, 2, 5);

/*SELECT * FROM personas;
SELECT * FROM empleados;
SELECT * FROM usuarios;
SELECT * FROM mesas;
SELECT * FROM productos;
select * from ventas; 
select * from detalle_venta*/

-- PROCEDIMIENTOS ALMACENADOS
-- USUARIOS
-- LOGIN
DELIMITER $$
CREATE PROCEDURE spu_usuarios_login(IN _nombreusuario VARCHAR(50))
BEGIN
	SELECT 	usuarios.idusuario, personas.apellidos, personas.nombres,
		usuarios.nombreusuario, usuarios.claveacceso, usuarios.nivelacceso
		FROM usuarios
		INNER JOIN empleados ON empleados.idempleado = usuarios.idempleado
		INNER JOIN personas ON personas.idpersona = empleados.idpersona
		WHERE usuarios.nombreusuario = _nombreusuario AND usuarios.estado = '1';
END $$

-- VENTAS
-- LISTAR VENTAS
DELIMITER $$
CREATE PROCEDURE spu_ventas_listar()
BEGIN
	SELECT  ventas.`idventa`, 
		mesas.`nombremesa`,
		CONCAT(personas.`apellidos`, ' ', personas.`nombres`) 'cliente',
		ventas.`fechahoraventa`,
		ventas.`estado`
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		INNER JOIN personas ON personas.`idpersona` = ventas.`idcliente`
		ORDER BY 1 DESC;
END $$

-- BUSCAR VENTA
DELIMITER $$
CREATE PROCEDURE spu_ventas_buscar(IN _idventa INT)
BEGIN
	SELECT  ventas.`idventa`, 
		mesas.`nombremesa`,
		CONCAT(p1.`apellidos`, ' ', p1.`nombres`) 'cliente',
		CONCAT(p2.apellidos, ' ', p2.nombres) 'mesero',
		ventas.`fechahoraventa`,
		ventas.`tipocomprobante`,
		ventas.`numcomprobante`,
		ventas.`estado`
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		INNER JOIN personas p1 ON p1.`idpersona` = ventas.`idcliente`
		INNER JOIN empleados ON empleados.`idempleado` = ventas.`idempleado`
		INNER JOIN personas p2 ON p2.idpersona = empleados.`idpersona`
		WHERE ventas.`idventa` = _idventa;
END $$

-- DETALLAR VENTA
DELIMITER $$
CREATE PROCEDURE spu_ventas_detallar(IN _idventa INT)
BEGIN
	SELECT 	DET.iddetalleventa, 
		PRO.nombreproducto, 
		DET.cantidad, 
		DET.precioproducto,
		DET.cantidad * DET.precioproducto 'importe'
		FROM detalle_venta DET
		INNER JOIN ventas VEN ON VEN.idventa = DET.idventa
		INNER JOIN productos PRO ON PRO.idproducto = DET.idproducto
		WHERE DET.idventa = _idventa;
END $$

CALL spu_ventas_detallar(1);
CALL spu_ventas_buscar(1);