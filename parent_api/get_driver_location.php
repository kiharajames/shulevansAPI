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

$driver_location->driver_phone = $_GET['driver_phone'];

$read_results = $driver_location->readLocation();

$num = $read_results->rowCount();

if ($num>0) {
	$row = $read_results->fetch(PDO::FETCH_ASSOC);
	$location = $row['location'];

	echo $location;
}else{
	echo json_encode("0");
}
?>