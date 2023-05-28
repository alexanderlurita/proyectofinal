<?php

require_once '../models/Empleado.model.php';

if (isset($_POST['operacion'])) {

  $empleado = new Empleado();

  if ($_POST['operacion'] == 'listar') {
    $datos = $empleado->listar();
    echo json_encode($datos);
  }

}

?>