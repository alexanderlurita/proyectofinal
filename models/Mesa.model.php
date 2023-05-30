<?php

require_once 'Conexion.php';

class Mesa extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function listar() {
    try {
      $consulta = $this->conexion->prepare("CALL spu_mesas_listar()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function cambiarEstado($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_mesas_cambiarestado(?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idmesa"],
        $datos["estado"]
      ));

      $resultado["message"] = ($resultado["success"]) ? "Cambio exitoso" : "Error al cambiar el estado";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
}

?>