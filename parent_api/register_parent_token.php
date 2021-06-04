<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');

//initalize the API
include_once "../core/initialize.php";

require_once(CORE_PATH.DS."parent_tokens.php");


$parent_tokens = new ParentTokens($conn);

// $data = json_decode(file_get_contents("php://input"));
// $parent_tokens->phone = $data->phone;
// $parent_tokens->token = $data->token;

$parent_tokens->phone = $_POST['phone'];
$parent_tokens->token = $_POST['token'];


$check_phone_result = $parent_tokens->checkPhone();

$num = $check_phone_result->rowCount();

if ($num > 0) {
	$parent_tokens->updateToken();
	
}else{
	$parent_tokens->addToken();

}

?>