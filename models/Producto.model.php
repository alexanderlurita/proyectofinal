<?php

require_once 'Conexion.php';

class Producto extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function cargarOpciones() {
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_cargaropciones()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
}

?>