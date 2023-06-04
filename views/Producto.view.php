<h3>Productos</h3>
<hr>

<div class="mb-2">
  <button class="btn btn-primary" type="button"  data-bs-toggle="modal" data-bs-target="#modal-registro-producto">
    <i class="fa-solid fa-drumstick-bite"></i> Nuevo producto
  </button>
</div>

<div class="table-responsive">
  <table class="table table-striped" id="tabla-productos">
    <colgroup>
      <col style="width: 5%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 45%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
      <col style="width: 10%;">
    </colgroup>
    <thead>
      <tr>
        <th>#</th>
        <th>Tipo</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Operaciones</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<!-- Modales -->
<!-- Registro de producto -->
<div class="modal fade" id="modal-registro-producto" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h5 class="modal-title" id="modalTitleId">Registro de productos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off" id="formulario-registro-productos">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="md-tipoproducto" class="form-label fw-semibold">Tipo:</label>
              <select class="form-select" name="md-tipoproducto" id="md-tipoproducto">
                <option value="">Seleccione</option>
                <option value="Bebida">Bebida</option>
                <option value="Entrada">Entrada</option>
                <option value="Plato de fondo">Plato de fondo</option>
                <option value="Postre">Postre</option>
              </select>
            </div>
            <div class="col-md-6 mb-2">
              <label for="md-nombreproducto" class="form-label fw-semibold">Nombre:</label>
              <input class="form-control" type="text" id="md-nombreproducto" maxlength="50">
            </div>
          </div>
          <div class="mb-2">
            <label for="md-descripcion" class="form-label fw-semibold">Descripción:</label>
            <textarea class="form-control" type="text" id="md-descripcion" maxlength="150" rows="3"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="md-precio" class="form-label fw-semibold">Precio:</label>
              <input class="form-control" type="number" id="md-precio" min="0">
            </div>
            <div class="col-md-6 mb-2">
              <label for="md-stock" class="form-label fw-semibold">Stock:</label>
              <input class="form-control" type="number" id="md-stock" min="1" max="100">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="md-guardar-datos">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modificando producto -->
<div class="modal fade" id="modal-update-producto" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h5 class="modal-title">Actualización de producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" autocomplete="off" id="formulario-update-productos">
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="up-tipoproducto" class="form-label fw-semibold">Tipo:</label>
              <select class="form-select" name="up-tipoproducto" id="up-tipoproducto">
                <option value="">Seleccione</option>
                <option value="Bebida">Bebida</option>
                <option value="Entrada">Entrada</option>
                <option value="Plato de fondo">Plato de fondo</option>
                <option value="Postre">Postre</option>
              </select>
            </div>
            <div class="col-md-6 mb-2">
              <label for="up-nombreproducto" class="form-label fw-semibold">Nombre:</label>
              <input class="form-control" type="text" id="up-nombreproducto" maxlength="50">
            </div>
          </div>
          <div class="mb-2">
            <label for="up-descripcion" class="form-label fw-semibold">Descripción:</label>
            <textarea class="form-control" type="text" id="up-descripcion" maxlength="150" rows="3"></textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="up-precio" class="form-label fw-semibold">Precio:</label>
              <input class="form-control" type="number" id="up-precio" min="0">
            </div>
            <div class="col-md-6 mb-2">
              <label for="up-stock" class="form-label fw-semibold">Stock:</label>
              <input class="form-control" type="number" id="up-stock" min="1" max="100">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="up-guardar-datos">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script src="./js/producto.js"></script>