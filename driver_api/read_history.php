<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."drivers.php");


$pickup_history = new PickupHistory($conn);
$driver = new Drivers($conn);

$driver->phone = $_GET['driver_phone'];


//get the drivers bus
$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);
$pickup_history->mybus = $driver_row['bus'];

$pickup_results = $pickup_history->getAllHistory();

$results = array();

$num = $pickup_results->rowCount();

if ($num > 0) {
	while ($row = $pickup_results->fetch(PDO::FETCH_ASSOC)) {

	$results[]=$row;

}
	echo json_encode($results);
}else{
	echo json_encode("nothing");
}


?>