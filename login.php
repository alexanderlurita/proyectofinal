<?php
session_start();
if(isset($_SESSION['seguridad']) && $_SESSION['seguridad']['login']) {
  header('Location:./index.php');
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
        <!-- Logo -->
        <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box bg-primary"">
          <div class="featured-image">
            <img src="./images/logo.png" class="img-fluid" style="width: 225px;">
           </div>
           <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600">Be Verified</p>
           <small class="text-white text-wrap text-center" style="width: 17rem; font-family: 'Courier New', Courier, monospace;">Join experienced Designers on this platform.</small>
        </div> <!-- Fin logo -->
        <!-- Login -->
        <div class="col-md-6 right-box">
          <div class="row align-items-center">
            <div class="header-text mb-4">
              <h2>Hello, Again</h2>
              <p>We are happy to have to back.</p>
            </div>
            <!-- Form input-groups -->
            <form action="" autocomplete="off">
              <div class="input-group mb-3">
                <input type="text" id="username" class="form-control form-control-lg bg-light fs-6" placeholder="Username" autofocus>
              </div>
              <div class="input-group mb-1">
                <input type="password" id="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
              </div>
              <div class="input-group mb-5 d-flex justify-content-between">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="formCheck">
                  <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                </div>
                <div class="forgot">
                  <small><a href="#">Forgot Password?</a></small>
                </div>
              </div>
            </form>
            <!-- Buttons -->
            <div class="input-group mb-3">
              <button id="login" class="btn btn-lg btn-primary w-100 fs-6" type="button">Login</button>
            </div>
            <div class="input-group mb-3">
              <button class="btn btn-lg btn-light w-100 fs-6" type="button"><img src="./images/google.png" style="width: 20px;" class="me-2"><small>Sign In with Google</small></button>
            </div>
            <div class="row">
              <small>Don't have account? <a href="#">Sign Up</a></small>
            </div>

          </div>
        </div> <!-- Fin login -->
      </div> <!-- Fin row -->
  </div> <!-- Fin container -->

  <script src="./js/login.js"></script>
  
</body>
</html>