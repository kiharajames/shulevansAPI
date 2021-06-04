<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."pickup_history.php");
require_once(CORE_PATH.DS."drivers.php");


$driver = new Drivers($conn);

// $data = json_decode(file_get_contents('php://input'));
// $driver->phone = $data->driver_phone;
// $driver->newPhone = $data->newPhone;

$driver->phone = $_POST['driver_phone'];
$driver->newPhone = $_POST['newPhone'];


//get the drivers bus
$driver_result = $driver->changePhone();

if ($driver_result == true) {
	echo json_encode("1");
}else{
	echo json_encode("error");
}

?>