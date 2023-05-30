<h3>Registro de órdenes</h3>
<hr>

<div class="">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-nueva-venta">
    Nueva orden
  </button>
</div>

<div class="row mt-1 rows-cols-1 row-cols-md-5 g-4" id="contenedor-mesas">
  
</div>

<!-- Modales -->
<!-- Primer modal Nueva venta -->
<div class="modal fade" id="modal-nueva-venta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva orden</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off" id="formulario-nueva-venta">
          <div class="row mb-5 d-flex justify-content-between">
            <div class="col-md-5 text-start mb-1">
              <div>
                <span class="col-form-label fw-semibold">Fecha: <span class="fw-normal"><?php echo date('d/m/Y'); ?></span></span>
              </div>  
              <div>
                <span class="col-form-label fw-semibold">Hora: <span id="hora" class="fw-normal"></span></span>
              </div>
            </div>
            <div class="col-md-3 mb-1">
              <label for="md-mesa" class="col-form-label fw-semibold">Mesa:</label>
              <input type="text" class="form-control" id="md-mesa" readonly>
            </div>
            <div class="col-md-4 mb-1">
              <label for="md-empleados" class="col-form-label fw-semibold">Mesero:</label>
              <select type="text" id="md-empleados" class="form-select">
                <option value="">Seleccione</option>
              </select>
            </div>
          </div>

          <div class="row mt-2 mb-5">
            <div class="col-md-5">
              <label for="md-productos" class="col-form-label fw-semibold">Producto:</label>
              <select class="form-select" id="md-productos">
                <option value="">Seleccione</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="md-cantidad" class="col-form-label fw-semibold">Cantidad:</label>
              <input type="number" class="form-control" id="md-cantidad" value="1">
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
        <h5 class="modal-title" id="modalTitleId">Detalles de la orden</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="row mb-3 justify-content-end">
          <div class="col-md-4">
            <label class="col-form-label fw-semibold">Fecha y hora:</label>
            <input id="det-fechahora" type="text" class="form-control" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="col-form-label fw-semibold">Mesa:</label>
            <input id="det-mesa" type="text" class="form-control" readonly>
          </div>
          <div class="col-md-6">
            <label class="col-form-label fw-semibold">Mesero:</label>
            <input id="det-mesero" type="text" class="form-control" readonly>
          </div>
        </div>

        <div class="mb-3">
          <table id="tabla-detalles" class="table table-sm table-striped">
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
          <label for="det-subtotal" class="col-form-label col-sm-2 fw-semibold text-end">Subtotal:</label>
          <div class="col-sm-2">
            <input id="det-subtotal" type="text" class="form-control text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="det-igv" class="col-form-label col-sm-2 fw-semibold text-end">IGV:</label>
          <div class="col-sm-2">
            <input id="det-igv" type="text" class="form-control text-end" readonly>
          </div>
        </div>
        <div class="row justify-content-end mb-1">
          <label for="det-total" class="col-form-label col-sm-2 fw-semibold text-end">Total:</label>
          <div class="col-sm-2">
            <input id="det-total" type="text" class="form-control text-end" readonly>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
              <input type="number" class="form-control" id="ap-cantidad" value="1">
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

<!-- Cuarto modal - Cambiar estado (Pagar - Cancelar) -->
<div class="modal fade" id="modal-cambiar-estado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>

<script src="./js/Venta.js"></script>