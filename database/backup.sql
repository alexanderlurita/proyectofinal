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

/*Table structure for table `contratos` */

DROP TABLE IF EXISTS `contratos`;

CREATE TABLE `contratos` (
  `idcontrato` int(11) NOT NULL AUTO_INCREMENT,
  `idempleado` int(11) NOT NULL,
  `cargo` varchar(30) NOT NULL,
  `fechainicio` datetime NOT NULL DEFAULT current_timestamp(),
  `fechatermino` datetime DEFAULT NULL,
  `idturno` tinyint(4) DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idcontrato`),
  UNIQUE KEY `uk_idempleado_cont` (`idempleado`,`cargo`),
  KEY `fk_idturno_cont` (`idturno`),
  CONSTRAINT `fk_idempleado_cont` FOREIGN KEY (`idempleado`) REFERENCES `personas` (`idpersona`),
  CONSTRAINT `fk_idturno_cont` FOREIGN KEY (`idturno`) REFERENCES `turnos` (`idturno`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `contratos` */

insert  into `contratos`(`idcontrato`,`idempleado`,`cargo`,`fechainicio`,`fechatermino`,`idturno`,`estado`) values 
(1,6,'Administrador','2023-05-29 19:40:49',NULL,NULL,'1'),
(2,4,'Chef','2023-05-29 19:40:49',NULL,2,'1'),
(3,2,'Mesero','2023-05-29 19:40:49',NULL,1,'1'),
(4,39,'Mesero','2023-06-01 10:31:09',NULL,2,'1'),
(5,40,'Chef','2023-06-01 10:31:09',NULL,3,'1'),
(6,41,'Mesero','2023-06-01 10:31:09',NULL,3,'1');

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detalle_venta` */

insert  into `detalle_venta`(`iddetalleventa`,`idventa`,`idproducto`,`cantidad`,`precioproducto`) values 
(1,1,1,2,20.00),
(2,1,8,2,40.00),
(3,1,9,2,5.00),
(4,2,5,2,20.00),
(5,2,6,2,5.00),
(6,6,14,1,12.00),
(7,6,18,2,8.00),
(8,6,13,1,35.00),
(9,6,6,3,5.00),
(10,6,15,2,30.00),
(11,7,14,2,12.00),
(12,7,9,2,5.00),
(13,7,11,2,38.00),
(14,7,10,1,15.00),
(15,8,15,2,30.00),
(16,8,10,2,15.00),
(17,8,16,2,12.00),
(18,9,9,3,5.00),
(19,9,6,2,5.00),
(20,9,2,5,25.00),
(21,9,11,2,38.00),
(22,9,4,1,64.00),
(23,9,3,1,45.00),
(24,10,6,1,5.00),
(25,10,13,2,35.00),
(26,10,17,2,18.00),
(27,10,9,1,5.00),
(28,11,18,3,8.00),
(29,11,6,2,5.00),
(30,11,5,2,32.00);

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `mesas` */

insert  into `mesas`(`idmesa`,`nombremesa`,`capacidad`,`estado`) values 
(1,'Mesa 1',3,'D'),
(2,'Mesa 2',4,'D'),
(3,'Mesa 3',3,'D'),
(4,'Mesa 4',6,'D'),
(5,'Mesa 5',2,'D'),
(6,'Mesa 6',5,'D'),
(7,'Mesa 7',2,'D'),
(8,'Mesa 8',2,'D'),
(9,'Mesa 9',3,'D'),
(10,'Mesa 10',6,'D'),
(11,'Mesa 11',6,'D'),
(12,'Mesa 12',4,'D'),
(13,'Mesa 13',2,'D');

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `apellidos` varchar(50) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `dni` char(8) NOT NULL,
  `telefono` char(9) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`idpersona`),
  UNIQUE KEY `uk_dni_per` (`dni`),
  CONSTRAINT `ck_dni_per` CHECK (`dni` regexp '^[0-9]{8}$')
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personas` */

insert  into `personas`(`idpersona`,`apellidos`,`nombres`,`dni`,`telefono`,`correo`,`direccion`) values 
(1,'Paredes Rovira','Xavier','66870596',NULL,NULL,NULL),
(2,'Apolaya Gomez','Paul','31403373','956012030',NULL,NULL),
(3,'Pino Miranda','Maria','71962088',NULL,NULL,NULL),
(4,'Campos Perez','Cintia','58688678',NULL,NULL,'Cl. Abigail Pulido # 760'),
(5,'Cartagena Magallanes','Nicole','94511874',NULL,NULL,NULL),
(6,'Lurita Chávez','Alexander','73790885','977522216','alexanderlu244@gmail.com','Tambo Cañete La Garita km 213'),
(7,'Mendoza Quispe','Carlos','26123248','987102030',NULL,NULL),
(8,'Ramirez','Mireya','57802365',NULL,NULL,NULL),
(9,'Casanova Lopez','Luis David','78510059',NULL,NULL,NULL),
(10,'Félix Ramos','Christian','74115373','924304010',NULL,NULL),
(11,'Pachas','Kiara','69850766','999881122',NULL,NULL),
(12,'Medina de la Cruz','Carmen','14093899',NULL,'carmenmedina@hotmail.com',NULL),
(13,'Guerrero Farfán','Jesús','85043143',NULL,NULL,'Condominio Los Sauces'),
(14,'García Hernández','María','53297846','912345678','mgarcia@gmail.com','Calle Mayor, 123'),
(15,'López Rodríguez','Juan','70984125','667890123','jlopez@hotmail.com','Avenida Libertad, 456'),
(16,'Martínez González','Laura','42169783','644567890','lauramartinez@gmail.com','Calle Sol, 789'),
(17,'Rodríguez Fernández','Carlos','36589247','912345678','crodriguez@gmail.com','Calle Luna, 234'),
(18,'Hernández Sánchez','Ana','57698342','667890123','ahernandez@hotmail.com','Avenida Principal, 567'),
(19,'Gómez Ramírez','Pedro','64821735','644567890','pgomez@gmail.com','Calle Central, 890'),
(20,'López García','Sofía','47382916','912345678','slopez@gmail.com','Calle Primavera, 345'),
(21,'Sánchez Torres','Marta','39214758','667890123','msanchez@hotmail.com','Avenida del Parque, 678'),
(22,'Pérez Martínez','Luis','62873941','644567890','lperez@gmail.com','Calle Jardín, 901'),
(23,'Ramírez Jiménez','Isabel','41273958','912345678','iramirez@gmail.com','Calle Verano, 234'),
(24,'González Castro','Manuel','59873214','667890123','mgonzalez@hotmail.com','Avenida Central, 567'),
(25,'Hernández García','Paula','73628194','644567890','phernandez@gmail.com','Calle Otoño, 890'),
(26,'Martínez Rodríguez','Daniel','31947285','912345678','dmartinez@gmail.com','Calle Invierno, 345'),
(27,'López Sánchez','Sara','52793841','667890123','slopez@hotmail.com','Avenida Primavera, 678'),
(28,'Sánchez Ramírez','Alejandro','46918327','644567890','asanchez@gmail.com','Calle Sol, 901'),
(29,'Gómez Torres','Laura','68173942','912345678','lgomez@hotmail.com','Calle Luna, 234'),
(30,'Rodríguez Pérez','Marcos','39472819','667890123','mrodriguez@gmail.com','Avenida Libertad, 567'),
(31,'Pérez López','Carolina','54873129','644567890','cperez@gmail.com','Calle Mayor, 890'),
(32,'Ramírez García','Javier','61384927','912345678','jramirez@hotmail.com','Calle Central, 123'),
(33,'Fernández Martínez','Andrea','27846193','667890123','afernandez@gmail.com','Avenida Principal, 456'),
(34,'García Sánchez','Lucía','39672841','644567890','lgarcia@hotmail.com','Calle Sol, 789'),
(35,'Torres Ramírez','Juan','52473918','912345678','jtorres@gmail.com','Calle Luna, 234'),
(36,'Pérez Sánchez','María','74291835','667890123','mperez@hotmail.com','Avenida Libertad, 567'),
(37,'López Martínez','Pablo','61394827','644567890','plopez@gmail.com','Calle Mayor, 890'),
(38,'Rodríguez Torres','Laura','31972846','912345678','lrodriguez@gmail.com','Calle Central, 123'),
(39,'López Ramírez','María','45682197','665478912','mlopez@gmail.com','Calle Principal, 123'),
(40,'Pérez Martínez','Juan','78965432','669874561','jpmartinez@gmail.com','Calle Secundaria, 789'),
(41,'Sánchez Herrera','Laura','12345678','668765432','lsanchez@gmail.com','Avenida Norte, 321'),
(42,'Torres Marcelo','Luzmila','45454545',NULL,NULL,NULL),
(43,'Yauri De Ccente','Leona','23241023',NULL,NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(9,'Bebida','Chicha morada','Vaso grande de chicha morada + hielos',5.00,NULL),
(10,'Postre','Tarta de Manzana','Deliciosa tarta de manzana con crujiente de canela y helado de vainilla',15.00,30),
(11,'Plato de fondo','Lomo de Res a la Parrilla','Tierno lomo de res a la parrilla con papas fritas y ensalada mixta',38.00,NULL),
(12,'Bebida','Mojito Clásico','Refrescante cóctel de mojito con lima, menta fresca y ron blanco',10.00,NULL),
(13,'Entrada','Ceviche Mixto','Ceviche de pescado y mariscos con limón, cebolla morada, ají y camote',35.00,NULL),
(14,'Postre','Flan de Caramelo','Suave y cremoso flan de caramelo con salsa de caramelo y trocitos de almendra',12.00,25),
(15,'Plato de fondo','Pollo a la Brasa','Jugoso pollo a la brasa acompañado de papas doradas y salsa huancaina',30.00,NULL),
(16,'Bebida','Margarita de Fresa','Refrescante margarita de fresa con tequila, jugo de limón y azúcar',12.00,NULL),
(17,'Entrada','Empanadas Argentinas','Deliciosas empanadas argentinas rellenas de carne, pollo o verduras',18.00,NULL),
(18,'Postre','Helado de Chocolate','Delicioso helado de chocolate con trocitos de chocolate negro y salsa de chocolate caliente',8.00,21),
(19,'Plato de fondo','Pasta Alfredo con Camarones','Pasta al dente con salsa Alfredo y camarones salteados en mantequilla y ajo',28.00,NULL);

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
  CONSTRAINT `fk_idempleado_usu` FOREIGN KEY (`idempleado`) REFERENCES `contratos` (`idcontrato`),
  CONSTRAINT `ck_nivelacceso_usu` CHECK (`nivelacceso` in ('A','E','S'))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`idusuario`,`idempleado`,`nombreusuario`,`claveacceso`,`nivelacceso`,`create_at`,`update_at`,`estado`) values 
(1,1,'Alexander','$2y$10$VCpxnyDPMD//XUMqa3kcPuxKcNwsgUVdaqvYdWUjKKSeCxEqqpB5i','E','2023-05-29 19:40:49',NULL,'1'),
(2,2,'Cintia','$2y$10$EESBB7SbmSCq/P9w0m5iO.IHrMJofhI/Suk4SqrSsB4bbMqAVkY2K','E','2023-05-29 19:40:49',NULL,'1'),
(3,3,'Paul','$2y$10$.H7VAses0eK0kwb7ogG9OuATyST4naJHR3X2XK5dWm0DIuwJaRh8G','E','2023-05-29 19:40:49',NULL,'1');

/*Table structure for table `ventas` */

DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `idmesa` tinyint(4) NOT NULL,
  `idcliente` int(11) DEFAULT NULL,
  `idempleado` int(11) NOT NULL,
  `fechahoraorden` datetime NOT NULL DEFAULT current_timestamp(),
  `tipocomprobante` char(2) DEFAULT NULL,
  `numcomprobante` char(10) DEFAULT NULL,
  `metodopago` char(1) DEFAULT NULL,
  `fechahorapago` datetime DEFAULT NULL,
  `montopagado` decimal(7,2) DEFAULT NULL,
  `estado` char(2) NOT NULL DEFAULT 'PE',
  PRIMARY KEY (`idventa`),
  KEY `fk_idmesa_ven` (`idmesa`),
  KEY `fk_idcliente_ven` (`idcliente`),
  KEY `fk_idempleado_ven` (`idempleado`),
  CONSTRAINT `fk_idcliente_ven` FOREIGN KEY (`idcliente`) REFERENCES `personas` (`idpersona`),
  CONSTRAINT `fk_idempleado_ven` FOREIGN KEY (`idempleado`) REFERENCES `contratos` (`idcontrato`),
  CONSTRAINT `fk_idmesa_ven` FOREIGN KEY (`idmesa`) REFERENCES `mesas` (`idmesa`),
  CONSTRAINT `ck_tipocomprobante_ven` CHECK (`tipocomprobante` in ('BE','BS')),
  CONSTRAINT `ck_metodopago_ven` CHECK (`metodopago` in ('E','T','Y','P')),
  CONSTRAINT `ck_montopagado_ven` CHECK (`montopagado` > 0),
  CONSTRAINT `ck_estado_ven` CHECK (`estado` in ('PA','PE','CA'))
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `ventas` */

insert  into `ventas`(`idventa`,`idmesa`,`idcliente`,`idempleado`,`fechahoraorden`,`tipocomprobante`,`numcomprobante`,`metodopago`,`fechahorapago`,`montopagado`,`estado`) values 
(1,2,7,3,'2023-05-29 19:40:50','BE','BLE-000001','Y','2023-05-29 20:10:50',130.00,'PA'),
(2,3,NULL,3,'2023-05-29 19:40:50','BS','BLS-000002','E','2023-05-29 20:10:50',50.00,'PA'),
(6,1,NULL,3,'2023-06-01 11:21:11','BS','BLS-000003','T','2023-06-01 12:11:23',138.00,'PA'),
(7,3,28,4,'2023-06-01 11:32:25','BE','BLE-000004','T','2023-06-01 12:34:14',125.00,'PA'),
(8,5,42,6,'2023-06-01 12:00:26','BE','BLE-000005','T','2023-06-01 12:47:24',114.00,'PA'),
(9,6,NULL,3,'2023-06-01 11:58:08','BS','BLS-000006','E','2023-06-01 12:51:02',335.00,'PA'),
(10,8,43,4,'2023-06-01 14:21:57','BE','BLE-000007','Y','2023-06-01 14:48:17',116.00,'PA'),
(11,1,NULL,4,'2023-06-01 14:50:20','BS','BLS-000008','E','2023-06-01 15:25:04',98.00,'PA');

/* Procedure structure for procedure `ContarClientes` */

/*!50003 DROP PROCEDURE IF EXISTS  `ContarClientes` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ContarClientes`()
begin
	select count(*) as total_clientes
		from personas
		WHERE idpersona NOT IN (SELECT idempleado FROM contratos);
