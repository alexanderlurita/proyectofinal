<style>
  #tabla-ventas tbody i{
    font-size: 15px;
  }
  
</style>

<h3>Ventas</h3>
<hr>

<div class="">
  <button class="btn btn-primary btn-sm">Nueva venta</button>
</div>

<table id="tabla-ventas" class="table table-sm table-striped mt-2">
  <colgroup>
    <col width="10%">
    <col width="15%">
    <col width="30%">
    <col width="20%">
    <col width="10%">
    <col class="" width="15%">
  </colgroup>
  <thead>
    <tr>
      <th>#</th>
      <th>Mesa</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>Estado</th>
      <th>Operaciones</th>
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table>

<!-- Modales -->
<!-- Primer modal - Detalles de venta -->
<div class="modal fade" id="modal-detalles-venta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Datos de la venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off">

          <div class="row justify-content-end">
            <div class="col-md-5">
            <div class="input-group input-group-sm mb-3">
              <span class="input-group-text">Fecha y hora:</span>
              <input id="det-fechahora" type="text" class="form-control" disabled>
            </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">Cliente:</span>
                <input id="det-cliente" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">Mesero:</span>
                <input id="det-mesero" type="text" class="form-control" disabled>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">Tipo comprobante:</span>
                <input id="det-tipocomprobante" type="text" class="form-control" disabled>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group input-group-sm mb-3">
                <span class="input-group-text">N° comprobante:</span>
                <input id="det-numcomprobante" type="text" class="form-control" disabled>
              </div>
            </div>
          </div>          

          <div class="mb-3">
            <table id="tabla-detalles" class="table table-sm table-stripped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>producto</th>
                  <th>cantidad</th>
                  <th>precio</th>
                  <th>importe</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>

          <div class="row justify-content-end mb-1">
            <label for="" class="col-form-label col-form-label-sm col-sm-1">Subtotal:</label>
            <div class="col-sm-2">
              <input id="det-subtotal" type="text" class="form-control form-control-sm text-end" disabled>
            </div>
          </div>
          <div class="row justify-content-end mb-1">
            <label for="" class="col-form-label col-form-label-sm col-sm-1">IGV:</label>
            <div class="col-sm-2">
              <input id="det-igv" type="text" class="form-control form-control-sm text-end" disabled>
            </div>
          </div>
          <div class="row justify-content-end mb-1">
            <label for="" class="col-form-label col-form-label-sm col-sm-1">Total:</label>
            <div class="col-sm-2">
              <input id="det-total" type="text" class="form-control form-control-sm text-end" disabled>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> <!-- Fin de primer modal -->


<script>
  document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tabla-ventas")
    const tableBody = table.querySelector("tbody")
    const modal = new bootstrap.Modal(document.querySelector("#modal-detalles-venta"));

    function loadSales(){
      const pm = new URLSearchParams()
      pm.append("operacion", "listar")
      fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          tableBody.innerHTML = '';
          data.forEach(element => {
            const row = `
              <tr>
                <td>${element.idventa}</td>
                <td>${element.nombremesa}</td>
                <td>${element.cliente}</td>
                <td>${element.fechahoraventa}</td>
                <td>${element.estado}</td>
                <td>
                  <a class='cambiar-estado btn btn-primary btn-sm' data-idventa='${element.idventa}'>
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                  <a class='detalles btn btn-primary btn-sm' data-idventa='${element.idventa}'>
                    <i class="fa-solid fa-list"></i>
                  </a>
                </td>
              </tr>
            `
            tableBody.innerHTML += row;
          })
        })
    }

    function loadDetails(idventa) {
      const pmSearch = new URLSearchParams()
      pmSearch.append("operacion", "buscar")
      pmSearch.append("idventa", idventa)

      const pmDetails = new URLSearchParams()
      pmDetails.append("operacion", "detallar")
      pmDetails.append("idventa", idventa)

      const solicitud1 = fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pmSearch
      })

      const solicitud2 = fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pmDetails
      })

      Promise.all([solicitud1, solicitud2])
        .then(response => {
          const response1 = response[0].json()
          const response2 = response[1].json()
          return Promise.all([response1, response2])
        })
        .then((data) => {
          const dataSearch = data[0]
          const dataDetails = data[1]
        
          document.querySelector("#det-fechahora").value = dataSearch.fechahoraventa
          document.querySelector("#det-cliente").value = dataSearch.cliente
          document.querySelector("#det-mesero").value = dataSearch.mesero
          document.querySelector("#det-tipocomprobante").value = dataSearch.tipocomprobante == 'B' ? 'Boleta' : 'Factura' 
          document.querySelector("#det-numcomprobante").value = dataSearch.numcomprobante

          document.querySelector("#tabla-detalles tbody").innerHTML = ''
          let subtotal = 0.0
          let igv = 0.0
          let total = 0.0
          dataDetails.forEach(element => {
            const row = `
              <tr>
                <td>${element.iddetalleventa}</td>
                <td>${element.nombreproducto}</td>
                <td>${element.cantidad}</td>
                <td>${element.precioproducto}</td>
                <td>${element.importe}</td>
              </tr>
            `
            document.querySelector("#tabla-detalles tbody").innerHTML += row
            total += parseFloat(element.importe)
          });

          igv = total * 0.18
          subtotal = total - igv

          document.querySelector("#det-subtotal").value = subtotal.toFixed(2)
          document.querySelector("#det-igv").value = igv.toFixed(2)
          document.querySelector("#det-total").value = total.toFixed(2)

          modal.toggle()
        })
        .catch(err => {
          console.error(err)
          alert("Problemas al consultar los detalles")
        })
    }

    // Evento click en las columnas operaciones
    tableBody.addEventListener("click", (e) => {
      if (e.target.classList.contains('detalles')) {
        const idventa = e.target.dataset.idventa
        loadDetails(idventa)
      }
    })

    //Funciones automáticas
    loadSales()

  })
</script>