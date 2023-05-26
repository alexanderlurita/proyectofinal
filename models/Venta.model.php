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

  public function detallar($idventa = 0) {
    try {
      $consulta = $this->conexion->prepare("CALL spu_ventas_detallar(?)");
      $consulta->execute(array($idventa));
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
  
}

?>