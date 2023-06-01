<?php

require_once '../services/ApiPeru.php';

if (isset($_POST['operacion'])) {

  $apiperu = new ApiPeru();

  if ($_POST["operacion"] == 'buscarDni') {
    $tipo = "dni";
    $numdoc = $_POST["numdoc"];

    $resultado = $apiperu->obtenerInformacionDocumento($tipo, $numdoc);

    echo $resultado;
  }
}

?>