<?php

require_once '../models/Venta.model.php';

if (isset($_POST['operacion'])) {

  $venta = new Venta();

  if ($_POST['operacion'] == 'listar') {
    $datos = $venta->listar();
    echo json_encode($datos);
  }

}

?>