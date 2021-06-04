<?php
//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");


$pickup = new PickupHistory($conn);

$pickup->adm_no = $_GET['adm_no'];

$results = $pickup->getKidsHistory();

$kids_pickup_data = array();

$num = $results->rowCount();

if ($num>0) {
	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
		$kids_pickup_data[] = $row;
	}
	echo json_encode($kids_pickup_data);
}else{
	echo json_encode([]);
}

?>