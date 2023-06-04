document.addEventListener("DOMContentLoaded", () => {
  const tbProductos = document.querySelector("#tabla-productos tbody");
  const slTipoProducto = document.querySelector("#md-tipoproducto");
  const txNombreProducto = document.querySelector("#md-nombreproducto");
  const txDescripcion = document.querySelector("#md-descripcion");
  const txPrecio = document.querySelector("#md-precio");
  const txStock = document.querySelector("#md-stock");
  const btGuardar = document.querySelector("#md-guardar-datos");
  
  const mdRegistroProducto = new bootstrap.Modal(document.querySelector("#modal-registro-producto"));
  const mdUpdateProducto = new bootstrap.Modal(document.querySelector("#modal-update-producto"));

  let idProducto = 0;

  function renderTable() {
    const pm = new URLSearchParams();
    pm.append("operacion", "listar");
    fetch("./controllers/Producto.controller.php", {
      method: "POST",
      body: pm
    })
      .then(res => res.json())
      .then(data => {

        new DataTable("#tabla-productos").destroy();

        tbProductos.innerHTML = "";
        data.forEach(element => {
          const descripcion = element.descripcion != null ? element.descripcion : "---"
          const stock = element.stock != null ? element.stock : "---"
          const row = `
            <tr>
              <td>${element.idproducto}</td>
              <td>${element.tipoproducto}</td>
              <td>${element.nombreproducto}</td>
              <td>${descripcion}</td>
              <td>${element.precio}</td>
              <td>${stock}</td>
              <td>
                <a class='btn btn-sm btn-primary editar' data-idproducto='${element.idproducto}'>
                  <i class="fa-solid fa-pencil"></i>
                </a>
                <a class='btn btn-sm btn-danger eliminar' data-idproducto='${element.idproducto}'>
                  <i class="fa-solid fa-trash"></i>
                </a>
              </td>
            </tr>
          `;
          tbProductos.innerHTML += row;
        });
    
        new DataTable("#tabla-productos", {
          ordering: false,
          lengthMenu: [5, 10, 25, 50, 100],
          pageLength: 10,
          language: {
            url: "./js/Spanish.json"
          }
        });
      })
  }

  function registerProduct() {
    if (
      !slTipoProducto.value ||
      !txNombreProducto.value ||
      !txPrecio.value
    ) {
      Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      }).fire({
        icon: 'error',
        title: 'Complete los datos por favor'
      })
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "registrar");
      pm.append("tipoproducto", slTipoProducto.value);
      pm.append("nombreproducto", txNombreProducto.value);
      pm.append("descripcion", txDescripcion.value);
      pm.append("precio", txPrecio.value);
      pm.append("stock", txStock.value ? txStock.value : -1);

      fetch("./controllers/Producto.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
    
          Toast.fire({
            icon: data.success ? 'success' : 'error',
            title: data.message
          })

          mdRegistroProducto.toggle();
          document.querySelector("#formulario-registro-productos").reset();
          
          if (data.success) renderTable();
        })
    }
  }

  function getData(idproducto) {
    const pm = new URLSearchParams();
    pm.append("operacion", "getdata");
    pm.append("idproducto", idproducto);
    
    fetch("./controllers/Producto.controller.php", {
      method: "POST",
      body: pm
    })
      .then(res => res.json())
      .then(data => {
        document.querySelector("#up-tipoproducto").value = data.tipoproducto;
        document.querySelector("#up-nombreproducto").value = data.nombreproducto;
        document.querySelector("#up-descripcion").value = data.descripcion;
        document.querySelector("#up-precio").value = data.precio;
        document.querySelector("#up-stock").value = data.stock;
      })
  }

  function updateProduct() {
    if (
      !document.querySelector("#up-tipoproducto").value ||
      !document.querySelector("#up-nombreproducto").value ||
      !document.querySelector("#up-precio").value
    ) {
      Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      }).fire({
        icon: 'error',
        title: 'Complete los datos por favor'
      })
    } else {
      const pm = new URLSearchParams();
      pm.append("operacion", "editar");
      pm.append("idproducto", idProducto);
      pm.append("tipoproducto", document.querySelector("#up-tipoproducto").value);
      pm.append("nombreproducto", document.querySelector("#up-nombreproducto").value);
      pm.append("descripcion", document.querySelector("#up-descripcion").value);
      pm.append("precio", document.querySelector("#up-precio").value);
      pm.append("stock", document.querySelector("#up-stock").value ? document.querySelector("#up-stock").value : -1);

      fetch("./controllers/Producto.controller.php", {
        method: "POST",
        body: pm
      })
        .then(res => res.json())
        .then(data => {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
          });
    
          Toast.fire({
            icon: data.success ? 'success' : 'error',
            title: data.message
          })

          mdUpdateProducto.toggle();
          document.querySelector("#formulario-update-productos").reset();
          
          if (data.success) renderTable();
        })
    }
  }

  function deleteProduct (idproducto) {
    Swal.fire({
      icon: 'question',
      text: '¿Seguro de eliminar este registro?',
      showCancelButton: true,
      confirmButtonText: 'Confirmar',
      cancelButtonText: `Cancelar`,
      focusCancel: true,
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        const pm = new URLSearchParams();
        pm.append("operacion", "eliminar");
        pm.append("idproducto", idproducto);
        
        fetch("./controllers/Producto.controller.php", {
          method: "POST",
          body: pm
        })
          .then(res => res.json())
          .then(data => {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: true
            });
      
            Toast.fire({
              icon: data.success ? 'success' : 'error',
              title: data.message
            })
      
            if (data.success) renderTable();
          })
      }
    });
  }

  btGuardar.addEventListener("click", registerProduct);

  document.querySelector("#up-guardar-datos").addEventListener("click", updateProduct);

  tbProductos.addEventListener("click", (e) => {
    const objetivo = e.target;

    if (objetivo.closest(".eliminar")) {
      const idProducto = objetivo.closest(".eliminar").dataset.idproducto;
      deleteProduct(idProducto);
    }

    if (objetivo.closest(".editar")) {
      idProducto = objetivo.closest(".editar").dataset.idproducto;
      getData(idProducto);
      mdUpdateProducto.toggle();
    }
  });

  renderTable();
}) 