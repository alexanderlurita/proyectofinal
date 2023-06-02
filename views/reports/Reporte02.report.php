<?php

require_once '../../vendor/autoload.php';
require_once '../../models/Venta.model.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {

    //Instancia clase Venta
    $venta = new Venta();
    $datos = [
        "idempleado"   => $_GET["idempleado"],
        "idmesa"       => $_GET["idmesa"]
    ];
    $resultado = $venta->listarEmpleadoMesa($datos);
    $titulo = $_GET["titulo"];

    ob_start();

    //Hoja de estilos
    include '../../styles/reports.html';
    //Archivos con datos dinámicos
    include './Reporte02.data.php';
    
    $content = ob_get_clean();

    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content);
    $html2pdf->output('reporte02.pdf');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}