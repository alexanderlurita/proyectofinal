<?php

require_once '../models/Venta.model.php';

if (isset($_POST['operacion'])) {

  $venta = new Venta();

  if ($_POST['operacion'] == 'listar') {
    $datos = $venta->listar();
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'buscar') {
    $datos = $venta->buscar($_POST['idventa']);
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'registrar') {
    $datos = [
      "idmesa"      => $_POST["idmesa"],
      "idcliente"   => $_POST["idcliente"],
      "idempleado"  => $_POST["idempleado"]
    ];
    $resultado = $venta->registrar($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'registrarDetalle') {
    $datos = [
      "idproducto"      => $_POST["idproducto"],
      "cantidad"        => $_POST["cantidad"],
      "precioproducto"  => $_POST["precioproducto"]
    ];
    $resultado = $venta->registrarDetalle($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'detallar') {
    $datos = $venta->detallar($_POST['idventa']);
    echo json_encode($datos);
  }

}

?>