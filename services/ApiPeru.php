<?php

class ApiPeru {

  private $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6IjEzMDg5ODZAc2VuYXRpLnBlIn0.00ynBIsYEjD8JIYbaqE5kJYAgUOjkR9MONRw-4_WxcI";

  public function obtenerInformacionDocumento($tipo, $documento) {
    $url = "https://dniruc.apisperu.com/api/v1/{$tipo}/{$documento}?token={$this->token}";

    // Realizar la solicitud a la API
    $resultado = file_get_contents($url);

    return $resultado;
  }
}

?>