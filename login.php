<?php
session_start();
if(isset($_SESSION['seguridad']) && $_SESSION['seguridad']['login']) {
  header('Location:./dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  
  <link rel="stylesheet" href="./styles/login.css">
</head>
<body>

  <!-- Container -->
  <div class="container d-flex justify-content-center align-items-center min-vh-100"> 
      <div class="row border rounded-5 p-3 bg-white shadow box-area">
        <!-- Contenedor del LOGO -->
        <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box bg-dark">
          <div class="featured-image">
            <img id="logo" src="./images/mil_sabores_logo_blanco.png" class="img-fluid" style="width: 250px;">
          </div>
          <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace;">Sabores exquisitos que deleitan tus sentidos.</small>
        </div> <!-- Fin logo -->
        <!-- Login -->
        <div class="col-md-6 right-box">
          <div class="row align-items-center">
            <div class="header-text mb-4">
              <h2>¡Bienvenido!</h2>
              <p>Ingresa los datos de tu cuenta para continuar.</p>
            </div>
            <!-- Form input-groups -->
            <form action="" autocomplete="off">
              <div class="input-group mb-3">
                <input type="text" id="username" class="form-control form-control-lg bg-light fs-6" placeholder="Nombre de usuario" autofocus>
              </div>
              <div class="input-group mb-1">
                <input type="password" id="password" class="form-control form-control-lg bg-light fs-6" placeholder="Contraseña">
              </div>
              <div class="input-group mb-5 d-flex justify-content-between">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="formCheck">
                  <label for="formCheck" class="form-check-label text-secondary"><small>Recuérdame</small></label>
                </div>
                <div class="forgot">
                  <small><a href="#">¿Olvista tu contraseña?</a></small>
                </div>
              </div>
            </form>
            <!-- Buttons -->
            <div class="input-group mb-3">
              <button id="login" class="btn bg-primary btn-lg text-light w-100 fs-6" type="button">Iniciar sesión</button>
            </div>
            <div class="input-group mb-3">
              <button class="btn btn-lg btn-light w-100 fs-6" type="button"><img src="./images/google.png" style="width: 20px;" class="me-2"><small>Sign In with Google</small></button>
            </div>
            <div class="row">
              <small>¿No tienes una cuenta? <a href="#">Registrarse</a></small>
            </div>

          </div>
        </div> <!-- Fin login -->
      </div> <!-- Fin row -->
  </div> <!-- Fin container -->

  <script src="./js/login.js"></script>
  
</body>
</html>