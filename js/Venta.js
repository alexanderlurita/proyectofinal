//Todo el script se ejecutará cuando la vista esté totalmente cargada
document.addEventListener("DOMContentLoaded", () => {
  //Seleccionamos algunas etiquetas y las guardamos en constantes
  const table = document.querySelector("#tabla-ventas");
  const tableBody = table.querySelector("tbody");
  const tablesContainer = document.querySelector("#contenedor-mesas");
  //Instanciamos los modales para poder usar sus métodos
  const mdNuevaVenta = new bootstrap.Modal(
    document.querySelector("#modal-nueva-venta")
  );
  const mdDetallesVenta = new bootstrap.Modal(
    document.querySelector("#modal-detalles-venta")
  );
  const mdAgregarProducto = new bootstrap.Modal(
    document.querySelector("#modal-agregar-producto")
  );
  const mdCambiarEstado = new bootstrap.Modal(
    document.querySelector("#modal-cambiar-estado")
  );

  //Variable global que se usará al agregar un producto SOLO a una venta pendiente
  let idventa = 0;

  //Función que renderizará las ventas en la tabla-ventas
  function loadSales() {
    //Manipulamos y extraemos la información para enviarla como parámetros
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    //Hacemos la consulta al controlador
    fetch("./controllers/Venta.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        //Le pasamos una cadena vacia al cuerpo de la tabla para que no liste duplicados
        tableBody.innerHTML = "";
        //Recorremos la data enviaba por el controlador
        data.forEach((element) => {
          //Creamos un operador condicional, donde convertiremos el valor del estado
          let estado =
            element.estado === "PA"
              ? "Pagado"
              : element.estado === "PE"
              ? "Pendiente"
              : "Cancelado";
          //construimos la fila a insertar
          const row = `
            <tr>
              <td>${element.idventa}</td>
              <td>${element.nombremesa}</td>
              <td>${element.cliente}</td>
              <td>${element.fechahoraorden}</td>
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
          `;
          //Agregamos la fila al cuerpo de la tabla
          tableBody.innerHTML += row;
        });
      });
  }

  function renderTables() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Mesa.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        tablesContainer.innerHTML = "";
        data.forEach((table) => {
          const bgStatus = (table.estado === 'D') ? 'bg-success-subtle' : (table.estado === 'O') ? 'bg-danger-subtle' : 'bg-warning-subtle';
          const status = (table.estado === 'D') ? 'Disponible' : (table.estado === 'O') ? 'Ocupado' : 'Reservado';
          
          const card = `
            <div class="col">
              <div class="card ${bgStatus}" data-idmesa='${table.idmesa}' data-status=${table.estado}>
                <div class="card-body">
                  <div class='row d-flex justify-content-between'>
                    <div class='col'>
                      <h4 class="card-title">${table.nombremesa}</h4>
                    </div>
                    <div class='col'>
                      <p class='text-end fw-medium'>${status}</p>
                    </div>
                  </div>
                  <p class="card-text">Capacidad: ${table.capacidad}</p>
                  <div class='d-flex justify-content-end'>
                    <button class='btn btn-secondary ms-1 detallar'>
                      <i class="fa-solid fa-list"></i>
                    </button>
                    <button class='btn btn-primary ms-1 agregar-producto'>
                      <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class='btn btn-success ms-1 procesar-pago'>
                      <i class="fa-solid fa-money-check-dollar"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            `;
          tablesContainer.innerHTML += card;
        });
      });
  }

  tablesContainer.addEventListener("click", (e) => {
    const card = e.target.closest(".card");
    if (card){
      const button = e.target.closest('.btn');
      if (!button) {
        if (card.dataset.status === "D") {
          mdNuevaVenta.toggle();
        } else {
          alert("Mesa no disponible")
        }
      }
    }
  })

  //Función que cargará los detalles de una venta en el modal detalles-venta
  function loadDetails(idventa) {
    //Construimos los parámetros de a enviar
    const pmSearch = new URLSearchParams();
    pmSearch.append("operacion", "buscar");
    pmSearch.append("idventa", idventa);

    const pmDetails = new URLSearchParams();
    pmDetails.append("operacion", "detallar");
    pmDetails.append("idventa", idventa);

    //Realizamos las diferentes solicitudes al controlador
    const solicitud1 = fetch("./controllers/Venta.controller.php", {
      method: "POST",
      body: pmSearch,
    });

    const solicitud2 = fetch("./controllers/Venta.controller.php", {
      method: "POST",
      body: pmDetails,
    });

    //Manejamos las solicitudes al mismo tiempo con Promise
    Promise.all([solicitud1, solicitud2])
      .then((response) => {
        //Convertimos a json cada respuesta
        const response1 = response[0].json();
        const response2 = response[1].json();
        return Promise.all([response1, response2]);
      })
      .then((data) => {
        //Y guardamos en una constante los datos recibimos
        const dataSearch = data[0];
        const dataDetails = data[1];

        //Establecemos los valores en sus respectivas cajas de texto
        document.querySelector("#det-fechahora").value =
          dataSearch.fechahoraventa;
        document.querySelector("#det-cliente").value = dataSearch.cliente;
        document.querySelector("#det-mesero").value = dataSearch.mesero;
        //Operador condicional, verificará si existe un tipo de comprobante para
        //posteriormente convertir el valor del estado
        document.querySelector("#det-tipocomprobante").value =
          dataSearch.tipocomprobante === "B"
            ? "Boleta"
            : dataSearch.tipocomprobante === "F"
            ? "Factura"
            : "";
        document.querySelector("#det-numcomprobante").value =
          dataSearch.numcomprobante;

        //Dejamos vacia el cuerpo de la tabla-detalles
        document.querySelector("#tabla-detalles tbody").innerHTML = "";
        //Lo mismo con las cajas de texto subtotal, igv y total neto
        let subtotal = 0.0;
        let igv = 0.0;
        let total = 0.0;
        let numRow = 1;
        //Recorremos la data de detalles
        dataDetails.forEach((element) => {
          //Construimos la fila
          const row = `
            <tr>
              <td>${numRow}</td>
              <td>${element.nombreproducto}</td>
              <td>${element.cantidad}</td>
              <td>${element.precioproducto}</td>
              <td>${element.importe}</td>
            </tr>
          `;
          numRow++;
          //La agregamos al cuerpo de la tabla
          document.querySelector("#tabla-detalles tbody").innerHTML += row;
          //Y actualizamos el total
          total += parseFloat(element.importe);
        });

        //Calculamos el igv y el subtotal
        igv = total * 0.18;
        subtotal = total - igv;

        //Y para finalizar establecemos los valores en sus respectivas cajas de texto
        //el método toFixed() formatea el número con una cantidad de decimales
        document.querySelector("#det-subtotal").value = subtotal.toFixed(2);
        document.querySelector("#det-igv").value = igv.toFixed(2);
        document.querySelector("#det-total").value = total.toFixed(2);

        //Abrimos el modal ya renderizado
        mdDetallesVenta.toggle();
      })
      .catch((err) => {
        //En caso haya una error en alguna solicitud mostraremos una alerta
        console.error(err);
        alert("Problemas al consultar los detalles");
      });
  }

  //Función que cargará la lista(select) de mesas
  function loadTables() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    //Se realiza la consulta al controlador
    fetch("./controllers/Mesa.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        //Recorremos los datos
        data.forEach((element) => {
          //Creamos una etiqueta option
          const option = document.createElement("option");
          option.textContent = element.nombremesa;
          option.value = element.idmesa;
          //Y la insertamos en el select mesas
          document.getElementById("md-mesas").appendChild(option);
        });
      });
  }

  //Función que cargará la lista(select) de clientes
  function loadCustomers() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Persona.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        data.forEach((element) => {
          const option = document.createElement("option");
          option.textContent = element.apellidos + " " + element.nombres;
          option.value = element.idpersona;
          document.getElementById("md-clientes").appendChild(option);
        });
      });
  }

  //Función que cargará la lista(select) de empleados solo (MESEROS)
  function loadEmployees() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Empleado.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        data.forEach((element) => {
          const option = document.createElement("option");
          option.textContent = element.apellidos + " " + element.nombres;
          option.value = element.idempleado;
          document.getElementById("md-empleados").appendChild(option);
        });
      });
  }

  //Función que cargará la lista(select) de productos
  //Tanto para md-productos(PRIMER MODAL) y ap-productos(TERCER MODAL)
  function loadProducts() {
    const pm = new URLSearchParams();
    pm.append("operacion", "cargarOpciones");
    fetch("./controllers/Producto.controller.php", {
      method: "POST",
      body: pm,
    })
      .then((response) => response.json())
      .then((data) => {
        data.forEach((element) => {
          const optionMd = document.createElement("option");
          optionMd.textContent = element.nombreproducto;
          optionMd.value = element.idproducto;
          optionMd.setAttribute("data-precio", element.precio);
          optionMd.setAttribute("data-stock", element.stock);
          document.querySelector("#md-productos").appendChild(optionMd);

          const optionAp = document.createElement("option");
          optionAp.textContent = element.nombreproducto;
          optionAp.value = element.idproducto;
          optionAp.setAttribute("data-precio", element.precio);
          optionAp.setAttribute("data-stock", element.stock);
          document.querySelector("#ap-productos").appendChild(optionAp);
        });
      });
  }

  //Función que agregará una fila a la tabla md-tabla-detalles (PROCESO DE REGISTRO)
  function addToDetailsTable() {
    //Verificamos que las cajas de texto tengan un valor
    if (
      !document.querySelector("#md-productos").value ||
      !document.querySelector("#md-cantidad").value ||
      !document.querySelector("#md-precio").value ||
      !document.querySelector("#md-importe").value
    ) {
      //En caso de que no mostramos una alerta
      alert("Seleccione un producto y la cantidad");
    } else {
      //Guardamos en una variable la lista y las cajas de texto
      const mdProductos = document.querySelector("#md-productos");
      const mdCantidad = document.querySelector("#md-cantidad");
      const mdPrecio = document.querySelector("#md-precio");
      const mdSubtotal = document.querySelector("#md-importe");

      //Traemos todas las filas del cuerpo de la tabla
      let tableBody = document.querySelectorAll("#md-tabla-detalles tbody");
      //Lo convertimos en un array para usar métodos
      let rows = Array.from(tableBody[0].children);

      //Buscamos si el producto ya existe en la tabla, hacemos la busqueda con find()
      let foundRow = rows.find((row) => {
        let productoName = row.cells[1].innerText;
        return (
          productoName === mdProductos.options[mdProductos.selectedIndex].text
        );
      });

      //En caso haya encontrado una coincidencia
      if (foundRow) {
        //Guardamos el nombre del producto
        let nameProduct = foundRow.cells[1].textContent;
        //Buscamos el productos en las opciones del select productos y lo convertimos en Array
        let productOption = Array.from(mdProductos.options).find(
          (option) => option.text === nameProduct
        );

        //Convertimos la cantidad actualizada y el stock del producto a entero
        let quantityUpdate =
          parseInt(foundRow.cells[2].innerText) + parseInt(mdCantidad.value);
        //Como ya tenemos la opción accedemos al data set que contiene y verificamos si el producto tine un stock o es NULL
        let stock =
          productOption.dataset.stock === "null"
            ? null
            : parseInt(productOption.dataset.stock);

        //Si es NULL
        if (stock === null) {
          // Sumamos sin verificar el stock
          foundRow.cells[2].innerText = quantityUpdate;
          foundRow.cells[4].innerText = (
            parseFloat(foundRow.cells[4].innerText) +
            parseFloat(mdSubtotal.value)
          ).toFixed(2);
        } else {
          //Si tiene un stock verificamos si la cantidad actualizada supera el stock
          if (quantityUpdate <= stock) {
            //Actualizamos la fila existente
            foundRow.cells[2].innerText = quantityUpdate;
            foundRow.cells[4].innerText = (
              parseFloat(foundRow.cells[4].innerText) +
              parseFloat(mdSubtotal.value)
            ).toFixed(2);
          } else {
            alert("Supera el stock");
          }
        }
      } else {
        //Si no existe la fila, la construimos
        let newRow = `
          <tr data-idproducto='${mdProductos.value}'>
            <td>${rows.length + 1}</td>
            <td>${mdProductos.options[mdProductos.selectedIndex].text}</td>
            <td>${mdCantidad.value}</td>
            <td>${mdPrecio.value}</td>
            <td>${mdSubtotal.value}</td>
            <td>
              <a type="button" class="btn btn-sm btn-danger me-1 md-eliminar-fila"><i class="fa-solid fa-trash"></i>
              <a type="button" class="btn btn-sm btn-warning text-light me-1 md-disminuir-producto"><i class="fa-solid fa-minus"></i>
              <a type="button" class="btn btn-sm btn-primary me-1 md-aumentar-producto"><i class="fa-solid fa-plus"></i></a>
            </td>
          </tr>
        `;
        //Agregamos la fila al cuerpo de la tabla
        document.querySelector("#md-tabla-detalles tbody").innerHTML += newRow;
      }

      //Reiniciamos lista y cajas de texto
      mdProductos.selectedIndex = 0;
      mdCantidad.value = 1;
      mdPrecio.value = "";
      mdSubtotal.value = "";

      //Usamos el método que calculará los precios
      calculateAmounts();
    }
  }

  //Función que calculará los precios(cantidades) de la tabla existente en el modal NUEVA VENTA
  function calculateAmounts() {
    //Traemos la tabla y construimos algunas variables
    let tableBody = document.querySelectorAll("#md-tabla-detalles tbody");
    let rowsBody = Array.from(tableBody[0].children); //Accedemos a todas las filas de la tabla y la convertimos en ARRAY
    let subtotal = 0.0;
    let igv = 0.0;
    let total = 0.0;
    rowsBody.forEach((element) => {
      total += parseFloat(element.cells[4].textContent);
    });

    igv = total * 0.18;
    subtotal = total - igv;

    //Asignamos los valores en sus respectivas cajas
    document.querySelector("#md-subtotal").value = subtotal.toFixed(2);
    document.querySelector("#md-igv").value = igv.toFixed(2);
    document.querySelector("#md-total").value = total.toFixed(2);
  }

  //Función que registrará una nueva venta(pedido)
  function registerSale() {
    if (
      !document.querySelector("#md-mesas").value ||
      !document.querySelector("#md-clientes").value ||
      !document.querySelector("#md-empleados").value ||
      !document.querySelector("#md-tabla-detalles tbody").childElementCount
    ) {
      alert("Complete los datos por favor");
    } else {
      if (confirm("¿Desea registrar este pedido?")) {
        //Construimos los parámetros
        const pmVenta = new URLSearchParams();
        pmVenta.append("operacion", "registrar");
        pmVenta.append(
          "idmesa",
          parseInt(document.querySelector("#md-mesas").value)
        );
        pmVenta.append(
          "idcliente",
          parseInt(document.querySelector("#md-clientes").value)
        );
        pmVenta.append(
          "idempleado",
          parseInt(document.querySelector("#md-empleados").value)
        );

        //Hacemos la consulta al controlador
        fetch("./controllers/Venta.controller.php", {
          method: "POST",
          body: pmVenta,
        })
          .then((response) => response.json())
          .then((data) => {
            //Si la venta se registró correctamente
            if (data.success) {
              //Traemos la tabla y sus filas para registrar sus detalles de la venta recién agregada
              let tableBody = document.querySelectorAll(
                "#md-tabla-detalles tbody"
              );
              let rowsBody = Array.from(tableBody[0].children);

              //Recorremos las filas
              rowsBody.forEach((element) => {
                //Y hacemos las insercciones de los detalles
                const pmDetalle = new URLSearchParams();
                pmDetalle.append("operacion", "registrarDetalle");
                pmDetalle.append("idproducto", element.dataset.idproducto);
                pmDetalle.append("cantidad", element.cells[2].textContent);
                pmDetalle.append(
                  "precioproducto",
                  element.cells[3].textContent
                );

                fetch("./controllers/Venta.controller.php", {
                  method: "POST",
                  body: pmDetalle,
                })
                  .then((response) => response.json())
                  .then((data) => {
                    console.log(data.success);
                  });
              });
              //Esto no mostrará nada porque deberia pasar por detrás del sistema
              mdNuevaVenta.toggle(); //Cerramos el modal
              document.querySelector("#formulario-nueva-venta").reset(); //Reiniciamos el formulario del modal
              document.querySelector("#md-tabla-detalles tbody").innerHTML = ""; //Dejamos vacia la tabla de detalles
              loadSales(); //Renderizamos la tabla principal
            } else {
              //Si no mostramos el mensaje
              alert(data.message);
            }
          });
      }
    }
  }

  // Función que agregará un detalle a una venta existente que tenga el ESTADO en PENDIENTE (SIN PAGAR)
  function addDetail(idventa) {
    if (
      !document.querySelector("#ap-productos").value ||
      !document.querySelector("#ap-cantidad").value ||
      !document.querySelector("#ap-precio").value ||
      !document.querySelector("#ap-importe").value
    ) {
      alert("Seleccione un producto y la cantidad");
    } else {
      if (confirm("¿Desea agregar un nuevo pedido?")) {
        const pm = new URLSearchParams();
        pm.append("operacion", "registrar");
        pm.append("idventa", idventa);
        pm.append("idproducto", document.querySelector("#ap-productos").value);
        pm.append("cantidad", document.querySelector("#ap-cantidad").value);
        pm.append("precioproducto", document.querySelector("#ap-precio").value);

        fetch("./controllers/Detalle_Venta.controller.php", {
          method: "POST",
          body: pm,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data) {
              document.querySelector("#formulario-agregar-producto").reset();
              mdAgregarProducto.toggle();
            } else {
              alert(data.message);
            }
          });
      }
    }
  }

  // Evento click en las columnas operaciones de la tabla principal
  tableBody.addEventListener("click", (e) => {
    //botón detallar
    if (
      e.target.classList.contains("detallar") ||
      e.target.parentElement.classList.contains("detallar")
    ) {
      const detallesButton = e.target.closest(".detallar");
      const idventa = detallesButton
        ? detallesButton.dataset.idventa
        : e.target.parentElement.dataset.idventa;
      loadDetails(idventa);
    }

    //botón agregar nuevo producto
    if (
      e.target.classList.contains("agregar-producto") ||
      e.target.parentElement.classList.contains("agregar-producto")
    ) {
      const trElement = e.target.closest("tr");
      const tds = trElement.querySelectorAll("td");
      const tdAnterior = tds[tds.length - 2];
      const contenidoTdAnterior = tdAnterior.textContent.trim();

      if (contenidoTdAnterior === "Pendiente") {
        const agregarButton = e.target.closest(".agregar-producto");
        //Establecemos el id de la venta de la fila para posteriormente usarla
        idventa = agregarButton
          ? agregarButton.dataset.idventa
          : e.target.parentElement.dataset.idventa;
        mdAgregarProducto.toggle();
      } else {
        alert("La venta ya está finalizada");
      }
    }

    if (
      e.target.classList.contains("cambiar-estado") ||
      e.target.parentElement.classList.contains("cambiar-estado")
    ) {
      mdCambiarEstado.toggle();
    }
  });

  //Evento change de la lista productos
  document.querySelector("#md-productos").addEventListener("change", (e) => {
    //Traemos la opción seleccionada
    const option = e.target.options[e.target.selectedIndex];

    //Accedemos a los dataset precio y stock de la opción
    const price = parseFloat(option.dataset.precio).toFixed(2);
    const stock = parseInt(option.dataset.stock);

    //Establecemos el valor del precio en el input con ID md-precio
    document.querySelector("#md-precio").value = price;

    //Si existe algún valor en el input con ID md-precio
    if (document.querySelector("#md-cantidad").value > 0) {
      //Multiplicamos el valor del input por el precio de producto
      const subtotal = (
        parseInt(document.querySelector("#md-cantidad").value) * price
      ).toFixed(2);

      //Establecemos el valor del subtotal(importe) en el input con ID md-importe
      document.querySelector("#md-importe").value = subtotal;
    }

    //Si la cantidad supera el stock
    if (document.querySelector("#md-cantidad").value > stock) {
      //Establecemos los inputs en vacios y mostramos una alerta
      document.querySelector("#md-cantidad").value = "";
      document.querySelector("#md-importe").value = "";
      alert("Supera el stock");
    }
  });

  //Evento input de la caja de texto cantidad
  document.querySelector("#md-cantidad").addEventListener("input", (e) => {
    //Traemos, convertimos y guardamos el valor de la caja cantidad
    const quantity = parseInt(e.target.value);
    //Traemos el select de productos
    const productSelect = document.querySelector("#md-productos");
    if (!productSelect.value) return; //Si no hay nada seleccionado, hacemos un return para finalizar este código

    //En caso haya un producto seleccionado hacemos los cálculos
    const price = parseFloat(document.querySelector("#md-precio").value);
    const stock = parseInt(
      productSelect.options[productSelect.selectedIndex].dataset.stock
    );

    if (quantity > 0) {
      const subtotal = (quantity * price).toFixed(2);
      document.querySelector("#md-importe").value = subtotal;
    } else {
      document.querySelector("#md-importe").value = "";
    }

    if (quantity > stock) {
      document.querySelector("#md-cantidad").value = "";
      document.querySelector("#md-importe").value = "";
      alert("Supera el stock");
    }
  });

  //Evento click del boton md-agregar-producto del primer modal (NUEVA VENTA)
  document
    .querySelector("#md-agregar-producto")
    .addEventListener("click", addToDetailsTable);

  //Evento change del select productos del tercer modal (AGREGAR PRODUCTO A VENTA PENDIENTE)
  document.querySelector("#ap-productos").addEventListener("change", (e) => {
    //Traemos la opción seleccionada
    const option = e.target.options[e.target.selectedIndex];

    //Accedemos a los dataset precio y stock de la opción
    const price = parseFloat(option.dataset.precio).toFixed(2);
    const stock = parseInt(option.dataset.stock);

    //Establecemos el valor del precio
    document.querySelector("#ap-precio").value = price;

    //Si la cantidad supera 0 hacemos el calculo
    if (document.querySelector("#ap-cantidad").value > 0) {
      //Multiplicamos el valor del input por el precio de producto
      const subtotal = (
        parseInt(document.querySelector("#ap-cantidad").value) * price
      ).toFixed(2);

      //Establecemos el valor del subtotal(importe) en el input con ID ap-importe
      document.querySelector("#ap-importe").value = subtotal;
    }

    //Si la cantidad supera el stock
    if (document.querySelector("#ap-cantidad").value > stock) {
      //Establecemos los inputs en vacios y mostramos una alerta
      document.querySelector("#ap-cantidad").value = "";
      document.querySelector("#ap-importe").value = "";
      alert("Supera el stock");
    }
  });

  //Evento input de la caja de texto cantidad del tercer modal
  //Hace lo similar a lo anterior para que calcule no importe en donde se haga el cambio
  document.querySelector("#ap-cantidad").addEventListener("input", (e) => {
    const quantity = parseInt(e.target.value);
    const productSelect = document.querySelector("#ap-productos");
    if (!productSelect.value) return;

    const price = parseFloat(document.querySelector("#ap-precio").value);
    const stock = parseInt(
      productSelect.options[productSelect.selectedIndex].dataset.stock
    );

    if (quantity > 0) {
      const subtotal = (quantity * price).toFixed(2);
      document.querySelector("#ap-importe").value = subtotal;
    } else {
      document.querySelector("#ap-importe").value = "";
    }

    if (quantity > stock) {
      document.querySelector("#ap-cantidad").value = "";
      document.querySelector("#ap-importe").value = "";
      alert("Supera el stock");
    }
  });

  //Evento click del boton agregar producto del tercer modal (AGREGAR PRODUCTO A VENTA PENDIENTE)
  document
    .querySelector("#ap-agregar-producto")
    .addEventListener("click", () => {
      //Le pasamos el idventa global en el cual se guarda el ID de la venta al abrir el tercer modal
      addDetail(idventa);
    });

  //mdNuevaVenta.toggle()
  //Evento click en la tabla md-detalles, se encuentra en el primer modal (NUEVA VENTA)
  document
    .querySelector("#md-tabla-detalles tbody")
    .addEventListener("click", (e) => {
      //Primer botón
      if (
        e.target.classList.contains("md-eliminar-fila") ||
        e.target.parentElement.classList.contains("md-eliminar-fila")
      ) {
        const row = e.target.closest("tr");
        row.remove();
        calculateAmounts();
      }

      //Segundo botón
      if (
        e.target.classList.contains("md-disminuir-producto") ||
        e.target.parentElement.classList.contains("md-disminuir-producto")
      ) {
        const row = e.target.closest("tr");
        row.cells[2].textContent = parseInt(row.cells[2].textContent) - 1;
        row.cells[4].textContent = (
          parseInt(row.cells[2].textContent) *
          parseFloat(row.cells[3].textContent)
        ).toFixed(2);
        if (parseInt(row.cells[2].textContent) <= 0) {
          row.remove();
        }
        calculateAmounts();
      }

      if (
        e.target.classList.contains("md-aumentar-producto") ||
        e.target.parentElement.classList.contains("md-aumentar-producto")
      ) {
        const row = e.target.closest("tr");
        row.cells[2].textContent = parseInt(row.cells[2].textContent) + 1;
        row.cells[4].textContent = (
          parseInt(row.cells[2].textContent) *
          parseFloat(row.cells[3].textContent)
        ).toFixed(2);

        let options = document.querySelector("#md-productos").options;
        console.log(options);

        calculateAmounts();
      }
    });

  //Evento click del boton registrar venta, se encuentra en el primer modal (NUEVA VENTA)
  document
    .querySelector("#md-registrar-venta")
    .addEventListener("click", registerSale);

  //Funciones automáticas
  loadSales();
  renderTables();
  loadTables();
  loadCustomers();
  loadEmployees();
  loadProducts();
});
