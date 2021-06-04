<?php
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."invoices.php");


$invoice = new Invoices($conn);

$invoice->adm_no = $_GET['adm_no'];

$results = $invoice->read_kids_invoice();

$kids_invoice_data = array();

$num = $results->rowCount();

if ($num>0) {
	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
		$kids_invoice_data[] = $row;
	}
	echo json_encode($kids_invoice_data);
}else{
	echo json_encode([]);
}

?>