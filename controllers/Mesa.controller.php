<?php

require_once '../models/Mesa.model.php';

if (isset($_POST['operacion'])) {

  $mesa = new Mesa();

  if ($_POST['operacion'] == 'listar') {
    $datos = $mesa->listar($_POST['estado']);
    echo json_encode($datos);
  }

}

?>