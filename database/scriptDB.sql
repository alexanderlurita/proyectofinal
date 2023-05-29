DROP DATABASE IF EXISTS restauranteDB;

CREATE DATABASE restauranteDB;

USE restauranteDB;

CREATE TABLE personas
(
	idpersona		INT AUTO_INCREMENT PRIMARY KEY,
	apellidos		VARCHAR(50)	NOT NULL,
	nombres 			VARCHAR(50)	NOT NULL,
	dni				CHAR(8)		NOT NULL,
	telefono			CHAR(9)		NULL,
	correo			VARCHAR(50)	NULL,
	direccion		VARCHAR(150)NULL,
	CONSTRAINT uk_dni_per UNIQUE(dni),
	CONSTRAINT ck_dni_per CHECK (dni REGEXP '^[0-9]{8}$')
) ENGINE = INNODB;

CREATE TABLE turnos
(
	idturno			TINYINT AUTO_INCREMENT PRIMARY KEY,
	turno				VARCHAR(30)	NOT NULL,
	horainicio		TIME 			NOT NULL,
	horafin			TIME 			NOT NULL,
	CONSTRAINT uk_turno_tur UNIQUE (turno)
) ENGINE = INNODB;

CREATE TABLE contratos
(
	idcontrato 		INT AUTO_INCREMENT PRIMARY KEY,
	idempleado		INT 			NOT NULL, -- FK
	cargo				VARCHAR(30) NOT NULL, -- Chef, Cajero, Mesero, Etc
	fechainicio		DATETIME		NOT NULL DEFAULT NOW(),
	fechatermino	DATETIME 	NULL,
	idturno			TINYINT		NULL,
	estado			CHAR(1) 		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idempleado_cont FOREIGN KEY (idempleado) REFERENCES personas(idpersona),
 	CONSTRAINT uk_idempleado_cont UNIQUE(idempleado, cargo),
 	CONSTRAINT fk_idturno_cont FOREIGN KEY (idturno) REFERENCES turnos(idturno)
) ENGINE = INNODB;

CREATE TABLE usuarios
(
	idusuario		INT AUTO_INCREMENT PRIMARY KEY,
	idempleado		INT			NOT NULL, -- FK
	nombreusuario	VARCHAR(50)	NOT NULL,
	claveacceso		VARCHAR(200)NOT NULL,
	nivelacceso		CHAR(1)		NOT NULL DEFAULT 'E', -- A(Administrador), -- E(Estándar), -- S(Supervisor)
	create_at		DATETIME		NOT NULL DEFAULT NOW(),
	update_at		DATETIME		NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idempleado_usu FOREIGN KEY (idempleado) REFERENCES contratos(idcontrato),
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
	idventa				INT AUTO_INCREMENT PRIMARY KEY,
	idmesa				TINYINT 		NOT NULL, -- FK
	idcliente			INT 			NULL, -- FK
	idempleado			INT 			NOT NULL, -- FK
	fechahoraorden		DATETIME		NOT NULL DEFAULT NOW(),
	tipocomprobante	CHAR(2)		NULL, -- BE(Boleta Electrónica), BS(Boleta Simple)
	numcomprobante		CHAR(10)		NULL, -- Se generará automáticamente
	metodopago			CHAR(1)		NULL, -- E(Efectivo), T(Tarjeta), Y(Yape), P(Plin)
	fechahorapago		DATETIME		NULL,
	montopagado			DECIMAL(7,2)NULL,
	estado				CHAR(2)		NOT NULL DEFAULT 'PE', -- PA(Pagado), PE(Pendiente), CA(Cancelado)
	CONSTRAINT fk_idmesa_ven FOREIGN KEY (idmesa) REFERENCES mesas(idmesa),
	CONSTRAINT fk_idcliente_ven FOREIGN KEY (idcliente) REFERENCES personas(idpersona),
	CONSTRAINT fk_idempleado_ven FOREIGN KEY (idempleado) REFERENCES contratos(idcontrato),
	CONSTRAINT ck_tipocomprobante_ven CHECK (tipocomprobante IN ('BE', 'BS')), 
	CONSTRAINT ck_metodopago_ven CHECK (metodopago IN ('E', 'T', 'Y', 'P')),
	CONSTRAINT ck_montopagado_ven CHECK (montopagado > 0),
	CONSTRAINT ck_estado_ven CHECK (estado IN ('PA', 'PE', 'CA'))
) ENGINE = INNODB;

