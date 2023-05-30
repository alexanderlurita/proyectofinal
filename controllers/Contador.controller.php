<?php

require_once '../models/Contador.model.php';

if (isset($_POST['operacion'])) {

  $contador = new Contador();

  if ($_POST['operacion'] == 'ObtenerTotalOrdenes') {
    $dato = $contador->ObtenerTotalOrdenes();
    echo json_encode($dato);
  }

  if ($_POST['operacion'] == 'ObtenerTotalVentas') {
    $dato = $contador->ObtenerTotalVentas();
    echo json_encode($dato);
  }

  if ($_POST['operacion'] == 'ContarProductosConsumidos') {
    $dato = $contador->ContarProductosConsumidos();
    echo json_encode($dato);
  }

  if ($_POST['operacion'] == 'ContarClientes') {
    $dato = $contador->ContarClientes();
    echo json_encode($dato);
  }

}

?>