end */$$
DELIMITER ;

/* Procedure structure for procedure `ContarProductosConsumidos` */

/*!50003 DROP PROCEDURE IF EXISTS  `ContarProductosConsumidos` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ContarProductosConsumidos`()
BEGIN
    SELECT SUM(cantidad) as total_productos
	FROM detalle_venta;
END */$$
DELIMITER ;

/* Procedure structure for procedure `ObtenerTotalOrdenes` */

/*!50003 DROP PROCEDURE IF EXISTS  `ObtenerTotalOrdenes` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerTotalOrdenes`()
BEGIN
    select count(*) as total_ordenes from ventas;
END */$$
DELIMITER ;

/* Procedure structure for procedure `ObtenerTotalVentasPagadas` */

/*!50003 DROP PROCEDURE IF EXISTS  `ObtenerTotalVentasPagadas` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerTotalVentasPagadas`()
BEGIN
    SELECT COUNT(*) AS total_ventas 
	FROM ventas
	where estado = "PA";
END */$$
DELIMITER ;

/* Procedure structure for procedure `ObtenerVentasPorEmpleado` */

/*!50003 DROP PROCEDURE IF EXISTS  `ObtenerVentasPorEmpleado` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerVentasPorEmpleado`()
BEGIN
    SELECT Concat(personas.`apellidos`, ' ', personas.`nombres`) AS empleado, COUNT(*) AS total_ventas
	FROM ventas
	INNER JOIN contratos ON contratos.`idcontrato` = ventas.`idempleado` 
	inner join personas on personas.`idpersona` = contratos.`idempleado`
	WHERE ventas.`estado` = 'PA' AND ventas.fechahoraorden >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
	GROUP BY empleado;
END */$$
DELIMITER ;

