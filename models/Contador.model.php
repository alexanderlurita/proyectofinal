<?php

require_once 'Conexion.php';

class Contador extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function ObtenerTotalOrdenes() {
    try {
      $consulta = $this->conexion->prepare("CALL ObtenerTotalOrdenes()");
      $consulta->execute();
      return $consulta->fetch(PDO::FETCH_NUM);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function ObtenerTotalVentas() {
    try {
      $consulta = $this->conexion->prepare("CALL ObtenerTotalVentasPagadas()");
      $consulta->execute();
      return $consulta->fetch(PDO::FETCH_NUM);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function ContarProductosConsumidos() {
    try {
      $consulta = $this->conexion->prepare("CALL ContarProductosConsumidos()");
      $consulta->execute();
      return $consulta->fetch(PDO::FETCH_NUM);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function ContarClientes() {
    try {
      $consulta = $this->conexion->prepare("CALL ContarClientes()");
      $consulta->execute();
      return $consulta->fetch(PDO::FETCH_NUM);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
  
}

?>