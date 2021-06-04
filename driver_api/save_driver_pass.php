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
// $driver->currentPass = $data->currentPass;
// $newPass = $data->newPass;

$driver->phone = $_POST['driver_phone'];
$driver->currentPass = $_POST['currentPass'];
$newPass = $_POST['newPass'];


$currentPass = $driver->currentPass;
$currentPass = md5($currentPass);

//get the pass saved in the db
$driver_result = $driver->read_single();
$driver_row = $driver_result->fetch(PDO::FETCH_ASSOC);
$savedPass = $driver_row['login'];

//compare the pass entered and the saved pass
if ($currentPass == $savedPass) {
	$driver->newPass = md5($newPass);

	//change the pass
	$driver_result = $driver->changePassword();

	if ($driver_result == true) {
		echo json_encode("1");
	}else{
		echo json_encode("error");
	}

}else{
	echo json_encode("incorrect");
}


?>