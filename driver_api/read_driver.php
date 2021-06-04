<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."drivers.php");


$driver = new Drivers($conn);

$driver->phone = $_GET['driver_phone'];


//get the drivers bus
$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);

$driver_data = array();
$driver_data[] = $driver_row;


$num = $driver_result->rowCount();

if ($num > 0) {
	echo json_encode($driver_data);
}else{
	echo json_encode("error");
}


?>