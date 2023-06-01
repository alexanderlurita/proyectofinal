<?php

require_once '../models/Venta.model.php';

if (isset($_POST['operacion'])) {

  $venta = new Venta();

  if ($_POST['operacion'] == 'buscar') {
    $datos = $venta->buscar($_POST['idventa']);
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'registrar') {
    $datos = [
      "idmesa"      => $_POST["idmesa"],
      "idempleado"  => $_POST["idempleado"]
    ];
    $resultado = $venta->registrar($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'registrarDetalle') {
    $datos = [
      "idproducto"      => $_POST["idproducto"],
      "cantidad"        => $_POST["cantidad"],
      "precioproducto"  => $_POST["precioproducto"]
    ];
    $resultado = $venta->registrarDetalle($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'obtenerIdVentaPorMesa') {
    $datos = $venta->obtenerIdVentaPorMesa($_POST['idmesa']);
    echo json_encode($datos);
  }

  if ($_POST['operacion'] == 'detallar') {
    $datos = [
      "idventa" => $_POST["idventa"],
      "idmesa"  => $_POST["idmesa"]
    ];
    $resultado = $venta->detallar($datos);
    echo json_encode($resultado);
  }

  if ($_POST['operacion'] == 'realizarPago') {
    $datos = [
      "idventa"         => $_POST["idventa"],
      "apellidos"       => $_POST["apellidos"],
      "nombres"         => $_POST["nombres"],
      "dni"             => $_POST["dni"],
      "tipocomprobante" => $_POST["tipocomprobante"],
      "metodopago"      => $_POST["metodopago"],
      "montopagado"     => $_POST["montopagado"]
    ];
    $resultado = $venta->realizarPago($datos);
    echo json_encode($resultado);
  }

  // Operaciones para gráficos con ChartJS
  if ($_POST['operacion'] == 'obtenerVentasTipo') {
    $datos = $venta->obtenerVentasTipo();
    echo json_encode($datos);
  }
  
  if ($_POST['operacion'] == 'obtenerVentasEmpleado') {
    $datos = $venta->obtenerVentasEmpleado();
    echo json_encode($datos);
  }

  // Operaciones para reportes
  if ($_POST['operacion'] == 'listarRangoFechas') {
    $datos = [
      "fechainicio"   => $_POST["fechainicio"],
      "fechafin"      => $_POST["fechafin"]
    ];
    $resultado = $venta->listarRangoFechas($datos);
    echo json_encode($resultado);
  }

}

?>