CREATE TABLE detalle_venta
(
	iddetalleventa		INT AUTO_INCREMENT PRIMARY KEY,
	idventa				INT 		NOT NULL, -- FK
	idproducto			INT 		NOT NULL, -- FK
	cantidad				TINYINT		NOT NULL,
	precioproducto		DECIMAL(7,2)	NOT NULL,
	CONSTRAINT fk_idventa_det FOREIGN KEY (idventa) REFERENCES ventas(idventa),
	CONSTRAINT fk_idproducto_det FOREIGN KEY (idproducto) REFERENCES productos(idproducto),
	CONSTRAINT ck_cantidad_det CHECK (cantidad > 0),
	CONSTRAINT ck_precioproducto_det CHECK (precioproducto >= 0)
) ENGINE = INNODB;

-- Inserciones a las tablas
INSERT INTO personas (apellidos, nombres, dni, telefono, correo, direccion) VALUES
	('Paredes Rovira', 'Xavier', '66870596', NULL, NULL, NULL),
	('Apolaya Gomez', 'Paul', '31403373', '956012030', NULL, NULL),
	('Pino Miranda', 'Maria', '71962088', NULL, NULL, NULL),
	('Campos Perez', 'Cintia', '58688678', NULL, NULL, 'Cl. Abigail Pulido # 760'),
	('Cartagena Magallanes', 'Nicole', '94511874', NULL, NULL, NULL),
	('Lurita Chávez', 'Alexander', '73790885', '977522216', 'alexanderlu244@gmail.com', 'Tambo Cañete La Garita km 213'),
	('Mendoza Quispe', 'Carlos', '26123248', '987102030', NULL, NULL),
	('Ramirez', 'Mireya', '57802365', NULL, NULL, NULL),
	('Casanova Lopez', 'Luis David', '78510059', NULL, NULL, NULL),
	('Félix Ramos', 'Christian', '74115373', '924304010', NULL, NULL),
	('Pachas', 'Kiara', '69850766', '999881122', NULL, NULL),
	('Medina de la Cruz', 'Carmen',  '14093899',NULL, 'carmenmedina@hotmail.com', NULL),
	('Guerrero Farfán', 'Jesús', '85043143', NULL, NULL, 'Condominio Los Sauces');
	
INSERT INTO turnos (turno, horainicio, horafin) VALUES
	('Mañana', '08:00:00', '12:00:00'),
	('Tarde', '12:00:00', '18:00:00'),
	('Noche', '18:00:00', '23:59:59');

INSERT INTO contratos (idempleado, cargo, idturno) VALUES
	(6, 'Administrador', NULL),
	(4, 'Chef', 2),
	(2, 'Mesero', 1);
	
INSERT INTO usuarios (idempleado, nombreusuario, claveacceso) VALUES
	(1, 'Alexander', '$2y$10$VCpxnyDPMD//XUMqa3kcPuxKcNwsgUVdaqvYdWUjKKSeCxEqqpB5i'),
	(2, 'Cintia', '$2y$10$EESBB7SbmSCq/P9w0m5iO.IHrMJofhI/Suk4SqrSsB4bbMqAVkY2K'),
	(3, 'Paul', '$2y$10$.H7VAses0eK0kwb7ogG9OuATyST4naJHR3X2XK5dWm0DIuwJaRh8G');

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

INSERT INTO ventas(idmesa, idcliente, idempleado, tipocomprobante, numcomprobante, metodopago, fechahorapago, montopagado, estado) VALUES
	(2, 7, 3, 'BE', 'BLE-000001', 'Y', DATE_ADD(NOW(), INTERVAL 30 MINUTE), 130, 'PA'),
	(3, NULL, 3, 'BS', 'BLS-000002', 'E', DATE_ADD(NOW(), INTERVAL 30 MINUTE), 50, 'PA');

INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES
	(1, 1, 2, 20),
	(1, 8, 2, 40),
	(1, 9, 2, 5),
	(2, 5, 2, 20),
	(2, 6, 2, 5);

