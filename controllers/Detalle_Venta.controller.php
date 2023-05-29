<?php

require_once '../models/Detalle_Venta.model.php';

if (isset($_POST['operacion'])) {

  $detalleventa = new DetalleVenta();

  if ($_POST['operacion'] == 'registrar') {
    $datos = [
      "idventa"         => $_POST["idventa"],
      "idproducto"      => $_POST["idproducto"],
      "cantidad"        => $_POST["cantidad"],
      "precioproducto"  => $_POST["precioproducto"],
    ];

    $resultado = $detalleventa->registrar($datos);
    echo json_encode($resultado);
  }

}

?>