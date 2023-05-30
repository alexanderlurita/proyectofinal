<?php
session_start();
date_default_timezone_set('America/Lima');

if (!isset($_SESSION['seguridad']) || !$_SESSION['seguridad']['login']) {
  header('Location:./index.php');
}

$username = $_SESSION['seguridad']['nombres'] . ' ' . $_SESSION['seguridad']['apellidos'];

$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

switch ($vista) {
  case 'venta':
    $contenido =  'views/Venta.view.php';
    break;
  case 'producto':
    $contenido = 'views/Producto.view.php';
    break;
  default:
    $contenido = 'views/Inicio.view.php';
    break;
} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

  <link rel="stylesheet" href="./styles/dashboard.css">
</head>
<body>
  
  <div class="main-container d-flex">
    <div class="sidebar bg-dark" id="side_nav"> 
      <div class="header-box px-3 pt-4 pb-4 d-flex justify-content-between">
        <h1 class="fs-4"><span class="text-white">MIL SABORES</span></h1>
        <button class="btn d-md-none d-block close-btn px-1 py-0 text-white" ><i class="fa-solid fa-bars-staggered"></i></button>
      </div>

      <?php include 'sidebar.php'; ?>
    
    </div> 
    <div class="content">
      <?php include 'navbar.php'; ?>
      <div class="dashboard-content px-4 pt-4" id="dynamic_content">
        <?php include $contenido; ?>
      </div> <!-- Fin Dynamic Content -->
    </div> <!-- Fin content -->
  </div> <!-- Fin main-container -->

  <!-- Bootstrap 5.3 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  
  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/f5edb5ee55.js" crossorigin="anonymous"></script>

  <script src="./js/dashboard.js"></script>

</body>
</html>