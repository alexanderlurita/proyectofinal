<?php

require_once 'Conexion.php';

class Mesa extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function listar($estado = "") {
    try {
      $consulta = $this->conexion->prepare("CALL spu_mesas_listar(?)");
      $consulta->execute(array($estado));
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
}

?>