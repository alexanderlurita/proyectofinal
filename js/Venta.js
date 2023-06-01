//Todo el script se ejecutará cuando la vista esté totalmente cargada
document.addEventListener("DOMContentLoaded", () => {
  //Seleccionamos algunas etiquetas y las guardamos en constantes
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
  const mdProcesarPago = new bootstrap.Modal(
    document.querySelector("#modal-procesar-pago")
  );

  let productos = [];

  //Variable global que se usará al agregar un producto SOLO a una venta pendiente
  let idMesaCard = 0;
  let idVentaCard = 0;

  //Objetos del cuarto modal
  const txDniCliente = document.querySelector("#pp-dni-cliente");
  const rbBoletaSimple = document.querySelector("#pp-tipocom-boletasimple")
  const rbBoletaElectronica = document.querySelector("#pp-tipocom-boletaelectronica")
  const btBuscarCliente = document.getElementById("pp-buscar-cliente");
  const txApellidosCliente = document.querySelector("#pp-apellidos-cliente");
  const txNombresCliente = document.querySelector("#pp-nombres-cliente");
  const slMetodoPago = document.querySelector("#pp-metodopago");
  const txTotalPagar = document.querySelector("#pp-monto-pago");
  const btConfirmarPago = document.getElementById("pp-confirmar-pago");

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

  function obtenerIdVentaPorMesa(idMesa) {
    const pm = new URLSearchParams();
    pm.append("operacion", "obtenerIdVentaPorMesa");
    pm.append("idmesa", idMesa);
  
    return fetch("./controllers/Venta.controller.php", {
      method: 'POST',
      body: pm
    })
      .then(response => response.json());
  }

  tablesContainer.addEventListener("click", (e) => {
    const card = e.target.closest(".card");

    const hasValidCard = card && !e.target.closest('.btn');
    const isStatusD = card?.dataset.status === 'D';
    const isStatusO = card?.dataset.status === 'O';

    if (hasValidCard && isStatusD) {
      //Antes de establecer datos reiniciciamos el formulario y la tabla
      document.querySelector("#formulario-nueva-venta").reset();
      document.querySelector("#md-productos").innerHTML = '';
      document.querySelector("#md-productos").disabled = true
      document.querySelector("#md-tabla-detalles tbody").innerHTML = "";
      
      document.querySelector("#md-mesa").dataset.idmesa = card.dataset.idmesa;
      document.querySelector("#md-mesa").value = card.querySelector("h4").textContent;

      mdNuevaVenta.toggle();
    }

    if (isStatusO) {
      if (e.target.closest('.detallar')) {
        loadDetails(card.dataset.idmesa);
      }

      if (e.target.closest(".agregar-producto")) {
        obtenerIdVentaPorMesa(card.dataset.idmesa)
          .then(data => {
            idVentaCard = data[0];
            document.querySelector("#formulario-agregar-producto").reset();
            document.querySelector("#ap-productos").innerHTML = '';
            document.querySelector("#ap-productos").disabled = true
            mdAgregarProducto.toggle();
          })
      }
      
      if (e.target.closest(".procesar-pago") && isStatusO) {
        obtenerIdVentaPorMesa(card.dataset.idmesa)
          .then(data => {
            idVentaCard = data[0];
            idMesaCard = card.dataset.idmesa;
            document.querySelector("#formulario-proceso-pago").reset();
            document.querySelector("#pp-dni-cliente").disabled = true
            document.querySelector("#pp-buscar-cliente").disabled = true
            document.querySelector("#pp-tipocom-boletasimple").checked = true

            const pm = new URLSearchParams();
            pm.append("operacion", "detallar");
            pm.append("idmesa", idMesaCard);
            pm.append("idventa", idVentaCard);
            fetch("./controllers/Venta.controller.php", {
              method: "POST",
              body: pm
            })
              .then(res => res.json())
              .then(data => {
                let totalPagar = 0.0;
                data.forEach(({ importe }) => {
                  totalPagar += parseFloat(importe);
                })
                txTotalPagar.value = parseFloat(totalPagar).toFixed(2);
              })
            mdProcesarPago.toggle();
            alert(idMesaCard)
          })
      }
    }
  })

  function searchCustomer() {
    if (!txDniCliente.value || txDniCliente.value.length < 8) {
      alert("Escriba el DNI del cliente")
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "buscar");
      pm.append("dni", txDniCliente.value);

      fetch("./controllers/Persona.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          if (data) {
            txApellidosCliente.value = data.apellidos;
            txNombresCliente.value = data.nombres;
          } else {
            const pm = new URLSearchParams();
            pm.append("operacion", "buscarDni")
            pm.append("numdoc", txDniCliente.value)

            fetch("./controllers/ApiPeru.controller.php", {
              method: "POST",
              body: pm
            })
              .then(response => {
                if (response.status == 200 && response.ok) {
                  return response.json();
                } else {
                  throw `Problemas al comunicarse con el servidor`
                }
              })
              .then(data => {
                if (data.success) {
                  txApellidosCliente.value = capitalizeWords(data.apellidoPaterno + " " + data.apellidoMaterno);
                  txNombresCliente.value = capitalizeWords(data.nombres);
                } else {
                  alert(data.message);
                  txApellidosCliente.value = "";
                  txNombresCliente.value = "";
                }
              })
              .catch(err => {
                console.error(err)
              });
          }
        })
    }
  }

  function payOrder() {
    if (rbBoletaSimple.checked && !slMetodoPago.value) {
      alert("Seleccione el método de pago");
    } else if (
      !rbBoletaSimple.checked &&
      (!txDniCliente.value ||
        !txApellidosCliente.value ||
        !txNombresCliente.value ||
        !slMetodoPago.value)
    ) {
      alert("Complete los datos por favor");
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "realizarPago");
      pm.append("idventa", idVentaCard);
      pm.append("apellidos", txApellidosCliente.value);
      pm.append("nombres", txNombresCliente.value);
      pm.append("dni", txDniCliente.value);
      pm.append("tipocomprobante", rbBoletaSimple.checked ? "BS" : "BE");
      pm.append("metodopago", slMetodoPago.value);
      pm.append("montopagado", txTotalPagar.value);
      fetch("./controllers/Venta.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const pm = new URLSearchParams();
              pm.append("operacion", "cambiarEstado");
              pm.append("idmesa", idMesaCard)
              pm.append("estado", "D")

              fetch("./controllers/Mesa.controller.php", {
                method: "POST",
                body: pm
              })
                .then(res => res.json())
                .then(data => {
                  if (data.success) {
                    mdProcesarPago.toggle();
                    renderTables();
                  }
                })
          } else {
            alert(data.message)
          }
        })
    }
  }

  btBuscarCliente.addEventListener("click", searchCustomer);
  txDniCliente.addEventListener("keypress", (e) => {
    if (e.keyCode === 13) searchCustomer();
  })

  btConfirmarPago.addEventListener("click", payOrder);

  //Función que cargará los detalles de una venta en el modal detalles-venta
  function loadDetails(idmesa) {
    //Parámetros iniciales para obtener el ID de la venta sobre la mesa actual (OCUPADA)
    const pm = new URLSearchParams();
    pm.append("operacion", "obtenerIdVentaPorMesa");
    pm.append("idmesa", idmesa)

    fetch("./controllers/Venta.controller.php", {
      method: 'POST',
      body: pm
    })
      .then(response => response.json())
      .then(data => {
        const idventa = data[0];
        //Construimos los parámetros de a enviar
        const pmSearch = new URLSearchParams();
        pmSearch.append("operacion", "buscar");
        pmSearch.append("idventa", idventa);
    
        const pmDetails = new URLSearchParams();
        pmDetails.append("operacion", "detallar");
        pmDetails.append("idventa", idventa);
        pmDetails.append("idmesa", idmesa);
    
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
            document.querySelector("#det-fechahora").value = dataSearch.fechahoraorden;
            document.querySelector("#det-mesa").value = dataSearch.nombremesa;
            document.querySelector("#det-mesero").value = dataSearch.mesero;
    
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
      })
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
          option.value = element.idcontrato;
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
        const tiposProductoSet = new Set();
        for (let i = 0; i < data.length; i++) {
            tiposProductoSet.add(data[i].tipoproducto);
        }
        const tiposProductoArray = Array.from(tiposProductoSet).sort();

        tiposProductoArray.forEach(tipo => {
          const option = `<option value='${tipo}'>${tipo}</option>`;
          document.querySelector("#ap-tipoproducto").innerHTML += option;
          document.querySelector("#md-tipoproducto").innerHTML += option;
        })

        productos = data;
      });
  }

  document.querySelector("#md-tipoproducto").addEventListener("change", (e) => {
    const tipoProductoSeleccionado = e.target.value;

    document.querySelector("#md-productos").innerHTML = '';
    document.querySelector("#md-precio").value = '';
    document.querySelector("#md-importe").value = '';
    if (tipoProductoSeleccionado === '') {
      document.querySelector("#md-productos").disabled = true;
    } else {
      document.querySelector("#md-productos").disabled = false;
      const filter = productos.filter(
        ({ tipoproducto }) => tipoproducto === tipoProductoSeleccionado
      )

      if (!filter.length) document.querySelector("#md-productos").disabled = true;

      document.querySelector("#md-productos").innerHTML = `<option value=''>Seleccione</option>`;
      filter.forEach(({ idproducto, nombreproducto, precio, stock }) => {
        document.querySelector("#md-productos").innerHTML += 
        `
          <option value='${idproducto}' data-precio=${precio} data-stock=${stock}>
            ${nombreproducto}
          </option>
        `;
      })

    }
  });

  document.querySelector("#ap-tipoproducto").addEventListener("change", (e) => {
    const tipoProductoSeleccionado = e.target.value;

    document.querySelector("#ap-productos").innerHTML = '';
    document.querySelector("#ap-precio").value = '';
    document.querySelector("#ap-importe").value = '';
    if (tipoProductoSeleccionado === '') {
      document.querySelector("#ap-productos").disabled = true;
    } else {
      document.querySelector("#ap-productos").disabled = false;
      const filter = productos.filter(
        ({ tipoproducto }) => tipoproducto === tipoProductoSeleccionado
      )

      if (!filter.length) document.querySelector("#ap-productos").disabled = true;

      document.querySelector("#ap-productos").innerHTML = `<option value=''>Seleccione</option>`;
      filter.forEach(({ idproducto, nombreproducto, precio, stock }) => {
        document.querySelector("#ap-productos").innerHTML += 
        `
          <option value='${idproducto}' data-precio=${precio} data-stock=${stock}>
            ${nombreproducto}
          </option>
        `;
      })

    }
  });

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
      !document.querySelector("#md-mesa").value ||
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
          parseInt(document.querySelector("#md-mesa").dataset.idmesa)
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
              });

              const pmMesa = new URLSearchParams();
              pmMesa.append("operacion", "cambiarEstado");
              pmMesa.append("idmesa", document.querySelector("#md-mesa").dataset.idmesa)
              pmMesa.append("estado", "O")

              fetch("./controllers/Mesa.controller.php", {
                method: "POST",
                body: pmMesa
              })

              //Esto no mostrará nada porque deberia pasar por detrás del sistema
              mdNuevaVenta.toggle(); //Cerramos el modal
              document.querySelector("#formulario-nueva-venta").reset(); //Reiniciamos el formulario del modal
              document.querySelector("#md-tabla-detalles tbody").innerHTML = ""; //Dejamos vacia la tabla de detalles
              renderTables(); //Renderizamos la tabla principal
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

  //Función que actualizará la hora en tiempo real
  function actualizarHora() {
    const fechaActual = new Date();
    let horas = fechaActual.getHours();
    const minutos = fechaActual.getMinutes();
    const segundos = fechaActual.getSeconds();
    const ampm = horas >= 12 ? 'PM' : 'AM';

    // Convertir a formato de 12 horas
    horas = horas % 12 || 12;

    const hora = `${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${ampm}`;
    document.querySelectorAll(".reloj-tiempo-real").forEach(reloj => {
      reloj.textContent = hora;
    })
  }

  //Funciones para convertir la primera letra de las cadenas en mayusculas
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
  }
  function capitalizeWords(string) {
    const words = string.split(' ');
    const capitalizedWords = words.map(word => capitalizeFirstLetter(word));
    return capitalizedWords.join(' ');
  }

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
  document.querySelector("#md-agregar-producto").addEventListener("click", addToDetailsTable);

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
  document.querySelector("#ap-agregar-producto").addEventListener("click", () => {
    //Le pasamos el idventa global en el cual se guarda el ID de la venta al abrir el tercer modal
    addDetail(idVentaCard);
  });

  //Evento click en la tabla md-detalles, se encuentra en el primer modal (NUEVA VENTA)
  document.querySelector("#md-tabla-detalles tbody").addEventListener("click", (e) => {
      //Primer botón (ELIMINAR FILA)
      if (
        e.target.classList.contains("md-eliminar-fila") ||
        e.target.parentElement.classList.contains("md-eliminar-fila")
      ) {
        const row = e.target.closest("tr");
        row.remove();
        calculateAmounts();
      }

      //Segundo botón (DISMINUIR)
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

      //Tercer botón (AUMENTAR)
      if (
        e.target.classList.contains("md-aumentar-producto") ||
        e.target.parentElement.classList.contains("md-aumentar-producto")
      ) {
        const row = e.target.closest("tr");
        const nombreProducto = row.cells[1].textContent;

        let options = document.querySelector("#md-productos").options;
        let stock = null;

        for (let i = 0; i < options.length; i++) {
          if (options[i].text === nombreProducto) {
            stock = options[i].dataset.stock;
            break;
          }
        }

        if (parseInt(stock) <= parseInt(row.cells[2].textContent)) {
          alert("Limite de stock")
          return;
        }

        row.cells[2].textContent = parseInt(row.cells[2].textContent) + 1;
        row.cells[4].textContent = (
          parseInt(row.cells[2].textContent) *
          parseFloat(row.cells[3].textContent)
        ).toFixed(2);

        calculateAmounts();
      }
    });

  //Evento click del boton registrar venta, se encuentra en el primer modal (NUEVA VENTA)
  document.querySelector("#md-registrar-venta").addEventListener("click", registerSale);

  document.querySelector("#pp-tipocom-boletasimple").addEventListener("change", () => {
    document.querySelector("#pp-dni-cliente").disabled = true
    document.querySelector("#pp-buscar-cliente").disabled = true
  })
  
  document.querySelector("#pp-tipocom-boletaelectronica").addEventListener("change", () => {
    document.querySelector("#pp-dni-cliente").disabled = false
    document.querySelector("#pp-buscar-cliente").disabled = false
  })

  //Funciones automáticas
  renderTables();
  loadEmployees();
  loadProducts();

  // Actualizar la hora cada segundo
  setInterval(actualizarHora, 1000);
});