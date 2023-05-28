<style>
  #tabla-ventas tbody i{
    font-size: 15px;
  }
  
</style>

<h3>Ventas</h3>
<hr>

<div class="">
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-nueva-venta">
    Nueva venta
  </button>
</div>

<table id="tabla-ventas" class="table table-sm table-striped mt-2">
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
<!-- Primer modal Nueva venta -->
<div class="modal fade" id="modal-nueva-venta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva venta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off">
          <div class="row text-end">
            <span class="col-form-label fw-semibold">Fecha: <span class="fw-normal"><?php echo date('d/m/Y'); ?></span></span>
          </div>
          <div class="row text-end">
            <span class="col-form-label fw-semibold">Hora: <span class="fw-normal"><?php echo date('h:i:s a'); ?></span></span>
          </div>

          <div class="row mb-5">
            <div class="col-md-3">
              <label for="md-mesas" class="col-form-label fw-semibold">Mesa:</label>
              <select name="md-mesas" id="md-mesas" class="form-select">
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="col-md-5">
              <label for="md-clientes" class="col-form-label fw-semibold">Cliente:</label>
              <select type="text" id="md-clientes" class="form-select">
                <option value="">Seleccione</option>
              </select>
            </div>
          </div>
          <div class="row mb-5">
            <div class="col-md-5">
              <label for="md-productos" class="col-form-label fw-semibold">Producto:</label>
              <select class="form-select" id="md-productos">
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="md-cantidad" class="col-form-label fw-semibold">Cantidad:</label>
              <input type="number" class="form-control" id="md-cantidad">
            </div>
            <div class="col-md-2">
              <label for="md-precio" class="col-form-label fw-semibold">Precio:</label>
              <input type="number" class="form-control" id="md-precio" readonly>
            </div>
            <div class="col-md-2">
              <label for="md-subtotal" class="col-form-label fw-semibold">Importe:</label>
              <input type="number" class="form-control" id="md-subtotal" readonly>
            </div>
            <div class="col-md-1 align-self-end">
              <button type="button" class="btn btn-primary" id="md-agregar-producto"><i class="fa-solid fa-plus"></i></button>
            </div>
          </div>
          <table class="table table-sm table-striped mb-5" id="md-tabla-detalles">
            <thead>
              <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Importe</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </form>
      </div> <!-- Fin body nueva venta -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div> <!-- Fin de primer modal -->

