<h1 class="text-lg text-center">Informe de ventas</h1>
<h1 class="text-md text-center"><?=$titulo?></h1>

<table class="table table-border mt-3">
  <colgroup>
    <col style="width: 5%">
    <col style="width: 10%">
    <col style="width: 40%">
    <col style="width: 25%">
    <col style="width: 20%" class="text-end">
  </colgroup>
  <thead>
    <tr>
      <th>#</th>
      <th>Mesa</th>
      <th>Cliente</th>
      <th>Fecha y hora</th>
      <th>Monto</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($resultado as $fila): ?>
    <tr>
      <td><?=$fila['idventa']?></td>
      <td><?=$fila['nombremesa']?></td>
      <td><?=$fila['cliente']?></td>
      <td><?=$fila['fechahoraorden']?></td>
      <td><?=$fila['montopagado']?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>