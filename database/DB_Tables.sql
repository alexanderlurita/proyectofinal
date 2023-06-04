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
	estado 			CHAR(1) 	NOT NULL DEFAULT '1'
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