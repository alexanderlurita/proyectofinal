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
	estado			CHAR(1)		NOT NULL DEFAULT 'D', -- D(Disponible), O(Ocupada), R(Reservada)
	CONSTRAINT uk_nombremesa_mes UNIQUE (nombremesa),
	CONSTRAINT ck_capacidad_mes CHECK (capacidad > 0),
	CONSTRAINT ck_estado_mes CHECK (estado IN ('D', 'O', 'R'))
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

INSERT INTO personas (apellidos, nombres, dni, telefono, correo, direccion) VALUES
	('García Hernández', 'María', '53297846', '912345678', 'mgarcia@gmail.com', 'Calle Mayor, 123'),
	('López Rodríguez', 'Juan', '70984125', '667890123', 'jlopez@hotmail.com', 'Avenida Libertad, 456'),
	('Martínez González', 'Laura', '42169783', '644567890', 'lauramartinez@gmail.com', 'Calle Sol, 789'),
	('Rodríguez Fernández', 'Carlos', '36589247', '912345678', 'crodriguez@gmail.com', 'Calle Luna, 234'),
	('Hernández Sánchez', 'Ana', '57698342', '667890123', 'ahernandez@hotmail.com', 'Avenida Principal, 567'),
	('Gómez Ramírez', 'Pedro', '64821735', '644567890', 'pgomez@gmail.com', 'Calle Central, 890'),
	('López García', 'Sofía', '47382916', '912345678', 'slopez@gmail.com', 'Calle Primavera, 345'),
	('Sánchez Torres', 'Marta', '39214758', '667890123', 'msanchez@hotmail.com', 'Avenida del Parque, 678'),
	('Pérez Martínez', 'Luis', '62873941', '644567890', 'lperez@gmail.com', 'Calle Jardín, 901'),
	('Ramírez Jiménez', 'Isabel', '41273958', '912345678', 'iramirez@gmail.com', 'Calle Verano, 234');

INSERT INTO personas (apellidos, nombres, dni, telefono, correo, direccion) VALUES
	('González Castro', 'Manuel', '59873214', '667890123', 'mgonzalez@hotmail.com', 'Avenida Central, 567'),
	('Hernández García', 'Paula', '73628194', '644567890', 'phernandez@gmail.com', 'Calle Otoño, 890'),
	('Martínez Rodríguez', 'Daniel', '31947285', '912345678', 'dmartinez@gmail.com', 'Calle Invierno, 345'),
	('López Sánchez', 'Sara', '52793841', '667890123', 'slopez@hotmail.com', 'Avenida Primavera, 678'),
	('Sánchez Ramírez', 'Alejandro', '46918327', '644567890', 'asanchez@gmail.com', 'Calle Sol, 901'),
	('Gómez Torres', 'Laura', '68173942', '912345678', 'lgomez@hotmail.com', 'Calle Luna, 234'),
	('Rodríguez Pérez', 'Marcos', '39472819', '667890123', 'mrodriguez@gmail.com', 'Avenida Libertad, 567'),
	('Pérez López', 'Carolina', '54873129', '644567890', 'cperez@gmail.com', 'Calle Mayor, 890'),
	('Ramírez García', 'Javier', '61384927', '912345678', 'jramirez@hotmail.com', 'Calle Central, 123'),
	('Fernández Martínez', 'Andrea', '27846193', '667890123', 'afernandez@gmail.com', 'Avenida Principal, 456'),
	('García Sánchez', 'Lucía', '39672841', '644567890', 'lgarcia@hotmail.com', 'Calle Sol, 789'),
	('Torres Ramírez', 'Juan', '52473918', '912345678', 'jtorres@gmail.com', 'Calle Luna, 234'),
	('Pérez Sánchez', 'María', '74291835', '667890123', 'mperez@hotmail.com', 'Avenida Libertad, 567'),
	('López Martínez', 'Pablo', '61394827', '644567890', 'plopez@gmail.com', 'Calle Mayor, 890'),
	('Rodríguez Torres', 'Laura', '31972846', '912345678', 'lrodriguez@gmail.com', 'Calle Central, 123');
	
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
	
INSERT INTO mesas (nombremesa, capacidad, estado) VALUES 
	('Mesa 6', '5', 'O'),
	('Mesa 7', '2', 'D'),
	('Mesa 8', '2', 'R'),
	('Mesa 9', '3', 'D'),
	('Mesa 10', '6', 'D');
	
INSERT INTO mesas(nombremesa, capacidad) VALUES
	('Mesa 11', 6),
	('Mesa 12', 4),
	('Mesa 13', 2);
	
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
	
INSERT INTO productos (tipoproducto, nombreproducto, descripcion, precio, stock) VALUES
	('Postre', 'Tarta de Manzana', 'Deliciosa tarta de manzana con crujiente de canela y helado de vainilla', 15, 30),
	('Plato de fondo', 'Lomo de Res a la Parrilla', 'Tierno lomo de res a la parrilla con papas fritas y ensalada mixta', 38, NULL),
	('Bebida', 'Mojito Clásico', 'Refrescante cóctel de mojito con lima, menta fresca y ron blanco', 10, NULL),
	('Entrada', 'Ceviche Mixto', 'Ceviche de pescado y mariscos con limón, cebolla morada, ají y camote', 35, NULL),
	('Postre', 'Flan de Caramelo', 'Suave y cremoso flan de caramelo con salsa de caramelo y trocitos de almendra', 12, 25),
	('Plato de fondo', 'Pollo a la Brasa', 'Jugoso pollo a la brasa acompañado de papas doradas y salsa huancaina', 30, NULL),
	('Bebida', 'Margarita de Fresa', 'Refrescante margarita de fresa con tequila, jugo de limón y azúcar', 12, NULL),
	('Entrada', 'Empanadas Argentinas', 'Deliciosas empanadas argentinas rellenas de carne, pollo o verduras', 18, NULL),
	('Postre', 'Helado de Chocolate', 'Delicioso helado de chocolate con trocitos de chocolate negro y salsa de chocolate caliente', 8, 21),
	('Plato de fondo', 'Pasta Alfredo con Camarones', 'Pasta al dente con salsa Alfredo y camarones salteados en mantequilla y ajo', 28, NULL);

INSERT INTO ventas(idmesa, idcliente, idempleado, tipocomprobante, numcomprobante, metodopago, fechahorapago, montopagado, estado) VALUES
	(2, 7, 3, 'BE', 'BLE-000001', 'Y', DATE_ADD(NOW(), INTERVAL 30 MINUTE), 130, 'PA'),
	(3, NULL, 3, 'BS', 'BLS-000002', 'E', DATE_ADD(NOW(), INTERVAL 30 MINUTE), 50, 'PA');

INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES
	(1, 1, 3, 20),
	(1, 8, 3, 40),
	(1, 9, 3, 5),
	(2, 5, 3, 20),
	(2, 6, 3, 5);