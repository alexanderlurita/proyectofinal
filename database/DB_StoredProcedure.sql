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

-- REGISTRAR VENTA
DELIMITER $$
CREATE PROCEDURE spu_ventas_registrar
(
IN _idmesa		TINYINT,
IN _idempleado		INT
)
BEGIN
	INSERT INTO ventas(idmesa, idempleado) VALUES
		(_idmesa, _idempleado);
END $$

-- REGISTRAR DETALLES DE VENTA RECIÉN CREADA
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

-- DETALLAR VENTA Y SUS DETALLES
DELIMITER $$
CREATE PROCEDURE spu_ventas_detallar
(
IN _idventa INT,
IN _idmesa 	TINYINT
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
		WHERE DET.idventa = _idventa AND VEN.idmesa = _idmesa;
END $$

-- OBTENER IDVENTA DE LA MESA ACTUALMENTE OCUPADA
DELIMITER $$
CREATE PROCEDURE spu_ventas_obtenerIdVentaPorMesa(IN _idmesa TINYINT)
BEGIN
	SELECT ventas.`idventa` 
		FROM ventas
		INNER JOIN mesas ON mesas.`idmesa` = ventas.`idmesa`
		WHERE mesas.`idmesa` = _idmesa AND ventas.`estado` = 'PE';
END $$

-- ACTUALIZAR VENTA (AGREGAR PAGO)
DELIMITER $$
CREATE PROCEDURE spu_ventas_realizarpago
(
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
END $$

-- MESAS
-- LISTAR
DELIMITER $$
CREATE PROCEDURE spu_mesas_listar() 
BEGIN
	SELECT *
		FROM mesas;
END $$

-- CAMBIAR ESTADO
DELIMITER $$ 
CREATE PROCEDURE spu_mesas_cambiarestado
(
IN _idmesa	 	TINYINT,
IN _estado 		CHAR(1)
)
BEGIN
	UPDATE mesas SET
		estado = _estado
	WHERE idmesa = _idmesa;
END $$

-- PRODUCTOS
-- LISTAR
DELIMITER $$
CREATE PROCEDURE spu_productos_cargaropciones()
BEGIN
	SELECT idproducto, tipoproducto, nombreproducto, precio, stock
		FROM productos
		ORDER BY nombreproducto;
END $$

-- PERSONAS
-- LISTAR PERSONAS
DELIMITER $$
CREATE PROCEDURE spu_personas_listar()
BEGIN
	SELECT *
		FROM personas
		ORDER BY 2,3;
END $$

-- BUSCAR PERSONA
DELIMITER $$
CREATE PROCEDURE spu_personas_buscar(IN _dni CHAR(8))
BEGIN
	SELECT * 
		FROM personas
		WHERE dni = _dni;
END $$

-- CONTRATOS
-- LISTAR CONTRATOS(EMPLEADOS)
DELIMITER $$
CREATE PROCEDURE spu_empleados_listar()
BEGIN
	SELECT 	contratos.idcontrato, personas.`apellidos`, personas.`nombres`,
		contratos.`cargo`, contratos.`idturno`
		FROM contratos
		INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`
		WHERE contratos.cargo = 'Mesero' AND contratos.estado = '1';
END $$



-- PROCEDIMIENTOS PARA GRÁFICOS
-- GRÁFICO 1
DELIMITER $$
CREATE PROCEDURE ObtenerVentasPorTipo()
BEGIN
	SELECT p.tipoproducto, SUM(dv.cantidad) AS total_cantidad
		FROM ventas v
		INNER JOIN detalle_venta dv ON dv.idventa = v.idventa
		INNER JOIN productos p ON p.idproducto = dv.idproducto
		WHERE DATE(v.fechahoraorden) BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
		GROUP BY p.tipoproducto;
END $$

-- GRÁFICO 2
DELIMITER $$
CREATE PROCEDURE ObtenerVentasPorEmpleado()
BEGIN
    SELECT CONCAT(personas.`apellidos`, ' ', personas.`nombres`) AS empleado, COUNT(*) AS total_ventas
	FROM ventas
	INNER JOIN contratos ON contratos.`idcontrato` = ventas.`idempleado` 
	INNER JOIN personas ON personas.`idpersona` = contratos.`idempleado`
	WHERE ventas.`estado` = 'PA' AND ventas.fechahoraorden >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
	GROUP BY empleado;
END $$




-- CONTADORES PARA EL INICIO
DELIMITER $$
CREATE PROCEDURE ObtenerTotalOrdenes()
BEGIN
    SELECT COUNT(*) AS total_ordenes FROM ventas;
END $$

DELIMITER $$
CREATE PROCEDURE ObtenerTotalVentasPagadas()
BEGIN
    SELECT COUNT(*) AS total_ventas 
	FROM ventas
	WHERE estado = "PA";
END $$

DELIMITER $$
CREATE PROCEDURE ContarProductosConsumidos()
BEGIN
    SELECT SUM(cantidad) AS total_productos
	FROM detalle_venta;
END $$

DELIMITER $$
CREATE PROCEDURE ContarClientes()
BEGIN
	SELECT COUNT(*) AS total_clientes
		FROM personas
		WHERE idpersona NOT IN (SELECT idempleado FROM contratos);
END $$