<?php
require_once dirname(__DIR__) . '/../../' . 'vendor/autoload.php';
require_once dirname(__DIR__) . '/../../' . 'src/config.php';
require_once dirname(__DIR__) . '/../../' . 'src/connection.php';
require_once dirname(__DIR__) . '/../../' . 'src/functions/orders.php';

use Dompdf\Dompdf;

$order_id = $_GET['id'] ?? 0;

$order = getOrderById($order_id);


$dompdf = new Dompdf();

ob_start();
require('order_pdf.php');
$html = ob_get_contents();
ob_get_clean();

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('order_pdf.pdf', ['Attachment' => false]);
