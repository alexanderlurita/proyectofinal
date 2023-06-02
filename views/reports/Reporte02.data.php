<h1 class="text-lg text-center">Informe de ventas</h1>
<h1 class="text-md text-center"><?=$titulo?></h1>

<table class="table table-border mt-3">
  <colgroup>
    <col style="width: 5%">
    <col style="width: 15%">
    <col style="width: 20%">
    <col style="width: 20%">
    <col style="width: 20%">
    <col style="width: 20%" class="text-end">
  </colgroup>
  <thead>
    <tr>
      <th>#</th>
      <th>Mesa</th>
      <th>Fecha orden</th>
      <th>Apertura</th>
      <th>Cierre</th>
      <th>Monto</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($resultado as $fila): ?>
    <tr>
      <td><?=$fila['idventa']?></td>
      <td><?=$fila['nombremesa']?></td>
      <td><?=$fila['fechaorden']?></td>
      <td><?=$fila['apertura']?></td>
      <td><?=$fila['cierre']?></td>
      <td><?=$fila['montopagado']?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>