<?php

session_start();
require_once '../models/Usuario.model.php';

if (isset($_POST['operacion'])) {

  $usuario = new Usuario();

  if ($_POST['operacion'] == 'login') {
    $acceso = [
      "login"     => false,
      "idusuario" => "",
      "apellidos" => "",
      "nombres"   => "",
      "nivelacceso" => ""
    ];

    $datos = $usuario->login($_POST['nombreusuario']);
    $claveingresada = $_POST['claveacceso'];

    if ($datos) {
      if (password_verify($claveingresada, $datos['claveacceso'])) {
        $acceso['login'] = true;
        $acceso['idusuario'] = $datos['idusuario'];
        $acceso['apellidos'] = $datos['apellidos']; 
        $acceso['nombres'] = $datos['nombres']; 
        $acceso['nivelacceso'] = $datos['nivelacceso']; 
      } else {
        $acceso['mensaje'] = "Contraseña incorrecta";
      }
    } else {
      $acceso['mensaje'] = "Nombre de usuario no encontrado";
    }
    $_SESSION['seguridad'] = $acceso;

    echo json_encode($acceso);
  }

}

if (isset($_GET['operacion'])) {
  if ($_GET['operacion'] == 'logout') {
    session_destroy();
    session_unset();
    header('Location:../index.php');
  }
}

?>