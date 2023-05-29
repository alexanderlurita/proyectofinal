<?php

require_once 'Conexion.php';

class DetalleVenta extends Conexion{
  private $conexion;

  public function __CONSTRUCT() {
    $this->conexion = parent::getConexion();
  }

  public function registrar($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_detalle_venta_registrar(?, ?,?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idventa"],
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
}

?>