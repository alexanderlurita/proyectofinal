<?php
session_start();

if (!isset($_SESSION['seguridad']) || !$_SESSION['seguridad']['login']) {
  header('Location:./login.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurante</title>
</head>
<body>

  <a id="logout" href="./controllers/Usuario.controller.php?operacion=logout">Cerrar sesión</a>
  
</body>
</html>