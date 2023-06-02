<h3>Reporte de ventas por empleado y mesa</h3>
<hr>

<div class="row mb-3 g-3 align-items-center">
  <div class="col-12 col-md-auto">
    <label for="empleados" class="col-form-label fw-medium">Empleados:</label>
  </div>
  <div class="col-12 col-md-auto">
    <select class="form-select" name="empleados" id="empleados">
      <option value="">Seleccione</option>
    </select>
  </div>
  <div class="col-12 col-md-auto">
    <label for="mesas" class="col-form-label fw-medium">Mesas:</label>
  </div>
  <div class="col-12 col-md-auto">
    <select class="form-select" name="mesas" id="mesas">
      <option value="">Seleccione</option>
    </select>
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
        <th>Fecha orden</th>
        <th>Apertura</th>
        <th>Cierre</th>
        <th>Monto</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>
</div>

<script>
  const slEmpleados = document.getElementById("empleados");
  const slMesas = document.getElementById("mesas");
  const btBuscar = document.getElementById("buscar-ventas");
  const btExportar = document.getElementById("exportar-pdf");
  const tbVentas = document.querySelector("#tabla-ventas tbody");

  function cargarEmpleados() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Empleado.controller.php", {
      method: "POST",
      body: pm
    })
      .then(res => res.json())
      .then(data => {
        data.forEach(({ idcontrato, apellidos, nombres }) => {
          const opcion = document.createElement("option");
          opcion.value = idcontrato;
          opcion.textContent = apellidos + " " + nombres;
          slEmpleados.appendChild(opcion);
        });
      })
  }

  function cargarMesas() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Mesa.controller.php", {
      method: "POST",
      body: pm
    })
      .then(res => res.json())
      .then(data => {
        data.forEach(({ idmesa, nombremesa }) => {
          const opcion = document.createElement("option");
          opcion.value = idmesa;
          opcion.textContent = nombremesa;
          slMesas.appendChild(opcion);
        });
      })
  }

  function cargarVentas() {
    if (!slEmpleados.value || !slMesas.value) {
      alert("Seleccione el empleado y la mesa");
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "listarEmpleadoMesa");
      pm.append("idempleado", slEmpleados.value);
      pm.append("idmesa", slMesas.value)

      fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          tbVentas.innerHTML = '';
          if (data.length) {
            data.forEach(element => {
              const fila = `
                <tr>
                  <td>${element.idventa}</td>
                  <td>${element.nombremesa}</td>
                  <td>${element.fechaorden}</td>
                  <td>${element.apertura}</td>
                  <td>${element.cierre}</td>
                  <td>${element.montopagado}</td>
                </tr>
              `;
              tbVentas.innerHTML += fila;
            });
          } else {
            alert("No se encontraron registros");
          }
        })
    }
  }

  function exportar() {
    if (tbVentas.rows.length === 0) {
      alert("No hay datos para exportar");
    } else {
      const pm = new URLSearchParams();
      pm.append("idempleado", slEmpleados.value);
      pm.append("idmesa", slMesas.value);

      let titulo = 'Empleado: ';
      titulo += slEmpleados.options[slEmpleados.selectedIndex].text + " - Mesa: ";
      titulo += slMesas.options[slMesas.selectedIndex].text
      pm.append("titulo", titulo)

      window.open(`./views/reports/Reporte02.report.php?${pm}`, '_blank');
    }
  }

  btBuscar.addEventListener("click", cargarVentas);
  btExportar.addEventListener("click", exportar);

  cargarEmpleados();
  cargarMesas();

</script>