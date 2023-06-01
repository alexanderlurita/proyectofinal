<?php

require_once 'Conexion.php';

class Persona extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function listar() {
    try {
      $consulta = $this->conexion->prepare("CALL spu_personas_listar()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function buscar($dni = "") {
    try {
      $consulta = $this->conexion->prepare("CALL spu_personas_buscar(?)");
      $consulta->execute(array($dni));
      return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

}

?>