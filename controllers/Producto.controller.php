<?php

require_once '../models/Producto.model.php';

if (isset($_POST['operacion'])) {

  $producto = new Producto();

  if ($_POST['operacion'] == 'cargarOpciones') {
    $datos = $producto->cargarOpciones();
    echo json_encode($datos);
  }

}

?>