DROP DATABASE IF EXISTS restauranteDB;

CREATE DATABASE restauranteDB;

USE restauranteDB;

CREATE TABLE personas
(
	idpersona		INT AUTO_INCREMENT PRIMARY KEY,
	apellidos		VARCHAR(50)	NOT NULL,
	nombres 			VARCHAR(50)	NOT NULL,
	direccion		VARCHAR(100)NULL,
	telefono			CHAR(9)		NULL,
	correo			VARCHAR(50)	NULL
) ENGINE = INNODB;

CREATE TABLE empleados
(
	idempleado 		INT AUTO_INCREMENT PRIMARY KEY,
	idpersona		INT 			NOT NULL, -- FK
	nombrerol		VARCHAR(30) NOT NULL,
	fechaalta		DATETIME		NOT NULL DEFAULT NOW(),
	fechabaja		DATETIME 	NULL,
	turnoinicio		TIME 			NOT NULL,
	turnofin			TIME 			NOT NULL,
	estado			CHAR(1) 		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idpersona_emp FOREIGN KEY (idpersona) REFERENCES personas(idpersona),
 	CONSTRAINT uk_idpersona_emp UNIQUE(idpersona)
) ENGINE = INNODB;

CREATE TABLE usuarios
(
	idusuario		INT AUTO_INCREMENT PRIMARY KEY,
	idempleado		INT			NOT NULL,
	nombreusuario	VARCHAR(50)	NOT NULL,
	claveacceso		VARCHAR(200)NOT NULL,
	nivelacceso		CHAR(1)		NOT NULL DEFAULT 'I', 
	create_at		DATETIME		NOT NULL DEFAULT NOW(),
	update_at		DATETIME		NULL,
	estado			CHAR(1)		NOT NULL DEFAULT '1',
	CONSTRAINT fk_idempleado_usu FOREIGN KEY (idempleado) REFERENCES empleados(idempleado),
	CONSTRAINT uk_nombreusuario_usu UNIQUE (nombreusuario)
) ENGINE = INNODB;

CREATE TABLE mesas
(
	idmesa			INT AUTO_INCREMENT PRIMARY KEY,
	nombremesa		VARCHAR(30)	NOT NULL,
	capacidad		TINYINT		NOT NULL,
	CONSTRAINT ck_capacidad_mes CHECK (capacidad > 0)
) ENGINE = INNODB;

CREATE TABLE productos
(
	idproducto 		INT AUTO_INCREMENT PRIMARY KEY,
	tipoproducto	VARCHAR(40)	NOT NULL,
	nombreproducto	VARCHAR(40)	NOT NULL,
	descripcion		VARCHAR(100)NULL,
	precio			DECIMAL(5,2)NOT NULL,
	CONSTRAINT ck_precio_pla CHECK (precio > 0)
) ENGINE = INNODB;

CREATE TABLE pedidos
(
	idpedido			INT AUTO_INCREMENT PRIMARY KEY,
	idmesa			INT 			NOT NULL, -- FK
	idcliente		INT 			NOT NULL, -- FK
	idempleado		INT 			NOT NULL, -- FK
	fechahorapedido DATETIME	NOT NULL DEFAULT NOW(),
	CONSTRAINT fk_idmesa_ped FOREIGN KEY (idmesa) REFERENCES mesas(idmesa),
	CONSTRAINT fk_idcliente_ped FOREIGN KEY (idcliente) REFERENCES personas(idpersona),
	CONSTRAINT fk_idempleado_ped FOREIGN KEY (idempleado) REFERENCES empleados(idempleado)
) ENGINE = INNODB;

CREATE TABLE detalle_pedido
(
	iddetallepedido	INT AUTO_INCREMENT PRIMARY KEY,
	idpedido			INT 			NOT NULL, -- FK
	idproducto		INT 			NOT NULL, -- FK
	cantidad			TINYINT		NOT NULL,
	precioproducto	DECIMAL(5,2)NOT NULL,
	CONSTRAINT fk_idpedido_det FOREIGN KEY (idpedido) REFERENCES pedidos(idpedido),
	CONSTRAINT fk_idproducto_det FOREIGN KEY (idproducto) REFERENCES productos(idproducto),
	CONSTRAINT ck_cantidad_det CHECK (cantidad > 0),
	CONSTRAINT ck_precioproducto_det CHECK (precioproducto >= 0)
) ENGINE = INNODB;

-- Inserciones a las tablas
INSERT INTO personas (apellidos, nombres, direccion, telefono, correo) VALUES
	('Paredes Rovira', 'Xavier', NULL, NULL, NULL),
	('Leiva', 'Paul', NULL, '956012030', NULL),
	('Pino Miranda', 'Maria', NULL, NULL, NULL),
	('Campos Perez', 'Cintia', 'Cl. Abigail Pulido # 760', NULL, NULL),
	('Cartagena Magallanes', 'Nicole', NULL, NULL, NULL);
	
INSERT INTO empleados (idpersona, nombrerol, turnoinicio, turnofin)	VALUES
	(4, 'Chef', '13:00:00', '21:00:00'),
	(2, 'Mesero', '13:30:00', '22:00:00');
	
INSERT INTO usuarios (idempleado, nombreusuario, claveacceso) VALUES
	(1, 'Cintia', '$2y$10$EESBB7SbmSCq/P9w0m5iO.IHrMJofhI/Suk4SqrSsB4bbMqAVkY2K'),
	(2, 'Paul', '$2y$10$.H7VAses0eK0kwb7ogG9OuATyST4naJHR3X2XK5dWm0DIuwJaRh8G');
	
INSERT INTO mesas (nombremesa, capacidad) VALUES
	('Mesa 1', 3),
	('Mesa 2', 4),
	('Mesa 3', 3),
	('Mesa 4', 6),
	('Mesa 5', 2);
INSERT INTO productos (tipoproducto, nombreproducto, descripcion, precio) VALUES
	('Entrada', 'Tequeños de Lomo Saltado', '8 unidades de tequeños rellenos de lomo saltado criollo con guacamole', 20),
	('Entrada', 'Club Sandwich', 'Pollo, jamón, queso, palta, lechuga y tomate acompañado de papas fritas', 25),
	('Plato de fondo', 'Rocoto Relleno', 'Tradicional rocoto relleno arequipeño, acompañado de pastel de papas', 45),
	('Plato de fondo', 'Lomo Saltado', 'Clásico lomo fino saltado de res acompañado de papas fritas y arroz blanco', 64),
	('Postre', 'Charlotte de maracuya', 'Charlotte de maracuyá, naranja y muña', 32);

/*SELECT * FROM personas;
SELECT * FROM empleados;
SELECT * FROM usuarios;
SELECT * FROM mesas;
SELECT * FROM productos;*/

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