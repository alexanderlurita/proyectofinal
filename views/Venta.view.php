<style>
  #tabla-ventas tbody i{
    font-size: 15px;
  }
  
</style>

<h3>Ventas</h3>
<hr>

<div class="">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-nueva-venta">
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