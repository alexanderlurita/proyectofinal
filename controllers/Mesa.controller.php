<?php

require_once '../models/Mesa.model.php';

if (isset($_POST['operacion'])) {

  $mesa = new Mesa();

  if ($_POST['operacion'] == 'listar') {
    $datos = $mesa->listar();
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'cambiarEstado') {
    $datos = [
      "idmesa"    => $_POST["idmesa"],
      "estado"    => $_POST["estado"]
    ];
    $resultado = $mesa->cambiarEstado($datos);
    echo json_encode($resultado);
  }

}

?>