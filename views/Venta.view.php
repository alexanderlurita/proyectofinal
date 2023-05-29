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
        <form action="" autocomplete="off" id="formulario-nueva-venta">
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
            <div class="col-md-4">
              <label for="md-clientes" class="col-form-label fw-semibold">Cliente:</label>
              <select type="text" id="md-clientes" class="form-select">
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="md-empleados" class="col-form-label fw-semibold">Mesero:</label>
              <select type="text" id="md-empleados" class="form-select">
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
              <label for="md-importe" class="col-form-label fw-semibold">Importe:</label>
              <input type="number" class="form-control" id="md-importe" readonly>
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

          <div class="row justify-content-end mb-1">
          <label for="md-subtotal" class="col-form-label col-sm-1 fw-semibold">Subtotal:</label>
          <div class="col-sm-2">
            <input id="md-subtotal" type="text" class="form-control text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="md-igv" class="col-form-label col-sm-1 fw-semibold">IGV:</label>
          <div class="col-sm-2">
            <input id="md-igv" type="text" class="form-control text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="md-total" class="col-form-label col-sm-1 fw-semibold">Total:</label>
          <div class="col-sm-2">
            <input id="md-total" type="text" class="form-control text-end" readonly>
          </div>
        </div>
        </form>
      </div> <!-- Fin body nueva venta -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="md-registrar-venta" class="btn btn-primary">Guardar</button>
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

