<?php
session_start();

if (!isset($_SESSION['seguridad']) || !$_SESSION['seguridad']['login']) {
  header('Location:./index.php');
}

$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

if ($vista === 'venta') {
  $contenido = './views/Venta.view.php';
} elseif ($vista === 'producto'){
  $contenido = './views/Producto.view.php';
} else {
  $contenido = './views/Inicio.view.php';
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

  <div class="container-fluid">
    <div class="row">
      <div class="d-flex flex-column justify-content-between col-auto bg-dark min-vh-100">
        <div class="mt-4">
          <a class="text-white d-none d-sm-inline text-decoration-none d-flex align-items-center ms-4" role="button">
            <span>Restaurante</span>
          </a>

          <hr class="text-white d-none d-sm-block">

          <ul class="nav nav-pills flex-column mt-2 mt-sm-0" id="menu">
            <li class="nav-item my-sm-1 my-2">
              <a href="./dashboard.php" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-house"></i> <span class="ms-2 d-none d-sm-inline">Inicio</span>
              </a>
            </li>
            <li class="nav-item my-sm-1 my-2">
              <a id="ventas" href="./dashboard.php?vista=venta" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-piggy-bank"></i> <span class="ms-2 d-none d-sm-inline">Ventas</span>
              </a>
            </li>
            <li class="nav-item my-sm-1 my-2">
              <a id="productos" href="./dashboard.php?vista=producto" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-burger"></i> <span class="ms-2 d-none d-sm-inline">Productos</span>
              </a>
            </li>
            <li class="nav-item my-sm-1 my-2">
              <a href="#" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-file-lines"></i> <span class="ms-2 d-none d-sm-inline">Reportes</span>
              </a>
            </li>

            <li class="nav-item my-sm-1 my-2 disabled">
              <a href="#sidemenu" data-bs-toggle="collapse" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-chart-pie"></i>
                <span class="ms-2 d-none d-sm-inline">Gráficos</span>
                <i class="fa-solid fa-caret-down"></i>
              </a>
              <ul class="nav collapse ms-1 flex-column" id="sidemenu" data-bs-parent="#menu">
                <li class="nav-item">
                  <a class="nav-link text-white" href="#" aria-current="page">Item 1</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">Item 2</a>
                </li>
              </ul>
            </li>

            <li class="nav-item my-sm-1 my-2">
              <a href="#" class="nav-link text-white text-center text-sm-start">
                <i class="fa-solid fa-file-lines"></i> <span class="ms-2 d-none d-sm-inline">Mesas</span>
              </a>
            </li>
          </ul>
        </div>
        <div>
          <div class="dropdown open">
            <a class="btn border-none outline-none text-white dropdown-toggle" type="button" id="triggerId" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                  <i class="fa-solid fa-user"></i>
                  <?php
                    if (isset($_SESSION['seguridad']) && $_SESSION['seguridad']['login']) {
                      $username = $_SESSION['seguridad']['nombres'] . ' ' . $_SESSION['seguridad']['apellidos'];
                      echo '<span class="ms-1 d-none d-sm-inline">' . $username . '</span>';
                    }
                  ?>
                </a>
            <div class="dropdown-menu" aria-labelledby="triggerId">
              <a class="dropdown-item" href="#">Perfil</a>
              <a class="dropdown-item" href="#">Configuración</a>
              <hr class="dropdown-divider">
              <a class="dropdown-item" href="./controllers/Usuario.controller.php?operacion=logout">Cerrar sesión</a>
            </div>
          </div>
        </div>
      </div>

      <div id="contenedor-vistas" class="col p-3">
        <?php include $contenido; ?>
      </div>
    </div>

  </div>
  
  <!-- Bootstrap 5.3 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

  <!-- FontAwesome -->
  <script src="https://kit.fontawesome.com/f5edb5ee55.js" crossorigin="anonymous"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const contenedorVistas = document.querySelector("#contenedor-vistas")
      const btVentas = document.querySelector("#ventas")
      const btProductos = document.querySelector("#productos")
      
      btVentas.addEventListener("click", () => {
        const pm = new URLSearchParams()
        pm.append("operacion", "cargaVentas")

        fetch("./views/Venta.view.php", {
          method: 'GET'
        })
          .then(response => response.text())
          .then(data => {
            contenedorVistas.innerHTML = data;
          })
      })
    })
  </script>
</body>
</html>