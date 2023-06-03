document.addEventListener("DOMContentLoaded", () => {
  // Seleccionamos el contenedor de las mesas en el DOM
  const tablesContainer = document.querySelector("#contenedor-mesas");

  // Creamos instancias de los modales usando Bootstrap
  const mdNuevaVenta = new bootstrap.Modal(document.querySelector("#modal-nueva-venta"));
  const mdDetallesVenta = new bootstrap.Modal(document.querySelector("#modal-detalles-venta"));
  const mdAgregarProducto = new bootstrap.Modal(document.querySelector("#modal-agregar-producto"));
  const mdProcesarPago = new bootstrap.Modal(document.querySelector("#modal-procesar-pago"));

  // Variable global que contendrá los productos para ir filtrando
  let productos = [];

  // Variables globales
  let idMesaCard = 0;
  let idVentaCard = 0;

  // Objetos del primer modal
  const txMesa = document.querySelector("#md-mesa");
  const slMeseros = document.querySelector("#md-empleados");
  const slTipoProductoNV = document.querySelector("#md-tipoproducto");
  const slProductosNV = document.querySelector("#md-productos");
  const txCantidadNV = document.querySelector("#md-cantidad");
  const txPrecioNV = document.querySelector("#md-precio");
  const txImporteNV = document.querySelector("#md-importe");
  const btAgregarProducto = document.querySelector("#md-agregar-producto");
  const tbDetalleVenta = document.querySelector("#md-tabla-detalles tbody");
  const txSubtotalNV = document.querySelector("#md-subtotal");
  const txIgvNV = document.querySelector("#md-igv");
  const txTotalNV = document.querySelector("#md-total");
  const btRegistrarVenta = document.querySelector("#md-registrar-venta");

  // Objetos del segundo modal
  const txFechaHora = document.querySelector("#det-fechahora");
  const txNombreMesa = document.querySelector("#det-mesa");
  const txMesero = document.querySelector("#det-mesero");
  const tbDetalles = document.querySelector("#tabla-detalles tbody");
  const txSubtotalDV = document.querySelector("#det-subtotal");
  const txIgvDV = document.querySelector("#det-igv");
  const txTotalDV = document.querySelector("#det-total");

  // Objetos del tercer modal
  const slTipoProductoAP = document.querySelector("#ap-tipoproducto");
  const slProductosAP = document.querySelector("#ap-productos");
  const txCantidadAP = document.querySelector("#ap-cantidad");
  const txPrecioAP = document.querySelector("#ap-precio");
  const txImporteAP = document.querySelector("#ap-importe");
  const btAgregarProductoAP = document.querySelector("#ap-agregar-producto");

  // Objetos del cuarto modal
  const txDniCliente = document.querySelector("#pp-dni-cliente");
  const rbBoletaSimple = document.querySelector("#pp-tipocom-boletasimple")
  const rbBoletaElectronica = document.querySelector("#pp-tipocom-boletaelectronica")
  const btBuscarCliente = document.getElementById("pp-buscar-cliente");
  const txApellidosCliente = document.querySelector("#pp-apellidos-cliente");
  const txNombresCliente = document.querySelector("#pp-nombres-cliente");
  const slMetodoPago = document.querySelector("#pp-metodopago");
  const txTotalPagar = document.querySelector("#pp-monto-pago");
  const btConfirmarPago = document.getElementById("pp-confirmar-pago");

  // Función para cargar todas las mesas en el contenedor
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

  // Función que cargará la lista(select) de empleados solo meseros
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
          slMeseros.appendChild(option);
        });
      });
  }

  // Función que cargará la lista(select) de tipo de productos
  // Tanto para nv-tipoproducto(Primer modal) y ap-tipoproducto(Tercer modal)
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
          slTipoProductoNV.innerHTML += option;
          slTipoProductoAP.innerHTML += option;
        })
        //Cargamos toda la data en el array productos
        //para filtrar el select de productos
        productos = data;
      });
  }

  // Función que agregará una fila a la tabla nv-tabla-detalles (Primer modal)
  function addToDetailsTable() {
    //Verificamos que las cajas de texto tengan un valor
    if (
      !slProductosNV.value ||
      !txCantidadNV.value ||
      !txPrecioNV.value ||
      !txImporteNV.value
    ) {
      alert("Seleccione un producto y la cantidad");
    } else {
      //Convertimos las filas en un array para usar algunos métodos
      const rows = Array.from(tbDetalleVenta.children);

      //Buscamos si el producto ya existe en la tabla, hacemos la busqueda con find()
      const foundRow = rows.find((row) => {
        const productoName = row.cells[1].innerText;
        return (
          productoName === slProductosNV.options[slProductosNV.selectedIndex].text
        );
      });

      //En caso haya encontrado una coincidencia
      if (foundRow) {
        //Guardamos el nombre del producto
        const nameProduct = foundRow.cells[1].textContent;
        //Buscamos el producto en las opciones del select productos y lo convertimos en Array
        const productOption = Array.from(slProductosNV.options).find(
          (option) => option.text === nameProduct
        );

        //Convertimos la cantidad actualizada y el stock del producto a entero
        const quantityUpdate = parseInt(foundRow.cells[2].innerText) + parseInt(txCantidadNV.value);
        //Como ya tenemos la opción accedemos al data set que contiene y verificamos si el producto tine un stock o es NULL
        const stock = productOption.dataset.stock === "null" ? null : parseInt(productOption.dataset.stock);

        //Si es NULL
        if (stock === null) {
          // Sumamos sin verificar el stock
          foundRow.cells[2].innerText = quantityUpdate;
          foundRow.cells[4].innerText = (parseFloat(foundRow.cells[4].innerText) +parseFloat(txImporteNV.value)).toFixed(2);
        } else {
          //Si tiene un stock verificamos si la cantidad actualizada supera el stock
          if (quantityUpdate <= stock) {
            //Actualizamos la fila existente
            foundRow.cells[2].innerText = quantityUpdate;
            foundRow.cells[4].innerText = (parseFloat(foundRow.cells[4].innerText) + parseFloat(txImporteNV.value)).toFixed(2);
          } else {
            alert("Supera el stock");
          }
        }
      } else {
        //Si no existe la fila, la construimos
        let newRow = `
          <tr data-idproducto='${slProductosNV.value}'>
            <td>${rows.length + 1}</td>
            <td>${slProductosNV.options[slProductosNV.selectedIndex].text}</td>
            <td>${txCantidadNV.value}</td>
            <td>${txPrecioNV.value}</td>
            <td>${txImporteNV.value}</td>
            <td>
              <a type="button" class="btn btn-sm btn-danger me-1 md-eliminar-fila"><i class="fa-solid fa-trash"></i>
              <a type="button" class="btn btn-sm btn-warning text-light me-1 md-disminuir-producto"><i class="fa-solid fa-minus"></i>
              <a type="button" class="btn btn-sm btn-primary me-1 md-aumentar-producto"><i class="fa-solid fa-plus"></i></a>
            </td>
          </tr>
        `;
        //Agregamos la fila al cuerpo de la tabla
        tbDetalleVenta.innerHTML += newRow;
      }

      //Reiniciamos lista y cajas de texto
      slProductosNV.selectedIndex = 0;
      txCantidadNV.value = 1;
      txPrecioNV.value = "";
      txImporteNV.value = "";

      //Usamos el método que calculará los precios
      calculateAmounts();
    }
  }
  
  // Función que calculará el importe de la tabla detalles (Primer modal)
  function calculateAmounts() {
    //Traemos la tabla y construimos algunas variables
    let filas = Array.from(tbDetalleVenta.children); //Accedemos a todas las filas de la tabla y la convertimos en ARRAY
    let subtotal = 0.0;
    let igv = 0.0;
    let total = 0.0;
    filas.forEach((element) => {
      total += parseFloat(element.cells[4].textContent);
    });

    igv = total * 0.18;
    subtotal = total - igv;

    //Asignamos los valores en sus respectivas cajas
    txSubtotalNV.value = subtotal.toFixed(2);
    txIgvNV.value = igv.toFixed(2);
    txTotalNV.value = total.toFixed(2);
  }

  // Función que registrará una nueva venta(pedido) (Primer modal)
  function registerSale() {
    if (
      !txMesa.value ||
      !slMeseros.value ||
      !tbDetalleVenta.childElementCount
    ) {
      alert("Complete los datos por favor");
    } else {
      if (confirm("¿Desea registrar este pedido?")) {
        const pm = new URLSearchParams();
        pm.append("operacion", "registrar");
        pm.append("idmesa", txMesa.dataset.idmesa);
        pm.append("idempleado", slMeseros.value);

        fetch("./controllers/Venta.controller.php", {
          method: "POST",
          body: pm,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const filas = Array.from(tbDetalleVenta.children);

              filas.forEach((element) => {
                //Y hacemos las insercciones de los detalles
                const pm = new URLSearchParams();
                pm.append("operacion", "registrarDetalle");
                pm.append("idproducto", element.dataset.idproducto);
                pm.append("cantidad", element.cells[2].textContent);
                pm.append("precioproducto", element.cells[3].textContent);

                fetch("./controllers/Venta.controller.php", {
                  method: "POST",
                  body: pm,
                })
              });

              const pm = new URLSearchParams();
              pm.append("operacion", "cambiarEstado");
              pm.append("idmesa", txMesa.dataset.idmesa)
              pm.append("estado", "O")

              fetch("./controllers/Mesa.controller.php", {
                method: "POST",
                body: pm
              })
              .then(() => {
                //Esto no mostrará nada porque deberia pasar por detrás del sistema
                mdNuevaVenta.toggle(); //Cerramos el modal
                document.querySelector("#formulario-nueva-venta").reset(); //Reiniciamos el formulario del modal
                tbDetalleVenta.innerHTML = ""; //Dejamos vacia la tabla de detalles
                renderTables(); //Renderizamos la tabla principal
              })
            } else {
              alert(data.message);
            }
          });
      }
    }
  }

  // Función que cargará los detalles de una venta en el modal detalles-venta (Segundo modal)
  function loadDetails(idmesa) {
    obtenerIdVentaPorMesa(idmesa) //Esta función obtiene el ID de la venta existente en la mesa respectiva
      .then(data => {
        const idventa = data[0];
        const pmSearch = new URLSearchParams();
        pmSearch.append("operacion", "buscar");
        pmSearch.append("idventa", idventa);
    
        const pmDetails = new URLSearchParams();
        pmDetails.append("operacion", "detallar");
        pmDetails.append("idventa", idventa);
        pmDetails.append("idmesa", idmesa);
    
        //Realizamos las diferentes solicitudes al controlador
        const dtSearch = fetch("./controllers/Venta.controller.php", {
          method: "POST",
          body: pmSearch,
        });
    
        const dtDetails = fetch("./controllers/Venta.controller.php", {
          method: "POST",
          body: pmDetails,
        });
    
        //Manejamos las solicitudes al mismo tiempo con Promise
        Promise.all([dtSearch, dtDetails])
          .then((response) => {
            //Convertimos a json cada respuesta
            const resSearch = response[0].json();
            const resDetails = response[1].json();
            return Promise.all([resSearch, resDetails]);
          })
          .then((data) => {
            //Y guardamos en una constante los datos recibidos
            const dataSearch = data[0];
            const dataDetails = data[1];
    
            //Establecemos los valores en sus respectivas cajas de texto
            txFechaHora.value = dataSearch.fechahoraorden;
            txNombreMesa.value = dataSearch.nombremesa;
            txMesero.value = dataSearch.mesero;
    
            //Dejamos vacia el cuerpo de la tabla-detalles
            tbDetalles.innerHTML = "";
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
              tbDetalles.innerHTML += row;
              //Y actualizamos el total
              total += parseFloat(element.importe);
            });
    
            //Calculamos el igv y el subtotal
            igv = total * 0.18;
            subtotal = total - igv;
    
            //Y para finalizar establecemos los valores en sus respectivas cajas de texto
            //el método toFixed() formatea el número con una cantidad de decimales
            txSubtotalDV.value = subtotal.toFixed(2);
            txIgvDV.value = igv.toFixed(2);
            txTotalDV.value = total.toFixed(2);
    
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

  // Función que agregará un detalle a una venta existente que tenga el ESTADO en PENDIENTE (Tercer modal)
  function addDetail() {
    if (
      !slProductosAP.value ||
      !txCantidadAP.value ||
      !txPrecioAP.value ||
      !txImporteAP.value
    ) {
      alert("Seleccione un producto y la cantidad");
    } else {
      if (confirm("¿Desea agregar un nuevo pedido?")) {
        const pm = new URLSearchParams();
        pm.append("operacion", "registrar");
        pm.append("idventa", idVentaCard);
        pm.append("idproducto", slProductosAP.value);
        pm.append("cantidad", txCantidadAP.value);
        pm.append("precioproducto", txPrecioAP.value);

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

  // Función que buscará al cliente en la base de datos o en la APIPERU
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

  // Función para finalizar el proceso de pago
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
      if (confirm("¿Finalizar venta?")) {
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
  }

  // FUNCIONES ESPECIALES (para cosas sencillas)
  // Función para obtener el ID de la venta que existe en una mesa
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

  // Función para actualizar la hora en tiempo real
  function actualizarHora() {
    //Obtenemos la fecha y hora actual
    const fechaActual = new Date();
    //Obtenemos las horas, minutos y segundos de la fecha actual
    let horas = fechaActual.getHours();
    const minutos = fechaActual.getMinutes();
    const segundos = fechaActual.getSeconds();
    const ampm = horas >= 12 ? 'PM' : 'AM';  //Determinar si es AM o PM

    //Convertimos la hora a formato de 12 horas
    horas = horas % 12 || 12;

    //Creamos una cadena de texto con la hora formateada
    const hora = `${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${ampm}`;
    //Actualizamos el contenido de todos los elementos con la clase "reloj-tiempo-real"
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

  // Evento click del contenedor de las mesas, se abrirán los modales a partir de aquí
  tablesContainer.addEventListener("click", (e) => {
    const card = e.target.closest(".card");

    const hasValidCard = card && !e.target.closest('.btn');
    const isStatusD = card?.dataset.status === 'D';
    const isStatusO = card?.dataset.status === 'O';

    if (hasValidCard && isStatusD) {
      //Antes de establecer datos reiniciciamos el formulario y la tabla
      document.querySelector("#formulario-nueva-venta").reset();
      slProductosNV.innerHTML = '';
      slProductosNV.disabled = true
      tbDetalleVenta.innerHTML = "";
      
      txMesa.dataset.idmesa = card.dataset.idmesa;
      txMesa.value = card.querySelector("h4").textContent;

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
            slProductosAP.innerHTML = '';
            slProductosAP.disabled = true
            mdAgregarProducto.toggle();
          })
      }
      
      if (e.target.closest(".procesar-pago") && isStatusO) {
        obtenerIdVentaPorMesa(card.dataset.idmesa)
          .then(data => {
            idVentaCard = data[0];
            idMesaCard = card.dataset.idmesa;
            document.querySelector("#formulario-proceso-pago").reset();
            txDniCliente.disabled = true
            btBuscarCliente.disabled = true
            rbBoletaSimple.checked = true

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
          })
      }
    }
  })

  // Evento change que filtrará el tipo de producto en el select productos (Primer modal)
  slTipoProductoNV.addEventListener("change", (e) => {
    const tipoProductoSeleccionado = e.target.value;

    slProductosNV.innerHTML = '';
    txPrecioNV.value = '';
    txImporteNV.value = '';
    if (tipoProductoSeleccionado === '') {
      slProductosNV.disabled = true;
    } else {
      slProductosNV.disabled = false;
      const filter = productos.filter(
        ({ tipoproducto }) => tipoproducto === tipoProductoSeleccionado
      )

      if (!filter.length) slProductosNV.disabled = true;

      slProductosNV.innerHTML = `<option value=''>Seleccione</option>`;
      filter.forEach(({ idproducto, nombreproducto, precio, stock }) => {
        slProductosNV.innerHTML += 
        `
          <option value='${idproducto}' data-precio=${precio} data-stock=${stock}>
            ${nombreproducto}
          </option>
        `;
      })

    }
  });

  // Evento change de la lista productos (Primer modal)
  slProductosNV.addEventListener("change", (e) => {
    //Traemos la opción seleccionada
    const option = e.target.options[e.target.selectedIndex];

    //Accedemos a los dataset precio y stock de la opción
    const price = parseFloat(option.dataset.precio).toFixed(2);
    const stock = parseInt(option.dataset.stock);

    //Establecemos el valor del precio en el input con ID md-precio
    txPrecioNV.value = price;

    //Si existe algún valor en el input con ID md-precio
    if (txCantidadNV.value > 0) {
      //Multiplicamos el valor del input por el precio de producto
      const subtotal = (
        parseInt(txCantidadNV.value) * price
      ).toFixed(2);

      //Establecemos el valor del subtotal(importe) en el input con ID md-importe
      txImporteNV.value = subtotal;
    }

    //Si la cantidad supera el stock
    if (txCantidadNV.value > stock) {
      //Establecemos los inputs en vacios y mostramos una alerta
      txCantidadNV.value = "";
      txImporteNV.value = "";
      alert("Supera el stock");
    }
  });

  // Evento input de la caja de texto cantidad (Primer modal)
  txCantidadNV.addEventListener("input", (e) => {
    //Traemos, convertimos y guardamos el valor de la caja cantidad
    const quantity = parseInt(e.target.value);
    //Traemos el select de productos
    const productSelect = slProductosNV;
    if (!productSelect.value) return; //Si no hay nada seleccionado, hacemos un return para finalizar este código

    //En caso haya un producto seleccionado hacemos los cálculos
    const price = parseFloat(txPrecioNV.value);
    const stock = parseInt(
      productSelect.options[productSelect.selectedIndex].dataset.stock
    );

    if (quantity > 0) {
      const subtotal = (quantity * price).toFixed(2);
      txImporteNV.value = subtotal;
    } else {
      txImporteNV.value = "";
    }

    if (quantity > stock) {
      txCantidadNV.value = "";
      txImporteNV.value = "";
      alert("Supera el stock");
    }
  });

  // Evento click del boton md-agregar-producto del primer modal (Primer modal)
  btAgregarProducto.addEventListener("click", addToDetailsTable);

  // Evento click en la tabla md-detalles (Primer modal)
  tbDetalleVenta.addEventListener("click", (e) => {
    //Primer botón (ELIMINAR FILA)
    if (e.target.closest(".md-eliminar-fila")) {
      const row = e.target.closest("tr");
      row.remove();
      calculateAmounts();
    }

    //Segundo botón (DISMINUIR)
    if (e.target.closest(".md-disminuir-producto")) {
      const row = e.target.closest("tr");
      row.cells[2].textContent = parseInt(row.cells[2].textContent) - 1;
      row.cells[4].textContent = (parseInt(row.cells[2].textContent) * parseFloat(row.cells[3].textContent)).toFixed(2);
      if (parseInt(row.cells[2].textContent) <= 0) {
        row.remove();
      }
      calculateAmounts();
    }

    //Tercer botón (AUMENTAR)
    if (e.target.closest(".md-aumentar-producto")) {
      const row = e.target.closest("tr");
      const nombreProducto = row.cells[1].textContent;

      let options = slProductosNV.options;
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
      row.cells[4].textContent = (parseInt(row.cells[2].textContent) * parseFloat(row.cells[3].textContent)).toFixed(2);

      calculateAmounts();
    }
  });

  // Evento click del boton registrar venta (Primer modal)
  btRegistrarVenta.addEventListener("click", registerSale);

  // Evento change que filtrará el tipo de producto en el select productos (Tercer modal)
  slTipoProductoAP.addEventListener("change", (e) => {
    const tipoProductoSeleccionado = e.target.value;

    slProductosAP.innerHTML = '';
    txPrecioAP.value = '';
    txImporteAP.value = '';
    if (tipoProductoSeleccionado === '') {
      slProductosAP.disabled = true;
    } else {
      slProductosAP.disabled = false;
      const filter = productos.filter(
        ({ tipoproducto }) => tipoproducto === tipoProductoSeleccionado
      )

      if (!filter.length) slProductosAP.disabled = true;

      slProductosAP.innerHTML = `<option value=''>Seleccione</option>`;
      filter.forEach(({ idproducto, nombreproducto, precio, stock }) => {
        slProductosAP.innerHTML += 
        `
          <option value='${idproducto}' data-precio=${precio} data-stock=${stock}>
            ${nombreproducto}
          </option>
        `;
      })

    }
  });

  // Evento change del select productos del tercer modal (Tercer modal)
  slProductosAP.addEventListener("change", (e) => {
    //Traemos la opción seleccionada
    const option = e.target.options[e.target.selectedIndex];

    //Accedemos a los dataset precio y stock de la opción
    const price = parseFloat(option.dataset.precio).toFixed(2);
    const stock = parseInt(option.dataset.stock);

    //Establecemos el valor del precio
    txPrecioAP.value = price;

    //Si la cantidad supera 0 hacemos el calculo
    if (txCantidadAP.value > 0) {
      //Multiplicamos el valor del input por el precio de producto
      const subtotal = (parseInt(txCantidadAP.value) * price).toFixed(2);

      //Establecemos el valor del subtotal(importe) en el input con ID ap-importe
      txImporteAP.value = subtotal;
    }

    //Si la cantidad supera el stock
    if (txCantidadAP.value > stock) {
      //Establecemos los inputs en vacios y mostramos una alerta
      txCantidadAP.value = "";
      txImporteAP.value = "";
      alert("Supera el stock");
    }
  });

  // Evento input de la caja de texto cantidad (Tercer modal)
  txCantidadAP.addEventListener("input", (e) => {
    const quantity = parseInt(e.target.value);
    const productSelect = slProductosAP;
    if (!productSelect.value) return;

    const price = parseFloat(txPrecioAP.value);
    const stock = parseInt(productSelect.options[productSelect.selectedIndex].dataset.stock);

    if (quantity > 0) {
      const subtotal = (quantity * price).toFixed(2);
      txImporteAP.value = subtotal;
    } else {
      txImporteAP.value = "";
    }

    if (quantity > stock) {
      txCantidadAP.value = "";
      txImporteAP.value = "";
      alert("Supera el stock");
    }
  });

  // Evento click del boton agregar producto del tercer modal (Tercer modal)
  btAgregarProductoAP.addEventListener("click", addDetail);

  // Evento change de radio button boleta simple (Cuarto modal)
  rbBoletaSimple.addEventListener("change", () => {
    txDniCliente.disabled = true
    btBuscarCliente.disabled = true
  })
  
  // Evento change de radio button boleta electrónica (Cuarto modal)
  rbBoletaElectronica.addEventListener("change", () => {
    txDniCliente.disabled = false
    btBuscarCliente.disabled = false
  })

  // Eventos click y keypress que buscarán el cliente en la BD o APIPERU (Cuarto modal)
  btBuscarCliente.addEventListener("click", searchCustomer);
  txDniCliente.addEventListener("keypress", (e) => {
    if (e.keyCode === 13) searchCustomer();
  })

  // Evento click para confirmar pago (Cuarto modal)
  btConfirmarPago.addEventListener("click", payOrder);

  //Funciones automáticas
  renderTables();
  loadEmployees();
  loadProducts();

  // Actualizar la hora cada segundo
  setInterval(actualizarHora, 1000);
});