<!-- Segundo modal - Detalles de venta -->
<div class="modal fade" id="modal-detalles-venta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h5 class="modal-title" id="modalTitleId">Datos de la venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row mb-2 justify-content-end">
          <div class="col-md-3">
            <label class="col-form-label col-form-label-sm fw-semibold">Fecha y hora:</label>
            <input id="det-fechahora" type="text" class="form-control form-control-sm" readonly>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-md-6">
            <label class="col-form-label col-form-label-sm fw-semibold">Cliente:</label>
            <input id="det-cliente" type="text" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-6">
            <label class="col-form-label col-form-label-sm fw-semibold">Mesero:</label>
            <input id="det-mesero" type="text" class="form-control form-control-sm" readonly>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-md-6">
            <label class="col-form-label col-form-label-sm fw-semibold">Tipo comprobante:</label>
            <input id="det-tipocomprobante" type="text" class="form-control form-control-sm" readonly>
          </div>
          <div class="col-md-6">
            <label class="col-form-label col-form-label-sm fw-semibold">N° comprobante:</label>
            <input id="det-numcomprobante" type="text" class="form-control form-control-sm" readonly>
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
          <label for="det-subtotal" class="col-form-label col-form-label-sm col-sm-1 fw-semibold">Subtotal:</label>
          <div class="col-sm-2">
            <input id="det-subtotal" type="text" class="form-control form-control-sm text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="det-igv" class="col-form-label col-form-label-sm col-sm-1 fw-semibold">IGV:</label>
          <div class="col-sm-2">
            <input id="det-igv" type="text" class="form-control form-control-sm text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="det-total" class="col-form-label col-form-label-sm col-sm-1 fw-semibold">Total:</label>
          <div class="col-sm-2">
            <input id="det-total" type="text" class="form-control form-control-sm text-end" readonly>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> <!-- Fin de segundo modal -->

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tabla-ventas")
    const tableBody = table.querySelector("tbody")
    const mdNuevaVenta = new bootstrap.Modal(document.querySelector("#modal-nueva-venta"))
    const modal = new bootstrap.Modal(document.querySelector("#modal-detalles-venta"))
    
    mdNuevaVenta.toggle()

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
            let estado = (element.estado === "PA") ? "Pagado" : (element.estado === "PE") ? "Pendiente" : "Cancelado";
            const row = `
              <tr>
                <td>${element.idventa}</td>
                <td>${element.nombremesa}</td>
                <td>${element.cliente}</td>
                <td>${element.fechahoraventa}</td>
                <td>${estado}</td>
                <td>
                  <a class='detallar btn btn-primary btn-sm' data-idventa='${element.idventa}'>
                    <i class="fa-solid fa-list"></i>
                  </a>
                  <a class='cambiar-estado btn btn-success btn-sm' data-idventa='${element.idventa}'>
                    <i class="fa-solid fa-money-check-dollar"></i>
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
    
    function loadTables() {
      const pm = new URLSearchParams()
      pm.append("operacion", "listar")
      pm.append("estado", "D")
      fetch("./controllers/Mesa.controller.php", {
        method: "POST",
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          data.forEach(element => {
            const option = document.createElement("option")
            option.textContent = element.nombremesa
            option.value = element.idmesa
            document.getElementById("md-mesas").appendChild(option)
          });
        }) 
    }

    function loadProducts() {
      const pm = new URLSearchParams()
      pm.append("operacion", "cargarOpciones")
      fetch("./controllers/Producto.controller.php", {
        method: "POST",
        body: pm
      })     
        .then(response => response.json())
        .then(data => {
          data.forEach(element => {
            const option = document.createElement("option")
            option.textContent = element.nombreproducto
            option.value = element.idproducto
            option.setAttribute("data-precio", element.precio)
            option.setAttribute("data-stock", element.stock)
            document.querySelector("#md-productos").appendChild(option)
          });
        })
    }

    function addToDetailsTable() {
      if (
        !document.querySelector("#md-productos").value ||
        !document.querySelector("#md-cantidad").value ||
        !document.querySelector("#md-precio").value ||
        !document.querySelector("#md-subtotal").value
      ) {
        alert("Seleccione un producto y la cantidad")
      } else {
        //Guardamos la lista y las cajas de texto
        const mdProductos = document.querySelector("#md-productos")
        const mdCantidad = document.querySelector("#md-cantidad")
        const mdPrecio = document.querySelector("#md-precio")
        const mdSubtotal = document.querySelector("#md-subtotal")

        //Traemos todas las filas del cuerpo de la tabla y lo convertimos en un array
        let tableBody = document.querySelectorAll("#md-tabla-detalles tbody")
        let rows = Array.from(tableBody[0].children)

        let foundRow = rows.find(row => {
          let productoName = row.cells[1].innerText
          return productoName === mdProductos.options[mdProductos.selectedIndex].text
        })
        
        if (foundRow) {
          // Obtenemos el nombre del producto
          let nameProduct = foundRow.cells[1].textContent
          //Buscamos la opción en el select productos
          let productOption = Array.from(mdProductos.options).find(option => option.text === nameProduct)

          //Convertimos la cantidad actualizada y el stock del producto a entero
          let quantityUpdate = parseInt(foundRow.cells[2].innerText) + parseInt(mdCantidad.value)
          let stock = parseInt(productOption.dataset.stock)

          if (quantityUpdate <= stock) {
            //Actualizamos la fila existente
            foundRow.cells[2].innerText = quantityUpdate
            foundRow.cells[4].innerText = (parseFloat(foundRow.cells[4].innerText) + parseFloat(mdSubtotal.value)).toFixed(2)
          } else {
            alert("Supera el stock")
          }
        } else {
          //Construimos la nueva fila
          let newRow = `
            <tr>
              <td>${rows.length + 1}</td>
              <td>${mdProductos.options[mdProductos.selectedIndex].text}</td>
              <td>${mdCantidad.value}</td>
              <td>${mdPrecio.value}</td>
              <td>${mdSubtotal.value}</td>
              <td><button type="button" class="btn btn-sm btn-danger"><i class="fa-solid fa-minus"></i></button></td>
            </tr>
          `
          //Agregar la fila al cuerpo de la tabla
          document.querySelector("#md-tabla-detalles tbody").innerHTML += newRow
        }

        //Reiniciamos lista y cajas de texto
        mdProductos.selectedIndex = 0
        mdCantidad.value = ""
        mdPrecio.value = ""
        mdSubtotal.value = ""
      }
    }

    // Evento click en las columnas operaciones
    tableBody.addEventListener("click", (e) => {
      if (e.target.classList.contains('detalles') || e.target.parentElement.classList.contains('detallar')) {
        const detallesButton = e.target.closest('.detallar');
        const idventa = detallesButton ? detallesButton.dataset.idventa : e.target.parentElement.dataset.idventa
        loadDetails(idventa)
      }
    })

    //Evento change de la lista productos
    document.querySelector("#md-productos").addEventListener("change", (e) => {
      //Traemos la opción seleccionada
      const option = e.target.options[e.target.selectedIndex]

      //Accedemos a los dataset precio y stock de la opción
      const price = parseFloat(option.dataset.precio).toFixed(2)
      const stock = parseInt(option.dataset.stock)

      //Establecemos el valor del precio en el input con ID md-precio
      document.querySelector("#md-precio").value = price

      //Si existe algún valor en el input con ID md-precio
      if (document.querySelector("#md-cantidad").value > 0) {
        //Multiplicamos el valor del input por el precio de producto
        const subtotal = (parseInt(document.querySelector("#md-cantidad").value) * price).toFixed(2)

        //Establecemos el valor del subtotal(importe) en el input con ID md-subtotal
        document.querySelector("#md-subtotal").value = subtotal
      }

      //Si la cantidad supera el stock
      if (document.querySelector("#md-cantidad").value > stock) {
        //Establecemos los inputs en vacios y mostramos una alerta
        document.querySelector("#md-cantidad").value = ""
        document.querySelector("#md-subtotal").value = ""
        alert("Supera el stock")
      }
    })

    //Evento input de la caja de texto cantidad
    document.querySelector("#md-cantidad").addEventListener("input", (e) => {
      const quantity = parseInt(e.target.value)
      const productSelect = document.querySelector("#md-productos")
      if (!productSelect.value) return;

      const price = parseFloat(document.querySelector("#md-precio").value)
      const stock = parseInt(productSelect.options[productSelect.selectedIndex].dataset.stock)

      if (quantity > 0) {
        const subtotal = (quantity * price).toFixed(2)
        document.querySelector("#md-subtotal").value = subtotal
      } else {
        document.querySelector("#md-subtotal").value = ""
      }

      if (quantity > stock) {
        document.querySelector("#md-cantidad").value = ""
        document.querySelector("#md-subtotal").value = ""
        alert("Supera el stock")
      }
    })

    document.querySelector("#md-agregar-producto").addEventListener("click", addToDetailsTable)

    //Funciones automáticas
    loadSales()
    loadTables()
    loadProducts()

  })
</script>