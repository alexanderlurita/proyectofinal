<?php

require_once '../models/Producto.model.php';

if (isset($_POST['operacion'])) {

  $producto = new Producto();

  if ($_POST['operacion'] == 'cargarOpciones') {
    $datos = $producto->cargarOpciones();
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'listar') {
    $datos = $producto->listar();
    echo json_encode($datos);
  }
  
  if ($_POST['operacion'] == 'registrar') {
    $datos = [
      "tipoproducto"    => $_POST["tipoproducto"],
      "nombreproducto"  => $_POST["nombreproducto"],
      "descripcion"     => $_POST["descripcion"],
      "precio"          => $_POST["precio"],
      "stock"           => $_POST["stock"]
    ];
    $resultado = $producto->registrar($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'getdata') {
    $datos = $producto->getData($_POST["idproducto"]);
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'editar') {
    $datos = [
      "idproducto"      => $_POST["idproducto"],
      "tipoproducto"    => $_POST["tipoproducto"],
      "nombreproducto"  => $_POST["nombreproducto"],
      "descripcion"     => $_POST["descripcion"],
      "precio"          => $_POST["precio"],
      "stock"           => $_POST["stock"]
    ];
    $resultado = $producto->editar($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'eliminar') {
    $resultado = $producto->eliminar($_POST["idproducto"]);
    echo json_encode($resultado);
  }

}

?>