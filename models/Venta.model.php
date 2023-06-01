<?php

require_once 'Conexion.php';

class Venta extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function listar() {
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_listar()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function buscar($idventa = 0) {
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_buscar(?)");
      $consulta->execute(array($idventa));
      return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function registrar($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_registrar(?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idmesa"],
        $datos["idempleado"]
      ));

      $resultado["message"] = ($resultado["success"]) ? "Registro exitoso" : "Error al registrar los datos";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function registrarDetalle($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_registrar_detalle(?,?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idproducto"],
        $datos["cantidad"],
        $datos["precioproducto"]
      ));

      $resultado["message"] = ($resultado["success"]) ? "Registro exitoso" : "Error al registrar los datos";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function obtenerIdVentaPorMesa($idmesa = 0) {
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_obtenerIdVentaPorMesa(?)");
      $consulta->execute(array($idmesa));
      return $consulta->fetch(PDO::FETCH_NUM);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function detallar($datos = []) {
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_detallar(?,?)");
      $consulta->execute(array(
        $datos["idventa"],
        $datos["idmesa"]
      ));
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function realizarPago($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_realizarpago(?,?,?,?,?,?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idventa"],
        $datos["apellidos"],
        $datos["nombres"],
        $datos["dni"],
        $datos["tipocomprobante"],
        $datos["metodopago"],
        $datos["montopagado"]
      ));

      $resultado["message"] = ($resultado["success"]) ? "Pago exitoso" : "Error al realizar el pago";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  // Métodos para gráficos con ChartJS
  public function obtenerVentasTipo() {
    try {
      $consulta = $this->conexion->prepare("CALL ObtenerVentasPorTipo()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function obtenerVentasEmpleado() {
    try {
      $consulta = $this->conexion->prepare("CALL ObtenerVentasPorEmpleado()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
  
}

?>