/* Procedure structure for procedure `ObtenerVentasPorTipo` */

/*!50003 DROP PROCEDURE IF EXISTS  `ObtenerVentasPorTipo` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerVentasPorTipo`()
BEGIN
	SELECT p.tipoproducto, SUM(dv.cantidad) AS total_cantidad
		FROM ventas v
		INNER JOIN detalle_venta dv ON dv.idventa = v.idventa
		INNER JOIN productos p ON p.idproducto = dv.idproducto
		WHERE DATE(v.fechahoraorden) BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
		GROUP BY p.tipoproducto;
END */$$
DELIMITER ;

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
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_empleados_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_empleados_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_empleados_listar`()
BEGIN
	SELECT 	contratos.idcontrato, personas.`apellidos`, personas.`nombres`,
		contratos.`cargo`, contratos.`idturno`
		FROM contratos
		INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`
		WHERE contratos.cargo = 'Mesero' AND contratos.estado = '1';
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_mesas_cambiarestado` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_mesas_cambiarestado` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_mesas_cambiarestado`(
in _idmesa	 	tinyint,
in _estado 		char(1)
)
begin
	update mesas set
		estado = _estado
	where idmesa = _idmesa;
end */$$
DELIMITER ;

/* Procedure structure for procedure `spu_mesas_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_mesas_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_mesas_listar`()
BEGIN
	SELECT *
		FROM mesas;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_personas_buscar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_personas_buscar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_personas_buscar`(in _dni char(8))
