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

  public function listar() {
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_listar()");
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
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
      $consulta = $this->conexion->prepare("CALL spu_productos_registrar(?,?,?,?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["tipoproducto"],
        $datos["nombreproducto"],
        $datos["descripcion"],
        $datos["precio"],
        $datos["stock"]
      ));
      
      $resultado["message"] = ($resultado["success"]) ? "Registrado correctamente" : "Error al registrar el producto";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function getData($idproducto = 0) {
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_getdata(?)");
      $consulta->execute(array($idproducto));
      return $consulta->fetch(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function editar($datos = []) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_editar(?,?,?,?,?,?)");
      $resultado["success"] = $consulta->execute(array(
        $datos["idproducto"],
        $datos["tipoproducto"],
        $datos["nombreproducto"],
        $datos["descripcion"],
        $datos["precio"],
        $datos["stock"]
      ));
      
      $resultado["message"] = ($resultado["success"]) ? "Editado correctamente" : "Error al editar el producto";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }

  public function eliminar($idproducto = 0) {
    $resultado = [
      "success" => false,
      "message" => ""
    ];
    try {
      $consulta = $this->conexion->prepare("CALL spu_productos_cambiarestado(?,?)");
      $resultado["success"] = $consulta->execute(array($idproducto, "0"));
      
      $resultado["message"] = ($resultado["success"]) ? "Eliminado correctamente" : "Error al eliminar el producto";
      return $resultado;
    } catch(Exception $e) {
      die($e->getMessage());
    }
  }
}

?>