/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.27-MariaDB : Database - restaurantedb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`restaurantedb` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `restaurantedb`;

/*Table structure for table `detalle_venta` */

DROP TABLE IF EXISTS `detalle_venta`;

CREATE TABLE `detalle_venta` (
  `iddetalleventa` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `idproducto` int(11) NOT NULL,
  `cantidad` tinyint(4) NOT NULL,
  `precioproducto` decimal(7,2) NOT NULL,
  PRIMARY KEY (`iddetalleventa`),
  KEY `fk_idventa_det` (`idventa`),
  KEY `fk_idproducto_det` (`idproducto`),
  CONSTRAINT `fk_idproducto_det` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`idproducto`),
  CONSTRAINT `fk_idventa_det` FOREIGN KEY (`idventa`) REFERENCES `ventas` (`idventa`),
  CONSTRAINT `ck_cantidad_det` CHECK (`cantidad` > 0),
  CONSTRAINT `ck_precioproducto_det` CHECK (`precioproducto` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detalle_venta` */

insert  into `detalle_venta`(`iddetalleventa`,`idventa`,`idproducto`,`cantidad`,`precioproducto`) values 
(1,1,1,2,20.00),
(2,1,8,2,40.00),
(3,1,9,2,5.00),
(4,2,5,2,20.00),
(5,2,6,2,5.00),
(6,3,5,2,32.00),
(7,3,3,2,45.00),
(8,3,9,2,5.00),
(9,3,6,1,5.00),
(10,4,2,1,25.00),
(11,4,6,1,5.00),
(12,5,5,1,32.00),
(13,4,7,1,25.00),
(14,5,9,2,5.00);

/*Table structure for table `empleados` */

DROP TABLE IF EXISTS `empleados`;

CREATE TABLE `empleados` (
  `idempleado` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `cargo` varchar(30) NOT NULL,
  `fechacontrato` datetime NOT NULL DEFAULT current_timestamp(),
  `fechadespido` datetime DEFAULT NULL,
  `idturno` tinyint(4) DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idempleado`),
  UNIQUE KEY `uk_idpersona_emp` (`idpersona`),
  KEY `fk_idturno_emp` (`idturno`),
  CONSTRAINT `fk_idpersona_emp` FOREIGN KEY (`idpersona`) REFERENCES `personas` (`idpersona`),
  CONSTRAINT `fk_idturno_emp` FOREIGN KEY (`idturno`) REFERENCES `turnos` (`idturno`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `empleados` */

insert  into `empleados`(`idempleado`,`idpersona`,`cargo`,`fechacontrato`,`fechadespido`,`idturno`,`estado`) values 
(1,4,'Chef','2023-05-28 15:18:28',NULL,2,'1'),
(2,2,'Mesero','2023-05-28 15:18:28',NULL,1,'1'),
(3,6,'Administrador','2023-05-28 15:18:28',NULL,NULL,'1');

/*Table structure for table `mesas` */

DROP TABLE IF EXISTS `mesas`;

CREATE TABLE `mesas` (
  `idmesa` tinyint(4) NOT NULL AUTO_INCREMENT,
  `nombremesa` varchar(40) NOT NULL,
  `capacidad` tinyint(4) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'D',
  PRIMARY KEY (`idmesa`),
  UNIQUE KEY `uk_nombremesa_mes` (`nombremesa`),
  CONSTRAINT `ck_capacidad_mes` CHECK (`capacidad` > 0),
  CONSTRAINT `ck_estado_mes` CHECK (`estado` in ('D','O','R','M'))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `mesas` */

insert  into `mesas`(`idmesa`,`nombremesa`,`capacidad`,`estado`) values 
(1,'Mesa 1',3,'D'),
(2,'Mesa 2',4,'D'),
(3,'Mesa 3',3,'D'),
(4,'Mesa 4',6,'D'),
(5,'Mesa 5',2,'D');

/*Table structure for table `pagos` */

DROP TABLE IF EXISTS `pagos`;

CREATE TABLE `pagos` (
  `idpago` int(11) NOT NULL AUTO_INCREMENT,
  `idventa` int(11) NOT NULL,
  `metodopago` char(1) NOT NULL,
  `montopagado` decimal(7,2) NOT NULL,
  `fechahorapago` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idpago`),
  KEY `fk_idventa_pag` (`idventa`),
  CONSTRAINT `fk_idventa_pag` FOREIGN KEY (`idventa`) REFERENCES `ventas` (`idventa`),
  CONSTRAINT `ck_metodopago_pag` CHECK (`metodopago` in ('E','T','Y','P'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `pagos` */

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `apellidos` varchar(50) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `telefono` char(9) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idpersona`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personas` */

insert  into `personas`(`idpersona`,`apellidos`,`nombres`,`telefono`,`correo`,`direccion`) values 
(1,'Paredes Rovira','Xavier',NULL,NULL,NULL),
(2,'Apolaya Gomez','Paul','956012030',NULL,NULL),
(3,'Pino Miranda','Maria',NULL,NULL,NULL),
(4,'Campos Perez','Cintia',NULL,NULL,'Cl. Abigail Pulido # 760'),
(5,'Cartagena Magallanes','Nicole',NULL,NULL,NULL),
(6,'Lurita Chávez','Alexander','977522216','alexanderlu244@gmail.com','Tambo Cañete La Garita km 213'),
(7,'Mendoza Quispe','Carlos','987102030',NULL,NULL),
(8,'Ramirez','Mireya',NULL,NULL,NULL),
(9,'Casanova Lopez','Luis David',NULL,NULL,NULL),
(10,'Félix','Christian','924304010',NULL,NULL),
(11,'Pachas','Kiara','999881122',NULL,NULL),
(12,'Medina de la Cruz','Carmen',NULL,'carmenmedina@hotmail.com',NULL),
(13,'Guerrero Farfán','Jesús',NULL,NULL,'Condominio Los Sauces');

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `tipoproducto` varchar(40) NOT NULL,
  `nombreproducto` varchar(50) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `precio` decimal(7,2) NOT NULL,
  `stock` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idproducto`),
  UNIQUE KEY `uk_producto_pla` (`tipoproducto`,`nombreproducto`),
  CONSTRAINT `ck_precio_pla` CHECK (`precio` > 0),
  CONSTRAINT `ck_stock_pla` CHECK (`stock` >= 0)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `productos` */

insert  into `productos`(`idproducto`,`tipoproducto`,`nombreproducto`,`descripcion`,`precio`,`stock`) values 
(1,'Entrada','Tequeños de Lomo Saltado','8 unidades de tequeños rellenos de lomo saltado criollo con guacamole',20.00,NULL),
(2,'Entrada','Club Sandwich','Pollo, jamón, queso, palta, lechuga y tomate acompañado de papas fritas',25.00,NULL),
(3,'Plato de fondo','Rocoto Relleno','Tradicional rocoto relleno arequipeño, acompañado de pastel de papas',45.00,NULL),
(4,'Plato de fondo','Lomo Saltado','Clásico lomo fino saltado de res acompañado de papas fritas y arroz blanco',64.00,NULL),
(5,'Postre','Charlotte de maracuya','Charlotte de maracuyá, naranja y muña',32.00,20),
(6,'Bebida','Inca Kola 600ml','Vaso de Inca Kola + hielos',5.00,16),
(7,'Entrada','Anticucho de corazón especial','Dos palitos de trozos tiernos de corazón de res, acompañados con choclo José Antonio, papa dorada y salsa criolla',25.00,NULL),
(8,'Plato de fondo','Cau Cau','Receta en base a trozos de mondongo y papa, acompañada con arroz blanco',40.00,NULL),
(9,'Bebida','Chicha morada','Vaso grande de chicha morada + hielos',5.00,NULL);

/*Table structure for table `turnos` */

DROP TABLE IF EXISTS `turnos`;

CREATE TABLE `turnos` (
  `idturno` tinyint(4) NOT NULL AUTO_INCREMENT,
  `turno` varchar(30) NOT NULL,
  `horainicio` time NOT NULL,
  `horafin` time NOT NULL,
  PRIMARY KEY (`idturno`),
  UNIQUE KEY `uk_turno_tur` (`turno`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `turnos` */

insert  into `turnos`(`idturno`,`turno`,`horainicio`,`horafin`) values 
(1,'Mañana','08:00:00','12:00:00'),
(2,'Tarde','12:00:00','18:00:00'),
(3,'Noche','18:00:00','23:59:59');

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL,
  `nombreusuario` varchar(50) NOT NULL,
  `claveacceso` varchar(200) NOT NULL,
  `nivelacceso` char(1) NOT NULL DEFAULT 'E',
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `uk_idempleado_usu` (`idempleado`),
  UNIQUE KEY `uk_nombreusuario_usu` (`nombreusuario`),
  CONSTRAINT `fk_idempleado_usu` FOREIGN KEY (`idempleado`) REFERENCES `empleados` (`idempleado`),
  CONSTRAINT `ck_nivelacceso_usu` CHECK (`nivelacceso` in ('A','E','S'))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`idusuario`,`idempleado`,`nombreusuario`,`claveacceso`,`nivelacceso`,`create_at`,`update_at`,`estado`) values 
(1,1,'Cintia','$2y$10$EESBB7SbmSCq/P9w0m5iO.IHrMJofhI/Suk4SqrSsB4bbMqAVkY2K','E','2023-05-28 15:18:28',NULL,'1'),
(2,2,'Paul','$2y$10$.H7VAses0eK0kwb7ogG9OuATyST4naJHR3X2XK5dWm0DIuwJaRh8G','E','2023-05-28 15:18:28',NULL,'1'),
(3,3,'Alexander','$2y$10$VCpxnyDPMD//XUMqa3kcPuxKcNwsgUVdaqvYdWUjKKSeCxEqqpB5i','E','2023-05-28 20:54:23',NULL,'1');

/*Table structure for table `ventas` */

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `idmesa` tinyint(4) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idempleado` int(11) NOT NULL,
  `fechahoraventa` datetime NOT NULL DEFAULT current_timestamp(),
  `tipocomprobante` char(1) DEFAULT NULL,
  `numcomprobante` char(10) DEFAULT NULL,
  `estado` char(2) NOT NULL DEFAULT 'PE',
  PRIMARY KEY (`idventa`),
  KEY `fk_idmesa_ped` (`idmesa`),
  KEY `fk_idcliente_ped` (`idcliente`),
  KEY `fk_idempleado_ped` (`idempleado`),
  CONSTRAINT `fk_idcliente_ped` FOREIGN KEY (`idcliente`) REFERENCES `personas` (`idpersona`),
  CONSTRAINT `fk_idempleado_ped` FOREIGN KEY (`idempleado`) REFERENCES `empleados` (`idempleado`),
  CONSTRAINT `fk_idmesa_ped` FOREIGN KEY (`idmesa`) REFERENCES `mesas` (`idmesa`),
  CONSTRAINT `ck_tipocomprobante_ped` CHECK (`tipocomprobante` in ('B','F')),
  CONSTRAINT `ck_estado_ped` CHECK (`estado` in ('PA','PE','CA'))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `ventas` */

insert  into `ventas`(`idventa`,`idmesa`,`idcliente`,`idempleado`,`fechahoraventa`,`tipocomprobante`,`numcomprobante`,`estado`) values 
(1,2,7,2,'2023-05-28 15:18:28','B','BOL-000001','PA'),
(2,3,8,2,'2023-05-28 15:18:28','B','BOL-000002','PA'),
(3,5,5,2,'2023-05-28 15:19:20',NULL,NULL,'PE'),
(4,1,1,2,'2023-05-28 15:31:59',NULL,NULL,'PE'),
(5,1,3,2,'2023-05-28 20:17:35',NULL,NULL,'PE');

/* Procedure structure for procedure `spu_detalle_venta_registrar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_detalle_venta_registrar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_detalle_venta_registrar`(
IN _idventa		INT,
IN _idproducto		INT,
IN _cantidad		TINYINT,
IN _precioproducto 	DECIMAL(7,2)
)
BEGIN
	declare _existe_producto int;
	
	SELECT COUNT(*) INTO _existe_producto FROM detalle_venta WHERE idventa = _idventa AND idproducto = _idproducto;
	
	IF _existe_producto > 0 THEN
		UPDATE detalle_venta SET 
			cantidad = cantidad + _cantidad 
		WHERE idventa = _idventa AND idproducto = _idproducto;
	ELSE
		INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES 
			(_idventa, _idproducto, _cantidad, _precioproducto);
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_empleados_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_empleados_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_empleados_listar`()
begin
	select 	empleados.idempleado, personas.`apellidos`, personas.`nombres`,
		empleados.`cargo`, empleados.`idturno`
		from empleados
		inner join personas on personas.`idpersona` = empleados.`idpersona`
		where empleados.cargo = 'Mesero' and empleados.estado = '1';
end */$$
DELIMITER ;

/* Procedure structure for procedure `spu_mesas_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_mesas_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_mesas_listar`(IN _estado CHAR(1))
BEGIN
	SELECT *
		FROM mesas
		WHERE estado = _estado;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_personas_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_personas_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_personas_listar`()
BEGIN
	SELECT *
		FROM personas
		ORDER BY 2,3;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_cargaropciones` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_cargaropciones` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_cargaropciones`()
BEGIN
	SELECT idproducto, nombreproducto, precio, stock
		FROM productos
		ORDER BY nombreproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_login` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_login`(IN _nombreusuario VARCHAR(50))
BEGIN
	SELECT 	usuarios.idusuario, personas.apellidos, personas.nombres,
		usuarios.nombreusuario, usuarios.claveacceso, usuarios.nivelacceso
		FROM usuarios
		INNER JOIN empleados ON empleados.idempleado = usuarios.idempleado
		INNER JOIN personas ON personas.idpersona = empleados.idpersona
		WHERE usuarios.nombreusuario = _nombreusuario AND usuarios.estado = '1';
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_buscar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_buscar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_buscar`(IN _idventa INT)
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
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_detallar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_detallar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_detallar`(IN _idventa INT)
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
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_listar`()
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
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_registrar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_registrar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_registrar`(
in _idmesa		tinyint,
in _idcliente		int,
in _idempleado		int
)
begin
	insert into ventas(idmesa, idcliente, idempleado) values
		(_idmesa, _idcliente, _idempleado);
end */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_registrar_detalle` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_registrar_detalle` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_registrar_detalle`(
in _idproducto		int,
in _cantidad		tinyint,
in _precioproducto 	decimal(7,2)
)
begin
	set @ultima_venta_id = (select max(idventa) as 'last_id' from ventas);
	insert into detalle_venta(idventa, idproducto, cantidad, precioproducto) values
		(@ultima_venta_id, _idproducto, _cantidad, _precioproducto);
end */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
