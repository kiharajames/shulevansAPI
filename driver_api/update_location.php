<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."driver_location.php");

$driver_location = new DriverLocation($conn);

// $data = json_decode(file_get_contents("php://input"));
// $driver_location->driver_phone = $data->driver_phone;
// $driver_location->location = $data->location;

$driver_location->driver_phone = $_POST['driver_phone'];
$driver_location->location = $_POST['location'];

$check_results = $driver_location->checkIfDriverExists();

$num = $check_results->rowCount();

if ($num>0) {
	$update_results = $driver_location->updateLocation();
	$num = $update_results->rowCount();
	if ($num>0) {
		echo json_encode("1");
	}else{
		echo json_encode("0");
	}
	
}else{
	$add_results = $driver_location->addLocation();
	$num = $add_results->rowCount();
	if ($num>0) {
		echo json_encode("1");
	}else{
		echo json_encode("0");
	}
}
?>