begin
	select * 
		from personas
		where dni = _dni;
end */$$
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
	SELECT idproducto, tipoproducto, nombreproducto, precio, stock
		FROM productos
		ORDER BY nombreproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_login` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_login`(IN _nombreusuario VARCHAR(50))
BEGIN
	SELECT 	usuarios.`idusuario`, personas.`apellidos`, personas.`nombres`,
				usuarios.`nombreusuario`, usuarios.`claveacceso`, usuarios.`nivelacceso`
		FROM usuarios
		INNER JOIN contratos ON contratos.`idcontrato` = usuarios.`idempleado`
		INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`		
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
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_detallar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_detallar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_detallar`(
IN _idventa INT,
in _idmesa 	tinyint
)
BEGIN
	SELECT 	DET.iddetalleventa, 
		PRO.nombreproducto, 
		DET.cantidad, 
		DET.precioproducto,
		DET.cantidad * DET.precioproducto 'importe'
		FROM detalle_venta DET
		INNER JOIN ventas VEN ON VEN.idventa = DET.idventa
		INNER JOIN productos PRO ON PRO.idproducto = DET.idproducto
		WHERE DET.idventa = _idventa and VEN.idmesa = _idmesa;
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
		ventas.`fechahoraorden`,
		ventas.`estado`
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		LEFT JOIN personas ON personas.`idpersona` = ventas.`idcliente`
		WHERE DATE(fechahoraorden) = CURDATE()
		ORDER BY 1 DESC;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_obtenerIdVentaPorMesa` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_obtenerIdVentaPorMesa` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_obtenerIdVentaPorMesa`(in _idmesa tinyint)
begin
	select ventas.`idventa` 
		from ventas
		inner join mesas on mesas.`idmesa` = ventas.`idmesa`
		where mesas.`idmesa` = _idmesa and ventas.`estado` = 'PE';
end */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_realizarpago` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_realizarpago` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_realizarpago`(
IN _idventa		INT,
IN _apellidos		VARCHAR(50),
IN _nombres		VARCHAR(50),
IN _dni			CHAR(8),
IN _tipocomprobante 	CHAR(2),
IN _metodopago 		CHAR(1),
IN _montopagado 	DECIMAL(7,2)
)
BEGIN
	-- Variable que guardará el num documento generado
	DECLARE v_numcomprobante 	VARCHAR(10);
	DECLARE v_prefijo 		CHAR(4);
	DECLARE v_idpersona 		INT;
	
	IF _tipocomprobante = 'BS' THEN SET v_prefijo = 'BLS-';
	ELSE SET v_prefijo = 'BLE-'; END IF;
	
	SET v_numcomprobante = CONCAT(v_prefijo, LPAD((SELECT MAX(SUBSTRING(numcomprobante, 5))+1 FROM ventas), 6, '0'));

	IF _tipocomprobante = 'BS' THEN
		-- Actualizamos la venta con cliente en NULL
		UPDATE ventas SET 
			idcliente = NULL,
			tipocomprobante = _tipocomprobante,
			numcomprobante = v_numcomprobante,
			metodopago = _metodopago,
			fechahorapago = NOW(),
			montopagado = _montopagado,
			estado = 'PA'
		WHERE idventa = _idventa;
	ELSE 
		-- Buscar el ID del cliente en la tabla personas
		SET v_idpersona = (SELECT idpersona FROM personas WHERE dni = _dni);
		
		IF v_idpersona IS NULL THEN
			-- Insertamos nuevo cliente en la tabla personas
			INSERT INTO personas (apellidos, nombres, dni) VALUES
				(_apellidos, _nombres, _dni);
			SET v_idpersona = LAST_INSERT_ID();
		END IF;
		
		UPDATE ventas SET 
			idcliente = v_idpersona,
			tipocomprobante = _tipocomprobante,
			numcomprobante = v_numcomprobante,
			metodopago = _metodopago,
			fechahorapago = NOW(),
			montopagado = _montopagado,
			estado = 'PA'
		WHERE idventa = _idventa;
	END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_registrar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_registrar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_registrar`(
IN _idmesa		TINYINT,
IN _idempleado		INT
)
BEGIN
	INSERT INTO ventas(idmesa, idempleado) VALUES
		(_idmesa, _idempleado);
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_ventas_registrar_detalle` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_ventas_registrar_detalle` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_ventas_registrar_detalle`(
IN _idproducto		INT,
IN _cantidad		TINYINT,
IN _precioproducto 	DECIMAL(7,2)
)
BEGIN
	SET @ultima_venta_id = (SELECT MAX(idventa) AS 'last_id' FROM ventas);
	INSERT INTO detalle_venta(idventa, idproducto, cantidad, precioproducto) VALUES
		(@ultima_venta_id, _idproducto, _cantidad, _precioproducto);
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
