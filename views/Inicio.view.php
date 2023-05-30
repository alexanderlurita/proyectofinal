<style>
  .card i {
    font-size: 25px;
    padding: 10px;
    border-radius: 10px;
  }

  .c-ordenes{
    color: #6eb1f0;
    background-color: #cbe4ff;
  }

  .c-ventas{
    color: #50d791;
    background-color: #c1f1d7;
  }

  .c-productos{
    color: #f6c95a;
    background-color: #fde8b7;
  }

  .c-clientes{
    color: #bf6268;
    background-color: #f3d8d7;
  }

  @media only screen and (max-width: 1030px) {
    .card i {
      display: none;
    }
  }
</style>

<div class="row mb-3 row-cols-1 row-cols-md-4 g-5">
  <div class="col counter">
    <div class="card shadow-md rounded-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h5>Ordenes realizadas</h5>
            <h3 id="contador-ordenes">0</h3> 
          </div>
          <div class="col-md-4 justify-content-start">
            <i class="fa-solid fa-utensils c-ordenes"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col counter">
    <div class="card shadow-md rounded-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8 justify-content-start">
            <h5>Ventas totales</h5>
            <h3 id="contador-ventas">0</h3>
          </div>
          <div class="col-md-4">
            <i class="fa-solid fa-sack-dollar c-ventas"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col counter">
    <div class="card shadow-md rounded-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8 justify-content-start">
            <h5>Productos vendidos</h5>
            <h3 id="contador-productos">0</h3>
          </div>
          <div class="col-md-4">
            <i class="fa-solid fa-burger c-productos"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col counter">
    <div class="card shadow-md rounded-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8 justify-content-start">
            <h5>Clientes totales</h5>
            <h3 id="contador-clientes">0</h3>
          </div>
          <div class="col-md-4">
            <i class="fa-solid fa-face-laugh-wink c-clientes"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card shadow-md rounded-3">
  <div class="row mb-3 row-cols-1 row-cols-md-2 g-4">
    <div class="col">
      <canvas class="m-4" id="grafico-barras"></canvas>
    </div>
    <div class="col">
      <canvas class="m-4" id="grafico-dona"></canvas>
    </div>
  </div>
</div>

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const contadorOrdenes = document.getElementById("contador-ordenes")
    const contadorVentas = document.getElementById("contador-ventas")
    const contadorProductos = document.getElementById("contador-productos")
    const contadorClientes = document.getElementById("contador-clientes")

    const ctxOne = document.getElementById('grafico-barras');
    const ctxTwo = document.getElementById('grafico-dona');

    const graphicOne = new Chart(ctxOne, {
      type: 'bar',
      data: {
        labels: [],
        datasets: [{
          label: 'Cantidad',
          data: [],
          backgroundColor: [
            'rgba(153, 102, 255)',
            'rgba(255, 159, 64)',
            'rgba(255, 205, 86)',
            'rgba(75, 192, 192)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          },
          title: {
            display: true,
            text: 'N° de productos vendidos por tipo'
          }
        },
      }
    });

    const graphicTwo = new Chart(ctxTwo, {
      type: 'pie',
      data: {
        labels: [],
        datasets: [{
          label: 'N° de ventas',
          data: [],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          },
          title: {
            display: true,
            text: 'N° de ventas por empleado'
          },
          colors: {
            forceOverride: true,
            enabled: true
          }
        },
      }
    });

    function loadGraphicOne() {
      const pm = new URLSearchParams()
      pm.append("operacion", "obtenerVentasTipo")
  
      fetch("./controllers/Venta.controller.php", {
        method: 'POST',
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          const lbTipos = [];
          const dtCantidades = [];
          data.forEach(element => {
            lbTipos.push(element.tipoproducto);
            dtCantidades.push(element.total_cantidad);
          })

          graphicOne.data.labels = lbTipos;
          graphicOne.data.datasets[0].data = dtCantidades;
          graphicOne.update();
        })
    }

    function loadGraphicTwo() {
      const pm = new URLSearchParams()
      pm.append("operacion", "obtenerVentasEmpleado")
  
      fetch("./controllers/Venta.controller.php", {
        method: 'POST',
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          const lbEmpleados = [];
          const dtVentas = [];
          data.forEach(element => {
            lbEmpleados.push(element.empleado);
            dtVentas.push(element.total_ventas);
          })

          graphicTwo.data.labels = lbEmpleados;
          graphicTwo.data.datasets[0].data = dtVentas;
          graphicTwo.update();
        })
    }

    function fetchData(operacion, element) {
      const params = new URLSearchParams();
      params.append("operacion", operacion);

      fetch("./controllers/Contador.controller.php", {
        method: 'POST',
        body: params
      })
        .then(response => response.json())
        .then(data => {
          element.textContent = data[0]
        });
    };

    fetchData("ObtenerTotalOrdenes", contadorOrdenes);
    fetchData("ObtenerTotalVentas", contadorVentas);
    fetchData("ContarProductosConsumidos", contadorProductos);
    fetchData("ContarClientes", contadorClientes);

    //Funciones automáticas
    loadGraphicOne();
    loadGraphicTwo();
    
  })
</script>