<!-- Tercer modal - Agregar nuevo producto a venta pendiente -->
<div class="modal fade" id="modal-agregar-producto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off" class="container" id="formulario-agregar-producto">
          <div class="row mb-1">
            <label for="ap-productos" class="col-form-label fw-semibold">Producto:</label>
            <select class="form-select" id="ap-productos">
              <option value="">Seleccione</option>
            </select>
          </div>
          <div class="row mb-1">
            <div class="col-md-4">
              <label for="ap-cantidad" class="col-form-label fw-semibold">Cantidad:</label>
              <input type="number" class="form-control" id="ap-cantidad">
            </div>
            <div class="col-md-4">
              <label for="ap-precio" class="col-form-label fw-semibold">Precio:</label>
              <input type="number" class="form-control" id="ap-precio" readonly>
            </div>
            <div class="col-md-4">
              <label for="ap-importe" class="col-form-label fw-semibold">Importe:</label>
              <input type="number" class="form-control" id="ap-importe" readonly>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="ap-agregar-producto" type="button" class="btn btn-primary">Agregar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#tabla-ventas")
    const tableBody = table.querySelector("tbody")
    const mdNuevaVenta = new bootstrap.Modal(document.querySelector("#modal-nueva-venta"))
    const mdDetallesVenta = new bootstrap.Modal(document.querySelector("#modal-detalles-venta"))
    const mdAgregarProducto = new bootstrap.Modal(document.querySelector("#modal-agregar-producto"))

    let idventa = 0

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
                  <a class='agregar-producto btn btn-primary btn-sm' data-idventa='${element.idventa}'>
                    <i class="fa-solid fa-plus"></i>
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
          document.querySelector("#det-tipocomprobante").value = dataSearch.tipocomprobante === 'B' ? 'Boleta' : (dataSearch.tipocomprobante === 'F' ? 'Factura' : '')
          document.querySelector("#det-numcomprobante").value = dataSearch.numcomprobante

          document.querySelector("#tabla-detalles tbody").innerHTML = ''
          let subtotal = 0.0
          let igv = 0.0
          let total = 0.0
          let numRow = 1
          dataDetails.forEach(element => {
            const row = `
              <tr>
                <td>${numRow}</td>
                <td>${element.nombreproducto}</td>
                <td>${element.cantidad}</td>
                <td>${element.precioproducto}</td>
                <td>${element.importe}</td>
              </tr>
            `
            numRow++
            document.querySelector("#tabla-detalles tbody").innerHTML += row
            total += parseFloat(element.importe)
          });

          igv = total * 0.18
          subtotal = total - igv

          document.querySelector("#det-subtotal").value = subtotal.toFixed(2)
          document.querySelector("#det-igv").value = igv.toFixed(2)
          document.querySelector("#det-total").value = total.toFixed(2)

          mdDetallesVenta.toggle()
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

    function loadCustomers() {
      const pm = new URLSearchParams()
      pm.append("operacion", "listar")
      fetch("./controllers/Persona.controller.php", {
        method: "POST",
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          data.forEach(element => {
            const option = document.createElement("option")
            option.textContent = element.apellidos + ' ' + element.nombres
            option.value = element.idpersona
            document.getElementById("md-clientes").appendChild(option)
          });
        }) 
    }

    function loadEmployees() {
      const pm = new URLSearchParams()
      pm.append("operacion", "listar")
      fetch("./controllers/Empleado.controller.php", {
        method: "POST",
        body: pm
      })
        .then(response => response.json())
        .then(data => {
          data.forEach(element => {
            const option = document.createElement("option")
            option.textContent = element.apellidos + ' ' + element.nombres
            option.value = element.idempleado
            document.getElementById("md-empleados").appendChild(option)
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
            const optionMd = document.createElement("option")
            optionMd.textContent = element.nombreproducto
            optionMd.value = element.idproducto
            optionMd.setAttribute("data-precio", element.precio)
            optionMd.setAttribute("data-stock", element.stock)
            document.querySelector("#md-productos").appendChild(optionMd)

            const optionAp = document.createElement("option")
            optionAp.textContent = element.nombreproducto
            optionAp.value = element.idproducto
            optionAp.setAttribute("data-precio", element.precio)
            optionAp.setAttribute("data-stock", element.stock)
            document.querySelector("#ap-productos").appendChild(optionAp)
          });
        })
    }

    function addToDetailsTable() {
      if (
        !document.querySelector("#md-productos").value ||
        !document.querySelector("#md-cantidad").value ||
        !document.querySelector("#md-precio").value ||
        !document.querySelector("#md-importe").value
      ) {
        alert("Seleccione un producto y la cantidad")
      } else {
        //Guardamos la lista y las cajas de texto
        const mdProductos = document.querySelector("#md-productos")
        const mdCantidad = document.querySelector("#md-cantidad")
        const mdPrecio = document.querySelector("#md-precio")
        const mdSubtotal = document.querySelector("#md-importe")

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
          let stock = productOption.dataset.stock === "null" ? null : parseInt(productOption.dataset.stock)
          
          if (stock === null) {
            // Suma sin verificar el stock
            foundRow.cells[2].innerText = quantityUpdate;
            foundRow.cells[4].innerText = (parseFloat(foundRow.cells[4].innerText) + parseFloat(mdSubtotal.value)).toFixed(2);
          } else {
            if (quantityUpdate <= stock) {
              //Actualizamos la fila existente
              foundRow.cells[2].innerText = quantityUpdate
              foundRow.cells[4].innerText = (parseFloat(foundRow.cells[4].innerText) + parseFloat(mdSubtotal.value)).toFixed(2)
            } else {
              alert("Supera el stock")
            }
          }
        } else {
          //Construimos la nueva fila
          let newRow = `
            <tr data-idproducto='${mdProductos.value}'>
              <td>${rows.length + 1}</td>
              <td>${mdProductos.options[mdProductos.selectedIndex].text}</td>
              <td>${mdCantidad.value}</td>
              <td>${mdPrecio.value}</td>
              <td>${mdSubtotal.value}</td>
              <td><button type="button" class="btn btn-sm btn-danger md-eliminar-producto"><i class="fa-solid fa-minus"></i></button></td>
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

        calculateAmounts()
      }
    }

    function calculateAmounts() {
      let tableBody = document.querySelectorAll("#md-tabla-detalles tbody")
      let rowsBody = Array.from(tableBody[0].children)
      let subtotal = 0.0
      let igv = 0.0
      let total = 0.0
      rowsBody.forEach(element => {
        total += parseFloat(element.cells[4].textContent)
      })

      igv = total * 0.18
      subtotal = total - igv

      //Asignando los valores
      document.querySelector("#md-subtotal").value = subtotal.toFixed(2)
      document.querySelector("#md-igv").value = igv.toFixed(2)
      document.querySelector("#md-total").value = total.toFixed(2)
    }

    function registerSale() {
      if (
        !document.querySelector("#md-mesas").value ||
        !document.querySelector("#md-clientes").value ||
        !document.querySelector("#md-empleados").value ||
        !document.querySelector("#md-tabla-detalles tbody").childElementCount
      ) {
        alert("Complete los datos por favor")
      } else {
        if (confirm("¿Desea registrar este pedido?")) {
          const pmVenta = new URLSearchParams()
          pmVenta.append("operacion", "registrar")
          pmVenta.append("idmesa", parseInt(document.querySelector("#md-mesas").value))
          pmVenta.append("idcliente", parseInt(document.querySelector("#md-clientes").value))
          pmVenta.append("idempleado", parseInt(document.querySelector("#md-empleados").value))

          console.log(parseInt(document.querySelector("#md-clientes").value));

          fetch("./controllers/Venta.controller.php", {
            method: 'POST',
            body: pmVenta
          })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                let tableBody = document.querySelectorAll("#md-tabla-detalles tbody")
                let rowsBody = Array.from(tableBody[0].children)

                rowsBody.forEach(element => {
                  const pmDetalle = new URLSearchParams()
                  pmDetalle.append("operacion", "registrarDetalle")
                  pmDetalle.append("idproducto", element.dataset.idproducto)
                  pmDetalle.append("cantidad", element.cells[2].textContent)
                  pmDetalle.append("precioproducto", element.cells[3].textContent)

                  fetch("./controllers/Venta.controller.php", {
                    method: 'POST',
                    body: pmDetalle
                  })
                    .then(response => response.json())
                    .then(data => {
                      console.log(data.success)
                    })
                })
                mdNuevaVenta.toggle()
                document.querySelector("#formulario-nueva-venta").reset()
                document.querySelector("#md-tabla-detalles tbody").innerHTML = ''
                loadSales()
              } else {
                alert(data.message)
              }
            })
        }
      }
    }

    function addDetail(idventa) {
      if (
        !document.querySelector("#ap-productos").value ||
        !document.querySelector("#ap-cantidad").value ||
        !document.querySelector("#ap-precio").value ||
        !document.querySelector("#ap-importe").value
      ) {
        alert("Seleccione un producto y la cantidad")
      } else {
        if (confirm("¿Desea agregar un nuevo pedido?")) {
          const pm = new URLSearchParams()
          pm.append("operacion", "registrar")
          pm.append("idventa", idventa)
          pm.append("idproducto", document.querySelector("#ap-productos").value)
          pm.append("cantidad", document.querySelector("#ap-cantidad").value)
          pm.append("precioproducto", document.querySelector("#ap-precio").value)
          
          fetch("./controllers/Detalle_Venta.controller.php", {
            method: 'POST',
            body: pm
          })
            .then(response => response.json())
            .then(data => {
              if (data) {
                document.querySelector("#formulario-agregar-producto").reset()
                mdAgregarProducto.toggle()
              } else {
                alert(data.message)
              }
            })
        }
      }
    }

    // Evento click en las columnas operaciones
    tableBody.addEventListener("click", (e) => {
      if (e.target.classList.contains('detallar') || e.target.parentElement.classList.contains('detallar')) {
        const detallesButton = e.target.closest('.detallar');
        const idventa = detallesButton ? detallesButton.dataset.idventa : e.target.parentElement.dataset.idventa
        loadDetails(idventa)
      } 

      if (e.target.classList.contains('agregar-producto') || e.target.parentElement.classList.contains('agregar-producto')) {
        const trElement = e.target.closest('tr')
        const tds = trElement.querySelectorAll('td')
        const tdAnterior = tds[tds.length - 2]
        const contenidoTdAnterior = tdAnterior.textContent.trim()

        if (contenidoTdAnterior === "Pendiente") {
          const agregarButton = e.target.closest('.agregar-producto');
          idventa = agregarButton ? agregarButton.dataset.idventa : e.target.parentElement.dataset.idventa
          mdAgregarProducto.toggle()
        } else {
          alert("La venta ya está finalizada")
        }
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

        //Establecemos el valor del subtotal(importe) en el input con ID md-importe
        document.querySelector("#md-importe").value = subtotal
      }

      //Si la cantidad supera el stock
      if (document.querySelector("#md-cantidad").value > stock) {
        //Establecemos los inputs en vacios y mostramos una alerta
        document.querySelector("#md-cantidad").value = ""
        document.querySelector("#md-importe").value = ""
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
        document.querySelector("#md-importe").value = subtotal
      } else {
        document.querySelector("#md-importe").value = ""
      }

      if (quantity > stock) {
        document.querySelector("#md-cantidad").value = ""
        document.querySelector("#md-importe").value = ""
        alert("Supera el stock")
      }
    })

    document.querySelector("#md-agregar-producto").addEventListener("click", addToDetailsTable)

    document.querySelector("#ap-productos").addEventListener("change", (e) => {
      const option = e.target.options[e.target.selectedIndex]

      //Accedemos a los dataset precio y stock de la opción
      const price = parseFloat(option.dataset.precio).toFixed(2)
      const stock = parseInt(option.dataset.stock)

      document.querySelector("#ap-precio").value = price

      if (document.querySelector("#ap-cantidad").value > 0) {
        //Multiplicamos el valor del input por el precio de producto
        const subtotal = (parseInt(document.querySelector("#ap-cantidad").value) * price).toFixed(2)

        //Establecemos el valor del subtotal(importe) en el input con ID ap-importe
        document.querySelector("#ap-importe").value = subtotal
      }

      if (document.querySelector("#ap-cantidad").value > stock) {
        //Establecemos los inputs en vacios y mostramos una alerta
        document.querySelector("#ap-cantidad").value = ""
        document.querySelector("#ap-importe").value = ""
        alert("Supera el stock")
      }
    })

    document.querySelector("#ap-cantidad").addEventListener("input", (e) => {
      const quantity = parseInt(e.target.value)
      const productSelect = document.querySelector("#ap-productos")
      if (!productSelect.value) return;

      const price = parseFloat(document.querySelector("#ap-precio").value)
      const stock = parseInt(productSelect.options[productSelect.selectedIndex].dataset.stock)

      if (quantity > 0) {
        const subtotal = (quantity * price).toFixed(2)
        document.querySelector("#ap-importe").value = subtotal
      } else {
        document.querySelector("#ap-importe").value = ""
      }

      if (quantity > stock) {
        document.querySelector("#ap-cantidad").value = ""
        document.querySelector("#ap-importe").value = ""
        alert("Supera el stock")
      }
    })    

    document.querySelector("#ap-agregar-producto").addEventListener("click", () => {
      addDetail(idventa)
    })

    document.querySelector("#md-tabla-detalles tbody").addEventListener("click", (e) => {
      if (
        e.target.classList.contains('md-eliminar-producto') || 
        e.target.parentElement.classList.contains('md-eliminar-producto')
      ) {
        const row = e.target.closest('tr');
        row.remove();
        calculateAmounts()
      }
    })

    document.querySelector("#md-registrar-venta").addEventListener("click", registerSale)

    //Funciones automáticas
    loadSales()
    loadTables()
    loadCustomers()
    loadEmployees()
    loadProducts()

  })
</script>