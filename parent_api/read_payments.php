<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."payments.php");
require_once(CORE_PATH.DS."parents.php");


$payments = new Payments($conn);
$parent = new Parents($conn);

$payments->adm_no = $_GET['adm_no'];


$payments_results = $payments->readFinances();

$results = array();

$num = $payments_results->rowCount();

if ($num > 0) {
	while ($row = $payments_results->fetch(PDO::FETCH_ASSOC)) {

	$results[]=$row;

}
	echo json_encode($results);
}else{
	echo json_encode([]);
}


?>