-- PROCEDIMIENTOS ALMACENADOS
-- USUARIOS
-- LOGIN
DELIMITER $$
CREATE PROCEDURE spu_usuarios_login(IN _nombreusuario VARCHAR(50))
BEGIN
	SELECT 	usuarios.`idusuario`, personas.`apellidos`, personas.`nombres`,
				usuarios.`nombreusuario`, usuarios.`claveacceso`, usuarios.`nivelacceso`
		FROM usuarios
		INNER JOIN contratos ON contratos.`idcontrato` = usuarios.`idempleado`
		INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`		
		WHERE usuarios.nombreusuario = _nombreusuario AND usuarios.estado = '1';
END $$

CALL spu_usuarios_login('alexander');

-- VENTAS
-- LISTAR VENTAS
DELIMITER $$
CREATE PROCEDURE spu_ventas_listar()
BEGIN
	SELECT  ventas.`idventa`, 
		mesas.`nombremesa`,
		CONCAT(personas.`apellidos`, ' ', personas.`nombres`) 'cliente',
		ventas.`fechahoraorden`,
		ventas.`estado`
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		LEFT JOIN personas ON personas.`idpersona` = ventas.`idcliente`
		WHERE DATE(fechahoraorden) = CURDATE()
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
		ventas.`fechahoraorden`,
		ventas.`tipocomprobante`,
		ventas.`numcomprobante`,
		ventas.`estado`
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		LEFT JOIN personas p1 ON p1.`idpersona` = ventas.`idcliente`
		INNER JOIN contratos ON contratos.`idcontrato` = ventas.`idempleado`
		INNER JOIN personas p2 ON p2.idpersona = contratos.`idempleado`
		WHERE ventas.`idventa` = _idventa;
END $$

-- REGISTRAR
DELIMITER $$
CREATE PROCEDURE spu_ventas_registrar
(
IN _idmesa		TINYINT,
IN _idcliente		INT,
IN _idempleado		INT
)
BEGIN
	IF _idcliente = 0 THEN SET _idcliente = NULL; END IF;
	INSERT INTO ventas(idmesa, idcliente, idempleado) VALUES
		(_idmesa, _idcliente, _idempleado);
END $$

-- REGISTRAR DETALLES
DELIMITER $$
CREATE PROCEDURE spu_ventas_registrar_detalle
(
IN _idproducto		INT,
IN _cantidad		TINYINT,
IN _precioproducto 	DECIMAL(7,2)
)
BEGIN
	SET @ultima_venta_id = (SELECT MAX(idventa) AS 'last_id' FROM ventas);
	INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES
		(@ultima_venta_id, _idproducto, _cantidad, _precioproducto);
END $$

-- AGREGAR PRODUCTO - VENTA PENDIENTE
DELIMITER $$
CREATE PROCEDURE spu_detalle_venta_registrar
(
IN _idventa		INT,
IN _idproducto		INT,
IN _cantidad		TINYINT,
IN _precioproducto 	DECIMAL(7,2)
)
BEGIN
	DECLARE _existe_producto INT;
	
	SELECT COUNT(*) INTO _existe_producto FROM detalle_venta WHERE idventa = _idventa AND idproducto = _idproducto;
	
	IF _existe_producto > 0 THEN
		UPDATE detalle_venta SET 
			cantidad = cantidad + _cantidad 
		WHERE idventa = _idventa AND idproducto = _idproducto;
	ELSE
		INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES 
			(_idventa, _idproducto, _cantidad, _precioproducto);
	END IF;
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

-- MESAS
-- LISTAR
DELIMITER $$
CREATE PROCEDURE spu_mesas_listar(IN _estado CHAR(1)) 
BEGIN
	SELECT *
		FROM mesas
		WHERE estado = _estado;
END $$

-- PRODUCTOS
-- LISTAR
DELIMITER $$
CREATE PROCEDURE spu_productos_cargaropciones()
BEGIN
	SELECT idproducto, nombreproducto, precio, stock
		FROM productos
		ORDER BY nombreproducto;
END $$

-- PERSONAS
DELIMITER $$
CREATE PROCEDURE spu_personas_listar()
BEGIN
	SELECT *
		FROM personas
		ORDER BY 2,3;
END $$

-- EMPLEADOS
DELIMITER $$
CREATE PROCEDURE spu_empleados_listar()
BEGIN
	SELECT 	contratos.idempleado, personas.`apellidos`, personas.`nombres`,
		contratos.`cargo`, contratos.`idturno`
		FROM contratos
		INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`
		WHERE contratos.cargo = 'Mesero' AND contratos.estado = '1';
END $$

-- PROCEDIMIENTOS PARA GRÁFICOS
DELIMITER $$
CREATE PROCEDURE ContarVentasUltimos7Dias()
BEGIN
	SELECT COUNT(*) 'amount' 
		FROM ventas;
END $$