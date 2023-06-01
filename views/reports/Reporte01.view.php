<h3>Visualizar ventas por intervalo de fechas</h3>
<hr>

<div class="row mb-3 g-3 align-items-center">
  <div class="col-12 col-md-auto">
    <label for="fecha-inicio" class="col-form-label fw-medium">Fecha desde</label>
  </div>
  <div class="col-12 col-md-auto">
    <input type="date" class="form-control fecha-actual" id="fecha-inicio">
  </div>
  <div class="col-12 col-md-auto">
    <label for="fecha-fin" class="col-form-label fw-medium">hasta</label>
  </div>
  <div class="col-12 col-md-auto">
    <input type="date" class="form-control fecha-actual" id="fecha-fin">
  </div>
  <div class="col-12 col-md-auto">
    <button type="button" class="btn btn-primary bg-gradient" id="buscar-ventas">
      <i class="fa-solid fa-magnifying-glass"></i>
    </button>
    <button type="button" class="btn btn-success bg-gradient" id="exportar-pdf">
      <i class="fa-solid fa-file-pdf"></i>
    </button>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-striped table-hover" id="tabla-ventas">
    <thead class="bg-dark bg-gradient text-light">
      <tr>
        <th>#</th>
        <th>Mesa</th>
        <th>Cliente</th>
        <th>Fecha y hora</th>
        <th>Monto</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>
</div>

<script>
  const dtFechaInicio = document.getElementById("fecha-inicio");
  const dtFechaFin = document.getElementById("fecha-fin");
  const btBuscar = document.getElementById("buscar-ventas");
  const btExportar = document.getElementById("exportar-pdf")
  const tbVentas = document.querySelector("#tabla-ventas tbody");

  function formatearFechas() {
    const today = new Date().toISOString().slice(0, 10);
    const inputs = document.getElementsByClassName("fecha-actual");

    for (var i = 0; i < inputs.length; i++) {
      inputs[i].value = today;
    }
  }

  function convertirFecha(dtFecha) {
    const fecha = new Date(dtFecha);
    fecha.setDate(fecha.getDate() + 1);

    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const fechaFormateada = fecha.toLocaleDateString('es-ES', options);

    return fechaFormateada;
  }

  function cargarVentas() {
    if (!dtFechaInicio.value || !dtFechaFin.value) {
      alert("Elija el rango de fechas");
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "listarRangoFechas");
      pm.append("fechainicio", dtFechaInicio.value);
      pm.append("fechafin", dtFechaFin.value)

      fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          tbVentas.innerHTML = '';
          data.forEach(element => {
            const fila = `
              <tr>
                <td>${element.idventa}</td>
                <td>${element.nombremesa}</td>
                <td>${element.cliente != null ? element.cliente : "---" }</td>
                <td>${element.fechahoraorden}</td>
                <td>${element.montopagado}</td>
              </tr>
            `;
            tbVentas.innerHTML += fila;
          });
        })
    }
  }

  function exportar() {
    if (tbVentas.rows.length === 0) {
      alert("No hay datos para exportar");
    } else {
      const pm = new URLSearchParams();
      pm.append("fechainicio", dtFechaInicio.value);
      pm.append("fechafin", dtFechaFin.value);

      let titulo = 'Período: ';
      titulo += convertirFecha(dtFechaInicio.value) + " - ";
      titulo += convertirFecha(dtFechaFin.value)
      pm.append("titulo", titulo)

      window.open(`./views/reports/Reporte01.report.php?${pm}`, '_blank');
    }
  }

  btBuscar.addEventListener("click", cargarVentas);
  btExportar.addEventListener("click", exportar)

  //Funciones automáticas
  formatearFechas();

</script>