<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."driver_tokens.php");


$driver_tokens = new DriverTokens($conn);

// $data = json_decode(file_get_contents("php://input"));
// $driver_tokens->phone = $data->phone;
// $driver_tokens->token = $data->token;

$driver_tokens->phone = $_POST['phone'];
$driver_tokens->token = $_POST['token'];


$check_phone_result = $driver_tokens->checkPhone();

$num = $check_phone_result->rowCount();

if ($num > 0) {
	$driver_tokens->updateToken();

}else{
	$driver_tokens->addToken();

}


?>