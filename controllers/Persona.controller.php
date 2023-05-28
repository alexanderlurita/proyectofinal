<?php

require_once '../models/Persona.model.php';

if (isset($_POST['operacion'])) {

  $persona = new Persona();

  if ($_POST['operacion'] == 'listar') {
    $datos = $persona->listar();
    echo json_encode($datos);
  }

}

?>